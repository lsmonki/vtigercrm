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
	<div class="listViewActionsDiv">
		<span class="btn-toolbar">
			<span class="btn-group listViewMassActions">
				<button class="btn dropdown-toggle" data-toggle="dropdown"><strong>{vtranslate('LBL_ACTIONS', $MODULE)}</strong>&nbsp;&nbsp;<i class="caret"></i></button>
				<ul class="dropdown-menu">
					{foreach item="LISTVIEW_MASSACTION" from=$LISTVIEW_MASSACTIONS}
						<li><a href="javascript:void(0);" {if stripos($LISTVIEW_MASSACTION->getUrl(), 'javascript:')===0}onclick='{$LISTVIEW_MASSACTION->getUrl()|substr:strlen("javascript:")};'{else} onclick="Vtiger_List_Js.triggerMassAction('{$LISTVIEW_MASSACTION->getUrl()}')"{/if} >{vtranslate($LISTVIEW_MASSACTION->getLabel(), $MODULE)}</a></li>
					{/foreach}
					{if $LISTVIEW_LINKS['LISTVIEW']|@count gt 0}
						<li class="divider"></li>
						{foreach item=LISTVIEW_ADVANCEDACTIONS from=$LISTVIEW_LINKS['LISTVIEW']}
							<li><a  {if stripos($LISTVIEW_ADVANCEDACTIONS->getUrl(), 'javascript:')===0} href="javascript:void(0);" onclick='{$LISTVIEW_ADVANCEDACTIONS->getUrl()|substr:strlen("javascript:")};'{else} href='{$LISTVIEW_ADVANCEDACTIONS->getUrl()}' {/if}>{vtranslate($LISTVIEW_ADVANCEDACTIONS->getLabel(), $MODULE)}</a></li>
						{/foreach}
					{/if}
				</ul>
			</span>
		</span>
		<span class="pageNavigation">
			<div class='pull-right'>
				<span class="pageNumbers">
					{if $LISTVIEW_ENTIRES_COUNT}{$PAGING_MODEL->getRecordStartRange()} {vtranslate('LBL_TO', $MODULE)} {$PAGING_MODEL->getRecordEndRange()}{if $LISTVIEW_COUNT} {vtranslate('LBL_OF', $MODULE)} {$LISTVIEW_COUNT}{/if}{/if}
				</span>
				<input type='hidden' value="{$PAGE_NUMBER}" id='pageNumber'>
				<input type='hidden' value="{$PAGING_MODEL->getPageLimit()}" id='pageLimit'>
				<input type="hidden" value="{$LISTVIEW_ENTIRES_COUNT}" id="noOfEntries">
				<div class='btn-group pull-right'>
					<button class="btn" id="listViewPreviousPageButton" {if !$PAGING_MODEL->isPrevPageExists()} disabled {/if} type="button"><span class="icon-chevron-left"></span></button>
					<button class="btn" id="listViewNextPageButton" {if !$PAGING_MODEL->isNextPageExists()} disabled {/if} type="button"><span class="icon-chevron-right"></span></button>
				</div>
			</div>
		</span>
{/strip}
