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


$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("FLD_MODULE", $_REQUEST['fld_module']);

$output = '';

$combo_output = '';
$sql="select * from vtiger_field where block='".$_REQUEST[blockid]."' and tabid='".$_REQUEST[tabid]."'";
$res= $adb->query($sql);
while($row_field = $adb->fetch_array($res))
{

 $field_assignid.=$row_field[fieldid].',';
}




$output .= '<div id="orgLay" style="display:block;" class="layerPopup"><script language="JavaScript" type="text/javascript" src="include/js/customview.js"></script>
	<form action="index.php" method="post" name="addtodb">
	  <input type="hidden" name="module" value="Settings">
	  <input type="hidden" name="fld_module" value="'.$_REQUEST['fld_module'].'">
	  <input type="hidden" name="parenttab" value="Settings">
          <input type="hidden" name="action" value="MoveBlockFieldToDB">
	   <input type="hidden" name="tabid" value="'.$_REQUEST[tabid].'">
	    <input type="hidden" name="deleteblockid" value="'.$_REQUEST[blockid].'">
	    <input type="hidden" name="field_assignid" value="'.$field_assignid.'">
	  <input type="hidden" name="cfcombo" id="selectedfieldtype" value="">

	  
		<table width="100%" border="0" cellpadding="5" cellspacing="0" class="layerHeadingULine">
			<tr>';
				$output .= '<td width="95%" align="left" class="layerPopupHeading">'.$mod_strings['LBL_MOVE_BLOCK_FIELD'].$_REQUEST[blockname].'</td>';
				
			$output .= '<td width="5%" align="right"><a href="javascript:fninvsh(\'orgLay\');"><img src="themes/images/close.gif" border="0"  align="absmiddle" /></a></td>
			</tr>';
			$output .='</table><table border=0 cellspacing=0 cellpadding=0 width=95% align=center> 
							<tr>
								<td class=small >
									<table border=0 celspacing=0 cellpadding=0 width=100% align=center bgcolor=white>
										<tr>';
				
		
			$output .='<td width="100%">
					<table width="100%" border="0" cellpadding="5" cellspacing="0">
						<tr>
							<td class="dataLabel" nowrap="nowrap" align="left" width="70%"><b>'.$mod_strings['LBL_MOVE'].' </b></td>';
							//print_r($mod_strings);
include('modules/'.$_REQUEST['fld_module'].'/language/en_us.lang.php');

$combo_output='<select name="blockid" style="width:120px">';
$sql="select * from vtiger_blocks where tabid='".$_REQUEST[tabid]."' and blockid!='".$_REQUEST[blockid]."'";
$res= $adb->query($sql);

while($row_block = $adb->fetch_array($res))
{

 $combo_output.='<option value="'.$row_block[blockid].'">'.$mod_strings[$row_block["blocklabel"]].'</option>';
}
$combo_output.='</select>';
						$output .='	<td align="left" width="30%">'.$combo_output.'</td>
						</tr>';
					
											
			$output .='<td width="70%">
						';
				
				$output .= '	
				</td>
			</tr>
			</table>
		</td>
	</tr>
	</table>
	<table border=0 cellspacing=0 cellpadding=5 width=100% class="layerPopupTransport">
			<tr>
				<td align="center">
					<input type="submit" name="save" value=" &nbsp; '.$app_strings['LBL_ASSIGN_BUTTON_LABEL'].' &nbsp; " class="crmButton small save" />&nbsp;
					<input type="button" name="cancel" value=" '.$app_strings['LBL_CANCEL_BUTTON_LABEL'].' " class="crmButton small cancel" onclick="fninvsh(\'orgLay\');" />
				</td>
			</tr>
	</table>
		<input type="hidden" name="fieldType" id="fieldType" value="'.$selectedvalue.'">
	</form></div>';
echo $output;
?>
