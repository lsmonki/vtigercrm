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

session_start();

// vtigerCRM version number; do not edit!
$vtiger_version = "4.2.3";
$release_date = "23 December 2005";

if (isset($_REQUEST['db_hostname']))
	$db_hostname =	$_REQUEST['db_hostname'];

if (isset($_REQUEST['db_username']))
	$db_username =	$_REQUEST['db_username'];

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
	$mail_server_username =	$_REQUEST['mail_server_username'];

if (isset($_REQUEST['mail_server_password']))
	$mail_server_password =	$_REQUEST['mail_server_password'];

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
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>vtigerCRM 4.x Installer: Step 4</title>
<link rel="stylesheet" href="install/install.css" type="text/css" />
</head>
<body leftMargin="0" topMargin="0" marginheight="0" marginwidth="0">
<table width="75%" border="0" cellpadding="3" cellspacing="0" align="center" style="border-bottom: 1px dotted #CCCCCC;"><tbody>
  <tr>
      <td align="left"><a href="http://www.vtiger.com" target="_blank" title="vtigerCRM"><IMG alt="vtigerCRM" border="0" src="include/images/vtiger_crmlogo.gif"/></a></td>
      <td align="right"><h2>Step 4 of 5</h2></td>
      <td align="right"><IMG alt="vtigerCRM" border="0" src="include/images/spacer.gif" width="10" height="1"/></td>
    </tr>
</tbody></table>
<table width="75%" align="center" cellpadding="10" cellspacing="0" border="0"><tbody>
   <tr>
      <td>
		<table width=100% cellpadding="0" cellspacing="0" border="0"><tbody><tr>
			  <td>
			   <table width=100% cellpadding="0" cellspacing="0" border="0"><tbody><tr>
				<td><h3>Create Config File</h3></td>
				<td width="80%"><hr width="100%"></td>
				</tr></tbody></table>
			  </td></tr>
		</tbody></table>
	  </td>
          </tr>
	         <tr>
            <td width="100%">
<?php

if (isset($_REQUEST['root_directory']))
	$root_directory = $_REQUEST['root_directory'];

if (is_file('config.php'))
	$is_writable = is_writable('config.php');
else
	$is_writable = is_writable('.');

$config = "<?php \n";
$config .= "/*********************************************************************************\n";
$config .= " * The contents of this file are subject to the SugarCRM Public License Version 1.1.2\n";
$config .= " * (\"License\"); You may not use this file except in compliance with the \n";
$config .= " * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL\n";
$config .= " * Software distributed under the License is distributed on an  \"AS IS\"  basis,\n";
$config .= " * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for\n";
$config .= " * the specific language governing rights and limitations under the License.\n";
$config .= " * The Original Code is:  SugarCRM Open Source\n";
$config .= " * The Initial Developer of the Original Code is SugarCRM, Inc.\n";
$config .= " * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;\n";
$config .= " * All Rights Reserved.\n";
$config .= " * Contributor(s): ______________________________________.\n";
$config .= "********************************************************************************/\n";
$config .= "\n";
$config .= "\$vtiger_version = '".$vtiger_version."';\n";
$config .= "\$release_date = '".$release_date."';\n";
$config .= "\n";
$config .= "// more than 8MB memory needed for graphs\n";
$config .= "// memory limit default value = 16M\n";
$config .= "ini_set('memory_limit','16M');\n";
$config .= "\n";
$config .= "// show or hide world clock and calculator\n";
$config .= "// world_clock_display default value = true\n";
$config .= "// calculator_clock_display default value = true\n";
$config .= "\$WORLD_CLOCK_DISPLAY = 'true';\n";
$config .= "\$CALCULATOR_DISPLAY = 'true';\n";
$config .= "\n";
$config .= "// url for customer portal (Example: http://vtiger.com/portal)\n";
$config .= "\$PORTAL_URL = 'http://your-domain.com/customerportal';\n";
$config .= "\n";
$config .= "// helpdesk support email id and support name (Example: 'support@vtiger.com' and 'vtiger support')\n";
$config .= "\$HELPDESK_SUPPORT_EMAIL_ID = 'support@your-domain.com';\n";
$config .= "\$HELPDESK_SUPPORT_NAME = 'your-domain name';\n";
$config .= "\n";
$config .= "/* database configuration\n";
$config .= "      db_hostname:MySQL Database Hostname\n";
$config .= "      db_username:MySQL Username\n";
$config .= "      db_password:MySQL Password\n";
$config .= "      db_name:MySQL Database Name\n";
$config .= "*/\n";
$config .= "\$dbconfig['db_hostname'] = '".$db_hostname."';\n";
$config .= "\$dbconfig['db_username'] = '".$db_username."';\n";
$config .= "\$dbconfig['db_password'] =	'".$db_password."';\n";
$config .= "\$dbconfig['db_name'] = '".$db_name."';\n";
$config .= "\n";
$config .= "// db_type default value = mysql\n";
$config .= "\$dbconfig['db_type'] = 'mysql';\n";
$config .= "\n";
$config .= "// log_sql default value = false\n";
$config .= "\$dbconfig['log_sql'] = false;\n";
$config .= "\n";
$config .= "// persistent default value = true\n";
$config .= "\$dbconfigoption['persistent'] = true;\n";
$config .= "\n";
$config .= "// autofree default value = false\n";
$config .= "\$dbconfigoption['autofree'] = false;\n";
$config .= "\n";
$config .= "// debug default value = 0\n";
$config .= "\$dbconfigoption['debug'] = 0;\n";
$config .= "\n";
$config .= "// seqname_format default value = '%s_seq'\n";
$config .= "\$dbconfigoption['seqname_format'] = '%s_seq';\n";
$config .= "\n";
$config .= "// portability default value = 0\n";
$config .= "\$dbconfigoption['portability'] = 0;\n";
$config .= "\n";
$config .= "// ssl default value = false\n";
$config .= "\$dbconfigoption['ssl'] = false;\n";
$config .= "\n";
$config .= "\$host_name = '".$db_hostname."';\n";
$config .= "\$site_URL = '".$site_URL."';\n";
$config .= "\$root_directory = '".$root_directory."';\n";
$config .= "\$cache_dir = '$cache_dir';\n";
$config .= "\$mail_server = '$mail_server';\n";
$config .= "\$mail_server_username = '$mail_server_username';\n";
$config .= "\$mail_server_password = '$mail_server_password';\n";
//$config .= "\$ftpserver = '$ftpserver';\n";
//$config .= "\$ftpuser = '$ftpuser';\n";
//$config .= "\$ftppassword = '$ftppassword';\n";
$config .= "\n";
$config .= "// tmp_dir default value prepended by cache_dir = images/\n";
$config .= "\$tmp_dir = '" . ($cache_dir . "images/") . "';\n";
$config .= "\n";
$config .= "//  import_dir default value prepended by cache_dir = import/\n";
$config .= "\$import_dir = '" . ($cache_dir . "import/") . "';\n";
$config .= "\n";
$config .= "// upload_dir default value prepended by cache_dir = upload/\n";
$config .= "\$upload_dir = '" . ($cache_dir . "upload/") . "';\n";
$config .= "\n";
$config .= "// maximum file size for uploaded files in bytes also used when uploading import files\n";
$config .= "// upload_maxsize default value = 3000000\n";
$config .= "\$upload_maxsize = ".return_session_value_or_default('upload_maxsize', '3000000').";\n";
$config .= "\n";
$config .= "// flag to allow export functionality\n";
$config .= "// 'all' to allow anyone to use exports \n";
$config .= "// 'admin' to only allow admins to export \n";
$config .= "// 'none' to block exports completely \n";
$config .= "// allow_exports default value = all\n";
$config .= "\$allow_exports = '".return_session_value_or_default('allow_exports', 'all')."';\n";
$config .= "\n";
$config .= "// files with one of these extensions will have '.txt' appended to their filename on upload\n";
$config .= "// upload_badext default value = php, php3, php4, php5, pl, cgi, py, asp, cfm, js, vbs, html, htm\n";
$config .= "\$upload_badext = array('php', 'php3', 'php4', 'php5', 'pl', 'cgi', 'py', 'asp', 'cfm', 'js', 'vbs', 'html', 'htm');\n";
$config .= "\n";
$config .= "// full path to include directory including the trailing slash\n";
$config .= "// includeDirectory default value = $root_directory.'include/\n";
$config .= "\$includeDirectory = \$root_directory.'include/';\n";
$config .= "\n";
$config .= "// list_max_entries_per_page default value = 20\n";
$config .= "\$list_max_entries_per_page = '20';\n";
$config .= "\n";
$config .= "// history_max_viewed default value = 5\n";
$config .= "\$history_max_viewed = '5';\n";
$config .= "\n";
$config .= "// define list of menu tabs\n";
$config .= "//\$moduleList = Array('Home', 'Dashboard', 'Contacts', 'Accounts', 'Opportunities', 'Cases', 'Notes', 'Calls', 'Emails', 'Meetings', 'Tasks','MessageBoard');\n";
$config .= "\n";
$config .= "// map sugar language codes to jscalendar language codes\n";
$config .= "// unimplemented until jscalendar language files are fixed\n";
$config .= "// \$cal_codes = array('en_us'=>'en', 'ja'=>'jp', 'sp_ve'=>'sp', 'it_it'=>'it', 'tw_zh'=>'zh', 'pt_br'=>'pt', 'se'=>'sv', 'cn_zh'=>'zh', 'ge_ge'=>'de', 'ge_ch'=>'de', 'fr'=>'fr');\n";
$config .= "\n";
$config .= "// default_module default value = Home\n";
$config .= "\$default_module = 'Home';\n";
$config .= "\n";
$config .= "// default_action default value = index\n";
$config .= "\$default_action = 'index';\n";
$config .= "\n";
$config .= "// set default theme\n";
$config .= "// default_theme default value = blue\n";
$config .= "\$default_theme = 'blue';\n";
$config .= "\n";
$config .= "// show or hide time to compose each page\n";
$config .= "// calculate_response_time default value = true\n";
$config .= "\$calculate_response_time = true;\n";
$config .= "\n";
$config .= "// default text that is placed initially in the login form for user name\n";
$config .= "// no default_user_name default value\n";
$config .= "\$default_user_name = '';\n";
$config .= "\n";
$config .= "// default text that is placed initially in the login form for password\n";
$config .= "// no default_password default value\n";
$config .= "\$default_password = '';\n";
$config .= "\n";
$config .= "// create user with default username and password\n";
$config .= "// create_default_user default value = false\n";
$config .= "\$create_default_user = false;\n";
$config .= "// default_user_is_admin default value = false\n";
$config .= "\$default_user_is_admin = false;\n";
$config .= "\n";
$config .= "// if your MySQL/PHP configuration does not support persistent connections set this to true to avoid a large performance slowdown\n";
$config .= "// disable_persistent_connections default value = false\n";
$config .= "\$disable_persistent_connections = ".return_session_value_or_default('disable_persistent_connections', 'false').";\n";
$config .= "\n";
$config .= "// defined languages available. the key must be the language file prefix. (Example 'en_us' is the prefix for every 'en_us.lang.php' file)\n";
$config .= "// languages default value = en_us=>US English\n";

$language_value = "Array('en_us'=>'US English',)";

if(isset($_SESSION['language_keys']) && isset($_SESSION['language_values'])) {
	$language_value = 'Array(';
	$language_keys = explode(',', urldecode($_SESSION['language_keys']));
	$language_values = explode(',', urldecode($_SESSION['language_values']));
	$size = count($language_keys);

	for($i = 0; $i < $size; $i+=1)
		$language_value .= "'$language_keys[$i]'=>'$language_values[$i]',";

	$language_value .= ')';
}

$config .= "\$languages = $language_value;\n";
$config .= "\n";
$config .= "// default charset\n";
$config .= "// default charset default value = ISO-8859-1\n";
$config .= "\$default_charset = '".return_session_value_or_default('default_charset','ISO-8859-1')."';\n";
$config .= "\n";
$config .= "// default language\n";
$config .= "// default_language default value = en_us\n";
$config .= "\$default_language = '".return_session_value_or_default('default_language','en_us')."';\n";
$config .= "\n";
$config .= "// add the language pack name to every translation string in the display.\n";
$config .= "// translation_string_prefix default value = false\n";
$config .= "\$translation_string_prefix = ".return_session_value_or_default('translation_string_prefix','false').";\n";
$config .= "\n";
//$config .= "// forum configurations\n";
//$config .= "\$dbms = 'mysql';\n";
//$config .= "\$dbhost = '".$db_hostname."';\n\n";
//$config .= "\$dbuser = '".$db_username."';\n\n";
//$config .= "\$dbpasswd = '".$db_password."';\n\n";
//$config .= "\$dbname= '".$db_name."';\n\n";
//$config .= "\$table_prefix = 'phpbb_';\n";
//$config .= "define('PHPBB_INSTALLED', true);\n";
$config .= "?>";

if ($is_writable && ($config_file = @ fopen("config.php", "w"))) {
	fputs($config_file, $config, strlen($config));
	fclose($config_file);

	echo "<br><table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\"><tbody><tr><td align=\"left\">";
	echo "<h4>Successfully created config file (<b>config.php</b>) in :</h4></td>";
        echo "<td align=\"left\"><font color=\"00CC00\">".$root_directory."</font>\n";
	echo "</td></tr></table>";
}
else {
	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\"><tbody><tr><td align=\"left\">";
	echo "Cannot write to config.php file in the directory <font color=red>".$root_directory."</font> file.\n";
	echo "<P>You can continue this installation by manually creating the config.php file and pasting the configuration information below into the config.php file.  However, you <strong>must </strong>create the config.php file before you continue to the next step.<P>\n";
	echo  "<TEXTAREA class=\"dataInput\" rows=\"15\" cols=\"80\">".$config."</TEXTAREA>";
	echo "<P>Did you remember to create the config.php file?</td></tr>";
}

?>
	<tr><td>&nbsp;</td></tr>
	<tr><td colspan="2" align="right">
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
	</td></tr>
	</tbody></table>
</body>
</html>

