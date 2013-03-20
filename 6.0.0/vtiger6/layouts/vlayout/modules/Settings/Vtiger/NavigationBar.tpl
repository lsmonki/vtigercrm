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
<div class="sidebarTitleBlock">
	<h3 class="titlePadding">{vtranslate('LBL_SETTINGS', $MODULE)}</h3>
</div>
<div class="padding10">
	{foreach item=MENU from=$SETTINGS_MENUS}
	<p><a class="quickLinks" href="{$MENU->getListUrl()}">
		{vtranslate($MENU->getLabel(), $QUALIFIED_MODULE)}
	</a></p>
	{/foreach}
</div>
{/strip}