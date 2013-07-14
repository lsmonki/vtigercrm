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
<input type="hidden" id="parentModule" value="{$SOURCE_MODULE}"/>
<input type="hidden" id="module" value="{$MODULE}"/>
<input type="hidden" id="parent" value="{$PARENT_MODULE}"/>
<input type="hidden" id="sourceRecord" value="{$SOURCE_RECORD}"/>
<input type="hidden" id="sourceField" value="{$SOURCE_FIELD}"/>
<input type="hidden" id="view" value="{$VIEW}"/>
<input type="hidden" id="url" value="{$GETURL}" />
<input type="hidden" id="multi_select" value="{$MULTI_SELECT}" />
<input type="hidden" id="currencyId" value="{$CURRENCY_ID}" />
<input type="hidden" id="relatedParentModule" value="{$RELATED_PARENT_MODULE}"/>
<input type="hidden" id="relatedParentId" value="{$RELATED_PARENT_ID}"/>
<div class="popupContainer row-fluid">
	<div class="span12">
		<div class="row-fluid">
			<div class="span6 row-fluid">
				<span class="logo span5"><img src="{$COMPANY_LOGO->get('imagepath')}" title="{$COMPANY_LOGO->get('title')}" alt="{$COMPANY_LOGO->get('alt')}"/></span>
			</div>
			<div class="span6 pull-right">
				<span class="pull-right"><b>{vtranslate($MODULE_NAME, $MODULE_NAME)}</b></span>
			</div>
		</div>
	</div>
</div>

<form class="form-horizontal popupSearchContainer">
	<div class="control-group margin0px">
		<span class="paddingLeft10px"><strong>{vtranslate('LBL_SEARCH_FOR')}</strong></span>
		<span class="paddingLeft10px"></span>
		<input type="text" placeholder="{vtranslate('LBL_TYPE_SEARCH')}" id="searchvalue"/>
		<span class="paddingLeft10px"><strong>{vtranslate('LBL_IN')}</strong></span>
		<span class="paddingLeft10px help-inline pushDownHalfper">
			<select style="width: 150px;" class="chzn-select help-inline" id="searchableColumnsList">
				{foreach key=block item=fields from=$RECORD_STRUCTURE}
					{foreach key=fieldName item=fieldObject from=$fields}
						<optgroup>
							<option value="{$fieldName}">{vtranslate($fieldObject->get('label'),$MODULE)}</option>
						</optgroup>
					{/foreach}
				{/foreach}
			</select>
		</span>
		<span class="paddingLeft10px cursorPointer help-inline" id="popupSearchButton"><img src="{vimage_path('search.png')}" alt="{vtranslate('LBL_SEARCH_BUTTON')}" title="{vtranslate('LBL_SEARCH_BUTTON')}" /></span>
</div>
</form>
{/strip}
