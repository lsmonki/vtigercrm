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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/install/3confirmConfig.php,v 1.13 2005/03/17 18:13:59 rank Exp $
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
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>vtiger CRM Open Source Installer: Step 3</title>
<link rel="stylesheet" href="install/install.css" type="text/css" />
</head>
<body leftMargin="0" topMargin="0" marginheight="0" marginwidth="0">
<table width="100%" border="0" cellpadding="5" cellspacing="0"><tbody>
<tr><td align="center"><a href="http://www.vtiger.com" target="_blank" title="vtiger CRM"><IMG alt="vtiger CRM" border="0" src="include/images/vtiger.jpg"/></a></td></tr>
</tbody></table>
<P></P>
<table align="center" border="0" cellpadding="2" cellspacing="2" border="1" width="60%"><tbody><tr>
   <tr>
      <td width="100%">
		<table width="100%" cellpadding="0" cellspacing="0" border="0"><tbody><tr>
			  <td>
			   <table cellpadding="0" cellspacing="0" border="0"><tbody><tr>
				<td class="formHeader" vAlign="top" align="left" height="20">
				 <IMG height="5" src="include/images/left_arc.gif" width="5" border="0"></td>
				<td class="formHeader" vAlign="middle" align="left" noWrap width="100%" height="20">Step 3: Confirm System Configuration</td>
				<td  class="formHeader" vAlign="top" align="right" height="20">
				  <IMG height="5" src="include/images/right_arc.gif" width="5" border="0"></td>
				</tr></tbody></table>
			  </td>
			  <td width="100%" align="right">&nbsp;</td>
			  </tr><tr>
			  <td colspan="2" width="100%" class="formHeader"><IMG width="100%" height="2" src="include/images/blank.gif"></td>
			  </tr>
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
			<table width="50%" cellpadding="2" border="0"><tbody>
              <tr>
  			   <td colspan="2" class="moduleTitle" noWrap>Database Configuration</td>
              </tr>
			  <tr>
               <td>Host Name</td>
               <td align="left" nowrap>: <font class="dataInput"><?php if (isset($db_host_name)) echo "$db_host_name"; ?></font></td>
              </tr>
              <tr>
               <td>MySQL User Name</td>
               <td align="left" nowrap>: <font class="dataInput"><?php if (isset($db_user_name)) echo "$db_user_name"; ?></font></td>
              </tr>
              <tr>
               <td noWrap>MySQL Password</td>
               <td align="left" nowrap>: <font class="dataInput"><?php if (isset($db_password)) echo ereg_replace('.', '*', $db_password); ?></font></td>
              </tr>
              <tr>
               <td noWrap>MySQL Database Name</td>
               <td align="left" nowrap>: <font class="dataInput"><?php if (isset($db_name)) echo "$db_name"; ?></font></td>
              </tr>
              <tr>
               <td noWrap>Drop Existing Tables</td>
               <td align="left" nowrap>: <font class="dataInput">
			   <?php if (isset($db_drop_tables) && $db_drop_tables == true) echo "True"; else echo "False"; ?>
				</font></td>
			  </tr>
			<tr><td>&nbsp;</td></tr>
			  <tr>
  			   <td colspan="2" class="moduleTitle" noWrap>Site Configuration</td>
              </tr>
              <tr>
               <td noWrap>URL</td>
               <td align="left" nowrap>: <font class="dataInput"><?php if (isset($site_URL)) echo $site_URL; ?></font></td>
              </tr>
              <tr>
               <td noWrap>Path</td>
               <td align="left" nowrap>: <font class="dataInput"><?php if (isset($root_directory)) echo $root_directory; ?></font></td>
              </tr>
              <tr>
               <td noWrap>Cache Path</td>
               <td align="left" nowrap>: <font class="dataInput"><?php if (isset($cache_dir)) echo $root_directory.'\\'.$cache_dir; ?></font></td>
              </tr>


              <tr>
               <td noWrap>Admin Password</td>
               <td align="left" nowrap>: <font class="dataInput"><?php if (isset($admin_password)) echo ereg_replace('.', '*', $admin_password); ?></font></td>
              </tr>

    	      </tbody>
			</table>
		  </td></tr>
          <tr><td align="center">
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



			 <input class="button" type="submit" name="next" value="Change" /></td>
			</form>
		 </td></tr>
		 <tr><td>&nbsp;</td></tr>
		 <tr><td align="center">
			<form action="install.php" method="post" name="form" id="form">
			<input type="hidden" name="file" value="4createConfigFile.php">
<!-- TODO Clint 4/28 - Add support for creating the database as well -->
<!--			 Also create database <font class="dataInput"><?php if (isset($db_name)) echo "$db_name"; ?></font>? -->
<!--			 <input type="checkbox" class="dataInput" name="db_create" value="1" /> -->
			 Also populate demo data?
			 <input type="checkbox" class="dataInput" name="db_populate" value="1">
			</td>
		 </tr>
		 <tr>
			<td align="right">
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
	

			 <input class="button" type="submit" name="next" value="Create" />
			</td>
          </tr>
	</tbody></table>
</form>
</body>
</html>
