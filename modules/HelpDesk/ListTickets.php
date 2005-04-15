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
$search_query="select troubletickets.ticketid,contact_id,priority,troubletickets.status,category,troubletickets.title,troubletickets.description,update_log,version_id,crmentity.createdtime,crmentity.modifiedtime, contactdetails.firstname,contactdetails.lastname,users.user_name from troubletickets inner join crmentity on crmentity.crmid= troubletickets.ticketid inner join users on users.id=crmentity.smownerid left join contactdetails on troubletickets.contact_id=contactdetails.contactid left join seticketsrel on seticketsrel.ticketid=troubletickets.ticketid where crmentity.smownerid=".$current_user->id." and crmentity.deleted=0 and troubletickets.status <> 'Closed'  ORDER BY createdtime DESC";

$tktresult = $adb->limitquery($search_query,0,5);
$ticketListheader = get_form_header($current_module_strings['LBL_MY_TICKETS'], "", false );
echo "<br>";

$list.=$ticketListheader;
$list.='<table width=100% cellpadding="0" cellspacing="0" border="0" class="formBorder">';

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
$list.='<td class="moduleListTitle" height="21" style="padding:0px 3px 0px 3px;">'.$current_module_strings['LBL_CONTACT'].'</td>';
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
        $contact_name = '<a href="index.php?action=DetailView&module=Contacts&record='.$adb->query_result($tktresult,$i,"contact_id").'">'.$adb->query_result($tktresult,$i,"firstname").' '.$adb->query_result($tktresult,$i,"lastname").'</a>';
        $list .= '<td style="padding:0px 3px 0px 3px;">'.$contact_name.'</td>';
                $list .= '<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="'.$image_path.'blank.gif"></td>';
        $list .= '<td style="padding:0px 3px 0px 3px;">'.$adb->query_result($tktresult,$i,"status").'</td>';
                $list .= '<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="'.$image_path.'blank.gif"></td>';
        $list .= '<td style="padding:0px 3px 0px 3px;">'.$adb->query_result($tktresult,$i,"createdtime").'</td>';
                $list .= '<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="'.$image_path.'blank.gif"></td>';
        $list .= '<td style="padding:0px 3px 0px 3px;">'.$adb->query_result($tktresult,$i,"user_name").'</td>';
                $list .= '<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="'.$image_path.'blank.gif"></td>';
        $list .= '</tr>';
}
$list.='</td></tr></table>';
echo $list;

?>
