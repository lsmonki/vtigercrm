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
 * $Header:  vtiger_crm/sugarcrm/modules/Users/Logout.php,v 1.1 2004/08/17 15:06:40 gjayakrishnan Exp $
 * Description:  TODO: To be written.
 ********************************************************************************/

require_once('include/logging.php');
require_once('database/DatabaseConnection.php');
require_once('modules/Users/LoginHistory.php');
require_once('modules/Users/User.php');


// Recording Logout Info
	$usip=$_SERVER['SERVER_ADDR'];
        $outtime=date("Y/m/d H:i:s");
        $loghistory=new LoginHistory();
        $loghistory->user_logout($current_user->user_name,$usip,$outtime);

$local_log =& LoggerManager::getLogger('Logout');

// clear out the autthenticating flag
session_destroy();

// go to the login screen.
header("Location: index.php?action=Login&module=Users");
?>
