<?php
/**
 * Theme Update Checker Integration & Admin UI
 *
 * Implements YahnisElsts Plugin Update Checker (PUC) v6.0 for automatic
 * theme updates from Bitbucket repository (https://bitbucket.org/unionolivia/kataskevi).
 * Includes an administrative backend interface under Appearance -> Theme Updates
 * allowing administrators to configure private repository credentials, test connectivity,
 * and check for theme releases.
 *
 * Adheres strictly to the WordPress PHP Coding Standards (WPCS).
 *
 * @package Kataskevi
 * @version 2.0.0
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

		wp_safe_redirect( add_query_arg( array( 'page' => 'kataskevi-updates', 'updated' => '1' ), admin_url( 'themes.php' ) ) );
		exit;
	}

	// 2. Handle Manual "Check for Updates" Button.
	if ( isset( $_GET['action'] ) && 'check_now' === $_GET['action'] ) {
		check_admin_referer( 'kataskevi_manual_check_nonce' );

		global $kataskevi_update_checker;

		if ( $kataskevi_update_checker && method_exists( $kataskevi_update_checker, 'checkForUpdates' ) ) {
			$update = $kataskevi_update_checker->checkForUpdates();
			if ( $update ) {
				$status = 'update_available';
			} else {
				$status = 'up_to_date';
			}
		} else {
			$status = 'check_error';
		}

		wp_safe_redirect( add_query_arg( array( 'page' => 'kataskevi-updates', 'check_result' => $status ), admin_url( 'themes.php' ) ) );
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
	$current_ver   = $theme->exists() ? $theme->get( 'Version' ) : '2.0.0';
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
			<div class="notice notice-error is-dismissible">
				<p><strong>⚠️ <?php esc_html_e( 'Could not fetch updates from Bitbucket. Please check your repository URL and credentials below.', 'kataskevi' ); ?></strong></p>
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
							○ <?php esc_html_e( 'Public Access Only', 'kataskevi' ); ?>
						</span>
					<?php endif; ?>
				</div>
			</div>

			<div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-top: 16px; padding-top: 16px; border-top: 1px solid #f1f5f9;">
				<div style="font-size: 0.875rem; color: #64748b;">
					<span><?php esc_html_e( 'Remote:', 'kataskevi' ); ?></span>
					<a href="<?php echo esc_url( $saved_repo ); ?>" target="_blank" rel="noopener noreferrer" style="font-family: monospace; color: #3f75a1;">
						<?php echo esc_html( $saved_repo ); ?>
					</a>
				</div>
				<div>
					<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'themes.php?page=kataskevi-updates&action=check_now' ), 'kataskevi_manual_check_nonce' ) ); ?>" class="button button-secondary">
						🔄 <?php esc_html_e( 'Check for Updates Now', 'kataskevi' ); ?>
					</a>
				</div>
			</div>
		</div>

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

						<!-- Bitbucket Username -->
						<tr>
							<th scope="row">
								<label for="kataskevi_bitbucket_user"><?php esc_html_e( 'Bitbucket Username', 'kataskevi' ); ?></label>
							</th>
							<td>
								<input 
									name="kataskevi_bitbucket_user" 
									type="text" 
									id="kataskevi_bitbucket_user" 
									value="<?php echo esc_attr( $saved_user ); ?>" 
									class="regular-text" 
									autocomplete="off"
									placeholder="<?php esc_attr_e( 'e.g. unionolivia', 'kataskevi' ); ?>"
									style="width: 100%; max-width: 480px;"
									<?php echo $has_const_user ? 'disabled' : ''; ?>
								>
								<?php if ( $has_const_user ) : ?>
									<p class="description" style="color: #0284c7;">
										<em><?php esc_html_e( 'Configured via KATASKEVI_BITBUCKET_USER constant in wp-config.php.', 'kataskevi' ); ?></em>
									</p>
								<?php else : ?>
									<p class="description"><?php esc_html_e( 'Your Bitbucket account username (not your email address).', 'kataskevi' ); ?></p>
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
				<span><?php esc_html_e( 'How to generate your Bitbucket App Password (2 minutes)', 'kataskevi' ); ?></span>
			</h3>
			<ol style="margin-left: 20px; line-height: 1.8; color: #475569; font-size: 0.92rem;">
				<li><?php esc_html_e( 'Log into Bitbucket and open your ', 'kataskevi' ); ?><a href="https://bitbucket.org/account/settings/app-passwords/" target="_blank" rel="noopener noreferrer"><strong><?php esc_html_e( 'Personal Settings &rarr; App passwords', 'kataskevi' ); ?></strong></a>.</li>
				<li><?php esc_html_e( 'Click the ', 'kataskevi' ); ?><strong><?php esc_html_e( 'Create app password', 'kataskevi' ); ?></strong><?php esc_html_e( ' button.', 'kataskevi' ); ?></li>
				<li><?php esc_html_e( 'Set the Label to ', 'kataskevi' ); ?><code>Kataskevi Theme Updater</code>.</li>
				<li><?php esc_html_e( 'Under ', 'kataskevi' ); ?><strong><?php esc_html_e( 'Permissions', 'kataskevi' ); ?></strong>, <?php esc_html_e( 'check only ', 'kataskevi' ); ?><strong><?php esc_html_e( 'Repositories &rarr; Read', 'kataskevi' ); ?></strong> (<code>read:repository:bitbucket</code>).</li>
				<li><?php esc_html_e( 'Click ', 'kataskevi' ); ?><strong><?php esc_html_e( 'Create', 'kataskevi' ); ?></strong>, <?php esc_html_e( 'copy the generated password, and paste it into the field above.', 'kataskevi' ); ?></li>
			</ol>
		</div>

	</div>
	<?php
}
