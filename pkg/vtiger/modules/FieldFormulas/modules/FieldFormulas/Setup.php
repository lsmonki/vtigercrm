<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

require_once('include/utils/utils.php');

global $adb;

$fieldid = $adb->getUniqueID('vtiger_settings_field');
$blockid = getSettingsBlockId('LBL_STUDIO');

$seq_res = $adb->query("SELECT max(sequence) AS max_seq FROM vtiger_settings_field");
$seq = 1;
if ($adb->num_rows($seq_res) > 0) {
	$cur_seq = $adb->query_result($seq_res, 0, 'max_seq');
	if ($cur_seq != null)	$seq = $cur_seq + 1;
}

$adb->pquery("INSERT INTO vtiger_settings_field(fieldid, blockid, name, iconpath, description, linkto, sequence) 
	VALUES (?,?,?,?,?,?,?)", array($fieldid, $blockid, 'Field Formulas', 'modules/FieldFormulas/resources/FieldFormulas.png', 'Add custom equations to custom fields', 'index.php?module=FieldFormulas&action=index&parenttab=Settings', $seq));

$tabid = getTabid('FieldFormulas');
if(isset($tabid) && $tabid!='') {
	$adb->pquery('DELETE FROM vtiger_profile2tab WHERE tabid = ?', array($tabid));
}
?>