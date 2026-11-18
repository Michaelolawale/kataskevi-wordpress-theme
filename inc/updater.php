<?php
/**
 * Theme Update Checker Integration & Admin UI
 *
 * Implements YahnisElsts Plugin Update Checker (PUC) v6.0 for automatic
 * theme updates from Bitbucket repository (https://bitbucket.org/unionolivia/kataskevi).
 * Includes an administrative backend interface under Appearance -> Theme Updates
 * allowing administrators to configure private repository credentials, test connectivity,
 * inspect release metadata (readme.txt Stable tag & Git tags), and check for theme releases.
 *
 * Adheres strictly to the WordPress PHP Coding Standards (WPCS).
 *
 * @package Kataskevi
 * @version 2.2.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use YahnisElsts\PluginUpdateChecker\v6\PucFactory;

/**
 * Initialize Plugin Update Checker for Kataskevi theme.
 *
 * @return \YahnisElsts\PluginUpdateChecker\v6p0\Theme\UpdateChecker|\YahnisElsts\PluginUpdateChecker\v6p0\Vcs\ThemeUpdateChecker|null
 */
function kataskevi_init_update_checker() {
	$puc_bootstrap = KATASKEVI_DIR . '/inc/plugin-update-checker/plugin-update-checker.php';

	if ( ! file_exists( $puc_bootstrap ) ) {
		return null;
	}

	require_once $puc_bootstrap;

	if ( ! class_exists( PucFactory::class ) ) {
		return null;
	}

	// Retrieve repository URL and branch from settings or fallback constants.
	$repo_url = get_option( 'kataskevi_bitbucket_repo', '' );
	if ( empty( $repo_url ) ) {
		$repo_url = defined( 'KATASKEVI_REPO_URL' ) ? KATASKEVI_REPO_URL : 'https://bitbucket.org/unionolivia/kataskevi';
	}

	$branch = get_option( 'kataskevi_bitbucket_branch', '' );
	if ( empty( $branch ) ) {
		$branch = defined( 'KATASKEVI_UPDATE_BRANCH' ) ? KATASKEVI_UPDATE_BRANCH : 'main';
	}

	/**
	 * Build theme update checker pointing to Bitbucket repository.
	 *
	 * First arg:  Repository URL
	 * Second arg: Main theme file (style.css or functions.php)
	 * Third arg:  Theme slug ('kataskevi')
	 */
	$update_checker = PucFactory::buildUpdateChecker(
		$repo_url,
		KATASKEVI_DIR . '/style.css',
		'kataskevi'
	);

	if ( ! $update_checker ) {
		return null;
	}

	// Set active branch for release updates.
	if ( method_exists( $update_checker, 'setBranch' ) ) {
		$update_checker->setBranch( $branch );
	}

	/**
	 * Configure Bitbucket Authentication for private repositories.
	 * Priority order:
	 * 1. Database options saved via the backend UI.
	 * 2. Constants defined in wp-config.php.
	 * 3. Filter hook 'kataskevi_bitbucket_credentials'.
	 */
	$auth_creds = array();

	$opt_user  = get_option( 'kataskevi_bitbucket_user', '' );
	$opt_token = get_option( 'kataskevi_bitbucket_token', '' );

	if ( ! empty( $opt_user ) && ! empty( $opt_token ) ) {
		$auth_creds = array(
			'username'  => $opt_user,
			'api_token' => $opt_token,
		);
	} elseif ( defined( 'KATASKEVI_BITBUCKET_USER' ) && defined( 'KATASKEVI_BITBUCKET_TOKEN' ) ) {
		$auth_creds = array(
			'username'  => KATASKEVI_BITBUCKET_USER,
			'api_token' => KATASKEVI_BITBUCKET_TOKEN,
		);
	} elseif ( defined( 'KATASKEVI_BITBUCKET_TOKEN' ) ) {
		$auth_creds = array(
			'api_token' => KATASKEVI_BITBUCKET_TOKEN,
		);
	}

	// Auto-extract or fallback username if only api_token was provided.
	if ( empty( $auth_creds['username'] ) && ! empty( $auth_creds['api_token'] ) ) {
		$path = (string) wp_parse_url( $repo_url, PHP_URL_PATH );
		if ( preg_match( '@^/?(?P<username>[^/]+?)/(?P<repository>[^/#?&]+?)/?$@', $path, $matches ) ) {
			$auth_creds['username'] = $matches['username'];
		} else {
			$auth_creds['username'] = '';
		}
	}

	$auth_creds = apply_filters( 'kataskevi_bitbucket_credentials', $auth_creds, $update_checker );

	if ( ! empty( $auth_creds ) && method_exists( $update_checker, 'setAuthentication' ) ) {
		$update_checker->setAuthentication( $auth_creds );
	}

	/**
	 * Allow third-party code or plugins to customize the update checker instance.
	 */
	do_action( 'kataskevi_update_checker_loaded', $update_checker );

	return $update_checker;
}

// Global instance of the update checker.
global $kataskevi_update_checker;
$kataskevi_update_checker = kataskevi_init_update_checker();

/**
 * Register the Theme Updates administrative page under Appearance.
 *
 * @return void
 */
function kataskevi_register_updater_menu() {
	add_theme_page(
		esc_html__( 'Kataskevi Updates', 'kataskevi' ),
		esc_html__( 'Theme Updates', 'kataskevi' ),
		'manage_options',
		'kataskevi-updates',
		'kataskevi_render_updater_page'
	);
}
add_action( 'admin_menu', 'kataskevi_register_updater_menu' );

/**
 * Perform a live diagnostic test against the Bitbucket API.
 *
 * @return array Diagnostic details.
 */
function kataskevi_test_bitbucket_connection() {
	$repo_url = get_option( 'kataskevi_bitbucket_repo', 'https://bitbucket.org/unionolivia/kataskevi' );
	$branch   = get_option( 'kataskevi_bitbucket_branch', 'main' );
	$user     = get_option( 'kataskevi_bitbucket_user', '' );
	$token    = get_option( 'kataskevi_bitbucket_token', '' );

	if ( empty( $user ) && defined( 'KATASKEVI_BITBUCKET_USER' ) ) {
		$user = KATASKEVI_BITBUCKET_USER;
	}
	if ( empty( $token ) && defined( 'KATASKEVI_BITBUCKET_TOKEN' ) ) {
		$token = KATASKEVI_BITBUCKET_TOKEN;
	}

	$path = (string) wp_parse_url( $repo_url, PHP_URL_PATH );
	if ( preg_match( '@^/?(?P<owner>[^/]+?)/(?P<repo>[^/#?&]+?)/?$@', $path, $matches ) ) {
		$owner = $matches['owner'];
		$repo  = $matches['repo'];
	} else {
		$owner = '';
		$repo  = '';
	}

	if ( empty( $user ) && ! empty( $token ) ) {
		$user = $owner;
	}

	$results = array(
		'timestamp'        => current_time( 'mysql' ),
		'repo_url'         => $repo_url,
		'branch'           => $branch,
		'owner'            => $owner,
		'repo'             => $repo,
		'user'             => $user,
		'has_token'        => ! empty( $token ),
		'api_accessible'   => false,
		'http_code'        => 0,
		'error_message'    => '',
		'readme_found'     => false,
		'stable_tag'       => '',
		'latest_git_tag'   => '',
		'all_tags'         => array(),
		'remote_style_ver' => '',
	);

	$args = array(
		'timeout' => 15,
		'headers' => array(
			'Accept' => 'application/json',
		),
	);

	if ( ! empty( $user ) && ! empty( $token ) ) {
		$args['headers']['Authorization'] = 'Basic ' . base64_encode( $user . ':' . $token );
	}

	// 1. Check Repository Access.
	$repo_api_url = "https://api.bitbucket.org/2.0/repositories/{$owner}/{$repo}";
	$response     = wp_remote_get( $repo_api_url, $args );

	if ( is_wp_error( $response ) ) {
		$results['error_message'] = $response->get_error_message();
		return $results;
	}

	$code                 = wp_remote_retrieve_response_code( $response );
	$results['http_code'] = $code;

	if ( 200 === $code ) {
		$results['api_accessible'] = true;
	} else {
		$body = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( isset( $body['error']['message'] ) ) {
			$results['error_message'] = sanitize_text_field( $body['error']['message'] );
		} else {
			$results['error_message'] = sprintf( 'Bitbucket API returned HTTP %d', $code );
		}
		return $results;
	}

	// 2. Check readme.txt on branch.
	$readme_url  = "https://api.bitbucket.org/2.0/repositories/{$owner}/{$repo}/src/{$branch}/readme.txt";
	$readme_resp = wp_remote_get( $readme_url, $args );
	if ( ! is_wp_error( $readme_resp ) && 200 === wp_remote_retrieve_response_code( $readme_resp ) ) {
		$readme_body             = wp_remote_retrieve_body( $readme_resp );
		$results['readme_found'] = true;
		if ( preg_match( '/^[ \t\/*#@]*Stable tag:[ \t]*(.+?)\s*$/mi', $readme_body, $m ) ) {
			$results['stable_tag'] = trim( $m[1] );
		}
	}

	// 3. Query Tags.
	$tags_url  = "https://api.bitbucket.org/2.0/repositories/{$owner}/{$repo}/refs/tags?sort=-target.date";
	$tags_resp = wp_remote_get( $tags_url, $args );
	if ( ! is_wp_error( $tags_resp ) && 200 === wp_remote_retrieve_response_code( $tags_resp ) ) {
		$tags_body = json_decode( wp_remote_retrieve_body( $tags_resp ), true );
		if ( isset( $tags_body['values'] ) && is_array( $tags_body['values'] ) ) {
			foreach ( $tags_body['values'] as $tag_obj ) {
				if ( isset( $tag_obj['name'] ) ) {
					$results['all_tags'][] = sanitize_text_field( $tag_obj['name'] );
				}
			}
			if ( ! empty( $results['all_tags'] ) ) {
				$results['latest_git_tag'] = $results['all_tags'][0];
			}
		}
	}

	// 4. Query style.css on branch.
	$style_url  = "https://api.bitbucket.org/2.0/repositories/{$owner}/{$repo}/src/{$branch}/style.css";
	$style_resp = wp_remote_get( $style_url, $args );
	if ( ! is_wp_error( $style_resp ) && 200 === wp_remote_retrieve_response_code( $style_resp ) ) {
		$style_body = wp_remote_retrieve_body( $style_resp );
		if ( preg_match( '/^[ \t\/*#@]*Version:[ \t]*(.+?)\s*$/mi', $style_body, $m ) ) {
			$results['remote_style_ver'] = trim( $m[1] );
		}
	}

	return $results;
}

/**
 * Handle form submissions and manual update checks for the updater page.
 *
 * @return void
 */
function kataskevi_handle_updater_actions() {
	if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// Check if this is the updates page.
	$page = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';
	if ( 'kataskevi-updates' !== $page ) {
		return;
	}

	// 1. Handle Saving Bitbucket Credentials Form.
	if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' === $_SERVER['REQUEST_METHOD'] && isset( $_POST['kataskevi_save_repo_settings'] ) ) {
		check_admin_referer( 'kataskevi_repo_settings_nonce', 'kataskevi_repo_nonce' );

		$repo_url = isset( $_POST['kataskevi_bitbucket_repo'] ) ? esc_url_raw( wp_unslash( $_POST['kataskevi_bitbucket_repo'] ) ) : '';
		$branch   = isset( $_POST['kataskevi_bitbucket_branch'] ) ? sanitize_text_field( wp_unslash( $_POST['kataskevi_bitbucket_branch'] ) ) : '';
		$user     = isset( $_POST['kataskevi_bitbucket_user'] ) ? sanitize_text_field( wp_unslash( $_POST['kataskevi_bitbucket_user'] ) ) : '';
		$token    = isset( $_POST['kataskevi_bitbucket_token'] ) ? sanitize_text_field( wp_unslash( $_POST['kataskevi_bitbucket_token'] ) ) : '';

		if ( empty( $repo_url ) ) {
			$repo_url = 'https://bitbucket.org/unionolivia/kataskevi';
		}
		if ( empty( $branch ) ) {
			$branch = 'main';
		}

		update_option( 'kataskevi_bitbucket_repo', $repo_url );
		update_option( 'kataskevi_bitbucket_branch', $branch );
		update_option( 'kataskevi_bitbucket_user', $user );

		// Only update token if a new one was entered (preserves masked tokens).
		if ( ! empty( $token ) && '********' !== $token ) {
			update_option( 'kataskevi_bitbucket_token', $token );
		}

		// Also run a test connection right after saving so the user gets instant feedback.
		$diag = kataskevi_test_bitbucket_connection();
		set_transient( 'kataskevi_diag_results', $diag, 300 );

		wp_safe_redirect( add_query_arg( array( 'page' => 'kataskevi-updates', 'tab' => 'updates', 'updated' => '1', 'diag_run' => '1' ), admin_url( 'themes.php' ) ) );
		exit;
	}

	// 2. Handle Saving Contact Google Map Settings Form.
	if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' === $_SERVER['REQUEST_METHOD'] && isset( $_POST['kataskevi_save_map_settings'] ) ) {
		check_admin_referer( 'kataskevi_map_settings_nonce', 'kataskevi_map_nonce' );

		$show_map    = isset( $_POST['kataskevi_contact_show_map'] ) ? 1 : 0;
		$map_eyebrow = isset( $_POST['kataskevi_contact_map_eyebrow'] ) ? sanitize_text_field( wp_unslash( $_POST['kataskevi_contact_map_eyebrow'] ) ) : 'Visit Our Office';
		$map_title   = isset( $_POST['kataskevi_contact_map_title'] ) ? sanitize_text_field( wp_unslash( $_POST['kataskevi_contact_map_title'] ) ) : 'Engineering Headquarters & Consultation Hub';
		$map_desc    = isset( $_POST['kataskevi_contact_map_desc'] ) ? sanitize_textarea_field( wp_unslash( $_POST['kataskevi_contact_map_desc'] ) ) : '';
		$raw_embed   = isset( $_POST['kataskevi_contact_map_embed'] ) ? wp_unslash( $_POST['kataskevi_contact_map_embed'] ) : '';

		// Parse into clean embed URL.
		$clean_embed = function_exists( 'kataskevi_parse_map_url' ) ? kataskevi_parse_map_url( $raw_embed ) : esc_url_raw( trim( $raw_embed ) );

		update_option( 'kataskevi_contact_show_map', $show_map );
		update_option( 'kataskevi_contact_map_eyebrow', $map_eyebrow );
		update_option( 'kataskevi_contact_map_title', $map_title );
		update_option( 'kataskevi_contact_map_desc', $map_desc );
		update_option( 'kataskevi_contact_map_embed', $clean_embed );

		// Sync with theme mod so any legacy calls continue to receive updated values.
		set_theme_mod( 'kataskevi_contact_show_map', (bool) $show_map );
		set_theme_mod( 'kataskevi_contact_map_eyebrow', $map_eyebrow );
		set_theme_mod( 'kataskevi_contact_map_title', $map_title );
		set_theme_mod( 'kataskevi_contact_map_desc', $map_desc );
		set_theme_mod( 'kataskevi_contact_map_embed', $clean_embed );

		wp_safe_redirect( add_query_arg( array( 'page' => 'kataskevi-updates', 'tab' => 'map', 'map_updated' => '1' ), admin_url( 'themes.php' ) ) );
		exit;
	}

	// 3. Handle Saving Consultation Form Settings.
	if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' === $_SERVER['REQUEST_METHOD'] && isset( $_POST['kataskevi_save_form_settings'] ) ) {
		check_admin_referer( 'kataskevi_form_settings_nonce', 'kataskevi_form_nonce' );

		$recipient_email = isset( $_POST['kataskevi_form_recipient_email'] ) ? sanitize_email( wp_unslash( $_POST['kataskevi_form_recipient_email'] ) ) : '';
		$subject_prefix  = isset( $_POST['kataskevi_form_email_subject'] ) ? sanitize_text_field( wp_unslash( $_POST['kataskevi_form_email_subject'] ) ) : '';
		$submit_label    = isset( $_POST['kataskevi_form_submit_label'] ) ? sanitize_text_field( wp_unslash( $_POST['kataskevi_form_submit_label'] ) ) : '';
		$success_msg     = isset( $_POST['kataskevi_form_success_msg'] ) ? sanitize_textarea_field( wp_unslash( $_POST['kataskevi_form_success_msg'] ) ) : '';
		$form_title      = isset( $_POST['kataskevi_form_title'] ) ? sanitize_text_field( wp_unslash( $_POST['kataskevi_form_title'] ) ) : '';
		$form_desc       = isset( $_POST['kataskevi_form_desc'] ) ? sanitize_textarea_field( wp_unslash( $_POST['kataskevi_form_desc'] ) ) : '';
		$show_phone      = isset( $_POST['kataskevi_form_show_phone'] ) ? 1 : 0;
		$show_org        = isset( $_POST['kataskevi_form_show_org'] ) ? 1 : 0;
		$show_service    = isset( $_POST['kataskevi_form_show_service'] ) ? 1 : 0;

		update_option( 'kataskevi_form_recipient_email', $recipient_email );
		update_option( 'kataskevi_form_email_subject', $subject_prefix );
		update_option( 'kataskevi_form_submit_label', $submit_label );
		update_option( 'kataskevi_form_success_msg', $success_msg );
		update_option( 'kataskevi_form_title', $form_title );
		update_option( 'kataskevi_form_desc', $form_desc );
		update_option( 'kataskevi_form_show_phone', $show_phone );
		update_option( 'kataskevi_form_show_org', $show_org );
		update_option( 'kataskevi_form_show_service', $show_service );

		// Sync with theme mod
		set_theme_mod( 'kataskevi_form_recipient_email', $recipient_email );
		set_theme_mod( 'kataskevi_form_email_subject', $subject_prefix );
		set_theme_mod( 'kataskevi_form_submit_label', $submit_label );
		set_theme_mod( 'kataskevi_form_success_msg', $success_msg );
		set_theme_mod( 'kataskevi_form_title', $form_title );
		set_theme_mod( 'kataskevi_form_desc', $form_desc );
		set_theme_mod( 'kataskevi_form_show_phone', (bool) $show_phone );
		set_theme_mod( 'kataskevi_form_show_org', (bool) $show_org );
		set_theme_mod( 'kataskevi_form_show_service', (bool) $show_service );

		wp_safe_redirect( add_query_arg( array( 'page' => 'kataskevi-updates', 'tab' => 'forms', 'form_updated' => '1' ), admin_url( 'themes.php' ) ) );
		exit;
	}

	// 4. Handle Clearing Recent Inquiries Log.
	if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' === $_SERVER['REQUEST_METHOD'] && isset( $_POST['kataskevi_clear_inquiries'] ) ) {
		check_admin_referer( 'kataskevi_clear_inquiries_nonce', 'kataskevi_clear_nonce' );

		delete_option( 'kataskevi_recent_inquiries' );

		wp_safe_redirect( add_query_arg( array( 'page' => 'kataskevi-updates', 'tab' => 'forms', 'inquiries_cleared' => '1' ), admin_url( 'themes.php' ) ) );
		exit;
	}

	// 5. Handle Manual "Check for Updates" Button.
	if ( isset( $_GET['action'] ) && 'check_now' === $_GET['action'] ) {
		check_admin_referer( 'kataskevi_manual_check_nonce' );

		global $kataskevi_update_checker;

		// Purge update transients to prevent caching old results.
		delete_site_transient( 'update_themes' );

		if ( $kataskevi_update_checker && method_exists( $kataskevi_update_checker, 'checkForUpdates' ) ) {
			$kataskevi_update_checker->resetUpdateState();
			$update = $kataskevi_update_checker->checkForUpdates();
			$errors = method_exists( $kataskevi_update_checker, 'getLastRequestApiErrors' ) ? $kataskevi_update_checker->getLastRequestApiErrors() : array();

			if ( ! empty( $errors ) ) {
				$status    = 'check_error';
				$error_msg = '';
				if ( isset( $errors[0]['error'] ) && is_wp_error( $errors[0]['error'] ) ) {
					$error_msg = $errors[0]['error']->get_error_message();
				}
				set_transient( 'kataskevi_update_error_notice', $error_msg, 60 );
			} elseif ( $update ) {
				$status = 'update_available';
			} else {
				$status = 'up_to_date';
			}
		} else {
			$status = 'check_error';
		}

		// Also refresh diagnostic check.
		$diag = kataskevi_test_bitbucket_connection();
		set_transient( 'kataskevi_diag_results', $diag, 300 );

		wp_safe_redirect( add_query_arg( array( 'page' => 'kataskevi-updates', 'tab' => 'updates', 'check_result' => $status, 'diag_run' => '1' ), admin_url( 'themes.php' ) ) );
		exit;
	}

	// 6. Handle Diagnostic "Test Connection" Button.
	if ( isset( $_GET['action'] ) && 'test_connection' === $_GET['action'] ) {
		check_admin_referer( 'kataskevi_test_connection_nonce' );

		$diag = kataskevi_test_bitbucket_connection();
		set_transient( 'kataskevi_diag_results', $diag, 300 );

		wp_safe_redirect( add_query_arg( array( 'page' => 'kataskevi-updates', 'tab' => 'updates', 'diag_run' => '1' ), admin_url( 'themes.php' ) ) );
		exit;
	}
}
add_action( 'admin_init', 'kataskevi_handle_updater_actions' );

/**
 * Render the Theme Updates and Settings administrative page.
 *
 * @return void
 */
function kataskevi_render_updater_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'kataskevi' ) );
	}

	global $kataskevi_update_checker;

	$theme         = wp_get_theme( 'kataskevi' );
	$current_ver   = $theme->exists() ? $theme->get( 'Version' ) : KATASKEVI_VERSION;
	$saved_repo    = get_option( 'kataskevi_bitbucket_repo', 'https://bitbucket.org/unionolivia/kataskevi' );
	$saved_branch  = get_option( 'kataskevi_bitbucket_branch', 'main' );
	$saved_user    = get_option( 'kataskevi_bitbucket_user', '' );
	$saved_token   = get_option( 'kataskevi_bitbucket_token', '' );
	$is_configured = ! empty( $saved_user ) && ! empty( $saved_token );

	// Determine if wp-config.php constants are overriding settings.
	$has_const_user  = defined( 'KATASKEVI_BITBUCKET_USER' );
	$has_const_token = defined( 'KATASKEVI_BITBUCKET_TOKEN' );

	$active_tab   = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'forms';
	$check_result = isset( $_GET['check_result'] ) ? sanitize_text_field( wp_unslash( $_GET['check_result'] ) ) : '';
	$is_updated   = isset( $_GET['updated'] ) && '1' === $_GET['updated'];
	$form_updated = isset( $_GET['form_updated'] ) && '1' === $_GET['form_updated'];
	$inq_cleared  = isset( $_GET['inquiries_cleared'] ) && '1' === $_GET['inquiries_cleared'];
	$map_updated  = isset( $_GET['map_updated'] ) && '1' === $_GET['map_updated'];
	$diag_run     = isset( $_GET['diag_run'] ) && '1' === $_GET['diag_run'];
	$diag_results = get_transient( 'kataskevi_diag_results' );
	$error_notice = get_transient( 'kataskevi_update_error_notice' );

	// Form Settings Options.
	$form_email     = get_option( 'kataskevi_form_recipient_email', get_theme_mod( 'kataskevi_form_recipient_email', get_theme_mod( 'kataskevi_email', get_option( 'admin_email' ) ) ) );
	$form_subject   = get_option( 'kataskevi_form_email_subject', get_theme_mod( 'kataskevi_form_email_subject', '[Kataskevi Inquiry] New Consultation Request' ) );
	$form_submit    = get_option( 'kataskevi_form_submit_label', get_theme_mod( 'kataskevi_form_submit_label', 'Submit Consultation Request' ) );
	$form_success   = get_option( 'kataskevi_form_success_msg', get_theme_mod( 'kataskevi_form_success_msg', 'Thank you! Your inquiry has been received. A Kataskevi technical specialist will contact you within 24 hours.' ) );
	$form_title     = get_option( 'kataskevi_form_title', get_theme_mod( 'kataskevi_form_title', '' ) );
	$form_desc      = get_option( 'kataskevi_form_desc', get_theme_mod( 'kataskevi_form_desc', '' ) );
	$form_phone     = (bool) get_option( 'kataskevi_form_show_phone', get_theme_mod( 'kataskevi_form_show_phone', 1 ) );
	$form_org       = (bool) get_option( 'kataskevi_form_show_org', get_theme_mod( 'kataskevi_form_show_org', 1 ) );
	$form_service   = (bool) get_option( 'kataskevi_form_show_service', get_theme_mod( 'kataskevi_form_show_service', 1 ) );
	$inquiries_list = get_option( 'kataskevi_recent_inquiries', array() );
	if ( ! is_array( $inquiries_list ) ) {
		$inquiries_list = array();
	}

	// Map Options.
	$default_map_embed = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2844.38140054598!2d3.4053212!3d6.4476227!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x103b8b248e126613%3A0x58cb7affcae5e3a7!2s48%20King%20George%20V%20Rd%2C%20Lagos%20Island%2C%20Lagos%20234001%2C%20Lagos!5e1!3m2!1sen!2sng!4v1794913471007!5m2!1sen!2sng';
	$saved_show_map    = get_option( 'kataskevi_contact_show_map', get_theme_mod( 'kataskevi_contact_show_map', 1 ) );
	$saved_map_eyebrow = get_option( 'kataskevi_contact_map_eyebrow', get_theme_mod( 'kataskevi_contact_map_eyebrow', 'Visit Our Office' ) );
	$saved_map_title   = get_option( 'kataskevi_contact_map_title', get_theme_mod( 'kataskevi_contact_map_title', 'Engineering Headquarters & Consultation Hub' ) );
	$saved_map_desc    = get_option( 'kataskevi_contact_map_desc', get_theme_mod( 'kataskevi_contact_map_desc', 'Located in Victoria Island, Lagos. We welcome blueprint review meetings, technical audits, and project consultations.' ) );
	$saved_map_embed   = get_option( 'kataskevi_contact_map_embed', get_theme_mod( 'kataskevi_contact_map_embed', $default_map_embed ) );
	if ( empty( $saved_map_embed ) ) {
		$saved_map_embed = $default_map_embed;
	}
	?>
	<div class="wrap kataskevi-admin-wrap" style="max-width: 1040px; margin-top: 1.5rem;">
		
		<style type="text/css">
			.kataskevi-admin-wrap {
				font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
			}
			.kataskevi-header-badge {
				display: inline-flex;
				align-items: center;
				justify-content: center;
				width: 38px;
				height: 38px;
				background: linear-gradient(135deg, #3f75a1 0%, #2f597c 100%);
				border-radius: 8px;
				color: #fff;
				font-weight: 700;
				font-size: 1.2rem;
				box-shadow: 0 2px 6px rgba(63, 117, 161, 0.25);
			}
			.kataskevi-nav-tabs {
				margin: 1.25rem 0 1.5rem 0;
				display: flex;
				gap: 4px;
				border-bottom: 2px solid #e2e8f0;
			}
			.kataskevi-tab-item {
				display: inline-flex;
				align-items: center;
				gap: 6px;
				padding: 10px 18px;
				font-size: 14px;
				font-weight: 600;
				color: #64748b;
				text-decoration: none;
				border-bottom: 2px solid transparent;
				margin-bottom: -2px;
				transition: all 0.2s ease;
			}
			.kataskevi-tab-item:hover {
				color: #3f75a1;
			}
			.kataskevi-tab-item.active {
				color: #3f75a1;
				border-bottom-color: #3f75a1;
				background: transparent;
			}
			.kataskevi-card {
				background: #ffffff;
				border: 1px solid #e2e8f0;
				border-radius: 10px;
				box-shadow: 0 1px 3px rgba(0,0,0,0.04);
				padding: 22px 26px;
				margin-bottom: 24px;
			}
			.kataskevi-card-title {
				margin-top: 0;
				font-size: 1.15rem;
				font-weight: 600;
				color: #1e293b;
				border-bottom: 1px solid #f1f5f9;
				padding-bottom: 12px;
				display: flex;
				align-items: center;
				gap: 8px;
			}
			.kataskevi-badge {
				display: inline-flex;
				align-items: center;
				gap: 4px;
				padding: 3px 10px;
				border-radius: 999px;
				font-size: 0.8rem;
				font-weight: 600;
			}
			.kataskevi-badge-success { background: #dcfce7; color: #15803d; }
			.kataskevi-badge-warning { background: #fef3c7; color: #b45309; }
			.kataskevi-badge-info    { background: #e0f2fe; color: #0369a1; }
			.kataskevi-badge-danger  { background: #fee2e2; color: #991b1b; }
			.kataskevi-btn-primary {
				background: #3f75a1 !important;
				border-color: #3f75a1 !important;
				color: #fff !important;
				font-weight: 500 !important;
				border-radius: 6px !important;
				padding: 5px 16px !important;
				height: auto !important;
				transition: background 0.15s ease !important;
			}
			.kataskevi-btn-primary:hover,
			.kataskevi-btn-primary:focus {
				background: #346389 !important;
				border-color: #346389 !important;
				color: #fff !important;
			}
			.kataskevi-table {
				width: 100%;
				border-collapse: collapse;
			}
			.kataskevi-table th {
				background: #f8fafc;
				padding: 10px 14px;
				font-size: 12px;
				font-weight: 600;
				color: #475569;
				text-transform: uppercase;
				letter-spacing: 0.04em;
				border-bottom: 1px solid #e2e8f0;
				text-align: left;
			}
			.kataskevi-table td {
				padding: 12px 14px;
				font-size: 13.5px;
				color: #1e293b;
				border-bottom: 1px solid #f1f5f9;
				vertical-align: top;
			}
			.kataskevi-table tr:hover td {
				background-color: #fafbfc;
			}
		</style>

		<!-- HEADER -->
		<div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 0.5rem;">
			<h1 style="display: flex; align-items: center; gap: 0.75rem; margin: 0; font-size: 1.5rem; font-weight: 700; color: #0f172a;">
				<span class="kataskevi-header-badge">K</span>
				<span><?php esc_html_e( 'Kataskevi — Theme Settings & Updates', 'kataskevi' ); ?></span>
			</h1>
			<div style="display: flex; align-items: center; gap: 8px;">
				<span class="kataskevi-badge kataskevi-badge-info">
					v<?php echo esc_html( $current_ver ); ?>
				</span>
				<a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button">
					🎨 <?php esc_html_e( 'Open Customizer', 'kataskevi' ); ?>
				</a>
			</div>
		</div>
		<p class="description" style="margin-bottom: 1.25rem; font-size: 0.95rem; color: #64748b;">
			<?php esc_html_e( 'Configure automated Bitbucket theme updates, manage inquiry form routing and consultation leads, and customize your office Google Maps embed.', 'kataskevi' ); ?>
		</p>

		<!-- TAB NAVIGATION -->
		<nav class="kataskevi-nav-tabs" aria-label="<?php esc_attr_e( 'Theme Settings Tabs', 'kataskevi' ); ?>">
			<a href="<?php echo esc_url( admin_url( 'themes.php?page=kataskevi-updates&tab=forms' ) ); ?>" class="kataskevi-tab-item <?php echo 'forms' === $active_tab ? 'active' : ''; ?>">
				📩 <span><?php esc_html_e( 'Form Settings & Leads', 'kataskevi' ); ?></span>
				<?php if ( ! empty( $inquiries_list ) ) : ?>
					<span class="kataskevi-badge kataskevi-badge-info" style="font-size: 11px; padding: 1px 7px;">
						<?php echo count( $inquiries_list ); ?>
					</span>
				<?php endif; ?>
			</a>
			<a href="<?php echo esc_url( admin_url( 'themes.php?page=kataskevi-updates&tab=map' ) ); ?>" class="kataskevi-tab-item <?php echo 'map' === $active_tab ? 'active' : ''; ?>">
				📍 <span><?php esc_html_e( 'Google Maps Location', 'kataskevi' ); ?></span>
			</a>
			<a href="<?php echo esc_url( admin_url( 'themes.php?page=kataskevi-updates&tab=updates' ) ); ?>" class="kataskevi-tab-item <?php echo 'updates' === $active_tab ? 'active' : ''; ?>">
				🔄 <span><?php esc_html_e( 'Theme Updates & Git Repository', 'kataskevi' ); ?></span>
			</a>
		</nav>

		<!-- NOTICES -->
		<?php if ( $is_updated ) : ?>
			<div class="notice notice-success is-dismissible" style="border-left-color: #3f75a1;">
				<p><strong>✅ <?php esc_html_e( 'Repository settings and credentials saved successfully.', 'kataskevi' ); ?></strong></p>
			</div>
		<?php endif; ?>

		<?php if ( $form_updated ) : ?>
			<div class="notice notice-success is-dismissible" style="border-left-color: #10b981;">
				<p><strong>✅ <?php esc_html_e( 'Form settings updated! Client inquiries will now follow your configured notifications and messages.', 'kataskevi' ); ?></strong></p>
			</div>
		<?php endif; ?>

		<?php if ( $inq_cleared ) : ?>
			<div class="notice notice-warning is-dismissible" style="border-left-color: #f59e0b;">
				<p><strong>🗑️ <?php esc_html_e( 'Inquiries log has been cleared successfully.', 'kataskevi' ); ?></strong></p>
			</div>
		<?php endif; ?>

		<?php if ( $map_updated ) : ?>
			<div class="notice notice-success is-dismissible" style="border-left-color: #10b981;">
				<p><strong>✅ <?php esc_html_e( 'Google Maps settings saved! Your office location on the Contact page is updated.', 'kataskevi' ); ?></strong></p>
			</div>
		<?php endif; ?>

		<?php if ( 'update_available' === $check_result ) : ?>
			<div class="notice notice-warning" style="border-left-color: #f59e0b; padding: 12px 16px;">
				<p style="font-size: 1rem; margin: 0 0 6px 0;">
					<strong>🎉 <?php esc_html_e( 'A new version of Kataskevi is available on Bitbucket!', 'kataskevi' ); ?></strong>
				</p>
				<p style="margin: 0;">
					<a href="<?php echo esc_url( admin_url( 'update-core.php' ) ); ?>" class="button kataskevi-btn-primary">
						<?php esc_html_e( 'Go to WordPress Updates & Install Now', 'kataskevi' ); ?> &rarr;
					</a>
				</p>
			</div>
		<?php elseif ( 'up_to_date' === $check_result ) : ?>
			<div class="notice notice-success is-dismissible" style="border-left-color: #10b981;">
				<p><strong>✅ <?php esc_html_e( 'Your Kataskevi theme is fully up to date! (Current Version: ', 'kataskevi' ); ?><?php echo esc_html( $current_ver ); ?>)</strong></p>
			</div>
		<?php elseif ( 'check_error' === $check_result ) : ?>
			<div class="notice notice-error is-dismissible" style="padding: 12px 16px;">
				<p style="font-size: 0.95rem; margin: 0 0 6px 0;"><strong>⚠️ <?php esc_html_e( 'Could not fetch updates from Bitbucket.', 'kataskevi' ); ?></strong></p>
				<?php if ( ! empty( $error_notice ) ) : ?>
					<p style="margin: 0 0 8px 0; font-family: monospace; font-size: 0.88rem;"><?php echo esc_html( $error_notice ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<!-- ================================================================= -->
		<!-- TAB 1: CONSULTATION FORM SETTINGS & INQUIRIES LEADS -->
		<!-- ================================================================= -->
		<?php if ( 'forms' === $active_tab ) : ?>

			<!-- Form Configuration Settings -->
			<div class="kataskevi-card">
				<h2 class="kataskevi-card-title">
					<span>⚙️</span>
					<span><?php esc_html_e( 'Consultation & Inquiry Form Routing', 'kataskevi' ); ?></span>
				</h2>
				<p class="description" style="margin-bottom: 20px;">
					<?php esc_html_e( 'Configure where consultation requests and site survey inquiries are delivered, customize automated email notifications, and set form copy.', 'kataskevi' ); ?>
				</p>

				<form method="post" action="<?php echo esc_url( admin_url( 'themes.php?page=kataskevi-updates&tab=forms' ) ); ?>">
					<?php wp_nonce_field( 'kataskevi_form_settings_nonce', 'kataskevi_form_nonce' ); ?>

					<table class="form-table" role="presentation">
						<tbody>
							<tr>
								<th scope="row">
									<label for="kataskevi_form_recipient_email"><?php esc_html_e( 'Notification Recipient Email', 'kataskevi' ); ?> <span style="color: #dc2626;">*</span></label>
								</th>
								<td>
									<input 
										name="kataskevi_form_recipient_email" 
										type="email" 
										id="kataskevi_form_recipient_email" 
										value="<?php echo esc_attr( $form_email ); ?>" 
										class="regular-text" 
										required
										style="width: 100%; max-width: 480px;"
									>
									<p class="description"><?php esc_html_e( 'All completed website consultation requests will be dispatched to this email address.', 'kataskevi' ); ?></p>
								</td>
							</tr>

							<tr>
								<th scope="row">
									<label for="kataskevi_form_email_subject"><?php esc_html_e( 'Email Subject Prefix', 'kataskevi' ); ?></label>
								</th>
								<td>
									<input 
										name="kataskevi_form_email_subject" 
										type="text" 
										id="kataskevi_form_email_subject" 
										value="<?php echo esc_attr( $form_subject ); ?>" 
										class="regular-text" 
										style="width: 100%; max-width: 480px;"
									>
									<p class="description"><?php esc_html_e( 'Subject line prefix in emails, e.g. [Kataskevi Inquiry] New Consultation Request', 'kataskevi' ); ?></p>
								</td>
							</tr>

							<tr>
								<th scope="row">
									<label for="kataskevi_form_submit_label"><?php esc_html_e( 'Submit Button Label', 'kataskevi' ); ?></label>
								</th>
								<td>
									<input 
										name="kataskevi_form_submit_label" 
										type="text" 
										id="kataskevi_form_submit_label" 
										value="<?php echo esc_attr( $form_submit ); ?>" 
										class="regular-text" 
										style="width: 100%; max-width: 480px;"
									>
									<p class="description"><?php esc_html_e( 'Button call to action text (defaults to "Submit Consultation Request").', 'kataskevi' ); ?></p>
								</td>
							</tr>

							<tr>
								<th scope="row">
									<label for="kataskevi_form_success_msg"><?php esc_html_e( 'Success Confirmation Message', 'kataskevi' ); ?></label>
								</th>
								<td>
									<textarea 
										name="kataskevi_form_success_msg" 
										id="kataskevi_form_success_msg" 
										rows="3" 
										class="large-text" 
										style="width: 100%; max-width: 600px;"
									><?php echo esc_textarea( $form_success ); ?></textarea>
									<p class="description"><?php esc_html_e( 'Displayed in the alert banner right after the user submits the form.', 'kataskevi' ); ?></p>
								</td>
							</tr>

							<tr>
								<th scope="row">
									<label for="kataskevi_form_title"><?php esc_html_e( 'Form Heading (Optional)', 'kataskevi' ); ?></label>
								</th>
								<td>
									<input 
										name="kataskevi_form_title" 
										type="text" 
										id="kataskevi_form_title" 
										value="<?php echo esc_attr( $form_title ); ?>" 
										class="regular-text" 
										placeholder="<?php esc_attr_e( 'e.g. Request a Technical Consultation', 'kataskevi' ); ?>"
										style="width: 100%; max-width: 480px;"
									>
									<p class="description"><?php esc_html_e( 'Optional header displayed directly inside the form card.', 'kataskevi' ); ?></p>
								</td>
							</tr>

							<tr>
								<th scope="row">
									<label for="kataskevi_form_desc"><?php esc_html_e( 'Form Subheading (Optional)', 'kataskevi' ); ?></label>
								</th>
								<td>
									<textarea 
										name="kataskevi_form_desc" 
										id="kataskevi_form_desc" 
										rows="2" 
										class="large-text" 
										style="width: 100%; max-width: 600px;"
										placeholder="<?php esc_attr_e( 'e.g. Complete the specifications below and our engineering team will follow up within 24 hours.', 'kataskevi' ); ?>"
									><?php echo esc_textarea( $form_desc ); ?></textarea>
								</td>
							</tr>

							<tr>
								<th scope="row"><?php esc_html_e( 'Optional Field Toggles', 'kataskevi' ); ?></th>
								<td>
									<fieldset style="display: flex; flex-direction: column; gap: 8px;">
										<label style="display: flex; align-items: center; gap: 8px;">
											<input type="checkbox" name="kataskevi_form_show_phone" value="1" <?php checked( $form_phone ); ?>>
											<span><?php esc_html_e( 'Display Phone Number field', 'kataskevi' ); ?></span>
										</label>
										<label style="display: flex; align-items: center; gap: 8px;">
											<input type="checkbox" name="kataskevi_form_show_org" value="1" <?php checked( $form_org ); ?>>
											<span><?php esc_html_e( 'Display Company / Organization field', 'kataskevi' ); ?></span>
										</label>
										<label style="display: flex; align-items: center; gap: 8px;">
											<input type="checkbox" name="kataskevi_form_show_service" value="1" <?php checked( $form_service ); ?>>
											<span><?php esc_html_e( 'Display Primary System of Interest dropdown', 'kataskevi' ); ?></span>
										</label>
									</fieldset>
								</td>
							</tr>
						</tbody>
					</table>

					<p class="submit" style="margin-top: 20px; padding-top: 16px; border-top: 1px solid #f1f5f9;">
						<button type="submit" name="kataskevi_save_form_settings" class="button kataskevi-btn-primary">
							💾 <?php esc_html_e( 'Save Form Settings', 'kataskevi' ); ?>
						</button>
					</p>
				</form>
			</div>

			<!-- Recent Inquiries & Leads Manager -->
			<div class="kataskevi-card">
				<div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px; margin-bottom: 16px;">
					<h2 style="margin: 0; font-size: 1.15rem; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 8px;">
						<span>📋</span>
						<span><?php esc_html_e( 'Recent Consultation Inquiries Log', 'kataskevi' ); ?></span>
						<span class="kataskevi-badge kataskevi-badge-info"><?php echo count( $inquiries_list ); ?> <?php esc_html_e( 'Saved Leads', 'kataskevi' ); ?></span>
					</h2>

					<?php if ( ! empty( $inquiries_list ) ) : ?>
						<form method="post" action="<?php echo esc_url( admin_url( 'themes.php?page=kataskevi-updates&tab=forms' ) ); ?>" onsubmit="return confirm('<?php esc_attr_e( 'Are you sure you want to clear all logged inquiries? This cannot be undone.', 'kataskevi' ); ?>');">
							<?php wp_nonce_field( 'kataskevi_clear_inquiries_nonce', 'kataskevi_clear_nonce' ); ?>
							<button type="submit" name="kataskevi_clear_inquiries" class="button button-link-delete" style="font-size: 13px; color: #b91c1c;">
								🗑️ <?php esc_html_e( 'Clear All Inquiries', 'kataskevi' ); ?>
							</button>
						</form>
					<?php endif; ?>
				</div>

				<?php if ( empty( $inquiries_list ) ) : ?>
					<div style="text-align: center; padding: 3rem 1rem; color: #64748b; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px;">
						<p style="font-size: 2rem; margin: 0 0 0.5rem 0;">📭</p>
						<p style="font-size: 1rem; font-weight: 600; color: #334155; margin: 0 0 4px 0;"><?php esc_html_e( 'No inquiries logged yet', 'kataskevi' ); ?></p>
						<p style="font-size: 0.88rem; margin: 0; max-width: 480px; margin-left: auto; margin-right: auto;">
							<?php esc_html_e( 'When visitors submit consultation or site survey requests through your website form, their details will automatically be backed up and listed here.', 'kataskevi' ); ?>
						</p>
					</div>
				<?php else : ?>
					<div style="overflow-x: auto; border: 1px solid #e2e8f0; border-radius: 8px;">
						<table class="kataskevi-table">
							<thead>
								<tr>
									<th><?php esc_html_e( 'Date & Time', 'kataskevi' ); ?></th>
									<th><?php esc_html_e( 'Contact Name & Email', 'kataskevi' ); ?></th>
									<th><?php esc_html_e( 'Phone & Org', 'kataskevi' ); ?></th>
									<th><?php esc_html_e( 'Service Requested', 'kataskevi' ); ?></th>
									<th><?php esc_html_e( 'Project Scope / Message', 'kataskevi' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ( $inquiries_list as $inquiry ) :
									$inq_date    = ! empty( $inquiry['date'] ) ? $inquiry['date'] : esc_html__( 'N/A', 'kataskevi' );
									$inq_name    = ! empty( $inquiry['name'] ) ? $inquiry['name'] : esc_html__( 'Anonymous', 'kataskevi' );
									$inq_email   = ! empty( $inquiry['email'] ) ? $inquiry['email'] : '';
									$inq_phone   = ! empty( $inquiry['phone'] ) ? $inquiry['phone'] : '';
									$inq_org     = ! empty( $inquiry['organization'] ) ? $inquiry['organization'] : esc_html__( 'Direct Client', 'kataskevi' );
									$inq_service = ! empty( $inquiry['service'] ) ? $inquiry['service'] : esc_html__( 'General Inquiry', 'kataskevi' );
									$inq_message = ! empty( $inquiry['message'] ) ? $inquiry['message'] : '';
									?>
									<tr>
										<td style="white-space: nowrap; font-size: 12px; color: #64748b;">
											<?php echo esc_html( $inq_date ); ?>
										</td>
										<td>
											<strong><?php echo esc_html( $inq_name ); ?></strong><br>
											<?php if ( ! empty( $inq_email ) ) : ?>
												<a href="mailto:<?php echo esc_attr( $inq_email ); ?>" style="color: #3f75a1; font-size: 12.5px;">
													<?php echo esc_html( $inq_email ); ?>
												</a>
											<?php endif; ?>
										</td>
										<td>
											<?php if ( ! empty( $inq_phone ) ) : ?>
												<a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $inq_phone ) ); ?>" style="color: #1e293b; font-size: 12.5px;">
													📞 <?php echo esc_html( $inq_phone ); ?>
												</a><br>
											<?php endif; ?>
											<span style="color: #64748b; font-size: 12px;">
												🏢 <?php echo esc_html( $inq_org ); ?>
											</span>
										</td>
										<td>
											<span class="kataskevi-badge kataskevi-badge-info">
												<?php echo esc_html( $inq_service ); ?>
											</span>
										</td>
										<td style="max-width: 320px; font-size: 12.5px; line-height: 1.5; color: #334155;">
											<?php echo esc_html( $inq_message ); ?>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				<?php endif; ?>
			</div>

		<!-- ================================================================= -->
		<!-- TAB 2: CONTACT GOOGLE MAPS LOCATION & EMBED -->
		<!-- ================================================================= -->
		<?php elseif ( 'map' === $active_tab ) : ?>

			<div class="kataskevi-card">
				<h2 class="kataskevi-card-title">
					<span>📍</span>
					<span><?php esc_html_e( 'Contact Page — Google Maps Location & Embed Settings', 'kataskevi' ); ?></span>
				</h2>
				<p class="description" style="margin-bottom: 20px;">
					<?php esc_html_e( 'Configure the interactive Google Map section displayed on your Contact page. Paste your full Google Maps <iframe> embed code or just the embed URL below.', 'kataskevi' ); ?>
				</p>

				<form method="post" action="<?php echo esc_url( admin_url( 'themes.php?page=kataskevi-updates&tab=map' ) ); ?>">
					<?php wp_nonce_field( 'kataskevi_map_settings_nonce', 'kataskevi_map_nonce' ); ?>

					<table class="form-table" role="presentation">
						<tbody>
							<tr>
								<th scope="row">
									<label for="kataskevi_contact_show_map"><?php esc_html_e( 'Display Map', 'kataskevi' ); ?></label>
								</th>
								<td>
									<label style="display: flex; align-items: center; gap: 8px; font-weight: 500;">
										<input 
											name="kataskevi_contact_show_map" 
											type="checkbox" 
											id="kataskevi_contact_show_map" 
											value="1" 
											<?php checked( 1, (int) $saved_show_map ); ?>
										>
										<?php esc_html_e( 'Show office location map section on the Contact Us page', 'kataskevi' ); ?>
									</label>
								</td>
							</tr>

							<tr>
								<th scope="row">
									<label for="kataskevi_contact_map_eyebrow"><?php esc_html_e( 'Section Eyebrow', 'kataskevi' ); ?></label>
								</th>
								<td>
									<input 
										name="kataskevi_contact_map_eyebrow" 
										type="text" 
										id="kataskevi_contact_map_eyebrow" 
										value="<?php echo esc_attr( $saved_map_eyebrow ); ?>" 
										class="regular-text" 
										style="width: 100%; max-width: 480px;"
									>
								</td>
							</tr>

							<tr>
								<th scope="row">
									<label for="kataskevi_contact_map_title"><?php esc_html_e( 'Section Headline', 'kataskevi' ); ?></label>
								</th>
								<td>
									<input 
										name="kataskevi_contact_map_title" 
										type="text" 
										id="kataskevi_contact_map_title" 
										value="<?php echo esc_attr( $saved_map_title ); ?>" 
										class="regular-text" 
										style="width: 100%; max-width: 480px;"
									>
								</td>
							</tr>

							<tr>
								<th scope="row">
									<label for="kataskevi_contact_map_desc"><?php esc_html_e( 'Lead Description', 'kataskevi' ); ?></label>
								</th>
								<td>
									<textarea 
										name="kataskevi_contact_map_desc" 
										id="kataskevi_contact_map_desc" 
										rows="3" 
										class="large-text" 
										style="width: 100%; max-width: 600px;"
									><?php echo esc_textarea( $saved_map_desc ); ?></textarea>
								</td>
							</tr>

							<tr>
								<th scope="row">
									<label for="kataskevi_contact_map_embed"><?php esc_html_e( 'Google Map Embed Code or URL', 'kataskevi' ); ?></label>
								</th>
								<td>
									<textarea 
										name="kataskevi_contact_map_embed" 
										id="kataskevi_contact_map_embed" 
										rows="4" 
										class="large-text code" 
										style="width: 100%; max-width: 700px; font-size: 12px; line-height: 1.5;"
										placeholder="https://www.google.com/maps/embed?pb=... or <iframe src=...>"
									><?php echo esc_textarea( $saved_map_embed ); ?></textarea>
									<p class="description" style="margin-top: 6px;">
										<?php esc_html_e( 'Paste your Google Maps embed code or URL here. It will automatically be parsed, secured, and rendered responsively.', 'kataskevi' ); ?>
									</p>
								</td>
							</tr>
						</tbody>
					</table>

					<!-- Live Preview Box -->
					<?php
					$preview_url = function_exists( 'kataskevi_parse_map_url' ) ? kataskevi_parse_map_url( $saved_map_embed ) : $saved_map_embed;
					if ( ! empty( $preview_url ) ) :
					?>
						<div style="margin: 20px 0; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; background: #f8fafc; padding: 14px;">
							<p style="margin: 0 0 8px 0; font-size: 0.85rem; font-weight: 600; color: #475569;">
								🖥️ <?php esc_html_e( 'Live Map Preview (Your Saved Location):', 'kataskevi' ); ?>
							</p>
							<div style="width: 100%; height: 320px; border-radius: 6px; overflow: hidden; background: #fff; border: 1px solid #cbd5e1;">
								<iframe 
									src="<?php echo esc_url( $preview_url ); ?>" 
									width="100%" 
									height="100%" 
									style="border:0;" 
									allowfullscreen="" 
									loading="lazy" 
									referrerpolicy="no-referrer-when-downgrade"
									title="<?php esc_attr_e( 'Google Map Preview', 'kataskevi' ); ?>"
								></iframe>
							</div>
						</div>
					<?php endif; ?>

					<p class="submit" style="margin-top: 20px; padding-top: 16px; border-top: 1px solid #f1f5f9;">
						<button type="submit" name="kataskevi_save_map_settings" class="button kataskevi-btn-primary">
							💾 <?php esc_html_e( 'Save Map Settings', 'kataskevi' ); ?>
						</button>
					</p>
				</form>
			</div>

		<!-- ================================================================= -->
		<!-- TAB 3: THEME UPDATES & BITBUCKET REPOSITORY -->
		<!-- ================================================================= -->
		<?php elseif ( 'updates' === $active_tab ) : ?>
			
			<!-- System Status Card -->
			<div class="kataskevi-card">
				<h2 class="kataskevi-card-title">
					<span>📊</span>
					<span><?php esc_html_e( 'Current Installation & Git Status', 'kataskevi' ); ?></span>
				</h2>

				<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin: 16px 0;">
					<div style="background: #f8fafc; padding: 14px 16px; border-radius: 8px; border: 1px solid #e2e8f0;">
						<span style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; font-weight: 600; display: block; margin-bottom: 4px;"><?php esc_html_e( 'Installed Version', 'kataskevi' ); ?></span>
						<strong style="font-size: 1.25rem; color: #1e293b;">v<?php echo esc_html( $current_ver ); ?></strong>
					</div>

					<div style="background: #f8fafc; padding: 14px 16px; border-radius: 8px; border: 1px solid #e2e8f0;">
						<span style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; font-weight: 600; display: block; margin-bottom: 4px;"><?php esc_html_e( 'Tracked Branch', 'kataskevi' ); ?></span>
						<strong style="font-size: 1.1rem; color: #1e293b; font-family: monospace;"><?php echo esc_html( $saved_branch ); ?></strong>
					</div>

					<div style="background: #f8fafc; padding: 14px 16px; border-radius: 8px; border: 1px solid #e2e8f0;">
						<span style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; font-weight: 600; display: block; margin-bottom: 4px;"><?php esc_html_e( 'Authentication Status', 'kataskevi' ); ?></span>
						<?php if ( $is_configured || $has_const_token ) : ?>
							<span class="kataskevi-badge kataskevi-badge-success">
								● <?php esc_html_e( 'Configured (Private Repo Ready)', 'kataskevi' ); ?>
							</span>
						<?php else : ?>
							<span class="kataskevi-badge kataskevi-badge-warning">
								○ <?php esc_html_e( 'Credentials Required for Private Repo', 'kataskevi' ); ?>
							</span>
						<?php endif; ?>
					</div>
				</div>

				<div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-top: 16px; padding-top: 16px; border-top: 1px solid #f1f5f9;">
					<div style="font-size: 0.875rem; color: #64748b;">
						<span><?php esc_html_e( 'Remote Repository:', 'kataskevi' ); ?></span>
						<a href="<?php echo esc_url( $saved_repo ); ?>" target="_blank" rel="noopener noreferrer" style="font-family: monospace; color: #3f75a1; font-weight: 500;">
							<?php echo esc_html( $saved_repo ); ?> &rarr;
						</a>
					</div>
					<div style="display: flex; gap: 8px;">
						<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'themes.php?page=kataskevi-updates&tab=updates&action=test_connection' ), 'kataskevi_test_connection_nonce' ) ); ?>" class="button button-secondary">
							🔍 <?php esc_html_e( 'Test Connection & Diagnostics', 'kataskevi' ); ?>
						</a>
						<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'themes.php?page=kataskevi-updates&tab=updates&action=check_now' ), 'kataskevi_manual_check_nonce' ) ); ?>" class="button kataskevi-btn-primary">
							🔄 <?php esc_html_e( 'Check for Updates Now', 'kataskevi' ); ?>
						</a>
					</div>
				</div>
			</div>

			<?php if ( $diag_run && is_array( $diag_results ) ) : ?>
				<!-- Live Diagnostic Report Card -->
				<div class="kataskevi-card" style="border-color: #bbf7d0; background: #f0fdf4;">
					<h3 style="margin-top: 0; font-size: 1.1rem; color: #166534; display: flex; align-items: center; gap: 8px;">
						<span>🩺</span>
						<span><?php esc_html_e( 'Bitbucket API Live Diagnostics Report', 'kataskevi' ); ?></span>
						<span style="font-size: 0.8rem; font-weight: normal; color: #15803d; margin-left: auto;">
							<?php echo esc_html( $diag_results['timestamp'] ); ?>
						</span>
					</h3>

					<div style="background: #fff; border: 1px solid #dcfce7; border-radius: 8px; padding: 14px 18px; margin-top: 12px; font-size: 0.92rem;">
						<table class="kataskevi-table">
							<tbody>
								<tr>
									<td style="color: #64748b; width: 220px; font-weight: 600;"><?php esc_html_e( 'API Connection Status', 'kataskevi' ); ?></td>
									<td>
										<?php if ( $diag_results['api_accessible'] ) : ?>
											<span class="kataskevi-badge kataskevi-badge-success">HTTP 200 OK — Repository Accessible</span>
										<?php else : ?>
											<span class="kataskevi-badge kataskevi-badge-danger">HTTP <?php echo esc_html( $diag_results['http_code'] ); ?> — <?php echo esc_html( $diag_results['error_message'] ); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<td style="color: #64748b; font-weight: 600;"><?php esc_html_e( 'readme.txt Stable Tag', 'kataskevi' ); ?></td>
									<td>
										<?php if ( $diag_results['readme_found'] && ! empty( $diag_results['stable_tag'] ) ) : ?>
											<code style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px;"><?php echo esc_html( $diag_results['stable_tag'] ); ?></code>
											<span style="color: #15803d; font-size: 0.85rem; margin-left: 6px;">(Primary PUC Strategy)</span>
										<?php elseif ( $diag_results['readme_found'] ) : ?>
											<span style="color: #b45309;">readme.txt present but missing 'Stable tag:' header</span>
										<?php else : ?>
											<span style="color: #dc2626;">readme.txt not found on branch <?php echo esc_html( $diag_results['branch'] ); ?></span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<td style="color: #64748b; font-weight: 600;"><?php esc_html_e( 'Latest Git Tag on Remote', 'kataskevi' ); ?></td>
									<td>
										<?php if ( ! empty( $diag_results['latest_git_tag'] ) ) : ?>
											<code style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px;"><?php echo esc_html( $diag_results['latest_git_tag'] ); ?></code>
											<?php if ( count( $diag_results['all_tags'] ) > 1 ) : ?>
												<span style="color: #64748b; font-size: 0.85rem; margin-left: 6px;">(All tags: <?php echo esc_html( implode( ', ', $diag_results['all_tags'] ) ); ?>)</span>
											<?php endif; ?>
										<?php else : ?>
											<span style="color: #64748b;">No tags found or authentication required</span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<td style="color: #64748b; font-weight: 600;"><?php esc_html_e( 'Remote style.css Version', 'kataskevi' ); ?></td>
									<td>
										<?php if ( ! empty( $diag_results['remote_style_ver'] ) ) : ?>
											<code style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px;"><?php echo esc_html( $diag_results['remote_style_ver'] ); ?></code>
										<?php else : ?>
											<span style="color: #64748b;">Not readable or authentication required</span>
										<?php endif; ?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			<?php endif; ?>

			<!-- Repository Credentials Settings Card -->
			<div class="kataskevi-card">
				<h2 class="kataskevi-card-title">
					<span>🔑</span>
					<span><?php esc_html_e( 'Bitbucket Repository Credentials', 'kataskevi' ); ?></span>
				</h2>
				<p class="description" style="margin-bottom: 20px;">
					<?php esc_html_e( 'Enter your Bitbucket repository details and private credentials below to enable automatic update checking and installation within WordPress.', 'kataskevi' ); ?>
				</p>

				<form method="post" action="<?php echo esc_url( admin_url( 'themes.php?page=kataskevi-updates&tab=updates' ) ); ?>">
					<?php wp_nonce_field( 'kataskevi_repo_settings_nonce', 'kataskevi_repo_nonce' ); ?>

					<table class="form-table" role="presentation">
						<tbody>
							<tr>
								<th scope="row">
									<label for="kataskevi_bitbucket_repo"><?php esc_html_e( 'Repository URL', 'kataskevi' ); ?> <span style="color: #dc2626;">*</span></label>
								</th>
								<td>
									<input 
										name="kataskevi_bitbucket_repo" 
										type="url" 
										id="kataskevi_bitbucket_repo" 
										value="<?php echo esc_attr( $saved_repo ); ?>" 
										class="regular-text code" 
										required
										style="width: 100%; max-width: 480px;"
									>
									<p class="description"><?php esc_html_e( 'Full HTTPS repository URL, e.g. https://bitbucket.org/workspace/repository', 'kataskevi' ); ?></p>
								</td>
							</tr>

							<tr>
								<th scope="row">
									<label for="kataskevi_bitbucket_branch"><?php esc_html_e( 'Branch to Track', 'kataskevi' ); ?></label>
								</th>
								<td>
									<input 
										name="kataskevi_bitbucket_branch" 
										type="text" 
										id="kataskevi_bitbucket_branch" 
										value="<?php echo esc_attr( $saved_branch ); ?>" 
										class="regular-text code"
										style="width: 100%; max-width: 240px;"
									>
									<p class="description"><?php esc_html_e( 'Git branch holding release tags and code (typically "main" or "master").', 'kataskevi' ); ?></p>
								</td>
							</tr>

							<tr>
								<th scope="row">
									<label for="kataskevi_bitbucket_user"><?php esc_html_e( 'Username or Registered Email', 'kataskevi' ); ?> <span style="color: #dc2626;">*</span></label>
								</th>
								<td>
									<input 
										name="kataskevi_bitbucket_user" 
										type="text" 
										id="kataskevi_bitbucket_user" 
										value="<?php echo esc_attr( $saved_user ); ?>" 
										class="regular-text" 
										autocomplete="off"
										placeholder="<?php esc_attr_e( 'e.g. user@example.com, username, or x-token-auth', 'kataskevi' ); ?>"
										style="width: 100%; max-width: 480px;"
										<?php echo $has_const_user ? 'disabled' : ''; ?>
									>
									<?php if ( $has_const_user ) : ?>
										<p class="description" style="color: #0284c7;">
											<em><?php esc_html_e( 'Configured via KATASKEVI_BITBUCKET_USER in wp-config.php.', 'kataskevi' ); ?></em>
										</p>
									<?php else : ?>
										<p class="description">
											&bull; <strong><?php esc_html_e( 'Atlassian Scoped Token:', 'kataskevi' ); ?></strong> <?php esc_html_e( 'Use your registered Atlassian email address.', 'kataskevi' ); ?><br>
											&bull; <strong><?php esc_html_e( 'Repository Access Token:', 'kataskevi' ); ?></strong> <?php esc_html_e( 'Use username: x-token-auth', 'kataskevi' ); ?><br>
											&bull; <strong><?php esc_html_e( 'Bitbucket App Password:', 'kataskevi' ); ?></strong> <?php esc_html_e( 'Use your Bitbucket account username.', 'kataskevi' ); ?>
										</p>
									<?php endif; ?>
								</td>
							</tr>

							<tr>
								<th scope="row">
									<label for="kataskevi_bitbucket_token"><?php esc_html_e( 'App Password / API Token', 'kataskevi' ); ?></label>
								</th>
								<td>
									<input 
										name="kataskevi_bitbucket_token" 
										type="password" 
										id="kataskevi_bitbucket_token" 
										value="<?php echo ! empty( $saved_token ) ? '********' : ''; ?>" 
										class="regular-text code" 
										autocomplete="new-password"
										placeholder="<?php esc_attr_e( 'Enter your App Password or API Token', 'kataskevi' ); ?>"
										style="width: 100%; max-width: 480px;"
										<?php echo $has_const_token ? 'disabled' : ''; ?>
									>
									<?php if ( $has_const_token ) : ?>
										<p class="description" style="color: #0284c7;">
											<em><?php esc_html_e( 'Configured via KATASKEVI_BITBUCKET_TOKEN in wp-config.php.', 'kataskevi' ); ?></em>
										</p>
									<?php else : ?>
										<p class="description">
											<?php esc_html_e( 'Requires "Repositories: Read" permission scope. Leave unchanged to keep the currently stored token.', 'kataskevi' ); ?>
										</p>
									<?php endif; ?>
								</td>
							</tr>
						</tbody>
					</table>

					<p class="submit" style="margin-top: 20px; padding-top: 16px; border-top: 1px solid #f1f5f9;">
						<button type="submit" name="kataskevi_save_repo_settings" class="button kataskevi-btn-primary">
							💾 <?php esc_html_e( 'Save Repository Settings', 'kataskevi' ); ?>
						</button>
					</p>
				</form>
			</div>

			<!-- Quick Step-by-Step Guide -->
			<div class="kataskevi-card" style="background: #fbfcfe;">
				<h3 style="margin-top: 0; font-size: 1.05rem; color: #1e293b; display: flex; align-items: center; gap: 8px;">
					<span>💡</span>
					<span><?php esc_html_e( 'Credential Setup Instructions', 'kataskevi' ); ?></span>
				</h3>
				<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; margin-top: 14px;">
					<div style="background: #fff; padding: 16px; border: 1px solid #e2e8f0; border-radius: 8px;">
						<h4 style="margin: 0 0 8px 0; color: #0284c7; font-size: 0.95rem;">
							🏆 <?php esc_html_e( 'Option A (Recommended): Repository Access Token', 'kataskevi' ); ?>
						</h4>
						<ol style="margin-left: 18px; line-height: 1.6; color: #475569; font-size: 0.88rem; padding: 0;">
							<?php
							$tokens_link = ! empty( $saved_repo ) ? esc_url( rtrim( $saved_repo, '/' ) . '/admin/access-tokens' ) : 'https://bitbucket.org';
							?>
							<li>Open: <a href="<?php echo $tokens_link; ?>" target="_blank" rel="noopener noreferrer"><strong><?php esc_html_e( 'Repository Access Tokens', 'kataskevi' ); ?> &rarr;</strong></a></li>
							<li>Click <strong>Create repository access token</strong>.</li>
							<li>Under <strong>Repositories</strong> check: <strong>Read</strong>.</li>
							<li>Username: <code>x-token-auth</code>, Token: paste your generated token.</li>
						</ol>
					</div>

					<div style="background: #fff; padding: 16px; border: 1px solid #e2e8f0; border-radius: 8px;">
						<h4 style="margin: 0 0 8px 0; color: #0284c7; font-size: 0.95rem;">
							⚙️ <?php esc_html_e( 'Option B: Personal Bitbucket App Password', 'kataskevi' ); ?>
						</h4>
						<ol style="margin-left: 18px; line-height: 1.6; color: #475569; font-size: 0.88rem; padding: 0;">
							<li>Open: <a href="https://bitbucket.org/account/settings/app-passwords/" target="_blank" rel="noopener noreferrer"><strong>Personal App Passwords &rarr;</strong></a></li>
							<li>Click <strong>Create app password</strong>.</li>
							<li>Under <strong>Repositories</strong> check: <strong>Read</strong>.</li>
							<li>Username: your Bitbucket username, Token: paste app password.</li>
						</ol>
					</div>
				</div>
			</div>

		<?php endif; ?>

	</div>
	<?php
}
