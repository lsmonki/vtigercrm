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


require_once('include/utils/GraphUtils.php');
include_once ('Image/Graph.php');
include_once ('Image/Canvas.php');


/** Function to render the Horizontal Graph
  * Portions created by vtiger are Copyright (C) vtiger.
  * All Rights Reserved.
  * Contributor(s): ______________________________________..
  */
function pie_chart($referdata,$refer_code,$width,$height,$left,$right,$top,$bottom,$title,$target_val,$cache_file_name,$html_image_name)
{


	global $log,$root_directory,$lang_crm,$theme;
	//We'll be getting the values in the form of a string separated by commas
	$datay=explode("::",$referdata); // The datay values
	$datax=explode("::",$refer_code); // The datax values

	$target_val=urldecode($target_val);
	$target=explode("::",$target_val);

	$alts=array();
	for($i=0;$i<count($datax); $i++)
	{
		$name=$datax[$i];
		$pos = substr_count($name," ");
		$alts[]=$name."=%d";
	}

	if($theme == "blue")
	{
		$font_color = "#212473";
	}
	else
	{
		$font_color = "#000000";
	}
	
	$canvas =& Image_Canvas::factory('png', array('width' => $width, 'height' => $height, 'usemap' => true));
	$imagemap = $canvas->getImageMap();
	$graph =& Image_Graph::factory('graph', $canvas);
	$font =& $graph->addNew('font', calculate_font_name('en_en'));
	// set the font size to 11 pixels
	$font->setSize(8);
	$font->setColor($font_color);
		
	$graph->setFont($font);
	// create the plotarea layout
    $title =& Image_Graph::factory('title', array($title,10));
   	$plotarea =& Image_Graph::factory('plotarea',array(
                  'category',
                  'axis'
              ));
   	$footer =& Image_Graph::factory('title', array('Footer',8));
   	$legend_box =& Image_Graph::factory('legend');
	$graph->add(
		    Image_Graph::vertical($title,
			$plotarea,
        	5
	    	)
	);   

	// Generate colours
	$colors = color_generator(count($datay),'#33DDFF','#3322FF');
	$dataset = & Image_Graph::factory('dataset');
	$fills =& Image_Graph::factory('Image_Graph_Fill_Array');
	$sum = 0;
	for($i=0;$i<count($datay); $i++)
	{
		$dataset->addPoint(
			        $datax[$i],
			        $datay[$i],
			        array(
			            'url' => $target[$i],
			            'alt' => $alts[$i]
			        )
	    );
	    $sum += $datay[$i];
		$fills->addColor($colors[$i]);
	}

	// create an array with % values
	$pcvalues = array();
	for($i=0;$i<count($datay); $i++)
	{
		$pcvalues[$datay[$i]] = sprintf('%0.1f%%',100*$datay[$i]/$sum);
	}

	// create the pie chart and associate the filling colours			
	$gbplot = & $plotarea->addNew('pie', $dataset);
	$plotarea->hideAxis();
	$gbplot->setFillStyle($fills);

	// format the data values
	$marker_array =& Image_Graph::factory('Image_Graph_DataPreprocessor_Array', array($pcvalues));
			
	// set markers
	$marker =& $graph->addNew('value_marker', IMAGE_GRAPH_VALUE_Y);
	$marker->setDataPreprocessor($marker_array);
	$marker->setFillColor('#FFFFFF');
	$marker->setBorderColor($font_color);
	$marker->setFontColor($font_color);
	$marker->setFontSize(8);
	$pointingMarker =& $graph->addNew('Image_Graph_Marker_Pointing_Angular', array(20, &$marker));
	$gbplot->setMarker($pointingMarker);
			
	// set legend
	$legend_box =& $plotarea->addNew('legend');
	$legend_box->setPadding(array('top'=>20,'bottom'=>0,'left'=>0,'right'=>0));
	$legend_box->setFillColor('#F5F5F5');
	$legend_box->showShadow();

	$img = $graph->done(
						    array(
							        'tohtml' => true,
							        'border' => 0,
							        'filename' => $cache_file_name,
							        'filepath' => '',
							        'urlpath' => ''
							    ));
	save_image_map($cache_file_name.'.map', $img);

	return $img;

}
?>

