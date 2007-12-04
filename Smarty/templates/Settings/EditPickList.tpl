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
<div style="position:relative;display: block;" id="orgLay" class="layerPopup">
	<table border="0" cellpadding="5" cellspacing="0" width="100%" class="layerHeadingULine">
		<tr>
			<td class="layerPopupHeading" align="left" width="40%" nowrap>{$MOD.LBL_EDIT_PICKLIST} - {$FIELDLABEL}</td>
			<td align="right" width="60%"><img src="{$IMAGE_PATH}close.gif" align="middle" border="0" onclick="hide('editdiv');"></td>
		</tr>
	</table>
	
	<table border=0 cellspacing=0 cellpadding=5 width=95% align=center> 
	<tr>
		<td class=small >
			<table border=0 celspacing=0 cellpadding=5 width=100% align=center bgcolor=white>
				<tr>
					<td colspan="2" align="left" width="40%">
						{$MOD.LBL_PICKLIST_SAVEINFO}
					</td>
				</tr>
				<tr>
					<td colspan="2" align="left" width="40%">
						<textarea id="picklist_values" class="detailedViewTextBox" rows="10" align="left">{$ENTRIES}</textarea>
					</td>
				</tr>
					{if $NON_EDITABLE_ENTRIES neq ''}
				<tr>
					<td colspan="2" align="left" width="40%"><b><u>{$MOD.LBL_NON_EDITABLE_PICKLIST_ENTRIES} :</u></b><br> {$NON_EDITABLE_ENTRIES} </td>
				</tr>
					{/if}
			</table>
		</td>
	</tr>
	</table>
	<table border=0 cellspacing=0 cellpadding=5 width=100% class="layerPopupTransport">
		<tr>
			<td  colspan=2 align="center">
			<input name="save" value=" &nbsp;{$APP.LBL_SELECT_PARENTROLE}&nbsp; " class="crmButton small save"  type="button"  onclick='return window.open("index.php?module=Users&action=UsersAjax&type=picklist&pick_fieldname={$FIELDNAME}&picklistmodule={$MODULE}&pick_uitype={$UITYPE}&file=RolePopup&parenttab=Settings","roles_popup_window","height=425,width=640,toolbar=no,menubar=no,dependent=yes,resizable =no");'>	
			<input type ="hidden" name="type" value="picklist">
			<input name="save" value=" &nbsp;{$APP.LBL_SAVE_BUTTON_LABEL}&nbsp; " class="crmButton small save" onClick="return picklist_validate('{$EDITABLE_MODE}','{$FIELDNAME}','{$MODULE}', {$UITYPE});" type="button">
			<input name="cancel" value=" &nbsp;{$APP.LBL_CANCEL_BUTTON_LABEL}&nbsp; " class="crmButton small cancel" onClick="hide('editdiv');" type="button">
			</td>
		</tr>
	</table>

</div>
