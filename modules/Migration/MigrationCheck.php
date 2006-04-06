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

include("config.inc.php");

$old_version = $_REQUEST['old_version'];
$latest_version = $_REQUEST['latest_version'];

//old database values
$old_host_name = $_REQUEST['old_host_name'];
$old_mysql_port = $_REQUEST['old_port_no'];
$old_mysql_username = $_REQUEST['old_mysql_username'];
$old_mysql_password = $_REQUEST['old_mysql_password'];
$old_dbname = $_REQUEST['old_dbname'];

//new database values get from the current vtigerCRM's config.php
global $dbconfig;
$new_host_name = $dbconfig['db_hostname'];
$new_mysql_username = $dbconfig['db_username'];
$new_mysql_password = $dbconfig['db_password'];
$new_dbname = $dbconfig['db_name'];


//make a connection with the old database
$oldconn = @mysql_connect($old_host_name.":".$old_mysql_port,$old_mysql_username,$old_mysql_password);

//make a connection with the new database
$newconn = @mysql_connect($new_host_name,$new_mysql_username,$new_mysql_password);

if(!$oldconn)
{
	echo '<br> Source Database Server can not be connected';
	$continue1 = 0;
}
elseif(!$newconn)
{
	echo '<br> New Database Server can not be connected';
	$continue1 = 0;
}
else
{
	echo '<br> Database Servers can be conneted';
	$continue1 = 1;
}

if($continue1 == 1)
{
	//check whether the specified databases are exist or not
	$olddb_exist = @mysql_select_db($old_dbname,$oldconn);
	//$newdb_exist = @mysql_select_db($new_dbname,$oldconn);
	if(!$olddb_exist)
	{
		echo '<br> Source Database is not exist';
		$continue2 = 0;
	}
	//elseif(!$newdb_exist)
	//{
	//	echo '<br> New Database is not exist';
	//	$continue2 = 0;
	//}
	else
	{
		echo '<br> Databases are exist';
		$continue2 = 1;
	}
}

if($continue2 == 1)
{
	//Check whether the table are exist in the databases or not
	$old_tables = @mysql_num_rows(mysql_list_tables($old_dbname,$oldconn));
	//$new_tables = @mysql_num_rows(mysql_list_tables($new_dbname));

	if(!$old_tables)
	{
		echo '<br> Tables are not exist in Source Database';
		$continue3 = 0;
	}
/*	if(!$new_tables)
        {
                echo '<br> Tables are not exist in New Database';
                $continue3 = 0;
        }
*/
	else
	{
		echo '<br> Tables are exist in the Database';
		$continue3 = 1;
	}
}

//To check whether the two databases are same
if($continue3 == 1)
{
	$new_host = explode(":",$new_host_name);

	if($old_host_name == $new_host[0] && $old_mysql_port == $new_host[1] && $old_mysql_username == $new_mysql_username && $old_mysql_password == $new_mysql_password && $old_dbname == $new_dbname)
	{
		echo '<br> Two databases are same.';
		$continue4 = 1;//change the value to 0 if you don't want to proceed with the same database
		$same_databases = 1;
	}
	else
	{
		$continue4 = 1;
		$same_databases = 0;
	}
}

//$continue1 -- Database servers can be connected
//$continue2 -- Database exists in the servers
//$continue3 -- Tables are exist in the databases
//$continue4 -- Two databases are not same

if($continue1 == 1 && $continue2 == 1 && $continue3 == 1 && $continue4 == 1)
{
	$new_host = explode(":",$new_host_name);

	echo '<br>';
	echo '<br>*************************************************************';
	echo '<br><b>Source Database Parameters : </b>
		<br> Host Name : '.$old_host_name.'
		<br> MySql Port : '.$old_mysql_port.'
		<br> MySql User Name : '.$old_mysql_username.'
		<br> MySql Password : '.$old_mysql_password.'
		<br> DB Name : '.$old_dbname;
	echo '<br>*************************************************************';
	echo '<br><b>Current Database Parameters : </b>
		<br> Host Name : '.$new_host[0].'
		<br> MySql Port : '.$new_host[1].'
		<br> MySql User Name : '.$new_mysql_username.'
		<br> MySql Password : '.$new_mysql_password.'
		<br> DB Name : '.$new_dbname;
	echo '<br>*************************************************************';

	//echo '<br>================'.$old_version.'==================';

	$conn = new PearDatabase("mysql",$new_host_name,$new_dbname,$new_mysql_username,$new_mysql_password);
	$conn->connect();
	if($conn)
	{
		//$filename = 'migration_'.$old_version.'_to_'.$latest_version.'.php';
	        //include("migration/$filename");

		include("modules/Migration/Migration.php");
		$obj = new Migration('',$conn);
		$obj->setOldDatabaseParams($old_host_name,$old_mysql_port,$old_mysql_username,$old_mysql_password,$old_dbname);
		$obj->setNewDatabaseParams($new_host[0],$new_host[1],$new_mysql_username,$new_mysql_password,$new_dbname);
		$obj->migrate($same_databases);
		//echo '<pre>';print_r($obj);echo '</pre>';
	}
	else
	{
		echo '<br> Cannot make a connection with the current database setup';
		include("modules/Migration/MigrationStep1.php");
	}
}
else
{
	echo '<br>Please check the values.';
	include("modules/Migration/MigrationStep1.php");
}

?>
