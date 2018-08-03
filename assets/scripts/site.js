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

		// Button and spanish text + valign wrapper
		$.extend(true, $.alert.defaults, {
			markup: '<div class="alert-overlay"><div class="valign-wrapper"><div class="valign"><div class="alert"><div class="alert-message">{message}</div><div class="alert-buttons"></div></div></div></div></div>',
			buttonMarkup: '<button class="button button-primary"></button>',
			buttons: [
				{ text: 'Got it', action: $.alert.close }
			]
		});

		jQuery(document).ready(function($) {
			obj.onDomReady($);
		});
	},
	showPeriodicity: function(val) {
		if (val) {
			$('#periodicity-group').removeClass('hide');
		} else {
			$('#periodicity-group').addClass('hide');
		}
	},
	installments: function(val) {
		if ($('#conekta').is(':checked')) {
			$('#tab-installment').removeClass('hide');
		} else {
			$('#tab-installment').addClass('hide');
		}
	},
	checkSlug: function(name){

		var obj = this,
			slugCheck = false;

		$.ajax({
			url: constants.siteUrl + 'backend/forms/slug-check',
			async: false,
			type: 'POST',
			data: { name: name },
			success: function(response) {
				slugCheck = response;
			}
		});

		return slugCheck;
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
		$('.span').css("display","none");
		$('.js-copy').on('click', function(e) {
			e.preventDefault();
			var copy = $('.input').select();
			document.execCommand("copy");
			$( '.span' ).css( "display", "inline" ).fadeOut( 2000 );
		});
		//set default 0 value in ocurrency
		$('#ocurrency').val('0');
		//call function show periodicty
		$('.js-toggle-periodicity').on('change', function() {
			var el = $(this),
				val = el.val();
			obj.showPeriodicity(val);
		}).trigger('change');
		$('#conekta').change( function(){
			console.log('checked');
			var el = $(this),
				val = el.val();
			obj.installments(val);

		});
		$('#PayPal, #Stripe' ).change( function(){
			console.log('checked2');
			var el = $(this),
				val = '';
			obj.installments(val);
		});

		$('#name').on('blur', function(e){
			var el = $(this),
				name = el.val();
			console.log(name);
			if (name) {
				var slug = obj.checkSlug(name);
				var clean = true;

				while(slug.result == 'error') {
					name = name + '-1';
					clean = false;
					slug = obj.checkSlug(name);
				}

				if(!clean) {

					$.alert('The slug of the form already exists. Creating a new one.');
				}

				$('#slug').val(slug.data.slug);
			}
		});

		//validation forms fronend
		$('#submit').click( function(event) {
			//event.preventDefault();
			return $('form').validate({
				callbacks: {
					fail: function(field, type, message) {
						/* An item has failed validation, field has the jQuery object, type is the rule and message its description */
						console.log(field);
						console.log('form invalid');
					},
					success: function() {
					/* Everything is OK, continue */
						$('form').submit();
						},
					error: function(fields) {
						/* Missing info! 'fields' is a jQuery object with the offending fields */
					}
				}
			});
		});
		$('.repeater').on('click', '.js-repeater-insert', function(e) {
			var el = $(this),
				item = el.closest('.repeater-item'),
				items = el.closest('.repeater-items'),
				repeater = item.closest('.repeater'),
				template = repeater.data('template'),
				number = items.find('.repeater-item').length + 1;
			e.preventDefault();
			if (! template ) {
				template = _.template( $( repeater.data('partial') ).html() || '<div>Template not found</div>' );
				repeater.data('template', template);
			}
			var newRow = $( template({ number: number }) );
			newRow.hide();
			item.before(newRow);
			items.find('.repeater-item').each(function(index) {
				var row = $(this);
				row.find('.grip-number > span').text(index + 1);
			});
			newRow.fadeIn();
			//obj.codeMirrorInit( newRow.find('.codemirror') );
		});

		$('.repeater').on('click', '.js-repeater-delete', function(e) {
			var el = $(this),
				item = el.closest('.repeater-item'),
				items = el.closest('.repeater-items'),
				repeater = item.closest('.repeater');
			e.preventDefault();
			item.fadeOut(function() {
				$(this).remove();
				items.find('.repeater-item').each(function(index) {
					var row = $(this);
					row.find('.grip-number > span').text(index + 1);
				});
			});
		});

		$('.repeater').on('click', '.js-repeater-add', function(e) {
			var el = $(this),
				repeater = el.closest('.repeater'),
				items = repeater.find('.repeater-items'),
				template = repeater.data('template'),
				number = items.find('.repeater-item').length + 1;
			e.preventDefault();
			if (! template ) {
				template = _.template( $( repeater.data('partial') ).html() || '<div>Template not found</div>' );
				repeater.data('template', template);
			}
			var newRow = $( template({ number: number }) );
			newRow.hide();
			items.append(newRow);
			items.find('.repeater-item').each(function(index) {
				var row = $(this);
				row.find('.grip-number > span').text(index + 1);
			});
			newRow.fadeIn();
		});
		//processors conditionals
		var res = '';
		function processors(){

			if ($('#currency').val() == 'usd' && $('#subscription').val() == 'Yes' ) {
				console.log('usd con suscr');
				res= 'true';
				//$('#conekta, #PayPal').attr('disabled', true);
				$('#StripeUSD, #lbstripeUSD').removeClass('hide');
				$('#PayPalUSD, #lbpaypalUSD,#conekta, #lbconekta,#PayPalMNX, #lbpaypalMNX, #StripeMNX, #lbstripeMNX').addClass('hide');

				//alert("Not a valid character");
			}
			else if($('#currency').val() == 'usd' && $('#subscription').val() == '' ) {
				//$('#conekta').attr('disabled', true);
				//$('#PayPal').attr('disabled', false);
				$('#PayPalUSD, #lbpaypalUSD,#StripeUSD, #lbstripeUSD').removeClass('hide');
				$('#conekta, #lbconekta,#PayPalMNX, #lbpaypalMNX, #StripeMNX, #lbstripeMNX').addClass('hide');
				console.log('usd sin suscr');
				res= 'true2';
				//alert("Not a valid character");
			}
			else if($('#currency').val() == 'mxn' && $('#subscription').val() == '' ) {
				//$('#conekta, #PayPal').attr('disabled', false);
				$('#conekta,#lbconekta, #PayPalMNX, #lbpaypalMNX, #StripeMNX, #lbstripeMNX').removeClass('hide');
				$('#PayPalUSD, #lbpaypalUSD,#StripeUSD, #lbstripeUSD').addClass('hide');
				console.log('mxn sin suscr');
				res= 'truemxn';
				//alert("Not a valid character");
			}
			else if($('#currency').val() == 'mxn' && $('#subscription').val() == 'Yes' ) {
				//$('#conekta, #PayPal').attr('disabled', true);
				$('#conekta, #lbconekta').removeClass('hide');
				$('#PayPalUSD, #lbpaypalUSD,#StripeUSD, #lbstripeUSD,#PayPalMNX, #lbpaypalMNX, #StripeMNX, #lbstripeMNX').addClass('hide');

				console.log('mxn con suscr');
				res= 'truemxn2';
				//alert("Not a valid character");
			}
			else {
				res= 'invalid';
			}
		}
		processors();
		$('#subscription').change( function() {
			processors();
			//processors(res);
			console.log(res);
		});
		$('#currency').change( function() {
			processors();
			console.log(res);
		});

		//Loadzilla
		$('.uploader-area').each(function() {
			var el = $(this),
				target = el.data('target'),
				attachments = el.next('.attachments'),
				attachment = _.template( $('#partial-attachment').html() ),
				loadzilla = new LoadzillaLite();

			// Existing buttons
			attachments.find('.js-remove').on('click', function(e) {
				e.preventDefault();
				var el = $(this);
				el.closest('.attachment').fadeOut(function() {
					$(this).remove();
				});
			});
			// Initialize Loadzilla instance
			loadzilla.init({
				element: el,
				target: target,
				callbacks: {
					start: function(item) {
						attachments.html( attachment({ item: item }) );
						console.log(item);
					},
					progress: function(item, percent) {
						var attachment = $('#' + item.uid);
						attachment.find('.attachment-percent').text(percent + '%');
						attachment.find('.attachment-progress .progress-bar').css('width', percent + '%');
						console.log(attachment);
					},
					complete: function(item, response) {

						var attachment = $('#' + item.uid),
							buttonRemove = $('<a class="attachment-remove js-remove" href="#">Remove</a>'),
							status = '';
							console.log(response);
							attachment.find('.attachment-percent').fadeOut();
							attachment.find('.attachment-progress .progress-bar').fadeOut(function() {

							if (response && response.result == 'success') {

								$('[name=product_image]').val(response.data.attachment.id);
								$('.uploader-area').html('<img class="img-responsive" src="' + response.data.attachment.url + '">').addClass('has-loaded');

								attachment.find('.attachment-percent').fadeOut(function() { $(this).remove(); });
								attachment.append('<i class="fa fa-fw fa-check"></i>');
								attachment.addClass('has-success');
							} else {
								// Error
								attachment.append('<i class="fa fa-fw fa-warning"></i>');
								attachment.addClass('has-error');
								attachment.append('<div class="attachment-status">' + response.message || 'Ha ocurrido un error' + '</div>');
							}

							$('.js-clear').removeClass('hide');
						});
					}
			 }
			});
			// Bind events
			loadzilla.onDomReady();
		});
	}
});

var site = new Site();
