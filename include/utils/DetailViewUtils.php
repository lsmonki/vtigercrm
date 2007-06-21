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
	require('user_privileges/user_privileges_'.$current_user->id.'.php');
	require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
	if($generatedtype == 2)
		$mod_strings[$fieldlabel] = $fieldlabel;

	if($uitype == 99)
	{
		$label_fld[] = $mod_strings[$fieldlabel];
		$label_fld[] = $col_fields[$fieldname];
		if($fieldname == 'confirm_password')
			return null;
	}elseif($uitype == 116)
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
	elseif($uitype == 15 || $uitype == 16 || $uitype == 115 || $uitype == 111) //uitype 111 added for non editable picklist - ahmed
	{
	     $label_fld[] = $mod_strings[$fieldlabel];
	     $label_fld[] = $col_fields[$fieldname];
	     
		$pick_query="select * from vtiger_".$fieldname." order by sortorderid";
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
	elseif($uitype == 33) //uitype 33 added for multiselector picklist - Jeri
	{
	     $label_fld[] = $mod_strings[$fieldlabel];
	     $label_fld[] = str_ireplace(' |##| ',', ',$col_fields[$fieldname]);
	     
		$pick_query="select * from vtiger_".$fieldname;
		$pickListResult = $adb->query($pick_query);
		$noofpickrows = $adb->num_rows($pickListResult);

		$options = array();
		$selected_entries = Array();
		$selected_entries = explode(' |##| ',$col_fields[$fieldname]);
		for($j = 0; $j < $noofpickrows; $j++)
		{
			$pickListValue = $adb->query_result($pickListResult,$j,strtolower($fieldname));
      $chk_val = '';
      foreach($selected_entries as $selected_entries_value)
      {
        if(trim($selected_entries_value) == trim($pickListValue))
        {
          $chk_val = 'selected';
          break;
        }
      }
			$options[] = array($pickListValue=>$chk_val);	
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
		$col_fields[$fieldname]= str_replace("&lt;br /&gt;","<br>",$col_fields[$fieldname]);
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
		$assigned_user_id = $current_user->id;
		if(is_admin($current_user))
		{
			$label_fld[] ='<a href="index.php?module=Users&action=DetailView&record='.$user_id.'">'.$user_name.'</a>';
		}
		else
		{
			$label_fld[] =$user_name;
		}
		if($is_admin==false && $profileGlobalPermission[2] == 1 && ($defaultOrgSharingPermission[getTabid($module)] == 3 or $defaultOrgSharingPermission[getTabid($module)] == 0))
		{
			$users_combo = get_select_options_array(get_user_array(FALSE, "Active", $assigned_user_id,'private'), $assigned_user_id);
		}
		else
		{
			$users_combo = get_select_options_array(get_user_array(FALSE, "Active", $user_id), $assigned_user_id);
		}
		$label_fld ["options"] = $users_combo;

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
			$label_fld["link"][] = "index.php?module=Settings&action=GroupDetailView&groupId=".$groupid;
		}
		//Security Checks
		if($fieldlabel == 'Assigned To' && $is_admin==false && $profileGlobalPermission[2] == 1 && ($defaultOrgSharingPermission[getTabid($module)] == 3 or $defaultOrgSharingPermission[getTabid($module)] == 0))
		{
			$result=get_current_user_access_groups($module);
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

		if($fieldlabel == 'Assigned To' && $is_admin==false && $profileGlobalPermission[2] == 1 && ($defaultOrgSharingPermission[getTabid($module)] == 3 or $defaultOrgSharingPermission[getTabid($module)] == 0))
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
			   //This is used to show the contact image as a thumbnail near First Name field
                           //$label_fld["cntimage"] ='<div style="position:absolute;height=100px"><img class="thumbnail" src="'.$imgpath.'" width="60" height="60" border="0"></div>&nbsp;'.$mod_strings[$fieldlabel];
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
			//Since "yes" is not been translated it is given as app strings here..
			$display_val = $app_strings['yes'];
		}
		else
		{
			$display_val = $app_strings['no'];
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
					<img src="modules/Products/placeholder.gif" width="571" height="117" style="position:relative;">
					</div><script>var Car_NoOfSides='.$sides.'; Car_Image_Sources=new Array(';

				for($image_iter=0;$image_iter < count($image_array);$image_iter++)
				{
					$images[]='"'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'","'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'"';
				}	
				$image_lists .=implode(',',$images).');</script><script language="JavaScript" type="text/javascript" src="modules/Products/Productsslide.js"></script><script language="JavaScript" type="text/javascript">Carousel();</script>';
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
			$sql = "select vtiger_attachments.* from vtiger_attachments inner join vtiger_seattachmentsrel on vtiger_seattachmentsrel.attachmentsid = vtiger_attachments.attachmentsid inner join vtiger_contactdetails on vtiger_contactdetails.imagename=vtiger_attachments.name where vtiger_seattachmentsrel.crmid=".$col_fields['record_id'];
			$image_res = $adb->query($sql);
			$image_id = $adb->query_result($image_res,0,'attachmentsid');
			$image_path = $adb->query_result($image_res,0,'path');
			$image_name = $adb->query_result($image_res,0,'name');
			$imgpath = $image_path.$image_id."_".$image_name;
			if($image_name != '')
				$label_fld[] ='<img src="'.$imgpath.'" alt="'.$app_strings['MSG_IMAGE_ERROR'].'" title= "'.$app_strings['MSG_IMAGE_ERROR'].'">';
			else
				$label_fld[] = '';
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
			elseif($parent_module == "Quotes")
			{
				$label_fld[] = $app_strings['LBL_QUOTES_NAME'];
				$sql = "select * from  vtiger_quotes where quoteid=".$value;
				$result = $adb->query($sql);
				$quotename= $adb->query_result($result,0,"subject");

				$label_fld[] ='<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$quotename.'</a>';
			}
			elseif($parent_module == "HelpDesk")
			{
				$label_fld[] = $app_strings['LBL_HELPDESK_NAME'];
				$sql = "select * from  vtiger_troubletickets where ticketid=".$value;
				$result = $adb->query($sql);
				$title= $adb->query_result($result,0,"title");
				$label_fld[] ='<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$title.'</a>';
			}
		}
		else
		{
			$label_fld[] = $mod_strings[$fieldlabel];
			$label_fld[] = $value;
		}


	}
	elseif($uitype == 105)//Added for user image
	{
		$label_fld[] =$mod_strings[$fieldlabel];
		//$imgpath = getModuleFileStoragePath('Contacts').$col_fields[$fieldname];
		$sql = "select vtiger_attachments.* from vtiger_attachments left join vtiger_salesmanattachmentsrel on vtiger_salesmanattachmentsrel.attachmentsid = vtiger_attachments.attachmentsid where vtiger_salesmanattachmentsrel.smid=".$col_fields['record_id'];
		$image_res = $adb->query($sql);
		$image_id = $adb->query_result($image_res,0,'attachmentsid');
		$image_path = $adb->query_result($image_res,0,'path');
		$image_name = $adb->query_result($image_res,0,'name');
		$imgpath = $image_path.$image_id."_".$image_name;
		if($image_name != '')
		$label_fld[] ='<a href="'.$imgpath.'" target="_blank"><img src="'.$imgpath.'" width="450" height="300" alt="'.$col_fields['user_name'].'" title="'.$col_fields['user_name'].'" border="0"></a>';
		else
			$label_fld[] = '';
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
			elseif($parent_module == "Campaigns")
			{
				$label_fld[] = $app_strings['LBL_CAMPAIGN_NAME'];
				$sql = "select * from  vtiger_campaign where campaignid=".$value;
				$result = $adb->query($sql);
				$campaignname = $adb->query_result($result,0,"campaignname");
				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$campaignname.'</a>';
			}
			elseif($parent_module == "HelpDesk")
			{
				$label_fld[] = $app_strings['LBL_HELPDESK_NAME'];
				$sql = "select * from  vtiger_troubletickets where ticketid=".$value;
				$result = $adb->query($sql);
				$tickettitle = $adb->query_result($result,0,"title");
				if(strlen($tickettitle) > 25)
				{
					$tickettitle = substr($tickettitle,0,25).'...';
				}
				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$tickettitle.'</a>';
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
		if($col_fields['time_end']!='' && ($tabid == 9 || $tabid == 16) && $uitype == 23)
		{
			$end_time = $col_fields['time_end'];
		}
		if($cur_date_val == '0000-00-00')
		{
			$display_val = '';	
		}
		else
		{
			$display_val = getDisplayDate($cur_date_val);
		}
		$label_fld[] = $display_val.'&nbsp;'.$end_time;
	}
	elseif($uitype == 71 || $uitype == 72)
	{
		$rate_symbol=getCurrencySymbolandCRate($user_info['currency_id']);
                $rate = $rate_symbol['rate'];
                $curr_symbol = $rate_symbol['symbol'];
		$label_fld[] =$mod_strings[$fieldlabel];
		$display_val = '';
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
			$label_fld[] = '<a href="index.php?module=Settings&action=RoleDetailView&roleid='.$col_fields[$fieldname].'">'.getRoleName($col_fields[$fieldname]).'</a>';
		else
			$label_fld[] = getRoleName($col_fields[$fieldname]);
	}elseif($uitype == 85) //Added for Skype by Minnie
	{
		$label_fld[] =$mod_strings[$fieldlabel];
		$label_fld[]= $col_fields[$fieldname];
	}
	else
	{
	 $label_fld[] =$mod_strings[$fieldlabel];
        if($col_fields[$fieldname]=='0')
              $col_fields[$fieldname]='';
	 if($uitype == 1 && ($fieldname=='expectedrevenue' || $fieldname=='budgetcost' || $fieldname=='actualcost' || $fieldname=='expectedroi' || $fieldname=='actualroi' ))
	 {
		  $rate_symbol=getCurrencySymbolandCRate($user_info['currency_id']);
		  $label_fld[] = convertFromDollar($col_fields[$fieldname],$rate_symbol['rate']);
	 }
	else
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
	$log->debug("Entering getDetailAssociatedProducts(".$module.",".get_class($focus).") method ...");
	global $adb;
	global $mod_strings;
	global $theme;
	global $log;
	global $app_strings,$current_user;
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";

	if($module != 'PurchaseOrder')
	{
		$colspan = '4';
	}
	else
	{
		$colspan = '3';
	}

	//Get the taxtype of this entity
	$taxtype = getInventoryTaxType($module,$focus->id);

	$output = '';
	//Header Rows
	$output .= '

	<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable" id="proTab">
	   <tr valign="top">
	   	<td colspan="'.$colspan.'" class="dvInnerHeader"><b>'.$app_strings['LBL_PRODUCT_DETAILS'].'</b></td>
		<td class="dvInnerHeader" align="right"><b>'.$app_strings['LBL_TAX_MODE'].' : </b></td>
		<td class="dvInnerHeader">'.$app_strings[$taxtype].'</td>
	   </tr>
	   <tr valign="top">
		<td width=40% class="lvtCol"><font color="red">*</font>
			<b>'.$app_strings['LBL_PRODUCT_NAME'].'</b>
		</td>';

	//Add Quantity in Stock column for SO, Quotes and Invoice
	if($module == 'Quotes' || $module == 'SalesOrder' || $module == 'Invoice')
		$output .= '<td width=10% class="lvtCol"><b>'.$app_strings['LBL_QTY_IN_STOCK'].'</b></td>';

	$output .= '
	
		<td width=10% class="lvtCol"><b>'.$app_strings['LBL_QTY'].'</b></td>
		<td width=10% class="lvtCol" align="right"><b>'.$app_strings['LBL_LIST_PRICE'].'</b></td>
		<td width=12% nowrap class="lvtCol" align="right"><b>'.$app_strings['LBL_TOTAL'].'</b></td>
		<td width=13% valign="top" class="lvtCol" align="right"><b>'.$app_strings['LBL_NET_PRICE'].'</b></td>
	   </tr>
	   	';


	// DG 15 Aug 2006
	// Add "ORDER BY sequence_no" to retain add order on all inventoryproductrel items

	if($module == 'Quotes')
	{
		$query="select vtiger_products.productname,vtiger_products.unit_price,vtiger_products.qtyinstock, vtiger_inventoryproductrel.* from vtiger_inventoryproductrel inner join vtiger_products on vtiger_products.productid=vtiger_inventoryproductrel.productid where id=".$focus->id." ORDER BY sequence_no";
	}
	elseif($module == 'PurchaseOrder')
	{
		$query="select vtiger_products.productname,vtiger_products.unit_price,vtiger_products.qtyinstock,vtiger_inventoryproductrel.* from vtiger_inventoryproductrel inner join vtiger_products on vtiger_products.productid=vtiger_inventoryproductrel.productid where id=".$focus->id." ORDER BY sequence_no";
	}
	elseif($module == 'SalesOrder')
	{
		$query="select vtiger_products.productname,vtiger_products.unit_price,vtiger_products.qtyinstock,vtiger_inventoryproductrel.* from vtiger_inventoryproductrel inner join vtiger_products on vtiger_products.productid=vtiger_inventoryproductrel.productid where id=".$focus->id." ORDER BY sequence_no";
	}
	elseif($module == 'Invoice')
	{
		$query="select vtiger_products.productname,vtiger_products.unit_price,vtiger_products.qtyinstock,vtiger_inventoryproductrel.* from vtiger_inventoryproductrel inner join vtiger_products on vtiger_products.productid=vtiger_inventoryproductrel.productid where id=".$focus->id." ORDER BY sequence_no";
	}

	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	$netTotal = '0.00';
	for($i=1;$i<=$num_rows;$i++)
	{
		$productid=$adb->query_result($result,$i-1,'productid');
		$productname=$adb->query_result($result,$i-1,'productname');
		$comment=$adb->query_result($result,$i-1,'comment');
		$qtyinstock=$adb->query_result($result,$i-1,'qtyinstock');
		$qty=$adb->query_result($result,$i-1,'quantity');
		$unitprice=$adb->query_result($result,$i-1,'unit_price');
		$listprice=$adb->query_result($result,$i-1,'listprice');
		$total = $qty*$listprice;

		$unitprice = getConvertedPriceFromDollar($unitprice);
		$listprice = getConvertedPriceFromDollar($listprice);

		//Product wise Discount calculation - starts
		$discount_percent=$adb->query_result($result,$i-1,'discount_percent');
		$discount_amount=$adb->query_result($result,$i-1,'discount_amount');
		//we should convert the amount and not convert the percentage
		$discount_amount = getConvertedPriceFromDollar($discount_amount);

		$total = getConvertedPriceFromDollar($total);
		$totalAfterDiscount = $total;

		$productDiscount = '0.00';
		if($discount_percent != 'NULL' && $discount_percent != '')
		{
			$productDiscount = $total*$discount_percent/100;
			$totalAfterDiscount = $total-$productDiscount;
			//if discount is percent then show the percentage
			$discount_info_message = "$discount_percent % of $total = $productDiscount";
		}
		elseif($discount_amount != 'NULL' && $discount_amount != '')
		{
			$productDiscount = $discount_amount;
			$totalAfterDiscount = $total-$productDiscount;
			$discount_info_message = $app_strings['LBL_DIRECT_AMOUNT_DISCOUNT']." = $productDiscount";
		}
		else
		{
			$discount_info_message = $app_strings['LBL_NO_DISCOUNT_FOR_THIS_PRODUCT'];
		}
		//Product wise Discount calculation - ends 

		$netprice = $totalAfterDiscount;
		//Calculate the individual tax if taxtype is individual
		if($taxtype == 'individual')
		{
			$taxtotal = '0.00';
			$tax_info_message = $app_strings['LBL_TOTAL_AFTER_DISCOUNT']." = $totalAfterDiscount \\n";
			$tax_details = getTaxDetailsForProduct($productid,'all');
			for($tax_count=0;$tax_count<count($tax_details);$tax_count++)
			{
				$tax_name = $tax_details[$tax_count]['taxname'];
				$tax_label = $tax_details[$tax_count]['taxlabel'];
				$tax_value = getInventoryProductTaxValue($focus->id, $productid, $tax_name);

				$individual_taxamount = $totalAfterDiscount*$tax_value/100;
				$taxtotal = $taxtotal + $individual_taxamount;
				$tax_info_message .= "$tax_label : $tax_value % = $individual_taxamount \\n";
			}
			$tax_info_message .= "\\n ".$app_strings['LBL_TOTAL_TAX_AMOUNT']." = $taxtotal";
			$netprice = $netprice + $taxtotal;
		}


		//For Product Name
		$output .= '
			   <tr valign="top">
				<td class="crmTableRow small lineOnTop">
					'.$productname.'
					<br>'.$comment.'
				</td>';
		//Upto this added to display the Product name and comment


		if($module != 'PurchaseOrder')
		{	
			$output .= '<td class="crmTableRow small lineOnTop">'.$qtyinstock.'</td>';
		}
		$output .= '<td class="crmTableRow small lineOnTop">'.$qty.'</td>';
		$output .= '
			<td class="crmTableRow small lineOnTop" align="right">
				<table width="100%" border="0" cellpadding="5" cellspacing="0">
				   <tr>
				   	<td align="right">'.$listprice.'</td>
				   </tr>
				   <tr>
					   <td align="right">(-)&nbsp;<b><a href="javascript:;" onclick="alert(\''.$discount_info_message.'\'); ">'.$app_strings['LBL_DISCOUNT'].' : </a></b></td>
				   </tr>
				   <tr>
				   	<td align="right" nowrap>'.$app_strings['LBL_TOTAL_AFTER_DISCOUNT'].' : </td>
				   </tr>';
		if($taxtype == 'individual')
		{
			$output .= '
				   <tr>
					   <td align="right" nowrap>(+)&nbsp;<b><a href="javascript:;" onclick="alert(\''.$tax_info_message.'\');">'.$app_strings['LBL_TAX'].' : </a></b></td>
				   </tr>';
		}
		$output .= '
				</table>
			</td>';

		$output .= '
			<td class="crmTableRow small lineOnTop" align="right">
				<table width="100%" border="0" cellpadding="5" cellspacing="0">
				   <tr><td align="right">'.$total.'</td></tr>
				   <tr><td align="right">'.$productDiscount.'</td></tr>
				   <tr><td align="right" nowrap>'.$totalAfterDiscount.'</td></tr>';

		if($taxtype == 'individual')
		{
			$output .= '<tr><td align="right" nowrap>'.$taxtotal.'</td></tr>';
		}

		$output .= '		   
				</table>
			</td>';
		$output .= '<td class="crmTableRow small lineOnTop" valign="bottom" align="right">'.$netprice.'</td>';
		$output .= '</tr>';

		$netTotal = $netTotal + $netprice;
	}

	$output .= '</table>';

	//$netTotal should be equal to $focus->column_fields['hdnSubTotal']
	$netTotal = $focus->column_fields['hdnSubTotal'];
	$netTotal = getConvertedPriceFromDollar($netTotal);

	//Display the total, adjustment, S&H details
	$output .= '<table width="100%" border="0" cellspacing="0" cellpadding="5" class="crmTable">';
	$output .= '<tr>'; 
	$output .= '<td width="88%" class="crmTableRow small" align="right"><b>'.$app_strings['LBL_NET_TOTAL'].'</td>';
	$output .= '<td width="12%" class="crmTableRow small" align="right"><b>'.$netTotal.'</b></td>';
	$output .= '</tr>';

	//Decide discount
	$finalDiscount = '0.00';
	if($focus->column_fields['hdnDiscountPercent'] != '')
	{
		$finalDiscount = ($netTotal*$focus->column_fields['hdnDiscountPercent']/100);
		$final_discount_info = $focus->column_fields['hdnDiscountPercent']." % of $netTotal = $finalDiscount";
	}
	elseif($focus->column_fields['hdnDiscountAmount'] != '')
	{
		$finalDiscount = $focus->column_fields['hdnDiscountAmount'];
		$finalDiscount = getConvertedPriceFromDollar($finalDiscount);
	}

	//Alert the Final Discount amount even it is zero
	$final_discount_info = $app_strings['LBL_FINAL_DISCOUNT_AMOUNT']." = $finalDiscount";
	$final_discount_info = 'onclick="alert(\''.$final_discount_info.'\');"';

	$output .= '<tr>'; 
	$output .= '<td align="right" class="crmTableRow small lineOnTop">(-)&nbsp;<b><a href="javascript:;" '.$final_discount_info.'>'.$app_strings['LBL_DISCOUNT'].'</a></b></td>';
	$output .= '<td align="right" class="crmTableRow small lineOnTop">'.$finalDiscount.'</td>';
	$output .= '</tr>';

	if($taxtype == 'group')
	{
		$taxtotal = '0.00';
		$final_totalAfterDiscount = $netTotal - $finalDiscount;
		$tax_info_message = $app_strings['LBL_TOTAL_AFTER_DISCOUNT']." = $final_totalAfterDiscount \\n";
		//First we should get all available taxes and then retrieve the corresponding tax values
		$tax_details = getAllTaxes('available');
		//if taxtype is group then the tax should be same for all products in vtiger_inventoryproductrel table
		for($tax_count=0;$tax_count<count($tax_details);$tax_count++)
		{
			$tax_name = $tax_details[$tax_count]['taxname'];
			$tax_label = $tax_details[$tax_count]['taxlabel'];
			$tax_value = $adb->query_result($result,0,$tax_name);
			if($tax_value == '' || $tax_value == 'NULL')
				$tax_value = '0.00';
			
			$taxamount = ($netTotal-$finalDiscount)*$tax_value/100;
			$taxtotal = $taxtotal + $taxamount;
			$tax_info_message .= "$tax_label : $tax_value % = $taxamount \\n";
		}
		$tax_info_message .= "\\n ".$app_strings['LBL_TOTAL_TAX_AMOUNT']." = $taxtotal";

		$output .= '<tr>';
		$output .= '<td align="right" class="crmTableRow small">(+)&nbsp;<b><a href="javascript:;" onclick="alert(\''.$tax_info_message.'\');">'.$app_strings['LBL_TAX'].'</a></b></td>';
		$output .= '<td align="right" class="crmTableRow small">'.$taxtotal.'</td>';
		$output .= '</tr>';
	}

	$shAmount = ($focus->column_fields['hdnS_H_Amount'] != '')?$focus->column_fields['hdnS_H_Amount']:'0.00';
	$shAmount = getConvertedPriceFromDollar($shAmount);
	$output .= '<tr>'; 
	$output .= '<td align="right" class="crmTableRow small">(+)&nbsp;<b>'.$app_strings['LBL_SHIPPING_AND_HANDLING_CHARGES'].'</b></td>';
	$output .= '<td align="right" class="crmTableRow small">'.$shAmount.'</td>';
	$output .= '</tr>';

	//calculate S&H tax
	$shtaxtotal = '0.00';
	//First we should get all available taxes and then retrieve the corresponding tax values
	$shtax_details = getAllTaxes('available','sh');
	//if taxtype is group then the tax should be same for all products in vtiger_inventoryproductrel table
	$shtax_info_message = $app_strings['LBL_SHIPPING_AND_HANDLING_CHARGE']." = $shAmount \\n";
	for($shtax_count=0;$shtax_count<count($shtax_details);$shtax_count++)
	{
		$shtax_name = $shtax_details[$shtax_count]['taxname'];
		$shtax_label = $shtax_details[$shtax_count]['taxlabel'];
		$shtax_percent = getInventorySHTaxPercent($focus->id,$shtax_name);
		$shtaxamount = $shAmount*$shtax_percent/100;
		$shtaxtotal = $shtaxtotal + $shtaxamount;
		$shtax_info_message .= "$shtax_label : $shtax_percent % = $shtaxamount \\n";
	}
	$shtax_info_message .= "\\n ".$app_strings['LBL_TOTAL_TAX_AMOUNT']." = $shtaxtotal";
	
	$output .= '<tr>'; 
	$output .= '<td align="right" class="crmTableRow small">(+)&nbsp;<b><a href="javascript:;" onclick="alert(\''.$shtax_info_message.'\')">'.$app_strings['LBL_TAX_FOR_SHIPPING_AND_HANDLING'].'</a></b></td>';
	$output .= '<td align="right" class="crmTableRow small">'.$shtaxtotal.'</td>';
	$output .= '</tr>';

	$adjustment = ($focus->column_fields['txtAdjustment'] != '')?$focus->column_fields['txtAdjustment']:'0.00';
	$adjustment = getConvertedPriceFromDollar($adjustment);
	$output .= '<tr>'; 
	$output .= '<td align="right" class="crmTableRow small">&nbsp;<b>'.$app_strings['LBL_ADJUSTMENT'].'</b></td>';
	$output .= '<td align="right" class="crmTableRow small">'.$adjustment.'</td>';
	$output .= '</tr>';

	$grandTotal = ($focus->column_fields['hdnGrandTotal'] != '')?$focus->column_fields['hdnGrandTotal']:'0.00';
	$grandTotal = getConvertedPriceFromDollar($grandTotal);
	$output .= '<tr>'; 
	$output .= '<td align="right" class="crmTableRow small lineOnTop"><b>'.$app_strings['LBL_GRAND_TOTAL'].'</b></td>';
	$output .= '<td align="right" class="crmTableRow small lineOnTop">'.$grandTotal.'</td>';
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
	$log->debug("Entering getRelatedLists(".$module.",".get_class($focus).") method ...");
	global $adb;
	global $current_user;
	require('user_privileges/user_privileges_'.$current_user->id.'.php');
	
	$cur_tab_id = getTabid($module);

	$sql1 = "select * from vtiger_relatedlists where tabid=".$cur_tab_id." order by sequence";
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

/** This function returns whether related lists is present for this particular module or not
* Param $module - module name
* Param $activity_mode - mode of activity 
* Return type true or false
*/


function isPresentRelatedLists($module,$activity_mode='')
{
	global $adb;
	$retval='true';
	$tab_id=getTabid($module);
	$query= "select count(*) as count from vtiger_relatedlists where tabid=".$tab_id;
	$result=$adb->query($query);
	$count=$adb->query_result($result,0,'count');
	if($count < 1 || ($module =='Calendar' && $activity_mode=='task'))
	{
		$retval='false';	
	}	
	return $retval;	
			
	
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
		if(is_array($custfld))
		{
			$label_data[$block][] = array($custfld[0]=>array("value"=>$custfld[1],"ui"=>$custfld[2],"options"=>$custfld["options"],"secid"=>$custfld["secid"],"link"=>$custfld["link"],"cursymb"=>$custfld["cursymb"],"salut"=>$custfld["salut"],"cntimage"=>$custfld["cntimage"],"isadmin"=>$custfld["isadmin"],"tablename"=>$fieldtablename,"fldname"=>$fieldname));
		}
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
			if(is_array($custfld))
			{
				$label_data[$block][] = array($custfld[0]=>array("value"=>$custfld[1],"ui"=>$custfld[2],"options"=>$custfld["options"],"secid"=>$custfld["secid"],"link"=>$custfld["link"],"cursymb"=>$custfld["cursymb"],"salut"=>$custfld["salut"],"cntimage"=>$custfld["cntimage"],"isadmin"=>$custfld["isadmin"],"tablename"=>$fieldtablename,"fldname"=>$fieldname));
			}
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
