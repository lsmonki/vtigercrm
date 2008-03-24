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
<script language="javascript">
function getHomeActivities(mode,view)

{ldelim}
        new Ajax.Request(
                'index.php',
                {ldelim}queue: {ldelim}position: 'end', scope: 'command'{rdelim},
                        method: 'post',
                        postBody: 'module=Calendar&action=ActivityAjax&file=OpenListView&activity_view='+view+'&mode='+mode+'&parenttab=My Home Page&ajax=true',
                        onComplete: function(response) {ldelim}
                                if(mode == 0)
                                        $("upcomingActivities").innerHTML=response.responseText;
                                else
                                        $("pendingActivities").innerHTML=response.responseText;
                        {rdelim}
                {rdelim}
        );
{rdelim}

</script>

{*<!--Home Page Entries  -->*}

	<TABLE border=0 cellspacing=0 cellpadding=0 width=100% class=small>
	<tr>
		<td style="height:2px" colspan="2"></td>
	</tr>
	<tr>
		<td style="padding-left:10px;padding-right:50px" class="moduleName" nowrap>{$APP.$CATEGORY} >
			<a class="hdrLink" href="index.php?action=index&module={$MODULE}">{$APP.$MODULE}</a>
		</td>
		<td width=100% nowrap>
					<table border="0" cellspacing="0" cellpadding="0" >
				<tr>
					<td class="sep1" style="width:1px;"></td>
					<td class=small >
							<table border=0 cellspacing=0 cellpadding=0>
							<tr>
								<td>
										<table border=0 cellspacing=0 cellpadding=5>
			<tr>
					<td style="padding-right:5px;padding-left:5px;"><img src="{$IMAGE_PATH}btnL3Add-Faded.gif" border=0></td>	
					<td style="padding-right:5px"><img src="{$IMAGE_PATH}btnL3Search-Faded.gif" border=0></td>
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
									{if $CALENDAR_DISPLAY eq 'true'} 
 		                                                                              {if $CHECK.Calendar eq 'yes'} 
 		                                                                                        <td style="padding-right:5px;padding-left:5px;"><a href="javascript:;" onClick='fnvshobj(this,"miniCal");getMiniCal();'><img src="{$IMAGE_PATH}btnL3Calendar.gif" alt="{$APP.LBL_CALENDAR_ALT}" title="{$APP.LBL_CALENDAR_TITLE}" border=0></a></a></td> 
 		                                                                              {else} 
 		                                                                                        <td style="padding-right:5px;padding-left:5px;"><img src="{$IMAGE_PATH}btnL3Calendar-Faded.gif" border=0></td> 
 		                                                                              {/if} 
									{/if}
									 {if $WORLD_CLOCK_DISPLAY eq 'true'} 
 		                                                                                <td style="padding-right:5px"><a href="javascript:;"><img src="{$IMAGE_PATH}btnL3Clock.gif" alt="{$APP.LBL_CLOCK_ALT}" title="{$APP.LBL_CLOCK_TITLE}" border=0 onClick="fnvshobj(this,'wclock');"></a></a></td> 
 		                                                         {/if} 
 		                                                                        {if $CALCULATOR_DISPLAY eq 'true'} 
 		                                                                                <td style="padding-right:5px"><a href="#"><img src="{$IMAGE_PATH}btnL3Calc.gif" alt="{$APP.LBL_CALCULATOR_ALT}" title="{$APP.LBL_CALCULATOR_TITLE}" border=0 onClick="fnvshobj(this,'calculator_cont');fetch_calc();"></a></td> 
 		                                                                        {/if} 
 		                                                                        {if $CHAT_DISPLAY eq 'true'} 
 		                                                                                <td style="padding-right:5px"><a href="javascript:;" onClick='return window.open("index.php?module=Contacts&action=vtchat","Chat","width=600,height=450,resizable=1,scrollbars=1");'><img src="{$IMAGE_PATH}tbarChat.gif" alt="{$APP.LBL_CHAT_ALT}" title="{$APP.LBL_CHAT_TITLE}" border=0></a></td>     
 		                                                                        {/if} 
									<td style="padding-right:5px"><img src="{$IMAGE_PATH}btnL3Tracker.gif" alt="{$APP.LBL_LAST_VIEWED}" title="{$APP.LBL_LAST_VIEWED}" border=0 onClick="fnvshobj(this,'tracker');"></td>
								</tr>
							</table>
					</td>
					<td style="width:20px;">&nbsp;</td>
					<td class="small">
						<table border=0 cellspacing=0 cellpadding=5>
							<tr>
							<td style="padding-right:5px;padding-left:10px;"><img src="{$IMAGE_PATH}tbarImport-Faded.gif" border="0"></td>	
							<td style="padding-right:5px"><img src="{$IMAGE_PATH}tbarExport-Faded.gif" border="0"></td>
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
	</tr>
	<tr><td style="height:2px"></td></tr>
</TABLE>



{* Main Contents Start Here *}
<table width="98%" cellpadding="0" cellspacing="0" border="0" class="small showPanelBg" align="center" valign="top">
	<tr>
		<td align=right valign=top><img src="{$IMAGE_PATH}showPanelTopLeft.gif"></td>
		<td width="75%" align="center" class="homePageSeperator" valign="top">
				<div id="MainMatrix">
					{foreach key=modulename item=tabledetail from=$HOMEDETAILS}
						{if $modulename neq 'Dashboard'}
							{if $tabledetail neq ''}
								<div class="MatrixLayer" style="float:left;" id="{$tabledetail.Title.2}">
										<table width="100%" border="0" cellpadding="5" cellspacing="0" class="small">
								<tr style="cursor:move;height:20px;">
									<td align="left" class="homePageMatrixHdr" ><b>{$tabledetail.Title.1}</b></td>
									<td align="right" class="homePageMatrixHdr" ><img src="{$IMAGE_PATH}uparrow.gif" align="absmiddle" /></td>
								</tr>
								<tr align="left" class="winmarkModulesdef">
									<td valign=top  colspan=2>
											<div style="overflow-y:auto;overflow-x:hidden;height:200px;width:99%"> 
											<table border=0 cellspacing=0 cellpadding=5 width=100%>
												{foreach item=elements from=$tabledetail.Entries}
													<tr>
														{if $tabledetail.Title.2 neq 'home_mytopinv' && $tabledetail.Title.2 neq 'home_mytopso' && $tabledetail.Title.2 neq 'home_mytopquote' && $tabledetail.Title.2 neq 'home_metrics' &&  $tabledetail.Title.2 neq 'home_mytoppo' &&  $tabledetail.Title.2 neq 'home_myfaq'  }
															<td colspan="2"><img src="{$IMAGE_PATH}bookMark.gif" align="absmiddle" />{$elements.0} 
															 	{if $modulename eq 'Leads'}
																 - {$elements.1}	
																{/if}
															</td>
														{elseif $tabledetail.Title.2 eq 'home_metrics'}
															<td><img src="{$IMAGE_PATH}bookMark.gif" align="absmiddle" /> {$elements.0}</td>
															<td align="absmiddle" /> {$elements.1}</td>
														{else}	
															<td colspan="2"><img src="{$IMAGE_PATH}bookMark.gif" align="absmiddle" /> {$elements.1}</td>
														{/if}
													</tr>
												{/foreach}
											</table>	
											</div>									
									</td>
								</tr>
								<tr>
									<td colspan="2" align="right" valign="bottom">
									{if $modulename neq 'CustomView' && $modulename neq 'GroupAllocation'}
									 <a href="index.php?module={$modulename}&action=index&search_field=assigned_user_id&searchtype=BasicSearch&search_text={$CURRENTUSER}&query=true">{$APP.LBL_MORE}..</a>
									{else}
										&nbsp;	
									{/if}
									</td>
								</tr>
							</table>
								</div>
							{/if}	
							{else}
								<div class="MatrixLayer" style="float:left;width:93%;" id="homepagedb">
									<table width="100%" border="0" cellpadding="8" cellspacing="0" class="small">
										<tr style="cursor:move;">
											<td align="left" class="homePageMatrixHdr"><b>{$APP.LBL_HOMEPAGE_DASHBOARD}</b></td>
											<td align="right" class="homePageMatrixHdr"><img src="{$IMAGE_PATH}uparrow.gif" align="absmiddle" /></td>
										</tr>
										<tr align="left" class="winmarkModulesdef">	
											<td colspan="2">
											<div style="overflow:hidden;height:255px;width:99%"> 
												<table border=0 cellspacing=0 cellpadding=5 width=100%>
													<tr><td id="dashborad_cont" style="height:250px;">&nbsp;</td></tr>
												</table>
											</div>
											<table border=0 cellspacing=0 cellpadding=5 width=100%>
													<tr>
														<td colspan="2" align="right" valign="bottom">
															&nbsp;	
														</td>
													</tr>
											</table>
											</td>
										</tr>
									</table>
								</div>
						{/if}
				{/foreach}
			</div>
		</td>
		<td width="25%" valign="top" style="padding:5px;">
			<div id="upcomingActivities">
                                {include file="upcomingActivities.tpl"}
                        </div><br>
                        <div id="pendingActivities">
                                {include file="pendingActivities.tpl"}
                        </div><br>
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

	
			
