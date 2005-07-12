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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Potentials/Charts.php,v 1.12 2005/04/20 20:23:34 ray Exp $
 * Description:  Includes the functions for Customer module specific charts.
 ********************************************************************************/

require_once('config.php');
require_once('include/logging.php');
require_once('modules/Potentials/Opportunity.php');
require_once("jpgraph/src/jpgraph.php");
require_once('include/utils.php');
require_once('include/logging.php');




// TTF Font families
DEFINE("FF_COURIER",10);
DEFINE("FF_VERDANA",11);
DEFINE("FF_TIMES",12);
DEFINE("FF_COMIC",14);
DEFINE("FF_ARIAL",15);
DEFINE("FF_GEORGIA",16);
DEFINE("FF_TREBUCHE",17);

// Chinese font
DEFINE("FF_SIMSUN",30);
DEFINE("FF_CHINESE",31);
DEFINE("FF_BIG5",31);


function calculate_font_family($locale)

{

	switch($locale)
	{
		case 'cn_zh':
			return FF_SIMSUN;
		case 'tw_zh':
			if(!function_exists('iconv')){
				echo " Unable to display traditional Chinese on the graphs.<BR>The function iconv does not exists please read more about <a href='http://us4.php.net/iconv'>iconv here</a><BR>";
				return FF_FONT1;

			}
			else return FF_CHINESE;
		default:
			return FF_FONT1;
	}

	return FF_FONT1;
}


class jpgraph {
	/**
	 * Creates opportunity pipeline image as a horizontal accumlated bar graph for multiple users.
	 * param $datax- the month data to display in the x-axis
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	 */
	function outcome_by_month($date_start='1971-10-15', $date_end='2071-10-15', $user_id=array('1'), $cache_file_name='a_file', $refresh=false) {
		global $app_strings,$lang_crm, $app_list_strings, $current_module_strings, $log, $charset, $tmp_dir;
		global $theme;
		include_once ("jpgraph/src/jpgraph_bar.php");

		// Size of graph
		$width=600;
		$height=400;

		$log =& LoggerManager::getLogger('outcome_by_month chart');
		// Set the basic parameters of the graph
		$graph = new Graph($width,$height,$cache_file_name);
		$log->debug("graph object created");

		$graph->SetScale("textlin");

		if (!file_exists($cache_file_name) || !file_exists($cache_file_name.'.map') || $refresh == true) {
			$font = calculate_font_family($lang_crm);

			$log->debug("date_start is: $date_start");
			$log->debug("date_end is: $date_end");
			$log->debug("user_id is: ");
			$log->debug($user_id);
			$log->debug("cache_file_name is: $cache_file_name");

			//build the where clause for the query that matches $user
			$where = "(";
			$first = true;
			$current = 0;
			foreach ($user_id as $the_id) {
				if (!$first) $where .= "OR ";
				$first = false;
				$where .= "crmentity.smcreatorid='$the_id' ";
			}
			$where .= ") ";

			//build the where clause for the query that matches $date_start and $date_end
			$where .= "AND closingdate >= '$date_start' AND closingdate <= '$date_end'";
			$subtitle = $current_module_strings['LBL_DATE_RANGE']." ".getDisplayDate($date_start)." ".$current_module_strings['LBL_DATE_RANGE_TO']." ".getDisplayDate($date_end)."\n";

			//Now do the db queries
			//query for opportunity data that matches $datay and $user
			$opp = new Potential();
			$opp_list = $opp->get_full_list("amount DESC, closingdate DESC", $where);

			//build pipeline by sales stage data
			$total = 0;
			$count = array();
			$sum = array();
			$months = array();
			$other = $current_module_strings['LBL_LEAD_SOURCE_OTHER'];
			if (isset($opp_list)) {
				foreach ($opp_list as $record) {
					$month = substr_replace($record->column_fields['closingdate'],'',-3);
					if (!in_array($month, $months)) { array_push($months, $month); }
					if ($record->column_fields['sales_stage'] == 'Closed Won' || $record->column_fields['sales_stage'] == 'Closed Lost') {
						$sales_stage=$record->column_fields['sales_stage'];
					}
					else {
						$sales_stage=$other;
					}

					if (!isset($sum[$month][$sales_stage])) {
						$sum[$month][$sales_stage] = 0;
					}
					if (isset($record->column_fields['amount']))	{
						// Strip all non numbers from this string.
						$amount = ereg_replace('[^0-9]', '', $record->column_fields['amount']);
						$sum[$month][$sales_stage] = $sum[$month][$sales_stage] + $amount;
						if (isset($count[$month][$sales_stage])) {
							$count[$month][$sales_stage]++;
						}
						else {
							$count[$month][$sales_stage] = 1;
						}
						$total = $total + ($amount/1000);
					}
				}
			}

			$legend = array();
			$datax = array();
			$aTargets = array();
			$aAlts = array();
			$stages = array($other, 'Closed Lost', 'Closed Won');
			//sort the months or push a bogus month on the array so that an empty chart is drawn
			if (empty($months)) {
				array_push($months, date('Y-m',time()));
			}
			else{
				sort($months);
			}
			foreach($months as $month) {
			  foreach($stages as $stage) {
				$log->debug("stage is $stage");
				if (!isset($datax[$stage])) {
					$datax[$stage] = array();
				}
				if (!isset($aAlts[$stage])) {
					$aAlts[$stage] = array();
				}
				if (!isset($aTargets[$stage])) {
					$aTargets[$stage] = array();
				}

				if (isset($sum[$month][$stage])) {
					array_push($datax[$stage], $sum[$month][$stage]/1000);
					array_push($aAlts[$stage], $count[$month][$stage]." ".$current_module_strings['LBL_OPPS_OUTCOME']." $stage");
				}
				else {
					array_push($datax[$stage], 0);
					array_push($aAlts[$stage], "");
				}
				array_push($aTargets[$stage], "index.php?module=Potentials&action=ListView&date_closed=$month&sales_stage=".urlencode($stage)."&query=true");
			  }
		  	  array_push($legend,$month);
			}

			$log->debug("datax is:");
			$log->debug($datax);
			$log->debug("aAlts is:");
			$log->debug($aAlts);
			$log->debug("aTargets is:");
			$log->debug($aTargets);
			$log->debug("sum is:");
			$log->debug($sum);
			$log->debug("count is:");
			$log->debug($count);

			//now build the bar plots for each user across the sales stages
			$bplot = array();
			$color = array('Closed Lost'=>'FF9900','Closed Won'=>'009933', $other=>'0066CC');
			$index = 0;
			foreach($stages as $stage) {
				// Now create a bar pot
				$bplot[$index] = new BarPlot($datax[$stage]);

				//You can change the width of the bars if you like
				$bplot[$index]->SetWidth(5);

				// Set fill colors for bars
				$bplot[$index]->SetFillColor("#".$color[$stage]);

				// We want to display the value of each bar at the top
				$bplot[$index]->value->Show();
				$bplot[$index]->value->SetFont($font,FS_NORMAL,8);
				//$bplot->value->SetAlign('left','center');
				$bplot[$index]->value->SetColor("white");
				$bplot[$index]->value->SetFormat(getCurrencySymbol().'%d');
				$bplot[$index]->SetValuePos('max');

				//set client side image map URL's
				$bplot[$index]->SetCSIMTargets($aTargets[$stage],$aAlts[$stage]);
				$log->debug("bplot[$index] is: ");
				$log->debug($bplot[$index]);
				$index++;
			}

			if($theme == "blue")
			{
				$font_color = "#212473";
			}
			else
			{
				$font_color = "#000000";
			}

			// Create the grouped bar plot
			$gbplot = new AccBarPlot($bplot);

			// Add the bar to the graph
			$graph->Add($gbplot);

			// No frame around the image
			$graph->SetFrame(true,"white");

			// Rotate graph 90 degrees and set margin
			$top = 20;
			$bottom = 50;
			$left = 20;
			$right = 50;
			$graph->SetMargin($left,$right,$top,$bottom);

			// Set white margin color
			$graph->SetMarginColor('#F5F5F5');

			// Use a box around the plot area
			$graph->SetBox();

			// Use a gradient to fill the plot area
			$graph->SetBackgroundGradient('#E5E5E5','white',GRAD_HOR,BGRAD_PLOT);

			// Setup title
			$title = $current_module_strings['LBL_TOTAL_PIPELINE'].getCurrencySymbol().$total.$app_strings['LBL_THOUSANDS_SYMBOL'];
			$graph->title->Set($title);
			$graph->title->SetColor($font_color);
			$graph->title->SetFont($font,FS_BOLD,11);

			// Setup X-axis
			$graph->xaxis->SetColor($font_color);
			$graph->xaxis->SetTickLabels($legend);
			$graph->xaxis->SetFont($font,FS_NORMAL,8);

			// Some extra margin looks nicer
			$graph->xaxis->SetLabelMargin(10);

			// Label align for X-axis
			$graph->xaxis->SetLabelAlign('center','center');
			$graph->yaxis->SetColor($font_color);
			$graph->yaxis->SetLabelSide(SIDE_LEFT);

			// The fix the tick marks
			$graph->yaxis->SetTickSide(SIDE_RIGHT);

			// Add some grace to y-axis so the bars doesn't go
			// all the way to the end of the plot area
			$graph->yaxis->scale->SetGrace(10);

			// Setup the Y-axis to be displayed in the bottom of the
			// graph. We also finetune the exact layout of the title,
			// ticks and labels to make them look nice.
			$graph->yaxis->SetPos('max');

			// First make the labels look right
			$graph->yaxis->SetLabelAlign('left','top');
			$graph->yaxis->SetLabelFormat(getCurrencySymbol().'%d');
			$graph->yaxis->SetLabelSide(SIDE_RIGHT);

			// The fix the tick marks
			$graph->yaxis->SetTickSide(SIDE_LEFT);

			// Finally setup the title
			$graph->yaxis->SetTitleSide(SIDE_RIGHT);
			$graph->yaxis->SetTitleMargin(35);

			$subtitle .= $current_module_strings['LBL_OPP_SIZE'].getCurrencySymbol().$current_module_strings['LBL_OPP_SIZE_VALUE'];
			$graph->footer->right->SetColor($font_color);
			$graph->footer->right->Set($subtitle);
			$graph->footer->right->SetFont($font,FS_NORMAL,8);

			$graph->yaxis->SetFont($font,FS_NORMAL, 8);

			// .. and stroke the graph
			$graph->Stroke($cache_file_name);
			$imgMap = $graph->GetHTMLImageMap('outcome_by_month');
			save_image_map($cache_file_name.'.map', $imgMap);
		}
		else {
			$imgMap_fp = fopen($cache_file_name.'.map', "rb");
			$imgMap = fread($imgMap_fp, filesize($cache_file_name.'.map'));
			fclose($imgMap_fp);
		}
		$fileModTime = filemtime($cache_file_name.'.map');
		$return = "\n$imgMap\n";
		$return .= "<img src='$cache_file_name?modTime=$fileModTime'\n";
		$return .= "ismap usemap='#outcome_by_month' border='0'>\n";
		return $return;
	}

	/**
	 * Creates lead_source_by_outcome pipeline image as a horizontal accumlated bar graph for multiple users.
	 * param $datay- the lead source data to display in the x-axis
	 * param $date_start- the begin date of opps to find
	 * param $date_end- the end date of opps to find
	 * param $ids - list of assigned users of opps to find
	 * param $cache_file_name - file name to write image to
	 * param $refresh - boolean whether to rebuild image if exists
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	 */
	function lead_source_by_outcome($datay=array('foo','bar'), $user_id=array('1'), $cache_file_name='a_file', $refresh=false) {
		global $app_strings,$lang_crm, $current_module_strings, $log, $charset, $tmp_dir;
		global $theme;

		include_once ("jpgraph/src/jpgraph_bar.php");

		// Size of graph
		$width=300;
		$height=400;

		$log =& LoggerManager::getLogger('lead_source_by_outcome chart');
		// Set the basic parameters of the graph
		$graph = new Graph($width,$height,$cache_file_name);
		$log->debug("graph object created");

		$graph->SetScale("textlin");

		if (!file_exists($cache_file_name) || !file_exists($cache_file_name.'.map') || $refresh == true) {
			$font = calculate_font_family($lang_crm);

			$log->debug("datay is:");
			$log->debug($datay);
			$log->debug("user_id is: ");
			$log->debug($user_id);
			$log->debug("cache_file_name is: $cache_file_name");

			$where="";
			//build the where clause for the query that matches $user
			$count = count($user_id);
			if ($count>0) {
				$where = "(";
				$first = true;
				$current = 0;
				foreach ($user_id as $the_id) {
					if (!$first) $where .= "OR ";
					$first = false;
					$where .= "crmentity.smcreatorid='$the_id' ";
				}
				$where .= ") ";
			}

			//build the where clause for the query that matches $datay
			$count = count($datay);
			if ($count>0) {
				$where .= "AND ( ";
				unset($first);
				$first = true;
				foreach ($datay as $key=>$value) {
					if (!$first) $where .= "OR ";
					$first = false;
					$where .= "leadsource ='$key' ";
				}
				$where .= ")";
			}

			//Now do the db queries
			//query for opportunity data that matches $datay and $user
			$opp = new Potential();
			$opp_list = $opp->get_full_list("amount DESC, closingdate DESC", $where);

			//build pipeline by sales stage data
			$total = 0;
			$count = array();
			$sum = array();
			$other = $current_module_strings['LBL_LEAD_SOURCE_OTHER'];
			if (isset($opp_list)) {
				foreach ($opp_list as $record) {
					//if lead source is blank, set it to the language's "none" value
					if (isset($record->column_fields['leadsource']) && $record->column_fields['leadsource'] != '') {
						$lead_source = $record->column_fields['leadsource'];
					}
					else {
						$lead_source = $current_module_strings['NTC_NO_LEGENDS'];
					}

					if ($record->column_fields['sales_stage'] == 'Closed Won' || $record->column_fields['sales_stage'] == 'Closed Lost') {
						$sales_stage=$record->column_fields['sales_stage'];
					}
					else {
						$sales_stage=$other;
					}

					if (!isset($sum[$lead_source][$sales_stage])) {
						$sum[$lead_source][$sales_stage] = 0;
					}
					if (isset($record->column_fields['amount']))	{
						// Strip all non numbers from this string.
						$amount = ereg_replace('[^0-9]', '', $record->column_fields['amount']);
						$sum[$lead_source][$sales_stage] = $sum[$lead_source][$sales_stage] + $amount;
						if (isset($count[$lead_source][$sales_stage])) {
							$count[$lead_source][$sales_stage]++;
						}
						else {
							$count[$lead_source][$sales_stage] = 1;
						}
						$total = $total + ($amount/1000);
					}
				}
			}

			$legend = array();
			$datax = array();
			$aTargets = array();
			$aAlts = array();
			$stages = array($other,'Closed Lost', 'Closed Won');
			foreach($datay as $lead=>$translation) {
			  if ($lead == '') {
					$lead = $current_module_strings['NTC_NO_LEGENDS'];
					$translation = $current_module_strings['NTC_NO_LEGENDS'];
			  }
			  foreach($stages as $stage) {
				$log->debug("stage_key is $stage");
				if (!isset($datax[$stage])) {
					$datax[$stage] = array();
				}
				if (!isset($aAlts[$stage])) {
					$aAlts[$stage] = array();
				}
				if (!isset($aTargets[$stage])) {
					$aTargets[$stage] = array();
				}

				if (isset($sum[$lead][$stage])) {
					array_push($datax[$stage], $sum[$lead][$stage]/1000);
					array_push($aAlts[$stage], $count[$lead][$stage]." ".$current_module_strings['LBL_OPPS_OUTCOME']." $stage");
				}
				else {
					array_push($datax[$stage], 0);
					array_push($aAlts[$stage], "");
				}
				array_push($aTargets[$stage], "index.php?module=Potentials&action=ListView&leadsource=".urlencode($lead)."&sales_stage=".urlencode($stage)."&query=true");
			  }
			  array_push($legend,$translation);
			}

			$log->debug("datax is:");
			$log->debug($datax);
			$log->debug("aAlts is:");
			$log->debug($aAlts);
			$log->debug("aTargets is:");
			$log->debug($aTargets);
			$log->debug("sum is:");
			$log->debug($sum);
			$log->debug("count is:");
			$log->debug($count);

			//now build the bar plots for each user across the sales stages
			$bplot = array();
			$color = array('Closed Lost'=>'FF9900','Closed Won'=>'009933', $other=>'0066CC');
			$index = 0;
			foreach($stages as $stage) {
				// Now create a bar pot
				$bplot[$index] = new BarPlot($datax[$stage]);

				//You can change the width of the bars if you like
				$bplot[$index]->SetWidth(5);

				// Set fill colors for bars
				$bplot[$index]->SetFillColor("#".$color[$stage]);

				// We want to display the value of each bar at the top
				$bplot[$index]->value->Show();
				$bplot[$index]->value->SetFont($font,FS_NORMAL,7);
				//$bplot->value->SetAlign('left','center');
				$bplot[$index]->value->SetColor("white");
				$bplot[$index]->value->SetFormat(getCurrencySymbol().'%d');
				$bplot[$index]->SetValuePos('max');

				//set client side image map URL's
				$bplot[$index]->SetCSIMTargets($aTargets[$stage],$aAlts[$stage]);
				$log->debug("bplot[$index] is: ");
				$log->debug($bplot[$index]);
				$log->debug("datax[$stage] is: ");
				$log->debug($datax[$stage]);
				$index++;
			}

			if($theme == "blue")
			{
				$font_color = "#212473";
			}
			else
			{
				$font_color = "#000000";
			}

			// Create the grouped bar plot
			$gbplot = new AccBarPlot($bplot);

			// Add the bar to the graph
			$graph->Add($gbplot);

			// No frame around the image
			$graph->SetFrame(true,"white");

			// Rotate graph 90 degrees and set margin
			$top = 20;
			$bottom = 50;
			$left = 130;
			$right = 40;
			$graph->Set90AndMargin($left,$right,$top,$bottom);

			// Set white margin color
			$graph->SetMarginColor('#F5F5F5');

			// Use a box around the plot area
			$graph->SetBox();

			// Use a gradient to fill the plot area
			$graph->SetBackgroundGradient('#E5E5E5','white',GRAD_HOR,BGRAD_PLOT);

			// Setup title
			$title = $current_module_strings['LBL_ALL_OPPORTUNITIES'].getCurrencySymbol().$total.$app_strings['LBL_THOUSANDS_SYMBOL'];
			$graph->title->Set($title);
			$graph->title->SetColor($font_color);
			$graph->title->SetFont($font,FS_BOLD,11);

			// Setup X-axis
			$graph->xaxis->SetTickLabels($legend);
			$graph->xaxis->SetColor($font_color);
			$graph->xaxis->SetFont($font,FS_NORMAL,8);

			// Some extra margin looks nicer
			$graph->xaxis->SetLabelMargin(10);

			// Label align for X-axis
			$graph->xaxis->SetLabelAlign('right','center');
			$graph->yaxis->SetLabelSide(SIDE_LEFT);
			$graph->yaxis->SetColor($font_color);
			// The fix the tick marks
			$graph->yaxis->SetTickSide(SIDE_RIGHT);

			// Add some grace to y-axis so the bars doesn't go
			// all the way to the end of the plot area
			$graph->yaxis->scale->SetGrace(10);

			// Setup the Y-axis to be displayed in the bottom of the
			// graph. We also finetune the exact layout of the title,
			// ticks and labels to make them look nice.
			$graph->yaxis->SetPos('max');

			// First make the labels look right
			$graph->yaxis->SetLabelAlign('left','top');
			$graph->yaxis->SetLabelFormat(getCurrencySymbol().'%d');
			$graph->yaxis->SetLabelSide(SIDE_RIGHT);

			// The fix the tick marks
			$graph->yaxis->SetTickSide(SIDE_LEFT);

			// Finally setup the title
			$graph->yaxis->SetTitleSide(SIDE_RIGHT);
			$graph->yaxis->SetTitleMargin(35);

			$subtitle = $current_module_strings['LBL_OPP_SIZE'].getCurrencySymbol().$current_module_strings['LBL_OPP_SIZE_VALUE']; 
			$graph->footer->right->SetColor($font_color);
			$graph->footer->right->Set($subtitle);
			$graph->footer->right->SetFont($font,FS_NORMAL,8);

			$graph->yaxis->SetFont($font,FS_NORMAL, 8);

			// .. and stroke the graph
			$graph->Stroke($cache_file_name);
			$imgMap = $graph->GetHTMLImageMap('lead_source_by_outcome');
			save_image_map($cache_file_name.'.map', $imgMap);
		}
		else {
			$imgMap_fp = fopen($cache_file_name.'.map', "rb");
			$imgMap = fread($imgMap_fp, filesize($cache_file_name.'.map'));
			fclose($imgMap_fp);
		}
		$fileModTime = filemtime($cache_file_name.'.map');
		$return = "\n$imgMap\n";
		$return .= "<img src='$cache_file_name?modTime=$fileModTime'\n";
		$return .= "ismap usemap='#lead_source_by_outcome' border='0'>\n";
		return $return;
	}

	/**
	 * Creates opportunity pipeline image as a horizontal accumlated bar graph for multiple users.
	 * param $datax- the sales stage data to display in the x-axis
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	 */
	function pipeline_by_sales_stage($datax=array('foo','bar'), $date_start='2071-10-15', $date_end='2071-10-15', $user_id=array('1'), $cache_file_name='a_file', $refresh=false) {
		global $app_strings,$lang_crm, $current_module_strings, $log, $charset, $tmp_dir;
		global $theme;
		include_once ("jpgraph/src/jpgraph_bar.php");

		// Size of graph
		$width=300;
		$height=400;

		$log =& LoggerManager::getLogger('opportunity charts');
		// Set the basic parameters of the graph
		$graph = new Graph($width,$height,$cache_file_name);
		$log->debug("graph object created");

		$graph->SetScale("textlin");

		if (!file_exists($cache_file_name) || !file_exists($cache_file_name.'.map') || $refresh == true) {
			$font = calculate_font_family($lang_crm);

			$log->debug("starting pipeline chart");
			$log->debug("datax is:");
			$log->debug($datax);
			$log->debug("user_id is: ");
			$log->debug($user_id);
			$log->debug("cache_file_name is: $cache_file_name");

			$where="";
			//build the where clause for the query that matches $user
			$count = count($user_id);
			if ($count>0) {
				$where = "(";
				$first = true;
				$current = 0;
				foreach ($user_id as $the_id) {
					if (!$first) $where .= "OR ";
					$first = false;
					//reference post
					//if I change the owner of a opportunity, the graph shown on Home does not update correctly, this is because the graph is looking for the creatorid and not for the ownerid
					//fix incorporated based on /sak's feedback
					$where .= "crmentity.smownerid='$the_id' ";
				}
				$where .= ") ";
			}

			//build the where clause for the query that matches $datax
			$count = count($datax);
			if ($count>0) {
				$where .= "AND ( ";
				unset($first);
				$first = true;
				foreach ($datax as $key=>$value) {
					if (!$first) $where .= "OR ";
					$first = false;
					$where .= "sales_stage ='$key' ";
				}
				$where .= ")";
			}

			//build the where clause for the query that matches $date_start and $date_end
			$where .= "AND closingdate >= '$date_start' AND closingdate <= '$date_end'";
			$subtitle = $current_module_strings['LBL_DATE_RANGE']." ".getDisplayDate($date_start)." ".$current_module_strings['LBL_DATE_RANGE_TO']." ".getDisplayDate($date_end)."\n";

			//Now do the db queries
			//query for opportunity data that matches $datax and $user
			$opp = new Potential();
			$opp_list = $opp->get_full_list("amount DESC, closingdate DESC", $where);

			//build pipeline by sales stage data
			$total = 0;
			$count = array();
			$sum = array();
			if (isset($opp_list)) {
				foreach ($opp_list as $record) {
					if (!isset($sum[$record->column_fields['sales_stage']][$record->column_fields['assigned_user_id']])) {
						$sum[$record->column_fields['sales_stage']][$record->column_fields['assigned_user_id']] = 0;
					}
					if (isset($record->column_fields['amount']))	{
						// Strip all non numbers from this string.
						$amount = ereg_replace('[^0-9]', '', $record->column_fields['amount']);
						$sum[$record->column_fields['sales_stage']][$record->column_fields['assigned_user_id']] = $sum[$record->column_fields['sales_stage']][$record->column_fields['assigned_user_id']] + $amount;
						if (isset($count[$record->column_fields['sales_stage']][$record->column_fields['assigned_user_id']])) {
							$count[$record->column_fields['sales_stage']][$record->column_fields['assigned_user_id']]++;
						}
						else {
							$count[$record->column_fields['sales_stage']][$record->column_fields['assigned_user_id']] = 1;
						}
						$total = $total + ($amount/1000);
					}
				}
			}

			$legend = array();
			$datay = array();
			$aTargets = array();
			$aAlts = array();
			foreach ($datax as $stage_key=>$stage_translation) {
			  foreach ($user_id as $the_id) {
			  	$the_user = get_assigned_user_name($the_id);
				if (!isset($datay[$the_id])) {
					$datay[$the_id] = array();
				}
				if (!isset($aAlts[$the_id])) {
					$aAlts[$the_id] = array();
				}
				if (!isset($aTargets[$the_id])) {
					$aTargets[$the_id] = array();
				}

				if (isset($sum[$stage_key][$the_id])) {
					array_push($datay[$the_id], $sum[$stage_key][$the_id]/1000);
					array_push($aAlts[$the_id], $the_user.' - '.$count[$stage_key][$the_id]." ".$current_module_strings['LBL_OPPS_IN_STAGE']." $stage_translation");
				}
				else {
					array_push($datay[$the_id], 0);
					array_push($aAlts[$the_id], "");
				}
				array_push($aTargets[$the_id], "index.php?module=Potentials&action=ListView&assigned_user_id[]=$the_id&sales_stage=".urlencode($stage_key)."&closingdate_start=".urlencode($date_start)."&closingdate_end=".urlencode($date_end)."&query=true");
			  }
			  array_push($legend,$stage_translation);
			}

			$log->debug("datay is:");
			$log->debug($datay);
			$log->debug("aAlts is:");
			$log->debug($aAlts);
			$log->debug("aTargets is:");
			$log->debug($aTargets);
			$log->debug("sum is:");
			$log->debug($sum);
			$log->debug("count is:");
			$log->debug($count);

			//now build the bar plots for each user across the sales stages
			$bplot = array();
			$color = 'D50100';
			$index = 0;
			foreach($user_id as $the_id) {
				// Now create a bar pot
				$bplot[$index] = new BarPlot($datay[$the_id]);
				//color="black",$hsize=3,$vsize=3,$show=true
				$bplot[$index]->SetShadow();
				//You can change the width of the bars if you like
				$bplot[$index]->SetWidth(0.5);

				// Set fill colors for bars
				//$bplot[$index]->SetFillGradient('red','#7D7D7D',GRAD_HOR);//SetFillColor("#$color");
				$bplot[$index]->SetFillColor("#$color");
				$color = $color + 220022;

				// We want to display the value of each bar at the top
				$bplot[$index]->value->Show();
				$bplot[$index]->value->SetFont($font,FS_NORMAL,8);
				//$bplot->value->SetAlign('left','center');
				$bplot[$index]->value->SetColor("white");
				$bplot[$index]->value->SetFormat(getCurrencySymbol().'%d');
				$bplot[$index]->SetValuePos('max');

				//set client side image map URL's
				$bplot[$index]->SetCSIMTargets($aTargets[$the_id],$aAlts[$the_id]);
				$log->debug("bplot[$index] is: ");
				$log->debug($bplot[$index]);
				$log->debug("datay[$the_id] is: ");
				$log->debug($datay[$the_id]);
				$index++;
			}

			// Create the grouped bar plot
			$gbplot = new AccBarPlot($bplot);

			// Add the bar to the graph
			$graph->Add($gbplot);

			// No frame around the image
			$graph->SetFrame(true,"white");

			// Rotate graph 90 degrees and set margin
			$top = 20;
			$bottom = 70;
			$left = 130;
			$right = 40;
			$graph->Set90AndMargin($left,$right,$top,$bottom);

			// Set white margin color
			$graph->SetMarginColor('#F5F5F5');

			// Use a box around the plot area
			$graph->SetBox();

			// Use a gradient to fill the plot area
			$graph->SetBackgroundGradient('#E5E5E5','white',GRAD_HOR,BGRAD_PLOT);

			if($theme == "blue")
			{
				$font_color = "#212473";
			}
			else
			{
				$font_color = "#000000";
			}


			// Setup title
			$title = $current_module_strings['LBL_TOTAL_PIPELINE'].getCurrencySymbol().$total.$app_strings['LBL_THOUSANDS_SYMBOL'];
			$graph->title->Set($title);
			$graph->title->SetColor($font_color);
			$graph->title->SetFont($font,FS_BOLD,11);

			// Setup X-axis
			$graph->xaxis->SetTickLabels($legend);
			$graph->xaxis->SetColor($font_color);
			$graph->xaxis->SetFont($font,FS_NORMAL,8);

			// Some extra margin looks nicer
			$graph->xaxis->SetLabelMargin(10);

			// Label align for X-axis
			$graph->xaxis->SetLabelAlign('right','center');

			// Add some grace to y-axis so the bars doesn't go
			// all the way to the end of the plot area
			$graph->yaxis->scale->SetGrace(10);

			// Setup the Y-axis to be displayed in the bottom of the
			// graph. We also finetune the exact layout of the title,
			// ticks and labels to make them look nice.
			$graph->yaxis->SetPos('max');

			// First make the labels look right
			$graph->yaxis->SetColor($font_color);
			$graph->yaxis->SetLabelAlign('center','top');
			$graph->yaxis->SetLabelFormat(getCurrencySymbol().'%d');
			$graph->yaxis->SetLabelSide(SIDE_RIGHT);

			// The fix the tick marks
			$graph->yaxis->SetTickSide(SIDE_LEFT);

			// Finally setup the title
			$graph->yaxis->SetTitleSide(SIDE_RIGHT);
			$graph->yaxis->SetTitleMargin(35);

			$subtitle .= $current_module_strings['LBL_OPP_SIZE'].getCurrencySymbol().$current_module_strings['LBL_OPP_SIZE_VALUE']; 
			$graph->footer->right->Set($subtitle);
			$graph->footer->right->SetColor($font_color);
			$graph->footer->right->SetFont($font,FS_NORMAL,8);

			$graph->yaxis->SetFont($font,FS_NORMAL, 8);

			// .. and stroke the graph
			$graph->Stroke($cache_file_name);
			$imgMap = $graph->GetHTMLImageMap('pipeline');
			save_image_map($cache_file_name.'.map', $imgMap);
		}
		else {
			$imgMap_fp = fopen($cache_file_name.'.map', "rb");
			$imgMap = fread($imgMap_fp, filesize($cache_file_name.'.map'));
			fclose($imgMap_fp);
		}
		$fileModTime = filemtime($cache_file_name.'.map');
		$return = "\n$imgMap\n";
		$return .= "<img src='$cache_file_name?modTime=$fileModTime'\n";
		$return .= "ismap usemap='#pipeline' border='0'>\n";
		return $return;
	}

	/**
	 * Creates pie chart image of opportunities by lead_source.
	 * param $datax- the sales stage data to display in the x-axis
	 * param $datay- the sum of opportunity amounts for each opportunity in each sales stage
	 * to display in the y-axis
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	 */
	function pipeline_by_lead_source($legends=array('foo','bar'), $user_id=array('1'), $cache_file_name='a_file', $refresh=true) {
		global $app_strings,$lang_crm, $current_module_strings, $log, $charset, $tmp_dir;
		global $theme;

		include_once ("jpgraph/src/jpgraph_pie.php");
		include_once ("jpgraph/src/jpgraph_pie3d.php");

		$font = calculate_font_family($lang_crm);

		if (!file_exists($cache_file_name) || !file_exists($cache_file_name.'.map') || $refresh == true) {
			$log =& LoggerManager::getLogger('opportunity charts');
			$log->debug("starting pipeline chart");
			$log->debug("legends is:");
			$log->debug($legends);
			$log->debug("user_id is: ");
			$log->debug($user_id);
			$log->debug("cache_file_name is: $cache_file_name");

			//Now do the db queries
			//query for opportunity data that matches $legends and $user
			$where="";
			//build the where clause for the query that matches $user
			$count = count($user_id);
			if ($count>0) {
				$where = "(";
				$first = true;
				foreach ($user_id as $the_id) {
					if (!$first) $where .= "OR ";
					$first = false;
					$where .= "crmentity.smcreatorid='$the_id' ";
				}
				$where .= ") ";
			}

			//build the where clause for the query that matches $datax
			$count = count($legends);
			if ($count>0) {
				$where .= "AND ( ";
				$first = true;
				foreach ($legends as $key=>$value) {
					if (!$first) $where .= "OR ";
					$first = false;
					$where .= "leadsource	='$key' ";
				}
				$where .= ")";
			}

			$opp = new Potential();
			$opp_list = $opp->get_full_list("amount DESC, closingdate DESC", $where);

			//build pipeline by lead source data
			$total = 0;
			$count = array();
			$sum = array();
			if (isset($opp_list)) {
				foreach ($opp_list as $record) {
					if (!isset($sum[$record->column_fields['leadsource']])) $sum[$record->column_fields['leadsource']] = 0;
					if (isset($record->column_fields['amount']) && isset($record->column_fields['leadsource']))	{
						// Strip all non numbers from this string.
						$amount = ereg_replace('[^0-9]', '', $record->column_fields['amount']);
						$sum[$record->column_fields['leadsource']] = $sum[$record->column_fields['leadsource']] + ($amount/1000);
						if (isset($count[$record->column_fields['leadsource']])) $count[$record->column_fields['leadsource']]++;
						else $count[$record->column_fields['leadsource']] = 1;
						$total = $total + ($amount/1000);
					}
				}
			}

			$visible_legends = array();
			$data= array();
			$aTargets = array();
			$aAlts = array();
			foreach ($legends as $lead_source_key=>$lead_source_translation) {
				if (isset($sum[$lead_source_key]))
				{
					array_push($data, $sum[$lead_source_key]);
					if($lead_source_key != '')
					{
						array_push($visible_legends, $lead_source_translation);
					}
					else
					{
						// put none in if the field is blank.
						array_push($visible_legends, $current_module_strings['NTC_NO_LEGENDS']);
					}
					array_push($aTargets, "index.php?module=Potentials&action=ListView&leadsource=".urlencode($lead_source_key)."&query=true");
					array_push($aAlts, $count[$lead_source_key]." ".$current_module_strings['LBL_OPPS_IN_LEAD_SOURCE']." $lead_source_translation	");
				}
			}

			$log->debug("sum is:");
			$log->debug($sum);
			$log->debug("count is:");
			$log->debug($count);
			$log->debug("total is: $total");
			if ($total == 0) {
				return ($current_module_strings['ERR_NO_OPPS']);
			}

			if($theme == "blue")
			{
				$font_color = "#212473";
			}
			else
			{
				$font_color = "#000000";
			}

			// Create the Pie Graph.
			$graph = new PieGraph(490,260,$cache_file_name);

			$graph->SetShadow();

			// Setup title
			$title = $current_module_strings['LBL_TOTAL_PIPELINE'].getCurrencySymbol().$total.$app_strings['LBL_THOUSANDS_SYMBOL'];
			$graph->title->Set($title);
			$graph->title->SetColor($font_color);
			$graph->title->SetFont($font,FS_BOLD,11);

			// No frame around the image
			$graph->SetFrame(false);
			//$graph->SetMarginColor('#F5F5F5');

			$graph->legend->Pos(0.01,0.10);
			$graph->legend->SetColor($font_color);
			$graph->legend->SetFont($font,FS_NORMAL,12);

			$subtitle = $current_module_strings['LBL_OPP_SIZE'].getCurrencySymbol().$current_module_strings['LBL_OPP_SIZE_VALUE'];
			$graph->footer->left->Set($subtitle);
			$graph->footer->left->SetColor($font_color);
			$graph->footer->left->SetFont($font,FS_NORMAL,8);

			// Create pie plot
			$p1 = new PiePlot3d($data);
			$p1->SetSize(0.30);
			$p1->SetTheme("water");
			$p1->SetCenter(0.33,0.35);
			$p1->SetAngle(30);
			$p1->value->SetFont($font,FS_NORMAL,12);
			$p1->SetLegends($visible_legends);
			$p1->SetLabelType(PIE_VALUE_ABS);
			$p1->value->SetFormat(getCurrencySymbol().'%d');

			//set client side image map URL's
			$p1->SetCSIMTargets($aTargets,$aAlts);

			$graph->Add($p1);

			$graph->Stroke($cache_file_name);
			$imgMap = $graph->GetHTMLImageMap('pipeline_by_lead_source');
			save_image_map($cache_file_name.'.map', $imgMap);
		}
		else {
			$imgMap_fp = fopen($cache_file_name.'.map', "rb");
			$imgMap = fread($imgMap_fp, filesize($cache_file_name.'.map'));
			fclose($imgMap_fp);
		}
		$fileModTime = filemtime($cache_file_name.'.map');
		$return = "\n$imgMap\n";
		$return .= "<img src='$cache_file_name?modTime=$fileModTime'\n";
		$return .= "ismap usemap='#pipeline_by_lead_source' border='0'>\n";
		return $return;

	}

}


/**
 * Creates a file with the image map
 * param $filename - file name to save to
 * param $image_map - image map string to save
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function save_image_map($filename,$image_map) {
	// save the image map to file
	$log =& LoggerManager::getLogger('save_image_file');

	if (!$handle = fopen($filename, 'w')) {
		$log->debug("Cannot open file ($filename)");
		return;
	}

	// Write $somecontent to our opened file.
	if (fwrite($handle, $image_map) === FALSE) {
	   $log->debug("Cannot write to file ($filename)");
	   return false;
	}

	$log->debug("Success, wrote ($image_map) to file ($filename)");

	fclose($handle);
	return true;

}

// retrieve the translated strings.
$app_strings = return_application_language($current_language);

if(isset($app_strings['LBL_CHARSET']))
{
	$charset = $app_strings['LBL_CHARSET'];
}
else
{
	$charset = $default_charset;
}


?>
