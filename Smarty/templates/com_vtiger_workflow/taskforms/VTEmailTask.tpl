<script src="modules/com_vtiger_workflow/resources/resources/jquery-1.2.6.js" type="text/javascript" charset="utf-8"></script>
<script src="modules/com_vtiger_workflow/functional.js" type="text/javascript" charset="utf-8"></script>
<script src="modules/com_vtiger_workflow/resources/json2.js" type="text/javascript" charset="utf-8"></script>
<script src="modules/com_vtiger_workflow/resources/vtigerwebservices.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
var moduleName = '{$entityName}';

{literal}


function VTEmailTask($){
	var vtinst = new VtigerWebservices("webservice.php");
	var desc = null;
	
	function map(fn, list){
		var out = [];
		$.each(list, function(i, v){
			out[out.length]=fn(v);
		});
		return out;
	}

	function dict(list){
		var out = {};
		$.each(list, function(i, v){
			out[v[0]] = v[1];
		});
		return out;
	}
	
	function filter(pred, list){
		var out = [];
		$.each(list, function(i, v){
			if(pred(v)){
				out[out.length]=v;
			}
		});
		return out;
	}
	
	
	function errorDialog(message){
		alert(message);
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

	function jsonget(operation, params, callback){
		var obj = {
				module:'com_vtiger_workflow', 
				action:'com_vtiger_workflowAjax',
				file:operation, ajax:'true'};
		$.each(params,function(key, value){
			obj[key] = value;
		});
		$.get('index.php', obj, 
			function(result){
				var parsed = JSON.parse(result);
				callback(parsed);
		});
	}
	
	
	$(document).ready(function(){
		vtinst.extendSession(handleError(function(result){
			vtinst.describeObject(moduleName, handleError(function(result){
				var fields = result['fields'];
				var fieldLabels = dict(map(function(e){return [e['name'], e['label']];}, fields));
				var mailFields = map(function(e){return e['name'];}, 
					filter(function(e){return e['type']['name']=='email';}, fields));
				var select = $('#task-fieldnames');
				$.each(fieldLabels, function(k, v){
					select.append('<option class="task-fieldnames_option" value="'+k+'">'+v+'</option>');
				});
				$('.task-fieldnames_option').click(function(){
					var textarea = $('#save_content').get(0);
					var value = '$'+$(this).attr('value');

					//http://alexking.org/blog/2003/06/02/inserting-at-the-cursor-using-javascript
					if (document.selection) {
						textarea.focus();
						var sel = document.selection.createRange();
						sel.text = value;
						textarea.focus();
					}else if (textarea.selectionStart || textarea.selectionStart == '0') {
						var startPos = textarea.selectionStart;
						var endPos = textarea.selectionEnd;
						var scrollTop = textarea.scrollTop;
						textarea.value = textarea.value.substring(0, startPos)
											+ value
											+ textarea.value.substring(endPos,
												textarea.value.length);
						textarea.focus();
						textarea.selectionStart = startPos + value.length;
						textarea.selectionEnd = startPos + value.length;
						textarea.scrollTop = scrollTop;
					}	else {
						textarea.value += value;
						textarea.focus();
					}
				});
				
				var select = $('#task-emailfields');
				$.each(mailFields, function(i, v){
					select.append('<option class="task-emailfields_option" value="'+v+'">' + fieldLabels[v] + '</option>');
				});
				$('.task-emailfields_option').click(function(){
					var input = $($('#save_recepient').get());
					var value = '$'+$(this).attr('value');
					input.attr("value", input.attr("value")+'; '+value);
				});
			}));
		}));
	});
	
	
}
vtEmailTask = VTEmailTask(jQuery)
{/literal}
</script>
<table>
	<tr><td>Recepient:</td>
		<td><input type="text" name="recepient" value="{$task->recepient}" id="save_recepient" class="form_input"> <select id="task-emailfields"></select></td></tr>
	<tr><td>Subject:</td>
		<td><input type="text" name="subject" value="{$task->subject}" id="save_subject" class="form_input"></td></tr>
</table>
<p>
<select id='task-fieldnames'></select>
</p>
<p>
	<textarea name="content" rows="15" cols="40" id="save_content">{$task->content}</textarea>
</p>



