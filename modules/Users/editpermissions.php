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

<html lang="en">
<head>
  <title>Role Details</title>
<!--meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"-->
</head>
<body>
<!--c:out value="${locale}"/-->
<!--fmt:setLocale value="ja_JP"/-->
                  <div class="moduleTitle hline">Role Details: <?php echo $_REQUEST["rolename"] ?></div>
                <br>
            <form name="editperm" action="index.php" method="post">
              <input type="hidden" name="action" value="updateRole">
              <input type="hidden" name="rolename" value="<?php echo $_REQUEST["rolename"] ?>"> 
              <input type="hidden" name="module" value="Users">
              <input type="hidden" name="return_module" value="Users">
              <input type="hidden" name="return_action" value="ListPermission">

                    <b>Entity Level Permissions</b>
                    <table border="0" width="100%" cellspacing="0" cellpadding="0" class="FormBorder">
                      <tbody>
                        <tr height="25">
                          <td width="15%" class="moduleListTitle">
                          <div align="left"><b>Entity</b></div>
                          </td>
                          <td nowrap="nowrap" class="moduleListTitle">
                          <div align="center"><b>Create/Edit</b></div>
                          </td>
                          <!--td nowrap="nowrap" class="bodyText bold">
                          <div align="center">Edit</div>
                          </td -->
                          <td nowrap="nowrap" class="moduleListTitle">
                          <div align="center"><b>Delete</b></div>
                          </td>
                          <td nowrap="nowrap" class="moduleListTitle">
                          <div align="center"><b>Allow</b></div>
                          </td>
                        </tr>
                        <tr class="oddListRow">
                         <td nowrap="nowrap">Leads</td>
	<?php

$sql = "select actionname,action_permission,module_permission from role2action inner join role2tab on role2tab.rolename=role2action.rolename and role2tab.tabid=role2action.tabid where role2tab.rolename='".$_REQUEST["rolename"] ."' and role2action.tabid=3";


         $result = mysql_query($sql);
	if(@mysql_result($result,1,"action_permission") == 1)
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

	if(@mysql_result($result,0,"action_permission") == 1)
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

 if(@mysql_result($result,2,"module_permission") == 1)
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
                          <td nowrap="nowrap">Accounts</td>

<?php
                        $sql_accounts = "select actionname,action_permission,module_permission from role2action inner join role2tab on role2tab.rolename=role2action.rolename and role2tab.tabid=role2action.tabid where role2tab.rolename='".$_REQUEST["rolename"] ."' and role2action.tabid=5";

            $result_accounts = mysql_query($sql_accounts);
        if(@mysql_result($result_accounts,1,"action_permission") == 1)
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
if(@mysql_result($result_accounts,0,"action_permission") == 1)
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




if(@mysql_result($result_accounts,2,"module_permission") == 1)
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
                          <td nowrap="nowrap">Contacts</td>

 	  <?php
                        $sql_contacts = "select actionname,action_permission,module_permission from role2action inner join role2tab on role2tab.rolename=role2action.rolename and role2tab.tabid=role2action.tabid where role2tab.rolename='".$_REQUEST["rolename"] ."' and role2action.tabid=4";
$result_contacts = mysql_query($sql_contacts);
        if(@mysql_result($result_contacts,2,"action_permission") == 1)
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
	if(@mysql_result($result_contacts,1,"action_permission") == 1)
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


 if(@mysql_result($result_contacts,2,"module_permission") == 1)
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
                          <td nowrap="nowrap">Opportunities</td>

 	  <?php
                        $sql_opportunities = "select actionname,action_permission,module_permission from role2action inner join role2tab on role2tab.rolename=role2action.rolename and role2tab.tabid=role2action.tabid where role2tab.rolename='".$_REQUEST["rolename"] ."' and role2action.tabid=6";
$result_opportunities = mysql_query($sql_opportunities);
        if(@mysql_result($result_opportunities,1,"action_permission") == 1)
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
	if(@mysql_result($result_opportunities,0,"action_permission") == 1)
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


if(@mysql_result($result_opportunities,2,"module_permission") == 1)
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
                          <td nowrap="nowrap">Tasks</td>

 	  <?php
                        $sql_activities = "select actionname,action_permission,module_permission from role2action inner join role2tab on role2tab.rolename=role2action.rolename and role2tab.tabid=role2action.tabid where role2tab.rolename='".$_REQUEST["rolename"] ."' and role2action.tabid=12";

           $result_activities = mysql_query($sql_activities);
        if(@mysql_result($result_activities,1,"action_permission") == 1)
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
	if(@mysql_result($result_activities,0,"action_permission") == 1)
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

 if(@mysql_result($result_activities,2,"module_permission") == 1)
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
                          <td nowrap="nowrap">Cases</td>

 	  <?php
                        $sql_cases = "select actionname,action_permission,module_permission from role2action inner join role2tab on role2tab.rolename=role2action.rolename and role2tab.tabid=role2action.tabid where role2tab.rolename='".$_REQUEST["rolename"] ."' and role2action.tabid=7";

           $result_cases = mysql_query($sql_cases);
        if(@mysql_result($result_cases,1,"action_permission") == 1)
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
	if(@mysql_result($result_cases,0,"action_permission") == 1)
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

 if(@mysql_result($result_cases,2,"module_permission") == 1)
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
                          <td nowrap="nowrap">Emails</td>

 	  <?php
                        $sql_emails = "select actionname,action_permission,module_permission from role2action inner join role2tab on role2tab.rolename=role2action.rolename and role2tab.tabid=role2action.tabid where role2tab.rolename='".$_REQUEST["rolename"] ."' and role2action.tabid=10";

           $result_emails = mysql_query($sql_emails);
        if(@mysql_result($result_emails,1,"action_permission") == 1)
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
	if(@mysql_result($result_emails,0,"action_permission") == 1)
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
 if(@mysql_result($result_emails,2,"module_permission") == 1)
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
                          <td nowrap="nowrap">Notes</td>

 	  <?php
                        $sql_notes = "select actionname,action_permission,module_permission from role2action inner join role2tab on role2tab.rolename=role2action.rolename and role2tab.tabid=role2action.tabid where role2tab.rolename='".$_REQUEST["rolename"] ."' and role2action.tabid=8";
           $result_notes = mysql_query($sql_notes);
        if(@mysql_result($result_notes,1,"action_permission") == 1)
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
	if(@mysql_result($result_notes,0,"action_permission") == 1)
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

  if(@mysql_result($result_notes,2,"module_permission") == 1)
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
                          <td nowrap="nowrap">Meetings</td>

 	  <?php
                        $sql_meetings = "select actionname,action_permission,module_permission from role2action inner join role2tab on role2tab.rolename=role2action.rolename and role2tab.tabid=role2action.tabid where role2tab.rolename='".$_REQUEST["rolename"] ."' and role2action.tabid=11";

           $result_meetings = mysql_query($sql_meetings);
        if(@mysql_result($result_meetings,1,"action_permission") == 1)
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
	if(@mysql_result($result_meetings,0,"action_permission") == 1)
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

 if(@mysql_result($result_meetings,2,"module_permission") == 1)
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
                          <td nowrap="nowrap">Calls</td>

 	  <?php
                        $sql_calls = "select actionname,action_permission,module_permission from role2action inner join role2tab on role2tab.rolename=role2action.rolename and role2tab.tabid=role2action.tabid where role2tab.rolename='".$_REQUEST["rolename"] ."' and role2action.tabid=9";

           $result_calls = mysql_query($sql_calls);
        if(@mysql_result($result_calls,1,"action_permission") == 1)
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
	if(@mysql_result($result_calls,0,"action_permission") == 1)
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

 if(@mysql_result($result_calls,2,"module_permission") == 1)
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
	<b>Import Permissions</b>	
	<table class="FormBorder" cellspacing="0" cellpadding="0" width="100%">

                        <tr class="moduleListTitle" height="25">
                         <td nowrap="nowrap"><div align="center"><b>Import Leads</b></div></td> 
			 <td nowrap="nowrap"><div align="center"><b>Import Accounts</b></div></td>
			 <td nowrap="nowrap"><div align="center"<b>Import Contacts</b></div></td>
			 <td nowrap="nowrap"><div align="center"<b>Import Opportunities</b></div></td>
			</tr>
				
			  
			<tr class="oddListRow">

  <?php
$sql_import_leads = "select actionname,action_permission,module_permission from role2action inner join role2tab on role2tab.rolename=role2action.rolename and role2tab.tabid=role2action.tabid where role2tab.rolename='".$_REQUEST["rolename"] ."' and role2action.tabid=3";
           $result_import_leads = mysql_query($sql_import_leads);
        if(@mysql_result($result_import_leads,2,"action_permission") == 1)
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
           $result_import_accounts = mysql_query($sql_import_accounts);

	if(@mysql_result($result_import_accounts,2,"action_permission") == 1)
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
           $result_import_contacts = mysql_query($sql_import_contacts);

	if(@mysql_result($result_import_contacts,3,"action_permission") == 1)
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
           $result_import_opportunities = mysql_query($sql_import_opportunities);

        if(@mysql_result($result_import_opportunities,2,"action_permission") == 1)
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
 <input type="submit" name="save" value="Save" class="button">
 <input name="cancel" class="button" type="button" value="Cancel" onclick="window.history.back()" >
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
