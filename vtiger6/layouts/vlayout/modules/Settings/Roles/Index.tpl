{*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************}
{strip}
<div class="container-fluid">
	<div class="titleBar row-fluid">
		<div class="span8">
			<h3 class="title">{vtranslate($MODULE, $QUALIFIED_MODULE)}</h3>
			<p>&nbsp;</p>
		</div>
		<div class="span4">
			<div class="pull-right">
				<div class="btn-toolbar">
					<span class="btn-group">
						<a class="btn btn-mini vtButton" href="javascript:window.history.back();">Back ...</a>
					</span>
				</div>
			</div>
		</div>
	</div>
	<div class="clearfix treeView">
		<ul>
			<li data-role="{$ROOT_ROLE->getParentRoleString()}" data-roleid="{$ROOT_ROLE->getId()}">
				<div class="toolbar-handle">
					<a href="javascript:;" class="btn btn-mini btn-info draggable droppable">{$ROOT_ROLE->getName()}</a>
					<div class="toolbar">
						&nbsp;<a href="javascript:;" data-url="{$ROOT_ROLE->getCreateChildUrl()}" data-action="modal"><span class="icon-plus-sign"></span></a>
					</div>
				</div>
				{assign var="ROLE" value=$ROOT_ROLE}
				{include file=vtemplate_path("RoleTree.tpl", "Settings:Roles")}
			</li>
		</ul>
	</div>
</div>
			
<script type="text/javascript">
	jQuery('body').ready(Settings_Roles_Js.initEditView);
</script>
{/strip}