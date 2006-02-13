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
require_once('modules/Rss/Rss.php');
require_once('include/logging.php');

if (isset($_REQUEST["rssurl"])) $newRssUrl = $_REQUEST["rssurl"];


if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = $_REQUEST['return_module'];
else $return_module = "Rss";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = $_REQUEST['return_action'];
else $return_action = "index";

$oRss = new vtigerRSS();
if($oRss->setRSSUrl($newRssUrl))
{
	if($oRss->saveRSSUrl($newRssUrl) == false)
	{
          echo "Unable to save the Url";	
	}else
	{
	  //header("Location: index.php?module=$return_module&action=$return_action");
	}
}else
{
	echo "Not a valid RSS URL";
}
//header("Location: index.php?module=$return_module&action=$return_action");
?>
