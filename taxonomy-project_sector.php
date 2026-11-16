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
	'all'         => esc_html__( 'All Projects', 'kataskevi' ),
	'datacenter'  => esc_html__( 'Data Centers & Telecoms', 'kataskevi' ),
	'logistics'   => esc_html__( 'Logistics & Freight', 'kataskevi' ),
	'commercial'  => esc_html__( 'Commercial & Corporate', 'kataskevi' ),
	'industrial'  => esc_html__( 'Industrial & Energy', 'kataskevi' ),
	'hospitality' => esc_html__( 'Hospitality & Leisure', 'kataskevi' ),
	'healthcare'  => esc_html__( 'Healthcare', 'kataskevi' ),
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
						<article class="work-card group">
							<div class="work-card-media-wrapper">
								<a href="<?php echo esc_url( $proj['permalink'] ); ?>" tabindex="-1" aria-hidden="true">
									<img 
										src="<?php echo esc_url( $proj['image'] ); ?>" 
										alt="<?php echo esc_attr( $proj['title'] ); ?>" 
										width="800" 
										height="500" 
										loading="lazy" 
										class="work-card-media-img"
									>
								</a>
								<div class="work-card-badge-row">
									<span class="sector-pill"><?php echo esc_html( $proj['sector'] ); ?></span>
									<span class="year-pill"><?php echo esc_html( $proj['year'] ); ?></span>
								</div>
							</div>

							<div class="work-card-content">
								<div class="work-card-meta">
									<span class="meta-client"><?php echo esc_html( $proj['client'] ); ?></span>
									<span class="meta-sep" aria-hidden="true">·</span>
									<span class="meta-loc"><?php echo esc_html( $proj['location'] ); ?></span>
								</div>

								<h2 class="work-card-title">
									<a href="<?php echo esc_url( $proj['permalink'] ); ?>">
										<?php echo esc_html( $proj['title'] ); ?>
									</a>
								</h2>

								<p class="work-card-excerpt">
									<?php echo esc_html( $proj['summary'] ?? $proj['short_desc'] ); ?>
								</p>

								<?php if ( ! empty( $proj['metric_value'] ) || ! empty( $proj['metric'] ) ) : ?>
									<div class="work-card-metric-block">
										<span class="metric-number"><?php echo esc_html( $proj['metric_value'] ?? $proj['metric'] ); ?></span>
										<span class="metric-caption"><?php echo esc_html( $proj['metric_label'] ); ?></span>
									</div>
								<?php endif; ?>

								<div class="work-card-bottom">
									<a href="<?php echo esc_url( $proj['permalink'] ); ?>" class="read-more-link">
										<span><?php esc_html_e( 'Explore case study', 'kataskevi' ); ?></span>
										<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
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
