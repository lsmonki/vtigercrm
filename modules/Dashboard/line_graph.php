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
include ("../../jpgraph/src/jpgraph_line.php");


$refer_code=(isset($_REQUEST['width']))?$_REQUEST['refer_code']:"0,0";
$datax=explode(",",$refer_code); //The values to the XAxis

$status_value=(isset($_REQUEST['width']))?$_REQUEST['referdata']:"null"; //The Status Name 
$status_value=explode(",",$status_value);

//Giving the colors to the Line graph
$color_array=array("red","blue","orange","green","darkorchid","gold1","gray3","lightpink","burlywood2","cadetblue");
$datavalue=$_REQUEST['datavalue'];

//Exploding the Ticket status 
$datavalue=explode("K",$datavalue); 


$width=(isset($_REQUEST['width']))?$_REQUEST['width']:410;
$height=(isset($_REQUEST['height']))?$_REQUEST['height']:270;
$left=(isset($_REQUEST['left']))?$_REQUEST['left']:50;
$right=(isset($_REQUEST['right']))?$_REQUEST['right']:130;
$top=(isset($_REQUEST['top']))?$_REQUEST['top']:50;
$bottom=(isset($_REQUEST['bottom']))?$_REQUEST['bottom']:50;
$title=(isset($_REQUEST['title']))?$_REQUEST['title']:"Horizontal graph";
$target_val=(isset($_REQUEST['target_val']))?$_REQUEST['target_val']:"";


// Setup the graph

//$graph = new Graph(300,200);
$graph = new Graph($width,$height);
$graph->SetMarginColor('white');
$graph->SetScale("textlin");
$graph->SetFrame(false);
//$graph->SetMargin(30,50,30,30);
$graph->SetMargin($left,$right,$top,$bottom);

$graph->tabtitle->Set($title );
//$graph->tabtitle->SetFont(FF_ARIAL,FS_BOLD,13);
$graph->tabtitle->SetFont(FF_FONT2,FS_BOLD,13);


$graph->yaxis->HideZeroLabel();
$graph->ygrid->SetFill(true,'#EFEFEF@0.5','#BBCCFF@0.5');
$graph->xgrid->Show();

//$graph->xaxis->SetTickLabels($gDateLocale->GetShortMonth());

// Create the lines of the Graph
for($i=0;$i<count($datavalue);$i++)
{
	$data=$datavalue[$i];
	$graph_data=explode(",",$data);
	$status_name=$status_value[$i];
		
	$color_val=$color_array[$i];
	$temp="p".$i;
	system("echo 'color value $tmp' >> /tmp/rama.tmp ");
	$$temp = new LinePlot($graph_data);
	$$temp->SetColor($color_val);
	$$temp->SetLegend($status_name);
	$graph->Add($$temp);
	
}

//$graph->legend->SetShadow('gray@0.4',5);
//$graph->legend->SetPos(0.1,0.1,'right','top');
//$graph->legend->SetPos(0.1,0.1,'right','center');
$graph->legend->Pos(0,0.4,"right","center");


// Output line
$graph->Stroke();



?>
