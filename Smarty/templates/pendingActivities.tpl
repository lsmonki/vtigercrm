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
{if $ACTIVITIES.1.noofactivities > 0}
{assign var=label value=$ACTIVITIES.1.Title.0}
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="small">
        <tr>
		<td><img src="{$IMAGE_PATH}pending_left.gif"></td>
                <td width="100%" background="{$IMAGE_PATH}pendingEvents.gif" valign="bottom" style="background-repeat:repeat-x;">
                        <b class="fontBold">{$APP.$label}&nbsp;{$APP.LBL_PENDING_EVENTS}&nbsp;({$ACTIVITIES.1.noofactivities})</b><br />
                        <b>{$APP.LBL_SHOW}</b>
                        {if $ACTIVITIES.1.Title.0 eq 'today'}
	                        {$APP.LBL_TODAY}&nbsp;&nbsp;|&nbsp;&nbsp;<a href="#" onClick="getHomeActivities(1,'all');">{$APP.LBL_ALL}</a>
                        {else}
                                <a href="#" onClick="getHomeActivities(1,'today');">{$APP.LBL_TODAY}</a>&nbsp;&nbsp;|&nbsp;&nbsp;{$APP.LBL_ALL}
                        {/if}
		</td>

                <td><img src="{$IMAGE_PATH}pending_right.gif"></td>
        </tr>
        <tr>
                <td colspan="3" bgcolor="#FEF7C1" style="border-left:2px solid #A6A4A5;border-right:2px solid #A6A4A5;border-bottom:2px solid #A6A4A5;">
			<div id="pendingActivitiesEntry" style="overflow-y:auto;overflow-x:hidden;height:150px;width:100%">
                	<table width="100%" border="0" cellpadding="5" cellspacing="0">
                        	<tr>
                                <td style="border-bottom:1px dotted #dddddd;" colspan=3 align="right" valign=top>&nbsp;</td>
                                </tr>
                                {foreach item=entries name=entryloop from=$ACTIVITIES.1.Entries}
                                <tr>
					<td  style="border-bottom:1px dotted #dddddd;"  align="right" width="20">{math equation="x+1" x=$smarty.foreach.entryloop.index}</td>
                                        <td  style="border-bottom:1px dotted #dddddd;"  align="right" width="20">{$entries.IMAGE}</td>
                                        <td  style="border-bottom:1px dotted #dddddd;" align="left" valign="middle" colspan="2" width="85%"><b class="style_Gray">{$entries.0}</b>{*<br />{$entries.ACCOUNT_NAME*}</td>
                                </tr>
                                {/foreach}
                        </table>
			</div>
                </td>
	</tr>
</table>
<br>
{/if}

