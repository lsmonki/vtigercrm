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
global $php_max_execution_time;
set_time_limit($php_max_execution_time);

if (isset($_REQUEST['db_name'])) $db_name  				= $_REQUEST['db_name'];
if (isset($_REQUEST['db_drop_tables'])) $db_drop_tables 	= $_REQUEST['db_drop_tables'];
if (isset($_REQUEST['db_create'])) $db_create 			= $_REQUEST['db_create'];
if (isset($_REQUEST['db_populate'])) $db_populate		= $_REQUEST['db_populate'];
if (isset($_REQUEST['admin_email'])) $admin_email		= $_REQUEST['admin_email'];
if (isset($_REQUEST['admin_password'])) $admin_password	= $_REQUEST['admin_password'];
if (isset($_REQUEST['standarduser_email'])) $standarduser_email  = $_REQUEST['standarduser_email'];
if (isset($_REQUEST['standarduser_password'])) $standarduser_password = $_REQUEST['standarduser_password'];
if (isset($_REQUEST['currency_name'])) $currency_name	= $_REQUEST['currency_name'];
if (isset($_REQUEST['currency_code'])) $currency_code	= $_REQUEST['currency_code'];
if (isset($_REQUEST['currency_symbol'])) $currency_symbol	= $_REQUEST['currency_symbol'];

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>vtiger CRM 5 - Configuration Wizard - Finish</title>

<script type="text/javascript">
function showhidediv()
{
	var div_style = document.getElementById("htaccess_div").style.display;
	if(div_style == "inline")
		document.getElementById("htaccess_div").style.display = "none";
	else
		document.getElementById("htaccess_div").style.display = "inline";
		
}
</script>

<link href="include/install/install.css" rel="stylesheet" type="text/css">
</head>

<body class="small cwPageBg" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">

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
					<tr><td class="small cwUnSelectedTab" align=right><div align="left">System Configuration</div></td></tr>
					<tr><td class="small cwUnSelectedTab" align=right><div align="left">Confirm Settings</div></td></tr>
					<tr><td class="small cwUnSelectedTab" align=right><div align="left">Config File Creation</div></td></tr>
					<tr><td class="small cwUnSelectedTab" align=right><div align="left">Database Generation</div></td></tr>
					<tr><td class="small cwSelectedTab" align=right><div align="left"><b>Finish</b></div></td></tr>
					</table>
					
				</td>
				<td width=80% valign=top class="cwContentDisplay" align=left>
				<!-- Right side tabs -->
					<table border=0 cellspacing=0 cellpadding=10 width=100%>
					<tr><td class=small align=left><img src="include/install/images/confWizFinish.gif" alt="Configuration Completed" title="Configuration Completed"><br>
					  <hr noshade size=1></td></tr>

					<tr>
					<td align=center class="small" style="height:250px;"> 

<?php

	// Output html instead of plain text for the web
	$useHtmlEntities = true;

	require_once('install/5createTables.inc.php');

	
//populating forums data

//this is to rename the installation file and folder so that no one destroys the setup
$renamefile = uniqid(rand(), true);

//@rename("install.php", $renamefile."install.php.txt");
if(!@rename("install.php", $renamefile."install.php.txt"))
{
	if (@copy ("install.php", $renamefile."install.php.txt"))
       	{
        	 unlink($renamefile."install.php.txt");
     	}
	else
	{
		echo "<b><font color='red'>We strongly suggest you to rename the install.php file.</font></b>";
	}
}

//@rename("install/", $renamefile."install/");
if(!@rename("install/", $renamefile."install/"))
{
	if (@copy ("install/", $renamefile."install/"))
       	{
        	 unlink($renamefile."install/");
     	}
	else
	{
		echo "<br><b><font color='red'>We strongly suggest you to rename the install directory.</font></b><br>";
	}

}
//populate Calendar data


?>
		<table border=0 cellspacing=0 cellpadding=5 align="center" width="80%" style="background-color:#E1E1FD;border:1px dashed #111111;">
		<tr>
			<td align=center class=small>
			<b>vtigercrm-5.1.0 is all set to go!</b>
			<hr noshade size=1>
			<div style="width:100%;padding:10px; "align=left>
			<ul>
			<li>Your install.php file has been renamed to <?php echo $renamefile;?>install.php.txt.
			<li>Your install folder too has been renamed to <?php echo $renamefile;?>install/.  
			<li>Please log in using the "admin" user name and the password you entered in step 2.
			<li>Do not forget to set the outgoing emailserver, setup accessible from Settings-&gt;Outgoing Server
			</ul>
			<ul>
			<li>Rename htaccess.txt file to .htaccess to control public file access. &nbsp;
			   <a href="javascript:;" onclick="showhidediv();">More Information</a>
			   <div id='htaccess_div' style="display:none">
				<br><br>This .htaccess file will work if "<b>AllowOverride All</b>" is set on Apache server configuration file (httpd.conf) for the DocumentRoot or for the current vtiger path.
			       	<br>If this AllowOverride is set as None ie., "<b>AllowOverride None</b>" then .htaccess file will not take into effect. 
				<br><br>If AllowOverride is None then add the following configuration in the apache server configuration file (httpd.conf) 
				<br><b>&lt;Directory "C:/Program Files/vtigercrm/apache/htdocs/vtigerCRM"&gt;<br>Options -Indexes<br>&lt;/Directory&gt;</b>
				<br>So that without .htaccess file we can restrict the directory listing
			   </div>
			</ul>
			<ul>
			<li><b><font color='#0000FF'>You are very important to us!</font></b>
<li><b> We take pride in being associated with you</li></b>
			<p>
			<b>Talk to us at <a href='http://forums.vtiger.com' target="_blank">forums</a></b>
			<p>
			<b>Discuss with us at <a href='http://blogs.vtiger.com' target="_blank">blogs</a></b>
			<p>
			<b>We aim to be - simply the best. Come on over, there is space for you too!</b>
			</ul>
			</div>

			</td>
		</tr>
		</table>
		<br>	
		<table border=0 cellspacing=0 cellpadding=10 width=100%>
		<tr><td colspan=2 align="center">
				 <form action="index.php" method="post" name="form" id="form">
				 <input type="hidden" name="default_user_name" value="admin">
			 	 <input  type="image" src="include/install/images/cwBtnFinish.gif" name="next" title="Finish" value="Finish" />
				 </form>
		</td></tr>
		</table>		
		</td>

		</tr>
		</table>
		<!-- Master display stops -->
		
	</td>
	</tr>
	</table>
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
</body>
</html>	
