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
require_once('HelpDeskUtil.php');
require_once('XTemplate/xtpl.php');
require_once('include/utils.php');

//Retreiving the id from the request:
$faqid = $_REQUEST['record'];

//Retreiving the faq info from database
$query = "select * from faq where id='".$faqid."'";
$faqresult = $adb->query($query);

$user_id = $adb->query_result($faqresult,0,'author_id');

$user_query = "select user_name from users where id='".$user_id."'"; 
$user_result = $adb->query($user_query);
$user_name = $adb->query_result($user_result,0,'user_name');

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$xtpl=new XTemplate ('modules/HelpDesk/FaqInfoView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);

$xtpl->assign("CATEGORYVALUE", $adb->query_result($faqresult,0,'category'));
$xtpl->assign("RETURN_MODULE", $_REQUEST['HelpDesk']);
$xtpl->assign("ID", $adb->query_result($faqresult,0,'id'));
$xtpl->assign("AUTHORNAME", $user_name);
$xtpl->assign("QUESTION", $adb->query_result($faqresult,0,'question'));
$xtpl->assign("ANSWER", $adb->query_result($faqresult,0,'answer'));
$xtpl->assign("COMMENT", $adb->query_result($faqresult,0,'comments'));

$xtpl->parse("main");
$xtpl->out("main");

?>
