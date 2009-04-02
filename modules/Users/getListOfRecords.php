<?php
/*********************************************************************************
** File content added/modified by SAKTI on 4th Feb, 2008 
 * This file is responsible for updating response for contact/leads/accounts.
 * This file is used as AJAX backend file for campaigns module
*
 ********************************************************************************/
session_start();
require_once('include/CustomFieldUtil.php');
require_once('Smarty_setup.php');
require_once('include/database/PearDatabase.php');


global $mod_strings,$app_strings,$app_list_strings,$theme,$adb,$current_user;
global $list_max_entries_per_page;

$theme_path="themes/".$theme."/";

require_once($theme_path.'layout_utils.php');

$iCurRecord = $_REQUEST['CurRecordId'];
$sModule = $_REQUEST['CurModule'];

checkFileAccess("modules/$sModule/$sModule.php");
require_once("modules/$sModule/$sModule.php");

$foc_obj = new $sModule();

$query = $adb->pquery("SELECT tablename,entityidfield, fieldname from vtiger_entityname WHERE modulename = ?",array($sModule));
$table_name = $adb->query_result($query,0,'tablename');
$field_name = $adb->query_result($query,0,'fieldname');
$id_field = $adb->query_result($query,0,'entityidfield');
$fieldname = split(",",$field_name);
$fields_array = array($sModule=>$fieldname);
$id_array = array($sModule=>$id_field);
$tables_array = array($sModule=>$table_name);

if(isset($_SESSION['listEntyKeymod_'.$iCurRecord]))
{
	$split_temp=explode(":",$_SESSION['listEntyKeymod_'.$iCurRecord]);
	if($split_temp[0] == $sModule)
	{	
		$ar_allist=explode(",",$split_temp[1]);
		$iMax = count($ar_allist);
	}
}
else
	$iMax = 0;

$output = '<table width="100%" border="0" cellpadding="5" cellspacing="0" class="layerHeadingULine">
			<tr><td width="60%" align="left" style="font-size:12px;font-weight:bold;">Jump to '.$app_strings[$sModule].':</td>
			<td width="5%" align="right"><a href="javascript:fninvsh(\'lstRecordLayout\');"><img src="'. vtiger_imageurl('close.gif', $theme).'" border="0"  align="absmiddle" /></a></td>
			</tr>
			</table><table border=0 cellspacing=0 cellpadding=0 width=100% align=center> 
							<tr>
								<td class=small >
									<table border=0 celspacing=0 cellpadding=0 width=100% align=center >
										<tr><td>';
										
if($iMax > 13)
	$output .= '<div style="height:270px;overflow-y:scroll;">';
else
	$output .= '<div style="height:250px;">';
	
$output .= '<table cellpadding="2">';				
	
if(isset($_SESSION['listEntyKeymod_'.$iCurRecord]))
{
	$split_temp=explode(":",$_SESSION['listEntyKeymod_'.$iCurRecord]);
	
	if($split_temp[0] == $sModule)
	{	
		$ar_allist=explode(",",$split_temp[1]);

		if(count($ar_allist) <= $list_max_entries_per_page){
			$start = 0;
			$end = count($ar_allist);
		}
		else{
			for($i=0;$i<count($ar_allist);$i++){
				if($ar_allist[$i]==$iCurRecord){
					$mid = $list_max_entries_per_page/2; 
					if($i > $mid){
						$start = $i-$mid;
						if(($i+$mid) <= count($ar_allist)){
							$end = $i+$mid;
							break;
						}else{
							$end = count($ar_allist);
							break;
						}
					}
					else
					{
						$start = 0;
						$end = $i+$mid;
					}
				}
			}
		}
		for($listi=$start;$listi<$end;$listi++)
		{
			$field_value = '';
			$field_query = $adb->pquery("SELECT * from ".$tables_array[$sModule]." WHERE ".$id_array[$sModule]." = ".$ar_allist[$listi],array());
			for($index = 0; $index<count($fieldname);$index++){				
				$checkForFieldAccess = $fieldname[$index];
				
				// Handling case where fieldname in vtiger_entityname mismatches fieldname in vtiger_field 				
				if($sModule == 'HelpDesk' && $checkForFieldAccess == 'title') {
					$checkForFieldAccess = 'ticket_title';	
				} else if($sModule == 'Documents' && $checkForFieldAccess == 'title') {
					$checkForFieldAccess = 'notes_title';	
				}
				// END
				
				if(getFieldVisibilityPermission($sModule,$current_user->id, $checkForFieldAccess) == '0'){
					$field_value .= " ".$adb->query_result($field_query,0,$fieldname[$index]);
				}
			}
			if(strlen($field_value)>50)
				$field_value=substr($field_value,0,50)."...";

			if($ar_allist[$listi]==$iCurRecord)
				$output .= '<tr><td style="text-align:left;font-weight:bold;">'.$field_value.'</td></tr>';
			else
				$output .= '<tr><td style="text-align:left;"><a href="index.php?module='.$sModule.'&action=DetailView&parenttab='.$_REQUEST['CurParentTab'].'&record='.$ar_allist[$listi].'">'.$field_value.'</a></td></tr>';
		}
		$output .= '</table>';
	}
}

$output .= '</div></td></tr></table></td></tr></table>';
	
echo $output;
?>
