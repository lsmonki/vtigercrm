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

session_start();

$errormsg = '';

if($_REQUEST['fun'] != '' && $_REQUEST['fun'] == 'savepassword')
{
	include("include.php");
	require_once("Tickets/Utils.php");
	require_once("language/en_us.lang.php");
	$errormsg = SavePassword();
}

if($_REQUEST['last_login'] != '')
{
	$last_login = stripslashes($_REQUEST['last_login']);
	$_SESSION['last_login'] = $last_login;
}
elseif($_SESSION['last_login'] != '')
{
	$last_login = $_SESSION['last_login'];
}

if($_REQUEST['support_start_date'] != '')
	$_SESSION['support_start_date'] = $support_start_date = stripslashes($_REQUEST['support_start_date']);
elseif($_SESSION['support_start_date'] != '')
	$support_start_date = $_SESSION['support_start_date'];

if($_REQUEST['support_end_date'] != '')
	$_SESSION['support_end_date'] = $support_end_date = stripslashes($_REQUEST['support_end_date']);
elseif($_SESSION['support_end_date'] != '')
	$support_end_date = $_SESSION['support_end_date'];

?>
<!-- added for popup My Settings -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
   <head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>My Settings</title>
	<link href="css/style.css" rel="stylesheet" type="text/css">
   </head>

   <body>
	<table width="95%"  border="0" cellspacing="0" cellpadding="3" align="center">
	   <form name="savepassword" action="MySettings.php" method="post">
	   <input type="hidden" name="fun" value="savepassword">
	   <tr><td colspan="2"></td></tr>
	   <tr>
		<td height="30" align="left"><b style="text-decoration:underline">My Settings</b></td>
		<td align="right" ><a href="javascript:window.close();">Close</a></td>
	   </tr>
	   <tr><td colspan="2">&nbsp;</td></tr>
	   <tr>
		<td colspan="2" class="detailedViewHeader"><b>My Details</b></td>
	   </tr>
	   <tr>
		<td class="dvtCellLabel" align="right">Last Login :</td>
		<td class="dvtCellInfo"><b><?php echo $last_login; ?></b></td>
	   </tr>
	   <tr>
		<td class="dvtCellLabel" align="right">Support Start Date :</td>
		<td class="dvtCellInfo"><b><?php echo $support_start_date; ?></b></td>
	   </tr>
	   <tr>
		<td class="dvtCellLabel" align="right">Support End Date :</td>
		<td class="dvtCellInfo"><b><?php echo $support_end_date; ?></b></td>
	   </tr>
	   <tr><td colspan="2">&nbsp;</td></tr>
	   <tr><td colspan="2"><?php echo $errormsg; ?></td></tr>
	   <tr>
		<td colspan="2" class="detailedViewHeader"><b>Change Password</b></td>
	   </tr>
	   <tr>
		<td class="dvtCellLabel" align="right">Old Password :</td>
		<td class="dvtCellInfo">
			<input type="password" name="old_password" class="detailedViewTextBox"  onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" value="">
		</td>
	   </tr>
	   <tr>
		<td class="dvtCellLabel" align="right">New Password :</td>
		<td class="dvtCellInfo">
			<input type="password" name="new_password" class="detailedViewTextBox"  onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" value="">
		</td>
	   </tr>
	   <tr>
		<td class="dvtCellLabel" align="right">Confirm Password :</td>
		<td class="dvtCellInfo">
			<input type="password" name="confirm_password" class="detailedViewTextBox"  onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" value="">
		</td>
	   </tr>
	   <tr><td colspan="2" class="dvtCellInfo">&nbsp;</td></tr>
	   <tr>
		<td colspan="2" align="center">
		   <input name="savepassword" type="submit" value="Save" onclick="return verify_data(this.form)">&nbsp;&nbsp;
		   <input name="Close" type="button" value="Close" onClick="window.close();">
		</td>
	   </tr>
	   <tr>
		<td colspan="2">&nbsp;</td>
	   </tr>
	   </form>
	</table>

	<script>
		function verify_data(form)
		{
		        oldpw = trim(form.old_password.value);
		        newpw = trim(form.new_password.value);
		        confirmpw = trim(form.confirm_password.value);
		        if(oldpw == '')
		        {
				alert("Enter Old Password");
		                return false;
		        }
		        else if(newpw == '')
		        {
				alert("Enter New Password");
		                return false;
		        }
		        else if(confirmpw == '')
		        {
				alert("Confirm the New Password");
		                return false;
		        }
		        else
		        {
		                return true;
		        }
		}
		function trim(s)
		{
		        while (s.substring(0,1) == " ")
		        {
		                s = s.substring(1, s.length);
		        }
		        while (s.substring(s.length-1, s.length) == ' ')
		        {
		                s = s.substring(0,s.length-1);
		        }

		        return s;
		}
	</script>
   </body>
</html>


