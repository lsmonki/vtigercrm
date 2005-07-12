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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Contacts/Delete.php,v 1.9 2005/04/18 10:37:49 samk Exp $
 * Description:  TODO: To be written.
 ********************************************************************************/

require_once('modules/Contacts/Contact.php');
global $mod_strings;

require_once('include/logging.php');
$log = LoggerManager::getLogger('contact_delete');

$focus = new Contact();

if(!isset($_REQUEST['record']))
	die($mod_strings['ERR_DELETE_RECORD']);

if($_REQUEST['return_module'] == 'Accounts')
{
        $sql = 'update crmentity set deleted = 1 where crmid = '.$_REQUEST['record'];
        $adb->query($sql);
}
if($_REQUEST['return_module'] == 'Potentials' && $_REQUEST['record'] != '' && $_REQUEST['return_id'] != '')
{
	$sql = 'delete from contpotentialrel where contactid='.$_REQUEST['record'].' and potentialid='.$_REQUEST['return_id'];
	$adb->query($sql);
}
if($_REQUEST['record'] != '' && $_REQUEST['return_id'] != '')
{
	$sql = 'delete from seactivityrel where crmid = '.$_REQUEST['record'].' and activityid = '.$_REQUEST['return_id'];
	$adb->query($sql);
$sql_recentviewed ='delete from tracker where user_id = '.$current_user->id.' and item_id = '.$_REQUEST['record'];
$adb->query($sql_recentviewed);
}
if($_REQUEST['return_module'] == 'Products')
{
	$sql = 'delete from vendorcontactrel where contactid='.$_REQUEST['record'].' and vendorid='.$_REQUEST['return_id'];
	$adb->query($sql);
}
if($_REQUEST['return_module'] == $_REQUEST['module'])
	$focus->mark_deleted($_REQUEST['record']);

header("Location: index.php?module=".$_REQUEST['return_module']."&action=".$_REQUEST['return_action']."&activity_mode=".$_REQUEST['activity_mode']."&record=".$_REQUEST['return_id']);
?>
