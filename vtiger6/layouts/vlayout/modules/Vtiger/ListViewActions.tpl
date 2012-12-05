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
	<span class="pull-right listViewActions">
		<span class="pageNumbers alignTop">
			{if $LISTVIEW_ENTIRES_COUNT}{$PAGING_MODEL->getRecordStartRange()} {vtranslate('LBL_TO', $MODULE)} {$PAGING_MODEL->getRecordEndRange()}{if $LISTVIEW_COUNT} {vtranslate('LBL_OF', $MODULE)} {$LISTVIEW_COUNT}{/if}{/if}
		</span>
		<span class="btn-group alignTop">

			<input type='hidden' value="{$PAGE_NUMBER}" id='pageNumber'>
			<input type='hidden' value="{$PAGING_MODEL->getPageLimit()}" id='pageLimit'>
			<input type="hidden" value="{$LISTVIEW_ENTIRES_COUNT}" id="noOfEntries">

			<span>
				<button class="btn" id="listViewPreviousPageButton" {if !$PAGING_MODEL->isPrevPageExists()} disabled {/if} type="button"><span class="icon-chevron-left"></span></button>
				<button class="btn" id="listViewNextPageButton" {if !$PAGING_MODEL->isNextPageExists()} disabled {/if} type="button"><span class="icon-chevron-right"></span></button>
			</span>
		</span>
	{if $LISTVIEW_LINKS['LISTVIEWSETTING']|@count gt 0}
		<span class="btn-group">
			<button class="btn dropdown-toggle" href="#" data-toggle="dropdown"><img class="alignMiddle" src="{vimage_path('tools.png')}" alt="{vtranslate('LBL_SETTINGS', $MODULE)}" title="{vtranslate('LBL_SETTINGS', $MODULE)}">&nbsp;&nbsp;<i class="caret"></i></button>
			<ul class="listViewSetting dropdown-menu">
				{foreach item=LISTVIEW_SETTING from=$LISTVIEW_LINKS['LISTVIEWSETTING']}
					<li><a href={$LISTVIEW_SETTING->getUrl()}>{vtranslate($LISTVIEW_SETTING->getLabel(), $MODULE)}</a></li>
				{/foreach}
			</ul>
		</span>
	{/if}
	</span>
	<div class="clearfix"></div>
	<input type="hidden" id="recordsCount" value=""/>
	<input type="hidden" id="selectedIds" name="selectedIds" />
	<input type="hidden" id="excludedIds" name="excludedIds" />
{/strip}