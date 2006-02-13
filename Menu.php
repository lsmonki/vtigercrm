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
 * $Header: /cvsroot/vtigercrm/vtiger_crm/Attic/Menu.php,v 1.17 2005/06/21 16:37:27 crouchingtiger Exp $
 * Description:  TODO To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $mod_strings;
global $app_strings;
global $moduleList;
//print_r($moduleList);
require_once('include/utils.php');
$permissionData = $_SESSION['action_permission_set'];

$module_menu_array = Array('Contacts' => $app_strings['LNK_NEW_CONTACT'],
	                   'Leads'=> $app_strings['LNK_NEW_LEAD'],
	                   'Accounts' => $app_strings['LNK_NEW_ACCOUNT'],
	                   'Potentials' => $app_strings['LNK_NEW_OPPORTUNITY'],
	                   'HelpDesk' => $app_strings['LNK_NEW_HDESK'],
	                   'Faq' => $app_strings['LNK_NEW_FAQ'],
	                   'Products' => $app_strings['LNK_NEW_PRODUCT'],
	                   'Notes' => $app_strings['LNK_NEW_NOTE'],
	                   'Emails' => $app_strings['LNK_NEW_EMAIL'],
			   'Events' => $app_strings['LNK_NEW_EVENT'],
	                   'Tasks' => $app_strings['LNK_NEW_TASK'],
	                   'Vendor' => $app_strings['LNK_NEW_VENDOR'],
	                   'PriceBook' => $app_strings['LNK_NEW_PRICEBOOK'],
			   'Quotes' => $app_strings['LNK_NEW_QUOTE'],	
			   'Orders' => $app_strings['LNK_NEW_PO'],	
			   'SalesOrder' => $app_strings['LNK_NEW_SO'],	
			   'Invoice' => $app_strings['LNK_NEW_INVOICE']	
	                    );
$module_menu = Array();
$i= 0;
$add_url = "";
foreach($module_menu_array as $module1 => $label)
{
	$add_url='';
	$curr_action = 'EditView';
	$ret_action = 'DetailView';
	if($module1 == 'Events')
	{
		$module_display = 'Activities';
		$add_url = "&activity_mode=Events";
		$tabid = getTabid($module1);
	}
	elseif($module1 == 'Tasks')
	{
		$module_display = 'Activities';
                $add_url = "&activity_mode=Task";
		$tabid = getTabid("Activities");
	}
	elseif($module1 == 'SalesOrder')
	{
		$module_display = 'Orders';
		$tabid = getTabid("SalesOrder");
		$curr_action = 'SalesOrderEditView';
		$ret_action = 'SalesOrderDetailView';	
		
	}
	elseif($module1 == 'Vendor')
	{
		$module_display = 'Products';
		$tabid = getTabid("Vendor");
		$curr_action = 'VendorEditView';
		$ret_action = 'VendorDetailView';	
		
	}
	elseif($module1 == 'PriceBook')
	{
		$module_display = 'Products';
		$tabid = getTabid("PriceBook");
		$curr_action = 'PriceBookEditView';
		$ret_action = 'PriceBookDetailView';	
		
	}
	else
	{
		$module_display = $module1;
		$tabid = getTabid($module1);
	}

	if($module1 == 'Vendor' || $module1 == 'PriceBook' || $module1 == 'SalesOrder')
	{
		$profile_id = $_SESSION['authenticated_user_profileid'];
	        $tab_per_Data = getAllTabsPermission($profile_id);
		if($tab_per_Data[$tabid] == 0)
		{
			if($permissionData[$tabid][1] ==0)
			{
				$tempArray = Array("index.php?module=".$module_display."&action=".$curr_action."&return_module=".$module_display."&return_action=".$ret_action.$add_url, $label);
				$module_menu[$i] = $tempArray;
				$i++;
			}
		}
	
	}
	elseif(in_array($module_display, $moduleList))
	{
	
		if($permissionData[$tabid][1] ==0)
		{
			$tempArray = Array("index.php?module=".$module_display."&action=".$curr_action."&return_module=".$module_display."&return_action=".$ret_action.$add_url, $label);
			$module_menu[$i] = $tempArray;
			$i++;
		}
	}
	elseif($module_display == 'Faq')
	{
			$tempArray = Array("index.php?module=".$module_display."&action=".$curr_action."&return_module=".$module_display."&return_action=".$ret_action.$add_url, $label);
			$module_menu[$i] = $tempArray;
			$i++;
	}
	
}


/*
$module_menu = Array(
	Array("index.php?module=Contacts&action=EditView&return_module=Contacts&return_action=DetailView", $app_strings['LNK_NEW_CONTACT']),
	Array("index.php?module=Leads&action=EditView&return_module=Leads&return_action=DetailView", $app_strings['LNK_NEW_LEAD']),
	Array("index.php?module=Accounts&action=EditView&return_module=Accounts&return_action=DetailView", $app_strings['LNK_NEW_ACCOUNT']),
	Array("index.php?module=Potentials&action=EditView&return_module=Potentials&return_action=DetailView", $app_strings['LNK_NEW_OPPORTUNITY']),
	Array("index.php?module=HelpDesk&action=EditView&return_module=HelpDesk&return_action=DetailView", $app_strings['LNK_NEW_HDESK']),
	Array("index.php?module=Products&action=EditView&return_module=Products&return_action=DetailView", $app_strings['LNK_NEW_PRODUCT']),
	Array("index.php?module=Notes&action=EditView&return_module=Notes&return_action=DetailView", $app_strings['LNK_NEW_NOTE']),
	Array("index.php?module=Emails&action=EditView&return_module=Emails&return_action=DetailView", $app_strings['LNK_NEW_EMAIL']),
	Array("index.php?module=Activities&action=EditView&return_module=Activities&activity_mode=Events&return_action=DetailView", $app_strings['LNK_NEW_EVENT']),
	Array("index.php?module=Activities&action=EditView&return_module=Activities&activity_mode=Task&return_action=DetailView", $app_strings['LNK_NEW_TASK'])
	);
*/

?>
