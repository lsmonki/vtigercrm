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
<div class="gridster" style="padding-left:10px;">
	<ul>
	{assign var=COLUMNS value=2}
	{assign var=ROW value=1}
	{foreach from=$WIDGETS item=WIDGET name=count}
		<li id="{$WIDGET->get('linkid')}" {if $smarty.foreach.count.index % $COLUMNS == 0 and $smarty.foreach.count.index != 0} {assign var=ROWCOUNT value=$ROW+1} data-row="{$ROWCOUNT}" {else} data-row="{$ROW}" {/if}
			{assign var=COLCOUNT value=($smarty.foreach.count.index % $COLUMNS)+1} data-col="{$COLCOUNT}" data-sizex="{$WIDGET->getWidth()}" data-sizey="{$WIDGET->getHeight()}"
			class="dashboardWidget dashboardWidget_{$smarty.foreach.count.index}" data-url="{$WIDGET->getUrl()}" data-mode="open" data-name="{$WIDGET->getName()}">
		</li>
	{/foreach}
	</ul>
	<input type="hidden" id=row value="{$ROWCOUNT}" />
	<input type="hidden" id=col value="{$COLCOUNT}" />
	<input type="hidden" id="userDateFormat" value="{$CURRENT_USER->get('date_format')}" />
</div>
