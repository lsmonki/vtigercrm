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
 * $Header: /cvsroot/vtigercrm/vtiger_crm/modules/Calendar/Save.php,v 1.4 2005/01/17 05:11:26 saraj Exp $
 * Description:  Saves an Account record and then redirects the browser to the 
 * defined return URL.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/Calendar/UserCalendar.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('modules/Calendar/webelements.p3');
require_once('modules/Calendar/Date.pinc');
#require_once('database/DatabaseConnection.php');

//Added for Appointment Validation
 global $callink,$mod_strings;
 $callink = "index.php?module=Calendar&action=";

  //$pref = new preference();
if ( ! isset($_REQUEST['gotourl']) ) {
   $gotourl= $callink ."app_new";
 } else {
   $gotourl=$_REQUEST['gotourl'] ;
 }
 $msg = "";
if ( isset($_REQUEST['creator']) ) {
   $gotourl= addUrlParameter($gotourl,"creator=".$_REQUEST['creator'],true);
 }
 if ( isset($_REQUEST['t_ignore']) ) {
   $_REQUEST['t_ignore'] = 1;
 } else {
   $_REQUEST['t_ignore'] = 0;
 }
 
 $gotourl= addUrlParameter($gotourl,"t_ignore=".$_REQUEST['t_ignore'],true);

 # Set the Start and End time

 $start = new DateTime();
 $start->setDateTimeF("start");
 $start->getTimeStamp();
 if ( ( !$start->checkDMY()) || (-1 == $start->getTimeStamp() ) ) {
   $msg .= sprintf($mod_strings['LBL_APP_ERR001'],$mod_strings['LBL_APP_START_DATE']) ."<br>";
 } else {
   $gotourl= addUrlParameter($gotourl,"start=".$start->getYYYYMMDDHHMM(),true);
   $_REQUEST['a_start'] = sprintf ("'%04d-%02d-%02d %02d:%02d:%02d'",$start->year,$start->month,$start->day,$start->hour,$start->min,$start->sec);
   $start_ts = $start->getYYYYMMDDHHMM();
 }

 $end = new DateTime();
 $end->setDateTimeF("end");
 if ( ( !$end->checkDMY()) || (-1 == $end->getTimeStamp()) ) {
   $msg .= sprintf($mod_strings['LBL_APP_ERR001'],$mod_strings['LBL_APP_END_DATE']) ."<br>";
 } else {
   $gotourl= addUrlParameter($gotourl,"end=".$end->getYYYYMMDDHHMM(),true);
   $_REQUEST['a_end'] = sprintf ("'%04d-%02d-%02d %02d:%02d:%02d'",$end->year,$end->month,$end->day,$end->hour,$end->min,$end->sec);
   $end_ts = $end->getYYYYMMDDHHMM();
 }

 #
 # Checks
 #
 if ($start_ts > $end_ts) {
   # Start after End
   $msg .= $mod_strings['LBL_APP_ERR002'] ."<br>";
 }

 #
 # Subject
 #
 if ( !isset($_REQUEST['subject']) || $_REQUEST['subject'] == "") {
   $msg .= $mod_strings['LBL_APP_ERR004'] ."<br>";
 }
 else {
      $gotourl= addUrlParameter($gotourl,"subject=".$_REQUEST['subject'],true);
 }
 #
 # Contact
 #
 if ((!isset($_REQUEST['contact_name'])) || ($_REQUEST['contact_name'] == " ")) {
   $msg .= $mod_strings['LBL_APP_ERR003']."<br>";
 }
 else
 {
      $gotourl= addUrlParameter($gotourl,"contact_name=".$_REQUEST['contact_name'],true);
      $gotourl= addUrlParameter($gotourl,"contact_id=".$_REQUEST['contact_id'],true);
 } 
 #
 # OUTSIDE
 #
 if ( !isset($_REQUEST['outside']) ) {
   $_REQUEST['outside'] = 0;
 }  
 $gotourl= addUrlParameter($gotourl,"outside=".$_REQUEST['outside'],true);
 #
 # DESCRIPTION
 #
 $gotourl = addUrlParameter($gotourl,"descr=". UrlEncode($_REQUEST['descr']),true);
 #

 ##################################################
 # End of Checks
 ##################################################
 if ( $msg == "" ) {

   /* Save and Go back to calendar */
  $local_log =& LoggerManager::getLogger('index');

  $focus = new UserCalendar();
  #$focus->retrieve($_REQUEST['creator']);

  $update = isset($_REQUEST['id']);

  foreach($focus->column_fields as $field)
  {
   	if($update && $field=='creator')
	{
		continue;
	}

	if(isset($_REQUEST[$field]))
	{
		$value = $_REQUEST[$field];
		$focus->$field = $value;
	}
		
  }

  foreach($focus->additional_column_fields as $field)
  {
	if(isset($_REQUEST[$field]))
	{
		$value = $_REQUEST[$field];
		$focus->$field = $value;
		
	}
  }

	$focus->save();
	$return_id = $focus->id;

	if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = $_REQUEST['return_module'];
	else $return_module = "Accounts";
	if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = $_REQUEST['return_action'];
	else $return_action = "DetailView";
	if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = $_REQUEST['return_id'];

	$local_log->debug("Saved record with id of ".$return_id);

	$gotourl ="index.php?action=$return_action&module=$return_module&record=$return_id";

 }
 $gotourl = addMessage($gotourl,$msg,true);
 $gotourl = addSessionKey($gotourl,true);
 
 Header("Status: 302 Moved Temporarily");
 Header("Location: " . $gotourl);

?>
