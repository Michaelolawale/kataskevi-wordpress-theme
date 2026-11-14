<?php
/**
 * Template Name: Services Overview
 * Description: The full overview of Kataskevi services and engineering lifecycle.
 *
 * Adheres strictly to the WordPress PHP Coding Standards (WPCS).
 * All text is fully customizable via Appearance -> Customize.
 *
 * @package Kataskevi
 * @version 2.1.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$contact_url   = kataskevi_get_page_url( 'contact', 'Contact' );
$main_services = kataskevi_get_main_services();

// Customizer Theme Options.
$page_eyebrow = kataskevi_get_mod( 'kataskevi_page_services_eyebrow', 'Services' );
$page_title   = kataskevi_get_mod( 'kataskevi_page_services_title', 'Engineering services, <em class="text-primary italic">one accountable partner.</em>' );
$page_desc    = kataskevi_get_mod( 'kataskevi_page_services_desc', 'Complete lifecycle support across our four core engineering service pillars — from preliminary site survey to structured commissioning and ongoing preventative maintenance.' );

$cta_eyebrow  = kataskevi_get_mod( 'kataskevi_services_how_eyebrow', 'One Accountable Partner' );
$cta_title    = kataskevi_get_mod( 'kataskevi_services_how_title', 'Engineered for complete protection from survey to handover.' );
$cta_desc     = kataskevi_get_mod( 'kataskevi_services_how_desc', 'Speak with our engineering team to audit your facility risks or design an integrated protection system tailored to your building.' );
$cta_btn_text = kataskevi_get_mod( 'kataskevi_services_how_btn', 'Request a Site Survey' );
?>

<main id="primary" class="site-main">

	<!-- HERO -->
	<section class="section-page-hero">
		<div class="container-editorial">
			<?php if ( ! empty( $page_eyebrow ) ) : ?>
				<p class="eyebrow"><?php echo esc_html( $page_eyebrow ); ?></p>
			<?php endif; ?>
			<?php if ( ! empty( $page_title ) ) : ?>
				<h1 class="page-hero-title">
					<?php echo wp_kses_post( $page_title ); ?>
				</h1>
			<?php endif; ?>
			<?php if ( ! empty( $page_desc ) ) : ?>
				<p class="page-hero-desc">
					<?php echo esc_html( $page_desc ); ?>
				</p>
			<?php endif; ?>
		</div>
	</section>

	<!-- FOUR MAIN CORE SERVICES -->
	<section id="main-services" class="section-services-list border-b border-border">
		<div class="container-editorial pb-20">
			<div class="border-t border-border">
				<?php 
				$m_idx = 1;
				foreach ( $main_services as $m_key => $m_service ) : 
					$formatted_num = str_pad( (string) $m_idx, 2, '0', STR_PAD_LEFT );
					$inquire_url   = esc_url( add_query_arg( 'service', urlencode( $m_service['title'] ), $contact_url ) );
					$detail_url    = esc_url( kataskevi_get_service_url( $m_service['slug'] ) );
				?>
					<article id="<?php echo esc_attr( $m_service['id'] ); ?>" class="service-article-row">
						<!-- Col 1: Attached Service Image (Clean frame without overlay tags) -->
						<?php if ( ! empty( $m_service['image'] ) ) : ?>
							<div class="service-article-media">
								<a href="<?php echo $detail_url; ?>" class="service-article-media-link" aria-label="<?php echo esc_attr( $m_service['title'] ); ?>">
									<img 
										src="<?php echo esc_url( $m_service['image'] ); ?>" 
										alt="<?php echo esc_attr( $m_service['title'] ); ?>" 
										width="640" 
										height="400" 
										loading="lazy" 
										class="service-article-img"
									>
								</a>
							</div>
						<?php endif; ?>

						<!-- Col 2: Info & Details -->
						<div class="service-article-info">
							<div class="service-article-top-meta">
								<span class="service-article-num"><?php echo esc_html( $formatted_num ); ?></span>
							</div>

							<h2 class="service-article-title">
								<a href="<?php echo $detail_url; ?>">
									<?php echo esc_html( $m_service['title'] ); ?>
								</a>
							</h2>
							<p class="service-article-desc"><?php echo esc_html( $m_service['description'] ); ?></p>

							<div class="service-article-actions">
								<a href="<?php echo $detail_url; ?>" class="btn-pill btn-pill-primary">
									<span><?php esc_html_e( 'Service Details', 'kataskevi' ); ?></span>
									<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
								</a>
							</div>
						</div>
					</article>
				<?php 
					$m_idx++;
				endforeach; 
				?>
			</div>
		</div>
	</section>

	<!-- BOTTOM CTA SECTION -->
	<?php if ( ! empty( $cta_title ) || ! empty( $cta_desc ) || ! empty( $cta_btn_text ) ) : ?>
		<section class="section-services-cta py-20 bg-muted border-t border-border">
			<div class="container-editorial text-center" style="max-width: 56rem; margin: 0 auto;">
				<?php if ( ! empty( $cta_eyebrow ) ) : ?>
					<p class="eyebrow"><?php echo esc_html( $cta_eyebrow ); ?></p>
				<?php endif; ?>
				<?php if ( ! empty( $cta_title ) ) : ?>
					<h2 class="section-title mb-4" style="text-wrap: balance; hyphens: none; word-break: normal;">
						<?php echo esc_html( $cta_title ); ?>
					</h2>
				<?php endif; ?>
				<?php if ( ! empty( $cta_desc ) ) : ?>
					<p class="section-lead-muted mb-8" style="max-width: 44rem; margin-left: auto; margin-right: auto;">
						<?php echo esc_html( $cta_desc ); ?>
					</p>
				<?php endif; ?>
				<?php if ( ! empty( $cta_btn_text ) ) : ?>
					<div class="services-cta-actions">
						<a href="<?php echo esc_url( $contact_url ); ?>" class="btn-pill btn-pill-primary">
							<span><?php echo esc_html( $cta_btn_text ); ?></span>
							<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
						</a>
					</div>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

</main>

<?php
get_footer();
