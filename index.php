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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/index.php,v 1.50.2.2 2004/12/15 17:29:11 jack Exp $
 * Description: Main file and starting point for the application.  Calls the 
 * theme header and footer files defined for the user as well as the module as 
 * defined by the input parameters.
 ********************************************************************************/

global $entityDel;
global $display;
$phpbb_root_path='./modules/MessageBoard/';
if (substr(phpversion(), 0, 1) == "5") {
        ini_set("zend.ze1_compatibility_mode", "1");
}

function fetchPermissionDataForTabList()
{
  $permittedTabs = $_SESSION['tab_permission_set'];
  $i=0;$j=0;
  
  while($i<count($permittedTabs))
  {
    $modulesPermitted[$j++]=  $permittedTabs[$i];
    $i++;
  }
  return $modulesPermitted;
}

function fetchPermissionData($module,$action)
{
  global $theme,$display;
  $permissionData = $_SESSION['action_permission_set'];
  $i=0;

  require_once('modules/Users/UserInfoUtil.php');

  if($module == 'Leads')
  {
    $tabid=3;
  }
  else if($module == 'Home')
  {
    $tabid=1;
  }
  else if($module == 'Dashboard')
  {
    $tabid=2;
  }
  else if($module == 'Accounts')
  {
    $tabid=5;
  }
  else if($module == 'Contacts')
  {
    $tabid=4;
  }
  else if($module == 'Opportunities')
  {
    $tabid=6;
  }
  else if($module == 'Cases')
  {
    $tabid=7;
  }
  else if($module == 'Notes')
  {
    $tabid=8;
  }
  else if($module == 'Calls')
  {
    $tabid=9;
  }
  else if($module == 'Emails')
  {
    $tabid=10;
  }
  else if($module == 'Meetings')
  {
    $tabid=11;
  }
  else if($module == 'Tasks')
  {
    $tabid=12;
  }
 else if($module == 'MessageBoard')
  {
    $tabid=13;
  }
  $accessFlag = false;
  checkDeletePermission($tabid);
  //if the tabid is not present in the array then he is not permitted
  //if the tabid is present, then check for the values of the action_permissions
  while($i<count($permissionData))
  {
    if($permissionData[$i][0] == $tabid )
    {
      $accessFlag=true;
      if($permissionData[$i][1]==$action)
      {
        $actionpermissionvalue=$permissionData[$i][2];
        if($actionpermissionvalue == 0)
        {
          echo "You are not permitted to execute this operation";
          $display = "No";
        }
        else
        {
          return;
        }
      }
      
    }
    $i++;
  }
   
  if(!$accessFlag)
  {
    echo "You are not permitted to execute this operation";
       $display = "No";
  }
}

//we have to do this as there is no UI page for Delete. Hence, when the user clicks delete, it gets stuck halfway and the page looks ugly because the theme is not set
function checkDeletePermission($tabid)
{
	global $entityDel;
  $action ="Delete";
  $permissionData = $_SESSION['action_permission_set'];
  $i = 0;
  //keep searching till Delete method is found in the array
  while($i<count($permissionData))
  {
    if($permissionData[$i][0] == $tabid  &&  $permissionData[$i][1]==$action)
    {
      
      $actionpermissionvalue=$permissionData[$i][2];
      if($actionpermissionvalue == 1)
      {
        $entityDel = true;
      }
      else
      {
        $entityDel = false;
      }
    }
    $i++;
  }

}
 function stripslashes_checkstrings($value){
        if(is_string($value)){
                return stripslashes($value);
        }
        return $value;

 }
 if(get_magic_quotes_gpc() == 1){
        $_REQUEST = array_map("stripslashes_checkstrings", $_REQUEST);
        $_POST = array_map("stripslashes_checkstrings", $_POST);
        $_GET = array_map("stripslashes_checkstrings", $_GET);

}

// Simulating the login process of forums here 
//This needs to be called only once. This check has been put so that common.php does not get invoked time and again
	if(isset($HTTP_POST_VARS['Login']) || isset($HTTP_GET_VARS['Login']) || isset($HTTP_POST_VARS['Logout']) || isset($HTTP_GET_VARS['Logout']))
	{
		if((isset($HTTP_POST_VARS['Login']) || isset($HTTP_GET_VARS['Login'])) && !$userdata['session_logged_in'])
		{
			//now log in to the Forums for the current user
			include($phpbb_root_path . 'common.php');

		 	$sql = "SELECT user_id, username, user_password, user_active, user_level
                        	FROM " . USERS_TABLE . "
	                        WHERE username = '" . $HTTP_POST_VARS['user_name'] . "'";
        	        if ( !($result = $db->sql_query($sql)) )
                	{
                        	message_die(GENERAL_ERROR, 'Error in obtaining userdata', '', __LINE__, __FILE__, $sql);
                	}
			$password=$HTTP_POST_VARS['user_password'];
			$username=$HTTP_POST_VARS['user_name'];
                	if( $row = $db->sql_fetchrow($result) )
                	{
                        	if( $row['user_level'] != ADMIN && $board_config['board_disable'] )
                        	{
                        	}
                        	else
                        	{
		                         if( md5($password) == $row['user_password'] && $row['user_active'] )
                	                {	
						$autologin = 0;

                                	        $session_id = session_begin($row['user_id'], $user_ip, PAGE_INDEX, FALSE, $autologin);
                                	}
                        	}
                	}

		}
	}


// Allow for the session information to be passed via the URL for printing.
if(isset($_REQUEST['PHPSESSID']))
{
	session_id($_REQUEST['PHPSESSID']);
	//Setting the same session id to Forums as in CRM
        $sid=$_REQUEST['PHPSESSID'];
}	
function insert_charset_header()
{
 	global $app_strings, $default_charset;
 	$charset = $default_charset;
 	
 	if(isset($app_strings['LBL_CHARSET']))
 	{
 	        $charset = $app_strings['LBL_CHARSET'];
 	}
 	header('Content-Type: text/html; charset='. $charset);
}
 	
insert_charset_header();
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
require_once('include/utils.php');
$default_config_values = Array( "allow_exports"=>"all","upload_maxsize"=>"3000000" );
 	
set_default_config($default_config_values);
require_once('include/logging.php');
require_once('modules/Users/User.php');

global $currentModule;

if($calculate_response_time) $startTime = microtime();

$log =& LoggerManager::getLogger('index');
if (isset($_REQUEST['PHPSESSID'])) $log->debug("****Starting for session ".$_REQUEST['PHPSESSID']);
else $log->debug("****Starting for new session");

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
$viewAttachment = false;
//echo $module;
// echo $action;
if(isset($action) && isset($module))
{
	$log->info("About to take action ".$action);
	$log->debug("in $action");
	if(ereg("^Save", $action) || ereg("^Delete", $action) || ereg("^Popup", $action) || ereg("^ChangePassword", $action) || ereg("^Authenticate", $action) || ereg("^Logout", $action) || ereg("^Export",$action) || ereg("^add2db", $action) || ereg("^result", $action) || ereg("^LeadConvertToEntities", $action) || ereg("^downloadfile", $action) || ereg("^massdelete", $action) || ereg("^updateLeadDBStatus",$action) || ereg("^AddCustomFieldToDB", $action) || ereg("^updateRole",$action) || ereg("^UserInfoUtil",$action) || ereg("^deleteRole",$action) || ereg("^UpdateComboValues",$action) || ereg("^fieldtypes",$action))
	{
		$skipHeaders=true;
		if(ereg("^Popup", $action) || ereg("^ChangePassword", $action) || ereg("^Export", $action) || ereg("^downloadfile", $action) || ereg("^fieldtypes",$action))
			$skipFooters=true;
		if(ereg("^downloadfile", $action) || ereg("^fieldtypes",$action))
		{
			$viewAttachment = true;
		}
		if(($action == ' Delete ') && (!$entityDel))
		{
			$skipHeaders=false;
		}
	}
	
	if($action == 'BusinessCard' || $action == 'Save')
	{
 	         header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
 	         header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
 	         header( "Cache-Control: no-cache, must-revalidate" );
 	         header( "Pragma: no-cache" );        
 	}

	if ( $action == "Import" &&
                isset($_REQUEST['step']) &&
                $_REQUEST['step'] == '4'  )
        {
                $skipHeaders=true;
                $skipFooters=true;
        }
        if($module == 'Users' || $module == 'Home' || $module == 'Administration' || $module == 'uploads' ||  $module == 'Settings')
        {
          $skipSecurityCheck=true;
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

global $permittedModulesList;

$permittedModulesList = fetchPermissionDataForTabList();
//print_r($permittedModulesList);
$templist="";
if(!$permittedModulesList == "")
{
     
     foreach ($permittedModulesList as $list) 
     {
       if($tempList=="")
       {
         $tempList = "'".$list."'" ;  
       }
       else
       {
         $tempList .= ",'" . $list."'" ;
       }
       $list="";
     }
}


$moduleList = $tabData->getTabNames($tempList);

foreach ($moduleList as $mod) {
	$moduleDefaultFile[$mod] = "modules/".$currentModule."/index.php";
}

// for printing
$module = (isset($_REQUEST['module'])) ? $_REQUEST['module'] : "";
$action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : "";
$record = (isset($_REQUEST['record'])) ? $_REQUEST['record'] : "";
$lang = (isset($_SESSION['authenticated_user_language'])) ? $_SESSION['authenticated_user_language'] : "";
$GLOBALS['request_string'] = "&module=$module&action=$action&record=$record&lang=$lang";

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

// set user, theme and language cookies so that login screen defaults to last values
if (isset($_SESSION['authenticated_user_id'])) {
        $log->debug("setting cookie ck_login_id to ".$_SESSION['authenticated_user_id']);
        setcookie('ck_login_id', $_SESSION['authenticated_user_id']);
}
if (isset($_SESSION['authenticated_user_theme'])) {
        $log->debug("setting cookie ck_login_theme to ".$_SESSION['authenticated_user_theme']);
        setcookie('ck_login_theme', $_SESSION['authenticated_user_theme']);
}
if (isset($_SESSION['authenticated_user_language'])) {
        $log->debug("setting cookie ck_login_language to ".$_SESSION['authenticated_user_language']);
        setcookie('ck_login_language', $_SESSION['authenticated_user_language']);
}

//skip headers for popups, deleting, saving, importing and other actions
if(!$skipHeaders) {
	$log->debug("including headers");
	include('themes/'.$theme.'/header.php');
	
	if(isset($_SESSION['administrator_error']))
	{
		// only print DB errors once otherwise they will still look broken after they are fixed.
		// Only print the errors for admin users.
		if(is_admin($current_user)) 
			echo $_SESSION['administrator_error'];
		unset($_SESSION['administrator_error']);
	}
	
	echo "<!-- startscrmprint -->";
}
else {
		$log->debug("skipping headers");
}



//fetch the permission set from session and search it for the requisite data

if(isset($_SESSION['authenticated_user_theme']) && $_SESSION['authenticated_user_theme'] != '')
{
	$theme = $_SESSION['authenticated_user_theme'];
}
else 
{
	$theme = $default_theme;
}
if(!$skipSecurityCheck)
{
  fetchPermissionData($module,$action);
}
if ($display == "No")
{
	$display == "";
}
else
{
	include($currentModuleFile);
}

	if(!$viewAttachment)
	{
		echo "<!-- stopscrmprint -->";
	}

//added to get the theme . This is a bad fix as we need to know where the problem lies yet
if(isset($_SESSION['authenticated_user_theme']) && $_SESSION['authenticated_user_theme'] != '')
{
        $theme = $_SESSION['authenticated_user_theme'];
}
else
{
        $theme = $default_theme;
}






if(!$skipFooters)
	     include('themes/'.$theme.'/footer.php');
if(!$viewAttachment)
{
// Under the SPL you do not have the right to remove this copyright statement.	
$copyrightstatement="<style>
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
	<script language=javascript>
         function LogOut(e)
         {
                 var nav4 = window.Event ? true : false;
                 var iX,iY;
                 if (nav4)
                 {
                         iX = e.pageX;
                         iY = e.pageY;
                 }
                 else
                 {
                         iX = event.clientX + document.body.scrollLeft;
                         iY = event.clientY + document.body.scrollTop;

                 }
                 if (iX <= 30 && iY < 0 )
                 {
                         w=window.open(\"index.php?action=Logout&module=Users\");
                         w.close();
                 }
         }
         //window.onunload=LogOut
       </script>
";
echo $copyrightstatement;
echo "<table width=60% border=0 cellspacing=1 cellpadding=0 class=\"bggray\" align=center><tr><td align=center>\n";
echo "<table width=100% border=0 cellspacing=1 cellpadding=0 class=\"bgwhite\" align=center><tr><td align=center class=\"copy\">\n";
echo("&copy; This software is a collective work consisting of the following major Open Source components : Apache Software, MySQL Server, PHP, SugarCRM, phpBB, and PHPMailer each licensed under a separate Open Source License. vtiger is not affiliated with nor endorsed by any of the above providers. See <a href='http://www.vtiger.com/copyrights/LICENSE_AGREEMENT.txt' class=\"copy\" target=\"_blank\">Copyrights </a> for details.<br>\n");
echo "</td></tr></table></td></tr></table>\n";

echo "<table align='center'><tr><td align='center'>";
// Under the Sugar Public License referenced above, you are required to leave in all copyright statements in both
// the code and end-user application.
//echo("<br>&copy; 2004 <a href='http://www.sugarcrm.com' target='_blank'>SugarCRM Inc.</a> All Rights Reserved.<BR />");	
if($calculate_response_time)
{
    $endTime = microtime();

    $deltaTime = microtime_diff($startTime, $endTime);
    echo('&nbsp;Server response time: '.$deltaTime.' seconds.');
}
echo "</td></tr></table>\n";
}







?>
