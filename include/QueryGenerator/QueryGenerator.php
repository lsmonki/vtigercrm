<?php
/*+*******************************************************************************
 *  The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
 *********************************************************************************/

require_once 'data/CRMEntity.php';
require_once 'modules/CustomView/CustomView.php';
require_once 'include/Webservices/Utils.php';

/**
 * Description of QueryGenerator
 *
 * @author MAK
 */
class QueryGenerator {
	private $module;
	private $customViewColumnList;
	private $stdFilterList;
	private $conditionals;
	private $group;
	private $groupType;
	private $whereFields;
	/**
	 *
	 * @var VtigerCRMObjectMeta 
	 */
	private $meta;
	/**
	 *
	 * @var Users 
	 */
	private $user;
	private $advFilterList;
	private $fields;
	private $referenceModuleMetaInfo;
	private $moduleNameFields;
	private $referenceFieldInfoList;
	private $referenceFieldList;
	private $ownerFields;
	public static $AND = 'AND';
	public static $OR = 'OR';
	
	public function __construct($module, $user) {
		$db = PearDatabase::getInstance();
		$this->module = $module;
		$this->customViewColumnList = null;
		$this->stdFilterList = null;
		$this->conditionals = array();
		$this->group = 0;
		$this->user = $user;
		$this->advFilterList = null;
		$this->fields = array();
		$this->referenceModuleMetaInfo = array();
		$this->moduleNameFields = array();
		$this->whereFields = array();
		$this->groupType = self::$AND;
		$this->meta = $this->getMeta($module);
		$this->moduleNameFields[$module] = $this->meta->getNameFields();
		$this->referenceFieldInfoList = $this->meta->getReferenceFieldDetails();
		$this->referenceFieldList = array_keys($this->referenceFieldInfoList);;
		$this->ownerFields = $this->meta->getOwnerFields();
	}

	/**
	 *
	 * @param String:ModuleName $module
	 * @return EntityMeta
	 */
	public function getMeta($module) {
		$db = PearDatabase::getInstance();
		if (empty($this->referenceModuleMetaInfo[$module])) {
			$handler = vtws_getModuleHandlerFromName($module, $this->user);
			$meta = $handler->getMeta();
			$this->referenceModuleMetaInfo[$module] = $meta;
			if($module == 'Users') {
				$this->moduleNameFields[$module] = 'user_name';
			} else {
				$this->moduleNameFields[$module] = $meta->getNameFields();
			}
		}
		return $this->referenceModuleMetaInfo[$module];
	}

	public function getFields() {
		return $this->fields;
	}

	public function getWhereFields() {
		return $this->whereFields;
	}

	public function getOwnerFieldList() {
		return $this->ownerFields;
	}

	public function getModuleNameFields($module) {
		return $this->moduleNameFields[$module];
	}

	public function getReferenceFieldList() {
		return $this->referenceFieldList;
	}

	public function getReferenceFieldInfoList() {
		return $this->referenceFieldInfoList;
	}

	public function getModule () {
		return $this->module;
	}

	public function getDefaultCustomViewQuery() {
		$customView = new CustomView($this->module);
		$viewId = $customView->getViewId($this->module);
		return $this->getCustomViewQueryById($viewId);
	}

	public function getCustomViewQueryById($viewId) {
		$customView = new CustomView($this->module);
		$this->customViewColumnList = $customView->getColumnsListByCvid($viewId);
		foreach ($this->customViewColumnList as $customViewColumnInfo) {
			$details = explode(':', $customViewColumnInfo);
			if(empty($details[2]) && $details[1] == 'crmid' && $details[0] == 'vtiger_crmentity') {
				$name = 'id';
			} else {
				$this->fields[] = $details[2];
			}
		}

		if($this->module == 'Calendar' && !in_array('activitytype', $this->fields)) {
			$this->fields[] = 'activitytype';
		}

		if($this->module == 'Documents') {
			if(in_array('filename', $this->fields)) {
				if(!in_array('filelocationtype', $this->fields)) {
					$this->fields[] = 'filelocationtype';
				}
				if(!in_array('filestatus', $this->fields)) {
					$this->fields[] = 'filestatus';
				}
			}
		}
		$this->fields[] = 'id';
		
		$this->stdFilterList = $customView->getStdFilterByCvid($viewId);
		if(is_array($this->stdFilterList)) {
			$value = array();
			if(!empty($this->stdFilterList['columnname'])) {
				$name = explode(':',$this->stdFilterList['columnname']);
				$name = $name[2];
				$value[] = $this->fixDateTimeValue($name, $this->stdFilterList['startdate']);
				$value[] = $this->fixDateTimeValue($name, $this->stdFilterList['enddate'], false);
				$this->addCondition($name, $value, 'BETWEEN');
			}
		}
		$this->advFilterList = $customView->getAdvFilterByCvid($viewId);
		if(is_array($this->advFilterList)) {
			foreach ($this->advFilterList as $filter) {
				$name = explode(':',$filter['columnname']);
				if(empty($name[2]) && $name[1] == 'crmid' && $name[0] == 'vtiger_crmentity') {
					$name = $this->getSQLColumn('id');
				} else {
					$name = $name[2];
				}
				$this->addCondition($name, $filter['value'], $filter['comparator']);
			}
		}
		return $this->getQuery();
	}

	public function getQuery() {
		$conditionedReferenceFields = array();
		$allFields = array_merge($this->whereFields,$this->fields);
		foreach ($allFields as $fieldName) {
			if(in_array($fieldName,$this->referenceFieldList)) {
				$moduleList = $this->referenceFieldInfoList[$fieldName];
				foreach ($moduleList as $module) {
					if(empty($this->moduleNameFields[$module])) {
						$meta = $this->getMeta($module);
					}
				}
			} elseif(in_array($fieldName, $this->ownerFields )) {
				$meta = $this->getMeta('Users');
				$meta = $this->getMeta('Groups');
			}
		}
		$query = 'SELECT ';
		$columns = array();
		$moduleFields = $this->meta->getModuleFields();
		$accessibleFieldList = array_keys($moduleFields);
		foreach ($this->fields as $index => $field) {
			if (!in_array($field, $accessibleFieldList) && $field != 'id') {
				unset($this->fields[$index]);
				continue;
			}
			$sql = $this->getSQLColumn($field);
			$columns[] = $sql;
		}
		$query .= implode(', ',$columns);
		$query .= $this->getFromClause();
		$query .= $this->getWhereClause();
		return $query;
	}

	public function getSQLColumn($name) {
		if ($name == 'id') {
			$baseTable = $this->meta->getEntityBaseTable();
			$moduleTableIndexList = $this->meta->getEntityTableIndexList();
			$baseTableIndex = $moduleTableIndexList[$baseTable];
			return $baseTable.'.'.$baseTableIndex;
		}
		
		$moduleFields = $this->meta->getModuleFields();
		$field = $moduleFields[$name];
		$sql = '';
		//optimization to eliminate one more lookup of name, incase the field refers to only
		//one module or is of type owner.
//		if(in_array($name, $this->whereFields)) {
//			if(in_array($name, $this->referenceFieldList)) {
//				$moduleList = $this->referenceFieldInfoList[$name];
//				foreach($moduleList as $module) {
//					$nameFields = $this->moduleNameFields[$module];
//					$nameFieldList = explode(',',$nameFields);
//					$meta = $this->getMeta($module);
//					foreach ($nameFieldList as $index=>$column) {
//						$field = $meta->getFieldByColumnName($column);
//						$nameFieldList[$index] = $field->getTableName().'.'.$column;
//					}
//					$nameFields = implode(",' ',", $nameFieldList);
//					$sql .= " CONCAT($nameFields) as $name ";
//				}
//				return $sql;
//			}
//			if(in_array($name, $this->ownerFields)) {
//				$sql = "case when (vtiger_users.user_name not like '') then vtiger_users.".
//				"user_name else vtiger_groups.groupname end as $name";
//				return $sql;
//			}
//		}
		$column = $field->getColumnName();
		return $field->getTableName().'.'.$column;
	}

	public function getFromClause() {
		$moduleFields = $this->meta->getModuleFields();
		$tableList = array();
		$tableJoinMapping = array();
		$tableJoinCondition = array();
		foreach ($this->fields as $fieldName) {
			if ($fieldName == 'id') {
				continue;
			}

			$field = $moduleFields[$fieldName];
			$baseTable = $field->getTableName();
			$tableIndexList = $this->meta->getEntityTableIndexList();
			$baseTableIndex = $tableIndexList[$baseTable];
			if($field->getFieldDataType() == 'reference') {
				$moduleList = $this->referenceFieldInfoList[$fieldName];
				$tableJoinMapping[$field->getTableName()] = 'INNER JOIN';
				foreach($moduleList as $module) {
					if($module == 'Users') {
						$tableJoinCondition[$fieldName]['vtiger_users'] = $field->getTableName().
								".".$field->getColumnName()." = vtiger_users.id";
						$tableJoinCondition[$fieldName]['vtiger_groups'] = $field->getTableName().
								".".$field->getColumnName()." = vtiger_groups.groupid";
						$tableJoinMapping['vtiger_users'] = 'LEFT JOIN';
						$tableJoinMapping['vtiger_groups'] = 'LEFT JOIN';
					}
				}
			} elseif($field->getFieldDataType() == 'owner') {
				$tableList['vtiger_users'] = 'vtiger_users';
				$tableList['vtiger_groups'] = 'vtiger_groups';
				$tableJoinMapping['vtiger_users'] = 'LEFT JOIN';
				$tableJoinMapping['vtiger_groups'] = 'LEFT JOIN';
			}
			$tableList[$field->getTableName()] = $field->getTableName();
				$tableJoinMapping[$field->getTableName()] =
						$this->meta->getJoinClause($field->getTableName());
		}
		$baseTable = $this->meta->getEntityBaseTable();
		$moduleTableIndexList = $this->meta->getEntityTableIndexList();
		$baseTableIndex = $moduleTableIndexList[$baseTable];
		foreach ($this->whereFields as $fieldName) {
			if(empty($fieldName)) {
				continue;
			}
			$field = $moduleFields[$fieldName];
			$baseTable = $field->getTableName();
			if($field->getFieldDataType() == 'reference') {
				$moduleList = $this->referenceFieldInfoList[$fieldName];
				$tableJoinMapping[$field->getTableName()] = 'INNER JOIN';
				foreach($moduleList as $module) {
					$nameFields = $this->moduleNameFields[$module];
					$nameFieldList = explode(',',$nameFields);
					$meta = $this->getMeta($module);
					foreach ($nameFieldList as $index=>$column) {
						$referenceField = $meta->getFieldByColumnName($column);
						$referenceTable = $referenceField->getTableName();
						$tableIndexList = $meta->getEntityTableIndexList();
						$referenceTableIndex = $tableIndexList[$referenceTable];
						if(isset($moduleTableIndexList[$referenceTable])) {
							$referenceTableName = "$referenceTable $referenceTable$fieldName";
							$referenceTable = "$referenceTable$fieldName";
						} else {
							$referenceTableName = $referenceTable;
						}
						//should always be left join for cases where we are checking for null
						//reference field values.
						$tableJoinMapping[$referenceTableName] = 'LEFT JOIN';
						$tableJoinCondition[$fieldName][$referenceTableName] = $baseTable.'.'.
							$field->getColumnName().' = '.$referenceTable.'.'.$referenceTableIndex;
					}
				}
			} elseif($field->getFieldDataType() == 'owner') {
				$tableList['vtiger_users'] = 'vtiger_users';
				$tableList['vtiger_groups'] = 'vtiger_groups';
				$tableJoinMapping['vtiger_users'] = 'LEFT JOIN';
				$tableJoinMapping['vtiger_groups'] = 'LEFT JOIN';
			} else {
				$tableList[$field->getTableName()] = $field->getTableName();
				$tableJoinMapping[$field->getTableName()] =
						$this->meta->getJoinClause($field->getTableName());
			}
		}

		$defaultTableList = $this->meta->getEntityDefaultTableList();
		foreach ($defaultTableList as $table) {
			if(!in_array($table, $tableList)) {
				$tableList[$table] = $table;
				$tableJoinMapping[$table] = 'INNER JOIN';
			}
		}
		$ownerFields = $this->meta->getOwnerFields();
		if (count($ownerFields) > 0) {
			$ownerField = $ownerFields[0];
		}
		$baseTable = $this->meta->getEntityBaseTable();
		$sql = " FROM $baseTable ";
		unset($tableList[$baseTable]);
		foreach ($defaultTableList as $tableName) {
			$sql .= " $tableJoinMapping[$tableName] $tableName ON $baseTable.".
					"$baseTableIndex = $tableName.$moduleTableIndexList[$tableName]";
			unset($tableList[$tableName]);
		}
		foreach ($tableList as $tableName) {
			if($tableName == 'vtiger_users') {
				$field = $moduleFields[$ownerField];
				$sql .= " $tableJoinMapping[$tableName] $tableName ON ".$field->getTableName().".".
					$field->getColumnName()." = $tableName.id";
			} elseif($tableName == 'vtiger_groups') {
				$field = $moduleFields[$ownerField];
				$sql .= " $tableJoinMapping[$tableName] $tableName ON ".$field->getTableName().".".
					$field->getColumnName()." = $tableName.groupid";
			} else {
				$sql .= " $tableJoinMapping[$tableName] $tableName ON $baseTable.".
					"$baseTableIndex = $tableName.$moduleTableIndexList[$tableName]";
			}
		}

		if( $this->meta->getTabName() == 'Documents') {
			$tableJoinCondition['folderid'] = array(
				'vtiger_attachmentsfolder'=>"$baseTable.folderid = vtiger_attachmentsfolder.folderid"
			);
			$tableJoinMapping['vtiger_attachmentsfolder'] = 'INNER JOIN';
		}

		foreach ($tableJoinCondition as $fieldName=>$conditionInfo) {
			foreach ($conditionInfo as $tableName=>$condition) {
				$sql .= " $tableJoinMapping[$tableName] $tableName ON $condition";
			}
		}
		$sql .= $this->meta->getEntityAccessControlQuery();
		return $sql;
	}

	public function getWhereClause() {
		$deletedQuery = $this->meta->getEntityDeletedQuery();
		$sql = '';
		if(!empty($deletedQuery)) {
			$sql .= " WHERE $deletedQuery";
			$nextGlue = null;
		}else{
			$nextGlue = 'where';
		}

		$moduleFieldList = $this->meta->getModuleFields();
		$baseTable = $this->meta->getEntityBaseTable();
		$moduleTableIndexList = $this->meta->getEntityTableIndexList();
		$baseTableIndex = $moduleTableIndexList[$baseTable];
		foreach ($this->conditionals as $conditionTypeInfoList) {
			foreach ($conditionTypeInfoList as $type=>$conditionInfoList) {
				if(empty($nextGlue)) {
					$nextGlue = $type;
				}
				foreach ($conditionInfoList as $index=>$conditionInfo) {

					$fieldName = $conditionInfo['name'];
					if(empty($fieldName)) {
						continue;
					}
					$sql .= " $nextGlue ";
					$field = $moduleFieldList[$fieldName];
					$fieldSql = '(';
					$fieldGlue = '';
					$valueSqlList = $this->getConditionValue($conditionInfo['value'],
						$conditionInfo['operator'], $field);
					if(!is_array($valueSqlList)) {
						$valueSqlList = array($valueSqlList);
					}
					foreach ($valueSqlList as $valueSql) {
						if (in_array($fieldName, $this->referenceFieldList)) {
							$moduleList = $this->referenceFieldInfoList[$fieldName];
							foreach($moduleList as $module) {
								$nameFields = $this->moduleNameFields[$module];
								$nameFieldList = explode(',',$nameFields);
								$meta = $this->getMeta($module);
								foreach ($nameFieldList as $index=>$column) {
									$referenceField = $meta->getFieldByColumnName($column);
									$referenceTable = $referenceField->getTableName();
									if(isset($moduleTableIndexList[$referenceTable])) {
										$referenceTable = "$referenceTable$fieldName";
									}
									$fieldSql .= "$fieldGlue $referenceTable.$column $valueSql";
									$fieldGlue = ' OR';
								}
							}
						} elseif (in_array($fieldName, $this->ownerFields)) {
							$fieldSql .= "$fieldGlue vtiger_users.user_name $valueSql or ".
									"vtiger_groups.groupname $valueSql";
						} else {
							if($fieldName == 'birthday') {
								$fieldSql .= "$fieldGlue DATE_FORMAT(".$field->getTableName().'.'.
										$field->getColumnName().",'%m%d') ".$valueSql;
							} else {
								$fieldSql .= "$fieldGlue ".$field->getTableName().'.'.
										$field->getColumnName().' '.$valueSql;
							}
						}
						$fieldGlue = ' OR';
					}
					$fieldSql .= ')';
					$sql .= $fieldSql;
					$nextGlue = $type;
				}
			}
		}
		$sql .= " AND $baseTable.$baseTableIndex > 0";
		return $sql;
	}

	/**
	 *
	 * @param mixed $value
	 * @param String $operator
	 * @param WebserviceField $field
	 */
	private function getConditionValue($value, $operator, $field) {
		$operator = strtolower($operator);
		$db = PearDatabase::getInstance();

		if(is_string($value)) {
			$valueArray = explode(',' , $value);
		}else{
			$valueArray = array($value);
		}

		$sql = array();
		if($operator == 'between') {
			if($field->getFieldName() == 'birthday') {
				$sql[] = "between DATE_FORMAT(".$db->quote($value[0]).", '%m%d') AND ".
						"DATE_FORMAT(".$db->quote($value[1]).", '%m%d')";
			} else {
				$sql[] = "BETWEEN ".$db->quote($value[0])." AND ".
							$db->quote($value[1]);
			}
			return $sql;
		}
		foreach ($valueArray as $value) {
			if($field->getFieldDataType() == 'boolean') {
				$value = strtolower($value);
				if ($value == 'yes') {
					$value = 1;
				} elseif($value == 'no') {
					$value = 0;
				}
			}
			if($field->getFieldName() == 'birthday') {
				$value = "DATE_FORMAT(".$db->quote($value).", '%m%d')";
			} else {
				$value = $db->sql_escape_string($value);
			}
			if((trim($value) == 'NULL') ||
					(trim($value) == '' && !$this->isStringType($field->getFieldDataType())) &&
							($operator == 'e' || $operator == 'n')) {
				if($operator == 'e'){
					$sql[] = "IS NULL";
					continue;
				}
				$sql[] = "IS NOT NULL";
				continue;
			}

			if(trim($value) == '' && ($operator == 's' || $operator == 'ew' || $operator == 'c')
					&& $this->isStringType($field->getFieldDataType())) {
				$sql[] = "LIKE ''";
				continue;
			}

			if(trim($value) == '' && ($operator == 'k') &&
					$this->isStringType($field->getFieldDataType())) {
				$sql[] = "NOT LIKE ''";
				continue;
			}

			switch($operator) {
				case 'e': $sqlOperator = "=";
					break;
				case 'n': $sqlOperator = "<>";
					break;
				case 's': $sqlOperator = "LIKE";
					$value = "$value%";
					break;
				case 'ew': $sqlOperator = "LIKE";
					$value = "%$value";
					break;
				case 'c': $sqlOperator = "LIKE";
					$value = "%$value%";
					break;
				case 'k': $sqlOperator = "NOT LIKE";
					$value = "%$value%";
					break;
				case 'l': $sqlOperator = "<";
					break;
				case 'g': $sqlOperator = ">";
					break;
				case 'm': $sqlOperator = "<=";
					break;
				case 'h': $sqlOperator = ">=";
					break;
			}
			if(!$this->isNumericType($field->getFieldDataType()) &&
					$field->getFieldName() != 'birthday'){
				$value = "'$value'";
			}
			$sql[] = "$sqlOperator $value";
		}
		return $sql;
	}

	private function isNumericType($type) {
		return ($type == 'integer' || $type == 'double');
	}

	private function isStringType($type) {
		return ($type == 'string' || $type == 'text' || $type == 'email');
	}

	private function fixDateTimeValue($name, $value, $first = true) {
		$moduleFields = $this->meta->getModuleFields();
		$field = $moduleFields[$name];
		$type = $field->getFieldDataType();
		if($type == 'datetime') {
			if(strrpos($value, ' ') === false) {
				if($first) {
					return $value.' 00:00:00';
				}else{
					return $value.' 23:59:59';
				}
			}
		}
		return $value;
	}

	public function addCondition($fieldname,$value,$operator,$glue= null,$newGroup = false,
			$newGroupType = null) {
		if(empty($glue)) {
			$glue = self::$AND;
		}
		$glue = strtoupper($glue);
		$this->whereFields[] = $fieldname;
		if($newGroup == true) {
			if(empty($newGroupType)) {
				$newGroupType = self::$AND;
			}
			$this->newGroup($newGroupType);
		}
		if($glue == self::$AND){
			$this->addAndCondition($fieldname,$value,$operator);
		}elseif($glue == self::$OR) {
			$this->addOrCondition($fieldname,$value,$operator);
		}
	}

	private function addAndCondition($fieldname,$value,$operator) {
		$this->conditionals[$this->group][self::$AND][] = $this->getConditionalArray($fieldname,
				$value, $operator);
	}

	private function addOrCondition($fieldname, $value, $operator) {
		$this->conditionals[$this->group][self::$OR][] = $this->getConditionalArray($fieldname,
				$value, $operator);
	}

	private function getConditionalArray($fieldname,$value,$operator) {
		return array('name'=>$fieldname,'value'=>$value,'operator'=>$operator);
	}

	private function newGroup() {
		$this->group++;
	}

}
?>
