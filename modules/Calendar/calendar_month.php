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
global $mod_strings,$currentModule,$app_strings;

 echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'], true); 
echo "\n<BR>\n";
 $t=Date("Ymd");
?>
		<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<form name="Calendar" method="GET" action="index.php">
			<input type="hidden" name="module" value="Calendar">
			<input type="hidden" name="action">
			<input type="hidden" name="t">
		<td><input title="<? echo $mod_strings['LBL_DAY_BUTTON_TITLE']?>" accessKey="<? echo $mod_strings['LBL_DAY_BUTTON_KEY']?>" onclick="this.form.action.value='calendar_day';this.form.t.value='<?echo $t?>'" type="image" src="<? echo $image_path ?>day.gif" name="button" value="  <? echo $mod_strings['LBL_DAY']?>  " >
		<input title="<? echo $mod_strings['LBL_WEEK_BUTTON_TITLE']?>" accessKey="<? echo $mod_strings['LBL_WEEK_BUTTON_KEY']?>" onclick="this.form.action.value='calendar_week';this.form.t.value='<?echo $t?>'" type="image" src="<? echo $image_path ?>week.gif" name="button" value="  <? echo $mod_strings['LBL_WEEK']?>  " >
		<input title="<? echo $mod_strings['LBL_MON_BUTTON_TITLE']?>" accessKey="<? echo $mod_strings['LBL_MON_BUTTON_KEY']?>" onclick="this.form.action.value='calendar_month';this.form.t.value='<?echo $t?>'" type="image" src="<? echo $image_path ?>month_sel.gif" name="button" value="  <? echo $mod_strings['LBL_MON']?>  " ></td>
		</tr>
			</form>
		</table>	
<?
session_write_close();
loadlayout();
$pref = new preference();

global $theme;
$theme_path="themes/".$theme."/";
require_once ($theme_path."layout_utils.php");

$d = Date("d");
$m = Date("n");
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

#echo "<html>\n";
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
#echo "<body leftmargin=\"0\" topmargin=\"5\">\n";
echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"95%\" class=\"outer\">\n";
echo "<tr><td>";
echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" width=\"100%\" align=\"center\" class=\"navigate\">\n";
echo "<tr height=\"35\">\n";
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

$m_name = $mod_strings['cal_month_long'][$m];

echo "<td align=\"left\"><a class=\"nodeco\" href=\"".$callink."calendar_month&f=".$f."&n=".$n."&m=".$lm."&d=".$d."&y=".$ly."\" title=\"Previous Month\"><img border=\"0\" src=\"".$image_path."left.gif\"></a></td>\n";
echo "<td align=\"center\" class=\"calhead\">". $m_name ." ". $y ."</td>\n";
echo "<td align=\"right\"><a class=\"nodeco\" href=\"". $callink ."calendar_month&f=".$f."&n=".$n."&m=".$nm."&d=".$d."&y=".$ny."\" title=\"Next Month\"><img border=\"0\" src=\"".$image_path."right.gif\"></a></td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "</td></tr>\n";
echo "<tr><td>\n";
echo "<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\" align=\"center\">\n";
echo "<tr>\n";
echo "<th class=\"weekday\">". $mod_strings['LBL_WEEK'] ."</th>\n";

for ( $i = $current_user->weekstart;$i<=6;$i++ ) { 
  echo "<th class=\"weekday\">". $mod_strings['LBL_DAY'.$i] ."</th>\n";
}
 
for ( $i = 0;$i<$current_user->weekstart;$i++ ) {
  echo "<th class=\"weekday\">". $mod_strings['LBL_DAY'.$i] ."</th>\n";
}

echo "</tr>\n";

$ts = mktime(12,0,0,$m,1,$y);

$today=Date("Ymd",time());

/* Back to last weekstart before ts */
while ( Date("w",$ts) != $current_user->weekstart ) {
  $ts -= 86400; //  1 day has 86400 seconds
}

$go = 1;
$a = 0;
$w = 0;
while ( $go == 1 ) {
 $wd = Date("w",$ts);  // day of week (0-6)
 $xd = Date("j",$ts);  // day of month without leading zero
 $xxd = Date("d",$ts); // day of month with leading zero
 $xm = Date("n",$ts);  // month (1-12
 $xy = Date("Y",$ts);  // Year (2005)

/* if ( $wd == $l->user->weekstart ) {
   # new week
   echo "<tr>\n";
   $w0 =  (( 1 + Date("w",mktime(12,0,0,1,1, Date("Y",$ts) ) )) % 7) > 3;
   $wn = sprintf("%02d", Round( (Date("z",$ts)+7 ) / 7) );
   echo " <td align=\"right\" class=\"week\">". $wn ."&nbsp;</td>\n";
 }
*/

// Overlapping days -starts by Fredy

 if ( $wd == $l->user->weekstart ) {
     if ($xm > $m or $xy > $y) {
         break;
     }
   # new week
   echo "<tr>\n";
   $w0 =  (( 1 + Date("w",mktime(12,0,0,1,1, Date("Y",$ts) ) )) % 7) > 3;
   $wn = sprintf("%02d", Round( (Date("z",$ts)+7 ) / 7) );
   echo " <td align=\"right\" class=\"week\">". $wn ."&nbsp;</td>\n";
 }

// check for overlapping days
 $month_overlap = (($xm == $nm) or ($xm == $lm));

 $col = "";

// Overlapping days -ends 

 $col = "";

if (!$month_overlap) {

	if ( $today == Date("Ymd",$ts) ) {
		$col = "today";
	} else if ($wd == 0 ) {
		$col = "holiday";
	} else if ($wd == 6 ) {
		$col = "freeday";
	} else if ($xm != $m ) {
		$col = "otherday";
	} else {
		$col = "appday";
	}
}
else 
{ // day overlaps by Fredy
     if ( $today == Date("Ymd",$ts)) {
       $col = "ol-today";
     } else if ($wd == 0) {
       $col = "ol-holiday";
     } else if ($wd == 6) {
       $col = "ol-freeday";
     } else if ($xm != $m ) {
       $col = "ol-otherday";
     } else {
       $col = "ol-appday";
     }
 }

 
 echo "<td align=\"right\" valign=\"top\" class=\"". $col ."\" height=\"90\" width=\"200\">\n";
 if (($xm == $m ) || $month_overlap)
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
//Display Date with link, move here from above to get tref date format

	if ($col=="today")
		echo "  <a class=\"today\" href=\"index.php?module=". $currentModule ."&action=calendar_day&t=".$tref."\">". $xxd ."</a>";
	else
		echo "  <a href=\"index.php?module=". $currentModule ."&action=calendar_day&t=".$tref."\">". $xxd ."</a>";
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

       foreach ($pref->callist as $idx => $x) {
         /* the correct day */
         if ( ! $pref->callist[$idx]->inside($dd) ) {
           continue;
         }
         if ( $hastable == 0 ) {
           echo "\n <table class=\"event\" cellspacing=\"2\" cellpadding=\"1\" border=\"0\" width=\"100%\" align=\"center\" style=\"margin-top:2\">\n";
           $hastable = 1;
         } 
        else {
           //echo "  <tr><td class=\"". $dinfo[color] ."\" colspan=\"3\"><img src=\"". $image_path ."black.png\" width=\"100%\" height=\"1\" alt=\"--------\"></td></tr>\n";
	   echo "  <tr><td height=\"2\" colspan=\"4\" class=\"eventSep\"><img src=\"". $image_path ."blank.gif\"></td></tr>\n";
         }
#echo "1 ".$this->user->weekstart ."<br />";
         $pref->callist[$idx]->formatted();
#echo "2 ".$this->user->weekstart ."<br />";
         $a++;
       }

       if ( $hastable == 1 ) {
         echo "</table>\n";
       }
       while ( $a < 2 ) {
         echo " <br/>\n";
         $a++;
       }

	//
 }
 else
 {
 	echo "&nbsp;";
 }
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
echo "</td></tr>";
echo "<tr><td>";
echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" width=\"100%\" align=\"center\" class=\"navigate\">\n";
echo "<tr height=\"30\">\n";
echo "<td align=\"left\"><a class=\"nodeco\" href=\"".$callink."calendar_month&f=".$f."&n=".$n."&m=".$lm."&d=".$d."&y=".$ly."\" title=\"Previous Month\"><img border=\"0\" src=\"".$image_path."left.gif\"></a></td>\n";
echo "<td>&nbsp;</td>\n";
echo "<td align=\"right\"><a class=\"nodeco\" href=\"". $callink ."calendar_month&f=".$f."&n=".$n."&m=".$nm."&d=".$d."&y=".$ny."\" title=\"Next Month\"><img border=\"0\" src=\"".$image_path."right.gif\"></a></td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "</td></tr></table>\n";
# selection of none allowed

echo "<br>\n";
#echo "</body>\n";
#echo "</html>\n";

?>
<!--
    CVS Info:  $Id: calendar_month.php,v 1.4 2005/02/28 13:27:41 sarajkumar Exp $
    $Author: sarajkumar $
-->
