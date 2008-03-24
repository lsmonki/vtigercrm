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
	require_once('Smarty_setup.php');
	global $mod_strings;
	global $app_strings;
	global $app_list_strings, $current_language,$adb;
	global $theme;
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";

	$smarty = new vtigerCRM_Smarty;
	$smarty->assign("IMAGE_PATH",$image_path);

	$fieldName=$_REQUEST["fieldname"];
	$fieldLabel =$_REQUEST["fieldlabel"];
	$moduleName=$_REQUEST["fld_module"];
	$uitype=$_REQUEST["uitype"];
	$mode=$_REQUEST["mode"];

	
	if($moduleName == 'Events')
	$temp_module_strings = return_module_language($current_language, 'Calendar');
else
	$temp_module_strings = return_module_language($current_language, $moduleName);


	if($mode == 'replace')
	{
		$replaceWith = addslashes($_REQUEST['replaceFields']);
		$selectedFields = $_REQUEST['selectedFields'];
		$unwantedPicklist = explode(',',$selectedFields);
		foreach($unwantedPicklist as $key => $val)
		{
			$qry="select tablename,columnname,uitype from vtiger_field where fieldname='$fieldName'";
			$result = $adb->query($qry);
			$num = $adb->num_rows($result);
			$val = "'".addslashes($val)."'";
			if($num > 0)
			{
				for($n=0;$n<$num;$n++)
				{
					$table_name = $adb->query_result($result,$n,'tablename');
					$column_name = $adb->query_result($result,$n,'columnname');
						if($replaceWith == '--None--')
						{
							$replaceWith='';
						}
					$update_picklist = "update $table_name set $column_name='$replaceWith' where $column_name=".$val;
					$adb->query($update_picklist);
					$dele_pick_val =" delete from vtiger_role2picklist where picklistvalueid in (select picklist_valueid from vtiger_$fieldName where $fieldName=$val)";
					$adb->query($dele_pick_val);

					$del_qry = "delete from vtiger_$fieldName where $fieldName=".$val;
					$adb->query($del_qry);


				}
			}
		}
		echo ":#:SUCCESS";
		exit;

	}	
	if(isset($fieldName))
	{
		$sql="select $fieldName from vtiger_$fieldName where presence=1 and $fieldName <> '--None--'";
		$res = $adb->query($sql);
		$RowCount = $adb->num_rows($res);
		if($RowCount > 0)
		{
			for($i=0;$i<$RowCount;$i++)
			{
				$pick_val = $adb->query_result($res,$i,$fieldName);
				if($temp_module_strings[$pick_val] != '')
				{
					$pick[]=$temp_module_strings[$pick_val];
				}else
				{
					$pick[]=$pick_val;
				}
			}
		}

		$nonEdit ="select $fieldName from vtiger_$fieldName where presence=0";
		$res_ult = $adb->query($nonEdit);
		$non_editNum = $adb->num_rows($res_ult);
		if($non_editNum > 0)
		{
			for($l=0;$l<$non_editNum;$l++)
			{
				$non_val = $adb->query_result($res_ult,$l,$fieldName);
				if($temp_module_strings[$non_val] != '')
				{
					$non_pick[]=$temp_module_strings[$non_val];
				}
				else
				{
					$non_pick[]=$non_val;
				}
			}
		}
		if(is_array($non_pick))
		$smarty->assign('NONEDIT_FLAG','true');
		else
		$smarty->assign('NONEDIT_FLAG','false');
				

		$smarty->assign('NONEDITPICKLIST',$non_pick);
		

		if($mode == "transfer")
		{
			$option='';
			$selectedFields = $_REQUEST['selectedFields'];
			$pick_arr = explode(",",$_REQUEST['selectedFields']);
			foreach($pick_arr as $v)
			{
				$pick_arr2[] = "'".addslashes($v)."'";
			}
			$pick_str = implode(",",$pick_arr2);
			$sql="select $fieldName from vtiger_$fieldName where $fieldName not in ($pick_str)";
			
			$res=$adb->query($sql);
			$num_Rows = $adb->num_rows($res);
				if($num_Rows > 0)
				{
					for($j=0;$j<$num_Rows;$j++)
					{
						$avail_entries = $adb->query_result($res,$j,$fieldName);
						if($temp_module_strings[$avail_entries] != '')
						{
							$option .= "<option value='".$avail_entries."'>".$temp_module_strings[$avail_entries]."</option>";
						}else
						$option .= "<option value='".$avail_entries."'>".$avail_entries."</option>";
					}
				}
				
			
			
			$output="<table border=0 cellspacing=0 cellpadding=5 width=100%>
					<tr><td colspan=2 align='center'><strong> ".$mod_strings['LBL_PICKLIST_TRANSFER']."\"".$fieldLabel."\"</strong></td><td align='right'><img src='".$image_path."close.gif' align='middle' border='0' onclick=hide('transferdiv');></td></tr>
					<tr><td></td><td></td><td></td></tr>
					<tr><td class='small' align='right'><b>".$mod_strings['LBL_REPLACE_VALUE_WITH'].":</b></td><td align='left'><select style='width:180px;font-size:normal;' class='small detailedViewTextBox' id='replacePick'>$option</select></td><td></td></tr>
					<tr><td colspan=3 align='center'><input type='button' name='replaceText' value='".$app_strings['LBL_REPLACE_LABEL']."' onClick=pickReplace('".$moduleName."','".$fieldName."'); class='crmButton small save'>&nbsp;<input type='button' value='".$app_strings['LBL_CANCEL_BUTTON_LABEL']."' name='cancel' class='crmButton small cancel' onclick=\"hide('transferdiv');\"></td></tr>
				</table>";
				
			$smarty->assign("OUTPUT",$output);
		}

		
		$temp_label = getTranslatedString($fieldLabel);
		$smarty->assign("FIELDLABEL",$temp_label);
		$smarty->assign("MODE",$mode);
		$smarty->assign("MODULE",$moduleName);
		$smarty->assign("PICKVAL",$pick);
		$smarty->assign("MOD", return_module_language($current_language,'Settings'));
		$smarty->assign("APP",$app_strings);
		$smarty->display("Settings/DeletePickList.tpl");
	}
	


?>
