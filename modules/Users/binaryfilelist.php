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

function getAttachmentsList()
{
	global $theme;
	global $app_strings;

	$dbQuery = "SELECT filename,filesize,filetype,description ";

	$dbQuery .= "FROM wordtemplatestorage" ;

	$dbQuery .= " ORDER BY filename ASC";

	//echo $dbQuery;

	$result = mysql_query($dbQuery) or die("Couldn't get file list");

$list = '<table border="0" cellpadding="0" cellspacing="0" class="FormBorder" width="90%">';

$list .= '<tr class="moduleListTitle" height=20>';
$list .='<td> '.$app_strings['LBL_OPERATION'].'</td>';
$list .= '';

$list .= '<td width="30%" class="moduleListTitle" height="21" style="padding:0px 3px 0px 3px;">';

$list .= $app_strings['LBL_FILENAME'].'</td>';
$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
$list .= '<td width="35%" class="moduleListTitle" style="padding:0px 3px 0px 3px;">';

$list .= $app_strings['LBL_UPD_DESC'].'</td>';
$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
$list .= '<td width="20%" class="moduleListTitle" style="padding:0px 3px 0px 3px;">';

$list .= $app_strings['LBL_TYPE'].'</td>';
$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
$list .= '<td width="15%" class="moduleListTitle" style="padding:0px 3px 0px 3px;">';
$list .= $app_strings['LBL_FILE'].'</td>';

$list .= '</tr>';

//$list .= '<tr><td COLSPAN="7" class="blackLine"><IMG SRC="themes/'.$theme.'/images//blank.gif"></td></tr>';

$i=1;
while($row = mysql_fetch_array($result))
{


if ($i%2==0)
$trowclass = 'evenListRow';
else
$trowclass = 'oddListRow';
	$list .= '<tr class="'. $trowclass.'"><td><a href="index.php?module=Users&action=deletewordtemplate&filename='.$row["filename"].'"> Del </a> </td><td height="21" style="padding:0px 3px 0px 3px;">';

	 $list .= $row["filename"]; 

	$list .= '</td>';
	
	$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';

	$list .= '<td height="21" style="padding:0px 3px 0px 3px;">';

	 $list .= $row["description"]; 

	$list .= '</td>';
	
	$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';

	$list .= '<td height="21" style="padding:0px 3px 0px 3px;">';

	 $list .= $row["filetype"]; 

	$list .= '</td>';

	$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';

	$list .= '<td height="21" style="padding:0px 3px 0px 3px;">';

	$list .= '<a href="index.php?module=Users&action=downloadfile&filename='.$row['filename'] .'">';

	$list .= $app_strings['LBL_DOWNLOAD'];

	$list .= '</a></td></tr>';
$i++;
}

	$list .= '</table>';

return $list;
}
?>
