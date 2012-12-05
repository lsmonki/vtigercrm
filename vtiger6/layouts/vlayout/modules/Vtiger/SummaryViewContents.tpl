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
<div class="recordDetails">
	<table class="summary-table">
		<tbody>
		{foreach item=FIELD_MODEL key=FIELD_NAME from=$SUMMARY_RECORD_STRUCTURE['SUMMARY_FIELDS']}	
			{if $FIELD_MODEL->get('name') neq 'assigned_user_id' && $FIELD_MODEL->get('name') neq 'modifiedtime' && $FIELD_MODEL->get('name') neq 'createdtime'}
				<tr class="summaryViewEntries">
					<td class="fieldLabel"><label class="muted">{vtranslate($FIELD_MODEL->get('label'),$MODULE_NAME)}</label></td>
					<td class="fieldValue">
						<span class="value">
							{include file=$FIELD_MODEL->getUITypeModel()->getDetailViewTemplateName()|@vtemplate_path FIELD_MODEL=$FIELD_MODEL USER_MODEL=$USER_MODEL MODULE=$MODULE_NAME RECORD=$RECORD}
						</span>&nbsp;&nbsp;
						{if $FIELD_MODEL->isEditable() eq 'true' && ($FIELD_MODEL->getFieldDataType()!=Vtiger_Field_Model::REFERENCE_TYPE) && $IS_AJAX_ENABLED && $FIELD_MODEL->isAjaxEditable() eq 'true'}
							<span class="summaryViewEdit cursorPointer"><i class="icon-pencil" title="{vtranslate('LBL_EDIT',$MODULE_NAME)}"></i></span>
							<span class="hide edit">
								{include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getTemplateName(),$MODULE_NAME) FIELD_MODEL=$FIELD_MODEL USER_MODEL=$USER_MODEL MODULE=$MODULE_NAME}
								{if $FIELD_MODEL->getFieldDataType() eq 'multipicklist'}
									<input type="hidden" class="fieldname" value='{$FIELD_MODEL->get('name')}[]' data-prev-value='{$FIELD_MODEL->getDisplayValue($FIELD_MODEL->get('fieldvalue'))}' />
								 {else}
									 <input type="hidden" class="fieldname" value='{$FIELD_MODEL->get('name')}' data-prev-value='{$FIELD_MODEL->get('fieldvalue')}' />
								 {/if}
							</span>
						{/if}
					</td>
				</tr>
			{/if}
		{/foreach}
		</tbody>
	</table>
	<hr>
	<div class="row-fluid textAlignCenter">
		<p>
			<small>
				<em>{vtranslate('LBL_CREATED_ON',$MODULE_NAME)} {$RECORD->getDisplayValue('createdtime')}</em>
			</small>
			<span>,&nbsp;&nbsp;</span>
			<small>
				<em>{vtranslate('LBL_MODIFIED_ON',$MODULE_NAME)} {$RECORD->getDisplayValue('modifiedtime')}</em>
			</small>
		</p>
		<p>
			<strong>{vtranslate('LBL_OWNER',$MODULE_NAME)} : </strong>
			<strong>{getOwnerName($RECORD->get('assigned_user_id'))}</strong>
		</p>
	</div>
</div>
{/strip}