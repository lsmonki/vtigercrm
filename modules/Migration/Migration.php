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

//Migration Procedure
//Step : 1 => Take a dump of old database
//Step : 2 => Drop the New Database
//Step : 3 => Create the New Database
//Step : 4 => Put the old dump into the New Database
//Step : 5 => Modify the new database with the new changes

class Migration
{

	var $conn;
	var $oldconn;

	//Old Database Parameters
	var $old_hostname;
	var $old_mysql_port;
	var $old_mysql_username;
	var $old_mysql_password;
	var $old_dbname;

	//New Database Parameters
	var $new_hostname;
	var $new_mysql_port;
	var $new_mysql_username;
	var $new_mysql_password;
	var $new_dbname;

	function Migration($old='',$new='')
	{
		$this->oldconn = $old;
		$this->conn = $new;
		$this->conn->println("Database Object has been created.");
	}
	function setOldDatabaseParams($hostname,$mysql_port,$mysql_username,$mysql_password,$dbname)
	{
		$this->old_hostname = $hostname;
		$this->old_mysql_port = $mysql_port;
		$this->old_mysql_username = $mysql_username;
		$this->old_mysql_password = $mysql_password;
		$this->old_dbname = $dbname;
		$this->conn->println("Old Database Parameters has been set.");
	}
	function setNewDatabaseParams($hostname,$mysql_port,$mysql_username,$mysql_password,$dbname)
	{
		$this->new_hostname = $hostname;
		$this->new_mysql_port = $mysql_port;
		$this->new_mysql_username = $mysql_username;
		$this->new_mysql_password = $mysql_password;
		$this->new_dbname = $dbname;
		$this->conn->println("New Database Parameters has been set.");
	}

	function takeDatabaseDump($host_name,$mysql_port,$mysql_username,$mysql_password,$dbname)
	{
		$this->conn->println("Inside the function taleDatabaseDump. Going to take the old database dump...");
		$dump_filename = 'dump_'.$dbname.'.txt';

		if($mysql_password != '')
		{
			$password_str = " -p".$mysql_password;
		}
		else
		{
			$password_str = '';
		}
		if($_SESSION['windows_mysql_path'] != '')
		{
			$current_working_dir = getcwd();
			$win_mysql_path = $_SESSION['windows_mysql_path'];

			$dump_str = "mysqldump -u".$mysql_username.$password_str." -h ".$host_name." --port=".$mysql_port." ".$dbname." >> ".$dump_filename;

			chdir ($win_mysql_path);

			exec("echo 'set FOREIGN_KEY_CHECKS = 0;' > ".$dump_filename);
			exec($dump_str);
			exec("echo 'set FOREIGN_KEY_CHECKS = 1;' >> ".$dump_filename);
			
			exec('cp "'.$win_mysql_path.'\\'.$dump_filename.'" "'.$current_working_dir.'\\'.$dump_filename).'"';
			chdir ($current_working_dir);
		}
		else
		{
			exec("mysqldump -u".$mysql_username." -h ".$host_name.$password_str." --port=".$mysql_port." ".$dbname." >> ".$dump_filename);
		}

		echo '<br> Old Database Dump has been taken and the file is ==> '.$dump_filename;

		return $dump_filename;
	}

	function dropDatabase($conn,$dbname)
	{
		$this->conn->println("Inside the function dropDatabase. Going to drop the new database...");
		$sql = "drop database ".$dbname;
		$conn->query($sql);

		echo '<br> Current Database has been dropped.';
	}
	function createDatabase($conn,$dbname)
	{
		$this->conn->println("Inside the function createDatabase. Going to create the new database...");
		$sql = "create database ".$dbname;
		$conn->query($sql);

		echo '<br> Current Database has been created.';

		//Added to avoid the No Database Selected error when execute the queries
		$conn->connect();
	}

	function applyDumpData($host_name,$mysql_port,$mysql_username,$mysql_password,$dbname,$dumpfile)
	{
		if($mysql_password != '')
		{
			$password_str = " --password".$mysql_password;
		}
		else
		{
			$password_str = '';
		}
		if($_SESSION['windows_mysql_path'] != '')
		{
			$current_working_dir = getcwd();
			$win_mysql_path = $_SESSION['windows_mysql_path'];
			
			$dump_str = "mysql --user=".$mysql_username.$password_str." -h ".$host_name." --force --port=".$mysql_port." ".$dbname." < ".$dumpfile;

			chdir ($win_mysql_path);

			exec($dump_str);
			
			chdir ($current_working_dir);
		}
		else
		{
			exec("mysql --user=".$mysql_username." -h ".$host_name." --force --port=".$mysql_port.$password_str." ".$dbname." < ".$dumpfile);
		}

		echo '<br> Old Database Dump has been applied to the Current Database.';
	}


	function localGetTabID($module)
	{
		global $conn;

		$sql = "select tabid from tab where name='".$module."'";
		$result = $conn->query($sql);
		$tabid=  $conn->query_result($result,0,"tabid");

		return $tabid;
	}

	function modifyDatabase($conn)
	{
		echo '<br><br>Note : Please note that for each query the "Object" string will be displayed at the starting of the line if the query executed successfully. If the query fails then "Object" will not be displayed. we can find out the failed queries based on these Object display.';
		$conn->println("\n\n\nMickie ---- Starts");

		//To handle the file includes for each and every version
		//Here we have to decide which files should be included, where the files will be added newly for every public release
		//Handle Here -- Mickie
		include("ModifyDatabase/42P2_to_50Alpha.php");

		$conn->println("Mickie ---- Ends\n\n\n");
	}

	function migrate($same_databases)
	{
		//Migration Procedure
		//Step : 1 => Take a dump of old database
		//Step : 2 => Drop the New Database
		//Step : 3 => Create the New Database
		//Step : 4 => Put the old dump into the New Database
		//Step : 5 => Modify the new database with the new changes

		global $conn;
		$this->conn->println("Database Migration from Old Database to the Current Database Starts.");

		//Set the old database parameters
		$old_host_name = $this->old_hostname;
		$old_mysql_port = $this->old_mysql_port;
		$old_mysql_username = $this->old_mysql_username;
		$old_mysql_password = $this->old_mysql_password;
		$old_dbname = $this->old_dbname;

		//Set the new database parameters
		$new_host_name = $this->new_hostname;
		$new_mysql_port = $this->new_mysql_port;
		$new_mysql_username = $this->new_mysql_username;
		$new_mysql_password = $this->new_mysql_password;
		$new_dbname = $this->new_dbname;



		//Take the dump of the old Database
		$this->conn->println("Going to take the old Database Dump.");
		$dump_file = $this->takeDatabaseDump($old_host_name,$old_mysql_port,$old_mysql_username,$old_mysql_password,$old_dbname);

		if($same_databases == 1)
		{
			echo '<br> Same databases are used. so skip the process of drop and crate the current database.';
		}
		else
		{
			echo '<br> Databases are different. So drop the Current Database and create. Also apply the dump of Old Database';
			//Drop the current(latest) Database
			$this->conn->println("Going to Drop the current Database");
			$this->dropDatabase($conn,$new_dbname);

			//Create the new current(latest) Database
			$this->conn->println("Going to Create the current Database");
			$this->createDatabase($conn,$new_dbname);

			//Apply the dump of the old database to the current database
			$this->conn->println("Going to apply the old database dump to the new database.");
			$this->applyDumpData($new_host_name,$new_mysql_port,$new_mysql_username,$new_mysql_password,$new_dbname,$dump_file);
		}

		//Modify the database which is now as old database setup
		$this->conn->println("Going to modify the current database which is now as old database setup");
		$this->modifyDatabase($conn);
		
		$this->conn->println("Database Modifications Ends......");
		$this->conn->println("Database Migration from Old Database to the Current Database has been Finished.");
	}

}



?>
