<?php
/**
 * Kataskevi Child Theme functions and definitions.
 *
 * Adheres strictly to the WordPress PHP Coding Standards (WPCS).
 *
 * @package Kataskevi_Child
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue parent and child theme styles cleanly.
 */
function kataskevi_child_enqueue_styles() {
	// Parent theme stylesheet
	wp_enqueue_style(
		'kataskevi-parent-style',
		get_template_directory_uri() . '/style.css',
		array( 'kataskevi-main-style' ),
		wp_get_theme( 'kataskevi' )->get( 'Version' )
	);

	// Child theme stylesheet
	wp_enqueue_style(
		'kataskevi-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'kataskevi-parent-style' ),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'kataskevi_child_enqueue_styles', 20 );

/*
 * Add custom functions, hooks, or filters below this line.
 */
