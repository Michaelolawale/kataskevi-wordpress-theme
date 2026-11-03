<?php
/**
 * Template Name: Our Works
 * Description: The full showcase of Kataskevi engineering, safety, and infrastructure projects.
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

$services_url = kataskevi_get_page_url( 'services', 'Services' );
$contact_url  = kataskevi_get_page_url( 'contact', 'Contact' );
$projects     = kataskevi_get_projects( -1 );

$sectors = array(
	'all'         => esc_html__( 'All Projects', 'kataskevi' ),
	'datacenter'  => esc_html__( 'Data Centers & Telecoms', 'kataskevi' ),
	'logistics'   => esc_html__( 'Logistics & Freight', 'kataskevi' ),
	'commercial'  => esc_html__( 'Commercial & Corporate', 'kataskevi' ),
	'industrial'  => esc_html__( 'Industrial & Energy', 'kataskevi' ),
	'hospitality' => esc_html__( 'Hospitality & Leisure', 'kataskevi' ),
	'healthcare'  => esc_html__( 'Healthcare', 'kataskevi' ),
);

// Customizer Theme Options.
$works_eyebrow = kataskevi_get_mod( 'kataskevi_works_page_eyebrow', 'Case Studies & Delivered Systems' );
$works_title   = kataskevi_get_mod( 'kataskevi_works_page_title', 'Engineered reliability, <em class="text-primary italic">proven in the field.</em>' );
$works_desc    = kataskevi_get_mod( 'kataskevi_works_page_desc', 'From mission-critical colocation suites and high-throughput corporate towers to petrochemical terminals, explore how Kataskevi delivers turnkey safety, security, and IT infrastructure.' );
?>

<main id="primary" class="site-main page-works-view">

	<!-- WORKS HERO -->
	<header class="section-works-hero">
		<div class="container-editorial">
			<p class="eyebrow"><?php echo esc_html( $works_eyebrow ); ?></p>
			<h1 class="works-hero-title">
				<?php echo wp_kses_post( $works_title ); ?>
			</h1>
			<p class="works-hero-lead">
				<?php echo esc_html( $works_desc ); ?>
			</p>

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
					<article 
						class="work-card group" 
						data-sector="<?php echo esc_attr( $proj['sector_slug'] ); ?>"
					>
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
								<?php echo esc_html( $proj['summary'] ); ?>
							</p>

							<?php if ( ! empty( $proj['metric_value'] ) ) : ?>
								<div class="work-card-metric-block">
									<span class="metric-number"><?php echo esc_html( $proj['metric_value'] ); ?></span>
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
					?>
					<div class="commitment-item">
						<div class="commitment-value"><?php echo esc_html( $commit_val ); ?></div>
						<div class="commitment-label"><?php echo esc_html( $commit_lbl ); ?></div>
						<p class="commitment-sub"><?php echo esc_html( $commit_sub ); ?></p>
					</div>
				<?php endfor; ?>
			</div>
		</div>
	</section>

	<!-- BOTTOM CALL TO ACTION -->
	<section class="section-dark-cta">
		<div class="container-editorial text-center">
			<p class="dark-cta-eyebrow"><?php esc_html_e( 'Plan Your Project', 'kataskevi' ); ?></p>
			<h2 class="dark-cta-title"><?php esc_html_e( 'Have a facility requiring protection?', 'kataskevi' ); ?></h2>
			<p class="dark-cta-lead">
				<?php esc_html_e( 'From architectural blueprint review to full-scale engineering execution, our technical team works with facility managers, contractors, and project directors across Nigeria.', 'kataskevi' ); ?>
			</p>
			<div class="dark-cta-actions">
				<a href="<?php echo esc_url( $contact_url ); ?>" class="btn-pill btn-pill-light">
					<span><?php esc_html_e( 'Request Consultation', 'kataskevi' ); ?></span>
					<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
				</a>
				<a href="<?php echo esc_url( $services_url ); ?>" class="btn-pill btn-pill-outline-light">
					<span><?php esc_html_e( 'View Capabilities', 'kataskevi' ); ?></span>
				</a>
			</div>
		</div>
	</section>

</main>

<?php
get_footer();
