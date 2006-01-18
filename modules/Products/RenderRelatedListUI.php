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

require_once('include/utils/UserInfoUtil.php');
require_once('include/RelatedListView.php');
require_once('modules/Activities/Activity.php');
require_once('modules/HelpDesk/HelpDesk.php');
require_once('modules/PriceBooks/PriceBook.php');
require_once('modules/PurchaseOrder/PurchaseOrder.php');
require_once('modules/SalesOrder/SalesOrder.php');
require_once('modules/Quotes/Quote.php');
require_once('modules/Invoice/Invoice.php');


function renderRelatedTickets($query,$id)
{
	global $mod_strings;
        global $app_strings;

	echo '<input type="hidden" name="parent_id" value="">';
        $focus = new HelpDesk();

	$button = '';

        if(isPermitted("HelpDesk",1,"") == 'yes')
        {
		$button .= '<input title="New TICKET" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'HelpDesk\';this.form.return_action.value=\'DetailView\';this.form.return_module.value=\'Products\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_TICKET'].'">&nbsp;';
	}
	$returnset = '&return_module=Products&return_action=DetailView&return_id='.$id;

	return GetRelatedList('Products','HelpDesk',$focus,$query,$button,$returnset);

}

function renderRelatedActivities($query,$id,$cntid='')
{
	global $mod_strings;
        global $app_strings;

	$hidden .= '<input type="hidden" name="activity_mode">';	
        if($cntid!=0 && $cntid!='')

        $focus = new Activity();

	$button = '';

        if(isPermitted("Activities",1,"") == 'yes')
        {
		$button .= '<input title="New Task" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Activities\';this.form.activity_mode.value=\'Task\';this.form.return_module.value=\'Products\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_TASK'].'">&nbsp;';
		$button .= '<input title="New Event" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';;this.form.activity_mode.value=\'Events\';this.form.module.value=\'Activities\';this.form.return_module.value=\'Products\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_EVENT'].'">&nbsp;';
	}
	$returnset = '&return_module=Products&return_action=DetailView&return_id='.$id;

	return GetRelatedList('Products','Activities',$focus,$query,$button,$returnset);

}

function renderRelatedAttachments($query,$id,$cntid='')
{

        return getAttachmentsAndNotes('Products',$query,$id);

}

function renderProductPurchaseOrders($query,$id,$vendid='',$cntid='')
{
        global $mod_strings;
        global $app_strings;


        $focus = new Order();
 
	$button = '';

        if(isPermitted("PurchaseOrder",1,"") == 'yes')
        {
 
		$button .= '<input title="'.$app_strings['LBL_PORDER_BUTTON_TITLE'].'" accessyKey="O" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'PurchaseOrder\';this.form.return_module.value=\'Products\';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$app_strings['LBL_PORDER_BUTTON'].'">&nbsp;';
	}
	$returnset = '&return_module=Products&return_action=DetailView&return_id='.$id;

	return GetRelatedList('Products','PurchaseOrder',$focus,$query,$button,$returnset);

}

function renderProductSalesOrders($query,$id,$cntid='',$prtid='')
{
        global $mod_strings;
        global $app_strings;


        $focus = new SalesOrder();
 
	$button = '';
	if(isPermitted("SalesOrder",1,"") == 'yes')
        {
		$button .= '<input title="'.$app_strings['LBL_NEW_SORDER_BUTTON_TITLE'].'" accessyKey="'.$app_strings['LBL_NEW_SORDER_BUTTON_KEY'].'" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'SalesOrder\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_SORDER_BUTTON'].'">&nbsp;</td>';
	}
	$returnset = '&return_module=Products&return_action=DetailView&return_id='.$id;

	return GetRelatedList('Products','SalesOrder',$focus,$query,$button,$returnset);

}

function renderRelatedQuotes($query,$id,$cntid='',$prtid='',$sid="product_id")
{
	global $mod_strings;
	global $app_strings;
	
	$focus = new Quote();
	
	$button = '';
	if(isPermitted("Quotes",1,"") == 'yes')
        {
		$button .= '<input title="'.$app_strings['LBL_NEW_QUOTE_BUTTON_TITLE'].'" accessyKey="'.$app_strings['LBL_NEW_QUOTE_BUTTON_KEY'].'" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Quotes\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_QUOTE_BUTTON'].'">&nbsp;</td>';
	}
	$returnset = '&return_module=Products&return_action=DetailView&return_id='.$id;

	$list = GetRelatedList('Products','Quotes',$focus,$query,$button,$returnset);
	echo '</form>';
}

function renderRelatedInvoices($query,$id,$cntid='',$prtid='')
{
	global $mod_strings;
	global $app_strings;

	$focus = new Invoice();
	
	$button = '';
	if(isPermitted("Invoice",1,"") == 'yes')
        {
		$button .= '<input title="'.$app_strings['LBL_NEW_INVOICE_BUTTON_TITLE'].'" accessyKey="'.$app_strings['LBL_NEW_INVOICE_BUTTON_KEY'].'" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Invoice\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_INVOICE_BUTTON'].'">&nbsp;</td>';
	}
	$returnset = '&return_module=Products&return_action=DetailView&return_id='.$id;

	return GetRelatedList('Products','Invoice',$focus,$query,$button,$returnset);

}

function renderProductRelatedPriceBooks($query,$id)
{
	global $mod_strings;
	global $app_strings;

	$focus = new PriceBook();
	$button = '';
	if(isPermitted("PriceBook",3,"") == 'yes' && $focus->get_pricebook_noproduct($id))
        {
		$button .= '<input title="'.$mod_strings['LBL_ADD_PRICEBOOK_BUTTON_TITLE'].'" accessyKey="'.$mod_strings['LBL_ADD_PRICEBOOK_BUTTON_KEY'].'" class="button" onclick="this.form.action.value=\'AddProductToPriceBooks\';this.form.module.value=\'Products\'" type="submit" name="button" value="'.$mod_strings['LBL_ADD_PRICEBOOK_BUTTON_LABEL'].'">&nbsp;</td>';

	}
	$returnset = '&return_module=Products&return_action=DetailView&return_id='.$id;

	return GetRelatedList('Products','PriceBooks',$focus,$query,$button,$returnset);
}


?>
