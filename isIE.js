//returns true if the internet browser is Internet Explorer
//DO NOT EDIT
function isIE(){
	return /msie/i.test(navigator.userAgent) && !/opera/i.test(navigator.userAgent);
}
