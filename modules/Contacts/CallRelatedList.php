<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/

require_once('Smarty_setup.php');
require_once('modules/Contacts/Contact.php');
require_once('include/utils/utils.php');

$focus = new Contact();
$currentmodule = $_REQUEST['module'];
$RECORD = $_REQUEST['record'];

if(isset($_REQUEST['record']) && isset($_REQUEST['record'])) {
    $focus->retrieve_entity_info($_REQUEST['record'],"Contacts");
    $focus->id = $_REQUEST['record'];
    $focus->name=$focus->column_fields['firstname'].' '.$focus->column_fields['lastname'];

$log->debug("id is ".$focus->id);

$log->debug("name is ".$focus->name);

}

global $adb;
$sql = $adb->query('select accountid from contactdetails where contactid='.$focus->id);
$accountid = $adb->query_result($sql,0,'accountid');
if($accountid == 0) $accountid='';

$sql1 = $adb->query('select campaignid from contactdetails where contactid='.$focus->id);
$campaignid = $adb->query_result($sql1,0,'campaignid');
if($campaignid == 0) $campaignid='';

$smarty = new vtigerCRM_Smarty;
$smarty->assign("accountid",$accountid);
$smarty->assign("campaignid",$campaignid);
	

if(isset($_request['isduplicate']) && $_request['isduplicate'] == 'true') {
        $focus->id = "";
}
$parent_email = getEmailParentsList('Contacts',$_REQUEST['record']);
        $smarty->assign("HIDDEN_PARENTS_LIST",$parent_email);
$category = getparenttab();
$smarty->assign("CATEGORY",$category);

$smarty->assign("id",$focus->id);
$smarty->assign("NAME",$focus->name);
$related_array = getrelatedlists("Contacts",$focus);
$smarty->assign("RELATEDLISTS", $related_array);
$smarty->assign("MODULE",$currentmodule);
$smarty->assign("SINGLE_MOD","Contact");
$smarty->assign("ID",$record );
$smarty->display("RelatedLists.tpl");
?>
