<?php
	
	class DataTransform{
		
		public static $recordString = "record_id";
		public static $recordModuleString = 'record_module';
		
		function sanitizeDataWithColumn($row,$meta){
			
			$newRow = array();
			$columnfield = $meta->getColumnFieldMapping();
			
			foreach($row as $col=>$val){
				$newRow[$columnfield[$col]] = $val;
			}
			$newRow = DataTransform::sanitizeData($newRow,$meta,true);
			
			return $newRow;
		}
		
		function filterAndSanitize($row,$meta){
			
			$row = DataTransform::filterAllColumns($row,$meta);
			$row = DataTransform::sanitizeData($row,$meta);
			return $row;
		}
		
		function sanitizeData($newRow,$meta,$t=null){
			
			$newRow = DataTransform::sanitizeReferences($newRow,$meta);
			$newRow = DataTransform::sanitizeOwnerFields($newRow,$meta,$t);
			$newRow = DataTransform::sanitizeFields($newRow,$meta);
			
			return $newRow;
		}
		
		function sanitizeForInsert($row,$meta){
			$references = $meta->getReferenceFieldDetails();
			foreach($references as $field=>$typeList){
				if(strpos($row[$field],'x')!==false){
					$row[$field] = getIdComponents($row[$field]);
					$row[$field] = $row[$field][1];
				}
			}
			$ownerFields = $meta->getOwnerFields();
			foreach($ownerFields as $index=>$field){
				if(isset($row[$field]) && $row[$field]!=null){
					$groupId = vtws_getGroupIdFromWebserviceGroupId($row[$field]);
					if($groupId !== null){
						$_REQUEST['assigntype'] = 'T';
						$_REQUEST['assigned_group_id'] = $groupId;//fetchGroupName($groupId);
						$row[$field] = 0;
					}else {
						$ownerDetails = getIdComponents($row[$field]);
						$row[$field] = $ownerDetails[1];
					}
				}
			}
			if($row["id"]){
				unset($row["id"]);
			}
			
			return $row;
			
		}
		
		function filterAllColumns($row,$meta){
			
			$recordString = DataTransform::$recordString;
			
			$allFields = $meta->getFieldColumnMapping();
			$newRow = array();
			foreach($allFields as $field=>$col){
				$newRow[$field] = $row[$field];
			}
			if(isset($row[$recordString])){
				$newRow[$recordString] = $row[$recordString];
			}
			return $newRow;
			
		}
		
		function sanitizeFields($row,$meta){
			
			$recordString = DataTransform::$recordString;
			
			$recordModuleString = DataTransform::$recordModuleString;
			
			if(isset($row[$recordModuleString])){
				unset($row[$recordModuleString]);
			}
			
			if(isset($row['id'])){
				if(strpos($row['id'],'x')===false){
					$row['id'] = getId($meta->getObjectId(),$row['id']);
				}
			}
			
			if(isset($row[$recordString])){
				$row['id'] = getId($meta->getObjectId(),$row[$recordString]);
				unset($row[$recordString]);
			}
			
			if(!isset($row['id'])){
				if($row[$meta->getObectIndexColumn()] ){
					$row['id'] = getId($meta->getObjectId(),$row[$meta->getObectIndexColumn()]);
				}else{
					//TODO Handle this.
					//echo 'error id noy set' ;
				}
			}else if(isset($row[$meta->getObectIndexColumn()]) && strcmp($meta->getObectIndexColumn(),"id")!==0){
				unset($row[$meta->getObectIndexColumn()]);			
			}
			
			return $row;
		}
		
		function sanitizeReferences($row,$meta){
			
			$references = $meta->getReferenceFieldDetails();
			foreach($references as $field=>$typeList){
				if($row[$field]){
					$type = getSalesEntityType($row[$field]);
					if(($type == null || $type == "" || !isset($type)) && in_array("Users",$typeList)){
						$type = "Users";
					}
					if(in_array($type,$typeList)){
						$object = new VtigerCRMObject($type);
						$row[$field] = getId($object->getModuleId(),$row[$field]);
					}
				}
			}
			return $row;
		}
		
		function sanitizeOwnerFields($row,$meta,$t=null){
			$ownerFields = $meta->getOwnerFields();
			foreach($ownerFields as $index=>$field){
				if(isset($row[$field]) && $row[$field]!=null){
					if($row[$field]==0){
						$recordId = $row[DataTransform::$recordString];
						if(!isset($recordId)){
							if(isset($row[$meta->getObectIndexColumn()])){
								$recordId = $row[$meta->getObectIndexColumn()];
							}else{
								$recordId = $row['id'];
							}
						}
						$groupId = getRecordOwnerId($recordId);
						$groupId = $groupId['Groups'];
						$row[$field] = vtws_getIdForGroup($groupId);
					}else{
						$object = new VtigerCRMObject("Users");
						$row[$field] = getId($object->getModuleId(),$row[$field]);
					}
				}
			}
			return $row;
		}
		
	}
	
?>
