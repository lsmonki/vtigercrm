<?php
include_once('vtlib/Vtiger/Rss/Parser.php');

global $app_strings, $mod_strings, $theme, $currentModule;


$ftimeout = 60;
$fparser = new Vtiger_RSS_Parser();
$fparser->fetch('http://www.vtiger.com/products/crm/newsfeed.php', $ftimeout);
$items = $fparser->items();
$NEWSLIST = Array();
foreach($items as $item) {
	$NEWSLIST[] = $item;
}

require_once('Smarty_setup.php');
$smarty = new vtigerCRM_Smarty;
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMAGE_PATH", "themes/$theme/images/");
$smarty->assign('NEWSLIST', $NEWSLIST);
$smarty->display("HomeNews.tpl");
?>

