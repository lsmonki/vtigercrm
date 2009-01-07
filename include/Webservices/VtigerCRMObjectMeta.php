<?php

class VtigerCRMObjectMeta{
		private $crmObject;
		private $objectName;
		private $objectId;
		private $user;
		
		private $userAccessibleColumns;
		private $columnTableMapping;
		private $fieldColumnMapping;
		private $columnDataTypeMapping;
		private $mandatoryFields;
		private $referenceFieldDetails;
		private $emailFields;
		private $fieldNameFieldIdMapping;
		private $ownerFields;
		private $columnFieldMapping;
		private $baseTable;
		private $idColumn;
				
		private $meta;
		private $assign;
		private $hasAccess;
		private $hasReadAccess;
		private $hasWriteAccess;
		private $hasDeleteAccess;
		private $assignUsers;
		public static $RETRIEVE = "DetailView";
		public static $CREATE = "Save";
		public static $UPDATE = "EditView";
		public static $DELETE = "Delete";
		
		function VtigerCRMObjectMeta($crmObject,$user){
			
			$this->crmObject = $crmObject;
			
			$this->objectName = $this->crmObject->getModuleName();
			$this->objectId = $this->crmObject->getModuleId();
			
			$this->user = $user;
			$this->columnDataTypeMapping = array();
			$this->columnTableMapping = array();
			$this->fieldColumnMapping = array();
			$this->userAccessibleColumns = array();
			$this->mandatoryFields = array();
			$this->emailFields = array();
			$this->fieldNameFieldIdMapping = array();
			$this->referenceFieldDetails = array();
			$this->columnFieldMapping = array();
			$this->ownerFields = array();
			$this->hasAccess = false;
			$this->hasReadAccess = false;
			$this->hasWriteAccess = false;
			$this->hasDeleteAccess = false;
			$instance = $this->crmObject->getInstance();
			$this->idColumn = $instance->tab_name_index[$instance->table_name];
			$this->baseTable = $instance->table_name;
		}
		
		private function computeAccess(){
			
			global $adb;
			
			$active = vtlib_isModuleActive($this->objectName);
			if($active == false){
				$this->hasAccess = false;
				$this->hasReadAccess = false;
				$this->hasWriteAccess = false;
				$this->hasDeleteAccess = false;
			}
			
			require('user_privileges/user_privileges_'.$this->user->id.'.php');
			if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0){
				$this->hasAccess = true;
				$this->hasReadAccess = true;
				$this->hasWriteAccess = true;
				$this->hasDeleteAccess = true;
			}else{
				
				//TODO get oer sort out the preference among profile2tab and profile2globalpermissions.
				//TODO check whether create/edit seperate controls required for web sevices?
				$profileList = getCurrentUserProfileList();
				
				$sql = "select * from vtiger_profile2globalpermissions where profileid in (".generateQuestionMarks($profileList).");";
				$result = $adb->pquery($sql,array($profileList));
				
				$noofrows = $adb->num_rows($result);
				//globalactionid=1 is view all action.
				//globalactionid=2 is edit all action.
				for($i=0; $i<$noofrows; $i++){
					$permission = $adb->query_result($result,$i,"globalactionpermission");
					$globalactionid = $adb->query_result($result,$i,"globalactionid");
					if($permission != 1 || $permission != "1"){
						$this->hasAccess = true;
						if($globalactionid == 2 || $globalactionid == "2"){
							$this->hasWriteAccess = true;
							$this->hasDeleteAccess = true;
						}else{
							$this->hasReadAccess = true;
						}
					}
				}
				
				$sql = 'select * from vtiger_profile2tab where profileid in ('.generateQuestionMarks($profileList).') and tabid = ?;';
				$result = $adb->pquery($sql,array($profileList,$this->objectId));
				$standardDefined = false;
				$permission = $adb->query_result($result,1,"permissions");
				if($permission == 1 || $permission == "1"){
					$this->hasAccess = false;
					return;
				}else{
					$this->hasAccess = true;
				}
				
				//operation=2 is delete operation.
				//operation=0 or 1 is create/edit operation. precise 0 create and 1 edit.
				//operation=3 index or popup. //ignored for websevices.
				//operation=4 is view operation.
				$sql = "select * from vtiger_profile2standardpermissions where profileid in (".generateQuestionMarks($profileList).") and tabid=?";
				$result = $adb->pquery($sql,array($profileList,$this->objectId));
				
				$noofrows = $adb->num_rows($result);
				for($i=0; $i<$noofrows; $i++){
					$standardDefined = true;
					$permission = $adb->query_result($result,$i,"permissions");
					$operation = $adb->query_result($result,$i,"Operation");
					if(!$operation){
						$operation = $adb->query_result($result,$i,"operation");
					}
					
					if($permission != 1 || $permission != "1"){
						$this->hasAccess = true;
						if($operation == 0 || $operation == "0"){
							$this->hasWriteAccess = true;
						}else if($operation == 1 || $operation == "1"){
							$this->hasWriteAccess = true;
						}else if($operation == 2 || $operation == "2"){
							$this->hasDeleteAccess = true;
						}else if($operation == 4 || $operation == "4"){
							$this->hasReadAccess = true;
						}
					}
				}
				if(!$standardDefined){
					$this->hasReadAccess = true;
					$this->hasWriteAccess = true;
					$this->hasDeleteAccess = true;
				}
				
			}
			
		}
		
		function hasAccess(){
			if(!$this->meta){
				$this->retrieveMeta();
			}
			return $this->hasAccess;
		}
		
		function hasWriteAccess(){
			if(!$this->meta){
				$this->retrieveMeta();
			}
			return $this->hasWriteAccess;
		}
		
		function hasReadAccess(){
			if(!$this->meta){
				$this->retrieveMeta();
			}
			return $this->hasReadAccess;
		}
		
		function hasDeleteAccess(){
			if(!$this->meta){
				$this->retrieveMeta();
			}
			return $this->hasDeleteAccess;
		}
		
		function hasPermission($operation,$id){
			
			$permitted = isPermitted($this->objectName,$operation,$id);
			if(strcmp($permitted,"yes")===0){
				return true;
			}
			return false;
		}
		
		function hasAssignPrivilege($userId){
			
			if(!$this->assign){
				$this->retrieveUserHierarchy();
			}
			if($userId == $this->user->id || in_array($userId,$this->assignUsers)){
				return true;
			}else{
				return false;
			}
			
		}
		
		function getUserAccessibleColumns(){
			
			if(!$this->meta){
				$this->retrieveMeta();
			}
			return $this->userAccessibleColumns;
			
		}
		
		function getColumnTableMapping(){
			if(!$this->meta){
				$this->retrieveMeta();
			}
			return $this->columnTableMapping;
		}
		
		function getColumnDataTypeMapping(){
			
			if(!$this->meta){
				$this->retrieveMeta();
			}
			return $this->columnDataTypeMapping;
			
		}
		
		function getFieldColumnMapping(){
			
			if(!$this->meta){
				$this->retrieveMeta();
			}
			return $this->fieldColumnMapping;
			
		}
		
		function getMandatoryFields(){
			if(!$this->meta){
				$this->retrieveMeta();
			}
			return $this->mandatoryFields;
		}
		
		function getReferenceFieldDetails(){
			if(!$this->meta){
				$this->retrieveMeta();
			}
			return $this->referenceFieldDetails;
		}
		
		function getOwnerFields(){
			if(!$this->meta){
				$this->retrieveMeta();
			}
			return $this->ownerFields;
		}
		
		function getObjectName(){
			return $this->objectName;
		}
		
		function getObjectId(){
			return $this->objectId;
		}
		
		function getObectIndexColumn(){
			return $this->idColumn;
		}
		
		function hasMandatoryFields($row){
			
			$mandatoryFields = $this->getMandatoryFields();
			$hasMandatory = true;
			foreach($mandatoryFields as $ind=>$field){
				if( !isset($row[$field])){
					$hasMandatory = false;
					break;
				}
			}
			return $hasMandatory;
			
		}
		
		function getColumnFieldMapping(){
			
			if(!$this->meta){
				$this->retrieveMeta();
			}
			if(sizeof($this->columnFieldMapping)===0){
				$this->columnFieldMapping = array();
				$fieldcol = $this->getFieldColumnMapping();
				foreach($fieldcol as $field=>$col){
					$this->columnFieldMapping[$col] = $field;
				}
			}
			return $this->columnFieldMapping;
		}
		
		function getEmailFields(){
			if(!$this->meta){
				$this->retrieveMeta();
			}
			return $this->emailFields;
		}
		
		function getFieldIdFromFieldName($fieldName){
			if(!$this->meta){
				$this->retrieveMeta();
			}
			return $this->fieldNameFieldIdMapping[$fieldName];
		}
		
		function retrieveMeta(){
			
			global $current_language,$theme,$current_user;
			
			$current_user = $this->user;
			$theme = $this->user->theme;
			$current_language = $default_language;
			//requie should happen here as it depends on state os of global vars to work
			require_once('modules/CustomView/CustomView.php');
			
			//$this->objectId = getObjectId($this->objectName);
			$this->computeAccess();
			
			$cv = new CustomView();
			$module_info = $cv->getCustomViewModuleInfo($this->objectName);
			
			$blockArray = array();
			foreach($cv->module_list[$this->objectName] as $label=>$blockList){
				$blockArray = array_merge($blockArray,explode(',',$blockList));
			}
			$this->retrieveMetaForBlock($blockArray);
			
			$this->columnDataTypeMapping[$this->idColumn] = 'I';
			$this->columnTableMapping[$this->idColumn] = $this->baseTable;
			$this->userAccessibleColumns[] = $this->idColumn;
			$this->fieldColumnMapping['id'] = $this->idColumn;
			
			
			$this->meta = true;
			
		}
		
		private function retrieveUserHierarchy(){
			
			$heirarchyUsers = get_user_array(false,"ACTIVE",$this->user->id);
			$groupUsers = vtws_getUsersInTheSameGroup($this->user->id);
			$this->assignUsers = array_merge($heirarchyUsers, $groupUsers);
			$this->assign = true;
		}
		
		private function retrieveMetaForBlock($block){
			
			global $adb;
			
			$tabid = $this->objectId;
			if($tabid == 9)
				$tabid ="9,16";
			if(!is_array($block)){
				$block = explode(',',$block);
			}
			require('user_privileges/user_privileges_'.$this->user->id.'.php');
			if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] ==0)
			  {
	
				 	$sql = "select * from vtiger_field where tabid in (". generateQuestionMarks(explode(',',$tabid)) .") and block in (".generateQuestionMarks($block).") and displaytype in (1,2,3,4) group by columnname"; 
					$params = array(explode(',',$tabid), $block);	
			  }
			  else
			  {
				  $profileList = getCurrentUserProfileList();
				  
				  if (count($profileList) > 0) {
				  	$sql = "SELECT *
				  			FROM vtiger_field
				  			INNER JOIN vtiger_profile2field
				  			ON vtiger_profile2field.fieldid = vtiger_field.fieldid
				  			INNER JOIN vtiger_def_org_field
				  			ON vtiger_def_org_field.fieldid = vtiger_field.fieldid
				  			WHERE vtiger_field.tabid in (". generateQuestionMarks($tabid) .")
				  			AND vtiger_profile2field.visible = 0 
				  			AND vtiger_profile2field.profileid IN (". generateQuestionMarks($profileList) .")
				  			AND vtiger_def_org_field.visible = 0 and vtiger_field.block in (".generateQuestionMarks($block).") and vtiger_field.displaytype in (1,2,3,4) group by columnname";
				  			  
				  	$params = array(explode(',',$tabid), $profileList, $block);
				  } else {
				  	$sql = "SELECT *
				  			FROM vtiger_field
				  			INNER JOIN vtiger_profile2field
				  			ON vtiger_profile2field.fieldid = vtiger_field.fieldid
				  			INNER JOIN vtiger_def_org_field
				  			ON vtiger_def_org_field.fieldid = vtiger_field.fieldid
				  			WHERE vtiger_field.tabid in (". generateQuestionMarks($tabid) .")
				  			AND vtiger_profile2field.visible = 0 
				  			AND vtiger_def_org_field.visible = 0 and vtiger_field.block in (".generateQuestionMarks($block).") and vtiger_field.displaytype in (1,2,3,4) group by columnname";
				  	
					$params = array(explode(',',$tabid), $block);
				  }
			  }			
			
			if($tabid == '9,16')
				$tabid ="9";
			
			$result = $adb->pquery($sql,$params);
			
			$noofrows = $adb->num_rows($result);
			$referenceArray = array();
			$knownFieldArray = array();
			for($i=0; $i<$noofrows; $i++){
				
				$fieldtablename = $adb->query_result($result,$i,"tablename");
				$fieldcolname = $adb->query_result($result,$i,"columnname");
				$fieldname = $adb->query_result($result,$i,"fieldname");
				$uitype = $adb->query_result($result,$i,"uitype");
				$fieldtype = $adb->query_result($result,$i,"typeofdata");
				$fieldId = $adb->query_result($result,$i,"fieldid");
				$fieldtype = explode("~",$fieldtype);
				$mandatory = $fieldtype[1];
				$fieldtype = $fieldtype[0];
				
				if(strtolower($fieldtype) == "e"){
					$this->emailFields[$fieldname] = $fieldname;
				}
				$this->fieldNameFieldIdMapping[$fieldname]=$fieldId;
				if(strcasecmp($fieldname,'filename')===0 || strcasecmp($fieldname,'imagename')===0){
					continue;
				}
				
				//fall back to this pattren, if column name conflicts arise.
				//columnname = tablename.columname;
				$this->columnDataTypeMapping[$fieldcolname] = $fieldtype;
				$this->columnTableMapping[$fieldcolname] = $fieldtablename;
				$this->userAccessibleColumns[] = $fieldcolname;
				$this->fieldColumnMapping[$fieldname] = $fieldcolname;
				//uitype 4 is module sequence number field.
				if(strcasecmp($mandatory,'M')===0 && $uitype !=4){
					$this->mandatoryFields[] = $fieldname;
				}
				if(in_array($uitype,array_keys($referenceArray))){
					$this->referenceFieldDetails[$fieldname] = $refernceArray[$uitype];
				}else{
					if(!in_array($uitype, $knownFieldArray)){
						$type = $this->getFieldType($uitype);
						if($type == "reference"){
							$referencetype = $this->getReferenceTypeList($uitype);
							$this->referenceFieldDetails[$fieldname] = $referencetype;
							$referenceArray[$uitype] = $referencetype;
						}else if($type == "owner"){
							$this->ownerFields[]=$fieldname;
						}
					}else{
						array_push($knownFieldArray,$uitype);
					}
				}
			}
			$this->emailFields = array_keys($this->emailFields);
			$this->ownerFields = array_unique($this->ownerFields);
		}
		
		function getFieldType($uitype){
			global $adb;
			
			$sql = "select * from vtiger_ws_fieldtype where uitype=?";
			$result = $adb->pquery($sql,array($uitype));
			$noofrows = $adb->num_rows($result);
			for($i=0;$i<$noofrows;$i++){
				$fieldtype = $adb->query_result($result,$i,"fieldtype");
			}
			return strtolower($fieldtype);
		}
		
		function getReferenceTypeList($uitype){
			global $adb;
			
			$referenceType = array();
			$sql = "select * from vtiger_ws_fieldtype where uitype=?";
			$result = $adb->pquery($sql,array($uitype));
			$noofrows = $adb->num_rows($result);
			for($i=0;$i<$noofrows;$i++){
				$fieldtypeid = $adb->query_result($result,$i,"fieldtypeid");
			}
			if($noofrows>0){
				$sql = "select * from vtiger_ws_referencetype where fieldtypeid=?";
				$result = $adb->pquery($sql,array($fieldtypeid));
				$noofrows = $adb->num_rows($result);
				for($i=0;$i<$noofrows;$i++){
					$type = $adb->query_result($result,$i,"type");
					array_push($referenceType,$type);
				}
			}
			return $referenceType;
		}
		
	}
	
?>