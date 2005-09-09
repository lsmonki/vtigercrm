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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Home/index.php,v 1.28 2005/04/20 06:57:47 samk Exp $
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
require_once('modules/Users/UserInfoUtil.php');
global $app_strings;
global $app_list_strings;
global $mod_strings;

//Security check for related list
global $profile_id;
$tab_per_Data = getAllTabsPermission($profile_id);
$permissionData = $_SESSION['action_permission_set'];

$_REQUEST['search_form'] = 'false';
$_REQUEST['query'] = 'true';
$_REQUEST['status'] = 'In Progress--Not Started';
$_REQUEST['current_user_only'] = 'On';

$task_title = $mod_strings['LBL_OPEN_TASKS'];

?>
<table width=100% cellpadding="5" cellspacing="5" border="0">
<tr>
<td valign="top">
<?php
if($tab_per_Data[9] == 0)
{
	if($permissionData[9][3] == 0)
	{
		include("modules/Activities/OpenListView.php") ;
	}
}
?>
<br>
<?php


	//Added to support the inclusion of the Top Accounts in the Home Page. 
	//Fix given by Mike Crowe
   if($tab_per_Data[2] == 0)
           {
                    if($permissionData[2][3] == 0)
                    {
                      include("modules/Accounts/ListViewTop.php");
                    }
	   }  
	
if($tab_per_Data[2] == 0)
{
	if($permissionData[2][3] == 0)
        {
		 include("modules/Potentials/ListViewTop.php");
	}
}
 ?>
<br>
<?php
//get all the group relation tasks
global $current_user;
$userid= $current_user->id;
$groupName = fetchUserGroups($userid);
$query = "select leaddetails.leadid as id,leaddetails.lastname as name,leadgrouprelation.groupname as groupname, 'Leads     ' as Type from leaddetails inner join leadgrouprelation on leaddetails.leadid=leadgrouprelation.leadid inner join crmentity on crmentity.crmid = leaddetails.leadid where  crmentity.deleted=0  and leadgrouprelation.groupname is not null and leadgrouprelation.groupname='".$groupName."' union all select activity.activityid,activity.subject,activitygrouprelation.groupname,'Activities' as Type from activity inner join activitygrouprelation on activitygrouprelation.activityid=activity.activityid inner join crmentity on crmentity.crmid = activity.activityid where  crmentity.deleted=0 and activitygrouprelation.groupname is not null and groupname ='".$groupName."' union all select troubletickets.ticketid,troubletickets.title,ticketgrouprelation.groupname,'Tickets   ' as Type from troubletickets inner join ticketgrouprelation on ticketgrouprelation.ticketid=troubletickets.ticketid inner join crmentity on crmentity.crmid = troubletickets.ticketid and crmentity.deleted=0 and ticketgrouprelation.groupname is not null and ticketgrouprelation.groupname='".$groupName."'";


//$query = "select leaddetails.lastname,leadgrouprelation.groupname, 'Leads' as Type from leaddetails inner join leadgrouprelation on leaddetails.leadid=leadgrouprelation.leadid inner join crmentity on crmentity.crmid = leaddetails.leadid where  crmentity.deleted=0 union all select activity.subject,activitygrouprelation.groupname,'Activities' as Type from activity inner join activitygrouprelation on activitygrouprelation.activityid=activity.activityid inner join crmentity on crmentity.crmid = activity.activityid where  crmentity.deleted=0 union all select troubletickets.ticketid,troubletickets.groupname,'Tickets' as Type from troubletickets inner join seticketsrel on seticketsrel.ticketid = troubletickets.ticketid inner join crmentity on crmentity.crmid = seticketsrel.ticketid where troubletickets.groupname is not null and crmentity.deleted=0";

  $log->info("Here is the where clause for the list view: $query");
	$result = $adb->limitquery($query,0,5) or die("Couldn't get the group listing");

//echo get_form_header($app_strings['LBL_GROUP_ALLOCATION_TITLE'], "", false);
$list ='<table border=0 cellspacing=0 cellpadding=0 width=100%>
<tr style="cursor:pointer;" unslectable="on" onclick="javascript:expandCont(\'home_mygrp\');"><td nowrap><img src="'.$image_path.'myGroupAllocation.gif" style="padding:5px"></td><td width=100%><b>'.$app_strings['LBL_GROUP_ALLOCATION_TITLE'].'</b> </td><td nowrap><img src="themes/images/toggle2.gif" id="img_home_mygrp" border=0></td></tr>';
$list .= '<tr><td colspan=3 bgcolor="#000000" style="height:1px;"></td></tr>';
$list .= '<tr><td colspan=3>';
$list .= '<div id="home_mygrp" style="display:block;">';
$list .= '<table width="100%" cellpadding="0" cellspacing="0"><tr>';
$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
$list .= '<td class="moduleListTitle" height="21" style="padding:0px 3px 0px 3px">';
$list .= $app_strings['LBL_ENTITY_NAME'].'</td>';
$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
$list .= '<td class="moduleListTitle" style="padding:0px 3px 0px 3px"> ';
$list .= $app_strings['LBL_GROUP_NAME'].'</td>';
$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
$list .= '<td class="moduleListTitle" style="padding:0px 3px 0px 3px"> ';
$list .= $app_strings['LBL_ENTITY_TYPE'].'</td>';
$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td></tr>';
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
  if($row["type"] == "Tickets")
  {
    $list .= '<td height="21" style="padding:0px 3px 0px 3px"><a href=index.php?module=HelpDesk';
  }
  elseif($row["type"] == "Activities")
  {
	$acti_type = getActivityType($row["id"]);
	$list .= '<td height="21" style="padding:0px 3px 0px 3px"><a href=index.php?module='.$row["type"];
	if($acti_type == 'Task')
	{
        	$list .= '&activity_mode=Task';
	}
        elseif($acti_type == 'Call' || $acti_type == 'Meeting')
	{
                $list .= '&activity_mode=Events';
	}
  }
  else
  {
    $list .= '<td height="21" style="padding:0px 3px 0px 3px"><a href=index.php?module='.$row["type"];
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

        $list .= '<tr><td WIDTH="1" colspan="6" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td></tr></table>';
	$list .= '</div></td></tr></table>';
$list .= '<script language=\'Javascript\'>
        var leftpanelistarray=new Array(\'home_mygrp\');
  setExpandCollapse_gen()</script>';

echo $list;
function getActivityType($id)
{
	global $adb;
	$quer = "select activitytype from activity where activityid=".$id;
	$res = $adb->query($quer);
	$acti_type = $adb->query_result($res,0,"activitytype");
	return $acti_type;

}

echo '<BR>';
$list='';
if($tab_per_Data[13] == 0)
{
        if($permissionData[13][3] == 0)
        {
		require_once('modules/HelpDesk/ListTickets.php');
	}
}
echo '<BR><BR>';
include("modules/CustomView/ListViewTop.php");
echo '<BR>';
if($tab_per_Data[20] == 0)
{
        if($permissionData[20][3] == 0)
        {
		require_once('modules/Quotes/ListTopQuotes.php');
	}
}
echo '<BR>';
if($tab_per_Data[22] == 0)
{
        if($permissionData[22][3] == 0)
        {
		require_once('modules/Orders/ListTopSalesOrder.php');
	}
}
echo '<BR>';
if($tab_per_Data[23] == 0)
{
        if($permissionData[23][3] == 0)
        {
		require_once('modules/Invoice/ListTopInvoice.php');
	}
}
global $current_language;
$current_module_strings = return_module_language($current_language, 'Calendar');

$t=Date("Ymd");
?>
</td>
<td width="300" valign="top" align="center">
            <?php include("modules/Calendar/minical.php"); ?>
            <form name="minc" method="GET" action="index.php">
                <input type="hidden" name="module" value="Calendar">
                <input type="hidden" name="action">
                <input type="hidden" name="t">
                <!--<input title="<? echo $current_module_strings['LBL_DAY_BUTTON_TITLE']?>" accessKey="<? echo $current_module_strings['LBL_DAY_BUTTON_KEY']?>" onclick="this.form.action.value='calendar_day';this.form.t.value='<? echo $t?>'" type="image" src="<? echo $image_path ?>day.gif" name="button" value="  <? echo $current_module_strings['LBL_DAY']?>  " >
                <input title="<? echo $current_module_strings['LBL_WEEK_BUTTON_TITLE']?>" accessKey="<? echo $current_module_strings['LBL_WEEK_BUTTON_KEY']?>" onclick="this.form.action.value='calendar_week';this.form.t.value='<? echo $t?>'" type="image" src="<? echo $image_path ?>week.gif" name="button" value="  <? echo $current_module_strings['LBL_WEEK']?>  " >
                <input title="<? echo $current_module_strings['LBL_MON_BUTTON_TITLE']?>" accessKey="<? echo $current_module_strings['LBL_MON_BUTTON_KEY']?>" onclick="this.form.action.value='calendar_month';this.form.t.value='<? echo $t?>'" type="image" src="<? echo $image_path ?>month.gif" name="button" value="  <? echo $current_module_strings['LBL_MON']?>  " >-->
            </form>
<?php echo get_left_form_header($mod_strings['LBL_PIPELINE_FORM_TITLE']);
	include ("modules/Dashboard/Chart_my_pipeline_by_sales_stage.php");
	echo get_left_form_footer(); ?>
</td></tr></table><br>
