<?php
/**
 * The template for displaying single Service detail pages
 *
 * Adheres strictly to the WordPress PHP Coding Standards (WPCS).
 *
 * @package Kataskevi
 * @version 2.1.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$services_url = kataskevi_get_page_url( 'services', 'Services' );
$contact_url  = kataskevi_get_page_url( 'contact', 'Contact' );
$brand_name   = kataskevi_get_brand_name();

// 1. Resolve active service slug
$service_slug = get_post_field( 'post_name', get_the_ID() );
if ( empty( $service_slug ) ) {
	$service_slug = get_query_var( 'service_slug' );
}
if ( empty( $service_slug ) && isset( $_GET['service_slug'] ) ) {
	$service_slug = sanitize_title( wp_unslash( $_GET['service_slug'] ) );
}
if ( empty( $service_slug ) && isset( $_GET['service'] ) ) {
	$service_slug = sanitize_title( wp_unslash( $_GET['service'] ) );
}
if ( empty( $service_slug ) ) {
	$req_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
	$req_path = trim( (string) parse_url( $req_uri, PHP_URL_PATH ), '/' );
	$parts    = explode( '/', $req_path );
	if ( count( $parts ) >= 2 && ( 'service' === $parts[0] || 'services' === $parts[0] ) ) {
		$service_slug = sanitize_title( $parts[1] );
	}
}

// 2. Fetch service data
$service = kataskevi_get_service_by_slug( $service_slug );
if ( ! $service ) {
	$all_services = kataskevi_get_main_services();
	$service      = reset( $all_services );
}

$inquire_url  = esc_url( add_query_arg( 'service', urlencode( $service['title'] ), $contact_url ) );
$all_services = kataskevi_get_main_services();
$other_services = array();
foreach ( $all_services as $s_key => $s_item ) {
	if ( $s_item['slug'] !== $service['slug'] ) {
		$other_services[] = $s_item;
	}
}

// 3. Featured related projects
$related_projects = kataskevi_get_projects( 2, false );
?>

<main id="primary" class="site-main service-detail-view">

	<!-- BREADCRUMBS & HERO HEADER -->
	<header class="section-service-detail-header">
		<div class="container-editorial">
			<nav class="case-breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb navigation', 'kataskevi' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'kataskevi' ); ?></a>
				<span class="breadcrumb-sep">/</span>
				<a href="<?php echo esc_url( $services_url ); ?>"><?php esc_html_e( 'Services', 'kataskevi' ); ?></a>
				<span class="breadcrumb-sep">/</span>
				<span class="breadcrumb-current"><?php echo esc_html( $service['title'] ); ?></span>
			</nav>

			<p class="eyebrow"><?php esc_html_e( 'Core Engineering Service', 'kataskevi' ); ?></p>

			<h1 class="case-title">
				<?php echo esc_html( $service['title'] ); ?>
			</h1>

			<p class="case-lead-summary">
				<?php echo esc_html( ! empty( $service['lead'] ) ? $service['lead'] : $service['short_desc'] ); ?>
			</p>

			<div class="hero-actions mt-8">
				<a href="<?php echo $inquire_url; ?>" class="btn-pill btn-pill-primary">
					<span><?php esc_html_e( 'Request Consultation', 'kataskevi' ); ?></span>
					<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
				</a>
				<a href="<?php echo esc_url( $services_url ); ?>" class="btn-pill btn-pill-outline">
					<span><?php esc_html_e( 'View All Services', 'kataskevi' ); ?></span>
				</a>
			</div>
		</div>
	</header>

	<!-- FEATURED MEDIA BANNER -->
	<?php if ( ! empty( $service['image'] ) ) : ?>
		<section class="section-case-media">
			<div class="container-editorial">
				<div class="case-media-frame">
					<img 
						src="<?php echo esc_url( $service['image'] ); ?>" 
						alt="<?php echo esc_attr( $service['title'] ); ?>" 
						width="1400" 
						height="780" 
						class="case-media-img"
						fetchpriority="high"
					>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<!-- KEY CAPABILITIES METRIC STRIP -->
	<?php if ( ! empty( $service['metrics'] ) ) : ?>
		<section class="section-case-metrics">
			<div class="container-editorial">
				<div class="case-metrics-grid">
					<?php foreach ( $service['metrics'] as $metric ) : ?>
						<div class="case-metric-card">
							<div class="metric-num"><?php echo esc_html( $metric['num'] ); ?></div>
							<div class="metric-txt"><?php echo esc_html( $metric['label'] ); ?></div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<!-- MAIN CONTENT SPLIT: BODY & TECHNICAL SIDEBAR -->
	<section class="section-case-body">
		<div class="container-editorial">
			<div class="case-layout-grid">

				<!-- Left Column: Detailed Service Narrative & Methodology -->
				<article class="case-narrative">

					<!-- Overview Block -->
					<div class="case-block">
						<h2 class="case-subheading"><?php esc_html_e( 'Service Scope & Engineering Approach', 'kataskevi' ); ?></h2>
						<p class="case-paragraph">
							<?php echo esc_html( ! empty( $service['overview'] ) ? $service['overview'] : $service['description'] ); ?>
						</p>
					</div>

					<!-- Step-by-Step Delivery Methodology -->
					<?php if ( ! empty( $service['methodology'] ) ) : ?>
						<div class="case-block">
							<h2 class="case-subheading"><?php esc_html_e( 'Execution Methodology', 'kataskevi' ); ?></h2>
							<p class="case-paragraph">
								<?php esc_html_e( 'Every project milestone is delivered through a disciplined, sequential engineering lifecycle designed to eliminate uncertainty and ensure zero-defect handovers.', 'kataskevi' ); ?>
							</p>

							<div class="service-methodology-list">
								<?php foreach ( $service['methodology'] as $m_step ) : ?>
									<div class="service-methodology-item">
										<div class="service-methodology-step-num"><?php echo esc_html( $m_step['step'] ); ?></div>
										<div class="service-methodology-step-content">
											<h3 class="service-methodology-step-title"><?php echo esc_html( $m_step['title'] ); ?></h3>
											<p class="service-methodology-step-desc"><?php echo esc_html( $m_step['desc'] ); ?></p>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>

					<!-- Deliverables Checklist -->
					<?php if ( ! empty( $service['deliverables'] ) ) : ?>
						<div class="case-block">
							<h2 class="case-subheading"><?php esc_html_e( 'Delivered Engineering Assets', 'kataskevi' ); ?></h2>
							<ul class="case-deliverables-checklist">
								<?php foreach ( $service['deliverables'] as $item ) : ?>
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

					<!-- Standards & Compliance -->
					<?php if ( ! empty( $service['compliance'] ) ) : ?>
						<div class="case-block case-lifecycle-note">
							<h3 class="note-title"><?php esc_html_e( 'Regulatory Standards & Compliance Framework', 'kataskevi' ); ?></h3>
							<p class="note-text">
								<?php
								printf(
									/* translators: 1: Service title, 2: Compliance standards, 3: Brand name */
									esc_html__( '%1$s operations strictly adhere to %2$s. %3$s engineers ensure every design, calculation, and field installation passes scrutiny by statutory fire service authorities, insurer auditors, and facility managers.', 'kataskevi' ),
									esc_html( $service['title'] ),
									esc_html( $service['compliance'] ),
									esc_html( $brand_name )
								);
								?>
							</p>
						</div>
					<?php endif; ?>

				</article>

				<!-- Right Column: Sticky Sidebar -->
				<aside class="case-sidebar">
					<div class="sidebar-sticky-panel">

						<!-- Service Metadata / Specifications Table -->
						<div class="specs-card">
							<h3 class="specs-heading"><?php esc_html_e( 'Service Specifications', 'kataskevi' ); ?></h3>
							<dl class="specs-list">
								<div class="spec-row">
									<dt><?php esc_html_e( 'Category', 'kataskevi' ); ?></dt>
									<dd><?php echo esc_html( $service['badge'] ); ?></dd>
								</div>
								<div class="spec-row">
									<dt><?php esc_html_e( 'Execution', 'kataskevi' ); ?></dt>
									<dd><?php esc_html_e( 'Certified Engineers', 'kataskevi' ); ?></dd>
								</div>
								<div class="spec-row">
									<dt><?php esc_html_e( 'Compliance', 'kataskevi' ); ?></dt>
									<dd><?php echo esc_html( ! empty( $service['compliance'] ) ? wp_trim_words( $service['compliance'], 5 ) : 'NFPA & ISO' ); ?></dd>
								</div>
								<div class="spec-row">
									<dt><?php esc_html_e( 'Accountability', 'kataskevi' ); ?></dt>
									<dd><?php esc_html_e( 'Single Accountable Partner', 'kataskevi' ); ?></dd>
								</div>
							</dl>
						</div>

						<!-- Quick Consultation Card -->
						<div class="sidebar-quote-card">
							<p class="eyebrow quote-card-eyebrow"><?php esc_html_e( 'Direct Consultation', 'kataskevi' ); ?></p>
							<h4 class="quote-card-title"><?php esc_html_e( 'Schedule this service for your facility', 'kataskevi' ); ?></h4>
							<p class="quote-card-text">
								<?php
								printf(
									/* translators: 1: Brand name, 2: Service Title */
									esc_html__( 'Connect with a %1$s lead specialist to discuss your facility floorplans, risk profiles, or scope for %2$s.', 'kataskevi' ),
									esc_html( $brand_name ),
									esc_html( $service['title'] )
								);
								?>
							</p>
							<a href="<?php echo $inquire_url; ?>" class="btn-pill btn-pill-primary btn-block">
								<span><?php esc_html_e( 'Request Consultation', 'kataskevi' ); ?></span>
								<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
							</a>

							<?php 
							$emergency_phone = kataskevi_get_mod( 'kataskevi_emergency_phone', '+234 802 345 6789' );
							if ( ! empty( $emergency_phone ) ) : 
							?>
								<div class="quote-card-direct">
									<span><?php esc_html_e( 'Or call engineering desk:', 'kataskevi' ); ?></span>
									<strong><?php echo esc_html( $emergency_phone ); ?></strong>
								</div>
							<?php endif; ?>
						</div>

						<!-- Other Core Services Navigation -->
						<?php if ( ! empty( $other_services ) ) : ?>
							<div class="specs-card mt-6">
								<h3 class="specs-heading"><?php esc_html_e( 'Other Core Pillars', 'kataskevi' ); ?></h3>
								<ul class="service-sidebar-links">
									<?php foreach ( $other_services as $other_s ) : ?>
										<li>
											<a href="<?php echo esc_url( kataskevi_get_service_url( $other_s['slug'] ) ); ?>" class="service-sidebar-link">
												<span><?php echo esc_html( $other_s['title'] ); ?></span>
												<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
											</a>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
						<?php endif; ?>

					</div>
				</aside>

			</div>
		</div>
	</section>

	<!-- RELATED WORKS / CASE STUDIES -->
	<?php if ( ! empty( $related_projects ) ) : ?>
		<section class="section-related-works border-t border-border">
			<div class="container-editorial">
				<div class="section-header-split">
					<div>
						<p class="eyebrow"><?php esc_html_e( 'Demonstrated In Practice', 'kataskevi' ); ?></p>
						<h2 class="section-title"><?php esc_html_e( 'Recent installations & deployments', 'kataskevi' ); ?></h2>
					</div>
					<a href="<?php echo esc_url( kataskevi_get_page_url( 'works', 'Our Works' ) ); ?>" class="btn-pill btn-pill-outline btn-sm desktop-only-inline">
						<span><?php esc_html_e( 'Explore All Projects', 'kataskevi' ); ?></span>
						<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
					</a>
				</div>

				<div class="featured-works-grid mt-10">
					<?php foreach ( $related_projects as $r_proj ) : ?>
						<article class="featured-work-card group">
							<a href="<?php echo esc_url( $r_proj['permalink'] ); ?>" class="featured-work-media-link" aria-label="<?php echo esc_attr( $r_proj['title'] ); ?>">
								<div class="featured-work-media">
									<img 
										src="<?php echo esc_url( $r_proj['image'] ); ?>" 
										alt="<?php echo esc_attr( $r_proj['title'] ); ?>" 
										width="800" 
										height="500" 
										loading="lazy" 
										class="featured-work-img"
									>
								</div>
							</a>

							<div class="featured-work-body">
								<h3 class="featured-work-title">
									<a href="<?php echo esc_url( $r_proj['permalink'] ); ?>">
										<?php echo esc_html( $r_proj['title'] ); ?>
									</a>
								</h3>

								<?php if ( ! empty( $r_proj['short_desc'] ) ) : ?>
									<p class="featured-work-desc">
										<?php echo esc_html( wp_trim_words( $r_proj['short_desc'], 18 ) ); ?>
									</p>
								<?php endif; ?>

								<div class="featured-work-action mt-auto">
									<a href="<?php echo esc_url( $r_proj['permalink'] ); ?>" class="btn-pill btn-pill-outline btn-sm">
										<span><?php esc_html_e( 'Read More', 'kataskevi' ); ?></span>
										<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
									</a>
								</div>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<!-- BOTTOM CONVERSION BANNER -->
	<?php
	$service_cta_eyebrow = kataskevi_get_mod( 'kataskevi_dark_cta_eyebrow', 'Get Started' );
	$service_cta_desc    = kataskevi_get_mod( 'kataskevi_dark_cta_desc', 'Our multidisciplinary engineering team is prepared to audit your environment, deliver comprehensive drawings, and ensure complete compliance.' );
	$service_cta_btn1    = kataskevi_get_mod( 'kataskevi_dark_cta_btn1_text', 'Request Consultation' );
	?>
	<section class="section-dark-cta">
		<div class="container-editorial text-center">
			<?php if ( ! empty( $service_cta_eyebrow ) ) : ?>
				<p class="eyebrow text-primary-light"><?php echo esc_html( $service_cta_eyebrow ); ?></p>
			<?php endif; ?>

			<h2 class="dark-cta-title">
				<?php 
				/* translators: %s: Service Title */
				printf( esc_html__( 'Ready to commission %s for your facility?', 'kataskevi' ), esc_html( $service['title'] ) ); 
				?>
			</h2>

			<?php if ( ! empty( $service_cta_desc ) ) : ?>
				<p class="dark-cta-desc">
					<?php echo esc_html( $service_cta_desc ); ?>
				</p>
			<?php endif; ?>

			<div class="dark-cta-actions justify-center">
				<?php if ( ! empty( $service_cta_btn1 ) ) : ?>
					<a href="<?php echo $inquire_url; ?>" class="btn-pill btn-pill-primary">
						<span><?php echo esc_html( $service_cta_btn1 ); ?></span>
						<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
					</a>
				<?php endif; ?>
				<a href="<?php echo esc_url( $services_url ); ?>" class="btn-pill btn-pill-outline text-white border-white/30">
					<span><?php esc_html_e( 'Back to Services Overview', 'kataskevi' ); ?></span>
				</a>
			</div>
		</div>
	</section>

</main>

<?php
get_footer();
