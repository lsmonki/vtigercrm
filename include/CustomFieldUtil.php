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
require_once('include/utils.php');

function getCustomFieldTypeName($uitype)
{
	global $vtlog;
	$vtlog->logthis("uitype is ".$uitype,'info');  
	$fldname = '';
	
	if($uitype == 1)
	{
		$fldname = 'Text';
	}
	elseif($uitype == 7)
	{
		$fldname = 'Number';
	}
	elseif($uitype == 9)
	{
		$fldname = 'Percent';
	}
	elseif($uitype == 3)
	{
		$fldname = 'Currency';
	}
	elseif($uitype == 5)
	{
		$fldname = 'Date';
	}
	elseif($uitype == 13)
	{
		$fldname = 'Email';
	}
	elseif($uitype == 11)
	{
		$fldname = 'Phone';
	}
	elseif($uitype == 15)
	{
		$fldname = 'PickList';
	}
	elseif($uitype == 17)
	{
		$fldname = 'Url';
	}
	elseif($uitype == 56)
	{
		$fldname = 'Checkbox';
	}
	elseif($uitype == 71)
	{
		$fldname = 'Currency';
	}
	elseif($uitype == 21)
	{
		$fldname = 'Text Area';
	}
	return $fldname;
}


function CustomFieldEditView($id, $fldModule, $tableName, $colidName, $app_strings, $theme)
{

	global $adb;
	//Custom Field Addition
	$dbquery = "select  * from field where tablename='".$fldModule."'";
	$result = $adb->query($dbquery);
	if($adb->num_rows($result) != 0)
	{
		if(isset($id))
		{
			$custquery = 'select * from '.$tableName.' where '.$colidName."='".$id."'";
			$cust_result = $adb->query($custquery);
		}
		$noofrows = $adb->num_rows($result);

		$custfld = '<table width="100%" border="0" cellspacing="1" cellpadding="0">';
		$custfld .= '<tr><th align="left" class="formSecHeader" colspan="4">Custom Information</th></tr>';
		for($i=0; $i<$noofrows; $i++)
		{
			$colName=$adb->query_result($result,$i,"fieldlabel");
			$setName=$adb->query_result($result,$i,"column_name");
			$uitype=$adb->query_result($result,$i,"uitype");
			if(isset($id) && $adb->num_rows($cust_result) != 0)
			{
				$value=$adb->query_result($cust_result,0,strtolower($setName));
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
				$pickListResult = $adb->query($pick_query);
				$noofpickrows = $adb->num_rows($pickListResult);
				$custfld .= '<td width="30%"><select name="'.$setName.'" tabindex="1">';
				for($j = 0; $j < $noofpickrows; $j++)
				{
					$pickListValue=$adb->query_result($pickListResult,$j,strtolower($setName));
					
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
				$colName=$adb->query_result($result,$i,"fieldlabel");
				$setName=$adb->query_result($result,$i,"column_name");
				$uitype=$adb->query_result($result,$i,"uitype");
				if(isset($id) && $adb->num_rows($cust_result) != 0)
				{
					$value=$adb->query_result($cust_result,0,$setName);
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
					$pickListResult = $adb->query($pick_query);
					$noofpickrows = $adb->num_rows($pickListResult);
					$custfld .= '<td width="30%"><select name="'.$setName.'" tabindex="1">';
					for($j = 0; $j < $noofpickrows; $j++)
					{
						$pickListValue=$adb->query_result($pickListResult,$j,strtolower($setName));

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
	global $adb;
	//Assigning custom field values
	$dbquery = "select  * from field where tablename='".$fldModule."'";
	$result = $adb->query($dbquery);
	$adb->println($result);
	if($adb->num_rows($result) != 0)
	{
		$custquery = 'select * from '.$tableName.' where '.$colidName."='".$id."'";
		$cust_result = $adb->query($custquery);
		$adb->println($cust_result);

		$noofrows=$adb->num_rows($result);
		$custfld = '';	
		for($i=0; $i<$noofrows; $i++)
		{
			$fldName=$adb->query_result($result,$i,"fieldlabel");
			$colName=$adb->query_result($result,$i,"column_name");
			$uitype=$adb->query_result($result,$i,"uitype");
			if($adb->num_rows($cust_result) != 0)
			{
				$value=$adb->query_result($cust_result,0,strtolower($colName));
			}
			else
			{
				$value='';
				$adb->println("emply value ");
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
				$fldName=$adb->query_result($result,$i,"fieldlabel");
				$colName=$adb->query_result($result,$i,"column_name");
				$uitype=$adb->query_result($result,$i,"uitype");
				if($adb->num_rows($cust_result) != 0)
				{
					$value=$adb->query_result($cust_result,0,strtolower($colName));
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
	global $adb;
	$custquery = "select * from field where tablename='".$module."'";
	$custresult = $adb->query($custquery);
	$custFldArray = Array();
	$noofrows = $adb->num_rows($custresult);
	for($i=0; $i<$noofrows; $i++)
	{
		$colName=$adb->query_result($custresult,$i,"column_name");
		$custFldArray[$colName] = $i;
	}
	return $custFldArray;
	
}

function getCustomFieldTrans($module, $trans_array)
{
	global $adb;
	$tab_id = getTabid($module);	
	$custquery = "select columnname,fieldlabel from field where generatedtype=2 and tabid=".$tab_id;
	$custresult = $adb->query($custquery);
	$custFldArray = Array();
	$noofrows = $adb->num_rows($custresult);
	for($i=0; $i<$noofrows; $i++)
	{
		$colName=$adb->query_result($custresult,$i,"columnname");
		$fldLbl = $adb->query_result($custresult,$i,"fieldlabel");
		$trans_array[$colName] = $fldLbl;
	}	
}


function CustomFieldSearch($customfieldarray, $fldModule, $tableName,$colidName,$app_strings,$theme,$fieldlabel,$column)
{
global $adb;
//for($i=0;$i<count($customfieldarray);$i++){echo '<br> Custom Field : '.$i.'...'.$customfieldarray[$i];}
        //Custom Field Addition
        $dbquery = "select  * from field  where tablename='".$fldModule."' order by fieldlabel";
        $result = $adb->query($dbquery);
        if($adb->num_rows($result) != 0)
        {
                $noofrows = $adb->num_rows($result);

                $custfld = '<table width="85%" border="0" cellspacing="0" cellpadding="0">';
                $custfld .= '<tr><th align="left" class="formSecHeader" colspan="4">'.$app_strings['LBL_CUSTOM_INFORMATION'].'</th></tr>';
                for($i=0; $i<$noofrows; $i++)
                {
                        $id=$customfieldarray[$i];
                        $colName=$column[$i];
                        $setName=$fieldlabel[$i];
			$uitype[$i] = $adb->query_result($result,$i,'uitype');

			if($uitype[$i] == 56)
		        {
                		$custfld .= '<td width="20%" class="dataLabel">'.$colName.':</td>';
		                if($customfieldarray[$i] == 'on')
                		{
		                        $custfld .='<td width="30%"><input name="'.$setName.'" type="checkbox"  checked></td>';
                		}
		                else
                		{
		                        $custfld .='<td width="30%"><input name="'.$setName.'" type="checkbox"></td>';
                		}
		        }
			else
			{
	                        $custfld .= '<td width="20%" class="dataLabel">'.$colName.':</td>';

        	                $custfld .= '<td width="30%"><input name="'.$setName.'" type="text" tabindex="'.$i.'" size="25" maxlength="25" value="'.$customfieldarray[$i].'"></td>';
	                        if($i%2==1)
        	                {
                	                $custfld .= '<tr>';
                        	}
			}
                }

                $custfld .= '</table>';
                return $custfld;

        }
}

?>
