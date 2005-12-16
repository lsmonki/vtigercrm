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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/include/utils/DetailViewUtils.php,v 1.188 2005/04/29 05:5 * 4:39 rank Exp  
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


function getDetailViewOutputHtml($uitype, $fieldname, $fieldlabel, $col_fields,$generatedtype)
{
	global $adb;
	global $mod_strings;
	global $app_strings;
	global $current_user;
	$fieldlabel = from_html($fieldlabel);
	$custfld = '';
	$value ='';

	if($generatedtype == 2)
		$mod_strings[$fieldlabel] = $fieldlabel;

        if($col_fields[$fieldname]=='--None--')
                $col_fields[$fieldname]='';
	
	if($uitype == 13)
	{
		$custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
		$custfld .= '<td width="30%" valign="top" class="dataField"><a href="mailto:'.$col_fields[$fieldname].'">'.$col_fields[$fieldname].'</a></td>';
	}
	elseif($uitype == 17)
	{
		$custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
		$custfld .= '<td width="30%" valign="top" class="dataField"><a href="http://'.$col_fields[$fieldname].'" target="_blank">'.$col_fields[$fieldname].'</a></td>';
	}
	elseif($uitype == 19)
	{
		$col_fields[$fieldname]= make_clickable(nl2br($col_fields[$fieldname]));
		$custfld .= '<td width="20%" class="dataLabel" valign="top">'.$mod_strings[$fieldlabel].':</td>';
		$custfld .= '<td colspan="3" valign="top" class="dataField">'.$col_fields[$fieldname].'</td>'; // Armando LC<scher 10.08.2005 -> B'descriptionSpan -> Desc: inserted colspan="3"
	}
	elseif($uitype == 20 || $uitype == 21 || $uitype == 22 || $uitype == 24) // Armando LC<scher 11.08.2005 -> B'descriptionSpan -> Desc: removed $uitype == 19 and made an aditional elseif above
	{
		$col_fields[$fieldname]=nl2br($col_fields[$fieldname]);
		$custfld .= '<td width="20%" class="dataLabel" valign="top">'.$mod_strings[$fieldlabel].':</td>';
		$custfld .= '<td valign="top" class="dataField">'.$col_fields[$fieldname].'</td>'; // Armando LC<scher 10.08.2005 -> B'descriptionSpan -> Desc: inserted colspan="3"
	}
	elseif($uitype == 51 || $uitype == 50 || $uitype == 73)
	{
		$account_id = $col_fields[$fieldname];
		if($account_id != '')
		{
			$account_name = getAccountName($account_id);
		}
		//Account Name View	
		$custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
		$custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module=Accounts&action=DetailView&record='.$account_id.'">'.$account_name.'</a></td>';
		

	}
	elseif($uitype == 52 || $uitype == 77)
	{
		$custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
		$user_id = $col_fields[$fieldname];
		$user_name = getUserName($user_id);
		if(is_admin($current_user))
		{
			$custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module=Users&action=DetailView&record='.$user_id.'">'.$user_name.'</a></td>';
		}
		else
		{
			$custfld .= '<td width="30%" valign="top" class="dataField">'.$user_name.'</td>';
		}
	}
	elseif($uitype == 53)
	{
		$user_id = $col_fields[$fieldname];
		if($user_id != 0)
		{
			$custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].' '.$app_strings['LBL_USER'].' :</td>';
			$user_name = getUserName($user_id);
			if(is_admin($current_user))
			{
				$custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module=Users&action=DetailView&record='.$user_id.'">'.$user_name.'</a></td>';
			}
			else
			{
				$custfld .= '<td width="30%" valign="top" class="dataField">'.$user_name.'</td>';
			}
		}
		elseif($user_id == 0)
		{
			$custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].' '.$app_strings['LBL_GROUP'].' :</td>';
			$id = $col_fields["record_id"];	
			$module = $col_fields["record_module"];
			$groupname = getGroupName($id, $module);
			if(is_admin($current_user))
                        {
				$custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module=Users&action=UserInfoUtil&groupname='.$groupname.'">'.$groupname.'</a></td>';
			}
			else
			{
				$custfld .= '<td width="30%" valign="top" class="dataField">'.$groupname.'</td>';
			}			
		}
		
	}
	elseif($uitype == 55)
        {
                $custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
                $value = $col_fields[$fieldname];
                $sal_value = $col_fields["salutationtype"];
                if($sal_value == '--None--')
                {
                        $sal_value='';
                }
                $custfld .= '<td width="30%" valign="top" class="dataField">'.$sal_value.' '.$value.'</td>';
        }
	elseif($uitype == 56)
	{
		$custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
		$value = $col_fields[$fieldname];
		if($value == 1)
		{
			$display_val = 'yes';
		}
		else
		{
			$display_val = '';
		}
		$custfld .= '<td width="30%" valign="top" class="dataField">'.$display_val.'</td>';
	}
	elseif($uitype == 57)
        {
                $custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
                $contact_id = $col_fields[$fieldname];
                if($contact_id != '')
                {
                        $contact_name = getContactName($contact_id);
                }

                $custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module=Contacts&action=DetailView&record='.$contact_id.'">'.$contact_name.'</a></td>';
        }
	elseif($uitype == 59)
	{
		$product_id = $col_fields[$fieldname];
		if($product_id != '')
		{
			$product_name = getProductName($product_id);
		}
		//Account Name View	
		$custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
		$custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module=Products&action=DetailView&record='.$product_id.'">'.$product_name.'</a></td>';
		
	}
        elseif($uitype == 61)
        {
                global $adb;

                $attachmentid=$adb->query_result($adb->query("select * from seattachmentsrel where crmid = ".$col_fields['record_id']),0,'attachmentsid');
		if($col_fields[$fieldname] == '' && $attachmentid != '')
		{
				$attachquery = "select * from attachments where attachmentsid=".$attachmentid;
        		        $col_fields[$fieldname] = $adb->query_result($adb->query($attachquery),0,'name');
		}
                $custfldval = '<a href = "index.php?module=uploads&action=downloadfile&return_module='.$col_fields['record_module'].'&fileid='.$attachmentid.'&filename='.$col_fields[$fieldname].'">'.$col_fields[$fieldname].'</a>';

                $custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
                $custfld .= '<td width="30%" valign="top" class="dataField">'.$custfldval.'</td>';
        }
	elseif($uitype == 69)
	{
			
                $custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
		if($col_fields[$fieldname] != '')
		{
			$imgpath = "test/product/".$col_fields[$fieldname];
			
                	$custfld .= '<td width="30%" valign="top" class="dataField"><img src="'.$imgpath.'" border="0"></td>';
		}
		else
		{
                	$custfld .= '<td width="30%" valign="top" class="dataField"></td>';
		}
		
	}
	elseif($uitype == 62)
	{
		$value = $col_fields[$fieldname];
		if($value != '')
		{
			$parent_module = getSalesEntityType($value);
			if($parent_module == "Leads")
			{
				$custfld .= '<td width="20%" class="dataLabel">'.$app_strings['LBL_LEAD_NAME'].':</td>';
				$sql = "select * from leaddetails where leadid=".$value;
				$result = $adb->query($sql);
				$first_name = $adb->query_result($result,0,"firstname");
				$last_name = $adb->query_result($result,0,"lastname");

				$custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$last_name.' '.$first_name.'</a></td>';
			}
			elseif($parent_module == "Accounts")
			{
				$custfld .= '<td width="20%" class="dataLabel">'.$app_strings['LBL_ACCOUNT_NAME'].':</td>';
				$sql = "select * from  account where accountid=".$value;
				$result = $adb->query($sql);
				$account_name = $adb->query_result($result,0,"accountname");

				$custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$account_name.'</a></td>';
			}
			elseif($parent_module == "Potentials")
			{
				$custfld .= '<td width="20%" class="dataLabel">'.$app_strings['LBL_POTENTIAL_NAME'].':</td>';
				$sql = "select * from  potential where potentialid=".$value;
				$result = $adb->query($sql);
				$potentialname = $adb->query_result($result,0,"potentialname");

				$custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$potentialname.'</a></td>';
			}
			elseif($parent_module == "Products")
			{
				$custfld .= '<td width="20%" class="dataLabel">'.$app_strings['LBL_PRODUCT_NAME'].':</td>';
				$sql = "select * from  products where productid=".$value;
				$result = $adb->query($sql);
				$productname= $adb->query_result($result,0,"productname");

				$custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$productname.'</a></td>';
			}
			elseif($parent_module == "PurchaseOrder")
			{
				$custfld .= '<td width="20%" class="dataLabel">'.$app_strings['LBL_PORDER_NAME'].':</td>';
				$sql = "select * from  purchaseorder where purchaseorderid=".$value;
				$result = $adb->query($sql);
				$pordername= $adb->query_result($result,0,"subject");

				$custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$pordername.'</a></td>';
			}
			elseif($parent_module == "SalesOrder")
			{
				$custfld .= '<td width="20%" class="dataLabel">'.$app_strings['LBL_SORDER_NAME'].':</td>';
				$sql = "select * from  salesorder where salesorderid=".$value;
				$result = $adb->query($sql);
				$sordername= $adb->query_result($result,0,"subject");

				$custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module=SalesOrder&action=DetailView&record='.$value.'">'.$sordername.'</a></td>';
			}
			elseif($parent_module == "Invoice")
			{
				$custfld .= '<td width="20%" class="dataLabel">'.$app_strings['LBL_INVOICE_NAME'].':</td>';
				$sql = "select * from  invoice where invoiceid=".$value;
				$result = $adb->query($sql);
				$invoicename= $adb->query_result($result,0,"subject");

				$custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$invoicename.'</a></td>';
			}
		}
		else
		{
			$custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
			$custfld .= '<td width="30%" valign="top" class="dataField">'.$value.'</td>';
		}


	}
	elseif($uitype == 66)
	{
		$value = $col_fields[$fieldname];
		if($value != '')
		{
			$parent_module = getSalesEntityType($value);
			if($parent_module == "Leads")
			{
				$custfld .= '<td width="20%" class="dataLabel">'.$app_strings['LBL_LEAD_NAME'].':</td>';
				$sql = "select * from leaddetails where leadid=".$value;
				$result = $adb->query($sql);
				$first_name = $adb->query_result($result,0,"firstname");
				$last_name = $adb->query_result($result,0,"lastname");

				$custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$last_name.' '.$first_name.'</a></td>';
			}
			elseif($parent_module == "Accounts")
			{
				$custfld .= '<td width="20%" class="dataLabel">'.$app_strings['LBL_ACCOUNT_NAME'].':</td>';
				$sql = "select * from  account where accountid=".$value;
				$result = $adb->query($sql);
				$account_name = $adb->query_result($result,0,"accountname");

				$custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$account_name.'</a></td>';
			}
			elseif($parent_module == "Potentials")
			{
				$custfld .= '<td width="20%" class="dataLabel">'.$app_strings['LBL_POTENTIAL_NAME'].':</td>';
				$sql = "select * from  potential where potentialid=".$value;
				$result = $adb->query($sql);
				$potentialname = $adb->query_result($result,0,"potentialname");

				$custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$potentialname.'</a></td>';
			}
			elseif($parent_module == "Quotes")
                        {
                                $custfld .= '<td width="20%" class="dataLabel">'.$app_strings['LBL_QUOTE_NAME'].':</td>';
                                $sql = "select * from  quotes where quoteid=".$value;
                                $result = $adb->query($sql);
                                $quotename = $adb->query_result($result,0,"subject");

                                $custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$quotename.'</a></td>';
                        }
			elseif($parent_module == "PurchaseOrder")
                        {
                                $custfld .= '<td width="20%" class="dataLabel">'.$app_strings['LBL_PORDER_NAME'].':</td>';
                                $sql = "select * from  purchaseorder where purchaseorderid=".$value;
                                $result = $adb->query($sql);
                                $pordername = $adb->query_result($result,0,"subject");

                                $custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$pordername.'</a></td>';
                        }
                        elseif($parent_module == "SalesOrder")
                        {
                                $custfld .= '<td width="20%" class="dataLabel">'.$app_strings['LBL_SORDER_NAME'].':</td>';
                                $sql = "select * from  salesorder where salesorderid=".$value;
                                $result = $adb->query($sql);
                                $sordername = $adb->query_result($result,0,"subject");

                                $custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module=SalesOrder&action=DetailView&record='.$value.'">'.$sordername.'</a></td>';
                        }
			elseif($parent_module == "Invoice")
                        {
                                $custfld .= '<td width="20%" class="dataLabel">'.$app_strings['LBL_INVOICE_NAME'].':</td>';
                                $sql = "select * from  invoice where invoiceid=".$value;
                                $result = $adb->query($sql);
                                $invoicename = $adb->query_result($result,0,"subject");

                                $custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$invoicename.'</a></td>';
                        }

		}
		else
		{
			$custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
			$custfld .= '<td width="30%" valign="top" class="dataField">'.$value.'</td>';
		}
	}
	elseif($uitype == 67)
	{
		$value = $col_fields[$fieldname];
		if($value != '')
		{
			$parent_module = getSalesEntityType($value);
			if($parent_module == "Leads")
			{
				$custfld .= '<td width="20%" class="dataLabel">'.$app_strings['LBL_LEAD_NAME'].':</td>';
				$sql = "select * from leaddetails where leadid=".$value;
				$result = $adb->query($sql);
				$first_name = $adb->query_result($result,0,"firstname");
				$last_name = $adb->query_result($result,0,"lastname");

				$custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$last_name.' '.$first_name.'</a></td>';
			}
			elseif($parent_module == "Contacts")
			{
				$custfld .= '<td width="20%" class="dataLabel">'.$app_strings['LBL_CONTACT_NAME'].':</td>';
				$sql = "select * from  contactdetails where contactid=".$value;
				$result = $adb->query($sql);
				$first_name = $adb->query_result($result,0,"firstname");
                                $last_name = $adb->query_result($result,0,"lastname");

                                $custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$last_name.' '.$first_name.'</a></td>';
			}
		}
		else
		{
			$custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
			$custfld .= '<td width="30%" valign="top" class="dataField">'.$value.'</td>';
		}
	}
	//added by raju/rdhital for better emails
	elseif($uitype == 357)
	{
		$value = $col_fields[$fieldname];
		if($value != '')
		{
			$parent_name='';
			$parent_id='';
			$myemailid= $_REQUEST['record'];
			$mysql = "select crmid from seactivityrel where activityid=".$myemailid;
			$myresult = $adb->query($mysql);
			$mycount=$adb->num_rows($myresult);
			if ($mycount>1){
				$custfld .= '<td width="20%" class="dataLabel">'.$app_strings['LBL_RELATED_TO'].':</td>';
				$custfld .= '<td width="30%" valign="top" class="dataField">'.$app_strings['LBL_MULTIPLE'].'</td>';
			}
			else
			{
				$parent_module = getSalesEntityType($value);
				if($parent_module == "Leads")
				{
					$custfld .= '<td width="20%" class="dataLabel">'.$app_strings['LBL_LEAD_NAME'].':</td>';
					$sql = "select * from leaddetails where leadid=".$value;
					$result = $adb->query($sql);
					$first_name = $adb->query_result($result,0,"firstname");
					$last_name = $adb->query_result($result,0,"lastname");
					$custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$last_name.' '.$first_name.'</a></td>';
				}
				elseif($parent_module == "Contacts")
				{
					$custfld .= '<td width="20%" class="dataLabel">'.$app_strings['LBL_CONTACT_NAME'].':</td>';
					$sql = "select * from  contactdetails where contactid=".$value;
					$result = $adb->query($sql);
					$first_name = $adb->query_result($result,0,"firstname");
					$last_name = $adb->query_result($result,0,"lastname");
					$custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$last_name.' '.$first_name.'</a></td>';
				}
				elseif($parent_module == "Accounts")
				{
					$custfld .= '<td width="20%" class="dataLabel">'.$app_strings['LBL_ACCOUNT_NAME'].':</td>';
					$sql = "select * from  account where accountid=".$value;
					$result = $adb->query($sql);
					$accountname = $adb->query_result($result,0,"accountname");
					$custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$accountname.'</a></td>';
				}

			}
		}
		else
		{
			$custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
			$custfld .= '<td width="30%" valign="top" class="dataField">'.$value.'</td>';
		}
	}//Code added by raju for better email ends

	elseif($uitype == 68)
	{
		$value = $col_fields[$fieldname];
		if($value != '')
		{
			$parent_module = getSalesEntityType($value);
			if($parent_module == "Contacts")
			{
				$custfld .= '<td width="20%" class="dataLabel">'.$app_strings['LBL_CONTACT_NAME'].':</td>';
				$sql = "select * from  contactdetails where contactid=".$value;
				$result = $adb->query($sql);
				$first_name = $adb->query_result($result,0,"firstname");
                                $last_name = $adb->query_result($result,0,"lastname");

                                $custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$last_name.' '.$first_name.'</a></td>';
			}
			elseif($parent_module == "Accounts")
			{
				$custfld .= '<td width="20%" class="dataLabel">'.$app_strings['LBL_ACCOUNT_NAME'].':</td>';
				$sql = "select * from account where accountid=".$value;
				$result = $adb->query($sql);
				$account_name = $adb->query_result($result,0,"accountname");

				$custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$account_name.'</a></td>';
			}

		}
		else
		{
			$custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
			$custfld .= '<td width="30%" valign="top" class="dataField">'.$value.'</td>';
		}
	}

	elseif($uitype==63)
        {
	   $custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';	
           $custfld .= '<td width="30%" valign="top" class="dataField">'.$col_fields[$fieldname].'h&nbsp; '.$col_fields['duration_minutes'].'m</td>';
        }
	elseif($uitype == 6)
        {
		$custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
	
          	if($col_fields[$fieldname]=='0')
                $col_fields[$fieldname]='';
		if($col_fields['time_start']!='')
                {
                       $start_time = $col_fields['time_start'];
                }
		if($col_fields[$fieldname] == '0000-00-00')
		{
			$displ_date = '';	
		}
		else
		{
			$displ_date = getDisplayDate($col_fields[$fieldname]);
		}
	
          	$custfld .= '<td width="30%" valign="top" class="dataField">'.$displ_date.'&nbsp;'.$start_time.'</td>';
	}
	elseif($uitype == 5 || $uitype == 23 || $uitype == 70)
	{
		$custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
		$cur_date_val = $col_fields[$fieldname];
		if($cur_date_val == '0000-00-00')
		{
			$display_val = '';	
		}
		else
		{
			$display_val = getDisplayDate($cur_date_val);
		}
		$custfld .= '<td width="30%" valign="top" class="dataField">'.$display_val.'</td>';	
	}
	elseif($uitype == 71 || $uitype == 72)
	{
		$custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
		$display_val = '';
		if($col_fields[$fieldname] != '' && $col_fields[$fieldname] != 0)
		{	
			$curr_symbol = getCurrencySymbol();
			$display_val = $curr_symbol.' '.$col_fields[$fieldname];
		}
		$custfld .= '<td width="30%" valign="top" class="dataField">'.$display_val.'</td>';	
	}
	elseif($uitype == 75 || $uitype == 81)
        {
                $custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
                $vendor_id = $col_fields[$fieldname];
                if($vendor_id != '')
                {
                        $vendor_name = getVendorName($vendor_id);
                }

                $custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module=Products&action=VendorDetailView&record='.$vendor_id.'">'.$vendor_name.'</a></td>';
        }
	elseif($uitype == 76)
        {
                $custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
                $potential_id = $col_fields[$fieldname];
                if($potential_id != '')
                {
                        $potential_name = getPotentialName($potential_id);
                }

                $custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module=Potentials&action=DetailView&record='.$potential_id.'">'.$potential_name.'</a></td>';
        }
	elseif($uitype == 78)
        {
                $custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
                $quote_id = $col_fields[$fieldname];
                if($quote_id != '')
                {
                        $quote_name = getQuoteName($quote_id);
                }

                $custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module=Quotes&action=DetailView&record='.$quote_id.'">'.$quote_name.'</a></td>';
        }
	elseif($uitype == 79)
        {
                $custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
                $purchaseorder_id = $col_fields[$fieldname];
                if($purchaseorder_id != '')
                {
                        $purchaseorder_name = getPoName($purchaseorder_id);
                }

                $custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module=PurchaseOrder&action=DetailView&record='.$purchaseorder_id.'">'.$purchaseorder_name.'</a></td>';
        }
	elseif($uitype == 80)
        {
                $custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
                $salesorder_id = $col_fields[$fieldname];
                if($salesorder_id != '')
                {
                        $salesorder_name = getSoName($salesorder_id);
                }

                $custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module=SalesOrder&action=DetailView&record='.$salesorder_id.'">'.$salesorder_name.'</a></td>';
        }
	elseif($uitype == 30)
	{
		$rem_days = 0;
		$rem_hrs = 0;
		$rem_min = 0;
		$reminder_str ="";
		$rem_days = floor($col_fields[$fieldname]/(24*60));
		$rem_hrs = floor(($col_fields[$fieldname]-$rem_days*24*60)/60);
		$rem_min = ($col_fields[$fieldname]-$rem_days*24*60)%60;
                 
                $custfld .= '<td width="20%" class="dataLabel" valign="top">'.$mod_strings[$fieldlabel].':</td>';
		if($col_fields[$fieldname])
                {
                        $reminder_str= $rem_days.'&nbsp;'.$mod_strings['LBL_DAYS'].'&nbsp;'.$rem_hrs.'&nbsp;'.$mod_strings['LBL_HOURS'].'&nbsp;'.$rem_min.'&nbsp;'.$mod_strings['LBL_MINUTES'].'&nbsp;&nbsp;'.$mod_strings['LBL_BEFORE_EVENT'];
                }
                $custfld .= '<td valign="top" colspan=3 class="datafield">&nbsp;'.$reminder_str.'</td>';
	}
	else
	{
	  $custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
	
          if($col_fields[$fieldname]=='0')
                $col_fields[$fieldname]='';
	
          $custfld .= '<td width="30%" valign="top" class="dataField">'.$col_fields[$fieldname].'</td>';
	}
	return $custfld;	
}




function getDetailAssociatedProducts($module,$focus)
{
	global $adb;
	global $theme;
	global $log;
        $theme_path="themes/".$theme."/";
        $image_path=$theme_path."images/";
	 $log->debug("in getDetailAssociatedProducts. Module is  ".$module);

	$output = '';
	$output .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formOuterBorder">';
	$output .=  '<tr><td  class="formBorder">';
	$output .= '<div style="padding:2 0 2 0"><strong>Product Details</strong></div> <div id="productList">';
    $output .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formBorder">';
    $output .= '<tr class="moduleListTitle" height="20" id="tablehead">';
    $output .= '<td width="20%" style="padding:3px;">Product</td>';
    $output .= '<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
    if($module != 'PurchaseOrder')
    {
    	$output .= '<td width="12%" style="padding:3px;">Qty In Stock</td>';
    	$output .= '<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
    }	
    $output .= '<td width="12%" style="padding:3px;">Qty</td>';
    $output .= '<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
    $output .= '<td width="15%" style="padding:3px;">Unit Price</td>';
    $output .= '<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
    $output .= '<td width="16%" style="padding:3px;">List Price</td>';
    $output .= '<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
    $output .= '<td style="padding:3px;"><div align="center">Total</div></td>';
    $output .=  '</tr>';
    $output .=  '<tr id="tableheadline">';
    $output .=  '<td colspan="11" height="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
    $output .=  '</tr>';
		
	if($module == 'Quotes')
	{
		$query="select products.productname,products.unit_price,products.qtyinstock,quotesproductrel.* from quotesproductrel inner join products on products.productid=quotesproductrel.productid where quoteid=".$focus->id;
	}
	elseif($module == 'PurchaseOrder')
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
	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	for($i=1;$i<=$num_rows;$i++)
	{
		$productname=$adb->query_result($result,$i-1,'productname');
		$unitprice=$adb->query_result($result,$i-1,'unit_price');
		$productid=$adb->query_result($result,$i-1,'productid');
		$qtyinstock=$adb->query_result($result,$i-1,'qtyinstock');
		$qty=$adb->query_result($result,$i-1,'quantity');
		$listprice=$adb->query_result($result,$i-1,'listprice');
		$total = $qty*$listprice;

		if($i%2 == 0)
		{
			$row_class = "evenListRow";
		}
		else
		{
			$row_class = "oddListRow";
		}

		$output .= '<tr class="'.$row_class.'">';
        	$output .= '<td height="25" style="padding:3px;" nowrap>'.$productname.'</td>';
        	$output .= '<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
		if($module != 'PurchaseOrder')
		{	
                	$output .= '<td style="padding:3px;">'.$qtyinstock.'</td>';
	        	$output .= '<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
		}
	        $output .= '<td style="padding:3px;">'.$qty.'</td>';
	        $output .='<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
                $output .= '<td style="padding:3px;">'.$unitprice.'</td>';
	        $output .= '<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
	        $output .= '<td style="padding:3px;">'.$listprice.'</td>';
        	$output .= '<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
        	$output .= '<td style="padding:3px;"><div id="total'.$i.'" align="right">'.$total.'</div></td>';
                $output .= '</tr>';


	}
	$output .= '<tr id="tableheadline">';
        $output .= '<td colspan="14" height="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td></tr>';
	$output .= '</table>';
  	$output .= '</div>';
	$output .= '<table width="100%" border="0" cellspacing="2" cellpadding="2" bgcolor="#FFFFFF">';
        $output .= '<tr>'; 
	$output .= '<td width="150"></td>';
      	$output .= '<td><div align="right"><b>Sub Total:</b></div></td>';
        $output .= '<td width="150"><div align="right" style="border:1px solid #000;padding:2px">&nbsp;'.$focus->column_fields['hdnSubTotal'].'</div></td>';
        $output .= '</tr>';
        $output .= '<tr>'; 
	$output .=  '<td>&nbsp;</td>';
        $output .= '<td><div align="right"><b>Tax:</b></div></td>';
        $output .= '<td width="150"><div align="right" style="border:1px solid #000;padding:2px">&nbsp;'.$focus->column_fields['txtTax'].'</div></td>';
      $output .= '</tr>';
      $output .= '<tr>'; 
      $output .= '<td>&nbsp;</td>';
      $output .= '<td><div align="right"><b>Adjustment:</b></div></td>';
      $output .= '<td width="150"><div align="right"><div align="right" style="border:1px solid #000;padding:2px">&nbsp;'.$focus->column_fields['txtAdjustment'].'</div></td>';
      $output .= '</tr>';
      $output .= '<tr>'; 
      $output .= '<td>&nbsp;</td>';
      $output .= '<td><div align="right"><b>Grand Total:</b></div></td>';
      $output .= '<td width="150"><div id="grandTotal" align="right" style="border:1px solid #000;padding:2px">&nbsp;'.$focus->column_fields['hdnGrandTotal'].'</div></td>';
    $output .= '</tr>';
    $output .= '</table>';
    $output .= '</td></tr></table>';	

	return $output;

}

function getRelatedLists($module,$focus)
{
	global $adb;
	global $profile_id;
	$mod_dir_name=getModuleDirName($module);
	$tab_per_Data = getAllTabsPermission($profile_id);
	$permissionData = $_SESSION['action_permission_set'];
	$inc_file = 'modules/'.$mod_dir_name.'/RenderRelatedListUI.php';
	include($inc_file);
	$cur_tab_id = getTabid($module);

	$sql1 = "select * from relatedlists where tabid=".$cur_tab_id;
	$result = $adb->query($sql1);
	$num_row = $adb->num_rows($result);
	for($i=0; $i<$num_row; $i++)
	{
		$rel_tab_id = $adb->query_result($result,$i,"related_tabid");
		$funtion_name = $adb->query_result($result,$i,"name");
		if($rel_tab_id != 0)
		{
			if($tab_per_Data[$rel_tab_id] == 0)
			{
		        	if($permissionData[$rel_tab_id][3] == 0)
        			{
		                	$focus_list = & $focus->$funtion_name($focus->id);
        			}
			}
		}
		else
		{
			$focus_list = & $focus->$funtion_name($focus->id);
		}
	}

}


function getDetailBlockInformation($module, $block, $col_fields)
{
	//retreive the tabid	
	global $adb;
	$tabid = getTabid($module);
        global $profile_id;

	//retreive the fields from database
	
	$sql = "select * from field inner join profile2field on profile2field.fieldid=field.fieldid inner join def_org_field on def_org_field.fieldid=field.fieldid where field.tabid=".$tabid." and field.block=".$block ." and field.displaytype in (1,2) and profile2field.visible=0 and def_org_field.visible=0  and profile2field.profileid=".$profile_id." order by sequence";
	
	$result = $adb->query($sql);
	$noofrows = $adb->num_rows($result);
	$output='';
	for($i=0; $i<$noofrows; $i++)
	{
		$fieldtablename = $adb->query_result($result,$i,"tablename");	
		$fieldcolname = $adb->query_result($result,$i,"columnname");	
		$uitype = $adb->query_result($result,$i,"uitype");	
		$fieldname = $adb->query_result($result,$i,"fieldname");	
		$fieldlabel = $adb->query_result($result,$i,"fieldlabel");
		$maxlength = $adb->query_result($result,$i,"maximumlength");
		$generatedtype = $adb->query_result($result,$i,"generatedtype");
		$output .= '<tr>';
		$custfld = getDetailViewOutputHtml($uitype, $fieldname, $fieldlabel, $col_fields,$generatedtype);
		$output .= $custfld;
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

			$output .= '';
			$custfld = getDetailViewOutputHtml($uitype, $fieldname, $fieldlabel, $col_fields,$generatedtype);
			$output .= $custfld;	
		}
		$output .= '</tr>';

	}
	return $output;

}


?>
