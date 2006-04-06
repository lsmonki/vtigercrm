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

require_once('include/database/PearDatabase.php');
require_once('include/ComboUtil.php'); //new
require_once('include/utils/CommonUtils.php'); //new

/** This function returns the field details for a given fieldname.
  * Param $uitype - UI type of the field
  * Param $fieldname - Form field name
  * Param $fieldlabel - Form field label name
  * Param $maxlength - maximum length of the field
  * Param $col_fields - array contains the fieldname and values
  * Param $generatedtype - Field generated type (default is 1)
  * Param $module_name - module name
  * Return type is an array
  */

function getOutputHtml($uitype, $fieldname, $fieldlabel, $maxlength, $col_fields,$generatedtype,$module_name)
{
	global $adb,$log;
	global $theme;
	global $mod_strings;
	global $app_strings;
	global $current_user;
	global $noof_group_rows;

	require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
	require('user_privileges/user_privileges_'.$current_user->id.'.php');

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
		$editview_label[]=$mod_strings[$fieldlabel];
		$date_format = parse_calendardate($app_strings['NTC_DATE_FORMAT']);
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
		}
		$fieldvalue[] = array($disp_value => $curr_time) ;
		if($uitype == 5 || $uitype == 23)
		{
			$fieldvalue[] = array($date_format=>$current_user->date_format);
		}
		else
		{
			$fieldvalue[] = array($date_format=>$current_user->date_format.' '.$app_strings['YEAR_MONTH_DATE']);
		}
	}
	elseif($uitype == 15 || $uitype == 16 || $uitype == 33)
	{
		$editview_label[]=$mod_strings[$fieldlabel];
		$pick_query="select * from ".$fieldname;
		$pickListResult = $adb->query($pick_query);
		$noofpickrows = $adb->num_rows($pickListResult);

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
		}
		$fieldvalue [] = $options;
	}
	elseif($uitype == 17)
	{
		$editview_label[]=$mod_strings[$fieldlabel];
		$fieldvalue [] = $value;
	}
	elseif($uitype == 19 || $uitype == 20)
	{
		if(isset($_REQUEST['body']))
		{
			$value = ($_REQUEST['body']);
		}

		if($fieldname == 'terms_conditions')//for default Terms & Conditions
		{
			if($focus->mode=='edit') $value=getTermsandConditions();
		}

		$editview_label[]=$mod_strings[$fieldlabel];
		$fieldvalue [] = $value;
	}
	elseif($uitype == 21 || $uitype == 24)
	{
		$editview_label[]=$mod_strings[$fieldlabel];
		$fieldvalue [] = $value;
	}
	elseif($uitype == 22)
	{
		$editview_label[]=$mod_strings[$fieldlabel];
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


		if($fieldlabel == 'Assigned To' && $is_admin==false && $profileGlobalPermission[2] == 1 && ($defaultOrgSharingPermission[getTabid($module_name)] == 3 or $defaultOrgSharingPermission[getTabid($module_name)] == 0))
		{
			$users_combo = get_select_options_array(get_user_array(FALSE, "Active", $assigned_user_id,'private'), $assigned_user_id);
		}
		else
		{
			$users_combo = get_select_options_array(get_user_array(FALSE, "Active", $assigned_user_id), $assigned_user_id);
		}
		$fieldvalue [] = $users_combo;
	}
	elseif($uitype == 53)     
	{  
		$editview_label[]=$mod_strings[$fieldlabel];
		//Security Checks
		if($fieldlabel == 'Assigned To' && $is_admin==false && $profileGlobalPermission[2] == 1 && ($defaultOrgSharingPermission[getTabid($module_name)] == 3 or $defaultOrgSharingPermission[getTabid($module_name)] == 0))
		{
			$result=get_current_user_access_groups($module_name);
		}
		else
		{ 		
			$result = get_group_options();
		}
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
		
		if($fieldlabel == 'Assigned To' && $is_admin==false && $profileGlobalPermission[2] == 1 && ($defaultOrgSharingPermission[getTabid($module_name)] == 3 or $defaultOrgSharingPermission[getTabid($module_name)] == 0))
		{
			$users_combo = get_select_options_array(get_user_array(FALSE, "Active", $assigned_user_id,'private'), $assigned_user_id);
		}
		else
		{
			$users_combo = get_select_options_array(get_user_array(FALSE, "Active", $assigned_user_id), $assigned_user_id);
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
				if($groupname == $selected_groupname[0])
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
		$editview_label[]=$mod_strings[$fieldlabel];
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
		}
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
		}
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
		$editview_label[]=$mod_strings[$fieldlabel];
		$fieldvalue[]=$product_name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 63)
	{
		$editview_label[]=$mod_strings[$fieldlabel];
		if($value=='')
			$value=1;
		$options = Array();
		$pick_query="select * from duration_minutes order by sortorderid";
		$pickListResult = $adb->query($pick_query);
		$noofpickrows = $adb->num_rows($pickListResult);
		$salt_value = $col_fields["duration_minutes"];
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
			$options[$pickListValue] = $chk_val;
		}
		$fieldvalue[]=$value;
		$fieldvalue[]=$options;
	}
	elseif($uitype == 64)
	{
		$editview_label[]=$mod_strings[$fieldlabel];
		$date_format = parse_calendardate($app_strings['NTC_DATE_FORMAT']);
		$fieldvalue[] = $value;
	}
	elseif($uitype == 56)
	{
		$editview_label[]=$mod_strings[$fieldlabel];
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
		$fieldvalue[] = $contact_name;
		$fieldvalue[] = $value;
	}
	elseif($uitype == 61)
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
		$fieldvalue[] = $filename;
		$fieldvalue[] = $value;
	}
	elseif($uitype == 69)
	{
		$editview_label[]=$mod_strings[$fieldlabel];
		$image_lists=explode("###",$value);
		if(count($image_lists) > 1)
		{
			foreach($image_lists as $image)
			{
				$fieldvalue[] = $image;
			}
		}else
		{
			$fieldvalue[] = $value;
		}
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
				$potential_selected = "selected";

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
		$editview_label[] = array($app_strings['COMBO_LEADS'],
                                          $app_strings['COMBO_ACCOUNTS'],
                                          $app_strings['COMBO_POTENTIALS'],
                                          $app_strings['COMBO_PRODUCTS'],
                                          $app_strings['COMBO_INVOICES'],
                                          $app_strings['COMBO_PORDER'],
                                          $app_strings['COMBO_SORDER']
                                         );
                $editview_label[] = array($lead_selected,
                                          $account_selected,
					  $potential_selected,
                                          $product_selected,
                                          $invoice_selected,
                                          $porder_selected,
                                          $sorder_selected
                                         );
                $editview_label[] = array("Leads&action=Popup","Accounts&action=Popup","Potentials&action=Popup","Products&action=Popup","Invoice&action=Popup","PurchaseOrder&action=Popup","SalesOrder&action=Popup");
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
				$potential_selected = "selected";

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
		if($act_mode == "Task")
                {
                        $editview_label[] = array($app_strings['COMBO_LEADS'],
                                $app_strings['COMBO_ACCOUNTS'],
                                $app_strings['COMBO_POTENTIALS'],
                                $app_strings['COMBO_QUOTES'],
                                $app_strings['COMBO_PORDER'],
                                $app_strings['COMBO_SORDER'],
                                $app_strings['COMBO_INVOICES']
                                        );
			$editview_label[] = array($lead_selected,
                                $account_selected,
                                $potential_selected,
                                $quote_selected,
                                $purchase_selected,
                                $sales_selected,
                                $invoice_selected
                                        );
                        $editview_label[] = array("Leads&action=Popup","Accounts&action=Popup","Potentials&action=Popup","Quotes&action=Popup","PurchaseOrder&action=Popup","SalesOrder&action=Popup","Invoice&action=Popup");
                }
                else
                {
                        $editview_label[] = array($app_strings['COMBO_LEADS'],
                                $app_strings['COMBO_ACCOUNTS'],
                                $app_strings['COMBO_POTENTIALS'],
                                );
                        $editview_label[] = array($lead_selected,
                                $account_selected,
                                $potential_selected
                                );
                        $editview_label[] = array("Leads&action=Popup","Accounts&action=Popup","Potentials&action=Popup");

                }
		$fieldvalue[] =$parent_name;
		$fieldvalue[] = $value;
	}
	//added by rdhital/Raju for better email support
	elseif($uitype == 357)
	{
		$contact_selected = 'selected';
		$account_selected = '';
		$lead_selected = '';
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
					$account_selected = 'selected';
				}
				elseif ($pmodule=='Contacts'){
					require_once('modules/Contacts/Contact.php');
					$myfocus = new Contact();
					$myfocus->retrieve_entity_info($entityid,"Contacts");
					$fname=br2nl($myfocus->column_fields['firstname']);
					$lname=br2nl($myfocus->column_fields['lastname']);
					$fullname=$lname.' '.$fname;
					$contact_selected = 'selected';
				}
				elseif ($pmodule=='Leads'){
					require_once('modules/Leads/Lead.php');
					$myfocus = new Lead();
					$myfocus->retrieve_entity_info($entityid,"Leads");
					$fname=br2nl($myfocus->column_fields['firstname']);
					$lname=br2nl($myfocus->column_fields['lastname']);
					$fullname=$lname.' '.$fname;
					$lead_selected = 'selected';
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
					if($parent_module == "Leads")
					{
						$sql = "select firstname,lastname,email from leaddetails where leadid=".$mycrmid;
						$result = $adb->query($sql);
						$first_name = $adb->query_result($result,0,"firstname");
						$last_name = $adb->query_result($result,0,"lastname");
						$myemail=$adb->query_result($result,0,"email");
						$parent_id .=$mycrmid.'@0|' ; //make it such that the email adress sent is remebered and only that one is retrived
						$parent_name .= $last_name.' '.$first_name.'<'.$myemail.'>; ';
						$lead_selected = 'selected';
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
						$contact_selected = 'selected';
					}
					elseif($parent_module == "Accounts")
					{
						$sql = "select * from  account where accountid=".$mycrmid;
						$result = $adb->query($sql);
						$account_name = $adb->query_result($result,0,"accountname");
						$myemail=$adb->query_result($result,0,"email1");
						$parent_id .=$mycrmid.'@0|'  ;//make it such that the email adress sent is remebered and only that one is retrived
						$parent_name .= $account_name.'<'.$myemail.'>; ';
						$account_selected = 'selected';
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
				$app_strings['COMBO_CONTACTS']=>$contact_selected,
				$app_strings['COMBO_ACCOUNTS']=>$account_selected,
				$app_strings['COMBO_LEADS']=>$lead_selected
				);
		$fieldvalue[] =$parent_name;
		$fieldvalue[] = $parent_id;

	}
	//end of rdhital/Raju
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
		$editview_label[] = array($app_strings['COMBO_CONTACTS'],
                                        $app_strings['COMBO_ACCOUNTS']
                                        );
                $editview_label[] = array($contact_selected,
                                        $account_selected
                                        );
                $editview_label[] = array("Contacts","Accounts");
		$fieldvalue[] = $parent_name;
		$fieldvalue[] = $value;
	}
	
	elseif($uitype == 71 || $uitype == 72)
	{
		$currencyid=fetchCurrency($current_user->id);
		$currency=getCurrencySymbol($currencyid);
		$rate = getConversionRate($currencyid,$currency);
		$editview_label[]=$mod_strings[$fieldlabel].': ('.$currency.')';
		if($value!='')
		        $fieldvalue[] = convertFromDollar($value,$rate);
		else
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
		$pop_type = 'specific';
		if($uitype == 81)
		{
			$pop_type = 'specific_vendor_address';
		}
		$editview_label[]=$mod_strings[$fieldlabel];
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
		$editview_label[]=$mod_strings[$fieldlabel];
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
		$editview_label[]=$mod_strings[$fieldlabel];
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
		$editview_label[]=$mod_strings[$fieldlabel];
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
		$editview_label[]=$mod_strings[$fieldlabel];
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
		$editview_label[]=$mod_strings[$fieldlabel];
		$custfld .= '<td valign="top" colspan=3>&nbsp;<input type="radio" name="set_reminder" value="Yes" '.$SET_REM.'>&nbsp;'.$mod_strings['LBL_YES'].'&nbsp;<input type="radio" name="set_reminder" value="No">&nbsp;'.$mod_strings['LBL_NO'].'&nbsp;';
		$day_options = getReminderSelectOption(0,31,'remdays',$rem_days);
		$hr_options = getReminderSelectOption(0,23,'remhrs',$rem_hrs);
		$min_options = getReminderSelectOption(1,59,'remmin',$rem_min);
		$custfld .= '&nbsp;&nbsp;'.$day_options.' &nbsp;'.$mod_strings['LBL_DAYS'].'&nbsp;&nbsp;'.$hr_options.'&nbsp;'.$mod_strings['LBL_HOURS'].'&nbsp;&nbsp;'.$min_options.'&nbsp;'.$mod_strings['LBL_MINUTES'].'&nbsp;&nbsp;'.$mod_strings['LBL_BEFORE_EVENT'].'</td>';
		$fieldvalue[] = array(array(0,32,'remdays','days',$rem_days),array(0,24,'remhrs','hours',$rem_hrs),array(1,60,'remmin','minutes  before event',$rem_min));
		$fieldvalue[] = array($SET_REM,$mod_strings['LBL_YES'],$mod_strings['LBL_NO']);
		$SET_REM = '';
	}
	else
	{
		//Added condition to set the subject if click Reply All from web mail
		if($_REQUEST['module'] == 'Emails' && $_REQUEST['mg_subject'] != '')
		{
			$value = $_REQUEST['mg_subject'];
		}
		$editview_label[]=$mod_strings[$fieldlabel];
		$fieldvalue[] = $value;
	}

	// Mike Crowe Mod --------------------------------------------------------force numerics right justified.
	if ( !eregi("id=",$custfld) )
		$custfld = preg_replace("/<input/iS","<input id='$fieldname' ",$custfld);

	if ( in_array($uitype,array(71,72,7,9,90)) )
	{
		$custfld = preg_replace("/<input/iS","<input align=right ",$custfld);
	}
	$final_arr[]=$ui_type;
	$final_arr[]=$editview_label;
	$final_arr[]=$editview_fldname;
	$final_arr[]=$fieldvalue;
	return $final_arr;
}

/** This function returns the invoice object populated with the details from sales order object.
* Param $focus - Invoice object
* Param $so_focus - Sales order focus
* Param $soid - sales order id
* Return type is an object array
*/

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

/** This function returns the invoice object populated with the details from quote object.
* Param $focus - Invoice object
* Param $quote_focus - Quote order focus
* Param $quoteid - quote id
* Return type is an object array
*/


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

/** This function returns the sales order object populated with the details from quote object.
* Param $focus - Sales order object
* Param $quote_focus - Quote order focus
* Param $quoteid - quote id
* Return type is an object array
*/

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

/** This function returns the detailed list of products associated to a given entity or a record.
* Param $module - module name
* Param $focus - module object
* Param $seid - sales entity id
* Return type is an object array
*/


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
		
		if($i%2 == 0)		$row_class = "evenListRow";
		else			$row_class = "oddListRow";

		$product_Detail[$i]['txtProduct'.$i]= $productname;

		if($module != 'PurchaseOrder' && $focus->object_name != 'Order')
		{
			$product_Detail[$i]['qtyInStock'.$i]=$qtyinstock;
		}
		$product_Detail[$i]['txtQty'.$i]=$qty;
		$product_Detail[$i]['unitPrice'.$i]=$unitprice;
		$product_Detail[$i]['txtListPrice'.$i]=$listprice;
		$product_Detail[$i]['total'.$i]=$total;

		if($i != 1)
		{
			$product_Detail[$i]['delRow'.$i]="Del";
		}

		$product_Detail[$i]['hdnProductId'.$i] = $productid;
		$product_Detail[$i]['hdnRowStatus'.$i] = '';
		$product_Detail[$i]['hdnTotal'.$i] = $total;

	}
	return $product_Detail;

}

/** This function returns the no of products associated to the given entity or a record.
* Param $module - module name
* Param $focus - module object
* Param $seid - sales entity id
* Return type is an object array
*/

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

/** This function returns the detail block information of a record for given block id.
* Param $module - module name
* Param $block - block name
* Param $mode - view type (detail/edit/create)
* Param $col_fields - fields array
* Param $tabid - tab id
* Param $info_type - information type (basic/advance) default ""
* Return type is an object array
*/

function getBlockInformation($module, $block, $mode, $col_fields,$tabid,$info_type='')
{
	global $adb;
	$editview_arr = Array();

	global $current_user;
	require('user_privileges/user_privileges_'.$current_user->id.'.php');
	
	if ($info_type != '')
	{
		if($is_admin==true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] ==0)
        	{

                	$sql = "select field.* from field where field.tabid=".$tabid." and field.block=".$block ." and field.displaytype=1 and info_type = '".$info_type."' order by sequence";
        	}
        	else
        	{
                	$profileList = getCurrentUserProfileList();

			$sql = "select field.* from field inner join profile2field on profile2field.fieldid=field.fieldid inner join def_org_field on def_org_field.fieldid=field.fieldid  where field.tabid=".$tabid." and field.block=".$block ." and field.displaytype=1 and info_type = '".$info_type."' and profile2field.visible=0 and def_org_field.visible=0 and profile2field.profileid in ".$profileList.=" group by field.fieldid order by sequence";
		}
	}
	else
	{
		if($is_admin==true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] ==0)
        	{

                	$sql = "select field.* from field where field.tabid=".$tabid." and field.block=".$block ." and field.displaytype=1 order by sequence";
        	}
        	else
        	{
                	$profileList = getCurrentUserProfileList();

			$sql = "select field.* from field inner join profile2field on profile2field.fieldid=field.fieldid inner join def_org_field on def_org_field.fieldid=field.fieldid  where field.tabid=".$tabid." and field.block=".$block ." and field.displaytype=1 and profile2field.visible=0 and def_org_field.visible=0 and profile2field.profileid in ".$profileList.=" group by field.fieldid order by sequence";
		}
	}

        $result = $adb->query($sql);
	$noofrows = $adb->num_rows($result);
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

		$custfld = getOutputHtml($uitype, $fieldname, $fieldlabel, $maxlength, $col_fields,$generatedtype,$module);
		$editview_arr[]=$custfld;
		if ($mvAdd_flag == true)
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
			$custfld = getOutputHtml($uitype, $fieldname, $fieldlabel, $maxlength, $col_fields,$generatedtype,$module);			
			$editview_arr[]=$custfld;
		}
	}
	for ($i=0,$j=0;$i<count($editview_arr);$i=$i+2,$j++)
        {
                $key1=$editview_arr[$i];
                if(is_array($editview_arr[$i+1]))
                {
                        $key2=$editview_arr[$i+1];
                }
		else
		{
			$key2 =array();
		}
                $return_data[$j]=array(0 => $key1,1 => $key2);
        }
        return $return_data;	
		
}

/** This function returns the data type of the fields, with field label, which is used for javascript validation.
* Param $validationData - array of fieldnames with datatype
* Return type array 
*/


function split_validationdataArray($validationData)
{
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
	$data['fieldname'] = $fieldName;
	$data['fieldlabel'] = $fieldLabel;
	$data['datatype'] = $fldDataType;
	return $data;
}


?>
