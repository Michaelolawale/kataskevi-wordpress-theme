<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * Adheres strictly to the WordPress PHP Coding Standards (WPCS).
 *
 * @package Kataskevi
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$services_url = kataskevi_get_page_url( 'services', 'Services' );
?>

<main id="primary" class="site-main">
	<section class="section-page-hero" style="min-height: 60vh; display: flex; align-items: center;">
		<div class="container-editorial">
			<p class="eyebrow"><?php esc_html_e( '404 Error', 'kataskevi' ); ?></p>
			<h1 class="page-hero-title">
				<?php esc_html_e( 'Page not', 'kataskevi' ); ?> <em class="text-primary italic"><?php esc_html_e( 'found.', 'kataskevi' ); ?></em>
			</h1>
			<p class="page-hero-desc">
				<?php esc_html_e( 'The page you requested could not be located or may have moved. You can return to our homepage or explore our turnkey engineering and safety services.', 'kataskevi' ); ?>
			</p>
			<div class="hero-actions" style="margin-top: 2.5rem;">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-pill btn-pill-primary">
					<span><?php esc_html_e( 'Return to Home', 'kataskevi' ); ?></span>
					<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
				</a>
				<a href="<?php echo esc_url( $services_url ); ?>" class="btn-pill btn-pill-outline">
					<span><?php esc_html_e( 'Explore Services', 'kataskevi' ); ?></span>
				</a>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
