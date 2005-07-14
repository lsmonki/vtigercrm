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

require_once('include/database/PearDatabase.php');
require_once('modules/Users/UserInfoUtil.php');
require_once('include/utils.php');
global $adb;
$fld_module = $_REQUEST['fld_module'];
$profileid = $_REQUEST['profileid'];
$fieldListResult = getProfile2FieldList($fld_module, $profileid);
$noofrows = $adb->num_rows($fieldListResult);
$tab_id = getTabid($fld_module);
for($i=0; $i<$noofrows; $i++)
{
	$fieldid =  $adb->query_result($fieldListResult,$i,"fieldid");
	//echo 'fieldid '.$fieldid;
		$visible = $_REQUEST[$fieldid];
		//echo '      visible   '.$visible;
		//echo '<BR>';
		if($visible == 'on')
		{
			$visible_value = 0;
		}
		else
		{
			$visible_value = 1;
		}
		//Updating the Mandatory fields
		$uitype = $adb->query_result($fieldListResult,$i,"uitype");
                if($uitype == 2 || $uitype == 51 || $uitype == 6 || $uitype == 22 || $uitype == 73 || $uitype == 24 || $uitype == 81 || $uitype == 50 || $uitype == 23 || $uitype == 16)
                {
                       $visible_value = 0; 
                }		
	
		//Updating the database
		$update_query = "update profile2field set visible=".$visible_value." where fieldid='".$fieldid."' and profileid=".$profileid." and tabid=".$tab_id;
		/*
		   echo '<BR>';
		   echo $update_query;
		   echo '<BR>';
		*/
		$adb->query($update_query);

}
$loc = "Location: index.php?action=ListFieldPermissions&module=Users&fld_module=".$fld_module."&profileid=".$profileid;
header($loc);

?>
