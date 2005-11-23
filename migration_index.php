<?php

//Give the latest version value based on the file you have included for migration which is the index of the list of old versions. 
//For ex. if give as 3 then the file included in migration_check.php will be like migration/migration_0_to_3.php

$latest_version = '3';

?>

<br><font color="purple"> Enter Values to Migrate Data from <b><i> vtiger CRM 4_2 </i></b> to <b><i> vtiger CRM 4_5  (Alpha)</i></b></font><br><br>
<form name="migration" method="POST" action="migration_check.php">
<input type="hidden" name="latest_version" value="<?php echo $latest_version; ?>">
<table border=0 width="80%">
	<tr>
		<td align="right"><b><font color="green"> Vtiger CRM Existing Version</font></b></td>
		<td>
			<select name="old_version">
				<OPTION value="0">4.2 Patch 2</OPTION>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">4.2 Host Name : </td>
		<td><input type="text" name="old_host_name" value="<?php echo $_REQUEST['old_host_name']?>"></td>
	</tr>
	<tr>
		<td align="right">4.2 MySql Port No : </td>
		<td><input type="text" name="old_port_no" value="<?php echo $_REQUEST['old_port_no']?>"></td>
	</tr>
	<tr>
		<td align="right">4.2 MySql User Name : </td>
		<td><input type="text" name="old_mysql_username" value="<?php echo $_REQUEST['old_mysql_username']?>"></td>
	</tr>
	<tr>
		<td align="right">4.2 MySql Password : </td>
		<td><input type="text" name="old_mysql_password" value="<?php echo $_REQUEST['old_mysql_password']?>"></td>
	</tr>
	<tr>
		<td align="right">4.2 Database Name : </td>
		<td><input type="text" name="old_dbname" value="<?php echo $_REQUEST['old_dbname']?>"></td>
	</tr>
	<tr>
		<td align="center" colspan=2>
			<input type="submit" name="submit" value="Update to Latest Version" onclick="return form_validate(migration)">
		</td>
	</tr>
<table>
</form>

<script type='text/javascript' language='JavaScript'>
function form_validate(formname)
{
	if(formname.old_host_name.value == '')
	{
		error_msg = "Please enter the 4.2 Host Name";
		error = true;
	}
	else if(formname.old_port_no.value == '')
	{
		error_msg = "Please enter the 4.2 MySql Port Number";
		error = true;
	}
	else if(formname.old_mysql_username.value == '')
	{
		error_msg = "Please enter the 4.2 MySql User Name";
		error = true;
	}
/*
	else if(formname.old_mysql_password.value == '')
	{
		error_msg = "Please enter the 4.2 MySql Password";
		error = true;
	}
*/
	else if(formname.old_dbname.value == '')
        {
                error_msg = "Please enter the 4.2 Database Name";
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
