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
	<span class="btn-toolbar">
		<span class="btn-group">
			<button name="addButton" class="btn-mini vtButton" onclick='window.location.href="{$MODULE_MODEL->getCreateViewUrl()}"'>
				{vtranslate('LBL_ADD_RECORD', $QUALIFIED_MODULE)}
			</button>
		</span>
		<span class="btn-group">
			<button name="backButton" class="btn-mini vtButton" onclick='window.location.href="{$SELECTED_MENU->getListUrl()}"'>
				{vtranslate('LBL_BACK', $QUALIFIED_MODULE)}
			</button>
		</span>
	</span>
</span>
<div class="clearfix"></div>
{/strip}
