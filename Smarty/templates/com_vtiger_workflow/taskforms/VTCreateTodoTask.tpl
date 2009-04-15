<script src="modules/com_vtiger_workflow/resources/vtigerwebservices.js" type="text/javascript" charset="utf-8"></script>

<script type="text/javascript" charset="utf-8">
var moduleName = '{$entityName}';
var taskStatus = '{$task->status}';
var taskPriority = '{$task->priority}';
</script>

<script src="modules/com_vtiger_workflow/resources/createtodotaskscript.js" type="text/javascript" charset="utf-8"></script>

<div id="view">
	<table border="0" cellspacing="5" cellpadding="5">
		<tr><td><b>*</b>Todo</td>
			<td><input type="text" name="todo" value="{$task->todo}" id="workflow_todo"></td></tr>
		<tr><td>Description</td>
			<td><textarea name="description" rows="8" cols="40">{$task->description}</textarea></td></tr>
		<tr><td colspan="2">
			<table border="0" cellspacing="5" cellpadding="5">
				<tr><th>Status</th><th>Priority</th></tr>
				<tr><td><select id="task_status" value="{$task->status}" name="status"></select></td>
					<td><select id="task_priority" value="{$task->priority}" name="priority"></select></td></tr>
			</table></td></tr>
		<tr><td colspan="2"><hr size="1" noshade="noshade" /></td></tr>
		<tr><td>Time</td>
			<td><input type="hidden" name="time" value="{$task->time}" id="workflow_time" style="width:60px" class="time_field"></td></tr>
		<tr><td>Due Date</td>
			<td><input type="text" name="days" value="{$task->days}" id="days" style="width:30px"> 
				days 
				<select name="direction" value={$task->direction}>
					<option>After</option>
					<option>Before</option>
				</select>
				<select name="datefield" value="{$task->datefield}">
{foreach key=name item=label from=$dateFields}
					<option value='{$name}' {if $task->datefield eq $name}selected{/if}>
						{$label}
					</option>
{/foreach}
				</select>
				(The same value is used for the start date)</td></tr>
		<tr><td>Send Notification</td>
			<td><input type="checkbox" name="sendNotification" value="true" id="sendNotification" {if $task->sendNotification}checked{/if}></td></tr>
	</table>
</div>
