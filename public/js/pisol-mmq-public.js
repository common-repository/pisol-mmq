(function ($) {
	'use strict';

	jQuery(function ($) {

		var message = {
			fadeInTime: 600,
			fadeOuttime: 600,
			howLongToShow: pisol_mmq.howLongToShow,
			showContinues: pisol_mmq.showContinues,
			show: function () {
				if(!pisol_mmq.show_amount_bar) return;

				var bar_content = jQuery('.pisol-mmq-bar-message').html();
				if (bar_content == '') {
					message.hide();
					return;
				}

				message.hide_progress();
				jQuery('.pisol-mmq-bar-container').fadeIn(message.fadeInTime);
				if (!message.showContinues) {
					setTimeout(function () { message.hide(); }, message.howLongToShow);
				}
			},

			hide: function () {
				message.show_progress();
				jQuery('.pisol-mmq-bar-container').fadeOut(message.fadeOuttime);
			},

			close_button: function () {
				jQuery('.pisol-mmq-close').click(function () {
					message.hide();
				});
			},

			hide_progress: function () {
				jQuery('#pi-mmq-progress-circle').fadeOut();
			},

			show_progress: function () {
				jQuery('#pi-mmq-progress-circle').fadeIn();
			},

			close_progress: function () {
				jQuery('#pi-mmq-progress-circle').on('click', function () {
					message.hide_progress();
					message.show();
				});
			},


			update_cart: function () {
				jQuery(document).on('added_to_cart updated_wc_div updated_checkout', function (event) {
					message.amount_progress();
				});
			},

			amount_progress: function () {
				jQuery.ajax({
					url: pisol_mmq.ajax_url,
					cache: false,
					type: 'POST',
					dataType: 'json',
					data: {
						action: 'get_cart_mmq'
					},
					success: function (response) {

						var percent = response.percent > 100 ? 1 : (parseFloat(response.percent) / 100);

						if (jQuery('#pi-mmq-progress-circle').length !== 0) {
							if (jQuery().circleProgress) {
								jQuery('#pi-mmq-progress-circle').circleProgress('value', percent);
							}
						}

						jQuery(".pisol-mmq-bar-message").html(response.message_bar);
						message.show();

					}
				});
			}
		};
		message.show();
		message.update_cart();
		message.close_button();
		message.amount_progress();
		message.close_progress();
	});

	jQuery(function () {
		var percent = pisol_mmq.percent > 100 ? 1 : (parseFloat(pisol_mmq.percent) / 100);
		jQuery('#pi-mmq-progress-circle').circleProgress({
			value: percent,
			size: 70,
			fill: {
				gradient: ["red", "orange"]
			}
		});
	});

})(jQuery);

