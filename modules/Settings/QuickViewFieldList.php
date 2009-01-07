<?php
/**
 * Created on 05-Oct-08
 * this field returns the fields in a module
 */

require_once 'include/utils/utils.php';
require_once 'include/utils/TooltipUtils.php';

echo QuickViewFieldList();
function QuickViewFieldList(){
	global $adb, $app_strings;
	
	if(empty($_REQUEST['fld_module'])){
		return false;
	}else{
		$module = $_REQUEST['fld_module'];
		$query = "select * from vtiger_tab where name='$module'";
		$result = $adb->pquery($query,array());

		if($adb->num_rows($result)>0){
			$tabid = $adb->query_result($result,0,"tabid");
			
			$query = "select * from vtiger_field where tabid = $tabid and columnname not like 'imagename' and uitype not in (61, 122)";
			$result = $adb->pquery($query,array());
			if($adb->num_rows($result)>0){
				$fieldlist = '<select onchange="getRelatedFieldInfo(this)" class="importBox" id="pick_field" name="pick_field">';
				$fieldlist.= 	'<option value="" disabled="true" selected>'
									.$app_strings['LBL_SELECT'].' Field
								</option>';
				while($fieldsinfo=$adb->fetch_array($result)){
					$fieldlabel = $fieldsinfo['fieldlabel'];
					$fieldname = $fieldsinfo['fieldname'];
					$fieldlist.= "<option value='$fieldname'>$fieldlabel</option>";
				}
				$fieldlist.= '</select>';
				return $fieldlist;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
}
?>
