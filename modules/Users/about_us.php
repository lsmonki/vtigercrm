<?php
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once('include/database/PearDatabase.php');
require_once('modules/Users/UserInfoUtil.php');
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
<title><?php echo $app_strings['LBL_ABOUTUS_TITLE']?></title>
<style type="text/css">@import url("<?php echo $theme_path?>style.css");</style>
<body>
<table width="100%" border="0" style="border:1px solid #CCC;">
<tr>
<td><img src="<?php echo $image_path?>vtiger-crm.gif" alt="vigercrm" border="0"></td>
<td>&nbsp;</td>
</tr>
</table><br>
<table width="100%" style="border:1px solid #CCC;">
<tr>
<td class="dataLabel" height="30 px" width="40%"><font face="Helvetica, sans-serif"><b>Version:&nbsp;</b></font></td>
<td class="dataField" height="30 px">&nbsp;<?php echo $vtiger_version; ?></td>
</tr>
<tr>
<td class="dataLabel" height="30 px" width="40%"><font face="Helvetica, sans-serif"><b>Applied Patch Version:&nbsp;</b></font></td>
<td class="dataField" height="30 px">&nbsp;<?php echo $patch_string; ?></td>
</tr>

<tr>
<td class="dataLabel" height="30 px"><font face="Helvetica, sans-serif"><b>4.2.3 Release Date:&nbsp;</b></td>
<td class="dataField" height="30 px">&nbsp;<?php echo $release_date; ?><span class="gensmall"> (Current version)</span></td>
</tr>

<tr>
<td class="dataLabel" height="30 px"><font face="Helvetica, sans-serif"><b>4.2 Release Date:&nbsp;</b></td>
<td class="dataField" height="30 px">&nbsp;18-7-2005</font></td>
</tr>

<tr>
<td class="dataLabel" height="30 px"><font face="Helvetica, sans-serif"><b>4.0.1 Release Date:&nbsp;</b></td>
<td class="dataField" height="30 px">&nbsp;04-29-2005</font></td>
                </tr>
		
	
<tr>
<td class="dataLabel" height="30 px"><font face="Helvetica, sans-serif"><b>4.0 Release Date:&nbsp;</b></td>
<td class="dataField" height="30 px">&nbsp;03-29-2005</font></td>
	        </tr>
		<tr>
		<td class="dataLabel" height="30 px"><font face="Helvetica, sans-serif"><b>3.2 Release Date:&nbsp;</b></td>
		<td class="dataField" height="30 px">&nbsp;12-13-2004</font></td>
			        </tr>

<tr>
<td class="dataLabel" height="30 px"><font face="Helvetica, sans-serif"><b>Toll Free Number:&nbsp;</b></font></td>
<td class="dataField" height="30 px">&nbsp;+1-877-788-4473</td>
</tr>
<tr>
<td class="dataLabel" height="30 px"><font face="Helvetica, sans-serif"><b>vtiger Discussions:&nbsp;</a></b></font></td>
<td class="dataField" height="30 px">&nbsp;<a href="http://www.vtiger.com/discussions/index.php?c=3" target=_blank><?php echo $app_strings['LBL_DISCUSS']?></a></td>
</tr>  	
</table><br>
<table cellpadding="5" width="100%" cellspacing="0" style="border: 1px solid #888888">
<tr>
<td ><p align="justify"><font face="Helvetica, sans-serif">vtiger offers an Open Source CRM solution for managing your organization's sales force automation, inventory management, and customer support & service requirements.<br><br>vtiger also provides Open Source business productivity enhancement add-ons, such as Outlook Plug-in, Office Plug-in, and Thunderbird extension that can be used with vtiger CRM.</font>
</td>
</tr>
</table>

