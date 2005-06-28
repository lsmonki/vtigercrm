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

require_once('include/utils.php');
require_once('include/database/PearDatabase.php');
function getBlockInformation($module, $block, $mode, $col_fields)
{
	//retreive the tabid	
	global $adb;
	$tabid = getTabid($module);
	global $profile_id;

	$sql = "select * from field inner join profile2field on profile2field.fieldid=field.fieldid inner join def_org_field on def_org_field.fieldid=field.fieldid  where field.tabid=".$tabid." and field.block=".$block ." and field.displaytype=1 and profile2field.visible=0 and def_org_field.visible=0 and profile2field.profileid=".$profile_id." order by sequence";
	

        $result = $adb->query($sql);
	$noofrows = $adb->num_rows($result);
	$output='';
	if (($module == 'Accounts' || $module == 'Contacts' || $module == 'Quotes' || $module == 'Orders' || $module == 'SalesOrder'|| $module == 'Invoice') && $block == 2)
	{
		global $vtlog;
		$vtlog->logthis("module is ".$module,'info');  
		
			$mvAdd_flag = true;
			$moveAddress = "<td rowspan='5' valign='middle' align='center'><input title='Copy billing address to shipping address'  class='button' onclick='return copyAddressRight(EditView)'  type='button' name='copyright' value='&raquo;' style='padding:0px 2px 0px 2px;font-size:12px'><br><br>
				<input title='Copy shipping address to billing address'  class='button' onclick='return copyAddressLeft(EditView)'  type='button' name='copyleft' value='&laquo;' style='padding:0px 2px 0px 2px;font-size:12px'></td>";
	}
	

	for($i=0; $i<$noofrows; $i++)
	{
		$fieldtablename = $adb->query_result($result,$i,"tablename");	
		$fieldcolname = $adb->query_result($result,$i,"columnname");	
		$uitype = $adb->query_result($result,$i,"uitype");	
		$fieldname = $adb->query_result($result,$i,"fieldname");	
		$fieldlabel = $adb->query_result($result,$i,"fieldlabel");
		$maxlength = $adb->query_result($result,$i,"maxlength");
		$generatedtype = $adb->query_result($result,$i,"generatedtype");				

		$output .= '<tr>';
		$custfld = getOutputHtml($uitype, $fieldname, $fieldlabel, $maxlength, $col_fields,$generatedtype);
		$output .= $custfld;	
		if ($mvAdd_flag == true)
		$output .= $moveAddress;
		$mvAdd_flag = false;
		$i++;
		if($i<$noofrows)
		{
			$fieldtablename = $adb->query_result($result,$i,"tablename");	
			$fieldcolname = $adb->query_result($result,$i,"columnname");	
			$uitype = $adb->query_result($result,$i,"uitype");	
			$fieldname = $adb->query_result($result,$i,"fieldname");	
			$fieldlabel = $adb->query_result($result,$i,"fieldlabel");
			$maxlength = $adb->query_result($result,$i,"maxlength");
			$generatedtype = $adb->query_result($result,$i,"generatedtype");
			$output .= '';
			$custfld = getOutputHtml($uitype, $fieldname, $fieldlabel, $maxlength, $col_fields,$generatedtype);
			$output .= $custfld;	
		}
		$output .= '</tr>';
			
	}
	return $output;
		
}


function getDetailBlockInformation($module, $block, $col_fields)
{
	//retreive the tabid	
	global $adb;
	$tabid = getTabid($module);
        global $profile_id;

	//retreive the fields from database
	
	$sql = "select * from field inner join profile2field on profile2field.fieldid=field.fieldid inner join def_org_field on def_org_field.fieldid=field.fieldid where field.tabid=".$tabid." and field.block=".$block ." and field.displaytype in (1,2) and profile2field.visible=0 and def_org_field.visible=0  and profile2field.profileid=".$profile_id." order by sequence";
	
	$result = $adb->query($sql);
	$noofrows = $adb->num_rows($result);
	$output='';
	for($i=0; $i<$noofrows; $i++)
	{
		$fieldtablename = $adb->query_result($result,$i,"tablename");	
		$fieldcolname = $adb->query_result($result,$i,"columnname");	
		$uitype = $adb->query_result($result,$i,"uitype");	
		$fieldname = $adb->query_result($result,$i,"fieldname");	
		$fieldlabel = $adb->query_result($result,$i,"fieldlabel");
		$maxlength = $adb->query_result($result,$i,"maxlength");
		$generatedtype = $adb->query_result($result,$i,"generatedtype");
		$output .= '<tr>';
		$custfld = getDetailViewOutputHtml($uitype, $fieldname, $fieldlabel, $col_fields,$generatedtype);
		$output .= $custfld;
		$i++;
		if($i<$noofrows)
		{
			$fieldtablename = $adb->query_result($result,$i,"tablename");	
			$fieldcolname = $adb->query_result($result,$i,"columnname");	
			$uitype = $adb->query_result($result,$i,"uitype");	
			$fieldname = $adb->query_result($result,$i,"fieldname");	
			$fieldlabel = $adb->query_result($result,$i,"fieldlabel");
			$maxlength = $adb->query_result($result,$i,"maxlength");
			$generatedtype = $adb->query_result($result,$i,"generatedtype");

			$output .= '';
			$custfld = getDetailViewOutputHtml($uitype, $fieldname, $fieldlabel, $col_fields,$generatedtype);
			$output .= $custfld;	
		}
		$output .= '</tr>';

	}
	return $output;

}

function getBlockTableHeader($header_label)
{
	global $mod_strings;
	$label = $mod_strings[$header_label];
	$output = '<table width="100%" border="0" cellspacing="1" cellpadding="0">';
	$output .= '<tr><th align="left" class="formSecHeader">'.$label.'</th></tr>';
	$output .= '</table>';
	return $output;

}
//added $viewid for customview to retain the sameview 27/5
function getTableHeaderNavigation($navigation_array, $url_qry,$module='',$action_val='index',$viewid='')
{
	global $theme;
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	$output = '<td align="right">';

	$dir_name=getModuleDirName($module);
	if(isset($navigation_array['prev']))
	{
		$output .= '<a href="index.php?module='.$dir_name.'&action='.$action_val.$url_qry.'&start=1&viewname='.$viewid.'" title="First"><img src="'.$image_path.'start.gif" border="0" align="absmiddle"></a>&nbsp;';
		$output .= '<a href="index.php?module='.$dir_name.'&action='.$action_val.$url_qry.'&start='.$navigation_array['prev'].'&viewname='.$viewid.'"><img src="'.$image_path.'previous.gif" border="0" align="absmiddle"></a>&nbsp;';

	}
	else
	{
		$output .= '<img src="'.$image_path.'start_disabled.gif" border="0" align="absmiddle">&nbsp;';
		$output .= '<img src="'.$image_path.'previous_disabled.gif" border="0" align="absmiddle">&nbsp;';
	}
	if(isset($navigation_array['next']))
	{
		$output .= '<a href="index.php?module='.$dir_name.'&action='.$action_val.$url_qry.'&start='.$navigation_array['next'].'&viewname='.$viewid.'"><img src="'.$image_path.'next.gif" border="0" align="absmiddle"></a>&nbsp;';
		$output .= '<a href="index.php?module='.$dir_name.'&action='.$action_val.$url_qry.'&start='.$navigation_array['end'].'&viewname='.$viewid.'"><img src="'.$image_path.'end.gif" border="0" align="absmiddle"></a>&nbsp;';
	}
	else
	{
		$output .= '<img src="'.$image_path.'next_disabled.gif" border="0" align="absmiddle">&nbsp;';
		$output .= '<img src="'.$image_path.'end_disabled.gif" border="0" align="absmiddle">&nbsp;';
	}
	$output .= '</td>';
	return $output;
}

?>
