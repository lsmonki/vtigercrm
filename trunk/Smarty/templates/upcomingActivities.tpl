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
{if $ACTIVITIES.0.noofactivities > 0}
	{assign var=label value=$ACTIVITIES.0.Title.0}
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        	<tr>
                	<td><img src="{$IMAGE_PATH}upcoming_left.gif" align="top"  /></td>
                	<td width="100%" background="{$IMAGE_PATH}upcomingEvents.gif" style="background-repeat:repeat-x; "></td>
			<td><img src="{$IMAGE_PATH}upcoming_right.gif" align="top"  /></td>
		</tr>
		<tr>
                        <td colspan="3" bgcolor="#FFFFCF" style="border-left:2px solid #A6A4A5;border-right:2px solid #A6A4A5;padding-left:10px;">
                	        <b class="fontBold">{$APP.$label}&nbsp;{$APP.LBL_UPCOMING_EVENTS}&nbsp;({$ACTIVITIES.0.noofactivities})</b><br />
                                <b>{$APP.LBL_SHOW}</b>
				{if $ACTIVITIES.0.Title.0 eq 'today'}
					{$APP.LBL_TODAY}&nbsp;&nbsp;|&nbsp;&nbsp;<a href="#" onClick="getHomeActivities(0,'all')">{$APP.LBL_ALL}</a>
				{else}
					<a href="#" onClick="getHomeActivities(0,'today')">{$APP.LBL_TODAY}</a>&nbsp;&nbsp;|&nbsp;&nbsp;{$APP.LBL_ALL}
			        {/if}
			</td>
		</tr>
		<tr>
			<td colspan="3" bgcolor="#FFFFCF" style="border-left:2px solid #A6A4A5;border-right:2px solid #A6A4A5;border-bottom:2px solid #A6A4A5;">
			<div id="upcomingActivitiesEntry" style="overflow-y:auto;overflow-x:hidden;height:150px;width:100%">
				<table width="100%" border="0" cellpadding="5" cellspacing="0">
					<tr bgcolor="#FFFFCF">
	                                        <td style="border-bottom:1px dotted #dddddd;" colspan=3 align="right" valign=top>&nbsp;</td>
                                        </tr>
                                        {foreach item=entries name=entryloop from=$ACTIVITIES.0.Entries}
                                        <tr bgcolor="#FFFFCF">
						<td style="border-bottom:1px dotted #dddddd;" align="right" width="20" valign=top>{math equation="x+1" x=$smarty.foreach.entryloop.index}</td>
						<td style="border-bottom:1px dotted #dddddd;" align="right" width="20" valign=top>{$entries.IMAGE}</td>
                                                <td style="border-bottom:1px dotted #dddddd;" align="left" valign="middle" colspan="2" width="85%"><b>{$entries.0}</b>{*<br />{$entries.ACCOUNT_NAME*}</td>
                                        </tr>
                                        {/foreach}
				</table>
				</div>
			</td>
		</tr>
	</table>
	<br>
{/if}

