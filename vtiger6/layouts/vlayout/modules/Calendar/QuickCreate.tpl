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
<div class="modelContainer">
	<div class="modal-header">
		<button class="close" aria-hidden="true" data-dismiss="modal" type="button" title="{vtranslate('LBL_CLOSE')}">x</button>
		<h3>{vtranslate('LBL_QUICK_CREATE', $MODULE)} {vtranslate($MODULE, $MODULE)}</h3>
	</div>
<form class="form-horizontal recordEditView" id="quickCreate" name="QuickCreate" method="post" action="index.php">
	<input type="hidden" name="module" value="{$MODULE}">
	<input type="hidden" name="action" value="SaveAjax">
	<!-- Random number is used to make specific tab is opened -->
	{assign var="RAND_NUMBER" value=rand()}
	<div class="modal-body tabbable" style="padding:0px">
		<ul class="nav nav-pills" style="margin-bottom:0px;padding-left:5px">
			<li class="active">
				<a href="javascript:void(0);" data-target=".EventsQuikcCreateContents_{$RAND_NUMBER}" data-toggle="tab">{vtranslate('LBL_EVENT',$MODULE)}</a>
			</li>
			<li class="">
				<a href="javascript:void(0);" data-target=".CalendarQuikcCreateContents_{$RAND_NUMBER} " data-toggle="tab">{vtranslate('LBL_TASK',$MODULE)}</a>
			</li>
		</ul>
		<div class="tab-content">
			{assign var="CALENDAR_MODULE_MODEL" value=$QUICK_CREATE_CONTENTS['Calendar']['moduleModel']}
			{foreach item=MODULE_DETAILS key=MODULE_NAME from=$QUICK_CREATE_CONTENTS}
			<div class="{$MODULE_NAME}QuikcCreateContents_{$RAND_NUMBER} tab-pane {if $MODULE_NAME eq 'Events'} active in {/if}fade">
				{assign var="RECORD_STRUCTURE_MODEL" value=$QUICK_CREATE_CONTENTS[$MODULE_NAME]['recordStructureModel']}
				{assign var="RECORD_STRUCTURE" value=$QUICK_CREATE_CONTENTS[$MODULE_NAME]['recordStructure']}
				{assign var="MODULE_MODEL" value=$QUICK_CREATE_CONTENTS[$MODULE_NAME]['moduleModel']}
				<div style='margin:5px'>
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
							<td class="fieldLabel alignMiddle">
								{if {$isReferenceField} eq "reference"}
									{if $refrenceListCount > 1}
										<select style="width: 150px;" class="chzn-select" id="referenceModulesList">
											<optgroup>
												{foreach key=index item=value from=$refrenceList}
													<option value="{$value}">{vtranslate($value, $value)}</option>
												{/foreach}
											</optgroup>
										</select>
									{else}
										{vtranslate($FIELD_MODEL->get('label'), $MODULE)}
									{/if}
								{vtranslate($FIELD_MODEL->get('label'), $MODULE)}
								{else}
									{vtranslate($FIELD_MODEL->get('label'), $MODULE)}
								{/if}
							{if $FIELD_MODEL->isMandatory() eq true} <span class="redColor"></span> {/if}
							</td>
							<td class="fieldValue" {if $FIELD_MODEL->get('uitype') eq '19'} colspan="3" {assign var=COUNTER value=$COUNTER+1} {/if}>
								{include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getTemplateName(),$MODULE_NAME)}
							</td>
						{/foreach}
						</tr>
					</table>
				</div>
				<div class="modal-footer quickCreateActions">
					{if $MODULE_NAME eq 'Calendar'}
						{assign var="EDIT_VIEW_URL" value=$CALENDAR_MODULE_MODEL->getCreateTaskRecordUrl()}
					{else}
						{assign var="EDIT_VIEW_URL" value=$CALENDAR_MODULE_MODEL->getCreateEventRecordUrl()}
					{/if}
						<a class="cancelLink cancelLinkContainer pull-right" type="reset" data-dismiss="modal">{vtranslate('LBL_CANCEL', $MODULE)}</a>
						<button class="btn btn-success" type="submit"><strong>{vtranslate('LBL_SAVE', $MODULE)}</strong></button>
						<button class="btn" id="goToFullForm" type="button" data-edit-view-url="{$EDIT_VIEW_URL}"><strong>{vtranslate('LBL_GO_TO_FULL_FORM', $MODULE)}</strong></button>
				</div>
			</div>
			{/foreach}
		</div>
		</div>
</form>
</div>
{/strip}