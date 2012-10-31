<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Vtiger_Util_Helper {
	/**
	 * Function used to transform mulitiple uploaded file information into useful format.
	 * @param array $_files - ex: array( 'file' => array('name'=> array(0=>'name1',1=>'name2'),
	 *												array('type'=>array(0=>'type1',2=>'type2'),
	 *												...);
	 * @param type $top
	 * @return array   array( 'file' => array(0=> array('name'=> 'name1','type' => 'type1'),
	 *									array(1=> array('name'=> 'name2','type' => 'type2'),
	 *												...);
	 */
	public static function transformUploadedFiles(array $_files, $top = TRUE) {
		$files = array();
		foreach($_files as $name=>$file){
			if($top) $subName = $file['name'];
			else    $subName = $name;

			if(is_array($subName)){
				foreach(array_keys($subName) as $key){
					$files[$name][$key] = array(
						'name'     => $file['name'][$key],
						'type'     => $file['type'][$key],
						'tmp_name' => $file['tmp_name'][$key],
						'error'    => $file['error'][$key],
						'size'     => $file['size'][$key],
					);
					$files[$name] = self::transformUploadedFiles($files[$name], FALSE);
				}
			}else{
				$files[$name] = $file;
			}
		}
		return $files;
	}

	/**
	 * Function parses date into readable format
	 * @param <Date Time> $dateTime
	 * @return <String>
	 */
	public static function formatDateDiffInStrings($dateTime) {
		// http://www.php.net/manual/en/datetime.diff.php#101029

		$currentDateTime = date('Y-m-d H:i:s');
		$now = date_parse($currentDateTime);
		$dt = date_parse($dateTime);

		$currentTimeMs = strtotime($currentDateTime);
		$dateTimeMs = strtotime($dateTime);

		if ($currentTimeMs > $dateTimeMs) {
			$suffix = ' '. vtranslate('LBL_AGO');

			if ($now['year']   != $dt['year'])   return self::pluralize($now['year']   - $dt['year'],   "LBL_YEAR") . $suffix;
			if ($now['month']  != $dt['month'])  return self::pluralize($now['month']  - $dt['month'],  "LBL_MONTH") . $suffix;
			if ($now['day']    != $dt['day'])    return self::pluralize($now['day']    - $dt['day'],    "LBL_DAY") . $suffix;
			if ($now['hour']   != $dt['hour'])   return self::pluralize($now['hour']   - $dt['hour'],   "LBL_HOUR") . $suffix;
			if ($now['minute'] != $dt['minute']) return self::pluralize($now['minute'] - $dt['minute'], "LBL_MINUTE") . $suffix;
			if ($now['second'] != $dt['second']) return self::pluralize($now['second'] - $dt['second'], "LBL_SECOND") . $suffix;
		} else if ($currentTimeMs < $dateTimeMs) {
			$prefix = vtranslate('LBL_DUE') . ' ';

			if ($now['year']   != $dt['year'])   return $prefix . self::pluralize($dt['year']   - $now['year'],   "LBL_YEAR");
			if ($now['month']  != $dt['month'])  return $prefix . self::pluralize($dt['month']  - $now['month'],  "LBL_MONTH");
			if ($now['day']    != $dt['day'])    return $prefix . self::pluralize($dt['day']    - $now['day'],    "LBL_DAY");
			if ($now['hour']   != $dt['hour'])   return $prefix . self::pluralize($dt['hour']   - $now['hour'],   "LBL_HOUR");
			if ($now['minute'] != $dt['minute']) return $prefix . self::pluralize($dt['minute'] - $now['minute'], "LBL_MINUTE");
			if ($now['second'] != $dt['second']) return $prefix . self::pluralize($dt['second'] - $now['second'], "LBL_SECOND");
		} else {
			return vtranslate('LBL_JUSTNOW');
		}
	}

	/**
	 * Function returns singular or plural text
	 * @param <Number> $count
	 * @param <String> $text
	 * @return <String>
	 */
	public static function pluralize($count, $text) {
		return $count ." ". (($count == 1) ? vtranslate("$text") : vtranslate("${text}S"));
	}

	/**
	 * Function to make the input safe to be used as HTML
	 */
	public static function toSafeHTML($input) {
		global $default_charset;
		return htmlentities($input, ENT_QUOTES, $default_charset);
	}

	/**
	 * Function to validate the input with given pattern.
	 * @param <String> $string
	 * @param <Boolean> $skipEmpty Skip the check if string is empty.
	 * @return <String>
	 * @throws AppException
	 */
	public static function validateStringForSql($string, $skipEmpty=true) {
		if (vtlib_purifyForSql($string, $skipEmpty)) {
			return $string;
		}
		return false;
	}

	/**
	* Function to determine the UI mode we are working in.
	*/
	public static function inVtiger6UI() {
		   if (isset($_COOKIE) && isset($_COOKIE['vtigerui'])) {
				   return ($_COOKIE['vtigerui'] == 6);
		   }
		   return false;
	}
}
