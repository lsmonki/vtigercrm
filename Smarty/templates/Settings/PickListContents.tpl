	<b class="lvtHeaderText">{$MOD.LBL_PICKLIST_AVAIL} {$MODULE}</b><br>
	{$MOD.LBL_PICKLIST_AVAILINFO1} {$MODULE} {$MOD.LBL_PICKLIST_AVAILINFO2}<br>
	<table style="background-color: rgb(204, 204, 204);" class="small" border="0" cellpadding="5" cellspacing="1" width="90%"><tbody>
		
		{foreach item=picklists from=$PICKLIST_VALUES}
		<tr>
		{foreach item=picklistfields from=$picklists}
		{if $picklistfields neq ''}
		<td class="lvtCol" width="25%">{$picklistfields.fieldlabel}</td>
		<td class="lvtCol" width="5%"><a href="javascript:;" onclick="fetchEditPickList('{$MODULE}','{$picklistfields.fieldname}');">Edit</a></td>
		{else}
		<td class="lvtCol" colspan="2">&nbsp;</td>
		{/if}
		{/foreach}
		</tr>
		
		<tr bgcolor="#ffffff">
		{foreach item=picklistelements from=$picklists}
		{if $picklistelements neq ''}
		<td colspan="2" valign="top">
			<ul style="list-style-type: none;">
			{foreach item=elements from=$picklistelements.value}
			<li>{$elements}</li>
			{/foreach}
			</ul>		
		</td>
		{else}
		<td colspan="2">&nbsp;</td>
		{/if}
		{/foreach}
		</tr>
		{/foreach}
	
	</tbody></table>

