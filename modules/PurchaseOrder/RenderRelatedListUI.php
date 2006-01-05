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

/** Function to get the list of activities related to a purchaseorder
 * This function accepts the query,id and purchaseorderid as arguments and
 * makes a call to GetRelatedList() method to get the activities related to the purchaseorderid
 * and echo the result in html form.
*/
function renderRelatedActivities($query,$id,$sid='purchaseorderid')
{
	global $mod_strings;
        global $app_strings;

        $focus = new Activity();

	$button = '';

        if(isPermitted("Activities",1,"") == 'yes')
        {
		$button .= '<input title="'.$app_strings['LBL_NEW_TASK'].'" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Activities\';this.form.activity_mode.value=\'Task\';this.form.return_module.value=\'PurchaseOrder\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_TASK'].'">&nbsp;';
	}
	$returnset = '&return_module=PurchaseOrder&return_action=DetailView&return_id='.$id;

	return GetRelatedList('PurchaseOrder','Activities',$focus,$query,$button,$returnset);

}


/** Function to get the list of attachements and notes associated with a purchaseorder
 *  This function accepts the query,id and purchaseorderid as arguments and
 *  makes a call to getAttachmentsAndNotes() method to get the attachments and notes
 *  related to the purchaseorderid and echo the result in html form.
*/
function renderRelatedAttachments($query,$id,$sid='purchaseorderid')
{

	return getAttachmentsAndNotes('PurchaseOrder',$query,$id,$sid);

}


/** Function to get the history list related to a purchaseorder
 *  This function accepts the query and id as arguments and makes a call to 
 *  getHistory() method to get the history list related to the purchaseorder.
*/
function renderRelatedHistory($query,$id)
{
	return getHistory('PurchaseOrder',$query,$id);
}


echo get_form_footer();


?>
