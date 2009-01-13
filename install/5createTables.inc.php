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

$new_tables = 0;

require_once('config.php');
require_once('include/database/PearDatabase.php');
require_once('include/logging.php');
require_once('modules/Leads/Leads.php');
require_once('modules/Contacts/Contacts.php');
require_once('modules/Accounts/Accounts.php');
require_once('modules/Potentials/Potentials.php');
require_once('modules/Calendar/Activity.php');
require_once('modules/Documents/Documents.php');
require_once('modules/Emails/Emails.php');
require_once('modules/Users/Users.php');
require_once('modules/Import/ImportMap.php');
require_once('modules/Import/UsersLastImport.php');
require_once('modules/Users/LoginHistory.php');
require_once('data/Tracker.php');
require_once('include/utils/utils.php');
require_once('modules/Users/DefaultDataPopulator.php');
require_once('modules/Users/CreateUserPrivilegeFile.php');

// load the config_override.php file to provide default user settings
if (is_file("config_override.php")) {
	require_once("config_override.php");
}

$db = new PearDatabase();

$log =& LoggerManager::getLogger('INSTALL');

function eecho($msg = FALSE) {
	if ($useHtmlEntities) {
		echo htmlentities(nl2br($msg));
	}
	else {
		echo $msg;
	}
}

function create_default_users_access() {
      	global $log, $adb;
        global $admin_email;
        global $admin_password;
		global $standarduser_email;
		global $standarduser_password;
        global $create_default_user;
        global $default_user_name;
        global $default_password;
        global $default_user_is_admin;

        $role1_id = $adb->getUniqueID("vtiger_role");
		$role2_id = $adb->getUniqueID("vtiger_role");
		$role3_id = $adb->getUniqueID("vtiger_role");
		$role4_id = $adb->getUniqueID("vtiger_role");
		$role5_id = $adb->getUniqueID("vtiger_role");
		
		$profile1_id = $adb->getUniqueID("vtiger_profile");
		$profile2_id = $adb->getUniqueID("vtiger_profile");
		$profile3_id = $adb->getUniqueID("vtiger_profile");
		$profile4_id = $adb->getUniqueID("vtiger_profile");
		
		$adb->query("insert into vtiger_role values('H".$role1_id."','Organisation','H".$role1_id."',0)");
        $adb->query("insert into vtiger_role values('H".$role2_id."','CEO','H".$role1_id."::H".$role2_id."',1)");
        $adb->query("insert into vtiger_role values('H".$role3_id."','Vice President','H".$role1_id."::H".$role2_id."::H".$role3_id."',2)");
        $adb->query("insert into vtiger_role values('H".$role4_id."','Sales Manager','H".$role1_id."::H".$role2_id."::H".$role3_id."::H".$role4_id."',3)");
        $adb->query("insert into vtiger_role values('H".$role5_id."','Sales Man','H".$role1_id."::H".$role2_id."::H".$role3_id."::H".$role4_id."::H".$role5_id."',4)");
                
        // create default admin user
    	$user = new Users();
        $user->column_fields["last_name"] = 'Administrator';
        $user->column_fields["user_name"] = 'admin';
        $user->column_fields["status"] = 'Active';
        $user->column_fields["is_admin"] = 'on';
        $user->column_fields["user_password"] = $admin_password;
        $user->column_fields["tz"] = 'Europe/Berlin';
        $user->column_fields["holidays"] = 'de,en_uk,fr,it,us,';
        $user->column_fields["workdays"] = '0,1,2,3,4,5,6,';
        $user->column_fields["weekstart"] = '1';
        $user->column_fields["namedays"] = '';
        $user->column_fields["currency_id"] = 1;
        $user->column_fields["reminder_interval"] = '1 Minute';
        $user->column_fields["reminder_next_time"] = date('Y-m-d H:i');
		$user->column_fields["date_format"] = 'yyyy-mm-dd';
		$user->column_fields["hour_format"] = 'am/pm';
		$user->column_fields["start_hour"] = '08:00';
		$user->column_fields["end_hour"] = '23:00';
		$user->column_fields["imagename"] = '';
		$user->column_fields["internal_mailer"] = '1';
		$user->column_fields["activity_view"] = 'This Week';	
		$user->column_fields["lead_view"] = 'Today';
		$user->column_fields["defhomeview"] = 'home_metrics';
        //added by philip for default admin emailid
		if($admin_email == '')
			$admin_email ="admin@vtigeruser.com";
        $user->column_fields["email1"] = $admin_email;
		$role_query = "select roleid from vtiger_role where rolename='CEO'";
		$adb->checkConnection();
		$adb->database->SetFetchMode(ADODB_FETCH_ASSOC);
		$role_result = $adb->query($role_query);
		$role_id = $adb->query_result($role_result,0,"roleid");
		$user->column_fields["roleid"] = $role_id;

        $user->save("Users");
        $admin_user_id = $user->id;

		//Creating the Standard User
    	$user = new Users();
        $user->column_fields["last_name"] = 'StandardUser';
        $user->column_fields["user_name"] = 'standarduser';
        $user->column_fields["is_admin"] = 'off';
        $user->column_fields["status"] = 'Active'; 
        $user->column_fields["user_password"] = $standarduser_password; 
        $user->column_fields["tz"] = 'Europe/Berlin';
        $user->column_fields["holidays"] = 'de,en_uk,fr,it,us,';
        $user->column_fields["workdays"] = '0,1,2,3,4,5,6,';
        $user->column_fields["weekstart"] = '1';
        $user->column_fields["namedays"] = '';
        $user->column_fields["reminder_interval"] = '1 Minute';
        $user->column_fields["reminder_next_time"] = date('Y-m-d H:i');
        $user->column_fields["currency_id"] = 1;
		$user->column_fields["date_format"] = 'yyyy-mm-dd';
		$user->column_fields["hour_format"] = '24';
		$user->column_fields["start_hour"] = '08:00';
		$user->column_fields["end_hour"] = '23:00';
		$user->column_fields["imagename"] = '';
		$user->column_fields["internal_mailer"] = '1';
        $user->column_fields["activity_view"] = 'This Week';	
		$user->column_fields["lead_view"] = 'Today';
		$user->column_fields["defhomeview"] = 'home_metrics';
		$std_email ="standarduser@vtigeruser.com";
        $user->column_fields["email1"] = $standarduser_email;
		//to get the role id for standard_user	
		$role_query = "SELECT roleid FROM vtiger_role WHERE rolename='Vice President'";
		$adb->database->SetFetchMode(ADODB_FETCH_ASSOC);
		$role_result = $adb->query($role_query);
		$role_id = $adb->query_result($role_result,0,"roleid");
		$user->column_fields["roleid"] = $role_id;

	    $user->save('Users');
		$std_user_id = $user->id;

	    //Inserting into vtiger_groups table
		$group1_id = $adb->getUniqueID("vtiger_users");
		$group2_id = $adb->getUniqueID("vtiger_users");
		$group3_id = $adb->getUniqueID("vtiger_users");
		
		$adb->query("insert into vtiger_groups values ('".$group1_id."','Team Selling','Group Related to Sales')");	
		$adb->query("insert into vtiger_group2role values ('".$group1_id."','H".$role4_id."')");	
		$adb->query("insert into vtiger_group2rs values ('".$group1_id."','H".$role5_id."')");	

		$adb->query("insert into vtiger_groups values ('".$group2_id."','Marketing Group','Group Related to Marketing Activities')");	
		$adb->query("insert into vtiger_group2role values ('".$group2_id."','H".$role2_id."')");	
		$adb->query("insert into vtiger_group2rs values ('".$group2_id."','H".$role3_id."')");	

		$adb->query("insert into vtiger_groups values ('".$group3_id."','Support Group','Group Related to providing Support to Customers')");
		$adb->query("insert into vtiger_group2role values ('".$group3_id."','H".$role3_id."')");
		$adb->query("insert into vtiger_group2rs values ('".$group3_id."','H".$role3_id."')");
	
		//Insert into vtiger_role2profile
		$adb->query("insert into vtiger_role2profile values ('H".$role2_id."',".$profile1_id.")");
		$adb->query("insert into vtiger_role2profile values ('H".$role3_id."',".$profile2_id.")");
	  	$adb->query("insert into vtiger_role2profile values ('H".$role4_id."',".$profile2_id.")");
		$adb->query("insert into vtiger_role2profile values ('H".$role5_id."',".$profile2_id.")"); 
	   
		// Setting user group relation for admin user
	 	$adb->pquery("insert into vtiger_users2group values (?,?)", array($group2_id, $admin_user_id));
	
		// Setting user group relation for standard user
	 	$adb->pquery("insert into vtiger_users2group values (?,?)", array($group1_id, $std_user_id));
	 	$adb->pquery("insert into vtiger_users2group values (?,?)", array($group2_id, $std_user_id));
	 	$adb->pquery("insert into vtiger_users2group values (?,?)", array($group3_id, $std_user_id));	
		
		//New Security Start
		//Inserting into vtiger_profile vtiger_table
		$adb->query("insert into vtiger_profile values ('".$profile1_id."','Administrator','Admin Profile')");	
		$adb->query("insert into vtiger_profile values ('".$profile2_id."','Sales Profile','Profile Related to Sales')");
		$adb->query("insert into vtiger_profile values ('".$profile3_id."','Support Profile','Profile Related to Support')");
		$adb->query("insert into vtiger_profile values ('".$profile4_id."','Guest Profile','Guest Profile for Test Users')");
		
		//Inserting into vtiger_profile2gloabal permissions
		$adb->query("insert into vtiger_profile2globalpermissions values ('".$profile1_id."',1,0)");
		$adb->query("insert into vtiger_profile2globalpermissions values ('".$profile1_id."',2,0)");
		$adb->query("insert into vtiger_profile2globalpermissions values ('".$profile2_id."',1,1)");
		$adb->query("insert into vtiger_profile2globalpermissions values ('".$profile2_id."',2,1)");
		$adb->query("insert into vtiger_profile2globalpermissions values ('".$profile3_id."',1,1)");
		$adb->query("insert into vtiger_profile2globalpermissions values ('".$profile3_id."',2,1)");
		$adb->query("insert into vtiger_profile2globalpermissions values ('".$profile4_id."',1,1)");
		$adb->query("insert into vtiger_profile2globalpermissions values ('".$profile4_id."',2,1)");

		//Inserting into vtiger_profile2tab
		$adb->query("insert into vtiger_profile2tab values (".$profile1_id.",1,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile1_id.",2,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile1_id.",3,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile1_id.",4,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile1_id.",6,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile1_id.",7,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile1_id.",8,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile1_id.",9,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile1_id.",10,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile1_id.",13,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile1_id.",14,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile1_id.",15,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile1_id.",16,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile1_id.",18,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile1_id.",19,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile1_id.",20,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile1_id.",21,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile1_id.",22,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile1_id.",23,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile1_id.",24,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile1_id.",25,0)");
       	$adb->query("insert into vtiger_profile2tab values (".$profile1_id.",26,0)");
       	$adb->query("insert into vtiger_profile2tab values (".$profile1_id.",27,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile1_id.",30,0)");

		//Inserting into vtiger_profile2tab
		$adb->query("insert into vtiger_profile2tab values (".$profile2_id.",1,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile2_id.",2,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile2_id.",3,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile2_id.",4,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile2_id.",6,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile2_id.",7,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile2_id.",8,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile2_id.",9,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile2_id.",10,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile2_id.",13,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile2_id.",14,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile2_id.",15,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile2_id.",16,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile2_id.",18,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile2_id.",19,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile2_id.",20,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile2_id.",21,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile2_id.",22,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile2_id.",23,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile2_id.",24,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile2_id.",25,0)");
        $adb->query("insert into vtiger_profile2tab values (".$profile2_id.",26,0)");
       	$adb->query("insert into vtiger_profile2tab values (".$profile2_id.",27,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile2_id.",30,0)");

		$adb->query("insert into vtiger_profile2tab values (".$profile3_id.",1,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile3_id.",2,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile3_id.",3,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile3_id.",4,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile3_id.",6,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile3_id.",7,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile3_id.",8,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile3_id.",9,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile3_id.",10,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile3_id.",13,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile3_id.",14,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile3_id.",15,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile3_id.",16,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile3_id.",18,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile3_id.",19,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile3_id.",20,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile3_id.",21,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile3_id.",22,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile3_id.",23,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile3_id.",24,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile3_id.",25,0)");
        $adb->query("insert into vtiger_profile2tab values (".$profile3_id.",26,0)");
       	$adb->query("insert into vtiger_profile2tab values (".$profile3_id.",27,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile3_id.",30,0)");        

		$adb->query("insert into vtiger_profile2tab values (".$profile4_id.",1,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile4_id.",2,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile4_id.",3,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile4_id.",4,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile4_id.",6,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile4_id.",7,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile4_id.",8,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile4_id.",9,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile4_id.",10,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile4_id.",13,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile4_id.",14,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile4_id.",15,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile4_id.",16,0)");	
		$adb->query("insert into vtiger_profile2tab values (".$profile4_id.",18,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile4_id.",19,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile4_id.",20,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile4_id.",21,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile4_id.",22,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile4_id.",23,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile4_id.",24,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile4_id.",25,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile4_id.",26,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile4_id.",27,0)");
		$adb->query("insert into vtiger_profile2tab values (".$profile4_id.",30,0)"); 
		//Inserting into vtiger_profile2standardpermissions  Adminsitrator
		
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",2,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",2,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",2,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",2,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",2,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",4,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",4,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",4,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",4,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",4,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",6,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",6,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",6,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",6,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",6,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",7,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",7,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",7,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",7,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",7,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",8,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",8,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",8,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",8,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",8,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",9,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",9,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",9,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",9,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",9,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",13,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",13,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",13,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",13,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",13,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",14,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",14,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",14,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",14,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",14,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",15,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",15,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",15,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",15,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",15,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",16,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",16,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",16,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",16,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",16,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",18,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",18,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",18,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",18,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",18,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",19,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",19,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",19,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",19,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",19,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",20,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",20,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",20,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",20,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",20,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",21,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",21,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",21,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",21,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",21,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",22,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",22,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",22,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",22,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",22,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",23,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",23,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",23,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",23,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",23,4,0)");

        $adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",26,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",26,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",26,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",26,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile1_id.",26,4,0)");

		//Insert into Profile 2 std permissions for Sales User  
		//Help Desk Create/Delete not allowed. Read-Only	
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",2,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",2,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",2,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",2,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",2,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",4,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",4,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",4,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",4,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",4,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",6,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",6,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",6,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",6,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",6,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",7,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",7,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",7,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",7,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",7,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",8,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",8,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",8,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",8,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",8,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",9,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",9,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",9,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",9,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",9,4,0)");
		
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",13,0,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",13,1,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",13,2,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",13,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",13,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",14,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",14,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",14,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",14,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",14,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",15,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",15,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",15,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",15,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",15,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",16,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",16,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",16,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",16,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",16,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",18,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",18,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",18,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",18,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",18,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",19,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",19,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",19,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",19,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",19,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",20,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",20,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",20,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",20,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",20,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",21,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",21,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",21,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",21,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",21,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",22,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",22,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",22,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",22,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",22,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",23,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",23,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",23,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",23,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",23,4,0)");


        	$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",26,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",26,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",26,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",26,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile2_id.",26,4,0)");

		//Inserting into vtiger_profile2std for Support Profile
		// Potential is read-only
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",2,0,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",2,1,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",2,2,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",2,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",2,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",4,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",4,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",4,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",4,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",4,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",6,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",6,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",6,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",6,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",6,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",7,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",7,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",7,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",7,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",7,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",8,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",8,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",8,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",8,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",8,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",9,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",9,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",9,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",9,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",9,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",13,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",13,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",13,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",13,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",13,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",14,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",14,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",14,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",14,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",14,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",15,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",15,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",15,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",15,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",15,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",16,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",16,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",16,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",16,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",16,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",18,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",18,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",18,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",18,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",18,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",19,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",19,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",19,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",19,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",19,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",20,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",20,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",20,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",20,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",20,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",21,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",21,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",21,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",21,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",21,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",22,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",22,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",22,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",22,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",22,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",23,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",23,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",23,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",23,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",23,4,0)");


        $adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",26,0,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",26,1,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",26,2,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",26,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile3_id.",26,4,0)");
        
		//Inserting into vtiger_profile2stdper for Profile Guest Profile
		//All Read-Only
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",2,0,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",2,1,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",2,2,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",2,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",2,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",4,0,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",4,1,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",4,2,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",4,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",4,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",6,0,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",6,1,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",6,2,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",6,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",6,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",7,0,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",7,1,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",7,2,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",7,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",7,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",8,0,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",8,1,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",8,2,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",8,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",8,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",9,0,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",9,1,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",9,2,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",9,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",9,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",13,0,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",13,1,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",13,2,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",13,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",13,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",14,0,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",14,1,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",14,2,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",14,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",14,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",15,0,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",15,1,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",15,2,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",15,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",15,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",16,0,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",16,1,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",16,2,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",16,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",16,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",18,0,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",18,1,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",18,2,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",18,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",18,4,0)");	
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",19,0,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",19,1,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",19,2,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",19,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",19,4,0)");	
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",20,0,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",20,1,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",20,2,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",20,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",20,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",21,0,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",21,1,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",21,2,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",21,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",21,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",22,0,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",22,1,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",22,2,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",22,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",22,4,0)");

		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",23,0,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",23,1,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",23,2,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",23,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",23,4,0)");	


        $adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",26,0,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",26,1,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",26,2,1)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",26,3,0)");
		$adb->query("insert into vtiger_profile2standardpermissions values (".$profile4_id.",26,4,0)");

		//Inserting into vtiger_profile 2 utility Admin
        $adb->query("insert into vtiger_profile2utility values (".$profile1_id.",2,5,0)");
        $adb->query("insert into vtiger_profile2utility values (".$profile1_id.",2,6,0)");
        $adb->query("insert into vtiger_profile2utility values (".$profile1_id.",4,5,0)");
        $adb->query("insert into vtiger_profile2utility values (".$profile1_id.",4,6,0)");
        $adb->query("insert into vtiger_profile2utility values (".$profile1_id.",6,5,0)");
        $adb->query("insert into vtiger_profile2utility values (".$profile1_id.",6,6,0)");
        $adb->query("insert into vtiger_profile2utility values (".$profile1_id.",7,5,0)");
        $adb->query("insert into vtiger_profile2utility values (".$profile1_id.",7,6,0)");
        $adb->query("insert into vtiger_profile2utility values (".$profile1_id.",8,6,0)");
       	$adb->query("insert into vtiger_profile2utility values (".$profile1_id.",7,8,0)");
        $adb->query("insert into vtiger_profile2utility values (".$profile1_id.",6,8,0)");
        $adb->query("insert into vtiger_profile2utility values (".$profile1_id.",4,8,0)");
		$adb->query("insert into vtiger_profile2utility values (".$profile1_id.",13,5,0)");
		$adb->query("insert into vtiger_profile2utility values (".$profile1_id.",13,6,0)");
        $adb->query("insert into vtiger_profile2utility values (".$profile1_id.",13,8,0)");
		$adb->query("insert into vtiger_profile2utility values (".$profile1_id.",14,5,0)");
        $adb->query("insert into vtiger_profile2utility values (".$profile1_id.",14,6,0)");
        $adb->query("insert into vtiger_profile2utility values (".$profile1_id.",7,9,0)");
		$adb->query("insert into vtiger_profile2utility values (".$profile1_id.",18,5,0)");
		$adb->query("insert into vtiger_profile2utility values (".$profile1_id.",18,6,0)");
		$adb->query("insert into vtiger_profile2utility values (".$profile1_id.",30,3,0)");
		$adb->query("insert into vtiger_profile2utility values (".$profile1_id.",7,10,0)");
        $adb->query("insert into vtiger_profile2utility values (".$profile1_id.",6,10,0)");
        $adb->query("insert into vtiger_profile2utility values (".$profile1_id.",4,10,0)");
		$adb->query("insert into vtiger_profile2utility values (".$profile1_id.",2,10,0)");
		$adb->query("insert into vtiger_profile2utility values (".$profile1_id.",13,10,0)");
		$adb->query("insert into vtiger_profile2utility values (".$profile1_id.",14,10,0)");
		$adb->query("insert into vtiger_profile2utility values (".$profile1_id.",18,10,0)");

		//Inserting into vtiger_profile2utility Sales Profile
		//Import Export Not Allowed.	
		$adb->query("insert into vtiger_profile2utility values (".$profile2_id.",2,5,1)");
        $adb->query("insert into vtiger_profile2utility values (".$profile2_id.",2,6,1)");
        $adb->query("insert into vtiger_profile2utility values (".$profile2_id.",4,5,1)");
        $adb->query("insert into vtiger_profile2utility values (".$profile2_id.",4,6,1)");
        $adb->query("insert into vtiger_profile2utility values (".$profile2_id.",6,5,1)");
        $adb->query("insert into vtiger_profile2utility values (".$profile2_id.",6,6,1)");
        $adb->query("insert into vtiger_profile2utility values (".$profile2_id.",7,5,1)");
        $adb->query("insert into vtiger_profile2utility values (".$profile2_id.",7,6,1)");
        $adb->query("insert into vtiger_profile2utility values (".$profile2_id.",8,6,1)");
       	$adb->query("insert into vtiger_profile2utility values (".$profile2_id.",7,8,0)");
        $adb->query("insert into vtiger_profile2utility values (".$profile2_id.",6,8,0)");
        $adb->query("insert into vtiger_profile2utility values (".$profile2_id.",4,8,0)");
		$adb->query("insert into vtiger_profile2utility values (".$profile2_id.",13,5,1)");
		$adb->query("insert into vtiger_profile2utility values (".$profile2_id.",13,6,1)");
        $adb->query("insert into vtiger_profile2utility values (".$profile2_id.",13,8,0)");
		$adb->query("insert into vtiger_profile2utility values (".$profile2_id.",14,5,1)");
        $adb->query("insert into vtiger_profile2utility values (".$profile2_id.",14,6,1)");
        $adb->query("insert into vtiger_profile2utility values (".$profile2_id.",7,9,0)");
		$adb->query("insert into vtiger_profile2utility values (".$profile2_id.",18,5,1)");
		$adb->query("insert into vtiger_profile2utility values (".$profile2_id.",18,6,1)");
		$adb->query("insert into vtiger_profile2utility values (".$profile2_id.",30,3,0)");
		$adb->query("insert into vtiger_profile2utility values (".$profile2_id.",7,10,0)");
        $adb->query("insert into vtiger_profile2utility values (".$profile2_id.",6,10,0)");
        $adb->query("insert into vtiger_profile2utility values (".$profile2_id.",4,10,0)");
		$adb->query("insert into vtiger_profile2utility values (".$profile2_id.",2,10,0)");
		$adb->query("insert into vtiger_profile2utility values (".$profile2_id.",13,10,0)");
		$adb->query("insert into vtiger_profile2utility values (".$profile2_id.",14,10,0)");
		$adb->query("insert into vtiger_profile2utility values (".$profile2_id.",18,10,0)");

		//Inserting into vtiger_profile2utility Support Profile
		//Import Export Not Allowed.	
		$adb->query("insert into vtiger_profile2utility values (".$profile3_id.",2,5,1)");
        $adb->query("insert into vtiger_profile2utility values (".$profile3_id.",2,6,1)");
        $adb->query("insert into vtiger_profile2utility values (".$profile3_id.",4,5,1)");
        $adb->query("insert into vtiger_profile2utility values (".$profile3_id.",4,6,1)");
        $adb->query("insert into vtiger_profile2utility values (".$profile3_id.",6,5,1)");
        $adb->query("insert into vtiger_profile2utility values (".$profile3_id.",6,6,1)");
        $adb->query("insert into vtiger_profile2utility values (".$profile3_id.",7,5,1)");
        $adb->query("insert into vtiger_profile2utility values (".$profile3_id.",7,6,1)");
        $adb->query("insert into vtiger_profile2utility values (".$profile3_id.",8,6,1)");
        $adb->query("insert into vtiger_profile2utility values (".$profile3_id.",7,8,0)");
        $adb->query("insert into vtiger_profile2utility values (".$profile3_id.",6,8,0)");
        $adb->query("insert into vtiger_profile2utility values (".$profile3_id.",4,8,0)");
		$adb->query("insert into vtiger_profile2utility values (".$profile3_id.",13,5,1)");
		$adb->query("insert into vtiger_profile2utility values (".$profile3_id.",13,6,1)");
        $adb->query("insert into vtiger_profile2utility values (".$profile3_id.",13,8,0)");
		$adb->query("insert into vtiger_profile2utility values (".$profile3_id.",14,5,1)");
        $adb->query("insert into vtiger_profile2utility values (".$profile3_id.",14,6,1)");
        $adb->query("insert into vtiger_profile2utility values (".$profile3_id.",7,9,0)");
		$adb->query("insert into vtiger_profile2utility values (".$profile3_id.",18,5,1)");
		$adb->query("insert into vtiger_profile2utility values (".$profile3_id.",18,6,1)");
		$adb->query("insert into vtiger_profile2utility values (".$profile3_id.",30,3,0)");
		$adb->query("insert into vtiger_profile2utility values (".$profile3_id.",7,10,0)");
        $adb->query("insert into vtiger_profile2utility values (".$profile3_id.",6,10,0)");
        $adb->query("insert into vtiger_profile2utility values (".$profile3_id.",4,10,0)");
		$adb->query("insert into vtiger_profile2utility values (".$profile3_id.",2,10,0)");
		$adb->query("insert into vtiger_profile2utility values (".$profile3_id.",13,10,0)");
		$adb->query("insert into vtiger_profile2utility values (".$profile3_id.",14,10,0)");
		$adb->query("insert into vtiger_profile2utility values (".$profile3_id.",18,10,0)");

		//Inserting into vtiger_profile2utility Guest Profile Read-Only
		//Import Export BusinessCar Not Allowed.	
		$adb->query("insert into vtiger_profile2utility values (".$profile4_id.",2,5,1)");
        $adb->query("insert into vtiger_profile2utility values (".$profile4_id.",2,6,1)");
        $adb->query("insert into vtiger_profile2utility values (".$profile4_id.",4,5,1)");
        $adb->query("insert into vtiger_profile2utility values (".$profile4_id.",4,6,1)");
        $adb->query("insert into vtiger_profile2utility values (".$profile4_id.",6,5,1)");
        $adb->query("insert into vtiger_profile2utility values (".$profile4_id.",6,6,1)");
        $adb->query("insert into vtiger_profile2utility values (".$profile4_id.",7,5,1)");
        $adb->query("insert into vtiger_profile2utility values (".$profile4_id.",7,6,1)");
        $adb->query("insert into vtiger_profile2utility values (".$profile4_id.",8,6,1)");
        $adb->query("insert into vtiger_profile2utility values (".$profile4_id.",7,8,1)");
        $adb->query("insert into vtiger_profile2utility values (".$profile4_id.",6,8,1)");
        $adb->query("insert into vtiger_profile2utility values (".$profile4_id.",4,8,1)");	
		$adb->query("insert into vtiger_profile2utility values (".$profile4_id.",13,5,1)");
    	$adb->query("insert into vtiger_profile2utility values (".$profile4_id.",13,6,1)");	 
        $adb->query("insert into vtiger_profile2utility values (".$profile4_id.",13,8,1)");		
		$adb->query("insert into vtiger_profile2utility values (".$profile4_id.",14,5,1)");
        $adb->query("insert into vtiger_profile2utility values (".$profile4_id.",14,6,1)");
        $adb->query("insert into vtiger_profile2utility values (".$profile4_id.",7,9,0)");
		$adb->query("insert into vtiger_profile2utility values (".$profile4_id.",18,5,1)");
		$adb->query("insert into vtiger_profile2utility values (".$profile4_id.",18,6,1)");
		$adb->query("insert into vtiger_profile2utility values (".$profile4_id.",30,3,0)");
		$adb->query("insert into vtiger_profile2utility values (".$profile4_id.",7,10,0)");
        $adb->query("insert into vtiger_profile2utility values (".$profile4_id.",6,10,0)");
        $adb->query("insert into vtiger_profile2utility values (".$profile4_id.",4,10,0)");
		$adb->query("insert into vtiger_profile2utility values (".$profile4_id.",2,10,0)");
		$adb->query("insert into vtiger_profile2utility values (".$profile4_id.",13,10,0)");
		$adb->query("insert into vtiger_profile2utility values (".$profile4_id.",14,10,0)");
		$adb->query("insert into vtiger_profile2utility values (".$profile4_id.",18,10,0)");
	
		//Creating the flat files for admin user
		createUserPrivilegesfile($admin_user_id);
		createUserSharingPrivilegesfile($admin_user_id);
		
		//Creating the flat vtiger_files for standard user
		createUserPrivilegesfile($std_user_id);
		createUserSharingPrivilegesfile($std_user_id);

		//Insert into vtiger_profile2field
		insertProfile2field($profile1_id);
        insertProfile2field($profile2_id);	
        insertProfile2field($profile3_id);	
        insertProfile2field($profile4_id);

		insert_def_org_field();	
}

//$startTime = microtime();
$modules = array("DefaultDataPopulator");
$focus=0;				
// tables creation
//eecho("Creating Core tables: ");
//$adb->setDebug(true);
$success = $adb->createTables("schema/DatabaseSchema.xml");

//Postgres8 fix - create sequences. 
//   This should be a part of "createTables" however ...
 if( $adb->dbType == "pgsql" ) {
     $sequences = array(
 	"vtiger_leadsource_seq",
 	"vtiger_accounttype_seq",
 	"vtiger_industry_seq",
 	"vtiger_leadstatus_seq",
 	"vtiger_rating_seq",
 	"vtiger_opportunity_type_seq",
 	"vtiger_salutationtype_seq",
 	"vtiger_sales_stage_seq",
 	"vtiger_ticketstatus_seq",
 	"vtiger_ticketpriorities_seq",
 	"vtiger_ticketseverities_seq",
 	"vtiger_ticketcategories_seq",
 	"vtiger_duration_minutes_seq",
 	"vtiger_eventstatus_seq",
 	"vtiger_taskstatus_seq",
 	"vtiger_taskpriority_seq",
 	"vtiger_manufacturer_seq",
 	"vtiger_productcategory_seq",
 	"vtiger_activitytype_seq",
 	"vtiger_currency_seq",
 	"vtiger_faqcategories_seq",
 	"vtiger_usageunit_seq",
 	"vtiger_glacct_seq",
 	"vtiger_quotestage_seq",
 	"vtiger_carrier_seq",
 	"vtiger_taxclass_seq",
 	"vtiger_recurringtype_seq",
 	"vtiger_faqstatus_seq",
 	"vtiger_invoicestatus_seq",
 	"vtiger_postatus_seq",
 	"vtiger_sostatus_seq",
 	"vtiger_visibility_seq",
 	"vtiger_campaigntype_seq",
 	"vtiger_campaignstatus_seq",
 	"vtiger_expectedresponse_seq",
 	"vtiger_status_seq",
 	"vtiger_activity_view_seq",
 	"vtiger_lead_view_seq",
 	"vtiger_date_format_seq",
 	"vtiger_users_seq",
 	"vtiger_role_seq",
 	"vtiger_profile_seq",
 	"vtiger_field_seq",
 	"vtiger_def_org_share_seq",
 	"vtiger_datashare_relatedmodules_seq",
 	"vtiger_relatedlists_seq",
 	"vtiger_notificationscheduler_seq",
 	"vtiger_inventorynotification_seq",
 	"vtiger_currency_info_seq",
 	"vtiger_emailtemplates_seq",
 	"vtiger_inventory_tandc_seq",
 	"vtiger_selectquery_seq",
 	"vtiger_customview_seq",
 	"vtiger_crmentity_seq",
 	"vtiger_seactivityrel_seq",
 	"vtiger_freetags_seq",
 	"vtiger_shippingtaxinfo_seq",
 	"vtiger_inventorytaxinfo_seq"
 	);
 
     foreach ($sequences as $sequence ) {
 	$log->info( "Creating sequence ".$sequence);
 	$adb->query( "CREATE SEQUENCE ".$sequence." INCREMENT BY 1 NO MAXVALUE NO MINVALUE CACHE 1;");
     }
 }


// TODO HTML
if($success==0)
	die("Error: Tables not created.  Table creation failed.\n");
elseif ($success==1)
	die("Error: Tables partially created.  Table creation failed.\n");
	//eecho("Tables Successfully created.\n");

foreach ($modules as $module ) 
{
	$focus = new $module();
	$focus->create_tables();
}
			
create_default_users_access();

// create and populate combo tables
require_once('include/PopulateComboValues.php');
$combo = new PopulateComboValues();
$combo->create_tables();
$combo->create_nonpicklist_tables();
//Writing tab data in flat file
create_tab_data_file();
create_parenttab_data_file();

//to get the users lists
$query = 'select id from vtiger_users';
$result=$adb->pquery($query,array());
for($i=0;$i<$adb->num_rows($result);$i++)
{
	$userid = $adb->query_result($result,$i,'id');

	$s1=$adb->getUniqueID("vtiger_homestuff");
	$sql="insert into vtiger_homestuff values(".$s1.",1,'Default',".$userid.",0,'Top Accounts')";
	$res=$adb->pquery($sql,array());

	$s2=$adb->getUniqueID("vtiger_homestuff");
	$sql="insert into vtiger_homestuff values(".$s2.",2,'Default',".$userid.",1,'Home Page Dashboard')";
	$res=$adb->pquery($sql,array());

	$s3=$adb->getUniqueID("vtiger_homestuff");
	$sql="insert into vtiger_homestuff values(".$s3.",3,'Default',".$userid.",0,'Top Potentials')";
	$res=$adb->pquery($sql,array());

	$s4=$adb->getUniqueID("vtiger_homestuff");
	$sql="insert into vtiger_homestuff values(".$s4.",4,'Default',".$userid.",0,'Top Quotes')";
	$res=$adb->pquery($sql,array());

	$s5=$adb->getUniqueID("vtiger_homestuff");
	$sql="insert into vtiger_homestuff values(".$s5.",5,'Default',".$userid.",0,'Key Metrics')";
	$res=$adb->pquery($sql,array());

	$s6=$adb->getUniqueID("vtiger_homestuff");
	$sql="insert into vtiger_homestuff values(".$s6.",6,'Default',".$userid.",0,'Top Trouble Tickets')";
	$res=$adb->pquery($sql,array());

	$s7=$adb->getUniqueID("vtiger_homestuff"); 
	$sql="insert into vtiger_homestuff values(".$s7.",7,'Default',".$userid.",0,'Upcoming Activities')";
	$res=$adb->pquery($sql,array());

	$s8=$adb->getUniqueID("vtiger_homestuff");
	$sql="insert into vtiger_homestuff values(".$s8.",8,'Default',".$userid.",0,'My Group Allocation')";
	$res=$adb->pquery($sql,array());

	$s9=$adb->getUniqueID("vtiger_homestuff");
	$sql="insert into vtiger_homestuff values(".$s9.",9,'Default',".$userid.",0,'Top Sales Orders')";
	$res=$adb->pquery($sql,array());

	$s10=$adb->getUniqueID("vtiger_homestuff");
	$sql="insert into vtiger_homestuff values(".$s10.",10,'Default',".$userid.",0,'Top Invoices')";
	$res=$adb->pquery($sql,array());

	$s11=$adb->getUniqueID("vtiger_homestuff");
	$sql="insert into vtiger_homestuff values(".$s11.",11,'Default',".$userid.",0,'My New Leads')";
	$res=$adb->pquery($sql,array());

	$s12=$adb->getUniqueID("vtiger_homestuff");
	$sql="insert into vtiger_homestuff values(".$s12.",12,'Default',".$userid.",0,'Top Purchase Orders')";
	$res=$adb->pquery($sql,array());

	$s13=$adb->getUniqueID("vtiger_homestuff");
	$sql="insert into vtiger_homestuff values(".$s13.",13,'Default',".$userid.",0,'Pending Activities')";
	$res=$adb->pquery($sql,array());

	$s14=$adb->getUniqueID("vtiger_homestuff");
	$sql="insert into vtiger_homestuff values(".$s14.",14,'Default',".$userid.",0,'My Recent FAQs')";
	$res=$adb->pquery($sql,array());


	$sql="insert into vtiger_homedefault values(".$s1.",'ALVT',5,'Accounts')";
	$adb->pquery($sql,array());

	$sql="insert into vtiger_homedefault values(".$s2.",'HDB',5,'Dashboard')";
	$adb->pquery($sql,array());

	$sql="insert into vtiger_homedefault values(".$s3.",'PLVT',5,'Potentials')";
	$adb->pquery($sql,array());

	$sql="insert into vtiger_homedefault values(".$s4.",'QLTQ',5,'Quotes')";
	$adb->pquery($sql,array());

	$sql="insert into vtiger_homedefault values(".$s5.",'CVLVT',5,'NULL')";
	$adb->pquery($sql,array());

	$sql="insert into vtiger_homedefault values(".$s6.",'HLT',5,'HelpDesk')";
	$adb->pquery($sql,array());

	$sql="insert into vtiger_homedefault values(".$s7.",'UA',5,'Calendar')";
	$adb->pquery($sql,array());

	$sql="insert into vtiger_homedefault values(".$s8.",'GRT',5,'NULL')";
	$adb->pquery($sql,array());

	$sql="insert into vtiger_homedefault values(".$s9.",'OLTSO',5,'SalesOrder')";
	$adb->pquery($sql,array());

	$sql="insert into vtiger_homedefault values(".$s10.",'ILTI',5,'Invoice')";
	$adb->pquery($sql,array());

	$sql="insert into vtiger_homedefault values(".$s11.",'MNL',5,'Leads')";
	$adb->pquery($sql,array());

	$sql="insert into vtiger_homedefault values(".$s12.",'OLTPO',5,'PurchaseOrder')";
	$adb->pquery($sql,array());

	$sql="insert into vtiger_homedefault values(".$s13.",'PA',5,'Calendar')";
	$adb->pquery($sql,array());

	$sql="insert into vtiger_homedefault values(".$s14.",'LTFAQ',5,'Faq')";
	$adb->pquery($sql,array());
}

// default report population
require_once('modules/Reports/PopulateReports.php');

// default customview population
require_once('modules/CustomView/PopulateCustomView.php');

// ensure required sequences are created (adodb creates them as needed, but if
// creation occurs within a transaction we get problems
$db->getUniqueID("vtiger_crmentity");
$db->getUniqueID("vtiger_seactivityrel");
$db->getUniqueID("vtiger_freetags");

//Master currency population
//Insert into vtiger_currency vtiger_table
$db->pquery("insert into vtiger_currency_info values(?,?,?,?,?,?,?,?)", array($db->getUniqueID("vtiger_currency_info"),$currency_name,$currency_code,$currency_symbol,1,'Active','-11','0'));

// Register All the Events
registerEvents($adb);

// Register All the Entity Methods
registerEntityMethods($adb);

// Populate Default Workflows
populateDefaultWorkflows($adb);

// Populate Links
populateLinks();

// Run the performance scripts based on the database type and the vtiger version.
require_once('modules/Migration/versions.php');
if($adb->isMySQL()) {
	@include_once('modules/Migration/Performance/'.$current_version.'_mysql.php');
} elseif($adb->isPostgres()) {
	@include_once('modules/Migration/Performance/'.$current_version.'_postgres.php');		
}
	
// populate the db with seed data
if ($db_populate) {
	//eecho ("Populate seed data into $db_name");
	include("install/populateSeedData.php");
}

// Register all the events here
function registerEvents($adb) {
	require_once('include/events/include.inc');
	$em = new VTEventsManager($adb);

	// Registering event for Recurring Invoices
	$em->registerHandler('vtiger.entity.aftersave', 'modules/SalesOrder/RecurringInvoiceHandler.php', 'RecurringInvoiceHandler');
	
	// Workflow manager
	$em->registerHandler('vtiger.entity.aftersave', 'modules/com_vtiger_workflow/VTEventHandler.inc', 'VTWorkflowEventHandler');
	
	//Document Handler -saves File information
	$em->registerHandler('vtiger.entity.aftersave', 'modules/Documents/AttachFile.php', 'Attachfile');
		
}

// Register all the entity methods here
function registerEntityMethods($adb) {
	require_once("modules/com_vtiger_workflow/include.inc");
	require_once("modules/com_vtiger_workflow/tasks/VTEntityMethodTask.inc");
	require_once("modules/com_vtiger_workflow/VTEntityMethodManager.inc");
	$emm = new VTEntityMethodManager($adb);
	
	// Registering method for Updating Inventory Stock
	$emm->addEntityMethod("SalesOrder","UpdateInventory","include/InventoryHandler.php","handleInventoryProductRel");//Adding EntityMethod for Updating Products data after creating SalesOrder
	$emm->addEntityMethod("Invoice","UpdateInventory","include/InventoryHandler.php","handleInventoryProductRel");//Adding EntityMethod for Updating Products data after creating Invoice
}

function populateDefaultWorkflows($adb) {
	require_once("modules/com_vtiger_workflow/include.inc");
	require_once("modules/com_vtiger_workflow/tasks/VTEntityMethodTask.inc");
	require_once("modules/com_vtiger_workflow/VTEntityMethodManager.inc");

	// Creating Workflow for Updating Inventory Stock for Invoice
	$vtWorkFlow = new VTWorkflowManager($adb);
	$invWorkFlow = $vtWorkFlow->newWorkFlow("Invoice");
	$invWorkFlow->test = '[{"fieldname":"subject","operation":"does not contain","value":"`!`"}]';
	$invWorkFlow->description = "UpdateInventoryProducts On Every Save";
	$vtWorkFlow->save($invWorkFlow);

	$tm = new VTTaskManager($adb);
	$task = $tm->createTask('VTEntityMethodTask', $invWorkFlow->id);
	$task->active=true;
	$task->methodName = "UpdateInventory";
	$tm->saveTask($task);
}

// Function to populate Links
function populateLinks() {
	include_once('vtlib/Vtiger/Module.php');
	
	// Links for Accounts module
	$moduleInstance = Vtiger_Module::getInstance('Accounts');
	// Detail View Custom link
	$moduleInstance->addLink('DETAILVIEW', 'LBL_SHOW_ACCOUNT_HIERARCHY', 'index.php?module=Accounts&action=AccountHierarchy&accountid=$RECORD$');
}

?>
