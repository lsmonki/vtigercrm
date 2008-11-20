<?php

class VTEntityCache{
	function __construct($adb){
		$this->adb = $adb;
		$this->cache = array();
	}
	
	function forId($id){
		if($this->cache[$id]==null){
			$data  = VTEntityData::fromEntityId($this->adb, $id);
			$this->cache[$id] = $data;
		}
		return $this->cache[$id];
	}
	
	function put($entity){
		$this->cache[$entity->getId()] = $entity;
	}
}
?>