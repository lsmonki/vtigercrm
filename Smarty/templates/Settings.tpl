<style type="text/css">@import url(themes/blue/style.css);</style>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
		{include file='SettingsMenu.tpl'}
<td width="75%" valign="top">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>

<td class="detailedViewHeader" align="left"><b>{$MOD.LBL_USER_MANAGEMENT}</b></td>
</tr>

<tr>
<td class="padTab">

<table class="controlTab" cellspacing="0" cellpadding="0">
<tr>

<td width="25%"><a href="index.php?module=Administration&action=index&parenttab=Settings"><img src="{$IMAGE_PATH}user.gif" border="0" alt="{$MOD.LBL_CREATE_AND_MANAGE_USERS}" title="{$MOD.LBL_CREATE_AND_MANAGE_USERS}"/></a><br>
<a href="index.php?module=Administration&action=index&parenttab=Settings" alt="{$MOD.LBL_CREATE_AND_MANAGE_USERS}" title="{$MOD.LBL_CREATE_AND_MANAGE_USERS}">{$MOD.LBL_USERS}</a></td>

<td width="25%"><a href="index.php?module=Users&action=listroles&parenttab=Settings"><img src="{$IMAGE_PATH}roles.gif" alt="{$MOD.LBL_CREATE_AND_MANAGE_USER_ROLES}" title="{$MOD.LBL_CREATE_AND_MANAGE_USER_ROLES}" border="0"/></a><br>
<a href="index.php?module=Users&action=listroles&parenttab=Settings" alt="{$MOD.LBL_CREATE_AND_MANAGE_USER_ROLES}" title="{$MOD.LBL_CREATE_AND_MANAGE_USER_ROLES}">{$MOD.LBL_ROLES}</a>
</td>

<td width="25%"><a href="index.php?module=Users&action=ListProfiles&parenttab=Settings"><img src="{$IMAGE_PATH}profile.gif" alt="{$MOD.LBL_CREATE_AND_MANAGE_USER_PROFILES}" title="{$MOD.LBL_CREATE_AND_MANAGE_USER_PROFILES}"  border="0" /></a><br>
<a href="index.php?module=Users&action=ListProfiles&parenttab=Settings" alt="{$MOD.LBL_CREATE_AND_MANAGE_USER_PROFILES}" title="{$MOD.LBL_CREATE_AND_MANAGE_USER_PROFILES}">{$MOD.LBL_PROFILES}</a>
</td>

<td width="25%"><a href="index.php?module=Users&action=listgroups&parenttab=Settings"><img src="{$IMAGE_PATH}groups.gif" alt="{$MOD.LBL_CREATE_AND_MANAGE_USER_GROUPS}" title="{$MOD.LBL_CREATE_AND_MANAGE_USER_GROUPS}" border="0" /></a><br>
<a href="index.php?module=Users&action=listgroups&parenttab=Settings" alt="{$MOD.LBL_CREATE_AND_MANAGE_USER_GROUPS}" title="{$MOD.LBL_CREATE_AND_MANAGE_USER_GROUPS}">{$MOD.USERGROUPLIST}</a></td>

</tr>

<tr><td colspan="4" height="30px;">&nbsp;</td></tr>

<tr>

<td><a href="index.php?module=Users&action=OrgSharingDetailView&parenttab=Settings"><img src="{$IMAGE_PATH}shareaccess.gif" alt="{$MOD.LBL_SETTING_DEFAULT_SHARING_ACCESS}" title="{$MOD.LBL_SETTING_DEFAULT_SHARING_ACCESS}" border="0"/></a><br>
<a href="index.php?module=Users&action=OrgSharingDetailView&parenttab=Settings" alt="{$MOD.LBL_SETTING_DEFAULT_SHARING_ACCESS}" title="{$MOD.LBL_SETTING_DEFAULT_SHARING_ACCESS}">{$MOD.LBL_DEFAULT_ORGANIZATION_SHARING_ACCESS}</a>
</td>

<td><a href="index.php?module=Users&action=DefaultFieldPermissions&parenttab=Settings"><img src="{$IMAGE_PATH}orgshar.gif" alt="{$MOD.LBL_SETTING_DEFAULT_ORGANIZATION_FIELDS}" title="{$MOD.LBL_SETTING_DEFAULT_ORGANIZATION_FIELDS}" border="0"/></a><br>						
<a href="index.php?module=Users&action=DefaultFieldPermissions&parenttab=Settings" alt="{$MOD.LBL_SETTING_DEFAULT_ORGANIZATION_FIELDS}" title="{$MOD.LBL_SETTING_DEFAULT_ORGANIZATION_FIELDS}">{$MOD.LBL_DEFAULT_ORGANIZATION_FIELDS}</a></td>

<td><a href="index.php?module=Settings&action=ListModuleOwners&parenttab=Settings"><img src="{$IMAGE_PATH}assign.gif" alt="{$MOD.LBL_ASSIGN_MODULE_OWNERS}" title="{$MOD.LBL_ASSIGN_MODULE_OWNERS}" border="0"/></a><br />
<a href="index.php?module=Settings&action=ListModuleOwners&parenttab=Settings" alt="{$MOD.LBL_ASSIGN_MODULE_OWNERS}" title="{$MOD.LBL_ASSIGN_MODULE_OWNERS}">{$MOD.LBL_ASSIGN_MODULE_OWNERS}</a>

<td><a href="index.php?module=Users&action=Announcements&parenttab=Settings"><img src="{$IMAGE_PATH}announ.gif" alt="{$MOD.LBL_SETTING_ANNOUNCEMENT}" title="{$MOD.LBL_SETTING_ANNOUNCEMENT}" border="0" /></a><br>
<a href="index.php?module=Users&action=Announcements&parenttab=Settings" alt="{$MOD.LBL_SETTING_ANNOUNCEMENT}" title="{$MOD.LBL_SETTING_ANNOUNCEMENT}">{$MOD.LBL_ANNOUNCEMENT}</a></td>
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
<td width="25%"><a href="index.php?module=Settings&action=SettingsSubMenu&type=CustomField&parenttab=Settings"><img src="{$IMAGE_PATH}custom.gif" border="0" alt="{$MOD.LBL_CREATE_AND_MANAGE_USER_DEFINED_FIELDS}" title="{$MOD.LBL_CREATE_AND_MANAGE_USER_DEFINED_FIELDS}"/></a><br />
<a href="index.php?module=Settings&action=SettingsSubMenu&type=CustomField&parenttab=Settings" alt="{$MOD.LBL_CREATE_AND_MANAGE_USER_DEFINED_FIELDS}" title="{$MOD.LBL_CREATE_AND_MANAGE_USER_DEFINED_FIELDS}">{$MOD.LBL_CUSTOM_FIELD_SETTINGS}</a></td>

<td width="25%"><a href="index.php?module=Settings&action=PickList&parenttab=Settings"><img src="{$IMAGE_PATH}picklist.gif" alt="{$MOD.LBL_EDIT_PICKLIST_VALUES}" title="{$MOD.LBL_EDIT_PICKLIST_VALUES}" border="0"/></a><br />
<a href="index.php?module=Settings&action=PickList&parenttab=Settings" alt="{$MOD.LBL_EDIT_PICKLIST_VALUES}" title="{$MOD.LBL_EDIT_PICKLIST_VALUES}">{$MOD.LBL_PICKLIST_SETTINGS}</a> </td>

<td width="25%"><a href="index.php?module=Settings&action=SettingsSubMenu&type=FieldOrder&parenttab=Settings"><img src="{$IMAGE_PATH}block.gif" alt="{$MOD.LBL_FIELD_ORDERING_DESC}" title="{$MOD.LBL_FIELD_ORDERING_DESC}"  border="0" /></a><br />
<a href="index.php?module=Settings&action=SettingsSubMenu&type=FieldOrder&parenttab=Settings" alt="{$MOD.LBL_FIELD_ORDERING_DESC}" title="{$MOD.LBL_FIELD_ORDERING_DESC}">{$MOD.LBL_FIELD_ORDERING}</a> </td>
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

<td width="25%"><a href="index.php?module=Users&action=listemailtemplates&parenttab=Settings"><img src="{$IMAGE_PATH}ViewTemplate.gif" border="0" alt="{$MOD.LBL_CREATE_EMAIL_TEMPLATES}" title="{$MOD.LBL_CREATE_EMAIL_TEMPLATES}"/></a><br />
<a href="index.php?module=Users&action=listemailtemplates&parenttab=Settings" alt="{$MOD.LBL_CREATE_EMAIL_TEMPLATES}" title="{$MOD.LBL_CREATE_EMAIL_TEMPLATES}">{$MOD.EMAILTEMPLATES}</a></td>

<td width="25%"><a href="index.php?module=Users&action=listwordtemplates&parenttab=Settings"><img src="{$IMAGE_PATH}mailmarge.gif" alt="{$MOD.LBL_UPLOAD_MSWORD_TEMPLATES}" title="{$MOD.LBL_UPLOAD_MSWORD_TEMPLATES}" border="0"/></a><br />
<a href="index.php?module=Users&action=listwordtemplates&parenttab=Settings" alt="{$MOD.LBL_UPLOAD_MSWORD_TEMPLATES}" title="{$MOD.LBL_UPLOAD_MSWORD_TEMPLATES}">{$MOD.WORDINTEGRATION}</a> </td>

<td width="25%"><a href="index.php?module=Users&action=listnotificationschedulers&parenttab=Settings"><img src="{$IMAGE_PATH}notification.gif" alt="{$MOD.LBL_SCHEDULE_EMAIL_NOTIFICATION}" title="{$MOD.LBL_SCHEDULE_EMAIL_NOTIFICATION}"  border="0" /></a><br />
<a href="index.php?module=Users&action=listnotificationschedulers&parenttab=Settings" alt="{$MOD.LBL_SCHEDULE_EMAIL_NOTIFICATION}" title="{$MOD.LBL_SCHEDULE_EMAIL_NOTIFICATION}">{$MOD.NOTIFICATIONSCHEDULERS}</a> </td>

</tr>
<tr><td colspan="4" height="30px;">&nbsp;</td></tr>
<tr>

<td width="25%"><a href="index.php?module=Users&action=listinventorynotifications&parenttab=Settings"><img src="{$IMAGE_PATH}inventory.gif" alt="{$MOD.LBL_INVENTORY_NOTIFICATIONS}" title="{$MOD.LBL_INVENTORY_NOTIFICATIONS}" border="0" /></a><br />
<a href="index.php?module=Users&action=listinventorynotifications&parenttab=Settings" alt="{$MOD.LBL_INVENTORY_NOTIFICATIONS}" title="{$MOD.LBL_INVENTORY_NOTIFICATIONS}">{$MOD.INVENTORYNOTIFICATION}</a></td>

<td><a href="index.php?module=Users&action=OrganizationTermsandConditions&parenttab=Settings"><img src="{$IMAGE_PATH}terms.gif" alt="{$MOD.LBL_INVENTORY_TERMSANDCONDITIONS}" title="{$MOD.LBL_INVENTORY_TERMSANDCONDITIONS}" border="0"/></a><br />
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

<td width="25%"><a href="index.php?module=Settings&action=OrganizationConfig&parenttab=Settings"><img src="{$IMAGE_PATH}company.gif" border="0" alt="{$MOD.LBL_SPECIFY_COMPANY_DETAILS}" title="{$MOD.LBL_SPECIFY_COMPANY_DETAILS}"/></a><br />
<a href="index.php?module=Settings&action=OrganizationConfig&parenttab=Settings" alt="{$MOD.LBL_SPECIFY_COMPANY_DETAILS}" title="{$MOD.LBL_SPECIFY_COMPANY_DETAILS}">{$MOD.LBL_ORGANIZATION_DETAILS}</a></td>

<td width="25%"><a href="index.php?module=Settings&action=EmailConfig&parenttab=Settings"><img src="{$IMAGE_PATH}ogmailserver.gif" alt="{$MOD.LBL_CONFIGURE_MAIL_SERVER}" title="{$MOD.LBL_CONFIGURE_MAIL_SERVER}" border="0"/></a><br />
<a href="index.php?module=Settings&action=EmailConfig&parenttab=Settings" alt="{$MOD.LBL_CONFIGURE_MAIL_SERVER}" title="{$MOD.LBL_CONFIGURE_MAIL_SERVER}">{$MOD.LBL_EMAIL_CONFIG}</a></td>

<td width="25%"><a href="index.php?module=Settings&action=BackupServerConfig&parenttab=Settings"><img src="{$IMAGE_PATH}backupserver.gif" alt="{$MOD.LBL_CONFIGURE_BACKUP_SERVER}" title="{$MOD.LBL_CONFIGURE_BACKUP_SERVER}"  border="0" /></a><br />
<a href="index.php?module=Settings&action=BackupServerConfig&parenttab=Settings" alt="{$MOD.LBL_CONFIGURE_BACKUP_SERVER}" title="{$MOD.LBL_CONFIGURE_BACKUP_SERVER}">{$MOD.LBL_BACKUP_SERVER_CONFIGURATION}</a> </td>

<td width="25%"><a href="index.php?module=System&action=systemconfig&parenttab=Settings"><img src="{$IMAGE_PATH}system.gif" alt="{$MOD.LBL_SYSTEM_CONFIGURATION}" title="{$MOD.LBL_SYSTEM_CONFIGURATION}" border="0" /></a><br />
<a href="index.php?module=System&action=systemconfig&parenttab=Settings" alt="{$MOD.LBL_SYSTEM_CONFIGURATION}" title="{$MOD.LBL_SYSTEM_CONFIGURATION}">{$MOD.LBL_SYSTEM_CONFIG}</a></td>

</tr>

<tr><td colspan="4" height="30px;">&nbsp;</td></tr>

<tr>

<td><a href="index.php?module=Settings&action=CurrencyListView&parenttab=Settings"><img src="{$IMAGE_PATH}currency.gif" alt="{$MOD.LBL_CURRENCY_CONFIGURATION}" title="{$MOD.LBL_CURRENCY_CONFIGURATION}" border="0"/></a><br />
<a href="index.php?module=Settings&action=CurrencyListView&parenttab=Settings" alt="{$MOD.LBL_CURRENCY_CONFIGURATION}" title="{$MOD.LBL_CURRENCY_CONFIGURATION}">{$MOD.LBL_CURRENCY_CONFIG}</a> </td>


<td><a href="index.php?module=Migration&action=MigrationStep1&parenttab=Settings"><img src="{$IMAGE_PATH}migrate.gif" alt="{$MOD.LBL_MIGRATION_INFO}" title="{$MOD.LBL_MIGRATION_INFO}" border="0"/></a><br />
<a href="index.php?module=Migration&action=MigrationStep1&parenttab=Settings" alt="{$MOD.LBL_MIGRATION_INFO}" title="{$MOD.LBL_MIGRATION_INFO}">{$MOD.LBL_MIGRATION}</a></td>

</tr>
<tr><td colspan="4" height="30px;">&nbsp;</td></tr>
</table></td></tr>
</table>
</td>
</tr>
</table>
		{include file='SettingsSubMenu.tpl'}
