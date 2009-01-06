<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
include_once('vtlib/Vtiger/Utils.php');
include_once('vtlib/Vtiger/Utils/StringTemplate.php');

/**
 * Provides API to handle custom links
 * @package vtlib
 */
class Vtiger_Link {
	var $linkid;
	var $linktype;
	var $linklabel;
	var $linkurl;
	var $linkicon;
	var $sequence;

	/**
	 * Constructor
	 */
	function __construct() {
	}

	/**
	 * Initialize this instance.
	 */
	function initialize($valuemap) {
		$this->linkid = $valuemap['linkid'];
		$this->linktype=$valuemap['linktype'];
		$this->linklabel=$valuemap['linklabel'];
		$this->linkurl  =decode_html($valuemap['linkurl']);
		$this->linkicon =decode_html($valuemap['linkicon']);
		$this->sequence =$valuemap['sequence'];
	}

	/**
	 * Get unique id for the insertion
	 */
	static function __getUniqueId() {
		global $adb;
		return $adb->getUniqueID('vtiger_links');
	}

	/** Cache (Record) the schema changes to improve performance */
	static $__cacheSchemaChanges = Array();

	/**
	 * Initialize the schema (tables)
	 */
	static function __initSchema() {
		if(empty(self::$__cacheSchemaChanges['vtiger_links'])) {
			if(!Vtiger_Utils::CheckTable('vtiger_links')) {
				Vtiger_Utils::CreateTable(
					'vtiger_links',
					'(linkid INT NOT NULL PRIMARY KEY,
					tabid INT, linktype VARCHAR(20), linklabel VARCHAR(30), linkurl VARCHAR(255), linkicon VARCHAR(100), sequence INT)');
				Vtiger_Utils::ExecuteQuery(
					'CREATE INDEX link_tabidtype_idx on vtiger_links(tabid,linktype)');
			}
			self::$__cacheSchemaChanges['vtiger_links'] = true;
		}
	}

	/**
	 * Add link given module
	 * @param Integer Module ID
	 * @param String Link Type (like DETAILVIEW). Useful for grouping based on pages.
	 * @param String Label to display
	 * @param String HREF value or URL to use for the link
	 * @param String ICON to use on the display
	 * @param Integer Order or sequence of displaying the link
	 */
	static function addLink($tabid, $type, $label, $url, $iconpath='',$sequence=0) {
		global $adb;
		self::__initSchema();
		$checkres = $adb->pquery('SELECT linkid FROM vtiger_links WHERE tabid=? AND linktype=? AND linkurl=? AND linkicon=? AND linklabel=?',
			Array($tabid, $type, $url, $iconpath, $label));
		if(!$adb->num_rows($checkres)) {
			$uniqueid = self::__getUniqueId();
			$adb->pquery('INSERT INTO vtiger_links (linkid,tabid,linktype,linklabel,linkurl,linkicon,sequence) VALUES(?,?,?,?,?,?,?)',
				Array($uniqueid, $tabid, $type, $label, $url, $iconpath, $sequence));
			self::log("Adding Link ($type - $label) ... DONE");
		}
	}

	/**
	 * Delete all links related to module
	 * @param Integer Module ID.
	 */
	static function deleteAll($tabid) {
		global $adb;
		self::__initSchema();
		$adb->pquery('DELETE FROM vtiger_links WHERE tabid=?', Array($tabid));
		self::log("Deleting Links ... DONE");
	}

	/**
	 * Get all the links related to module
	 * @param Integer Module ID.
	 */
	static function getAll($tabid) {
		return self::getAllByType($tabid);
	}

	/**
	 * Get all the link related to module based on type
	 * @param Integer Module ID
	 * @param String Type of the links to pickup
	 * @param Map Key-Value pair to use for formating the link url
	 */
	static function getAllByType($tabid, $type=false, $parameters=false) {
		global $adb;
		self::__initSchema();

		if($type) {
			$result = $adb->pquery('SELECT * FROM vtiger_links WHERE tabid=? AND linktype=?',
				Array($tabid, $type));
		} else {
			$result = $adb->pquery('SELECT * FROM vtiger_links WHERE tabid=?', Array($tabid));
		}

		$strtemplate = new Vtiger_StringTemplate();
		if($parameters) {
			foreach($parameters as $key=>$value) $strtemplate->assign($key, $value);
		}

		$instances = Array();
		while($row = $adb->fetch_array($result)){
			$instance = new self();
			$instance->initialize($row);
			if($parameters) {
				$instance->linkurl = $strtemplate->merge($instance->linkurl);
				$instance->linkicon= $strtemplate->merge($instance->linkicon);
			}
			$instances[] = $instance;
		}
		return $instances;
	}

	/**
	 * Helper function to log messages
	 * @param String Message to log
	 * @param Boolean true appends linebreak, false to avoid it
	 * @access private
	 */
	static function log($message, $delimit=true) {
		Vtiger_Utils::Log($message, $delimit);
	}
}
?>
