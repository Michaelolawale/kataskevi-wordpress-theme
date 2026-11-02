<?php
/**
 * Template Name: Contact & Site Survey
 * Redesigned UI matching ember-shield-systems.lovable.app
 *
 * @package Kataskevi
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$services  = kataskevi_get_services();
$email     = get_theme_mod( 'kataskevi_email', 'info@kataskevi.com' );
$phone     = get_theme_mod( 'kataskevi_phone', '+234 800 KATASKEVI' );

$services_url = kataskevi_get_page_url( 'services', 'Services' );

$selected_service = isset( $_GET['service'] ) ? sanitize_text_field( wp_unslash( $_GET['service'] ) ) : '';
?>

<main id="primary" class="site-main">

	<!-- HERO -->
	<section class="section-page-hero">
		<div class="container-editorial">
			<p class="eyebrow">Contact</p>
			<h1 class="page-hero-title">
				Let's talk about <em class="text-primary italic">your site.</em>
			</h1>
			<p class="page-hero-desc">
				Request a quote, book a site survey, or ask about maintenance for an existing system — our team will respond promptly.
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
							<h3 class="channel-stack-label">Email</h3>
							<a href="mailto:<?php echo esc_attr( $email ); ?>" class="channel-stack-link"><?php echo esc_html( $email ); ?></a>
						</div>

						<div class="channel-stack-item">
							<h3 class="channel-stack-label">Phone</h3>
							<p class="channel-stack-text">Speak with our engineering team during business hours.</p>
							<?php if ( ! empty( $phone ) ) : ?>
								<a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $phone ) ); ?>" class="channel-stack-sublink">
									<?php echo esc_html( $phone ); ?>
								</a>
							<?php endif; ?>
						</div>

						<div class="channel-stack-item">
							<h3 class="channel-stack-label">Location</h3>
							<p class="channel-stack-text">Serving clients across the energy, hospitality, government, telecoms, financial, education and manufacturing sectors.</p>
						</div>
					</div>

					<!-- URGENT SYSTEM ISSUE BOX -->
					<div class="urgent-editorial-box mt-8">
						<h3 class="urgent-editorial-title">Urgent system issue?</h3>
						<p class="urgent-editorial-desc">
							For existing installations, contact us about our maintenance and support cover.
						</p>
						<a href="<?php echo esc_url( $services_url ); ?>" class="urgent-editorial-link">
							<span>See our service lines</span>
							<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
						</a>
					</div>
				</div>

				<!-- RIGHT COLUMN: CONTACT FORM & INTERACTIVE STATE -->
				<div class="contact-editorial-card">
					<!-- Interactive Success State -->
					<div id="contact-success-state" class="contact-success-box" style="display: none;">
						<span class="success-icon-wrap">
							<?php echo kataskevi_render_icon( 'circle-check', 'icon icon-lg text-primary' ); ?>
						</span>
						<h2 class="success-title">Request received</h2>
						<p class="success-desc">
							Thank you for reaching out. Our team will review your enquiry and get back to you shortly.
						</p>
						<button type="button" id="contact-reset-btn" class="btn-pill btn-pill-outline mt-6">
							Send another message
						</button>
					</div>

					<!-- Form -->
					<form id="kataskevi-inquiry-form" class="editorial-interactive-form" method="post">
						<div id="form-status-message" class="form-status-alert" style="display: none;" role="alert"></div>

						<div class="form-row-2col">
							<div class="form-input-group">
								<label for="fullname">Full name <span class="required">*</span></label>
								<input type="text" id="fullname" name="fullname" class="input-editorial" placeholder="Jane Doe" required>
							</div>
							<div class="form-input-group">
								<label for="company">Company</label>
								<input type="text" id="company" name="company" class="input-editorial" placeholder="Company Ltd">
							</div>
						</div>

						<div class="form-row-2col">
							<div class="form-input-group">
								<label for="email">Email <span class="required">*</span></label>
								<input type="email" id="email" name="email" class="input-editorial" placeholder="you@company.com" required>
							</div>
							<div class="form-input-group">
								<label for="phone">Phone <span class="required">*</span></label>
								<input type="tel" id="phone" name="phone" class="input-editorial" placeholder="+234 800 000 0000" required>
							</div>
						</div>

						<div class="form-input-group">
							<label for="service">Service of interest <span class="required">*</span></label>
							<select id="service" name="service" class="input-editorial" required>
								<option value="" disabled <?php echo empty( $selected_service ) ? 'selected' : ''; ?>>Select a service</option>
								<?php foreach ( $services as $s ) : ?>
									<option value="<?php echo esc_attr( $s['title'] ); ?>" <?php selected( $selected_service, $s['title'] ); ?>>
										<?php echo esc_html( $s['title'] ); ?>
									</option>
								<?php endforeach; ?>
								<option value="Other / not sure yet">Other / not sure yet</option>
							</select>
						</div>

						<div class="form-input-group">
							<label for="message">Message <span class="required">*</span></label>
							<textarea id="message" name="message" class="input-editorial" rows="5" placeholder="Tell us about your site and what you need — e.g. a new data room, a CCTV upgrade, or a suppression system survey." required></textarea>
						</div>

						<div>
							<button type="submit" id="submit-inquiry-btn" class="btn-pill btn-pill-primary w-full sm:w-auto">
								<span>Send Request</span>
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
