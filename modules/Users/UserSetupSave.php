<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM  License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

require_once('modules/Users/Users.php');
require_once('modules/Users/LoginHistory.php');
require_once('modules/Users/CreateUserPrivilegeFile.php');

global $current_user, $current_language;

//Handling the user preferences
$current_user->setUserPreferences($_REQUEST);

//Handling the System Setup
$isFirstUser = Users_CRMSetup::isFirstUser($current_user);
if ($isFirstUser && isset ($_FILES)) {
	$current_user->uploadOrgLogo($_REQUEST, $_FILES);
	$current_user->updateBaseCurrency($_REQUEST);
	$current_user->updateConfigFile($_REQUEST);
}
Users_CRMSetup::insertEntryIntoCRMSetup($current_user->id);

//Security related entries start
require_once('include/utils/UserInfoUtil.php');

createUserPrivilegesfile($current_user->id);
$current_user = $current_user->retrieveCurrentUserInfoFromFile($current_user->id);

// store the user's theme in the session
if(!empty($current_user->column_fields["theme"])) {
	$authenticated_user_theme = $current_user->column_fields["theme"];
} else {
	$authenticated_user_theme = $default_theme;
}

// store the user's language in the session
if(!empty($current_user->column_fields["language"])) {
	$authenticated_user_language = $current_user->column_fields["language"];
} else {
	$authenticated_user_language = $default_language;
}

$_SESSION['vtiger_authenticated_user_theme'] = $authenticated_user_theme;
$_SESSION['authenticated_user_language'] = $authenticated_user_language;

$log->debug("authenticated_user_theme is $authenticated_user_theme");
$log->debug("authenticated_user_language is $authenticated_user_language");
$log->debug("authenticated_user_id is ". $current_user->id);
$log->debug("app_unique_key is $application_unique_key");

$current_language = $authenticated_user_language;

header("Location: index.php?action=index&module=Home");

?>
