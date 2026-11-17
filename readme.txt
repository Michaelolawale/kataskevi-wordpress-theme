=== Kataskevi ===
Contributors: unionolivia
Tags: engineering, safety, telecommunications, corporate, responsive-layout, modern, grid-layout, accessibility-ready
Requires at least: 5.8
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: v2.1.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

High-performance, bespoke WordPress theme for Kataskevi Engineering & Safety Support Services.

== Description ==

High-performance, bespoke WordPress theme for Kataskevi - Engineering, Safety, Information Technology, and Telecommunications support services. Includes turnkey support for Fire Suppression, Electronic Security, IT/Telecoms Site Prep, Access Control, and Industrial PPE.

== Installation ==

1. Upload the theme zip file via WordPress Admin under Appearance > Themes > Add New > Upload Theme, or extract to `/wp-content/themes/kataskevi`.
2. Activate Kataskevi through the Themes menu in WordPress.
3. Configure your Bitbucket repository credentials under Appearance > Theme Updates for automatic one-click updates.
4. Optional: Activate the Kataskevi Child Theme (`kataskevi-child`) for custom template and function modifications.

== Frequently Asked Questions ==

= How do theme updates work? =
Updates are distributed through the integrated Plugin Update Checker (PUC) v6.0 connected directly to the Bitbucket repository (`https://bitbucket.org/unionolivia/kataskevi`). When a new release tag is published on Bitbucket, WordPress detects the update and allows one-click upgrading directly from Appearance > Themes or Dashboard > Updates.

= How do I configure private Bitbucket updates? =
Navigate to Appearance > Theme Updates in your WordPress admin dashboard and input your Bitbucket Username (`unionolivia`) along with an App Password configured with `Repositories: Read` permissions.

== Changelog ==

= 2.1.3 =
* Automated release verification update for Plugin Update Checker (PUC) via Bitbucket API.
* Added live connection diagnostic telemetry in Appearance > Theme Updates.
* Enhanced Atlassian email and Bitbucket App Password authentication handling.

= 2.1.2 =
* Added dedicated Single Service template (`single-service.php`) with dynamic custom post type integration and Customizer fallback content.
* Refined Services page layout with balanced 24rem media proportions and streamlined single CTA per card.
* Replaced static Works grid with responsive touch-enabled carousel featuring smooth drag, swipe, and arrow navigation.
* Created standalone Kataskevi Child Theme (`kataskevi-child`) for safe developer customizations.
* Full WPCS (WordPress PHP Coding Standards) and Web Content Accessibility Guidelines (WCAG) compliance.

= 2.1.1 =
* Standard WordPress custom-logo styling support with auto-fit geometry.
* Flexible header branding with dynamic site-title fallback.
* Resilient client logo renderer for both media library attachments and fallback paths.

= 2.1.0 =
* Initial public release with complete Customizer controls, responsive typography, and security hardened templates.
