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
 * $Header:  vtiger_crm/sugarcrm/modules/Opportunities/Charts.php,v 1.3 2004/08/24 11:03:24 rakeebk Exp $
 * Description:  Includes the functions for Customer module specific charts.
 ********************************************************************************/

require_once('config.php');
require_once('include/logging.php');
require_once('modules/Opportunities/Opportunity.php');
require_once("jpgraph/src/jpgraph.php");


/*
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
*/

function calculate_font_family($charset)
{
	switch($charset)
	{
		case 'gb2312':
			return FF_SIMSUN;
		default:
			return FF_FONT1;
	}
	
	return FF_FONT1;
}


class jpgraph {
	/**
	 * Creates opportunity pipeline image as a horizontal bar graph.
	 * param $datax- the sales stage data to display in the x-axis
	 * param $datay- the sum of opportunity amounts for each opportunity in each sales stage 
	 * to display in the y-axis
	 */
	function pipeline ($datax=array('foo','bar'),$datay=array(1,2), $title='the title', $subtitle='the subtitle') {
		global $app_strings, $log, $charset;
		include ("jpgraph/src/jpgraph_bar.php");
		
		$font = calculate_font_family($charset);
	
		$log =& LoggerManager::getLogger('opportunity charts');
		$log->debug("starting pipeline chart");
		
		// Size of graph
		$width=300; 
		$height=400;
		
		// Set the basic parameters of the graph 
		$graph = new Graph($width,$height,'auto');
		$graph->SetScale("textlin");
		//$graph->img->SetImgFormat("jpeg")
		
		// No frame around the image
		$graph->SetFrame(false);
		
		// Rotate graph 90 degrees and set margin
		$top = 20;
		$bottom = 60;
		$left = 130;
		$right = 20;
		$graph->Set90AndMargin($left,$right,$top,$bottom);
		
		// Set white margin color
		$graph->SetMarginColor('white');
		
		// Use a box around the plot area
		$graph->SetBox();
		
		// Use a gradient to fill the plot area
		$graph->SetBackgroundGradient('#F0F0F0','#FFFFFF',GRAD_HOR,BGRAD_PLOT);
		
		// Setup title
		$graph->title->Set($title);
		$graph->title->SetFont($font,FS_BOLD,11);
		
		// Setup X-axis
		$graph->xaxis->SetTickLabels($datax);
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
		$graph->yaxis->SetLabelAlign('center','top');
		$graph->yaxis->SetLabelFormat($app_strings['LBL_CURRENCY_SYMBOL'].'%d');
		$graph->yaxis->SetLabelSide(SIDE_RIGHT);
		
		// The fix the tick marks
		$graph->yaxis->SetTickSide(SIDE_LEFT);
		
		// Finally setup the title
		$graph->yaxis->SetTitleSide(SIDE_RIGHT);
		$graph->yaxis->SetTitleMargin(35);
		
		// To align the title to the right use :
		$graph->yaxis->SetTitle($subtitle);
		$graph->yaxis->title->Align('right');
		
		$graph->yaxis->title->SetFont($font,FS_NORMAL,8);
		$graph->yaxis->title->SetAngle(0);
		
		$graph->yaxis->SetFont($font,FS_NORMAL, 8);
		
		// Now create a bar pot
		$bplot = new BarPlot($datay);
		$bplot->SetShadow();
		
		//You can change the width of the bars if you like
		//$bplot->SetWidth(0.5);
		
		// Set gradient fill for bars
		$bplot->SetFillGradient('#990000','#FF6600',GRAD_HOR);  // orange to red
//		$bplot->SetFillGradient('#339900','#99CC00',GRAD_HOR);  // light green to dark green
//		$bplot->SetFillGradient('#0033CC','#0099FF',GRAD_HOR);  // light blue to dark blue
		
		// We want to display the value of each bar at the top
		$bplot->value->Show();
		$bplot->value->SetFont($font,FS_NORMAL,8);
		//$bplot->value->SetAlign('left','center');
		$bplot->value->SetColor("white");
		$bplot->value->SetFormat($app_strings['LBL_CURRENCY_SYMBOL'].'%d');
		$bplot->SetValuePos('max');
		
		// Add the bar to the graph
		$graph->Add($bplot);
		
		// .. and stroke the graph
		$graph->Stroke();
	}
	
	/**
	 * Creates pie chart image of opportunities by lead_source.
	 * param $datax- the sales stage data to display in the x-axis
	 * param $datay- the sum of opportunity amounts for each opportunity in each sales stage 
	 * to display in the y-axis
	 */
	function pipeline_by_lead_source ($legends=array('foo', 'bar'), $data=array(1,2), $title='the title', $subtitle='the subtitle') {
		global $app_strings, $log, $charset;
		include ("jpgraph/src/jpgraph_pie.php");
		include ("jpgraph/src/jpgraph_pie3d.php");

		$font = calculate_font_family($charset);
		
		// Create the Pie Graph.
		$graph = new PieGraph(490,320,"auto");
		$graph->SetShadow();
	
		// Setup title
		$graph->title->Set($title);
		$graph->title->SetFont($font,FS_BOLD,11);
	
		// No frame around the image
		$graph->SetFrame(false);
	
		$graph->legend->Pos(0.03,0.07);
		$graph->legend->SetFont($font,FS_NORMAL,12);
		
		$graph->footer->left->Set($subtitle); 
		$graph->footer->left->SetFont($font,FS_NORMAL,8);

		// Create pie plot
		$p1 = new PiePlot3d($data);
		$p1->SetSize(0.40);
		$p1->SetTheme("sand");
		$p1->SetCenter(0.33);
		$p1->SetAngle(30);
		$p1->value->SetFont($font,FS_NORMAL,12);
		$p1->SetLegends($legends);
		$p1->SetLabelType(PIE_VALUE_ABS);
		$p1->value->SetFormat($app_strings['LBL_CURRENCY_SYMBOL'].'%d'); 
		
		$graph->Add($p1);
		$graph->Stroke();
	
	}

}
?>
