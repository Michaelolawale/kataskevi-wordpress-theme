<?php
/**
 * The template for displaying the footer
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

$phone     = kataskevi_get_mod( 'kataskevi_phone', '+234 800 123 4567' );
$emergency = kataskevi_get_mod( 'kataskevi_emergency_phone', '+234 802 345 6789' );
$email     = kataskevi_get_mod( 'kataskevi_email', get_option( 'admin_email', 'info@example.com' ) );

$services_url = kataskevi_get_page_url( 'services', 'Services' );
$works_url    = kataskevi_get_page_url( 'works', 'Our Works' );
$about_url    = kataskevi_get_page_url( 'about', 'About' );
$contact_url  = kataskevi_get_page_url( 'contact', 'Contact' );

$services = kataskevi_get_services();
$socials  = kataskevi_get_social_links();

$footer_desc    = kataskevi_get_mod( 'kataskevi_footer_brand_desc', 'Engineering, Safety, Information Technology and Telecommunications support services — design, supply, installation and maintenance, end to end.' );
$footer_tagline = kataskevi_get_mod( 'kataskevi_footer_tagline', 'Design · Supply · Install · Maintain' );
?>

	<!-- MAIN SITE FOOTER -->
	<footer id="colophon" class="site-footer">
		<div class="container-editorial">
			<div class="footer-columns-grid">
				
				<!-- Col 1: Brand & Bio -->
				<div class="footer-col-brand">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-brand-link" rel="home">
						<?php
						$header_logo = kataskevi_get_mod( 'kataskevi_header_logo', '' );
						if ( ! empty( $header_logo ) ) :
							?>
							<img src="<?php echo esc_url( $header_logo ); ?>" alt="<?php echo esc_attr( kataskevi_get_brand_name() ); ?>" class="footer-custom-logo" />
							<?php
						elseif ( has_custom_logo() ) :
							$custom_logo_id = get_theme_mod( 'custom_logo' );
							$logo_src       = wp_get_attachment_image_src( $custom_logo_id, 'full' );
							if ( ! empty( $logo_src[0] ) ) :
								?>
								<img src="<?php echo esc_url( $logo_src[0] ); ?>" alt="<?php echo esc_attr( kataskevi_get_brand_name() ); ?>" class="footer-custom-logo" />
								<?php
							endif;
						endif;
						?>
						<span class="footer-brand-title"><?php echo esc_html( kataskevi_get_brand_name() ); ?></span>
					</a>
					<?php if ( ! empty( $footer_desc ) ) : ?>
						<p class="footer-brand-desc">
							<?php echo esc_html( $footer_desc ); ?>
						</p>
					<?php endif; ?>

					<!-- Social Channels -->
					<?php if ( ! empty( $socials ) ) : ?>
						<div class="footer-social-strip" aria-label="<?php echo esc_attr( sprintf( __( 'Follow %s', 'kataskevi' ), kataskevi_get_brand_name() ) ); ?>">
							<?php foreach ( $socials as $s_key => $s_item ) : ?>
								<a 
									href="<?php echo esc_url( $s_item['url'] ); ?>" 
									target="_blank" 
									rel="noopener noreferrer" 
									class="footer-social-btn" 
									aria-label="<?php echo esc_attr( $s_item['name'] ); ?>"
									title="<?php echo esc_attr( $s_item['name'] ); ?>"
								>
									<?php echo kataskevi_render_icon( $s_item['icon'], 'icon icon-xs' ); ?>
								</a>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>

				<!-- Col 2: Company Navigation -->
				<div class="footer-col-nav">
					<h3 class="footer-heading"><?php esc_html_e( 'Company', 'kataskevi' ); ?></h3>
					<ul class="footer-links-list">
						<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'kataskevi' ); ?></a></li>
						<li><a href="<?php echo esc_url( $services_url ); ?>"><?php esc_html_e( 'Services', 'kataskevi' ); ?></a></li>
						<li><a href="<?php echo esc_url( $works_url ); ?>"><?php esc_html_e( 'Our Works', 'kataskevi' ); ?></a></li>
						<li><a href="<?php echo esc_url( $about_url ); ?>"><?php esc_html_e( 'About', 'kataskevi' ); ?></a></li>
						<li><a href="<?php echo esc_url( $contact_url ); ?>"><?php esc_html_e( 'Contact', 'kataskevi' ); ?></a></li>
					</ul>
				</div>

				<!-- Col 3: Services List -->
				<div class="footer-col-nav">
					<h3 class="footer-heading"><?php esc_html_e( 'Services', 'kataskevi' ); ?></h3>
					<ul class="footer-links-list">
						<?php foreach ( $services as $s ) : ?>
							<li>
								<a href="<?php echo esc_url( $services_url . '#' . $s['id'] ); ?>">
									<?php echo esc_html( $s['title'] ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>

				<!-- Col 4: Get in Touch -->
				<div class="footer-col-contact">
					<h3 class="footer-heading"><?php esc_html_e( 'Get in Touch', 'kataskevi' ); ?></h3>
					<?php if ( ! empty( $email ) || ! empty( $phone ) ) : ?>
						<ul class="footer-links-list footer-contact-direct-list">
							<?php if ( ! empty( $email ) ) : ?>
								<li>
									<a href="mailto:<?php echo esc_attr( $email ); ?>" class="footer-mail-link">
										<?php echo kataskevi_render_icon( 'mail', 'icon icon-xs' ); ?>
										<span><?php echo esc_html( $email ); ?></span>
									</a>
								</li>
							<?php endif; ?>
							<?php if ( ! empty( $phone ) ) : ?>
								<li>
									<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>" class="footer-phone-link">
										<?php echo kataskevi_render_icon( 'phone', 'icon icon-xs' ); ?>
										<span><?php echo esc_html( $phone ); ?></span>
									</a>
								</li>
							<?php endif; ?>
						</ul>
					<?php endif; ?>
					<a href="<?php echo esc_url( $contact_url ); ?>" class="btn-pill btn-pill-outline btn-sm footer-quote-btn">
						<?php esc_html_e( 'Request a Quote', 'kataskevi' ); ?>
					</a>
				</div>

			</div>

			<!-- Bottom Copyright Bar -->
			<div class="footer-bottom-bar">
				<p class="footer-copyright">
					&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( kataskevi_get_brand_name() ); ?>. <?php esc_html_e( 'Engineering, Safety, IT & Telecommunications Support Services.', 'kataskevi' ); ?>
				</p>
				<?php if ( ! empty( $footer_tagline ) ) : ?>
					<span class="footer-tagline">
						<?php echo esc_html( $footer_tagline ); ?>
					</span>
				<?php endif; ?>
			</div>
		</div>
	</footer>

</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
