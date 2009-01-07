<?PHP

/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
   * ("License"); You may not use this file except in compliance with the License
   * The Original Code is:  vtiger CRM Open Source
   * The Initial Developer of the Original Code is vtiger.
   * Portions created by vtiger are Copyright (C) vtiger.
   * All Rights Reserved.
  *
 ********************************************************************************/

require_once('include/utils/utils.php');
require_once('include/database/PearDatabase.php');

		
class Attachfile extends VTEventHandler {
	public function handleEvent($handlerType, $entityData){
	global $log, $adb;
  	
	if($handlerType == 'vtiger.entity.aftersave') {  
		$moduleName = $entityData->getModuleName();
	  	$columnvalues = $entityData->getData();
	  	$id = $entityData->getId();
	  	if($moduleName == 'Documents') {
	 		$filename = $columnvalues['filename'];
	 		$filesize = $columnvalues['filesize'];
	 		$filetype = $columnvalues['filetype'];
	 		$filelocationtype = $columnvalues['filelocationtype'];
	 		if (isset($filename) && $filename != '' && $filename != $entityData->old_filename) {
		 		$query = "Update vtiger_notes set filename = ? ,filesize = ?, filetype = ? , filelocationtype = ? where notesid = ?";
		 		$re=$adb->pquery($query,array($filename,$filesize,$filetype,$filelocationtype,$id));
		 	}	 		
		}
	}
   
   }
  }
 


?>
