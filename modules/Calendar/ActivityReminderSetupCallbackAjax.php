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

global $app_strings;
global $currentModule,$image_path,$theme,$adb, $current_user;

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

require_once('Smarty_setup.php');
require_once("data/Tracker.php");
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/database/PearDatabase.php');
require_once('include/utils/utils.php');

require_once('modules/Leads/Leads.php');
require_once('modules/Contacts/Contacts.php');

$log = LoggerManager::getLogger('Activity_Reminder');
$smarty = new vtigerCRM_Smarty;

$cbmodule = $_REQUEST['cbmodule'];
$cbrecord = $_REQUEST['cbrecord'];
$cbaction = $_REQUEST['cbaction'];

if($cbaction == 'POSTPONE') {
	if(isset($cbmodule) && isset($cbrecord)) {

		//$cbincr = $_REQUEST['cbincr'];

		$reminderidres = $adb->pquery("SELECT * FROM vtiger_activity_reminder_popup WHERE semodule = ? and recordid = ?",array(mysql_real_escape_string($cbmodule),mysql_real_escape_string($cbrecord)));
	
		$reminderid = null;
		if($adb->num_rows($reminderidres) > 0) {
			$reminderid = $adb->query_result($reminderidres, 0, "reminderid");
		}

		if(isset($reminderid) )//&& isset($cbincr) && $cbincr != '') 
		{
			$reminder_query = "UPDATE vtiger_activity_reminder_popup set status = 0 WHERE reminderid = $reminderid";
			$adb->query($reminder_query);
			echo ":#:SUCCESS";
		} else {
			echo ":#:FAILURE";			
		}
		
	}
}

?>
