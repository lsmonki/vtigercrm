<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/
require_once('include/utils/CommonUtils.php');
if(isset($_REQUEST['templateid']) && $_REQUEST['templateid'] !='')
{
	$templatedetails = getTemplateDetails($_REQUEST['templateid']);
	//$details['Email Information'][2][0][3][0] = $templatedetails[2]; //Subject
	//$details['Email Information'][3][0][3][0] = nl2br($templatedetails[1]); //Body
}
?>
<form name="frmrepstr">
<input type="hidden" name="subject" value="<?php echo $templatedetails[2];?>"></input>
<textarea name="repstr" style="visibility:hidden">
<?php echo nl2br($templatedetails[1]); ?>
</textarea>
</form>
<script language="javascript">
window.opener.document.getElementById('subject').value = window.document.frmrepstr.subject.value
window.opener.document.getElementById('description').value = window.document.frmrepstr.repstr.value
window.opener.oFCKeditor.ReplaceTextarea();
window.close();
</script>
