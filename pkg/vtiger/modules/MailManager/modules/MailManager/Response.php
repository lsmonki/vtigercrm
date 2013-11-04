<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
class MailManager_Response extends Vtiger_Response {
	
	public function __construct($isjson=false) {
		if ($isjson) {
			$this->setEmitType(self::$EMIT_JSON);
		} else {
			$this->setEmitType(self::$EMIT_HTML);
		}
	}
	
	public function isJson($flag) {
		if (func_num_args() > 0) parent::setEmitType ($flag ? self::$EMIT_JSON : self::$EMIT_HTML);
		return parent::isJSON();
	}
}