
/*JSON.js is required*/

var ajaxURL = "ajaxindex.php";

function doAjax(method,url, params, callbk, global){
	
	ajaxRequestProperties = {		queue: {position: 'end', scope: 'command'},
						method: 'post',
						postBody: params,
						onComplete: callbk
					}
	
	new Ajax.Request(url, ajaxRequestProperties);

}

function showWorking(resultNode, global){
	
	if(global){
		showGlobalWorking()
	}else{
		showLocalWorking(resultNode)
	}
	
}

function showGlobalWorking(){
	
	var node = getBusyNode();
	if(typeof node == "undefined" || node == null){
		return;
	}
	node.style.visibility = "visible";
	if(node.style.display == "none"){
		node.style.display = "";
	}
}

function showLocalWorking(resultNode){
	
	var node = getBusyNode();
	var localNode = node.cloneNode(true);
	localNode.id = "tmpbusy";
	localNode.style.display = "";
	localNode.style.visibility = "visible";
	localNode.style.position = "relative";
	localNode.style.top = "50%";
	localNode.style.left = "50%";
	resultNode.innerHTML = '';
	resultNode.appendChild(localNode);
}

function hideWorking(resultNode,global){
	
	if(global){
		hideGlobalWorking()
	}else{
		hideLocalWorking(resultNode)
	}	
}

function hideGlobalWorking(){
	
	var node = getBusyNode();
	if(typeof node == "undefined" || node == null){
		return;
	}
	node.style.visibility = "hidden";
	
}

function getBusyNode(nodeId){
	if(typeof nodeId == "undefined"){
		nodeId = 'vtbusy_info';
	}
	return document.getElementById(nodeId);
}

function doUpdateStatus(module,view,elementId,eventStatus,eventCategory,resultNode){
	var params = "?module="+module+"&view="+view;
	
	var viewParams = {"action": "changestatus","elementId": elementId,"status": eventStatus,"eventCategory": eventCategory};
	params += "&viewparams="+JSON.stringify(viewParams);
	
	var global = true;
	showWorking(resultNode,global);
	doAjax("post",ajaxURL,params,function(response){
			handleUpdateResponse(response,resultNode);
		},true);
	hideWorking(resultNode,global);
}

function doCreateFollowup(view,eventCategory,recordId,followupparams,resultNode){
	var params = "view="+view;
	var viewParams = {"action": "createfollowup","elementId": recordId,"eventCategory": eventCategory,
						"followupparams": followupparams};
	params += "&viewparams="+JSON.stringify(viewParams);
	var global = true;
	showWorking(resultNode,global);
	doAjax("post",ajaxURL,params,function(response){
		hideNodesById(['shim']);
			handleUpdateResponse(response,resultNode);
		},true);
	hideWorking(resultNode,global);
}

function handleEventMenu(obj,view,recordId,menuNodeId,resultElementId){
	
	var heldstatus = "Held";
	var notheldstatus = "Not Held";
	var activityMode = "Events";
	if(menuNodeId == "eventcalAction"){
		hideNodesById(["taskcalAction","act_changeowner"]);
	}else if(menuNodeId == "taskcalAction"){
		heldstatus = "Completed";
		notheldstatus = "Deferred";
		activityMode = "Task";
		hideNodesById(["eventcalAction","act_changeowner"]);
	}else{
		hideNodesById(["taskcalAction","eventcalAction"]);
	}
	
	showMenuAtNode(obj,menuNodeId);
	
	var createFollowUpLink = document.getElementById("createfollowup");
	if(activityMode == "Events"){
		var complete = document.getElementById("complete");
		var pending = document.getElementById("pending");
		var postpone = document.getElementById("postpone");
		var actdelete =	document.getElementById("actdelete");
		var changeowner = document.getElementById("changeowner");
		var duplicate = document.getElementById("eventduplicate");
	}else{
		var complete = document.getElementById("taskcomplete");
		var pending = document.getElementById("taskpending");
		var postpone = document.getElementById("taskpostpone");
		var actdelete = document.getElementById("taskactdelete");
		var changeowner = document.getElementById("taskchangeowner");
		var duplicate = document.getElementById("taskduplicate");
	}
	if(complete){
		complete.href="javascript:doUpdateStatus(Calendar,'"+view+"',"+recordId+",'"+heldstatus+"','"+activityMode+"',document.getElementById('"+resultElementId+"'))";
	}
	if(pending){
		pending.href="javascript:doUpdateStatus(Calendar,'"+view+"',"+recordId+",'"+notheldstatus+"','"+activityMode+"',document.getElementById('"+resultElementId+"'))";
	}

	if(postpone){
		postpone.href="index.php?module=Calendar&action=EditView&record="+recordId+"&return_action=index&return_module=Home&activity_mode="+activityMode;
	}
	if(actdelete){
		actdelete.onclick=function(){
								deleteActivity(view,recordId,activityMode,document.getElementById(resultElementId));
							}
	}
	if(changeowner){
		changeowner.href="javascript:void(0);";
		changeowner.onclick = function(){
									handleChangeOwnerMenu(obj,view,recordId,'act_changeowner',resultElementId);
								}
	}
	if(duplicate){
		duplicate.href="index.php?module=Calendar&action=EditView&record="+recordId+"&return_action=index&return_module=Home&activity_mode="+activityMode+"&isDuplicate=true";
	}
	if(createFollowUpLink){
		createFollowUpLink.onclick = function(){
			handleCreateFollowUp(view,recordId,'createfollowupdiv',resultElementId);
		}
	}
}

function hideNodesById(idArray){
	
	for(var index = 0;index < idArray.length; ++index){
		node = document.getElementById(idArray[index]);
		if(typeof node != "undefined" && node != null){
			node.style.display = "none";
		}
	}
	
}

function showMenuAtNode(node,menuNodeId){
	
	var menuNode = document.getElementById(menuNodeId);
	menuNode.style.display = 'block';
	menuNode.style.visibility = "visible";
	
	var leftSide = findPosX(node);
	var topSide = findPosY(node);
	
	menuNode.style.right = getWindowWidth() - leftSide + 'px';
	menuNode.style.top= topSide + 'px';
	
}

function getWindowWidth(){
	
	return (document.body.offsetWidth)? document.body.offsetWidth : window.innerWidth;
}

function handleChangeOwnerMenu(obj,view,recordId,menuNodeId,resultElementId){
	hideNodesById(["taskcalAction","eventcalAction"]);
	showMenuAtNode(obj,menuNodeId);
	document.change_owner.button[0].onclick = function(){
		doChangeOwner(view,recordId,menuNodeId,document.getElementById(resultElementId));
		hideNodesById([menuNodeId]);
	}
}

function doChangeOwner(view,recordId,menuNodeId,resultNode){
	
	var checked = document.change_owner.user_lead_owner[0].checked;
	var ownerType = "User";
	if(checked==true){
		var ownerId = document.getElementById('lead_owner').options[document.getElementById('lead_owner').options.selectedIndex].value;
	}else {
		ownerType = "Group";
		var ownerId = document.getElementById('lead_group_owner').options[document.getElementById('lead_group_owner').options.selectedIndex].value;
	}
	
	var params = "view="+view;
	
	var viewParams = {"action": "changeOwner","elementId": recordId,"ownerType": ownerType,"ownerId": ownerId};
	params += "&viewparams="+JSON.stringify(viewParams);
	
	var global = true;
	showWorking(resultNode,global);
	doAjax("post",ajaxURL,params,function(response){
			handleUpdateResponse(response,resultNode);
		},true);
	hideWorking(resultNode,global);
	
}

var activityViews = new Array('upcomingActivities','pendingActivities');

function deleteActivity(view, recordId,eventCategory,resultNode){
	
	if(!confirm(alert_arr.SURE_TO_DELETE)){
		return;
	}
	
	var params = "view="+view;
	var viewParams = {"action": "delete","elementId": recordId,"eventCategory": eventCategory};
	params += "&viewparams="+JSON.stringify(viewParams);
	
	var global = true;
	showWorking(resultNode,global);
	doAjax("post",ajaxURL,params,function(response){
			triggerRelated(activityViews,view);
			handleUpdateResponse(response,resultNode);
		},true);
	hideWorking(resultNode,global);
}

function triggerRelated(viewList,currentView){

	for(var index=0;index<viewList.length;++index){
		if(viewList[index].toLowerCase() != currentView.toLowerCase()){
			resultNode = document.getElementById(viewList[index]);
			doRefresh(viewList[index],resultNode);
		}
	}

}

function doRefresh(view,resultNode){
	
	params = "view="+view;
	viewParams = {"action": "refresh"};
	params += "&viewparams="+JSON.stringify(viewParams);
	
	global = true;
	showWorking(resultNode,global);
	doAjax("post",ajaxURL,params,function(response){
			triggerRelated(activityViews,view);
			handleUpdateResponse(response,resultNode);
		},true);
	hideWorking(resultNode,global);
	
}

function handleUpdateResponse(response,resultNode){
	var response = JSON.parse(response.responseText);
	if(response.success == "true" || response.success == true ){
		resultNode.innerHTML = response.result;
		window.setTimeout(function(){
								scriptNodes = resultNode.getElementsByTagName("script");
								for(var scriptNodeIndex=0;scriptNodeIndex<scriptNodes.length;++scriptNodeIndex){
									eval(scriptNodes[scriptNodeIndex].innerHTML);
								}
							},200);
	}else{
		showError(response.error);
	}
}

function handleCreateFollowUp(view, recordId, nodeId,resultElementId){
	
	showDisabledShim();
	var node = document.getElementById(nodeId);
	node.style.display = "";
	node.style.visibility = "visible";
	
	var global = false;
	showWorking(node,global);
	placeAtCenter(node);
	var params = "view="+view;
	var viewParams = {"action": "createfollowupform","elementId": recordId,"viewNodeId": resultElementId};
	params += "&viewparams="+JSON.stringify(viewParams);
	
	doAjax("post",ajaxURL,params,function(response){
			handleUpdateResponse(response,node);
		},true);
	window.setTimeout(function(){
						if(isIE()){
							fixAtViewPortCenter(node);
						}else{
							placeAtCenter(node);
						}
					},1000);
}

function isIE(){
	return navigator.userAgent.indexOf("MSIE") !=-1;
}

function placeAtCenter(node){
	var centerPixel = getViewPortCenter()
	node.style.position = "absolute";
	var point = getDimension(node);
	
	node.style.top = centerPixel.y - point.y/2 +"px";
	node.style.right = centerPixel.x - point.x/2 + "px";
}

function getDimension(node){
	
	var ht = node.offsetHeight;
	var wdth = node.offsetWidth;
	var nodeChildren = node.getElementsByTagName("*");
	var noOfChildren = nodeChildren.length;
	for(var index =0;index<noOfChildren;++index){
		ht = Math.max(nodeChildren[index].offsetHeight, ht);
		wdth = Math.max(nodeChildren[index].offsetWidth,wdth);
	}
	return {x: wdth,y: ht};
}



/** requires prototype.js
 * 
 */
function fixAtViewPortCenter(node){
	placeAtCenter(node);
	Event.observe(window, 'scroll', function(){
										placeAtCenter(node);
									});
}

function getViewPortSize(){
	
	var height;
	var width;
	
	if(document.documentElement && typeof document.body.scrollWidth != "undefined"){
		height = document.body.scrollHeight;
		width = document.body.scrollWidth;
	}else{
		var height = window.screen.availHeight;
		var width = window.screen.availWidth;
	}
	return {x: width,y: height};
	
}

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

function showDisabledShim(){
	
	var viewPortSize = getViewPortSize();
	var shim = document.getElementById("shim");
	shim.classname='veil';
	shim.style.height = viewPortSize.y+"px";
	shim.style.width = viewPortSize.x+"px";
	shim.style.visibility = "visible";
	shim.style.display = "block";
	
}

