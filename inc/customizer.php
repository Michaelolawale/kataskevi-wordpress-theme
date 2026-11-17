<?php
/**
 * Kataskevi Theme Customizer
 *
 * Implements comprehensive Theme Customizer settings, controls, and image uploaders
 * allowing site administrators to customize all site text and images directly
 * from the WordPress Customizer (Appearance -> Customize).
 *
 * Adheres strictly to the WordPress PHP Coding Standards (WPCS).
 *
 * @package Kataskevi
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Available Predefined Color Theme Presets
 *
 * @return array
 */
function kataskevi_get_color_presets() {
	return array(
		'default' => array(
			'label'       => esc_html__( 'Steel Blue (Default Kataskevi)', 'kataskevi' ),
			'description' => esc_html__( 'Architectural precision steel blue with cool slate accents.', 'kataskevi' ),
			'colors'      => array(
				'primary'          => '#3f75a1',
				'primary_hover'    => '#346389',
				'secondary'        => '#f1f5f9',
				'secondary_hover'  => '#e2e8f0',
				'dark_bg'          => '#171f27',
				'dark_text'        => '#ffffff',
				'background'       => '#f8fafc',
				'foreground'       => '#171f27',
				'muted_foreground' => '#60676f',
				'card'             => '#ffffff',
				'border'           => '#dbdee2',
			),
		),
		'amber_industrial' => array(
			'label'       => esc_html__( 'Industrial Safety Amber & Charcoal', 'kataskevi' ),
			'description' => esc_html__( 'Authoritative civil, heavy equipment & hazard warning palette.', 'kataskevi' ),
			'colors'      => array(
				'primary'          => '#d97706',
				'primary_hover'    => '#b45309',
				'secondary'        => '#fef3c7',
				'secondary_hover'  => '#fde68a',
				'dark_bg'          => '#1e293b',
				'dark_text'        => '#ffffff',
				'background'       => '#f8fafc',
				'foreground'       => '#0f172a',
				'muted_foreground' => '#64748b',
				'card'             => '#ffffff',
				'border'           => '#e2e8f0',
			),
		),
		'cobalt_tech' => array(
			'label'       => esc_html__( 'Enterprise Cobalt & Midnight Navy', 'kataskevi' ),
			'description' => esc_html__( 'High-tech electronic security, enterprise CCTV & banking automation.', 'kataskevi' ),
			'colors'      => array(
				'primary'          => '#2563eb',
				'primary_hover'    => '#1d4ed8',
				'secondary'        => '#eff6ff',
				'secondary_hover'  => '#dbeafe',
				'dark_bg'          => '#0f172a',
				'dark_text'        => '#ffffff',
				'background'       => '#f8fafc',
				'foreground'       => '#0f172a',
				'muted_foreground' => '#64748b',
				'card'             => '#ffffff',
				'border'           => '#cbd5e1',
			),
		),
		'crimson_fire' => array(
			'label'       => esc_html__( 'Life Safety Crimson & Obsidian', 'kataskevi' ),
			'description' => esc_html__( 'Bold NFPA fire protection red with deep obsidian dark contrast.', 'kataskevi' ),
			'colors'      => array(
				'primary'          => '#dc2626',
				'primary_hover'    => '#b91c1c',
				'secondary'        => '#fef2f2',
				'secondary_hover'  => '#fee2e2',
				'dark_bg'          => '#18181b',
				'dark_text'        => '#ffffff',
				'background'       => '#fafafa',
				'foreground'       => '#18181b',
				'muted_foreground' => '#71717a',
				'card'             => '#ffffff',
				'border'           => '#e4e4e7',
			),
		),
		'emerald_infra' => array(
			'label'       => esc_html__( 'Sustainable Emerald & Forest', 'kataskevi' ),
			'description' => esc_html__( 'Clean green energy, MEP infrastructure & environmental safety.', 'kataskevi' ),
			'colors'      => array(
				'primary'          => '#059669',
				'primary_hover'    => '#047857',
				'secondary'        => '#ecfdf5',
				'secondary_hover'  => '#d1fae5',
				'dark_bg'          => '#064e3b',
				'dark_text'        => '#ffffff',
				'background'       => '#f8fafc',
				'foreground'       => '#0f172a',
				'muted_foreground' => '#64748b',
				'card'             => '#ffffff',
				'border'           => '#cbd5e1',
			),
		),
		'copper_warm' => array(
			'label'       => esc_html__( 'Architectural Bronze & Warm Taupe', 'kataskevi' ),
			'description' => esc_html__( 'Structural steel, mechanical fabrication & warm architectural finishes.', 'kataskevi' ),
			'colors'      => array(
				'primary'          => '#b45309',
				'primary_hover'    => '#92400e',
				'secondary'        => '#fef3c7',
				'secondary_hover'  => '#fde68a',
				'dark_bg'          => '#1c1917',
				'dark_text'        => '#ffffff',
				'background'       => '#fafaf9',
				'foreground'       => '#1c1917',
				'muted_foreground' => '#78716c',
				'card'             => '#ffffff',
				'border'           => '#e7e5e4',
			),
		),
		'monochrome_slate' => array(
			'label'       => esc_html__( 'Minimalist Charcoal & Platinum', 'kataskevi' ),
			'description' => esc_html__( 'Ultra-clean modernist architectural and engineering firm aesthetic.', 'kataskevi' ),
			'colors'      => array(
				'primary'          => '#0f172a',
				'primary_hover'    => '#334155',
				'secondary'        => '#f1f5f9',
				'secondary_hover'  => '#e2e8f0',
				'dark_bg'          => '#020617',
				'dark_text'        => '#ffffff',
				'background'       => '#ffffff',
				'foreground'       => '#0f172a',
				'muted_foreground' => '#64748b',
				'card'             => '#f8fafc',
				'border'           => '#e2e8f0',
			),
		),
		'royal_purple' => array(
			'label'       => esc_html__( 'Executive Violet & Deep Indigo', 'kataskevi' ),
			'description' => esc_html__( 'Modern executive engineering, high-value contracts & consulting.', 'kataskevi' ),
			'colors'      => array(
				'primary'          => '#7c3aed',
				'primary_hover'    => '#6d28d9',
				'secondary'        => '#f5f3ff',
				'secondary_hover'  => '#ede9fe',
				'dark_bg'          => '#1e1b4b',
				'dark_text'        => '#ffffff',
				'background'       => '#f8fafc',
				'foreground'       => '#0f172a',
				'muted_foreground' => '#64748b',
				'card'             => '#ffffff',
				'border'           => '#e2e8f0',
			),
		),
	);
}

/**
 * Register all Theme Customizer settings, panels, and controls.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @return void
 */
function kataskevi_customize_register( $wp_customize ) {

	/*
	 * =========================================================================
	 * MAIN THEME OPTIONS PANEL
	 * =========================================================================
	 */
	$wp_customize->add_panel(
		'kataskevi_options_panel',
		array(
			'title'       => esc_html__( 'Kataskevi Theme Settings', 'kataskevi' ),
			'description' => esc_html__( 'Manage all site copy, hero banners, media assets, stats, and company information across all pages.', 'kataskevi' ),
			'priority'    => 20,
		)
	);

	/*
	 * =========================================================================
	 * SECTION 1: COMPANY CONTACT & DETAILS
	 * =========================================================================
	 */
	$wp_customize->add_section(
		'kataskevi_sec_company',
		array(
			'title'       => esc_html__( '1. Company & Contact Details', 'kataskevi' ),
			'panel'       => 'kataskevi_options_panel',
			'priority'    => 10,
			'description' => esc_html__( 'Configure corporate company name, phone numbers, emergency lines, office location, and business hours.', 'kataskevi' ),
		)
	);

	// Company / Brand Name.
	$wp_customize->add_setting(
		'kataskevi_company_name',
		array(
			'default'           => get_bloginfo( 'name' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_company_name',
		array(
			'label'       => esc_html__( 'Company / Brand Name', 'kataskevi' ),
			'description' => esc_html__( 'Defaults to your WordPress Site Title. Used dynamically across branding, headings, and footer copyright.', 'kataskevi' ),
			'section'     => 'kataskevi_sec_company',
			'type'        => 'text',
		)
	);

	// Custom Header Logo / Brand Mark (Alternative / Direct Uploader).
	$wp_customize->add_setting(
		'kataskevi_header_logo',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'kataskevi_header_logo',
			array(
				'label'       => esc_html__( 'Header Logo / Icon', 'kataskevi' ),
				'description' => esc_html__( 'Upload a custom logo or icon (PNG, SVG, JPG, WebP). Also supports standard WordPress Site Identity Logo. Never breaks navbar layout.', 'kataskevi' ),
				'section'     => 'kataskevi_sec_company',
				'priority'    => 15,
			)
		)
	);

	// Primary Phone.
	$wp_customize->add_setting(
		'kataskevi_phone',
		array(
			'default'           => '+234 800 123 4567',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_phone',
		array(
			'label'       => esc_html__( 'Primary Phone / Hotline', 'kataskevi' ),
			'section'     => 'kataskevi_sec_company',
			'type'        => 'text',
		)
	);

	// Emergency Support Line.
	$wp_customize->add_setting(
		'kataskevi_emergency_phone',
		array(
			'default'           => '+234 802 345 6789',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_emergency_phone',
		array(
			'label'       => esc_html__( '24/7 Emergency Line', 'kataskevi' ),
			'section'     => 'kataskevi_sec_company',
			'type'        => 'text',
		)
	);

	// Email.
	$wp_customize->add_setting(
		'kataskevi_email',
		array(
			'default'           => get_option( 'admin_email', 'info@example.com' ),
			'sanitize_callback' => 'sanitize_email',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_email',
		array(
			'label'       => esc_html__( 'Corporate Contact Email', 'kataskevi' ),
			'section'     => 'kataskevi_sec_company',
			'type'        => 'email',
		)
	);

	// Physical Address.
	$wp_customize->add_setting(
		'kataskevi_address',
		array(
			'default'           => 'Engineering Hub, Commercial District, Victoria Island, Lagos',
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_address',
		array(
			'label'       => esc_html__( 'Physical Office Address', 'kataskevi' ),
			'section'     => 'kataskevi_sec_company',
			'type'        => 'textarea',
		)
	);

	// Business Hours.
	$wp_customize->add_setting(
		'kataskevi_hours',
		array(
			'default'           => 'Mon - Fri: 8:00 AM - 6:00 PM | 24/7 Emergency Response',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_hours',
		array(
			'label'       => esc_html__( 'Operating Hours', 'kataskevi' ),
			'section'     => 'kataskevi_sec_company',
			'type'        => 'text',
		)
	);

	/*
	 * =========================================================================
	 * SECTION 2: COLOR THEME & PALETTE
	 * =========================================================================
	 */
	$wp_customize->add_section(
		'kataskevi_sec_colors',
		array(
			'title'       => esc_html__( '2. Color Theme & Palette', 'kataskevi' ),
			'panel'       => 'kataskevi_options_panel',
			'priority'    => 15,
			'description' => esc_html__( 'Choose a predefined color preset or customize every individual brand color visually.', 'kataskevi' ),
		)
	);

	// Predefined Theme Presets Selector
	$color_presets  = kataskevi_get_color_presets();
	$preset_choices = array();
	foreach ( $color_presets as $p_key => $p_data ) {
		$preset_choices[ $p_key ] = $p_data['label'];
	}

	$wp_customize->add_setting(
		'kataskevi_color_preset',
		array(
			'default'           => 'default',
			'sanitize_callback' => 'sanitize_key',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'kataskevi_color_preset',
		array(
			'label'       => esc_html__( 'Predefined Color Theme / Preset', 'kataskevi' ),
			'description' => esc_html__( 'Select a coordinated brand palette. Selecting a preset updates all color pickers below automatically.', 'kataskevi' ),
			'section'     => 'kataskevi_sec_colors',
			'type'        => 'select',
			'choices'     => $preset_choices,
			'priority'    => 1,
		)
	);

	$color_settings = array(
		'kataskevi_color_primary'          => array(
			'label'       => esc_html__( 'Primary Brand Color', 'kataskevi' ),
			'description' => esc_html__( 'Main brand color for primary buttons, active links, badge pills, and highlights (Hex code e.g. #3f75a1).', 'kataskevi' ),
			'default'     => '#3f75a1',
			'priority'    => 10,
		),
		'kataskevi_color_primary_hover'    => array(
			'label'       => esc_html__( 'Primary Hover Color', 'kataskevi' ),
			'description' => esc_html__( 'Color for primary buttons and interactive elements on hover or focus (Hex code e.g. #346389).', 'kataskevi' ),
			'default'     => '#346389',
			'priority'    => 20,
		),
		'kataskevi_color_secondary'        => array(
			'label'       => esc_html__( 'Secondary Surface / Subtle Accent Color', 'kataskevi' ),
			'description' => esc_html__( 'Subtle background color for secondary cards, client logo marks, and badge accents (Hex code e.g. #f1f5f9).', 'kataskevi' ),
			'default'     => '#f1f5f9',
			'priority'    => 30,
		),
		'kataskevi_color_secondary_hover'  => array(
			'label'       => esc_html__( 'Secondary Hover Color', 'kataskevi' ),
			'description' => esc_html__( 'Color for secondary buttons and tags on hover (Hex code e.g. #e2e8f0).', 'kataskevi' ),
			'default'     => '#e2e8f0',
			'priority'    => 40,
		),
		'kataskevi_color_dark_bg'          => array(
			'label'       => esc_html__( 'Dark Section Background', 'kataskevi' ),
			'description' => esc_html__( 'Background for dark CTA sections and high-contrast callouts (Hex code e.g. #171f27).', 'kataskevi' ),
			'default'     => '#171f27',
			'priority'    => 50,
		),
		'kataskevi_color_dark_text'        => array(
			'label'       => esc_html__( 'Dark Section Text Color', 'kataskevi' ),
			'description' => esc_html__( 'Text and heading color inside dark callout sections (Hex code e.g. #ffffff).', 'kataskevi' ),
			'default'     => '#ffffff',
			'priority'    => 60,
		),
		'kataskevi_color_background'       => array(
			'label'       => esc_html__( 'Page Background Color', 'kataskevi' ),
			'description' => esc_html__( 'Overall site body and canvas background (Hex code e.g. #f8fafc).', 'kataskevi' ),
			'default'     => '#f8fafc',
			'priority'    => 70,
		),
		'kataskevi_color_foreground'       => array(
			'label'       => esc_html__( 'Headings & Main Text Color', 'kataskevi' ),
			'description' => esc_html__( 'Color for headings, titles, and primary body copy (Hex code e.g. #171f27).', 'kataskevi' ),
			'default'     => '#171f27',
			'priority'    => 80,
		),
		'kataskevi_color_muted_foreground' => array(
			'label'       => esc_html__( 'Muted / Subtitle Text Color', 'kataskevi' ),
			'description' => esc_html__( 'Color for subtitles, metadata, dates, and secondary text (Hex code e.g. #60676f).', 'kataskevi' ),
			'default'     => '#60676f',
			'priority'    => 90,
		),
		'kataskevi_color_card'             => array(
			'label'       => esc_html__( 'Card & Surface Background', 'kataskevi' ),
			'description' => esc_html__( 'Background for cards, white boxes, and footer area (Hex code e.g. #ffffff).', 'kataskevi' ),
			'default'     => '#ffffff',
			'priority'    => 100,
		),
		'kataskevi_color_border'           => array(
			'label'       => esc_html__( 'Border & Divider Color', 'kataskevi' ),
			'description' => esc_html__( 'Color for borders, divider lines, and form input outlines (Hex code e.g. #dbdee2).', 'kataskevi' ),
			'default'     => '#dbdee2',
			'priority'    => 110,
		),
	);

	foreach ( $color_settings as $setting_id => $data ) {
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => $data['default'],
				'sanitize_callback' => 'kataskevi_sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'       => $data['label'],
					'description' => $data['description'],
					'section'     => 'kataskevi_sec_colors',
					'settings'    => $setting_id,
					'priority'    => $data['priority'],
				)
			)
		);
	}

	/*
	 * =========================================================================
	 * SECTION 3: SOCIAL CHANNELS
	 * =========================================================================
	 */
	$wp_customize->add_section(
		'kataskevi_sec_social',
		array(
			'title'       => esc_html__( '3. Social Channels', 'kataskevi' ),
			'panel'       => 'kataskevi_options_panel',
			'priority'    => 20,
			'description' => esc_html__( 'Configure links for corporate social profiles displayed in the footer and contact sections.', 'kataskevi' ),
		)
	);

	$social_platforms = array(
		'linkedin'  => array( 'label' => __( 'LinkedIn Profile URL', 'kataskevi' ),  'default' => 'https://linkedin.com/company/kataskevi' ),
		'twitter'   => array( 'label' => __( 'X (Twitter) URL', 'kataskevi' ),       'default' => 'https://x.com/kataskevi' ),
		'facebook'  => array( 'label' => __( 'Facebook Page URL', 'kataskevi' ),     'default' => 'https://facebook.com/kataskevi' ),
		'instagram' => array( 'label' => __( 'Instagram Profile URL', 'kataskevi' ), 'default' => 'https://instagram.com/kataskevi' ),
		'youtube'   => array( 'label' => __( 'YouTube Channel URL', 'kataskevi' ),   'default' => 'https://youtube.com/@kataskevi' ),
		'whatsapp'  => array( 'label' => __( 'WhatsApp Direct Chat Link', 'kataskevi' ), 'default' => 'https://wa.me/2348005282753' ),
	);

	foreach ( $social_platforms as $key => $data ) {
		$wp_customize->add_setting(
			'kataskevi_' . $key,
			array(
				'default'           => $data['default'],
				'sanitize_callback' => 'esc_url_raw',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			'kataskevi_' . $key,
			array(
				'label'   => esc_html( $data['label'] ),
				'section' => 'kataskevi_sec_social',
				'type'    => 'url',
			)
		);
	}

	/*
	 * =========================================================================
	 * SECTION 3: HEADER & FOOTER
	 * =========================================================================
	 */
	$wp_customize->add_section(
		'kataskevi_sec_header_footer',
		array(
			'title'       => esc_html__( '3. Header & Footer Copy', 'kataskevi' ),
			'panel'       => 'kataskevi_options_panel',
			'priority'    => 30,
			'description' => esc_html__( 'Customize navigation call-to-action button, footer company description, and copyright line.', 'kataskevi' ),
		)
	);

	// Header CTA Button Text.
	$wp_customize->add_setting(
		'kataskevi_header_cta_text',
		array(
			'default'           => 'Get a Quote',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_header_cta_text',
		array(
			'label'   => esc_html__( 'Header CTA Button Text', 'kataskevi' ),
			'section' => 'kataskevi_sec_header_footer',
			'type'    => 'text',
		)
	);

	// Footer About Title.
	$wp_customize->add_setting(
		'kataskevi_footer_about_title',
		array(
			'default'           => 'About Company',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_footer_about_title',
		array(
			'label'   => esc_html__( 'Footer About Title', 'kataskevi' ),
			'section' => 'kataskevi_sec_header_footer',
			'type'    => 'text',
		)
	);

	// Footer Brand Description.
	$wp_customize->add_setting(
		'kataskevi_footer_brand_desc',
		array(
			'default'           => 'Engineering, Safety, Information Technology and Telecommunications support services — design, supply, installation and maintenance, end to end.',
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_footer_brand_desc',
		array(
			'label'   => esc_html__( 'Footer Brand Summary', 'kataskevi' ),
			'section' => 'kataskevi_sec_header_footer',
			'type'    => 'textarea',
		)
	);

	// Footer Tagline.
	$wp_customize->add_setting(
		'kataskevi_footer_tagline',
		array(
			'default'           => 'Design · Supply · Install · Maintain',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_footer_tagline',
		array(
			'label'   => esc_html__( 'Footer Tagline', 'kataskevi' ),
			'section' => 'kataskevi_sec_header_footer',
			'type'    => 'text',
		)
	);

	/*
	 * =========================================================================
	 * SECTION 4: HOMEPAGE HERO SECTION
	 * =========================================================================
	 */
	$wp_customize->add_section(
		'kataskevi_sec_home_hero',
		array(
			'title'       => esc_html__( '4. Homepage: Hero Section', 'kataskevi' ),
			'panel'       => 'kataskevi_options_panel',
			'priority'    => 40,
			'description' => esc_html__( 'Configure homepage hero titles, description, CTA buttons, and the full-width hero feature image.', 'kataskevi' ),
		)
	);

	// Hero Eyebrow.
	$wp_customize->add_setting(
		'kataskevi_hero_eyebrow',
		array(
			'default'           => 'Engineering · Safety · IT · Telecoms',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_hero_eyebrow',
		array(
			'label'   => esc_html__( 'Hero Eyebrow Tag', 'kataskevi' ),
			'section' => 'kataskevi_sec_home_hero',
			'type'    => 'text',
		)
	);

	// Hero Title (HTML allowed for emphasis styling).
	$wp_customize->add_setting(
		'kataskevi_hero_title',
		array(
			'default'           => 'Complete protection, <em class="text-primary italic">engineered end&nbsp;to&nbsp;end.</em>',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_hero_title',
		array(
			'label'       => esc_html__( 'Hero Headline', 'kataskevi' ),
			'description' => esc_html__( 'Supports <em> tags for editorial italic styling.', 'kataskevi' ),
			'section'     => 'kataskevi_sec_home_hero',
			'type'        => 'textarea',
		)
	);

	// Hero Description.
	$wp_customize->add_setting(
		'kataskevi_hero_description',
		array(
			'default'           => sprintf(
				/* translators: %s: Company / Brand Name */
				esc_html__( '%s provides full-service support in the design, supply, installation and maintenance of fire safety, electronic security and IT/telecoms infrastructure — from site survey to commissioning and beyond.', 'kataskevi' ),
				kataskevi_get_brand_name()
			),
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_hero_description',
		array(
			'label'   => esc_html__( 'Hero Lead Description', 'kataskevi' ),
			'section' => 'kataskevi_sec_home_hero',
			'type'    => 'textarea',
		)
	);

	// Hero Primary Button Label.
	$wp_customize->add_setting(
		'kataskevi_hero_btn1_text',
		array(
			'default'           => 'Explore Services',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_hero_btn1_text',
		array(
			'label'   => esc_html__( 'Hero Button 1 Text', 'kataskevi' ),
			'section' => 'kataskevi_sec_home_hero',
			'type'    => 'text',
		)
	);

	// Hero Secondary Button Label.
	$wp_customize->add_setting(
		'kataskevi_hero_btn2_text',
		array(
			'default'           => 'Request a Quote',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_hero_btn2_text',
		array(
			'label'   => esc_html__( 'Hero Button 2 Text', 'kataskevi' ),
			'section' => 'kataskevi_sec_home_hero',
			'type'    => 'text',
		)
	);

	// Hero Image Upload.
	$wp_customize->add_setting(
		'kataskevi_hero_image',
		array(
			'default'           => get_template_directory_uri() . '/assets/images/hero.jpg',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'kataskevi_hero_image',
			array(
				'label'       => esc_html__( 'Hero Feature Image', 'kataskevi' ),
				'description' => esc_html__( 'Recommended resolution: 1600x1000px or larger.', 'kataskevi' ),
				'section'     => 'kataskevi_sec_home_hero',
			)
		)
	);

	// Three Core Specialties Strip (Under Hero Image).
	$features = array(
		1 => array(
			'title_default' => 'Data Preparation',
			'desc_default'  => 'Turnkey preparation of IT and telecoms facilities, clean data halls, and mission-critical server environments.',
			'label'         => 'Specialty 1',
		),
		2 => array(
			'title_default' => 'Raised Floor',
			'desc_default'  => 'Heavy-duty anti-static raised access flooring engineered for airflow, cable management, and load-bearing stability.',
			'label'         => 'Specialty 2',
		),
		3 => array(
			'title_default' => 'Access Control',
			'desc_default'  => 'Multi-factor biometric access, smart card terminals, optical turnstiles, and auditable physical security systems.',
			'label'         => 'Specialty 3',
		),
	);

	foreach ( $features as $f_idx => $f_data ) {
		$wp_customize->add_setting(
			'kataskevi_hero_feat' . $f_idx . '_title',
			array(
				'default'           => $f_data['title_default'],
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			'kataskevi_hero_feat' . $f_idx . '_title',
			array(
				'label'   => sprintf( esc_html__( '%s: Title', 'kataskevi' ), $f_data['label'] ),
				'section' => 'kataskevi_sec_home_hero',
				'type'    => 'text',
			)
		);

		$wp_customize->add_setting(
			'kataskevi_hero_feat' . $f_idx . '_desc',
			array(
				'default'           => $f_data['desc_default'],
				'sanitize_callback' => 'sanitize_textarea_field',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			'kataskevi_hero_feat' . $f_idx . '_desc',
			array(
				'label'   => sprintf( esc_html__( '%s: Description', 'kataskevi' ), $f_data['label'] ),
				'section' => 'kataskevi_sec_home_hero',
				'type'    => 'textarea',
			)
		);
	}

	/*
	 * =========================================================================
	 * SECTION 5: HOMEPAGE FEATURED WORKS
	 * =========================================================================
	 */
	$wp_customize->add_section(
		'kataskevi_sec_home_sections',
		array(
			'title'       => esc_html__( '5. Homepage: Featured Works', 'kataskevi' ),
			'panel'       => 'kataskevi_options_panel',
			'priority'    => 50,
			'description' => esc_html__( 'Headings and introductory text for the Featured Works carousel section on the homepage.', 'kataskevi' ),
		)
	);

	// Featured Works Eyebrow.
	$wp_customize->add_setting(
		'kataskevi_home_works_eyebrow',
		array(
			'default'           => 'Proven in the field',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_home_works_eyebrow',
		array(
			'label'   => esc_html__( 'Featured Works Eyebrow', 'kataskevi' ),
			'section' => 'kataskevi_sec_home_sections',
			'type'    => 'text',
		)
	);

	// Featured Works Title.
	$wp_customize->add_setting(
		'kataskevi_home_works_title',
		array(
			'default'           => 'Featured works',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_home_works_title',
		array(
			'label'   => esc_html__( 'Featured Works Headline', 'kataskevi' ),
			'section' => 'kataskevi_sec_home_sections',
			'type'    => 'text',
		)
	);

	// Featured Works Lead.
	$wp_customize->add_setting(
		'kataskevi_home_works_desc',
		array(
			'default'           => 'A cross-section of recent installations where life safety, mission-critical uptime, and rigorous engineering were non-negotiable.',
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_home_works_desc',
		array(
			'label'   => esc_html__( 'Featured Works Lead Description', 'kataskevi' ),
			'section' => 'kataskevi_sec_home_sections',
			'type'    => 'textarea',
		)
	);

	/*
	 * =========================================================================
	 * SECTION 6: HOMEPAGE LIFECYCLE BANNER & 4 STEPS
	 * =========================================================================
	 */
	$wp_customize->add_section(
		'kataskevi_sec_home_lifecycle',
		array(
			'title'       => esc_html__( '6. Homepage: Lifecycle Support Banner', 'kataskevi' ),
			'panel'       => 'kataskevi_options_panel',
			'priority'    => 60,
			'description' => esc_html__( 'Configure the high-impact media banner image, headline, and the 4 lifecycle engineering cards.', 'kataskevi' ),
		)
	);

	// Lifecycle Banner Image Upload.
	$wp_customize->add_setting(
		'kataskevi_lifecycle_image',
		array(
			'default'           => get_template_directory_uri() . '/assets/images/band.jpg',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'kataskevi_lifecycle_image',
			array(
				'label'       => esc_html__( 'Lifecycle Banner Image', 'kataskevi' ),
				'description' => esc_html__( 'Recommended resolution: 1920x820px.', 'kataskevi' ),
				'section'     => 'kataskevi_sec_home_lifecycle',
			)
		)
	);

	// Lifecycle Eyebrow.
	$wp_customize->add_setting(
		'kataskevi_lifecycle_eyebrow',
		array(
			'default'           => 'Core Services',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_lifecycle_eyebrow',
		array(
			'label'   => esc_html__( 'Lifecycle Eyebrow', 'kataskevi' ),
			'section' => 'kataskevi_sec_home_lifecycle',
			'type'    => 'text',
		)
	);

	// Lifecycle Title.
	$wp_customize->add_setting(
		'kataskevi_lifecycle_title',
		array(
			'default'           => 'Beyond installation',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_lifecycle_title',
		array(
			'label'   => esc_html__( 'Lifecycle Headline', 'kataskevi' ),
			'section' => 'kataskevi_sec_home_lifecycle',
			'type'    => 'text',
		)
	);

	// Lifecycle Lead.
	$wp_customize->add_setting(
		'kataskevi_lifecycle_desc',
		array(
			'default'           => 'From preliminary risk assessment to turnkey deployment and lifetime preventative maintenance, our team delivers complete protection across every project stage.',
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_lifecycle_desc',
		array(
			'label'   => esc_html__( 'Lifecycle Lead Summary', 'kataskevi' ),
			'section' => 'kataskevi_sec_home_lifecycle',
			'type'    => 'textarea',
		)
	);

	// 4 Lifecycle Steps.
	$lifecycle_defaults = array(
		1 => array( 'title' => 'Site Surveys', 'desc' => 'Assessment of your facility to establish exactly what your site requires.' ),
		2 => array( 'title' => 'System Design & Specification', 'desc' => 'Engineered designs and precise specifications for every system we deliver.' ),
		3 => array( 'title' => 'Project Commissioning', 'desc' => 'Structured commissioning and handover so systems perform from day one.' ),
		4 => array( 'title' => 'Sales & Installation', 'desc' => 'Supply and professional installation backed by ongoing maintenance.' ),
	);

	for ( $s = 1; $s <= 4; $s++ ) {
		$wp_customize->add_setting(
			'kataskevi_lifecycle_step' . $s . '_title',
			array(
				'default'           => $lifecycle_defaults[ $s ]['title'],
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			'kataskevi_lifecycle_step' . $s . '_title',
			array(
				/* translators: %d: step index number */
				'label'   => sprintf( esc_html__( 'Step %d Title', 'kataskevi' ), $s ),
				'section' => 'kataskevi_sec_home_lifecycle',
				'type'    => 'text',
			)
		);

		$wp_customize->add_setting(
			'kataskevi_lifecycle_step' . $s . '_desc',
			array(
				'default'           => $lifecycle_defaults[ $s ]['desc'],
				'sanitize_callback' => 'sanitize_textarea_field',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			'kataskevi_lifecycle_step' . $s . '_desc',
			array(
				/* translators: %d: step index number */
				'label'   => sprintf( esc_html__( 'Step %d Description', 'kataskevi' ), $s ),
				'section' => 'kataskevi_sec_home_lifecycle',
				'type'    => 'textarea',
			)
		);
	}

	/*
	 * =========================================================================
	 * SECTION 7: HOMEPAGE SECTORS & DARK CTA
	 * =========================================================================
	 */
	$wp_customize->add_section(
		'kataskevi_sec_home_dark_cta',
		array(
			'title'       => esc_html__( '7. Homepage: Sectors, Clients & Dark CTA', 'kataskevi' ),
			'panel'       => 'kataskevi_options_panel',
			'priority'    => 70,
			'description' => esc_html__( 'Configure the industry sectors header, our clients dual marquee section, and the high-contrast dark CTA banner at the bottom of the front page.', 'kataskevi' ),
		)
	);

	// Sectors Eyebrow.
	$wp_customize->add_setting(
		'kataskevi_home_sectors_eyebrow',
		array(
			'default'           => 'Sectors',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_home_sectors_eyebrow',
		array(
			'label'   => esc_html__( 'Sectors Section Eyebrow', 'kataskevi' ),
			'section' => 'kataskevi_sec_home_dark_cta',
			'type'    => 'text',
		)
	);

	// Sectors Title.
	$wp_customize->add_setting(
		'kataskevi_home_sectors_title',
		array(
			'default'           => 'Sectors we serve',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_home_sectors_title',
		array(
			'label'   => esc_html__( 'Sectors Section Headline', 'kataskevi' ),
			'section' => 'kataskevi_sec_home_dark_cta',
			'type'    => 'text',
		)
	);

	// Sectors Lead Description.
	$wp_customize->add_setting(
		'kataskevi_home_sectors_desc',
		array(
			'default'           => 'Every industry has its own risks, standards and uptime demands. We tailor each system to the environment it protects.',
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_home_sectors_desc',
		array(
			'label'   => esc_html__( 'Sectors Lead Description', 'kataskevi' ),
			'section' => 'kataskevi_sec_home_dark_cta',
			'type'    => 'textarea',
		)
	);

	// Clients Eyebrow.
	$wp_customize->add_setting(
		'kataskevi_clients_eyebrow',
		array(
			'default'           => 'Our Clients',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_clients_eyebrow',
		array(
			'label'   => esc_html__( 'Clients Eyebrow', 'kataskevi' ),
			'section' => 'kataskevi_sec_home_dark_cta',
			'type'    => 'text',
		)
	);

	// Clients Title.
	$wp_customize->add_setting(
		'kataskevi_clients_title',
		array(
			'default'           => 'Trusted by industry leaders across Nigeria',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_clients_title',
		array(
			'label'   => esc_html__( 'Clients Headline', 'kataskevi' ),
			'section' => 'kataskevi_sec_home_dark_cta',
			'type'    => 'text',
		)
	);

	// Clients Description.
	$wp_customize->add_setting(
		'kataskevi_clients_desc',
		array(
			'default'           => sprintf(
				/* translators: %s: Company / Brand Name */
				esc_html__( 'From Tier III data centers and telecommunications networks to corporate financial institutions and energy complexes, Nigeria\'s leading enterprises rely on %s for mission-critical infrastructure.', 'kataskevi' ),
				kataskevi_get_brand_name()
			),
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_clients_desc',
		array(
			'label'   => esc_html__( 'Clients Description', 'kataskevi' ),
			'section' => 'kataskevi_sec_home_dark_cta',
			'type'    => 'textarea',
		)
	);

	// Dark CTA Eyebrow.
	$wp_customize->add_setting(
		'kataskevi_dark_cta_eyebrow',
		array(
			'default'           => 'Request a survey',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_dark_cta_eyebrow',
		array(
			'label'   => esc_html__( 'Dark CTA Eyebrow', 'kataskevi' ),
			'section' => 'kataskevi_sec_home_dark_cta',
			'type'    => 'text',
		)
	);

	// Dark CTA Title.
	$wp_customize->add_setting(
		'kataskevi_dark_cta_title',
		array(
			'default'           => 'Ready to protect what matters?',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_dark_cta_title',
		array(
			'label'   => esc_html__( 'Dark CTA Headline', 'kataskevi' ),
			'section' => 'kataskevi_sec_home_dark_cta',
			'type'    => 'text',
		)
	);

	// Dark CTA Lead.
	$wp_customize->add_setting(
		'kataskevi_dark_cta_desc',
		array(
			'default'           => 'Request a site survey or a quote — our team will design a system around your facility, your risks and your budget.',
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_dark_cta_desc',
		array(
			'label'   => esc_html__( 'Dark CTA Description', 'kataskevi' ),
			'section' => 'kataskevi_sec_home_dark_cta',
			'type'    => 'textarea',
		)
	);

	// Dark CTA Primary Button Text.
	$wp_customize->add_setting(
		'kataskevi_dark_cta_btn1_text',
		array(
			'default'           => 'Get a Quote',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_dark_cta_btn1_text',
		array(
			'label'   => esc_html__( 'Dark CTA Button 1 Text', 'kataskevi' ),
			'section' => 'kataskevi_sec_home_dark_cta',
			'type'    => 'text',
		)
	);

	// Dark CTA Secondary Button Text.
	$wp_customize->add_setting(
		'kataskevi_dark_cta_btn2_text',
		array(
			'default'           => 'View All Services',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_dark_cta_btn2_text',
		array(
			'label'   => esc_html__( 'Dark CTA Button 2 Text', 'kataskevi' ),
			'section' => 'kataskevi_sec_home_dark_cta',
			'type'    => 'text',
		)
	);

	/*
	 * =========================================================================
	 * SECTION 8: PAGE - SERVICES OVERVIEW
	 * =========================================================================
	 */
	$wp_customize->add_section(
		'kataskevi_sec_page_services',
		array(
			'title'       => esc_html__( '8. Page: Services Overview', 'kataskevi' ),
			'panel'       => 'kataskevi_options_panel',
			'priority'    => 80,
			'description' => esc_html__( 'Configure the hero header titles and bottom Call to Action section on the Services page template.', 'kataskevi' ),
		)
	);

	// Page Hero Eyebrow.
	$wp_customize->add_setting(
		'kataskevi_page_services_eyebrow',
		array(
			'default'           => 'Services',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_page_services_eyebrow',
		array(
			'label'   => esc_html__( 'Hero Eyebrow', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_services',
			'type'    => 'text',
		)
	);

	// Page Hero Title.
	$wp_customize->add_setting(
		'kataskevi_page_services_title',
		array(
			'default'           => 'Engineering services, <em class="text-primary italic">one accountable partner.</em>',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_page_services_title',
		array(
			'label'   => esc_html__( 'Hero Headline', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_services',
			'type'    => 'textarea',
		)
	);

	// Page Hero Desc.
	$wp_customize->add_setting(
		'kataskevi_page_services_desc',
		array(
			'default'           => 'Complete lifecycle support across our four core engineering service pillars — from preliminary site survey to structured commissioning and ongoing preventative maintenance.',
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_page_services_desc',
		array(
			'label'   => esc_html__( 'Hero Description', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_services',
			'type'    => 'textarea',
		)
	);

	// Bottom CTA Eyebrow.
	$wp_customize->add_setting(
		'kataskevi_services_how_eyebrow',
		array(
			'default'           => 'One Accountable Partner',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_services_how_eyebrow',
		array(
			'label'   => esc_html__( 'Bottom CTA Eyebrow', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_services',
			'type'    => 'text',
		)
	);

	// Bottom CTA Title.
	$wp_customize->add_setting(
		'kataskevi_services_how_title',
		array(
			'default'           => 'Engineered for complete protection from survey to handover.',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_services_how_title',
		array(
			'label'   => esc_html__( 'Bottom CTA Headline', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_services',
			'type'    => 'text',
		)
	);

	// Bottom CTA Desc.
	$wp_customize->add_setting(
		'kataskevi_services_how_desc',
		array(
			'default'           => 'Speak with our engineering team to audit your facility risks or design an integrated protection system tailored to your building.',
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_services_how_desc',
		array(
			'label'   => esc_html__( 'Bottom CTA Description', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_services',
			'type'    => 'textarea',
		)
	);

	// Bottom CTA Button.
	$wp_customize->add_setting(
		'kataskevi_services_how_btn',
		array(
			'default'           => 'Request a Site Survey',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_services_how_btn',
		array(
			'label'   => esc_html__( 'Bottom CTA Button Text', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_services',
			'type'    => 'text',
		)
	);

	/*
	 * =========================================================================
	 * SECTION 9: PAGE - ABOUT KATASKEVI
	 * =========================================================================
	 */
	$wp_customize->add_section(
		'kataskevi_sec_page_about',
		array(
			'title'       => esc_html__( '9. Page: About Us', 'kataskevi' ),
			'panel'       => 'kataskevi_options_panel',
			'priority'    => 90,
			'description' => esc_html__( 'Configure hero text, full-width media banner, "Why we exist" narrative story, and sector CTA on the About page.', 'kataskevi' ),
		)
	);

	// About Hero Eyebrow.
	$wp_customize->add_setting(
		'kataskevi_about_hero_eyebrow',
		array(
			'default'           => 'About',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_about_hero_eyebrow',
		array(
			'label'   => esc_html__( 'About Hero Eyebrow', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_about',
			'type'    => 'text',
		)
	);

	// About Hero Title.
	$wp_customize->add_setting(
		'kataskevi_about_hero_title',
		array(
			'default'           => 'One partner for the systems <em class="text-primary italic">modern facilities depend on.</em>',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_about_hero_title',
		array(
			'label'   => esc_html__( 'About Hero Headline', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_about',
			'type'    => 'textarea',
		)
	);

	// About Hero Desc.
	$wp_customize->add_setting(
		'kataskevi_about_hero_desc',
		array(
			'default'           => 'An Engineering, Safety, Information Technology and Telecommunications support services company — established to provide complete service in the design, supply, installation and maintenance of the systems modern facilities run on.',
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_about_hero_desc',
		array(
			'label'   => esc_html__( 'About Hero Description', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_about',
			'type'    => 'textarea',
		)
	);

	// About Full-Width Media Image Upload.
	$wp_customize->add_setting(
		'kataskevi_about_image',
		array(
			'default'           => get_template_directory_uri() . '/assets/images/about.jpg',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'kataskevi_about_image',
			array(
				'label'       => esc_html__( 'About Full-Width Banner Image', 'kataskevi' ),
				'description' => esc_html__( 'Recommended resolution: 1400x1000px.', 'kataskevi' ),
				'section'     => 'kataskevi_sec_page_about',
			)
		)
	);

	// Why We Exist Eyebrow.
	$wp_customize->add_setting(
		'kataskevi_about_why_eyebrow',
		array(
			'default'           => 'Why we exist',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_about_why_eyebrow',
		array(
			'label'   => esc_html__( 'Why We Exist Eyebrow', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_about',
			'type'    => 'text',
		)
	);

	// Why We Exist Title.
	$wp_customize->add_setting(
		'kataskevi_about_why_title',
		array(
			'default'           => 'Closing the gaps between systems',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_about_why_title',
		array(
			'label'   => esc_html__( 'Why We Exist Headline', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_about',
			'type'    => 'text',
		)
	);

	// Why We Exist Paragraphs.
	$why_paragraphs = array(
		1 => 'Modern facilities run on interdependent systems — suppression, detection, security, access, connectivity. When any one of them fails, everything downstream is at risk.',
		2 => 'Too often, these systems are procured in silos, installed by different vendors with no shared accountability, and handed over without ongoing maintenance plans. When something goes wrong, the finger-pointing begins.',
		3 => sprintf(
			/* translators: %s: Company / Brand Name */
			esc_html__( '%s was founded to eliminate those gaps. We take end-to-end responsibility for every system we deliver — from initial survey through engineering, procurement, installation, and lifecycle maintenance. One partner. One standard.', 'kataskevi' ),
			kataskevi_get_brand_name()
		),
		4 => 'Whether designing a clean-agent suppression system for a colocation suite, securing a corporate headquarters, or outfitting a marine terminal with certified safety gear, we bring the same engineering rigor to every engagement.',
	);

	for ( $p = 1; $p <= 4; $p++ ) {
		$wp_customize->add_setting(
			'kataskevi_about_why_p' . $p,
			array(
				'default'           => $why_paragraphs[ $p ],
				'sanitize_callback' => 'sanitize_textarea_field',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			'kataskevi_about_why_p' . $p,
			array(
				/* translators: %d: paragraph number */
				'label'   => sprintf( esc_html__( 'Story Paragraph %d', 'kataskevi' ), $p ),
				'section' => 'kataskevi_sec_page_about',
				'type'    => 'textarea',
			)
		);
	}

	// =========================================================================
	// Corporate Framework Accordion Section
	// =========================================================================

	// Framework Eyebrow.
	$wp_customize->add_setting(
		'kataskevi_about_framework_eyebrow',
		array(
			'default'           => 'Corporate Framework',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_about_framework_eyebrow',
		array(
			'label'   => esc_html__( 'Corporate Framework Eyebrow', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_about',
			'type'    => 'text',
		)
	);

	// Framework Headline.
	$wp_customize->add_setting(
		'kataskevi_about_framework_title',
		array(
			'default'           => 'Vision, Mission & Operational Standards',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_about_framework_title',
		array(
			'label'   => esc_html__( 'Corporate Framework Headline', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_about',
			'type'    => 'text',
		)
	);

	// Framework Description.
	$wp_customize->add_setting(
		'kataskevi_about_framework_desc',
		array(
			'default'           => 'The foundational principles, certified safety protocols, and technical competencies guiding every engineering engagement across modern facilities.',
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_about_framework_desc',
		array(
			'label'   => esc_html__( 'Corporate Framework Description', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_about',
			'type'    => 'textarea',
		)
	);

	// Accordion Item 1: Vision.
	$wp_customize->add_setting(
		'kataskevi_about_vision_title',
		array(
			'default'           => 'Vision',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_about_vision_title',
		array(
			'label'   => esc_html__( 'Accordion Item 1: Vision Title', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_about',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'kataskevi_about_vision_desc',
		array(
			'default'           => 'To be the premier engineering, safety, and infrastructure solutions partner across West Africa — recognized for technical distinction, uncompromised safety integrity, and long-term client value.',
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_about_vision_desc',
		array(
			'label'   => esc_html__( 'Accordion Item 1: Vision Description', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_about',
			'type'    => 'textarea',
		)
	);

	// Accordion Item 2: Mission.
	$wp_customize->add_setting(
		'kataskevi_about_mission_title',
		array(
			'default'           => 'Mission',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_about_mission_title',
		array(
			'label'   => esc_html__( 'Accordion Item 2: Mission Title', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_about',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'kataskevi_about_mission_desc',
		array(
			'default'           => 'To protect lives, mission-critical assets, and operational continuity by delivering world-class engineering, fire suppression, electronic security, and IT/telecommunications services through certified technical expertise and responsive lifecycle maintenance.',
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_about_mission_desc',
		array(
			'label'   => esc_html__( 'Accordion Item 2: Mission Description', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_about',
			'type'    => 'textarea',
		)
	);

	// Accordion Item 3: HSE Policy.
	$wp_customize->add_setting(
		'kataskevi_about_hse_title',
		array(
			'default'           => 'HSE Policy',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_about_hse_title',
		array(
			'label'   => esc_html__( 'Accordion Item 3: HSE Policy Title', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_about',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'kataskevi_about_hse_desc',
		array(
			'default'           => 'We operate under an uncompromising "Zero Harm, Zero Incident" policy. Every project engagement adheres strictly to OSHA, NFPA, and ISO-aligned protocols to ensure the absolute safety of our workforce, client infrastructure, and the surrounding environment.',
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_about_hse_desc',
		array(
			'label'   => esc_html__( 'Accordion Item 3: HSE Policy Description', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_about',
			'type'    => 'textarea',
		)
	);

	// Accordion Item 4: Experienced Customer Support.
	$wp_customize->add_setting(
		'kataskevi_about_support_title',
		array(
			'default'           => 'Experienced Customer Support',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_about_support_title',
		array(
			'label'   => esc_html__( 'Accordion Item 4: Customer Support Title', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_about',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'kataskevi_about_support_desc',
		array(
			'default'           => 'Our dedicated technical support desk and mobile field engineers provide 24/7/365 coverage, offering rapid emergency dispatch, proactive SLA monitoring, and scheduled preventive maintenance routines to guarantee zero facility downtime.',
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_about_support_desc',
		array(
			'label'   => esc_html__( 'Accordion Item 4: Customer Support Description', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_about',
			'type'    => 'textarea',
		)
	);

	// Accordion Item 5: Professional Technical Staff.
	$wp_customize->add_setting(
		'kataskevi_about_staff_title',
		array(
			'default'           => 'Professional Technical Staff',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_about_staff_title',
		array(
			'label'   => esc_html__( 'Accordion Item 5: Technical Staff Title', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_about',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'kataskevi_about_staff_desc',
		array(
			'default'           => 'Our multidisciplinary engineering roster consists of certified fire protection specialists, OEM-trained instrumentation technicians, certified fiber optic engineers, and licensed industrial safety consultants with decades of combined hands-on industrial field experience.',
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_about_staff_desc',
		array(
			'label'   => esc_html__( 'Accordion Item 5: Technical Staff Description', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_about',
			'type'    => 'textarea',
		)
	);

	// =========================================================================
	// Philosophy Pillars (4 Grid Cards)
	// =========================================================================
	$default_pillars = array(
		1 => array(
			'title' => 'Safety first, always',
			'desc'  => 'Every system we design and install exists to protect people, assets and continuity of operations.',
		),
		2 => array(
			'title' => 'Engineered, not guessed',
			'desc'  => 'Site surveys, precise specifications and structured commissioning — the system fits the facility, because it was designed for it.',
		),
		3 => array(
			'title' => 'One accountable partner',
			'desc'  => 'Design, supply, installation and maintenance under one roof — no finger-pointing between vendors.',
		),
		4 => array(
			'title' => 'Built around your profit',
			'desc'  => 'In a busy, changing industry, our variety of services supports our clientele towards maximising profit.',
		),
	);

	for ( $pl = 1; $pl <= 4; $pl++ ) {
		$wp_customize->add_setting(
			'kataskevi_about_pillar_' . $pl . '_title',
			array(
				'default'           => $default_pillars[ $pl ]['title'],
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			'kataskevi_about_pillar_' . $pl . '_title',
			array(
				/* translators: %d: pillar number */
				'label'   => sprintf( esc_html__( 'Philosophy Pillar %d Title', 'kataskevi' ), $pl ),
				'section' => 'kataskevi_sec_page_about',
				'type'    => 'text',
			)
		);

		$wp_customize->add_setting(
			'kataskevi_about_pillar_' . $pl . '_desc',
			array(
				'default'           => $default_pillars[ $pl ]['desc'],
				'sanitize_callback' => 'sanitize_textarea_field',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			'kataskevi_about_pillar_' . $pl . '_desc',
			array(
				/* translators: %d: pillar number */
				'label'   => sprintf( esc_html__( 'Philosophy Pillar %d Description', 'kataskevi' ), $pl ),
				'section' => 'kataskevi_sec_page_about',
				'type'    => 'textarea',
			)
		);
	}

	// Who We Serve Eyebrow.
	$wp_customize->add_setting(
		'kataskevi_about_who_eyebrow',
		array(
			'default'           => 'Who we serve',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_about_who_eyebrow',
		array(
			'label'   => esc_html__( 'Who We Serve Eyebrow', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_about',
			'type'    => 'text',
		)
	);

	// Who We Serve Title.
	$wp_customize->add_setting(
		'kataskevi_about_who_title',
		array(
			'default'           => 'Different industries, one standard',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_about_who_title',
		array(
			'label'   => esc_html__( 'Who We Serve Title', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_about',
			'type'    => 'text',
		)
	);

	// Who We Serve Description.
	$wp_customize->add_setting(
		'kataskevi_about_who_desc',
		array(
			'default'           => 'Systems that work when they\'re needed most.',
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_about_who_desc',
		array(
			'label'   => esc_html__( 'Who We Serve Subtitle / Description', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_about',
			'type'    => 'textarea',
		)
	);

	// Who We Serve CTA Button Text.
	$wp_customize->add_setting(
		'kataskevi_about_who_btn',
		array(
			'default'           => 'Discuss your facility',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_about_who_btn',
		array(
			'label'   => esc_html__( 'Who We Serve CTA Button Text', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_about',
			'type'    => 'text',
		)
	);

	/*
	 * =========================================================================
	 * SECTION 10: PAGE - OUR WORKS & COMMITMENTS
	 * =========================================================================
	 */
	$wp_customize->add_section(
		'kataskevi_sec_page_works',
		array(
			'title'       => esc_html__( '10. Page: Our Works & Commitments', 'kataskevi' ),
			'panel'       => 'kataskevi_options_panel',
			'priority'    => 100,
			'description' => esc_html__( 'Configure the headline, lead summary, and the 4 engineering commitments displayed on the Our Works archive page.', 'kataskevi' ),
		)
	);

	// Works Hero Eyebrow.
	$wp_customize->add_setting(
		'kataskevi_works_page_eyebrow',
		array(
			'default'           => 'Our Works',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_works_page_eyebrow',
		array(
			'label'   => esc_html__( 'Works Hero Eyebrow', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_works',
			'type'    => 'text',
		)
	);

	// Works Hero Title.
	$wp_customize->add_setting(
		'kataskevi_works_page_title',
		array(
			'default'           => 'Engineered reliability, <em class="text-primary italic">proven in the field.</em>',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_works_page_title',
		array(
			'label'   => esc_html__( 'Works Hero Headline', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_works',
			'type'    => 'textarea',
		)
	);

	// Works Hero Description.
	$wp_customize->add_setting(
		'kataskevi_works_page_desc',
		array(
			'default'           => sprintf(
				/* translators: %s: Company / Brand Name */
				esc_html__( 'From mission-critical colocation suites and high-throughput corporate towers to petrochemical terminals, explore how %s delivers turnkey safety, security, and IT infrastructure.', 'kataskevi' ),
				kataskevi_get_brand_name()
			),
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_works_page_desc',
		array(
			'label'   => esc_html__( 'Works Lead Description', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_works',
			'type'    => 'textarea',
		)
	);

	// 4 Works Commitments.
	$commitments_defaults = array(
		1 => array( 'val' => '100%', 'lbl' => 'Commissioning Compliance', 'sub' => 'Every system tested against NFPA & EN standards before handover.' ),
		2 => array( 'val' => 'Tier III & IV', 'lbl' => 'Prepared Infrastructure', 'sub' => 'Engineered for high-availability mission-critical operations.' ),
		3 => array( 'val' => 'Zero', 'lbl' => 'Lost-Time Incidents', 'sub' => 'Rigorous HSE protocols on every installation and maintenance site.' ),
		4 => array( 'val' => '24/7', 'lbl' => 'Dedicated Support Desk', 'sub' => 'Emergency response SLAs covering all contracted facilities.' ),
	);

	for ( $c = 1; $c <= 4; $c++ ) {
		$wp_customize->add_setting(
			'kataskevi_commit' . $c . '_val',
			array(
				'default'           => $commitments_defaults[ $c ]['val'],
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			'kataskevi_commit' . $c . '_val',
			array(
				/* translators: %d: commitment index */
				'label'   => sprintf( esc_html__( 'Commitment %d Number', 'kataskevi' ), $c ),
				'section' => 'kataskevi_sec_page_works',
				'type'    => 'text',
			)
		);

		$wp_customize->add_setting(
			'kataskevi_commit' . $c . '_lbl',
			array(
				'default'           => $commitments_defaults[ $c ]['lbl'],
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			'kataskevi_commit' . $c . '_lbl',
			array(
				/* translators: %d: commitment index */
				'label'   => sprintf( esc_html__( 'Commitment %d Title', 'kataskevi' ), $c ),
				'section' => 'kataskevi_sec_page_works',
				'type'    => 'text',
			)
		);

		$wp_customize->add_setting(
			'kataskevi_commit' . $c . '_sub',
			array(
				'default'           => $commitments_defaults[ $c ]['sub'],
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			'kataskevi_commit' . $c . '_sub',
			array(
				/* translators: %d: commitment index */
				'label'   => sprintf( esc_html__( 'Commitment %d Description', 'kataskevi' ), $c ),
				'section' => 'kataskevi_sec_page_works',
				'type'    => 'text',
			)
		);
	}

	// Works Bottom CTA Settings.
	$wp_customize->add_setting(
		'kataskevi_works_cta_eyebrow',
		array(
			'default'           => 'Plan Your Project',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_works_cta_eyebrow',
		array(
			'label'   => esc_html__( 'Bottom CTA Eyebrow', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_works',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'kataskevi_works_cta_title',
		array(
			'default'           => 'Have a facility requiring protection?',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_works_cta_title',
		array(
			'label'   => esc_html__( 'Bottom CTA Title', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_works',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'kataskevi_works_cta_desc',
		array(
			'default'           => 'From architectural blueprint review to full-scale engineering execution, our technical team works with facility managers, contractors, and project directors across Nigeria.',
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_works_cta_desc',
		array(
			'label'   => esc_html__( 'Bottom CTA Description', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_works',
			'type'    => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'kataskevi_works_cta_btn1',
		array(
			'default'           => 'Request Consultation',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_works_cta_btn1',
		array(
			'label'   => esc_html__( 'Bottom CTA Button 1 Text', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_works',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'kataskevi_works_cta_btn2',
		array(
			'default'           => 'View Capabilities',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_works_cta_btn2',
		array(
			'label'   => esc_html__( 'Bottom CTA Button 2 Text', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_works',
			'type'    => 'text',
		)
	);

	/*
	 * =========================================================================
	 * SECTION 11: PAGE - CONTACT & SITE SURVEY
	 * =========================================================================
	 */
	$wp_customize->add_section(
		'kataskevi_sec_page_contact',
		array(
			'title'       => esc_html__( '11. Page: Contact & Consultation', 'kataskevi' ),
			'panel'       => 'kataskevi_options_panel',
			'priority'    => 110,
			'description' => esc_html__( 'Configure the Contact page hero headlines, description, and urgent response callout card.', 'kataskevi' ),
		)
	);

	// Contact Hero Eyebrow.
	$wp_customize->add_setting(
		'kataskevi_contact_page_eyebrow',
		array(
			'default'           => 'Contact',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_contact_page_eyebrow',
		array(
			'label'   => esc_html__( 'Contact Hero Eyebrow', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_contact',
			'type'    => 'text',
		)
	);

	// Contact Hero Title.
	$wp_customize->add_setting(
		'kataskevi_contact_page_title',
		array(
			'default'           => 'Let\'s talk about <em class="text-primary italic">your site.</em>',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_contact_page_title',
		array(
			'label'   => esc_html__( 'Contact Hero Headline', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_contact',
			'type'    => 'textarea',
		)
	);

	// Contact Hero Description.
	$wp_customize->add_setting(
		'kataskevi_contact_page_desc',
		array(
			'default'           => 'Request a quote, book a site survey, or ask about maintenance for an existing system — our team will respond promptly.',
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_contact_page_desc',
		array(
			'label'   => esc_html__( 'Contact Lead Description', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_contact',
			'type'    => 'textarea',
		)
	);

	// Urgent Support Box Title.
	$wp_customize->add_setting(
		'kataskevi_contact_urgent_title',
		array(
			'default'           => 'Need urgent support?',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_contact_urgent_title',
		array(
			'label'   => esc_html__( 'Urgent Box Title', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_contact',
			'type'    => 'text',
		)
	);

	// Urgent Support Box Description.
	$wp_customize->add_setting(
		'kataskevi_contact_urgent_desc',
		array(
			'default'           => 'For active alarm faults, critical camera outages, or urgent life-safety requirements, contact our 24/7 technical desk directly:',
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_contact_urgent_desc',
		array(
			'label'   => esc_html__( 'Urgent Box Description', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_contact',
			'type'    => 'textarea',
		)
	);

	// Map: Show Map Toggle.
	$wp_customize->add_setting(
		'kataskevi_contact_show_map',
		array(
			'default'           => true,
			'sanitize_callback' => 'kataskevi_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_contact_show_map',
		array(
			'label'       => esc_html__( 'Display Office Map Section', 'kataskevi' ),
			'section'     => 'kataskevi_sec_page_contact',
			'type'        => 'checkbox',
		)
	);

	// Map Eyebrow.
	$wp_customize->add_setting(
		'kataskevi_contact_map_eyebrow',
		array(
			'default'           => 'Visit Our Office',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_contact_map_eyebrow',
		array(
			'label'   => esc_html__( 'Map Section Eyebrow', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_contact',
			'type'    => 'text',
		)
	);

	// Map Title.
	$wp_customize->add_setting(
		'kataskevi_contact_map_title',
		array(
			'default'           => 'Engineering Headquarters & Consultation Hub',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_contact_map_title',
		array(
			'label'   => esc_html__( 'Map Section Headline', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_contact',
			'type'    => 'text',
		)
	);

	// Map Description.
	$wp_customize->add_setting(
		'kataskevi_contact_map_desc',
		array(
			'default'           => 'Located in Victoria Island, Lagos. We welcome blueprint review meetings, technical audits, and project consultations.',
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_contact_map_desc',
		array(
			'label'   => esc_html__( 'Map Section Description', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_contact',
			'type'    => 'textarea',
		)
	);

	// Map Custom Embed Code / URL.
	$wp_customize->add_setting(
		'kataskevi_contact_map_embed',
		array(
			'default'           => '',
			'sanitize_callback' => 'kataskevi_sanitize_map_embed',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_contact_map_embed',
		array(
			'label'       => esc_html__( 'Custom Google Map Embed Code (Optional)', 'kataskevi' ),
			'description' => esc_html__( 'Paste an iframe embed code from Google Maps. Leave empty to automatically use your company address.', 'kataskevi' ),
			'section'     => 'kataskevi_sec_page_contact',
			'type'        => 'textarea',
		)
	);
}
add_action( 'customize_register', 'kataskevi_customize_register' );

/**
 * Safe helper to retrieve Theme Customizer options with fallback default.
 *
 * Intelligently distinguishes between a setting that has NEVER been saved in
 * the database (in which case the default fallback is returned) and a setting
 * where the user deliberately emptied/cleared the field in Customizer to delete
 * or hide the text/section (in which case the empty string is respected).
 *
 * @param string $key     Theme mod setting name.
 * @param mixed  $default Fallback value if setting has never been configured.
 * @return mixed The theme mod value or fallback.
 */
function kataskevi_get_mod( $key, $default = '' ) {
	$sentinel = '__KATASKEVI_MOD_NOT_SET__';
	$value    = get_theme_mod( $key, $sentinel );

	// Setting has never been customized or saved in database.
	if ( $sentinel === $value || null === $value ) {
		return $default;
	}

	return $value;
}

/**
 * Sanitize hex color with fallback to default if invalid.
 *
 * @param string                            $color   The user input color.
 * @param WP_Customize_Setting|object|null  $setting The setting instance.
 * @return string Sanitized hex color string.
 */
function kataskevi_sanitize_hex_color( $color, $setting = null ) {
	$sanitized = sanitize_hex_color( $color );
	if ( $sanitized ) {
		return $sanitized;
	}
	if ( is_object( $setting ) && isset( $setting->default ) ) {
		return $setting->default;
	}
	return '';
}

/**
 * Sanitize Google Map iframe embed code or raw embed URL.
 *
 * @param string $input Raw iframe embed code or map URL.
 * @return string Sanitized iframe or URL string.
 */
function kataskevi_sanitize_map_embed( $input ) {
	if ( empty( $input ) ) {
		return '';
	}

	$trimmed = trim( $input );

	// If a raw Google Maps URL is provided instead of an iframe tag.
	if ( 0 === strpos( $trimmed, 'http://' ) || 0 === strpos( $trimmed, 'https://' ) ) {
		return esc_url_raw( $trimmed );
	}

	$allowed_html = array(
		'iframe' => array(
			'src'             => true,
			'width'           => true,
			'height'          => true,
			'style'           => true,
			'allowfullscreen' => true,
			'loading'         => true,
			'referrerpolicy'  => true,
			'title'           => true,
			'frameborder'     => true,
			'aria-hidden'     => true,
			'tabindex'        => true,
		),
	);

	return wp_kses( $trimmed, $allowed_html );
}

/**
 * Convert a hex color string to rgba format with specified alpha opacity.
 *
 * @param string $hex   Hex color string (e.g. #3f75a1 or 3f75a1).
 * @param float  $alpha Opacity value between 0 and 1.
 * @return string RGBA color string.
 */
function kataskevi_hex_to_rgba( $hex, $alpha = 0.08 ) {
	$hex = ltrim( (string) $hex, '#' );
	if ( 3 === strlen( $hex ) ) {
		$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
		$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
		$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
	} elseif ( 6 === strlen( $hex ) ) {
		$r = hexdec( substr( $hex, 0, 2 ) );
		$g = hexdec( substr( $hex, 2, 2 ) );
		$b = hexdec( substr( $hex, 4, 2 ) );
	} else {
		return sprintf( 'rgba(63, 117, 161, %F)', (float) $alpha );
	}
	return sprintf( 'rgba(%d, %d, %d, %F)', $r, $g, $b, (float) $alpha );
}

/**
 * Generate dynamic CSS for customized colors.
 *
 * @return string Inline CSS.
 */
function kataskevi_get_customizer_css() {
	$preset_key = kataskevi_get_mod( 'kataskevi_color_preset', 'default' );
	$presets    = kataskevi_get_color_presets();
	$preset     = $presets[ $preset_key ]['colors'] ?? array();

	$default_primary          = $preset['primary'] ?? '#3f75a1';
	$default_primary_hover    = $preset['primary_hover'] ?? '#346389';
	$default_secondary        = $preset['secondary'] ?? '#f1f5f9';
	$default_secondary_hover  = $preset['secondary_hover'] ?? '#e2e8f0';
	$default_dark_bg          = $preset['dark_bg'] ?? '#171f27';
	$default_dark_text        = $preset['dark_text'] ?? '#ffffff';
	$default_background       = $preset['background'] ?? '#f8fafc';
	$default_foreground       = $preset['foreground'] ?? '#171f27';
	$default_muted_foreground = $preset['muted_foreground'] ?? '#60676f';
	$default_card             = $preset['card'] ?? '#ffffff';
	$default_border           = $preset['border'] ?? '#dbdee2';

	$primary          = kataskevi_get_mod( 'kataskevi_color_primary', $default_primary );
	$primary_hover    = kataskevi_get_mod( 'kataskevi_color_primary_hover', $default_primary_hover );
	$secondary        = kataskevi_get_mod( 'kataskevi_color_secondary', $default_secondary );
	$secondary_hover  = kataskevi_get_mod( 'kataskevi_color_secondary_hover', $default_secondary_hover );
	$dark_bg          = kataskevi_get_mod( 'kataskevi_color_dark_bg', $default_dark_bg );
	$dark_text        = kataskevi_get_mod( 'kataskevi_color_dark_text', $default_dark_text );
	$background       = kataskevi_get_mod( 'kataskevi_color_background', $default_background );
	$foreground       = kataskevi_get_mod( 'kataskevi_color_foreground', $default_foreground );
	$muted_foreground = kataskevi_get_mod( 'kataskevi_color_muted_foreground', $default_muted_foreground );
	$card             = kataskevi_get_mod( 'kataskevi_color_card', $default_card );
	$border           = kataskevi_get_mod( 'kataskevi_color_border', $default_border );

	$primary_subtle = kataskevi_hex_to_rgba( $primary, 0.08 );

	$css = ":root {
	--primary: {$primary};
	--primary-hover: {$primary_hover};
	--primary-subtle: {$primary_subtle};
	--primary-glow: {$primary};
	--secondary: {$secondary};
	--secondary-hover: {$secondary_hover};
	--secondary-foreground: #171f27;
	--background: {$background};
	--foreground: {$foreground};
	--card: {$card};
	--card-foreground: {$foreground};
	--muted-foreground: {$muted_foreground};
	--border: {$border};
	--border-focus: {$primary};
	--dark-section-bg: {$dark_bg};
	--dark-section-text: {$dark_text};
}
body {
	background-color: var(--background);
	color: var(--foreground);
}
.section-dark-cta {
	background-color: var(--dark-section-bg) !important;
	color: var(--dark-section-text) !important;
}
.section-dark-cta h2,
.section-dark-cta p,
.section-dark-cta .dark-cta-title,
.section-dark-cta .dark-cta-desc,
.section-dark-cta .dark-cta-lead,
.section-dark-cta .dark-cta-eyebrow,
.section-dark-cta .badge-pill-dark {
	color: var(--dark-section-text);
}
.section-dark-cta .btn-pill-light,
.section-dark-cta .btn-pill-light span,
.section-dark-cta .btn-pill-light .icon {
	background-color: #ffffff !important;
	color: #171f27 !important;
}
.section-dark-cta .btn-pill-light:hover,
.section-dark-cta .btn-pill-light:hover span,
.section-dark-cta .btn-pill-light:hover .icon {
	background-color: #f1f5f9 !important;
	color: #0f172a !important;
}
.section-dark-cta .btn-pill-outline-light {
	color: #ffffff !important;
	border-color: rgba(255, 255, 255, 0.4) !important;
}
";

	return $css;
}

