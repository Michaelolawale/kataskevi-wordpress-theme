<?php
/**
 * Template Name: Services Overview
 * Description: The full overview of Kataskevi services and engineering lifecycle.
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

$contact_url = kataskevi_get_page_url( 'contact', 'Contact' );

$services     = kataskevi_get_services();
$capabilities = kataskevi_get_capabilities();

// Customizer Theme Options.
$page_eyebrow = kataskevi_get_mod( 'kataskevi_page_services_eyebrow', 'Services' );
$page_title   = kataskevi_get_mod( 'kataskevi_page_services_title', 'Seven service lines, <em class="text-primary italic">one accountable partner.</em>' );
$page_desc    = kataskevi_get_mod( 'kataskevi_page_services_desc', 'Complete service in the design, supply, installation and maintenance of every system below — one partner, one accountable team, from survey to handover.' );

$how_eyebrow = kataskevi_get_mod( 'kataskevi_services_how_eyebrow', 'How we work' );
$how_title   = kataskevi_get_mod( 'kataskevi_services_how_title', 'From survey to support' );
$how_desc    = kataskevi_get_mod( 'kataskevi_services_how_desc', 'A structured path from first visit to long-term support, so every system performs from day one and keeps performing.' );
$how_btn     = kataskevi_get_mod( 'kataskevi_services_how_btn', 'Start with a site survey' );
?>

<main id="primary" class="site-main">

	<!-- HERO -->
	<section class="section-page-hero">
		<div class="container-editorial">
			<p class="eyebrow"><?php echo esc_html( $page_eyebrow ); ?></p>
			<h1 class="page-hero-title">
				<?php echo wp_kses_post( $page_title ); ?>
			</h1>
			<p class="page-hero-desc">
				<?php echo esc_html( $page_desc ); ?>
			</p>
		</div>
	</section>

	<!-- 7 SERVICE LINES (3-COLUMN ARTICLE GRID) -->
	<section class="section-services-list">
		<div class="container-editorial pb-20">
			<div class="border-t border-border">
				<?php 
				$idx = 1;
				foreach ( $services as $key => $service ) : 
					$formatted_num = str_pad( (string) $idx, 2, '0', STR_PAD_LEFT );
				?>
					<article id="<?php echo esc_attr( $service['id'] ); ?>" class="service-article-row">
						<!-- Col 1: Number -->
						<span class="service-article-num"><?php echo esc_html( $formatted_num ); ?></span>

						<!-- Col 2: Info & Details -->
						<div class="service-article-info">
							<div class="service-article-icon">
								<?php echo kataskevi_render_icon( $service['icon'], 'icon icon-md text-primary' ); ?>
							</div>
							<h2 class="service-article-title"><?php echo esc_html( $service['title'] ); ?></h2>
							<p class="service-article-desc"><?php echo esc_html( $service['description'] ); ?></p>
						</div>

						<!-- Col 3: Points List -->
						<ul class="service-points-list">
							<?php foreach ( $service['points'] as $point ) : ?>
								<li class="service-point-item">
									<span class="check-icon-wrap" aria-hidden="true">
										<?php echo kataskevi_render_icon( 'check', 'icon icon-xs text-primary' ); ?>
									</span>
									<span><?php echo esc_html( $point ); ?></span>
								</li>
							<?php endforeach; ?>
						</ul>
					</article>
				<?php 
					$idx++;
				endforeach; 
				?>
			</div>
		</div>
	</section>

	<!-- HOW WE WORK / FROM SURVEY TO SUPPORT -->
	<section class="section-how-we-work border-y border-border">
		<div class="container-editorial py-20">
			<p class="eyebrow"><?php echo esc_html( $how_eyebrow ); ?></p>
			<h2 class="section-title"><?php echo esc_html( $how_title ); ?></h2>
			<p class="section-lead-muted" style="max-width: 36rem;">
				<?php echo esc_html( $how_desc ); ?>
			</p>

			<div class="lifecycle-cards-grid mt-12">
				<?php 
				$step_idx = 1;
				foreach ( $capabilities as $cap ) : 
					$cap_num = str_pad( (string) $step_idx, 2, '0', STR_PAD_LEFT );
				?>
					<div class="lifecycle-card">
						<span class="lifecycle-card-step"><?php echo esc_html( $cap_num ); ?></span>
						<h3 class="lifecycle-card-title"><?php echo esc_html( $cap['title'] ); ?></h3>
						<p class="lifecycle-card-desc"><?php echo esc_html( $cap['desc'] ); ?></p>
					</div>
				<?php 
					$step_idx++;
				endforeach; 
				?>
			</div>

			<div class="how-we-work-action mt-12">
				<a href="<?php echo esc_url( $contact_url ); ?>" class="btn-pill btn-pill-primary">
					<span><?php echo esc_html( $how_btn ); ?></span>
					<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
				</a>
			</div>
		</div>
	</section>

</main>

<?php
get_footer();
