<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
include_once('config.inc.php');
include_once('include/database/PearDatabase.php');
include_once('include/utils/utils.php');

/**
 * Provides few utility functions
 * @package vtlib
 */
class Vtiger_Utils {

	/**
	 * Check if given value is a number or not
	 * @param mixed String or Integer
	 */
	static function isNumber($value) {
		return is_numeric($value)? intval($value) == $value : false;
	}

	/** 
	 * Function to check the file access is made within web root directory. 
	 * @param String File path to check
	 * @param Boolean False to avoid die() if check fails
	 */
	static function checkFileAccess($filepath, $dieOnFail=true) {
		global $root_directory;
		$realfilepath = realpath($filepath);

		$realfilepath = str_replace('\\', '/', $realfilepath);
		$rootdirpath  = str_replace('\\', '/', $root_directory);

		if(stripos($realfilepath, $rootdirpath) !== 0) {
			if($dieOnFail) {
				die("Sorry! Attempt to access restricted file.");
			}
			return false;
		}
		return true;
	}

	/**
	 * Log the debug message 
	 * @param String Log message
	 * @param Boolean true to append end-of-line, false otherwise
	 */
	static function Log($message, $delimit=true) {
		global $Vtiger_Utils_Log;
		if(!isset($Vtiger_Utils_Log) || $Vtiger_Utils_Log == false) return;

		print_r($message);
		if($delimit) {
			if(isset($_REQUEST)) echo "<BR>";
			else echo "\n";
		}
	}

	/**
	 * Escape the string to avoid SQL Injection attacks.
	 * @param String Sql statement string
	 */
	static function SQLEscape($value) {
		if($value == null) return $value;
		return mysql_real_escape_string($value);
	}

	/**
	 * Check if table is present in database
	 * @param String tablename to check
	 */
	static function CheckTable($tablename) {
		global $adb;
		$old_dieOnError = $adb->dieOnError;
		$adb->dieOnError = false;

		$tablecheck = $adb->query("select count(*) as count from $tablename");

		$tablePresent = true;
		if(empty($tablecheck) || $adb->num_rows($tablecheck) <= 0)
			$tablePresent = false;

		$adb->dieOnError = $old_dieOnError;
		return $tablePresent;
	}

	/**
	 * Create table (supressing failure)
	 * @param String tablename to create
	 * @param String table creation criteria like '(columnname columntype, ....)' <br>
	 * will be appended to CREATE TABLE $tablename SQL
	 */
	static function CreateTable($tablename, $criteria) {
		global $adb;

		$org_dieOnError = $adb->dieOnError;
		$adb->dieOnError = false;
		$adb->query("CREATE TABLE " . $tablename . $criteria);
		$adb->dieOnError = $org_dieOnError;	
	}

	/**
	 * Alter existing table
	 * @param String tablename to alter
	 * @param String alter criteria like ' ADD columnname columntype' <br>
	 * will be appended to ALTER TABLE $tablename SQL
	 */
	static function AlterTable($tablename, $criteria) {
		global $adb;
		$adb->query("ALTER TABLE " . $tablename . $criteria);
	}

	/**
	 * Add column to existing table
	 * @param String tablename to alter
	 * @param String columnname to add
	 * @param String columntype (criteria like 'VARCHAR(100)') 
	 */
	static function AddColumn($tablename, $columnname, $criteria) {
		global $adb;
		if(!in_array($columnname, $adb->getColumnNames($tablename))) {
			self::AlterTable($tablename, " ADD COLUMN $columnname $criteria");
		}
	}

	/**
	 * Get SQL query
	 * @param String SQL query statement
	 */
	static function ExecuteQuery($sqlquery, $supressdie=false) {
		global $adb;
		$old_dieOnError = $adb->dieOnError;

		if($supressdie) $adb->dieOnError = false;

		$adb->query($sqlquery);

		$adb->dieOnError = $old_dieOnError;
	}

	/**
	 * Get CREATE SQL for given table
	 * @param String tablename for which CREATE SQL is requried
	 */
	static function CreateTableSql($tablename) {
		global $adb;

		$create_table = $adb->query("SHOW CREATE TABLE $tablename");
		$sql = decode_html($adb->query_result($create_table, 0, 1));
		$lastIndex = strripos($sql, ')');
		if($lastIndex !== false) {
			$sql = substr($sql, 0, $lastIndex+1);
		}
		return $sql;
	}

	/**
	 * Check if the given SQL is a CREATE statement
	 * @param String SQL String
	 */
	static function IsCreateSql($sql) {
		if(preg_match('/(CREATE TABLE)/', strtoupper($sql))) {
			return true;
		}
		return false;
	}

	/**
	 * Check if the given SQL is destructive (DELETE's DATA)
	 * @param String SQL String
	 */
	static function IsDestructiveSql($sql) {
		if(preg_match('/(DROP TABLE)|(DROP COLUMN)|(DELETE FROM)/', 
			strtoupper($sql))) {
			return true;
		}
		return false;
	}
}
?>
