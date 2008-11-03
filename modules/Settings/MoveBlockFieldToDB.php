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

 $fldmodule=$_REQUEST['fld_module'];
 $fldType= $_REQUEST['fieldType'];
 $parenttab=$_REQUEST['parenttab'];
 $tabid = getTabid($fldmodule);
//print_r($_REQUEST[field_assignid]);
//exit;
if(isset($_REQUEST[field_assignid]))
{
	//to get the sequence of the field after which the new field will add
	$sql_seq="select * from vtiger_field where tabid=? and block=? order by sequence desc limit 0,1";
	$res_seq= $adb->pquery($sql_seq, array($_REQUEST[tabid],$_REQUEST[blockid]));
    $row_seq=$adb->fetch_array($res_seq);
	$fld_sequence=$row_seq[sequence];
	$newfld_sequence=$fld_sequence+1;
	$fieldselect=$_REQUEST[fieldselect];
	//end
	//print_r($_REQUEST[field_assignid]);
	$field_assignid=explode(',',$_REQUEST[field_assignid]);
	foreach($field_assignid as $field_id)
	{
		if($field_id!='')
		{
			$sql="update vtiger_field set block=?,sequence=? where fieldid=?";	
			$adb->pquery($sql, array($_REQUEST[blockid],$newfld_sequence,$field_id));
		 	$newfld_sequence++;
		}//check if blank
	}
}

$sql="delete from vtiger_blocks where blockid=?";
$adb->pquery($sql, array($_REQUEST[deleteblockid]));

header("Location:index.php?module=Settings&action=LayoutBlockList&fld_module=".$fldmodule."&parenttab=".$parenttab);
?>
