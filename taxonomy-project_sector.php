<?php
/**
 * The template for displaying Project Sector taxonomy archives
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

$current_term = get_queried_object();
$current_slug = $current_term instanceof WP_Term ? $current_term->slug : '';
$current_name = $current_term instanceof WP_Term ? $current_term->name : esc_html__( 'Sector Projects', 'kataskevi' );

$services_url = kataskevi_get_page_url( 'services', 'Services' );
$contact_url  = kataskevi_get_page_url( 'contact', 'Contact' );
$works_url    = kataskevi_get_page_url( 'works', 'Our Works' );

$projects = kataskevi_get_projects( -1, false, $current_slug );

$sectors = array(
	'all'            => esc_html__( 'All Projects', 'kataskevi' ),
	'fire-hydrant'   => esc_html__( 'Fire Hydrant & Hose Reel', 'kataskevi' ),
	'alarm-systems'  => esc_html__( 'Alarm Systems', 'kataskevi' ),
	'access-control' => esc_html__( 'Access Control Systems', 'kataskevi' ),
	'cctv-cameras'   => esc_html__( 'CCTV Cameras', 'kataskevi' ),
);
?>

<main id="primary" class="site-main page-works-view">

	<!-- SECTOR HEADER -->
	<header class="section-works-hero">
		<div class="container-editorial">
			<p class="eyebrow"><?php esc_html_e( 'Sector Showcase', 'kataskevi' ); ?></p>
			<h1 class="works-hero-title">
				<?php echo esc_html( $current_name ); ?>
			</h1>
			<?php if ( ! empty( $current_term->description ) ) : ?>
				<p class="works-hero-lead">
					<?php echo esc_html( $current_term->description ); ?>
				</p>
			<?php else : ?>
				<p class="works-hero-lead">
					<?php
					printf(
						/* translators: %s: sector name */
						esc_html__( 'Explore delivered systems, infrastructure designs, and engineering case studies across %s.', 'kataskevi' ),
						esc_html( $current_name )
					);
					?>
				</p>
			<?php endif; ?>

			<!-- SECTOR NAVIGATION -->
			<div class="works-filter-nav">
				<a href="<?php echo esc_url( $works_url ); ?>" class="works-filter-btn">
					<?php esc_html_e( 'All Projects', 'kataskevi' ); ?>
				</a>
				<?php foreach ( $sectors as $slug => $label ) : ?>
					<?php if ( 'all' === $slug ) continue; ?>
					<a 
						href="<?php echo esc_url( get_term_link( $slug, 'project_sector' ) ); ?>" 
						class="works-filter-btn <?php echo $current_slug === $slug ? 'active' : ''; ?>"
					>
						<?php echo esc_html( $label ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</header>

	<!-- PROJECTS SHOWCASE GRID -->
	<section class="section-works-grid">
		<div class="container-editorial">
			<?php if ( ! empty( $projects ) ) : ?>
				<div class="works-projects-grid">
					<?php foreach ( $projects as $proj ) : ?>
						<?php 
						$gallery = ! empty( $proj['gallery'] ) && is_array( $proj['gallery'] ) ? $proj['gallery'] : array( $proj['image'] );
						$has_slider = count( $gallery ) > 1;
						?>
						<article class="work-card group">
							<div class="work-card-media-wrapper">
								<div class="card-image-slider" data-current-slide="0">
									<div class="card-slider-track">
										<?php foreach ( $gallery as $g_idx => $g_img ) : ?>
											<?php
											$optimized_img = kataskevi_get_webp_url( $g_img );
											$is_first      = ( 0 === $g_idx );
											?>
											<div class="card-slider-slide <?php echo $is_first ? 'is-active' : ''; ?>">
												<a href="<?php echo esc_url( $proj['permalink'] ); ?>" tabindex="-1" aria-hidden="true" class="card-slider-img-link">
													<img 
														src="<?php echo esc_url( $is_first ? $optimized_img : 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 800 500\'%3E%3C/svg%3E' ); ?>" 
														<?php if ( ! $is_first ) : ?>
															data-src="<?php echo esc_url( $optimized_img ); ?>" 
														<?php endif; ?>
														alt="<?php echo esc_attr( $proj['title'] . ( $has_slider ? ' - ' . ( $g_idx + 1 ) : '' ) ); ?>" 
														width="800" 
														height="500" 
														loading="lazy" 
														decoding="async" 
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
			<?php else : ?>
				<div class="works-no-results">
					<p><?php esc_html_e( 'No projects currently published in this sector.', 'kataskevi' ); ?></p>
					<a href="<?php echo esc_url( $works_url ); ?>" class="btn-pill btn-pill-outline btn-sm">
						<?php esc_html_e( 'View All Projects', 'kataskevi' ); ?>
					</a>
				</div>
			<?php endif; ?>
		</div>
	</section>

</main>

<?php
get_footer();
