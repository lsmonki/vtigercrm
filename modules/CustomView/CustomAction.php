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
require_once('database/DatabaseConnection.php');
require_once('XTemplate/xtpl.php');
require_once('include/utils/utils.php');
require_once('modules/CustomView/CustomView.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$module = $_REQUEST['module'];
$cvid = $_REQUEST['record'];

$xtpl= new XTemplate('modules/CustomView/CustomAction.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("IMAGE_PATH",$image_path);
$xtpl->assign("MODULE",$module);
$xtpl->assign("CVMODULE",$module);
$xtpl->assign("CVID",$cvid);
$oCustomView = new CustomView();
$CADtls = $oCustomView->getCustomActionDetails($cvid);
//print_r($CADtls);
if(isset($CADtls))
{
$xtpl->assign("SUBJECT",$CADtls['subject']);
$xtpl->assign("BODY",$CADtls['content']);
$xtpl->assign("MODE","edit");
}else
{
$xtpl->assign("MODE","new");	
}
$xtpl->parse("main");
$xtpl->out("main");
?>





