(function ($) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	jQuery(function ($) {
		jQuery("#pi_mmq_min_quantity_enabled").change(function () {
			min_quantity();
		});

		jQuery("#pi_mmq_max_quantity_enabled").change(function () {
			max_quantity();
		});

		jQuery("#pi_mmq_min_quantity_enabled, #pi_mmq_max_quantity_enabled").trigger("change");

		productSelector2("#pi_mmq_min_exclude_product", 2);
	});

	function min_quantity() {
		if (jQuery("#pi_mmq_min_quantity_enabled").is(":checked")) {
			jQuery("#row_pi_mmq_min_quantity").fadeIn();
		} else {
			jQuery("#row_pi_mmq_min_quantity").fadeOut();
		}
	}

	function max_quantity() {
		if (jQuery("#pi_mmq_max_quantity_enabled").is(":checked")) {
			jQuery("#row_pi_mmq_max_quantity").fadeIn();
		} else {
			jQuery("#row_pi_mmq_max_quantity").fadeOut();
		}
	}

	function productSelector2(selector, maximumSelectionLength = false) {
		jQuery(selector).selectWoo({
			maximumSelectionLength: maximumSelectionLength,
			minimumInputLength: 3,
			closeOnSelect: false,
			language: {

				maximumSelected: function (e) {

					return "You can only select " + e.maximum + " products" + ' - Upgrade Now and Select More';
				}
			},
			ajax: {
				url: ajaxurl,
				dataType: 'json',
				type: "GET",
				delay: 1000,
				data: function (params) {
					return {
						keyword: params.term,
						action: "pi_search_product",
					};
				},
				processResults: function (data) {
					return {
						results: data
					};

				},
			}
		});
	}

})(jQuery);
