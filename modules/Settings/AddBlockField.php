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

require_once($theme_path.'layout_utils.php');

$tabid=$_REQUEST['tabid'];
$fieldid=$_REQUEST['fieldselect'];
if(isset($_REQUEST['uitype']) && $_REQUEST['uitype'] != '')
	$uitype=$_REQUEST['uitype'];
else
	$uitype=1;
$readonly = '';
$smarty = new vtigerCRM_Smarty;
if($_REQUEST[mode]=='edit')
{
	$mode='edit';
	$customfield_columnname=getCustomFieldData($tabid,$fieldid,'columnname');
	$customfield_typeofdata=getCustomFieldData($tabid,$fieldid,'typeofdata');
	$customfield_fieldlabel=getCustomFieldData($tabid,$fieldid,'fieldlabel');
	
	
	$customfield_typename=getCustomFieldTypeName($uitype);
	$fieldtype_lengthvalue=getFldTypeandLengthValue($customfield_typename,$customfield_typeofdata);
	list($fieldtype,$fieldlength,$decimalvalue)= explode(";",$fieldtype_lengthvalue);
	$readonly = "readonly";

	$selectedvalue = $typeVal[$fieldtype];
}

$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("THEME", $theme);
$smarty->assign("FLD_MODULE", $_REQUEST['fld_module']);

$output = '';

$combo_output = '';



$output .= '<div id="orgLay" style="display:block;" class="layerPopup"><script language="JavaScript" type="text/javascript" src="include/js/customview.js"></script>
	<form action="index.php" method="post" name="addtodb">
	  <input type="hidden" name="module" value="Settings">
	  <input type="hidden" name="fld_module" value="'.$_REQUEST['fld_module'].'">
	  <input type="hidden" name="parenttab" value="Settings">
          <input type="hidden" name="action" value="AddBlockFieldToDB">
	  <input type="hidden" name="blockid" id="blockid" value="'.$_REQUEST[blockid].'">
	   <input type="hidden" name="tabid" id="tabid" value="'.$_REQUEST[tabid].'">
	    <input type="hidden" name="fieldselect" value="'.$_REQUEST[fieldselect].'">
	  <input type="hidden" name="column" value="'.$customfield_columnname.'">
	  <input type="hidden" name="mode" id="cfedit_mode" value="'.$mode.'">
	  <input type="hidden" name="cfcombo" id="selectedfieldtype" value="">

	  
		<table width="100%" border="0" cellpadding="5" cellspacing="0" class="layerHeadingULine">
			<tr>';
			if($mode == 'edit')
				$output .= '<td width="60%" align="left" class="layerPopupHeading">Edit Field</td>';
			else
				$output .= '<td width="95%" align="left" class="layerPopupHeading">'.$mod_strings['LBL_ADD_BLOCK_FIELD'].$_REQUEST[blockname].'</td>';
				
			$output .= '<td width="5%" align="right"><a href="javascript:fninvsh(\'orgLay\');"><img src="'. vtiger_imageurl('close.gif', $theme) .'" border="0"  align="absmiddle" /></a></td>
			</tr>';
			$output .='</table><table border=0 cellspacing=0 cellpadding=0 width=95% align=center> 
							<tr>
								<td class=small >
									<table border=0 celspacing=0 cellpadding=0 width=100% align=center bgcolor=white>
										<tr>';
			if($mode != 'edit')
		    {						
			$output .= '<td><table>
					<tr><td>'.$mod_strings['LBL_SELECT_FIELD_TYPE'].'</td></tr>
					<tr><td>COMBO_OUTPUT_CHANGE</td></tr>
					</table></td>';
			}
			if($mode == 'edit')
			{
			$output .='<td width="50%">
					<table width="100%" border="0" cellpadding="5" cellspacing="0">
						<tr>
							<td class="dataLabel" nowrap="nowrap" align="right" width="30%"><b>'.$mod_strings['LBL_LABEL'].' </b></td>
							<td align="left" width="70%"><input name="fldLabel" id="fldLabel" value="'.$customfield_fieldlabel.'" type="text" class="txtBox"></td>
						</tr>';
					
						switch($uitype)
						{
							case 1:
								$output .= '<tr id="lengthdetails">
									<td class="dataLabel" nowrap="nowrap" align="right"><b>'.$mod_strings['LBL_LENGTH'].'</b></td>
									<td align="left"><input type="text" name="fldLength" value="'.$fieldlength.'" '.$readonly.' class="txtBox"></td>
								</tr>';
								break;
							case 71:
							case 9:
							case 7:
								$output .= '<tr id="lengthdetails">
									<td class="dataLabel" nowrap="nowrap" align="right"><b>'.$mod_strings['LBL_LENGTH'].'</b></td>
									<td align="left"><input type="text" name="fldLength" value="'.$fieldlength.'" '.$readonly.' class="txtBox"></td>
								</tr>';
								$output .= '<tr id="decimaldetails">
									<td class="dataLabel" nowrap="nowrap" align="right"><b>'.$mod_strings['LBL_DECIMAL_PLACES'].'</b></td>
									<td align="left"><input type="text" name="fldDecimal" value="'.$decimalvalue.'" '.$readonly.' class="txtBox"></td>
								</tr>';
								break;
							case 33:
							//case 15:
							//	$output .= '<tr id="picklist">
							//		<td class="dataLabel" nowrap="nowrap" align="right" valign="top"><b>'.$mod_strings['LBL_PICK_LIST_VALUES'].'</b></td>
							//	</tr>';
							//	break;
								
						}
					}else{		
			
						
			$output .='<td width="70%">
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td  align="left" width="100%"><b>'.$app_strings['LBL_ASSIGN_FIELD_DESCRIPTION'].'</b></td></tr>';
					}
				$output .= '	
					</table>
				</td>
			</tr>
			</table>
		</td>
	</tr>
	</table>
	<table border=0 cellspacing=0 cellpadding=5 width=100% class="layerPopupTransport">
			<tr>
				<td align="center">';
				if($mode != 'edit')
				$output .= '<input type="submit" name="save" value=" &nbsp; '.$app_strings['LBL_ASSIGN_BUTTON_LABEL'].' &nbsp; " class="crmButton small save" />';
				else
				$output .= '<input type="submit" name="save" value=" &nbsp; '.$app_strings['LBL_SAVE_BUTTON_LABEL'].'&nbsp; " class="crmButton small save" />';
					$output .= '&nbsp;
					<input type="button" name="cancel" value=" '.$app_strings['LBL_CANCEL_BUTTON_LABEL'].' " class="crmButton small cancel" onclick="fninvsh(\'orgLay\');" />
				</td>
			</tr>
	</table>
		<input type="hidden" name="fieldType" id="fieldType" value="'.$selectedvalue.'">
	</form></div>';


function InStrCount($String,$Find,$CaseSensitive = false) {
    $i=0;
    $x=0;
    while (strlen($String)>=$i) {
     unset($substring);
     if ($CaseSensitive) {
      $Find=strtolower($Find);
      $String=strtolower($String);
     }
     $substring=substr($String,$i,strlen($Find));
     if ($substring==$Find) $x++;
     $i++;
    }
    return $x;
   }

include('modules/'.$_REQUEST['fld_module'].'/language/'.$_SESSION['authenticated_user_language'].'.lang.php');
	
$custom_flag=InStrCount($customfield_columnname,'cf_',true);

if($custom_flag==0)
$output=str_replace('CHANGE_FIELDLABEL',$mod_strings[$customfield_fieldlabel],$output);	
else
$output=str_replace('CHANGE_FIELDLABEL',$customfield_fieldlabel,$output);	
 
$sql="SELECT fieldid,fieldlabel,fieldname FROM vtiger_field WHERE tabid='".$_REQUEST[tabid]."' AND block NOT IN ('".$_REQUEST[blockid]."') AND block NOT IN (SELECT blockid from vtiger_blocks where blocklabel='LBL_RELATED_PRODUCTS') AND displaytype in (1,2,4) ORDER BY fieldlabel ASC"; // added by projjwal on 22-11-2007
$res= $adb->pquery($sql,array());

$combo_output='<select name="field_assignid[]" style="width:250px" size=10 multiple>';// added by projjwal on 22-11-2007
while($row_field = $adb->fetch_array($res))
{
	$combo_output.='<option value="'.$row_field[fieldid].'">'.getTranslatedString($row_field[fieldlabel]).'</option>'; 
}
$combo_output.='</select>';


$output=str_replace('COMBO_OUTPUT_CHANGE',$combo_output,$output);	
 
echo $output;
?>
