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

require_once('include/database/PearDatabase.php');
require_once('include/CustomFieldUtil.php');
require_once ($theme_path."layout_utils.php");
global $mod_strings;

echo get_module_title("Settings", $mod_strings['LBL_MODULE_NAME'].": ".$mod_strings[$_REQUEST['fld_module']].$mod_strings['CustomFields'] , true);
//or die("Couldn't connect to database $dbDatabase");

echo '<table width="25%" cellpadding="2" cellspacing="0" border="0">';
echo '<form action="index.php" method="post" name="new" id="form">';
echo '<input type="hidden" name="fld_module" value="'.$_REQUEST['fld_module'].'">';
echo '<input type="hidden" name="module" value="Settings">';
echo '<input type="hidden" name="action" value="CreateCustomField">';
echo '<tr><br>';
echo '<td><input title="'.$mod_strings['`'].'" accessKey="C" class="button" type="submit" name="NewCustomField" value="'.$mod_strings['NewCustomField'].'"></td>';

if($_REQUEST['fld_module']=="Leads")
{
	echo '<td><input title="'.$mod_strings['CUSTOMFIELDMAPPING'].'"  class="button" onclick="this.form.action.value=\'LeadCustomFieldMapping\'" type="submit" name="LeadCustomFieldMapping" value="'.$mod_strings['CUSTOMFIELDMAPPING'].'"></td>'; //button for custom field mapping
}

echo '</tr></form></table>';
echo '<br>';
//onclick="this.form.return_module.value="Settings"; this.form.action.value="index"


function fetchTabIDVal($fldmodule)
{

  global $adb;
  $query = "select tabid from tab where tablabel='" .$fldmodule ."'";
  $tabidresult = $adb->query($query);
  return $adb->query_result($tabidresult,0,"tabid");
}

$tabid = fetchTabIDVal($_REQUEST['fld_module']);

$fld_module = $_REQUEST['fld_module'];

echo getCustomFieldList($tabid,$mod_strings,$fld_module);


function getCustomFieldList($tabid, $mod_strings, $fld_module)
{
  global $adb;
        //fieldid,fieldlabel,column_name,typdesc

	$dbQuery = "select fieldid,columnname,fieldlabel,uitype,displaytype from field where tabid=".$tabid." and generatedtype=2 order by sequence";
        
        $result = $adb->query($dbQuery) or die("Couldn't get file list");


$list = '<table border="0" cellpadding="5" cellspacing="1" class="FormBorder" width="60%">';

$list .='<form action="index.php" method="post" name="CustomFieldUpdate" id="form">';

$list .= '<tr height=20>';

$list .= '<td class="ModuleListTitle" width="20%" style="padding:0px 3px 0px 3px;"><div><b>Operation</b></div>';

$list .= '</td>';

$list .= '';

$list .= '<td class="ModuleListTitle" height="21" width="20%" style="padding:0px 3px 0px 3px;"><b>';

$list .= $mod_strings['FieldName'].'</b></td>';

//$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
$list .= '<td class="ModuleListTitle" width="20%" style="padding:0px 3px 0px 3px;"><b>';

$list .= $mod_strings['FieldType'].'</b></td>';

$list .= '</tr>';

//$list .= '<tr><td COLSPAN="7" class="blackLine"><IMG SRC="themes/'.$theme.'/images//blank.gif"></td></tr>';

$i=1;
while($row = $adb->fetch_array($result))
{


if ($i%2==0)
$trowclass = 'evenListRow';
else
$trowclass = 'oddListRow';
	$list .= '<tr class="'. $trowclass.'">';
	
	$list .= '<td height="21" style="padding:0px 3px 0px 3px;"><div>';

	 $list .= '<a href="javascript:deleteCustomField('.$row["fieldid"].',\''.$fld_module.'\', \''.$row["columnname"].'\', \''.$row["uitype"].'\')">'.$mod_strings['Delete'].'</a>'; 

	$list .= '</div></td>';

	
	$list .= '<td height="21" style="padding:0px 3px 0px 3px;">';

	 $list .= $row["fieldlabel"]; 

	$list .= '</td>';
        

	$list .= '<td height="21" style="padding:0px 3px 0px 3px;">';

	$fld_type_name = getCustomFieldTypeName($row["uitype"]);

	 $list .= $fld_type_name; 

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
