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
 * $Header:  vtiger_crm/sugarcrm/install/5createTables.php,v 1.15 2004/09/13 14:37:24 jack Exp $
 * Description:  Executes a step in the installation process.
 ********************************************************************************/

if (isset($_REQUEST['db_name'])) $db_name  				= $_REQUEST['db_name'];
if (isset($_REQUEST['db_drop_tables'])) $db_drop_tables 	= $_REQUEST['db_drop_tables'];
if (isset($_REQUEST['db_create'])) $db_create 			= $_REQUEST['db_create'];
if (isset($_REQUEST['db_populate'])) $db_populate		= $_REQUEST['db_populate'];
if (isset($_REQUEST['admin_email'])) $admin_email		= $_REQUEST['admin_email'];
if (isset($_REQUEST['admin_password'])) $admin_password	= $_REQUEST['admin_password'];

require_once('include/logging.php');
require_once('modules/Leads/Lead.php'); 
require_once('modules/Settings/FileStorage.php'); 
require_once('modules/imports/Headers.php'); 
require_once('modules/Contacts/Contact.php'); 
require_once('modules/Accounts/Account.php'); 
require_once('modules/Opportunities/Opportunity.php'); 
require_once('modules/Cases/Case.php'); 
require_once('modules/Tasks/Task.php'); 
require_once('modules/Notes/Note.php'); 
require_once('modules/Meetings/Meeting.php'); 
require_once('modules/Calls/Call.php'); 
require_once('modules/Emails/Email.php'); 
require_once('modules/Users/User.php'); 
require_once('modules/Users/TabMenu.php');
require_once('modules/Users/LoginHistory.php');
require_once('data/Tracker.php'); 
require_once('include/utils.php');

// load up the config_override.php file.  This is used to provide default user settings
if (is_file("config_override.php")) {
	require_once("config_override.php");
}

$log =& LoggerManager::getLogger('create_table');

function createSchemaTable () {
	global $log;
	// create the schema tables
	$query = "CREATE TABLE modules (id int(11) NOT NULL auto_increment,
				name text,
				PRIMARY KEY ( ID ))";

	$log->info($query);
	mysql_query($query);
}


function createObjectTable () {
	global $log;
	// create the object tables
	$query = "CREATE TABLE objects (
		module_id int(11),
		name text,
		PRIMARY KEY ( module_id, name ))";

	$log->info($query);
	mysql_query($query);
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

	$log->info($query);
	mysql_query($query);
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

	$log->info($query);
	mysql_query($query);
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>vtiger CRM Open Source Installer: Step 4</title>
<link rel="stylesheet" href="install/install.css" type="text/css" />
</head>
<body leftMargin="0" topMargin="0" marginheight="0" marginwidth="0">
<table width="100%" border="0" cellpadding="5" cellspacing="0"><tbody>
  <tr><td align="center"><a href="http://www.vtiger.com" target="_blank" title="vtiger CRM"><IMG alt="vtiger CRM" border="0" src="include/images/vtiger.jpg"/></a></td></tr>
</tbody></table>
<P></P>
<table align="center" border="0" cellpadding="2" cellspacing="2" border="1" width="60%"><tbody><tr> 
   <tr>
      <td width="100%">
		<table width=100% cellpadding="0" cellspacing="0" border="0"><tbody><tr>
			  <td>
			   <table cellpadding="0" cellspacing="0" border="0"><tbody><tr>
				<td class="formHeader" vAlign="top" align="left" height="20"> 
				 <IMG height="5" src="include/images/left_arc.gif" width="5" border="0"></td>
				<td class="formHeader" vAlign="middle" align="left" noWrap width="100%" height="20">Step 5: Create Database Tables</td>
				<td  class="formHeader" vAlign="top" align="right" height="20">
				  <IMG height="5" src="include/images/right_arc.gif" width="5" border="0"></td>
				</tr></tbody></table>
			  </td>
			  <td width="100%" align="right">&nbsp;</td>
			  </tr><tr>
			  <td colspan="2" width="100%" class="formHeader"><IMG width="100%" height="2" src="include/images/blank.gif"></td>
			  </tr>
		</tbody></table>
	  </td>
          </tr>
          <tr>
            <td>
<?php

$lead 	        = new Lead();
$filestorage    = new FileStorage();
$contact 	= new Contact();
$account 	= new Account();
$opportunity	= new Opportunity();
$case 		= new aCase();
$user 		= new User();
$tracker 	= new Tracker();
$task		= new Task();
$note		= new Note();
$meeting	= new Meeting();
$call		= new Call();
$email		= new Email();
$tab            = new Tab();
$loghistory     = new LoginHistory();
$headers        = new Headers();
$startTime = microtime();

//TODO Clint 4/28 - Add logic for creating database as part of the script

//Dropping old tables if table exists and told to drop it
//Dropping leads table
if ($db_drop_tables == true &&
        mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$lead->table_name."'"))==1) {
        echo "Dropping existing ".$lead->table_name." table...";
        $lead->drop_tables();
        $log->info("Dropped old ".$lead->table_name." table.");
    echo "<font color=green>dropped existing ".$lead->table_name." table</font><BR>\n";
}
else {
        $log->info("Did not need to drop old ".$lead->table_name." table.  It doesn't exist.");
}
//Dropping filestorage table
if ($db_drop_tables == true &&
        mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$filestorage->table_name."'"))==1) {
        echo "Dropping existing ".$filestorage->table_name." table...";
        $filestorage->drop_tables();
        $log->info("Dropped old ".$filestorage->table_name." table.");
    echo "<font color=green>dropped existing ".$filestorage->table_name." table</font><BR>\n";
}
else {
        $log->info("Did not need to drop old ".$filestorage->table_name." table.  It doesn't exist.");
}
//Dropping headers table
if ($db_drop_tables == true &&
        mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$headers->table_name."'"))==1) {
        echo "Dropping existing ".$headers->table_name." table...";
        $headers->drop_tables();
        $log->info("Dropped old ".$headers->table_name." table.");
    echo "<font color=green>dropped existing ".$headers->table_name." table</font><BR>\n";
}
else {
        $log->info("Did not need to drop old ".$headers->table_name." table.  It doesn't exist.");
}

//Dropping contacts table
if ($db_drop_tables == true &&
	mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$contact->table_name."'"))==1) {
	echo "Dropping existing ".$contact->table_name." table...";
	$contact->drop_tables();
	$log->info("Dropped old ".$contact->table_name." table.");
    echo "<font color=green>dropped existing ".$contact->table_name." table</font><BR>\n";
}
else {
	$log->info("Did not need to drop old ".$contact->table_name." table.  It doesn't exist.");
}

//Dropping accounts table
if ($db_drop_tables == true &&
	mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$account->table_name."'"))==1) {
	echo "Dropping existing ".$account->table_name." table...";
	$account->drop_tables();
	$log->info("Dropped old ".$account->table_name." table.");
    echo "<font color=green>dropped existing ".$account->table_name." table</font><BR>\n";
}
else {
	$log->info("Did not need to drop old ".$account->table_name." table.  It doesn't exist.");
}

//Dropping opportunities table
if ($db_drop_tables == true &&
	mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$opportunity->table_name."'"))==1) {
	echo "Dropping existing ".$opportunity->table_name." table...";
	$opportunity->drop_tables();
	$log->info("Dropped old ".$opportunity->table_name." table.");
    echo "<font color=green>dropped existing ".$opportunity->table_name." table</font><BR>\n";
}
else {
	$log->info("Did not need to drop old ".$opportunity->table_name." table.  It doesn't exist.");
}

//Dropping cases table
if ($db_drop_tables == true &&
	mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$case->table_name."'"))==1) {
	echo "Dropping existing ".$case->table_name." table...";
	$case->drop_tables();
	$log->info("Dropped old ".$case->table_name." table.");
    echo "<font color=green>dropped existing ".$case->table_name." table</font><BR>\n";
}
else {
	$log->info("Did not need to drop old ".$case->table_name." table.  It doesn't exist.");
}

//Dropping users table
if ($db_drop_tables == true &&
	mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$user->table_name."'"))==1) {
	echo "Dropping existing ".$user->table_name." table...";
	$user->drop_tables();
	$log->info("Dropped old ".$user->table_name." table.");
    echo "<font color=green>dropped existing ".$user->table_name." table</font><BR>\n";
}
else {
	$log->info("Did not need to drop old ".$user->table_name." table.  It doesn't exist.");
}

//Dropping tracker table
if ($db_drop_tables == true &&
	mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$tracker->table_name."'"))==1) {
	echo "Dropping existing ".$tracker->table_name." table...";
	$tracker->drop_tables();
	$log->info("Dropped old ".$tracker->table_name." table.");
    echo "<font color=green>dropped existing ".$tracker->table_name." table</font><BR>\n";
}
else {
	$log->info("Did not need to drop old ".$tracker->table_name." table.  It doesn't exist.");
}

//Dropping tasks table
if ($db_drop_tables == true &&
	mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$task->table_name."'"))==1) {
	echo "Dropping existing ".$task->table_name." table...";
	$task->drop_tables();
	$log->info("Dropped old ".$task->table_name." table.");
    echo "<font color=green>dropped existing ".$task->table_name." table</font><BR>\n";
}
else {
	$log->info("Did not need to drop old ".$task->table_name." table.  It doesn't exist.");
}

//Dropping notes table
if ($db_drop_tables == true &&
	mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$note->table_name."'"))==1) {
	echo "Dropping existing ".$note->table_name." table...";
	$note->drop_tables();
	$log->info("Dropped old ".$note->table_name." table.");
    echo "<font color=green>dropped existing ".$note->table_name." table</font><BR>\n";
}
else {
	$log->info("Did not need to drop old ".$note->table_name." table.  It doesn't exist.");
}

//Dropping meetings table
if ($db_drop_tables == true &&
	mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$meeting->table_name."'"))==1) {
	echo "Dropping existing ".$meeting->table_name." table...";
	$meeting->drop_tables();
	$log->info("Dropped old ".$meeting->table_name." table.");
    echo "<font color=green>dropped existing ".$meeting->table_name." table</font><BR>\n";
}
else {
	$log->info("Did not need to drop old ".$meeting->table_name." table.  It doesn't exist.");
}

//Dropping calls table
if ($db_drop_tables == true &&
	mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$call->table_name."'"))==1) {
	echo "Dropping existing ".$call->table_name." table...";
	$call->drop_tables();
	$log->info("Dropped old ".$call->table_name." table.");
    echo "<font color=green>dropped existing ".$call->table_name." table</font><BR>\n";
}
else {
	$log->info("Did not need to drop old ".$call->table_name." table.  It doesn't exist.");
}

//Dropping emails table
if ($db_drop_tables == true &&
	mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$email->table_name."'"))==1) {
	echo "Dropping existing ".$email->table_name." table...";
	$email->drop_tables();
	$log->info("Dropped old ".$email->table_name." table.");
    echo "<font color=green>dropped existing ".$email->table_name." table</font><BR>\n";
}
else {
	$log->info("Did not need to drop old ".$email->table_name." table.  It doesn't exist.");
}

//Dropping tabmenu table
if ($db_drop_tables == true &&
        mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$tab->table_name."'"))==1) {
        echo "Dropping existing ".$tab->table_name." table...";
        $tab->drop_tables();
        $log->info("Dropped old ".$tab->table_name." table.");
    echo "<font color=green>dropped existing ".$tab->table_name." table</font><BR>\n";
}
else {
        $log->info("Did not need to drop old ".$tab->table_name." table.  It doesn't exist.");
}

//Dropping loginhistory table
if ($db_drop_tables == true &&
        mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$loghistory->table_name."'"))==1) {
        echo "Dropping existing ".$loghistory->table_name." table...";
        $loghistory->drop_tables();
        $log->info("Dropped old ".$loghistory->table_name." table.");
    echo "<font color=green>dropped existing ".$loghistory->table_name." table</font><BR>\n";
}
else {
        $log->info("Did not need to drop old ".$loghistory->table_name." table.  It doesn't exist.");
}

// Creating new tables if they don't exist.
// Creating leads table.
if (mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$lead->table_name."'"))!=1) {
        echo "Creating new ".$lead->table_name." table...";
        $lead->create_tables();
        $log->info("Created ".$lead->table_name." table.");
        echo "<font color=green>created ".$lead->table_name." table</font><BR>\n";
}
else {
        echo "Not creating new ".$lead->table_name." table.  It already exists.<BR>\n";
}

// Creating new tables if they don't exist.
// Creating filestorage table.
if (mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$filestorage->table_name."'"))!=1) {
        echo "Creating new filestorage table...";
	$filestorage->create_tables();
        $log->info("Created ".$filestorage->table_name." table.");
        echo "<font color=green>created ".$filestorage->table_name." table</font><BR>\n";
}
else {
        echo "Not creating new ".$filestorage->table_name." table.  It already exists.<BR>\n";
}
// Creating new tables if they don't exist.
// Creating headers table.
if (mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$headers->table_name."'"))!=1) {
        echo "Creating new headers table...";
	$headers->create_tables();
        $log->info("Created ".$headers->table_name." table.");
        echo "<font color=green>created ".$headers->table_name." table</font><BR>\n";
}
else {
        echo "Not creating new ".$headers->table_name." table.  It already exists.<BR>\n";
}



// Creating contacts table.
if (mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$contact->table_name."'"))!=1) {
	echo "Creating new ".$contact->table_name." table...";
	$contact->create_tables();
	$log->info("Created ".$contact->table_name." table.");
	echo "<font color=green>created ".$contact->table_name." table</font><BR>\n";
}
else {
	echo "Not creating new ".$contact->table_name." table.  It already exists.<BR>\n";
}

// Creating accounts table.
if (mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$account->table_name."'"))!=1) {
	echo "Creating new ".$account->table_name." table...";
    $account->create_tables();
	$log->info("Created ".$account->table_name." table.");
    echo "<font color=green>created ".$account->table_name." table</font><BR>\n";
}
else {
	echo "Not creating new ".$account->table_name." table.  It already exists.<BR>\n";
}

// Creating opportunities table.
if (mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$opportunity->table_name."'"))!=1) {
	echo "Creating new ".$opportunity->table_name." table...";
    $opportunity->create_tables();
	$log->info("Created ".$opportunity->table_name." table.");
    echo "<font color=green>created ".$opportunity->table_name." table</font><BR>\n";
}
else {
	echo "Not creating new ".$opportunity->table_name." table.  It already exists.<BR>\n";
}

// Creating cases table.
if (mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$case->table_name."'"))!=1) {
	echo "Creating new ".$case->table_name." table...";
    $case->create_tables();
	$log->info("Created ".$case->table_name." table.");
    echo "<font color=green>created ".$case->table_name." table</font><BR>\n";
}
else {
	echo "Not creating new ".$case->table_name." table.  It already exists.<BR>\n";
}

// Creating users table.
if (mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$user->table_name."'"))!=1) {
	echo "Creating new ".$user->table_name." table...";
    $user->create_tables();
	
	//Create default admin user
	$user->last_name = 'Administrator';
	$user->user_name = 'admin';
	$user->is_admin = 'on';
	$user->user_password = $user->encrypt_password($admin_password);
	$user->email = $admin_email;
	$user->save();

	// We need to change the admin user to a fixed id of 1.
	$query = "update users set id='1' where user_name='$user->user_name'";
	$result = mysql_query($query) or die("Error updating admin user ID: ".mysql_error());

	$log->info("Created ".$user->table_name." table. for user $user->id");
    echo "<font color=green>created ".$user->table_name." table</font><BR>\n";
    
    if($create_default_user)
    {
    	$default_user = new User();
    	$default_user->last_name = $default_user_name;
    	$default_user->user_name = $default_user_name;
    	if (isset($default_user_is_admin) && $default_user_is_admin) $default_user->is_admin = 'on';
    	$default_user->user_password = $default_user->encrypt_password($default_password);
    	$default_user->save();
    }
    
}
else {
	echo "Not creating new ".$user->table_name." table.  It already exists.<BR>\n";
}

// Creating tracker table.
if (mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$tracker->table_name."'"))!=1) {
	echo "Creating new ".$tracker->table_name." table...";
    $tracker->create_tables();
	$log->info("Created ".$tracker->table_name." table.");
    echo "<font color=green>created ".$tracker->table_name." table</font><BR>\n";
}
else {
	echo "Not creating new ".$tracker->table_name." table.  It already exists.<BR>\n";
}

// Creating tasks table.
if (mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$task->table_name."'"))!=1) {
	echo "Creating new ".$task->table_name." table...";
    $task->create_tables();
	$log->info("Created ".$task->table_name." table.");
    echo "<font color=green>created ".$task->table_name." table</font><BR>\n";
}
else {
	echo "Not creating new ".$task->table_name." table.  It already exists.<BR>\n";
}

// Creating notes table.
if (mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$note->table_name."'"))!=1) {
	echo "Creating new ".$note->table_name." table...";
    $note->create_tables();
	$log->info("Created ".$note->table_name." table.");
    echo "<font color=green>created ".$note->table_name." table</font><BR>\n";
}
else {
	echo "Not creating new ".$note->table_name." table.  It already exists.<BR>\n";
}

// Creating meetings table.
if (mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$meeting->table_name."'"))!=1) {
	echo "Creating new ".$meeting->table_name." table...";
    $meeting->create_tables();
	$log->info("Created ".$meeting->table_name." table.");
    echo "<font color=green>created ".$meeting->table_name." table</font><BR>\n";
}
else {
	echo "Not creating new ".$meeting->table_name." table.  It already exists.<BR>\n";
}

// Creating calls table.
if (mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$call->table_name."'"))!=1) {
	echo "Creating new ".$call->table_name." table...";
    $call->create_tables();
	$log->info("Created ".$call->table_name." table.");
    echo "<font color=green>created ".$call->table_name." table</font><BR>\n";
}
else {
	echo "Not creating new ".$call->table_name." table.  It already exists.<BR>\n";
}

// Creating emails table.
if (mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$email->table_name."'"))!=1) {
	echo "Creating new ".$email->table_name." table...";
    $email->create_tables();
	$log->info("Created ".$email->table_name." table.");
    echo "<font color=green>created ".$email->table_name." table</font><BR>\n";
}
else {
	echo "Not creating new ".$email->table_name." table.  It already exists.<BR>\n";
}

// Creating tabmenu table.
if (mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$tab->table_name."'"))!=1) {
        echo "Creating new ".$tab->table_name." table...";
    $tab->create_tables();
        $log->info("Created ".$tab->table_name." table.");
    echo "<font color=green>created ".$tab->table_name." table</font><BR>\n";
}
else {
        echo "Not creating new ".$tab->table_name." table.  It already exists.<BR>\n";
}

// Creating loginhistory table.
if (mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$loghistory->table_name."'"))!=1) {
        echo "Creating new ".$loghistory->table_name." table...";
    $loghistory->create_tables();
        $log->info("Created ".$loghistory->table_name." table.");
    echo "<font color=green>created ".$loghistory->table_name." table</font><BR>\n";
}
else {
        echo "Not creating new ".$loghistory->table_name." table.  It already exists.<BR>\n";
}

//populating the db with seed data 
if ($db_populate) {
	echo "Populating seed data into $db_name";
	include("install/populateSeedData.php");
	echo "...<font color=\"00CC00\">done</font><BR><P>\n";
}

$endTime = microtime();

$deltaTime = microtime_diff($startTime, $endTime);

$database->disconnect();
?>
The database tables are now set up.<HR></HR>
Total time: <?php echo "$deltaTime"; ?> seconds.<BR />
</td></tr>
<tr><td><hr></td></tr>
<tr><td align=left><font color=green>Your system is now installed and configured for use.  You will need to log in for the first time using the "admin" 
userid and the password you entered in step 2.</font></td></tr>
<tr><td align="right">
	 <form action="<?php echo $site_URL; ?>/index.php" method="post" name="form" id="form">
	 <input type="hidden" name="default_user_name" value="admin"> 
	 <input class="button" type="submit" name="next" value="Finish" />			
	 </form>
</td></tr>
</tbody></table></body></html>

