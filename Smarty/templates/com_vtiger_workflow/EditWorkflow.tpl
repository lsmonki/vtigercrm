<script src="modules/{$module->name}/resources/jquery-1.2.6.js" type="text/javascript" charset="utf-8"></script>
<script src="modules/{$module->name}/resources/functional.js" type="text/javascript" charset="utf-8"></script>
<script src="modules/{$module->name}/resources/json2.js" type="text/javascript" charset="utf-8"></script>
<script src="modules/{$module->name}/resources/vtigerwebservices.js" type="text/javascript" charset="utf-8"></script>
<script src="modules/{$module->name}/resources/parallelexecuter.js" type="text/javascript" charset="utf-8"></script>
<script src="modules/{$module->name}/resources/editworkflowscript.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
	jQuery.noConflict();
	fn.addStylesheet('modules/{$module->name}/resources/style.css');
	var moduleName = '{$workflow->moduleName}';
{if $workflow->test}
	var conditions = {$workflow->test};
{else}
	var conditions = null;
{/if}
	editworkflowscript(jQuery, conditions);
</script>

<div id="new_task_popup" class='layerPopup' style="display:none;">
	<table width="100%" cellspacing="0" cellpadding="5" border="0" class="layerHeadingULine">
		<tr>
			<td width="60%" align="left" class="layerPopupHeading">
				{$MOD.LBL_CREATE_TASK}
				</td>
			<td width="40%" align="right">
				<a href="javascript:void;" id="new_task_popup_close">
					<img border="0" align="absmiddle" src="themes/softed/images/close.gif"/>
				</a>
			</td>
		</tr>
	</table>

	<form action="index.php" method="get" accept-charset="utf-8">
	<div class="popup_content">
		{$MOD.LBL_CREATE_TASK_OF_TYPE} 
		<select name="task_type">
	{foreach item=taskType from=$taskTypes}
			<option>
				{$taskType}
			</option>
	{/foreach}
		</select>
		<input type="hidden" name="module_name" value="{$workflow->moduleName}">
		<input type="hidden" name="save_type" value="new" id="save_type_new">
		<input type="hidden" name="module" value="{$module->name}" id="save_module">
		<input type="hidden" name="action" value="edittask" id="save_action">
		<input type="hidden" name="return_url" value="{$newTaskReturnUrl}" id="save_return_url">
		<input type="hidden" name="workflow_id" value="{$workflow->id}">
	</div>
	<table width="100%" cellspacing="0" cellpadding="5" border="0" class="layerPopupTransport">
		<tr><td align="center">
			<input type="submit" class="crmButton small save" value="{$APP.LBL_CREATE_BUTTON_LABEL}" name="save" id='new_task_popup_save'/> 
			<input type="button" class="crmButton small cancel" value="{$APP.LBL_CANCEL_BUTTON_LABEL} " name="cancel" id='new_task_popup_cancel'/>
		</td></tr>
	</table>
	</form>
</div>
{include file='SetMenu.tpl'}
<div id="view">
	{include file='com_vtiger_workflow/ModuleTitle.tpl'}
	
	<form name="new_workflow" action="index.php">
		<table>
			<tr>
				<td>{$MOD.LBL_SUMMARY}</td>
				<td colspan="3"><input type="text" name="description" id="save_description" value="{$workflow->description}"></td>
			</tr>
			<tr>
				<td>{$APP.LBL_MODULE}</td>
				<td>{$workflow->moduleName}</td>
			</tr>
		</table>
		<h4>{$MOD.LBL_WHEN_TO_RUN_WORKFLOW}</h4>
		<table border="0" >
			<tr><td><input type="radio" name="execution_condition" value="ON_FIRST_SAVE" 
				{if $workflow->executionConditionAsLabel() eq 'ON_FIRST_SAVE'}checked{/if}/></td> 
				<td>{$MOD.LBL_ONLY_ON_FIRST_SAVE}.</td></tr>
			<!-- <tr><td><input type="radio" name="execution_condition" value="ONCE" 
							{if $workflow->executionConditionAsLabel() eq 'ONCE'}checked{/if} /></td>
							<td>{$MOD.LBL_UNTIL_FIRST_TIME_CONDITION_TRUE}.</td></tr> -->
						<tr><td><input type="radio" name="execution_condition" value="ON_EVERY_SAVE" 
				{if $workflow->executionConditionAsLabel() eq 'ON_EVERY_SAVE'}checked{/if}/></td>
				<td>{$MOD.LBL_EVERYTIME_RECORD_SAVED}.</td></tr>
		</table>
		<br>
		<table class="tableHeading" width="75%" border="0" cellspacing="0" cellpadding="5">
			<tr>
				<td class="big" nowrap="">
					<strong>{$MOD.LBL_CONDITIONS}</strong>
				</td>
			</tr>
		</table>
		<table class="listTableTopButtons" width="75%" border="0" cellspacing="0" cellpadding="5">
			<tr>
				<td class="small"> <span id="status_message"></span> </td>
				<td class="small" align="right">
					<input type="button" class="crmButton create small" 
						value="{$MOD.LBL_NEW_CONDITION_BUTTON_LABEL}" id="save_conditions_add"/>
				</td>
			</tr>
		</table>
		<div id="save_conditions"></div>
		
		<p><input type="submit" id="save_submit" value="Save" class="crmButton small save"></p>
		<input type="hidden" name="module_name" value="{$workflow->moduleName}" id="save_modulename">
		<input type="hidden" name="save_type" value="{$saveType}" id="save_savetype">
{if $saveType eq "edit"}
		<input type="hidden" name="workflow_id" value="{$workflow->id}">
{/if}
		<input type="hidden" name="conditions" value="" id="save_conditions_json"/>
		<input type="hidden" name="action" value="saveworkflow" id="some_name">
		<input type="hidden" name="module" value="{$module->name}" id="some_name">
	</form>
{if $saveType eq "edit"}
	<table class="tableHeading" width="75%" border="0" cellspacing="0" cellpadding="5">
		<tr>
			<td class="big" nowrap="">
				<strong>{$MOD.LBL_TASKS}</strong>
			</td>
		</tr>
	</table>
	<table class="listTableTopButtons" width="75%" border="0" cellspacing="0" cellpadding="5">
		<tr>
			<td class="small"> <span id="status_message"></span> </td>
			<td class="small" align="right">
				<input type="button" class="crmButton create small" 
					value="{$MOD.LBL_NEW_TASK_BUTTON_LABEL}" id='new_task'/>
			</td>
		</tr>
	</table>
	<table class="listTable" width="75%" border="0" cellspacing="0" cellpadding="5" id='expressionlist'>
		<tr>
			<td class="colHeader small" width="70%">
				{$MOD.LBL_TASK}
			</td>
			<td class="colHeader small" width="15%">
				{$MOD.LBL_STATUS}
			</td>
			<td class="colHeader small" width="15%">
				{$MOD.LBL_LIST_TOOLS}
			</td>
		</tr>
{foreach item=task from=$tasks}
		<tr>
			<td>{$task->summary}</td>
			<td>{if $task->active}Active{else}Inactive{/if}</td>
			<td>
				<a href="{$module->editTaskUrl($task->id)}">
					<img border="0" title="Edit" alt="Edit" \
						style="cursor: pointer;" id="expressionlist_editlink_{$task->id}" \
						src="themes/softed/images/editfield.gif"/>
				</a>
				<a href="{$module->deleteTaskUrl($task->id)}">
					<img border="0" title="Delete" alt="Delete"\
			 			src="themes/softed/images/delete.gif" \
						style="cursor: pointer;" id="expressionlist_deletelink_{$task->id}"/>
				</a>
			</td>
		</tr>
{/foreach}
	</table>
{/if}
</div>