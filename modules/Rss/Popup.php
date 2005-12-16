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
require_once("data/Tracker.php");
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('include/utils/utils.php');
require_once('modules/Rss/Rss.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;
$current_module_strings = return_module_language($current_language, 'Rss');
global $urlPrefix;
$log = LoggerManager::getLogger('rss_save');
global $currentModule;
global $image_path;
global $theme;

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

// focus_list is the means of passing data to a ListView.
global $focus_list;

if(isset($_REQUEST["rssurl"]))
{
	$newRssUrl = $_REQUEST["rssurl"];
	$rsscategory = $_REQUEST["rsscategory"];
	$setstarred = $_REQUEST["setstarred"];
	
	if($setstarred != 1)
	{
		$setstarred = 0;
	}
	$oRss = new vtigerRSS();
	if($oRss->setRSSUrl($newRssUrl))
	{
        	if($oRss->saveRSSUrl($newRssUrl,$setstarred,$rsscategory) == false)
        	{
			echo "<font color='red'><b>Unable to save the RSS Feed URL</b></font><br>" ;
        	}else
        	{
			$jscript = "window.opener.location.href=window.opener.location.href;
				    window.self.close();";
        	}
	}else
	{
		echo "<font color='red'><b>Not a valid RSS Feed URL</b></font><br>" ;

	}
}
function getRsscategory_html()
{
	$oRss = new vtigerRSS();
	$rsscategory = $oRss->getRsscategory();
	//print_r($rsscategory);
	if(isset($rsscategory)) 
	{
		for($i=0;$i<count($rsscategory);$i++)
		{
			$shtml .= "<option value=\"$rsscategory[$i]\">$rsscategory[$i]</option>";
		}
	}
	return $shtml;
}
$save_rss_form=new XTemplate ("modules/Rss/Popup.html");
$save_rss_form->assign("MOD", $mod_strings);
$save_rss_form->assign("APP", $app_strings);
$save_rss_form->assign("IMAGE_PATH",$image_path);
$save_rss_form->assign("THEME_PATH",$theme_path);
$save_rss_form->assign("JAVASCRIPT",$jscript);
$save_rss_form->assign("RSS_CATEGORY",getRsscategory_html());
$save_rss_form->parse("main");
$save_rss_form->out("main");
?>
