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


global $result;
global $mod_strings;
$list = '';
$closedlist = '';

$list_fields = Array(   'TICKETID'	=> '7%',
       	                'TITLE' 	=> '28%',
               	        'PRIORITY'	=> '10%',
                       	'STATUS'	=> '10%',
                        'CATEGORY'	=> '15%',
       	                'MODIFIED TIME'	=> '15%',
               	        'CREATED TIME'	=> '15%'
                     );

$val = @array_values($result);
$rowcount = count($result);


$showstatus = $_REQUEST['showstatus'];

if($showstatus != '' && $rowcount >= 1 && $val)
{
	$list .= '<tr><td colspan="2"><div id="scrollTab">';
	$list .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">';
	$list .= '<tr><td class="mnu">'.$showstatus.' Tickets</td></tr></table>';
	$list .= '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
	$list .= '<tr>';

	foreach($list_fields as $val => $per)
	{
		$list .= '<td class="detailedViewHeader" width="'.$per.'" align="center">'.$mod_strings[$val].'</td>';
	}
	$list .= '</tr>';

	$ticketexist = 0;
	for($i=0;$i<count($result);$i++)
	{
		if($result[$i]['status'] == $showstatus)
		{
			$ticketexist = 1;
			$ticketlist = '';

			if ($i%2==0)
				$ticketlist .= '<tr class="dvtLabel">';
			else
				$ticketlist .= '<tr class="dvtInfo">';

			$ticketlist .= '<td>'.$result[$i]['ticketid'].'</td>';
			$ticketlist .= '<td><a href="index.php?module=Tickets&action=index&ticketid='.$result[$i]['ticketid'].'&fun=detail">'.$result[$i]['title'].'</a></td>';
			$ticketlist .= '<td>'.$result[$i]['priority'].'</td>';
			$ticketlist .= '<td>'.$result[$i]['status'].'</td>';
			$ticketlist .= '<td>'.$result[$i]['category'].'</td>';
			$ticketlist .= '<td>'.$result[$i]['modifiedtime'].'</td>';
			$ticketlist .= '<td>'.$result[$i]['createdtime'].'</td></tr>';

			$list .= $ticketlist;
		}
	}	

	if($ticketexist == 0)
	{
		$list .= '<tr><td>&nbsp;</td></tr><tr><td colspan="7" class="pageTitle">No Tickets available in this status.</td></tr></table>';
	}

	$list .= '</table>';

}
elseif($rowcount >= 1 && $val)
{
	$list .= '<tr><td colspan="2"><div id="scrollTab">';
	$list .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">';
	$list .= '<tr><td class="mnu">'.$mod_strings['LBL_MY_OPEN_TICKETS'].'</td></tr></table>';
	$list .= '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
	$list .= '<tr>';

	$closedlist .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">';
	$closedlist .= '<tr><td class="mnu">'.$mod_strings['LBL_CLOSED_TICKETS'].'</td></tr></table>';
	$closedlist .= '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
	$closedlist .= '<tr>';
	foreach($list_fields as $val => $per)
	{
		$list .= '<td class="detailedViewHeader" width="'.$per.'" align="center">'.$mod_strings[$val].'</td>';
		$closedlist .= '<td class="detailedViewHeader" width="'.$per.'" align="center">'.$mod_strings[$val].'</td>';
	}
	$list .= '</tr>';

	for($i=0;$i<count($result);$i++)
	{
		$ticketlist = '';

		if ($i%2==0)
			$ticketlist .= '<tr class="dvtLabel">';
		else
			$ticketlist .= '<tr class="dvtInfo">';

		$ticketlist .= '<td>'.$result[$i]['ticketid'].'</td>';
		//$ticketlist .= '<td><a href="general.php?action=UserTickets&ticketid='.$result[$i]['ticketid'].'&fun=detail&username='.$_REQUEST['username'].'">'.$result[$i]['title'].'</a></td>';
		$ticketlist .= '<td><a href="index.php?module=Tickets&action=index&ticketid='.$result[$i]['ticketid'].'&fun=detail">'.$result[$i]['title'].'</a></td>';
		$ticketlist .= '<td>'.$result[$i]['priority'].'</td>';
		$ticketlist .= '<td>'.$result[$i]['status'].'</td>';
		$ticketlist .= '<td>'.$result[$i]['category'].'</td>';
		$ticketlist .= '<td>'.$result[$i]['modifiedtime'].'</td>';
		$ticketlist .= '<td>'.$result[$i]['createdtime'].'</td></tr>';

		if($result[$i]['status'] == $mod_strings['LBL_STATUS_CLOSED'])
			$closedlist .= $ticketlist;
		elseif($result[$i]['status'] != '')
			$list .= $ticketlist;
	}	

	$list .= '</table>';
	$closedlist .= '</table>';

	$closedlist .= '</div></td></tr>';

	$list .= '<br><br>'.$closedlist;
}
else
{
	$list = '<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">';
	$list .= '<tr><td class="pageTitle">'.$mod_strings['LBL_NONE_SUBMITTED'].'</td></tr></table>';
}

echo $list;








?>
