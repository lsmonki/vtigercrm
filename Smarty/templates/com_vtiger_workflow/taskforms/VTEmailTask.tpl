<script src="modules/com_vtiger_workflow/resources/vtigerwebservices.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
var moduleName = '{$entityName}';
</script>
<script src="modules/com_vtiger_workflow/resources/emailtaskscript.js" type="text/javascript" charset="utf-8"></script>

<table>
	<tr><td><b>*</b>Recepient:</td>
		<td><input type="text" name="recepient" value="{$task->recepient}" id="save_recepient" class="form_input"> <select id="task-emailfields"></select></td></tr>
	<tr><td><b>*</b>Subject:</td>
		<td><input type="text" name="subject" value="{$task->subject}" id="save_subject" class="form_input"></td></tr>
</table>
<p>
<select id='task-fieldnames'></select>
</p>
<p>
	<textarea name="content" rows="15" cols="40" id="save_content">{$task->content}</textarea>
</p>



