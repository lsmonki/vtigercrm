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
 
require_once('include/CustomFieldUtil.php');
require_once('Smarty_setup.php');

global $mod_strings,$app_strings,$app_list_strings,$theme,$adb;

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

require_once($theme_path.'layout_utils.php');

$tabid=$_REQUEST['tabid'];
$fieldid=$_REQUEST['fieldid'];
$readonly = '';
$smarty = new vtigerCRM_Smarty;
$cfimagecombo = Array($image_path."text.gif",
                        $image_path."number.gif",
                        $image_path."percent.gif",
                        $image_path."cfcurrency.gif",
                        $image_path."date.gif",
                        $image_path."email.gif",
                        $image_path."phone.gif",
                        $image_path."cfpicklist.gif",
                        $image_path."url.gif",
                        $image_path."checkbox.gif",
                        $image_path."text.gif",
                        $image_path."cfpicklist.gif");

$cftextcombo = Array($mod_strings['Text'],
                        $mod_strings['Number'],
                        $mod_strings['Percent'],
                        $mod_strings['Currency'],
                        $mod_strings['Date'],
                        $mod_strings['Email'],
                        $mod_strings['Phone'],
                        $mod_strings['PickList'],
                        $mod_strings['LBL_URL'],
                        $mod_strings['LBL_CHECK_BOX'],
                        $mod_strings['LBL_TEXT_AREA'],
                        $mod_strings['LBL_MULTISELECT_COMBO']
                        );
if(isset($fieldid) && $fieldid!='')
{
	$mode='edit';
	$customfield_columnname=getCustomFieldData($tabid,$fieldid,'columnname');
	$customfield_typeofdata=getCustomFieldData($tabid,$fieldid,'typeofdata');
	$customfield_fieldlabel=getCustomFieldData($tabid,$fieldid,'fieldlabel');
	$customfield_typename=getCustomFieldTypeName($_REQUEST['uitype']);
	$fieldtype_lengthvalue=getFldTypeandLengthValue($customfield_typename,$customfield_typeofdata);
	list($fieldtype,$fieldlength,$decimalvalue)= explode(";",$fieldtype_lengthvalue);
	$readonly = "readonly";
	if($fieldtype == '7' || $fieldtype == '11')
	{
		$query = "select * from vtiger_".$customfield_columnname;
		$result = $adb->query($query);
		$fldVal='';
		while($row = $adb->fetch_array($result))
		{
			$fldVal .= $row[$customfield_columnname];
			$fldVal .= "\n";
		}
		$smarty->assign("PICKLISTVALUE",$fldVal);
	}
	$selectedvalue = $fieldtype;
}
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("FLD_MODULE", $_REQUEST['fld_module']);
if(isset($_REQUEST["duplicate"]) && $_REQUEST["duplicate"] == "yes")
{
	$error='Custom Field in the Name '.$_REQUEST["fldlabel"].' already exists. Please specify a different Label';
	$smarty->assign("DUPLICATE_ERROR", $error);
	$customfield_fieldlabel=$_REQUEST["fldlabel"];
	$fieldlength=$_REQUEST["fldlength"];
	$decimalvalue=$_REQUEST["flddecimal"];
	$fldVal = $_REQUEST["fldPickList"];
	$typeVal = Array(
	'Text'=>'0',
	'Number'=>'1',
	'Percent'=>'2',
	'Currency'=>'3',
	'Date'=>'4',
	'Email'=>'5',
	'Phone'=>'6',
	'Picklist'=>'7',
	'URL'=>'8',
	'MultiSelectCombo'=>'11');
	$selectedvalue = $typeVal[$_REQUEST["fldType"]];
}
elseif($fieldid == '')
{
	$selectedvalue = "0";
}
$output = '';
$combo_output = '';
for($i=0;$i<count($cftextcombo);$i++)
{
        if($selectedvalue == $i && $fieldid != '')
                $sel_val = 'selected';
        else
                $sel_val = '';
        $combo_output.= '<option style="background:url('.$cfimagecombo[$i].');background-repeat:no-repeat;background-position:left;padding-left:30px;padding-top:5px;padding-bottom:5px;" '.$sel_val.' onClick="selFieldType('.$i.')" >'.$cftextcombo[$i].'</option>';

}
$output .= '<form action="index.php" method="post" name="addtodb" onSubmit="return validate()">
	  <input type="hidden" name="module" value="Settings">
	  <input type="hidden" name="fld_module" value="'.$_REQUEST['fld_module'].'">
	  <input type="hidden" name="parenttab" value="Settings">
          <input type="hidden" name="action" value="AddCustomFieldToDB">
	  <input type="hidden" name="fieldid" value="'.$fieldid.'">
	  <input type="hidden" name="column" value="'.$customfield_columnname.'">

	  <div id="orgLay" style="display:block;">
		<table width="100%" border="0" cellpadding="5" cellspacing="0">
			<tr>
				<td width="40%" align="left" class="genHeaderSmall">'.$mod_strings['LBL_ADD_FIELD'].'</td>
				<td width="60%" align="right"><a href="javascript:fninvsh(\'orgLay\');"><img src="'.$image_path.'close.gif" border="0"  align="absmiddle" /></a></td>
			</tr>
			<tr><td colspan="2"><hr /></td></tr>
			<tr>
				<td>
					<table>
						<tr><td>'.$mod_strings['LBL_SELECT_FIELD_TYPE'].'</td></tr>
						<tr><td>
							<select name="cfcombo" id="cfcombo" class=small size=10 multiple style="width:100%">'.$combo_output.'</select>
						</td></tr>
					</table>
				</td>
				<td>
					<table>
						<tr>
							<td class="dataLabel" nowrap="nowrap" align="right"><b>'.$mod_strings['LBL_LABEL'].' </b></td>
							<td align="left"><input name="fldLabel" value="'.$customfield_fieldlabel.'" type="text" class="txtBox"></td>
						</tr>
						<tr id="lengthdetails">
							<td class="dataLabel" nowrap="nowrap" align="right"><b>'.$mod_strings['LBL_LENGTH'].'</b></td>
							<td align="left"><input type="text" name="fldLength" value="'.$fieldlength.'" '.$readonly.' class="txtBox"></td>
						</tr>
						<tr id="decimaldetails" style="visibility:hidden;">
							<td class="dataLabel" nowrap="nowrap" align="right"><b>'.$mod_strings['LBL_DECIMAL_PLACES'].'</b></td>
							<td align="left"><input type="text" name="fldDecimal" value="'.$decimalvalue.'" '.$readonly.' class="txtBox"></td>
						</tr>
						<tr id="picklist" style="visibility:hidden;">
							<td class="dataLabel" nowrap="nowrap" align="right" valign="top"><b>'.$mod_strings['LBL_PICK_LIST_VALUES'].'</b></td>
							<td align="left" valign="top"><textarea name="fldPickList" rows="10" class="txtBox" '.$readonly.'>'.$fldVal.'</textarea></td>
							<!--td style="padding-left:10px"><img src="themes/Aqua/images/picklist_hint.gif"/></td-->
						</tr>
					</table>
				</td>
			</tr>
			<tr><td style="border-bottom:1px dashed #CCCCCC;" colspan="2">&nbsp;</td></tr>
			<tr>
				<td colspan="2" align="center">
					<input type="submit" name="save" value=" &nbsp; '.$app_strings['LBL_SAVE_BUTTON_LABEL'].' &nbsp; " class="classBtn" />&nbsp;&nbsp;
					<input type="button" name="cancel" value=" '.$app_strings['LBL_CANCEL_BUTTON_LABEL'].' " class="classBtn" onclick="fninvsh(\'orgLay\');" />
				</td>
			</tr>
			<tr><td colspan="2" style="border-top:1px dashed #CCCCCC;">&nbsp;</td></tr>
		</table>
		<input type="hidden" name="fieldType" id="fieldType" value="'.$selectedvalue.'">
	</div>
	</form>';
echo $output;
?>
