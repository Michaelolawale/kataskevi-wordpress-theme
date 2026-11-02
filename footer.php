<?php
/**
 * The template for displaying the footer
 * Redesigned UI matching ember-shield-systems.lovable.app
 *
 * @package Kataskevi
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$phone     = get_theme_mod( 'kataskevi_phone', '+234 800 KATASKEVI' );
$emergency = get_theme_mod( 'kataskevi_emergency_phone', '+234 802 345 6789' );
$email     = get_theme_mod( 'kataskevi_email', 'info@kataskevi.com' );

$services_url = kataskevi_get_page_url( 'services', 'Services' );
$works_url    = kataskevi_get_page_url( 'works', 'Our Works' );
$about_url    = kataskevi_get_page_url( 'about', 'About' );
$contact_url  = kataskevi_get_page_url( 'contact', 'Contact' );

$services = kataskevi_get_services();
$socials  = kataskevi_get_social_links();
?>

	<!-- MAIN SITE FOOTER -->
	<footer id="colophon" class="site-footer">
		<div class="container-editorial">
			<div class="footer-columns-grid">
				
				<!-- Col 1: Brand & Bio -->
				<div class="footer-col-brand">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-brand-link" rel="home">
						<span class="footer-brand-title">Kataskevi</span>
					</a>
					<p class="footer-brand-desc">
						Engineering, Safety, Information Technology and Telecommunications support services — design, supply, installation and maintenance, end to end.
					</p>

					<!-- Social Channels -->
					<?php if ( ! empty( $socials ) ) : ?>
						<div class="footer-social-strip" aria-label="<?php esc_attr_e( 'Follow Kataskevi', 'kataskevi' ); ?>">
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
					<h3 class="footer-heading">Company</h3>
					<ul class="footer-links-list">
						<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
						<li><a href="<?php echo esc_url( $services_url ); ?>">Services</a></li>
						<li><a href="<?php echo esc_url( $works_url ); ?>">Our Works</a></li>
						<li><a href="<?php echo esc_url( $about_url ); ?>">About</a></li>
						<li><a href="<?php echo esc_url( $contact_url ); ?>">Contact</a></li>
					</ul>
				</div>

				<!-- Col 3: Services List -->
				<div class="footer-col-nav">
					<h3 class="footer-heading">Services</h3>
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
					<h3 class="footer-heading">Get in Touch</h3>
					<ul class="footer-links-list footer-contact-direct-list">
						<li>
							<a href="mailto:<?php echo esc_attr( $email ); ?>" class="footer-mail-link">
								<?php echo kataskevi_render_icon( 'mail', 'icon icon-xs' ); ?>
								<span><?php echo esc_html( $email ); ?></span>
							</a>
						</li>
						<?php if ( ! empty( $phone ) ) : ?>
							<li>
								<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>" class="footer-phone-link">
									<?php echo kataskevi_render_icon( 'phone', 'icon icon-xs' ); ?>
									<span><?php echo esc_html( $phone ); ?></span>
								</a>
							</li>
						<?php endif; ?>
					</ul>
					<a href="<?php echo esc_url( $contact_url ); ?>" class="btn-pill btn-pill-outline btn-sm footer-quote-btn">
						Request a Quote
					</a>
				</div>

			</div>

			<!-- Bottom Copyright Bar -->
			<div class="footer-bottom-bar">
				<p class="footer-copyright">
					&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> Kataskevi. Engineering, Safety, IT &amp; Telecommunications Support Services.
				</p>
				<span class="footer-tagline">
					Design · Supply · Install · Maintain
				</span>
			</div>
		</div>
	</footer>

</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
