<?php
/**
 * Template part for displaying posts in post loops
 *
 * Adheres strictly to the WordPress PHP Coding Standards (WPCS).
 *
 * @package Kataskevi
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="post-card__thumb">
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'kataskevi-card' ); ?>
			</a>
		</div>
	<?php endif; ?>

	<div class="post-card__body">
		<div class="post-card__meta">
			<span class="meta-date"><?php echo esc_html( get_the_date() ); ?></span>
			<?php if ( has_category() ) : ?>
				<span class="meta-dot">&bull;</span>
				<span class="meta-cat"><?php the_category( ', ' ); ?></span>
			<?php endif; ?>
		</div>

		<h2 class="post-card__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h2>

		<div class="post-card__excerpt">
			<?php the_excerpt(); ?>
		</div>

		<div class="post-card__footer">
			<a href="<?php the_permalink(); ?>" class="read-more-link">
				<span><?php esc_html_e( 'Read More', 'kataskevi' ); ?></span>
				<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
			</a>
		</div>
	</div>
</article>
