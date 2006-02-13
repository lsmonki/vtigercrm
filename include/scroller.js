/*********************************************************************************

** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/
 
 var tabcontainer=document.getElementById("tabcontainer")
tabcontainer.style.width=tabcontainer.offsetParent.offsetWidth
var tabcontent=document.getElementById("tabcontent")

if (window.navigator.appName.toUpperCase().indexOf("OPERA")>=0 || browser_nn4 || browser_nn6)
	var contentwidth=tabcontent.childNodes.item(1).offsetWidth
else if (browser_ie)
	var contentwidth=tabcontent.children[0].offsetWidth

if (get_cookie("scrollval")!=null && get_cookie("scrollval")!="none")
	tabcontent.style.left=get_cookie("scrollval")

if (contentwidth==parseInt(tabcontainer.style.width)) {
	getObj("scrollleft").style.visibility="hidden"
	getObj("scrollright").style.visibility="hidden"
	tabcontent.style.left="0px"
}

var speed=50;
var intensity=10;
function scrolright() {
	if (window.moverightvar) clearTimeout(moverightvar)
	moverightvar=setInterval("moveright()",speed)
}
function scrolleft() {
	if (window.moveleftvar) clearTimeout(moveleftvar)
	moveleftvar=setInterval("moveleft()",speed)
}
function moveright() {
	if (parseInt(tabcontent.style.left)>(parseInt(tabcontainer.style.width)-contentwidth)) {
		if (parseInt(tabcontent.style.left)-intensity>(parseInt(tabcontainer.style.width)-contentwidth))
			tabcontent.style.left=parseInt(tabcontent.style.left)-intensity+"px"
		else
			tabcontent.style.left=parseInt(tabcontainer.style.width)-contentwidth
		set_cookie("scrollval",tabcontent.style.left)
	}
}
function moveleft() {
	if (parseInt(tabcontent.style.left)<0) {
		if (parseInt(tabcontent.style.left)+intensity>0)
			tabcontent.style.left="0px"
		else
			tabcontent.style.left=parseInt(tabcontent.style.left)+intensity+"px"
		set_cookie("scrollval",tabcontent.style.left)
	}
}