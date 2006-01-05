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

/**     Function to display the Activities which are related to the Ticket
 *      @param  string $query - query to get the Activities which are related to the Ticket
 *	@param  int $id - Ticket id
 * 	return void. Design the New Event and New Task Buttons adn call the function GetRelatedList which is in include/RelatedListView.php
 */
function renderRelatedActivities($query,$id)
{
	global $mod_strings;
	global $app_strings;

        $focus = new Activity();

	$button = '';

        if(isPermitted("Activities",1,"") == 'yes')
        {
		$button .= '<input title="New Task" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Activities\';this.form.return_module.value=\'HelpDesk\';this.form.activity_mode.value=\'Task\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_TASK'].'">&nbsp;';
		$button .= '<input title="New Event" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Activities\';this.form.return_module.value=\'HelpDesk\';this.form.activity_mode.value=\'Events\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_EVENT'].'">&nbsp;';
	}
	$returnset = '&return_module=HelpDesk&return_action=DetailView&return_id='.$id;

	return GetRelatedList('HelpDesk','Activities',$focus,$query,$button,$returnset);

}

/**     Function to display the Attachments and Notes which are related to the Ticket
 *      @param  string $query - query to get the attachments and notes which are related to the Ticket
 *	@param  int $id - Ticket id
 *	return void. Call the function GetAttachmentsAndNotes which is in include/RelatedListView.php
 */
function renderRelatedAttachments($query,$id)
{

        return getAttachmentsAndNotes('HelpDesk',$query,$id);


}

/**     Function to display the History of the Ticket which just includes a file which contains the TicketHistory informations
 */
function Get_Ticket_History()
{
        global $mod_strings;
        echo '<br><br>';
        echo get_form_header($mod_strings['LBL_TICKET_HISTORY'],"", false);
        include("modules/HelpDesk/TicketHistory.php");
}

echo get_form_footer();

?>
