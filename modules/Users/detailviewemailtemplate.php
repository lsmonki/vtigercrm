<?php

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Users/UserInfoUtil.php');

global $mod_strings;
global $app_strings;
global $app_list_strings;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');
$xtpl=new XTemplate ('modules/Users/detailviewemailtemplate.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("THEME", $theme);
$result = fetchEmailTemplateInfo($_REQUEST['templatename']);

$emailtemplateResult = mysql_fetch_array($result);

$xtpl->assign("FOLDERNAME", $emailtemplateResult["foldername"]);

$xtpl->assign("TEMPLATENAME", $emailtemplateResult["templatename"]);
$xtpl->assign("DESCRIPTION", $emailtemplateResult["description"]);

$xtpl->assign("SUBJECT", $emailtemplateResult["subject"]);
$xtpl->assign("BODY", $emailtemplateResult["body"]);

$xtpl->parse("main");
$xtpl->out("main");



?>






