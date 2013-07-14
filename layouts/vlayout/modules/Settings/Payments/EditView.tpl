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
<div class="modal">
	<div class="modal-header">
		<button data-dismiss="modal" class="close" title="{vtranslate('LBL_CLOSE')}">x</button>
		{if $RECORD_ID}
			<h3>{vtranslate('LBL_EDIT_CONFIGURATION', $QUALIFIED_MODULE_NAME)} </h3>
		{else}
			<h3>{vtranslate('LBL_ADD_CONFIGURATION', $QUALIFIED_MODULE_NAME)} </h3>
		{/if}
	</div>
	<form class="form-horizontal contentsBackground" id="paymentsSettingsForm">
		<div class="modal-body configContent">
            <input type="hidden" value="{$RECORD_MODEL->getId()}" name="record" id="recordId"/>
            
			{foreach item=FIELD_MODEL from=$MODULE_MODEL->getFields()}
            {if !$FIELD_MODEL->isEditable()}
                {continue}
            {/if}
			<div class="control-group">
				{assign var=FIELD_NAME value=$FIELD_MODEL->get('name')}
                {assign var=FIELD_VALUE value=$RECORD_MODEL->get($FIELD_NAME)}
				<span class="control-label">
					<strong>
						{vtranslate($FIELD_MODEL->get('label'), $QUALIFIED_MODULE_NAME)}{if $FIELD_MODEL->isMandatory()}<span class="redColor">*</span>{/if}
					</strong>
				</span>
				<div class="controls">
					{assign var=FIELD_TYPE value=$FIELD_MODEL->getFieldDataType()}
					{if $FIELD_TYPE == 'picklist'}
                        <select class="select2" name="{$FIELD_NAME}" placeholder="{vtranslate('LBL_SELECT_ONE', $QUALIFIED_MODULE_NAME)}">
                            {foreach item=PICKLIST_LABEL key=PICKLIST_KEY from=$FIELD_MODEL->getPicklistValues()}
								<option value="{$PICKLIST_KEY}" {if $PICKLIST_KEY eq $FIELD_VALUE} selected {/if}>
                                    {vtranslate($PICKLIST_LABEL, $QUALIFIED_MODULE_NAME)}
                                </option>
							{/foreach}
						</select>
                    {else if $FIELD_TYPE == 'boolean'}
                        <input type="hidden" name="{$FIELD_NAME}" value="0" />
                        <input type="checkbox" name="{$FIELD_NAME}" value="1" {if $FIELD_VALUE}checked="checked"{/if} />
					{else if $FIELD_TYPE == 'password'}
						<input type="password" {if $FIELD_MODEL->isMandatory()} data-validation-engine="validate[required]" {/if} name="{$FIELD_NAME}" class="span3" value="{$FIELD_VALUE}" />
					{else}
						<input type="text" {if $FIELD_MODEL->isMandatory()} data-validation-engine="validate[required]" {/if} name="{$FIELD_NAME}" class="span3" value="{$FIELD_VALUE}" />
					{/if}
				</div>
			</div>
			{/foreach}
            
            {foreach item=PROVIDER from=$PROVIDERS_LIST}
                {assign var=PROVIDER_FIELDS value=$PROVIDER->getRequiredFields()}
                <div class="providers" name="{$PROVIDER->getName()}" {if $PROVIDER->getName()!=$SELECTED_PROVIDER->getName()}style="display:none"{/if}>
                    {include file=vtemplate_path($PROVIDER->getTemplateName(),$QUALIFIED_MODULE_NAME)}
                </div>
            {/foreach}
		</div>
		{include file='ModalFooter.tpl'|@vtemplate_path:$MODULE}
	</form>
</div>
{/strip}