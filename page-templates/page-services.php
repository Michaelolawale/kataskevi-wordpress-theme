<?php
/**
 * Template Name: Services Overview
 * Redesigned UI matching ember-shield-systems.lovable.app
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
?>

<main id="primary" class="site-main">

	<!-- HERO -->
	<section class="section-page-hero">
		<div class="container-editorial">
			<p class="eyebrow">Services</p>
			<h1 class="page-hero-title">
				Seven service lines, <em class="text-primary italic">one accountable partner.</em>
			</h1>
			<p class="page-hero-desc">
				Complete service in the design, supply, installation and maintenance of every system below — one partner, one accountable team, from survey to handover.
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
							<?php foreach ( $service['points'] as $pt ) : ?>
								<li class="service-point-item">
									<?php echo kataskevi_render_icon( 'circle-check', 'icon icon-xs text-primary' ); ?>
									<span><?php echo esc_html( $pt ); ?></span>
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
			<p class="eyebrow">How we work</p>
			<h2 class="section-title">From survey to support</h2>
			<p class="section-lead-muted" style="max-width: 36rem;">
				A structured path from first visit to long-term support, so every system performs from day one and keeps performing.
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

			<div class="mt-12">
				<a href="<?php echo esc_url( $contact_url ); ?>" class="btn-pill btn-pill-primary">
					<span>Start with a site survey</span>
					<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
				</a>
			</div>
		</div>
	</section>

</main>

<?php
get_footer();
