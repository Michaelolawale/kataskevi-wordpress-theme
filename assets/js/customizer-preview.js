/**
 * File customizer-preview.js.
 *
 * Handles real-time live preview for Kataskevi Theme Customizer color settings.
 *
 * @package Kataskevi
 */

(function($) {
  'use strict';

  // Primary Brand Color
  wp.customize('kataskevi_color_primary', function(value) {
    value.bind(function(to) {
      document.documentElement.style.setProperty('--primary', to);
      document.documentElement.style.setProperty('--border-focus', to);
      document.documentElement.style.setProperty('--primary-glow', to);
    });
  });

  // Primary Hover Color
  wp.customize('kataskevi_color_primary_hover', function(value) {
    value.bind(function(to) {
      document.documentElement.style.setProperty('--primary-hover', to);
    });
  });

  // Secondary Brand Color
  wp.customize('kataskevi_color_secondary', function(value) {
    value.bind(function(to) {
      document.documentElement.style.setProperty('--secondary', to);
    });
  });

  // Secondary Hover Color
  wp.customize('kataskevi_color_secondary_hover', function(value) {
    value.bind(function(to) {
      document.documentElement.style.setProperty('--secondary-hover', to);
    });
  });

  // Dark Section Background
  wp.customize('kataskevi_color_dark_bg', function(value) {
    value.bind(function(to) {
      document.documentElement.style.setProperty('--dark-section-bg', to);
    });
  });

  // Dark Section Text Color
  wp.customize('kataskevi_color_dark_text', function(value) {
    value.bind(function(to) {
      document.documentElement.style.setProperty('--dark-section-text', to);
    });
  });

  // Page Body Background
  wp.customize('kataskevi_color_background', function(value) {
    value.bind(function(to) {
      document.documentElement.style.setProperty('--background', to);
    });
  });

  // Headings & Main Text Color
  wp.customize('kataskevi_color_foreground', function(value) {
    value.bind(function(to) {
      document.documentElement.style.setProperty('--foreground', to);
      document.documentElement.style.setProperty('--card-foreground', to);
    });
  });

  // Muted / Subtitle Text Color
  wp.customize('kataskevi_color_muted_foreground', function(value) {
    value.bind(function(to) {
      document.documentElement.style.setProperty('--muted-foreground', to);
    });
  });

  // Card Background
  wp.customize('kataskevi_color_card', function(value) {
    value.bind(function(to) {
      document.documentElement.style.setProperty('--card', to);
    });
  });

  // Border & Divider Color
  wp.customize('kataskevi_color_border', function(value) {
    value.bind(function(to) {
      document.documentElement.style.setProperty('--border', to);
    });
  });
})(jQuery);
