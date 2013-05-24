<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

/**
 * Abstract Controller Class
 */
abstract class Vtiger_Controller {

	function __construct() { }

	function loginRequired() {
		return true;
	}

	abstract function getViewer(Vtiger_Request $request);
	abstract function process (Vtiger_Request $request);

	function preProcess(Vtiger_Request $request) {}
	function postProcess(Vtiger_Request $request) {}

	// Control the exposure of methods to be invoked from client (kind-of RPC)
	protected $exposedMethods = array();

	/**
	 * Function that will expose methods for external access
	 * @param <String> $name - method name
	 */
	protected function exposeMethod($name) {
		if(!in_array($name, $this->exposedMethods)) {
			$this->exposedMethods[] = $name;
		}
	}

	/**
	 * Function checks if the method is exposed for client usage
	 * @param string $name - method name
	 * @return boolean
	 */
	function isMethodExposed($name) {
		if(in_array($name, $this->exposedMethods)) {
			return true;
		}
		return false;
	}

	/**
	 * Function invokes exposed methods for this class
	 * @param string $name - method name
	 * @param Vtiger_Request $request
	 * @throws Exception
	 */
	function invokeExposedMethod() {
		$parameters = func_get_args();
		$name = array_shift($parameters);
		if (!empty($name) && $this->isMethodExposed($name)) {
			return call_user_func_array(array($this, $name), $parameters);
		}
		throw new Exception(vtranslate('LBL_NOT_ACCESSIBLE'));
	}
}

/**
 * Abstract Action Controller Class
 */
abstract class Vtiger_Action_Controller extends Vtiger_Controller {
	function __construct() {
		parent::__construct();
	}

	function getViewer(Vtiger_Request $request) {
		throw new AppException ('Action - implement getViewer - JSONViewer');
	}

	function preProcess(Vtiger_Request $request) {
		return true;
	}

	protected function preProcessDisplay(Vtiger_Request $request) {
	}

	protected function preProcessTplName() {
		return false;
	}

	//TODO: need to revisit on this as we are not sure if this is helpful
	/*function preProcessParentTplName(Vtiger_Request $request) {
		return false;
	}*/

	function postProcess(Vtiger_Request $request) {
		return true;
	}
}

/**
 * Abstract View Controller Class
 */
abstract class Vtiger_View_Controller extends Vtiger_Action_Controller {

	function __construct() {
		parent::__construct();
	}

	function getViewer(Vtiger_Request $request) {
		if(!$this->viewer) {
			global $vtiger_current_version;
			$viewer = new Vtiger_Viewer();
			$viewer->assign('APPTITLE', getTranslatedString('APPTITLE'));
			$viewer->assign('VTIGER_VERSION', $vtiger_current_version);
			$this->viewer = $viewer;
		}
		return $this->viewer;
	}

	function getPageTitle(Vtiger_Request $request) {
		return vtranslate($request->getModule(), $request->get('module'));
	}

	function preProcess(Vtiger_Request $request, $display=true) {
		$viewer = $this->getViewer($request);
		$viewer->assign('PAGETITLE', $this->getPageTitle($request));
		$viewer->assign('SCRIPTS',$this->getHeaderScripts($request));
		$viewer->assign('STYLES',$this->getHeaderCss($request));
		$viewer->assign('SKIN_PATH', Vtiger_Theme::getCurrentUserThemePath());
		$viewer->assign('LANGUAGE_STRINGS', $this->getJSLanguageStrings($request));
		if($display) {
			$this->preProcessDisplay($request);
		}
	}

	protected function preProcessTplName(Vtiger_Request $request) {
		return 'Header.tpl';
	}

	//Note : To get the right hook for immediate parent in PHP,
	// specially in case of deep hierarchy
	//TODO: Need to revisit this.
	/*function preProcessParentTplName(Vtiger_Request $request) {
		return parent::preProcessTplName($request);
	}*/

	protected function preProcessDisplay(Vtiger_Request $request) {
		$viewer = $this->getViewer($request);
		$displayed = $viewer->view($this->preProcessTplName($request), $request->getModule());
		/*if(!$displayed) {
			$tplName = $this->preProcessParentTplName($request);
			if($tplName) {
				$viewer->view($tplName, $request->getModule());
			}
		}*/
	}


	function postProcess(Vtiger_Request $request) {
		$viewer = $this->getViewer($request);
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$viewer->assign('ACTIVITY_REMINDER', $currentUser->getCurrentUserActivityReminderInSeconds());
		$viewer->view('Footer.tpl');
	}

	/**
	 * Retrieves headers scripts that need to loaded in the page
	 * @param Vtiger_Request $request - request model
	 * @return <array> - array of Vtiger_JsScript_Model
	 */
	function getHeaderScripts(Vtiger_Request $request){
		return array();
	}

	function checkAndConvertJsScripts($jsFileNames) {
		$fileExtension = 'js';

		$jsScriptInstances = array();
		foreach($jsFileNames as $jsFileName) {
			// TODO Handle absolute inclusions (~/...) like in checkAndConvertCssStyles
			$completeFilePath = Vtiger_Loader::resolveNameToPath($jsFileName, $fileExtension);

			if(file_exists($completeFilePath)) {
				if (strpos($jsFileName, '~') === 0) {
					$filePath = ltrim(ltrim($jsFileName, '~'), '/');
					// if ~~ (reference is outside vtiger6 folder)
					if (substr_count($jsFileName, "~") == 2) {
						$filePath = "../" . $filePath;
					}
				} else {
					$filePath = str_replace('.','/', $jsFileName) . '.'.$fileExtension;
				}

				$jsScript = new Vtiger_JsScript_Model();
				$jsScriptInstances[$jsFileName] = $jsScript->set('src', $filePath);
			} else {
				$fallBackFilePath = Vtiger_Loader::resolveNameToPath(Vtiger_JavaScript::getBaseJavaScriptPath().'/'.$jsFileName, 'js');
				if(file_exists($fallBackFilePath)) {
					$filePath = str_replace('.','/', $jsFileName) . '.js';
					$jsScript = new Vtiger_JsScript_Model();
					$jsScriptInstances[$jsFileName] = $jsScript->set('src', Vtiger_JavaScript::getFilePath($filePath));
				}
			}
		}
		return $jsScriptInstances;
	}

	function checkAndConvertCssStyles($cssFileNames, $fileExtension='css') {
		$cssStyleInstances = array();
		foreach($cssFileNames as $cssFileName) {
			$completeFilePath = Vtiger_Loader::resolveNameToPath($cssFileName, $fileExtension);
			$filePath = NULL;
			if(file_exists($completeFilePath)) {
				if (strpos($cssFileName, '~') === 0) {
					$filePath = ltrim(ltrim($cssFileName, '~'), '/');
					// if ~~ (reference is outside vtiger6 folder)
					if (substr_count($cssFileName, "~") == 2) {
						$filePath = "../" . $filePath;
					}
				} else {
					$filePath = str_replace('.','/', $cssFileName) . '.'.$fileExtension;
					$filePath = Vtiger_Theme::getStylePath($filePath);
				}
				$cssScriptModel = new Vtiger_CssScript_Model();
				$cssStyleInstances[] = $cssScriptModel->set('href', $filePath);
			}
		}
		return $cssStyleInstances;
	}

	/**
	 * Retrieves css styles that need to loaded in the page
	 * @param Vtiger_Request $request - request model
	 * @return <array> - array of Vtiger_CssScript_Model
	 */
	function getHeaderCss(Vtiger_Request $request){
		return array();
	}

	/**
	 * Function returns the Client side language string
	 * @param Vtiger_Request $request
	 */
	function getJSLanguageStrings(Vtiger_Request $request) {
		$moduleName = $request->getModule(false);
		return Vtiger_Language_Handler::export($moduleName, 'jsLanguageStrings');
	}
}