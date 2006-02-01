<?php

include ("../../jpgraph/src/jpgraph.php");
include ("../../jpgraph/src/jpgraph_bar.php");

$refer_code=(isset($_REQUEST['width']))?$_REQUEST['refer_code']:"0,0";
$referdata=(isset($_REQUEST['referdata']))?$_REQUEST['referdata']:"null"; //The Status Name
$datavalue=(isset($_REQUEST['datavalue']))?$_REQUEST['datavalue']:"0,K,0";
$width=(isset($_REQUEST['width']))?$_REQUEST['width']:410;
$height=(isset($_REQUEST['height']))?$_REQUEST['height']:270;
$left=(isset($_REQUEST['left']))?$_REQUEST['left']:110;
$right=(isset($_REQUEST['right']))?$_REQUEST['right']:20;
$top=(isset($_REQUEST['top']))?$_REQUEST['top']:40;
$bottom=(isset($_REQUEST['bottom']))?$_REQUEST['bottom']:50;
$title=(isset($_REQUEST['title']))?$_REQUEST['title']:"Horizontal graph";
$target_val=(isset($_REQUEST['target_val']))?$_REQUEST['target_val']:"";

/*
function accumlated_graph($refer_code,$referdata,$datavalue,$title,$target_val,$width,$height,$left,$right,$top,$bottom)
{
*/
	//Exploding the data values
	$datavalue=explode("K",$datavalue);
	$name_value=explode(",",$referdata);
	$datax=explode(",",$refer_code); //The values to the XAxis
	$color_array=array("#FF8B8B","#8BFF8B","#A8A8FF","#FFFF6E","#C5FFFF","#FFA8FF","#FFE28B","lightpink","burlywood2","cadetblue");

	// Create the graph. These two calls are always required
	$graph = new Graph($width,$height,"auto");    
	$graph->SetScale("textlin");

	$graph->SetShadow();


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

		$$temp->SetWidth(1.0);
		$$temp->value->Show();
		$$temp->value->SetFormat('%d');
		$$temp->SetValuePos('top');

		$$temp->value->SetColor('black');

		$graph->Add($$temp);
	}

	$graph->xaxis->SetTickLabels($datax);
	//$graph->xaxis->SetFont(FF_FONT1,FS_NORMAL,8);
	//$graph->xaxis->SetLabelAngle(45);

	$graph->title->Set($title);

	$graph->Set90AndMargin($left,$right,$top,$bottom);
	//$graph->SetFrame(false);
	$graph->title->SetFont(FF_FONT1,FS_BOLD);
	$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
	$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);

	$graph->SetColor("#7D9CB8");
	$graph->SetMarginColor("#3D6A93");

	// Display the graph
	$graph->Stroke();
//}

?>

