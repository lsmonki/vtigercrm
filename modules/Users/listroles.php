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

global $mod_strings;
global $app_strings;
global $app_list_strings;

echo '<form action="index.php" method="post" name="new" id="form">';
echo get_module_title("Users",'Roles', true);

global $adb;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');


$xtpl=new XTemplate ('modules/Users/listroles.html');

$sql = "select * from role";
$roleListResult = $adb->query($sql);
$noofrows = $adb->num_rows($roleListResult);

$standCustFld = getStdOutput($roleListResult, $noofrows, $mod_strings);

//Standard PickList Fields
function getStdOutput($roleListResult, $noofrows, $mod_strings)
{
	global $adb;
	global $app_strings;
	//echo get_form_header("Profiles", "", false );
	$standCustFld= '';
	$standCustFld .= '<input type="hidden" name="module" value="Users">';
	$standCustFld .= '<input type="hidden" name="action" value="createrole">';
	$standCustFld .= '<br><input title="New" accessKey="C" class="button" type="submit" name="New" value="'.$mod_strings['LBL_TITLE_ROLE_NAME'].'">';
	$standCustFld .= '<br><BR>'; 
	$standCustFld .= '<table border="0" cellpadding="5" cellspacing="1" class="FormBorder" width="30%">';
	$standCustFld .=  '<tr>';
	$standCustFld .=   '<td class="ModuleListTitle" width="18%" height="21" style="padding:0px 3px 0px 3px;"><div><b>Operation</b></div></td>';
	$standCustFld .=   '<td class="ModuleListTitle" height="21" style="padding:0px 3px 0px 3px;"><b>'.$mod_strings['LBL_ROLE_NAME'].'</b></td>';
	$standCustFld .=  '</tr>';
	
	$row=1;
	for($i=0; $i<$noofrows; $i++,$row++)
	{
		if ($row%2==0)
		{
			$trowclass = 'evenListRow';
		}
		else
		{	
			$trowclass = 'oddListRow';
		}

		$standCustFld .= '<tr class="'.$trowclass.'">';
		$role_name = $adb->query_result($roleListResult,$i,"name");
		$role_id = $adb->query_result($roleListResult,$i,"roleid");
		$standCustFld .= '<td width="18%" height="21" style="padding:0px 3px 0px 3px;"><div>';
		$standCustFld .= '<a href="index.php?module=Users&action=createrole&mode=edit&roleid='.$role_id.'">'.$app_strings['LNK_EDIT'].'</a>';
		global $current_user;
        	$current_role = fetchUserRole($current_user->id);
        	if($role_id != 1 && $role_id != 2 && $role_id != $current_role)
        	{
		$standCustFld .=' | <a href="index.php?module=Users&action=RoleDeleteStep1&roleid='.$role_id.'">'.$app_strings['LNK_DELETE'].'</a>';
		}
		$standCustFld .= '</div></td>';	
		$standCustFld .= '<td height="21" style="padding:0px 3px 0px 3px;"><a href="index.php?module=Users&action=RoleDetailView&roleid='.$role_id.'">'.$role_name.'</a></td></tr>';
		
	}
	$standCustFld .='</table>';
	//echo $standCustFld;	
	return $standCustFld;
}
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("ROLES", $standCustFld);

//Retreiving the hierarchy
$hquery = "select * from test_role order by parentrole asc";
$hr_res = $adb->query($hquery);
$num_rows = $adb->num_rows($hr_res);
$hrarray= Array();

for($l=0; $l<$num_rows; $l++)
{
	$roleid = $adb->query_result($hr_res,$l,'roleid');
	$parent = $adb->query_result($hr_res,$l,'parentrole');
	$temp_list = explode('::',$parent);
	$size = sizeof($temp_list);
	$i=0;
	$k= Array();
	$y=$hrarray;
	if(sizeof($hrarray) == 0)
	{
		$hrarray[$temp_list[0]]= Array();
	}
	else
	{
		while($i<$size-1)
		{
			$y=$y[$temp_list[$i]];
			$k[$temp_list[$i]] = $y;
			$i++;

		}
		//echo '<BR> Now the last array is';
		//print_r($y);
		//echo '<BR>';
		$y[$roleid] = Array();
		//print_r($y);
		//echo '<BR>';
		$k[$roleid] = Array();
		//print_r($k);

		//Reversing the Array
		$rev_temp_list=array_reverse($temp_list);
		$j=0;
		//Now adding this into the main array
		foreach($rev_temp_list as $value)
		{
			if($j == $size-1)
			{
				$hrarray[$value]=$k[$value];
			}
			else
			{
				$k[$rev_temp_list[$j+1]][$value]=$k[$value];
				//print_r($k);
			}
			$j++;
		}
	}


	//echo '<BR> Final Array is <BR>';
	//print_r($hrarray);
	//echo '<BR>';

}
//print_r($hrarray);
//Constructing the Roledetails array
$role_det = getAllRoleDetails();
$query = "select * from temp_role";
$result = $adb->query($query);
$num_rows=$adb->num_rows($result);



$roleout='';
indent($hrarray,$roleout,$role_det);
function indent($hrarray,$roleout,$role_det)
{
	foreach($hrarray as $roleid => $value)
	{
		//retreiving the role details
		$role_det_arr=$role_det[$roleid];
		$roleid_arr=$role_det_arr[2];
		$rolename = $role_det_arr[0]; 
		echo '<ul class="small" id="'.$roleid.'" style="display:block">';
		echo '<li>';
		//echo '<li><a href="#" onClick="showhide(\''.$roleid_arr.'\')">'.$rolename.'</a>';
		echo '<table onMouseOver="showx(\''.$roleid.'tools\')" onMouseOut="hidex(\''.$roleid.'tools\')" border=0 cellspacing=0 cellpadding=2 class=small >';
		echo '<tr style="height:20px">';

		echo '<td valign=top><a href="#" style="width:100%" onClick="showhide(\''.$roleid_arr.'\')" >'.$rolename.'</a></td>';
		echo '<td style="width:5px"></td>';
		echo '<td valign=top><span id="'.$roleid.'tools" style="display:none"> <a href="createnewrole.php">Add</a> | <a href="editrole.php">Edit</a> | Delete </span></td>';
		echo '</tr>';
		echo '</table>';
		if(sizeof($value) > 0 )
		{
			indent($value,$roleout,$role_det);
		}

		echo '</ul>';
	}
	

}
$xtpl->assign("HR", $roleout);
$xtpl->parse("main");
$xtpl->out("main");

?>
