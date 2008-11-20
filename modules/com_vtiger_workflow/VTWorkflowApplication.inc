<?php
class VTWorkflowApplication{
	function __construct($action){
		global $_REQUEST;
		$this->request;
		$this->name = "com_vtiger_workflow";
		$this->label = "Workflow";
		$this->action = $action;
	}
	
	function currentUrl(){
		// $req = $this->request;
		// $url = "index.php?module={$this->name}&action={$this->action}";
		// if($this->action=='editworkflow'){
		// 	if(isset($req['workflow_id'])){
		// 		$url.="&workflow_id=".$req['workflow_id'];
		// 	}
		// }else if($this->action=='edittask'){
		// 	if(isset($req['task_id'])){
		// 		$url.="&task_id=".$req['task_id'];
		// 	}
		// }
		return $_SERVER["REQUEST_URI"];			
	}
	
	function listViewUrl(){
		return "index.php?module={$this->name}&action=workflowlist";
	}
	
	function editWorkflowUrl($id=null){
		if($id!=null){
			$idPart="&workflow_id=$id";
		}
		return "index.php?module={$this->name}&action=editworkflow$idPart&return_url=".urlencode($this->currentUrl());
	}
	
	function deleteWorkflowUrl($id){
		$idPart="&workflow_id=$id";
		return "index.php?module={$this->name}&action=deleteworkflow$idPart&return_url=".urlencode($this->currentUrl());
	}
	
	function editTaskUrl($id=null){
		if($id!=null){
			$idPart="&task_id=$id";
		}
		return "index.php?module={$this->name}&action=edittask$idPart&return_url=".urlencode($this->currentUrl());
	}
}
?>