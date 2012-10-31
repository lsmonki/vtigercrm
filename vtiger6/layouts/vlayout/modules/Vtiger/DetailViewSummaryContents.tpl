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
	{if !empty($MODULE_SUMMARY)}
		<div class="summaryView">
		{$MODULE_SUMMARY}
		</div>
	{/if}
	<div class="widgetContainer">
		{foreach item=DETAIL_VIEW_WIDGET from=$DETAILVIEW_LINKS['DETAILVIEWWIDGET'] name=count}
			<div class="widgetContainer_{$smarty.foreach.count.index}" data-url={$DETAIL_VIEW_WIDGET->getUrl()}>
				<div class="widget_header">
					<h3>{vtranslate($DETAIL_VIEW_WIDGET->getLabel(),$MODULE_NAME)}</h3>
				</div>
				<div class="widget_contents">
				</div>
			</div>
		{/foreach}
	</div>
{/strip}