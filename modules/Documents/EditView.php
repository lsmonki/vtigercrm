<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

require_once 'modules/Vtiger/EditView.php';

if($_REQUEST['upload_error'] == true) {
	echo '<br><b><font color="red"> '.$mod_strings['FILE_HAS_NO_DATA'].'.</font></b><br>';
}

if($focus->mode != 'edit') {
	if(isset($_REQUEST['parent_id']) && isset($_REQUEST['return_module'])) {
		$owner = getRecordOwnerId($_REQUEST['parent_id']);
		if(isset($owner['Users']) && $owner['Users'] != '') {
			$permitted_users = get_user_array('true', 'Active',$current_user->id);
			if(!in_array($owner['Users'],$permitted_users)){
				$owner['Users'] = $current_user->id;
			}
			$focus->column_fields['assigntype'] = 'U';
			$focus->column_fields['assigned_user_id'] = $owner['Users'];
		} elseif(isset($owner['Groups']) && $owner['Groups'] != '') {
			$focus->column_fields['assigntype'] = 'T';
			$focus->column_fields['assigned_user_id'] = $owner['Groups'];
		} 
	}   
}

$dbQuery="select filename from vtiger_notes where notesid = ?";
$result=$adb->pquery($dbQuery,array($focus->id));
$filename=$adb->query_result($result,0,'filename');
if(is_null($filename) || $filename == '') {
	$smarty->assign("FILE_EXIST","no");
} else  {
	$smarty->assign("FILE_NAME",$filename);
	$smarty->assign("FILE_EXIST","yes");
}

$USE_RTE = vt_hasRTE();
if(getFieldVisibilityPermission('Documents',$current_user->id,'notecontent') != '0') {
	$USE_RTE = false;
}
$smarty->assign("USE_RTE",$USE_RTE);

if($focus->mode == 'edit') {
    $smarty->assign("MODE", $focus->mode);
} else {
	$smarty->assign("MODE",'create');
}

if (isset($_REQUEST['fileid'])) {
	$smarty->assign("FILEID", vtlib_purify($_REQUEST['fileid']));
}

if(empty($focus->filename)) {
	$smarty->assign("FILENAME_TEXT", "");
	$smarty->assign("FILENAME", "");
} else {
	$smarty->assign("FILENAME_TEXT", "(".$focus->filename.")");
	$smarty->assign("FILENAME", $focus->filename);
}

if($focus->mode == 'edit')
	$smarty->display("salesEditView.tpl");
else
	$smarty->display("CreateView.tpl");
?>