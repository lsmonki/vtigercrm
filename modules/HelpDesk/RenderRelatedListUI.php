<?php
require_once('include/RelatedListView.php');
require_once('modules/Activities/Activity.php');
require_once('modules/Users/UserInfoUtil.php');

function getHiddenValues($id)
{
        $hidden .= '<form border="0" action="index.php" method="post" name="form" id="form">';
        $hidden .= '<input type="hidden" name="module">';
        $hidden .= '<input type="hidden" name="return_module" value="HelpDesk">';
        $hidden .= '<input type="hidden" name="return_action" value="DetailView">';
        $hidden .= '<input type="hidden" name="return_id" value="'.$id.'">';
        $hidden .= '<input type="hidden" name="parent_id" value="'.$id.'">';
        $hidden .= '<input type="hidden" name="ticket_id" value="'.$id.'">';
        $hidden .= '<input type="hidden" name="action">';
        return $hidden;
}

function renderRelaredActivities($query,$id)
{
	global $mod_strings;
	global $app_strings;

	$hidden = getHiddenValues($id);
        $hidden .= '<input type="hidden" name="activity_mode">';
        echo $hidden;

        $focus = new Activity();

	$button = '';

        if(isPermitted("Activities",1,"") == 'yes')
        {
		$button .= '<input title="New Task" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Activities\';this.form.return_module.value=\'HelpDesk\';this.form.activity_mode.value=\'Task\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_TASK'].'">&nbsp;';
		$button .= '<input title="New Event" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Activities\';this.form.return_module.value=\'HelpDesk\';this.form.activity_mode.value=\'Events\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_EVENT'].'">&nbsp;';
	}
	$returnset = '&return_module=HelpDesk&return_action=DetailView&return_id='.$id;

	$list = GetRelatedList('HelpDesk','Activities',$focus,$query,$button,$returnset);
        echo '</form>';
}

function renderRelatedAttachments($query,$id)
{
       $hidden = getHiddenValues($id);
        echo $hidden;

        getAttachmentsAndNotes('HelpDesk',$query,$id);

        echo '</form>';
}

function Get_Ticket_History()
{
        global $mod_strings;
        echo '<br><br>';
        echo get_form_header($mod_strings['LBL_TICKET_HISTORY'],"", false);
        include("modules/HelpDesk/TicketHistory.php");
}

echo get_form_footer();

?>
