/*********************************************************************************

** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/

//Utility Functions

if (document.all)

	var browser_ie=true

else if (document.layers)

	var browser_nn4=true

else if (document.layers || (!document.all && document.getElementById))

	var browser_nn6=true



function getObj(n,d) {

  var p,i,x; 

  if(!d)

      d=document;

   

  if((p=n.indexOf("?"))>0&&parent.frames.length) {

    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);

  }



  if(!(x=d[n])&&d.all)

      x=d.all[n];

 

  for(i=0;!x&&i<d.forms.length;i++)

      x=d.forms[i][n];

 

  for(i=0;!x&&d.layers&&i<d.layers.length;i++)

      x=getObj(n,d.layers[i].document);

 

  if(!x && d.getElementById)

      x=d.getElementById(n);



  return x;

}

	

function getOpenerObj(n) {

    return getObj(n,opener.document)

}



function findPosX(obj) {

	var curleft = 0;

	if (document.getElementById || document.all) {

		while (obj.offsetParent) {

			curleft += obj.offsetLeft

			obj = obj.offsetParent;

		}

	} else if (document.layers) {

		curleft += obj.x;

	}



	return curleft;

}



function findPosY(obj) {

	var curtop = 0;



	if (document.getElementById || document.all) {

		while (obj.offsetParent) {

			curtop += obj.offsetTop

			obj = obj.offsetParent;

		}

	} else if (document.layers) {

		curtop += obj.y;

	}



	return curtop;

}



function clearTextSelection() {

	if (browser_ie) document.selection.empty();

    else if (browser_nn4 || browser_nn6) window.getSelection().removeAllRanges();

}

function patternValidate(fldName,fldLabel,type) {
	var currObj=getObj(fldName)
	if (type.toUpperCase()=="EMAIL") //Email ID validation
		var re=new RegExp(/^.+@.+\..+$/)
	
	if (type.toUpperCase()=="DATE") {//DATE validation 
		var re = /^\d{4}(\-|\/|\.)\d{1,2}\1\d{1,2}$/ // 4 digit year
	   
	}
	
	if (type.toUpperCase()=="TIME") {//TIME validation
		var re = /^\d{1,2}(\:\d{1,2})*$/
	}
	
	if (!re.test(currObj.value)) {
		alert("Please enter a valid "+fldLabel)
		currObj.focus()
		return false
	}
}

function dateValidate(fldName,fldLabel,type) {
	if(patternValidate(fldName,fldLabel,"DATE")==false)
		return false;
	dateval=getObj(fldName).value.replace(/^\s+/g, '').replace(/\s+$/g, '') 
	
	dd=dateval.substr(dateval.lastIndexOf("-")+1,dateval.length)
	mm=dateval.substring(dateval.indexOf("-")+1,dateval.lastIndexOf("-"))
	yyyy=dateval.substring(0,dateval.indexOf("-"))
	
	if (dd<1 || dd>31 || mm<1 || mm>12 || yyyy<1 || yyyy<1000) {
		alert("Please enter a valid "+fldLabel)
		getObj(fldName).focus()
		return false
	}
	
	if ((mm==2) && (dd>29)) {//checking of no. of days in february month
		alert("Please enter a valid "+fldLabel)
		getObj(fldName).focus()
		return false
	}
	
	if ((mm==2) && (dd>28) && ((yyyy%4)!=0)) {//leap year checking
		alert("Please enter a valid "+fldLabel)
		getObj(fldName).focus()
		return false
	}

	switch (parseInt(mm)) {
		case 2 : 
		case 4 : 
		case 6 : 
		case 9 : 
		case 11 :	if (dd>30) {
						alert("Please enter a valid "+fldLabel)
						getObj(fldName).focus()
						return false
					}	
	}
	
	var currdate=new Date()
	var chkdate=new Date()
	
	chkdate.setYear(yyyy)
	chkdate.setMonth(mm-1)
	chkdate.setDate(dd)
	
	var ret=true
	switch (type) {
		case 'LCD'	:	if (chkdate>=currdate) {//DATE VALUE LESS THAN CURRENT DATE
							alert(fldLabel+" should be less than current date")
							ret=false
						}
						break;
		case 'LECD'	:	if (chkdate>currdate) {//DATE VALUE LESS THAN OR EQUAL TO CURRENT DATE
							alert(fldLabel+" should be less than or equal to current date")
							ret=false
						}
						break;
		case 'ECD'	:	if (chkdate!=currdate) {//DATE VALUE EQUAL TO CURRENT DATE
							alert(fldLabel+" should be equal to current date")
							ret=false
						}
						break;
		case 'GCD'	:	if (chkdate<=currdate) {//DATE VALUE GREATER THAN CURRENT DATE
							alert(fldLabel+" should be greater than current date")
							ret=false
						}
						break;	
		case 'GECD'	:	if (chkdate<currdate) {//DATE VALUE GREATER THAN OR EQUAL TO CURRENT DATE
							alert(fldLabel+" should be greater than or equal to current date")
							ret=false
						}
						break;
	}
	
	if (ret==false) {
		getObj(fldName).focus()
		return false
	} else return true;
}
//End of Utility Functions
