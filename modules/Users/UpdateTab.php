<?

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
require_once('modules/Users/User.php');
require_once('include/database/PearDatabase.php');
global $current_user;
global $theme;
global $default_language;

global $app_strings;
global $mod_strings;

$focus = new User();

if(isset($_REQUEST['record'])) {
	$focus->retrieve($_REQUEST['record']);
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
} 

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("User detail view");

$xtpl=new XTemplate ('modules/Users/UpdateTab.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id());
$xtpl->assign("ID", $focus->id);
$xtpl->assign("USER_NAME", $focus->user_name);
$xtpl->assign("FIRST_NAME", $focus->first_name);
$xtpl->assign("LAST_NAME", $focus->last_name);
$xtpl->assign("STATUS", $focus->status);
$xtpl->assign("YAHOO_ID", $focus->yahoo_id);
if (isset($focus->yahoo_id) && $focus->yahoo_id !== "") $xtpl->assign("YAHOO_MESSENGER", "<a href='http://edit.yahoo.com/config/send_webmesg?.target=".$focus->yahoo_id."'><img border=0 src='http://opi.yahoo.com/online?u=".$focus->yahoo_id."'&m=g&t=2'></a>");
$xtpl->parse("main");
$xtpl->out("main");

if ((is_admin($current_user) || $_REQUEST['record'] == $current_user->id) && $focus->is_admin == 'on') {
	$xtpl->assign("IS_ADMIN", "checked");
	$xtpl->parse("user_settings");
	$xtpl->out("user_settings");
}

$sql_set = "update tab set presence=1 where presence !=2";
$adb->query($sql_set);
$j=1;
foreach($_POST as $name=>$value)
{
  $sql="update tab set presence=0,tabsequence= " . $j . " where tabid = " .$value;
  $adb->query($sql);
  $j++;
}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td class="formHeader"><?php echo $mod_strings['LBL_CUSTOMISE_TABS']; ?> </td>
    </tr>
  </table>
<br><b><center><?php echo $mod_strings['LBL_TAB_MENU_UPDATED']; ?><a href="index.php?module=Users&action=DetailView&record=<?php echo $focus->id ?>">My Account</a><?php echo $mod_strings['LBL_TO_VIEW_CHANGES']; ?></center></b><br><br>

