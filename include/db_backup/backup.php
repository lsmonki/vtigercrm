<?php
/*********************************************************************************
 ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
 *********************************************************************************
 */

require_once("config.php");
require_once("include/database/PearDatabase.php");
define("dbserver", $dbconfig['db_hostname']);
define("dbuser", $dbconfig['db_username']);
define("dbpass", $dbconfig['db_password']);
define("dbname", $dbconfig['db_name']);

function save_structure($filename, $root_directory) {
		global $log;
		$log->debug("Entering save_structure(".$filename.",".$root_directory.") method ...");

		$dbdump = new DatabaseDump(dbserver, dbuser, dbpass);
		$dumpfile = $root_directory.'/'.$filename;
		$dbdump->save(dbname, $dumpfile) ;
        $log->debug("Exiting save_structure method ...");
}

/**
 * DatabaseDump will save the dump of database to the file specified.
 *
 * The dump file contains series of SQL statements (with some meta information)
 * generated similar to mysqldump command.
 *
 * To restore back the dump you can use the 'source' command in sql.
 * Like:
 * mysql> create database dbname;
 * mysql> use dbname;
 * mysql> source sql_dump_file;
 *
 * @author Prasad
 */
class DatabaseDump {
		private $fhandle;
		function DatabaseDump($dbserver, $username, $password) {
				mysql_connect($dbserver, $username, $password);
		}
		function save($database, $filename) {
			// Connect to database
			$db = mysql_select_db($database);
			
			if(empty($db)) {
				return;
			}
			$this->file_open($filename);

			// Write some information regarding database dump and the time first.	
			$this->writeln("-- $database database dump");
			$this->writeln("-- Date: " . date("D, M j, G:i:s T Y"));
			$this->writeln("------------------------------------------------");
			$this->writeln("");
	
			// Meta information which helps to import into mysql database.
			$this->writeln("SET FOREIGN_KEY_CHECKS=0;");
			$this->writeln("SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';");
			$this->writeln("");

			// Get all table names from database
			$tcount = 0;
			$trs = mysql_list_tables($database);
			for($tindex = 0; $tindex < mysql_num_rows($trs); $tindex++) {
				$table = mysql_tablename($trs, $tindex);
				if(!empty($table)) {
					$tables[$tcount] = mysql_tablename($trs, $tindex);
					$tcount++;
				}
			}

			// List tables
			$dump = '';
			for($tindex = 0; $tindex < count($tables); $tindex++) {
				// Table Name
				$table = $tables[$tindex];

				$table_create_rs = mysql_query("SHOW CREATE TABLE `$table`");
				$table_create_rows = mysql_fetch_array($table_create_rs);
				$table_create_sql = $table_create_rows[1];

				// Write table create statement 
				$this->writeln("");
				$this->writeln("--");
				$this->writeln("-- Table structure for table `$table` ");
				$this->writeln("--");
				$this->writeln("");
				$this->writeln("DROP TABLE IF EXISTS `$table`;");
				$this->writeln($table_create_sql . ';');
				$this->writeln("");

				// Write data
				$this->writeln("--");
				$this->writeln("-- Dumping data for table `$table` ");
				$this->writeln("--");
				$this->writeln("");

				$table_query = mysql_query("SELECT * FROM `$table`");
				$num_fields = mysql_num_fields($table_query);
				while($fetch_row = mysql_fetch_array($table_query)) {
					$insert_sql = "INSERT INTO `$table` VALUES(";
					for($n = 1; $n <= $num_fields; $n++) {
							$m = $n -1;
							$field_value = $fetch_row[$m];
							$field_value = str_replace('\"', '"', mysql_escape_string($field_value));
							$insert_sql .= "'". $field_value . "', ";
					}
					$insert_sql = substr($insert_sql,0,-2);
					$insert_sql .= ");";

					if($insert_sql != "") {
						$this->writeln($insert_sql);
					}
				}
			}
			// Meta information reset to original state.
			$this->writeln("SET FOREIGN_KEY_CHECKS=0;");

			$this->file_close();
	}
	function file_open($filename) {	$this->fhandle = fopen($filename, "w+"); }
	function file_close()         { fclose($this->fhandle); }
	function write($string)       { fprintf($this->fhandle, "%s", $string); }
	function writeln($string)     { fprintf($this->fhandle, "%s\r\n", $string); }
};
?>
