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
<td class="detailedViewHeader" align="left" colspan=2><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS} </a> > {$MOD.LBL_STUDIO} > {$MOD.LBL_PICKLIST_SETTINGS} </b></td></tr>
<tr><td>
<a href="index.php?module=Settings&action=ComboFieldList&fld_module=Leads">{$MOD.EDITLEADPICKLISTVALUES}</a></td><td>
<a href="index.php?module=Settings&action=ComboFieldList&fld_module=Accounts">{$MOD.EDITACCOUNTPICKLISTVALUES}</a></td></tr><tr><td>
<a href="index.php?module=Settings&action=ComboFieldList&fld_module=Contacts">{$MOD.EDITCONTACTPICKLISTVALUES}</a></td><td>
<a href="index.php?module=Settings&action=ComboFieldList&fld_module=Potentials">{$MOD.EDITOPPORTUNITYPICKLISTVALUES}</a></td></tr><tr><td>
<a href="index.php?module=Settings&action=ComboFieldList&fld_module=HelpDesk">{$MOD.EDITHELPDESKPICKLISTVALUES}</a></td><td>
<a href="index.php?module=Settings&action=ComboFieldList&fld_module=Products">{$MOD.EDITPRODUCTPICKLISTVALUES}</a></td></tr><tr><td>
<a href="index.php?module=Settings&action=ComboFieldList&fld_module=Events">{$MOD.EDITEVENTPICKLISTVALUES}</a></td><td>
<a href="index.php?module=Settings&action=ComboFieldList&fld_module=Calendar">{$MOD.EDITTASKPICKLISTVALUES}</a></td></tr><tr><td>
<a href="index.php?module=Settings&action=ComboFieldList&fld_module=Vendor">{$MOD.EDITVENDORPICKLISTVALUES}</a></td><td>
<a href="index.php?module=Settings&action=ComboFieldList&fld_module=PriceBook">{$MOD.EDITPBPICKLISTVALUES}</a></td></tr><tr><td>
<a href="index.php?module=Settings&action=ComboFieldList&fld_module=Orders">{$MOD.EDITPOPICKLISTVALUES}</a></td><td>
<a href="index.php?module=Settings&action=ComboFieldList&fld_module=SalesOrder">{$MOD.EDITSOPICKLISTVALUES}</a></td></tr><tr><td>
<a href="index.php?module=Settings&action=ComboFieldList&fld_module=Quotes">{$MOD.EDITQUOTEPICKLISTVALUES}</a></td><td>
<a href="index.php?module=Settings&action=ComboFieldList&fld_module=Invoice">{$MOD.EDITINVOICEPICKLISTVALUES}</a></td></tr><tr><td>
<a href="index.php?module=Settings&action=ComboFieldList&fld_module=Rss">{$MOD.EDITRSSPICKLISTVALUES}</a>
</td><td>&nbsp;</td>
</tr>
</table>
</td>
</tr>
</table>
        {include file='SettingsSubMenu.tpl'}

