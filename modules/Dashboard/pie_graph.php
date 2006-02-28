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


include ("jpgraph/src/jpgraph_pie.php");
include ("jpgraph/src/jpgraph_pie3d.php");


/** Function to render the Horizontal Graph
        * Portions created by vtiger are Copyright (C) vtiger.
        * All Rights Reserved.
        * Contributor(s): ______________________________________..
 */
function pie_chart($referdata,$refer_code,$width,$height,$left,$right,$top,$bottom,$title,$target_val,$cache_file_name,$html_image_name)
{
	

	global $log,$root_directory;
	//We'll be getting the values in the form of a string separated by commas
	$datay=explode(",",$referdata); // The datay values
	$datax=explode(",",$refer_code); // The datax values

	$target_val=urldecode($target_val);
	$target=explode(",",$target_val);

	$alts=array();
	for($i=0;$i<count($datax); $i++)
	{
		$name=$datax[$i];
                $pos = substr_count($name," ");
                $alts[]=$name."=%d";
	}

	$graph = new PieGraph($width,$height,"auto");


	$graph->SetShadow();

	$graph->title->Set($title);
	$graph->title->SetFont(FF_FONT1,FS_BOLD);

	$p1 = new PiePlot3D($datay);
	$p1->SetTheme("sand");
	$p1->ExplodeSlice(1);
	$p1->SetCenter(0.45);
	//$p1->SetLegends($gDateLocale->GetShortMonth());
	$p1->SetLegends($datax);
	//$p1->ShowBorder(false);


	// Setup the labels
	$p1->SetLabelType(PIE_VALUE_PER);    
	$p1->value->Show();            
	//$p1->value->SetFont(FF_ARIAL,FS_NORMAL,9);    
	//$p1->value->SetFormat('%2.1f%%');        
	$p1->value->SetFormat('%2.1f%%');        
	//$p1->value->SetFormat("$datax\n$datay('%2.1f%')");


	$p1->SetCSIMTargets($target,$alts);
	// Don't display the border
	$graph->SetFrame(false);

	$graph->Add($p1);

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

