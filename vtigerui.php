<?php

/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/


/**
 * Redirect based on the UI state selected. 
 */

$defaultUI = ''; // Use ('') for vtiger5

// Hook to reset the configuration
if (file_exists('vtigerui_override.php')) {
	/**
	 * <?php
	 * global $defaultUI;
	 * $defaultUI = ''; 
	 */
	include_once 'vtigerui_override.php';
}

if (isset($_COOKIE['vtigerui'])) {
	switch ($_COOKIE['vtigerui']) {
		case '6': $ui = 'vtiger6/'; break;
		case '5': $ui = '';			break;
		default: $ui = $defaultUI; break;
	}
} else{
	$ui = $defaultUI;
}

$uri = '';
if (isset($_REQUEST['next'])) {
	// Request redirection to Home (from UsersSetup)
	if (strcasecmp($_REQUEST['next'], 'home') == 0 && empty($ui)) {
		$uri = "?module=Home&action=index";
	}
}

// To overcome browser caching this redirect (could cause trouble to enable switching UI between 5 & 6)
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); header("Cache-Control: no-cache"); header("Pragma: no-cache");

header ("Location: {$ui}index.php{$uri}");