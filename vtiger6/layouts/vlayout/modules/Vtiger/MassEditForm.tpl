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
		<h3 id="massEditHeader">{vtranslate('LBL_MASS_EDITING', $MODULE)} {vtranslate($MODULE, $MODULE)}</h3>
	</div>
	<form class="form-horizontal contentsBackground" id="massEdit" name="MassEdit" method="post" action="index.php">
		<input type="hidden" name="module" value="{$MODULE}" />
		<input type="hidden" name="action" value="MassSave" />
		<input type="hidden" name="viewname" value="{$CVID}" />
		<input type="hidden" name="selected_ids" value={ZEND_JSON::encode($SELECTED_IDS)}>
		<input type="hidden" name="excluded_ids" value={ZEND_JSON::encode($EXCLUDED_IDS)}>
		<div name='massEditContent'>
			<div class="modal-body tabbable">
				<ul class="nav nav-tabs massEditTabs">
					{foreach key=BLOCK_LABEL item=BLOCK_FIELDS from=$RECORD_STRUCTURE name=blockIterator}
					{if $BLOCK_FIELDS|@count gt 0}
					<li {if $smarty.foreach.blockIterator.iteration eq 1}class="active"{/if}><a href="#block_{$smarty.foreach.blockIterator.iteration}" data-toggle="tab"><strong>{vtranslate($BLOCK_LABEL, $MODULE)}</strong></a></li>
					{/if}
					{/foreach}
				</ul>
				<div class="tab-content massEditContent">
				{foreach key=BLOCK_LABEL item=BLOCK_FIELDS from=$RECORD_STRUCTURE name=blockIterator}
					{if $BLOCK_FIELDS|@count gt 0}
					<div class="tab-pane {if $smarty.foreach.blockIterator.iteration eq 1}active{/if}" id="block_{{$smarty.foreach.blockIterator.iteration}}">
						<table class="massEditTable table table-bordered">
							<tr>
							{assign var=COUNTER value=0}
							{foreach key=FIELD_NAME item=FIELD_MODEL from=$BLOCK_FIELDS name=blockfields}
								{assign var="isReferenceField" value=$FIELD_MODEL->getFieldDataType()}
								{assign var="refrenceList" value=$FIELD_MODEL->getReferenceList()}
								{assign var="refrenceListCount" value=count($refrenceList)}
								{if $FIELD_MODEL->isEditable() eq true}
									{if $COUNTER eq 2}
										</tr><tr>
										{assign var=COUNTER value=1}
									{else}
										{assign var=COUNTER value=$COUNTER+1}
									{/if}
									<td class="fieldLabel alignMiddle">
									{if $FIELD_MODEL->isMandatory() eq true} <span class="redColor">*</span> {/if}
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
									{else}
										{vtranslate($FIELD_MODEL->get('label'), $MODULE)}
									{/if}
									&nbsp;&nbsp;
								</td>
								<td class="fieldValue" {if $FIELD_MODEL->get('uitype') eq '19'} colspan="3" {assign var=COUNTER value=$COUNTER+1} {/if}>
									{include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getTemplateName(),$MODULE)}
								</td>
							{/if}
							{/foreach}
							</tr>
						</table>
					</div>
					{/if}
				{/foreach}
				</div>
			</div>
		</div>
		{include file='ModalFooter.tpl'|@vtemplate_path:$MODULE}
	</form>
</div>
{/strip}