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

 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/install/4createConfigFile.php,v 1.26 2005/04/25 05:40:50 samk Exp $

 * Description:  Executes a step in the installation process.

 ********************************************************************************/



require_once('include/utils/utils.php');
include('vtigerversion.php');


session_start();



// vtiger CRM version number; do not edit!

$vtiger_version = "5.0 Beta";
$release_date = "31 March 2006";


if (isset($_REQUEST['db_hostname'])) 	list($db_hostname,$db_port) = 	split(":",$_REQUEST['db_hostname']);

// update default mysql port if not 
// provided, to be removed latter
if ($db_port == "")
$db_port = "3306";

if (isset($_REQUEST['db_username'])) 	$db_username = 	$_REQUEST['db_username'];

if (isset($_REQUEST['db_password'])) 	$db_password = 		$_REQUEST['db_password'];

if (isset($_REQUEST['db_name'])) 		$db_name  	= 		$_REQUEST['db_name'];

if (isset($_REQUEST['db_drop_tables'])) $db_drop_tables = 	$_REQUEST['db_drop_tables'];

if (isset($_REQUEST['db_create'])) 		$db_create = 		$_REQUEST['db_create'];

if (isset($_REQUEST['db_populate']))	$db_populate = 		$_REQUEST['db_populate'];

if (isset($_REQUEST['site_URL'])) 		$site_URL = 		$_REQUEST['site_URL'];
 
if (isset($_REQUEST['admin_email'])) 	$admin_email = 		$_REQUEST['admin_email'];

if (isset($_REQUEST['admin_password'])) $admin_password = 	$_REQUEST['admin_password'];

if (isset($_REQUEST['mail_server'])) $mail_server = 	$_REQUEST['mail_server'];

if (isset($_REQUEST['mail_server_username'])) $mail_server_username = 	$_REQUEST['mail_server_username'];

if (isset($_REQUEST['mail_server_password'])) $mail_server_password = 	$_REQUEST['mail_server_password'];

if (isset($_REQUEST['ftpserver'])) $ftpserver = 	$_REQUEST['ftpserver'];

if (isset($_REQUEST['ftpuser'])) $ftpuser = 	$_REQUEST['ftpuser'];

if (isset($_REQUEST['ftppassword'])) $ftppassword = 	$_REQUEST['ftppassword'];

$cache_dir = 'cache/';



?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<HTML>

<HEAD>

<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">

<title>vtiger CRM 5.0 beta Installer: Step 4</title>

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
	
	
	
	<!-- 4 of 5 header -->
	<table border="0" cellspacing="0" cellpadding="5" width="75%" class=small> 
	<tr>	
		<td valign=top><img src="install/images/cwIcoConfFile.gif" alt="System Check" title="System Check"></td>
		<td width=98% valign=top>
			<table border=0 cellspacing=0 cellpadding=0 width=100%>
			<tr>
				<td><img src="install/images/cwHdrVtConfWiz.gif" alt="vtiger CRM Configuration Wizard" title="vtiger CRM Configuration Wizard"></td>
				<td align=right><img src="install/images/cwStep4of5.gif" alt="Step 4 of 5" title="Step 4 of 5"></td>
			</tr>
			<tr>
				<td colspan=2><img src="install/images/cwHdrCrConfFile.gif" alt="Create Configuration File" title="Create Configuration File"></td>
			</tr>
			</table>
			<hr noshade size=1>
		</td>

	</tr>
	<tr>
		<td></td>
		<td valign="top" align=center>
		<!--Create Configuration File-->
<?php
	if (isset($_REQUEST['root_directory']))
	  $root_directory = $_REQUEST['root_directory'];

	  if (is_file('config.inc.php'))
	    $is_writable = is_writable('config.inc.php');
	    else
	      $is_writable = is_writable('.');

	      /* open template configuration file read only */
	      $templateFilename = 'config.template.php';
	      $templateHandle = fopen($templateFilename, "r");
	      if($templateHandle) {
		      /* open include configuration file write only */
		      $includeFilename = 'config.inc.php';
		      $includeHandle = fopen($includeFilename, "w");
		      if($includeHandle) {
			      while (!feof($templateHandle)) {
				      $buffer = fgets($templateHandle);

				      /* replace _DBC_ variable */
				      $buffer = str_replace( "_DBC_SERVER_", $db_hostname, $buffer);
				      $buffer = str_replace( "_DBC_PORT_", $db_port, $buffer);
				      $buffer = str_replace( "_DBC_USER_", $db_username, $buffer);
				      $buffer = str_replace( "_DBC_PASS_", $db_password, $buffer);
				      $buffer = str_replace( "_DBC_NAME_", $db_name, $buffer);
				      $buffer = str_replace( "_DBC_TYPE_", "mysql", $buffer);

				      $buffer = str_replace( "_SITE_URL_", $site_URL, $buffer);

				      /* replace dir variable */
				      $buffer = str_replace( "_VT_ROOTDIR_", $root_directory, $buffer);
				      $buffer = str_replace( "_VT_CACHEDIR_", $cache_dir, $buffer);
				      $buffer = str_replace( "_VT_TMPDIR_", $cache_dir."images/", $buffer);
				      $buffer = str_replace( "_VT_UPLOADDIR_", $cache_dir."upload/", $buffer);
				      /* replace mail variable */
				      $buffer = str_replace( "_MAIL_SERVER_", $mail_server, $buffer);
				      $buffer = str_replace( "_MAIL_USERNAME_", $mail_server_username, $buffer);
				      $buffer = str_replace( "_MAIL_PASSWORD_", $mail_server_password, $buffer);
				      $buffer = str_replace( "_DB_STAT_", "true", $buffer);
				
				      /* replace the application unique key variable */
				      $buffer = str_replace( "_VT_APP_UNIQKEY_", md5($root_directory), $buffer);

				      fwrite($includeHandle, $buffer);
				      }

				      fclose($includeHandle);
				      }

				      fclose($templateHandle);
				      }
  
if ($templateHandle && $includeHandle) {
	echo "<br><table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\"><tbody><tr><td align=\"left\">";
	echo "<h4>Successfully created configuration file (<b>config.inc.php</b>) in :</h4></td>";
	echo "<td align=\"left\"><font color=\"00CC00\">".$root_directory."</font>\n";
	echo "</td></tr></table>";
	}
	else {
		echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\"><tbody><tr><td align=\"left\">";
		echo "Cannot write configuration file (config.inc.php ) in the directory <font color=red>".$root_directory."</font>.\n";
		echo "<P>You can continue this installation by manually creating the config.inc.php file and pasting the configuration information below inside.However, you <strong>must</strong> create the configuration file before you continue to the next step.<P>\n";
		echo  "<TEXTAREA class=\"dataInput\" rows=\"15\" cols=\"80\">".$config."</TEXTAREA>";
		echo "<P>Did you remember to create the config.inc.php file ?</td></tr>";
		}
				  
?>


	<tr>
		<td colspan=2 >
		<br><br>
		<table border=0 cellspacing=0 cellpadding=0 width=100% class=small>
			<tr>
				<td><img src="install/images/cwURL.gif"></td>
				<td align=right>
				 <form action="install.php" method="post" name="form" id="form">
				 <!--<form action="install.php" method="post" name="form" id="form"> -->
				 <input type="hidden" name="file" value="5createTables.php">
				 <input type="hidden" class="dataInput" name="db_hostname" value="<?php if (isset($db_hostname)) echo "$db_hostname"; ?>" />
				 <input type="hidden" class="dataInput" name="db_username" value="<?php if (isset($db_username)) echo "$db_username"; ?>" />
				 <input type="hidden" class="dataInput" name="db_password" value="<?php if (isset($db_password)) echo "$db_password"; ?>" />
				 <input type="hidden" class="dataInput" name="db_name" value="<?php if (isset($db_name)) echo "$db_name"; ?>" />
				 <input type="hidden" class="dataInput" name="db_drop_tables" value="<?php if (isset($db_drop_tables)) echo "$db_drop_tables"; ?>" />
				 <input type="hidden" class="dataInput" name="db_create" value="<?php if (isset($db_create)) echo "$db_create"; ?>" />
				 <input type="hidden" class="dataInput" name="db_populate" value="<?php if (isset($db_populate)) echo "$db_populate"; ?>" />
				 <input type="hidden" class="dataInput" name="admin_email" value="<?php if (isset($admin_email)) echo "$admin_email"; ?>" />
				 <input type="hidden" class="dataInput" name="admin_password" value="<?php if (isset($admin_password)) echo "$admin_password"; ?>" />
				 <input  type="image" name="next" value="Next" src="install/images/cwBtnNext.gif" onClick="window.location=('install.php')" />
				 </form>
					
				</td>
			</tr>
		</table>
		<br><br>
		<!-- Horizontal Shade -->
		<table border="0" cellspacing="0" cellpadding="0" width="100%" style="background:url(install/images/cwShadeBg.gif) repeat-x;">
			<tr>
				<td><img src="install/images/cwShadeLeft.gif"></td>
				<td align=right><img src="install/images/cwShadeRight.gif"></td>
			</tr>
		</table>
				

	
	</td>
	</tr>

 </tbody>
</table>

<!-- Create Configuration File -->
</td>
</tr>
</table>
		
<br><br>
	
			








</td>
</tr>
</table>
<!-- Master table closes -->

</body>

</html>

