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
    
             </script>
	<?php
		$delete_prof_id = $_REQUEST['profileid'];
		$delete_prof_name = getProfileName($delete_prof_id);
	?>
            <div class="bodyText mandatory"> </div>
            <form name="newProfileForm" action="index.php">
                    <input type="hidden" name="module" value="Users">
                    <input type="hidden" name="action" value="DeleteProfile">
		    <input type="hidden" name="delete_prof_id" value="<?php echo $delete_prof_id;  ?>">	
              <table width="100%" border="0" cellpadding="0"
 cellspacing="0">
                <tbody>
                  <tr>
                    <td class="moduleTitle hline">Delete Profile:</td>
                  </tr>
                </tbody>
              </table>
              <p></p>
              <table width="40%" border="0" cellpadding="0"
 cellspacing="0">
                <tbody>
                  <tr>
                    <td>
                    <div align="right"><font class="required">*</font><?php echo $mod_strings['LBL_INDICATES_REQUIRED_FIELD']; ?> </div>
                    </td>
                  </tr>
                </tbody>
              </table>
              <table width="40%" border="0" cellpadding="0"
 cellspacing="1" class="formOuterBorder">
                <tbody>
                  <tr>
                    <td class="formSecHeader" colspan="2">Delete Profile</td>
                  </tr>
                  <tr>
                    <td class="dataLabel mandatory">* Profile To Be Deleted:</td>
                    <td class="value"><?php echo $delete_prof_name;  ?></td>
                  </tr>
                  <tr>
                    <td class="dataLabel mandatory">* Transfer Roles to Profile:</td>
                    <td class="value">
                    <select class="select" name="transfer_prof_id">
            <?php
	     global $adb;	
             $sql = "select * from profile";
                  $result = $adb->query($sql);
                  $temprow = $adb->fetch_array($result);
                  do
                  {
                    $prof_name=$temprow["profilename"];
		    $prof_id=$temprow["profileid"];
		    if($delete_prof_id 	!= $prof_id)
		    {	 
                    ?>
                      
                    <option value="<?php echo $prof_id ?>"><?php echo $prof_name ?></option>
                       <?php
		     }	
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
                   
 <input type="submit" class="button" name="save" value="<?php echo $app_strings['LBL_SAVE_BUTTON_LABEL'] ?>" tabindex="2">
  <input name="cancel" class="button" type="button" value="<?php echo $app_strings['LBL_CANCEL_BUTTON_LABEL'] ?>" onclick="window.history.back()">
</form> </div>
                    </td>
                  </tr>
                </tbody>
              </table>
</body>
</html>
