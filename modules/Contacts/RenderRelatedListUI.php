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

function getHiddenValues($id)
{
	global $adb;
	$sql = $adb->query('select accountid from contactdetails where contactid='.$id);
	$accountid = $adb->query_result($sql,0,'accountid');
	if($accountid == 0) $accountid='';
	$hidden .= '<form border="0" action="index.php" method="post" name="form" id="form">';
	$hidden .= '<input type="hidden" name="module">';
	$hidden .= '<input type="hidden" name="mode">';
	$hidden .= '<input type="hidden" name="contact_id" value="'.$id.'">';
	$hidden .= '<input type="hidden" name="account_id" value="'.$accountid.'">';
	$hidden .= '<input type="hidden" name="return_module" value="Contacts">';
	$hidden .= '<input type="hidden" name="return_action" value="DetailView">';
	$hidden .= '<input type="hidden" name="return_id" value="'.$id.'">';
	$hidden .= '<input type="hidden" name="parent_id" value="'.$id.'">';
	$hidden .= '<input type="hidden" name="action">';
	return $hidden;
}

function renderRelatedPotentials($query,$id)
{
	global $log;
	global $mod_strings;
        global $app_strings;
        require_once('include/RelatedListView.php');

        $hidden = getHiddenValues($id);

        $focus = new Potential();
	$button = '';

        if(isPermitted("Potentials",1,"") == 'yes')
        {

		$button .= '<input title="New Potential" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Potentials\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_POTENTIAL'].'">&nbsp;';
	}
        $returnset = '&return_module=Contacts&return_action=DetailView&return_id='.$id;

        return GetRelatedList('Contacts','Potentials',$focus,$query,$button,$returnset);
	 $log->info("Potential Related List for Contact Displayed");
}

function renderRelatedTasks($query,$id)
{
	global $log;
	global $adb;
	global $mod_strings;
	global $app_strings;
        require_once('include/RelatedListView.php');

        $hidden = getHiddenValues($id);
	$hidden .= '<input type="hidden" name="activity_mode">';

        $focus = new Activity();

	$button = '';

        if(isPermitted("Activities",1,"") == 'yes')
        {
		$button .= '<input title="New Task" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Activities\';this.form.activity_mode.value=\'Task\';this.form.return_module.value=\'Contacts\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_TASK'].'">&nbsp;';
		$button .= '<input title="New Event" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Activities\';this.form.return_module.value=\'Contacts\';this.form.activity_mode.value=\'Events\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_EVENT'].'">&nbsp;';
	}
	$returnset = '&return_module=Contacts&return_action=DetailView&return_id='.$id;

	return GetRelatedList('Contacts','Activities',$focus,$query,$button,$returnset);
	$log->info("Activity Related List for Contact Displayed");
}

function renderRelatedEmails($query,$id)
{
	global $log;
	global $mod_strings;
	global $app_strings;
	
	$hidden = getHiddenValues($id);
	$hidden .="<input type=\"hidden\" name=\"email_directing_module\">";
	$hidden .="<input type=\"hidden\" name=\"record\">";

	//Added to pass the parents list as hidden for Emails -- 09-11-2005
	$hidden .= getEmailParentsList('Contacts',$id);


	$focus = new Email();

	$button = '';

        if(isPermitted("Emails",1,"") == 'yes')
        {	
		$button .= '<input title="New Email" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Emails\';this.form.email_directing_module.value=\'contacts\';this.form.record.value='.$id.';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_EMAIL'].'">';
	}
	$returnset = '&return_module=Contacts&return_action=DetailView&return_id='.$id;

	return GetRelatedList('Contacts','Emails',$focus,$query,$button,$returnset);
	 $log->info("Email Related List for Contact Displayed");
}

function renderRelatedHistory($query,$id)
{
        return getHistory('Contacts',$query,$id);
}

function renderRelatedTickets($query,$id)
{
	global $log;
        global $mod_strings;
        global $app_strings;

        $hidden = getHiddenValues($id);

        $focus = new HelpDesk();

        $button = '';	
	$button .= '<td valign="bottom" align="right"><input title="New Ticket" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'HelpDesk\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_TICKET'].'">&nbsp;</td>';
        $returnset = '&return_module=Contacts&return_action=DetailView&return_id='.$id;

        return GetRelatedList('Contacts','HelpDesk',$focus,$query,$button,$returnset);
         $log->info("Ticket Related List for Contact Displayed");
}

function renderRelatedAttachments($query,$id)
{
	global $log;
	$hidden = getHiddenValues($id);

	return getAttachmentsAndNotes('Contacts',$query,$id);
	$log->info("Notes&Attachmenmts for Contact Displayed");
}
function renderRelatedProducts($query,$id)
{
	require_once('modules/Products/Product.php');
        global $mod_strings;
        global $app_strings;

        $hidden = getHiddenValues($id,$sid);

        $focus = new Product();
 
	$button = '';

        if(isPermitted("Products",1,"") == 'yes')
        {
 
		$button .= '<input title="'.$app_strings['LBL_NEW_PRODUCT'].'" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Products\';this.form.return_module.value=\'Contacts\';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_PRODUCT'].'">&nbsp;';
	}
	$returnset = '&return_module=Contacts&return_action=DetailView&return_id='.$id;

	return GetRelatedList('Contacts','Products',$focus,$query,$button,$returnset);
}
function renderRelatedSalesOrders($query,$id,$sid="product_id")
{
	require_once('modules/SalesOrder/SalesOrder.php');
        global $mod_strings;
        global $app_strings;

        $hidden = getHiddenValues($id,$sid);

        $focus = new SalesOrder();
 
	$button = '';

        if(isPermitted("SalesOrder",1,"") == 'yes')
        {
 
		$button .= '<input title="'.$app_strings['LBL_NEW_SORDER_BUTTON_TITLE'].'" accessyKey="O" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'SalesOrder\';this.form.return_module.value=\'Contacts\';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_SORDER_BUTTON'].'">&nbsp;';
	}
	$returnset = '&return_module=Contacts&return_action=DetailView&return_id='.$id;

	return GetRelatedList('Contacts','SalesOrder',$focus,$query,$button,$returnset);
}

function renderRelatedOrders($query,$id)
{
	require_once('modules/PurchaseOrder/PurchaseOrder.php');
        global $mod_strings;
        global $app_strings;

        $hidden = getHiddenValues($id);

        $focus = new Order();
 
	$button = '';

        if(isPermitted("PurchaseOrder",1,"") == 'yes')
        {
 
		$button .= '<input title="'.$app_strings['LBL_PORDER_BUTTON_TITLE'].'" accessyKey="O" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'PurchaseOrder\';this.form.return_module.value=\'Contacts\';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$app_strings['LBL_PORDER_BUTTON'].'">&nbsp;';
	}
	$returnset = '&return_module=Contacts&return_action=DetailView&return_id='.$id;

	return GetRelatedList('Contacts','PurchaseOrder',$focus,$query,$button,$returnset);
}
function renderRelatedQuotes($query,$id)
{
	global $mod_strings;
	global $app_strings;
	require_once('modules/Quotes/Quote.php');

	$hidden = getHiddenValues($id);                                                                                        
	
	$focus = new Quote();
	
	$button = '';
	if(isPermitted("Quotes",1,"") == 'yes')
        {
		$button .= '<input title="'.$app_strings['LBL_NEW_QUOTE_BUTTON_TITLE'].'" accessyKey="'.$app_strings['LBL_NEW_QUOTE_BUTTON_KEY'].'" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Quotes\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_QUOTE_BUTTON'].'">&nbsp;</td>';
	}
	$returnset = '&return_module=Contacts&return_action=DetailView&return_id='.$id;

	return GetRelatedList('Contacts','Quotes',$focus,$query,$button,$returnset);
}




?>
