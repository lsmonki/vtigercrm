<?php
/**
 * Created on 09-Oct-08
 * this file saves the tooltip information
 */
$fieldid = $_REQUEST['fieldid'];
$sequence = 1;

deleteOldInfo($fieldid);
echo SaveTooltipInformation($fieldid, $sequence);



/**
 * this function saves the tooltip information
 * @param integer $fieldid - the fieldid of the field
 * @param integer $view - the current view :: 1 by default
 * @param integer $sequence - the starting sequence 
 */
function SaveTooltipInformation($fieldid, $sequence, $view=1){
	global $adb;

	if(empty($fieldid)){
		return "FAILURE";
	}else{
		$checkedFields = array();
		if(!empty($_REQUEST['checkedFields'])){
			$checkedFields = explode(",",$_REQUEST['checkedFields']);
			//add to vtiger_quickview table
			foreach($checkedFields as $checkedField){
				$query = "insert into vtiger_quickview values ($fieldid, $checkedField, $sequence, $view)";
				$adb->pquery($query,array());
				$sequence++;
			}
		}
		$data = getDetailViewForTooltip($fieldid, $checkedFields);
		return $data;
	}
}

/**
 * this function deletes the old information present in quickview table for that view for that field
 * @param integer $fieldid - the fieldid of the field
 * @param integer $view - the view for which ot remove :: 1 by default
 */
function deleteOldInfo($fieldid, $view=1){
	global $adb;
	//remove from the table
	$query = "delete from vtiger_quickview where fieldid = $fieldid and view = $view";
	$adb->pquery($query,array());
}

/**
 * this function returns the detailview for tooltip
 * @param integer $fieldid - the fieldid of the field for which you want the detailview
 * @param array $checkedFields - the fields which are selected to be displayed in quickview
 * @return string $data - the formatted quickview data
 */
function getDetailViewForTooltip($fieldid, $checkedFields){
	require_once('Smarty_setup.php');
	global $app_strings;
	$labels = array();
	$checkedFields = implode(",",$checkedFields);
	if(!empty($checkedFields)){
		$labels = getFieldLabels($checkedFields);
	}
	$smarty = new vtigerCRM_Smarty;
	$smarty->assign("FIELDID", $fieldid);
	$smarty->assign("APP",$app_strings);
	$smarty->assign("IMAGES", "themes/images/");
	$smarty->assign("LABELS", $labels);
	$smarty->assign("COUNT", count($labels));
	
	$data = $smarty->fetch("QuickView/DetailQuickView.tpl");
	return $data;
}

/**
 * this function accepts the fieldids array and returns an array of field labels for them
 * @param array $checkedFields - the fieldids array
 * @return array $data - the fieldlabels array
 */
function getFieldLabels($checkedFields){
	global $adb;
	$data = array();
	
	$sql = "select * from vtiger_field where fieldid in ($checkedFields)";
	$result = $adb->query($sql);
	$count = $adb->num_rows($result);
	
	for($i=0;$i<$count;$i++){
		$data[] = $adb->query_result($result, $i, "fieldlabel");
	}
	return $data;
}
?>
