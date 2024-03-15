/**
 * @file webform-ajax-datalayer.js
 */
(function (Drupal, $) {
  'use strict';

  /**
   * Attaches the webformAjaxDataLayer behaviors
   *
   * @type {{attach: Drupal.webformAjaxDataLayer.attach}}
   */
  Drupal.behaviors.webformAjaxDataLayer = {
    attach: function (context, settings) {
      $.fn.webformAjaxPushToDataLayer = function(formId, status, params) {
        window.dataLayer = window.dataLayer || [];
        let dataLayerObject = {};
        dataLayerObject.event = 'formSubmissionAJAX';
        dataLayerObject.formName = formId;
        dataLayerObject.status = status;
        if (params !== "") {
          params = JSON.parse(params);
          let keys = Object.keys(params);
          keys.forEach((key) => {
            dataLayerObject[key] = params[key];
          });
        }
        dataLayer.push(dataLayerObject);
      }
    }
  };

}(Drupal, jQuery));