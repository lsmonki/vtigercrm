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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/include/utils/EditViewUtils.php,v 1.188 2005/04/29 05:5 * 4:39 rank Exp  
 * Description:  Includes generic helper functions used throughout the application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/



/** This function returns the name of the person.
  * It currently returns "first last".  It should not put the space if either name is not available.
  * It should not return errors if either name is not available.
  * If no names are present, it will return ""
  * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
  * All Rights Reserved.
  * Contributor(s): ______________________________________..
  */

require_once('include/database/PearDatabase.php');
require_once('include/ComboUtil.php'); //new
require_once('include/utils/CommonUtils.php'); //new

function getOutputHtml($uitype, $fieldname, $fieldlabel, $maxlength, $col_fields,$generatedtype,$module_name)
{
	global $adb,$log;
	global $theme;
	global $mod_strings;
	global $app_strings;
	global $current_user;
	global $noof_group_rows;
        $theme_path="themes/".$theme."/";
        $image_path=$theme_path."images/";
	$fieldlabel = from_html($fieldlabel);
	$fieldvalue = Array();
	$final_arr = Array();
        $value = $col_fields[$fieldname];
	$custfld = '';
	$ui_type[]= $uitype;
	$editview_fldname[] = $fieldname;

	if($generatedtype == 2)
		$mod_strings[$fieldlabel] = $fieldlabel;

	if($uitype == 5 || $uitype == 6 || $uitype ==23)
	{	
		$log->info("uitype is ".$uitype);
		if($value=='')
                {
			if($fieldname != 'birthday')// && $fieldname != 'due_date')//due date is today's date by default
                        	$disp_value=getNewDisplayDate();

			//Added to display the Contact - Support End Date as one year future instead of today's date -- 30-11-2005
                        if($fieldname == 'support_end_date' && $_REQUEST['module'] == 'Contacts')
                        {
                        	$addyear = strtotime("+1 year");
                                global $current_user;
                                $dat_fmt = (($current_user->date_format == '')?('dd-mm-yyyy'):($current_user->date_format));

                                $disp_value = (($dat_fmt == 'dd-mm-yyyy')?(date('d-m-Y',$addyear)):(($dat_fmt == 'mm-dd-yyyy')?(date('m-d-Y',$addyear)):(($dat_fmt == 'yyyy-mm-dd')?(date('Y-m-d', $addyear)):(''))));
			}
                }
                else
                {
                        $disp_value = getDisplayDate($value);
                }

		$custfld .= '<td width="20%" class="dataLabel">';

		if($uitype == 6 || ($uitype == 23 && $fieldname =='closingdate'))
			$custfld .= '<font color="red">*</font>';

		$editview_label[]=$mod_strings[$fieldlabel];
		$date_format = parse_calendardate($app_strings['NTC_DATE_FORMAT']);
		$custfld .= '<td width="30%"><input name="'.$fieldname.'" id="jscal_field_'.$fieldname.'" type="text" size="11" maxlength="10" value="'.$disp_value.'"> <img src="themes/'.$theme.'/images/calendar.gif" id="jscal_trigger_'.$fieldname.'">';
		if($uitype == 6)
                {
			if($col_fields['time_start']!='')
                        {
                                $curr_time = $col_fields['time_start'];
                        }
                        else
                        {
                                $curr_time = date('H:i');
                        }
                        $custfld .= '&nbsp; <input name="time_start" size="5" maxlength="5" type="text" value="'.$curr_time.'">';
                }
		if($uitype == 5 || $uitype == 23)
			$custfld .= '<br><font size=1><em old="(yyyy-mm-dd)">('.$current_user->date_format.')</em></font></td>';
		else
			$custfld .= '<br><font size=1><em old="(yyyy-mm-dd 24:00)">('.$current_user->date_format.' '.$app_strings['YEAR_MONTH_DATE'].')</em></font></td>';
		$custfld .= '<script type="text/javascript">';
		$custfld .= 'Calendar.setup ({';
				$custfld .= 'inputField : "jscal_field_'.$fieldname.'", ifFormat : "'.$date_format.'", showsTime : false, button : "jscal_trigger_'.$fieldname.'", singleClick : true, step : 1';
				$custfld .= '});';
		$custfld .= '</script>';
		$fieldvalue[] = array($disp_value => $curr_time) ;
	}
	elseif($uitype == 15 || $uitype == 16)
	{
		$custfld .= '<td width="20%" class="dataLabel">';

		if($uitype == 16)
			$custfld .= '<font color="red">*</font>';

		$editview_label[]=$mod_strings[$fieldlabel];
		$pick_query="select * from ".$fieldname;
		$pickListResult = $adb->query($pick_query);
		$noofpickrows = $adb->num_rows($pickListResult);
		$custfld .= '<td width="30%"><select name="'.$fieldname.'">';
		
		//Mikecrowe fix to correctly default for custom pick lists
		$options = array();
		$found = false;
		for($j = 0; $j < $noofpickrows; $j++)
		{
			$pickListValue=$adb->query_result($pickListResult,$j,strtolower($fieldname));
			
			if($value == $pickListValue)
 			{
 				$chk_val = "selected";	
               			$found = true;
 			}
 			else
 			{	
 				$chk_val = '';
 			}
  			$options[] = array($pickListValue=>$chk_val );	
             		if ( $j == 0 )
                 		$soption = '<OPTION value="'.$pickListValue.'" selected>'.$pickListValue.'</OPTION>';
  		}
		$fieldvalue [] = $options;
	}
	elseif($uitype == 17)
	{
		$editview_label[]=$mod_strings[$fieldlabel];
		$custfld .= '<td>&nbsp;&nbsp;http://<input type=text name="'.$fieldname.'" size="19" value="'.$value.'"></td>';
		$fieldvalue [] = $value;
	}
	elseif($uitype == 19 || $uitype == 20)
	{
		if(isset($_REQUEST['body']))
		{
			$value = ($_REQUEST['body']);
		}

		$custfld .= '<td width="20%" class="dataLabel" valign="top">';
		if($uitype == 20)
                {
                        $custfld .= '<font color="red">*</font>';
                }
		if($fieldlabel == 'Terms & Conditions')//for default Terms & Conditions
                {
                       if($focus->mode=='edit') $value=getTermsandConditions();
                }

		$editview_label[]=$mod_strings[$fieldlabel];
        	$custfld .= '<td colspan=3><textarea name="'.$fieldname.'" cols="70" rows="8">'.$value.'</textarea></td>';
		$fieldvalue [] = $value;
	}
	elseif($uitype == 21 || $uitype == 24)
	{
		$custfld .= '<td width="20%" class="dataLabel" valign="top">';
                if($uitype == 24)
                {
                        $custfld .= '<font color="red">*</font>';
                }
		$editview_label[]=$mod_strings[$fieldlabel];
        	$fieldvalue [] = $value;
		$custfld .= '<td><textarea name="'.$fieldname.'" cols="30" rows="2">'.$value.'</textarea></td>';
	}
	elseif($uitype == 22)
	{
		$editview_label[]=$mod_strings[$fieldlabel];
        	$custfld .= '<td><textarea name="'.$fieldname.'" cols="30" rows="2">'.$value.'</textarea></td>';
		$fieldvalue[] = $value;
	}
	elseif($uitype == 52 || $uitype == 77)
	{
		$editview_label[]=$mod_strings[$fieldlabel];
		global $current_user;
		if($value != '')
		{
			$assigned_user_id = $value;	
		}
		else
		{
			$assigned_user_id = $current_user->id;
		}
		if($uitype == 52)
		{
			$combo_lbl_name = 'assigned_user_id';
		}
		elseif($uitype == 77)
		{
			$combo_lbl_name = 'assigned_user_id1';
		}

		global $others_permission_id;
		if($fieldlabel == 'Assigned To' && $others_permission_id == 3)
		{
			$users_combo = get_select_options_with_id(get_user_array(FALSE, "Active", $assigned_user_id,'private'), $assigned_user_id);
		}
		else
		{
			$users_combo = get_select_options_with_id(get_user_array(FALSE, "Active", $assigned_user_id), $assigned_user_id);
		}
                $custfld .= '<td width="30%"><select name="'.$combo_lbl_name.'">'.$users_combo.'</select></td>';
		$fieldvalue [] = $users_combo;
	}
	elseif($uitype == 53)     
	{  
	  $editview_label[]=$mod_strings[$fieldlabel];
          $result = get_group_options();
          $nameArray = $adb->fetch_array($result);
	  	
          
          global $current_user;
	  if($value != '' && $value != 0)
	  {
		  $assigned_user_id = $value;
		  $user_checked = "checked";
		  $team_checked = '';
		  $user_style='display:block';
		  $team_style='display:none';			
	  }
	  else
	  {
		  if($value=='0')
		  {
			  $record = $col_fields["record_id"];
			  $module = $col_fields["record_module"];

			  $selected_groupname = getGroupName($record, $module);
			  $user_checked = '';
		          $team_checked = 'checked';
			  $user_style='display:none';
		  	  $team_style='display:block';
		  }
		  else	
		  {				
			  $assigned_user_id = $current_user->id;
			  $user_checked = "checked";
		          $team_checked = '';
			  $user_style='display:block';
		  	  $team_style='display:none';
		  }	
	  }
        
	global $others_permission_id;
	if($fieldlabel == 'Assigned To' && $others_permission_id == 3)
	{
		$users_combo = get_select_options_with_id(get_user_array(FALSE, "Active", $assigned_user_id,'private'), $assigned_user_id);
	}
	else
	{
		$users_combo = get_select_options_with_id(get_user_array(FALSE, "Active", $assigned_user_id), $assigned_user_id);
	}
 
          
          $GROUP_SELECT_OPTION = '<td width=30%><input type="radio"
          name="assigntype" value="U" '.$user_checked.'
          onclick="toggleAssignType(this.value)">'.$app_strings['LBL_USER'];
		
	if($noof_group_rows!=0)
	{

          $log->debug("Has a Group, get the Radio button");
          $GROUP_SELECT_OPTION .= '<input
          type="radio" name="assigntype" value="T"'.$team_checked.'
          onclick="toggleAssignType(this.value)">'.$app_strings['LBL_TEAM'];
	}
	
          $GROUP_SELECT_OPTION .='<br><span
          id="assign_user" style="'.$user_style.'"><select name="assigned_user_id">';
          
          $GROUP_SELECT_OPTION .= $users_combo;
          
          $GROUP_SELECT_OPTION .= '</select></span>';
          
	if($noof_group_rows!=0)
	{
          $log->debug("Has a Group, getting the group names ");
          $GROUP_SELECT_OPTION .='<span id="assign_team" style="'.$team_style.'"><select name="assigned_group_name">';
          
          
          do
          {
            $groupname=$nameArray["groupname"];
	    $selected = '';	
	    if($groupname == $selected_groupname)
	    {
		$selected = "selected";
            }	
	    $group_option[] = array($groupname=>$selected);
	
          }while($nameArray = $adb->fetch_array($result));

	}
          
        	$fieldvalue[]=$users_combo;  
                $fieldvalue[] = $group_option;
        }
	elseif($uitype == 51 || $uitype == 50 || $uitype == 73)
	{
		if($_REQUEST['convertmode'] != 'update_quote_val' && $_REQUEST['convertmode'] != 'update_so_val')
		{
                	if(isset($_REQUEST['account_id']) && $_REQUEST['account_id'] != '')
                        	$value = $_REQUEST['account_id'];	
		}

		if($value != '')
		{		
			$account_name = getAccountName($value);	
		}
		$custfld .= '<td width="20%" class="dataLabel">';
		if($uitype==50 || $uitype==73)
			$custfld .= '<font color="red">*</font>';
		$editview_label[]=$mod_strings[$fieldlabel];

		if($uitype == 73)
		{
			$custfld .= '<td width="30%" valign="top"  class="dataField"><input readonly name="account_name" type="text" value="'.$account_name.'"><input name="account_id" type="hidden" value="'.$value.'">&nbsp;<img src="'.$image_path.'select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Accounts&action=Popup&popuptype=specific_account_address&form=TasksEditView&form_submit=false","test","width=600,height=400,resizable=1,scrollbars=1");\' align="absmiddle" style=\'cursor:hand;cursor:pointer\'></td>';
			
		}
		elseif($uitype == 50 && $module_name == 'Potentials')
                {
                        $custfld .= '<td width="30%" valign="top"  class="dataField"><input readonly name="account_name" type="text" value="'.$account_name.'"><input name="account_id" type="hidden" value="'.$value.'">&nbsp;<img src="'.$image_path.'select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Accounts&action=Popup&popuptype=specific&form=TasksEditView&form_submit=false","test","width=600,height=400,resizable=1,scrollbars=1");\' align="absmiddle" style=\'cursor:hand;cursor:pointer\'></td>';

                }
		elseif($uitype == 51 && $module_name == 'Accounts')
                {
                        $custfld .= '<td width="30%" valign="top"  class="dataField"><input readonly name="account_name" type="text" value="'.$account_name.'"><input name="account_id" type="hidden" value="'.$value.'">&nbsp;<img src="'.$image_path.'select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Accounts&action=Popup&popuptype=specific_account_address&form=TasksEditView&form_submit=false","test","width=600,height=400,resizable=1,scrollbars=1");\' align="absmiddle" style=\'cursor:hand;cursor:pointer\'>&nbsp;<input type="image" src="'.$image_path.'clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.account_id.value=\'\';this.form.account_name.value=\'\';return false;" align="absmiddle" style=\'cursor:hand;cursor:pointer\'></td>';

                }
		else
		{
		$custfld .= '<td width="30%" valign="top"  class="dataField"><input readonly name="account_name" type="text" value="'.$account_name.'"><input name="account_id" type="hidden" value="'.$value.'">&nbsp;<img src="'.$image_path.'select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Accounts&action=Popup&popuptype=specific_contact_account_address&form=TasksEditView&form_submit=false","test","width=600,height=400,resizable=1,scrollbars=1");\' align="absmiddle" style=\'cursor:hand;cursor:pointer\'>&nbsp;<input type="image" src="'.$image_path.'clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.account_id.value=\'\';this.form.account_name.value=\'\';return false;" align="absmiddle" style=\'cursor:hand;cursor:pointer\'></td>';
		}	
		$fieldvalue[]=$account_name;
		$fieldvalue[] = $value;
	}
	elseif($uitype == 54)
	{
		$options =Array();
		$editview_label[]=$mod_strings[$fieldlabel];
		$pick_query="select * from groups";
		$pickListResult = $adb->query($pick_query);
		$noofpickrows = $adb->num_rows($pickListResult);
		$custfld .= '<td width="30%"><select name="'.$fieldname.'">';
		$custfld .= '<OPTION value="selectagroup" selected>'.$app_strings['LBL_SELECT_GROUP'].'</OPTION>';
		for($j = 0; $j < $noofpickrows; $j++)
		{
			$pickListValue=$adb->query_result($pickListResult,$j,"name");

			if($value == $pickListValue)
			{
				$chk_val = "selected";	
			}
			else
			{	
				$chk_val = '';	
			}
			$options[] = array($pickListValue => $chk_val );
			$custfld .= '<OPTION value="'.$pickListValue.'" '.$chk_val.'>'.$pickListValue.'</OPTION>';
		}
		$custfld .= '</td>';
		$fieldvalue[] = $options;
		
	}
	elseif($uitype == 55)
	{
		$editview_label[]=$mod_strings[$fieldlabel];
		$options = Array();
		$pick_query="select * from salutationtype order by sortorderid";
		$pickListResult = $adb->query($pick_query);
		$noofpickrows = $adb->num_rows($pickListResult);
		$salt_value = $col_fields["salutationtype"];
		$custfld .= '<td width="30%"><select name="salutationtype">';
		for($j = 0; $j < $noofpickrows; $j++)
		{
			$pickListValue=$adb->query_result($pickListResult,$j,"salutationtype");
			
			if($salt_value == $pickListValue)
			{
				$chk_val = "selected";	
			}
			else
			{	
				$chk_val = '';	
			}
			$options[] = array($pickListValue => $chk_val );
			$custfld .= '<OPTION value="'.$pickListValue.'" '.$chk_val.'>'.$pickListValue.'</OPTION>';
		}
		$custfld .= '</select><input name="'.$fieldname.'" type="text" size="25" maxlength="'.$maxlength.'" value="'.$value.'"></td>';
		$fieldvalue[] = $options;
		$fieldvalue[] = $value;
	}
	elseif($uitype == 59)
	{
		if($_REQUEST['module'] == 'HelpDesk')
                {
                        if(isset($_REQUEST['product_id']) & $_REQUEST['product_id'] != '')
                                $value = $_REQUEST['product_id'];
                }
                elseif(isset($_REQUEST['parent_id']) & $_REQUEST['parent_id'] != '')
                        $value = $_REQUEST['parent_id'];

		if($value != '')
		{		
			$product_name = getProductName($value);	
		}
               $custfld .= '<td width="20%" class="dataLabel">';
		$editview_label[]=$mod_strings[$fieldlabel];
               $custfld .= '<td width="30%"><input name="product_id" type="hidden" value="'.$value.'"><input name="product_name" readonly type="text" value="'.$product_name.'">&nbsp;<img src="'.$image_path.'select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Products&action=Popup&html=Popup_picker&form=HelpDeskEditView&popuptype=specific","test","width=600,height=400,resizable=1,scrollbars=1,top=150,left=200");\' align="absmiddle" style=\'cursor:hand;cursor:pointer\'>&nbsp;<input type="image" src="'.$image_path.'clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.product_id.value=\'\';this.form.product_name.value=\'\';return false;" align="absmiddle" style=\'cursor:hand;cursor:pointer\'></td>';
	$fieldvalue[]=$product_name;
	$fieldvalue[]=$value;
	}
	elseif($uitype == 63)
        {
		//$custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
		$editview_label[]=$mod_strings[$fieldlabel];
                if($value=='')
                $value=1;
		$options = Array();
                $custfld .= '<td width="30%"><input name="'.$fieldname.'" type="text" size="2" maxlength="'.$maxlength.'" value="'.$value.'">&nbsp;';
                $pick_query="select * from duration_minutes order by sortorderid";
                $pickListResult = $adb->query($pick_query);
                $noofpickrows = $adb->num_rows($pickListResult);
                $salt_value = $col_fields["duration_minutes"];
                $custfld .= '<select name="duration_minutes">';
                for($j = 0; $j < $noofpickrows; $j++)
                {
                        $pickListValue=$adb->query_result($pickListResult,$j,"duration_minutes");

                        if($salt_value == $pickListValue)
                        {
                                $chk_val = "selected";
                        }
                        else
                        {
                                $chk_val = '';
                        }
			$options[] = array($pickListValue => $chk_val );
                        $custfld .= '<OPTION value="'.$pickListValue.'" '.$chk_val.'>'.$pickListValue.'</OPTION>';
                }
                $custfld .= '</select>';
                $custfld .= $app_strings['LBL_HOUR_AND_MINUTE'].'</td>';
		 $fieldvalue[]=$value;
		 $fieldvalue[]=$options;
        }
	elseif($uitype == 64)
        {
                $custfld .= '<td width="20%" class="dataLabel">';
		$editview_label[]=$mod_strings[$fieldlabel];
                $date_format = parse_calendardate($app_strings['NTC_DATE_FORMAT']);
                $custfld .= '<td width="30%"><input name="'.$fieldname.'" id="jscal_field" type="text" size="11" readonly maxlength="10" value="'.$value.'"> <img src="themes/'.$theme.'/images/calendar.gif" id="jscal_trigger">&nbsp;<input name="duetime" size="5" maxlength="5" readonly type="text" value=""> <input name="duedate_flag" type="checkbox" language="javascript" onclick="set_values(this.form)" checked>'.$mod_strings["LBL_NONE"].'<br><font size="1"><em>'.$mod_strings["DATE_FORMAT"].'</em></font></td>';
                $custfld .= '<script type="text/javascript">';
                $custfld .= 'Calendar.setup ({';
                                $custfld .= 'inputField : "jscal_field", ifFormat : "'.$date_format.'", showsTime : false, button : "jscal_trigger", singleClick : true, step : 1';
                                $custfld .= '});';
                $custfld .= '</script>';
		$fieldvalue[] = $value;
        }
	elseif($uitype == 56)
	{
		$editview_label[]=$mod_strings[$fieldlabel];

		if($fieldname == 'notime' && $module_name =='Events' )
		{
			if($value == 1)
			{
				$custfld .='<td width="30%"><input name="'.$fieldname.'" type="checkbox"  onclick="toggleTime()" checked></td>';
			}
			else
			{
				$custfld .='<td width="30%"><input name="'.$fieldname.'" type="checkbox" onclick="toggleTime()" ></td>';
			}
		}
		else
		{
			if($value == 1)
			{
				$custfld .='<td width="30%"><input name="'.$fieldname.'" type="checkbox"  checked></td>';
			}else
			{
				$custfld .='<td width="30%"><input name="'.$fieldname.'" type="checkbox"></td>';
			}
			
		}
		
		
		$fieldvalue[] = $value;	
	}
	elseif($uitype == 57)
	{

	       if($value != '')
               {
                       $contact_name = getContactName($value);
               }
	       elseif(isset($_REQUEST['contact_id']) && $_REQUEST['contact_id'] != '')
	       {
			if($_REQUEST['module'] == 'Contacts' && $fieldname = 'contact_id')
			{
				$contact_name = '';	
			}
			else
			{
				$value = $_REQUEST['contact_id'];
				$contact_name = getContactName($value);		
			}
			
	       }
	
		//Checking for contacts duplicate
					 	
		$editview_label[]=$mod_strings[$fieldlabel];
		$custfld .= '<td width="30%"><input name="contact_name" readonly type="text" value="'.$contact_name.'"><input name="contact_id" type="hidden" value="'.$value.'">&nbsp;<img src="'.$image_path.'select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Contacts&action=Popup&html=Popup_picker&popuptype=specific&form=EditView","test","width=600,height=400,resizable=1,scrollbars=1");\' align="absmiddle" style=\'cursor:hand;cursor:pointer\'>&nbsp;<input type="image" src="'.$image_path.'clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.contact_id.value=\'\';this.form.contact_name.value=\'\';return false;" align="absmiddle" style=\'cursor:hand;cursor:pointer\'></td>';
		$fieldvalue[] = $contact_name;
		$fieldvalue[] = $value;
	}
        elseif($uitype == 61 || $uitype == 69)
        {
                global $current_user;
                if($value != '')
                {
                        $assigned_user_id = $value;
                }
                else
                {
                        $assigned_user_id = $current_user->id;
                }
                if($value!='')
                        $filename=' [ '.$value. ' ]';
		$editview_label[]=$mod_strings[$fieldlabel];
                $custfld .='<td colspan="3"><input name="'.$fieldname.'" type="file" size="60" value="'.$value.'"/><input type="hidden" name="filename" value=""/><input type="hidden" name="id" value=""/>'.$filename.'</td>';
		$fieldvalue[] = $filename;
		$fieldvalue[] = $value;
        }
        elseif($uitype == 62)
        {
                if(isset($_REQUEST['parent_id']) && $_REQUEST['parent_id'] != '')
                        $value = $_REQUEST['parent_id'];

		if($value != '')
		{
			$parent_module = getSalesEntityType($value);
			if($parent_module == "Leads")
			{
				$sql = "select * from leaddetails where leadid=".$value;
				$result = $adb->query($sql);
				$first_name = $adb->query_result($result,0,"firstname");
				$last_name = $adb->query_result($result,0,"lastname");
				$parent_name = $last_name.' '.$first_name;
				$lead_selected = "selected";

			}
			elseif($parent_module == "Accounts")
			{
				$sql = "select * from  account where accountid=".$value;
				$result = $adb->query($sql);
				$parent_name = $adb->query_result($result,0,"accountname");
				$account_selected = "selected";

			}
			elseif($parent_module == "Potentials")
			{
				$sql = "select * from  potential where potentialid=".$value;
				$result = $adb->query($sql);
				$parent_name = $adb->query_result($result,0,"potentialname");
				$contact_selected = "selected";

			}
			elseif($parent_module == "Products")
			{
				$sql = "select * from  products where productid=".$value;
				$result = $adb->query($sql);
				$parent_name= $adb->query_result($result,0,"productname");
				$product_selected = "selected";

			}
			elseif($parent_module == "PurchaseOrder")
                        {
                                $sql = "select * from  purchaseorder where purchaseorderid=".$value;
                                $result = $adb->query($sql);
                                $parent_name= $adb->query_result($result,0,"subject");
                                $porder_selected = "selected";

                        }
                        elseif($parent_module == "SalesOrder")
                        {
                                $sql = "select * from  salesorder where salesorderid=".$value;
                                $result = $adb->query($sql);
                                $parent_name= $adb->query_result($result,0,"subject");
                                $sorder_selected = "selected";

                        }
			elseif($parent_module == "Invoice")
                        {
                                $sql = "select * from  invoice where invoiceid=".$value;
                                $result = $adb->query($sql);
                                $parent_name= $adb->query_result($result,0,"subject");
                                $invoice_selected = "selected";

                        }


		}
		$custfld .= '<td width="20%" class="dataLabel"><select name="parent_type" onChange=\'document.EditView.parent_name.value=""; document.EditView.parent_id.value=""\'>';
                $custfld .= '<OPTION value="Leads&action=Popup" '.$lead_selected.'>'.$app_strings['COMBO_LEADS'].'</OPTION>';
                $custfld .= '<OPTION value="Accounts&action=Popup" '.$account_selected.'>'.$app_strings['COMBO_ACCOUNTS'].'</OPTION>';
                $custfld .= '<OPTION value="Potentials&action=Popup" '.$contact_selected.'>'.$app_strings['COMBO_POTENTIALS'].'</OPTION>';
		$custfld .= '<OPTION value="Products&action=Popup" '.$product_selected.'>'.$app_strings['COMBO_PRODUCTS'].'</OPTION>';
		$custfld .= '<OPTION value="Invoice&action=Popup" '.$Invoice_selected.'>'.$app_strings['COMBO_INVOICES'].'</OPTION>';
                $custfld .= '<OPTION value="PurchaseOrder&action=Popup" '.$porder_selected.'>'.$app_strings['COMBO_PORDER'].'</OPTION>';
                $custfld .= '<OPTION value="SalesOrder&action=Popup" '.$sorder_selected.'>'.$app_strings['COMBO_SORDER'].'</OPTION></select></td>';

 		$custfld .= '<td width="30%"><input name="parent_id" type="hidden" value="'.$value.'"><input name="parent_name" readonly type="text" value="'.$parent_name.'">&nbsp;<img src="'.$image_path.'select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick=\'return window.open("index.php?module="+ document.EditView.parent_type.value +"&html=Popup_picker&form=HelpDeskEditView","test","width=600,height=400,resizable=1,scrollbars=1,top=150,left=200");\' align="absmiddle" style=\'cursor:hand;cursor:pointer\'>&nbsp;<input type="image" src="'.$image_path.'clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.parent_id.value=\'\';this.form.parent_name.value=\'\';return false;" align="absmiddle" style=\'cursor:hand;cursor:pointer\'></td>';
		$editview_label[] = array($app_strings['COMBO_LEADS']=>$lead_selected,
                                          $app_strings['COMBO_ACCOUNTS']=>$account_selected,
                                          $app_strings['COMBO_POTENTIALS']=>$contact_selected,
                                          $app_strings['COMBO_PRODUCTS']=>$product_selected,
                                          $app_strings['COMBO_INVOICES']=>$Invoice_selected,
					  $app_strings['COMBO_PORDER']=>$porder_selected,
                                          $app_strings['COMBO_SORDER']=>$sorder_selected
                                        );
		$fieldvalue[] =$parent_name;
		$fieldvalue[] =$value;
		
        }
        elseif($uitype == 66)
        {
                if(isset($_REQUEST['parent_id']) && $_REQUEST['parent_id'] != '')
                        $value = $_REQUEST['parent_id'];
		// Check for activity type if task orders to be added in select option
                $act_mode = $_REQUEST['activity_mode'];
			
		if($value != '')
		{
			$parent_module = getSalesEntityType($value);
			if($parent_module == "Leads")
			{
				$sql = "select * from leaddetails where leadid=".$value;
				$result = $adb->query($sql);
				$first_name = $adb->query_result($result,0,"firstname");
				$last_name = $adb->query_result($result,0,"lastname");
				$parent_name = $last_name.' '.$first_name;
				$lead_selected = "selected";

			}
			elseif($parent_module == "Accounts")
			{
				$sql = "select * from  account where accountid=".$value;
				$result = $adb->query($sql);
				$parent_name = $adb->query_result($result,0,"accountname");
				$account_selected = "selected";

			}
			elseif($parent_module == "Potentials")
			{
				$sql = "select * from  potential where potentialid=".$value;
				$result = $adb->query($sql);
				$parent_name = $adb->query_result($result,0,"potentialname");
				$contact_selected = "selected";

			}
			elseif($parent_module == "Quotes")
                        {
                                $sql = "select * from  quotes where quoteid=".$value;
                                $result = $adb->query($sql);
                                $parent_name = $adb->query_result($result,0,"subject");
                                $quote_selected = "selected";

                        }
			elseif($act_mode == "Task")
                        {
                                if($parent_module == "PurchaseOrder")
                                {
                                        $sql = "select * from purchaseorder where purchaseorderid=".$value;
                                        $result = $adb->query($sql);
                                        $parent_name = $adb->query_result($result,0,"subject");
                                        $purchase_selected = "selected";
                                }
                                if($parent_module == "SalesOrder")
                                {
                                        $sql = "select * from salesorder where salesorderid=".$value;
                                        $result = $adb->query($sql);
                                        $parent_name = $adb->query_result($result,0,"subject");
                                        $sales_selected = "selected";
                                }
				if($parent_module == "Invoice")
                                {
                                        $sql = "select * from invoice where invoiceid=".$value;
                                        $result = $adb->query($sql);
                                        $parent_name = $adb->query_result($result,0,"subject");
                                        $invoice_selected = "selected";
                                }

                        }

		}
		$custfld .= '<td width="20%" class="dataLabel"><select name="parent_type" onChange=\'document.EditView.parent_name.value=""; document.EditView.parent_id.value=""\'>';
                $custfld .= '<OPTION value="Leads&action=Popup" '.$lead_selected.'>'.$app_strings['COMBO_LEADS'].'</OPTION>';
                $custfld .= '<OPTION value="Accounts&action=Popup" '.$account_selected.'>'.$app_strings['COMBO_ACCOUNTS'].'</OPTION>';
                $custfld .= '<OPTION value="Potentials&action=Popup" '.$contact_selected.'>'.$app_strings['COMBO_POTENTIALS'].'</OPTION>';
		if($act_mode == "Task")
                {
			$custfld .= '<OPTION value="Quotes&action=Popup" '.$quote_selected.'>'.$app_strings['COMBO_QUOTES'].'</OPTION>';
                        $custfld .= '<OPTION value="PurchaseOrder&action=Popup" '.$purchase_selected.'>'.$app_strings['COMBO_PORDER'].'</OPTION>';
                        $custfld .= '<OPTION value="SalesOrder&action=Popup" '.$sales_selected.'>'.$app_strings['COMBO_SORDER'].'</OPTION>';
                        $custfld .= '<OPTION value="Invoice&action=Popup" '.$invoice_selected.'>'.$app_strings['COMBO_INVOICES'].'</OPTION>';
                }
                $custfld .='</select></td>';

		$custfld .= '<td width="30%"><input name="parent_id" type="hidden" value="'.$value.'"><input name="parent_name" readonly type="text" value="'.$parent_name.'">&nbsp;<img src="'.$image_path.'select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick=\'return window.open("index.php?module="+ document.EditView.parent_type.value +"&html=Popup_picker&form=HelpDeskEditView","test","width=600,height=400,resizable=1,scrollbars=1,top=150,left=200");\' align="absmiddle" style=\'cursor:hand;cursor:pointer\'>&nbsp;<input type="image" src="'.$image_path.'clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.parent_id.value=\'\';this.form.parent_name.value=\'\';return false;" align="absmiddle" style=\'cursor:hand;cursor:pointer\'></td>';

                $editview_label[] = array($app_strings['COMBO_LEADS']=>$lead_selected,
					  $app_strings['COMBO_ACCOUNTS']=>$account_selected,
					  $app_strings['COMBO_POTENTIALS']=>$contact_selected,
					  $app_strings['COMBO_QUOTES']=>$quote_selected,
					  $app_strings['COMBO_PORDER']=>$purchase_selected,
					  $app_strings['COMBO_SORDER']=>$sales_selected,
					  $app_strings['COMBO_INVOICES']=>$invoice_selected
                                         );
		$fieldvalue[] =$parent_name;
		$fieldvalue[] = $value;
        }
		//added by rdhital/Raju for better email support
        elseif($uitype == 357)
        {
        	if(isset($_REQUEST['emailids']) && $_REQUEST['emailids'] != '')
			{
            	$parent_id = $_REQUEST['emailids'];
				$parent_name='';
				$pmodule=$_REQUEST['pmodule'];
				
				$myids=explode("|",$parent_id);
				for ($i=0;$i<(count($myids)-1);$i++)
				{
					$realid=explode("@",$myids[$i]);
					$entityid=$realid[0];
					$nemail=count($realid);
										
					if ($pmodule=='Accounts'){
						require_once('modules/Accounts/Account.php');
						$myfocus = new Account();
						$myfocus->retrieve_entity_info($entityid,"Accounts");
						$fullname=br2nl($myfocus->column_fields['accountname']);
					}
					elseif ($pmodule=='Contacts'){
						require_once('modules/Contacts/Contact.php');
						$myfocus = new Contact();
						$myfocus->retrieve_entity_info($entityid,"Contacts");
						$fname=br2nl($myfocus->column_fields['firstname']);
						$lname=br2nl($myfocus->column_fields['lastname']);
						$fullname=$lname.' '.$fname;
					}
					elseif ($pmodule=='Leads'){
						require_once('modules/Leads/Lead.php');
						$myfocus = new Lead();
						$myfocus->retrieve_entity_info($entityid,"Leads");
						$fname=br2nl($myfocus->column_fields['firstname']);
						$lname=br2nl($myfocus->column_fields['lastname']);
						$fullname=$lname.' '.$fname;
					}
					for ($j=1;$j<$nemail;$j++){
						$querystr='select columnname from field where fieldid='.$realid[$j].';';
						$result=$adb->query($querystr);
						$temp=$adb->query_result($result,0,'columnname');
						$temp1=br2nl($myfocus->column_fields[$temp]);
						$parent_name.=$fullname.'<'.$temp1.'>; ';
					}
				}
			}
			else
			{
				$parent_name='';
				$parent_id='';
				$myemailid= $_REQUEST['record'];
				$mysql = "select crmid from seactivityrel where activityid=".$myemailid;
				$myresult = $adb->query($mysql);
				$mycount=$adb->num_rows($myresult);
				if($mycount >0)
				{
					for ($i=0;$i<$mycount;$i++)
					{	
						$mycrmid=$adb->query_result($myresult,$i,'crmid');
						$parent_module = getSalesEntityType($mycrmid);
						//echo $mycrmid.'id<br>'.$parent_module;
						if($parent_module == "Leads")
						{
							$sql = "select firstname,lastname,email from leaddetails where leadid=".$mycrmid;
							$result = $adb->query($sql);
							$first_name = $adb->query_result($result,0,"firstname");
							$last_name = $adb->query_result($result,0,"lastname");
							$myemail=$adb->query_result($result,0,"email");
							$parent_id .=$mycrmid.'@0|' ; //make it such that the email adress sent is remebered and only that one is retrived
							$parent_name .= $last_name.' '.$first_name.'<'.$myemail.'>; ';
						}
						elseif($parent_module == "Contacts")
						{
							$sql = "select * from  contactdetails where contactid=".$mycrmid;
							$result = $adb->query($sql);
							$first_name = $adb->query_result($result,0,"firstname");
							$last_name = $adb->query_result($result,0,"lastname");
							$myemail=$adb->query_result($result,0,"email");
							$parent_id .=$mycrmid.'@0|'  ;//make it such that the email adress sent is remebered and only that one is retrived
							$parent_name .= $last_name.' '.$first_name.'<'.$myemail.'>; ';
						}
						elseif($parent_module == "Accounts")
						{
							$sql = "select * from  account where accountid=".$mycrmid;
							$result = $adb->query($sql);
							$account_name = $adb->query_result($result,0,"accountname");
							$myemail=$adb->query_result($result,0,"email1");
							$parent_id .=$mycrmid.'@0|'  ;//make it such that the email adress sent is remebered and only that one is retrived
							$parent_name .= $account_name.'<'.$myemail.'>; ';
						}
					}
				}
			}
		$custfld .= '<td width="20%" class="dataLabel">To:&nbsp;</td>';
       	$custfld .= '<td width="90%" colspan="3"><input name="parent_id" type="hidden" value="'.$parent_id.'"><textarea readonly name="parent_name" cols="70" rows="2">'.$parent_name.'</textarea>&nbsp;<select name="parent_type" >';
        $custfld .= '<OPTION value="Contacts" selected>'.$app_strings['COMBO_CONTACTS'].'</OPTION>';
        $custfld .= '<OPTION value="Accounts" >'.$app_strings['COMBO_ACCOUNTS'].'</OPTION>';
		$custfld .= '<OPTION value="Leads" >'.$app_strings['COMBO_LEADS'].'</OPTION></select><img src="'.$image_path.'select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick=\'return window.open("index.php?module="+ document.EditView.parent_type.value +"&action=Popup&popuptype=set_return_emails&form=EmailEditView&form_submit=false","test","width=600,height=400,resizable=1,scrollbars=1,top=150,left=200");\' align="absmiddle" style=\'cursor:hand;cursor:pointer\'>&nbsp;<input type="image" src="'.$image_path.'clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.parent_id.value=\'\';this.form.parent_name.value=\'\';return false;" align="absmiddle" style=\'cursor:hand;cursor:pointer\'></td>';
        $editview_label[] = array(	 
					  $app_strings['COMBO_CONTACTS']=>'selected',
					  $app_strings['COMBO_ACCOUNTS']=>'',
					  $app_strings['COMBO_LEADS']=>''
                                 );
       $fieldvalue[] =$parent_name;
       $fieldvalue[] = $parent_id;

		}
        //end of rdhital/Raju
		
		
		
		
		
		
	elseif($uitype == 67)
        {
                if(isset($_REQUEST['parent_id']) && $_REQUEST['parent_id'] != '')
                        $value = $_REQUEST['parent_id'];

		if($value != '')
		{
			$parent_module = getSalesEntityType($value);
			if($parent_module == "Leads")
			{
				$sql = "select * from leaddetails where leadid=".$value;
				$result = $adb->query($sql);
				$first_name = $adb->query_result($result,0,"firstname");
				$last_name = $adb->query_result($result,0,"lastname");
				$parent_name = $last_name.' '.$first_name;
				$lead_selected = "selected";

			}
			elseif($parent_module == "Contacts")
			{
				$sql = "select * from  contactdetails where contactid=".$value;
				$result = $adb->query($sql);
				$first_name = $adb->query_result($result,0,"firstname");
				$last_name = $adb->query_result($result,0,"lastname");
				$parent_name = $last_name.' '.$first_name;
				$contact_selected = "selected";

			}
		}
		$custfld .= '<td width="20%" class="dataLabel"><select name="parent_type" onChange=\'document.EditView.parent_name.value=""; document.EditView.parent_id.value=""\'>';
                $custfld .= '<OPTION value="Leads" '.$lead_selected.'>'.$app_strings['COMBO_LEADS'].'</OPTION>';
                $custfld .= '<OPTION value="Contacts" '.$contact_selected.'>'.$app_strings['COMBO_CONTACTS'].'</OPTION>';

       		$custfld .= '<td width="30%"><input name="parent_id" type="hidden" value="'.$value.'"><input name="parent_name" readonly type="text" value="'.$parent_name.'">&nbsp;<img src="'.$image_path.'select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick=\'return window.open("index.php?module="+ document.EditView.parent_type.value +"&action=Popup&html=Popup_picker&form=HelpDeskEditView","test","width=600,height=400,resizable=1,scrollbars=1,top=150,left=200");\' align="absmiddle" style=\'cursor:hand;cursor:pointer\'>&nbsp;<input type="image" src="'.$image_path.'clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.parent_id.value=\'\';this.form.parent_name.value=\'\';return false;" align="absmiddle" style=\'cursor:hand;cursor:pointer\'></td>';
		$fieldvalue[] = $parent_name;
		$fieldvalue[] = $value;
        }
        elseif($uitype == 68)
        {
                if(isset($_REQUEST['parent_id']) && $_REQUEST['parent_id'] != '')
                        $value = $_REQUEST['parent_id'];

		if($value != '')
		{
			$parent_module = getSalesEntityType($value);
			if($parent_module == "Contacts")
			{
				$sql = "select * from  contactdetails where contactid=".$value;
				$result = $adb->query($sql);
				$first_name = $adb->query_result($result,0,"firstname");
				$last_name = $adb->query_result($result,0,"lastname");
				$parent_name = $last_name.' '.$first_name;
				$contact_selected = "selected";

			}
			elseif($parent_module == "Accounts")
			{
				$sql = "select * from  account where accountid=".$value;
				$result = $adb->query($sql);
				$parent_name = $adb->query_result($result,0,"accountname");
				$account_selected = "selected";

			}
		}
		$custfld .= '<td width="20%" class="dataLabel"><select name="parent_type" onChange=\'document.EditView.parent_name.value=""; document.EditView.parent_id.value=""\'>';
                $custfld .= '<OPTION value="Contacts" '.$contact_selected.'>'.$app_strings['COMBO_CONTACTS'];
                $custfld .= '<OPTION value="Accounts" '.$account_selected.'>'.$app_strings['COMBO_ACCOUNTS'].'</OPTION>';

		$custfld .= '<td width="30%"><input name="parent_id" type="hidden" value="'.$value.'"><input name="parent_name" readonly type="text" value="'.$parent_name.'">&nbsp;<img src="'.$image_path.'select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick=\'return window.open("index.php?module="+ document.EditView.parent_type.value +"&action=Popup&html=Popup_picker&form=HelpDeskEditView","test","width=600,height=400,resizable=1,scrollbars=1,top=150,left=200");\' align="absmiddle" style=\'cursor:hand;cursor:pointer\'>&nbsp;<input type="image" src="'.$image_path.'clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.parent_id.value=\'\';this.form.parent_name.value=\'\';return false;" align="absmiddle" style=\'cursor:hand;cursor:pointer\'></td>';
			$editview_label[] = array($app_strings['COMBO_CONTACTS']=>$contact_selected,
						  $app_strings['COMBO_ACCOUNTS']=>$account_selected
						 );
			$fieldvalue[] = $parent_name;
			$fieldvalue[] = $value;
        }

	elseif($uitype == 65)
	{
	
		$custfld .= '<td width="20%" class="dataLabel"><select name="parent_type" onChange=\'document.EditView.parent_name.value=""; document.EditView.parent_id.value=""\'>
                <OPTION value="Leads">'.$app_strings['COMBO_LEADS'].'</OPTION>
                <OPTION value="Accounts">'.$app_strings['COMBO_ACCOUNTS'].'</OPTION>
                <OPTION value="Potentials">'.$app_strings['COMBO_POTENTIALS'].'</OPTION>
                <OPTION value="Products">'.$app_strings['COMBO_PRODUCTS'].'</OPTION></select></td>';

		$custfld .= '<td width="30%"><input name="parent_id" type="hidden" value=""><input name="parent_name" readonly type="text" value="">&nbsp;<img src="'.$image_path.'select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick=\'return window.open("index.php?module="+ document.EditView.parent_type.value + "&action=Popup&html=Popup_picker&form=HelpDeskEditView","test","width=600,height=400,resizable=1,scrollbars=1,top=150,left=200");\' align="absmiddle" style=\'cursor:hand;cursor:pointer\'>&nbsp;<input type="image" src="'.$image_path.'clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.parent_id.value=\'\';this.form.parent_name.value=\'\';return false;" align="absmiddle" style=\'cursor:hand;cursor:pointer\'></td>';
		$fieldvalue[] = '';
	}
	elseif($uitype == 71 || $uitype == 72)
	{
		$custfld .= '<td width="20%" class="dataLabel">';

		if($uitype == 72)
		{
			$custfld .= '<font color="red">*</font>';
		}

		$disp_currency = getDisplayCurrency();

		//$custfld .= $mod_strings[$fieldlabel].': ('.$disp_currency.')</td>';
		$editview_label[]=$mod_strings[$fieldlabel].': ('.$disp_currency.')';

		$custfld .= '<td width="30%"><input name="'.$fieldname.'" type="text" size="25" maxlength="'.$maxlength.'" value="'.$value.'"></td>';
		$fieldvalue[] = $value;
	}
	elseif($uitype == 75 || $uitype ==81)
	{

		if($value != '')
               {
                       $vendor_name = getVendorName($value);
               }
	       elseif(isset($_REQUEST['vendor_id']) && $_REQUEST['vendor_id'] != '')
	       {
			$value = $_REQUEST['vendor_id'];
			$vendor_name = getVendorName($value);
	       }		 	
		$custfld .= '<td width="20%" valign="center" class="dataLabel">';
		$pop_type = 'specific';
		if($uitype == 81)
		{
			$custfld .= '<font color="red">*</font>';
			$pop_type = 'specific_vendor_address';
		}
		//$custfld .= $mod_strings[$fieldlabel].'</td>';
		$editview_label[]=$mod_strings[$fieldlabel];
		
		$custfld .= '<td width="30%"><input name="vendor_name" readonly type="text" value="'.$vendor_name.'"><input name="vendor_id" type="hidden" value="'.$value.'">&nbsp;<img src="'.$image_path.'select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Vendors&action=Popup&html=Popup_picker&popuptype='.$pop_type.'&form=EditView","test","width=600,height=400,resizable=1,scrollbars=1");\' align="absmiddle" style=\'cursor:hand;cursor:pointer\'>';
		if($uitype == 75)
                {
                        $custfld .='&nbsp;<input type="image" src="'.$image_path.'clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.vendor_id.value=\'\';this.form.vendor_name.value=\'\';return false;" align="absmiddle" style=\'cursor:hand;cursor:pointer\'></td>';
                }
		$fieldvalue[] = $vendor_name;
		$fieldvalue[] = $value;
	}
	elseif($uitype == 76)
	{

		if($value != '')
               {
                       $potential_name = getPotentialName($value);
               }
	       elseif(isset($_REQUEST['potential_id']) && $_REQUEST['potential_id'] != '')
	       {
			$value = $_REQUEST['potental_id'];
			$potential_name = getPotentialName($value);
	       }		 	
		//$custfld .= '<td width="20%" valign="center" class="dataLabel">'.$mod_strings[$fieldlabel].'</td>';
		$editview_label[]=$mod_strings[$fieldlabel];
		$custfld .= '<td width="30%"><input name="potential_name" readonly type="text" value="'.$potential_name.'"><input name="potential_id" type="hidden" value="'.$value.'">&nbsp;<img src="'.$image_path.'select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Potentials&action=Popup&html=Popup_picker&popuptype=specific_potential_account_address&form=EditView","test","width=600,height=400,resizable=1,scrollbars=1");\' align="absmiddle" style=\'cursor:hand;cursor:pointer\'>&nbsp;<input type="image" src="'.$image_path.'clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.potential_id.value=\'\';this.form.potential_name.value=\'\';return false;" align="absmiddle" style=\'cursor:hand;cursor:pointer\'></td>';
		$fieldvalue[] = $potential_name;
		$fieldvalue[] = $value;
	}
	elseif($uitype == 78)
	{

		if($value != '')
               {
                       $quote_name = getQuoteName($value);
               }
	       elseif(isset($_REQUEST['quote_id']) && $_REQUEST['quote_id'] != '')
	       {
			$value = $_REQUEST['quote_id'];
			$potential_name = getQuoteName($value);
	       }		 	
		//$custfld .= '<td width="20%" valign="center" class="dataLabel">'.$mod_strings[$fieldlabel].'</td>';
		$editview_label[]=$mod_strings[$fieldlabel];
		$custfld .= '<td width="30%"><input name="quote_name" readonly type="text" value="'.$quote_name.'"><input name="quote_id" type="hidden" value="'.$value.'">&nbsp;<img src="'.$image_path.'select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Quotes&action=Popup&html=Popup_picker&popuptype=specific&form=EditView","test","width=600,height=400,resizable=1,scrollbars=1");\' align="absmiddle" style=\'cursor:hand;cursor:pointer\'>&nbsp;<input type="image" src="'.$image_path.'clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.quote_id.value=\'\';this.form.quote_name.value=\'\';return false;" align="absmiddle" style=\'cursor:hand;cursor:pointer\'></td>';
		$fieldvalue[] = $quote_name;
		$fieldvalue[] = $value;
	}
	elseif($uitype == 79)
	{

		if($value != '')
               {
                       $purchaseorder_name = getPoName($value);
               }
	       elseif(isset($_REQUEST['purchaseorder_id']) && $_REQUEST['purchaseorder_id'] != '')
	       {
			$value = $_REQUEST['purchaseorder_id'];
			$purchaseorder_name = getPoName($value);
	       }		 	
		//$custfld .= '<td width="20%" valign="center" class="dataLabel">'.$mod_strings[$fieldlabel].'</td>';
		$editview_label[]=$mod_strings[$fieldlabel];
		$custfld .= '<td width="30%"><input name="purchaseorder_name" readonly type="text" value="'.$purchaseorder_name.'"><input name="purchaseorder_id" type="hidden" value="'.$value.'">&nbsp;<img src="'.$image_path.'select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick=\'return window.open("index.php?module=PurchaseOrder&action=Popup&html=Popup_picker&popuptype=specific&form=EditView","test","width=600,height=400,resizable=1,scrollbars=1");\' align="absmiddle" style=\'cursor:hand;cursor:pointer\'>&nbsp;<input type="image" src="'.$image_path.'clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.purchaseorder_id.value=\'\';this.form.purchaseorder_name.value=\'\';return false;" align="absmiddle" style=\'cursor:hand;cursor:pointer\'></td>';
		$fieldvalue[] = $purchaseorder_name;
		$fieldvalue[] = $value;
	}
	elseif($uitype == 80)
	{

		if($value != '')
               {
                       $salesorder_name = getSoName($value);
               }
	       elseif(isset($_REQUEST['salesorder_id']) && $_REQUEST['salesorder_id'] != '')
	       {
			$value = $_REQUEST['salesorder_id'];
			$salesorder_name = getSoName($value);
	       }		 	
		//$custfld .= '<td width="20%" valign="center" class="dataLabel">'.$mod_strings[$fieldlabel].'</td>';
		$editview_label[]=$mod_strings[$fieldlabel];
		$custfld .= '<td width="30%"><input name="salesorder_name" readonly type="text" value="'.$salesorder_name.'"><input name="salesorder_id" type="hidden" value="'.$value.'">&nbsp;<img src="'.$image_path.'select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick=\'return window.open("index.php?module=SalesOrder&action=Popup&html=Popup_picker&popuptype=specific&form=EditView","test","width=600,height=400,resizable=1,scrollbars=1");\' align="absmiddle" style=\'cursor:hand;cursor:pointer\'>&nbsp;<input type="image" src="'.$image_path.'clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.salesorder_id.value=\'\';this.form.salesorder_name.value=\'\';return false;" align="absmiddle" style=\'cursor:hand;cursor:pointer\'></td>';
		$fieldvalue[] = $salesorder_name;
		$fieldvalue[] = $value;
	}
	elseif($uitype == 30)
	{
		$rem_days = 0;
		$rem_hrs = 0;
		$rem_min = 0;
		if($value!='')
		$SET_REM = "CHECKED";
		$rem_days = floor($col_fields[$fieldname]/(24*60));
		$rem_hrs = floor(($col_fields[$fieldname]-$rem_days*24*60)/60);
		$rem_min = ($col_fields[$fieldname]-$rem_days*24*60)%60;

                //$custfld .= '<td width="20%" class="dataLabel" valign="top">'.$mod_strings[$fieldlabel].':</td>';
		$editview_label[]=$mod_strings[$fieldlabel];
                $custfld .= '<td valign="top" colspan=3>&nbsp;<input type="radio" name="set_reminder" value="Yes" '.$SET_REM.'>&nbsp;'.$mod_strings['LBL_YES'].'&nbsp;<input type="radio" name="set_reminder" value="No">&nbsp;'.$mod_strings['LBL_NO'].'&nbsp;';
		$day_options = getReminderSelectOption(0,31,'remdays',$rem_days);
		$hr_options = getReminderSelectOption(0,23,'remhrs',$rem_hrs);
		$min_options = getReminderSelectOption(1,59,'remmin',$rem_min);
		$custfld .= '&nbsp;&nbsp;'.$day_options.' &nbsp;'.$mod_strings['LBL_DAYS'].'&nbsp;&nbsp;'.$hr_options.'&nbsp;'.$mod_strings['LBL_HOURS'].'&nbsp;&nbsp;'.$min_options.'&nbsp;'.$mod_strings['LBL_MINUTES'].'&nbsp;&nbsp;'.$mod_strings['LBL_BEFORE_EVENT'].'</td>';
		$SET_REM = '';
		$fieldvalue[] = array($rem_days=>array(0,31,'remdays'),
				      $rem_hrs=>array(0,23,'remhrs'),
				      $rem_min=>array(1,59,'remmin'));
	}
	else
	{
		$custfld .= '<td width="20%" class="dataLabel">';
		//Added condition to set the subject if click Reply All from web mail
		if($_REQUEST['module'] == 'Emails' && $_REQUEST['mg_subject'] != '')
		{
			$value = $_REQUEST['mg_subject'];
		}

		if($uitype == 2)
			$custfld .= '<font color="red">*</font>';

		//$custfld .= $mod_strings[$fieldlabel].':</td>';
		$editview_label[]=$mod_strings[$fieldlabel];

		$custfld .= '<td width="30%"><input name="'.$fieldname.'" type="text" size="25" maxlength="'.$maxlength.'" value="'.$value.'"></td>';
		$fieldvalue[] = $value;
	}
	
	// Mike Crowe Mod --------------------------------------------------------force numerics right justified.
	if ( !eregi("id=",$custfld) )
		$custfld = preg_replace("/<input/iS","<input id='$fieldname' ",$custfld);
 
	if ( in_array($uitype,array(71,72,7,9,90)) )
	{
		$custfld = preg_replace("/<input/iS","<input align=right ",$custfld);
		//"align='right'"
	}
	$final_arr[]=$ui_type;
	$final_arr[]=$editview_label;
	$final_arr[]=$editview_fldname;
	$final_arr[]=$fieldvalue;
	//return $custfld;
	//return $uitype;
	return $final_arr;
}





function getConvertSoToInvoice($focus,$so_focus,$soid)
{
	global $log;
        $log->info("in getConvertSoToInvoice ".$soid);

	$focus->column_fields['salesorder_id'] = $soid;
	$focus->column_fields['subject'] = $so_focus->column_fields['subject'];
	$focus->column_fields['customerno'] = $so_focus->column_fields['customerno'];
	$focus->column_fields['duedate'] = $so_focus->column_fields['duedate'];
	$focus->column_fields['contact_id'] = $so_focus->column_fields['contact_id'];//to include contact name in Invoice
	$focus->column_fields['account_id'] = $so_focus->column_fields['account_id'];
	$focus->column_fields['exciseduty'] = $so_focus->column_fields['exciseduty'];
	$focus->column_fields['salescommission'] = $so_focus->column_fields['salescommission'];
	$focus->column_fields['purchaseorder'] = $so_focus->column_fields['purchaseorder'];
	$focus->column_fields['bill_street'] = $so_focus->column_fields['bill_street'];
	$focus->column_fields['ship_street'] = $so_focus->column_fields['ship_street'];
	$focus->column_fields['bill_city'] = $so_focus->column_fields['bill_city'];
	$focus->column_fields['ship_city'] = $so_focus->column_fields['ship_city'];
	$focus->column_fields['bill_state'] = $so_focus->column_fields['bill_state'];
	$focus->column_fields['ship_state'] = $so_focus->column_fields['ship_state'];
	$focus->column_fields['bill_code'] = $so_focus->column_fields['bill_code'];
	$focus->column_fields['ship_code'] = $so_focus->column_fields['ship_code'];
	$focus->column_fields['bill_country'] = $so_focus->column_fields['bill_country'];
	$focus->column_fields['ship_country'] = $so_focus->column_fields['ship_country'];
	$focus->column_fields['bill_pobox'] = $so_focus->column_fields['bill_pobox'];
    $focus->column_fields['ship_pobox'] = $so_focus->column_fields['ship_pobox'];
	$focus->column_fields['description'] = $so_focus->column_fields['description'];
	$focus->column_fields['terms_conditions'] = $so_focus->column_fields['terms_conditions'];

	return $focus;

}



function getConvertQuoteToInvoice($focus,$quote_focus,$quoteid)
{
	global $log;
        $log->info("in getConvertQuoteToInvoice ".$quoteid);

	$focus->column_fields['subject'] = $quote_focus->column_fields['subject'];
	$focus->column_fields['account_id'] = $quote_focus->column_fields['account_id'];
	$focus->column_fields['bill_street'] = $quote_focus->column_fields['bill_street'];
	$focus->column_fields['ship_street'] = $quote_focus->column_fields['ship_street'];
	$focus->column_fields['bill_city'] = $quote_focus->column_fields['bill_city'];
	$focus->column_fields['ship_city'] = $quote_focus->column_fields['ship_city'];
	$focus->column_fields['bill_state'] = $quote_focus->column_fields['bill_state'];
	$focus->column_fields['ship_state'] = $quote_focus->column_fields['ship_state'];
	$focus->column_fields['bill_code'] = $quote_focus->column_fields['bill_code'];
	$focus->column_fields['ship_code'] = $quote_focus->column_fields['ship_code'];
	$focus->column_fields['bill_country'] = $quote_focus->column_fields['bill_country'];
	$focus->column_fields['ship_country'] = $quote_focus->column_fields['ship_country'];
	$focus->column_fields['bill_pobox'] = $quote_focus->column_fields['bill_pobox'];
    $focus->column_fields['ship_pobox'] = $quote_focus->column_fields['ship_pobox'];
	$focus->column_fields['description'] = $quote_focus->column_fields['description'];
	$focus->column_fields['terms_conditions'] = $quote_focus->column_fields['terms_conditions'];

	return $focus;

}


function getConvertQuoteToSoObject($focus,$quote_focus,$quoteid)
{
	global $log;
        $log->info("in getConvertQuoteToSoObject ".$quoteid);

        $focus->column_fields['quote_id'] = $quoteid;
        $focus->column_fields['subject'] = $quote_focus->column_fields['subject'];
        $focus->column_fields['contact_id'] = $quote_focus->column_fields['contact_id'];
        $focus->column_fields['potential_id'] = $quote_focus->column_fields['potential_id'];
        $focus->column_fields['account_id'] = $quote_focus->column_fields['account_id'];
        $focus->column_fields['carrier'] = $quote_focus->column_fields['carrier'];
        $focus->column_fields['bill_street'] = $quote_focus->column_fields['bill_street'];
        $focus->column_fields['ship_street'] = $quote_focus->column_fields['ship_street'];
        $focus->column_fields['bill_city'] = $quote_focus->column_fields['bill_city'];
        $focus->column_fields['ship_city'] = $quote_focus->column_fields['ship_city'];
        $focus->column_fields['bill_state'] = $quote_focus->column_fields['bill_state'];
        $focus->column_fields['ship_state'] = $quote_focus->column_fields['ship_state'];
        $focus->column_fields['bill_code'] = $quote_focus->column_fields['bill_code'];
        $focus->column_fields['ship_code'] = $quote_focus->column_fields['ship_code'];
        $focus->column_fields['bill_country'] = $quote_focus->column_fields['bill_country'];
        $focus->column_fields['ship_country'] = $quote_focus->column_fields['ship_country'];
        $focus->column_fields['bill_pobox'] = $quote_focus->column_fields['bill_pobox'];
        $focus->column_fields['ship_pobox'] = $quote_focus->column_fields['ship_pobox'];
		$focus->column_fields['description'] = $quote_focus->column_fields['description'];
        $focus->column_fields['terms_conditions'] = $quote_focus->column_fields['terms_conditions'];

        return $focus;

}


function getAssociatedProducts($module,$focus,$seid='')
{
	global $adb;
	$output = '';
	global $theme;
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	$product_Detail = Array();
	if($module == 'Quotes')
	{
		$query="select products.productname,products.unit_price,products.qtyinstock,quotesproductrel.* from quotesproductrel inner join products on products.productid=quotesproductrel.productid where quoteid=".$focus->id;
	}
	elseif($module == 'Orders')
	{
		$query="select products.productname,products.unit_price,products.qtyinstock,poproductrel.* from poproductrel inner join products on products.productid=poproductrel.productid where purchaseorderid=".$focus->id;
	}
	elseif($module == 'SalesOrder')
	{
		$query="select products.productname,products.unit_price,products.qtyinstock,soproductrel.* from soproductrel inner join products on products.productid=soproductrel.productid where salesorderid=".$focus->id;
	}
	elseif($module == 'Invoice')
	{
		$query="select products.productname,products.unit_price,products.qtyinstock,invoiceproductrel.* from invoiceproductrel inner join products on products.productid=invoiceproductrel.productid where invoiceid=".$focus->id;
	}
	elseif($module == 'Potentials')
	{
		$query="select products.productname,products.unit_price,products.qtyinstock,seproductsrel.* from products inner join seproductsrel on seproductsrel.productid=products.productid where crmid=".$seid;
	}
	elseif($module == 'Products')
	{
		$query="select products.productid,products.productname,products.unit_price,products.qtyinstock,crmentity.* from products inner join crmentity on crmentity.crmid=products.productid where crmentity.deleted=0 and productid=".$seid;
	}

	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	for($i=1;$i<=$num_rows;$i++)
	{
		$productname=$adb->query_result($result,$i-1,'productname');
		$unitprice=$adb->query_result($result,$i-1,'unit_price');
		$qtyinstock=$adb->query_result($result,$i-1,'qtyinstock');
		$productid=$adb->query_result($result,$i-1,'productid');
		$qty=$adb->query_result($result,$i-1,'quantity');
		$listprice=$adb->query_result($result,$i-1,'listprice');
		if($listprice == '')
			$listprice = $unitprice;
		if($qty =='')
			$qty = 1;
		$total = $qty*$listprice;

		$product_id_var = 'hdnProductId'.$i;
		$status_var = 'hdnRowStatus'.$i;
		$qty_var = 'txtQty'.$i;
		$list_price_var = 'txtListPrice'.$i;	
		$total_var = 'total'.$i;
		
		if($i%2 == 0)
		{
			$row_class = "evenListRow";
		}
		else
		{
			$row_class = "oddListRow";
		}

		$product_Detail[$i]['txtProduct']= $productname;
		
		/*$output .= '<tr id="row'.$i.'" class="'.$row_class.'">';
		$output .= '<td height="25" style="padding:3px;" nowrap><input id="txtProduct'.$i.'" name="txtProduct'.$i.'" type="text" readonly value="'.$productname.'"> <img src="'.$image_path.'search.gif" onClick=\'productPickList(this)\' align="absmiddle" style=\'cursor:hand;cursor:pointer\'></td>';
		$output .= '<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
		*/
		if($module != 'Orders' && $focus->object_name != 'Order')
		{
			//$output .= '<td style="padding:3px;"><div id="qtyInStock'.$i.'">'.$qtyinstock.'</div>&nbsp;</td>';
			//$output .= '<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
			$product_Detail[$i]['qtyInStock'.$i]=$qtyinstock;
		}	
		#$output .= '<td style="padding:3px;"><input type=text id="txtQty'.$i.'" name="txtQty'.$i.'" size="7" value="'.$qty.'" onBlur=\'calcTotal(this)\'></td>';
		$product_Detail[$i]['txtQty'.$i]=$qty;
		#$output .='<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
		$product_Detail[$i]['unitPrice'.$i]=$unitprice;
		#$output .= '<td style="padding:3px;"><div id="unitPrice'.$i.'">'.$unitprice.'</div>&nbsp;</td>';
		#$output .= '<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
		$product_Detail[$i]['txtListPrice'.$i]=$listprice;
		#$output .= '<td style="padding:3px;"><input type=text id="txtListPrice'.$i.'" name="txtListPrice'.$i.'" value="'.$listprice.'" size="12" onBlur="calcTotal(this)"> <img src="'.$image_path.'pricebook.gif" onClick=\'priceBookPickList(this)\' align="absmiddle" style="cursor:hand;cursor:pointer" title="Price Book"></td>';
		#$output .= '<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
		$product_Detail[$i]['total'.$i]=$total;
		#$output .= '<td style="padding:3px;"><div id="total'.$i.'" align="right">'.$total.'</div></td>';
		$output .= '<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';

		if($i != 1)
		{
			#$output .= '<td style="padding:0px 3px 0px 3px;" align="center" width="50"><a id="delRow'.$i.'" href=\'javascript:;\' onclick=\'delRow(this.id)\'>Del</a>';
			$product_Detail[$i]['delRow'.$i]="Del";
		}
		else
		{
			#$output .= '<td style="padding:0px 3px 0px 3px;" align="center" width="50">';
		}
		$product_Detail[$i]['hdnProductId'.$i]=$total;
		#$output .= '<input type="hidden" id="hdnProductId'.$i.'" name="hdnProductId'.$i.'" value="'.$productid.'">';
		$product_Detail[$i]['hdnRowStatus'.$i]='';
		#$output .= '<input type="hidden" id="hdnRowStatus'.$i.'" name="hdnRowStatus'.$i.'">';
		$product_Detail[$i]['hdnTotal'.$i]='';
		#$output .= '<input type="hidden" id="hdnTotal'.$i.'" name="hdnTotal'.$i.'" value="'.$total.'">';
		#$output .= '</td></tr>';	

	}
	return $product_Detail;

}

function getNoOfAssocProducts($module,$focus,$seid='')
{
	global $adb;
	$output = '';
	if($module == 'Quotes')
	{
		$query="select products.productname,products.unit_price,quotesproductrel.* from quotesproductrel inner join products on products.productid=quotesproductrel.productid where quoteid=".$focus->id;
	}
	elseif($module == 'PurchaseOrder')
	{
		$query="select products.productname,products.unit_price,poproductrel.* from poproductrel inner join products on products.productid=poproductrel.productid where purchaseorderid=".$focus->id;
	}
	elseif($module == 'SalesOrder')
	{
		$query="select products.productname,products.unit_price,soproductrel.* from soproductrel inner join products on products.productid=soproductrel.productid where salesorderid=".$focus->id;
	}
	elseif($module == 'Invoice')
	{
		$query="select products.productname,products.unit_price,invoiceproductrel.* from invoiceproductrel inner join products on products.productid=invoiceproductrel.productid where invoiceid=".$focus->id;
	}
	elseif($module == 'Potentials')
	{
		$query="select products.productname,products.unit_price,seproductsrel.* from products inner join seproductsrel on seproductsrel.productid=products.productid where crmid=".$seid;
	}	
	elseif($module == 'Products')
	{
		$query="select products.productname,products.unit_price, crmentity.* from products inner join crmentity on crmentity.crmid=products.productid where crmentity.deleted=0 and productid=".$seid;
	}


	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	return $num_rows;
}





function getBlockInformation($module, $block, $mode, $col_fields,$tabid)
{
	//echo '*******************************<br>';
	//echo '<pre>';print_r($col_fields);echo '</pre>';
	//echo '*******************************<br>';
	//retreive the tabid	
	global $adb;
	//$tabid = getTabid($module);
	global $profile_id;
	$editview_arr = Array();

	global $current_user;
	require('user_privileges/user_privileges_'.$current_user->id.'.php');
	if($is_admin==true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] ==0)
        {

                $sql = "select field.* from field where field.tabid=".$tabid." and field.block=".$block ." and field.displaytype=1 order by sequence";
        }
        else
        {
                $profileList = getCurrentUserProfileList();

		$sql = "select field.* from field inner join profile2field on profile2field.fieldid=field.fieldid inner join def_org_field on def_org_field.fieldid=field.fieldid  where field.tabid=".$tabid." and field.block=".$block ." and field.displaytype=1 and profile2field.visible=0 and def_org_field.visible=0 and profile2field.profileid in ".$profileList.=" group by field.fieldid order by sequence";
	}
	//echo $sql;	

        $result = $adb->query($sql);
	$noofrows = $adb->num_rows($result);
	//$output='';
	if (($module == 'Accounts' || $module == 'Contacts' || $module == 'Quotes' || $module == 'PurchaseOrder' || $module == 'SalesOrder'|| $module == 'Invoice') && $block == 2)
	{
		 global $log;
                $log->info("module is ".$module);

			$mvAdd_flag = true;
			$moveAddress = "<td rowspan='6' valign='middle' align='center'><input title='Copy billing address to shipping address'  class='button' onclick='return copyAddressRight(EditView)'  type='button' name='copyright' value='&raquo;' style='padding:0px 2px 0px 2px;font-size:12px'><br><br>
				<input title='Copy shipping address to billing address'  class='button' onclick='return copyAddressLeft(EditView)'  type='button' name='copyleft' value='&laquo;' style='padding:0px 2px 0px 2px;font-size:12px'></td>";
	}
	

	for($i=0; $i<$noofrows; $i++)
	{
		$fieldtablename = $adb->query_result($result,$i,"tablename");	
		$fieldcolname = $adb->query_result($result,$i,"columnname");	
		$uitype = $adb->query_result($result,$i,"uitype");	
		$fieldname = $adb->query_result($result,$i,"fieldname");	
		$fieldlabel = $adb->query_result($result,$i,"fieldlabel");
		$maxlength = $adb->query_result($result,$i,"maximumlength");
		$generatedtype = $adb->query_result($result,$i,"generatedtype");				

		//$output .= '<tr>';
		$custfld = getOutputHtml($uitype, $fieldname, $fieldlabel, $maxlength, $col_fields,$generatedtype,$module);
		//$output .= $custfld;	
		$editview_arr[]=$custfld;
		if ($mvAdd_flag == true)
		//$output .= $moveAddress;
		$mvAdd_flag = false;
		$i++;
		if($i<$noofrows)
		{
			$fieldtablename = $adb->query_result($result,$i,"tablename");	
			$fieldcolname = $adb->query_result($result,$i,"columnname");	
			$uitype = $adb->query_result($result,$i,"uitype");	
			$fieldname = $adb->query_result($result,$i,"fieldname");	
			$fieldlabel = $adb->query_result($result,$i,"fieldlabel");
			$maxlength = $adb->query_result($result,$i,"maximumlength");
			$generatedtype = $adb->query_result($result,$i,"generatedtype");
			//$output .= '';
			$custfld = getOutputHtml($uitype, $fieldname, $fieldlabel, $maxlength, $col_fields,$generatedtype,$module);			
			//$output .= $custfld;
			//echo '<pre>';print_r($custfld);echo '</pre>';		
			//die;
			$editview_arr[]=$custfld;
		}
		//$output .= '</tr>';
		//echo '<pre>';print_r($editview_arr);echo '</pre>';
			
	}
	//return $output;
	for ($i=0,$j=0;$i<count($editview_arr);$i=$i+2,$j++)
        {
                $key1=$editview_arr[$i];
                if(is_array($editview_arr[$i+1]))
                {
                        $key2=$editview_arr[$i+1];
                }
		else
		{
			$key2 ='';
		}
                $return_data[$j]=array(0 => $key1,1 => $key2);
        }
        return $return_data;	
	//return $editview_arr;
		
		
}


?>
