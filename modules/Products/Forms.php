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
 * $Header: /advent/projects/wesat/vtiger_crm/vtigercrm/modules/Products/Forms.php,v 1.3 2005/03/04 17:06:55 saraj Exp $
 * Description:  Contains a variety of utility functions used to display UI
 * components such as form headers and footers.  Intended to be modified on a per
 * theme basis.
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

require_once('include/ComboUtil.php');

function get_validate_record_js () {
global $mod_strings;
global $app_strings;

$lbl_product_name = $mod_strings['LBL_LIST_PRODUCT_NAME'];
$err_missing_required_fields = $app_strings['ERR_MISSING_REQUIRED_FIELDS'];
$err_invalid_email_address = $app_strings['ERR_INVALID_EMAIL_ADDRESS'];

$the_script  = <<<EOQ

<script type="text/javascript" language="Javascript">
<!--  to hide script contents from old browsers

function trim(s) {
	while (s.substring(0,1) == " ") {
		s = s.substring(1, s.length);
	}
	while (s.substring(s.length-1, s.length) == ' ') {
		s = s.substring(0,s.length-1);
	}

	return s;
}

function verify_data(form) {
	var isError = false;
	var errorMessage = "";
	if (trim(form.productname.value) == "") {
		isError = true;
		errorMessage += "\\n$lbl_product_name";
	}

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
global $current_user;
global $adb;//for dynamic quickcreateform construction


$lbl_required_symbol = $app_strings['LBL_REQUIRED_SYMBOL'];
$lbl_product_name = $mod_strings['LBL_PRODUCT_NAME'];
$lbl_product_code = $mod_strings['LBL_PRODUCT_CODE'];
$lbl_category = $mod_strings['LBL_CATEGORY'];
$lbl_save_button_title = $app_strings['LBL_SAVE_BUTTON_TITLE'];
$lbl_save_button_key = $app_strings['LBL_SAVE_BUTTON_KEY'];
$lbl_save_button_label = $app_strings['LBL_SAVE_BUTTON_LABEL'];
$user_id = $current_user->id;

//retreiving the combo values array
$comboFieldNames = Array('productcategory'=>'productcategory_dom');
$comboFieldArray = getComboArray($comboFieldNames);
$start_date = date("Y-m-d");

$qcreate_form = get_left_form_header($mod_strings['LBL_NEW_FORM_TITLE']);


$qcreate_get_field="select * from field where tabid=14 and quickcreate=0 order by quickcreatesequence";
$qcreate_get_result=$adb->query($qcreate_get_field);
$qcreate_get_noofrows=$adb->num_rows($qcreate_get_result);

$fieldName_array = Array();//for validation 


$qcreate_form.='<form name="ProductSave" onSubmit="return formValidate()" method="POST" action="index.php">';
$qcreate_form.='<input type="hidden" name="module" value="Products">';
$qcreate_form.='<input type="hidden" name="record" value="">';
$qcreate_form.='<input type="hidden" name="assigned_user_id" value="'.$user_id.'">';
$qcreate_form.='<input type="hidden" name="action" value="Save">';			
//$qcreate_form.='<input type="hidden" name="return_action" value="index">';
//$qcreate_form.='<input type="hidden" name="return_module" value="Products">';
$qcreate_form.='<input type="hidden" name="start_date" value="'.$start_date.'">';
$qcreate_form.='<input type="hidden" name="expiry_date" value="'.$start_date.'">';
$qcreate_form.='<input type="hidden" name="purchase_date" value="'.$start_date.'">';

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
