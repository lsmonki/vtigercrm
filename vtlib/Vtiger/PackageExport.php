<?php
include_once('vtlib/Vtiger/Common.inc.php');

include_once('vtlib/Vtiger/ParentTab.php');
include_once('vtlib/Vtiger/Module.php');
include_once('vtlib/Vtiger/Zip.php');

include_once('include/utils/VtlibUtils.php');

/**
 * Package Manager class for vtiger Modules.
 */
class Vtiger_PackageExport {
	var $_export_tmpdir = 'test/vtlib';
	var $_export_modulexml_filename = null;
	var $_export_modulexml_file = null;

	function Vtiger_PackageExport() {
		if(is_dir($this->_export_tmpdir) === FALSE) {
			mkdir($this->_export_tmpdir);
		}
	}

	/** Output Handler Functions **/
	function openNode($node,$delimiter="\n") {
		$this->__write("<$node>$delimiter");
	}	
	function closeNode($node,$delimiter="\n") {
		$this->__write("</$node>$delimiter");
	}
	function outputNode($value, $node='') {
		if($node != '') $this->openNode($node,'');
		$this->__write($value);
		if($node != '') $this->closeNode($node);
	}
	function __write($value) {
		fwrite($this->_export_modulexml_file, $value);
	}

	/**
	 * Set the module.xml file path for this export and 
	 * return its temporary path.
	 */
	function __getManifestFilePath() {
		if(!isset($this->_export_modulexml_filename)) {
			// Set the module xml filename to be written for exporting.
			$this->_export_modulexml_filename = "manifest-".time().".xml";
		}
		return "$this->_export_tmpdir/$this->_export_modulexml_filename";
	}

	/**
	 * Initialize Export
	 */
	function __initExport($module) {
		// We will be including the file, so do a security check.
		Vtiger_Utils::checkFileAccess("modules/$module/$module.php");
		$this->_export_modulexml_file = fopen($this->__getManifestFilePath(), 'w');
		$this->__write("<?xml version='1.0'?>\n");
	}

	/**
	 * Post export work.
	 */
	function __finishExport() {
		if(isset($this->_export_modulexml_file)) {
			fclose($this->_export_modulexml_file);
			$this->_export_modulexml_file = null;
		}
	}

    /**
     * Clean up the temporary files created.
     */
	function __cleanupExport() {
		if(isset($this->_export_modulexml_filename)) {
			unlink($this->__getManifestFilePath());
		}
	}

	/**
	 * Export Module as a zip file.
	 */
	function export($module, $todir='', $zipfilename='', $directDownload=false) {
		$this->__initExport($module);

		// Call module export function
		$this->export_Module($module);

		$this->__finishExport();		

		// Export as Zip
		if($zipfilename == '') $zipfilename = "$module-" . date('YmdHis') . ".zip";
		$zipfilename = "$this->_export_tmpdir/$zipfilename";

		$zip = new Vtiger_Zip($zipfilename);
		// Add manifest file
		$zip->addFile($this->__getManifestFilePath(), "manifest.xml");		
		// Copy module directory
		$zip->copyDirectoryFromDisk("modules/$module");
		// Copy templates directory of the module (if any)
		if(is_dir("Smarty/templates/modules/$module"))
			$zip->copyDirectoryFromDisk("Smarty/templates/modules/$module", "templates");

		$zip->save();

		if($directDownload) {
			$zip->forceDownload($zipfilename);
			unlink($zipfilename);
		}
		$this->__cleanupExport();
	}

	/**
	 * Export vtiger dependencies.
	 */
	function export_Dependencies() {
		global $vtiger_current_version;
		$this->openNode('dependencies');
		$this->outputNode($vtiger_current_version, 'vtiger_version');
		$this->closeNode('dependencies');
	}

	/**
	 * Export Module Handler.
	 */
	function export_Module($module) {
		global $adb;

		$moduleid = Vtiger_Module::getId($module);

		$sqlresult = $adb->query("SELECT * FROM vtiger_parenttabrel WHERE tabid = $moduleid");
		$parenttabid = $adb->query_result($sqlresult, 0, 'parenttabid');
		$parent_name = Vtiger_ParentTab::getNameById($parenttabid);

		$sqlresult = $adb->query("SELECT * FROM vtiger_tab WHERE tabid = $moduleid");
		$tabname = $adb->query_result($sqlresult, 0, 'name');
		$tablabel= $adb->query_result($sqlresult, 0, 'tablabel');

		$this->openNode('module');
		$this->outputNode(date('Y-m-d H:i:s'),'exporttime');
		$this->outputNode($tabname, 'name');
		$this->outputNode($tablabel, 'label');
		$this->outputNode($parent_name, 'parent');

		// Export dependency information
		$this->export_Dependencies();

		// Export module tables
		$this->export_Tables($module);

		// Export module blocks
		$this->export_Blocks($moduleid);

		// Export module filters
		$this->export_CustomViews($module, $moduleid);

		// Export Sharing Access
		$this->export_SharingAccess($module, $moduleid);

		// Export Actions
		$this->export_Actions($module, $moduleid);

		$this->closeNode('module');
	}

	/**
	 * Export module base and related tables.
	 */
	function export_Tables($module) {
		require_once("modules/$module/$module.php");

		$focus = new $module();

		// Setup required module variables which is need for vtlib API's
		vtlib_setup_modulevars($module, $focus);

		$tables = Array ($focus->table_name);
		if(isset($focus->groupTable)) $tables[] = $focus->groupTable[0];
		if(isset($focus->customFieldTable)) $tables[] = $focus->customFieldTable[0];

		$this->openNode('tables');

		foreach($tables as $table) {
			$this->openNode('table');
			$this->outputNode($table, 'name');
			$this->outputNode(Vtiger_Utils::CreateTableSql($table), 'sql');
			$this->closeNode('table');
		}
		$this->closeNode('tables');
	}

	/**
	 * Export module blocks with its related fields.
	 */
	function export_Blocks($moduleid) {
		global $adb;
		$sqlresult = $adb->query("SELECT * FROM vtiger_blocks WHERE tabid = $moduleid");
		$resultrows= $adb->num_rows($sqlresult);

		$this->openNode('blocks');
		for($index = 0; $index < $resultrows; ++$index) {
			$blockid    = $adb->query_result($sqlresult, $index, 'blockid');
			$blocklabel = $adb->query_result($sqlresult, $index, 'blocklabel');
		
			$this->openNode('block');
			$this->outputNode($blocklabel, 'label');
			// Export fields associated with the block
			$this->export_Fields($moduleid, $blockid);
			$this->closeNode('block');
		}
		$this->closeNode('blocks');
	}

	/**
	 * Export fields related to a module block.
	 */
	function export_Fields($moduleid, $blockid) {
		global $adb;
		
		$fieldresult = $adb->query("SELECT * FROM vtiger_field WHERE tabid=$moduleid AND block=$blockid");
		$fieldcount = $adb->num_rows($fieldresult);

		$entityresult = $adb->query("SELECT * FROM vtiger_entityname WHERE tabid=$moduleid");
		$entity_fieldname = $adb->query_result($entityresult, 0, 'fieldname');

		$this->openNode('fields');
		for($index = 0; $index < $fieldcount; ++$index) {
			$this->openNode('field');
			$fieldname = $adb->query_result($fieldresult, $index, 'fieldname');
			$uitype = $adb->query_result($fieldresult, $index, 'uitype');

			$this->outputNode($fieldname, 'fieldname');			
			$this->outputNode($uitype,    'uitype');
			$this->outputNode($adb->query_result($fieldresult, $index, 'columnname'),'columnname');			
			$this->outputNode($adb->query_result($fieldresult, $index, 'tablename'),     'tablename');
			$this->outputNode($adb->query_result($fieldresult, $index, 'generatedtype'), 'generatedtype');
			$this->outputNode($adb->query_result($fieldresult, $index, 'fieldlabel'),    'fieldlabel');
			$this->outputNode($adb->query_result($fieldresult, $index, 'readonly'),      'readonly');
			$this->outputNode($adb->query_result($fieldresult, $index, 'presence'),      'presence');
			$this->outputNode($adb->query_result($fieldresult, $index, 'selected'),      'selected');
			$this->outputNode($adb->query_result($fieldresult, $index, 'maximumlength'), 'maximumlength');
			$this->outputNode($adb->query_result($fieldresult, $index, 'typeofdata'),    'typeofdata');
			$this->outputNode($adb->query_result($fieldresult, $index, 'quickcreate'),   'quickcreate');
			$this->outputNode($adb->query_result($fieldresult, $index, 'displaytype'),   'displaytype');
			$this->outputNode($adb->query_result($fieldresult, $index, 'info_type'),     'info_type');

			// Export Entity Identifier Information
			if($fieldname == $entity_fieldname) {
				$this->openNode('entityidentifier');
				$this->outputNode($adb->query_result($entityresult, 0, 'entityidfield'),    'entityidfield');
				$this->outputNode($adb->query_result($entityresult, 0, 'entityidcolumn'), 'entityidcolumn');
				$this->closeNode('entityidentifier');
			}

			// Export picklist values for picklist fields
			if($uitype == '15' || $uitype == '16' || $uitype == '111' || $uitype == '33' || $uitype == '55') {
				$picklistvalues = vtlib_getPicklistValues_AccessibleToAll($fieldname);
				$this->openNode('picklistvalues');
				foreach($picklistvalues as $picklistvalue) {
					$this->outputNode($picklistvalue, 'picklistvalue');
				}
				$this->closeNode('picklistvalues');
			}

			$this->closeNode('field');

		}
		$this->closeNode('fields');
	}

	/**
	 * Export Custom views of the module.
	 */
	function export_CustomViews($module, $moduleid) {
		global $adb;

		$customviewres = $adb->query("SELECT * FROM vtiger_customview WHERE entitytype = '$module'");
		$customviewcount=$adb->num_rows($customviewres);

		$this->openNode('customviews');
		for($cvindex = 0; $cvindex < $customviewcount; ++$cvindex) {

			$cvid = $adb->query_result($customviewres, $cvindex, 'cvid');

			$cvcolumnres = $adb->query("SELECT * FROM vtiger_cvcolumnlist WHERE cvid=$cvid");
			$cvcolumncount=$adb->num_rows($cvcolumnres);

			$this->openNode('customview');

			$setdefault = $adb->query_result($customviewres, $cvindex, 'setdefault');
			$setdefault = ($setdefault == 1)? 'true' : 'false';

			$setmetrics = $adb->query_result($customviewres, $cvindex, 'setmetrics');
			$setmetrics = ($setmetrics == 1)? 'true' : 'false';

			$this->outputNode($adb->query_result($customviewres, $cvindex, 'viewname'),   'viewname');
			$this->outputNode($setdefault, 'setdefault');
			$this->outputNode($setmetrics, 'setmetrics');

			$this->openNode('fields');
			for($index = 0; $index < $cvcolumncount; ++$index) {
				$cvcolumnindex = $adb->query_result($cvcolumnres, $index, 'columnindex');
				$cvcolumnname = $adb->query_result($cvcolumnres, $index, 'columnname');
				$cvcolumnnames= explode(':', $cvcolumnname);
				$cvfieldname = $cvcolumnnames[2];

				$this->openNode('field');
				$this->outputNode($cvfieldname, 'fieldname');
				$this->outputNode($cvcolumnindex,'columnindex');
				$this->closeNode('field');
			}
			$this->closeNode('fields');

			$this->closeNode('customview');
		}
		$this->closeNode('customviews');
	}

	/**
	 * Export Sharing Access of the module.
	 */
	function export_SharingAccess($module, $moduleid) {
		global $adb;

		$deforgshare = $adb->query("SELECT * FROM vtiger_def_org_share WHERE tabid=$moduleid");
		$deforgshareCount = $adb->num_rows($deforgshare);

		$this->openNode('sharingaccess');
		if($deforgshareCount) {
			for($index = 0; $index < $deforgshareCount; ++$index) {
				$permission = $adb->query_result($deforgshare, $index, 'permission');
				$permissiontext = '';
				if($permission == '0') $permissiontext = 'public_readonly';
				if($permission == '1') $permissiontext = 'public_readwrite';
				if($permission == '2') $permissiontext = 'public_readwritedelete';
				if($permission == '3') $permissiontext = 'private';

				$this->outputNode($permissiontext, 'default');
			}
		}
		$this->closeNode('sharingaccess');		
	}

	/**
	 * Export actions (tools) associated with module.
	 * TODO: Need to pickup values based on status for all user (profile)
	 */
	function export_Actions($module, $moduleid) {
		$this->openNode('actions');

		$this->openNode('action');
		$this->outputNode('Export', 'name');
		$this->outputNode('enabled', 'status');
		$this->closeNode('action');

		$this->openNode('action');
		$this->outputNode('Import', 'name');
		$this->outputNode('enabled', 'status');
		$this->closeNode('action');

		$this->closeNode('actions');
	}
}
?>
