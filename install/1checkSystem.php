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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>vtiger CRM 5.0 Alpha5 Installer: Step 1</title>
<link rel="stylesheet" href="install/install.css" type="text/css" />
</head>
<body leftMargin="0" topMargin="0" marginheight="0" marginwidth="0" class="">

<!-- Master table -->
<table border=0 cellspacing=0 cellpadding=0 width=100%>
<tr>
	<td align=center>
	<br><br>
	<!--  Top Header -->
	<table border="0" cellspacing="0" cellpadding="0" width="80%" style="background:url(install/images/cwTopBg.gif) repeat-x;">
	<tr>
		<td><img src="install/images/cwTopLeft.gif" alt="vtiger CRM" title="vtiger CRM"></td>
		<td align=right><img src="install/images/cwTopRight.gif" alt="v5alpha5" title="v5alpha5"></td>
	</tr>
	</table>
	
	
	
	<!-- 1 of 5 header -->
	<table border="0" cellspacing="0" cellpadding="5" width="75%" class=small> 
	<tr>	
		<td valign=top><img src="install/images/cwIcoSystem.gif" alt="System Check" title="System Check"></td>
		<td width=98% valign=top>
			<table border=0 cellspacing=0 cellpadding=0 width=100%>
			<tr>
				<td><img src="install/images/cwHdrVtConfWiz.gif" alt="vtiger CRM Configuration Wizard" title="vtiger CRM Configuration Wizard"></td>
				<td align=right><img src="install/images/cwStep1of5.gif" alt="Step 1 of 5" title="Step 1 of 5"></td>
			</tr>
			<tr>
				<td colspan=2><img src="install/images/cwHdrSysCheck.gif" alt="System Check" title="System Check"></td>
			</tr>
			</table>
			<hr noshade size=1>
		</td>

	</tr>
	<tr>
		<td></td>
		<td valign="top" align=center>
		<!-- System Check -->
	    <table cellpadding="10" cellspacing="1" width="90%" border="0" class="small" style="background-color:#cccccc">
		<tr bgcolor="#efefef"><td colspan=2><span style="color:#003399"><strong>Core Components</strong></span></td></tr>
		<tr bgcolor="#fafafa">
			<td valign=top ><strong>PHP version 5.0.x</strong><BR>(Note: Charts are not supported in PHP5)</td>
			<td  valign=top bgcolor="white"><?php $php_version = phpversion(); echo (str_replace(".", "", $php_version) < "430") ? "<strong><font color=\"#FF0000\">Failed.</strong><br> Invalid version ($php_version) Installed</font>" : "<strong><font color=\"#0066CC\">Passed</strong><br>Version $php_version Installed</font>"; ?></td>
    	</tr>
	<tr bgcolor="#fafafa">
			<td valign=top ><strong>IMAP Support Availability</strong></td>
        	<td valign=top bgcolor=white><?php echo function_exists('imap_open')?"<strong><font color=\"#0066CC\">Passed</strong><br>IMAP library available</font>":"<strong><font color=\"#FF0000\">Failed</strong><br>Not Available</font>";?></td>
	    </tr>
		<tr bgcolor="#fafafa">
			<td valign=top ><strong>GD graphics library</strong><br> version 2.0 or later</td>
			<td valign=top bgcolor="white"><?php
								if (!extension_loaded('gd')) {
									echo "<strong><font size=-1 color=\"#FF0000\">GD Graphics Library not configured. </strong>.<br>Check out our <a href='http://sourceforge.net/docman/?group_id=107819'>online documentation</a> for tips on enabling this library. You can ignore this error and continue your vtiger CRM installation, however the chart images simply won't work.</font>";
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
											echo "<strong><font color=\"#0066CC\">Passed</strong><br>Version $gd_version Installed</font>";
										}
										else {
											echo "<strong><font color=\"#0066CC\">Passed</strong><br>Version $gd_version Installed.</font>";
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
			<td valign=top bgcolor="white" ><?php echo (is_writable('./config.inc.php') || is_writable('.'))?"<strong><font color=\"#0066CC\">Writeable</font>":"<strong><font color=\"#FF0000\">Failed</strong><br>Not Writeable</font>"; ?></td>
		</tr>
		 <tr bgcolor="#fafafa">
		    <td valign=top ><strong>Cache Directory </strong> <br>(cache/)</td>
            <td valign=top bgcolor="white" ><?php echo (is_writable('./cache/'))?"<strong><font color=\"#0066CC\">Writeable</font></strong>":"<strong><font color=\"#FF0000\">Not Writeable</font></strong>"; ?></td>
        </tr>
		 <tr bgcolor="#fafafa">
		    <td valign=top ><strong>Mail Merge Template Directory </strong><br>(test/wordtemplatedownload/)</td>
            <td valign=top bgcolor="white"><?php echo (is_writable('./test/wordtemplatedownload/'))?"<strong><font color=\"#0066CC\">Writeable</font></strong>":"<strong><font color=\"#FF0000\">Not Writeable</strong><br> You might experience issues with the word template feature. Visit <a href=\"http://www.vtiger.com/discussions/viewtopic.php?p=2200#2200\" target=\"_blank\">forums</a> for more details </font>"; ?></td>
        </tr>
		<tr bgcolor="#fafafa">
		    <td valign=top ><strong>Uploads Directory</strong><br> (storage/)</td>
            <td valign=top bgcolor="white"><?php echo (is_writable('./storage/'))?"<strong><font color=\"#0066CC\">Writeable</font></strong>":"<strong><font color=\"#FF0000\">Not Writeable</strong> <br>You might experience problems with the file attachments feature. Refer <a href=http://www.vtiger.com/forums/viewtopic.php?t=24&highlight=permission>File attachments issue</a> for more details </font>"; ?></td>
        </tr>
		<tr bgcolor="#fafafa">
		    <td valign=top ><strong>Install Directory</strong><br> (install/)</td>
            <td valign=top bgcolor="white"><?php echo (is_writable('./install/'))?"<strong><font color=\"#0066CC\">Writeable</font></strong>":"<strong><font color=\"#FF0000\">Not Writeable</strong> <br>You might experience problems with the last step of installation.</font>"; ?></td>
        </tr>
		<tr bgcolor="#fafafa">
		    <td valign=top ><strong>Installation file</strong><br> (install.php)</td>
            <td valign=top bgcolor="white"><?php echo (is_writable('./install.php'))?"<strong><font color=\"#0066CC\">Writeable</font></strong>":"<strong><font color=\"#FF0000\">Not Writeable</strong> <br>You might experience problems with the last step of installation.</font>"; ?></td>
        </tr>
	<tr bgcolor="#fafafa">
		<td valign=top ><strong>Product Image Directory</strong><br> (test/product/)</td>
		<td valign=top bgcolor="white"><?php echo (is_writable('./test/product/'))?"<strong><font color=\"#0066CC\">Writeable</font></strong>":"<strong><font color=\"#FF0000\">Not Writeable</strong> <br>You might experience problems while attaching image for Products.Refer <a href=http://www.vtiger.com/forums/viewtopic.php?t=24&highlight=permission>File attachments issue</a> for more details </font>"; ?></td>
	</tr>
	<tr bgcolor="#fafafa">
		<td valign=top ><strong>User Image Directory</strong><br> (test/user/)</td>
		<td valign=top bgcolor="white"><?php echo (is_writable('./test/user/'))?"<strong><font color=\"#0066CC\">Writeable</font></strong>":"<strong><font color=\"#FF0000\">Not Writeable</strong> <br>You might experience problems while attaching image for Users. Refer <a href=http://www.vtiger.com/forums/viewtopic.php?t=24&highlight=permission>File attachments issue</a> for more details </font>"; ?></td>
	</tr>
	<tr bgcolor="#fafafa">
		<td valign=top ><strong>Contact Image Directory</strong><br> (test/contact/)</td>
		<td valign=top bgcolor="white"><?php echo (is_writable('./test/contact/'))?"<strong><font color=\"#0066CC\">Writeable</font></strong>":"<strong><font color=\"#FF0000\">Not Writeable</strong> <br>You might experience problems while attaching image for Contacts. Refer <a href=http://www.vtiger.com/forums/viewtopic.php?t=24&highlight=permission>File attachments issue</a> for more details </font>"; ?></td>
	</tr>
		<tr bgcolor="#fafafa">
		    <td valign=top ><strong>Logo Directory</strong><br> (test/logo/)</td>
            <td valign=top bgcolor="white"><?php echo (is_writable('./test/logo/'))?"<strong><font color=\"#0066CC\">Writeable</font></strong>":"<strong><font color=\"#FF0000\">Not Writeable</strong> <br>You might experience problems with the company logo in the pdf generation. Refer <a href=http://www.vtiger.com/forums/viewtopic.php?t=24&highlight=permission>File attachments issue</a> for more details </font>"; ?></td>
        </tr>

		<tr bgcolor="#fafafa">
		    <td valign=top ><strong>Email Templates Directory</strong><br> (modules/Emails/templates/)</td>
			<td valign=top bgcolor="white"><?php echo (is_writable('./modules/Emails/templates/'))?"<strong><font color=\"#0066CC\">Writeable</font></strong>":"<strong><font color=\"#FF0000\">Not Writeable</strong><br> You might experience problems with the email templates feature. Refer <a href= http://www.vtiger.com/forums/viewtopic.php?t=388&highlight=permission>Email templates issue </a> for more details  </font>"; ?></td>
		</tr>
		<tr bgcolor="#fafafa">
           <td valign=top ><strong>User Privileges</strong><br> (user_privileges/)</td>
           <td valign=top bgcolor="white"><?php echo (is_writable('./user_privileges/'))?"<strong><font color=\"#0066CC\">Writeable</font></strong>":"<strong><font color=\"#FF0000\">Not Writeable</strong><br> You will not be able to login </font>"; ?></td>
        </tr>
	
	<tr bgcolor="#fafafa">
                <td valign=top ><strong>Smarty Compile Directory </strong><br> (Smarty/templates_c)</td>
                <td valign=top bgcolor="white"><?php echo (is_writable('./Smarty/templates_c/'))?"<strong><font color=\"#0066CC\">Writeable</font></strong>":"<strong><font color=\"#FF0000\">Not Writeable</strong> <br>You will not be able to login </font>";?></td>
        </tr>
	
	<tr bgcolor="#fafafa">
                <td valign=top ><strong>Tabdata File Permission </strong><br> (Smarty/templates_c)</td>
                <td valign=top bgcolor="white"><?php echo (is_writable('./tabdata.php'))?"<strong><font color=\"#0066CC\">Writeable</font></strong>":"<strong><font color=\"#FF0000\">Not Writeable</strong> <br>You will not be able to work with the product</font>";?></td>
        </tr>

	<tr bgcolor="#fafafa">
                <td valign=top ><strong>ParentTabdata File Permission </strong><br> (Smarty/templates_c)</td>
                <td valign=top bgcolor="white"><?php echo (is_writable('./parent_tabdata.php'))?"<strong><font color=\"#0066CC\">Writeable</font></strong>":"<strong><font color=\"#FF0000\">Not Writeable</strong> <br>You will not be able to work with the product</font>";?></td>
        </tr>

	
	<tr bgcolor="#fafafa">
                <td valign=top ><strong>Smarty Cache Directory </strong><br> (Smarty/cache)</td>
                <td valign=top bgcolor="white"><?php echo (is_writable('./Smarty/cache/'))?"<strong><font color=\"#0066CC\">Writeable</font></strong>":"<strong><font color=\"#FF0000\">Not Writeable</strong>";?></td>
        </tr>

       </tbody>
	   </table>

<br><br>
	   
	   <!-- Recommended Settings -->
		<table cellpadding="10" cellspacing="1" width="90%" border="0" class="small" style="background-color:#cccccc">
		<tr bgcolor="#efefef"><td colspan=2><span style="color:#003399"><strong>Recommended Settings: We strongly suggest that you check for the following values in your php.ini file </strong></span></td></tr>
		<tr bgcolor="#ffffff"> <td valign=top ><strong>Safe Mode Off</strong></tr>
		<tr bgcolor="#ffffff"> <td valign=top ><strong>Display Errors On</strong></tr>
		<tr bgcolor="#ffffff"> <td valign=top ><strong>File Uploads On</strong></tr>
		<tr bgcolor="#ffffff"> <td valign=top ><strong>Register Globals Off</strong></tr>
		<tr bgcolor="#ffffff"> <td valign=top ><strong>Max Execution Time 300</strong></tr>
		<tr bgcolor="#ffffff"> <td valign=top ><strong>output_buffering= On</strong></tr>
		<tr bgcolor="#ffffff"> <td valign=top ><strong>Change the memory limit = 16M</strong></tr>
		<tr bgcolor="#ffffff"> <td valign=top ><strong>error_reporting = E_WARNING & ~E_NOTICE</strong></tr>
		<tr bgcolor="#ffffff"> <td valign=top ><strong>allow_call_time_reference = On</strong></tr>
		<tr bgcolor="#ffffff"> <td valign=top ><strong>output_buffering= On</strong></tr>
		<tr bgcolor="#ffffff">  <td valign=top ><a href="http://www.vtiger.com/products/crm/help/vtiger_CRM_Linux_Dependencies.pdf">Linux installation pre-requisites</a></tr>
		</table>

	</td>
</tr>
</table>

	<br><br>

	
	<table border=0 cellspacing=0 cellpadding=0 width=70% class=small>
	<tr>
		<td><img src="install/images/cwURL.gif"></td>
		<td align=right>
			<form action="install.php" method="post" name="form" id="form">
			<input type="hidden" name="file" value="2setConfig.php" />
			<input type="image" src="install/images/cwBtnNext.gif" border="0" onClick="window.location=('install.php')">
		</td>
	</tr>
	</table>
	<br><br>
	<!-- Horizontal Shade -->
	<table border="0" cellspacing="0" cellpadding="0" width="80%" style="background:url(install/images/cwShadeBg.gif) repeat-x;">
	<tr>
		<td><img src="install/images/cwShadeLeft.gif"></td>
		<td align=right><img src="install/images/cwShadeRight.gif"></td>
	</tr>
	</table>


</td>
</tr>
</table>
<!-- Master table closes -->

<br><br><br>
<!--
<table width="75%" border="0" cellpadding="3" cellspacing="0" align="center" style="border-bottom: 1px dotted #CCCCCC;"><tbody>
  <tr>
      <td align="left"><a href="http://www.vtiger.com" target="_blank" title="vtiger CRM"><IMG alt="vtiger CRM" border="0" src="include/images/vtiger_crmlogo.gif"/></a></td>
      <td align="right"><h2>Step 1 of 5</h2></td>
      <td align="right"><IMG alt="vtiger CRM" border="0" src="include/images/spacer.gif" width="10" height="1"/></td>
    </tr>
</tbody></table>
<table width="75%" align="center" border="0" cellpadding="10" cellspacing="0" border="0"><tbody>
    <tr>
      <td width="100%" colspan="3">
		<table width=100% cellpadding="0" cellspacing="0" border="0"><tbody><tr>
			  <td>
			   <table cellpadding="0" cellspacing="0" border="0"width="100%" ><tbody><tr>

				<td><h3>System Check</h3></td>
				</tr>
		    	</tbody></table>
			  </td>
			  <td width="85%" align="right"><hr width="100%"></td>
			  </tr>
		</tbody></table>
	  </td>
    </tr>
    <tr><td colspan="3" align="center"><br>
	</td>
		</tr>
       </tbody></table>
	    <table cellpadding="5" cellspacing="1" width="75%" border="0" align="center"><tbody>

	<tr>
       <td colspan="3" align="right">
	    <form action="install.php" method="post" name="form" id="form">
		<input type="hidden" name="file" value="2setConfig.php" />
		<input class="button" type="submit" name="next" value="Next >" /></td>
    </tr>
	</tbody>
-->
</form>
</body>
</html>
