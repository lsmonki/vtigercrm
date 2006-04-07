<table style="background-color: rgb(204, 204, 204);" class="small" border="0" cellpadding="5" cellspacing="1" width="100%">
	<tbody>
	<tr>
	<td class="lvtCol" width="5%">#</td>
	<td class="lvtCol" width="35%">Notification</td>
	<td class="lvtCol" width="50%">Description</td>
	<td class="lvtCol" width="10%">Active</td>
	<td class="lvtCol" width="10%">Tool</td>
	</tr>
	{foreach name=notifyfor item=elements from=$NOTIFICATION}
	<tr class="lvtColData" onmouseover="this.className='lvtColDataHover'" onmouseout="this.className='lvtColData'" bgcolor="white">
	<td>{$smarty.foreach.notifyfor.iteration}</td>
	<td>{$elements.label}</td>
	<td>{$elements.schedulename}</td>
	<td>{$elements.active}</td>
	<td onClick="fetchEditNotify('{$smarty.foreach.notifyfor.iteration}');"><img src="{$IMAGE_PATH}editfield.gif"></td>
	</tr>
	{/foreach}
	</tbody>
	</table>

