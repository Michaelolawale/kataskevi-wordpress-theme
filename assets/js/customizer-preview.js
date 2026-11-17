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

  // Predefined Color Presets live preview
  wp.customize('kataskevi_color_preset', function(value) {
    value.bind(function(presetKey) {
      if (window.kataskeviColorPresets && window.kataskeviColorPresets[presetKey]) {
        var p = window.kataskeviColorPresets[presetKey];
        if (p.primary) {
          document.documentElement.style.setProperty('--primary', p.primary);
          document.documentElement.style.setProperty('--border-focus', p.primary);
          document.documentElement.style.setProperty('--primary-glow', p.primary);
        }
        if (p.primary_hover) document.documentElement.style.setProperty('--primary-hover', p.primary_hover);
        if (p.secondary) document.documentElement.style.setProperty('--secondary', p.secondary);
        if (p.secondary_hover) document.documentElement.style.setProperty('--secondary-hover', p.secondary_hover);
        if (p.dark_bg) document.documentElement.style.setProperty('--dark-section-bg', p.dark_bg);
        if (p.dark_text) document.documentElement.style.setProperty('--dark-section-text', p.dark_text);
        if (p.background) document.documentElement.style.setProperty('--background', p.background);
        if (p.foreground) {
          document.documentElement.style.setProperty('--foreground', p.foreground);
          document.documentElement.style.setProperty('--card-foreground', p.foreground);
        }
        if (p.muted_foreground) document.documentElement.style.setProperty('--muted-foreground', p.muted_foreground);
        if (p.card) document.documentElement.style.setProperty('--card', p.card);
        if (p.border) document.documentElement.style.setProperty('--border', p.border);
      }
    });
  });
})(jQuery);
