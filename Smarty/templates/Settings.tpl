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

	{include file="Buttons_List1.tpl"}

<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">
<tbody><tr>
        <td valign="top"><img src="{$IMAGE_PATH}showPanelTopLeft.gif"></td>
	<td class="showPanelBg" style="padding: 10px;" valign="top" width="100%">
	<br>
	<div align=center>
	<table border=0 cellspacing=0 cellpadding=20 width=90% class="settingsUI">
	<tr>
		<td>
			<!--All Icons table -->
			<table border=0 cellspacing=0 cellpadding=0 width=100%>
			<tr>

				<td class="settingsTabHeader">
				<!-- Users & Access Management -->
					{$MOD.LBL_USER_MANAGEMENT}	
				</td>
			</tr>
			<tr>
				<td class="settingsIconDisplay small">
				<!-- Icons for Users & Access Management -->
				
				<table border=0 cellspacing=0 cellpadding=10 width=100%>

				<tr>
					<td width=25% valign=top>
					<!-- icon 1-->
						<table border=0 cellspacing=0 cellpadding=5 width=100%>
						<tr>
							<td rowspan=2 valign=top><a href="index.php?module=Administration&action=index&parenttab=Settings"><img src="{$IMAGE_PATH}ico-users.gif" alt="{$MOD.LBL_USERS}" width="48" height="48" border=0 title="{$MOD.LBL_USERS}"></a></td>
							<td class=big valign=top><a href="index.php?module=Administration&action=index&parenttab=Settings">{$MOD.LBL_USERS}</a></td>
						</tr>

						<tr>
							<td class="small" valign=top>{$MOD.LBL_USER_DESCRIPTION}</td>
						</tr>
						</table>
					</td>
					<td width=25% valign=top>
					<!-- icon 2-->
						<table border=0 cellspacing=0 cellpadding=5 width=100%>

						<tr>
							<td rowspan=2 valign=top><a href="index.php?module=Settings&action=listroles&parenttab=Settings"><img src="{$IMAGE_PATH}ico-roles.gif" alt="{$MOD.LBL_ROLES}" width="48" height="48" border=0 title="{$MOD.LBL_ROLES}"></a></td>
							<td class=big valign=top><a href="index.php?module=Settings&action=listroles&parenttab=Settings">{$MOD.LBL_ROLES}</a></td>
						</tr>
						<tr>
							<td class="small" valign=top>{$MOD.LBL_ROLE_DESCRIPTION}</td>
						</tr>
						</table>

					</td>
					<td width=25% valign=top>
					<!-- icon 3-->
						<table border=0 cellspacing=0 cellpadding=5 width=100%>
						<tr>
							<td rowspan=2 valign=top><a href="index.php?module=Settings&action=ListProfiles&parenttab=Settings"><img src="{$IMAGE_PATH}ico-profile.gif" alt="{$MOD.LBL_PROFILES}" width="48" height="48" border=0 title="{$MOD.LBL_PROFILES}"></a></td>
							<td class=big valign=top><a href="index.php?module=Settings&action=ListProfiles&parenttab=Settings">{$MOD.LBL_PROFILES}</a></td>
						</tr>

						<tr>
							<td class="small" valign=top>{$MOD.LBL_PROFILE_DESCRIPTION}</td>
						</tr>
						</table>
					</td>
					<td width=25% valign=top>
					<!-- icon 4-->
						<table border=0 cellspacing=0 cellpadding=5 width=100%>

						<tr>
							<td rowspan=2 valign=top><a href="index.php?module=Settings&action=listgroups&parenttab=Settings"><img src="{$IMAGE_PATH}ico-groups.gif" alt="{$MOD.USERGROUPLIST}" width="48" height="48" border=0 title="{$MOD.USERGROUPLIST}"></a></td>
							<td class=big valign=top><a href="index.php?module=Settings&action=listgroups&parenttab=Settings">{$MOD.USERGROUPLIST}</a></td>
						</tr>
						<tr>
							<td class="small" valign=top>{$MOD.LBL_GROUP_DESCRIPTION}</td>
						</tr>
						</table>

					</td>
				</tr>
				<!-- Row 2 -->
				<tr>
					<td width=25% valign=top>
					<!-- icon 5-->
						<table border=0 cellspacing=0 cellpadding=5 width=100%>
						<tr>
							<td rowspan=2 valign=top><a href="index.php?module=Settings&action=OrgSharingDetailView&parenttab=Settings"><img src="{$IMAGE_PATH}shareaccess.gif" alt="{$MOD.LBL_SHARING_ACCESS}" width="48" height="48" border=0 title="{$MOD.LBL_SHARING_ACCESS}"></a></td>

							<td class=big valign=top><a href="index.php?module=Settings&action=OrgSharingDetailView&parenttab=Settings">{$MOD.LBL_SHARING_ACCESS}</a></td>
						</tr>
						<tr>
							<td class="small" valign=top>{$MOD.LBL_SHARING_ACCESS_DESCRIPTION}</td>
						</tr>
						</table>
					</td>
					<td width=25% valign=top>

					<!-- icon 6 -->
						<table border=0 cellspacing=0 cellpadding=5 width=100%>
						<tr>
							<td rowspan=2 valign=top><a href="index.php?module=Settings&action=DefaultFieldPermissions&parenttab=Settings"><img src="{$IMAGE_PATH}orgshar.gif" alt="{$MOD.LBL_FIELDS_TO_BE_SHOWN}" width="48" height="48" border=0 title="{$MOD.LBL_FIELDS_TO_BE_SHOWN}"></a></td>
							<td class=big valign=top><a href="index.php?module=Settings&action=DefaultFieldPermissions&parenttab=Settings">{$MOD.LBL_FIELDS_ACCESS}</a></td>
						</tr>
						<tr>
							<td class="small" valign=top>{$MOD.LBL_SHARING_FIELDS_DESCRIPTION}</td>

						</tr>
						</table>
					</td>
					<td width=25% valign=top>
					<!-- icon 7-->
						<table border=0 cellspacing=0 cellpadding=5 width=100%>
						<tr>
							<td rowspan=2 valign=top><a href="index.php?module=Settings&action=AuditTrailList&parenttab=Settings"><img border=0 src="{$IMAGE_PATH}audit.gif" alt="{$MOD.LBL_AUDIT_TRAIL}" title="{$MOD.LBL_AUDIT_TRAIL}"></a></td>
							<td class=big valign=top><a href="index.php?module=Settings&action=AuditTrailList&parenttab=Settings">{$MOD.LBL_AUDIT_TRAIL}</a></td>
						</tr>

						<tr>
							<td class="small" valign=top>{$MOD.LBL_AUDIT_DESCRIPTION}</td>
						</tr>
						</table>

						
					</td>
					<td width=25% valign=top>
					<!-- icon 8-->	
						<table border=0 cellspacing=0 cellpadding=5 width=100%>
						<tr>
							<td rowspan=2 valign=top><a href="index.php?module=Settings&action=ListLoginHistory&parenttab=Settings"><img src="{$IMAGE_PATH}set-IcoLoginHistory.gif" alt="{$MOD.LBL_LOGIN_HISTORY_DETAILS}" width="48" height="48" border=0 title="{$MOD.LBL_LOGIN_HISTORY_DETAILS}"></a></td>
							<td class=big valign=top><a href="index.php?module=Settings&action=ListLoginHistory&parenttab=Settings">{$MOD.LBL_LOGIN_HISTORY_DETAILS}</a></td>
						</tr>
						<tr>
							<td class="small" valign=top>{$MOD.LBL_LOGIN_HISTORY_DESCRIPTION}</td>
						</tr>

						</table>
					</td>
					<!-- Row 3 -->
					<tr>
					
				</tr>
				</table>


				</td>
			</tr>


			<tr>
				<td class="settingsTabHeader">

				<!-- Studio  -->
					{$MOD.LBL_STUDIO}	
				</td>
			</tr>
			<tr>
				<td class="settingsIconDisplay small">
				<!-- Icons for Users & Access Management -->
				
				<table border=0 cellspacing=0 cellpadding=10 width=100%>
				<tr>

					<td width=25% valign=top>
					<!-- icon 9-->
						<table border=0 cellspacing=0 cellpadding=5 width=100%>
						<tr>
							<td rowspan=2 valign=top><a href="index.php?module=Settings&action=CustomFieldList&parenttab=Settings"><img border=0 src="{$IMAGE_PATH}custom.gif" alt="{$MOD.LBL_CUSTOM_FIELDS}" title="{$MOD.LBL_CUSTOM_FIELDS}"></a></td>
							<td class=big valign=top><a href="index.php?module=Settings&action=CustomFieldList&parenttab=Settings">{$MOD.LBL_CUSTOM_FIELDS}</a></td>
						</tr>
						<tr>

							<td class="small" valign=top>{$MOD.LBL_CUSTOM_FIELDS_DESCRIPTION}</td>
						</tr>
						</table>
					</td>
					<td width=25% valign=top>
					<!-- icon 10-->
						<table border=0 cellspacing=0 cellpadding=5 width=100%>
						<tr>

							<td rowspan=2 valign=top><a href="index.php?module=Settings&action=PickList&parenttab=Settings"><img border=0 src="{$IMAGE_PATH}picklist.gif" alt="{$MOD.LBL_PICKLIST_EDITOR}" title="{$MOD.LBL_PICKLIST_EDITOR}"></a></td>
							<td class=big valign=top><a href="index.php?module=Settings&action=PickList&parenttab=Settings">{$MOD.LBL_PICKLIST_EDITOR}</a></td>
						</tr>
						<tr>
							<td class="small" valign=top>{$MOD.LBL_PICKLIST_DESCRIPTION}</td>	
						</tr>
						</table>
					</td>

					<td width=25% valign=top>
					<!-- icon 11-->
						<table border=0 cellspacing=0 cellpadding=5 width=100%>
						<tr>
							<td rowspan=2 valign=top><a href="index.php?module=Recyclebin&action=index&parenttab=Settings"><img border=0 src="{$IMAGE_PATH}settingsTrash.gif" alt="{$MOD.LBL_RECYCLEBIN}" title="{$MOD.LBL_RECYCLEBIN}"></a></td>
							<td class=big valign=top><a href="index.php?module=Recyclebin&action=index&parenttab=Settings">{$MOD.LBL_RECYCLEBIN}</a></td>
						</tr>
						<tr>
							<td class="small" valign=top>{$MOD.LBL_RECYCLEBIN_DESCRIPTION}</td>

						</tr>
						</table>
					</td>
					<td width=25% valign=top>
					<!-- empty-->
						<table border=0 cellspacing=0 cellpadding=5 width=100%>
						<tr>
							<td rowspan=2 valign=top>&nbsp;</td>
							<td class=big valign=top>&nbsp;</td>

						</tr>
						<tr>
							<td class="small" valign=top>&nbsp;</td>
						</tr>
						</table>
					</td>
				</tr>
				</table>
				
				
				</td>

			</tr>
			
			
			
			<tr>
				<td class="settingsTabHeader">
				<!-- Communication Templates -->
					{$MOD.LBL_COMMUNICATION_TEMPLATES}
				</td>
			</tr>
			<tr>
				<td class="settingsIconDisplay small">

				<!-- Icons for Communication Templates -->
				
				<table border=0 cellspacing=0 cellpadding=10 width=100%>
				<tr>
					<td width=25% valign=top>
					<!-- icon 12-->
						<table border=0 cellspacing=0 cellpadding=5 width=100%>
						<tr>
							<td rowspan=2 valign=top><a href="index.php?module=Settings&action=listemailtemplates&parenttab=Settings"><img border=0 src="{$IMAGE_PATH}ViewTemplate.gif" alt="{$MOD.EMAILTEMPLATES}" title="{$MOD.EMAILTEMPLATES}"></a></td>
							<td class=big valign=top><a href="index.php?module=Settings&action=listemailtemplates&parenttab=Settings">{$MOD.EMAILTEMPLATES}</a></td>

						</tr>
						<tr>
							<td class="small" valign=top>{$MOD.LBL_EMAIL_TEMPLATE_DESCRIPTION}</td>
						</tr>
						</table>
					</td>
					<td width=25% valign=top>
					<!-- icon 13-->

						<table border=0 cellspacing=0 cellpadding=5 width=100%>
						<tr>
							<td rowspan=2 valign=top><a href="index.php?module=Settings&action=listwordtemplates&parenttab=Settings"><img border=0 src="{$IMAGE_PATH}mailmarge.gif" alt="{$MOD.LBL_MAIL_MERGE}" title="{$MOD.LBL_MAIL_MERGE}"></a></td>
							<td class=big valign=top><a href="index.php?module=Settings&action=listwordtemplates&parenttab=Settings">{$MOD.WORDINTEGRATION}</a></td>
						</tr>
						<tr>
							<td class="small" valign=top>{$MOD.LBL_MAIL_MERGE_DESCRIPTION}</td>
						</tr>

						</table>
					</td>
					<td width=25% valign=top>
					<!-- icon 14-->
						<table border=0 cellspacing=0 cellpadding=5 width=100%>
						<tr>
							<td rowspan=2 valign=top><a href="index.php?module=Settings&action=listnotificationschedulers&parenttab=Settings"><img border=0 src="{$IMAGE_PATH}notification.gif" alt="{$MOD.NOTIFICATIONSCHEDULERS}" title="{$MOD.NOTIFICATIONSCHEDULERS}"></a></td>
							<td class=big valign=top><a href="index.php?module=Settings&action=listnotificationschedulers&parenttab=Settings">{$MOD.NOTIFICATIONSCHEDULERS}</a></td>

						</tr>
						<tr>
							<td class="small" valign=top>{$MOD.LBL_NOTIF_SCHED_DESCRIPTION}</td>
						</tr>
						</table>
					</td>
					<td width=25% valign=top>
					<!-- icon 15-->

						<table border=0 cellspacing=0 cellpadding=5 width=100%>
						<tr>
							<td rowspan=2 valign=top><a href="index.php?module=Settings&action=listinventorynotifications&parenttab=Settings"><img border=0 src="{$IMAGE_PATH}inventory.gif" alt="{$MOD.INVENTORYNOTIFICATION}" title="{$MOD.INVENTORYNOTIFICATION}"></a></td>
							<td class=big valign=top><a href="index.php?module=Settings&action=listinventorynotifications&parenttab=Settings">{$MOD.INVENTORYNOTIFICATION}</a></td>
						</tr>
						<tr>
							<td class="small" valign=top>{$MOD.LBL_INV_NOTIF_DESCRIPTION}</td>
						</tr>

						</table>
					</td>
				</tr>
				</table>
				
				
				</td>
			</tr>
			<tr>
				<td class="settingsTabHeader">
				<!-- Other settings -->
					{$MOD.LBL_OTHER_SETTINGS}
				</td>

			</tr>
			<tr>
				<td class="settingsIconDisplay small">
				<!-- Icons for Other Settings-->
				
				<table border=0 cellspacing=0 cellpadding=10 width=100%>
				<tr>
					<td width=25% valign=top>
					<!-- icon 16-->
						<table border=0 cellspacing=0 cellpadding=5 width=100%>

						<tr>
							<td rowspan=2 valign=top><a href="index.php?module=Settings&action=OrganizationConfig&parenttab=Settings"><img border=0 src="{$IMAGE_PATH}company.gif" alt="{$MOD.LBL_COMPANY_DETAILS}" title="{$MOD.LBL_COMPANY_DETAILS}"></a></td>
							<td class=big valign=top><a href="index.php?module=Settings&action=OrganizationConfig&parenttab=Settings">{$MOD.LBL_COMPANY_DETAILS}</a></td>
						</tr>
						<tr>
							<td class="small" valign=top>{$MOD.LBL_COMPANY_DESCRIPTION}</td>
						</tr>
						</table>

					</td>
					<td width=25% valign=top>
					<!-- icon 17-->
						<table border=0 cellspacing=0 cellpadding=5 width=100%>
						<tr>
							<td rowspan=2 valign=top><a href="index.php?module=Settings&action=EmailConfig&parenttab=Settings"><img border=0 src="{$IMAGE_PATH}ogmailserver.gif" alt="{$MOD.LBL_MAIL_SERVER_SETTINGS}" title="{$MOD.LBL_MAIL_SERVER_SETTINGS}"></a></td>
							<td class=big valign=top><a href="index.php?module=Settings&action=EmailConfig&parenttab=Settings">{$MOD.LBL_MAIL_SERVER_SETTINGS}</a></td>
						</tr>

						<tr>
							<td class="small" valign=top>{$MOD.LBL_MAIL_SERVER_DESCRIPTION}</td>
						</tr>
						</table>
					</td>
					<td width=25% valign=top>
					<!-- icon 18-->
						<table border=0 cellspacing=0 cellpadding=5 width=100%>

						<tr>
							<td rowspan=2 valign=top><a href="index.php?module=Settings&action=BackupServerConfig&parenttab=Settings"><img border=0 src="{$IMAGE_PATH}backupserver.gif" alt="{$MOD.LBL_BACKUP_SERVER_SETTINGS}" title="{$MOD.LBL_BACKUP_SERVER_SETTINGS}"></a></td>
							<td class=big valign=top><a href="index.php?module=Settings&action=BackupServerConfig&parenttab=Settings">{$MOD.LBL_BACKUP_SERVER_SETTINGS}</a></td>
						</tr>
						<tr>
							<td class="small" valign=top>{$MOD.LBL_BACKUP_SERVER_DESCRIPTION}</td>
						</tr>
						</table>

					</td>
					<td width=25% valign=top>
					<!-- icon 7-->
						<table border=0 cellspacing=0 cellpadding=5 width=100%>
						<tr>
							<td rowspan=2 valign=top><a href="index.php?module=Settings&action=ListModuleOwners&parenttab=Settings"><img src="{$IMAGE_PATH}assign.gif" alt="{$MOD.LBL_ASSIGN_MODULE_OWNERS}" width="48" height="48" border=0 title="{$MOD.LBL_ASSIGN_MODULE_OWNERS}"></a></td>
							<td class=big valign=top><a href="index.php?module=Settings&action=ListModuleOwners&parenttab=Settings">{$MOD.LBL_MODULE_OWNERS}</a></td>

						</tr>
						<tr>
							<td class="small" valign=top>{$MOD.LBL_MODULE_OWNERS_DESCRIPTION}</td>
						</tr>
						</table>
					</td>
				</tr>
				<!-- Row 2 -->
				<tr>

					<td width=25% valign=top>
					<!-- icon 19-->
						<table border=0 cellspacing=0 cellpadding=5 width=100%>
						<tr>
							<td rowspan=2 valign=top><a href="index.php?module=Settings&action=CurrencyListView&parenttab=Settings"><img border=0 src="{$IMAGE_PATH}currency.gif" alt="{$MOD.LBL_CURRENCY_SETTINGS}" title="{$MOD.LBL_CURRENCY_SETTINGS}"></a></td>
							<td class=big valign=top><a href="index.php?module=Settings&action=CurrencyListView&parenttab=Settings">{$MOD.LBL_CURRENCY_SETTINGS}</a></td>
						</tr>

						<tr>
							<td class="small" valign=top>{$MOD.LBL_CURRENCY_DESCRIPTION}</td>
						</tr>
						</table>
					</td>

					<td width=25% valign=top>
					<!-- icon 20-->
						<table border=0 cellspacing=0 cellpadding=5 width=100%>
						<tr>
							<td rowspan=2 valign=top><a href="index.php?module=Settings&action=TaxConfig&parenttab=Settings"><img border=0 src="{$IMAGE_PATH}taxConfiguration.gif" alt="{$MOD.LBL_TAX_SETTINGS}" title="{$MOD.LBL_TAX_SETTINGS}"></a></td>
							<td class=big valign=top><a href="index.php?module=Settings&action=TaxConfig&parenttab=Settings">{$MOD.LBL_TAX_SETTINGS}</a></td>
						</tr>
						<tr>

							<td class="small" valign=top>{$MOD.LBL_TAX_DESCRIPTION}</td>
						</tr>
						</table>
					</td>
					<td width=25% valign=top>
					<!-- empty -->
						<table border=0 cellspacing=0 cellpadding=5 width=100%>
						<tr>
							<td rowspan=2 valign=top><a href="index.php?module=System&action=listsysconfig&parenttab=Settings"><img border=0 src="{$IMAGE_PATH}system.gif" alt="{$MOD.LBL_SYSTEM_INFO}" title="{$MOD.LBL_SYSTEM_INFO}"></a></td>
							<td class=big valign=top><a href="index.php?module=System&action=listsysconfig&parenttab=Settings">{$MOD.LBL_SYSTEM_INFO}</a></td>

						</tr>
						<tr>
							<td class="small" valign=top>{$MOD.LBL_SYSTEM_DESCRIPTION}</td>
						</tr>
						</table>
					</td>
					<td width=25% valign=top>

					<!-- empty-->
						<table border=0 cellspacing=0 cellpadding=5 width=100%>
						<tr>
							<td rowspan=2 valign=top><a href="index.php?module=Settings&action=ProxyServerConfig&parenttab=Settings"><img border=0 src="{$IMAGE_PATH}proxy.gif" alt="{$MOD.LBL_PROXY_SETTINGS}" title="{$MOD.LBL_PROXY_SETTINGS}"></a></td>
							<td class=big valign=top><a href="index.php?module=Settings&action=ProxyServerConfig&parenttab=Settings">{$MOD.LBL_PROXY_SETTINGS}</a></td>
						</tr>
						<tr>
							<td class="small" valign=top>{$MOD.LBL_PROXY_DESCRIPTION}</td>
						</tr>

						</table>
					</td>
				</tr>
				<tr>

					<td width=25% valign=top>
					<!-- empty-->
						<table border=0 cellspacing=0 cellpadding=5 width=100%>
						<tr>
							<td rowspan=2 valign=top><a href="index.php?module=Settings&action=Announcements&parenttab=Settings"><img src="{$IMAGE_PATH}announ.gif" alt="{$MOD.LBL_ANNOUNCEMENT}" width="48" height="48" border=0 title="{$MOD.LBL_ANNOUNCEMENT}"></a></td>
							<td class=big valign=top><a href="index.php?module=Settings&action=Announcements&parenttab=Settings">{$MOD.LBL_ANNOUNCEMENT}</a></td>
						</tr>
						<tr>
							<td class="small" valign=top>{$MOD.LBL_ANNOUNCEMENT_DESCRIPTION}</td>
						</tr>

						</table>
					</td>
<td width=25% valign=top>
					<!-- icon 9-->	
					<table border=0 cellspacing=0 cellpadding=5 width=100%>
					<tr>
						<td rowspan=2 valign=top><a href="index.php?module=Settings&action=DefModuleView&parenttab=Settings"><img src="{$IMAGE_PATH}set-IcoTwoTabConfig.gif" alt="{$MOD.LBL_DEFAULT_MODULE_VIEW}" width="48" height="48" border=0 title="{$MOD.LBL_DEFAULT_MODULE_VIEW}"></a></td>
						<td class=big valign=top><a href="index.php?module=Settings&action=DefModuleView&parenttab=Settings">{$MOD.LBL_DEFAULT_MODULE_VIEW}</a></td>
					</tr>
					<tr>
						<td class="small" valign=top>{$MOD.LBL_DEFAULT_MODULE_VIEW_DESC}</td>
					</tr>

					</table>
					</td>
				  <td valign=top><table border=0 cellspacing=0 cellpadding=5 width=100%>
                    <tr>
                      <td rowspan=2 valign=top><a href="index.php?module=Migration&action=index&parenttab=Settings"><img border=0 src="{$IMAGE_PATH}migrate.gif" alt="{$MOD.LBL_MIGRATION}" title="{$MOD.LBL_MIGRATION}"></a></td>
                      <td class=big valign=top><a href="index.php?module=Migration&action=index&parenttab=Settings">{$MOD.LBL_MIGRATION}</a></td>
                    </tr>
                    <tr>
                      <td class="small" valign=top>{$MOD.LBL_MIGRATION_INFO}</td>
                    </tr>
                  </table></td>
		<td width=25% valign=top>
			<!-- icon 15-->
			<table border=0 cellspacing=0 cellpadding=5 width=100%>
			<tr>

			<td rowspan=2 valign=top><a href="index.php?module=Settings&action=OrganizationTermsandConditions&parenttab=Settings"><img border=0 src="{$IMAGE_PATH}terms.gif" alt="{$MOD.INVENTORYTERMSANDCONDITIONS}" title="{$MOD.INVENTORYTERMSANDCONDITIONS}"></a></td>
			<td class=big valign=top><a href="index.php?module=Settings&action=OrganizationTermsandConditions&parenttab=Settings">{$MOD.LBL_INVENTORY_TANDC}</a></td>
			</tr>
			<tr>
			<td class="small" valign=top>{$MOD.LBL_INV_TANDC_DESCRIPTION}</td>
			</tr>
			</table>

		</td>

		</tr>

		<!-- Added for Custom Invoice Number #start -->
		<!-- icon for Invoice Number Configuration -->
		<tr>
			<td width=25% valign=top>
			<!-- empty-->

			<table border=0 cellspacing=0 cellpadding=5 width=100%>
			<tr>
				<td rowspan=2 valign=top><a href="index.php?module=Settings&action=CustomInvoiceNo&parenttab=Settings"><img src="{$IMAGE_PATH}settingsInvNumber.gif" alt="{$MOD.LBL_CUSTOMIZE_INVOICE_NUMBER}" width="48" height="48" border=0 title="{$MOD.LBL_CUSTOMIZE_INVOICE_NUMBER}"></a></td>
				<td class=big valign=top><a href="index.php?module=Settings&action=CustomInvoiceNo&parenttab=Settings">{$MOD.LBL_CUSTOMIZE_INVOICE_NUMBER}</a></td>
			</tr>
			<tr>
				<td class="small" valign=top>{$MOD.LBL_CUSTOMIZE_INVOICE_NUMBER_DESCRIPTION}</td>
			</tr>

			</table>
			</td>
		</tr>
		<!-- Added for Custom Invoice Number #end -->

		</table>
		
		</td>
	</tr>
	</table>
	</td></tr></table>	
	</div>

	</td>
        <td valign="top"><img src="{$IMAGE_PATH}showPanelTopRight.gif"></td>
   </tr>
</tbody></table>



