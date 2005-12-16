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
require_once('modules/HelpDesk/HelpDesk.php');
require_once('modules/Activities/Activity.php');
require_once('include/utils/UserInfoUtil.php');

/** Function to get the lists of hidden tags related with salesorder
 * This function accepts the id and salesorderid as arguments and
 * returns the hidden html tags related with salesorderid as a string
*/

function getHiddenValues($id,$sid='salesorderid')
{
        $hidden .= '<form border="0" action="index.php" method="post" name="form" id="form">';
        $hidden .= '<input type="hidden" name="module">';
        $hidden .= '<input type="hidden" name="mode">';
        $hidden .= '<input type="hidden" name="'.$sid.'" value="'.$id.'">';
        $hidden .= '<input type="hidden" name="return_module" value="SalesOrder">';
       	$hidden .= '<input type="hidden" name="return_action" value="DetailView">';
        $hidden .= '<input type="hidden" name="return_id" value="'.$id.'">';
        $hidden .= '<input type="hidden" name="parent_id" value="'.$id.'">';
        $hidden .= '<input type="hidden" name="action">';	
	return $hidden;
}

/** Function to get the list of activities related to a salesorder
 *  This function accepts the query,id and salesorderid as arguments and
 *  makes a call to GetRelatedList() method to get the activities related to the salesorderid
 *  and echo the result in html form.
*/

function renderSalesRelatedActivities($query,$id)
{
	global $mod_strings;
        global $app_strings;

        $hidden = getHiddenValues($id,"salesorderid");
	$hidden .= '<input type="hidden" name="activity_mode">';	
        echo $hidden;

        $focus = new Activity();

	$button = '';

        if(isPermitted("Activities",1,"") == 'yes')
        {
		$button .= '<input title="'.$app_strings['LBL_NEW_TASK'].'" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Activities\';this.form.activity_mode.value=\'Task\';this.form.return_module.value=\'SalesOrder\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_TASK'].'">&nbsp;';
	}
	$returnset = '&return_module=SalesOrder&return_action=DetailView&return_id='.$id;

	$list = GetRelatedList('SalesOrder','Activities',$focus,$query,$button,$returnset);
	echo '</form>';
}

function renderRelatedOrders($query,$id)
{
	require_once('modules/SalesOrder/SalesOrder.php');
        global $mod_strings;
        global $app_strings;

        $hidden = getHiddenValues($id);
        echo $hidden;

        $focus = new SalesOrder();
 
	$button = '';

	$returnset = '&return_module=SalesOrder&return_action=DetailView&return_id='.$id;

	$list = GetRelatedList('SalesOrder','SalesOrder',$focus,$query,$button,$returnset);
	echo '</form>';
}

/** Function to get the list of attachements and notes associated with a salesorder
 *  This function accepts the query,id and salesorderid as arguments and
 *  makes a call to getAttachmentsAndNotes() method to get the attachments and notes
 *  related to the salesorderid and echo the result in html form.
*/
function renderRelatedAttachments($query,$id,$sid='salesorderid')
{
        $hidden = getHiddenValues($id,$sid);
        echo $hidden;

	getAttachmentsAndNotes('SalesOrder',$query,$id,$sid);
	echo '</form>';
}

/** Function to get the list of invoices associated with a salesorder
 *  This function accepts the query and id as arguments and
 *  makes a call to GetRelatedList() method to get the invoices
 *  related to the salesorder and echo the result in html form.
*/
function renderRelatedInvoices($query,$id)
{
	global $mod_strings;
	global $app_strings;
	require_once('modules/Invoice/Invoice.php');

	$hidden = getHiddenValues($id);                                                                                             echo $hidden;
	
	$focus = new Invoice();
	
	$button = '';
	$returnset = '&return_module=SalesOrder&return_action=DetailView&return_id='.$id;

	$list = GetRelatedList('SalesOrder','Invoice',$focus,$query,$button,$returnset);
	echo '</form>';
}

/** Function to get the history list related to a salesorder
 *  This function accepts the query and id as arguments and makes a call to
 *  getHistory() method to get the history list related to the salesorder.
*/
function renderRelatedHistory($query,$id)
{
	getHistory('SalesOrder',$query,$id);
}


echo get_form_footer();


?>
