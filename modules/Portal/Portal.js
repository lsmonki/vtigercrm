/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/


/***********************************************
* Tabbed Document Viewer script- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

var selectedtablink=""
var tcischecked=false

function handlelink(aobject){
	selectedtablink=aobject.href
	tcischecked=(document.tabcontrol && document.tabcontrol.tabcheck.checked)? true : false
	if (document.getElementById && !tcischecked){
		var tabobj=document.getElementById("tablist")
		var tabobjlinks=tabobj.getElementsByTagName("A")
		for (i=0; i<tabobjlinks.length; i++)
			tabobjlinks[i].className=""
			aobject.className="current"
			document.getElementById("tabiframe").src=selectedtablink
			return false
	}
	else
		return true
}

function handleview(){
	tcischecked=document.tabcontrol.tabcheck.checked
	if (document.getElementById && tcischecked){
		if (selectedtablink!="")
			window.location=selectedtablink
	}
}
