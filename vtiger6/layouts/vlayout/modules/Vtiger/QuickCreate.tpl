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
{foreach key=index item=jsModel from=$SCRIPTS}
	<script type="{$jsModel->getType()}" src="{$jsModel->getSrc()}"></script>
{/foreach}
		
<div class="modelContainer">
<div class="modal-header">
	<button class="close" aria-hidden="true" data-dismiss="modal" type="button" title="{vtranslate('LBL_CLOSE')}">x</button>
    <h3>{vtranslate('LBL_QUICK_CREATE', $MODULE)} {vtranslate($SINGLE_MODULE, $MODULE)}</h3>
</div>
<form class="form-horizontal recordEditView contentsBackground" name="QuickCreate" method="post" action="index.php">
	<input type="hidden" name="module" value="{$MODULE}">
	<input type="hidden" name="action" value="SaveAjax">
	<div class="modal-body">
		<table class="massEditTable table table-bordered">
			<tr>
			{assign var=COUNTER value=0}
			{foreach key=FIELD_NAME item=FIELD_MODEL from=$RECORD_STRUCTURE name=blockfields}
				{assign var="isReferenceField" value=$FIELD_MODEL->getFieldDataType()}
				{assign var="refrenceList" value=$FIELD_MODEL->getReferenceList()}
				{assign var="refrenceListCount" value=count($refrenceList)}
				{if $COUNTER eq 2}
					</tr><tr>
					{assign var=COUNTER value=1}
				{else}
					{assign var=COUNTER value=$COUNTER+1}
				{/if}
				<td class='fieldLabel'>
					<label class='muted pull-right'>
					{if $FIELD_MODEL->isMandatory() eq true} <span class="redColor">*</span> {/if}
					{if $isReferenceField eq "reference"}
						{if $refrenceListCount > 1}
                            {assign var="DISPLAYID" value=$FIELD_MODEL->get('fieldvalue')}
                            {assign var="REFERENCED_MODULE_STRUCT" value=$FIELD_MODEL->getUITypeModel()->getReferenceModule($DISPLAYID)}
                            {if !empty($REFERENCED_MODULE_STRUCT)}
                                {assign var="REFERENCED_MODULE_NAME" value=$REFERENCED_MODULE_STRUCT->get('name')}
                            {/if}
							<select style="width: 150px;" class="chzn-select referenceModulesList" id="referenceModulesList">
								<optgroup>
									{foreach key=index item=value from=$refrenceList}
										<option value="{$value}" {if $value eq $REFERENCED_MODULE_NAME} selected {/if} >{vtranslate($value, $value)}</option>
									{/foreach}
								</optgroup>
							</select>
						{else}
							{vtranslate($FIELD_MODEL->get('label'), $MODULE)}
						{/if}
					{else}
						{vtranslate($FIELD_MODEL->get('label'), $MODULE)}
					{/if}
				</label>
				</td>
				<td class="fieldValue" {if $FIELD_MODEL->get('uitype') eq '19'} colspan="3" {assign var=COUNTER value=$COUNTER+1} {/if}>
					{include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getTemplateName(),$MODULE)}
				</td>
			{/foreach}
			</tr>
		</table>
	</div>
	<div class="modal-footer quickCreateActions">
		{assign var="EDIT_VIEW_URL" value=$MODULE_MODEL->getCreateRecordUrl()}
			<a class="cancelLink cancelLinkContainer pull-right" type="reset" data-dismiss="modal">{vtranslate('LBL_CANCEL', $MODULE)}</a>
			<button class="btn btn-success" type="submit"><strong>{vtranslate('LBL_SAVE', $MODULE)}</strong></button>
			<button class="btn" id="goToFullForm" data-edit-view-url="{$EDIT_VIEW_URL}" type="button"><strong>{vtranslate('LBL_GO_TO_FULL_FORM', $MODULE)}</strong></button>
	</div>
</form>
</div>
{/strip}