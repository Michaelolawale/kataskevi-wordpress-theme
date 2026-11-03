<?php
/**
 * The template for displaying all single posts
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

<main id="primary" class="site-main">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-post-article' ); ?>>
			<section class="section-page-hero">
				<div class="container-editorial">
					<p class="eyebrow"><?php echo esc_html( get_the_date() ); ?> &middot; <?php the_author(); ?></p>
					<h1 class="page-hero-title"><?php the_title(); ?></h1>
				</div>
			</section>

			<div class="container-editorial py-16">
				<div class="content-area" style="max-width: 48rem; margin: 0 auto;">
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="single-featured-image" style="margin-bottom: 2.5rem; border-radius: var(--radius-md); overflow: hidden; border: 1px solid var(--border);">
							<?php the_post_thumbnail( 'full' ); ?>
						</div>
					<?php endif; ?>

					<div class="entry-content" style="font-size: 1.0625rem; line-height: 1.75; color: var(--foreground);">
						<?php
						the_content();

						wp_link_pages(
							array(
								'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'kataskevi' ),
								'after'  => '</div>',
							)
						);
						?>
					</div>

					<footer class="entry-footer" style="margin-top: 3.5rem; padding-top: 2rem; border-top: 1px solid var(--border);">
						<div class="post-navigation-links" style="display: flex; justify-content: space-between; font-size: 0.875rem; color: var(--primary);">
							<div class="nav-prev"><?php previous_post_link( '&larr; %link' ); ?></div>
							<div class="nav-next"><?php next_post_link( '%link &rarr;' ); ?></div>
						</div>
					</footer>

					<?php
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
					?>
				</div>
			</div>
		</article>
		<?php
	endwhile;
	?>
</main>

<?php
get_footer();
