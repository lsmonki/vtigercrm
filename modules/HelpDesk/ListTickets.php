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

require_once($theme_path.'layout_utils.php');

global $theme;
global $current_language;
$current_module_strings = return_module_language($current_language, 'HelpDesk');
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

/*
//Setting the start value
//Setting the start to end counter
$start=1;
$starttoendvaluecounter = $list_max_entries_per_page - 1;
echo 'start to end counter : '.$starttoendvaluecounter;
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
*/

//$search_query="select troubletickets.ticketid,contact_id,priority,troubletickets.status,category,troubletickets.title,troubletickets.description,update_log,version_id,crmentity.createdtime,crmentity.modifiedtime, contactdetails.firstname,contactdetails.lastname,users.user_name from troubletickets inner join users on users.id=crmentity.smownerid left join contactdetails on troubletickets.contact_id=contactdetails.contactid left join seticketsrel on seticketsrel.ticketid=troubletickets.ticketid inner join crmentity on crmentity.crmid=troubletickets.ticketid and crmentity.smownerid=".$current_user->id." and crmentity.deleted=0";
$search_query="select troubletickets.ticketid,parent_id,priority,troubletickets.status,category,troubletickets.title,troubletickets.description,update_log,version_id,crmentity.createdtime,crmentity.modifiedtime, contactdetails.firstname,contactdetails.lastname, account.accountid, account.accountname, users.user_name from troubletickets inner join crmentity on crmentity.crmid= troubletickets.ticketid inner join users on users.id=crmentity.smownerid left join contactdetails on troubletickets.parent_id=contactdetails.contactid left join account on account.accountid=troubletickets.parent_id left join seticketsrel on seticketsrel.ticketid=troubletickets.ticketid where crmentity.smownerid=".$current_user->id." and crmentity.deleted=0 and troubletickets.status <> 'Closed'  ORDER BY createdtime DESC";

$resultcount = $adb->num_rows($adb->query($search_query));
$tktresult = $adb->limitquery($search_query,0,5);
//$ticketListheader = get_form_header($current_module_strings['LBL_MY_TICKETS'], "", false );
echo "<br>";

$list .='<table border=0 cellspacing=0 cellpadding=0 width=100%><tr style="cursor:pointer;" unslectable="on" onclick="javascript:expandCont(\'home_mytkt\');"><td nowrap><img src="'.$image_path.'myTickets.gif" style="padding:5px"></td><td width=100%><b>'.$current_module_strings['LBL_MY_TICKETS'].'</b> </td><td nowrap><img src="themes/images/toggle2.gif" id="img_home_mytkt" border=0></td></tr>';
$list .= '<tr><td colspan=3 bgcolor="#000000" style="height:1px;"></td></tr>';
$list .= '<tr><td colspan=3>';
$list .= '<div id="home_mytkt" style="display:block;">';
$list.='<table width=100% cellpadding="0" cellspacing="0" border="0">';

//$list.='<!-- BEGIN: list_nav_row -->';
//$list.='<tr height="20"> <td COLSPAN="15" class="listFormHeaderLinks">';
//$list.='<table border="0" cellpadding="0" cellspacing="0" width="100%">';
//$list.='<tr> <td align="right">[ Start ]&nbsp;&nbsp;[ Prev ]&nbsp;&nbsp;[ Next ]&nbsp;&nbsp;[ End ]&nbsp;&nbsp;</td>';
//$list.='</tr> </table></td></tr>';
//$list.='<!-- END: list_nav_row -->';

$list.='<tr height=20> ';
$list.='<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="'.$image_path.'blank.gif"></td>';
$list.='<td class="moduleListTitle" height="21" style="padding:0px 3px 0px 3px;">'.$current_module_strings['LBL_TICKET_ID'].'</td>';
$list.='<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="'.$image_path.'blank.gif"></td>';
$list.='<td class="moduleListTitle" height="21" style="padding:0px 3px 0px 3px;">'.$current_module_strings['LBL_SUBJECT'].'</td>';
$list.='<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="'.$image_path.'blank.gif"></td>';
$list.='<td class="moduleListTitle" height="21" style="padding:0px 3px 0px 3px;">'.$current_module_strings['Related To'].'</td>';
$list.='<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="'.$image_path.'blank.gif"></td>';
$list.='<td class="moduleListTitle" height="21" style="padding:0px 3px 0px 3px;">'.$current_module_strings['LBL_STATUS'].'</td>';
$list.='<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="'.$image_path.'blank.gif"></td>';
$list.='<td class="moduleListTitle" height="21" style="padding:0px 3px 0px 3px;">'.$current_module_strings['LBL_CREATED_DATE'].'</td>';
$list.='<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="'.$image_path.'blank.gif"></td>';
$list.='<td class="moduleListTitle" height="21" style="padding:0px 3px 0px 3px;">'.$current_module_strings['LBL_ASSIGNED_TO'].'</td>';
$list.='<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="'.$image_path.'blank.gif"></td>';
$list.='<tr>';

for ($i=0; $i<$adb->num_rows($tktresult); $i++)
{
        if (($i%2)==0)
                $list .= '<tr height=20 class=evenListRow>';
        else
                $list .= '<tr height=20 class=oddListRow>';

                $list .= '<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="'.$image_path.'blank.gif"></td>';
        $ticketid = $adb->query_result($tktresult,$i,"ticketid");
       $list .= '<td style="padding:0px 3px 0px 3px;">'.$ticketid.'</td>';

                $list .= '<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="'.$image_path.'blank.gif"></td>';
        $subject = '<a href="index.php?action=DetailView&module=HelpDesk&record='.$adb->query_result($tktresult,$i,"ticketid").'">'.$adb->query_result($tktresult,$i,"title").'</a>';
       $list .= '<td style="padding:0px 3px 0px 3px;">'.$subject.'</td>';
                $list .= '<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="'.$image_path.'blank.gif"></td>';

	$parent_id = $adb->query_result($tktresult,$i,"parent_id");
	$parent_name = '';
	if($parent_id != '' && $parent_id != NULL)
	{
		$parent_name = getParentLink($parent_id);
	}

        $list .= '<td style="padding:0px 3px 0px 3px;">'.$parent_name.'</td>';
                $list .= '<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="'.$image_path.'blank.gif"></td>';
        $list .= '<td style="padding:0px 3px 0px 3px;">'.$adb->query_result($tktresult,$i,"status").'</td>';
                $list .= '<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="'.$image_path.'blank.gif"></td>';
        $list .= '<td style="padding:0px 3px 0px 3px;">'.getDisplayDate($adb->query_result($tktresult,$i,"createdtime")).'</td>';
                $list .= '<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="'.$image_path.'blank.gif"></td>';
        $list .= '<td style="padding:0px 3px 0px 3px;">'.$adb->query_result($tktresult,$i,"user_name").'</td>';
                $list .= '<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="'.$image_path.'blank.gif"></td>';
        $list .= '</tr>';
}

if($resultcount > 5)
        $list .= '</td></tr><tr><td colspan="11">&nbsp;</td><td align="right"><a href="index.php?action=index&module=HelpDesk&query=true&my_open_tickets=true">'.$current_module_strings['LBL_MORE'].'...&nbsp;&nbsp;</a></td></tr>';

$list.='<tr><td COLSPAN="15" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td></tr></table>';
$list .= '</div></td></tr></table>';
$list .= '<script language=\'Javascript\'>
        var leftpanelistarray=new Array(\'home_mytkt\');
  setExpandCollapse_gen()</script>';
echo $list;

function getParentLink($parent_id)
{
	global $adb;

	$sql = "select setype from crmentity where crmid=".$parent_id;
	$parent_module = $adb->query_result($adb->query($sql),0,'setype');

	if($parent_module == 'Contacts')
	{
		$sql = "select firstname,lastname from contactdetails where contactid=".$parent_id;
		$parentname = $adb->query_result($adb->query($sql),0,'firstname');
		$parentname .= ' '.$adb->query_result($adb->query($sql),0,'lastname');
	        $parent_name = '<a href="index.php?action=DetailView&module='.$parent_module.'&record='.$parent_id.'">'.$parentname.'</a>';
	}
	if($parent_module == 'Accounts')
	{
		$sql = "select accountname from account where accountid=".$parent_id;
		$parentname = $adb->query_result($adb->query($sql),0,'accountname');
	        $parent_name = '<a href="index.php?action=DetailView&module='.$parent_module.'&record='.$parent_id.'">'.$parentname.'</a>';
	}

	return $parent_name;
}

?>
