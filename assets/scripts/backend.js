 /**
 * backend.js
 * Base logic, feel free to replace with your own and/or use the libraries of your choice
 */
Backend = Class.extend({
	init: function(options) {
		var obj = this,
			opts = _.defaults(options, {
				// Add options here
			});

		$.extend(true, $.alert.defaults, {
			markup: '<div class="alert-overlay"><div class="valign-wrapper"><div class="valign"><div class="alert"><div class="alert-message">{message}</div><div class="alert-buttons"></div></div></div></div></div>',
			buttonMarkup: '<button class="button button-primary"></button>',
			buttons: [
				{ text: 'Got it', action: $.alert.close }
			]
		});

		$.extend(true,$.validate.defaults, {
			fieldsSelector: '[data-validate]:not(:disabled)'
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
	showInstallments: function(val) {
		if ($('#conekta').is(':checked')) {
			$('#tab-installment').removeClass('hide');
			$('#tab-three input').prop('disabled', false);
		} else {
			$('#tab-installment').addClass('hide');
			$('#tab-three input').prop('disabled', true);
		}
	},
	checkSlug: function(name){

		var obj = this,
			slugCheck = false;

		$.ajax({
			url: constants.siteUrl + 'backend/forms/slug-check',
			async: false,
			type: 'POST',
			data: { name: name, id: constants.mvc.id },
			success: function(response) {
				slugCheck = response;
				console.log(slugCheck);
			}
		});

		return slugCheck;
	},
	processors: function() {

		console.log($('#currency').val());
		console.log($('#subscription').val());

		if ($('#currency').val() == 'usd' && $('#subscription').val() == 'Yes' ) {

			$('#lbstripe').removeClass('hide');
			$('#conekta, #PayPal').attr('disabled', true);
			$('#lbpaypal').addClass('hide');

		} else if($('#currency').val() == 'usd' && $('#subscription').val() == '' ) {

			$('#lbpaypal, #lbstripe').removeClass('hide');
			$('#lbconekta').addClass('hide');
			$('#conekta').attr('disabled', true);
			$('#PayPal, #Stripe').attr('disabled', false);


		} else if($('#currency').val() == 'mxn' && $('#subscription').val() == '' ) {

			$('#lbconekta, #lbpaypal, #lbstripe').removeClass('hide');
			$('#conekta, #PayPal, #Stripe').attr('disabled', false);


		} else if($('#currency').val() == 'mxn' && $('#subscription').val() == 'Yes' ) {

			$('#lbstripe').removeClass('hide');
			$('#lbpaypal, #lbconekta').addClass('hide');
			$('#conekta, #PayPal').attr('disabled', true);
			$('#Stripe').attr('disabled', false);

		}
	},
	// Validation ranges
	ranges: function(rangesresponse) {

		var ranges = [];
		froms = $('.repeater-item input[name^="from"]').map(function () { return this.value; }).get();
		tos = $('.repeater-item input[name="to[]"').map(function () { return this.value; }).get();
		ranges = $.map(froms, function(from, i) {
			return {from:parseInt(froms[i]), to:parseInt(tos[i])};
		});
		rr = rangesresponse;

		function compare(a,b) {

			var x = a.from;
			var y = b.from;
			if (x < y) {return -1;}
			if (x > y) {return 1;}
			return 0;
		}

		function validateRanges(ranges, rr) {

			for(var i = 0; i < ranges.length; i++) {
				if(i == 0 && ranges[i].from < 2) {
					$.alert('1 is an invalid range of discount');
					rangesresponse = false;
					return rangesresponse;
				}

				if(ranges[i].from >= ranges[i].to) {
					$.alert('Range from is bigger than a Range to');
					rangesresponse = false;
					return rangesresponse;
				}
				if(i+1 < ranges.length) {
					if(ranges[i].to != ranges[i+1].from-1) {
						$.alert('Missing numbers in the discounts ranges');
						rangesresponse = false;
						return rangesresponse;
					}
				}
				else{

					rangesresponse = true;
					return rangesresponse;
				}
			}
		}

		ranges.sort(compare);
		validateRanges(ranges, rr);
		if (rangesresponse == true ) {
			rangesresponse = true;
			return true;
		} else {
			rangesresponse = false;
			return false;
		}
	},
	copy: function(params) {
		new ClipboardJS('.js-copy');
		console.log($('#slugroot').val());
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
			obj.copy();
			$('.span' ).css( "display", "inline" ).fadeOut( 2000 );
		});

		//set default 0 value in ocurrency
		if ($('#ocurrency').val() == '') {
			$('#ocurrency').val('0');
		}

		//call function show periodicty
		$('.js-toggle-periodicity').on('change', function() {
			var el = $(this),
				val = el.val();
			obj.showPeriodicity(val);
		}).trigger('change');

		$('#conekta').change(function() {
			console.log('checked');
			var el = $(this),
				val = el.val();
			obj.showInstallments(val);
		}).trigger('change');

		$('#PayPal, #Stripe').change( function(){
			var el = $(this),
				val = '';
			obj.showInstallments(val);
		});

		$('#name').on('blur', function(e){
			var el = $(this),
				name = el.val(),
				slug = $('#slug').val()
			if (name && !slug) {
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

		$('#slug').on('blur', function(e){
			var el = $(this),
				name = el.val(),
				slug = $('#slug').val()
			if (slug) {
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

		// Validation forms frontend
		$('#payment-form').on('submit', function() {
			var form = $(this);
			var processorChecked = $('.form-group-processors input:checked').length;
			if(!processorChecked) {
				$.alert('You must select at least one payment processor');
				return false;
			}
			var hasDiscount = $('.repeater-item').length;
			if(hasDiscount >= 1) {
				//console.log(hasDiscount);
				var rangesresponse = false;
				//obj.ranges(rangesresponse);
				 respfinal = obj.ranges(rangesresponse);
				console.log('resp funcion' + respfinal);
					if(respfinal == false) {
						console.log('error en los rangos');
						return false;
					}
				}


			return form.validate({
				callbacks: {
					fail: function(field, type, message) {
						/* An item has failed validation, field has the jQuery object, type is the rule and message its description */
						// console.log('form invalid');
						var container = field.closest('.tab').attr('id'),
							tab = $('.tab-list a[href=\'#' + container + '\']');
						console.log(field);
						field.closest('.form-group').addClass('has-error');
						tab.trigger('click');
						field.on('focus', function() {
							field.closest('.form-group').removeClass('has-error');
							field.off('focus');
						});
					},
					success: function() {},
					error: function(fields) {}
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

		//Processors conditionals
		obj.processors();
		$('#subscription').change( function() {
			obj.processors();

		});
		$('#currency').change( function() {
			obj.processors();
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
								//attachment.append('<i class="fa fa-fw fa-check"></i>');
								attachment.addClass('has-success');
							} else {
								// Error
								attachment.append('<i class="fa fa-fw fa-warning"></i>');
								attachment.addClass('has-error');
								attachment.append('<div class="attachment-status">' + response.message || 'An error has ocurred' + '</div>');
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

var site = new Backend();