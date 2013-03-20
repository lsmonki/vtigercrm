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
	<div class="row-fluid">
		<div class="title row-fluid padding-bottom1per">
			<span class="secondaryColor padding-left1per">
				<!-- Check if the module should the for module to get the translations-->
				{vtranslate('LBL_SHARING_RULE', $QUALIFIED_MODULE)} :
			</span>
			<span class="pull-right padding-right1per">
				<button class="vtButton btn-mini addCustomRule" data-url="{$MODULE_MODEL->getCreateRuleUrl()}"> {vtranslate('LBL_ADD_CUSTOM_RULE', $QUALIFIED_MODULE)} </button>
			</span>
		</div>
		<div class="contents">
			<table class="table table-bordered table-condensed">
				<tbody>
					<tr>
						<th class="secondaryColor">{vtranslate('LBL_RULE_NO', $QUALIFIED_MODULE)}</th>
						<!-- Check if the module should the for module to get the translations -->
						<th class="secondaryColor">{vtranslate('LBL_MODULE_OF', $QUALIFIED_MODULE)}</th>
						<th class="secondaryColor">{vtranslate('LBL_CAN_ACCESSED_BY', $QUALIFIED_MODULE)}</th>
						<th class="secondaryColor">{vtranslate('LBL_PRIVILEGES', $QUALIFIED_MODULE)}</th>
						<th class="secondaryColor">{vtranslate('LBL_TOOLS', $QUALIFIED_MODULE)}</th>
					</tr>
					{foreach item=RULE_MODEL key=RULE_ID from=$RULE_MODEL_LIST name="customRuleIterator"}
					<tr>
						<td>
							{$smarty.foreach.customRuleIterator.index + 1}
						</td>
						<td>
							{$RULE_MODEL->getSourceMember()->getName()}
						</td>
						<td>
							{$RULE_MODEL->getTargetMember()->getName()}
						</td>
						<td>
							{if $RULE_MODEL->isReadOnly()}
								{vtranslate('Read Only', $QUALIFIED_MODULE)}
							{else}
								{vtranslate('Read Write', $QUALIFIED_MODULE)}
							{/if}
						</td>
						<td>
							<span>
								<a href="javascript:void(0);" class="edit" data-url="{$RULE_MODEL->getEditViewUrl()}"><i title="{vtranslate('LBL_EDIT', $MODULE)}" class="icon-pencil alignMiddle"></i></a>
								<span class="alignMiddle actionImagesAlignment"> <b>|</b></span>
								<a href="javascript:void(0);" class="delete" data-url="{$RULE_MODEL->getDeleteActionUrl()}"><i title="{vtranslate('LBL_DELETE', $MODULE)}" class="icon-trash alignMiddle"></i></a>
							</span>
						</td>
					</tr>
					{/foreach}
				</tbody>
			</table>
		</div>
	</div>
{/strip}