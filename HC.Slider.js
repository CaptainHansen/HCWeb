/***
HCSlider - a jQuery plugin:

$(el).HCSlider(function(){
	//do something while sliding the slider
});

***/

(function($){
	$.fn.HCSlider = function(ch_fn){
		return this.each(function(){
			var v = $(this).val();
			var id = $(this).attr('id');
			var cls = $(this).attr('class');
			$(this).before('<div class="HCSlider '+cls+'"><div class="track"><div class="slider" style="margin-left: '+v+'%;"><input type="hidden" id="'+id+'" value="'+v+'"></div></div></div>');
			var obj = $(this).prev();
			$(this).remove();
		
			$(obj).find('.slider').mousedown(function(e) {
				e = e || window.event;
		
				//EVALUATING APPARENT ORIGIN:
				var trackoffset = $(obj).find('.track').offset().left;
				var ptr = 0;
				if(e.pageX) ptr = e.pageX;
				else if(e.clientX) ptr = e.clientX;
				var slideroffset = $(obj).find('.slider').offset().left;
				var ms = $(obj).find('.slider').css('left').match(/(-?\d+)px/);
				var origin = (ptr-slideroffset + trackoffset) + parseInt(ms[1]);
		
				//EVALUATING MAX LEFT MARGIN
				var max = $(obj).find('.track').width();

				$('body,html').mousemove(function(e) {
					e = e || window.event;
					var ptr = 0;
					if(e.pageX) ptr = e.pageX;
					else if(e.clientX) ptr = e.clientX;

					var slider_pos = ptr-origin;
					if(slider_pos < 0) slider_pos = 0;
					if(slider_pos > max) slider_pos = max;

					var new_val = (slider_pos / max) * 100;
					if($(obj).find('.slider').find('input').val() != new_val){
						$(obj).find('.slider').css({'margin-left': new_val+"%"}).find('input').val(new_val);
						$(obj).find('input').change();
					}
				}).mouseup(function(e) {
					$('body,html').off('mousemove').css({
						'-webkit-touch-callout': 'auto',
						'-webkit-user-select': 'auto',
						'-khtml-user-select': 'auto',
						'-moz-user-select': 'auto',
						'-ms-user-select': 'auto',
						'user-select': 'auto'
					});
				}).css({
					'-webkit-touch-callout': 'none',
					'-webkit-user-select': 'none',
					'-khtml-user-select': 'none',
					'-moz-user-select': 'none',
					'-ms-user-select': 'none',
					'user-select': 'none'
				});
			});
			$(obj).find('input').change(ch_fn);
		});
	};
}(jQuery));

$(document).ready(function(){
	$('input[type=HCSlider]').HCSlider();
});