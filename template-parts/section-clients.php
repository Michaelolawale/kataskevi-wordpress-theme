<?php
/**
 * Template part for displaying the Our Clients dual infinite-scrolling marquee
 *
 * Adheres strictly to the WordPress PHP Coding Standards (WPCS).
 * Features dual opposite-direction marquee rows (Row 1: left-scrolling, Row 2: right-scrolling).
 * Seamless infinite loop with pause-on-hover and edge fade gradients.
 *
 * @package Kataskevi
 * @version 2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$clients = kataskevi_get_clients();

if ( empty( $clients ) || ( empty( $clients['row_1'] ) && empty( $clients['row_2'] ) ) ) {
	return;
}

// Customizer Theme Options.
$brand_name      = kataskevi_get_brand_name();
$clients_eyebrow = kataskevi_get_mod( 'kataskevi_clients_eyebrow', 'Our Clients' );
$clients_title   = kataskevi_get_mod( 'kataskevi_clients_title', 'Trusted by industry leaders across Nigeria' );
$clients_desc    = kataskevi_get_mod(
	'kataskevi_clients_desc',
	sprintf(
		/* translators: %s: Company / Brand Name */
		esc_html__( 'From Tier III data centers and telecommunications networks to corporate financial institutions and energy complexes, Nigeria\'s leading enterprises rely on %s for mission-critical infrastructure.', 'kataskevi' ),
		$brand_name
	)
);
?>

<section id="clients" class="section-clients" aria-label="<?php esc_attr_e( 'Our Enterprise Clients', 'kataskevi' ); ?>">
	<div class="container-editorial">
		<div class="clients-header">
			<?php if ( ! empty( $clients_eyebrow ) ) : ?>
				<p class="eyebrow"><?php echo esc_html( $clients_eyebrow ); ?></p>
			<?php endif; ?>

			<?php if ( ! empty( $clients_title ) ) : ?>
				<h2 class="section-title"><?php echo esc_html( $clients_title ); ?></h2>
			<?php endif; ?>

			<?php if ( ! empty( $clients_desc ) ) : ?>
				<p class="section-lead-muted">
					<?php echo esc_html( $clients_desc ); ?>
				</p>
			<?php endif; ?>
		</div>
	</div>

	<div class="clients-marquee-wrapper" role="region" aria-label="<?php esc_attr_e( 'Clients marquee carousel', 'kataskevi' ); ?>">
		<?php if ( ! empty( $clients['row_1'] ) ) : ?>
			<!-- ROW 1: SCROLL LEFT -->
			<div class="clients-marquee-row" aria-label="<?php esc_attr_e( 'Enterprise clients row one', 'kataskevi' ); ?>">
				<div class="clients-marquee-track track-scroll-left">
					<!-- Primary Group -->
					<div class="clients-marquee-group">
						<?php foreach ( $clients['row_1'] as $client ) : ?>
							<div class="client-logo-card">
								<div class="client-logo-mark" aria-hidden="true">
									<?php echo kataskevi_render_icon( $client['icon'], 'icon icon-sm text-primary' ); ?>
								</div>
								<div class="client-logo-meta">
									<h3 class="client-name"><?php echo esc_html( $client['name'] ); ?></h3>
									<span class="client-sector"><?php echo esc_html( $client['sector'] ); ?></span>
								</div>
							</div>
						<?php endforeach; ?>
					</div>

					<!-- Duplicate Group for Seamless Infinite Loop -->
					<div class="clients-marquee-group" aria-hidden="true">
						<?php foreach ( $clients['row_1'] as $client ) : ?>
							<div class="client-logo-card">
								<div class="client-logo-mark" aria-hidden="true">
									<?php echo kataskevi_render_icon( $client['icon'], 'icon icon-sm text-primary' ); ?>
								</div>
								<div class="client-logo-meta">
									<span class="client-name"><?php echo esc_html( $client['name'] ); ?></span>
									<span class="client-sector"><?php echo esc_html( $client['sector'] ); ?></span>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $clients['row_2'] ) ) : ?>
			<!-- ROW 2: SCROLL RIGHT -->
			<div class="clients-marquee-row" aria-label="<?php esc_attr_e( 'Enterprise clients row two', 'kataskevi' ); ?>">
				<div class="clients-marquee-track track-scroll-right">
					<!-- Primary Group -->
					<div class="clients-marquee-group">
						<?php foreach ( $clients['row_2'] as $client ) : ?>
							<div class="client-logo-card">
								<div class="client-logo-mark" aria-hidden="true">
									<?php echo kataskevi_render_icon( $client['icon'], 'icon icon-sm text-primary' ); ?>
								</div>
								<div class="client-logo-meta">
									<h3 class="client-name"><?php echo esc_html( $client['name'] ); ?></h3>
									<span class="client-sector"><?php echo esc_html( $client['sector'] ); ?></span>
								</div>
							</div>
						<?php endforeach; ?>
					</div>

					<!-- Duplicate Group for Seamless Infinite Loop -->
					<div class="clients-marquee-group" aria-hidden="true">
						<?php foreach ( $clients['row_2'] as $client ) : ?>
							<div class="client-logo-card">
								<div class="client-logo-mark" aria-hidden="true">
									<?php echo kataskevi_render_icon( $client['icon'], 'icon icon-sm text-primary' ); ?>
								</div>
								<div class="client-logo-meta">
									<span class="client-name"><?php echo esc_html( $client['name'] ); ?></span>
									<span class="client-sector"><?php echo esc_html( $client['sector'] ); ?></span>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
