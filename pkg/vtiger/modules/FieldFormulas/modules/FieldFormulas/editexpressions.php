<?php
require_once("Smarty_setup.php");
require_once("include/utils/CommonUtils.php");
require_once("include/utils/utils.php");

function vtGetModules($adb) {
	$modules = com_vtGetModules($adb);
	return $modules;
}

function vtEditExpressions($adb, $appStrings, $current_language, $image_path, $formodule='') {
	$smarty = new vtigerCRM_Smarty();
	$smarty->assign('APP', $appStrings);
	
	$smarty->assign("UMOD", return_module_language($current_language,'FieldFormulas'));
	$smarty->assign("MOD", return_module_language($current_language,'Settings'));
	$smarty->assign("IMAGE_PATH",$image_path);
	$smarty->assign("MODULE_NAME", 'FieldFormulas');
	$smarty->assign("PAGE_NAME", 'Field Formulas');
	$smarty->assign("PAGE_TITLE", 'Field Formulas');
	$smarty->assign("FORMODULE", $formodule);
	
	$smarty->display(vtlib_getModuleTemplate('FieldFormulas', 'EditExpressions.tpl'));
}
	
$modules = vtGetModules($adb);
if(vtlib_isModuleActive('FieldFormulas') && in_array($_REQUEST['formodule'],$modules)) {
	vtEditExpressions($adb, $app_strings, $current_language, $image_path, $_REQUEST['formodule']);
} else {
	echo "<table border='0' cellpadding='5' cellspacing='0' width='100%' height='450px'><tr><td align='center'>";
	echo "<div style='border: 3px solid rgb(153, 153, 153); background-color: rgb(255, 255, 255); width: 80%; position: relative; z-index: 10000000;'>

	<table border='0' cellpadding='5' cellspacing='0' width='98%'>
	<tbody><tr>
	<td rowspan='2' width='11%'><img src='". vtiger_imageurl('denied.gif', $theme) ."' ></td>
	<td style='border-bottom: 1px solid rgb(204, 204, 204);' nowrap='nowrap' width='70%'><span class='genHeaderSmall'>".$app_strings['LBL_PERMISSION']." </span></td>
	</tr>
	<tr>
	<td class='small' align='right' nowrap='nowrap'>			   	
	<a href='javascript:window.history.back();'>$app_strings[LBL_BACK]</a><br></td>
	</tr>
	</tbody></table> 
	</div>";
	echo "</td></tr></table>";die;
}
		
?>
