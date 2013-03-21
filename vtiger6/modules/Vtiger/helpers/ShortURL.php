<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

/**
 * Helper methods to work with ShortURLs
 */
class Vtiger_ShortURL_Helper {
	
	/*
	 * @param options array(
	 * 'handler_path'     => 'path/to/TrackerClass.php',
	 * 'handler_class'    => 'TrackerClass',
	 * 'handler_function' => 'trackingFunction',
	 * 'handler_data'     => array(
	 *			'key1' => 'value1',
	 *			'key2' => 'value2'
	 *		)
	 *	));
	 */
	static function generateURL(array $options) {
		global $site_URL;
		$uid = self::generate($options);
		return "$site_URL/shorturl.php?id=" . $uid;
	}
	
	static function generate(array $options) {
		$db = PearDatabase::getInstance();
		
		// TODO Review the random unique ID generation
		$uid = uniqid("", true);
		
		$handlerPath = $options['handler_path'];
		$handlerClass= $options['handler_class'];
		$handlerFn   = $options['handler_function'];
		$handlerData = $options['handler_data'];
		
		if (empty($handlerPath) || empty($handlerClass) || empty($handlerFn)) {
			throw new Exception("Invalid options for generate");
		}
		
		$sql = "INSERT INTO vtiger_shorturls(uid, handler_path, handler_class, handler_function, handler_data) VALUES (?,?,?,?,?)";
		$params = array($uid, $handlerPath, $handlerClass, $handlerFn, json_encode($handlerData));
		
		$db->pquery($sql, $params);
		return $uid;
	}
	
	static function handle($uid) {
		$db = PearDatabase::getInstance();
		
		$rs = $db->pquery('SELECT * FROM vtiger_shorturls WHERE uid=?', array($uid));
		if ($rs && $db->num_rows($rs)) {
			$record = $db->fetch_array($rs);
			$handlerPath = decode_html($record['handler_path']);
			$handlerClass= decode_html($record['handler_class']);
			$handlerFn   = decode_html($record['handler_function']);
			$handlerData = json_decode(decode_html($record['handler_data']), true);
			
			checkFileAccessForInclusion($handlerPath);
			require_once $handlerPath;
			
			$handler = new $handlerClass();
			call_user_func(array($handler, $handlerFn), $handlerData);
		}
	}
	
	static function sendTrackerImage() {
		// TODO send 1px x 1px transparent image
		echo "1 x 1 px transparent image\n";
	}
}