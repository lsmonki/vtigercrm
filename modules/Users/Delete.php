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


$sql= 'delete from salesmanactivityrel where smid='.$_REQUEST['record'].' and activityid = '.$_REQUEST['return_id'];
$adb->query($sql);

if($_REQUEST['return_module'] == 'Activities')
	$mode ='&activity_mode=Events';

header("Location: index.php?module=".$_REQUEST['return_module']."&action=".$_REQUEST['return_action'].$mode."&record=".$_REQUEST['return_id']);
?>
