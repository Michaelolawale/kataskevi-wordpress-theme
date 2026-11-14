<?php
/**
 * The template for displaying all single pages
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
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-single-article' ); ?>>
			<section class="section-page-hero">
				<div class="container-editorial">
					<p class="eyebrow"><?php echo esc_html( kataskevi_get_brand_name() ); ?></p>
					<h1 class="page-hero-title"><?php the_title(); ?></h1>
					<?php if ( has_excerpt() ) : ?>
						<p class="page-hero-desc"><?php echo esc_html( get_the_excerpt() ); ?></p>
					<?php endif; ?>
				</div>
			</section>

			<div class="container-editorial py-16">
				<div class="entry-content" style="max-width: 48rem; font-size: 1.0625rem; line-height: 1.75; color: var(--foreground);">
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
			</div>
		</article>
		<?php
	endwhile;
	?>
</main>

<?php
get_footer();
