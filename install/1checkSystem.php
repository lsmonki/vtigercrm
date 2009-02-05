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
if(isset($_REQUEST['filename'])){
	$file_name = $_REQUEST['filename'];
}

$writable_files_folders = array(
'Configuration File'=>'./config.inc.php',
'Tabdata File'=>'./tabdata.php',
'Installation File'=>'./install.php',
'Parent Tabdata File'=>'./parent_tabdata.php',
'Cache Directory'=>'./cache/',
'Storage Directory'=>'./storage/',
'Install Directory'=>'./install/',
'User Privileges Directory'=>'./user_privileges/',
'Smarty Cache Directory'=>'./Smarty/cache/',
'Smarty Compile Directory'=>'./Smarty/templates_c/',
'Email Templates Directory'=>'./modules/Emails/templates/',
'Modules Directory'=>'./modules/',
'Cron Modules Directory'=>'./cron/modules/',
'Vtlib Test Directory'=>'./test/vtlib/',
'Backup Directory'=>'./backup/',
'Smarty Modules Directory'=>'./Smarty/templates/modules/',
'Mail Merge Template Directory'=>'./test/wordtemplatedownload/',
'Product Image Directory'=>'./test/product/',
'User Image Directory'=>'./test/user/',
'Contact Image Directory'=>'./test/contact/',
'Logo Directory'=>'./test/logo/',
'Logs Directory'=>'./logs/',
'Webmail Attachments Directory'=>'./modules/Webmails/tmp/'); 

foreach($writable_files_folders as $index=>$value){
if(!is_writable($value)){
	$failed_permissions[$index]=$value;	
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

	<br>
	<!-- Table for cfgwiz starts -->

	<table border=0 cellspacing=0 cellpadding=0 width=80% align=center>
	<tr>
		<td class="cwHeadBg" align=left><img src="include/install/images/configwizard.gif" alt="Configuration Wizard" hspace="20" title="Configuration Wizard"></td>
		<td class="cwHeadBg1" align=right><img src="include/install/images/vtigercrm5.gif" alt="vtiger CRM 5" title="vtiger CRM 5"></td>
		<td class="cwHeadBg1" width=2%></td>
	</tr>
	</table>
	<table border=0 cellspacing=0 cellpadding=0 width=80% align=center>
	<tr>
		<td background="include/install/images/topInnerShadow.gif" colspan=2 align=left><img src="include/install/images/topInnerShadow.gif" ></td>

	</tr>
	</table>
	<table border=0 cellspacing=0 cellpadding=10 width=80% align=center>
	<tr>
		<td class="small" bgcolor="#4572BE" align=center>
			<!-- Master display -->
			<table border=0 cellspacing=0 cellpadding=0 width=97%>
			<tr>
				<td width=80% valign=top class="cwContentDisplay" align=center>
				<!-- Right side tabs -->
				    <table cellspacing=0 cellpadding=2 width=95% align=center>
				    <tr>
				    <td align=left><img src="include/install/images/confWizInstallCheck.gif" alt="Pre Installation Check" title="Pre Installation Check"><br>
					  </td>
					<td align=right valign="middle">
							<form action="install.php" method="post" name="form" id="form">
							<input type="hidden" name="filename" value="<?php echo $file_name; ?>" />	
							<input type="hidden" name="file" value="1checkSystem.php" />	
					        <input type="image" src="include/install/images/checkagain_blue2.png" value='Refresh' alt="Refresh" border="0" title="Refresh" style="cursor:pointer;" onClick="submit();">
							</form>
					</td>  
					</tr>
					<tr><td colspan=2><hr noshade size=1></td></tr>
				    <tr>
				    	<td colspan=2>
				    		<table cellpadding="0" cellspacing="1" align=right width="100%" class="level3">
				    			<tr>
				    			<td colspan=2 style="font-size:13;">
				    				<strong>Pre-Installation Check :</strong>
				    				<hr size="1" noshade=""/>
				    			</td>
				    			</tr>
				    			<tr >
								    <td width=50%  valign=top >
										<table cellpadding="5" cellspacing="1" align=right width="100%" border="0" class="level1">
															<tr class='level1'>
																<td valign=top >PHP version >= 5.0</td>
																<td  valign=top><?php $php_version = phpversion(); echo (str_replace(".", "", $php_version) < "430") ? "<strong><font color=\"Red\">No.</strong></font>" : "<strong><font color=\"#46882B\">$php_version</strong></font>"; ?></td>
															</tr>
															<tr class='level1'>
																<td valign=top >IMAP Support</td>
								        						<td valign=top><?php echo function_exists('imap_open')?"<strong><font color=\"#46882B\">Yes</strong></font>":"<strong><font color=\"#FF0000\">No</strong></font>";?></td>
															</tr>
															<tr class='level1'>
																<td valign=top >Zlib Support</td>
								        						<td valign=top><?php echo function_exists('gzinflate')?"<strong><font color=\"#46882B\">Yes</strong></font>":"<strong><font color=\"#FF0000\">No</strong></font>";?></td>
															</tr>
															<tr class='level1'>
																<td valign=top >GD graphics library
																<td valign=top><?php
																	if (!extension_loaded('gd')) {
																		echo "<strong><font size=-1 color=\"#FF0000\">Not configured. </strong></font>";
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
																			echo "<strong><font color=\"#46882B\">Yes</strong></font>";							
																		}
																		else {
																			echo "<strong><font size=-1 color=\"#FF0000\">No</font>";
																		}
																	}
																?>
																</td>
															</tr>
									</table>  
								    </td>
									<td align=left width=50% valign=top>
										<table cellpadding="5" cellspacing="1" align=right width="100%" border="0" class="level1">

									<?php
										if(!empty($failed_permissions)) {
									?>
									<tr class='level1'><td colspan=2><strong><span style="color:Black;">Read/Write Access</span></strong></td></tr>
									<?php
										
										foreach($failed_permissions as $index=>$value) {
									?>
															<tr class='level1'>
																<td valign=top ><?php echo $index; ?> (<?php echo str_replace("./","",$value); ?>)</td>
								        						<td valign=top><font color="red"><strong>No</strong></font></td>
															</tr>
									<?php
										}
										}
									?>
				       				</table>
								<br>
							</td>
						</tr>
		    			<tr>
			    			<td colspan=2 style="font-size:13;">
			    				<strong>PHP Configuration Check :</strong>
			    				<hr size="1" noshade=""/>
			    			</td>
		    			</tr>
		    			<tr>
							<?php 
								$directive_recommended = array(
									'safe_mode'=>'Off',
									'display_errors'=>'On',
									'file_uploads'=>'On',
									'register_globals'=>'On',
									'output_buffering'=>'Off',
									'max_execution_time'=>'600',
									'memory_limit'=>'32',
									'error_reporting'=>'E_WARNING & ~E_NOTICE',
									'allow_call_time_pass_reference'=>'On',
									'log_errors'=>'Off',
									'short_open_tag'=>'On'
								);
								$directive_array = array();
								if(ini_get('safe_mode') == '1') $directive_array['safe_mode'] = 'On';
								if(ini_get('display_errors') != '1') $directive_array['display_errors'] = 'Off';
								if(ini_get('file_uploads') != '1') $directive_array['file_uploads'] = 'Off';
								if(ini_get('register_globals') == '1') $directive_array['register_globals'] = 'On';
								if(ini_get('output_buffering') != '4096') $directive_array['output_buffering'] = 'Off';
								if(ini_get('max_execution_time') < 600) $directive_array['max_execution_time'] = ini_get('max_execution_time');
								if(ini_get('memory_limit') < 32) $directive_array['memory_limit'] = ini_get('memory_limit');
								if(ini_get('error_reporting') != '2') $directive_array['error_reporting'] = 'NOT RECOMMENDED';
								if(ini_get('allow_call_time_pass_reference') != '1') $directive_array['allow_call_time_pass_reference'] = 'Off';
								if(ini_get('log_errors') == '1') $directive_array['log_errors'] = 'On';
								if(ini_get('short_open_tag') != '1') $directive_array['short_open_tag'] = 'Off';
								
								if(!empty($directive_array)){					
							?>
							<td align=left colspan=2 width=100%>
					   	   		<!-- Recommended Settings -->
								<table cellpadding="5" cellspacing="1"  width="100%" border="0" class="level1">
								    <tr> <td valign=top ><strong>Directive</strong> </td><td><strong>Recommended</strong></td><td nowrap><strong>PHP.ini value</strong></td></tr>
								    <?php
								    	foreach($directive_array as $index=>$value){
								    ?>
								    <tr> 
								    	<td valign=top ><?php echo $index; ?></td>
								    	<td><?php echo $directive_recommended[$index]; ?></td>
								    	<td><strong><font color = red><?php echo $value; ?></font></strong></td></tr>
								    <?php
										}
								    ?>
								</table>
							</td>
							<?php
								}
							?>
						</tr>
					</table>
				</td>
			</tr>
			<tr valign=top>
				<td align=left >
					<input type="image" src="include/install/images/cwBtnBack.gif" alt="Back" border="0" title="Back" onClick="window.history.back();">
					
					</td>
				<td align=right>
					<form action="install.php" method="post" name="form" id="form">
	                <?php echo '<input type="hidden" name="file" value="'.$file_name.'" />'; ?>
					<input type="image" src="include/install/images/cwBtnNext.gif" alt="Next" border="0" title="Next" onClick="window.document.form.submit();">
					</form>
				    </td>
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
