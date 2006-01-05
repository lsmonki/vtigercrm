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

function renderRelatedTasks($query, $id)
{

        global $mod_strings;
        global $app_strings;
        require_once('include/RelatedListView.php');

        $focus = new Activity();
	$button = '';

	if(isPermitted("Activities",1,"") == 'yes')
	{
		$button .= '<input title="New Task" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Activities\';i;this.form.return_module.value=\'Leads\';this.form.activity_mode.value=\'Task\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_TASK'].'">&nbsp;';
	$button .= '<input title="New Event" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Activities\';this.form.return_module.value=\'Leads\';this.form.activity_mode.value=\'Events\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_EVENT'].'">&nbsp;</td>';
	}
        $returnset = '&return_module=Leads&return_action=DetailView&return_id='.$id;

       return  GetRelatedList('Leads','Activities',$focus,$query,$button,$returnset);
}

function renderRelatedEmails($query,$id)
{
        global $mod_strings;
        global $app_strings;
        require_once('include/RelatedListView.php');

        $focus = new Email();

	$button = '';

        if(isPermitted("Emails",1,"") == 'yes')
        {

		$button .= '<input title="New Email" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Emails\';this.form.email_directing_module.value=\'leads\';this.form.record.value='.$id.';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_EMAIL'].'">&nbsp;';
	}
        $returnset = '&return_module=Leads&return_action=DetailView&return_id='.$id;

        return GetRelatedList('Leads','Emails',$focus,$query,$button,$returnset);
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

 
		$button .= '<input title="New Product" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Products\';this.form.return_module.value=\'Leads\';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_PRODUCT'].'">&nbsp;';
	}
	$returnset = '&return_module=Leads&return_action=DetailView&return_id='.$id;

	return  GetRelatedList('Leads','Products',$focus,$query,$button,$returnset);
}

function renderRelatedHistory($query,$id)
{
	return getHistory('Leads',$query,$id);
}

function renderRelatedAttachments($query,$id)
{
	return getAttachmentsAndNotes('Leads',$query,$id);
}

?>
