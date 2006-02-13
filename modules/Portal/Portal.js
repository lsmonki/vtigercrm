

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
