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
include ("../../jpgraph/src/jpgraph_pie.php");
include ("../../jpgraph/src/jpgraph_pie3d.php");


$referdata=(isset($HTTP_GET_VARS['referdata']))?$HTTP_GET_VARS['referdata']:"0,0";
$refer_code=(isset($HTTP_GET_VARS['refer_code']))?$HTTP_GET_VARS['refer_code']:"0,0";


$title=(isset($HTTP_GET_VARS['title']))?$HTTP_GET_VARS['title']:"Horizontal graph";
$target_val=(isset($HTTP_GET_VARS['target_val']))?$HTTP_GET_VARS['target_val']:"";
$width=($HTTP_GET_VARS['width'])?$HTTP_GET_VARS['width']:410;
$height=($HTTP_GET_VARS['height'])?$HTTP_GET_VARS['height']:270;
$title=(isset($HTTP_GET_VARS['title']))?$HTTP_GET_VARS['title']:"Pie graph";


$datay=explode(",",$referdata);
$datax=explode(",",$refer_code);

$data = array(40,60,21,33);

$graph = new PieGraph($width,$height,"auto");


$graph->SetShadow();

$graph->title->Set($title);
$graph->title->SetFont(FF_FONT1,FS_BOLD);

$p1 = new PiePlot3D($datay);
$p1->ExplodeSlice(1);
$p1->SetCenter(0.45);
//$p1->SetLegends($gDateLocale->GetShortMonth());
$p1->SetLegends($datax);

$graph->Add($p1);
$graph->Stroke();

?>

