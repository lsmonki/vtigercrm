<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *********************************************************************************/
global $app_strings, $mod_strings, $current_language, $currentModule, $theme;
global $list_max_entries_per_page;

require_once('Smarty_setup.php');
require_once('include/ListView/ListView.php');
require_once('modules/CustomView/CustomView.php');
require_once('include/DatabaseUtil.php');

checkFileAccess("modules/$currentModule/$currentModule.php");
require_once("modules/$currentModule/$currentModule.php");

// Custom View
$customView = new CustomView($currentModule);
$viewid = $customView->getViewId($currentModule);
$customview_html = $customView->getCustomViewCombo($viewid);
$viewinfo = $customView->getCustomViewByCvid($viewid);

if($viewid ==0){
	echo "<table border='0' cellpadding='5' cellspacing='0' width='100%' height='450px'><tr><td align='center'>";
	echo "<div style='border: 3px solid rgb(153, 153, 153); background-color: rgb(255, 255, 255); width: 55%; position: relative; z-index: 10000000;'>

		<table border='0' cellpadding='5' cellspacing='0' width='98%'>
		<tbody><tr>
		<td rowspan='2' width='11%'><img src='". vtiger_imageurl('denied.gif', $theme) ."' ></td>
		<td style='border-bottom: 1px solid rgb(204, 204, 204);' nowrap='nowrap' width='70%'><span clas
		s='genHeaderSmall'>$app_strings[LBL_PERMISSION]</span></td>
		</tr>
		<tr>
		<td class='small' align='right' nowrap='nowrap'>
		<a href='javascript:window.history.back();'>$app_strings[LBL_GO_BACK]</a><br>
		</td>
		</tr>
		</tbody></table>
		</div>";
	echo "</td></tr></table>";
	exit;
}

if($viewid != "0"){
	$listquery = getListQuery($currentModule);
	$list_query= $customView->getModifiedCvListQuery($viewid, $listquery, $currentModule);
}else{
	$list_query = getListQuery($currentModule);
}

// Enabling Module Search
$url_string = '';
if($_REQUEST['query'] == 'true') {
	if(!empty($_REQUEST['globalSearch'])){
		$searchValue = vtlib_purify($_REQUEST['globalSearchText']);
		$where = '(' . getUnifiedWhere($list_query,$currentModule,$searchValue) . ')';
		$url_string .= '&query=true&globalSearch=true&globalSearchText='.$searchValue;
	}else{
		list($where, $ustring) = split('#@@#', getWhereCondition($currentModule));
		$url_string .= "&query=true$ustring";
	}
}
//print_r($where);
if($where != '') {
	$list_query = "$list_query AND $where";
	$_SESSION['export_where'] = $where;
}else{
	unset($_SESSION['export_where']);
}
// Sorting
if($order_by) {
	if($order_by == 'smownerid'){
		if( $adb->dbType == "pgsql"){
			$list_query .= ' GROUP BY user_name';
		}
		$list_query .= ' ORDER BY user_name '.$sorder;
	}else {
		$tablename = getTableNameForField($currentModule, $order_by);
		$tablename = ($tablename != '')? ($tablename . '.') : '';
		if( $adb->dbType == "pgsql"){
			$list_query .= ' GROUP BY '. $tablename . $order_by;
		}
		$list_query .= ' ORDER BY ' . $tablename . $order_by . ' ' . $sorder;
	}
}

$count_result = $adb->query(mkCountQuery($list_query));
$noofrows = $adb->query_result($count_result,0,"count");

$pageNumber = ceil($noofrows/$list_max_entries_per_page);
if($pageNumber == 0){
	$pageNumber = 1;
}
echo $app_strings['LBL_LIST_OF'].' '.$pageNumber;
?>