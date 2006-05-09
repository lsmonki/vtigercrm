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

<tr>
	<td align="right"><img src="{$IMAGE_PATH}one.gif" /></td>
	<td><b class="lvtHeaderText">Select Module</b></td>
</tr>
<tr>
	<form name="selectModule">
        <td>&nbsp;</td>
	<td>
	        Select the CRM module to show CustomFields :
                <select name="pick_module" class="importBox" onChange="getCustomFieldList(this)">
			{foreach key=sel_value item=value from=$MODULES}
				{if $MODULE eq $sel_value}
					{assign var = "selected_val" value="selected"}
				{else}
					{assign var = "selected_val" value=""}
				{/if}
				<option value="{$sel_value}" {$selected_val}>{$value}</option>
			{/foreach}
		</select>
	</td>
	</form>
</tr>
