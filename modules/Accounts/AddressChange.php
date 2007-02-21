<?php
/*********************************************************************************

** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/
require_once('include/database/PearDatabase.php');
require_once('data/CRMEntity.php');
require_once('modules/Accounts/Accounts.php');
require_once('modules/Contacts/Contacts.php');
require_once('include/utils/utils.php');
require_once('user_privileges/default_module_view.php');
global $adb;
global $log;
//When changing the Account Address Information  it should also change the related contact address --Dinakaran
$record = $_REQUEST['record'];
$sql ="select vtiger_account.accountid,vtiger_accountbillads.street as billingstreet, vtiger_accountbillads.city as billingcity,vtiger_accountbillads.code as billingcode,vtiger_accountbillads.country as billingcountry,vtiger_accountbillads.state as billingstate,vtiger_accountbillads.pobox as billingpobox ,vtiger_accountshipads.* from vtiger_account inner join vtiger_accountbillads on vtiger_accountbillads.accountaddressid=vtiger_account.accountid inner join vtiger_accountshipads on vtiger_accountshipads.accountaddressid = vtiger_account.accountid where accountid=".$record;
//$sql ="select vtiger_account.accountid,vtiger_accountbillads.* ,vtiger_accountshipads.* from vtiger_accountbillads,vtiger_accountshipads,vtiger_account where accountid =".$record;
$result = $adb->query($sql);
$value = $adb->fetch_row($result);

if(($_REQUEST['bill_city'] != $value['billingcity'])  ||  $_REQUEST['bill_street'] != $value['billingstreet']  ||  $_REQUEST['bill_country']!=$value['billingcountry']  ||  $_REQUEST['bill_code']!=$value['billingcode']  ||  $_REQUEST['bill_pobox']!=$value['billingpobox']   ||  $_REQUEST['bill_state']!=$value['billingstate']   ||  $_REQUEST['ship_country']!=$value['country']  ||  $_REQUEST['ship_city']!=$value['city']  ||  $_REQUEST['ship_state']!=$value['state']  ||  $_REQUEST['ship_code']!=$value['code']   ||  $_REQUEST['ship_street']!=$value['street']   ||  $_REQUEST['ship_pobox']!=$value['pobox'] )
{
	$sql1="select contactid from vtiger_contactdetails where accountid=".$record;
	$result1 = $adb->query($sql1);
        if($adb->num_rows($result1) > 0)
	{
		echo 'address_change';
	}
	else
	{
		echo 'No Changes';
	}
}
else
{
	echo 'No Changes';
}
?>
