<?php

require_once('include/utils/UserInfoUtil.php');
$toid=$_REQUEST['parentId'];
$fromid=$_REQUEST['childId'];


global $adb;
$query = "select * from role where roleid='".$toid."'";
$result=$adb->query($query);
$parentRoleList=$adb->query_result($result,0,'parentrole');
$replace_with=$parentRoleList;
$orgDepth=$adb->query_result($result,0,'depth');

//echo 'replace with is '.$replace_with;
//echo '<BR>org depth '.$orgDepth;
$parentRoles=explode('::',$parentRoleList);

if(in_array($fromid,$parentRoles))
{
	echo 'You cannot move a Parent Node under a Child Node';
        die;
}


$roleInfo=getRoleAndSubordinatesInformation($fromid);

$fromRoleInfo=$roleInfo[$fromid];
$replaceToStringArr=explode('::'.$fromid,$fromRoleInfo[1]);
$replaceToString=$replaceToStringArr[0];
//echo '<BR>to be replaced string '.$replaceToString;


$stdDepth=$fromRoleInfo['2'];
//echo '<BR> std depth '.$stdDepth;

//Constructing the query
foreach($roleInfo as $mvRoleId=>$mvRoleInfo)
{
	$subPar=explode($replaceToString,$mvRoleInfo[1]);
	$mvParString=$replace_with.$subPar[1];
	$subDepth=$mvRoleInfo[2];
	$mvDepth=$orgDepth+(($subDepth-$stdDepth)+1);
	$query="update role set parentrole='".$mvParString."',depth=".$mvDepth." where roleid='".$mvRoleId."'";
	//echo $query;
	$adb->query($query);
		
}



header("Location: index.php?action=UsersAjax&module=Users&file=listroles&ajax=true");
?>
