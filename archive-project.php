<?php
/**
 * The template for displaying Project archives (Our Works)
 *
 * Adheres strictly to the WordPress PHP Coding Standards (WPCS).
 *
 * @package Kataskevi
 * @version 2.1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$services_url = kataskevi_get_page_url( 'services', 'Services' );
$contact_url  = kataskevi_get_page_url( 'contact', 'Contact' );
$projects     = kataskevi_get_projects( -1 );

$sectors = array(
	'all'            => esc_html__( 'All Projects', 'kataskevi' ),
	'fire-hydrant'   => esc_html__( 'Fire Hydrant & Hose Reel', 'kataskevi' ),
	'alarm-systems'  => esc_html__( 'Alarm Systems', 'kataskevi' ),
	'access-control' => esc_html__( 'Access Control Systems', 'kataskevi' ),
	'cctv-cameras'   => esc_html__( 'CCTV Cameras', 'kataskevi' ),
);

// Customizer Theme Options.
$brand_name    = kataskevi_get_brand_name();
$works_eyebrow = kataskevi_get_mod( 'kataskevi_works_page_eyebrow', 'Our Works' );
if ( empty( $works_eyebrow ) || in_array( $works_eyebrow, array( 'Delivered Systems & Case Studies', 'Case Studies & Delivered Systems' ), true ) ) {
	$works_eyebrow = esc_html__( 'Our Works', 'kataskevi' );
}
$works_title   = kataskevi_get_mod( 'kataskevi_works_page_title', 'Engineered protection, <em class="text-primary italic">proven in the field.</em>' );
$works_desc    = kataskevi_get_mod(
	'kataskevi_works_page_desc',
	sprintf(
		/* translators: %s: Company / Brand Name */
		esc_html__( 'Explore flagship turnkey engineering projects by %s — including high-pressure Fire Hydrant and Hose Reel reticulation, Intelligent Alarm Systems, Biometric Access Control, and 4K CCTV Video Surveillance.', 'kataskevi' ),
		$brand_name
	)
);
$cta_eyebrow = kataskevi_get_mod( 'kataskevi_works_cta_eyebrow', 'Plan Your Project' );
$cta_title   = kataskevi_get_mod( 'kataskevi_works_cta_title', 'Have a facility requiring protection?' );
$cta_desc    = kataskevi_get_mod( 'kataskevi_works_cta_desc', 'From architectural blueprint review to full-scale engineering execution, our technical team works with facility managers, contractors, and project directors across Nigeria.' );
$cta_btn1    = kataskevi_get_mod( 'kataskevi_works_cta_btn1', 'Request Consultation' );
$cta_btn2    = kataskevi_get_mod( 'kataskevi_works_cta_btn2', 'View Capabilities' );
?>

<main id="primary" class="site-main page-works-view">

	<!-- WORKS HERO -->
	<header class="section-works-hero">
		<div class="container-editorial">
			<?php if ( ! empty( $works_eyebrow ) ) : ?>
				<p class="eyebrow"><?php echo esc_html( $works_eyebrow ); ?></p>
			<?php endif; ?>
			<?php if ( ! empty( $works_title ) ) : ?>
				<h1 class="works-hero-title">
					<?php echo wp_kses_post( $works_title ); ?>
				</h1>
			<?php endif; ?>
			<?php if ( ! empty( $works_desc ) ) : ?>
				<p class="works-hero-lead">
					<?php echo esc_html( $works_desc ); ?>
				</p>
			<?php endif; ?>

			<!-- INTERACTIVE SECTOR FILTER -->
			<div class="works-filter-nav" role="tablist" aria-label="<?php esc_attr_e( 'Filter projects by sector', 'kataskevi' ); ?>">
				<?php foreach ( $sectors as $slug => $label ) : ?>
					<button 
						type="button" 
						class="works-filter-btn <?php echo 'all' === $slug ? 'active' : ''; ?>" 
						data-filter="<?php echo esc_attr( $slug ); ?>"
						role="tab"
						aria-selected="<?php echo 'all' === $slug ? 'true' : 'false'; ?>"
					>
						<?php echo esc_html( $label ); ?>
					</button>
				<?php endforeach; ?>
			</div>
		</div>
	</header>

	<!-- PROJECTS SHOWCASE GRID -->
	<section class="section-works-grid">
		<div class="container-editorial">
			<div id="works-grid-container" class="works-projects-grid">
				<?php foreach ( $projects as $proj ) : ?>
					<?php 
					$gallery = ! empty( $proj['gallery'] ) && is_array( $proj['gallery'] ) ? $proj['gallery'] : array( $proj['image'] );
					$has_slider = count( $gallery ) > 1;
					?>
					<article 
						class="work-card group" 
						data-sector="<?php echo esc_attr( $proj['sector_slug'] ); ?>"
					>
						<!-- Clean Card Media with Multi-Image Slider -->
						<div class="work-card-media-wrapper">
							<div class="card-image-slider" data-current-slide="0">
								<div class="card-slider-track">
									<?php foreach ( $gallery as $g_idx => $g_img ) : ?>
										<div class="card-slider-slide <?php echo 0 === $g_idx ? 'is-active' : ''; ?>">
											<a href="<?php echo esc_url( $proj['permalink'] ); ?>" tabindex="-1" aria-hidden="true" class="card-slider-img-link">
												<img 
													src="<?php echo esc_url( $g_img ); ?>" 
													alt="<?php echo esc_attr( $proj['title'] . ( $has_slider ? ' - ' . ( $g_idx + 1 ) : '' ) ); ?>" 
													width="800" 
													height="500" 
													loading="lazy" 
													class="work-card-media-img"
												>
											</a>
										</div>
									<?php endforeach; ?>
								</div>

								<?php if ( $has_slider ) : ?>
									<div class="card-slider-dots">
										<?php foreach ( $gallery as $g_idx => $g_img ) : ?>
											<button type="button" class="card-slider-dot <?php echo 0 === $g_idx ? 'is-active' : ''; ?>" data-slide="<?php echo esc_attr( $g_idx ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Image %d', 'kataskevi' ), $g_idx + 1 ) ); ?>"></button>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>
							</div>
						</div>

						<div class="work-card-content">
							<h2 class="work-card-title">
								<a href="<?php echo esc_url( $proj['permalink'] ); ?>">
									<?php echo esc_html( $proj['title'] ); ?>
								</a>
							</h2>

							<p class="work-card-excerpt">
								<?php echo esc_html( $proj['summary'] ?? $proj['short_desc'] ?? '' ); ?>
							</p>

							<div class="work-card-bottom">
								<a href="<?php echo esc_url( $proj['permalink'] ); ?>" class="btn-pill btn-pill-outline btn-sm work-card-btn">
									<span><?php esc_html_e( 'View Project', 'kataskevi' ); ?></span>
								</a>
							</div>
						</div>
					</article>
				<?php endforeach; ?>
			</div>

			<!-- Dynamic Zero Results State -->
			<div id="works-no-results" class="works-no-results" style="display: none;">
				<p><?php esc_html_e( 'No projects currently listed in this sector.', 'kataskevi' ); ?></p>
				<button type="button" class="btn-pill btn-pill-outline btn-sm" id="reset-filter-btn">
					<?php esc_html_e( 'View All Projects', 'kataskevi' ); ?>
				</button>
			</div>
		</div>
	</section>

	<!-- DELIVERY PERFORMANCE MATRIX -->
	<section class="section-works-commitments">
		<div class="container-editorial">
			<div class="commitments-hairline-grid">
				<?php for ( $c = 1; $c <= 4; $c++ ) : ?>
					<?php
					$default_val = 1 === $c ? '100%' : ( 2 === $c ? 'Tier III & IV' : ( 3 === $c ? 'Zero' : '24/7' ) );
					$default_lbl = 1 === $c ? 'Commissioned on Schedule' : ( 2 === $c ? 'Prepared Infrastructure' : ( 3 === $c ? 'Lost-Time Incidents' : 'Dedicated Support Desk' ) );
					$default_sub = 1 === $c ? 'Rigorous milestone governance from design to live handover.' : ( 2 === $c ? 'Engineered for high-availability mission-critical operations.' : ( 3 === $c ? 'Rigorous HSE protocols on every installation and maintenance site.' : 'Emergency response SLAs covering all contracted facilities.' ) );

					$commit_val = kataskevi_get_mod( 'kataskevi_commit' . $c . '_val', $default_val );
					$commit_lbl = kataskevi_get_mod( 'kataskevi_commit' . $c . '_lbl', $default_lbl );
					$commit_sub = kataskevi_get_mod( 'kataskevi_commit' . $c . '_sub', $default_sub );

					if ( empty( $commit_val ) && empty( $commit_lbl ) && empty( $commit_sub ) ) {
						continue;
					}
					?>
					<div class="commitment-item">
						<?php if ( ! empty( $commit_val ) ) : ?>
							<div class="commitment-value"><?php echo esc_html( $commit_val ); ?></div>
						<?php endif; ?>
						<?php if ( ! empty( $commit_lbl ) ) : ?>
							<div class="commitment-label"><?php echo esc_html( $commit_lbl ); ?></div>
						<?php endif; ?>
						<?php if ( ! empty( $commit_sub ) ) : ?>
							<p class="commitment-sub"><?php echo esc_html( $commit_sub ); ?></p>
						<?php endif; ?>
					</div>
				<?php endfor; ?>
			</div>
		</div>
	</section>

	<!-- BOTTOM CALL TO ACTION -->
	<?php if ( ! empty( $cta_title ) || ! empty( $cta_desc ) || ! empty( $cta_btn1 ) || ! empty( $cta_btn2 ) ) : ?>
		<section class="section-dark-cta">
			<div class="container-editorial text-center">
				<?php if ( ! empty( $cta_eyebrow ) ) : ?>
					<p class="dark-cta-eyebrow"><?php echo esc_html( $cta_eyebrow ); ?></p>
				<?php endif; ?>
				<?php if ( ! empty( $cta_title ) ) : ?>
					<h2 class="dark-cta-title"><?php echo esc_html( $cta_title ); ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $cta_desc ) ) : ?>
					<p class="dark-cta-lead">
						<?php echo esc_html( $cta_desc ); ?>
					</p>
				<?php endif; ?>
				<?php if ( ! empty( $cta_btn1 ) || ! empty( $cta_btn2 ) ) : ?>
					<div class="dark-cta-actions">
						<?php if ( ! empty( $cta_btn1 ) ) : ?>
							<a href="<?php echo esc_url( $contact_url ); ?>" class="btn-pill btn-pill-light">
								<span><?php echo esc_html( $cta_btn1 ); ?></span>
								<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
							</a>
						<?php endif; ?>
						<?php if ( ! empty( $cta_btn2 ) ) : ?>
							<a href="<?php echo esc_url( $services_url ); ?>" class="btn-pill btn-pill-outline-light">
								<span><?php echo esc_html( $cta_btn2 ); ?></span>
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
