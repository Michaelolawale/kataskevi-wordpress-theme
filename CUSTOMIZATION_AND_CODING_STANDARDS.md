# Kataskevi Theme — Architecture, WordPress Coding Standards & Customizer Guide

This document details the complete technical implementation of the **Kataskevi** WordPress theme, including full adherence to the **[WordPress PHP Coding Standards (WPCS)](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/)** and the **Theme Customizer architecture** that allows 100% of the site's text content, headings, metrics, and media assets to be edited directly from the WordPress Customizer.

---

## 1. WordPress PHP Coding Standards (WPCS) Compliance

Every PHP file across the Kataskevi theme has been audited and refactored to comply with official WordPress core engineering standards:

### A. Yoda Conditions
In logical comparisons involving a variable and a constant or literal value, the constant/literal is always placed on the left side:
```php
// WPCS Compliant:
if ( 'all' === $slug ) { ... }
if ( 1 === $idx ) { ... }
if ( 'default' === $current_tpl ) { ... }
if ( true === $active ) { ... }
```

### B. Late Output Escaping
All variables and dynamic data printed to HTML templates are strictly escaped at the point of output:
- `esc_html()` — Used for plain text strings and labels.
- `esc_attr()` — Used for HTML tag attributes, IDs, and form values.
- `esc_url()` — Used for all anchor `href` and image `src` URLs.
- `wp_kses_post()` — Used for editorial headings allowing specific tags (such as `<em>` and `<br>`).
- `esc_html_e()` / `esc_attr_e()` / `esc_html__()` — Used for internationalized translatable strings with domain `'kataskevi'`.

### C. Input Sanitization
Every Customizer setting and AJAX form submission enforces appropriate sanitization callbacks:
- `sanitize_text_field()` for headlines, labels, phone numbers, and badges.
- `sanitize_textarea_field()` for multi-line copy and descriptive blocks.
- `sanitize_email()` for corporate and enquiry email addresses.
- `esc_url_raw()` for media image paths and external hyperlinks.
- `absint()` / `intval()` for numeric quantities and limits.
- Nonce verification via `wp_verify_nonce()` for CSRF protection on contact and site survey forms.

### D. File Structure & PHP Tag Discipline
- Pure PHP files (`functions.php`, `inc/customizer.php`) omit the closing `?>` tag to prevent accidental whitespace emissions and header injection bugs.
- Template files use standard full `<?php ... ?>` tags (no short tags).
- Spacing inside parentheses: spaces are consistently used after opening parentheses and before closing parentheses: `if ( ! empty( $var ) )`.
- Real tabs are used for indentation.

---

## 2. Theme Customizer Architecture (`Appearance -> Customize`)

All content—including **headings, paragraphs, button labels, button URLs, stats, and image assets**—is registered within a dedicated panel in the WordPress Customizer:

**Customizer Panel**: `Kataskevi Theme Settings` (`kataskevi_options_panel`)

The panel contains 11 modular sections:

```
Kataskevi Theme Settings
├── 1. Company & Contact Details
├── 2. Social Channels
├── 3. Header & Footer Copy
├── 4. Homepage: Hero & Live Stats
├── 5. Homepage: Services & Featured Works
├── 6. Homepage: Lifecycle Support Banner
├── 7. Homepage: Sectors & Dark CTA
├── 8. Page: Services Overview
├── 9. Page: About Kataskevi
├── 10. Page: Our Works & Commitments
├── 11. Page: Contact & Consultation
└── 12. Theme Updates (Bitbucket Repository)
```

---

## 3. Customizer Settings & Controls Breakdown

### Section 1: Company & Contact Details
| Setting ID | Control Type | Default Value | Description |
|---|---|---|---|
| `kataskevi_company_name` | Text | `get_bloginfo('name')` | Dynamic company/brand name automatically inherited from target site |
| `kataskevi_phone` | Text | `+234 800 123 4567` | Primary hotline / support line |
| `kataskevi_emergency_phone` | Text | `+234 802 345 6789` | 24/7 emergency dispatch line |
| `kataskevi_email` | Email | `get_option('admin_email')` | Official contact email (defaults to WordPress admin email) |
| `kataskevi_address` | Textarea | `Engineering Hub, Commercial District, Victoria Island, Lagos` | Physical headquarters address |
| `kataskevi_hours` | Text | `Mon - Fri: 8:00 AM - 6:00 PM \| 24/7 Emergency Response` | Business and support hours |

### Section 2: Social Channels
| Setting ID | Control Type | Default Value |
|---|---|---|
| `kataskevi_linkedin` | URL | `https://linkedin.com/company/kataskevi` |
| `kataskevi_twitter` | URL | `https://x.com/kataskevi` |
| `kataskevi_facebook` | URL | `https://facebook.com/kataskevi` |
| `kataskevi_instagram` | URL | `https://instagram.com/kataskevi` |
| `kataskevi_youtube` | URL | `https://youtube.com/@kataskevi` |
| `kataskevi_whatsapp` | URL | `https://wa.me/2348005282753` |

### Section 3: Header & Footer Copy
| Setting ID | Control Type | Default Value |
|---|---|---|
| `kataskevi_header_cta_text` | Text | `Get a Quote` |
| `kataskevi_footer_brand_desc` | Textarea | `Engineering, Safety, Information Technology and Telecommunications support services — design, supply, installation and maintenance, end to end.` |
| `kataskevi_footer_tagline` | Text | `Design · Supply · Install · Maintain` |

### Section 4: Homepage Hero Section
| Setting ID | Control Type | Default Value | Description |
|---|---|---|---|
| `kataskevi_hero_eyebrow` | Text | `Engineering · Safety · IT · Telecoms` | Hero eyebrow badge |
| `kataskevi_hero_title` | Textarea (HTML) | `Complete protection, <em class="text-primary italic">engineered end&nbsp;to&nbsp;end.</em>` | Main hero headline |
| `kataskevi_hero_description` | Textarea | `Kataskevi provides full-service support in the design, supply, installation and maintenance of fire safety, electronic security and IT/telecoms infrastructure — from site survey to commissioning and beyond.` | Hero narrative lead |
| `kataskevi_hero_btn1_text` | Text | `Explore Services` | Primary button text |
| `kataskevi_hero_btn2_text` | Text | `Request a Quote` | Secondary button text |
| **`kataskevi_hero_image`** | **Image Upload** (`WP_Customize_Image_Control`) | `assets/images/hero.jpg` | Full-width modern facility feature image |
| `kataskevi_hero_feat1_title` / `_desc` | Text / Textarea | `Data Preparation` / `Turnkey preparation of IT and telecoms facilities, clean data halls...` | Core Specialty 1 (Under Hero Image) |
| `kataskevi_hero_feat2_title` / `_desc` | Text / Textarea | `Raised Floor` / `Heavy-duty anti-static raised access flooring engineered for airflow...` | Core Specialty 2 (Under Hero Image) |
| `kataskevi_hero_feat3_title` / `_desc` | Text / Textarea | `Access Control` / `Multi-factor biometric access, smart card terminals, optical turnstiles...` | Core Specialty 3 (Under Hero Image) |

### Section 5: Homepage Featured Works (Carousel Slider)
| Setting ID | Control Type | Default Value | Description |
|---|---|---|---|
| `kataskevi_home_works_eyebrow` | Text | `Proven in the field` | Featured projects eyebrow |
| `kataskevi_home_works_title` | Text | `Featured works` | Featured projects headline |
| `kataskevi_home_works_desc` | Textarea | `A cross-section of recent installations where life safety, mission-critical uptime, and rigorous engineering were non-negotiable.` | Carousel lead description |

### Section 6: Homepage Lifecycle Banner & Steps
| Setting ID | Control Type | Default Value |
|---|---|---|
| **`kataskevi_lifecycle_image`** | **Image Upload** (`WP_Customize_Image_Control`) | `assets/images/band.jpg` |
| `kataskevi_lifecycle_eyebrow` | Text | `Whole-of-life support` |
| `kataskevi_lifecycle_title` | Text | `Beyond installation` |
| `kataskevi_lifecycle_desc` | Textarea | `In an ever-changing industry, we support our clientele towards maximising profit with a variety of services across the whole project lifecycle.` |
| `kataskevi_lifecycle_step1_title` / `_desc` | Text / Textarea | `Site Surveys` / `Assessment of your facility to establish exactly what your site requires.` |
| `kataskevi_lifecycle_step2_title` / `_desc` | Text / Textarea | `System Design & Specification` / `Engineered designs and precise specifications for every system we deliver.` |
| `kataskevi_lifecycle_step3_title` / `_desc` | Text / Textarea | `Project Commissioning` / `Structured commissioning and handover so systems perform from day one.` |
| `kataskevi_lifecycle_step4_title` / `_desc` | Text / Textarea | `Sales & Installation` / `Supply and professional installation backed by ongoing maintenance.` |

### Section 7: Homepage Sectors, Clients & Dark CTA
| Setting ID | Control Type | Default Value | Description |
|---|---|---|---|
| `kataskevi_home_sectors_eyebrow` | Text | `Sectors` | Industry sectors eyebrow label |
| `kataskevi_home_sectors_title` | Text | `Sectors we serve` | Industry sectors headline |
| `kataskevi_home_sectors_desc` | Textarea | `Every industry has its own risks, standards and uptime demands. We tailor each system to the environment it protects.` | Sector section lead copy |
| `kataskevi_clients_eyebrow` | Text | `Our Clients` | Clients dual marquee eyebrow |
| `kataskevi_clients_title` | Text | `Trusted by industry leaders across Nigeria` | Clients dual marquee headline |
| `kataskevi_clients_desc` | Textarea | `From Tier III data centers and telecommunications networks to corporate financial institutions and energy complexes, Nigeria's leading enterprises rely on Kataskevi for mission-critical infrastructure.` | Clients description copy |
| `kataskevi_dark_cta_eyebrow` | Text | `Request a survey` | Dark CTA badge |
| `kataskevi_dark_cta_title` | Text | `Ready to protect what matters?` | Dark CTA high-contrast headline |
| `kataskevi_dark_cta_desc` | Textarea | `Request a site survey or a quote — our team will design a system around your facility, your risks and your budget.` | Dark CTA narrative |
| `kataskevi_dark_cta_btn1_text` | Text | `Get a Quote` | Primary button text |
| `kataskevi_dark_cta_btn2_text` | Text | `View All Services` | Secondary button text |

### Section 8: Page — Services Overview
| Setting ID | Control Type | Default Value |
|---|---|---|
| `kataskevi_page_services_eyebrow` | Text | `Services` |
| `kataskevi_page_services_title` | Textarea (HTML) | `Engineering services, <em class="text-primary italic">one accountable partner.</em>` |
| `kataskevi_page_services_desc` | Textarea | `Complete lifecycle support across our four core engineering service pillars — from preliminary site survey to structured commissioning and ongoing preventative maintenance.` |
| `kataskevi_services_how_eyebrow` | Text | `How we work` |
| `kataskevi_services_how_title` | Text | `From survey to support` |
| `kataskevi_services_how_desc` | Textarea | `A structured path from first visit to long-term support, so every system performs from day one and keeps performing.` |
| `kataskevi_services_how_btn` | Text | `Start with a site survey` |

### Section 9: Page — About Kataskevi
| Setting ID | Control Type | Default Value |
|---|---|---|
| `kataskevi_about_hero_eyebrow` | Text | `About` |
| `kataskevi_about_hero_title` | Textarea (HTML) | `One partner for the systems <em class="text-primary italic">modern facilities depend on.</em>` |
| `kataskevi_about_hero_desc` | Textarea | `An Engineering, Safety, Information Technology and Telecommunications support services company — established to provide complete service in the design, supply, installation and maintenance of the systems modern facilities run on.` |
| **`kataskevi_about_image`** | **Image Upload** (`WP_Customize_Image_Control`) | `assets/images/about.jpg` |
| `kataskevi_about_why_eyebrow` | Text | `Why we exist` |
| `kataskevi_about_why_title` | Text | `Closing the gaps between systems` |
| `kataskevi_about_why_p1` to `_p4` | Textarea | Four detailed story narrative paragraphs explaining the integrated philosophy |
| `kataskevi_about_who_btn` | Text | `Discuss your facility` |

### Section 10: Page — Our Works & Commitments
| Setting ID | Control Type | Default Value |
|---|---|---|
| `kataskevi_works_page_eyebrow` | Text | `Case Studies & Delivered Systems` |
| `kataskevi_works_page_title` | Textarea (HTML) | `Engineered reliability, <em class="text-primary italic">proven in the field.</em>` |
| `kataskevi_works_page_desc` | Textarea | `From mission-critical colocation suites and high-throughput corporate towers to petrochemical terminals, explore how Kataskevi delivers turnkey safety, security, and IT infrastructure.` |
| `kataskevi_commit1_val` / `_lbl` / `_sub` | Text | `100%` / `Commissioned on Schedule` / `Rigorous milestone governance from design to live handover.` |
| `kataskevi_commit2_val` / `_lbl` / `_sub` | Text | `Tier III & IV` / `Prepared Infrastructure` / `Engineered for high-availability mission-critical operations.` |
| `kataskevi_commit3_val` / `_lbl` / `_sub` | Text | `Zero` / `Lost-Time Incidents` / `Rigorous HSE protocols on every installation and maintenance site.` |
| `kataskevi_commit4_val` / `_lbl` / `_sub` | Text | `24/7` / `Dedicated Support Desk` / `Emergency response SLAs covering all contracted facilities.` |

### Section 11: Page — Contact & Consultation
| Setting ID | Control Type | Default Value |
|---|---|---|
| `kataskevi_contact_page_eyebrow` | Text | `Contact` |
| `kataskevi_contact_page_title` | Textarea (HTML) | `Let's talk about <em class="text-primary italic">your site.</em>` |
| `kataskevi_contact_page_desc` | Textarea | `Request a quote, book a site survey, or ask about maintenance for an existing system — our team will respond promptly.` |
| `kataskevi_contact_urgent_title` | Text | `Need urgent support?` |
| `kataskevi_contact_urgent_desc` | Textarea | `For active alarm faults, critical camera outages, or urgent life-safety requirements, contact our 24/7 technical desk directly:` |

### Section 12: Theme Updates (Bitbucket Repository)
| Setting ID | Control Type | Default Value | Description |
|---|---|---|---|
| `kataskevi_bitbucket_repo` | URL | `https://bitbucket.org/unionolivia/kataskevi` | Bitbucket Repository HTTPS URL |
| `kataskevi_bitbucket_branch` | Text | `main` | Release branch to track |
| `kataskevi_bitbucket_user` | Text | *(empty)* | Bitbucket account username |
| `kataskevi_bitbucket_token` | Password | *(empty)* | Bitbucket App Password (`read:repository:bitbucket`) |

> [!NOTE]
> These credentials can also be managed directly in the dedicated admin interface under **Appearance &rarr; Theme Updates**, complete with an instant "Check for Updates" trigger and step-by-step instructions.

---

## 4. Helper Function: `kataskevi_get_mod()`

All templates retrieve settings through the resilient helper declared in `inc/customizer.php`:

```php
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
```

This guarantees that:
1. **Fresh Installations**: Before an administrator modifies any setting in the Customizer, the site renders all default editorial text, images, and metrics smoothly.
2. **Explicit Deletion**: When an administrator clears or empties a field in the Customizer (e.g. to remove an eyebrow, hide an extra button, omit a phone number, or eliminate a section), `kataskevi_get_mod()` respects the deletion and returns an empty string instead of forcing hardcoded fallback text back onto the page. Templates cleanly omit the corresponding markup.

---

## 5. File Inventory & Verification

| File Path | Description | WPCS Status | Customizer Integrated |
|---|---|:---:|:---:|
| `inc/customizer.php` | Customizer settings, controls, and image uploaders | ✅ Passed | Core Engine |
| `inc/updater.php` | Plugin Update Checker (PUC) Bitbucket updater | ✅ Passed | Auto-updates |
| `functions.php` | Theme setup, CPT registration, navigation, icons, AJAX | ✅ Passed | Loads `inc/customizer.php` |
| `header.php` | Responsive header, SVG shield emblem, navigation, CTA | ✅ Passed | Header CTA & phone |
| `footer.php` | 4-column footer, social strip, copyright, tagline | ✅ Passed | Brand desc, social, tagline |
| `front-page.php` | Homepage hero, stats, services, works, lifecycle, CTA | ✅ Passed | Hero, stats, lifecycle, CTA |
| `page-templates/page-services.php` | Service rows, specs cards, how we work lifecycle | ✅ Passed | Headings, lifecycle, CTA |
| `page-templates/page-about.php` | About hero, full-width media, story, pillars, sectors | ✅ Passed | Headings, media image, story |
| `page-templates/page-works.php` | Works filterable portfolio, 4 commitments matrix | ✅ Passed | Headings, 4 commitments |
| `page-templates/page-contact.php` | 2-column contact channels, urgent card, AJAX form | ✅ Passed | Headings, urgent card, info |
| `single-project.php` | Individual case study template with technical specs | ✅ Passed | Case metadata, quote card |
| `single-service.php` | Individual service detail template with methodology & specs | ✅ Passed | Service metadata, CPT, CTA |
| `search.php` | Search results template with editorial pagination | ✅ Passed | Localized UI strings |
| `404.php` | 404 error template with quick return navigation | ✅ Passed | Localized UI strings |
| `archive.php` | Category and archive listing template | ✅ Passed | Localized UI strings |
| `index.php` | Default blog / news listing template | ✅ Passed | Localized UI strings |
| `page.php` | Standard fallback WordPress page template | ✅ Passed | Localized UI strings |
| `single.php` | Standard single blog post template | ✅ Passed | Localized UI strings |
| `template-parts/content.php` | Editorial post card layout | ✅ Passed | Localized UI strings |
| `template-parts/content-none.php` | Empty search/archive state | ✅ Passed | Localized UI strings |

---

## 6. How to Edit Content in WordPress

1. Log into your WordPress administrative dashboard (`/wp-admin`).
2. Navigate to **Appearance** &rarr; **Customize**.
3. Click on the **Kataskevi Theme Settings** panel.
4. Select any of the 11 sections to edit headlines, paragraphs, or replace any image (Hero, Lifecycle banner, or About banner).
5. The live preview immediately reflects your adjustments. Click **Publish** to save changes to production.

