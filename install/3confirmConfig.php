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
if (isset($_REQUEST['db_host_name'])) $db_host_name 	= $_REQUEST['db_host_name'];
if (isset($_REQUEST['db_user_name'])) $db_user_name 	= $_REQUEST['db_user_name'];
if (isset($_REQUEST['db_password'])) $db_password 		= $_REQUEST['db_password'];
if (isset($_REQUEST['db_name'])) $db_name  				= $_REQUEST['db_name'];
if (isset($_REQUEST['db_drop_tables'])) $db_drop_tables = $_REQUEST['db_drop_tables'];
if (isset($_REQUEST['site_URL'])) $site_URL 			= $_REQUEST['site_URL'];
if (isset($_REQUEST['admin_email'])) $admin_email 		= $_REQUEST['admin_email'];
if (isset($_REQUEST['admin_password'])) $admin_password = $_REQUEST['admin_password'];
if (isset($_REQUEST['cache_dir'])) $cache_dir           = $_REQUEST['cache_dir'];
if (isset($_REQUEST['mail_server'])) $mail_server           = $_REQUEST['mail_server'];
if (isset($_REQUEST['mail_server_username'])) $mail_server_username           = $_REQUEST['mail_server_username'];
if (isset($_REQUEST['mail_server_password'])) $mail_server_password           = $_REQUEST['mail_server_password'];
if (isset($_REQUEST['root_directory'])) $root_directory = $_REQUEST['root_directory'];
if (isset($_REQUEST['ftpserver'])) $ftpserver 	= $_REQUEST['ftpserver'];
if (isset($_REQUEST['ftpuser'])) $ftpuser 	= $_REQUEST['ftpuser'];
if (isset($_REQUEST['ftppassword'])) $ftppassword	= $_REQUEST['ftppassword'];
if (isset($_REQUEST['dbtype'])) $dbtype	= $_REQUEST['dbtype'];

//Checking for mysql connection parameters



$mysql_status = '';
$mysql_db_status = '';
if($dbtype != 'mysql' || $dbtype =='')
{
	$mysql_status = 'true';
	$mysql_db_status = 'true';
}
else
{
	$conn = @mysql_pconnect($db_host_name,$db_user_name,$db_password);
	if(!$conn)
	{
		$mysql_status = 'false';
	}
	else
	{
		if(mysql_select_db($db_name,$conn))
		{
			$mysql_status = 'true';
			$mysql_db_status = 'true';
		}
		else
		{
			$mysql_status = 'true';
			$mysql_db_status = 'false';
		}
	}
}
?>

<?php
if($mysql_status == 'true' && $mysql_db_status == 'true')
{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>vtiger CRM 4.2 Installer: Step 3</title>
<link rel="stylesheet" href="install/install.css" type="text/css" />
</head>
<body leftMargin="0" topMargin="0" marginheight="0" marginwidth="0">
<table width="75%" border="0" cellpadding="3" cellspacing="0" align="center" style="border-bottom: 1px dotted #CCCCCC;"><tbody>
  <tr>
      <td align="left"><a href="http://www.vtiger.com" target="_blank" title="vtiger CRM"><IMG alt="vtiger CRM" border="0" src="include/images/vtiger_crmlogo.gif"/></a></td>
      <td align="right"><h2>Step 3 of 5</h2></td>
      <td align="right"><IMG alt="vtiger CRM" border="0" src="include/images/spacer.gif" width="10" height="1"/></td>
    </tr>
</tbody></table>
<table width="75%" align="center" border="0" cellpadding="10" cellspacing="0" border="0"><tbody>
   <tr>
      <td width="100%">
		<table width="100%" cellpadding="0" cellspacing="0" border="0"><tbody><tr>
			  <td>
			   <table width="100%" cellpadding="0" cellspacing="0" border="0"><tbody><tr>

				<td nowrap><h3>Confirm System Configuration</h3></td>
				<td width="80%"><hr width="100%"></td>

				</tr></tbody></table>
			  </td>

		</tbody></table>
	  </td>
          </tr>
          <tr>
            <td>
          <P>Please review the configuration information below... <P>
		    </td>
          </tr>
          <tr>
		    <td align="center">
					<table width="80%" cellpadding="5" border="0" style="border: 1px dotted #666666;"><tbody>
		              <tr>
					<td bgcolor="#EEEEEE"><h4>Database Configuration</h4></td>
		              </tr>
              </table>
<table width="80%" cellpadding="5" border="0" style="border: 1px dotted #666666;"><tbody>
			  <tr>
               <td bgcolor="#F5F5F5" width="40%">Host Name</td>
               <td align="left" nowrap>: <font class="dataInput"><?php if (isset($db_host_name)) echo "$db_host_name"; ?></font></td>
              </tr>
              <tr>
               <td bgcolor="#F5F5F5" width="40%">User Name</td>
               <td align="left" nowrap>: <font class="dataInput"><?php if (isset($db_user_name)) echo "$db_user_name"; ?></font></td>
              </tr>
              <tr>
               <td bgcolor="#F5F5F5" width="40%" noWrap>Password</td>
               <td align="left" nowrap>: <font class="dataInput"><?php if (isset($db_password)) echo ereg_replace('.', '*', $db_password); ?></font></td>
              </tr>
              <tr>
               <td noWrap bgcolor="#F5F5F5" width="40%">Database Name</td>
               <td align="left" nowrap>: <font class="dataInput"><?php if (isset($db_name)) echo "$db_name"; ?></font></td>
              </tr>
              <tr>
               <td noWrap bgcolor="#F5F5F5" width="40%">Drop Existing Tables</td>
               <td align="left" nowrap>: <font class="dataInput">
			   <?php if (isset($db_drop_tables) && $db_drop_tables == true) echo "True"; else echo "False"; ?>
				</font></td>
			  </tr></table><br>
					<table width="85%" cellpadding="5" border="0" style="border: 1px dotted #666666;"><tbody>
		              <tr>
					<td bgcolor="#EEEEEE"><h4>Site Configuration</h4></td>
		              </tr>
              </table>
			<table width="85%" cellpadding="5" border="0" style="border: 1px dotted #666666;"><tbody>

              <tr>
               <td bgcolor="#F5F5F5" width="40%">URL</td>
               <td align="left">: <font class="dataInput"><?php if (isset($site_URL)) echo $site_URL; ?></font></td>
              </tr>
              <tr>
               <td bgcolor="#F5F5F5" width="40%">Path</td>
               <td align="left">: <font class="dataInput"><?php if (isset($root_directory)) echo $root_directory; ?></font></td>
              </tr>
              <tr>
               <td bgcolor="#F5F5F5" width="40%">Cache Path</td>
               <td align="left">: <font class="dataInput"><?php if (isset($cache_dir)) echo $root_directory.''.$cache_dir; ?></font></td>
              </tr>


              <tr>
               <td bgcolor="#F5F5F5" width="40%">Admin Password</td>
               <td align="left">: <font class="dataInput"><?php if (isset($admin_password)) echo ereg_replace('.', '*', $admin_password); ?></font></td>
              </tr>

    	      </tbody>
			</table>
<table width="80%" cellpadding="5" border="0">
          <tr>
           <td align="left" valign="bottom">
	       		 <form action="install.php" method="post" name="form" id="form">
			       <input type="hidden" name="file" value="2setConfig.php">
             <input type="hidden" class="dataInput" name="db_host_name" value="<?php if (isset($db_host_name)) echo "$db_host_name"; ?>" />
             <input type="hidden" class="dataInput" name="db_user_name" value="<?php if (isset($db_user_name)) echo "$db_user_name"; ?>" />
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



			 <input class="button" type="submit" name="next" value="Change" /></td></form>

		 </td>
<td width="40%" align="right" valign="bottom">
		  					<form action="install.php" method="post" name="form" id="form"> 
		  		 			<input type="hidden" name="file" value="4createConfigFile.php">
		  		 <!-- TODO Clint 4/28 - Add support for creating the database as well -->
		  		 <!--			 Also create database <font class="dataInput"><?php if (isset($db_name)) echo "$db_name"; ?></font>? -->
		  		 <!--			 <input type="checkbox" class="dataInput" name="db_create" value="1" /> -->
		  		 			 <b>Also populate demo data?</b>
		  		 			 <input type="checkbox" class="dataInput" name="db_populate" value="1">
		  		 			 
		  		 			 
			 <input type="hidden" class="dataInput" name="db_host_name" value="<?php if (isset($db_host_name)) echo "$db_host_name"; ?>" />
             <input type="hidden" class="dataInput" name="db_user_name" value="<?php if (isset($db_user_name)) echo "$db_user_name"; ?>" />
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


			 <input class="button" type="submit" name="next" value="Create" /></form>
		  		 			 
			</td>

	<!-- td align="right">
	<form action="install.php" method="post" name="form" id="form">
	<input type="hidden" name="file" value="4createConfigFile.php">
			 <input type="hidden" class="dataInput" name="db_host_name" value="<?php if (isset($db_host_name)) echo "$db_host_name"; ?>" />
             <input type="hidden" class="dataInput" name="db_user_name" value="<?php if (isset($db_user_name)) echo "$db_user_name"; ?>" />
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


			 <input class="button" type="submit" name="next" value="Create" /></form>
			</td -->
		 </tr>
		 


	</tbody></table>

</body>
</html>
<?php
}
?>

<?php
if($mysql_status == 'false')
{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>vtiger CRM 4.2 Installer: Step 3</title>
<link rel="stylesheet" href="install/install.css" type="text/css" />
</head>
<body leftMargin="0" topMargin="0" marginheight="0" marginwidth="0">
<table width="75%" border="0" cellpadding="3" cellspacing="0" align="center" style="border-bottom: 1px dotted #CCCCCC;"><tbody>
  <tr>
      <td align="left"><a href="http://www.vtiger.com" target="_blank" title="vtiger CRM"><IMG alt="vtiger CRM" border="0" src="include/images/vtiger_crmlogo.gif"/></a></td>
      <td align="right"><h2>Step 3 of 5</h2></td>
      <td align="right"><IMG alt="vtiger CRM" border="0" src="include/images/spacer.gif" width="10" height="1"/></td>
    </tr>
</tbody></table>
<table width="75%" align="center" cellpadding="10" cellspacing="0" border="0"><tbody>
   <tr>
      <td>
		<table width="100%" cellpadding="0" cellspacing="0" border="0"><tbody><tr>
			  <td>
			   <table width="100%" cellpadding="0" cellspacing="0" border="0"><tbody><tr>

				<td class="formHeader" vAlign="middle" align="left" noWrap width="100%" height="20"><h4>Invalid Mysql Connection Parameters specified</h4></td>
				<td width="80%">&nbsp;</td>
				</tr></tbody></table>
			  </td>

			  </tr>
		</tbody></table>
	  </td>
          </tr>
	  <tr>
		<td>
			<font color=brown><b><P>Error Message: Unable to connect to database Sever with the specified connection parameters. This may be due to the following reasons:<P>

			-  specified database user, password , hostname or port is invalid.<BR>
                        -  specified database user does not have access to connect to the database server from the host</b></font>
		</td>
          </tr>
          <tr>
            <td>
          <font color=brown><b><P>Kindly check the specified database connection parameters... <P></b></font>
		    </td>
          </tr>
          <tr>
		    <td align="center">
	<table width="70%" cellpadding="5" border="0" style="border: 1px dotted #666666;">
		              <tr>
					<td bgcolor="#EEEEEE"><h3>Database Configuration</h3></td>
		              </tr>
              </table>
	<table width="70%" cellpadding="5" border="0" style="border: 1px dotted #666666;">
			  <tr>
               <td bgcolor="#F5F5F5" width="40%">Host Name</td>
               <td align="left" nowrap><font class="dataInput"><?php if (isset($db_host_name)) echo "$db_host_name"; ?></font></td>
              </tr>
              <tr>
               <td bgcolor="#F5F5F5" width="40%">User Name</td>
               <td align="left" nowrap><font class="dataInput"><?php if (isset($db_user_name)) echo "$db_user_name"; ?></font></td>
              </tr>
              <tr>
               <td noWrap bgcolor="#F5F5F5" width="40%">Password</td>
               <td align="left" nowrap><font class="dataInput"><?php if (isset($db_password)) echo ereg_replace('.', '*', $db_password); ?></font></td>
              </tr>
              <tr>
               <td noWrap bgcolor="#F5F5F5" width="40%">Database Name</td>
               <td align="left" nowrap>: <font class="dataInput"><?php if (isset($db_name)) echo "$db_name"; ?></font></td>
              </tr>

			</table>
		  </td></tr>
          <tr>
      <td align="right">
        <form action="install.php" method="post" name="form" id="form">
			 <input type="hidden" name="file" value="2setConfig.php">
             <input type="hidden" class="dataInput" name="db_host_name" value="<?php if (isset($db_host_name)) echo "$db_host_name"; ?>" />
             <input type="hidden" class="dataInput" name="db_user_name" value="<?php if (isset($db_user_name)) echo "$db_user_name"; ?>" />
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



			 <input class="button" type="submit" name="next" value="Change" />
        </form>
		 </td></tr>
</table>
</body>
</html>
<?php
}
?>

<?php
if($mysql_status == 'true' && $mysql_db_status == 'false')
{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>vtiger CRM 4.2 Installer: Step 3</title>
<link rel="stylesheet" href="install/install.css" type="text/css" />
</head>
<body leftMargin="0" topMargin="0" marginheight="0" marginwidth="0">
<table width="75%" border="0" cellpadding="3" cellspacing="0" align="center" style="border-bottom: 1px dotted #CCCCCC;"><tbody>
  <tr>
      <td align="left"><a href="http://www.vtiger.com" target="_blank" title="vtiger CRM"><IMG alt="vtiger CRM" border="0" src="include/images/vtiger_crmlogo.gif"/></a></td>
      <td align="right"><h2>Step 3 of 5</h2></td>
      <td align="right"><IMG alt="vtiger CRM" border="0" src="include/images/spacer.gif" width="10" height="1"/></td>
    </tr>
</tbody></table>
<table width="75%" align="center" cellpadding="10" cellspacing="0" border="0"><tbody>
   <tr>
      <td width="100%">
		<table width="100%" cellpadding="0" cellspacing="0" border="0"><tbody><tr>
			  <td>
			   <table  width="100%" cellpadding="0" cellspacing="0" border="0"><tbody><tr>
				<td class="formHeader" vAlign="middle" align="left" noWrap width="100%" height="20"><h4>Database Not Found</h4> </td>
				<td width="80%">&nbsp;</td>
				</tr></tbody></table>
			  </td>

			  </tr>
		</tbody></table>
	  </td>
          </tr>
	  <tr>
		<td>
			<font color=brown><b><P>Error Message: The specified database <?php echo $db_name ?> is not present. Create the database or specify some other database name <P></b></font>
		</td>
          </tr>
          <tr>
      <td height="40" align="right">
        <form action="install.php" method="post" name="form" id="form">
			 <input type="hidden" name="file" value="2setConfig.php">
             <input type="hidden" class="dataInput" name="db_host_name" value="<?php if (isset($db_host_name)) echo "$db_host_name"; ?>" />
             <input type="hidden" class="dataInput" name="db_user_name" value="<?php if (isset($db_user_name)) echo "$db_user_name"; ?>" />
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



			 <input class="button" type="submit" name="next" value="Change" />
        </form>
</td>
</tr>
</table>
</body>
</html>


<?php
}
?>
