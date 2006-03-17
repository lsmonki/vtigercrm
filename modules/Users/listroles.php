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
require_once('include/utils/UserInfoUtil.php');

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

//Retreiving the hierarchy
$hquery = "select * from role order by parentrole asc";
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
$query = "select * from role";
$result = $adb->query($query);
$num_rows=$adb->num_rows($result);



$roleout='';
indent($hrarray,$roleout,$role_det);
function indent($hrarray,$roleout,$role_det)
{
	global $theme;
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";

	foreach($hrarray as $roleid => $value)
	{
		//retreiving the role details
		$role_det_arr=$role_det[$roleid];
		$roleid_arr=$role_det_arr[2];
		$rolename = $role_det_arr[0];
		$roledepth = $role_det_arr[1]; 
		echo '<ul class="uil" id="'.$roleid.'" style="display:block">';
		echo '<li>';
		//echo '<li><a href="#" onClick="showhide(\''.$roleid_arr.'\')">'.$rolename.'</a>';
		//echo '<table onMouseOver="showx(\''.$roleid.'tools\')" onMouseOut="hidex(\''.$roleid.'tools\')" onMouseDown="startDrag(\''.$roleid.'\',\''.$rolename.'\')" border=0 cellspacing=0 cellpadding=2 class=small >';
		
		echo '<table  border=0 cellspacing=0 cellpadding=2 class=small >';
		echo '<tr style="height:20px">';
  		echo '<td style="width:20px"><a href=\'#\' onClick="showhide(\''.$roleid_arr.'\')"><img src="'.$image_path.'/plus.gif" border="0" width="16" height="16" alt="Expand/Collapse" title="Expand/Collapse"></a></td>';
		echo '<td id="li_'.$roleid.'" valign=top onMouseDown="startDrag(\'li_'.$roleid.'\',\''.$rolename.'\')" onMouseMove="doItemMove(event)" onMouseUp="endItemMove(event)"><a href="#" style="width:100%" onClick="showhide(\''.$roleid_arr.'\')" ><b>'.$rolename.'</b></a></td>';
		echo '<td style="width:5px"></td>';
		if(! $roledepth == 0)
		{
			echo '<td valign=top><span id="'.$roleid.'tools" style="display:block"> <a href="index.php?module=Users&action=createrole&parent='.$roleid.'">Add</a> | <a href="index.php?module=Users&action=createrole&roleid='.$roleid.'&mode=edit">Edit</a> | <a href="#" onclick="DeleteRole(\''.$roleid.'\')">Delete</a> | <a href="index.php?module=Users&action=RoleDetailView&roleid='.$roleid.'">View</a></span></td>';
		}
		else
		{
			
			echo '<td valign=top><span id="'.$roleid.'tools" style="display:block"> <a href="index.php?module=Users&action=createrole&parent='.$roleid.'">Add</a></span></td>';
		}
                  
		echo '</tr>';
		echo '</table>';
		echo '</li>';
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
