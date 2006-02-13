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
require_once('include/utils.php');

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
        if( !emptyCheck( "roleName", "Role Name" ) )
            return false;    
            
        return true;
    }
    
    function cancelNewRoleCreation( roleId )
    {
    
    }
	<?
		global $adb;
		if(isset($_REQUEST['roleid']) && $_REQUEST['roleid'] != '')
		{	
			$roleid= $_REQUEST['roleid'];
			$sql = "select * from role where roleid=".$roleid;
			$roleResult = $adb->query($sql);
			$mode = $_REQUEST['mode'];
			$rolename = $adb->query_result($roleResult,0,"name");

			//retreiving the profileid
			$sql1 = "select profile.* from role2profile inner join profile on profile.profileid=role2profile.profileid where roleid=".$roleid;
		        $result1 = $adb->query($sql1);
			$selected_profileid = $adb->query_result($result1,0,'profileid');
			echo 'select profileid is'.$selected_profileid;
		}
	?>
    
             </script>
            <div class="bodyText mandatory"> </div>
            <form name="newRoleForm" action="index.php">
                    <input type="hidden" name="module" value="Users">
                    <input type="hidden" name="action" value="SaveRole">
                    <input type="hidden" name="roleid" value="<?php echo $roleid;    ?>">
                    <input type="hidden" name="mode" value="<?php echo $mode;    ?>">
              <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tbody>
                  <tr>
                    <td class="moduleTitle hline"><?php echo $mod_strings['LBL_HDR_ROLE_NAME'];?></td>
                  </tr>
                </tbody>
              </table>
              <p></p>
              <table width="40%" border="0" cellpadding="0" cellspacing="0">
                <tbody>
                  <tr>
                    <td>
                    <div><font class="required"><?php echo $app_strings['LBL_REQUIRED_SYMBOL']; ?></font><?php echo $mod_strings['LBL_INDICATES_REQUIRED_FIELD']; ?> </div>
                    </td>
                  </tr>
                </tbody>
              </table>
              <table width="40%" border="0" cellpadding="5"
 cellspacing="1" class="formOuterBorder">
                <tbody>
                  <tr>
                    <td class="formSecHeader" colspan="2"><?php echo $mod_strings['LBL_TITLE_ROLE_NAME'];?></td>
                  </tr>
                  <tr>
                    <td class="dataLabel mandatory"><font class="required"><?php echo $app_strings['LBL_REQUIRED_SYMBOL'];?></font><?php echo $mod_strings['LBL_ROLE_NAME']; ?></td>
                    <td class="value"><input class="textField" type="text" name="roleName" value="<?php echo $rolename;  ?>"></td>
                  </tr>
                  <tr>
                    <td class="dataLabel mandatory"><font class="required"><?php echo $app_strings['LBL_REQUIRED_SYMBOL'];?></font><?php echo $mod_strings['LBL_ROLE_PROFILE_NAME'];?></td>
                    <td class="value">
                    <select class="select" name="profileId">
            <?php
             $sql = "select * from profile";
                  $result = $adb->query($sql);
                  $temprow = $adb->fetch_array($result);
                  do
                  {
		    $selected = '';	
                    $name=$temprow["profilename"];
		    $profileid=$temprow["profileid"];
		    if($profileid == $selected_profileid)
		    {
			$selected = 'selected';
		    }		
                    ?>
                      
                    <option value="<?php echo $profileid ?>" <?php echo $selected;  ?>><?php echo $temprow["profilename"] ?></option>
                       <?php
                    }while($temprow = $adb->fetch_array($result));
                     ?>
                    
                    </select>
                    </td>
                  </tr>
                </tbody>
              </table>
              <p></p>
              <table width="40%" border="0" cellpadding="0"
 cellspacing="0">
                <tbody>
                  <tr>
                    <td>
                     <div align="center">
                   
 <input type="submit" class="button" name="save" value="<?php echo $app_strings['LBL_SAVE_BUTTON_LABEL'] ?>" tabindex="2" onclick="return validate()">
  <input name="cancel" class="button" type="button" value="<?php echo $app_strings['LBL_CANCEL_BUTTON_LABEL'] ?>" onclick="window.history.back()">
</form> </div>
                    </td>
                  </tr>
                </tbody>
              </table>
</body>
</html>
