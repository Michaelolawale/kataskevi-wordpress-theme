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

$form_feedback = null;
if ( 'POST' === ( $_SERVER['REQUEST_METHOD'] ?? '' ) && ( isset( $_POST['kataskevi_inquiry_nonce'] ) || isset( $_POST['nonce'] ) ) ) {
	$submitted_nonce = isset( $_POST['kataskevi_inquiry_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['kataskevi_inquiry_nonce'] ) ) : sanitize_text_field( wp_unslash( $_POST['nonce'] ?? '' ) );
	if ( wp_verify_nonce( $submitted_nonce, 'kataskevi_inquiry_action' ) || wp_verify_nonce( $submitted_nonce, 'kataskevi_inquiry_nonce' ) ) {
		$name         = isset( $_POST['fullname'] ) ? sanitize_text_field( wp_unslash( $_POST['fullname'] ) ) : '';
		$email        = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
		$phone        = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
		$organization = isset( $_POST['organization'] ) ? sanitize_text_field( wp_unslash( $_POST['organization'] ) ) : '';
		$service      = isset( $_POST['service'] ) ? sanitize_text_field( wp_unslash( $_POST['service'] ) ) : '';
		$message      = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

		if ( empty( $name ) || empty( $email ) || empty( $message ) || ! is_email( $email ) ) {
			$form_feedback = array(
				'type'    => 'error',
				'message' => __( 'Please complete all required fields (Full Name, Work Email, and Project Scope) with a valid email.', 'kataskevi' ),
			);
		} else {
			$admin_email = get_theme_mod( 'kataskevi_email', get_option( 'admin_email' ) );
			$brand_name  = kataskevi_get_brand_name();
			/* translators: 1: Brand name, 2: Customer name */
			$subject     = sprintf( '[%1$s Inquiry] New Consultation Request from %2$s', $brand_name, $name );
			$body        = "You have received a new consultation / site survey request:\n\n";
			$body       .= "Full Name: $name\n";
			$body       .= "Email: $email\n";
			$body       .= "Phone: " . ( ! empty( $phone ) ? $phone : 'Not provided' ) . "\n";
			$body       .= "Company / Organization: " . ( ! empty( $organization ) ? $organization : 'Not provided' ) . "\n";
			$body       .= "Service Required: " . ( ! empty( $service ) ? $service : 'General Audit / Inquiries' ) . "\n\n";
			$body       .= "Project Scope / Notes:\n$message\n\n";
			$body       .= "---\nSent from: " . home_url() . "\n";

			$headers = array(
				'Content-Type: text/plain; charset=UTF-8',
				"Reply-To: $name <$email>",
			);

			@wp_mail( $admin_email, $subject, $body, $headers );

			$inquiries = get_option( 'kataskevi_recent_inquiries', array() );
			if ( ! is_array( $inquiries ) ) {
				$inquiries = array();
			}
			array_unshift(
				$inquiries,
				array(
					'date'         => current_time( 'mysql' ),
					'name'         => $name,
					'email'        => $email,
					'phone'        => $phone,
					'organization' => $organization,
					'service'      => $service,
					'message'      => $message,
				)
			);
			$inquiries = array_slice( $inquiries, 0, 50 );
			update_option( 'kataskevi_recent_inquiries', $inquiries, false );

			$form_feedback = array(
				'type'    => 'success',
				'message' => __( 'Thank you! Your inquiry has been received. A Kataskevi technical specialist will contact you within 24 hours.', 'kataskevi' ),
			);
		}
	} else {
		$form_feedback = array(
			'type'    => 'error',
			'message' => __( 'Security verification failed. Please try again.', 'kataskevi' ),
		);
	}
}

get_header();

$services     = kataskevi_get_services();
$email        = kataskevi_get_mod( 'kataskevi_email', get_option( 'admin_email', 'info@example.com' ) );
$phone        = kataskevi_get_mod( 'kataskevi_phone', '+234 800 123 4567' );
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
			<?php if ( ! empty( $contact_eyebrow ) ) : ?>
				<p class="eyebrow"><?php echo esc_html( $contact_eyebrow ); ?></p>
			<?php endif; ?>
			<?php if ( ! empty( $contact_title ) ) : ?>
				<h1 class="page-hero-title">
					<?php echo wp_kses_post( $contact_title ); ?>
				</h1>
			<?php endif; ?>
			<?php if ( ! empty( $contact_desc ) ) : ?>
				<p class="page-hero-desc">
					<?php echo esc_html( $contact_desc ); ?>
				</p>
			<?php endif; ?>
		</div>
	</section>

	<!-- CONTACT MAIN SPLIT -->
	<section class="section-contact-split">
		<div class="container-editorial pb-20">
			<div class="contact-editorial-layout">

				<!-- LEFT COLUMN: DIRECT CHANNELS -->
				<div class="contact-editorial-info">
					<?php if ( ! empty( $email ) || ! empty( $phone ) || ! empty( $address ) ) : ?>
						<div class="contact-channels-stack border-y border-border">
							<?php if ( ! empty( $email ) ) : ?>
								<div class="channel-stack-item">
									<h3 class="channel-stack-label"><?php esc_html_e( 'Email', 'kataskevi' ); ?></h3>
									<a href="mailto:<?php echo esc_attr( $email ); ?>" class="channel-stack-link"><?php echo esc_html( $email ); ?></a>
								</div>
							<?php endif; ?>

							<?php if ( ! empty( $phone ) ) : ?>
								<div class="channel-stack-item">
									<h3 class="channel-stack-label"><?php esc_html_e( 'Phone', 'kataskevi' ); ?></h3>
									<p class="channel-stack-text"><?php esc_html_e( 'Speak with our engineering team during business hours.', 'kataskevi' ); ?></p>
									<a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $phone ) ); ?>" class="channel-stack-sublink">
										<?php echo esc_html( $phone ); ?>
									</a>
								</div>
							<?php endif; ?>

							<?php if ( ! empty( $address ) ) : ?>
								<div class="channel-stack-item">
									<h3 class="channel-stack-label"><?php esc_html_e( 'Headquarters', 'kataskevi' ); ?></h3>
									<p class="channel-stack-text"><?php echo esc_html( $address ); ?></p>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<!-- URGENT SYSTEM ISSUE BOX -->
					<?php if ( ! empty( $urgent_title ) || ! empty( $urgent_desc ) || ! empty( $emergency ) ) : ?>
						<div class="urgent-editorial-box mt-8">
							<?php if ( ! empty( $urgent_title ) ) : ?>
								<h3 class="urgent-editorial-title"><?php echo esc_html( $urgent_title ); ?></h3>
							<?php endif; ?>
							<?php if ( ! empty( $urgent_desc ) ) : ?>
								<p class="urgent-editorial-desc">
									<?php echo esc_html( $urgent_desc ); ?>
								</p>
							<?php endif; ?>
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
					<?php endif; ?>
				</div>

				<!-- RIGHT COLUMN: INTERACTIVE EDITORIAL FORM -->
				<div class="contact-editorial-card">
					<form id="editorial-survey-form" class="editorial-interactive-form" method="post" action="">
						<?php wp_nonce_field( 'kataskevi_inquiry_action', 'kataskevi_inquiry_nonce' ); ?>
						
						<div id="form-feedback-alert" class="form-status-alert <?php echo ! empty( $form_feedback['type'] ) ? esc_attr( $form_feedback['type'] ) : ''; ?>" style="<?php echo ! empty( $form_feedback['message'] ) ? 'display: block;' : 'display: none;'; ?>" role="alert">
							<?php echo ! empty( $form_feedback['message'] ) ? esc_html( $form_feedback['message'] ) : ''; ?>
						</div>

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

	<!-- OFFICE LOCATION MAP -->
	<?php
	$default_embed = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2844.38140054598!2d3.4053212!3d6.4476227!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x103b8b248e126613%3A0x58cb7affcae5e3a7!2s48%20King%20George%20V%20Rd%2C%20Lagos%20Island%2C%20Lagos%20234001%2C%20Lagos!5e1!3m2!1sen!2sng!4v1794913471007!5m2!1sen!2sng';

	$show_map    = (bool) get_option( 'kataskevi_contact_show_map', kataskevi_get_mod( 'kataskevi_contact_show_map', 1 ) );
	$map_eyebrow = get_option( 'kataskevi_contact_map_eyebrow', kataskevi_get_mod( 'kataskevi_contact_map_eyebrow', 'Visit Our Office' ) );
	$map_title   = get_option( 'kataskevi_contact_map_title', kataskevi_get_mod( 'kataskevi_contact_map_title', 'Engineering Headquarters & Consultation Hub' ) );
	$map_desc    = get_option( 'kataskevi_contact_map_desc', kataskevi_get_mod( 'kataskevi_contact_map_desc', 'Located in Victoria Island, Lagos. We welcome blueprint review meetings, technical audits, and project consultations.' ) );
	$map_embed   = get_option( 'kataskevi_contact_map_embed', kataskevi_get_mod( 'kataskevi_contact_map_embed', $default_embed ) );

	if ( empty( $map_embed ) ) {
		$map_embed = $default_embed;
	}
	?>
	<?php if ( $show_map ) : ?>
		<section class="section-contact-map">
			<div class="container-editorial">
				<div class="contact-map-header text-center">
					<?php if ( ! empty( $map_eyebrow ) ) : ?>
						<p class="eyebrow"><?php echo esc_html( $map_eyebrow ); ?></p>
					<?php endif; ?>
					<?php if ( ! empty( $map_title ) ) : ?>
						<h2 class="contact-map-title"><?php echo esc_html( $map_title ); ?></h2>
					<?php endif; ?>
					<?php if ( ! empty( $map_desc ) ) : ?>
						<p class="contact-map-desc"><?php echo esc_html( $map_desc ); ?></p>
					<?php endif; ?>
				</div>

				<div class="contact-map-frame">
					<?php
					$parsed_map_url = function_exists( 'kataskevi_parse_map_url' ) ? kataskevi_parse_map_url( $map_embed ) : '';
					$embed_src      = ! empty( $parsed_map_url ) ? $parsed_map_url : $default_embed;
					?>
					<iframe 
						src="<?php echo esc_url( $embed_src ); ?>" 
						width="100%" 
						height="450" 
						style="border:0;" 
						allowfullscreen="" 
						loading="lazy" 
						referrerpolicy="no-referrer-when-downgrade"
						title="<?php esc_attr_e( 'Headquarters Location Map', 'kataskevi' ); ?>"
					></iframe>
				</div>
			</div>
		</section>
	<?php endif; ?>

</main>

<?php
get_footer();
