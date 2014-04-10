$(document).ready(function(){
	$('.HC-blackout').click(function(e){
		if($(e.target).hasClass('HC-blackout')) HCUI.BlkOff(this);
	});

	$(document).keyup(function(e){
		e.preventDefault();
		if(e.keyCode == 27) HCUI.BlkOff();
	});
});


function HCUI() {
}

HCUI.BlkOn = function(el){
	$('html,body').addClass('blackout-on');
	if(el == undefined) el = '.HC-blackout';
	$(el).fadeIn(200);
}

HCUI.BlkOff = function(el){
	if(el == undefined) el = '.HC-blackout';
	$(el).fadeOut(200,function(){
		$('html,body').removeClass('blackout-on');
	});
}