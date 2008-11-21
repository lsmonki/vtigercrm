<?php
include_once('vtlib/Vtiger/PackageExport.php');
include_once('vtlib/Vtiger/Unzip.php');

include_once('vtlib/Vtiger/Module.php');
include_once('vtlib/Vtiger/Event.php');

/**
 * Provides API to import module into vtiger CRM
 * @package vtlib
 */
class Vtiger_PackageImport extends Vtiger_PackageExport {

	/**
	 * Module Meta XML File (Parsed)
	 * @access private
	 */
	var $_modulexml;
	/**
	 * Module Fields mapped by [modulename][fieldname] which
	 * will be used to create customviews.
	 * @access private
	 */
	var $_modulefields = Array();

	/**
	 * Constructor
	 */
	function Vtiger_PackageImport() {
		parent::__construct();
	}

	/**
	 * Parse the manifest file
	 * @access private
	 */
	function __parseManifestFile($unzip) {
		$manifestfile = $this->__getManifestFilePath();
		$unzip->unzip('manifest.xml', $manifestfile);
		$this->_modulexml = simplexml_load_file($manifestfile);
		unlink($manifestfile);
	}

	/**
	 * Check if zipfile is a valid package
	 * @access private
	 */
	function checkzip($zipfile) {
		$unzip = new Vtiger_Unzip($zipfile);
		$filelist = $unzip->getList();

		$manifestxml_found = false;
		$languagefile_found = false;
		$vtigerversion_found = false;

		$modulename = null;

		foreach($filelist as $filename=>$fileinfo) {
			$matches = Array();
			preg_match('/manifest.xml/', $filename, $matches);
			if(count($matches)) { 
				$manifestxml_found = true;
				$this->__parseManifestFile($unzip);
				$modulename = $this->_modulexml->name;
				continue; 
			}
			preg_match("/modules\/([^\/]+)\/language\/en_us.lang.php/", $filename, $matches);
			if(count($matches) && strcmp($modulename, $matches[1]) === 0) { $languagefile_found = true; continue; }
		}

		if(!empty($this->_modulexml) && 
			!empty($this->_modulexml->dependencies) &&
			!empty($this->_modulexml->dependencies->vtiger_version)) {
				$vtigerversion_found = true;
		}

		$validzip = false;
		if($manifestxml_found && $languagefile_found && $vtigerversion_found) 
			$validzip = true;

		return $validzip;
	}

	/**
	 * Get module name packaged in the zip file
	 * @access private
	 */
	function getModuleNameFromZip($zipfile) {
		if(!$this->checkZip($zipfile)) return null;

		return $this->_modulexml->name;
	}

	/**
	 * Initialize Import
	 * @access private
	 */
	function initImport($zipfile, $overwrite) {
		$module = $this->getModuleNameFromZip($zipfile);
		if($module != null) {
			$unzip = new Vtiger_Unzip($zipfile, $overwrite);

			// Unzip selectively
	
			$unzip->unzipAllEx( ".",
				Array(
					'include' => Array('templates', "modules/$module"), // We don't need manifest.xml
					//'exclude' => Array('manifest.xml')                // DEFAULT: excludes all not in include
				),
				// Templates folder to be renamed while copying
				Array('templates' => "Smarty/templates/modules/$module") 
			);

			// If data is not yet available
			if(empty($this->_modulexml)) {
				$this->__parseManifestFile($unzip);
			}
		}
		return $module;
	}

	/**
	 * Get dependent version
	 * @access private
	 */
	function getDependentVtigerVersion() {
		return $this->_modulexml->dependencies->vtiger_version;
	}

	/**
	 * Import Module from zip file
	 * @param String Zip file name
	 * @param Boolean True for overwriting existing module
	 */
	function import($zipfile, $overwrite=false) {
		$module = $this->initImport($zipfile, $overwrite);
	
		// Call module import function
		$this->import_Module();
	}

	/**
	 * Import Module
	 * @access private
	 */
	function import_Module() {
		$tabname = $this->_modulexml->name;
		$tablabel= $this->_modulexml->label;
		$parenttab=$this->_modulexml->parent;

		$moduleInstance = new Vtiger_Module();
		$moduleInstance->name = $tabname;
		$moduleInstance->label= $tablabel;
		$moduleInstance->save();

		$menuInstance = Vtiger_Menu::getInstance($parenttab);
		$menuInstance->addModule($moduleInstance);
		
		$this->import_Tables($this->_modulexml);
		$this->import_Blocks($this->_modulexml, $moduleInstance);
		$this->import_CustomViews($this->_modulexml, $moduleInstance);
		$this->import_SharingAccess($this->_modulexml, $moduleInstance);
		$this->import_Events($this->_modulexml, $moduleInstance);
		$this->import_Actions($this->_modulexml, $moduleInstance);
	}

	/**
	 * Import Tables of the module
	 * @access private
	 */
	function import_Tables($modulenode) {
		if(empty($modulenode->tables) || empty($modulenode->tables->table)) return;
		foreach($modulenode->tables->table as $tablenode) {
			$tablename = $tablenode->name;
			$tablesql  = $tablenode->sql;

			if(!Vtiger_Utils::checkTable($tablename)) {
				Vtiger_Utils::ExecuteQuery($tablesql);
			}
		}
	}

	/**
	 * Import Blocks of the module
	 * @access private
	 */
	function import_Blocks($modulenode, $moduleInstance) {
		if(empty($modulenode->blocks) || empty($modulenode->blocks->block)) return;
		foreach($modulenode->blocks->block as $blocknode) {
			$blocklabel = $blocknode->label;

			$blockInstance = new Vtiger_Block();
			$blockInstance->label = $blocklabel;
			$moduleInstance->addBlock($blockInstance);
			
			$this->import_Fields($blocknode, $blockInstance, $moduleInstance);
		}
	}

	/**
	 * Import Fields of the module
	 * @access private
	 */
	function import_Fields($blocknode, $blockInstance, $moduleInstance) {
		if(empty($blocknode->fields) || empty($blocknode->fields->field)) return;

		foreach($blocknode->fields->field as $fieldnode) {
			$fieldInstance = new Vtiger_Field();
			$fieldInstance->name         = $fieldnode->fieldname;
			$fieldInstance->label        = $fieldnode->fieldlabel;
			$fieldInstance->table        = $fieldnode->tablename;
			$fieldInstance->column       = $fieldnode->columnname;
			$fieldInstance->uitype       = $fieldnode->uitype;
			$fieldInstance->generatedtype= $fieldnode->generatedtype;
			$fieldInstance->readonly     = $fieldnode->readonly;
			$fieldInstance->presence     = $fieldnode->presence;
			$fieldInstance->selected     = $fieldnode->selected;
			$fieldInstance->maximumlength= $fieldnode->maximumlength;
			$fieldInstance->sequence     = $fieldnode->sequence;
			$fieldInstance->quickcreate  = $fieldnode->quickcreate;
			$fieldInstance->quicksequence= $fieldnode->quickcreatesequence;
			$fieldInstance->typeofdata   = $fieldnode->typeofdata;
			$fieldInstance->displaytype  = $fieldnode->displaytype;
			$fieldInstance->info_type    = $fieldnode->info_type;

			if(isset($fieldnode->columntype) && !empty($fieldnode->columntype)) 
				$fieldInstance->columntype = $fieldnode->columntype;

			$blockInstance->addField($fieldInstance);

			// Set the field as entity identifier if marked.
			if(!empty($fieldnode->entityidentifier)) {
				$moduleInstance->entityidfield = $fieldnode->entityidentifier->entityidfield;
				$moduleInstance->entityidcolumn= $fieldnode->entityidentifier->entityidcolumn;
				$moduleInstance->setEntityIdentifier($fieldInstance);
			}

			// Check picklist values associated with field if any.
			if(!empty($fieldnode->picklistvalues) && !empty($fieldnode->picklistvalues->picklistvalue)) {
				$picklistvalues = Array();
				foreach($fieldnode->picklistvalues->picklistvalue as $picklistvaluenode) {
					$picklistvalues[] = $picklistvaluenode;
				}
				$fieldInstance->setPicklistValues( $picklistvalues );
			}

			// Check related modules associated with this field
			if(!empty($fieldnode->relatedmodules) && !empty($fieldnode->relatedmodules->relatedmodule)) {
				$relatedmodules = Array();
				foreach($fieldnode->relatedmodules->relatedmodule as $relatedmodulenode) {
					$relatedmodules[] = $relatedmodulenode;
				}
				$fieldInstance->setRelatedModules($relatedmodules);
			}

			$this->_modulefields["$moduleInstance->name"]["$fieldnode->fieldname"] = $fieldInstance;
		}
	}

	/**
	 * Import Custom views of the module
	 * @access private
	 */
	function import_CustomViews($modulenode, $moduleInstance) {
		if(empty($modulenode->customviews) || empty($modulenode->customviews->customview)) return;
		foreach($modulenode->customviews->customview as $customviewnode) {
			$viewname = $customviewnode->viewname;
			$setdefault=$customviewnode->setdefault;
			$setmetrics=$customviewnode->setmetrics;

			$filterInstance = new Vtiger_Filter();
			$filterInstance->name = $viewname;
			$filterInstance->isdefault = $setdefault;
			$filterInstance->inmetrics = $setmetrics;

			$moduleInstance->addFilter($filterInstance);

			foreach($customviewnode->fields->field as $fieldnode) {
				$fieldInstance = $this->_modulefields["$moduleInstance->name"]["$fieldnode->fieldname"];
				$filterInstance->addField($fieldInstance, $fieldnode->columnindex);

				if(!empty($fieldnode->rules->rule)) {
					foreach($fieldnode->rules->rule as $rulenode) {
						$filterInstance->addRule($fieldInstance, $rulenode->comparator, $rulenode->value, $rulenode->columnindex);
					}
				}
			}
		}
	}

	/**
	 * Import Sharing Access of the module
	 * @access private
	 */
	function import_SharingAccess($modulenode, $moduleInstance) {
		if(empty($modulenode->sharingaccess)) return;

		if(!empty($modulenode->sharingaccess->default)) {
			foreach($modulenode->sharingaccess->default as $defaultnode) {
				$moduleInstance->setDefaultSharing($defaultnode);
			}
		}
	}

	/**
	 * Import Events of the module
	 * @access private
	 */
	function import_Events($modulenode, $moduleInstance) {
		if(empty($modulenode->events) || empty($modulenode->events->event))	return;

		if(Vtiger_Event::hasSupport()) {
			foreach($modulenode->events->event as $eventnode) {
				Vtiger_Event::register($moduleInstance, 
					$eventnode->eventname, $eventnode->classname, $eventnode->filename);
			}
		}
	}

	/**
	 * Import actions of the module
	 * @access private
	 */
	function import_Actions($modulenode, $moduleInstance) {
		if(empty($modulenode->actions) || empty($modulenode->actions->action)) return;
		foreach($modulenode->actions->action as $actionnode) {
			$actionstatus = $actionnode->status;
			if($actionstatus == 'enabled') 
				$moduleInstance->enableTools($actionnode->name);
			else
				$moduleInstance->disableTools($actionnode->name);
		}
	}
}			
?>
