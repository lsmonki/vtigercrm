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

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="en">
<head>
  <title>Role Details</title>
<!--meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"-->
</head>
<body>

<?php


global $adb;
$Err_msg;
$parentGroupArray=Array();
if(isset($_REQUEST['groupId']) && $_REQUEST['groupId'] != '')
{	
	$mode = 'edit';
	$groupId=$_REQUEST['groupId'];
	$groupInfo=getGroupInfo($groupId);
	require_once('include/utils/GetParentGroups.php');
	$parGroups = new GetParentGroups();
	$parGroups->parent_groups[]=$groupId;
	$parGroups->getAllParentGroups($groupId);
	$parentGroupArray=$parGroups->parent_groups;	
	

}
else
{
	$mode = 'create';
	if(isset($_REQUEST['error']) && ($_REQUEST['error']=='true'))
	{
		$Err_msg = "<center><font color='red'><b>".$mod_strings['LBL_GROUP_NAME_ERROR']."</b></font></center>";
		$groupInfo[] = $_REQUEST['groupname'];
		$groupInfo[] = $_REQUEST['desc'];
	}
}
			

//Constructing the Role Array
$roleDetails=getAllRoleDetails();
$i=0;
$roleIdStr="";
$roleNameStr="";
$userIdStr="";
$userNameStr="";
$grpIdStr="";
$grpNameStr="";

foreach($roleDetails as $roleId=>$roleInfo)
{
	if($i !=0)
	{
		if($i !=1)
		{
			$roleIdStr .= ", ";
			$roleNameStr .= ", ";
		}

		$roleName=$roleInfo[0];
		$roleIdStr .= "'".$roleId."'";
		$roleNameStr .= "'".$roleName."'"; 
	}
	
	$i++;	
}

//Constructing the User Array
$l=0;
$userDetails=getAllUserName();
foreach($userDetails as $userId=>$userInfo)
{
		if($l !=0)
		{
			$userIdStr .= ", ";
			$userNameStr .= ", ";
		}

		$userIdStr .= "'".$userId."'";
		$userNameStr .= "'".$userInfo."'";
	
	$l++;	
}

//Constructing the Group Array
$m=0;
$grpDetails=getAllGroupName();
foreach($grpDetails as $grpId=>$grpName)
{
	if(! in_array($grpId,$parentGroupArray))
	{
		if($m !=0)
		{
			$grpIdStr .= ", ";
			$grpNameStr .= ", ";
		}

		$grpIdStr .= "'".$grpId."'";
		$grpNameStr .= "'".$grpName."'";
	
	$m++;
	}	
}

?>

<script language="javascript">
var constructedOptionValue;
var constructedOptionName;

var roleIdArr=new Array(<?php echo $roleIdStr; ?>);
var roleNameArr=new Array(<?php echo $roleNameStr; ?>);
var userIdArr=new Array(<?php echo $userIdStr; ?>);
var userNameArr=new Array(<?php echo $userNameStr; ?>);
var grpIdArr=new Array(<?php echo $grpIdStr; ?>);
var grpNameArr=new Array(<?php echo $grpNameStr; ?>);

function showOptions()
{
	var selectedOption=document.newGroupForm.memberType.value;
	//Completely clear the select box
	document.forms['newGroupForm'].availList.options.length = 0;

	if(selectedOption == 'groups')
	{
		constructSelectOptions('groups',grpIdArr,grpNameArr);		
	}
	else if(selectedOption == 'roles')
	{
		constructSelectOptions('roles',roleIdArr,roleNameArr);		
	}
	else if(selectedOption == 'rs')
	{
	
		constructSelectOptions('rs',roleIdArr,roleNameArr);	
	}
	else if(selectedOption == 'users')
	{
		constructSelectOptions('users',userIdArr,userNameArr);		
	}

}

function constructSelectOptions(selectedMemberType,idArr,nameArr)
{
	var i;
	var findStr=document.newGroupForm.findStr.value;
	if(findStr.replace(/^\s+/g, '').replace(/\s+$/g, '').length !=0)
	{
		
		var k=0;
		for(i=0; i<nameArr.length; i++)
		{
			if(nameArr[i].indexOf(findStr) ==0)
			{
				constructedOptionName[k]=nameArr[i];
				constructedOptionValue[k]=idArr[i];
				k++;			
			}		
		}
	}
	else
	{
		constructedOptionValue = idArr;
		constructedOptionName = nameArr;	
	}
	
	//Constructing the selectoptions
	var j;
	var nowNamePrefix;	
	for(j=0;j<constructedOptionName.length;j++)
	{
		if(selectedMemberType == 'roles')
		{
			nowNamePrefix = 'Roles::'
		}
		else if(selectedMemberType == 'rs')
		{
			nowNamePrefix = 'RoleAndSubordinates::'
		}
		else if(selectedMemberType == 'groups')
		{
			nowNamePrefix = 'Group::'
		}
		else if(selectedMemberType == 'users')
		{
			nowNamePrefix = 'User::'
		}

		var nowName = nowNamePrefix + constructedOptionName[j];
		var nowId = selectedMemberType + '::'  + constructedOptionValue[j]
		document.forms['newGroupForm'].availList.options[j] = new Option(nowName,nowId);	
	}
	//clearing the array
	constructedOptionValue = new Array();
        constructedOptionName = new Array();	
				

}

function validate()
{
	formSelectColumnString();
	if( !emptyCheck( "groupName", "Group Name" ) )
		return false;

	//alert(document.newGroupForm.selectedColumnsString.value);
	if(document.newGroupForm.selectedColumnsString.value.replace(/^\s+/g, '').replace(/\s+$/g, '').length==0)
	{

		alert('Group should have atleast one member. Select a member to the group');
		return false;
	}
	return true;
}
</script>
	    
            <div class="bodyText mandatory"> </div>
            <form name="newGroupForm" action="index.php" method="post">
                    <input type="hidden" name="module" value="Users">
                    <input type="hidden" name="action" value="SaveGroup">
                    <input type="hidden" name="returnaction" value="<?php echo $_REQUEST['returnaction']?>">
                    <input type="hidden" name="groupId" value="<?php echo $groupId;    ?>">
                    <input type="hidden" name="mode" value="<?php echo $mode;   ?>">
		<table border=0 cellspacing=0 cellpadding=5 width=100% >
			<tr>
				<td class=small><font class=big><b>Settings</b></font><br><font class=h2><b>Groups > Add Group</b></font></td>
			</tr>
		</table>
			
			<hr noshade size=2>
			<br>

			
			<table border=0 cellspacing=1 cellpadding=5 class=small width=100%>  
			<tr bgcolor=white>
				<td nowrap class=small align=left valign=top>
				<?php echo $Err_msg;?>
				<!-- basic details-->
				<table border=0 cellspacing=0 cellpadding=3 width=100% class=big><tr><td style="height:2px;background-color:#dadada"><b>Group Details</b></td></tr></table>

				<table border=0 cellspacing=0 cellpadding=5 width=100% class=small>
				<tr>
					<td align=right width=20%><b><?php echo 'Group Name'; ?></b></td>

					<td width=50%><input type="text" name="groupName" class=small style="width:400px;background-color:#ffffef" value="<?php echo $groupInfo[0] ?>"></td>
					<td width=30%>(<i>Use A-Z, a-z, 1-9</i>)</td>
				</tr>
				<tr>
					<td align=right width=20%><b><?php echo 'Select Member Type'; ?></b></td>

					<td width=50%>
					<select id="memberType" name="memberType" onchange="showOptions()">
					<option value="groups" selected>Groups</option>
					<option value="roles">Roles</option>
					<option value="rs">Roles and Subordinates</option>
					<option value="users">Users</option>
					</select>
					</td>
					<td width=30%><input type="text" name="findStr"><input type="button" name="Find" value="Find" class="button" onClick="showOptions()"></td>
				</tr>
				
				<tr>
					<td valign=top align=right>Select Members<br> </td>
					<td valign=top >
						<select id="availList" name="availList" rows=7 class=small multiple style="width:200px;height:200px">
						
						</select><br>

						
					</td>
					<input type="hidden" name="selectedColumnsString"/>
					<td><table border="0" align="center" cellpadding="0" cellspacing="5">
            				<tr> 
                				<td><div align="center"> 
				                    <input type="button" name="Button" value="Add" class="button" onClick="addColumn()">

			                </div></td>
            				</tr>
					<tr> 
                				<td><div align="center"> 
				                    <input type="button" name="Button1" value="Remove" class="button" onClick="delColumn()">

			                </div></td>
            				</tr>
				        </table>
					</td>
					<td valign=top>
							<select id="selectedColumns" name="selectedColumns" rows=7 class=small multiple style="width:200px;height:200px">
						<?php
                                                if($mode == 'edit')
                                                {
							$groupMemberArr=$groupInfo[2];
                                                        foreach($groupMemberArr as $memberType=>$memberValue)
                                                        {
								foreach($memberValue as $memberId)
                						{
									if($memberType == 'groups')
									{
										$memberName=fetchGroupName($memberId);
										$memberDisplay="Group::";
									}
									elseif($memberType == 'roles')
									{
										$memberName=getRoleName($memberId);
										$memberDisplay="Roles::";
									}
									elseif($memberType == 'rs')
									{
										$memberName=getRoleName($memberId);
										$memberDisplay="RoleAndSubordinates::";
									}
									elseif($memberType == 'users')
									{
										$memberName=getUserName($memberId);
										$memberDisplay="User::";
									}
                                                ?>
                                                                <option value="<?php echo $memberType.'::'.$memberId; ?>"><?php echo $memberDisplay.$memberName; ?></option>
                                                <?php
								}
                                                        }
                                                }
                                                ?>
							</select>
						<br>
					</td>
					<td valign=top>(Use CTRL to select multiple)</td>
				
				</tr>
				<tr>
					<td valign=top align=right>Description </td>
                                        <td valign=top ><textarea name="description" cols="70" rows="8"><?php echo$groupInfo[1]; ?></textarea></td>
                                        <td valign=top></td>
				</tr>
				</table>
				
				<!-- Buttons -->
				<table border=0 cellspacing=0 cellpadding=5 width=100% bgcolor="#efefef">
				<tr>
					<td align=center>
						<input type="submit" class="button" name="add" value="Add Group" onClick="return validate()">
						<input type="button" class="button" name="cancel" value="Cancel" onClick="window.history.back()">
					
					</td>

				</tr>
				</table>
				</td>
			</tr>
			</table>
<script language="JavaScript" type="text/JavaScript">    
        var moveupLinkObj,moveupDisabledObj,movedownLinkObj,movedownDisabledObj;
        function setObjects() 
        {
            availListObj=getObj("availList")
            selectedColumnsObj=getObj("selectedColumns")

        }

        function addColumn() 
        {
            for (i=0;i<selectedColumnsObj.length;i++) 
            {
                selectedColumnsObj.options[i].selected=false
            }

            for (i=0;i<availListObj.length;i++) 
            {
                if (availListObj.options[i].selected==true) 
                {
                    for (j=0;j<selectedColumnsObj.length;j++) 
                    {
                        if (selectedColumnsObj.options[j].value==availListObj.options[i].value) 
                        {
                            var rowFound=true
                            var existingObj=selectedColumnsObj.options[j]
                            break
                        }
                    }

                    if (rowFound!=true) 
                    {
                        var newColObj=document.createElement("OPTION")
                        newColObj.value=availListObj.options[i].value
                        if (browser_ie) newColObj.innerText=availListObj.options[i].innerText
                        else if (browser_nn4 || browser_nn6) newColObj.text=availListObj.options[i].text
                        selectedColumnsObj.appendChild(newColObj)
                        availListObj.options[i].selected=false
                        newColObj.selected=true
                        rowFound=false
                    } 
                    else 
                    {
                        existingObj.selected=true
                    }
                }
            }
        }

        function delColumn() 
        {
            for (i=0;i<=selectedColumnsObj.options.length;i++) 
            {
                if (selectedColumnsObj.options.selectedIndex>=0)
                selectedColumnsObj.remove(selectedColumnsObj.options.selectedIndex)
            }
        }
                        
        function formSelectColumnString()
        {
            var selectedColStr = "";
            for (i=0;i<selectedColumnsObj.options.length;i++) 
            {
                selectedColStr += selectedColumnsObj.options[i].value + ";";
            }
            document.newGroupForm.selectedColumnsString.value = selectedColStr;
        }
	setObjects();
	showOptions();
</script>				
		</form>
              </body>
		
</html>
