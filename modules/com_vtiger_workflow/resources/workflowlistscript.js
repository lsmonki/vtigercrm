jQuery.noConflict();
function workflowlistscript($){
	
	function center(el){
		el.css({position: 'absolute'});
		el.width("400px");
		el.height("110px");
		positionDivToCenter(el.attr('id'));
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
