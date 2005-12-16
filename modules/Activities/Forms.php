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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Activities/Forms.php,v 1.7 2005/04/19 16:49:29 ray Exp $
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
require_once('include/ComboUtil.php');
require_once('include/utils/utils.php');
function get_validate_record_js () {
global $mod_strings;
global $app_strings;

$lbl_subject = $mod_strings['LBL_LIST_SUBJECT'];
$lbl_date = $mod_strings['LBL_LIST_DUE_DATE'];
$lbl_time = $mod_strings['LBL_LIST_DUE_TIME'];
$err_missing_required_fields = $app_strings['ERR_MISSING_REQUIRED_FIELDS'];
$err_invalid_email_address = $app_strings['ERR_INVALID_EMAIL_ADDRESS'];
$err_invalid_date_format = $app_strings['ERR_INVALID_DATE_FORMAT'];
$err_invalid_month = $app_strings['ERR_INVALID_MONTH'];
$err_invalid_day = $app_strings['ERR_INVALID_DAY'];
$err_invalid_year = $app_strings['ERR_INVALID_YEAR'];
$err_invalid_date = $app_strings['ERR_INVALID_DATE'];
$err_invalid_time = $app_strings['ERR_INVALID_TIME'];

$the_script  = <<<EOQ

<script type="text/javascript" language="Javascript">
<!--  to hide script contents from old browsers
/**
 * DHTML date validation script. Courtesy of SmartWebby.com (http://www.smartwebby.com/dhtml/)
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
// Declaring valid date character, minimum year and maximum year

var fieldname,fieldlabel,fielddatatype;	
function verify_data(form,fname,flabel,fdatatype) 
{
	var form_name=form.name;
	if(form_name=='ActivitySave')
        {
               form.due_date.value=form.date_start.value;
	       fieldname =fname.split(",");
	       fieldlabel = flabel.split(",");
               fielddatatype = fdatatype.split(",");
        }
        else
        {
              form.due_date.value=form.date_start.value;
	      fieldname = fname.split(",");
              fieldlabel = flabel.split(",");
              fielddatatype = fdatatype.split(",");
        
        }

	var ret = formValidate();
	return ret;
}
// end hiding contents from old browsers  -->
</script>

EOQ;

return $the_script;
}

/* Commented for RC
/**
 * Create HTML form to enter a new record with the minimum necessary fields.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_new_record_form () {
global $app_strings, $mod_strings, $app_list_strings;
global $current_user;
global $theme;
global $adb;//for dynamic quickcreateform construction

// Unimplemented until jscalendar language files are fixed
// global $current_language;
// global $default_language;
// global $cal_codes;

$user_id = $current_user->id;
// Unimplemented until jscalendar language files are fixed
// $cal_lang = (empty($cal_codes[$current_language])) ? $cal_codes[$default_language] : $cal_codes[$current_language];
$cal_lang = "en";
$cal_dateformat = parse_calendardate($app_strings['NTC_DATE_FORMAT']);
$value=date('Y-m-d');
$dis_value=getNewDisplayDate();
$curr_time = date('H:i');

//for task
$qcreate_form = get_left_form_header($app_strings['LBL_NEW_TASK']);
$fieldName_task = '';
$fieldLabel_task = '';
$fldDataType_task = '';

$qcreate_get_field="select * from field where tabid=9 and quickcreate=0 order by quickcreatesequence";
$qcreate_get_result=$adb->query($qcreate_get_field);
$qcreate_get_noofrows=$adb->num_rows($qcreate_get_result);
$fieldName_array_task = Array();//for validation

$qcreate_form .='<link rel="stylesheet" type="text/css" media="all" href="jscalendar/calendar-win2k-cold-1.css">';
$qcreate_form .='<script type="text/javascript" src="jscalendar/calendar.js"></script>';
$qcreate_form .='<script type="text/javascript" src="jscalendar/lang/calendar-'.$cal_lang.'.js"></script>';
$qcreate_form .='<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>';
$qcreate_form .='<form name="ActivitySave" method="POST" action="index.php">';
$qcreate_form .='<input type="hidden" name="module" value="Activities">';
$qcreate_form .='<input type="hidden" name="record" value="">';
$qcreate_form .='<input type="hidden" name="activity_mode" value="Task">';
$qcreate_form .='<input type="hidden" name="assigned_user_id" value="'.$user_id.'">';
$qcreate_form .='<input type="hidden" name="action" value="Save">';
$qcreate_form .='<input type="hidden" name="due_date" value="">';

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
	if($fieldname == "date_start")
	{
		$typeofdata = 'DT~M~task_time_start';
	}
	
	//to get validationdata
	//start
	$fldLabel_array_task = Array();
        $fldLabel_array_task[$fieldlabel] = $typeofdata;
        $fieldName_array_task['QCK_T_'.$fieldname] = $fldLabel_array_task;
	
	//end
	
	$qcreate_form.='</tr>';
}

//for validation
$validationData = $fieldName_array_task;

$rows = count($validationData);
foreach($validationData as $fldName => $fldLabel_array_task)
{
   if($fieldName_task == '')
   {
     $fieldName_task="'".$fldName;
   }
   else
   {
     $fieldName_task .= ",".$fldName;
   }
   foreach($fldLabel_array_task as $fldLabel => $datatype)
   {
	if($fieldLabel_task == '')
	{
			
     		$fieldLabel_task = "'".$fldLabel;
	}		
        else
        {
       		$fieldLabel_task .= ",".$fldLabel;
        }
 	if($fldDataType_task == '')
        {
      		$fldDataType_task = "'".$datatype;
    	}
	else
        {
       		$fldDataType_task .= ",".$datatype;
     	}
   }
 }
   $fieldName_task .= "'";
   $fieldLabel_task .= "'";
   $fldDataType_task .= "'";
     
    
     


$qcreate_form.='</table>';

$qcreate_form.='<input title="'.$app_strings["LBL_SAVE_BUTTON_TITLE"].'" accessKey="'.$app_strings["LBL_SAVE_BUTTON_KEY"].'" class="button" onclick="return verify_data(ActivitySave,'.$fieldName_task.','.$fieldLabel_task.','.$fldDataType_task.')" type="submit" name="button" value="'.$app_strings["LBL_SAVE_BUTTON_LABEL"].'" >';
$qcreate_form.='</form>';
$qcreate_form.='<script type="text/javascript">
		Calendar.setup ({
			inputField : "QCK_T_date_start", ifFormat : "'.$cal_dateformat.'", showsTime : false, button : "jscal_trigger_date_start", singleClick : true, step : 1
		});
		
		</script>';
		

//for event
$qcreate_form .= get_left_form_footer();
$qcreate_form .='<br>';

$comboFieldNames = Array('activitytype'=>'activitytype_dom',
			 'duration_minutes'=>'duration_minutes_dom');
$comboFieldArray = getComboArray($comboFieldNames);

$qcreate_form .= get_left_form_header($app_strings['LBL_NEW_EVENT']);
$fieldName = '';
$fieldLabel = '';
$fldDataType = '';

$qcreate_get_field="select * from field where tabid=16 and quickcreate=0 order by quickcreatesequence";
$qcreate_get_result=$adb->query($qcreate_get_field);
$qcreate_get_noofrows=$adb->num_rows($qcreate_get_result);

$fieldName_array = Array();//for validation

$qcreate_form.='<form name="EventSave" method="POST" action="index.php">'; 
$qcreate_form.='<input type="hidden" name="module" value="Activities">';
$qcreate_form.='<input type="hidden" name="record" value="">';
$qcreate_form.='<input type="hidden" name="activity_mode" value="Events">';
$qcreate_form.='<input type="hidden" name="assigned_user_id" value="'.$user_id.'">';
$qcreate_form.='<input type="hidden" name="action" value="Save">';
$qcreate_form.='<input type="hidden" name="due_date" value="">';
$qcreate_form.='<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>';

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

	if($fieldname == "date_start")
	{
		$typeofdata = 'DT~M~event_time_start';
	}
	
	//to get validationdata
	//start
	$fldLabel_array = Array();
        $fldLabel_array[$fieldlabel] = $typeofdata;
        $fieldName_array['QCK_E_'.$fieldname] = $fldLabel_array;
	
	//end
	
	$qcreate_form.='</tr>';

}

//for validation
$validationData = $fieldName_array;

$rows = count($validationData);
foreach($validationData as $fldName => $fldLabel_array)
{
   if($fieldName == '')
   {
     $fieldName="'".$fldName;
   }
   else
   {
     $fieldName .=",".$fldName;
   }
   foreach($fldLabel_array as $fldLabel => $datatype)
   {
	if($fieldLabel == '')
	{
			
     		$fieldLabel = "'".$fldLabel;
	}		
        else
        {
       		$fieldLabel .= ",".$fldLabel;
        }
 	if($fldDataType == '')
        {
      		$fldDataType = "'".$datatype;
    	}
	else
        {
       		$fldDataType .= ",".$datatype;
     	}
   }
 }
   $fieldName .= "'";
   $fieldLabel .= "'";
   $fldDataType .= "'";
 
$qcreate_form.='</table>';

$qcreate_form.='<input title="'.$app_strings["LBL_SAVE_BUTTON_TITLE"].'" accessKey="'.$app_strings["LBL_SAVE_BUTTON_KEY"].'" class="button" onclick="return verify_data(EventSave,'.$fieldName.','.$fieldLabel.','.$fldDataType.')" type="submit" name="button" value="'.$app_strings["LBL_SAVE_BUTTON_LABEL"].'" >';
$qcreate_form.='</form>';

$qcreate_form.='<script type="text/javascript">
		Calendar.setup ({
			inputField : "QCK_E_date_start", ifFormat : "'.$cal_dateformat.'", showsTime : false, button : "jscal_trigger_event_date_start", singleClick : true, step : 1
		});
				
		</script>';

$qcreate_form .= get_left_form_footer();
$qcreate_form .= get_validate_record_js();

return $qcreate_form;




}
?>
