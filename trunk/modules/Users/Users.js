/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
 ********************************************************************************/



document.write("<script type='text/javascript' src='include/js/Mail.js'></"+"script>");
function set_return(user_id, user_name) {
		window.opener.document.EditView.reports_to_name.value = user_name;
		window.opener.document.EditView.reports_to_id.value = user_id;
}

