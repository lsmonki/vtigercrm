<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM  License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

require_once('Smarty_setup.php');
require_once('modules/Users/Users.php');
require_once('include/utils/UserInfoUtil.php');

global $current_user;

$userSetupStatus = Users_CRMSetup::getUserSetupStatus($current_user->id);
if ($userSetupStatus) {
	//Security related entries end
	$isFirstUser = Users_CRMSetup::isFirstUser($current_user);

	$smarty = new vtigerCRM_Smarty();
	$smarty->assign('MODULE', 'Users');
	$smarty->assign('IS_FIRST_USER', $isFirstUser);
	$smarty->assign('USER_NAME', vtlib_purify($_REQUEST['user_name']));
	$smarty->assign('PASSWORD', vtlib_purify($_REQUEST['user_password']));
	$smarty->assign('USER', $current_user->user_name);
	if ($isFirstUser) {
		$currencies = getCurrenciesList();
		$defaultCurrencyKey = 'USA, Dollars';
		$defaultCurrencyValue = $currencies[$defaultCurrencyKey];
		unset($currencies[$defaultCurrencyKey]);
		$defaultcurrency[$defaultCurrencyKey] = $defaultCurrencyValue;
		
		$currenciesList = array_merge($defaultcurrency, $currencies);
		$smarty->assign('CURRENCIES', $currenciesList);
	}
	$smarty->assign('TIME_ZONES', getTimeZonesList());
	$smarty->assign('LANGUAGES', getLanguagesList());
	$smarty->assign('POINTER_IMAGE', 'test/logo/pointer.png');
	$smarty->display('UserSetup.tpl');
} else {
	header("Location: vtigerui.php?next=home&_" . microtime(true)); // _ added to overcome cache.
}

?>
