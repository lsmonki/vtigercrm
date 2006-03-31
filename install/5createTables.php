<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is: SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/install/5createTables.php,v 1.58 2005/04/19 16:57:08 ray Exp $
 * Description:  Executes a step in the installation process.
 ********************************************************************************/
set_time_limit(600);

if (isset($_REQUEST['db_name'])) $db_name  				= $_REQUEST['db_name'];
if (isset($_REQUEST['db_drop_tables'])) $db_drop_tables 	= $_REQUEST['db_drop_tables'];
if (isset($_REQUEST['db_create'])) $db_create 			= $_REQUEST['db_create'];
if (isset($_REQUEST['db_populate'])) $db_populate		= $_REQUEST['db_populate'];
if (isset($_REQUEST['admin_email'])) $admin_email		= $_REQUEST['admin_email'];
if (isset($_REQUEST['admin_password'])) $admin_password	= $_REQUEST['admin_password'];

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>vtiger CRM 5.0 beta Installer: Step 5</title>
<link rel="stylesheet" href="install/install.css" type="text/css" />
<style type="text/css"><!--


.percents {
 background: #eeeeee;
 border: 1px solid #dddddd;
 margin-left: 260px;
 height: 20px;
 position:absolute;
 width:575px;
 z-index:10;
 left: 10px;
 top: 203px;
 text-align: center;
}

.blocks {
 background: #aaaaaa;
 border: 1px solid #a1a1a1;
 margin-left: 260px;
 height: 20px;
 width: 10px;
 position: absolute;
 z-index:11;
 left: 12px;
 top: 203px;
 filter: alpha(opacity=50);
 -moz-opacity: 0.5;
 opacity: 0.5;
 -khtml-opacity: .5
}

-->
</style>
</head>
<body leftMargin="0" topMargin="0" marginheight="0" marginwidth="0">


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
	
	
	
	<!-- 5 of 5 header -->
	<table border="0" cellspacing="0" cellpadding="5" width="75%" class=small> 
	<tr>	
		<td valign=top><img src="install/images/cwIcoDB.gif" alt="Create Database Tables" title="Create Database Tables"></td>
		<td width=98% valign=top>
			<table border=0 cellspacing=0 cellpadding=0 width=100%>
			<tr>
				<td><img src="install/images/cwHdrVtConfWiz.gif" alt="vtiger CRM Configuration Wizard" title="vtiger CRM Configuration Wizard"></td>
				<td align=right><img src="install/images/cwStep5of5.gif" alt="Step 5 of 5" title="Step 5 of 5"></td>
			</tr>
			<tr>
				<td colspan=2><img src="install/images/cwHdrCrDbTables.gif" alt="Create Database Tables" title="Create Database Tables"></td>
			</tr>
			</table>
			<hr noshade size=1>
		</td>

	</tr>
	<tr>
		<td></td>
		<td>
		<!--- code -->
<?php

	// Output html instead of plain text for the web
	$useHtmlEntities = true;

	require_once('install/5createTables.inc.php');

	
//populating forums data

//this is to rename the installation file and folder so that no one destroys the setup
$renamefile = uniqid(rand(), true);
rename("install.php", $renamefile."install.php.txt");
rename("install/", $renamefile."install/");

//populate Calendar data


?>
		<br><br>
		
		<table borde=0 cellspacing=0 cellpadding=5 width=100% style="background-color:#EEFFEE; border:1px dashed #ccddcc;">
		<tr>
			<td align=center class=small>
			<b>The database tables are now set up.</b>
			<br>Total time taken: <?php echo "$deltaTime"; ?> seconds.
			<hr noshade size=1>
			<div style="width:100%;padding:10px; "align=left>
			<ul>
			<li>Your install.php file has been renamed to <?echo $renamefile;?>install.php.txt.
		<li>Your install folder too has been renamed to <?echo $renamefile;?>install/.  
			<li>Your system is now installed and configured for use.  
			<li>You need to log in for the first time using the "admin" user name and the password you entered in step 2.
			</ul>
			</div>

			</td>
		</tr>
		</table>
		
		</td></tr>
		<tr><td colspan=2 align="center">
				 <form action="index.php" method="post" name="form" id="form">
				 <input type="hidden" name="default_user_name" value="admin">
			 <input  type="image" src="<?echo $renamefile;?>install/images/cwBtnFinish.gif" name="next" value="Finish" />
				 </form>
		</td></tr>
		</table>		
							<br><br>
						<!-- Horizontal Shade -->
					<table border="0" cellspacing="0" cellpadding="0" width="75%" style="background:url(<?echo $renamefile;?>install/images/cwShadeBg.gif) repeat-x;">
					<tr>
				<td><img src="<?echo $renamefile;?>install/images/cwShadeLeft.gif"></td>
					<td align=right><img src="<?echo $renamefile;?>install/images/cwShadeRight.gif"></td>
					</tr>
					</table><br><br>

		<!-- code -->
		
		</td>
	</tr>
	</table>
	








</td>
</tr>
</table>
<!-- master table closes -->


</body></html>
