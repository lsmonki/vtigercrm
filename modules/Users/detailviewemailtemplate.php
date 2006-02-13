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
$xtpl=new XTemplate ('modules/Users/detailviewemailtemplate.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("THEME", $theme);

if(isset($_REQUEST['templateid']) && $_REQUEST['templateid']!='')
{
	$tempid = $_REQUEST['templateid'];
	$sql = "select * from emailtemplates where templateid=".$tempid;
	$result = $adb->query($sql);
/*	$temprow = $adb->fetch_array($result);
	$cnt=1;
	$selcount = $_REQUEST['templatename'];	
	do
	{
	  if ($cnt == $selcount)
  	  {
	      $templatename = $temprow["templatename"]; 
  	  }
	  $cnt++;
	}while($temprow = $adb->fetch_array($result));

	$result = fetchEmailTemplateInfo($templatename);
*/
//
	$emailtemplateResult = $adb->fetch_array($result);
}
$xtpl->assign("FOLDERNAME", $emailtemplateResult["foldername"]);

$xtpl->assign("TEMPLATENAME", $emailtemplateResult["templatename"]);
$xtpl->assign("DESCRIPTION", $emailtemplateResult["description"]);
$xtpl->assign("TEMPLATEID", $emailtemplateResult["templateid"]);

$xtpl->assign("SUBJECT", $emailtemplateResult["subject"]);
$xtpl->assign("BODY", nl2br($emailtemplateResult["body"]));

$xtpl->parse("main");
$xtpl->out("main");



?>






