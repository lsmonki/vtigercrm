jQuery.noConflict();
function workflowlistscript($){


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

	function center(el){
		el.css({position: 'absolute'});
		el.width("400px");
		el.height("110px");
		placeAtCenter(el.get(0));
	}

	function NewWorkflowPopup(){
		function close(){
			$('#new_workflow_popup').css('display', 'none');
		}

		function show(module){
			$('#new_workflow_popup').css('display', 'block');
			center($('#new_workflow_popup'));
		}

		$('#new_workflow_popup_close').click(close);
		$('#new_workflow_popup_cancel').click(close);
		return {
			close:close,show:show
		};
	}


	var workflowCreationMode='from_module';
	var templatesForModule = {};
	function updateTemplateList(){
		var moduleSelect = $('#module_list');
		var currentModule = moduleSelect.attr('value');
		function fillTemplateList(templates){
			var templateSelect = $('#template_list');
			templateSelect.empty();
			$.each(templates, function(i, v){
				templateSelect.append('<option value="'+v['id']+'">'+
											v['title']+'</option>');
			});

		}
		if(templatesForModule[currentModule]==null){

			jsonget('templatesformodulejson',
																				 {module_name:currentModule},
																				 function(templates){
				templatesForModule[currentModule] = templates;
				fillTemplateList(templatesForModule[currentModule]);
			});
		}else{
			fillTemplateList(templatesForModule[currentModule]);
		}
	}

	$(document).ready(function(){
		var newWorkflowPopup = NewWorkflowPopup();
		$("#new_workflow").click(newWorkflowPopup.show);
		$("#pick_module").change(function(){
			$("#filter_modules").submit();
		});


		$('.workflow_creation_mode').click(function(){
			var el = $(this);
			workflowCreationMode = el.attr('value');
			if(workflowCreationMode=='from_template'){
				updateTemplateList();
				$('#template_select_field').css('display', null);
			}else{
				$('#template_select_field').css('display', 'none');
			}

		});
		$('#module_list').change(function(){
			if(workflowCreationMode=='from_template'){
				updateTemplateList();
			}
		});

		var filterModule = $('#pick_module').attr('value');
		if(filterModule!='All'){
			$('#module_list').attr('value', filterModule);
			$('#module_list').change();
		}
	});
}
workflowlistscript(jQuery);
