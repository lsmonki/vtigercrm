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

echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].' : Assign Module Owners', true);

global $mod_strings, $app_strings;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

$module_array = getModuleNameList();

$user_array = get_user_array();
//echo '<pre>';print_r($user_array);echo '</pre>';

$row = 1;
foreach($module_array as $val)
{
	if ($row%2==0)
        {
                $trowclass = 'evenListRow';
        }
        else
        {
                $trowclass = 'oddListRow';
        }
        $row ++;

	//get the user array as a combo list
	$user_combo = getUserCombo($user_array,$val);

	$user_list .= '<tr class="'.$trowclass.'"><td nowrap width="20%" height="21" style="padding:0px 3px 0px 3px;">'.$val.' : </td>';
	$user_list .= '<td width="20%" style="padding:0px 3px 0px 3px;">'.$user_combo.'</td></tr>';

	//To add the modules as single string to pass as hidden value
	$modules .= $val.'&&&';
}


require_once($theme_path.'layout_utils.php');

$log->info("Settings Module Owners view");

$xtpl=new XTemplate ('modules/Settings/EditModuleOwners.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("MODULE", "Settings");

$xtpl->assign("MODULES_LIST", $modules);

$xtpl->assign("USER_LIST", $user_list);

$xtpl->parse("main");

$xtpl->out("main");

function getUserCombo($user_array,$name)
{
	global $adb;

	$tabid = getTabid($name);
	$sql = "select * from moduleowners where tabid=".$tabid;
	$res = $adb->query($sql);
	$db_userid = $adb->query_result($res,0,'user_id');
	
	//Form the user combo list for each module
	$combo_name = "user_".strtolower($name);
	$user_combo = '<select name="'.$combo_name.'">';
	foreach($user_array as $user_id => $user_name)
	{
		$selected = '';
		if($user_id == $db_userid)
		{	
			$selected = 'selected';
		}
		$user_combo .= '<OPTION value="'.$user_id.'" '.$selected.'>'.$user_name.'</OPTION>';
	}
	$user_combo .= '</select>';

	return $user_combo;
}
function getModuleNameList()
{
        global $adb;

        $sql = "select moduleowners.*, tab.name from moduleowners inner join tab on moduleowners.tabid = tab.tabid order by tab.tabsequence";
        $res = $adb->query($sql);
        $mod_array = Array();
        while($row = $adb->fetchByAssoc($res))
        {
                array_push($mod_array,$row['name']);
        }
        //echo '<pre>';print_r($mod_array);echo '</pre>';

        return $mod_array;
}
?>
