<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/install/3confirmConfig.php,v 1.14 2005/04/25 09:41:26 samk Exp $
 * Description:  Executes a step in the installation process.
 ********************************************************************************/

if (isset($_REQUEST['db_hostname'])) $db_hostname= $_REQUEST['db_hostname'];
if (isset($_REQUEST['db_username'])) $db_username= $_REQUEST['db_username'];
if (isset($_REQUEST['db_password'])) $db_password= $_REQUEST['db_password'];
if (isset($_REQUEST['db_name'])) $db_name= $_REQUEST['db_name'];
if (isset($_REQUEST['db_drop_tables'])) $db_drop_tables = $_REQUEST['db_drop_tables'];
if (isset($_REQUEST['site_URL'])) $site_URL= $_REQUEST['site_URL'];
if (isset($_REQUEST['admin_email'])) $admin_email= $_REQUEST['admin_email'];
if (isset($_REQUEST['admin_password'])) $admin_password = $_REQUEST['admin_password'];
if (isset($_REQUEST['cache_dir'])) $cache_dir= $_REQUEST['cache_dir'];
if (isset($_REQUEST['mail_server'])) $mail_server= $_REQUEST['mail_server'];
if (isset($_REQUEST['mail_server_username'])) $mail_server_username= $_REQUEST['mail_server_username'];
if (isset($_REQUEST['mail_server_password'])) $mail_server_password= $_REQUEST['mail_server_password'];
if (isset($_REQUEST['root_directory'])) $root_directory = $_REQUEST['root_directory'];
if (isset($_REQUEST['ftpserver'])) $ftpserver= $_REQUEST['ftpserver'];
if (isset($_REQUEST['ftpuser'])) $ftpuser = $_REQUEST['ftpuser'];
if (isset($_REQUEST['ftppassword'])) $ftppassword= $_REQUEST['ftppassword'];
if (isset($_REQUEST['dbtype'])) $dbtype	= $_REQUEST['dbtype'];

//Checking for mysql connection parameters

$mysql_status = '';
$mysql_db_status = '';
if($dbtype != 'mysql' || $dbtype =='')
{
	$mysql_status = 'false';
	$mysql_db_status = 'false';
}
else
{
	$mysql_status = 'true';
	$conn = @mysql_pconnect($db_hostname,$db_username,$db_password);
	if(!$conn)
	{
		$mysqlconn_status = 'false';
	}else
	{
		$mysqlconn_status = 'true';
		$version = explode('-',mysql_get_server_info($conn));
		$mysql_server_version=$version[0];
		if($_REQUEST['check_createdb'] == 'on')
		{
			$root_user = $_REQUEST['root_user'];
			$root_password = $_REQUEST['root_password'];
			$create_conn = @mysql_connect($db_hostname,$root_user,$root_password);
			if(mysql_select_db($db_name,$create_conn))
				@mysql_drop_db($db_name,$create_conn);
			$dbstatus = @mysql_create_db($db_name,$create_conn);
			if(!$dbstatus)
			{
				$mysql_createddb_status = 'false';
			}
			else	
			{
				$mysql_db_status = 'true';
				$mysql_createddb_status = 'true';
			}
			@mysql_close($create_conn);	
		}else
		{	
			if(mysql_select_db($db_name,$conn))
			{
				$mysql_db_status = 'true';
			}else
			{
				$mysql_db_status = 'false';
			}
		}
	}
}

if($mysql_status == 'true' && $mysqlconn_status == 'false')
{
?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<HTML>
	<HEAD>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
	<title>vtiger CRM 5.0 beta Installer: Step 3</title>
	<link rel="stylesheet" href="install/install.css" type="text/css" />
	</head>
	<body leftMargin="0" topMargin="0" marginheight="0" marginwidth="0">


	<!-- Master table -->
	<table border=0 cellspacing=0 cellpadding=0 width=100%>
	<tr>
	<td align=center>
	<br><br>
	<!--  Top Header -->
	<table border="0" cellspacing="0" cellpadding="0" width="80%" style="background:url(install/images/cwTopBg.gif) repeat-x;">
	<tr>
	<td><img src="install/images/cwTopLeft.gif" alt="vtiger CRM" title="vtiger CRM"></td>
	<td align=right><img src="install/images/cwTopRight.gif" alt="v5alpha4" title="v5alpha4"></td>
	</tr>
	</table>

	<!-- 3 of 5 header -->
	<table border="0" cellspacing="0" cellpadding="5" width="75%" class=small> 
	<tr>	
	<td valign=top><img src="install/images/cwIcoSystem.gif" alt="Confirm Configuration" title="Confirm Configuration"></td>
	<td width=98% valign=top>
	<table border=0 cellspacing=0 cellpadding=0 width=100%>
	<tr>
	<td><img src="install/images/cwHdrVtConfWiz.gif" alt="vtiger CRM Configuration Wizard" title="vtiger CRM Configuration Wizard"></td>
	<td align=right><img src="install/images/cwStep3of5.gif" alt="Step 3 of 5" title="Step 3 of 5"></td>
	</tr>
	<tr>
	<td colspan=2><img src="install/images/cwHdrCnfSysConf.gif" alt="Confirm Configuration" title="Confirm Configuration"></td>
	</tr>
	</table>
	<hr noshade size=1>
	</td>

	</tr>
	<tr>
	<td></td>
	<td valign="top" align=center>
	<!-- ---------------------------------------------- System Configuration -- -->

	<table border=0 cellspacing=0 cellpadding=10 width=90% class=small >
	<tr>
	<td>
	<!-- Error Messages -->
	<b><span style="background-color:#ff0000;padding:5px;color:#ffffff;">Unable to connect to database Server. Invalid mySQL Connection Parameters specified</span></b><br><br>
	This may be due to the following reasons:<br>
	-  specified database user, password , hostname or port is invalid.<BR>
	-  specified database user does not have access to connect to the database server from the host


	<br><br>
	<table width="100%" cellpadding="5" border="0" style="background-color:#cccccc" cellspacing="1" class=small>
	<tr>
	<td colspan=2><strong>Database Configuration, as provided by you</strong></td>
	</tr>
	<tr>
	<td bgcolor="#F5F5F5" width="40%">Host Name</td>
	<td bgcolor="White" align="left" nowrap><font class="dataInput"><?php if (isset($db_hostname)) echo "$db_hostname"; ?></font></td>
	</tr>
	<tr>
	<td bgcolor="#F5F5F5" width="40%">User Name</td>
	<td bgcolor="White" align="left" nowrap><font class="dataInput"><?php if (isset($db_username)) echo "$db_username"; ?></font></td>
	</tr>
	<tr>
	<td noWrap bgcolor="#F5F5F5" width="40%">Password</td>
	<td bgcolor="White" align="left" nowrap><font class="dataInput"><?php if (isset($db_password)) echo ereg_replace('.', '*', $db_password); ?></font></td>
	</tr>
	<tr>
	<td noWrap bgcolor="#F5F5F5" width="40%">Database Name</td>
	<td bgcolor="White" align="left" nowrap> <font class="dataInput"><?php if (isset($db_name)) echo "$db_name"; ?></font></td>
	</tr>
	</table>

	<!-- ---------------------------------------------- System Configuration -- -->

	</td>
	</tr>
	</table>
	<br>
	<table border=0 cellspacing=0 cellpadding=10 width=100%>
	<tr>
	<td align=center>
	<form action="install.php" method="post" name="form" id="form">
	<input type="hidden" name="file" value="2setConfig.php">
	<input type="hidden" class="dataInput" name="db_hostname" value="<?php if (isset($db_hostname)) echo "$db_hostname"; ?>" />
	<input type="hidden" class="dataInput" name="db_username" value="<?php if (isset($db_username)) echo "$db_username"; ?>" />
	<input type="hidden" class="dataInput" name="db_password" value="<?php if (isset($db_password)) echo "$db_password"; ?>" />
	<input type="hidden" class="dataInput" name="db_name" value="<?php if (isset($db_name)) echo "$db_name"; ?>" />
	<input type="hidden" class="dataInput" name="db_drop_tables" value="<?php if (isset($db_drop_tables)) echo "$db_drop_tables"; ?>" />
	<input type="hidden" class="dataInput" name="site_URL" value="<?php if (isset($site_URL)) echo "$site_URL"; ?>" />
	<input type="hidden" class="dataInput" name="root_directory" value="<?php if (isset($root_directory)) echo "$root_directory"; ?>" />
	<input type="hidden" class="dataInput" name="admin_email" value="<?php if (isset($admin_email)) echo "$admin_email"; ?>" />
	<input type="hidden" class="dataInput" name="admin_password" value="<?php if (isset($admin_password)) echo "$admin_password"; ?>" />
	<input type="hidden" class="dataInput" name="cache_dir" value="<?php if (isset($cache_dir)) echo $cache_dir; ?>" />
	<input type="hidden" class="dataInput" name="mail_server" value="<?php if (isset($maill_server)) echo $mail_server; ?>" />
	<input type="hidden" class="dataInput" name="mail_server_username" value="<?php if (isset($maill_server_username)) echo $mail_server_username; ?>" />
	<input type="hidden" class="dataInput" name="mail_server_password" value="<?php if (isset($maill_server_password)) echo $mail_server_password; ?>" />
	<input type="hidden" class="dataInput" name="ftpserver" value="<?php if (isset($ftpserver)) echo "$ftpserver"; ?>" />
	<input type="hidden" class="dataInput" name="ftpuser" value="<?php if (isset($ftpuser)) echo "$ftpuser"; ?>" />
	<input type="hidden" class="dataInput" name="ftppassword" value="<?php if (isset($ftppassword)) echo "$ftppassword"; ?>" />
	<input type="image" name="next" value="Change" src="install/images/cwBtnChange.gif" />
	</form>
	</td>
	</tr>
	</table>

	</td>
	</tr>
	</table>
	<!-- Horizontal Shade -->
	<table border="0" cellspacing="0" cellpadding="0" width="80%" style="background:url(install/images/cwShadeBg.gif) repeat-x;">
	<tr>
	<td><img src="install/images/cwShadeLeft.gif"></td>
	<td align=right><img src="install/images/cwShadeRight.gif"></td>
	</tr>
	</table><br><br>

	<!-- 3 of 5 closes -->

	</td>
	</tr>
	</table>
	<!-- Master table closes -->



	</body>
	</html>
<?php
}
elseif($mysql_server_version < '4.1' || $mysql_server_version > '5.0.19')
{
?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<HTML>
	<HEAD>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
	<title>vtiger CRM 5.0 beta Installer: Step 3</title>
	<link rel="stylesheet" href="install/install.css" type="text/css" />
	</head>
	<body leftMargin="0" topMargin="0" marginheight="0" marginwidth="0">

	<!-- Master table -->
	<table border=0 cellspacing=0 cellpadding=0 width=100%>
	<tr>
	<td align=center>
	<br><br>
	<!--  Top Header -->
	<table border="0" cellspacing="0" cellpadding="0" width="80%" style="background:url(install/images/cwTopBg.gif) repeat-x;">
	<tr>
	<td><img src="install/images/cwTopLeft.gif" alt="vtiger CRM" title="vtiger CRM"></td>
	<td align=right><img src="install/images/cwTopRight.gif" alt="v5alpha4" title="v5alpha4"></td>
	</tr>
	</table>



	<!-- 3 of 5 header -->
	<table border="0" cellspacing="0" cellpadding="5" width="75%" class=small> 
	<tr>	
	<td valign=top><img src="install/images/cwIcoSystem.gif" alt="Confirm Configuration" title="Confirm Configuration"></td>
	<td width=98% valign=top>
	<table border=0 cellspacing=0 cellpadding=0 width=100%>
	<tr>
	<td><img src="install/images/cwHdrVtConfWiz.gif" alt="vtiger CRM Configuration Wizard" title="vtiger CRM Configuration Wizard"></td>
	<td align=right><img src="install/images/cwStep3of5.gif" alt="Step 3 of 5" title="Step 3 of 5"></td>
	</tr>
	<tr>
	<td colspan=2><img src="install/images/cwHdrCnfSysConf.gif" alt="Confirm Configuration" title="Confirm Configuration"></td>
	</tr>
	</table>
	<hr noshade size=1>
	</td>

	</tr>
	<tr>
	<td></td>
	<td valign="top" align=center>
	<! ---------------------------------------------- System Configuration-->

	<div style="background-color:#ff0000;color:#ffffff;padding:5px">
	<b>MySQL version <?php echo $mysql_server_version; ?> is not supported,kindly connect to MySQL 4.1.x or above</b>
	</div>
	<br><br>
	<table border=0 width=100% cellspacing=0 cellpadding=0>
	<tr>
	<td align=center>
	<form action="install.php" method="post" name="form" id="form">
	<input type="hidden" name="file" value="2setConfig.php">
	<input type="hidden" class="dataInput" name="db_hostname" value="<?php if (isset($db_hostname)) echo "$db_hostname"; ?>" />
	<input type="hidden" class="dataInput" name="db_username" value="<?php if (isset($db_username)) echo "$db_username"; ?>" />
	<input type="hidden" class="dataInput" name="db_password" value="<?php if (isset($db_password)) echo "$db_password"; ?>" />
	<input type="hidden" class="dataInput" name="db_name" value="<?php if (isset($db_name)) echo "$db_name"; ?>" />
	<input type="hidden" class="dataInput" name="db_drop_tables" value="<?php if (isset($db_drop_tables)) echo "$db_drop_tables"; ?>" />
	<input type="hidden" class="dataInput" name="site_URL" value="<?php if (isset($site_URL)) echo "$site_URL"; ?>" />
	<input type="hidden" class="dataInput" name="root_directory" value="<?php if (isset($root_directory)) echo "$root_directory"; ?>" />
	<input type="hidden" class="dataInput" name="admin_email" value="<?php if (isset($admin_email)) echo "$admin_email"; ?>" />
	<input type="hidden" class="dataInput" name="admin_password" value="<?php if (isset($admin_password)) echo "$admin_password"; ?>" />
	<input type="hidden" class="dataInput" name="cache_dir" value="<?php if (isset($cache_dir)) echo $cache_dir; ?>" />
	<input type="hidden" class="dataInput" name="mail_server" value="<?php if (isset($maill_server)) echo $mail_server; ?>" />
	<input type="hidden" class="dataInput" name="mail_server_username" value="<?php if (isset($maill_server_username)) echo $mail_server_username; ?>" />
	<input type="hidden" class="dataInput" name="mail_server_password" value="<?php if (isset($maill_server_password)) echo $mail_server_password; ?>" />
	<input type="hidden" class="dataInput" name="ftpserver" value="<?php if (isset($ftpserver)) echo "$ftpserver"; ?>" />
	<input type="hidden" class="dataInput" name="ftpuser" value="<?php if (isset($ftpuser)) echo "$ftpuser"; ?>" />
	<input type="hidden" class="dataInput" name="ftppassword" value="<?php if (isset($ftppassword)) echo "$ftppassword"; ?>" />
	<input type="image" name="next" value="Change" src="install/images/cwBtnChange.gif"/>
	</form>
	</td>
	</tr>
	</table>


	</td>
	</tr>
	</table>
	<!-- Horizontal Shade -->
	<br><br>
	<table border="0" cellspacing="0" cellpadding="0" width="80%" style="background:url(install/images/cwShadeBg.gif) repeat-x;">
	<tr>
	<td><img src="install/images/cwShadeLeft.gif"></td>
	<td align=right><img src="install/images/cwShadeRight.gif"></td>
	</tr>
	</table><br><br>


	<!-- 3 of 5 stops -->

	</td>
	</tr>
	</table>




<?php
}
elseif($mysql_status == 'true' && $mysqlconn_status =='true' && $mysql_createddb_status == 'false')
{
?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<HTML>
	<HEAD>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
	<title>vtiger CRM 5.0 beta Installer: Step 3</title>
	<link rel="stylesheet" href="install/install.css" type="text/css" />
	</head>
	<body leftMargin="0" topMargin="0" marginheight="0" marginwidth="0">

	<!-- Master table -->
	<table border=0 cellspacing=0 cellpadding=0 width=100%>
	<tr>
	<td align=center>
	<br><br>
	<!--  Top Header -->
	<table border="0" cellspacing="0" cellpadding="0" width="80%" style="background:url(install/images/cwTopBg.gif) repeat-x;">
	<tr>
	<td><img src="install/images/cwTopLeft.gif" alt="vtiger CRM" title="vtiger CRM"></td>
	<td align=right><img src="install/images/cwTopRight.gif" alt="v5alpha4" title="v5alpha4"></td>
	</tr>
	</table>



	<!-- 3 of 5 header -->
	<table border="0" cellspacing="0" cellpadding="5" width="75%" class=small> 
	<tr>	
	<td valign=top><img src="install/images/cwIcoSystem.gif" alt="Confirm Configuration" title="Confirm Configuration"></td>
	<td width=98% valign=top>
	<table border=0 cellspacing=0 cellpadding=0 width=100%>
	<tr>
	<td><img src="install/images/cwHdrVtConfWiz.gif" alt="vtiger CRM Configuration Wizard" title="vtiger CRM Configuration Wizard"></td>
	<td align=right><img src="install/images/cwStep3of5.gif" alt="Step 3 of 5" title="Step 3 of 5"></td>
	</tr>
	<tr>
	<td colspan=2><img src="install/images/cwHdrCnfSysConf.gif" alt="Confirm Configuration" title="Confirm Configuration"></td>
	</tr>
	</table>
	<hr noshade size=1>
	</td>

	</tr>
	<tr>
	<td></td>
	<td valign="top" align=center>
	<! ---------------------------------------------- System Configuration-->

	<div style="background-color:#ff0000;color:#ffffff;padding:5px">
	<b>Unable to Create Database <?php echo $db_name ?></b>
	</div>
	<P>Message: The MySQL User "<? echo $root_user ?>" doesn't have permission to Create database. Try changing the Database settings<P></font>

	<br><br>
	<table border=0 width=100% cellspacing=0 cellpadding=0>
	<tr>
	<td align=center>
	<form action="install.php" method="post" name="form" id="form">
	<input type="hidden" name="file" value="2setConfig.php">
	<input type="hidden" class="dataInput" name="db_hostname" value="<?php if (isset($db_hostname)) echo "$db_hostname"; ?>" />
	<input type="hidden" class="dataInput" name="db_username" value="<?php if (isset($db_username)) echo "$db_username"; ?>" />
	<input type="hidden" class="dataInput" name="db_password" value="<?php if (isset($db_password)) echo "$db_password"; ?>" />
	<input type="hidden" class="dataInput" name="db_name" value="<?php if (isset($db_name)) echo "$db_name"; ?>" />
	<input type="hidden" class="dataInput" name="db_drop_tables" value="<?php if (isset($db_drop_tables)) echo "$db_drop_tables"; ?>" />
	<input type="hidden" class="dataInput" name="site_URL" value="<?php if (isset($site_URL)) echo "$site_URL"; ?>" />
	<input type="hidden" class="dataInput" name="root_directory" value="<?php if (isset($root_directory)) echo "$root_directory"; ?>" />
	<input type="hidden" class="dataInput" name="admin_email" value="<?php if (isset($admin_email)) echo "$admin_email"; ?>" />
	<input type="hidden" class="dataInput" name="admin_password" value="<?php if (isset($admin_password)) echo "$admin_password"; ?>" />
	<input type="hidden" class="dataInput" name="cache_dir" value="<?php if (isset($cache_dir)) echo $cache_dir; ?>" />
	<input type="hidden" class="dataInput" name="mail_server" value="<?php if (isset($maill_server)) echo $mail_server; ?>" />
	<input type="hidden" class="dataInput" name="mail_server_username" value="<?php if (isset($maill_server_username)) echo $mail_server_username; ?>" />
	<input type="hidden" class="dataInput" name="mail_server_password" value="<?php if (isset($maill_server_password)) echo $mail_server_password; ?>" />
	<input type="hidden" class="dataInput" name="ftpserver" value="<?php if (isset($ftpserver)) echo "$ftpserver"; ?>" />
	<input type="hidden" class="dataInput" name="ftpuser" value="<?php if (isset($ftpuser)) echo "$ftpuser"; ?>" />
	<input type="hidden" class="dataInput" name="ftppassword" value="<?php if (isset($ftppassword)) echo "$ftppassword"; ?>" />
	<input type="image" name="next" value="Change" src="install/images/cwBtnChange.gif"/>
	</form>
	</td>
	</tr>
	</table>


	</td>
	</tr>
	</table>
	<!-- Horizontal Shade -->
	<br><br>
	<table border="0" cellspacing="0" cellpadding="0" width="80%" style="background:url(install/images/cwShadeBg.gif) repeat-x;">
	<tr>
	<td><img src="install/images/cwShadeLeft.gif"></td>
	<td align=right><img src="install/images/cwShadeRight.gif"></td>
	</tr>
	</table><br><br>


	<!-- 3 of 5 stops -->

	</td>
	</tr>
	</table>




<?php
}
elseif($mysql_status == 'true' && $mysqlconn_status =='true' && $mysql_db_status == 'false')
{
?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<HTML>
	<HEAD>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
	<title>vtiger CRM 5.0 beta Installer: Step 3</title>
	<link rel="stylesheet" href="install/install.css" type="text/css" />
	</head>
	<body leftMargin="0" topMargin="0" marginheight="0" marginwidth="0">

	<!-- Master table -->
	<table border=0 cellspacing=0 cellpadding=0 width=100%>
	<tr>
	<td align=center>
	<br><br>
	<!--  Top Header -->
	<table border="0" cellspacing="0" cellpadding="0" width="80%" style="background:url(install/images/cwTopBg.gif) repeat-x;">
	<tr>
	<td><img src="install/images/cwTopLeft.gif" alt="vtiger CRM" title="vtiger CRM"></td>
	<td align=right><img src="install/images/cwTopRight.gif" alt="v5alpha4" title="v5alpha4"></td>
	</tr>
	</table>



	<!-- 3 of 5 header -->
	<table border="0" cellspacing="0" cellpadding="5" width="75%" class=small> 
	<tr>	
	<td valign=top><img src="install/images/cwIcoSystem.gif" alt="Confirm Configuration" title="Confirm Configuration"></td>
	<td width=98% valign=top>
	<table border=0 cellspacing=0 cellpadding=0 width=100%>
	<tr>
	<td><img src="install/images/cwHdrVtConfWiz.gif" alt="vtiger CRM Configuration Wizard" title="vtiger CRM Configuration Wizard"></td>
	<td align=right><img src="install/images/cwStep3of5.gif" alt="Step 3 of 5" title="Step 3 of 5"></td>
	</tr>
	<tr>
	<td colspan=2><img src="install/images/cwHdrCnfSysConf.gif" alt="Confirm Configuration" title="Confirm Configuration"></td>
	</tr>
	</table>
	<hr noshade size=1>
	</td>

	</tr>
	<tr>
	<td></td>
	<td valign="top" align=center>
	<! ---------------------------------------------- System Configuration-->

	<div style="background-color:#ff0000;color:#ffffff;padding:5px">
	<b>The Database "<?php echo $db_name ?>"  is not found.Try changing the Database settings</b>
	</div>

	<br><br>
	<table border=0 width=100% cellspacing=0 cellpadding=0>
	<tr>
	<td align=center>
	<form action="install.php" method="post" name="form" id="form">
	<input type="hidden" name="file" value="2setConfig.php">
	<input type="hidden" class="dataInput" name="db_hostname" value="<?php if (isset($db_hostname)) echo "$db_hostname"; ?>" />
	<input type="hidden" class="dataInput" name="db_username" value="<?php if (isset($db_username)) echo "$db_username"; ?>" />
	<input type="hidden" class="dataInput" name="db_password" value="<?php if (isset($db_password)) echo "$db_password"; ?>" />
	<input type="hidden" class="dataInput" name="db_name" value="<?php if (isset($db_name)) echo "$db_name"; ?>" />
	<input type="hidden" class="dataInput" name="db_drop_tables" value="<?php if (isset($db_drop_tables)) echo "$db_drop_tables"; ?>" />
	<input type="hidden" class="dataInput" name="site_URL" value="<?php if (isset($site_URL)) echo "$site_URL"; ?>" />
	<input type="hidden" class="dataInput" name="root_directory" value="<?php if (isset($root_directory)) echo "$root_directory"; ?>" />
	<input type="hidden" class="dataInput" name="admin_email" value="<?php if (isset($admin_email)) echo "$admin_email"; ?>" />
	<input type="hidden" class="dataInput" name="admin_password" value="<?php if (isset($admin_password)) echo "$admin_password"; ?>" />
	<input type="hidden" class="dataInput" name="cache_dir" value="<?php if (isset($cache_dir)) echo $cache_dir; ?>" />
	<input type="hidden" class="dataInput" name="mail_server" value="<?php if (isset($maill_server)) echo $mail_server; ?>" />
	<input type="hidden" class="dataInput" name="mail_server_username" value="<?php if (isset($maill_server_username)) echo $mail_server_username; ?>" />
	<input type="hidden" class="dataInput" name="mail_server_password" value="<?php if (isset($maill_server_password)) echo $mail_server_password; ?>" />
	<input type="hidden" class="dataInput" name="ftpserver" value="<?php if (isset($ftpserver)) echo "$ftpserver"; ?>" />
	<input type="hidden" class="dataInput" name="ftpuser" value="<?php if (isset($ftpuser)) echo "$ftpuser"; ?>" />
	<input type="hidden" class="dataInput" name="ftppassword" value="<?php if (isset($ftppassword)) echo "$ftppassword"; ?>" />
	<input type="image" name="next" value="Change" src="install/images/cwBtnChange.gif"/>
	</form>
	</td>
	</tr>
	</table>


	</td>
	</tr>
	</table>
	<!-- Horizontal Shade -->
	<br><br>
	<table border="0" cellspacing="0" cellpadding="0" width="80%" style="background:url(install/images/cwShadeBg.gif) repeat-x;">
	<tr>
	<td><img src="install/images/cwShadeLeft.gif"></td>
	<td align=right><img src="install/images/cwShadeRight.gif"></td>
	</tr>
	</table><br><br>


	<!-- 3 of 5 stops -->

	</td>
	</tr>
	</table>




<?php
}
elseif($mysql_status == 'true' && $mysqlconn_status == 'true' && ($mysql_db_status == 'true' || $mysql_createddb_status == 'true'))
{
?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<HTML>
	<HEAD>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
	<title>vtiger CRM 5.0 beta Installer: Step 3</title>
	<link rel="stylesheet" href="install/install.css" type="text/css" />
	<link rel="stylesheet" href="style.css" type="text/css" />
	</head>

	<body leftMargin="0" topMargin="0" marginheight="0" marginwidth="0">

	<!-- Master table -->
	<table border=0 cellspacing=0 cellpadding=0 width=100%>
	<tr>
	<td align=center>
	<br><br>
	<!--  Top Header -->
	<table border="0" cellspacing="0" cellpadding="0" width="80%" style="background:url(install/images/cwTopBg.gif) repeat-x;">
	<tr>
	<td><img src="install/images/cwTopLeft.gif" alt="vtiger CRM" title="vtiger CRM"></td>
	<td align=right><img src="install/images/cwTopRight.gif" alt="v5alpha4" title="v5alpha4"></td>
	</tr>
	</table>



	<!-- 3 of 5 header -->
	<table border="0" cellspacing="0" cellpadding="5" width="75%" class=small> 
	<tr>	
	<td valign=top><img src="install/images/cwIcoSystem.gif" alt="Confirm Configuration" title="Confirm Configuration"></td>
	<td width=98% valign=top>
	<table border=0 cellspacing=0 cellpadding=0 width=100%>
	<tr>
	<td><img src="install/images/cwHdrVtConfWiz.gif" alt="vtiger CRM Configuration Wizard" title="vtiger CRM Configuration Wizard"></td>
	<td align=right><img src="install/images/cwStep3of5.gif" alt="Step 3 of 5" title="Step 3 of 5"></td>
	</tr>
	<tr>
	<td colspan=2><img src="install/images/cwHdrCnfSysConf.gif" alt="Confirm Configuration" title="Confirm Configuration"></td>
	</tr>
	</table>
	<hr noshade size=1>
	</td>

	</tr>
	<tr>
	<td></td>
	<td valign="top" align=center>
	<!-- ---------------------------------------------- System Configuration-- -->

	<table width="90%" cellpadding="5" border="0" class="small" style="background-color:#cccccc" cellspacing="1"><tbody>
	<tr>
	<td ><strong>Database Configuration</strong></td>
	</tr>
	<tr bgcolor="White">
	<td bgcolor="#F5F5F5" width="40%">Host Name</td>
	<td align="left" nowrap> <font class="dataInput"><?php if (isset($db_hostname)) echo "$db_hostname"; ?></font></td>
	</tr>
	<tr bgcolor="White">
	<td bgcolor="#F5F5F5" width="40%">User Name</td>
	<td align="left" nowrap> <font class="dataInput"><?php if (isset($db_username)) echo "$db_username"; ?></font></td>
	</tr>
	<tr bgcolor="White">
	<td bgcolor="#F5F5F5" width="40%" noWrap>Password</td>
	<td align="left" nowrap> <font class="dataInput"><?php if (isset($db_password)) echo ereg_replace('.', '*', $db_password); ?></font></td>
	</tr>
	<tr bgcolor="White">
	<td noWrap bgcolor="#F5F5F5" width="40%">Database Name</td>
	<td align="left" nowrap> <font class="dataInput"><?php if (isset($db_name)) echo "$db_name"; ?></font></td>
	</tr>
	</table><br>

	<table width="90%" cellpadding="5" border="0" class="small" cellspacing="1" style="background-color:#cccccc"><tbody>
	<tr>
	<td colspan=2 ><h4>Site Configuration</h4></td>
	</tr>
	<tr bgcolor="White">
	<td bgcolor="#F5F5F5" width="40%">URL</td>
	<td align="left"> <font class="dataInput"><?php if (isset($site_URL)) echo $site_URL; ?></font></td>
	</tr>
	<tr bgcolor="White"> 
	<td bgcolor="#F5F5F5" width="40%">Path</td>
	<td align="left"> <font class="dataInput"><?php if (isset($root_directory)) echo $root_directory; ?></font></td>
	</tr>
	<tr bgcolor="White">
	<td bgcolor="#F5F5F5" width="40%">Cache Path</td>
	<td align="left"> <font class="dataInput"><?php if (isset($cache_dir)) echo $root_directory.''.$cache_dir; ?></font></td>
	</tr>
	<tr bgcolor="White">
	<td bgcolor="#F5F5F5" width="40%">Admin Password</td>
	<td align="left"> <font class="dataInput"><?php if (isset($admin_password)) echo ereg_replace('.', '*', $admin_password); ?></font></td>
	</tr>

	</tbody>
	</table>
	<br><br>

	<table width="90%" cellpadding="5" border="0" class="small" >
	<tr>
	<td align="left" valign="bottom">
	<form action="install.php" method="post" name="form" id="form">
	<input type="hidden" name="file" value="2setConfig.php">
	<input type="hidden" class="dataInput" name="db_hostname" value="<?php if (isset($db_hostname)) echo "$db_hostname"; ?>" />
	<input type="hidden" class="dataInput" name="db_username" value="<?php if (isset($db_username)) echo "$db_username"; ?>" />
	<input type="hidden" class="dataInput" name="db_password" value="<?php if (isset($db_password)) echo "$db_password"; ?>" />
	<input type="hidden" class="dataInput" name="db_name" value="<?php if (isset($db_name)) echo "$db_name"; ?>" />
	<input type="hidden" class="dataInput" name="db_drop_tables" value="<?php if (isset($db_drop_tables)) echo "$db_drop_tables"; ?>" />
	<input type="hidden" class="dataInput" name="site_URL" value="<?php if (isset($site_URL)) echo "$site_URL"; ?>" />
	<input type="hidden" class="dataInput" name="root_directory" value="<?php if (isset($root_directory)) echo "$root_directory"; ?>" />
	<input type="hidden" class="dataInput" name="admin_email" value="<?php if (isset($admin_email)) echo "$admin_email"; ?>" />
	<input type="hidden" class="dataInput" name="admin_password" value="<?php if (isset($admin_password)) echo "$admin_password"; ?>" />
	<input type="hidden" class="dataInput" name="cache_dir" value="<?php if (isset($cache_dir)) echo $cache_dir; ?>" />
	<input type="hidden" class="dataInput" name="mail_server" value="<?php if (isset($maill_server)) echo $mail_server; ?>" />
	<input type="hidden" class="dataInput" name="mail_server_username" value="<?php if (isset($maill_server_username)) echo $mail_server_username; ?>" />
	<input type="hidden" class="dataInput" name="mail_server_password" value="<?php if (isset($maill_server_password)) echo $mail_server_password; ?>" />
	<input type="hidden" class="dataInput" name="ftpserver" value="<?php if (isset($ftpserver)) echo "$ftpserver"; ?>" />
	<input type="hidden" class="dataInput" name="ftpuser" value="<?php if (isset($ftpuser)) echo "$ftpuser"; ?>" />
	<input type="hidden" class="dataInput" name="ftppassword" value="<?php if (isset($ftppassword)) echo "$ftppassword"; ?>" />
	<input type="image" name="Change" value="Change" src="install/images/cwBtnChange.gif"/></td>
	</form>
	</td>

	<td align="right" valign="bottom">

	<form action="install.php" method="post" name="form" id="form">
	<input type="hidden" name="file" value="4createConfigFile.php">
	<table class=small>
	<tr>
	<td><input type="checkbox" class="dataInput" name="db_populate" value="1"></td>
	<td>Populate database with demo data</td>
	</tr>
	</table>

	<input type="hidden" class="dataInput" name="db_hostname" value="<?php if (isset($db_hostname)) echo "$db_hostname"; ?>" />
	<input type="hidden" class="dataInput" name="db_username" value="<?php if (isset($db_username)) echo "$db_username"; ?>" />
	<input type="hidden" class="dataInput" name="db_password" value="<?php if (isset($db_password)) echo "$db_password"; ?>" />
	<input type="hidden" class="dataInput" name="db_name" value="<?php if (isset($db_name)) echo "$db_name"; ?>" />
	<input type="hidden" class="dataInput" name="db_drop_tables" value="<?php if (isset($db_drop_tables)) echo "$db_drop_tables"; ?>" />
	<input type="hidden" class="dataInput" name="site_URL" value="<?php if (isset($site_URL)) echo "$site_URL"; ?>" />
	<input type="hidden" class="dataInput" name="root_directory" value="<?php if (isset($root_directory)) echo "$root_directory"; ?>" />
	<input type="hidden" class="dataInput" name="admin_email" value="<?php if (isset($admin_email)) echo "$admin_email"; ?>" />
	<input type="hidden" class="dataInput" name="admin_password" value="<?php if (isset($admin_password)) echo "$admin_password"; ?>" />
	<input type="hidden" class="dataInput" name="cache_dir" value="<?php if (isset($cache_dir)) echo $cache_dir; ?>" />
	<input type="hidden" class="dataInput" name="mail_server" value="<?php if (isset($mail_server)) echo $mail_server; ?>" />
	<input type="hidden" class="dataInput" name="mail_server_username" value="<?php if (isset($mail_server_username)) echo $mail_server_username; ?>" />
	<input type="hidden" class="dataInput" name="mail_server_password" value="<?php if (isset($mail_server_password)) echo $mail_server_password; ?>" />
	<input type="hidden" class="dataInput" name="ftpserver" value="<?php if (isset($ftpserver)) echo "$ftpserver"; ?>" />
	<input type="hidden" class="dataInput" name="ftpuser" value="<?php if (isset($ftpuser)) echo "$ftpuser"; ?>" />
	<input type="hidden" class="dataInput" name="ftppassword" value="<?php if (isset($ftppassword)) echo "$ftppassword"; ?>" />
	<input type="image" src="install/images/cwBtnNext.gif" name="next" value="Create" onClick="window.location=('install.php')"/></form>
	</td>

	</tr>
	</tbody></table>
	<!-- ---------------------------------------------- System Configuration -- -->

	</td>
	</tr>
	</table>
	<!-- -->
	<br><br>
	<!-- Horizontal Shade -->
	<table border="0" cellspacing="0" cellpadding="0" width="80%" style="background:url(install/images/cwShadeBg.gif) repeat-x;">
	<tr>
	<td><img src="install/images/cwShadeLeft.gif"></td>
	<td align=right><img src="install/images/cwShadeRight.gif"></td>
	</tr>
	</table>
	</td>
	</tr>
	</table>
	</table>
	<!-- Master table closes -->
	</html>
<?php
}
?>
