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
<title>vtiger CRM 4.2 Installer: Step 1</title>
<link rel="stylesheet" href="install/install.css" type="text/css" />
</head>
<body leftMargin="0" topMargin="0" marginheight="0" marginwidth="0" class="">
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
	    <table cellpadding="1" cellspacing="1" border="0" width="75%"><tbody>
				<tr>
			<td bgcolor="#EEEEEE">
	    <table cellpadding="5" cellspacing="1" width="100%" border="0"><tbody>
		<tr>
			<td bgcolor="#EEEEEE" width="60%"><strong>PHP version 4.2.x or 4.3.x.<BR><em><LI><font size=-2>NOTE: Charts are not supported in PHP5</font></em></strong></td>

			<td align="right"><?php $php_version = phpversion(); echo (str_replace(".", "", $php_version) < "420") ? "<strong><font color=\"#FF0000\">Invalid version ($php_version) Installed</font></strong>" : "<strong><font color=\"#0066CC\">Version $php_version Installed</font></strong>"; ?></td>

    	</tr>
		<tr>
			<td bgcolor="#EEEEEE"><strong>Database</strong></td>

        	<td align="right"><?php echo function_exists('mysql_connect')?"<strong><font color=\"#0066CC\">Available</font></strong>":"<strong><font color=\"#FF0000\">Not Available</font></strong>";?></td>

	    </tr>
		<tr>
			<td bgcolor="#EEEEEE"><strong>config.php</strong></td>

			<td align="right"><?php echo (is_writable('./config.php') || is_writable('.'))?"<strong><font color=\"#0066CC\">Writeable</font></strong>":"<strong><font color=\"#FF0000\">Not Writeable</font></strong>"; ?></td>
		</tr>
		 <tr>
		    <td bgcolor="#EEEEEE"><strong>Cache Directory (cache/)</strong></td>

                    <td align="right"><?php echo (is_writable('./cache/'))?"<strong><font color=\"#0066CC\">Writeable</font></strong>":"<strong><font color=\"#FF0000\">Not Writeable</font></strong>"; ?></td>
                 </tr>
		<tr>
		 <tr>
		    <td bgcolor="#EEEEEE"><strong>Mail Merge Template Directory (test/wordtemplatedownload/)</strong></td>

                    <td align="right"><?php echo (is_writable('./test/wordtemplatedownload/'))?"<strong><font color=\"#0066CC\">Writeable</font></strong>":"<strong><font color=\"#FF0000\">Not Writeable<br> You might experience issues with the word template feature. You might visit the link for more details : <a href=\"http://www.vtiger.com/discussions/viewtopic.php?p=2200#2200\" target=\"_blank\">forums</a> </font></strong>"; ?></td>
                 </tr>
		<tr>
		 <tr>
		    <td bgcolor="#EEEEEE"><strong>Uploads Directory (test/upload/)</strong></td>

                    <td align="right"><?php echo (is_writable('./test/upload/'))?"<strong><font color=\"#0066CC\">Writeable</font></strong>":"<strong><font color=\"#FF0000\">Not Writeable <br>You might experience problems with the file attachments feature. You might visit the following link for more details : <a href=http://www.vtiger.com/forums/viewtopic.php?t=24&highlight=permission>attachment issue</a></font></strong>"; ?></td>
                 </tr>
		<tr>
		 <tr>
		    <td bgcolor="#EEEEEE"><strong>Email Templates Directory (modules/Emails/templates/)</strong></td>

                    <td align="right"><?php echo (is_writable('./modules/Emails/templates/'))?"<strong><font color=\"#0066CC\">Writeable</font></strong>":"<strong><font color=\"#FF0000\">Not Writeable. You might experience problems with the email templates feature. You might refer to the following link for more details :<a href= http://www.vtiger.com/forums/viewtopic.php?t=388&highlight=permission>email templates issue </a> </font></strong>"; ?></td>
                 </tr>
		<tr>
			<td bgcolor="#EEEEEE"><strong>GD graphics library version 2.0 or later</strong></td>

			<td align="right"><?php
								if (!extension_loaded('gd')) {
									echo "<strong><font size=-1 color=\"#FF0000\">GD Library not configured in your PHP installation.<br>Check out our <a href='http://sourceforge.net/docman/?group_id=107819'>online documentation</a> for tips on enabling this library. You can ignore this error and continue your vtiger CRM installation, however the chart images simply won't work.</font></strong>";
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
											echo "<strong><font color=\"#0066CC\">Version $gd_version Installed</font></strong>";
										}
										else {
											echo "<strong><font color=\"#0066CC\">Version $gd_version Installed.</font></strong>";
										}
									}
									else {
										echo "<strong><font size=-1 color=\"#FF0000\">GD Library available, but not properly configured in your PHP installation.<br>You can ignore this error and continue your vtiger CRM installation, however the chart images simply won't work.</font></strong>";
									}
								}
								?>
			</td>
		</tr>
       </tbody></table>
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

</form>
</body>
</html>
