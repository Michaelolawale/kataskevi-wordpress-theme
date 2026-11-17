<?php
/**
 * The header for Kataskevi theme
 * Redesigned UI matching ember-shield-systems.lovable.app
 *
 * Adheres strictly to the WordPress PHP Coding Standards (WPCS).
 *
 * @package Kataskevi
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$phone     = kataskevi_get_mod( 'kataskevi_phone', '+234 800 KATASKEVI' );
$emergency = kataskevi_get_mod( 'kataskevi_emergency_phone', '+234 802 345 6789' );
$email     = kataskevi_get_mod( 'kataskevi_email', 'info@kataskevi.com' );

// Resilient page links.
$services_url = kataskevi_get_page_url( 'services', 'Services' );
$works_url    = kataskevi_get_page_url( 'works', 'Our Works' );
$about_url    = kataskevi_get_page_url( 'about', 'About' );
$contact_url  = kataskevi_get_page_url( 'contact', 'Contact' );

$header_cta_text = kataskevi_get_mod( 'kataskevi_header_cta_text', 'Get a Quote' );
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<?php if ( is_front_page() ) : ?>
		<?php
		$hero_img_preload = function_exists( 'kataskevi_get_webp_url' ) 
			? kataskevi_get_webp_url( kataskevi_get_mod( 'kataskevi_hero_image', get_template_directory_uri() . '/assets/images/hero.jpg' ) )
			: kataskevi_get_mod( 'kataskevi_hero_image', get_template_directory_uri() . '/assets/images/hero.jpg' );
		?>
		<link rel="preload" as="image" href="<?php echo esc_url( $hero_img_preload ); ?>" fetchpriority="high">
	<?php endif; ?>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site-wrapper">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'kataskevi' ); ?></a>

	<!-- Sticky Header -->
	<header id="masthead" class="site-header">
		<div class="site-header__inner">
			<!-- Brand Logo -->
			<div class="site-branding">
				<?php 
				$header_logo = kataskevi_get_mod( 'kataskevi_header_logo', '' );
				if ( ! empty( $header_logo ) ) : 
				?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="custom-logo-link" rel="home" aria-label="<?php echo esc_attr( kataskevi_get_brand_name() ); ?>">
						<img src="<?php echo esc_url( $header_logo ); ?>" alt="<?php echo esc_attr( kataskevi_get_brand_name() ); ?>" class="custom-logo" />
					</a>
				<?php elseif ( has_custom_logo() ) : ?>
					<div class="site-logo">
						<?php the_custom_logo(); ?>
					</div>
				<?php else : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="brand-link" rel="home" aria-label="<?php echo esc_attr( kataskevi_get_brand_name() ); ?>">
						<span class="brand-emblem-wrap">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 44 44" width="32" height="32" fill="none" class="brand-emblem-svg">
								<defs>
									<linearGradient id="hdrShieldGrad" x1="6" y1="4" x2="38" y2="40" gradientUnits="userSpaceOnUse">
										<stop offset="0%" stop-color="#3f75a1"/>
										<stop offset="100%" stop-color="#18344f"/>
									</linearGradient>
									<linearGradient id="hdrEmberGrad" x1="22" y1="13" x2="22" y2="31" gradientUnits="userSpaceOnUse">
										<stop offset="0%" stop-color="#f59e0b"/>
										<stop offset="60%" stop-color="#ea580c"/>
										<stop offset="100%" stop-color="#dc2626"/>
									</linearGradient>
								</defs>
								<path d="M22 3.5 L38.5 9.5 V22.5 C38.5 31.8 31.2 38.2 22 41 C12.8 38.2 5.5 31.8 5.5 22.5 V9.5 L22 3.5 Z" stroke="url(#hdrShieldGrad)" stroke-width="2.5" stroke-linejoin="round" fill="#f8fafc"/>
								<path d="M22 7.5 L34.5 12 V22 C34.5 28.8 29.2 34 22 36.5 C14.8 34 9.5 28.8 9.5 22 V12 L22 7.5 Z" stroke="url(#hdrShieldGrad)" stroke-width="1.2" stroke-opacity="0.3" fill="none"/>
								<path d="M22 4 L5.5 10 V22.5 C5.5 31.8 12.8 38.2 22 41 V4 Z" fill="url(#hdrShieldGrad)" fill-opacity="0.05"/>
								<path d="M22 13 C20 16.5 16.5 19.5 16.5 23.5 C16.5 27.5 19 30.5 22 31 C25 30.5 27.5 27.5 27.5 23.5 C27.5 19.5 24 16.5 22 13 Z" fill="url(#hdrEmberGrad)"/>
								<path d="M22 18.5 L24 22.5 L22 26.5 L20 22.5 Z" fill="#ffffff" fill-opacity="0.95"/>
							</svg>
						</span>
					</a>
				<?php endif; ?>

				<?php if ( display_header_text() ) : ?>
					<div class="site-branding-text">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="brand-title-link" rel="home">
							<span class="brand-title"><?php echo esc_html( kataskevi_get_brand_name() ); ?></span>
						</a>
					</div>
				<?php endif; ?>
			</div>

			<!-- Desktop Navigation -->
			<nav id="site-navigation" class="site-nav" aria-label="<?php esc_attr_e( 'Main Menu', 'kataskevi' ); ?>">
				<?php
				if ( has_nav_menu( 'primary' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_id'        => 'primary-menu',
							'container'      => false,
							'menu_class'     => 'nav-links',
						)
					);
				} else {
					?>
					<ul id="primary-menu" class="nav-links">
						<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" <?php echo is_front_page() ? 'class="active"' : ''; ?>><?php esc_html_e( 'Home', 'kataskevi' ); ?></a></li>
						<li><a href="<?php echo esc_url( $services_url ); ?>" <?php echo is_page( 'services' ) ? 'class="active"' : ''; ?>><?php esc_html_e( 'Services', 'kataskevi' ); ?></a></li>
						<li><a href="<?php echo esc_url( $works_url ); ?>" <?php echo is_page( 'works' ) || is_singular( 'project' ) || is_post_type_archive( 'project' ) ? 'class="active"' : ''; ?>><?php esc_html_e( 'Our Works', 'kataskevi' ); ?></a></li>
						<li><a href="<?php echo esc_url( $about_url ); ?>" <?php echo is_page( 'about' ) ? 'class="active"' : ''; ?>><?php esc_html_e( 'About', 'kataskevi' ); ?></a></li>
						<li><a href="<?php echo esc_url( $contact_url ); ?>" <?php echo is_page( 'contact' ) ? 'class="active"' : ''; ?>><?php esc_html_e( 'Contact', 'kataskevi' ); ?></a></li>
					</ul>
					<?php
				}
				?>
				<?php if ( ! empty( $header_cta_text ) ) : ?>
					<a href="<?php echo esc_url( $contact_url ); ?>" class="btn-pill btn-pill-primary nav-cta">
						<?php echo esc_html( $header_cta_text ); ?>
					</a>
				<?php endif; ?>
			</nav>

			<!-- Mobile Hamburger Toggle -->
			<button id="mobile-menu-toggle" class="mobile-toggle" aria-controls="mobile-navigation" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle navigation', 'kataskevi' ); ?>">
				<?php echo kataskevi_render_icon( 'menu', 'icon icon-sm menu-open-icon' ); ?>
				<?php echo kataskevi_render_icon( 'close', 'icon icon-sm menu-close-icon' ); ?>
			</button>
		</div>

		<!-- Mobile Navigation Dropdown -->
		<div id="mobile-navigation" class="mobile-nav-drawer" aria-hidden="true">
			<nav class="mobile-nav-inner">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" <?php echo is_front_page() ? 'class="active"' : ''; ?>><?php esc_html_e( 'Home', 'kataskevi' ); ?></a>
				<a href="<?php echo esc_url( $services_url ); ?>" <?php echo is_page( 'services' ) ? 'class="active"' : ''; ?>><?php esc_html_e( 'Services', 'kataskevi' ); ?></a>
				<a href="<?php echo esc_url( $works_url ); ?>" <?php echo is_page( 'works' ) || is_singular( 'project' ) || is_post_type_archive( 'project' ) ? 'class="active"' : ''; ?>><?php esc_html_e( 'Our Works', 'kataskevi' ); ?></a>
				<a href="<?php echo esc_url( $about_url ); ?>" <?php echo is_page( 'about' ) ? 'class="active"' : ''; ?>><?php esc_html_e( 'About', 'kataskevi' ); ?></a>
				<a href="<?php echo esc_url( $contact_url ); ?>" <?php echo is_page( 'contact' ) ? 'class="active"' : ''; ?>><?php esc_html_e( 'Contact', 'kataskevi' ); ?></a>
				<?php if ( ! empty( $header_cta_text ) ) : ?>
					<a href="<?php echo esc_url( $contact_url ); ?>" class="btn-pill btn-pill-primary mobile-cta">
						<?php echo esc_html( $header_cta_text ); ?>
					</a>
				<?php endif; ?>
			</nav>
		</div>
	</header>
