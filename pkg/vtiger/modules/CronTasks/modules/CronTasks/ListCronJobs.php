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

require_once('Smarty_setup.php');
require_once('vtlib/Vtiger/Cron.php');
require_once ('include/utils/utils.php');

global $theme,$currentModule;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
$smarty = new vtigerCRM_Smarty;
$cronTasks = Vtiger_Cron::listAllActiveInstances(1);
$output = Array();

foreach ($cronTasks as $cronTask) {
	$out = Array();
	$cron_id = $cronTask->getId();
	$cron_mod = $cronTask->getName();
	$cron_freq = $cronTask->getFrequency();
	$cron_st = $cronTask->getStatus();
	if($cronTask->getLastStart() != 0) {
		$start_ts = $cronTask->getLastStartDateTime();
		$end_ts = new DateTimeField(date("Y-m-d H:i:s"));
 	    $end_ts = $end_ts->getDBInsertDateTimeValue();
 	    $difference = dateDiff($start_ts, $end_ts);
 	    $years = $difference['years'];
 	    $months = $difference['months'];
 	    $days = $difference['days'];
 	    $hours = $difference['hours'];
 	    $minutes = $difference['minutes'];
 	    $seconds = $difference['seconds'];
 	    if($years == 0 && $months == 0 && $days == 0 && $hours == 0 && $minutes == 0){
		    $cron_started = "$seconds ".getTranslatedString('LBL_SECONDS',$currentModule);
 	    }else if($years == 0 && $months == 0 && $days == 0 && $hours == 0 ){
		    $cron_started = "$minutes ".getTranslatedString('LBL_MINUTES',$currentModule);
 	    }else if($years == 0 && $months == 0 && $days == 0 ){
			$cron_started = "$hours ".getTranslatedString('LBL_HOURS',$currentModule);
		}else if($years == 0 && $months == 0 ){
			$cron_started = "$days ".getTranslatedString('LBL_DAYS',$currentModule);
		}else if($years == 0 ){
			$cron_started = "$months ".getTranslatedString('LBL_MONTHS',$currentModule);
		}else {
			$cron_started = "$years ".getTranslatedString('LBL_YEARS',$currentModule);
		}
	}
	else {
		$cron_started = '';
	}
	if($cronTask->getLastEnd() != 0) {
		$start_ts = $cronTask->getLastEndDateTime();
		$end_ts = new DateTimeField(date("Y-m-d H:i:s"));
 	    $end_ts = $end_ts->getDBInsertDateTimeValue();
 	    $difference = dateDiff($start_ts, $end_ts);
 	    $years = $difference['years'];
 	    $months = $difference['months'];
 	    $days = $difference['days'];
 	    $hours = $difference['hours'];
 	    $minutes = $difference['minutes'];
 	    $seconds = $difference['seconds'];
 	    if($years == 0 && $months == 0 && $days == 0 && $hours == 0 && $minutes == 0){
		    $cron_end = "$seconds ".getTranslatedString('LBL_SECONDS',$currentModule);
 	    }else if($years == 0 && $months == 0 && $days == 0 && $hours == 0 ){
		    $cron_end = "$minutes ".getTranslatedString('LBL_MINUTES',$currentModule);
 	    }else if($years == 0 && $months == 0 && $days == 0 ){
			$cron_end = "$hours ".getTranslatedString('LBL_HOURS',$currentModule);
		}else if($years == 0 && $months == 0 ){
			$cron_end = "$days ".getTranslatedString('LBL_DAYS',$currentModule);
		}else if($years == 0 ){
			$cron_end = "$months ".getTranslatedString('LBL_MONTHS',$currentModule);
		}else {
			$cron_end = "$years ".getTranslatedString('LBL_YEARS',$currentModule);
		}
	}
	else {
		$cron_end = '';
	}
	$out ['cronname'] = getTranslatedString($cron_mod,$cronTask->getModule());

	$out['hours'] = str_pad((int)(($cron_freq/(60*60))),2,0,STR_PAD_LEFT);
	$out['mins'] =str_pad((int)(($cron_freq%(60*60))/60),2,0,STR_PAD_LEFT);
	$out ['id'] = $cron_id;
	$out ['status'] = $cron_st;
	$out['laststart']= $cron_started;
	$out['lastend'] =$cron_end;
	if($out['status'] == Vtiger_Cron::$STATUS_DISABLED )
		$out['status'] = $mod_strings['LBL_INACTIVE'];
	elseif($out['status'] == Vtiger_Cron::$STATUS_ENABLED)
		$out['status'] = $mod_strings['LBL_ACTIVE'];
	else
		$out['status'] = $mod_strings['LBL_RUNNING'];

	$output [] = $out;
}

$smarty->assign("CRON",$output);
$smarty->assign("MOD", return_module_language($current_language,'CronTasks'));
$smarty->assign("MIN_CRON_FREQUENCY",$VtigerOndemandConfig['MINIMUM_CRON_FREQUENCY']);
$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("APP", $app_strings);
$smarty->assign("CMOD", $mod_strings);

if($_REQUEST['directmode'] != '')
	$smarty->display("modules/CronTasks/CronContents.tpl");
else {
	$smarty->display("modules/CronTasks/Cron.tpl");
}
?>
