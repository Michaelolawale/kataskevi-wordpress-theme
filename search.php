<?php
/**
 * The template for displaying search results pages
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
			<p class="eyebrow"><?php esc_html_e( 'Search Results', 'kataskevi' ); ?></p>
			<h1 class="page-hero-title">
				Results for: <em class="text-primary italic">&ldquo;<?php echo esc_html( get_search_query() ); ?>&rdquo;</em>
			</h1>
		</div>
	</section>

	<div class="container-editorial py-16">
		<?php if ( have_posts() ) : ?>
			<div class="posts-grid" style="display: grid; gap: 2rem; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content', 'search' );
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
			<div class="no-results" style="max-width: 36rem;">
				<h2 class="text-2xl mb-4" style="font-family: var(--font-display);">No results found</h2>
				<p class="text-muted-foreground mb-6" style="color: var(--muted-foreground);">
					We could not find any matching articles or documentation. Try searching with different keywords.
				</p>
				<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" style="display: flex; gap: 0.5rem;">
					<input type="search" class="input-editorial" placeholder="Search Kataskevi..." value="<?php echo esc_attr( get_search_query() ); ?>" name="s" required>
					<button type="submit" class="btn-pill btn-pill-primary">Search</button>
				</form>
			</div>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();

