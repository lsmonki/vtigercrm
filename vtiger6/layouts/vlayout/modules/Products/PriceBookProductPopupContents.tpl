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
<div class="popupEntriesDiv">
	<input type="hidden" value="{$ORDER_BY}" id="orderBy">
	<input type="hidden" value="{$SORT_ORDER}" id="sortOrder">
	<input type="hidden" value="{$SOURCE_FIELD}" id="sourceField">
	<input type="hidden" value="{$SOURCE_RECORD}" id="sourceRecord">
	<input type="hidden" value="{$SOURCE_MODULE}" id="parentModule">
	<input type="hidden" value="PriceBook_Products_Popup_Js" id="popUpClassName"/>
	<table class="table table-bordered listViewEntriesTable">
		<thead>
			<tr class="listViewHeaders">
				<td>
					<input type="checkbox"  class="selectAllInCurrentPage" />
				</td>
				{foreach item=LISTVIEW_HEADER from=$LISTVIEW_HEADERS}
				<td>
					<a class="listViewHeaderValues cursorPointer" data-nextsortorderval="{if $ORDER_BY eq $LISTVIEW_HEADER->get('column')}{$NEXT_SORT_ORDER}{else}ASC{/if}" data-columnname="{$LISTVIEW_HEADER->get('column')}">{vtranslate($LISTVIEW_HEADER->get('label'), $MODULE_NAME)}
						{if $ORDER_BY eq $LISTVIEW_HEADER->get('column')}<img class="sortImage" src="{vimage_path( $SORT_IMAGE, $MODULE_NAME)}">{else}<img class="hide sortingImage" src="{vimage_path( 'downArrowSmall.png', $MODULE_NAME)}">{/if}</a>
				</td>
				{/foreach}
				<td class="listViewHeaderValues noSorting">{vtranslate('LBL_LIST_PRICE',$MODULE)}</td>
			</tr>
		</thead>
		{foreach item=LISTVIEW_ENTRY from=$LISTVIEW_ENTRIES}
		<tr class="listViewEntries" data-id="{$LISTVIEW_ENTRY->getId()}" data-name='{$LISTVIEW_ENTRY->getName()}'
			{if $GETURL neq '' } data-url='{$LISTVIEW_ENTRY->$GETURL()}' {/if} >
			<td>
				<input class="entryCheckBox" type="checkbox" />
			</td>
			{foreach item=LISTVIEW_HEADER from=$LISTVIEW_HEADERS}
			{assign var=LISTVIEW_HEADERNAME value=$LISTVIEW_HEADER->get('name')}
			<td>
				{if $LISTVIEW_HEADERNAME eq 'unit_price'}
					{$LISTVIEW_ENTRY->get('currencySymbol')}
				{/if}
				{if $LISTVIEW_HEADER->isNameField() eq true or $LISTVIEW_HEADER->get('uitype') eq '4'}
					<a>{$LISTVIEW_ENTRY->get($LISTVIEW_HEADERNAME)}</a>
				{else}
					{$LISTVIEW_ENTRY->get($LISTVIEW_HEADERNAME)}
				{/if}
			</td>
			{/foreach}
			<td class="listPricetd">
				<div class="row-fluid">
					<input type="text" value="{$LISTVIEW_ENTRY->get('unit_price')}" class="invisible span10 listPrice" data-validation-engine="validate[required,funcCall[Vtiger_Currency_Validator_Js.invokeValidation]]" 
						   data-decimal-seperator='{$USER_MODEL->get('currency_decimal_separator')}' data-group-seperator='{$USER_MODEL->get('currency_grouping_separator')}'/>
				</div>
			</td>
		</tr>
		{/foreach}
	</table>
	<!--added this div for Temporarily -->
	{if $LISTVIEW_ENTIRES_COUNT eq '0'}
		<div class="row-fluid">
			<div class="emptyRecordsDiv">{vtranslate('LBL_NO', $MODULE_NAME)} {vtranslate($MODULE_NAME, $MODULE_NAME)} {vtranslate('LBL_FOUND', $MODULE_NAME)}.</div>
		</div>
	{/if}
</div>
<div> 
	<a class="cancelLink cursorPointer pull-right padding1per"> Cancel </a> 
	<button class="btn addButton select pull-right"><i class="icon-plus icon-white"></i>&nbsp;<strong>{vtranslate('LBL_ADD_TO_PRICEBOOKS',$MODULE)}</strong></button> 
</div> 
{/strip}