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
include("modules/Accounts/Accounts.php");
include("getCompanyProfile.php");
$variable = $_REQUEST['tickersymbol'];
$url = "http://finance.yahoo.com/q?s=".$variable;
$data = getComdata($url);
if(is_array($data))
{
	$output = '';
	$output .='<div id="headnews">';
	$output .='<table>';
	foreach($data as $key=>$value)
	{
		$output .= '<tr valign="top">';
		$output .= '<td>'.$value[0].'</td>';
		$output .= '<td><a href="http://finance.yahoo.com/q/ud?s='.trim($variable).'">'.$value[1].'</a></td>';
		$output .= '</tr>';		
	}
	$output .='</table>';
	$output .='</div>';
	echo $output;
}
else
{
        $output = '';
        $output .= "<div style='display:block'>";
        $output .= "<b><font color='red'>".$data."</font>";
        $output .= "</div>";
        echo $output;
}
?>
