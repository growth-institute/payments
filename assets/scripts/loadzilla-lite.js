LoadzillaLite = Class.extend({
	defaults: {
		callbacks: {
			start: function() {
				console.log('start', arguments);
			},
			progress: function() {
				console.log('progress', arguments);
			},
			complete: function() {
				console.log('complete', arguments);
			}
		},
		functions: {
			processFile: function(file) {
				// Create the progress indicator
				var obj = this,
					ext = file.name.match(/(.+?)(\.[^.]*$|$)/i),
					item = {};
				ext = ext.pop() || '';
				ext = ext.replace('.', '');
				item.uid = 'file_' + new Date().valueOf();
				item.name = file.name;
				item.size = file.size;
				item.type = file.type;
				item.ext = ext.toUpperCase();
				item.image = !!file.type.match('image.*');
				if (item.image) {
					// Gotcha, generate thumbnail
					// obj.generateThumbnail(file, item);
				}
				// And start upload
				obj.opts.callbacks.start.call(obj, item);
				obj.uploadFile(file, item);
			}
		}
	},
	init: function(options) {
		var obj = this;
		obj.opts = obj.merge(obj.defaults, options);
	},
	onDomReady: function() {
		var obj = this;
		// var counter = 0;
		obj.opts.element.on('dragenter', function(e) {
			// Only if drag & drop is supported
			if ( Modernizr.draganddrop ) {
				// counter++;
			}
		});
		obj.opts.element.on('dragover', function(e) {
			var el = $(this);
			e.stopPropagation();
			e.preventDefault();
			// Only if drag & drop is supported
			if ( Modernizr.draganddrop ) {
				el.addClass('over');
				e.originalEvent.dataTransfer.dropEffect = 'copy';
			}
		});
		obj.opts.element.on('dragleave', function(e) {
			var el = $(this);
			e.stopPropagation();
			e.preventDefault();
			// Only if drag & drop is supported
			if ( Modernizr.draganddrop ) {
				// counter--;
				// if (counter === 0) {
					el.removeClass('over');
				// }
			}
		});
		obj.opts.element.on('drop', function(e) {
			var el = $(this);
			e.stopPropagation();
			e.preventDefault();
			// Only if drag & drop is supported
			if ( Modernizr.draganddrop ) {
				el.removeClass('over');
				var files = e.originalEvent.dataTransfer.files;
				for (var i = 0, file; file = files[i]; i++) {
					obj.opts.functions.processFile.call(obj, file);
				}
			}
		});
		//
		obj.opts.element.on('click', function(e) {
			e.preventDefault();
			var el = $(this),
				input = el.dataToElement('input');
			input.trigger('click');
		});
		obj.opts.element.dataToElement('input').on('change', function(e) {
			var el = $(this),
				input = el[0];
			// Only if File APIs are supported
			if ( !!window.FileReader ) {
				var file = input.files[0];
				obj.opts.functions.processFile.call(obj, file);
			} else {
				// Maybe submit form for direct upload?
			}
		});
	},
	uploadFile: function( file, item) {
		var obj = this,
			formData = new FormData();
		// Create the formData structure
		formData.append('file', file, file.name);
		console.log(formData);
		$.ajax({
			url: obj.opts.target,
			data: formData,
			xhr: function() {
				var xhr = jQuery.ajaxSettings.xhr();
				// Check if we're dealing with the uploader
				if (xhr.upload) {
					// Bind progress event
					Loadzilla.Utils.addEventListener(xhr.upload, 'progress', function(e) {
						if (e.lengthComputable) {
							var num = (e.loaded / e.total) * 100;
							obj.opts.callbacks.progress.call(obj, item, num.toFixed());
						}
					});
					return xhr;
				}
			},
			processData: false,
			contentType: false,
			type: 'POST',
			success: function(data){
				obj.opts.callbacks.complete.call(obj, item, data);
			}
		});
	}
});

(function() {
	if ( typeof Loadzilla === 'undefined' ) {
		Loadzilla = {};
		Loadzilla.Utils = {
			addEventListener: function(obj, evt, fnc) {
				// Provide an standard mechanism to bind events across different browsers
				if (obj.addEventListener) {
					obj.addEventListener(evt, fnc, false);
					return true;
				} else if (obj.attachEvent) {
					return obj.attachEvent('on' + evt, fnc);
				} else {
					evt = 'on'+evt;
					if (typeof obj[evt] === 'function') {
						fnc = (function(f1,f2){
							return function() {
								f1.apply(this,arguments);
								f2.apply(this,arguments);
							}
						})(obj[evt], fnc);
					}
					obj[evt] = fnc;
					return true;
				}
				return false;
			}
		}
	}
})();