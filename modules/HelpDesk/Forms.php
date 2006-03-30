<?php
//Form for quick create should be done here

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
 * $Header: /advent/projects/wesat/vtiger_crm/vtigercrm/modules/HelpDesk/Forms.php,v 1.9 2005/03/25 10:21:31 mickie Exp $
 * Description:  Contains a variety of utility functions specific to this module.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
require_once('include/utils/utils.php');
require_once('include/ComboUtil.php');


/**
 * Create HTML form to enter a new record with the minimum necessary fields.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_new_record_form () 
{
	global $mod_strings;
	global $app_strings;
	global $app_list_strings;
	global $current_user;
	global $adb;//for dynamic quickcreateform construction


	$lbl_required_symbol = $app_strings['LBL_REQUIRED_SYMBOL'];
	$lbl_ticket_title = $mod_strings['LBL_TICKET_TITLE'];
	$lbl_ticket_description = $mod_strings['LBL_TICKET_DESCRIPTION'];
	$lbl_ticket_category = $mod_strings['LBL_TICKET_CATEGORY'];
	$lbl_ticket_priority = $mod_strings['LBL_TICKET_PRIORITY'];
	$lbl_save_button_title = $app_strings['LBL_SAVE_BUTTON_TITLE'];
	$lbl_save_button_key = $app_strings['LBL_SAVE_BUTTON_KEY'];
	$lbl_save_button_label = $app_strings['LBL_SAVE_BUTTON_LABEL'];
	$default_parent_type= $app_list_strings['record_type_default_key'];

	$comboFieldNames = Array('ticketpriorities'=>'ticketpriorities_dom');
	$comboFieldArray = getComboArray($comboFieldNames);
	$user_id = $current_user->id;

	$qcreate_form = get_left_form_header($mod_strings['LBL_NEW_FORM_TITLE']);


	$qcreate_get_field="select * from field where tabid=13 and quickcreate=0 order by quickcreatesequence";
	$qcreate_get_result=$adb->query($qcreate_get_field);
	$qcreate_get_noofrows=$adb->num_rows($qcreate_get_result);

	$fieldName_array = Array();//for validation

	$qcreate_form.='<form name="TicketSave" onSubmit="return formValidate()" method="POST" action="index.php">';
	$qcreate_form.='<input type="hidden" name="module" value="HelpDesk">';
	$qcreate_form.='<input type="hidden" name="return_module" value="HelpDesk">';
	$qcreate_form.='<input type="hidden" name="record" value="">';
	$qcreate_form.='<input type="hidden" name="parent_type" value="'.$default_parent_type.'">';
	$qcreate_form.='<input type="hidden" name="assigned_user_id" value="'.$user_id.'">';
	$qcreate_form.='<input type="hidden" name="action" value="Save">';
	$qcreate_form.='<input type="hidden" name="return_action" value="DetailView">';

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
		$fldLabel_array = Array();
		$fldLabel_array[$fieldlabel] = $typeofdata;
		$fieldName_array['QCK_'.$fieldname] = $fldLabel_array;

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
