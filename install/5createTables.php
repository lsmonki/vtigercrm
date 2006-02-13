<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is: SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/install/5createTables.php,v 1.58 2005/04/19 16:57:08 ray Exp $
 * Description:  Executes a step in the installation process.
 ********************************************************************************/
set_time_limit(600);
if (isset($_REQUEST['db_name'])) $db_name  				= $_REQUEST['db_name'];
if (isset($_REQUEST['db_drop_tables'])) $db_drop_tables 	= $_REQUEST['db_drop_tables'];
if (isset($_REQUEST['db_create'])) $db_create 			= $_REQUEST['db_create'];
if (isset($_REQUEST['db_populate'])) $db_populate		= $_REQUEST['db_populate'];
if (isset($_REQUEST['admin_email'])) $admin_email		= $_REQUEST['admin_email'];
if (isset($_REQUEST['admin_password'])) $admin_password	= $_REQUEST['admin_password'];

$new_tables = 0;

require_once('include/database/PearDatabase.php');
require_once('include/logging.php');
require_once('modules/Leads/Lead.php');
require_once('modules/Settings/FileStorage.php');
//require_once('modules/imports/Headers.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Accounts/Account.php');
require_once('modules/Potentials/Opportunity.php');
require_once('modules/Activities/Activity.php');
require_once('modules/Notes/Note.php');
require_once('modules/Emails/Email.php');
require_once('modules/Users/User.php');
require_once('modules/Import/SugarFile.php');
require_once('modules/Import/ImportMap.php');
require_once('modules/Import/UsersLastImport.php');
require_once('modules/Users/TabMenu.php');
require_once('modules/Users/LoginHistory.php');
require_once('modules/Settings/FileStorage.php');
require_once('data/Tracker.php');
require_once('include/utils.php');
require_once('modules/Users/Security.php');
// load up the config_override.php file.  This is used to provide default user settings
if (is_file("config_override.php")) {
	require_once("config_override.php");
}
$db = new PearDatabase();
$log =& LoggerManager::getLogger('create_table');

function createSchemaTable () {
	global $log;
	// create the schema tables
	$query = "CREATE TABLE modules (id int(11) NOT NULL auto_increment, name text,PRIMARY KEY ( ID ))";

	$this->query($query);
}


function createObjectTable () {
	global $log;
	// create the object tables
	$query = "CREATE TABLE objects (
		module_id int(11),
		name text,
		PRIMARY KEY ( module_id, name ))";

	$this->query($query);
}

function createAttributesTable () {
	global $log;
	// create the attributes tables
	$query = "CREATE TABLE attributes (
		module_id int(11),
		object_name text,
		name text,

		PRIMARY KEY ( module_id, object_name ))";
	// fk module_id, object_name -> object table.

	$this->query($query);
}

function createLabelsTable () {
	global $log;
	// create the translation tables
	$query = "CREATE TABLE labels (
		module_id int(11),
		name text,
		value text,
		value_long text,
		value_popup text,
		PRIMARY KEY ( module_id, name ))";

	$this->query($query);
}

//Drop old tables if table exists and told to drop it
function drop_table_install(&$focus)
{

        global $log, $db;

        $result = $db->requireSingleResult("SHOW TABLES LIKE '".$focus->table_name."'");
        if (!empty($result)) {



                $focus->drop_tables();
                $log->info("Dropped old ".$focus->table_name." table.");
                return 1;

        }
        else
        {
                $log->info("Did not need to drop old ".$focus->table_name." table.  It doesn't exist.");
                return 0;
        }
}

// Creating new tables if they don't exist.
function create_table_install(&$focus)
{

        global $log, $db;
        $result = $db->query("SHOW TABLES LIKE '".$focus->table_name."'");
        if ($db->getRowCount($result) == 0)
        {
                $focus->create_tables();
                $log->info("Created ".$focus->table_name." table.");
                return 1;
        }
        else
        {
                $log->info("Table ".$focus->table_name." already exists.");
                return 0;
        }
}

function create_default_users()
{
        global $log, $db;
        global $admin_email;
        global $admin_password;
        global $create_default_user;
        global $default_user_name;
        global $default_password;
        global $default_user_is_admin;

        //Create default admin user
    	$user = new User();
        $user->last_name = 'Administrator';
        $user->user_name = 'admin';
        $user->status = 'Active';
        $user->is_admin = 'on';
        $user->user_password = $user->encrypt_password($admin_password);
        $user->tz = 'Europe/Berlin';
        $user->holidays = 'de,en_uk,fr,it,us,';
        $user->workdays = '0,1,2,3,4,5,6,';
        $user->weekstart = '1';
        $user->namedays = '';
	$user->date_format = 'yyyy-mm-dd';
        $user->email = $admin_email;
        $user->save();

        // We need to change the admin user to a fixed id of 1.
        //$query = "update users set id='1' where user_name='$user->user_name'";
        //$result = $db->query($query, true, "Error updating admin user ID: ");

        $log->info("Created ".$user->table_name." table. for user $user->id");

        if($create_default_user)
        {
                $default_user = new User();
                $default_user->last_name = $default_user_name;
                $default_user->user_name = $default_user_name;
                $default_user->status = 'Active';
			if (isset($default_user_is_admin) && $default_user_is_admin) $default_user->is_admin = 'on';
                $default_user->user_password = $default_user->encrypt_password($default_password);
        	$default_user->tz = 'Europe/Berlin';
	        $default_user->holidays = 'de,en_uk,fr,it,us,';
        	$default_user->workdays = '0,1,2,3,4,5,6,';
	        $default_user->weekstart = '1';
        	$default_user->namedays = '';
                $default_user->save();

        }

	//Inserting values into user2role table
	$role_query = "select roleid from role where name='administrator'";
	$db->database->SetFetchMode(ADODB_FETCH_ASSOC);
	$role_result = $db->query($role_query);
	$role_id = $db->query_result($role_result,0,"roleid");

	$sql_stmt1 = "insert into user2role values(".$user->id.",".$role_id.")";
	$db->query($sql_stmt1) or die($app_strings['ERR_CREATING_TABLE'].mysql_error());

}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>vtiger CRM 4.2 Installer: Step 5</title>
<link rel="stylesheet" href="install/install.css" type="text/css" />
</head>
<body leftMargin="0" topMargin="0" marginheight="0" marginwidth="0">
<table width="75%" border="0" cellpadding="3" cellspacing="0" align="center" style="border-bottom: 1px dotted #CCCCCC;"><tbody>
  <tr>
      <td align="left"><a href="http://www.vtiger.com" target="_blank" title="vtiger CRM"><IMG alt="vtiger CRM" border="0" src="include/images/vtiger_crmlogo.gif"/></a></td>
      <td align="right"><h2>Step 5 of 5</h2></td>
      <td align="right"><IMG alt="vtiger CRM" border="0" src="include/images/spacer.gif" width="10" height="1"/></td>
    </tr>
</tbody></table>
<table width="75%" align="center" cellpadding="10" cellspacing="0" border="0"><tbody>

   <tr>
      <td width="100%">
		<table width="100%" cellpadding="0" cellspacing="0" border="0"><tbody><tr>
			  <td>
			   <table width="100%" cellpadding="0" cellspacing="0" border="0"><tbody><tr>

				<td><h3>Create Database Tables</h3></td>
				<td width="74%"><hr width="100%"></td>

				</tr></tbody></table>
			  </td>

			  </tr>
		</tbody></table>
	  </td>
          </tr>
          <tr>
            <td>
<?php
$startTime = microtime();

$modules = array(
 "Contact"
,"Account"
,"potential"
,"Lead"
,"Tab"
,"Security"
,"LoginHistory"
,"FileStorage"
,"User"
,"Tracker"
,"Activity"
,"Note"
,"Email"
 ,"SugarFile"
,"ImportMap"
,"UsersLastImport"
);

$focus = 0;

// Tables creation

// temporary
require_once('config.php');

$success = $db->createTables("adodb/DatabaseSchema.xml");

// TODO HTML
if($success==0)
{
	print("Tables not created");
}
else if($success==1)
{
	print("Tables partially created");
}
else
{
	print("Tables Successfully created");
}


foreach ( $modules as $module )
{
        $focus = new $module();

        /*if ($db_drop_tables == true )
        {
                $existed = drop_table_install($focus);

                if ($existed)
                {
                        echo "<font color=red>Dropped existing ".$focus->table_name." table</font><BR>\n";
                }
                else
                {
                        echo "<font color=green>Table ".$focus->table_name." does not exist</font><BR>\n";
                }
        }

        $success = create_table_install($focus);

        if ( $success)
        {
                echo "<font color=green>Created new ".$focus->table_name." table</font><BR>\n";
                if ( $module == "User")
                {
                        $new_tables = 1;
                }
        }
        else
        {
		echo "Table ".$focus->table_name." already exists<BR>\n";
        }*/

	$focus->create_tables(); // inserts only rows

}
/*
if ($new_tables)
{
        create_default_users();
}*/

/*if($success==2)
{*/
	create_default_users();
//}

//Populating users table
$uid = $db->getUniqueID("users");
$sql_stmt1 = "insert into users(id,user_name,user_password,last_name,email1,date_format) values(".$uid.",'standarduser','stX/AHHNK/Gkw','standarduser','standarduser@standard.user.com','yyyy-mm-dd')";
$db->query($sql_stmt1) or die($app_strings['ERR_CREATING_TABLE'].mysql_error());


//$sql_stmt1 = "insert into user2role values(1,1)";
//$db->query($sql_stmt1) or die($app_strings['ERR_CREATING_TABLE'].mysql_error());


$role_query = "select roleid from role where name='standard_user'";
$db->database->SetFetchMode(ADODB_FETCH_ASSOC);
$role_result = $db->query($role_query);
$role_id = $db->query_result($role_result,0,"roleid");


$sql_stmt2 = "insert into user2role values(".$uid.",".$role_id.")";
$db->query($sql_stmt2) or die($app_strings['ERR_CREATING_TABLE'].mysql_error());


//Create and populate combo tables
require_once('include/PopulateComboValues.php');
$combo = new PopulateComboValues();
$combo->create_tables();

//Create and populate Custom Field tables;
require_once('include/PopulateCustomFieldTables.php');
create_custom_field_tables();

//Default report population//
require_once('modules/Reports/PopulateReports.php');

//Default customview population//
require_once('modules/CustomView/PopulateCustomView.php');

//Creating and Populating PHPBB tables and data
//require_once('include/PopulatePhpBBtables.php');
//create_populate_phpbb();

// populating the db with seed data
if ($db_populate)
{
        echo "Populating seed data into $db_name";
        include("install/populateSeedData.php");
        echo "...<font color=\"00CC00\">done</font><BR><P>\n";
}

//populating forums data
global $log, $db;
/*
$db->query("update phpbb_config set config_value='".$admin_email."' where config_name='board_email'");

$db->query("update phpbb_config set config_value='modules/MessageBoard/images/smiles' where config_name='smilies_path'");

$db->query("update phpbb_config set config_value='".$server_name."' where config_name='server_name'");

$db->query("update phpbb_config set config_value='".$server_port."' where config_name='server_port'");


$db->query("update phpbb_config set config_value='modules/MessageBoard' where config_name='script_path'");

$curr_time=time();
$db->query("insert phpbb_config values('board_startdate','".$curr_time."')");

$db->query("insert phpbb_config values('default_lang', 'english')");

*/
$endTime = microtime();

$deltaTime = microtime_diff($startTime, $endTime);

function populatePermissions4StandardUser()
{

  mysql_query("insert into role2permission(roleid,permissionid,module,module_action,action_permission,module_permission,description) values (2,'','Leads','EditView',1,1,'')");
  mysql_query("insert into role2permission(roleid,permissionid,module,module_action,action_permission,module_permission,description) values (2,'','Leads','Delete',1,1,'')");
  mysql_query("insert into role2permission(roleid,permissionid,module,module_action,action_permission,module_permission,description) values (2,'','Leads','index',1,1,'')");



  mysql_query("insert into role2permission(roleid,permissionid,module,module_action,action_permission,module_permission,description) values (2,'','Accounts','EditView',1,1,'')");
  mysql_query("insert into role2permission(roleid,permissionid,module,module_action,action_permission,module_permission,description) values (2,'','Accounts','Delete',1,1,'')");
mysql_query("insert into role2permission(roleid,permissionid,module,module_action,action_permission,module_permission,description) values (2,'','Accounts','index',1,1,'')");

 mysql_query("insert into role2permission(roleid,permissionid,module,module_action,action_permission,module_permission,description) values (2,'','Contacts','EditView',1,1,'')");
 mysql_query("insert into role2permission(roleid,permissionid,module,module_action,action_permission,module_permission,description) values (2,'','Contacts','Delete',1,1,'')");
mysql_query("insert into role2permission(roleid,permissionid,module,module_action,action_permission,module_permission,description) values (2,'','Contacts','index',1,1,'')");

 mysql_query("insert into role2permission(roleid,permissionid,module,module_action,action_permission,module_permission,description) values (2,'','Opportunities','EditView',1,1,'')");
 mysql_query("insert into role2permission(roleid,permissionid,module,module_action,action_permission,module_permission,description) values (2,'','Opportunities','Delete',1,1,'')");
 mysql_query("insert into role2permission(roleid,permissionid,module,module_action,action_permission,module_permission,description) values (2,'','Opportunities','index',1,1,'')");

 mysql_query("insert into role2permission(roleid,permissionid,module,module_action,action_permission,module_permission,description) values (2,'','Calls','EditView',1,1,'')");
 mysql_query("insert into role2permission(roleid,permissionid,module,module_action,action_permission,module_permission,description) values (2,'','Calls','Delete',1,1,'')");
 mysql_query("insert into role2permission(roleid,permissionid,module,module_action,action_permission,module_permission,description) values (2,'','Calls','index',1,1,'')");

		mysql_query("insert into role2permission(roleid,permissionid,module,module_action,action_permission,module_permission,description) values (2,'','Emails','EditView',1,1,'')");
		mysql_query("insert into role2permission(roleid,permissionid,module,module_action,action_permission,module_permission,description) values (2,'','Emails','Delete',1,1,'')");
                mysql_query("insert into role2permission(roleid,permissionid,module,module_action,action_permission,module_permission,description) values (2,'','Emails','index',1,1,'')");



		mysql_query("insert into role2permission(roleid,permissionid,module,module_action,action_permission,module_permission,description) values (2,'','Tasks','EditView',1,1,'')");
		mysql_query("insert into role2permission(roleid,permissionid,module,module_action,action_permission,module_permission,description) values (2,'','Tasks','Delete',1,1,'')");
                mysql_query("insert into role2permission(roleid,permissionid,module,module_action,action_permission,module_permission,description) values (2,'','Tasks','index',1,1,'')");

		mysql_query("insert into role2permission(roleid,permissionid,module,module_action,action_permission,module_permission,description) values (2,'','Notes','EditView',1,1,'')");
		mysql_query("insert into role2permission(roleid,permissionid,module,module_action,action_permission,module_permission,description) values (2,'','Notes','Delete',1,1,'')");
                mysql_query("insert into role2permission(roleid,permissionid,module,module_action,action_permission,module_permission,description) values (2,'','Notes','index',1,1,'')");


		mysql_query("insert into role2permission(roleid,permissionid,module,module_action,action_permission,module_permission,description) values (2,'','Meetings','EditView',1,1,'')");
		mysql_query("insert into role2permission(roleid,permissionid,module,module_action,action_permission,module_permission,description) values (2,'','Meetings','Delete',1,1,'')");

		mysql_query("insert into role2permission(roleid,permissionid,module,module_action,action_permission,module_permission,description) values (2,'','Cases','EditView',1,1,'')");
		mysql_query("insert into role2permission(roleid,permissionid,module,module_action,action_permission,module_permission,description) values (2,'','Cases','Delete',1,1,'')");
                mysql_query("insert into role2permission(roleid,permissionid,module,module_action,action_permission,module_permission,description) values (2,'','Cases','index',1,1,'')");

		mysql_query("insert into role2permission(roleid,permissionid,module,module_action,action_permission,module_permission,description) values (2,'','imports','fetchfile',0,1,'')");
		mysql_query("insert into role2permission(roleid,permissionid,module,module_action,action_permission,module_permission,description) values (2,'','Contacts','BusinessCard',0,1,'')");
		mysql_query("insert into role2permission(roleid,permissionid,module,module_action,action_permission,module_permission,description) values (2,'','Contacts','Import',0,1,'')");
		mysql_query("insert into role2permission(roleid,permissionid,module,module_action,action_permission,module_permission,description) values (2,'','Accounts','Import',0,1,'')");
		mysql_query("insert into role2permission(roleid,permissionid,module,module_action,action_permission,module_permission,description) values (2,'','Opportunities','Import',0,1,'')");


}

//populate Calendar data
//include("modules/Calendar/admin/scheme.php");

?>
The database tables are now set up.<HR></HR>
total time: <?php echo "$deltaTime"; ?> seconds.<BR />
</td></tr>
<tr><td><hr></td></tr>
<tr><td align=left><font color=green>Your system is now installed and configured for use.  You need to log in for the first time using the "admin" user name and the password you entered in step 2.</font></td></tr>
<tr><td align="right">
         <form action="index.php" method="post" name="form" id="form">
         <input type="hidden" name="default_user_name" value="admin">
         <input class="button" type="submit" name="next" value="Finish" />
         </form>
</td></tr>
</tbody></table></body></html>
