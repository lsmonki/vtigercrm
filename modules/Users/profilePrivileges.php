<?php
require_once('include/utils/UserInfoUtil.php');
require_once('include/utils/utils.php');

global $app_strings;
global $mod_strings;
global $current_user;
global $adb;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
$profileId=$_REQUEST['profileid'];
$profileName='';
$parentProfileId=$_REQUEST['parentprofile'];
if($_REQUEST['mode'] =='create' && $_REQUEST['radiobutton'] != 'baseprofile')
	$parentProfileId = '';



$smarty = new vtigerCRM_Smarty;
$secondaryModule='';
$mode='';
$output ='';
$output1 ='';
$smarty->assign("PROFILEID", $profileId);
$smarty->assign("MOD", return_module_language($current_language,'Settings'));
$smarty->assign("APP", $app_strings);
$smarty->assign("CMOD", $mod_strings);
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != '')
	$smarty->assign("RETURN_ACTION", $_REQUEST['return_action']);


if(isset($_REQUEST['profile_name']) && $_REQUEST['profile_name'] != '' && $_REQUEST['mode'] == 'create')
{	
	$profileName=$_REQUEST['profile_name'];
}
else
{
	$profileName=getProfileName($profileId);
}

$smarty->assign("PROFILE_NAME", $profileName);

if(isset($_REQUEST['profile_description']) && $_REQUEST['profile_description'] != '' && $_REQUEST['mode'] == 'create')
	$smarty->assign("PROFILE_DESCRIPTION",$_REQUEST['profile_description']);
if(isset($_REQUEST['mode']) && $_REQUEST['mode'] != '')
	$smarty->assign("MODE",$_REQUEST['mode']);




//Initially setting the secondary selected tab
$mode=$_REQUEST['mode'];
if($mode == 'create')
{
	$smarty->assign("ACTION",'SaveProfile');
}
elseif($mode == 'edit')
{
	$smarty->assign("ACTION",'UpdateProfileChanges');
}


//Global Privileges

if($mode == 'view')
{
	$global_per_arry = getProfileGlobalPermission($profileId);
	$view_all_per = $global_per_arry[1];
	$edit_all_per = $global_per_arry[2];
	$privileges_global[]=getGlobalDisplayValue($view_all_per,1);
	$privileges_global[]=getGlobalDisplayValue($edit_all_per,2); 
}
elseif($mode == 'edit')
{
	$global_per_arry = getProfileGlobalPermission($profileId);
	$view_all_per = $global_per_arry[1];
	$edit_all_per = $global_per_arry[2];
	$privileges_global[]=getGlobalDisplayOutput($view_all_per,1);
	$privileges_global[]=getGlobalDisplayOutput($edit_all_per,2);
}
elseif($mode == 'create')
{
	if($parentProfileId != '')
	{
		$global_per_arry = getProfileGlobalPermission($parentProfileId);
		$view_all_per = $global_per_arry[1];
		$edit_all_per = $global_per_arry[2];
		$privileges_global[]=getGlobalDisplayOutput($view_all_per,1);
		$privileges_global[]=getGlobalDisplayOutput($edit_all_per,2);
	}
	else
	{
		$privileges_global[]=getGlobalDisplayOutput(0,1);
		$privileges_global[]=getGlobalDisplayOutput(0,2);
	}

}

$smarty->assign("GLOBAL_PRIV",$privileges_global);			

//standard privileges	
if($mode == 'view')
{
	$act_perr_arry = getTabsActionPermission($profileId);	
	foreach($act_perr_arry as $tabid=>$action_array)
	{
		$stand = array();
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

		$stand[]=$entity_name;
		$stand[]=$tab_create_per;
		$stand[]=$tab_delete_per;
		$stand[]=$tab_view_per;
		$privileges_stand[]=$stand;
	}
}
if($mode == 'edit')
{
	$act_perr_arry = getTabsActionPermission($profileId);	
	foreach($act_perr_arry as $tabid=>$action_array)
	{
		$stand = array();
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

		$stand[]=$entity_name;
		$stand[]=$tab_create_per;
		$stand[]=$tab_delete_per;
		$stand[]=$tab_view_per;
		$privileges_stand[]=$stand;
	}
}
if($mode == 'create')
{
	if($parentProfileId != '')
	{
		$act_perr_arry = getTabsActionPermission($parentProfileId);
		foreach($act_perr_arry as $tabid=>$action_array)
		{
			$stand = array();
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

			$stand[]=$entity_name;
			$stand[]=$tab_create_per;
			$stand[]=$tab_delete_per;
			$stand[]=$tab_view_per;
			$privileges_stand[]=$stand;
		}	
	}
	else
	{
		$act_perr_arry = getTabsActionPermission(1);	
		foreach($act_perr_arry as $tabid=>$action_array)
		{
			$stand = array();
			$entity_name = getTabname($tabid);
			//Create/Edit Permission
			$tab_create_per_id = $action_array['1'];
			$tab_create_per = getDisplayOutput(0,$tabid,'1');
			//Delete Permission
			$tab_delete_per_id = $action_array['2'];
			$tab_delete_per = getDisplayOutput(0,$tabid,'2');
			//View Permission
			$tab_view_per_id = $action_array['4'];
			$tab_view_per = getDisplayOutput(0,$tabid,'4');

			$stand[]=$entity_name;
			$stand[]=$tab_create_per;
			$stand[]=$tab_delete_per;
			$stand[]=$tab_view_per;
			$privileges_stand[]=$stand;
		}
	}

}
$smarty->assign("STANDARD_PRIV",$privileges_stand);			

//tab Privileges

if($mode == 'view')
{
	$tab_perr_array = getTabsPermission($profileId);
	$no_of_tabs =  sizeof($tab_perr_array);
	foreach($tab_perr_array as $tabid=>$tab_perr)
	{
		$tab=array();
		$entity_name = getTabname($tabid);
		$tab_allow_per_id = $tab_perr_array[$tabid];
		$tab_allow_per = getDisplayValue($tab_allow_per_id,$tabid,'');	
		$tab[]=$entity_name;
		$tab[]=$tab_allow_per;
		$privileges_tab[]=$tab;
	}
}
if($mode == 'edit')
{
	$tab_perr_array = getTabsPermission($profileId);
	$no_of_tabs =  sizeof($tab_perr_array);
	foreach($tab_perr_array as $tabid=>$tab_perr)
	{
		$tab=array();
		$entity_name = getTabname($tabid);
		$tab_allow_per_id = $tab_perr_array[$tabid];
		$tab_allow_per = getDisplayOutput($tab_allow_per_id,$tabid,'');	
		$tab[]=$entity_name;
		$tab[]=$tab_allow_per;
		$privileges_tab[]=$tab;
	}
}
if($mode == 'create')
{
	if($parentProfileId != '')
	{
		$tab_perr_array = getTabsPermission($parentProfileId);
		$no_of_tabs =  sizeof($tab_perr_array);
		foreach($tab_perr_array as $tabid=>$tab_perr)
		{
			$tab=array();
			$entity_name = getTabname($tabid);
			$tab_allow_per_id = $tab_perr_array[$tabid];
			$tab_allow_per = getDisplayOutput($tab_allow_per_id,$tabid,'');	
			$tab[]=$entity_name;
			$tab[]=$tab_allow_per;
			$privileges_tab[]=$tab;
		}
	}
	else
	{
		$tab_perr_array = getTabsPermission(1);
		$no_of_tabs =  sizeof($tab_perr_array);
		foreach($tab_perr_array as $tabid=>$tab_perr)
		{
			$tab=array();
			$entity_name = getTabname($tabid);
			$tab_allow_per_id = $tab_perr_array[$tabid];
			$tab_allow_per = getDisplayOutput(0,$tabid,'');	
			$tab[]=$entity_name;
			$tab[]=$tab_allow_per;
			$privileges_tab[]=$tab;
		}
	}

}
$privileges_tab = array_chunk($privileges_tab, 2);
$smarty->assign("TAB_PRIV",$privileges_tab);			

//utilities privileges

if($mode == 'view')
{
	$act_utility_arry = getTabsUtilityActionPermission($profileId);
	foreach($act_utility_arry as $tabid=>$action_array)
	{
		$util=array();
		$entity_name = getTabname($tabid);
		$no_of_actions=sizeof($action_array);
		foreach($action_array as $action_id=>$act_per)
		{
			$action_name = getActionName($action_id);
			$tab_util_act_per = $action_array[$action_id];
			$tab_util_per = getDisplayValue($tab_util_act_per,$tabid,$action_id);
			$util[]=$action_name;
			$util[]=$tab_util_per;
		}
		$util=array_chunk($util,2);
		$util=array_chunk($util,2);
		$privilege_util[$entity_name] = $util;
	}
}
elseif($mode == 'edit')
{
	$act_utility_arry = getTabsUtilityActionPermission($profileId);
	foreach($act_utility_arry as $tabid=>$action_array)
	{
		$util=array();
		$entity_name = getTabname($tabid);
		$no_of_actions=sizeof($action_array);
		foreach($action_array as $action_id=>$act_per)
		{
			$action_name = getActionName($action_id);
			$tab_util_act_per = $action_array[$action_id];
			$tab_util_per = getDisplayOutput($tab_util_act_per,$tabid,$action_id);
			$util[]=$action_name;
			$util[]=$tab_util_per;
		}
		$util=array_chunk($util,2);
		$util=array_chunk($util,2);
		$privilege_util[$entity_name] = $util;
	}
}
elseif($mode == 'create')
{
	if($parentProfileId != '')
	{
		$act_utility_arry = getTabsUtilityActionPermission($parentProfileId);
		foreach($act_utility_arry as $tabid=>$action_array)
		{
			$util=array();
			$entity_name = getTabname($tabid);
			$no_of_actions=sizeof($action_array);
			foreach($action_array as $action_id=>$act_per)
			{
				$action_name = getActionName($action_id);
				$tab_util_act_per = $action_array[$action_id];
				$tab_util_per = getDisplayOutput($tab_util_act_per,$tabid,$action_id);
				$util[]=$action_name;
				$util[]=$tab_util_per;
			}
			$util=array_chunk($util,2);
			$util=array_chunk($util,2);
			$privilege_util[$entity_name] = $util;
		}
	}
	else
	{
		$act_utility_arry = getTabsUtilityActionPermission(1);
		foreach($act_utility_arry as $tabid=>$action_array)
		{
			$util=array();
			$entity_name = getTabname($tabid);
			$no_of_actions=sizeof($action_array);
			foreach($action_array as $action_id=>$act_per)
			{
				$action_name = getActionName($action_id);
				$tab_util_act_per = $action_array[$action_id];
				$tab_util_per = getDisplayOutput(0,$tabid,$action_id);
				$util[]=$action_name;
				$util[]=$tab_util_per;
			}
			$util=array_chunk($util,2);
			$util=array_chunk($util,2);
			$privilege_util[$entity_name] = $util;
		}

	}

}
$smarty->assign("UTILITIES_PRIV",$privilege_util);		

//Field privileges		
$modArr=getFieldModuleAccessArray();

 
$no_of_mod=sizeof($modArr);
for($i=0;$i<$no_of_mod; $i++)
{
	$fldModule=key($modArr);
	$lang_str=$modArr[$fldModule];	
	$privilege_fld[]=$fldModule;
	next($modArr);
}
$smarty->assign("PRI_FIELD_LIST",$privilege_fld);	
$smarty->assign("MODE",$mode);
if($mode=='view')
{
	$fieldListResult = getProfile2AllFieldList($modArr,$profileId);
	for($i=0; $i<count($fieldListResult);$i++)
	{
		$field_module=array();
		$module_name=key($fieldListResult);
		for($j=0; $j<count($fieldListResult[$module_name]); $j++)
		{
			$field=array();
			if($fieldListResult[$module_name][$j][1] == 0)
			{
				$visible = "<img src=".$image_path."/yes.gif>";
			}
			else
			{
				$visible = "<img src=".$image_path."/no.gif>";
			}
			$field[]=$fieldListResult[$module_name][$j][0];
			$field[]=$visible;
			$field_module[]=$field;
		}
		$privilege_field[$module_name] = array_chunk($field_module,2);
		next($fieldListResult);
	}
}
elseif($mode=='edit')
{
	$fieldListResult = getProfile2AllFieldList($modArr,$profileId);
	for($i=0; $i<count($fieldListResult);$i++)
	{
		$field_module=array();
		$module_name=key($fieldListResult);
		for($j=0; $j<count($fieldListResult[$module_name]); $j++)
		{
			$fldLabel= $fieldListResult[$module_name][$j][0];
			$uitype = $fieldListResult[$module_name][$j][2];
			$mandatory = '';
			$readonly = '';
			$field=array();

			if($uitype == 2 || $uitype == 51 || $uitype == 6 || $uitype == 22 || $uitype == 73 || $uitype == 24 || $uitype == 81 || $uitype == 50 || $uitype == 23 || $uitype == 16)
			{
				$mandatory = '<font color="red">*</font>';
				$readonly = 'disabled';
			}	
			if($fieldListResult[$module_name][$j][3] == 0)
			{
				$visible = "checked";
			}
			else
			{
				$visible = "";
			}
			$field[]=$mandatory.' '.$fldLabel;
			$field[]='<input type="checkbox" name="'.$fieldListResult[$module_name][$j][4].'" '.$visible.' '.$readonly.'>';
			$field_module[]=$field;
		}
		$privilege_field[$module_name] = array_chunk($field_module,2);
		next($fieldListResult);
	}
}
elseif($mode=='create')
{
	if($parentProfileId != '')
	{
		$fieldListResult = getProfile2AllFieldList($modArr,$parentProfileId);
		for($i=0; $i<count($fieldListResult);$i++)
		{
			$field_module=array();
			$module_name=key($fieldListResult);
			for($j=0; $j<count($fieldListResult[$module_name]); $j++)
			{
				$fldLabel= $fieldListResult[$module_name][$j][0];
				$uitype = $fieldListResult[$module_name][$j][2];
				$mandatory = '';
				$readonly = '';
				$field=array();

				if($uitype == 2 || $uitype == 51 || $uitype == 6 || $uitype == 22 || $uitype == 73 || $uitype == 24 || $uitype == 81 || $uitype == 50 || $uitype == 23 || $uitype == 16)
				{
					$mandatory = '<font color="red">*</font>';
					$readonly = 'disabled';
				}	
				if($fieldListResult[$module_name][$j][3] == 0)
				{
					$visible = "checked";
				}
				else
				{
					$visible = "";
				}
				$field[]=$mandatory.' '.$fldLabel;
				$field[]='<input type="checkbox" name="'.$fieldListResult[$module_name][$j][4].'" '.$visible.' '.$readonly.'>';
				$field_module[]=$field;
			}
			$privilege_field[$module_name] = array_chunk($field_module,2);
			next($fieldListResult);
		}
	}
	else
	{
		$fieldListResult = getProfile2AllFieldList($modArr,1);
		for($i=0; $i<count($fieldListResult);$i++)
		{
			$field_module=array();
			$module_name=key($fieldListResult);
			for($j=0; $j<count($fieldListResult[$module_name]); $j++)
			{
				$fldLabel= $fieldListResult[$module_name][$j][0];
				$uitype = $fieldListResult[$module_name][$j][2];
				$mandatory = '';
				$readonly = '';
				$field=array();

				if($uitype == 2 || $uitype == 51 || $uitype == 6 || $uitype == 22 || $uitype == 73 || $uitype == 24 || $uitype == 81 || $uitype == 50 || $uitype == 23 || $uitype == 16)
				{
					$mandatory = '<font color="red">*</font>';
					$readonly = 'disabled';
				}	
				$visible = "checked";
				$field[]=$mandatory.' '.$fldLabel;
				$field[]='<input type="checkbox" name="'.$fieldListResult[$module_name][$j][4].'" '.$visible.' '.$readonly.'>';
				$field_module[]=$field;
			}
			$privilege_field[$module_name] = array_chunk($field_module,2);
			next($fieldListResult);
		}	
	}
}

$smarty->assign("FIELD_PRIVILEGES",$privilege_field);	
$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);
if($mode == 'view')
	$smarty->display("ProfileDetailView.tpl");
else
	$smarty->display("EditProfile.tpl");

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

	if($id == '' && $id != 0)
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



	if($id == '' && $id != 0)
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
