<?php

/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Vtiger_Cache  {
    private static  $selfInstance = false;
    private static $cacheEnable = true;
    private function __construct() {}
    
    public static function getInstance(){
	if(self::$selfInstance){
	    return self::$selfInstance;
	} else{
	    self::$selfInstance = new self();
	    return self::$selfInstance;
	}
    }
    
    //cache for the module Instance
    private static  $_module_cache = array();
    
    public function getModule($value){
	if(isset(self::$_module_cache[$value])){
	    return self::$_module_cache[$value];
	}
	return false;
    }
    
    public function setModule($module,$instance){
		if(self::$cacheEnable){
			self::$_module_cache[$module] = $instance;
		}
    }
    
    //cache for the entity filed
    private static  $_entity_cache = array();
    
    public function getEntityField($value){
	if(isset(self::$_entity_cache[$value])){
	    return self::$_entity_cache[$value];
	}
	return false;
    }
    
    public function setEntityField($module,$entity){
		if(self::$cacheEnable){
			self::$_entity_cache[$module] = $entity;
		}	
    }
    
    //cache for the field permision
    private static $_field_permision;
    public function getFieldPermision($module,$field){
	if(isset(self::$_field_permision[$module][$field])){
	    return self::$_field_permision[$module][$field];
	}
	return false;
    }
    
    public function setFieldPermision($module,$field,$permision){
		if(self::$cacheEnable){
			self::$_field_permision[$module][$field] = $permision;
		}	
    }
	
	private static $_user_list;
	
	public function getUserList($module,$currentUser){
		if(isset(self::$_user_list[$currentUser][$module])){
			return self::$_user_list[$currentUser][$module];
		}
		return false;
	}
	
	public function setUserList($module,$userList,$currentUser){
		if(self::$cacheEnable){
			self::$_user_list[$currentUser][$module]=$userList;
		}
	}
	
	private static $_group_list;
	
	public function getGroupList($module,$currentUser){
		if(isset(self::$_group_list[$currentUser][$module])){
			return self::$_group_list[$currentUser][$module];
		}
		return false;
	}
	
	public function setGroupList($module,$GroupList,$currentUser){
		if(self::$cacheEnable){
			self::$_group_list[$currentUser][$module]=$GroupList;
		}
	}
	
	private static $_picklist_values;
	
	public function getPicklistValues($fieldName){
		if(isset(self::$_picklist_values[$fieldName])){
			return self::$_picklist_values[$fieldName];
		}
		return false;
	}
	
	public function setPicklistValues($fieldName,$values){
		if(self::$cacheEnable){
			self::$_picklist_values[$fieldName]=$values;
		}
	}
	
	private static $_picklist_details;
	
	public function getPicklistDetails($module,$field){
		if(isset(self::$_picklist_details[$module][$field])){
			return self::$_picklist_details[$module][$field];
		}
		return false;
	}
	
	public function setPicklistDetails($module,$field,$picklistDetails){
		if(self::$cacheEnable){
			self::$_picklist_details[$module][$field] = $picklistDetails;
		}
	}
	
	private static $_module_ownedby;
	
	public function getModuleOwned($module){
		if(isset(self::$_module_ownedby[$module])){
 			return self::$_module_ownedby[$module];
		}
		return false;
	}
	
	public function setModuleOwned($module,$ownedby){
		if(self::$cacheEnable){
			self::$_module_ownedby[$module] = $ownedby;
		}	
	}
	
	private static $_block_instance;
	
	public function getBlockInstance($block){
		if(isset(self::$_block_instance[$block])){
 			return self::$_block_instance[$block];
		}
		return false;
	}
	
	public function setBlockInstance($block,$instance){
		if(self::$cacheEnable){
			self::$_block_instance[$block] = $instance;
		}	
	}
	
		
	private static $_field_instance;
	
	public function getFieldInstance($field,$moduleId){
		if(isset(self::$_field_instance[$moduleId][$field])){
 			return self::$_field_instance[$moduleId][$field];
		}
		return false;
	}
	
	public function setFieldInstance($field,$moduleId,$instance){
		if(self::$cacheEnable){
			self::$_field_instance[$moduleId][$field] = $instance;
		}	
	}
	
	private static $_admin_user_id = false;
	
	public function getAdminUserId(){
 			return self::$_admin_user_id;
	}
	
	public function setAdminUserId($userId){
		if(self::$cacheEnable){
			self::$_admin_user_id = $userId;
		}	
	}
	
	//cache for the module Instance
    private static  $_module_name = array();
    
    public function getModuleName($moduleId){
	if(isset(self::$_module_name[$moduleId])){
	    return self::$_module_name[$moduleId];
	}
	return false;
    }
    
    public function setModuleName($moduleId,$moduleName){
		if(self::$cacheEnable){
			self::$_module_name[$moduleId] = $moduleName;
		}
    }
	
	//cache for the module Instance
    private static  $_workflow_for_module = array();
    
    public function getWorkflowForModule($module){
		if(isset(self::$_workflow_for_module[$module])){
			return self::$_workflow_for_module[$module];
		}
		return false;
    }
    
    public function setWorkflowForModule($module,$workflows){
		if(self::$cacheEnable){
			self::$_workflow_for_module[$module] = $workflows;
		}
    }
	
	
	private static $_user_id ;
	
	public function getUserId($userName){
		if(isset(self::$_user_id[$userName])){
			return self::$_user_id[$userName];
		}
		return false;
	}
	
	public function setUserId($userName,$userId){
		if(self::$cacheEnable){
			self::$_user_id[$userName] = $userId;
		}
	}
	
	private static $_table_exists ;
	
	public function getTableExists($tableName){
		if(isset(self::$_table_exists[$tableName])){
			return self::$_table_exists[$tableName];
		}
		return false;
	}
	
	public function setTableExists($tableName,$exists){
		if(self::$cacheEnable){
			self::$_table_exists[$tableName] = $exists;
		}
	}
	
	private static $_picklist_id;
	
	public function getPicklistId($fieldName,$moduleName){
		if(isset(self::$_picklist_id[$moduleName][$fieldName])){
			return self::$_picklist_id[$moduleName][$fieldName];
		}
		return false;
	}
	public function setPicklistId($fieldName,$moduleName,$picklistId){
		if(self::$cacheEnable){
			self::$_picklist_id[$moduleName][$fieldName] = $picklistId;
		}
	}
	
	private static $_group_id;
	
	public function getGroupId($groupName){
		if(isset(self::$_group_id[$groupName])){
			return self::$_group_id[$groupName];
		}
		return false;
	}
	
	public function setGroupId($groupName,$groupId){
		if(self::$cacheEnable){
			self::$_group_id[$groupName]=$groupId;
		}
	}
	
	private static $_assigned_picklist_values;
	
	public function getAssignedPicklistValues($tableName,$roleId){
		if(isset(self::$_assigned_picklist_values[$tableName][$roleId])){
			return self::$_assigned_picklist_values[$tableName][$roleId];
		}
		return false;
	}
	
	public function setAssignedPicklistValues($tableName,$roleId,$values){
		if(self::$cacheEnable){
			self::$_assigned_picklist_values[$tableName][$roleId]=$values;
		}
	}
	
	
	private static $_field_write_premisions;
	
	public function getFieldWritePermision($moduleName,$fieldName){
		if(isset($_field_write_premisions[$moduleName][$fieldName])){
			return self::$_field_write_premisions[$moduleName][$fieldName];
		}
		return false;
	}
	
	public function setFieldWritePermision($moduleName,$fieldName,$permission){
		if(self::$cacheEnable){
			self::$_field_write_premisions[$moduleName][$fieldName]=$permission;
		}
	}
	
	private static $_field_read_premisions;
	
	public function getFieldReadPermision($moduleName,$fieldName){
		if(isset($_field_Read_premisions[$moduleName][$fieldName])){
			return self::$_field_read_premisions[$moduleName][$fieldName];
		}
		return false;
	}
	
	public function setFieldReadPermision($moduleName,$fieldName,$permission){
		if(self::$cacheEnable){
			self::$_field_read_premisions[$moduleName][$fieldName]=$permission;
		}
	}
	
}