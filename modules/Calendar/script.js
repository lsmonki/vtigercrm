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



function gshow(argg1,type,startdate,enddate,starthr,startmin,startfmt,endhr,endmin,endfmt,viewOption,subtab)
{
	var y=document.getElementById(argg1).style;
	
	if (y.display=="none") 
	{
		if(type == 'call' || type == 'meeting')
		{
			if(type == 'call')
	                        document.EditView.activitytype[0].checked = true;
	                if(type == 'meeting')
        	                document.EditView.activitytype[1].checked = true;

			enableFields('Events');
			document.EditView.subject.value = '';
			document.EditView.date_start.value = startdate;
			document.EditView.due_date.value = enddate;
			document.EditView.starthr.value = starthr;
			document.EditView.startmin.value = startmin;
			document.EditView.startfmt.value = startfmt;
			document.EditView.endhr.value = endhr;
			document.EditView.endmin.value = endmin;
			document.EditView.endfmt.value = endfmt;
			document.EditView.viewOption.value = viewOption;
                        document.EditView.subtab.value = subtab;
		}
		if(type == 'todo')
		{
			enableFields('Task');
			document.createTodo.task_subject.value = '';
			document.createTodo.task_date_start.value = startdate;
			document.createTodo.starthr.value = starthr;
                        document.createTodo.startmin.value = startmin;
                        document.createTodo.startfmt.value = startfmt;
			document.createTodo.viewOption.value = viewOption;
                        document.createTodo.subtab.value = subtab;
		}
		y.display="block";
	}
	else
	{
		 document.EditView.date_start.value = startdate;
                 document.EditView.due_date.value = enddate;
                 document.EditView.starthr.value = starthr;
                 document.EditView.startmin.value = startmin;
                 document.EditView.startfmt.value = startfmt;
                 document.EditView.endhr.value = endhr;
                 document.EditView.endmin.value = endmin;
                 document.EditView.endfmt.value = endfmt;
		 document.EditView.viewOption.value = viewOption;
                 document.EditView.subtab.value = subtab;
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
        if(document.EditView.subject.value == "")
        {
                alert("Missing Event Name");
                document.EditView.subject.focus()
                return false;
        }
        else
        {
		if(document.EditView.remindercheck.checked == true)
			document.EditView.set_reminder.value = 'Yes';
		else
			document.EditView.set_reminder.value = 'No';
		if(document.EditView.recurringcheck.checked == false)
		{
			document.EditView.recurringtype.value = '--None--';
		}
		if(document.EditView.record.value != '')
                {
                        document.EditView.mode.value = 'edit';
                }
		else
		{
			document.EditView.mode.value = 'create';
		}
		starthour = document.EditView.starthr.value;
		startmin  = document.EditView.startmin.value;
		startformat = document.EditView.startfmt.value;
		endhour = document.EditView.endhr.value;
                endmin  = document.EditView.endmin.value;
                endformat = document.EditView.endfmt.value;
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
	                	document.EditView.endhr.focus();
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
		document.EditView.duration_hours.value = hour;
		document.EditView.duration_minutes.value = minute;
		document.EditView.time_start.value = starthour+':'+startmin;
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
	if(document.createTodo.record.value != '')
        {
        	document.createTodo.mode.value = 'edit';
        }
        else
        {
        	document.createTodo.mode.value = 'create';
        }

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
        var OptionData = $('viewOption').options[$('viewOption').selectedIndex].value;
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

function fnAddEvent(obj,CurrObj,start_date,end_date,start_hr,start_min,start_fmt,end_hr,end_min,end_fmt,viewOption,subtab){
	var tagName = document.getElementById(CurrObj);
	var left_Side = findPosX(obj);
	var top_Side = findPosY(obj);
	tagName.style.left= left_Side  + 'px';
	tagName.style.top= top_Side + 22+ 'px';
	tagName.style.display = 'block';
	document.getElementById("addcall").href="javascript:gshow('addEvent','call','"+start_date+"','"+end_date+"','"+start_hr+"','"+start_min+"','"+start_fmt+"','"+end_hr+"','"+end_min+"','"+end_fmt+"','"+viewOption+"','"+subtab+"');fnRemoveEvent();";
	document.getElementById("addmeeting").href="javascript:gshow('addEvent','meeting','"+start_date+"','"+end_date+"','"+start_hr+"','"+start_min+"','"+start_fmt+"','"+end_hr+"','"+end_min+"','"+end_fmt+"','"+viewOption+"','"+subtab+"');fnRemoveEvent();";
	document.getElementById("addtodo").href="javascript:gshow('createTodo','todo','"+start_date+"','"+end_date+"','"+start_hr+"','"+start_min+"','"+start_fmt+"','"+end_hr+"','"+end_min+"','"+end_fmt+"','"+viewOption+"','"+subtab+"');fnRemoveEvent();";
	
}
	
function fnRemoveEvent(){
	var tagName = document.getElementById('addEventDropDown').style.display= 'none';
}

function fnShowEvent(){
		var tagName = document.getElementById('addEventDropDown').style.display= 'block';
}

function getMiniCal(url){
	if(url == undefined)
		url = 'module=Calendar&action=CalendarAjax&type=minical&ajax=true';
	else
		 url = 'module=Calendar&action=CalendarAjax&'+url+'&type=minical&ajax=true';
        new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: url,
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
                        postBody: 'module=Calendar&action=CalendarAjax&type=settings&ajax=true',
                        onComplete: function(response) {
                                $("calSettings").innerHTML=response.responseText;
                        }
                }

          );
}

function updateStatus(record,status,view,hour,day,month,year,type){
	if(type == 'event')
	{
		var OptionData = $('viewOption').options[$('viewOption').selectedIndex].value;
		
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
	var OptionData = document.getElementById('viewOption').options[document.getElementById('viewOption').selectedIndex].value;
	
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
	var OptionData = '';
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
    postpone.href="javascript:getValidationarr("+id+",'"+activity_mode+"','edit_view','"+type+"','"+OptionData+"');";
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
		var OptionData = $('viewOption').options[$('viewOption').selectedIndex].value;
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
		var OptionData = $('viewOption').options[$('viewOption').selectedIndex].value;
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


/*
* javascript function to display the div tag
* @param divId :: div tag ID
*/
function cal_show(divId)

{

    var id = document.getElementById(divId);

    id.style.visibility = 'visible';

}

function fnAssignTo(){
		var option_Box = document.getElementById('parent_type');
		var option_select = option_Box.options[option_Box.selectedIndex].value;
		if(option_select == "Leads")
		{
			document.getElementById('leadLay').style.visibility = 'visible';
		}
		else if(option_select == "Accounts")
		{
			document.getElementById('leadLay').style.visibility = 'visible';
		}
		else if(option_select == "Potentials")
		{
			document.getElementById('leadLay').style.visibility = 'visible';
		}
		else{
			document.getElementById('leadLay').style.visibility = 'hidden';
		}
	}
	
function fnShowPopup(){
	document.getElementById('popupLay').style.display = 'block';
}
	
function fnHidePopup(){
	document.getElementById('popupLay').style.display = 'none';
}

function getValidationarr(id,activity_mode,opmode,subtab,viewOption)
{
	 new Ajax.Request(
                        'index.php',
                        {queue: {position: 'end', scope: 'command'},
                                method: 'post',
                                postBody: 'module=Calendar&action=CalendarAjax&record='+id+'&activity_mode='+activity_mode+'&ajax=true&type=view&file=DetailView',
                                onComplete: function(response) {
                                        $("dataArray").innerHTML=response.responseText;
					setFieldvalues(opmode,subtab,viewOption);
                                }
                        }
                );

}

function setFieldvalues(opmode,subtab,viewOption)
{
	var st = document.getElementById('activity_cont');
	eval(st.innerHTML);
	if(activity_type == 'Events')
	{
		if(opmode == 'detail_view')
                {
			enableFields(activity_type);
                        disableFields(activity_type);
                }
                else
                {
                        enableFields(activity_type);
                }
		document.EditView.viewOption.value = viewOption;
                document.EditView.subtab.value = subtab;
		for(x=0;x<key.length;x++)
		{	
			if(document.EditView[key[x]] != undefined)
			{

				if(key[x] == 'visibility' && data[x] == 'Public')
					document.EditView.visibility.checked = true;
				if(key[x] == 'visibility' && data[x] == 'Private')
					document.EditView.visibility.checked = false;
				if(key[x] == 'activitytype' && data[x] == 'Call')
				{
					document.EditView.activitytype[0].checked = true;
				}
				else
				{
					document.EditView.activitytype[1].checked = true;
				}
				if(key[x] == 'set_reminder' && data[x] == 'Yes')
				{
					document.EditView.remindercheck.checked = true;
					document.getElementById('reminderOptions').style.display = 'block';
				}
				if(key[x] == 'recurringcheck' && data[x] == 'on')
				{
					document.EditView.recurringcheck.checked = true;
					document.getElementById('repeatOptions').style.display = 'block';
				}
				if(key[x] == 'recurringtype')
				{	
					if(data[x] == 'Weekly')
						document.getElementById('repeatWeekUI').style.display = 'block';
					else
						document.getElementById('repeatWeekUI').style.display = 'none';
					if(data[x] == 'Monthly')
						document.getElementById('repeatMonthUI').style.display = 'block';
					else
						document.getElementById('repeatMonthUI').style.display = 'none';
				}
				if(key[x] == 'parent_name')
				{
					if(data[x] != '')
						document.getElementById('leadLay').style.visibility = 'visible';
					else
						document.getElementById('leadLay').style.display = 'hidden';
				}
				document.EditView[key[x]].value = data[x];
			//}	
			}
		}
		document.getElementById('addEvent').style.display = 'block';
	}
	else
	{
		document.createTodo.viewOption.value = viewOption;
                document.createTodo.subtab.value = subtab;
		for(x=0;x<key.length;x++)
                {
			if(document.createTodo[key[x]] != undefined)
			{
                                document.createTodo[key[x]].value = data[x];
			}
		}
		if(opmode == 'detail_view')
		{
			disableFields(activity_type);
		}
		else
		{
			enableFields(activity_type);
		}
		document.getElementById('createTodo').style.display = 'block';
	}
}

function disableFields(type)
{	
	if(type == 'Events')
	{
		document.EditView.activitytype[0].disabled = true;
                document.EditView.activitytype[1].disabled = true;
		document.EditView.subject.readOnly = true;
		document.EditView.visibility.disabled = true;
		document.EditView.date_start.readOnly = true;
                document.EditView.due_date.readOnly = true;
		document.EditView.starthr.disabled = true;
                document.EditView.startmin.disabled = true;
                document.EditView.startfmt.disabled = true;
                document.EditView.endhr.disabled = true;
                document.EditView.endmin.disabled = true;
                document.EditView.endfmt.disabled = true;
		document.EditView.taskpriority.disabled = true;
		document.EditView.availableusers.disabled = true;
                document.EditView.selectedusers.disabled = true;
		document.EditView.remindercheck.disabled = true;
		document.EditView.remdays.disabled = true;
                document.EditView.remhrs.disabled = true;
                document.EditView.remmin.disabled = true;
		document.EditView.toemail.readOnly = true;
		document.EditView.recurringcheck.disabled = true;
		document.EditView.repeat_frequency.readOnly = true;
                document.EditView.recurringtype.disabled = true;
                document.EditView.sun_flag.disabled = true;
                document.EditView.mon_flag.disabled = true;
                document.EditView.tue_flag.disabled = true;
                document.EditView.wed_flag.disabled = true;
                document.EditView.thu_flag.disabled = true;
                document.EditView.fri_flag.disabled = true;
                document.EditView.sat_flag.disabled = true;
		document.EditView.repeatMonth[0].disabled = true;
                document.EditView.repeatMonth[1].disabled = true;
                document.EditView.repeatMonth_date.readOnly = true;
                document.EditView.repeatMonth_daytype.disabled = true;
                document.EditView.repeatMonth_day.disabled = true;
		document.EditView.parent_type.disabled = true;
		document.EditView.selectcnt.style.display = "none";
		document.EditView.selectparent.style.display = "none";

                document.EditView.eventsave.disabled = true;
                document.EditView.eventcancel.disabled = true;
	}
	else
	{
		document.createTodo.task_subject.readOnly = true;
                document.createTodo.task_date_start.readOnly = true;
                document.createTodo.starthr.disabled = true;
                document.createTodo.startmin.disabled = true;
                document.createTodo.startfmt.disabled = true;
		document.createTodo.taskpriority.disabled = true;
                document.createTodo.todosave.disabled = true;
                document.createTodo.todocancel.disabled = true;
		document.createTodo.task_toemail.readOnly = true;
	}
}

function enableFields(type)
{
        if(type == 'Events')
        {
		/*Enabling fields
		*/
		document.EditView.activitytype[0].disabled = false;
                document.EditView.activitytype[1].disabled = false;
                document.EditView.subject.readOnly = false;
		document.EditView.visibility.disabled = false;
                document.EditView.date_start.readOnly = false;
                document.EditView.due_date.readOnly = false;
                document.EditView.starthr.disabled = false;
                document.EditView.startmin.disabled = false;
                document.EditView.startfmt.disabled = false;
                document.EditView.endhr.disabled = false;
                document.EditView.endmin.disabled = false;
                document.EditView.endfmt.disabled = false;
		document.EditView.taskpriority.disabled = false;
		document.EditView.availableusers.disabled = false;
                document.EditView.selectedusers.disabled = false;
		document.EditView.remindercheck.disabled = false;
		document.EditView.remdays.disabled = false;
                document.EditView.remhrs.disabled = false;
                document.EditView.remmin.disabled = false;
		document.EditView.toemail.readOnly = false;
		document.EditView.recurringcheck.disabled = false;
		document.EditView.repeat_frequency.readOnly = false;
		document.EditView.recurringtype.disabled = false;
		document.EditView.sun_flag.disabled = false;
                document.EditView.mon_flag.disabled = false;
                document.EditView.tue_flag.disabled = false;
                document.EditView.wed_flag.disabled = false;
                document.EditView.thu_flag.disabled = false;
                document.EditView.fri_flag.disabled = false;
                document.EditView.sat_flag.disabled = false;
		document.EditView.repeatMonth[0].disabled = false;
                document.EditView.repeatMonth[1].disabled = false;
                document.EditView.repeatMonth_date.readOnly = false;
                document.EditView.repeatMonth_daytype.disabled = false;
                document.EditView.repeatMonth_day.disabled = false;
		document.EditView.parent_type.disabled = false;
		document.EditView.selectcnt.style.display = "block";
                document.EditView.selectparent.style.display = "block";

		document.EditView.eventsave.disabled = false;
                document.EditView.eventcancel.disabled = false;
		/*Setting to default value
		*/
		document.EditView.subject.value = '';
		document.EditView.visibility.checked = false;
		document.EditView.date_start.value = '';
                document.EditView.due_date.value = '';
                document.EditView.starthr.value = '';
                document.EditView.startmin.value = '';
                document.EditView.startfmt.value = '';
                document.EditView.endhr.value = '';
                document.EditView.endmin.value = '';
                document.EditView.endfmt.value = '';
		document.EditView.taskpriority.value = 'High';
		document.EditView.selectedusers.value = '';
		document.EditView.remindercheck.checked = false;
		document.getElementById('reminderOptions').style.display = 'none';
		document.EditView.remdays.value = 0;
                document.EditView.remhrs.value = 0;
                document.EditView.remmin.value = 1;
		document.EditView.recurringcheck.checked = false;
		document.getElementById('repeatOptions').style.display = 'none';
		document.EditView.repeat_frequency.value = '';
                document.EditView.recurringtype.value = 'Daily';
		document.getElementById('repeatWeekUI').style.display = 'none';
		document.EditView.sun_flag.checked = false;
                document.EditView.mon_flag.checked = false;
                document.EditView.tue_flag.checked = false;
                document.EditView.wed_flag.checked = false;
                document.EditView.thu_flag.checked = false;
                document.EditView.fri_flag.checked = false;
                document.EditView.sat_flag.checked = false;
		document.getElementById('repeatMonthUI').style.display = 'none';
		document.EditView.repeatMonth[0].checked = true;
		document.EditView.repeatMonth_date.value = '';
		document.EditView.repeatMonth_daytype.value = 'first';
		document.EditView.repeatMonth_day.value = 1;
		document.EditView.parent_id.value = '';
		document.EditView.parent_type.value = 'None';
		document.getElementById('leadLay').style.visibility = 'hidden';
		document.EditView.contactlist.value = '';
		document.EditView.contactidlist.value = '';
		
		
        }
        else
        {
                document.createTodo.task_subject.readOnly = false;
                document.createTodo.task_date_start.readOnly = false;
                document.createTodo.starthr.disabled = false;
                document.createTodo.startmin.disabled = false;
                document.createTodo.startfmt.disabled = false;
		document.createTodo.taskpriority.disabled = false;
                document.createTodo.todosave.disabled = false;
                document.createTodo.todocancel.disabled = false;
		document.createTodo.task_toemail.readOnly = false;
        }
}


