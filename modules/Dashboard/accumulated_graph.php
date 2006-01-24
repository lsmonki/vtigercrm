<?php

include ("../../jpgraph/src/jpgraph.php");
include ("../../jpgraph/src/jpgraph_bar.php");

$refer_code=(isset($_REQUEST['width']))?$_REQUEST['refer_code']:"0,0";
$datax=explode(",",$refer_code); //The values to the XAxis

$names_value=(isset($_REQUEST['referdata']))?$_REQUEST['referdata']:"null"; //The Status Name
$name_value=explode(",",$names_value);

//Giving the colors to the Line graph
$color_array=array("red","blue","orange","green","darkorchid","gold1","gray3","lightpink","burlywood2","cadetblue");
$datavalue=(isset($_REQUEST['datavalue']))?$_REQUEST['datavalue']:"0,K,0";

//Exploding the data values
$datavalue=explode("K",$datavalue);


$width=(isset($_REQUEST['width']))?$_REQUEST['width']:410;
$height=(isset($_REQUEST['height']))?$_REQUEST['height']:270;
$left=(isset($_REQUEST['left']))?$_REQUEST['left']:110;
$right=(isset($_REQUEST['right']))?$_REQUEST['right']:20;
$top=(isset($_REQUEST['top']))?$_REQUEST['top']:40;
$bottom=(isset($_REQUEST['bottom']))?$_REQUEST['bottom']:50;
$title=(isset($_REQUEST['title']))?$_REQUEST['title']:"Horizontal graph";
$target_val=(isset($_REQUEST['target_val']))?$_REQUEST['target_val']:"";


setlocale (LC_ALL, 'et_EE.ISO-8859-1');


// Create the graph. These two calls are always required
$graph = new Graph($width,$height,"auto");    
$graph->SetScale("textlin");

$graph->SetShadow();
//$graph->img->SetMargin(40,30,20,40);



// Create the lines of the Graph
for($i=0;$i<count($datavalue);$i++)
{
        $data=$datavalue[$i];
        $graph_data=explode(",",$data);
        $name=$name_value[$i];

        $color_val=$color_array[$i];
        $temp="bplot".$i;
//	system("echo 'temppppppppppppp   -- >>>  $yes----------------- ' >> /tmp/rama.tmp ");
        $$temp = new BarPlot($graph_data);
        $$temp->SetFillColor($color_val);
        $$temp->SetColor($color_val);
	
	$graph->Add($$temp);
}


// Create the grouped bar plot
//$gbplot = new AccBarPlot(array($b1plot,$b2plot));
//$gbplot = new AccBarPlot(array($test));

// ...and add it to the graPH
//$graph->Add($gbplot);


$graph->xaxis->SetTickLabels($datax);
//$graph->xaxis->SetFont(FF_FONT1,FS_NORMAL,8);
//$graph->xaxis->SetLabelAngle(45);

$graph->title->Set("Accumulated bar plots");
//$graph->xaxis->title->Set("X-title");
//$graph->yaxis->title->Set("Y-title");
	
$graph->Set90AndMargin($left,$right,$top,$bottom);
$graph->SetFrame(false);
$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);

// Display the graph
$graph->Stroke();
?>
