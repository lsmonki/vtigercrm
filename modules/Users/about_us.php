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
								<tr><th><?php echo $mod_strings['LBL_TEAM'];?></th></tr>
										<tr><td>Ahmed</td></tr>
										<tr><td>Don</td></tr>
										<tr><td>Ela</td></tr>
										<tr><td>Gopal</td></tr>
										<tr><td>Jeri</td></tr>
										<tr><td>Mani</td></tr>
										<tr><td>Mickie</td></tr>
										<tr><td>Minnie</td></tr>
										<tr><td>Philip</td></tr>
										<tr><td>Radiant</td></tr>
										<tr><td>Richie</td></tr>
										<tr><td>SRaj</td></tr>
										<tr><td>&nbsp;</td></tr>
										<tr><th><?php echo $mod_strings['LBL_CREDITS'];?></th></tr>
										<tr><td>Aissa Belaid</td></tr>
										<tr><td>Allan Bush</td></tr>
										<tr><td>Brian Devendorf</td></tr>
										<tr><td>Brian Laughlin</td></tr>
										<tr><td>Davide Giarolo</td></tr>	
										<tr><td>Dennis Grant</td></tr>
										<tr><td>Dhr. R.R. Gerbrands</td></tr>
										<tr><td>Dino Eberle</td></tr> 
										<tr><td>Dirk Gorny</td></tr>
										<tr><td>Fathi Boudra</td></tr>
										<tr><td>Frank Piepiorra</td></tr>
										<tr><td>Jamie Jackson</td></tr>
										<tr><td>Jeff Kowalczyk</td></tr>
										<tr><td>Jens Gammelgaard</td></tr>
										<tr><td>Jens Hamisch</td></tr>
										<tr><td>Joao Oliveira</td></tr>
										<tr><td>Joel Rydbeck</td></tr>
										<tr><td>Josh Lee</td></tr>
										<tr><td>Ken Lyle</td></tr>
										<tr><td>Kim Haverblad</td></tr>
										<tr><td>Manilal K M</td></tr>
										<tr><td>Matjaz Slak</td></tr>
										<tr><td>Matthew Brichacek</td></tr>
										<tr><td>Michel Jacquemes</td></tr> 
										<tr><td>Mike Crowe</td></tr> 
										<tr><td>Mike Fedyk</td></tr>
										<tr><td>Neil</td></tr>
										<tr><td>Tim Smith</td></tr>
										<tr><td>Sergio A. Kessler</td></tr>
										<tr><td>Valmir Carlos Trindade</td></tr>
										<tr><td>&nbsp;</td></tr>
										<tr><th><?php echo $mod_strings['LBL_CREDITS'];?> - <?php echo $mod_strings['LBL_THIRD_PARTY'];?></th></tr>
										<tr><td><a href="http://adodb.sourceforge.net" target="_blank">ADOdb</a></td></tr>
										<tr><td><a href="http://www.os-solution.com/demo/ajaxcsspopupchat/index.php" target="_blank">Ajax Popup Chat</a></td></tr>
										<tr><td><a href="http://httpd.apache.org/" target="_blank">Apache HTTP Server</a></td></tr>
										<tr><td><a href="http://www.linuxscope.net/articles/mailAttachmentsPHP.html" target="_blank">Attachments in E-mail Client</a></td></tr>
										<tr><td><a href="http://www.hmhd.com/steve" target="_blank">Calculator</a></td></tr>
										<tr><td><a href="http://www.dynamicdrive.com/dynamicindex14/carousel2.htm" target="_blank">Carousel Slideshow</a></td></tr>
										<tr><td><a href="http://www.troywolf.com/articles/php/class_http/" target="_blank">class_http</a></td></tr>
										<tr><td><a href="http://freshmeat.net/projects/phpexcelreader/" target="_blank">ExcelReader</a></td></tr>
										<tr><td><a href="http://www.fckeditor.net/download/default.html" target="_blank">FCKeditor</a></td></tr>
										<tr><td><a href="http://www.fpdf.org" target="_blank">FPDF</a></td></tr>
										<tr><td><a href="http://www.getluky.net" target="_blank">freetag</a></td></tr>
										<tr><td><a href="http://www.boutell.com/gd/" target="_blank">gdwin32</a></td></tr>
										<tr><td><a href="http://pear.php.net/package/Image_Graph" target="_blank">Graph</a></td></tr>
										<tr><td><a href="http://slayeroffice.com/code/imageCrossFade/xfade2.html" target="_blank">Image Crossfade Redux</a></td></tr>
										<tr><td><a href="http://pear.php.net/pepr/pepr-proposal-show.php?id=212" target="_blank">Image_Canvas</a></td></tr>
										<tr><td><a href="http://pear.php.net/package/Image_Color" target="_blank">Image_Color</a></td></tr>
										<tr><td><a href="http://www.dynarch.com/projects/calendar/" target="_blank">jscalendar</a></td></tr>
										<tr><td><a href="http://www.vxr.it/log4php/" target="_blank">log4php</a></td></tr>
										<tr><td><a href="http://magpierss.sourceforge.net/" target="_blank">MagpieRSS</a></td></tr>
										<tr><td><a href="http://wiki.wonko.com/software/mailfeed/" target="_blank">Mailfeed</a></td></tr>
										<tr><td><a href="http://www.mysql.com" target="_blank">MySQL</a></td></tr>
										<tr><td><a href="http://sourceforge.net/projects/nusoap" target="_blank">nusoap</a></td></tr>
										<tr><td><a href="http://www.php.net" target="_blank">PHP</a></td></tr>
										<tr><td><a href="http://phpmailer.sourceforge.net/" target="_blank">PHPMailer</a></td></tr>
										<tr><td><a href="http://phpsysinfo.sourceforge.net/" target="_blank">phpSysinfo</a></td></tr>
										<tr><td><a href="http://prototype.conio.net" target="_blank">Prototype</a></td></tr>
										<tr><td><a href="http://script.aculo.us" target="_blank">script.oculo.us</a></td></tr>
										<tr><td><a href="http://smarty.php.net/" target="_blank">Smarty Template Engine</a></td></tr>
										<tr><td><a href="http://www.sugarcrm.com" target="_blank">SugarCRM</a> (SPL 1.1.2)</td></tr>
										<tr><td>&nbsp;</td></tr>
										<tr><td><b><?php echo $mod_strings['LBL_COMMUNITY'];?></b></td></tr>
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
							<td width="70%" align="left" class="small">
							<span class="small" style="color:#999999;"><?php echo $mod_strings['LBL_VERSION'];?> : 5.0.0 </span>&nbsp;|&nbsp;
									<a href="http://www.vtiger.com/copyrights/LICENSE_AGREEMENT.txt" target="_blank"><?php echo $mod_strings['LBL_READ_LICENSE'];?></a>&nbsp;|&nbsp;
									<a href="http://www.vtiger.com/index.php?option=com_content&task=view&id=26&Itemid=54" target="_blank"><?php echo $mod_strings['LBL_CONTACT_US'];?></a>
							</td>
							<td align="right">
									<input type="button" name="close" value=" &nbsp;<?php echo $mod_strings['LBL_CLOSE'];?>&nbsp; " onClick="window.close();" class="crmbutton small cancel">
							</td>
						</tr>
				</table>
		  </td>
  </tr>
</table>
</body>
</html>
