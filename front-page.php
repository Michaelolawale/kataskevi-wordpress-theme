<?php
/**
 * The template for displaying the front page / home page
 * Redesigned UI matching ember-shield-systems.lovable.app
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
?>

<main id="primary" class="site-main">

	<!-- HERO SECTION -->
	<section class="section-hero">
		<div class="container-editorial">
			<p class="eyebrow">Engineering · Safety · IT · Telecoms</p>
			<h1 class="hero-title">
				Complete protection, <em class="text-primary italic">engineered end&nbsp;to&nbsp;end.</em>
			</h1>
			<p class="hero-description">
				Kataskevi provides full-service support in the design, supply, installation and maintenance of fire safety, electronic security and IT/telecoms infrastructure — from site survey to commissioning and beyond.
			</p>
			<div class="hero-actions">
				<a href="<?php echo esc_url( $services_url ); ?>" class="btn-pill btn-pill-primary">
					<span>Explore Services</span>
					<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
				</a>
				<a href="<?php echo esc_url( $contact_url ); ?>" class="btn-pill btn-pill-outline">
					<span>Request a Quote</span>
				</a>
			</div>
		</div>
	</section>

	<!-- HERO FEATURE IMAGE -->
	<section class="section-hero-media">
		<img 
			src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/hero.jpg' ); ?>" 
			alt="Bright modern facility with fire suppression pipework and steel infrastructure" 
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
				<div class="stat-card">
					<div class="stat-value">7</div>
					<div class="stat-label">Specialised service lines</div>
				</div>
				<div class="stat-card">
					<div class="stat-value">7</div>
					<div class="stat-label">Industry sectors served</div>
				</div>
				<div class="stat-card">
					<div class="stat-value">End-to-end</div>
					<div class="stat-label">Supply to maintenance</div>
				</div>
				<div class="stat-card">
					<div class="stat-value">24/7</div>
					<div class="stat-label">Critical-systems support</div>
				</div>
			</div>
		</div>
	</section>

	<!-- SERVICES OVERVIEW -->
	<section id="services" class="section-services">
		<div class="container-editorial">
			<div class="section-header-split">
				<div>
					<p class="eyebrow">Services</p>
					<h2 class="section-title">What we deliver</h2>
				</div>
				<p class="section-lead-muted">
					Seven specialised service lines, delivered as complete systems — designed, supplied, installed and maintained by one accountable partner.
				</p>
			</div>

			<div class="services-row-list">
				<?php 
				$service_index = 1;
				foreach ( $services as $service_key => $service ) : 
					$formatted_num = str_pad( (string) $service_index, 2, '0', STR_PAD_LEFT );
					$target_link = esc_url( $services_url . '#' . $service['id'] );
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
					<p class="eyebrow">Proven in the field</p>
					<h2 class="section-title">Featured works</h2>
				</div>
				<div class="section-header-aside">
					<p class="section-lead-muted">
						A cross-section of recent installations where life safety, mission-critical uptime, and rigorous engineering were non-negotiable.
					</p>
					<a href="<?php echo esc_url( $works_url ); ?>" class="btn-pill btn-pill-outline btn-sm desktop-only-inline">
						<span>Explore All Projects</span>
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
									<span>Read More</span>
									<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
								</a>
							</div>
						</div>
					</article>
				<?php endforeach; ?>
			</div>

			<div class="mobile-only-center mt-6">
				<a href="<?php echo esc_url( $works_url ); ?>" class="btn-pill btn-pill-outline">
					<span>View All Projects</span>
					<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
				</a>
			</div>
		</div>
	</section>

	<!-- WHOLE-OF-LIFE SUPPORT / BEYOND INSTALLATION -->
	<section class="section-lifecycle-banner">
		<div class="lifecycle-banner-media">
			<img 
				src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/band.jpg' ); ?>" 
				alt="Kataskevi engineers inspecting a fire alarm panel in a bright facility" 
				width="1920" 
				height="820" 
				loading="lazy" 
				class="lifecycle-media-img"
			>
			<div class="lifecycle-gradient-overlay" aria-hidden="true"></div>
			<div class="lifecycle-banner-content">
				<div class="container-editorial">
					<p class="eyebrow">Whole-of-life support</p>
					<h2 class="section-title">Beyond installation</h2>
					<p class="lifecycle-lead">
						In an ever-changing industry, we support our clientele towards maximising profit with a variety of services across the whole project lifecycle.
					</p>
				</div>
			</div>
		</div>

		<div class="lifecycle-grid-wrapper">
			<div class="container-editorial">
				<div class="lifecycle-cards-grid">
					<div class="lifecycle-card">
						<span class="lifecycle-card-step">01</span>
						<h3 class="lifecycle-card-title">Site Surveys</h3>
						<p class="lifecycle-card-desc">Assessment of your facility to establish exactly what your site requires.</p>
					</div>
					<div class="lifecycle-card">
						<span class="lifecycle-card-step">02</span>
						<h3 class="lifecycle-card-title">System Design &amp; Specification</h3>
						<p class="lifecycle-card-desc">Engineered designs and precise specifications for every system we deliver.</p>
					</div>
					<div class="lifecycle-card">
						<span class="lifecycle-card-step">03</span>
						<h3 class="lifecycle-card-title">Project Commissioning</h3>
						<p class="lifecycle-card-desc">Structured commissioning and handover so systems perform from day one.</p>
					</div>
					<div class="lifecycle-card">
						<span class="lifecycle-card-step">04</span>
						<h3 class="lifecycle-card-title">Sales &amp; Installation</h3>
						<p class="lifecycle-card-desc">Supply and professional installation backed by ongoing maintenance.</p>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- SECTORS WE SERVE -->
	<section id="sectors" class="section-sectors">
		<div class="container-editorial">
			<p class="eyebrow">Sectors</p>
			<h2 class="section-title">Sectors we serve</h2>
			<p class="section-lead-muted">
				Every industry has its own risks, standards and uptime demands. We tailor each system to the environment it protects.
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
			<p class="dark-cta-eyebrow">Request a survey</p>
			<h2 class="dark-cta-title">Ready to protect what matters?</h2>
			<p class="dark-cta-lead">
				Request a site survey or a quote — our team will design a system around your facility, your risks and your budget.
			</p>
			<div class="dark-cta-actions">
				<a href="<?php echo esc_url( $contact_url ); ?>" class="btn-pill btn-pill-light">
					<span>Get a Quote</span>
					<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
				</a>
				<a href="<?php echo esc_url( $services_url ); ?>" class="btn-pill btn-pill-outline-light">
					<span>View All Services</span>
				</a>
			</div>
		</div>
	</section>

</main>

<?php
get_footer();
