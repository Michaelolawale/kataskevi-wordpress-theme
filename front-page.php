<?php
/**
 * The template for displaying the front page / home page
 * Redesigned UI matching ember-shield-systems.lovable.app
 *
 * Adheres strictly to the WordPress PHP Coding Standards (WPCS).
 * All text and images are fully customizable via Appearance -> Customize.
 *
 * @package Kataskevi
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$services_url = kataskevi_get_page_url( 'services', 'Services' );
$works_url    = kataskevi_get_page_url( 'works', 'Our Works' );
$about_url    = kataskevi_get_page_url( 'about', 'About' );
$contact_url  = kataskevi_get_page_url( 'contact', 'Contact' );

$services          = kataskevi_get_services();
$sectors           = kataskevi_get_sectors();
$featured_projects = kataskevi_get_projects( 3, true );

// Customizer Theme Options.
$hero_eyebrow = kataskevi_get_mod( 'kataskevi_hero_eyebrow', 'Engineering · Safety · IT · Telecoms' );
$hero_title   = kataskevi_get_mod( 'kataskevi_hero_title', 'Complete protection, <em class="text-primary italic">engineered end&nbsp;to&nbsp;end.</em>' );
$hero_desc    = kataskevi_get_mod( 'kataskevi_hero_description', 'Kataskevi provides full-service support in the design, supply, installation and maintenance of fire safety, electronic security and IT/telecoms infrastructure — from site survey to commissioning and beyond.' );
$hero_btn1    = kataskevi_get_mod( 'kataskevi_hero_btn1_text', 'Explore Services' );
$hero_btn2    = kataskevi_get_mod( 'kataskevi_hero_btn2_text', 'Request a Quote' );
$hero_image   = kataskevi_get_mod( 'kataskevi_hero_image', get_template_directory_uri() . '/assets/images/hero.jpg' );

$services_eyebrow = kataskevi_get_mod( 'kataskevi_home_services_eyebrow', 'Services' );
$services_title   = kataskevi_get_mod( 'kataskevi_home_services_title', 'What we deliver' );
$services_desc    = kataskevi_get_mod( 'kataskevi_home_services_desc', 'Seven specialised service lines, delivered as complete systems — designed, supplied, installed and maintained by one accountable partner.' );

$works_eyebrow = kataskevi_get_mod( 'kataskevi_home_works_eyebrow', 'Proven in the field' );
$works_title   = kataskevi_get_mod( 'kataskevi_home_works_title', 'Featured works' );
$works_desc    = kataskevi_get_mod( 'kataskevi_home_works_desc', 'A cross-section of recent installations where life safety, mission-critical uptime, and rigorous engineering were non-negotiable.' );

$lifecycle_image   = kataskevi_get_mod( 'kataskevi_lifecycle_image', get_template_directory_uri() . '/assets/images/band.jpg' );
$lifecycle_eyebrow = kataskevi_get_mod( 'kataskevi_lifecycle_eyebrow', 'Whole-of-life support' );
$lifecycle_title   = kataskevi_get_mod( 'kataskevi_lifecycle_title', 'Beyond installation' );
$lifecycle_desc    = kataskevi_get_mod( 'kataskevi_lifecycle_desc', 'In an ever-changing industry, we support our clientele towards maximising profit with a variety of services across the whole project lifecycle.' );

$sectors_eyebrow = kataskevi_get_mod( 'kataskevi_home_sectors_eyebrow', 'Sectors' );
$sectors_title   = kataskevi_get_mod( 'kataskevi_home_sectors_title', 'Sectors we serve' );
$sectors_desc    = kataskevi_get_mod( 'kataskevi_home_sectors_desc', 'Every industry has its own risks, standards and uptime demands. We tailor each system to the environment it protects.' );

$dark_cta_eyebrow = kataskevi_get_mod( 'kataskevi_dark_cta_eyebrow', 'Request a survey' );
$dark_cta_title   = kataskevi_get_mod( 'kataskevi_dark_cta_title', 'Ready to protect what matters?' );
$dark_cta_desc    = kataskevi_get_mod( 'kataskevi_dark_cta_desc', 'Request a site survey or a quote — our team will design a system around your facility, your risks and your budget.' );
$dark_cta_btn1    = kataskevi_get_mod( 'kataskevi_dark_cta_btn1_text', 'Get a Quote' );
$dark_cta_btn2    = kataskevi_get_mod( 'kataskevi_dark_cta_btn2_text', 'View All Services' );
?>

<main id="primary" class="site-main">

	<!-- HERO SECTION -->
	<section class="section-hero">
		<div class="container-editorial">
			<p class="eyebrow"><?php echo esc_html( $hero_eyebrow ); ?></p>
			<h1 class="hero-title">
				<?php echo wp_kses_post( $hero_title ); ?>
			</h1>
			<p class="hero-description">
				<?php echo esc_html( $hero_desc ); ?>
			</p>
			<div class="hero-actions">
				<a href="<?php echo esc_url( $services_url ); ?>" class="btn-pill btn-pill-primary">
					<span><?php echo esc_html( $hero_btn1 ); ?></span>
					<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
				</a>
				<a href="<?php echo esc_url( $contact_url ); ?>" class="btn-pill btn-pill-outline">
					<span><?php echo esc_html( $hero_btn2 ); ?></span>
				</a>
			</div>
		</div>
	</section>

	<!-- HERO FEATURE IMAGE -->
	<section class="section-hero-media">
		<img 
			src="<?php echo esc_url( $hero_image ); ?>" 
			alt="<?php esc_attr_e( 'Bright modern facility with fire suppression pipework and steel infrastructure', 'kataskevi' ); ?>" 
			width="1600" 
			height="1000" 
			class="hero-media-img"
			fetchpriority="high"
		>
	</section>

	<!-- STATS GRID -->
	<section class="section-stats-bar">
		<div class="container-editorial">
			<div class="stats-grid">
				<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
					<?php
					$stat_val = kataskevi_get_mod( 'kataskevi_stat' . $i . '_val', $i <= 2 ? '7' : ( 3 === $i ? 'End-to-end' : '24/7' ) );
					$stat_lbl = kataskevi_get_mod( 'kataskevi_stat' . $i . '_lbl', 1 === $i ? 'Specialised service lines' : ( 2 === $i ? 'Industry sectors served' : ( 3 === $i ? 'Supply to maintenance' : 'Critical-systems support' ) ) );
					?>
					<div class="stat-card">
						<div class="stat-value"><?php echo esc_html( $stat_val ); ?></div>
						<div class="stat-label"><?php echo esc_html( $stat_lbl ); ?></div>
					</div>
				<?php endfor; ?>
			</div>
		</div>
	</section>

	<!-- SERVICES OVERVIEW -->
	<section id="services" class="section-services">
		<div class="container-editorial">
			<div class="section-header-split">
				<div>
					<p class="eyebrow"><?php echo esc_html( $services_eyebrow ); ?></p>
					<h2 class="section-title"><?php echo esc_html( $services_title ); ?></h2>
				</div>
				<p class="section-lead-muted">
					<?php echo esc_html( $services_desc ); ?>
				</p>
			</div>

			<div class="services-row-list">
				<?php 
				$service_index = 1;
				foreach ( $services as $service_key => $service ) : 
					$formatted_num = str_pad( (string) $service_index, 2, '0', STR_PAD_LEFT );
					$target_link   = esc_url( $services_url . '#' . $service['id'] );
				?>
					<a href="<?php echo $target_link; ?>" class="service-row-item group">
						<span class="service-row-num"><?php echo esc_html( $formatted_num ); ?></span>
						<div class="service-row-content">
							<h3 class="service-row-title"><?php echo esc_html( $service['title'] ); ?></h3>
							<p class="service-row-desc"><?php echo esc_html( $service['short_desc'] ); ?></p>
						</div>
						<span class="service-row-arrow" aria-hidden="true">
							<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-sm' ); ?>
						</span>
					</a>
				<?php 
					$service_index++;
				endforeach; 
				?>
			</div>
		</div>
	</section>

	<!-- FEATURED WORKS -->
	<section id="works" class="section-featured-works">
		<div class="container-editorial">
			<div class="section-header-split">
				<div>
					<p class="eyebrow"><?php echo esc_html( $works_eyebrow ); ?></p>
					<h2 class="section-title"><?php echo esc_html( $works_title ); ?></h2>
				</div>
				<div class="section-header-aside">
					<p class="section-lead-muted">
						<?php echo esc_html( $works_desc ); ?>
					</p>
					<a href="<?php echo esc_url( $works_url ); ?>" class="btn-pill btn-pill-outline btn-sm desktop-only-inline">
						<span><?php esc_html_e( 'Explore All Projects', 'kataskevi' ); ?></span>
						<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
					</a>
				</div>
			</div>

			<div class="featured-works-grid">
				<?php foreach ( $featured_projects as $proj ) : ?>
					<article class="featured-work-card group">
						<a href="<?php echo esc_url( $proj['permalink'] ); ?>" class="featured-work-media-link" aria-label="<?php echo esc_attr( $proj['title'] ); ?>">
							<div class="featured-work-media">
								<img 
									src="<?php echo esc_url( $proj['image'] ); ?>" 
									alt="<?php echo esc_attr( $proj['title'] ); ?>" 
									width="800" 
									height="540" 
									loading="lazy" 
									class="featured-work-img"
								>
								<div class="featured-work-badge-strip">
									<span class="sector-pill"><?php echo esc_html( $proj['sector'] ); ?></span>
								</div>
							</div>
						</a>
						<div class="featured-work-body">
							<h3 class="featured-work-title">
								<a href="<?php echo esc_url( $proj['permalink'] ); ?>">
									<?php echo esc_html( $proj['title'] ); ?>
								</a>
							</h3>
							<div class="featured-work-action">
								<a href="<?php echo esc_url( $proj['permalink'] ); ?>" class="btn-pill btn-pill-outline btn-sm">
									<span><?php esc_html_e( 'Read More', 'kataskevi' ); ?></span>
									<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
								</a>
							</div>
						</div>
					</article>
				<?php endforeach; ?>
			</div>

			<div class="mobile-only-center mt-6">
				<a href="<?php echo esc_url( $works_url ); ?>" class="btn-pill btn-pill-outline">
					<span><?php esc_html_e( 'View All Projects', 'kataskevi' ); ?></span>
					<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
				</a>
			</div>
		</div>
	</section>

	<!-- WHOLE-OF-LIFE SUPPORT / BEYOND INSTALLATION -->
	<section class="section-lifecycle-banner">
		<div class="lifecycle-banner-media">
			<img 
				src="<?php echo esc_url( $lifecycle_image ); ?>" 
				alt="<?php esc_attr_e( 'Kataskevi engineers inspecting a fire alarm panel in a bright facility', 'kataskevi' ); ?>" 
				width="1920" 
				height="820" 
				loading="lazy" 
				class="lifecycle-media-img"
			>
			<div class="lifecycle-gradient-overlay" aria-hidden="true"></div>
			<div class="lifecycle-banner-content">
				<div class="container-editorial">
					<p class="eyebrow"><?php echo esc_html( $lifecycle_eyebrow ); ?></p>
					<h2 class="section-title"><?php echo esc_html( $lifecycle_title ); ?></h2>
					<p class="lifecycle-lead">
						<?php echo esc_html( $lifecycle_desc ); ?>
					</p>
				</div>
			</div>
		</div>

		<div class="lifecycle-grid-wrapper">
			<div class="container-editorial">
				<div class="lifecycle-cards-grid">
					<?php for ( $s = 1; $s <= 4; $s++ ) : ?>
						<?php
						$step_num   = str_pad( (string) $s, 2, '0', STR_PAD_LEFT );
						$step_title = kataskevi_get_mod(
							'kataskevi_lifecycle_step' . $s . '_title',
							1 === $s ? 'Site Surveys' : ( 2 === $s ? 'System Design & Specification' : ( 3 === $s ? 'Project Commissioning' : 'Sales & Installation' ) )
						);
						$step_desc  = kataskevi_get_mod(
							'kataskevi_lifecycle_step' . $s . '_desc',
							1 === $s ? 'Assessment of your facility to establish exactly what your site requires.' : ( 2 === $s ? 'Engineered designs and precise specifications for every system we deliver.' : ( 3 === $s ? 'Structured commissioning and handover so systems perform from day one.' : 'Supply and professional installation backed by ongoing maintenance.' ) )
						);
						?>
						<div class="lifecycle-card">
							<span class="lifecycle-card-step"><?php echo esc_html( $step_num ); ?></span>
							<h3 class="lifecycle-card-title"><?php echo esc_html( $step_title ); ?></h3>
							<p class="lifecycle-card-desc"><?php echo esc_html( $step_desc ); ?></p>
						</div>
					<?php endfor; ?>
				</div>
			</div>
		</div>
	</section>

	<!-- SECTORS WE SERVE -->
	<section id="sectors" class="section-sectors">
		<div class="container-editorial">
			<p class="eyebrow"><?php echo esc_html( $sectors_eyebrow ); ?></p>
			<h2 class="section-title"><?php echo esc_html( $sectors_title ); ?></h2>
			<p class="section-lead-muted">
				<?php echo esc_html( $sectors_desc ); ?>
			</p>

			<div class="sectors-grid">
				<?php foreach ( $sectors as $sector ) : ?>
					<div class="sector-card">
						<div class="sector-card-icon">
							<?php echo kataskevi_render_icon( $sector['icon'], 'icon icon-sm text-primary' ); ?>
						</div>
						<h3 class="sector-card-title"><?php echo esc_html( $sector['name'] ); ?></h3>
						<p class="sector-card-desc"><?php echo esc_html( $sector['desc'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- DARK HIGH-CONTRAST CTA -->
	<section class="section-dark-cta">
		<div class="container-editorial text-center">
			<p class="dark-cta-eyebrow"><?php echo esc_html( $dark_cta_eyebrow ); ?></p>
			<h2 class="dark-cta-title"><?php echo esc_html( $dark_cta_title ); ?></h2>
			<p class="dark-cta-lead">
				<?php echo esc_html( $dark_cta_desc ); ?>
			</p>
			<div class="dark-cta-actions">
				<a href="<?php echo esc_url( $contact_url ); ?>" class="btn-pill btn-pill-light">
					<span><?php echo esc_html( $dark_cta_btn1 ); ?></span>
					<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
				</a>
				<a href="<?php echo esc_url( $services_url ); ?>" class="btn-pill btn-pill-outline-light">
					<span><?php echo esc_html( $dark_cta_btn2 ); ?></span>
				</a>
			</div>
		</div>
	</section>

</main>

<?php
get_footer();
