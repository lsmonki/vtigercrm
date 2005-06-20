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
<td><img src="<?php echo $image_path?>vtiger.jpg" alt="vigercrm" border="0"></td>
</tr>
<tr>
<td class="dataLabel" height="30 px"><font face="Helvetica, sans-serif"><b>Product Version:&nbsp;</b></td><td class="dataField">&nbsp;<?php echo $vtiger_version; ?></font></td>
</tr>
<tr>
	<td class="dataLabel" height="30 px"><font face="Helvetica, sans-serif"><b>Release Date:&nbsp;</b></td><td class="dataField">&nbsp;<?php echo $release_date; ?></font></td>
    	</tr>
	<tr>
     <td class="dataLabel" height="30 px"><font face="Helvetica, sans-serif"><b>Toll Free Number:&nbsp;</b></td><td class="dataField">&nbsp;+1-888-720-9500</font></td>
	</tr>
  	<tr>
    	<td class="dataLabel" height="30 px"><font face="Helvetica, sans-serif"><b>vtiger Discussions</a></b>:&nbsp;</td><td class="dataField">&nbsp;<a href="http://www.vtiger.com/discussions/index.php?c=3" target=_blank><?php echo $app_strings['LBL_DISCUSS']?></a></font></td>
  	</tr>  	
</table>

