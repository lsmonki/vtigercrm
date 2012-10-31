{*<!--
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/
-->*}
<form method="POST" action="index.php" enctype="multipart/form-data" onsubmit="VtigerJS_DialogBox.block();" id="ical_import" name="ical_import">
		<input type="hidden" value="{$MODULE}" name="module">
		<input type="hidden" value="{$VIEW}" name="view">
		<input type="hidden" value="{$MODE}" name="mode">
		<input type="file" id="import_file" name="import_file" class="small">
		<input type="submit" name="submit" value="Import">
</form>