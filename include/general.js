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

//End of Utility Functions



function emptyCheck(fldName,fldLabel, fldType) {

	var currObj=getObj(fldName)

        if( fldType && currObj.value == "none" ) {

                alert(fldLabel+" cannot be none")

                return false

        }   

	if (currObj.value.replace(/^\s+/g, '').replace(/\s+$/g, '').length==0) {

                alert(fldLabel+" cannot be empty")

		currObj.focus()

                return false

	}

        else

            return true

}



function patternValidate(fldName,fldLabel,type) {
	var currObj=getObj(fldName)
	if (type.toUpperCase()=="EMAIL") //Email ID validation
		var re=new RegExp(/^.+@.+\..+$/)
	
	if (type.toUpperCase()=="DATE") {//DATE validation 
		//YMD
//		var reg1 = /^\d{2}(\-|\/|\.)\d{1,2}\1\d{1,2}$/ //2 digit year
		var re = /^\d{4}(\-|\/|\.)\d{1,2}\1\d{1,2}$/ // 4 digit year
	   
		//MYD
//		var reg1 = /^\d{1,2}(\-|\/|\.)\d{2}\1\d{1,2}$/ 
//		var reg2 = /^\d{1,2}(\-|\/|\.)\d{4}\1\d{1,2}$/ 
	   
	   //DMY
//		var reg1 = /^\d{1,2}(\-|\/|\.)\d{1,2}\1\d{2}$/ 
//		var reg2 = /^\d{1,2}(\-|\/|\.)\d{1,2}\1\d{4}$/
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

function timeValidate(fldName,fldLabel) {
	if (patternValidate(fldName,fldLabel,"TIME")==false)
		return false
		
	var timeval=getObj(fldName).value.replace(/^\s+/g, '').replace(/\s+$/g, '')
	var hourval=parseInt(timeval.substring(0,timeval.indexOf(":")))
	var minval=parseInt(timeval.substring(timeval.indexOf(":")+1,timeval.length))
	var currObj=getObj(fldName)
	
	if (hourval>23 || minval>59) {
		alert("Please enter a valid "+fldLabel)
		currObj.focus()
		return false
	}
	else
        return true
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

function dateComparison(fldName1,fldLabel1,fldName2,fldLabel2,type) {
	var dateval1=getObj(fldName1).value.replace(/^\s+/g, '').replace(/\s+$/g, '')
	var dateval2=getObj(fldName2).value.replace(/^\s+/g, '').replace(/\s+$/g, '')
	
	var dd1=dateval1.substr(dateval1.lastIndexOf("-")+1,dateval1.length)
	var mm1=dateval1.substring(dateval1.indexOf("-")+1,dateval1.lastIndexOf("-"))
	var yyyy1=dateval1.substring(0,dateval1.indexOf("-"))

	var dd2=dateval2.substr(dateval2.lastIndexOf("-")+1,dateval2.length)
	var mm2=dateval2.substring(dateval2.indexOf("-")+1,dateval2.lastIndexOf("-"))
	var yyyy2=dateval2.substring(0,dateval2.indexOf("-"))
	
	var date1=new Date()
	var date2=new Date()		
	
	date1.setYear(yyyy1)
	date1.setMonth(mm1-1)
	date1.setDate(dd1)		
	
	date2.setYear(yyyy2)
	date2.setMonth(mm2-1)
	date2.setDate(dd2)
	
	var ret=true
	switch (type) {
		case 'LT'	:	if (date1>=date2) {//DATE1 VALUE LESS THAN DATE2
							alert(fldLabel1+" should be less than "+fldLabel2)
							ret=false
						}	
						break;
		case 'LE'	:	if (date1>date2) {//DATE1 VALUE LESS THAN OR EQUAL TO DATE2
							alert(fldLabel1+" should be less than or equal to "+fldLabel2)
							ret=false
						}
						break;	
		case 'EQ'	:	if (date1!=date2) {//DATE1 VALUE EQUAL TO DATE2
							alert(fldLabel1+" should be equal to "+fldLabel2)
							ret=false
						}
						break;	
		case 'GT'	:	if (date1<=date2) {//DATE1 VALUE GREATER THAN DATE2
							alert(fldLabel1+" should be greater than "+fldLabel2)
							ret=false
						}
						break;	
		case 'GE'	:	if (date1<date2) {//DATE1 GREATER THAN OR EQUAL TO DATE2
							alert(fldLabel1+" should be greater than or equal to "+fldLabel2)
							ret=false
						}
						break;	
	}
	
	if (ret==false) {
		getObj(fldName1).focus()
		return false
	} else return true;
}



function numValidate(fldName,fldLabel) {
	var val=getObj(fldName).value.replace(/^\s+/g, '').replace(/\s+$/g, '')
	if (isNaN(val)) {
		alert("Invalid "+fldLabel)
		getObj(fldName).focus()
		return false
	} else return true
}

function intValidate(fldName,fldLabel) {
	var val=getObj(fldName).value.replace(/^\s+/g, '').replace(/\s+$/g, '')
	if (isNaN(val) || val.indexOf(".")!=-1) {
		alert("Invalid "+fldLabel)
		getObj(fldName).focus()
		return false
	} else return true
}

function numConstComp(fldName,fldLabel,type,constval) {
	var val=parseFloat(getObj(fldName).value.replace(/^\s+/g, '').replace(/\s+$/g, ''))
	constval=parseFloat(constval)
	var ret=true
	
	switch (type) {
		case "LT":
				if (val>=constval) {
                                        alert(fldLabel+" should be less than "+constval)
                                        ret=false
                                }
                                break;
		case "LE":
				if (val>constval) {
					alert(fldLabel+" should be less than or equal to "+constval)
			        ret=false
				}
				break;
		case "EQ":
				if (val!=constval) {
                                        alert(fldLabel+" should be equal to "+constval)
                                        ret=false
                                }
                                break;
		case "NE":
				if (val==constval) {
                                        alert(fldLabel+" should not be equal to "+constval)
                                        ret=false
                                }
                                break;
		case "GT":
				if (val<=constval) {
                                        alert(fldLabel+" should be greater than "+constval)
                                        ret=false
                                }
                                break;

		case "GE":
				if (val<constval) {
                                        alert(fldLabel+" should be greater than or equal to "+constval)
                                        ret=false
                                }
                                break;
	}
	
	if (ret==false) {
		getObj(fldName).focus()
		return false
	} else return true;
}

function clearId(fldName) {

	var currObj=getObj(fldName)	

	currObj.value=""

}



function showLookUp(fldName,fldId,fldLabel,searchmodule,hostName,serverPort,username) {

	var currObj=getObj(fldName)

	//var fldValue=currObj.value.replace(/^\s+/g, '').replace(/\s+$/g, '')

	        	//need to pass the name of the system in which the server is running so that even when the search is invoked from another system, the url will remain the same

	openPopUp(currObj,"/crm/Search.do?searchmodule="+searchmodule+"&fldName="+fldName+"&fldId="+fldId+"&fldLabel="+fldLabel+"&fldValue=&user="+username,"LookUp",300,300,"menubar=no,toolbar=no,location=no,status=no,scrollbars=yes,resizable=yes")

}



function openPopUp(currObj,baseURL,winName,width,height,features) {
	var left=parseInt(findPosX(currObj))
	
	if (window.navigator.appName!="Opera") {
		var top=parseInt(findPosY(currObj))+parseInt(currObj.offsetHeight)
		if (browser_ie)	{
			top+=window.screenTop
			if (top+height>window.screen.height) 
				top=findPosY(currObj)+window.screenTop-height
			if (left+width>window.screen.width) 
				left=findPosX(currObj)+window.screenLeft-width
		}
		else if (browser_nn4 || browser_nn6) {
			top+=window.screenY
			if (top+height>window.screen.height) 
				top=findPosY(currObj)+window.screenY-height
			if (left+width>window.screen.width) 
				left=findPosX(currObj)+window.screenX-width
		}
	} else {
		var top=parseInt(findPosY(currObj))+(parseInt(currObj.offsetHeight)*2)+10
		if (top+height>window.screen.height)
			top=findPosY(currObj)+window.screenTop-height
		if (left+width>window.screen.width)
			left=findPosX(currObj)+window.screenLeft-width
	}
	
	features="width="+width+",height="+height+",top="+top+",left="+left+";"+features
	window.open(baseURL,winName,features)
}

