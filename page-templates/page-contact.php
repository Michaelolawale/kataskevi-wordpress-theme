<?php
/**
 * Template Name: Contact & Site Survey
 * Description: The contact and site survey request page for Kataskevi.
 *
 * Adheres strictly to the WordPress PHP Coding Standards (WPCS).
 * All text is fully customizable via Appearance -> Customize.
 *
 * @package Kataskevi
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$services     = kataskevi_get_services();
$email        = kataskevi_get_mod( 'kataskevi_email', 'info@kataskevi.com' );
$phone        = kataskevi_get_mod( 'kataskevi_phone', '+234 800 KATASKEVI' );
$emergency    = kataskevi_get_mod( 'kataskevi_emergency_phone', '+234 802 345 6789' );
$address      = kataskevi_get_mod( 'kataskevi_address', 'Engineering Hub, Commercial District, Victoria Island, Lagos' );
$services_url = kataskevi_get_page_url( 'services', 'Services' );

$selected_service = isset( $_GET['service'] ) ? sanitize_text_field( wp_unslash( $_GET['service'] ) ) : '';

// Customizer Theme Options.
$contact_eyebrow  = kataskevi_get_mod( 'kataskevi_contact_page_eyebrow', 'Contact' );
$contact_title    = kataskevi_get_mod( 'kataskevi_contact_page_title', 'Let\'s talk about <em class="text-primary italic">your site.</em>' );
$contact_desc     = kataskevi_get_mod( 'kataskevi_contact_page_desc', 'Request a quote, book a site survey, or ask about maintenance for an existing system — our team will respond promptly.' );
$urgent_title     = kataskevi_get_mod( 'kataskevi_contact_urgent_title', 'Need urgent support?' );
$urgent_desc      = kataskevi_get_mod( 'kataskevi_contact_urgent_desc', 'For active alarm faults, critical camera outages, or urgent life-safety requirements, contact our 24/7 technical desk directly:' );
?>

<main id="primary" class="site-main">

	<!-- HERO -->
	<section class="section-page-hero">
		<div class="container-editorial">
			<p class="eyebrow"><?php echo esc_html( $contact_eyebrow ); ?></p>
			<h1 class="page-hero-title">
				<?php echo wp_kses_post( $contact_title ); ?>
			</h1>
			<p class="page-hero-desc">
				<?php echo esc_html( $contact_desc ); ?>
			</p>
		</div>
	</section>

	<!-- CONTACT MAIN SPLIT -->
	<section class="section-contact-split">
		<div class="container-editorial pb-20">
			<div class="contact-editorial-layout">

				<!-- LEFT COLUMN: DIRECT CHANNELS -->
				<div class="contact-editorial-info">
					<div class="contact-channels-stack border-y border-border">
						<div class="channel-stack-item">
							<h3 class="channel-stack-label"><?php esc_html_e( 'Email', 'kataskevi' ); ?></h3>
							<a href="mailto:<?php echo esc_attr( $email ); ?>" class="channel-stack-link"><?php echo esc_html( $email ); ?></a>
						</div>

						<div class="channel-stack-item">
							<h3 class="channel-stack-label"><?php esc_html_e( 'Phone', 'kataskevi' ); ?></h3>
							<p class="channel-stack-text"><?php esc_html_e( 'Speak with our engineering team during business hours.', 'kataskevi' ); ?></p>
							<?php if ( ! empty( $phone ) ) : ?>
								<a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $phone ) ); ?>" class="channel-stack-sublink">
									<?php echo esc_html( $phone ); ?>
								</a>
							<?php endif; ?>
						</div>

						<div class="channel-stack-item">
							<h3 class="channel-stack-label"><?php esc_html_e( 'Headquarters', 'kataskevi' ); ?></h3>
							<p class="channel-stack-text"><?php echo esc_html( $address ); ?></p>
						</div>
					</div>

					<!-- URGENT SYSTEM ISSUE BOX -->
					<div class="urgent-editorial-box mt-8">
						<h3 class="urgent-editorial-title"><?php echo esc_html( $urgent_title ); ?></h3>
						<p class="urgent-editorial-desc">
							<?php echo esc_html( $urgent_desc ); ?>
						</p>
						<?php if ( ! empty( $emergency ) ) : ?>
							<a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $emergency ) ); ?>" class="urgent-editorial-link">
								<span><?php echo esc_html( $emergency ); ?> (24/7 Desk)</span>
								<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
							</a>
						<?php else : ?>
							<a href="<?php echo esc_url( $services_url ); ?>" class="urgent-editorial-link">
								<span><?php esc_html_e( 'See our service lines', 'kataskevi' ); ?></span>
								<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
							</a>
						<?php endif; ?>
					</div>
				</div>

				<!-- RIGHT COLUMN: INTERACTIVE EDITORIAL FORM -->
				<div class="contact-editorial-card">
					<form id="editorial-survey-form" class="editorial-interactive-form" method="post" action="">
						<?php wp_nonce_field( 'kataskevi_inquiry_action', 'kataskevi_inquiry_nonce' ); ?>
						
						<div id="form-feedback-alert" class="form-status-alert" style="display: none;" role="alert"></div>

						<div class="form-row-2col">
							<div class="form-input-group">
								<label for="contact-name"><?php esc_html_e( 'Full Name', 'kataskevi' ); ?> <span class="required" aria-hidden="true">*</span></label>
								<input type="text" id="contact-name" name="fullname" class="input-editorial" placeholder="<?php esc_attr_e( 'Engr. Alex Morgan', 'kataskevi' ); ?>" required>
							</div>

							<div class="form-input-group">
								<label for="contact-email"><?php esc_html_e( 'Work Email', 'kataskevi' ); ?> <span class="required" aria-hidden="true">*</span></label>
								<input type="email" id="contact-email" name="email" class="input-editorial" placeholder="<?php esc_attr_e( 'name@organization.com', 'kataskevi' ); ?>" required>
							</div>
						</div>

						<div class="form-row-2col">
							<div class="form-input-group">
								<label for="contact-phone"><?php esc_html_e( 'Phone Number', 'kataskevi' ); ?></label>
								<input type="tel" id="contact-phone" name="phone" class="input-editorial" placeholder="<?php esc_attr_e( '+234 ...', 'kataskevi' ); ?>">
							</div>

							<div class="form-input-group">
								<label for="contact-org"><?php esc_html_e( 'Company / Organization', 'kataskevi' ); ?></label>
								<input type="text" id="contact-org" name="organization" class="input-editorial" placeholder="<?php esc_attr_e( 'Enterprise Facility Ltd', 'kataskevi' ); ?>">
							</div>
						</div>

						<div class="form-input-group">
							<label for="contact-service"><?php esc_html_e( 'Primary System of Interest', 'kataskevi' ); ?></label>
							<select id="contact-service" name="service" class="input-editorial">
								<option value=""><?php esc_html_e( 'Select service or project type...', 'kataskevi' ); ?></option>
								<?php foreach ( $services as $s ) : ?>
									<option value="<?php echo esc_attr( $s['id'] ); ?>" <?php selected( $selected_service, $s['id'] ); ?>>
										<?php echo esc_html( $s['title'] ); ?>
									</option>
								<?php endforeach; ?>
								<option value="general-audit"><?php esc_html_e( 'Full Site Audit & Survey', 'kataskevi' ); ?></option>
								<option value="maintenance"><?php esc_html_e( 'Preventive Maintenance Agreement', 'kataskevi' ); ?></option>
							</select>
						</div>

						<div class="form-input-group">
							<label for="contact-message"><?php esc_html_e( 'Project Scope or Site Details', 'kataskevi' ); ?> <span class="required" aria-hidden="true">*</span></label>
							<textarea id="contact-message" name="message" rows="5" class="input-editorial" placeholder="<?php esc_attr_e( 'Tell us about your facility, location, timeline, and current requirements...', 'kataskevi' ); ?>" required></textarea>
						</div>

						<div class="form-action-row">
							<button type="submit" id="submit-inquiry-btn" class="btn-pill btn-pill-primary w-full sm:w-auto">
								<span><?php esc_html_e( 'Submit Consultation Request', 'kataskevi' ); ?></span>
								<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
							</button>
						</div>
					</form>
				</div>

			</div>
		</div>
	</section>

</main>

<?php
get_footer();
