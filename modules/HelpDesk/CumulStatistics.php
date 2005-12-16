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
require_once('XTemplate/xtpl.php');
require_once('include/utils/utils.php');
require_once('TicketStatisticsUtil.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

echo get_module_title("HelpDesk", $mod_strings['LBL_TICKETS'].": ".$mod_strings['LBL_STATISTICS'] , true);
echo '<br>';

$totTickets = getTotalNoofTickets();
if($totTickets == 0)
{
	$singleUnit = 0;
}
else
{
	$singleUnit = 80/$totTickets;
}
$totOpenTickets = getTotalNoofOpenTickets();
$totClosedTickets = getTotalNoofClosedTickets();

$xtpl=new XTemplate ('modules/HelpDesk/CumulStatistics.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

if(isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if(isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if(isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);

$xtpl->assign("ALLOPEN", outBar($totOpenTickets, $image_path, $singleUnit));
$xtpl->assign("ALLCLOSED", outBar($totClosedTickets, $image_path, $singleUnit));
$xtpl->assign("ALLTOTAL", outBar($totTickets, $image_path, $singleUnit));
$xtpl->assign("PRIORITIES", showPriorities($image_path, $singleUnit)); 
$xtpl->assign("CATEGORIES", showCategories($image_path, $singleUnit)); 
$xtpl->assign("USERS", showUserBased($image_path, $singleUnit)); 

$xtpl->parse("main");

$xtpl->out("main");

?>
