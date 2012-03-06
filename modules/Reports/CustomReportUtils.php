<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

require_once 'modules/Reports/ReportRun.php';
require_once 'include/ChartUtils.php';
require_once 'include/utils/CommonUtils.php';

Class CustomReportUtils {

	public static function getCustomReportsQuery($reportid, $filterlist=null) {
		global $current_user;
		$reportnew = new ReportRun($reportid);
		$groupby = $reportnew->getGroupingList($reportid);
		$showcharts = false;
		if (!empty($groupby)) {
			$showcharts = true;
		}
		$reportQuery = $reportnew->sGetSQLforReport($reportid, $filterlist, 'HTML', $showcharts);
		return $reportQuery;
	}

	public static function getReportChart($reportid, $chartType) {
		global $log, $adb;
		$oReportRun = new ReportRun($reportid);
		$groupBy = $oReportRun->getGroupingList($reportid);
		foreach ($groupBy as $key => $value) {
			// $groupByConditon = explode(" ",$value);
			//$groupByNew = explode("'",$groupByConditon[0]);
			list($tablename, $colname, $module_field, $fieldname, $single) = split(":", $key);
			list($module, $field) = split("_", $module_field);
			$fieldDetails = $key;
			break;
		}
		$queryReports = self::getCustomReportsQuery($reportid);

		$queryResult = $adb->pquery($queryReports, array());
		//ChartUtils::generateChartDataFromReports($queryResult, strtolower($groupByNew[1]));
		if ($chartType == 'horizontalbarchart') {
			$Chart = ChartUtils::getReportBarChart($queryResult, strtolower($module_field), $fieldDetails, $reportid);
		}
		if ($chartType == 'verticalbarchart') {
			$Chart = ChartUtils::getReportBarChart($queryResult, strtolower($module_field), $fieldDetails, $reportid, 'vertical');
		} else if ($chartType == 'piechart')
			$Chart = ChartUtils::getReportPieChart($queryResult, strtolower($module_field), $fieldDetails, $reportid);
		return $Chart;
	}

	public static function IsDateField($reportColDetails) {
		list($tablename, $colname, $module_field, $fieldname, $typeOfData) = split(":", $reportColDetails);
		if ($typeOfData == "D") {
			return true;
		} else {
			return false;
		}
	}

	public static function getAdvanceSearchCondition($fieldDetails, $criteria, $fieldvalue) {
		list($tablename, $colname, $module_field, $fieldname, $single) = split(":", $fieldDetails);
		list($module, $field) = split("_", $module_field);
		list($year, $month, $day) = split("-", $fieldvalue);
		$grteqCondition = 'h';
		$eqCondition = 'e';
		$lessCondititon = 'l';
		$json = new Zend_Json();
		$advft_criteria_groups = array('1' => array('groupcondition' => null));
		if (empty($fieldvalue)) {
			$condition = 'advft_criteria=' . $advft_criteria . '&advft_criteria_groups=' . $advft_criteria_groups . '&searchtype=advance&matchtype=any';
			return $condition;
		}
		if (strtolower($criteria) == 'year') {
			$firstDate = DateTimeField::convertToUserFormat($year);
			$secondDate = DateTimeField::convertToUserFormat($year + 1);
			$condition = array(
				array(
					'groupid' => 1,
					'columnname' => $tablename . ':' . $colname . ':' . $colname . ':' . $module_field . ':' . $single,
					'comparator' => $grteqCondition,
					'value' => $firstDate,
					'columncondition' => 'and'
				),
				array(
					'groupid' => 1,
					'columnname' => $tablename . ':' . $colname . ':' . $colname . ':' . $module_field . ':' . $single,
					'comparator' => $lessCondititon,
					'value' => $secondDate,
					'columncondition' => 'and'
				)
			);
			$conditionJson = urlencode($json->encode($condition));
			$condition = "searchtype=advance&matchtype=any&advft_criteria=" . $conditionJson . "&advft_criteria_groups=" . urlencode($json->encode($advft_criteria_groups));
		} else if (strtolower($criteria) == 'month') {
			$date = DateTimeField::convertToUserFormat($year . "-" . $month);
			$endMonth = $month + 1;
			if ($endMonth < 10) {
				$endMonth = "0" . $endMonth;
			}
			$endDate = DateTimeField::convertToUserFormat($year . "-" . $endMonth . "-01");
			$condition = array(
				array(
					'groupid' => 1,
					'columnname' => $tablename . ':' . $colname . ':' . $colname . ':' . $module_field . ':' . $single,
					'comparator' => $grteqCondition,
					'value' => $date,
					'columncondition' => 'and'
				),
				array(
					'groupid' => 1,
					'columnname' => $tablename . ':' . $colname . ':' . $colname . ':' . $module_field . ':' . $single,
					'comparator' => $lessCondititon,
					'value' => $endDate,
					'columncondition' => 'and'
				)
			);
			$conditionJson = urlencode($json->encode($condition));
			$condition = "searchtype=advance&matchtype=any&advft_criteria=" . $conditionJson . "&advft_criteria_groups=" . urlencode($json->encode($advft_criteria_groups));
		} else if (strtolower($criteria) == 'quarter') {
			$condition = "";
			$quraterNum = $month / 3;
			if ($month % 3 == 0)
				$quraterNum = $quraterNum - 1;
			$startingMonth = 3 * ($quraterNum);
			$quarterMonth = $startingMonth;
			if ($quarterMonth < 10) {
				$quarterMonth = "0" . $quarterMonth;
			}
			$date = DateTimeField::convertToUserFormat($year . "-" . $quarterMonth . "-01");
			$quarterMonth +=3;
			if ($quarterMonth < 10) {
				$quarterMonth = "0" . $quarterMonth;
			}
			$date1 = DateTimeField::convertToUserFormat($year . "-" . $quarterMonth . "-01");
			$condition = array(
				array(
					'groupid' => 1,
					'columnname' => $tablename . ':' . $colname . ':' . $colname . ':' . $module_field . ':' . $single,
					'comparator' => $grteqCondition,
					'value' => $date,
					'columncondition' => 'and'
				),
				array(
					'groupid' => 1,
					'columnname' => $tablename . ':' . $colname . ':' . $colname . ':' . $module_field . ':' . $single,
					'comparator' => $lessCondititon,
					'value' => $date1,
					'columncondition' => 'and'
				)
			);
			$conditionJson = urlencode($json->encode($condition));
			$condition = "searchtype=advance&matchtype=any&advft_criteria=" . $conditionJson . "&advft_criteria_groups=" . urlencode($json->encode($advft_criteria_groups));
		} elseif (strtolower($criteria) == 'none') {
			$date = DateTimeField::convertToUserFormat($fieldvalue);
			$condition = array(
				array(
					'groupid' => 1,
					'columnname' => $tablename . ':' . $colname . ':' . $colname . ':' . $module_field . ':' . $single,
					'comparator' => $grteqCondition,
					'value' => $date,
					'columncondition' => 'and'
				)
			);
			$conditionJson = urlencode($json->encode($condition));
			$condition = "searchtype=advance&matchtype=any&advft_criteria=" . $conditionJson . "&advft_criteria_groups=" . urlencode($json->encode($advft_criteria_groups));
		}
		return $condition;
	}

	public static function getXAxisDateFieldValue($dateFieldValue, $criteria) {
		global $log;
		$timeStamp = strtotime($dateFieldValue);
		$year = date('Y', $timeStamp);
		$month = date('m', $timeStamp);
		$day = date('d', $timeStamp);
		$xaxisLabel = "";
		if (strtolower($criteria) == 'year') {
			$xaxisLabel = "Year $year";
		} else if (strtolower($criteria) == 'month') {
			$monthLabel = date('M', $timeStamp);
			$xaxisLabel = "$monthLabel $year";
		} else if (strtolower($criteria) == "quarter") {
			$monthNum = date('n', $timeStamp);
			$quarter = (($monthNum - 1) / 3) + 1;
			$textNumArray = array('', 'I', 'II', 'III', 'IV');
			$textNum = $textNumArray[$quarter];
			$xaxisLabel = $textNumArray[$quarter] . " Quarter of " . $year;
		} else if (strtolower($criteria) == 'none') {
			$xaxisLabel = DateTimeField::convertToUserFormat($dateFieldValue);
		}
		return $xaxisLabel;
	}

	public static function getCustomEntityId($entityname, $modules) {
		global $adb;
		//$entityname = str_replace(" "," ",$entityname);
		foreach ($modules as $referenceModule) {
			$query = "SELECT * FROM vtiger_entityname WHERE  modulename=?";
			$result = $adb->pquery($query, array($referenceModule));
			$tablename = $adb->query_result($result, 0, 'tablename');
			$fieldName = $adb->query_result($result, 0, 'fieldname');
			//$nameComp = explode(',',$fieldName);
			//if(count($nameComp)>1){
			//	$whereField = "contact(".$fieldName
			//}
			$entityField = $adb->query_result($result, 0, 'entityidfield');
			$entityQuery = "SELECT $entityField FROM $tablename WHERE concat(" . $fieldName . ")=?";
			$entityResult = $adb->pquery($entityQuery, array($entityname));
			$num_rows = $adb->num_rows($entityResult);
			if ($num_rows > 0) {
				return $referenceModule;
			}
		}
	}

}

?>
