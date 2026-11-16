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
	<?php
	$post_type = get_post_type();
	$thumb_src = '';
	if ( has_post_thumbnail() ) {
		$thumb_src = get_the_post_thumbnail_url( get_the_ID(), 'kataskevi-card' );
	} elseif ( 'project' === $post_type ) {
		$proj_data = kataskevi_get_project_by_slug( get_post_field( 'post_name', get_the_ID() ) );
		$thumb_src = ! empty( $proj_data['image'] ) ? $proj_data['image'] : get_template_directory_uri() . '/assets/images/projects/datacenter.jpg';
	} else {
		$thumb_src = get_template_directory_uri() . '/assets/images/hero.jpg';
	}
	?>
	<div class="post-card__thumb">
		<a href="<?php the_permalink(); ?>">
			<img src="<?php echo esc_url( $thumb_src ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy" />
		</a>
	</div>

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
