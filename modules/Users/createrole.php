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

            <script language="javascript">
    function validate()
    {
	formSelectColumnString();
        if( !emptyCheck( "roleName", "Role Name" ) )
            return false;

	//alert(document.newRoleForm.selectedColumnsString.value);
	if(document.newRoleForm.selectedColumnsString.value.replace(/^\s+/g, '').replace(/\s+$/g, '').length==0)
	{

                        alert('Role should have atlease one profile');
			return false;
	}
        return true;
    }
             </script>
	<?
		global $adb;
		$profDetails=getAllProfileInfo();
		if(isset($_REQUEST['roleid']) && $_REQUEST['roleid'] != '')
		{	
			$roleid= $_REQUEST['roleid'];
			$mode = $_REQUEST['mode'];
			$roleInfo=getRoleInformation($roleid);
			$thisRoleDet=$roleInfo[$roleid];
			$rolename = $thisRoleDet[0]; 
			$parent = $thisRoleDet[3]; 
			//retreiving the profileid
			$roleRelatedProfiles=getRoleRelatedProfiles($roleid);
			
		}
		elseif(isset($_REQUEST['parent']) && $_REQUEST['parent'] != '')
		{
			$mode = 'create';
			$parent=$_REQUEST['parent'];
		}
			
		$parentname=getRoleName($parent);
	?>
    
            <div class="bodyText mandatory"> </div>
            <form name="newRoleForm" action="index.php">
                    <input type="hidden" name="module" value="Users">
                    <input type="hidden" name="action" value="SaveRole">
                    <input type="hidden" name="returnaction" value="<?php echo $_REQUEST['returnaction']?>">
                    <input type="hidden" name="roleid" value="<?php echo $roleid;    ?>">
                    <input type="hidden" name="mode" value="<?php echo $mode;   ?>">
                    <input type="hidden" name="parent" value="<?php echo $parent; ?>">
		<table border=0 cellspacing=0 cellpadding=5 width=100% >
			<tr>
				<td class=small><font class=big><b>Settings</b></font><br><font class=h2><b>Roles > Add Role</b></font></td>
			</tr>
		</table>
			
			<hr noshade size=2>
			<br>

			
			<table border=0 cellspacing=1 cellpadding=5 class=small width=100%>  
			<tr bgcolor=white>
				<td nowrap class=small align=left valign=top>
				<!-- basic details-->
				<table border=0 cellspacing=0 cellpadding=3 width=100% class=big><tr><td style="height:2px;background-color:#dadada"><b>Role Details</b></td></tr></table>

				<table border=0 cellspacing=0 cellpadding=5 width=100% class=small>
				<tr>
					<td align=right width=20%><b><?php echo 'Role Name'; ?></b></td>

					<td width=50%><input type="text" name="roleName" class=small style="width:400px;background-color:#ffffef" value="<?php echo $rolename ?>"></td>
					<td width=30%>(<i>Use A-Z, a-z, 1-9</i>)</td>
				</tr>
				<tr>
					<td valign=top align=right>Select Profiles<br> </td>
					<td valign=top >

						<select id="availList" name="availList" rows=7 class=small multiple style="width:200px;height:200px">
						<?php
							foreach($profDetails as $profId=>$profName)
							{
						?>
						<option value="<?php echo $profId; ?>"><?php echo $profName; ?></option>
						<?php
							}
						?>
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
							foreach($roleRelatedProfiles as $relProfId=>$relProfName)
							{
						?>
								<option value="<?php echo $relProfId; ?>"><?php echo $relProfName; ?></option>
						<?php
							}
						}
						?>
                                                </select><br>		
					
					</td>
					<td valign=top>(Use CTRL to select multiple)</td>
				</tr>
				<tr>
					<td valign=top align=right>Reports to </td>
					<td valign=top ><b><?php echo $parentname;?><b></td>

					<td valign=top></td>
				</tr>
				</table>
				
				<!-- Buttons -->
				<table border=0 cellspacing=0 cellpadding=5 width=100% bgcolor="#efefef">
				<tr>
					<td align=center>
						<input type="submit" class="button" name="add" value="Add Role" onClick="return validate()">
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
            document.newRoleForm.selectedColumnsString.value = selectedColStr;
        }
	setObjects();			
</script>				
		</form>
              </body>
		
</html>
