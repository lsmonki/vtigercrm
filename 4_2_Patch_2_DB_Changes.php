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

require_once('config.php');
require_once('include/utils.php');
$filename = $root_directory.'vtigerversion.php';

$readhandle = @fopen($filename, "r+");
$migrated_flag = 0;
if($readhandle)
{
	while(!feof($readhandle))
	{
		$buffer = fgets($readhandle, 5200);

	        list($starter, $tmp) = explode(" = ", $buffer);
		if($starter == '$migrated_from_401_to_42')
		{
			$migrated_flag = 1;
		}
	}
	fclose($readhandle);
}

if($migrated_flag != 1)
{

$newbuf = "<?
/*********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * (\"License\"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
 ********************************************************************************/

\$patch_version = '';
\$modified_database = '';
\$vtiger_current_version = '4.2 GA';
\$migrated_from_401_to_42 = 'true';
?>";

	$handle = @fopen($filename, "w");
	if($handle)
	{
		fputs($handle, $newbuf);
		$write_flag = 1;
		fclose($handle);
	}
	else
	{
		echo '<br><br> Please give write permission to the <b>vtigerversion.php </b> present under <b>'.$root_directory.'</b>';
	}
	if($write_flag == 1)
	{
		include($root_directory."vtigerpatch.php");
	}
}
else
{
	echo '<br><br> Database already updated to Patch 2.';
}
?>
