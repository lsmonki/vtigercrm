<?php
require_once("include/utils/DeleteUtils.php");

class VtigerCRMObject{
	
	private $moduleName ;
	private $moduleId ;
	private $instance ;
	
	function VtigerCRMObject($moduleCredential, $isId=false){
		
		if($isId){
			$this->moduleId = $moduleCredential;
			$this->moduleName = $this->getObjectTypeName($this->moduleId);
		}else{
			$this->moduleName = $this->titleCase($moduleCredential);
			$this->moduleId = $this->getObjectTypeId($this->moduleName);
		}
		$this->instance = null;
		
	}
	
	public function getModuleName(){
		return $this->moduleName;
	}
	
	public function getModuleId(){
		return $this->moduleId;
	}
	
	public function getInstance(){
		if($this->instance == null){
			$this->instance = $this->getModuleClassInstance($this->moduleName);
		}
		return $this->instance;
	}
	
	public function getObjectId(){
		return $this->instance->id;
	}
	
	public function setObjectId($id){
		$this->instance->id = $id;
	}
	
	private function titleCase($str){
		$first = substr($str, 0, 1);
		return strtoupper($first).substr($str,1);
	}
	
	private function getObjectTypeId($objectName){
		
		global $adb;
		
		$sql = "select * from vtiger_tab where name=?;";
		$params = array($objectName);
		$result = $adb->pquery($sql, $params);
		$data1 = $adb->fetchByAssoc($result,1,false);
		
		$tid = $data1["tabid"];
		
		return $tid;
		
	}
	
	private function getModuleClassInstance($moduleName){
		$this->includeModule($moduleName);
		if($moduleName == "Calendar"){
			$moduleName = "Activity";
		}
		if($moduleName == "Portal"){
			$bt = debug_backtrace();
			foreach($bt as $ind=>$st){
				print_r(array('file'=>$st['file'],'line'=>$st['line'],'function'=>'function'));
			}
		}
		return new $moduleName();
	}
	
	function includeModule($moduleName){
		if($moduleName == "Calendar"){
			require_once("modules/".$moduleName."/Activity.php");
		}else{
			require_once("modules/".$moduleName."/".$moduleName.".php");
		}
	}
	
	private function getObjectTypeName($moduleId){
		
		global $adb;
		
		$sql = "select * from vtiger_tab where tabid=?";
		$params = array($moduleId);
		$result = $adb->pquery($sql, $params);
		$data = $adb->fetchByAssoc($result,1,false);
		return $data["name"];
		
	}
	
	public function read($id){
		global $adb;
		
		$error = false;
		$adb->startTransaction();
		$this->instance->retrieve_entity_info($id,$this->moduleName);
		$error = $adb->hasFailedTransaction();
		$adb->completeTransaction();
		return !$error;
	}
	
	public function create($element){
		global $adb;
		
		$error = false;
		
		foreach($element as $k=>$v){
			$this->instance->column_fields[$k] = $v;
		}
		
		$adb->startTransaction();
		$this->instance->Save($this->moduleName);
		$error = $adb->hasFailedTransaction();
		$adb->completeTransaction();
		return !$error;
	}
	
	public function update($element){
		
		global $adb;
		$error = false;
		
		foreach($element as $k=>$v){
			$this->instance->column_fields[$k] = $v;
		}
		
		$adb->startTransaction();
		$this->instance->mode = "edit";
		$this->instance->Save($this->moduleName);
		$error = $adb->hasFailedTransaction();
		$adb->completeTransaction();
		return !$error;
	}
	
	public function delete($id){
		global $adb;
		$error = false;
		$adb->startTransaction();
		DeleteEntity($this->moduleName, $this->moduleName, $this->instance, $id,$returnid);
		$error = $adb->hasFailedTransaction();
		$adb->completeTransaction();
		return !$error;
	}
	
	public function getFields(){
		return $this->instance->column_fields;
	}
	
	function getIdComponents($objectId){
		return explode("x",$objectId);
	}
	
	function getIdFromComponents($objectTypeId, $databaseId){
		return $objectTypeId."x".$databaseId;
	}
	
	function exists($id){
		global $adb;
		
		$exists = false;
		$sql = "select * from vtiger_crmentity where crmid=? and deleted=0";
		$result = $adb->pquery($sql , array($id));
		if($result != null && isset($result)){
			if($adb->num_rows($result)>0){
				$exists = true;
			}
		}
		return $exists;
	}
	
	function getSEType($id){
		global $adb;
		
		$seType = null;
		$sql = "select * from vtiger_crmentity where crmid=? and deleted=0";
		$result = $adb->pquery($sql , array($id));
		if($result != null && isset($result)){
			if($adb->num_rows($result)>0){
				$seType = $adb->query_result($result,0,"setype");
			}
		}
		return $seType;
	}
	
}

?>
