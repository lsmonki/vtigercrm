<?php

/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/


require_once('include/database/PearDatabase.php');
require_once('XTemplate/xtpl.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('modules/Users/UserInfoUtil.php');
require_once('include/utils.php');

global $mod_strings;
global $app_strings;
global $app_list_strings;


$profileid = $_REQUEST["profileid"];
$profilename = getProfileName($profileid);
echo '<form action="index.php" method="post" name="new" id="form">';
echo get_module_title("Users", "Profile Information: ".$profilename, true);

global $adb;
global $theme;
global $theme_path;
global $image_path;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

//Retreiving the tabs permisson array
$tab_perr_array = getTabsPermission($profileid);
$act_perr_arry = getTabsActionPermission($profileid);
$act_utility_arry = getTabsUtilityActionPermission($profileid);
$global_per_arry = getProfileGlobalPermission($profileid);

$xtpl=new XTemplate ('modules/Users/ProfileDetailView.html');

$standCustFld = getStdOutput($tab_perr_array, $act_perr_arry, $act_utility_arry,$profileid,$global_per_arry);

//Standard PickList Fields
function getStdOutput($tab_perr_array, $act_perr_arry, $act_utility_arry,$profileid,$global_per_arry)
{
	global $adb;
	global $mod_strings;
	global $app_strings;
	global $image_path;
	$standCustFld = '';
	$standCustFld .= '<input type="hidden" name="module" value="Users">';
	$standCustFld .= '<input type="hidden" name="profileid" value="'.$profileid.'">';
	$standCustFld .= '<input type="hidden" name="action" value="ProfileEditView">';

	//Check for Current Profile
	global $current_user;
	$current_profile = fetchUserProfileId($current_user->id);
	
	$standCustFld .= '<br><input title="Edit" accessKey="C" class="button" onclick="this.form.action.value=\'ProfileEditView\'" type="submit" name="Edit" value="Edit Profile">&nbsp;&nbsp;';
	$standCustFld .= '<input title="'.$app_strings["LBL_CANCEL_BUTTON_TITLE"].'" accessKey="'.$app_strings["LBL_CANCEL_BUTTON_KEY"].'" class="button" onclick="window.history.back()" type="button" name="button" value="  '.$app_strings["LBL_CANCEL_BUTTON_LABEL"].'  ">&nbsp;&nbsp;';	

	if($profileid != 1 && $profileid != 2 && $profileid != 3 && $profileid != 4 && $profileid != $current_profile)

        {
	
		$standCustFld .= '<input title="Delete" accessKey="D" class="button" onclick="this.form.action.value=\'ProfileDeleteStep1\'"  type="submit" name="Delete" value="Delete Profile">';
	}
	$standCustFld .= '<BR><BR>';
	$standCustFld .= '<table border="0" cellpadding="0" cellspacing="0" width="60%"><tr><td>';
	$standCustFld .=  get_form_header("Profile Global Access Information", "", false );
	$standCustFld .= '</td></tr></table>';
	$standCustFld .= '<table border="0" cellpadding="0" cellspacing="0" class="FormBorder" width="60%">';
	$standCustFld .=  '<tr class="ModuleListTitle" height=20>';
	$standCustFld .=  '<td width="" class="moduleListTitle" style="padding:0px 3px 0px 3px;">View All</td>';
	$view_all_per = $global_per_arry[1];
	$standCustFld .=  '<td width="" class="moduleListTitle" style="padding:0px 3px 0px 3px;"><div align="center">'.getGlobalDisplayValue($view_all_per,1).'</div></td>';
	$standCustFld .=  '<td width="" class="moduleListTitle" style="padding:0px 3px 0px 3px;"><div align="center">Edit All</div></td>';
	$edit_all_per = $global_per_arry[2];
	$standCustFld .=  '<td width="" class="moduleListTitle" style="padding:0px 3px 0px 3px;"><div align="center">'.getGlobalDisplayValue($edit_all_per,2).'</div></td>';
	$standCustFld .=  '</tr>';

	$standCustFld .='</table>';


	$standCustFld .= '<BR><BR>';
	$standCustFld .= '<table border="0" cellpadding="0" cellspacing="0" width="60%"><tr><td>';
	$standCustFld .=  get_form_header("Profile Tab Access Information", "", false );
	$standCustFld .= '</td></tr></table>';
	$standCustFld .= '<table border="0" cellpadding="0" cellspacing="0" class="FormBorder" width="60%">';
	$standCustFld .=  '<tr class="ModuleListTitle" height=20>';
	$standCustFld .=  '<td width="" class="moduleListTitle" style="padding:0px 3px 0px 3px;">Entity</td>';
	$standCustFld .=  '<td width="" class="moduleListTitle" style="padding:0px 3px 0px 3px;"><div align="center">Allow</div></td>';
	$standCustFld .=  '<td width="" class="moduleListTitle" style="padding:0px 3px 0px 3px;">Entity</td>';
	$standCustFld .=  '<td width="" class="moduleListTitle" style="padding:0px 3px 0px 3px;"><div align="center">Allow</div></td>';
	$standCustFld .=  '</tr>';

	$i = 1;
	$rowclass='';
	$no_of_tabs =  sizeof($tab_perr_array);	
	foreach($tab_perr_array as $tabid=>$tab_perr)
	{
		$entity_name = getTabname($tabid);
		//Tab Permission
		$tab_allow_per_id = $tab_perr_array[$tabid];
		$tab_allow_per = getDisplayValue($tab_allow_per_id,$tabid,'');
	
		if ($i%2==0)
		{
			$trowclass = 'evenListRow';
		}
		else
		{
			if($rowclass == '')
			{	
				$trowclass = 'oddListRow';
				$rowclass = 'evenListRow';	
			}
			elseif($rowclass == 'evenListRow')
			{
				$trowclass = 'evenListRow';
                                $rowclass = 'oddListRow';
			}
			elseif($rowclass == 'oddListRow')
			{
				$trowclass = 'oddListRow';
                                $rowclass = 'evenListRow';
			}	
			$standCustFld .= '<tr class="'.$trowclass.'">';
		}

		
		$standCustFld .= '<td height="21" style="padding:0px 3px 0px 3px;">'.$entity_name.'</td>';
		$standCustFld .= '<td style="padding:0px 3px 0px 3px;"><div align="center">'.$tab_allow_per.'</div></td>';
		if ($i%2==0)
		{
			$standCustFld .= '</tr>';
		}
		$i++;
	}
	$standCustFld .='</table>';

	$standCustFld .= '<BR><BR>';
	$standCustFld .= '<table border="0" cellpadding="0" cellspacing="0" width="60%"><tr><td>';
	$standCustFld .=  get_form_header("Profile Standard Access Information", "", false );
	$standCustFld .= '</td></tr></table>';
	$standCustFld .= '<table border="0" cellpadding="0" cellspacing="0" class="FormBorder" width="60%">';
	$standCustFld .=  '<tr class="ModuleListTitle" height=20>';
	$standCustFld .=  '<td width="" class="moduleListTitle" style="padding:0px 3px 0px 3px;">Entity</td>';
	$standCustFld .=  '<td width="" class="moduleListTitle" style="padding:0px 3px 0px 3px;"><div align="center">Create/Edit</div></td>';
	$standCustFld .=  '<td width="" class="moduleListTitle" style="padding:0px 3px 0px 3px;"><div align="center">Delete</div></td>';
	$standCustFld .=  '<td width="" class="moduleListTitle" style="padding:0px 3px 0px 3px;"><div align="center">View</div></td>';
	$standCustFld .=  '</tr>';

	$i = 1;	
	foreach($act_perr_arry as $tabid=>$action_array)
	{
		$entity_name = getTabname($tabid);
		//Create/Edit Permission
		$tab_create_per_id = $action_array['1'];
		$tab_create_per = getDisplayValue($tab_create_per_id,$tabid,'1');
		//Delete Permission
		$tab_delete_per_id = $action_array['2'];
		$tab_delete_per = getDisplayValue($tab_delete_per_id,$tabid,'2');
		//View Permission
		$tab_view_per_id = $action_array['4'];
		$tab_view_per = getDisplayValue($tab_view_per_id,$tabid,'4');

		if ($i%2==0)
		{
			$trowclass = 'evenListRow';
		}
		else
		{	
			$trowclass = 'oddListRow';
		}

		$standCustFld .= '<tr class="'.$trowclass.'">';
		
		$standCustFld .= '<td height="21" style="padding:0px 3px 0px 3px;">'.$entity_name.'</td>';
		$standCustFld .= '<td style="padding:0px 3px 0px 3px;"><div align="center">'.$tab_create_per.'</div></td>';
		$standCustFld .= '<td style="padding:0px 3px 0px 3px;"><div align="center">'.$tab_delete_per.'</div></td>';
		$standCustFld .= '<td style="padding:0px 3px 0px 3px;"><div align="center">'.$tab_view_per.'</div></td>';
			
		$standCustFld .= '</tr>';
		$i++;
	}
	$standCustFld .='</table>';
	//echo $standCustFld;

	$standCustFld .= '<BR>';
	$standCustFld .= '<table border="0" cellpadding="0" cellspacing="0" width="60%"><tr><td>';
	$standCustFld .=  get_form_header("Profile Utility Access Information", "", false );
	$standCustFld .= '</td></tr></table>';
	$standCustFld .= '<table border="0" cellpadding="0" cellspacing="0" class="FormBorder" width="60%">';

	foreach($act_utility_arry as $tabid=>$action_array)
	{
		
		$entity_name = getTabname($tabid);
		$standCustFld .=  '<tr class="ModuleListTitle" height=20>';
		$standCustFld .=  '<td width="40%" class="moduleListTitle" height="21" style="padding:0px 3px 0px 3px;">'.$entity_name.'</td>';
		$standCustFld .=  '<td width="30%" class="moduleListTitle" style="padding:0px 3px 0px 3px;"><div align="center"></div></td>';
		$standCustFld .=  '<td width="30%" class="moduleListTitle" style="padding:0px 3px 0px 3px;"><div align="center"></div></td>';
		$standCustFld .=  '<td width="30%" class="moduleListTitle" style="padding:0px 3px 0px 3px;"><div align="center"></div></td>';
		$standCustFld .=  '</tr>';

		
		$i = 1;	
		$rowclass = '';
		
		$no_of_actions=sizeof($action_array);	
		foreach($action_array as $action_id=>$act_per)
		{
			

			if ($i%2==0)
			{
				$trowclass = 'evenListRow';
			}
			else
			{
				if($rowclass == '')
				{	
					$trowclass = 'oddListRow';
					$rowclass = 'evenListRow';	
				}
				elseif($rowclass == 'evenListRow')
				{
					$trowclass = 'evenListRow';
					$rowclass = 'oddListRow';
				}
				elseif($rowclass == 'oddListRow')
				{
					$trowclass = 'oddListRow';
					$rowclass = 'evenListRow';
				}	
				$standCustFld .= '<tr class="'.$trowclass.'">';
			 }
			//Import Permission
			$action_name = getActionName($action_id);
			$tab_util_act_per = $action_array[$action_id];
			$tab_util_per = getDisplayValue($tab_util_act_per,$tabid,$action_id);
		
		
			$standCustFld .= '<td height="21" style="padding:0px 3px 0px 3px;">'.$action_name.' </td>';
			$standCustFld .= '<td style="padding:0px 3px 0px 3px;"><div align="center">'.$tab_util_per.'</div></td>';

			if ($i%2==0)
			{
				$standCustFld .= '</tr>';
			}
			$i++;

		}
			
	}
	$standCustFld .= '</table>';
	$standCustFld .= '<BR><br>';
	$standCustFld .= '<table border="0" cellpadding="0" cellspacing="0" width="60%"><tr><td>';
	$standCustFld .=  get_form_header("Profile Field Access Information", "", false );
	$standCustFld .= '</td></tr></table>';	
	$standCustFld .= '<table border="0" cellspacing="2" cellpadding="2">';
        $standCustFld .= '<tr>'; 
        $standCustFld .= '<td><a href="index.php?module=Users&action=ListFieldPermissions&fld_module=Leads&profileid='.$profileid.'"><img src="'.$image_path.'/bullet.gif" align="absmiddle" border="0" hspace="5">'.$mod_strings["LBL_LEAD_FIELD_ACCESS"].'</a></td>';
        $standCustFld .= '<td width="30">&nbsp;</td>';
        $standCustFld .= '<td><a href="index.php?module=Users&action=ListFieldPermissions&fld_module=Accounts&profileid='.$profileid.'"><img src="'.$image_path.'/bullet.gif" align="absmiddle" border="0" hspace="5">'.$mod_strings["LBL_ACCOUNT_FIELD_ACCESS"].'</a></td>';
        $standCustFld .='</tr>';
        $standCustFld .='<tr>'; 
        $standCustFld .= '<td><a href="index.php?module=Users&action=ListFieldPermissions&fld_module=Contacts&profileid='.$profileid.'"><img src="'.$image_path.'/bullet.gif" align="absmiddle" border="0" hspace="5">'.$mod_strings["LBL_CONTACT_FIELD_ACCESS"].'</a></td>';
        $standCustFld .='<td>&nbsp;</td>';
        $standCustFld .='<td><a href="index.php?module=Users&action=ListFieldPermissions&fld_module=Potentials&profileid='.$profileid.'""><img src="'.$image_path.'/bullet.gif" align="absmiddle" border="0" hspace="5">'.$mod_strings["LBL_OPPORTUNITY_FIELD_ACCESS"].'</a></td></tr>';
        $standCustFld .='<tr>'; 
        $standCustFld .='<td><a href="index.php?module=Users&action=ListFieldPermissions&fld_module=HelpDesk&profileid='.$profileid.'""><img src="'.$image_path.'/bullet.gif" align="absmiddle" border="0" hspace="5">'.$mod_strings["LBL_HELPDESK_FIELD_ACCESS"].'</a></td>';
      	$standCustFld .='<td>&nbsp;</td>';
        $standCustFld .='<td><a href="index.php?module=Users&action=ListFieldPermissions&fld_module=Products&profileid='.$profileid.'""><img src="'.$image_path.'/bullet.gif" align="absmiddle" border="0" hspace="5">'.$mod_strings["LBL_PRODUCT_FIELD_ACCESS"].'</a></td>';
        $standCustFld .='</tr>';
        $standCustFld .='<tr>'; 
        $standCustFld .='<td><a href="index.php?module=Users&action=ListFieldPermissions&fld_module=Notes&profileid='.$profileid.'"><img src="'.$image_path.'/bullet.gif" align="absmiddle" border="0" hspace="5">'.$mod_strings["LBL_NOTE_FIELD_ACCESS"].'</a></td>';
        $standCustFld .='<td>&nbsp;</td>';
        $standCustFld .='<td><a href="index.php?module=Users&action=ListFieldPermissions&fld_module=Emails&profileid='.$profileid.'"><img src="'.$image_path.'/bullet.gif" align="absmiddle" border="0" hspace="5">'.$mod_strings["LBL_EMAIL_FIELD_ACCESS"].'</a></td>';
        $standCustFld .='</tr>';
        $standCustFld .='<tr>';
        $standCustFld .='<td><a href="index.php?module=Users&action=ListFieldPermissions&fld_module=Activities&profileid='.$profileid.'"><img src="'.$image_path.'/bullet.gif" align="absmiddle" border="0" hspace="5">'.$mod_strings["LBL_TASK_FIELD_ACCESS"].'</a></td>';
        $standCustFld .='<td>&nbsp;</td>';
        $standCustFld .='<td><a href="index.php?module=Users&action=ListFieldPermissions&fld_module=Events&profileid='.$profileid.'"><img src="'.$image_path.'/bullet.gif" align="absmiddle" border="0" hspace="5">'.$mod_strings["LBL_EVENT_FIELD_ACCESS"].'</a></td>';
        $standCustFld .='</tr>';
	$standCustFld .='<tr>';
        $standCustFld .= '<td><a href="index.php?module=Users&action=ListFieldPermissions&fld_module=Vendor&profileid='.$profileid.'"><img src="'.$image_path.'/bullet.gif" align="absmiddle" border="0" hspace="5">'.$mod_strings["LBL_VENDOR_FIELD_ACCESS"].'</a></td>';
        $standCustFld .='<td>&nbsp;</td>';
        $standCustFld .='<td><a href="index.php?module=Users&action=ListFieldPermissions&fld_module=PriceBook&profileid='.$profileid.'"><img src="'.$image_path.'/bullet.gif" align="absmiddle" border="0" hspace="5">'.$mod_strings["LBL_PB_FIELD_ACCESS"].'</a></td>';
        $standCustFld .='</tr>';
	$standCustFld .='<tr>';
        $standCustFld .='<td><a href="index.php?module=Users&action=ListFieldPermissions&fld_module=Quotes&profileid='.$profileid.'"><img src="'.$image_path.'/bullet.gif" align="absmiddle" border="0" hspace="5">'.$mod_strings["LBL_QUOTE_FIELD_ACCESS"].'</a></td>';
        $standCustFld .='<td>&nbsp;</td>';
        $standCustFld .='<td><a href="index.php?module=Users&action=ListFieldPermissions&fld_module=Orders&profileid='.$profileid.'"><img src="'.$image_path.'/bullet.gif" align="absmiddle" border="0" hspace="5">'.$mod_strings["LBL_PO_FIELD_ACCESS"].'</a></td>';
        $standCustFld .='</tr>';
	$standCustFld .='<tr>';
        $standCustFld .='<td><a href="index.php?module=Users&action=ListFieldPermissions&fld_module=SalesOrder&profileid='.$profileid.'"><img src="'.$image_path.'/bullet.gif" align="absmiddle" border="0" hspace="5">'.$mod_strings["LBL_SO_FIELD_ACCESS"].'</a></td>';
       $standCustFld .='<td>&nbsp;</td>';
       $standCustFld .='<td><a href="index.php?module=Users&action=ListFieldPermissions&fld_module=Invoice&profileid='.$profileid.'"><img src="'.$image_path.'/bullet.gif" align="absmiddle" border="0" hspace="5">'.$mod_strings["LBL_INVOICE_FIELD_ACCESS"].'</a></td>';
      $standCustFld .='</tr>';	
      $standCustFld .='</table>';
      
	
	return $standCustFld;
}
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("STANDARDPERMISSIONS", $standCustFld);


$xtpl->parse("main");
$xtpl->out("main");

function getDisplayValue($id)
{
	global $image_path;
	
	if($id == '')
	{
		$value = '&nbsp;';
	}
	elseif($id == 0)
	{
		$value = '<img src="'.$image_path.'yes.gif">';
	}
	elseif($id == 1)
	{
		$value = '<img src="'.$image_path.'no.gif">';
	}
	else
	{
		$value = '&nbsp;';
	}
	return $value;
		
}
function getGlobalDisplayValue($id,$actionid)
{
	global $image_path;
	if($id == '')
	{
		$value = '&nbsp;';
	}
	elseif($id == 0)
	{
		$value = '<img src="'.$image_path.'yes.gif">';
	}
	elseif($id == 1)
	{
		$value = '<img src="'.$image_path.'no.gif">';
	}
	else
	{
		$value = '&nbsp;';
	}
	
	return $value;
		
}
?>
