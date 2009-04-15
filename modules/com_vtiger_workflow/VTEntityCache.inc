<?php


class VTWorkflowEntity{
	function __construct($user, $id){
		$this->moduleName = null;
		$this->id = $id;
		$this->user = $user;
		$data = vtws_retrieve($id, $user);
		foreach($data as $key => $value){
			if(is_string($value)){
				$data[$key] = html_entity_decode($value, ENT_QUOTES, 'utf-8');
			}
		}
		$this->data = $data;
	}
	/**
	 * Get the data from the entity object as an array.
	 *
	 * @return An array representation of the module data.
	 */
	function getData(){
		return $this->data;
	}
	
	/**
	 * Get the entity id.
	 *
	 * @return The entity id.
	 */
	function getId(){
		return $this->data['id'];
	}
	
	/**
	 * Get the name of the module represented by the entity data object.
	 *
	 * @return The module name.
	 */
	function getModuleName(){
		if($this->moduleName==null){
			global $adb;
			$wsId = $this->data['id'];
			$parts = explode('x', $wsId);
			$result = $adb->pquery('select name from vtiger_ws_entity where id=?', 
						 array($parts[0]));
			$rowData = $adb->raw_query_result_rowdata($result, 0);
			return $rowData['name'];

		}
		return $this->moduleName;
	}
	
	function get($fieldName){
		return $this->data[$fieldName];
	}
	
	function set($fieldName, $value){

		$this->data[$fieldName] = $value;
	}

	function save(){
		vtws_update($this->data,$this->user);
	}
	
}

class VTEntityCache{
	function __construct($user){
		$this->user = $user;
		$this->cache = array();
	}
	
	function forId($id){
		if($this->cache[$id]==null){
			$data  = new VTWorkflowEntity($this->user, $id);
			$this->cache[$id] = $data;
		}
		return $this->cache[$id];
	}
}
?>