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
		var obj = this,
			body = $('body');

		$.extend(true, $.alert.defaults, {
			buttonMarkup: '<button class="button button-primary"></button>',
			buttons: [
				{ text: 'Close', action: $.alert.close }
			]
		});

		$('[data-value]').each(function() {
			var el = $(this),
				value = el.data('value');
			el.val(value);
		});

		// var select = $('[name=correct]'),
		// 	textarea = $('[name=options]');
		// if (select.length) {
		// 	textarea.on('change', function() {
		// 		obj.updateOptionSelect(select, textarea);
		// 	});
		// 	obj.updateOptionSelect(select, textarea);
		// }

		$('form[data-submit=validate]').on('submit', function() {
			var form = $(this);
			return form.validate({
				callbacks: {
					fail: function(field, type, message) {
						field.closest('.form-group').addClass('has-error');
						field.on('focus', function() {
							field.closest('.form-group').removeClass('has-error');
							field.off('focus');
						});
					},
					error: function(fields) {
						$.alert('Please fill all the required fields');
					}
				}
			});
		});

		// $('.async-paginated').on('click', '.pagination a', function(e) {
		// 	var el = $(this),
		// 		href = el.attr('href'),
		// 		target = el.closest('.async-paginated');
		// 	target.loading();
		// 	$.ajax({
		// 		url: href,
		// 		type: 'get',
		// 		success: function(responseBody) {
		// 			var replacement = $(responseBody).find( '#' + target.attr('id') );
		// 			if (replacement.length) {
		// 				target.html( replacement.html() );
		// 			}
		// 		}
		// 	});
		// 	e.preventDefault();
		// });

		// $('.js-file-input').on('click', function(e) {
		// 	e.preventDefault();
		// 	var el = $(this),
		// 		group = el.closest('.form-group'),
		// 		input = group.find('input[type=file]');
		// 	input.trigger('click');
		// });

		// $('input[type=file]').on('change', function(e) {
		// 	e.preventDefault();
		// 	var el = $(this),
		// 		group = el.closest('.form-group'),
		// 		label = group.find('.js-file-name');
		// 	if (label.length == 0) {
		// 		label = $('<span class="help-block js-file-name"></span>');
		// 		group.find('.js-file-input').after(label);
		// 	}
		// 	label.text( el.get(0).files[0].name );
		// });

		$('.tab-list li a').on('click', function(e) {
			var el = $(this),
				li = el.closest('li'),
				href = el.attr('href'),
				target = $(href);
			e.preventDefault();
			li.addClass('selected').siblings('li').removeClass('selected');
			target.addClass('active').siblings('.tab').removeClass('active');
			$('.codemirror').each(function() {
				var el = $(this),
					textarea = el.find('textarea'),
					editor = textarea.data('cm');
				editor.refresh();
			});
		});

		var hash = window.location.hash.match(/^#\/([a-z_-]+)$/i);
		if ( hash && hash.length > 1 ) {
			$('[href="#tab-'+ hash[1] +'"]').trigger('click');
		}

		if ( body.hasClass('has-graphs') ) {

			var createStatsChart = function() {
				var ctx = $('#chart-stats');
				var src = $.parseJSON( $('[name=stats]').val() );
				var labels = [];
				var data = [];
				_.each(src, function(value, key) {
					labels.push(key);
					data.push(value);
				});
				var statsChart = new Chart(ctx, {
					type: 'line',
					data: {
						labels: labels,
						datasets: [{
							label: 'Total entries',
							data: data,
							fill: false,
							lineTension: 0,
							borderColor: '#12AFCB',
							pointBackgroundColor: '#12AFCB',
							borderWidth: 1
						}]
					},
					options: {
						legend: {
							display: false
						},
						scales: {
							xAxes: [{
								gridLines: {
									display: false
								},
								type: 'time',
								time: {
									displayFormats: {
										day: 'MMM D'
									},
									unit: 'day'
								}
							}]
						}
					}
				});
			};

		// 	var createDetailedChart = function() {
		// 		var ctx = $('#chart-detailed');
		// 		var src = $.parseJSON( $('[name=detailed]').val() );
		// 		var labels = [];
		// 		var data = {};
		// 		var dataSets = [];
		// 		_.each(src, function(value, key) {
		// 			labels.push(key);
		// 			_.each(value, function(value, key) {
		// 				if ( typeof data[key] === 'undefined' ) {
		// 					data[key] = [];
		// 				}
		// 				data[key].push( Number(value) );
		// 				var color = obj.stringToColour(key);
		// 				// console.log(key, color);
		// 				dataSets.push({
		// 					label: key,
		// 					data: data[key],
		// 					fill: false,
		// 					lineTension: 0,
		// 					borderColor: color,
		// 					pointBackgroundColor: color,
		// 					borderWidth: 1
		// 				});
		// 			});
		// 		});
		// 		var statsChart = new Chart(ctx, {
		// 			type: 'line',
		// 			data: {
		// 				labels: labels,
		// 				datasets: dataSets
		// 			},
		// 			options: {
		// 				legend: {
		// 					display: false
		// 				},
		// 				scales: {
		// 					xAxes: [{
		// 						gridLines: {
		// 							display: false
		// 						},
		// 						type: 'time',
		// 						time: {
		// 							displayFormats: {
		// 								day: 'MMM D'
		// 							},
		// 							unit: 'day'
		// 						}
		// 					}]
		// 				}
		// 			}
		// 		});
		// 	};

			createStatsChart();
		// 	createDetailedChart();

		}

		$('[data-check]').each(function() {
			var el = $(this),
				check = el.data('check');
			$.ajax({
				url: constants.siteUrl + 'backend/clients/check/' + check,
				type: 'get',
				success: function(response) {
					if (response && response.result == 'success') {
						var resultText = response.data.status.code == '200' ? '<i class="led led-green"></i> Available' : '<i class="led led-red"></i> Unavailable',
							resultClass = response.data.status.code == '200' ? 'text-success' : 'text-error';
						var availability = '<span>' + resultText + '</span>' + ' - HTTP ' + response.data.status.code + ' (' + response.data.status.time + ' seconds)';
						el.html(availability);
					} else {
						el.text('Unable to get availability');
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
			obj.codeMirrorInit( newRow.find('.codemirror') );
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
			obj.codeMirrorInit( newRow.find('.codemirror') );
		});

		obj.codeMirrorInit();

	},
	codeMirrorInit: function(scope) {
		var scope = scope || $('.codemirror');
		scope.each(function() {
			var el = $(this),
				textarea = el.find('textarea'),
				form = textarea.closest('form'),
				editor = null;
			//
			editor = CodeMirror.fromTextArea(textarea.get(0), {
				lineNumbers: true,
				mode: 'text/javascript',
				matchBrackets: true,
				lineWrapping: true,
				theme: 'seti'
			});
			//
			textarea.data('cm', editor);
			//
			form.on('submit', function() {
				textarea.val( editor.getValue() );
			});
		});
	}
	/*updateOptionSelect: function(select, textarea) {
		var options = $(textarea).val(),
			values = options.split(/\r\n|\r|\n/),
			prevVal = select.val() || select.data('value');
		select.empty();
		_.each(values, function(value, index) {
			select.append('<option value="'+ index +'">'+ value +'</option>');
		});
		select.val(prevVal);
	},
	stringToColour: function(str) {
		var hash = 0;
		for (var i = 0; i < str.length; i++) {
			hash = str.charCodeAt(i) + ((hash << 5) - hash);
		}
		var colour = '#';
		for (var i = 0; i < 3; i++) {
			var value = (hash >> (i * 8)) & 0xFF;
			colour += ('00' + value.toString(16)).substr(-2);
		}
		return colour;
	}*/
});

var site = new Site();