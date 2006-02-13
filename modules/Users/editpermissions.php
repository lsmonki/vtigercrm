<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<!-- 
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/
-->
<?php

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once('include/database/PearDatabase.php');

?>

<html lang="en">
<head>
  <title>Role Details</title>
<!--meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"-->
</head>
<body>
<!--c:out value="${locale}"/-->
<!--fmt:setLocale value="ja_JP"/-->
                  <div class="moduleTitle hline"><?php echo $mod_strings['LBL_ROLE_DETAILS']; ?> : <?php echo $_REQUEST["rolename"] ?></div>
                <br>
            <form name="editperm" action="index.php" method="post">
              <input type="hidden" name="action" value="updateRole">
              <input type="hidden" name="rolename" value="<?php echo $_REQUEST["rolename"] ?>"> 
              <input type="hidden" name="module" value="Users">
              <input type="hidden" name="return_module" value="Users">
              <input type="hidden" name="return_action" value="ListPermission">

                    <table border="0" width="100%" cellspacing="0" cellpadding="0" class="FormBorder">

        <th class="formHeader" background="<?php echo $image_path ?>header_tile.gif" vAlign="middle" align="left" noWrap width="20%" height="22"><b><?php echo $mod_strings['LBL_ENTITY_LEVEL_PERMISSIONS']; ?></b></th>

                      <tbody>
                        <tr class="moduleListTitle" height="25">
                          <td nowrap="nowrap">
                          <div align="left"><b><?php echo $mod_strings['LBL_ENTITY']; ?></b></div>
                          </td>
                          <td nowrap="nowrap">
                          <div align="center"><b><?php echo $mod_strings['LBL_CREATE_EDIT']; ?></b></div>
                          </td>
                          <!--td nowrap="nowrap" class="bodyText bold">
                          <div align="center">Edit</div>
                          </td -->
                          <td nowrap="nowrap">
                          <div align="center"><b><?php echo $mod_strings['LBL_DELETE']; ?></b></div>
                          </td>
                          <td nowrap="nowrap">
                          <div align="center"><b><?php echo $mod_strings['LBL_ALLOW']; ?></b></div>
                          </td>
                        </tr>
                        <tr class="oddListRow">
                         <td nowrap="nowrap"><?php echo $mod_strings['LBL_LEADS']; ?></td>
	<?php

$sql = "select actionname,action_permission,module_permission from role2action inner join role2tab on role2tab.rolename=role2action.rolename and role2tab.tabid=role2action.tabid where role2tab.rolename='".$_REQUEST["rolename"] ."' and role2action.tabid=3";


         $result = $adb->query($sql);
	if(@$adb->query_result($result,1,"action_permission") == 1)
	{
       ?>
                          <td>
         <div align="center"> <input checked type="checkbox" name="lead_create"> </div>
                          </td>
                          <!--td>
        <div align="center"> <input checked  type="checkbox" name="lead_edit"> </div>
                          </td -->
	<?php
	}
      else
        {
          ?>

                          <td>
        <div align="center"> <input  type="checkbox" name="lead_create"> </div>
                          </td>
                          <!-- td>
        <div align="center"> <input   type="checkbox" name="lead_edit"> </div>
                          </td -->
       <?php
        }

	if(@$adb->query_result($result,0,"action_permission") == 1)
        {
	?>
                            
                          <td>
      <div align="center"> <input checked="checked" type="checkbox" name="lead_delete"> </div>
                          </td>
	<?
	}
	else
	{
	?>
                             
                          <td>
     <div align="center"> <input  type="checkbox" name="lead_delete"> </div>
                          </td>
	<?
	}

 if(@$adb->query_result($result,2,"module_permission") == 1)
        {
        ?>

                          <td>
      <div align="center"> <input onclick="disableOthers('lead',this.checked)" checked="checked" type="checkbox" name="lead_module_access"> </div>
                          </td>
        <?
        }
        else
        {
        ?>

                          <td>
     <div align="center"> <input onclick="disableOthers('lead',this.checked)" type="checkbox" name="lead_module_access"> </div>
                          </td>
        <?
        }


	?>

                        </tr>
                        </tr>
                        <tr class="evenListRow">
                          <td nowrap="nowrap"><?php echo $mod_strings['LBL_ACCOUNTS']; ?></td>

<?php
                        $sql_accounts = "select actionname,action_permission,module_permission from role2action inner join role2tab on role2tab.rolename=role2action.rolename and role2tab.tabid=role2action.tabid where role2tab.rolename='".$_REQUEST["rolename"] ."' and role2action.tabid=5";

            $result_accounts = $adb->query($sql_accounts);
        if(@$adb->query_result($result_accounts,1,"action_permission") == 1)
        {

        ?>

                 <td>
        <div align="center"> <input checked type="checkbox" name="account_create"> </div>
                          </td>
                          <!-- td>
        <div align="center"> <input checked type="checkbox" name="account_edit"> </div>
                          </td -->
<?php
}
  else
        {
          ?>
  
                               <td>
      <div align="center"> <input  type="checkbox" name="account_create"> </div>
                          </td>
                          <!-- td>
      <div align="center"> <input  type="checkbox" name="account_edit"> </div>
                          </td -->

<?php

        }
if(@$adb->query_result($result_accounts,0,"action_permission") == 1)
{
?>
                          <td>
     <div align="center"> <input checked="checked" type="checkbox" name="account_delete"> </div>
                          </td>
<?
}
else
{
?>
                       <td>
     <div align="center"> <input type="checkbox" name="account_delete"> </div>
                          </td>
	
 <?
}




if(@$adb->query_result($result_accounts,2,"module_permission") == 1)
{
?>
                          <td>
     <div align="center"> <input onclick="disableOthers('account',this.checked)" checked="checked" type="checkbox" name="account_module_access"> </div>
                          </td>
<?
}
else
{
?>
                       <td>
     <div align="center"> <input onclick="disableOthers('account',this.checked)" type="checkbox" name="account_module_access"> </div>
                          </td>

 <?
}

?>





                        <tr class="oddListRow">
                          <td nowrap="nowrap"><?php echo $mod_strings['LBL_CONTACTS']; ?></td>

 	  <?php
                        $sql_contacts = "select actionname,action_permission,module_permission from role2action inner join role2tab on role2tab.rolename=role2action.rolename and role2tab.tabid=role2action.tabid where role2tab.rolename='".$_REQUEST["rolename"] ."' and role2action.tabid=4";
$result_contacts = $adb->query($sql_contacts);
        if(@$adb->query_result($result_contacts,2,"action_permission") == 1)
        {
        ?>

                          <td>
     <div align="center"> <input checked type="checkbox" name="contact_create"> </div>
                          </td>
                          <!-- td>
     <div align="center"> <input checked type="checkbox" name="contact_edit"> </div>
                          </td -->
	<?php
	}
       else
        {
          ?>
                          <td>
     <div align="center"> <input  type="checkbox" name="contact_create"> </div>
                          </td>
                          <!-- td>
     <div align="center"> <input  type="checkbox" name="contact_edit"> </div>
                          </td -->
     <?php
        }
	if(@$adb->query_result($result_contacts,1,"action_permission") == 1)
        {
        ?>

                          <td>
       <div align="center"> <input checked type="checkbox" name="contact_delete"> </div>
                          </td>
	<?
	}
	else
	{
	?>
                          <td>
       <div align="center"> <input type="checkbox" name="contact_delete"> </div>
                          </td>
        <?php
	}


 if(@$adb->query_result($result_contacts,2,"module_permission") == 1)
        {
        ?>

                          <td>
       <div align="center"> <input onclick="disableOthers('contact',this.checked)" checked type="checkbox" name="contact_module_access"> </div>
                          </td>
        <?
        }
        else
        {
        ?>
                          <td>
       <div align="center"> <input onclick="disableOthers('contact',this.checked)" type="checkbox" name="contact_module_access"> </div>
                          </td>
        <?php
        }

	?>

                        </tr>

                        <tr class="evenListRow">
                          <td nowrap="nowrap"><?php echo $mod_strings['LBL_OPPURTUNITIES']; ?></td>

 	  <?php
                        $sql_opportunities = "select actionname,action_permission,module_permission from role2action inner join role2tab on role2tab.rolename=role2action.rolename and role2tab.tabid=role2action.tabid where role2tab.rolename='".$_REQUEST["rolename"] ."' and role2action.tabid=6";
$result_opportunities = $adb->query($sql_opportunities);
        if(@$adb->query_result($result_opportunities,1,"action_permission") == 1)
        {
        ?>

                          <td>
     <div align="center"> <input checked type="checkbox" name="opportunities_create"> </div>
                          </td>
                          <!-- td>
     <div align="center"> <input checked type="checkbox" name="opportunities_edit"> </div>
                          </td -->
	<?php
	}
       else
        {
          ?>
                          <td>
     <div align="center"> <input  type="checkbox" name="opportunities_create"> </div>
                          </td>
                          <!-- td>
     <div align="center"> <input  type="checkbox" name="opportunities_edit"> </div>
                          </td -->
     <?php
        }
	if(@$adb->query_result($result_opportunities,0,"action_permission") == 1)
        {
        ?>

                          <td>
       <div align="center"> <input checked type="checkbox" name="opportunities_delete"> </div>
                          </td>
	<?
	}
	else
	{
	?>
                          <td>
       <div align="center"> <input type="checkbox" name="opportunities_delete"> </div>
                          </td>
        <?php
	}


if(@$adb->query_result($result_opportunities,2,"module_permission") == 1)
        {
        ?>

                          <td>
       <div align="center"> <input onclick="disableOthers('opportunities',this.checked)" checked type="checkbox" name="opportunities_module_access"> </div>
                          </td>
        <?
        }
        else
        {
        ?>
                          <td>
       <div align="center"> <input onclick="disableOthers('opportunities',this.checked)" type="checkbox" name="opportunities_module_access"> </div>
                          </td>
        <?php
        }


	?>

                        </tr>
                        <tr class="oddListRow">
                          <td nowrap="nowrap"><?php echo $mod_strings['LBL_TASKS']; ?></td>

 	  <?php
                        $sql_activities = "select actionname,action_permission,module_permission from role2action inner join role2tab on role2tab.rolename=role2action.rolename and role2tab.tabid=role2action.tabid where role2tab.rolename='".$_REQUEST["rolename"] ."' and role2action.tabid=12";

           $result_activities = $adb->query($sql_activities);
        if(@$adb->query_result($result_activities,1,"action_permission") == 1)
        {
        ?>

                          <td>
     <div align="center"> <input checked type="checkbox" name="activities_create"> </div>
                          </td>
                          <!-- td>
     <div align="center"> <input checked type="checkbox" name="activities_edit"> </div>
                          </td -->
	<?php
	}
       else
        {
          ?>
                          <td>
     <div align="center"> <input  type="checkbox" name="activities_create"> </div>
                          </td>
                          <!-- td>
     <div align="center"> <input  type="checkbox" name="activities_edit"> </div>
                          </td -->
     <?php
        }
	if(@$adb->query_result($result_activities,0,"action_permission") == 1)
        {
        ?>

                          <td>
       <div align="center"> <input checked type="checkbox" name="activities_delete"> </div>
                          </td>
	<?
	}
	else
	{
	?>
                          <td>
       <div align="center"> <input type="checkbox" name="activities_delete"> </div>
                          </td>
        <?php
	}

 if(@$adb->query_result($result_activities,2,"module_permission") == 1)
        {
        ?>

                          <td>
       <div align="center"> <input onclick="disableOthers('activities',this.checked)" checked type="checkbox" name="activities_module_access"> </div>
                          </td>
        <?
        }
        else
        {
        ?>
                          <td>
       <div align="center"> <input onclick="disableOthers('activities',this.checked)" type="checkbox" name="activities_module_access"> </div>
                          </td>
        <?php
        }

	?>

                        </tr>
                        <tr class="evenListRow">
                          <td nowrap="nowrap"><?php echo $mod_strings['LBL_CASES']; ?></td>

 	  <?php
                        $sql_cases = "select actionname,action_permission,module_permission from role2action inner join role2tab on role2tab.rolename=role2action.rolename and role2tab.tabid=role2action.tabid where role2tab.rolename='".$_REQUEST["rolename"] ."' and role2action.tabid=7";

           $result_cases = $adb->query($sql_cases);
        if(@$adb->query_result($result_cases,1,"action_permission") == 1)
        {
        ?>

                          <td>
     <div align="center"> <input checked type="checkbox" name="cases_create"> </div>
                          </td>
                          <!-- td>
     <div align="center"> <input checked type="checkbox" name="cases_edit"> </div>
                          </td -->
	<?php
	}
       else
        {
          ?>
                          <td>
     <div align="center"> <input  type="checkbox" name="cases_create"> </div>
                          </td>
                          <!-- td>
     <div align="center"> <input  type="checkbox" name="cases_edit"> </div>
                          </td -->
     <?php
        }
	if(@$adb->query_result($result_cases,0,"action_permission") == 1)
        {
        ?>

                          <td>
       <div align="center"> <input checked type="checkbox" name="cases_delete"> </div>
                          </td>
	<?
	}
	else
	{
	?>
                          <td>
       <div align="center"> <input type="checkbox" name="cases_delete"> </div>
                          </td>
        <?php
	}

 if(@$adb->query_result($result_cases,2,"module_permission") == 1)
        {
        ?>

                          <td>
       <div align="center"> <input onclick="disableOthers('cases',this.checked)" checked type="checkbox" name="cases_module_access"> </div>
                          </td>
        <?
        }
        else
        {
        ?>
                          <td>
       <div align="center"> <input onclick="disableOthers('cases',this.checked)" type="checkbox" name="cases_module_access"> </div>
                          </td>
        <?php
        }

	?>

                        </tr>
                        <tr class="oddListRow">
                          <td nowrap="nowrap"><?php echo $mod_strings['LBL_EMAILS']; ?></td>

 	  <?php
                        $sql_emails = "select actionname,action_permission,module_permission from role2action inner join role2tab on role2tab.rolename=role2action.rolename and role2tab.tabid=role2action.tabid where role2tab.rolename='".$_REQUEST["rolename"] ."' and role2action.tabid=10";

           $result_emails = $adb->query($sql_emails);
        if(@$adb->query_result($result_emails,1,"action_permission") == 1)
        {
        ?>

                          <td>
     <div align="center"> <input checked type="checkbox" name="emails_create"> </div>
                          </td>
                          <!-- td>
     <div align="center"> <input checked type="checkbox" name="emails_edit"> </div>
                          </td -->
	<?php
	}
       else
        {
          ?>
                          <td>
     <div align="center"> <input  type="checkbox" name="emails_create"> </div>
                          </td>
                          <!-- td>
     <div align="center"> <input  type="checkbox" name="emails_edit"> </div>
                          </td -->
     <?php
        }
	if(@$adb->query_result($result_emails,0,"action_permission") == 1)
        {
        ?>

                          <td>
       <div align="center"> <input checked type="checkbox" name="emails_delete"> </div>
                          </td>
	<?
	}
	else
	{
	?>
                          <td>
       <div align="center"> <input type="checkbox" name="emails_delete"> </div>
                          </td>
        <?php
	}
 if(@$adb->query_result($result_emails,2,"module_permission") == 1)
        {
        ?>

                          <td>
        <div align="center"> <input onclick="disableOthers('emails',this.checked)" checked type="checkbox" name="emails_module_access"> </div>
                          </td>
        <?
        }
        else
        {
        ?>
                          <td>
       <div align="center"> <input onclick="disableOthers('emails',this.checked)" type="checkbox" name="emails_module_access"> </div>
                          </td>
        <?php
        }

	?>

                        </tr>
                        <tr class="evenListRow">
                          <td nowrap="nowrap"><?php echo $mod_strings['LBL_NOTES']; ?></td>

 	  <?php
                        $sql_notes = "select actionname,action_permission,module_permission from role2action inner join role2tab on role2tab.rolename=role2action.rolename and role2tab.tabid=role2action.tabid where role2tab.rolename='".$_REQUEST["rolename"] ."' and role2action.tabid=8";
           $result_notes = $adb->query($sql_notes);
        if(@$adb->query_result($result_notes,1,"action_permission") == 1)
        {
        ?>

                          <td>
     <div align="center"> <input checked type="checkbox" name="notes_create"> </div>
                          </td>
                          <!-- td>
     <div align="center"> <input checked type="checkbox" name="notes_edit"> </div>
                          </td -->
	<?php
	}
       else
        {
          ?>
                          <td>
     <div align="center"> <input  type="checkbox" name="notes_create"> </div>
                          </td>
                          <!-- td>
     <div align="center"> <input  type="checkbox" name="notes_edit"> </div>
                          </td -->
     <?php
        }
	if(@$adb->query_result($result_notes,0,"action_permission") == 1)
        {
        ?>

                          <td>
       <div align="center"> <input checked type="checkbox" name="notes_delete"> </div>
                          </td>
	<?
	}
	else
	{
	?>
                          <td>
       <div align="center"> <input type="checkbox" name="notes_delete"> </div>
                          </td>
        <?php
	}

  if(@$adb->query_result($result_notes,2,"module_permission") == 1)
        {
        ?>

                          <td>
       <div align="center"> <input onclick="disableOthers('notes',this.checked)" checked type="checkbox" name="notes_module_access"> </div>
                          </td>
        <?
        }
        else
        {
        ?>
                          <td>
       <div align="center"> <input onclick="disableOthers('notes',this.checked)" type="checkbox" name="notes_module_access"> </div>
                          </td>
        <?php
        }

	?>

                        </tr>

                        <tr class="oddListRow">
                          <td nowrap="nowrap"><?php echo $mod_strings['LBL_MEETINGS']; ?></td>

 	  <?php
                        $sql_meetings = "select actionname,action_permission,module_permission from role2action inner join role2tab on role2tab.rolename=role2action.rolename and role2tab.tabid=role2action.tabid where role2tab.rolename='".$_REQUEST["rolename"] ."' and role2action.tabid=11";

           $result_meetings = $adb->query($sql_meetings);
        if(@$adb->query_result($result_meetings,1,"action_permission") == 1)
        {
        ?>

                          <td>
     <div align="center"> <input checked type="checkbox" name="meetings_create"> </div>
                          </td>
                          <!-- td>
     <div align="center"> <input checked type="checkbox" name="meetings_edit"> </div>
                          </td -->
	<?php
	}
       else
        {
          ?>
                          <td>
     <div align="center"> <input  type="checkbox" name="meetings_create"> </div>
                          </td>
                          <!-- td>
     <div align="center"> <input  type="checkbox" name="meetings_edit"> </div>
                          </td -->
     <?php
        }
	if(@$adb->query_result($result_meetings,0,"action_permission") == 1)
        {
        ?>

                          <td>
       <div align="center"> <input checked type="checkbox" name="meetings_delete"> </div>
                          </td>
	<?
	}
	else
	{
	?>
                          <td>
       <div align="center"> <input type="checkbox" name="meetings_delete"> </div>
                          </td>
        <?php
	}

 if(@$adb->query_result($result_meetings,2,"module_permission") == 1)
        {
        ?>

                          <td>
       <div align="center"> <input onclick="disableOthers('meetings',this.checked)" checked type="checkbox" name="meetings_module_access"> </div>
                          </td>
        <?
        }
        else
        {
        ?>
                          <td>
       <div align="center"> <input onclick="disableOthers('meetings',this.checked)" type="checkbox" name="meetings_module_access"> </div>
                          </td>
        <?php
        }


	?>

                        </tr>








                        <tr class="oddListRow">
                          <td nowrap="nowrap"><?php echo $mod_strings['LBL_CALLS']; ?></td>

 	  <?php
                        $sql_calls = "select actionname,action_permission,module_permission from role2action inner join role2tab on role2tab.rolename=role2action.rolename and role2tab.tabid=role2action.tabid where role2tab.rolename='".$_REQUEST["rolename"] ."' and role2action.tabid=9";

           $result_calls = $adb->query($sql_calls);
        if(@$adb->query_result($result_calls,1,"action_permission") == 1)
        {
        ?>

                          <td>
     <div align="center"> <input checked type="checkbox" name="calls_create"> </div>
                          </td>
                          <!-- td>
     <div align="center"> <input checked type="checkbox" name="calls_edit"> </div>
                          </td -->
	<?php
	}
       else
        {
          ?>
                          <td>
     <div align="center"> <input  type="checkbox" name="calls_create"> </div>
                          </td>
                          <!-- td>
     <div align="center"> <input  type="checkbox" name="calls_edit"> </div>
                          </td -->
     <?php
        }
	if(@$adb->query_result($result_calls,0,"action_permission") == 1)
        {
        ?>

                          <td>
       <div align="center"> <input checked type="checkbox" name="calls_delete"> </div>
                          </td>
	<?
	}
	else
	{
	?>
                          <td>
       <div align="center"> <input type="checkbox" name="calls_delete"> </div>
                          </td>
        <?php
	}

 if(@$adb->query_result($result_calls,2,"module_permission") == 1)
        {
        ?>

                          <td>
       <div align="center"> <input onclick="disableOthers('calls',this.checked)" checked type="checkbox" name="calls_module_access"> </div>
                          </td>
        <?
        }
        else
        {
        ?>
                          <td>
       <div align="center"> <input onclick="disableOthers('calls',this.checked)" type="checkbox" name="calls_module_access"> </div>
                          </td>
        <?php
        }


	?>

                        </tr>













	</table>
	<br>
	<b><?php //echo $mod_strings['LBL_IMPORT_PERMISSIONS']; ?></b>	
	<table class="FormBorder" cellspacing="0" cellpadding="0" width="100%">

        <th class="formHeader" background="<?php echo $image_path ?>header_tile.gif"  vAlign="middle" align="left" noWrap width="20%" height="22"><b><?php echo $mod_strings['LBL_IMPORT_PERMISSIONS']; ?></b></th>


                        <tr class="moduleListTitle" height="25">
                         <td nowrap="nowrap"><div align="center"><b><?php echo $mod_strings['LBL_IMPORT_LEADS']; ?></b></div></td> 
			 <td nowrap="nowrap"><div align="center"><b><?php echo $mod_strings['LBL_IMPORT_ACCOUNTS']; ?></b></div></td>
			 <td nowrap="nowrap"><div align="center"<b><?php echo $mod_strings['LBL_IMPORT_CONTACTS']; ?></b></div></td>
			 <td nowrap="nowrap"><div align="center"<b><?php echo $mod_strings['LBL_IMPORT_OPPURTUNITIES']; ?></b></div></td>
			</tr>
				
			  
			<tr class="oddListRow">

  <?php
$sql_import_leads = "select actionname,action_permission,module_permission from role2action inner join role2tab on role2tab.rolename=role2action.rolename and role2tab.tabid=role2action.tabid where role2tab.rolename='".$_REQUEST["rolename"] ."' and role2action.tabid=3";
           $result_import_leads = $adb->query($sql_import_leads);
        if(@$adb->query_result($result_import_leads,2,"action_permission") == 1)
        {
        ?>
                           <td>
     <div align="center"> <input checked type="checkbox" name="import_leads"> </div>
                          </td>
	<?php
	}
       else
        {
          ?>
		                  <td>
       <div align="center"> <input type="checkbox" name="import_leads"> </div>
                          </td>

     <?php
        }

	?>

  <?php
$sql_import_accounts = "select actionname,action_permission,module_permission from role2action inner join role2tab on role2tab.rolename=role2action.rolename and role2tab.tabid=role2action.tabid where role2tab.rolename='".$_REQUEST["rolename"] ."' and role2action.tabid=5";
           $result_import_accounts = $adb->query($sql_import_accounts);

	if(@$adb->query_result($result_import_accounts,2,"action_permission") == 1)
        {
        ?>
                   <td>
     <div align="center"> <input checked type="checkbox" name="import_accounts"> </div>
                          </td>
	
	<?
	}
	else
	{
	?>
		                  <td>
       <div align="center"> <input type="checkbox" name="import_accounts"> </div>
                          </td>
        <?php
	}
	?>



  <?php
$sql_import_contacts = "select actionname,action_permission,module_permission from role2action inner join role2tab on role2tab.rolename=role2action.rolename and role2tab.tabid=role2action.tabid where role2tab.rolename='".$_REQUEST["rolename"] ."' and role2action.tabid=4";
           $result_import_contacts = $adb->query($sql_import_contacts);

	if(@$adb->query_result($result_import_contacts,3,"action_permission") == 1)
	{
        ?>
           <td>
     <div align="center"> <input checked type="checkbox" name="import_contacts"> </div>
                          </td>

	<?
	}
	else
	{
	?>

			                  <td>
       <div align="center"> <input type="checkbox" name="import_contacts"> </div>
                          </td>
 
        <?php
	}
	?>





<?php
$sql_import_opportunities = "select actionname,action_permission,module_permission from role2action inner join role2tab on role2tab.rolename=role2action.rolename and role2tab.tabid=role2action.tabid where role2tab.rolename='".$_REQUEST["rolename"] ."' and role2action.tabid=6";
           $result_import_opportunities = $adb->query($sql_import_opportunities);

        if(@$adb->query_result($result_import_opportunities,2,"action_permission") == 1)
        {
        ?>
           <td>
     <div align="center"> <input checked type="checkbox" name="import_opportunities"> </div>
                          </td>

        <?
        }
        else
        {
        ?>

                                          <td>
       <div align="center"> <input type="checkbox" name="import_opportunities"> </div>
                          </td>

        <?php
        }
        ?>





















                       </tbody>
                    </table>
              <br>
		<table align="center">
                  <tr>
                    <td>
                    <div align="center">
 <input type="submit" name="save" value="<?php echo $app_strings['LBL_SAVE_BUTTON_LABEL'] ?>" class="button">
 <input name="cancel" class="button" type="button" value="<?php echo $app_strings['LBL_CANCEL_BUTTON_LABEL'] ?>" onclick="window.history.back()" >
                    </div>
                    </td>
                  </tr>
              </table>
            </form>
</body>
</html>
<script>
function disableOthers(module,state)
{
	objCreate=document.editperm.elements[module+"_create"]
	objEdit=document.editperm.elements[module+"_delete"]
	objCreate.disabled=objEdit.disabled=!state
	if (state==false) objCreate.checked=objEdit.checked=false	
}
function setPerm()
{
	disableOthers('lead',document.editperm.elements["lead_module_access"].checked)
	disableOthers('account',document.editperm.elements["account_module_access"].checked)
	disableOthers('contact',document.editperm.elements["contact_module_access"].checked)
	disableOthers('opportunities',document.editperm.elements["opportunities_module_access"].checked)
	disableOthers('activities',document.editperm.elements["activities_module_access"].checked)
	disableOthers('cases',document.editperm.elements["cases_module_access"].checked)
	disableOthers('emails',document.editperm.elements["emails_module_access"].checked)
	disableOthers('notes',document.editperm.elements["notes_module_access"].checked)
	disableOthers('meetings',document.editperm.elements["meetings_module_access"].checked)
     	disableOthers('calls',document.editperm.elements["calls_module_access"].checked)
}
setPerm()
</script>
