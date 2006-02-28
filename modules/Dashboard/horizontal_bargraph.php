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

include ("jpgraph/src/jpgraph.php");
include ("jpgraph/src/jpgraph_bar.php");
require_once('config.php');

$tmp_dir=$root_directory."cache/images/";

/** Function to render the Horizontal Graph
        * Portions created by vtiger are Copyright (C) vtiger.
        * All Rights Reserved.
        * Contributor(s): ______________________________________..
 */
function horizontal_graph($referdata,$refer_code,$width,$height,$left,$right,$top,$bottom,$title,$target_val,$cache_file_name,$html_image_name)
{

	global $log,$root_directory;
//We'll be getting the values in the form of a string separated by commas
	$datay=explode(",",$referdata); // The datay values  
	$datax=explode(",",$refer_code); // The datax values  

// The links values are given as string in the encoded form, here we are decoding it
	$target_val=urldecode($target_val);
	$target=explode(",",$target_val);

	$alts=array();
	$temp=array();
	for($i=0;$i<count($datax);$i++)
	{
		$name=$datax[$i];
		$pos = substr_count($name," ");
		$alts[]=$name."=%d";
//If the daatx value of a string is greater, adding '\n' to it so that it'll cme inh 2nd line
		 if(strlen($name)>=14)
                        $name=substr($name, 0, 14);
		if($pos>=2)
		{
			$val=explode(" ",$name);
			$n=count($val)-1;

			$x="";
			for($j=0;$j<count($val);$j++)
			{
				if($j != $n)
				{
					$x  .=" ". $val[$j];
				}
				else
				{
					$x .= "@#".$val[$j];
				}
			}
			$name = $x;
		}
		$name=str_replace("@#", "\n",$name);
		$temp[]=$name; 
	}
	$datax=$temp;

	//datay is the values
	//datax is the status

	// Set the basic parameters of the graph
	$graph = new Graph($width,$height,'auto');
	$graph->SetScale("textlin");

	// To get a horizontal Graph	
	$graph->Set90AndMargin($left,$right,$top,$bottom);

	$graph->xaxis->SetPos('min');

	// Nice shadow
	$graph->SetShadow();

	// Setup title
	$graph->title->Set($title);
	$graph->title->SetFont(FF_FONT2,FS_BOLD,14);

	// Setup X-axis
	$graph->xaxis->SetTickLabels($datax);
	$graph->xaxis->SetFont(FF_FONT2,FS_NORMAL,12);

	// Some extra margin looks nicer
	$graph->xaxis->SetLabelMargin(5);

	// Label align for X-axis
	$graph->xaxis->SetLabelAlign('right','center');

	if($max>=5)
	{
		$graph->xaxis->SetLabelFormat('%d');
	}

	// Add some grace to y-axis so the bars doesn't go
	// all the way to the end of the plot area
	$graph->yaxis->scale->SetGrace(20);

	// Setup the Y-axis to be displayed in the bottom of the
	// graph. We also finetune the exact layout of the title,
	// ticks and labels to make them look nice.
	$graph->yaxis->SetPos('max');

	// First make the labels look right
	$graph->yaxis->SetLabelAlign('center','top');
	$graph->yaxis->SetLabelFormat('%d');
	$graph->yaxis->SetLabelSide(SIDE_RIGHT);

	// The fix the tick marks
	//$graph->yaxis->SetTickSide(SIDE_LEFT);

	// Finally setup the title
	$graph->yaxis->SetTitleSide(SIDE_RIGHT);
	$graph->yaxis->SetTitleMargin(35);

	$graph->yaxis->title->SetFont(FF_FONT2,FS_BOLD,12);
	$graph->yaxis->title->SetAngle(0);

	$graph->yaxis->SetFont(FF_FONT2,FS_NORMAL);

	// Now create a bar pot
	$max=0;
	for($i=0;$i<count($datay); $i++)
	{
		$x=$datay[$i];
		if($x>=$max)
			$max=$x;
		else
			$max=$max;
	}


	$bplot = new BarPlot($datay);
	$bplot->SetFillColor("aquamarine3");

	//You can change the width of the bars if you like
	$bplot->SetWidth(0.2);

	// We want to display the value of each bar at the top
	$bplot->value->Show();
	$bplot->value->SetFont(FF_FONT2,FS_BOLD,12);
	$bplot->value->SetAlign('left','center');
	$bplot->value->SetColor("black","gray4");
	$bplot->value->SetFormat('%d');

	//Adding this top get usemap	
	$bplot->SetCSIMTargets($target,$alts);

	$graph->SetBackgroundGradient('#E5E5E5','white',GRAD_VER,BGRAD_PLOT);
	$bplot->SetFillGradient("navy","lightsteelblue",GRAD_MIDVER);

	$graph->SetFrame(false);
	$graph->SetMarginColor('cadetblue2');
	$graph->ygrid->SetFill(true,'azure1','azure2');
	$graph->xgrid->Show();

	// Add the bar to the graph
	$graph->Add($bplot);


	//Getting the graph in the form of html page	
	$graph-> Stroke( $cache_file_name );
	$imgMap = $graph ->GetHTMLImageMap ($html_image_name);
	save_image_map($cache_file_name.'.map', $imgMap);
	$base_name_cache_file=basename($cache_file_name);
	$ccc="cache/images/".$base_name_cache_file;
	$img = "<img src=$ccc ismap usemap='#$html_image_name' border=0>" ;
	$img.=$imgMap;
	return $img;
}

?>
