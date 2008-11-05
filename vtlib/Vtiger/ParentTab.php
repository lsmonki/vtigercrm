<?php

include_once('vtlib/Vtiger/Common.inc.php');

class Vtiger_ParentTab {

	/**
	 * Add new menu item.
	 */
	static function add($new_tabid, $parent_tabname) {
		global $adb;
		
		$parent_tabid = self::getId($parent_tabname);
		$parent_tabrelseq = self::getNextParentTabRelSeq($parent_tabid);

		// vtiger_parenttabrel (parenttabid,tabid,sequence)
		$parent_tabsql = "INSERT INTO vtiger_parenttabrel(parenttabid, tabid, sequence) 
                      VALUES ($parent_tabid, $new_tabid, $parent_tabrelseq)";

		$adb->query($parent_tabsql);

		self::syncToFile();	

		Vtiger_Utils::Log("Creating new item ($new_tabid) under $parent_tabname " . " ... DONE");
	}

	static function syncToFile() {
		create_parenttab_data_file();
	}

	/**
	 * Get next sequence usable for the tab and parent tab relation.
	 */
	static function getNextParentTabRelSeq($parent_tabid) {
		global $adb;
		$parent_tabsql = "SELECT MAX(sequence) as max_sequence FROM vtiger_parenttab WHERE parenttabid = '$parent_tabid'";
		$parent_tabres = $adb->query($parent_tabsql);
		$parent_tabseq = $adb->query_result($parent_tabres, 0, 'max_sequence');
		return ++$parent_tabseq;
	}

	/**
	 * Get id associated with parent tab.
	 */	
	static function getId($parent_tabname) {
		global $adb;

		$parent_tabsql = "SELECT parenttabid FROM vtiger_parenttab WHERE parenttab_label = '$parent_tabname'";
		$parent_tabres = $adb->query($parent_tabsql);
		$parent_tabid  = $adb->query_result($parent_tabres, 0, 'parenttabid');
		return $parent_tabid;
	}

	/**
	 * Get label of the parent tab.
	 */
	static function getNameById($parenttabid) {
		global $adb;

		$parent_tabsql = "SELECT parenttab_label FROM vtiger_parenttab WHERE parenttabid = $parenttabid";
		$parent_tabres = $adb->query($parent_tabsql);
		$parent_tablabel=$adb->query_result($parent_tabres, 0, 'parenttab_label');
		return $parent_tablabel;
	}
}

?>
