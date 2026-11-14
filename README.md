# Kataskevi — WordPress Engineering & Safety Theme

[![WordPress](https://img.shields.io/badge/WordPress-5.8%2B-blue.svg)](https://wordpress.org)
[![PHP](https://img.shields.io/badge/PHP-7.4%2B%20%7C%208.2%20Ready-777bb4.svg)](https://php.net)
[![License: GPL v2+](https://img.shields.io/badge/License-GPL%20v2%2B-green.svg)](http://www.gnu.org/licenses/gpl-2.0.html)
[![Standards: WPCS](https://img.shields.io/badge/Coding%20Standards-WPCS-orange.svg)](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/)

A bespoke, high-performance WordPress theme engineered for **Kataskevi** — an Engineering, Safety, Information Technology, and Telecommunications support services company delivering turnkey design, supply, installation, and lifecycle maintenance for mission-critical facilities.

Crafted with an editorial aesthetic inspired by modern architectural publications: high-contrast layout, clean hairline grid boundaries, paired typography (*Instrument Serif* + *Work Sans*), and an industrial palette of warm slate (`#f8fafc`), deep charcoal (`#171f27`), and technical steel blue (`#3f75a1`).

---

## Key Features

- **Automatic Updates via Bitbucket (PUC v6.0)**: Bundled with YahnisElsts Plugin Update Checker library to provide seamless one-click theme updates directly from `bitbucket.org/unionolivia/kataskevi` inside the WordPress admin dashboard (`Dashboard -> Updates`).
- **100% Editable via WordPress Customizer**: Every single text headline, eyebrow label, lead paragraph, button text, live statistic number, and media asset is editable directly in **Appearance &rarr; Customize** without touching any code.
- **Dedicated Media Uploaders**: Uses `WP_Customize_Image_Control` to allow site administrators to upload or select custom images for the Homepage Hero, the Whole-of-Life Support Banner, and the About page showcase directly from the WordPress Media Library.
- **Custom Post Type: Projects (`project`)**: Dedicated portfolio architecture with custom slug `/works/`, sector taxonomy (`project_sector`), project metadata (Client, Year, Location, Accountability, Compliance), and dynamic metric highlights.
- **Interactive Works Portfolio**: Live instant client-side sector filter buttons (All Projects, Data Centers & Telecoms, Logistics & Freight, Commercial & Corporate, Industrial & Energy, Hospitality & Leisure, Healthcare).
- **Auto-Provisioning on Activation**: Automatically registers and assigns the core pages (*Services*, *Our Works*, *About*, *Contact*) with their bespoke templates, builds the primary navigation menu, and seeds realistic enterprise case studies.
- **Interactive Consultation Form**: Asynchronous AJAX site survey form with instant client-side validation, server-side sanitization, and CSRF protection via WordPress nonces.
- **Strict WPCS Compliance**: 100% compliant with the official [WordPress PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/) (Yoda conditions, late output escaping, input sanitization, and no trailing PHP tags in pure PHP files).
- **Fully Responsive & Accessible**: Fluid typography, responsive grids, keyboard-navigable drawer, and high-contrast accessibility standards.

---

## System Requirements

| Requirement | Minimum | Recommended |
|---|---|---|
| **WordPress** | 5.8+ | 6.7+ |
| **PHP** | 7.4 | 8.2+ |
| **Database** | MySQL 5.7+ / MariaDB 10.3+ | MySQL 8.0+ |
| **Web Server** | Apache / Nginx | Nginx with FastCGI |

---

## Directory Structure

```text
kataskevi/
├── assets/
│   ├── css/
│   │   └── main.css             # Compiled styling, custom properties, responsive grid
│   ├── js/
│   │   └── main.js              # Header toggle, AJAX form handling, portfolio filtering
│   └── images/
│       ├── hero.jpg             # Default hero infrastructure image
│       ├── band.jpg             # Default whole-of-life banner image
│       ├── about.jpg            # Default engineering schematics image
│       ├── logo.svg             # Primary Kataskevi logo
│       └── projects/            # Seeded case study photography
├── inc/
│   ├── customizer.php           # Theme Customizer settings, controls, and image uploaders
│   ├── updater.php              # Plugin Update Checker (PUC) Bitbucket integration
│   └── plugin-update-checker/   # YahnisElsts Plugin Update Checker library v6.0
├── page-templates/
│   ├── page-about.php           # Template: About Us
│   ├── page-contact.php         # Template: Contact & Site Survey
│   ├── page-services.php        # Template: Services Overview
│   └── page-works.php           # Template: Our Works (Filterable Portfolio)
├── template-parts/
│   ├── content.php              # Standard post card markup
│   └── content-none.php         # Empty query state layout
├── 404.php                      # Error 404 page template
├── archive.php                  # Archive and taxonomy listing template
├── footer.php                   # 4-column footer, social strip, copyright bar
├── front-page.php               # Homepage template
├── functions.php                # Theme setup, CPT registration, navigation, AJAX
├── header.php                   # Responsive header, SVG shield monogram emblem
├── index.php                    # Default blog / news loop
├── page.php                     # Standard page template
├── search.php                   # Search results template
├── single-project.php           # Single project case study detail template
├── single.php                   # Single article template
├── style.css                    # Theme stylesheet header declaration
├── CUSTOMIZATION_AND_CODING_STANDARDS.md # Detailed customizer & standards reference
└── README.md                    # This documentation file
```

---

## Installation & Setup

### 1. Installation
1. Download or clone this repository into your WordPress themes directory:
   ```bash
   cd wp-content/themes/
   git clone https://bitbucket.org/unionolivia/kataskevi.git
   ```
2. In the WordPress Admin Dashboard, navigate to **Appearance &rarr; Themes**.
3. Locate **Kataskevi** and click **Activate**.

### 2. Automatic Configuration
Upon theme activation, Kataskevi automatically:
- Creates the four core pages if they don't already exist:
  - **Services** (assigned to `page-templates/page-services.php`)
  - **Our Works** (assigned to `page-templates/page-works.php`)
  - **About** (assigned to `page-templates/page-about.php`)
  - **Contact** (assigned to `page-templates/page-contact.php`)
- Sets the front page display mode and configures the primary navigation menu.
- Seeds default industry case studies into the `project` Custom Post Type.

---

## Managing Content with the Customizer

All text and image content can be configured via **Appearance &rarr; Customize &rarr; Kataskevi Theme Settings**:

1. **Company & Contact Details**: Phone hotline, 24/7 emergency dispatch line, email address, physical office location, and business hours.
2. **Social Channels**: URLs for LinkedIn, X (Twitter), Facebook, Instagram, YouTube, and WhatsApp.
3. **Header & Footer Copy**: Navigation CTA label, footer brand narrative, and copyright tagline.
4. **Homepage: Hero Section**: Hero eyebrow, headline, lead summary, CTA button labels and URLs, and hero feature image upload.
5. **Homepage: Services & Featured Works**: Headings and introductory copy for core disciplines and featured project cards.
6. **Homepage: Lifecycle Support Banner**: Lifecycle banner image upload, section headline, lead summary, and the 4-step lifecycle cards (*Site Surveys, Design, Commissioning, Sales & Installation*).
7. **Homepage: Sectors, Clients & Dark CTA**: Sectors header copy, Our Clients dual infinite marquee copy (eyebrow, headline, description), and Dark CTA banner headline, description, and action button labels.
8. **Page: Services Overview**: Services page hero text, "How We Work" lifecycle copy, and CTA button text.
9. **Page: About Kataskevi**: Hero headline, full-width media banner upload, the 4 "Why We Exist" story paragraphs, and the "Who We Serve" action button.
10. **Page: Our Works & Commitments**: Portfolio hero copy and the 4 delivery performance metrics (*100% Commissioned, Tier III & IV Prepared, Zero Incidents, 24/7 Support*).
11. **Page: Contact & Consultation**: Contact hero headlines, description, and the 24/7 urgent support callout card.

For a full reference table of all Customizer setting IDs, default values, and sanitize callbacks, refer to [`CUSTOMIZATION_AND_CODING_STANDARDS.md`](CUSTOMIZATION_AND_CODING_STANDARDS.md).

---

## WordPress PHP Coding Standards (WPCS)

This theme strictly follows the **WordPress PHP Coding Standards**:

- **Yoda Conditions**: Evaluated with literal/constant first:
  ```php
  if ( 'all' === $slug ) { ... }
  if ( 1 === $idx ) { ... }
  ```
- **Late Output Escaping**: All dynamic variables are escaped using `esc_html()`, `esc_attr()`, `esc_url()`, or `wp_kses_post()`.
- **Input Sanitization**: Superglobals and form inputs are thoroughly sanitized with `sanitize_text_field()`, `sanitize_email()`, `sanitize_textarea_field()`, `esc_url_raw()`, and `absint()`.
- **Security & Nonce Verification**: AJAX inquiry submissions require a valid nonce verified with `check_ajax_referer()`.
- **Pure PHP Files**: `functions.php` and `inc/customizer.php` omit the closing `?>` tag.

---

## Automatic Theme Updates (PUC Integration)

Kataskevi includes native support for automatic updates via the **[YahnisElsts Plugin Update Checker (PUC)](https://github.com/YahnisElsts/plugin-update-checker)** library (v6.0), configured to track the `main` branch of the Bitbucket repository:

- **Repository**: `https://bitbucket.org/unionolivia/kataskevi`
- **Branch**: `main`

### How It Works:
1. When a new version tag or commit is pushed to the `main` branch on Bitbucket with a bumped `Version:` header in `style.css`, WordPress automatically detects the new release under **Dashboard &rarr; Updates** and **Appearance &rarr; Themes**.
2. Administrators can update the theme with a single click, just like an official repository theme.

### Private Repository Authentication (Backend UI)

You can configure private repository credentials directly in the WordPress dashboard:

1. **Dedicated Admin Page**: Navigate to **Appearance &rarr; Theme Updates** (`themes.php?page=kataskevi-updates`).
   - Enter your **Bitbucket Repository URL**, **Branch**, **Bitbucket Username**, and **Bitbucket App Password**.
   - Includes real-time status indicators, masked credential storage, and an instant **"Check for Updates Now"** button.
   - Includes step-by-step instructions on generating an App Password with `read:repository:bitbucket` permissions.
2. **WordPress Customizer**: Navigate to **Appearance &rarr; Customize &rarr; Kataskevi Theme Settings &rarr; 12. Theme Updates (Bitbucket)**.
3. **Optional wp-config.php Constants**: You can also define constants in `wp-config.php`:

```php
// Optional: In wp-config.php
define( 'KATASKEVI_BITBUCKET_USER', 'your_bitbucket_username' );
define( 'KATASKEVI_BITBUCKET_TOKEN', 'your_bitbucket_app_password' );
```

Alternatively, use the filter hook in custom code or an mu-plugin:

```php
add_filter( 'kataskevi_bitbucket_credentials', function( $creds ) {
    return array(
        'username'  => 'your_bitbucket_username',
        'api_token' => 'your_app_password',
    );
} );
```

---

## Remote Repository & Source Control

- **Remote**: `git@bitbucket.org:unionolivia/kataskevi.git`
- **Main Branch**: `main`

To push updates:
```bash
git add -A
git commit -m "Your commit message"
git push origin main
```

---

## License

Kataskevi is licensed under the [GNU General Public License v2 or later](http://www.gnu.org/licenses/gpl-2.0.html).

```text
Kataskevi WordPress Theme
Copyright (C) 2026 Kataskevi

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.
```

