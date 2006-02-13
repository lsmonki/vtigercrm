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
require_once('modules/Users/UserInfoUtil.php');

function getHiddenValues($id)
{
//	global $theme;
//	$theme_path="themes/".$theme."/";
//	$image_path=$theme_path."images/";
//	require_once ($theme_path."layout_utils.php");

	$hidden .= '<form border="0" action="index.php" method="post" name="form" id="form">';
	$hidden .= '<input type="hidden" name="module">';
	$hidden .= '<input type="hidden" name="mode">';
	$hidden .= '<input type="hidden" name="account_id" value="'.$id.'">';
	$hidden .= '<input type="hidden" name="return_module" value="Accounts">';
	$hidden .= '<input type="hidden" name="return_action" value="DetailView">';
	$hidden .= '<input type="hidden" name="return_id" value="'.$id.'">';
	$hidden .= '<input type="hidden" name="parent_id" value="'.$id.'">';
	$hidden .= '<input type="hidden" name="action">';
	return $hidden;
}
function renderRelatedPotentials($query,$id)
{
	global $mod_strings;
	global $app_strings;
	require_once('include/RelatedListView.php');

	$hidden = getHiddenValues($id);
	echo $hidden;

	$focus = new Potential();
	$button = '';

        if(isPermitted("Potentials",1,"") == 'yes')
        {
	$button .= '<input title="New Potential" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Potentials\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_POTENTIAL'].'">';
	}
	$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;

	$list = GetRelatedList('Accounts','Potentials',$focus,$query,$button,$returnset);
	echo '</form>';
}

function renderRelatedContacts($query,$id)
{
	global $mod_strings;
	global $app_strings;
	require_once('include/RelatedListView.php');

	$hidden = getHiddenValues($id);                                                                                             echo $hidden;
	
	$focus = new Contact();
	
	$button = '';
	if(isPermitted("Contacts",1,"") == 'yes')
        {
		$button .= '<input title="New Contact" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Contacts\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_CONTACT'].'">&nbsp;</td>';
	}
	$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;

	$list = GetRelatedList('Accounts','Contacts',$focus,$query,$button,$returnset);
	echo '</form>';
}

function renderRelatedTasks($query,$id)
{

	global $mod_strings;
	global $app_strings;
        require_once('include/RelatedListView.php');

        $hidden = getHiddenValues($id); 
 	$hidden .= '<input type="hidden" name="activity_mode">';
        echo $hidden;

	$focus = new Activity();
	$button = '';
        if(isPermitted("Activities",1,"") == 'yes')
        {
 
		$button .= '<input title="New Task" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Activities\';this.form.return_module.value=\'Accounts\';this.form.activity_mode.value=\'Task\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_TASK'].'">&nbsp;';
		$button .= '<input title="New Event" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Activities\';this.form.return_module.value=\'Accounts\';this.form.activity_mode.value=\'Events\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_EVENT'].'">&nbsp;</td>';
	}
	$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;

        $list = GetRelatedList('Accounts','Activities',$focus,$query,$button,$returnset);
	echo '</form>';
}

function renderRelatedHistory($query,$id)
{
	getHistory('Accounts',$query,$id);
}

function renderRelatedAttachments($query,$id)
{
        $hidden = getHiddenValues($id);
        echo $hidden;

	getAttachmentsAndNotes('Accounts',$query,$id);
	echo '</form>';
}


function renderRelatedTickets($query,$id)
{
        global $mod_strings;
        global $app_strings;
        require_once('include/RelatedListView.php');

	$hidden = getHiddenValues($id);
        echo $hidden;

	$focus = new HelpDesk();
        $button = '';

	$button .= '<td valign="bottom" align="right"><input title="New TICKET" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'HelpDesk\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_TICKET'].'">&nbsp;</td>';
	$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;

        $list = GetRelatedList('Accounts','HelpDesk',$focus,$query,$button,$returnset);
        echo '</form>';
}
function renderRelatedQuotes($query,$id)
{
	global $mod_strings;
	global $app_strings;
	require_once('modules/Quotes/Quote.php');

	$hidden = getHiddenValues($id);                                                                                             echo $hidden;
	
	$focus = new Quote();
	
	$button = '';
	if(isPermitted("Quotes",1,"") == 'yes')
        {
		$button .= '<input title="'.$app_strings['LBL_NEW_QUOTE_BUTTON_TITLE'].'" accessyKey="'.$app_strings['LBL_NEW_QUOTE_BUTTON_KEY'].'" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Quotes\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_QUOTE_BUTTON'].'">&nbsp;</td>';
	}
	$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;

	$list = GetRelatedList('Accounts','Quotes',$focus,$query,$button,$returnset);
	echo '</form>';
}
function renderRelatedInvoices($query,$id)
{
	global $mod_strings;
	global $app_strings;
	require_once('modules/Invoice/Invoice.php');

	$hidden = getHiddenValues($id);                                                                                             echo $hidden;
	
	$focus = new Invoice();
	
	$button = '';
	if(isPermitted("Invoice",1,"") == 'yes')
        {
		$button .= '<input title="'.$app_strings['LBL_NEW_INVOICE_BUTTON_TITLE'].'" accessyKey="'.$app_strings['LBL_NEW_INVOICE_BUTTON_KEY'].'" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Invoice\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_INVOICE_BUTTON'].'">&nbsp;</td>';
	}
	$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;

	$list = GetRelatedList('Accounts','Invoice',$focus,$query,$button,$returnset);
	echo '</form>';
}
function renderRelatedOrders($query,$id)
{
	require_once('modules/Orders/SalesOrder.php');
        global $mod_strings;
        global $app_strings;

        $hidden = getHiddenValues($id);
        echo $hidden;

        $focus = new SalesOrder();
 
	$button = '';
	if(isPermitted("SalesOrder",1,"") == 'yes')
        {
		$button .= '<input title="'.$app_strings['LBL_NEW_SORDER_BUTTON_TITLE'].'" accessyKey="'.$app_strings['LBL_NEW_SORDER_BUTTON_KEY'].'" class="button" onclick="this.form.action.value=\'SalesOrderEditView\';this.form.module.value=\'Orders\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_SORDER_BUTTON'].'">&nbsp;</td>';
	}

	$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;

	$list = GetRelatedList('Accounts','SalesOrder',$focus,$query,$button,$returnset);
	echo '</form>';
}
function renderRelatedProducts($query,$id)
{
	require_once('modules/Products/Product.php');
        global $mod_strings;
        global $app_strings;

        $hidden = getHiddenValues($id);
        echo $hidden;

        $focus = new Product();
 
	$button = '';

        if(isPermitted("Products",1,"") == 'yes')
        {

 
		$button .= '<input title="New Product" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Products\';this.form.return_module.value=\'Accounts\';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_PRODUCT'].'">&nbsp;';
	}
	$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;

	$list = GetRelatedList('Accounts','Products',$focus,$query,$button,$returnset);
	echo '</form>';
}


echo get_form_footer();

?>
