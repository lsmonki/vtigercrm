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


require_once('database/DatabaseConnection.php');

function fetchUserRole($userid)
{
	$sql= "select rolename from user2role where userid='" .$userid ."'";
        //echo $sql;
	$result = mysql_query($sql);
	$rolename=  mysql_result($result,0,"rolename");
	return $rolename;
}

function setPermittedTabs2Session($rolename)
{
  $sql = "select tabid from role2tab where rolename='" .$rolename ."' and module_permission !=0" ;
  $result = mysql_query($sql);
  
  $tabPermission=mysql_fetch_array($result);
  $i=0;
  do
  {
    for($j=0;$j<count($tabPermission);$j++)
    {
      $copy[$i]=$tabPermission["tabid"];
    }
    $i++;
    
  }while($tabPermission=mysql_fetch_array($result));
  
  $_SESSION['tab_permission_set']=$copy;
  
}

function setPermittedActions2Session($rolename)
{
  
  $sql= "select role2action.tabid,actionname,action_permission from role2action inner join role2tab on role2tab.rolename=role2action.rolename and role2tab.tabid=role2action.tabid where role2tab.module_permission !=0 and role2tab.rolename='".$rolename ."'";

  $result = mysql_query($sql);

  $permissionRow=mysql_fetch_array($result);
  $i=0;
  do
  {
    for($j=0;$j<count($permissionRow);$j++)
    {
      $copy[$i][0]=$permissionRow["tabid"];
      $copy[$i][1]=$permissionRow["actionname"];
      $copy[$i][2]=$permissionRow["action_permission"];
    }
    $i++;
          
  }while($permissionRow=mysql_fetch_array($result));
        
  $_SESSION['action_permission_set']=$copy;
}



function createNewRole($roleName,$parentRoleName)
{
  $sql = "insert into role(name) values('" .$roleName ."')";
  $result = mysql_query($sql); 
  populatePermissions4NewRole($parentRoleName,$roleName);
  header("Location: index.php?module=Users&action=listroles");
}

function fetchTabId($moduleName)
{

  $sql = "select id from tabmenu where name ='" .$moduleName ."'";
  echo $sql;
  $result = mysql_query($sql); 
  $tabid =  mysql_result($result,0,"id");
  return $tabid;

}


if(isset($_REQUEST['roleName']))
{
  $roleName = $_REQUEST['roleName'];
  //echo $roleName;
  $parentRoleName = $_REQUEST['parentRoleName'];
  //echo 'PARENT ROLE IS '.$parentRoleName;
  createNewRole($roleName,$parentRoleName);
  
}

function populatePermissions4NewRole($parentroleName,$roleName)
{
  //fetch the permissions for the parent role
  $referenceValues = fetchTabReferenceEntityValues($parentroleName);

  while($permissionRow = mysql_fetch_array($referenceValues))
  {
    $sql_insert="insert into role2tab(rolename,tabid,module_permission,description) values('" .$roleName ."'," .$permissionRow['tabid'] ."," .$permissionRow['module_permission'] .", '')";

    //echo $sql_insert;
    mysql_query($sql_insert);
  }

  $actionreferenceValues = fetchActionReferenceEntityValues($parentroleName);
  while($permissionRow = mysql_fetch_array($actionreferenceValues))
  {
    $sql_insert="insert into role2action(rolename,tabid,actionname,action_permission,description) values('" .$roleName ."'," .$permissionRow['tabid'] .",'" .$permissionRow['actionname'] ."'," .$permissionRow['action_permission'] .", '')";
    //echo $sql_insert;
    mysql_query($sql_insert);
  }
  
}


function fetchTabReferenceEntityValues($parentrolename)
{
  
  $sql = "select tabid,module_permission,description from role2tab where rolename='" .$parentrolename ."'"; 
  //echo $sql;
  $result=mysql_query($sql);
  return $result;

}



function fetchActionReferenceEntityValues($parentrolename)
{
  $sql = "select tabid,actionname,action_permission,description from role2action where rolename='" .$parentrolename ."'"; 
  //echo $sql;
  $result=mysql_query($sql);
  return $result;
}


function updateUser2RoleMapping($roleid,$userid)
{

  $sql = "insert into user2role(userid,rolename) values('" .$userid ."','" .$roleid ."')";
  //  echo $sql;
  $result = mysql_query($sql);

}

?>
