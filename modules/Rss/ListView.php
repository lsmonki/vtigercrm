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
require_once("data/Tracker.php");
require_once('Smarty_setup.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('include/ListView/ListView.php');
require_once('include/utils/utils.php');
require_once('include/utils/utils.php');
require_once('modules/Rss/Rss.php');
global $app_strings;
global $app_list_strings;
global $mod_strings;

$current_module_strings = return_module_language($current_language, 'Rss');
$log = LoggerManager::getLogger('rss_list');

global $currentModule;
global $image_path;
global $theme;
global $cache_dir;
// focus_list is the means of passing data to a ListView.
global $focus_list;

if(isset($_REQUEST[record]))
{
	$recordid = $_REQUEST[record];
}

$rss_form = new vtigerCRM_Smarty;
$rss_form->assign("MOD", $mod_strings);
$rss_form->assign("APP", $app_strings);
$rss_form->assign("IMAGEPATH",$image_path);

//<<<<<<<<<<<<<<lastrss>>>>>>>>>>>>>>>>>>//
//$url = 'http://forums/rss.php?name=forums&file=rss';
//$url = 'http://forums/weblog_rss.php?w=202';
$oRss = new vtigerRSS();
if(isset($_REQUEST[record]))
{
    $recordid = $_REQUEST[record];
	$url = $oRss->getRssUrlfromId($recordid);
	if($oRss->setRSSUrl($url))
	{
        	$rss_html = $oRss->getSelectedRssHTML($recordid);
	}else
	{
        	$rss_html = "<strong>No RSS Feeds are selected</strong>";
	}
	$rss_form->assign("TITLE",gerRssTitle($recordid));
}else
{
	$rss_html = $oRss->getStarredRssHTML();
}
if($currentModule == "Rss")
{
	require_once("modules/".$currentModule."/Forms.php");
	if (function_exists('get_rssfeeds_form'))
	{
		$rss_form->assign("RSSFEEDS_TITLE","<img src='".$image_path."rssroot.gif' align='absmiddle'/>&nbsp;<a href='javascript:openPopUp(\"addRssFeedIns\",this,\"index.php?action=Popup&module=Rss\",\"addRssFeedWin\",350,150,\"menubar=no,toolbar=no,location=no,status=no,scrollbars=yes,resizable=yes\");' title='".$app_strings['LBL_ADD_RSS_FEEDS']."'>Add RSS Feed</a>");
		$rss_form->assign("RSSFEEDS", get_rssfeeds_form());
	}
}


$rss_form->assign("RSSDETAILS",$rss_html);
//<<<<<<<<<<<<<<lastrss>>>>>>>>>>>>>>>>>>//

$rss_form->display("Rss.tpl");
function gerRssTitle($id)
{
	global $adb;
	$query = 'select * from rss where rssid ='.$id;	 
	$result = $adb->query($query);	
	$title = $adb->query_result($result,0,'rsstitle');
	return $title;
	
}
?>
