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

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

//Retreiving the ticket id
if(isset($_REQUEST['id']))	$faqid = $_REQUEST['id'];
else				$faqid = $_REQUEST['record'];

//Retreiving the ticket info from database
$query = "select * from faq where id='".$faqid."'";
$faqresult = $adb->query($query);

$user_id = $adb->query_result($faqresult,0,'author_id');

$user_query = "select user_name from users where id='".$user_id."'";
$user_result = $adb->query($user_query);
$user_name = $adb->query_result($user_result,0,'user_name');

$xtpl=new XTemplate ('modules/HelpDesk/CreateFaq.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != '')
{
	$xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
}
else
{
	$xtpl->assign("RETURN_MODULE", "HelpDesk");
}
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != '')
{
	$xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
}
else
{
	$xtpl->assign("RETURN_ACTION", "FaqInfoView");
}
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != '')
{
	$xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
}
else
{
	$xtpl->assign("RETURN_ID", $faqid);
}


$xtpl->assign("ID", $faqid);
$xtpl->assign("THEME", $theme);
$xtpl->assign("MODE", "Edit");
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);


$question_val = $adb->query_result($faqresult,0,'question');
$answer_val = $adb->query_result($faqresult,0,'answer');
$comment_val = $adb->query_result($faqresult,0,'comments');
$category_val = $adb->query_result($faqresult,0,'category');


//Assigning the combo values
$xtpl->assign("CATEGORYOPTIONS",getComboValues("category","faqcategories","category_name","1",$category_val));

$xtpl->assign("QUESTION", $adb->query_result($faqresult,0,'question'));
$xtpl->assign("ANSWER", $adb->query_result($faqresult,0,'answer'));
$xtpl->assign("COMMENTS", $adb->query_result($faqresult,0,'comments'));

$xtpl->parse("main");

$xtpl->out("main");

?>
