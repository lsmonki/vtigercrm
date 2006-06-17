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
<div id="EditInv" class="fixedLay">
<table width="100%">
	<tbody>
	<tr>
		<td class="genHeaderSmall" align="left" colspan="3">{$NOTIFY_DETAILS.label}</td>
		<td align="right"><a href="javascript:hide('editdiv');"><img src="{$IMAGE_PATH}close.gif" align="middle" border="0"></a></td>
	</tr>
	<tr><td colspan="4"><hr></td></tr>

	<tr><td colspan="4" class="genHeaderSmall">
		<b><font class="required">*</font>Note: Donot remove or alter the values within {ldelim}  {rdelim}</b>
		</td>
	</tr>

	<tr><td style="border-bottom: 1px dashed rgb(204, 204, 204);" colspan="4">&nbsp;</td></tr>
	<tr><td colspan="4">&nbsp;</td></tr>
	<tr>
		<td align="right" colspan="2"><b>{$MOD.LBL_SUBJECT} : </b></td>
		<td align="left"><input class="txtBox" id="notifysubject" name="notifysubject" value="{$NOTIFY_DETAILS.subject}" size="40" type="text"></td>
	</tr>
	<tr><td colspan="4">&nbsp;</td></tr>
	<tr>
		<td align="right" valign="top" colspan="2" ><b>{$MOD.LBL_MESSAGE} : </b></td>
		<td align="left"><textarea id="notifybody" name="notifybody" class="txtBox" rows="5" cols="40">{$NOTIFY_DETAILS.body}</textarea></td>
	</tr>
	<tr><td colspan="4" style="border-bottom: 1px dashed rgb(204, 204, 204);">&nbsp;</td></tr>
	<tr>
		<td colspan="4" align="center">
	<input name="save" value="{$APP.LBL_SAVE_BUTTON_LABEL}" class="crmButton small save" type="button" onClick="fetchSaveNotify('{$NOTIFY_DETAILS.id}')">
	<input name="cancel" value="{$APP.LBL_CANCEL_BUTTON_LABEL}" class="crmButton small cancel" type="button" onClick="hide('editdiv');">
		</td>
	</tr>
	<tr><td colspan="4" style="border-top: 1px dashed rgb(204, 204, 204);">&nbsp;</td></tr>
</tbody>
</table>
</div>
