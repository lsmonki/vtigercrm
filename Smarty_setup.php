<?php
require('Smarty/libs/Smarty.class.php');
class vtigerCRM_Smarty extends Smarty{
	
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

