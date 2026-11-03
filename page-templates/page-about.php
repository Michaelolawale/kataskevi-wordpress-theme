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

$who_btn = kataskevi_get_mod( 'kataskevi_about_who_btn', 'Discuss your facility' );
?>

<main id="primary" class="site-main">

	<!-- HERO -->
	<section class="section-page-hero">
		<div class="container-editorial">
			<p class="eyebrow"><?php echo esc_html( $about_eyebrow ); ?></p>
			<h1 class="page-hero-title">
				<?php echo wp_kses_post( $about_title ); ?>
			</h1>
			<p class="page-hero-desc">
				<?php echo esc_html( $about_desc ); ?>
			</p>
		</div>
	</section>

	<!-- FULL-WIDTH MEDIA -->
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

	<!-- WHY WE EXIST -->
	<section class="section-why-we-exist">
		<div class="container-editorial py-20">
			<div class="why-split-grid">
				<div>
					<p class="eyebrow"><?php echo esc_html( $why_eyebrow ); ?></p>
					<h2 class="section-title"><?php echo esc_html( $why_title ); ?></h2>
				</div>
				<div class="why-copy-column">
					<?php for ( $p = 1; $p <= 4; $p++ ) : ?>
						<?php
						$default_p = '';
						if ( 1 === $p ) {
							$default_p = 'Modern facilities run on interdependent systems — suppression, detection, security, access, connectivity. When any one of them fails, everything downstream is at risk.';
						} elseif ( 2 === $p ) {
							$default_p = 'Kataskevi was established to close that gap: a single engineering partner that surveys your site, designs the specification, supplies the equipment, installs it professionally and keeps it maintained for the life of the system.';
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
			<p class="eyebrow"><?php esc_html_e( 'Who we serve', 'kataskevi' ); ?></p>
			<h2 class="section-title"><?php esc_html_e( 'Different industries, one standard', 'kataskevi' ); ?></h2>
			<p class="section-lead-muted mt-2"><?php esc_html_e( 'Systems that work when they\'re needed most.', 'kataskevi' ); ?></p>

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

			<div class="who-we-serve-action mt-12">
				<a href="<?php echo esc_url( $contact_url ); ?>" class="btn-pill btn-pill-primary">
					<span><?php echo esc_html( $who_btn ); ?></span>
					<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
				</a>
			</div>
		</div>
	</section>

</main>

<?php
get_footer();
