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
//Code Added by Minnie -Starts
 include_once $calpath .'webelements.p3';
 include_once $calpath .'permission.p3';
 require_once('include/database/PearDatabase.php');

 require_once('modules/Calendar/preference.pinc');
global $calpath,$callink;
global $mod_strings;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
$callink = 'index.php?module=Calendar&action=';

/**
 * Create HTML to display calendar header tabs
 * @param $t -- date :: Type string
 * @param $view -- view name(day/week/month) :: Type string
 * Returns the html in string format.
 */
function getHeaderTab($t,$view)
{
        $day_selected = $week_selected = $month_selected = $shared_selected = "dvtUnSelectedCell";
        if($view == 'day')
		$day_selected="dvtSelectedCell";
        if($view == 'week')
                $week_selected="dvtSelectedCell";
        if($view == 'month')
                $month_selected="dvtSelectedCell";
	if($view == 'shared')
	        $shared_selected = "dvtSelectedCell";
        $space_class = "dvtTabCache";
	$tabhtml = "";
	$tabhtml .= <<<EOQ
	<table border="0" cellspacing="0" cellpadding="0" width="95%" align="center">
        <tr>
           <form name="Calendar" method="GET" action="index.php">
           <input type="hidden" name="module" value="Calendar">
           <input type="hidden" name="action" value="">
           <input type="hidden" name="t">
           <td>
              <table border=0 cellspacing=0 cellpadding=3 width=100%>
              <tr>
                 <td class="$space_class" style="width:10px" nowrap>&nbsp;</td>
                 <td class="$day_selected" align=center nowrap><a href="index.php?module=Calendar&action=new_calendar&sel=day&t=$t">Day</a></td>
                 <td class="$space_class" style="width:10px">&nbsp;</td>
                 <td class="$week_selected" align=center nowrap><a href="index.php?module=Calendar&action=new_calendar&sel=week&t=$t">Week</a></td>
                 <td class="$space_class" style="width:10px">&nbsp;</td>
                 <td class="$month_selected" align=center nowrap><a href="index.php?module=Calendar&action=new_calendar&sel=month&t=$t">Month</a></td>
		 <td class="$space_class" style="width:10px">&nbsp;</td>
		 <td class="$shared_selected" align=center nowrap><a href="index.php?module=Calendar&action=new_calendar&sel=share&t=$t">Share</a></td>
                 <td class="$space_class" style="width:10px">&nbsp;</td>
                 <td class="$space_class" style="width:100%">&nbsp;</td>
              </tr>
              </table>
           </td>
           </form>
        </tr>
        <tr>
           <td valign=top align=left >
              <table border=0 cellspacing=0 cellpadding=3 width=100% class="dvtContentSpace">
              <tr>
                 <td align=left style="padding:5px">
EOQ;
return $tabhtml;
}

/**
 * Create HTML to display calendar heading for each view
 * @params $prev,$next,$day_from,$day_to -- date :: Type string
 * @param $pref -- Object of preference class
 * @param $view -- view name(day/week/month) :: Type string
 * @param $month -- month :: Type string
 * @param $year -- year :: Type string
 * @param $f -- string :: Type string
 * @param $n -- Numeric representation of a month, without leading zeros :: Type string
 * $param $d -- Day of the month, 2 digits with leading zeros :: Type string
 * Returns the html in string format.
 */
function getCalendarHeader($prev,$next,$view,$day_from,$pref="",$day_to="",$month="",$year="",$d="",$f="",$n="")
{
	global $calpath,$callink,$image_path,$mod_strings;
	$headerhtml = "";
	$leftimage = $image_path."calTopLeft.gif";
	$rightimage = $image_path."calTopRight.gif";
        $calsep_image = $image_path."calSep.gif";
	if($view == "day")
	{
		$submitlink = $callink ."calendar_dayview";
		$prevlink = $callink ."calendar_dayview&t=".$prev->getYYYYMMDD();
		$nextlink = $callink ."calendar_dayview&t=".$next->getYYYYMMDD();
		$linkto_previous = $pref->menulink($prevlink,$pref->getImage(left,'list'),$prev->getDate());
		$linkto_next = $pref->menulink($nextlink,$pref->getImage(right,'list'),$next->getDate());
		$label = "&nbsp;". strftime($mod_strings['LBL_DATE_TITLE'],$day_from) ."&nbsp;";
	}
	if($view == "week")
	{
		$submitlink = $callink ."calendar_weekview";
		$prevlink = $callink ."calendar_weekview&t=".$prev;
		$nextlink = $callink ."calendar_weekview&t=".$next;
		$linkto_previous = $pref->menulink($prevlink,$pref->getImage(left,'list'),$mod_strings['LBL_LAST_WEEK']);
		$linkto_next = $pref->menulink($nextlink,$pref->getImage(right,'list'),$mod_strings['LBL_NEXT_WEEK']);
		$label = $mod_strings['LBL_WEEK'] ."&nbsp;of&nbsp;" . $month . "&nbsp;".$day_from."&nbsp;to&nbsp;".$month."&nbsp;".$day_to."&nbsp;". $year ."&nbsp;";
	}
	if($view == "month")
	{
		$submitlink = $callink ."calendar_monthview";
		$linkto_previous = "<a href=\"".$submitlink."&f=".$f."&n=".$n."&m=".$prev."&d=".$d."&y=".$day_from."\" title=\"Previous Month\"><img border=\"0\" src=\"".$image_path."left.gif\"></a>";
		$linkto_next = "<a href=\"". $submitlink ."&f=".$f."&n=".$n."&m=".$next."&d=".$d."&y=".$day_to."\" title=\"Next Month\"><img border=\"0\" src=\"".$image_path."right.gif\"></a>";
		$label = $month ." ". $year;
	}

        $headerhtml .= <<<EOQ
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
	<form action="$submitlink" method="get" name="calendar">
	<tr><td>
	<table border=0 cellspacing=0 cellpadding=0 width=100% class="calTopBg"><tr><td>
	<img src=$leftimage></td>
	<td>$linkto_previous</td>
	<td><img src=$calsep_image></td>
	<td align="center" width="100%" class="lvtHeaderText">$label</td>
	<td><img src=$calsep_image></td>
	<td>$linkto_next</td>
	<td><img src=$rightimage></td><td>
	</tr></table></td></tr>
	
        
EOQ;
return $headerhtml;
  
}

/**
 * Function to get the lists of sharedids related in calendar sharing of an user
 * This function accepts the user id as arguments and
 * returns the shared ids related with the user id
 * as an array
 */
function getSharedUserId($id)
{
        global $adb;
        $query = "SELECT * from sharedcalendar where userid=".$id;
        $result = $adb->query($query);
        $rows = $adb->num_rows($result);
        for($j=0;$j<$rows;$j++)
        {
	        $sharedid[] = $adb->query_result($result,$j,'sharedid');
        }
        return $sharedid;
}

/**
 * Function to get the lists of user ids who shared their calendar with an user
 * This function accepts the shared id as arguments and
 * returns the user ids related with the shared id
 * as a comma seperated string
 */
function getSharedCalendarId($sharedid)
{
	global $adb;
	$query = "SELECT * from sharedcalendar where sharedid=".$sharedid;
	$result = $adb->query($query);
	if($adb->num_rows($result)!=0)
	{
		for($j=0;$j<$adb->num_rows($result);$j++)
			$userid[] = $adb->query_result($result,$j,'userid');
		$shared_ids = implode (",",$userid);
	}
	return $shared_ids;
}

/**
 * Function to get the label for user lists
 * Returns the label as an array
 */
function getSharedUserListViewHeader()
{
	global $mod_strings;
		$header_label=array($mod_strings['LBL_LIST_NAME'],
			            $mod_strings['LBL_LIST_USER_NAME'],
				   );
	return $header_label;
}

/**
 * Function to get the entries for user lists
 * This function accepts the shared id as arguments and
 * returns the user entries related with the shared id
 * as an array
 */
function getSharedUserListViewEntries($sharedid)
{
	global $adb;
	$query = "SELECT * from users where id=".$sharedid;
	$result =$adb->query($query);
	$entries[]=$adb->query_result($result,0,'first_name').' '.$adb->query_result($result,0,'last_name');
	$entries[]='<a href="index.php?action=DetailView&module=Users&parenttab=Settings&record='.$sharedid.'">'.$adb->query_result($result,0,'user_name').'</a>';
	return $entries;

}

/**
 * Function to get userid and username of all users except the current user
 * @returns $userArray -- User Array in the following format:
 * $userArray=Array($userid1=>$username, $userid2=>$username,............,$useridn=>$username);
 */
function getOtherUserName($id)
{
	global $adb;
	$query="select * from users where deleted=0 and id!=".$id;
	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	$user_details=Array();
	for($i=0;$i<$num_rows;$i++)
	{
		$userid=$adb->query_result($result,$i,'id');
		$username=$adb->query_result($result,$i,'user_name');
 		$user_details[$userid]=$username;
	}
	return $user_details;
}

//Code Added by Minnie -Ends
?>
