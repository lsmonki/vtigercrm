<?php

require_once('Smarty_setup.php');
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

global $app_strings;
global $mod_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Portal');
global $adb;

$query="select * from portal";
$result=$adb->query($query);
$no_of_portals=$adb->num_rows($result);
$portal_info=array();
for($i=0 ; $i<$no_of_portals; $i++)
{
	$portalname = $adb->query_result($result,$i,'portalname');
	$portalurl = $adb->query_result($result,$i,'portalurl');
	$portal_array['portalname'] = $portalname;
	$portal_array['portalurl'] = $portalurl;
	$portal_info[]=$portal_array;
}
//echo '<pre>';print_r($portal_array);echo '</pre>';
$smarty = new vtigerCRM_Smarty;

$smarty->assign("IMAGEPATH", $image_path);
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("PORTALS", $portal_info);
$smarty->display("Portal.tpl");
?>
