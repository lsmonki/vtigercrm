<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/

require_once("config.php");
require_once("include/database/PearDatabase.php");
define("dbserver", $dbconfig['db_host_name']);
define("dbuser", $dbconfig['db_user_name']);
define("dbpass", $dbconfig['db_password']);
define("dbname", $dbconfig['db_name']);

function get_structure()
{
	mysql_connect(dbserver, dbuser, dbpass);
	mysql_select_db(dbname);
	$tables = mysql_list_tables(dbname);
	while ($td = mysql_fetch_array($tables))
	{
		$table = $td[0];
		$r = mysql_query("SHOW CREATE TABLE `$table`");
		if ($r)
		{
			$insert_sql = "";
			$d = mysql_fetch_array($r);
			$d[1] .= ";";
			$SQL[] = str_replace("\n", "", $d[1]);
			$table_query = mysql_query("SELECT * FROM `$table`");
			$num_fields = mysql_num_fields($table_query);
			while ($fetch_row = mysql_fetch_array($table_query)){
				$insert_sql .= "INSERT INTO $table VALUES(";
				for ($n=1;$n<=$num_fields;$n++){
					$m = $n - 1;
					$insert_sql .= "'".$fetch_row[$m]."', ";
				}
				$insert_sql = substr($insert_sql,0,-2);
				$insert_sql .= ");\n";
			}
			if ($insert_sql != ""){
				$SQL[] = $insert_sql;
			}
		}
	}
	return $SQL;
}

function save_structure($filename, $root_directory)
{
	$sql = get_structure();
	$sql = implode("\r", $sql);
	$handle = fopen($root_directory.'/'.$filename,"wb") ;
	fwrite($handle,$sql,9999999999);
	fclose($handle);
}

?>
