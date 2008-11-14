<?php
include_once('vtlib/Vtiger/PackageExport.php');

include_once('vtlib/Vtiger/Unzip.php');

include_once('vtlib/Vtiger/Tab.php');
include_once('vtlib/Vtiger/Module.php');
include_once('vtlib/Vtiger/Block.php');
include_once('vtlib/Vtiger/Field.php');
include_once('vtlib/Vtiger/CustomView.php');


/**
 * Package Manager class for vtiger Modules.
 */
class Vtiger_PackageImport extends Vtiger_PackageExport {

	/**
	 * Module Meta XML File (Parsed).
	 */
	var $_modulexml;
	/**
	 * Module Fields mapped by [modulename][fieldname] which
	 * will be used to create customviews.
	 */
	var $_modulefields = Array();

	function Vtiger_PackageImport() {
		parent::__construct();
	}

	/**
	 * Parse the manifest file.
	 */
	function __parseManifestFile($unzip) {
		$manifestfile = $this->__getManifestFilePath();
		$unzip->unzip('manifest.xml', $manifestfile);
		$this->_modulexml = simplexml_load_file($manifestfile);
		unlink($manifestfile);
	}

	/**
	 * Check if zipfile is a valid package.
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
	 * Get module name packaged in the zip file.
	 */
	function getModuleNameFromZip($zipfile) {
		if(!$this->checkZip($zipfile)) return null;

		return $this->_modulexml->name;
	}

	/**
	 * Initialize Import
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
	 * Get dependent version.
	 */
	function getDependentVtigerVersion() {
		return $this->_modulexml->dependencies->vtiger_version;
	}

	/**
	 * Import Module from zip file.
	 */
	function import($zipfile, $overwrite=false) {
		$module = $this->initImport($zipfile, $overwrite);
	
		// Call module import function
		$this->import_Module();
	}

	/**
	 * Import Module.
	 */
	function import_Module() {
		$tabname = $this->_modulexml->name;
		$tablabel= $this->_modulexml->label;
		$parenttab=$this->_modulexml->parent;

		Vtiger_Tab::create($tabname, $tablabel, $parenttab);

		$this->import_Tables($this->_modulexml);
		$this->import_Blocks($this->_modulexml);
		$this->import_CustomViews($this->_modulexml);
		$this->import_SharingAccess($this->_modulexml);
		$this->import_Actions($this->_modulexml);
	}

	/**
	 * Import Tables of the module.
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
	 * Import Blocks of the module.
	 */
	function import_Blocks($modulenode) {
		if(empty($modulenode->blocks) || empty($modulenode->blocks->block)) return;
		foreach($modulenode->blocks->block as $blocknode) {
			$blocklabel = $blocknode->label;
			
			Vtiger_Block::create($modulenode->name, $blocklabel);

			$this->import_Fields($modulenode, $blocknode);
		}
	}

	/**
	 * Import Fields of the module.
	 */
	function import_Fields($modulenode, $blocknode) {
		if(empty($blocknode->fields) || empty($blocknode->fields->field)) return;
		foreach($blocknode->fields->field as $fieldnode) {
			$field = new Vtiger_Field();
			$field-> set('module', $modulenode->name)
				-> set('columnname', $fieldnode->columnname)
				-> set('tablename',  $fieldnode->tablename)
				-> set('generatedtype',$fieldnode->generatedtype)
				-> set('uitype',       $fieldnode->uitype)
				-> set('fieldname',    $fieldnode->fieldname)
				-> set('fieldlabel',   $fieldnode->fieldlabel)
				-> set('readonly',     $fieldnode->readonly)
				-> set('presence',     $fieldnode->presence)
				-> set('selected',     $fieldnode->selected)
				-> set('maximumlength',$fieldnode->maximumlength)
				-> set('sequence',     null)
				-> set('typeofdata',   $fieldnode->typeofdata)
				-> set('quickcreate',  $fieldnode->quickcreate)
				-> set('block',        null)
				-> set('blocklabel',   $blocknode->label)
				-> set('displaytype',  $fieldnode->displaytype)
				-> set('quickcreatesequence',null)
				-> set('info_type',    $fieldnode->info_type);
			if(isset($fieldnode->columntype) && !empty($fieldnode->columntype)) 
				$field->set('columntype', $fieldnode->columntype);
			$field->create();

			// Set the field as entity identifier if marked.
			if(!empty($fieldnode->entityidentifier)) {
				$field->set('entityidfield', $fieldnode->entityidentifier->entityidfield)
					->set('entityidcolumn', $fieldnode->entityidentifier->entityidcolumn);
				$field->setEntityIdentifier();
			}

			// Check picklist values associated with field if any.
			if(!empty($fieldnode->picklistvalues) && !empty($fieldnode->picklistvalues->picklistvalue)) {
				$picklistvalues = Array();
				foreach($fieldnode->picklistvalues->picklistvalue as $picklistvaluenode) {
					$picklistvalues[] = $picklistvaluenode;
				}
				$field->setupPicklistValues( $picklistvalues );
			}

			// Check related modules associated with this field
			if(!empty($fieldnode->relatedmodules) && !empty($fieldnode->relatedmodules->relatedmodule)) {
				$relatedmodules = Array();
				foreach($fieldnode->relatedmodules->relatedmodule as $relatedmodulenode) {
					$relatedmodules[] = $relatedmodulenode;
				}
				$field->setRelatedModules($relatedmodules);
			}

			$this->_modulefields["$modulenode->name"]["$fieldnode->fieldname"] = $field;
		}
	}

	/**
	 * Import Custom views of the module.
	 */
	function import_CustomViews($modulenode) {
		if(empty($modulenode->customviews) || empty($modulenode->customviews->customview)) return;
		foreach($modulenode->customviews->customview as $customviewnode) {
			$viewname = $customviewnode->viewname;
			$setdefault=$customviewnode->setdefault;
			$setmetrics=$customviewnode->setmetrics;
			
			Vtiger_CustomView::create($modulenode->name, $viewname, $setdefault, $setmetrics);			
			$cv = new Vtiger_CustomView($modulenode->name, $viewname);
			foreach($customviewnode->fields->field as $fieldnode) {
				$cvfield = $this->_modulefields["$modulenode->name"]["$fieldnode->fieldname"];
				$cv->addColumn($cvfield, $fieldnode->columnindex);
				if(!empty($fieldnode->rules->rule)) {
					foreach($fieldnode->rules->rule as $rulenode) {
						$cv->addRule($cvfield, $rulenode->comparator, $rulenode->value, $rulenode->columnindex);
					}
				}
			}
		}
	}

	/**
	 * Import Sharing Access of the module.
	 */
	function import_SharingAccess($modulenode) {
		if(empty($modulenode->sharingaccess)) return;

		if(!empty($modulenode->sharingaccess->default)) {
			foreach($modulenode->sharingaccess->default as $defaultnode) {
				Vtiger_Module::setDefaultSharingAccess($modulenode->name, $defaultnode);
			}
		}
	}

	/**
	 * Import actions of the module.
	 */
	function import_Actions($modulenode) {
		if(empty($modulenode->actions) || empty($modulenode->actions->action)) return;
		foreach($modulenode->actions->action as $actionnode) {
			$actionstatus = $actionnode->status;
			if($actionstatus == 'enabled') 
				Vtiger_Module::enableAction($modulenode->name, $actionnode->name);
			else
				Vtiger_Module::disableAction($modulenode->name, $actionnode->name);
		}
	}
}			
?>
