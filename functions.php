<?php
/**
 * Kataskevi Theme Functions and Definitions
 *
 * @package Kataskevi
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'KATASKEVI_VERSION', '2.2.4' );
define( 'KATASKEVI_DIR', get_template_directory() );
define( 'KATASKEVI_URI', get_template_directory_uri() );

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function kataskevi_theme_setup() {
	// Make theme available for translation.
	load_theme_textdomain( 'kataskevi', KATASKEVI_DIR . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails on posts and pages.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 800, 500, true );
	add_image_size( 'kataskevi-hero', 1920, 900, true );
	add_image_size( 'kataskevi-card', 640, 420, true );
	add_image_size( 'kataskevi-square', 500, 500, true );

	// Register Navigation Menus.
	register_nav_menus(
		array(
			'primary'   => esc_html__( 'Primary Navigation', 'kataskevi' ),
			'services'  => esc_html__( 'Services Menu', 'kataskevi' ),
			'footer'    => esc_html__( 'Footer Navigation', 'kataskevi' ),
		)
	);

	// Switch default core markup to output valid HTML5.
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
			'navigation-widgets',
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Custom logo support (Standard WordPress Custom Logo).
	add_theme_support(
		'custom-logo',
		array(
			'height'               => 80,
			'width'                => 280,
			'flex-width'           => true,
			'flex-height'          => true,
			'unlink-homepage-logo' => false,
		)
	);

	// Gutenberg / Block editor supports.
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );
}
add_action( 'after_setup_theme', 'kataskevi_theme_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 */
function kataskevi_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'kataskevi_content_width', 1240 );
}
add_action( 'after_setup_theme', 'kataskevi_content_width', 0 );

/**
 * Register Custom Post Type: Project ('project') with slug 'works'
 * and Custom Taxonomy: Project Sector ('project_sector')
 */
function kataskevi_register_project_cpt() {
	$labels = array(
		'name'                  => _x( 'Projects', 'Post type general name', 'kataskevi' ),
		'singular_name'         => _x( 'Project', 'Post type singular name', 'kataskevi' ),
		'menu_name'             => _x( 'Projects', 'Admin Menu text', 'kataskevi' ),
		'name_admin_bar'        => _x( 'Project', 'Add New on Toolbar', 'kataskevi' ),
		'add_new'               => __( 'Add New', 'kataskevi' ),
		'add_new_item'          => __( 'Add New Project', 'kataskevi' ),
		'new_item'              => __( 'New Project', 'kataskevi' ),
		'edit_item'             => __( 'Edit Project', 'kataskevi' ),
		'view_item'             => __( 'View Project', 'kataskevi' ),
		'all_items'             => __( 'All Projects', 'kataskevi' ),
		'search_items'          => __( 'Search Projects', 'kataskevi' ),
		'parent_item_colon'     => __( 'Parent Projects:', 'kataskevi' ),
		'not_found'             => __( 'No projects found.', 'kataskevi' ),
		'not_found_in_trash'    => __( 'No projects found in Trash.', 'kataskevi' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'works', 'with_front' => false ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 20,
		'menu_icon'          => 'dashicons-portfolio',
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
		'show_in_rest'       => true,
	);

	register_post_type( 'project', $args );

	// Register Custom Post Type: Service
	$service_labels = array(
		'name'               => _x( 'Services', 'post type general name', 'kataskevi' ),
		'singular_name'      => _x( 'Service', 'post type singular name', 'kataskevi' ),
		'menu_name'          => _x( 'Services', 'admin menu', 'kataskevi' ),
		'name_admin_bar'     => _x( 'Service', 'add new on admin bar', 'kataskevi' ),
		'add_new'            => _x( 'Add New', 'service', 'kataskevi' ),
		'add_new_item'       => __( 'Add New Service', 'kataskevi' ),
		'new_item'           => __( 'New Service', 'kataskevi' ),
		'edit_item'          => __( 'Edit Service', 'kataskevi' ),
		'view_item'          => __( 'View Service', 'kataskevi' ),
		'all_items'          => __( 'All Services', 'kataskevi' ),
		'search_items'       => __( 'Search Services', 'kataskevi' ),
		'not_found'          => __( 'No services found.', 'kataskevi' ),
		'not_found_in_trash' => __( 'No services found in Trash.', 'kataskevi' ),
	);

	$service_args = array(
		'labels'             => $service_labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'service', 'with_front' => false ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => 21,
		'menu_icon'          => 'dashicons-hammer',
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
		'show_in_rest'       => true,
	);

	register_post_type( 'service', $service_args );

	// Register Taxonomy: Project Sector
	$tax_labels = array(
		'name'              => _x( 'Sectors', 'taxonomy general name', 'kataskevi' ),
		'singular_name'     => _x( 'Sector', 'taxonomy singular name', 'kataskevi' ),
		'search_items'      => __( 'Search Sectors', 'kataskevi' ),
		'all_items'         => __( 'All Sectors', 'kataskevi' ),
		'parent_item'       => __( 'Parent Sector', 'kataskevi' ),
		'parent_item_colon' => __( 'Parent Sector:', 'kataskevi' ),
		'edit_item'         => __( 'Edit Sector', 'kataskevi' ),
		'update_item'       => __( 'Update Sector', 'kataskevi' ),
		'add_new_item'      => __( 'Add New Sector', 'kataskevi' ),
		'new_item_name'     => __( 'New Sector Name', 'kataskevi' ),
		'menu_name'         => __( 'Sectors', 'kataskevi' ),
	);

	register_taxonomy( 'project_sector', array( 'project' ), array(
		'hierarchical'      => true,
		'labels'            => $tax_labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'project-sector' ),
		'show_in_rest'      => true,
	) );
}
add_action( 'init', 'kataskevi_register_project_cpt' );

/**
 * Helper to get a resilient URL for core pages (Services, Works, About, Contact)
 * Resolves by path, title, or fallback URL.
 */
function kataskevi_get_page_url( $slug, $title = '' ) {
	$page = get_page_by_path( $slug );
	if ( ! $page && ! empty( $title ) ) {
		$page = get_page_by_title( $title );
	}
	if ( $page ) {
		return get_permalink( $page->ID );
	}
	return home_url( '/' . $slug . '/' );
}

/**
 * Safe helper to retrieve the dynamic company / brand name.
 * Uses Theme Customizer option if set, otherwise automatically reflects
 * the target WordPress Site Title (Settings -> General).
 *
 * @return string
 */
function kataskevi_get_brand_name() {
	$brand = get_theme_mod( 'kataskevi_company_name', '' );
	if ( ! empty( $brand ) ) {
		return $brand;
	}
	$site_title = get_bloginfo( 'name' );
	return ! empty( $site_title ) ? $site_title : esc_html__( 'Our Company', 'kataskevi' );
}

/**
 * Automatically create and integrate default pages (Services, Our Works, About, Contact)
 * if they do not exist yet, assign their templates, and build primary menu.
 */
function kataskevi_setup_default_pages() {
	$pages_to_create = array(
		'services' => array(
			'title'    => 'Services',
			'template' => 'page-templates/page-services.php',
			'content'  => '',
		),
		'works' => array(
			'title'    => 'Our Works',
			'template' => 'page-templates/page-works.php',
			'content'  => '',
		),
		'about' => array(
			'title'    => 'About',
			'template' => 'page-templates/page-about.php',
			'content'  => '',
		),
		'contact' => array(
			'title'    => 'Contact',
			'template' => 'page-templates/page-contact.php',
			'content'  => '',
		),
	);

	$created_pages = array();

	foreach ( $pages_to_create as $slug => $data ) {
		$page = get_page_by_path( $slug );
		if ( ! $page ) {
			$page = get_page_by_title( $data['title'] );
		}

		if ( ! $page ) {
			$page_id = wp_insert_post(
				array(
					'post_title'     => $data['title'],
					'post_name'      => $slug,
					'post_status'    => 'publish',
					'post_type'      => 'page',
					'comment_status' => 'closed',
					'post_content'   => $data['content'],
				)
			);
			if ( $page_id && ! is_wp_error( $page_id ) ) {
				update_post_meta( $page_id, '_wp_page_template', $data['template'] );
				$created_pages[ $slug ] = $page_id;
			}
		} else {
			// Ensure template is explicitly assigned
			$current_tpl = get_post_meta( $page->ID, '_wp_page_template', true );
			if ( empty( $current_tpl ) || 'default' === $current_tpl ) {
				update_post_meta( $page->ID, '_wp_page_template', $data['template'] );
			}
			$created_pages[ $slug ] = $page->ID;
		}
	}

	// Auto-seed default projects into WordPress CPT if none exist
	kataskevi_seed_default_projects();

	// Auto-create and assign Primary Menu if not set
	$locations = get_theme_mod( 'nav_menu_locations' );
	if ( empty( $locations['primary'] ) ) {
		$menu_name = 'Primary Navigation';
		$menu_obj  = wp_get_nav_menu_object( $menu_name );

		if ( ! $menu_obj ) {
			$menu_id = wp_create_nav_menu( $menu_name );
			if ( $menu_id && ! is_wp_error( $menu_id ) ) {
				// Home link
				wp_update_nav_menu_item( $menu_id, 0, array(
					'menu-item-title'  => __( 'Home', 'kataskevi' ),
					'menu-item-url'    => home_url( '/' ),
					'menu-item-status' => 'publish',
				) );

				// Services, Works, About, Contact links
				foreach ( array( 'services' => 'Services', 'works' => 'Our Works', 'about' => 'About', 'contact' => 'Contact' ) as $s => $t ) {
					$p_id = isset( $created_pages[ $s ] ) ? $created_pages[ $s ] : 0;
					if ( $p_id ) {
						wp_update_nav_menu_item( $menu_id, 0, array(
							'menu-item-title'     => $t,
							'menu-item-object'    => 'page',
							'menu-item-object-id' => $p_id,
							'menu-item-type'      => 'post_type',
							'menu-item-status'    => 'publish',
						) );
					} else {
						wp_update_nav_menu_item( $menu_id, 0, array(
							'menu-item-title'  => $t,
							'menu-item-url'    => home_url( '/' . $s . '/' ),
							'menu-item-status' => 'publish',
						) );
					}
				}

				if ( ! is_array( $locations ) ) {
					$locations = array();
				}
				$locations['primary'] = $menu_id;
				set_theme_mod( 'nav_menu_locations', $locations );
			}
		} else {
			if ( ! is_array( $locations ) ) {
				$locations = array();
			}
			$locations['primary'] = $menu_obj->term_id;
			set_theme_mod( 'nav_menu_locations', $locations );

			// Ensure "Our Works" is always present in the existing menu
			$existing_items = wp_get_nav_menu_items( $menu_obj->term_id );
			$has_works      = false;
			if ( ! empty( $existing_items ) ) {
				foreach ( $existing_items as $item ) {
					if ( false !== stripos( $item->title, 'Works' ) || false !== stripos( $item->url, '/works' ) ) {
						$has_works = true;
						break;
					}
				}
			}
			if ( ! $has_works ) {
				$works_page_id = isset( $created_pages['works'] ) ? $created_pages['works'] : 0;
				if ( $works_page_id ) {
					wp_update_nav_menu_item(
						$menu_obj->term_id,
						0,
						array(
							'menu-item-title'     => __( 'Our Works', 'kataskevi' ),
							'menu-item-object'    => 'page',
							'menu-item-object-id' => $works_page_id,
							'menu-item-type'      => 'post_type',
							'menu-item-status'    => 'publish',
						)
					);
				} else {
					wp_update_nav_menu_item(
						$menu_obj->term_id,
						0,
						array(
							'menu-item-title'  => __( 'Our Works', 'kataskevi' ),
							'menu-item-url'    => home_url( '/works/' ),
							'menu-item-status' => 'publish',
						)
					);
				}
			}
		}
	}
}
add_action( 'init', 'kataskevi_setup_default_pages' );

/**
 * Enqueue scripts and styles.
 */
function kataskevi_scripts_styles() {
	// Google Fonts: Instrument Serif for display/headings, Work Sans for body
	wp_enqueue_style(
		'kataskevi-google-fonts',
		'https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Work+Sans:wght@300;400;500;600;700&display=swap',
		array(),
		null
	);

	// Main Theme Stylesheet
	$css_ver = file_exists( KATASKEVI_DIR . '/assets/css/main.css' ) ? (string) filemtime( KATASKEVI_DIR . '/assets/css/main.css' ) : KATASKEVI_VERSION;
	wp_enqueue_style(
		'kataskevi-main-style',
		KATASKEVI_URI . '/assets/css/main.css',
		array(),
		$css_ver
	);

	// Parent fallback / header style
	wp_enqueue_style(
		'kataskevi-style',
		get_stylesheet_uri(),
		array( 'kataskevi-main-style' ),
		$css_ver
	);

	// Dynamic Theme Customizer Color Palette
	if ( function_exists( 'kataskevi_get_customizer_css' ) ) {
		$custom_colors_css = kataskevi_get_customizer_css();
		if ( ! empty( $custom_colors_css ) ) {
			wp_add_inline_style( 'kataskevi-main-style', $custom_colors_css );
		}
	}

	// Main interactive JavaScript
	$js_ver = file_exists( KATASKEVI_DIR . '/assets/js/main.js' ) ? (string) filemtime( KATASKEVI_DIR . '/assets/js/main.js' ) : KATASKEVI_VERSION;
	wp_enqueue_script(
		'kataskevi-main-js',
		KATASKEVI_URI . '/assets/js/main.js',
		array(),
		$js_ver,
		true
	);

	// Localize script for AJAX quotes and forms
	$default_success_msg = esc_html__( 'Thank you! Your inquiry has been received. A Kataskevi technical specialist will contact you within 24 hours.', 'kataskevi' );
	$configured_success  = get_option( 'kataskevi_form_success_msg', get_theme_mod( 'kataskevi_form_success_msg', $default_success_msg ) );

	wp_localize_script(
		'kataskevi-main-js',
		'kataskeviData',
		array(
			'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
			'nonce'      => wp_create_nonce( 'kataskevi_inquiry_nonce' ),
			'siteUrl'    => esc_url( home_url( '/' ) ),
			'successMsg' => ! empty( $configured_success ) ? esc_html( $configured_success ) : $default_success_msg,
			'submitting' => esc_html__( 'Submitting Request...', 'kataskevi' ),
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'kataskevi_scripts_styles' );

/**
 * Load Theme Customizer settings and controls.
 */
require_once KATASKEVI_DIR . '/inc/customizer.php';

/**
 * Binds JS handlers to make Theme Customizer live preview reload changes instantly.
 */
function kataskevi_customize_preview_js() {
	$preview_ver = file_exists( KATASKEVI_DIR . '/assets/js/customizer-preview.js' ) ? (string) filemtime( KATASKEVI_DIR . '/assets/js/customizer-preview.js' ) : KATASKEVI_VERSION;
	wp_enqueue_script(
		'kataskevi-customizer-preview',
		KATASKEVI_URI . '/assets/js/customizer-preview.js',
		array( 'customize-preview', 'jquery' ),
		$preview_ver,
		true
	);

	if ( function_exists( 'kataskevi_get_color_presets' ) ) {
		$presets       = kataskevi_get_color_presets();
		$preset_colors = array();
		foreach ( $presets as $k => $p ) {
			if ( ! empty( $p['colors'] ) ) {
				$preset_colors[ $k ] = $p['colors'];
			}
		}
		wp_localize_script( 'kataskevi-customizer-preview', 'kataskeviColorPresets', $preset_colors );
	}
}
add_action( 'customize_preview_init', 'kataskevi_customize_preview_js' );

/**
 * Enqueue scripts and localized data for Customizer controls pane.
 */
function kataskevi_customize_controls_js() {
	$controls_ver = file_exists( KATASKEVI_DIR . '/assets/js/customizer-controls.js' ) ? (string) filemtime( KATASKEVI_DIR . '/assets/js/customizer-controls.js' ) : KATASKEVI_VERSION;
	wp_enqueue_script(
		'kataskevi-customizer-controls',
		KATASKEVI_URI . '/assets/js/customizer-controls.js',
		array( 'customize-controls', 'jquery' ),
		$controls_ver,
		true
	);

	if ( function_exists( 'kataskevi_get_color_presets' ) ) {
		$presets       = kataskevi_get_color_presets();
		$preset_colors = array();
		foreach ( $presets as $k => $p ) {
			if ( ! empty( $p['colors'] ) ) {
				$preset_colors[ $k ] = $p['colors'];
			}
		}
		wp_localize_script( 'kataskevi-customizer-controls', 'kataskeviColorPresets', $preset_colors );
	}
}
add_action( 'customize_controls_enqueue_scripts', 'kataskevi_customize_controls_js' );

/**
 * Load Plugin Update Checker (PUC) for automatic theme updates from Bitbucket.
 */
require_once KATASKEVI_DIR . '/inc/updater.php';

/**
 * Data provider: Active Social Links
 */
function kataskevi_get_social_links() {
	$links = array(
		'linkedin' => array(
			'name'  => 'LinkedIn',
			'url'   => kataskevi_get_mod( 'kataskevi_linkedin', 'https://linkedin.com/company/kataskevi' ),
			'icon'  => 'linkedin',
		),
		'twitter' => array(
			'name'  => 'X (Twitter)',
			'url'   => kataskevi_get_mod( 'kataskevi_twitter', 'https://x.com/kataskevi' ),
			'icon'  => 'twitter',
		),
		'facebook' => array(
			'name'  => 'Facebook',
			'url'   => kataskevi_get_mod( 'kataskevi_facebook', 'https://facebook.com/kataskevi' ),
			'icon'  => 'facebook',
		),
		'instagram' => array(
			'name'  => 'Instagram',
			'url'   => kataskevi_get_mod( 'kataskevi_instagram', 'https://instagram.com/kataskevi' ),
			'icon'  => 'instagram',
		),
		'youtube' => array(
			'name'  => 'YouTube',
			'url'   => kataskevi_get_mod( 'kataskevi_youtube', 'https://youtube.com/@kataskevi' ),
			'icon'  => 'youtube',
		),
		'whatsapp' => array(
			'name'  => 'WhatsApp',
			'url'   => kataskevi_get_mod( 'kataskevi_whatsapp', 'https://wa.me/2348005282753' ),
			'icon'  => 'whatsapp',
		),
	);

	return array_filter( $links, function( $item ) {
		return ! empty( $item['url'] );
	} );
}

/**
 * Data provider: Core Services
 * Returns curated services matching the company scope
 */
function kataskevi_get_services() {
	$img_base = get_template_directory_uri() . '/assets/images/';

	return array(
		'fire-suppression' => array(
			'id'          => 'fire-suppression',
			'title'       => 'Fire Suppression Systems',
			'badge'       => 'Life & Asset Safety',
			'short_desc'  => 'Clean-agent suppression for critical and unoccupied spaces.',
			'description' => 'Complete design, supply, installation and maintenance of clean-agent fire suppression systems that protect high-value equipment without damaging it.',
			'points'      => array( 'FM 200 systems', 'CO2 systems', 'NOVEC 1230 systems', 'Detection & release panels' ),
			'icon'        => 'flame',
			'image'       => $img_base . 'projects/datacenter.jpg',
		),
		'fire-alarm-safety' => array(
			'id'          => 'fire-alarm-safety',
			'title'       => 'Fire Alarm & Safety Equipment',
			'badge'       => 'Detection & Containment',
			'short_desc'  => 'Early detection and first-response readiness.',
			'description' => 'Conventional and addressable fire detection plus the hydrant and hose reel infrastructure and safety equipment your site needs to respond fast.',
			'points'      => array( 'Fire alarm systems', 'Fire hydrants', 'Hose reels', 'Safety equipment' ),
			'icon'        => 'bell-alert',
			'image'       => $img_base . 'band.jpg',
		),
		'security-cctv' => array(
			'id'          => 'security-cctv',
			'title'       => 'Electronic Security & CCTV',
			'badge'       => 'Intelligence & Surveillance',
			'short_desc'  => 'Surveillance that protects people, assets and operations.',
			'description' => 'End-to-end electronic security: camera systems designed around your site, recording and monitoring infrastructure, and ongoing support.',
			'points'      => array( 'CCTV design & installation', 'IP & analog camera systems', 'Recording & monitoring', 'Perimeter security' ),
			'icon'        => 'video-camera',
			'image'       => $img_base . 'projects/logistics.jpg',
		),
		'it-site-preparation' => array(
			'id'          => 'it-site-preparation',
			'title'       => 'IT / Telecoms Site Preparation',
			'badge'       => 'Mission Critical Infrastructure',
			'short_desc'  => 'Rooms built to keep critical infrastructure running.',
			'description' => 'Turnkey preparation of IT and telecoms facilities — from the floor beneath your racks to the ceiling and walls that protect them.',
			'points'      => array( 'Raised access floors', 'Fire-rated ceilings', 'Wall cladding', 'Site fit-out' ),
			'icon'        => 'server',
			'image'       => $img_base . 'hero.jpg',
		),
		'access-control' => array(
			'id'          => 'access-control',
			'title'       => 'Access Control & Time Attendance',
			'badge'       => 'Identity & Verification',
			'short_desc'  => 'Control who enters, and account for every hour.',
			'description' => 'Integrated access control and time attendance systems that secure your premises while giving you accurate, auditable workforce data.',
			'points'      => array( 'Card, fob & biometric access', 'Time attendance terminals', 'Visitor management', 'Central reporting' ),
			'icon'        => 'finger-print',
			'image'       => $img_base . 'projects/commercial.jpg',
		),
		'hotel-locks' => array(
			'id'          => 'hotel-locks',
			'title'       => 'Hotel Lock Systems',
			'badge'       => 'Hospitality Tech',
			'short_desc'  => 'Guest experience and security, in one system.',
			'description' => 'Hospitality-grade electronic locking systems — supply, installation and integration with your front-office and energy management systems.',
			'points'      => array( 'RFID & smart card locks', 'Front-desk integration', 'Master key planning', 'Maintenance & support' ),
			'icon'        => 'key',
			'image'       => $img_base . 'projects/hospitality.jpg',
		),
		'ppe' => array(
			'id'          => 'ppe',
			'title'       => 'Personal Protective Equipment',
			'badge'       => 'Workplace Safety Compliance',
			'short_desc'  => 'Certified PPE, supplied when you need it.',
			'description' => 'Quality personal protective equipment for energy, industrial and construction environments — specified correctly and delivered reliably.',
			'points'      => array( 'Head-to-toe protection', 'Site-specific specifications', 'Bulk supply', 'Replacement programmes' ),
			'icon'        => 'shield-check',
			'image'       => $img_base . 'projects/industrial.jpg',
		),
	);
}

/**
 * Data provider: Core Lifecycle Capabilities / Main Services
 */
function kataskevi_get_capabilities() {
	return array(
		array(
			'step'        => '01',
			'title'       => 'Site Surveys',
			'desc'        => 'Assessment of your facility to establish exactly what your site requires.',
			'icon'        => 'clipboard-document-check',
		),
		array(
			'step'        => '02',
			'title'       => 'System Design & Specification',
			'desc'        => 'Engineered designs and precise specifications for every system we deliver.',
			'icon'        => 'pencil-square',
		),
		array(
			'step'        => '03',
			'title'       => 'Project Commissioning',
			'desc'        => 'Structured commissioning and handover so systems perform from day one.',
			'icon'        => 'truck',
		),
		array(
			'step'        => '04',
			'title'       => 'Sales & Installation',
			'desc'        => 'Supply and professional installation backed by ongoing maintenance.',
			'icon'        => 'wrench-screwdriver',
		),
	);
}

/**
 * Data provider: The 4 Core Main Services
 * 1. Site Surveys
 * 2. System Design & Specification
 * 3. Project Commissioning
 * 4. Sales & Installation
 *
 * @return array
 */
function kataskevi_get_main_services() {
	$img_base = get_template_directory_uri() . '/assets/images/';

	return array(
		'site-surveys' => array(
			'id'          => 'site-surveys',
			'slug'        => 'site-surveys',
			'title'       => 'Site Surveys',
			'badge'       => 'Assessment & Risk Audit',
			'short_desc'  => 'Comprehensive on-site evaluation of facility hazards, space constraints, and technical safety requirements.',
			'description' => 'In-depth assessment of your premises to establish exactly what your site requires before specifying equipment, avoiding costly redesigns and compliance gaps.',
			'lead'        => 'Before any equipment is procured or pipework installed, our certified survey engineers conduct exhaustive on-site audits to assess physical risks, structural constraints, and regulatory safety demands.',
			'points'      => array(
				'Facility hazard assessment & fire load analysis',
				'Spatial, ceiling void & sub-floor infrastructure audits',
				'Airflow dynamics, ventilation & ambient risk modeling',
				'Preliminary feasibility reports & comprehensive BOQ',
			),
			'metrics'     => array(
				array( 'num' => '100%', 'label' => 'Spatial & Risk Audit' ),
				array( 'num' => 'Zero', 'label' => 'Unplanned Variations' ),
				array( 'num' => 'NFPA & ISO', 'label' => 'Compliance Verification' ),
				array( 'num' => 'Turnkey', 'label' => 'Engineering Standard' ),
			),
			'overview'    => 'A successful engineering deployment begins with knowing every constraint of the built environment. Our site survey methodology assesses facility geometry, ventilation, fire loads, electrical distribution, and cable tray routing to ensure systems are engineered specifically for your facility without costly redesigns. By discovering building characteristics in advance, we eliminate project delay and guarantee seamless authority approvals.',
			'methodology' => array(
				array(
					'step'  => '01',
					'title' => 'Preliminary Consultation & Blueprint Review',
					'desc'  => 'Analyzing architectural plans, structural blueprints, and operational risk priorities in collaboration with your facilities and security leadership.',
				),
				array(
					'step'  => '02',
					'title' => 'On-Site Physical & Environmental Audit',
					'desc'  => 'Measuring ceiling heights, sub-floor voids, airflow dynamics, ambient temperatures, and structural fire ratings on location.',
				),
				array(
					'step'  => '03',
					'title' => 'Threat Modeling & Code Compliance Verification',
					'desc'  => 'Evaluating occupancy hazards, electrical switchgear rooms, IT server density, and mandatory statutory fire safety codes.',
				),
				array(
					'step'  => '04',
					'title' => 'Technical Survey Dossier & BOQ Handover',
					'desc'  => 'Delivering a comprehensive audit document including risk assessments, bill of quantities (BOQ), and turnkey engineering recommendations.',
				),
			),
			'deliverables' => array(
				'Comprehensive Facility Hazard & Fire Load Report',
				'Spatial Blueprint & Sub-floor Void Assessment',
				'Ventilation, Airflow & Thermal Environmental Profile',
				'Preliminary Bill of Quantities (BOQ) & Feasibility Summary',
				'Statutory Safety & Local Authority Code Review',
			),
			'compliance'  => 'NFPA 72, NFPA 2001, BS 5839, ISO 9001:2015, Statutory Fire Service Regulations',
			'icon'        => 'compass',
			'image'       => $img_base . 'about.jpg',
		),
		'system-design' => array(
			'id'          => 'system-design',
			'slug'        => 'system-design',
			'title'       => 'System Design & Specification',
			'badge'       => 'Engineering & Architecture',
			'short_desc'  => 'Precision engineered designs, technical specifications, and hydraulic/electrical schematics.',
			'description' => 'Engineered system designs, precise equipment specifications, and schematic layouts tailored directly to facility layout and NFPA/ISO compliance standards.',
			'lead'        => 'Precision engineered designs, technical specifications, and schematic layouts tailored directly to facility architecture and international life-safety codes.',
			'points'      => array(
				'NFPA & EN compliance design standards',
				'Certified clean-gas & hydraulic flow calculations',
				'BMS integration & alarm interlocking blueprints',
				'Detailed equipment procurement specifications & BIM',
			),
			'metrics'     => array(
				array( 'num' => 'CAD & BIM', 'label' => 'Precision Modeling' ),
				array( 'num' => 'ISO 9001', 'label' => 'Engineering Standard' ),
				array( 'num' => 'Sub-Second', 'label' => 'Detection Sensitivity' ),
				array( 'num' => '100%', 'label' => 'Custom Tailored' ),
			),
			'overview'    => 'Great engineering leaves nothing to chance. Kataskevi delivers precise AutoCAD and Revit BIM designs, gaseous clean-agent hydraulic flow calculations, electrical conduit pathways, and Building Management System (BMS) integration schematics. We design around your facility layout, equipment density, and mission-critical uptime requirements to ensure seamless installation.',
			'methodology' => array(
				array(
					'step'  => '01',
					'title' => 'Hydraulic & Clean-Agent Volume Calculations',
					'desc'  => 'Computing exact agent concentrations (FM-200, Novec 1230, CO2) according to room volumes, leakage rates, and nozzle discharge pressures.',
				),
				array(
					'step'  => '02',
					'title' => 'Aspirating & Sensor Density Mapping',
					'desc'  => 'Engineering high-sensitivity smoke detection (VESDA) and addressable sensor layouts to eliminate dead zones and prevent false alarms.',
				),
				array(
					'step'  => '03',
					'title' => 'Access Control & Surveillance Topology',
					'desc'  => 'Designing high-security ingress points, biometric readers, fail-safe magnetic locks, and complete CCTV camera field-of-view matrices.',
				),
				array(
					'step'  => '04',
					'title' => 'Full Construction Schematics & Tender Specs',
					'desc'  => 'Producing verified single-line electrical diagrams, isometric piping blueprints, and tender specifications ready for procurement.',
				),
			),
			'deliverables' => array(
				'Certified Clean-Agent Hydraulic Flow Calculations',
				'AutoCAD / BIM Isometric Piping & Conduit Schematics',
				'BMS Integration & Alarm Interlocking Matrix',
				'Complete Equipment Procurement Bill of Materials (BOM)',
				'Authority Having Jurisdiction (AHJ) Submittal Package',
			),
			'compliance'  => 'NFPA 13, NFPA 72, NFPA 2001, EN 54, ISO 14001, BS 7671',
			'icon'        => 'pencil-square',
			'image'       => $img_base . 'projects/commercial.jpg',
		),
		'project-commissioning' => array(
			'id'          => 'project-commissioning',
			'slug'        => 'project-commissioning',
			'title'       => 'Project Commissioning',
			'badge'       => 'Quality Assurance & Handover',
			'short_desc'  => 'Structured commissioning, pressure-testing, acoustic verification, and certified sign-off.',
			'description' => 'Rigorous testing and structured commissioning ensuring every detector, cylinder, camera, and access barrier performs under real-world emergency conditions.',
			'lead'        => 'Rigorous multi-stage testing and structured commissioning ensuring every detector, cylinder array, camera, and access barrier performs under real-world emergency conditions.',
			'points'      => array(
				'Enclosure room integrity & door-fan testing',
				'Integrated alarm, voice & acoustic verification',
				'Automated failsafe release & HVAC trip audits',
				'Statutory compliance sign-off & operator training',
			),
			'metrics'     => array(
				array( 'num' => '100%', 'label' => 'Functional Verification' ),
				array( 'num' => '10s', 'label' => 'Clean-Agent Release Test' ),
				array( 'num' => 'Certified', 'label' => 'Handover Sign-off' ),
				array( 'num' => 'Zero Defect', 'label' => 'Quality Handover' ),
			),
			'overview'    => 'Commissioning is the bridge between construction and life-safety operation. Our certified commissioning engineers subject every safety system, suppression cylinder bank, smoke damper, and access turnstile to rigorous stress testing, room integrity enclosure tests, and simulated power-fail failovers before handing over the keys to your operational team.',
			'methodology' => array(
				array(
					'step'  => '01',
					'title' => 'Enclosure Room Integrity Fan Testing',
					'desc'  => 'Verifying room leakage and gas retention times using calibrated door-fan testing to ensure gaseous agent hold times satisfy NFPA 2001.',
				),
				array(
					'step'  => '02',
					'title' => 'Loop Checking & Sensory Calibration',
					'desc'  => 'Individually testing addressable smoke heads, flame detectors, break-glass units, and siren sounders for calibrated sensitivity.',
				),
				array(
					'step'  => '03',
					'title' => 'Simulated Emergency Discharge Runs',
					'desc'  => 'Performing dry solenoid actuations, automated damper drop tests, HVAC shutdown sequences, and generator interlock tests.',
				),
				array(
					'step'  => '04',
					'title' => 'Operator Training & Compliance Certification',
					'desc'  => 'Training facility personnel, delivering as-built manuals, and issuing statutory certificates of commissioning compliance.',
				),
			),
			'deliverables' => array(
				'Certified Room Integrity Enclosure Test Certificate',
				'Full Loop Verification & Sensor Calibration Log',
				'Integrated HVAC Damper & Power-Trip Verification',
				'Comprehensive As-Built Operations & Maintenance (O&M) Manual',
				'Operator & Facility Security Staff Training Sign-off',
			),
			'compliance'  => 'NFPA 2001 Room Integrity Protocol, EN 12094, ISO 9001, NFPA 72 Inspection Guidelines',
			'icon'        => 'circle-check',
			'image'       => $img_base . 'projects/datacenter.jpg',
		),
		'sales-installation' => array(
			'id'          => 'sales-installation',
			'slug'        => 'sales-installation',
			'title'       => 'Sales & Installation',
			'badge'       => 'Turnkey Delivery & Maintenance',
			'short_desc'  => 'Certified equipment supply, professional deployment, and ongoing preventative SLA maintenance.',
			'description' => 'Turnkey supply and certified professional installation backed by preventative maintenance SLAs, warranty coverage, and emergency response dispatch.',
			'lead'        => 'Turnkey supply and certified professional installation backed by preventative maintenance SLAs, warranty coverage, and emergency response dispatch.',
			'points'      => array(
				'Certified hardware procurement & OEM warranties',
				'Professional mechanical & electrical installation',
				'Quarterly preventative servicing & inspections',
				'24/7 technical dispatch support & spare parts',
			),
			'metrics'     => array(
				array( 'num' => 'Turnkey', 'label' => 'Supply & Deployment' ),
				array( 'num' => '24/7', 'label' => 'Emergency Dispatch' ),
				array( 'num' => 'OEM Certified', 'label' => 'Direct Procurement' ),
				array( 'num' => 'SLA Backed', 'label' => 'Quarterly Servicing' ),
			),
			'overview'    => 'From certified hardware procurement to heavy mechanical and electrical installation, Kataskevi manages turnkey deployment with single-source accountability. We supply genuine, OEM-warranted equipment and maintain certified field installation crews. Following installation, we back every client with quarterly maintenance SLAs, emergency callouts, and lifecycle parts support.',
			'methodology' => array(
				array(
					'step'  => '01',
					'title' => 'Direct OEM Procurement & Staging',
					'desc'  => 'Sourcing certified cylinders, valves, control panels, biometric terminals, and heavy-duty raised flooring directly from top-tier manufacturers.',
				),
				array(
					'step'  => '02',
					'title' => 'Rigorous Mechanical & Electrical Deployment',
					'desc'  => 'Routing high-pressure seamless steel pipework, fire-rated cabling, and precision flooring supports adhering to international installation codes.',
				),
				array(
					'step'  => '03',
					'title' => 'Quality Control & Pre-Commissioning Punchlists',
					'desc'  => 'Conducting daily site inspections, pressure hydrostatic testing, and zero-defect checks before handover.',
				),
				array(
					'step'  => '04',
					'title' => 'Preventative Maintenance SLA Enactment',
					'desc'  => 'Enrolling your facility into scheduled quarterly inspections, 24/7 on-call technical response, and guaranteed spare parts replenishment.',
				),
			),
			'deliverables' => array(
				'OEM Factory Test Certificates & Hardware Warranties',
				'Professional Mechanical & Electrical Installation Sign-off',
				'24/7 Emergency Support Contact Protocols',
				'Quarterly Preventative Maintenance SLA Schedule',
				'Emergency Refill & Spare Cylinder Availability Guarantee',
			),
			'compliance'  => 'ISO 9001:2015, NFPA Installation Codes, IEEE Electrical Standards, Factory Mutual (FM)',
			'icon'        => 'shield-check',
			'image'       => $img_base . 'band.jpg',
		),
	);
}

/**
 * Helper to get a resilient URL for a service detail page.
 *
 * @param string $slug Service slug.
 * @return string
 */
function kataskevi_get_service_url( $slug ) {
	$slug = sanitize_title( $slug );
	$post = get_page_by_path( $slug, OBJECT, 'service' );
	if ( $post ) {
		return get_permalink( $post->ID );
	}
	if ( get_option( 'permalink_structure' ) ) {
		return home_url( '/service/' . $slug . '/' );
	}
	return add_query_arg( 'service_slug', $slug, kataskevi_get_page_url( 'services', 'Services' ) );
}

/**
 * Retrieve a single service by its slug.
 * Resolves from WordPress CPT 'service' or falls back to main services array.
 *
 * @param string $slug
 * @return array|null
 */
function kataskevi_get_service_by_slug( $slug ) {
	$slug     = sanitize_title( $slug );
	$defaults = kataskevi_get_main_services();
	$default  = isset( $defaults[ $slug ] ) ? $defaults[ $slug ] : null;

	$post = get_page_by_path( $slug, OBJECT, 'service' );
	if ( $post ) {
		$img = get_the_post_thumbnail_url( $post->ID, 'full' );
		if ( empty( $img ) && $default ) {
			$img = $default['image'];
		}

		$content = get_post_field( 'post_content', $post->ID );
		$excerpt = get_post_field( 'post_excerpt', $post->ID );

		return array(
			'id'           => $slug,
			'slug'         => $slug,
			'title'        => get_the_title( $post->ID ),
			'badge'        => get_post_meta( $post->ID, '_kataskevi_badge', true ) ?: ( $default ? $default['badge'] : 'Core Engineering Service' ),
			'short_desc'   => ! empty( $excerpt ) ? $excerpt : ( $default ? $default['short_desc'] : wp_trim_words( $content, 25 ) ),
			'description'  => ! empty( $content ) ? $content : ( $default ? $default['description'] : '' ),
			'lead'         => get_post_meta( $post->ID, '_kataskevi_lead', true ) ?: ( $default ? $default['lead'] : '' ),
			'points'       => $default ? $default['points'] : array(),
			'metrics'      => $default ? $default['metrics'] : array(),
			'overview'     => get_post_meta( $post->ID, '_kataskevi_overview', true ) ?: ( $default ? $default['overview'] : $content ),
			'methodology'  => $default ? $default['methodology'] : array(),
			'deliverables' => $default ? $default['deliverables'] : array(),
			'compliance'   => get_post_meta( $post->ID, '_kataskevi_compliance', true ) ?: ( $default ? $default['compliance'] : 'NFPA, ISO & EN Standards' ),
			'icon'         => $default ? $default['icon'] : 'compass',
			'image'        => $img ?: ( $default ? $default['image'] : get_template_directory_uri() . '/assets/images/hero.jpg' ),
			'permalink'    => get_permalink( $post->ID ),
		);
	}

	if ( $default ) {
		$default['permalink'] = kataskevi_get_service_url( $slug );
		return $default;
	}

	return null;
}

/**
 * Register custom rewrite rules for services
 */
function kataskevi_service_rewrite_rules() {
	add_rewrite_rule(
		'^service/([^/]+)/?$',
		'index.php?service_slug=$matches[1]',
		'top'
	);
	add_rewrite_rule(
		'^services/([^/]+)/?$',
		'index.php?service_slug=$matches[1]',
		'top'
	);
}
add_action( 'init', 'kataskevi_service_rewrite_rules' );

/**
 * Register service_slug query variable
 */
function kataskevi_service_query_vars( $vars ) {
	$vars[] = 'service_slug';
	return $vars;
}
add_filter( 'query_vars', 'kataskevi_service_query_vars' );

/**
 * Template include filter to ensure single-service.php is served reliably
 */
function kataskevi_service_template_include( $template ) {
	if ( is_singular( 'service' ) ) {
		$new_template = locate_template( array( 'single-service.php' ) );
		if ( ! empty( $new_template ) ) {
			return $new_template;
		}
	}

	$service_slug = get_query_var( 'service_slug' );
	if ( empty( $service_slug ) && isset( $_GET['service_slug'] ) ) {
		$service_slug = sanitize_text_field( wp_unslash( $_GET['service_slug'] ) );
	}

	if ( empty( $service_slug ) ) {
		$req = trim( (string) parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' );
		$parts = explode( '/', $req );
		if ( count( $parts ) >= 2 && ( 'service' === $parts[0] || 'services' === $parts[0] ) && in_array( $parts[1], array( 'site-surveys', 'system-design', 'project-commissioning', 'sales-installation' ), true ) ) {
			$service_slug = $parts[1];
		}
	}

	if ( ! empty( $service_slug ) ) {
		$service_tpl = locate_template( array( 'single-service.php' ) );
		if ( ! empty( $service_tpl ) ) {
			return $service_tpl;
		}
	}

	return $template;
}
add_filter( 'template_include', 'kataskevi_service_template_include' );

/**
 * Register project query vars
 */
function kataskevi_project_query_vars( $vars ) {
	$vars[] = 'project';
	$vars[] = 'project_slug';
	return $vars;
}
add_filter( 'query_vars', 'kataskevi_project_query_vars' );

/**
 * Register project rewrite rules for seamless routing
 */
function kataskevi_project_rewrite_rules() {
	add_rewrite_rule( '^works/([^/]+)/?$', 'index.php?project=$matches[1]', 'top' );
	add_rewrite_rule( '^project/([^/]+)/?$', 'index.php?project=$matches[1]', 'top' );
	add_rewrite_rule( '^projects/([^/]+)/?$', 'index.php?project=$matches[1]', 'top' );
}
add_action( 'init', 'kataskevi_project_rewrite_rules' );

/**
 * Template include filter to ensure single-project.php is served reliably for any project URL
 */
function kataskevi_project_template_include( $template ) {
	if ( is_singular( 'project' ) ) {
		$new_template = locate_template( array( 'single-project.php' ) );
		if ( ! empty( $new_template ) ) {
			return $new_template;
		}
	}

	$valid_slugs = array(
		'fire-hydrant-and-hose-reel',
		'alarm-systems',
		'access-control-systems',
		'cctv-cameras',
		'fire-hydrant',
		'access-control',
		'cctv',
	);

	$project_slug = get_query_var( 'project' );
	if ( empty( $project_slug ) ) {
		$project_slug = get_query_var( 'project_slug' );
	}
	if ( empty( $project_slug ) && isset( $_GET['project'] ) ) {
		$project_slug = sanitize_text_field( wp_unslash( $_GET['project'] ) );
	}
	if ( empty( $project_slug ) && isset( $_GET['project_slug'] ) ) {
		$project_slug = sanitize_text_field( wp_unslash( $_GET['project_slug'] ) );
	}

	if ( empty( $project_slug ) ) {
		$req   = trim( (string) parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' );
		$parts = explode( '/', $req );
		$last  = end( $parts );
		if ( in_array( $last, $valid_slugs, true ) ) {
			$project_slug = $last;
		} elseif ( count( $parts ) >= 2 && in_array( $parts[1], $valid_slugs, true ) ) {
			$project_slug = $parts[1];
		}
	}

	if ( ! empty( $project_slug ) && in_array( $project_slug, $valid_slugs, true ) ) {
		$project_tpl = locate_template( array( 'single-project.php' ) );
		if ( ! empty( $project_tpl ) ) {
			return $project_tpl;
		}
	}

	return $template;
}
add_filter( 'template_include', 'kataskevi_project_template_include' );

/**
 * Remove standard 'Archives:' prefix from all archive titles.
 */
add_filter( 'get_the_archive_title_prefix', '__return_empty_string' );

/**
 * Filter archive titles to display clean, human-readable titles.
 *
 * @param string $title Default archive title.
 * @return string Cleaned archive title.
 */
function kataskevi_clean_archive_titles( $title ) {
	if ( is_post_type_archive( 'project' ) ) {
		return esc_html__( 'Our Works', 'kataskevi' );
	}
	if ( is_post_type_archive( 'service' ) ) {
		return esc_html__( 'Our Services', 'kataskevi' );
	}
	if ( is_tax( 'project_sector' ) ) {
		return single_term_title( '', false );
	}
	if ( is_category() || is_tag() || is_tax() ) {
		return single_term_title( '', false );
	}
	if ( is_post_type_archive() ) {
		return post_type_archive_title( '', false );
	}
	return $title;
}
add_filter( 'get_the_archive_title', 'kataskevi_clean_archive_titles' );

/**
 * Auto-seed default services into WordPress CPT if none exist
 */
function kataskevi_seed_default_services() {
	if ( get_option( 'kataskevi_services_seeded_v1' ) ) {
		return;
	}

	$defaults = kataskevi_get_main_services();
	foreach ( $defaults as $slug => $data ) {
		$existing = get_page_by_path( $slug, OBJECT, 'service' );
		if ( ! $existing ) {
			$post_id = wp_insert_post(
				array(
					'post_title'     => $data['title'],
					'post_name'      => $slug,
					'post_content'   => $data['overview'],
					'post_excerpt'   => $data['short_desc'],
					'post_status'    => 'publish',
					'post_type'      => 'service',
					'comment_status' => 'closed',
				)
			);

			if ( $post_id && ! is_wp_error( $post_id ) ) {
				update_post_meta( $post_id, '_kataskevi_badge', $data['badge'] );
				update_post_meta( $post_id, '_kataskevi_lead', $data['lead'] );
				update_post_meta( $post_id, '_kataskevi_overview', $data['overview'] );
				update_post_meta( $post_id, '_kataskevi_compliance', $data['compliance'] );
			}
		}
	}

	update_option( 'kataskevi_services_seeded_v1', 1 );
}
add_action( 'init', 'kataskevi_seed_default_services', 20 );

/**
 * Data provider: Philosophy Pillars
 */
function kataskevi_get_pillars() {
	return array(
		array(
			'title' => kataskevi_get_mod( 'kataskevi_about_pillar_1_title', 'Safety first, always' ),
			'desc'  => kataskevi_get_mod( 'kataskevi_about_pillar_1_desc', 'Every system we design and install exists to protect people, assets and continuity of operations.' ),
			'icon'  => 'shield-check',
		),
		array(
			'title' => kataskevi_get_mod( 'kataskevi_about_pillar_2_title', 'Engineered, not guessed' ),
			'desc'  => kataskevi_get_mod( 'kataskevi_about_pillar_2_desc', 'Site surveys, precise specifications and structured commissioning — the system fits the facility, because it was designed for it.' ),
			'icon'  => 'compass',
		),
		array(
			'title' => kataskevi_get_mod( 'kataskevi_about_pillar_3_title', 'One accountable partner' ),
			'desc'  => kataskevi_get_mod( 'kataskevi_about_pillar_3_desc', 'Design, supply, installation and maintenance under one roof — no finger-pointing between vendors.' ),
			'icon'  => 'handshake',
		),
		array(
			'title' => kataskevi_get_mod( 'kataskevi_about_pillar_4_title', 'Built around your profit' ),
			'desc'  => kataskevi_get_mod( 'kataskevi_about_pillar_4_desc', 'In a busy, changing industry, our variety of services supports our clientele towards maximising profit.' ),
			'icon'  => 'users',
		),
	);
}

/**
 * Data provider: Industry Sectors Serviced
 */
function kataskevi_get_sectors() {
	return array(
		array(
			'id'    => 'oil-gas',
			'name'  => 'Oil & Gas / Energy',
			'desc'  => 'Hazardous environments, critical assets and strict safety compliance.',
			'icon'  => 'flame',
		),
		array(
			'id'    => 'hospitality',
			'name'  => 'Hospitality',
			'desc'  => 'Guest-facing security, hotel locking and discreet life-safety systems.',
			'icon'  => 'hotel',
		),
		array(
			'id'    => 'government',
			'name'  => 'Government',
			'desc'  => 'Public facilities secured to standards with dependable support.',
			'icon'  => 'landmark',
		),
		array(
			'id'    => 'telecoms',
			'name'  => 'Telecommunications',
			'desc'  => 'Site preparation and protection for network infrastructure.',
			'icon'  => 'radio-tower',
		),
		array(
			'id'    => 'financial',
			'name'  => 'Financial Institutions',
			'desc'  => 'Surveillance, access control and fire protection for high-value premises.',
			'icon'  => 'banknote',
		),
		array(
			'id'    => 'education',
			'name'  => 'Education',
			'desc'  => 'Safe campuses with monitored access and reliable alarm systems.',
			'icon'  => 'graduation-cap',
		),
		array(
			'id'    => 'manufacturing',
			'name'  => 'Manufacturing',
			'desc'  => 'Plant-floor safety, suppression and PPE programmes that keep lines running.',
			'icon'  => 'factory',
		),
	);
}

/**
 * Data provider: Enterprise Clients and Partners
 * Returns two curated sets of enterprise accounts for the dual-marquee scrolling section.
 *
 * @return array
 */
function kataskevi_get_clients() {
	$clients = array(
		'row_1' => array(
			array(
				'name'   => 'Shell Petroleum Development',
				'sector' => 'Petrochemical & Energy',
				'mark'   => 'SP',
				'icon'   => 'flame',
			),
			array(
				'name'   => 'MainOne / Equinix',
				'sector' => 'Colocation & Telecoms',
				'mark'   => 'MO',
				'icon'   => 'server',
			),
			array(
				'name'   => 'Dangote Industries',
				'sector' => 'Refinery & Industrial',
				'mark'   => 'DG',
				'icon'   => 'factory',
			),
			array(
				'name'   => 'Access Bank PLC',
				'sector' => 'Corporate Banking HQ',
				'mark'   => 'AB',
				'icon'   => 'landmark',
			),
			array(
				'name'   => 'Rack Centre',
				'sector' => 'Tier III Data Center',
				'mark'   => 'RC',
				'icon'   => 'server',
			),
			array(
				'name'   => 'Julius Berger',
				'sector' => 'Civil & Marine Infrastructure',
				'mark'   => 'JB',
				'icon'   => 'compass',
			),
			array(
				'name'   => 'TotalEnergies',
				'sector' => 'Energy & Deepwater',
				'mark'   => 'TE',
				'icon'   => 'flame',
			),
			array(
				'name'   => 'Medallion Data Centres',
				'sector' => 'Interconnection Hub',
				'mark'   => 'MD',
				'icon'   => 'cpu',
			),
		),
		'row_2' => array(
			array(
				'name'   => 'MTN Communications',
				'sector' => 'Switching Centers & NOC',
				'mark'   => 'MTN',
				'icon'   => 'radio-tower',
			),
			array(
				'name'   => 'Seplat Energy',
				'sector' => 'Upstream Exploration',
				'mark'   => 'SE',
				'icon'   => 'flame',
			),
			array(
				'name'   => 'Zenith Bank',
				'sector' => 'Commercial Towers',
				'mark'   => 'ZB',
				'icon'   => 'landmark',
			),
			array(
				'name'   => 'Africa Data Centres',
				'sector' => 'Hyperscale Cloud Facility',
				'mark'   => 'ADC',
				'icon'   => 'layers',
			),
			array(
				'name'   => 'APM Terminals',
				'sector' => 'Port & Freight Logistics',
				'mark'   => 'APM',
				'icon'   => 'compass',
			),
			array(
				'name'   => 'Airtel Africa',
				'sector' => 'Cellular Infrastructure',
				'mark'   => 'AT',
				'icon'   => 'radio-tower',
			),
			array(
				'name'   => 'FirstBank of Nigeria',
				'sector' => 'Mission-Critical Processing',
				'mark'   => 'FBN',
				'icon'   => 'banknote',
			),
			array(
				'name'   => 'Lafarge Africa',
				'sector' => 'Industrial Processing Plants',
				'mark'   => 'LA',
				'icon'   => 'factory',
			),
		),
	);

	return apply_filters( 'kataskevi_clients_list', $clients );
}

/**
 * Safely render a client logo mark, handling image URLs, attachment IDs, SVGs, or icon names.
 * Adheres strictly to standard WordPress theme architecture and never breaks if an
 * invalid, custom, or newly uploaded icon/image is passed.
 *
 * @param array  $client Client data array.
 * @param string $class  Optional CSS class for icon/image.
 * @return string Safe HTML for logo mark.
 */
function kataskevi_render_client_logo( $client, $class = 'client-logo-img' ) {
	$icon_or_logo = '';
	if ( ! empty( $client['logo'] ) ) {
		$icon_or_logo = $client['logo'];
	} elseif ( ! empty( $client['image'] ) ) {
		$icon_or_logo = $client['image'];
	} elseif ( ! empty( $client['icon'] ) ) {
		$icon_or_logo = $client['icon'];
	}

	$client_name = ! empty( $client['name'] ) ? $client['name'] : esc_html__( 'Client', 'kataskevi' );

	// 1. If it's a numeric attachment ID from WordPress media library.
	if ( is_numeric( $icon_or_logo ) ) {
		$img_html = wp_get_attachment_image(
			(int) $icon_or_logo,
			'medium',
			false,
			array(
				'class'   => esc_attr( $class ),
				'alt'     => esc_attr( $client_name ),
				'loading' => 'lazy',
			)
		);
		if ( ! empty( $img_html ) ) {
			return $img_html;
		}
	}

	// 2. If it's an image URL (http, https, relative path, or common image extensions).
	if ( is_string( $icon_or_logo ) && ( preg_match( '/\.(svg|png|jpg|jpeg|webp|gif)(\?.*)?$/i', $icon_or_logo ) || preg_match( '#^https?://#i', $icon_or_logo ) || strpos( $icon_or_logo, '/wp-content/' ) !== false ) ) {
		return sprintf(
			'<img src="%s" alt="%s" class="%s" loading="lazy" />',
			esc_url( $icon_or_logo ),
			esc_attr( $client_name ),
			esc_attr( $class )
		);
	}

	// 3. If it's raw SVG markup.
	if ( is_string( $icon_or_logo ) && strpos( $icon_or_logo, '<svg' ) !== false ) {
		return $icon_or_logo;
	}

	// 4. If it's a named icon in kataskevi_render_icon().
	if ( is_string( $icon_or_logo ) && ! empty( $icon_or_logo ) ) {
		$rendered_svg = kataskevi_render_icon( $icon_or_logo, 'icon icon-sm text-primary' );
		if ( ! empty( $rendered_svg ) ) {
			return $rendered_svg;
		}
	}

	// 5. Safe Monogram fallback: Never breaks, always renders a clean 2-letter badge.
	$mark = ! empty( $client['mark'] ) ? $client['mark'] : '';
	if ( empty( $mark ) && ! empty( $client_name ) ) {
		$words = preg_split( '/\s+/', trim( $client_name ) );
		if ( count( $words ) >= 2 ) {
			$mark = mb_strtoupper( mb_substr( $words[0], 0, 1 ) . mb_substr( $words[1], 0, 1 ) );
		} else {
			$mark = mb_strtoupper( mb_substr( $client_name, 0, 2 ) );
		}
	}

	return sprintf( '<span class="client-monogram-mark">%s</span>', esc_html( $mark ) );
}

/**
 * Helper to render SVG icons inline
 */
function kataskevi_render_icon( $name, $class = 'icon' ) {
	$icons = array(
		'shield' => '<svg class="' . esc_attr( $class ) . '" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path></svg>',
		'building' => '<svg class="' . esc_attr( $class ) . '" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"></rect><path d="M9 22v-4h6v4"></path><path d="M8 6h.01"></path><path d="M16 6h.01"></path><path d="M12 6h.01"></path><path d="M12 10h.01"></path><path d="M12 14h.01"></path><path d="M16 10h.01"></path><path d="M16 14h.01"></path><path d="M8 10h.01"></path><path d="M8 14h.01"></path></svg>',
		'building-2' => '<svg class="' . esc_attr( $class ) . '" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"></path><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"></path><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"></path><path d="M10 6h4"></path><path d="M10 10h4"></path><path d="M10 14h4"></path><path d="M10 18h4"></path></svg>',
		'cogs' => '<svg class="' . esc_attr( $class ) . '" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"></path><circle cx="12" cy="12" r="3"></circle></svg>',
		'briefcase' => '<svg class="' . esc_attr( $class ) . '" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect width="20" height="14" x="2" y="7" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>',
		'globe' => '<svg class="' . esc_attr( $class ) . '" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"></path><path d="M2 12h20"></path></svg>',
		'network' => '<svg class="' . esc_attr( $class ) . '" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="16" y="16" width="6" height="6" rx="1"></rect><rect x="2" y="16" width="6" height="6" rx="1"></rect><rect x="9" y="2" width="6" height="6" rx="1"></rect><path d="M5 16v-3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3"></path><path d="M12 12V8"></path></svg>',
		'truck' => '<svg class="' . esc_attr( $class ) . '" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path><path d="M15 18H9"></path><path d="M19 18h2a1 1 0 0 0 1-1v-5l-3-4h-5v10"></path><circle cx="7" cy="18" r="2"></circle><circle cx="17" cy="18" r="2"></circle></svg>',
		'lock' => '<svg class="' . esc_attr( $class ) . '" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>',
		'wifi' => '<svg class="' . esc_attr( $class ) . '" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 20h.01"></path><path d="M2 8.82a15 15 0 0 1 20 0"></path><path d="M5 12.859a10 10 0 0 1 14 0"></path><path d="M8.5 16.429a5 5 0 0 1 7 0"></path></svg>',
		'flame' => '<svg class="' . esc_attr( $class ) . '" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 3q1 4 4 6.5t3 5.5a1 1 0 0 1-14 0 5 5 0 0 1 1-3 1 1 0 0 0 5 0c0-2-1.5-3-1.5-5q0-2 2.5-4"></path></svg>',
		'hotel' => '<svg class="' . esc_attr( $class ) . '" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M10 22v-6.57"></path><path d="M12 11h.01"></path><path d="M12 7h.01"></path><path d="M14 15.43V22"></path><path d="M15 16a5 5 0 0 0-6 0"></path><path d="M16 11h.01"></path><path d="M16 7h.01"></path><path d="M8 11h.01"></path><path d="M8 7h.01"></path><rect x="4" y="2" width="16" height="20" rx="2"></rect></svg>',
		'landmark' => '<svg class="' . esc_attr( $class ) . '" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M10 18v-7"></path><path d="M11.12 2.198a2 2 0 0 1 1.76.006l7.866 3.847c.476.233.31.949-.22.949H3.474c-.53 0-.695-.716-.22-.949z"></path><path d="M14 18v-7"></path><path d="M18 18v-7"></path><path d="M3 22h18"></path><path d="M6 18v-7"></path></svg>',
		'radio-tower' => '<svg class="' . esc_attr( $class ) . '" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M4.9 16.1C1 12.2 1 5.8 4.9 1.9"></path><path d="M7.8 4.7a6.14 6.14 0 0 0-.8 7.5"></path><circle cx="12" cy="9" r="2"></circle><path d="M16.2 4.8c2 2 2.26 5.11.8 7.47"></path><path d="M19.1 1.9a9.96 9.96 0 0 1 0 14.1"></path><path d="M9.5 18h5"></path><path d="m8 22 4-11 4 11"></path></svg>',
		'banknote' => '<svg class="' . esc_attr( $class ) . '" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect width="20" height="12" x="2" y="6" rx="2"></rect><circle cx="12" cy="12" r="2"></circle><path d="M6 12h.01M18 12h.01"></path></svg>',
		'graduation-cap' => '<svg class="' . esc_attr( $class ) . '" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"></path><path d="M22 10v6"></path><path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"></path></svg>',
		'factory' => '<svg class="' . esc_attr( $class ) . '" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 16h.01"></path><path d="M16 16h.01"></path><path d="M3 19a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8.5a.5.5 0 0 0-.769-.422l-4.462 2.844A.5.5 0 0 1 15 10.5v-2a.5.5 0 0 0-.769-.422L9.77 10.922A.5.5 0 0 1 9 10.5V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2z"></path><path d="M8 16h.01"></path></svg>',
		'bell-alert' => '<svg class="' . esc_attr( $class ) . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>',
		'video-camera' => '<svg class="' . esc_attr( $class ) . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>',
		'server' => '<svg class="' . esc_attr( $class ) . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>',
		'finger-print' => '<svg class="' . esc_attr( $class ) . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 004 11m0 0a8 8 0 00.126 1.405m2.775 5.76A18.9 18.9 0 016 11"></path></svg>',
		'key' => '<svg class="' . esc_attr( $class ) . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>',
		'shield-check' => '<svg class="' . esc_attr( $class ) . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>',
		'arrow-left' => '<svg class="' . esc_attr( $class ) . '" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"></path><path d="m12 19-7-7 7-7"></path></svg>',
		'arrow-right' => '<svg class="' . esc_attr( $class ) . '" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>',
		'chevron-left' => '<svg class="' . esc_attr( $class ) . '" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"></path></svg>',
		'chevron-right' => '<svg class="' . esc_attr( $class ) . '" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"></path></svg>',
		'menu' => '<svg class="' . esc_attr( $class ) . '" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 5h16"></path><path d="M4 12h16"></path><path d="M4 19h16"></path></svg>',
		'close' => '<svg class="' . esc_attr( $class ) . '" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg>',
		'phone' => '<svg class="' . esc_attr( $class ) . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>',
		'mail' => '<svg class="' . esc_attr( $class ) . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>',
		'map-pin' => '<svg class="' . esc_attr( $class ) . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>',
		'check' => '<svg class="' . esc_attr( $class ) . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>',
		'compass' => '<svg class="' . esc_attr( $class ) . '" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><path d="m16.24 7.76-1.804 5.411a2 2 0 0 1-1.265 1.265L7.76 16.24l1.804-5.411a2 2 0 0 1 1.265-1.265z"></path></svg>',
		'handshake' => '<svg class="' . esc_attr( $class ) . '" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m11 17 2 2a1 1 0 1 0 3-3"></path><path d="m14 14 2.5 2.5a1 1 0 1 0 3-3l-3.88-3.88a3 3 0 0 0-4.24 0l-.88.88a1 1 0 1 1-3-3l2.81-2.81a5.79 5.79 0 0 1 7.06-.87l.47.28a2 2 0 0 0 1.42.25L21 4"></path><path d="m21 3 1 11h-2"></path><path d="M3 3 2 14l6.5 6.5a1 1 0 1 0 3-3"></path><path d="M3 4h8"></path></svg>',
		'users' => '<svg class="' . esc_attr( $class ) . '" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><path d="M16 3.128a4 4 0 0 1 0 7.744"></path><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><circle cx="9" cy="7" r="4"></circle></svg>',
		'circle-check' => '<svg class="' . esc_attr( $class ) . '" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><path d="m9 12 2 2 4-4"></path></svg>',
		'arrow-up-right' => '<svg class="' . esc_attr( $class ) . '" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M7 17 17 7"></path><path d="M7 7h10v10"></path></svg>',
		'calendar' => '<svg class="' . esc_attr( $class ) . '" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>',
		'layers' => '<svg class="' . esc_attr( $class ) . '" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.57 3.91a2 2 0 0 0 1.66 0l8.57-3.9a1 1 0 0 0 0-1.84Z"></path><path d="m2 12 10 4.5 10-4.5"></path><path d="m2 17 10 4.5 10-4.5"></path></svg>',
		'cpu' => '<svg class="' . esc_attr( $class ) . '" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect width="16" height="16" x="4" y="4" rx="2"></rect><rect width="6" height="6" x="9" y="9"></rect><path d="M15 2v2"></path><path d="M15 20v2"></path><path d="M2 15h2"></path><path d="M2 9h2"></path><path d="M20 15h2"></path><path d="M20 9h2"></path><path d="M9 2v2"></path><path d="M9 20v2"></path></svg>',
		'sparkles' => '<svg class="' . esc_attr( $class ) . '" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"></path></svg>',
		'clipboard-document-check' => '<svg class="' . esc_attr( $class ) . '" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect><path d="m9 14 2 2 4-4"></path></svg>',
		'pencil-square' => '<svg class="' . esc_attr( $class ) . '" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path><path d="m15 5 4 4"></path></svg>',
		'wrench-screwdriver' => '<svg class="' . esc_attr( $class ) . '" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>',
		'linkedin' => '<svg class="' . esc_attr( $class ) . '" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect width="4" height="12" x="2" y="9"></rect><circle cx="4" cy="4" r="2"></circle></svg>',
		'twitter' => '<svg class="' . esc_attr( $class ) . '" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
		'facebook' => '<svg class="' . esc_attr( $class ) . '" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>',
		'instagram' => '<svg class="' . esc_attr( $class ) . '" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"></line></svg>',
		'youtube' => '<svg class="' . esc_attr( $class ) . '" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17"></path><polygon points="10 15 15 12 10 9 10 15"></polygon></svg>',
		'whatsapp' => '<svg class="' . esc_attr( $class ) . '" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>',
	);

	return isset( $icons[ $name ] ) ? $icons[ $name ] : '';
}

/**
 * Data provider: Default Seeded Projects & Case Studies (The 4 Core Systems)
 */
function kataskevi_get_default_projects() {
	$img_base   = get_template_directory_uri() . '/assets/images/projects/';
	$theme_base = get_template_directory_uri() . '/assets/images/';

	return array(
		'fire-hydrant-and-hose-reel' => array(
			'id'           => 1,
			'slug'         => 'fire-hydrant-and-hose-reel',
			'title'        => 'Fire Hydrant & Hose Reel',
			'client'       => 'Industrial Facilities & Commercial Compounds',
			'location'     => 'Lekki Free Zone, Lagos',
			'year'         => '2014',
			'surface_area' => '450,000 m²',
			'value'        => '$250,000',
			'architect'    => 'Jason & Perry',
			'sector'       => 'Fire Protection & Suppression',
			'sector_slug'  => 'fire-hydrant',
			'featured'     => true,
			'metric'       => '16 Bar Rated',
			'metric_label' => 'Ring Main Hydraulic Pressure',
			'stat_2'       => '64 Outlets',
			'stat_2_label' => 'Hydrant Pillars & Hose Reels',
			'stat_3'       => '100% NFPA',
			'stat_3_label' => 'BS 5306 & NFPA 14 Standard',
			'image'        => $img_base . 'industrial.jpg',
			'gallery'      => array(
				$img_base . 'industrial.jpg',
				$img_base . 'healthcare.jpg',
				$theme_base . 'hero.jpg',
				$theme_base . 'band.jpg',
			),
			'short_desc'   => 'Complete turnkey design, pressurized ring-main piping reticulation, automated diesel and electric booster pump sets, and heavy-duty fire hydrant pillars with swinging hose reels.',
			'summary'      => 'Complete turnkey design, pressurized ring-main piping reticulation, automated diesel and electric booster pump sets, and heavy-duty fire hydrant pillars with swinging hose reels.',
			'scope'        => 'Turnkey Hydraulic Fire Ring Main & Automated Hose Reel Reticulation',
			'challenge'    => 'Expansive industrial processing zones with volatile materials required instantaneous, high-pressure water delivery capable of sustaining dual-stream operation without pressure drops, compliant with strict factory life-safety regulations.',
			'solution'     => 'Engineered an automated pressurized ring-main network featuring duty and standby diesel booster pumps, synchronized jockey pumps, underground anti-corrosive ductile iron pipework, dual-outlet pillar hydrants, and wall-mounted swinging hose reels across all critical plant sectors.',
			'deliverables' => array(
				'High-pressure underground fire ring-main piping reticulation',
				'Automated diesel and electric fire booster pump sets with jockey synchronization',
				'Heavy-duty swivel-arm 30m high-pressure fire hose reel drums with jet/spray nozzles',
				'Pillar hydrants with dual instantaneous landing valves and suction inlets',
				'Quarterly hydrostatic flow tests, pressure gauge certification, and maintenance SLA',
			),
		),
		'alarm-systems' => array(
			'id'           => 2,
			'slug'         => 'alarm-systems',
			'title'        => 'Alarm Systems',
			'client'       => 'Enterprise Banking & Corporate Towers',
			'location'     => 'Victoria Island, Lagos',
			'year'         => '2014',
			'surface_area' => '450,000 m²',
			'value'        => '$250,000',
			'architect'    => 'Jason & Perry',
			'sector'       => 'Life Safety & Detection',
			'sector_slug'  => 'alarm-systems',
			'featured'     => true,
			'metric'       => 'Addressable',
			'metric_label' => 'Multi-Loop Interactive Matrix',
			'stat_2'       => 'Sub-Second',
			'stat_2_label' => 'Alarm Trigger Response',
			'stat_3'       => '100% Coverage',
			'stat_3_label' => 'Multi-Zone Evacuation Sync',
			'image'        => $img_base . 'datacenter.jpg',
			'gallery'      => array(
				$img_base . 'datacenter.jpg',
				$theme_base . 'band.jpg',
				$theme_base . 'about.jpg',
				$img_base . 'commercial.jpg',
			),
			'short_desc'   => 'Intelligent multi-loop addressable fire detection, aspirating smoke sensing, and perimeter intrusion alarm matrices unified with automated voice evacuation.',
			'summary'      => 'Intelligent multi-loop addressable fire detection, aspirating smoke sensing, and perimeter intrusion alarm matrices unified with automated voice evacuation.',
			'scope'        => 'Integrated Addressable Fire Detection, Voice Evacuation & Intrusion Alarms',
			'challenge'    => 'Complex multi-storey commercial towers demanded zero false alarms, continuous system diagnostics, and synchronized floor-by-floor phased voice evacuation to prevent corridor stampedes while directly notifying facility managers and emergency services.',
			'solution'     => 'Installed advanced multi-loop addressable fire alarm control panels connected to optical-smoke, heat, and beam detectors. Integrated automated HVAC damper shutdown, emergency voice evacuation broadcasting, break-glass call points, and perimeter anti-intrusion tripwires.',
			'deliverables' => array(
				'Multi-loop intelligent addressable fire alarm control panels (FACP) with touchscreen annunciators',
				'High-sensitivity optical, thermal, and multi-criteria smoke detectors with self-diagnostics',
				'Emergency break-glass call points and high-candela strobe sounders',
				'Automated HVAC ventilation shutdown and smoke damper actuators',
				'Direct municipal emergency dialer, BMS integration, and perimeter intrusion beams',
			),
		),
		'access-control-systems' => array(
			'id'           => 3,
			'slug'         => 'access-control-systems',
			'title'        => 'Access Control Systems',
			'client'       => 'Financial Headquarters & Multi-Tenant Towers',
			'location'     => 'Marina CBD, Lagos',
			'year'         => '2014',
			'surface_area' => '450,000 m²',
			'value'        => '$250,000',
			'architect'    => 'Jason & Perry',
			'sector'       => 'Electronic Security',
			'sector_slug'  => 'access-control',
			'featured'     => true,
			'metric'       => '0.2s Transit',
			'metric_label' => 'Optical Speed Gate Clearance',
			'stat_2'       => '3,500 Users',
			'stat_2_label' => 'Multi-Factor Biometric Profiles',
			'stat_3'       => 'Zero Tailgating',
			'stat_3_label' => 'Anti-Passback Verification',
			'image'        => $img_base . 'commercial.jpg',
			'gallery'      => array(
				$img_base . 'commercial.jpg',
				$img_base . 'hospitality.jpg',
				$theme_base . 'about.jpg',
				$theme_base . 'hero.jpg',
			),
			'short_desc'   => 'Touchless biometric speed turnstiles, encrypted RFID smartcard access, magnetic door interlocks, and automated time & attendance integration.',
			'summary'      => 'Touchless biometric speed turnstiles, encrypted RFID smartcard access, magnetic door interlocks, and automated time & attendance integration.',
			'scope'        => 'Enterprise Biometric Access Control & Optical Turnstile Speed Lanes',
			'challenge'    => 'Managing morning and evening rush-hour transit for over 3,500 staff while preventing unauthorized tailgating, securing executive zones and server rooms, and satisfying stringent Central Bank physical security audit standards.',
			'solution'     => 'Deployed optical glass speed turnstile lanes equipped with AI facial recognition and NFC credentials, integrated with fail-safe electromagnetic shear locks, server room biometric authorization, and centralized cloud attendance logging.',
			'deliverables' => array(
				'Optical glass speed turnstiles with infrared anti-tailgating sensors',
				'AI touchless facial recognition and multi-frequency RFID smartcard readers',
				'Fail-safe electromagnetic shear locks and emergency break-glass door release',
				'Server room and cash vault dual-custody biometric authorization',
				'Centralized web-based access control management, audit logging, and time & attendance reporting',
			),
		),
		'cctv-cameras' => array(
			'id'           => 4,
			'slug'         => 'cctv-cameras',
			'title'        => 'CCTV Cameras',
			'client'       => 'Logistics Hubs & Industrial Port Terminals',
			'location'     => 'Apapa & Ikeja Industrial Zones',
			'year'         => '2014',
			'surface_area' => '450,000 m²',
			'value'        => '$250,000',
			'architect'    => 'Jason & Perry',
			'sector'       => 'Video Surveillance',
			'sector_slug'  => 'cctv-cameras',
			'featured'     => true,
			'metric'       => '4K Ultra-HD',
			'metric_label' => 'AI Human & Vehicle Analytics',
			'stat_2'       => '180+ Channels',
			'stat_2_label' => 'High-Capacity NVR Storage Array',
			'stat_3'       => '24/7 SOC',
			'stat_3_label' => 'Real-Time Remote Monitoring',
			'image'        => $img_base . 'logistics.jpg',
			'gallery'      => array(
				$img_base . 'logistics.jpg',
				$img_base . 'datacenter.jpg',
				$img_base . 'commercial.jpg',
				$theme_base . 'hero.jpg',
			),
			'short_desc'   => 'Ultra-high-definition 4K IP cameras, automatic number plate recognition (ANPR) at cargo gates, long-range night vision, and centralized security operations center (SOC).',
			'summary'      => 'Ultra-high-definition 4K IP cameras, automatic number plate recognition (ANPR) at cargo gates, long-range night vision, and centralized security operations center (SOC).',
			'scope'        => 'Turnkey Enterprise IP CCTV Surveillance & Remote Security Monitoring',
			'challenge'    => 'Vast outdoor logistics depot acreage with heavy cargo vehicle traffic, low-light perimeter sections, and blind spots required real-time perimeter protection and automatic vehicle registration logging without cargo delays.',
			'solution'     => 'Engineered a dedicated fiber-optic surveillance ring supporting 180+ 4K motorized varifocal bullet and dome IP cameras with smart infrared night vision, automatic license plate recognition (ANPR) at gate barriers, and a central video wall.',
			'deliverables' => array(
				'4K Ultra-HD vandal-proof dome and bullet IP cameras with smart infrared night vision',
				'Automatic Number Plate Recognition (ANPR) cameras linked to barrier gate controllers',
				'High-capacity RAID enterprise Network Video Recorders (NVR) with 90-day retention',
				'Central Security Operations Center (SOC) video wall with multi-screen operator stations',
				'Encrypted mobile remote viewing and perimeter AI intrusion tripwire alerts',
			),
		),
	);
}

/**
 * Retrieve projects: checks WordPress CPT posts first; falls back to default projects
 */
function kataskevi_get_projects( $limit = -1, $featured_only = false, $sector_slug = '' ) {
	$allowed_slugs = array(
		'fire-hydrant-and-hose-reel',
		'alarm-systems',
		'access-control-systems',
		'cctv-cameras',
	);

	$defaults = kataskevi_get_default_projects();
	$projects = array();

	$args = array(
		'post_type'      => 'project',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order date',
		'order'          => 'DESC',
	);

	if ( $featured_only ) {
		$args['meta_query'] = array(
			array(
				'key'     => '_kataskevi_featured',
				'value'   => '1',
				'compare' => '=',
			),
		);
	}

	if ( ! empty( $sector_slug ) && 'all' !== $sector_slug ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'project_sector',
				'field'    => 'slug',
				'terms'    => $sector_slug,
			),
		);
	}

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$post_id = get_the_ID();
			$slug    = get_post_field( 'post_name', $post_id );

			// Strictly restrict to the 4 current projects
			if ( ! in_array( $slug, $allowed_slugs, true ) ) {
				continue;
			}

			$img = get_the_post_thumbnail_url( $post_id, 'large' );
			if ( ! $img ) {
				$img = get_post_meta( $post_id, '_kataskevi_image', true );
			}
			if ( ! $img && isset( $defaults[ $slug ]['image'] ) ) {
				$img = $defaults[ $slug ]['image'];
			}

			// Gallery multi-images for slider
			$gallery_raw = get_post_meta( $post_id, '_kataskevi_gallery', true );
			$gallery     = array();
			if ( is_array( $gallery_raw ) && ! empty( $gallery_raw ) ) {
				$gallery = $gallery_raw;
			} elseif ( is_string( $gallery_raw ) && ! empty( $gallery_raw ) ) {
				$gallery = array_filter( array_map( 'trim', preg_split( '/[\r\n,]+/', $gallery_raw ) ) );
			}
			if ( empty( $gallery ) && isset( $defaults[ $slug ]['gallery'] ) ) {
				$gallery = $defaults[ $slug ]['gallery'];
			}
			if ( empty( $gallery ) && $img ) {
				$gallery = array( $img );
			}

			// Ensure high-performance WebP formats are used if available
			$img     = kataskevi_get_webp_url( $img );
			$gallery = array_map( 'kataskevi_get_webp_url', (array) $gallery );

			$deliverables_raw = get_post_meta( $post_id, '_kataskevi_deliverables', true );
			$deliverables     = is_array( $deliverables_raw ) ? $deliverables_raw : array_filter( array_map( 'trim', explode( "\n", (string) $deliverables_raw ) ) );

			$scope = get_post_meta( $post_id, '_kataskevi_scope', true ) ?: ( $defaults[ $slug ]['title'] ?? get_the_title() );
			$short_desc = get_the_excerpt( $post_id );
			if ( empty( $short_desc ) ) {
				$content = get_post_field( 'post_content', $post_id );
				if ( ! empty( $content ) ) {
					$short_desc = wp_trim_words( $content, 22 );
				}
			}
			if ( empty( $short_desc ) ) {
				$sol = get_post_meta( $post_id, '_kataskevi_solution', true );
				if ( ! empty( $sol ) ) {
					$short_desc = wp_trim_words( $sol, 22 );
				}
			}
			if ( empty( $short_desc ) && isset( $defaults[ $slug ]['short_desc'] ) ) {
				$short_desc = $defaults[ $slug ]['short_desc'];
			}
			if ( empty( $short_desc ) ) {
				$short_desc = $scope;
			}

			$post_title = get_the_title( $post_id );
			$title      = ! empty( $post_title ) ? $post_title : ( $defaults[ $slug ]['title'] ?? 'Project' );

			$solution = get_post_meta( $post_id, '_kataskevi_solution', true );
			if ( empty( $solution ) ) {
				$raw_content = get_post_field( 'post_content', $post_id );
				if ( ! empty( $raw_content ) ) {
					$solution = $raw_content;
				}
			}
			if ( empty( $solution ) && isset( $defaults[ $slug ]['solution'] ) ) {
				$solution = $defaults[ $slug ]['solution'];
			}

			$projects[ $slug ] = array(
				'id'           => $post_id,
				'slug'         => $slug,
				'title'        => $title,
				'permalink'    => kataskevi_get_project_url( $slug ),
				'client'       => get_post_meta( $post_id, '_kataskevi_client', true ) ?: ( $defaults[ $slug ]['client'] ?? 'Corporate Client' ),
				'location'     => get_post_meta( $post_id, '_kataskevi_location', true ) ?: ( $defaults[ $slug ]['location'] ?? 'Nigeria' ),
				'surface_area' => get_post_meta( $post_id, '_kataskevi_surface_area', true ) ?: ( $defaults[ $slug ]['surface_area'] ?? '450,000 m²' ),
				'year'         => get_post_meta( $post_id, '_kataskevi_year', true ) ?: ( $defaults[ $slug ]['year'] ?? '2014' ),
				'value'        => get_post_meta( $post_id, '_kataskevi_value', true ) ?: ( $defaults[ $slug ]['value'] ?? '$250,000' ),
				'architect'    => get_post_meta( $post_id, '_kataskevi_architect', true ) ?: ( $defaults[ $slug ]['architect'] ?? 'Jason & Perry' ),
				'sector'       => get_post_meta( $post_id, '_kataskevi_sector', true ) ?: ( $defaults[ $slug ]['sector'] ?? 'Engineering' ),
				'sector_slug'  => get_post_meta( $post_id, '_kataskevi_sector_slug', true ) ?: ( $defaults[ $slug ]['sector_slug'] ?? 'engineering' ),
				'featured'     => true,
				'metric'       => get_post_meta( $post_id, '_kataskevi_metric', true ) ?: ( $defaults[ $slug ]['metric'] ?? 'Engineered Standard' ),
				'metric_label' => get_post_meta( $post_id, '_kataskevi_metric_label', true ) ?: ( $defaults[ $slug ]['metric_label'] ?? 'Delivered Specification' ),
				'stat_2'       => get_post_meta( $post_id, '_kataskevi_stat_2', true ) ?: ( $defaults[ $slug ]['stat_2'] ?? '' ),
				'stat_2_label' => get_post_meta( $post_id, '_kataskevi_stat_2_label', true ) ?: ( $defaults[ $slug ]['stat_2_label'] ?? '' ),
				'stat_3'       => get_post_meta( $post_id, '_kataskevi_stat_3', true ) ?: ( $defaults[ $slug ]['stat_3'] ?? '' ),
				'stat_3_label' => get_post_meta( $post_id, '_kataskevi_stat_3_label', true ) ?: ( $defaults[ $slug ]['stat_3_label'] ?? '' ),
				'image'        => $img,
				'gallery'      => $gallery,
				'short_desc'   => $short_desc,
				'summary'      => $short_desc,
				'scope'        => $scope,
				'challenge'    => get_post_meta( $post_id, '_kataskevi_challenge', true ) ?: ( $defaults[ $slug ]['challenge'] ?? '' ),
				'solution'     => $solution,
				'deliverables' => ! empty( $deliverables ) ? $deliverables : ( $defaults[ $slug ]['deliverables'] ?? array() ),
			);
		}
		wp_reset_postdata();
	}

	// Fallback/fill-in for default projects to guarantee all 4 are always present
	if ( empty( $projects ) || count( $projects ) < 4 ) {
		foreach ( $defaults as $k => $item ) {
			if ( ! in_array( $k, $allowed_slugs, true ) ) {
				continue;
			}
			if ( ! isset( $projects[ $k ] ) ) {
				if ( ! empty( $sector_slug ) && 'all' !== $sector_slug && $item['sector_slug'] !== $sector_slug ) {
					continue;
				}
				$item['image']     = kataskevi_get_webp_url( $item['image'] );
				$item['gallery']   = array_map( 'kataskevi_get_webp_url', (array) $item['gallery'] );
				$item['permalink'] = kataskevi_get_project_url( $item['slug'] );
				$projects[ $k ]    = $item;
			}
		}
	}

	// Filter by sector slug if requested
	if ( ! empty( $sector_slug ) && 'all' !== $sector_slug ) {
		$filtered = array();
		foreach ( $projects as $k => $item ) {
			if ( $item['sector_slug'] === $sector_slug ) {
				$filtered[ $k ] = $item;
			}
		}
		$projects = $filtered;
	}

	// Limit count if requested
	if ( $limit > 0 && count( $projects ) > $limit ) {
		$projects = array_slice( $projects, 0, $limit, true );
	}

	return $projects;
}

/**
 * Helper to get reliable permalink for a project
 */
function kataskevi_get_project_url( $slug ) {
	$slug_map = array(
		'fire-hydrant'   => 'fire-hydrant-and-hose-reel',
		'alarm'          => 'alarm-systems',
		'access-control' => 'access-control-systems',
		'cctv'           => 'cctv-cameras',
	);
	if ( isset( $slug_map[ $slug ] ) ) {
		$slug = $slug_map[ $slug ];
	}

	$works_url = kataskevi_get_page_url( 'works', 'Our Works' );

	if ( get_option( 'permalink_structure' ) ) {
		return trailingslashit( $works_url ) . $slug . '/';
	}

	return add_query_arg( 'project', $slug, $works_url );
}

/**
 * Retrieve single project by slug
 */
function kataskevi_get_project_by_slug( $slug ) {
	$slug_map = array(
		'fire-hydrant'   => 'fire-hydrant-and-hose-reel',
		'alarm'          => 'alarm-systems',
		'access-control' => 'access-control-systems',
		'cctv'           => 'cctv-cameras',
	);
	if ( isset( $slug_map[ $slug ] ) ) {
		$slug = $slug_map[ $slug ];
	}

	$projects = kataskevi_get_projects( -1 );
	if ( isset( $projects[ $slug ] ) ) {
		return $projects[ $slug ];
	}

	$defaults = kataskevi_get_default_projects();
	if ( isset( $defaults[ $slug ] ) ) {
		$item = $defaults[ $slug ];
		$item['permalink'] = kataskevi_get_project_url( $item['slug'] );
		return $item;
	}

	return null;
}

/**
 * Seed & Synchronize default projects into WordPress database (runs once)
 */
function kataskevi_seed_default_projects() {
	if ( get_option( 'kataskevi_projects_seeded_v5' ) ) {
		return;
	}

	// Clean up legacy decommissioned projects
	$legacy_slugs = array( 'clean-agent-gas-suppression', 'industrial-deluge-ppe-program' );
	foreach ( $legacy_slugs as $l_slug ) {
		$legacy_post = get_page_by_path( $l_slug, OBJECT, 'project' );
		if ( $legacy_post ) {
			wp_delete_post( $legacy_post->ID, true );
		}
	}

	$defaults = kataskevi_get_default_projects();
	foreach ( $defaults as $slug => $data ) {
		$existing = get_page_by_path( $slug, OBJECT, 'project' );
		if ( ! $existing ) {
			$post_id = wp_insert_post(
				array(
					'post_title'     => $data['title'],
					'post_name'      => $slug,
					'post_content'   => $data['solution'],
					'post_excerpt'   => $data['short_desc'],
					'post_status'    => 'publish',
					'post_type'      => 'project',
					'comment_status' => 'closed',
				)
			);
			if ( $post_id && ! is_wp_error( $post_id ) ) {
				update_post_meta( $post_id, '_kataskevi_client', $data['client'] );
				update_post_meta( $post_id, '_kataskevi_location', $data['location'] );
				update_post_meta( $post_id, '_kataskevi_year', $data['year'] );
				update_post_meta( $post_id, '_kataskevi_sector', $data['sector'] );
				update_post_meta( $post_id, '_kataskevi_sector_slug', $data['sector_slug'] );
				update_post_meta( $post_id, '_kataskevi_featured', '1' );
				update_post_meta( $post_id, '_kataskevi_metric', $data['metric'] );
				update_post_meta( $post_id, '_kataskevi_metric_label', $data['metric_label'] );
				update_post_meta( $post_id, '_kataskevi_stat_2', $data['stat_2'] );
				update_post_meta( $post_id, '_kataskevi_stat_2_label', $data['stat_2_label'] );
				update_post_meta( $post_id, '_kataskevi_stat_3', $data['stat_3'] );
				update_post_meta( $post_id, '_kataskevi_stat_3_label', $data['stat_3_label'] );
				update_post_meta( $post_id, '_kataskevi_scope', $data['scope'] );
				update_post_meta( $post_id, '_kataskevi_challenge', $data['challenge'] );
				update_post_meta( $post_id, '_kataskevi_solution', $data['solution'] );
				update_post_meta( $post_id, '_kataskevi_image', $data['image'] );
				update_post_meta( $post_id, '_kataskevi_gallery', $data['gallery'] );
				update_post_meta( $post_id, '_kataskevi_deliverables', $data['deliverables'] );

				// Assign sector term
				wp_set_object_terms( $post_id, $data['sector'], 'project_sector' );
			}
		}
	}

	update_option( 'kataskevi_projects_seeded_v5', 1 );
}
add_action( 'init', 'kataskevi_seed_default_projects', 25 );

/**
 * Enqueue WordPress Media Uploader scripts for Project post type in WP Admin
 */
function kataskevi_admin_project_scripts( $hook ) {
	if ( in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		$screen = get_current_screen();
		if ( $screen && 'project' === $screen->post_type ) {
			wp_enqueue_media();
		}
	}
}
add_action( 'admin_enqueue_scripts', 'kataskevi_admin_project_scripts' );

/**
 * Register Meta Box for Project CPT to ensure all project fields are editable in WP Admin
 */
function kataskevi_register_project_meta_boxes() {
	add_meta_box(
		'kataskevi_project_details',
		__( 'Project Specifications & Gallery', 'kataskevi' ),
		'kataskevi_render_project_meta_box',
		'project',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'kataskevi_register_project_meta_boxes' );

function kataskevi_render_project_meta_box( $post ) {
	wp_nonce_field( 'kataskevi_save_project_meta', 'kataskevi_project_nonce' );

	$client       = get_post_meta( $post->ID, '_kataskevi_client', true );
	$location     = get_post_meta( $post->ID, '_kataskevi_location', true );
	$surface_area = get_post_meta( $post->ID, '_kataskevi_surface_area', true );
	$year         = get_post_meta( $post->ID, '_kataskevi_year', true );
	$value        = get_post_meta( $post->ID, '_kataskevi_value', true );
	$architect    = get_post_meta( $post->ID, '_kataskevi_architect', true );
	$sector       = get_post_meta( $post->ID, '_kataskevi_sector', true );
	$metric       = get_post_meta( $post->ID, '_kataskevi_metric', true );
	$metric_label = get_post_meta( $post->ID, '_kataskevi_metric_label', true );
	$stat_2       = get_post_meta( $post->ID, '_kataskevi_stat_2', true );
	$stat_2_label = get_post_meta( $post->ID, '_kataskevi_stat_2_label', true );
	$stat_3       = get_post_meta( $post->ID, '_kataskevi_stat_3', true );
	$stat_3_label = get_post_meta( $post->ID, '_kataskevi_stat_3_label', true );
	$scope        = get_post_meta( $post->ID, '_kataskevi_scope', true );
	$challenge    = get_post_meta( $post->ID, '_kataskevi_challenge', true );
	$solution     = get_post_meta( $post->ID, '_kataskevi_solution', true );

	$gallery_urls = get_post_meta( $post->ID, '_kataskevi_gallery_urls', true );
	if ( empty( $gallery_urls ) ) {
		$gal = get_post_meta( $post->ID, '_kataskevi_gallery', true );
		if ( is_array( $gal ) ) {
			$gallery_urls = implode( "\n", $gal );
		}
	}

	$deliverables = get_post_meta( $post->ID, '_kataskevi_deliverables', true );
	if ( is_array( $deliverables ) ) {
		$deliverables = implode( "\n", $deliverables );
	}
	?>
	<style>
		.kataskevi-meta-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px; margin-bottom: 16px; }
		.kataskevi-field { margin-bottom: 14px; }
		.kataskevi-field label { display: block; font-weight: 600; margin-bottom: 5px; font-size: 13px; color: #1e293b; }
		.kataskevi-field input[type="text"], .kataskevi-field textarea { width: 100%; border: 1px solid #cbd5e1; border-radius: 4px; padding: 7px 10px; font-size: 13px; box-sizing: border-box; }
		.kataskevi-field .description { font-size: 12px; color: #64748b; margin-top: 4px; }
		.kataskevi-gallery-preview-grid {
			display: flex;
			flex-wrap: wrap;
			gap: 12px;
			padding: 14px;
			background: #f8fafc;
			border: 2px dashed #cbd5e1;
			border-radius: 8px;
			min-height: 100px;
			align-items: center;
		}
		.kataskevi-gallery-item {
			position: relative;
			width: 90px;
			height: 90px;
			border-radius: 6px;
			overflow: hidden;
			border: 1px solid #cbd5e1;
			background: #ffffff;
			box-shadow: 0 2px 5px rgba(0,0,0,0.06);
		}
		.kataskevi-gallery-item img {
			width: 100%;
			height: 100%;
			object-fit: cover;
			display: block;
		}
		.kataskevi-gallery-item .remove-img-btn {
			position: absolute;
			top: 4px;
			right: 4px;
			width: 22px;
			height: 22px;
			background: rgba(220, 38, 38, 0.92);
			color: #ffffff;
			border: none;
			border-radius: 50%;
			cursor: pointer;
			font-size: 15px;
			line-height: 20px;
			text-align: center;
			padding: 0;
			box-shadow: 0 1px 3px rgba(0,0,0,0.3);
			transition: background 0.2s ease, transform 0.15s ease;
		}
		.kataskevi-gallery-item .remove-img-btn:hover {
			background: #b91c1c;
			transform: scale(1.1);
		}
		.kataskevi-gallery-empty-msg {
			color: #64748b;
			font-size: 13px;
			font-style: italic;
			width: 100%;
			text-align: center;
			padding: 20px 0;
		}
	</style>
	<div class="kataskevi-meta-wrapper">
		<div class="kataskevi-meta-grid">
			<div class="kataskevi-field">
				<label for="_kataskevi_client"><?php esc_html_e( 'Client / Facility Name', 'kataskevi' ); ?></label>
				<input type="text" id="_kataskevi_client" name="_kataskevi_client" value="<?php echo esc_attr( $client ); ?>">
			</div>
			<div class="kataskevi-field">
				<label for="_kataskevi_location"><?php esc_html_e( 'Location', 'kataskevi' ); ?></label>
				<input type="text" id="_kataskevi_location" name="_kataskevi_location" value="<?php echo esc_attr( $location ); ?>">
			</div>
			<div class="kataskevi-field">
				<label for="_kataskevi_surface_area"><?php esc_html_e( 'Surface Area (e.g. 450,000 m²)', 'kataskevi' ); ?></label>
				<input type="text" id="_kataskevi_surface_area" name="_kataskevi_surface_area" value="<?php echo esc_attr( $surface_area ); ?>">
			</div>
			<div class="kataskevi-field">
				<label for="_kataskevi_year"><?php esc_html_e( 'Completion Year (e.g. 2014)', 'kataskevi' ); ?></label>
				<input type="text" id="_kataskevi_year" name="_kataskevi_year" value="<?php echo esc_attr( $year ); ?>">
			</div>
			<div class="kataskevi-field">
				<label for="_kataskevi_value"><?php esc_html_e( 'Project Value (e.g. $250,000)', 'kataskevi' ); ?></label>
				<input type="text" id="_kataskevi_value" name="_kataskevi_value" value="<?php echo esc_attr( $value ); ?>">
			</div>
			<div class="kataskevi-field">
				<label for="_kataskevi_architect"><?php esc_html_e( 'Architect / Lead Consultant (e.g. Jason & Perry)', 'kataskevi' ); ?></label>
				<input type="text" id="_kataskevi_architect" name="_kataskevi_architect" value="<?php echo esc_attr( $architect ); ?>">
			</div>
			<div class="kataskevi-field">
				<label for="_kataskevi_sector"><?php esc_html_e( 'Sector / Category Label', 'kataskevi' ); ?></label>
				<input type="text" id="_kataskevi_sector" name="_kataskevi_sector" value="<?php echo esc_attr( $sector ); ?>">
			</div>
		</div>

		<div class="kataskevi-meta-grid">
			<div class="kataskevi-field">
				<label for="_kataskevi_metric"><?php esc_html_e( 'Key Metric (e.g. 100% Pass)', 'kataskevi' ); ?></label>
				<input type="text" id="_kataskevi_metric" name="_kataskevi_metric" value="<?php echo esc_attr( $metric ); ?>">
			</div>
			<div class="kataskevi-field">
				<label for="_kataskevi_metric_label"><?php esc_html_e( 'Key Metric Label', 'kataskevi' ); ?></label>
				<input type="text" id="_kataskevi_metric_label" name="_kataskevi_metric_label" value="<?php echo esc_attr( $metric_label ); ?>">
			</div>
			<div class="kataskevi-field">
				<label for="_kataskevi_stat_2"><?php esc_html_e( 'Stat 2', 'kataskevi' ); ?></label>
				<input type="text" id="_kataskevi_stat_2" name="_kataskevi_stat_2" value="<?php echo esc_attr( $stat_2 ); ?>">
			</div>
			<div class="kataskevi-field">
				<label for="_kataskevi_stat_2_label"><?php esc_html_e( 'Stat 2 Label', 'kataskevi' ); ?></label>
				<input type="text" id="_kataskevi_stat_2_label" name="_kataskevi_stat_2_label" value="<?php echo esc_attr( $stat_2_label ); ?>">
			</div>
			<div class="kataskevi-field">
				<label for="_kataskevi_stat_3"><?php esc_html_e( 'Stat 3', 'kataskevi' ); ?></label>
				<input type="text" id="_kataskevi_stat_3" name="_kataskevi_stat_3" value="<?php echo esc_attr( $stat_3 ); ?>">
			</div>
			<div class="kataskevi-field">
				<label for="_kataskevi_stat_3_label"><?php esc_html_e( 'Stat 3 Label', 'kataskevi' ); ?></label>
				<input type="text" id="_kataskevi_stat_3_label" name="_kataskevi_stat_3_label" value="<?php echo esc_attr( $stat_3_label ); ?>">
			</div>
		</div>

		<div class="kataskevi-field">
			<label for="_kataskevi_scope"><?php esc_html_e( 'Scope Summary', 'kataskevi' ); ?></label>
			<input type="text" id="_kataskevi_scope" name="_kataskevi_scope" value="<?php echo esc_attr( $scope ); ?>">
		</div>

		<div class="kataskevi-field">
			<label for="_kataskevi_challenge"><?php esc_html_e( 'The Operational Challenge', 'kataskevi' ); ?></label>
			<textarea id="_kataskevi_challenge" name="_kataskevi_challenge" rows="3"><?php echo esc_textarea( $challenge ); ?></textarea>
		</div>

		<div class="kataskevi-field">
			<label for="_kataskevi_solution"><?php esc_html_e( 'The Engineered Solution', 'kataskevi' ); ?></label>
			<textarea id="_kataskevi_solution" name="_kataskevi_solution" rows="4"><?php echo esc_textarea( $solution ); ?></textarea>
		</div>

		<div class="kataskevi-field kataskevi-gallery-field" style="margin-top: 12px; margin-bottom: 20px;">
			<label style="font-size: 13px; font-weight: 700; color: #0f172a; margin-bottom: 8px; display: block;">
				<?php esc_html_e( 'Project Gallery Images (Card Slider & Case Study Gallery)', 'kataskevi' ); ?>
			</label>
			
			<div id="kataskevi-gallery-preview" class="kataskevi-gallery-preview-grid"></div>

			<div class="kataskevi-gallery-actions" style="margin-top: 10px; display: flex; align-items: center; gap: 10px;">
				<button type="button" class="button button-primary" id="kataskevi-upload-gallery-btn">
					<span class="dashicons dashicons-images-alt2" style="vertical-align: text-bottom; margin-right: 4px;"></span>
					<?php esc_html_e( 'Upload / Select Gallery Images', 'kataskevi' ); ?>
				</button>
				<button type="button" class="button button-secondary" id="kataskevi-clear-gallery-btn">
					<?php esc_html_e( 'Clear All', 'kataskevi' ); ?>
				</button>
			</div>

			<textarea id="_kataskevi_gallery_urls" name="_kataskevi_gallery_urls" style="display:none;"><?php echo esc_textarea( $gallery_urls ); ?></textarea>
			<p class="description" style="margin-top: 8px;">
				<?php esc_html_e( 'Click "Upload / Select Gallery Images" to upload photos from your computer or choose from your WordPress Media Library. Selected photos automatically populate the card preview slider and the project details gallery.', 'kataskevi' ); ?>
			</p>
		</div>

		<div class="kataskevi-field">
			<label for="_kataskevi_deliverables"><?php esc_html_e( 'Deliverables / Scope Checklist (one item per line)', 'kataskevi' ); ?></label>
			<textarea id="_kataskevi_deliverables" name="_kataskevi_deliverables" rows="4"><?php echo esc_textarea( $deliverables ); ?></textarea>
		</div>
	</div>

	<script>
	jQuery(document).ready(function($) {
		var galleryFrame;
		var $textarea = $('#_kataskevi_gallery_urls');
		var $preview  = $('#kataskevi-gallery-preview');

		function getUrls() {
			var raw = $textarea.val().trim();
			return raw ? raw.split('\n').map(function(s) { return s.trim(); }).filter(Boolean) : [];
		}

		function renderPreviews() {
			var urls = getUrls();
			$preview.empty();
			if (urls.length === 0) {
				$preview.append('<div class="kataskevi-gallery-empty-msg"><?php echo esc_js( __( 'No gallery images selected yet. Click "Upload / Select Gallery Images" below to add photos.', 'kataskevi' ) ); ?></div>');
				return;
			}
			urls.forEach(function(url, index) {
				var $item = $('<div class="kataskevi-gallery-item" data-index="' + index + '"></div>');
				var $img  = $('<img>').attr('src', url);
				var $del  = $('<button type="button" class="remove-img-btn" title="<?php echo esc_js( __( 'Remove image', 'kataskevi' ) ); ?>">&times;</button>');
				$del.on('click', function(e) {
					e.preventDefault();
					urls.splice(index, 1);
					$textarea.val(urls.join('\n'));
					renderPreviews();
				});
				$item.append($img).append($del);
				$preview.append($item);
			});
		}

		renderPreviews();

		$('#kataskevi-upload-gallery-btn').on('click', function(e) {
			e.preventDefault();
			if (galleryFrame) {
				galleryFrame.open();
				return;
			}

			galleryFrame = wp.media({
				title: '<?php echo esc_js( __( 'Select or Upload Project Gallery Images', 'kataskevi' ) ); ?>',
				button: {
					text: '<?php echo esc_js( __( 'Add to Project Gallery', 'kataskevi' ) ); ?>'
				},
				multiple: true,
				library: {
					type: 'image'
				}
			});

			galleryFrame.on('select', function() {
				var selection = galleryFrame.state().get('selection');
				var urls = getUrls();
				selection.each(function(attachment) {
					var json = attachment.toJSON();
					var url  = json.url;
					if (json.sizes && json.sizes.large) {
						url = json.sizes.large.url;
					}
					if (url && urls.indexOf(url) === -1) {
						urls.push(url);
					}
				});
				$textarea.val(urls.join('\n'));
				renderPreviews();
			});

			galleryFrame.open();
		});

		$('#kataskevi-clear-gallery-btn').on('click', function(e) {
			e.preventDefault();
			if (confirm('<?php echo esc_js( __( 'Are you sure you want to remove all gallery images from this project?', 'kataskevi' ) ); ?>')) {
				$textarea.val('');
				renderPreviews();
			}
		});
	});
	</script>
	<?php
}

function kataskevi_save_project_meta_box( $post_id ) {
	if ( ! isset( $_POST['kataskevi_project_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['kataskevi_project_nonce'] ), 'kataskevi_save_project_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$text_fields = array(
		'_kataskevi_client',
		'_kataskevi_location',
		'_kataskevi_surface_area',
		'_kataskevi_year',
		'_kataskevi_value',
		'_kataskevi_architect',
		'_kataskevi_sector',
		'_kataskevi_metric',
		'_kataskevi_metric_label',
		'_kataskevi_stat_2',
		'_kataskevi_stat_2_label',
		'_kataskevi_stat_3',
		'_kataskevi_stat_3_label',
		'_kataskevi_scope',
	);

	foreach ( $text_fields as $field ) {
		if ( isset( $_POST[ $field ] ) ) {
			update_post_meta( $post_id, $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
		}
	}

	$textarea_fields = array(
		'_kataskevi_challenge',
		'_kataskevi_solution',
	);

	foreach ( $textarea_fields as $field ) {
		if ( isset( $_POST[ $field ] ) ) {
			update_post_meta( $post_id, $field, sanitize_textarea_field( wp_unslash( $_POST[ $field ] ) ) );
		}
	}

	if ( isset( $_POST['_kataskevi_gallery_urls'] ) ) {
		$raw_gallery = sanitize_textarea_field( wp_unslash( $_POST['_kataskevi_gallery_urls'] ) );
		update_post_meta( $post_id, '_kataskevi_gallery_urls', $raw_gallery );
		$gallery_array = array_filter( array_map( 'esc_url_raw', array_map( 'trim', explode( "\n", $raw_gallery ) ) ) );
		update_post_meta( $post_id, '_kataskevi_gallery', array_values( $gallery_array ) );
	}

	if ( isset( $_POST['_kataskevi_deliverables'] ) ) {
		$raw_del = sanitize_textarea_field( wp_unslash( $_POST['_kataskevi_deliverables'] ) );
		$del_array = array_filter( array_map( 'sanitize_text_field', array_map( 'trim', explode( "\n", $raw_del ) ) ) );
		update_post_meta( $post_id, '_kataskevi_deliverables', array_values( $del_array ) );
	}
}
add_action( 'save_post_project', 'kataskevi_save_project_meta_box' );

/**
 * Return WebP URL if available, otherwise original URL.
 *
 * @param string $image_url The image URL.
 * @return string The WebP URL if the file exists on disk, otherwise original URL.
 */
function kataskevi_get_webp_url( $image_url ) {
	if ( empty( $image_url ) ) {
		return $image_url;
	}

	$theme_uri = get_template_directory_uri();
	$theme_dir = KATASKEVI_DIR;

	if ( 0 === strpos( $image_url, $theme_uri ) ) {
		$rel_path  = substr( $image_url, strlen( $theme_uri ) );
		$file_path = $theme_dir . $rel_path;
		$webp_path = preg_replace( '/\.(jpg|jpeg|png)$/i', '.webp', $file_path );
		$webp_url  = preg_replace( '/\.(jpg|jpeg|png)$/i', '.webp', $image_url );

		if ( file_exists( $webp_path ) ) {
			return $webp_url;
		}
	}

	return $image_url;
}

/**
 * Handle AJAX quote & site survey form submissions
 */
function kataskevi_handle_inquiry_submission() {
	$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : ( isset( $_POST['kataskevi_inquiry_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['kataskevi_inquiry_nonce'] ) ) : '' );
	if ( ! wp_verify_nonce( $nonce, 'kataskevi_inquiry_nonce' ) && ! wp_verify_nonce( $nonce, 'kataskevi_inquiry_action' ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Security check failed. Please refresh the page and try again.', 'kataskevi' ) ) );
	}

	$name         = isset( $_POST['fullname'] ) ? sanitize_text_field( wp_unslash( $_POST['fullname'] ) ) : '';
	$email        = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$phone        = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$organization = isset( $_POST['organization'] ) ? sanitize_text_field( wp_unslash( $_POST['organization'] ) ) : ( isset( $_POST['sector'] ) ? sanitize_text_field( wp_unslash( $_POST['sector'] ) ) : '' );
	$service      = isset( $_POST['service'] ) ? sanitize_text_field( wp_unslash( $_POST['service'] ) ) : '';
	$message      = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

	if ( empty( $name ) || empty( $email ) || empty( $message ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Please complete all required fields (Full Name, Work Email, and Project Scope).', 'kataskevi' ) ) );
	}

	if ( ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Please provide a valid work email address.', 'kataskevi' ) ) );
	}

	// Recipient email from Form Settings, fallback to company email or admin email.
	$admin_email = get_option( 'kataskevi_form_recipient_email', get_theme_mod( 'kataskevi_form_recipient_email', get_theme_mod( 'kataskevi_email', get_option( 'admin_email' ) ) ) );
	if ( empty( $admin_email ) || ! is_email( $admin_email ) ) {
		$admin_email = get_option( 'admin_email' );
	}

	$brand_name     = kataskevi_get_brand_name();
	$subject_prefix = get_option( 'kataskevi_form_email_subject', get_theme_mod( 'kataskevi_form_email_subject', '' ) );
	if ( empty( $subject_prefix ) ) {
		/* translators: 1: Brand name, 2: Customer name */
		$subject = sprintf( '[%1$s Inquiry] New Consultation Request from %2$s', $brand_name, $name );
	} else {
		$subject = sprintf( '%1$s from %2$s', $subject_prefix, $name );
	}

	$body  = "You have received a new consultation / site survey request:\n\n";
	$body .= 'Full Name: ' . $name . "\n";
	$body .= 'Email: ' . $email . "\n";
	$body .= 'Phone: ' . ( ! empty( $phone ) ? $phone : esc_html__( 'Not provided', 'kataskevi' ) ) . "\n";
	$body .= 'Company / Organization: ' . ( ! empty( $organization ) ? $organization : esc_html__( 'Not provided', 'kataskevi' ) ) . "\n";
	$body .= 'Service Required: ' . ( ! empty( $service ) ? $service : esc_html__( 'General Audit / Inquiries', 'kataskevi' ) ) . "\n\n";
	$body .= "Project Scope / Notes:\n" . $message . "\n\n";
	$body .= "---\nSent from: " . esc_url( home_url( '/' ) ) . "\n";

	$headers = array(
		'Content-Type: text/plain; charset=UTF-8',
		'Reply-To: ' . $name . ' <' . $email . '>',
	);

	// Send email (falls back gracefully in local dev).
	@wp_mail( $admin_email, $subject, $body, $headers );

	// Store in options as recent inquiries log so admin never loses a lead even without SMTP.
	$inquiries = get_option( 'kataskevi_recent_inquiries', array() );
	if ( ! is_array( $inquiries ) ) {
		$inquiries = array();
	}
	array_unshift(
		$inquiries,
		array(
			'date'         => current_time( 'mysql' ),
			'name'         => $name,
			'email'        => $email,
			'phone'        => $phone,
			'organization' => $organization,
			'service'      => $service,
			'message'      => $message,
		)
	);
	$inquiries = array_slice( $inquiries, 0, 50 );
	update_option( 'kataskevi_recent_inquiries', $inquiries, false );

	$default_success_msg = esc_html__( 'Thank you! Your inquiry has been received. A Kataskevi technical specialist will contact you within 24 hours.', 'kataskevi' );
	$success_msg         = get_option( 'kataskevi_form_success_msg', get_theme_mod( 'kataskevi_form_success_msg', $default_success_msg ) );
	if ( empty( $success_msg ) ) {
		$success_msg = $default_success_msg;
	}

	wp_send_json_success( array(
		'message' => esc_html( $success_msg ),
	) );
}
add_action( 'wp_ajax_kataskevi_submit_inquiry', 'kataskevi_handle_inquiry_submission' );
add_action( 'wp_ajax_nopriv_kataskevi_submit_inquiry', 'kataskevi_handle_inquiry_submission' );

/**
 * Safely parse and normalize Google Maps embed input into a valid embed URL.
 * Accepts full iframe embed code, raw embed URL, or partial Google pb snippets.
 *
 * @param string $input Raw input (iframe HTML, embed URL, or pb snippet).
 * @return string Clean embed URL or empty string.
 */
function kataskevi_parse_map_url( $input ) {
	if ( empty( $input ) ) {
		return '';
	}

	$trimmed = trim( wp_unslash( $input ) );

	// 1. Extract src attribute if full iframe tag was provided.
	if ( preg_match( '/src=["\']([^"\']+)["\']/i', $trimmed, $matches ) ) {
		return esc_url_raw( $matches[1] );
	}

	// 2. Direct URL provided (https://...).
	if ( preg_match( '/https?:\/\/[^\s"\'<>]+/i', $trimmed, $matches ) ) {
		return esc_url_raw( $matches[0] );
	}

	// 3. Fallback: if partial Google pb parameter was pasted (e.g. !1m or !3m).
	if ( preg_match( '/(!1m|!3m|pb=!)[^\s"\'<>]+/i', $trimmed, $matches ) ) {
		$pb = ltrim( $matches[0], 'pb=' );
		return esc_url_raw( 'https://www.google.com/maps/embed?pb=' . $pb );
	}

	return '';
}
