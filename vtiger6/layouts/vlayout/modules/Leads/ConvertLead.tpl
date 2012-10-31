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
<div id="convertLeadContainer" class='modelContainer'>
	{if !$CONVERT_LEAD_FIELDS['Accounts'] && !$CONVERT_LEAD_FIELDS['Contacts']}
		<input type="hidden" id="convertLeadErrorTitle" value="{vtranslate('LBL_CONVERT_LEAD_ERROR_TITLE',$MODULE)}"/>
		<input id="convertLeadError" class="convertLeadError" type="hidden" value="{vtranslate('LBL_CONVERT_LEAD_ERROR',$MODULE)}"/>
	{else}
		<div class="modal-header">
			<button data-dismiss="modal" class="close" title="{vtranslate('LBL_CLOSE')}">x</button>
			<h3>{vtranslate('LBL_CONVERT_LEAD', $MODULE)} : {$RECORD->getName()}</h3>
		</div>
		<form class="form-horizontal contentsBackground" id="convertLeadForm" method="post" action="index.php">
			<input type="hidden" name="module" value="{$MODULE}"/>
			<input type="hidden" name="view" value="SaveConvertLead"/>
			<input type="hidden" name="record" value="{$RECORD->getId()}"/>
			<input type="hidden" name="modules" value=''/>
			<div class="modal-body accordion" id="leadAccordion">
				{foreach item=MODULE_FIELD_MODEL key=MODULE_NAME from=$CONVERT_LEAD_FIELDS}
					<div class="accordion-group convertLeadModules">
						<div class="header accordion-heading">
							<div data-parent="#leadAccordion" data-toggle="collapse" class="accordion-toggle table-bordered moduleSelection" href="#{$MODULE_NAME}_FieldInfo">
								<input id="{$MODULE_NAME}Module" class="convertLeadModuleSelection alignBottom" data-module="{vtranslate($MODULE_NAME,$MODULE_NAME)}" value="{$MODULE_NAME}" type="checkbox" {if $MODULE_NAME != 'Potentials'} checked="" {/if}/>
									{if $MODULE_NAME eq 'Accounts'}&nbsp;{vtranslate('LBL_CREATE', $MODULE)}&nbsp;{vtranslate('SINGLE_Accounts', $MODULE_NAME)}
										{elseif $MODULE_NAME eq 'Contacts'}&nbsp;{vtranslate('LBL_CREATE', $MODULE)}&nbsp;{vtranslate('SINGLE_Contacts', $MODULE_NAME)}
										{elseif $MODULE_NAME eq 'Potentials'}&nbsp;{vtranslate('LBL_CREATE', $MODULE)}&nbsp;{vtranslate('SINGLE_Potentials', $MODULE_NAME)}
									{/if}
									<span class="pull-right"><i class="iconArrow {if $CONVERT_LEAD_FIELDS['Accounts'] && $MODULE_NAME == "Accounts"} icon-chevron-up {elseif !$CONVERT_LEAD_FIELDS['Accounts'] && $MODULE_NAME == "Contacts"} icon-chevron-up {else} icon-chevron-down {/if}alignBottom"></i></span>
							</div>
						</div>
						<div id="{$MODULE_NAME}_FieldInfo" class="{$MODULE_NAME}_FieldInfo accordion-body collapse fieldInfo {if $CONVERT_LEAD_FIELDS['Accounts'] && $MODULE_NAME == "Accounts"} in {elseif !$CONVERT_LEAD_FIELDS['Accounts'] && $MODULE_NAME == "Contacts"} in {/if}">
							<table class="table table-bordered moduleBlock">
								{foreach item=FIELD_MODEL from=$MODULE_FIELD_MODEL}
								<tr>
									<td class="fieldLabel">
										<label class='muted pull-right marginRight10px'>
											{vtranslate($FIELD_MODEL->get('label'), $MODULE_NAME)}
											{if $FIELD_MODEL->isMandatory() eq true} <span class="redColor">*</span> {/if}
										</label>
									</td>
									<td class="fieldValue">
										{if $FIELD_MODEL->getFieldDataType() eq 'reference'}
											<input type="text" class="reference" readonly name="{$FIELD_MODEL->getName()}" value="{$FIELD_MODEL->get('fieldvalue')}" />
										{else}
											{include file=$FIELD_MODEL->getUITypeModel()->getTemplateName()|@vtemplate_path}
										{/if}
									</td>
								</tr>
								{/foreach}
							</table>
						</div>
					</div>
				{/foreach}
				<div class="convertLeadInfo">
					<table class="table table-bordered">
						{assign var=FIELD_MODEL value=$ASSIGN_TO}
						<tr>
							<td class="fieldLabel">
								<label class='muted pull-right marginRight10px'>
									{vtranslate($FIELD_MODEL->get('label'), $MODULE_NAME)}
									{if $FIELD_MODEL->isMandatory() eq true} <span class="redColor">*</span> {/if}
								</label>
							</td>
							<td class="fieldValue">
								{include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getTemplateName(),$MODULE)}
							</td>
						</tr>
						<tr>
							<td>{vtranslate('LBL_TRANSFER_RELATED_RECORD', $MODULE)}</td>
							<td>
								{foreach item=MODULE_FIELD_MODEL key=MODULE_NAME from=$CONVERT_LEAD_FIELDS}
									{if $MODULE_NAME != 'Potentials'}
										<input type="radio" id="transfer{$MODULE_NAME}" class="transferModule alignBottom" name="transferModule" value="{$MODULE_NAME}"
										{if $CONVERT_LEAD_FIELDS['Contacts'] && $MODULE_NAME=="Contacts"} checked="" {elseif !$CONVERT_LEAD_FIELDS['Contacts'] && $MODULE_NAME=="Accounts"} checked="" {/if}/>
										{if $MODULE_NAME eq 'Contacts'}
											&nbsp; {vtranslate('SINGLE_Contacts',$MODULE_NAME)} &nbsp;&nbsp;
										{else}
											&nbsp; {vtranslate('SINGLE_Accounts',$MODULE_NAME)} &nbsp;&nbsp;
										{/if}
									{/if}
								{/foreach}
							</td>
						</tr>
					</table>
				</div>
			</div>
			{include file='ModalFooter.tpl'|@vtemplate_path:$MODULE}
		</form>
	{/if}
</div>
{/strip}