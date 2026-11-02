<?php
/**
 * Template part for displaying posts in post loops
 *
 * @package Kataskevi
 * @version 1.0.0
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
			<span class="meta-date"><?php echo get_the_date(); ?></span>
			<?php if ( has_category() ) : ?>
				<span class="meta-dot">•</span>
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
				<span>Read Case Study</span>
				<?php echo kataskevi_render_icon( 'arrow-right', 'icon icon-xs' ); ?>
			</a>
		</div>
	</div>
</article>
