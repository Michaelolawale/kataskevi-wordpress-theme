/**
 * File customizer-controls.js.
 *
 * Controls panel logic for Kataskevi Theme Customizer.
 * Automatically synchronizes color picker controls when a predefined color preset is selected.
 *
 * @package Kataskevi
 */
(function($) {
  'use strict';

  wp.customize.bind('ready', function() {
    wp.customize('kataskevi_color_preset', function(setting) {
      setting.bind(function(presetKey) {
        if (!window.kataskeviColorPresets || !window.kataskeviColorPresets[presetKey]) {
          return;
        }

        var colors = window.kataskeviColorPresets[presetKey];

        $.each(colors, function(prop, hex) {
          var settingId = 'kataskevi_color_' + prop;
          if (wp.customize(settingId)) {
            wp.customize(settingId).set(hex);

            var control = wp.customize.control(settingId);
            if (control && control.container) {
              var $picker = control.container.find('.wp-color-picker');
              if ($picker.length) {
                try {
                  $picker.iris('color', hex);
                } catch (e) {
                  $picker.val(hex);
                }
              }
            }
          }
        });
      });
    });
  });
})(jQuery);
