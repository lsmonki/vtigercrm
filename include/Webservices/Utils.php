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

function vtws_getUserAccessibleGroups($moduleId, $user){
	global $adb;
	require('user_privileges/user_privileges_'.$user->id.'.php');
	require('user_privileges/sharing_privileges_'.$user->id.'.php');
	$tabName = getTabname($moduleId);
	if($is_admin==false && $profileGlobalPermission[2] == 1 && 
			($defaultOrgSharingPermission[$moduleId] == 3 or $defaultOrgSharingPermission[$moduleId] == 0)){
		$result=get_current_user_access_groups($tabName);
	}else{ 		
		$result = get_group_options();
	}
	
	$groups = array();
	if($result != null && $result != '' && is_object($result)){
		$rowCount = $adb->num_rows($result);
		for ($i = 0; $i < $rowCount; $i++) {
			$nameArray = $adb->query_result_rowdata($result,$i);
			$groupId=$nameArray["groupid"];
			$groupName=$nameArray["groupname"];
			$groups[] = array('id'=>$groupId,'name'=>$groupName);
		}
	}
	return $groups;
}

function vtws_getWebserviceGroupFromGroups($groups){
	global $adb;
	$webserviceObject = VtigerWebserviceObject::fromName($adb,'Groups');
	foreach($groups as $index=>$group){
		$groups[$index]['id'] = vtws_getId($webserviceObject->getEntityId(),$group['id']);
	}
	return $groups;
}

function vtws_getUserWebservicesGroups($tabId,$user){
	$groups = vtws_getUserAccessibleGroups($tabId,$user);
	return vtws_getWebserviceGroupFromGroups($groups);
}

function vtws_getIdComponents($elementid){
	return explode("x",$elementid);
}

function vtws_getId($objId, $elemId){
	return $objId."x".$elemId;
}

function getEmailFieldId($meta, $entityId,$fields){
	global $adb;
	if(sizeof($fields)>0){
		return $meta->getFieldIdFromFieldName($fields[0]);
	}
	//no email field accessible in the module. since its only association pick up the field any way.
	$query="SELECT fieldid,fieldlabel,columnname FROM vtiger_field WHERE tabid=? 
		and uitype=13 and presence in (0,2)";
	$result = $adb->pquery($query, array($meta->getEntityId()));
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

	$sql = "select name from vtiger_tab where presence=0 and isentitytype=1 order by tabsequence";
	$res = $adb->pquery($sql, array());
	$mod_array = Array();
	while($row = $adb->fetchByAssoc($res)){
		array_push($mod_array,$row['name']);
	}
	return $mod_array;
}

function vtws_getWebserviceEntities(){
	global $adb;

	$sql = "select name,id,ismodule from vtiger_ws_entity";
	$res = $adb->pquery($sql, array());
	$moduleArray = Array();
	$entityArray = Array();
	while($row = $adb->fetchByAssoc($res)){
		if($row['ismodule'] == '1'){
			array_push($moduleArray,$row['name']);
		}else{
			array_push($entityArray,$row['name']);
		}
	}
	return array('module'=>$moduleArray,'entity'=>$entityArray);
}

function vtws_includeModule($moduleName){
	if($moduleName == "Events"){
		$moduleName = "Calendar";
	}
	if($moduleName == "Calendar"){
		require_once("modules/".$moduleName."/Activity.php");
	}else{
		require_once("modules/".$moduleName."/".$moduleName.".php");
	}
}

function vtws_getModuleInstance($webserviceObject){
	$moduleName = $webserviceObject->getEntityName();
	vtws_includeModule($moduleName);
	if($moduleName == "Calendar" || $moduleName == "Events"){
		$moduleName = "Activity";
	}
	return new $moduleName();
}

function vtws_isRecordOwnerUser($ownerId){
	global $adb;
	$result = $adb->pquery("select first_name from vtiger_users where id = ?",array($ownerId));
	$rowCount = $adb->num_rows($result);
	$ownedByUser = ($rowCount > 0);
	return $ownedByUser;
}

function vtws_isRecordOwnerGroup($ownerId){
	global $adb;
	$result = $adb->pquery("select groupname from vtiger_groups where groupid = ?",array($ownerId));
	$rowCount = $adb->num_rows($result);
	$ownedByGroup = ($rowCount > 0);
	return $ownedByGroup;
}

function vtws_getOwnerType($ownerId){
	if(vtws_isRecordOwnerGroup($ownerId) == true){
		return 'Groups';
	}
	if(vtws_isRecordOwnerUser($ownerId) == true){
		return 'Users';
	}
	throw new WebServiceException(WebServiceErrorCode::$INVALIDID,"Invalid owner of the record");
}

function vtws_runQueryAsTransaction($query,$params,&$result){
	global $adb;
	
	$adb->startTransaction();
	$result = $adb->pquery($query,$params);
	$error = $adb->hasFailedTransaction();
	$adb->completeTransaction();
	return !$error;
}

function vtws_getCalendarEntityType($id){
	global $adb;
	
	$sql = "select activitytype from vtiger_activity where activityid=?";
	$result = $adb->pquery($sql,array($id));
	$seType = 'Calendar';
	if($result != null && isset($result)){
		if($adb->num_rows($result)>0){
			$activityType = $adb->query_result($result,0,"activitytype");
			if($activityType !== "Task"){
				$seType = "Events";
			}
		}
	}
	return $seType;
}

/***
 * Get the webservice reference Id given the entity's id and it's type name
 */
function vtws_getWebserviceEntityId($entityName, $id){
	global $adb;
 
	$webserviceObject = VtigerWebserviceObject::fromName($adb,$entityName);
	return $webserviceObject->getEntityId().'x'.$id;
}
?>