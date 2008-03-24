 /*********************************************************************************
 ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
 ********************************************************************************/
function trimfValues(value)
{
    var string_array;
    string_array = value.split(":");
    return string_array[4];
}

function updatefOptions(sel, opSelName) {
    var selObj = document.getElementById(opSelName);
    var fieldtype = null ;

    var currOption = selObj.options[selObj.selectedIndex];
    var currField = sel.options[sel.selectedIndex];
    
    var fld = currField.value.split(":");
    var tod = fld[4];
    if(fld[4] == 'D' || fld[4] == 'DT')
    {
	    $("and"+sel.id).innerHTML =  "";
	    if(sel.id != "fcol5")
		    $("and"+sel.id).innerHTML =  "<em old='(yyyy-mm-dd)'>("+$("user_dateformat").value+")</em>&nbsp;"+alert_arr.LBL_AND;
	    else
		    $("and"+sel.id).innerHTML =  "<em old='(yyyy-mm-dd)'>("+$("user_dateformat").value+")</em>&nbsp;";
    }
    else if(fld[4] == 'T' && fld[1] != 'time_start' && fld[1] != 'time_end')
    {
	    $("and"+sel.id).innerHTML =  "";
	    if(sel.id != "fcol5")
		    $("and"+sel.id).innerHTML =  "<em old='(yyyy-mm-dd)'>("+$("user_dateformat").value+" hh:mm:ss)</em>&nbsp;"+alert_arr.LBL_AND;
	    else
		    $("and"+sel.id).innerHTML =  "<em old='(yyyy-mm-dd)'>("+$("user_dateformat").value+" hh:mm:ss)</em>&nbsp;";
    }
    else if(fld[4] == 'C')
    {
	    $("and"+sel.id).innerHTML =  "";
	    if(sel.id != "fcol5")
		    $("and"+sel.id).innerHTML =  "( Yes / No )&nbsp;"+alert_arr.LBL_AND;
	    else
		    $("and"+sel.id).innerHTML =  "( Yes / No )&nbsp;";
    } 
    else {
	    $("and"+sel.id).innerHTML =  "";
	    if(sel.id != "fcol5")
		    $("and"+sel.id).innerHTML =  "&nbsp;"+alert_arr.LBL_AND;
	    else
		    $("and"+sel.id).innerHTML =  "&nbsp;";
    } 	

    if(currField.value != null && currField.value.length != 0)
    {
	fieldtype = trimfValues(currField.value);
	ops = typeofdata[fieldtype];
	var off = 0;
	if(ops != null)
	{

		var nMaxVal = selObj.length;
		for(nLoop = 0; nLoop < nMaxVal; nLoop++)
		{
			selObj.remove(0);
		}
		selObj.options[0] = new Option ('None', '');
		if (currField.value == '') {
			selObj.options[0].selected = true;
		}
		off = 1;
		for (var i = 0; i < ops.length; i++)
		{
			var label = fLabels[ops[i]];
			if (label == null) continue;
			var option = new Option (fLabels[ops[i]], ops[i]);
			selObj.options[i + off] = option;
			if (currOption != null && currOption.value == option.value)
			{
				option.selected = true;
			}
		}
	}
    }else
    {
	var nMaxVal = selObj.length;
	for(nLoop = 0; nLoop < nMaxVal; nLoop++)
	{
		selObj.remove(0);
	}
	selObj.options[0] = new Option ('None', '');
	if (currField.value == '') {
		selObj.options[0].selected = true;
	}
    }

}
function verify_data() {
	var isError = false;
	var errorMessage = "";
	if (trim(document.CustomView.viewName.value) == "") {
		isError = true;
		errorMessage += "\nView Name";
	}
	// Here we decide whether to submit the form.
	if (isError == true) {
		alert(alert_arr.MISSING_REQUIRED_FIELDS + errorMessage);
		return false;
	}
	//return true;
}


function CancelForm()
{
var cvmodule = document.templatecreate.cvmodule.value;
var viewid = document.templatecreate.cvid.value;
document.location.href = "index.php?module="+cvmodule+"&action=index&viewname="+viewid;
}


function check4null(form)
{
        var isError = false;
        var errorMessage = "";
        // Here we decide whether to submit the form.
        if (trim(form.subject.value) =='') {
                isError = true;
                errorMessage += "\n subject";
                form.subject.focus();
        }

        // Here we decide whether to submit the form.
        if (isError == true) {
                alert(alert_arr.MISSING_REQUIRED_FIELDS + errorMessage);
                return false;
        }
 return true;
}

// Added for Custom View Advance Filter validation
function checkval()
{
	var value,option,arr,dttime,sep;
	for(var i=1;i<=5;i++)
	{
		value=trim(getObj("fval"+i).value);
		option=getObj("fcol"+i).value;
		if(option !="" && value !="")
		{
			if(getObj("fop"+i).selectedIndex == 0)
				{
					alert(alert_arr.LBL_SELECT_CRITERIA);
		        	        return false;	
				}
			arr=option.split(":");
			if(arr[4] == "N" || arr[4] == "I" || arr[4] == "NN")
			{
				sep=value.split(",");
				for(var j=0;j<sep.length;j++)
				{
					if(isNaN(sep[j]))
					{
					alert(alert_arr.LBL_ENTER_VALID_NO);
					getObj("fval"+i).select();
					return false;
					}
				
	
				}
			}
			if(arr[4] == "D")
			{

				sep=value.split(",");
                                for(var j=0;j<sep.length;j++)
                                {
					if(!cv_dateValidate(trim(sep[j]),"Date","OTH"))
					{
						getObj("fval"+i).select();
						return false;
					}
				}
			}	
			if(arr[4] == "T")
			{

				sep=value.split(",");
				for(var j=0;j<sep.length;j++)
				{
					var dttime=sep[j].split(" ");
					if(!cv_dateValidate(dttime[0],"Date","OTH"))
					{
						getObj("fval"+i).select();
						return false;
					}


					if(!cv_patternValidate(dttime[1],"Time","TIMESECONDS"))
					{
						getObj("fval"+i).select();
						return false;
					}
				}

			}	
			if(arr[4] == "C")
			{
				sep=value.split(",");
                                for(var j=0;j<sep.length;j++)
                                {

					if(sep[j].toLowerCase() != "yes") if(sep[j].toLowerCase() != "no") 
					{
						alert(alert_arr.LBL_PROVIDE_YES_NO);
						getObj("fval"+i).select();
						return false;
					}
				}
			}	
		}	
	}
return true;
}

//Added for Custom view validation
//Copied from general.js and altered some lines. becos we cant send vales to function present in general.js. it accept only field names.
function cv_dateValidate(fldval,fldLabel,type) {
	if(cv_patternValidate(fldval,fldLabel,"DATE")==false)
		return false;
	dateval=fldval.replace(/^\s+/g, '').replace(/\s+$/g, '') 

	var dateelements=splitDateVal(dateval)
	
	dd=dateelements[0]
	mm=dateelements[1]
	yyyy=dateelements[2]
	
	if (dd<1 || dd>31 || mm<1 || mm>12 || yyyy<1 || yyyy<1000) {
		alert(alert_arr.ENTER_VALID+fldLabel)
		return false
	}
	
	if ((mm==2) && (dd>29)) {//checking of no. of days in february month
		alert(alert_arr.ENTER_VALID+fldLabel)
		return false
	}
	
	if ((mm==2) && (dd>28) && ((yyyy%4)!=0)) {//leap year checking
		alert(alert_arr.ENTER_VALID+fldLabel)
		return false
	}

	switch (parseInt(mm)) {
		case 2 : 
		case 4 : 
		case 6 : 
		case 9 : 
		case 11 :	if (dd>30) {
						alert(alert_arr.ENTER_VALID+fldLabel)
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
			return false
		} else return true;
	} else return true;
}

//Added for Custom view validation
//Copied from general.js and altered some lines. becos we cant send vales to function present in general.js. it accept only field names.
function cv_patternValidate(fldval,fldLabel,type) {
	if (type.toUpperCase()=="DATE") {//DATE validation 

		switch (userDateFormat) {
			case "yyyy-mm-dd" : 
								var re = /^\d{4}(-)\d{1,2}\1\d{1,2}$/
								break;
			case "mm-dd-yyyy" : 
			case "dd-mm-yyyy" : 
								var re = /^\d{1,2}(-)\d{1,2}\1\d{4}$/								
		}
	}
	

	if (type.toUpperCase()=="TIMESECONDS") {//TIME validation
		var re = new RegExp("^([0-1][0-9]|[2][0-3]):([0-5][0-9]):([0-5][0-9])$");
	}
	if (!re.test(fldval)) {
		alert(alert_arr.ENTER_VALID + fldLabel)
		return false
	}
	else return true
}



