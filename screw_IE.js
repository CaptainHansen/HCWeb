//Internet explorer is such a terrible browser.  These functions exists to attempt to get my websites to work with it...

function getElementsByClassName(className) {
if (document.getElementsByClassName) { 
  return document.getElementsByClassName(className); }
else { return document.querySelectorAll('.' + className); } }

//returns true if the internet browser is Internet Explorer
//DO NOT EDIT
function isIE(){
	return /msie/i.test(navigator.userAgent) && !/opera/i.test(navigator.userAgent);
}
