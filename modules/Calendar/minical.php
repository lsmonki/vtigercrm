<?php
/**
 * Copyright 1999 - 2004 by Gero Kohnert
 */
global $calpath,$callink,$current_user;
$callink = "index.php?module=Calendar&action=";
include_once $calpath .'webelements.p3';
include_once $calpath .'permission.p3';
require_once('modules/Calendar/preference.pinc');
require_once('modules/Calendar/appointment.pinc');

global $current_language;
$current_module_strings = return_module_language($current_language, 'Calendar');

/* Check if user is allowed to use it */
//check_user();
session_write_close();
loadlayout();
$pref = new preference();

global $theme;
$theme_path="themes/".$theme."/";
require_once ($theme_path."layout_utils.php");

if($d == '')
$d = Date("d");
if($m == '')
$m = Date("n");
if($y == '')
$y = Date("Y");
$f = "default";

$l = new layout($current_user);
echo $l->PrintHeader("MiniCal");

if (isset($_GET['f']) ) {
  $f = $_GET['f'];
}
if (isset($_GET['d']) ) {
  $d = $_GET['d'];
}
if (isset($_GET['m']) ) {
  $m = $_GET['m'];
}
if (isset($_GET['y']) ) {
  $y = $_GET['y'];
}
if (isset($_GET['n']) ) {
  $n = $_GET['n'];
}
if ( $d == -1 ) {
  $d = Date("d");
}
if ( $m == -1 ) {
  $m = Date("n");
}
if ( $y == -1 ) {
  $y = Date("Y");
}

echo "<html>\n";
echo "<style type=\"text/css\">@import url(\"". $theme_path ."/style.css\");</style>";
echo "<script language='JavaScript'>\n";
echo " function closeandaway (d, m, y) { \n";
echo "  var x = opener.document.appnew; \n";

echo "  x.". $f ."_d.selectedIndex = d-1; \n";
echo "  x.". $f ."_m.selectedIndex = m-1; \n";
echo "  x.". $f ."_y.selectedIndex = y; \n";

# echo "  d.EventDT.value = mo + '/' + dy + '/' + yr; \n";
echo "  window.close(); \n";
echo " }\n";
echo " function noneandaway () { \n";
echo "  var x = opener.document.forms[0]; \n";
echo "  \n";
echo "  x.". $f ."_d.selectedIndex = 0; \n";
echo "  x.". $f ."_m.selectedIndex = 0; \n";
echo "  x.". $f ."_y.selectedIndex = 0; \n";
# echo "  d.EventDT.value = mo + '/' + dy + '/' + yr; \n";
echo "  window.close(); \n";
echo " }\n";
echo "</script>\n";


$yoff =  Date("Y") + 10;
echo "<body leftmargin=\"0\" topmargin=\"5\">\n";
echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"outer\">\n";
echo "<tr><td>";
echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" width=\"100%\" align=\"center\" class=\"navigate\">\n";
echo "<tr>\n";
echo "\n";
$nm = $m + 1;
$ny = $y;
if ( $nm == 13 ) {
  $nm = 1;
  $ny = $y + 1;
}
$lm = $m - 1;
$ly = $y;
if ( $lm == 0 ) {
  $lm = 12;
  $ly = $y -1;
}
$m_name = $current_module_strings['cal_month_long'][$m];
if($currentModule == 'Home')
{
	$path_name = "index.php?module=Home&action=index";
}
else
{
	$path_name = "index.php?module=Calendar&action=calendar_day";
}
echo "<td align=\"left\"><a class=\"nodeco\" href=\"".$path_name."&f=".$f."&n=".$n."&m=".$lm."&d=".$d."&y=".$ly."\" title=\"Previous Month\"><img border=\"0\" src=\"".$image_path."left.gif\"></a></td>\n";
echo "<td align=\"center\"><b>". $m_name ." ". $y ."</b></td>\n";
echo "<td align=\"right\"><a class=\"nodeco\" href=\"". $path_name ."&f=".$f."&n=".$n."&m=".$nm."&d=".$d."&y=".$ny."\" title=\"Next Month\"><img border=\"0\" src=\"".$image_path."right.gif\"></a></td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "</td></tr>\n";
echo "<tr><td>\n";
echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"1\" width=\"100%\" align=\"center\">\n";
echo "<tr>\n";
echo "<th class=\"weekday\">". $current_module_strings['LBL_WEEK'] ."</th>\n";

$WeekDayName=array('LBL_SM_SUN','LBL_SM_MON','LBL_SM_TUE','LBL_SM_WED','LBL_SM_THU','LBL_SM_FRI','LBL_SM_SAT');
 
for ( $i = $current_user->weekstart;$i<=6;$i++ ) { 
  echo "<th class=\"weekday\">". $current_module_strings[$WeekDayName[$i]] ."</th>\n";
}
 
for ( $i = 0;$i<$current_user->weekstart;$i++ ) {
  echo "<th class=\"weekday\">". $current_module_strings[$WeekDayName[$i]] ."</th>\n";
}

echo "</tr>\n";

$ts = mktime(12,0,0,$m,1,$y);

$today=Date("Ymd",time());

/* Back to last weekstart before ts */
while ( Date("w",$ts) != $current_user->weekstart ) {
  $ts -= 86400;
}

$go = 1;
$a = 0;
$w = 0;
while ( $go == 1 ) {
 $wd = Date("w",$ts);
 $xd = Date("j",$ts);
 $xxd = Date("d",$ts);
 $xm = Date("n",$ts);
 $xy = Date("Y",$ts);
 if ( $wd == $l->user->weekstart ) {
   # new week
   echo "<tr>\n";
   $w0 =  (( 1 + Date("w",mktime(12,0,0,1,1, Date("Y",$ts) ) )) % 7) > 3;
   $wn = sprintf("%02d", Round( (Date("z",$ts)+7 ) / 7) );
   echo " <td align=\"right\" class=\"week\">". $wn ."&nbsp;</td>\n";
 }

 $col = "";
 if ( $today == Date("Ymd",$ts) ) 
 {
   $col = "today";
 }
 else if ($wd == 0 ) 
 {
   $col = "holiday";
 }
 else if ($wd == 6 ) 
 {
   $col = "freeday";
 }
 else if ($xm != $m ) 
 {
   $col = "otherday";
 } 
 else 
 {
   $col = "appday";
 }
 
 //For displaying the class - included by Jaguar
 if($xm !=$m)
 {
	 echo "<td align=\"right\" class=\"". $col ."\">\n";
 }
 else
 {
	 $tdavl="<td align=\"right\" class=\"";
 }
// ends - Jaguar
 
if ($xm == $m )
{
	#echo "  <a href=\"JavaScript:closeandaway(". ($xd + $n) .",". ($xm + $n) .",". ($yoff - $xy + $n) .")\">". $xxd ."</a>";
	// added by raj
	/* Select appointments for this day */
	$from =  new DateTime();
	$to   =  new DateTime();
	$from->setDateTimeTS($ts - 12 * 3600);
	$to->setDateTimeTS($ts - 12 * 3600);
	#$to->addDays(7);

	$pref->callist = array();
	$app = new appointment();
	$app->readCal($pref,$from,$to);
	// appointment::readCal($pref,$from,$to);

	$dd = new DateTime();
	# $d = strftime($lang['DateFormatStr'],$ts);
	$dd->setDateTimeTS($ts);
	$d = $dd->getDate();
	$tref = Date("Ymd",$ts);
	$eventclass=$col;
	if(count($pref->callist)!=0)
	{
		//Classes are provided when events are created  - Jaguar
		if($col == "today")
			$eventclass="todayevent";
		else
			$eventclass="eventbold";
	}
	$tdavl.=$eventclass."\">\n";
	echo $tdavl; //displaying the td - Jaguar

//Display Date with link, move here from above to get tref date format

	if ($col=="today")
	{	
		echo "<a href=\"index.php?module=Calendar&action=calendar_day&t=".$tref."\">". $xxd ."</a>"; 
	}	
	else
	{
		 echo "<a href=\"index.php?module=Calendar&action=calendar_day&t=".$tref."\">". $xxd ."</a>" ;
	}
//
       $next = NextDay($ts);
       # Check for workday
       if ( ! $dd->isWorkDay($pref) ) {
         $ts = $next;
         $day++;
         continue;
       }
       $dinfo = GetDaysInfo($ts);


       $hastable = 0;
       $a = 0;

	//
 }
 else
 {
 	echo "&nbsp;";
 }

 #echo "  <a href=\"JavaScript:closeandaway(". ($xd + $n) .",". ($xm + $n) .",". ($yoff - $xy + $n) .")\">". $xxd ."</a>";

 echo "</td>\n";

 if ( $wd == ($l->user->weekstart+6)%7   ) {
   # end week
   echo "</tr>\n";
   if ( ($xm > $m) || ($xy > $y)  ) {
     break;
   }
 }
 $a++;
 $w++;
 $ts += 86400;
}
if ( $n == 1 ) {
  echo "<tr><th colspan=\"8\"><a class=\"nodeco\" href=\"JavaScript:noneandaway()\">NONE</a></th></tr>";
}

echo "</table>\n";
echo "</td></tr></table>\n";
# selection of none allowed

echo "<br>\n";
echo "</body>\n";
echo "</html>\n";

?>
<!--
    CVS Info:  $Id: minical.php,v 1.17 2005/07/11 07:18:10 indigoleopard Exp $
    $Author: indigoleopard $
-->
