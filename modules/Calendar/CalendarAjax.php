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
global $theme,$mod_strings,$current_language,$currentModule,$current_user;
$theme_path = "themes/".$theme."/";
$image_path = $theme_path."images/";
require_once($theme_path."layout_utils.php");
require_once("modules/Calendar/calendarLayout.php");
require_once("modules/Calendar/Calendar.php");
require_once('include/logging.php');
$cal_log =& LoggerManager::getLogger('calendar');
if(isset($_REQUEST['type']) && ($_REQUEST['type'] !=''))
{
	$type = $_REQUEST['type'];
	if($type == 'minical')
	{
	        $temp_module = $currentModule;
        	$mod_strings = return_module_language($current_language,'Calendar');
	        $currentModule = 'Calendar';
        	$calendar_arr = Array();
		$calendar_arr['IMAGE_PATH'] = $image_path;
	        $calendar_arr['calendar'] = new Calendar('month');
        	$calendar_arr['view'] = 'month';
	        $calendar_arr['size'] = 'small';
		$calendar_arr['calendar']->add_Activities($current_user);
        	calendar_layout($calendar_arr);
	        $mod_strings = return_module_language($current_language,$temp_module);
        	$currentModule = $_REQUEST['module'];
	}
	elseif($type == 'settings')
	{
		require_once('modules/Calendar/calendar_share.php');	
	}
	else
	{
		$mysel= $_REQUEST['view'];
		$calendar_arr = Array();
		$calendar_arr['IMAGE_PATH'] = $image_path;
		if(empty($mysel))
		{
			$mysel = 'day';
		}
		$date_data = array();
		if ( isset($_REQUEST['day']))
		{
			$date_data['day'] = $_REQUEST['day'];
		}

		if ( isset($_REQUEST['month']))
		{
			$date_data['month'] = $_REQUEST['month'];
		}

		if ( isset($_REQUEST['week']))
		{
			$date_data['week'] = $_REQUEST['week'];
		}

		if ( isset($_REQUEST['year']))
		{
			if ($_REQUEST['year'] > 2037 || $_REQUEST['year'] < 1970)
			{
				print("<font color='red'>Sorry, Year must be between 1970 and 2037</font>");
				exit;
			}
			$date_data['year'] = $_REQUEST['year'];
		}
		$calendar_arr['calendar'] = new Calendar($mysel,$date_data);
		if ($mysel == 'day' || $mysel == 'week' || $mysel == 'month' || $mysel == 'year')
		{
			$calendar_arr['calendar']->add_Activities($current_user);
		}
		$calendar_arr['view'] = $mysel;
		if($type == 'hourview')
		{
			getHourView($calendar_arr,'ajax');
		}
		elseif($type == 'listview')
		{
			getEventListView($calendar_arr);
		}
		else
		{
			die("View option is not defined");
		}
	}
}
else
{
	die("type is not set");
}

?>
