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
			<h3 class="title">{vtranslate($MENU_ITEM->getName(), $QUALIFIED_MODULE)} - {vtranslate($MODULE, $QUALIFIED_MODULE)}</h3>
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

	<form name="EditWorkflow" action="index.php" method="post" id="EditView" class="form-horizontal">
		<input type="hidden" name="module" value="Workflows">
		<input type="hidden" name="action" value="Save">
		<input type="hidden" name="parent" value="Settings">

		<table class="table table-striped table-bordered table-condensed">
			<tbody>
				<tr class="listViewActionsDiv">
					<th colspan="2">{vtranslate('LBL_NEW_GROUP', $QUALIFIED_MODULE)}</th>
				</tr>
				<tr>
					<td class="fieldLabel">{vtranslate('LBL_SELECT_MODULE', $QUALIFIED_MODULE)}</td>
					<td class="fieldValue">
						<select class="chzn-select" name="module_name" required="true" data-placeholder="Select Module...">
							{foreach from=$ALL_MODULES key=TABID item=MODULE_MODEL}
								<option value="{$MODULE_MODEL->getName()}">{vtranslate($MODULE_MODEL->getLabel(), $MODULE_MODEL->getName())}</option>
							{/foreach}
						</select>
					</td>
				</tr>
				<tr>
					<td class="fieldLabel">{vtranslate('LBL_DESCRIPTION', $QUALIFIED_MODULE)}</td>
					<td class="fieldValue">
						<input type="text" name="summary" id="summary" required="true" />
					</td>
				</tr>
				<tr>
					<td class="fieldLabel">Name <span class="redColor">*</span></td>
					<td class="fieldValue">
						<input class="input-large" required="true" name="groupname" value="">
					</td>
				</tr>
			</tbody>
		</table>
		<div class="form-actions">
			<input class="vtButton" type="submit" value="{vtranslate('LBL_BACK', $QUALIFIED_MODULE)}" />
			<input class="vtButton" type="submit" value="{vtranslate('LBL_NEXT', $QUALIFIED_MODULE)}" />
			<a class="cancelLink" type="reset" onclick="window.history.back();">{vtranslate('LBL_CANCEL', $QUALIFIED_MODULE)}</a>
		</div>
	</form>
</div>
{/strip}