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
require_once('include/utils.php');
include('config.php');
global $vtlog;
if(isset($_REQUEST['groupname']))
{
  $groupname = $_REQUEST['groupname'];
  $sql= "select user_name from users2group inner join users on users.id= users2group.userid where groupname='" .$_REQUEST['groupname'] ."'";
  $result = $adb->query($sql);
  $groupnameList = "";
$numRows=$adb->num_rows($result);
  if($numRows == 0)
    {
     header("Location: index.php?module=Users&action=listgroupmembers&nameofgroup=$groupname&groupmembers=0");
    }
		
  while($groupList=$adb->fetch_array($result))
  {
    $groupnameList = $groupnameList .$groupList['user_name'] .",";
  }
  //CAUTION: The url exceeded was happening because the variable names were the same and would have been set in session thereby getting into an infinite loop
  header("Location: index.php?module=Users&action=listgroupmembers&nameofgroup=$groupname&groupmembers=$groupnameList");
}
function getMailServerInfo($user)
{
	global $adb;
	//$sql= "select rolename from user2role where userid='" .$userid ."'";
   $sql = "select * from mail_accounts where status=1 and user_id=".$user->id;
        $result = $adb->query($sql);
	return $result;
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


	$sql1 = "select profileid from role2profile where roleid='" .$roleid."'";
        $result1 = $adb->query($sql1);
	$profileid=  $adb->query_result($result1,0,"profileid");
	return $profileid;
}

function fetchUserGroups($userid)
{
	global $adb;
	$sql= "select groupname from users2group inner join groups on groups.groupid=users2group.groupid where userid='" .$userid ."'";
        //echo $sql;
        $result = $adb->query($sql);
        //store the groupnames in a comma separated string
        //echo 'count is ' .count($result);
	if($adb->num_rows($result)!=0)	$groupname=  $adb->query_result($result,0,"groupname");
	return $groupname;
}

function getAllTabsPermission($profileid)
{
	global $adb,$MAX_TAB_PER;
	$sql = "select * from profile2tab where profileid=" .$profileid ;
	$result = $adb->query($sql);
	$tab_perr_array = Array();
	if($MAX_TAB_PER !='')
	{
		$tab_perr_array = array_fill(0,$MAX_TAB_PER,0);
	}
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
	$sql = "select * from profile2tab where profileid=" .$profileid." and tabid not in(15)";
	$result = $adb->query($sql);
	$tab_perr_array = Array();
	$num_rows = $adb->num_rows($result);
	for($i=0; $i<$num_rows; $i++)
	{
		$tabid= $adb->query_result($result,$i,'tabid');
		$tab_per= $adb->query_result($result,$i,'permissions');
		if($tabid != 3 && $tabid != 16 && $tab_id != 15)
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
	$temp_tabid = Array();	
	$sql1 = "select * from profile2standardpermissions where profileid=".$profileid." and tabid not in(15,16) order by(tabid)";
	//echo $sql1.'<BR>';
	$result1 = $adb->query($sql1);
        $num_rows1 = $adb->num_rows($result1);
        for($i=0; $i<$num_rows1; $i++)
        {
		$tab_id = $adb->query_result($result1,$i,'tabid');
		if(! in_array($tab_id,$temp_tabid))
		{	
			$temp_tabid[] = $tab_id;
			$access = Array(); 
		}

		$action_id = $adb->query_result($result1,$i,'operation');
		$per_id = $adb->query_result($result1,$i,'permissions');
		$access[$action_id] = $per_id;
		$check[$tab_id] = $access;	


	}

 	
	return $check;
}

function getTabsUtilityActionPermission($profileid)
{

	global $adb;
	$check = Array();
	$temp_tabid = Array();	
	$sql1 = "select * from profile2utility where profileid=".$profileid." order by(tabid)";
	//echo $sql1.'<BR>';
	$result1 = $adb->query($sql1);
        $num_rows1 = $adb->num_rows($result1);
        for($i=0; $i<$num_rows1; $i++)
        {
		$tab_id = $adb->query_result($result1,$i,'tabid');
		if(! in_array($tab_id,$temp_tabid))
		{	
			$temp_tabid[] = $tab_id;
			$access = Array(); 
		}

		$action_id = $adb->query_result($result1,$i,'activityid');
		$per_id = $adb->query_result($result1,$i,'permission');
		$access[$action_id] = $per_id;
		$check[$tab_id] = $access;	


	}

	return $check;

}
/**This Function returns the Default Organisation Sharing Action Array for all modules whose sharing actions are editable
  * The result array will be in the following format:
  * Arr=(tabid1=>Sharing Action Id,
  *      tabid2=>SharingAction Id,
  *            |
  *            |
  *            |
  *      tabid3=>SharingAcion Id)  
  */       

function getDefaultSharingEditAction()
{
	global $adb;
	//retreiving the standard permissions	
	$sql= "select * from def_org_share where editstatus=0";
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
/**This Function returns the Default Organisation Sharing Action Array for all modules 
  * The result array will be in the following format:
  * Arr=(tabid1=>Sharing Action Id,
  *      tabid2=>SharingAction Id,
  *            |
  *            |
  *            |
  *      tabid3=>SharingAcion Id)  
  */
function getDefaultSharingAction()
{
	global $adb;
	//retreiving the standard permissions	
	$sql= "select * from def_org_share where editstatus in(0,1)";
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
	$sql = "select * from def_org_share";
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

function createRole($roleName,$parentRoleId,$roleProfileArray)
{
	global $adb;
	$parentRoleDetails=getRoleInformation($parentRoleId);
	$parentRoleInfo=$parentRoleDetails[$parentRoleId];
	$roleid_no=$adb->getUniqueId("role");
        $roleId='H'.$roleid_no;
        $parentRoleHr=$parentRoleInfo[1];
        $parentRoleDepth=$parentRoleInfo[2];
        $nowParentRoleHr=$parentRoleHr.'::'.$roleId;
        $nowRoleDepth=$parentRoleDepth + 1;

	//Inserting role into db
	$query="insert into role values('".$roleId."','".$roleName."','".$nowParentRoleHr."',".$nowRoleDepth.")";
	$adb->query($query);

	//Inserting into role2profile table
	foreach($roleProfileArray as $profileId)
        {
                if($profileId != '')
                {
                        insertRole2ProfileRelation($roleId,$profileId);
                }
        }

	return $roleId;

}

function updateRole($roleId,$roleName,$roleProfileArray)
{
	global $adb;
	$sql1 = "update role set rolename='".$roleName."' where roleid='".$roleId."'";
        $adb->query($sql1);
	//Updating the Role2Profile relation
	$sql2 = "delete from role2profile where roleId='".$roleId."'";
	$adb->query($sql1);

	foreach($roleProfileArray as $profileId)
        {
                if($profileId != '')
                {
                        insertRole2ProfileRelation($roleId,$profileId);
                }
        }
	
	
}

function insertRole2ProfileRelation($roleId,$profileId)
{
	global $adb;
	$query="insert into role2profile values('".$roleId."',".$profileId.")";
	$adb->query($query);	
	
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
  $sqlfetchroleid = "select roleid from role where rolename='".$rolename ."'";
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
  $sql = "insert into user2role(userid,roleid) values(" .$userid .",'" .$roleid ."')";
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
	global $vtlog;
	$vtlog->logthis("in substituteTokens method  with filename ".$filename.' and content globals as '.$globals,'debug');  

	global $root_directory;
	//$globals = implode(",\\$",$tokens);
    
	if (!$filename)
	 {

	$vtlog->logthis("filename is not set in substituteTokens",'debug');  
		 $filename = $this->filename;
	$vtlog->logthis("filename is not set in substituteTokens so taking default filename",'debug');  
	 }
	
    if (!$dump = file ($filename))
	 {
	$vtlog->logthis("not able to create the file or get access to the file with filename ".$filename." so returning 0",'debug');  
     		 return 0;
    	 }	

	$vtlog->logthis("about to start replacing the tokens",'debug');  
      require_once($root_directory .'/modules/Emails/templates/testemailtemplateusage.php');
      eval ("global $globals; ");
    while (list($key,$val) = each($dump))
    {
	$replacedString ;
      if (ereg( "\$",$val)) 
	{
        $val = addslashes ($val);      
	$vtlog->logthis("token is ".$val,'debug');  
        eval(  "\$val = \"$val\";");
        $val = stripslashes ($val);
	$replacedString .= $val;
      }
    }

	$vtlog->logthis("the replacedString  is ".$replacedString,'debug');  
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
function updateTicketGroupRelation($ticketid,$groupname)
{
 global $adb;
  $sqldelete = "delete from ticketgrouprelation where ticketid=".$ticketid;
  $adb->query($sqldelete);
  $sql = "insert into ticketgrouprelation values (".$ticketid .",'" .$groupname ."')";  
  $adb->query($sql);

}

function insert2ActivityGroupRelation($activityid,$groupname)
{
global $adb;
  $sql = "insert into activitygrouprelation values (" .$activityid .",'".$groupname."')";
  $adb->query($sql);

}

function insert2TicketGroupRelation($ticketid,$groupname)
{
global $adb;
  $sql = "insert into ticketgrouprelation values (" .$ticketid .",'".$groupname."')";
  $adb->query($sql);

}

function updateActivityGroupRelation($activityid,$groupname)
{
	global $adb;
  $sqldelete = "delete from activitygrouprelation where activityid=".$activityid;
  $adb->query($sqldelete);
  $sql = "insert into activitygrouprelation values (".$activityid .",'" .$groupname ."')";  
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
	$sql1 = "select * from role where roleid='".$roleid."'";
	$result = $adb->query($sql1);
	$rolename = $adb->query_result($result,0,"rolename");
	return $rolename;	
}

function getProfileName($profileid)
{
	global $adb;
	$sql1 = "select * from profile where profileid=".$profileid;
	$result = $adb->query($sql1);
	$profilename = $adb->query_result($result,0,"profilename");
	return $profilename;	
}

function isPermitted($module,$actionid,$record_id)
{

	$permission = "no";
	if($module == 'Users' || $module == 'Home' || $module == 'Administration' || $module == 'uploads' ||  $module == 'Settings' || $module == 'Calendar')
	{
		//These modules done have security
		$permission = "yes";

	}
	else
	{	
		global $adb;
		global $current_user;
		$tabid = getTabid($module);
		//echo "tab id is ".$tabid;
		//echo '<BR>';
		$action = getActionname($actionid);
		$profile_id = $_SESSION['authenticated_user_profileid'];
		$tab_per_Data = getAllTabsPermission($profile_id);

		$permissionData = $_SESSION['action_permission_set'];
		$defSharingPermissionData = $_SESSION['defaultaction_sharing_permission_set'];
		$others_permission_id = $defSharingPermissionData[$tabid];

		//Checking whether this tab is allowed
		if($tab_per_Data[$tabid] == 0)
		{
			//echo "inside tab permission success";
			//echo '<BR>';
			$permission = 'yes';
			//Checking whether this action is allowed
			if($permissionData[$tabid][$actionid] == 0)
			{
				//echo "inside action permission success";
	                        //echo '<BR>';	
				$permission = 'yes';
				$rec_owner_id = '';
				if($record_id != '' && $module != 'Notes' && $module != 'Products' && $module != 'Faq' && $module != 'Vendor'  && $module != 'PriceBook')
				{
					$rec_owner_id = getUserId($record_id);
				}

				if($record_id != '' && $others_permission_id != '' && $module != 'Notes' && $module != 'Products' && $module != 'Faq' && $module != 'Vendor' && $module != 'PriceBook' && $rec_owner_id != 0)
				{
					//echo "inside other permission success";
                                	//echo '<BR>';
					//Checking for Default Sharing Permission
					//$rec_owner_id = getUserId($record_id);
					if($rec_owner_id != $current_user->id)
					{
						if($others_permission_id == 0)
						{
							if($action == 'EditView' || $action == 'Delete')
							{
								$permission = "no";	
							}
							else
							{
								$permission = "yes";
							}
						}
						elseif($others_permission_id == 1)
						{
							if($action == 'Delete')
							{
								$permission = "no";
							}
							else
							{
								$permission = "yes";
							}
						}
						elseif($others_permission_id == 2)
						{

							$permission = "yes";
						}
						elseif($others_permission_id == 3)
						{
							if($action == 'DetailView' || $action == 'EditView' || $action == 'Delete')
							{
								$permission = "no";
							}
							else
							{
								$permission = "yes";
							}
						}


					}
					else
					{
						$permission = "yes";	
					}	
				}
			}
			else
			{
				$permission = "no";
			}		
		}
		else
		{
			$permission = "no";
		}		
	}
	return $permission;

}



function isAllowed_Outlook($module,$action,$user_id,$record_id)
{

	$permission = "no";
	if($module == 'Users' || $module == 'Home' || $module == 'Administration' || $module == 'uploads' ||  $module == 'Settings' || $module == 'Calendar')
	{
		//These modules done have security
		$permission = "yes";

	}
	else
	{	
		global $adb;
		global $current_user;
		$tabid = getTabid($module);
		//echo "tab id is ".$tabid;
		//echo '<BR>';
		$actionid = getActionid($action);
		$profile_id = fetchUserProfileId($user_id);
		$tab_per_Data = getAllTabsPermission($profile_id);

		$permissionData = getTabsActionPermission($profile_id); 
		$defSharingPermissionData = getDefaultSharingAction();
		$others_permission_id = $defSharingPermissionData[$tabid];

		//Checking whether this tab is allowed
		if($tab_per_Data[$tabid] == 0)
		{
			//echo "inside tab permission success";
			//echo '<BR>';
			$permission = 'yes';
			//Checking whether this action is allowed
			if($permissionData[$tabid][$actionid] == 0)
			{
				//echo "inside action permission success";
	                        //echo '<BR>';	
				$permission = 'yes';
				$rec_owner_id = '';
				if($record_id != '' && $module != 'Notes' && $module != 'Products' && $module != 'Faq')
				{
					$rec_owner_id = getUserId($record_id);
				}

				if($record_id != '' && $others_permission_id != '' && $module != 'Notes' && $module != 'Products' && $module != 'Faq' && $rec_owner_id != 0)
				{
					//echo "inside other permission success";
                                	//echo '<BR>';
					//Checking for Default Sharing Permission
					//$rec_owner_id = getUserId($record_id);
					if($rec_owner_id != $current_user->id)
					{
						if($others_permission_id == 0)
						{
							if($action == 'EditView' || $action == 'Delete')
							{
								$permission = "no";	
							}
							else
							{
								$permission = "yes";
							}
						}
						elseif($others_permission_id == 1)
						{
							if($action == 'Delete')
							{
								$permission = "no";
							}
							else
							{
								$permission = "yes";
							}
						}
						elseif($others_permission_id == 2)
						{

							$permission = "yes";
						}
						elseif($others_permission_id == 3)
						{
							if($action == 'DetailView' || $action == 'EditView' || $action == 'Delete')
							{
								$permission = "no";
							}
							else
							{
								$permission = "yes";
							}
						}


					}
					else
					{
						$permission = "yes";	
					}	
				}
			}
			else
			{
				$permission = "no";
			}		
		}
		else
		{
			$permission = "no";
		}		
	}
	return $permission;

}

function setGlobalProfilePermission2Session($profileid)
{
  global $adb;
  $sql = "select * from profile2globalpermissions where profileid=".$profileid ;
  $result = $adb->query($sql);
  $num_rows = $adb->num_rows($result);

  for($i=0; $i<$num_rows; $i++)
  {
	$act_id = $adb->query_result($result,$i,"globalactionid");
	$per_id = $adb->query_result($result,$i,"globalactionpermission");
	$copy[$act_id] = $per_id;
  }	 

  $_SESSION['global_permission_set']=$copy;
  
}

function getProfileGlobalPermission($profileid)
{
  global $adb;
  $sql = "select * from profile2globalpermissions where profileid=".$profileid ;
  $result = $adb->query($sql);
  $num_rows = $adb->num_rows($result);

  for($i=0; $i<$num_rows; $i++)
  {
	$act_id = $adb->query_result($result,$i,"globalactionid");
	$per_id = $adb->query_result($result,$i,"globalactionpermission");
	$copy[$act_id] = $per_id;
  }	 

   return $copy;
  
}

function createProfile($profilename,$parentProfileId)
{
	global $adb;
	//Inserting values into Profile Table
	$sql1 = "insert into profile values('','".$profilename."')";
	$adb->query($sql1);

	//Retreiving the profileid
	$sql2 = "select max(profileid) as current_id from profile";
	$result2 = $adb->query($sql2);
	$current_profile_id = $adb->query_result($result2,0,'current_id');

	//Inserting values into profile2globalpermissions
	$sql3 = "select * from profile2globalpermissions where profileid=".$parentProfileId;
	$result3= $adb->query($sql3);
	$p2tab_rows = $adb->num_rows($result3);
	for($i=0; $i<$p2tab_rows; $i++)
	{
		$act_id=$adb->query_result($result3,$i,'globalactionid');
		$permissions=$adb->query_result($result3,$i,'globalactionpermission');
		$sql4="insert into profile2globalpermissions values(".$current_profile_id.", ".$act_id.", ".$permissions.")";
		$adb->query($sql4);	
	}

	//Inserting values into Profile2tab table
	$sql3 = "select * from profile2tab where profileid=".$parentProfileId;
	$result3= $adb->query($sql3);
	$p2tab_rows = $adb->num_rows($result3);
	for($i=0; $i<$p2tab_rows; $i++)
	{
		$tab_id=$adb->query_result($result3,$i,'tabid');
		$permissions=$adb->query_result($result3,$i,'permissions');
		$sql4="insert into profile2tab values(".$current_profile_id.", ".$tab_id.", ".$permissions.")";
		$adb->query($sql4);	
	}

	//Inserting values into Profile2standard table
	$sql6 = "select * from profile2standardpermissions where profileid=".$parentProfileId;
	$result6= $adb->query($sql6);
	$p2per_rows = $adb->num_rows($result6);
	for($i=0; $i<$p2per_rows; $i++)
	{
		$tab_id=$adb->query_result($result6,$i,'tabid');
		$action_id=$adb->query_result($result6,$i,'operation');	
		$permissions=$adb->query_result($result6,$i,'permissions');
		$sql7="insert into profile2standardpermissions values(".$current_profile_id.", ".$tab_id.", ".$action_id.", ".$permissions.")";
		$adb->query($sql7);	
	}

	//Inserting values into Profile2Utility table
	$sql8 = "select * from profile2utility where profileid=".$parentProfileId;
	$result8= $adb->query($sql8);
	$p2util_rows = $adb->num_rows($result8);
	for($i=0; $i<$p2util_rows; $i++)
	{
		$tab_id=$adb->query_result($result8,$i,'tabid');
		$action_id=$adb->query_result($result8,$i,'activityid');	
		$permissions=$adb->query_result($result8,$i,'permission');
		$sql9="insert into profile2utility values(".$current_profile_id.", ".$tab_id.", ".$action_id.", ".$permissions.")";
		$adb->query($sql9);	
	}

	//Inserting values into Profile2field table
	$sql10 = "select * from profile2field where profileid=".$parentProfileId;
	$result10= $adb->query($sql10);
	$p2field_rows = $adb->num_rows($result10);
	for($i=0; $i<$p2field_rows; $i++)
	{
		$tab_id=$adb->query_result($result10,$i,'tabid');
		$fieldid=$adb->query_result($result10,$i,'fieldid');	
		$permissions=$adb->query_result($result10,$i,'visible');
		$readonly=$adb->query_result($result10,$i,'readonly');
		$sql11="insert into profile2field values(".$current_profile_id.", ".$tab_id.", ".$fieldid.", ".$permissions." ,".$readonly.")";
		$adb->query($sql11);	
	}
}

function deleteProfile($prof_id,$transfer_profileid='')
{
	global $adb;
	//delete from profile2global permissions
	$sql4 = "delete from profile2globalpermissions where profileid=".$prof_id;
	$adb->query($sql4);

	//deleting from profile 2 tab;
	$sql4 = "delete from profile2tab where profileid=".$prof_id;
	$adb->query($sql4);

	//deleting from profile2standardpermissions table
	$sql5 = "delete from profile2standardpermissions where profileid=".$prof_id;
	$adb->query($sql5);

	//deleting from profile2field
	$sql6 ="delete from profile2field where profileid=".$prof_id;
	$adb->query($sql6);

	//deleting from profile2utility
	$sql7 ="delete from profile2utility where profileid=".$prof_id;
	$adb->query($sql7);


	//updating role2profile
	if(isset($transfer_profileid) && $transfer_profileid != '')
	{
		$sql8 = "update role2profile set profileid=".$transfer_profileid." where profileid=".$prof_id;
		$adb->query($sql8);
	}

	//delete from profile table;
	$sql9 = "delete from profile where profileid=".$prof_id;
	$adb->query($sql9);	

}

function getAllRoleDetails()
{
	global $adb;
	$role_det = Array();
	$query = "select * from role";
	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	for($i=0; $i<$num_rows;$i++)
	{
		$each_role_det = Array();
		$roleid=$adb->query_result($result,$i,'roleid');
		$rolename=$adb->query_result($result,$i,'rolename');
		$roledepth=$adb->query_result($result,$i,'depth');
		$sub_roledepth=$roledepth + 1;
		$parentrole=$adb->query_result($result,$i,'parentrole');
		$sub_role='';
		
		//getting the immediate subordinates
		$query1="select * from role where parentrole like '".$parentrole."::%' and depth=".$sub_roledepth;
		$res1 = $adb->query($query1);
		$num_roles = $adb->num_rows($res1);
		if($num_roles > 0)
		{
			for($j=0; $j<$num_roles; $j++)
			{
				if($j == 0)
				{
					$sub_role .= $adb->query_result($res1,$j,'roleid');
				}
				else
				{
					$sub_role .= ','.$adb->query_result($res1,$j,'roleid');
				}
			}
		}
			

		$each_role_det[]=$rolename;
		$each_role_det[]=$roledepth;
		$each_role_det[]=$sub_role;
		$role_det[$roleid]=$each_role_det;	
		
	}
	return $role_det;
}

function getAllProfileInfo()
{
	global $adb;
	$query="select * from profile";
	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	$prof_details=Array();
	for($i=0;$i<$num_rows;$i++)
	{
		$profileid=$adb->query_result($result,$i,'profileid');
		$profilename=$adb->query_result($result,$i,'profilename');
		$prof_details[$profileid]=$profilename;
		
	}
	return $prof_details;	
}

function getRoleInformation($roleid)
{
	global $adb;
	$query = "select * from role where roleid='".$roleid."'";
	$result = $adb->query($query);
	$rolename=$adb->query_result($result,0,'rolename');
	$parentrole=$adb->query_result($result,0,'parentrole');
	$roledepth=$adb->query_result($result,0,'depth');
	$parentRoleArr=explode('::',$parentrole);
	$immediateParent=$parentRoleArr[sizeof($parentRoleArr)-2];
	$roleDet=Array();
	$roleDet[]=$rolename;
	$roleDet[]=$parentrole;
	$roleDet[]=$roledepth;
	$roleDet[]=$immediateParent;
	$roleInfo=Array();
	$roleInfo[$roleid]=$roleDet;
	return $roleInfo;	
}

function getRoleRelatedProfiles($roleId)
{
	global $adb;
	$query = "select role2profile.*,profile.profilename from role2profile inner join profile on profile.profileid=role2profile.profileid where roleid='".$roleId."'";
	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	$roleRelatedProfiles=Array();
	for($i=0; $i<$num_rows; $i++)
	{
		$roleRelatedProfiles[$adb->query_result($result,$i,'profileid')]=$adb->query_result($result,$i,'profilename');
	}	
	return $roleRelatedProfiles;	
}

function getRoleUsers($roleId)
{
	global $adb;
	$query = "select user2role.*,users.user_name from user2role inner join users on users.id=user2role.userid where roleid='".$roleId."'";
	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	$roleRelatedUsers=Array();
	for($i=0; $i<$num_rows; $i++)
	{
		$roleRelatedUsers[$adb->query_result($result,$i,'userid')]=$adb->query_result($result,$i,'user_name');
	}
	return $roleRelatedUsers;
	

}

function getRoleAndSubordinatesInformation($roleId)
{
	global $adb;
	$roleDetails=getRoleInformation($roleId);
	$roleInfo=$roleDetails[$roleId];
	$roleParentSeq=$roleInfo[1];
	
	$query="select * from role where parentrole like '".$roleParentSeq."%' order by parentrole asc";
	$result=$adb->query($query);
	$num_rows=$adb->num_rows($result);
	$roleInfo=Array();
	for($i=0;$i<$num_rows;$i++)
	{
		$roleid=$adb->query_result($result,$i,'roleid');
                $rolename=$adb->query_result($result,$i,'rolename');
                $roledepth=$adb->query_result($result,$i,'depth');
                $parentRoleSeq=$adb->query_result($result,$i,'parentrole');
		$roleDet=Array();
		$roleDet[]=$rolename;
		$roleDet[]=$parentrole;
		$roleDet[]=$roledepth;
		$roleInfo[$roleid]=$roleDet;
		
	}
	return $roleInfo;	

}


function deleteRole($roleId,$transferRoleId)
{
	global $adb;
	$roleInfo=getRoleAndSubordinatesInformation($roleId);
	foreach($roleInfo as $roleid=>$roleDetArr)
	{
		
		$sql1 = "update user2role set roleid='".$transferRoleId."' where roleid='".$roleid."'";
		$adb->query($sql1);

		//Deleteing from role2profile table
		$sql2 = "delete from role2profile where roleid='".$roleid."'";
		$adb->query($sql2);

		//delete from role table;
		$sql9 = "delete from role where roleid='".$roleid."'";
		$adb->query($sql9);		
	}

}

function getAllUserName()
{
	global $adb;
	$query="select * from users where deleted=0";
	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	$user_details=Array();
	for($i=0;$i<$num_rows;$i++)
	{
		$userid=$adb->query_result($result,$i,'id');
		$username=$adb->query_result($result,$i,'user_name');
		$user_details[$userid]=$username;
		
	}
	return $user_details;

}

function getAllGroupName()
{
	global $adb;
	$query="select * from groups";
	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	$group_details=Array();
	for($i=0;$i<$num_rows;$i++)
	{
		$grpid=$adb->query_result($result,$i,'groupid');
		$grpname=$adb->query_result($result,$i,'groupname');
		$group_details[$grpid]=$grpname;
		
	}
	return $group_details;

}

function getAllGroupInfo()
{
	global $adb;
	$query="select * from groups";
	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	$group_details=Array();
	for($i=0;$i<$num_rows;$i++)
	{
		$grpInfo=Array();
		$grpid=$adb->query_result($result,$i,'groupid');
		$grpname=$adb->query_result($result,$i,'groupname');
		$description=$adb->query_result($result,$i,'description');
		$grpInfo[0]=$grpname;
		$grpInfo[1]=$description;
		$group_details[$grpid]=$grpInfo;
		
	}
	return $group_details;

}


function createGroup($groupName,$groupMemberArray,$description)
{
	global $adb;
	$groupId=$adb->getUniqueId("groups");
	//Insert into group table
	$query = "insert into groups values(".$groupId.",'".$groupName."','".$description."')";
	$adb->query($query);

	//Insert Group to Group Relation
	$groupArray=$groupMemberArray['groups'];
	$roleArray=$groupMemberArray['roles'];
	$rsArray=$groupMemberArray['rs'];
	$userArray=$groupMemberArray['users'];

	foreach($groupArray as $group_id)
	{
		insertGroupToGroupRelation($groupId,$group_id);
	}
	 
	//Insert Group to Role Relation
	foreach($roleArray as $roleId)
	{
		insertGroupToRoleRelation($groupId,$roleId);
	}

	//Insert Group to RoleAndSubordinate Relation
	foreach($rsArray as $rsId)
	{
		insertGroupToRsRelation($groupId,$rsId);
	}

	//Insert Group to Role Relation
	foreach($userArray as $userId)
	{
		insertGroupToUserRelation($groupId,$userId);
	}
	return $groupId;	
}

function insertGroupToGroupRelation($groupId,$containsGroupId)
{
	global $adb;
	$query="insert into group2grouprel values(".$groupId.",".$containsGroupId.")";
	$adb->query($query);
}

function insertGroupToRoleRelation($groupId,$roleId)
{
	global $adb;
	$query="insert into group2role values(".$groupId.",'".$roleId."')";
	$adb->query($query);
}

function insertGroupToRsRelation($groupId,$rsId)
{
	global $adb;
	$query="insert into group2rs values(".$groupId.",'".$rsId."')";
	$adb->query($query);
}

function insertGroupToUserRelation($groupId,$userId)
{
	global $adb;
	$query="insert into users2group values(".$groupId.",".$userId.")";
	$adb->query($query);
}

function getGroupInfo($groupId)
{
	global $adb;
	$groupDetailArr=Array();
	$groupMemberArr=Array();
	//Retreving the group Info
	$query="select * from groups where groupid=".$groupId;
	$result = $adb->query($query);
	$groupName=$adb->query_result($result,0,'groupname');
	$description=$adb->query_result($result,0,'description');
	
	//Retreving the Group RelatedMembers
	$groupMemberArr=getGroupMembers($groupId);
	$groupDetailArr[]=$groupName;
	$groupDetailArr[]=$description;
	$groupDetailArr[]=$groupMemberArr;

	//Returning the Group Detail Array
	return $groupDetailArr;
	 

}
function fetchGroupName($groupId)
{

	global $adb;
	//Retreving the group Info
	$query="select * from groups where groupid=".$groupId;
	$result = $adb->query($query);
	$groupName=$adb->query_result($result,0,'groupname');
	return $groupName;
	
}

function getGroupMembers($groupId)
{
	$groupMemberArr=Array();
	$roleGroupArr=getGroupRelatedRoles($groupId);
	$rsGroupArr=getGroupRelatedRoleSubordinates($groupId);
	$groupGroupArr=getGroupRelatedGroups($groupId);
	$userGroupArr=getGroupRelatedUsers($groupId);
	
	$groupMemberArr['groups']=$groupGroupArr;
	$groupMemberArr['roles']=$roleGroupArr;
	$groupMemberArr['rs']=$rsGroupArr;
	$groupMemberArr['users']=$userGroupArr;
	
	return($groupMemberArr);
}


function getGroupRelatedRoles($groupId)
{
	global $adb;
	$roleGroupArr=Array();
	$query="select * from group2role where groupid=".$groupId;
	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	for($i=0;$i<$num_rows;$i++)
	{
		$roleId=$adb->query_result($result,$i,'roleid');
		$roleGroupArr[]=$roleId;
	}
	return $roleGroupArr;	
			
}

function getGroupRelatedRoleSubordinates($groupId)
{
	global $adb;
	$rsGroupArr=Array();
	$query="select * from group2rs where groupid=".$groupId;
	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	for($i=0;$i<$num_rows;$i++)
	{
		$roleSubId=$adb->query_result($result,$i,'roleandsubid');
		$rsGroupArr[]=$roleSubId;
	}
	return $rsGroupArr;
}

function getGroupRelatedGroups($groupId)
{
	global $adb;
	$groupGroupArr=Array();
	$query="select * from group2grouprel where groupid=".$groupId;
	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	for($i=0;$i<$num_rows;$i++)
	{
		$relGroupId=$adb->query_result($result,$i,'containsgroupid');
		$groupGroupArr[]=$relGroupId;
	}
	return $groupGroupArr;	
			
}

function getGroupRelatedUsers($groupId)
{
	global $adb;
	$userGroupArr=Array();
	$query="select * from users2group where groupid=".$groupId;
	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	for($i=0;$i<$num_rows;$i++)
	{
		$userId=$adb->query_result($result,$i,'userid');
		$userGroupArr[]=$userId;
	}
	return $userGroupArr;	
			
}

function updateGroup($groupId,$groupName,$groupMemberArray,$description)
{
	global $adb;
	$query="update groups set groupname='".$groupName."',description='".$description."' where groupid=".$groupId;
	$adb->query($query);

	//Deleting the Group Member Relation
	deleteGroupRelatedGroups($groupId);	
	deleteGroupRelatedRoles($groupId);	
	deleteGroupRelatedRolesAndSubordinates($groupId);	
	deleteGroupRelatedUsers($groupId);	

	//Inserting the Group Member Entries
	$groupArray=$groupMemberArray['groups'];
	$roleArray=$groupMemberArray['roles'];
	$rsArray=$groupMemberArray['rs'];
	$userArray=$groupMemberArray['users'];

	foreach($groupArray as $group_id)
	{
		insertGroupToGroupRelation($groupId,$group_id);
	}
	 
	//Insert Group to Role Relation
	foreach($roleArray as $roleId)
	{
		insertGroupToRoleRelation($groupId,$roleId);
	}

	//Insert Group to RoleAndSubordinate Relation
	foreach($rsArray as $rsId)
	{
		insertGroupToRsRelation($groupId,$rsId);
	}

	//Insert Group to Role Relation
	foreach($userArray as $userId)
	{
		insertGroupToUserRelation($groupId,$userId);
	}
		

}

function deleteGroup($groupId)
{
	global $adb;
	$query="delete from groups where groupid=".$groupId;
	$adb->query($query);

	deleteGroupRelatedGroups($groupId);
	deleteGroupRelatedRoles($groupId);
	deleteGroupRelatedRolesAndSubordinates($groupId);
	deleteGroupRelatedUsers($groupId);	

}


function deleteGroupRelatedGroups($groupId)
{
	global $adb;
	$query="delete from group2grouprel where groupid=".$groupId;
	$adb->query($query);
}

function deleteGroupRelatedRoles($groupId)
{
	global $adb;
	$query="delete from group2role where groupid=".$groupId;
	$adb->query($query);
}

function deleteGroupRelatedRolesAndSubordinates($groupId)
{
	global $adb;
	$query="delete from group2rs where groupid=".$groupId;
	$adb->query($query);
}

function deleteGroupRelatedUsers($groupId)
{
	global $adb;
	$query="delete from users2group where groupid=".$groupId;
	$adb->query($query);
}

/** This function returns the Default Organisation Sharing Action Name
  * It takes the Default Organisation Sharing ActionId as input
  */
function getDefOrgShareActionName($share_action_id)
{
	global $adb;
	$query="select * from org_share_action_mapping where share_action_id=".$share_action_id;
	$result=$adb->query($query);
	$share_action_name=$adb->query_result($result,0,"share_action_name");
	return $share_action_name;		


}
/** This function returns the Default Organisation Sharing Action Array for the specified Module
  * It takes the module tabid as input and constructs the array. 
  * The output array consists of the 'Default Organisation Sharing Id'=>'Default Organisation Sharing Action' mapping for all the sharing actions available for the specifed module
  * The output Array will be in the following format:
  *    Array = (Default Org ActionId1=>Default Org ActionName1,
  *             Default Org ActionId2=>Default Org ActionName2,
  *			|
  *                     |
  *              Default Org ActionIdn=>Default Org ActionNamen)
  */
function getModuleSharingActionArray($tabid)
{
	global $adb;
	$share_action_arr=Array();
	$query = "select org_share_action_mapping.share_action_name,org_share_action2tab.share_action_id from org_share_action2tab inner join org_share_action_mapping on org_share_action2tab.share_action_id=org_share_action_mapping.share_action_id where org_share_action2tab.tabid=".$tabid;
	$result=$adb->query($query);
	$num_rows=$adb->num_rows($result);
	for($i=0; $i<$num_rows; $i++)
	{
		$share_action_name=$adb->query_result($result,$i,"share_action_name");
		$share_action_id=$adb->query_result($result,$i,"share_action_id");
		$share_action_arr[$share_action_id] = $share_action_name;
	}
	return $share_action_arr;
	
}

?>
