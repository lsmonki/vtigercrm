{include file='com_vtiger_workflow/Header.tpl'}
<script src="modules/{$module->name}/resources/jquery-1.2.6.js" type="text/javascript" charset="utf-8"></script>
<script src="modules/{$module->name}/resources/functional.js" type="text/javascript" charset="utf-8"></script>
<script src="modules/{$module->name}/resources/json2.js" type="text/javascript" charset="utf-8"></script>
<script src="modules/{$module->name}/resources/edittaskscript.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
	jQuery.noConflict();
	fn.addStylesheet('modules/{$module->name}/resources/style.css');
	edittaskscript(jQuery);
</script>
{include file='SetMenu.tpl'}
<div id="view">
	{include file='com_vtiger_workflow/ModuleTitle.tpl'}
	<table class="tableHeading" width="75%" border="0" cellspacing="0" cellpadding="5">
		<tr>
			<td class="big" nowrap="">
				<strong>{$MOD.LBL_SUMMARY}</strong>
			</td>
		</tr>
	</table>
	<form name="new_task">
		<table border="0" cellpadding="5" cellspacing="0" width="75%">
			<tr>
				<td class="dvtCellLabel" align=right width=25%>{$MOD.LBL_TASK_TITLE}</td>
				<td class="dvtCellInfo" align="left" colspan="3"><input type="text" name="summary" value="{$task->summary}" id="save_summary"></td>
			</tr>
			<tr>
				<td class="dvtCellLabel" align=right width=25%>{$MOD.LBL_PARENT_WORKFLOW}</td>
				<td class="dvtCellInfo" align="left" colspan="3">
					{$workflow->id} {$workflow->description}
					<input type="hidden" name="workflow_id" value="{$workflow->id}" id="save_workflow_id">
				</td>
			</tr>
			<tr>
				<td class="dvtCellLabel" align=right width=25%>{$MOD.LBL_STATUS}</td>
				<td class="dvtCellInfo" align="left" colspan="3">
					<select name="active">
						<option value="true">{$MOD.LBL_ACTIVE}</option>
						<option value="false" {if not $task->active}selected{/if}>{$MOD.LBL_INACTIVE}</option>
					</select> 
				</td>
			</tr>
		</table>
		<h4><input type="checkbox" name="check_select_date" value="" id="check_select_date" {if $trigger neq null}checked{/if}> 
			{$MOD.MSG_EXECUTE_TASK_DELAY}.</h4>
		<div id="select_date" style="display:none;">
			<input type="text" name="select_date_days" value="{$trigger.days}" id="select_date_days" cols="3"> days 
			<select name="select_date_direction">
				<option {if $trigger.direction eq 'after'}selected{/if} value='after'>{$MOD.LBL_AFTER}</option>
				<option {if $trigger.direction eq 'after'}selected{/if} value='before'>{$MOD.LBL_BEFORE}</option>
			</select> 
			<select name="select_date_field">
{foreach key=name item=label from=$dateFields}
				<option value='{$name}' {if $trigger->name eq $name}selected{/if}>
					{$label}
				</option>
{/foreach}
			</select> 
		</div>
		<br>
		<table class="tableHeading" border="0"  width="100%" cellspacing="0" cellpadding="5">
			<tr>
				<td class="big" nowrap="">
					<strong>{$MOD.LBL_TASK_OPERATIONS}</strong>
				</td>
			</tr>
		</table>
{include file="$taskTemplate"}
		<input type="hidden" name="save_type" value="{$saveType}" id="save_save_type">
{if $edit}
		<input type="hidden" name="task_id" value="{$task->id}" id="save_task_id">
{/if}
		<input type="hidden" name="task_type" value="{$taskType}" id="save_task_type">
		<input type="hidden" name="action" value="savetask" id="save_action">
		<input type="hidden" name="module" value="{$module->name}" id="save_module">
		<input type="hidden" name="return_url" value="{$returnUrl}" id="save_return_url">
		<p><input type="submit" name="save" value="{$APP.LBL_SAVE_BUTTON_LABEL}" id="save"></p>
	</form>
</div>
{include file='com_vtiger_workflow/Footer.tpl'}
