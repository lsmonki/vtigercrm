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
	<input type="file" class="input-large {if $MODULE eq 'Products'}multi" maxlength="6"{else}"{/if} name="{$FIELD_MODEL->get('name')}[]" value="{$FIELD_MODEL->get('fieldvalue')}"
	data-validation-engine="validate[{if $FIELD_MODEL->isMandatory() eq true} required,{/if}funcCall[Vtiger_Base_Validator_Js.invokeValidation]]"
	data-fieldinfo='{$FIELD_INFO}' {if !empty($SPECIAL_VALIDATOR)}data-validator={Zend_Json::encode($SPECIAL_VALIDATOR)}{/if} />
	{if $MODULE eq 'Products'}<div id="MultiFile1_wrap_list" class="MultiFile-list"></div>{/if}

	{foreach key=ITER item=IMAGE_INFO from=$IMAGE_DETAILS}
		<div class="imageContainer">
			{if !empty($IMAGE_INFO.path) && !empty({$IMAGE_INFO.orgname})}
				<img src="../{$IMAGE_INFO.path}_{$IMAGE_INFO.orgname}" data-image-id="{$IMAGE_INFO.id}">&nbsp;&nbsp;[{$IMAGE_INFO.name}]
				<input type="button" id="file_{$ITER}" value="Delete" class="imageDelete">
			{/if}
		</div>
	{/foreach}
{/strip}