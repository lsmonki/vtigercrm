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

// TTF Font names
DEFINE("FF_COURIER",'Courier New');
DEFINE("FF_VERDANA",'Verdana');
DEFINE("FF_TIMES",'Times New Roman');
DEFINE("FF_COMIC",'Comic');
DEFINE("FF_ARIAL",'Arial');
DEFINE("FF_GEORGIA",'Georgia');
DEFINE("FF_TREBUCHE",'Trebuc');

// Chinese font
DEFINE("FF_SIMSUN",'Simsun');
DEFINE("FF_CHINESE",'Chinese');
DEFINE("FF_BIG5",'Big5');

DEFINE("FF_FONT1",'Verdana');

/**This function is used to get the font name when a language code is given
* Param $locale - language code
* Return type string
*/
function calculate_font_name($locale)

{

	switch($locale)
	{
		case 'cn_zh':
			return FF_SIMSUN;
		case 'tw_zh':
			if(!function_exists('iconv')){
				echo " Unable to display traditional Chinese on the graphs.<BR>The function iconv does not exists please read more about <a href='http://us4.php.net/iconv'>iconv here</a><BR>";
				return FF_FONT1;

			}
			else return FF_CHINESE;
		default:
			return FF_FONT1;
	}

	return FF_FONT1;
}

/**This function is used to generate the color format.
* Param $count - language code
* Return type string
*/

function color_generator($count = 1, $start = '33CCFF', $step = '221133')
{
	// explode color strings to RGB array
	if($start{0} == "#") $start = substr($start,1);
	if($step{0} == "#") $step = substr($step,1);
	// pad shorter strings with 0
	$start = substr($start."000000",0,6);
	$step = substr($step."000000",0,6);
	$colors = array(hexdec(substr($start,0,2)),hexdec(substr($start,2,2)),hexdec(substr($start,4,2)));
	$steps = array(hexdec(substr($step,0,2)),hexdec(substr($step,2,2)),hexdec(substr($step,4,2)));
	// buils $count colours adding $step to $start
	$result = array();
	for($i=1; $i<=$count; $i++){
		array_push($result,"#".dechex($colors[0]).dechex($colors[1]).dechex($colors[2]));
		for($j=0; $j<3; $j++) {
			$colors[$j] += $steps[$j];
			if($colors[$j] > 0xFF) $colors[$j] -= 0xFF;
		}
	}
	return $result;
}
?>
