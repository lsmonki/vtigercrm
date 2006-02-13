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

require_once('database/DatabaseConnection.php');

//or die("Couldn't connect to database $dbDatabase");

function getLoghistory($theme)
{



	$dbQuery = "SELECT * from loginhistory order by login_id DESC";

	//echo $dbQuery;

	$result = mysql_query($dbQuery) or die("Couldn't get file list");

$list = '<table border="0" cellpadding="0" cellspacing="0" class="FormBorder" width="100%">';

$list .= '<tr><td COLSPAN="11" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td></tr>';
$list .= '<tr class="ModuleListTitle" height=20>';

$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td>';
$list .= '<td class="moduleListTitle" height="21">';

$list .= '<p style="margin-left: 10">';

$list .= 'User Name</td>';
$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td>';
$list .= '<td width="15%" class="moduleListTitle">';

$list .= '<p style="margin-left: 10">';

$list .= 'User IP</td>';
$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td>';
$list .= '<td width="15%" class="moduleListTitle">';

$list .= '<p style="margin-left: 10">';


$list .= 'Signin TimeType</td>';
$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td>';
$list .= '<td class="moduleListTitle" >';

$list .= '<p style="margin-left: 10">';

$list .= 'Signout TimeFile</td>';


$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td>';
$list .= '<td class="moduleListTitle" >';

$list .= '<p style="margin-left: 10">';

$list .= 'Status</td>';


$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td>';
$list .= '<tr><td COLSPAN="11" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td></tr>';

$i=1;
while($row = mysql_fetch_array($result))
{


if ($i%2==0)
$trowclass = 'evenListRow';
else
$trowclass = 'oddListRow';
	$list .= '<tr class="'. $trowclass.'">';
	
	$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td>';
	$list .='<td height="21" style="padding:0px 3px 0px 3px;"><p style="margin-left: 10; margin-right: 10">';

	 $list .= $row["user_name"]; 

	$list .= '</td>';

	$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td>';

	$list .= '<td height="21" style="padding:0px 3px 0px 3px;">	<p style="margin-left: 10">';

	 $list .= $row["user_ip"]; 

	$list .= '</td>';

	$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td>';

	$list .= '<td height="21" style="padding:0px 3px 0px 3px;">	<p style="margin-left: 10">';

	 $list .= $row["login_time"]; 

	$list .= '</td>';

	$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td>';

	$list .= '<td height="21" style="padding:0px 3px 0px 3px;"><p style="margin-left: 10">';

	$list .= $row["logout_time"];

	$list .= '</td>';
	
	$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td>';

	$list .= '<td height="21" style="padding:0px 3px 0px 3px;"><p style="margin-left: 10">';

	$list .= $row["status"];

	$list .= '</td>';
	$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td>';

	$list .= '</tr>';
	$list .= '<tr><td COLSPAN="11" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td></tr>';
$i++;
}
	$list .= '</table>';

return $list;
}
?>
