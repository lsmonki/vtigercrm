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
function getAttachmentsList($id,$theme)
{

global $app_strings;


	$dbQuery = "SELECT fileid, filename,filesize,filetype,description ";

	$dbQuery .= "FROM filestorage where parent_id='".$id ."'" ;

	$dbQuery .= " ORDER BY filename ASC";

	//echo $dbQuery;

	$result = mysql_query($dbQuery) or die("Couldn't get file list");

//$list = '<br><br>';
//$list .= '<table width="100%" cellpadding="0" cellspacing="0" border="0"><tbody><tr>';
//$list .= '<td vAlign="middle" class="formHeader" align="left" noWrap width="100%" height="15">Attachment</td></tr></tbody></table>';

$list = '<table border="0" cellpadding="0" cellspacing="0" class="FormBorder" width="100%">';
$list .= '<tr class="ModuleListTitle" height=20>';
$list .= '<td>'.$app_strings['LBL_OPERATION'].'</td>';
$list .= '    ';

$list .= '<td class="moduleListTitle" height="21">';

$list .= '<p style="margin-left: 10">';

$list .= $app_strings['LBL_FILENAME'].'</td>';
$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
$list .= '<td width="15%" class="moduleListTitle">';

$list .= '<p style="margin-left: 10">';

$list .= $app_strings['LBL_UPD_DESC'].'</td>';
$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
$list .= '<td width="15%" class="moduleListTitle">';

$list .= '<p style="margin-left: 10">';


$list .= $app_strings['LBL_TYPE'].'</td>';
$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
$list .= '<td class="moduleListTitle" >';

$list .= '<p style="margin-left: 10">';

$list .= $app_strings['LBL_FILE'].'</td>';

$list .= '</tr>';

$list .= '<tr><td COLSPAN="7" class="blackLine"><IMG SRC="themes/'.$theme.'/images//blank.gif"></td></tr>';

$i=1;
while($row = mysql_fetch_array($result))
{


if ($i%2==0)
$trowclass = 'evenListRow';
else
$trowclass = 'oddListRow';
	$list .= '<tr class="'. $trowclass.'"><td><a href="index.php?module=uploads&action=deleteattachments&filename='.$row["filename"] .'&record='.$_REQUEST["record"] .'">Del</td> <td width="34%" height="21" style="padding:0px 3px 0px 3px;"><p style="margin-left: 10; margin-right: 10">';

	 $list .= $row["filename"]; 

	$list .= '</td>';


	$list .= '<td></td><td width="33%" height="21" style="padding:0px 3px 0px 3px;">	<p style="margin-left: 10">';

	 $list .= $row["description"]; 

	$list .= '</td>';


	$list .= '<td></td><td width="33%" height="21" style="padding:0px 3px 0px 3px;">	<p style="margin-left: 10">';

	 $list .= $row["filetype"]; 

	$list .= '</td>';


	$list .= '<td></td><td width="33%" height="21" style="padding:0px 3px 0px 3px;"><p style="margin-left: 10">';

	$list .= '<a href="index.php?module=uploads&action=downloadfile&fileId='.$row['fileid'] .'">';

	$list .= $app_strings['LBL_DOWNLOAD'];

	$list .= '</a></td></tr>';
$i++;
}

	$list .= '</table>';

return $list;
}
?>
