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
require_once('include/utils/utils.php');
require_once('modules/HelpDesk/HelpDesk.php');

$idlist= $_REQUEST['massedit_recordids'];
$viewid = $_REQUEST['viewname'];
$return_module = $_REQUEST['massedit_module'];
$return_action = 'index';

global $rstart;
//Added to fix 4600
$url = getBasic_Advance_SearchURL();

if(isset($_REQUEST['start']) && $_REQUEST['start']!=''){
	$rstart = "&start=".$_REQUEST['start'];
}

if(isset($idlist)) {
	$recordids = explode(';', $idlist);
	for($index = 0; $index < count($recordids); ++$index) {
		$recordid = $recordids[$index];
		if($recordid == '') continue;

		// Save each module record with update value.
		$focus = new HelpDesk();
		$focus->retrieve_entity_info($recordid, $return_module);
		$focus->mode = 'edit';		
		$focus->id = $recordid;		
		foreach($focus->column_fields as $fieldname => $val)
		{    	
			if(isset($_REQUEST[$fieldname."_mass_edit_check"]))
			{
				if(is_array($_REQUEST[$fieldname]))
					$value = $_REQUEST[$fieldname];
				else
					$value = trim($_REQUEST[$fieldname]);	
				$log->info("the value is ".$value);
				$focus->column_fields[$fieldname] = $value;
			}
			else{
				$focus->column_fields[$fieldname] = decode_html($focus->column_fields[$fieldname]);
			}
		}
   		$focus->save($return_module);
	}
}
print_r("<script type='text/javascript'>document.location.href='index.php?module=$return_module&action=$return_action"."$rstart';</script>");
?>
