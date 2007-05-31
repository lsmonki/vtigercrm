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
 lpha2* Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header: /advent/projects/wesat'vtiger_crm/sugarcrm/install/2setConfig.php,v 1.41 2005/04/29 06:44:13 samk Exp $
 * Description:  Executes a step in the installation process.
 ********************************************************************************/

// TODO: deprecate connection.php file
//require_once("connection.php");

// TODO: introduce MySQL port as parameters to use non-default value 3306
//$sock_path=":" .$mysql_port;
$hostname = $_SERVER['SERVER_NAME'];

// TODO: introduce Apache port as parameters to use non-default value 80
//$web_root = $_SERVER['SERVER_NAME']. ":" .$_SERVER['SERVER_PORT'].$_SERVER['PHP_SELF'];
//$web_root = $hostname.$_SERVER['PHP_SELF'];
//$web_root = $HTTP_SERVER_VARS["HTTP_HOST"] . $HTTP_SERVER_VARS["REQUEST_URI"];
$web_root = ($_SERVER["HTTP_HOST"]) ? $_SERVER["HTTP_HOST"]:$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'];
$web_root .= $HTTP_SERVER_VARS["REQUEST_URI"];
$web_root = str_replace("/install.php", "", $web_root);
$web_root = "http://".$web_root;

$current_dir = pathinfo(dirname(__FILE__));
$current_dir = $current_dir['dirname']."/";
$cache_dir = "cache/";

if (is_file("config.php") && is_file("config.inc.php")) {
	require_once("config.inc.php");
	session_start();

	if(isset($upload_maxsize))
	$_SESSION['upload_maxsize'] = $upload_maxsize;

	if(isset($allow_exports))
	$_SESSION['allow_exports'] = $allow_exports;

	if(isset($disable_persistent_connections))
	$_SESSION['disable_persistent_connections'] = $disable_persistent_connections;

	if(isset($default_language))
	$_SESSION['default_language'] = $default_language;

	if(isset($translation_string_prefix))
	$_SESSION['translation_string_prefix'] = $translation_string_prefix;

	if(isset($default_charset))
	$_SESSION['default_charset'] = $default_charset;

	if(isset($languages)) {
		// need to encode the languages in a way that can be retrieved later
		$language_keys = Array();
		$language_values = Array();

		foreach($languages as $key=>$value) {
			$language_keys[] = $key;
			$language_values[] = $value;
		}
		$_SESSION['language_keys'] = urlencode(implode(",",$language_keys));
		$_SESSION['language_values'] = urlencode(implode(",",$language_values));
	}
													
	global $dbconfig;

	if (isset($_REQUEST['db_hostname']))
	$db_hostname = $_REQUEST['db_hostname'];
	elseif (isset($dbconfig['db_hostname']))
	$db_hostname = $dbconfig['db_hostname'];
	else
	$db_hostname = $hostname;

	if (isset($_REQUEST['db_username']))
	$db_username = $_REQUEST['db_username'];
	elseif (isset($dbconfig['db_username']))
	$db_username = $dbconfig['db_username'];

	if (isset($_REQUEST['db_password']))
	$db_password = $_REQUEST['db_password'];
	elseif (isset($dbconfig['db_password']))
	$db_password = $dbconfig['db_password'];

	if (isset($_REQUEST['db_type']))
	$db_type = $_REQUEST['db_type'];
	elseif (isset($dbconfig['db_type']))
	$db_type = $dbconfig['db_type'];

	if (isset($_REQUEST['db_name']))
	$db_name = $_REQUEST['db_name'];
	elseif (isset($dbconfig['db_name']) && $dbconfig['db_name']!='_DBC_NAME_')
	$db_name = $dbconfig['db_name'];
	else
	$db_name = 'vtigercrm503';

	!isset($_REQUEST['db_drop_tables']) ? $db_drop_tables = "0" : $db_drop_tables = $_REQUEST['db_drop_tables'];
	if (isset($_REQUEST['host_name'])) $host_name = $_REQUEST['host_name'];
	else $host_name = $hostname;

	if (isset($_REQUEST['site_URL'])) $site_URL = $_REQUEST['site_URL'];
	elseif (isset($site_URL) && $site_URL!='_SITE_URL_')
	$site_URL = $site_URL;
	else $site_URL = $web_root;

	if(isset($_REQUEST['root_directory'])) $root_directory = $_REQUEST['root_directory'];
	else $root_directory = $current_dir;
	    
	if (isset($_REQUEST['cache_dir']))
	$cache_dir= $_REQUEST['cache_dir'];

	if (isset($_REQUEST['mail_server']))
	$mail_server= $_REQUEST['mail_server'];

	if (isset($_REQUEST['mail_server_username']))
	$mail_server_username= $_REQUEST['mail_server_username'];

	if (isset($_REQUEST['mail_server_password']))
	$mail_server_password= $_REQUEST['mail_server_password'];

	if (isset($_REQUEST['admin_email']))
	$admin_email = $_REQUEST['admin_email'];

	if (isset($_REQUEST['admin_password']))
	$admin_password = $_REQUEST['admin_password'];
	
	if (isset($_REQUEST['currency_name']))
		$currency_name = $_REQUEST['currency_name'];
	else
		$currency_name = '';
	
	if (isset($_REQUEST['currency_symbol']))
	$currency_symbol = $_REQUEST['currency_symbol'];
	
	if (isset($_REQUEST['currency_code']))
	$currency_code = $_REQUEST['currency_code'];

	}
	else {
		!isset($_REQUEST['db_hostname']) ? $db_hostname = $hostname: $db_hostname = $_REQUEST['db_hostname'];
		!isset($_REQUEST['db_name']) ? $db_name = "vtigercrm503" : $db_name = $_REQUEST['db_name'];
		!isset($_REQUEST['db_drop_tables']) ? $db_drop_tables = "0" : $db_drop_tables = $_REQUEST['db_drop_tables'];
		!isset($_REQUEST['host_name']) ? $host_name= $hostname : $host_name= $_REQUEST['host_name'];
		!isset($_REQUEST['site_URL']) ? $site_URL = $web_root : $site_URL = $_REQUEST['site_URL'];
		!isset($_REQUEST['root_directory']) ? $root_directory = $current_dir : $root_directory = stripslashes($_REQUEST['root_directory']);
		!isset($_REQUEST['cache_dir']) ? $cache_dir = $cache_dir : $cache_dir = stripslashes($_REQUEST['cache_dir']);
		!isset($_REQUEST['mail_server']) ? $mail_server = $mail_server : $mail_server = stripslashes($_REQUEST['mail_server']);
		!isset($_REQUEST['mail_server_username']) ? $mail_server_username = $mail_server_username : $mail_server_username = stripslashes($_REQUEST['mail_server_username']);
		!isset($_REQUEST['mail_server_password']) ? $mail_server_password = $mail_server_password : $mail_server_password = stripslashes($_REQUEST['mail_server_password']);
		!isset($_REQUEST['admin_email']) ? $admin_email = "" : $admin_email = $_REQUEST['admin_email'];
	}
		!isset($_REQUEST['check_createdb']) ? $check_createdb = "" : $check_createdb = $_REQUEST['check_createdb'];
		!isset($_REQUEST['root_user']) ? $root_user = "" : $root_user = $_REQUEST['root_user'];
		!isset($_REQUEST['root_password']) ? $root_password = "" : $root_password = $_REQUEST['root_password'];
		// determine database options
		$db_options = array();
		if(function_exists('mysql_connect')) {
			$db_options['mysql'] = 'MySQL';
		}
		if(function_exists('pg_connect')) {
			$db_options['pgsql'] = 'Postgres';
		}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>vtiger CRM 5 - Configuration Wizard - System Configuration</title>
	<link href="include/install/install.css" rel="stylesheet" type="text/css">
</head>

<body class="small cwPageBg" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">
<style>
	.hide_tab{display:none;}
	.show_tab{display:inline-table;}
</style>

<script type="text/javascript" language="Javascript">

	function fnShow_Hide(){
		var sourceTag = document.getElementById('check_createdb').checked;
		if(sourceTag){
			document.getElementById('root_user').className = 'show_tab';
			document.getElementById('root_pass').className = 'show_tab';
			document.getElementById('root_user_txtbox').focus();
		}
		else{
			document.getElementById('root_user').className = 'hide_tab';
			document.getElementById('root_pass').className = 'hide_tab';
		}
	}

function trim(s) {
        while (s.substring(0,1) == " ") {
                s = s.substring(1, s.length);
        }
        while (s.substring(s.length-1, s.length) == ' ') {
                s = s.substring(0,s.length-1);
        }

        return s;
}

function verify_data(form) {
	var isError = false;
	var errorMessage = "";
	// Here we decide whether to submit the form.
	if (trim(form.db_hostname.value) =='') {
		isError = true;
		errorMessage += "\n database host name";
		form.db_hostname.focus();
	}
	if (trim(form.db_username.value) =='') {
		isError = true;
		errorMessage += "\n database user name";
		form.db_username.focus();
	}
	if (trim(form.db_name.value) =='') {
		isError = true;
		errorMessage += "\n database name";
		form.db_name.focus();
	}
	if (trim(form.site_URL.value) =='') {
		isError = true;
		errorMessage += "\n site url";
		form.site_URL.focus();
	}
	if (trim(form.root_directory.value) =='') {
		isError = true;
		errorMessage += "\n path";
		form.root_directory.focus();
	}
	if (trim(form.admin_password.value) =='') {
		isError = true;
		errorMessage += "\n admin password";
		form.admin_password.focus();
	}
	if (trim(form.admin_email.value) =='') {
		isError = true;
		errorMessage += "\n user email";
		form.admin_email.focus();
	}
	if (trim(form.cache_dir.value) =='') {
                isError = true;
                errorMessage += "\n temp directory path";
                form.cache_dir.focus();
        }
	if (trim(form.currency_name.value) =='') {
                isError = true;
                errorMessage += "\n currency name";
                form.currency_name.focus();
        }
	if (trim(form.currency_symbol.value) =='') {
                isError = true;
                errorMessage += "\n currency symbol";
                form.currency_symbol.focus();
        }	
	if (trim(form.currency_code.value) =='') {
                isError = true;
                errorMessage += "\n currency code";
                form.currency_code.focus();
        }


	if(document.getElementById('check_createdb').checked == true)
	{
		if (trim(form.root_user.value) =='') {
			isError = true;
			errorMessage += "\n root username";
			form.root_user.focus();
		}
	}

	// Here we decide whether to submit the form.
	if (isError == true) {
		alert("Missing required fields:" + errorMessage);
		return false;
	}
	if (trim(form.admin_email.value) != "" && !/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(form.admin_email.value)) {
		alert("The email id \'"+form.admin_email.value+"\' in the email field is invalid");
		form.admin_email.focus();
		return false;
	}

	var SiteUrl = form.site_URL.value;
        if(SiteUrl.indexOf("localhost") > -1 && SiteUrl.indexOf("localhost") < 10)
        {
                if(confirm("Speicfy the exact host name instead of \"localhost\" in Site URL field, otherwise you will experience some issues while working with vtiger plug-ins. Do you wish to Continue?"))
                {
                        form.submit();
                }else
                {
                        form.site_URL.select();
                        return false;
                }
        }else
        {
                form.submit();
        }
	
}
// end hiding contents from old browsers  -->
</script>

	<br><br><br>
	<!-- Table for cfgwiz starts -->

	<table border=0 cellspacing=0 cellpadding=0 width=80% align=center>
	<tr>
		<td class="cwHeadBg" align=left><img src="include/install/images/configwizard.gif" alt="Configuration Wizard" hspace="20" title="Configuration Wizard"></td>
		<td class="cwHeadBg" align=right><img src="include/install/images/vtigercrm5.gif" alt="vtiger CRM 5" title="vtiger CRM 5"></td>
	</tr>
	</table>
	<table border=0 cellspacing=0 cellpadding=0 width=80% align=center>
	<tr>
		<td background="include/install/images/topInnerShadow.gif" align=left><img src="include/install/images/topInnerShadow.gif" ></td>

	</tr>
	</table>
	<table border=0 cellspacing=0 cellpadding=10 width=80% align=center>
	<tr>
		<td class="small" bgcolor="#4572BE" align=center>
			<!-- Master display -->
			<table border=0 cellspacing=0 cellpadding=0 width=97%>
			<tr>
				<td width=20% valign=top>

				<!-- Left side tabs -->
					<table border=0 cellspacing=0 cellpadding=10 width=100%>
					<tr><td class="small cwUnSelectedTab" align=right><div align="left">Welcome</div></td></tr>
					<tr><td class="small cwUnSelectedTab" align=right><div align="left">Installation Check</div></td></tr>
					<tr><td class="small cwSelectedTab" align=right><div align="left"><b>System Configuration</b></div></td></tr>
					<tr><td class="small cwUnSelectedTab" align=right><div align="left">Confirm Settings</div></td></tr>
					<tr><td class="small cwUnSelectedTab" align=right><div align="left">Config File Creation</div></td></tr>
					<tr><td class="small cwUnSelectedTab" align=right><div align="left">Database Generation</div></td></tr>
					<tr><td class="small cwUnSelectedTab" align=right><div align="left">Finish</div></td></tr>
					</table>
					
				</td>
				<td width=80% valign=top class="cwContentDisplay" align=left>
				<!-- Right side tabs -->
				    <form action="install.php" method="post" name="installform" id="form" name="setConfig" id="form">
				    <table border=0 cellspacing=0 cellpadding=10 width=100%>
				    <tr><td class=small align=left><img src="include/install/images/confWizSysConfig.gif" alt="System Configuration" title="System Configuration"><br>
					  <hr noshade size=1></td></tr>
				    <tr>
					<td align=left class="small" style="padding-left:20px">

	
				<table width="100%" cellpadding="5"  cellspacing="1" border="0" class=small><tbody>
				<tr>
					<td >
		          		<b>Please enter your database configuration information below...</b><br>

					  If you do not have root access to your database (for example you are installing in a virtual
					  hosting environment), you will need to have your database created for you before you proceed.
					  However, this installer will still be able to create the necessary database tables. <br><br>
			
					  If you are unsure of your database host, username or password, we suggest that you use the default
					  values below. <br><br>
					  
					  *- required information
					  

					</td>
				</tr>
				</table>
			
			<br>
			
			<table width="90%" cellpadding="5"  cellspacing="1" border="0" class=small style="background-color:#cccccc"><tbody>
			<tr><td colspan=2><strong>Database Configuration</strong></td></tr>
			<tr>
               <td width="25%" nowrap bgcolor="#F5F5F5" ><strong>Database Type</strong> <sup><font color=red>*</font></sup></td>
               <td width="75%" bgcolor="white" align="left">
		<?php if(!$db_options) : ?>
			No Database Support Detected
		<?php elseif(count($db_options) == 1) : ?>
			<?php list($db_type, $label) = each($db_options); ?>
			<input type="hidden" name="db_type" value="<?php echo $db_type ?>"><?php echo $label ?>
		<?php else : ?>
			<select name="db_type">
			<?php foreach($db_options as $db_option_type => $label) : ?>
				<option value="<?php echo $db_option_type ?>" <?php if(isset($db_type) && $db_type == $db_option_type) { echo "SELECTED"; } ?>><?php echo $label ?></option>
			<?php endforeach ?>
			</select>
		<?php endif ?>
			   </td>
            </tr>
			<tr>
               <td width="25%" nowrap bgcolor="#F5F5F5" ><strong>Host Name</strong> <sup><font color=red>*</font></sup></td>
               <td width="75%" bgcolor="white" align="left"><input type="text" class="dataInput" name="db_hostname" value="<?php if (isset($db_hostname)) echo "$db_hostname"; ?>" /></td>
              </tr>
              <tr>
               <td nowrap bgcolor="#F5F5F5"><strong>User Name</strong> <sup><font color=red>*</font></sup></td>
               <td bgcolor="white" align="left"><input type="text" class="dataInput" name="db_username" value="<?php if (isset($db_username)) echo "$db_username"; ?>" /></td>
              </tr>
              <tr>
               <td nowrap bgcolor="#F5F5F5"><strong>Password</strong></td>
               <td bgcolor="white" align="left"><input type="password" class="dataInput" name="db_password" value="<?php if (isset($db_password)) echo "$db_password"; ?>" /></td>
              </tr>
              <tr>
               <td nowrap bgcolor="#F5F5F5"><strong>Database Name</strong> <sup><font color=red>*</font></sup></td>
               <td bgcolor="white" align="left"><input type="text" class="dataInput" name="db_name" value="<?php if (isset($db_name)) echo "$db_name"; ?>" />&nbsp;
		       <?php if($check_createdb == 'on')
			       {?>
			       <input name="check_createdb" type="checkbox" id="check_createdb" checked onClick="fnShow_Hide()"/>
			       <?php }else{?>
				       <input name="check_createdb" type="checkbox" id="check_createdb" onClick="fnShow_Hide()"/>
			       <?php } ?>
			       &nbsp;Create Database(will drop the database if exists)</td>
              </tr>
	      <tr id="root_user" class="hide_tab">
			   <td bgcolor="#f5f5f5" nowrap="nowrap" width="25%"><strong>Root Username</strong> <sup><font color="red">*</font></sup></td>
			   <td align="left" bgcolor="white"><input class="dataInput" name="root_user" id="root_user_txtbox" value="<?php echo $root_user;?>" type="text"></td>
 	      </tr>
	      <tr id="root_pass" class="hide_tab">
			   <td bgcolor="#f5f5f5" nowrap="nowrap"><strong>Root Password</strong></td>
			   <td align="left" bgcolor="white"><input class="dataInput" name="root_password" value="<?php echo $root_password;?>" type="password"></td>
	      </tr>
              </table>
			
			<br><br>
			
		  <!-- Web site configuration -->
		<table width="90%" cellpadding="5" border="0" style="background-color:#cccccc" cellspacing="1" class="small"><tbody>
            <tr>
				<td ><strong>Site Configuration</strong></td>
            </tr>
			<tr>
				<td width="25%" bgcolor="#F5F5F5" ><strong>URL</strong> <sup><font color=red>*</font></sup></td>
				<td width="75%" bgcolor=white align="left"><input class="dataInput" type="text" name="site_URL"
				value="<?php if (isset($site_URL)) echo $site_URL; ?>" size="40" />
				</td>
			</tr>
			<tr>
				<td bgcolor="#F5F5F5"><strong>Path</strong> <sup><font color=red>*</font></sup></td>
				<td align="left" bgcolor="white"><input class="dataInput" type="text" name="root_directory" value="<?php if (isset($root_directory)) echo "$root_directory"; ?>" size="40" /> </td>
			</tr>
			<tr valign="top">
				<td bgcolor="#F5F5F5"><strong>Path to Cache Directory  <sup><font color=red>*</font></sup><br>(must be writable)</td>
				<td align="left" bgcolor="white"><?php echo $root_directory; ?><input class="dataInput" type="text" name="cache_dir" size='14' value="<?php if (isset($cache_dir)) echo $cache_dir; ?>" size="40" /> </td>
          </tr>
          </table>
			<br><br>
			
			<!-- Admin Configuration -->
            <table width="90%" cellpadding="5" border="0" class="small" cellspacing="1" style="background-color:#cccccc">
			<tr>
				<td colspan=2><strong>Admin Configuration</strong></td>
            </tr>
			<tr>
				<td nowrap width=25% bgcolor="#F5F5F5" ><strong>User name</strong></td>
				<td width=75% bgcolor="white" align="left">admin</td>
			</tr>
			<tr>
				<td bgcolor="#F5F5F5" nowrap><strong>Password</strong><sup><font color=red>*</font></sup></td>
				<td bgcolor="white" align="left"><input class="dataInput" type="password" name="admin_password" value="<?php if (isset($admin_password)) echo "$admin_password"; else echo "admin"; ?>"></td>
			</tr>
			<tr>
				<td bgcolor="#F5F5F5" nowrap><strong>Email</strong><sup><font color=red>*</font></sup></td>
				<td bgcolor="white" align="left"><input class="dataInput" type="text" name="admin_email" value="<?php if (isset($admin_email)) echo "$admin_email"; ?>"></td>
			</tr>
			<tr>
				<td colspan="2" bgcolor="white"><font color=blue> <b>Note:</b> The default password is 'admin'. You can change the password if necessary now or else you can change it later after logging-in.</font></td>
			</tr>
			</table>
	
				<br><br>
		<!-- Currency Configuration -->
            <table width="90%" cellpadding="5" border="0" class="small" cellspacing="1" style="background-color:#cccccc">
			<tr>
				<td colspan=2><strong>Currency
		Configuration</strong></td> <i>This will setup the
		default currency which will be used for maintaining
		transactions in vtiger.</i> 
            </tr>
			<tr>
				<td nowrap width=25% bgcolor="#F5F5F5" ><strong>Name</strong><sup><font color=red>*</font></sup></td>
				<td width=75% bgcolor="white" align="left"><input class="dataInput" type="text" name="currency_name" value="<?php if (isset($currency_name)) echo "$currency_name"; ?>"></td>
			</tr>
			<tr>
				<td bgcolor="#F5F5F5" nowrap><strong>Symbol</strong><sup><font color=red>*</font></sup></td>
				<td bgcolor="white" align="left"><input class="dataInput" type="text" name="currency_symbol" value="<?php if (isset($currency_symbol)) echo "$currency_symbol";?>"></td>
			</tr>
			<tr>
				<td bgcolor="#F5F5F5" nowrap><strong>Code</strong><sup><font color=red>*</font></sup></td>
				<td bgcolor="white" align="left"><input class="dataInput" type="text" name="currency_code" value="<?php if (isset($currency_code)) echo "$currency_code"; ?>"></td>
			</tr>
			</table>

		<!-- System Configuration -->
		</td>
		</tr>
		<tr>
				<td align=center>
					<input type="hidden" name="file" value="3confirmConfig.php" />
					<input type="image" src="include/install/images/cwBtnNext.gif" id="starttbn" alt="Next" border="0" title="Next" onClick="return verify_data(window.document.installform);">
					<br>
				    <br></td>
			</tr>
		</table>
		</form>
	</td>
		</tr>
	</table>
	<!-- Master display stops -->
	<br>
	</td>
	</tr>
	</table>
	<table border=0 cellspacing=0 cellpadding=0 width=80% align=center>
	<tr>

		<td background="include/install/images/bottomGradient.gif"><img src="include/install/images/bottomGradient.gif"></td>
	</tr>
	</table>
	<table border=0 cellspacing=0 cellpadding=0 width=80% align=center>
	<tr>
		<td align=center><img src="include/install/images/bottomShadow.jpg"></td>
	</tr>
	</table>	
	<table border=0 cellspacing=0 cellpadding=0 width=80% align=center>

      	<tr>
        	<td class=small align=center> <a href="http://www.vtiger.com" target="_blank">www.vtiger.com</a></td>
      	</tr>
    	</table>
	<script>
	fnShow_Hide();
	</script>
</body>
</html>
