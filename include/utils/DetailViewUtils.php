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

require_once('include/database/PearDatabase.php');
require_once('include/ComboUtil.php'); //new
require_once('include/utils/CommonUtils.php'); //new


/** This function returns the detail view form field and and its properties in array format.
  * Param $uitype - UI type of the field
  * Param $fieldname - Form field name
  * Param $fieldlabel - Form field label name
  * Param $col_fields - array contains the fieldname and values
  * Param $generatedtype - Field generated type (default is 1)
  * Param $tabid - tab id to which the Field belongs to (default is "")
  * Return type is an array
  */

function getDetailViewOutputHtml($uitype, $fieldname, $fieldlabel, $col_fields,$generatedtype,$tabid='')
{
	global $adb;
	global $mod_strings;
	global $app_strings;
	global $current_user;
	$fieldlabel = from_html($fieldlabel);
	$custfld = '';
	$value ='';
	$arr_data =Array();
	$label_fld = Array();
	$data_fld = Array();

	if($generatedtype == 2)
		$mod_strings[$fieldlabel] = $fieldlabel;

        if($col_fields[$fieldname]=='--None--')
                $col_fields[$fieldname]='';
	
	if($uitype == 13)
	{
		$label_fld[] = $mod_strings[$fieldlabel];
		$label_fld[] = $col_fields[$fieldname];
		//$label_fld[] = '<a href="mailto:'.$col_fields[$fieldname].'">'.$col_fields[$fieldname].'</a>';
	}
	elseif($uitype == 15 || $uitype == 16)
	{
	     $label_fld[] = $mod_strings[$fieldlabel];
	     $label_fld[] = $col_fields[$fieldname];
	     
		$pick_query="select * from ".$fieldname;
		$pickListResult = $adb->query($pick_query);
		$noofpickrows = $adb->num_rows($pickListResult);

		//Mikecrowe fix to correctly default for custom pick lists
		$options = array();
		$found = false;
		for($j = 0; $j < $noofpickrows; $j++)
		{
			$pickListValue=$adb->query_result($pickListResult,$j,strtolower($fieldname));

			if($col_fields[$fieldname] == $pickListValue)
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
		$label_fld ["options"] = $options;
	}
	elseif($uitype == 17)
	{
		$label_fld[] = $mod_strings[$fieldlabel];
		$label_fld[] = $col_fields[$fieldname];
		//$label_fld[] = '<a href="http://'.$col_fields[$fieldname].'" target="_blank">'.$col_fields[$fieldname].'</a>';
	}
	elseif($uitype == 19)
	{
		$col_fields[$fieldname]= make_clickable(nl2br($col_fields[$fieldname]));
		$label_fld[] = $mod_strings[$fieldlabel];
		$label_fld[] = $col_fields[$fieldname];
	}
	elseif($uitype == 20 || $uitype == 21 || $uitype == 22 || $uitype == 24) // Armando LC<scher 11.08.2005 -> B'descriptionSpan -> Desc: removed $uitype == 19 and made an aditional elseif above
	{
		$col_fields[$fieldname]=nl2br($col_fields[$fieldname]);
		$label_fld[] = $mod_strings[$fieldlabel];
		$label_fld[] = $col_fields[$fieldname];
	}
	elseif($uitype == 51 || $uitype == 50 || $uitype == 73)
	{
		$account_id = $col_fields[$fieldname];
		if($account_id != '')
		{
			$account_name = getAccountName($account_id);
		}
		//Account Name View	
		$label_fld[] = $mod_strings[$fieldlabel];
		$label_fld[] = $account_name;
		$label_fld["secid"] = $account_id;
		$label_fld["link"] = "index.php?module=Accounts&action=DetailView&record=".$account_id;
	}
	elseif($uitype == 52 || $uitype == 77)
	{
		$label_fld[] = $mod_strings[$fieldlabel];
		$user_id = $col_fields[$fieldname];
		$user_name = getUserName($user_id);
		if(is_admin($current_user))
		{
			$label_fld[] ='<a href="index.php?module=Users&action=DetailView&record='.$user_id.'">'.$user_name.'</a>';
		}
		else
		{
			$label_fld[] =$user_name;
		}
	}
	elseif($uitype == 53)
	{
		$user_id = $col_fields[$fieldname];
		if($user_id != 0)
		{
			$label_fld[] =$mod_strings[$fieldlabel].' '.$app_strings['LBL_USER'];
			$user_name = getUserName($user_id);
			if(is_admin($current_user))
			{
				$label_fld[] ='<a href="index.php?module=Users&action=DetailView&record='.$user_id.'">'.$user_name.'</a>';
			}
			else
			{
				$label_fld[] =$user_name;
			}
		}
		elseif($user_id == 0)
		{
			$label_fld[] =$mod_strings[$fieldlabel].' '.$app_strings['LBL_GROUP'];

			$id = $col_fields["record_id"];	
			$module = $col_fields["record_module"];
			$group_info = getGroupName($id, $module);
			$groupname = $group_info[0];
			$groupid = $group_info[1];
			if(is_admin($current_user))
                        {
				$label_fld[] ='<a href="index.php?module=Users&action=GroupDetailView&groupId='.$groupid.'">'.$groupname.'</a>';
			}
			else
			{
				$label_fld[] =$groupname;
			}			
		}
		
	}
	elseif($uitype == 55)
        {
		if($tabid == 4)
           {
                   $query="select contactdetails.imagename from contactdetails where contactid=".$col_fields['record_id'];
                   $result = $adb->query($query);
                   $imagename=$adb->query_result($result,0,'imagename');
                   if($imagename != '')
                   {
                           $imgpath = "test/contact/".$imagename;
                           $label_fld[] =$mod_strings[$fieldlabel];
                           $label_fld["cntimage"] ='<div style="position:absolute;height=100px"><img class="thumbnail" src="'.$imgpath.'" width="60" height="60" border="0"></div>&nbsp;'.$mod_strings[$fieldlabel];
                   }
                   else
                   {
                         $label_fld[] =$mod_strings[$fieldlabel];
                   }
                           
           }
           else
           {
                   $label_fld[] =$mod_strings[$fieldlabel];
           }
           $value = $col_fields[$fieldname];
           $sal_value = $col_fields["salutationtype"];
           if($sal_value == '--None--')
           {
                   $sal_value='';
           }
          $label_fld["salut"] = $sal_value;
          $label_fld[] = $value;
		//$label_fld[] =$sal_value.' '.$value;
        }
	elseif($uitype == 56)
	{
		$label_fld[] =$mod_strings[$fieldlabel];
		$value = $col_fields[$fieldname];
		if($value == 1)
		{
			$display_val = 'yes';
		}
		else
		{
			$display_val = '';
		}
		$label_fld[] = $display_val;
	}
	elseif($uitype == 57)
        {
		 $label_fld[] =$mod_strings[$fieldlabel];
           $contact_id = $col_fields[$fieldname];
           if($contact_id != '')
           {
                   $contact_name = getContactName($contact_id);
           }
          $label_fld[] = $contact_name;
		$label_fld["secid"] = $contact_id;
		$label_fld["link"] = "index.php?module=Contacts&action=DetailView&record=".$contact_id; 
        }
	elseif($uitype == 59)
	{
		$product_id = $col_fields[$fieldname];
		if($product_id != '')
		{
			$product_name = getProductName($product_id);
		}
		//Account Name View	
		$label_fld[] = $product_name;
		$label_fld["secid"] = $product_id;
		$label_fld["link"] = "index.php?module=Products&action=DetailView&record=".$product_id; 
		
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

		//This is added to strip the crmid and _ from the file name and show the original filename
		$org_filename = ltrim($col_fields[$fieldname],$col_fields['record_id'].'_');
                $custfldval = '<a href = "index.php?module=uploads&action=downloadfile&return_module='.$col_fields['record_module'].'&fileid='.$attachmentid.'&entityid='.$col_fields['record_id'].'">'.$org_filename.'</a>';

		$label_fld[] =$mod_strings[$fieldlabel];

		$label_fld[] =$custfldval;
        }
	elseif($uitype == 69)
	{
			
		$label_fld[] =$mod_strings[$fieldlabel];
		if($col_fields[$fieldname] != '')
		{
			if($tabid==14)
			{
				$images=array();
				$image_array=explode("###",$col_fields[$fieldname]);
				$image_array = array_slice($image_array,0,count($image_array)-1);
				if(count($image_array)>1)
				{
					if(count($image_array) < 4)
						$sides=count($image_array)*2;
					else
						$sides=8;
					$image_lists = '<div id="Carousel" style="position:relative;vertical-align: middle;">
						<img src="modules/Products/placeholder.gif" width="371" height="227" style="position:relative;">
						</div><script>var Car_NoOfSides='.$sides.'; Car_Image_Sources=new Array(';
								$imgpath = "test/product/";
								foreach($image_array as $image)
								{
								$images[]='"'.$imgpath.$image.'","'.$imgpath.$image.'"';
								}	
								$image_lists .=implode(',',$images).');</script>';	
					$label_fld[] =$image_lists;
				}else
				{
					$imgpath = "test/product/".$col_fields[$fieldname];
					$label_fld[] ='<img src="'.$imgpath.'" border="0" width="450" height="300">';
				}
			}	
            if($tabid==4)
            {
				$imgpath = "test/contact/".$col_fields[$fieldname];
				$label_fld[] ='<img src="'.$imgpath.'" border="0">';
			}
		}
		else
		{
			$label_fld[] ="";
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
				$label_fld[] =$app_strings['LBL_LEAD_NAME'];
				$sql = "select * from leaddetails where leadid=".$value;
				$result = $adb->query($sql);
				$first_name = $adb->query_result($result,0,"firstname");
				$last_name = $adb->query_result($result,0,"lastname");

				$label_fld[] ='<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$last_name.' '.$first_name.'</a>';
			}
			elseif($parent_module == "Accounts")
			{
				$label_fld[] = $app_strings['LBL_ACCOUNT_NAME'];
				$sql = "select * from  account where accountid=".$value;
				$result = $adb->query($sql);
				$account_name = $adb->query_result($result,0,"accountname");

				$label_fld[] ='<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$account_name.'</a>';
		}
			elseif($parent_module == "Potentials")
			{
				$label_fld[] =$app_strings['LBL_POTENTIAL_NAME'];
				$sql = "select * from  potential where potentialid=".$value;
				$result = $adb->query($sql);
				$potentialname = $adb->query_result($result,0,"potentialname");

				$label_fld[] ='<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$potentialname.'</a>';
			}
			elseif($parent_module == "Products")
			{
				$label_fld[] =$app_strings['LBL_PRODUCT_NAME'];
				$sql = "select * from  products where productid=".$value;
				$result = $adb->query($sql);
				$productname= $adb->query_result($result,0,"productname");

				$label_fld[] ='<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$productname.'</a>';
			}
			elseif($parent_module == "PurchaseOrder")
			{
				$label_fld[] =$app_strings['LBL_PORDER_NAME'];
				$sql = "select * from  purchaseorder where purchaseorderid=".$value;
				$result = $adb->query($sql);
				$pordername= $adb->query_result($result,0,"subject");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$pordername.'</a>';
			}
			elseif($parent_module == "SalesOrder")
			{
				$label_fld[] = $app_strings['LBL_SORDER_NAME'];
				$sql = "select * from  salesorder where salesorderid=".$value;
				$result = $adb->query($sql);
				$sordername= $adb->query_result($result,0,"subject");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$sordername.'</a>';
			}
			elseif($parent_module == "Invoice")
			{
				$label_fld[] = $app_strings['LBL_INVOICE_NAME'];
				$sql = "select * from  invoice where invoiceid=".$value;
				$result = $adb->query($sql);
				$invoicename= $adb->query_result($result,0,"subject");

				$label_fld[] ='<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$invoicename.'</a>';
			}
		}
		else
		{
			$label_fld[] = $mod_strings[$fieldlabel];
			$label_fld[] = $value;
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
				$label_fld[] =$app_strings['LBL_LEAD_NAME'];
				$sql = "select * from leaddetails where leadid=".$value;
				$result = $adb->query($sql);
				$first_name = $adb->query_result($result,0,"firstname");
				$last_name = $adb->query_result($result,0,"lastname");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$last_name.' '.$first_name.'</a>';
			}
			elseif($parent_module == "Accounts")
			{
				$label_fld[] = $app_strings['LBL_ACCOUNT_NAME'];
				$sql = "select * from  account where accountid=".$value;
				$result = $adb->query($sql);
				$account_name = $adb->query_result($result,0,"accountname");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$account_name.'</a>';
			}
			elseif($parent_module == "Potentials")
			{
				$label_fld[] =$app_strings['LBL_POTENTIAL_NAME'];
				$sql = "select * from  potential where potentialid=".$value;
				$result = $adb->query($sql);
				$potentialname = $adb->query_result($result,0,"potentialname");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$potentialname.'</a>';
			}
			elseif($parent_module == "Quotes")
                        {
				$label_fld[] =$app_strings['LBL_QUOTE_NAME'];
                                $sql = "select * from  quotes where quoteid=".$value;
                                $result = $adb->query($sql);
                                $quotename = $adb->query_result($result,0,"subject");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$quotename.'</a>';
                        }
			elseif($parent_module == "PurchaseOrder")
                        {
				$label_fld[] = $app_strings['LBL_PORDER_NAME'];
                                $sql = "select * from  purchaseorder where purchaseorderid=".$value;
                                $result = $adb->query($sql);
                                $pordername = $adb->query_result($result,0,"subject");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$pordername.'</a>';
                        }
                        elseif($parent_module == "SalesOrder")
                        {
				$label_fld[] = $app_strings['LBL_SORDER_NAME'];
                                $sql = "select * from  salesorder where salesorderid=".$value;
                                $result = $adb->query($sql);
                                $sordername = $adb->query_result($result,0,"subject");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$sordername.'</a>';
                        }
			elseif($parent_module == "Invoice")
                        {
				$label_fld[] = $app_strings['LBL_INVOICE_NAME'];
                                $sql = "select * from  invoice where invoiceid=".$value;
                                $result = $adb->query($sql);
                                $invoicename = $adb->query_result($result,0,"subject");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$invoicename.'</a>';
                        }

		}
		else
		{
			$label_fld[] = $mod_strings[$fieldlabel];
			$label_fld[] = $value;
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
				$label_fld[] = $app_strings['LBL_LEAD_NAME'];
				$sql = "select * from leaddetails where leadid=".$value;
				$result = $adb->query($sql);
				$first_name = $adb->query_result($result,0,"firstname");
				$last_name = $adb->query_result($result,0,"lastname");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$last_name.' '.$first_name.'</a>';
			}
			elseif($parent_module == "Contacts")
			{
				$label_fld[] = $app_strings['LBL_CONTACT_NAME'];
				$sql = "select * from  contactdetails where contactid=".$value;
				$result = $adb->query($sql);
				$first_name = $adb->query_result($result,0,"firstname");
                                $last_name = $adb->query_result($result,0,"lastname");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$last_name.' '.$first_name.'</a>';
			}
		}
		else
		{
			$label_fld[] = $mod_strings[$fieldlabel];
			$label_fld[] = $value;
			
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
				$label_fld[] = $app_strings['LBL_RELATED_TO'];
				$label_fld[] =$app_strings['LBL_MULTIPLE'];
			}
			else
			{
				$parent_module = getSalesEntityType($value);
				if($parent_module == "Leads")
				{
					$label_fld[] = $app_strings['LBL_LEAD_NAME'];
					$sql = "select * from leaddetails where leadid=".$value;
					$result = $adb->query($sql);
					$first_name = $adb->query_result($result,0,"firstname");
					$last_name = $adb->query_result($result,0,"lastname");
					$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$last_name.' '.$first_name.'</a>';
				}
				elseif($parent_module == "Contacts")
				{
					$label_fld[] = $app_strings['LBL_CONTACT_NAME'];
					$sql = "select * from  contactdetails where contactid=".$value;
					$result = $adb->query($sql);
					$first_name = $adb->query_result($result,0,"firstname");
					$last_name = $adb->query_result($result,0,"lastname");
					$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$last_name.' '.$first_name.'</a>';
				}
				elseif($parent_module == "Accounts")
				{
					$label_fld[] = $app_strings['LBL_ACCOUNT_NAME'];
					$sql = "select * from  account where accountid=".$value;
					$result = $adb->query($sql);
					$accountname = $adb->query_result($result,0,"accountname");
					$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$accountname.'</a>';
				}

			}
		}
		else
		{
			$label_fld[] = $mod_strings[$fieldlabel];
			$label_fld[] = $value;
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
				$label_fld[] = $app_strings['LBL_CONTACT_NAME'];
				$sql = "select * from  contactdetails where contactid=".$value;
				$result = $adb->query($sql);
				$first_name = $adb->query_result($result,0,"firstname");
                                $last_name = $adb->query_result($result,0,"lastname");

				$label_fld[] ='<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$last_name.' '.$first_name.'</a>';
			}
			elseif($parent_module == "Accounts")
			{
				$label_fld[] = $app_strings['LBL_ACCOUNT_NAME'];
				$sql = "select * from account where accountid=".$value;
				$result = $adb->query($sql);
				$account_name = $adb->query_result($result,0,"accountname");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$account_name.'</a>';
			}

		}
		else
		{
			$label_fld[] = $mod_strings[$fieldlabel];
			$label_fld[] = $value;
		}
	}

	elseif($uitype==63)
        {
	   $label_fld[] =$mod_strings[$fieldlabel];
	   $label_fld[] = $col_fields[$fieldname].'h&nbsp; '.$col_fields['duration_minutes'].'m';
        }
	elseif($uitype == 6)
        {
		$label_fld[] =$mod_strings[$fieldlabel];
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
	
		$label_fld[] = $displ_date.'&nbsp;'.$start_time;
	}
	elseif($uitype == 5 || $uitype == 23 || $uitype == 70)
	{
		$label_fld[] =$mod_strings[$fieldlabel];
		$cur_date_val = $col_fields[$fieldname];
		if($cur_date_val == '0000-00-00')
		{
			$display_val = '';	
		}
		else
		{
			$display_val = getDisplayDate($cur_date_val);
		}
		$label_fld[] = $display_val;
	}
	elseif($uitype == 71 || $uitype == 72)
	{
		$label_fld[] =$mod_strings[$fieldlabel];
		$display_val = '';
		if($col_fields[$fieldname] != '' && $col_fields[$fieldname] != 0)
		{
		    $currencyid=fetchCurrency($current_user->id);
 	            $curr_symbol=getCurrencySymbol($currencyid);
              	    $rate = getConversionRate($currencyid,$curr_symbol);
	 	    $amount_user_specific=convertFromDollar($col_fields[$fieldname],$rate);
                    $display_val = $amount_user_specific;	
		}
		$label_fld["cursymb"] = $curr_symbol;
          	$label_fld[] = $display_val;
	}
	elseif($uitype == 75 || $uitype == 81)
        {
		 $label_fld[] =$mod_strings[$fieldlabel];
           	$vendor_id = $col_fields[$fieldname];
           	if($vendor_id != '')
           	{
                   $vendor_name = getVendorName($vendor_id);
           	}
          	$label_fld[] = $vendor_name;
		$label_fld["secid"] = $vendor_id;
		$label_fld["link"] = "index.php?module=Products&action=VendorDetailView&record=".$vendor_id; 
		//$label_fld[] = '<a href="index.php?module=Products&action=VendorDetailView&record='.$vendor_id.'">'.$vendor_name.'</a>';
        }
	elseif($uitype == 76)
        {
		 $label_fld[] =$mod_strings[$fieldlabel];
           $potential_id = $col_fields[$fieldname];
           if($potential_id != '')
           {
                   $potential_name = getPotentialName($potential_id);
           }
          $label_fld[] = $potential_name;
		$label_fld["secid"] = $potential_id;
		$label_fld["link"] = "index.php?module=Potentials&action=DetailView&record=".$potential_id; 
        }
	elseif($uitype == 78)
        {
		 $label_fld[] =$mod_strings[$fieldlabel];
           $quote_id = $col_fields[$fieldname];
           if($quote_id != '')
           {
                   $quote_name = getQuoteName($quote_id);
           }
          $label_fld[] = $quote_name;
		$label_fld["secid"] = $quote_id;
		$label_fld["link"] = "index.php?module=Quotes&action=DetailView&record=".$quote_id; 
        }
	elseif($uitype == 79)
        {
 		 $label_fld[] =$mod_strings[$fieldlabel];
           $purchaseorder_id = $col_fields[$fieldname];
           if($purchaseorder_id != '')
           {
                   $purchaseorder_name = getPoName($purchaseorder_id);
           }
           $label_fld[] = $purchaseorder_name;
		 $label_fld["secid"] = $purchaseorder_id;
		 $label_fld["link"] = "index.php?module=PurchaseOrder&action=DetailView&record=".$purchaseorder_id; 
        }
	elseif($uitype == 80)
        {
		 $label_fld[] =$mod_strings[$fieldlabel];
           $salesorder_id = $col_fields[$fieldname];
           if($salesorder_id != '')
           {
                   $salesorder_name = getSoName($salesorder_id);
           }
          $label_fld[] = $salesorder_name;
		$label_fld["secid"] = $salesorder_id;
		$label_fld["link"] = "index.php?module=SalesOrder&action=DetailView&record=".$salesorder_id; 
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
                 
		$label_fld[] =$mod_strings[$fieldlabel];
		if($col_fields[$fieldname])
                {
                        $reminder_str= $rem_days.'&nbsp;'.$mod_strings['LBL_DAYS'].'&nbsp;'.$rem_hrs.'&nbsp;'.$mod_strings['LBL_HOURS'].'&nbsp;'.$rem_min.'&nbsp;'.$mod_strings['LBL_MINUTES'].'&nbsp;&nbsp;'.$mod_strings['LBL_BEFORE_EVENT'];
                }
		$label_fld[] = '&nbsp;'.$reminder_str;
	}
	else
	{
	
	 $label_fld[] =$mod_strings[$fieldlabel];
          if($col_fields[$fieldname]=='0')
                $col_fields[$fieldname]='';
	
		$label_fld[] = $col_fields[$fieldname];
	}
	$label_fld[]=$uitype;
	return $label_fld;
}

/** This function returns a HTML output of associated products for a given entity (Quotes,Invoice,Sales order or Purchase order)
  * Param $module - module name
  * Param $focus - module object
  * Return type string
  */

function getDetailAssociatedProducts($module,$focus)
{
	global $adb;
	global $theme;
	global $log;
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	$log->debug("in getDetailAssociatedProducts. Module is  ".$module);

	$output = '';
	$output .= '<table class="prdTab" border="0" cellspacing="0" cellpadding="0" id="proTab">';
	$output .=  '<tr><th width="20%">Product</th>';

	if($module == 'Quotes' || $module == 'SalesOrder' || $module == 'Invoice')
	{
		$output .= '<th width="12%">Qty In Stock</th>';
	}

	$output .= '<th width="10%">Qty</th>';
	$output .= '<th width="10%">Unit Price </th>';
	$output .= '<th width="19%">List Price</th>';
	$output .= '<th width="10%">Total</th>';
	$output .= '</tr>';

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
			$row_class = "dvtCellLable";
		}
		else
		{
			$row_class = "dvtCellInfo";
		}

		$output .= '<tr class="'.$row_class.'">';
		$output .= '<td nowrap>'.$productname.'</td>';
		if($module != 'PurchaseOrder')
		{	
			$output .= '<td>'.$qtyinstock.'</td>';
		}
		$output .= '<td style="padding:3px;">'.$qty.'</td>';
		$output .= '<td style="padding:3px;">'.$unitprice.'</td>';
		$output .= '<td style="padding:3px;">'.$listprice.'</td>';
		$output .= '<td style="padding:3px;"><div id="total'.$i.'" align="right">'.$total.'</div></td>';
		$output .= '</tr>';


	}
	$output .= '<tr id="tableheadline">';
	$output .= '<td colspan="14" height="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td></tr>';
	$output .= '</table>';
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

	return $output;

}

/** This function returns the related tab details for a given entity or a module.
* Param $module - module name
* Param $focus - module object
* Return type is an array
*/
		
function getRelatedLists($module,$focus)
{
	global $adb;
	global $current_user;
	require('user_privileges/user_privileges_'.$current_user->id.'.php');
	
	$cur_tab_id = getTabid($module);

	$sql1 = "select * from relatedlists where tabid=".$cur_tab_id;
	$result = $adb->query($sql1);
	$num_row = $adb->num_rows($result);
	for($i=0; $i<$num_row; $i++)
	{
		$rel_tab_id = $adb->query_result($result,$i,"related_tabid");
		$function_name = $adb->query_result($result,$i,"name");
		$label = $adb->query_result($result,$i,"label");
		if($rel_tab_id != 0)
		{

			if($profileTabsPermission[$rel_tab_id] == 0)
			{
		        	if($profileActionPermission[$rel_tab_id][3] == 0)
        			{
		                	$focus_list[$label] = $focus->$function_name($focus->id);
        			}
			}
		}
		else
		{
			$focus_list[$label] = $focus->$function_name($focus->id);
		}
	}
	return $focus_list;
}

/** This function returns the detailed block information of a record in a module.
* Param $module - module name
* Param $block - block id
* Param $col_fields - column fields array for the module 
* Param $tabid - tab id
* Return type is an array
*/

function getDetailBlockInformation($module, $block,$col_fields,$tabid)
{
	//retreive the tabid	
	global $adb;
	#$tabid = getTabid($module);
	global $current_user;
	$label_data = Array();

	//retreive the profileList from database
	require('user_privileges/user_privileges_'.$current_user->id.'.php');
	//Checking for field level security
	if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] ==0)
	{	
				
		$sql = "select field.* from field where field.tabid=".$tabid." and field.block=".$block ." and field.displaytype in (1,2) order by sequence";
	}
	else
	{
		$profileList = getCurrentUserProfileList();
		$sql = "select field.* from field inner join profile2field on profile2field.fieldid=field.fieldid inner join def_org_field on def_org_field.fieldid=field.fieldid where field.tabid=".$tabid." and field.block=".$block ." and field.displaytype in (1,2) and profile2field.visible=0 and def_org_field.visible=0  and profile2field.profileid in ".$profileList." group by field.fieldid order by sequence";
	}
	
	$result = $adb->query($sql);
	$noofrows = $adb->num_rows($result);
	for($i=0; $i<$noofrows; $i++)
	{
		$fieldtablename = $adb->query_result($result,$i,"tablename");	
		$fieldcolname = $adb->query_result($result,$i,"columnname");	
		$uitype = $adb->query_result($result,$i,"uitype");	
		$fieldname = $adb->query_result($result,$i,"fieldname");	
		$fieldlabel = $adb->query_result($result,$i,"fieldlabel");
		$maxlength = $adb->query_result($result,$i,"maximumlength");
		$generatedtype = $adb->query_result($result,$i,"generatedtype");
		$tabid = $adb->query_result($result,$i,"tabid");
		$output .= '<tr>';
		$custfld = getDetailViewOutputHtml($uitype, $fieldname, $fieldlabel, $col_fields,$generatedtype,$tabid);
		$label_data[] = array($custfld[0]=>array("value"=>$custfld[1],"ui"=>$custfld[2],"options"=>$custfld["options"],"secid"=>$custfld["secid"],"link"=>$custfld["link"],"cursymb"=>$custfld["cursymb"],"salut"=>$custfld["salut"],"cntimage"=>$custfld["cntimage"],"tablename"=>$fieldtablename,"fldname"=>$fieldname));
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
			$tabid = $adb->query_result($result,$i,"tabid");

			$custfld = getDetailViewOutputHtml($uitype, $fieldname, $fieldlabel, $col_fields,$generatedtype,$tabid);
			$label_data[] = array($custfld[0]=>array("value"=>$custfld[1],"ui"=>$custfld[2],"options"=>$custfld["options"],"secid"=>$custfld["secid"],"link"=>$custfld["link"],"cursymb"=>$custfld["cursymb"],"salut"=>$custfld["salut"],"cntimage"=>$custfld["cntimage"],"tablename"=>$fieldtablename,"fldname"=>$fieldname));
		}

	}
	for ($i=0,$j=0;$i<count($label_data);$i=$i+2,$j++)
	{
		$keys=array_keys($label_data[$i]);
                $key1=$keys[0];	
		if(is_array($label_data[$i+1]))
		{
                	$keys=array_keys($label_data[$i+1]);
                	$key2=$keys[0];
		}
		$return_data[$j]=array($key1 => $label_data[$i][$key1],$key2 => $label_data[$i+1][$key2]);
	}
	return $return_data;


}


?>
