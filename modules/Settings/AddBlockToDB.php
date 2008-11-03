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
global $current_language;

require_once('include/database/PearDatabase.php');
require_once('modules/'.$_REQUEST['fld_module'].'/language/'.$current_language.'.lang.php');
global $mod_strings;
 $fldmodule=$_REQUEST['fld_module'];
 $fldType= $_REQUEST['fieldType'];
 $parenttab=$_REQUEST['parenttab'];
 $mode=$_REQUEST['mode'];
 $blocklabel = trim($_REQUEST[blocklabel]);

 $tabid = getTabid($fldmodule);
	$flag = 0;
	$dup_check_query = $adb->pquery("SELECT blocklabel from vtiger_blocks WHERE tabid = ?",array($tabid));	
	for($i=0;$i<$adb->num_rows($dup_check_query);$i++){
		$blklbl = $adb->query_result($dup_check_query,$i,'blocklabel'); 
		if($mod_strings[$blklbl] == $blocklabel){
			$flag = 1;
			break;
		}
	}
	if($flag!=1){
		    $sql_seq="select sequence from vtiger_blocks where blockid=?";
			$res_seq= $adb->pquery($sql_seq, array($_REQUEST[blockselect]));
		    $row_seq=$adb->fetch_array($res_seq);
			$fld_sequence=$row_seq[sequence];
			$newfld_sequence=$fld_sequence+1;
			$fieldselect=$_REQUEST[fieldselect];
			
			$sql_up="update vtiger_blocks set sequence=sequence+1 where tabid=? and sequence > ?";
			$adb->pquery($sql_up, array($tabid,$fld_sequence));
			
			$sql='select max(blockid) as max_id from vtiger_blocks';
			$res=$adb->query($sql);
			$row=$adb->fetch_array($res);
			$max_blockid=$row['max_id']+1;
	
			$sql="INSERT INTO vtiger_blocks (tabid, blockid, sequence, blocklabel) values (?,?,?,?)";	
			$params = array($tabid,$max_blockid,$newfld_sequence,$_REQUEST[blocklabel]);
			$adb->pquery($sql,$params);
	}
	else
		$duplicate='yes';
  
header("Location:index.php?module=Settings&action=LayoutBlockList&fld_module=".$fldmodule."&duplicate=".$duplicate."&parenttab=".$parenttab);
?>
