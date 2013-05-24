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
						<button type="button" class="btn addButton
							{if $IS_SELECT_BUTTON eq true} selectRelation {/if} "
							{if $IS_SELECT_BUTTON eq true} data-moduleName={$RELATED_LINK->get('_module')->get('name')} {/if}
							{if ($RELATED_LINK->isPageLoadLink())}
                                    {if $RELATION_FIELD} data-name="{$RELATION_FIELD->getName()}" {/if}
                            {/if}
						{if $IS_SELECT_BUTTON neq true}name="composeEmail"{/if}>{if $IS_SELECT_BUTTON eq false}<i class="icon-plus icon-white"></i>{/if}&nbsp;<strong>{$RELATED_LINK->getLabel()}</strong></button>
					</div>
				{/foreach}
				&nbsp;
			</div>
			<div class="span4">
				<span class="row-fluid">
					<span class="span8 pushDown">
						<span class="pull-right">
						{if !empty($RELATED_RECORDS)} {$PAGING->getRecordStartRange()} {vtranslate('LBL_TO', $RELATED_MODULE->get('name'))} {$PAGING->getRecordEndRange()}{if $TOTAL_ENTRIES} {vtranslate('LBL_OF', $RELATED_MODULE->get('name'))} {$TOTAL_ENTRIES}{/if}{/if}
						</span>
					</span>
					<span class="span4 pull-right">
						<span class="btn-group pull-right">
							<button class="btn" id="relatedListPreviousPageButton" {if !$PAGING->isPrevPageExists()} disabled {/if} type="button"><span class="icon-chevron-left"></span></button>
							<button class="btn" id="relatedListNextPageButton" {if !$PAGING->isNextPageExists()} disabled {/if} type="button"><span class="icon-chevron-right"></span></button>
						</span>
					</span>
				</span>
			</div>
		</div>
	</div>
	<div class="contents-topscroll">
		<div class="topscroll-div">
		&nbsp;
		</div>
	</div>
	<div class="relatedContents contents-bottomscroll">
		<div class="bottomscroll-div">
			<table class="table table-bordered listViewEntriesTable">
				<thead>
					<tr class="listViewHeaders">
						{foreach item=HEADER_FIELD from=$RELATED_HEADERS}
							<th>
								{if $HEADER_FIELD->get('column') eq 'access_count' or $HEADER_FIELD->get('column') eq 'idlists'}
									<a href="javascript:void(0);" class="noSorting">{vtranslate($HEADER_FIELD->get('label'), $RELATED_MODULE->get('name'))}</a>
								{elseif $HEADER_FIELD->get('column') eq 'time_start'}
								{else}
									<a href="javascript:void(0);" class="relatedListHeaderValues" data-nextsortorderval="{if $COLUMN_NAME eq $HEADER_FIELD->get('column')}{$NEXT_SORT_ORDER}{else}ASC{/if}" data-fieldname="{$HEADER_FIELD->get('column')}">{vtranslate($HEADER_FIELD->get('label')|html_entity_decode, $RELATED_MODULE->get('name'))}
										&nbsp;&nbsp;{if $COLUMN_NAME eq $HEADER_FIELD->get('column')}<img class="{$SORT_IMAGE} icon-white">{/if}
									</a>

								{/if}
							</th>
						{/foreach}
					</tr>
				</thead>
				{foreach item=RELATED_RECORD from=$RELATED_RECORDS}
					<tr class="listViewEntries" data-id='{$RELATED_RECORD->getId()}' name="emailsRelatedRecord">
						{foreach item=HEADER_FIELD from=$RELATED_HEADERS}
							{assign var=RELATED_HEADERNAME value=$HEADER_FIELD->get('name')}
							<td>
								{if $HEADER_FIELD->isNameField() eq true or $HEADER_FIELD->get('uitype') eq '4'}
									<a>{$RELATED_RECORD->getDisplayValue($RELATED_HEADERNAME)}</a>
								{elseif $RELATED_HEADERNAME eq 'access_count'}
									{$RELATED_RECORD->getAccessCountValue($PARENT_RECORD->getId())}
								{elseif $RELATED_HEADERNAME eq 'date_start'}
									{$RELATED_RECORD->getDisplayValue($RELATED_HEADERNAME)} {$RELATED_RECORD->getDisplayValue('time_start')}
								{elseif $RELATED_HEADERNAME eq 'time_start'}
								{elseif $RELATED_HEADERNAME eq 'parent_id'}
									{assign var=REFERENCE_RECORD value=$RELATED_RECORD->get($RELATED_HEADERNAME)}
									{assign var=RECORD_MODULE_MODEL value=$RELATED_RECORD->getModule()}
									{assign var=RECORD_FIELD_MODEL value=$RECORD_MODULE_MODEL->getField($RELATED_HEADERNAME)}
									{assign var=UI_TYPE_MODEL value=Vtiger_Base_UIType::getInstanceFromField($RECORD_FIELD_MODEL)}
									{assign var=REFERENCE_MODULE_MODEL value=$UI_TYPE_MODEL->getReferenceModule($REFERENCE_RECORD)}
									{assign var=REFERENCE_MODULE_NAME value=$REFERENCE_MODULE_MODEL->get('name')}
									{assign var=REFERENCE_RECORD_ENTIYNAME_LIST value=getEntityName($REFERENCE_MODULE_NAME,$REFERENCE_RECORD)}
									<a href="index.php?module={$REFERENCE_MODULE_NAME}&view={$REFERENCE_MODULE_MODEL->getDetailViewName()}&record={$REFERENCE_RECORD}"
										title="{vtranslate($REFERENCE_MODULE_NAME, $REFERENCE_MODULE_NAME)}"
										onclick="if(event.stopPropagation){ldelim}event.stopPropagation();{rdelim}else{ldelim}event.cancelBubble=true;{rdelim}">
										{$REFERENCE_RECORD_ENTIYNAME_LIST.$REFERENCE_RECORD}
									</a>
								{else if $HEADER_FIELD->getFieldDataType() eq 'owner'}
									{getOwnerName($RELATED_RECORD->get($RELATED_HEADERNAME))}
								{else}
									{$RELATED_RECORD->getDisplayValue($RELATED_HEADERNAME)}
								{/if}
							{if $HEADER_FIELD@last}
							<div class="pull-right actions">
								<span class="actionImages">
									<a name="emailsDetailView"><i title="{vtranslate('LBL_SHOW_COMPLETE_DETAILS', $MODULE)}" class="icon-th-list alignMiddle"></i></a>&nbsp;
									{if !($RELATED_RECORD->isSentMail())}
										<a name="emailsEditView"><i title="{vtranslate('LBL_EDIT', $MODULE)}" class="icon-pencil alignMiddle"></i></a>	
									{/if}
									{if $IS_DELETABLE}
										<a class="relationDelete"><i title="{vtranslate('LBL_DELETE', $MODULE)}" class="icon-trash alignMiddle"></i></a>
									{/if}
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
</div>
{/strip}