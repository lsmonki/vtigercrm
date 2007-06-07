/*********************************************************************************

** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/

//Utility Functions

var gValidationCall='';

if (document.all)

	var browser_ie=true

else if (document.layers)

	var browser_nn4=true

else if (document.layers || (!document.all && document.getElementById))

	var browser_nn6=true

var gBrowserAgent = navigator.userAgent.toLowerCase();

function hideSelect()
{
        var oselect_array = document.getElementsByTagName('SELECT');
        for(var i=0;i<oselect_array.length;i++)
        {
                oselect_array[i].style.display = 'none';
        }
}

function showSelect()
{
        var oselect_array = document.getElementsByTagName('SELECT');
        for(var i=0;i<oselect_array.length;i++)
        {
                oselect_array[i].style.display = 'block';
        }
}
function getObj(n,d) {

  var p,i,x; 

  if(!d)

      d=document;

   
   if(n != undefined)
   {
	   if((p=n.indexOf("?"))>0&&parent.frames.length) {

		   d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);

	   }
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

// Setting cookies
function set_cookie ( name, value, exp_y, exp_m, exp_d, path, domain, secure )
{
  var cookie_string = name + "=" + escape ( value );

  if (exp_y) //delete_cookie(name)
  {
    var expires = new Date ( exp_y, exp_m, exp_d );
    cookie_string += "; expires=" + expires.toGMTString();
  }

  if (path) cookie_string += "; path=" + escape ( path );
  if (domain) cookie_string += "; domain=" + escape ( domain );
  if (secure) cookie_string += "; secure";

  document.cookie = cookie_string;
}

// Retrieving cookies
function get_cookie(cookie_name)
{
  var results = document.cookie.match(cookie_name + '=(.*?)(;|$)');
  if (results) return (unescape(results[1]));
  else return null;
}

// Delete cookies 
function delete_cookie( cookie_name )
{
  var cookie_date = new Date ( );  // current date & time
  cookie_date.setTime ( cookie_date.getTime() - 1 );
  document.cookie = cookie_name += "=; expires=" + cookie_date.toGMTString();
}
//End of Utility Functions



function emptyCheck(fldName,fldLabel, fldType) {
	var currObj=getObj(fldName)
	

	if (fldType=="text") {
		if (currObj.value.replace(/^\s+/g, '').replace(/\s+$/g, '').length==0) {

       			alert(fldLabel+alert_arr.CANNOT_BE_EMPTY)

			currObj.focus()

                	return false

		}

        	else
            	
		return true
	} else {
		if (trim(currObj.value) == '') {

	                alert(fldLabel+alert_arr.CANNOT_BE_NONE)

        	        return false

 	       } else return true

	}

}



function patternValidate(fldName,fldLabel,type) {
	var currObj=getObj(fldName)
	if (type.toUpperCase()=="YAHOO") //Email ID validation
	{
		//yahoo Id validation
		var re=new RegExp(/^[a-z0-9]([a-z0-9_\-\.]*)@([y][a][h][o][o])(\.[a-z]{2,3}(\.[a-z]{2}){0,2})$/)
	}
	if (type.toUpperCase()=="EMAIL") //Email ID validation
	{
		/*changes made to fix -- ticket#3278 & ticket#3461
		  var re=new RegExp(/^.+@.+\..+$/)*/
		var re=new RegExp(/^[a-z0-9]([a-z0-9_\-\.]*)@([a-z0-9_\-\.]*)(\.[a-z]{2,3}(\.[a-z]{2}){0,2})$/)
	}

	if (type.toUpperCase()=="DATE") {//DATE validation 
		//YMD
		//var reg1 = /^\d{2}(\-|\/|\.)\d{1,2}\1\d{1,2}$/ //2 digit year
		//var re = /^\d{4}(\-|\/|\.)\d{1,2}\1\d{1,2}$/ //4 digit year
	   
		//MYD
		//var reg1 = /^\d{1,2}(\-|\/|\.)\d{2}\1\d{1,2}$/ 
		//var reg2 = /^\d{1,2}(\-|\/|\.)\d{4}\1\d{1,2}$/ 
	   
	   //DMY
		//var reg1 = /^\d{1,2}(\-|\/|\.)\d{1,2}\1\d{2}$/ 
		//var reg2 = /^\d{1,2}(\-|\/|\.)\d{1,2}\1\d{4}$/

		switch (userDateFormat) {
			case "yyyy-mm-dd" : 
								var re = /^\d{4}(\-|\/|\.)\d{1,2}\1\d{1,2}$/
								break;
			case "mm-dd-yyyy" : 
			case "dd-mm-yyyy" : 
								var re = /^\d{1,2}(\-|\/|\.)\d{1,2}\1\d{4}$/								
		}
	}
	
	if (type.toUpperCase()=="TIME") {//TIME validation
		var re = /^\d{1,2}\:\d{1,2}$/
	}
	
	if (!re.test(currObj.value)) {
		alert(alert_arr.ENTER_VALID + fldLabel)
		currObj.focus()
		return false
	}
	else return true
}

function splitDateVal(dateval) {
	var datesep;
	var dateelements = new Array(3);
	
	if (dateval.indexOf("-")>=0) datesep="-"
	else if (dateval.indexOf(".")>=0) datesep="."
	else if (dateval.indexOf("/")>=0) datesep="/"
	
	switch (userDateFormat) {
		case "yyyy-mm-dd" : 
							dateelements[0]=dateval.substr(dateval.lastIndexOf(datesep)+1,dateval.length) //dd
							dateelements[1]=dateval.substring(dateval.indexOf(datesep)+1,dateval.lastIndexOf(datesep)) //mm
							dateelements[2]=dateval.substring(0,dateval.indexOf(datesep)) //yyyyy
							break;
		case "mm-dd-yyyy" : 
							dateelements[0]=dateval.substring(dateval.indexOf(datesep)+1,dateval.lastIndexOf(datesep))
							dateelements[1]=dateval.substring(0,dateval.indexOf(datesep))
							dateelements[2]=dateval.substr(dateval.lastIndexOf(datesep)+1,dateval.length)
							break;
		case "dd-mm-yyyy" : 
							dateelements[0]=dateval.substring(0,dateval.indexOf(datesep))
							dateelements[1]=dateval.substring(dateval.indexOf(datesep)+1,dateval.lastIndexOf(datesep))
							dateelements[2]=dateval.substr(dateval.lastIndexOf(datesep)+1,dateval.length)
	}
	
	return dateelements;
}

function compareDates(date1,fldLabel1,date2,fldLabel2,type) {
	var ret=true
	switch (type) {
		case 'L'	:	if (date1>=date2) {//DATE1 VALUE LESS THAN DATE2
							alert(fldLabel1+ alert_arr.SHOULDBE_LESS +fldLabel2)
							ret=false
						}
						break;
		case 'LE'	:	if (date1>date2) {//DATE1 VALUE LESS THAN OR EQUAL TO DATE2
							alert(fldLabel1+alert_arr.SHOULDBE_LESS_EQUAL+fldLabel2)
							ret=false
						}
						break;
		case 'E'	:	if (date1!=date2) {//DATE1 VALUE EQUAL TO DATE
							alert(fldLabel1+alert_arr.SHOULDBE_EQUAL+fldLabel2)
							ret=false
						}
						break;
		case 'G'	:	if (date1<=date2) {//DATE1 VALUE GREATER THAN DATE2
							alert(fldLabel1+alert_arr.SHOULDBE_GREATER+fldLabel2)
							ret=false
						}
						break;	
		case 'GE'	:	if (date1<date2) {//DATE1 VALUE GREATER THAN OR EQUAL TO DATE2
							alert(fldLabel1+alert_arr.SHOULDBE_GREATER_EQUAL+fldLabel2)
							ret=false
						}
						break;
	}
	
	if (ret==false) return false
	else return true
}

function dateTimeValidate(dateFldName,timeFldName,fldLabel,type) {
	if(patternValidate(dateFldName,fldLabel,"DATE")==false)
		return false;
	dateval=getObj(dateFldName).value.replace(/^\s+/g, '').replace(/\s+$/g, '') 
	
	var dateelements=splitDateVal(dateval)
	
	dd=dateelements[0]
	mm=dateelements[1]
	yyyy=dateelements[2]
	
	if (dd<1 || dd>31 || mm<1 || mm>12 || yyyy<1 || yyyy<1000) {
		alert(alert_arr.ENTER_VALID+fldLabel)
		getObj(dateFldName).focus()
		return false
	}
	
	if ((mm==2) && (dd>29)) {//checking of no. of days in february month
		alert(alert_arr.ENTER_VALID+fldLabel)
		getObj(dateFldName).focus()
		return false
	}
	
	if ((mm==2) && (dd>28) && ((yyyy%4)!=0)) {//leap year checking
		alert(alert_arr.ENTER_VALID+fldLabel)
		getObj(dateFldName).focus()
		return false
	}

	switch (parseInt(mm)) {
		case 2 : 
		case 4 : 
		case 6 : 
		case 9 : 
		case 11 :	if (dd>30) {
						alert(alert_arr.ENTER_VALID+fldLabel)
						getObj(dateFldName).focus()
						return false
					}	
	}
	
	if (patternValidate(timeFldName,fldLabel,"TIME")==false)
		return false
		
	var timeval=getObj(timeFldName).value.replace(/^\s+/g, '').replace(/\s+$/g, '')
	var hourval=parseInt(timeval.substring(0,timeval.indexOf(":")))
	var minval=parseInt(timeval.substring(timeval.indexOf(":")+1,timeval.length))
	var currObj=getObj(timeFldName)
	
	if (hourval>23 || minval>59) {
		alert(alert_arr.ENTER_VALID+fldLabel)
		currObj.focus()
		return false
	}
	
	var currdate=new Date()
	var chkdate=new Date()
	
	chkdate.setYear(yyyy)
	chkdate.setMonth(mm-1)
	chkdate.setDate(dd)
	chkdate.setHours(hourval)
	chkdate.setMinutes(minval)
	
	if (type!="OTH") {
		if (!compareDates(chkdate,fldLabel,currdate,"current date & time",type)) {
			getObj(dateFldName).focus()
			return false
		} else return true;
	} else return true;
}

function dateTimeComparison(dateFldName1,timeFldName1,fldLabel1,dateFldName2,timeFldName2,fldLabel2,type) {
	var dateval1=getObj(dateFldName1).value.replace(/^\s+/g, '').replace(/\s+$/g, '')
	var dateval2=getObj(dateFldName2).value.replace(/^\s+/g, '').replace(/\s+$/g, '')
	
	var dateelements1=splitDateVal(dateval1)
	var dateelements2=splitDateVal(dateval2)
	
	dd1=dateelements1[0]
	mm1=dateelements1[1]
	yyyy1=dateelements1[2]
	
	dd2=dateelements2[0]
	mm2=dateelements2[1]
	yyyy2=dateelements2[2]
	
	var timeval1=getObj(timeFldName1).value.replace(/^\s+/g, '').replace(/\s+$/g, '')
	var timeval2=getObj(timeFldName2).value.replace(/^\s+/g, '').replace(/\s+$/g, '')
	
	var hh1=timeval1.substring(0,timeval1.indexOf(":"))
	var min1=timeval1.substring(timeval1.indexOf(":")+1,timeval1.length)
	
	var hh2=timeval2.substring(0,timeval2.indexOf(":"))
	var min2=timeval2.substring(timeval2.indexOf(":")+1,timeval2.length)
	
	var date1=new Date()
	var date2=new Date()		
	
	date1.setYear(yyyy1)
	date1.setMonth(mm1-1)
	date1.setDate(dd1)
	date1.setHours(hh1)
	date1.setMinutes(min1)
	
	date2.setYear(yyyy2)
	date2.setMonth(mm2-1)
	date2.setDate(dd2)
	date2.setHours(hh2)
	date2.setMinutes(min2)
	
	if (type!="OTH") {
		if (!compareDates(date1,fldLabel1,date2,fldLabel2,type)) {
			getObj(dateFldName1).focus()
			return false
		} else return true;
	} else return true;
}

function dateValidate(fldName,fldLabel,type) {
	if(patternValidate(fldName,fldLabel,"DATE")==false)
		return false;
	dateval=getObj(fldName).value.replace(/^\s+/g, '').replace(/\s+$/g, '') 

	var dateelements=splitDateVal(dateval)
	
	dd=dateelements[0]
	mm=dateelements[1]
	yyyy=dateelements[2]
	
	if (dd<1 || dd>31 || mm<1 || mm>12 || yyyy<1 || yyyy<1000) {
		alert(alert_arr.ENTER_VALID+fldLabel)
		getObj(fldName).focus()
		return false
	}
	
	if ((mm==2) && (dd>29)) {//checking of no. of days in february month
		alert(alert_arr.ENTER_VALID+fldLabel)
		getObj(fldName).focus()
		return false
	}
	
	if ((mm==2) && (dd>28) && ((yyyy%4)!=0)) {//leap year checking
		alert(alert_arr.ENTER_VALID+fldLabel)
		getObj(fldName).focus()
		return false
	}

	switch (parseInt(mm)) {
		case 2 : 
		case 4 : 
		case 6 : 
		case 9 : 
		case 11 :	if (dd>30) {
						alert(alert_arr.ENTER_VALID+fldLabel)
						getObj(fldName).focus()
						return false
					}	
	}
	
	var currdate=new Date()
	var chkdate=new Date()
	
	chkdate.setYear(yyyy)
	chkdate.setMonth(mm-1)
	chkdate.setDate(dd)
	
	if (type!="OTH") {
		if (!compareDates(chkdate,fldLabel,currdate,"current date",type)) {
			getObj(fldName).focus()
			return false
		} else return true;
	} else return true;
}

function dateComparison(fldName1,fldLabel1,fldName2,fldLabel2,type) {
	var dateval1=getObj(fldName1).value.replace(/^\s+/g, '').replace(/\s+$/g, '')
	var dateval2=getObj(fldName2).value.replace(/^\s+/g, '').replace(/\s+$/g, '')

	var dateelements1=splitDateVal(dateval1)
	var dateelements2=splitDateVal(dateval2)
	
	dd1=dateelements1[0]
	mm1=dateelements1[1]
	yyyy1=dateelements1[2]
	
	dd2=dateelements2[0]
	mm2=dateelements2[1]
	yyyy2=dateelements2[2]
	
	var date1=new Date()
	var date2=new Date()		
	
	date1.setYear(yyyy1)
	date1.setMonth(mm1-1)
	date1.setDate(dd1)		
	
	date2.setYear(yyyy2)
	date2.setMonth(mm2-1)
	date2.setDate(dd2)
	
	if (type!="OTH") {
		if (!compareDates(date1,fldLabel1,date2,fldLabel2,type)) {
			getObj(fldName1).focus()
			return false
		} else return true;
	} else return true
}

function timeValidate(fldName,fldLabel,type) {
	if (patternValidate(fldName,fldLabel,"TIME")==false)
		return false
		
	var timeval=getObj(fldName).value.replace(/^\s+/g, '').replace(/\s+$/g, '')
	var hourval=parseInt(timeval.substring(0,timeval.indexOf(":")))
	var minval=parseInt(timeval.substring(timeval.indexOf(":")+1,timeval.length))
	var currObj=getObj(fldName)
	
	if (hourval>23 || minval>59) {
		alert(alert_arr.ENTER_VALID+fldLabel)
		currObj.focus()
		return false
	}
	
	var currtime=new Date()
	var chktime=new Date()
	
	chktime.setHours(hourval)
	chktime.setMinutes(minval)
	
	if (type!="OTH") {
		if (!compareDates(chktime,fldLabel1,currtime,"current time",type)) {
			getObj(fldName).focus()
			return false
		} else return true;
	} else return true
}

function timeComparison(fldName1,fldLabel1,fldName2,fldLabel2,type) {
	var timeval1=getObj(fldName1).value.replace(/^\s+/g, '').replace(/\s+$/g, '')
	var timeval2=getObj(fldName2).value.replace(/^\s+/g, '').replace(/\s+$/g, '')
	
	var hh1=timeval1.substring(0,timeval1.indexOf(":"))
	var min1=timeval1.substring(timeval1.indexOf(":")+1,timeval1.length)
	
	var hh2=timeval2.substring(0,timeval2.indexOf(":"))
	var min2=timeval2.substring(timeval2.indexOf(":")+1,timeval2.length)

	var time1=new Date()
	var time2=new Date()		
	
	time1.setHours(hh1)
	time1.setMinutes(min1)
	
	time2.setHours(hh2)
	time2.setMinutes(min2)

	if (type!="OTH") {	
		if (!compareDates(time1,fldLabel1,time2,fldLabel2,type)) {
			getObj(fldName1).focus()
			return false
		} else return true;
	} else return true;
}

function numValidate(fldName,fldLabel,format,neg) {
   var val=getObj(fldName).value.replace(/^\s+/g, '').replace(/\s+$/g, '')
   if (format!="any") {
       if (isNaN(val)) {
           var invalid=true
       } else {
           var format=format.split(",")
           var splitval=val.split(".")
           if (neg==true) {
               if (splitval[0].indexOf("-")>=0) {
                   if (splitval[0].length-1>format[0])
                       invalid=true
               } else {
                   if (splitval[0].length>format[0])
                       invalid=true
               }
           } else {
               if (val<0)
                   invalid=true
	       else if (format[0]==2 && splitval[0]==100 && (!splitval[1] || splitval[1]==0))
		   invalid=false
               else if (splitval[0].length>format[0])
                   invalid=true
           }
                      if (splitval[1])
               if (splitval[1].length>format[1])
                   invalid=true
       }
              if (invalid==true) {
           alert(alert_arr.INVALID+fldLabel)
           getObj(fldName).focus()
           return false
       } else return true
   } else {
	   // changes made -- to fix the ticket#3272
	   var splitval=val.split(".")
	   var arr_len = splitval.length;
           var len = 0;
	   if(fldName == "probability" || fldName == "commissionrate")
           {
                   if(arr_len > 1)
                           len = splitval[1].length;
                   if(isNaN(val))
                   {
                        alert(alert_arr.INVALID+fldLabel)
                        getObj(fldName).focus()
                        return false
                   }
                   else if(splitval[0] > 100 || len > 3)
                   {
                        alert( fldLabel + alert_arr.EXCEEDS_MAX);
                        return false;
                   }
           }
	   else if(splitval[0]>18446744073709551615)
           {
                   alert( fldLabel + alert_arr.EXCEEDS_MAX);
                   return false;
           }


       if (neg==true)
           var re=/^(-|)\d+(\.\d\d*)*$/
       else
           var re=/^\d+(\.\d\d*)*$/
   }

	//for precision check. ie.number must contains only one "."	
	var dotcount=0;
	for (var i = 0; i < val.length; i++)
	{   
	  	if (val.charAt(i) == ".")
			 dotcount++;
	}	

	if(dotcount>1)
	{
       		alert(alert_arr.INVALID+fldLabel)
		getObj(fldName).focus()
		return false;
	}

      if (!re.test(val)) {
       alert(alert_arr.INVALID+fldLabel)
       getObj(fldName).focus()
       return false
   } else return true
}


function intValidate(fldName,fldLabel) {
	var val=getObj(fldName).value.replace(/^\s+/g, '').replace(/\s+$/g, '')
	if (isNaN(val) || (val.indexOf(".")!=-1 && fldName != 'potential_amount' && fldName != 'list_price')) 
	{
		alert(alert_arr.INVALID+fldLabel)
		getObj(fldName).focus()
		return false
	} 
        else if((fldName != 'employees' || fldName != 'noofemployees') && (val < -2147483648 || val > 2147483647))
        {
                alert(fldLabel +alert_arr.OUT_OF_RANGE);
                return false;
        }
	else if((fldName == 'employees' || fldName != 'noofemployees') && (val < 0 || val > 2147483647))
        {
                alert(fldLabel +alert_arr.OUT_OF_RANGE);
                return false;
        }
	else
	{
		return true
	}
}

function numConstComp(fldName,fldLabel,type,constval) {
	var val=parseFloat(getObj(fldName).value.replace(/^\s+/g, '').replace(/\s+$/g, ''))
	constval=parseFloat(constval)

	var ret=true
	switch (type) {
		case "L"  : if (val>=constval) {
						alert(fldLabel+alert_arr.SHOULDBE_LESS+constval)
						ret=false
					}
					break;
		case "LE" :	if (val>constval) {
					alert(fldLabel+alert_arr.SHOULDBE_LESS_EQUAL+constval)
			        ret=false
					}
					break;
		case "E"  :	if (val!=constval) {
                                        alert(fldLabel+alert_arr.SHOULDBE_EQUAL+constval)
                                        ret=false
                                }
                                break;
		case "NE" : if (val==constval) {
						 alert(fldLabel+alert_arr.SHOULDNOTBE_EQUAL+constval)
							ret=false
					}
					break;
		case "G"  :	if (val<=constval) {
							alert(fldLabel+alert_arr.SHOULDBE_GREATER+constval)
							ret=false
					}
					break;
		case "GE" : if (val<constval) {
							alert(fldLabel+alert_arr.SHOULDBE_GREATER_EQUAL+constval)
							ret=false
					}
					break;
	}
	
	if (ret==false) {
		getObj(fldName).focus()
		return false
	} else return true;
}

function formValidate() {

//Validation for Portal User
if(gVTModule == 'Contacts' && gValidationCall != 'tabchange')
{
	if(getObj('portal').checked && trim(getObj('email').value) == '')   {
		alert(alert_arr.PORTAL_PROVIDE_EMAILID);
		return false;
	}
}
if(gVTModule == 'Contacts')
{
	if(getObj('imagename').value != '' )
	{
		var image_arr = new Array();
		image_arr = (getObj('imagename').value).split(".");
		if((image_arr[1] ==  "jpeg") || (image_arr[1] ==  "png") || (image_arr[1] ==  "jpg") || (image_arr[1] ==  "pjpeg") || (image_arr[1] ==  "x-png") || (image_arr[1] ==  "gif") )
		{
			return true;
		}
		else
		{
			alert(alert_arr.LBL_WRONG_IMAGE_TYPE);
			return false;
		}
	}
}


	for (var i=0; i<fieldname.length; i++) {
		if(getObj(fieldname[i]) != null)
		{
			var type=fielddatatype[i].split("~")
				if (type[1]=="M") {
					if (!emptyCheck(fieldname[i],fieldlabel[i],getObj(fieldname[i]).type))
						return false
				}

			switch (type[0]) {
				case "O"  : break;
				case "V"  : break;
				case "C"  : break;
				case "DT" :
					if (getObj(fieldname[i]) != null && getObj(fieldname[i]).value.replace(/^\s+/g, '').replace(/\s+$/g, '').length!=0)
					{	 
						if (type[1]=="M")
							if (!emptyCheck(fieldname[2],fieldlabel[i],getObj(type[2]).type))
								return false

									if(typeof(type[3])=="undefined") var currdatechk="OTH"
									else var currdatechk=type[3]

										if (!dateTimeValidate(fieldname[i],type[2],fieldlabel[i],currdatechk))
											return false
												if (type[4]) {
													if (!dateTimeComparison(fieldname[i],type[2],fieldlabel[i],type[5],type[6],type[4]))
														return false

												}
					}		
				break;
				case "D"  :
					if (getObj(fieldname[i]) != null && getObj(fieldname[i]).value.replace(/^\s+/g, '').replace(/\s+$/g, '').length!=0)
					{	
						if(typeof(type[2])=="undefined") var currdatechk="OTH"
						else var currdatechk=type[2]

							if (!dateValidate(fieldname[i],fieldlabel[i],currdatechk))
								return false
									if (type[3]) {
										if (!dateComparison(fieldname[i],fieldlabel[i],type[4],type[5],type[3]))
											return false
									}
					}	
				break;
				case "T"  :
					if (getObj(fieldname[i]) != null && getObj(fieldname[i]).value.replace(/^\s+/g, '').replace(/\s+$/g, '').length!=0)
					{	 
						if(typeof(type[2])=="undefined") var currtimechk="OTH"
						else var currtimechk=type[2]

							if (!timeValidate(fieldname[i],fieldlabel[i],currtimechk))
								return false
									if (type[3]) {
										if (!timeComparison(fieldname[i],fieldlabel[i],type[4],type[5],type[3]))
											return false
									}
					}
				break;
				case "I"  :
					if (getObj(fieldname[i]) != null && getObj(fieldname[i]).value.replace(/^\s+/g, '').replace(/\s+$/g, '').length!=0)
					{	
						if (getObj(fieldname[i]).value.length!=0)
						{
							if (!intValidate(fieldname[i],fieldlabel[i]))
								return false
									if (type[2]) {
										if (!numConstComp(fieldname[i],fieldlabel[i],type[2],type[3]))
											return false
									}
						}
					}
				break;
				case "N"  :
					case "NN" :
					if (getObj(fieldname[i]) != null && getObj(fieldname[i]).value.replace(/^\s+/g, '').replace(/\s+$/g, '').length!=0)
					{
						if (getObj(fieldname[i]).value.length!=0)
						{
							if (typeof(type[2])=="undefined") var numformat="any"
							else var numformat=type[2]

								if (type[0]=="NN") {

									if (!numValidate(fieldname[i],fieldlabel[i],numformat,true))
										return false
								} else {
									if (!numValidate(fieldname[i],fieldlabel[i],numformat))
										return false
								}
							if (type[3]) {
								if (!numConstComp(fieldname[i],fieldlabel[i],type[3],type[4]))
									return false
							}
						}
					}
				break;
				case "E"  :
					if (getObj(fieldname[i]) != null && getObj(fieldname[i]).value.replace(/^\s+/g, '').replace(/\s+$/g, '').length!=0)
					{
						if (getObj(fieldname[i]).value.length!=0)
						{
							var etype = "EMAIL"
							if(fieldname[i] == "yahooid" || fieldname[i] == "yahoo_id")
							{
								etype = "YAHOO";
							}
							if (!patternValidate(fieldname[i],fieldlabel[i],etype))
								return false;
						}
					}
				break;
			}
			//start Birth day date validation
			if(fieldname[i] == "birthday" && getObj(fieldname[i]).value.replace(/^\s+/g, '').replace(/\s+$/g, '').length!=0 )
			{
				var now =new Date()
				var currtimechk="OTH"
				var datelabel = fieldlabel[i]
				var datefield = fieldname[i]
				var datevalue =getObj(datefield).value.replace(/^\s+/g, '').replace(/\s+$/g, '')
                        	if (!dateValidate(fieldname[i],fieldlabel[i],currdatechk))
				{
		        	        getObj(datefield).focus()
                        		return false
				}
				else
				{
					datearr=splitDateVal(datevalue);
					dd=datearr[0]
					mm=datearr[1]
					yyyy=datearr[2]
					var datecheck = new Date()
        				datecheck.setYear(yyyy)
				        datecheck.setMonth(mm-1)
        				datecheck.setDate(dd)
                			if (!compareDates(datecheck,datelabel,now,"Current Date","L"))
					{
		                	        getObj(datefield).focus()
                			        return false
                			}
				}
			}
		      //End Birth day	
		}
		
	}
       //added to check Start Date & Time,if Activity Status is Planned.//start
        for (var j=0; j<fieldname.length; j++)
	{
		if(getObj(fieldname[j]) != null)
		{
			if(fieldname[j] == "date_start" || fieldname[j] == "task_date_start" )
			{
				var datelabel = fieldlabel[j]
				var datefield = fieldname[j]
				var startdatevalue = getObj(datefield).value.replace(/^\s+/g, '').replace(/\s+$/g, '')
			}
			if(fieldname[j] == "time_start" || fieldname[j] == "task_time_start")
			{
				var timelabel = fieldlabel[j]
				var timefield = fieldname[j]
				var timeval=getObj(timefield).value.replace(/^\s+/g, '').replace(/\s+$/g, '')
			}
			if(fieldname[j] == "eventstatus" || fieldname[j] == "taskstatus")
			{
				var statusvalue = getObj(fieldname[j]).value.replace(/^\s+/g, '').replace(/\s+$/g, '')
				var statuslabel = fieldlabel[j++]
			}
		}
	}
	if(statusvalue == "Planned")
        {
                var dateelements=splitDateVal(startdatevalue)

                var hourval=parseInt(timeval.substring(0,timeval.indexOf(":")))
                var minval=parseInt(timeval.substring(timeval.indexOf(":")+1,timeval.length))


               dd=dateelements[0]
               mm=dateelements[1]
               yyyy=dateelements[2]

               var currdate=new Date()
               var chkdate=new Date()
               chkdate.setYear(yyyy)
               chkdate.setMonth(mm-1)
               chkdate.setDate(dd)

               chktime = new Date()

               chktime.setMinutes(minval)
               chktime.setHours(hourval)
               chktime.setYear(yyyy)
               chktime.setMonth(mm-1)
               chktime.setDate(dd)
                if (!compareDates(chkdate,datelabel,currdate,alert_arr.DATE_SHOULDNOT_PAST,"GE")) {
                        getObj(datefield).focus()
                        return false
                }
                else if(!compareDates(chktime,timelabel,currdate,alert_arr.TIME_SHOULDNOT_PAST,"GE"))
                {
                        getObj(datefield).focus()
                        return false
                }
                else return true

	 }//end

	return true
}

function clearId(fldName) {

	var currObj=getObj(fldName)	

	currObj.value=""

}

function showCalc(fldName) {
	var currObj=getObj(fldName)
	openPopUp("calcWin",currObj,"/crm/Calc.do?currFld="+fldName,"Calc",170,220,"menubar=no,toolbar=no,location=no,status=no,scrollbars=no,resizable=yes")
}

function showLookUp(fldName,fldId,fldLabel,searchmodule,hostName,serverPort,username) {
	var currObj=getObj(fldName)

	//var fldValue=currObj.value.replace(/^\s+/g, '').replace(/\s+$/g, '')

	//need to pass the name of the system in which the server is running so that even when the search is invoked from another system, the url will remain the same

	openPopUp("lookUpWin",currObj,"/crm/Search.do?searchmodule="+searchmodule+"&fldName="+fldName+"&fldId="+fldId+"&fldLabel="+fldLabel+"&fldValue=&user="+username,"LookUp",500,400,"menubar=no,toolbar=no,location=no,status=no,scrollbars=yes,resizable=yes")
}

function openPopUp(winInst,currObj,baseURL,winName,width,height,features) {
	var left=parseInt(findPosX(currObj))
	var top=parseInt(findPosY(currObj))
	
	if (window.navigator.appName!="Opera") top+=parseInt(currObj.offsetHeight)
	else top+=(parseInt(currObj.offsetHeight)*2)+10

	if (browser_ie)	{
		top+=window.screenTop-document.body.scrollTop
		left-=document.body.scrollLeft
		if (top+height+30>window.screen.height) 
			top=findPosY(currObj)+window.screenTop-height-30 //30 is a constant to avoid positioning issue
		if (left+width>window.screen.width) 
			left=findPosX(currObj)+window.screenLeft-width
	} else if (browser_nn4 || browser_nn6) {
		top+=(scrY-pgeY)
		left+=(scrX-pgeX)
		if (top+height+30>window.screen.height) 
			top=findPosY(currObj)+(scrY-pgeY)-height-30
		if (left+width>window.screen.width) 
			left=findPosX(currObj)+(scrX-pgeX)-width
	}
	
	features="width="+width+",height="+height+",top="+top+",left="+left+";"+features
	eval(winInst+'=window.open("'+baseURL+'","'+winName+'","'+features+'")')
}

var scrX=0,scrY=0,pgeX=0,pgeY=0;

if (browser_nn4 || browser_nn6) {
	document.addEventListener("click",popUpListener,true)
}

function popUpListener(ev) {
	if (browser_nn4 || browser_nn6) {
		scrX=ev.screenX
		scrY=ev.screenY
		pgeX=ev.pageX
		pgeY=ev.pageY
	}
}

function toggleSelect(state,relCheckName) {
	if (getObj(relCheckName)) {
		if (typeof(getObj(relCheckName).length)=="undefined") {
			getObj(relCheckName).checked=state
		} else {
			for (var i=0;i<getObj(relCheckName).length;i++)
				getObj(relCheckName)[i].checked=state
		}
	}
}

function toggleSelectAll(relCheckName,selectAllName) {
	if (typeof(getObj(relCheckName).length)=="undefined") {
		getObj(selectAllName).checked=getObj(relCheckName).checked
	} else {
		var atleastOneFalse=false;
		for (var i=0;i<getObj(relCheckName).length;i++) {
			if (getObj(relCheckName)[i].checked==false) {
				atleastOneFalse=true
				break;
			}
		}
		getObj(selectAllName).checked=!atleastOneFalse
	}
}
//added for show/hide 10July
function expandCont(bn)
{
	var leftTab = document.getElementById(bn);
       	leftTab.style.display = (leftTab.style.display == "block")?"none":"block";
       	img = document.getElementById("img_"+bn);
      	img.src=(img.src.indexOf("images/toggle1.gif")!=-1)?"themes/images/toggle2.gif":"themes/images/toggle1.gif";
      	set_cookie_gen(bn,leftTab.style.display)

}

function setExpandCollapse_gen()
{
	var x = leftpanelistarray.length;
	for (i = 0 ; i < x ; i++)
	{
		var listObj=getObj(leftpanelistarray[i])
		var tgImageObj=getObj("img_"+leftpanelistarray[i])
		var status = get_cookie_gen(leftpanelistarray[i])
		
		if (status == "block") {
			listObj.style.display="block";
			tgImageObj.src="themes/images/toggle2.gif";
		} else if(status == "none") {
			listObj.style.display="none";
			tgImageObj.src="themes/images/toggle1.gif";
		}
	}
}

function toggleDiv(id) {

	var listTableObj=getObj(id)

	if (listTableObj.style.display=="block") 
	{
		listTableObj.style.display="none"
	}else{
		listTableObj.style.display="block"
	}
	//set_cookie(id,listTableObj.style.display)
}

//Setting cookies
function set_cookie_gen ( name, value, exp_y, exp_m, exp_d, path, domain, secure )
{
  var cookie_string = name + "=" + escape ( value );

  if ( exp_y )
  {
    var expires = new Date ( exp_y, exp_m, exp_d );
    cookie_string += "; expires=" + expires.toGMTString();
  }

  if ( path )
        cookie_string += "; path=" + escape ( path );

  if ( domain )
        cookie_string += "; domain=" + escape ( domain );
  
  if ( secure )
        cookie_string += "; secure";
  
  document.cookie = cookie_string;
}

// Retrieving cookies
function get_cookie_gen ( cookie_name )
{
  var results = document.cookie.match ( cookie_name + '=(.*?)(;|$)' );

  if ( results )
    return ( unescape ( results[1] ) );
  else
    return null;
}

// Delete cookies 
function delete_cookie_gen ( cookie_name )
{
  var cookie_date = new Date ( );  // current date & time
  cookie_date.setTime ( cookie_date.getTime() - 1 );
  document.cookie = cookie_name += "=; expires=" + cookie_date.toGMTString();
}
//end added for show/hide 10July

/** This is Javascript Function which is used to toogle between
  * assigntype user and group/team select options while assigning owner to entity.
  */
function toggleAssignType(currType)
{
        if (currType=="U")
        {
                getObj("assign_user").style.display="block"
                getObj("assign_team").style.display="none"
        }
        else
        {
                getObj("assign_user").style.display="none"
                getObj("assign_team").style.display="block"
        }
}
//to display type of address for google map
function showLocateMapMenu()
    {
            getObj("dropDownMenu").style.display="block"
            getObj("dropDownMenu").style.left=findPosX(getObj("locateMap"))
            getObj("dropDownMenu").style.top=findPosY(getObj("locateMap"))+getObj("locateMap").offsetHeight
    }


function hideLocateMapMenu(ev)
    {
            if (browser_ie)
                    currElement=window.event.srcElement
            else if (browser_nn4 || browser_nn6)
                    currElement=ev.target

            if (currElement.id!="locateMap")
                    if (getObj("dropDownMenu").style.display=="block")
                            getObj("dropDownMenu").style.display="none"
    }
/*
* javascript function to display the div tag
* @param divId :: div tag ID
*/
function show(divId)
{
	if(getObj(divId))
	{
		var id = document.getElementById(divId);

		id.style.display = 'inline';
	}
}

/*
* javascript function to display the div tag
* @param divId :: div tag ID
*/
function showBlock(divId)
{
    var id = document.getElementById(divId);
    id.style.display = 'block';
}


/*
* javascript function to hide the div tag
* @param divId :: div tag ID
*/
function hide(divId)
{

    var id = document.getElementById(divId);

    id.style.display = 'none';

}
function fnhide(divId)
{

    var id = document.getElementById(divId);

    id.style.display = 'none';
}

function fnLoadValues(obj1,obj2,SelTab,unSelTab,moduletype,module){
	
   var oform = document.forms['EditView'];
   oform.action.value='Save';	
   //global variable to check the validation calling function to avoid validating when tab change
   gValidationCall = 'tabchange'; 	
   if((moduletype == 'inventory' && validateInventory(module)) ||(moduletype == 'normal') && formValidate())	
   if(formValidate())
   {	
	   var tabName1 = document.getElementById(obj1);

	   var tabName2 = document.getElementById(obj2);

	   var tagName1 = document.getElementById(SelTab);

	   var tagName2 = document.getElementById(unSelTab);

	   if(tabName1.className == "dvtUnSelectedCell")

		   tabName1.className = "dvtSelectedCell";

	   if(tabName2.className == "dvtSelectedCell")

		   tabName2.className = "dvtUnSelectedCell";   
	   tagName1.style.display='block';

	   tagName2.style.display='none';
   }
   gValidationCall = ''; 	
}

function fnCopy(source,design){

   document.getElementById(source).value=document.getElementById(design).value;

   document.getElementById(source).disabled=true;

}

function fnClear(source){

   document.getElementById(source).value=" ";

   document.getElementById(source).disabled=false;

}

function fnCpy(){

   var tagName=document.getElementById("cpy");

   if(tagName.checked==true){   
       fnCopy("shipaddress","address");

       fnCopy("shippobox","pobox");

       fnCopy("shipcity","city");

       fnCopy("shipcode","code");

       fnCopy("shipstate","state");

       fnCopy("shipcountry","country");

   }

   else{

       fnClear("shipaddress");

       fnClear("shippobox");

       fnClear("shipcity");

       fnClear("shipcode");

       fnClear("shipstate");

       fnClear("shipcountry");

   }

}
function fnDown(obj){
        var tagName = document.getElementById(obj);
        var tabName = document.getElementById("one");
        if(tagName.style.display == 'none'){
                tagName.style.display = 'block';
                tabName.style.display = 'block';
        }
        else{
                tabName.style.display = 'none';
                tagName.style.display = 'none';
        }
}

/*
* javascript function to add field rows
* @param option_values :: List of Field names
*/
var count = 0;
var rowCnt = 1;
function fnAddSrch(option_values,criteria_values){

    var tableName = document.getElementById('adSrc');

    var prev = tableName.rows.length;

    var count = prev;

    var row = tableName.insertRow(prev);

    if(count%2)

        row.className = "dvtCellLabel";

    else

        row.className = "dvtCellInfo";

    var colone = row.insertCell(0);

    var coltwo = row.insertCell(1);

    var colthree = row.insertCell(2);

    colone.innerHTML="<select id='Fields"+count+"' name='Fields"+count+"' onchange=\"updatefOptions(this, 'Condition"+count+"')\" class='detailedViewTextBox'>"+option_values+"</select>";

    coltwo.innerHTML="<select id='Condition"+count+"' name='Condition"+count+"' class='detailedViewTextBox'>"+criteria_values+"</select> ";

    colthree.innerHTML="<input type='text' id='Srch_value"+count+"' name='Srch_value"+count+"' class='detailedViewTextBox'>";

}

function totalnoofrows()
{
	var tableName = document.getElementById('adSrc');
	document.basicSearch.search_cnt.value = tableName.rows.length;
}

/*
* javascript function to delete field rows in advance search
* @param void :: void
*/
function delRow()
{

    var tableName = document.getElementById('adSrc');

    var prev = tableName.rows.length;

    if(prev > 1)

    document.getElementById('adSrc').deleteRow(prev-1);

}

function fnVis(obj){

   var profTag = document.getElementById("prof");

   var moreTag = document.getElementById("more");

   var addrTag = document.getElementById("addr");

  
   if(obj == 'prof'){

       document.getElementById('mnuTab').style.display = 'block';

       document.getElementById('mnuTab1').style.display = 'none';

       document.getElementById('mnuTab2').style.display = 'none';

       profTag.className = 'dvtSelectedCell';

       moreTag.className = 'dvtUnSelectedCell';

       addrTag.className = 'dvtUnSelectedCell';

   }

  
   else if(obj == 'more'){

       document.getElementById('mnuTab1').style.display = 'block';

       document.getElementById('mnuTab').style.display = 'none';

       document.getElementById('mnuTab2').style.display = 'none';

       moreTag.className = 'dvtSelectedCell';

       profTag.className = 'dvtUnSelectedCell';

       addrTag.className = 'dvtUnSelectedCell';

   }

  
   else if(obj == 'addr'){

       document.getElementById('mnuTab2').style.display = 'block';

       document.getElementById('mnuTab').style.display = 'none';

       document.getElementById('mnuTab1').style.display = 'none';

       addrTag.className = 'dvtSelectedCell';

       profTag.className = 'dvtUnSelectedCell';

       moreTag.className = 'dvtUnSelectedCell';

   }

}

function fnvsh(obj,Lay){
    var tagName = document.getElementById(Lay);
    var leftSide = findPosX(obj);
    var topSide = findPosY(obj);
    tagName.style.left= leftSide + 175 + 'px';
    tagName.style.top= topSide + 'px';
    tagName.style.visibility = 'visible';
}

function fnvshobj(obj,Lay){
    var tagName = document.getElementById(Lay);
    var leftSide = findPosX(obj);
    var topSide = findPosY(obj);
    var maxW = tagName.style.width;
    var widthM = maxW.substring(0,maxW.length-2);
    if(Lay == 'editdiv') {
        leftSide = leftSide - 225;
        topSide = topSide - 125;
    }
    var getVal = eval(leftSide) + eval(widthM);
    if(getVal  > document.body.clientWidth ){
        leftSide = eval(leftSide) - eval(widthM);
        tagName.style.left = leftSide + 34 + 'px';
    }
    else
        tagName.style.left= leftSide + 'px';
    tagName.style.top= topSide + 'px';
    tagName.style.display = 'block';
    tagName.style.visibility = "visible";
}

function posLay(obj,Lay){
    var tagName = document.getElementById(Lay);
    var leftSide = findPosX(obj);
    var topSide = findPosY(obj);
    var maxW = tagName.style.width;
    var widthM = maxW.substring(0,maxW.length-2);
    var getVal = eval(leftSide) + eval(widthM);
    if(getVal  > document.body.clientWidth ){
        leftSide = eval(leftSide) - eval(widthM);
        tagName.style.left = leftSide + 'px';
    }
    else
        tagName.style.left= leftSide + 'px';
    tagName.style.top= topSide + 'px';
}

function fninvsh(Lay){
    var tagName = document.getElementById(Lay);
    tagName.style.visibility = 'hidden';
    tagName.style.display = 'none';
}

function fnvshNrm(Lay){
    var tagName = document.getElementById(Lay);
    tagName.style.visibility = 'visible';
    tagName.style.display = 'block';
}

function cancelForm(frm)
{
	    window.history.back();
}

function trim(s)
{
	while (s.substring(0,1) == " " || s.substring(0,1) == "\n")
	{
		s = s.substring(1, s.length);
	}
	while (s.substring(s.length-1, s.length) == " " || s.substring(s.length-1,s.length) == "\n") {
		s = s.substring(0,s.length-1);
	}
	return s;
}

function clear_form(form)
{
	for (j = 0; j < form.elements.length; j++)
	{
		if (form.elements[j].type == 'text' || form.elements[j].type == 'select-one')
		{
			form.elements[j].value = '';
		}
	}
}

function ActivateCheckBox()
{
        var map = document.getElementById("saved_map_checkbox");
        var source = document.getElementById("saved_source");

        if(map.checked == true)
        {
                source.disabled = false;
        }
        else
        {
                source.disabled = true;
        }
}

//wipe for Convert Lead  

function fnSlide2(obj,inner)
{
  var buff = document.getElementById(obj).height;
  closeLimit = buff.substring(0,buff.length);
  menu_max = eval(closeLimit);
  var tagName = document.getElementById(inner);
  document.getElementById(obj).style.height=0 + "px"; menu_i=0;
  if (tagName.style.display == 'none')
          fnexpanLay2(obj,inner);
  else
        fncloseLay2(obj,inner);
 }

function fnexpanLay2(obj,inner)
{
    // document.getElementById(obj).style.display = 'run-in';
   var setText = eval(closeLimit) - 1;
   if (menu_i<=eval(closeLimit))
   {
            if (menu_i>setText){document.getElementById(inner).style.display='block';}
       document.getElementById(obj).style.height=menu_i + "px";
           setTimeout(function() { fnexpanLay2(obj,inner); },5);
        menu_i=menu_i+5;
   }
}

 function fncloseLay2(obj,inner)
{
  if (menu_max >= eval(openLimit))
   {
            if (menu_max<eval(closeLimit)){document.getElementById(inner).style.display='none';}
       document.getElementById(obj).style.height=menu_max +"px";
          setTimeout(function() { fncloseLay2(obj,inner); }, 5);
       menu_max = menu_max -5;
   }
}

function addOnloadEvent(fnc){
  if ( typeof window.addEventListener != "undefined" )
    window.addEventListener( "load", fnc, false );
  else if ( typeof window.attachEvent != "undefined" ) {
    window.attachEvent( "onload", fnc );
  }
  else {
    if ( window.onload != null ) {
      var oldOnload = window.onload;
      window.onload = function ( e ) {
        oldOnload( e );
        window[fnc]();
      };
    }
    else
      window.onload = fnc;
  }
}
function InternalMailer(record_id,field_id,par_module,type) {
        var url;
        switch(type) {
                case 'record_id':
                        url = 'index.php?module=Emails&action=EmailsAjax&internal_mailer=true&type='+type+'&record=&field_id='+field_id+'&rec_id='+record_id+'&file=EditView&par_module='+par_module;//query string field_id added for listview-compose email issue
                break;
                case 'email_addy':
                        url = 'index.php?module=Emails&action=EmailsAjax&internal_mailer=true&type='+type+'&record=&email_addy='+record_id+'&file=EditView';
                break;

        }

        var opts = "menubar=no,toolbar=no,location=no,status=no,resizable=yes,scrollbars=yes";
        openPopUp('xComposeEmail',this,url,'createemailWin',830,662,opts);
}

function fnHide_Event(obj){
        document.getElementById(obj).style.visibility = 'hidden';
}
function ReplyCompose(id,mode)
{
			url = 'index.php?module=Emails&action=EmailsAjax&file=EditView&record='+id+'&reply=true';
	
	openPopUp('xComposeEmail',this,url,'createemailWin',820,689,'menubar=no,toolbar=no,location=no,status=no,resizable=no,scrollbars=yes');	
}
function OpenCompose(id,mode) 
{
	switch(mode)
	{		
		case 'edit':
			url = 'index.php?module=Emails&action=EmailsAjax&file=EditView&record='+id;
			break;
		case 'create':
			url = 'index.php?module=Emails&action=EmailsAjax&file=EditView';
			break;
		case 'forward':
			url = 'index.php?module=Emails&action=EmailsAjax&file=EditView&record='+id+'&forward=true';
			break;
	}
	openPopUp('xComposeEmail',this,url,'createemailWin',820,689,'menubar=no,toolbar=no,location=no,status=no,resizable=no,scrollbars=yes');
}

//Function added for Mass select in Popup - Philip
function SelectAll(mod,parmod)
{

        x = document.selectall.selected_id.length;
	var y=0;
	if(parmod != 'Calendar')
        {
                var module = window.opener.document.getElementById('RLreturn_module').value
                var entity_id = window.opener.document.getElementById('RLparent_id').value
        }
        idstring = "";
	namestr = "";

        if ( x == undefined)
        {

                if (document.selectall.selected_id.checked)
                {
			idstring = document.selectall.selected_id.value;
			if(parmod == 'Calendar')
                                namestr = document.getElementById('calendarCont'+idstring).innerHTML;
                        y=1;
                }
                else
		{
                        alert(alert_arr.SELECT);
                        return false;
                }
        }
        else
        {
                y=0;
                for(i = 0; i < x ; i++)
                {
                        if(document.selectall.selected_id[i].checked)
                        {
                                idstring = document.selectall.selected_id[i].value +";"+idstring;
				if(parmod == 'Calendar')
                                {
                                        idval = document.selectall.selected_id[i].value;
                                        namestr = document.getElementById('calendarCont'+idval).innerHTML+"\n"+namestr;
                                }
                  		y=y+1;
                        }
                }
	}
	if (y != 0)
        {
        	document.selectall.idlist.value=idstring;
        }
        else
        {
                alert(alert_arr.SELECT);
                return false;
        }
        if(confirm(alert_arr.ADD_CONFIRMATION+y+alert_arr.RECORDS))
        {
		if(parmod == 'Calendar')
                {
                        window.opener.document.EditView.contactidlist.value = idstring;
                        window.opener.document.EditView.contactlist.value = namestr;
                }
                else
                {
			opener.document.location.href="index.php?module="+module+"&parentid="+entity_id+"&action=updateRelations&destination_module="+mod+"&idlist="+idstring;
		}
                self.close();
        }
	else
        {
                return false;
        }
}
function ShowEmail(id)
{
       url = 'index.php?module=Emails&action=EmailsAjax&file=DetailView&record='+id;
       openPopUp('xComposeEmail',this,url,'createemailWin',820,695,'menubar=no,toolbar=no,location=no,status=no,resizable=no,scrollbars=yes');
}

var bSaf = (navigator.userAgent.indexOf('Safari') != -1);
var bOpera = (navigator.userAgent.indexOf('Opera') != -1);
var bMoz = (navigator.appName == 'Netscape');
function execJS(node) {
    var st = node.getElementsByTagName('SCRIPT');
    var strExec;
    for(var i=0;i<st.length; i++) {
      if (bSaf) {
        strExec = st[i].innerHTML;
      }
      else if (bOpera) {
        strExec = st[i].text;
      }
      else if (bMoz) {
        strExec = st[i].textContent;
      }
      else {
        strExec = st[i].text;
      }
      try {
        eval(strExec);
      } catch(e) {
        alert(e);
      }
    }
}

//Function added for getting the Tab Selected Values (Standard/Advanced Filters) for Custom View - Ahmed
function fnLoadCvValues(obj1,obj2,SelTab,unSelTab){

   var tabName1 = document.getElementById(obj1);

   var tabName2 = document.getElementById(obj2);

   var tagName1 = document.getElementById(SelTab);

   var tagName2 = document.getElementById(unSelTab);

   if(tabName1.className == "dvtUnSelectedCell")

       tabName1.className = "dvtSelectedCell";

   if(tabName2.className == "dvtSelectedCell")

       tabName2.className = "dvtUnSelectedCell";   
   tagName1.style.display='block';

   tagName2.style.display='none';

}


// Drop Dwon Menu


function fnDropDown(obj,Lay){
    var tagName = document.getElementById(Lay);
    var leftSide = findPosX(obj);
    var topSide = findPosY(obj);
    var maxW = tagName.style.width;
    var widthM = maxW.substring(0,maxW.length-2);
    var getVal = eval(leftSide) + eval(widthM);
    if(getVal  > document.body.clientWidth ){
        leftSide = eval(leftSide) - eval(widthM);
        tagName.style.left = leftSide + 34 + 'px';
    }
    else
        tagName.style.left= leftSide + 'px';
    tagName.style.top= topSide + 28 +'px';
    tagName.style.display = 'block';
 }

function fnShowDrop(obj){
	document.getElementById(obj).style.display = 'block';
}

function fnHideDrop(obj){
	document.getElementById(obj).style.display = 'none';
}

function getCalendarPopup(imageid,fieldid,dateformat)
{
        Calendar.setup ({
                inputField : fieldid, ifFormat : dateformat, showsTime : false, button : imageid, singleClick : true, step : 1
        });
}

//Added to check duplicate account creation

function AjaxDuplicateValidate(module,fieldname,oform)
{
      var fieldvalue = getObj(fieldname).value;
	if(fieldvalue == '')
	{
		alert(alert_arr.ACCOUNTNAME_CANNOT_EMPTY);
		return false;	
	}
      var url = "module="+module+"&action="+module+"Ajax&file=Save&"+fieldname+"="+fieldvalue+"&dup_check=true"
      new Ajax.Request(
                            'index.php',
                              {queue: {position: 'end', scope: 'command'},
                                      method: 'post',
                                      postBody:url,
                                      onComplete: function(response) {
                                              var str = response.responseText
                                              if(str.indexOf('SUCCESS') > -1)
                                              {
                                                      oform.submit();
                                              }else
                                              {
                                                      alert(str);
                                              }
                                      }
                              }
                              );
}

/**to get SelectContacts Popup
check->to check select options enable or disable
*type->to differentiate from task
*frmName->form name*/

function selectContact(check,type,frmName)
{
        if($("single_accountid"))
        {
		var potential_id = '';
		if($("potential_id"))
			potential_id = frmName.potential_id.value;
		account_id = frmName.account_id.value;
		if(potential_id != '')
		{
			record_id = potential_id;
			module_string = "&parent_module=Potentials";
		}	
		else
		{
			record_id = account_id;
			module_string = "&parent_module=Accounts";
		}
		if(record_id != '')
	                window.open("index.php?module=Contacts&action=Popup&html=Popup_picker&popuptype=specific&form=EditView"+module_string+"&relmod_id="+record_id,"test","width=640,height=602,resizable=0,scrollbars=0");
		else
			 window.open("index.php?module=Contacts&action=Popup&html=Popup_picker&popuptype=specific&form=EditView","test","width=640,height=602,resizable=0,scrollbars=0");	
        }
        else if(($("parentid")) && type != 'task')
        {
		if(getObj("parent_type")){
                	rel_parent_module = frmName.parent_type.value;
			record_id = frmName.parent_id.value;
        	        module = rel_parent_module.split("&");	
			if(record_id != '' && module[0] == "Leads")
			{
				alert(alert_arr.CANT_SELECT_CONTACTS);
			}
			else
			{
				if(check == 'true')
					search_string = "&return_module=Calendar&select=enable&popuptype=detailview&form_submit=false";
				else
					search_string="&popuptype=specific";
				if(record_id != '')
					window.open("index.php?module=Contacts&action=Popup&html=Popup_picker&form=EditView"+search_string+"&relmod_id="+record_id+"&parent_module="+module[0],"test","width=640,height=602,resizable=0,scrollbars=0");
				else
					window.open("index.php?module=Contacts&action=Popup&html=Popup_picker&form=EditView"+search_string,"test","width=640,height=602,resizable=0,scrollbars=0");
			}
		}else{
			window.open("index.php?module=Contacts&action=Popup&html=Popup_picker&return_module=Calendar&select=enable&popuptype=detailview&form=EditView&form_submit=false","test","width=640,height=602,resizable=0,scrollbars=0");
		}
        }
	else if(($("contact_name")) && type == 'task')
	{
		var formName = frmName.name;
		if(formName == 'EditView')
		{
			task_parent_module = frmName.parent_type.value;
			task_recordid = frmName.parent_id.value;
			task_module = task_parent_module.split("&");
			popuptype="&popuptype=specific";
		}
		else
		{
			task_parent_module = frmName.task_parent_type.value;
			task_recordid = frmName.task_parent_id.value;
			task_module = task_parent_module.split("&");
			popuptype="&popuptype=toDospecific";
		}
		if(task_recordid != '' && task_module[0] == "Leads" )
		{
			alert(alert_arr.CANT_SELECT_CONTACTS);
		}
		else
		{
			if(task_recordid != '')
				window.open("index.php?module=Contacts&action=Popup&html=Popup_picker"+popuptype+"&form=EditView&task_relmod_id="+task_recordid+"&task_parent_module="+task_module[0],"test","width=640,height=602,resizable=0,scrollbars=0");
			else	
				window.open("index.php?module=Contacts&action=Popup&html=Popup_picker"+popuptype+"&form=EditView","test","width=640,height=602,resizable=0,scrollbars=0");
		}

	}
        else
        {
                window.open("index.php?module=Contacts&action=Popup&html=Popup_picker&popuptype=specific&form=EditView","test","width=640,height=602,resizable=0,scrollbars=0");
        }
}
//to get Select Potential Popup
function selectPotential()
{
	var record_id= document.EditView.account_id.value;
	if(record_id != '')
		window.open("index.php?module=Potentials&action=Popup&html=Popup_picker&popuptype=specific_potential_account_address&form=EditView&relmod_id="+record_id+"&parent_module=Accounts","test","width=640,height=602,resizable=0,scrollbars=0");
	else
		window.open("index.php?module=Potentials&action=Popup&html=Popup_picker&popuptype=specific_potential_account_address&form=EditView","test","width=640,height=602,resizable=0,scrollbars=0");
}
//to select Quote Popup
function selectQuote()
{
	var record_id= document.EditView.account_id.value;
        if(record_id != '')
		window.open("index.php?module=Quotes&action=Popup&html=Popup_picker&popuptype=specific&form=EditView&relmod_id="+record_id+"&parent_module=Accounts","test","width=640,height=602,resizable=0,scrollbars=0");

	else
		window.open("index.php?module=Quotes&action=Popup&html=Popup_picker&popuptype=specific&form=EditView","test","width=640,height=602,resizable=0,scrollbars=0");
}
//to get select SalesOrder Popup
function selectSalesOrder()
{
	var record_id= document.EditView.account_id.value;
        if(record_id != '')
		window.open("index.php?module=SalesOrder&action=Popup&html=Popup_picker&popuptype=specific&form=EditView&relmod_id="+record_id+"&parent_module=Accounts","test","width=640,height=602,resizable=0,scrollbars=0");
	else
		window.open("index.php?module=SalesOrder&action=Popup&html=Popup_picker&popuptype=specific&form=EditView","test","width=640,height=602,resizable=0,scrollbars=0");
}

function checkEmailid(parent_module,emailid,yahooid)
 {
       var check = true;
       if(emailid == '' && yahooid == '')
       {
               alert(alert_arr.LBL_THIS+parent_module+alert_arr.DOESNOT_HAVE_MAILIDS);
               check=false;
       }
       return check;
 }

function calQCduedatetime()
{
        var datefmt = document.QcEditView.dateFormat.value;
        var type = document.QcEditView.activitytype.value;
        var dateval1=getObj('date_start').value.replace(/^\s+/g, '').replace(/\s+$/g, '');
        var dateelements1=splitDateVal(dateval1);
        dd1=parseInt(dateelements1[0],10);
        mm1=dateelements1[1];
        yyyy1=dateelements1[2];
        var date1=new Date();
        date1.setYear(yyyy1);
        date1.setMonth(mm1-1,dd1+1);
        var yy = date1.getFullYear();
        var mm = date1.getMonth() + 1;
        var dd = date1.getDate();
        var date = document.QcEditView.date_start.value;
        var starttime = document.QcEditView.time_start.value;
        if (!timeValidate('time_start',' Start Date & Time','OTH'))
                return false;
        var timearr = starttime.split(":");
        var hour = parseInt(timearr[0],10);
        var min = parseInt(timearr[1],10);
        dd = _2digit(dd);
        mm = _2digit(mm);
        var tempdate = yy+'-'+mm+'-'+dd;
        if(datefmt == '%d-%m-%Y')
                var tempdate = dd+'-'+mm+'-'+yy;
        else if(datefmt == '%m-%d-%Y')
                var tempdate = mm+'-'+dd+'-'+yy;
        if(type == 'Meeting')
        {
                hour = hour + 1;
                if(hour == 24)
                {
                        hour = 0;
                        date =  tempdate;
                }
                hour = _2digit(hour);
		min = _2digit(min);
                document.QcEditView.due_date.value = date;
                document.QcEditView.time_end.value = hour+':'+min;
        }
        if(type == 'Call')
        {
                if(min >= 55)
                {
                        min = min%55;
                        hour = hour + 1;
                }else min = min + 5;
                if(hour == 24)
                {
                        hour = 0;
                        date =  tempdate;
                }
                hour = _2digit(hour);
		min = _2digit(min);
                document.QcEditView.due_date.value = date;
                document.QcEditView.time_end.value = hour+':'+min;
        }

}

function _2digit( no ){
        if(no < 10) return "0" + no;
        else return "" + no;
}

function confirmdelete(url)
{
if(confirm(alert_arr.ARE_YOU_SURE))
       {
            document.location.href=url;
       }
}


function valid(c,type)
{
	if(type == 'name')
	{
		return (((c >= 'a') && (c <= 'z')) ||((c >= 'A') && (c <= 'Z')) ||((c >= '0') && (c <= '9')) || (c == '.') || (c == '_') || (c == '-') );
	}
	else if(type == 'namespace')
	{
		return (((c >= 'a') && (c <= 'z')) ||((c >= 'A') && (c <= 'Z')) ||((c >= '0') && (c <= '9')) || (c == '.')||(c==' ') || (c == '_') || (c == '-') );
	}
}

function CharValidation(s,type)
{
	for (var i = 0; i < s.length; i++)
	{
		if (!valid(s.charAt(i),type))
		{
			return false;
		}
	}
	return true;
}


/** Check Upload file is in specified format(extension).
  * @param fldname -- name of the file field
  * @param fldLabel -- Lable of the file field
  * @param filter -- List of file extensions to allow. each extension must be seperated with a | sybmol.
  * Example: upload_filter("imagename","Image", "jpg|gif|bmp|png") 
  * @returns true -- if the extension is IN  specified extension.
  * @returns false -- if the extension is NOT IN specified extension.
  *
  * NOTE: If this field is mandatory,  please call emptyCheck() function before calling this function.
 */

function upload_filter(fldName, filter)
{
	var currObj=getObj(fldName)
	if(currObj.value !="")
	{
		var file=currObj.value;
		var type=file.split(".");
		var valid_extn=filter.split("|");	
	
		if(valid_extn.indexOf(type[type.length-1]) == -1)
		{
			alert(alert_arr.PLS_SELECT_VALID_FILE+valid_extn)
			currObj.focus();
		 	return false;
		}
	}	
	return true
	
}

function validateUrl(name)
{
	var Url = getObj(name);
	var wProtocol;

	var oRegex = new Object();
	oRegex.UriProtocol = new RegExp('');
	oRegex.UriProtocol.compile( '^(((http|https|ftp|news):\/\/)|mailto:)', 'gi' );
	oRegex.UrlOnChangeProtocol = new RegExp('') ;
	oRegex.UrlOnChangeProtocol.compile( '^(http|https|ftp|news)://(?=.)', 'gi' );

	wUrl = Url.value;
	wProtocol=oRegex.UrlOnChangeProtocol.exec( wUrl ) ;
	if ( wProtocol )
	{
		wUrl = wUrl.substr( wProtocol[0].length );
		Url.value = wUrl;
	}
}

function LTrim( value )
{

        var re = /\s*((\S+\s*)*)/;
        return value.replace(re, "$1");

}
