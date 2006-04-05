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
require_once('Image/Graph.php');
require_once('include/utils/utils.php');
require_once('include/utils/GraphUtils.php');




class jpgraph {
	/**
	 * Creates opportunity pipeline image as a horizontal accumlated bar graph for multiple users.
	 * param $datax- the month data to display in the x-axis
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	 */
	function outcome_by_month($date_start='1971-10-15', $date_end='2071-10-15', $user_id=array('1'), $cache_file_name='a_file', $refresh=false) {
		global $app_strings,$lang_crm, $app_list_strings, $current_module_strings,$current_user, $log, $charset, $tmp_dir;
		global $theme;
		include_once ('Image/Graph.php');
		include_once ('Image/Canvas.php');

		// Size of graph
		$width=600;
		$height=400;

		$log =& LoggerManager::getLogger('outcome_by_month chart');
		// Set the basic parameters of the graph
		$canvas =& Image_Canvas::factory('png', array('width' => $width, 'height' => $height, 'usemap' => true));
		$imagemap = $canvas->getImageMap();
		$graph =& Image_Graph::factory('graph', $canvas);
		$log->debug("graph object created");

		// add a TrueType font
		$font =& $graph->addNew('font', calculate_font_name($lang_crm));
		// set the font size to 11 pixels
		$font->setSize(8);
		
		$graph->setFont($font);
		// create the plotarea layout
        $title =& Image_Graph::factory('title', array('Title',10));
    	$plotarea =& Image_Graph::factory('plotarea',array(
                    'category',
                    'axis'
                ));
        $footer =& Image_Graph::factory('title', array('Footer',8));
		$graph->add(
		    Image_Graph::vertical($title,
	        Image_Graph::vertical(
				$plotarea,
        	    $footer,
            	90
		        ),
        	5
	    	)
		);   

		//$graph->SetScale("textlin");

		if (!file_exists($cache_file_name) || !file_exists($cache_file_name.'.map') || $refresh == true) {
			//$font = calculate_font_family($lang_crm);

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
			$color = array('Closed Lost'=>'#FF9900','Closed Won'=>'#009933', $other=>'#0066CC');
			$index = 0;
			$datasets = array();
			$fills =& Image_Graph::factory('Image_Graph_Fill_Array');
			foreach($stages as $stage) {
				// Now create a bar plot
				$datasets[$index] = & Image_Graph::factory('dataset');
				foreach($datax[$stage] as $x => $y) {
				    $datasets[$index]->addPoint(
				        $months[$x],
				        $y,
				        array(
				            'url' => $aTargets[$stage][$x],
				            'alt' => $aAlts[$stage][$x]
				        )
				    );
				}

				// Set fill colors for bars
				$fills->addColor($color[$stage]);

				$index++;
			}
			
			// compute maximum value because of grace jpGraph parameter not supported
			$maximum = 0;
			foreach($months as $num=>$m) {
			  	$monthSum = 0;
			  	foreach($stages as $stage) $monthSum += $datax[$stage][$num];
				if($monthSum > $maximum) $maximum = $monthSum;
				$log->debug('maximum = '.$maximum.' month = '.$m.' sum = '.$monthSum);
			}

			if($theme == "blue")
			{
				$font_color = "#212473";
			}
			else
			{
				$font_color = "#000000";
			}
			$font->setColor($font_color);

			// Create the grouped bar plot
			$gbplot = & $plotarea->addNew('bar', array($datasets, 'stacked'));
			$gbplot->setFillStyle($fills);

			//You can change the width of the bars if you like
			$gbplot->setBarWidth(50/count($months),"%");

			// set margin
			$plotarea->setPadding(array('top'=>0,'bottom'=>0,'left'=>10,'right'=>20));

			// Set white margin color
			$graph->setBackgroundColor('#F5F5F5');

			// Use a box around the plot area
			$gbplot->setBorderColor('black');

			// Use a gradient to fill the plot area
			$gbplot->setBackground(Image_Graph::factory('gradient', array(IMAGE_GRAPH_GRAD_VERTICAL, 'white', '#E5E5E5')));

			// Setup title
			$titlestr = $current_module_strings['LBL_TOTAL_PIPELINE'].$curr_symbol.$total.$app_strings['LBL_THOUSANDS_SYMBOL'];
			$title->setText($titleStr);

			$xaxis =& $plotarea->getAxis(IMAGE_GRAPH_AXIS_X);
			$yaxis =& $plotarea->getAxis(IMAGE_GRAPH_AXIS_Y);

			// Setup X-axis
			$yaxis->setFontSize(8);

			// set grid
			$gridY =& $plotarea->addNew('line_grid', IMAGE_GRAPH_AXIS_Y);
			$gridY->setLineColor('#E5E5E5@0.5');


			// Add some grace to y-axis so the bars doesn't go
			// all the way to the end of the plot area
			$yaxis->forceMaximum($maximum * 1.1);

			// Setup the Y-axis to be displayed in the bottom of the
			// graph. We also finetune the exact layout of the title,
			// ticks and labels to make them look nice.
			$yaxis->setAxisIntersection('max');

			// Then fix the tick marks
			$valueproc =& Image_Graph::factory('Image_Graph_DataPreprocessor_Formatted', $curr_symbol."%d");
			$yaxis->setDataPreprocessor($valueproc);
			// Fix X-Axis tick marks inside
			$xaxis->setTickOptions(0,5);
			// Arrange Y-Axis tick marks inside
			$yaxis->setLabelInterval(1000);
			$yaxis->setTickOptions(-5,0);
			$yaxis->setLabelInterval(500,2);
			$yaxis->setTickOptions(-2,0,2);
			$yaxis->setLabelOption('position','inside');

			// Finally setup the title
			$yaxis->setLabelOption('position','inside');
			
			// eliminate zero values
			$gbplot->setDataSelector(Image_Graph::factory('Image_Graph_DataSelector_NoZeros'));
			
			// set markers
			$marker =& $graph->addNew('value_marker', IMAGE_GRAPH_VALUE_Y);
			$marker->setDataPreprocessor($valueproc);
			$marker->setFillColor('000000@0.0');
			$marker->setBorderColor('000000@0.0');
			$marker->setFontColor('white');
			$marker->setFontSize(8);
			$gbplot->setMarker($marker);

			$subtitle .= $current_module_strings['LBL_OPP_SIZE'].$curr_symbol.$current_module_strings['LBL_OPP_SIZE_VALUE'];
			$footer->setText($subtitle);
			$footer->setAlignment(IMAGE_GRAPH_ALIGN_TOP_RIGHT);

			// .. and stroke the graph
			$imgMap = $graph->done(
								    array(
									        'tohtml' => true,
									        'border' => 0,
									        'filename' => $cache_file_name,
									        'filepath' => './',
									        'urlpath' => ''
									    ));
			//$imgMap = htmlspecialchars($output);
			save_image_map($cache_file_name.'.map', $imgMap);
		}
		else {
			$imgMap_fp = fopen($cache_file_name.'.map', "rb");
			$imgMap = fread($imgMap_fp, filesize($cache_file_name.'.map'));
			fclose($imgMap_fp);
		}
		$fileModTime = filemtime($cache_file_name.'.map');
		$return = "\n$imgMap";
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

		include_once ('Image/Graph.php');
		include_once ('Image/Canvas.php');

		// Size of graph
		$width=600;
		$height=400;

		$log =& LoggerManager::getLogger('lead_source_by_outcome chart');
		// Set the basic parameters of the graph
		$canvas =& Image_Canvas::factory('png', array('width' => $width, 'height' => $height, 'usemap' => true));
		$imagemap = $canvas->getImageMap();
		$graph =& Image_Graph::factory('graph', $canvas);
		$log->debug("graph object created");
		// add a TrueType font
		$font =& $graph->addNew('font', calculate_font_name($lang_crm));
		// set the font size to 11 pixels
		$font->setSize(8);
		
		$graph->setFont($font);
		// create the plotarea layout
        $title =& Image_Graph::factory('title', array('Test',10));
    	$plotarea =& Image_Graph::factory('plotarea',array(
                    'category',
                    'axis',
                    'horizontal'
                ));
        $footer =& Image_Graph::factory('title', array('Footer',8));
		$graph->add(
		    Image_Graph::vertical($title,
	        Image_Graph::vertical(
				$plotarea,
        	    $footer,
            	90
		        ),
        	5
	    	)
		);   

		if (!file_exists($cache_file_name) || !file_exists($cache_file_name.'.map') || $refresh == true) {

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
			$color = array('Closed Lost'=>'FF9900','Closed Won'=>'009933', $other=>'0066CC');
			$index = 0;
			$datasets = array();
			$fills =& Image_Graph::factory('Image_Graph_Fill_Array');
			foreach($stages as $stage) {
				// Now create a bar pot
				$datasets[$index] = & Image_Graph::factory('dataset');
				foreach($datax[$stage] as $x => $y) {
				    $datasets[$index]->addPoint(
				        $datay[$legend[$x]],
				        $y,
				        array(
				            'url' => $aTargets[$stage][$x],
				            'alt' => $aAlts[$stage][$x],
				            'target' => '_blank'
				        )
				    );
				}

				// Set fill colors for bars
				$fills->addColor("#".$color[$stage]);

				$log->debug("datax[$stage] is: ");
				$log->debug($datax[$stage]);
				$index++;
			}
			
			// compute maximum value because of grace jpGraph parameter not supported
			$maximum = 0;
			foreach($legend as $legendidx=>$legend_text) {
			  	$dataxSum = 0;
				foreach($stages as $stage) $dataxSum += $datax[$stage][$legendidx];
				if($dataxSum > $maximum) $maximum = $dataxSum;
			}

			if($theme == "blue")
			{
				$font_color = "#212473";
			}
			else
			{
				$font_color = "#000000";
			}
			$font->setColor($font_color);

			// Create the grouped bar plot
			$gbplot = & $plotarea->addNew('bar', array($datasets, 'stacked'));
			$gbplot->setFillStyle($fills);

			//You can change the width of the bars if you like
			$gbplot->setBarWidth(50/count($legend),"%");

			// Set white margin color
			$graph->setBackgroundColor('#F5F5F5');

			// Use a box around the plot area
			$gbplot->setBorderColor('black');

			// Use a gradient to fill the plot area
			$gbplot->setBackground(Image_Graph::factory('gradient', array(IMAGE_GRAPH_GRAD_HORIZONTAL, 'white', '#E5E5E5')));

			// Setup title
			$titlestr = $current_module_strings['LBL_ALL_OPPORTUNITIES'].$curr_symbol.$total.$app_strings['LBL_THOUSANDS_SYMBOL'];
			$title->setText($titlestr);

			// Setup X-axis
			$xaxis =& $plotarea->getAxis(IMAGE_GRAPH_AXIS_X);
			$yaxis =& $plotarea->getAxis(IMAGE_GRAPH_AXIS_Y);
			$yaxis->setFontSize(8);
			$xaxis->setInverted(true);
			$yaxis->setAxisIntersection('max');
			
			// set grid
			$gridY =& $plotarea->addNew('line_grid', IMAGE_GRAPH_AXIS_Y);
			$gridY->setLineColor('#E5E5E5@0.5');

			// Then fix the tick marks
			$valueproc =& Image_Graph::factory('Image_Graph_DataPreprocessor_Formatted', $curr_symbol."%d");
			$yaxis->setDataPreprocessor($valueproc);
			$yaxis->setLabelInterval(1000);
			$yaxis->setTickOptions(-5,0);
			$yaxis->setLabelInterval(500,2);
			$yaxis->setTickOptions(-2,0,2);

			// Add some grace to y-axis so the bars doesn't go
			// all the way to the end of the plot area
			$yaxis->forceMaximum($maximum * 1.1);
			
			// eliminate zero values
			$gbplot->setDataSelector(Image_Graph::factory('Image_Graph_DataSelector_NoZeros'));
			
			// set markers
			$marker =& $graph->addNew('value_marker', IMAGE_GRAPH_VALUE_Y);
			$marker->setDataPreprocessor($valueproc);
			$marker->setFillColor('#000000@0.0');
			$marker->setBorderColor('#000000@0.0');
			$marker->setFontColor('white');
			$marker->setFontSize(8);
			$gbplot->setMarker($marker);


			// The fix the tick marks
			$xaxis->setTickOptions(0,5);

			// Finally setup the title
			$subtitle = $current_module_strings['LBL_OPP_SIZE'].$curr_symbol.$current_module_strings['LBL_OPP_SIZE_VALUE']; 
			$footer->setText($subtitle);
			$footer->setAlignment(IMAGE_GRAPH_ALIGN_TOP_RIGHT);

			// .. and stroke the graph
			$imgMap = $graph->done(
								    array(
									        'tohtml' => true,
									        'border' => 0,
									        'filename' => $cache_file_name,
									        'filepath' => './',
									        'urlpath' => ''
									    ));
			//$imgMap = htmlspecialchars($output);
			save_image_map($cache_file_name.'.map', $imgMap);
		}
		else {
			$imgMap_fp = fopen($cache_file_name.'.map', "rb");
			$imgMap = fread($imgMap_fp, filesize($cache_file_name.'.map'));
			fclose($imgMap_fp);
		}
		$fileModTime = filemtime($cache_file_name.'.map');
		$return = "\n$imgMap";
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
		include_once ('Image/Graph.php');
		include_once ('Image/Canvas.php');

		// Size of graph
		$width=600;
		$height=400;

		$log =& LoggerManager::getLogger('opportunity charts');
		// Set the basic parameters of the graph

		
		$canvas =& Image_Canvas::factory('png', array('width' => $width, 'height' => $height, 'usemap' => true));
		$imagemap = $canvas->getImageMap();
		$graph =& Image_Graph::factory('graph', $canvas);
		//$log->debug("graph object created");
		// add a TrueType font
		//$font =& $graph->addNew('font', calculate_font_name($lang_crm));
		$font =& $graph->addNew('font', calculate_font_name($lang_crm));
		// set the font size to 11 pixels
		$font->setSize(8);
		
		$graph->setFont($font);
        $title =& Image_Graph::factory('title', array('Test',10));
    	$plotarea =& Image_Graph::factory('plotarea',array(
                    'category',
                    'axis',
                    'horizontal'
                ));
        $footer =& Image_Graph::factory('title', array('Footer',8));
		$graph->add(
		    Image_Graph::vertical($title,
	        Image_Graph::vertical(
				$plotarea,
        	    $footer,
            	90
		        ),
        	5
	    	)
		);   
		$log->debug("graph object created");
		

		if (!file_exists($cache_file_name) || !file_exists($cache_file_name.'.map') || $refresh == true) {

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
			$colors = color_generator(count($user_id),'#D50100','#002222');
			$index = 0;
			$datasets = array();
			$fills =& Image_Graph::factory('Image_Graph_Fill_Array');
			foreach($user_id as $the_id) {
				// Now create a bar pot
				$datasets[$index] = & Image_Graph::factory('dataset');
				foreach($datay[$the_id] as $x => $y) {
				    $datasets[$index]->addPoint(
				        $legend[$x],
				        $y,
				        array(
				            'url' => $aTargets[$the_id][$x],
				            'alt' => $aAlts[$the_id][$x]
				        )
				    );
				}

				// Set fill colors for bars
				$fills->addColor($colors[$index]);

				$index++;
			}
			
			// compute maximum value because of grace jpGraph parameter not supported
			$maximum = 0;
			foreach($legend as $legendidx=>$legend_text) {
			  	$legendsum = 0;
				foreach($user_id as $the_id) $legendsum += $datay[$the_id][$legendidx];
				if($legendsum > $maximum) $maximum = $legendsum;
			}
			// Create the grouped bar plot
			$gbplot = & $plotarea->addNew('bar', array($datasets, 'stacked'));
			$gbplot->setFillStyle($fills);

			//You can change the width of the bars if you like
			$gbplot->setBarWidth(50/count($legend),"%");


			// Set white margin color
			$graph->setBackgroundColor('#F5F5F5');

			// Use a box around the plot area
			$gbplot->setBorderColor('black');

			// Use a gradient to fill the plot area
			$gbplot->setBackground(Image_Graph::factory('gradient', array(IMAGE_GRAPH_GRAD_HORIZONTAL, 'white', '#E5E5E5')));

			if($theme == "blue")
			{
				$font_color = "#212473";
			}
			else
			{
				$font_color = "#000000";
			}
			$font->setColor($font_color);

			// Setup title
			$titlestr = $current_module_strings['LBL_TOTAL_PIPELINE'].$curr_symbol.$total.$app_strings['LBL_THOUSANDS_SYMBOL'];
			$title->setText($titlestr);

			// Setup X-axis
			$xaxis =& $plotarea->getAxis(IMAGE_GRAPH_AXIS_X);
			$yaxis =& $plotarea->getAxis(IMAGE_GRAPH_AXIS_Y);
			$yaxis->setFontSize(8);
			// Invert X-axis and put Y-axis at bottom
			$xaxis->setInverted(true);
			$yaxis->setAxisIntersection('max');
			
			// set grid
			$gridY =& $plotarea->addNew('line_grid', IMAGE_GRAPH_AXIS_Y);
			$gridY->setLineColor('#E5E5E5@0.5');


			// Add some grace to y-axis so the bars doesn't go
			// all the way to the end of the plot area
			$yaxis->forceMaximum($maximum * 1.1);

			// First make the labels look right
			$valueproc =& Image_Graph::factory('Image_Graph_DataPreprocessor_Formatted', $curr_symbol."%d");
			$yaxis->setDataPreprocessor($valueproc);
			$yaxis->setLabelInterval(1000);
			$yaxis->setTickOptions(-5,0);
			$yaxis->setLabelInterval(500,2);
			$yaxis->setTickOptions(-2,0,2);

			// The fix the tick marks
			$xaxis->setTickOptions(0,5);
			
			// eliminate zero values
			$gbplot->setDataSelector(Image_Graph::factory('Image_Graph_DataSelector_NoZeros'));
			
			// set markers
			$marker =& $graph->addNew('value_marker', IMAGE_GRAPH_VALUE_Y);
			$marker->setDataPreprocessor($valueproc);
			$marker->setFillColor('000000@0.0');
			$marker->setBorderColor('000000@0.0');
			$marker->setFontColor('white');
			$marker->setFontSize(8);
			$gbplot->setMarker($marker);

			// Finally setup the title

			$subtitle .= $current_module_strings['LBL_OPP_SIZE'].$curr_symbol.$current_module_strings['LBL_OPP_SIZE_VALUE']; 
			$footer->setText($subtitle);
			$footer->setAlignment(IMAGE_GRAPH_ALIGN_TOP_RIGHT);

			// .. and stroke the graph
			$imgMap = $graph->done(
								    array(
									        'tohtml' => true,
									        'border' => 0,
									        'filename' => $cache_file_name,
									        'filepath' => './',
									        'urlpath' => ''
									    ));
			//$imgMap = $graph->GetHTMLImageMap('pipeline');
			save_image_map($cache_file_name.'.map', $imgMap);
		}
		else {
			$imgMap_fp = fopen($cache_file_name.'.map', "rb");
			$imgMap = fread($imgMap_fp, filesize($cache_file_name.'.map'));
			fclose($imgMap_fp);
		}
		$fileModTime = filemtime($cache_file_name.'.map');
		$return = "\n$imgMap";
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

		include_once ('Image/Graph.php');
		include_once ('Image/Canvas.php');

		$font = calculate_font_name($lang_crm);

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
			$width = 600;
			$height = 400;
	
			$canvas =& Image_Canvas::factory('png', array('width' => $width, 'height' => $height, 'usemap' => true));
			$imagemap = $canvas->getImageMap();
			$graph =& Image_Graph::factory('graph', $canvas);
	
			$font =& $graph->addNew('font', calculate_font_name('en_en'));
			// set the font size to 11 pixels
			$font->setSize(8);
			$font->setColor($font_color);
			
			$graph->setFont($font);
			// create the plotarea layout
	        $title =& Image_Graph::factory('title', array('Test',10));
	    	$plotarea =& Image_Graph::factory('plotarea',array(
                    'category',
                    'axis'
                ));
	        $footer =& Image_Graph::factory('title', array('Footer',8));
			$graph->add(
			    Image_Graph::vertical($title,
		        Image_Graph::vertical(
					$plotarea,
	        	    $footer,
	            	90
			        ),
	        	5
		    	)
			);   

			// Generate colours
			$colors = color_generator(count($visible_legends),'#33CCFF','#3322FF');
			$index = 0;
			$dataset = & Image_Graph::factory('dataset');
			$fills =& Image_Graph::factory('Image_Graph_Fill_Array');
			foreach($visible_legends as $legend) {
			    $dataset->addPoint(
			        $legend,
			        $data[$index],
			        array(
			            'url' => $aTargets[$index],
			            'alt' => $aAlts[$index]
			        )
			    );
				$fills->addColor($colors[$index]);
			    $log->debug('point ='.$legend.','.$data[$index]);

				$index++;
			}

			// create the pie chart and associate the filling colours			
			$gbplot = & $plotarea->addNew('pie', $dataset);
			$plotarea->hideAxis();
			$gbplot->setFillStyle($fills);

			// Setup title
			$titlestr = $current_module_strings['LBL_TOTAL_PIPELINE'].$curr_symbol.$total.$app_strings['LBL_THOUSANDS_SYMBOL'];
			$title->setText($titlestr);

			// format the data values
			$valueproc =& Image_Graph::factory('Image_Graph_DataPreprocessor_Formatted', $curr_symbol."%d");

			// set markers
			$marker =& $graph->addNew('value_marker', IMAGE_GRAPH_VALUE_Y);
			$marker->setDataPreprocessor($valueproc);
			$marker->setFillColor('#FFFFFF');
			$marker->setBorderColor($font_color);
			$marker->setFontColor($font_color);
			$marker->setFontSize(8);
			$pointingMarker =& $graph->addNew('Image_Graph_Marker_Pointing_Angular', array(20, &$marker));
			$gbplot->setMarker($pointingMarker);
			
			// set legend
			$legend_box =& $plotarea->addNew('legend');
			$legend_box->setPadding(array('top'=>20,'bottom'=>0,'left'=>0,'right'=>0));
			$legend_box->setFillColor('#F5F5F5');
			$legend_box->showShadow();

			$subtitle = $current_module_strings['LBL_OPP_SIZE'].$curr_symbol.$current_module_strings['LBL_OPP_SIZE_VALUE'];
			$footer->setText($subtitle);
			$footer->setAlignment(IMAGE_GRAPH_ALIGN_TOP_LEFT);

			$imgMap = $graph->done(
								    array(
									        'tohtml' => true,
									        'border' => 0,
									        'filename' => $cache_file_name,
									        'filepath' => './',
									        'urlpath' => ''
									    ));
			//$imgMap = htmlspecialchars($output);
			save_image_map($cache_file_name.'.map', $imgMap);
		}
		else {
			$imgMap_fp = fopen($cache_file_name.'.map', "rb");
			$imgMap = fread($imgMap_fp, filesize($cache_file_name.'.map'));
			fclose($imgMap_fp);
		}
		$fileModTime = filemtime($cache_file_name.'.map');
		$return = "\n$imgMap";
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
