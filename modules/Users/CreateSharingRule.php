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
/*
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
*/
//Constructing the Group Array
$m=0;
$grpDetails=getAllGroupName();
foreach($grpDetails as $grpId=>$grpName)
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

?>

<script language="javascript">
var constructedOptionValue;
var constructedOptionName;

var roleIdArr=new Array(<?php echo $roleIdStr; ?>);
var roleNameArr=new Array(<?php echo $roleNameStr; ?>);
//var userIdArr=new Array(<?php echo $userIdStr; ?>);
//var userNameArr=new Array(<?php echo $userNameStr; ?>);
var grpIdArr=new Array(<?php echo $grpIdStr; ?>);
var grpNameArr=new Array(<?php echo $grpNameStr; ?>);

function showOptions(comboName)
{
	//alert(comboName);
	if(comboName == 'availList')
	{
		
		var selectedOption=document.newGroupForm.memberType.value;
	}
	else if(comboName == 'share_availList')
	{
		var selectedOption=document.newGroupForm.share_memberType.value;
	}


	//alert(selectedOption);
	//Completely clear the select box
	getObj(comboName).options.length = 0;

	if(selectedOption == 'groups')
	{
		constructSelectOptions('groups',grpIdArr,grpNameArr,comboName);		
	}
	else if(selectedOption == 'roles')
	{
		constructSelectOptions('roles',roleIdArr,roleNameArr,comboName);		
	}
	else if(selectedOption == 'rs')
	{
	
		constructSelectOptions('rs',roleIdArr,roleNameArr,comboName);	
	}
	/*
	else if(selectedOption == 'users')
	{
		constructSelectOptions('users',userIdArr,userNameArr,comboName);		
	}
	*/

}
//Call on page loading



function constructSelectOptions(selectedMemberType,idArr,nameArr,comboName)
{
	
	constructedOptionValue = idArr;
	constructedOptionName = nameArr;	
	
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
		
		/*
		else if(selectedMemberType == 'users')
		{
			nowNamePrefix = 'User::'
		}
		*/

		var nowName = nowNamePrefix + constructedOptionName[j];
		//var nowId = selectedMemberType + '::'  + constructedOptionValue[j]
		var nowId = constructedOptionValue[j];
		getObj(comboName).options[j] = new Option(nowName,nowId);
		//getObj(comboName).value=H1
		//getObj(comboName).options['H1'].selected=true;	
	}
	//clearing the array
	constructedOptionValue = new Array();
        constructedOptionName = new Array();	
				

}

function validate()
{
	if(document.newGroupForm.availList.value.replace(/^\s+/g, '').replace(/\s+$/g, '').length==0)
        {
		var selOption=document.newGroupForm.memberType.value;
		var entname=getEntityName(selOption);	
                alert('The '+entname+' to be shared is not selected');
                return false;
        }
	if(document.newGroupForm.share_availList.value.replace(/^\s+/g, '').replace(/\s+$/g, '').length==0)
        {
		var selOption=document.newGroupForm.memberType.value;
		var entname=getEntityName(selOption);	
                alert('The Share With '+entname+' is not selected');
                return false;
        }
	return true;
}

function getEntityName(selectedMemberType)
{
	var nowNamePrefix='';
	if(selectedMemberType == 'roles')
	{
		nowNamePrefix = 'Role'
	}
	else if(selectedMemberType == 'rs')
	{
		nowNamePrefix = 'Role And Subordinates'
	}
	else if(selectedMemberType == 'groups')
	{
		nowNamePrefix = 'Group'
	}
	return nowNamePrefix;	
}
</script>
	<?
		global $adb;
		if(isset($_REQUEST['shareid']) && $_REQUEST['shareid'] != '')
		{	
			$mode = 'edit';
			$shareid=$_REQUEST['shareid'];
			$shareInfo=getSharingRuleInfo($shareid);
			$tabid=$shareInfo[1];
			$sharing_module=getTabModuleName($tabid);
			
		}
		else
		{
			$mode = 'create';
			$sharing_module=$_REQUEST['sharing_module'];
			$tabid=getTabid($sharing_module);
		}
			
	?>
    

	<?php
         ?>
            <div class="bodyText mandatory"> </div>
            <form name="newGroupForm" action="index.php" method="post">
                    <input type="hidden" name="module" value="Users">
                    <input type="hidden" name="action" value="SaveSharingRule">
                    <input type="hidden" name="sharing_module" value="<?php echo $sharing_module; ?>">
                    <input type="hidden" name="shareId" value="<?php echo $shareid; ?>">
                    <input type="hidden" name="mode" value="<?php echo $mode;   ?>">
		<table border=0 cellspacing=0 cellpadding=5 width=100% >
			<tr>
				<td class=small><font class=big><b>Settings</b></font><br><font class=h2><b><?php echo $sharing_module ?> Sharing Rule</b></font></td>
			</tr>
		</table>
			
			<hr noshade size=2>
			<br>

			
			<table border=0 cellspacing=1 cellpadding=5 class=small width=100%>  
			<tr bgcolor=white>
				<td nowrap class=small align=left valign=top>
				<!-- basic details-->
				<table border=0 cellspacing=0 cellpadding=3 width=100% class=big><tr><td style="height:2px;background-color:#dadada"><b>Sharing Rule Details</b></td></tr></table>

				<table border=0 cellspacing=0 cellpadding=5 width=100% class=small>
				<tr>
					<td align=right width=20%><b><?php echo $sharing_module; ?> owned by members of</b></td>

					<td>
						<select id="memberType" name="memberType" onchange="showOptions('availList')">
						<option value="groups" selected>Groups</option>
						<option value="roles">Roles</option>
						<option value="rs">Roles and Subordinates</option>
						</select>
					</td>
					<td>
						<select id="availList" name="availList">
						</select>
					</td>
					
				</tr>
				<tr>
					<td align=right width=20%><b>Share with</b></td>

					<td>
						<select id="share_memberType" name="share_memberType" onchange="showOptions('share_availList')">
						<option value="groups" selected>Groups</option>
						<option value="roles">Roles</option>
						<option value="rs">Roles and Subordinates</option>
						</select>
					</td>
					<td>
						<select id="share_availList" name="share_availList">
						</select>
					</td>
					
				</tr>
				<tr>
					<td align=right width=20%><b><?php echo $sharing_module; ?> Access</b></td>

					<td colspan="2">
						<select id="<?php echo $sharing_module; ?>_access" name="<?php echo $sharing_module; ?>_access">
						<option value="0" selected>Read Only</option>
						<option value="1">Read/Write</option>
						</select>
					</td>
					
				</tr>
				<?php
					$relatedModuleSharingArr=getRelatedSharingModules($tabid);
					$size=sizeof($relatedModuleSharingArr);
					if($size > 0)
					{

						if($mode=='edit')
						{
							$relatedModuleSharingPerrArr=getRelatedModuleSharingPermission($shareid);
						}
						foreach($relatedModuleSharingArr as $reltabid=>$relmode_shareid)
						{
							$ro_selected='';
							$rw_selected='';
							$rel_module=getTabModuleName($reltabid);
							if($mode=='create')
							{
								$ro_selected='selected';
							}
							elseif($mode=='edit')
							{
								$perr=$relatedModuleSharingPerrArr[$reltabid];
								if($perr == 0)
								{
									$ro_selected='selected';
								}
								elseif($perr == 1)
								{
									$rw_selected='selected';
								}
										
							}	
							
				?>
							<tr>
							<td align=right width=20%><b><?php echo $rel_module; ?> Access</b></td>

							<td colspan="2">
							<select id="<?php echo $rel_module; ?>_access" name="<?php echo $rel_module; ?>_access">
							<option value="0" <?php echo $ro_selected ?> >Read Only</option>
							<option value="1"<?php echo $rw_selected ?> >Read/Write</option>
							</select>
							</td>
					
							</tr>
				<?php
						}
					}
				?>	
	
	
				
				</table>
				
				<!-- Buttons -->
				<table border=0 cellspacing=0 cellpadding=5 width=100% bgcolor="#efefef">
				<tr>
					<td align=center>
						<input type="submit" class="button" name="add" value="Add Rule" onClick="return validate()">
						<input type="button" class="button" name="cancel" value="Cancel" onClick="window.history.back()">
					
					</td>

				</tr>
				</table>
				</td>
			</tr>
			</table>


	<?php
	if($mode == 'create')
	{
	?>	
		<script language="javascript">
			getObj('memberType').value='roles';
			getObj('share_memberType').value='roles';
			constructSelectOptions('roles',roleIdArr,roleNameArr,'availList');
			constructSelectOptions('roles',roleIdArr,roleNameArr,'share_availList');
		</script>
	<?php
	}
	elseif($mode=='edit')
	{
		$share_ent_type=$shareInfo[3];
		$to_ent_type=$shareInfo[4];
		$share_id=$shareInfo[5];
		$to_id=$shareInfo[6];
		$perr=$shareInfo[7];
		
	?>		
		<script language="javascript">
			var share_type= '<?php echo $share_ent_type;?>';
			var to_type='<?php echo $to_ent_type;?>;'
			getObj('memberType').value='<?php echo $share_ent_type;?>';			
			getObj('share_memberType').value='<?php echo $to_ent_type;?>';
			showOptions('availList');
			showOptions('share_availList');			
			getObj('availList').value='<?php echo $share_id;?>';			
			getObj('share_availList').value='<?php echo $to_id;?>';
			getObj('<?php echo $sharing_module; ?>'+'_access').value='<?php echo $perr;?>';
			
		</script>
	<?php		
	}
	?>
		</form>
              </body>
		
</html>
