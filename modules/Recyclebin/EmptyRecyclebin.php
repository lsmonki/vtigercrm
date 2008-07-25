<?php
/*********************************************************************************
 *** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 ** ("License"); You may not use this file except in compliance with the License
 ** The Original Code is:  vtiger CRM Open Source
 ** The Initial Developer of the Original Code is vtiger.
 ** Portions created by vtiger are Copyright (C) vtiger.
 ** All Rights Reserved.
 **
 *********************************************************************************/

global $adb;

$adb->query("delete from vtiger_crmentity where deleted = 1");
//TODO Related records for the module records deleted from vtiger_crmentity has to be deleted. 
//It needs lookup in the related tables and needs to be removed if doesn't have a reference record in vtiger_crmentity
 
$adb->query("delete from vtiger_relatedlists_rb");

if(isset($_REQUEST['parenttab']) && $_REQUEST['parenttab'] != '')
	$parenttab = $_REQUEST['parenttab'];
else 
	$parenttab = 'Tools';

header("Location: index.php?module=Recyclebin&action=RecyclebinAjax&file=index&parenttab=$parenttab&mode=ajax");
?>

