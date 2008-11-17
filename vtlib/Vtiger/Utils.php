<?php

class Vtiger_Utils {

	/** 
	 * Function to check the file access is made within web root directory. 
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
	 * Log the debug message if set.
	 */
	static function Log($message) {
		global $Vtiger_Utils_Log;
		if(!isset($Vtiger_Utils_Log) || $Vtiger_Utils_Log == false) return;

		print_r($message);
		if(isset($_REQUEST)) echo "<BR>";
		else echo "\n";		
	}

	/**
	 * Escape the string to avoid SQL Injection attacks.
	 */
	static function SQLEscape($value) {
		if($value == null) return $value;
		return mysql_real_escape_string($value);
	}

	/**
	 * Check if table is present in database.
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
	 * Create table (supressing failure).
	 */
	static function CreateTable($tablename, $criteria) {
		global $adb;

		$org_dieOnError = $adb->dieOnError;
		$adb->dieOnError = false;
		$adb->query("CREATE TABLE " . $tablename . $criteria);
		$adb->dieOnError = $org_dieOnError;	
	}

	/**
	 * Alter existing table.
	 */
	static function AlterTable($tablename, $criteria) {
		global $adb;
		$adb->query("ALTER TABLE " . $tablename . $criteria);
	}

	/**
	 * Get SQL query.
	 */
	static function ExecuteQuery($sqlquery) {
		global $adb;
		$adb->query($sqlquery);
	}

	/**
	 * Get CREATE SQL for given table.
	 */
	static function CreateTableSql($tablename) {
		global $adb;

		$create_table = $adb->query("SHOW CREATE TABLE $tablename");
		return $adb->query_result($create_table, 0, 1);
	}
}

?>
