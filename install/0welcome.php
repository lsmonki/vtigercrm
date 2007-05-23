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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/install/0welcome.php,v 1.10 2004/08/26 11:44:30 saraj Exp $
 * Description:  Executes a step in the installation process.
 ********************************************************************************/

//get php configuration settings.  requires elaborate parsing of phpinfo() output
ob_start();
phpinfo(INFO_GENERAL);
$string = ob_get_contents();
ob_end_clean();

$pieces = explode("<h2", $string);
$settings = array();
foreach($pieces as $val)
{
   preg_match("/<a name=\"module_([^<>]*)\">/", $val, $sub_key);
   preg_match_all("/<tr[^>]*>
									   <td[^>]*>(.*)<\/td>
									   <td[^>]*>(.*)<\/td>/Ux", $val, $sub);
   preg_match_all("/<tr[^>]*>
									   <td[^>]*>(.*)<\/td>
									   <td[^>]*>(.*)<\/td>
									   <td[^>]*>(.*)<\/td>/Ux", $val, $sub_ext);
   foreach($sub[0] as $key => $val) {
		if (preg_match("/Configuration File \(php.ini\) Path /", $val)) {
	   		$val = preg_replace("/Configuration File \(php.ini\) Path /", '', $val);
			$phpini = strip_tags($val);
	   	}
   }

}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>vtiger CRM 5 - Configuration Wizard - Welcome</title>
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
					<tr><td class="small cwSelectedTab" align=right><div align="left"><b>Welcome</b></div></td></tr>
					<tr><td class="small cwUnSelectedTab" align=right><div align="left">Installation Check</div></td></tr>
					<tr><td class="small cwUnSelectedTab" align=right><div align="left">System Configuration</div></td></tr>
					<tr><td class="small cwUnSelectedTab" align=right><div align="left">Confirm Settings</div></td></tr>
					<tr><td class="small cwUnSelectedTab" align=right><div align="left">Config File Creation</div></td></tr>
					<tr><td class="small cwUnSelectedTab" align=right><div align="left">Database Generation</div></td></tr>
					<tr><td class="small cwUnSelectedTab" align=right><div align="left">Finish</div></td></tr>
					</table>
					
				</td>
				<td width=80% valign=top class="cwContentDisplay" align=left>
				<!-- Right side tabs -->
					<table border=0 cellspacing=0 cellpadding=10 width=100%>
					<tr><td class=small align=left><img src="include/install/images/welcome.gif" alt="Welcome to Configuration Wizard" title="Welcome to Configuration Wizard"><br>
					  <hr noshade size=1></td></tr>

					<tr>
						<td align=left class="small" style="padding-left:20px">
		<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This Configuration Wizard will create the requisite data needed to get working with vtiger CRM. The entire process should take about four minutes. Click the Start button when you are ready. 
<br><br>
<p><span style="color:#555555">- vtiger CRM 5.0.3 is tested on mySQL 4.1.X, mySQL 5.0.19, PHP 5.0.19 and Apache 2.0.40.</p> 
<p align="center"><font color='red'><b>vtiger CRM 5.0.3 will not work on mysql 4.0.x versions and PHP 5.2.x versions</b></font></center></p></font></center><center>
 
<font color='blue'><b>vtiger CRM can run on a system which has xampp/lampp/wampp already installed in it provided it meets the above mentioned requirements</b></font></center>
<p>The installation wizard will guide you with the installation regardless of the setup you may have.</span>
						
					  </td>
					</tr>
					<tr>
					<td><span style="color:#999999">Please take a moment to register your copy of vtiger CRM. Though this is optional, we encourage you to register. Only your name and email address are required for registration. We do not sell, rent, share or otherwise, distribute your information to third parties.<br></span>
		
					</td>
					</tr>
					<tr>
						<td align=center>
						<IFRAME src="http://www.vtiger.com/products/crm/registration.php" width="500" height=250 scrolling='no' frameborder="0">
						  [Your user agent does not support frames or is currently configured
						  not to display frames. However, you may visit
						  <A href="http://www.vtiger.com/products/crm/registration.php">the related document.</A>] 
						 </IFRAME>
				 		 </td>
					</tr>
					<tr>
						<td align="center">
							Please read the <a href="Copyright.txt" target="_BLANK">license agreement</a> and click the "Start" button to continue. 
						</td>
					</tr>		
					<tr>
						<td align=center>
						<form action="install.php" method="post" name="installform" id="form">
				                <input type="hidden" name="file" value="1checkSystem.php" />	
						<input type="image" src="include/install/images/start.jpg" alt="Start" border="0" title="Start" style="cursor:pointer;" onClick="window.document.installform.submit();">
						</form><br>
					    <br></td>
					</tr>
					
					
				</table>
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
</body>
</html>
