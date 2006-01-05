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
require_once('modules/Activities/Activity.php');
require_once('include/utils/UserInfoUtil.php');


function renderRelatedActivities($query,$id)
{
	global $mod_strings;
        global $app_strings;


        $focus = new Activity();

	$button = '';

        if(isPermitted("Activities",1,"") == 'yes')
        {
		$button .= '<input title="'.$app_strings['LBL_NEW_TASK'].'" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Activities\';this.form.activity_mode.value=\'Task\';this.form.return_module.value=\'Invoice\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_TASK'].'">&nbsp;';
	}
	$returnset = '&return_module=Invoice&return_action=DetailView&return_id='.$id;

	return  GetRelatedList('Invoice','Activities',$focus,$query,$button,$returnset);

}

function renderRelatedAttachments($query,$id)
{

	return getAttachmentsAndNotes('Invoice',$query,$id);

}

function renderRelatedHistory($query,$id)
{
	return getHistory('Invoice',$query,$id);
}


echo get_form_footer();


?>
