jQuery.noConflict();
function workflowlistscript($){
	
	function center(el){
		el.css({position: 'fixed'});
		var height = el.height();
		var width = el.width();
		el.css({
			position: 'fixed',
			top: '50%',
			left: '50%',
			'margin-left': (-width/2)+'px',
			'margin-top': (-height/2)+'px'
		});
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
		}
	}
	$(document).ready(function(){
		newWorkflowPopup = NewWorkflowPopup();
		$("#new_workflow").click(newWorkflowPopup.show);
		$("#pick_module").change(function(){
			$("#filter_modules").submit();
		});
	});
}
workflowlistscript(jQuery);