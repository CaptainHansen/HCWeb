/* Below is example html code for implementing this script:
 * <div class="nav"><a id="dd0but" onMouseOver="overBut('dd0');" onMouseOut="offBut('dd0')" href="javascript:;">Store</a>
 * </div>
 * <div id="dd0" onMouseOver="overMenu('dd0');" onMouseOut="offMenu('dd0');" class="ddgal">
 * <a href="/store/?t=paintings">Paintings</a><a href="/store/?t=prints">Prints</a><a href="/store/?t=cards">Greeting Cards</a><a href="/store/?t=necklaces">Necklaces</a></div> 
 *
 */

//THESE FOUR FUNCTION MUST BE INCORPORATED INTO THE CODE OF THE WEBSITE ACCORDINGLY IN ORDER FOR FULL FUNCTIONALITY OF THIS SCRIPT

//this function is to be called from the onMouseOver object of the menu
//DO NOT EDIT
function overMenu(butid,menuid){
	document.getElementById(menuid).setAttribute('on','true');
}

//this function is to be called from the onMouseOut object of the menu
//DO NOT EDIT
function offMenu(butid,menuid,outfunc){
	menu=document.getElementById(menuid);
	menu.setAttribute('on','false');
	menu.setAttribute('wason','true');
	check_close_menu(butid,menuid,outfunc);
}

//BUTTON OVER AND OUT:

//this function is responsible for displaying the menu when the onMouseOver is used on the button
//DO NOT EDIT (except for the commented line)function show_menu(butid,menuid){
function overBut(butid,menuid,algn,overfunc){
	var but=document.getElementById(butid);
	var pos=findPos(but);
	var menu=document.getElementById(menuid);
	if(overfunc === undefined) {
		overfunc=function (butid,menuid) {}
	}
	if(algn === undefined) {
		algn='bl';
	}
	switch(algn){
	case 'br':
		pos[0]+= (but.offsetWidth - menu.offsetWidth);
		pos[1] += but.offsetHeight;
	break;
	case 'bc':
		pos[0] += (but.offsetWidth - menu.offsetWidth) / 2;
		pos[1] += (but.offsetHeight);
	break;
	case 'tl':
		pos[0] -= menu.offsetWidth;
	break;
	case 'tr':
		pos[0] += but.offsetWidth;
	break;
	case 'tc':
		pos[0] += (but.offsetWidth - menu.offsetWidth) / 2;
		pos[1] -= menu.offsetHeight;
	break;
	default : pos[1]+=but.offsetHeight;
	}
	document.getElementById(butid).setAttribute('on','true');
	menu.style.cssText="left:"+pos[0]+"px;top:"+pos[1]+"px;";
	if(menu.getAttribute('wason') != 'true') overfunc(butid,menuid);
	menu.style.display="block";
}

//this function is to be called from the onMouseOut object of the button
//DO NOT EDIT
function offBut(butid,menuid,outfunc){
	document.getElementById(butid).setAttribute('on','false');
	check_close_menu(butid,menuid,outfunc);
}

//END BUTTON OVER AND OUT

//REQUIRED FUNCTIONS BY THIS SCRIPT:

//this is a function required by this script to determine the position of the menu accurately under the button
//DO NOT EDIT
function findPos(obj) {
	var curleft = curtop = 0;
	if (obj.offsetParent) {
		do {
			curleft += obj.offsetLeft;
			curtop += obj.offsetTop;
		} while (obj = obj.offsetParent);
	return [curleft,curtop];
	}
}

//this function is called (from check_close_menu ONLY) after a short delay to ensure that the menu remains up if the mouse is moved from the link activating the menu to the menu itself.
//DO NOT EDIT
function close_menu(butid,menuid,outfunc){
	menu=document.getElementById(menuid);
	but=document.getElementById(butid);
	if(but.getAttribute('on') != 'true' && menu.getAttribute('on') != 'true'){
		menu.setAttribute('wason','false');
		menu.style.display="none";
		but.style.cssText="";
		if(outfunc !== undefined) outfunc(butid,menuid);
	}
}


//this function is called from within this script. DO NOT call it from an onMouseOut object
//DO NOT EDIT
function check_close_menu(butid,menuid,outfunc){
	if(outfunc !== undefined) {
		var funcname = outfunc.toString().match(/^function ([^(]+)/)[1];
		setTimeout('close_menu("'+butid+'","'+menuid+'",'+funcname+')',10);
	} else {
		setTimeout('close_menu("'+butid+'","'+menuid+'")',10);
	}
}
