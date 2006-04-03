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
	global $adb;
	//$sql= "select rolename from user2role where userid='" .$userid ."'";
   $sql = "select * from mail_accounts where status=1 and user_id=".$user->id;
        $result = $adb->query($sql);
	return $result;
}

/** To get the Role of the specified user
  * @param $userid -- The user Id:: Type integer
  * @returns  roleid :: Type String
 */
function fetchUserRole($userid)
{
	global $adb;
	//$sql= "select rolename from user2role where userid='" .$userid ."'";
	$sql = "select roleid from user2role where userid='" .$userid ."'";
        $result = $adb->query($sql);
	$roleid=  $adb->query_result($result,0,"roleid");
	return $roleid;
}

/** Depricated. Function to be replaced by getUserProfile()
  * Should be done accross the product 
  * 
 */
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

/** Function to get the lists of groupids releated with an user
 * This function accepts the user id as arguments and 
 * returns the groupids related with the user id
 * as a comma seperated string
*/
function fetchUserGroupids($userid)
{
	global $adb;
        $focus = new GetUserGroups();
        $focus->getAllUserGroups($userid);
        $groupidlists = implode(",",$focus->user_groups);
        return $groupidlists;
		
}

/** Function to load all the permissions
  *
 */
function loadAllPerms()
                {
        global $adb,$MAX_TAB_PER;
        global $persistPermArray;

        $persistPermArray = Array();
        $profiles = Array();
        $sql = "select distinct profileid from profile2tab";
        $result = $adb->query($sql);
        $num_rows = $adb->num_rows($result);
        for ( $i=0; $i < $num_rows; $i++ )
                $profiles[] = $adb->query_result($result,$i,'profileid');

        $persistPermArray = Array();
        foreach ( $profiles as $profileid )
        {
                $sql = "select * from profile2tab where profileid=" .$profileid ;
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
}

/** Function to get all the tab permission for the specified profile
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
	global $persistPermArray;
        global $adb,$MAX_TAB_PER;
        // Mike Crowe Mod --------------------------------------------------------
        if ( $cache_tab_perms )
        {
                if ( count($persistPermArray) == 0 )
                        loadAllPerms();
                return $persistPermArray[$profileid];
        }
        else
        {
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
        // Mike Crowe Mod ---------------------------------------------------------------- 

}

/** Function to get all the tab permission for the specified profile other than tabid 15
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
                return $tab_perr_array;
        }
        else
        {
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

}

/** Function to get all the tab standard action permission for the specified profile
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

/** Function to get all the tab utility action permission for the specified profile
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
	global $adb;
	//retreiving the standard permissions	
	$sql= "select * from def_org_share";
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

/* Deprecated Function. To be removed
 *
 *
*/
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

/* Deprecated Function. To be removed
 *
 *
*/
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

/* Deprecated Function. To be removed
 *
 *
*/
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


/** Function to create the role
  * @param $roleName -- Role Name:: Type varchar
  * @param $parentRoleId -- Parent Role Id:: Type varchar
  * @param $roleProfileArray -- Profile to be associated with this role:: Type Array
  * @returns  the Rold Id :: Type varchar
  *
 */

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

/** Function to update the role
  * @param $roleName -- Role Name:: Type varchar
  * @param $roleId -- Role Id:: Type varchar
  * @param $roleProfileArray -- Profile to be associated with this role:: Type Array
  *
 */
function updateRole($roleId,$roleName,$roleProfileArray)
{
	global $adb;
	$sql1 = "update role set rolename='".$roleName."' where roleid='".$roleId."'";
        $adb->query($sql1);
	//Updating the Role2Profile relation
	$sql2 = "delete from role2profile where roleId='".$roleId."'";
	$adb->query($sql2);

	foreach($roleProfileArray as $profileId)
        {
                if($profileId != '')
                {
                        insertRole2ProfileRelation($roleId,$profileId);
                }
        }
	
	
}

/** Function to add the role to profile relation
  * @param $profileId -- Profile Id:: Type integer
  * @param $roleId -- Role Id:: Type varchar
  *
 */
function insertRole2ProfileRelation($roleId,$profileId)
{
	global $adb;
	$query="insert into role2profile values('".$roleId."',".$profileId.")";
	$adb->query($query);	
	
}


/** Deprecated Function. To be removed
  *
  *
  *
*/
function createNewGroup($groupName,$groupDescription)
{
  global $adb;
  $sql = "insert into groups(name,description) values('" .$groupName ."','". $groupDescription ."')";
  $result = $adb->query($sql); 
  header("Location: index.php?module=Users&action=listgroups");
}


/** Deprecated Function. To be removed
  *
  *
  *
*/
function fetchTabId($moduleName)
{
  global $adb;
  $sql = "select id from tabu where name ='" .$moduleName ."'";
  $result = $adb->query($sql); 
  $tabid =  $adb->query_result($result,0,"id");
  return $tabid;

}

/** Deprecated Function. To be removed
  *
  *
  *
*/
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

/** Deprecated Function. To be removed
  *
  *
  *
*/
function fetchTabReferenceEntityValues($parentrolename)
{
  global $adb;
  $sql = "select tabid,module_permission,description from role2tab where rolename='" .$parentrolename ."'"; 
  //echo $sql;
  $result=$adb->query($sql);
  return $result;

}


/** Deprecated Function. To be removed
  *
  *
  *
*/
function fetchActionReferenceEntityValues($parentrolename)
{
  global $adb;
  $sql = "select tabid,actionname,action_permission,description from role2action where rolename='" .$parentrolename ."'"; 
    $result=$adb->query($sql);
  return $result;
}

/** Function to get the roleid from rolename
  * @param $rolename -- Role Name:: Type varchar
  * @returns Role Id:: Type varchar
  *
 */
function fetchRoleId($rolename)
{

  global $adb;
  $sqlfetchroleid = "select roleid from role where rolename='".$rolename ."'";
  $resultroleid = $adb->query($sqlfetchroleid);
  $role_id = $adb->query_result($resultroleid,0,"roleid");
  return $role_id;
}

/** Function to update user to role mapping based on the userid
  * @param $roleid -- Role Id:: Type varchar
  * @param $userid User Id:: Type integer
  *
 */
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


/** Function to update user to group mapping based on the userid
  * @param $groupname -- Group Name:: Type varchar
  * @param $userid User Id:: Type integer
  *
 */
function updateUsers2GroupMapping($groupname,$userid)
{
  global $adb;
  $sqldelete = "delete from users2group where userid = '" .$userid ."'";
  $result_delete = $adb->query($sqldelete);
  $sql = "insert into users2group(groupname,userid) values('" .$groupname ."','" .$userid ."')";
  $result = $adb->query($sql);
}

/** Function to add user to role mapping 
  * @param $roleid -- Role Id:: Type varchar
  * @param $userid User Id:: Type integer
  *
 */
function insertUser2RoleMapping($roleid,$userid)
{

  global $adb;	
  $sql = "insert into user2role(userid,roleid) values('" .$userid ."','" .$roleid ."')";
 $adb->query($sql); 

}

/** Function to add user to group mapping 
  * @param $groupname -- Group Name:: Type varchar
  * @param $userid User Id:: Type integer
  *
 */
function insertUsers2GroupMapping($groupname,$userid)
{
  global $adb;
  $sql = "insert into users2group(groupname,userid) values('" .$groupname ."','" .$userid ."')";
  $adb->query($sql);
}

/** Function to get the word template resultset 
  * @param $module -- Module Name:: Type varchar
  * @returns Type:: resultset
  *
 */
function fetchWordTemplateList($module)
{
  global $adb;
  $sql_word = "select templateid, filename from wordtemplates where module ='".$module."'" ; 
  $result=$adb->query($sql_word);
  return $result;
}



/** Function to get the email template iformation 
  * @param $templateName -- Template Name:: Type varchar
  * @returns Type:: resultset
  *
 */
function fetchEmailTemplateInfo($templateName)
{
	global $adb;
        $sql= "select * from emailtemplates where templatename='" .$templateName ."'";
        $result = $adb->query($sql);
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
	return $replacedString;
}

/** Function to add lead group relation 
  * @param $leadid -- Lead Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */
function insert2LeadGroupRelation($leadid,$groupname)
{
global $adb;
  $sql = "insert into leadgrouprelation values (" .$leadid .",'".$groupname."')";
  $adb->query($sql);

}

/** Function to update lead group relation 
  * @param $leadid -- Lead Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */
function updateLeadGroupRelation($leadid,$groupname)
{
 global $adb;
  $sqldelete = "delete from leadgrouprelation where leadid=".$leadid;
  $adb->query($sqldelete);
  $sql = "insert into leadgrouprelation values (".$leadid .",'" .$groupname ."')";  
  $adb->query($sql);

}

/** Function to add Account group relation 
  * @param $accountid -- Account Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */
function insert2AccountGroupRelation($accountid,$groupname)
{
global $adb;
  $sql = "insert into accountgrouprelation values (" .$accountid .",'".$groupname."')";
  $adb->query($sql);

}

/** Function to update Account group relation
  * @param $accountid -- Account Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */

function updateAccountGroupRelation($accountid,$groupname)
{
 global $adb;
  $sqldelete = "delete from accountgrouprelation where accountid=".$accountid;
  $adb->query($sqldelete);
  $sql = "insert into accountgrouprelation values (".$accountid .",'" .$groupname ."')";
  $adb->query($sql);

}

/** Function to add Contact group relation
  * @param $contactid -- Contact Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */
function insert2ContactGroupRelation($contactid,$groupname)
{
global $adb;
  $sql = "insert into contactgrouprelation values (" .$contactid .",'".$groupname."')";
  $adb->query($sql);

}

/** Function to update contact group relation
  * @param $contactid -- Contact Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */

function updateContactGroupRelation($contactid,$groupname)
{
 global $adb;
  $sqldelete = "delete from contactgrouprelation where contactid=".$contactid;
  $adb->query($sqldelete);
  $sql = "insert into contactgrouprelation values (".$contactid .",'" .$groupname ."')";
  $adb->query($sql);

}
/** Function to add Potential group relation
  * @param $potentialid -- Potential Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */
function insert2PotentialGroupRelation($potentialid,$groupname)
{
global $adb;
  $sql = "insert into potentialgrouprelation values (" .$potentialid .",'".$groupname."')";
  $adb->query($sql);

}

/** Function to update Potential group relation
  * @param $potentialid -- Potential Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */

function updatePotentialGroupRelation($potentialid,$groupname)
{
 global $adb;
  $sqldelete = "delete from potentialgrouprelation where potentialid=".$potentialid;
  $adb->query($sqldelete);
  $sql = "insert into potentialgrouprelation values (".$potentialid .",'" .$groupname ."')";
  $adb->query($sql);

}

/** Function to add Quote group relation
  * @param $quoteid -- Quote Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */
function insert2QuoteGroupRelation($quoteid,$groupname)
{
global $adb;
  $sql = "insert into quotegrouprelation values (" .$quoteid .",'".$groupname."')";
  $adb->query($sql);

}

/** Function to update Quote group relation
  * @param $quoteid -- Quote Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */

function updateQuoteGroupRelation($quoteid,$groupname)
{
 global $adb;
  $sqldelete = "delete from quotegrouprelation where quoteid=".$quoteid;
  $adb->query($sqldelete);
  $sql = "insert into quotegrouprelation values (".$quoteid .",'" .$groupname ."')";
  $adb->query($sql);

}
/** Function to add Salesorder group relation
  * @param $salesorderid -- Salesorder Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */
function insert2SoGroupRelation($salesorderid,$groupname)
{
global $adb;
  $sql = "insert into sogrouprelation values (" .$salesorderid .",'".$groupname."')";
  $adb->query($sql);

}

/** Function to update Salesorder group relation
  * @param $salesorderid -- Salesorder Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */

function updateSoGroupRelation($salesorderid,$groupname)
{
 global $adb;
  $sqldelete = "delete from sogrouprelation where salesorderid=".$salesorderid;
  $adb->query($sqldelete);
  $sql = "insert into sogrouprelation values (".$salesorderid .",'" .$groupname ."')";
  $adb->query($sql);

}

/** Function to add Invoice group relation
  * @param $invoiceid -- Invoice Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */
function insert2InvoiceGroupRelation($invoiceid,$groupname)
{
global $adb;
  $sql = "insert into invoicegrouprelation values (" .$invoiceid .",'".$groupname."')";
  $adb->query($sql);

}

/** Function to update Invoice group relation
  * @param $invoiceid -- Invoice Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */

function updateInvoiceGroupRelation($invoiceid,$groupname)
{
 global $adb;
  $sqldelete = "delete from invoicegrouprelation where invoiceid=".$invoiceid;
  $adb->query($sqldelete);
  $sql = "insert into invoicegrouprelation values (".$invoiceid .",'" .$groupname ."')";
  $adb->query($sql);

}

/** Function to add PurchaseOrder group relation
  * @param $poid -- PurchaseOrder Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */
function insert2PoGroupRelation($poid,$groupname)
{
global $adb;
  $sql = "insert into pogrouprelation values (" .$poid .",'".$groupname."')";
  $adb->query($sql);

}

/** Function to update PurchaseOrder group relation
  * @param $poid -- Purchaseorder Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */

function updatePoGroupRelation($poid,$groupname)
{
 global $adb;
  $sqldelete = "delete from pogrouprelation where purchaseorderid=".$poid;
  $adb->query($sqldelete);
  $sql = "insert into pogrouprelation values (".$poid .",'" .$groupname ."')";
  $adb->query($sql);

}


/** Function to update ticket group relation 
  * @param $ticketid -- Ticket Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */
function updateTicketGroupRelation($ticketid,$groupname)
{
 global $adb;
  $sqldelete = "delete from ticketgrouprelation where ticketid=".$ticketid;
  $adb->query($sqldelete);
  $sql = "insert into ticketgrouprelation values (".$ticketid .",'" .$groupname ."')";  
  $adb->query($sql);

}


/** Function to insert activity group relation 
  * @param $activityid -- Activity Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */
function insert2ActivityGroupRelation($activityid,$groupname)
{
global $adb;
  $sql = "insert into activitygrouprelation values (" .$activityid .",'".$groupname."')";
  $adb->query($sql);

}


/** Function to insert ticket group relation 
  * @param $ticketid -- Ticket Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */
function insert2TicketGroupRelation($ticketid,$groupname)
{
global $adb;
  $sql = "insert into ticketgrouprelation values (" .$ticketid .",'".$groupname."')";
  $adb->query($sql);

}

/** Function to update activity group relation 
  * @param $activityid -- Activity Id:: Type integer
  * @param $groupname -- Group Name:: Type varchar
  *
 */
function updateActivityGroupRelation($activityid,$groupname)
{
	global $adb;
  $sqldelete = "delete from activitygrouprelation where activityid=".$activityid;
  $adb->query($sqldelete);
  $sql = "insert into activitygrouprelation values (".$activityid .",'" .$groupname ."')";  
  $adb->query($sql);

}



/** Function to be depricated
 *
 *
 *
*/
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


/** Function to be depricated
 *
 *
 *
*/
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


/** Function to be depricated
 *
 *
 *
*/
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


/** Function to get the role name from the roleid 
  * @param $roleid -- Role Id:: Type varchar
  * @returns $rolename -- Role Name:: Type varchar
  *
 */
function getRoleName($roleid)
{
	global $adb;
	$sql1 = "select * from role where roleid='".$roleid."'";
	$result = $adb->query($sql1);
	$rolename = $adb->query_result($result,0,"rolename");
	return $rolename;	
}

/** Function to get the profile name from the profileid 
  * @param $profileid -- Profile Id:: Type integer
  * @returns $rolename -- Role Name:: Type varchar
  *
 */
function getProfileName($profileid)
{
	global $adb;
	$sql1 = "select * from profile where profileid=".$profileid;
	$result = $adb->query($sql1);
	$profilename = $adb->query_result($result,0,"profilename");
	return $profilename;	
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

	global $adb;
	global $current_user;
	global $seclog;
	require('user_privileges/user_privileges_'.$current_user->id.'.php');
	require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
	$permission = "no";
	if($module == 'Users' || $module == 'Home' || $module == 'Administration' || $module == 'uploads' ||  $module == 'Settings' || $module == 'Calendar')
	{
		//These modules dont have security right now
		$permission = "yes";
		return $permission;

	}
	//Checking whether the user is admin
	if($is_admin)
	{
		$permission ="yes";
		return $permission;
	}
	//Retreiving the Tabid and Action Id	
	$tabid = getTabid($module);
	$actionid=getActionid($actionname);
	//If no actionid, then allow action is tab permission is available	
	if($actionid == '')
	{
		if($profileTabsPermission[$tabid] ==0)
        	{	
                	$permission = "yes";
                	return $permission;
        	}
		else
		{
			$permission ="no";
		}
		
	}	

	$action = getActionname($actionid);
	//Checking for view all permission
	if($profileGlobalPermission[1] ==0 || $profileGlobalPermission[2] ==0)
	{	
		if($actionid == 3 || $actionid == 4)
		{
			$permission = "yes";
			return $permission;

		}
	}
	//Checking for edit all permission
	if($profileGlobalPermission[2] ==0)
	{	
		if($actionid == 3 || $actionid == 4 || $actionid ==0 || $actionid ==1)
		{
			$permission = "yes";
			return $permission;

		}
	}
	//Checking for tab permission
	if($profileTabsPermission[$tabid] !=0)
	{
		$permission = "no";
		return $permission;
	}
	//Checking for Action Permission
	if($profileActionPermission[$tabid][$actionid] != 0)
	{
		$permission = "no";
		return $permission;
	}
	//Checking and returning true if recorid is null
	if($record_id == '')
	{
		$permission = "yes";
		return $permission;
	}

	//If modules is Notes,Products,Vendors,Faq,PriceBook then no sharing			
	if($record_id != '')
	{
		if($module == 'Notes' || $module == 'Products' || $module == 'Faq' || $module == 'Vendor'  || $module == 'PriceBook')
		{
			$permission = "yes";
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
			return $permission;
		}
		//Checking if the Record Owner is the Subordinate User
		foreach($subordinate_roles_users as $roleid=>$userids)
		{
			if(in_array($recOwnId,$userids))
			{
				$permission='yes';
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
			return $permission;
		}	 
	}	

	//Checking for Default Org Sharing permission
	if($others_permission_id == 0)
	{
		if($actionid == 1 || $actionid == 0)
		{

			$permission = isReadWritePermittedBySharing($module,$tabid,$actionid,$record_id);
			return $permission;	
		}
		elseif($actionid == 2)
		{
			$permission = "no";
			return $permission;
		}
		else
		{
			$permission = "yes";
			return $permission;
		}
	}
	elseif($others_permission_id == 1)
	{
		if($actionid == 2)
		{
			$permission = "no";
			return $permission;
		}
		else
		{
			$permission = "yes";
			return $permission;
		}
	}
	elseif($others_permission_id == 2)
	{

		$permission = "yes";
		return $permission;
	}
	elseif($others_permission_id == 3)
	{
		
		if($actionid == 3 || $actionid == 4)
		{
			$permission = isReadPermittedBySharing($module,$tabid,$actionid,$record_id);
			return $permission;	
		}
		elseif($actionid ==0 || $actionid ==1)
		{
			$permission = isReadWritePermittedBySharing($module,$tabid,$actionid,$record_id);
			return $permission;	
		}
	}
	else
	{
		$permission = "yes";	
	}			

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
	global $adb;
	global $current_user;
	require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
	$recordOwnerArr=getRecordOwnerId($record_id);
	$ownertype='';
	$ownerid='';
	$sharePer='no';
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
						return $sharePer;
					}

				}	
			}		
		}
	}
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
	global $adb;
	global $current_user;	
	require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
	$recordOwnerArr=getRecordOwnerId($record_id);
	$ownertype='';
	$ownerid='';
	$sharePer='no';
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
						return $sharePer;
					}

				}	
			}		
		}
	}
	
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

		//Checking whether this tab is allowed
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
	return $permission;

}


/** Function to be depricated
  *
  *
 */
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



/** Function to get the Profile Global Information for the specified profileid  
  * @param $profileid -- Profile Id:: Type integer
  * @returns Profile Gloabal Permission Array in the following format:
  * $profileGloblaPermisson=Array($viewall_actionid=>permission, $editall_actionid=>permission)
 */
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

/** Function to get the Profile Tab Permissions for the specified profileid  
  * @param $profileid -- Profile Id:: Type integer
  * @returns Profile Tabs Permission Array in the following format:
  * $profileTabPermisson=Array($tabid1=>permission, $tabid2=>permission,........., $tabidn=>permission)
 */
function getProfileTabsPermission($profileid)
{
  global $adb;
  $sql = "select * from profile2tab where profileid=".$profileid ;
  $result = $adb->query($sql);
  $num_rows = $adb->num_rows($result);

  for($i=0; $i<$num_rows; $i++)
  {
	$tab_id = $adb->query_result($result,$i,"tabid");
	$per_id = $adb->query_result($result,$i,"permissions");
	$copy[$tab_id] = $per_id;
  }	 

   return $copy;
  
}


/** Function to get the Profile Action Permissions for the specified profileid  
  * @param $profileid -- Profile Id:: Type integer
  * @returns Profile Tabs Action Permission Array in the following format:
  *    $tabActionPermission = Array($tabid1=>Array(actionid1=>permission, actionid2=>permission,...,actionidn=>permission),
  *                        $tabid2=>Array(actionid1=>permission, actionid2=>permission,...,actionidn=>permission),
  *                                |
  *                        $tabidn=>Array(actionid1=>permission, actionid2=>permission,...,actionidn=>permission))
 */
function getProfileActionPermission($profileid)
{
	global $adb;
	$check = Array();
	$temp_tabid = Array();	
	$sql1 = "select * from profile2standardpermissions where profileid=".$profileid;
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



/** Function to get the Standard and Utility Profile Action Permissions for the specified profileid  
  * @param $profileid -- Profile Id:: Type integer
  * @returns Profile Tabs Action Permission Array in the following format:
  *    $tabActionPermission = Array($tabid1=>Array(actionid1=>permission, actionid2=>permission,...,actionidn=>permission),
  *                        $tabid2=>Array(actionid1=>permission, actionid2=>permission,...,actionidn=>permission),
  *                                |
  *                        $tabidn=>Array(actionid1=>permission, actionid2=>permission,...,actionidn=>permission))
 */
function getProfileAllActionPermission($profileid)
{
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
	return $actionArr;
}


/** Function to create profile 
  * @param $profilename -- Profile Name:: Type varchar
  * @param $parentProfileId -- Profile Id:: Type integer
 */
function createProfile($profilename,$parentProfileId,$description)
{
	global $adb;
	//Inserting values into Profile Table
	$sql1 = "insert into profile values('','".$profilename."','".$description."')";
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

/** Function to delete profile 
  * @param $transfer_profileid -- Profile Id to which the existing role2profile relationships are to be transferred :: Type varchar
  * @param $prof_id -- Profile Id to be deleted:: Type integer
 */
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

                $sql8 = "select roleid from role2profile where profileid=".$prof_id;
                $result=$adb->query($sql8);
                $num_rows=$adb->num_rows($result);

                for($i=0;$i<$num_rows;$i++)
                {
                        $roleid=$adb->query_result($result,$i,'roleid');
                        $sql = "select profileid from role2profile where roleid='".$roleid."'";
                        $profresult=$adb->query($sql);
                        $num=$adb->num_rows($profresult);
                        if($num>1)
                        {
                                $sql10="delete from role2profile where roleid='".$roleid."' and profileid=".$prof_id;
                                $adb->query($sql10);
                        }
                        else
                        {
                                $sql8 = "update role2profile set profileid=".$transfer_profileid." where profileid=".$prof_id." and roleid='".$roleid."'";
                                $adb->query($sql8);
                        }


                }
        }

	//delete from profile table;
	$sql9 = "delete from profile where profileid=".$prof_id;
	$adb->query($sql9);	

}

/** Function to get all  the role information 
  * @returns $allRoleDetailArray-- Array will contain the details of all the roles. RoleId will be the key:: Type array
 */
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


/** Function to get all  the profile information 
  * @returns $allProfileInfoArray-- Array will contain the details of all the profiles. Profile ID will be the key:: Type array
 */
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

/** Function to get the role information of the specified role
  * @param $roleid -- RoleId :: Type varchar 
  * @returns $roleInfoArray-- RoleInfoArray in the following format:
  *       $roleInfo=Array($roleId=>Array($rolename,$parentrole,$roledepth,$immediateParent));
 */
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


/** Function to get the role related profiles
  * @param $roleid -- RoleId :: Type varchar 
  * @returns $roleProfiles-- Role Related Profile Array in the following format:
  *       $roleProfiles=Array($profileId1=>$profileName,$profileId2=>$profileName,........,$profileIdn=>$profileName));
 */
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


/** Function to get the role related users
  * @param $roleid -- RoleId :: Type varchar 
  * @returns $roleUsers-- Role Related User Array in the following format:
  *       $roleUsers=Array($userId1=>$userName,$userId2=>$userName,........,$userIdn=>$userName));
 */
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


/** Function to get the role related user ids
  * @param $roleid -- RoleId :: Type varchar 
  * @returns $roleUserIds-- Role Related User Array in the following format:
  *       $roleUserIds=Array($userId1,$userId2,........,$userIdn);
 */

function getRoleUserIds($roleId)
{
	global $adb;
	$query = "select user2role.*,users.user_name from user2role inner join users on users.id=user2role.userid where roleid='".$roleId."'";
	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	$roleRelatedUsers=Array();
	for($i=0; $i<$num_rows; $i++)
	{
		$roleRelatedUsers[]=$adb->query_result($result,$i,'userid');
	}
	return $roleRelatedUsers;
	

}

/** Function to get the role and subordinate users
  * @param $roleid -- RoleId :: Type varchar 
  * @returns $roleSubUsers-- Role and Subordinates Related Users Array in the following format:
  *       $roleSubUsers=Array($userId1=>$userName,$userId2=>$userName,........,$userIdn=>$userName));
 */
function getRoleAndSubordinateUsers($roleId)
{
	global $adb;
	$roleInfoArr=getRoleInformation($roleId);
	$parentRole=$roleInfoArr[$roleId][1];
	$query = "select user2role.*,users.user_name from user2role inner join users on users.id=user2role.userid inner join role on role.roleid=user2role.roleid where role.parentrole like '".$parentRole."%'";
	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	$roleRelatedUsers=Array();
	for($i=0; $i<$num_rows; $i++)
	{
		$roleRelatedUsers[$adb->query_result($result,$i,'userid')]=$adb->query_result($result,$i,'user_name');
	}
	return $roleRelatedUsers;
	

}


/** Function to get the role and subordinate user ids
  * @param $roleid -- RoleId :: Type varchar 
  * @returns $roleSubUserIds-- Role and Subordinates Related Users Array in the following format:
  *       $roleSubUserIds=Array($userId1,$userId2,........,$userIdn);
 */
function getRoleAndSubordinateUserIds($roleId)
{
	global $adb;
	$roleInfoArr=getRoleInformation($roleId);
	$parentRole=$roleInfoArr[$roleId][1];
	$query = "select user2role.*,users.user_name from user2role inner join users on users.id=user2role.userid inner join role on role.roleid=user2role.roleid where role.parentrole like '".$parentRole."%'";
	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	$roleRelatedUsers=Array();
	for($i=0; $i<$num_rows; $i++)
	{
		$roleRelatedUsers[]=$adb->query_result($result,$i,'userid');
	}
	return $roleRelatedUsers;
	

}

/** Function to get the role and subordinate Information for the specified roleId
  * @param $roleid -- RoleId :: Type varchar 
  * @returns $roleSubInfo-- Role and Subordinates Information array in the following format:
  *       $roleSubInfo=Array($roleId1=>Array($rolename,$parentrole,$roledepth,$immediateParent), $roleId2=>Array($rolename,$parentrole,$roledepth,$immediateParent),.....);
 */
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


/** Function to get the role and subordinate role ids
  * @param $roleid -- RoleId :: Type varchar 
  * @returns $roleSubRoleIds-- Role and Subordinates RoleIds in an Array in the following format:
  *       $roleSubRoleIds=Array($roleId1,$roleId2,........,$roleIdn);
 */
function getRoleAndSubordinatesRoleIds($roleId)
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
		$roleInfo[]=$roleid;
		
	}
	return $roleInfo;	

}

/** Function to get delete the spcified role 
  * @param $roleid -- RoleId :: Type varchar 
  * @param $transferRoleId -- RoleId to which users of the role that is being deleted are transferred:: Type varchar 
 */
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

                //delete handling for groups
                $sql10 = "delete from group2role where roleid='".$roleid."'";
                $adb->query($sql10);

                $sql11 = "delete from group2rs where roleandsubid='".$roleid."'";
                $adb->query($sql11);


                //delete handling for sharing rules
                deleteRoleRelatedSharingRules($roleid);

                //delete from role table;
                $sql9 = "delete from role where roleid='".$roleid."'";
                $adb->query($sql9);
                //echo $sql1.'            '.$sql2.'           '.$sql9;



        }

}

/** Function to delete the role related sharing rules
  * @param $roleid -- RoleId :: Type varchar
 */
function deleteRoleRelatedSharingRules($roleId)
{
        global $adb;
        $dataShareTableColArr=Array('datashare_grp2role'=>'to_roleid',
                                    'datashare_grp2rs'=>'to_roleandsubid',
                                    'datashare_role2group'=>'share_roleid',
                                    'datashare_role2role'=>'share_roleid::to_roleid',
                                    'datashare_role2rs'=>'share_roleid::to_roleandsubid',
                                    'datashare_rs2grp'=>'share_roleandsubid',
                                    'datashare_rs2role'=>'share_roleandsubid::to_roleid',
                                    'datashare_rs2rs'=>'share_roleandsubid::to_roleandsubid');

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
}

/** Function to delete the group related sharing rules
  * @param $roleid -- RoleId :: Type varchar
 */
function deleteGroupRelatedSharingRules($grpId)
{

        global $adb;
        $dataShareTableColArr=Array('datashare_grp2grp'=>'share_groupid::to_groupid',
                                    'datashare_grp2role'=>'share_groupid',
                                    'datashare_grp2rs'=>'share_groupid',
                                    'datashare_role2group'=>'to_groupid',
                                    'datashare_rs2grp'=>'to_groupid');


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
}


/** Function to get userid and username of all users 
  * @returns $userArray -- User Array in the following format:
  * $userArray=Array($userid1=>$username, $userid2=>$username,............,$useridn=>$username); 
 */
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


/** Function to get groupid and groupname of all groups 
  * @returns $grpArray -- Group Array in the following format:
  * $grpArray=Array($grpid1=>$grpname, $grpid2=>$grpname,............,$grpidn=>$grpname); 
 */
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

/** Function to get group information of all groups 
  * @returns $grpInfoArray -- Group Informaton array in the following format: 
  * $grpInfoArray=Array($grpid1=>Array($grpname,description) $grpid2=>Array($grpname,description),............,$grpidn=>Array($grpname,description)); 
 */
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

/** Function to create a group 
  * @param $groupName -- Group Name :: Type varchar 
  * @param $groupMemberArray -- Group Members (Groups,Roles,RolesAndsubordinates,Users) 
  * @param $groupName -- Group Name :: Type varchar 
  * @returns $groupId -- Group Id :: Type integer 
 */
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


/** Function to insert group to group relation 
  * @param $groupId -- Group Id :: Type integer 
  * @param $containsGroupId -- Group Id :: Type integer 
 */
function insertGroupToGroupRelation($groupId,$containsGroupId)
{
	global $adb;
	$query="insert into group2grouprel values(".$groupId.",".$containsGroupId.")";
	$adb->query($query);
}


/** Function to insert group to role relation 
  * @param $groupId -- Group Id :: Type integer 
  * @param $roleId -- Role Id :: Type varchar 
 */
function insertGroupToRoleRelation($groupId,$roleId)
{
	global $adb;
	$query="insert into group2role values(".$groupId.",'".$roleId."')";
	$adb->query($query);
}


/** Function to insert group to role&subordinate relation 
  * @param $groupId -- Group Id :: Type integer 
  * @param $rsId -- Role Sub Id :: Type varchar 
 */
function insertGroupToRsRelation($groupId,$rsId)
{
	global $adb;
	$query="insert into group2rs values(".$groupId.",'".$rsId."')";
	$adb->query($query);
}

/** Function to insert group to user relation 
  * @param $groupId -- Group Id :: Type integer 
  * @param $userId -- User Id :: Type varchar 
 */
function insertGroupToUserRelation($groupId,$userId)
{
	global $adb;
	$query="insert into users2group values(".$groupId.",".$userId.")";
	$adb->query($query);
}


/** Function to get the group Information of the specified group 
  * @param $groupId -- Group Id :: Type integer 
  * @returns Group Detail Array in the following format:
  *   $groupDetailArray=Array($groupName,$description,$groupMembers);
 */
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

/** Function to fetch the group name of the specified group 
  * @param $groupId -- Group Id :: Type integer 
  * @returns Group Name :: Type varchar
 */
function fetchGroupName($groupId)
{

	global $adb;
	//Retreving the group Info
	$query="select * from groups where groupid=".$groupId;
	$result = $adb->query($query);
	$groupName=$adb->query_result($result,0,'groupname');
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

/** Function to get the group related roles of the specified group 
  * @param $groupId -- Group Id :: Type integer 
  * @returns Group Related Role Array in the follwing format:
  *  $groupRoles=Array(roleid1,roleid2,roleid3,.....,roleidn);
 */
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


/** Function to get the group related roles and subordinates of the specified group 
  * @param $groupId -- Group Id :: Type integer 
  * @returns Group Related Roles & Subordinate Array in the follwing format:
  *  $groupRoleSubordinates=Array(roleid1,roleid2,roleid3,.....,roleidn);
 */
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


/** Function to get the group related groups  
  * @param $groupId -- Group Id :: Type integer 
  * @returns Group Related Groups Array in the follwing format:
  *  $groupGroups=Array(grpid1,grpid2,grpid3,.....,grpidn);
 */
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

/** Function to get the group related users  
  * @param $userId -- User Id :: Type integer 
  * @returns Group Related Users Array in the follwing format:
  *  $groupUsers=Array(userid1,userid2,userid3,.....,useridn);
 */
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

/** Function to update the group  
  * @param $groupId -- Group Id :: Type integer 
  * @param $groupName -- Group Name :: Type varchar 
  * @param $groupMemberArray -- Group Members Array :: Type array 
  * @param $description -- Description :: Type text
 */
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

/** Function to delete the specified group  
  * @param $groupId -- Group Id :: Type integer 
 */
function deleteGroup($groupId)
{
	global $adb;
	deleteGroupRelatedSharingRules($groupId);		
	$query="delete from groups where groupid=".$groupId;
	$adb->query($query);

	deleteGroupRelatedGroups($groupId);
	deleteGroupRelatedRoles($groupId);
	deleteGroupRelatedRolesAndSubordinates($groupId);
	deleteGroupRelatedUsers($groupId);

}

/** Function to delete group to group relation of the  specified group  
  * @param $groupId -- Group Id :: Type integer 
 */
function deleteGroupRelatedGroups($groupId)
{
	global $adb;
	$query="delete from group2grouprel where groupid=".$groupId;
	$adb->query($query);
}


/** Function to delete group to role relation of the  specified group  
  * @param $groupId -- Group Id :: Type integer 
 */
function deleteGroupRelatedRoles($groupId)
{
	global $adb;
	$query="delete from group2role where groupid=".$groupId;
	$adb->query($query);
}


/** Function to delete group to role and subordinates relation of the  specified group  
  * @param $groupId -- Group Id :: Type integer 
 */
function deleteGroupRelatedRolesAndSubordinates($groupId)
{
	global $adb;
	$query="delete from group2rs where groupid=".$groupId;
	$adb->query($query);
}


/** Function to delete group to user relation of the  specified group  
  * @param $groupId -- Group Id :: Type integer 
 */
function deleteGroupRelatedUsers($groupId)
{
	global $adb;
	$query="delete from users2group where groupid=".$groupId;
	$adb->query($query);
}

/** This function returns the Default Organisation Sharing Action Name
  * @param $share_action_id -- It takes the Default Organisation Sharing ActionId as input :: Type Integer
  * @returns The sharing Action Name :: Type Varchar
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

/** This function adds a organisation level sharing rule for the specified Module
  * It takes the following input parameters:
  * 	$tabid -- Module tabid - Datatype::Integer
  * 	$shareEntityType -- The Entity Type may be groups,roles,rs and users - Datatype::String
  * 	$toEntityType -- The Entity Type may be groups,roles,rs and users - Datatype::String
  * 	$shareEntityId -- The id of the group,role,rs,user to be shared 
  * 	$toEntityId -- The id of the group,role,rs,user to which the specified entity is to be shared
  * 	$sharePermisson -- This can have the following values:
  *                       0 - Read Only
  *                       1 - Read/Write
  * This function will return the shareid as output
  */
function addSharingRule($tabid,$shareEntityType,$toEntityType,$shareEntityId,$toEntityId,$sharePermission)
{
	
	global $adb;
	$shareid=$adb->getUniqueId("datashare_module_rel");
	

	if($shareEntityType == 'groups' && $toEntityType == 'groups')
	{
		$type_string='GRP::GRP';
		$query = "insert into datashare_grp2grp values(".$shareid.", ".$shareEntityId.", ".$toEntityId.", ".$sharePermission.")";
	}
	elseif($shareEntityType == 'groups' && $toEntityType == 'roles')
	{
		
		$type_string='GRP::ROLE';
		$query = "insert into datashare_grp2role values(".$shareid.", ".$shareEntityId.", '".$toEntityId."', ".$sharePermission.")";
	}
	elseif($shareEntityType == 'groups' && $toEntityType == 'rs')
	{
		
		$type_string='GRP::RS';
		$query = "insert into datashare_grp2rs values(".$shareid.", ".$shareEntityId.", '".$toEntityId."', ".$sharePermission.")";
	}
	elseif($shareEntityType == 'roles' && $toEntityType == 'groups')
	{
		
		$type_string='ROLE::GRP';
		$query = "insert into datashare_role2group values(".$shareid.", '".$shareEntityId."', ".$toEntityId.", ".$sharePermission.")";
	}
	elseif($shareEntityType == 'roles' && $toEntityType == 'roles')
	{
		
		$type_string='ROLE::ROLE';
		$query = "insert into datashare_role2role values(".$shareid.", '".$shareEntityId."', '".$toEntityId."', ".$sharePermission.")";
	}
	elseif($shareEntityType == 'roles' && $toEntityType == 'rs')
	{
		
		$type_string='ROLE::RS';
		$query = "insert into datashare_role2rs values(".$shareid.", '".$shareEntityId."', '".$toEntityId."', ".$sharePermission.")";
	}
	elseif($shareEntityType == 'rs' && $toEntityType == 'groups')
	{
		
		$type_string='RS::GRP';
		$query = "insert into datashare_rs2grp values(".$shareid.", '".$shareEntityId."', ".$toEntityId.", ".$sharePermission.")";
	}
	elseif($shareEntityType == 'rs' && $toEntityType == 'roles')
	{
		
		$type_string='RS::ROLE';
		$query = "insert into datashare_rs2role values(".$shareid.", '".$shareEntityId."', '".$toEntityId."', ".$sharePermission.")";
	}
	elseif($shareEntityType == 'rs' && $toEntityType == 'rs')
	{
		
		$type_string='RS::RS';
		$query = "insert into datashare_rs2rs values(".$shareid.", '".$shareEntityId."', '".$toEntityId."', ".$sharePermission.")";
	}
	$query1 = "insert into datashare_module_rel values(".$shareid.",".$tabid.",'".$type_string."')";
	$adb->query($query1);		
	$adb->query($query);	
	return $shareid;	
	
}


/** This function is to update the organisation level sharing rule 
  * It takes the following input parameters:
  *     $shareid -- Id of the Sharing Rule to be updated
  * 	$tabid -- Module tabid - Datatype::Integer
  * 	$shareEntityType -- The Entity Type may be groups,roles,rs and users - Datatype::String
  * 	$toEntityType -- The Entity Type may be groups,roles,rs and users - Datatype::String
  * 	$shareEntityId -- The id of the group,role,rs,user to be shared 
  * 	$toEntityId -- The id of the group,role,rs,user to which the specified entity is to be shared
  * 	$sharePermisson -- This can have the following values:
  *                       0 - Read Only
  *                       1 - Read/Write
  * This function will return the shareid as output
  */
function updateSharingRule($shareid,$tabid,$shareEntityType,$toEntityType,$shareEntityId,$toEntityId,$sharePermission)
{
	
	global $adb;
	$query2="select * from datashare_module_rel where shareid=".$shareid;
	$res=$adb->query($query2);
	$typestr=$adb->query_result($res,0,'relationtype');
	$tabname=getDSTableNameForType($typestr);
	$query3="delete from ".$tabname." where shareid=".$shareid;
	$adb->query($query3);
		

	if($shareEntityType == 'groups' && $toEntityType == 'groups')
	{
		$type_string='GRP::GRP';
		$query = "insert into datashare_grp2grp values(".$shareid.", ".$shareEntityId.", ".$toEntityId.", ".$sharePermission.")";
	}
	elseif($shareEntityType == 'groups' && $toEntityType == 'roles')
	{
		
		$type_string='GRP::ROLE';
		$query = "insert into datashare_grp2role values(".$shareid.", ".$shareEntityId.", '".$toEntityId."', ".$sharePermission.")";
	}
	elseif($shareEntityType == 'groups' && $toEntityType == 'rs')
	{
		
		$type_string='GRP::RS';
		$query = "insert into datashare_grp2rs values(".$shareid.", ".$shareEntityId.", '".$toEntityId."', ".$sharePermission.")";
	}
	elseif($shareEntityType == 'roles' && $toEntityType == 'groups')
	{
		
		$type_string='ROLE::GRP';
		$query = "insert into datashare_role2group values(".$shareid.", '".$shareEntityId."', ".$toEntityId.", ".$sharePermission.")";
	}
	elseif($shareEntityType == 'roles' && $toEntityType == 'roles')
	{
		
		$type_string='ROLE::ROLE';
		$query = "insert into datashare_role2role values(".$shareid.", '".$shareEntityId."', '".$toEntityId."', ".$sharePermission.")";
	}
	elseif($shareEntityType == 'roles' && $toEntityType == 'rs')
	{
		
		$type_string='ROLE::RS';
		$query = "insert into datashare_role2rs values(".$shareid.", '".$shareEntityId."', '".$toEntityId."', ".$sharePermission.")";
	}
	elseif($shareEntityType == 'rs' && $toEntityType == 'groups')
	{
		
		$type_string='RS::GRP';
		$query = "insert into datashare_rs2grp values(".$shareid.", '".$shareEntityId."', ".$toEntityId.", ".$sharePermission.")";
	}
	elseif($shareEntityType == 'rs' && $toEntityType == 'roles')
	{
		
		$type_string='RS::ROLE';
		$query = "insert into datashare_rs2role values(".$shareid.", '".$shareEntityId."', '".$toEntityId."', ".$sharePermission.")";
	}
	elseif($shareEntityType == 'rs' && $toEntityType == 'rs')
	{
		
		$type_string='RS::RS';
		$query = "insert into datashare_rs2rs values(".$shareid.", '".$shareEntityId."', '".$toEntityId."', ".$sharePermission.")";
	}
	
	$query1 = "update datashare_module_rel set relationtype='".$type_string."' where shareid=".$shareid;
	$adb->query($query1);		
	$adb->query($query);	
	return $shareid;	
	
}


/** This function is to delete the organisation level sharing rule 
  * It takes the following input parameters:
  *     $shareid -- Id of the Sharing Rule to be updated
  */
function deleteSharingRule($shareid)
{
	global $adb;
	$query2="select * from datashare_module_rel where shareid=".$shareid;
	$res=$adb->query($query2);
	$typestr=$adb->query_result($res,0,'relationtype');
	$tabname=getDSTableNameForType($typestr);
	$query3="delete from ".$tabname." where shareid=".$shareid;
	$adb->query($query3);
	$query4="delete from datashare_module_rel where shareid=".$shareid;
	$adb->query($query4);

	//deleting the releated module sharing permission
	$query5="delete from datashare_relatedmodule_permission where shareid=".$shareid;
	$adb->query($query5);
	
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
	$dataShareTableColArr=Array('datashare_grp2grp'=>'share_groupid::to_groupid',
				    'datashare_grp2role'=>'share_groupid::to_roleid',
				    'datashare_grp2rs'=>'share_groupid::to_roleandsubid',
				    'datashare_role2group'=>'share_roleid::to_groupid',
				    'datashare_role2role'=>'share_roleid::to_roleid',
				    'datashare_role2rs'=>'share_roleid::to_roleandsubid',
				    'datashare_rs2grp'=>'share_roleandsubid::to_groupid',
				    'datashare_rs2role'=>'share_roleandsubid::to_roleid',
				    'datashare_rs2rs'=>'share_roleandsubid::to_roleandsubid');
	return $dataShareTableColArr;	
					
}



/** Function get the Data Share Column Names for the specified Table Name
 *  @param $tableName -- DataShare Table Name :: Type Varchar
 *  @returns Column Name -- Type Varchar
 *
 */ 
function getDSTableColumns($tableName)
{
	$dataShareTableColArr=getDataShareTableandColumnArray();
	
	$dsTableCols=$dataShareTableColArr[$tableName];
	$dsTableColsArr=explode('::',$dsTableCols);	
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
	$dataShareTableColArr=Array('GRP::GRP'=>'datashare_grp2grp',
				    'GRP::ROLE'=>'datashare_grp2role',
				    'GRP::RS'=>'datashare_grp2rs',
				    'ROLE::GRP'=>'datashare_role2group',
				    'ROLE::ROLE'=>'datashare_role2role',
				    'ROLE::RS'=>'datashare_role2rs',
				    'RS::GRP'=>'datashare_rs2grp',
				    'RS::ROLE'=>'datashare_rs2role',
				    'RS::RS'=>'datashare_rs2rs');
	return $dataShareTableColArr;	
					
}

/** Function to get the Data Share Table Name from the speciified type string
 *  @param $typeString -- Datashare Type Sting :: Type Varchar
 *  @returns Table Name -- Type Varchar
 *
 */
function getDSTableNameForType($typeString)
{
	$dataShareTableColArr=getDataShareTableName();
	$tableName=$dataShareTableColArr[$typeString];
	return $tableName;	
					
}

/** Function to get the Entity type from the specified DataShare Table Column Name
 *  @param $colname -- Datashare Table Column Name :: Type Varchar
 *  @returns The entity type. The entity type may be groups or roles or rs -- Type Varchar
 */
function getEntityTypeFromCol($colName)
{

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
	
	return $entity_type;

}

/** Function to get the Entity Display Link
 *  @param $entityid -- Entity Id 
 *  @params $entityType --  The entity type may be groups or roles or rs -- Type Varchar
 *  @returns the Entity Display link  
 */
function getEntityDisplayLink($entityType,$entityid)
{
	if($entityType == 'groups')
	{
		$groupNameArr = getGroupInfo($entityid); 
		$display_out = "<a href='index.php?module=Users&action=GroupDetailView&returnaction=OrgSharingDetailView&groupId=".$entityid."'>Group::". $groupNameArr[0]." </a>";			
	}
	elseif($entityType == 'roles')
	{
		$roleName=getRoleName($entityid);	
		$display_out = "<a href='index.php?module=Users&action=RoleDetailView&returnaction=OrgSharingDetailView&roleid=".$entityid."'>Role::".$roleName. "</a>";			
	}
	elseif($entityType == 'rs')
	{
		$roleName=getRoleName($entityid);	
		$display_out = "<a href='index.php?module=Users&action=RoleDetailView&returnaction=OrgSharingDetailView&roleid=".$entityid."'>RoleAndSubordinate::".$roleName. "</a>";			
	}
	return $display_out;
	
}


/** Function to get the Sharing rule Info
 *  @param $shareId -- Sharing Rule Id 
 *  @returns Sharing Rule Information Array in the following format:
 *    $shareRuleInfoArr=Array($shareId, $tabid, $type, $share_ent_type, $to_ent_type, $share_entity_id, $to_entity_id,$permission);
 */
function getSharingRuleInfo($shareId)
{
	global $adb;
	global $log;
	$shareRuleInfoArr=Array();
	$query="select * from datashare_module_rel where shareid=".$shareId;
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
		
	return $shareRuleInfoArr;	
		
	
	
}

/** This function is to retreive the list of related sharing modules for the specifed module 
  * It takes the following input parameters:
  *     $tabid -- The module tabid:: Type Integer
  */

function getRelatedSharingModules($tabid)
{
	global $adb;
	$relatedSharingModuleArray=Array();
	$query="select * from datashare_relatedmodules where tabid=".$tabid;
	$result=$adb->query($query);
	$num_rows=$adb->num_rows($result);
	for($i=0;$i<$num_rows;$i++)
	{
		$ds_relmod_id=$adb->query_result($result,$i,'datashare_relatedmodule_id');
		$rel_tabid=$adb->query_result($result,$i,'relatedto_tabid');
		$relatedSharingModuleArray[$rel_tabid]=$ds_relmod_id;
		
	}
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
	global $adb;
	$relatedModuleSharingId=getRelatedModuleSharingId($tabid,$relatedtabid);	
	$query="insert into datashare_relatedmodule_permission values(".$shareid.", ".$relatedModuleSharingId.", ".$sharePermission.")" ;
	$result=$adb->query($query);
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
	global $adb;
	$relatedModuleSharingId=getRelatedModuleSharingId($tabid,$relatedtabid);
	$query="update datashare_relatedmodule_permission set permission=".$sharePermission." where shareid=".$shareid." and datashare_relatedmodule_id=".$relatedModuleSharingId;		
	$result=$adb->query($query);
}

/** This function is to retreive the Related Module Sharing Id
  * It takes the following input parameters:
  *     $tabid -- The module tabid:: Type Integer
  *     $related_tabid -- The related module tabid:: Type Integer
  * This function returns the Related Module Sharing Id
  */

function getRelatedModuleSharingId($tabid,$related_tabid)
{
	global $adb;
	$query="select datashare_relatedmodule_id from datashare_relatedmodules where tabid=".$tabid." and relatedto_tabid=".$related_tabid ;
	$result=$adb->query($query);
	$relatedModuleSharingId=$adb->query_result($result,0,'datashare_relatedmodule_id');
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
	global $adb;
	$relatedSharingModulePermissionArray=Array();
	$query="select datashare_relatedmodules.*,datashare_relatedmodule_permission.permission from datashare_relatedmodules inner join datashare_relatedmodule_permission on datashare_relatedmodule_permission.datashare_relatedmodule_id=datashare_relatedmodules.datashare_relatedmodule_id where datashare_relatedmodule_permission.shareid=".$shareid;
	$result=$adb->query($query);
	$num_rows=$adb->num_rows($result);
	for($i=0;$i<$num_rows;$i++)
	{
		$relatedto_tabid=$adb->query_result($result,$i,'relatedto_tabid');
		$permission=$adb->query_result($result,$i,'permission');
		$relatedSharingModulePermissionArray[$relatedto_tabid]=$permission;
		
	
	}
	return $relatedSharingModulePermissionArray;	
	
}


/** This function is to retreive the profiles associated with the  the specified user 
  * It takes the following input parameters:
  *     $userid -- The User Id:: Type Integer
  *This function will return the profiles associated to the specified users in an Array in the following format:
  *     $userProfileArray=(profileid1,profileid2,profileid3,...,profileidn);
  */
function getUserProfile($userId)
{
	global $adb;
	$roleId=fetchUserRole($userId);
	$profArr=Array();	
	$sql1 = "select profileid from role2profile where roleid='" .$roleId."'";
        $result1 = $adb->query($sql1);
	$num_rows=$adb->num_rows($result1);
	for($i=0;$i<$num_rows;$i++)
	{
		
        	$profileid=  $adb->query_result($result1,$i,"profileid");
		$profArr[]=$profileid;
	}
        return $profArr;	
	
}

/** To retreive the global permission of the specifed user from the various profiles associated with the user  
  * @param $userid -- The User Id:: Type Integer
  * @returns  user global permission  array in the following format:
  *     $gloabalPerrArray=(view all action id=>permission,
			   edit all action id=>permission)							);
  */
function getCombinedUserGlobalPermissions($userId)
{
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
			
	return $userGlobalPerrArr;

}

/** To retreive the tab permissions of the specifed user from the various profiles associated with the user  
  * @param $userid -- The User Id:: Type Integer
  * @returns  user global permission  array in the following format:
  *     $tabPerrArray=(tabid1=>permission,
  *			   tabid2=>permission)							);
  */
function getCombinedUserTabsPermissions($userId)
{
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
	return $userTabPerrArr;

}

/** To retreive the tab acion permissions of the specifed user from the various profiles associated with the user  
  * @param $userid -- The User Id:: Type Integer
  * @returns  user global permission  array in the following format:
  *     $actionPerrArray=(tabid1=>permission,
  *			   tabid2=>permission);
 */
function getCombinedUserActionPermissions($userId)
{
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
	return $actionPerrArr;

}

/** To retreive the parent role of the specified role 
  * @param $roleid -- The Role Id:: Type varchar
  * @returns  parent role array in the following format:
  *     $parentRoleArray=(roleid1,roleid2,.......,roleidn);
 */
function getParentRole($roleId)
{
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
	return $parentRoleArr;
	
}

/** To retreive the subordinate roles of the specified parent role  
  * @param $roleid -- The Role Id:: Type varchar
  * @returns  subordinate role array in the following format:
  *     $subordinateRoleArray=(roleid1,roleid2,.......,roleidn);
 */
function getRoleSubordinates($roleId)
{
	global $adb;
	$roleDetails=getRoleInformation($roleId);
	$roleInfo=$roleDetails[$roleId];
	$roleParentSeq=$roleInfo[1];
	
	$query="select * from role where parentrole like '".$roleParentSeq."::%' order by parentrole asc";
	$result=$adb->query($query);
	$num_rows=$adb->num_rows($result);
	$roleSubordinates=Array();
	for($i=0;$i<$num_rows;$i++)
	{
		$roleid=$adb->query_result($result,$i,'roleid');
                
		$roleSubordinates[]=$roleid;
		
	}
	return $roleSubordinates;	

}

/** To retreive the subordinate roles and users of the specified parent role  
  * @param $roleid -- The Role Id:: Type varchar
  * @returns  subordinate role array in the following format:
  *     $subordinateRoleUserArray=(roleid1=>Array(userid1,userid2,userid3),
                               roleid2=>Array(userid1,userid2,userid3)
				                |
						|
			       roleidn=>Array(userid1,userid2,userid3));
 */
function getSubordinateRoleAndUsers($roleId)
{
	global $adb;
	$subRoleAndUsers=Array();
	$subordinateRoles=getRoleSubordinates($roleId);
	foreach($subordinateRoles as $subRoleId)
	{
		$userArray=getRoleUsers($subRoleId);
		$subRoleAndUsers[$subRoleId]=$userArray;

	}
	return $subRoleAndUsers;	
		
}

function getCurrentUserProfileList()
{
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
        return $profList;

}


function getCurrentUserGroupList()
{
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
       	 return $grpList;
}

function getSubordinateUsersList()
{
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
       	return $subUserList;

}

function getReadSharingUsersList($module)
{
	global $adb;
	global $current_user;
	$user_array=Array();
	$tabid=getTabid($module);
	$query = "select shareduserid from tmp_read_user_sharing_per where userid=".$current_user->id." and tabid=".$tabid;
	$result=$adb->query($query);
	$num_rows=$adb->num_rows($result);
	for($i=0;$i<$num_rows;$i++)
	{
		$user_id=$adb->query_result($result,$i,'shareduserid');
		$user_array[]=$user_id;
	}
	$shareUserList=constructList($user_array,'INTEGER');
	return $shareUserList;
}

function getReadSharingGroupsList($module)
{
	global $adb;
	global $current_user;
	$grp_array=Array();
	$tabid=getTabid($module);
	$query = "select sharedgroupid from tmp_read_group_sharing_per where userid=".$current_user->id." and tabid=".$tabid;
	$result=$adb->query($query);
	$num_rows=$adb->num_rows($result);
	for($i=0;$i<$num_rows;$i++)
	{
		$grp_id=$adb->query_result($result,$i,'sharedgroupid');
		$grp_array[]=$grp_id;
	}
	$shareGrpList=constructList($grp_array,'INTEGER');
	return $shareGrpList;
}

function getWriteSharingGroupsList($module)
{
	global $adb;
	global $current_user;
	$grp_array=Array();
	$tabid=getTabid($module);
	$query = "select sharedgroupid from tmp_write_group_sharing_per where userid=".$current_user->id." and tabid=".$tabid;
	$result=$adb->query($query);
	$num_rows=$adb->num_rows($result);
	for($i=0;$i<$num_rows;$i++)
	{
		$grp_id=$adb->query_result($result,$i,'sharedgroupid');
		$grp_array[]=$grp_id;
	}
	$shareGrpList=constructList($grp_array,'INTEGER');
	return $shareGrpList;
}

function constructList($array,$data_type)
{
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
	return $list;	
}

function getListViewSecurityParameter($module)
{
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
		$sec_query .= "and (crmentity.smownerid in($current_user->id) or crmentity.smownerid in(select user2role.userid from user2role inner join users on users.id=user2role.userid inner join role on role.roleid=user2role.roleid where role.parentrole like '".$current_user_parent_role_seq."::%') or crmentity.smownerid in(select shareduserid from tmp_read_user_sharing_per where userid=".$current_user->id." and tabid=".$tabid.") or (crmentity.smownerid in (0) and (";

                        if(sizeof($current_user_groups) > 0)
                        {
                              $sec_query .= "groups.groupid in".getCurrentUserGroupList()." or ";
                        }
                         $sec_query .= "groups.groupid in(select tmp_read_group_sharing_per.sharedgroupid from tmp_read_group_sharing_per where userid=".$current_user->id." and tabid=".$tabid.")))) ";	
	}
	elseif($module == 'Accounts')
	{
		$sec_query .= "and (crmentity.smownerid in($current_user->id) or crmentity.smownerid in(select user2role.userid from user2role inner join users on users.id=user2role.userid inner join role on role.roleid=user2role.roleid where role.parentrole like '".$current_user_parent_role_seq."::%') or crmentity.smownerid in(select shareduserid from tmp_read_user_sharing_per where userid=".$current_user->id." and tabid=".$tabid.") or (crmentity.smownerid in (0) and (";

                if(sizeof($current_user_groups) > 0)
                {
                	$sec_query .= "groups.groupid in".getCurrentUserGroupList()." or ";
                }
		$sec_query .= "groups.groupid in(select tmp_read_group_sharing_per.sharedgroupid from tmp_read_group_sharing_per where userid=".$current_user->id." and tabid=".$tabid.")))) ";			
	
	}
	elseif($module == 'Contacts')
	{
		$sec_query .= "and (crmentity.smownerid in($current_user->id) or crmentity.smownerid in(select user2role.userid from user2role inner join users on users.id=user2role.userid inner join role on role.roleid=user2role.roleid where role.parentrole like '".$current_user_parent_role_seq."::%') or crmentity.smownerid in(select shareduserid from tmp_read_user_sharing_per where userid=".$current_user->id." and tabid=".$tabid.") or (crmentity.smownerid in (0) and (";

                if(sizeof($current_user_groups) > 0)
                {
                	$sec_query .= "groups.groupid in".getCurrentUserGroupList()." or ";
                }
		$sec_query .= "groups.groupid in(select tmp_read_group_sharing_per.sharedgroupid from tmp_read_group_sharing_per where userid=".$current_user->id." and tabid=".$tabid.")))) ";			
	
	}
	elseif($module == 'Potentials')
	{
		$sec_query .= "and (crmentity.smownerid in($current_user->id) or crmentity.smownerid in(select user2role.userid from user2role inner join users on users.id=user2role.userid inner join role on role.roleid=user2role.roleid where role.parentrole like '".$current_user_parent_role_seq."::%') or crmentity.smownerid in(select shareduserid from tmp_read_user_sharing_per where userid=".$current_user->id." and tabid=".$tabid.") or potential.accountid in (select crmid from crmentity where setype='accounts' and crmentity.smownerid in(select shareduserid from tmp_read_user_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('Accounts')." and relatedtabid=".$tabid.")) or potential.accountid in (select crmid from crmentity left join accountgrouprelation on accountgrouprelation.accountid=crmentity.crmid inner join groups on groups.groupname=accountgrouprelation.groupname where setype='Accounts' and crmentity.smownerid=0 and groups.groupid in(select tmp_read_group_rel_sharing_per.sharedgroupid from tmp_read_group_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('Accounts')." and relatedtabid=".$tabid.")) or (crmentity.smownerid in (0) and (";

                if(sizeof($current_user_groups) > 0)
                {
                	$sec_query .= "groups.groupid in".getCurrentUserGroupList()." or ";
                }
		$sec_query .= "groups.groupid in(select tmp_read_group_sharing_per.sharedgroupid from tmp_read_group_sharing_per where userid=".$current_user->id." and tabid=".$tabid.")))) ";			
	
	}
	elseif($module == 'HelpDesk')
	{
		$sec_query .= "and (crmentity.smownerid in($current_user->id) or crmentity.smownerid in(select user2role.userid from user2role inner join users on users.id=user2role.userid inner join role on role.roleid=user2role.roleid where role.parentrole like '".$current_user_parent_role_seq."::%') or crmentity.smownerid in(select shareduserid from tmp_read_user_sharing_per where userid=".$current_user->id." and tabid=".$tabid.") or troubletickets.parent_id in (select crmid from crmentity where setype='Accounts' and crmentity.smownerid in(select shareduserid from tmp_read_user_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('Accounts')." and relatedtabid=".$tabid.")) or troubletickets.parent_id in (select crmid from crmentity left join accountgrouprelation on accountgrouprelation.accountid=crmentity.crmid inner join groups on groups.groupname=accountgrouprelation.groupname where setype='Accounts' and crmentity.smownerid=0 and groups.groupid in(select tmp_read_group_rel_sharing_per.sharedgroupid from tmp_read_group_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('Accounts')." and relatedtabid=".$tabid.")) or (crmentity.smownerid in (0) and (";

                if(sizeof($current_user_groups) > 0)
                {
                	$sec_query .= "groups.groupid in".getCurrentUserGroupList()." or ";
                }
		$sec_query .= "groups.groupid in(select tmp_read_group_sharing_per.sharedgroupid from tmp_read_group_sharing_per where userid=".$current_user->id." and tabid=".$tabid.")))) ";			
	
	}
	elseif($module == 'Emails')
	{
		echo '<BR>now<BR>'; 
		$sec_query .= "and (crmentity.smownerid in($current_user->id) or crmentity.smownerid in(select user2role.userid from user2role inner join users on users.id=user2role.userid inner join role on role.roleid=user2role.roleid where role.parentrole like '".$current_user_parent_role_seq."::%') or crmentity.smownerid in(select shareduserid from tmp_read_user_sharing_per where userid=".$current_user->id." and tabid=".$tabid.")";

		//Adding crterial for account related emails sharing
		 $sec_query .= "or seactivityrel.crmid in (select crmid from crmentity where setype='Accounts' and crmentity.smownerid in(select shareduserid from tmp_read_user_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('Accounts')." and relatedtabid=".$tabid.")) or seactivityrel.crmid in (select crmid from crmentity left join accountgrouprelation on accountgrouprelation.accountid=crmentity.crmid inner join groups on groups.groupname=accountgrouprelation.groupname where setype='Accounts' and crmentity.smownerid=0 and groups.groupid in(select tmp_read_group_rel_sharing_per.sharedgroupid from tmp_read_group_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('Accounts')." and relatedtabid=".$tabid."))";
		//Adding crterial for lead related emails sharing
		 $sec_query .= " or seactivityrel.crmid in (select crmid from crmentity where setype='Leads' and crmentity.smownerid in(select shareduserid from tmp_read_user_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('Leads')." and relatedtabid=".$tabid.")) or seactivityrel.crmid in (select crmid from crmentity left join leadgrouprelation on leadgrouprelation.leadid=crmentity.crmid inner join groups on groups.groupname=leadgrouprelation.groupname where setype='Leads' and crmentity.smownerid=0 and groups.groupid in(select tmp_read_group_rel_sharing_per.sharedgroupid from tmp_read_group_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('Leads')." and relatedtabid=".$tabid."))";
	

		//Adding crteria for group sharing
		 $sec_query .= " or (crmentity.smownerid in (0) and (";

                if(sizeof($current_user_groups) > 0)
                {
                	$sec_query .= "groups.groupid in".getCurrentUserGroupList()." or ";
                }
		$sec_query .= "groups.groupid in(select tmp_read_group_sharing_per.sharedgroupid from tmp_read_group_sharing_per where userid=".$current_user->id." and tabid=".$tabid.")))) ";
	
	}
	elseif($module == 'Activities')
	{
		$sec_query .= "and (crmentity.smownerid in($current_user->id) or crmentity.smownerid in(select user2role.userid from user2role inner join users on users.id=user2role.userid inner join role on role.roleid=user2role.roleid where role.parentrole like '".$current_user_parent_role_seq."::%')";

		if(sizeof($current_user_groups) > 0)
		{
			$sec_query .= " or (crmentity.smownerid in (0) and (groups.groupid in".getCurrentUserGroupList()."))";
		}
		$sec_query .= ")";	
	}
	elseif($module == 'Quotes')
	{
		$sec_query .= "and (crmentity.smownerid in($current_user->id) or crmentity.smownerid in(select user2role.userid from user2role inner join users on users.id=user2role.userid inner join role on role.roleid=user2role.roleid where role.parentrole like '".$current_user_parent_role_seq."::%') or crmentity.smownerid in(select shareduserid from tmp_read_user_sharing_per where userid=".$current_user->id." and tabid=".$tabid.")";

		//Adding crterial for account related quotes sharing
		 $sec_query .= "or quotes.accountid in (select crmid from crmentity where setype='Accounts' and crmentity.smownerid in(select shareduserid from tmp_read_user_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('Accounts')." and relatedtabid=".$tabid.")) or quotes.accountid in (select crmid from crmentity left join accountgrouprelation on accountgrouprelation.accountid=crmentity.crmid inner join groups on groups.groupname=accountgrouprelation.groupname where setype='Accounts' and crmentity.smownerid=0 and groups.groupid in(select tmp_read_group_rel_sharing_per.sharedgroupid from tmp_read_group_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('Accounts')." and relatedtabid=".$tabid."))";
		//Adding crterial for potential related quotes sharing
		 $sec_query .= " or quotes.potentialid in (select crmid from crmentity where setype='Potentials' and crmentity.smownerid in(select shareduserid from tmp_read_user_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('Potentials')." and relatedtabid=".$tabid.")) or quotes.potentialid in (select crmid from crmentity left join potentialgrouprelation on potentialgrouprelation.potentialid=crmentity.crmid inner join groups on groups.groupname=potentialgrouprelation.groupname where setype='Potentials' and crmentity.smownerid=0 and groups.groupid in(select tmp_read_group_rel_sharing_per.sharedgroupid from tmp_read_group_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('Potentials')." and relatedtabid=".$tabid."))";
	

		//Adding crteria for group sharing
		 $sec_query .= " or (crmentity.smownerid in (0) and (";

                if(sizeof($current_user_groups) > 0)
                {
                	$sec_query .= "groups.groupid in".getCurrentUserGroupList()." or ";
                }
		$sec_query .= "groups.groupid in(select tmp_read_group_sharing_per.sharedgroupid from tmp_read_group_sharing_per where userid=".$current_user->id." and tabid=".$tabid.")))) ";			
	
	}	
	elseif($module == 'PurchaseOrder')
	{
		$sec_query .= "and (crmentity.smownerid in($current_user->id) or crmentity.smownerid in(select user2role.userid from user2role inner join users on users.id=user2role.userid inner join role on role.roleid=user2role.roleid where role.parentrole like '".$current_user_parent_role_seq."::%') or crmentity.smownerid in(select shareduserid from tmp_read_user_sharing_per where userid=".$current_user->id." and tabid=".$tabid.") or (crmentity.smownerid in (0) and (";

                if(sizeof($current_user_groups) > 0)
                {
                	$sec_query .= "groups.groupid in".getCurrentUserGroupList()." or ";
                }
		$sec_query .= "groups.groupid in(select tmp_read_group_sharing_per.sharedgroupid from tmp_read_group_sharing_per where userid=".$current_user->id." and tabid=".$tabid.")))) ";			
	
	}
	elseif($module == 'SalesOrder')
	{
		$sec_query .= "and (crmentity.smownerid in($current_user->id) or crmentity.smownerid in(select user2role.userid from user2role inner join users on users.id=user2role.userid inner join role on role.roleid=user2role.roleid where role.parentrole like '".$current_user_parent_role_seq."::%') or crmentity.smownerid in(select shareduserid from tmp_read_user_sharing_per where userid=".$current_user->id." and tabid=".$tabid.")";

		//Adding crterial for account related so sharing
		 $sec_query .= "or salesorder.accountid in (select crmid from crmentity where setype='Accounts' and crmentity.smownerid in(select shareduserid from tmp_read_user_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('Accounts')." and relatedtabid=".$tabid.")) or salesorder.accountid in (select crmid from crmentity left join accountgrouprelation on accountgrouprelation.accountid=crmentity.crmid inner join groups on groups.groupname=accountgrouprelation.groupname where setype='Accounts' and crmentity.smownerid=0 and groups.groupid in(select tmp_read_group_rel_sharing_per.sharedgroupid from tmp_read_group_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('Accounts')." and relatedtabid=".$tabid."))";
		//Adding crterial for potential related so sharing
		 $sec_query .= " or salesorder.potentialid in (select crmid from crmentity where setype='Potentials' and crmentity.smownerid in(select shareduserid from tmp_read_user_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('Potentials')." and relatedtabid=".$tabid.")) or salesorder.potentialid in (select crmid from crmentity left join potentialgrouprelation on potentialgrouprelation.potentialid=crmentity.crmid inner join groups on groups.groupname=potentialgrouprelation.groupname where setype='Potentials' and crmentity.smownerid=0 and groups.groupid in(select tmp_read_group_rel_sharing_per.sharedgroupid from tmp_read_group_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('Potentials')." and relatedtabid=".$tabid."))";
		//Adding crterial for quotes related so sharing
		 $sec_query .= " or salesorder.quoteid in (select crmid from crmentity where setype='Quotes' and crmentity.smownerid in(select shareduserid from tmp_read_user_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('Quotes')." and relatedtabid=".$tabid.")) or salesorder.quoteid in (select crmid from crmentity left join quotegrouprelation on quotegrouprelation.quoteid=crmentity.crmid inner join groups on groups.groupname=quotegrouprelation.groupname where setype='Quotes' and crmentity.smownerid=0 and groups.groupid in(select tmp_read_group_rel_sharing_per.sharedgroupid from tmp_read_group_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('Quotes')." and relatedtabid=".$tabid."))";
	

		//Adding crteria for group sharing
		 $sec_query .= " or (crmentity.smownerid in (0) and (";

                if(sizeof($current_user_groups) > 0)
                {
                	$sec_query .= "groups.groupid in".getCurrentUserGroupList()." or ";
                }
		$sec_query .= "groups.groupid in(select tmp_read_group_sharing_per.sharedgroupid from tmp_read_group_sharing_per where userid=".$current_user->id." and tabid=".$tabid.")))) ";			
	
	}
	elseif($module == 'Invoice')
	{
		$sec_query .= "and (crmentity.smownerid in($current_user->id) or crmentity.smownerid in(select user2role.userid from user2role inner join users on users.id=user2role.userid inner join role on role.roleid=user2role.roleid where role.parentrole like '".$current_user_parent_role_seq."::%') or crmentity.smownerid in(select shareduserid from tmp_read_user_sharing_per where userid=".$current_user->id." and tabid=".$tabid.")";

		//Adding crterial for account related invoice sharing
		 $sec_query .= "or invoice.accountid in (select crmid from crmentity where setype='Accounts' and crmentity.smownerid in(select shareduserid from tmp_read_user_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('Accounts')." and relatedtabid=".$tabid.")) or invoice.accountid in (select crmid from crmentity left join accountgrouprelation on accountgrouprelation.accountid=crmentity.crmid inner join groups on groups.groupname=accountgrouprelation.groupname where setype='Accounts' and crmentity.smownerid=0 and groups.groupid in(select tmp_read_group_rel_sharing_per.sharedgroupid from tmp_read_group_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('Accounts')." and relatedtabid=".$tabid."))";
		//Adding crterial for salesorder related invoice sharing
		 $sec_query .= " or invoice.salesorderid in (select crmid from crmentity where setype='SalesOrder' and crmentity.smownerid in(select shareduserid from tmp_read_user_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('SalesOrder')." and relatedtabid=".$tabid.")) or invoice.salesorderid in(select crmid from crmentity left join sogrouprelation on sogrouprelation.salesorderid=crmentity.crmid inner join groups on groups.groupname=sogrouprelation.groupname where setype='SalesOrder' and crmentity.smownerid=0 and groups.groupid in(select tmp_read_group_rel_sharing_per.sharedgroupid from tmp_read_group_rel_sharing_per where userid=".$current_user->id." and tabid=".getTabid('SalesOrder')." and relatedtabid=".$tabid."))";
	

		//Adding crteria for group sharing
		 $sec_query .= " or (crmentity.smownerid in (0) and (";

                if(sizeof($current_user_groups) > 0)
                {
                	$sec_query .= "groups.groupid in".getCurrentUserGroupList()." or ";
                }
		$sec_query .= "groups.groupid in(select tmp_read_group_sharing_per.sharedgroupid from tmp_read_group_sharing_per where userid=".$current_user->id." and tabid=".$tabid.")))) ";			
	
	}	
	else
	{

		//Current User	
		$sec_query = " and (crmentity.smownerid=".$current_user->id;

		//Subordinate User
		$subUsersList=getSubordinateUsersList();
		if($subUsersList != '')
		{
			$sec_query .= " or crmentity.smownerid in".$subUsersList;
		}

		//Shared User
		$sharedUsersList=getReadSharingUsersList($module);
		if($sharedUsersList != '')
		{
			$sec_query .= " or crmentity.smownerid in".$sharedUsersList;
		}


		//Current User Groups
		if($module == 'Leads' or $module=='HelpDesk' or $module=='Activities')
		{
			$userGroupsList=getCurrentUserGroupList();
			if($userGroupsList != '')
			{
				$sec_query .= " or (crmentity.smownerid in(0) and groups.groupid in".$userGroupsList.")";
			}

			//Shared User Groups
			$sharedGroupsList=getReadSharingGroupsList($module);
			if($sharedGroupsList != '')
			{
				$sec_query .= " or (crmentity.smownerid in(0) and groups.groupid in".$sharedGroupsList.")";
			}	


		}

		$sec_query .=") ";
	}
	return $sec_query;	
}

function get_current_user_access_groups($module)
{
	global $adb,$noof_group_rows;
	$current_user_group_list=getCurrentUserGroupList();
	$sharing_write_group_list=getWriteSharingGroupsList($module);
	$query ="select groupname from groups";
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
	return $result;	
}
/** Function to get the Group Id for a given group groupname
 *  @param $groupname -- Groupname
 *  @returns Group Id -- Type Integer
 */

function getGrpId($groupname)
{
	global $adb;
	
	$result = $adb->query("select groupid from groups where groupname='".$groupname."'");
	$groupid = $adb->query_result($result,0,'groupid');
	return $groupid;
}

/** Function to check permission to access a field for a given user
  * @param $fld_module -- Module :: Type String
  * @param $userid -- User Id :: Type integer
  * @param $fieldname -- Field Name :: Type varchar
  * @returns $rolename -- Role Name :: Type varchar
  *
 */
function getFieldVisibilityPermission($fld_module, $userid, $fieldname)
{

	global $adb;
	global $current_user;


	require('user_privileges/user_privileges_'.$userid.'.php');

	if($is_admin)
	{                                                                                                                                  return 0;
	}                                                                                                                          else
	{
		//get profile list using userid
		$profilelist = getCurrentUserProfileList();

		//get tabid
		$tabid = getTabid($fld_module);

		$query="select profile2field.* from field inner join profile2field on profile2field.fieldid=field.fieldid inner join def_org_field on def_org_field.fieldid=field.fieldid where field.tabid=".$tabid." and profile2field.visible=0 and def_org_field.visible=0  and profile2field.profileid in".$profilelist." and field.fieldname='".$fieldname."' group by field.fieldid";
		$result = $adb->query($query);

		return $adb->query_result($result,"0","visible");
	}
}

/** Function to get the field access module array 
  * @returns The field Access module Array :: Type Array
  *
 */
function getFieldModuleAccessArray()
{

	$fldModArr=Array('Leads'=>'LBL_LEAD_FIELD_ACCESS',
                'Accounts'=>'LBL_ACCOUNT_FIELD_ACCESS',
                'Contacts'=>'LBL_CONTACT_FIELD_ACCESS',
                'Potentials'=>'LBL_OPPORTUNITY_FIELD_ACCESS',
                'HelpDesk'=>'LBL_HELPDESK_FIELD_ACCESS',
                'Products'=>'LBL_PRODUCT_FIELD_ACCESS',
                'Notes'=>'LBL_NOTE_FIELD_ACCESS',
                'Emails'=>'LBL_EMAIL_FIELD_ACCESS',
                'Activities'=>'LBL_TASK_FIELD_ACCESS',
                'Events'=>'LBL_EVENT_FIELD_ACCESS',
                'Vendors'=>'LBL_VENDOR_FIELD_ACCESS',
                'PriceBooks'=>'LBL_PB_FIELD_ACCESS',
                'Quotes'=>'LBL_QUOTE_FIELD_ACCESS',
                'PurchaseOrder'=>'LBL_PO_FIELD_ACCESS',
                'SalesOrder'=>'LBL_SO_FIELD_ACCESS',
                'Invoice'=>'LBL_INVOICE_FIELD_ACCESS'
              );

	return $fldModArr;
}

/** Function to get the permitted module name Array with presence as 0 
  * @returns permitted module name Array :: Type Array
  *
 */
function getPermittedModuleNames()
{
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
	return $permittedModules;			
}

?>
