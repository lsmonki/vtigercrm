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
global $adb;

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$ticketid = $_REQUEST['record'];
$query="select title,update_log from troubletickets where ticketid=".$ticketid;
$result=$adb->query($query);
$update_log = $adb->query_result($result,0,"update_log");
$splitval = split('--//--',$update_log); 
$noofelements= sizeof($splitval);
$outHistory='';
for($i=0;$i<$noofelements;$i++)
{
	
	$outHistory .= '<tr>';
	$outHistory .= '<TD  class="dataLabel" width="50%" noWrap ><div align="left">'.$splitval[$i].'</div></TD></tr>';
	$i++;
	$outHistory .= '<tr><TD  width="50%" noWrap ><div align="left">'.$splitval[$i].'</div></TD>';
	$outHistory .= '</tr>';
}


$xtpl=new XTemplate ('modules/HelpDesk/TicketHistory.html');
if ($noofelements > 15)
{
	$xtpl->assign('SCROLLSTART','<div style="overflow:auto;height:315px;width:100%;">');
	$xtpl->assign('SCROLLSTOP','</div>');
}

$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$xtpl->assign("TICKETTITLE", $adb->query_result($result,0,"title"));
$xtpl->assign("TICKETHISTORY", $outHistory);

$xtpl->parse("main");

$xtpl->out("main");
?>
