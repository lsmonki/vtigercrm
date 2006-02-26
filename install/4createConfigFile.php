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

require_once('include/utils.php');
include('vtigerversion.php');

session_start();

if (isset($_REQUEST['db_hostname']))
  $db_hostname = $_REQUEST['db_hostname'];

if (isset($_REQUEST['db_username']))
  $db_username = $_REQUEST['db_username'];

if (isset($_REQUEST['db_password']))
  $db_password = $_REQUEST['db_password'];

if (isset($_REQUEST['db_name']))
  $db_name = $_REQUEST['db_name'];

if (isset($_REQUEST['db_drop_tables']))
  $db_drop_tables = $_REQUEST['db_drop_tables'];

if (isset($_REQUEST['db_create']))
  $db_create = $_REQUEST['db_create'];

if (isset($_REQUEST['db_populate']))
  $db_populate = $_REQUEST['db_populate'];

if (isset($_REQUEST['site_URL']))
  $site_URL = $_REQUEST['site_URL'];
 
if (isset($_REQUEST['admin_email']))
  $admin_email = $_REQUEST['admin_email'];

if (isset($_REQUEST['admin_password']))
  $admin_password = $_REQUEST['admin_password'];

if (isset($_REQUEST['mail_server']))
  $mail_server = $_REQUEST['mail_server'];

if (isset($_REQUEST['mail_server_username']))
  $mail_server_username = $_REQUEST['mail_server_username'];

if (isset($_REQUEST['mail_server_password']))
  $mail_server_password = $_REQUEST['mail_server_password'];

if (isset($_REQUEST['ftpserver']))
  $ftpserver = $_REQUEST['ftpserver'];

if (isset($_REQUEST['ftpuser']))
  $ftpuser = $_REQUEST['ftpuser'];

if (isset($_REQUEST['ftppassword']))
  $ftppassword = $_REQUEST['ftppassword'];

$cache_dir = 'cache/';

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <title>vtigerCRM 4.x Installer: Step 4</title>
  <link rel="stylesheet" href="install/install.css" type="text/css" />
</head>
<body leftMargin="0" topMargin="0" marginheight="0" marginwidth="0">
  <table width="75%" border="0" cellpadding="3" cellspacing="0" align="center" style="border-bottom: 1px dotted #CCCCCC;">
    <tbody>
      <tr>
        <td align="left"><a href="http://www.vtiger.com" target="_blank" title="vtigerCRM"><IMG alt="vtigerCRM" border="0" src="include/images/vtiger_crmlogo.gif"/></a></td>
        <td align="right"><h2>Step 4 of 5</h2></td>
        <td align="right"><IMG alt="vtigerCRM" border="0" src="include/images/spacer.gif" width="10" height="1"/></td>
      </tr>
    </tbody>
  </table>
  <table width="75%" align="center" cellpadding="10" cellspacing="0" border="0">
  <tbody>
    <tr>
      <td>
        <table width=100% cellpadding="0" cellspacing="0" border="0">
          <tbody>
            <tr>
              <td>
                <table width=100% cellpadding="0" cellspacing="0" border="0">
                  <tbody>
                    <tr>
                      <td><h3>Create configuration file</h3></td>
                      <td width="80%"><hr width="100%"></td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
    <tr>
    <td width="100%">
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
      $buffer = str_replace( "_DBC_PORT_", "3306", $buffer);
      $buffer = str_replace( "_DBC_USER_", $db_username, $buffer);
      $buffer = str_replace( "_DBC_PASS_", $db_password, $buffer);
      $buffer = str_replace( "_DBC_NAME_", $db_name, $buffer);
      $buffer = str_replace( "_DBC_TYPE_", "mysql", $buffer);
      /* replace dir variable */
      $buffer = str_replace( "_VT_ROOTDIR_", $root_directory, $buffer);
      $buffer = str_replace( "_VT_CACHEDIR_", $cache_dir, $buffer);
      $buffer = str_replace( "_VT_TMPDIR_", $cache_dir."images/", $buffer);
      $buffer = str_replace( "_VT_IMPORTDIR_", $cache_dir."import/", $buffer);
      $buffer = str_replace( "_VT_UPLOADDIR_", $cache_dir."upload/", $buffer);
      /* replace mail variable */
      $buffer = str_replace( "_MAIL_SERVER_", $mail_server, $buffer);
      $buffer = str_replace( "_MAIL_USERNAME_", $mail_server_username, $buffer);
      $buffer = str_replace( "_MAIL_PASSWORD_", $mail_server_password, $buffer);

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

      <tr><td>&nbsp;</td></tr>
        <tr>
          <td colspan="2" align="right">
            <form action="install.php" method="post" name="form" id="form">
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

              <input class="button" type="submit" name="next" value="Next" />
            </form>
          </td>
        </tr>
      </tbody>
    </table>
  </body>
</html>
