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
<table border=0 cellspacing=0 cellpadding=20 width=90% class="settingsUI">
<tr>
<td valign=top>
	<table border=0 cellspacing=0 cellpadding=0 width=100%>
	<tr>
	<td valign=top>
		<!--Left Side Navigation Table-->
		<table border=0 cellspacing=0 cellpadding=0 width=100%>
		<tr>
			<td class="settingsTabHeader" nowrap>{$MOD.LBL_USER_MANAGEMENT}</td>
		</tr>

		{if ($smarty.request.action eq 'index' && $smarty.request.module neq 'Recyclebin') ||  $smarty.request.action eq 'DetailView' || $smarty.request.action eq 'EditView' || $smarty.request.action eq 'ListView' }
		<tr><td class="settingsTabSelected" nowrap><a href="index.php?module=Administration&action=index&parenttab=Settings">{$MOD.LBL_USERS}</a></td></tr>
		{else}
		<tr><td class="settingsTabList" nowrap><a href="index.php?module=Administration&action=index&parenttab=Settings">{$MOD.LBL_USERS}</a></td></tr>
		{/if}

		{if $smarty.request.action eq 'listroles' ||  $smarty.request.action eq 'RoleDetailView' ||  $smarty.request.action eq 'saverole' ||  $smarty.request.action eq 'createrole' || $smarty.request.action eq 'RoleDeleteStep1'} 		
		<tr><td class="settingsTabSelected" nowrap><a href="index.php?module=Settings&action=listroles&parenttab=Settings">{$MOD.LBL_ROLES}</a></td></tr>
		{else}	
		<tr><td class="settingsTabList" nowrap><a href="index.php?module=Settings&action=listroles&parenttab=Settings">{$MOD.LBL_ROLES}</a></td></tr>
		{/if}

		{if $smarty.request.action eq 'ListProfiles' || $smarty.request.action eq 'profilePrivileges' ||  $smarty.request.action eq 'CreateProfile' ||  $smarty.request.action eq 'SaveProfile' || $smarty.request.action eq 'UpdateProfileChanges' }
		<tr><td class="settingsTabSelected" nowrap><a href="index.php?module=Settings&action=ListProfiles&parenttab=Settings">{$MOD.LBL_PROFILES}</a></td></tr>
		{else}
		<tr><td class="settingsTabList" nowrap><a href="index.php?module=Settings&action=ListProfiles&parenttab=Settings">{$MOD.LBL_PROFILES}</a></td></tr>
		{/if}

		{if $smarty.request.action eq 'listgroups' || $smarty.request.action eq 'GroupDetailView' || $smarty.request.action eq 'createnewgroup' || $smarty.request.action eq 'SaveGroup'}
		<tr><td class="settingsTabSelected" nowrap><a href="index.php?module=Settings&action=listgroups&parenttab=Settings">{$MOD.USERGROUPLIST}</a></td></tr>   	
		{else}
		<tr><td class="settingsTabList" nowrap><a href="index.php?module=Settings&action=listgroups&parenttab=Settings">{$MOD.USERGROUPLIST}</a></td></tr>
		{/if}

		{if  $smarty.request.action eq 'OrgSharingEditView' || $smarty.request.action eq 'OrgSharingDetailView' || $smarty.request.action eq 'SaveOrgSharing'}
		<tr><td class="settingsTabSelected" nowrap><a href="index.php?module=Settings&action=OrgSharingDetailView&parenttab=Settings">{$MOD.LBL_SHARING_ACCESS}</a></td></tr>  	
		{else}
		<tr><td class="settingsTabList" nowrap><a href="index.php?module=Settings&action=OrgSharingDetailView&parenttab=Settings">{$MOD.LBL_SHARING_ACCESS}</a></td></tr>
		{/if}
			
		{if $smarty.request.action eq 'DefaultFieldPermissions' || $smarty.request.action eq 'UpdateDefaultFieldLevelAccess' || $smarty.request.action eq 'EditDefOrgFieldLevelAccess' }
		<tr><td class="settingsTabSelected" nowrap><a href="index.php?module=Settings&action=DefaultFieldPermissions&parenttab=Settings">{$MOD.LBL_FIELDS_ACCESS}</a></td></tr>
		{else}
		<tr><td class="settingsTabList" nowrap><a href="index.php?module=Settings&action=DefaultFieldPermissions&parenttab=Settings">{$MOD.LBL_FIELDS_ACCESS}</a></td></tr>
		{/if}
			
		{if $smarty.request.action eq 'AuditTrailList'}
		<tr><td class="settingsTabSelected" nowrap><a href="index.php?module=Settings&action=AuditTrailList&parenttab=Settings">{$MOD.LBL_AUDIT_TRAIL}</a></td></tr>
        {else}
        <tr><td class="settingsTabList" nowrap><a href="index.php?module=Settings&action=AuditTrailList&parenttab=Settings">{$MOD.LBL_AUDIT_TRAIL}</a></td></tr>
        {/if}

		{if $smarty.request.action eq 'ListLoginHistory'}
		<tr><td class="settingsTabSelected" nowrap><a href="index.php?module=Settings&action=ListLoginHistory&parenttab=Settings">{$MOD.LBL_LOGIN_HISTORY_DETAILS}</a></td></tr>
		{else}
		<tr><td class="settingsTabList" nowrap><a href="index.php?module=Settings&action=ListLoginHistory&parenttab=Settings">{$MOD.LBL_LOGIN_HISTORY_DETAILS}</a></td></tr>
		{/if}

		
		<tr><td class="settingsTabHeader" nowrap>{$MOD.LBL_STUDIO}</td></tr>
		{if  $smarty.request.action eq 'LayoutBlockList'}
		<tr><td class="settingsTabSelected" nowrap><a href="index.php?module=Settings&action=LayoutBlockList&parenttab=Settings">{$MOD.LBL_LAYOUT_EDITOR}</a></td></tr>
		{else}
		<tr><td class="settingsTabList" nowrap><a href="index.php?module=Settings&action=LayoutBlockList&parenttab=Settings">{$MOD.LBL_LAYOUT_EDITOR}</a></td></tr>
		{/if}
		
		{if  $smarty.request.action eq 'CustomFieldList' || $smarty.request.action eq 'LeadCustomFieldMapping'}
		<tr><td class="settingsTabSelected" nowrap><a href="index.php?module=Settings&action=CustomFieldList&parenttab=Settings">{$MOD.LBL_CUSTOM_FIELDS}</a></td></tr>
		{else}
		<tr><td class="settingsTabList" nowrap><a href="index.php?module=Settings&action=CustomFieldList&parenttab=Settings">{$MOD.LBL_CUSTOM_FIELDS}</a></td></tr>
		{/if}
				
		{if  $smarty.request.action eq 'PickList' ||  $smarty.request.action eq 'SettingsAjax'}
		<tr><td class="settingsTabSelected" nowrap><a href="index.php?module=Settings&action=PickList&parenttab=Settings">{$MOD.LBL_PICKLIST_EDITOR}</a></td></tr>						     {else}
		<tr><td class="settingsTabList" nowrap><a href="index.php?module=Settings&action=PickList&parenttab=Settings">{$MOD.LBL_PICKLIST_EDITOR}</a></td></tr>
		{/if}

		{if $smarty.request.module eq 'Recyclebin' && $smarty.request.action eq 'index'}
		<tr><td class="settingsTabSelected" nowrap><a href="index.php?module=Recyclebin&action=index&parenttab=Settings">{$MOD.LBL_RECYCLEBIN}</a></td></tr>
		{else}
		<tr><td class="settingsTabList" nowrap><a href="index.php?module=Recyclebin&action=index&parenttab=Settings">{$MOD.LBL_RECYCLEBIN}</a></td></tr>
		{/if}		

		{* vtlib customization: Module Manager *}
		{if  $smarty.request.action eq 'ModuleManager' ||  $smarty.request.action eq 'SettingsAjax'}
		<tr><td class="settingsTabSelected" nowrap><a href="index.php?module=Settings&action=ModuleManager&parenttab=Settings">{$MOD.VTLIB_LBL_MODULE_MANAGER}</a></td></tr>						     {else}
		<tr><td class="settingsTabList" nowrap><a href="index.php?module=Settings&action=ModuleManager&parenttab=Settings">{$MOD.VTLIB_LBL_MODULE_MANAGER}</a></td></tr>
		{/if}
		{* END *}
	
		<tr><td class="settingsTabHeader" nowrap>{$MOD.LBL_COMMUNICATION_TEMPLATES}</td></tr>

		{if $smarty.request.action eq 'listemailtemplates' || $smarty.request.action eq 'detailviewemailtemplate' || $smarty.request.action eq 'editemailtemplate' || $smarty.request.action eq 'saveemailtemplate' || $smarty.request.action eq 'deleteemailtemplate' || $smarty.request.action eq 'createemailtemplate'}
		<tr><td class="settingsTabSelected" nowrap><a href="index.php?module=Settings&action=listemailtemplates&parenttab=Settings">{$MOD.EMAILTEMPLATES}</a></td></tr>
		{else}
		<tr><td class="settingsTabList" nowrap><a href="index.php?module=Settings&action=listemailtemplates&parenttab=Settings">{$MOD.EMAILTEMPLATES}</a></td></tr>
		{/if}

		{if $smarty.request.action eq 'listwordtemplates' || $smarty.request.action eq 'savewordtemplate' || $smarty.request.action eq 'deletewordtemplate' || $smarty.request.action eq 'upload'}
		<tr><td class="settingsTabSelected" nowrap><a href="index.php?module=Settings&action=listwordtemplates&parenttab=Settings">{$MOD.WORDINTEGRATION}</a></td></tr>
		{else}	
		<tr><td class="settingsTabList" nowrap><a href="index.php?module=Settings&action=listwordtemplates&parenttab=Settings">{$MOD.WORDINTEGRATION}</a></td></tr>
		{/if}

		{if $smarty.request.action eq 'listnotificationschedulers' || $smarty.request.action eq 'SettingsAjax'}
		<tr><td class="settingsTabSelected" nowrap><a href="index.php?module=Settings&action=listnotificationschedulers&parenttab=Settings">{$MOD.NOTIFICATIONSCHEDULERS}</a></td></tr>
		{else}
		<tr><td class="settingsTabList" nowrap><a href="index.php?module=Settings&action=listnotificationschedulers&parenttab=Settings">{$MOD.NOTIFICATIONSCHEDULERS}</a></td></tr>
		{/if}
				
		{if $smarty.request.action eq 'listinventorynotifications' || $smarty.request.action eq 'SettingsAjax'}
		<tr><td class="settingsTabSelected" nowrap><a href="index.php?module=Settings&action=listinventorynotifications&parenttab=Settings">{$MOD.INVENTORYNOTIFICATION}</a></td></tr>
		{else}	
		<tr><td class="settingsTabList" nowrap><a href="index.php?module=Settings&action=listinventorynotifications&parenttab=Settings">{$MOD.INVENTORYNOTIFICATION}</a></td></tr>
		{/if} 	

		

		<tr><td class="settingsTabHeader" nowrap>{$MOD.LBL_OTHER_SETTINGS}</td></tr>
		{if $smarty.request.action eq 'OrganizationConfig' || $smarty.request.action eq 'EditCompanyDetails' || $smarty.request.action eq 'add2db'}
		<tr><td class="settingsTabSelected" nowrap><a href="index.php?module=Settings&action=OrganizationConfig&parenttab=Settings">{$MOD.LBL_COMPANY_DETAILS}</a></td></tr>
		{else}	
		<tr><td class="settingsTabList" nowrap><a href="index.php?module=Settings&action=OrganizationConfig&parenttab=Settings">{$MOD.LBL_COMPANY_DETAILS}</a></td></tr>
		{/if}
		
		{if  $smarty.request.action eq 'EmailConfig' ||  $smarty.request.action eq 'Save' }
		<tr><td class="settingsTabSelected" nowrap><a href="index.php?module=Settings&action=EmailConfig&parenttab=Settings">{$MOD.LBL_MAIL_SERVER_SETTINGS}</a></td></tr>
		{else}
		<tr><td class="settingsTabList" nowrap><a href="index.php?module=Settings&action=EmailConfig&parenttab=Settings">{$MOD.LBL_MAIL_SERVER_SETTINGS}</a></td></tr>
		{/if}
		
		{if $smarty.request.action eq 'BackupServerConfig' || $smarty.request.action eq 'Save'}
		<tr><td class="settingsTabSelected" nowrap><a href="index.php?module=Settings&action=BackupServerConfig&parenttab=Settings">{$MOD.LBL_BACKUP_SERVER_SETTINGS}</a></td></tr>
		{else}
		<tr><td class="settingsTabList" nowrap><a href="index.php?module=Settings&action=BackupServerConfig&parenttab=Settings">{$MOD.LBL_BACKUP_SERVER_SETTINGS}</a></td></tr>
		{/if}
		
		{if $smarty.request.action eq 'ListModuleOwners' || $smarty.request.action eq 'SettingsAjax'}
		<tr><td class="settingsTabSelected" nowrap><a href="index.php?module=Settings&action=ListModuleOwners&parenttab=Settings">{$MOD.LBL_MODULE_OWNERS}</a></td></tr>
		{else}
		<tr><td class="settingsTabList" nowrap><a href="index.php?module=Settings&action=ListModuleOwners&parenttab=Settings">{$MOD.LBL_MODULE_OWNERS}</a></td></tr>
		{/if}
		
		{if $smarty.request.action eq 'CurrencyListView' ||  $smarty.request.action eq 'CurrencyEditView' ||  $smarty.request.action eq 'SaveCurrencyInfo'}
		<tr><td class="settingsTabSelected" nowrap><a href="index.php?module=Settings&action=CurrencyListView&parenttab=Settings">{$MOD.LBL_CURRENCY_SETTINGS}</a></td></tr>
		{else}
		<tr><td class="settingsTabList" nowrap><a href="index.php?module=Settings&action=CurrencyListView&parenttab=Settings">{$MOD.LBL_CURRENCY_SETTINGS}</a></td></tr>
		{/if}
		
		{if $smarty.request.action eq 'TaxConfig'}
		<tr><td class="settingsTabSelected" nowrap><a href="index.php?module=Settings&action=TaxConfig&parenttab=Settings">{$MOD.LBL_TAX_SETTINGS}</a></td></tr>
		{else}
		<tr><td class="settingsTabList" nowrap><a href="index.php?module=Settings&action=TaxConfig&parenttab=Settings">{$MOD.LBL_TAX_SETTINGS}</a></td></tr>
		{/if}

		{if $smarty.request.action eq 'listsysconfig'}
		<tr><td class="settingsTabSelected" nowrap><a href="index.php?module=System&action=listsysconfig&parenttab=Settings">{$MOD.LBL_SYSTEM_INFO}</a></td></tr>
		{else}
		<tr><td class="settingsTabList" nowrap><a href="index.php?module=System&action=listsysconfig&parenttab=Settings">{$MOD.LBL_SYSTEM_INFO}</a></td></tr>
		{/if}

		{if $smarty.request.action eq 'ProxyServerConfig'}
		<tr><td class="settingsTabSelected" nowrap><a href="index.php?module=Settings&action=ProxyServerConfig&parenttab=Settings">{$MOD.LBL_PROXY_SETTINGS}</a></td></tr>
		{else}
		<tr><td class="settingsTabList" nowrap><a href="index.php?module=Settings&action=ProxyServerConfig&parenttab=Settings">{$MOD.LBL_PROXY_SETTINGS}</a></td></tr>
		{/if}

		{if  $smarty.request.action eq 'Announcements' ||  $smarty.request.action eq 'SettingsAjax' }
		<tr><td class="settingsTabSelected" nowrap><a href="index.php?module=Settings&action=Announcements&parenttab=Settings">{$MOD.LBL_ANNOUNCEMENT}</a></td></tr>		
		{else}
		<tr><td class="settingsTabList" nowrap><a href="index.php?module=Settings&action=Announcements&parenttab=Settings">{$MOD.LBL_ANNOUNCEMENT}</a></td></tr>
		{/if}
		
		{if $smarty.request.action eq 'DefModuleView'}
		<tr><td class="settingsTabSelected" nowrap><a href="index.php?module=Settings&action=DefModuleView&parenttab=Settings">{$MOD.LBL_DEFAULT_MODULE_VIEW}</a></td></tr>
		{else}
		<tr><td class="settingsTabList" nowrap><a href="index.php?module=Settings&action=DefModuleView&parenttab=Settings">{$MOD.LBL_DEFAULT_MODULE_VIEW}</a></td></tr>
		{/if}
	
		<tr><td class="settingsTabList" nowrap><a href="index.php?module=Migration&action=index&parenttab=Settings">{$MOD.LBL_MIGRATION}</a></td></tr>

		{if $smarty.request.action eq 'OrganizationTermsandConditions' || $smarty.request.action eq 'savetermsandconditions'}
		<tr><td class="settingsTabSelected" nowrap><a href="index.php?module=Settings&action=OrganizationTermsandConditions&parenttab=Settings">{$MOD.LBL_INVENTORY_TANDC}</a></td></tr>
		{else}
		<tr><td class="settingsTabList" nowrap><a href="index.php?module=Settings&action=OrganizationTermsandConditions&parenttab=Settings">{$MOD.LBL_INVENTORY_TANDC}</a></td></tr>
		{/if}

<!-- Added For Custom Invoice Number #start -->

		{if $smarty.request.action eq 'CustomInvoiceNo'}
			<tr><td class="settingsTabSelected" nowrap><a href="index.php?module=Settings&action=CustomInventorySeq&parenttab=Settings">{$MOD.LBL_CUSTOMIZE_INVENTORY_NUMBER}</a></td></tr>
		{else}
			<tr><td class="settingsTabList" nowrap><a href="index.php?module=Settings&action=CustomInventorySeq&parenttab=Settings">{$MOD.LBL_CUSTOMIZE_INVENTORY_NUMBER}</a></td></tr>
		{/if}

<!-- Added For Custom Invoice Number #end -->

		</table>
		<!-- Left side navigation table ends -->
		
	</td>
	<td width=90% class="small settingsSelectedUI" valign=top align=left>




