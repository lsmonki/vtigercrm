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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/install/1checkSystem.php,v 1.16 2005/03/08 12:01:36 samk Exp $
 * Description:  Executes a step in the installation process.
 ********************************************************************************/

//get php configuration settings.  requires elaborate parsing of phpinfo() output
?>
<html>
<?php


ob_start();
eval("phpinfo();");
$info = ob_get_contents();
ob_end_clean();

 foreach(explode("\n", $info) as $line) {
           if(strpos($line, "Client API version")!==false)
               $mysql_version = trim(str_replace("Client API version", "", strip_tags($line)));
 }









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

$gd_info_alternate = 'function gd_info() {
$array = Array(
                       "GD Version" => "",
                       "FreeType Support" => 0,
                       "FreeType Support" => 0,
                       "FreeType Linkage" => "",
                       "T1Lib Support" => 0,
                       "GIF Read Support" => 0,
                       "GIF Create Support" => 0,
                       "JPG Support" => 0,
                       "PNG Support" => 0,
                       "WBMP Support" => 0,
                       "XBM Support" => 0
                     );
       $gif_support = 0;

       ob_start();
       eval("phpinfo();");
       $info = ob_get_contents();
       ob_end_clean();

       foreach(explode("\n", $info) as $line) {
           if(strpos($line, "GD Version")!==false)
               $array["GD Version"] = trim(str_replace("GD Version", "", strip_tags($line)));
           if(strpos($line, "FreeType Support")!==false)
               $array["FreeType Support"] = trim(str_replace("FreeType Support", "", strip_tags($line)));
           if(strpos($line, "FreeType Linkage")!==false)
               $array["FreeType Linkage"] = trim(str_replace("FreeType Linkage", "", strip_tags($line)));
           if(strpos($line, "T1Lib Support")!==false)
               $array["T1Lib Support"] = trim(str_replace("T1Lib Support", "", strip_tags($line)));
           if(strpos($line, "GIF Read Support")!==false)
               $array["GIF Read Support"] = trim(str_replace("GIF Read Support", "", strip_tags($line)));
           if(strpos($line, "GIF Create Support")!==false)
               $array["GIF Create Support"] = trim(str_replace("GIF Create Support", "", strip_tags($line)));
           if(strpos($line, "GIF Support")!==false)
               $gif_support = trim(str_replace("GIF Support", "", strip_tags($line)));
           if(strpos($line, "JPG Support")!==false)
               $array["JPG Support"] = trim(str_replace("JPG Support", "", strip_tags($line)));
           if(strpos($line, "PNG Support")!==false)
               $array["PNG Support"] = trim(str_replace("PNG Support", "", strip_tags($line)));
           if(strpos($line, "WBMP Support")!==false)
               $array["WBMP Support"] = trim(str_replace("WBMP Support", "", strip_tags($line)));
           if(strpos($line, "XBM Support")!==false)
               $array["XBM Support"] = trim(str_replace("XBM Support", "", strip_tags($line)));
       }

       if($gif_support==="enabled") {
           $array["GIF Read Support"]  = 1;
           $array["GIF Create Support"] = 1;
       }

       if($array["FreeType Support"]==="enabled"){
           $array["FreeType Support"] = 1;    }

       if($array["T1Lib Support"]==="enabled")
           $array["T1Lib Support"] = 1;

       if($array["GIF Read Support"]==="enabled"){
           $array["GIF Read Support"] = 1;    }

       if($array["GIF Create Support"]==="enabled")
           $array["GIF Create Support"] = 1;

       if($array["JPG Support"]==="enabled")
           $array["JPG Support"] = 1;

       if($array["PNG Support"]==="enabled")
           $array["PNG Support"] = 1;

       if($array["WBMP Support"]==="enabled")
           $array["WBMP Support"] = 1;

       if($array["XBM Support"]==="enabled")
           $array["XBM Support"] = 1;

       return $array;

}';

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>vtiger CRM 5 - Configuration Wizard - Installation Check</title>
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
					<tr><td class="small cwSelectedTab" align=right><div align="left"><b>Installation Check</b></div></td></tr>
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
				    <tr><td class=small align=left><img src="include/install/images/confWizInstallCheck.gif" alt="Pre Installation Check" title="Pre Installation Check"><br>
					  <hr noshade size=1></td></tr>
				    <tr>
					<td align=left class="small" style="padding-left:20px">
	    				<table cellpadding="10" cellspacing="1" width="90%" border="0" class="small" style="background-color:#cccccc">
					<tr bgcolor="#efefef"><td colspan=2><span style="color:#003399"><strong>Core Components</strong></span></td></tr>
					<tr bgcolor="#fafafa">
						<td valign=top ><strong>PHP version</strong><BR></td>
						<td  valign=top bgcolor="white"><?php $php_version = phpversion(); echo (str_replace(".", "", $php_version) < "430") ? "<strong><font color=\"#FF0000\">Failed.</strong><br> Invalid version ($php_version) Installed</font>" : "<strong><font color=\"#00CC00\">Passed</strong><br>Version $php_version Installed</font>"; ?></td>
    					</tr>
					<tr bgcolor="#fafafa">
						<td valign=top ><strong>IMAP Support Availability</strong></td>
				        	<td valign=top bgcolor=white><?php echo function_exists('imap_open')?"<strong><font color=\"#00CC00\">Passed</strong><br>IMAP library available</font>":"<strong><font color=\"#FF0000\">Failed</strong><br>Not Available</font>";?></td>
					</tr>
					<tr bgcolor="#fafafa">
						<td valign=top ><strong>GD graphics library</strong><br> version 2.0 or later</td>
						<td valign=top bgcolor="white"><?php
						if (!extension_loaded('gd')) {
							echo "<strong><font size=-1 color=\"#FF0000\">GD Graphics Library not configured. </strong>.<br>Check out our <a target=\"_blank\" href='http://sourceforge.net/docman/?group_id=107819'>online documentation</a> for tips on enabling this library. You can ignore this error and continue your vtiger CRM installation, however the chart images simply won't work.</font>";
						}
						else {
							if (!function_exists('gd_info'))
							{
								eval($gd_info_alternate);
							}
							$gd_info = gd_info();

							if (isset($gd_info['GD Version'])) {
								$gd_version = $gd_info['GD Version'];
								$gd_version=preg_replace('%[^0-9.]%', '', $gd_version);

								if ($gd_version > "2.0") {
									echo "<strong><font color=\"#00CC00\">Passed</strong><br>Version $gd_version Installed</font>";
								}
								else {
									echo "<strong><font color=\"#00CC00\">Passed</strong><br>Version $gd_version Installed.</font>";
								}
							}
							else {
								echo "<strong><font size=-1 color=\"#FF0000\">GD Library available, but not properly configured in your PHP installation</strong>.<br>You can ignore this error and continue your vtiger CRM installation, however the chart images simply won't work.</font>";
							}
						}
						?>
						</td>
					</tr>
					<tr bgcolor="#efefef"><td colspan=2><strong><span style="color:#003399">Read/Write Access</span></strong></td></tr>
					<tr bgcolor="#fafafa">
						<td valign=top ><strong>PHP Configuration</strong><br>(config.inc.php)</strong></td>
						<td valign=top bgcolor="white" ><?php echo (is_writable('./config.inc.php') || is_writable('.'))?"<strong><font color=\"#00CC00\">Writeable</font>":"<strong><font color=\"#FF0000\">Failed</strong><br>Not Writeable</font>"; ?></td>
					</tr>
		 			<tr bgcolor="#fafafa">
						<td valign=top ><strong>Cache Directory </strong> <br>(cache/)</td>
            					<td valign=top bgcolor="white" ><?php echo (is_writable('./cache/'))?"<strong><font color=\"#00CC00\">Writeable</font></strong>":"<strong><font color=\"#FF0000\">Not Writeable</font></strong>"; ?></td>
        				</tr>
		 			
					<tr bgcolor="#fafafa">
		    				<td valign=top ><strong>Uploads Directory</strong><br> (storage/)</td>
            					<td valign=top bgcolor="white"><?php echo (is_writable('./storage/'))?"<strong><font color=\"#00CC00\">Writeable</font></strong>":"<strong><font color=\"#FF0000\">Not Writeable</strong> <br>You might experience problems with the file attachments feature. Refer <a target=\"_blank\"  href=http://www.vtiger.com/forums/viewtopic.php?t=24&highlight=permission>File attachments issue</a> for more details </font>"; ?></td>
        				</tr>
					<tr bgcolor="#fafafa">
		    				<td valign=top ><strong>Install Directory</strong><br> (install/)</td>
            					<td valign=top bgcolor="white"><?php echo (is_writable('./install/'))?"<strong><font color=\"#00CC00\">Writeable</font></strong>":"<strong><font color=\"#FF0000\">Not Writeable</strong> <br>You might experience problems with the last step of installation.</font>"; ?></td>
        				</tr>
					<tr bgcolor="#fafafa">
		    				<td valign=top ><strong>Installation file</strong><br> (install.php)</td>
            					<td valign=top bgcolor="white"><?php echo (is_writable('./install.php'))?"<strong><font color=\"#00CC00\">Writeable</font></strong>":"<strong><font color=\"#FF0000\">Not Writeable</strong> <br>You might experience problems with the last step of installation.</font>"; ?></td>
					</tr>
					<tr bgcolor="#fafafa">
				                <td valign=top ><strong>Tabdata File Permission </strong><br> (tabdata.php) </td>
				                <td valign=top bgcolor="white"><?php echo (is_writable('./tabdata.php'))?"<strong><font color=\"#00CC00\">Writeable</font></strong>":"<strong><font color=\"#FF0000\">Not Writeable</strong> <br>You will not be able to work with the product</font>";?></td>
				        </tr>

					<tr bgcolor="#fafafa">
				                <td valign=top ><strong>ParentTabdata File Permission </strong><br> (parent_tabdata.php) </td>
				                <td valign=top bgcolor="white"><?php echo (is_writable('./parent_tabdata.php'))?"<strong><font color=\"#00CC00\">Writeable</font></strong>":"<strong><font color=\"#FF0000\">Not Writeable</strong> <br>You will not be able to work with the product</font>";?></td>
				        </tr>

					<tr bgcolor="#fafafa">
           					<td valign=top ><strong>User Privileges</strong><br> (user_privileges/)</td>
           					<td valign=top bgcolor="white"><?php echo (is_writable('./user_privileges/'))?"<strong><font color=\"#00CC00\">Writeable</font></strong>":"<strong><font color=\"#FF0000\">Not Writeable</strong><br> You will not be able to login </font>"; ?></td>
        				</tr>

					
					<tr bgcolor="#fafafa">
                				<td valign=top ><strong>Smarty Cache Directory </strong><br> (Smarty/cache)</td>
                				<td valign=top bgcolor="white"><?php echo (is_writable('./Smarty/cache/'))?"<strong><font color=\"#00CC00\">Writeable</font></strong>":"<strong><font color=\"#FF0000\">Not Writeable</strong>";?></td>
					</tr>
					<tr bgcolor="#fafafa">
				                <td valign=top ><strong>Smarty Compile Directory </strong><br> (Smarty/templates_c)</td>
				                <td valign=top bgcolor="white"><?php echo (is_writable('./Smarty/templates_c/'))?"<strong><font color=\"#00CC00\">Writeable</font></strong>":"<strong><font color=\"#FF0000\">Not Writeable</strong> <br>You will not be able to login </font>";?></td>
					</tr>

					<tr bgcolor="#fafafa">
		    				<td valign=top ><strong>Email Templates Directory</strong><br> (modules/Emails/templates/)</td>
						<td valign=top bgcolor="white"><?php echo (is_writable('./modules/Emails/templates/'))?"<strong><font color=\"#00CC00\">Writeable</font></strong>":"<strong><font color=\"#FF0000\">Not Writeable</strong><br> You might experience problems with the email templates feature. Refer <a target=\"_blank\" href= http://www.vtiger.com/forums/viewtopic.php?t=388&highlight=permission>Email templates issue </a> for more details  </font>"; ?></td>
					</tr>
				
					<tr bgcolor="#fafafa">
		    				<td valign=top ><strong>Mail Merge Template Directory </strong><br>(test/wordtemplatedownload/)</td>
            					<td valign=top bgcolor="white"><?php echo (is_writable('./test/wordtemplatedownload/'))?"<strong><font color=\"#00CC00\">Writeable</font></strong>":"<strong><font color=\"#FF0000\">Not Writeable</strong><br> You might experience issues with the word template feature. Visit <a href=\"http://www.vtiger.com/discussions/viewtopic.php?p=2200#2200\" target=\"_blank\">forums</a> for more details </font>"; ?></td>
        				</tr>
				
					<tr bgcolor="#fafafa">
						<td valign=top ><strong>Product Image Directory</strong><br> (test/product/)</td>
						<td valign=top bgcolor="white"><?php echo (is_writable('./test/product/'))?"<strong><font color=\"#00CC00\">Writeable</font></strong>":"<strong><font color=\"#FF0000\">Not Writeable</strong> <br>You might experience problems while attaching image for Products.Refer <a href=http://www.vtiger.com/forums/viewtopic.php?t=24&highlight=permission target=\"_blank\">File attachments issue</a> for more details </font>"; ?></td>
					</tr>
					<tr bgcolor="#fafafa">
						<td valign=top ><strong>User Image Directory</strong><br> (test/user/)</td>
						<td valign=top bgcolor="white"><?php echo (is_writable('./test/user/'))?"<strong><font color=\"#00CC00\">Writeable</font></strong>":"<strong><font color=\"#FF0000\">Not Writeable</strong> <br>You might experience problems while attaching image for Users. Refer <a target=\"_blank\"  href=http://www.vtiger.com/forums/viewtopic.php?t=24&highlight=permission>File attachments issue</a> for more details </font>"; ?></td>
					</tr>
					<tr bgcolor="#fafafa">
						<td valign=top ><strong>Contact Image Directory</strong><br> (test/contact/)</td>
						<td valign=top bgcolor="white"><?php echo (is_writable('./test/contact/'))?"<strong><font color=\"#00CC00\">Writeable</font></strong>":"<strong><font color=\"#FF0000\">Not Writeable</strong> <br>You might experience problems while attaching image for Contacts. Refer <a target=\"_blank\" href=http://www.vtiger.com/forums/viewtopic.php?t=24&highlight=permission>File attachments issue</a> for more details </font>"; ?></td>
					</tr>	
					<tr bgcolor="#fafafa">
		    				<td valign=top ><strong>Logo Directory</strong><br> (test/logo/)</td>
					        <td valign=top bgcolor="white"><?php echo (is_writable('./test/logo/'))?"<strong><font color=\"#00CC00\">Writeable</font></strong>":"<strong><font color=\"#FF0000\">Not Writeable</strong> <br>You might experience problems with the company logo in the pdf generation. Refer <a target=\"_blank\" href=http://www.vtiger.com/forums/viewtopic.php?t=24&highlight=permission>File attachments issue</a> for more details </font>"; ?></td>
					</tr>
					<tr bgcolor="#fafafa">
						<td valign=top ><strong>Logs Directory</strong><br> (logs/)</td>
						<td valign=top bgcolor="white"><?php echo (is_writable('./logs/'))?"<strong><font color=\"#00CC00\">Writeable</font></strong>":"<strong><font color=\"#FF0000\">Not Writeable</strong> <br>System will experience problems while writing to the logs. You are strongly urged to give write permissions to the logs folder please!!!"; ?></td>
					</tr>
					<tr bgcolor="#fafafa">
						<td valign=top ><strong>WebMail attachments Directory</strong><br> (modules/Webmails/tmp/)</td>
						<td valign=top bgcolor="white"><?php echo (is_writable('./modules/Webmails/tmp/'))?"<strong><font color=\"#00CC00\">Writeable</font></strong>":"<strong><font color=\"#FF0000\">Not Writeable</strong> <br>System will experience problems in saving attachments in received mail. You are strongly urged to give write permissions to ./modules/Webmails/tmp/ folder please!!!"; ?></td>
					</tr>
       				</table>
				<br><br>
	   	   		<!-- Recommended Settings -->
				<table cellpadding="10" cellspacing="1" width="90%" border="0" class="small" style="background-color:#cccccc">
				<tr bgcolor="#efefef"><td colspan=2><span style="color:#003399"><strong>Recommended Settings: We strongly suggest that you check for the following values in your php.ini file </strong></span></td></tr>
				<tr bgcolor="#ffffff"> <td valign=top ><strong>Safe Mode Off</strong></tr>
				<tr bgcolor="#ffffff"> <td valign=top ><strong>Display Errors On</strong></tr>
				<tr bgcolor="#ffffff"> <td valign=top ><strong>File Uploads On</strong></tr>
				<tr bgcolor="#ffffff"> <td valign=top ><strong>Register Globals Off</strong></tr>
				<tr bgcolor="#ffffff"> <td valign=top ><strong>Max Execution Time 600</strong></tr>
				<tr bgcolor="#ffffff"> <td valign=top ><strong>output_buffering= On</strong></tr>
				<tr bgcolor="#ffffff"> <td valign=top ><strong>Change the memory limit = 64M</strong></tr>
				<tr bgcolor="#ffffff"> <td valign=top ><strong>error_reporting = E_ALL & ~E_NOTICE</strong></tr>
				<tr bgcolor="#ffffff"> <td valign=top ><strong>allow_call_time_pass_reference = On</strong></tr>
				<tr bgcolor="#ffffff"> <td valign=top ><strong>log_errors = Off</strong></tr>
				<tr bgcolor="#ffffff"> <td valign=top ><strong>short_open_tag= On</strong></tr>
				<tr bgcolor="#ffffff">  <td valign=top ><a href="http://www.vtiger.com/products/crm/help/5.1.0/vtiger_CRM_Linux_Dependencies.pdf" target="_blank">Linux installation pre-requisites</a></tr>
				</table>
			</td>
			</tr>
			<tr>
				<td align=center>
					<form action="install.php" method="post" name="installform" id="form">
			                <input type="hidden" name="file" value="2setConfig.php" />	
					<input type="image" src="include/install/images/cwBtnNext.gif" alt="Next" border="0" title="Next" onClick="window.document.installform.submit();">
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
