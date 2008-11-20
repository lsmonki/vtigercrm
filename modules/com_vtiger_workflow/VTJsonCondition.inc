<?php
	require_once("include/Zend/Json.php");

	class VTJsonCondition{
		function  __construct(){
		}
		
		function evaluate($condition, $entityCache, $id){
			$expr = Zend_Json::decode($condition);
			$result=TRUE;
			$data = $entityCache->forId($id)->getData();
			
			foreach($expr as $cond){
				preg_match('/(\w+) : \((\w+)\) (\w+)/', $cond['fieldname'], $matches);
				if(count($matches)==0){
					$result = $this->checkCondition($data, $cond);
				}else{
					list($full,  $referenceField, $referenceModule, $fieldname) = $matches;
					$entity = $entityCache->forId($data[$referenceField]);
					if($entity->getModuleName()==$referenceModule){
						$entityData = $entity->getData();
						$cond['fieldname'] = $fieldname;
						$result = $this->checkCondition($entityData, $cond);
					}else{
						$result = false;
					}
				}
				if($result==false){
					return false;
				}
			}
			return true;
		}
		function startsWith($str, $subStr){
			$sl = strlen($str);
			$ssl = strlen($subStr);
			if($sl>=$ssl){
				return substr_compare($str,$subStr,0, $ssl)==0;
			}else{
				return FALSE;
			}
		}

		function endsWith($str, $subStr){
			$sl = strlen($str);
			$ssl = strlen($subStr);
			if($sl>=$ssl){
				return substr_compare($str,$subStr,$sl-$ssl, $ssl)==0;
			}else{
				return FALSE;
			}
		}
		
		
		function checkCondition($data, $cond){
			$condition = $cond['operation'];
			$fieldValue=$data[$cond['fieldname']];
			$value = $cond['value'];
			switch($condition){
				case "equal to":
					return $fieldValue == $value;
				case "less than":
					return $fieldValue < $value;
				case "greater than":
					return $fieldValue > $value;
				case "does not equal":
					return $fieldValue != $value;
				case "less than or equal to":
					return $fieldValue <= $value;
				case "greater than or equal to":
					return $fieldValue >= $value;
				case "is":
					return $fieldValue == $value;
				case "contains":
					return strpos($fieldValue, $value) !== FALSE;
				case "does not contain":
					return strpos($fieldValue, $value) === FALSE;
				case "starts with":
					return $this->startsWith($fieldValue,$value);
				case "ends with":
					return $this->endsWith($fieldValue, $value);
				case "matches":
					return preg_match($value, $fieldValue);
				default:
					//Unexpected condition
					throw new Exception("Found an unexpected condition: ".$condition);
			}
		}
	}
?>