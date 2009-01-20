<?php
/*
 * Created on 25-Jul-08
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
 include('include/database/PearDatabase.php');
 
 global $adb;
 
 $fieldlabel = $_REQUEST['fld_label'];
 $tabid = $_REQUEST['tabid'];
 $fieldselect = $_REQUEST['fld_select'];
 
 $check_query = $adb->pquery('SELECT * FROM vtiger_field WHERE fieldid != ? and fieldlabel = ? and vtiger_field.presence in (0,2)', array($fieldselect,$fieldlabel));
 
 if($adb->num_rows($check_query) < 2 )
 	echo "SUCESS";
 else
	echo "FAILURE"; 
 
?>
