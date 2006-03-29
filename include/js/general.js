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

       			alert(fldLabel+" cannot be empty")

			currObj.focus()

                	return false

		}

        	else
            	
		return true
	} else {
		if (currObj.value == "" ) {

	                alert(fldLabel+" cannot be none")

        	        return false

 	       } else return true

	}

}



function patternValidate(fldName,fldLabel,type) {
	var currObj=getObj(fldName)
	if (type.toUpperCase()=="EMAIL") //Email ID validation
		var re=new RegExp(/^.+@.+\..+$/)
	
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
		alert("Please enter a valid "+fldLabel)
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
							alert(fldLabel1+" should be less than "+fldLabel2)
							ret=false
						}
						break;
		case 'LE'	:	if (date1>date2) {//DATE1 VALUE LESS THAN OR EQUAL TO DATE2
							alert(fldLabel1+" should be less than or equal to "+fldLabel2)
							ret=false
						}
						break;
		case 'E'	:	if (date1!=date2) {//DATE1 VALUE EQUAL TO DATE
							alert(fldLabel1+" should be equal to "+fldLabel2)
							ret=false
						}
						break;
		case 'G'	:	if (date1<=date2) {//DATE1 VALUE GREATER THAN DATE2
							alert(fldLabel1+" should be greater than "+fldLabel2)
							ret=false
						}
						break;	
		case 'GE'	:	if (date1<date2) {//DATE1 VALUE GREATER THAN OR EQUAL TO DATE2
							alert(fldLabel1+" should be greater than or equal to "+fldLabel2)
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
		alert("Please enter a valid "+fldLabel)
		getObj(dateFldName).focus()
		return false
	}
	
	if ((mm==2) && (dd>29)) {//checking of no. of days in february month
		alert("Please enter a valid "+fldLabel)
		getObj(dateFldName).focus()
		return false
	}
	
	if ((mm==2) && (dd>28) && ((yyyy%4)!=0)) {//leap year checking
		alert("Please enter a valid "+fldLabel)
		getObj(dateFldName).focus()
		return false
	}

	switch (parseInt(mm)) {
		case 2 : 
		case 4 : 
		case 6 : 
		case 9 : 
		case 11 :	if (dd>30) {
						alert("Please enter a valid "+fldLabel)
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
		alert("Please enter a valid "+fldLabel)
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
		alert("Please enter a valid "+fldLabel)
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
               else if (splitval[0].length>format[0])
                   invalid=true
           }
                      if (splitval[1])
               if (splitval[1].length>format[1])
                   invalid=true
       }
              if (invalid==true) {
           alert("Invalid "+fldLabel)
           getObj(fldName).focus()
           return false
       } else return true
   } else {
	
	   var splitval=val.split(".")

                if(splitval[0]>2147483647)
                {
                        alert( fldLabel + " exceeds the maximum limit ");
                        return false;
                }


       if (neg==true)
           var re=/^(-|)\d+(\.\d\d*)*$/
       else
           var re=/^\d+(\.\d\d*)*$/
   }
      if (!re.test(val)) {
       alert("Invalid "+fldLabel)
       getObj(fldName).focus()
       return false
   } else return true
}


function intValidate(fldName,fldLabel) {
	var val=getObj(fldName).value.replace(/^\s+/g, '').replace(/\s+$/g, '')
	if (isNaN(val) || (val.indexOf(".")!=-1 && fldName != 'potential_amount')) 
	{
		alert("Invalid "+fldLabel)
		getObj(fldName).focus()
		return false
	} 
        else if( val < -2147483648 || val > 2147483647)
        {
                alert(fldLabel +" is out of range");
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
						alert(fldLabel+" should be less than "+constval)
						ret=false
					}
					break;
		case "LE" :	if (val>constval) {
					alert(fldLabel+" should be less than or equal to "+constval)
			        ret=false
					}
					break;
		case "E"  :	if (val!=constval) {
                                        alert(fldLabel+" should be equal to "+constval)
                                        ret=false
                                }
                                break;
		case "NE" : if (val==constval) {
						 alert(fldLabel+" should not be equal to "+constval)
							ret=false
					}
					break;
		case "G"  :	if (val<=constval) {
							alert(fldLabel+" should be greater than "+constval)
							ret=false
					}
					break;
		case "GE" : if (val<constval) {
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

function formValidate() {
	for (var i=0; i<fieldname.length; i++) {
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
						if (!emptyCheck(type[2],fieldlabel[i],getObj(type[2]).type))
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
						if (!patternValidate(fieldname[i],fieldlabel[i],etype))
							return false
					}
				}
					break;
		}
	}
       //added to check Start Date & Time,if Activity Status is Planned.//start
        for (var j=0; j<fieldname.length; j++)
        {
            if(fieldname[j] == "date_start")
            {
               var datelabel = fieldlabel[j]
               var datefield = fieldname[j]
               var startdatevalue = getObj(datefield).value.replace(/^\s+/g, '').replace(/\s+$/g, '')
            }
            if(fieldname[j] == "time_start")
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

               chktime.setYear(yyyy)
               chktime.setMonth(mm-1)
               chktime.setDate(dd)
               chktime.setHours(hourval)
               chktime.setMinutes(minval)
               //chkdate.setHours(hourval)
               //chkdate.setMinutes(minval)
                if (!compareDates(chkdate,datelabel,currdate,"Current date & time for Activities with status as Planned","GE")) {
                        getObj(datefield).focus()
                        return false
                }
                else if(!compareDates(chktime,timelabel,currdate,"Current Time for Activities with status as Planned","GE"))
                {
                        getObj(timefield).focus()
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

    var id = document.getElementById(divId);

    id.style.display = 'inline';

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

function fnLoadValues(obj1,obj2,SelTab,unSelTab){

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

    colone.innerHTML="<select name='Fields"+count+"' class='detailedViewTextBox'>"+option_values+"</select>";

    coltwo.innerHTML="<select name='Condition"+count+"' class='detailedViewTextBox'>"+criteria_values+"</select> ";

    colthree.innerHTML="<input type='text' name='Srch_value"+count+"' class='detailedViewTextBox'>";

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
    tagName.style.left= leftSide + 'px';
    tagName.style.top= topSide + 'px';
    tagName.style.visibility = 'visible';
}

function fninvsh(Lay){
    var tagName = document.getElementById(Lay);
    tagName.style.visibility = 'hidden';
}

function fnvshNrm(Lay){
    var tagName = document.getElementById(Lay);
    tagName.style.visibility = 'visible';
}


// Add & Delete Row in a Table

function fnAddRow(){
	rowCnt++;
	var tableName = document.getElementById('proTab');
	var prev = tableName.rows.length;
    var count = prev;
    var row = tableName.insertRow(prev);
	row.id = "row"+count;
	if(count%2)
		row.className = "dvtCellLabel";
	else
		row.className = "dvtCellInfo";
	var colone = row.insertCell(0);
	var coltwo = row.insertCell(1);
	var colthree = row.insertCell(2);
	var colfour = row.insertCell(3);
	var colfive = row.insertCell(4);
	var colsix = row.insertCell(5);
	var colseven = row.insertCell(6);
	colone.innerHTML="<input type='text' name='txtProduct"+count+"' class='detailedViewProdTextBox' readonly/>&nbsp;<img src='themes/blue/images/search.gif' onclick='productPickList(this)' align='absmiddle' /><input type='hidden' id='hdnProductId"+count+"' name='hdnProductId"+count+"'>";
	coltwo.innerHTML="<div id='qtyInStock"+count+"'>";	
	colthree.innerHTML="<input type='text' name='txtQty"+count+"' class='detailedViewTextBox' onfocus='this.className=\"detailedViewTextBoxOn\"' onBlur='this.className=\"detailedViewTextBox\"; settotalnoofrows(); calcTotal(this);' /> ";
	colfour.innerHTML="&nbsp;</div><div id='unitPrice"+count+"'></div>";
	colfive.innerHTML="<input type='text' name='txtListPrice"+count+"' class='detailedViewProdTextBox' readonly onBlur='settotalnoofrows(); calcTotal(this)'>&nbsp;<img src='themes/blue/images/pricebook.gif' onClick='priceBookPickList(this)' align='absmiddle' style='cursor:hand;cursor:pointer' title='Price Book' /> ";
	colsix.innerHTML="&nbsp;<div id='total"+count+"' align='right'></div><input type='hidden' id='hdnTotal"+count+"' name='hdnTotal"+count+"'>";
	colseven.innerHTML="<span class='delTxt' onclick=\"deleteRow(this.parentNode.parentNode.rowIndex)\">Del</span>";

}
function fnAddRowForPO(){
	rowCnt++;

	var tableName = document.getElementById('proTab');
	var prev = tableName.rows.length;
	var count = prev;
	var row = tableName.insertRow(prev);
	row.id = "row"+count;
	if(count%2)
		row.className = "dvtCellLabel";
	else
		row.className = "dvtCellInfo";
	var colone = row.insertCell(0);
	var coltwo = row.insertCell(1);
	var colthree = row.insertCell(2);
	var colfour = row.insertCell(3);
	var colfive = row.insertCell(4);
	var colsix = row.insertCell(5);
	colone.innerHTML="<input type='text' name='txtProduct"+count+"' class='detailedViewProdTextBox' readonly/>&nbsp;<img src='themes/blue/images/search.gif' onclick='productPickList(this)' align='absmiddle' /><input type='hidden' id='hdnProductId"+count+"' name='hdnProductId"+count+"'>";
	coltwo.innerHTML="<input type='text' name='txtQty"+count+"' class='detailedViewTextBox' onfocus='this.className=\"detailedViewTextBoxOn\"' onBlur='this.className=\"detailedViewTextBox\"; settotalnoofrows(); calcTotal(this);' /> ";
	colthree.innerHTML="&nbsp;<div id='unitPrice"+count+"'></div>";
	colfour.innerHTML="<input type='text' name='txtListPrice"+count+"' class='detailedViewProdTextBox' readonly onBlur='settotalnoofrows(); calcTotal(this)'>&nbsp;<img src='themes/blue/images/pricebook.gif' onClick='priceBookPickList(this)' align='absmiddle' style='cursor:hand;cursor:pointer' title='Price Book' /> ";
	colfive.innerHTML="&nbsp;<div id='total"+count+"' align='right'></div><input type='hidden' id='hdnTotal"+count+"' name='hdnTotal"+count+"'>";
	colsix.innerHTML="<span class='delTxt' onclick=\"deleteRow(this.parentNode.parentNode.rowIndex)\">Del</span>";

}
  
 
function deleteRow(i)
{
	rowCnt--;
	 document.getElementById('proTab').deleteRow(i);
}

function cancelForm(frm)
{
	    window.history.back();
}

function trim(s)
{
	while (s.substring(0,1) == " ")
	{
		s = s.substring(1, s.length);
	}
	while (s.substring(s.length-1, s.length) == ' ') {
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

