<?php
/**
 * The template for displaying single Project case studies
 *
 * Adheres strictly to the WordPress PHP Coding Standards (WPCS).
 *
 * @package Kataskevi
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$works_url    = kataskevi_get_page_url( 'works', 'Our Works' );
$services_url = kataskevi_get_page_url( 'services', 'Services' );
$contact_url  = kataskevi_get_page_url( 'contact', 'Contact' );

// Retrieve project data with resilient multi-channel resolution.
$project_slug = get_query_var( 'project' ) ?: get_query_var( 'project_slug' );

if ( empty( $project_slug ) && isset( $_GET['project'] ) ) {
	$project_slug = sanitize_text_field( wp_unslash( $_GET['project'] ) );
}
if ( empty( $project_slug ) && isset( $_GET['project_slug'] ) ) {
	$project_slug = sanitize_text_field( wp_unslash( $_GET['project_slug'] ) );
}

if ( empty( $project_slug ) ) {
	$queried_obj = get_queried_object();
	if ( $queried_obj instanceof WP_Post && 'project' === $queried_obj->post_type ) {
		$project_slug = $queried_obj->post_name;
	}
}

if ( empty( $project_slug ) ) {
	$req   = trim( (string) parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' );
	$parts = explode( '/', $req );
	$defaults = kataskevi_get_default_projects();
	for ( $i = count( $parts ) - 1; $i >= 0; $i-- ) {
		$segment = $parts[ $i ];
		if ( isset( $defaults[ $segment ] ) || in_array( $segment, array( 'fire-hydrant', 'alarm', 'access-control', 'cctv' ), true ) ) {
			$project_slug = $segment;
			break;
		}
	}
}

$proj = ! empty( $project_slug ) ? kataskevi_get_project_by_slug( $project_slug ) : null;
if ( ! $proj ) {
	$all_projects = kataskevi_get_default_projects();
	$proj         = reset( $all_projects );
}

$gallery     = ! empty( $proj['gallery'] ) && is_array( $proj['gallery'] ) ? $proj['gallery'] : array( $proj['image'] );
$has_gallery = count( $gallery ) > 1;

$all_projects = kataskevi_get_projects( 4 );
$related      = array();
foreach ( $all_projects as $k => $item ) {
	if ( $item['slug'] !== $proj['slug'] ) {
		$related[] = $item;
	}
	if ( count( $related ) >= 2 ) {
		break;
	}
}
?>

<main id="primary" class="site-main project-case-view">

	<!-- BREADCRUMBS & CASE HEADER -->
	<header class="section-case-header">
		<div class="container-editorial">
			<nav class="case-breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb navigation', 'kataskevi' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'kataskevi' ); ?></a>
				<span class="breadcrumb-sep">/</span>
				<a href="<?php echo esc_url( $works_url ); ?>"><?php esc_html_e( 'Our Works', 'kataskevi' ); ?></a>
				<span class="breadcrumb-sep">/</span>
				<span class="breadcrumb-current"><?php echo esc_html( $proj['title'] ); ?></span>
			</nav>

			<div class="case-hero-meta">
				<span class="sector-pill"><?php echo esc_html( $proj['sector'] ); ?></span>
				<span class="case-year-tag"><?php echo esc_html( $proj['year'] ); ?></span>
			</div>

			<h1 class="case-title">
				<?php echo esc_html( $proj['title'] ); ?>
			</h1>

			<p class="case-lead-summary">
				<?php echo esc_html( $proj['short_desc'] ?? $proj['summary'] ?? '' ); ?>
			</p>
		</div>
	</header>

	<!-- FEATURED MEDIA BANNER -->
	<section class="section-case-media">
		<div class="container-editorial">
			<div class="case-media-frame">
				<img 
					src="<?php echo esc_url( $proj['image'] ); ?>" 
					alt="<?php echo esc_attr( $proj['title'] ); ?>" 
					width="1400" 
					height="780" 
					class="case-media-img"
				>
			</div>
		</div>
	</section>

	<!-- MAIN CONTENT SPLIT: BODY & SIDEBAR -->
	<section class="section-case-body">
		<div class="container-editorial">
			<div class="case-layout-grid">
				
				<!-- Left Column: Story, Challenge, Solution, Deliverables -->
				<article class="case-narrative">
					
					<?php if ( ! empty( $proj['challenge'] ) ) : ?>
						<div class="case-block">
							<h2 class="case-subheading"><?php esc_html_e( 'The Operational Challenge', 'kataskevi' ); ?></h2>
							<p class="case-paragraph">
								<?php echo esc_html( $proj['challenge'] ); ?>
							</p>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $proj['solution'] ) ) : ?>
						<div class="case-block">
							<h2 class="case-subheading"><?php esc_html_e( 'The Engineered Solution', 'kataskevi' ); ?></h2>
							<p class="case-paragraph">
								<?php echo esc_html( $proj['solution'] ); ?>
							</p>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $proj['deliverables'] ) ) : ?>
						<div class="case-block">
							<h2 class="case-subheading"><?php esc_html_e( 'Systems Delivered &amp; Commissioned', 'kataskevi' ); ?></h2>
							<ul class="case-deliverables-checklist">
								<?php foreach ( $proj['deliverables'] as $item ) : ?>
									<li>
										<span class="check-icon-wrap" aria-hidden="true">
											<?php echo kataskevi_render_icon( 'check', 'icon icon-xs text-primary' ); ?>
										</span>
										<span><?php echo esc_html( $item ); ?></span>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>

					<?php if ( $has_gallery ) : ?>
						<!-- Project Image Gallery -->
						<div class="case-block case-gallery-block">
							<h2 class="case-subheading"><?php esc_html_e( 'Project Visuals & Installation Gallery', 'kataskevi' ); ?></h2>
							<div class="case-gallery-grid">
								<?php foreach ( $gallery as $g_idx => $g_img ) : ?>
									<div class="case-gallery-item">
										<img 
											src="<?php echo esc_url( $g_img ); ?>" 
											alt="<?php echo esc_attr( $proj['title'] . ' - Photo ' . ( $g_idx + 1 ) ); ?>" 
											loading="lazy" 
											class="case-gallery-img"
										>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>

					<!-- Handover & Lifecycle Note -->
					<div class="case-block case-lifecycle-note">
						<h3 class="note-title"><?php esc_html_e( 'Commissioning & Handover Protocol', 'kataskevi' ); ?></h3>
						<p class="note-text">
							<?php
							printf(
								/* translators: %s: Company / Brand Name */
								esc_html__( 'Following final pressure integrity testing, NFPA audit verifications, and integrated BMS simulation runs, %s provided full as-built documentation, facility manager certifications, and active 24/7 on-call SLA maintenance coverage.', 'kataskevi' ),
								esc_html( kataskevi_get_brand_name() )
							);
							?>
						</p>
					</div>

				</article>

				<!-- Right Column: Technical Specs & Action Card -->
				<aside class="case-sidebar">
					<div class="sidebar-sticky-panel">
						
						<!-- Project Specifications Table -->
						<div class="specs-card">
							<h3 class="specs-heading"><?php esc_html_e( 'Project Metadata', 'kataskevi' ); ?></h3>
							<dl class="specs-list">
								<div class="spec-row">
									<dt><?php esc_html_e( 'Client', 'kataskevi' ); ?></dt>
									<dd><?php echo esc_html( $proj['client'] ); ?></dd>
								</div>
								<div class="spec-row">
									<dt><?php esc_html_e( 'Sector', 'kataskevi' ); ?></dt>
									<dd><?php echo esc_html( $proj['sector'] ); ?></dd>
								</div>
								<div class="spec-row">
									<dt><?php esc_html_e( 'Location', 'kataskevi' ); ?></dt>
									<dd><?php echo esc_html( $proj['location'] ); ?></dd>
								</div>
								<div class="spec-row">
									<dt><?php esc_html_e( 'Surface Area', 'kataskevi' ); ?></dt>
									<dd><?php echo esc_html( $proj['surface_area'] ?? '450,000 m²' ); ?></dd>
								</div>
								<div class="spec-row">
									<dt><?php esc_html_e( 'Year Completed', 'kataskevi' ); ?></dt>
									<dd><?php echo esc_html( $proj['year'] ?? '2014' ); ?></dd>
								</div>
								<div class="spec-row">
									<dt><?php esc_html_e( 'Value', 'kataskevi' ); ?></dt>
									<dd><?php echo esc_html( $proj['value'] ?? '$250,000' ); ?></dd>
								</div>
								<div class="spec-row">
									<dt><?php esc_html_e( 'Architect', 'kataskevi' ); ?></dt>
									<dd><?php echo esc_html( $proj['architect'] ?? 'Jason & Perry' ); ?></dd>
								</div>
								<div class="spec-row">
									<dt><?php esc_html_e( 'Accountability', 'kataskevi' ); ?></dt>
									<dd><?php esc_html_e( 'Survey, Design, Supply, Install, Maintain', 'kataskevi' ); ?></dd>
								</div>
								<div class="spec-row">
									<dt><?php esc_html_e( 'Compliance', 'kataskevi' ); ?></dt>
									<dd><?php esc_html_e( 'NFPA, ISO & EN Standards', 'kataskevi' ); ?></dd>
								</div>
							</dl>
						</div>

						<!-- Action Card -->
						<div class="sidebar-quote-card">
							<div class="quote-card-badge"><?php esc_html_e( 'Site Consultation', 'kataskevi' ); ?></div>
							<h4 class="quote-card-title"><?php esc_html_e( 'Need a similar protection system?', 'kataskevi' ); ?></h4>
							<p class="quote-card-text">
								<?php
								printf(
									/* translators: %s: Company / Brand Name */
									esc_html__( 'Speak directly with a %s systems engineer. We assess your facility floorplans and develop an end-to-end design.', 'kataskevi' ),
									esc_html( kataskevi_get_brand_name() )
								);
								?>
							</p>
							<a href="<?php echo esc_url( add_query_arg( array( 'project' => $proj['slug'] ), $contact_url ) ); ?>" class="btn-pill btn-pill-primary btn-block">
								<span><?php esc_html_e( 'Request a Site Survey', 'kataskevi' ); ?></span>
								<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
							</a>
							<?php 
							$emergency_phone = kataskevi_get_mod( 'kataskevi_emergency_phone', '+234 802 345 6789' );
							if ( ! empty( $emergency_phone ) ) : 
							?>
								<div class="quote-card-direct">
									<span><?php esc_html_e( 'Or call 24/7 support:', 'kataskevi' ); ?></span>
									<strong><?php echo esc_html( $emergency_phone ); ?></strong>
								</div>
							<?php endif; ?>
						</div>

					</div>
				</aside>

			</div>
		</div>
	</section>

	<!-- RELATED WORKS -->
	<?php if ( ! empty( $related ) ) : ?>
		<section class="section-related-works">
			<div class="container-editorial">
				<div class="section-header-split">
					<div>
						<p class="eyebrow"><?php esc_html_e( 'Case Studies', 'kataskevi' ); ?></p>
						<h2 class="section-title"><?php esc_html_e( 'Explore more works', 'kataskevi' ); ?></h2>
					</div>
					<a href="<?php echo esc_url( $works_url ); ?>" class="btn-pill btn-pill-outline btn-sm">
						<span><?php esc_html_e( 'All Projects', 'kataskevi' ); ?></span>
						<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
					</a>
				</div>

				<div class="related-works-grid">
					<?php foreach ( $related as $rel ) : ?>
						<article class="featured-work-card group">
							<a href="<?php echo esc_url( $rel['permalink'] ); ?>" class="featured-work-media-link" aria-label="<?php echo esc_attr( $rel['title'] ); ?>">
								<div class="featured-work-media">
									<img 
										src="<?php echo esc_url( $rel['image'] ); ?>" 
										alt="<?php echo esc_attr( $rel['title'] ); ?>" 
										width="800" 
										height="540" 
										loading="lazy" 
										class="featured-work-img"
									>
								</div>
							</a>
							<div class="featured-work-body">
								<h3 class="featured-work-title">
									<a href="<?php echo esc_url( $rel['permalink'] ); ?>">
										<?php echo esc_html( $rel['title'] ); ?>
									</a>
								</h3>
								<div class="featured-work-action">
									<a href="<?php echo esc_url( $rel['permalink'] ); ?>" class="btn-pill btn-pill-outline btn-sm work-card-btn">
										<span><?php esc_html_e( 'View Project', 'kataskevi' ); ?></span>
									</a>
								</div>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

</main>

<?php
get_footer();
