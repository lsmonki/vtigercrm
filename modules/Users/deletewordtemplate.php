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
//include('include/database/PearDatabase.php');
global $adb;
$filename = $_REQUEST["filename"];
$sql = "delete from wordtemplates where filename = '".$filename ."'";
$adb->query($sql);

header("Location:index.php?module=Users&action=listwordtemplates");


?>


