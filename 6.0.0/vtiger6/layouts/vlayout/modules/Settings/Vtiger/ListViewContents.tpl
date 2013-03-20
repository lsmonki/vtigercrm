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
<div class="well listViewActionsDiv">
	<span class="btn-toolbar">
		{vtranslate($MODULE, $QUALIFIED_MODULE)}
	</span>
	<span class="pageNavigation pull-right">
		<span class="pageNumbers">{$PAGING_MODEL->getRecordStartRange()} to {$PAGING_MODEL->getRecordEndRange()}</span>
		<input type='hidden' value="{$PAGE_NUMBER}" id='pageNumber'>
		<input type='hidden' value="{$PAGING_MODEL->getPageLimit()}" id='pageLimit'>
		<input type="hidden" value="{$LISTVIEW_ENTIRES_COUNT}" id="noOfEntries">
		<span class="btn btn-mini" id="listViewPreviousPageButton"><span class="icon-chevron-left"></span></span>
		<span class="btn btn-mini" id="listViewNextPageButton"><span class="icon-chevron-right"></span></span>
	</span>
</div>		
<div class="listViewEntriesDiv">
	<input type="hidden" value="{$ORDER_BY}" id="orderBy">
	<input type="hidden" value="{$SORT_ORDER}" id="sortOrder">
	<span class="listViewLoadingImageBlock hide modal" id="loadingListViewModal">
		<img class="listViewLoadingImage" src="{vimage_path('loading.gif')}" alt="no-image" title="{vtranslate('LBL_LOADING', $MODULE)}"/>
		<p class="listViewLoadingMsg">{vtranslate('LBL_LOADING_LISTVIEW_CONTENTS', $MODULE)}........</p>
	</span>
	{assign var="NAME_FIELDS" value=$MODULE_MODEL->getNameFields()}
	<table class="table table-bordered table-condensed table-striped listViewEntriesTable">
		<thead>
			<tr class="listViewHeaders">
				{foreach item=LISTVIEW_HEADER from=$LISTVIEW_HEADERS}
				<td>
					<a href="javascript:void(0);" class="listViewHeaderValues" data-nextsortorderval="{if $COLUMN_NAME eq $LISTVIEW_HEADER->get('name')}{$NEXT_SORT_ORDER}{else}ASC{/if}" data-columnname="{$LISTVIEW_HEADER->get('name')}">{vtranslate($LISTVIEW_HEADER->get('label'), $QUALIFIED_MODULE)}
						{if $COLUMN_NAME eq $LISTVIEW_HEADER->get('name')}<img class="sortImage" src="{vimage_path( $SORT_IMAGE, $MODULE)}">{else}<img class="hide sortingImage" src="{vimage_path( 'downArrowSmall.png', $MODULE)}">{/if}</a>
				</td>
				{/foreach}
			</tr>
		</thead>
		{foreach item=LISTVIEW_ENTRY from=$LISTVIEW_ENTRIES}
		<tr class="listViewEntries" data-id={$LISTVIEW_ENTRY->getId()}>
			{foreach item=LISTVIEW_HEADER from=$LISTVIEW_HEADERS}
			{assign var=LISTVIEW_HEADERNAME value=$LISTVIEW_HEADER->get('name')}
			{assign var=LAST_COLUMN value=$LISTVIEW_HEADER@last}
			{if $LAST_COLUMN}<td><div class="row-fluid">{else}<td>{/if}
				{if $LAST_COLUMN}<div class="span9">{/if}	
					{if isset($NAME_FIELDS[$LISTVIEW_HEADER->get('name')]) eq true}
					<a href="{$LISTVIEW_ENTRY->getDetailViewUrl()}">{$LISTVIEW_ENTRY->get($LISTVIEW_HEADERNAME)}</a>
					{else}
					{$LISTVIEW_ENTRY->get($LISTVIEW_HEADERNAME)}
					{/if}
				{if $LAST_COLUMN}</div>{/if}	
				{if $LAST_COLUMN && $LISTVIEW_ENTRY->getRecordLinks()}
				<div class="pull-right actions">
					<span class="actionImages">
						{foreach item=RECORD_LINK from=$LISTVIEW_ENTRY->getRecordLinks()}
							{assign var="RECORD_LINK_URL" value=$RECORD_LINK->getUrl()}
							<a href='{$RECORD_LINK_URL}' {if stripos($RECORD_LINK_URL, 'javascript:')===0}onclick='{$RECORD_LINK_URL|substr:strlen("javascript:")};return false;'{/if}><i class="{$RECORD_LINK->getIcon()} alignMiddle"></i></a>
							{if !$RECORD_LINK@last}
							<span class="alignMiddle actionImagesAlignment"><b>|</b></span>
							{/if}
						{/foreach}
					</span>
				</div>
				{/if}
			{if $LAST_COLUMN}</div>{/if}	
			</td>
			{/foreach}
		</tr>
		{/foreach}
	</table>

	<!--added this div for Temporarily -->
	{if $LISTVIEW_ENTIRES_COUNT eq '0'}
	<div class="span7 emptyRecordsDiv">NO {$MODULE} found.Click on add {$MODULE}</div>
	{/if}
</div>
{/strip}