<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
<style type="text/css">@import url(themes/blue/style.css);</style>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="25%" class="lftMnuTab" valign="top">
<table  cellspacing="0" cellpadding="0" class="lftMnuHdr" >
<tr>
<td>
<div  style="position:relative;left:0px;top:0px;width:100%;display:block;">
<table class="lftMnuHdr" cellpadding="0" cellspacing="0" onclick="just();">

<tr><td width="95%"><a href="#" class="lftMnuHdr">{$MOD.LBL_USER_MANAGEMENT}</a></td>
<td width="5%"><a href="#" class="lftMnuHdr"><img src="{$IMAGE_PATH}downArrow.gif" border="0" /></a></td>
</tr>
</table>
<div id="top">
<div id="user">
<a href="index.php?module=Administration&action=index&parenttab=Settings" class="lftSubMnu">{$MOD.LBL_USERS}</a>
<a href="index.php?module=Users&action=listroles&parenttab=Settings" class="lftSubMnu">{$MOD.LBL_ROLES}</a>
<a href="index.php?module=Users&action=ListProfiles&parenttab=Settings" class="lftSubMnu">{$MOD.LBL_PROFILES}</a>
<a href="index.php?module=Users&action=listgroups&parenttab=Settings" class="lftSubMnu">{$MOD.USERGROUPLIST}</a>
<a href="index.php?module=Users&action=OrgSharingDetailView&parenttab=Settings" class="lftSubMnu">{$MOD.LBL_DEFAULT_ORGANIZATION_SHARING_ACCESS}</a>
<a href="#" class="lftSubMnu">{$MOD.LBL_DEFAULT_ORGANIZATION_FIELDS}</a>
<a href="#" class="lftSubMnu">{$MOD.LBL_FIELD_ACCESSIBILITY}</a>
</div></div>

<table class="lftMnuHdr" cellpadding="0" cellspacing="0" onclick="just1();">
<tr><td width="95%"><a href="#" class="lftMnuHdr" >{$MOD.LBL_STUDIO}</a></td>
<td width="5%"><a href="#" class="lftMnuHdr"><img src="{$IMAGE_PATH}downArrow.gif" border="0" /></a></td>
</tr></table>
<div id="top2">
<div id="studio">
<a href="#" class="lftSubMnu">{$MOD.LBL_CUSTOM_FIELD_SETTINGS}</a>
<a href="#" class="lftSubMnu">{$MOD.LBL_PICKLIST_SETTINGS}</a>
<a href="#" class="lftSubMnu">{$MOD.LBL_FIELD_ORDERING}</a>
</div></div>
<table class="lftMnuHdr" cellpadding="0" cellspacing="0" onclick="just2()">
<tr><td width="95%"><a href="#" class="lftMnuHdr" >{$MOD.LBL_COMMUNICATION_TEMPLATES}</a></td>
<td width="5%"><a href="#" class="lftMnuHdr"><img src="{$IMAGE_PATH}downArrow.gif" border="0" /></a></td>
</tr></table>
<div id="top3">		
<div id="comm">
<a href="index.php?module=Users&action=listemailtemplates&parenttab=Settings" class="lftSubMnu">{$MOD.EMAILTEMPLATES}</a>
<a href="index.php?module=Users&action=listwordtemplates&parenttab=Settings" class="lftSubMnu">{$MOD.WORDINTEGRATION}</a>
<a href="index.php?module=Users&action=listnotificationschedulers&parenttab=Settings" class="lftSubMnu">{$MOD.NOTIFICATIONSCHEDULERS}</a>
<a href="index.php?module=Users&action=listinventorynotifications&parenttab=Settings" class="lftSubMnu">{$MOD.INVENTORYNOTIFICATION}</a>
<a href="index.php?module=Users&action=OrganizationTermsandConditions&parenttab=Settings" class="lftSubMnu">{$MOD.INVENTORYTERMSANDCONDITIONS}</a>
</div>
</div>

<table class="lftMnuHdr" cellpadding="0" cellspacing="0" onclick="just3()">
<tr><td width="95%"><a href="#" class="lftMnuHdr" >{$MOD.LBL_CONFIGURATION}</a></td>
<td width="5%"><a href="#" class="lftMnuHdr"><img src="{$IMAGE_PATH}downArrow.gif" border="0" /></a></td>
</tr></table>
<div id="top4">
<div id="config">
<a href="index.php?module=Settings&action=OrganizationConfig&parenttab=Settings" class="lftSubMnu">{$MOD.LBL_ORGANIZATION_DETAILS}</a>
<a href="index.php?module=Settings&action=EmailConfig&parenttab=Settings" class="lftSubMnu">{$MOD.LBL_EMAIL_CONFIG}</a>
<a href="index.php?module=Settings&action=BackupServerConfig&parenttab=Settings" class="lftSubMnu">{$MOD.LBL_BACKUP_SERVER_CONFIGURATION}</a>
<a href="index.php?module=System&action=systemconfig&parenttab=Settings" class="lftSubMnu">{$MOD.LBL_SYSTEM_CONFIG}</a>
<a href="index.php?module=Settings&action=CurrencyListView&parenttab=Settings" class="lftSubMnu">{$MOD.LBL_CURRENCY_CONFIG}</a>
<a href="index.php?module=Settings&action=ListModuleOwners&parenttab=Settings" class="lftSubMnu">{$MOD.LBL_ASSIGN_MODULE_OWNERS}</a>
<a href=index.php?module=Migration&action=MigrationStep1&parenttab=Settings" class="lftSubMnu">{$MOD.LBL_MIGRATION}</a>
</div></div>

</div>
</td>
</tr>
<tr><td style="height:3px;background-image:url({$IMAGE_PATH}/bg.gif);"></td></tr>
</table>
</td>
<td width="75%" valign="top">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>

<td class="detailedViewHeader" align="left"><b>{$MOD.LBL_USER_MANAGEMENT}</b></td>
</tr>

<tr>
<td class="padTab">

<table class="controlTab" cellspacing="0" cellpadding="0">
<tr>

<td width="25%"><a href="index.php?module=Administration&action=index&parenttab=Settings"><img src="{$IMAGE_PATH}user_mgmt.gif" border="0" alt="{$MOD.LBL_CREATE_AND_MANAGE_USERS}" title="{$MOD.LBL_CREATE_AND_MANAGE_USERS}"/></a><br>
<a href="index.php?module=Administration&action=index&parenttab=Settings" alt="{$MOD.LBL_CREATE_AND_MANAGE_USERS}" title="{$MOD.LBL_CREATE_AND_MANAGE_USERS}">{$MOD.LBL_USERS}</a></td>

<td width="25%"><a href="index.php?module=Users&action=listroles&parenttab=Settings"><img src="{$IMAGE_PATH}roles.gif" alt="{$MOD.LBL_CREATE_AND_MANAGE_USER_ROLES}" title="{$MOD.LBL_CREATE_AND_MANAGE_USER_ROLES}" border="0"/></a><br>
<a href="index.php?module=Users&action=listroles&parenttab=Settings" alt="{$MOD.LBL_CREATE_AND_MANAGE_USER_ROLES}" title="{$MOD.LBL_CREATE_AND_MANAGE_USER_ROLES}">{$MOD.LBL_ROLES}</a>
</td>

<td width="25%"><a href="index.php?module=Users&action=ListProfiles&parenttab=Settings"><img src="{$IMAGE_PATH}profile.gif" alt="{$MOD.LBL_CREATE_AND_MANAGE_USER_PROFILES}" title="{$MOD.LBL_CREATE_AND_MANAGE_USER_PROFILES}"  border="0" /></a><br>
<a href="index.php?module=Users&action=ListProfiles&parenttab=Settings" alt="{$MOD.LBL_CREATE_AND_MANAGE_USER_PROFILES}" title="{$MOD.LBL_CREATE_AND_MANAGE_USER_PROFILES}">{$MOD.LBL_PROFILES}</a>
</td>

<td width="25%"><a href="index.php?module=Users&action=listgroups&parenttab=Settings"><img src="{$IMAGE_PATH}groupss.gif" alt="{$MOD.LBL_CREATE_AND_MANAGE_USER_GROUPS}" title="{$MOD.LBL_CREATE_AND_MANAGE_USER_GROUPS}" border="0" /></a><br>
<a href="index.php?module=Users&action=listgroups&parenttab=Settings" alt="{$MOD.LBL_CREATE_AND_MANAGE_USER_GROUPS}" title="{$MOD.LBL_CREATE_AND_MANAGE_USER_GROUPS}">{$MOD.USERGROUPLIST}</a></td>

</tr>

<tr><td colspan="4" height="30px;">&nbsp;</td></tr>

<tr>

<td><a href="index.php?module=Users&action=OrgSharingDetailView&parenttab=Settings"><img src="{$IMAGE_PATH}sharaccs.gif" alt="{$MOD.LBL_SETTING_DEFAULT_SHARING_ACCESS}" title="{$MOD.LBL_SETTING_DEFAULT_SHARING_ACCESS}" border="0"/></a><br>
<a href="index.php?module=Users&action=OrgSharingDetailView&parenttab=Settings" alt="{$MOD.LBL_SETTING_DEFAULT_SHARING_ACCESS}" title="{$MOD.LBL_SETTING_DEFAULT_SHARING_ACCESS}">{$MOD.LBL_DEFAULT_ORGANIZATION_SHARING_ACCESS}</a>
</td>

<td><a href="#"><img src="{$IMAGE_PATH}images.jpg" alt="{$MOD.LBL_SETTING_DEFAULT_ORGANIZATION_FIELDS}" title="{$MOD.LBL_SETTING_DEFAULT_ORGANIZATION_FIELDS}" border="0"/></a><br>						
<a href="#" alt="{$MOD.LBL_SETTING_DEFAULT_ORGANIZATION_FIELDS}" title="{$MOD.LBL_SETTING_DEFAULT_ORGANIZATION_FIELDS}">{$MOD.LBL_DEFAULT_ORGANIZATION_FIELDS}</a></td>

<td><a href="#"><img src="{$IMAGE_PATH}shar.jpg" alt="{$MOD.LBL_SETTING_FIELD_ACCESSIBILITY}" title="{$MOD.LBL_SETTING_FIELD_ACCESSIBILITY}" border="0" /></a><br>
<a href="#" alt="{$MOD.LBL_SETTING_FIELD_ACCESSIBILITY}" title="{$MOD.LBL_SETTING_FIELD_ACCESSIBILITY}">{$MOD.LBL_FIELD_ACCESSIBILITY}</a></td>
<td>&nbsp;</td>

</tr>

</table>
</td>
</tr>

<tr><td>&nbsp;</td></tr>

<tr>
<td class="detailedViewHeader" align="left" ><b>{$MOD.LBL_STUDIO}</b></td>
</tr>

<tr>
<td class="padTab">

<table class="controlTab" cellspacing="0" cellpadding="0">

<tr>
<td width="25%"><a href="#"><img src="{$IMAGE_PATH}user_mgmt.gif" border="0" alt="{$MOD.LBL_CREATE_AND_MANAGE_USER_DEFINED_FIELDS}" title="{$MOD.LBL_CREATE_AND_MANAGE_USER_DEFINED_FIELDS}"/></a><br />
<a href="#" alt="{$MOD.LBL_CREATE_AND_MANAGE_USER_DEFINED_FIELDS}" title="{$MOD.LBL_CREATE_AND_MANAGE_USER_DEFINED_FIELDS}">{$MOD.LBL_CUSTOM_FIELD_SETTINGS}</a></td>

<td width="25%"><a href="#"><img src="{$IMAGE_PATH}roles.gif" alt="{$MOD.LBL_EDIT_PICKLIST_VALUES}" title="{$MOD.LBL_EDIT_PICKLIST_VALUES}" border="0"/></a><br />
<a href="#" alt="{$MOD.LBL_EDIT_PICKLIST_VALUES}" title="{$MOD.LBL_EDIT_PICKLIST_VALUES}">{$MOD.LBL_PICKLIST_SETTINGS}</a> </td>

<td width="25%"><a href="#"><img src="{$IMAGE_PATH}profile.gif" alt="{$MOD.LBL_FIELD_ORDERING_DESC}" title="{$MOD.LBL_FIELD_ORDERING_DESC}"  border="0" /></a><br />
<a href="#" alt="{$MOD.LBL_FIELD_ORDERING_DESC}" title="{$MOD.LBL_FIELD_ORDERING_DESC}">{$MOD.LBL_FIELD_ORDERING}</a> </td>
</tr>

<tr>
<td colspan="4" height="30px;">&nbsp;</td>
</tr>

</table>
</td>
</tr>

<tr><td>&nbsp;</td></tr>

<tr>
<td class="detailedViewHeader" align="left"><b>{$MOD.LBL_COMMUNICATION_TEMPLATES}</b></td>
</tr>

<tr><td class="padTab">
<table class="controlTab" cellspacing="0" cellpadding="0">
<tr>

<td width="25%"><a href="index.php?module=Users&action=listemailtemplates&parenttab=Settings"><img src="{$IMAGE_PATH}user_mgmt.gif" border="0" alt="{$MOD.LBL_CREATE_EMAIL_TEMPLATES}" title="{$MOD.LBL_CREATE_EMAIL_TEMPLATES}"/></a><br />
<a href="index.php?module=Users&action=listemailtemplates&parenttab=Settings" alt="{$MOD.LBL_CREATE_EMAIL_TEMPLATES}" title="{$MOD.LBL_CREATE_EMAIL_TEMPLATES}">{$MOD.EMAILTEMPLATES}</a></td>

<td width="25%"><a href="index.php?module=Users&action=listwordtemplates&parenttab=Settings"><img src="{$IMAGE_PATH}roles.gif" alt="{$MOD.LBL_UPLOAD_MSWORD_TEMPLATES}" title="{$MOD.LBL_UPLOAD_MSWORD_TEMPLATES}" border="0"/></a><br />
<a href="index.php?module=Users&action=listwordtemplates&parenttab=Settings" alt="{$MOD.LBL_UPLOAD_MSWORD_TEMPLATES}" title="{$MOD.LBL_UPLOAD_MSWORD_TEMPLATES}">{$MOD.WORDINTEGRATION}</a> </td>

<td width="25%"><a href="index.php?module=Users&action=listnotificationschedulers&parenttab=Settings"><img src="{$IMAGE_PATH}profile.gif" alt="{$MOD.LBL_SCHEDULE_EMAIL_NOTIFICATION}" title="{$MOD.LBL_SCHEDULE_EMAIL_NOTIFICATION}"  border="0" /></a><br />
<a href="index.php?module=Users&action=listnotificationschedulers&parenttab=Settings" alt="{$MOD.LBL_SCHEDULE_EMAIL_NOTIFICATION}" title="{$MOD.LBL_SCHEDULE_EMAIL_NOTIFICATION}">{$MOD.NOTIFICATIONSCHEDULERS}</a> </td>

</tr>
<tr><td colspan="4" height="30px;">&nbsp;</td></tr>
<tr>

<td width="25%"><a href="index.php?module=Users&action=listinventorynotifications&parenttab=Settings"><img src="{$IMAGE_PATH}groupss.gif" alt="{$MOD.LBL_INVENTORY_NOTIFICATIONS}" title="{$MOD.LBL_INVENTORY_NOTIFICATIONS}" border="0" /></a><br />
<a href="index.php?module=Users&action=listinventorynotifications&parenttab=Settings" alt="{$MOD.LBL_INVENTORY_NOTIFICATIONS}" title="{$MOD.LBL_INVENTORY_NOTIFICATIONS}">{$MOD.INVENTORYNOTIFICATION}</a></td>

<td><a href="index.php?module=Users&action=OrganizationTermsandConditions&parenttab=Settings"><img src="{$IMAGE_PATH}sharaccs.gif" alt="{$MOD.LBL_INVENTORY_TERMSANDCONDITIONS}" title="{$MOD.LBL_INVENTORY_TERMSANDCONDITIONS}" border="0"/></a><br />
<a href="index.php?module=Users&action=OrganizationTermsandConditions&parenttab=Settings" alt="{$MOD.LBL_INVENTORY_TERMSANDCONDITIONS}" title="{$MOD.LBL_INVENTORY_TERMSANDCONDITIONS}">{$MOD.INVENTORYTERMSANDCONDITIONS}</a> </td>
</tr>

<td>&nbsp;</td>
</tr>

</table>
</td></tr>

<tr><td>&nbsp;</td></tr>
<tr>
<td class="detailedViewHeader" align="left"><b>{$MOD.LBL_CONFIGURATION}</b></td>
</tr>
<tr><td class="padTab">
<table class="controlTab" cellspacing="0" cellpadding="0">
<tr>

<td width="25%"><a href="index.php?module=Settings&action=OrganizationConfig&parenttab=Settings"><img src="{$IMAGE_PATH}user_mgmt.gif" border="0" alt="{$MOD.LBL_SPECIFY_COMPANY_DETAILS}" title="{$MOD.LBL_SPECIFY_COMPANY_DETAILS}"/></a><br />
<a href="index.php?module=Settings&action=OrganizationConfig&parenttab=Settings" alt="{$MOD.LBL_SPECIFY_COMPANY_DETAILS}" title="{$MOD.LBL_SPECIFY_COMPANY_DETAILS}">{$MOD.LBL_ORGANIZATION_DETAILS}</a></td>

<td width="25%"><a href="index.php?module=Settings&action=EmailConfig&parenttab=Settings"><img src="{$IMAGE_PATH}roles.gif" alt="{$MOD.LBL_CONFIGURE_MAIL_SERVER}" title="{$MOD.LBL_CONFIGURE_MAIL_SERVER}" border="0"/></a><br />
<a href="index.php?module=Settings&action=EmailConfig&parenttab=Settings" alt="{$MOD.LBL_CONFIGURE_MAIL_SERVER}" title="{$MOD.LBL_CONFIGURE_MAIL_SERVER}">{$MOD.LBL_EMAIL_CONFIG}</a></td>

<td width="25%"><a href="index.php?module=Settings&action=BackupServerConfig&parenttab=Settings"><img src="{$IMAGE_PATH}profile.gif" alt="{$MOD.LBL_CONFIGURE_BACKUP_SERVER}" title="{$MOD.LBL_CONFIGURE_BACKUP_SERVER}"  border="0" /></a><br />
<a href="index.php?module=Settings&action=BackupServerConfig&parenttab=Settings" alt="{$MOD.LBL_CONFIGURE_BACKUP_SERVER}" title="{$MOD.LBL_CONFIGURE_BACKUP_SERVER}">{$MOD.LBL_BACKUP_SERVER_CONFIGURATION}</a> </td>

<td width="25%"><a href="index.php?module=System&action=systemconfig&parenttab=Settings"><img src="{$IMAGE_PATH}groupss.gif" alt="{$MOD.LBL_SYSTEM_CONFIGURATION}" title="{$MOD.LBL_SYSTEM_CONFIGURATION}" border="0" /></a><br />
<a href="index.php?module=System&action=systemconfig&parenttab=Settings" alt="{$MOD.LBL_SYSTEM_CONFIGURATION}" title="{$MOD.LBL_SYSTEM_CONFIGURATION}">{$MOD.LBL_SYSTEM_CONFIG}</a></td>

</tr>

<tr><td colspan="4" height="30px;">&nbsp;</td></tr>

<tr>

<td><a href="index.php?module=Settings&action=CurrencyListView&parenttab=Settings"><img src="{$IMAGE_PATH}sharaccs.gif" alt="{$MOD.LBL_CURRENCY_CONFIGURATION}" title="{$MOD.LBL_CURRENCY_CONFIGURATION}" border="0"/></a><br />
<a href="index.php?module=Settings&action=CurrencyListView&parenttab=Settings" alt="{$MOD.LBL_CURRENCY_CONFIGURATION}" title="{$MOD.LBL_CURRENCY_CONFIGURATION}">{$MOD.LBL_CURRENCY_CONFIG}</a> </td>

<td><a href="index.php?module=Settings&action=ListModuleOwners&parenttab=Settings"><img src="{$IMAGE_PATH}images.jpg" alt="{$MOD.LBL_ASSIGN_MODULE_OWNERS}" title="{$MOD.LBL_ASSIGN_MODULE_OWNERS}" border="0"/></a><br />
<a href="index.php?module=Settings&action=ListModuleOwners&parenttab=Settings" clt="{$MOD.LBL_ASSIGN_MODULE_OWNERS}" title="{$MOD.LBL_ASSIGN_MODULE_OWNERS}">{$MOD.LBL_ASSIGN_MODULE_OWNERS}</a>

<td><a href="index.php?module=Migration&action=MigrationStep1&parenttab=Settings"><img src="{$IMAGE_PATH}images.jpg" alt="{$MOD.LBL_MIGRATION_INFO}" title="{$MOD.LBL_MIGRATION_INFO}" border="0"/></a><br />
<a href="index.php?module=Migration&action=MigrationStep1&parenttab=Settings" alt="{$MOD.LBL_MIGRATION_INFO}" title="{$MOD.LBL_MIGRATION_INFO}">{$MOD.LBL_MIGRATION}</a></td>

</tr>
<tr><td colspan="4" height="30px;">&nbsp;</td></tr>
</table></td></tr>

</table>

</td>
</tr>
</table>
<script>
ScrollEffect.limit = 171;
ScrollEffect.closelimit= 170;
ScrollEffect1.limit1 = 81;
ScrollEffect1.closelimit1= 80;
ScrollEffect2.limit2 = 121;
ScrollEffect2.closelimit2= 120;
ScrollEffect3.limit3 = 151;
ScrollEffect3.closelimit3= 150;

</script>
