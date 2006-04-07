<?php
/*********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
 ********************************************************************************/

require_once("modules/Migration/language/en_us.lang.php");
global $mod_strings;

//To get the Current installed MySQL path
include("connection.php");
$vtiger_home = $_ENV["VTIGER_HOME"];
$mysqldir = $mysql_dir;

if(is_file($mysqldir."/bin/mysqldump"))
{
	$installed_mysql_path = $mysqldir."/bin/mysqldump";
	$getmysqlpath = false;
}
elseif(is_file($vtiger_home."/mysql/bin/mysqldump"))
{
	$installed_mysql_path = $vtiger_home."/mysql/bin/mysqldump";
	$getmysqlpath = false;
}
elseif(substr($_ENV["OS"],0,3) == "Win")
{
	if(is_file($vtiger_home.'\mysql\bin\mysql.exe'))
	{
		$installed_mysql_path = $vtiger_home.'\mysql\bin\mysqldump.exe';
		@session_unregister('windows_mysql_path');
		$_SESSION['windows_mysql_path'] = $vtiger_home.'\mysql\bin';
		$getmysqlpath = false;
	}
	else
	{
		$getmysqlpath = true;
	}
}
else
{
	$getmysqlpath = true;
}

//The control will come here if getparams will be set from Step2
if($getOldParams == 1)
{
	$installed_mysql_path = trim($current_mysqlpath."/")."/bin/mysqldump";
	$getmysqlpath = false;
}


//Get the MySQL path value if it couldnot retrieved by ENV variable or mysql_dir from connection.php
if($getmysqlpath == true) 
{

?>

   <br><br>
   <form name="current_migration" method="POST" action="index.php">
   <input type="hidden" name="module" value="Migration">
   <input type="hidden" name="action" value="MigrationStep2">
   <input type="hidden" name="pre_action" value="MigrationStep1">
   <table border=10 width="80%" cellpadding="5" cellspacing="5" bgcolor="#D1DFEF">
	<th colspan=3>
         <font color="#3535A2"><?php echo $mod_strings['LBL_MIGRATE_INFO']; ?></font>
	</th>
	<tr>
		<!-- td nowrap>
		    vtiger 4.5 Type 
			<select name="4.5type_install">
			        <OPTION name="Install" value="install"> Install </OPTION>
			        <OPTION name="Source" value="source"> Source </OPTION>
			</select>
		</td -->
		<td width="40%">
		   <?php echo $mod_strings['LBL_CURRENT_VT_MYSQL_EXIST']; ?> 
		   <?php if($_REQUEST['current_mysql_install'] == 'different') $different = 'selected'; else $same = 'selected';?>
		   <select name="current_mysql_install">
               	      <OPTION name="This machine" value="same" <?php echo $same;?> ><?php echo $mod_strings['LBL_THIS_MACHINE'];?></OPTION>
                      <OPTION name="Different machine" value="different" <?php echo $different; ?> ><?php echo $mod_strings['LBL_DIFFERENT_MACHINE'];?></OPTION>
                   </select>
		</td>
		<!-- This text will be displayed if current_mysql_install is same. else get the mysql dump file -->
		<td width="60%">
			<?php 
				if($_REQUEST['old_dump_filename'] != '')
				{
					$dumpstyle = "display:block";	$dumpcheck = 'checked';
					$pathstyle = "display:none";	$pathcheck = '';
				}
				else//if($_REQUEST['current_mysqlpath'] != '') 
				{
					$pathstyle = "display:block";	$pathcheck = 'checked';
					$dumpstyle = "display:none";	$dumpcheck = '';
				}
			?>
			<!-- table to display the radio buttons and the related text fields starts here -->
			<table border=0 cellspacing=0 cellpadding=0>
			   <tr>
			      <td width="40%">
				Current MySQL Path <input type="radio" name="mysql" value="path" <?php echo $pathcheck; ?> onclick="toggleAssignType(this.value)">
				<br>
				Source  MySQL Dump <input type="radio" name="mysql" value="dump" <?php echo $dumpcheck; ?> onclick="toggleAssignType(this.value)">
			      </td>
			      <td width="60%">
			   	<div name="pathdisplay" id="path" style="<?php echo $pathstyle?>">
				   <?php echo $mod_strings['LBL_CURRENT_VT_MYSQL_PATH']; ?>
				   <input type="text" name="current_mysqlpath" value="<?php echo $_REQUEST['current_mysqlpath']; ?>">
			   	</div>
			   	<div name="dumpdisplay" id="dump" style="<?php echo $dumpstyle?>">
				   <?php echo $mod_strings['LBL_SOURCE_VT_MYSQL_DUMPFILE']; ?>
				   <input type="text" name="old_dump_filename" value="<?php echo $_REQUEST['old_dump_filename'];?>">
			   	</div>
			      </td>
		        </tr>
			</table>
			<!-- table to display the radio buttons and the related text fields ends here -->
		</td>
	</tr>
	<tr>
		<td align="center" colspan=3>
			<input type="submit" name="submit" value="Continue" onclick="return form_validate_new(current_migration)">
		</td>
	</tr>
   <table>
	<br> <?php echo $mod_strings['LBL_NOTE_TITLE']; ?>
	   <ol>
		<li><?php echo $mod_strings['LBL_NOTES_LIST1']; ?></li>
		<li><?php echo $mod_strings['LBL_NOTES_LIST2']; ?></li>
		<li><?php echo $mod_strings['LBL_NOTES_DUMP_PROCESS']; ?></li>
		<li><?php echo $mod_strings['LBL_NOTES_LIST3']; ?></b></li>
		<li><?php echo $mod_strings['LBL_NOTES_LIST4']; ?></b></li>
	   </ol>
   </form>
   <!-- Extra table tag is closed as to move the footer at the end of the page -->
   </table>
   <script type='text/javascript' language='JavaScript'>
   function toggleAssignType(val)
   {
        if (val=="path")
        {
                getObj("path").style.display="block";
                getObj("dump").style.display="none";
        }
        else
        {
                getObj("path").style.display="none";
                getObj("dump").style.display="block";
        }
   }
   function form_validate_new(form)
   {
	if(form.current_mysql_install.value == 'same')
	{
		if(form.current_mysqlpath.value == '' && form.old_dump_filename.value == '')
		{
			alert("Please enter the Current MySQL Path or Enter the Source Dump file name.");
			return false;
		}
	}
	else if(form.current_mysql_install.value == 'different')
	{
		if(form.old_dump_filename.value == '')
		{
			alert('Please enter the Source Dump file name.')
			return false;
		}
	}
	return true;
   }
   </script>

<?php
}
else
{
	//include("modules/Migration/MigrationStep2.php");
	if($_REQUEST['old_host_name'] == '')
	{
		echo '<br>'.$mod_strings['LBL_CURRENT_MYSQL_PATH_FOUND'];
	}
	?>
   <br><br>
   <form name="old_migration" method="POST" action="index.php">
   <input type="hidden" name="module" value="Migration">
   <input type="hidden" name="action" value="MigrationCheck">
   <table border=10 width="80%" cellpadding="5" cellspacing="5" bgcolor="#D1DFEF">
      <th colspan=2>
         <font color="#3535A2"><?php echo $mod_strings['LBL_MIGRATE_INFO'];?></font>
      </th>
	<!-- tr>
		<td align="right">vtiger 4.2 Exist in </td>
		<td>
			<?php //if($_REQUEST['machine_4_2'] == 'different') $different = 'selected'; else $same = 'selected';?>
			<select name="machine_4_2">
                                <OPTION name="This machine" value="same" <?php echo $same; ?> > This Machine </OPTION>
                                <OPTION name="Different machine" value="different" <?php echo $different; ?> > Different Machine </OPTION>
                        </select>
		</td>
		<td>vtiger 4.2 MySQL Exist in 
			<select name="mysqlinstall">
                                <OPTION name="This machine" value="same"> This Machine </OPTION>
                                <OPTION name="Different machine" value="different"> Different Machine </OPTION>
                        </select>
		</td>
	</tr -->
	<tr>
		<td align="right"><?php echo $mod_strings['LBL_SOURCE_HOST_NAME'];?></td>
		<td><input type="text" name="old_host_name" value="<?php echo $_REQUEST['old_host_name']?>"></td>
	</tr>
	<tr>
		<td align="right"><?php echo $mod_strings['LBL_SOURCE_MYSQL_PORT_NO'];?></td>
		<td><input type="text" name="old_port_no" value="<?php echo $_REQUEST['old_port_no']?>"></td>
	</tr>
	<tr>
		<td align="right"><?php echo $mod_strings['LBL_SOURCE_MYSQL_USER_NAME'];?></td>
		<td><input type="text" name="old_mysql_username" value="<?php echo $_REQUEST['old_mysql_username']?>"></td>
	</tr>
	<tr>
		<td align="right"><?php echo $mod_strings['LBL_SOURCE_MYSQL_PASSWORD'];?></td>
		<td><input type="password" name="old_mysql_password" value="<?php echo $_REQUEST['old_mysql_password']?>"></td>
	</tr>
	<tr>
		<td align="right"><?php echo $mod_strings['LBL_SOURCE_DB_NAME'];?></td>
		<td><input type="text" name="old_dbname" value="<?php echo $_REQUEST['old_dbname']?>"></td>
	</tr>
	<tr>
		<td align="center" colspan=2>
			<input type="submit" name="submit" value="<?php echo $mod_strings['LBL_MIGRATE'];?>" onclick="return form_validate_old(old_migration)">
		</td>
	</tr>
   <table>
   </form>
   <!-- Extra table tag is closed as to move the footer at the end of the page -->
   </table>
   <script type='text/javascript' language='JavaScript'>
   function form_validate_old(formname)
   {
	if(formname.old_host_name.value == '')
	{
		error_msg = "Please enter the Source Host Name";
		error = true;
	}
	else if(formname.old_port_no.value == '')
	{
		error_msg = "Please enter the Source MySql Port Number";
		error = true;
	}
	else if(formname.old_mysql_username.value == '')
	{
		error_msg = "Please enter the Source MySql User Name";
		error = true;
	}
	/*
	else if(formname.old_mysql_password.value == '')
	{
		error_msg = "Please enter the Source MySql Password";
		error = true;
	}
	*/
	else if(formname.old_dbname.value == '')
        {
                error_msg = "Please enter the Source Database Name";
                error = true;
        }
	else
	{
		error = false;
	}


	if(error == true)
	{
		alert(error_msg);
		return false;
	}
	else
	{
		return true;
	}
   }
   </script>

<?php


}








?>
