<script type="text/javascript" charset="utf-8">
var moduleName = '{$entityName}';
var methodName = '{$task->methodName}';
{literal}
	function entityMethodScript($){
		
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
			jsonget('entitymethodjson', {module_name:moduleName}, function(result){
				if(result.length==0){
					$('#method_name_select').css("display", "none");
					$('#message_text').css("display", "inline");
				}else{
					$('#method_name_select').css("display", "inline");
					$('#message_text').css("display", "none");
					
					$.each(result, function(i, v){
						var optionText = '<option value="'+moduleName+'" '+(v==moduleName?'selected':'')+'>'+moduleName+'</option>';
						$('#method_name_select').append(optionText);
					});
				}
			});
		});
	}
{/literal}
entityMethodScript(jQuery);
</script>


<span>Method Name</span> : 
<select name="methodName" id="method_name_select"></select>
<sspan id="message_text">No method is available for this module.</sspan>