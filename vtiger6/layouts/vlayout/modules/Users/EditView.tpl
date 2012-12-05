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
<div>
	<form class="form-horizontal recordEditView equalSplit" id="EditView" name="EditView" method="post" enctype="multipart/form-data" action="index.php">
		<input type="hidden" name="module" value="{$MODULE}" />
		<input type="hidden" name="action" value="Save" />
		<input type="hidden" name="record" value="{$RECORD_ID}" />
		<input type=hidden name="timeFormatOptions" data-value='{$DAY_STARTS}' />
		{if $IS_RELATION_OPERATION }
			<input type="hidden" name="sourceModule" value="{$SOURCE_MODULE}" />
			<input type="hidden" name="sourceRecord" value="{$SOURCE_RECORD}" />
			<input type="hidden" name="relationOperation" value="{$IS_RELATION_OPERATION}" />
		{/if}
		<div class="contentHeader row-fluid">
		{assign var=SINGLE_MODULE_NAME value='Single_'|cat:$MODULE}
		{if $RECORD_ID neq ''}
			<span class="span8 font-x-x-large textOverflowEllipsis" title='{vtranslate('LBL_EDITING', $MODULE)} {vtranslate($SINGLE_MODULE_NAME, $MODULE)} "{$RECORD_STRUCTURE_MODEL->getRecordName()}"'>{vtranslate('LBL_EDITING', $MODULE)} {vtranslate($SINGLE_MODULE_NAME, $MODULE)} "{$RECORD_STRUCTURE_MODEL->getRecordName()}"</span>
		{else}
			<span class="span8 font-x-x-large textOverflowEllipsis" title="{vtranslate('LBL_CREATING_NEW', $MODULE)} {vtranslate($SINGLE_MODULE_NAME, $MODULE)}">{vtranslate('LBL_CREATING_NEW', $MODULE)} {vtranslate($SINGLE_MODULE_NAME, $MODULE)}</span>
		{/if}
			<div class='pull-right'>
				&nbsp;&nbsp;<button class="btn btn-success" type="submit"><strong>{vtranslate('LBL_SAVE', $MODULE)}</strong></button>
				<a class="cancelLink" type="reset" onclick="javascript:window.history.back();">{vtranslate('LBL_CANCEL', $MODULE)}</a>
			</div>
			<span class="pull-right">
				<div class='btn-group' title="{vtranslate('LBL_DISPLAY_TYPE', 'Vtiger')}">
					<a class='btn dropdown-toggle' data-toggle='dropdown' href='#'>
						<span id='currentWidthType'><i class='icon-th-list'></i></span>&nbsp;<span class='caret'></span>
					</a>
					<ul class='dropdown-menu pull-right' id='widthType' style='min-width:100px;'>
						<li data-class='wideWidthType' style="margin-left:7px" title="{vtranslate('LBL_DISPLAY_WIDETYPE', 'Vtiger')}">
							<i class='icon-th-list'></i>  {vtranslate('LBL_DISPLAY_WIDETYPE', 'Vtiger')}
						</li>
						<li data-class='mediumWidthType' style="margin-left:7px" title="{vtranslate('LBL_DISPLAY_MEDIUMTYPE', 'Vtiger')}">
							<i class='icon-list'></i>  {vtranslate('LBL_DISPLAY_MEDIUMTYPE', 'Vtiger')}
						</li>
						<li data-class='narrowWidthType' style="margin-left:7px" title="{vtranslate('LBL_DISPLAY_NARROWTYPE', 'Vtiger')}">
							<i class='icon-list-alt'></i>  {vtranslate('LBL_DISPLAY_NARROWTYPE', 'Vtiger')}
						</li>
					</ul>
				</div>
			</span>
		</div>
		<div style='padding:10px'>
			<table class="table table-bordered marginLeftZero">
			{foreach key=BLOCK_LABEL item=BLOCK_FIELDS from=$RECORD_STRUCTURE}
				{if $BLOCK_FIELDS|@count gt 0}
				<tr class="listViewActionsDiv">
					<th colspan="4">{vtranslate($BLOCK_LABEL, $MODULE)}</th>
				</tr>
				<tr>
				{assign var=COUNTER value=0}
				{foreach key=FIELD_NAME item=FIELD_MODEL from=$BLOCK_FIELDS name=blockfields}
					{assign var="isReferenceField" value=$FIELD_MODEL->getFieldDataType()}
					{assign var="refrenceList" value=$FIELD_MODEL->getReferenceList()}
					{assign var="refrenceListCount" value=count($refrenceList)}
					{if $COUNTER eq 2}
						</tr><tr>
						{assign var=COUNTER value=1}
					{else}
						{assign var=COUNTER value=$COUNTER+1}
					{/if}
					<td class="fieldLabel">
					{if {$isReferenceField} eq "reference"}
						{if $refrenceListCount > 1}
							<select style="width: 150px;" class="chzn-select" id="referenceModulesList">
								<optgroup>
									{foreach key=index item=value from=$refrenceList}
										<option value="{$value}">{$value}</option>
									{/foreach}
								</optgroup>
							</select>
						{/if}
						{vtranslate($FIELD_MODEL->get('label'), $MODULE)}
					{else}
						{vtranslate($FIELD_MODEL->get('label'), $MODULE)}
					{/if}
					{if $FIELD_MODEL->isMandatory() eq true} <span class="redColor">*</span> {/if}
					</td>
					<td class="fieldValue" {if $FIELD_MODEL->get('uitype') eq '19'} colspan="3" {assign var=COUNTER value=$COUNTER+1} {/if}>
						{include file=$FIELD_MODEL->getUITypeModel()->getTemplateName()|@vtemplate_path}
					</td>
				{/foreach}
				</tr>
				{/if}
			{/foreach}
			</table>
			<div class='pull-right'>
				<button class="btn btn-success" type="submit"><strong>{vtranslate('LBL_SAVE', $MODULE)}</strong></button>
				<a class="cancelLink" type="reset" onclick="javascript:window.history.back();">{vtranslate('LBL_CANCEL', $MODULE)}</a>
			</div>
		</div>
    </form>
</div>
{/strip}