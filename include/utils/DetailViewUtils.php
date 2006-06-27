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


/** This function returns the detail view form vtiger_field and and its properties in array format.
  * Param $uitype - UI type of the vtiger_field
  * Param $fieldname - Form vtiger_field name
  * Param $fieldlabel - Form vtiger_field label name
  * Param $col_fields - array contains the vtiger_fieldname and values
  * Param $generatedtype - Field generated type (default is 1)
  * Param $tabid - vtiger_tab id to which the Field belongs to (default is "")
  * Return type is an array
  */

function getDetailViewOutputHtml($uitype, $fieldname, $fieldlabel, $col_fields,$generatedtype,$tabid='')
{
	global $log;
	$log->debug("Entering getDetailViewOutputHtml(".$uitype.",". $fieldname.",". $fieldlabel.",". $col_fields.",".$generatedtype.",".$tabid.") method ...");
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
	if($uitype == 116)
	{
		$label_fld[] = $mod_strings[$fieldlabel];
                $label_fld[] = getCurrencyName($col_fields[$fieldname]);
		$pick_query="select * from vtiger_currency_info";
		$pickListResult = $adb->query($pick_query);
		$noofpickrows = $adb->num_rows($pickListResult);

		//Mikecrowe fix to correctly default for custom pick lists
		$options = array();
		$found = false;
		for($j = 0; $j < $noofpickrows; $j++)
		{
			$pickListValue=$adb->query_result($pickListResult,$j,'currency_name');
			$currency_id=$adb->query_result($pickListResult,$j,'id');
			if($col_fields[$fieldname] == $pickListValue)
			{
				$chk_val = "selected";	
				$found = true;
			}
			else
			{	
				$chk_val = '';
			}
			$options[$currency_id] = array($pickListValue=>$chk_val );	
		}
		$label_fld ["options"] = $options;	
	}	
	elseif($uitype == 13)
	{
		$label_fld[] = $mod_strings[$fieldlabel];
		$label_fld[] = $col_fields[$fieldname];
	}
	elseif($uitype == 15 || $uitype == 16 || $uitype == 115)
	{
	     $label_fld[] = $mod_strings[$fieldlabel];
	     $label_fld[] = $col_fields[$fieldname];
	     
		$pick_query="select * from vtiger_".$fieldname;
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
	elseif($uitype == 52 || $uitype == 77  || $uitype == 101)
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
		$user_name = getUserName($user_id);
		$id = $col_fields["record_id"];	
		$module = $col_fields["record_module"];
		$group_info = getGroupName($id, $module);
		$groupname = $group_info[0];
		$groupid = $group_info[1];
		if($user_id != 0)
		{	
			$label_fld[] =$mod_strings[$fieldlabel].' '.$app_strings['LBL_USER'];
			$label_fld[] =$user_name;
			$label_fld ["options"][] = 'User';
		}else
		{
			
			$label_fld[] =$mod_strings[$fieldlabel].' '.$app_strings['LBL_GROUP'];
			$label_fld[] =$groupname;
			$label_fld ["options"][] = 'Group';
		}
		if(is_admin($current_user))
		{
			$label_fld["secid"][] = $user_id;
			$label_fld["link"][] = "index.php?module=Users&action=DetailView&record=".$user_id;
			$label_fld["secid"][] = $groupid;
			$label_fld["link"][] = "index.php?module=Users&action=GroupDetailView&groupId=".$groupid;
		}
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
		$value = $user_id;
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
			do{
				$groupname=$nameArray["groupname"];
				$group_id=$nameArray["groupid"];
				$selected = '';	
				if($groupname == $selected_groupname[0])
				{
					$selected = "selected";
				}	
				if($groupname != '')
					$group_option[$group_id] = array($groupname=>$selected);
			}while($nameArray = $adb->fetch_array($result));
			
			$label_fld ["options"][] = $users_combo;
			if(count($group_option) >0)
				$label_fld ["options"][] = $group_option; 
	}
	elseif($uitype == 55)
        {
		if($tabid == 4)
           {
                   $query="select vtiger_contactdetails.imagename from vtiger_contactdetails where contactid=".$col_fields['record_id'];
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
	elseif($uitype == 58)
	{
		$label_fld[] =$mod_strings[$fieldlabel];
		$campaign_id = $col_fields[$fieldname];
		if($campaign_id != '')
		{
			$campaign_name = getCampaignName($campaign_id);
		}
		$label_fld[] = $campaign_name;
		$label_fld["secid"] = $campaign_id;
		$label_fld["link"] = "index.php?module=Campaigns&action=DetailView&record=".$campaign_id;

	}
	elseif($uitype == 59)
	{
		$label_fld[] =$mod_strings[$fieldlabel];
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
			$label_fld[] =$mod_strings[$fieldlabel];

			if($tabid ==10)
			{
				$attach_result = $adb->query("select * from vtiger_seattachmentsrel where crmid = ".$col_fields['record_id']);
				for($ii=0;$ii < $adb->num_rows($attach_result);$ii++)
				{
					$attachmentid = $adb->query_result($attach_result,$ii,'attachmentsid');
					if($attachmentid != '')
					{
						$attachquery = "select * from vtiger_attachments where attachmentsid=".$attachmentid;
						$attachmentsname = $adb->query_result($adb->query($attachquery),0,'name');
						if($attachmentsname != '')	
							$custfldval = '<a href = "index.php?module=uploads&action=downloadfile&return_module='.$col_fields['record_module'].'&fileid='.$attachmentid.'&entityid='.$col_fields['record_id'].'">'.$attachmentsname.'</a>';
						else
							$custfldval = '';
					}
					$label_fld['options'][] = $custfldval;
				}
			}else
			{
				$attachmentid=$adb->query_result($adb->query("select * from vtiger_seattachmentsrel where crmid = ".$col_fields['record_id']),0,'attachmentsid');
				if($col_fields[$fieldname] == '' && $attachmentid != '')
				{
					$attachquery = "select * from vtiger_attachments where attachmentsid=".$attachmentid;
					$col_fields[$fieldname] = $adb->query_result($adb->query($attachquery),0,'name');
				}

				//This is added to strip the crmid and _ from the file name and show the original filename
				$org_filename = ltrim($col_fields[$fieldname],$col_fields['record_id'].'_');
				if($org_filename != '')
					$custfldval = '<a href = "index.php?module=uploads&action=downloadfile&return_module='.$col_fields['record_module'].'&fileid='.$attachmentid.'&entityid='.$col_fields['record_id'].'">'.$org_filename.'</a>';
				else
					$custfldval = '';
			}
			$label_fld[] =$custfldval;
		}
	elseif($uitype == 69)
	{
		$label_fld[] =$mod_strings[$fieldlabel];
		if($tabid==14)
		{
			$images=array();
			$query = 'select productname, vtiger_attachments.path, vtiger_attachments.attachmentsid, vtiger_attachments.name from vtiger_products left join vtiger_seattachmentsrel on vtiger_seattachmentsrel.crmid=vtiger_products.productid inner join vtiger_attachments on vtiger_attachments.attachmentsid=vtiger_seattachmentsrel.attachmentsid where productid='.$col_fields['record_id'];
			$result_image = $adb->query($query);
			for($image_iter=0;$image_iter < $adb->num_rows($result_image);$image_iter++)	
			{
				$image_id_array[] = $adb->query_result($result_image,$image_iter,'attachmentsid');	
				$image_array[] = $adb->query_result($result_image,$image_iter,'name');	
				$imagepath_array[] = $adb->query_result($result_image,$image_iter,'path');	
			}
			if(count($image_array)>1)
			{
				if(count($image_array) < 4)
					$sides=count($image_array)*2;
				else
					$sides=8;

				$image_lists = '<div id="Carousel" style="position:relative;vertical-align: middle;">
					<img src="modules/Products/placeholder.gif" width="371" height="227" style="position:relative;">
					</div><script>var Car_NoOfSides='.$sides.'; Car_Image_Sources=new Array(';

				for($image_iter=0;$image_iter < count($image_array);$image_iter++)
				{
					$images[]='"'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'","'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'"';
				}	
				$image_lists .=implode(',',$images).');</script>';
				$label_fld[] =$image_lists;
			}elseif(count($image_array)==1)
			{
				$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="450" height="300">';
			}else
			{
				$label_fld[] ='';
			}
			
		}	
		if($tabid==4)
		{
			//$imgpath = getModuleFileStoragePath('Contacts').$col_fields[$fieldname];
			$sql = "select vtiger_attachments.* from vtiger_attachments inner join vtiger_seattachmentsrel on vtiger_seattachmentsrel.attachmentsid = vtiger_attachments.attachmentsid where vtiger_seattachmentsrel.crmid=".$col_fields['record_id'];
			$image_res = $adb->query($sql);
			$image_id = $adb->query_result($image_res,0,'attachmentsid');
			$image_path = $adb->query_result($image_res,0,'path');
			$image_name = $adb->query_result($image_res,0,'name');
			$imgpath = $image_path.$image_id."_".$image_name;
			$label_fld[] ='<img src="'.$imgpath.'" class="reflect" width="450" height="300" alt="">';
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
				$sql = "select * from vtiger_leaddetails where leadid=".$value;
				$result = $adb->query($sql);
				$first_name = $adb->query_result($result,0,"firstname");
				$last_name = $adb->query_result($result,0,"lastname");

				$label_fld[] ='<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$last_name.' '.$first_name.'</a>';
			}
			elseif($parent_module == "Accounts")
			{
				$label_fld[] = $app_strings['LBL_ACCOUNT_NAME'];
				$sql = "select * from  vtiger_account where accountid=".$value;
				$result = $adb->query($sql);
				$account_name = $adb->query_result($result,0,"accountname");

				$label_fld[] ='<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$account_name.'</a>';
		}
			elseif($parent_module == "Potentials")
			{
				$label_fld[] =$app_strings['LBL_POTENTIAL_NAME'];
				$sql = "select * from  vtiger_potential where potentialid=".$value;
				$result = $adb->query($sql);
				$potentialname = $adb->query_result($result,0,"potentialname");

				$label_fld[] ='<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$potentialname.'</a>';
			}
			elseif($parent_module == "Products")
			{
				$label_fld[] =$app_strings['LBL_PRODUCT_NAME'];
				$sql = "select * from  vtiger_products where productid=".$value;
				$result = $adb->query($sql);
				$productname= $adb->query_result($result,0,"productname");

				$label_fld[] ='<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$productname.'</a>';
			}
			elseif($parent_module == "PurchaseOrder")
			{
				$label_fld[] =$app_strings['LBL_PORDER_NAME'];
				$sql = "select * from  vtiger_purchaseorder where purchaseorderid=".$value;
				$result = $adb->query($sql);
				$pordername= $adb->query_result($result,0,"subject");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$pordername.'</a>';
			}
			elseif($parent_module == "SalesOrder")
			{
				$label_fld[] = $app_strings['LBL_SORDER_NAME'];
				$sql = "select * from  vtiger_salesorder where salesorderid=".$value;
				$result = $adb->query($sql);
				$sordername= $adb->query_result($result,0,"subject");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$sordername.'</a>';
			}
			elseif($parent_module == "Invoice")
			{
				$label_fld[] = $app_strings['LBL_INVOICE_NAME'];
				$sql = "select * from  vtiger_invoice where invoiceid=".$value;
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
				$sql = "select * from vtiger_leaddetails where leadid=".$value;
				$result = $adb->query($sql);
				$first_name = $adb->query_result($result,0,"firstname");
				$last_name = $adb->query_result($result,0,"lastname");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$last_name.' '.$first_name.'</a>';
			}
			elseif($parent_module == "Accounts")
			{
				$label_fld[] = $app_strings['LBL_ACCOUNT_NAME'];
				$sql = "select * from  vtiger_account where accountid=".$value;
				$result = $adb->query($sql);
				$account_name = $adb->query_result($result,0,"accountname");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$account_name.'</a>';
			}
			elseif($parent_module == "Potentials")
			{
				$label_fld[] =$app_strings['LBL_POTENTIAL_NAME'];
				$sql = "select * from  vtiger_potential where potentialid=".$value;
				$result = $adb->query($sql);
				$potentialname = $adb->query_result($result,0,"potentialname");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$potentialname.'</a>';
			}
			elseif($parent_module == "Quotes")
                        {
				$label_fld[] =$app_strings['LBL_QUOTE_NAME'];
                                $sql = "select * from  vtiger_quotes where quoteid=".$value;
                                $result = $adb->query($sql);
                                $quotename = $adb->query_result($result,0,"subject");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$quotename.'</a>';
                        }
			elseif($parent_module == "PurchaseOrder")
                        {
				$label_fld[] = $app_strings['LBL_PORDER_NAME'];
                                $sql = "select * from  vtiger_purchaseorder where purchaseorderid=".$value;
                                $result = $adb->query($sql);
                                $pordername = $adb->query_result($result,0,"subject");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$pordername.'</a>';
                        }
                        elseif($parent_module == "SalesOrder")
                        {
				$label_fld[] = $app_strings['LBL_SORDER_NAME'];
                                $sql = "select * from  vtiger_salesorder where salesorderid=".$value;
                                $result = $adb->query($sql);
                                $sordername = $adb->query_result($result,0,"subject");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$sordername.'</a>';
                        }
			elseif($parent_module == "Invoice")
                        {
				$label_fld[] = $app_strings['LBL_INVOICE_NAME'];
                                $sql = "select * from  vtiger_invoice where invoiceid=".$value;
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
				$sql = "select * from vtiger_leaddetails where leadid=".$value;
				$result = $adb->query($sql);
				$first_name = $adb->query_result($result,0,"firstname");
				$last_name = $adb->query_result($result,0,"lastname");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$last_name.' '.$first_name.'</a>';
			}
			elseif($parent_module == "Contacts")
			{
				$label_fld[] = $app_strings['LBL_CONTACT_NAME'];
				$sql = "select * from  vtiger_contactdetails where contactid=".$value;
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
			$mysql = "select crmid from vtiger_seactivityrel where activityid=".$myemailid;
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
					$sql = "select * from vtiger_leaddetails where leadid=".$value;
					$result = $adb->query($sql);
					$first_name = $adb->query_result($result,0,"firstname");
					$last_name = $adb->query_result($result,0,"lastname");
					$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$last_name.' '.$first_name.'</a>';
				}
				elseif($parent_module == "Contacts")
				{
					$label_fld[] = $app_strings['LBL_CONTACT_NAME'];
					$sql = "select * from  vtiger_contactdetails where contactid=".$value;
					$result = $adb->query($sql);
					$first_name = $adb->query_result($result,0,"firstname");
					$last_name = $adb->query_result($result,0,"lastname");
					$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$last_name.' '.$first_name.'</a>';
				}
				elseif($parent_module == "Accounts")
				{
					$label_fld[] = $app_strings['LBL_ACCOUNT_NAME'];
					$sql = "select * from  vtiger_account where accountid=".$value;
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
				$sql = "select * from  vtiger_contactdetails where contactid=".$value;
				$result = $adb->query($sql);
				$first_name = $adb->query_result($result,0,"firstname");
                                $last_name = $adb->query_result($result,0,"lastname");

				$label_fld[] ='<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$last_name.' '.$first_name.'</a>';
			}
			elseif($parent_module == "Accounts")
			{
				$label_fld[] = $app_strings['LBL_ACCOUNT_NAME'];
				$sql = "select * from vtiger_account where accountid=".$value;
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
		$currencyid=fetchCurrency($current_user->id);
		$rate_symbol = getCurrencySymbolandCRate($currencyid);
		$rate = $rate_symbol['rate'];
		$curr_symbol = $rate_symbol['symbol'];
	        if($col_fields[$fieldname] != '' && $col_fields[$fieldname] != 0)
		{
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
		$label_fld["link"] = "index.php?module=Vendors&action=DetailView&record=".$vendor_id; 
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
	}elseif($uitype == 98)
	{
	 	$label_fld[] =$mod_strings[$fieldlabel];
		if(is_admin($current_user))
			$label_fld[] = '<a href="index.php?module=Users&action=RoleDetailView&roleid='.$col_fields[$fieldname].'">'.getRoleName($col_fields[$fieldname]).'</a>';
		else
			$label_fld[] = getRoleName($col_fields[$fieldname]);
	}
	else
	{
	
	 $label_fld[] =$mod_strings[$fieldlabel];
          if($col_fields[$fieldname]=='0')
                $col_fields[$fieldname]='';
	
		$label_fld[] = $col_fields[$fieldname];
	}
	$label_fld[]=$uitype;
	
	//sets whether the currenct user is admin or not
	if(is_admin($current_user))
	{
	    $label_fld["isadmin"] = 1;
	}else
	{
	   $label_fld["isadmin"] = 0;
  }
  
	$log->debug("Exiting getDetailViewOutputHtml method ...");
	return $label_fld;
}

/** This function returns a HTML output of associated vtiger_products for a given entity (Quotes,Invoice,Sales order or Purchase order)
  * Param $module - module name
  * Param $focus - module object
  * Return type string
  */

function getDetailAssociatedProducts($module,$focus)
{
	global $log;
	$log->debug("Entering getDetailAssociatedProducts(".$module.",".$focus.") method ...");
	global $adb;
	global $theme;
	global $log;
	global $app_strings;
	
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	$log->debug("in getDetailAssociatedProducts. Module is  ".$module);

	$output = '';
	$output .= '<table class="prdTab" border="0" cellspacing="0" cellpadding="2" id="proTab">';
	$output .= '<tr><td colspan="6" class="detailedViewHeader"><b>'.$app_strings['LBL_PRODUCT_DETAILS'].'</b></td></tr>';
	$output .=  '<tr><th width="20%">'.$app_strings['LBL_PRODUCT'].'</th>';

	if($module == 'Quotes' || $module == 'SalesOrder' || $module == 'Invoice')
	{
		$output .= '<th width="12%">'.$app_strings['LBL_QTY_IN_STOCK'].'</th>';
	}

	$output .= '<th width="10%">'.$app_strings['LBL_QTY'].'</th>';
	$output .= '<th width="10%">'.$app_strings['LBL_UNIT_PRICE'].'</th>';
	$output .= '<th width="19%">'.$app_strings['LBL_LIST_PRICE'].'</th>';
	$output .= '<th width="10%">'.$app_strings['LBL_TOTAL'].'</th>';
	$output .= '</tr>';

	if($module == 'Quotes')
	{
		$query="select vtiger_products.productname,vtiger_products.unit_price,vtiger_products.qtyinstock,vtiger_quotesproductrel.* from vtiger_quotesproductrel inner join vtiger_products on vtiger_products.productid=vtiger_quotesproductrel.productid where quoteid=".$focus->id;
	}
	elseif($module == 'PurchaseOrder')
	{
		$query="select vtiger_products.productname,vtiger_products.unit_price,vtiger_products.qtyinstock,vtiger_poproductrel.* from vtiger_poproductrel inner join vtiger_products on vtiger_products.productid=vtiger_poproductrel.productid where purchaseorderid=".$focus->id;
	}
	elseif($module == 'SalesOrder')
	{
		$query="select vtiger_products.productname,vtiger_products.unit_price,vtiger_products.qtyinstock,vtiger_soproductrel.* from vtiger_soproductrel inner join vtiger_products on vtiger_products.productid=vtiger_soproductrel.productid where salesorderid=".$focus->id;
	}
	elseif($module == 'Invoice')
	{
		$query="select vtiger_products.productname,vtiger_products.unit_price,vtiger_products.qtyinstock,vtiger_invoiceproductrel.* from vtiger_invoiceproductrel inner join vtiger_products on vtiger_products.productid=vtiger_invoiceproductrel.productid where invoiceid=".$focus->id;
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
		$vat=$adb->query_result($result,$i-1,'vattax');
		$sales=$adb->query_result($result,$i-1,'salestax');
		$service=$adb->query_result($result,$i-1,'servicetax');
		$total = $qty*$listprice;
		$total_with_tax = $total+($vat*$total/100)+($sales*$total/100)+($service*$total/100);

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
		$output .= '<td style="padding:3px;"><div id="total'.$i.'" align="right">'.$total_with_tax.'</div></td>';
		$output .= '</tr>';


	}
	$output .= '<tr id="tableheadline">';
	$output .= '<td colspan="14" height="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td></tr>';
	$output .= '</table>';
	$output .= '<table width="100%" border="0" cellspacing="2" cellpadding="2" bgcolor="#FFFFFF">';
	$output .= '<tr>'; 
	$output .= '<td width="150"></td>';
	$output .= '<td><div align="right"><b>'.$app_strings['LBL_SUB_TOTAL'].':</b></div></td>';
	$output .= '<td width="150"><div align="right" style="border:1px solid #000;padding:2px">&nbsp;'.$focus->column_fields['hdnSubTotal'].'</div></td>';
	$output .= '</tr>';
	$output .= '<tr>'; 
	$output .=  '<td>&nbsp;</td>';
	$output .= '<td><div align="right"><b>'.$app_strings['LBL_TAX'].':</b></div></td>';
	$output .= '<td width="150"><div align="right" style="border:1px solid #000;padding:2px">&nbsp;'.$focus->column_fields['txtTax'].'</div></td>';
	$output .= '</tr>';
	$output .= '<tr>'; 
	$output .= '<td>&nbsp;</td>';
	$output .= '<td><div align="right"><b>'.$app_strings['LBL_ADJUSTMENT'].':</b></div></td>';
	$output .= '<td width="150"><div align="right"><div align="right" style="border:1px solid #000;padding:2px">&nbsp;'.$focus->column_fields['txtAdjustment'].'</div></td>';
	$output .= '</tr>';
	$output .= '<tr>'; 
	$output .= '<td>&nbsp;</td>';
	$output .= '<td><div align="right"><b>'.$app_strings['LBL_GRAND_TOTAL'].':</b></div></td>';
	$output .= '<td width="150"><div id="grandTotal" align="right" style="border:1px solid #000;padding:2px">&nbsp;'.$focus->column_fields['hdnGrandTotal'].'</div></td>';
	$output .= '</tr>';
	$output .= '</table>';

	$log->debug("Exiting getDetailAssociatedProducts method ...");
	return $output;

}

/** This function returns the related vtiger_tab details for a given entity or a module.
* Param $module - module name
* Param $focus - module object
* Return type is an array
*/
		
function getRelatedLists($module,$focus)
{
	global $log;
	$log->debug("Entering getRelatedLists(".$module.",".$focus.") method ...");
	global $adb;
	global $current_user;
	require('user_privileges/user_privileges_'.$current_user->id.'.php');
	
	$cur_tab_id = getTabid($module);

	$sql1 = "select * from vtiger_relatedlists where tabid=".$cur_tab_id;
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
	$log->debug("Exiting getRelatedLists method ...");
	return $focus_list;
}

/** This function returns the detailed block information of a record in a module.
* Param $module - module name
* Param $block - block id
* Param $col_fields - column vtiger_fields array for the module 
* Param $tabid - vtiger_tab id
* Return type is an array
*/

function getDetailBlockInformation($module, $result,$col_fields,$tabid,$block_label)
{
	global $log;
	$log->debug("Entering getDetailBlockInformation(".$module.",". $result.",".$col_fields.",".$tabid.",".$block_label.") method ...");
	global $adb;
	global $current_user;
	global $mod_strings;
	$label_data = Array();

	$noofrows = $adb->num_rows($result);
	for($i=0; $i<$noofrows; $i++)
	{
		$fieldtablename = $adb->query_result($result,$i,"tablename");	
		$fieldcolname = $adb->query_result($result,$i,"columnname");	
		$uitype = $adb->query_result($result,$i,"uitype");	
		$fieldname = $adb->query_result($result,$i,"fieldname");	
		$fieldlabel = $adb->query_result($result,$i,"fieldlabel");
		$maxlength = $adb->query_result($result,$i,"maximumlength");
		$block = $adb->query_result($result,$i,"block");
		$generatedtype = $adb->query_result($result,$i,"generatedtype");
		$tabid = $adb->query_result($result,$i,"tabid");
		$custfld = getDetailViewOutputHtml($uitype, $fieldname, $fieldlabel, $col_fields,$generatedtype,$tabid);
		$label_data[$block][] = array($custfld[0]=>array("value"=>$custfld[1],"ui"=>$custfld[2],"options"=>$custfld["options"],"secid"=>$custfld["secid"],"link"=>$custfld["link"],"cursymb"=>$custfld["cursymb"],"salut"=>$custfld["salut"],"cntimage"=>$custfld["cntimage"],"isadmin"=>$custfld["isadmin"],"tablename"=>$fieldtablename,"fldname"=>$fieldname));
		$i++;
		if($i<$noofrows)
		{
			$fieldtablename = $adb->query_result($result,$i,"tablename");	
			$fieldcolname = $adb->query_result($result,$i,"columnname");	
			$uitype = $adb->query_result($result,$i,"uitype");	
			$fieldname = $adb->query_result($result,$i,"fieldname");	
			$fieldlabel = $adb->query_result($result,$i,"fieldlabel");
			$maxlength = $adb->query_result($result,$i,"maximumlength");
			$block = $adb->query_result($result,$i,"block");
			$generatedtype = $adb->query_result($result,$i,"generatedtype");
			$tabid = $adb->query_result($result,$i,"tabid");

			$custfld = getDetailViewOutputHtml($uitype, $fieldname, $fieldlabel, $col_fields,$generatedtype,$tabid);
			$label_data[$block][] = array($custfld[0]=>array("value"=>$custfld[1],"ui"=>$custfld[2],"options"=>$custfld["options"],"secid"=>$custfld["secid"],"link"=>$custfld["link"],"cursymb"=>$custfld["cursymb"],"salut"=>$custfld["salut"],"cntimage"=>$custfld["cntimage"],"isadmin"=>$custfld["isadmin"],"tablename"=>$fieldtablename,"fldname"=>$fieldname));
		}

	}
	foreach($label_data as $headerid=>$value_array)
	{
		$detailview_data = Array();
		for ($i=0,$j=0;$i<count($value_array);$i=$i+2,$j++)
		{
			$key2 = null;
			$keys=array_keys($value_array[$i]);
			$key1=$keys[0];
			if(is_array($value_array[$i+1]))
			{
				$keys=array_keys($value_array[$i+1]);
				$key2=$keys[0];
			}
			$detailview_data[$j]=array($key1 => $value_array[$i][$key1],$key2 => $value_array[$i+1][$key2]);
		}
		$label_data[$headerid] = $detailview_data;
	}
	foreach($block_label as $blockid=>$label)
	{
		if($label == '')
		{
			$returndata[$mod_strings[$curBlock]]=array_merge((array)$returndata[$mod_strings[$curBlock]],(array)$label_data[$blockid]);
		}
		else
		{
			$curBlock = $label;
			if(is_array($label_data[$blockid]))
				$returndata[$mod_strings[$label]]=array_merge((array)$returndata[$mod_strings[$label]],(array)$label_data[$blockid]);
		}
	}
	$log->debug("Exiting getDetailBlockInformation method ...");
	return $returndata;


}

?>
