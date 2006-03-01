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

if (isset($_REQUEST['db_name']))
	$db_name = $_REQUEST['db_name'];
	
if (isset($_REQUEST['db_drop_tables']))
	$db_drop_tables = $_REQUEST['db_drop_tables'];
	
if (isset($_REQUEST['db_create']))
	$db_create = $_REQUEST['db_create'];

if (isset($_REQUEST['db_populate']))
	$db_populate = $_REQUEST['db_populate'];

if (isset($_REQUEST['admin_email']))
	$admin_email = $_REQUEST['admin_email'];

if (isset($_REQUEST['admin_password']))
	$admin_password	= $_REQUEST['admin_password'];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>vtigerCRM 4.x Installer: Step 5</title>
<link rel="stylesheet" href="install/install.css" type="text/css" />
</head>
<body leftMargin="0" topMargin="0" marginheight="0" marginwidth="0">
<table width="75%" border="0" cellpadding="3" cellspacing="0" align="center" style="border-bottom: 1px dotted #CCCCCC;"><tbody>
  <tr>
      <td align="left"><a href="http://www.vtiger.com" target="_blank" title="vtigerCRM"><IMG alt="vtigerCRM" border="0" src="include/images/vtiger_crmlogo.gif"/></a></td>
      <td align="right"><h2>Step 5 of 5</h2></td>
      <td align="right"><IMG alt="vtigerCRM" border="0" src="include/images/spacer.gif" width="10" height="1"/></td>
    </tr>
</tbody></table>
<table width="75%" align="center" cellpadding="10" cellspacing="0" border="0"><tbody>

   <tr>
      <td width="100%">
		<table width="100%" cellpadding="0" cellspacing="0" border="0"><tbody><tr>
			  <td>
			   <table width="100%" cellpadding="0" cellspacing="0" border="0"><tbody><tr>

				<td><h3>Create Database Tables</h3></td>
				<td width="74%"><hr width="100%"></td>

				</tr></tbody></table>
			  </td>

			  </tr>
		</tbody></table>
	  </td>
          </tr>
          <tr>
            <td>
<?php

// Output html instead of plain text for the web
$useHtmlEntities = true;

require_once('install/5createTables.inc.php');

?>
<HR></HR>
total time: <?php echo "$deltaTime"; ?> seconds.<BR />
</td></tr>
<tr><td><hr></td></tr>
<tr><td align=left><font color=green>Your system is now installed and configured for use. You need to log in for the first time using the "admin" user name and the password you entered in step 2.</font></td></tr>
<tr><td align="right">
         <form action="index.php" method="post" name="form" id="form">
         <input type="hidden" name="default_user_name" value="admin">
         <input class="button" type="submit" name="next" value="Finish" />
         </form>
</td></tr>
</tbody></table></body></html>
