<?php
/*+*******************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/
/* 
 * During migration config.inc.php might not be populated.
 * We will create this instance later based on the configuration file as later binding.
 */
$adb = true; 
require_once('include/utils/utils.php');
require_once('include/logging.php');
$migrationlog = & LoggerManager::getLogger('MIGRATION');
require_once('include/db_backup/backup.php');

$log =& LoggerManager::getLogger('PLATFORM');
$completed = false;

session_start();

if($_REQUEST['migration_verify']=='true'){
session_start();

	$migration_array = array();
	if (isset($_REQUEST['source_path'])) $source_directory = $_REQUEST['source_path'];
	$tmp = strlen($source_directory);
	if(!empty($source_directory)){
		if($source_directory[$tmp-1]!= "/" || $source_directory[$tmp-1]!= "\\"){
			$source_directory .= "/";
		}
		$migration_array['source_directory'] = $source_directory;
	}
	if (isset($_REQUEST['db_name'])){ 
		$migration_array['db_name'] = vtlib_purify($_REQUEST['db_name']);
	}
	if (isset($_REQUEST['db_drop_tables'])){
		$migration_array['db_drop_tables'] = vtlib_purify($_REQUEST['db_drop_tables']);
	}
	if (isset($_REQUEST['root_directory'])) {
		$migration_array['root_directory'] = vtlib_purify($_REQUEST['root_directory']);
	}
	if (isset($_REQUEST['site_URL'])) {
		$migration_array['site_URL'] = vtlib_purify($_REQUEST['site_URL']);
	}
	if (isset($_REQUEST['check_createdb'])){ 
		$migration_array['check_createdb'] = vtlib_purify($_REQUEST['check_createdb']);
	}
	if (isset($_REQUEST['root_user'])){ 
		$migration_array['root_user'] = vtlib_purify($_REQUEST['root_user']);
	}
	if (isset($_REQUEST['root_password'])){ 
		$migration_array['root_password'] = vtlib_purify($_REQUEST['root_password']);
	}
	if (isset($_REQUEST['create_utf8_db'])){ 
		$migration_array['create_utf8_db'] = 'true';
	}
	if (isset($_REQUEST['user_name'])){ 
		$migration_array['user_name'] = vtlib_purify($_REQUEST['user_name']);
	}
	if (isset($_REQUEST['user_pwd'])){ 
		$migration_array['user_pwd'] = vtlib_purify($_REQUEST['user_pwd']);
	}
	if (isset($_REQUEST['old_version'])){ 
		$migration_array['old_version'] = vtlib_purify($_REQUEST['old_version']);
	}
	if (isset($_REQUEST['cache_dir'])){ 
		$migration_array['cache_dir'] = vtlib_purify($_REQUEST['cache_dir']);
	}
	if (isset($_REQUEST['check_dbbackup'])){ 
		$migration_array['check_dbbackup'] = vtlib_purify($_REQUEST['check_dbbackup']);
	}
	if (isset($_REQUEST['selected_modules'])){ 
		$migration_array['selected_modules'] = vtlib_purify($_REQUEST['selected_modules']);
	}
	$_SESSION['migration_info'] = $migration_array;
	vtiger_MigrationVerify::process();
} 
if($_REQUEST['migration_start']=='true' && $_SESSION['authentication_key']==$_REQUEST['auth_key']){
	vtiger_DatabaseMigration::process();
}

class vtiger_MigrationVerify{
	function process(){
		set_time_limit(0);//ADDED TO AVOID UNEXPECTED TIME OUT WHILE MIGRATING
		if (isset($_SESSION['migration_info']['source_directory'])) $source_directory = $_SESSION['migration_info']['source_directory'];
		if(is_dir($source_directory)){
			if(!is_file($source_directory."config.inc.php")){
				echo "NO_CONFIG_FILE";
				return false;
			}
			if(!is_dir($source_directory."user_privileges")){
				echo "NO_USER_PRIV_DIR";
				return false;
			}
			if(!is_dir($source_directory."storage")){
				echo "NO_STORAGE_DIR";
				return false;
			}
		} else {
			echo "NO_SOURCE_DIR";
			return false;
		}
		$selected_modules = $_SESSION['migration_info']['selected_modules'];
		require_once($source_directory."config.inc.php");
		$old_db_name = $dbconfig['db_name'];
		$db_hostname = $dbconfig['db_server'].$dbconfig['db_port'];
		$db_username = $dbconfig['db_username'];
		$db_password = $dbconfig['db_password'];
		$db_type = $dbconfig['db_type'];
		
		if (isset($_SESSION['migration_info']['db_name'])) $db_name= $_SESSION['migration_info']['db_name'];
		if (isset($_SESSION['migration_info']['db_drop_tables'])) $db_drop_tables = $_SESSION['migration_info']['db_drop_tables'];
		if (isset($_SESSION['migration_info']['root_directory'])) $root_directory = $_SESSION['migration_info']['root_directory'];
		if (isset($_SESSION['migration_info']['site_URL'])) $site_URL = $_SESSION['migration_info']['site_URL'];
		if (isset($_SESSION['migration_info']['check_createdb'])) $check_createdb = $_SESSION['migration_info']['check_createdb'];
		if (isset($_SESSION['migration_info']['root_user'])) $root_user = $_SESSION['migration_info']['root_user'];
		if (isset($_SESSION['migration_info']['root_password'])) $root_password = $_SESSION['migration_info']['root_password'];
		if (isset($_SESSION['migration_info']['create_utf8_db'])) $create_utf8_db = 'true';
		if (isset($_SESSION['migration_info']['user_name'])) $user_name = $_SESSION['migration_info']['user_name'];
		if (isset($_SESSION['migration_info']['user_pwd'])) $user_pwd = $_SESSION['migration_info']['user_pwd'];
		if (isset($_SESSION['migration_info']['old_version'])) $source_version = $_SESSION['migration_info']['old_version'];
		if (isset($_SESSION['migration_info']['check_dbbackup'])) $check_dbbackup = $_SESSION['migration_info']['check_dbbackup'];
		if (isset($_SESSION['migration_info']['cache_dir'])) $cache_dir= $_SESSION['migration_info']['cache_dir'];
		else $cache_dir = "cache/";

		$db_type_status = false; // is there a db type?
		$db_server_status = false; // does the db server connection exist?
		$db_creation_failed = false; // did we try to create a database and fail?
		$db_exist_status = false; // does the database exist?
		$old_db_exist_status = false; // does the old database exist?
		$db_utf8_support = false; // does the database support utf8?
		$vt_charset = ''; // set it based on the database charset support
		$next = false; // allow installation to continue
		
		require_once('include/DatabaseUtil.php');
		//Checking for database connection parameters and copying old database into new database
		if($db_type)
		{
			$conn = &NewADOConnection($db_type);
			$db_type_status = true;
			if(@$conn->Connect($db_hostname,$db_username,$db_password)) {
				$db_server_status = true;
		
				if($db_type=='mysql') {
					$mysql_conn = mysql_connect($db_hostname,$db_username,$db_password);
					$version = explode('-',mysql_get_server_info($mysql_conn));
					$mysql_server_version=$version[0];
					mysql_close($mysql_conn);
				}
		
				// test the connection to the old database
				$olddb_conn = &NewADOConnection($db_type);
				if(@$olddb_conn->Connect($db_hostname, $db_username, $db_password, $old_db_name))
				{
					$old_db_exist_status = true;
					
					if(authenticate_user($user_name,$user_pwd)==true){
						$is_admin = true;
		
						if(isset($check_dbbackup) && $check_dbbackup == 'On'){
							$backup_DBFileName = $old_db_name."_".date("Ymd_His").".sql";
							$dbdump = new DatabaseDump($db_hostname.$db_port, $db_username, $db_password);
							$dumpfile = 'backup/'.$backup_DBFileName;
							$dbdump->save($old_db_name, $dumpfile) ;
						}
						if(isset($check_createdb) && $check_createdb == 'On') {
							// drop the current database if it exists
							// create the new database
							$db_creation_failed = true;
							$createdb_conn = &NewADOConnection($db_type);
							if(@$createdb_conn->Connect($db_hostname, $root_user, $root_password)) {
								mysql_query("drop database IF EXISTS ".$db_name);
								$create_query = "create database ".$db_name;
								// TODO: MySQL version less than 4.1.2 does not suppot UTF-8, a check here is required for it.
								if($create_utf8_db == 'true') { 
									if($db_type=='mysql')
										$create_query .= " default character set utf8 default collate utf8_general_ci"; 
									$db_utf8_support = true;
								}
								
								if($createdb_conn->Execute($create_query)) {
									$db_creation_failed = false;
								}
								$createdb_conn->Close();
							}
		
							// test the connection to the database
							$db_conn = &NewADOConnection($db_type);
							if(@$db_conn->Connect($db_hostname, $db_username, $db_password, $db_name)) {
								$db_exist_status = true;
								if(!$db_utf8_support) {
									// Check if the database that we are going to use supports UTF-8
									$db_utf8_support = check_db_utf8_support($conn);
								}
								$file_size = filesize($dumpfile);
									//Will take effect only if PHP safe-mode is Off
								if($file_size < 50*1024*1024){
									ini_set('memory_limit',2*$file_size);
								} else {
									ini_set('memory_limit','100M');
								}
								$fileHandle = fopen($dumpfile,'r');
								flush();
								ob_flush();
								while(!feof($fileHandle )){
									$line = fgets($fileHandle);
									$line = trim($line);
									flush();
									ob_flush();
									if(empty($line)) continue;
									if(stripos($line, '--') === 0) {
										// Ignore comments
									} else {
										$db_conn->Execute($line);
									}
								}
								$db_conn->Close();
								fclose($fileHandle );
				 			}
						} else {
							$db_conn = &NewADOConnection($db_type);
							if(@$db_conn->Connect($db_hostname, $db_username, $db_password, $db_name)) {
								$db_exist_status = true;
								if(!$db_utf8_support) {
									// Check if the database that we are going to use supports UTF-8
									$db_utf8_support = check_db_utf8_support($conn);
								}
								$db_conn->Close();
				 			}
							
						}
						if($source_directory!=$root_directory){
							@get_files_from_folder($source_directory."user_privileges/",$root_directory."user_privileges/",
								// Force copy these files. 
								Array($source_directory."user_privileges/default_module_view.php") 
							);	
							@get_files_from_folder($source_directory."storage/",$root_directory."storage/");
						}	
					}
					else{
						echo 'NOT_VALID_USER';
						return false;
					}
					$olddb_conn->Close();
				}			
			}
			$conn->Close();
		}
		// Update vtiger charset to use
		$vt_charset = ($db_utf8_support)? "UTF-8" : "ISO-8859-1";
		
		if(!$db_type_status || !$db_server_status)
		{
			$error_msg = 'ERR - Unable to connect to database Server. Invalid mySQL Connection Parameters specified';
			$error_msg_info = 'This may be due to the following reasons:<br>
					-  specified database user, password, hostname, database type, or port is invalid. <a href="http://www.vtiger.com/products/crm/help/5.1.0/vtiger_CRM_Database_Hostname.pdf" target="_blank">More Information</a><BR>
					-  specified database user does not have access to connect to the database server from the host';
		}
		elseif($db_type == 'mysql' && $mysql_server_version < '4.1')
		{
			$error_msg = 'ERR - MySQL version '.$mysql_server_version.' is not supported, kindly connect to MySQL 4.1.x or above';
		}
		elseif($db_creation_failed)
		{
			$error_msg = 'ERR - Unable to Create Database '.$db_name;
			$error_msg_info = 'Message: The database User "'. $root_user .'" doesn\'t have permission to Create database. Try changing the Database settings';
		}
		elseif(!$old_db_exist_status)
		{
			$error_msg = 'ERR - The Database "'.$old_db_name.'" is not found. Provide the correct database name';
		}
		elseif(!$db_exist_status)
		{
			$error_msg = 'ERR - The Database "'.$db_name.'" is not found.Try changing the Database settings';
		}
		else
		{
			$next = true;
		}
		if($next != true){
			echo $error_msg."\n".$error_msg_info;
			return false;
		}
		
		//Writing to Config file
		if (is_file('config.inc.php'))
			$is_writable = is_writable('config.inc.php');
		else
			$is_writable = is_writable('.');
		
		$dbtype=$db_type;
		$host=$db_hostname;
		$dbname=$db_name;
		$username=$db_username;
		$passwd=$db_password;
		
		/* open template configuration file read only */
		$templateFilename = 'config.template.php';
		$templateHandle = fopen($templateFilename, "r");
		if($templateHandle) {
			/* open include configuration file write only */
			$includeFilename = 'config.inc.php';
			$includeHandle = fopen($includeFilename, "w");
			if($includeHandle) {
				while (!feof($templateHandle)) {
				$buffer = fgets($templateHandle);
		
				/* replace _DBC_ variable */
				$buffer = str_replace( "_DBC_SERVER_", $dbconfig['db_server'], $buffer);
				$buffer = str_replace( ":_DBC_PORT_", $dbconfig['db_port'], $buffer);
				$buffer = str_replace( "_DBC_USER_", $db_username, $buffer);
				$buffer = str_replace( "_DBC_PASS_", $db_password, $buffer);
				$buffer = str_replace( "_DBC_NAME_", $db_name, $buffer);
				$buffer = str_replace( "_DBC_TYPE_", $db_type, $buffer);
		
				$buffer = str_replace( "_SITE_URL_", $site_URL, $buffer);
		
				/* replace dir variable */
				$buffer = str_replace( "_VT_ROOTDIR_", $root_directory, $buffer);
				$buffer = str_replace( "_VT_CACHEDIR_", $cache_dir, $buffer);
				$buffer = str_replace( "_VT_TMPDIR_", $cache_dir."images/", $buffer);
				$buffer = str_replace( "_VT_UPLOADDIR_", $cache_dir."upload/", $buffer);
				$buffer = str_replace( "_DB_STAT_", "true", $buffer);
		
					/* replace charset variable */
					$buffer = str_replace( "_VT_CHARSET_", $vt_charset, $buffer);
		
				/* replace master currency variable */
				$buffer = str_replace( "_MASTER_CURRENCY_", $currency_name, $buffer);
		
				/* replace the application unique key variable */
				$buffer = str_replace( "_VT_APP_UNIQKEY_", md5($root_directory), $buffer);
				/* replace support email variable */
				$buffer = str_replace( "_USER_SUPPORT_EMAIL_", $admin_email, $buffer);
		
				fwrite($includeHandle, $buffer);
				}
		
			fclose($includeHandle);
			}
		
		fclose($templateHandle);
		}
		  
		if (!($templateHandle && $includeHandle)) {
			echo "FAILURE: Writing to config file. check permissions. ";
			return false;
		} else {
			$_SESSION['authentication_key'] = md5(microtime());
			return true;
		}
		//CONFIG FILE CREATION ENDS
	}
}

class vtiger_DatabaseMigration{
	function process(){
		set_time_limit(0);//ADDED TO AVOID UNEXPECTED TIME OUT WHILE MIGRATING
		/* ----------------------------------------------------------Migration Starts HERE ------------------------------------------------------------------------*/
		require_once (dirname(__FILE__) . '/config.inc.php');
		
		global $dbconfig;
		$dbtype		= $dbconfig['db_type'];
		$host		= $dbconfig['db_server'].$dbconfig['db_port'];
		$dbname		= $dbconfig['db_name'];
		$username	= $dbconfig['db_username'];
		$passwd		= $dbconfig['db_password'];
				
		global $adb,$migrationlog;
		$adb = new PearDatabase($dbtype,$host,$dbname,$username,$passwd);

		$query = " ALTER DATABASE ".$dbname." DEFAULT CHARACTER SET utf8";
		$adb->query($query);
		
		$source_directory = $_SESSION['migration_info']['source_directory'];
		if(file_exists($source_directory.'user_privileges/CustomInvoiceNo.php'))
			require_once($source_directory.'user_privileges/CustomInvoiceNo.php');
			$versions_non_utf8 = array("50","501","502","503rc2","503","504rc");
			$php_max_execution_time = 600;
			set_time_limit($php_max_execution_time);
		
			$migrationlog =& LoggerManager::getLogger('MIGRATION');
			if (isset($_SESSION['migration_info']['old_version'])) $source_version = $_SESSION['migration_info']['old_version'];
			if(!isset($source_version) || empty($source_version))
			{
				//If source version is not set then we cannot proceed
				echo "<br> Source Version is not set. Please check vtigerversion.php and contiune the Patch Process";
				exit;
			}
		
			$reach = 0;
			include(dirname(__FILE__)."/modules/Migration/versions.php");
			foreach($versions as $version => $label)
			{
				if($version == $source_version || $reach == 1)
				{
					$reach = 1;
					$temp[] = $version;
				}
			}
			$temp[] = intval($current_version);
			//Here we have to decide the database object to which we have to run the migration queries
			//For options 1 and 2 we need to execute the queries in current database ie., adb object
			//But for option 3, we have to run the queries in given 4.2.3 database ie., conn object
			//This session variable should be used in all patch files(which contains the queries) so that based on the option selected the queries will be executed in the corresponding database. ie., in all patch files we have to assign this session object to adb and conn objects
		global $adb, $dbname;
		
		$_SESSION['adodb_current_object'] = $adb;
		echo '<table width="98%" border="1px" cellpadding="3" cellspacing="0" height="100%">';
		echo "<tr><td colspan='2'><b>Going to apply the Database Changes...</b></td><tr>";
		//echo '<table width="98%" cellpadding="3" cellspacing="0" border="0" class="MigInfo">';
	
		for($patch_count=0;$patch_count<count($temp);$patch_count++)
		{
			//Here we have to include all the files (all db differences for each release will be included)
			$filename = "modules/Migration/DBChanges/".$temp[$patch_count]."_to_".$temp[$patch_count+1].".php";
			$empty_tag = "<tr><td colspan='2'>&nbsp;</td></tr>";
			$start_tag = "<tr><td colspan='2'><b><font color='red'>&nbsp;";
			$end_tag = "</font></b></td></tr>";
	
			if(is_file($filename))
			{
				echo $empty_tag.$start_tag.$temp[$patch_count]." ==> ".$temp[$patch_count+1]." Database changes -- Starts.".$end_tag;
		
				include($filename);//include the file which contains the corresponding db changes
		
				echo $start_tag.$temp[$patch_count]." ==> ".$temp[$patch_count+1]." Database changes -- Ends.".$end_tag;
			}
		}
	
		//Here we have to update the version in table. so that when we do migration next time we will get the version
		$res = $adb->query("select * from vtiger_version");
		require_once(dirname(__FILE__)."/vtigerversion.php");
		global $vtiger_current_version;
		if($adb->num_rows($res))
		{
			$res = ExecuteQuery("update vtiger_version set old_version='$versions[$source_version]',current_version='$vtiger_current_version'");
			$completed = true;
			session_destroy();
		}
		else
		{
			ExecuteQuery("insert into vtiger_version (id, old_version, current_version) values ('', '$versions[$source_version]', '$vtiger_current_version');");
			$completed = true;
			session_destroy();
		}
		echo '</table><br><br>';
		if($completed==true){
			echo "<script type='text/javascript'>window.parent.Migration_Complete();</script>";
		}
		
		create_tab_data_file();
		create_parenttab_data_file();
		if($completed ==true){
			return true; 
		}

	}
}

function authenticate_user($user_name,$user_password){
	$salt = substr($user_name, 0, 2);
	
	$sql = mysql_query("SELECT * from vtiger_users WHERE user_name = '$user_name'");
	$result = mysql_fetch_array($sql);
	$crypt_type = $result['crypt_type'];
	if($crypt_type == 'MD5') {
		$salt = '$1$' . $salt . '$';
	} else if($crypt_type == 'BLOWFISH') {
		$salt = '$2$' . $salt . '$';
	}
	$encrypted_password = crypt($user_password, $salt);	
	$password =  $result['user_password'];
	$status =  $result['status'];
	$is_admin =  $result['is_admin'];
	
	if(!($password == $encrypted_password) || !($status=='Active') || !($is_admin=='on')){
		return false;
	}
	return true;
}

function get_files_from_folder($source, $dest, $forcecopy=false) {
	if(!$forcecopy) $forcecopy = Array();
	
	if ($handle = opendir($source)) {
		while (false != ($file = readdir($handle))) {
			if (is_file($source.$file)) {
				if(!file_exists($dest.$file) || in_array($source.$file, $forcecopy)){
					$file_handle = fopen($dest.$file,'w');
					fclose($file_handle);
					copy($source.$file, $dest.$file);
				}
			} elseif ($file != '.' && $file != '..' && is_dir($source.$file)) {
				mkdir($dest.$file.'/',0777);
				get_files_from_folder($source.$file.'/', $dest.$file.'/');
			}
		}
	}
	@closedir($handle);
}

//Function used to execute the query and display the success/failure of the query
function ExecuteQuery($query)
{
	global $adb;
	global $conn, $query_count, $success_query_count, $failure_query_count, $success_query_array, $failure_query_array;
        global $migrationlog;

	ob_flush();
	flush();
	//For third option migration we have to use the $conn object because the queries should be executed in 4.2.3 db
	$status = $adb->query($query);
	$query_count++;
	if(is_object($status))
	{
		echo '
			<tr width="100%">
				<td width="10%"><font color="green"> SUCCESS </font></td>
				<td width="80%">'.$query.'</td>
			</tr>';
		$success_query_array[$success_query_count++] = $query;
		$migrationlog->debug("Query Success ==> $query");
	}
	else
	{
		echo '
			<tr width="100%">
					<td width="5%"><font color="red"> FAILURE </font></td>
				<td width="70%">'.$query.'</td>
			</tr>';
		$failure_query_array[$failure_query_count++] = $query;
		$migrationlog->debug("Query Failed ==> $query \n Error is ==> [".$adb->database->ErrorNo()."]".$adb->database->ErrorMsg());
	}
}

?>