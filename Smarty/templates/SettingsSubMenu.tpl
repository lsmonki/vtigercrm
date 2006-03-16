<style>
.subMnu
{ldelim}
position:absolute;
width:155px;
	  background-color:#FFFFFF;
	filter: progid:DXImageTransform.Microsoft.BasicImage(opacity=.7);*/
	  border-top:1px solid #CCCCCC;
	  border-left:1px solid #CCCCCC;
	  border-right:2px solid #CCCCCC;
	  border-bottom:2px solid #CCCCCC;
left:200px;top:200px;
	 z-index:100001;
visibility:hidden;
{rdelim}
a.mnuSub
{ldelim}
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:10px;
	text-decoration:none;
	text-align:left;
color:#000000;
display:block;
width:140px;
height:15px;
padding:2px;
{rdelim}

a.mnuSub:Hover
{ldelim}
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:10px;
	text-decoration:none;
	text-align:left;
	background-color:#E9DCE9;
opacity:.7;
color:#000000;
display:block;
width:142px;
height:15px;
padding:2px;
{rdelim}
</style>
<div id="subMnuFldAccess" class="subMnu" onmouseover="fnvshNrm('subMnuFldAccess')" onmouseout="fninvsh('subMnuFldAccess')">
<table width="99%" cellpadding="5" cellspacing="0" border="0">
<tr><td>
<a href="index.php?module=Users&action=DefaultFieldPermissions&fld_module=Leads" class="mnuSub">{$MOD.LBL_LEAD_FIELD_ACCESS}</a>
<a href="index.php?module=Users&action=DefaultFieldPermissions&fld_module=Accounts" class="mnuSub">{$MOD.LBL_ACCOUNT_FIELD_ACCESS}</a>
<a href="index.php?module=Users&action=DefaultFieldPermissions&fld_module=Contacts" class="mnuSub">{$MOD.LBL_CONTACT_FIELD_ACCESS}</a>
<a href="index.php?module=Users&action=DefaultFieldPermissions&fld_module=Potentials" class="mnuSub">{$MOD.LBL_OPPORTUNITY_FIELD_ACCESS}</a>
<a href="index.php?module=Users&action=DefaultFieldPermissions&fld_module=HelpDesk" class="mnuSub">{$MOD.LBL_HELPDESK_FIELD_ACCESS}</a>
<a href="index.php?module=Users&action=DefaultFieldPermissions&fld_module=Products" class="mnuSub">{$MOD.LBL_PRODUCT_FIELD_ACCESS}</a>
<a href="index.php?module=Users&action=DefaultFieldPermissions&fld_module=Notes" class="mnuSub">{$MOD.LBL_NOTE_FIELD_ACCESS}</a>
<a href="index.php?module=Users&action=DefaultFieldPermissions&fld_module=Emails" class="mnuSub">{$MOD.LBL_EMAIL_FIELD_ACCESS}</a>
<a href="index.php?module=Users&action=DefaultFieldPermissions&fld_module=Activities" class="mnuSub">{$MOD.LBL_TASK_FIELD_ACCESS}</a>
<a href="index.php?module=Users&action=DefaultFieldPermissions&fld_module=Events" class="mnuSub">{$MOD.LBL_EVENT_FIELD_ACCESS}</a>
<a href="index.php?module=Users&action=DefaultFieldPermissions&fld_module=Vendor" class="mnuSub">{$MOD.LBL_VENDOR_FIELD_ACCESS}</a>
<a href="index.php?module=Users&action=DefaultFieldPermissions&fld_module=PriceBook" class="mnuSub">{$MOD.LBL_PB_FIELD_ACCESS}</a>
<a href="index.php?module=Users&action=DefaultFieldPermissions&fld_module=Quotes" class="mnuSub">{$MOD.LBL_QUOTE_FIELD_ACCESS}</a>
<a href="index.php?module=Users&action=DefaultFieldPermissions&fld_module=Orders" class="mnuSub">{$MOD.LBL_PO_FIELD_ACCESS}</a>
<a href="index.php?module=Users&action=DefaultFieldPermissions&fld_module=SalesOrder" class="mnuSub">{$MOD.LBL_SO_FIELD_ACCESS}</a>
<a href="index.php?module=Users&action=DefaultFieldPermissions&fld_module=Invoice" class="mnuSub">{$MOD.LBL_INVOICE_FIELD_ACCESS}</a>
</td></tr>
</table>
</div>


<div id="subMnuCusFld" class="subMnu" onmouseover="fnvshNrm('subMnuCusFld')" onmouseout="fninvsh('subMnuCusFld')">
<table width="99%" cellpadding="5" cellspacing="0" border="0">
<tr><td>
<a href="index.php?module=Settings&action=CustomFieldList&fld_module=Leads" class="mnuSub">{$MOD.LEADCUSTOMFIELDS}</a>
<a href="index.php?module=Settings&action=CustomFieldList&fld_module=Accounts" class="mnuSub">{$MOD.ACCOUNTCUSTOMFIELDS}</a>
<a href="index.php?module=Settings&action=CustomFieldList&fld_module=Contacts" class="mnuSub">{$MOD.CONTACTCUSTOMFIELDS}</a>
<a href="index.php?module=Settings&action=CustomFieldList&fld_module=Potentials" class="mnuSub">{$MOD.OPPORTUNITYCUSTOMFIELDS}</a>
<a href="index.php?module=Settings&action=CustomFieldList&fld_module=HelpDesk" class="mnuSub">{$MOD.HELPDESKCUSTOMFIELDS}</a>
<a href="index.php?module=Settings&action=CustomFieldList&fld_module=Products" class="mnuSub">{$MOD.PRODUCTCUSTOMFIELDS}</a>
<a href="index.php?module=Settings&action=CustomFieldList&fld_module=Vendor" class="mnuSub">{$MOD.VENDORCUSTOMFIELDS}</a>
<a href="index.php?module=Settings&action=CustomFieldList&fld_module=PriceBook" class="mnuSub">{$MOD.PRICEBOOKCUSTOMFIELDS}</a>
<a href="index.php?module=Settings&action=CustomFieldList&fld_module=Orders" class="mnuSub">{$MOD.POCUSTOMFIELDS}</a>
<a href="index.php?module=Settings&action=CustomFieldList&fld_module=SalesOrder" class="mnuSub">{$MOD.SOCUSTOMFIELDS}</a>
<a href="index.php?module=Settings&action=CustomFieldList&fld_module=Quotes" class="mnuSub">{$MOD.QUOTESCUSTOMFIELDS}</a>
<a href="index.php?module=Settings&action=CustomFieldList&fld_module=Invoice" class="mnuSub">{$MOD.INVOICECUSTOMFIELDS}</a>
</td></tr>
</table>
</div>

<div id="subMnuPickList" class="subMnu" onmouseover="fnvshNrm('subMnuPickList')" onmouseout="fninvsh('subMnuPickList')">
<table width="99%" cellpadding="5" cellspacing="0" border="0">
<tr><td>
<a href="index.php?module=Settings&action=ComboFieldList&fld_module=Leads" class="mnuSub">{$MOD.EDITLEADPICKLISTVALUES}</a>
<a href="index.php?module=Settings&action=ComboFieldList&fld_module=Accounts" class="mnuSub">{$MOD.EDITACCOUNTPICKLISTVALUES}</a>
<a href="index.php?module=Settings&action=ComboFieldList&fld_module=Contacts" class="mnuSub">{$MOD.EDITCONTACTPICKLISTVALUES}</a>
<a href="index.php?module=Settings&action=ComboFieldList&fld_module=Potentials" class="mnuSub">{$MOD.EDITOPPORTUNITYPICKLISTVALUES}</a>
<a href="index.php?module=Settings&action=ComboFieldList&fld_module=HelpDesk" class="mnuSub">{$MOD.EDITHELPDESKPICKLISTVALUES}</a>
<a href="index.php?module=Settings&action=ComboFieldList&fld_module=Products" class="mnuSub">{$MOD.EDITPRODUCTPICKLISTVALUES}</a>
<a href="index.php?module=Settings&action=ComboFieldList&fld_module=Events" class="mnuSub">{$MOD.EDITEVENTPICKLISTVALUES}</a>
<a href="index.php?module=Settings&action=ComboFieldList&fld_module=Activities" class="mnuSub">{$MOD.EDITTASKPICKLISTVALUES}</a>
<a href="index.php?module=Settings&action=ComboFieldList&fld_module=Vendor" class="mnuSub">{$MOD.EDITVENDORPICKLISTVALUES}</a>
<a href="index.php?module=Settings&action=ComboFieldList&fld_module=PriceBook" class="mnuSub">{$MOD.EDITPBPICKLISTVALUES}</a>
<a href="index.php?module=Settings&action=ComboFieldList&fld_module=Orders" class="mnuSub">{$MOD.EDITPOPICKLISTVALUES}</a>
<a href="index.php?module=Settings&action=ComboFieldList&fld_module=SalesOrder" class="mnuSub">{$MOD.EDITSOPICKLISTVALUES}</a>
<a href="index.php?module=Settings&action=ComboFieldList&fld_module=Quotes" class="mnuSub">{$MOD.EDITQUOTEPICKLISTVALUES}</a>
<a href="index.php?module=Settings&action=ComboFieldList&fld_module=Invoice" class="mnuSub">{$MOD.EDITINVOICEPICKLISTVALUES}</a>
<a href="index.php?module=Settings&action=ComboFieldList&fld_module=Rss" class="mnuSub">{$MOD.EDITRSSPICKLISTVALUES}</a>
</td></tr>
</table>
</div>

<div id="subMnuEditFld" class="subMnu" onmouseover="fnvshNrm('subMnuEditFld')" onmouseout="fninvsh('subMnuEditFld')">
<table width="99%" cellpadding="5" cellspacing="0" border="0">
<tr><td>
<a href="index.php?module=Settings&action=EditFieldBlock&tabid=7&fld_module=Leads&parenttab=Settings" class="mnuSub">{$MOD.LBL_LEAD_FIELD_ACCESS}</a>
<a href="index.php?module=Settings&action=EditFieldBlock&tabid=6&fld_module=Accounts&parenttab=Settings" class="mnuSub">{$MOD.LBL_ACCOUNT_FIELD_ACCESS}</a>
<a href="index.php?module=Settings&action=EditFieldBlock&tabid=4&fld_module=Contacts&parenttab=Settings" class="mnuSub">{$MOD.LBL_CONTACT_FIELD_ACCESS}</a>
<a href="index.php?module=Settings&action=EditFieldBlock&tabid=2&fld_module=Potentials&parenttab=Settings" class="mnuSub">{$MOD.LBL_OPPORTUNITY_FIELD_ACCESS}</a>
<a href="index.php?module=Settings&action=EditFieldBlock&tabid=13&fld_module=HelpDesk&parenttab=Settings" class="mnuSub">{$MOD.LBL_HELPDESK_FIELD_ACCESS}</a>
<a href="index.php?module=Settings&action=EditFieldBlock&tabid=14&fld_module=Products&parenttab=Settings" class="mnuSub">{$MOD.LBL_PRODUCT_FIELD_ACCESS}</a>
<a href="index.php?module=Settings&action=EditFieldBlock&tabid=8&fld_module=Notes&parenttab=Settings" class="mnuSub">{$MOD.LBL_NOTE_FIELD_ACCESS}</a>
<a href="index.php?module=Settings&action=EditFieldBlock&tabid=10&fld_module=Emails&parenttab=Settings" class="mnuSub">{$MOD.LBL_EMAIL_FIELD_ACCESS}</a>
<a href="index.php?module=Settings&action=EditFieldBlock&tabid=9&fld_module=Activities&parenttab=Settings" class="mnuSub">{$MOD.LBL_TASK_FIELD_ACCESS}</a>
<a href="index.php?module=Settings&action=EditFieldBlock&tabid=16&fld_module=Events&parenttab=Settings" class="mnuSub">{$MOD.LBL_EVENT_FIELD_ACCESS}</a>
</td></tr>
</table>
</div>
