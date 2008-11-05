<?php

include_once('vtlib/Vtiger/Utils.php');
include_once('vtlib/Vtiger/Field.php');
include_once('vtlib/Vtiger/Module.php');

class Vtiger_Field {

	var $parameters = Array();

	/**
	 * Get unique id for the field.
	 */
	static function getUniqueId() {
		global $adb;
		return $adb->getUniqueId('vtiger_field');
	}

	/**
	 * Create new field based on the parameters set.
	 */
	function create() {
		global $adb;

		$tabid = Vtiger_Module::getId($this->get('module'));

		$sqlresult = $adb->query("SELECT fieldid from vtiger_field where tablename = '"
			. Vtiger_Utils::SQLEscape($this->get('tablename')) . "' and columnname = '"
			. Vtiger_Utils::SQLEscape($this->get('columnname')) . "' and fieldname = '"
			. Vtiger_Utils::SQLEscape($this->get('fieldname')) .  "' and fieldlabel = '"
			. Vtiger_Utils::SQLEscape($this->get('fieldlabel')) . "' and tabid = '$tabid'"
		);

		$fieldid = $adb->query_result($sqlresult, 0, 'fieldid');

		// Avoid duplicate entries
		if(isset($fieldid)) return;

		$fieldid = self::getUniqueId();

		$columnname    = $this->get('columnname');
		$tablename     = $this->get('tablename');
		$generatedtype = $this->get('generatedtype');
		$uitype        = $this->get('uitype');
		$fieldname     = $this->get('fieldname');
		$fieldlabel    = $this->get('fieldlabel');
		$readonly      = $this->get('readonly');
		$presence      = $this->get('presence');
		$selected      = $this->get('selected');
		$maximumlength = $this->get('maximumlength');
		$sequence      = $this->get('sequence');
		$block         = $this->get('block');
		$displaytype   = $this->get('displaytype');
		$typeofdata    = $this->get('typeofdata');
		$quickcreate   = $this->get('quickcreate');
		$quickcreatesequence = $this->get('quickcreatesequence');
		$info_type     = $this->get('info_type');
		$columntype    = $this->get('columntype');

		// Set proper values for input if not sent
		if(is_null($generatedtype)) $generatedtype = 1;

		if(!isset($block)) {
			$blocklabel = $this->get('blocklabel');
			$sqlresult = $adb->query("select blockid from vtiger_blocks where tabid=$tabid and blocklabel='$blocklabel'");
			$block = $adb->query_result($sqlresult, 0, "blockid");
		}

		if(!isset($sequence)) {
			$sqlresult = $adb->query("select max(sequence) as max_sequence from vtiger_field where tabid=$tabid and block=$block");
			$sequence = $adb->query_result($sqlresult, 0, "max_sequence") + 1;
		}

		if(!isset($quickcreatesequence)) {
			if($quickcreate == '0') {
				$sqlresult = $adb->query("select max(quicksequence) as max_quickcreatesequence from vtiger_field where tabid=$tabid");
				$sequence = $adb->query_result($sqlresult, 0, "max_quickcreatesequence") + 1;
			} else {
				$quickcreatesequce = 'NULL';
			}
		}

		// Add the field entry
		$sql = "INSERT INTO vtiger_field 
			(tabid, fieldid, columnname, tablename, generatedtype, uitype, fieldname, fieldlabel, 
			readonly, presence, selected, maximumlength, sequence, block, displaytype, typeofdata, quickcreate, quickcreatesequence, info_type)
			values ($tabid, $fieldid, '$columnname', '$tablename', '$generatedtype', '$uitype', '$fieldname', '$fieldlabel', 
			'$readonly','$presence','$selected','$maximumlength','$sequence','$block','$displaytype','$typeofdata','$quickcreate','$quickcreatesequence','$info_type')";

		$adb->query($sql);

	
		// Make the field available to all the existing profiles.
		$adb->query("INSERT INTO vtiger_def_org_field (tabid, fieldid, visible, readonly) VALUES ($tabid, $fieldid, 0, 1)");
	
		$sqlresult = $adb->query("select profileid from vtiger_profile");
		$profilecnt = $adb->num_rows($sqlresult);
		for($pridx = 0; $pridx < $profilecnt; ++$pridx) {
			$profileid = $adb->query_result($sqlresult, $pridx, "profileid");
			$adb->query("INSERT INTO vtiger_profile2field (profileid, tabid, fieldid, visible, readonly) VALUES($profileid, $tabid, $fieldid, 0, 1)");
		}

		// Create the mapping table column
		if(!in_array($columnname, $adb->getColumnNames($tablename)) && isset($columntype)) {
			$columntype = $this->get('columntype');
			Vtiger_Utils::AlterTable($tablename, " ADD COLUMN $columnname $columntype");
		}

		Vtiger_Utils::Log("Adding " . $this->get('fieldlabel') . " to " . $this->get('module') . " ... DONE");
		Vtiger_Utils::Log("Check Module Language Mapping entry for " . $this->get('fieldlabel') . " ... TODO");
	}

	/**
	 * Set this field as module record (entity) identifier.
	 */
	function setEntityIdentifier() {
		global $adb;

		$tabid = Vtiger_Module::getId($this->get('module'));
		$modulename = $this->get('module');
		$tablename  = $this->get('tablename');
		$fieldname  = $this->get('fieldname');
		$entityfield= $this->get('entityidfield');
		$entityidcolumn=$this->get('entityidcolumn');

		// Add the field entry in vtiger_entityname (used by Tracker)
		$sql = "INSERT INTO vtiger_entityname(tabid, modulename, tablename, fieldname, entityidfield, entityidcolumn)
			VALUES ($tabid, '$modulename', '$tablename', '$fieldname', '$entityfield', '$entityidcolumn')";
		$adb->query($sql);
	}

	function get($key) {
		return $this->parameters[$key];
	}

	function set($key, $value) {
		$this->parameters[$key] = $value;
		return $this;
	}

	function setupPicklistValues($values) {
		global $adb;

		$fieldname = $this->get('fieldname');

		$this_picklist_table = "vtiger_$fieldname";
		$this_picklist_idcol = $fieldname . "id";

		if(Vtiger_Utils::CheckTable($this_picklist_table) === false) {
			Vtiger_Utils::CreateTable(
				$this_picklist_table,
				"($this_picklist_idcol INT NOT NULL AUTO_INCREMENT,
				$fieldname VARCHAR(200) NOT NULL,
				presence INT (1) NOT NULL DEFAULT 1,
				picklist_valueid INT NOT NULL DEFAULT 0,
				PRIMARY KEY ($this_picklist_idcol))"
			);
			$new_picklistid = $adb->getUniqueId('vtiger_picklist');
			$adb->query("INSERT INTO vtiger_picklist (picklistid, name)
				VALUES ($new_picklistid, '$fieldname')");

			Vtiger_Utils::Log("Creating picklist table $this_picklist_table ... DONE");
		} else {
			$new_picklistid = $adb->query_result(
				$adb->query("SELECT picklistid FROM vtiger_picklist WHERE name = '$fieldname'"), 0, 'picklistid');
		}


		// Add value to picklist
		$sortid = 0; // TODO To be set based on role.
		foreach($values as $value) {
			$new_picklistvalueid = getUniquePicklistID();
			$presence = 1; // 0 - readonly
			// Refer function in include/ComboUtil.php

			$new_id = $adb->getUniqueId($this_picklist_table);
			$adb->query("INSERT INTO $this_picklist_table
				($this_picklist_idcol, $fieldname, presence, picklist_valueid)
				VALUES ($new_id, '$value', $presence, $new_picklistvalueid)");
			
			++$sortid;

			// Associate picklist values to all the role
			$adb->query("INSERT INTO vtiger_role2picklist (roleid, picklistvalueid, picklistid, sortid)
				SELECT roleid, $new_picklistvalueid, $new_picklistid, $sortid FROM vtiger_role");
		}
	}
}

?>
