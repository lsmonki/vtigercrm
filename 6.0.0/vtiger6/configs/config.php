<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

$THIS_DIR = dirname(__FILE__);
include_once $THIS_DIR . '/../../config.php';

// TODO Better to move this check into config.php
if (file_exists($THIS_DIR . '/../../config_override.php')) {
	include_once $THIS_DIR . '/../../config_override.php';
}

class VtigerConfig {
	
	static function get($key, $defvalue='') {
		if (self::has($key)) {
			global $$key;
			return $$key;
		}
		return $defvalue;
	}
	
	static function has($key) {
		global $$key;
		return (isset($$key));
	}
}