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
	<h3>{vtranslate('LBL_DELETE_GROUP', $MODULE)} - {$RECORD_MODEL->getName()}</h3>
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
				<th colspan="2">{vtranslate('LBL_TRANSFORM_OWNERSHIP', $QUALIFIED_MODULE)}</th>
			</tr>
			<tr>
				<td class="fieldLabel">{vtranslate('LBL_TO_OTHER_GROUP', $QUALIFIED_MODULE)}<span class="redColor">*</span></td>
				<td class="fieldValue">
					<select id="transfer_record" name="transfer_record" class="chzn-select">
						<optgroup label="{vtranslate('LBL_USERS', $QUALIFIED_MODULE)}">
						{foreach from=$ALL_USERS key=USER_ID item=USER_MODEL}
						<option value="{$USER_ID}">{$USER_MODEL->getName()}</option>
						{/foreach}
						</optgroup>
						<optgroup label="{vtranslate('LBL_GROUPS', $QUALIFIED_MODULE)}">
						{foreach from=$ALL_GROUPS key=GROUP_ID item=GROUP_MODEL}
							{if $RECORD_MODEL->getId() != $GROUP_ID }
							<option value="{$GROUP_ID}">{$GROUP_MODEL->getName()}</option>
							{/if}
						{/foreach}
						</optgroup>
					</select>
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
{/strip}