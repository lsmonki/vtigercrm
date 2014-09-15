<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

include_once dirname(__FILE__) . '/csrf-magic/csrf-magic.php';

function requestValidate($module, $action) {
	$writeAction = false;
	if (preg_match('/Save/', $action) && ($module == 'Reports' && $action != 'SaveAndRun') ) $writeAction = true;
	else if ($action == "{$module}Ajax" && isset($_REQUEST['record']) && 
		isset($_REQUEST['fldName']) && isset($_REQUEST['fieldValue']) && isset($_REQUEST['ajxaction']) && $_REQUEST['ajxaction'] == 'DETAILVIEW') {
		$writeAction = true;
	}
	if ($writeAction) {
		requestValidateWriteAccess();
	} else {
		requestValidateReadAccess();
	}
}

function requestValidateReadAccess() {
	if (isset($_SERVER['HTTP_REFERER'])) {
		global $site_URL;
		if (stripos($_SERVER['HTTP_REFERER'], $site_URL) !== 0) {
			throw new Exception('Illegal request');
		}
	}
	return true;
}

function requestValidateWriteAccess() {
	if ($_SERVER['REQUEST_METHOD'] != 'POST') throw new Exception('Invalid request');
	requestValidateReadAccess();
	if (!csrf_check(false)) throw new Exception('Unsupported request');
}
