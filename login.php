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

require_once("language/en_us.lang.php");

@session_start();
if(isset($_SESSION['customer_id']) && isset($_SESSION['customer_name']))
{
	header("Location: index.php?action=index&module=Tickets");
	exit;
}
if($_REQUEST['close_window'] == 'true')
{
   ?>
	<script>
        	window.close();
	</script>
   <?php
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>vtiger CRM 5 - CustomerPortal</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="css/style.css" rel="stylesheet" type="text/css">
</head>

<body>
	<table cellspacing="0" cellpadding="0" class="outerTab">
	   <tr>
		<td width="15%"><br><br><br></td>
		<td width="70%">&nbsp;</td>
		<td width="15%">&nbsp;</td>
	   </tr>
	   <tr>
		<td>&nbsp;</td>
		<td>
			<table class="innerTab"  cellspacing="0" cellpadding="0">
			   <tr>
				<th align="left"><img src="images/loginVtigerCRM.gif" width="169" height="49"></th>
				<th>&nbsp;</th>
				<th align="right">&nbsp;</th>
			   </tr>
			   <tr class="tableTop"><td colspan="3"></td></tr>
			   <tr>
				<td colspan="3" class="tableMidone">
					<table class="loginTab"  cellspacing="0" cellpadding="0" align="center">
					   <tr>
						<td width="6" height="5"><img src="images/loginSITopLeft.gif"></td>
						<td bgcolor="#FFFFFF"></td>
						<td width="6" height="5"><img src="images/loginSITopRight.gif"></td>
					   </tr>
					   <tr bgcolor="#FFFFFF">
						<td height="150">&nbsp;</td>
						<td valign="top">
							<table width="100%"  border="0" cellspacing="0" cellpadding="3">
							<form name="login" action="CustomerAuthenticate.php" method="post">
							   <tr>
								<?php
								   //Display the login error message 
								   if($_REQUEST['login_error'] != '')
									echo base64_decode($_REQUEST['login_error']); 
								?>
							   </tr>
							   <tr>
								<td colspan="2" class="detailedViewHeader"><b>Customer Portal</b></td>
							   </tr>
							   <tr>
								<td class="dvtCellLabel"  align="right" width="50%">Email ID : </td>
								<td class="dvtCellInfo"><input type="text" name="username" class="detailedViewTextBox"></td>
							   </tr>
							   <tr>
								<td class="dvtCellLabel" align="right">Password :</td>
								<td class="dvtCellInfo"><input type="password" name="pw" class="detailedViewTextBox"></td>
							   </tr>
							   <tr>
								<td>&nbsp;</td>
								<td align="right"><a href='javascript:;' onclick='window.open("supportpage.php?param=forgot_password","ForgotPassword","width=400,height=250");'><?php  echo $mod_strings['LBL_FORGOT_LOGIN']?></a></td>
							   </tr>
							   <tr>
								<td colspan="2" align="center"><input type="image" src="images/loginBtnSignin.gif"></td>
							   </tr>
							   <tr>
								<td class="dvtCellInfo" colspan="2"></td>
							   </tr>
							</table>
						</td>
						<td>&nbsp;</td>
					   </tr>
					   <tr>
						<td width="6" height="6"><img src="images/loginSIBottomLeft.gif"></td>
						<td bgcolor="#FFFFFF"></td>
						<td width="6" height="6"><img src="images/loginSIBottomRight.gif"></td>
					   </tr>
					</table>
					</form>
				</td>
			   </tr>
			  <tr>
			    <td colspan="3" class="tableBtm">&nbsp;</td>
		      </tr>
			</table>

		</td>
		<td>&nbsp;</td>
	   </tr>
	   <tr>
		<td>&nbsp;</td>
		<td align="left"><img src="images/loginBottomURL.gif" width="100" height="21"></td>
		<td>&nbsp;</td>
	   </tr>
	   <!-- <tr>
		<td>&nbsp;</td>
		<td align="center">© Click <a href="javascript:mypopup()">here</a> for Copyright details.</td>
		<td>&nbsp;</td>
	   </tr -->
	</table>

</body>
</html>

<?php
?>
