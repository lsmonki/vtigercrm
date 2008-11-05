<?php

include_once('vtlib/Vtiger/Common.inc.php');
include_once('vtlib/Vtiger/Module.php');

class Vtiger_Block {

	static function create($module, $blocklabel) {
		global $adb;

		$blockid = self::getUniqueId();
		$tabid = Vtiger_Module::getId($module);
		$sequence = self::getNextSequence($tabid);
		$show_title = 0;
		$visible = 0;
		$create_view = 0;
		$edit_view = 0;
		$detail_view = 0;

		$blockquery = 
			"INSERT INTO vtiger_blocks(blockid,tabid,blocklabel,sequence,
				show_title,visible,create_view,edit_view,detail_view)
				VALUES($blockid, $tabid, '$blocklabel', $sequence, $show_title,
					$visible, $create_view, $edit_view, $detail_view)";

		$adb->query($blockquery);

		Vtiger_Utils::Log("Creating block $module ($blocklabel) ... DONE");
		Vtiger_Utils::Log("Check Module Language Mapping entry for $blocklabel ... TODO");
	}

	/**
	 * Generate the unique tab id.
	 */
	static function getUniqueId() {
		global $adb;
		$blocksql = "SELECT MAX(blockid) as max_blockid from vtiger_blocks";
		$blockres = $adb->query($blocksql);
		$blockid  = $adb->query_result($blockres, 0, 'max_blockid');
		return ++$blockid;
	}

	static function getNextSequence($tabid) {
		global $adb;
		$blocksql = "SELECT MAX(sequence) as max_sequence from vtiger_blocks where tabid = $tabid";
		$blockres = $adb->query($blocksql);
		$blockseq = $adb->query_result($blockres, 0, 'max_sequence');
		if($blockseq === null || $blockseq == '') $blockseq = 0;
		return ++$blockseq;
	}
}

?>
