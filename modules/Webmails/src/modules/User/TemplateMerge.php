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
require_once('modules/Users/UserInfoUtil.php');
require_once('modules/Emails/templates/testemailtemplateusage.php');
$replacedString = substituteTokens("".$_REQUEST["templatename"],$globals);
$replacedSubjectString = substituteTokens("".$_REQUEST["templatename"]."subject",$globals);
?>
<form name="frmrepstr">
<input type="text" name="rebstrSubject" value="<?php echo chop($replacedSubjectString)?>">
<textarea name="repstr" style="visibility:hidden">
<?php echo chop($replacedString); ?>
</textarea>
</form>
<script language="javascript">
opener.document.EditView.subject.value=document.frmrepstr.rebstrSubject.value
opener.document.EditView.description.value=document.frmrepstr.repstr.value
opener.document.EditView.description.value=document.frmrepstr.repstr.value
opener.oFCKeditor.ReplaceTextarea() ;
window.close()
</script>

