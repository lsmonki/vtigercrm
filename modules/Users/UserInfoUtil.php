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
include('config.php');

if(isset($_REQUEST['groupname']))
{
  $groupname = $_REQUEST['groupname'];
  $sql= "select user_name from users2group inner join users on users.id= users2group.userid where groupname='" .$_REQUEST['groupname'] ."'";
  $result = $adb->query($sql);
  $groupnameList = "";
  
  while($groupList=$adb->fetch_array($result))
  {
    $groupnameList = $groupnameList .$groupList['user_name'] .",";
  }
  //echo 'final group list is ' .$groupnameList;
  
  header("Location: index.php?module=Users&action=listgroupmembers&groupname=$groupname&groupmembers=$groupnameList");
}

function fetchUserRole($userid)
{
	global $adb;
	//$sql= "select rolename from user2role where userid='" .$userid ."'";
	$sql = "select roleid from user2role where userid='" .$userid ."'";
        $result = $adb->query($sql);
	$roleid=  $adb->query_result($result,0,"roleid");
	return $roleid;
}

function fetchUserProfileId($userid)
{
	global $adb;
	$sql = "select roleid from user2role where userid=" .$userid;
        $result = $adb->query($sql);
	$roleid=  $adb->query_result($result,0,"roleid");


	$sql1 = "select profileid from role2profile where roleid=" .$roleid;
        $result1 = $adb->query($sql1);
	$profileid=  $adb->query_result($result1,0,"profileid");
	return $profileid;
}

function fetchUserGroups($userid)
{
	global $adb;
	$sql= "select groupname from users2group where userid='" .$userid ."'";
        //echo $sql;
        $result = $adb->query($sql);
        //store the groupnames in a comma separated string
        //echo 'count is ' .count($result);
	if($adb->num_rows($result)!=0)	$groupname=  $adb->query_result($result,0,"groupname");
	return $groupname;
}

function getAllTabsPermission($profileid)
{
	global $adb;
	$sql = "select * from profile2tab where profileid=" .$profileid ;
	$result = $adb->query($sql);
	$tab_perr_array = Array();
	$num_rows = $adb->num_rows($result);
	for($i=0; $i<$num_rows; $i++)
	{
		$tabid= $adb->query_result($result,$i,'tabid');
		$tab_per= $adb->query_result($result,$i,'permissions');
		$tab_perr_array[$tabid] = $tab_per;
	}		
	return $tab_perr_array; 

}

function getTabsPermission($profileid)
{
	global $adb;
	$sql = "select * from profile2tab where profileid=" .$profileid ;
	$result = $adb->query($sql);
	$tab_perr_array = Array();
	$num_rows = $adb->num_rows($result);
	for($i=0; $i<$num_rows; $i++)
	{
		$tabid= $adb->query_result($result,$i,'tabid');
		$tab_per= $adb->query_result($result,$i,'permissions');
		if($tabid != 1 && $tabid != 3 && $tabid != 16 && $tab_id != 15 && $tab_id != 17)
		{
			$tab_perr_array[$tabid] = $tab_per;
		}
	}		
	return $tab_perr_array; 

}

function getTabsActionPermission($profileid)
{
	global $adb;
	$check = Array(); 	
	$sql1 = "select tabid from profile2tab where profileid=" .$profileid;
	$result1 = $adb->query($sql1);
	$num_rows1 = $adb->num_rows($result1);
	for($i=0; $i<$num_rows1; $i++)
	{
		$access = Array();
		$tab_id = $adb->query_result($result1,$i,'tabid');

		if($tab_id != 1 && $tab_id != 3 && $tab_id != 15 && $tab_id !=16  && $tab_id != 17)
		{
			//Inserting the Standard Actions into the Array	
			$sql= "select * from profile2standardpermissions where profileid =".$profileid." and tabid=".$tab_id;
			$result = $adb->query($sql);
			$num_rows = $adb->num_rows($result);
			for($j=0; $j<$num_rows; $j++)
			{
				$action_id = $adb->query_result($result,$j,'operation');
				$per_id = $adb->query_result($result,$j,'permissions');
				$access[$action_id] = $per_id;
			}

			//Inserting into the global Array
			$check[$tab_id] = $access;
		}

	}			

	return $check;
}

function getTabsUtilityActionPermission($profileid)
{
	global $adb;
	$check = Array(); 	
	$sql1 = "select tabid from profile2tab where profileid=" .$profileid;
	$result1 = $adb->query($sql1);
	$num_rows1 = $adb->num_rows($result1);
	for($i=0; $i<$num_rows1; $i++)
	{
		$access = Array();
		$tab_id = $adb->query_result($result1,$i,'tabid');

		if($tab_id != 1 && $tab_id != 3 && $tab_id != 16 && $tab_id != 15  && $tab_id != 17)
		{
			//Inserting the Standard Actions into the Array	
			$sql= "select * from profile2utility where profileid =".$profileid." and tabid=".$tab_id;
			$result = $adb->query($sql);
			$num_rows = $adb->num_rows($result);
			for($j=0; $j<$num_rows; $j++)
			{
				$action_id = $adb->query_result($result,$j,'activityid');
				$per_id = $adb->query_result($result,$j,'permission');
				$access[$action_id] = $per_id;
			}

			//Inserting into the global Array
			$check[$tab_id] = $access;
		}

	}			

	return $check;
}

function getDefaultSharingAction()
{
	global $adb;
	//retreiving the standard permissions	
	$sql= "select * from default_org_sharingrule";
	$result = $adb->query($sql);
	$permissionRow=$adb->fetch_array($result);
	do
	{
		for($j=0;$j<count($permissionRow);$j++)
		{
			$copy[$permissionRow[1]]=$permissionRow[2];
		}

	}while($permissionRow=$adb->fetch_array($result));

	return $copy;

}

function setPermittedTabs2Session($profileid)
{
  global $adb;
  $sql = "select tabid from profile2tab where profileid=" .$profileid ." and permissions =0" ;
  $result = $adb->query($sql);
  
  $tabPermission=$adb->fetch_array($result);
  $i=0;
  do
  {
    for($j=0;$j<count($tabPermission);$j++)
    {
      $copy[$i]=$tabPermission["tabid"];
    }
    $i++;
    
  }while($tabPermission=$adb->fetch_array($result));
  
  $_SESSION['tab_permission_set']=$copy;
  
}

function setPermittedActions2Session($profileid)
{
  global $adb;
  $check = Array(); 	
  $sql1 = "select tabid from profile2tab where profileid=" .$profileid ." and permissions =0" ;
  $result1 = $adb->query($sql1);
  $num_rows1 = $adb->num_rows($result1);
  for($i=0; $i<$num_rows1; $i++)
  {
	$access = Array();
	$tab_id = $adb->query_result($result1,$i,'tabid');
	
	//echo 'tab is '.$tab_id;
	//echo '<BR>';

	//Inserting the Standard Actions into the Array	
	$sql= "select * from profile2standardpermissions where profileid =".$profileid." and tabid=".$tab_id;
	$result = $adb->query($sql);
	$num_rows = $adb->num_rows($result);
	for($j=0; $j<$num_rows; $j++)
	{
		$action_id = $adb->query_result($result,$j,'operation');
		//echo 'action is '.$action_id;
		//echo '<BR>';
		$per_id = $adb->query_result($result,$j,'permissions');
		//echo 'permission is '.$per_id;
		//echo '<BR>';
		$access[$action_id] = $per_id;
	}
	
	//Inserting the utility Actions into the Array
	$sql2= "select * from profile2utility where profileid =".$profileid." and tabid=".$tab_id;
	$result2 = $adb->query($sql2);
	$num_rows2 = $adb->num_rows($result2);
	for($k=0; $k<$num_rows2; $k++)
	{
		$action_id = $adb->query_result($result2,$k,'activityid');
		//echo 'action is '.$action_id;
		//echo '<BR>';
		$per_id = $adb->query_result($result2,$k,'permission');
		//echo 'permission is '.$per_id;
		//echo '<BR>';
		$access[$action_id] = $per_id;
	}

	//Inserting into the global Array
	$check[$tab_id] = $access;
	
  }			
  	
 $_SESSION['action_permission_set']=$check;
}

function setPermittedDefaultSharingAction2Session($profileid)
{
	global $adb;
	//retreiving the standard permissions	
	//$sql= "select default_org_sharingrule.* from default_org_sharingrule inner join profile2tab on profile2tab.tabid = default_org_sharingrule.tabid where profile2tab.permissions =0 and profile2tab.profileid=".$profileid;
	$sql = "select * from default_org_sharingrule";
	$result = $adb->query($sql);
	$permissionRow=$adb->fetch_array($result);
	do
	{
		for($j=0;$j<count($permissionRow);$j++)
		{
			$copy[$permissionRow[1]]=$permissionRow[2];
		}

	}while($permissionRow=$adb->fetch_array($result));

	$_SESSION['defaultaction_sharing_permission_set']=$copy;

}

function createNewRole($roleName,$parentRoleName)
{
  global $adb;
  $sql = "insert into role(name) values('" .$roleName ."')";
  $result = $adb->query($sql); 
  populatePermissions4NewRole($parentRoleName,$roleName);
  header("Location: index.php?module=Users&action=listroles");
}


function createNewGroup($groupName,$groupDescription)
{
  global $adb;
  $sql = "insert into groups(name,description) values('" .$groupName ."','". $groupDescription ."')";
  $result = $adb->query($sql); 
  header("Location: index.php?module=Users&action=listgroups");
}



function fetchTabId($moduleName)
{
  global $adb;
  $sql = "select id from tabu where name ='" .$moduleName ."'";
  $result = $adb->query($sql); 
  $tabid =  $adb->query_result($result,0,"id");
  return $tabid;

}

/*
if(isset($_REQUEST['roleName']))
{
  $roleName = $_REQUEST['roleName'];
  //echo $roleName;
  $parentRoleName = $_REQUEST['parentRoleName'];
  //echo 'PARENT ROLE IS '.$parentRoleName;
  createNewRole($roleName,$parentRoleName);
  
}*/

function populatePermissions4NewRole($parentroleName,$roleName)
{
  global $adb;
  //fetch the permissions for the parent role
  $referenceValues = fetchTabReferenceEntityValues($parentroleName);

  while($permissionRow = $adb->fetch_array($referenceValues))
  {
    $sql_insert="insert into role2tab(rolename,tabid,module_permission,description) values('" .$roleName ."'," .$permissionRow['tabid'] ."," .$permissionRow['module_permission'] .", '')";

    //echo $sql_insert;
    $adb->query($sql_insert);
  }

  $actionreferenceValues = fetchActionReferenceEntityValues($parentroleName);
  while($permissionRow = $adb->fetch_array($actionreferenceValues))
  {
    $sql_insert="insert into role2action(rolename,tabid,actionname,action_permission,description) values('" .$roleName ."'," .$permissionRow['tabid'] .",'" .$permissionRow['actionname'] ."'," .$permissionRow['action_permission'] .", '')";
    //echo $sql_insert;
    $adb->query($sql_insert);
  }
  
}


function fetchTabReferenceEntityValues($parentrolename)
{
  global $adb;
  $sql = "select tabid,module_permission,description from role2tab where rolename='" .$parentrolename ."'"; 
  //echo $sql;
  $result=$adb->query($sql);
  return $result;

}



function fetchActionReferenceEntityValues($parentrolename)
{
  global $adb;
  $sql = "select tabid,actionname,action_permission,description from role2action where rolename='" .$parentrolename ."'"; 
    $result=$adb->query($sql);
  return $result;
}


function fetchRoleId($rolename)
{

  global $adb;
  $sqlfetchroleid = "select roleid from role where name='".$rolename ."'";
  $resultroleid = $adb->query($sqlfetchroleid);
  $role_id = $adb->query_result($resultroleid,0,"roleid");
  return $role_id;
}

function updateUser2RoleMapping($roleid,$userid)
{
  global $adb;
  //Check if row already exists
  $sqlcheck = "select * from user2role where userid=".$userid;
  $resultcheck = $adb->query($sqlcheck);
  if($adb->num_rows($resultcheck) == 1)
  {
  	$sqldelete = "delete from user2role where userid=".$userid;
  	$result_delete = $adb->query($sqldelete);
  }	
  $sql = "insert into user2role(userid,roleid) values(" .$userid ."," .$roleid .")";
  $result = $adb->query($sql);

}




function updateUsers2GroupMapping($groupname,$userid)
{
  global $adb;
  $sqldelete = "delete from users2group where userid = '" .$userid ."'";
  $result_delete = $adb->query($sqldelete);
  $sql = "insert into users2group(groupname,userid) values('" .$groupname ."','" .$userid ."')";
  $result = $adb->query($sql);
}

function insertUser2RoleMapping($roleid,$userid)
{

  global $adb;	
  $sql = "insert into user2role(userid,roleid) values('" .$userid ."','" .$roleid ."')";
 $adb->query($sql); 

}


function insertUsers2GroupMapping($groupname,$userid)
{
  global $adb;
  $sql = "insert into users2group(groupname,userid) values('" .$groupname ."','" .$userid ."')";
  $adb->query($sql);
}





if(isset($_REQUEST['actiontype']))
{
  if($_REQUEST['actiontype'] == 'createnewgroup')
  {
    $groupname = $_REQUEST['groupName'];
    $description = $_REQUEST['groupDescription'];
    //get the new group name
    createNewGroup($groupname,$description);
    
  }
}

function fetchWordTemplateList($module)
{
  global $adb;
  $sql_word = "select filename from wordtemplates where module ='".$module."'" ; 
  $result=$adb->query($sql_word);
  return $result;
}




function fetchEmailTemplateInfo($templateName)
{
	global $adb;
        $sql= "select * from emailtemplates where templatename='" .$templateName ."'";
        $result = $adb->query($sql);
        return $result;
}

//template file 
function substituteTokens($filename,$globals)
{
	global $root_directory;
	//$globals = implode(",\\$",$tokens);
    
	if (!$filename)
	 {
		 $filename = $this->filename;
	 }
	
    if (!$dump = file ($filename))
	 {
     		 return 0;
    	 }	

      require_once($root_directory .'/modules/Emails/templates/testemailtemplateusage.php');
      eval ("global $globals; ");
    while (list($key,$val) = each($dump))
    {
	$replacedString ;
      if (ereg( "\$",$val)) 
	{
        $val = addslashes ($val);      
        eval(  "\$val = \"$val\";");
        $val = stripslashes ($val);
	$replacedString .= $val;
      }
    }
	return $replacedString;
}

function insert2LeadGroupRelation($leadid,$groupname)
{
global $adb;
  $sql = "insert into leadgrouprelation values (" .$leadid .",'".$groupname."')";
  $adb->query($sql);

}
function updateLeadGroupRelation($leadid,$groupname)
{
 global $adb;
  $sqldelete = "delete from leadgrouprelation where leadid=".$leadid;
  $adb->query($sqldelete);
  $sql = "insert into leadgrouprelation values (".$leadid .",'" .$groupname ."')";  
  $adb->query($sql);

}

function insert2ActivityGroupRelation($activityid,$groupname)
{
global $adb;
  $sql = "insert into activitygrouprelation values (" .$activityid ."','".$groupname."')";
  $adb->query($sql);

}
function updateActivityGroupRelation($activityid,$groupname)
{
global $adb;
  $sql = "update activityrouprelation set groupname='". $groupname ."' where activityid ='" .$activityid ."'";
  $adb->query($sql);

}

function getFieldList($fld_module, $profileid)
{
        global $adb;
        if($fld_module == "Accounts")
        {
                $tabid = 5;
        }
        $query = "select * from profile2field where profileid =".$profileid." and tabid=".$tabid;
        //echo $query;
        $result = $adb->query($query);
        return $result;
}

function getFieldVisibilityArray($fld_module, $profileid)
{
	global $adb;
        if($fld_module == "Accounts")
        {
                $tabid = 5;
        }
        $query = "select * from profile2field where profileid =".$profileid." and tabid=".$tabid;
        //echo $query;
        $result = $adb->query($query);
	$fldVisbArray = Array();
	$noofrows = $adb->num_rows($fieldListResult);
	for($i=0; $i<$noofrows; $i++)
	{
		$fld_name = $adb->query_result($fieldListResult,$i,"fieldname");
		$fldVisbArray[$fld_name] = $adb->query_result($fieldListResult,$i,"visible");	
	}
	return $fldVisbArray;	
	
}

function getFieldReadOnlyArray($fld_module, $profileid)
{
	global $adb;
        if($fld_module == "Accounts")
        {
                $tabid = 5;
        }
        $query = "select * from profile2field where profileid =".$profileid." and tabid=".$tabid;
        //echo $query;
        $result = $adb->query($query);
	$fldReadOnlyArray = Array();
	$noofrows = $adb->num_rows($fieldListResult);
	for($i=0; $i<$noofrows; $i++)
	{
		$fld_name = $adb->query_result($fieldListResult,$i,"fieldname");
		$fldReadOnlyArray[$fld_name] = $adb->query_result($fieldListResult,$i,"readonly");	
	}
	
	return $fldReadOnlyArray;	
}

function getRecordOwnerId($module, $record)
{
	global $adb;
	if($module == "Accounts")
	{
		$table_name = "accounts";
	}
	elseif($module == "Leads")
	{
		$table_name = "leads";
	}
	elseif($module == "Contacts")
	{
		$table_name = "contacts";
	}
	elseif($module == "Potentials")
	{
		$table_name = "potential";
	}

	$query = "select assigned_user_id from ".$table_name." where id='".$record."'";
	$result = $adb->query($query);
	$user_id = $adb->query_result($result,0,"assigned_user_id");
	return $user_id;	
		
}

function getRoleName($roleid)
{
	global $adb;
	$sql1 = "select * from role where roleid=".$roleid;
	$result = $adb->query($sql1);
	$rolename = $adb->query_result($result,0,"name");
	return $rolename;	
}

?>
