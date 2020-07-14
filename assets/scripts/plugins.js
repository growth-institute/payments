/**
 * Serialize object
 */
;(function($){$.fn.serializeObject=function(){var arrayData,objectData;arrayData=this.serializeArray();objectData={};$.each(arrayData,function(){var value;if(this.value!=null){value=this.value}else{value=''}if(objectData[this.name]!=null){if(!objectData[this.name].push){objectData[this.name]=[objectData[this.name]]}objectData[this.name].push(value)}else{objectData[this.name]=value}});return objectData}})(jQuery);

/**
 * jQuery.DataToElement
 * @version   0.1
 * @author    biohzrdmx <github.com/biohzrdmx>
 * @requires  jQuery 1.8+
 * @license   MIT
 * @copyright Copyright © 2016 biohzrdmx. All rights reserved.
 */
(function($) {$.fn.dataToElement = function(data) {return $( this.data(data) || '' ); } })(jQuery);

/**
 * jQuery.Loading - 'Loading' messages made easy
 * @version   0.9.0.20131205
 * @author    biohzrdmx <github.com/biohzrdmx>
 * @requires  jQuery 1.8+
 * @license   MIT
 * @copyright Copyright © 2013 biohzrdmx. All rights reserved.
 */
;(function($){$.fn.loading=function(options){if(!this.length){return this}var isApiCall=typeof options==='string';var opts=$.extend(true,{},$.fn.loading.defaults,isApiCall?{}:options);this.each(function(){var fn=isApiCall?options:'loading';if($(this).is('button,a,input')){fn='button'}fn=$.fn.loading.api[fn];if($.isFunction(fn)){fn.call(this,opts)}});return this};$.fn.loading.api={loading:function(options){var el=$(this);var markup=options.markup.replace('{class}',options.className).replace('{text}',options.text);var overlay=$(markup);if(options.themeClass){overlay.addClass(options.themeClass)}if(options.emptyParent){el.empty()}overlay.hide();el.append(overlay);if(options.animate){overlay.fadeIn()}else{overlay.show()}},done:function(options){var el=$(this);var overlay=el.children().filter(function(index){return $(this).hasClass(options.className)});if(overlay.length){overlay.detach()}},button:function(options){var el=$(this);var prev=el.data('loading.button');if(prev){if(el.is('input')){el.val(prev)}else{el.text(prev)}el.prop({disabled:false});el.data('loading.button',null)}else{if(el.is('input')){prev=el.val();el.data('loading.button',prev);el.val(options.text)}else{prev=el.text();el.data('loading.button',prev);el.text(options.text)}el.prop({disabled:true})}}};$.fn.loading.defaults={text:'Loading...',className:'loading',themeClass:null,emptyParent:false,animate:false,markup:'<div class="{class}"></div>'}})(jQuery);

/**
 * jQuery Validator 4
 * @author     biohzrdmx <github.com/biohzrdmx>
 * @version    4.0.20160315
 * @requires   jQuery 1.8+
 * @license    MIT
 */
;!function(a){a.fn.validate=function(e){if(!this.length)return this;var t=a.extend(!0,{},a.validate.defaults,e),l=0;return this.each(function(){var e=a(this),s=e.find(t.fieldsSelector),i=a.validate.check(s,t);i||l++}),0==l},a.validate={defaults:{breakOnFail:!0,fieldsSelector:"[data-validate]:visible:not(:disabled)",callbacks:{fail:a.noop,error:a.noop,success:a.noop},strings:{required:"This is a required field",email:"This must be a valid email address",equal:"The fields don't match",confirm:"The fields don't match",regexp:"The field doesn't match the specified pattern",checked:{"at least":"You must select at least # options","at most":"You must select at most # options",exactly:"You must select exactly # options"},date:{before:"The date must be before #",after:"The date must be after #",exactly:"The date must be exactly #"}}},types:{required:function(e,t){var l=a.trim(e.val()),s=!0,i=e.data("message-required");return e.length&&(e.is(":checkbox ")&&!e.is(":checked")||e.is(":radio")&&0==a("input[name='"+e.attr("name")+"']:checked").length||""==l)&&(s=!1,t.callbacks.fail.call(this,e,"required",i||t.strings.required)),s},email:function(a,e){var t=!0,l=/^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/,s=a.data("message-email");return a.length&&(t=l.test(a.val()),t||e.callbacks.fail.call(this,a,"email",s||e.strings.email)),t},equal:function(e,t){var l=!0,s=e.data("param"),i="string"==typeof s?a(s):s,r=e.data("message-equal");return e.length&&i.length&&(l=e.val()==i.val(),l||t.callbacks.fail.call(this,e,"equal",r||t.strings.equal)),l},confirm:function(e,t){var l=!0,s=e.data("param"),i="string"==typeof s?a(s):s,r=e.data("message-confirm");return i.val()&&e.length&&i.length&&(l=e.val()==i.val(),l||t.callbacks.fail.call(this,e,"confirm",r||t.strings.confirm)),l},regexp:function(a,e){var t=!0,l=a.data("param"),s=new RegExp(l),i=a.data("message-regexp");return a.length&&(t=s.test(a.val()),t||e.callbacks.fail.call(this,a,"regexp",i||e.strings.regexp)),t},checked:function(e,t){var l=!0,s=e.data("param"),i=s.match(/(at least|at most|exactly)\s([0-9]+)/),r=i[1]||"exactly",c=i[2]||1,n=a("input[name='"+e.attr("name")+"']:checked").length,d=e.data("message-checked");if(e.length){switch(r){case"at least":l=n>=c;break;case"at most":l=c>=n;break;case"exactly":l=n==c}l||t.callbacks.fail.call(this,e,"checked",d||t.strings.checked[r].replace("#",c))}return l},date:function(a,e){var t=!0,l=a.data("param"),s=a.data("message-date"),i=l.match(/(before|after)\s([0-9]{4})\/([0-9]{1,2})\/([0-9]{1,2})/),r=i[1]||"before",c=new Date(i[2]||1900,--i[3]||0,i[4]||1),n=null;if(a.length){if(a.is("input")||a.is("textarea"))n=new Date(a.val());else{var d=a.find("[data-date]");switch(d.length){case 1:n=new Date(a.find('[data-date="year"]').val()||1900);break;case 2:n=new Date(a.find('[data-date="year"]').val()||1900,a.find('[data-date="month"]').val()-1||0);break;case 3:n=new Date(a.find('[data-date="year"]').val()||1900,a.find('[data-date="month"]').val()-1||0,a.find('[data-date="day"]').val()||1)}}if(console.log(c,n),n)switch(r){case"before":t=c>n;break;case"after":t=n>c;break;case"exactly":t=n==c}t||e.callbacks.fail.call(this,a,"date",s||e.strings.date[r].replace("#",c.toDateString()))}return t}},check:function(e,t){var l=this,s=a.extend(!0,{},a.validate.defaults,t),i=0,r=[];return a.each(e,function(){for(var e=a(this),t=e.data("validate").split("|"),c=0;c<t.length;c++){var n=t[c],d=!1;if("function"==typeof a.validate.types[n]&&(d=a.validate.types[n].call(l,e,s),!d&&(i++,r.push(e),s.breakOnFail)))break}}),i?s.callbacks.error.call(l,a(r)):s.callbacks.success.call(l,e),0==i}}}(jQuery);

/**
 * jQuery Alert
 * @author     biohzrdmx <github.com/biohzrdmx>
 * @version    1.0.20131213
 * @requires   jQuery 1.8+
 * @license    MIT
 */
;(function($){if(typeof $.easing.easeInOutQuad!=='function'){$.easing.easeInOutQuad=function(x,t,b,c,d){if((t/=d/2)<1)return c/2*t*t+b;return-c/2*((--t)*(t-2)-1)+b}}$.alert=function(message,options){var opts=$.extend(true,{},$.alert.defaults,options);var alert=$(opts.markup.replace('{message}',message));var container=$(opts.container);var buttons=[];if(opts.onlyOne&&container.find('.alert-overlay').length>0){$.alert.close()}if(opts.themeClass){alert.addClass(opts.themeClass)}var buttonContainer=alert.find('.alert-buttons');buttonContainer.empty();$.each(opts.buttons,function(index,val){var button=$(opts.buttonMarkup);var key=opts.buttons[index].key||index;button.addClass('button-'+key);button.text(opts.buttons[index].text||'Close');button.on('click',opts.buttons[index].action||$.noop);buttonContainer.append(button)});var dialog=alert.find('.alert');alert.hide();container.append(alert);alert.fadeIn();opts.fnShow(dialog,opts.onOpen);alert.data('alert-opts',opts)};$.alert.close=function(){var alert=$('.alert-overlay');var dialog=alert.find('.alert');var opts=alert.data('alert-opts');var detachIt=function(){alert.detach();opts.onClose.call()};if(opts){opts.fnHide(dialog,function(){alert.fadeOut(detachIt)})}else{alert.fadeOut(detachIt)}};$.alert.defaults={container:'body',markup:'<div class="alert-overlay"><div class="alert"><div class="alert-message">{message}</div><div class="alert-buttons"></div></div></div>',buttonMarkup:'<button></button>',themeClass:'',onlyOne:true,buttons:[{text:'Close',action:function(){$.alert.close()}}],onOpen:$.noop,onClose:$.noop,fnShow:function(element,callback){element.css({opacity:0,marginTop:'-=40'});element.animate({opacity:1,marginTop:'+=40'},{duration:200,easing:'easeInOutQuad',complete:callback||$.noop})},fnHide:function(element,callback){element.animate({opacity:0,marginTop:'-=40'},{duration:200,easing:'easeInOutQuad',complete:callback||$.noop})}}})(jQuery);

/**
* jQuery DatePicker
* @author biohzrdmx <github.com/biohzrdmx>
* @version 1.0
* @requires jQuery 1.8+
* @license MIT
*/
;!function(e){e.datePicker={strings:{monthsFull:["January","Febraury","March","April","May","June","July","August","September","October","November","December"],monthsShort:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],daysFull:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],daysShort:["Su","Mo","Tu","We","Th","Fr","Sa"],messageLocked:"The day you have just selected is not available"},defaults:{formatDate:function(a){return e.datePicker.utils.pad(a.getDate(),2)+"/"+e.datePicker.utils.pad(a.getMonth()+1,2)+"/"+a.getFullYear()},parseDate:function(e){var a=new Date,t=e.match(/(\d{1,2})\/(\d{1,2})\/(\d{4})/);return t&&4==t.length&&(a=new Date(t[3],t[2]-1,t[1])),a},selectDate:function(e){return!0},limitCenturies:!0,closeOnPick:!0},utils:{firstDay:function(e,a){return new Date(e,a,1).getDay()},daysInMonth:function(e,a){return new Date(e,++a,0).getDate()},buildDecadePicker:function(a,t){e.datePicker;var r=e('<div class="decades"></div>'),n=100*Math.floor(a/100)-10,d=e.datePicker.defaults.limitCenturies,s='<div class="row header"><a href="#" class="prev'+(d&&n<1900?" disabled":"")+'"><span class="arrow"></span></a><a href="#" class="century" data-century="'+(n+10)+'">'+(n+1)+"-"+(n+100)+'</a><a href="#" class="next'+(d&&1990==n?" disabled":"")+'"><span class="arrow"></span></a></div>';r.append(s);for(var l=0,c="",i=0,o=0;o<3;o++){for(var u=e('<div class="row"></div>'),h=0;h<4;h++)if(l=h+4*o,c=0==l?" grayed prev":11==l?" grayed next":"",i=n+10*l,d&&(i<1900||i>2090)){f=e('<a href="" class="cell large double decade blank"> </a>');u.append(f)}else{t>=i&&t<=i+9&&(c+=" selected");var f=e('<a href="#" data-year="'+i+'" class="cell large double decade'+c+'"><span>'+i+"- "+(i+9)+"</span></a>");u.append(f)}r.append(u)}return r},buildYearPicker:function(a,t){e.datePicker;var r=e('<div class="years"></div>'),n=10*Math.floor(a/10)-1,d=e.datePicker.defaults.limitCenturies,s='<div class="row header"><a href="#" class="prev'+(d&&1899==n?" disabled":"")+'"><span class="arrow"></span></a><a href="#" class="decade" data-decade="'+(n+1)+'">'+(n+1)+"-"+(n+10)+'</a><a href="#" class="next'+(d&&2089==n?" disabled":"")+'"><span class="arrow"></span></a></div>';r.append(s);for(var l=0,c="",i=0,o=0;o<3;o++){for(var u=e('<div class="row"></div>'),h=0;h<4;h++)if(l=h+4*o,c=0==l?" grayed prev":11==l?" grayed next":"",i=n+l,d&&(i<1900||i>2099)){f=e('<a href="" class="cell large year blank"> </a>');u.append(f)}else{i==t&&(c+=" selected");var f=e('<a href="#" data-year="'+i+'" class="cell large year'+c+'">'+i+"</a>");u.append(f)}r.append(u)}return r},buildMonthPicker:function(a,t){var r=e.datePicker,n=e('<div class="months"></div>'),d=e.datePicker.defaults.limitCenturies,s='<div class="row header"><a href="#" class="prev'+(d&&1900==a?" disabled":"")+'"><span class="arrow"></span></a><a href="#" class="year" data-year="'+a+'">'+a+'</a><a href="#" class="next'+(d&&2099==a?" disabled":"")+'"><span class="arrow"></span></a></div>';n.append(s);for(var l=0,c="",i=0;i<3;i++){for(var o=e('<div class="row"></div>'),u=0;u<4;u++){c="",(l=u+4*i)==t&&(c+=" selected");var h=e('<a href="#" data-year="'+a+'" data-month="'+l+'" class="cell large month'+c+'">'+r.strings.monthsShort[l]+"</a>");o.append(h)}n.append(o)}return n},buildCalendar:function(a,t,r){var n=e.datePicker,d=e('<div class="calendar"></div>'),s=new Date,a=a||s.getFullYear(),t=t>=0?t:s.getMonth(),l=new Date(a,t,1),c=e.datePicker.defaults.limitCenturies;l.setDate(l.getDate()-1);var i=l.getDate(),o=this.daysInMonth(a,t),u=this.firstDay(a,t),h=1-u;0==u&&(h-=7);var f='<div class="row header"><a href="#" class="prev'+(c&&1900==a&&0==t?" disabled":"")+'"><span class="arrow"></span></a><a href="#" class="month" data-year="'+a+'" data-month="'+t+'">'+n.strings.monthsFull[t]+" "+a+'</a><a href="#" class="next'+(c&&2099==a&&11==t?" disabled":"")+'"><span class="arrow"></span></a></div>';d.append(f);for(var p=e('<div class="row days"></div>'),v=0;v<7;v++)p.append('<div class="cell">'+n.strings.daysShort[v]+"</div>");d.append(p);for(v=0;v<6;v++){for(var m=e('<div class="row week"></div>'),y=0;y<7;y++){var k=h<=0?i+h:h>o?h-o:h,g=h<=0?" grayed prev":h>o?" grayed next":"";c&&(1900==a&&0==t&&h<1||2099==a&&11==t&&h>o)?(m.append('<a href="#" class="cell day blank"> </a>'),h++):(h==s.getDate()&&t==s.getMonth()&&a==s.getFullYear()&&(g+=" today"),h==r.getDate()&&t==r.getMonth()&&a==r.getFullYear()&&(g+=" selected"),m.append('<a href="#" class="cell day'+g+'">'+k+"</a>"),h++)}d.append(m)}return d},pad:function(e,a){for(var t=e+"";t.length<a;)t="0"+t;return t}},show:function(a){var t=e.extend(!0,{},e.datePicker.defaults,a),r=null,n=new Date;t.element&&("string"==typeof t.element&&(t.element=e(t.element)),n=t.parseDate(t.element.val()));var d={day:n.getDate(),month:n.getMonth(),year:n.getFullYear(),decade:n.getFullYear()},s=e.datePicker.utils.buildCalendar(d.year,d.month,n),l=e.datePicker.utils.buildMonthPicker(d.year,d.month),c=e.datePicker.utils.buildYearPicker(d.year,d.year),i=e.datePicker.utils.buildDecadePicker(d.year,d.year);if((r=e('<div class="datepicker"><span class="tip"></span></div>')).append(s),r.append(l),r.append(c),r.append(i),e.datePicker.hide(!0),t.element){var o=t.element.offset();r.css({left:o.left+"px",top:o.top+t.element.outerHeight(!0)+15+"px"})}r.hide(),e("body").append(r),r.fadeIn(150),r.on("click",".calendar .day",function(a){a.preventDefault();var r=e(this),s=r.closest(".calendar");if(!r.hasClass("blank")){s.find(".selected").removeClass("selected"),r.addClass("selected"),d.day=parseInt(r.text())||1,r.hasClass("grayed")&&(r.hasClass("prev")?(d.year-=0==d.month?1:0,d.month=d.month>0?d.month-1:11):r.hasClass("next")&&(d.year+=11==d.month?1:0,d.month=d.month<11?d.month+1:0));var l=new Date;if(l.setFullYear(d.year,d.month,d.day),t.selectDate(l)){n.setFullYear(d.year,d.month,d.day);var c=t.formatDate(n);e(t.element).val(c),t.closeOnPick&&!r.hasClass("grayed")&&e.datePicker.hide()}}}),r.on("click",".calendar .month",function(a){a.preventDefault();var t=e(this).closest(".calendar"),n=r.children(".months"),s=e.datePicker.utils.buildMonthPicker(d.year,d.month);n.replaceWith(s),n=s,t.fadeOut(150,function(){n.fadeIn(150)})}),r.on("click",".calendar .prev",function(a){a.preventDefault();var t=e(this),r=t.closest(".calendar"),s=r.find(".month"),l=s.data("month"),c=s.data("year");t.hasClass("disabled")||((l-=1)<0&&(l=11,c--),d.month=l,d.year=c,replacement=e.datePicker.utils.buildCalendar(c,l,n),replacement.hide(),r.after(replacement),r.fadeOut(150,function(){r.detach(),replacement.fadeIn(150)}))}),r.on("click",".calendar .next",function(a){a.preventDefault();var t=e(this),r=t.closest(".calendar"),s=r.find(".month"),l=s.data("month"),c=s.data("year");t.hasClass("disabled")||((l+=1)>11&&(l=0,c++),d.month=l,d.year=c,replacement=e.datePicker.utils.buildCalendar(c,l,n),replacement.hide(),r.after(replacement),r.fadeOut(150,function(){r.detach(),replacement.fadeIn(150)}))}),r.on("click",".months .month",function(a){a.preventDefault();var t=e(this),s=t.closest(".months"),l=t.data("month"),c=t.data("year"),i=r.children(".calendar"),o=null;t.hasClass("blank")||(s.find(".selected").removeClass("selected"),t.addClass("selected"),d.month=l,(o=e.datePicker.utils.buildCalendar(c,l,n)).hide(),i.replaceWith(o),s.fadeOut(150,function(){o.fadeIn(150)}))}),r.on("click",".months .prev",function(a){a.preventDefault();var t=e(this),r=t.closest(".months"),n=r.find(".year").data("year");t.hasClass("disabled")||(n-=1,d.year=n,replacement=e.datePicker.utils.buildMonthPicker(n,d.month),replacement.hide(),r.after(replacement),r.fadeOut(150,function(){r.detach(),replacement.fadeIn(150)}))}),r.on("click",".months .next",function(a){a.preventDefault();var t=e(this),r=t.closest(".months"),n=r.find(".year").data("year");t.hasClass("disabled")||(n+=1,d.year=n,replacement=e.datePicker.utils.buildMonthPicker(n,d.month),replacement.hide(),r.after(replacement),r.fadeOut(150,function(){r.detach(),replacement.fadeIn(150)}))}),r.on("click",".months .year",function(a){a.preventDefault();var t=e(this).closest(".months"),n=r.children(".years"),s=e.datePicker.utils.buildYearPicker(d.decade,d.year);n.replaceWith(s),n=s,t.fadeOut(150,function(){n.fadeIn(150)})}),r.on("click",".years .year",function(a){a.preventDefault();var t=e(this),n=t.closest(".years"),s=t.data("year"),l=r.children(".months"),c=null;t.hasClass("blank")||t.hasClass("next")||t.hasClass("prev")||(n.find(".selected").removeClass("selected"),t.addClass("selected"),d.year=s,d.decade=s,(c=e.datePicker.utils.buildMonthPicker(s,d.month)).hide(),l.replaceWith(c),n.fadeOut(150,function(){c.fadeIn(150)}))}),r.on("click",".years .prev",function(a){a.preventDefault();var t=e(this),r=t.closest(".years"),n=r.find(".decade").data("decade");t.hasClass("disabled")||(n-=10,d.decade=n,replacement=e.datePicker.utils.buildYearPicker(n,d.year),replacement.hide(),r.after(replacement),r.fadeOut(150,function(){r.detach(),replacement.fadeIn(150)}))}),r.on("click",".years .next",function(a){a.preventDefault();var t=e(this),r=t.closest(".years"),n=r.find(".decade").data("decade");t.hasClass("disabled")||(n+=10,d.decade=n,replacement=e.datePicker.utils.buildYearPicker(n,d.year),replacement.hide(),r.after(replacement),r.fadeOut(150,function(){r.detach(),replacement.fadeIn(150)}))}),r.on("click",".years .decade",function(a){a.preventDefault();var t=e(this).closest(".years"),n=r.children(".decades");t.fadeOut(150,function(){n.fadeIn(150)})}),r.on("click",".decades .decade",function(a){a.preventDefault();var t=e(this),n=t.data("year"),s=t.closest(".decades"),l=r.children(".years"),c=null;t.hasClass("blank")||t.hasClass("next")||t.hasClass("prev")||(s.find(".selected").removeClass("selected"),t.addClass("selected"),(c=e.datePicker.utils.buildYearPicker(n,d.year)).hide(),l.replaceWith(c),s.fadeOut(150,function(){c.fadeIn(150)}))}),r.on("click",".decades .prev",function(a){a.preventDefault();var t=e(this),r=t.closest(".decades"),n=r.find(".century").data("century");t.hasClass("disabled")||(n-=100,replacement=e.datePicker.utils.buildDecadePicker(n,d.decade),replacement.hide(),r.after(replacement),r.fadeOut(150,function(){r.detach(),replacement.fadeIn(150)}))}),r.on("click",".decades .next",function(a){a.preventDefault();var t=e(this),r=t.closest(".decades"),n=r.find(".century").data("century");t.hasClass("disabled")||(n+=100,replacement=e.datePicker.utils.buildDecadePicker(n,d.decade),replacement.hide(),r.after(replacement),r.fadeOut(150,function(){r.detach(),replacement.fadeIn(150)}))}),r.on("click",".decades .century",function(e){e.preventDefault()}),e(document).on("mouseup",function(a){r.is(a.target)||0!==r.has(a.target).length||(e(document).off("mouseup"),e.datePicker.hide())})},hide:function(a){var a=a||!1,t=e(".datepicker");a?t.remove():t.fadeOut(150,t.remove)}},e.fn.datePicker=function(a){if(!this.length)return this;e.extend(!0,{},e.datePicker.defaults,a);return this.each(function(){var a=e(this),t=a.parent().find("[data-toggle=datepicker]"),r=a.data("locked");r=!!r&&r.split(";");var n=function(a){var t=!0,n=e.datePicker.utils.pad(a.getDate(),2)+"/"+e.datePicker.utils.pad(a.getMonth()+1,2)+"/"+a.getFullYear();if(r.length)for(var d=0;d<r.length;d++)if(r[d]==n){"function"==typeof e.alert?e.alert=e.datePicker.strings.messageLocked:alert(e.datePicker.strings.messageLocked),t=!1;break}return t};t.length?t.on("click",function(t){t.preventDefault(),e(".datepicker:visible").length?e.datePicker.hide():e.datePicker.show({element:a,selectDate:n})}):a.on("click",function(){e.datePicker.show({element:a,selectDate:n})})}),this},e("[data-select=datepicker]").each(function(){e(this).datePicker()})}(jQuery);

/**
 * jQuery.Conditional
 * @version   0.1
 * @author    biohzrdmx <github.com/biohzrdmx>
 * @requires  jQuery 1.8+
 * @license   MIT
 * @copyright Copyright © 2015 biohzrdmx. All rights reserved.
 */
;(function($){$.fn.conditional=function(options){if(!this.length){return this}var opts=$.extend(true,{},$.conditional.defaults,options);this.each(function(){var el=$(this),conditional=el.data('conditional');el.on(opts.eventName,function(){var value=el.val(),elements=$('[data-condition="'+conditional+'"]');opts.onDeactivate(elements,opts,function(){elements.each(function(){var element=$(this);if(element.data('match')==value){opts.onActivate(element,opts)}})})});el.trigger(opts.eventName)});return this};$.conditional={defaults:{className:'hide',eventName:'change',onActivate:function(element,opts){element.removeClass(opts.className)},onDeactivate:function(elements,opts,callback){elements.addClass(opts.className);callback.call()}}};$('[data-conditional]').conditional()})(jQuery);

/**
 * jQuery.lazyTube
 * On-demand loading for YouTube videos
 * Avoid hanging your client's browsers by loading YouTube videos ONLY when they want to watch them
 * @version  1.0
 * @author   biohzrdmx <github.com/biohzrdmx>
 * @requires jQuery 1.8+
 * @license  MIT
 */
;!function(a){a.fn.lazyTube=function(b){if(!this.length)return this;var c=a.extend(!0,{},a.lazyTube.defaults,b);return this.each(function(){var b=a(this),d=b.data("id")||null,e=b.data("thumbnail")||"mqdefault",f=b.data("autoplay")||"no",g=b.data("autoload")||!1,h=b.data("width")||"320",i=b.data("height")||"240",j=b.data("target")||"self",k=b.children(".preview");0==k.length&&(k=a('<a href="#" class="preview"></a>'),b.prepend(k)),k.append('<img src="//img.youtube.com/vi/'+d+"/"+e+'.jpg" alt="" />'),k.on("click",function(e){switch(j){case"self":var g=c.embedCode,l=null,m="rel=0&wmode=transparent";m+="yes"==f?"&autoplay=1":"",g=g.replace("{width}",h).replace("{height}",i).replace("{id}",d).replace("{flags}",m),l=a(g),k.hide(),b.append(l);break;default:var n=c.targetHandlers[j];"function"==typeof n&&n.call(b,c,{id:d,width:h,height:i,autoplay:f})}e.preventDefault()}),"yes"==g&&a(window).on("load",function(){k.trigger("click")})}),this},a.lazyTube={defaults:{targetHandlers:{},embedCode:'<div class="embed"><iframe width="{width}" height="{height}" src="//www.youtube-nocookie.com/embed/{id}?{flags}" frameborder="0" allowfullscreen></iframe></div>'}}}(jQuery);

/**
 * jQuery Toooggle
 * @version   0.1
 * @author    biohzrdmx <github.com/biohzrdmx>
 * @requires  jQuery 1.8+
 * @license   MIT
 * @copyright Copyright © 2015 biohzrdmx. All rights reserved.
 */
;(function($) {$.fn.toooggle = function(options) {if (!this.length) { return this; } var opts = $.extend(true, {}, $.fn.toooggle.defaults, options); this.each(function() {var el = $(this); el.on('click', function(e) {e.preventDefault(); opts.callback(el, opts); }); }); return this; }; $.fn.toooggle.defaults = {hideTrigger: false, callback: function(element, opts) {var target = element.data('toooggle'); $(target).toggleClass('hide'); if (opts.hideTrigger) {element.toggleClass('hide'); } } }; jQuery(document).ready(function($) {$('[data-toooggle]').each(function() {var el = $(this), hideTrigger = el.data('hide-trigger') || false; el.toooggle({hideTrigger: hideTrigger }); }); }); })(jQuery);

/**
 * jQuery Shareify
 * @version   0.1
 * @author    biohzrdmx <github.com/biohzrdmx>
 * @requires  jQuery 1.8+
 * @license   MIT
 * @copyright Copyright © 2015 biohzrdmx. All rights reserved.
 */
;(function($) {$.fn.shareify = function(options) {if (!this.length) { return this; } var opts = $.extend(true, {}, $.fn.shareify.defaults, options); this.each(function() {var el = $(this), link = el.data('link') || '', image = el.data('image') || '', title = el.data('title') || ''; if (link) {var list = $(opts.templates.list), ul = list.find('ul'); $.each(opts.providers, function(key, provider) {var li = $(opts.templates.item), url = provider.url.replace('%url%', link).replace('%image%', image).replace('%title%', title), button = $(provider.button.replace('%url%', url)); li.append(button); li.addClass('link-' + key); ul.append(li); }); el.append(list); } }); return this; }; $.fn.shareify.defaults = {providers: {facebook: {url: 'https://www.facebook.com/sharer/sharer.php?u=%url%', button: '<a href="%url%" target="_blank"><i class="fa fa-fw fa-facebook"></i></a>'}, twitter: {url: 'https://twitter.com/home?status=%title% %url%', button: '<a href="%url%" target="_blank"><i class="fa fa-fw fa-twitter"></i></a>'}, google: {url: 'https://plus.google.com/share?url=%url%', button: '<a href="%url%" target="_blank"><i class="fa fa-fw fa-google"></i></a>'}, pinterest: {url: 'https://pinterest.com/pin/create/button/?url=%url%&media=%image%&description=%title%', button: '<a href="%url%" target="_blank"><i class="fa fa-fw fa-pinterest"></i></a>'} }, templates: {list: '<nav class="shareify"><ul></ul></nav>', item: '<li class="shareify-link"></li>'} }; jQuery(document).ready(function($) {$('[data-share=shareify]').shareify(); }); })(jQuery);

/**
 * jQuery.Fixation
 * @version   0.1
 * @author    biohzrdmx <github.com/biohzrdmx>
 * @requires  jQuery 1.8+
 * @license   MIT
 * @copyright Copyright © 2017 biohzrdmx. All rights reserved.
 */
;(function($) {$.fn.fixation = function(options) {if (!this.length) { return this; } var opts = $.extend(true, {}, $.fn.fixation.defaults, options), body = $('body'), html = $('html'), getScrollTop = function() {var obj = this, ret = 0; ret = body.scrollTop(); if (! ret ) {ret = html.scrollTop(); } return ret; }; this.each(function() {var el = $(this); $(window).on('scroll', function() {var scrollTop = getScrollTop(), isFixed = el.hasClass(opts.className), hasChanged = false, direction = ''; if ( scrollTop >= opts.offset && !isFixed ) {el.addClass(opts.className); hasChanged = true; direction = 'down'; } else if (scrollTop < opts.offset && isFixed) {el.removeClass(opts.className); hasChanged = true; direction = 'up'; } if (hasChanged) {opts.callback.call(el, direction); } }); }); return this; }; $.fn.fixation.defaults = {className: 'fixed', callback: $.noop, offset: 100 }; })(jQuery);