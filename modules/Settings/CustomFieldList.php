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
require_once ($theme_path."layout_utils.php");
global $mod_strings;

echo get_module_title("Settings", "Settings: ".$_REQUEST['fld_module']." Custom Fields" , true);
//or die("Couldn't connect to database $dbDatabase");

echo '<table width="25%" cellpadding="2" cellspacing="0" border="0">';
echo '<form action="index.php" method="post" name="new" id="form">';
echo '<input type="hidden" name="fld_module" value="'.$_REQUEST['fld_module'].'">';
echo '<input type="hidden" name="module" value="Settings">';
echo '<input type="hidden" name="action" value="CreateCustomField">';
echo '<tr><br>';
echo '<td><input title="New Custom Field [Alt+C]" accessKey="C" class="button" type="submit" name="NewCustomField" value=" New Custom Field "></td>';
echo '</tr></form></table>';
echo '<br>';
//onclick="this.form.return_module.value="Settings"; this.form.action.value="index"
echo getCustomFieldList($_REQUEST['fld_module']);

function getCustomFieldList($customfieldmodule)
{

//fieldid,fieldlabel,column_name,typdesc

	$dbQuery = "select * from customfields inner join customfieldtypemapping on customfields.uitype=customfieldtypemapping.uitype where module='".$customfieldmodule."' order by fieldlabel";

	//echo $dbQuery;

	$result = mysql_query($dbQuery) or die("Couldn't get file list");


$list = '<table border="0" cellpadding="0" cellspacing="0" class="FormBorder" width="80%">';

$list .='<form action="index.php" method="post" name="CustomFieldUpdate" id="form">';

$list .= '<tr class="ModuleListTitle" height=20>';

$list .= '';

$list .= '<td class="moduleListTitle" height="21">';

$list .= '<p style="margin-left: 10">';

$list .= 'Field Name</td>';
//$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
$list .= '<td width="15%" class="moduleListTitle">';

$list .= '<p style="margin-left: 10">';

$list .= 'Field Type</td>';

$list .= '<td width="15%" class="moduleListTitle">';

$list .= '<p style="margin-left: 10">';

$list .= '</td>';

$list .= '</tr>';

//$list .= '<tr><td COLSPAN="7" class="blackLine"><IMG SRC="themes/'.$theme.'/images//blank.gif"></td></tr>';

$i=1;
while($row = mysql_fetch_array($result))
{


if ($i%2==0)
$trowclass = 'evenListRow';
else
$trowclass = 'oddListRow';
	$list .= '<tr class="'. $trowclass.'"><td width="34%" height="21"><p style="margin-left: 10;">';

	 $list .= $row["fieldlabel"]; 

	$list .= '</td>';


	$list .= '<td width="33%" height="21">	<p style="margin-left: 10">';

	 $list .= $row["typdesc"]; 

	$list .= '</td>';

	$list .= '<td width="33%" height="21">	<p style="margin-left: 10">';

	 $list .= '<a href="javascript:deleteCustomField('.$row["fieldid"].',\''.$customfieldmodule.'\', \''.$row["column_name"].'\', \''.$row["uitype"].'\')">Del</a>'; 

	$list .= '</td>';


	$list .= '</tr>';
$i++;
}
	$list .= '</form>';

	$list .= '</table>';

	$list .= '<script type="text/javascript">';
	$list .= 'function deleteCustomField(id, fld_module, colName, uitype)
	  	  {
			if(confirm("Are you sure?"))
			{
				document.CustomFieldUpdate.action="index.php?module=Settings&action=DeleteCustomField&fld_module="+fld_module+"&fld_id="+id+"&colName="+colName+"&uitype="+uitype
				document.CustomFieldUpdate.submit()
		   	}	
	  	   }';
	$list .= '</script>';
	

return $list;
}
?>
