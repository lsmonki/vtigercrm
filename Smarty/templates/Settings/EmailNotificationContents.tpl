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
<table style="background-color: rgb(204, 204, 204);" class="small" border="0" cellpadding="5" cellspacing="1" width="100%">
	<tbody>
	<tr>
	<td class="lvtCol" width="5%">#</td>
	<td class="lvtCol" width="35%">{$MOD.LBL_NOTIFICATION}</td>
	<td class="lvtCol" width="50%">{$MOD.LBL_DESCRIPTION}</td>
	<td class="lvtCol" width="10%">{$MOD.LBL_ACTIVE}</td>
	<td class="lvtCol" width="10%">{$MOD.LBL_TOOL}</td>
	</tr>
	{foreach name=notifyfor item=elements from=$NOTIFICATION}
	<tr class="lvtColData" onmouseover="this.className='lvtColDataHover'" onmouseout="this.className='lvtColData'" bgcolor="white">
	<td>{$smarty.foreach.notifyfor.iteration}</td>
	<td>{$elements.label}</td>
	<td>{$elements.schedulename}</td>
	<td>{$elements.active}</td>
	<td onClick="fetchEditNotify('{$smarty.foreach.notifyfor.iteration}');"><img src="{$IMAGE_PATH}editfield.gif" title="edit"></td>
	</tr>
	{/foreach}
	</tbody>
	</table>

