<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
 ********************************************************************************/


global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once('include/database/PearDatabase.php');
require_once('include/utils/UserInfoUtil.php');
require_once('vtigerversion.php');
if($patch_version !='')
{
	    $patch_string = $vtiger_version . " Patch " . $patch_version;
}
else
{
	    $patch_string = "--None--";
}
global $app_strings;
global $app_list_strings;
global $mod_strings;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>vtiger CRM 5 - Free, Commercial grade Open Source CRM</title>
<link href="<? echo $theme_path;?>style.css" rel="stylesheet" type="text/css">
</head>
<style>
	.rollOver{
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:12px;
		border:0px solid white;
		width:100%;
		padding:0px;
	}
	
	.rollOver tr th{
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:12px;
		border:0px solid white;
		padding-left:10px;
		padding-bottom:5px;
		font-weight:bold;
		text-decoration:underline;
		color:#000000;
		text-align:left;
	}
	
	.rollOver tr td{
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:11px;
		border:0px solid white;
		padding-left:30px;
		font-weight:normal;
		text-decoration:none;
		color:#000000;
		text-align:left;
		padding-bottom:1px;
	}
	
	
	
</style>
<body>
<table border="0" cellpadding="0" cellspacing="0" width="500">
		<tr>
				<td colspan="3"><img src="<? echo $image_path;?>aboutUS.jpg" width="500" height="301"></td>
		</tr>
		<tr>
				<td width="15%" style="border-left:2px solid #7F7F7F;">&nbsp;</td>
				<td width="70%" style="border:3px solid #CCCCCC;" height="100" >
						<marquee behavior="scroll" direction="up" width="100%" scrollamount="1" scrolldelay="50"  height="100" onMouseOut="javascript:start();" onMouseOver="javascript:stop();">
								<table width="100%" border="0" cellpadding="0" cellspacing="0" class="rollOver">
										<tr><th>Team</th></tr>
										<tr><td>Ahmed</td></tr>
										<tr><td>Don</td></tr>
										<tr><td>Ela</td></tr>
										<tr><td>Gopal</td></tr>
										<tr><td>Jeri</td></tr>
										<tr><td>Mani</td></tr>
										<tr><td>Mickie</td></tr>
										<tr><td>Minnie</td></tr>
										<tr><td>Philip</td></tr>
										<tr><td>Richie</td></tr>
										<tr><td>Saint</td></tr>
										<tr><td>SRaj</td></tr>
										<tr><td>&nbsp;</td></tr>
										<tr><th>Credits</th></tr>
										<tr><td>Matthew Brichacek</td></tr>
										<tr><td>Michel JACQUEMES </td></tr>
										<tr><td>Mike Crowe </td></tr>
										<tr><td>Allan Bush</td></tr>
										<tr><td>Frank Piepiorra</td></tr>
										<tr><td>Dino Eberle </td></tr>
										<tr><td>Jamie Jackson</td></tr>
										<tr><td>Aissa Belaid</td></tr>
										<tr><td>Sergio A. Kessler</td></tr>
										<tr><td>Jeff Kowalczyk</td></tr>
										<tr><td>Brian Devendorf</td></tr>
										<tr><td>Brian Laughlin</td></tr>
										<tr><td>Dennis Grant</td></tr>
										<tr><td>Fathi Boudra</td></tr>
										<tr><td>Jamie Jackson</td></tr>
										<tr><td>Joel Rydbeck</td></tr>
										<tr><td>Josh Lee</td></tr>
										<tr><td>Mike Fedyk</td></tr>
										<tr><td>&nbsp;</td></tr>
										<tr><td><b>And vtiger Community</b></td></tr>
										<tr><td>&nbsp;</td></tr>
										<tr><td>&nbsp;</td></tr>
								</table>
						</marquee>
				</td>
				<td width="15%" style="border-right:2px solid #7F7F7F;">&nbsp;</td>
		</tr>
		<tr><td colspan="3"  style="border-left:2px solid #7F7F7F;border-right:2px solid #7F7F7F">&nbsp;</td></tr>
		<tr>
		  <td colspan="3" background="<? echo $image_path;?>about_btm.jpg" height="75">
		  		<table width="100%" border="0" cellpadding="5" cellspacing="0">
						<tr>
							<td width="70%" align="left">
									<span class="small" style="color:#999999;">Version : 5.0.0 rc</span>&nbsp;|&nbsp;
									<a href="http://www.vtiger.com/copyrights/LICENSE_AGREEMENT.txt" class="webMnu" target="_blank">Read License</a>&nbsp;|&nbsp;
									<a href="http://www.vtiger.com/index.php?option=com_content&task=view&id=26&Itemid=54" class="webMnu" target="_blank">Contact Us</a>
							</td>
							<td align="right">
									<input type="button" name="close" value=" &nbsp;Close&nbsp; " onClick="window.close();" class="classBtn">
							</td>
						</tr>
				</table>
		  </td>
  </tr>
</table>
</body>
</html>
