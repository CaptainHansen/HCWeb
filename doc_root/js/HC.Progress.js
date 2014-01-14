/***
HCProgress - a jQuery plugin:

$(el).HCProgress();

***/

(function($){
	$.fn.HCProgress = function(){
		return this.each(function(){
			var v = $(this).val();
			if(v === undefined) v=0;
			var id = $(this).attr('id');
			var cls = $(this).attr('class');
			var html = $(this).html();
			$(this).before('<div class="HCProgress-wrap '+cls+'"><div class="HCProgress-text">'+html+'</div><div class="HCProgress-progress"><input class="HCProgress" type="hidden" id="'+id+'" value="'+v+'"></div></div>');
			var obj = $(this).prev();
			obj.find('.HCProgress-progress').css({'width': v+'%'});
			$(this).remove();
		});
	};
})(jQuery);

// jQuery default val function needs to be overridden in order to properly set the slider position when its value is changed.
(function ($) {
	var originalVal = $.fn.val;
	$.fn.val = function(value) {
		if(value == undefined){
			return originalVal.call(this);
		}
		var ret = originalVal.call(this,value);
		if (typeof value != undefined) {
			if($(this).hasClass('HCProgress')){
				var obj = $(this).parent().parent();
				obj.find('.HCProgress-progress').css({'width': $(this).val()+'%'});
			}
		}
		return ret
	};
})(jQuery);

(function ($) {
	var originalVal = $.fn.html;
	$.fn.html = function(value) {
		if(value == undefined){
			return originalVal.call(this);
		}
		if (typeof value != undefined && $(this).hasClass('HCProgress')) {
			var obj = $(this).parent().parent();
			obj.find('.HCProgress-text').html(value);
			return this;
		} else {
			return originalVal.call(this,value);
		}
	};
})(jQuery);