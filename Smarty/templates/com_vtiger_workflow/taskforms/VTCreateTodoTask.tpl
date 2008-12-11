<script src="modules/com_vtiger_workflow/resources/resources/jquery-1.2.6.js" type="text/javascript" charset="utf-8"></script>
<script src="modules/com_vtiger_workflow/functional.js" type="text/javascript" charset="utf-8"></script>
<script src="modules/com_vtiger_workflow/resources/json2.js" type="text/javascript" charset="utf-8"></script>
<script src="modules/com_vtiger_workflow/resources/vtigerwebservices.js" type="text/javascript" charset="utf-8"></script>

<script type="text/javascript" charset="utf-8">
var moduleName = '{$entityName}';
var taskStatus = '{$task->status}';
var taskPriority = '{$task->priority}';
{literal}
function VTCreateTodoTask($){
	
	var map = fn.map;
	var dict = fn.dict;
	var filter = fn.filter;
	var reduceR = fn.reduceR;
	var parallelExecuter = fn.parallelExecuter;
	var contains = fn.contains;
	var concat = fn.concat;
	
	function errorDialog(message){
		alert(message);
	}
	
	function index(arr, field){
		return dict(map(function(e){return [e[field], e];}, arr));
	}
	
	function handleError(fn){
		return function(status, result){
			if(status){
				fn(result);
			}else{
				errorDialog('Failure:'+result);
			}
		}
	}
	
	var vtinst = new VtigerWebservices("webservice.php");
	vtinst.extendSession(handleError(function(result){
		$(document).ready(function(){
			vtinst.describeObject('Calendar', handleError(function(result){
				var fields = result['fields'];
				var fieldsMap = index(fields, 'name');
				var eventStatusType = fieldsMap['taskstatus'];
				var eventStatusValues = eventStatusType['type']['picklistValues'];
				
				var taskPriorityType = fieldsMap['taskpriority'];
				var taskPriorityValues = taskPriorityType['type']['picklistValues'];
				
				var status = $('#task_status');
				$.each(eventStatusValues, function(i, v){
					status.append('<option value="'+v['value']+'">'+v['label']+'</option>');
				});
				status.attr('value', taskStatus);
				var priority = $('#task_priority');
				$.each(taskPriorityValues, function(i, v){
					priority.append('<option value="'+v['value']+'">'+v['label']+'</option>');
				});
				priority.attr('value', taskPriority);
				
			}));
		});
	}));
}
VTCreateTodoTask(jQuery);
{/literal}
</script>


<div id="view">
	<table border="0" cellspacing="5" cellpadding="5">
		<tr><td>Todo</td>
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
			<td><input type="text" name="time" value="{$task->time}" id="workflow_time" style="width:60px"> (HH:MM 24 hour time)</td></tr>
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
