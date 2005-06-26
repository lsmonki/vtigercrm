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
require_once("data/Tracker.php");
require_once('modules/CustomView/CustomView.php');
require_once('include/logging.php');
require_once('include/ListView/ListView.php');

global $app_strings;
global $adb;

$metricviewnames = "'Hot Leads'";

$current_module_strings = return_module_language($current_language, "CustomView");
$log = LoggerManager::getLogger('metrics');

$xtpl=new XTemplate ('modules/CustomView/ListViewTop.html');
$xtpl->assign("MOD", $current_module_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("IMAGE_PATH", $image_path);

$metriclists = getMetricList($metricviewnames);

foreach ($metriclists as $key => $metriclist)
{
	$listquery = getListQuery($metriclist['module']);
	$oCustomView = new CustomView($metriclist['module']);
	$metricsql = $oCustomView->getMetricsCvListQuery($metriclist['id'],$listquery,$metriclist['module']);
	$metricresult = $adb->query($metricsql);
	if($metricresult)
	{
		$rowcount = $adb->fetch_array($metricresult);
		if(isset($rowcount))
		{
			$metriclists[$key]['count'] = $rowcount['count'];
		}	
	}
}
//print_r($metriclists);
// Stick the form header out there.
echo get_form_header("Metrics", '', false);

$oddRow = true;
foreach($metriclists as $metriclist)
{
	$metric_fields = array(
		'ID' => $metriclist['id'],
		'NAME' => $metriclist['name'],
		'COUNT' => $metriclist['count'],
		'MODULE' => $metriclist['module']
	);

	$xtpl->assign("CUSTOMVIEW", $metric_fields);

    if($oddRow)
    {
        //todo move to themes
	$xtpl->assign("ROW_COLOR", 'oddListRow');
    }
    else
    {
        //todo move to themes
	$xtpl->assign("ROW_COLOR", 'evenListRow');
    }
    $oddRow = !$oddRow;
        
    $xtpl->parse("main.row");
    // Put the rows in.
}

$xtpl->parse("main");
if (count($metriclists)>0) $xtpl->out("main");
else echo "<em>".$current_module_strings['NTC_NONE_SCHEDULED']."</em>";
echo "<BR>";
// Stick on the form footer
echo get_form_footer();


function getMetricList($viewnames)
{
	global $adb;
	$ssql = "select customview.* from customview inner join tab on tab.name = customview.entitytype";
        $ssql .= " where customview.viewname in (".$viewnames.") order by customview.entitytype";
        //echo $ssql;
        $result = $adb->query($ssql);
	while($cvrow=$adb->fetch_array($result))
	{
		$metricslist = Array();

		$metricslist['id'] = $cvrow['cvid'];
		$metricslist['name'] = $cvrow['viewname'];
		$metricslist['module'] = $cvrow['entitytype'];
		$metricslist['count'] = '';

		$metriclists[] = $metricslist;
	}

	return $metriclists;	
}

?>
