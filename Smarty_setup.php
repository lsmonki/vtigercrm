<?php
/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
   * ("License"); You may not use this file except in compliance with the License
   * The Original Code is:  vtiger CRM Open Source
   * The Initial Developer of the Original Code is vtiger.
   * Portions created by vtiger are Copyright (C) vtiger.
   * All Rights Reserved.
  *
 ********************************************************************************/

require('Smarty/libs/Smarty.class.php');
class vtigerCRM_Smarty extends Smarty{
	
	/**This function sets the smarty directory path for the member variables	
	*/
	function vtigerCRM_Smarty()
	{
		$this->Smarty();
		$this->template_dir = 'Smarty/templates';
		$this->compile_dir = 'Smarty/templates_c';
		$this->config_dir = 'Smarty/configs';
		$this->cache_dir = 'Smarty/cache';

		//$this->caching = true;
	        //$this->assign('app_name', 'Login');
	}
}

?>
