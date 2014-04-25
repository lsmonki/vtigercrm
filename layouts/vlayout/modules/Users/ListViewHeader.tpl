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
<div class="container-fluid">
	<div class="widget_header row-fluid">
		<h3>{vtranslate($MODULE, $QUALIFIED_MODULE)}</h3>
	</div>
	<hr>
	<div class="row-fluid">
		<span class="span4 btn-toolbar">
			{foreach item=LISTVIEW_BASICACTION from=$LISTVIEW_LINKS['LISTVIEWBASIC']}
			<button class="btn addButton" {if stripos($LISTVIEW_BASICACTION->getUrl(), 'javascript:')===0} onclick='{$LISTVIEW_BASICACTION->getUrl()|substr:strlen("javascript:")};'
					{else} onclick='window.location.href="{$LISTVIEW_BASICACTION->getUrl()}"' {/if}>
				<i class="icon-plus"></i>&nbsp;
				<strong>{vtranslate('LBL_ADD_RECORD', $QUALIFIED_MODULE)}</strong>
			</button>
			{/foreach}
		</span>
        <div class="span4 btn-toolbar">
            <select class="select2" id="usersFilter" name="status" style="min-width:350px;">
                <option value="Active">{vtranslate('LBL_ACTIVE_USERS', $QUALIFIED_MODULE)}</option>
                <option value="Inactive">{vtranslate('LBL_INACTIVE_USERS', $QUALIFIED_MODULE)}</option>
            </select>
        </div>
		<span class="span4 btn-toolbar">
			{include file='ListViewActions.tpl'|@vtemplate_path:$QUALIFIED_MODULE}
		</span>
	</div>
	<div class="listViewContentDiv" id="listViewContents">
{/strip}