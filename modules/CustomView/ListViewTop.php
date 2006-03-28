<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header$
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

function getKeyMetrics()
{
	require_once("data/Tracker.php");
	require_once('modules/CustomView/CustomView.php');
	require_once('include/logging.php');
	require_once('include/ListView/ListView.php');

	global $app_strings;
	global $adb;
	global $log;
	global $current_language;
	$metricviewnames = "'Hot Leads'";

	$current_module_strings = return_module_language($current_language, "CustomView");
	$log = LoggerManager::getLogger('metrics');

	$metriclists = getMetricList();
	$log->info("Metrics :: Successfully got MetricList to be displayed");
	if(isset($metriclists))
	{
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
		$log->info("Metrics :: Successfully build the Metrics");
	}
	$title=array();
	$title[]='keyMetrics.gif';
	$title[]=$app_strings['LBL_HOME_KEY_METRICS'];
	$title[]='home_metrics';
	$header=Array();
	$header[]=$app_strings['LBL_HOME_METRICS'];
	$header[]=$app_strings['LBL_HOME_COUNT'];
	$entries=Array();
	if(isset($metriclists))
	{
		$oddRow = true;
		foreach($metriclists as $metriclist)
		{
			$value=array();
			$metric_fields = array(
					'ID' => $metriclist['id'],
					'NAME' => $metriclist['name'],
					'COUNT' => $metriclist['count'],
					'MODULE' => $metriclist['module']
					);

			$value[]='<a href="index.php?action=index&module='.$metriclist['module'].'&viewname='.$metriclist['id'].'">'.$metriclist['name'].'</a>';
			$value[]='<a href="index.php?action=index&module='.$metriclist['module'].'&viewname='.$metriclist['id'].'">'.$metriclist['count'].'</a>';
			$entries[$metriclist['id']]=$value;
		}

	}
	$values=Array('Title'=>$title,'Header'=>$header,'Entries'=>$entries);
	if ( ($display_empty_home_blocks ) || (count($value)!= 0) )
		return $values;

}
function getMetricList()
{
	global $adb;
	$ssql = "select customview.* from customview inner join tab on tab.name = customview.entitytype";
	$ssql .= " where customview.setmetrics = 1 order by customview.entitytype";
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
