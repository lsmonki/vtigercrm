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
<div class="relatedContainer">
	<input type="hidden" name="currentPageNum" value="{$PAGING->getCurrentPage()}" />
	<input type="hidden" name="relatedModuleName" class="relatedModuleName" value="{$RELATED_MODULE->get('name')}" />
	<input type="hidden" value="{$ORDER_BY}" id="orderBy">
	<input type="hidden" value="{$SORT_ORDER}" id="sortOrder">
	<input type="hidden" value="{$RELATED_ENTIRES_COUNT}" id="noOfEntries">
	<input type='hidden' value="{$PAGING->getPageLimit()}" id='pageLimit'>
	<div class="relatedHeader ">
		<div class="btn-toolbar row-fluid">
			<div class="span8">

				{foreach item=RELATED_LINK from=$RELATED_LIST_LINKS['LISTVIEWBASIC']}
					<div class="btn-group">
						{assign var=IS_SELECT_BUTTON value={$RELATED_LINK->get('_selectRelation')}}
						<button type="button" class="btn
							{if $IS_SELECT_BUTTON eq true} selectRelation {/if} "
							{if $IS_SELECT_BUTTON eq true} data-moduleName={$RELATED_LINK->get('_module')->get('name')} {/if}
							{if $RELATED_LINK->isPageLoadLink()}onclick="window.location.href='{$RELATED_LINK->getUrl()}'"{/if}
							>{if $IS_SELECT_BUTTON eq false}<i class="icon-plus"></i>{/if}&nbsp;<strong>{$RELATED_LINK->getLabel()}</strong></button>
					</div>
				{/foreach}
				&nbsp;
			</div>
			<div class="span4">
				<span class="row-fluid">
					<span class="span7 pushDown">
						<span class="pull-right">
						{if !empty($RELATED_RECORDS)} {$PAGING->getRecordStartRange()} {vtranslate('LBL_TO', $RELATED_MODULE->get('name'))} {$PAGING->getRecordEndRange()}{if $TOTAL_ENTRIES} {vtranslate('LBL_OF', $RELATED_MODULE->get('name'))} {$TOTAL_ENTRIES}{/if}{/if}
						</span>
					</span>
					
						<div class="span5 btn-group pull-right">
							<button class="btn" id="listViewPreviousPageButton" {if !$PAGING->isPrevPageExists()} disabled="disabled" {/if} type="button"><span class="icon-chevron-left"></span></button>
							<button class="btn dropdown-toggle" type="button" id="listViewPageJump" data-toggle="dropdown" {if $PAGE_COUNT eq 1} disabled {/if}>
								<span><img src="{vimage_path('ListViewJump.png')}" alt="{vtranslate('LBL_LISTVIEW_PAGE_JUMP',$moduleName)}" title="{vtranslate('LBL_LISTVIEW_PAGE_JUMP',$moduleName)}" /></span>
							</button>
							<ul class="listViewBasicAction dropdown-menu" id="listViewPageJumpDropDown">
								<li>
									<span class="row-fluid">
										<span class="span3"><span class="pull-right">{vtranslate('LBL_PAGE',$moduleName)}</span></span>
										<span class="span4">
											<input type="text" id="pageToJump" class="listViewPagingInput" value="{$PAGING->getCurrentPage()}"/>
										</span>
										<span class="span2 textAlignCenter">
											{vtranslate('LBL_OF',$moduleName)}
										</span>
										<span class="span2" id="totalPageCount"></span>
									</span>
								</li>
							</ul>
							<button class="btn" id="listViewNextPageButton" {if !$PAGING->isNextPageExists()} disabled="disabled" {/if} type="button"><span class="icon-chevron-right"></span></button>
						</div>
					</span>
				</span>
			</div>
		</div>
	</div>
	<div class="relatedContents">
		<table class="table table-bordered listViewEntriesTable">
			<thead>
				<tr class="listViewHeaders">
					{foreach item=HEADER_FIELD from=$RELATED_HEADERS}
						<th>
							<a href="javascript:void(0);" class="listViewHeaderValues" data-nextsortorderval="{if $COLUMN_NAME eq $HEADER_FIELD->get('name')}{$NEXT_SORT_ORDER}{else}ASC{/if}" data-fieldname="{$HEADER_FIELD->get('name')}">{vtranslate($HEADER_FIELD->get('label'), $RELATED_MODULE->get('name'))}
								&nbsp;&nbsp;{if $COLUMN_NAME eq $HEADER_FIELD->get('name')}<img class="{$SORT_IMAGE} icon-white">{/if}
							</a>
						</th>
					{/foreach}
				</tr>
			</thead>
			{foreach item=RELATED_RECORD from=$RELATED_RECORDS}
				{assign var=BASE_CURRENCY_DETAILS value=$RELATED_RECORD->getBaseCurrencyDetails()}
				<tr class="listViewEntries" data-id='{$RELATED_RECORD->getId()}' data-recordUrl='{$RELATED_RECORD->getDetailViewUrl()}'>
					{foreach item=HEADER_FIELD from=$RELATED_HEADERS}
						{assign var=RELATED_HEADERNAME value=$HEADER_FIELD->get('name')}
						<td>
						{if $HEADER_FIELD->get('name') == 'listprice'}
								{$RELATED_RECORD->get($HEADER_FIELD->get('name'))}
								{assign var="LISTPRICE" value=$RELATED_RECORD->get($HEADER_FIELD->get('name'))}
							{else if $HEADER_FIELD->isNameField() eq true or $HEADER_FIELD->get('uitype') eq '4'}
								<a href="{$RELATED_RECORD->getDetailViewUrl()}">{$RELATED_RECORD->getDisplayValue($RELATED_HEADERNAME)}</a>
						{else}
							{$RELATED_RECORD->getDisplayValue($RELATED_HEADERNAME)}
						{/if}
						{if $HEADER_FIELD@last}
						<div class="pull-right actions">
							<span class="actionImages">
								<a href="{$RELATED_RECORD->getFullDetailViewUrl()}"><i title="{vtranslate('LBL_SHOW_COMPLETE_DETAILS', $MODULE)}" class="icon-th-list alignMiddle"></i></a>&nbsp;
								<a data-url="index.php?module=PriceBooks&view=ListPriceUpdate&record={$PARENT_RECORD->getId()}&relid={$RELATED_RECORD->getId()}&currentPrice={$LISTPRICE}"
									class="editListPrice cursorPointer" data-related-recordid='{$RELATED_RECORD->getId()}' data-list-price={$LISTPRICE}>
									<i class="icon-pencil alignMiddle" title="{vtranslate('LBL_EDIT', $MODULE)}"></i>
								</a>
								<a class="relationDelete"><i title="{vtranslate('LBL_DELETE', $MODULE)}" class="icon-trash alignMiddle"></i></a>
							</span>
						</div>
						{/if}
						</td>
					{/foreach}
				</tr>
			{/foreach}
		</table>
	</div>
</div>
{/strip}
