/**
 * site.js
 * Base logic, feel free to replace with your own and/or use the libraries of your choice
 */
Site = Class.extend({
	init: function(options) {
		var obj = this,
			opts = _.defaults(options, {
				// Add options here
			});
		jQuery(document).ready(function($) {
			obj.onDomReady($);
		});
	},
	onDomReady: function($) {
		var obj = this;

		// Tabs Miniplugin
		$('.tab-list li a').on('click', function(e) {
			e.preventDefault();
			var el = $(this),
				li = el.closest('li'),
				target = $( el.attr('href') );
				li.addClass('selected').siblings('li').removeClass('selected');
				target.addClass('active').siblings('.tab').removeClass('active');
		});

		$('.tab-list').each(function() {
			var el = $(this);
			el.find('li a').first().trigger('click');
		});

		/**Clipboard function */
		$('.js-copy').on('click', function(e) {
			e.preventDefault();
			var copy = $('.input').select();
			document.execCommand("copy");
			$( '.span' ).css( "display", "inline" ).fadeOut( 2000 );			
		});
		/**Checking the val yes or no to show and hide inputs */
		if ($('#subscription').val() == 'Yes') {
			$('#label_periodicity').css("display","block");
			$("#periodicity").css("display","block");
			$('#label_ocurrency').css("display", "block");
			$('#ocurrency').css("display", "block");
		} else {
			$("#label_periodicity").css("display", "none");
			$("#periodicity").css("display", "none");
			$('#label_ocurrency').css("display", "none");
			$('#ocurrency').css("display", "none");
		}
		/**Showing elements when the val of subscription change yes/no*/
		$('#subscription').on('change', function() {
			if ($('#subscription').val() == 'Yes' ) {
				$('#label_periodicity').show();
				$('#periodicity').show();
				$('#label_ocurrency').show();
				$('#ocurrency').show();
			} else {
				$('#label_periodicity').hide();
				$('#periodicity').hide();
				$('#label_ocurrency').hide();
				$('#ocurrency').hide();
			}
		});
		
	}
});

var site = new Site();
