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
		foreach($_files as $name=>$file) {
			if($top) $subName = $file['name'];
			else    $subName = $name;

			if(is_array($subName)) {
				foreach(array_keys($subName) as $key) {
					$files[$name][$key] = array(
							'name'     => $file['name'][$key],
							'type'     => $file['type'][$key],
							'tmp_name' => $file['tmp_name'][$key],
							'error'    => $file['error'][$key],
							'size'     => $file['size'][$key],
					);
					$files[$name] = self::transformUploadedFiles($files[$name], FALSE);
				}
			}else {
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

		$seconds =  strtotime($currentDateTime) - strtotime($dateTime);

		if ($seconds == 0) return vtranslate('LBL_JUSTNOW');
		if ($seconds > 0) {
			$prefix = '';
			$suffix = ' '. vtranslate('LBL_AGO');
		} else if ($seconds < 0) {
			$prefix = vtranslate('LBL_DUE') . ' ';
			$suffix = '';
			$seconds = -($seconds);
		}

		$minutes = floor($seconds/60);
		$hours = floor($minutes/60);
		$days = floor($hours/24);
		$months = floor($days/30);

		if ($seconds < 60)	return $prefix . self::pluralize($seconds,	"LBL_SECOND") . $suffix;
		if ($minutes < 60)	return $prefix . self::pluralize($minutes,	"LBL_MINUTE") . $suffix;
		if ($hours < 24)	return $prefix . self::pluralize($hours,	"LBL_HOUR") . $suffix;
		if ($days < 30)		return $prefix . self::pluralize($days,		"LBL_DAY") . $suffix;
		if ($months < 12)	return $prefix . self::pluralize($months,	"LBL_MONTH") . $suffix;
		if ($months > 11)	return $prefix . self::pluralize(floor($days/365), "LBL_YEAR") . $suffix;
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

	/**
	 * Function to parses date into string format
	 * @param <Date> $date
	 * @param <Time> $time
	 * @return <String>
	 */
	public static function formatDateIntoStrings($date, $time = false) {
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$dateTimeInUserFormat = Vtiger_Datetime_UIType::getDisplayDateTimeValue($date . ' ' . $time);

		list($dateInUserFormat, $timeInUserFormat) = explode(' ', $dateTimeInUserFormat);
		list($hours, $minutes, $seconds) = explode(':', $timeInUserFormat);

		$displayTime = $hours .':'. $minutes;
		if ($currentUser->get('hour_format') === '12') {
			$displayTime = Vtiger_Time_UIType::getTimeValueInAMorPM($displayTime);
		}

		$today = Vtiger_Date_UIType::getDisplayDateValue(date('Y-m-d H:i:s'));
		$tomorrow = Vtiger_Date_UIType::getDisplayDateValue(date('Y-m-d H:i:s', strtotime('tomorrow')));

		if ($dateInUserFormat == $today) {
			$formatedDate = vtranslate('LBL_TODAY');
			if ($time) {
				$formatedDate .= ' '. vtranslate('LBL_AT') .' '. $displayTime;
			}
		} elseif ($dateInUserFormat == $tomorrow) {
			$formatedDate = vtranslate('LBL_TOMORROW');
			if ($time) {
				$formatedDate .= ' '. vtranslate('LBL_AT') .' '. $displayTime;
			}
		} else {
			$date = strtotime($date.' '.$time);
			$formatedDate = vtranslate('LBL_'.date('D', $date)) . ' ' . date('d', $date) . ' ' . vtranslate('LBL_'.date('M', $date));
			if (date('Y', $date) != date('Y')) {
				$formatedDate .= ', '.date('Y', $date);
			}
		}
		return $formatedDate;
	}

	/**
	 * Function to replace spaces with under scores
	 * @param <String> $string
	 * @return <String>
	 */
	public static function replaceSpaceWithUnderScores($string) {
		return str_replace(' ', '_', $string);
	}

	public static function getRecordName ($recordId, $checkDelete=false) {
        $adb = PearDatabase::getInstance();

        $query = 'SELECT label from vtiger_crmentity where crmid=?';
        if($checkDelete) {
            $query.= ' AND deleted=0';
        }
        $result = $adb->pquery($query,array($recordId));

        $num_rows = $adb->num_rows($result);
        if($num_rows) {
            return $adb->query_result($result,0,'label');
        }
        return false;
    }

	/**
	 * Function to parse dateTime into Days
	 * @param <DateTime> $dateTime
	 * @return <String>
	 */
	public static function formatDateTimeIntoDayString($dateTime) {
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$dateTimeInUserFormat = Vtiger_Datetime_UIType::getDisplayDateTimeValue($dateTime);

		list($dateInUserFormat, $timeInUserFormat) = explode(' ', $dateTimeInUserFormat);
		list($hours, $minutes, $seconds) = explode(':', $timeInUserFormat);

		$displayTime = $hours .':'. $minutes;
		if ($currentUser->get('hour_format') === '12') {
			$displayTime = Vtiger_Time_UIType::getTimeValueInAMorPM($displayTime);
		}

		$date = strtotime($dateTime);
		//Adding date details
		$formatedDate = vtranslate('LBL_'.date('D', $date)). ', ' .vtranslate('LBL_'.date('M', $date)). ' ' .date('d', $date). ', ' .date('Y', $date);
		//Adding time details
		$formatedDate .= ' ' .vtranslate('LBL_AT'). ' ' .$displayTime;

		return $formatedDate;
	}

}