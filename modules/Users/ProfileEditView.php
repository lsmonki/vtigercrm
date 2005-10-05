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
echo get_module_title("Users", 'Profile Information: '.$profilename, true);

global $adb;
global $theme;
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
	global $app_strings;
	$standCustFld= '';
	$standCustFld .= '<input type="hidden" name="module" value="Users">';
	$standCustFld .= '<input type="hidden" name="profileid" value="'.$profileid.'">';
	$standCustFld .= '<input type="hidden" name="action" value="UpdateProfile">';
	$standCustFld .= '<BR><BR>';
	$standCustFld .= '<table border="0" cellpadding="0" cellspacing="0" width="60%"><tr><td>';
	$standCustFld .=  get_form_header("Profile Global Access Information", "", false );
	$standCustFld .= '</td></tr></table>';
	$standCustFld .= '<table border="0" cellpadding="0" cellspacing="0" class="FormBorder" width="60%">';
	$standCustFld .=  '<tr class="ModuleListTitle" height=20>';
	$standCustFld .=  '<td width="" class="moduleListTitle" style="padding:0px 3px 0px 3px;">View All</td>';
	$view_all_per = $global_per_arry[1];
	$standCustFld .=  '<td width="" class="moduleListTitle" style="padding:0px 3px 0px 3px;"><div align="center">'.getGlobalDisplayOutput($view_all_per,1).'</div></td>';
	$standCustFld .=  '<td width="" class="moduleListTitle" style="padding:0px 3px 0px 3px;"><div align="center">Edit All</div></td>';
	$edit_all_per = $global_per_arry[2];
	$standCustFld .=  '<td width="" class="moduleListTitle" style="padding:0px 3px 0px 3px;"><div align="center">'.getGlobalDisplayOutput($edit_all_per,2).'</div></td>';
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
		$tab_allow_per = getDisplayOutput($tab_allow_per_id,$tabid,'');
	
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
		$tab_create_per = getDisplayOutput($tab_create_per_id,$tabid,'1');
		//Delete Permission
		$tab_delete_per_id = $action_array['2'];
		$tab_delete_per = getDisplayOutput($tab_delete_per_id,$tabid,'2');
		//View Permission
		$tab_view_per_id = $action_array['4'];
		$tab_view_per = getDisplayOutput($tab_view_per_id,$tabid,'4');

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
			$tab_util_per = getDisplayOutput($tab_util_act_per,$tabid,$action_id);
		
		
			$standCustFld .= '<td height="21" style="padding:0px 3px 0px 3px;">'.$action_name.' </td>';
			$standCustFld .= '<td style="padding:0px 3px 0px 3px;"><div align="center">'.$tab_util_per.'</div></td>';

			if ($i%2==0)
			{
				$standCustFld .= '</tr>';
			}
			$i++;

		}
			
	}
	$standCustFld .='</table>';
	$standCustFld .= '<br><div align="center" style="width:60%"><input title="Edit" accessKey="C" class="button" type="submit" name="Save" value="Save">&nbsp;&nbsp; <input title="'.$app_strings["LBL_CANCEL_BUTTON_TITLE"].'" accessKey="'.$app_strings["LBL_CANCEL_BUTTON_KEY"].'" class="button" onclick="window.history.back()" type="button" name="button" value="  '.$app_strings["LBL_CANCEL_BUTTON_LABEL"].'  ">&nbsp;&nbsp;</div>';
	$standCustFld .='</form>';		
	return $standCustFld;
}
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("STANDARDPERMISSIONS", $standCustFld);


$xtpl->parse("main");
$xtpl->out("main");

function getDisplayOutput($id,$tabid,$actionid)
{
	if($actionid == '')
	{
		$name = $tabid.'_tab';
	}
	else
	{
		$temp_name = getActionname($actionid);
		$name = $tabid.'_'.$temp_name;
	}



	if($id == '')
	{
		$value = '';
	}
	elseif($id == 0)
	{
		$value = '<input type="checkbox" name="'.$name.'" checked>'; 
	}
	elseif($id == 1)
	{
		$value = '<input type="checkbox" name="'.$name.'">';
	}
	return $value;
		
}

function getGlobalDisplayOutput($id,$actionid)
{
	if($actionid == '1')
	{
		$name = 'view_all';
	}
	elseif($actionid == '2')
	{
		
		$name = 'edit_all';
	}

	if($id == '')
        {
                $value = '';
        }
	elseif($id == 0)
	{
		$value = '<input type="checkbox" name="'.$name.'" checked>'; 
	}
	elseif($id == 1)
	{
		$value = '<input type="checkbox" name="'.$name.'">';
	}
	return $value;
		
}

?>
