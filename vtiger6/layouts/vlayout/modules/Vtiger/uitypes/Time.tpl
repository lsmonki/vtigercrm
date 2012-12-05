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
{assign var="FIELD_INFO" value=Zend_Json::encode($FIELD_MODEL->getFieldInfo())}
{assign var="SPECIAL_VALIDATOR" value=$FIELD_MODEL->getValidator()}
{assign var=FIELD_VALUE value=$FIELD_MODEL->getEditViewDisplayValue($FIELD_MODEL->get('fieldvalue'))}
{assign var="TIME_FORMAT" value=$USER_MODEL->get('hour_format')}
<div class="input-append bootstrap-timepicker-component">
    <input id="{$MODULE}_editView_fieldName_{$FIELD_MODEL->get('name')}" type="text" {if $MODULE eq 'Calendar' || $MODULE eq 'Events'}data-format="{$TIME_FORMAT}"{/if} class="timepicker-default input-small" value="{$FIELD_VALUE}" name="{$FIELD_MODEL->getName()}"
	data-validation-engine="validate[{if $FIELD_MODEL->isMandatory() eq true} required,{/if}funcCall[Vtiger_Base_Validator_Js.invokeValidation]]"   {if !empty($SPECIAL_VALIDATOR)}data-validator='{Zend_Json::encode($SPECIAL_VALIDATOR)}'{/if} data-fieldinfo='{$FIELD_INFO}' />
    <span class="add-on">
        <i class="icon-time"></i>
    </span>
</div>
{/strip}