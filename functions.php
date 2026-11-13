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

define( 'KATASKEVI_VERSION', '2.0.0' );
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

	// Custom logo support.
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 80,
			'width'       => 280,
			'flex-width'  => true,
			'flex-height' => true,
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
	wp_enqueue_style(
		'kataskevi-main-style',
		KATASKEVI_URI . '/assets/css/main.css',
		array(),
		KATASKEVI_VERSION
	);

	// Parent fallback / header style
	wp_enqueue_style(
		'kataskevi-style',
		get_stylesheet_uri(),
		array( 'kataskevi-main-style' ),
		KATASKEVI_VERSION
	);

	// Main interactive JavaScript
	wp_enqueue_script(
		'kataskevi-main-js',
		KATASKEVI_URI . '/assets/js/main.js',
		array(),
		KATASKEVI_VERSION,
		true
	);

	// Localize script for AJAX quotes and forms
	wp_localize_script(
		'kataskevi-main-js',
		'kataskeviData',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'kataskevi_inquiry_nonce' ),
			'siteUrl' => esc_url( home_url( '/' ) ),
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
			'url'   => get_theme_mod( 'kataskevi_linkedin', 'https://linkedin.com/company/kataskevi' ),
			'icon'  => 'linkedin',
		),
		'twitter' => array(
			'name'  => 'X (Twitter)',
			'url'   => get_theme_mod( 'kataskevi_twitter', 'https://x.com/kataskevi' ),
			'icon'  => 'twitter',
		),
		'facebook' => array(
			'name'  => 'Facebook',
			'url'   => get_theme_mod( 'kataskevi_facebook', 'https://facebook.com/kataskevi' ),
			'icon'  => 'facebook',
		),
		'instagram' => array(
			'name'  => 'Instagram',
			'url'   => get_theme_mod( 'kataskevi_instagram', 'https://instagram.com/kataskevi' ),
			'icon'  => 'instagram',
		),
		'youtube' => array(
			'name'  => 'YouTube',
			'url'   => get_theme_mod( 'kataskevi_youtube', 'https://youtube.com/@kataskevi' ),
			'icon'  => 'youtube',
		),
		'whatsapp' => array(
			'name'  => 'WhatsApp',
			'url'   => get_theme_mod( 'kataskevi_whatsapp', 'https://wa.me/2348005282753' ),
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
	return array(
		'fire-suppression' => array(
			'id'          => 'fire-suppression',
			'title'       => 'Fire Suppression Systems',
			'badge'       => 'Life & Asset Safety',
			'short_desc'  => 'Clean-agent suppression for critical and unoccupied spaces.',
			'description' => 'Complete design, supply, installation and maintenance of clean-agent fire suppression systems that protect high-value equipment without damaging it.',
			'points'      => array( 'FM 200 systems', 'CO2 systems', 'NOVEC 1230 systems', 'Detection & release panels' ),
			'icon'        => 'flame',
		),
		'fire-alarm-safety' => array(
			'id'          => 'fire-alarm-safety',
			'title'       => 'Fire Alarm & Safety Equipment',
			'badge'       => 'Detection & Containment',
			'short_desc'  => 'Early detection and first-response readiness.',
			'description' => 'Conventional and addressable fire detection plus the hydrant and hose reel infrastructure and safety equipment your site needs to respond fast.',
			'points'      => array( 'Fire alarm systems', 'Fire hydrants', 'Hose reels', 'Safety equipment' ),
			'icon'        => 'bell-alert',
		),
		'security-cctv' => array(
			'id'          => 'security-cctv',
			'title'       => 'Electronic Security & CCTV',
			'badge'       => 'Intelligence & Surveillance',
			'short_desc'  => 'Surveillance that protects people, assets and operations.',
			'description' => 'End-to-end electronic security: camera systems designed around your site, recording and monitoring infrastructure, and ongoing support.',
			'points'      => array( 'CCTV design & installation', 'IP & analog camera systems', 'Recording & monitoring', 'Perimeter security' ),
			'icon'        => 'video-camera',
		),
		'it-site-preparation' => array(
			'id'          => 'it-site-preparation',
			'title'       => 'IT / Telecoms Site Preparation',
			'badge'       => 'Mission Critical Infrastructure',
			'short_desc'  => 'Rooms built to keep critical infrastructure running.',
			'description' => 'Turnkey preparation of IT and telecoms facilities — from the floor beneath your racks to the ceiling and walls that protect them.',
			'points'      => array( 'Raised access floors', 'Fire-rated ceilings', 'Wall cladding', 'Site fit-out' ),
			'icon'        => 'server',
		),
		'access-control' => array(
			'id'          => 'access-control',
			'title'       => 'Access Control & Time Attendance',
			'badge'       => 'Identity & Verification',
			'short_desc'  => 'Control who enters, and account for every hour.',
			'description' => 'Integrated access control and time attendance systems that secure your premises while giving you accurate, auditable workforce data.',
			'points'      => array( 'Card, fob & biometric access', 'Time attendance terminals', 'Visitor management', 'Central reporting' ),
			'icon'        => 'finger-print',
		),
		'hotel-locks' => array(
			'id'          => 'hotel-locks',
			'title'       => 'Hotel Lock Systems',
			'badge'       => 'Hospitality Tech',
			'short_desc'  => 'Guest experience and security, in one system.',
			'description' => 'Hospitality-grade electronic locking systems — supply, installation and integration with your front-office and energy management systems.',
			'points'      => array( 'RFID & smart card locks', 'Front-desk integration', 'Master key planning', 'Maintenance & support' ),
			'icon'        => 'key',
		),
		'ppe' => array(
			'id'          => 'ppe',
			'title'       => 'Personal Protective Equipment',
			'badge'       => 'Workplace Safety Compliance',
			'short_desc'  => 'Certified PPE, supplied when you need it.',
			'description' => 'Quality personal protective equipment for energy, industrial and construction environments — specified correctly and delivered reliably.',
			'points'      => array( 'Head-to-toe protection', 'Site-specific specifications', 'Bulk supply', 'Replacement programmes' ),
			'icon'        => 'shield-check',
		),
	);
}

/**
 * Data provider: Core Lifecycle Capabilities
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
 * Data provider: Philosophy Pillars
 */
function kataskevi_get_pillars() {
	return array(
		array(
			'title'       => 'Safety first, always',
			'desc'        => 'Every system we design and install exists to protect people, assets and continuity of operations.',
			'icon'        => 'shield-check',
		),
		array(
			'title'       => 'Engineered, not guessed',
			'desc'        => 'Site surveys, precise specifications and structured commissioning — the system fits the facility, because it was designed for it.',
			'icon'        => 'compass',
		),
		array(
			'title'       => 'One accountable partner',
			'desc'        => 'Design, supply, installation and maintenance under one roof — no finger-pointing between vendors.',
			'icon'        => 'handshake',
		),
		array(
			'title'       => 'Built around your profit',
			'desc'        => 'In a busy, changing industry, our variety of services supports our clientele towards maximising profit.',
			'icon'        => 'users',
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
 * Helper to render SVG icons inline
 */
function kataskevi_render_icon( $name, $class = 'icon' ) {
	$icons = array(
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
		'arrow-right' => '<svg class="' . esc_attr( $class ) . '" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>',
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
 * Data provider: Default Seeded Projects & Case Studies
 */
function kataskevi_get_default_projects() {
	$img_base = get_template_directory_uri() . '/assets/images/projects/';

	return array(
		'apex-tower-data-center' => array(
			'id'           => 1,
			'slug'         => 'apex-tower-data-center',
			'title'        => 'Apex Tier III Data Center Infrastructure',
			'client'       => 'Apex Cloud & Colocation Services',
			'location'     => 'Victoria Island, Lagos',
			'year'         => '2025',
			'sector'       => 'Data Centers & Telecoms',
			'sector_slug'  => 'datacenter',
			'featured'     => true,
			'metric'       => '99.999% Uptime',
			'metric_label' => 'Life-Safety Protection',
			'stat_2'       => '1,400 m²',
			'stat_2_label' => 'Clean Agent Zone',
			'stat_3'       => '0.8s',
			'stat_3_label' => 'Discharge Response',
			'image'        => $img_base . 'datacenter.jpg',
			'short_desc'   => 'Turnkey delivery of FM-200 clean-agent suppression, anti-static raised flooring, and high-sensitivity VESDA smoke detection across four mission-critical halls.',
			'scope'        => 'Turnkey Data Hall Protection & Site Preparation',
			'challenge'    => 'The high-density colocation facility required zero-tolerance fire risk management. Standard water sprinkler systems were completely impermissible due to irreparable equipment damage, and the facility demanded 120-minute physical fire containment without disrupting live tenant feeds.',
			'solution'     => 'Kataskevi engineered an integrated FM-200 clean-gas suppression matrix with dual-action solenoid actuators, VESDA laser aspirating smoke detectors, and fire-resistant raised airflow access floors. We established sub-floor acoustic dampening, automated damper shutdown, and full BMS integration.',
			'deliverables' => array(
				'Synchronized FM-200 gaseous clean agent cylinder arrays',
				'VESDA laser air-sampling smoke detection with sub-second alert',
				'600mm heavy-duty anti-static raised access floor panels',
				'Fire-rated wall cladding and thermal barrier ceiling system',
				'Integrated Building Management System (BMS) telemetry'
			),
		),
		'meridian-logistics-hub' => array(
			'id'           => 2,
			'slug'         => 'meridian-logistics-hub',
			'title'        => 'Meridian Automated Logistics Hub',
			'client'       => 'Meridian Trans-African Freight Ltd',
			'location'     => 'Ikeja Industrial Zone, Lagos',
			'year'         => '2025',
			'sector'       => 'Logistics & Warehousing',
			'sector_slug'  => 'logistics',
			'featured'     => true,
			'metric'       => '240 Nodes',
			'metric_label' => 'Integrated CCTV & ANPR',
			'stat_2'       => '35,000 m²',
			'stat_2_label' => 'Compound Covered',
			'stat_3'       => '100%',
			'stat_3_label' => 'Perimeter Coverage',
			'image'        => $img_base . 'logistics.jpg',
			'short_desc'   => 'Site-wide IP electronic surveillance, automated license plate recognition (ANPR) at cargo gates, and full-perimeter infrared tripwire beam detection.',
			'challenge'    => 'A 35,000 m² high-turnover cargo depot suffered from freight processing congestion and surveillance blind spots. The client needed automated truck gate entry verification and unified video coverage of dispatch docks without cargo delay.',
			'solution'     => 'We deployed a high-capacity fiber-optic network supporting 240 4K IP cameras with motorized zoom, dual-lane ANPR readers connected to hydraulic road blockers, and perimeter intrusion beams with AI false-alarm suppression.',
			'deliverables' => array(
				'240 Ultra-HD IP security cameras with long-range night vision',
				'Automatic Number Plate Recognition (ANPR) barrier controls',
				'Multi-gigabit fiber backbone linking 6 guard outposts to central SOC',
				'Active infrared perimeter tripwires with zero-nuisance filtering',
				'24/7 technical dispatch and quarterly maintenance SLA'
			),
		),
		'centennial-financial-hq' => array(
			'id'           => 3,
			'slug'         => 'centennial-financial-hq',
			'title'        => 'Centennial Financial Tower',
			'client'       => 'Centennial Banking Group',
			'location'     => 'Marina CBD, Lagos',
			'year'         => '2024',
			'sector'       => 'Commercial & Corporate',
			'sector_slug'  => 'commercial',
			'featured'     => true,
			'metric'       => '1,800 Users',
			'metric_label' => 'Biometric Verification',
			'stat_2'       => '18 Floors',
			'stat_2_label' => 'Addressable Life Safety',
			'stat_3'       => '0.2s',
			'stat_3_label' => 'Turnstile Clearance',
			'image'        => $img_base . 'commercial.jpg',
			'short_desc'   => 'High-throughput optical speed turnstiles, multi-factor biometric access, and multi-zone addressable fire alarms across an 18-storey banking headquarters.',
			'challenge'    => 'Controlling peak morning transit for over 1,800 personnel while satisfying stringent Central Bank security standards and delivering phased fire evacuation across an 18-floor high-rise.',
			'solution'     => 'Kataskevi installed 14 lanes of optical glass speed turnstiles with dual facial-recognition and encrypted smartcard readers, unified with an addressable multi-loop voice evacuation fire system.',
			'deliverables' => array(
				'14 optical speed turnstile lanes with touchless facial recognition',
				'Addressable multi-loop fire alarm system with phased voice evacuation',
				'Enterprise time & attendance platform linked directly to corporate payroll',
				'Magnetic fire exit lock release certified to life safety standards',
				'Executive boardroom and cash vault biometric authorization'
			),
		),
		'helios-petrochemical-terminal' => array(
			'id'           => 4,
			'slug'         => 'helios-petrochemical-terminal',
			'title'        => 'Helios Coastal Oil & Gas Terminal',
			'client'       => 'Helios Energy Global',
			'location'     => 'Port Harcourt, Rivers State',
			'year'         => '2024',
			'sector'       => 'Industrial & Energy',
			'sector_slug'  => 'industrial',
			'featured'     => false,
			'metric'       => 'ATEX Zone 1',
			'metric_label' => 'Explosion-Proof Certified',
			'stat_2'       => '48 Hydrants',
			'stat_2_label' => 'High-Pressure Fire Ring',
			'stat_3'       => '100% PPE',
			'stat_3_label' => 'Workforce Compliance',
			'image'        => $img_base . 'industrial.jpg',
			'short_desc'   => 'Intrinsically safe flame detection, high-pressure foam deluge suppression, and custom industrial PPE provisioning for hazardous petrochemical processing zones.',
			'challenge'    => 'Corrosive coastal sea atmosphere and volatile hydrocarbon fumes demanded ATEX-certified, intrinsically safe hardware capable of surviving extreme marine operating conditions.',
			'solution'     => 'Designed and commissioned optical triple-spectrum IR3 flame detectors, automated water-foam deluge monitor cannons, and supplied certified Category 3 PPE and breathing apparatus.',
			'deliverables' => array(
				'Triple-spectrum infrared (IR3) explosion-proof flame detectors',
				'High-pressure automated foam deluge fire suppression systems',
				'Pressurized industrial fire hydrant ring main with diesel booster pumps',
				'Certified Category 3 flame-retardant PPE suits and SCBA units',
				'Hydrostatic cylinder re-testing and bi-annual safety recertification'
			),
		),
		'grand-azure-resort' => array(
			'id'           => 5,
			'slug'         => 'grand-azure-resort',
			'title'        => 'Grand Azure Luxury Resort & Suites',
			'client'       => 'Azure Hospitality Group',
			'location'     => 'Lekki Coastal Peninsula',
			'year'         => '2025',
			'sector'       => 'Hospitality & Leisure',
			'sector_slug'  => 'hospitality',
			'featured'     => false,
			'metric'       => '450 Rooms',
			'metric_label' => 'RFID Smart Locks',
			'stat_2'       => 'PMS Sync',
			'stat_2_label' => 'Opera Cloud Integrated',
			'stat_3'       => 'Zero Fail',
			'stat_3_label' => 'Marine Weatherproof',
			'image'        => $img_base . 'hospitality.jpg',
			'short_desc'   => 'Marine-grade contactless RFID locking, smart elevator floor dispatch, and architectural public area surveillance integrated with front-office management.',
			'challenge'    => 'Severe salt-spray corrosion degraded standard lock components. The property required refined architectural finishes (matte graphite) that withstand maritime humidity while integrating with Opera PMS.',
			'solution'     => 'Supplied and installed 450 IP65 marine-grade contactless RFID door locks with mobile key support, elevator floor controllers, and discreet low-profile corridor dome cameras.',
			'deliverables' => array(
				'450 marine-grade stainless steel RFID guest door locks',
				'Direct bi-directional interface with Oracle Opera PMS',
				'Smart elevator destination control with keycard permissions',
				'Discreet architectural dome CCTV across lobby and dining areas',
				'Master-keying plan and 24-hour rapid hardware replacement reserve'
			),
		),
		'st-jude-medical-center' => array(
			'id'           => 6,
			'slug'         => 'st-jude-medical-center',
			'title'        => 'St. Jude Specialty Hospital Complex',
			'client'       => 'St. Jude Healthcare Foundation',
			'location'     => 'Central District, Abuja FCT',
			'year'         => '2024',
			'sector'       => 'Healthcare',
			'sector_slug'  => 'healthcare',
			'featured'     => false,
			'metric'       => '6 Theatres',
			'metric_label' => 'Novec 1230 Clean Gas',
			'stat_2'       => '12 Wards',
			'stat_2_label' => 'Hygienic Wave Access',
			'stat_3'       => '100% Clean',
			'stat_3_label' => 'Zero-Residue Certified',
			'image'        => $img_base . 'healthcare.jpg',
			'short_desc'   => 'Residue-free Novec 1230 gas suppression for MRI/CT suites, hygienic touchless wave-to-open access control, and hospital-wide life safety paging.',
			'challenge'    => 'Protecting expensive medical imaging suites and vulnerable surgical wards where chemical residue or harsh emergency alarms could endanger patients and sensitive instruments.',
			'solution'     => 'Engineered clean-agent Novec 1230 fire suppression with non-conductive, residue-free discharge. Installed hygienic wave-to-open door sensors and zoned visual life-safety beacons.',
			'deliverables' => array(
				'Novec 1230 clean-gas fire suppression for surgical and MRI rooms',
				'Hygienic touchless wave-to-open sensors for sterile corridors',
				'Phased visual strobe and acoustic warning system for clinical wards',
				'Neonatal anti-abduction perimeter security integration',
				'Emergency 2-hour SLA on-call engineering coverage'
			),
		),
	);
}

/**
 * Retrieve projects: checks WordPress CPT posts first; falls back to default projects
 */
function kataskevi_get_projects( $limit = -1, $featured_only = false, $sector_slug = '' ) {
	$args = array(
		'post_type'      => 'project',
		'post_status'    => 'publish',
		'posts_per_page' => $limit,
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
	$projects = array();

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$post_id = get_the_ID();
			$slug    = get_post_field( 'post_name', $post_id );

			$img = get_the_post_thumbnail_url( $post_id, 'large' );
			if ( ! $img ) {
				$img = get_post_meta( $post_id, '_kataskevi_image', true );
			}
			if ( ! $img ) {
				$img = get_template_directory_uri() . '/assets/images/hero.jpg';
			}

			$deliverables_raw = get_post_meta( $post_id, '_kataskevi_deliverables', true );
			$deliverables = is_array( $deliverables_raw ) ? $deliverables_raw : array_filter( array_map( 'trim', explode( "\n", (string) $deliverables_raw ) ) );

			$projects[ $slug ] = array(
				'id'           => $post_id,
				'slug'         => $slug,
				'title'        => get_the_title(),
				'permalink'    => get_permalink( $post_id ),
				'client'       => get_post_meta( $post_id, '_kataskevi_client', true ) ?: 'Corporate Client',
				'location'     => get_post_meta( $post_id, '_kataskevi_location', true ) ?: 'Nigeria',
				'year'         => get_post_meta( $post_id, '_kataskevi_year', true ) ?: gmdate( 'Y' ),
				'sector'       => get_post_meta( $post_id, '_kataskevi_sector', true ) ?: 'Engineering',
				'sector_slug'  => get_post_meta( $post_id, '_kataskevi_sector_slug', true ) ?: 'engineering',
				'featured'     => (bool) get_post_meta( $post_id, '_kataskevi_featured', true ),
				'metric'       => get_post_meta( $post_id, '_kataskevi_metric', true ) ?: 'Engineered Standard',
				'metric_label' => get_post_meta( $post_id, '_kataskevi_metric_label', true ) ?: 'Delivered Specification',
				'stat_2'       => get_post_meta( $post_id, '_kataskevi_stat_2', true ) ?: '',
				'stat_2_label' => get_post_meta( $post_id, '_kataskevi_stat_2_label', true ) ?: '',
				'stat_3'       => get_post_meta( $post_id, '_kataskevi_stat_3', true ) ?: '',
				'stat_3_label' => get_post_meta( $post_id, '_kataskevi_stat_3_label', true ) ?: '',
				'image'        => $img,
				'short_desc'   => get_the_excerpt() ?: wp_trim_words( get_the_content(), 25 ),
				'scope'        => get_post_meta( $post_id, '_kataskevi_scope', true ) ?: get_the_title(),
				'challenge'    => get_post_meta( $post_id, '_kataskevi_challenge', true ) ?: '',
				'solution'     => get_post_meta( $post_id, '_kataskevi_solution', true ) ?: get_the_content(),
				'deliverables' => $deliverables,
			);
		}
		wp_reset_postdata();
	}

	// Fallback to default project list if query returned empty
	if ( empty( $projects ) ) {
		$defaults = kataskevi_get_default_projects();
		$projects = array();
		$count    = 0;

		foreach ( $defaults as $k => $item ) {
			if ( $featured_only && empty( $item['featured'] ) ) {
				continue;
			}
			if ( ! empty( $sector_slug ) && 'all' !== $sector_slug && $item['sector_slug'] !== $sector_slug ) {
				continue;
			}
			$item['permalink'] = home_url( '/works/' . $item['slug'] . '/' );
			$projects[ $k ] = $item;
			$count++;
			if ( $limit > 0 && $count >= $limit ) {
				break;
			}
		}
	}

	return $projects;
}

/**
 * Retrieve single project by slug
 */
function kataskevi_get_project_by_slug( $slug ) {
	$projects = kataskevi_get_projects( -1 );
	if ( isset( $projects[ $slug ] ) ) {
		return $projects[ $slug ];
	}

	$defaults = kataskevi_get_default_projects();
	if ( isset( $defaults[ $slug ] ) ) {
		$item = $defaults[ $slug ];
		$item['permalink'] = home_url( '/works/' . $item['slug'] . '/' );
		return $item;
	}

	return null;
}

/**
 * Seed default projects into WordPress database
 */
function kataskevi_seed_default_projects() {
	if ( get_option( 'kataskevi_projects_seeded_v1' ) ) {
		return;
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
				update_post_meta( $post_id, '_kataskevi_featured', $data['featured'] ? '1' : '0' );
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
				update_post_meta( $post_id, '_kataskevi_deliverables', $data['deliverables'] );

				// Assign or create sector term
				wp_set_object_terms( $post_id, $data['sector'], 'project_sector' );
			}
		}
	}

	update_option( 'kataskevi_projects_seeded_v1', 1 );
}

/**
 * Handle AJAX quote & site survey form submissions
 */
function kataskevi_handle_inquiry_submission() {
	check_ajax_referer( 'kataskevi_inquiry_nonce', 'nonce' );

	$name     = isset( $_POST['fullname'] ) ? sanitize_text_field( wp_unslash( $_POST['fullname'] ) ) : '';
	$email    = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$phone    = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$service  = isset( $_POST['service'] ) ? sanitize_text_field( wp_unslash( $_POST['service'] ) ) : '';
	$sector   = isset( $_POST['sector'] ) ? sanitize_text_field( wp_unslash( $_POST['sector'] ) ) : '';
	$message  = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

	if ( empty( $name ) || empty( $email ) || empty( $phone ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Please complete all required fields.', 'kataskevi' ) ) );
	}

	$admin_email = get_theme_mod( 'kataskevi_email', get_option( 'admin_email' ) );
	$subject     = sprintf( '[Kataskevi Inquiry] New Request from %s', $name );
	$body        = "You have received a new consultation / site survey request:\n\n";
	$body       .= "Full Name: $name\n";
	$body       .= "Email: $email\n";
	$body       .= "Phone: $phone\n";
	$body       .= "Service Required: $service\n";
	$body       .= "Industry Sector: $sector\n";
	$body       .= "Project Scope / Notes:\n$message\n\n";
	$body       .= "Sent from: " . home_url();

	$headers = array( 'Content-Type: text/plain; charset=UTF-8', "Reply-To: $name <$email>" );

	// Send email (falls back gracefully in local dev)
	@wp_mail( $admin_email, $subject, $body, $headers );

	wp_send_json_success( array(
		'message' => esc_html__( 'Thank you! Your inquiry has been received. A Kataskevi technical specialist will contact you within 24 hours.', 'kataskevi' ),
	) );
}
add_action( 'wp_ajax_kataskevi_submit_inquiry', 'kataskevi_handle_inquiry_submission' );
add_action( 'wp_ajax_nopriv_kataskevi_submit_inquiry', 'kataskevi_handle_inquiry_submission' );
