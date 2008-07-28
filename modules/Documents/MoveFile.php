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

require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
global $current_user;
if($current_user->is_admin != 'on')
{
	echo 'NOT_PERMITTED';
	die;	
}
else
{
	$new_folderid = $_REQUEST['folderid'];
	$old_folderid = $_REQUEST['from_folderid'];

	if(isset($_REQUEST['idlist']) && $_REQUEST['idlist']!= '')
	{
		$id_array = Array();
		$id_array = explode(';',$_REQUEST['idlist']);
		for($i = 0;$i < count($id_array)-1;$i++)
		{
			ChangeFolder($id_array[$i],$new_folderid,$old_folderid);	
		}
		header("Location: index.php?action=DocumentsAjax&file=ListView&mode=ajax&module=Documents");
	}
}

/** To Change the Documents to another folder
  * @param $recordid -- The file id
  * @param $new_folderid -- The folderid to which the file to be moved
  * @returns nothing 
 */
function ChangeFolder($recordid,$new_folderid,$old_folderid)
{
	global $adb;
	$filelocationqry = "select concat('_',folderid,'_') as folderid,concat(filepath,notesid,'_',folderid,'_',filename) as filepath from vtiger_notes where notesid in (".$recordid.") and filelocationtype='I'";
	$result = $adb->pquery($filelocationqry,array());
	$path = $adb->query_result($result,0,'filepath');
	$folderid = $adb->query_result($result,0,'folderid');
	$replacefolderid = $new_folderid;
	$new_filepath = str_replace($folderid,'_'.$replacefolderid.'_',$path);	
	@rename($path,$new_filepath);
	$sql="update vtiger_notes set folderid=".$replacefolderid." where notesid in (".$recordid.")";
	$res=$adb->pquery($sql,array());
}
?>
