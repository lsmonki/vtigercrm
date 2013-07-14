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
    {foreach item=FIELD_MODEL key=FIELD_NAME from=$PROVIDER_FIELDS}
        {if !$FIELD_MODEL->isEditable()}
            {continue}
        {/if}
        <div class="control-group">
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
                    <input type="password" name="{$FIELD_NAME}" {if $FIELD_MODEL->isMandatory()} data-validation-engine="validate[required]" {/if} class="span3" value="{$FIELD_VALUE}" />
                {else if $FIELD_NAME == 'SilentPostURL'}
                    <a href="{$PROVIDER->getSilentPostUrl()}" 
                       onclick="event.preventDefault();">
                        {vtranslate('LBL_RIGHT_CLICK_COPY',$QUALIFIED_MODULE_NAME)}
                    </a>
                {else}
                    <input type="text" {if $FIELD_MODEL->isMandatory()} data-validation-engine="validate[required]" {/if} name="{$FIELD_NAME}" class="span3" value="{$FIELD_VALUE}" />
                {/if}
            </div>
        </div>
    {/foreach}
{/strip}