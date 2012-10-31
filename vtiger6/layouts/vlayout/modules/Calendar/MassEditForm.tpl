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
<div id="massEditContainer" class='modelContainer'>
	<div class="modal-header">
		<button data-dismiss="modal" class="close" title="{vtranslate('LBL_CLOSE')}">x</button>
		<h3 id="massEditHeader">{vtranslate('LBL_CHANGE_OWNER', $MODULE)}</h3>
	</div>
	<form class="form-horizontal calendarMassEdit contentsBackground" id="massEdit" name="MassEdit" method="post" action="index.php">
		<input type="hidden" name="module" value="{$MODULE}" />
		<input type="hidden" name="action" value="MassSave" />
		<input type="hidden" name="viewname" value="{$CVID}" />
		<input type="hidden" name="selected_ids" value={ZEND_JSON::encode($SELECTED_IDS)}>
		<input type="hidden" name="excluded_ids" value={ZEND_JSON::encode($EXCLUDED_IDS)}>

		<div class="controlElements">
			<div class="row-fluid">
				{assign var=ASSIGNED_USER_FIELD value=$RECORD_STRUCTURE_MODEL->getModule()->getField('assigned_user_id')}
				<span class="span3">
					{vtranslate($ASSIGNED_USER_FIELD->get('label'),$MODULE)}
				</span>
				<span class="">
				</span>
				<span class="span9 offset2">
				<input type="hidden" name="assigned_user_id_mass_edit_check" value="on"/>

				{assign var=ACCESSIBLE_USER_LIST value=$USER_MODEL->getAccessibleUsers()}
				{assign var=ACCESSIBLE_GROUP_LIST value=$USER_MODEL->getAccessibleGroups()}

				<select class="chzn-select row-fluid" name="assigned_user_id" data-validation-engine="validate[]">
					<optgroup label="{vtranslate('LBL_USERS')}">
						{foreach key=OWNER_ID item=OWNER_NAME from=$ACCESSIBLE_USER_LIST}
								<option value="{$OWNER_ID}">
								{$OWNER_NAME}
								</option>
						{/foreach}
					</optgroup>
					<optgroup label="{vtranslate('LBL_GROUPS')}">
						{foreach key=OWNER_ID item=OWNER_NAME from=$ACCESSIBLE_GROUP_LIST}
							<option value="{$OWNER_ID}" >
							{$OWNER_NAME}
							</option>
						{/foreach}
					</optgroup>
				</select>
				</span>
			</div>
		</div>
		{include file='ModalFooter.tpl'|@vtemplate_path:$MODULE}
	</form>
</div>
{/strip}