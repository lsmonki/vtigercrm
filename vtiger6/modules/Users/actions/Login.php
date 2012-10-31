<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

class Users_Login_Action extends Vtiger_Action_Controller {

	function loginRequired() {
		return false;
	}

	function process(Vtiger_Request $request) {
		$u = $request->get('username');
		$p = $request->get('password');

		$user = CRMEntity::getInstance('Users');
		$user->column_fields['user_name'] = $u;

		if ($user->doLogin($p)) {
			$userid = $user->retrieve_user_id($u);
			Vtiger_Session::set('AUTHUSERID', $userid);
			
			// For Backward compatability
			// TODO Remove when switch-to-old look is not needed
			$_SESSION['authenticated_user_id'] = $userid;
			$_SESSION['app_unique_key'] = vglobal('application_unique_key');
			$_SESSION['authenticated_user_language'] = vglobal('default_language');
			// End
			
			header ('Location: index.php');
		} else {
			header ('Location: index.php?module=Users&parent=Settings&view=Login&error=1');
		}

	}
}