jQuery.noConflict();
function workflowlistscript($){
//The following was copied from Homestuff.js
	/**
	 * this function returns the center co-ordinates of the viewport as an array
	 */
	function getViewPortCenter(){
	var height;
	var width;

	if(typeof window.pageXOffset != "undefined"){
		height = window.innerHeight/2;
		width = window.innerWidth/2;
		height +=window.pageYOffset;
		width +=window.pageXOffset;
	}else if(document.documentElement && typeof document.documentElement.scrollTop != "undefined"){
		height = document.documentElement.clientHeight/2;
		width = document.documentElement.clientWidth/2;
		height += document.documentElement.scrollTop;
		width += document.documentElement.scrollLeft;
	}else if(document.body && typeof document.body.clientWidth != "undefined"){
		var height = window.screen.availHeight/2;
		var width = window.screen.availWidth/2;
		height += document.body.clientHeight;
		width += document.body.clientWidth;
	}
	return {x: width,y: height};
	}


  /**
 * this function accepts a node and puts it at the center of the screen
 * @param object node - the dom object which you want to set in the center
 */
function placeAtCenter(node){
  var centerPixel = getViewPortCenter();
	node.style.position = "absolute";
	var point = getDimension(node);

	node.style.top = centerPixel.y - point.y/2 +"px";
	node.style.right = centerPixel.x - point.x/2 + "px";
}
//The previous bit was copied from Homestuff.js

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
