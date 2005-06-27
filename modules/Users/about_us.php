<?php
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once('include/database/PearDatabase.php');
require_once('modules/Users/UserInfoUtil.php');
global $app_strings;
global $app_list_strings;
global $mod_strings;
?>
<title><?php echo $app_strings['LBL_ABOUTUS_TITLE']?></title>
<style type="text/css">@import url("<?php echo $theme_path?>style.css");</style>
<body>
<table width="100%" border="0">
<tr>
<td><img src="<?php echo $image_path?>vtiger-crm.gif" alt="vigercrm" border="0"></td>
<td>&nbsp;</td>
</tr>
<tr>
<td class="dataLabel" height="30 px"><font face="Helvetica, sans-serif"><b>Version:&nbsp;</b></font></td>
<td class="dataField" height="30 px">&nbsp;<?php echo $vtiger_version; ?></td>
</tr>
<tr>
<td class="dataLabel" height="30 px"><font face="Helvetica, sans-serif"><b>Release Date:&nbsp;</b></td>
<td class="dataField" height="30 px">&nbsp;<?php echo $release_date; ?></font></td>
</tr>
<tr>
<td class="dataLabel" height="30 px"><font face="Helvetica, sans-serif"><b>Toll Free Number:&nbsp;</b></font></td>
<td class="dataField" height="30 px">&nbsp;+1-888-720-9500</td>
</tr>
<tr>
<td class="dataLabel" height="30 px"><font face="Helvetica, sans-serif"><b>vtiger Discussions:&nbsp;</a></b></font></td>
<td class="dataField" height="30 px">&nbsp;<a href="http://www.vtiger.com/discussions/index.php?c=3" target=_blank><?php echo $app_strings['LBL_DISCUSS']?></a></td>
</tr>  	
</table>
<table cellpadding="5" width="100%" cellspacing="0" style="border: 1px solid #888888">
<tr>
<td ><p align="justify"><font face="Helvetica, sans-serif">vtiger offers an Open Source CRM solution for managing your organization's sales force automation, inventory management, and customer support & service requirements.<br><br>vtiger also provides Open Source business productivity enhancement add-ons, such as Outlook Plug-in, Office Plug-in, and Thunderbird extension that can be used with vtiger CRM.</font>
</td>
</tr>
</table>

