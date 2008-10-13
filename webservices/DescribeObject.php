<?php
	require_once 'webservices/VtigerCRMObject.php';
	require_once 'webservices/VtigerCRMObjectMeta.php';
	require_once 'include/language/en_us.lang.php';
	require_once 'include/utils/UserInfoUtil.php';
	require_once 'include/database/PearDatabase.php';
	require_once 'include/utils/CommonUtils.php';
	require_once 'webservices/ModuleTypes.php';
	
	function describeobject($elementType,$user){
		
		global $app_strings,$current_user;
		
		$current_user = $user;
		$crmObject = new VtigerCRMObject($elementType);
		$meta = new VtigerCRMObjectMeta($crmObject,$user);
		if(!$meta->hasAccess()){
			return new WebServiceError(WebServiceErrorCode::$ACCESSDENIED,"Permission to access object type is denied");
		}
		
		$types = listtypes($user);
		if(!in_array($elementType,$types['types'])){
			return new WebServiceError(WebServiceErrorCode::$ACCESSDENIED,"Permission to perform the operation is denied");
		}
		
		$label = (isset($app_strings[$elementType]))? $app_strings[$elementType]:$elementType;
		$createable = (strcasecmp(isPermitted($elementType,"Save"),'yes')===0)? true:false;
		$updateable = (strcasecmp(isPermitted($elementType,"EditView"),'yes')===0)? true:false;
		$deleteable = $meta->hasDeleteAccess();
		$retrieveable = $meta->hasReadAccess();
		$fields = getModuleFields($meta,$user);
		return array("label"=>$label,"name"=>$elementType,"createable"=>$createable,"updateable"=>$updateable,
				"deleteable"=>$deleteable,"retrieveable"=>$retrieveable,"fields"=>$fields);
	}
	
	function getModuleFields($meta,$user){
		
		$fields = array();
		$userColumns = $meta->getUserAccessibleColumns();
		foreach($userColumns as $index=>$column){
			array_push($fields ,getField($column,$meta,$user));
		}
		return $fields;
	}
	
	function getField($column,$meta,$user){
		
		if(strcmp($column,$meta->getObectIndexColumn())===0){
			return getIdField($column);
		}
		
		$fieldColumnMapping = $meta->getColumnFieldMapping();
		$fieldName = $fieldColumnMapping[$column];
		require 'modules/'.$meta->getObjectName().'/language/en_us.lang.php';
		$fieldLabel = $mod_strings[getFieldLabelKey($column,$meta->getObjectId())];
		$isMandatory = in_array($fieldName,$meta->getMandatoryFields());
		$detail = getFieldDetail($column,$meta,$user);
		return array('name'=>$fieldName,'label'=>$fieldLabel,'mandatory'=>$isMandatory,'type'=>$detail['type'],
						'default'=>$detail['default'],'nillable'=>$detail['nillable'],"editable"=>$detail['editable']);		
	}
	
	function getFieldDetail($column,$meta,$user){
		$uitype = getFieldUIType($column,$meta->getObjectId());
		$type = getFieldTypeForUIType($uitype);
		if($type == null){
			$typeMapping = $meta->getColumnDataTypeMapping();
			$type = getFieldTypeFromTypeOfData($typeMapping[$column]);
		}
		
		$tables = $meta->getColumnTableMapping();
		$table = $tables[$column];
		$details = getFieldDetailFromTable($column,$table);
		if($details["type"] !='datetime'){
			$details['type'] = $type;
		}
		$details['editable'] = isEditable($details['type'],$uitype);
		$typeDetails = getTypeDetails($column,$uitype,$details,$meta,$user);
		$typeDetails['name'] = $details['type'];
		$details['type'] = $typeDetails;
		return $details;
	}
	
	function isEditable($type,$uitype){
		if(strcasecmp($type,"autogenerated")===0 || strcasecmp($type,"ID")===0){
			return false;
		}
		if($uitype ==  70){
			return false;
		}
		return true;
	}
	
	function getTypeDetails($column,$uitype,$meta,$user){
		$typeDetails = array();
		switch($details['type']){
			case 'reference': $typeDetails['refersTo'] = getReferenceType($uitype);
								break;
			case 'picklist': $typeDetails["picklistValues"] = getPicklistDetails($column,$uitype,$meta,$user);
								$typeDetails['defaultValue'] = $typeDetails["picklistValues"][0]['value']; 
		}
		return $typeDetails;
	}
	
	function getPicklistDetails($column,$uitype,$meta,$user){
		$hardCodedPickListNames = array("hdntaxtype");
		$hardCodedPickListValues = array("hdntaxtype"=>array("label"=>"Individual","value"=>"individual"),
														array("label"=>"Group","value"=>"group"));
		$fieldColumnMapping = $meta->getColumnFieldMapping();
		$fieldName = $fieldColumnMapping[$column];
		$fieldName = strtolower($fieldName);
		if(in_array($fieldName,$hardCodedPickListNames)){
			return $hardCodedPickListValues[$fieldName];
		}
		
		return getPickListOptions($fieldName,$user);
		
	}
	
	function getPickListOptions($fieldName,$user){
		
		global $adb;
		
		$options = array();
		$sql = "select * from vtiger_picklist where name=?";
		$result = $adb->pquery($sql,array($fieldName));
		$numRows = $adb->num_rows($result);
		if($numRows == 0){
			$sql = "select * from vtiger_$fieldName";
			$result = $adb->pquery($sql,array());
			$numRows = $adb->num_rows($result);
			for($i=0;$i<$numRows;++$i){
				$elem = array();
				$elem["label"] = $adb->query_result($result,$i,$fieldName);
				$elem["value"] = $adb->query_result($result,$i,$fieldName);
				array_push($options,$elem);
			}
		}else{
			$details = getPickListValues($fieldName,$user->roleid);
			for($i=0;$i<sizeof($details);++$i){
				$elem = array();
				$elem["label"] = $details[$i];
				$elem["value"] = $details[$i];
				array_push($options,$elem);
			}
		}
		return $options;
	}
	
	function getReferenceType($uitype){
		
		global $adb;
		$sql = "select * from vtiger_ws_fieldtype where uitype=?";
		$result = $adb->pquery($sql,array($uitype));
		$fieldTypeId = null;
		if($result != null && isset($result)){
			if($adb->num_rows($result)>0){
				$fieldTypeId = $adb->query_result($result,0,"fieldtypeid");
			}
		}
		
		$referenceTypes = array();
		$sql = "select * from vtiger_ws_referencetype where fieldtypeid=?";
		$result = $adb->pquery($sql,array($fieldTypeId));
		$numRows = $adb->num_rows($result);
		for($i=0;$i<$numRows;++$i){
			array_push($referenceTypes,$adb->query_result($result,$i,"type"));
		}
		return $referenceTypes;
	}
	
	function getFieldTypeFromTypeOfData($typeofdata){
		switch($typeofdata){
			case 'T': return "time";
			case 'D':
			case 'DT': return "date";
			case 'E': return "email";
			case 'N':
			case 'NN': return "double";
			case 'P': return "password";
			case 'I': return "integer";
			case 'V':
			default: return "string";
		}
	}
	
	function getFieldDetailFromTable($column,$table){
		
		global $adb;
		$sql = "desc $table";
		$result = $adb->pquery($sql,array());
		$details = array();
		$details['type'] = null;
		$details['default'] = "";
		$details['nillable'] = true;
		if($result != null && isset($result)){
			$numRows = $adb->num_rows($result);
			for($i=0;$i<$numRows;++$i){
				$name = $adb->query_result($result,$i,"field");
				if($name == $column){
					$details['type'] = $adb->query_result($result,$i,"type");
					$default = $adb->query_result($result,$i,"default");
					$details['nillable'] = ($adb->query_result($result,$i,"null")=="NO")? false: true;
					$details['default'] = ($default!="" && isset($default))? $default: "";
					break;
				}
			}
		}
		return $details;
	}
	
	function getFieldTypeForUIType($uitype){
		global $adb;
		$sql = "select * from vtiger_ws_fieldtype where uitype=?";
		$result = $adb->pquery($sql,array($uitype));
		if($result != null && isset($result)){
			if($adb->num_rows($result)>0){
				return $adb->query_result($result,0,"fieldtype");
			}
		}
		return null;
	}
	
	function getFieldUIType($column,$tabId){
		global $adb;
		$sql = "select * from vtiger_field where columnname=? and tabid=?";
		$result = $adb->pquery($sql,array($column,$tabId));
		if($result != null && isset($result)){
			if($adb->num_rows($result)>0){
				return $adb->query_result($result,0,"uitype");
			}
		}
		return null;
	}
	
	function getIdField($column){
		return array('name'=>'id','label'=>$column,'mandatory'=>false,'type'=>'ID','editable'=>false,'type'=>'autogenerated',
						'nillable'=>false);
	}
	
	function getFieldLabelKey($column,$tabId){
		
		global $adb;
		$sql = "select * from vtiger_field where columnname=? and tabid=?";
		$result = $adb->pquery($sql,array($column,$tabId));
		if($result != null && isset($result)){
			if($adb->num_rows($result)>0){
				return $adb->query_result($result,0,"fieldlabel");
			}
		}
		return null;
	}
	
?>