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
	if($result != null && $result != '' && is_object($result)){
		while($nameArray = $adb->fetch_array($result)){
			$groupId=$nameArray["groupid"];
			$groupName=$nameArray["groupname"];
			$groups[] = array('id'=>$groupId,'name'=>$groupName);
		}
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

function getIdComponents($elementid){
	return explode("x",$elementid);
}

function getId($objId, $elemId){
	return $objId."x".$elemId;
}

function getEmailFieldId($meta, $entityId,$fields){
	global $adb;
	if(sizeof($fields)>0){
		return $meta->getFieldIdFromFieldName($fields[0]);
	}
	//no email field accessible in the module. since its only association pick up the field any way.
	$query="SELECT fieldid,fieldlabel,columnname FROM vtiger_field WHERE tabid=? and uitype=13;";
	$result = $adb->pquery($query, array($meta->getObjectId()));
	//pick up the first field.
	$fieldId = $adb->query_result($result,0,'fieldid');
	return $fieldId;
}

function vtws_getParameter($parameterArray, $paramName,$default=null){
	
	if (!get_magic_quotes_gpc()) {
		$param = addslashes($parameterArray[$paramName]);
	} else {
		$param = $parameterArray[$paramName];
	}
	if(!$param){
		$param = $default;
	}
	return $param;
}

function vtws_getEntityNameFields($moduleName){
	
	global $adb;
	$query = "select fieldname,tablename,entityidfield from vtiger_entityname where modulename = ?";
	$result = $adb->pquery($query, array($moduleName));
	$rowCount = $adb->num_rows($result);
	$nameFields = array();
	if($rowCount > 0){
		$fieldsname = $adb->query_result($result,0,'fieldname');
		if(!(strpos($fieldsname,',') === false)){
			 $nameFields = explode(',',$fieldsname);
		}else{
			array_push($nameFields,$fieldsname);
		}
	}
	return $nameFields;	
}

/** function to get the module List to which are crm entities. 
 *  @return Array modules list as array
 */
function vtws_getModuleNameList(){
	global $adb;

	$sql = "select vtiger_moduleowners.*, vtiger_tab.name from vtiger_moduleowners inner join vtiger_tab on vtiger_moduleowners.tabid = vtiger_tab.tabid order by vtiger_tab.tabsequence";
	$res = $adb->pquery($sql, array());
	$mod_array = Array();
	while($row = $adb->fetchByAssoc($res)){
		array_push($mod_array,$row['name']);
	}
	return $mod_array;
}

?>