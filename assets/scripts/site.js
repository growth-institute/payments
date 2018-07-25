git /**
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
	showPeriodicity: function(val) {
		if (val) {
			$('#periodicity-group').removeClass('hide');
		} else {
			$('#periodicity-group').addClass('hide');
		}
	},
	installments: function(val) {
		if (val){
			$('#tab-installment').removeClass('hide');
		} else {
			$('#tab-installment').addClass('hide');
		}
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
		$('#conekta:checked').on('change', function(){
			var el = $(this),
				val = el.val();
			obj.installments(val);
		}).trigger('change');
		//validation forms fronend
		$('#form-test').on('submit', function() {
			return $(this).validate({
				callbacks: {
					fail: function(field, type, message) {
						/* An item has failed validation, field has the jQuery object, type is the rule and message its description */
					},
					success: function() {
						/* Everything is OK, continue */
					},
					error: function(fields) {
						/* Missing info! 'fields' is a jQuery object with the offending fields */
					}
				}
			});
		});
<<<<<<< HEAD
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
		//processors conditionals
		var res = '';
		function processors(){
			
			if ($('#currency').val() == 'usd' && $('#subscription').val() == 'Yes' ) {
				console.log('usd con suscr');
				res= 'true';
				$('#conekta', '#PayPal').attr('disabled', true);
				//alert("Not a valid character");
			}
			else if($('#currency').val() == 'usd' && $('#subscription').val() == '' ) {
				$('#conekta').attr('disabled', true);
				console.log('usd sin suscr');
				res= 'true2';
				//alert("Not a valid character");
			}
			else if($('#currency').val() == 'mxn' && $('#subscription').val() == '' ) {
				console.log('mxn sin suscr');
				res= 'truemxn';
				//alert("Not a valid character");
			}
			else if($('#currency').val() == 'mxn' && $('#subscription').val() == 'Yes' ) {
				$('#conekta', '#PayPal').attr('disabled', true);
				console.log('mxn con suscr');
				res= 'truemxn2';
				//alert("Not a valid character");
			}
			else if($('#currency').val() == 'mxn' && $('#subscription').val() == '' ) {
				$('#conekta', '#PayPal').attr('disabled', true);
				console.log('mxn sin suscr');
				res= 'truemxn';
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
					},
					progress: function(item, percent) {
						var attachment = $('#' + item.uid);
						attachment.find('.attachment-percent').text(percent + '%');
						attachment.find('.attachment-progress .progress-bar').css('width', percent + '%');
					},
					complete: function(item, response) {
	
						var attachment = $('#' + item.uid),
							buttonRemove = $('<a class="attachment-remove js-remove" href="#">Remove</a>'),
							status = '';
						attachment.find('.attachment-percent').fadeOut();
						attachment.find('.attachment-progress .progress-bar').fadeOut(function() {
							if (response && response.result == 'success') {
								attachment.find('.attachment-percent').fadeOut(function() {
									$(this).remove();
								});
	
								status = response.data.ConsultaResult.CodigoEstatus + ' / ' + response.data.ConsultaResult.Estado;
								attachment.append('<i class="fa fa-fw fa-check"></i>');
								attachment.addClass('has-success');
								attachment.append('<div class="attachment-status">'+status+'</div>');
							} else {
	
								// Error
								attachment.append('<i class="fa fa-fw fa-warning"></i>');
								attachment.addClass('has-error');
								attachment.append('<div class="attachment-status">'+response.message||'Ha ocurrido un error'+'</div>');
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
