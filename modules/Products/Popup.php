<?
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/
require_once('database/DatabaseConnection.php');
require_once('XTemplate/xtpl.php');
require_once('include/utils.php');


//Retreive the list from Database
$tktresult = mysql_query("select * from products where deleted =0");

//Retreiving the no of rows
$noofrows = mysql_num_rows($tktresult);

//Retreiving the start value from request
if(isset($_REQUEST['start']) && $_REQUEST['start'] != '')
{
	$start = $_REQUEST['start'];
}
else
{
	
	$start = 1;
}
//Setting the start value
//Setting the start to end counter
$starttoendvaluecounter = $list_max_entries_per_page - 1;
//Setting the ending value
if($noofrows > $list_max_entries_per_page)
{
	$end = $start + $starttoendvaluecounter;
	if($end > $noofrows)
	{
		$end = $noofrows;
	}
	$startvalue = 1;
	$remainder = $noofrows % $list_max_entries_per_page;
	if($remainder > 0)
	{
		$endval = $noofrows - $remainder + 1;
	}
	elseif($remainder == 0)
	{
		$endval = $noofrows - $starttoendvaluecounter;
	}
}
else
{
	$end = $noofrows;
}


//Setting the next and previous value
if(isset($_REQUEST['start']) && $_REQUEST['start'] != '')
{
	$tempnextstartvalue = $_REQUEST['start'] + $list_max_entries_per_page;
	if($tempnextstartvalue <= $noofrows)
	{
		
		$nextstartvalue = $tempnextstartvalue;
	}
	$tempprevvalue = $_REQUEST['start'] - $list_max_entries_per_page;
	if($tempprevvalue  > 0)
	{
		$prevstartvalue = $tempprevvalue;
	}
}
else
{
	if($noofrows > $list_max_entries_per_page)
	{
		$nextstartvalue = $list_max_entries_per_page + 1;
	}
}

global $app_strings;
global $mod_strings;

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$xtpl=new XTemplate ('modules/Products/ProductsPopupList.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("THEME_PATH", $theme_path);
echo get_module_title("Products", $mod_strings['LBL_MODULE_NAME']." List" , false);
$tkList = '';
for ($i=$start; $i<=$end; $i++)
{
	$tkList .= '<tr  height=20>';
	//$productname = '<a href="index.php?action=ProductDetailView&module=Products&record='.mysql_result($tktresult,$i-1,"id").'">'.mysql_result($tktresult,$i-1,"productname").'</a>';
	$productname = '<a href="a" LANGUAGE=javascript onclick=\'set_return("'.mysql_result($tktresult,$i-1,"id").'", "'.mysql_result($tktresult,$i-1,"productname").'"); window.close()\'>'.mysql_result($tktresult,$i-1,"productname").'</a>';
	$tkList .= '<td style="padding:0px 3px 0px 3px;">'.$productname.'</td>';
        $tkList .= '<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="'.$image_path.'blank.gif"></td>';

       	$tkList .= '</tr>';
	

}
$xtpl->assign("PRODUCTLIST", $tkList);
if(isset($startvalue))
{
	$startoutput = '<a href="index.php?action=index&module=Products&start='.$startvalue.'">start</a>';
}
else
{
	$startoutput = 'start';
}
if(isset($endval))
{
	$endoutput = '<a href="index.php?action=index&module=Products&start='.$endval.'">end</a>';
}
else
{
	$endoutput = 'end';
}
if(isset($nextstartvalue))
{
	$nextoutput = '<a href="index.php?action=index&module=Products&start='.$nextstartvalue.'">next</a>';
}
else
{
	$nextoutput = 'next';
}
if(isset($prevstartvalue))
{
	$prevoutput = '<a href="index.php?action=index&module=Products&start='.$prevstartvalue.'">prev</a>';
}
else
{
	$prevoutput = 'prev';
}
$xtpl->assign("Start", $startoutput);
$xtpl->assign("End", $endoutput);
$xtpl->assign("Next", $nextoutput);
$xtpl->assign("Prev", $prevoutput);

$xtpl->parse("main");

$xtpl->out("main");

?>
