<?php
/**
 * The template for displaying archive pages
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
			<p class="eyebrow"><?php esc_html_e( 'Archive', 'kataskevi' ); ?></p>
			<h1 class="page-hero-title"><?php the_archive_title(); ?></h1>
			<?php if ( get_the_archive_description() ) : ?>
				<div class="page-hero-desc">
					<?php the_archive_description(); ?>
				</div>
			<?php endif; ?>
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
						'mid_size'  => 2,
						'prev_text' => '&larr; Previous',
						'next_text' => 'Next &rarr;',
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
