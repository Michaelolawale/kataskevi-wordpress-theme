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
$main_services     = array_slice( $services, 0, 3 );
$sectors           = kataskevi_get_sectors();
$all_projects      = kataskevi_get_projects( -1, false );
$featured_projects = kataskevi_get_projects( -1, true );
if ( empty( $featured_projects ) || count( $featured_projects ) < 4 ) {
	$featured_projects = $all_projects;
} else {
	$merged = $featured_projects;
	foreach ( $all_projects as $slug => $proj ) {
		if ( ! isset( $merged[ $slug ] ) ) {
			$merged[ $slug ] = $proj;
		}
	}
	$featured_projects = $merged;
}

$brand_name = kataskevi_get_brand_name();

// Customizer Theme Options.
$hero_eyebrow = kataskevi_get_mod( 'kataskevi_hero_eyebrow', 'Engineering · Safety · IT · Telecoms' );
$hero_title   = kataskevi_get_mod( 'kataskevi_hero_title', 'Complete protection, <em class="text-primary italic">engineered end&nbsp;to&nbsp;end.</em>' );
$hero_desc    = kataskevi_get_mod(
	'kataskevi_hero_description',
	sprintf(
		/* translators: %s: Company / Brand Name */
		esc_html__( '%s provides full-service support in the design, supply, installation and maintenance of fire safety, electronic security and IT/telecoms infrastructure — from site survey to commissioning and beyond.', 'kataskevi' ),
		$brand_name
	)
);
$hero_btn1    = kataskevi_get_mod( 'kataskevi_hero_btn1_text', 'Explore Services' );
$hero_btn2    = kataskevi_get_mod( 'kataskevi_hero_btn2_text', 'Request a Quote' );
$hero_image   = kataskevi_get_mod( 'kataskevi_hero_image', get_template_directory_uri() . '/assets/images/hero.jpg' );

$works_eyebrow = kataskevi_get_mod( 'kataskevi_home_works_eyebrow', 'Proven in the field' );
$works_title   = kataskevi_get_mod( 'kataskevi_home_works_title', 'Featured Engineering Projects' );
$works_desc    = kataskevi_get_mod( 'kataskevi_home_works_desc', 'Explore our core installations across Fire Hydrants & Hose Reels, Intelligent Alarms, Biometric Access Control, and 4K CCTV Video Surveillance.' );

$lifecycle_image   = kataskevi_get_mod( 'kataskevi_lifecycle_image', get_template_directory_uri() . '/assets/images/band.jpg' );
$lifecycle_eyebrow = kataskevi_get_mod( 'kataskevi_lifecycle_eyebrow', 'Core Services' );
$lifecycle_title   = kataskevi_get_mod( 'kataskevi_lifecycle_title', 'Beyond installation' );
$lifecycle_desc    = kataskevi_get_mod( 'kataskevi_lifecycle_desc', 'From preliminary risk assessment to turnkey deployment and lifetime preventative maintenance, our team delivers complete protection across every project stage.' );

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
			<?php if ( ! empty( $hero_eyebrow ) ) : ?>
				<p class="eyebrow"><?php echo esc_html( $hero_eyebrow ); ?></p>
			<?php endif; ?>

			<?php if ( ! empty( $hero_title ) ) : ?>
				<h1 class="hero-title">
					<?php echo wp_kses_post( $hero_title ); ?>
				</h1>
			<?php endif; ?>

			<?php if ( ! empty( $hero_desc ) ) : ?>
				<p class="hero-description">
					<?php echo esc_html( $hero_desc ); ?>
				</p>
			<?php endif; ?>

			<?php if ( ! empty( $hero_btn1 ) || ! empty( $hero_btn2 ) ) : ?>
				<div class="hero-actions">
					<?php if ( ! empty( $hero_btn1 ) ) : ?>
						<a href="<?php echo esc_url( $services_url ); ?>" class="btn-pill btn-pill-primary">
							<span><?php echo esc_html( $hero_btn1 ); ?></span>
							<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
						</a>
					<?php endif; ?>
					<?php if ( ! empty( $hero_btn2 ) ) : ?>
						<a href="<?php echo esc_url( $contact_url ); ?>" class="btn-pill btn-pill-outline">
							<span><?php echo esc_html( $hero_btn2 ); ?></span>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<!-- HERO FEATURE IMAGE -->
	<?php if ( ! empty( $hero_image ) ) : ?>
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
	<?php endif; ?>

	<!-- THREE CORE SPECIALTIES STRIP (UNDER HERO IMAGE) -->
	<?php
	$feat1_title = kataskevi_get_mod( 'kataskevi_hero_feat1_title', 'Data Preparation' );
	$feat1_desc  = kataskevi_get_mod( 'kataskevi_hero_feat1_desc', 'Turnkey preparation of IT and telecoms facilities, clean data halls, and mission-critical server environments.' );

	$feat2_title = kataskevi_get_mod( 'kataskevi_hero_feat2_title', 'Raised Floor' );
	$feat2_desc  = kataskevi_get_mod( 'kataskevi_hero_feat2_desc', 'Heavy-duty anti-static raised access flooring engineered for airflow, cable management, and load-bearing stability.' );

	$feat3_title = kataskevi_get_mod( 'kataskevi_hero_feat3_title', 'Access Control' );
	$feat3_desc  = kataskevi_get_mod( 'kataskevi_hero_feat3_desc', 'Multi-factor biometric access, smart card terminals, optical turnstiles, and auditable physical security systems.' );

	$has_features = ( ! empty( $feat1_title ) || ! empty( $feat1_desc ) || ! empty( $feat2_title ) || ! empty( $feat2_desc ) || ! empty( $feat3_title ) || ! empty( $feat3_desc ) );
	?>
	<?php if ( $has_features ) : ?>
		<section class="section-hero-features">
			<div class="container-editorial">
				<div class="hero-features-grid">
					<?php if ( ! empty( $feat1_title ) || ! empty( $feat1_desc ) ) : ?>
						<div class="hero-feature-card">
							<?php if ( ! empty( $feat1_title ) ) : ?>
								<h3 class="hero-feature-title"><?php echo esc_html( $feat1_title ); ?></h3>
							<?php endif; ?>
							<?php if ( ! empty( $feat1_desc ) ) : ?>
								<p class="hero-feature-desc"><?php echo esc_html( $feat1_desc ); ?></p>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $feat2_title ) || ! empty( $feat2_desc ) ) : ?>
						<div class="hero-feature-card">
							<?php if ( ! empty( $feat2_title ) ) : ?>
								<h3 class="hero-feature-title"><?php echo esc_html( $feat2_title ); ?></h3>
							<?php endif; ?>
							<?php if ( ! empty( $feat2_desc ) ) : ?>
								<p class="hero-feature-desc"><?php echo esc_html( $feat2_desc ); ?></p>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $feat3_title ) || ! empty( $feat3_desc ) ) : ?>
						<div class="hero-feature-card">
							<?php if ( ! empty( $feat3_title ) ) : ?>
								<h3 class="hero-feature-title"><?php echo esc_html( $feat3_title ); ?></h3>
							<?php endif; ?>
							<?php if ( ! empty( $feat3_desc ) ) : ?>
								<p class="hero-feature-desc"><?php echo esc_html( $feat3_desc ); ?></p>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<!-- FEATURED WORKS (CAROUSEL SLIDER) -->
	<section id="works" class="section-featured-works">
		<div class="container-editorial">
			<div class="featured-works-header">
				<div class="featured-works-header-left">
					<?php if ( ! empty( $works_eyebrow ) ) : ?>
						<p class="eyebrow"><?php echo esc_html( $works_eyebrow ); ?></p>
					<?php endif; ?>
					<h2 class="section-title"><?php echo esc_html( $works_title ); ?></h2>
					<?php if ( ! empty( $works_desc ) ) : ?>
						<p class="section-lead-muted">
							<?php echo esc_html( $works_desc ); ?>
						</p>
					<?php endif; ?>
				</div>
				<div class="featured-works-header-right">
					<div class="works-carousel-nav" aria-label="<?php esc_attr_e( 'Project carousel controls', 'kataskevi' ); ?>">
						<button type="button" class="works-carousel-btn" id="works-carousel-prev" aria-label="<?php esc_attr_e( 'Previous projects', 'kataskevi' ); ?>">
							<?php echo kataskevi_render_icon( 'arrow-left', 'icon icon-xs' ); ?>
						</button>
						<button type="button" class="works-carousel-btn" id="works-carousel-next" aria-label="<?php esc_attr_e( 'Next projects', 'kataskevi' ); ?>">
							<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
						</button>
					</div>
					<a href="<?php echo esc_url( $works_url ); ?>" class="btn-pill btn-pill-outline btn-sm desktop-only-inline">
						<span><?php esc_html_e( 'Explore All Projects', 'kataskevi' ); ?></span>
						<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
					</a>
				</div>
			</div>

			<div class="works-carousel-wrapper">
				<div class="works-carousel-track" id="works-carousel-track" tabindex="0" role="region" aria-label="<?php esc_attr_e( 'Featured projects carousel', 'kataskevi' ); ?>">
					<?php foreach ( $featured_projects as $proj ) : ?>
						<?php 
						$gallery = ! empty( $proj['gallery'] ) && is_array( $proj['gallery'] ) ? $proj['gallery'] : array( $proj['image'] );
						$has_card_slider = count( $gallery ) > 1;
						?>
						<article class="featured-work-card works-carousel-slide group">
							<div class="featured-work-media card-image-slider" data-current-slide="0">
								<div class="card-slider-track">
									<?php foreach ( $gallery as $g_idx => $g_img ) : ?>
										<div class="card-slider-slide <?php echo 0 === $g_idx ? 'is-active' : ''; ?>">
											<a href="<?php echo esc_url( $proj['permalink'] ); ?>" class="card-slider-img-link" aria-label="<?php echo esc_attr( $proj['title'] ); ?>">
												<img 
													src="<?php echo esc_url( $g_img ); ?>" 
													alt="<?php echo esc_attr( $proj['title'] . ( $has_card_slider ? ' - ' . ( $g_idx + 1 ) : '' ) ); ?>" 
													width="800" 
													height="500" 
													loading="lazy" 
													class="featured-work-img"
												>
											</a>
										</div>
									<?php endforeach; ?>
								</div>

								<?php if ( $has_card_slider ) : ?>
									<div class="card-slider-dots">
										<?php foreach ( $gallery as $g_idx => $g_img ) : ?>
											<button type="button" class="card-slider-dot <?php echo 0 === $g_idx ? 'is-active' : ''; ?>" data-slide="<?php echo esc_attr( $g_idx ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Image %d', 'kataskevi' ), $g_idx + 1 ) ); ?>"></button>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>
							</div>

							<div class="featured-work-body">
								<h3 class="featured-work-title">
									<a href="<?php echo esc_url( $proj['permalink'] ); ?>">
										<?php echo esc_html( $proj['title'] ); ?>
									</a>
								</h3>

								<?php
								$card_desc = ! empty( $proj['short_desc'] ) ? $proj['short_desc'] : ( ! empty( $proj['scope'] ) ? $proj['scope'] : '' );
								if ( ! empty( $card_desc ) ) :
								?>
									<p class="featured-work-desc">
										<?php echo esc_html( wp_trim_words( $card_desc, 20 ) ); ?>
									</p>
								<?php endif; ?>

								<div class="featured-work-action mt-auto">
									<a href="<?php echo esc_url( $proj['permalink'] ); ?>" class="btn-pill btn-pill-outline btn-sm work-card-btn">
										<span><?php esc_html_e( 'View Project', 'kataskevi' ); ?></span>
									</a>
								</div>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="works-carousel-dots" id="works-carousel-dots" aria-label="<?php esc_attr_e( 'Carousel pagination', 'kataskevi' ); ?>"></div>

			<div class="mobile-only-center mt-8">
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
			<?php if ( ! empty( $lifecycle_image ) ) : ?>
				<img 
					src="<?php echo esc_url( $lifecycle_image ); ?>" 
					alt="<?php echo esc_attr( sprintf( __( '%s engineers inspecting a fire alarm panel in a bright facility', 'kataskevi' ), $brand_name ) ); ?>" 
					width="1920" 
					height="820" 
					loading="lazy" 
					class="lifecycle-media-img"
				>
			<?php endif; ?>
			<div class="lifecycle-gradient-overlay" aria-hidden="true"></div>
			<div class="lifecycle-banner-content">
				<div class="container-editorial">
					<?php if ( ! empty( $lifecycle_eyebrow ) ) : ?>
						<p class="eyebrow"><?php echo esc_html( $lifecycle_eyebrow ); ?></p>
					<?php endif; ?>
					<?php if ( ! empty( $lifecycle_title ) ) : ?>
						<h2 class="section-title"><?php echo esc_html( $lifecycle_title ); ?></h2>
					<?php endif; ?>
					<?php if ( ! empty( $lifecycle_desc ) ) : ?>
						<p class="lifecycle-lead">
							<?php echo esc_html( $lifecycle_desc ); ?>
						</p>
					<?php endif; ?>
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
						if ( empty( $step_title ) && empty( $step_desc ) ) {
							continue;
						}
						?>
						<div class="lifecycle-card">
							<span class="lifecycle-card-step"><?php echo esc_html( $step_num ); ?></span>
							<?php if ( ! empty( $step_title ) ) : ?>
								<h3 class="lifecycle-card-title"><?php echo esc_html( $step_title ); ?></h3>
							<?php endif; ?>
							<?php if ( ! empty( $step_desc ) ) : ?>
								<p class="lifecycle-card-desc"><?php echo esc_html( $step_desc ); ?></p>
							<?php endif; ?>
						</div>
					<?php endfor; ?>
				</div>
			</div>
		</div>
	</section>

	<!-- SECTORS WE SERVE -->
	<section id="sectors" class="section-sectors">
		<div class="container-editorial">
			<?php if ( ! empty( $sectors_eyebrow ) ) : ?>
				<p class="eyebrow"><?php echo esc_html( $sectors_eyebrow ); ?></p>
			<?php endif; ?>
			<?php if ( ! empty( $sectors_title ) ) : ?>
				<h2 class="section-title"><?php echo esc_html( $sectors_title ); ?></h2>
			<?php endif; ?>
			<?php if ( ! empty( $sectors_desc ) ) : ?>
				<p class="section-lead-muted">
					<?php echo esc_html( $sectors_desc ); ?>
				</p>
			<?php endif; ?>

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

	<!-- OUR CLIENTS (DUAL INFINITE MARQUEE) -->
	<?php get_template_part( 'template-parts/section', 'clients' ); ?>

	<!-- DARK HIGH-CONTRAST CTA -->
	<?php if ( ! empty( $dark_cta_title ) || ! empty( $dark_cta_desc ) || ! empty( $dark_cta_btn1 ) || ! empty( $dark_cta_btn2 ) ) : ?>
		<section class="section-dark-cta">
			<div class="container-editorial text-center">
				<?php if ( ! empty( $dark_cta_eyebrow ) ) : ?>
					<p class="dark-cta-eyebrow"><?php echo esc_html( $dark_cta_eyebrow ); ?></p>
				<?php endif; ?>
				<?php if ( ! empty( $dark_cta_title ) ) : ?>
					<h2 class="dark-cta-title"><?php echo esc_html( $dark_cta_title ); ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $dark_cta_desc ) ) : ?>
					<p class="dark-cta-lead">
						<?php echo esc_html( $dark_cta_desc ); ?>
					</p>
				<?php endif; ?>
				<?php if ( ! empty( $dark_cta_btn1 ) || ! empty( $dark_cta_btn2 ) ) : ?>
					<div class="dark-cta-actions">
						<?php if ( ! empty( $dark_cta_btn1 ) ) : ?>
							<a href="<?php echo esc_url( $contact_url ); ?>" class="btn-pill btn-pill-light">
								<span><?php echo esc_html( $dark_cta_btn1 ); ?></span>
								<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
							</a>
						<?php endif; ?>
						<?php if ( ! empty( $dark_cta_btn2 ) ) : ?>
							<a href="<?php echo esc_url( $services_url ); ?>" class="btn-pill btn-pill-outline-light">
								<span><?php echo esc_html( $dark_cta_btn2 ); ?></span>
							</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

</main>

<?php
get_footer();
