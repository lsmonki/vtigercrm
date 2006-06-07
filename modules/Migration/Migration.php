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
		$this->conn->println("Inside the function takeDatabaseDump. Going to take the old database dump...");
		$dump_filename = 'dump_'.$dbname.'.txt';

		if($mysql_password != '')
		{
			$password_str = " -p".$mysql_password;
		}
		else
		{
			$password_str = '';
		}

		//This if is used when we cannot access mysql from vtiger root directory
		if($_SESSION['set_server_mysql_path'] != '')
		{
			$current_working_dir = getcwd();
			$server_mysql_path = $_SESSION['set_server_mysql_path'];

			$dump_str = "mysqldump -u".$mysql_username.$password_str." -h ".$host_name." --port=".$mysql_port." ".$dbname." >> ".$dump_filename;

			chdir ($server_mysql_path);

			exec("echo 'set FOREIGN_KEY_CHECKS = 0;' > ".$dump_filename);
			exec($dump_str);
			exec("echo 'set FOREIGN_KEY_CHECKS = 1;' >> ".$dump_filename);
			
			exec('cp "'.$server_mysql_path.'\\'.$dump_filename.'" "'.$current_working_dir.'\\'.$dump_filename).'"';
			chdir ($current_working_dir);
		}
		else
		{
			exec("echo 'set FOREIGN_KEY_CHECKS = 0;' > ".$dump_filename);
			exec("mysqldump -u".$mysql_username." -h ".$host_name.$password_str." --port=".$mysql_port." ".$dbname." >> ".$dump_filename);
			exec("echo 'set FOREIGN_KEY_CHECKS = 1;' >> ".$dump_filename);
		}

		$_SESSION['migration_log'] .= '<br> <b>'.$dbname.'</b> Database Dump has been taken and the file is ==> '.$dump_filename;

		return $dump_filename;
	}

	function dropDatabase($conn,$dbname)
	{
		$this->conn->println("Inside the function dropDatabase. Going to drop the new database...");
		$sql = "drop database ".$dbname;
		$conn->query($sql);

		$_SESSION['migration_log'] .= '<br> <b>'.$dbname.'</b> Database has been dropped.';
	}
	function createDatabase($conn,$dbname)
	{
		$this->conn->println("Inside the function createDatabase. Going to create the new database...");
		$sql = "create database ".$dbname;
		$conn->query($sql);

		$_SESSION['migration_log'] .= '<br> <b>'.$dbname.'</b> Database has been created.';

		//Added to avoid the No Database Selected error when execute the queries
		$conn->connect();
	}

	function applyDumpData($host_name,$mysql_port,$mysql_username,$mysql_password,$dbname,$dumpfile)
	{
		$this->conn->println("Inside the function applyDumpData.");
		$this->conn->println("hostname=$host_name&port=$mysql_port&username=$mysql_username&password=$mysql_password& dump file = $dumpfile");
		if($mysql_password != '')
		{
			$password_str = " --password=".$mysql_password;
		}
		else
		{
			$password_str = '';
		}

		//This if is used when we cannot access mysql from vtiger root directory
		if($_SESSION['set_server_mysql_path'] != '')
		{
			$current_working_dir = getcwd();
			$server_mysql_path = $_SESSION['set_server_mysql_path'];
			
			$dump_str = "mysql --user=".$mysql_username.$password_str." -h ".$host_name." --force --port=".$mysql_port." ".$dbname." < ".$dumpfile;

			//exec("path = $server_mysql_path");
			chdir ($server_mysql_path);

			exec($dump_str);
			
			chdir ($current_working_dir);
		}
		else
		{
			exec("mysql --user=".$mysql_username." -h ".$host_name." --force --port=".$mysql_port.$password_str." ".$dbname." < ".$dumpfile);
		}

		$_SESSION['migration_log'] .= '<br> Database Dump has been applied to the <b>'.$dbname.'</b> Database from <b>'.$dumpfile.'</b>';
	}


	function localGetTabID($module)
	{
		global $conn;

		$sql = "select tabid from tab where name='".$module."'";
		$result = $conn->query($sql);
		$tabid=  $conn->query_result($result,0,"tabid");

		return $tabid;
	}

	function getTablesCountInNewDatabase()
	{
		$this->conn->println("Inside the function getTablesCountInNewDatabase");
		$newconn = @mysql_connect($this->new_hostname.':'.$this->new_mysql_port,$this->new_mysql_username,$this->new_mysql_password);
		$tables = @mysql_num_rows(mysql_list_tables($this->new_dbname,$newconn));

		$this->conn->println("Number of Tables in New Database = ".$tables);
		return $tables;
	}

	function getTablesCountInOldDatabase()
	{
		$this->conn->println("Inside the function getTablesCountInOldDatabase");
		$oldconn = @mysql_connect($this->old_hostname.':'.$this->old_mysql_port,$this->old_mysql_username,$this->old_mysql_password);
		$tables = @mysql_num_rows(mysql_list_tables($this->old_dbname,$oldconn));

		$this->conn->println("Number of Tables in Old Database = ".$tables);
		return $tables;
	}

	function modifyDatabase($conn)
	{
		$this->conn->println("Inside the function modifyDatabase");
		$conn->println("\n\n\nMickie ---- Starts");

		$_SESSION['migration_log'] .= "<br>The current database is going to be modified by executing the following queries...<br>";
		
		//Added variables to get the queries list and count
		$query_count = 1;
		$success_query_count = 1;
		$failure_query_count = 1;
		$success_query_array = Array();
		$failure_query_array = Array();

		//To handle the file includes for each and every version
		//Here we have to decide which files should be included, where the files will be added newly for every public release
		//Handle Here -- Mickie
		include("modules/Migration/ModifyDatabase/MigrationInfo.php");

		$conn->println("Mickie ---- Ends\n\n\n");
	}

	function migrate($same_databases, $option, $old_dump_file_name='')
	{
		//1. Migration Procedure -- when we give the Source Database values
		//Step : 1 => Take a dump of old database
		//Step : 2 => Drop the New Database
		//Step : 3 => Create the New Database
		//Step : 4 => Put the old dump into the New Database
		//Step : 5 => Modify the new database with the new changes

		//2. Migration Procedure -- when we give the Database dump file
		//Step : 1 => Drop the New Database
		//Step : 2 => Create the New Database
		//Step : 3 => Put the dump into the New Database
		//Step : 4 => Modify the new database with the new changes


		global $conn;
		$this->conn->println("Database Migration from Old Database to the Current Database Starts.");
		$this->conn->println("Migration Option = $option");

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

		//This will be done when we give the Source Database details
		if($option == 'dbsource')
		{
			//Take the dump of the old Database
			$this->conn->println("Going to take the old Database Dump.");
			$dump_file = $this->takeDatabaseDump($old_host_name,$old_mysql_port,$old_mysql_username,$old_mysql_password,$old_dbname);
		}
		elseif($option == 'dumpsource')
		{
			$dump_file = $old_dump_file_name;
		}

		//if old db and new db are different then take new db dump
		if($old_dbname != $new_dbname)
		{
			//This is to take dump of the new database for backup purpose
			$this->conn->println("Going to take the current Database Dump.");
			$new_dump_file = $this->takeDatabaseDump($new_host_name,$new_mysql_port,$new_mysql_username,$new_mysql_password,$new_dbname);
		}


		$continue_process = 1;
		if($same_databases == 1)
		{
			$_SESSION['migration_log'] .= '<br> Same databases are used. so skip the process of drop and create the current database.';
		}
		else
		{
			$_SESSION['migration_log'] .= '<br> Databases are different. So drop the Current Database and create. Also apply the dump of Old Database';
			//Drop the current(latest) Database
			$this->conn->println("Going to Drop the current Database");
			$this->dropDatabase($conn,$new_dbname);

			//Create the new current(latest) Database
			$this->conn->println("Going to Create the current Database");
			$this->createDatabase($conn,$new_dbname);

			//Apply the dump of the old database to the current database
			$this->conn->println("Going to apply the old database dump to the new database.");
			$this->applyDumpData($new_host_name,$new_mysql_port,$new_mysql_username,$new_mysql_password,$new_dbname,$dump_file);

			//get the number of tables in new database 
			$new_tables_count = $this->getTablesCountInNewDatabase();

			//get the number of tables in old database 
			$old_tables_count = $this->getTablesCountInOldDatabase();

			//if tables are missing after apply the dump, then alert the user and quit
			if(($new_tables_count != $old_tables_count && $option == 'dbsource') || ($new_tables_count < 180 && $option == 'dumpsource'))
			{
				$this->conn->println("New Database tables not equal to Old Database tables count. Reload the current database again and quit.");
				
				$continue_process = 0;
				$msg = "The dump may not be applied correctly. Tables exist in 4.2.3 database : $old_tables_count. Tables exist in current database after apply the dump : $new_tables_count";
			   ?>
				<script language="javascript">
					alert("<?php echo $msg; ?>");
				</script>
			   <?php
			}
		}

		if($continue_process == 1)
		{
			//Modify the database which is now as old database setup
			$this->conn->println("Going to modify the current database which is now as old database setup");
			$this->modifyDatabase($conn);
		
			$this->conn->println("Database Modifications Ends......");
			$this->conn->println("Database Migration from Old Database to the Current Database has been Finished.");
		}
		else
		{
			//Drop the current(latest) Database
			$this->conn->println("Going to Restore the current Database");
			$this->conn->println("Going to Drop the current Database");
			$this->dropDatabase($conn,$new_dbname);

			//Create the new current(latest) Database
			$this->conn->println("Going to Create the current Database");
			$this->createDatabase($conn,$new_dbname);

			//Reload the new db dump and quit
			$this->conn->println("Going to apply the new backup db dump");
			$this->applyDumpData($new_host_name,$new_mysql_port,$new_mysql_username,$new_mysql_password,$new_dbname,$new_dump_file);

			//Return to Step1
			echo '<br><font color="red"><b>Dump could not be applied correctly. so your previous database restored.</b></font>';
			include("modules/Migration/MigrationStep1.php");
		}

		//Now we should recalculate the user and sharing privileges
		RecalculateSharingRules();
	}

}



?>
