<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the 
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/index.php,v 1.14.2.1 2004/09/08 12:41:40 jack Exp $
 * Description: Main file and starting point for the application.  Calls the 
 * theme header and footer files defined for the user as well as the module as 
 * defined by the input parameters.
 ********************************************************************************/

// Allow for the session information to be passed via the URL for printing.
if(isset($_REQUEST['PHPSESSID']))
{
	session_id($_REQUEST['PHPSESSID']);
}	

// Create or reestablish the current session
session_start();

if (!is_file('config.php')) {
    header("Location: install.php");
    exit();
}

require_once('config.php');
if (!isset($dbconfig['db_host_name'])) {
    header("Location: install.php");
    exit();
}

// load up the config_override.php file.  This is used to provide default user settings
if (is_file('config_override.php')) 
{
	require_once('config_override.php');
}

require_once('include/logging.php');
require_once('modules/Users/User.php');

global $currentModule;

if($calculate_response_time) $startTime = microtime();

$log =& LoggerManager::getLogger('index');

// We use the REQUEST_URI later to construct dynamic URLs.  IIS does not pass this field
// to prevent an error, if it is not set, we will assign it to ''
if(!isset($_SERVER['REQUEST_URI']))
{
	$_SERVER['REQUEST_URI'] = '';
}

if(isset($_REQUEST['action']))
{
	$action = $_REQUEST['action'];
}

if(isset($_REQUEST['module']))
{
	$module = $_REQUEST['module'];	
}
// Check to see if there is an authenticated user in the session.
if(isset($_SESSION["authenticated_user_id"]))
{
	$log->debug("We have an authenticated user id: ".$_SESSION["authenticated_user_id"]);
}
else if(isset($action) && isset($module) && $action=="Authenticate" && $module=="Users")
{
	$log->debug("We are authenticating user now");
}
else 
{
	$log->debug("The current user does not have a session.  Going to the login page");	
	$action = "Login";
	$module = "Users";
}


$log->debug($_REQUEST);

$skipHeaders=false;
$skipFooters=false;
if(isset($action) && isset($module))
{
	$log->info("About to take action ".$action);
	$log->debug("in $action");
	if(ereg("^Save", $action) || ereg("^Delete", $action) || ereg("^Popup", $action) || ereg("^ChangePassword", $action) || ereg("^Authenticate", $action) || ereg("^Logout", $action) || ereg("^add2db", $action) || ereg("^result", $action))
	{
		$skipHeaders=true;
		if(ereg("^Popup", $action) || ereg("^ChangePassword", $action))
			$skipFooters=true;
	}
	
	$currentModuleFile = 'modules/'.$module.'/'.$action.'.php';
	$currentModule = $module;
}
elseif(isset($module))
{
	$currentModule = $module;
	$currentModuleFile = $moduleDefaultFile[$currentModule];
}
else {
    // use $default_module and $default_action as set in config.php
    // Redirect to the correct module with the correct action.  We need the URI to include these fields.
    header("Location: index.php?action=$default_action&module=$default_module");
    exit();
}

$log->info("current page is $currentModuleFile");	
$log->info("current module is $currentModule ");	

//define default home pages for each module
require_once("modules/Users/TabMenu.php");
$tabData = new TabMenu();
$moduleList = $tabData->getTabNames();

foreach ($moduleList as $mod) {
	$moduleDefaultFile[$mod] = "modules/".$currentModule."/index.php";
}

$current_user = new User();
if(isset($_SESSION['authenticated_user_id']))
{
	$result = $current_user->retrieve($_SESSION['authenticated_user_id']);
	if($result == null)
	{
		session_destroy();
	    header("Location: index.php?action=Login&module=Users");
	}
	
	$log->debug('Current user is: '.$current_user->user_name);
}

if(isset($_SESSION['authenticated_user_theme']) && $_SESSION['authenticated_user_theme'] != '')
{
	$theme = $_SESSION['authenticated_user_theme'];
}
else 
{
	$theme = $default_theme;
}
$log->debug('Current theme is: '.$theme);

//Used for current record focus
$focus = "";

// if the language is not set yet, then set it to the default language.
if(isset($_SESSION['authenticated_user_language']) && $_SESSION['authenticated_user_language'] != '')
{
	$current_language = $_SESSION['authenticated_user_language'];
}
else 
{
	$current_language = $default_language;
}
$log->debug('current_language is: '.$current_language);

//set module and application string arrays based upon selected language
$app_strings = return_application_language($current_language);
$app_list_strings = return_app_list_strings_language($current_language);
$mod_strings = return_module_language($current_language, $currentModule);

//TODO: Clint - this key map needs to be moved out of $app_list_strings since it never gets translated.
//              best to just have an upgrade script that changes the parent_type column from Account to Accounts, etc.
$app_list_strings['record_type_module'] = array('Account' => 'Accounts','Opportunity' => 'Opportunities', 'Case' => 'Cases');

//If DetailView, set focus to record passed in
if($action == "DetailView")
{
	if(!isset($_REQUEST['record']))
		die("A record number must be specified to view details.");

	// If we are going to a detail form, load up the record now.
	// Use the record to track the viewing.
	// todo - Have a record of modules and thier primary object names.
	switch($currentModule)
	{
		case 'Leads':
			require_once("modules/$currentModule/Lead.php");
			$focus = new Lead();
			break;
		case 'Contacts':
			require_once("modules/$currentModule/Contact.php");
			$focus = new Contact();
			break;
		case 'Accounts':
			require_once("modules/$currentModule/Account.php");
			$focus = new Account();
			break;
		case 'Opportunities':
			require_once("modules/$currentModule/Opportunity.php");
			$focus = new Opportunity();
			break;
		case 'Cases':
			require_once("modules/$currentModule/Case.php");
			$focus = new aCase();
			break;
		case 'Tasks':
			require_once("modules/$currentModule/Task.php");
			$focus = new Task();
			break;
		case 'Notes':
			require_once("modules/$currentModule/Note.php");
			$focus = new Note();
			break;
		case 'Meetings':
			require_once("modules/$currentModule/Meeting.php");
			$focus = new Meeting();
			break;
		case 'Calls':
			require_once("modules/$currentModule/Call.php");
			$focus = new Call();
			break;
		case 'Emails':
			require_once("modules/$currentModule/Email.php");
			$focus = new Email();
			break;
		case 'Users':
			require_once("modules/$currentModule/User.php");
			$focus = new User();
			break;
		}
	
	$focus->retrieve($_REQUEST['record']);
	
	$focus->track_view($current_user->id, $currentModule);
}	

if(!$skipHeaders) {
	include('themes/'.$theme.'/header.php');
	
	if(isset($_SESSION['administrator_error']))
	{
		// only print DB errors once otherwise they will still look broken after they are fixed.
		// Only print the errors for admin users.
		if(is_admin($current_user)) 
			echo $_SESSION['administrator_error'];
		unset($_SESSION['administrator_error']);
	}
	
	echo "<!-- startprint -->";
}
else {
		$log->debug("skipping headers");
}

include($currentModuleFile);

echo "<!-- stopprint -->";

if(!$skipFooters)
	include('themes/'.$theme.'/footer.php');

// Under the SPL you do not have the right to remove this copyright statement.	
echo "<style>
        .bggray
        {
        background-color: #dfdfdf;
        }
        .bgwhite
        {
        background-color: #FFFFFF;
        }
	.copy
        {
        font-size:9px;
        font-family: Verdana, Arial, Helvetica, Sans-serif;
        }
        </style>
";
echo "<table width=60% border=0 cellspacing=1 cellpadding=0 class=\"bggray\" align=center><tr><td align=center>\n";
echo "<table width=100% border=0 cellspacing=1 cellpadding=0 class=\"bgwhite\" align=center><tr><td align=center class=\"copy\">\n";
echo("&copy; This software is a collective work consisting of the following Open  Source components : Apache Software, MySQL Server, PHP and SugarCRM , each licensed under a separate Open Source License. vtiger is not affiliated with nor endorsed by any of the above providers. See <a href='http://www.vtiger.com/copyrights/LICENSE_AGREEMENT.txt' class=\"copy\">Copyrights </a> for details.<br>\n");
echo "</td></tr></table></td></tr></table>\n";
	
if($calculate_response_time)
{
    $endTime = microtime();

    $deltaTime = microtime_diff($startTime, $endTime);
    echo("<center><font style='font-family: Verdana, Arial, Helvetica, Sans-serif;font-size:9px'>&nbsp;Server response time: ".$deltaTime." seconds.</font></center>");
}
?>
