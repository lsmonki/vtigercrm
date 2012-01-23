<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

require_once 'modules/Import/readers/FileReader.php';

class Import_CSV_Reader extends Import_File_Reader {

	public function getRow($rowNo) {
		global $default_charset;
		if($rowNo <= 0) return false;
		$fileHandler = $this->getFileHandler();
		$currentRow = 0;
		while($data = fgetcsv($fileHandler, 0, $this->userInputObject->get('delimiter'))) {
			$currentRow++;
			if($currentRow == $rowNo) {
				break;
			}
		}
		foreach($data as $key => $value) {
			$data[$key] = $this->convertCharacterEncoding($value, $this->userInputObject->get('file_encoding'), $default_charset);
		}
		unset($fileHandler);
		return $data;
	}

	public function read() {
		global $default_charset;

		$fileHandler = $this->getFileHandler();
		$status = $this->createTable();
		if(!$status) {
			return false;
		}

		$fieldMapping = $this->userInputObject->get('field_mapping');

		$i=-1;
		while($data = fgetcsv($fileHandler, 0, $this->userInputObject->get('delimiter'))) {
			$i++;
			if($this->userInputObject->get('has_header') && $i == 0) continue;
			$mappedData = array();
			$allValuesEmpty = true;
			foreach($fieldMapping as $fieldName => $index) {
				$fieldValue = $data[$index];
				$mappedData[$fieldName] = $fieldValue;
				if($this->userInputObject->get('file_encoding') != $default_charset) {
					$mappedData[$fieldName] = $this->convertCharacterEncoding($fieldValue, $this->userInputObject->get('file_encoding'), $default_charset);
				}
				if(!empty($fieldValue)) $allValuesEmpty = false;
			}
			if($allValuesEmpty) continue;
			$fieldNames = array_keys($mappedData);
			$fieldValues = array_values($mappedData);
			$this->addRecordToDB($fieldNames, $fieldValues);
		}
		unset($fileHandler);
	}
}
?>
