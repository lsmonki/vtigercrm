{*<!--
/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
   * ("License"); You may not use this file except in compliance with the License
   * The Original Code is:  vtiger CRM Open Source
   * The Initial Developer of the Original Code is vtiger.
   * Portions created by vtiger are Copyright (C) vtiger.
   * All Rights Reserved.
  *
 ********************************************************************************/
-->*}
<!-- BEGIN: main -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

{literal}	
<!--[if lte IE 6]>
<STYLE type=text/css>HTML {
	OVERFLOW: auto; HEIGHT: 100%
}
BODY {
	margin:0 0 0 0;
	OVERFLOW: auto; HEIGHT: 100%
}
DIV.fixedLay {
	POSITION: absolute;
}
</STYLE>
<![endif]-->
{/literal}

	<title>{$CURRENT_USER} - {$APP.$CATEGORY} - {$APP.$MODULE_NAME} - {$APP.LBL_BROWSER_TITLE}</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link REL="SHORTCUT ICON" HREF="include/images/vtigercrm_icon.ico">	
	<style type="text/css">@import url("themes/{$THEME}/style.css");</style>
</head>
	<body leftmargin=0 topmargin=0 marginheight=0 marginwidth=0 class=small onLoad="Announcement_rss()">
	<a name="top">
	<!-- header -->
	<!-- header-vtiger crm name & RSS -->
	<script language="JavaScript" type="text/javascript" src="include/js/general.js"></script>
	<script language="javascript" type="text/javascript" src="include/scriptaculous/prototype.js"></script>
	<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
	<script language="JavaScript" type="text/javascript" src="include/calculator/calc.js"></script>
	<script language="JavaScript" type="text/javascript" src="modules/Calendar/script.js"></script>
	<script language="javascript" type="text/javascript" src="include/scriptaculous/dom-drag.js"></script>
	
	<TABLE border=0 cellspacing=0 cellpadding=0 width=100% class="hdrNameBg">
	<tr>
		<td><img src="{$IMAGEPATH}/vtiger-crm.gif" alt="vtiger CRM" title="vtiger CRM" border=0></td>
		<td width=100% align=center>
		<table border=0 cellspacing=0 cellpadding=2 width=90% class="rssDisplay">
		 <tr>
		 <td class=rssDisplayPanel align=center><marquee id="rss" direction="left" scrolldelay="10" scrollamount="3" behavior="scroll" class="marStyle" onMouseOver="javascript:stop();" onMouseOut="javascript:start();">&nbsp;</marquee></td>
		 {*<td class=rssDisplayPanel align=center> RSS is not configured. <a href="#">Click here</a> to configure</td>*}
		 </tr>
		</table>
		</td>
		<td class=small nowrap>
		<table border=0 cellspacing=0 cellpadding=0>
		 <tr>
		 <td style="padding-left:10px;padding-right:10px" class=small nowrap> <a href="index.php?module=Users&action=DetailView&record={$CURRENT_USER_ID}&modechk=prefview">{$APP.LBL_MY_PREFERENCES}</a></td>
		 <td style="padding-left:10px;padding-right:10px" class=small nowrap><a href="http://www.vtiger.com/index.php?option=com_content&task=view&id=34&Itemid=60" target="_blank">{$APP.LNK_HELP}</a></td>
	         <td style="padding-left:10px;padding-right:10px" class=small nowrap><a href="javascript:;" onClick="openwin();">{$APP.LNK_WEARE}</a></td>
	  	 <td style="padding-left:10px;padding-right:10px" class=small nowrap> <a href="index.php?module=Users&action=Logout">{$APP.LBL_LOGOUT}</a> ({$CURRENT_USER})</td>
		 </tr>
		</table>
		</td>
	</tr>
	</TABLE>

<div id='miniCal' style='width:300px; position:absolute; display:none; left:100px; top:100px; z-index:100000'>
</div>
<!-- header - master tabs -->
<TABLE border=0 cellspacing=0 cellpadding=0 width=100% class="hdrTabBg">
<tr>
	<td style="width:50px" class=small>&nbsp;</td>
	<td class=small nowrap> 
		<table border=0 cellspacing=0 cellpadding=0>

		<tr>
			{foreach key=maintabs item=detail from=$HEADERS}
				{if $maintabs ne $CATEGORY}
				  <td class=tabUnSelected align=center><a href="index.php?module={$detail[0]}&action=index&parenttab={$maintabs}">{$APP[$maintabs]}</a><img src="{$IMAGEPATH}/menuDnArrow.gif" border=0 style="padding-left:5px"></td>
				  <td class=tabSeperator><img src="{$IMAGEPATH}/tabSeperatorBg.gif"></td>
				{else}
				  <td class=tabSelected align=center><a href="index.php?module={$detail[0]}&action=index&parenttab={$maintabs}">{$APP[$maintabs]}</a><img src="{$IMAGEPATH}/menuDnArrow.gif" border=0 style="padding-left:5px"></td>
				  <td class=tabSeperator><img src="{$IMAGEPATH}/tabSeperatorBg.gif"></td>
				{/if}
			{/foreach}
			<td style="padding-left:10px" nowrap>
			{if $CNT eq 1}
                                <select class=small style="width:120px" title="Create New" onclick="QCreate(this);">
					<option value="none">{$APP.LBL_QUICK_CREATE}</option>
                        {foreach  item=detail from=$QCMODULE}
                                        <option value="{$detail.1}">{$APP[$detail.0]}</option>
                        {/foreach}
                                </select>
                        {else}
                                <select class=small style="width:120px" title="Create New" onchange="QCreate(this);">
					<option value="none">{$APP.LBL_QUICK_CREATE}</option>
                        {foreach  item=detail from=$QCMODULE}
                                        <option value="{$detail.1}">{$APP[$detail.0]}</option>
                        {/foreach}
                                </select>

                        {/if}
	
			</td>
		</tr>

		</table>
	</td>
	<td align=right style="padding-right:10px" >
		<table border=0 cellspacing=0 cellpadding=0 id="search" style="border:1px solid #999999;background-color:white">
		   <tr>
			<form name="UnifiedSearch" method="post" action="index.php">
			<td style="height:19px;background-color:#ffffef" >
				<input type="hidden" name="action" value="UnifiedSearch">
				<input type="hidden" name="module" value="Home">
				<input type="text" name="query_string" value="{$QUERY_STRING}" class="searchBox" onFocus="this.value=''" >
			</td>
			<td style="background-color:#cccccc">
				<input type="submit" class="searchBtn" value="{$APP.LBL_FIND_BUTTON}" alt="Find" title="Find">
			</td>
			</form>
		   </tr>
		</table>
	</td>
</td>
</tr>
</TABLE>
<!-- header - level 2 tabs -->
<TABLE border=0 cellspacing=0 cellpadding=2 width=100% class="level2Bg" style="border-bottom:1px solid #999999">
<tr>
	<!-- Left side gap -->
	
	<td >

		<table border=0 cellspacing=0 cellpadding=0>
		<tr>
			{foreach  key=maintabs item=detail from=$HEADERS}
				{if $maintabs eq $CATEGORY}
					{foreach  key=number item=module from=$detail}
						{if $module eq $MODULE_NAME}
							<td class="level2textSel" nowrap><a href="index.php?module={$module}&action=index&parenttab={$maintabs}">{$APP[$module]}</a></td>
						{else}
							<td class="level2text" nowrap> <a href="index.php?module={$module}&action=index&parenttab={$maintabs}">{$APP[$module]}</a> </td>
						{/if}	
					{/foreach}
				{/if}
			{/foreach}
		</tr>
		</table>
	</td>
</tr>
</TABLE>		

<div id="calculator_cont" style="position:absolute; z-index:10000" ></div>
	{include file="Clock.tpl"}


<div id="qcform" style="position:absolute;width:500px;top:60px;left:450px;z-index:5000;"></div>

<script>
function fetch_clock()
{ldelim}
	new Ajax.Request(
		'index.php',
		{ldelim}queue: {ldelim}position: 'end', scope: 'command'{rdelim},
			method: 'post',
			postBody: 'module=Utilities&action=UtilitiesAjax&file=Clock',
			onComplete: function(response)
				    {ldelim}
					$("clock_cont").innerHTML=response.responseText;
					execJS($('clock_cont'));
				    {rdelim}
		{rdelim}
	);

{rdelim}

function fetch_calc()
{ldelim}
	new Ajax.Request(
		'index.php',
		{ldelim}queue: {ldelim}position: 'end', scope: 'command'{rdelim},
			method: 'post',
			postBody: 'module=Utilities&action=UtilitiesAjax&file=Calculator',
			onComplete: function(response)
					{ldelim}
						$("calculator_cont").innerHTML=response.responseText;
						execJS($('calculator_cont'));
					{rdelim}
		{rdelim}
	);
{rdelim}

function Announcement_rss()
{ldelim}
	new Ajax.Request(
                'index.php',
                {ldelim}queue: {ldelim}position: 'end', scope: 'command'{rdelim},
                        method: 'post',
                        postBody: 'module=Users&action=UsersAjax&announce_rss=yes',
                        onComplete: function(response)
                                        {ldelim}
						if($("rss").innerHTML != response.responseText)
							$("rss").innerHTML=response.responseText;
                                        {rdelim}
                {rdelim}
        );

{rdelim}
setInterval("Announcement_rss()",300000)
</script>

<script>

function QCreate(qcoptions)
{ldelim}
	var module = qcoptions.options[qcoptions.options.selectedIndex].value;
	if(module != 'none')
	{ldelim}
	$("status").style.display="inline";
	if(module == 'Events')
	{ldelim}
		module = 'Activities';
		action = 'Activities';
		var urlstr = '&activity_mode=Events';
	{rdelim}
	else
		var urlstr = '';
	new Ajax.Request(
                'index.php',
                {ldelim}queue: {ldelim}position: 'end', scope: 'command'{rdelim},
                        method: 'post',
                        postBody: 'module='+module+'&action='+module+'Ajax&file=QuickCreate'+urlstr,
                        onComplete: function(response)
                                        {ldelim}
						$("status").style.display="none";
						$("qcform").style.display="inline";
					        $("qcform").innerHTML = response.responseText;
						eval($("qcform"));
                                        {rdelim}
                {rdelim}
        );
	{rdelim}
	else
		hide('qcform');
{rdelim}

</script>

{literal}
<SCRIPT>
function getFormValidate(divValidate)
{
  var st = document.getElementById('qcvalidate');
  eval(st.innerHTML);
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
	}
       //added to check Start Date & Time,if Activity Status is Planned.//start
        for (var j=0; j<fieldname.length; j++)
		{

			if(getObj(fieldname[i]) != null)
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
	return true;
}
</SCRIPT>
{/literal}

{* Quick Access Functionality *}
<div id="allMenu" onmouseout="fninvsh('allMenu');" onMouseOver="fnvshNrm('allMenu');" style="width:500px">
	<table border=0 cellpadding="5" cellspacing="0" class="allMnuTable" >
	<tr>
		<td valign="top">
		{assign var="parentno" value=0}
		{foreach name=parenttablist key=parenttab item=details from=$QUICKACCESS}
			<span class="allMnuHdr">{$APP[$parenttab]}</span>
			{foreach name=modulelist item=modules from=$details}
       		{math assign="num" equation="x + y" x=$parentno y=1}
			{math assign="loopvalue" equation="x % y" x=$num y=14}
			{assign var="parentno" value=$num}
			{if $loopvalue eq '0'}
				</td><td valign="top">
			{/if}
			<a href="index.php?module={$modules.0}&action=index&parenttab={$parenttab}" class="allMnu">{$APP[$modules.1]}</a>
		{/foreach}
		{/foreach}
		</td>
	</tr>
</table>
</div>


<div id="status" style="display:none;position:absolute;left:930px;top:90px;height:27px;white-space:nowrap;"><img src="{$IMAGEPATH}status.gif"></div>
<script>
function openwin()
{ldelim}
            window.open("index.php?module=Users&action=about_us","aboutwin","height=520,width=515,top=200,left=300")
{rdelim}
</script>

