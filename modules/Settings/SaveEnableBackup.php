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

if(isset($_REQUEST['enable_backup']) && $_REQUEST['enable_backup'] != '')
{
	global $root_directory;
	$filename = $root_directory.'user_privileges/enable_backup.php';

	$readhandle = @fopen($filename, "r+");

	if($readhandle)
	{
		$buffer = '';
		$new_buffer = '';
		while(!feof($readhandle))
		{
			$buffer = fgets($readhandle, 5200);
			list($starter, $tmp) = explode(" = ", $buffer);

			if($starter == '$enable_backup' && stristr($tmp,'false'))
			{
				$new_buffer .= "\$enable_backup = 'true';\n";
			}
			elseif($starter == '$enable_backup' && stristr($tmp,'true'))
			{
				$new_buffer .= "\$enable_backup = 'false';\n";
			}
			else
				$new_buffer .= $buffer;
		}
		fclose($readhandle);
	}

	$handle = fopen($filename, "w");
	fputs($handle, $new_buffer);
	fclose($handle);
}
elseif(isset($_REQUEST['GetBackupDetail']) && $_REQUEST['GetBackupDetail'] != '')
{
	require_once("include/database/PearDatabase.php");
	global $mod_strings,$adb;

	$GetBackup = $adb->pquery("select * from vtiger_systems where server_type = ?", array('backup'));
	$BackRowsCheck = $adb->num_rows($GetBackup);

	if($BackRowsCheck > 0)
		echo "SUCESS";
	else
		echo "FAILURE";

}
?>
