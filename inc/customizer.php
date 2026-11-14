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
	 * SECTION 2: SOCIAL CHANNELS
	 * =========================================================================
	 */
	$wp_customize->add_section(
		'kataskevi_sec_social',
		array(
			'title'       => esc_html__( '2. Social Channels', 'kataskevi' ),
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

	/*
	 * =========================================================================
	 * SECTION 5: HOMEPAGE SERVICES & FEATURED WORKS
	 * =========================================================================
	 */
	$wp_customize->add_section(
		'kataskevi_sec_home_sections',
		array(
			'title'       => esc_html__( '5. Homepage: Services & Featured Works', 'kataskevi' ),
			'panel'       => 'kataskevi_options_panel',
			'priority'    => 50,
			'description' => esc_html__( 'Headings and introductory text for the core disciplines and featured projects sections.', 'kataskevi' ),
		)
	);

	// Services Section Eyebrow.
	$wp_customize->add_setting(
		'kataskevi_home_services_eyebrow',
		array(
			'default'           => 'Services',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_home_services_eyebrow',
		array(
			'label'   => esc_html__( 'Services Eyebrow', 'kataskevi' ),
			'section' => 'kataskevi_sec_home_sections',
			'type'    => 'text',
		)
	);

	// Services Section Title.
	$wp_customize->add_setting(
		'kataskevi_home_services_title',
		array(
			'default'           => 'What we deliver',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_home_services_title',
		array(
			'label'   => esc_html__( 'Services Headline', 'kataskevi' ),
			'section' => 'kataskevi_sec_home_sections',
			'type'    => 'text',
		)
	);

	// Services Section Description.
	$wp_customize->add_setting(
		'kataskevi_home_services_desc',
		array(
			'default'           => 'Seven specialised service lines, delivered as complete systems — designed, supplied, installed and maintained by one accountable partner.',
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_home_services_desc',
		array(
			'label'   => esc_html__( 'Services Lead Description', 'kataskevi' ),
			'section' => 'kataskevi_sec_home_sections',
			'type'    => 'textarea',
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
			'default'           => 'Whole-of-life support',
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
			'default'           => 'In an ever-changing industry, we support our clientele towards maximising profit with a variety of services across the whole project lifecycle.',
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
			'description' => esc_html__( 'Configure the header titles and "How we work" lifecycle section of the Services page template.', 'kataskevi' ),
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
			'default'           => 'Seven service lines, <em class="text-primary italic">one accountable partner.</em>',
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
			'default'           => 'Complete service in the design, supply, installation and maintenance of every system below — one partner, one accountable team, from survey to handover.',
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

	// How We Work Eyebrow.
	$wp_customize->add_setting(
		'kataskevi_services_how_eyebrow',
		array(
			'default'           => 'How we work',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_services_how_eyebrow',
		array(
			'label'   => esc_html__( 'How We Work Eyebrow', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_services',
			'type'    => 'text',
		)
	);

	// How We Work Title.
	$wp_customize->add_setting(
		'kataskevi_services_how_title',
		array(
			'default'           => 'From survey to support',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_services_how_title',
		array(
			'label'   => esc_html__( 'How We Work Headline', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_services',
			'type'    => 'text',
		)
	);

	// How We Work Desc.
	$wp_customize->add_setting(
		'kataskevi_services_how_desc',
		array(
			'default'           => 'A structured path from first visit to long-term support, so every system performs from day one and keeps performing.',
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_services_how_desc',
		array(
			'label'   => esc_html__( 'How We Work Lead', 'kataskevi' ),
			'section' => 'kataskevi_sec_page_services',
			'type'    => 'textarea',
		)
	);

	// How We Work Button.
	$wp_customize->add_setting(
		'kataskevi_services_how_btn',
		array(
			'default'           => 'Start with a site survey',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'kataskevi_services_how_btn',
		array(
			'label'   => esc_html__( 'How We Work Action Button', 'kataskevi' ),
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
			'default'           => 'Case Studies & Delivered Systems',
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

	/*
	 * =========================================================================
	 * SECTION 12: THEME UPDATES & BITBUCKET REPOSITORY
	 * =========================================================================
	 */
	$wp_customize->add_section(
		'kataskevi_sec_theme_updates',
		array(
			'title'       => esc_html__( '12. Theme Updates (Bitbucket)', 'kataskevi' ),
			'panel'       => 'kataskevi_options_panel',
			'priority'    => 120,
			'description' => esc_html__( 'Configure your Bitbucket repository and private access token to receive automated one-click theme updates.', 'kataskevi' ),
		)
	);

	// Bitbucket Repo URL.
	$wp_customize->add_setting(
		'kataskevi_bitbucket_repo',
		array(
			'default'           => 'https://bitbucket.org/unionolivia/kataskevi',
			'type'              => 'option',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'kataskevi_bitbucket_repo',
		array(
			'label'   => esc_html__( 'Bitbucket Repository URL', 'kataskevi' ),
			'section' => 'kataskevi_sec_theme_updates',
			'type'    => 'url',
		)
	);

	// Bitbucket Branch.
	$wp_customize->add_setting(
		'kataskevi_bitbucket_branch',
		array(
			'default'           => 'main',
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'kataskevi_bitbucket_branch',
		array(
			'label'   => esc_html__( 'Target Branch', 'kataskevi' ),
			'section' => 'kataskevi_sec_theme_updates',
			'type'    => 'text',
		)
	);

	// Bitbucket Username.
	$wp_customize->add_setting(
		'kataskevi_bitbucket_user',
		array(
			'default'           => '',
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'kataskevi_bitbucket_user',
		array(
			'label'       => esc_html__( 'Bitbucket Username', 'kataskevi' ),
			'description' => esc_html__( 'Your Bitbucket account username.', 'kataskevi' ),
			'section'     => 'kataskevi_sec_theme_updates',
			'type'        => 'text',
		)
	);

	// Bitbucket App Password / Token.
	$wp_customize->add_setting(
		'kataskevi_bitbucket_token',
		array(
			'default'           => '',
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'kataskevi_bitbucket_token',
		array(
			'label'       => esc_html__( 'Bitbucket App Password / Token', 'kataskevi' ),
			'description' => esc_html__( 'Generate in Bitbucket Settings -> App passwords with "Repositories: Read" permission.', 'kataskevi' ),
			'section'     => 'kataskevi_sec_theme_updates',
			'type'        => 'password',
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

