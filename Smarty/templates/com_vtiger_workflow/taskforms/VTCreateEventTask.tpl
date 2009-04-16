<script src="modules/com_vtiger_workflow/resources/vtigerwebservices.js" type="text/javascript" charset="utf-8"></script>

<script type="text/javascript" charset="utf-8">
var moduleName = '{$entityName}';
var eventStatus = '{$task->status}';
var eventType = '{$task->eventType}';
</script>
<script src="modules/com_vtiger_workflow/resources/createeventtaskscript.js" type="text/javascript" charset="utf-8"></script>


<div id="view">
	<table border="0" cellspacing="5" cellpadding="5">
		<tr><td><b>*</b> Event Name</td>
			<td><input type="text" name="eventName" value="{$task->eventName}" id="workflow_eventname"></td></tr>
		<tr><td>Description</td>
			<td><textarea name="description" rows="8" cols="40">{$task->description}</textarea></td></tr>
		<tr><td colspan="2">
			<table border="0" cellspacing="5" cellpadding="5">
				<tr><th>Status</th><th>Type</th></tr>
				<tr><td><select id="event_status" value="{$task->status}" name="status"></select></td>
					<td><select id="event_type" value="{$task->eventType}" name="eventType"></select></td></tr>
			</table></td></tr>
		<tr><td colspan="2"><hr size="1" noshade="noshade" /></td></tr>
		<tr><td>Start Time</td>
			<td><input type="hidden" name="startTime" value="{$task->startTime}" id="workflow_time" style="width:60px"  class="time_field"></td></tr>
		<tr><td>Start Date</td>
			<td><input type="text" name="startDays" value="{$task->startDays}" id="start_days" style="width:30px"> 
				days 
				<select name="startDirection" value={$task->startDirection}>
					<option>After</option>
					<option>Before</option>
				</select>
				<select name="startDatefield" value="{$task->startDatefield}">
{foreach key=name item=label from=$dateFields}
					<option value='{$name}' {if $task->startDatefield eq $name}selected{/if}>
						{$label}
					</option>
{/foreach}
				</select></td></tr>
		<tr><td>End Time</td>
			<td><input type="hidden" name="endTime" value="{$task->endTime}" id="end_time" style="width:60px" class="time_field"></td></tr>
		<tr><td>End Date</td>
			<td><input type="text" name="endDays" value="{$task->endDays}" id="end_days" style="width:30px"> 
				days 
				<select name="endDirection" value={$task->endDirection}>
					<option>After</option>
					<option>Before</option>
				</select>
				<select name="endDatefield" value="{$task->endDatefield}">
{foreach key=name item=label from=$dateFields}
					<option value='{$name}' {if $task->endDatefield eq $name}selected{/if}>
						{$label}
					</option>
{/foreach}
				</select></td></tr>
	</table>
</div>
