<?php
/**
 * Template Name: Our Works
 * Description: The full showcase of Kataskevi engineering, safety, and infrastructure projects.
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
	'all'         => 'All Projects',
	'datacenter'  => 'Data Centers & Telecoms',
	'logistics'   => 'Logistics & Freight',
	'commercial'  => 'Commercial & Corporate',
	'industrial'  => 'Industrial & Energy',
	'hospitality' => 'Hospitality & Leisure',
	'healthcare'  => 'Healthcare',
);
?>

<main id="primary" class="site-main page-works-view">

	<!-- WORKS HERO -->
	<header class="section-works-hero">
		<div class="container-editorial">
			<p class="eyebrow">Case Studies &amp; Delivered Systems</p>
			<h1 class="works-hero-title">
				Engineered reliability, <em class="text-primary italic">proven in the field.</em>
			</h1>
			<p class="works-hero-lead">
				From mission-critical colocation suites and high-throughput corporate towers to petrochemical terminals, explore how Kataskevi delivers turnkey safety, security, and IT infrastructure.
			</p>

			<!-- INTERACTIVE SECTOR FILTER -->
			<div class="works-filter-nav" role="tablist" aria-label="<?php esc_attr_e( 'Filter projects by sector', 'kataskevi' ); ?>">
				<?php foreach ( $sectors as $slug => $label ) : ?>
					<button 
						type="button" 
						class="works-filter-btn <?php echo $slug === 'all' ? 'active' : ''; ?>" 
						data-filter="<?php echo esc_attr( $slug ); ?>"
						role="tab"
						aria-selected="<?php echo $slug === 'all' ? 'true' : 'false'; ?>"
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
								<?php echo esc_html( $proj['short_desc'] ); ?>
							</p>

							<?php if ( ! empty( $proj['deliverables'] ) ) : ?>
								<ul class="work-card-bullet-preview">
									<?php 
									$preview_deliverables = array_slice( $proj['deliverables'], 0, 2 );
									foreach ( $preview_deliverables as $deliv ) : 
									?>
										<li>
											<?php echo kataskevi_render_icon( 'check', 'icon icon-xs text-primary' ); ?>
											<span><?php echo esc_html( $deliv ); ?></span>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>

							<div class="work-card-bottom">
								<div class="work-card-metric-block">
									<div class="metric-number"><?php echo esc_html( $proj['metric'] ); ?></div>
									<div class="metric-caption"><?php echo esc_html( $proj['metric_label'] ); ?></div>
								</div>

								<a href="<?php echo esc_url( $proj['permalink'] ); ?>" class="btn-pill btn-pill-outline btn-sm">
									<span>Case Study</span>
									<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
								</a>
							</div>
						</div>
					</article>
				<?php endforeach; ?>
			</div>

			<div id="works-empty-notice" class="works-no-results" style="display: none;">
				<p>No projects currently listed in this sector.</p>
				<button type="button" class="btn-pill btn-pill-outline btn-sm" id="reset-filter-btn">
					View All Projects
				</button>
			</div>
		</div>
	</section>

	<!-- DELIVERY PERFORMANCE MATRIX -->
	<section class="section-works-commitments">
		<div class="container-editorial">
			<div class="commitments-hairline-grid">
				<div class="commitment-item">
					<div class="commitment-value">100%</div>
					<div class="commitment-label">Commissioned on Schedule</div>
					<p class="commitment-sub">Rigorous milestone governance from design to live handover.</p>
				</div>
				<div class="commitment-item">
					<div class="commitment-value">Zero</div>
					<div class="commitment-label">Compromised Critical Zones</div>
					<p class="commitment-sub">NFPA and EN compliance standards enforced on every site.</p>
				</div>
				<div class="commitment-item">
					<div class="commitment-value">24/7</div>
					<div class="commitment-label">Engineering Support Line</div>
					<p class="commitment-sub">Rapid on-call engineering dispatch for mission-critical client systems.</p>
				</div>
				<div class="commitment-item">
					<div class="commitment-value">1:1</div>
					<div class="commitment-label">Single Accountable Partner</div>
					<p class="commitment-sub">No fragmented subcontractors — one accountable point of contact.</p>
				</div>
			</div>
		</div>
	</section>

	<!-- BOTTOM CALL TO ACTION -->
	<section class="section-dark-cta">
		<div class="container-editorial text-center">
			<p class="dark-cta-eyebrow">Plan Your Project</p>
			<h2 class="dark-cta-title">Have a facility requiring protection?</h2>
			<p class="dark-cta-lead">
				From architectural blueprint review to full-scale engineering execution, our technical team works with facility managers, contractors, and project directors across Nigeria.
			</p>
			<div class="dark-cta-actions">
				<a href="<?php echo esc_url( $contact_url ); ?>" class="btn-pill btn-pill-light">
					<span>Request a Project Consultation</span>
					<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
				</a>
				<a href="<?php echo esc_url( $services_url ); ?>" class="btn-pill btn-pill-outline-light">
					<span>Explore Service Capabilities</span>
				</a>
			</div>
		</div>
	</section>

</main>

<?php
get_footer();

