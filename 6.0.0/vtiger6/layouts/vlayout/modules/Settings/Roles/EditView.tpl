{*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************}
{strip}
<div class="modal-header">
	<button class="close vtButton" data-dismiss="modal">x</button>
	<h3>{vtranslate($MODULE, $QUALIFIED_MODULE)}</h3>
</div>

<form name="EditRole" action="index.php" method="post" id="EditView" class="form-horizontal">
	<input type="hidden" name="module" value="Roles">
	<input type="hidden" name="action" value="Save">
	<input type="hidden" name="parent" value="Settings">
	<input type="hidden" name="record" value="{$RECORD_MODEL->getId()}">
	<input type="hidden" name="mode" value="{$MODE}">
	{if $RECORD_MODEL->getParent() neq null}
	<input type="hidden" name="parent_roleid" value="{$RECORD_MODEL->getParent()->getId()}">
	{/if}

	<div class="modal-body tabbable">
		<table class="table table-striped table-bordered table-condensed">
		<tbody>
			<tr class="listViewActionsDiv">
				<th colspan="2">New Role</th>
			</tr>
			<tr>
				<td class="fieldLabel">Name <span class="redColor">*</span></td>
				<td class="fieldValue">
					<input class="input-large" required="true" name="rolename" value="{$RECORD_MODEL->getName()}">
				</td>
			</tr>
			<tr>
				<td class="fieldLabel">Reports To</td>
				<td class="fieldValue">
					<input type="hidden" name="parent_roleid" value="{$RECORD_MODEL->getParent()->getId()}">
					<input type="text" class="input-large" name="parent_roleid_display" value="{$RECORD_MODEL->getParent()->getName()}" readonly>
				</td>
			</tr>
			<tr>
				<td class="fieldLabel">Profile</td>
				<td class="fieldValue">
					{assign var="ROLE_PROFILES" value=$RECORD_MODEL->getProfiles()}
					<select class="chzn-select row-fluid" multiple="true" name="profiles[]" required="true" data-placeholder="Choose Profiles...">
						{foreach from=$ALL_PROFILES item=PROFILE}
							<option value="{$PROFILE->getId()}" {if isset($ROLE_PROFILES[$PROFILE->getId()])}selected="true"{/if}>{$PROFILE->getName()}</option>
						{/foreach}
					</select>
				</td>
			</tr>
		</tbody>
		</table>
	</div>
				
	<div class="modal-footer">
		<button class="vtButton saveButton" type="submit">{vtranslate('LBL_SAVE', $QUALIFIED_MODULE)}</button>
		<a class="cancelLink" type="reset" data-dismiss="modal">{vtranslate('LBL_CANCEL', $QUALIFIED_MODULE)}</a>
	</div>

</form>
{/strip}