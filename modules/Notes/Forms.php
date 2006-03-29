<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Notes/Forms.php,v 1.5 2005/02/19 12:42:23 jack Exp $
 * Description:  Contains a variety of utility functions specific to this module.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

/**
 * Create javascript to validate the data entered into a record.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */

require_once('include/utils/utils.php'); //new

function get_validate_record_js () {
global $mod_strings;
global $app_strings;

$lbl_subject = $mod_strings['LBL_LIST_SUBJECT'];
$err_missing_required_fields = $app_strings['ERR_MISSING_REQUIRED_FIELDS'];

$the_script  = <<<EOQ

<script type="text/javascript" language="Javascript">
<!--  to hide script contents from old browsers

function verify_data(form) {
	var isError = false;
	var errorMessage = "";
	if (trim(form.title.value) == "") {
		isError = true;
		errorMessage += "\\n$lbl_subject";
	}
	// Here we decide whether to submit the form.
	if (isError == true) {
		alert("$err_missing_required_fields" + errorMessage);
		return false;
	}
	return true;
}
// end hiding contents from old browsers  -->
</script>

EOQ;

return $the_script;
}

/**
 * Create HTML form to enter a new record with the minimum necessary fields.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_new_record_form () {
global $mod_strings;
global $app_strings;
global $app_list_strings;
global $adb;//for dynamic quickcreateform construction


$lbl_required_symbol = $app_strings['LBL_REQUIRED_SYMBOL'];
$lbl_note_subject = $mod_strings['LBL_NOTE_SUBJECT'];
$lbl_save_button_title = $app_strings['LBL_SAVE_BUTTON_TITLE'];
$lbl_save_button_key = $app_strings['LBL_SAVE_BUTTON_KEY'];
$lbl_save_button_label = $app_strings['LBL_SAVE_BUTTON_LABEL'];
$default_parent_type= $app_list_strings['record_type_default_key'];


$qcreate_form = get_left_form_header($mod_strings['LBL_NEW_FORM_TITLE']);

$qcreate_get_field="select * from field where tabid=8 and quickcreate=0 order by quickcreatesequence";
$qcreate_get_result=$adb->query($qcreate_get_field);
$qcreate_get_noofrows=$adb->num_rows($qcreate_get_result);
$fieldName_array = Array();//for validation 

$qcreate_form.='<form name="NoteSave" onSubmit="return formValidate()" method="POST" action="index.php">';
$qcreate_form.='<input type="hidden" name="module" value="Notes">';
$qcreate_form.='<input type="hidden" name="record" value="">';
$qcreate_form.='<input type="hidden" name="parent_type" value="'.$default_parent_type.'">';
$qcreate_form.='<input type="hidden" name="action" value="Save">';			
$qcreate_form.='<table>';

for($j=0;$j<$qcreate_get_noofrows;$j++)
{
	$qcreate_form.='<tr>';
	$fieldlabel=$adb->query_result($qcreate_get_result,$j,'fieldlabel');
	$uitype=$adb->query_result($qcreate_get_result,$j,'uitype');
	$tabid=$adb->query_result($qcreate_get_result,$j,'tabid');
	
	$fieldname=$adb->query_result($qcreate_get_result,$j,'fieldname');//for validation
	$typeofdata=$adb->query_result($qcreate_get_result,$j,'typeofdata');//for validation
       	$qcreate_form .= get_quickcreate_form($fieldlabel,$uitype,$fieldname,$tabid);

	
	//to get validationdata
	//start
	$fldLabel_array = Array();
        $fldLabel_array[$fieldlabel] = $typeofdata;
        $fieldName_array['QCK_'.$fieldname] = $fldLabel_array;
	
	//end
	
	$qcreate_form.='</tr>';
	
}

//for validation
$validationData = $fieldName_array;
$fieldName = '';
$fieldLabel = '';
$fldDataType = '';

$rows = count($validationData);
foreach($validationData as $fldName => $fldLabel_array)
{
   if($fieldName == '')
   {
     $fieldName="'".$fldName."'";
   }
   else
   {
     $fieldName .= ",'".$fldName ."'";
   }
   foreach($fldLabel_array as $fldLabel => $datatype)
   {
	if($fieldLabel == '')
	{
			
     		$fieldLabel = "'".$fldLabel ."'";
	}		
        else
        {
       		$fieldLabel .= ",'".$fldLabel ."'";
        }
 	if($fldDataType == '')
        {
      		$fldDataType = "'".$datatype ."'";
    	}
	else
        {
       		$fldDataType .= ",'".$datatype ."'";
     	}
   }
 }
 
$qcreate_form.='</table>';

$qcreate_form.='<input title="'.$lbl_save_button_title.'" accessKey="'.$lbl_save_button_key.'" class="button" type="submit" name="button" value="'.$lbl_save_button_label.'" >';
$qcreate_form.='</form>';
$qcreate_form.='<script type="text/javascript">
		
	var fieldname = new Array('.$fieldName.')
	var fieldlabel = new Array('.$fieldLabel.')
	var fielddatatype = new Array('.$fldDataType.')

		</script>';

$qcreate_form .= get_left_form_footer();
return $qcreate_form;

}

?>
