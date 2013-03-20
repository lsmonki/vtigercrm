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
{strip}
<div class="modal-header">
	<button class="close vtButton" data-dismiss="modal">×</button>
	<h3>{vtranslate('LBL_DELETE_ROLE', $MODULE)} - {$RECORD_MODEL->getName()}</h3>
</div>
<form class="form-horizontal" id="EditView" name="AddComment" method="post" action="index.php">
	<input type="hidden" name="module" value="{$MODULE}" />
	<input type="hidden" name="parent" value="Settings" />
	<input type="hidden" name="action" value="Delete" />
	<input type="hidden" name="record" id="record" value="{$RECORD_MODEL->getId()}" />

	<div class="modal-body tabbable">
		<table class="table table-striped table-bordered table-condensed">
		<tbody>
			<tr class="listViewActionsDiv">
				<th colspan="2">Transfer ownership</th>
			</tr>
			<tr>
				<td class="fieldLabel">To Other Role <span class="redColor">*</span></td>
				<td class="fieldValue">
					<input id="transfer_record" name="transfer_record" type="hidden" value="" class="sourceField">
					<input id="transfer_record_display" name="transfer_record_display" readonly type="text" class="input-medium" required value="">
					&nbsp;
					<input type="image" src="{vimage_path('btnColorAdd.png', $MODULE)}" class="alignMiddle cursorPointer relatedPopup"
						data-field="transfer_record" data-action="popup" data-url="{$RECORD_MODEL->getPopupWindowUrl()}&type=Transfer" />
					<input type="image" src="{vimage_path('clear.png', $MODULE)}" class="alignMiddle cursorPointer"
						onClick="this.form.transfer_record.value=''; this.form.transfer_record_display.value=''; return false;" />
				</td>
			</tr>
		</tbody>
		</table>
	</div>
	<div class="modal-footer">
		<button class="vtButton saveButton" type="submit">{vtranslate('LBL_SAVE', $MODULE)}</button>
		<a class="cancelLink" type="reset" data-dismiss="modal">{vtranslate('LBL_CANCEL', $MODULE)}</a>
	</div>
</form>
		
<script type="text/javascript">
	jQuery('body').ready(Settings_Roles_Js.initDeleteView);
</script>
{/strip}