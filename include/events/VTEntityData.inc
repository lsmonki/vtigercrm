<?php
	/**
	 * A wrapper around CRMEntity instances
	 */
	class VTEntityData{
		private $isNew = false;
		
		/**
		 * Get an entity data object.
		 *
		 * @param $adb Pear database instance.
		 * @param $entityId The id of the entity to load.
		 * @return The new entity data object.
		 */
		function fromEntityId($adb, $entityId){
			$obj = new VTEntityData();
			$obj->entityId = $entityId;
			$result = $adb->pquery("select setype from vtiger_crmentity where crmid=?", array($entityId));
			$setype =  $adb->query_result($result,0,"setype");
			$obj->moduleName = $setype;
			require_once("modules/".$setype."/".$setype.".php");
			$focus = new $setype();
			$focus->retrieve_entity_info($entityId, $setype);
			$focus->id = $entityId;
			$obj->isNew = isset($crmEntity->id) && $crmEntity->id != null;
			$obj->focus = $focus;
			return $obj;
		}
		
		
		/**
		 * Get an entity data object from a crmentity object
		 *
		 * @param $crmEntity The CRMEntity instance.
		 * @return The new entity data object.
		 */
		function fromCRMEntity($crmEntity){
			$obj = new VTEntityData();
			$obj->focus = $crmEntity;
			$obj->isNew = isset($crmEntity->id) && $crmEntity->id != null;
			return $obj;
		}
		
		/**
		 * Get the data from the entity object as an array.
		 *
		 * @return An array representation of the module data.
		 */
		function getData(){
			return $this->focus->column_fields;
		}
		
		/**
		 * Get the entity id.
		 *
		 * @return The entity id.
		 */
		function getId(){
			return $this->focus->id;
		}
		
		/**
		 * Get the name of the module represented by the entity data object.
		 *
		 * @return The module name.
		 */
		function getModuleName(){
			return get_class($this->focus);
		}
		
		function get($fieldName){
			$this->focus->data[$fieldName];
		}
		
		function set($fieldName, $value){
			$data = $this->focus->column_fields[$fieldName] = $value;
		}
		
		/**
		 * Check whether the object is stored on the database.
		 * 
		 * @return True if the object is saved false otherwiser.
		 */
		function isSaved(){
			return isset($this->focus->id);
		}
		
		
		/**
		* Check wether the obkect is new.
		* 
		* @return True if the object is new, false otherwise.
		*/
		function isNew(){
			return $this->isNew;
		}
		
	}

?>
