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
$roleid = $_SESSION['authenticated_user_roleid'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html lang="en">
<head>
  <title>Role Details</title>
</head>
<script type="text/javascript" language="javascript">
function delRole()
{
	document.location.href="index.php?module=Users&action=deleteRole&rolename="+document.editRole.rolename.value
}
</script>
<body>
<!--div class="bodyText" style="margin-top:10">Lead Assignment Rules allow you to automatically route leads, that are added via Import Leads Wizard, to the appropriate users. A Lead Assignment Rule consists of multiple rule entries that define the conditions and order for assigning leads.</div-->
            
            <form action="index.php" name="editRole">
	    <div class="moduleTitle hline">Role Details : <?php echo $_REQUEST["rolename"] ?> </div>
		<br>
            <input type="hidden" name="action" value="editpermissions">
            <input type="hidden" name="module" value="Users">
            <input type="hidden" name="rolename" value="<?php echo $_REQUEST["rolename"] ?>">
            <input type="hidden" name="currentLoggedRole" value="<?php echo $_REQUEST["currentLoggedRole"] ?>">


			<?php
	if(	$_SESSION['authenticated_user_roleid']  != 'administrator')
			{
                        }
			else
			{
				if ($_REQUEST["rolename"]!="administrator" && $_REQUEST["rolename"]!="standard_user")
				{
			?>
   <input type="submit" name="edit" value="Edit" class="button" >
<input type="button" name="delete" value="Delete" class="button" onclick="delRole()">
<br><br>
			<?
				}
			}
                        
		?>
                    <b>Entity Level Permissions</b>
                    <table border="0" width="100%" cellspacing="0" cellpadding="0" class="FormBorder">
                      <tbody>
                        <tr height="25">
                          <td class="moduleListTitle" width="15%">
                          <div align="left"><b>Entity</b></div>
                          </td>
                          <td nowrap="nowrap" class="moduleListTitle">
                          <div align="center"><b>Create/Edit</b></div>
                          </td>
                          <!-- td nowrap="nowrap" class="moduleListTitle">
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
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td>
                          
	<?php
	}
      else
        {
          ?>
                          <td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
                          </td>
                          
       <?php
        }

	if(@mysql_result($result,0,"action_permission") == 1)
        {
	?>
                          <td>
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td>
	<?
	}
	else
	{
	?>
			 <td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
                          </td>
	<?
	}

	if(@mysql_result($result,2,"module_permission") == 1)
        {
	?>
                          <td>
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td>
	<?
	}
	else
	{
	?>
			 <td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
                          </td>
	<?
	}

	?>


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
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td>
                          <!-- td>
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td -->
<?php
}
  else
        {
          ?>
  <td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
                          </td>
                          <!-- td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
                          </td -->

<?php

        }
if(@mysql_result($result_accounts,0,"action_permission") == 1)
{
?>

		  <td>
		  <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
		  </td>
<?
}
else
{
?>
		 <td>
		  <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
		  </td>
 <?
}
if(@mysql_result($result_accounts,2,"module_permission") == 1)
{
?>

		  <td>
		  <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
		  </td>
<?
}
else
{
?>
		 <td>
		  <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
		  </td>
 <?
}

?>

		</tr>
		<tr class="oddListRow">
		  <td nowrap="nowrap">Contacts</td>
 	  <?php
                $sql_contacts = "select actionname,action_permission,module_permission from role2action inner join role2tab on role2tab.rolename=role2action.rolename and role2tab.tabid=role2action.tabid where role2tab.rolename='".$_REQUEST["rolename"] ."' and role2action.tabid=4";
$result_contacts = mysql_query($sql_contacts);
        if(@mysql_result($result_contacts,2,"action_permission") == 1)
        {
        ?>
                          <td>
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td>
                          <!-- td>
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td -->
	<?php
	}
       else
        {
          ?>
  <td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
                          </td>
                          <!-- td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
                          </td -->


     <?php
        }
	if(@mysql_result($result_contacts,1,"action_permission") == 1)
        {
        ?>

                          <td>
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td>
	<?
	}
	else
	{
	?>
		 <td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
                          </td>
        <?php
	}
	if(@mysql_result($result_contacts,2,"module_permission") == 1)
        {
        ?>

                          <td>
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td>
	<?
	}
	else
	{
	?>
		 <td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
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
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td>
                          <!-- td>
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td -->
	<?php
	}
       else
        {
          ?>
  <td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
                          </td>
                          <!-- td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
                          </td -->


     <?php
        }
	if(@mysql_result($result_opportunities,0,"action_permission") == 1)
        {
        ?>

                          <td>
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td>
	<?
	}
	else
	{
	?>
		 <td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
                          </td>
        <?php
	}
    if(@mysql_result($result_opportunities,2,"module_permission") == 1)
        {
        ?>

                          <td>
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td>
        <?
        }
        else
        {
        ?>
                 <td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
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
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td>
                          <!-- td>
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td -->
	<?php
	}
       else
        {
          ?>
  <td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
                          </td>
                          <!-- td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
                          </td -->


     <?php
        }
	if(@mysql_result($result_activities,0,"action_permission") == 1)
        {
        ?>

                          <td>
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td>
	<?
	}
	else
	{
	?>
		 <td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
                          </td>
        <?php
	}

 if(@mysql_result($result_activities,2,"module_permission") == 1)
        {
        ?>

                          <td>
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td>
        <?
        }
        else
        {
        ?>
                 <td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
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
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td>
                          <!-- td>
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td -->
	<?php
	}
       else
        {
          ?>
  <td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
                          </td>
                          <!-- td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
                          </td -->


     <?php
        }
	if(@mysql_result($result_cases,0,"action_permission") == 1)
        {
        ?>

                          <td>
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td>
	<?
	}
	else
	{
	?>
		 <td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
                          </td>
        <?php
	}


  if(@mysql_result($result_cases,2,"module_permission") == 1)
        {
        ?>

                          <td>
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td>
        <?
        }
        else
        {
        ?>
                 <td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
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
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td>
                          <!-- td>
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td -->
	<?php
	}
       else
        {
          ?>
  <td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
                          </td>
                          <!-- td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
                          </td -->


     <?php
        }
	if(@mysql_result($result_emails,0,"action_permission") == 1)
        {
        ?>

                          <td>
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td>
	<?
	}
	else
	{
	?>
		 <td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
                          </td>
        <?php
	}
	 if(@mysql_result($result_emails,2,"module_permission") == 1)
        {
        ?>

                          <td>
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td>
        <?
        }
        else
        {
        ?>
                 <td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
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
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td>
                          <!-- td>
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td -->
	<?php
	}
       else
        {
          ?>
  <td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
                          </td>

     <?php
        }
	if(@mysql_result($result_notes,0,"action_permission") == 1)
        {
        ?>

                          <td>
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td>
	<?
	}
	else
	{
	?>
		 <td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
                          </td>
        <?php
	}
	if(@mysql_result($result_notes,2,"module_permission") == 1)
        {
        ?>

                          <td>
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td>
        <?
        }
        else
        {
        ?>
                 <td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
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
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td>
                          <!-- td>
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td -->
	<?php
	}
       else
        {
          ?>
  <td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
                          </td>
                          <!-- td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
                          </td -->


     <?php
        }
	if(@mysql_result($result_meetings,0,"action_permission") == 1)
        {
        ?>

                          <td>
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td>
	<?
	}
	else
	{
	?>
		 <td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
                          </td>
        <?php
	}


  if(@mysql_result($result_meetings,2,"module_permission") == 1)
        {
        ?>

                          <td>
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td>
        <?
        }
        else
        {
        ?>
                 <td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
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
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td>
                          <!-- td>
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td -->
	<?php
	}
       else
        {
          ?>
  <td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
                          </td>
                          <!-- td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
                          </td -->


     <?php
        }
	if(@mysql_result($result_calls,0,"action_permission") == 1)
        {
        ?>

                          <td>
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td>
	<?
	}
	else
	{
	?>
		 <td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
                          </td>
        <?php
	}


  if(@mysql_result($result_calls,2,"module_permission") == 1)
        {
        ?>

                          <td>
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td>
        <?
        }
        else
        {
        ?>
                 <td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
                          </td>
        <?php
        }

	?>


                        </tr>
       














       

	</table>
	<br>
	<b>Import Permissions:</b>	
	<table class="FormBorder" cellspacing="0" cellpadding="0" width="100%" border="0">

                         <tr class="moduleListTitle" height="25">
                          <td nowrap="nowrap"><div align="center">Import Leads</div></td> 
			  <td nowrap="nowrap"><div align="center">Import Accounts</div></td>
			  <td nowrap="nowrap"><div align="center">Import Contacts</div></td>
			  <td nowrap="nowrap"><div align="center">Import Opportunities</div></td>
			</tr>
			<tr class="oddListRow" height="25">
		

  <?php
$sql_import_leads = "select actionname,action_permission,module_permission from role2action inner join role2tab on role2tab.rolename=role2action.rolename and role2tab.tabid=role2action.tabid where role2tab.rolename='".$_REQUEST["rolename"] ."' and role2action.tabid=3";
           $result_import_leads = mysql_query($sql_import_leads);
        if(@mysql_result($result_import_leads,2,"action_permission") == 1)
        {
        ?>
                          <td>
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td>
	<?php
	}
       else
        {
          ?>
			  <td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
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
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td>
	<?
	}
	else
	{
	?>
		 <td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
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
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td>
	<?
	}
	else
	{
	?>
		 <td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
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
                          <div align="center"> <img src="themes/Aqua/images/yes.gif"> </div>
                          </td>
        <?
        }
        else
        {
        ?>
                 <td>
                          <div align="center"> <img src="themes/Aqua/images/no.gif"> </div>
                          </td>
        <?php
        }
        ?>













                </tbody>
              </table>
</body>
</html>
