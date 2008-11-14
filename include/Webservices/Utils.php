<?php

/* Function to return all the users in the groups that this user is part of.
 * @param $id - id of the user
 * returns Array:UserIds userid of all the users in the groups that this user is part of.
 */
function vtws_getUsersInTheSameGroup($id){
	require_once('include/utils/GetGroupUsers.php');
	require_once('include/utils/GetUserGroups.php');
	
	$groupUsers = new GetGroupUsers();
	$userGroups = new GetUserGroups();
	$allUsers = Array();
	$userGroups->getAllUserGroups($id);
	$groups = $userGroups->user_groups;
	
	foreach ($groups as $group) {
		$groupUsers->getAllUsersInGroup($group);
		$usersInGroup = $groupUsers->group_users;
		foreach ($usersInGroup as $user) {
	                        if($user != $id){
				$allUsers[$user] = getUserName($user); 
			}
		}		
	}
	return $allUsers;
}

function vtws_generateRandomAccessKey($length=10){
	$source = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$accesskey = "";
	$maxIndex = strlen($source);
	for($i=0;$i<$length;++$i){
		$accesskey = $accesskey.substr($source,rand(null,$maxIndex),1);
	}
	return $accesskey;
}

/**
 * get current vtiger version from the database.
 */
function vtws_getVtigerVersion(){
	global $adb;
	$query = 'select * from vtiger_version';
	$result = $adb->pquery($query, array());
	$version = '';
	while($row = $adb->fetch_array($result))
	{
		$version = $row['current_version'];
	}
	return $version;
}

function vtws_getUserAccessibleGroups($crmObject, $user){
	global $adb;
	require('user_privileges/user_privileges_'.$user->id.'.php');
	require('user_privileges/sharing_privileges_'.$user->id.'.php');
	
	$moduleId = $crmObject->getModuleId();
	if($is_admin==false && $profileGlobalPermission[2] == 1 && 
			($defaultOrgSharingPermission[$moduleId] == 3 or $defaultOrgSharingPermission[$moduleId] == 0)){
		$result=get_current_user_access_groups($crmObject->getModuleName());
	}else{ 		
		$result = get_group_options();
	}
	
	$groups = array();
	while($nameArray = $adb->fetch_array($result)){
		$groupId=$nameArray["groupid"];
		$groupName=$nameArray["groupname"];
		$groups[] = array('id'=>$groupId,'name'=>$groupName);
	}
	return $groups;
}
function vtws_getIdForGroup($groupId){
	return 'GROUPx'.$groupId;
}
function vtws_getGroupIdFromWebserviceGroupId($elementId){
	if(stristr($elementId,"group")!==false){
		$id = getIdComponents($elementId);
		return $id[1];
	}
	return null;
}

function vtws_getWebserviceGroupFromGroups($groups){
	foreach($groups as $index=>$group){
		$groups[$index]['id'] = vtws_getIdForGroup($group['id']);
	}
	return $groups;
}

function vtws_getUserWebservicesGroups($crmObject,$user){
	$groups = vtws_getUserAccessibleGroups($crmObject,$user);
	return vtws_getWebserviceGroupFromGroups($groups);
}

?>