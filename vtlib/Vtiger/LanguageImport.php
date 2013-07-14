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
		$this->_export_tmpdir;
	}

	function getPrefix() {
		return (string)$this->_modulexml->prefix;
	}

	/**
	 * Initialize Import
	 * @access private
	 */
	function initImport($zipfile, $overwrite) {
		$this->__initSchema();

		$name = $this->getModuleNameFromZip($zipfile);
		return $name;
	}

	/**
	 * Import Module from zip file
	 * @param String Zip file name
	 * @param Boolean True for overwriting existing module
	 */
	function import($zipfile, $overwrite=false) {
		$name = $this->initImport($zipfile, $overwrite);

		// Call module import function
		$this->import_Language($zipfile, $name);
	}

	/**
	 * Update Module from zip file
	 * @param Object Instance of Language (to keep Module update API consistent)
	 * @param String Zip file name
	 * @param Boolean True for overwriting existing module
	 */
	function update($instance, $zipfile, $overwrite=true) {
		$this->import($zipfile, $overwrite);
	}

	/**
	 * Import Module
	 * @access private
	 */
	function import_Language($zipfile, $name) {
		$prefix = (string)$this->_modulexml->prefix;
		$label = $this->_modulexml->label;

		if(!$name) {
			self::log("Importing $label [$prefix] ... FAILED");
			return;
		}

		self::log("Importing $label [$prefix] ... STARTED");
		$unzip = new Vtiger_Unzip($zipfile);
		$filelist = $unzip->getList();

		foreach($filelist as $filename=>$fileinfo) {
			if(!$unzip->isdir($filename)) {

				if(strpos($filename, '/') === false) continue;

				$targetdir  = substr($filename, 0, strripos($filename,'/'));
				$targetfile = basename($filename);
				$defaultLangPath = 'languages'; $defaultLanguage = 'en_us';
				$prefixFileName = null;

				$dounzip = false;
				//check and copy modules folder with languages/$prefix/
				if(preg_replace("/modules/", "$defaultLangPath/$defaultLanguage", $targetdir)) {
					$baseDir = preg_replace("/modules/", "$defaultLangPath/$defaultLanguage", $targetdir);
					if(is_dir($baseDir)) {
						$dounzip = true;
						$prefixDir = preg_replace("/modules/", "$defaultLangPath/$prefix", $targetdir);
						$prefixFileName = "$prefixDir/$targetfile";
						@mkdir($prefixDir);
						@chmod($prefixDir, 0777);
					}
				} else if(is_dir($targetdir)) {
					if(stripos($targetfile, "phpmailer.lang-$prefix.php")===0) {

						if(file_exists("$targetdir/phpmailer.lang-en.php")) {
							$dounzip = true;
						}
					} else if(preg_replace("/$prefix/", $defaultLanguage, $targetdir)) {
						if(file_exists($targetdir)) {
							$dounzip = true;
						}
					}
				}

				if($dounzip) {
					if($prefixFileName == null) $prefixFileName = $filename;
					if($unzip->unzip($filename, $prefixFileName) !== false) {
						self::log("Copying file $prefixFileName ... DONE");
					} else {
						self::log("Copying file $prefixFileName ... FAILED");
					}
				} else {
					self::log("Copying file $prefixFileName ... SKIPPED");
				}
			}
		}
		if($unzip) $unzip->close();

		self::register($prefix, $label, $name);

		self::log("Importing $label [$prefix] ... DONE");

		return;
	}
}