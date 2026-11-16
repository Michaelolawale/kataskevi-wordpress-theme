<?php
/**
 * The template for displaying archive pages
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
?>

<?php
$archive_eyebrow = esc_html__( 'Delivered Systems', 'kataskevi' );
if ( is_post_type_archive( 'project' ) ) {
	$archive_eyebrow = esc_html__( 'Case Studies & Delivered Systems', 'kataskevi' );
} elseif ( is_post_type_archive( 'service' ) ) {
	$archive_eyebrow = esc_html__( 'Capabilities & Solutions', 'kataskevi' );
} elseif ( is_category() ) {
	$archive_eyebrow = esc_html__( 'Topic', 'kataskevi' );
} elseif ( is_tag() ) {
	$archive_eyebrow = esc_html__( 'Tag', 'kataskevi' );
} elseif ( is_tax() ) {
	$archive_eyebrow = esc_html__( 'Industry Sector', 'kataskevi' );
} else {
	$archive_eyebrow = esc_html__( 'Case Studies', 'kataskevi' );
}
?>

<main id="primary" class="site-main">
	<section class="section-page-hero">
		<div class="container-editorial">
			<p class="eyebrow"><?php echo esc_html( $archive_eyebrow ); ?></p>
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
