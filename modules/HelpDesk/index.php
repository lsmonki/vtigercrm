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
require_once('modules/HelpDesk/HelpDeskUtil.php');


//Retreive the list from Database
$tktresult = getTicketList();

//Retreiving the no of rows
$noofrows = mysql_num_rows($tktresult);


echo $list_max_entries_per_page;
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
echo '<BR>';
echo 'end value is '.$endval;
echo '         ';
echo 'remainder is '.$tempendval;


echo '<BR>';
echo 'start to endvalue counter is '.$starttoendvaluecounter;
//Setting the next and previous value
if(isset($_REQUEST['start']) && $_REQUEST['start'] != '')
{
	$tempnextstartvalue = $_REQUEST['start'] + $list_max_entries_per_page;
	if($tempnextstartvalue <= $noofrows)
	{
		
		$nextstartvalue = $tempnextstartvalue;
	}
	$tempprevvalue = $_REQUEST['start'] - $list_max_entries_per_page;
	if($tempnextstartvalue  > 1)
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
echo '<BR>';
echo 'next startvalue counter is '.$nextstartvalue;
echo '<BR>';
echo 'next previousvalue counter is '.$prevstartvalue;

global $app_strings;
global $mod_strings;

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$xtpl=new XTemplate ('modules/HelpDesk/TicketsList.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
echo get_module_title("HelpDesk", $mod_strings['LBL_MODULE_NAME'].": Home" , true);
$tkList = '';
for ($i=$start; $i<=$end; $i++)
{
	$tkList .= '<tr  height=20>';
	$subject = '<a href="index.php?action=TicketInfoView&module=HelpDesk&record='.mysql_result($tktresult,$i,"id").'">'.mysql_result($tktresult,$i,"title").'</a>';
       $tkList .= '<td width="15%">'.$subject.'</td>';
	$contact_name = '<a href="index.php?action=DetailView&module=Contacts&record='.mysql_result($tktresult,$i,"contact_id").'">'.mysql_result($tktresult,$i,"first_name").' '.mysql_result($tktresult,$i,"last_name").'</a>';  
        $tkList .= '<td width="15%">'.$contact_name.'</td>';
        $tkList .= '<td width="15%">'.mysql_result($tktresult,$i,"status").'</td>';
        $tkList .= '<td width="15%">'.mysql_result($tktresult,$i,"groupname").'</td>';
        $tkList .= '<td width="15%">'.mysql_result($tktresult,$i,"user_name").'</td>';
	$tkList .= '</tr>';

}
$xtpl->assign("TICKETLIST", $tkList);
if(isset($startvalue))
{
	$startoutput = '<a href="index.php?action=index&module=HelpDesk&start='.$startvalue.'">start</a>';
}
else
{
	$startoutput = 'start';
}
if(isset($endval))
{
	$endoutput = '<a href="index.php?action=index&module=HelpDesk&start='.$endval.'">end</a>';
}
else
{
	$endoutput = 'end';
}
if(isset($nextstartvalue))
{
	$nextoutput = '<a href="index.php?action=index&module=HelpDesk&start='.$nextstartvalue.'">next</a>';
}
else
{
	$nextoutput = 'next';
}
if(isset($prevstartvalue))
{
	$prevoutput = '<a href="index.php?action=index&module=HelpDesk&start='.$prevstartvalue.'">prev</a>';
}
else
{
	$prevoutput = 'next';
}
$xtpl->assign("Start", $startoutput);
$xtpl->assign("End", $endoutput);
$xtpl->assign("Next", $nextoutput);
$xtpl->assign("Prev", $prevoutput);

$xtpl->parse("main");

$xtpl->out("main");

?>
