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

require_once('include/utils/UserInfoUtil.php');
global $adb;
$del_id =  $_REQUEST['groupId'];

//Updating the user2 role table
deleteGroup($del_id);

header("Location: index.php?action=listgroups&module=Users");
?>
