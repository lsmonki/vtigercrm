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
 * Class to handler language translations
 */

class Vtiger_Language_Handler {

	//Contains module language translations
	protected static $languageContainer;

	/**
	 * Functions that gets translated string
	 * @param <String> $key - string which need to be translated
	 * @param <String> $module - module scope in which the translation need to be check
	 * @return <String> - translated string
	 */
	public static function getTranslatedString($key,$module=''){
		$moduleStrings = array();

		$module = str_replace(':', '.', $module);
		$moduleStrings = self::getModuleStringsFromFile($module);
		if(!empty($moduleStrings['languageStrings'][$key])) {
			return $moduleStrings['languageStrings'][$key];
		}
		// Lookup for the translation in base module, in case of sub modules, before ending up with common strings
		if(strpos($module, '.') > 0) {
			$baseModule = substr($module, 0, strpos($module, ':'));
			if($baseModule == 'Settings') {
				$baseModule = 'Settings.Vtiger';
			}
			$moduleStrings = self::getModuleStringsFromFile($baseModule);
			if(!empty($moduleStrings['languageStrings'][$key])) {
				return $moduleStrings['languageStrings'][$key];
			}
		}

		$commonStrings = self::getModuleStringsFromFile();
		if(!empty($commonStrings['languageStrings'][$key]))
			return $commonStrings['languageStrings'][$key];

		return $key;
	}

	/**
	 * Functions that gets translated string for Client side
	 * @param <String> $key - string which need to be translated
	 * @param <String> $module - module scope in which the translation need to be check
	 * @return <String> - translated string
	 */
	public static function getJSTranslatedString($key, $module=''){
		$moduleStrings = array();

		$module = str_replace(':', '.', $module);
		$moduleStrings = self::getModuleStringsFromFile($module);
		if(!empty($moduleStrings['jsLanguageStrings'][$key])) {
			return $moduleStrings['jsLanguageStrings'][$key];
		}
		// Lookup for the translation in base module, in case of sub modules, before ending up with common strings
		if(strpos($module, '.') > 0) {
			$baseModule = substr($module, 0, strpos($module, '.'));
			if($baseModule == 'Settings') {
				$baseModule = 'Settings.Vtiger';
			}
			$moduleStrings = self::getModuleStringsFromFile($baseModule);
			if(!empty($moduleStrings['jsLanguageStrings'][$key])) {
				return $moduleStrings['jsLanguageStrings'][$key];
			}
		}

		$commonStrings = self::getModuleStringsFromFile();
		if(!empty($commonStrings['jsLanguageStrings'][$key]))
			return $commonStrings['jsLanguageStrings'][$key];

		return $key;
	}

	/**
	 * Function that returns translation strings from file
	 * @global <array> $languageStrings - language specific string which is used in translations
	 * @param <String> $module - module Name
	 * @return <array> - array if module has language strings else returns empty array
	 */
	public static function getModuleStringsFromFile($module='Vtiger'){
		$currentLanguage = self::getLanguage();
		if(empty(self::$languageContainer[$currentLanguage][$module])){

				$qualifiedName = 'languages.'.$currentLanguage.'.'.$module;
				$file = Vtiger_Loader::resolveNameToPath($qualifiedName);

				if(file_exists($file)){
					require $file;
					self::$languageContainer[$currentLanguage][$module]['languageStrings'] = $languageStrings;
					self::$languageContainer[$currentLanguage][$module]['jsLanguageStrings'] = $jsLanguageStrings;
				}
		}
		return self::$languageContainer[$currentLanguage][$module];
	}
	
	/**
	 * Function that returns current language
	 * @return <String> -
	 */
	public static function getLanguage() {
		$userModel = Users_Record_Model::getCurrentUserModel();
		$language = '';
		if (!empty($userModel)) {
			$language = $userModel->get('language');
		}
		return empty($language)? vglobal('default_language') : $language;
	}

	/**
	 * Function returns module strings
	 * @param <String> $module - module Name
	 * @param <String> languageStrings or jsLanguageStrings
	 * @return <Array>
	 */
	public static function export($module, $type='languageStrings') {
		$exportLangString = array();

		$moduleStrings = self::getModuleStringsFromFile($module);
		if(!empty($moduleStrings[$type])) {
			$exportLangString = $moduleStrings[$type];
		}

		// Lookup for the translation in base module, in case of sub modules, before ending up with common strings
		if(strpos($module, '.') > 0) {
			$baseModule = substr($module, 0, strpos($module, '.'));
			if($baseModule == 'Settings') {
				$baseModule = 'Settings.Vtiger';
			}
			$moduleStrings = self::getModuleStringsFromFile($baseModule);
			if(!empty($moduleStrings[$type])) {
				$exportLangString += $commonStrings[$type];
			}
		}

		$commonStrings = self::getModuleStringsFromFile();
		if(!empty($commonStrings[$type])) {
			$exportLangString += $commonStrings[$type];
		}

		return $exportLangString;;
	}

    /**
     * Function to returns all language information
     * @return <Array>
     */
    public static function getAllLanguages(){
       return Vtiger_Language::getAll();
    }

	/**
	 * Function to get the label name of the Langauge package
	 * @param <String> $name
	 */
	public static function getLanguageLabel($name) {
		$db = PearDatabase::getInstance();
		$languageResult = $db->pquery('SELECT label FROM vtiger_language WHERE prefix = ?', array($name));
		if($db->num_rows($languageResult)) {
			return $db->query_result($languageResult, 0, 'label');
		}
		return false;
	}
}

function vtranslate($key, $moduleName='') {
	$args = func_get_args();
	return call_user_func_array(array('Vtiger_Language_Handler', 'getTranslatedString'), $args);
}

function vJSTranslate($key, $moduleName='') {
	$args = func_get_args();
	return call_user_func_array(array('Vtiger_Language_Handler', 'getJSTranslatedString'), $args);
}
