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
	//The control will comes here when the 4.5 MySQL could not be found itself. 
	$mysql_install_4_5 = $_REQUEST['mysql_install_4_5'];
	$mysqlpath_4_5 = $_REQUEST['mysqlpath_4_5'];
	$dump_filename_4_5 = $_REQUEST['dump_filename_4_5'];

	if($dump_filename_4_5 != '' && is_file($dump_filename_4_5))
	{
		$gotostep1 = 0;
		//apply this dump file to the new database
		$checkDumpFileAndApply = 1;
	}
	elseif($mysql_install_4_5 == 'same' && is_file(trim($mysqlpath_4_5."/")."/bin/mysqldump"))
	{
		$get4_2_Params = 1;
	}
	else
	{
		$gotostep1 = 1;
		if($dump_filename_4_5 != '' && !is_file($dump_filename_4_5))
		{
			$invalid_dump = 1;
		}
		if($mysqlpath_4_5 != '' && !is_file(trim($mysqlpath_4_5."/")."/bin/mysqldump"))
		{
			$mysqldumpNotExist = 1;
		}
	}
}

//This is to redirect the page to Step1 to re-get the parameters as given dump file is not a file or mysqldump file is not exist
if($gotostep1 == 1)
{
	if($invalid_dump == 1 && $mysqldumpNotExist == 1 && $mysql_install_4_5 == 'same')
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
			<br>Please Take a Dump of 4.2 Database and enter the Dump file.';
	}
	else
	{
		echo '<br> Please Enter the correct MySQL Path (or) Enter a valid Dump file.';
	}
	include("modules/Migration/MigrationStep1.php");
}
elseif($get4_2_Params == 1)
{
	//Go to step1 and get the 4.2 parameters
	$getparams_4_2 = 1;
	include("modules/Migration/MigrationStep1.php");
}
elseif($checkDumpFileAndApply == 1)
{
	//TODO - Check whether the given file is Dump file and then apply to the new database
	echo '<br> Going to apply the Dump file to the new database.';

	include("config.php");
	global $dbconfig;

	$new_host_name = $dbconfig['db_host_name'];
	$new_dbname = $dbconfig['db_name'];
	$new_mysql_username = $dbconfig['db_user_name'];
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
                $obj->applyDumpData($temp_new_host_name,$new_mysql_port,$new_mysql_username,$new_mysql_password,$new_dbname,$dump_filename_4_5);

		$conn->println("Going to modify the current database which is now as old database setup");
                $obj->modifyDatabase42P2_to_45Alpha($conn);

                $conn->println("Database Modifications Ends......");
                $conn->println("Database Migration from 4.2 Patch2 to 4.5(Alpha) Finished.");
	}
}
//elseif($takeDump == 1)
//{
//	//TODO - Go to this given path and Get the MySQL Dump of 4.2 Database and Proceed with the regular process
//	echo '<br> MySQL path has been given. Go to this path and take dump and the proceed.'; 
//}










?>
