<?php
	require 'include.inc';
	class VTFieldFormulasEventHandler extends VTEventHandler{
		function handleEvent($name, $data){
			global $adb;
			$moduleName = $data->getModuleName();
			$mem = new VTModuleExpressionsManager($adb);
			$me = $mem->retrieve($moduleName);
			$me->update($data);
		}
	}
?>