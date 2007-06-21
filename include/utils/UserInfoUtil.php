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
require_once('include/database/Postgres8.php');
require_once('include/utils/utils.php');
require_once('include/utils/GetUserGroups.php');
include('config.php');
global $log;

/** To retreive the mail server info resultset for the specified user
  * @param $user -- The user object:: Type Object
  * @returns  the mail server info resultset
 */
function getMailServerInfo($user)
{
	global $log;
	$log->debug("Entering getMailServerInfo(".$user->user_name.") method ...");
	global $adb;
	//$sql= "select vtiger_rolename from vtiger_user2role where userid='" .$userid ."'";
   $sql = "select * from vtiger_mail_accounts where status=1 and user_id=".$user->id;
        $result = $adb->query($sql);
	$log->debug("Exiting getMailServerInfo method ...");
	return $result;
}

/** To get the Role of the specified user
  * @param $userid -- The user Id:: Type integer
  * @returns  vtiger_roleid :: Type String
 */
function fetchUserRole($userid)
{
	global $log;
	$log->debug("Entering fetchUserRole(".$userid.") method ...");
	global $adb;
	//$sql= "select vtiger_rolename from vtiger_user2role where userid='" .$userid ."'";
	$sql = "select roleid from vtiger_user2role where userid='" .$userid ."'";
        $result = $adb->query($sql);
	$roleid=  $adb->query_result($result,0,"roleid");
	$log->debug("Exiting fetchUserRole method ...");
	return $roleid;
}

/** Depricated. Function to be replaced by getUserProfile()
  * Should be done accross the product 
  * 
 */
function fetchUserProfileId($userid)
{
	global $log;
	$log->debug("Entering fetchUserProfileId(".$userid.") method ...");
	global $adb;
	$sql = "select roleid from vtiger_user2role where userid=" .$userid;
        $result = $adb->query($sql);
	$roleid=  $adb->query_result($result,0,"roleid");


	$sql1 = "select profileid from vtiger_role2profile where roleid='" .$roleid."'";
        $result1 = $adb->query($sql1);
	$profileid=  $adb->query_result($result1,0,"profileid");
	$log->debug("Exiting fetchUserProfileId method ...");
	return $profileid;
}

/** Function to get the lists of groupids releated with an user
 * This function accepts the user id as arguments and 
 * returns the groupids related with the user id
 * as a comma seperated string
*/
function fetchUserGroupids($userid)
{
	global $log;
	$log->debug("Entering fetchUserGroupids(".$userid.") method ...");
	global $adb;
        $focus = new GetUserGroups();
        $focus->getAllUserGroups($userid);
        $groupidlists = implode(",",$focus->user_groups);
	$log->debug("Exiting fetchUserGroupids method ...");
        return $groupidlists;
		
}

/** Function to load all the permissions
  *
 */
function loadAllPerms()
                {
	global $log;
	$log->debug("Entering loadAllPerms() method ...");
        global $adb,$MAX_TAB_PER;
        global $persistPermArray;

        $persistPermArray = Array();
        $profiles = Array();
        $sql = "select distinct profileid from vtiger_profile2tab";
        $result = $adb->query($sql);
        $num_rows = $adb->num_rows($result);
        for ( $i=0; $i < $num_rows; $i++ )
                $profiles[] = $adb->query_result($result,$i,'profileid');

        $persistPermArray = Array();
        foreach ( $profiles as $profileid )
        {
                $sql = "select * from vtiger_profile2tab where profileid=" .$profileid ;
                $result = $adb->query($sql);
                if($MAX_TAB_PER !='')
                {
                        $persistPermArray[$profileid] = array_fill(0,$MAX_TAB_PER,0);
                }
                $num_rows = $adb->num_rows($result);
                for($i=0; $i<$num_rows; $i++)
                {
                        $tabid= $adb->query_result($result,$i,'tabid');
                        $tab_per= $adb->query_result($result,$i,'permissions');
                        $persistPermArray[$profileid][$tabid] = $tab_per;
                }
        }
	$log->debug("Exiting loadAllPerms method ...");
}

/** Function to get all the vtiger_tab permission for the specified vtiger_profile
  * @param $profileid -- Profile Id:: Type integer
  * @returns  TabPermission Array in the following format:
  * $tabPermission = Array($tabid1=>permission,
  *                        $tabid2=>permission, 
  *                                |
  *                        $tabidn=>permission)  
  *
 */
function getAllTabsPermission($profileid)
{
	global $log;
	$log->debug("Entering getAllTabsPermission(".$profileid.") method ...");
	global $persistPermArray;
        global $adb,$MAX_TAB_PER;
        // Mike Crowe Mod --------------------------------------------------------
        if ( $cache_tab_perms )
        {
                if ( count($persistPermArray) == 0 )
                        loadAllPerms();
		$log->debug("Exiting getAllTabsPermission method ...");
                return $persistPermArray[$profileid];
        }
        else
        {
                $sql = "select * from vtiger_profile2tab where profileid=" .$profileid ;
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
		$log->debug("Exiting getAllTabsPermission method ...");
                return $tab_perr_array;
        }
        // Mike Crowe Mod ---------------------------------------------------------------- 

}

/** Function to get all the vtiger_tab permission for the specified vtiger_profile other than tabid 15
  * @param $profileid -- Profile Id:: Type integer
  * @returns  TabPermission Array in the following format:
  * $tabPermission = Array($tabid1=>permission,
  *                        $tabid2=>permission, 
  *                                |
  *                        $tabidn=>permission)  
  *
 */
function getTabsPermission($profileid)
{
	global $log;
	$log->debug("Entering getTabsPermission(".$profileid.") method ...");
	global $persistPermArray;
        global $adb;
        // Mike Crowe Mod -------------------------------------------------------
        if ( $cache_tab_perms )
        {
                if ( count($persistPermArray) == 0 )
                        loadAllPerms();
                $tab_perr_array = $persistPermArray;
                foreach( array(1,3,16,15) as $tabid )
                        $tab_perr_array[$tabid] = 0;
		$log->debug("Exiting getTabsPermission method ...");
                return $tab_perr_array;
        }
        else
        {
                $sql = "select * from vtiger_profile2tab where profileid=" .$profileid;
                $result = $adb->query($sql);
                $tab_perr_array = Array();
                $num_rows = $adb->num_rows($result);
                for($i=0; $i<$num_rows; $i++)
                {
                        $tabid= $adb->query_result($result,$i,'tabid');
                        $tab_per= $adb->query_result($result,$i,'permissions');
                        if($tabid != 3 && $tabid != 16)
                        {
                                $tab_perr_array[$tabid] = $tab_per;
                        }
                }
		$log->debug("Exiting getTabsPermission method ...");
                return $tab_perr_array;
        } 

}

/** Function to get all the vtiger_tab standard action permission for the specified vtiger_profile
  * @param $profileid -- Profile Id:: Type integer
  * @returns  Tab Action Permission Array in the following format:
  * $tabPermission = Array($tabid1=>Array(actionid1=>permission, actionid2=>permission,...,actionidn=>permission), 
  *                        $tabid2=>Array(actionid1=>permission, actionid2=>permission,...,actionidn=>permission),
  *                                |
  *                        $tabidn=>Array(actionid1=>permission, actionid2=>permission,...,actionidn=>permission))  
  *
 */
function getTabsActionPermission($profileid)
{
	global $log;
	$log->debug("Entering getTabsActionPermission(".$profileid.") method ...");
	global $adb;
	$check = Array();
	$temp_tabid = Array();	
	$sql1 = "select * from vtiger_profile2standardpermissions where profileid=".$profileid." and tabid not in(16) order by(tabid)";
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
	$log->debug("Exiting getTabsActionPermission method ...");
	return $check;
}

/** Function to get all the vtiger_tab utility action permission for the specified vtiger_profile
  * @param $profileid -- Profile Id:: Type integer
  * @returns  Tab Utility Action Permission Array in the following format:
  * $tabPermission = Array($tabid1=>Array(actionid1=>permission, actionid2=>permission,...,actionidn=>permission), 
  *                        $tabid2=>Array(actionid1=>permission, actionid2=>permission,...,actionidn=>permission),
  *                                |
  *                        $tabidn=>Array(actionid1=>permission, actionid2=>permission,...,actionidn=>permission))  
  *
 */

function getTabsUtilityActionPermission($profileid)
{
	global $log;
	$log->debug("Entering getTabsUtilityActionPermission(".$profileid.") method ...");

	global $adb;
	$check = Array();
	$temp_tabid = Array();	
	$sql1 = "select * from vtiger_profile2utility where profileid=".$profileid." order by(tabid)";
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

	$log->debug("Exiting getTabsUtilityActionPermission method ...");
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
	global $log;
	$log->debug("Entering getDefaultSharingEditAction() method ...");
	global $adb;
	//retreiving the standard permissions	
	$sql= "select * from vtiger_def_org_share where editstatus=0";
	$result = $adb->query($sql);
	$permissionRow=$adb->fetch_array($result);
	do
	{
		for($j=0;$j<count($permissionRow);$j++)
		{
			$copy[$permissionRow[1]]=$permissionRow[2];
		}

	}while($permissionRow=$adb->fetch_array($result));

	$log->debug("Exiting getDefaultSharingEditAction method ...");
	return $copy;

}
/**This Function returns the Default Organisation Sharing Action Array for modules with edit status in (0,1) 
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
	global $log;
	$log->debug("Entering getDefaultSharingAction() method ...");
	global $adb;
	//retreivin the standard permissions	
	$sql= "select * from vtiger_def_org_share where editstatus in(0,1)";
	$result = $adb->query($sql);
	$permissionRow=$adb->fetch_array($result);
	do
	{
		for($j=0;$j<count($permissionRow);$j++)
		{
			$copy[$permissionRow[1]]=$permissionRow[2];
		}

	}while($permissionRow=$adb->fetch_array($result));
	$log->debug("Exiting getDefaultSharingAction method ...");
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
function getAllDefaultSharingAction()
{
	global $log;
	$log->debug("Entering getAllDefaultSharingAction() method ...");
	global $adb;
	$copy=Array();
	//retreiving the standard permissions	
	$sql= "select * from vtiger_def_org_share";
	$result = $adb->query($sql);
	$num_rows=$adb->num_rows($result);

	for($i=0;$i<$num_rows;$i++)
	{
		$tabid=$adb->query_result($result,$i,'tabid');
		$permission=$adb->query_result($result,$i,'permission');
		$copy[$tabid]=$permission;
		
	}

	$log->debug("Exiting getAllDefaultSharingAction method ...");
	return $copy;

}

/* Deprecated Function. To be removed
 *
 *
*/
function setPermittedTabs2Session($profileid)
{
  global $log;
  $log->debug("Entering setPermittedTabs2Session(".$profileid.") method ...");
  global $adb;
  $sql = "select tabid from vtiger_profile2tab where profileid=" .$profileid ." and permissions =0" ;
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
  $log->debug("Exiting setPermittedTabs2Session method ...");
}

/* Deprecated Function. To be removed
 *
 *
*/
function setPermittedActions2Session($profileid)
{
  global $log;	
  $log->debug("Entering setPermittedActions2Session(".$profileid.") method ...");
  global $adb;
  $check = Array(); 	
  $sql1 = "select tabid from vtiger_profile2tab where profileid=" .$profileid ." and permissions =0" ;
  $result1 = $adb->query($sql1);
  $num_rows1 = $adb->num_rows($result1);
  for($i=0; $i<$num_rows1; $i++)
  {
	$access = Array();
	$tab_id = $adb->query_result($result1,$i,'tabid');
	
	//echo 'tab is '.$tab_id;
	//echo '<BR>';

	//Inserting the Standard Actions into the Array	
	$sql= "select * from vtiger_profile2standardpermissions where profileid =".$profileid." and tabid=".$tab_id;
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
	$sql2= "select * from vtiger_profile2utility where profileid =".$profileid." and tabid=".$tab_id;
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
 $log->debug("Entering setPermittedActions2Session method ...");
}

/* Deprecated Function. To be removed
 *
 *
*/
function setPermittedDefaultSharingAction2Session($profileid)
{
	global $log;
	$log->debug("Entering setPermittedDefaultSharingAction2Session(".$profileid.") method ...");
	global $adb;
	//retreiving the standard permissions	
	//$sql= "select default_org_sharingrule.* from default_org_sharingrule inner join vtiger_profile2tab on vtiger_profile2tab.tabid = default_org_sharingrule.tabid where vtiger_profile2tab.permissions =0 and vtiger_profile2tab.profileid=".$profileid;
	$sql = "select * from vtiger_def_org_share";
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
	$log->debug("Entering setPermittedDefaultSharingAction2Session method ...");
}


/** Function to create the vtiger_role
  * @param $roleName -- Role Name:: Type varchar
  * @param $parentRoleId -- Parent Role Id:: Type varchar
  * @param $roleProfileArray -- Profile to be associated with this vtiger_role:: Type Array
  * @returns  the Rold Id :: Type varchar
  *
 */

function createRole($roleName,$parentRoleId,$roleProfileArray)
{
	global $log;
	$log->debug("Entering createRole(".$roleName.",".$parentRoleId.") method ...");
	global $adb;
	$parentRoleDetails=getRoleInformation($parentRoleId);
	$parentRoleInfo=$parentRoleDetails[$parentRoleId];
	$roleid_no=$adb->getUniqueId("vtiger_role");
        $roleId='H'.$roleid_no;
        $parentRoleHr=$parentRoleInfo[1];
        $parentRoleDepth=$parentRoleInfo[2];
        $nowParentRoleHr=$parentRoleHr.'::'.$roleId;
        $nowRoleDepth=$parentRoleDepth + 1;

	//Inserting vtiger_role into db
	$query="insert into vtiger_role values('".$roleId."','".$roleName."','".$nowParentRoleHr."',".$nowRoleDepth.")";
	$adb->query($query);

	//Inserting into vtiger_role2profile vtiger_table
	foreach($roleProfileArray as $profileId)
        {
                if($profileId != '')
                {
                        insertRole2ProfileRelation($roleId,$profileId);
                }
        }

	$log->debug("Exiting createRole method ...");
	return $roleId;

}

/** Function to update the vtiger_role
  * @param $roleName -- Role Name:: Type varchar
  * @param $roleId -- Role Id:: Type varchar
  * @param $roleProfileArray -- Profile to be associated with this vtiger_role:: Type Array
  *
 */
function updateRole($roleId,$roleName,$roleProfileArray)
{
	global $log;
	$log->debug("Entering updateRole(".$roleId.",".$roleName.") method ...");
	global $adb;
	$sql1 = "update vtiger_role set rolename='".$roleName."' where roleid='".$roleId."'";
        $adb->query($sql1);
	//Updating the Role2Profile relation
	$sql2 = "delete from vtiger_role2profile where roleId='".$roleId."'";
	$adb->query($sql2);

	foreach($roleProfileArray as $profileId)
        {
                if($profileId != '')
                {
                        insertRole2ProfileRelation($roleId,$profileId);
                }
        }
	$log->debug("Exiting updateRole method ...");
	
}

/** Function to add the vtiger_role to vtiger_profile relation
  * @param $profileId -- Profile Id:: Type integer
  * @param $roleId -- Role Id:: Type varchar
  *
 */
function insertRole2ProfileRelation($roleId,$profileId)
{
	global $log;
	$log->debug("Entering insertRole2ProfileRelation(".$roleId.",".$profileId.") method ...");
	global $adb;
	$query="insert into vtiger_role2profile values('".$roleId."',".$profileId.")";
	$adb->query($query);	
	$log->debug("Exiting insertRole2ProfileRelation method ...");
	
}


/** Deprecated Function. To be removed
  *
  *
  *
*/
function createNewGroup($groupName,$groupDescription)
{
	global $log;
	$log->debug("Entering createNewGroup(".$groupName.",".$groupDescription.") method ...");
	global $adb;
	$sql = "insert into vtiger_groups(name,description) values('" .$groupName ."','". $groupDescription ."')";
	$result = $adb->query($sql); 
	$log->debug("Exiting createNewGroup method ...");
	header("Location: index.php?module=Settings&action=listgroups");
	
}


/** Deprecated Function. To be removed
  *
  *
  *
*/
function fetchTabId($moduleName)
{
	global $log;
	$log->debug("Entering fetchTabId(".$moduleName.") method ...");
	global $adb;
	$sql = "select id from vtiger_tab where name ='" .$moduleName ."'";
	$result = $adb->query($sql); 
	$tabid =  $adb->query_result($result,0,"id");
	$log->debug("Exiting fetchTabId method ...");
	return $tabid;

}

/** Deprecated Function. To be removed
  *
  *
  *
*/
function populatePermissions4NewRole($parentroleName,$roleName)
{
	global $log;
	$log->debug("Entering populatePermissions4NewRole(".$parentroleName.",".$roleName.") method ...");
  global $adb;
  //fetch the permissions for the parent vtiger_role
  $referenceValues = fetchTabReferenceEntityValues($parentroleName);

  while($permissionRow = $adb->fetch_array($referenceValues))
  {
    $sql_insert="insert into vtiger_role2tab(rolename,tabid,module_permission,description) values('" .$roleName ."'," .$permissionRow['tabid'] ."," .$permissionRow['module_permission'] .", '')";

    //echo $sql_insert;
    $adb->query($sql_insert);
  }

  $actionreferenceValues = fetchActionReferenceEntityValues($parentroleName);
  while($permissionRow = $adb->fetch_array($actionreferenceValues))
  {
    $sql_insert="insert into vtiger_role2action(rolename,tabid,actionname,action_permission,description) values('" .$roleName ."'," .$permissionRow['tabid'] .",'" .$permissionRow['actionname'] ."'," .$permissionRow['action_permission'] .", '')";
    //echo $sql_insert;
    $adb->query($sql_insert);
  }
	$log->debug("Exiting populatePermissions4NewRole method ...");
  
}

/** Deprecated Function. To be removed
  *
  *
  *
*/
function fetchTabReferenceEntityValues($parentrolename)
{
	global $log;
	$log->debug("Entering fetchTabReferenceEntityValues(".$parentrolename.") method ...");
  global $adb;
  $sql = "select tabid,module_permission,description from vtiger_role2tab where rolename='" .$parentrolename ."'"; 
  //echo $sql;
  $result=$adb->query($sql);
$log->debug("Exiting fetchTabReferenceEntityValues method ...");
  return $result;

}


/** Deprecated Function. To be removed
  *
  *
  *
*/
function fetchActionReferenceEntityValues($parentrolename)
{
	global $log;
$log->debug("Entering fetchActionReferenceEntityValues(".$parentrolename.") method ...");
  global $adb;
  $sql = "select tabid,actionname,action_permission,description from vtiger_role2action where rolename='" .$parentrolename ."'"; 
    $result=$adb->query($sql);
$log->debug("Exiting fetchActionReferenceEntityValues method ...");
  return $result;
}

/** Function to get the vtiger_roleid from vtiger_rolename
  * @param $rolename -- Role Name:: Type varchar
  * @returns Role Id:: Type varchar
  *
 */
function fetchRoleId($rolename)
{
global $log;
$log->debug("Entering fetchRoleId(".$rolename.") method ...");

  global $adb;
  $sqlfetchroleid = "select roleid from vtiger_role where rolename='".$rolename ."'";
  $resultroleid = $adb->query($sqlfetchroleid);
  $role_id = $adb->query_result($resultroleid,0,"roleid");
$log->debug("Exiting fetchRoleId method ...");
  return $role_id;
}

/** Function to update user to vtiger_role mapping based on the userid
  * @param $roleid -- Role Id:: Type varchar
  * @param $userid User Id:: Type integer
  *
 */
function updateUser2RoleMapping($roleid,$userid)
{
global $log;
$log->debug("Entering updateUser2RoleMapping(".$roleid.",".$userid.") method ...");
  global $adb;
  //Check if row already exists
  $sqlcheck = "select * from vtiger_user2role where userid=".$userid;
  $resultcheck = $adb->query($sqlcheck);
  if($adb->num_rows($resultcheck) == 1)
  {
  	$sqldelete = "delete from vtiger_user2role where userid=".$userid;
  	$result_delete = $adb->query($sqldelete);
  }	
  $sql = "insert into vtiger_user2role(userid,roleid) values(" .$userid .",'" .$roleid ."')";
  $result = $adb->query($sql);
	$log->debug("Exiting updateUser2RoleMapping method ...");

}


/** Function to update user to group mapping based on the userid
  * @param $groupname -- Group Name:: Type varchar
  * @param $userid User Id:: Type integer
  *
 */
function updateUsers2GroupMapping($groupname,$userid)
{
global $log;
$log->debug("Entering updateUsers2GroupMapping(".$groupname.",".$userid.") method ...");
  global $adb;
  $sqldelete = "delete from vtiger_users2group where userid = '" .$userid ."'";
  $result_delete = $adb->query($sqldelete);
  $sql = "insert into vtiger_users2group(groupname,userid) values('" .$groupname ."','" .$userid ."')";
  $result = $adb->query($sql);
  $log->debug("Exiting updateUsers2GroupMapping method ...");
}

/** Function to add user to vtiger_role mapping 
  * @param $roleid -- Role Id:: Type varchar
  * @param $userid User Id:: Type integer
  *
 */
function insertUser2RoleMapping($roleid,$userid)
{
global $log;
$log->debug("Entering insertUser2RoleMapping(".$roleid.",".$userid.") method ...");

  global $adb;	
  $sql = "insert into vtiger_user2role(userid,roleid) values('" .$userid ."','" .$roleid ."')";
 $adb->query($sql); 
$log->debug("Exiting insertUser2RoleMapping method ...");

}

/** Function to add user to group mapping 
  * @param $groupname -- Group Name:: Type varchar
  * @param $userid User Id:: Type integer
  *
 */
function insertUsers2GroupMapping($groupname,$userid)
{
global $log;
$log->debug("Entering insertUsers2GroupMapping(".$groupname.",".$userid.") method ...");
  global $adb;
  $sql = "insert into vtiger_users2group(groupname,userid) values('" .$groupname ."','" .$userid ."')";
  $adb->query($sql);
$log->debug("Exiting insertUsers2GroupMapping method ...");
}

/** Function to get the word template resultset 
  * @param $module -- Module Name:: Type varchar
  * @returns Type:: resultset
  *
 */
function fetchWordTemplateList($module)
{
global $log;
$log->debug("Entering fetchWordTemplateList(".$module.") method ...");
  global $adb;
  $sql_word = "select templateid, filename from vtiger_wordtemplates where module ='".$module."'" ; 
  $result=$adb->query($sql_word);
$log->debug("Exiting fetchWordTemplateList method ...");
  return $result;
}



/** Function to get the email template iformation 
  * @param $templateName -- Template Name:: Type varchar
  * @returns Type:: resultset
  *
 */
function fetchEmailTemplateInfo($templateName)
{
global $log;
$log->debug("Entering fetchEmailTemplateInfo(".$templateName.") method ...");
	global $adb;
        $sql= "select * from vtiger_emailtemplates where templatename='" .$templateName ."'";
        $result = $adb->query($sql);
$log->debug("Exiting fetchEmailTemplateInfo method ...");
        return $result;
}

/** Function to substitute the tokens in the specified file 
  * @param $templateName -- Template Name:: Type varchar
  * @param $globals
  *
 */
function substituteTokens($filename,$globals)
{
global $log;
$log->debug("Entering substituteTokens(".$filename.",".$globals.") method ...");
	$log->debug("in substituteTokens method  with filename ".$filename.' and content globals as '.$globals);

	global $root_directory;
	//$globals = implode(",\\$",$tokens);
    
	if (!$filename)
	 {

	$log->debug("filename is not set in substituteTokens");
		 $filename = $this->filename;
	$log->debug("filename is not set in substituteTokens so taking default filename");
	 }
	
    if (!$dump = file ($filename))
	 {
		 $log->debug("not able to create the file or get access to the file with filename ".$filename." so returning 0");
		 $log->debug("Exiting substituteTokens method ...");
     		 return 0;
    	 }	

	 $log->debug("about to start replacing the tokens");
      require_once($root_directory .'/modules/Emails/templates/testemailtemplateusage.php');
      eval ("global $globals; ");
    while (list($key,$val) = each($dump))
    {
	$replacedString ;
      if (ereg( "\$",$val)) 
	{
        $val = addslashes ($val);      
	$log->debug("token is ".$val);
        eval(  "\$val = \"$val\";");
        $val = stripslashes ($val);
	$replacedString .= $val;
      }
    }

	$log->debug("the replacedString  is ".$replacedString);
	$log->debug("Exiting substituteTokens method ...");
	return $replacedString;
}


/** Function to add module group relation 
  * @param $module -- module name:: Type varchar
  * @param $groupname -- Group Name:: Type varchar
  *
 */
 function insertIntoGroupRelation($module,$moduleid,$groupname)
 {
	 global $log;
	 $log->debug("Entering insertIntoGroupRelation(".$module.",".$moduleid.",".$groupname.") method ...");
	 global $adb;

	 //Get the module grouptable name and the
	require_once("modules/$module/$module.php");
	$modObj = new $module();	 
	 
	 $sql = "insert into ".$modObj->groupTable[0]." values (" .$moduleid .",'".$groupname."')";
	 
	 $adb->query($sql);
	 $log->debug("Exiting insert2LeadGroupRelation method ...");

 }

/** Function to update lead group relation
  * @param $module -- module name:: Type varchar	
  * @param $leadid -- Lead Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */
function updateModuleGroupRelation($module,$moduleid,$groupname)
{
	global $log;
	$log->debug("Entering updateModuleGroupRelation(".$moduleid.",".$groupname.") method ...");
 	global $adb;
	
	//Get the module grouptable name and the
	require_once("modules/$module/$module.php");
	$modObj = new $module();

 	//Deleting the existing entry	 
  	$sqldelete = "delete from ".$modObj->groupTable[0]." where " .$modObj->groupTable[1] ."=".$moduleid;
  	$adb->query($sqldelete);
	if($groupname != ""){	
	  	$sql = "insert into ".$modObj->groupTable[0]." values (".$moduleid .",'" .$groupname ."')";  
	  	$adb->query($sql);
	}	
  	$log->debug("Exiting updateLeadGroupRelation method ...");

}
 


/** Function to add lead group relation 
  * @param $leadid -- Lead Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */
function insert2LeadGroupRelation($leadid,$groupname)
{
	global $log;
	$log->debug("Entering insert2LeadGroupRelation(".$leadid.",".$groupname.") method ...");
global $adb;
  $sql = "insert into vtiger_leadgrouprelation values (" .$leadid .",'".$groupname."')";
  $adb->query($sql);
	$log->debug("Exiting insert2LeadGroupRelation method ...");

}

/** Function to update lead group relation 
  * @param $leadid -- Lead Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */
function updateLeadGroupRelation($leadid,$groupname)
{
global $log;
$log->debug("Entering updateLeadGroupRelation(".$leadid.",".$groupname.") method ...");
 global $adb;
  $sqldelete = "delete from vtiger_leadgrouprelation where leadid=".$leadid;
  $adb->query($sqldelete);
  $sql = "insert into vtiger_leadgrouprelation values (".$leadid .",'" .$groupname ."')";  
  $adb->query($sql);
  $log->debug("Exiting updateLeadGroupRelation method ...");

}

/** Function to add Account group relation 
  * @param $accountid -- Account Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */
function insert2AccountGroupRelation($accountid,$groupname)
{
global $log;
$log->debug("Entering insert2AccountGroupRelation(".$accountid.",".$groupname.") method ...");
global $adb;
  $sql = "insert into vtiger_accountgrouprelation values (" .$accountid .",'".$groupname."')";
  $adb->query($sql);
$log->debug("Exiting insert2AccountGroupRelation method ...");

}

/** Function to update Account group relation
  * @param $accountid -- Account Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */

function updateAccountGroupRelation($accountid,$groupname)
{
global $log;
$log->debug("Entering updateAccountGroupRelation(".$accountid.",".$groupname.") method ...");
 global $adb;
  $sqldelete = "delete from vtiger_accountgrouprelation where accountid=".$accountid;
  $adb->query($sqldelete);
  $sql = "insert into vtiger_accountgrouprelation values (".$accountid .",'" .$groupname ."')";
  $adb->query($sql);
$log->debug("Exiting updateAccountGroupRelation method ...");

}

/** Function to add Contact group relation
  * @param $contactid -- Contact Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */
function insert2ContactGroupRelation($contactid,$groupname)
{
global $log;
$log->debug("Entering insert2ContactGroupRelation(".$contactid.",".$groupname.") method ...");
global $adb;
  $sql = "insert into vtiger_contactgrouprelation values (" .$contactid .",'".$groupname."')";
  $adb->query($sql);
$log->debug("Exiting insert2ContactGroupRelation method ...");

}

/** Function to update contact group relation
  * @param $contactid -- Contact Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */

function updateContactGroupRelation($contactid,$groupname)
{
global $log;
$log->debug("Entering updateContactGroupRelation(".$contactid.",".$groupname.") method ...");
 global $adb;
  $sqldelete = "delete from vtiger_contactgrouprelation where contactid=".$contactid;
  $adb->query($sqldelete);
  $sql = "insert into vtiger_contactgrouprelation values (".$contactid .",'" .$groupname ."')";
  $adb->query($sql);
$log->debug("Exiting updateContactGroupRelation method ...");

}
/** Function to add Potential group relation
  * @param $potentialid -- Potential Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */
function insert2PotentialGroupRelation($potentialid,$groupname)
{
global $log;
$log->debug("Entering insert2PotentialGroupRelation(".$potentialid.",".$groupname.") method ...");
global $adb;
  $sql = "insert into vtiger_potentialgrouprelation values (" .$potentialid .",'".$groupname."')";
  $adb->query($sql);
  $log->debug("Exiting insert2PotentialGroupRelation method ...");

}

/** Function to update Potential group relation
  * @param $potentialid -- Potential Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */

function updatePotentialGroupRelation($potentialid,$groupname)
{
global $log;
$log->debug("Entering updatePotentialGroupRelation(".$potentialid.",".$groupname.") method ...");
 global $adb;
  $sqldelete = "delete from vtiger_potentialgrouprelation where potentialid=".$potentialid;
  $adb->query($sqldelete);
  $sql = "insert into vtiger_potentialgrouprelation values (".$potentialid .",'" .$groupname ."')";
  $adb->query($sql);
  $log->debug("Exiting updatePotentialGroupRelation method ...");

}

/** Function to add Quote group relation
  * @param $quoteid -- Quote Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */
function insert2QuoteGroupRelation($quoteid,$groupname)
{
global $log;
$log->debug("Entering insert2QuoteGroupRelation(".$quoteid.",".$groupname.") method ...");
global $adb;
  $sql = "insert into vtiger_quotegrouprelation values (" .$quoteid .",'".$groupname."')";
  $adb->query($sql);
$log->debug("Exiting insert2QuoteGroupRelation method ...");

}

/** Function to update Quote group relation
  * @param $quoteid -- Quote Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */

function updateQuoteGroupRelation($quoteid,$groupname)
{
global $log;
$log->debug("Entering updateQuoteGroupRelation(".$quoteid.",".$groupname.") method ...");
 global $adb;
  $sqldelete = "delete from vtiger_quotegrouprelation where quoteid=".$quoteid;
  $adb->query($sqldelete);
  $sql = "insert into vtiger_quotegrouprelation values (".$quoteid .",'" .$groupname ."')";
  $adb->query($sql);
$log->debug("Exiting updateQuoteGroupRelation method ...");

}
/** Function to add Salesorder group relation
  * @param $salesorderid -- Salesorder Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */
function insert2SoGroupRelation($salesorderid,$groupname)
{
global $log;
$log->debug("Entering insert2SoGroupRelation(".$salesorderid.",".$groupname.") method ...");
global $adb;
  $sql = "insert into vtiger_sogrouprelation values (" .$salesorderid .",'".$groupname."')";
  $adb->query($sql);
$log->debug("Exiting insert2SoGroupRelation method ...");

}

/** Function to update Salesorder group relation
  * @param $salesorderid -- Salesorder Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */

function updateSoGroupRelation($salesorderid,$groupname)
{
global $log;
$log->debug("Entering updateSoGroupRelation(".$salesorderid.",".$groupname.") method ...");
 global $adb;
  $sqldelete = "delete from vtiger_sogrouprelation where salesorderid=".$salesorderid;
  $adb->query($sqldelete);
  $sql = "insert into vtiger_sogrouprelation values (".$salesorderid .",'" .$groupname ."')";
  $adb->query($sql);
$log->debug("Exiting updateSoGroupRelation method ...");

}

/** Function to add Invoice group relation
  * @param $invoiceid -- Invoice Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */
function insert2InvoiceGroupRelation($invoiceid,$groupname)
{
global $log;
$log->debug("Entering insert2InvoiceGroupRelation(".$invoiceid.",".$groupname.") method ...");
global $adb;
  $sql = "insert into vtiger_invoicegrouprelation values (" .$invoiceid .",'".$groupname."')";
  $adb->query($sql);
$log->debug("Exiting insert2InvoiceGroupRelation method ...");

}

/** Function to update Invoice group relation
  * @param $invoiceid -- Invoice Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */

function updateInvoiceGroupRelation($invoiceid,$groupname)
{
global $log;
$log->debug("Entering updateInvoiceGroupRelation(".$invoiceid.",".$groupname.") method ...");
 global $adb;
  $sqldelete = "delete from vtiger_invoicegrouprelation where invoiceid=".$invoiceid;
  $adb->query($sqldelete);
  $sql = "insert into vtiger_invoicegrouprelation values (".$invoiceid .",'" .$groupname ."')";
  $adb->query($sql);
$log->debug("Exiting updateInvoiceGroupRelation method ...");

}

/** Function to add PurchaseOrder group relation
  * @param $poid -- PurchaseOrder Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */
function insert2PoGroupRelation($poid,$groupname)
{
global $log;
$log->debug("Entering insert2PoGroupRelation(".$poid.",".$groupname.") method ...");
global $adb;
  $sql = "insert into vtiger_pogrouprelation values (" .$poid .",'".$groupname."')";
  $adb->query($sql);
$log->debug("Exiting insert2PoGroupRelation method ...");

}

/** Function to update PurchaseOrder group relation
  * @param $poid -- Purchaseorder Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */

function updatePoGroupRelation($poid,$groupname)
{
global $log;
$log->debug("Entering updatePoGroupRelation(".$poid.",".$groupname.") method ...");
 global $adb;
  $sqldelete = "delete from vtiger_pogrouprelation where purchaseorderid=".$poid;
  $adb->query($sqldelete);
  $sql = "insert into vtiger_pogrouprelation values (".$poid .",'" .$groupname ."')";
  $adb->query($sql);
$log->debug("Exiting updatePoGroupRelation method ...");

}


/** Function to update ticket group relation 
  * @param $ticketid -- Ticket Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */
function updateTicketGroupRelation($ticketid,$groupname)
{
global $log;
$log->debug("Entering updateTicketGroupRelation(".$ticketid.",".$groupname.") method ...");
 global $adb;
  $sqldelete = "delete from vtiger_ticketgrouprelation where ticketid=".$ticketid;
  $adb->query($sqldelete);
  $sql = "insert into vtiger_ticketgrouprelation values (".$ticketid .",'" .$groupname ."')";  
  $adb->query($sql);
$log->debug("Exiting updateTicketGroupRelation method ...");

}


/** Function to insert vtiger_activity group relation 
  * @param $activityid -- Activity Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */
function insert2ActivityGroupRelation($activityid,$groupname)
{
global $log;
$log->debug("Entering insert2ActivityGroupRelation(".$activityid.",".$groupname.") method ...");
global $adb;
  $sql = "insert into vtiger_activitygrouprelation values (" .$activityid .",'".$groupname."')";
  $adb->query($sql);
$log->debug("Exiting insert2ActivityGroupRelation method ...");

}


/** Function to insert ticket group relation 
  * @param $ticketid -- Ticket Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */
function insert2TicketGroupRelation($ticketid,$groupname)
{
global $log;
$log->debug("Entering insert2TicketGroupRelation(".$ticketid.",".$groupname.") method ...");
global $adb;
  $sql = "insert into vtiger_ticketgrouprelation values (" .$ticketid .",'".$groupname."')";
  $adb->query($sql);
$log->debug("Exiting insert2TicketGroupRelation method ...");

}

/** Function to update vtiger_activity group relation 
  * @param $activityid -- Activity Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */
function updateActivityGroupRelation($activityid,$groupname)
{
global $log;
$log->debug("Entering updateActivityGroupRelation(".$activityid.",".$groupname.") method ...");
	global $adb;
  $sqldelete = "delete from vtiger_activitygrouprelation where activityid=".$activityid;
  if($groupname != '' && $groupname != 'null') {
	  $adb->query($sqldelete);
	  $sql = "insert into vtiger_activitygrouprelation values (".$activityid .",'" .$groupname ."')";  
	  $adb->query($sql);
  }
  $log->debug("Exiting updateActivityGroupRelation method ...");

}



/** Function to be depricated
 *
 *
 *
*/
function getFieldList($fld_module, $profileid)
{
	global $log;
	$log->debug("Entering getFieldList(".$fld_module.",". $profileid.") method ...");
        global $adb;
        if($fld_module == "Accounts")
        {
                $tabid = 5;
        }
        $query = "select * from vtiger_profile2field where profileid =".$profileid." and tabid=".$tabid;
        //echo $query;
        $result = $adb->query($query);
	$log->debug("Exiting getFieldList method ...");
        return $result;
}


/** Function to be depricated
 *
 *
 *
*/
function getFieldVisibilityArray($fld_module, $profileid)
{
	global $log;
	$log->debug("Entering getFieldVisibilityArray(".$fld_module.",". $profileid.") method ...");
	global $adb;
        if($fld_module == "Accounts")
        {
                $tabid = 5;
        }
        $query = "select * from vtiger_profile2field where profileid =".$profileid." and tabid=".$tabid;
        //echo $query;
        $result = $adb->query($query);
	$fldVisbArray = Array();
	$noofrows = $adb->num_rows($fieldListResult);
	for($i=0; $i<$noofrows; $i++)
	{
		$fld_name = $adb->query_result($fieldListResult,$i,"fieldname");
		$fldVisbArray[$fld_name] = $adb->query_result($fieldListResult,$i,"visible");	
	}
	$log->debug("Exiting getFieldVisibilityArray method ...");
	return $fldVisbArray;	
	
}


/** Function to be depricated
 *
 *
 *
*/
function getFieldReadOnlyArray($fld_module, $profileid)
{
	global $log;
	$log->debug("Entering getFieldReadOnlyArray(".$fld_module.",". $profileid.") method ...");
	global $adb;
        if($fld_module == "Accounts")
        {
                $tabid = 5;
        }
        $query = "select * from vtiger_profile2field where profileid =".$profileid." and tabid=".$tabid;
        //echo $query;
        $result = $adb->query($query);
	$fldReadOnlyArray = Array();
	$noofrows = $adb->num_rows($fieldListResult);
	for($i=0; $i<$noofrows; $i++)
	{
		$fld_name = $adb->query_result($fieldListResult,$i,"fieldname");
		$fldReadOnlyArray[$fld_name] = $adb->query_result($fieldListResult,$i,"readonly");	
	}
	
	$log->debug("Exiting getFieldReadOnlyArray method ...");
	return $fldReadOnlyArray;	
}


/** Function to get the vtiger_role name from the vtiger_roleid 
  * @param $roleid -- Role Id:: Type varchar
  * @returns $rolename -- Role Name:: Type varchar
  *
 */
function getRoleName($roleid)
{
	global $log;
	$log->debug("Entering getRoleName(".$roleid.") method ...");
	global $adb;
	$sql1 = "select * from vtiger_role where roleid='".$roleid."'";
	$result = $adb->query($sql1);
	$rolename = $adb->query_result($result,0,"rolename");
	$log->debug("Exiting getRoleName method ...");
	return $rolename;	
}

/** Function to get the vtiger_profile name from the vtiger_profileid 
  * @param $profileid -- Profile Id:: Type integer
  * @returns $rolename -- Role Name:: Type varchar
  *
 */
function getProfileName($profileid)
{
	global $log;
	$log->debug("Entering getProfileName(".$profileid.") method ...");
	global $adb;
	$sql1 = "select * from vtiger_profile where profileid=".$profileid;
	$result = $adb->query($sql1);
	$profilename = $adb->query_result($result,0,"profilename");
	$log->debug("Exiting getProfileName method ...");
	return $profilename;	
}
/** Function to get the vtiger_profile Description from the vtiger_profileid
  * @param $profileid -- Profile Id:: Type integer
  * @returns $rolename -- Role Name:: Type varchar
  *
 */
function getProfileDescription($profileid)
{
        global $log;
        $log->debug("Entering getProfileDescription(".$profileid.") method ...");
        global $adb;
        $sql1 = "select  description from vtiger_profile where profileid=".$profileid;
        $result = $adb->query($sql1);
        $profileDescription = $adb->query_result($result,0,"description");
        $log->debug("Exiting getProfileDescription method ...");
        return $profileDescription;
}


/** Function to check if the currently logged in user is permitted to perform the specified action  
  * @param $module -- Module Name:: Type varchar
  * @param $actionname -- Action Name:: Type varchar
  * @param $recordid -- Record Id:: Type integer
  * @returns yes or no. If Yes means this action is allowed for the currently logged in user. If no means this action is not allowed for the currently logged in user 
  *
 */
function isPermitted($module,$actionname,$record_id='')
{

	global $log;
	$log->debug("Entering isPermitted(".$module.",".$actionname.",".$record_id.") method ...");

	global $adb;
	global $current_user;
	global $seclog;
	require('user_privileges/user_privileges_'.$current_user->id.'.php');
	require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
	$permission = "no";
	if($module == 'Users' || $module == 'Home' || $module == 'uploads')
	{
		//These modules dont have security right now
		$permission = "yes";
		$log->debug("Exiting isPermitted method ...");
		return $permission;

	}
	
	//Checking the Access for the Settings Module
	if($module == 'Settings' || $module == 'Administration')
	{
		if(! $is_admin)
		{
			$permission = "no";
		}
		else
		{
			$permission = "yes";
		}
		$log->debug("Exiting isPermitted method ...");
		return $permission;
	}

	//Checking whether the user is admin
	if($is_admin)
	{
		$permission ="yes";
		$log->debug("Exiting isPermitted method ...");
		return $permission;
	}
	//Retreiving the Tabid and Action Id	
	$tabid = getTabid($module);
	$actionid=getActionid($actionname);
	//If no actionid, then allow action is vtiger_tab permission is available	
	if($actionid == '')
	{
		if($profileTabsPermission[$tabid] ==0)
        	{	
                	$permission = "yes";
			$log->debug("Exiting isPermitted method ...");
        	}
		else
		{
			$permission ="no";
		}
               	return $permission;
		
	}	

	$action = getActionname($actionid);
	//Checking for view all permission
	if($profileGlobalPermission[1] ==0 || $profileGlobalPermission[2] ==0)
	{	
		if($actionid == 3 || $actionid == 4)
		{
			$permission = "yes";
			$log->debug("Exiting isPermitted method ...");
			return $permission;

		}
	}
	//Checking for edit all permission
	if($profileGlobalPermission[2] ==0)
	{	
		if($actionid == 3 || $actionid == 4 || $actionid ==0 || $actionid ==1)
		{
			$permission = "yes";
			$log->debug("Exiting isPermitted method ...");
			return $permission;

		}
	}
	//Checking for vtiger_tab permission
	if($profileTabsPermission[$tabid] !=0)
	{
		$permission = "no";
		$log->debug("Exiting isPermitted method ...");
		return $permission;
	}
	//Checking for Action Permission
		//echo '<BR>';
		//echo '******* '.$actionid.'  ***********'.$tabid.'%%%%%%%%%%%%%%%%%%%%%%%'.$profileActionPermission[$tabid][$actionid];
		//echo '<BR>';
	if(strlen($profileActionPermission[$tabid][$actionid]) <  1 && $profileActionPermission[$tabid][$actionid] == '')
	{
		$permission = "yes";
		$log->debug("Exiting isPermitted method ...");
		return $permission;
	}	
	
	if($profileActionPermission[$tabid][$actionid] != 0 && $profileActionPermission[$tabid][$actionid] != '')
	{
		$permission = "no";
		$log->debug("Exiting isPermitted method ...");
		return $permission;
	}
	//Checking and returning true if recorid is null
	if($record_id == '')
	{
		$permission = "yes";
		$log->debug("Exiting isPermitted method ...");
		return $permission;
	}

	//If modules is Notes,Products,Vendors,Faq,PriceBook then no sharing			
	if($record_id != '')
	{
		//if($module == 'Notes' || $module == 'Products' || $module == 'Faq' || $module == 'Vendors'  || $module == 'PriceBooks')
		if(getTabOwnedBy($module) == 1)
		{
			$permission = "yes";
			$log->debug("Exiting isPermitted method ...");
			return $permission;			
		}
	}
	
	//Retreiving the RecordOwnerId
	$recOwnType='';
	$recOwnId='';	
	$recordOwnerArr=getRecordOwnerId($record_id);
	foreach($recordOwnerArr as $type=>$id)
	{
		$recOwnType=$type;
		$recOwnId=$id;
	}	
	//Retreiving the default Organisation sharing Access	
	$others_permission_id = $defaultOrgSharingPermission[$tabid];

	if($recOwnType == 'Users')
	{
		//Checking if the Record Owner is the current User
		if($current_user->id == $recOwnId)
		{
			$permission = "yes";
			$log->debug("Exiting isPermitted method ...");
			return $permission;
		}
		//Checking if the Record Owner is the Subordinate User
		foreach($subordinate_roles_users as $roleid=>$userids)
		{
			if(in_array($recOwnId,$userids))
			{
				$permission='yes';
				$log->debug("Exiting isPermitted method ...");
				return $permission;
			}

		}
		

	}
	elseif($recOwnType == 'Groups')
	{
		//Checking if the record owner is the current user's group
		if(in_array($recOwnId,$current_user_groups))
		{
			$permission='yes';
			$log->debug("Exiting isPermitted method ...");
			return $permission;
		}	 
	}	
	
	//Checking for Default Org Sharing permission
	if($others_permission_id == 0)
	{
		if($actionid == 1 || $actionid == 0)
		{

			if($module == 'Calendar')
			{
				if($recOwnType == 'Users')
				{
					$permission = isCalendarPermittedBySharing($record_id);
				}
				else
				{
					$permission='no'; 
				}		
			}
			else
			{
				$permission = isReadWritePermittedBySharing($module,$tabid,$actionid,$record_id);
			}		
			$log->debug("Exiting isPermitted method ...");
			return $permission;	
		}
		elseif($actionid == 2)
		{
			$permission = "no";
			$log->debug("Exiting isPermitted method ...");
			return $permission;
		}
		else
		{
			$permission = "yes";
			$log->debug("Exiting isPermitted method ...");
			return $permission;
		}
	}
	elseif($others_permission_id == 1)
	{
		if($actionid == 2)
		{
			$permission = "no";
			$log->debug("Exiting isPermitted method ...");
			return $permission;
		}
		else
		{
			$permission = "yes";
			$log->debug("Exiting isPermitted method ...");
			return $permission;
		}
	}
	elseif($others_permission_id == 2)
	{

		$permission = "yes";
		$log->debug("Exiting isPermitted method ...");
		return $permission;
	}
	elseif($others_permission_id == 3)
	{
		
		if($actionid == 3 || $actionid == 4)
		{
			if($module == 'Calendar')
			{
				if($recOwnType == 'Users')
				{
					$permission = isCalendarPermittedBySharing($record_id);
				}
				else
				{
					$permission='no'; 
				}		
			}
			else
			{
				$permission = isReadPermittedBySharing($module,$tabid,$actionid,$record_id);
			}	
			$log->debug("Exiting isPermitted method ...");
			return $permission;	
		}
		elseif($actionid ==0 || $actionid ==1)
		{
			if($module == 'Calendar')
			{
				if($recOwnType == 'Users')
				{
					$permission = isCalendarPermittedBySharing($record_id);
				}
				else
				{
					$permission='no'; 
				}		
			}
			else
			{
				$permission = isReadWritePermittedBySharing($module,$tabid,$actionid,$record_id);
			}	
			$log->debug("Exiting isPermitted method ...");
			return $permission;	
		}
		elseif($actionid ==2)
		{
				$permission ="no";
				return $permission;	
		}		
		else
		{
			$permission = "yes";
			$log->debug("Exiting isPermitted method ...");
			return $permission;
		}
	}
	else
	{
		$permission = "yes";	
	}			

	$log->debug("Exiting isPermitted method ...");
	return $permission;

}

/** Function to check if the currently logged in user has Read Access due to Sharing for the specified record  
  * @param $module -- Module Name:: Type varchar
  * @param $actionid -- Action Id:: Type integer
  * @param $recordid -- Record Id:: Type integer
  * @param $tabid -- Tab Id:: Type integer
  * @returns yes or no. If Yes means this action is allowed for the currently logged in user. If no means this action is not allowed for the currently logged in user 
 */
function isReadPermittedBySharing($module,$tabid,$actionid,$record_id)
{
	global $log;
	$log->debug("Entering isReadPermittedBySharing(".$module.",".$tabid.",".$actionid.",".$record_id.") method ...");
	global $adb;
	global $current_user;
	require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
	$ownertype='';
	$ownerid='';
	$sharePer='no';

	$sharingModuleList=getSharingModuleList();
	if(! in_array($module,$sharingModuleList))
	{
		$sharePer='no';
		return $sharePer;
	}

	$recordOwnerArr=getRecordOwnerId($record_id);
	foreach($recordOwnerArr as $type=>$id)
	{
		$ownertype=$type;
		$ownerid=$id;
	}

	$varname=$module."_share_read_permission";
	$read_per_arr=$$varname;
	if($ownertype == 'Users')
	{
		//Checking the Read Sharing Permission Array in Role Users
		$read_role_per=$read_per_arr['ROLE'];
		foreach($read_role_per as $roleid=>$userids)
		{
			if(in_array($ownerid,$userids))
			{
				$sharePer='yes';
				$log->debug("Exiting isReadPermittedBySharing method ...");
				return $sharePer;		
			}

		}

		//Checking the Read Sharing Permission Array in Groups Users
		$read_grp_per=$read_per_arr['GROUP'];
		foreach($read_grp_per as $grpid=>$userids)
		{
			if(in_array($ownerid,$userids))
			{
				$sharePer='yes';
				$log->debug("Exiting isReadPermittedBySharing method ...");
				return $sharePer;		
			}

		}

	}
	elseif($ownertype == 'Groups')
	{
		$read_grp_per=$read_per_arr['GROUP'];
		if(array_key_exists($ownerid,$read_grp_per))
		{
			$sharePer='yes';
			$log->debug("Exiting isReadPermittedBySharing method ...");
			return $sharePer;
		}
	}
	
	//Checking for the Related Sharing Permission
	$relatedModuleArray=$related_module_share[$tabid];
	if(is_array($relatedModuleArray))
	{
		foreach($relatedModuleArray as $parModId)
		{
			$parRecordOwner=getParentRecordOwner($tabid,$parModId,$record_id);
			if(sizeof($parRecordOwner) > 0)
			{
				$parModName=getTabname($parModId);
				$rel_var=$parModName."_".$module."_share_read_permission";
				$read_related_per_arr=$$rel_var;
				$rel_owner_type='';
				$rel_owner_id='';
				foreach($parRecordOwner as $rel_type=>$rel_id)
				{
					$rel_owner_type=$rel_type;
					$rel_owner_id=$rel_id;
				}
				if($rel_owner_type=='Users')
				{
					//Checking in Role Users
					$read_related_role_per=$read_related_per_arr['ROLE'];
					foreach($read_related_role_per as $roleid=>$userids)
					{
						if(in_array($rel_owner_id,$userids))
						{
							$sharePer='yes';
							$log->debug("Exiting isReadPermittedBySharing method ...");
							return $sharePer;
						}

					}
					//Checking in Group Users
					$read_related_grp_per=$read_related_per_arr['GROUP'];
					foreach($read_related_grp_per as $grpid=>$userids)
					{
						if(in_array($rel_owner_id,$userids))
						{
							$sharePer='yes';
							$log->debug("Exiting isReadPermittedBySharing method ...");
							return $sharePer;
						}

					}

				}
				elseif($rel_owner_type=='Groups')
				{
					$read_related_grp_per=$read_related_per_arr['GROUP'];
					if(array_key_exists($rel_owner_id,$read_related_grp_per))
					{
						$sharePer='yes';
						$log->debug("Exiting isReadPermittedBySharing method ...");
						return $sharePer;
					}

				}	
			}		
		}
	}
	$log->debug("Exiting isReadPermittedBySharing method ...");
	return $sharePer;
}



/** Function to check if the currently logged in user has Write Access due to Sharing for the specified record  
  * @param $module -- Module Name:: Type varchar
  * @param $actionid -- Action Id:: Type integer
  * @param $recordid -- Record Id:: Type integer
  * @param $tabid -- Tab Id:: Type integer
  * @returns yes or no. If Yes means this action is allowed for the currently logged in user. If no means this action is not allowed for the currently logged in user 
 */
function isReadWritePermittedBySharing($module,$tabid,$actionid,$record_id)
{
	global $log;
	$log->debug("Entering isReadWritePermittedBySharing(".$module.",".$tabid.",".$actionid.",".$record_id.") method ...");
	global $adb;
	global $current_user;	
	require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
	$ownertype='';
	$ownerid='';
	$sharePer='no';

	$sharingModuleList=getSharingModuleList();
        if(! in_array($module,$sharingModuleList))
        {
                $sharePer='no';
                return $sharePer;
        }

	$recordOwnerArr=getRecordOwnerId($record_id);
	foreach($recordOwnerArr as $type=>$id)
	{
		$ownertype=$type;
		$ownerid=$id;
	}

	$varname=$module."_share_write_permission";
	$write_per_arr=$$varname;
	
	if($ownertype == 'Users')
	{
		//Checking the Write Sharing Permission Array in Role Users
		$write_role_per=$write_per_arr['ROLE'];
		foreach($write_role_per as $roleid=>$userids)
		{
			if(in_array($ownerid,$userids))
			{
				$sharePer='yes';
				$log->debug("Exiting isReadWritePermittedBySharing method ...");
				return $sharePer;		
			}

		}
		//Checking the Write Sharing Permission Array in Groups Users
		$write_grp_per=$write_per_arr['GROUP'];
		foreach($write_grp_per as $grpid=>$userids)
		{
			if(in_array($ownerid,$userids))
			{
				$sharePer='yes';
				$log->debug("Exiting isReadWritePermittedBySharing method ...");
				return $sharePer;		
			}

		}

	}
	elseif($ownertype == 'Groups')
	{
		$write_grp_per=$write_per_arr['GROUP'];
		if(array_key_exists($ownerid,$write_grp_per))
		{
			$sharePer='yes';
			$log->debug("Exiting isReadWritePermittedBySharing method ...");
			return $sharePer;
		}
	}	
	//Checking for the Related Sharing Permission
	$relatedModuleArray=$related_module_share[$tabid];
	if(is_array($relatedModuleArray))
	{
		foreach($relatedModuleArray as $parModId)
		{
			$parRecordOwner=getParentRecordOwner($tabid,$parModId,$record_id);
			if(sizeof($parRecordOwner) > 0)
			{
				$parModName=getTabname($parModId);
				$rel_var=$parModName."_".$module."_share_write_permission";
				$write_related_per_arr=$$rel_var;
				$rel_owner_type='';
				$rel_owner_id='';
				foreach($parRecordOwner as $rel_type=>$rel_id)
				{
					$rel_owner_type=$rel_type;
					$rel_owner_id=$rel_id;
				}
				if($rel_owner_type=='Users')
				{
					//Checking in Role Users
					$write_related_role_per=$write_related_per_arr['ROLE'];
					foreach($write_related_role_per as $roleid=>$userids)
					{
						if(in_array($rel_owner_id,$userids))
						{
							$sharePer='yes';
							$log->debug("Exiting isReadWritePermittedBySharing method ...");
							return $sharePer;
						}

					}
					//Checking in Group Users
					$write_related_grp_per=$write_related_per_arr['GROUP'];
					foreach($write_related_grp_per as $grpid=>$userids)
					{
						if(in_array($rel_owner_id,$userids))
						{
							$sharePer='yes';
							$log->debug("Exiting isReadWritePermittedBySharing method ...");
							return $sharePer;
						}

					}

				}
				elseif($rel_owner_type=='Groups')
				{
					$write_related_grp_per=$write_related_per_arr['GROUP'];
					if(array_key_exists($rel_owner_id,$write_related_grp_per))
					{
						$sharePer='yes';
						$log->debug("Exiting isReadWritePermittedBySharing method ...");
						return $sharePer;
					}

				}	
			}		
		}
	}
	
	$log->debug("Exiting isReadWritePermittedBySharing method ...");
	return $sharePer;
}

/** Function to check if the outlook user is permitted to perform the specified action  
  * @param $module -- Module Name:: Type varchar
  * @param $actionname -- Action Name:: Type varchar
  * @param $recordid -- Record Id:: Type integer
  * @returns yes or no. If Yes means this action is allowed for the currently logged in user. If no means this action is not allowed for the currently logged in user 
  *
 */
function isAllowed_Outlook($module,$action,$user_id,$record_id)
{
	global $log;
	$log->debug("Entering isAllowed_Outlook(".$module.",".$action.",".$user_id.",".$record_id.") method ...");

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
		$actionid = getActionid($action);
		$profile_id = fetchUserProfileId($user_id);
		$tab_per_Data = getAllTabsPermission($profile_id);

		$permissionData = getTabsActionPermission($profile_id); 
		$defSharingPermissionData = getDefaultSharingAction();
		$others_permission_id = $defSharingPermissionData[$tabid];

		//Checking whether this vtiger_tab is allowed
		if($tab_per_Data[$tabid] == 0)
		{
			$permission = 'yes';
			//Checking whether this action is allowed
			if($permissionData[$tabid][$actionid] == 0)
			{
				$permission = 'yes';
				$rec_owner_id = '';
				if($record_id != '' && $module != 'Notes' && $module != 'Products' && $module != 'Faq')
				{
					$rec_owner_id = getUserId($record_id);
				}

				if($record_id != '' && $others_permission_id != '' && $module != 'Notes' && $module != 'Products' && $module != 'Faq' && $rec_owner_id != 0)
				{
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
	$log->debug("Exiting isAllowed_Outlook method ...");
	return $permission;

}


/** Function to be depricated
  *
  *
 */
function setGlobalProfilePermission2Session($profileid)
{
	global $log;
	$log->debug("Entering setGlobalProfilePermission2Session(".$profileid.") method ...");
  global $adb;
  $sql = "select * from vtiger_profile2globalpermissions where profileid=".$profileid ;
  $result = $adb->query($sql);
  $num_rows = $adb->num_rows($result);

  for($i=0; $i<$num_rows; $i++)
  {
	$act_id = $adb->query_result($result,$i,"globalactionid");
	$per_id = $adb->query_result($result,$i,"globalactionpermission");
	$copy[$act_id] = $per_id;
  }	 

  $_SESSION['global_permission_set']=$copy;
	$log->debug("Exiting setGlobalProfilePermission2Session method ...");
  
}



/** Function to get the Profile Global Information for the specified vtiger_profileid  
  * @param $profileid -- Profile Id:: Type integer
  * @returns Profile Gloabal Permission Array in the following format:
  * $profileGloblaPermisson=Array($viewall_actionid=>permission, $editall_actionid=>permission)
 */
function getProfileGlobalPermission($profileid)
{
global $log;
$log->debug("Entering getProfileGlobalPermission(".$profileid.") method ...");
  global $adb;
  $sql = "select * from vtiger_profile2globalpermissions where profileid=".$profileid ;
  $result = $adb->query($sql);
  $num_rows = $adb->num_rows($result);

  for($i=0; $i<$num_rows; $i++)
  {
	$act_id = $adb->query_result($result,$i,"globalactionid");
	$per_id = $adb->query_result($result,$i,"globalactionpermission");
	$copy[$act_id] = $per_id;
  }	 

	$log->debug("Exiting getProfileGlobalPermission method ...");
   return $copy;
  
}

/** Function to get the Profile Tab Permissions for the specified vtiger_profileid  
  * @param $profileid -- Profile Id:: Type integer
  * @returns Profile Tabs Permission Array in the following format:
  * $profileTabPermisson=Array($tabid1=>permission, $tabid2=>permission,........., $tabidn=>permission)
 */
function getProfileTabsPermission($profileid)
{
global $log;
$log->debug("Entering getProfileTabsPermission(".$profileid.") method ...");
  global $adb;
  $sql = "select * from vtiger_profile2tab where profileid=".$profileid ;
  $result = $adb->query($sql);
  $num_rows = $adb->num_rows($result);

  for($i=0; $i<$num_rows; $i++)
  {
	$tab_id = $adb->query_result($result,$i,"tabid");
	$per_id = $adb->query_result($result,$i,"permissions");
	$copy[$tab_id] = $per_id;
  }	 

$log->debug("Exiting getProfileTabsPermission method ...");
   return $copy;
  
}


/** Function to get the Profile Action Permissions for the specified vtiger_profileid  
  * @param $profileid -- Profile Id:: Type integer
  * @returns Profile Tabs Action Permission Array in the following format:
  *    $tabActionPermission = Array($tabid1=>Array(actionid1=>permission, actionid2=>permission,...,actionidn=>permission),
  *                        $tabid2=>Array(actionid1=>permission, actionid2=>permission,...,actionidn=>permission),
  *                                |
  *                        $tabidn=>Array(actionid1=>permission, actionid2=>permission,...,actionidn=>permission))
 */
function getProfileActionPermission($profileid)
{
global $log;
$log->debug("Entering getProfileActionPermission(".$profileid.") method ...");
	global $adb;
	$check = Array();
	$temp_tabid = Array();	
	$sql1 = "select * from vtiger_profile2standardpermissions where profileid=".$profileid;
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

 	
$log->debug("Exiting getProfileActionPermission method ...");
	return $check;
}



/** Function to get the Standard and Utility Profile Action Permissions for the specified vtiger_profileid  
  * @param $profileid -- Profile Id:: Type integer
  * @returns Profile Tabs Action Permission Array in the following format:
  *    $tabActionPermission = Array($tabid1=>Array(actionid1=>permission, actionid2=>permission,...,actionidn=>permission),
  *                        $tabid2=>Array(actionid1=>permission, actionid2=>permission,...,actionidn=>permission),
  *                                |
  *                        $tabidn=>Array(actionid1=>permission, actionid2=>permission,...,actionidn=>permission))
 */
function getProfileAllActionPermission($profileid)
{
global $log;
$log->debug("Entering getProfileAllActionPermission(".$profileid.") method ...");
	global $adb;
	$actionArr=getProfileActionPermission($profileid);
	$utilArr=getTabsUtilityActionPermission($profileid);
	foreach($utilArr as $tabid=>$act_arr)
	{
		$act_tab_arr=$actionArr[$tabid];
		foreach($act_arr as $utilid=>$util_perr)
		{
			$act_tab_arr[$utilid]=$util_perr;	
		}
		$actionArr[$tabid]=$act_tab_arr;
	}
$log->debug("Exiting getProfileAllActionPermission method ...");
	return $actionArr;
}


/** Function to create vtiger_profile 
  * @param $profilename -- Profile Name:: Type varchar
  * @param $parentProfileId -- Profile Id:: Type integer
 */
function createProfile($profilename,$parentProfileId,$description)
{
global $log;
$log->debug("Entering createProfile(".$profilename.",".$parentProfileId.",".$description.") method ...");
	global $adb;
	//Inserting values into Profile Table
	$sql1 = "insert into vtiger_profile values('','".$profilename."','".$description."')";
	$adb->query($sql1);

	//Retreiving the vtiger_profileid
	$sql2 = "select max(profileid) as current_id from vtiger_profile";
	$result2 = $adb->query($sql2);
	$current_profile_id = $adb->query_result($result2,0,'current_id');

	//Inserting values into vtiger_profile2globalpermissions
	$sql3 = "select * from vtiger_profile2globalpermissions where profileid=".$parentProfileId;
	$result3= $adb->query($sql3);
	$p2tab_rows = $adb->num_rows($result3);
	for($i=0; $i<$p2tab_rows; $i++)
	{
		$act_id=$adb->query_result($result3,$i,'globalactionid');
		$permissions=$adb->query_result($result3,$i,'globalactionpermission');
		$sql4="insert into vtiger_profile2globalpermissions values(".$current_profile_id.", ".$act_id.", ".$permissions.")";
		$adb->query($sql4);	
	}

	//Inserting values into Profile2tab vtiger_table
	$sql3 = "select * from vtiger_profile2tab where profileid=".$parentProfileId;
	$result3= $adb->query($sql3);
	$p2tab_rows = $adb->num_rows($result3);
	for($i=0; $i<$p2tab_rows; $i++)
	{
		$tab_id=$adb->query_result($result3,$i,'tabid');
		$permissions=$adb->query_result($result3,$i,'permissions');
		$sql4="insert into vtiger_profile2tab values(".$current_profile_id.", ".$tab_id.", ".$permissions.")";
		$adb->query($sql4);	
	}

	//Inserting values into Profile2standard vtiger_table
	$sql6 = "select * from vtiger_profile2standardpermissions where profileid=".$parentProfileId;
	$result6= $adb->query($sql6);
	$p2per_rows = $adb->num_rows($result6);
	for($i=0; $i<$p2per_rows; $i++)
	{
		$tab_id=$adb->query_result($result6,$i,'tabid');
		$action_id=$adb->query_result($result6,$i,'operation');	
		$permissions=$adb->query_result($result6,$i,'permissions');
		$sql7="insert into vtiger_profile2standardpermissions values(".$current_profile_id.", ".$tab_id.", ".$action_id.", ".$permissions.")";
		$adb->query($sql7);	
	}

	//Inserting values into Profile2Utility vtiger_table
	$sql8 = "select * from vtiger_profile2utility where profileid=".$parentProfileId;
	$result8= $adb->query($sql8);
	$p2util_rows = $adb->num_rows($result8);
	for($i=0; $i<$p2util_rows; $i++)
	{
		$tab_id=$adb->query_result($result8,$i,'tabid');
		$action_id=$adb->query_result($result8,$i,'activityid');	
		$permissions=$adb->query_result($result8,$i,'permission');
		$sql9="insert into vtiger_profile2utility values(".$current_profile_id.", ".$tab_id.", ".$action_id.", ".$permissions.")";
		$adb->query($sql9);	
	}

	//Inserting values into Profile2field vtiger_table
	$sql10 = "select * from vtiger_profile2field where profileid=".$parentProfileId;
	$result10= $adb->query($sql10);
	$p2field_rows = $adb->num_rows($result10);
	for($i=0; $i<$p2field_rows; $i++)
	{
		$tab_id=$adb->query_result($result10,$i,'tabid');
		$fieldid=$adb->query_result($result10,$i,'fieldid');	
		$permissions=$adb->query_result($result10,$i,'visible');
		$readonly=$adb->query_result($result10,$i,'readonly');
		$sql11="insert into vtiger_profile2field values(".$current_profile_id.", ".$tab_id.", ".$fieldid.", ".$permissions." ,".$readonly.")";
		$adb->query($sql11);	
	}
	$log->debug("Exiting createProfile method ...");
}

/** Function to delete vtiger_profile 
  * @param $transfer_profileid -- Profile Id to which the existing vtiger_role2profile relationships are to be transferred :: Type varchar
  * @param $prof_id -- Profile Id to be deleted:: Type integer
 */
function deleteProfile($prof_id,$transfer_profileid='')
{
	global $log;
$log->debug("Entering deleteProfile(".$prof_id.",".$transfer_profileid.") method ...");
	global $adb;
	//delete from vtiger_profile2global permissions
	$sql4 = "delete from vtiger_profile2globalpermissions where profileid=".$prof_id;
	$adb->query($sql4);

	//deleting from vtiger_profile 2 vtiger_tab;
	$sql4 = "delete from vtiger_profile2tab where profileid=".$prof_id;
	$adb->query($sql4);

	//deleting from vtiger_profile2standardpermissions vtiger_table
	$sql5 = "delete from vtiger_profile2standardpermissions where profileid=".$prof_id;
	$adb->query($sql5);

	//deleting from vtiger_profile2field
	$sql6 ="delete from vtiger_profile2field where profileid=".$prof_id;
	$adb->query($sql6);

	//deleting from vtiger_profile2utility
	$sql7 ="delete from vtiger_profile2utility where profileid=".$prof_id;
	$adb->query($sql7);

	//updating vtiger_role2profile
        if(isset($transfer_profileid) && $transfer_profileid != '')
        {

                $sql8 = "select roleid from vtiger_role2profile where profileid=".$prof_id;
                $result=$adb->query($sql8);
                $num_rows=$adb->num_rows($result);

                for($i=0;$i<$num_rows;$i++)
                {
                        $roleid=$adb->query_result($result,$i,'roleid');
                        $sql = "select profileid from vtiger_role2profile where roleid='".$roleid."'";
                        $profresult=$adb->query($sql);
                        $num=$adb->num_rows($profresult);
                        if($num>1)
                        {
                                $sql10="delete from vtiger_role2profile where roleid='".$roleid."' and profileid=".$prof_id;
                                $adb->query($sql10);
                        }
                        else
                        {
                                $sql8 = "update vtiger_role2profile set profileid=".$transfer_profileid." where profileid=".$prof_id." and roleid='".$roleid."'";
                                $adb->query($sql8);
                        }


                }
        }

	//delete from vtiger_profile vtiger_table;
	$sql9 = "delete from vtiger_profile where profileid=".$prof_id;
	$adb->query($sql9);
	$log->debug("Exiting deleteProfile method ...");	

}

/** Function to get all  the vtiger_role information 
  * @returns $allRoleDetailArray-- Array will contain the details of all the vtiger_roles. RoleId will be the key:: Type array
 */
function getAllRoleDetails()
{
global $log;
$log->debug("Entering getAllRoleDetails() method ...");
	global $adb;
	$role_det = Array();
	$query = "select * from vtiger_role";
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
		$query1="select * from vtiger_role where parentrole like '".$parentrole."::%' and depth=".$sub_roledepth;
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
	$log->debug("Exiting getAllRoleDetails method ...");
	return $role_det;
}


/** Function to get all  the vtiger_profile information 
  * @returns $allProfileInfoArray-- Array will contain the details of all the vtiger_profiles. Profile ID will be the key:: Type array
 */
function getAllProfileInfo()
{
	global $log;
	$log->debug("Entering getAllProfileInfo() method ...");
	global $adb;
	$query="select * from vtiger_profile";
	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	$prof_details=Array();
	for($i=0;$i<$num_rows;$i++)
	{
		$profileid=$adb->query_result($result,$i,'profileid');
		$profilename=$adb->query_result($result,$i,'profilename');
		$prof_details[$profileid]=$profilename;
		
	}
	$log->debug("Exiting getAllProfileInfo method ...");
	return $prof_details;	
}

/** Function to get the vtiger_role information of the specified vtiger_role
  * @param $roleid -- RoleId :: Type varchar 
  * @returns $roleInfoArray-- RoleInfoArray in the following format:
  *       $roleInfo=Array($roleId=>Array($rolename,$parentrole,$roledepth,$immediateParent));
 */
function getRoleInformation($roleid)
{
	global $log;
	$log->debug("Entering getRoleInformation(".$roleid.") method ...");
	global $adb;
	$query = "select * from vtiger_role where roleid='".$roleid."'";
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
	$log->debug("Exiting getRoleInformation method ...");
	return $roleInfo;	
}


/** Function to get the vtiger_role related vtiger_profiles
  * @param $roleid -- RoleId :: Type varchar 
  * @returns $roleProfiles-- Role Related Profile Array in the following format:
  *       $roleProfiles=Array($profileId1=>$profileName,$profileId2=>$profileName,........,$profileIdn=>$profileName));
 */
function getRoleRelatedProfiles($roleId)
{
	global $log;
	$log->debug("Entering getRoleRelatedProfiles(".$roleId.") method ...");
	global $adb;
	$query = "select vtiger_role2profile.*,vtiger_profile.profilename from vtiger_role2profile inner join vtiger_profile on vtiger_profile.profileid=vtiger_role2profile.profileid where roleid='".$roleId."'";
	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	$roleRelatedProfiles=Array();
	for($i=0; $i<$num_rows; $i++)
	{
		$roleRelatedProfiles[$adb->query_result($result,$i,'profileid')]=$adb->query_result($result,$i,'profilename');
	}	
	$log->debug("Exiting getRoleRelatedProfiles method ...");
	return $roleRelatedProfiles;	
}


/** Function to get the vtiger_role related vtiger_users
  * @param $roleid -- RoleId :: Type varchar 
  * @returns $roleUsers-- Role Related User Array in the following format:
  *       $roleUsers=Array($userId1=>$userName,$userId2=>$userName,........,$userIdn=>$userName));
 */
function getRoleUsers($roleId)
{
	global $log;
	$log->debug("Entering getRoleUsers(".$roleId.") method ...");
	global $adb;
	$query = "select vtiger_user2role.*,vtiger_users.user_name from vtiger_user2role inner join vtiger_users on vtiger_users.id=vtiger_user2role.userid where roleid='".$roleId."'";
	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	$roleRelatedUsers=Array();
	for($i=0; $i<$num_rows; $i++)
	{
		$roleRelatedUsers[$adb->query_result($result,$i,'userid')]=$adb->query_result($result,$i,'user_name');
	}
	$log->debug("Exiting getRoleUsers method ...");
	return $roleRelatedUsers;
	

}


/** Function to get the vtiger_role related user ids
  * @param $roleid -- RoleId :: Type varchar 
  * @returns $roleUserIds-- Role Related User Array in the following format:
  *       $roleUserIds=Array($userId1,$userId2,........,$userIdn);
 */

function getRoleUserIds($roleId)
{
	global $log;
	$log->debug("Entering getRoleUserIds(".$roleId.") method ...");
	global $adb;
	$query = "select vtiger_user2role.*,vtiger_users.user_name from vtiger_user2role inner join vtiger_users on vtiger_users.id=vtiger_user2role.userid where roleid='".$roleId."'";
	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	$roleRelatedUsers=Array();
	for($i=0; $i<$num_rows; $i++)
	{
		$roleRelatedUsers[]=$adb->query_result($result,$i,'userid');
	}
	$log->debug("Exiting getRoleUserIds method ...");
	return $roleRelatedUsers;
	

}

/** Function to get the vtiger_role and subordinate vtiger_users
  * @param $roleid -- RoleId :: Type varchar 
  * @returns $roleSubUsers-- Role and Subordinates Related Users Array in the following format:
  *       $roleSubUsers=Array($userId1=>$userName,$userId2=>$userName,........,$userIdn=>$userName));
 */
function getRoleAndSubordinateUsers($roleId)
{
	global $log;
	$log->debug("Entering getRoleAndSubordinateUsers(".$roleId.") method ...");
	global $adb;
	$roleInfoArr=getRoleInformation($roleId);
	$parentRole=$roleInfoArr[$roleId][1];
	$query = "select vtiger_user2role.*,vtiger_users.user_name from vtiger_user2role inner join vtiger_users on vtiger_users.id=vtiger_user2role.userid inner join vtiger_role on vtiger_role.roleid=vtiger_user2role.roleid where vtiger_role.parentrole like '".$parentRole."%'";
	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	$roleRelatedUsers=Array();
	for($i=0; $i<$num_rows; $i++)
	{
		$roleRelatedUsers[$adb->query_result($result,$i,'userid')]=$adb->query_result($result,$i,'user_name');
	}
	$log->debug("Exiting getRoleAndSubordinateUsers method ...");
	return $roleRelatedUsers;
	

}


/** Function to get the vtiger_role and subordinate user ids
  * @param $roleid -- RoleId :: Type varchar 
  * @returns $roleSubUserIds-- Role and Subordinates Related Users Array in the following format:
  *       $roleSubUserIds=Array($userId1,$userId2,........,$userIdn);
 */
function getRoleAndSubordinateUserIds($roleId)
{
	global $log;
	$log->debug("Entering getRoleAndSubordinateUserIds(".$roleId.") method ...");
	global $adb;
	$roleInfoArr=getRoleInformation($roleId);
	$parentRole=$roleInfoArr[$roleId][1];
	$query = "select vtiger_user2role.*,vtiger_users.user_name from vtiger_user2role inner join vtiger_users on vtiger_users.id=vtiger_user2role.userid inner join vtiger_role on vtiger_role.roleid=vtiger_user2role.roleid where vtiger_role.parentrole like '".$parentRole."%'";
	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	$roleRelatedUsers=Array();
	for($i=0; $i<$num_rows; $i++)
	{
		$roleRelatedUsers[]=$adb->query_result($result,$i,'userid');
	}
	$log->debug("Exiting getRoleAndSubordinateUserIds method ...");
	return $roleRelatedUsers;
	

}

/** Function to get the vtiger_role and subordinate Information for the specified vtiger_roleId
  * @param $roleid -- RoleId :: Type varchar 
  * @returns $roleSubInfo-- Role and Subordinates Information array in the following format:
  *       $roleSubInfo=Array($roleId1=>Array($rolename,$parentrole,$roledepth,$immediateParent), $roleId2=>Array($rolename,$parentrole,$roledepth,$immediateParent),.....);
 */
function getRoleAndSubordinatesInformation($roleId)
{
	global $log;
	$log->debug("Entering getRoleAndSubordinatesInformation(".$roleId.") method ...");
	global $adb;
	$roleDetails=getRoleInformation($roleId);
	$roleInfo=$roleDetails[$roleId];
	$roleParentSeq=$roleInfo[1];
	
	$query="select * from vtiger_role where parentrole like '".$roleParentSeq."%' order by parentrole asc";
	$result=$adb->query($query);
	$num_rows=$adb->num_rows($result);
	$roleInfo=Array();
	for($i=0;$i<$num_rows;$i++)
	{
		$roleid=$adb->query_result($result,$i,'roleid');
                $rolename=$adb->query_result($result,$i,'rolename');
                $roledepth=$adb->query_result($result,$i,'depth');
                $parentrole=$adb->query_result($result,$i,'parentrole');
		$roleDet=Array();
		$roleDet[]=$rolename;
		$roleDet[]=$parentrole;
		$roleDet[]=$roledepth;
		$roleInfo[$roleid]=$roleDet;
		
	}
	$log->debug("Exiting getRoleAndSubordinatesInformation method ...");
	return $roleInfo;	

}


/** Function to get the vtiger_role and subordinate vtiger_role ids
  * @param $roleid -- RoleId :: Type varchar 
  * @returns $roleSubRoleIds-- Role and Subordinates RoleIds in an Array in the following format:
  *       $roleSubRoleIds=Array($roleId1,$roleId2,........,$roleIdn);
 */
function getRoleAndSubordinatesRoleIds($roleId)
{
	global $log;
	$log->debug("Entering getRoleAndSubordinatesRoleIds(".$roleId.") method ...");
	global $adb;
	$roleDetails=getRoleInformation($roleId);
	$roleInfo=$roleDetails[$roleId];
	$roleParentSeq=$roleInfo[1];
	
	$query="select * from vtiger_role where parentrole like '".$roleParentSeq."%' order by parentrole asc";
	$result=$adb->query($query);
	$num_rows=$adb->num_rows($result);
	$roleInfo=Array();
	for($i=0;$i<$num_rows;$i++)
	{
		$roleid=$adb->query_result($result,$i,'roleid');
		$roleInfo[]=$roleid;
		
	}
	$log->debug("Exiting getRoleAndSubordinatesRoleIds method ...");
	return $roleInfo;	

}

/** Function to get delete the spcified vtiger_role 
  * @param $roleid -- RoleId :: Type varchar 
  * @param $transferRoleId -- RoleId to which vtiger_users of the vtiger_role that is being deleted are transferred:: Type varchar 
 */
function deleteRole($roleId,$transferRoleId)
{
	global $log;
	$log->debug("Entering deleteRole(".$roleId.",".$transferRoleId.") method ...");
        global $adb;
        $roleInfo=getRoleAndSubordinatesInformation($roleId);
        foreach($roleInfo as $roleid=>$roleDetArr)
        {

                $sql1 = "update vtiger_user2role set roleid='".$transferRoleId."' where roleid='".$roleid."'";
                $adb->query($sql1);

                //Deleteing from vtiger_role2profile vtiger_table
                $sql2 = "delete from vtiger_role2profile where roleid='".$roleid."'";
                $adb->query($sql2);

                //delete handling for vtiger_groups
                $sql10 = "delete from vtiger_group2role where roleid='".$roleid."'";
                $adb->query($sql10);

                $sql11 = "delete from vtiger_group2rs where roleandsubid='".$roleid."'";
                $adb->query($sql11);


                //delete handling for sharing rules
                deleteRoleRelatedSharingRules($roleid);

                //delete from vtiger_role vtiger_table;
                $sql9 = "delete from vtiger_role where roleid='".$roleid."'";
                $adb->query($sql9);
                //echo $sql1.'            '.$sql2.'           '.$sql9;



        }
	$log->debug("Exiting deleteRole method ...");

}

/** Function to delete the vtiger_role related sharing rules
  * @param $roleid -- RoleId :: Type varchar
 */
function deleteRoleRelatedSharingRules($roleId)
{
	global $log;
	$log->debug("Entering deleteRoleRelatedSharingRules(".$roleId.") method ...");
        global $adb;
        $dataShareTableColArr=Array('vtiger_datashare_grp2role'=>'to_roleid',
                                    'vtiger_datashare_grp2rs'=>'to_roleandsubid',
                                    'vtiger_datashare_role2group'=>'share_roleid',
                                    'vtiger_datashare_role2role'=>'share_roleid::to_roleid',
                                    'vtiger_datashare_role2rs'=>'share_roleid::to_roleandsubid',
                                    'vtiger_datashare_rs2grp'=>'share_roleandsubid',
                                    'vtiger_datashare_rs2role'=>'share_roleandsubid::to_roleid',
                                    'vtiger_datashare_rs2rs'=>'share_roleandsubid::to_roleandsubid');

        foreach($dataShareTableColArr as $tablename=>$colname)
        {
                $colNameArr=explode('::',$colname);
                $query="select shareid from ".$tablename." where ".$colNameArr[0]."='".$roleId."'";
                if(sizeof($colNameArr) >1)
                {
                        $query .=" or ".$colNameArr[1]."='".$roleId."'";
                }


                $result=$adb->query($query);
                $num_rows=$adb->num_rows($result);
                for($i=0;$i<$num_rows;$i++)
                {
                        $shareid=$adb->query_result($result,$i,'shareid');
                        deleteSharingRule($shareid);
                }

        }
	$log->debug("Exiting deleteRoleRelatedSharingRules method ...");
}

/** Function to delete the group related sharing rules
  * @param $roleid -- RoleId :: Type varchar
 */
function deleteGroupRelatedSharingRules($grpId)
{
	global $log;
	$log->debug("Entering deleteGroupRelatedSharingRules(".$grpId.") method ...");

        global $adb;
        $dataShareTableColArr=Array('vtiger_datashare_grp2grp'=>'share_groupid::to_groupid',
                                    'vtiger_datashare_grp2role'=>'share_groupid',
                                    'vtiger_datashare_grp2rs'=>'share_groupid',
                                    'vtiger_datashare_role2group'=>'to_groupid',
                                    'vtiger_datashare_rs2grp'=>'to_groupid');


        foreach($dataShareTableColArr as $tablename=>$colname)
        {
                $colNameArr=explode('::',$colname);
                $query="select shareid from ".$tablename." where ".$colNameArr[0]."=".$grpId;
                if(sizeof($colNameArr) >1)
                {
                        $query .=" or ".$colNameArr[1]."=".$grpId;
                }


                $result=$adb->query($query);
                $num_rows=$adb->num_rows($result);
                for($i=0;$i<$num_rows;$i++)
                {
                        $shareid=$adb->query_result($result,$i,'shareid');
                        deleteSharingRule($shareid);
                }

        }
	$log->debug("Exiting deleteGroupRelatedSharingRules method ...");
}


/** Function to get userid and username of all vtiger_users 
  * @returns $userArray -- User Array in the following format:
  * $userArray=Array($userid1=>$username, $userid2=>$username,............,$useridn=>$username); 
 */
function getAllUserName()
{
	global $log;
	$log->debug("Entering getAllUserName() method ...");
	global $adb;
	$query="select * from vtiger_users where deleted=0";
	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	$user_details=Array();
	for($i=0;$i<$num_rows;$i++)
	{
		$userid=$adb->query_result($result,$i,'id');
		$username=$adb->query_result($result,$i,'user_name');
		$user_details[$userid]=$username;
		
	}
	$log->debug("Exiting getAllUserName method ...");
	return $user_details;

}


/** Function to get groupid and groupname of all vtiger_groups 
  * @returns $grpArray -- Group Array in the following format:
  * $grpArray=Array($grpid1=>$grpname, $grpid2=>$grpname,............,$grpidn=>$grpname); 
 */
function getAllGroupName()
{
	global $log;
	$log->debug("Entering getAllGroupName() method ...");
	global $adb;
	$query="select * from vtiger_groups";
	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	$group_details=Array();
	for($i=0;$i<$num_rows;$i++)
	{
		$grpid=$adb->query_result($result,$i,'groupid');
		$grpname=$adb->query_result($result,$i,'groupname');
		$group_details[$grpid]=$grpname;
		
	}
	$log->debug("Exiting getAllGroupName method ...");
	return $group_details;

}

/** Function to get groupid and groupname of all for the given groupid 
  * @returns $grpArray -- Group Array in the following format:
  * $grpArray=Array($grpid1=>$grpname); 
 */
function getGroupDetails($id)
{
	global $log;
	$log->debug("Entering getAllGroupDetails() method ...");
	global $adb;
	$query="select * from vtiger_groups where groupid = $id";
	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	if($num_rows < 1)
		return null;
	$group_details=Array();
	$grpid=$adb->query_result($result,0,'groupid');
	$grpname=$adb->query_result($result,0,'groupname');
	$grpdesc=$adb->query_result($result,0,'description');
	$group_details=Array($grpid,$grpname,$grpdesc);
		
	$log->debug("Exiting getAllGroupDetails method ...");
	return $group_details;

}
/** Function to get group information of all vtiger_groups 
  * @returns $grpInfoArray -- Group Informaton array in the following format: 
  * $grpInfoArray=Array($grpid1=>Array($grpname,description) $grpid2=>Array($grpname,description),............,$grpidn=>Array($grpname,description)); 
 */
function getAllGroupInfo()
{
	global $log;
	$log->debug("Entering getAllGroupInfo() method ...");
	global $adb;
	$query="select * from vtiger_groups";
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
	$log->debug("Exiting getAllGroupInfo method ...");
	return $group_details;

}

/** Function to create a group 
  * @param $groupName -- Group Name :: Type varchar 
  * @param $groupMemberArray -- Group Members (Groups,Roles,RolesAndsubordinates,Users) 
  * @param $groupName -- Group Name :: Type varchar 
  * @returns $groupId -- Group Id :: Type integer 
 */
function createGroup($groupName,$groupMemberArray,$description)
{	
	global $log;
	$log->debug("Entering createGroup(".$groupName.",".$groupMemberArray.",".$description.") method ...");
	global $adb;
	$groupId=$adb->getUniqueId("vtiger_groups");
	//Insert into group vtiger_table
	$query = "insert into vtiger_groups values(".$groupId.",'".$groupName."','".$description."')";
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
	$log->debug("Exiting createGroup method ...");
	return $groupId;	
}


/** Function to insert group to group relation 
  * @param $groupId -- Group Id :: Type integer 
  * @param $containsGroupId -- Group Id :: Type integer 
 */
function insertGroupToGroupRelation($groupId,$containsGroupId)
{
	global $log;
	$log->debug("Entering insertGroupToGroupRelation(".$groupId.",".$containsGroupId.") method ...");
	global $adb;
	$query="insert into vtiger_group2grouprel values(".$groupId.",".$containsGroupId.")";
	$adb->query($query);
	$log->debug("Exiting insertGroupToGroupRelation method ...");
}


/** Function to insert group to vtiger_role relation 
  * @param $groupId -- Group Id :: Type integer 
  * @param $roleId -- Role Id :: Type varchar 
 */
function insertGroupToRoleRelation($groupId,$roleId)
{
	 global $log;
	$log->debug("Entering insertGroupToRoleRelation(".$groupId.",".$roleId.") method ...");
	global $adb;
	$query="insert into vtiger_group2role values(".$groupId.",'".$roleId."')";
	$adb->query($query);
	$log->debug("Exiting insertGroupToRoleRelation method ...");
}


/** Function to insert group to vtiger_role&subordinate relation 
  * @param $groupId -- Group Id :: Type integer 
  * @param $rsId -- Role Sub Id :: Type varchar 
 */
function insertGroupToRsRelation($groupId,$rsId)
{
	global $log;
	$log->debug("Entering insertGroupToRsRelation(".$groupId.",".$rsId.") method ...");
	global $adb;
	$query="insert into vtiger_group2rs values(".$groupId.",'".$rsId."')";
	$adb->query($query);
	$log->debug("Exiting insertGroupToRsRelation method ...");
}

/** Function to insert group to user relation 
  * @param $groupId -- Group Id :: Type integer 
  * @param $userId -- User Id :: Type varchar 
 */
function insertGroupToUserRelation($groupId,$userId)
{
	global $log;
	$log->debug("Entering insertGroupToUserRelation(".$groupId.",".$userId.") method ...");
	global $adb;
	$query="insert into vtiger_users2group values(".$groupId.",".$userId.")";
	$adb->query($query);
	$log->debug("Exiting insertGroupToUserRelation method ...");
}


/** Function to get the group Information of the specified group 
  * @param $groupId -- Group Id :: Type integer 
  * @returns Group Detail Array in the following format:
  *   $groupDetailArray=Array($groupName,$description,$groupMembers);
 */
function getGroupInfo($groupId)
{
	global $log;
	$log->debug("Entering getGroupInfo(".$groupId.") method ...");
	global $adb;
	$groupDetailArr=Array();
	$groupMemberArr=Array();
	//Retreving the group Info
	$query="select * from vtiger_groups where groupid=".$groupId;
	$result = $adb->query($query);
	$groupName=$adb->query_result($result,0,'groupname');
	$description=$adb->query_result($result,0,'description');
	
	//Retreving the Group RelatedMembers
	$groupMemberArr=getGroupMembers($groupId);
	$groupDetailArr[]=$groupName;
	$groupDetailArr[]=$description;
	$groupDetailArr[]=$groupMemberArr;

	//Returning the Group Detail Array
	$log->debug("Exiting getGroupInfo method ...");
	return $groupDetailArr;
	 

}

/** Function to fetch the group name of the specified group 
  * @param $groupId -- Group Id :: Type integer 
  * @returns Group Name :: Type varchar
 */
function fetchGroupName($groupId)
{
	global $log;
	$log->debug("Entering fetchGroupName(".$groupId.") method ...");

	global $adb;
	//Retreving the group Info
	$query="select * from vtiger_groups where groupid=".$groupId;
	$result = $adb->query($query);
	$groupName=$adb->query_result($result,0,'groupname');
	$log->debug("Exiting fetchGroupName method ...");
	return $groupName;
	
}

/** Function to fetch the group members of the specified group 
  * @param $groupId -- Group Id :: Type integer 
  * @returns Group Member Array in the follwing format:
  *  $groupMemberArray=Array([groups]=>Array(groupid1,groupid2,groupid3,.....,groupidn),
  *                          [roles]=>Array(roleid1,roleid2,roleid3,.....,roleidn),
  *                          [rs]=>Array(roleid1,roleid2,roleid3,.....,roleidn),
  *                          [users]=>Array(useridd1,userid2,userid3,.....,groupidn)) 
 */
function getGroupMembers($groupId)
{
	global $log;
	$log->debug("Entering getGroupMembers(".$groupId.") method ...");
	$groupMemberArr=Array();
	$roleGroupArr=getGroupRelatedRoles($groupId);
	$rsGroupArr=getGroupRelatedRoleSubordinates($groupId);
	$groupGroupArr=getGroupRelatedGroups($groupId);
	$userGroupArr=getGroupRelatedUsers($groupId);
	
	$groupMemberArr['groups']=$groupGroupArr;
	$groupMemberArr['roles']=$roleGroupArr;
	$groupMemberArr['rs']=$rsGroupArr;
	$groupMemberArr['users']=$userGroupArr;
	
	$log->debug("Exiting getGroupMembers method ...");
	return($groupMemberArr);
}

/** Function to get the group related vtiger_roles of the specified group 
  * @param $groupId -- Group Id :: Type integer 
  * @returns Group Related Role Array in the follwing format:
  *  $groupRoles=Array(roleid1,roleid2,roleid3,.....,roleidn);
 */
function getGroupRelatedRoles($groupId)
{
	global $log;	
	$log->debug("Entering getGroupRelatedRoles(".$groupId.") method ...");
	global $adb;
	$roleGroupArr=Array();
	$query="select * from vtiger_group2role where groupid=".$groupId;
	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	for($i=0;$i<$num_rows;$i++)
	{
		$roleId=$adb->query_result($result,$i,'roleid');
		$roleGroupArr[]=$roleId;
	}
	$log->debug("Exiting getGroupRelatedRoles method ...");
	return $roleGroupArr;	
			
}


/** Function to get the group related vtiger_roles and subordinates of the specified group 
  * @param $groupId -- Group Id :: Type integer 
  * @returns Group Related Roles & Subordinate Array in the follwing format:
  *  $groupRoleSubordinates=Array(roleid1,roleid2,roleid3,.....,roleidn);
 */
function getGroupRelatedRoleSubordinates($groupId)
{
	global $log;
	$log->debug("Entering getGroupRelatedRoleSubordinates(".$groupId.") method ...");
	global $adb;
	$rsGroupArr=Array();
	$query="select * from vtiger_group2rs where groupid=".$groupId;
	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	for($i=0;$i<$num_rows;$i++)
	{
		$roleSubId=$adb->query_result($result,$i,'roleandsubid');
		$rsGroupArr[]=$roleSubId;
	}
	$log->debug("Exiting getGroupRelatedRoleSubordinates method ...");
	return $rsGroupArr;
}


/** Function to get the group related vtiger_groups  
  * @param $groupId -- Group Id :: Type integer 
  * @returns Group Related Groups Array in the follwing format:
  *  $groupGroups=Array(grpid1,grpid2,grpid3,.....,grpidn);
 */
function getGroupRelatedGroups($groupId)
{
	global $log;
	$log->debug("Entering getGroupRelatedGroups(".$groupId.") method ...");
	global $adb;
	$groupGroupArr=Array();
	$query="select * from vtiger_group2grouprel where groupid=".$groupId;
	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	for($i=0;$i<$num_rows;$i++)
	{
		$relGroupId=$adb->query_result($result,$i,'containsgroupid');
		$groupGroupArr[]=$relGroupId;
	}
	$log->debug("Exiting getGroupRelatedGroups method ...");
	return $groupGroupArr;	
			
}

/** Function to get the group related vtiger_users  
  * @param $userId -- User Id :: Type integer 
  * @returns Group Related Users Array in the follwing format:
  *  $groupUsers=Array(userid1,userid2,userid3,.....,useridn);
 */
function getGroupRelatedUsers($groupId)
{
	global $log;
	$log->debug("Entering getGroupRelatedUsers(".$groupId.") method ...");
	global $adb;
	$userGroupArr=Array();
	$query="select * from vtiger_users2group where groupid=".$groupId;
	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	for($i=0;$i<$num_rows;$i++)
	{
		$userId=$adb->query_result($result,$i,'userid');
		$userGroupArr[]=$userId;
	}
	$log->debug("Exiting getGroupRelatedUsers method ...");
	return $userGroupArr;	
			
}

/** Function to update the group  
  * @param $groupId -- Group Id :: Type integer 
  * @param $groupName -- Group Name :: Type varchar 
  * @param $groupMemberArray -- Group Members Array :: Type array 
  * @param $description -- Description :: Type text
 */
function updateGroup($groupId,$groupName,$groupMemberArray,$description)
{
	global $log;
	$log->debug("Entering updateGroup(".$groupId.",".$groupName.",".$groupMemberArray.",".$description.") method ...");
	global $adb;
	$query="update vtiger_groups set groupname='".$groupName."',description='".$description."' where groupid=".$groupId;
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
	$log->debug("Exiting updateGroup method ...");	

}

/** Function to delete the specified group  
  * @param $groupId -- Group Id :: Type integer
  * @param $transferId --  Id of the group/user to which record ownership is to be transferred:: Type integer 
  * @param $transferType -- It can have only two values namely 'Groups' or 'Users'. This determines whether the owneship is to be transferred to a group or user :: Type varchar
 */
function deleteGroup($groupId,$transferId,$transferType)
{
	global $log;
	$log->debug("Entering deleteGroup(".$groupId.") method ...");	
	global $adb;
	
	tranferGroupOwnership($groupId,$transferId,$transferType);		
	deleteGroupRelatedSharingRules($groupId);
		
	$query="delete from vtiger_groups where groupid=".$groupId;
	$adb->query($query);

	deleteGroupRelatedGroups($groupId);
	deleteGroupRelatedRoles($groupId);
	deleteGroupRelatedRolesAndSubordinates($groupId);
	deleteGroupRelatedUsers($groupId);
	$log->debug("Exiting deleteGroup method ...");
}


/** Function to transfer the ownership of records owned by a particular group to the specified group
  * @param $groupId -- Group Id of the group which's record ownership has to be transferred:: Type integer 
  * @param $transferId --  Id of the group/user to which record ownership is to be transferred:: Type integer 
  * @param $transferType -- It can have only two values namely 'Groups' or 'Users'. This determines whether the owneship is to be transferred to a group or user :: Type varchar 
 */
function tranferGroupOwnership($groupId,$transferId,$transferType)
{
	global $log;
	$log->debug("Entering deleteGroup(".$groupId.") method ...");	
	global $adb;
	$table_array =	Array ('vtiger_leadgrouprelation'=>'leadid',
			'vtiger_accountgrouprelation'=>'accountid',
			'vtiger_contactgrouprelation'=>'contactid',
			'vtiger_potentialgrouprelation'=>'potentialid',
			'vtiger_campaigngrouprelation'=>'campaignid',
			'vtiger_activitygrouprelation'=>'activityid',
			'vtiger_ticketgrouprelation'=>'ticketid',
			'vtiger_sogrouprelation'=>'salesorderid',
			'vtiger_quotegrouprelation'=>'quoteid',
			'vtiger_pogrouprelation'=>'purchaseorderid',
			'vtiger_invoicegrouprelation'=>'invoiceid');

	
	$toBeTransferredGroupName=fetchGroupName($groupId);
			
	if($transferType == 'Groups')
	{

			
		$transferGroupName=fetchGroupName($transferId);
		foreach($table_array as $tableName=>$colName)
		{
			$query = "update ".$tableName." set groupname='".$transferGroupName."' where groupname ='".$toBeTransferredGroupName."'";
			$adb->query($query);			
		}		
	}
	elseif($transferType == 'Users')
	{
		foreach($table_array as $tableName=>$colName)
		{
			//Updating in the Crm Entity Table	
			$query = "update vtiger_crmentity set smownerid=".$transferId." where crmid in(select ".$colName." from ".$tableName." where groupname='".$toBeTransferredGroupName."')";
			//echo '<BR>';
			//echo $query;
			//echo '<BR>';
			$adb->query($query);

			//Deleting from the group table
			$query1 = "delete from ".$tableName." where groupname='".$toBeTransferredGroupName."'";
			//echo '<BR>';
			//echo $query1;
			//echo '<BR>';
			$adb->query($query1);			
		}	
	}		
		$log->debug("Exiting deleteGroup method ...");
}

/** Function to delete group to group relation of the  specified group  
  * @param $groupId -- Group Id :: Type integer 
 */
function deleteGroupRelatedGroups($groupId)
{
	global $log;
	$log->debug("Entering deleteGroupRelatedGroups(".$groupId.") method ...");
	global $adb;
	$query="delete from vtiger_group2grouprel where groupid=".$groupId;
	$adb->query($query);
	$log->debug("Exiting deleteGroupRelatedGroups method ...");
}


/** Function to delete group to vtiger_role relation of the  specified group  
  * @param $groupId -- Group Id :: Type integer 
 */
function deleteGroupRelatedRoles($groupId)
{
	global $log;
	$log->debug("Entering deleteGroupRelatedRoles(".$groupId.") method ...");
	global $adb;
	$query="delete from vtiger_group2role where groupid=".$groupId;
	$adb->query($query);
	$log->debug("Exiting deleteGroupRelatedRoles method ...");
}


/** Function to delete group to vtiger_role and subordinates relation of the  specified group  
  * @param $groupId -- Group Id :: Type integer 
 */
function deleteGroupRelatedRolesAndSubordinates($groupId)
{
	global $log;
	$log->debug("Entering deleteGroupRelatedRolesAndSubordinates(".$groupId.") method ...");
	global $adb;
	$query="delete from vtiger_group2rs where groupid=".$groupId;
	$adb->query($query);
	$log->debug("Exiting deleteGroupRelatedRolesAndSubordinates method ...");
}


/** Function to delete group to user relation of the  specified group  
  * @param $groupId -- Group Id :: Type integer 
 */
function deleteGroupRelatedUsers($groupId)
{
	global $log;
	$log->debug("Entering deleteGroupRelatedUsers(".$groupId.") method ...");
	global $adb;
	$query="delete from vtiger_users2group where groupid=".$groupId;
	$adb->query($query);
	$log->debug("Exiting deleteGroupRelatedUsers method ...");
}

/** This function returns the Default Organisation Sharing Action Name
  * @param $share_action_id -- It takes the Default Organisation Sharing ActionId as input :: Type Integer
  * @returns The sharing Action Name :: Type Varchar
  */
function getDefOrgShareActionName($share_action_id)
{
	global $log;
	$log->debug("Entering getDefOrgShareActionName(".$share_action_id.") method ...");
	global $adb;
	$query="select * from vtiger_org_share_action_mapping where share_action_id=".$share_action_id;
	$result=$adb->query($query);
	$share_action_name=$adb->query_result($result,0,"share_action_name");
	$log->debug("Exiting getDefOrgShareActionName method ...");
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
	global $log;
	$log->debug("Entering getModuleSharingActionArray(".$tabid.") method ...");
	global $adb;
	$share_action_arr=Array();
	$query = "select vtiger_org_share_action_mapping.share_action_name,vtiger_org_share_action2tab.share_action_id from vtiger_org_share_action2tab inner join vtiger_org_share_action_mapping on vtiger_org_share_action2tab.share_action_id=vtiger_org_share_action_mapping.share_action_id where vtiger_org_share_action2tab.tabid=".$tabid;
	$result=$adb->query($query);
	$num_rows=$adb->num_rows($result);
	for($i=0; $i<$num_rows; $i++)
	{
		$share_action_name=$adb->query_result($result,$i,"share_action_name");
		$share_action_id=$adb->query_result($result,$i,"share_action_id");
		$share_action_arr[$share_action_id] = $share_action_name;
	}
	$log->debug("Exiting getModuleSharingActionArray method ...");
	return $share_action_arr;
	
}

/** This function adds a organisation level sharing rule for the specified Module
  * It takes the following input parameters:
  * 	$tabid -- Module tabid - Datatype::Integer
  * 	$shareEntityType -- The Entity Type may be vtiger_groups,roles,rs and vtiger_users - Datatype::String
  * 	$toEntityType -- The Entity Type may be vtiger_groups,roles,rs and vtiger_users - Datatype::String
  * 	$shareEntityId -- The id of the group,role,rs,user to be shared 
  * 	$toEntityId -- The id of the group,role,rs,user to which the specified entity is to be shared
  * 	$sharePermisson -- This can have the following values:
  *                       0 - Read Only
  *                       1 - Read/Write
  * This function will return the shareid as output
  */
function addSharingRule($tabid,$shareEntityType,$toEntityType,$shareEntityId,$toEntityId,$sharePermission)
{
	global $log;
	$log->debug("Entering addSharingRule(".$tabid.",".$shareEntityType.",".$toEntityType.",".$shareEntityId.",".$toEntityId.",".$sharePermission.") method ...");
	
	global $adb;
	$shareid=$adb->getUniqueId("vtiger_datashare_module_rel");
	

	if($shareEntityType == 'groups' && $toEntityType == 'groups')
	{
		$type_string='GRP::GRP';
		$query = "insert into vtiger_datashare_grp2grp values(".$shareid.", ".$shareEntityId.", ".$toEntityId.", ".$sharePermission.")";
	}
	elseif($shareEntityType == 'groups' && $toEntityType == 'roles')
	{
		
		$type_string='GRP::ROLE';
		$query = "insert into vtiger_datashare_grp2role values(".$shareid.", ".$shareEntityId.", '".$toEntityId."', ".$sharePermission.")";
	}
	elseif($shareEntityType == 'groups' && $toEntityType == 'rs')
	{
		
		$type_string='GRP::RS';
		$query = "insert into vtiger_datashare_grp2rs values(".$shareid.", ".$shareEntityId.", '".$toEntityId."', ".$sharePermission.")";
	}
	elseif($shareEntityType == 'roles' && $toEntityType == 'groups')
	{
		
		$type_string='ROLE::GRP';
		$query = "insert into vtiger_datashare_role2group values(".$shareid.", '".$shareEntityId."', ".$toEntityId.", ".$sharePermission.")";
	}
	elseif($shareEntityType == 'roles' && $toEntityType == 'roles')
	{
		
		$type_string='ROLE::ROLE';
		$query = "insert into vtiger_datashare_role2role values(".$shareid.", '".$shareEntityId."', '".$toEntityId."', ".$sharePermission.")";
	}
	elseif($shareEntityType == 'roles' && $toEntityType == 'rs')
	{
		
		$type_string='ROLE::RS';
		$query = "insert into vtiger_datashare_role2rs values(".$shareid.", '".$shareEntityId."', '".$toEntityId."', ".$sharePermission.")";
	}
	elseif($shareEntityType == 'rs' && $toEntityType == 'groups')
	{
		
		$type_string='RS::GRP';
		$query = "insert into vtiger_datashare_rs2grp values(".$shareid.", '".$shareEntityId."', ".$toEntityId.", ".$sharePermission.")";
	}
	elseif($shareEntityType == 'rs' && $toEntityType == 'roles')
	{
		
		$type_string='RS::ROLE';
		$query = "insert into vtiger_datashare_rs2role values(".$shareid.", '".$shareEntityId."', '".$toEntityId."', ".$sharePermission.")";
	}
	elseif($shareEntityType == 'rs' && $toEntityType == 'rs')
	{
		
		$type_string='RS::RS';
		$query = "insert into vtiger_datashare_rs2rs values(".$shareid.", '".$shareEntityId."', '".$toEntityId."', ".$sharePermission.")";
	}
	$query1 = "insert into vtiger_datashare_module_rel values(".$shareid.",".$tabid.",'".$type_string."')";
	$adb->query($query1);		
	$adb->query($query);	
	$log->debug("Exiting addSharingRule method ...");
	return $shareid;	
	
}


/** This function is to update the organisation level sharing rule 
  * It takes the following input parameters:
  *     $shareid -- Id of the Sharing Rule to be updated
  * 	$tabid -- Module tabid - Datatype::Integer
  * 	$shareEntityType -- The Entity Type may be vtiger_groups,roles,rs and vtiger_users - Datatype::String
  * 	$toEntityType -- The Entity Type may be vtiger_groups,roles,rs and vtiger_users - Datatype::String
  * 	$shareEntityId -- The id of the group,role,rs,user to be shared 
  * 	$toEntityId -- The id of the group,role,rs,user to which the specified entity is to be shared
  * 	$sharePermisson -- This can have the following values:
  *                       0 - Read Only
  *                       1 - Read/Write
  * This function will return the shareid as output
  */
function updateSharingRule($shareid,$tabid,$shareEntityType,$toEntityType,$shareEntityId,$toEntityId,$sharePermission)
{
	global $log;
	$log->debug("Entering updateSharingRule(".$shareid.",".$tabid.",".$shareEntityType.",".$toEntityType.",".$shareEntityId.",".$toEntityId.",".$sharePermission.") method ...");
	
	global $adb;
	$query2="select * from vtiger_datashare_module_rel where shareid=".$shareid;
	$res=$adb->query($query2);
	$typestr=$adb->query_result($res,0,'relationtype');
	$tabname=getDSTableNameForType($typestr);
	$query3="delete from ".$tabname." where shareid=".$shareid;
	$adb->query($query3);
		

	if($shareEntityType == 'groups' && $toEntityType == 'groups')
	{
		$type_string='GRP::GRP';
		$query = "insert into vtiger_datashare_grp2grp values(".$shareid.", ".$shareEntityId.", ".$toEntityId.", ".$sharePermission.")";
	}
	elseif($shareEntityType == 'groups' && $toEntityType == 'roles')
	{
		
		$type_string='GRP::ROLE';
		$query = "insert into vtiger_datashare_grp2role values(".$shareid.", ".$shareEntityId.", '".$toEntityId."', ".$sharePermission.")";
	}
	elseif($shareEntityType == 'groups' && $toEntityType == 'rs')
	{
		
		$type_string='GRP::RS';
		$query = "insert into vtiger_datashare_grp2rs values(".$shareid.", ".$shareEntityId.", '".$toEntityId."', ".$sharePermission.")";
	}
	elseif($shareEntityType == 'roles' && $toEntityType == 'groups')
	{
		
		$type_string='ROLE::GRP';
		$query = "insert into vtiger_datashare_role2group values(".$shareid.", '".$shareEntityId."', ".$toEntityId.", ".$sharePermission.")";
	}
	elseif($shareEntityType == 'roles' && $toEntityType == 'roles')
	{
		
		$type_string='ROLE::ROLE';
		$query = "insert into vtiger_datashare_role2role values(".$shareid.", '".$shareEntityId."', '".$toEntityId."', ".$sharePermission.")";
	}
	elseif($shareEntityType == 'roles' && $toEntityType == 'rs')
	{
		
		$type_string='ROLE::RS';
		$query = "insert into vtiger_datashare_role2rs values(".$shareid.", '".$shareEntityId."', '".$toEntityId."', ".$sharePermission.")";
	}
	elseif($shareEntityType == 'rs' && $toEntityType == 'groups')
	{
		
		$type_string='RS::GRP';
		$query = "insert into vtiger_datashare_rs2grp values(".$shareid.", '".$shareEntityId."', ".$toEntityId.", ".$sharePermission.")";
	}
	elseif($shareEntityType == 'rs' && $toEntityType == 'roles')
	{
		
		$type_string='RS::ROLE';
		$query = "insert into vtiger_datashare_rs2role values(".$shareid.", '".$shareEntityId."', '".$toEntityId."', ".$sharePermission.")";
	}
	elseif($shareEntityType == 'rs' && $toEntityType == 'rs')
	{
		
		$type_string='RS::RS';
		$query = "insert into vtiger_datashare_rs2rs values(".$shareid.", '".$shareEntityId."', '".$toEntityId."', ".$sharePermission.")";
	}
	
	$query1 = "update vtiger_datashare_module_rel set relationtype='".$type_string."' where shareid=".$shareid;
	$adb->query($query1);		
	$adb->query($query);	
	$log->debug("Exiting updateSharingRule method ...");
	return $shareid;	
	
}


/** This function is to delete the organisation level sharing rule 
  * It takes the following input parameters:
  *     $shareid -- Id of the Sharing Rule to be updated
  */
function deleteSharingRule($shareid)
{
	global $log;
	$log->debug("Entering deleteSharingRule(".$shareid.") method ...");
	global $adb;
	$query2="select * from vtiger_datashare_module_rel where shareid=".$shareid;
	$res=$adb->query($query2);
	$typestr=$adb->query_result($res,0,'relationtype');
	$tabname=getDSTableNameForType($typestr);
	$query3="delete from ".$tabname." where shareid=".$shareid;
	$adb->query($query3);
	$query4="delete from vtiger_datashare_module_rel where shareid=".$shareid;
	$adb->query($query4);

	//deleting the releated module sharing permission
	$query5="delete from vtiger_datashare_relatedmodule_permission where shareid=".$shareid;
	$adb->query($query5);
	$log->debug("Exiting deleteSharingRule method ...");
	
}

/** Function get the Data Share Table and their columns
  * @returns -- Data Share Table and Column Array in the following format:
  *  $dataShareTableColArr=Array('datashare_grp2grp'=>'share_groupid::to_groupid',
  *				    'datashare_grp2role'=>'share_groupid::to_roleid',
  *				    'datashare_grp2rs'=>'share_groupid::to_roleandsubid',
  * 				    'datashare_role2group'=>'share_roleid::to_groupid',
  *				    'datashare_role2role'=>'share_roleid::to_roleid',
  *				    'datashare_role2rs'=>'share_roleid::to_roleandsubid',
  *				    'datashare_rs2grp'=>'share_roleandsubid::to_groupid',
  *				    'datashare_rs2role'=>'share_roleandsubid::to_roleid',
  *				    'datashare_rs2rs'=>'share_roleandsubid::to_roleandsubid');
  */
function getDataShareTableandColumnArray()
{
	global $log;
	$log->debug("Entering getDataShareTableandColumnArray() method ...");
	$dataShareTableColArr=Array('vtiger_datashare_grp2grp'=>'share_groupid::to_groupid',
				    'vtiger_datashare_grp2role'=>'share_groupid::to_roleid',
				    'vtiger_datashare_grp2rs'=>'share_groupid::to_roleandsubid',
				    'vtiger_datashare_role2group'=>'share_roleid::to_groupid',
				    'vtiger_datashare_role2role'=>'share_roleid::to_roleid',
				    'vtiger_datashare_role2rs'=>'share_roleid::to_roleandsubid',
				    'vtiger_datashare_rs2grp'=>'share_roleandsubid::to_groupid',
				    'vtiger_datashare_rs2role'=>'share_roleandsubid::to_roleid',
				    'vtiger_datashare_rs2rs'=>'share_roleandsubid::to_roleandsubid');
	$log->debug("Exiting getDataShareTableandColumnArray method ...");
	return $dataShareTableColArr;	
					
}



/** Function get the Data Share Column Names for the specified Table Name
 *  @param $tableName -- DataShare Table Name :: Type Varchar
 *  @returns Column Name -- Type Varchar
 *
 */ 
function getDSTableColumns($tableName)
{
	global $log;
	$log->debug("Entering getDSTableColumns(".$tableName.") method ...");
	$dataShareTableColArr=getDataShareTableandColumnArray();
	
	$dsTableCols=$dataShareTableColArr[$tableName];
	$dsTableColsArr=explode('::',$dsTableCols);	
	$log->debug("Exiting getDSTableColumns method ...");
	return $dsTableColsArr;	
					
}


/** Function get the Data Share Table Names
 *  @returns the following Date Share Table Name Array:  
 *  $dataShareTableColArr=Array('GRP::GRP'=>'datashare_grp2grp',
 * 				    'GRP::ROLE'=>'datashare_grp2role',
 *				    'GRP::RS'=>'datashare_grp2rs',
 *				    'ROLE::GRP'=>'datashare_role2group',
 *				    'ROLE::ROLE'=>'datashare_role2role',
 *				    'ROLE::RS'=>'datashare_role2rs',
 *				    'RS::GRP'=>'datashare_rs2grp',
 *				    'RS::ROLE'=>'datashare_rs2role',
 *				    'RS::RS'=>'datashare_rs2rs');
 */
function getDataShareTableName()
{
	global $log;
	$log->debug("Entering getDataShareTableName() method ...");
	$dataShareTableColArr=Array('GRP::GRP'=>'vtiger_datashare_grp2grp',
				    'GRP::ROLE'=>'vtiger_datashare_grp2role',
				    'GRP::RS'=>'vtiger_datashare_grp2rs',
				    'ROLE::GRP'=>'vtiger_datashare_role2group',
				    'ROLE::ROLE'=>'vtiger_datashare_role2role',
				    'ROLE::RS'=>'vtiger_datashare_role2rs',
				    'RS::GRP'=>'vtiger_datashare_rs2grp',
				    'RS::ROLE'=>'vtiger_datashare_rs2role',
				    'RS::RS'=>'vtiger_datashare_rs2rs');
	$log->debug("Exiting getDataShareTableName method ...");
	return $dataShareTableColArr;	
					
}

/** Function to get the Data Share Table Name from the speciified type string
 *  @param $typeString -- Datashare Type Sting :: Type Varchar
 *  @returns Table Name -- Type Varchar
 *
 */
function getDSTableNameForType($typeString)
{
	global $log;
	$log->debug("Entering getDSTableNameForType(".$typeString.") method ...");
	$dataShareTableColArr=getDataShareTableName();
	$tableName=$dataShareTableColArr[$typeString];
	$log->debug("Exiting getDSTableNameForType method ...");
	return $tableName;	
					
}

/** Function to get the Entity type from the specified DataShare Table Column Name
 *  @param $colname -- Datashare Table Column Name :: Type Varchar
 *  @returns The entity type. The entity type may be vtiger_groups or vtiger_roles or rs -- Type Varchar
 */
function getEntityTypeFromCol($colName)
{
	global $log;
	$log->debug("Entering getEntityTypeFromCol(".$colName.") method ...");

        if($colName == 'share_groupid' || $colName == 'to_groupid')
        {
                $entity_type='groups';
        }
        elseif($colName =='share_roleid' || $colName =='to_roleid')
        {
                $entity_type='roles';
        }
	elseif($colName == 'share_roleandsubid' || $colName == 'to_roleandsubid')
        {
                $entity_type='rs';
        }
	
	$log->debug("Exiting getEntityTypeFromCol method ...");
	return $entity_type;

}

/** Function to get the Entity Display Link
 *  @param $entityid -- Entity Id 
 *  @params $entityType --  The entity type may be vtiger_groups or vtiger_roles or rs -- Type Varchar
 *  @returns the Entity Display link  
 */
function getEntityDisplayLink($entityType,$entityid)
{
	global $log;
	$log->debug("Entering getEntityDisplayLink(".$entityType.",".$entityid.") method ...");
	if($entityType == 'groups')
	{
		$groupNameArr = getGroupInfo($entityid); 
		$display_out = "<a href='index.php?module=Settings&action=GroupDetailView&returnaction=OrgSharingDetailView&groupId=".$entityid."'>Group::". $groupNameArr[0]." </a>";			
	}
	elseif($entityType == 'roles')
	{
		$roleName=getRoleName($entityid);	
		$display_out = "<a href='index.php?module=Settings&action=RoleDetailView&returnaction=OrgSharingDetailView&roleid=".$entityid."'>Role::".$roleName. "</a>";			
	}
	elseif($entityType == 'rs')
	{
		$roleName=getRoleName($entityid);	
		$display_out = "<a href='index.php?module=Settings&action=RoleDetailView&returnaction=OrgSharingDetailView&roleid=".$entityid."'>RoleAndSubordinate::".$roleName. "</a>";			
	}
	$log->debug("Exiting getEntityDisplayLink method ...");
	return $display_out;
	
}


/** Function to get the Sharing rule Info
 *  @param $shareId -- Sharing Rule Id 
 *  @returns Sharing Rule Information Array in the following format:
 *    $shareRuleInfoArr=Array($shareId, $tabid, $type, $share_ent_type, $to_ent_type, $share_entity_id, $to_entity_id,$permission);
 */
function getSharingRuleInfo($shareId)
{
	global $log;
	$log->debug("Entering getSharingRuleInfo(".$shareId.") method ...");
	global $adb;
	$shareRuleInfoArr=Array();
	$query="select * from vtiger_datashare_module_rel where shareid=".$shareId;
	$result=$adb->query($query);
	//Retreving the Sharing Tabid
	$tabid=$adb->query_result($result,0,'tabid');
	$type=$adb->query_result($result,0,'relationtype');
	
	//Retreiving the Sharing Table Name
	$tableName=getDSTableNameForType($type);

	//Retreiving the Sharing Col Names
	$dsTableColArr=getDSTableColumns($tableName);
	$share_ent_col=$dsTableColArr[0];
	$to_ent_col=$dsTableColArr[1];

	//Retreiving the Sharing Entity Col Types
	$share_ent_type=getEntityTypeFromCol($share_ent_col);
	$to_ent_type=getEntityTypeFromCol($to_ent_col);

	//Retreiving the Value from Table
	$query1="select * from ".$tableName." where shareid=".$shareId;
	$result1=$adb->query($query1);
	$share_id=$adb->query_result($result1,0,$share_ent_col);
	$to_id=$adb->query_result($result1,0,$to_ent_col);
	$permission=$adb->query_result($result1,0,'permission');

	//Constructing the Array
	$shareRuleInfoArr[]=$shareId;
	$shareRuleInfoArr[]=$tabid;
	$shareRuleInfoArr[]=$type;
	$shareRuleInfoArr[]=$share_ent_type;
	$shareRuleInfoArr[]=$to_ent_type;
	$shareRuleInfoArr[]=$share_id;
	$shareRuleInfoArr[]=$to_id;
	$shareRuleInfoArr[]=$permission;
		
	$log->debug("Exiting getSharingRuleInfo method ...");
	return $shareRuleInfoArr;	
		
	
	
}

/** This function is to retreive the list of related sharing modules for the specifed module 
  * It takes the following input parameters:
  *     $tabid -- The module tabid:: Type Integer
  */

function getRelatedSharingModules($tabid)
{
	global $log;
	$log->debug("Entering getRelatedSharingModules(".$tabid.") method ...");
	global $adb;
	$relatedSharingModuleArray=Array();
	$query="select * from vtiger_datashare_relatedmodules where tabid=".$tabid;
	$result=$adb->query($query);
	$num_rows=$adb->num_rows($result);
	for($i=0;$i<$num_rows;$i++)
	{
		$ds_relmod_id=$adb->query_result($result,$i,'datashare_relatedmodule_id');
		$rel_tabid=$adb->query_result($result,$i,'relatedto_tabid');
		$relatedSharingModuleArray[$rel_tabid]=$ds_relmod_id;
		
	}
	$log->debug("Exiting getRelatedSharingModules method ...");
	return $relatedSharingModuleArray;
	
}


/** This function is to add the related module sharing permission for a particulare Sharing Rule 
  * It takes the following input parameters:
  *     $shareid -- The Sharing Rule Id:: Type Integer
  *     $tabid -- The module tabid:: Type Integer
  *     $relatedtabid -- The related module tabid:: Type Integer
  * 	$sharePermisson -- This can have the following values:
  *                       0 - Read Only
  *                       1 - Read/Write
  */

function addRelatedModuleSharingPermission($shareid,$tabid,$relatedtabid,$sharePermission)
{
	global $log;
	$log->debug("Entering addRelatedModuleSharingPermission(".$shareid.",".$tabid.",".$relatedtabid.",".$sharePermission.") method ...");
	global $adb;
	$relatedModuleSharingId=getRelatedModuleSharingId($tabid,$relatedtabid);	
	$query="insert into vtiger_datashare_relatedmodule_permission values(".$shareid.", ".$relatedModuleSharingId.", ".$sharePermission.")" ;
	$result=$adb->query($query);
	$log->debug("Exiting addRelatedModuleSharingPermission method ...");
}

/** This function is to update the related module sharing permission for a particulare Sharing Rule 
  * It takes the following input parameters:
  *     $shareid -- The Sharing Rule Id:: Type Integer
  *     $tabid -- The module tabid:: Type Integer
  *     $relatedtabid -- The related module tabid:: Type Integer
  * 	$sharePermisson -- This can have the following values:
  *                       0 - Read Only
  *                       1 - Read/Write
  */

function updateRelatedModuleSharingPermission($shareid,$tabid,$relatedtabid,$sharePermission)
{
	global $log;
	$log->debug("Entering updateRelatedModuleSharingPermission(".$shareid.",".$tabid.",".$relatedtabid.",".$sharePermission.") method ...");
	global $adb;
	$relatedModuleSharingId=getRelatedModuleSharingId($tabid,$relatedtabid);
	$query="update vtiger_datashare_relatedmodule_permission set permission=".$sharePermission." where shareid=".$shareid." and datashare_relatedmodule_id=".$relatedModuleSharingId;		
	$result=$adb->query($query);
	$log->debug("Exiting updateRelatedModuleSharingPermission method ...");
}

/** This function is to retreive the Related Module Sharing Id
  * It takes the following input parameters:
  *     $tabid -- The module tabid:: Type Integer
  *     $related_tabid -- The related module tabid:: Type Integer
  * This function returns the Related Module Sharing Id
  */

function getRelatedModuleSharingId($tabid,$related_tabid)
{
	global $log;
	$log->debug("Entering getRelatedModuleSharingId(".$tabid.",".$related_tabid.") method ...");
	global $adb;
	$query="select datashare_relatedmodule_id from vtiger_datashare_relatedmodules where tabid=".$tabid." and relatedto_tabid=".$related_tabid ;
	$result=$adb->query($query);
	$relatedModuleSharingId=$adb->query_result($result,0,'datashare_relatedmodule_id');
	$log->debug("Exiting getRelatedModuleSharingId method ...");
	return $relatedModuleSharingId;
	
}

/** This function is to retreive the Related Module Sharing Permissions for the specified Sharing Rule 
  * It takes the following input parameters:
  *     $shareid -- The Sharing Rule Id:: Type Integer
  *This function will return the Related Module Sharing permissions in an Array in the following format:
  *     $PermissionArray=($relatedTabid1=>$sharingPermission1,
  *			  $relatedTabid2=>$sharingPermission2,
  *					|
  *                                     |
  *                       $relatedTabid-n=>$sharingPermission-n) 
  */
function getRelatedModuleSharingPermission($shareid)
{
	global $log;
	$log->debug("Entering getRelatedModuleSharingPermission(".$shareid.") method ...");
	global $adb;
	$relatedSharingModulePermissionArray=Array();
	$query="select vtiger_datashare_relatedmodules.*,vtiger_datashare_relatedmodule_permission.permission from vtiger_datashare_relatedmodules inner join vtiger_datashare_relatedmodule_permission on vtiger_datashare_relatedmodule_permission.datashare_relatedmodule_id=vtiger_datashare_relatedmodules.datashare_relatedmodule_id where vtiger_datashare_relatedmodule_permission.shareid=".$shareid;
	$result=$adb->query($query);
	$num_rows=$adb->num_rows($result);
	for($i=0;$i<$num_rows;$i++)
	{
		$relatedto_tabid=$adb->query_result($result,$i,'relatedto_tabid');
		$permission=$adb->query_result($result,$i,'permission');
		$relatedSharingModulePermissionArray[$relatedto_tabid]=$permission;
		
	
	}
	$log->debug("Exiting getRelatedModuleSharingPermission method ...");
	return $relatedSharingModulePermissionArray;	
	
}


/** This function is to retreive the vtiger_profiles associated with the  the specified user 
  * It takes the following input parameters:
  *     $userid -- The User Id:: Type Integer
  *This function will return the vtiger_profiles associated to the specified vtiger_users in an Array in the following format:
  *     $userProfileArray=(profileid1,profileid2,profileid3,...,profileidn);
  */
function getUserProfile($userId)
{
	global $log;
	$log->debug("Entering getUserProfile(".$userId.") method ...");
	global $adb;
	$roleId=fetchUserRole($userId);
	$profArr=Array();	
	$sql1 = "select profileid from vtiger_role2profile where roleid='" .$roleId."'";
        $result1 = $adb->query($sql1);
	$num_rows=$adb->num_rows($result1);
	for($i=0;$i<$num_rows;$i++)
	{
		
        	$profileid=  $adb->query_result($result1,$i,"profileid");
		$profArr[]=$profileid;
	}
		$log->debug("Exiting getUserProfile method ...");
        return $profArr;	
	
}

/** To retreive the global permission of the specifed user from the various vtiger_profiles associated with the user  
  * @param $userid -- The User Id:: Type Integer
  * @returns  user global permission  array in the following format:
  *     $gloabalPerrArray=(view all action id=>permission,
			   edit all action id=>permission)							);
  */
function getCombinedUserGlobalPermissions($userId)
{
	global $log;
	$log->debug("Entering getCombinedUserGlobalPermissions(".$userId.") method ...");
	global $adb;
	$profArr=getUserProfile($userId);
	$no_of_profiles=sizeof($profArr);
	$userGlobalPerrArr=Array();
	
	$userGlobalPerrArr=getProfileGlobalPermission($profArr[0]);			
	if($no_of_profiles != 1)
	{
			for($i=1;$i<$no_of_profiles;$i++)
		{
			$tempUserGlobalPerrArr=getProfileGlobalPermission($profArr[$i]);
		
			foreach($userGlobalPerrArr as $globalActionId=>$globalActionPermission)
			{
				if($globalActionPermission == 1)
				{
					$now_permission = $tempUserGlobalPerrArr[$globalActionId];
					if($now_permission == 0)
					{
						$userGlobalPerrArr[$globalActionId]=$now_permission;
					}
 			
	
				}
		
			}	
			
		}

	}
			
	$log->debug("Exiting getCombinedUserGlobalPermissions method ...");
	return $userGlobalPerrArr;

}

/** To retreive the vtiger_tab permissions of the specifed user from the various vtiger_profiles associated with the user  
  * @param $userid -- The User Id:: Type Integer
  * @returns  user global permission  array in the following format:
  *     $tabPerrArray=(tabid1=>permission,
  *			   tabid2=>permission)							);
  */
function getCombinedUserTabsPermissions($userId)
{
	global $log;
	$log->debug("Entering getCombinedUserTabsPermissions(".$userId.") method ...");
	global $adb;
	$profArr=getUserProfile($userId);
	$no_of_profiles=sizeof($profArr);
	$userTabPerrArr=Array();

	$userTabPerrArr=getProfileTabsPermission($profArr[0]);
	if($no_of_profiles != 1)
	{
		for($i=1;$i<$no_of_profiles;$i++)
		{
			$tempUserTabPerrArr=getProfileTabsPermission($profArr[$i]);

			foreach($userTabPerrArr as $tabId=>$tabPermission)
			{
				if($tabPermission == 1)
				{
					$now_permission = $tempUserTabPerrArr[$tabId];
					if($now_permission == 0)
					{
						$userTabPerrArr[$tabId]=$now_permission;
					}


				}

			}	

		}

	}
	$log->debug("Exiting getCombinedUserTabsPermissions method ...");
	return $userTabPerrArr;

}

/** To retreive the vtiger_tab acion permissions of the specifed user from the various vtiger_profiles associated with the user  
  * @param $userid -- The User Id:: Type Integer
  * @returns  user global permission  array in the following format:
  *     $actionPerrArray=(tabid1=>permission,
  *			   tabid2=>permission);
 */
function getCombinedUserActionPermissions($userId)
{
	global $log;
	$log->debug("Entering getCombinedUserActionPermissions(".$userId.") method ...");
	global $adb;
	$profArr=getUserProfile($userId);
	$no_of_profiles=sizeof($profArr);
	$actionPerrArr=Array();

	$actionPerrArr=getProfileAllActionPermission($profArr[0]);
	if($no_of_profiles != 1)
	{
		for($i=1;$i<$no_of_profiles;$i++)
		{
			$tempActionPerrArr=getProfileAllActionPermission($profArr[$i]);

			foreach($actionPerrArr as $tabId=>$perArr)
			{
				foreach($perArr as $actionid=>$per)
				{	
					if($per == 1)
					{
						$now_permission = $tempActionPerrArr[$tabId][$actionid];
						if($now_permission == 0)
						{
							$actionPerrArr[$tabId][$actionid]=$now_permission;
						}


					}
				}

			}	

		}

	}
	$log->debug("Exiting getCombinedUserActionPermissions method ...");
	return $actionPerrArr;

}

/** To retreive the parent vtiger_role of the specified vtiger_role 
  * @param $roleid -- The Role Id:: Type varchar
  * @returns  parent vtiger_role array in the following format:
  *     $parentRoleArray=(roleid1,roleid2,.......,roleidn);
 */
function getParentRole($roleId)
{
	global $log;
	$log->debug("Entering getParentRole(".$roleId.") method ...");
	$roleInfo=getRoleInformation($roleId);
	$parentRole=$roleInfo[$roleId][1];
	$tempParentRoleArr=explode('::',$parentRole);
	$parentRoleArr=Array();
	foreach($tempParentRoleArr as $role_id)
	{
		if($role_id != $roleId)
		{
			$parentRoleArr[]=$role_id;
		}
	}
	$log->debug("Exiting getParentRole method ...");
	return $parentRoleArr;
	
}

/** To retreive the subordinate vtiger_roles of the specified parent vtiger_role  
  * @param $roleid -- The Role Id:: Type varchar
  * @returns  subordinate vtiger_role array in the following format:
  *     $subordinateRoleArray=(roleid1,roleid2,.......,roleidn);
 */
function getRoleSubordinates($roleId)
{
	global $log;
	$log->debug("Entering getRoleSubordinates(".$roleId.") method ...");
	global $adb;
	$roleDetails=getRoleInformation($roleId);
	$roleInfo=$roleDetails[$roleId];
	$roleParentSeq=$roleInfo[1];
	
	$query="select * from vtiger_role where parentrole like '".$roleParentSeq."::%' order by parentrole asc";
	$result=$adb->query($query);
	$num_rows=$adb->num_rows($result);
	$roleSubordinates=Array();
	for($i=0;$i<$num_rows;$i++)
	{
		$roleid=$adb->query_result($result,$i,'roleid');
                
		$roleSubordinates[]=$roleid;
		
	}
	$log->debug("Exiting getRoleSubordinates method ...");
	return $roleSubordinates;	

}

/** To retreive the subordinate vtiger_roles and vtiger_users of the specified parent vtiger_role  
  * @param $roleid -- The Role Id:: Type varchar
  * @returns  subordinate vtiger_role array in the following format:
  *     $subordinateRoleUserArray=(roleid1=>Array(userid1,userid2,userid3),
                               vtiger_roleid2=>Array(userid1,userid2,userid3)
				                |
						|
			       vtiger_roleidn=>Array(userid1,userid2,userid3));
 */
function getSubordinateRoleAndUsers($roleId)
{
	global $log;
	$log->debug("Entering getSubordinateRoleAndUsers(".$roleId.") method ...");
	global $adb;
	$subRoleAndUsers=Array();
	$subordinateRoles=getRoleSubordinates($roleId);
	foreach($subordinateRoles as $subRoleId)
	{
		$userArray=getRoleUsers($subRoleId);
		$subRoleAndUsers[$subRoleId]=$userArray;

	}
	$log->debug("Exiting getSubordinateRoleAndUsers method ...");
	return $subRoleAndUsers;	
		
}

function getCurrentUserProfileList()
{
	global $log;
	$log->debug("Entering getCurrentUserProfileList() method ...");
        global $current_user;
        require('user_privileges/user_privileges_'.$current_user->id.'.php');
        $profList ='(';
        $i=0;
        foreach ($current_user_profiles as $profid)
        {
                if($i != 0)
                {
                        $profList .= ', ';
                }
                $profList .= $profid;
                $i++;
        }
        $profList .=')';
	$log->debug("Exiting getCurrentUserProfileList method ...");
        return $profList;

}


function getCurrentUserGroupList()
{
	global $log;
	$log->debug("Entering getCurrentUserGroupList() method ...");
        global $current_user;
        require('user_privileges/user_privileges_'.$current_user->id.'.php');
	$grpList='';
	if(sizeof($current_user_groups) > 0)
	{
        	$grpList .='(';
       	 	$i=0;
        	foreach ($current_user_groups as $grpid)
        	{
                	if($i != 0)
                	{
                        	$grpList .= ', ';
                	}
                	$grpList .= $grpid;
                	$i++;
        	}
        	$grpList .=')';
	}
	$log->debug("Exiting getCurrentUserGroupList method ...");
       	 return $grpList;
}

function getSubordinateUsersList()
{
	global $log;
	$log->debug("Entering getSubordinateUsersList() method ...");
        global $current_user;
	$user_array=Array();
        require('user_privileges/user_privileges_'.$current_user->id.'.php');

	if(sizeof($subordinate_roles_users) > 0)
	{	
        	foreach ($subordinate_roles_users as $roleid => $userArray)
        	{
			foreach($userArray as $userid)
			{
				if(! in_array($userid,$user_array))
				{
					$user_array[]=$userid;
				}
			}
        	}
	}
	$subUserList = constructList($user_array,'INTEGER');	
	$log->debug("Exiting getSubordinateUsersList method ...");
       	return $subUserList;

}

function getReadSharingUsersList($module)
{
	global $log;
	$log->debug("Entering getReadSharingUsersList(".$module.") method ...");
	global $adb;
	global $current_user;
	$user_array=Array();
	$tabid=getTabid($module);
	$query = "select shareduserid from vtiger_tmp_read_user_sharing_per where userid=".$current_user->id." and tabid=".$tabid;
	$result=$adb->query($query);
	$num_rows=$adb->num_rows($result);
	for($i=0;$i<$num_rows;$i++)
	{
		$user_id=$adb->query_result($result,$i,'shareduserid');
		$user_array[]=$user_id;
	}
	$shareUserList=constructList($user_array,'INTEGER');
	$log->debug("Exiting getReadSharingUsersList method ...");
	return $shareUserList;
}

function getReadSharingGroupsList($module)
{
	global $log;
	$log->debug("Entering getReadSharingGroupsList(".$module.") method ...");
	global $adb;
	global $current_user;
	$grp_array=Array();
	$tabid=getTabid($module);
	$query = "select sharedgroupid from vtiger_tmp_read_group_sharing_per where userid=".$current_user->id." and tabid=".$tabid;
	$result=$adb->query($query);
	$num_rows=$adb->num_rows($result);
	for($i=0;$i<$num_rows;$i++)
	{
		$grp_id=$adb->query_result($result,$i,'sharedgroupid');
		$grp_array[]=$grp_id;
	}
	$shareGrpList=constructList($grp_array,'INTEGER');
	$log->debug("Exiting getReadSharingGroupsList method ...");
	return $shareGrpList;
}

function getWriteSharingGroupsList($module)
{
	global $log;
	$log->debug("Entering getWriteSharingGroupsList(".$module.") method ...");
	global $adb;
	global $current_user;
	$grp_array=Array();
	$tabid=getTabid($module);
	$query = "select sharedgroupid from vtiger_tmp_write_group_sharing_per where userid=".$current_user->id." and tabid=".$tabid;
	$result=$adb->query($query);
	$num_rows=$adb->num_rows($result);
	for($i=0;$i<$num_rows;$i++)
	{
		$grp_id=$adb->query_result($result,$i,'sharedgroupid');
		$grp_array[]=$grp_id;
	}
	$shareGrpList=constructList($grp_array,'INTEGER');
	$log->debug("Exiting getWriteSharingGroupsList method ...");
	return $shareGrpList;
}

function constructList($array,$data_type)
{
	global $log;
	$log->debug("Entering constructList(".$array.",".$data_type.") method ...");
	$list="";
	if(sizeof($array) > 0)
	{
		$i=0;
		$list .= "(";
		foreach($array as $value)
		{
			if($i != 0)
			{
				$list .= ", ";
			}
			if($data_type == "INTEGER")
			{
				$list .= $value;
			}
			elseif($data_type == "VARCHAR")
			{
				$list .= "'".$value."'"; 
			}
			$i++;		
		}
		$list.=")";
	}
	$log->debug("Exiting constructList method ...");
	return $list;	
}

function getListViewSecurityParameter($module)
{
	global $log;
	$log->debug("Entering getListViewSecurityParameter(".$module.") method ...");
	global $adb;
	global $current_user;

	$tabid=getTabid($module);
	global $current_user;
	if($current_user)
	{
        	require('user_privileges/user_privileges_'.$current_user->id.'.php');
        	require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
	}

	if($module == 'Leads')
	{
		$sec_query .= "and (vtiger_crmentity.smownerid in($current_user->id) or vtiger_crmentity.smownerid in(select vtiger_user2role.userid from vtiger_user2role inner join vtiger_users on vtiger_users.id=vtiger_user2role.userid inner join vtiger_role on vtiger_role.roleid=vtiger_user2role.roleid where vtiger_role.parentrole like '".$current_user_parent_role_seq."::%') or vtiger_crmentity.smownerid in(select shareduserid from vtiger_tmp_read_user_sharing_per where userid=".$current_user->id." and tabid=".$tabid.") or (vtiger_crmentity.smownerid in (0) and (";

                        if(sizeof($current_user_groups) > 0)
                        {
                              $sec_query .= "vtiger_groups.groupid in".getCurrentUserGroupList()." or ";
                        }
                         $sec_query .= "vtiger_groups.groupid in(select vtiger_tmp_read_group_sharing_per.sharedgroupid from vtiger_tmp_read_group_sharing_per where userid=".$current_user->id." and tabid=".$tabid.")))) ";	
	}
	elseif($module == 'Accounts')
	{
		$sec_query .= "and (vtiger_crmentity.smownerid in($current_user->id) or vtiger_crmentity.smownerid in(select vtiger_user2role.userid from vtiger_user2role inner join vtiger_users on vtiger_users.id=vtiger_user2role.userid inner join vtiger_role on vtiger_role.roleid=vtiger_user2role.roleid where vtiger_role.parentrole like '".$current_user_parent_role_seq."::%') or vtiger_crmentity.smownerid in(select shareduserid from vtiger_tmp_read_user_sharing_per where userid=".$current_user->id." and tabid=".$tabid.") or (vtiger_crmentity.smownerid in (0) and (";

                if(sizeof($current_user_groups) > 0)
                {
                	$sec_query .= "vtiger_groups.groupid in".getCurrentUserGroupList()." or ";
                }
		$sec_query .= "vtiger_groups.groupid in(select vtiger_tmp_read_group_sharing_per.sharedgroupid from vtiger_tmp_read_group_sharing_per where userid=".$current_user->id." and tabid=".$tabid.")))) ";			
	
	}
	elseif($module == 'Contacts')
	{
		$sec_query .= "and (vtiger_crmentity.smownerid in($current_user->id) or vtiger_crmentity.smownerid in(select vtiger_user2role.userid from vtiger_user2role inner join vtiger_users on vtiger_users.id=vtiger_user2role.userid inner join vtiger_role on vtiger_role.roleid=vtiger_user2role.roleid where vtiger_role.parentrole like '".$current_user_parent_role_seq."::%') or vtiger_crmentity.smownerid in(select shareduserid from vtiger_tmp_read_user_sharing_per where userid=".$current_user->id." and tabid=".$tabid.") or (vtiger_crmentity.smownerid in (0) and (";

                if(sizeof($current_user_groups) > 0)
                {
                	$sec_query .= "vtiger_groups.groupid in".getCurrentUserGroupList()." or ";
                }
		$sec_query .= "vtiger_groups.groupid in(select vtiger_tmp_read_group_sharing_per.sharedgroupid from vtiger_tmp_read_group_sharing_per where userid=".$current_user->id." and tabid=".$tabid.")))) ";			
	
	}
	elseif($module == 'Potentials')
	{
		$sec_query .= "and (vtiger_crmentity.smownerid in($current_user->id) or vtiger_crmentity.smownerid in(select vtiger_user2role.userid from vtiger_user2role inner join vtiger_users on vtiger_users.id=vtiger_user2role.userid inner join vtiger_role on vtiger_role.roleid=vtiger_user2role.roleid where vtiger_role.parentrole like '".$current_user_parent_role_seq."::%') or vtiger_crmentity.smownerid in(select shareduserid from vtiger_tmp_read_user_sharing_per where userid=".$current_user->id." and tabid=".$tabid.") or vtiger_potential.accountid in (select crmid from vtiger_crmentity where setype='accounts' and vtiger_crmentity.smownerid in(select shareduserid from vtiger_tmp_read_user_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('Accounts')." and relatedtabid=".$tabid.")) or vtiger_potential.accountid in (select crmid from vtiger_crmentity left join vtiger_accountgrouprelation on vtiger_accountgrouprelation.accountid=vtiger_crmentity.crmid inner join vtiger_groups on vtiger_groups.groupname=vtiger_accountgrouprelation.groupname where setype='Accounts' and vtiger_crmentity.smownerid=0 and vtiger_groups.groupid in(select vtiger_tmp_read_group_rel_sharing_per.sharedgroupid from vtiger_tmp_read_group_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('Accounts')." and relatedtabid=".$tabid.")) or (vtiger_crmentity.smownerid in (0) and (";

                if(sizeof($current_user_groups) > 0)
                {
                	$sec_query .= "vtiger_groups.groupid in".getCurrentUserGroupList()." or ";
                }
		$sec_query .= "vtiger_groups.groupid in(select vtiger_tmp_read_group_sharing_per.sharedgroupid from vtiger_tmp_read_group_sharing_per where userid=".$current_user->id." and tabid=".$tabid.")))) ";			
	
	}
	elseif($module == 'HelpDesk')
	{
		$sec_query .= "and (vtiger_crmentity.smownerid in($current_user->id) or vtiger_crmentity.smownerid in(select vtiger_user2role.userid from vtiger_user2role inner join vtiger_users on vtiger_users.id=vtiger_user2role.userid inner join vtiger_role on vtiger_role.roleid=vtiger_user2role.roleid where vtiger_role.parentrole like '".$current_user_parent_role_seq."::%') or vtiger_crmentity.smownerid in(select shareduserid from vtiger_tmp_read_user_sharing_per where userid=".$current_user->id." and tabid=".$tabid.") or vtiger_troubletickets.parent_id in (select crmid from vtiger_crmentity where setype='Accounts' and vtiger_crmentity.smownerid in(select shareduserid from vtiger_tmp_read_user_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('Accounts')." and relatedtabid=".$tabid.")) or vtiger_troubletickets.parent_id in (select crmid from vtiger_crmentity left join vtiger_accountgrouprelation on vtiger_accountgrouprelation.accountid=vtiger_crmentity.crmid inner join vtiger_groups on vtiger_groups.groupname=vtiger_accountgrouprelation.groupname where setype='Accounts' and vtiger_crmentity.smownerid=0 and vtiger_groups.groupid in(select vtiger_tmp_read_group_rel_sharing_per.sharedgroupid from vtiger_tmp_read_group_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('Accounts')." and relatedtabid=".$tabid.")) or (vtiger_crmentity.smownerid in (0) and (";

                if(sizeof($current_user_groups) > 0)
                {
                	$sec_query .= "vtiger_groups.groupid in".getCurrentUserGroupList()." or ";
                }
		$sec_query .= "vtiger_groups.groupid in(select vtiger_tmp_read_group_sharing_per.sharedgroupid from vtiger_tmp_read_group_sharing_per where userid=".$current_user->id." and tabid=".$tabid.")))) ";			
	
	}
	elseif($module == 'Emails')
	{
		$sec_query .= "and vtiger_crmentity.smownerid=".$current_user->id." "; 
	
	}
	elseif($module == 'Calendar')
	{
		require_once('modules/Calendar/CalendarCommon.php');
		$shared_ids = getSharedCalendarId($current_user->id);
		if(isset($shared_ids) && $shared_ids != '')
			$condition = " or (vtiger_crmentity.smownerid in($shared_ids) and vtiger_activity.visibility = 'Public')";
		else
			$condition = null;
		$sec_query .= "and (vtiger_crmentity.smownerid in($current_user->id) $condition or vtiger_crmentity.smownerid in(select vtiger_user2role.userid from vtiger_user2role inner join vtiger_users on vtiger_users.id=vtiger_user2role.userid inner join vtiger_role on vtiger_role.roleid=vtiger_user2role.roleid where vtiger_role.parentrole like '".$current_user_parent_role_seq."::%')";

		if(sizeof($current_user_groups) > 0)
		{
			$sec_query .= " or (vtiger_crmentity.smownerid in (0) and (vtiger_groups.groupid in".getCurrentUserGroupList()."))";
		}
		$sec_query .= ")";	
	}
	elseif($module == 'Quotes')
	{
		$sec_query .= "and (vtiger_crmentity.smownerid in($current_user->id) or vtiger_crmentity.smownerid in(select vtiger_user2role.userid from vtiger_user2role inner join vtiger_users on vtiger_users.id=vtiger_user2role.userid inner join vtiger_role on vtiger_role.roleid=vtiger_user2role.roleid where vtiger_role.parentrole like '".$current_user_parent_role_seq."::%') or vtiger_crmentity.smownerid in(select shareduserid from vtiger_tmp_read_user_sharing_per where userid=".$current_user->id." and tabid=".$tabid.")";

		//Adding crterial for vtiger_account related vtiger_quotes sharing
		 $sec_query .= "or vtiger_quotes.accountid in (select crmid from vtiger_crmentity where setype='Accounts' and vtiger_crmentity.smownerid in(select shareduserid from vtiger_tmp_read_user_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('Accounts')." and relatedtabid=".$tabid.")) or vtiger_quotes.accountid in (select crmid from vtiger_crmentity left join vtiger_accountgrouprelation on vtiger_accountgrouprelation.accountid=vtiger_crmentity.crmid inner join vtiger_groups on vtiger_groups.groupname=vtiger_accountgrouprelation.groupname where setype='Accounts' and vtiger_crmentity.smownerid=0 and vtiger_groups.groupid in(select vtiger_tmp_read_group_rel_sharing_per.sharedgroupid from vtiger_tmp_read_group_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('Accounts')." and relatedtabid=".$tabid."))";
		//Adding crterial for vtiger_potential related vtiger_quotes sharing
		 $sec_query .= " or vtiger_quotes.potentialid in (select crmid from vtiger_crmentity where setype='Potentials' and vtiger_crmentity.smownerid in(select shareduserid from vtiger_tmp_read_user_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('Potentials')." and relatedtabid=".$tabid.")) or vtiger_quotes.potentialid in (select crmid from vtiger_crmentity left join vtiger_potentialgrouprelation on vtiger_potentialgrouprelation.potentialid=vtiger_crmentity.crmid inner join vtiger_groups on vtiger_groups.groupname=vtiger_potentialgrouprelation.groupname where setype='Potentials' and vtiger_crmentity.smownerid=0 and vtiger_groups.groupid in(select vtiger_tmp_read_group_rel_sharing_per.sharedgroupid from vtiger_tmp_read_group_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('Potentials')." and relatedtabid=".$tabid."))";
	

		//Adding crteria for group sharing
		 $sec_query .= " or (vtiger_crmentity.smownerid in (0) and (";

                if(sizeof($current_user_groups) > 0)
                {
                	$sec_query .= "vtiger_groups.groupid in".getCurrentUserGroupList()." or ";
                }
		$sec_query .= "vtiger_groups.groupid in(select vtiger_tmp_read_group_sharing_per.sharedgroupid from vtiger_tmp_read_group_sharing_per where userid=".$current_user->id." and tabid=".$tabid.")))) ";			
	
	}	
	elseif($module == 'PurchaseOrder')
	{
		$sec_query .= "and (vtiger_crmentity.smownerid in($current_user->id) or vtiger_crmentity.smownerid in(select vtiger_user2role.userid from vtiger_user2role inner join vtiger_users on vtiger_users.id=vtiger_user2role.userid inner join vtiger_role on vtiger_role.roleid=vtiger_user2role.roleid where vtiger_role.parentrole like '".$current_user_parent_role_seq."::%') or vtiger_crmentity.smownerid in(select shareduserid from vtiger_tmp_read_user_sharing_per where userid=".$current_user->id." and tabid=".$tabid.") or (vtiger_crmentity.smownerid in (0) and (";

                if(sizeof($current_user_groups) > 0)
                {
                	$sec_query .= "vtiger_groups.groupid in".getCurrentUserGroupList()." or ";
                }
		$sec_query .= "vtiger_groups.groupid in(select vtiger_tmp_read_group_sharing_per.sharedgroupid from vtiger_tmp_read_group_sharing_per where userid=".$current_user->id." and tabid=".$tabid.")))) ";			
	
	}
	elseif($module == 'SalesOrder')
	{
		$sec_query .= "and (vtiger_crmentity.smownerid in($current_user->id) or vtiger_crmentity.smownerid in(select vtiger_user2role.userid from vtiger_user2role inner join vtiger_users on vtiger_users.id=vtiger_user2role.userid inner join vtiger_role on vtiger_role.roleid=vtiger_user2role.roleid where vtiger_role.parentrole like '".$current_user_parent_role_seq."::%') or vtiger_crmentity.smownerid in(select shareduserid from vtiger_tmp_read_user_sharing_per where userid=".$current_user->id." and tabid=".$tabid.")";

		//Adding crterial for vtiger_account related so sharing
		 $sec_query .= "or vtiger_salesorder.accountid in (select crmid from vtiger_crmentity where setype='Accounts' and vtiger_crmentity.smownerid in(select shareduserid from vtiger_tmp_read_user_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('Accounts')." and relatedtabid=".$tabid.")) or vtiger_salesorder.accountid in (select crmid from vtiger_crmentity left join vtiger_accountgrouprelation on vtiger_accountgrouprelation.accountid=vtiger_crmentity.crmid inner join vtiger_groups on vtiger_groups.groupname=vtiger_accountgrouprelation.groupname where setype='Accounts' and vtiger_crmentity.smownerid=0 and vtiger_groups.groupid in(select vtiger_tmp_read_group_rel_sharing_per.sharedgroupid from vtiger_tmp_read_group_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('Accounts')." and relatedtabid=".$tabid."))";
		//Adding crterial for vtiger_potential related so sharing
		 $sec_query .= " or vtiger_salesorder.potentialid in (select crmid from vtiger_crmentity where setype='Potentials' and vtiger_crmentity.smownerid in(select shareduserid from vtiger_tmp_read_user_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('Potentials')." and relatedtabid=".$tabid.")) or vtiger_salesorder.potentialid in (select crmid from vtiger_crmentity left join vtiger_potentialgrouprelation on vtiger_potentialgrouprelation.potentialid=vtiger_crmentity.crmid inner join vtiger_groups on vtiger_groups.groupname=vtiger_potentialgrouprelation.groupname where setype='Potentials' and vtiger_crmentity.smownerid=0 and vtiger_groups.groupid in(select vtiger_tmp_read_group_rel_sharing_per.sharedgroupid from vtiger_tmp_read_group_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('Potentials')." and relatedtabid=".$tabid."))";
		//Adding crterial for vtiger_quotes related so sharing
		 $sec_query .= " or vtiger_salesorder.quoteid in (select crmid from vtiger_crmentity where setype='Quotes' and vtiger_crmentity.smownerid in(select shareduserid from vtiger_tmp_read_user_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('Quotes')." and relatedtabid=".$tabid.")) or vtiger_salesorder.quoteid in (select crmid from vtiger_crmentity left join vtiger_quotegrouprelation on vtiger_quotegrouprelation.quoteid=vtiger_crmentity.crmid inner join vtiger_groups on vtiger_groups.groupname=vtiger_quotegrouprelation.groupname where setype='Quotes' and vtiger_crmentity.smownerid=0 and vtiger_groups.groupid in(select vtiger_tmp_read_group_rel_sharing_per.sharedgroupid from vtiger_tmp_read_group_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('Quotes')." and relatedtabid=".$tabid."))";
	

		//Adding crteria for group sharing
		 $sec_query .= " or (vtiger_crmentity.smownerid in (0) and (";

                if(sizeof($current_user_groups) > 0)
                {
                	$sec_query .= "vtiger_groups.groupid in".getCurrentUserGroupList()." or ";
                }
		$sec_query .= "vtiger_groups.groupid in(select vtiger_tmp_read_group_sharing_per.sharedgroupid from vtiger_tmp_read_group_sharing_per where userid=".$current_user->id." and tabid=".$tabid.")))) ";			
	
	}
	elseif($module == 'Invoice')
	{
		$sec_query .= "and (vtiger_crmentity.smownerid in($current_user->id) or vtiger_crmentity.smownerid in(select vtiger_user2role.userid from vtiger_user2role inner join vtiger_users on vtiger_users.id=vtiger_user2role.userid inner join vtiger_role on vtiger_role.roleid=vtiger_user2role.roleid where vtiger_role.parentrole like '".$current_user_parent_role_seq."::%') or vtiger_crmentity.smownerid in(select shareduserid from vtiger_tmp_read_user_sharing_per where userid=".$current_user->id." and tabid=".$tabid.")";

		//Adding crterial for vtiger_account related vtiger_invoice sharing
		 $sec_query .= "or vtiger_invoice.accountid in (select crmid from vtiger_crmentity where setype='Accounts' and vtiger_crmentity.smownerid in(select shareduserid from vtiger_tmp_read_user_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('Accounts')." and relatedtabid=".$tabid.")) or vtiger_invoice.accountid in (select crmid from vtiger_crmentity left join vtiger_accountgrouprelation on vtiger_accountgrouprelation.accountid=vtiger_crmentity.crmid inner join vtiger_groups on vtiger_groups.groupname=vtiger_accountgrouprelation.groupname where setype='Accounts' and vtiger_crmentity.smownerid=0 and vtiger_groups.groupid in(select vtiger_tmp_read_group_rel_sharing_per.sharedgroupid from vtiger_tmp_read_group_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('Accounts')." and relatedtabid=".$tabid."))";
		//Adding crterial for vtiger_salesorder related vtiger_invoice sharing
		 $sec_query .= " or vtiger_invoice.salesorderid in (select crmid from vtiger_crmentity where setype='SalesOrder' and vtiger_crmentity.smownerid in(select shareduserid from vtiger_tmp_read_user_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('SalesOrder')." and relatedtabid=".$tabid.")) or vtiger_invoice.salesorderid in(select crmid from vtiger_crmentity left join vtiger_sogrouprelation on vtiger_sogrouprelation.salesorderid=vtiger_crmentity.crmid inner join vtiger_groups on vtiger_groups.groupname=vtiger_sogrouprelation.groupname where setype='SalesOrder' and vtiger_crmentity.smownerid=0 and vtiger_groups.groupid in(select vtiger_tmp_read_group_rel_sharing_per.sharedgroupid from vtiger_tmp_read_group_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('SalesOrder')." and relatedtabid=".$tabid."))";
	

		//Adding crteria for group sharing
		 $sec_query .= " or (vtiger_crmentity.smownerid in (0) and (";

                if(sizeof($current_user_groups) > 0)
                {
                	$sec_query .= "vtiger_groups.groupid in".getCurrentUserGroupList()." or ";
                }
		$sec_query .= "vtiger_groups.groupid in(select vtiger_tmp_read_group_sharing_per.sharedgroupid from vtiger_tmp_read_group_sharing_per where userid=".$current_user->id." and tabid=".$tabid.")))) ";			
	
	}
	elseif($module == 'Campaigns')
	{

		$sec_query .= " and (vtiger_crmentity.smownerid in($current_user->id) or vtiger_crmentity.smownerid in(select vtiger_user2role.userid from vtiger_user2role inner join vtiger_users on vtiger_users.id=vtiger_user2role.userid inner join vtiger_role on vtiger_role.roleid=vtiger_user2role.roleid where vtiger_role.parentrole like '".$current_user_parent_role_seq."::%') or vtiger_crmentity.smownerid in(select shareduserid from vtiger_tmp_read_user_sharing_per where userid=".$current_user->id." and tabid=".$tabid.") or (vtiger_crmentity.smownerid in (0) and (";

		if(sizeof($current_user_groups) > 0)
		{
			$sec_query .= "vtiger_groups.groupid in".getCurrentUserGroupList()." or ";
		}
		$sec_query .= "vtiger_groups.groupid in(select vtiger_tmp_read_group_sharing_per.sharedgroupid from vtiger_tmp_read_group_sharing_per where userid=".$current_user->id." and tabid=".$tabid.")))) ";

			
	}	
	else
	{
		$mobObj = new $module;
		$sec_query = $modObj->getListViewSecurityParameter($module);
		
	}
	$log->debug("Exiting getListViewSecurityParameter method ...");
	return $sec_query;	
}

function get_current_user_access_groups($module)
{
	global $log;
	$log->debug("Entering get_current_user_access_groups(".$module.") method ...");
	global $adb,$noof_group_rows;
	$current_user_group_list=getCurrentUserGroupList();
	$sharing_write_group_list=getWriteSharingGroupsList($module);
	$query ="select groupname,groupid from vtiger_groups";
	if($current_user_group_list != '' && $sharing_write_group_list != '')
	{
		$query .= " where (groupid in".$current_user_group_list." or groupid in".$sharing_write_group_list.")";
	}
	elseif($current_user_group_list != '')
	{
		$query .= " where groupid in".$current_user_group_list;	
	}
	elseif($sharing_write_group_list != '')
	{
		$query .= " where groupid in".$sharing_write_group_list;
	}
	$result = $adb->query($query);
	$noof_group_rows=$adb->num_rows($result);
	$log->debug("Exiting get_current_user_access_groups method ...");
	return $result;	
}
/** Function to get the Group Id for a given group groupname
 *  @param $groupname -- Groupname
 *  @returns Group Id -- Type Integer
 */

function getGrpId($groupname)
{
	global $log;
	$log->debug("Entering getGrpId(".$groupname.") method ...");
	global $adb;
	
	$result = $adb->query("select groupid from vtiger_groups where groupname='".$groupname."'");
	$groupid = $adb->query_result($result,0,'groupid');
	$log->debug("Exiting getGrpId method ...");
	return $groupid;
}

/** Function to check permission to access a vtiger_field for a given user
  * @param $fld_module -- Module :: Type String
  * @param $userid -- User Id :: Type integer
  * @param $fieldname -- Field Name :: Type varchar
  * @returns $rolename -- Role Name :: Type varchar
  *
 */
function getFieldVisibilityPermission($fld_module, $userid, $fieldname)
{
	global $log;
	$log->debug("Entering getFieldVisibilityPermission(".$fld_module.",". $userid.",". $fieldname.") method ...");

	global $adb;
	global $current_user;


	require('user_privileges/user_privileges_'.$userid.'.php');

	if($is_admin)
	{
		$log->debug("Exiting getFieldVisibilityPermission method ...");
		return 0;
	}
	else
	{
		//get vtiger_profile list using userid
		$profilelist = getCurrentUserProfileList();

		//get tabid
		$tabid = getTabid($fld_module);

	
		$query="SELECT vtiger_profile2field.* FROM vtiger_field INNER JOIN vtiger_profile2field ON vtiger_profile2field.fieldid=vtiger_field.fieldid INNER JOIN vtiger_def_org_field ON vtiger_def_org_field.fieldid=vtiger_field.fieldid WHERE vtiger_field.tabid=".$tabid." AND vtiger_profile2field.visible=0 AND vtiger_def_org_field.visible=0  AND vtiger_profile2field.profileid in".$profilelist." AND vtiger_field.fieldname='".$fieldname."' GROUP BY vtiger_field.fieldid";
 
 		//Postgres 8 fixes
 		if( $adb->dbType == "pgsql")
 		    $query = fixPostgresQuery( $query, $log, 0);

		
		$result = $adb->query($query);

		$log->debug("Exiting getFieldVisibilityPermission method ...");
		return $adb->query_result($result,"0","visible");
	}
}

/** Function to get the vtiger_field access module array 
  * @returns The vtiger_field Access module Array :: Type Array
  *
 */
function getFieldModuleAccessArray()
{
	global $log;
	global $adb;
	$log->debug("Entering getFieldModuleAccessArray() method ...");

	$fldModArr=Array();
	$query = 'select distinct(name) from vtiger_profile2field inner join vtiger_tab on vtiger_tab.tabid=vtiger_profile2field.tabid where vtiger_tab.tabid != 10';
	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	for($i=0;$i<$num_rows;$i++)
	{
		$fldModArr[$adb->query_result($result,$i,'name')]=$adb->query_result($result,$i,'name');
	}	
		

	/*
	$fldModArr=Array('Leads'=>'LBL_LEAD_FIELD_ACCESS',
                'Accounts'=>'LBL_ACCOUNT_FIELD_ACCESS',
                'Contacts'=>'LBL_CONTACT_FIELD_ACCESS',
                'Potentials'=>'LBL_OPPORTUNITY_FIELD_ACCESS',
                'HelpDesk'=>'LBL_HELPDESK_FIELD_ACCESS',
                'Products'=>'LBL_PRODUCT_FIELD_ACCESS',
                'Notes'=>'LBL_NOTE_FIELD_ACCESS',
                'Calendar'=>'LBL_TASK_FIELD_ACCESS',
                'Events'=>'LBL_EVENT_FIELD_ACCESS',
                'Vendors'=>'LBL_VENDOR_FIELD_ACCESS',
                'PriceBooks'=>'LBL_PB_FIELD_ACCESS',
                'Quotes'=>'LBL_QUOTE_FIELD_ACCESS',
                'PurchaseOrder'=>'LBL_PO_FIELD_ACCESS',
                'SalesOrder'=>'LBL_SO_FIELD_ACCESS',
                'Invoice'=>'LBL_INVOICE_FIELD_ACCESS',
                'Campaigns'=>'LBL_CAMPAIGN_FIELD_ACCESS',
		'Faq'=>'LBL_FAQ_FIELD_ACCESS'
              );
	 */     

	$log->debug("Exiting getFieldModuleAccessArray method ...");
	return $fldModArr;
}

/** Function to get the permitted module name Array with presence as 0 
  * @returns permitted module name Array :: Type Array
  *
 */
function getPermittedModuleNames()
{
	global $log;
	$log->debug("Entering getPermittedModuleNames() method ...");
	global $current_user;
	$permittedModules=Array();
	require('user_privileges/user_privileges_'.$current_user->id.'.php');
	include('tabdata.php');

	if($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1)
	{
		foreach($tab_seq_array as $tabid=>$seq_value)
		{
			if($seq_value ==0 && $profileTabsPermission[$tabid] == 0)
			{
				$permittedModules[]=getTabModuleName($tabid);
			}
			
		}	
	

	}
	else
	{
		foreach($tab_seq_array as $tabid=>$seq_value)
		{
			if($seq_value ==0)
			{
				$permittedModules[]=getTabModuleName($tabid);
			}
			
		}	
	}
	$log->debug("Exiting getPermittedModuleNames method ...");
	return $permittedModules;			
}



/** Function to recalculate the Sharing Rules for all the vtiger_users 
  * This function will recalculate all the sharing rules for all the vtiger_users in the Organization and will write them in flat vtiger_files 
  *
 */
function RecalculateSharingRules()
{
	global $log;
	$log->debug("Entering RecalculateSharingRules() method ...");
	global $adb;
	require_once('modules/Users/CreateUserPrivilegeFile.php');
	$query="select id from vtiger_users where deleted=0";
	$result=$adb->query($query);
	$num_rows=$adb->num_rows($result);
	for($i=0;$i<$num_rows;$i++)
	{
		$id=$adb->query_result($result,$i,'id');	
		createUserPrivilegesfile($id);
	        createUserSharingPrivilegesfile($id);
	}	
	$log->debug("Exiting RecalculateSharingRules method ...");	
				
}

//Code added by Minnie for vtiger_campaigngroup relation -- start

/** Function to add vtiger_campaign group relation
  * @param $campaignid -- Campaign Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
  */
function insert2CampaignGroupRelation($campaignid,$groupname)
{
	global $log;
	$log->debug("Entering insert2CampaignGroupRelation(".$campaignid.",".$groupname.") method ...");
	global $adb;
	$sql = "insert into vtiger_campaigngrouprelation values (" .$campaignid .",'".$groupname."')";
	$adb->query($sql);
	$log->debug("Exiting insert2CampaignGroupRelation method ...");

}

/** Function to update vtiger_campaign group relation
  * @param $campaignid -- Campaign Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
  */
function updateCampaignGroupRelation($campaignid,$groupname)
{
	global $log;
	$log->debug("Entering updateCampaignGroupRelation(".$campaignid.",".$groupname.") method ...");
	global $adb;
	$sqldelete = "delete from vtiger_campaigngrouprelation where campaignid=".$campaignid;
	$adb->query($sqldelete);
	if($groupname) {
		$sql = "insert into vtiger_campaigngrouprelation values (".$campaignid .",'" .$groupname ."')";
		$adb->query($sql);
	}
	$log->debug("Exiting updateCampaignGroupRelation method ...");
}


/** Function to get the list of module for which the user defined sharing rules can be defined  
  * @returns Array:: Type array
  *
  */
function getSharingModuleList()
{
	global $log;
	
	$sharingModuleArray=Array('Accounts',
				 'Leads',
				 'Contacts',
				 'Potentials',
				 'HelpDesk',
				 'Emails',
				 'Campaigns',
				 'Quotes',
				 'PurchaseOrder',
				 'SalesOrder',
				 'Invoice');

	return $sharingModuleArray;					
}


function isCalendarPermittedBySharing($recordId)
{
	global $adb;
	global $current_user;
	$permission = 'no';
	$query = "select * from vtiger_sharedcalendar where userid in(select smownerid from vtiger_activity inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_activity.activityid where activityid=".$recordId." and visibility='Public' and smownerid !=0) and sharedid=".$current_user->id;
	$result=$adb->query($query);
	if($adb->num_rows($result) >0)
	{
		$permission = 'yes';
	}
	return $permission;	
}	

//end					   
?>
