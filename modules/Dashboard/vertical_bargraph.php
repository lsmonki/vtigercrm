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

//$datay=array(12,8,19,3,10,5);
$datay=explode(",",$referdata);
$datax=explode(",",$refer_code);

// Create the graph. These two calls are always required
$graph = new Graph($width,$height,"auto");    
$graph->SetScale("textlin");
$graph->yaxis->scale->SetGrace(20);

// Add a drop shadow
$graph->SetShadow();

// Adjust the margin a bit to make more room for titles
$graph->img->SetMargin(40,30,20,40);

// Create a bar pot
$bplot = new BarPlot($datay);

// Adjust fill color
$bplot->SetFillColor('orange');
$bplot->value->Show();
$graph->Add($bplot);

// Setup the titles
$graph->title->Set($title);
//$graph->xaxis->title->Set("X-title");
//$graph->yaxis->title->Set("Y-title");

// Make the bar a little bit wider
$bplot->SetWidth(0.2);

// Setup X-axis
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetFont(FF_FONT2,FS_Bold,12);

//$graph->yaxis->SetLabelAlign('center','top');
$graph->yaxis->SetLabelFormat('%d');
//$graph->yaxis->SetLabelSide(SIDE_RIGHT);


// Setup color for gradient fill style
$bplot->SetFillGradient("navy","lightsteelblue",GRAD_MIDVER);
$graph->SetFrame(false);
$graph->SetMarginColor('cadetblue2');
$graph->ygrid->SetFill(true,'azure1','azure2');
$graph->xgrid->Show();



// Set color for the frame of each bar
$bplot->SetColor("navy");


$graph->SetBackgroundGradient('#E5E5E5','white',GRAD_VER,BGRAD_PLOT);

$graph->title->SetFont(FF_FONT1,FS_BOLD);

$bplot->value->SetFormat('%d');
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->SetTickLabels($datax);
//$graph->xaxis->SetLabelAngle(45);
//$graph->xaxis->SetTextAlign('center','top');    

// Display the graph
$graph->Stroke();
?>
