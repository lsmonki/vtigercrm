<?php
require_once('include/events/SqlResultIterator.inc');

class VTEntityType{
	function __construct($adb, $setype){
		$this->moduleName=$setype;
		require_once("modules/".$setype."/".$setype.".php"); 
		$result = $adb->pquery("select tabid from vtiger_tab where name=?", 
			array($setype));
		$tabId = $adb->query_result($result,0,"tabid");
		$this->tabId = $tabId;
		$this->adb = $adb;
	}
	
	function getTabId(){
		return $this->tabId;
	}
	
	function getModuleName(){
		return $this->moduleName;
	}

	function getFieldNames(){
		$adb = $this->adb;
		$arr = array();
		$result = $adb->pquery("select fieldname from vtiger_field where tabid=?", 
			array($this->getTabId()));
		$it = new SQLResultIterator($adb, $result);
		foreach($it as $row){
			$arr[] = $row->fieldname;
		}
		return $arr;
	}
	
	function getFieldType($fieldName){
		$adb = $this->adb;
		$result = $adb->pquery("select * from vtiger_field where fieldname=? and tabid=?", 
			array($fieldName, $this->tabId));
		$uitype = $adb->query_result($result,0,"uitype");
		$fieldType = new VTFieldType();
		if(in_array($uitype, array(50, 51, 73))){
			$fieldType->type = "Related";
			$fieldType->relatedTo = "Accounts";	
		}else if($uitype == 71){
			$fieldType->type = "Number";
		}else{
			$fieldType->type = "String";
		}
		return $fieldType;
	}
	
	function getFieldTypes(){
		$adb = $this->adb;
		$fieldNames = $this->getFieldNames();
		$fieldTypes = array();
		foreach($fieldNames as $fieldName){
			$fieldTypes[$fieldName]=$this->getFieldType($fieldName);
		}
		return $fieldTypes;
	}
	

}

class VTFieldType{
	function toArray(){
		$ro = new ReflectionObject($this);
		$props = $ro->getProperties();
		$arr = array();
		foreach($props as $prop){
			$arr[$prop->getName()]=$prop->getValue($this);
		}
		return $arr;
	}
	
}

?>
