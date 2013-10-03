{*+***********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.0
* ("License"); You may not use this file except in compliance with the License
* The Original Code is:  vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
*************************************************************************************}
{strip}
	<div class="workFlowContents" style="padding-left: 3%;padding-right: 3%">
		<form name="EditWorkflow" action="index.php" method="post" id="workflow_step1" class="form-horizontal">
			<input type="hidden" name="module" value="Workflows">
			<input type="hidden" name="view" value="Edit">
			<input type="hidden" name="mode" value="Step2" />
			<input type="hidden" name="parent" value="Settings" />
			<input type="hidden" class="step" value="1" />
			<input type="hidden" name="record" value="{$RECORDID}" />

			<div class="padding1per" style="border:1px solid #ccc;box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.5);">
				<label><strong>{vtranslate('LBL_STEP_1',$QUALIFIED_MODULE)}: {vtranslate('LBL_ENTER_BASIC_DETAILS_OF_THE_WORKFLOW',$QUALIFIED_MODULE)}</strong></label><br>
				<div class="control-group">
					<div class="control-label">
						{vtranslate('LBL_SELECT_MODULE', $QUALIFIED_MODULE)}
					</div>
					<div class="controls">
						{if $MODE eq 'edit'}
							<input type='text' disabled='disabled' value="{vtranslate($MODULE_MODEL->getName(), $MODULE_MODEL->getName())}" >
							<input type='hidden' name='module_name' value="{$MODULE_MODEL->get('name')}" >
						{else}
							<select class="chzn-select" id="moduleName" name="module_name" required="true" data-placeholder="Select Module...">
								{foreach from=$ALL_MODULES key=TABID item=MODULE_MODEL}
									<option value="{$MODULE_MODEL->getName()}" {if $SELECTED_MODULE == $MODULE_MODEL->getName()} selected {/if}>{vtranslate($MODULE_MODEL->getName(), $MODULE_MODEL->getName())}</option>
								{/foreach}
							</select>
						{/if}
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						{vtranslate('LBL_DESCRIPTION', $QUALIFIED_MODULE)}<span class="redColor">*</span>
					</div>
					<div class="controls">
						<div class="row-fluid">
							<input type="text" name="summary" class="span6" data-validation-engine='validate[required]' value="{$WORKFLOW_MODEL->get('summary')}" id="summary" />
						</div>
					</div>
				</div>
				<div>
					{vtranslate('LBL_SPECIFY_WHEN_TO_EXECUTE', $QUALIFIED_MODULE)} :
				</div><br>
				<div style="margin-left: 16%">
					{assign var=WORKFLOW_MODEL_OBJ value=$WORKFLOW_MODEL->getWorkflowObject()}

					{foreach from=$TRIGGER_TYPES item=LABEL key=LABEL_ID}
						<div>
							<input type="radio" class="alignTop" name="execution_condition" {if $LABEL_ID eq 7}disabled=""{/if} {if $WORKFLOW_MODEL_OBJ->executionCondition eq $LABEL_ID} checked="" {/if} value="{$LABEL_ID}" />&nbsp;&nbsp;{vtranslate($LABEL,$QUALIFIED_MODULE)}<br><br>
							<div class="clearfix"></div>
						</div>
					{/foreach}
				</div>
			</div><br>
			<div class="pull-right">
				<button class="btn btn-success" type="submit" disabled="disabled"><strong>{vtranslate('LBL_NEXT', $QUALIFIED_MODULE)}</strong></button>
				<a class="cancelLink" type="reset" onclick="javascript:window.history.back();">{vtranslate('LBL_CANCEL', $QUALIFIED_MODULE)}</a>
			</div>
			<div class="clearfix"></div>
		</form>
	</div>
{/strip}