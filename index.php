<?php
/**
 * The main template file
 *
 * @package Kataskevi
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="primary" class="site-main">
	<section class="section-page-hero">
		<div class="container-editorial">
			<p class="eyebrow">Updates &amp; Insights</p>
			<h1 class="page-hero-title">
				<?php
				if ( is_home() && ! is_front_page() ) {
					single_post_title();
				} else {
					esc_html_e( 'Engineering & Safety Articles', 'kataskevi' );
				}
				?>
			</h1>
			<p class="page-hero-desc">
				Industry standards, fire safety protocols, and mission-critical infrastructure insights from Kataskevi.
			</p>
		</div>
	</section>

	<div class="container-editorial py-16">
		<?php if ( have_posts() ) : ?>
			<div class="posts-grid" style="display: grid; gap: 2rem; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content', get_post_format() );
				endwhile;
				?>
			</div>

			<div class="pagination-wrapper" style="margin-top: 3.5rem; text-align: center;">
				<?php
				the_posts_pagination(
					array(
						'prev_text' => '&larr; ' . esc_html__( 'Previous', 'kataskevi' ),
						'next_text' => esc_html__( 'Next', 'kataskevi' ) . ' &rarr;',
					)
				);
				?>
			</div>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content', 'none' ); ?>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
