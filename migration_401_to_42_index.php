<?php

$list .= '<br><font color="purple"> Enter Values to Migrate Data from <b><i> vtiger CRM 4_0_1 </i></b> to <b><i> vtiger CRM 4_2 </i></b></font><br><br>';
$list .= '<form name="migration" method="POST" action="migration_401_to_42_makechanges.php">';
$list .= '<table border=0 width="75%">';
$list .= '<tr><td align="right">Host Name : </td><td><input type="text" name="host_name"></td></tr>';
$list .= '<tr><td align="right">MySql Port No : </td><td><input type="text" name="port_no"></td></tr>';
$list .= '<tr><td align="right">MySql User Name : </td><td><input type="text" name="mysql_username"></td></tr>';
$list .= '<tr><td align="right">MySql Password : </td><td><input type="text" name="mysql_password"></td></tr>';
//$list .= '<tr><td align="right">vtiger CRM 4_0_1 Database Name : </td><td><input type="text" name="vtiger401_dbname"></td></tr>';
//$list .= '<tr><td align="right">vtiger CRM 4_2 Database Name : </td><td><input type="text" name="vtiger42_dbname"></td></tr>';
$list .= '<tr><td align="right">vtiger Dump Database Name : </td><td><input type="text" name="vtigerdump_dbname"></td></tr>';
$list .= '<tr><td>&nbsp;</td><td align="right"><input type="submit" name="submit" value="Migrate" onclick="return form_validate(migration)"></td>';
$list .= '<table></form>';

echo $list;


?>
<script>
function form_validate(formname)
{
	if(formname.host_name.value == '')
	{
		alert("Please enter the Host Name");
		return false;
	}
	else if(formname.port_no.value == '')
	{
		alert("Please enter the MySql Port Number");
		return false;
	}
	else if(formname.mysql_username.value == '')
	{
		alert("Please enter the MySql User Name");
		return false;
	}
	else if(formname.mysql_password.value == '')
	{
		alert("Please enter the MySql Password");
		return false;
	}
/*	else if(formname.vtiger401_dbname.value == '')
	{
		alert("Please enter the vtiger CRM 4_0_1 dababase name");
		return false;
	}
	else if(formname.vtiger42_dbname.value == '')
	{
		alert("Please enter the vtiger CRM 4_2 database name");
		return false;
	}
*/	else if(formname.vtigerdump_dbname.value == '')
	{
		alert("Please enter the vtiger CRM 4_0_1 Dump database name");
		return false;
	}
	else
	{
		return true;
	}
}
</script>
