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

include ("../../jpgraph/src/jpgraph.php");
include ("../../jpgraph/src/jpgraph_bar.php");


$referdata=$_REQUEST['referdata'];
$refer_code=$_REQUEST['refer_code'];
$width=($_REQUEST['width'])?$_REQUEST['width']:410;
$height=($_REQUEST['height'])?$_REQUEST['height']:270;
$left=(isset($_REQUEST['left']))?$_REQUEST['left']:50;
$right=(isset($_REQUEST['right']))?$_REQUEST['right']:30;
$top=(isset($_REQUEST['top']))?$_REQUEST['top']:50;
$bottom=(isset($_REQUEST['bottom']))?$_REQUEST['bottom']:50;
$title=(isset($_REQUEST['title']))?$_REQUEST['title']:"Horizontal graph";
$target_val=(isset($_REQUEST['target_val']))?$_REQUEST['target_val']:"";



$datay=explode(",",$referdata);
$datax=explode(",",$refer_code);


$temp=array();
for($i=0;$i<count($datax);$i++)
{
	$name=$datax[$i];
	$pos = substr_count($name," ");

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

//$datax=explode(",",$refer_code);


$target=explode(",",$target_val);


// Set the basic parameters of the graph
$graph = new Graph($width,$height,'auto');
$graph->SetScale("textlin");


$graph->Set90AndMargin($left,$right,$top,$bottom);

$graph->xaxis->SetPos('min');

// Nice shadow
$graph->SetShadow();

// Setup title

//$graph->title->Set("Horizontal bar graph ex 3");
$graph->title->Set($title);
$graph->title->SetFont(FF_FONT2,FS_BoLD,14);
//$graph->subtitle->Set("(Axis at bottom)");

// Setup X-axis
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetFont(FF_FONT2,FS_NORMAL,12);

// Some extra margin looks nicer
$graph->xaxis->SetLabelMargin(5);

// Label align for X-axis
$graph->xaxis->SetLabelAlign('right','center');

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

// To align the title to the right use :
//$graph->yaxis->SetTitle('Turnaround 2002','high');
//$graph->yaxis->title->Align('right');


// To center the title use :
//$graph->yaxis->SetTitle('Turnaround 2002','center');
//$graph->yaxis->title->Align('center');


$graph->yaxis->title->SetFont(FF_FONT2,FS_BOLD,12);
$graph->yaxis->title->SetAngle(0);



$graph->yaxis->SetFont(FF_FONT2,FS_NORMAL);
// If you want the labels at an angle other than 0 or 90
// you need to use TTF fonts
//$graph->yaxis->SetLabelAngle(0);

// Now create a bar pot
$bplot = new BarPlot($datay);
$bplot->SetFillColor("aquamarine3");
//$bplot->SetShadow();

//You can change the width of the bars if you like
//$bplot->SetWidth(0.5);

// We want to display the value of each bar at the top
$bplot->value->Show();
$bplot->value->SetFont(FF_FONT2,FS_BOLD,12);
$bplot->value->SetAlign('left','center');
$bplot->value->SetColor("black","gray4");
$bplot->value->SetFormat('%d');

$bplot->SetCSIMTargets($target,$datay);

//Added by Jaguar
$graph->SetBackgroundGradient('#E5E5E5','white',GRAD_VER,BGRAD_PLOT);
$bplot->SetFillGradient("aquamarine3","aquamarine2",GRAD_MIDVER);

//$graph->SetFrame(false);
$graph->SetMarginColor('cadetblue2');
$graph->ygrid->SetFill(true,'azure1','azure2');
$graph->xgrid->Show();


//Ends 

// Add the bar to the graph
$graph->Add($bplot);


$graph->Stroke();
//$graph->StrokeCSIM();

//$imgMap = $graph->GetHTMLImageMap('outcome_by_month');

	echo <<< END
		
	//	$imgMap<img src='$cache_file_name?modTime=$fileModTime' ismap usemap='#outcome_by_month' border='0'>

END;


?>
