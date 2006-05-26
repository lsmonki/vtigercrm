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

<script src="include/scriptaculous/prototype.js" type="text/javascript"></script>
<script src="include/scriptaculous/scriptaculous.js" type="text/javascript"></script>
<script language="JavaScript" type="text/javascript" src="include/js/ajax.js"></script>
<script language="JavaScript" type="text/javascript" src="modules/Portal/Portal.js"></script>

{include file="Buttons_List1.tpl"}
<br>
<table border="0" cellpadding="0" cellspacing="0" width="98%" align="center">
<tbody><tr>
<td class="SiteSel" id="datatab" onClick="fetchContents('data');">{$MOD.LBL_BOOKMARKED_URL}</td>
<td width="10">&nbsp;</td>
<td class="SiteUnSel" id="managetab" onclick="fetchContents('manage');">{$MOD.LBL_MANAGE_BOOKMARKS}</td>
<td class="SiteHdr">&nbsp;</td>
</tr>

<tr bgcolor="#e5e5e5">
<td colspan="4" style="padding: 10px;">


<!-- BOOKMARK PAGE -->
<div id="portalcont" style="padding: 10px; overflow: hidden; width: 98%;">
	{include file="MySitesContents.tpl"}
</div>


</td>
</tr>
</tbody></table>
<br><br>
<div id="editportal_cont" style="z-index:100001;position:absolute;width:510px;"></div>
