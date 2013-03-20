{*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************}
{strip}
<ul>
{foreach from=$ROLE->getChildren() item=CHILD_ROLE}
	<li data-role="{$CHILD_ROLE->getParentRoleString()}" data-roleid="{$CHILD_ROLE->getId()}">
		<div class="toolbar-handle">
			{if $smarty.request.type == 'Transfer'}
				{assign var="SOURCE_ROLE_SUBPATTERN" value='::'|cat:$SOURCE_ROLE->getId()}
				{if strpos($CHILD_ROLE->getParentRoleString(), $SOURCE_ROLE_SUBPATTERN) !== false}
					{$CHILD_ROLE->getName()}
				{else}
					<a href="javascript:;" data-url="{$CHILD_ROLE->getEditViewUrl()}" data-action="modal" class="btn btn-mini draggable droppable" rel="tooltip" title="Click to edit / Drag to move">{$CHILD_ROLE->getName()}</a>
				{/if}
			{else}
					<a href="javascript:;" data-url="{$CHILD_ROLE->getEditViewUrl()}" data-action="modal" class="btn btn-mini draggable droppable" rel="tooltip" title="Click to edit / Drag to move">{$CHILD_ROLE->getName()}</a>
			{/if}
			{if $smarty.request.view != 'Popup'}
			<div class="toolbar">
				&nbsp;<a href="javascript:;" data-url="{$CHILD_ROLE->getCreateChildUrl()}" data-action="modal"><span class="icon-plus-sign"></span></a>
				&nbsp;<a href="javascript:;" data-url="{$CHILD_ROLE->getDeleteActionUrl()}" data-action="modal"><span class="icon-trash"></span></a>
			</div>
			{/if}
		</div>
		
		{assign var="ROLE" value=$CHILD_ROLE}
		{include file=vtemplate_path("RoleTree.tpl", "Settings:Roles")}
	</li>
{/foreach}
</ul>
{/strip}