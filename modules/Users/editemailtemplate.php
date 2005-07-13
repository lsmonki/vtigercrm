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
require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Users/UserInfoUtil.php');
require_once('include/database/PearDatabase.php');
global $mod_strings;
global $app_strings;
global $app_list_strings;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');
global $vtlog;

if(isset($_REQUEST['templateid']) && $_REQUEST['templateid']!='')
{
	$templateid = $_REQUEST['templateid'];
	$vtlog->logthis("the templateid is set to the value ".$templateid,'debug');  
}
$sql = "select * from emailtemplates where templateid=".$templateid;
$result = $adb->query($sql);
$emailtemplateResult = $adb->fetch_array($result);

$xtpl=new XTemplate ('modules/Users/createemailtemplate.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("THEME", $theme);
$xtpl->assign("FOLDERNAME", $emailtemplateResult["foldername"]);
$xtpl->assign("TEMPLATENAME", $emailtemplateResult["templatename"]);
$xtpl->assign("TEMPLATEID", $emailtemplateResult["templateid"]);
$xtpl->assign("DESCRIPTION", $emailtemplateResult["description"]);
$xtpl->assign("SUBJECT", $emailtemplateResult["subject"]);
$xtpl->assign("BODY", $emailtemplateResult["body"]);

$xtpl->parse("main");
$xtpl->out("main");
?>
