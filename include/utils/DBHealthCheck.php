<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *********************************************************************************/

class DBHealthCheck {
	var $db;	
	var $dbType;
	var $dbName;
	var $dbHostName;
	var $recommendedEngineType = 'InnoDB';
	
	function DBHealthCheck($db) {
		$this->db = $db;
		$this->dbType = $db->databaseType;
		$this->dbName = $db->databaseName;
		$this->dbHostName = $db->host;
	}
	
	function isDBHealthy() {
		$tablesList = $this->getUnhealthyTablesList();
		if (count($tablesList) > 0) {
			return false;
		}
		return true;
	}
	
	function getUnhealthyTablesList() {
		$tablesList = array();
		$dbApiName = '_'.($this->dbType).'_getUnhealthyTables';
		if(method_exists($this,$dbApiName)) {
			$tablesList = $this->$dbApiName();
		}
		return $tablesList;
	}
	
	function updateTableEngineType($tableName) {
		$dbApiName = '_'.($this->dbType).'_updateEngineType';
		if(method_exists($this,$dbApiName)) {
			$this->$dbApiName($tableName);
		}
	}
	
	function updateAllTablesEngineType() {
		$dbApiName = '_'.($this->dbType).'_updateEngineTypeForAllTables';
		if(method_exists($this,$dbApiName)) {
			$this->$dbApiName();
		}
	}
	
	function _mysql_getUnhealthyTables() {
		$tablesResult = $this->db->_Execute("SHOW TABLE STATUS FROM $this->dbName " .
					" WHERE name NOT LIKE '%_seq' AND engine != 'InnoDB'");
		$noOfTables = $tablesResult->NumRows($tablesResult);
		$unHealthyTables = array();
		for($i=0; $i<$noOfTables; ++$i) {
			$tableInfo = $tablesResult->GetRowAssoc(0);
			//print_r($tableInfo); die();
			$unHealthyTables[$i]['name'] = $tableInfo['name'];
			$unHealthyTables[$i]['engine'] = $tableInfo['engine'];
			$unHealthyTables[$i]['autoincrementValue'] = $tableInfo['auto_increment'];
			$tableCollation = $tableInfo['collation'];
			$unHealthyTables[$i]['characterset'] = substr($tableCollation, 0, strpos($tableCollation,'_'));
			$unHealthyTables[$i]['collation'] = $tableCollation;
			$unHealthyTables[$i]['createOptions'] = $tableInfo['create_options'];
			$tablesResult->MoveNext();
		}
		return $unHealthyTables;
	}
	
	function _mysql_updateEngineType($tableName) {
		$this->db->_Execute("ALTER TABLE $tableName ENGINE=$this->recommendedEngineType");
	}
	
	function _mysql_updateEngineTypeForAllTables() {
		$tablesResult = $this->db->_Execute("SHOW TABLE STATUS FROM $this->dbName " .
					" WHERE name NOT LIKE '%_seq' AND engine != 'InnoDB'");
		$noOfTables = $tablesResult->NumRows($tablesResult);
		for($i=0; $i<$noOfTables; ++$i) {
			$tableRow = $tablesResult->GetRowAssoc(0);
			$tableName = $tableRow['name'];
			$this->db->_Execute("ALTER TABLE $tableName ENGINE=$this->recommendedEngineType");
			$tablesResult->MoveNext();
		}		
	}
}
?>