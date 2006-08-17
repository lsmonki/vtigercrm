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

global $theme;
$theme_path = "themes/".$theme."/";
$image_path = $theme_path."images/";
require_once($theme_path."layout_utils.php");
require_once("modules/Calendar/calendarLayout.php");
require_once("modules/Calendar/Calendar.php");
$mysel= $_REQUEST['view'];
$subtab = $_REQUEST['subtab'];
$viewBox = $_REQUEST['viewOption'];
if(empty($viewBox))
{
	$viewBox = 'listview';
}
if(empty($subtab))
{
	$subtab = 'event';
}
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


if(empty($date_data))
{
	$data_value=date('Y-m-d H:i:s');
	preg_match('/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/',$data_value,$value);
	$date_data = Array(
		'day'=>$value[3],
		'month'=>$value[2],
		'year'=>$value[1],
		'hour'=>$value[4],
		'min'=>$value[5],
	);
	
}
$calendar_arr['calendar'] = new Calendar($mysel,$date_data); 
if ($mysel == 'day' || $mysel == 'week' || $mysel == 'month' || $mysel == 'year')
{
        global $current_user;
        $calendar_arr['calendar']->add_Activities($current_user);
}
$calendar_arr['view'] = $mysel;
calendar_layout($calendar_arr,$viewBox,$subtab);
?>

