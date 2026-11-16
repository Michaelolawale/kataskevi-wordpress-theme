<?php
/**
 * Template Name: About Us
 * Description: The full overview of Kataskevi history, engineering philosophy, and sectors.
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

$contact_url = kataskevi_get_page_url( 'contact', 'Contact' );

$pillars = kataskevi_get_pillars();
$sectors = kataskevi_get_sectors();

// Customizer Theme Options.
$about_eyebrow = kataskevi_get_mod( 'kataskevi_about_hero_eyebrow', 'About' );
$about_title   = kataskevi_get_mod( 'kataskevi_about_hero_title', 'One partner for the systems <em class="text-primary italic">modern facilities depend on.</em>' );
$about_desc    = kataskevi_get_mod( 'kataskevi_about_hero_desc', 'An Engineering, Safety, Information Technology and Telecommunications support services company — established to provide complete service in the design, supply, installation and maintenance of the systems modern facilities run on.' );
$about_image   = kataskevi_get_mod( 'kataskevi_about_image', get_template_directory_uri() . '/assets/images/about.jpg' );

$why_eyebrow = kataskevi_get_mod( 'kataskevi_about_why_eyebrow', 'Why we exist' );
$why_title   = kataskevi_get_mod( 'kataskevi_about_why_title', 'Closing the gaps between systems' );

$who_eyebrow = kataskevi_get_mod( 'kataskevi_about_who_eyebrow', 'Who we serve' );
$who_title   = kataskevi_get_mod( 'kataskevi_about_who_title', 'Different industries, one standard' );
$who_desc    = kataskevi_get_mod( 'kataskevi_about_who_desc', 'Systems that work when they\'re needed most.' );
$who_btn     = kataskevi_get_mod( 'kataskevi_about_who_btn', 'Discuss your facility' );

// Accordion Customizer Descriptions.
$vision_desc  = kataskevi_get_mod( 'kataskevi_about_vision_desc', 'To be the premier engineering, safety, and infrastructure solutions partner across West Africa — recognized for technical distinction, uncompromised safety integrity, and long-term client value.' );
$mission_desc = kataskevi_get_mod( 'kataskevi_about_mission_desc', 'To protect lives, mission-critical assets, and operational continuity by delivering world-class engineering, fire suppression, electronic security, and IT/telecommunications services through certified technical expertise and responsive lifecycle maintenance.' );
$hse_desc     = kataskevi_get_mod( 'kataskevi_about_hse_desc', 'We operate under an uncompromising "Zero Harm, Zero Incident" policy. Every project engagement adheres strictly to OSHA, NFPA, and ISO-aligned protocols to ensure the absolute safety of our workforce, client infrastructure, and the surrounding environment.' );
$support_desc = kataskevi_get_mod( 'kataskevi_about_support_desc', 'Our dedicated technical support desk and mobile field engineers provide 24/7/365 coverage, offering rapid emergency dispatch, proactive SLA monitoring, and scheduled preventive maintenance routines to guarantee zero facility downtime.' );
$staff_desc   = kataskevi_get_mod( 'kataskevi_about_staff_desc', 'Our multidisciplinary engineering roster consists of certified fire protection specialists, OEM-trained instrumentation technicians, certified fiber optic engineers, and licensed industrial safety consultants with decades of combined hands-on industrial field experience.' );

$accordion_items = array(
	array(
		'id'      => 'vision',
		'title'   => esc_html__( 'Vision', 'kataskevi' ),
		'desc'    => $vision_desc,
		'is_open' => false,
	),
	array(
		'id'      => 'mission',
		'title'   => esc_html__( 'Mission', 'kataskevi' ),
		'desc'    => $mission_desc,
		'is_open' => false,
	),
	array(
		'id'      => 'hse-policy',
		'title'   => esc_html__( 'HSE Policy', 'kataskevi' ),
		'desc'    => $hse_desc,
		'is_open' => false,
	),
	array(
		'id'      => 'customer-support',
		'title'   => esc_html__( 'Experienced Customer Support', 'kataskevi' ),
		'desc'    => $support_desc,
		'is_open' => false,
	),
	array(
		'id'      => 'technical-staff',
		'title'   => esc_html__( 'Professional Technical Staff', 'kataskevi' ),
		'desc'    => $staff_desc,
		'is_open' => true,
	),
);
?>

<main id="primary" class="site-main">

	<!-- HERO -->
	<section class="section-page-hero">
		<div class="container-editorial">
			<?php if ( ! empty( $about_eyebrow ) ) : ?>
				<p class="eyebrow"><?php echo esc_html( $about_eyebrow ); ?></p>
			<?php endif; ?>
			<?php if ( ! empty( $about_title ) ) : ?>
				<h1 class="page-hero-title">
					<?php echo wp_kses_post( $about_title ); ?>
				</h1>
			<?php endif; ?>
			<?php if ( ! empty( $about_desc ) ) : ?>
				<p class="page-hero-desc">
					<?php echo esc_html( $about_desc ); ?>
				</p>
			<?php endif; ?>
		</div>
	</section>

	<!-- FULL-WIDTH MEDIA -->
	<?php if ( ! empty( $about_image ) ) : ?>
		<section class="section-about-fullmedia">
			<img 
				src="<?php echo esc_url( $about_image ); ?>" 
				alt="<?php esc_attr_e( 'Engineering drawings and system schematics on a bright design workspace', 'kataskevi' ); ?>" 
				width="1400" 
				height="1000" 
				class="about-fullmedia-img"
				loading="lazy"
			>
		</section>
	<?php endif; ?>

	<!-- WHY WE EXIST -->
	<section class="section-why-we-exist">
		<div class="container-editorial py-20">
			<div class="why-split-grid">
				<div>
					<?php if ( ! empty( $why_eyebrow ) ) : ?>
						<p class="eyebrow"><?php echo esc_html( $why_eyebrow ); ?></p>
					<?php endif; ?>
					<?php if ( ! empty( $why_title ) ) : ?>
						<h2 class="section-title"><?php echo esc_html( $why_title ); ?></h2>
					<?php endif; ?>
				</div>
				<div class="why-copy-column">
					<?php
					$brand_name = kataskevi_get_brand_name();
					for ( $p = 1; $p <= 4; $p++ ) :
					?>
						<?php
						$default_p = '';
						if ( 1 === $p ) {
							$default_p = 'Modern facilities run on interdependent systems — suppression, detection, security, access, connectivity. When any one of them fails, everything downstream is at risk.';
						} elseif ( 2 === $p ) {
							$default_p = sprintf(
								/* translators: %s: Company / Brand Name */
								esc_html__( '%s was established to close that gap: a single engineering partner that surveys your site, designs the specification, supplies the equipment, installs it professionally and keeps it maintained for the life of the system.', 'kataskevi' ),
								$brand_name
							);
						} elseif ( 3 === $p ) {
							$default_p = 'We service the energy sector, oil and gas, hospitality, government, telecommunications, financial institutions, education institutions and manufacturing companies — tailoring every system to the environment it protects.';
						} else {
							$default_p = 'Whether designing a clean-agent suppression system for a colocation suite, securing a corporate headquarters, or outfitting a marine terminal with certified safety gear, we bring the same engineering rigor to every engagement.';
						}
						$paragraph_text = kataskevi_get_mod( 'kataskevi_about_why_p' . $p, $default_p );
						if ( ! empty( $paragraph_text ) ) :
						?>
							<p><?php echo esc_html( $paragraph_text ); ?></p>
						<?php endif; ?>
					<?php endfor; ?>
				</div>
			</div>
		</div>
	</section>

	<!-- CORPORATE VALUES & POLICIES ACCORDION -->
	<section class="section-about-accordion py-16 border-t border-border">
		<div class="container-editorial">
			<div class="why-split-grid">
				<div>
					<p class="eyebrow"><?php esc_html_e( 'Corporate Framework', 'kataskevi' ); ?></p>
					<h2 class="section-title"><?php esc_html_e( 'Vision, Mission & Operational Standards', 'kataskevi' ); ?></h2>
					<p class="text-muted-foreground mt-4" style="line-height: 1.7;">
						<?php esc_html_e( 'The foundational principles, certified safety protocols, and technical competencies guiding every engineering engagement across modern facilities.', 'kataskevi' ); ?>
					</p>
				</div>
				<div>
					<div class="about-accordion-card" role="region" aria-label="<?php esc_attr_e( 'Company Corporate Framework and Policies', 'kataskevi' ); ?>">
						<?php foreach ( $accordion_items as $item ) : ?>
							<div class="about-accordion-item <?php echo $item['is_open'] ? 'is-open' : ''; ?>" id="accordion-item-<?php echo esc_attr( $item['id'] ); ?>">
								<button 
									type="button" 
									class="about-accordion-trigger" 
									aria-expanded="<?php echo $item['is_open'] ? 'true' : 'false'; ?>"
									aria-controls="accordion-body-<?php echo esc_attr( $item['id'] ); ?>"
								>
									<span class="about-accordion-icon" aria-hidden="true">
										<?php echo $item['is_open'] ? '−' : '+'; ?>
									</span>
									<span class="about-accordion-label"><?php echo esc_html( $item['title'] ); ?></span>
								</button>
								<div 
									id="accordion-body-<?php echo esc_attr( $item['id'] ); ?>" 
									class="about-accordion-body" 
									role="region"
									aria-labelledby="accordion-item-<?php echo esc_attr( $item['id'] ); ?>"
								>
									<div class="about-accordion-content">
										<p><?php echo esc_html( $item['desc'] ); ?></p>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- 4 PILLARS (2x2 GRID WITH BORDERS) -->
	<section class="section-pillars border-y border-border">
		<div class="container-editorial">
			<div class="pillars-editorial-grid">
				<?php foreach ( $pillars as $pillar ) : ?>
					<div class="pillar-editorial-card">
						<?php echo kataskevi_render_icon( $pillar['icon'], 'icon icon-md text-primary' ); ?>
						<h3 class="pillar-editorial-title"><?php echo esc_html( $pillar['title'] ); ?></h3>
						<p class="pillar-editorial-desc"><?php echo esc_html( $pillar['desc'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- WHO WE SERVE -->
	<section class="section-who-we-serve">
		<div class="container-editorial py-20">
			<?php if ( ! empty( $who_eyebrow ) ) : ?>
				<p class="eyebrow"><?php echo esc_html( $who_eyebrow ); ?></p>
			<?php endif; ?>
			<?php if ( ! empty( $who_title ) ) : ?>
				<h2 class="section-title"><?php echo esc_html( $who_title ); ?></h2>
			<?php endif; ?>
			<?php if ( ! empty( $who_desc ) ) : ?>
				<p class="section-lead-muted mt-2"><?php echo esc_html( $who_desc ); ?></p>
			<?php endif; ?>

			<div class="sectors-horizontal-list mt-12 border-t border-border">
				<?php foreach ( $sectors as $sector ) : ?>
					<div class="sector-horizontal-row">
						<span class="sector-horizontal-icon">
							<?php echo kataskevi_render_icon( $sector['icon'], 'icon icon-sm text-primary' ); ?>
						</span>
						<h3 class="sector-horizontal-name"><?php echo esc_html( $sector['name'] ); ?></h3>
						<p class="sector-horizontal-desc"><?php echo esc_html( $sector['desc'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>

			<?php if ( ! empty( $who_btn ) ) : ?>
				<div class="who-we-serve-action mt-12">
					<a href="<?php echo esc_url( $contact_url ); ?>" class="btn-pill btn-pill-primary">
						<span><?php echo esc_html( $who_btn ); ?></span>
						<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
					</a>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<style>
		/* About Accordion Component Styles */
		.section-about-accordion {
			padding-top: 4rem;
			padding-bottom: 4rem;
		}
		.about-accordion-card {
			background: #ffffff;
			border: 1px solid #e2e8f0;
			border-radius: 8px;
			overflow: hidden;
			box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
		}
		.about-accordion-item {
			border-bottom: 1px solid #edf2f7;
			transition: background-color 0.15s ease;
		}
		.about-accordion-item:last-child {
			border-bottom: none;
		}
		.about-accordion-trigger {
			width: 100%;
			display: flex;
			align-items: center;
			gap: 0.875rem;
			padding: 1.15rem 1.35rem;
			background: transparent;
			border: none;
			cursor: pointer;
			text-align: left;
			font-family: inherit;
			font-size: 1.05rem;
			font-weight: 500;
			color: #334155;
			transition: background-color 0.15s ease, color 0.15s ease;
		}
		.about-accordion-trigger:hover {
			background-color: #f8fafc;
			color: #0f172a;
		}
		.about-accordion-item.is-open {
			background-color: #fafbfc;
		}
		.about-accordion-item.is-open .about-accordion-trigger {
			background-color: #f1f5f9;
			color: #0f172a;
			font-weight: 600;
		}
		.about-accordion-icon {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			width: 18px;
			height: 18px;
			font-size: 1.35rem;
			font-weight: 400;
			line-height: 1;
			color: #475569;
			user-select: none;
			flex-shrink: 0;
		}
		.about-accordion-item.is-open .about-accordion-icon {
			color: #0284c7;
			font-weight: 700;
		}
		.about-accordion-label {
			flex: 1;
			letter-spacing: -0.01em;
		}
		.about-accordion-body {
			max-height: 0;
			overflow: hidden;
			transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1);
			background-color: #ffffff;
		}
		.about-accordion-item.is-open .about-accordion-body {
			max-height: 300px;
		}
		.about-accordion-content {
			padding: 0.75rem 1.35rem 1.25rem 3rem;
			font-size: 0.9375rem;
			line-height: 1.65;
			color: #475569;
		}
	</style>

	<script>
		document.addEventListener('DOMContentLoaded', function () {
			var triggers = document.querySelectorAll('.about-accordion-trigger');
			triggers.forEach(function (trigger) {
				trigger.addEventListener('click', function () {
					var item = this.closest('.about-accordion-item');
					if (!item) return;
					var card = item.closest('.about-accordion-card');
					var isOpen = item.classList.contains('is-open');

					if (card) {
						card.querySelectorAll('.about-accordion-item').forEach(function (sib) {
							if (sib !== item && sib.classList.contains('is-open')) {
								sib.classList.remove('is-open');
								var sibBtn = sib.querySelector('.about-accordion-trigger');
								if (sibBtn) sibBtn.setAttribute('aria-expanded', 'false');
								var sibIcon = sib.querySelector('.about-accordion-icon');
								if (sibIcon) sibIcon.textContent = '+';
							}
						});
					}

					if (isOpen) {
						item.classList.remove('is-open');
						this.setAttribute('aria-expanded', 'false');
						var icon = item.querySelector('.about-accordion-icon');
						if (icon) icon.textContent = '+';
					} else {
						item.classList.add('is-open');
						this.setAttribute('aria-expanded', 'true');
						var icon = item.querySelector('.about-accordion-icon');
						if (icon) icon.textContent = '−';
					}
				});
			});
		});
	</script>

</main>

<?php
get_footer();
