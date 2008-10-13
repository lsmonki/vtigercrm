<?php
	require_once 'include/utils/CommonUtils.php';
	
	class DataTransform{
		
		public static $recordString = "record_id";
		public static $recordModuleString = 'record_module';
		
		function sanitizeDataWithColumn($row,$meta){
			
			$newRow = array();
			$columnfield = $meta->getColumnFieldMapping();
			
			foreach($row as $col=>$val){
				$newRow[$columnfield[$col]] = $val;
			}
			
			$newRow = DataTransform::sanitizeData($newRow,$meta);
			
			return $newRow;
		}
		
		function filterAndSanitize($row,$meta){
			
			$row = DataTransform::filterAllColumns($row,$meta);
			$row = DataTransform::sanitizeData($row,$meta);
			return $row;
		}
		
		function sanitizeData($newRow,$meta){
			
			$newRow = DataTransform::sanitizeReferences($newRow,$meta);
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
		
	}
	
?>