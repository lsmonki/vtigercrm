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

<br>
<div style='float:left'><a href='javascript:openPopUp("addPortal",this,"index.php?action=Popup&module=Portal","addPortalWin",350,150,"menubar=no,toolbar=no,location=no,status=no,scrollbars=yes,resizable=no");' title='{$APP.LBL_ADD_Portal}'>Add<img src='{$IMAGEPATH}/addrss.gif' border=0 align=absmiddle></a>&nbsp;</div>
<br><br>
<style type="text/css">@import url("modules/Portal/Portalstyle.css");</style>
<script language="JavaScript" type="text/javascript" src="modules/Portal/Portal.js"></script>
<!--Portal info starts-->
			<ul id="tablist" style="margin-left: 40px;">
			{section name=portalinfo loop=$PORTALS}
				{if $smarty.section.portalinfo.iteration eq '1'}
					<li><a class="current" href="{$PORTALS[portalinfo].portalurl}" onClick="return handlelink(this)">{$PORTALS[portalinfo].portalname}</a><a href="index.php?module=Portal&action=Delete&return_module=Portal&return_action=index&record={$PORTALS[portalinfo].portalid}"><img src='{$IMAGEPATH}/del.gif' border=0 align=absmiddle></a></li>
				{else}	
					<li><a href="{$PORTALS[portalinfo].portalurl}" onClick="return handlelink(this)">{$PORTALS[portalinfo].portalname}</a><a href="index.php?module=Portal&action=Delete&return_module=Portal&return_action=index&record={$PORTALS[portalinfo].portalid}"><img src='{$IMAGEPATH}/del.gif' border=0 align=absmiddle></a></li>
				{/if}
			{/section}
			</ul>
			<iframe id="tabiframe" src="{$PORTALS.0.portalurl}" width="90%" height="400px" style="margin-left: 40px;"></iframe>
			<form name="tabcontrol" style="margin-top:0">
			<input name="tabcheck" type="checkbox" onClick="handleview()" style="margin-left: 40px;">{$MOD.LBL_OPEN_IN_BROWSER}</form>
			<br><br>
<!--Portal info ends-->
