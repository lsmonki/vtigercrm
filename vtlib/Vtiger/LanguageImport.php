<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
include_once('vtlib/Vtiger/LanguageExport.php');

/**
 * Provides API to import language into vtiger CRM
 * @package vtlib
 */
class Vtiger_LanguageImport extends Vtiger_LanguageExport {

	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}

	function getPrefix() {
		return $this->_modulexml->name;
	}

	/**
	 * Initialize Import
	 * @access private
	 */
	function initImport($zipfile, $overwrite) {
		$this->__initSchema();

		$name = $this->getModuleNameFromZip($zipfile);
	}

	/**
	 * Import Module from zip file
	 * @param String Zip file name
	 * @param Boolean True for overwriting existing module
	 */
	function import($zipfile, $overwrite=false) {
		$this->initImport($zipfile, $overwrite);
	
		// Call module import function
		$this->import_Language($zipfile);
	}

	/**
	 * Import Module
	 * @access private
	 */
	function import_Language($zipfile) {
		$name = $this->_modulexml->name;
		$prefix = $this->_modulexml->prefix;
		$label = $this->_modulexml->label;

		self::log("Importing $label [$prefix] ... STARTED");
		$unzip = new Vtiger_Unzip($zipfile);
		$filelist = $unzip->getList();

		foreach($filelist as $filename=>$fileinfo) {
			if(!$unzip->isdir($filename)) {
				
				if(strpos($filename, '/') === false) continue;

				$targetdir  = substr($filename, 0, strripos($filename,'/'));
				$targetfile = basename($filename);

				$dounzip = false;
				if(is_dir($targetdir)) {
					if(preg_match("/$prefix.lang.php/", $targetfile)) {
						if(file_exists("$targetdir/en_us.lang.php")) {
							$dounzip = true;
						}
					}
				}

				if($dounzip) {					
					$unzip->unzip($filename, $filename);
					self::log("Copying file $filename ... DONE");
				} else {
					self::log("Copying file $filename ... SKIPPED");
				}
			}
		}
		if($unzip) $unzip->close();

		self::register($prefix, $label, $name);
		
		self::log("Importing $label [$prefix] ... DONE");

		return;
	}
}			
?>
