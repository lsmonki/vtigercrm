<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/
include_once dirname(__FILE__).'/../../../../vtlib/Vtiger/Block.php';

class Vtiger_Block_Model extends Vtiger_Block {

	protected $fields = false;

	public function getFields() {
		if(empty($this->fields)) {
			$blockFields = Vtiger_Field_Model::getAllForBlock($this, $this->module);
			$this->fields = array();
			foreach($blockFields as $field){
				$this->fields[$field->get('name')] = $field;
			}
		}
		return $this->fields;
	}

	/**
	 * Function to get the value of a given property
	 * @param <String> $propertyName
	 * @return <Object>
	 */
	public function get($propertyName) {
		if(property_exists($this,$propertyName)){
			return $this->$propertyName;
		}
	}

	/**
     * Function which indicates whether the block is shown or hidden
     * @return : <boolean>
     */
    public function isHidden(){
        if($this->get('display_status') == '0') {
            return true;
        }
        return false;
    }

	/**
	 * Function to retrieve block instances for a module
	 * @param <type> $moduleModel - module instance
	 * @return <array> - list of Vtiger_Block_Model
	 */
	public static function getAllForModule($moduleModel) {
		$blockObjects = parent::getAllForModule($moduleModel);
		$blockModelList = array();

		if($blockObjects) {
			foreach($blockObjects as $blockObject) {
				$blockModelList[] = self::getInstanceFromBlockObject($blockObject);
			}
		}
		return $blockModelList;
	}

	/**
	 * Function to retrieve block instance from Vtiger_Block object
	 * @param Vtiger_Block $blockObject - vtlib block object
	 * @return Vtiger_Block_Model
	 */
	public static function getInstanceFromBlockObject(Vtiger_Block $blockObject) {
		$objectProperties = get_object_vars($blockObject);
		$blockClassName = Vtiger_Loader::getComponentClassName('Model', 'Block', $blockObject->module->name);
		$blockModel = new $blockClassName();
		foreach($objectProperties as $properName=>$propertyValue) {
			$blockModel->$properName = $propertyValue;
		}
		return $blockModel;
	}
}
