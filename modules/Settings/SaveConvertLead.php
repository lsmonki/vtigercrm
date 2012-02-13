<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/

require_once('include/utils/utils.php');
require_once('Smarty_setup.php');

$smarty=new vtigerCRM_Smarty;
$sql="SELECT * from vtiger_convertleadmapping";
$result = $adb->pquery($sql, array());
$noofrows = $adb->num_rows($result);

for($i=0;$i<$noofrows;$i++)
{
	$cfmid=$adb->query_result($result,$i,"cfmid");

	$accountfid=vtlib_purify($_REQUEST[$cfmid.'_Accounts']);
	$contactfid=vtlib_purify($_REQUEST[$cfmid.'_Contacts']);
	$potentialfid=vtlib_purify($_REQUEST[$cfmid.'_Potentials']);

	$update_sql="UPDATE vtiger_convertleadmapping SET accountfid=?, contactfid=?, potentialfid=? WHERE cfmid=?";
	$update_params = array($accountfid, $contactfid, $potentialfid, $cfmid);
	$adb->pquery($update_sql, $update_params);
}

header("Location: index.php?action=CustomFieldList&module=Settings&parenttab=Settings");
?>