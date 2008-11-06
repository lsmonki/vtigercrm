<?php

/**
 * Add the performance index based on database.
 */

global $adb;
$old_dieOnError = $adb->dieOnError;
$adb->dieOnError = false;

/** We cannot create partial length index in postgres etc... */
$adb->pquery("CREATE INDEX messageid_idx ON vtiger_mailscanner_ids(messageid(255))", Array());

$adb->dieOnError = $old_dieOnError;

?>		
