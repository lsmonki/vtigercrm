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
 * $Header:  vtiger_crm/sugarcrm/modules/Users/Logout.php,v 1.5 2004/12/30 06:57:47 jack Exp $
 * Description:  TODO: To be written.
 ********************************************************************************/

require_once('include/logging.php');
require_once('database/DatabaseConnection.php');
require_once('modules/Users/LoginHistory.php');
require_once('modules/Users/User.php');


// Recording Logout Info
	$usip=$_SERVER['REMOTE_ADDR'];
        $outtime=date("Y/m/d H:i:s");
        $loghistory=new LoginHistory();
        $loghistory->user_logout($current_user->user_name,$usip,$outtime);


$local_log =& LoggerManager::getLogger('Logout');

//Calendar Logout
//include('modules/Calendar/logout.php');

// clear out the autthenticating flag
session_destroy();

define("IN_LOGIN", true);
	
 define('IN_PHPBB', true);
 include($phpbb_root_path . 'extension.inc');
 include($phpbb_root_path . 'common.'.$phpEx);

//
// Set page ID for session management
//
$userdata = session_pagestart($user_ip, PAGE_LOGIN);
init_userprefs($userdata);
//
// End session management
//

// session id check
if (!empty($HTTP_POST_VARS['sid']) || !empty($HTTP_GET_VARS['sid']))
{
        $sid = (!empty($HTTP_POST_VARS['sid'])) ? $HTTP_POST_VARS['sid'] : $HTTP_GET_VARS['sid'];
}
else
{
        $sid = '';
}
if( $userdata['session_logged_in'] )
	{
		if( $userdata['session_logged_in'] )
		{
			session_end($userdata['session_id'], $userdata['user_id']);
		}

	}

// go to the login screen.
header("Location: index.php?action=Login&module=Users");
?>
