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
<div class="container-fluid" id="sharingAccessContainer">
	<div class="titleBar row-fluid">
		<div class="span8">
			<h3 class="title">{vtranslate('LBL_CREATE_PROFILE', $QUALIFIED_MODULE)}</h3>
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

	<div class="row-fluid">
		<div class="contents well contentsBackground span8">
			<form name="EditSharingAccess" action="index.php" method="post" class="form-horizontal" id="EditSharingAccess">
				<input type="hidden" name="module" value="SharingAccess" />
				<input type="hidden" name="action" value="SaveAjax" />
				<input type="hidden" name="parent" value="Settings" />

				<table class="table table-bordered table-condensed sharingAccessDetails">
					<thead>
						<tr class="contentsBackground" >
							<th width="30%">
								{vtranslate('LBL_MODULE', $QUALIFIED_MODULE)}
							</th>
							
								{foreach from=$ALL_ACTIONS key=ACTION_ID item=ACTION_MODEL}
									<th class="row-fluid">
									<div class="">
										{$ACTION_MODEL->getName()|vtranslate:$QUALIFIED_MODULE}
									</div>
									</th>
								{/foreach}
							<th nowrap="nowrap">{'LBL_SHARING_RULES'|vtranslate:$QUALIFIED_MODULE}</th>
						</tr>
					</thead>
					<tbody>
						{foreach from=$ALL_MODULES key=TABID item=MODULE_MODEL}
						<tr data-module-name="{$MODULE_MODEL->get('name')}">
							<td>
								{$MODULE_MODEL->get('label')|vtranslate:$MODULE_MODEL->getName()}
							</td>
							{foreach from=$ALL_ACTIONS key=ACTION_ID item=ACTION_MODEL}
							<td class="row-fluid">
									{if $ACTION_MODEL->isModuleEnabled($MODULE_MODEL)}
										<div><input type="radio" name="permissions[{$TABID}]" data-action-state="{$ACTION_MODEL->getName()}" value="{$ACTION_ID}"{if $MODULE_MODEL->getPermissionValue() eq $ACTION_ID}checked="true"{/if}></div>
									{/if}
							</td>
							{/foreach}
							<td class="triggerCustomSharingAccess">
								<a href="javascript:;" data-handlerfor="fields" data-togglehandler="{$TABID}-rules" class="icon-chevron-down"></a>
							</td>
						</tr>
						{/foreach}
					</tbody>
				</table>
				<div class="form-actions">
					<button class="vtButton saveButton" type="submit">{vtranslate('LBL_SAVE', $MODULE)}</button>
					<a type="button" class="cancelLink" onclick="javascript:window.history.back();">{vtranslate('LBL_CANCEL', $MODULE)}</a>
				</div>
			</form>
		</div>
	</div>
</div>
{/strip}