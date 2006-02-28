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
 require_once('data/Tracker.php');
 require_once('include/utils/utils.php');
 require_once('modules/Users/Security.php');
if (is_file("config_override.php")) {
	require_once("config_override.php");
}
 $db = new PearDatabase();
 $log =& LoggerManager::getLogger('create_table');

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
        //added by philip for default default admin emailid
	if($admin_email == '')
	$admin_email ="admin@administrator.com";
         $user->email = $admin_email;
         $user->save();

        // We need to change the admin user to a fixed id of 1.
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
	$role_query = "select roleid from role where rolename='administrator'";
	$db->database->SetFetchMode(ADODB_FETCH_ASSOC);
	$role_result = $db->query($role_query);
	$role_id = $db->query_result($role_result,0,"roleid");

	$sql_stmt1 = "insert into user2role values(".$user->id.",'".$role_id."')";
	$db->query($sql_stmt1) or die($app_strings['ERR_CREATING_TABLE'].mysql_error());

}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>vtiger CRM 5.0 Beta Installer: Step 5</title>
<link rel="stylesheet" href="install/install.css" type="text/css" />
<style type="text/css"><!--


.percents {
 background: #eeeeee;
 border: 1px solid #dddddd;
 margin-left: 260px;
 height: 20px;
 position:absolute;
 width:575px;
 z-index:10;
 left: 10px;
 top: 203px;
 text-align: center;
}

.blocks {
 background: #aaaaaa;
 border: 1px solid #a1a1a1;
 margin-left: 260px;
 height: 20px;
 width: 10px;
 position: absolute;
 z-index:11;
 left: 12px;
 top: 203px;
 filter: alpha(opacity=50);
 -moz-opacity: 0.5;
 opacity: 0.5;
 -khtml-opacity: .5
}

-->
</style>
</head>
<body leftMargin="0" topMargin="0" marginheight="0" marginwidth="0">


<table border=0 cellspacing=0 cellpadding=0 width=100%>
<tr>
	<td align=center>
	<br><br>
	<!--  Top Header -->
	<table border="0" cellspacing="0" cellpadding="0" width="80%" style="background:url(install/images/cwTopBg.gif) repeat-x;">
	<tr>
		<td><img src="install/images/cwTopLeft.gif" alt="vtiger CRM" title="vtiger CRM"></td>
		<td align=right><img src="install/images/cwTopRight.gif" alt="v5beta" title="v5beta"></td>
	</tr>
	</table>
	
	
	
	<!-- 5 of 5 header -->
	<table border="0" cellspacing="0" cellpadding="5" width="75%" class=small> 
	<tr>	
		<td valign=top><img src="install/images/cwIcoDB.gif" alt="Create Database Tables" title="Create Database Tables"></td>
		<td width=98% valign=top>
			<table border=0 cellspacing=0 cellpadding=0 width=100%>
			<tr>
				<td><img src="install/images/cwHdrVtConfWiz.gif" alt="vtiger CRM Configuration Wizard" title="vtiger CRM Configuration Wizard"></td>
				<td align=right><img src="install/images/cwStep5of5.gif" alt="Step 5 of 5" title="Step 5 of 5"></td>
			</tr>
			<tr>
				<td colspan=2><img src="install/images/cwHdrCrDbTables.gif" alt="Create Database Tables" title="Create Database Tables"></td>
			</tr>
			</table>
			<hr noshade size=1>
		</td>

	</tr>
	<tr>
		<td></td>
		<td>
		<!--- code -->
<?php
$startTime = microtime();

$modules = array(
"Security"
,"Contact"
,"Account"
,"potential"
,"Lead"
,"Tab"
,"LoginHistory"
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

/*if (ob_get_level() == 0) {
   ob_start();
}
echo str_pad('Loading... ',4096)."<br />\n";
for ($i = 0; $i < 48; $i++) {
   $d = $d + 11;
   $m=$d+10;
   //This div will show loading percents
   echo '<div class="percents">' . $i*2 . '%&nbsp;complete</div>';
   //This div will show progress bar
   echo '<div class="blocks" style="left: '.$d.'px">&nbsp;</div>';
   flush();
   ob_flush();
   sleep(1);
   ob_end_flush();
}*/
?>
<!--<div class="percents" style="z-index:12">Done.</div>-->
<?
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


 	$focus->create_tables(); // inserts only rows

}
	create_default_users();

//Populating users table
$uid = $db->getUniqueID("users");
$sql_stmt1 = "insert into users(id,user_name,user_password,last_name,email1,date_format) values(".$uid.",'standarduser','stX/AHHNK/Gkw','standarduser','standarduser@standard.user.com','yyyy-mm-dd')";
$db->query($sql_stmt1) or die($app_strings['ERR_CREATING_TABLE'].mysql_error());




$role_query = "select roleid from role where rolename='standard_user'";
$db->database->SetFetchMode(ADODB_FETCH_ASSOC);
$role_result = $db->query($role_query);
$role_id = $db->query_result($role_result,0,"roleid");


$sql_stmt2 = "insert into user2role values(".$uid.",'".$role_id."')";
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

// populating the db with seed data
if ($db_populate)
{
        echo "Populating seed data into $db_name";
        include("install/populateSeedData.php");
        echo "...<font color=\"00CC00\">done</font><BR><P>\n";
}

//populating forums data
global $log, $db;
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
        //this is to rename the installation file and folder so that no one destroys the setup
$renamefile = uniqid(rand(), true);

rename("install.php", $renamefile."install.php.txt");
rename("install/", $renamefile."install/");

//populate Calendar data


?>
		<br><br>
		
		<table borde=0 cellspacing=0 cellpadding=5 width=100% style="background-color:#EEFFEE; border:1px dashed #ccddcc;">
		<tr>
			<td align=center class=small>
			<b>The database tables are now set up.</b>
			<br>Total time taken: <?php echo "$deltaTime"; ?> seconds.
			<hr noshade size=1>
			<div style="width:100%;padding:10px; "align=left>
			<ul>
			<li>Your install.php file has been renamed in the format <?echo $renamefile;?>install.php.txt.
		<li>Your install folder too has been renamed in the format <?echo $renamefile;?>install/.  
			<li>Your system is now installed and configured for use.  
			<li>You need to log in for the first time using the "admin" user name and the password you entered in step 2.
			</ul>
			</div>

			</td>
		</tr>
		</table>
		
		</td></tr>
		<tr><td colspan=2 align="center">
				 <form action="index.php" method="post" name="form" id="form">
				 <input type="hidden" name="default_user_name" value="admin">
				 <input  type="image" src="install/images/cwBtnFinish.gif" name="next" value="Finish" />
				 </form>
		</td></tr>
		</table>		
							<br><br>
						<!-- Horizontal Shade -->
					<table border="0" cellspacing="0" cellpadding="0" width="75%" style="background:url(install/images/cwShadeBg.gif) repeat-x;">
					<tr>
						<td><img src="install/images/cwShadeLeft.gif"></td>
						<td align=right><img src="install/images/cwShadeRight.gif"></td>
					</tr>
					</table><br><br>

		<!-- code -->
		
		</td>
	</tr>
	</table>
	








</td>
</tr>
</table>
<!-- master table closes -->


</body></html>
