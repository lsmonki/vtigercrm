<script language="javascript" type="text/javascript" src="modules/Home/Homestuff.js"></script>
<script language="javascript" type="text/javascript" src="include/scriptaculous/prototype.js"></script>
<script language="javascript" type="text/javascript" src="include/scriptaculous/scriptaculous.js"></script>
<script language="javascript" type="text/javascript" src="include/scriptaculous/unittest.js"></script>
{*<!--Home Page Entries  -->*}
	<TABLE border=0 cellspacing=0 cellpadding=0 width=100% class=small>
	<tr>
		<td style="padding-left:10px;padding-right:50px" width=10% class="moduleName" nowrap>{$APP.$CATEGORY}&gt; 
			<a class="hdrLink" href="index.php?action=index&module={$MODULE}">{$APP.$MODULE}</a>
		</td>
		<td width=40% nowrap>
			<table border="0" cellspacing="0" cellpadding="0" >
			<tr>
				<td class="sep1" style="width:1px;"></td>
				<td class=small >
				<table border=0 cellspacing=0 cellpadding=0>
				<tr>
					<td>
					<table border=0 cellspacing=0 cellpadding=5>
					<tr>
						<td style="padding-right:5px;padding-left:5px;"><img src="themes/images/btnL3Add-Faded.gif" border=0></td>	
						<td style="padding-right:5px"><img src="themes/images/btnL3Search-Faded.gif" border=0></td>
					</tr>
					</table>
					</td>
				</tr>
				</table>
				</td>
				<td style="width:20px;">&nbsp;</td>
				<td class="small">
				<table border=0 cellspacing=0 cellpadding=5>
				<tr>
					{if $CHECK.Calendar eq 'yes'}
					<td style="padding-right:5px;padding-left:5px;"><a href="javascript:;" onClick='fnvshobj(this,"miniCal");getMiniCal();'><img src="{$IMAGE_PATH}btnL3Calendar.gif" alt="{$APP.LBL_CALENDAR_ALT}" title="{$APP.LBL_CALENDAR_TITLE}" border=0></a></a></td>
					{else}
					<td style="padding-right:5px;padding-left:5px;"><img src="themes/images/btnL3Calendar-Faded.gif" border=0></td>
					{/if}
					<td style="padding-right:5px"><a href="javascript:;"><img src="{$IMAGE_PATH}btnL3Clock.gif" alt="{$APP.LBL_CLOCK_ALT}" title="{$APP.LBL_CLOCK_TITLE}" border=0 onClick="fnvshobj(this,'wclock');"></a></a></td>
					<td style="padding-right:5px"><a href="#"><img src="{$IMAGE_PATH}btnL3Calc.gif" alt="{$APP.LBL_CALCULATOR_ALT}" title="{$APP.LBL_CALCULATOR_TITLE}" border=0 onClick="fnvshobj(this,'calculator_cont');fetch_calc();"></a></td>
					<td style="padding-right:5px"><a href="javascript:;" onClick='return window.open("index.php?module=Home&action=vtchat","Chat","width=600,height=450,resizable=1,scrollbars=1");'><img src="{$IMAGE_PATH}tbarChat.gif" alt="{$APP.LBL_CHAT_ALT}" title="{$APP.LBL_CHAT_TITLE}" border=0></a></td>	
					<td style="padding-right:5px"><img src="{$IMAGE_PATH}btnL3Tracker.gif" alt="{$APP.LBL_LAST_VIEWED}" title="{$APP.LBL_LAST_VIEWED}" border=0 onClick="fnvshobj(this,'tracker');"></td>
				</tr>
				</table>
				</td>
				<td style="width:20px;">&nbsp;</td>
				<td class="small">
				<table border=0 cellspacing=0 cellpadding=5>
				<tr>
					<td style="padding-right:5px;padding-left:10px;"><img src="themes/images/tbarImport-Faded.gif" border="0"></td>	
					<td style="padding-right:5px"><img src="themes/images/tbarExport-Faded.gif" border="0"></td>
				</tr>
				</table>	
				</td>
				<td style="width:20px;">&nbsp;</td>
				<td class="small">
				<table border=0 cellspacing=0 cellpadding=5>
				<tr>
					<td style="padding-left:5px;"><a href="javascript:;" onmouseout="fninvsh('allMenu');" onClick="fnvshobj(this,'allMenu')"><img src="{$IMAGE_PATH}btnL3AllMenu.gif" alt="{$APP.LBL_ALL_MENU_ALT}" title="{$APP.LBL_ALL_MENU_TITLE}" border="0"></a></td>
				</tr>
				</table>
				</td>			
			</tr>
			</table>
		</td>
		<td style="width:50px" class='small' width=1%>&nbsp;</td>
		<td class="small" align=right nowrap width=1%></td><td align='center' width=9%><img onMouseOver='fnAddWindow(this,"addEventDropDown");' onMouseOut='fnRemoveWindow();' src="{$IMAGE_PATH}btnL3CreateWindow.gif" border="0" title="{$MOD.LBL_HOME_ADDWINDOW}" alt"{$MOD.LBL_HOME_ADDWINDOW}" style="cursor:pointer;">
		</td>

<!-- Icon for check ticket Status -->
{if $smarty.session.IssueCheck eq 'yes'}
<td style="padding-right:10px" width=9%><img src="{$IMAGE_PATH}ticketChecker.gif" style="cursor:pointer;" alt="{$APP.LBL_TICKET_STATUS}" title="{$APP.LBL_TICKET_STATUS}" border=0 onClick="checkTicketStatus('head');"></td>
{/if}

<!-- Icon for check alert Status -->


	{if $smarty.session.Contract neq ''}			
		<td style="padding-right:0px"><a href="{$smarty.session.Contract}"><img src="{$IMAGE_PATH}contractcheck.gif" alt="{$APP.LBL_CONTRACT_ALT}" title="{$APP.LBL_CONTRACT_TITLE}" border=0></a></td>
	{/if}

		<td width="20%"><marquee onmouseover="javascript:stop();" onmouseout="javascript:start();">{$CONFIGPROXY}</marquee</td>
		<td width=5%><div id="vtbusy_info" style="display:none;"><img src="{$IMAGE_PATH}status.gif" border="0"></div></td>
	</TABLE>
<div id="vtbusy_homeinfo" style="display:none;"><img src="themes/images/vtbusy.gif" border="0"></div>

{* Main Contents Starts Here *}
<form name="Homestuff" id="formStuff">
<input type="hidden" name="action" value="homestuff">
<input type="hidden" name="module" value="Home">
<div id='addEventDropDown' style='position:absolute;width:120px;display:none;z-index:3000;' onmouseover='fnShowWindow()' onmouseout='fnRemoveWindow()'>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr><td><a href='' id="addmodule" class='drop_down' style="width:110px;">{$MOD.LBL_HOME_MODULE}</a></td></tr>
	{if $ALLOW_RSS eq "yes"}
		<tr><td><a href='' id="addrss" class='drop_down' style="width:110px;">{$MOD.LBL_HOME_RSS}</a></td></tr>
	{/if}	
	{if $ALLOW_DASH eq "yes"}
		<tr><td><a href='' id="adddash" class='drop_down' style="width:110px;">{$MOD.LBL_HOME_DASHBOARD}</a></td></tr>
	{/if}
</table>
</div>
<div id="PopupLay" class="layerPopup" style="width:450px;z-index:2000;">
	<input type="hidden" name="stufftype" id="stufftype_id">	
	<table width="100%" border="0" cellpadding="5" cellspacing="0" class="layerHeadingULine">
		<tr>
			<td class="layerPopupHeading" align="left" id="divHeader"></td>
			<td align="right"><a href="javascript:;" onclick="fnhide('PopupLay');$('stufftitle_id').value='';"><img src="themes/images/close.gif" border="0"  align="absmiddle" /></a></td>
		</tr>
	</table>
	<table border=0 cellspacing=0 cellpadding=5 width=95% align=center> 
		<tr>
			<td class=small >
				<!-- popup specific content fill in starts -->
			<table border=0 cellspacing=0 cellpadding=3 width=100% align=center bgcolor=white>
					<tr id="StuffTitleId" style="display:block;">
						<td class="dvtCellLabel"  width="110" align="right">{$MOD.LBL_HOME_STUFFTITLE}<font color='red'>*</font></td>
						<td class="dvtCellInfo" colspan="2" width="300"><input type="text" name="stufftitle" id="stufftitle_id" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" style="width:57%"></td>
					</tr>
					<tr id="showrow">
						<td class="dvtCellLabel"  width="110" align="right">{$MOD.LBL_HOME_SHOW}</td>
						<td class="dvtCellInfo" width="300" colspan="2">
							<select name="maxentries" id="maxentryid" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" style="width:60%">
								{section name=iter start=1 loop=13 step=1}
								<option value="{$smarty.section.iter.index}">{$smarty.section.iter.index}</option>
								{/section}
							</select>&nbsp;&nbsp;{$MOD.LBL_HOME_ITEMS}
						</td>
					</tr>
					<tr id="moduleNameRow" style="display:block">
						<td class="dvtCellLabel"  width="110" align="right">{$MOD.LBL_HOME_MODULE}</td>
						<td width="300" class="dvtCellInfo" colspan="2">
							<select name="selmodule" id="selmodule_id" onchange="setFilter(this)" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" style="width:60%">
								{foreach item=arr from=$MODULE_NAME}
									{assign var="MODULE_LABEL" value=$arr.1}
									{if $APP[$arr.1] neq ''}		
										{assign var="MODULE_LABEL" value=$APP[$arr.1]}
									{/if}
									<option value="{$arr.1}">{$MODULE_LABEL}</option>
								{/foreach}
							</select>
							<input type="hidden" name="fldname">
						</td>
					</tr>
					<tr id="moduleFilterRow" style="display:block">
						<td class="dvtCellLabel" align="right" width="110" >{$MOD.LBL_HOME_FILTERBY}</td>
						<td id="selModFilter_id" colspan="2" class="dvtCellInfo" width="300">
						</td>
					</tr>
					<tr id="modulePrimeRow" style="display:block">
						<td class="dvtCellLabel" width="110" align="right" valign="top">{$MOD.LBL_HOME_Fields}</td>
						<td id="selModPrime_id" colspan="2" class="dvtCellInfo" width="300">
						</td>
					</tr>
					<tr id="rssRow" style="display:none">
						<td class="dvtCellLabel"  width="110" align="right">{$MOD.LBL_HOME_RSSURL}<font color='red'>*</font></td>
						<td width="300" colspan="2" class="dvtCellInfo"><input type="text" name="txtRss" id="txtRss_id" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" style="width:58%"></td>
					</tr>
					<tr id="dashNameRow" style="display:none">
						<td class="dvtCellLabel"  width="110" align="right">{$MOD.LBL_HOME_DASHBOARD_NAME}</td>
						<td id="selDashName" class="dvtCellInfo" colspan="2" width="300"></td>
					</tr>
					<tr id="dashTypeRow" style="display:none">
						<td class="dvtCellLabel" align="right" width="110">{$MOD.LBL_HOME_DASHBOARD_TYPE}</td>
						<td id="selDashType" class="dvtCellInfo" width="300" colspan="2">
							<select name="seldashtype" id="seldashtype_id" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" style="width:60%">
								<option value="horizontalbarchart">{$MOD.LBL_HOME_HORIZONTAL_BARCHART}</option>
								<option value="verticalbarchart">{$MOD.LBL_HOME_VERTICAL_BARCHART}</option>
								<option value="piechart">{$MOD.LBL_HOME_PIE_CHART}</option>
							</select>
						</td>
					</tr>
				</table>	
				<!-- popup specific content fill in ends -->
			</td>
		</tr>
	</table>
	<table border=0 cellspacing=0 cellpadding=5 width=95% align="center">
		<tr>
			<td align="right">
				<input type="button" name="save" value=" &nbsp;{$APP.LBL_SAVE_BUTTON_LABEL}&nbsp; " id="savebtn" class="crmbutton small save" onclick="frmValidate()"></td>
			<td align="left"><input type="button" name="cancel" value="{$APP.LBL_CANCEL_BUTTON_LABEL}" class="crmbutton small cancel" onclick="fnhide('PopupLay');$('stufftitle_id').value='';">
			</td>
		</tr>
	</table>
</div>
<div id="seqSettings" style="position:absolute;width:250px;height:20px;top:5px;left:360px;z-index:6000000;display:none;"></div>
{* Main Contents Start Here *}
<table width="97%" cellpadding="0" cellspacing="0" border="0" class="small showPanelBg" align="center" valign="top">
	<tr>
		<td width="78%" align="center" valign="top" height=350>
			<div id="MainMatrix" class="show_tab" style="padding:0px;width:100%">
				{foreach item=tablestuff from=$HOMEFRAME name="homeframe"}
					{include file="Home/MainHomeBlock.tpl"}
					<script>
					{if $tablestuff.Stufftype eq 'Default' && $tablestuff.Stufftitle eq 'Home Page Dashboard'}
						fetch_homeDB({$tablestuff.Stuffid},'{$tablestuff.Stufftype}');
					{else}
						loadStuff({$tablestuff.Stuffid},'{$tablestuff.Stufftype}');
						
					{/if}
					</script>
				{/foreach}
			</div>
		</td>	
		<td width="22%" valign="top" style="padding:5px;">
			<div id="upcomingActivities" style="margin: 0px;padding: 0px;">
				{include file="upcomingActivities.tpl"}
			</div><br>
			<div id="pendingActivities" style="margin: 0px;padding: px;">
				{include file="pendingActivities.tpl"}
			</div>
			{if $TAG_CLOUD_DISPLAY eq 'true'}
				<table border=0 cellspacing=0 cellpadding=0 width=100% class="tagCloud">
				<tr>
						<td class="tagCloudTopBg"><img src="{$IMAGE_PATH}tagCloudName.gif" border=0></td>
					</tr>
					<tr>
						<td class="tagCloudDisplay" valign=top> <span id="tagfields">{$ALL_TAG}</span></td>
					</tr>
				</table>
			{/if}
		</td>	
	</tr>
</table>

</form>
	<div id="eventcalAction" class="calAction" style="width:125px;" onMouseout="fninvsh('eventcalAction')" onMouseover="fnvshNrm('eventcalAction')">
		<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF">
			<tr>
				<td>
					{php}
					global $theme,$mod_strings,$app_strings,$current_user;
					if(isPermitted("Calendar","EditView") == "yes")
					{
					{/php}
					{php} if(getFieldVisibilityPermission('Events',$current_user->id,'eventstatus') == '0') { {/php}
						<a href="javascript:;" id="complete" onClick="fninvsh('eventcalAction')" class="calMnu">- {$MOD.LBL_HELD}</a>
						<a href="javascript:;" id="pending" onClick="fninvsh('eventcalAction')" class="calMnu">- {$MOD.LBL_NOTHELD}</a>
						<a href="javascript:void(0);" id="eventduplicate" onClick ="javascript:void(0);" class="calMnu">- {$APP.LBL_DUPLICATE_BUTTON}</a>
						<a href="javascript:void(0);" id="createfollowup" class="calMnu">- {$MOD.LBL_CREATEFOLLOWUP}</a>
					{php}
						}
					{/php}		
					<span style="border-top:1px dashed #CCCCCC;width:99%;display:block;"></span>
					<a href="javascript:;" id="postpone" onClick="fninvsh('eventcalAction')" class="calMnu">- {$MOD.LBL_POSTPONE}</a>
					<a href="javascript:;" id="changeowner" onClick="cal_fnvshobj(this,'act_changeowner');fninvsh('eventcalAction')" class="calMnu">- {$MOD.LBL_CHANGEOWNER}</a>
					{php}
					}
					if(isPermitted("Calendar","Delete") == "yes")	
					{
					{/php}
					<a href="javascript:void(0);" id="actdelete" onclick ="javascript:void(0);" class="calMnu">- {$MOD.LBL_DEL}</a>
					{php}
					}
					{/php}
				</td>
			</tr>
		</table>
	</div>
	<div id="act_changeowner" class="statechange">
		<form name="change_owner">
			<input type="hidden" value="" name="idlist" id="idlist">
			<input type="hidden" value="" name="action">
			<input type="hidden" value="" name="hour">
			<input type="hidden" value="" name="day">
			<input type="hidden" value="" name="month">
			<input type="hidden" value="" name="year">
			<input type="hidden" value="" name="view">
			<input type="hidden" value="" name="module">
			<input type="hidden" value="" name="subtab">
			<table width="100%" border="0" cellpadding="3" cellspacing="0" >
				<tr>
					<td class="genHeaderSmall" align="left" style="border-bottom:1px solid #CCCCCC;" width="60%">{$APP.LBL_CHANGE_OWNER}</td>
					<td style="border-bottom: 1px solid rgb(204, 204, 204);">&nbsp;</td>
					<td align="right" style="border-bottom:1px solid #CCCCCC;" width="40%"><a href="javascript:fninvsh('act_changeowner')"><img src="themes/images/close.gif" align="absmiddle" border="0"></a></td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td width="50%"><b>{$APP.LBL_TRANSFER_OWNERSHIP}</b></td>
					<td width="2%"><b>:</b></td>
					<td width="48%">
						<input type = "radio" name = "user_lead_owner"  onclick=checkgroup();  checked>{$APP.LBL_USER}&nbsp;
						<input type = "radio" name = "user_lead_owner" onclick=checkgroup(); >{$APP.LBL_GROUP}<br>
						<select name="lead_owner" id="lead_owner" class="detailedViewTextBox" style="display:block">
						{php} echo getUserslist(); {/php}
						</select>
						<select name="lead_group_owner" id="lead_group_owner" class="detailedViewTextBox" style="display:none;">
						{php} echo getGroupslist();{/php}
						</select>
					</td>
				</tr>
				<tr><td colspan="3" style="border-bottom:1px dashed #CCCCCC;">&nbsp;</td></tr>
				<tr>
					<td colspan="3" align="center">
						&nbsp;&nbsp;
						<input type="button" name="button" class="crm button small save" value="{$APP.LBL_UPDATE_OWNER}" onClick="calendarChangeOwner();fninvsh('act_changeowner');">
						<input type="button" name="button" class="crm button small cancel" value="{$APP.LBL_CANCEL_BUTTON_LABEL}" onClick="fninvsh('act_changeowner')">	
					</td>
				</tr>
			</table>
		</form>
	</div>

	<div id="taskcalAction" class="calAction" style="width:125px;" onMouseout="fninvsh('taskcalAction')" onMouseover="fnvshNrm('taskcalAction')">
		<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF">
			<tr>
				<td>
					{php}
						if(isPermitted("Calendar","EditView") == "yes")
						{
					{/php}
					{php} 
						if(getFieldVisibilityPermission('Calendar',$current_user->id,'taskstatus') == '0') { 
					{/php}
					<a href="" id="taskcomplete" onClick="fninvsh('taskcalAction');" class="calMnu">- {$MOD.LBL_COMPLETED}</a>
					<a href="" id="taskpending" onClick="fninvsh('taskcalAction');" class="calMnu">- {$MOD.LBL_DEFERRED}</a>
					<a href="javascript:void(0);" id="taskduplicate" onClick ="javascript:void(0);" class="calMnu">- {$APP.LBL_DUPLICATE_BUTTON}</a>
					{php}
						}
					{/php}
					<span style="border-top:1px dashed #CCCCCC;width:99%;display:block;"></span>
					<a href="" id="taskpostpone" onClick="fninvsh('taskcalAction');" class="calMnu">- {$MOD.LBL_POSTPONE}</a>
					<a href="" id="taskchangeowner" onClick="cal_fnvshobj(this,'act_changeowner'); fninvsh('taskcalAction');" class="calMnu">- {$MOD.LBL_CHANGEOWNER}</a>
					{php}
						}
						if(isPermitted("Calendar","Delete") == "yes")
						{
					{/php}
					<a href="javascript:void(0);" id="taskactdelete" onClick ="javascript:void(0);" class="calMnu">- {$MOD.LBL_DEL}</a>
					{php}
						}
					{/php}
				</td>
			</tr>
		</table>
	</div>
	
	<div id="createfollowupdiv" style="width: 500px;">
	</div>
	<div id="shim" class="veil" style="visibility: hidden;">
	</div>
	
{* Main Contents Ends Here *}
<script>
{literal}

initHomePage = function(){
Sortable.create
(
	"MainMatrix",
	{
		constraint:false,tag:'div',overlap:'Horizontal',handle:'headerrow',
		onUpdate:function()
		{
			matrixarr = Sortable.serialize('MainMatrix').split("&");
			matrixseqarr=new Array();
			seqarr=new Array();
			for(x=0;x<matrixarr.length;x++)
			{
				matrixseqarr[x]=matrixarr[x].split("=")[1];
			}
			BlockSorting(matrixseqarr);	
			
		}
	}
);
}

initHomePage();

function BlockSorting(matrixseqarr)
{
var sequence = matrixseqarr.join("_");

new Ajax.Request('index.php',
					{queue: {position: 'end', scope: 'command'},
						method: 'post',
						postBody:'module=Home&action=HomeAjax&file=HomestuffAjax&matrixsequence='+sequence,
						onComplete: function(response) 
						{
							$('seqSettings').innerHTML=response.responseText;
							LocateObj($('seqSettings'))
							Effect.Appear('seqSettings');
							setTimeout(hideSeqSettings,3000);
						}
					}
				);
}
function fnAddWindow(obj,CurrObj)
{
	var tagName = document.getElementById(CurrObj);
	tagName.style.display="block";
	var left_Side = findPosX(obj);
	var top_Side = findPosY(obj);
	tagName.style.left= left_Side + 2 + 'px';
	tagName.style.top= top_Side + 22 + 'px';
	tagName.style.display = 'block';
	document.getElementById("addmodule").href="javascript:chooseType('Module');fnRemoveWindow();setFilter($('selmodule_id'))";
{/literal}
{if $ALLOW_RSS eq "yes"}
{literal}	
	document.getElementById("addrss").href="javascript:chooseType('RSS');fnRemoveWindow();positionDivToCenter('PopupLay');show('PopupLay')";
{/literal}
{/if}
{if $ALLOW_DASH eq "yes"}
{literal}	
	document.getElementById("adddash").href="javascript:chooseType('DashBoard');fnRemoveWindow()";
{/literal}	
{/if}
{literal}	
}
{/literal}	
</script>


