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
<script language="javascript" type="text/javascript" src="include/scriptaculous/prototype.js"></script>
<script language="javascript" type="text/javascript" src="include/scriptaculous/scriptaculous.js"></script>
<script language="javascript" type="text/javascript" src="include/scriptaculous/effects.js"></script>
<script language="javascript" type="text/javascript" src="include/scriptaculous/builder.js"></script>
<script language="javascript" type="text/javascript" src="include/scriptaculous/dragdrop.js"></script>
<script language="javascript" type="text/javascript" src="include/scriptaculous/controls.js"></script>
<script language="javascript" type="text/javascript" src="include/scriptaculous/slider.js"></script>
<script language="javascript" type="text/javascript" src="include/scriptaculous/dom-drag.js"></script>
<script type="text/javascript" language="JavaScript" src="include/js/general.js"></script>


{*<!--Home Page Entries  -->*}

<TABLE border=0 cellspacing=0 cellpadding=0 width=100% class=small>
<tr><td style="height:2px"></td></tr>
<tr>
	<td style="padding-left:10px;padding-right:50px" class="moduleName" nowrap>{$APP.$CATEGORY} > <a class="hdrLink" href="index.php?action=index&module={$MODULE}">{$APP.$MODULE}</a></td>
	<td width=100% nowrap>
	
		<table border="0" cellspacing="0" cellpadding="0" >
		<tr>
		<td class="sep1" style="width:1px;"></td>
		<td class=small >
			<!-- Add and Search -->
			<table border=0 cellspacing=0 cellpadding=0>
			<tr>
			<td>
				<table border=0 cellspacing=0 cellpadding=5>
				<tr>
					{if $CHECK.EditView eq 'yes'}
			        		{if $MODULE eq 'Activities'}
		                      	        	<td style="padding-right:0px;padding-left:10px;"><a href="javascript:;" id="showSubMenu"  onMouseOver="fnvshobj(this,'reportLay');" onMouseOut="fninvsh('reportLay');"><img src="{$IMAGE_PATH}btnL3Add.gif" alt="{$APP.LBL_CREATE_BUTTON_LABEL} {$MODULE}..." title="{$APP.LBL_CREATE_BUTTON_LABEL} {$MODULE}..." border=0></a></td>
                	   			 {else}
	                        		       	<td style="padding-right:0px;padding-left:10px;"><a href="index.php?module={$MODULE}&action=EditView&return_action=DetailView&parenttab={$CATEGORY}"><img src="{$IMAGE_PATH}btnL3Add.gif" alt="{$APP.LBL_CREATE_BUTTON_LABEL} {$MODULE}..." title="{$APP.LBL_CREATE_BUTTON_LABEL} {$MODULE}..." border=0></a></td>
			                       	{/if}
					{else}
						<td style="padding-right:0px;padding-left:10px;"><img src="{$IMAGE_PATH}btnL3Add-Faded.gif" border=0></td>	
					{/if}
									
					{if $CHECK.index eq 'yes'}
						 <td style="padding-right:10px"><a href="javascript:;" onClick="moveMe('searchAcc');searchshowhide('searchAcc','advSearch')" ><img src="{$IMAGE_PATH}btnL3Search.gif" alt="{$APP.LBL_SEARCH_ALT}{$MODULE}..." title="{$APP.LBL_SEARCH_TITLE}{$MODULE}..." border=0></a></a></td>
					{else}
						<td style="padding-right:10px"><img src="{$IMAGE_PATH}btnL3Search-Faded.gif" border=0></td>
					{/if}
					
				</tr>
				</table>
			</td>
			</tr>
			</table>
		</td>
		<td style="width:20px;">&nbsp;</td>
		<td class="small">
			<!-- Calendar Clock Calculator and Chat -->
				<table border=0 cellspacing=0 cellpadding=5>
				<tr>
					<td style="padding-right:0px;padding-left:10px;"><a href="javascript:;" onClick='fnvshobj(this,"miniCal");getMiniCal();'><img src="{$IMAGE_PATH}btnL3Calendar.gif" alt="{$APP.LBL_CALENDAR_ALT}" title="{$APP.LBL_CALENDAR_TITLE}" border=0></a></a></td>
					<td style="padding-right:0px"><a href="javascript:;"><img src="{$IMAGE_PATH}btnL3Clock.gif" alt="{$APP.LBL_CLOCK_ALT}" title="{$APP.LBL_CLOCK_TITLE}" border=0 onClick="fnvshobj(this,'wclock');"></a></a></td>
					<td style="padding-right:0px"><a href="#"><img src="{$IMAGE_PATH}btnL3Calc.gif" alt="{$APP.LBL_CALCULATOR_ALT}" title="{$APP.LBL_CALCULATOR_TITLE}" border=0 onClick="fnvshobj(this,'calculator_cont');fetch_calc();"></a></td>
					<td style="padding-right:10px"><a href="javascript:;" onClick='return window.open("index.php?module=Contacts&action=vtchat","Chat","width=450,height=400,resizable=1,scrollbars=1");'><img src="{$IMAGE_PATH}tbarChat.gif" alt="{$APP.LBL_CHAT_ALT}" title="{$APP.LBL_CHAT_TITLE}" border=0></a></td>	
					<td style="padding-right:10px"><img src="{$IMAGE_PATH}btnL3Tracker.gif" alt="{$APP.LBL_LAST_VIEWED}" title="{$APP.LBL_LAST_VIEWED}" border=0 onClick="fnvshobj(this,'tracker');"></td>
				</tr>
				</table>
		</td>
		<td style="width:20px;">&nbsp;</td>
		<td class="small">
			<!-- Import / Export -->
			<table border=0 cellspacing=0 cellpadding=5>
			<tr>
			{if $MODULE eq 'Contacts' || $MODULE eq 'Leads' || $MODULE eq 'Accounts' || $MODULE eq 'Potentials' || $MODULE eq 'Products' }
		   		{if $CHECK.Import eq 'yes'}	
					<td style="padding-right:0px;padding-left:10px;"><a href="index.php?module={$MODULE}&action=Import&step=2&return_module={$MODULE}&return_action=index&parenttab={$CATEGORY}"><img src="{$IMAGE_PATH}tbarImport.gif" alt="{$APP.LBL_IMPORT} {$APP.$MODULE}" title="{$APP.LBL_IMPORT} {$APP.$MODULE}" border="0"></a></td>	
				{else}	
					<td style="padding-right:0px;padding-left:10px;"><img src="{$IMAGE_PATH}tbarImport-Faded.gif" border="0"></td>	
				{/if}	
				{if $CHECK.Export eq 'yes'}	
	    			<td style="padding-right:10px"><a href="index.php?module={$MODULE}&action=Export&all=1"><img src="{$IMAGE_PATH}tbarExport.gif" alt="{$APP.LBL_EXPORT} {$APP.$MODULE}" title="{$APP.LBL_EXPORT} {$APP.$MODULE}" border="0"></a></td>
				{else}	
					<td style="padding-right:10px"><img src="{$IMAGE_PATH}tbarExport-Faded.gif" border="0"></td>
                {/if}
			{elseif $MODULE eq 'Notes' || $MODULE eq 'Emails'}	
				
				{if $CHECK.Export eq 'yes'}
					<td style="padding-right:0px;padding-left:10px;"><img src="{$IMAGE_PATH}tbarImport-Faded.gif" border="0"></td>
					<td style="padding-right:10px"><a href="index.php?module={$MODULE}&action=Export&all=1"><img src="{$IMAGE_PATH}tbarExport.gif" alt="{$APP.LBL_EXPORT} {$APP.$MODULE}" title="{$APP.LBL_EXPORT} {$APP.$MODULE}" border="0"></a></td>
				{else}	 
					<td style="padding-right:0px;padding-left:10px;"><img src="{$IMAGE_PATH}tbarImport-Faded.gif" border="0"></td>
					<td style="padding-right:10px"><img src="{$IMAGE_PATH}tbarExport-Faded.gif" border="0"></td>
				{/if}
			{else}
				<td style="padding-right:0px;padding-left:10px;"><img src="{$IMAGE_PATH}tbarImport-Faded.gif" border="0"></td>
                <td style="padding-right:10px"><img src="{$IMAGE_PATH}tbarExport-Faded.gif" border="0"></td>
			{/if}
			</tr>
			</table>	
		<td style="width:20px;">&nbsp;</td>
		<td class="small">
			<!-- All Menu -->
				<table border=0 cellspacing=0 cellpadding=5>
				<tr>
				<td style="padding-left:10px;"><a href="javascript:;" onmouseout="fninvsh('allMenu');" onClick="fnvshobj(this,'allMenu')"><img src="{$IMAGE_PATH}btnL3AllMenu.gif" alt="{$APP.LBL_ALL_MENU_ALT}" title="{$APP.LBL_ALL_MENU_TITLE}" border="0"></a></td>
				</tr>
				</table>
		</td>			
		</tr>
		</table>
	</td>
</tr>
<tr><td style="height:2px"></td></tr>
</TABLE>



{* Main Contents Start Here *}
<table width="98%" cellpadding="0" cellspacing="0" border="0" class="small showPanelBg" align="center" valign="top">
			<tr>
				<td align=right valign=top><img src="{$IMAGE_PATH}showPanelTopLeft.gif"></td>
					<td width="75%" align="center" style="border-right:1px solid #666666;" valign="top">

	<div id="MainMatrix">
				{foreach key=modulename item=tabledetail from=$HOMEDETAILS}
				{if $modulename neq 'Dashboard'}
				{if $tabledetail neq ''}
				
					<div class="MatrixLayer" style="float:left;" id="{$tabledetail.Title.2}">
	<table width="100%" height="100%" border="0" cellpadding="5" cellspacing="0" class="small">
  <tr style="cursor:move;height:30px;">
		<td align="left" style="border-bottom:1px solid #666666;"><b>{$tabledetail.Title.1}</b></td>
		<td align="right" style="border-bottom:1px solid #666666;"><img src="{$IMAGE_PATH}uparrow.gif" align="absmiddle" /></td>
           </tr>
	{foreach item=elements from=$tabledetail.Entries}
	    <tr style="height:25px;" align="left">
		{if $tabledetail.Title.2 neq 'home_mytopinv' && $tabledetail.Title.2 neq 'home_mytopso' && $tabledetail.Title.2 neq 'home_mytopquote' && $tabledetail.Title.2 neq 'home_metrics' &&  $tabledetail.Title.2 neq 'home_mytoppo' &&  $tabledetail.Title.2 neq 'home_myfaq'  }
		<td colspan="2"><img src="{$IMAGE_PATH}bookMark.gif" align="absmiddle" /> {$elements.0}</td>
		{elseif $tabledetail.Title.2 eq 'home_metrics'}
		<td><img src="{$IMAGE_PATH}bookMark.gif" align="absmiddle" /> {$elements.0}</td>
		<td align="absmiddle" /> {$elements.1}</td>
		{else}	
		<td colspan="2"><img src="{$IMAGE_PATH}bookMark.gif" align="absmiddle" /> {$elements.1}</td>
		{/if}

           </tr>
{/foreach}
	<tr><td colspan="2" align="right" valign="bottom">
		{if $modulename neq 'CustomView'}
	                <a href="index.php?module={$modulename}&action=index">{$APP.LBL_MORE}..</a>
                {else}
        	        &nbsp;
                {/if}
	</table>
				
			</div>
			{/if}	
		{else}
                        <div class="MatrixLayer" style="float:left;width:61%;" id="homepagedb">
                        <table width="100%" height="100%" border="0" cellpadding="5" cellspacing="0" class="small">
			<tr style="cursor:move;">
				<td align="left" style="border-bottom:1px solid #666666;height:32px;"><b>{$APP.LBL_HOMEPAGE_DASHBOARD}</b></td>
				<td align="right" style="border-bottom:1px solid #666666;"><img src="{$IMAGE_PATH}uparrow.gif" align="absmiddle" /></td>
		         </tr>
			<tr align="center"><td id="dashborad_cont" colspan="2" style="height:227px;">&nbsp;
                                
                        </td></tr>
                        </table>
			</div>
                {/if}
{/foreach}
</div>
</td>

<td width="25%" valign="top" style="padding:5px;">
	{if $ACTIVITIES.0.Entries.noofactivities > 0}	
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
	<td width="13" height="38"><img src="{$IMAGE_PATH}upcoming_left.gif" align="top"  /></td>
	<td width="100%" background="{$IMAGE_PATH}upcomingEvents.gif" style="background-repeat:repeat-x; ">&nbsp;</td>
	<td width="14" height="38" align="left"><img src="{$IMAGE_PATH}upcoming_right.gif" align="top"  /></td>
	</tr>		
	<tr>
	<td colspan="3" bgcolor="#FFFFCF" style="border-left:2px solid #A6A4A5;border-right:2px solid #A6A4A5;border-bottom:2px solid #A6A4A5;">
	<table width="100%" border="0" cellpadding="5" cellspacing="0">
	<tr>
	<td width="75%" colspan="2"><b class="fontBold">{$MOD.LBL_UPCOMING_EVENTS}</b><br />{$ACTIVITIES.0.Entries.noofactivities} {$APP.Events} {$APP.LBL_FOR} {$ACTIVITIES.0.Title.0}</td>
	<td width="25%" valign="top" align="right"><img src="{$IMAGE_PATH}up.gif" align="absmiddle" /></td>
	</tr>
	{foreach item=entries from=$ACTIVITIES.0.Entries}
	<tr>
	<td align="right" width="15%">{$entries.IMAGE}</td>
	<td align="left" valign="middle" colspan="2" width="85%"><b class="style_Gray">{$entries.0}</b><br />{$entries.ACCOUNT_NAME}</td>
	</tr>
	{/foreach}
	</table>
	</td>
	</tr>
	</table><br />
	{/if}

	{if $ACTIVITIES.1.Entries.noofactivities > 0}	
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="small">
	<tr>
	<td width="14" height="70" background="{$IMAGE_PATH}pending_left.gif" ></td>
	<td width="90%" background="{$IMAGE_PATH}pendingEvents.gif" valign="bottom" style="background-repeat:repeat-x;">
	<b class="fontBold">{$MOD.LBL_PENDING_EVENTS}</b><br />
	{$ACTIVITIES.1.Entries.noofactivities} {$MOD.LBL_TODAYEVENT}</td>
	<td width="15" height="70" background="{$IMAGE_PATH}pending_right.gif" valign="bottom">
	<img src="{$IMAGE_PATH}up.gif" align="top" />&nbsp;</td>
	</tr>		
	<tr>
	<td colspan="3" bgcolor="#FEF7C1" style="border-left:2px solid #A6A4A5;border-right:2px solid #A6A4A5;border-bottom:2px solid #A6A4A5;">
	<table width="100%" border="0" cellpadding="5" cellspacing="0">
	<tr>
	<td colspan="3" height="10"></td>
	</tr>
	{foreach item=entries from=$ACTIVITIES.1.Entries}
	<tr>
	<td align="right" width="15%">{$entries.IMAGE}</td>
	<td align="left" valign="middle" colspan="2" width="85%"><b class="style_Gray">{$entries.0}</b><br />{$entries.ACCOUNT_NAME}</td>
	</tr>
	{/foreach}
	</table></td>
	</tr>
	</table>
	<br>
	{/if}
    {if $TAGCLOUD_JS ne ''}
    <table width="100%" border=0>
    <tr><td>
    <link href="{$TAGCLOUD_CSS}" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="{$TAGCLOUD_JS}"></script>
    </td></tr>
    </table>
    {/if}


	<table border=0 cellspacing=0 cellpadding=0 width=100% class="tagCloud">
	<tr>
		<td class="tagCloudTopBg"><img src="{$IMAGE_PATH}tagCloudName.gif" border=0></td>
	</tr>
	<tr>
		<td class="tagCloudDisplay" valign=top> <span id="tagfields">{$ALL_TAG}</span></td>
	</tr>
	</table>
	



</td>
<td align=right valign=top><img src="{$IMAGE_PATH}showPanelTopRight.gif"></td>

</tr>
</table>

{literal}
<script  language="javascript">
		Sortable.create("MainMatrix",
        {constraint:false,tag:'div',overlap:'horizontal',
			onUpdate:function(){
			//	alert(Sortable.serialize('MainMatrix')); 
			}
		});
	 
		//new Sortable.create('MainMatrix','div');

function fetch_homeDB()
{
	new Ajax.Request(
		'index.php',
		{queue: {position: 'end', scope: 'command'},
			method: 'post',
			postBody: 'module=Dashboard&action=DashboardAjax&file=HomepageDB',
			onComplete: function(response)
			{
				$("dashborad_cont").style.display = 'none';
				$("dashborad_cont").innerHTML=response.responseText;
				Effect.Appear("dashborad_cont");
			}
		}
	);
}
</script>
{/literal}
<script>
function showhide(tab)
{ldelim}
var divid = document.getElementById(tab);
if(divid.style.display!='none')
	hide(tab)
else
	show(tab)
{rdelim}

{if $IS_HOMEDASH eq 'true'}
fetch_homeDB();
{/if}
</script>

	
			
