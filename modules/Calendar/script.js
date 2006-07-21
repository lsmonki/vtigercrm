/*********************************************************************************

** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/

function DisableSharing()
{

        x = document.SharedList.selected_id.length;
        idstring = "";
        xx = 0;
        if ( x == undefined)
        {

                if (document.SharedList.selected_id.checked)
                {
                        document.SharedList.idlist.value=document.SharedList.selected_id.value;
                }
                else
                {
                        alert("Please select at least one user");
                        return false;
                }
        }
        else
        {
                for(i = 0; i < x ; i++)
                {
                        if(document.SharedList.selected_id[i].checked)
                        {
                                idstring = document.SharedList.selected_id[i].value +";"+idstring
                        xx++
                        }
                }
                if (xx != 0)
                {
                        document.SharedList.idlist.value=idstring;
                }
                else
                {
                        alert("Please select at least one user");
                        return false;
                }
        }
        if(confirm("Are you sure you want to disable sharing for selected "+xx+" user(s) ?"))
        {
                document.SharedList.action="index.php?module=Calendar&action=disable_sharing&return_module=Calendar&return_action=calendar_share";
        }
        else
        {
                return false;
        }
}



function showhide(argg)
{
	var x=document.getElementById(argg).style;
	if (x.display=="none") 
	{
		x.display="block"
	
	}
	else {
			x.display="none"
		  }
}


function showhideRepeat(argg1,argg2)
{
	var x=document.getElementById(argg2).style;
	var y=document.getElementById(argg1).checked;
	
	if (y)
	{
		x.display="block";
	}
	else {
		x.display="none";
	}
	
}



function gshow(argg1,type,startdate,enddate,starthr,startmin,startfmt,endhr,endmin,endfmt)
{
	var y=document.getElementById(argg1).style;
	
	if (y.display=="none") 
	{
		if(type == 'call' || type == 'meeting')
		{
			if(type == 'call')
	                        document.appSave.activitytype[0].checked = true;
	                if(type == 'meeting')
        	                document.appSave.activitytype[1].checked = true;

			document.appSave.date_start.value = startdate;
			document.appSave.due_date.value = enddate;
			document.appSave.starthr.value = starthr;
			document.appSave.startmin.value = startmin;
			document.appSave.startfmt.value = startfmt;
			document.appSave.endhr.value = endhr;
			document.appSave.endmin.value = endmin;
			document.appSave.endfmt.value = endfmt;
		}
		if(type == 'todo')
		{
			document.createTodo.task_date_start.value = startdate;
			document.createTodo.starthr.value = starthr;
                        document.createTodo.startmin.value = startmin;
                        document.createTodo.startfmt.value = startfmt;
		}
		y.display="block";
	}
}

function ghide(argg2)
{
	var z=document.getElementById(argg2).style;
	if (z.display=="block" ) 
	{
		z.display="none"
	
	}
}

 function moveMe(arg1) {
	var posx = 0;
	var posy = 0;
	var e=document.getElementById(arg1);
	
	if (!e) var e = window.event;
	
	if (e.pageX || e.pageY)
	{
		posx = e.pageX;
		posy = e.pageY;
	}
	else if (e.clientX || e.clientY)
	{
		posx = e.clientX + document.body.scrollLeft;
		posy = e.clientY + document.body.scrollTop;
	}
 }

function switchClass(myModule,toStatus) {
	var x=document.getElementById(myModule);
	if (toStatus=="on") {
		x.className="dvtSelectedCell";
		}
	if (toStatus=="off") {
		x.className="dvtUnSelectedCell";
		}
		
}

function enableCalstarttime()
{
	if(document.SharingForm.sttime_check.checked == true)
		document.SharingForm.start_hour.disabled = false;
	else	
		document.SharingForm.start_hour.disabled = true;
}

function check_form()
{
	formSelectColumnString('inviteesid');
        if(document.appSave.subject.value == "")
        {
                alert("Missing Event Name");
                document.appSave.subject.focus()
                return false;
        }
        else
        {
		if(document.appSave.remindercheck.checked == true)
			document.appSave.set_reminder.value = 'Yes';
		else
			document.appSave.set_reminder.value = 'No';
		if(document.appSave.recurringcheck.checked == false)
		{
			document.appSave.recurringtype.value = '--None--';
		}
		else
		{
			document.appSave.recurringtype.value = document.appSave.repeat_option.value;
		}
		starthour = document.appSave.starthr.value;
		startmin  = document.appSave.startmin.value;
		startformat = document.appSave.startfmt.value;
		endhour = document.appSave.endhr.value;
                endmin  = document.appSave.endmin.value;
                endformat = document.appSave.endfmt.value;
		if(startformat != '')
		{
			if(startformat == 'pm')
			{
				if(starthour == '12')
					starthour = 12;
				else
					starthour = eval(starthour) + 12;
				startmin  = startmin;
			}
			else
			{
				starthour = starthour;
				startmin  = startmin;
			}
		}
		if(endformat != '')
		{
			if(endformat == 'pm')
                        {
				if(endhour == '12')
                                        endhour = 12;
                                else
                                        endhour = eval(endhour) + 12;
				endmin = endmin;
                        }
			else
			{
				endhour = endhour;
				endmin = endmin;
			}
		}
		if(dateComparison('due_date','End date','date_start','Start date','GE'))
		{
			if((eval(endhour)*60+eval(endmin)) < (eval(starthour)*60+eval(startmin)))
			{
				alert("End Time should be greater than Start Time ");
	                	document.appSave.endhr.focus();
	        	        return false;
			}
		}	
		else
			return false;
		durationinmin = (eval(endhour)*60+eval(endmin)) - (eval(starthour)*60+eval(startmin));
		if(durationinmin >= 60)
		{
			hour = durationinmin/60;
			minute = durationinmin%60;
		}
		else
		{
			hour = 0;
			minute = durationinmin;
		}
		document.appSave.duration_hours.value = hour;
		document.appSave.duration_minutes.value = minute;
		document.appSave.time_start.value = starthour+':'+startmin;
                return true;
        }
}

function task_check_form()
{
	starthour = document.createTodo.starthr.value;
	startmin  = document.createTodo.startmin.value;
        startformat = document.createTodo.startfmt.value;
	if(startformat != '')
	{
        	if(startformat == 'pm')
                {
                	starthour = eval(starthour) + 12;
                        startmin  = startmin;
                }
                else
                {
                	starthour = starthour;
                        startmin  = startmin;
                }
        }
	document.createTodo.task_time_start.value = starthour+':'+startmin;
}


var moveupLinkObj,moveupDisabledObj,movedownLinkObj,movedownDisabledObj;
function setObjects()
{
        availListObj=getObj("availableusers")
        selectedColumnsObj=getObj("selectedusers")

}



function addColumn()
{
        var selectlength=selectedColumnsObj.length
        var availlength=availListObj.length
        var s=0
        for (i=0;i<selectlength;i++)
        {
                selectedColumnsObj.options[i].selected=false
        }
        for (i=0;i<availlength;i++)
        {
                if (availListObj.options[s].selected==true)
                {
                        for (j=0;j<selectlength;j++)
                        {
                                if (selectedColumnsObj.options[j].value==availListObj.options[s].value)
                                {
                                        var rowFound=true
                                        var existingObj=selectedColumnsObj.options[j]
                                        breaK;
                                }
                        }
                        if (rowFound!=true)
                        {
                                var newColObj=document.createElement("OPTION")
                                        newColObj.value=availListObj.options[s].value
                                        if (browser_ie) newColObj.innerText=availListObj.options[s].innerText
                                        else if (browser_nn4 || browser_nn6) newColObj.text=availListObj.options[s].text
                                                selectedColumnsObj.appendChild(newColObj)
                                        availListObj.removeChild(availListObj.options[s])
                                        newColObj.selected=true
                                        rowFound=false
                        }
                        else
                        {
                                existingObj.selected=true
                        }
                }
		else
                        s++
        }
}

function delColumn()
{
        var selectlength=selectedColumnsObj.length
        var availlength=availListObj.length
        var s=0
        for (i=0;i<availlength;i++)
        {
                availListObj.options[i].selected=false
        }
        for (i=0;i<selectlength;i++)
        {
                if (selectedColumnsObj.options[s].selected==true)
                {
                        for (j=0;j<availlength;j++)
                        {
                                if (availListObj.options[j].value==selectedColumnsObj.options[s].value)
                                {
                                        var rowFound=true
                                        var existingObj=availListObj.options[j]
                                        breaK;
                                }
                        }

                        if (rowFound!=true)
                        {
                                var newColObj=document.createElement("OPTION")
                                        newColObj.value=selectedColumnsObj.options[s].value
                                        if (browser_ie) newColObj.innerText=selectedColumnsObj.options[s].innerText
                                        else if (browser_nn4 || browser_nn6) newColObj.text=selectedColumnsObj.options[s].text
                                                availListObj.appendChild(newColObj)
                                        selectedColumnsObj.removeChild(selectedColumnsObj.options[s])
                                        newColObj.selected=true
                                        rowFound=false
                        }
                        else
                        {
                                existingObj.selected=true
                        }
                }
		else
                        s++
        }
}

function formSelectColumnString(usr)
{
	usr_id = document.getElementById(usr);
	var selectedColStr = "";
        for (i=0;i<selectedColumnsObj.options.length;i++)
        {
        	selectedColStr += selectedColumnsObj.options[i].value + ";";
        }
	usr_id.value = selectedColStr;
}

function fnRedirect() {
        var OptionData = $('viewBox').options[$('viewBox').selectedIndex].value;
	if(OptionData == 'listview')
	{
		document.EventViewOption.action.value = "index";
		window.document.EventViewOption.submit();
	}
	if(OptionData == 'hourview')
	{
		document.EventViewOption.action.value = "index";
		window.document.EventViewOption.submit();
	}
}

function fnAddEvent(obj,CurrObj,start_date,end_date,start_hr,start_min,start_fmt,end_hr,end_min,end_fmt){
	var tagName = document.getElementById(CurrObj);
	var left_Side = findPosX(obj);
	var top_Side = findPosY(obj);
	tagName.style.left= left_Side  + 'px';
	tagName.style.top= top_Side + 22+ 'px';
	tagName.style.display = 'block';
	document.getElementById("addcall").href="javascript:gshow('addEvent','call','"+start_date+"','"+end_date+"','"+start_hr+"','"+start_min+"','"+start_fmt+"','"+end_hr+"','"+end_min+"','"+end_fmt+"');fnRemoveEvent();";
	document.getElementById("addmeeting").href="javascript:gshow('addEvent','meeting','"+start_date+"','"+end_date+"','"+start_hr+"','"+start_min+"','"+start_fmt+"','"+end_hr+"','"+end_min+"','"+end_fmt+"');fnRemoveEvent();";
	document.getElementById("addtodo").href="javascript:gshow('createTodo','todo','"+start_date+"','"+end_date+"','"+start_hr+"','"+start_min+"','"+start_fmt+"','"+end_hr+"','"+end_min+"','"+end_fmt+"');fnRemoveEvent();";
	
}
	
function fnRemoveEvent(){
	var tagName = document.getElementById('addEventDropDown').style.display= 'none';
}

function fnShowEvent(){
		var tagName = document.getElementById('addEventDropDown').style.display= 'block';
}

function getMiniCal(){
        new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: 'module=Calendar&action=CalendarAjax&type=minical&parenttab=My Home Page&ajax=true',
                        onComplete: function(response) {
                                $("miniCal").innerHTML=response.responseText;
                        }
                }

          );
}

function getCalSettings(){
        new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: 'module=Calendar&action=CalendarAjax&type=settings&parenttab=My Home Page&ajax=true',
                        onComplete: function(response) {
                                $("calSettings").innerHTML=response.responseText;
                        }
                }

          );
}

function updateStatus(record,status,view,hour,day,month,year,type){
	if(type == 'event')
	{
		var OptionData = $('viewBox').options[$('viewBox').selectedIndex].value;
		
		new Ajax.Request(
                	'index.php',
                	{queue: {position: 'end', scope: 'command'},
                        	method: 'post',
                        	postBody: 'module=Calendar&action=CalendarAjax&record='+record+'&'+status+'&view='+view+'&hour='+hour+'&day='+day+'&month='+month+'&year='+year+'&type=change_status&viewOption='+OptionData+'&subtab=event&ajax=true',
                        	onComplete: function(response) {
					if(OptionData == 'listview')
						$("listView").innerHTML=response.responseText;
                                	if(OptionData == 'hourview')
                        			$("hrView").innerHTML=response.responseText;
                        	}
                	}
		);
	}
	if(type == 'todo')
        {
		new Ajax.Request(
                        'index.php',
			{queue: {position: 'end', scope: 'command'},
                                method: 'post',
				postBody: 'module=Calendar&action=CalendarAjax&record='+record+'&'+status+'&view='+view+'&hour='+hour+'&day='+day+'&month='+month+'&year='+year+'&type=change_status&subtab=todo&ajax=true',
                                onComplete: function(response) {
                                        $("mnuTab2").innerHTML=response.responseText;
                                }
                        }
                )
	}
}

function getcalAction(obj,Lay,id,view,hour,day,month,year,type){
    var tagName = document.getElementById(Lay);
    var leftSide = findPosX(obj);
    var topSide = findPosY(obj);
    var maxW = tagName.style.width;
    var widthM = maxW.substring(0,maxW.length-2);
    var getVal = eval(leftSide) + eval(widthM);
    if(getVal  > window.innerWidth ){
        leftSide = eval(leftSide) - eval(widthM);
        tagName.style.left = leftSide + 'px';
    }
    else
        tagName.style.left= leftSide + 'px';
    tagName.style.top= topSide + 'px';
    tagName.style.display = 'block';
    tagName.style.visibility = "visible";
    if(type == 'event')
    {
	var heldstatus = "eventstatus=Held";
	var notheldstatus = "eventstatus=Not Held";
        var activity_mode = "Events";
	var complete = document.getElementById("complete");
	var pending = document.getElementById("pending");
	var postpone = document.getElementById("postpone");
	var actdelete =	document.getElementById("actdelete");
	var changeowner = document.getElementById("changeowner");
	
    }
    if(type == 'todo')
    {
	var heldstatus = "status=Completed";
        var notheldstatus = "status=Deferred";
	var activity_mode = "Task";
	var complete = document.getElementById("taskcomplete");
        var pending = document.getElementById("taskpending");
        var postpone = document.getElementById("taskpostpone");
        var actdelete = document.getElementById("taskactdelete");
        var changeowner = document.getElementById("taskchangeowner");
    }
    document.getElementById("idlist").value = id;
    document.change_owner.hour.value = hour;
    document.change_owner.day.value = day;
    document.change_owner.view.value = view;
    document.change_owner.month.value = month;
    document.change_owner.year.value = year;
    document.change_owner.subtab.value = type;
    complete.href="javascript:updateStatus("+id+",'"+heldstatus+"','"+view+"',"+hour+","+day+","+month+","+year+",'"+type+"')";
    pending.href="javascript:updateStatus("+id+",'"+notheldstatus+"','"+view+"',"+hour+","+day+","+month+","+year+",'"+type+"')";
    postpone.href="index.php?action=EditView&module=Activities&record="+id+"&activity_mode="+activity_mode;
    actdelete.href="javascript:delActivity("+id+",'"+view+"',"+hour+","+day+","+month+","+year+",'"+type+"')";
    changeowner.href="javascript:dispLayer('act_changeowner');";

}

function dispLayer(lay)
{
	var tagName = document.getElementById(lay);
        tagName.style.visibility = 'visible';
        tagName.style.display = 'block';
}

function calendarChangeOwner()
{
	var user_id = document.getElementById('activity_owner').options[document.getElementById('activity_owner').options.selectedIndex].value;
	var idlist = document.change_owner.idlist.value;
        var view   = document.change_owner.view.value;
        var day    = document.change_owner.day.value;
        var month  = document.change_owner.month.value;
        var year   = document.change_owner.year.value;
        var hour   = document.change_owner.hour.value;
	var subtab = document.change_owner.subtab.value;
	if(subtab == 'event')
	{
		var OptionData = $('viewBox').options[$('viewBox').selectedIndex].value;
	 	new Ajax.Request(
                	'index.php',
                	{queue: {position: 'end', scope: 'command'},
                        	method: 'post',
                        	postBody: 'module=Users&action=updateLeadDBStatus&return_module=Calendar&return_action=CalendarAjax&user_id='+user_id+'&idlist='+idlist+'&view='+view+'&hour='+hour+'&day='+day+'&month='+month+'&year='+year+'&type=change_owner&viewOption='+OptionData+'&subtab=event&ajax=true',
                        	onComplete: function(response) {
					if(OptionData == 'listview')
						 $("listView").innerHTML=response.responseText;
					if(OptionData == 'hourview')
                                        	$("hrView").innerHTML=response.responseText;
                        	}
                	}
		);
	}
	if(subtab == 'todo')
        {
                new Ajax.Request(
                        'index.php',
                        {queue: {position: 'end', scope: 'command'},
                                method: 'post',
                                postBody: 'module=Users&action=updateLeadDBStatus&return_module=Calendar&return_action=CalendarAjax&user_id='+user_id+'&idlist='+idlist+'&view='+view+'&hour='+hour+'&day='+day+'&month='+month+'&year='+year+'&type=change_owner&subtab=todo&ajax=true',
                                onComplete: function(response) {
                                        $("mnuTab2").innerHTML=response.responseText;
                                }
                        }
                );
        }

}

function delActivity(id,view,hour,day,month,year,subtab)
{
	if(subtab == 'event')
	{
		var OptionData = $('viewBox').options[$('viewBox').selectedIndex].value;
         	new Ajax.Request(
                	'index.php',
                	{queue: {position: 'end', scope: 'command'},
                        	method: 'post',
                        	postBody: 'module=Users&action=massdelete&return_module=Calendar&return_action=CalendarAjax&idlist='+id+'&view='+view+'&hour='+hour+'&day='+day+'&month='+month+'&year='+year+'&type=activity_delete&viewOption='+OptionData+'&subtab=event&ajax=true',
                        	onComplete: function(response) {
					if(OptionData == 'listview')
                                        	$("listView").innerHTML=response.responseText;
                                	if(OptionData == 'hourview')
                                        	$("hrView").innerHTML=response.responseText;
                        	}
                	}
		);
	}
	if(subtab == 'todo')
        {
                new Ajax.Request(
                        'index.php',
                        {queue: {position: 'end', scope: 'command'},
                                method: 'post',
                                postBody: 'module=Users&action=massdelete&return_module=Calendar&return_action=CalendarAjax&idlist='+id+'&view='+view+'&hour='+hour+'&day='+day+'&month='+month+'&year='+year+'&type=activity_delete&subtab=todo&ajax=true',
                                onComplete: function(response) {
                                        $("mnuTab2").innerHTML=response.responseText;
                                }
                        }
                );
        }

}


