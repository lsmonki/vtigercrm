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
<div style="position:relative;display: block;" id="orgLay">
	<table border="0" cellpadding="5" cellspacing="0" width="100%">
	<tbody><tr>
	<td class="genHeaderSmall" align="left" width="40%">{$MOD.LBL_EDIT_PICKLIST} {$FIELDNAME}</td>
	<td align="right" width="60%"><img src="{$IMAGE_PATH}close.gif" align="middle" border="0" onclick="hide('editdiv');"></td>
	</tr>
	<tr><td colspan="2"><hr></td></tr>
	<tr>

	<td colspan="2" align="left" width="40%">
	{$MOD.LBL_PICKLIST_SAVEINFO}
	</td>
	</tr>
	<tr>
	<td colspan="2" align="left" width="40%">
	<textarea id="picklist_values" class="detailedViewTextBox" rows="10">
	{$ENTRIES}
	</textarea>
	</td>
	</tr>
	
	<tr><td style="border-bottom: 1px dashed rgb(204, 204, 204);" colspan="2">&nbsp;</td></tr>
	<tr>
	<td colspan="2" align="center">
	<input name="save" value=" &nbsp;Save&nbsp; " class="crmButton small save" onClick="SavePickList('{$FIELDNAME}','{$MODULE}');" type="button">
	<input name="cancel" value=" &nbsp;Cancel&nbsp; " class="crmButton small cancel" onClick="hide('editdiv');" type="button">
	</td>
	</tr>
	<tr><td colspan="2" style="border-top: 1px dashed rgb(204, 204, 204);">&nbsp;</td></tr>
	</tbody></table>

</div>
