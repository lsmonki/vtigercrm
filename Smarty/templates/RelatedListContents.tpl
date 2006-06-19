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
{foreach key=header item=detail from=$RELATEDLISTS}
{if $detail ne ''}
	{foreach key=header item=detail from=$detail}
		{if $header eq 'header'}
			<table border=0 cellspacing=1 cellpadding=3 width=100% style="background-color:#eaeaea;" class="small">
				<tr style="height:25px" bgcolor=white>
				{foreach key=header item=headerfields from=$detail}
					<td class="lvtCol">{$headerfields}</td>
				{/foreach}
                                </tr>
		{elseif $header eq 'entries'}
			{foreach key=header item=detail from=$detail}
				<tr bgcolor=white>
				{foreach key=header item=listfields from=$detail}
	                                 <td>{$listfields}</td>
				{/foreach}
				</tr>
			{/foreach}
			</table>
		{/if}
	{/foreach}
{else}
	<table style="background-color:#eaeaea;color:eeeeee" border="0" cellpadding="3" cellspacing="1" width="100%" class="small">
		<tr style="height: 25px;" bgcolor="white">
			<td><i>{$APP.LBL_NONE_INCLUDED}</i></td>
		</tr>
	</table>
{/if}
{/foreach}
