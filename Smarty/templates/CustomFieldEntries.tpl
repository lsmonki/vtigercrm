{*
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/ *}
<table style="background-color: rgb(204, 204, 204);" class="small" border="0" cellpadding="5" cellspacing="1" width="95%">
	<tbody>
		<tr>
			<td class="lvtCol" width="5%">#</td>
			<td class="lvtCol" width="35%">Field Lable </td>
			<td class="lvtCol" width="50%">Field Type </td>
			<td class="lvtCol" width="10%">Tools</td>
		</tr>
		{foreach item=entries key=id from=$CFENTRIES}
			<tr class="lvtColData" onmouseover="this.className='lvtColDataHover'" onmouseout="this.className='lvtColData'" bgcolor="white">
			{foreach item=value from=$entries}
				<td nowrap>{$value}</td>
			{/foreach}
			</tr>
		{/foreach}
	</tbody>
</table><br/>
{if $MODULE eq 'Leads'}
	<table width="35%" style="border:1px dashed #CCCCCC;background-color:#FFFFEC;" cellpadding="5" cellspacing="0">
		<tr>
			<td style="padding:5px;" width="5%" >
				<img src="themes/blue/images/mapping.gif" align="absmiddle" /> </td>
				<td><span  class="genHeaderSmall">Filed Mapping</span><br />
					Field Mapping allows you to ....
				</td>
		</tr>
		<tr><td colspan="2" align="right"><input type="button" value=" Edit Field Mapping " class="classBtn" /></td></tr>
	</table>
{/if}
