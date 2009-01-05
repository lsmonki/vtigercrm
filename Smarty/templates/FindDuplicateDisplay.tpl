{*<!--

/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/

-->*}
<html>
<head>
<link rel="stylesheet" type="text/css" href="themes/{$THEME}/style.css">
	<link REL="SHORTCUT ICON" HREF="themes/images/vtigercrm_icon.ico">	
	<style type="text/css">@import url("themes/{$THEME}/style.css");</style>
</head>
<script language="JAVASCRIPT" type="text/javascript" src="include/js/smoothscroll.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/ListView.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
<script src="include/scriptaculous/prototype.js" type="text/javascript"></script>
<script src="include/scriptaculous/scriptaculous.js" type="text/javascript"></script>
<table width="100%" cellpadding="2" cellspacing="0" border="0">
<tr>
	{if $VIEW eq true}
		<td>
			{include file='Buttons_List.tpl'}
		</td>
	{else}
		<td>
			&nbsp;
		</td>
	{/if}
</tr>

<tr><td>
<div id="duplicate_ajax">

{include file='FindDuplicateAjax.tpl'}
</div>
<div id="current_action" style="display:none">{$smarty.request.action}</div>
</td></tr>
</table>
</html>

