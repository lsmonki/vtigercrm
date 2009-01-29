<?php
require_once("Smarty_setup.php");
require_once("include/utils/CommonUtils.php");
require_once("include/events/SqlResultIterator.inc");


	function vtGetModules($adb){
		$sql="select distinct vtiger_field.tabid, name 
			from vtiger_field 
			inner join vtiger_tab 
				on vtiger_field.tabid=vtiger_tab.tabid 
			where vtiger_field.tabid not in(9,10,16,15,8,29) and vtiger_tab.presence =0";
		$it = new SqlResultIterator($adb, $adb->query($sql));
		$modules = array();
		foreach($it as $row){
			if(isPermitted($row->name,'index') == "yes") {
				$modules[$row->name] = getTranslatedString($row->name);
			}
		}
		return $modules;
	}


	function vtEditExpressions($adb, $appStrings, $current_language, $image_path){
		$smarty = new vtigerCRM_Smarty();
		$smarty->assign('APP', $appStrings);
		$modules = vtGetModules($adb);
		$smarty->assign('MODULES', $modules);
		
		$smarty->assign("UMOD", return_module_language($current_language,'FieldFormulas'));
		$smarty->assign("MOD", return_module_language($current_language,'Settings'));
		$smarty->assign("IMAGE_PATH",$image_path);
		$smarty->assign("MODULE_NAME", 'FieldFormulas');
		$smarty->assign("PAGE_NAME", 'Field Formulas');
		$smarty->assign("PAGE_TITLE", 'Field Formulas');
		
		$smarty->display(vtlib_getModuleTemplate('FieldFormulas', 'EditExpressions.tpl'));
	}
	
	vtEditExpressions($adb, $app_strings, $current_language, $image_path);
?>
