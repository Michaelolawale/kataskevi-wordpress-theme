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
 * @version 2.1.4
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
			$auth_creds['username'] = 'unionolivia';
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
		$owner = 'unionolivia';
		$repo  = 'kataskevi';
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

	// 1. Handle Saving Credentials Form.
	if ( 'POST' === ( isset( $_SERVER['REQUEST_METHOD'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_METHOD'] ) ) : '' ) && isset( $_POST['kataskevi_save_repo_settings'] ) ) {
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

		wp_safe_redirect( add_query_arg( array( 'page' => 'kataskevi-updates', 'updated' => '1', 'diag_run' => '1' ), admin_url( 'themes.php' ) ) );
		exit;
	}

	// 2. Handle Manual "Check for Updates" Button.
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

		wp_safe_redirect( add_query_arg( array( 'page' => 'kataskevi-updates', 'check_result' => $status, 'diag_run' => '1' ), admin_url( 'themes.php' ) ) );
		exit;
	}

	// 3. Handle Diagnostic "Test Connection" Button.
	if ( isset( $_GET['action'] ) && 'test_connection' === $_GET['action'] ) {
		check_admin_referer( 'kataskevi_test_connection_nonce' );

		$diag = kataskevi_test_bitbucket_connection();
		set_transient( 'kataskevi_diag_results', $diag, 300 );

		wp_safe_redirect( add_query_arg( array( 'page' => 'kataskevi-updates', 'diag_run' => '1' ), admin_url( 'themes.php' ) ) );
		exit;
	}
}
add_action( 'admin_init', 'kataskevi_handle_updater_actions' );

/**
 * Render the Theme Updates administrative page.
 *
 * @return void
 */
function kataskevi_render_updater_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'kataskevi' ) );
	}

	global $kataskevi_update_checker;

	$theme         = wp_get_theme( 'kataskevi' );
	$current_ver   = $theme->exists() ? $theme->get( 'Version' ) : '2.1.2';
	$saved_repo    = get_option( 'kataskevi_bitbucket_repo', 'https://bitbucket.org/unionolivia/kataskevi' );
	$saved_branch  = get_option( 'kataskevi_bitbucket_branch', 'main' );
	$saved_user    = get_option( 'kataskevi_bitbucket_user', '' );
	$saved_token   = get_option( 'kataskevi_bitbucket_token', '' );
	$is_configured = ! empty( $saved_user ) && ! empty( $saved_token );

	// Determine if wp-config.php constants are overriding settings.
	$has_const_user  = defined( 'KATASKEVI_BITBUCKET_USER' );
	$has_const_token = defined( 'KATASKEVI_BITBUCKET_TOKEN' );

	$check_result = isset( $_GET['check_result'] ) ? sanitize_text_field( wp_unslash( $_GET['check_result'] ) ) : '';
	$is_updated   = isset( $_GET['updated'] ) && '1' === $_GET['updated'];
	$diag_run     = isset( $_GET['diag_run'] ) && '1' === $_GET['diag_run'];
	$diag_results = get_transient( 'kataskevi_diag_results' );
	$error_notice = get_transient( 'kataskevi_update_error_notice' );
	?>
	<div class="wrap kataskevi-admin-wrap" style="max-width: 960px; margin-top: 2rem;">
		
		<h1 style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
			<span style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; background: #3f75a1; border-radius: 8px; color: #fff; font-weight: bold; font-size: 1.125rem;">K</span>
			<span><?php esc_html_e( 'Kataskevi — Theme Updates & Repository Settings', 'kataskevi' ); ?></span>
		</h1>
		<p class="description" style="margin-bottom: 2rem; font-size: 0.95rem;">
			<?php esc_html_e( 'Configure your Bitbucket repository details and private credentials to enable automated, one-click theme updates directly within WordPress.', 'kataskevi' ); ?>
		</p>

		<?php if ( $is_updated ) : ?>
			<div class="notice notice-success is-dismissible" style="border-left-color: #3f75a1;">
				<p><strong><?php esc_html_e( 'Repository settings and credentials saved successfully.', 'kataskevi' ); ?></strong></p>
			</div>
		<?php endif; ?>

		<?php if ( 'update_available' === $check_result ) : ?>
			<div class="notice notice-warning" style="border-left-color: #f59e0b; padding: 12px 16px;">
				<p style="font-size: 1rem; margin: 0 0 6px 0;">
					<strong>🎉 <?php esc_html_e( 'A new version of Kataskevi is available on Bitbucket!', 'kataskevi' ); ?></strong>
				</p>
				<p style="margin: 0;">
					<a href="<?php echo esc_url( admin_url( 'update-core.php' ) ); ?>" class="button button-primary" style="background: #3f75a1; border-color: #3f75a1;">
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
				<?php if ( ! empty( $error_notice ) && false !== strpos( $error_notice, '403' ) ) : ?>
					<div style="background: #fff; border: 1px solid #fecaca; border-radius: 6px; padding: 12px 14px; margin-top: 8px; color: #1e293b; font-size: 0.9rem; line-height: 1.6;">
						<strong style="color: #b91c1c;"><?php esc_html_e( 'Why did Bitbucket return HTTP 403 (Forbidden)?', 'kataskevi' ); ?></strong>
						<ol style="margin: 8px 0 8px 18px; padding: 0;">
							<li><strong><?php esc_html_e( 'Atlassian API Token vs Bitbucket App Password:', 'kataskevi' ); ?></strong> <?php esc_html_e( 'Tokens created at id.atlassian.com are Jira/Confluence tokens and cannot access Bitbucket code. You must create a Bitbucket App Password instead.', 'kataskevi' ); ?></li>
							<li><strong><?php esc_html_e( 'Missing Permission Scope:', 'kataskevi' ); ?></strong> <?php esc_html_e( 'When creating your Bitbucket App Password, the "Repositories: Read" checkbox must be selected.', 'kataskevi' ); ?></li>
						</ol>
						<p style="margin: 0;">
							👉 <a href="https://bitbucket.org/account/settings/app-passwords/" target="_blank" rel="noopener noreferrer"><strong><?php esc_html_e( 'Open Bitbucket App Passwords &rarr;', 'kataskevi' ); ?></strong></a> <?php esc_html_e( 'Create a new password with "Repositories: Read", then enter your username "unionolivia" and the new password below.', 'kataskevi' ); ?>
						</p>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<!-- System Status Card -->
		<div class="card" style="max-width: 100%; padding: 20px 24px; border-radius: 8px; border: 1px solid #ccd0d4; box-shadow: 0 1px 3px rgba(0,0,0,0.04); margin-bottom: 24px; background: #fff;">
			<h2 style="margin-top: 0; font-size: 1.15rem; border-bottom: 1px solid #eee; padding-bottom: 12px;">
				<?php esc_html_e( 'Current Installation & Status', 'kataskevi' ); ?>
			</h2>

			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin: 16px 0;">
				<div style="background: #f8fafc; padding: 14px 16px; border-radius: 6px; border: 1px solid #e2e8f0;">
					<span style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; font-weight: 600; display: block; margin-bottom: 4px;"><?php esc_html_e( 'Installed Version', 'kataskevi' ); ?></span>
					<strong style="font-size: 1.25rem; color: #1e293b;">v<?php echo esc_html( $current_ver ); ?></strong>
				</div>

				<div style="background: #f8fafc; padding: 14px 16px; border-radius: 6px; border: 1px solid #e2e8f0;">
					<span style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; font-weight: 600; display: block; margin-bottom: 4px;"><?php esc_html_e( 'Target Branch', 'kataskevi' ); ?></span>
					<strong style="font-size: 1.1rem; color: #1e293b; font-family: monospace;"><?php echo esc_html( $saved_branch ); ?></strong>
				</div>

				<div style="background: #f8fafc; padding: 14px 16px; border-radius: 6px; border: 1px solid #e2e8f0;">
					<span style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; font-weight: 600; display: block; margin-bottom: 4px;"><?php esc_html_e( 'Authentication Status', 'kataskevi' ); ?></span>
					<?php if ( $is_configured || $has_const_token ) : ?>
						<span style="display: inline-flex; align-items: center; gap: 4px; background: #dcfce7; color: #15803d; padding: 4px 10px; border-radius: 999px; font-size: 0.85rem; font-weight: 600;">
							● <?php esc_html_e( 'Configured (Private Repo Ready)', 'kataskevi' ); ?>
						</span>
					<?php else : ?>
						<span style="display: inline-flex; align-items: center; gap: 4px; background: #fef3c7; color: #b45309; padding: 4px 10px; border-radius: 999px; font-size: 0.85rem; font-weight: 600;">
							○ <?php esc_html_e( 'Unauthenticated (Requires App Password for Private Repo)', 'kataskevi' ); ?>
						</span>
					<?php endif; ?>
				</div>
			</div>

			<div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-top: 16px; padding-top: 16px; border-top: 1px solid #f1f5f9;">
				<div style="font-size: 0.875rem; color: #64748b;">
					<span><?php esc_html_e( 'Remote Repository:', 'kataskevi' ); ?></span>
					<a href="<?php echo esc_url( $saved_repo ); ?>" target="_blank" rel="noopener noreferrer" style="font-family: monospace; color: #3f75a1;">
						<?php echo esc_html( $saved_repo ); ?>
					</a>
				</div>
				<div style="display: flex; gap: 8px;">
					<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'themes.php?page=kataskevi-updates&action=test_connection' ), 'kataskevi_test_connection_nonce' ) ); ?>" class="button button-secondary">
						🔍 <?php esc_html_e( 'Test Connection & Diagnostics', 'kataskevi' ); ?>
					</a>
					<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'themes.php?page=kataskevi-updates&action=check_now' ), 'kataskevi_manual_check_nonce' ) ); ?>" class="button button-primary" style="background: #3f75a1; border-color: #3f75a1;">
						🔄 <?php esc_html_e( 'Check for Updates Now', 'kataskevi' ); ?>
					</a>
				</div>
			</div>
		</div>

		<?php if ( $diag_run && is_array( $diag_results ) ) : ?>
			<!-- Live Diagnostic Report Card -->
			<div class="card" style="max-width: 100%; padding: 20px 24px; border-radius: 8px; border: 1px solid #bbf7d0; box-shadow: 0 1px 3px rgba(0,0,0,0.04); margin-bottom: 24px; background: #f0fdf4;">
				<h3 style="margin-top: 0; font-size: 1.1rem; color: #166534; display: flex; align-items: center; gap: 8px;">
					<span>🩺</span>
					<span><?php esc_html_e( 'Bitbucket API Live Diagnostics Report', 'kataskevi' ); ?></span>
					<span style="font-size: 0.8rem; font-weight: normal; color: #15803d; margin-left: auto;">
						<?php echo esc_html( $diag_results['timestamp'] ); ?>
					</span>
				</h3>

				<div style="background: #fff; border: 1px solid #dcfce7; border-radius: 6px; padding: 14px 18px; margin-top: 12px; font-size: 0.92rem;">
					<table style="width: 100%; border-collapse: collapse; text-align: left;">
						<tbody>
							<tr style="border-bottom: 1px solid #f1f5f9;">
								<td style="padding: 6px 0; color: #64748b; width: 220px; font-weight: 600;"><?php esc_html_e( 'API Connection Status', 'kataskevi' ); ?></td>
								<td style="padding: 6px 0;">
									<?php if ( $diag_results['api_accessible'] ) : ?>
										<strong style="color: #15803d;">HTTP 200 OK — Repository Accessible</strong>
									<?php else : ?>
										<strong style="color: #dc2626;">HTTP <?php echo esc_html( $diag_results['http_code'] ); ?> — <?php echo esc_html( $diag_results['error_message'] ); ?></strong>
									<?php endif; ?>
								</td>
							</tr>
							<tr style="border-bottom: 1px solid #f1f5f9;">
								<td style="padding: 6px 0; color: #64748b; font-weight: 600;"><?php esc_html_e( 'readme.txt Stable Tag', 'kataskevi' ); ?></td>
								<td style="padding: 6px 0;">
									<?php if ( $diag_results['readme_found'] && ! empty( $diag_results['stable_tag'] ) ) : ?>
										<code style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px; color: #0f172a;"><?php echo esc_html( $diag_results['stable_tag'] ); ?></code>
										<span style="color: #15803d; font-size: 0.85rem; margin-left: 6px;">(Primary PUC Strategy)</span>
									<?php elseif ( $diag_results['readme_found'] ) : ?>
										<span style="color: #b45309;">readme.txt present but missing 'Stable tag:' header</span>
									<?php else : ?>
										<span style="color: #dc2626;">readme.txt not found on branch <?php echo esc_html( $diag_results['branch'] ); ?></span>
									<?php endif; ?>
								</td>
							</tr>
							<tr style="border-bottom: 1px solid #f1f5f9;">
								<td style="padding: 6px 0; color: #64748b; font-weight: 600;"><?php esc_html_e( 'Latest Git Tag on Remote', 'kataskevi' ); ?></td>
								<td style="padding: 6px 0;">
									<?php if ( ! empty( $diag_results['latest_git_tag'] ) ) : ?>
										<code style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px; color: #0f172a;"><?php echo esc_html( $diag_results['latest_git_tag'] ); ?></code>
										<?php if ( count( $diag_results['all_tags'] ) > 1 ) : ?>
											<span style="color: #64748b; font-size: 0.85rem; margin-left: 6px;">(All tags: <?php echo esc_html( implode( ', ', $diag_results['all_tags'] ) ); ?>)</span>
										<?php endif; ?>
									<?php else : ?>
										<span style="color: #64748b;">No tags found or authentication required</span>
									<?php endif; ?>
								</td>
							</tr>
							<tr>
								<td style="padding: 6px 0; color: #64748b; font-weight: 600;"><?php esc_html_e( 'Remote style.css Version', 'kataskevi' ); ?></td>
								<td style="padding: 6px 0;">
									<?php if ( ! empty( $diag_results['remote_style_ver'] ) ) : ?>
										<code style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px; color: #0f172a;"><?php echo esc_html( $diag_results['remote_style_ver'] ); ?></code>
									<?php else : ?>
										<span style="color: #64748b;">Not readable or authentication required</span>
									<?php endif; ?>
								</td>
							</tr>
						</tbody>
					</table>
				</div>

				<?php if ( ! $diag_results['api_accessible'] ) : ?>
					<?php if ( 401 === (int) $diag_results['http_code'] && false !== stripos( $diag_results['error_message'], 'registered email' ) ) : ?>
						<div style="margin-top: 14px; padding: 16px 20px; background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; color: #92400e; font-size: 0.92rem; line-height: 1.6;">
							<strong style="display: block; margin-bottom: 6px; font-size: 1.05rem; color: #b45309;">⚠️ Atlassian API Token Detected (HTTP 401)</strong>
							<p style="margin: 0 0 10px 0;">
								<?php esc_html_e( 'Bitbucket detected that this is an Atlassian API Token. Atlassian API Tokens must be paired with your Atlassian registered email address in the Username field.', 'kataskevi' ); ?>
							</p>
							<div style="background: #fff; padding: 14px 16px; border: 1px solid #fef3c7; border-radius: 6px; margin-bottom: 10px;">
								<strong style="color: #0f766e; display: block; margin-bottom: 4px;">👉 To resolve this:</strong>
								<ol style="margin: 0 0 0 18px; padding: 0; font-size: 0.88rem; color: #334155;">
									<li>In the <strong>Atlassian Email or Bitbucket Username</strong> field below, enter your registered email (e.g. <code>olawalemichael335@gmail.com</code>).</li>
									<li><strong>Important:</strong> If saving with your email then gives a <em>"403 — credentials lack required privilege scopes"</em> error, your token was created without Bitbucket scopes. You must generate a new scoped token at <a href="https://id.atlassian.com/manage-profile/security/api-tokens" target="_blank" rel="noopener noreferrer"><strong>Atlassian Security &rarr;</strong></a> by clicking <strong>Create API token with scopes</strong> &rarr; Select <strong>Bitbucket</strong> &rarr; check <strong>Repositories: Read</strong>.</li>
								</ol>
							</div>
						</div>
					<?php elseif ( 403 === (int) $diag_results['http_code'] || false !== stripos( $diag_results['error_message'], 'privilege scopes' ) || false !== stripos( $diag_results['error_message'], 'forbidden' ) ) : ?>
						<div style="margin-top: 14px; padding: 16px 20px; background: #fff5f5; border: 1px solid #fca5a5; border-radius: 8px; color: #7f1d1d; font-size: 0.92rem; line-height: 1.6;">
							<strong style="display: block; margin-bottom: 8px; font-size: 1.05rem; color: #991b1b;">🚫 Missing Privilege Scopes (HTTP 403)</strong>
							<p style="margin: 0 0 10px 0;">
								<?php esc_html_e( 'Bitbucket recognized your email and token, but rejected access because the token was not granted the "Repositories: Read" scope.', 'kataskevi' ); ?>
							</p>
							<div style="background: #fff; padding: 14px 16px; border: 1px solid #fed7d7; border-radius: 6px; margin-bottom: 12px;">
								<strong style="color: #0369a1; display: block; margin-bottom: 4px;">🏆 Fix: Create a Scoped Atlassian Token (2 minutes)</strong>
								<ol style="margin: 0 0 0 18px; padding: 0; font-size: 0.88rem; color: #334155;">
									<li>Go to <a href="https://id.atlassian.com/manage-profile/security/api-tokens" target="_blank" rel="noopener noreferrer"><strong>Atlassian Account Security &rarr; API Tokens</strong></a>.</li>
									<li>Click <strong>Create API token with scopes</strong> (do not click the standard unscoped button).</li>
									<li>Select <strong>Bitbucket</strong> as the application.</li>
									<li>Check <strong>Repositories: Read</strong> (<code>read:repository:bitbucket</code>) and <strong>Account: Read</strong> (<code>read:user:bitbucket</code>).</li>
									<li>Copy the new token, paste it below, ensure your registered email is in the Username field, and save.</li>
								</ol>
							</div>
							<div style="background: #fff; padding: 14px 16px; border: 1px solid #fed7d7; border-radius: 6px;">
								<strong style="color: #0369a1; display: block; margin-bottom: 4px;">⚡ Alternative (Repository Access Token):</strong>
								<p style="margin: 0; font-size: 0.88rem; color: #334155;">
									If the repo owner (<code>unionolivia</code>) generates a <a href="https://bitbucket.org/unionolivia/kataskevi/admin/access-tokens" target="_blank" rel="noopener noreferrer"><strong>Repository Access Token</strong></a> with <strong>Repositories: Read</strong>, you can simply enter Username: <code>x-token-auth</code> and paste that token.
								</p>
							</div>
						</div>
					<?php else : ?>
						<div style="margin-top: 12px; padding: 12px 16px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 6px; color: #991b1b; font-size: 0.88rem;">
							<strong><?php esc_html_e( 'Attention:', 'kataskevi' ); ?></strong>
							<?php esc_html_e( 'Bitbucket returned HTTP ' . intval( $diag_results['http_code'] ) . '. Because the repository is private, you must provide valid Bitbucket credentials with "Repositories: Read" permission below to allow WordPress to check for updates.', 'kataskevi' ); ?>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<!-- Main Settings Form -->
		<div class="card" style="max-width: 100%; padding: 24px; border-radius: 8px; border: 1px solid #ccd0d4; box-shadow: 0 1px 3px rgba(0,0,0,0.04); margin-bottom: 24px; background: #fff;">
			<h2 style="margin-top: 0; font-size: 1.15rem; border-bottom: 1px solid #eee; padding-bottom: 12px;">
				<?php esc_html_e( 'Bitbucket Repository Credentials', 'kataskevi' ); ?>
			</h2>
			<p class="description" style="margin-bottom: 20px;">
				<?php esc_html_e( 'Fill in the details below if your repository is private on Bitbucket. If your repository is public, credentials are not required.', 'kataskevi' ); ?>
			</p>

			<form method="post" action="<?php echo esc_url( admin_url( 'themes.php?page=kataskevi-updates' ) ); ?>">
				<?php wp_nonce_field( 'kataskevi_repo_settings_nonce', 'kataskevi_repo_nonce' ); ?>

				<table class="form-table" role="presentation">
					<tbody>
						<!-- Repository URL -->
						<tr>
							<th scope="row">
								<label for="kataskevi_bitbucket_repo"><?php esc_html_e( 'Bitbucket Repository URL', 'kataskevi' ); ?> <span class="required" style="color: #dc2626;">*</span></label>
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
								<p class="description"><?php esc_html_e( 'The full HTTPS web address of your Bitbucket repository, e.g. https://bitbucket.org/unionolivia/kataskevi', 'kataskevi' ); ?></p>
							</td>
						</tr>

						<!-- Release Branch -->
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
								<p class="description"><?php esc_html_e( 'The git branch that holds your release code (typically "main" or "master").', 'kataskevi' ); ?></p>
							</td>
						</tr>

						<!-- Bitbucket Username or Atlassian Email -->
						<tr>
							<th scope="row">
								<label for="kataskevi_bitbucket_user"><?php esc_html_e( 'Atlassian Email or Bitbucket Username', 'kataskevi' ); ?> <span class="required" style="color: #dc2626;">*</span></label>
							</th>
							<td>
								<input 
									name="kataskevi_bitbucket_user" 
									type="text" 
									id="kataskevi_bitbucket_user" 
									value="<?php echo esc_attr( $saved_user ); ?>" 
									class="regular-text" 
									autocomplete="off"
									placeholder="<?php esc_attr_e( 'e.g. olawalemichael335@gmail.com or x-token-auth', 'kataskevi' ); ?>"
									style="width: 100%; max-width: 480px;"
									<?php echo $has_const_user ? 'disabled' : ''; ?>
								>
								<?php if ( $has_const_user ) : ?>
									<p class="description" style="color: #0284c7;">
										<em><?php esc_html_e( 'Configured via KATASKEVI_BITBUCKET_USER constant in wp-config.php.', 'kataskevi' ); ?></em>
									</p>
								<?php else : ?>
									<p class="description">
										&bull; <strong><?php esc_html_e( 'Atlassian API Token:', 'kataskevi' ); ?></strong> <?php esc_html_e( 'Enter your registered email address (e.g. olawalemichael335@gmail.com).', 'kataskevi' ); ?><br>
										&bull; <strong><?php esc_html_e( 'Repository Access Token:', 'kataskevi' ); ?></strong> <?php esc_html_e( 'Enter "x-token-auth".', 'kataskevi' ); ?><br>
										&bull; <strong><?php esc_html_e( 'Bitbucket App Password:', 'kataskevi' ); ?></strong> <?php esc_html_e( 'Enter your Bitbucket account username.', 'kataskevi' ); ?>
									</p>
								<?php endif; ?>
							</td>
						</tr>

						<!-- Bitbucket App Password / Access Token -->
						<tr>
							<th scope="row">
								<label for="kataskevi_bitbucket_token"><?php esc_html_e( 'Bitbucket App Password', 'kataskevi' ); ?></label>
							</th>
							<td>
								<div style="position: relative; max-width: 480px;">
									<input 
										name="kataskevi_bitbucket_token" 
										type="password" 
										id="kataskevi_bitbucket_token" 
										value="<?php echo ! empty( $saved_token ) ? '********' : ''; ?>" 
										class="regular-text code" 
										autocomplete="new-password"
										placeholder="<?php esc_attr_e( 'Enter your App Password / API Token', 'kataskevi' ); ?>"
										style="width: 100%; padding-right: 40px;"
										<?php echo $has_const_token ? 'disabled' : ''; ?>
									>
								</div>
								<?php if ( $has_const_token ) : ?>
									<p class="description" style="color: #0284c7;">
										<em><?php esc_html_e( 'Configured via KATASKEVI_BITBUCKET_TOKEN constant in wp-config.php.', 'kataskevi' ); ?></em>
									</p>
								<?php else : ?>
									<p class="description">
										<?php esc_html_e( 'Generate a Bitbucket App Password with "Repositories: Read" permission. Leave unchanged to keep the currently stored token.', 'kataskevi' ); ?>
									</p>
								<?php endif; ?>
							</td>
						</tr>
					</tbody>
				</table>

				<p class="submit" style="margin-top: 24px; padding-top: 16px; border-top: 1px solid #f1f5f9;">
					<button type="submit" name="kataskevi_save_repo_settings" class="button button-primary" style="background: #3f75a1; border-color: #3f75a1; padding: 6px 18px; height: auto; font-size: 0.95rem;">
						<?php esc_html_e( 'Save Repository Settings', 'kataskevi' ); ?>
					</button>
				</p>
			</form>
		</div>

		<!-- Step-by-step Guide Card -->
		<div class="card" style="max-width: 100%; padding: 20px 24px; border-radius: 8px; border: 1px solid #ccd0d4; box-shadow: 0 1px 3px rgba(0,0,0,0.04); background: #fbfcfe;">
			<h3 style="margin-top: 0; font-size: 1.05rem; color: #1e293b; display: flex; align-items: center; gap: 8px;">
				<span>🔑</span>
				<span><?php esc_html_e( 'How to generate your Bitbucket Credentials (2 Easy Options)', 'kataskevi' ); ?></span>
			</h3>

			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; margin-top: 14px;">
				<!-- Option 1: Repository Access Token -->
				<div style="background: #fff; padding: 16px; border: 1px solid #e2e8f0; border-radius: 6px;">
					<h4 style="margin: 0 0 8px 0; color: #0284c7; font-size: 0.95rem;">
						🏆 <?php esc_html_e( 'Option A (Fastest): Repository Access Token', 'kataskevi' ); ?>
					</h4>
					<ol style="margin-left: 18px; line-height: 1.7; color: #475569; font-size: 0.88rem; padding: 0;">
						<li>Open your repo: <a href="https://bitbucket.org/unionolivia/kataskevi/admin/access-tokens" target="_blank" rel="noopener noreferrer"><strong><?php esc_html_e( 'Repository Access Tokens', 'kataskevi' ); ?></strong></a>.</li>
						<li>Click <strong><?php esc_html_e( 'Create repository access token', 'kataskevi' ); ?></strong>.</li>
						<li>Name it <code>Kataskevi Updater</code>.</li>
						<li>Under <strong>Repositories</strong>, check: <strong>Read</strong>.</li>
						<li>Click <strong>Create</strong> and copy the token.</li>
						<li>In the form above:
							<br>&bull; <strong>Username:</strong> <code>x-token-auth</code>
							<br>&bull; <strong>Token:</strong> paste your token.
						</li>
					</ol>
				</div>

				<!-- Option 2: Personal Bitbucket App Password -->
				<div style="background: #fff; padding: 16px; border: 1px solid #e2e8f0; border-radius: 6px;">
					<h4 style="margin: 0 0 8px 0; color: #0284c7; font-size: 0.95rem;">
						⚙️ <?php esc_html_e( 'Option B: Personal App Password', 'kataskevi' ); ?>
					</h4>
					<ol style="margin-left: 18px; line-height: 1.7; color: #475569; font-size: 0.88rem; padding: 0;">
						<li>In Bitbucket, click your <strong>Profile avatar</strong> (or the gear icon ⚙️) in the top-right corner.</li>
						<li>Click <strong>Personal Bitbucket settings</strong>.</li>
						<li>Under <strong>Access Management</strong> on the left, click <strong>App passwords</strong>.</li>
						<li>Click <strong>Create app password</strong>.</li>
						<li>Under <strong>Repositories</strong>, check: <strong>Read</strong>.</li>
						<li>In the form above:
							<br>&bull; <strong>Username:</strong> <code>unionolivia</code>
							<br>&bull; <strong>Token:</strong> paste your app password.
						</li>
					</ol>
				</div>
			</div>
		</div>

	</div>
	<?php
}
