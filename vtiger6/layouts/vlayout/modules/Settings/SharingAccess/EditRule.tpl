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
	{assign var=RULE_MODEL_EXISTS value=true}
	{assign var=RULE_ID value=$RULE_MODEL->getId()}
	{if empty($RULE_ID)}
		{assign var=RULE_MODEL_EXISTS value=false}
	{/if}
	<div class="modal-header">
		<button class="close vtButton" data-dismiss="modal">×</button>
		<h3>{vtranslate('LBL_CREATE_CUSTOM_RULE', $QUALIFIED_MODULE)}</h3>
	</div>
	<div class="contents">
		<form id="editCustomRule">
			<input type="hidden" name="for_module" value="{$MODULE_MODEL->get('name')}" />
			<input type="hidden" name="record" value="{$RULE_ID}" />
			<div class="modal-body">
				<table class="table table-bordered table-condensed table-striped">
					<tr>
						<td>{vtranslate('LBL_MODULE_OF', $QUALIFIED_MODULE)}</td>
						<td>
							<select class="chzn-select" name="source_id">
								{foreach from=$ALL_RULE_MEMBERS key=GROUP_LABEL item=ALL_GROUP_MEMBERS}
								<optgroup label="{vtranslate($GROUP_LABEL, $QUALIFIED_MODULE)}">
								{foreach from=$ALL_GROUP_MEMBERS item=MEMBER}
									<option value="{$MEMBER->getId()}"
										{if $RULE_MODEL_EXISTS} {if $RULE_MODEL->getSourceMember()->getId() == $MEMBER->getId()}selected{/if}{/if}>
										{$MEMBER->getName()}
									</option>
								{/foreach}
								</optgroup>
							{/foreach}
							</select>
						</td>
					</tr>
					<tr>
						<td>{vtranslate('LBL_CAN_ACCESSED_BY', $QUALIFIED_MODULE)}</td>
						<td>
							<select class="chzn-select" name="target_id">
								{foreach from=$ALL_RULE_MEMBERS key=GROUP_LABEL item=ALL_GROUP_MEMBERS}
								<optgroup label="{vtranslate($GROUP_LABEL, $QUALIFIED_MODULE)}">
								{foreach from=$ALL_GROUP_MEMBERS item=MEMBER}
									<option value="{$MEMBER->getId()}"
											{if $RULE_MODEL_EXISTS}{if $RULE_MODEL->getTargetMember()->getId() == $MEMBER->getId()}selected{/if}{/if}>
										{$MEMBER->getName()}
									</option>
								{/foreach}
								</optgroup>
							{/foreach}
							</select>
						</td>
					</tr>
					<tr>
						<td>{vtranslate('LBL_PRIVILEGES', $QUALIFIED_MODULE)}</td>
						<td>
							<select class="chzn-select" name="permission">
								{foreach item=PERMISSION_LABEL key=PERMISSION_ID from=$ALL_PERMISSIONS}
								<option value="{$PERMISSION_ID}"
										{if $RULE_MODEL_EXISTS}{if $RULE_MODEL->getPermission() == $PERMISSION_ID}selected{/if}{/if}>
									{vtranslate($PERMISSION_LABEL, $QUALIFIED_MODULE)}
								</option>
								{/foreach}
							</select>
						</td>
					</tr>
				</table>
			</div>
			<div class="modal-footer">
				<div class="row-fluid">
					<div class="pull-right">
						<div class="span5">
							<button class="vtButton" data-dismiss="modal">{vtranslate('LBL_CANCEL', $QUALIFIED_MODULE)}</button>
							<button type="submit" class="vtButton saveButton">{vtranslate('LBL_SAVE', $QUALIFIED_MODULE)}</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
{/strip}