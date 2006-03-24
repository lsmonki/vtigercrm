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

<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
        {include file='SettingsMenu.tpl'}
<td width="75%" valign="top">
<table width="99%" cellpadding="2" cellspacing="5" border="0">

<tr>
<td class="detailedViewHeader" align="left" colspan=2><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS} </a> > {$MOD.LBL_STUDIO} > {$MOD.LBL_CUSTOM_FIELD_SETTINGS}</b></td></tr>
<tr>
<td>
<a href="index.php?module=Settings&action=CustomFieldList&fld_module=Leads&parenttab=Settings">{$MOD.LEADCUSTOMFIELDS}</a></td><td>
<a href="index.php?module=Settings&action=CustomFieldList&fld_module=Accounts&parenttab=Settings">{$MOD.ACCOUNTCUSTOMFIELDS}</a></td></tr><tr><td>
<a href="index.php?module=Settings&action=CustomFieldList&fld_module=Contacts&parenttab=Settings">{$MOD.CONTACTCUSTOMFIELDS}</a></td><td>
<a href="index.php?module=Settings&action=CustomFieldList&fld_module=Potentials&parenttab=Settings">{$MOD.OPPORTUNITYCUSTOMFIELDS}</a></td></tr><tr><td>
<a href="index.php?module=Settings&action=CustomFieldList&fld_module=HelpDesk&parenttab=Settings">{$MOD.HELPDESKCUSTOMFIELDS}</a></td><td>
<a href="index.php?module=Settings&action=CustomFieldList&fld_module=Products&parenttab=Settings">{$MOD.PRODUCTCUSTOMFIELDS}</a></td></tr><tr><td>
<a href="index.php?module=Settings&action=CustomFieldList&fld_module=Vendor&parenttab=Settings">{$MOD.VENDORCUSTOMFIELDS}</a></td><td>
<a href="index.php?module=Settings&action=CustomFieldList&fld_module=PriceBook&parenttab=Settings">{$MOD.PRICEBOOKCUSTOMFIELDS}</a></td></tr><tr><td>
<a href="index.php?module=Settings&action=CustomFieldList&fld_module=Orders&parenttab=Settings">{$MOD.POCUSTOMFIELDS}</a></td><td>
<a href="index.php?module=Settings&action=CustomFieldList&fld_module=SalesOrder&parenttab=Settings">{$MOD.SOCUSTOMFIELDS}</a></td></tr><tr><td>
<a href="index.php?module=Settings&action=CustomFieldList&fld_module=Quotes&parenttab=Settings">{$MOD.QUOTESCUSTOMFIELDS}</a></td><td>
<a href="index.php?module=Settings&action=CustomFieldList&fld_module=Invoice&parenttab=Settings">{$MOD.INVOICECUSTOMFIELDS}</a></td></tr>
</table>
</td>
</tr>
</table>
        {include file='SettingsSubMenu.tpl'}

