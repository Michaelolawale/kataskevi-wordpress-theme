<?php
/**
 * Template Name: About Us
 * Redesigned UI matching ember-shield-systems.lovable.app
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
?>

<main id="primary" class="site-main">

	<!-- HERO -->
	<section class="section-page-hero">
		<div class="container-editorial">
			<p class="eyebrow">About</p>
			<h1 class="page-hero-title">
				One partner for the systems <em class="text-primary italic">modern facilities depend on.</em>
			</h1>
			<p class="page-hero-desc">
				An Engineering, Safety, Information Technology and Telecommunications support services company — established to provide complete service in the design, supply, installation and maintenance of the systems modern facilities run on.
			</p>
		</div>
	</section>

	<!-- FULL-WIDTH MEDIA -->
	<section class="section-about-fullmedia">
		<img 
			src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/about.jpg' ); ?>" 
			alt="Engineering drawings and system schematics on a bright design workspace" 
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
					<p class="eyebrow">Why we exist</p>
					<h2 class="section-title">Closing the gaps between systems</h2>
				</div>
				<div class="why-copy-column">
					<p>
						Modern facilities run on interdependent systems — suppression, detection, security, access, connectivity. When any one of them fails, everything downstream is at risk.
					</p>
					<p>
						Kataskevi was established to close that gap: a single engineering partner that surveys your site, designs the specification, supplies the equipment, installs it professionally and keeps it maintained for the life of the system.
					</p>
					<p>
						We service the energy sector, oil and gas, hospitality, government, telecommunications, financial institutions, education institutions and manufacturing companies — tailoring every system to the environment it protects.
					</p>
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
			<p class="eyebrow">Who we serve</p>
			<h2 class="section-title">Different industries, one standard</h2>
			<p class="section-lead-muted mt-2">Systems that work when they're needed most.</p>

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

			<div class="mt-12">
				<a href="<?php echo esc_url( $contact_url ); ?>" class="btn-pill btn-pill-primary">
					<span>Discuss your facility</span>
					<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
				</a>
			</div>
		</div>
	</section>

</main>

<?php
get_footer();
