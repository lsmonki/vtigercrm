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


include ("jpgraph/src/jpgraph_flags.php");
include ("jpgraph/src/jpgraph_iconplot.php");

/** Function to render the Vertical Bar Graph
        * Portions created by vtiger are Copyright (C) vtiger.
        * All Rights Reserved.
        * Contributor(s): ______________________________________..
 */

function vertical_graph($referdata,$refer_code,$width,$height,$left,$right,$top,$bottom,$title,$target_val,$cache_file_name,$html_image_name)
{

	//We'll be getting the values in the form of a string separated by commas
	$datay=explode(",",$referdata); //The datay values
	$datax=explode(",",$refer_code); // The datax values
	$target_val=urldecode($target_val);// The links values for bar are given as string in the encoded form, here we are decoding it
        $target=explode(",",$target_val);

	$alts=array(); //Array which contains the data which is displayed on the mouse over
	$temp=array();
	for($i=0;$i<count($datax);$i++)
	{
		$name=$datax[$i];
		$alts[]=$name."=%d";

		if(strlen($name)>=14)
			$name=substr($name, 0, 14);
		$temp[]=$name;
	}
	$datax=$temp;

	$max=0;
	for($i=0;$i<count($datay); $i++)
	{
		$x=$datay[$i];
		if($x>=$max)
			$max=$x;
		else
			$max=$max;
	}

	// Create the graph. These two calls are always required
	$graph = new Graph($width,$height,"auto");    
	$graph->SetScale("textlin");
	$graph->yaxis->scale->SetGrace(20);

	// Add a drop shadow
	$graph->SetShadow();

	// Adjust the margin a bit to make more room for titles
	$graph->img->SetMargin($left,$right,$top,$bottom);



	// Create a bar pot
	$bplot = new BarPlot($datay);

	// Adjust fill color
	$bplot->SetFillColor('orange');
	$bplot->value->Show();
	$graph->Add($bplot);

	// Setup the titles
	$graph->title->Set($title);

	// Make the bar a little bit wider
	$bplot->SetWidth(0.2);

	// Setup X-axis
	$graph->xaxis->SetTickLabels($datax);
	$graph->xaxis->SetFont(FF_FONT2,FS_Bold,12);


	$graph->xaxis->SetLabelAlign('center','top');
	$graph->xaxis->SetLabelAngle(90);

	$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
	if($max>=5)
	{
		$graph->yaxis->SetLabelFormat('%d');
	}

	// Setup color for gradient fill style
	$bplot->SetFillGradient("navy","lightsteelblue",GRAD_MIDVER);
	$graph->SetFrame(false);
	$graph->SetMarginColor('white');
	//$graph->ygrid->SetFill(true,'azure1','azure2');
	$graph->xgrid->Show();

	// To get the Targets 
	$bplot->SetCSIMTargets($target,$alts);

	// Set color for the frame of each bar
	$bplot->SetColor("navy");


	$graph->SetBackgroundGradient('#F2FBDB','white',GRAD_VER,BGRAD_PLOT);

	$graph->title->SetFont(FF_FONT1,FS_BOLD);

	$img_path="test/logo/vtiger-crm-logo.jpg";

	$icon = new IconPlot($img_path,0.7,0.3,1,30);
	$icon->SetAnchor('center','center');
	$graph->Add($icon);


	$bplot->value->SetFormat('%d');

	// Display the graph
//	$graph->Stroke(); 

        //Getting the graph in the form of html page
	$graph-> Stroke( $cache_file_name );
	$imgMap = $graph ->GetHTMLImageMap ($html_image_name);
        save_image_map($cache_file_name.'.map', $imgMap);
        $base_name_cache_file=basename($cache_file_name);
        $ccc="cache/images/".$base_name_cache_file;
        $img= "<img src=$ccc ismap usemap='#$html_image_name' border=0>" ;
        $img.=$imgMap;
        echo $img;

}
?>
