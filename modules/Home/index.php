<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the 
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Home/index.php,v 1.21 2005/03/04 14:31:01 jack Exp $
 * Description:  Main file for the Home module.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');
require_once('include/database/PearDatabase.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;

$_REQUEST['search_form'] = 'false';
$_REQUEST['query'] = 'true';
$_REQUEST['status'] = 'In Progress--Not Started';
$_REQUEST['current_user_only'] = 'On';

$task_title = $mod_strings['LBL_OPEN_TASKS'];

?>
<table width=100% cellpadding="5" cellspacing="5" border="0">
<tr>
<td valign="top"><?php include("modules/Potentials/ListViewTop.php"); ?>
<br>
<?php include("modules/Activities/OpenListView.php") ;?>
<br>
<?php
//get all the group relation tasks
$query = "select leaddetails.leadid as id,leaddetails.lastname as name,leadgrouprelation.groupname as groupname, 'Leads' as Type from leaddetails inner join leadgrouprelation on leaddetails.leadid=leadgrouprelation.leadid inner join crmentity on crmentity.crmid = leaddetails.leadid where  crmentity.deleted=0  and leadgrouprelation.groupname is not null and leadgrouprelation.groupname != '' union all select activity.activityid,activity.subject,activitygrouprelation.groupname,'Activities' as Type from activity inner join activitygrouprelation on activitygrouprelation.activityid=activity.activityid inner join crmentity on crmentity.crmid = activity.activityid where  crmentity.deleted=0 and activitygrouprelation.groupname is not null and groupname != '' union all select troubletickets.ticketid,troubletickets.title,troubletickets.groupname,'Tickets' as Type from troubletickets inner join seticketsrel on seticketsrel.ticketid = troubletickets.ticketid inner join crmentity on crmentity.crmid = seticketsrel.ticketid where troubletickets.groupname is not null and crmentity.deleted=0 and groupname != ''";


//$query = "select leaddetails.lastname,leadgrouprelation.groupname, 'Leads' as Type from leaddetails inner join leadgrouprelation on leaddetails.leadid=leadgrouprelation.leadid inner join crmentity on crmentity.crmid = leaddetails.leadid where  crmentity.deleted=0 union all select activity.subject,activitygrouprelation.groupname,'Activities' as Type from activity inner join activitygrouprelation on activitygrouprelation.activityid=activity.activityid inner join crmentity on crmentity.crmid = activity.activityid where  crmentity.deleted=0 union all select troubletickets.ticketid,troubletickets.groupname,'Tickets' as Type from troubletickets inner join seticketsrel on seticketsrel.ticketid = troubletickets.ticketid inner join crmentity on crmentity.crmid = seticketsrel.ticketid where troubletickets.groupname is not null and crmentity.deleted=0";

  $log->info("Here is the where clause for the list view: $query");
	$result = $adb->query($query) or die("Couldn't get the group listing");

echo get_form_header($app_strings['LBL_GROUP_ALLOCATION_TITLE'], "", false);

$list = '<table width="100%" cellpadding="0" cellspacing="0" class="formBorder"><tr>';
$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
$list .= '<td class="moduleListTitle" height="21" style="padding:0px 3px 0px 3px">';
$list .= $app_strings['LBL_ENTITY_NAME'].'</td>';
$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
$list .= '<td class="moduleListTitle" style="padding:0px 3px 0px 3px"> ';
$list .= $app_strings['LBL_GROUP_NAME'].'</td>';
$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
$list .= '<td class="moduleListTitle" style="padding:0px 3px 0px 3px"> ';
$list .= $app_strings['LBL_ENTITY_TYPE'].'</td>';
$list .= '</tr>';
$list .= ' ';



$i=1;
while($row = $adb->fetch_array($result))
{
  if ($i%2==0)
    $trowclass = 'evenListRow';
  else
    $trowclass = 'oddListRow';
  $list .= '<tr class="'. $trowclass.'">';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
  if($row["type"] == "Leads")
  {
    $list .= '<td height="21" style="padding:0px 3px 0px 3px"><a href=index.php?module=Leads';
  }
  else
  {
    $list .= '<td height="21" style="padding:0px 3px 0px 3px"><a href=index.php?module=Activities';
  }

  $list .= '&action=DetailView&record=';
  $list .= $row["id"] ;
  $list .='>';
  $list .= $row["name"];
  $list .= '</a></td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
  $list .= '<td height="21"  style="padding:0px 3px 0px 3px">';
  $list .= $row["groupname"];
  $list .= '</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
  $list .= '<td height="21"  style="padding:0px 3px 0px 3px">';
  $list .= $row["type"];
  $list .= '</td>';
  $list .= '</tr>';
  $i++;
}

        $list .= '</table>';

echo $list;
$list='';
require_once('modules/HelpDesk/ListTickets.php');
global $current_language;
$current_module_strings = return_module_language($current_language, 'Calendar');

$t=Date("Ymd");
?>
</td>
<td width="320" valign="top" align="center">
            <?php include("modules/Calendar/minical.php"); ?>
            <form name="Calendar" method="GET" action="index.php">
                <input type="hidden" name="module" value="Calendar">
                <input type="hidden" name="action">
                <input type="hidden" name="t">
                <input title="<? echo $current_module_strings['LBL_DAY_BUTTON_TITLE']?>" accessKey="<? echo $current_module_strings['LBL_DAY_BUTTON_KEY']?>" onclick="this.form.action.value='calendar_day';this.form.t.value='<? echo $t?>'" type="image" src="<? echo $image_path ?>day.gif" name="button" value="  <? echo $current_module_strings['LBL_DAY']?>  " >
                <input title="<? echo $current_module_strings['LBL_WEEK_BUTTON_TITLE']?>" accessKey="<? echo $current_module_strings['LBL_WEEK_BUTTON_KEY']?>" onclick="this.form.action.value='calendar_week';this.form.t.value='<? echo $t?>'" type="image" src="<? echo $image_path ?>week.gif" name="button" value="  <? echo $current_module_strings['LBL_WEEK']?>  " >
                <input title="<? echo $current_module_strings['LBL_MON_BUTTON_TITLE']?>" accessKey="<? echo $current_module_strings['LBL_MON_BUTTON_KEY']?>" onclick="this.form.action.value='calendar_month';this.form.t.value='<? echo $t?>'" type="image" src="<? echo $image_path ?>month.gif" name="button" value="  <? echo $current_module_strings['LBL_MON']?>  " >
            </form>
<?php echo get_left_form_header($mod_strings['LBL_PIPELINE_FORM_TITLE']);
	include ("modules/Dashboard/Chart_my_pipeline_by_sales_stage.php"); 
	echo get_left_form_footer(); ?>
</td></tr></table><br>
