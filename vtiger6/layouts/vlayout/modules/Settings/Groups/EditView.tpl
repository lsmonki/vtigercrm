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
	
	<form name="EditGroup" action="index.php" method="post" id="EditView" class="form-horizontal">
		<input type="hidden" name="module" value="Groups">
		<input type="hidden" name="action" value="Save">
		<input type="hidden" name="parent" value="Settings">
		<input type="hidden" name="record" value="{$RECORD_MODEL->getId()}">
		<input type="hidden" name="mode" value="{$MODE}">

		<table class="table table-striped table-bordered table-condensed">
			<tbody>
				<tr class="listViewActionsDiv">
					<th colspan="2">{vtranslate('LBL_NEW_GROUP', $QUALIFIED_MODULE)}</th>
				</tr>
				<tr>
					<td class="fieldLabel">Name <span class="redColor">*</span></td>
					<td class="fieldValue">
						<input class="input-large" required="true" name="groupname" value="{$RECORD_MODEL->getName()}">
					</td>
				</tr>
				<tr>
					<td class="fieldLabel">{vtranslate('LBL_DESCRIPTION', $QUALIFIED_MODULE)}</td>
					<td class="fieldValue">
						<textarea name="description" id="description">{$RECORD_MODEL->getDescription()}</textarea>
					</td>
				</tr>
				<tr>
					<td class="fieldLabel">{vtranslate('LBL_MEMBERS', $QUALIFIED_MODULE)}</td>
					<td class="fieldValue">
						{assign var="GROUP_MEMBERS" value=$RECORD_MODEL->getMembers()}

						<select id="memberList" class="chzn-select row-fluid members" multiple="true" name="members[]" required="true" data-placeholder="Choose Members...">
							{foreach from=$MEMBER_GROUPS key=GROUP_LABEL item=ALL_GROUP_MEMBERS}
								<optgroup label="{$GROUP_LABEL}">
								{foreach from=$ALL_GROUP_MEMBERS item=MEMBER}
									<option value="{$MEMBER->getId()}"  data-member-type="{$GROUP_LABEL}" {if isset($GROUP_MEMBERS[$GROUP_LABEL][$MEMBER->getId()])}selected="true"{/if}>{$MEMBER->getName()}</option>
								{/foreach}
								</optgroup>
							{/foreach}
						</select>
					</td>
				</tr>
			</tbody>
		</table>
		<div class="form-actions">
			<button class="vtButton saveButton" type="submit">{vtranslate('LBL_SAVE', $QUALIFIED_MODULE)}</button>
			<a class="cancelLink" type="reset" onclick="window.history.back();">{vtranslate('LBL_CANCEL', $QUALIFIED_MODULE)} </a>
		</div>
	</form>
</div>
{/strip}