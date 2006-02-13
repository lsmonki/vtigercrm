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
	global $vtlog;
	global $mod_strings;
        global $app_strings;
        require_once('include/RelatedListView.php');

        $hidden = getHiddenValues($id);
        echo $hidden;

        $focus = new Potential();
	$button = '';

        if(isPermitted("Potentials",1,"") == 'yes')
        {

		$button .= '<input title="New Potential" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Potentials\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_POTENTIAL'].'">&nbsp;';
	}
        $returnset = '&return_module=Contacts&return_action=DetailView&return_id='.$id;

        $list = GetRelatedList('Contacts','Potentials',$focus,$query,$button,$returnset);
	$vtlog->logthis("Potential Related List for Contact Displayed",'info');
	echo '</form>';
}

function renderRelatedTasks($query,$id)
{
	global $vtlog;
	global $adb;
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
		$button .= '<input title="New Task" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Activities\';this.form.activity_mode.value=\'Task\';this.form.return_module.value=\'Contacts\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_TASK'].'">&nbsp;';
		$button .= '<input title="New Event" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Activities\';this.form.return_module.value=\'Contacts\';this.form.activity_mode.value=\'Events\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_EVENT'].'">&nbsp;';
	}
	$returnset = '&return_module=Contacts&return_action=DetailView&return_id='.$id;

	$list = GetRelatedList('Contacts','Activities',$focus,$query,$button,$returnset);
	$vtlog->logthis("Activity Related List for Contact Displayed",'info');
	echo '</form>';
}

function renderRelatedEmails($query,$id)
{
	global $vtlog;
	global $mod_strings;
	global $app_strings;
	
	$hidden = getHiddenValues($id);
	$hidden .="<input type=\"hidden\" name=\"email_directing_module\">";
	$hidden .="<input type=\"hidden\" name=\"record\">";
	echo $hidden;

	$focus = new Email();

	$button = '';

        if(isPermitted("Emails",1,"") == 'yes')
        {	
		$button .= '<input title="New Email" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Emails\';this.form.email_directing_module.value=\'contacts\';this.form.record.value='.$id.';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_EMAIL'].'">';
	}
	$returnset = '&return_module=Contacts&return_action=DetailView&return_id='.$id;

	$list = GetRelatedList('Contacts','Emails',$focus,$query,$button,$returnset);
	$vtlog->logthis("Email Related List for Contact Displayed",'info');
	echo '</form>';
}

function renderRelatedHistory($query,$id)
{
	getHistory('Contacts',$query,$id);
	echo '<br><br>';
}

function renderRelatedTickets($query,$id)
{
	global $vtlog;
        global $mod_strings;
        global $app_strings;

        $hidden = getHiddenValues($id);
        echo $hidden;

        $focus = new HelpDesk();

        $button = '';	
	$button .= '<td valign="bottom" align="right"><input title="New Ticket" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'HelpDesk\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_TICKET'].'">&nbsp;</td>';
        $returnset = '&return_module=Contacts&return_action=DetailView&return_id='.$id;

        $list = GetRelatedList('Contacts','HelpDesk',$focus,$query,$button,$returnset);
	$vtlog->logthis("Ticket Related List for Contact Displayed",'info');
        echo '</form>';
}

function renderRelatedAttachments($query,$id)
{
	global $vtlog;
	$hidden = getHiddenValues($id);
        echo $hidden;

	getAttachmentsAndNotes('Contacts',$query,$id);
	$vtlog->logthis("Notes&Attachmenmts for Contact Displayed",'info');
	echo '</form>';
}
function renderRelatedProducts($query,$id)
{
	require_once('modules/Products/Product.php');
        global $mod_strings;
        global $app_strings;

        $hidden = getHiddenValues($id,$sid);
        echo $hidden;

        $focus = new Product();
 
	$button = '';

        if(isPermitted("Products",1,"") == 'yes')
        {
 
		$button .= '<input title="'.$app_strings['LBL_NEW_PRODUCT'].'" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Products\';this.form.return_module.value=\'Contacts\';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_PRODUCT'].'">&nbsp;';
	}
	$returnset = '&return_module=Contacts&return_action=DetailView&return_id='.$id;

	$list = GetRelatedList('Contacts','Products',$focus,$query,$button,$returnset);
	echo '</form>';
}
function renderRelatedSalesOrders($query,$id,$sid="product_id")
{
	require_once('modules/Orders/SalesOrder.php');
        global $mod_strings;
        global $app_strings;

        $hidden = getHiddenValues($id,$sid);
        echo $hidden;

        $focus = new SalesOrder();
 
	$button = '';

        if(isPermitted("SalesOrder",1,"") == 'yes')
        {
 
		$button .= '<input title="'.$app_strings['LBL_NEW_SORDER_BUTTON_TITLE'].'" accessyKey="O" class="button" onclick="this.form.action.value=\'SalesOrderEditView\';this.form.module.value=\'Orders\';this.form.return_module.value=\'Contacts\';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_SORDER_BUTTON'].'">&nbsp;';
	}
	$returnset = '&return_module=Contacts&return_action=DetailView&return_id='.$id;

	$list = GetRelatedList('Contacts','SalesOrder',$focus,$query,$button,$returnset);
	echo '</form>';
}

function renderRelatedOrders($query,$id)
{
	require_once('modules/Orders/Order.php');
        global $mod_strings;
        global $app_strings;

        $hidden = getHiddenValues($id);
        echo $hidden;

        $focus = new Order();
 
	$button = '';

        if(isPermitted("Orders",1,"") == 'yes')
        {
 
		$button .= '<input title="'.$app_strings['LBL_PORDER_BUTTON_TITLE'].'" accessyKey="O" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Orders\';this.form.return_module.value=\'Contacts\';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$app_strings['LBL_PORDER_BUTTON'].'">&nbsp;';
	}
	$returnset = '&return_module=Contacts&return_action=DetailView&return_id='.$id;

	$list = GetRelatedList('Contacts','Orders',$focus,$query,$button,$returnset);
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
	$returnset = '&return_module=Contacts&return_action=DetailView&return_id='.$id;

	$list = GetRelatedList('Contacts','Quotes',$focus,$query,$button,$returnset);
	echo '</form>';
}


echo get_form_footer();


?>
