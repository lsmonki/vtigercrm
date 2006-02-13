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
	$list .= '<form border="0" action="index.php" method="post" name="form" id="form">';
	$list .= '<input type="hidden" name="module">';
	$list .= '<input type="hidden" name="mode">';
	$list .= '<input type="hidden" name="parent_id" value="'.$id.'">';
	$list .= '<input type="hidden" name="return_module" value="Leads">';
	$list .= '<input type="hidden" name="return_action" value="DetailView">';
	$list .= '<input type="hidden" name="return_id" value="'.$id.'">';
	$list .= '<input type="hidden" name="action">';
	return $list;
}

function renderRelatedTasks($query, $id)
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
		$button .= '<input title="New Task" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Activities\';i;this.form.return_module.value=\'Leads\';this.form.activity_mode.value=\'Task\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_TASK'].'">&nbsp;';
	$button .= '<input title="New Event" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Activities\';this.form.return_module.value=\'Leads\';this.form.activity_mode.value=\'Events\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_EVENT'].'">&nbsp;</td>';
	}
        $returnset = '&return_module=Leads&return_action=DetailView&return_id='.$id;

        $list = GetRelatedList('Leads','Activities',$focus,$query,$button,$returnset);
	echo '</form>';
}

function renderRelatedEmails($query,$id)
{
        global $mod_strings;
        global $app_strings;
        require_once('include/RelatedListView.php');

        $hidden = getHiddenValues($id);
        $hidden .= '<input type="hidden" name="activity_mode">';
	$hidden .="<input type=\"hidden\" name=\"email_directing_module\">";
	$hidden .="<input type=\"hidden\" name=\"record\">";

        echo $hidden;

        $focus = new Email();

	$button = '';

        if(isPermitted("Emails",1,"") == 'yes')
        {

		$button .= '<input title="New Email" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Emails\';this.form.email_directing_module.value=\'leads\';this.form.record.value='.$id.';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_EMAIL'].'">&nbsp;';
	}
        $returnset = '&return_module=Leads&return_action=DetailView&return_id='.$id;

        $list = GetRelatedList('Leads','Emails',$focus,$query,$button,$returnset);
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

 
		$button .= '<input title="New Product" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Products\';this.form.return_module.value=\'Leads\';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_PRODUCT'].'">&nbsp;';
	}
	$returnset = '&return_module=Leads&return_action=DetailView&return_id='.$id;

	$list = GetRelatedList('Leads','Products',$focus,$query,$button,$returnset);
	echo '</form>';
}

function renderRelatedHistory($query,$id)
{
	getHistory('Leads',$query,$id);
}

function renderRelatedAttachments($query,$id)
{
	$hidden = getHiddenValues($id);
	echo $hidden;
	getAttachmentsAndNotes('Leads',$query,$id);
	echo '</form>';
}

echo get_form_footer();

?>
