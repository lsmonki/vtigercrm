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
require_once('include/utils.php');

function CustomFieldEditView($id, $fldModule, $tableName, $colidName, $app_strings, $theme)
{

	//Custom Field Addition
	$dbquery = "select  * from customfields inner join customfieldtypemapping on customfields.uitype=customfieldtypemapping.uitype where module='".$fldModule."'";
	$result = mysql_query($dbquery);
	if(mysql_num_rows($result) != 0)
	{
		if(isset($id))
		{
			$custquery = 'select * from '.$tableName.' where '.$colidName.'="'.$id.'"';
			$cust_result = mysql_query($custquery);
		}
		$noofrows = mysql_num_rows($result);

		$custfld = '<table width="100%" border="0" cellspacing="1" cellpadding="0">';
		$custfld .= '<tr><th align="left" class="formSecHeader" colspan="4">Custom Information</th></tr>';
		for($i=0; $i<$noofrows; $i++)
		{
			$colName=mysql_result($result,$i,"fieldlabel");
			$setName=mysql_result($result,$i,"column_name");
			$uitype=mysql_result($result,$i,"uitype");
			if(isset($id) && mysql_num_rows($cust_result) != 0)
			{
				$value=mysql_result($cust_result,0,$setName);
			}
			else
			{
				$value='';
			}
			$custfld .= '<tr>
				<td width="20%" class="dataLabel">'.$colName.':</td>';
			if($uitype == 5)
			{
				$date_format = parse_calendardate($app_strings['NTC_DATE_FORMAT']);
				$custfld .= '<td width="30%"><input name="'.$setName.'" id="jscal_field_'.$setName.'" type="text" tabindex="2" size="11" maxlength="10" value="'.$value.'"> <img src="themes/'.$theme.'/images/calendar.gif" id="jscal_trigger_'.$setName.'"> <font size=1><em old="(yyyy-mm-dd)">(yyyy-mm-dd)</em></font></td>';
				$custfld .= '<script type="text/javascript">';
				$custfld .= 'Calendar.setup ({';
						$custfld .= 'inputField : "jscal_field_'.$setName.'", ifFormat : "'.$date_format.'", showsTime : false, button : "jscal_trigger_'.$setName.'", singleClick : true, step : 1';
						$custfld .= '});';
				$custfld .= '</script>';
			}
			elseif($uitype == 15)
			{
				$pick_query="select * from ".$fldModule."_".$setName;
				$pickListResult = mysql_query($pick_query);
				$noofpickrows = mysql_num_rows($pickListResult);
				$custfld .= '<td width="30%"><select name="'.$setName.'" tabindex="1">';
				for($j = 0; $j < $noofpickrows; $j++)
				{
					$pickListValue=mysql_result($pickListResult,$j,$setName);
					
					if($value == $pickListValue)
					{
						$chk_val = "selected";	
					}
					else
					{	
						$chk_val = '';	
					}
					
					$custfld .= '<OPTION value="'.$pickListValue.'" '.$chk_val.'>'.$pickListValue.'</OPTION>';
				}
				$custfld .= '</td>';
			}
			else
			{

				$custfld .= '<td width="30%"><input name="'.$setName.'" type="text" tabindex="'.$i.'" size="25" maxlength="25" value="'.$value.'"></td>';
			}
			$i++;
			if($i<$noofrows)
			{
				$colName=mysql_result($result,$i,"fieldlabel");
				$setName=mysql_result($result,$i,"column_name");
				$uitype=mysql_result($result,$i,"uitype");
				if(isset($id) && mysql_num_rows($cust_result) != 0)
				{
					$value=mysql_result($cust_result,0,$setName);
				}
				else
				{
					$value='';
				}
				$custfld .= '<td width="20%" class="dataLabel">'.$colName.':</td>';
				if($uitype == 5)
				{
					$date_format = parse_calendardate($app_strings['NTC_DATE_FORMAT']);
					$custfld .= '<td width="30%"><input name="'.$setName.'" id="jscal_field_'.$setName.'" type="text" tabindex="2" size="11" maxlength="10" value="'.$value.'"> <img src="themes/'.$theme.'/images/calendar.gif" id="jscal_trigger_'.$setName.'"> <font size=1><em old="(yyyy-mm-dd)">(yyyy-mm-dd)</em></font></td>';
					$custfld .= '<script type="text/javascript">';
					$custfld .= 'Calendar.setup ({';
							$custfld .= 'inputField : "jscal_field_'.$setName.'", ifFormat : "'.$date_format.'", showsTime : false, button : "jscal_trigger_'.$setName.'", singleClick : true, step : 1';
							$custfld .= '});';
					$custfld .= '</script>';

				}
				elseif($uitype == 15)
				{
					$pick_query="select * from ".$fldModule."_".$setName;
					$pickListResult = mysql_query($pick_query);
					$noofpickrows = mysql_num_rows($pickListResult);
					$custfld .= '<td width="30%"><select name="'.$setName.'" tabindex="1">';
					for($j = 0; $j < $noofpickrows; $j++)
					{
						$pickListValue=mysql_result($pickListResult,$j,$setName);

						if($value == $pickListValue)
						{
							$chk_val = "selected";	
						}
						else
						{	
							$chk_val = '';	
						}

						$custfld .= '<OPTION value="'.$pickListValue.'" '.$chk_val.'>'.$pickListValue.'</OPTION>';
					}
					$custfld .= '</td>';
				}
				else
				{
					$custfld .= '<td width="30%"><input name="'.$setName.'" type="text" tabindex="'.$i.'" size="25" maxlength="25" value="'.$value.'"></td>';
				}
			}

			$custfld .= '<tr>';

		}
		$custfld .= '</table>';
		return $custfld;

	}
}

function CustomFieldDetailView($id, $fldModule, $tableName, $colidName)
{
	//Assigning custom field values
	$dbquery = "select  * from customfields where module='".$fldModule."'";
	$result = mysql_query($dbquery);
	if(mysql_num_rows($result) != 0)
	{
		$custquery = 'select * from '.$tableName.' where '.$colidName.'="'.$id.'"';
		$cust_result = mysql_query($custquery);

		$noofrows=mysql_num_rows($result);
		$custfld = '';	
		for($i=0; $i<$noofrows; $i++)
		{
			$fldName=mysql_result($result,$i,"fieldlabel");
			$colName=mysql_result($result,$i,"column_name");
			$uitype=mysql_result($result,$i,"uitype");
			if(mysql_num_rows($cust_result) != 0)
			{
				$value=mysql_result($cust_result,0,$colName);
			}
			else
			{
				$value='';
			}
			$custfld .= '<tr>';
			$custfld .= '<td width="20%" valign="top" class="dataLabel">'.$fldName.':</td>';
			if($uitype == 13)
			{
				$custfld .= '<td width="30%" valign="top" class="dataField"><a href="mailto:'.$value.'">'.$value.'</a></td>';
			}
			else
			{
				$custfld .= '<td width="30%" valign="top" class="dataField">'.$value.'</td>';
			}	
			$i++;
			if($i<$noofrows)
			{
				$fldName=mysql_result($result,$i,"fieldlabel");
				$colName=mysql_result($result,$i,"column_name");
				$uitype=mysql_result($result,$i,"uitype");
				if(mysql_num_rows($cust_result) != 0)
				{
					$value=mysql_result($cust_result,0,$colName);
				}
				else
				{
					$value='';
				}
				$custfld .= '<td width="20%" valign="top" class="dataLabel">'.$fldName.':</td>';
				if($uitype == 13)
				{

					$custfld .= '<td width="30%" valign="top" class="dataField"><a href="mailto:'.$value.'">'.$value.'</a></td>';
				}
				else
				{
					$custfld .= '<td width="30%" valign="top" class="dataField">'.$value.'</td>';
				}
			}


			$custfld .= '<tr>';

		}
	}
	return $custfld;
}

function getCustomFieldArray($module)
{
	$custquery = "select * from customfields where module='".$module."'";
	$custresult = mysql_query($custquery);
	$custFldArray = Array();
	$noofrows = mysql_num_rows($custresult);
	for($i=0; $i<$noofrows; $i++)
	{
		$colName=mysql_result($custresult,$i,"column_name");
		$custFldArray[$colName] = $i;
	}
	return $custFldArray;
	
}
?>
