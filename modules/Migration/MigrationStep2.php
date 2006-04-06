<?php
/*********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
 ********************************************************************************/

include("modules/Migration/Migration.php");

if($_REQUEST['pre_action'] == 'MigrationStep1')
{
	//The control will comes here when the Current installation's MySQL path could not be found itself. 
	$current_mysql_install = $_REQUEST['current_mysql_install'];
	$current_mysqlpath = $_REQUEST['current_mysqlpath'];
	$old_dump_filename = $_REQUEST['old_dump_filename'];

	if($old_dump_filename != '' && is_file($old_dump_filename))
	{
		$gotostep1 = 0;
		//apply this dump file to the new database
		$checkDumpFileAndApply = 1;
	}
	elseif($current_mysql_install == 'same' && is_file(trim($current_mysqlpath."/")."/bin/mysqldump"))
	{
		$getOld_Params = 1;
	}
	else
	{
		$gotostep1 = 1;
		if($old_dump_filename != '' && !is_file($old_dump_filename))
		{
			$invalid_dump = 1;
		}
		if($current_mysqlpath != '' && !is_file(trim($current_mysqlpath."/")."/bin/mysqldump"))
		{
			$mysqldumpNotExist = 1;
		}
	}
}

//This is to redirect the page to Step1 to re-get the parameters as given dump file is not a file or mysqldump file is not exist
if($gotostep1 == 1)
{
	if($invalid_dump == 1 && $mysqldumpNotExist == 1 && $current_mysql_install == 'same')
	{
		echo '<br> Please Enter the correct MySQL Path (or) Enter a valid Dump file.';
	}
	elseif($invalid_dump == 1)
	{
		echo '<br> Please Enter a valid Dump file.';
	}
	elseif($mysqldumpNotExist == 1)
	{
		echo '<br><b>mysqldump</b> file is not exist in the given MySQL path. 
			<br>Please Take a Dump of Source Database and enter the Dump file.';
	}
	else
	{
		echo '<br> Please Enter the correct MySQL Path (or) Enter a valid Dump file.';
	}
	include("modules/Migration/MigrationStep1.php");
}
elseif($getOld_Params == 1)
{
	//Go to step1 and get the Source Database parameters
	$getOldParams = 1;
	include("modules/Migration/MigrationStep1.php");
}
elseif($checkDumpFileAndApply == 1)
{
	//TODO - Check whether the given file is Dump file and then apply to the new database
	echo '<br> Going to apply the Dump file to the new database.';

	include("config.inc.php");
	global $dbconfig;

	$new_host_name = $dbconfig['db_hostname'];
	$new_dbname = $dbconfig['db_name'];
	$new_mysql_username = $dbconfig['db_username'];
	$new_mysql_password = $dbconfig['db_password'];

	$conn = new PearDatabase("mysql",$new_host_name,$new_dbname,$new_mysql_username,$new_mysql_password);
        $conn->connect();

	if($conn)
        {
		$obj = new Migration('',$conn);

		$new_host = explode(":",$new_host_name);
		$temp_new_host_name = $new_host[0];
		$new_mysql_port = $new_host[1];

		$conn->println("Going to Drop the current Database");
                $obj->dropDatabase($conn,$new_dbname);

		$conn->println("Going to Create the current Database");
                $obj->createDatabase($conn,$new_dbname);

		$conn->println("Going to apply the old database dump to the new database.");
                $obj->applyDumpData($temp_new_host_name,$new_mysql_port,$new_mysql_username,$new_mysql_password,$new_dbname,$old_dump_filename);

		$conn->println("Going to modify the current database which is now as old database setup");
                $obj->modifyDatabase($conn);

                $conn->println("Database Modifications Ends......");
                $conn->println("Database Migration from Source Database to Current Database has been Finished.");
	}
}
//elseif($takeDump == 1)
//{
//	//TODO - Go to this given path and Get the MySQL Dump of Source Database and Proceed with the regular process
//	echo '<br> MySQL path has been given. Go to this path and take dump and the proceed.'; 
//}










?>
