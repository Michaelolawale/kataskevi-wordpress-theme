# Kataskevi Child Theme

Official WordPress child theme for **Kataskevi** (Engineering, Safety, IT & Telecoms Support Services).

## Why Use This Child Theme?
When the parent theme (`kataskevi`) receives updates via the Plugin Update Checker (PUC) or Bitbucket, any custom PHP functions or style edits made directly in the parent theme files would be overwritten. 

By activating this child theme:
- Your custom CSS styles in `style.css` are preserved across all parent theme updates.
- Custom action hooks, filters, and functions added to `functions.php` remain intact.
- You can override any template file (e.g. `header.php`, `footer.php`, `front-page.php`) by placing a copy of it inside this child theme directory.

## Installation & Activation
1. Ensure the parent theme **Kataskevi** (`kataskevi`) is installed in `wp-content/themes/kataskevi`.
2. Upload and install `kataskevi-child.zip` under **Appearance &rarr; Themes &rarr; Add New &rarr; Upload Theme** (or place the `kataskevi-child` folder into `wp-content/themes/`).
3. Click **Activate**.

## Enqueued Styles
The child theme's `functions.php` automatically enqueues:
1. `kataskevi-main-style` (Compiled design system)
2. `kataskevi-parent-style` (Parent stylesheet header)
3. `kataskevi-child-style` (Child theme custom CSS overrides)
