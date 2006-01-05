<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/

require_once('include/RelatedListView.php');
require_once('include/utils/UserInfoUtil.php');

function renderRelatedPotentials($query,$id)
{
	global $mod_strings;
	global $app_strings;
	require_once('include/RelatedListView.php');

	$focus = new Potential();
	$button = '';

        if(isPermitted("Potentials",1,"") == 'yes')
        {
	$button .= '<input title="New Potential" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Potentials\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_POTENTIAL'].'">';
	}
	$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;

	return GetRelatedList('Accounts','Potentials',$focus,$query,$button,$returnset);
}

function renderRelatedContacts($query,$id)
{
	global $mod_strings;
	global $app_strings;
	require_once('include/RelatedListView.php');

	$focus = new Contact();
	
	$button = '';
	if(isPermitted("Contacts",1,"") == 'yes')
        {
		$button .= '<input title="New Contact" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Contacts\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_CONTACT'].'">&nbsp;</td>';
	}
	$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;

	return GetRelatedList('Accounts','Contacts',$focus,$query,$button,$returnset);
}

function renderRelatedTasks($query,$id)
{

	global $mod_strings;
	global $app_strings;
        require_once('include/RelatedListView.php');

	$focus = new Activity();
	$button = '';
        if(isPermitted("Activities",1,"") == 'yes')
        {
 
		$button .= '<input title="New Task" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Activities\';this.form.return_module.value=\'Accounts\';this.form.activity_mode.value=\'Task\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_TASK'].'">&nbsp;';
		$button .= '<input title="New Event" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Activities\';this.form.return_module.value=\'Accounts\';this.form.activity_mode.value=\'Events\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_EVENT'].'">&nbsp;</td>';
	}
	$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;

        return GetRelatedList('Accounts','Activities',$focus,$query,$button,$returnset);
}

function renderRelatedHistory($query,$id)
{
	return getHistory('Accounts',$query,$id);
}

function renderRelatedAttachments($query,$id)
{

	return getAttachmentsAndNotes('Accounts',$query,$id);
}


function renderRelatedTickets($query,$id)
{
        global $mod_strings;
        global $app_strings;
        require_once('include/RelatedListView.php');

	$focus = new HelpDesk();
        $button = '';

	$button .= '<td valign="bottom" align="right"><input title="New TICKET" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'HelpDesk\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_TICKET'].'">&nbsp;</td>';
	$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;

        return GetRelatedList('Accounts','HelpDesk',$focus,$query,$button,$returnset);

}
function renderRelatedQuotes($query,$id)
{
	global $mod_strings;
	global $app_strings;
	require_once('modules/Quotes/Quote.php');

	$focus = new Quote();
	
	$button = '';
	if(isPermitted("Quotes",1,"") == 'yes')
        {
		$button .= '<input title="'.$app_strings['LBL_NEW_QUOTE_BUTTON_TITLE'].'" accessyKey="'.$app_strings['LBL_NEW_QUOTE_BUTTON_KEY'].'" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Quotes\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_QUOTE_BUTTON'].'">&nbsp;</td>';
	}
	$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;

	return GetRelatedList('Accounts','Quotes',$focus,$query,$button,$returnset);

}
function renderRelatedInvoices($query,$id)
{
	global $mod_strings;
	global $app_strings;
	require_once('modules/Invoice/Invoice.php');

	$focus = new Invoice();
	
	$button = '';
	if(isPermitted("Invoice",1,"") == 'yes')
        {
		$button .= '<input title="'.$app_strings['LBL_NEW_INVOICE_BUTTON_TITLE'].'" accessyKey="'.$app_strings['LBL_NEW_INVOICE_BUTTON_KEY'].'" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Invoice\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_INVOICE_BUTTON'].'">&nbsp;</td>';
	}
	$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;

	return GetRelatedList('Accounts','Invoice',$focus,$query,$button,$returnset);

}
function renderRelatedOrders($query,$id)
{
	require_once('modules/SalesOrder/SalesOrder.php');
        global $mod_strings;
        global $app_strings;

        $focus = new SalesOrder();
 
	$button = '';
	if(isPermitted("SalesOrder",1,"") == 'yes')
        {
		$button .= '<input title="'.$app_strings['LBL_NEW_SORDER_BUTTON_TITLE'].'" accessyKey="'.$app_strings['LBL_NEW_SORDER_BUTTON_KEY'].'" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'SalesOrder\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_SORDER_BUTTON'].'">&nbsp;</td>';
	}

	$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;

	return GetRelatedList('Accounts','SalesOrder',$focus,$query,$button,$returnset);
}
function renderRelatedProducts($query,$id)
{
	require_once('modules/Products/Product.php');
        global $mod_strings;
        global $app_strings;

        $focus = new Product();
 
	$button = '';

        if(isPermitted("Products",1,"") == 'yes')
        {

 
		$button .= '<input title="New Product" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Products\';this.form.return_module.value=\'Accounts\';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_PRODUCT'].'">&nbsp;';
	}
	$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;

	return GetRelatedList('Accounts','Products',$focus,$query,$button,$returnset);
	echo '</form>';
}

?>
