<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
<td width="25%" valign="top">
<table width="100%" border="0" cellpadding="0" cellspacing="0">

<tr>
<td width="24%" valign="top" id="slideMnu" >
<div id="one">
<table  cellspacing="0" cellpadding="0" class="lftMnuHdr" >
<tr>
<td>
<table class="lftMnuHdr" cellpadding="0" cellspacing="0" onclick="fnWipe('top','user');">

<tr><td width="95%"><a href="#" class="lftMnuHdr">{$MOD.LBL_USER_MANAGEMENT}</a></td>
<td width="5%"><a href="#" class="lftMnuHdr"><img src="{$IMAGE_PATH}/downArrow.gif" border="0" /></a></td>
</tr>
</table>

<div id="top">
<div id="user" style="height:185px;">
	<a href="index.php?module=Administration&action=index&parenttab=Settings" class="lftSubMnu">{$MOD.LBL_USERS}</a>
	<a href="index.php?module=Users&action=listroles&parenttab=Settings" class="lftSubMnu">{$MOD.LBL_ROLES}</a>
	<a href="index.php?module=Users&action=ListProfiles&parenttab=Settings" class="lftSubMnu">{$MOD.LBL_PROFILES}</a>	
	<a href="index.php?module=Users&action=listgroups&parenttab=Settings" class="lftSubMnu">{$MOD.USERGROUPLIST}</a>
	<a href="index.php?module=Users&action=OrgSharingDetailView&parenttab=Settings" class="lftSubMnu">{$MOD.LBL_DEFAULT_ORGANIZATION_SHARING_ACCESS}</a>
	<a href="index.php?module=Users&action=DefaultFieldPermissions&parenttab=Settings" class="lftSubMnu">{$MOD.LBL_DEFAULT_ORGANIZATION_FIELDS}</a>
	<a href="index.php?module=Settings&action=ListModuleOwners&parenttab=Settings" class="lftSubMnu">{$MOD.LBL_ASSIGN_MODULE_OWNERS}</a>
	<a href="index.php?module=Users&action=Announcements&parenttab=Settings" class="lftSubMnu" class="lftSubMnu">{$MOD.LBL_ANNOUNCEMENT}</a>
</div></div>

<table class="lftMnuHdr" cellpadding="0" cellspacing="0" onclick="fnWipe('top2','studio');">
<tr><td width="95%"><a href="#" class="lftMnuHdr" >{$MOD.LBL_STUDIO}</a></td>
<td width="5%"><a href="#" class="lftMnuHdr"><img src="{$IMAGE_PATH}downArrow.gif" border="0" /></a></td>
</tr></table>

<div id="top2">
<div id="studio" style="height:70px;">
	<a href="index.php?module=Settings&action=SettingsSubMenu&type=CustomField&parenttab=Settings" class="lftSubMnu" onmouseover="fnvsh(this,'subMnuCusFld')" onmouseout="fninvsh('subMnuCusFld')">{$MOD.LBL_CUSTOM_FIELD_SETTINGS}</a>
	<a href="index.php?module=Settings&action=PickList&parenttab=Settings" class="lftSubMnu">{$MOD.LBL_PICKLIST_SETTINGS}</a>
	<a href="index.php?module=Settings&action=SettingsSubMenu&type=FieldOrder&parenttab=Settings" class="lftSubMnu" onmouseover="fnvsh(this,'subMnuEditFld')" onmouseout="fninvsh('subMnuEditFld')">{$MOD.LBL_FIELD_ORDERING}</a>
</div></div>

<table class="lftMnuHdr" cellpadding="0" cellspacing="0" onclick="fnWipe('top3','comm')" >
<tr><td width="95%"><a href="#" class="lftMnuHdr" >{$MOD.LBL_COMMUNICATION_TEMPLATES}</a></td>
<td width="5%"><a href="#" class="lftMnuHdr"><img src="{$IMAGE_PATH}downArrow.gif" border="0" /></a></td>
</tr></table>

<div id="top3">		
<div id="comm" style="height:116px;">
	<a href="index.php?module=Users&action=listemailtemplates&parenttab=Settings" class="lftSubMnu">{$MOD.EMAILTEMPLATES}</a>
	<a href="index.php?module=Users&action=listwordtemplates&parenttab=Settings" class="lftSubMnu">{$MOD.WORDINTEGRATION}</a>
	<a href="index.php?module=Users&action=listnotificationschedulers&parenttab=Settings" class="lftSubMnu">{$MOD.NOTIFICATIONSCHEDULERS}</a>
	<a href="index.php?module=Users&action=listinventorynotifications&parenttab=Settings" class="lftSubMnu">{$MOD.INVENTORYNOTIFICATION}</a>
	<a href="index.php?module=Users&action=OrganizationTermsandConditions&parenttab=Settings" class="lftSubMnu">{$MOD.INVENTORYTERMSANDCONDITIONS}</a>
</div>
</div>

<table class="lftMnuHdr" cellpadding="0" cellspacing="0" onclick="fnWipe('top4','config')">
<tr><td width="95%"><a href="#" class="lftMnuHdr" >{$MOD.LBL_CONFIGURATION}</a></td>
<td width="5%"><a href="#" class="lftMnuHdr"><img src="{$IMAGE_PATH}downArrow.gif" border="0" /></a></td>
</tr></table>
<div id="top4">
<div id="config" style="height:135px;">
<a href="index.php?module=Settings&action=OrganizationConfig&parenttab=Settings" class="lftSubMnu">{$MOD.LBL_ORGANIZATION_DETAILS}</a>
<a href="index.php?module=Settings&action=EmailConfig&parenttab=Settings" class="lftSubMnu">{$MOD.LBL_EMAIL_CONFIG}</a>
<a href="index.php?module=Settings&action=BackupServerConfig&parenttab=Settings" class="lftSubMnu">{$MOD.LBL_BACKUP_SERVER_CONFIGURATION}</a>
<a href="index.php?module=System&action=systemconfig&parenttab=Settings" class="lftSubMnu">{$MOD.LBL_SYSTEM_CONFIG}</a>
<a href="index.php?module=Settings&action=CurrencyListView&parenttab=Settings" class="lftSubMnu">{$MOD.LBL_CURRENCY_CONFIG}</a>
<a href=index.php?module=Migration&action=MigrationStep1&parenttab=Settings" class="lftSubMnu">{$MOD.LBL_MIGRATION}</a>
</div></div>
</td>
</tr>
<tr><td style="height:3px;background-image:url({$IMAGE_PATH}/bg.gif);"></td></tr>
</table>
</div>
</td>
<td width="1%" class="dock"><img src="{$IMAGE_PATH}/rhtArrow.gif" class="imgDoc" alt="Expand/Close Menu" title="Expand/Close Menu" onclick="fnSlide('slideMnu','one')" /></td>
