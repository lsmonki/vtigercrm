<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
        {include file='SettingsMenu.tpl'}
<td width="75%" valign="top">
<table width="99%" cellpadding="2" cellspacing="5" border="0">

<tr>
<td class="detailedViewHeader" align="left" colspan=2><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS} </a> > {$MOD.LBL_STUDIO} > {$MOD.LBL_CUSTOM_FIELD_SETTINGS}</b></td></tr>
<tr>
<td>
<a href="index.php?module=Settings&action=CustomFieldList&fld_module=Leads">{$MOD.LEADCUSTOMFIELDS}</a></td><td>
<a href="index.php?module=Settings&action=CustomFieldList&fld_module=Accounts">{$MOD.ACCOUNTCUSTOMFIELDS}</a></td></tr><tr><td>
<a href="index.php?module=Settings&action=CustomFieldList&fld_module=Contacts">{$MOD.CONTACTCUSTOMFIELDS}</a></td><td>
<a href="index.php?module=Settings&action=CustomFieldList&fld_module=Potentials">{$MOD.OPPORTUNITYCUSTOMFIELDS}</a></td></tr><tr><td>
<a href="index.php?module=Settings&action=CustomFieldList&fld_module=HelpDesk">{$MOD.HELPDESKCUSTOMFIELDS}</a></td><td>
<a href="index.php?module=Settings&action=CustomFieldList&fld_module=Products">{$MOD.PRODUCTCUSTOMFIELDS}</a></td></tr><tr><td>
<a href="index.php?module=Settings&action=CustomFieldList&fld_module=Vendor">{$MOD.VENDORCUSTOMFIELDS}</a></td><td>
<a href="index.php?module=Settings&action=CustomFieldList&fld_module=PriceBook">{$MOD.PRICEBOOKCUSTOMFIELDS}</a></td></tr><tr><td>
<a href="index.php?module=Settings&action=CustomFieldList&fld_module=Orders">{$MOD.POCUSTOMFIELDS}</a></td><td>
<a href="index.php?module=Settings&action=CustomFieldList&fld_module=SalesOrder">{$MOD.SOCUSTOMFIELDS}</a></td></tr><tr><td>
<a href="index.php?module=Settings&action=CustomFieldList&fld_module=Quotes">{$MOD.QUOTESCUSTOMFIELDS}</a></td><td>
<a href="index.php?module=Settings&action=CustomFieldList&fld_module=Invoice">{$MOD.INVOICECUSTOMFIELDS}</a></td></tr>
</table>
</td>
</tr>
</table>
        {include file='SettingsSubMenu.tpl'}

