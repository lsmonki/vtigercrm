<?php
require_once('modules/Calendar/CalendarCommon.php');
require_once('modules/Calendar/addEventUI.php');
/**
 * Copyright 1999 - 2004 by Gero Kohnert
 */
global $calpath,$callink,$current_user,$adb;
$callink = "index.php?module=Calendar&action=";
include_once $calpath .'webelements.p3';
include_once $calpath .'permission.p3';
require_once('modules/Calendar/preference.pinc');
require_once('modules/Calendar/appointment.pinc');
global $mod_strings,$currentModule,$app_strings;

 echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'], true); 
echo "\n<BR>\n";
 $t=Date("Ymd");
 $html = getHeaderTab($t,'month');
 echo $html;
 
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

if (isset($_GET['f']) ) 
{
  $f = $_GET['f'];
}
if (isset($_GET['d']) ) 
{
  $d = $_GET['d'];
}
if (isset($_GET['m']) ) 
{
  $m = $_GET['m'];
}
if (isset($_GET['y']) ) 
{
  $y = $_GET['y'];
}
if (isset($_GET['n']) ) 
{
  $n = $_GET['n'];
}
if ( $d == -1 ) 
{
  $d = Date("d");
}
if ( $m == -1 ) 
{
  $m = Date("n");
}
if ( $y == -1 ) 
{
  $y = Date("Y");
}

echo "<style type=\"text/css\">@import url(\"". $theme_path ."/style.css\");</style>";
echo "<script language='JavaScript'>\n";
echo " function closeandaway (d, m, y) { \n";
echo "  var x = opener.document.appnew; \n";

echo "  x.". $f ."_d.selectedIndex = d-1; \n";
echo "  x.". $f ."_m.selectedIndex = m-1; \n";
echo "  x.". $f ."_y.selectedIndex = y; \n";

echo "  window.close(); \n";
echo " }\n";
echo " function noneandaway () { \n";
echo "  var x = opener.document.forms[0]; \n";
echo "  \n";
echo "  x.". $f ."_d.selectedIndex = 0; \n";
echo "  x.". $f ."_m.selectedIndex = 0; \n";
echo "  x.". $f ."_y.selectedIndex = 0; \n";
echo "  window.close(); \n";
echo " }\n";
echo "</script>\n";


$yoff =  Date("Y") + 10;
$nm = $m + 1;
$ny = $y;
	if ( $nm == 13 ) 
	{
  		$nm = 1;
  		$ny = $y + 1;
	}
	$lm = $m - 1;
	$ly = $y;
	if ( $lm == 0 ) 
	{
  		$lm = 12;
  		$ly = $y -1;
	}

	$m_name = $mod_strings['cal_month_long'][$m];
	$calendarheader = getCalendarHeader($lm,$nm,"month",$ly,$this->pref,$ny,$m_name,$y,$d,$f,$n);
	echo $calendarheader;
                echo "<tr><td>";
                echo "<!-- calendar list -->
                      <table border=\"0\" cellspacing=\"0\" cellpadding=\"10\" width=\"100%\" class=\"calDisplay\">
                      <tr><td align=center>";
		echo "<div class=\"calDiv\" >";
		echo "<table border=0 cellspacing=1 cellpadding=5 width=100% class=\"calDayHour\" style=\"background-color: #dadada\">";

	echo "<tr>\n";

for ( $i = $current_user->weekstart;$i<=6;$i++ ) 
{ 
  echo "<td width=12% class=\"lvtCol\" bgcolor=\"blue\" valign=top>". $mod_strings['LBL_DAY'.$i] ."</td>\n";
}

for ( $i = 0;$i<$current_user->weekstart;$i++ )                                                               {                                                                                                               echo "<td width=12% class=\"lvtCol\" bgcolor=\"blue\" valign=top>". $mod_strings['LBL_DAY'.$i] ."</td>\n";  }

echo "</tr></div>\n";
echo "<table border=0 cellspacing=1 cellpadding=5 width=100% class=\"calDayHour\" style=\"background-color: #dadada\">";

$ts = mktime(12,0,0,$m,1,$y);


$today=Date("Ymd",time());

/* Back to last weekstart before ts */
while ( Date("w",$ts) != $current_user->weekstart ) 
{
  $ts -= 86400; //  1 day has 86400 seconds
}

$go = 1;
$a = 0;
$w = 0;

while ( $go == 1 ) 
{
 $wd = Date("w",$ts);  // day of week (0-6)
 $xd = Date("j",$ts);  // day of month without leading zero
 $xxd = Date("d",$ts); // day of month with leading zero
 $xm = Date("n",$ts);  // month (1-12
 $xy = Date("Y",$ts);  // Year (2005)

// Overlapping days -starts by Fredy

	if ( $wd == $l->user->weekstart ) 
	{
	     	if ($xm > $m or $xy > $y) 
		{
         	//	break; //commented as january is not coming for all the even years
     		}
   # new week
   		echo "<tr>\n";
   		$w0 =  (( 1 + Date("w",mktime(12,0,0,1,1, Date("Y",$ts) ) )) % 7) > 3;
   		$wn = sprintf("%02d", Round( (Date("z",$ts)+7 ) / 7) );
 	}

// check for overlapping days
 		$month_overlap = (($xm == $nm) or ($xm == $lm));

 		$col = "";

// Overlapping days -ends 

		$col = "";

		if (!$month_overlap) 
		{
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
		}
		else 
		{ // day overlaps by Fredy
		     	if ( $today == Date("Ymd",$ts)) 
			{
       				$col = "ol-today";
     			} 
			else if ($wd == 0) 
			{
       				$col = "ol-holiday";
	     		} 
			else if ($wd == 6) 
			{
       				$col = "ol-freeday";
     			} 
			else if ($xm != $m ) 
			{
       				$col = "ol-otherday";
    			} 
			else 
			{
       				$col = "ol-appday";
     			}
 		}

 
 echo "<td onClick=\"gshow('addEvent')\" href=\"javascript:void(0)\" onMouseOver=\"this.className='cellNormalHover'\" onMouseOut=\"this.className='cellNormal'\" bgcolor=\"white\" style=\"height:40px\" width=12% valign=top>\n";
 		if (($xm == $m ) || $month_overlap)
 		{
		     // added by raj
		     /* Select appointments for this day */
		     	$from =  new DateTime();
		     	$to   =  new DateTime();
		     	$from->setDateTimeTS($ts - 12 * 3600);
		     	$to->setDateTimeTS($ts - 12 * 3600);
     
		     	$pref->callist = array();
		     	$app = new appointment();
		     	$app->readCal($pref,$from,$to);

		     	$dd = new DateTime();
       		     	$dd->setDateTimeTS($ts);
       			$d = $dd->getDate();
       			$tref = Date("Ymd",$ts);

			//Display Date with link, move here from above to get tref date format

			if ($col=="today")
			{
				echo $xd;
				echo "<div valign=bottom align=right onclick=\"gshow('addEvent')\"  onMouseOut=\"ghide('12pm')\"  width=10%>";
				echo "+";
				echo "</div>";
			}
			else
			{
				echo $xd;
				echo "<div valign=bottom align=right onclick=\"gshow('addEvent')\"  onMouseOut=\"ghide('12pm')\"  width=10%>";
				echo "+";
				echo "</div>";
			}

       			$next = NextDay($ts);
       			# Check for workday
       			if ( ! $dd->isWorkDay($pref) ) 
			{
         			$ts = $next;
         			$day++;
         			continue;
       			}
       			$dinfo = GetDaysInfo($ts);


       			$hastable = 0;
       			$a = 0;

       			foreach ($pref->callist as $idx => $x) 
			{
			         /* the correct day */
			        if ( ! $pref->callist[$idx]->inside($dd) ) 
				{
           				continue;
         			}
         			if ( $hastable == 0 ) 
				{
           				echo "\n <table class=\"event\" cellspacing=\"2\" cellpadding=\"1\" border=\"0\" width=\"100%\" align=\"center\" style=\"margin-top:2\">\n";
           				$hastable = 1;
         			} 
        			else 
				{
	   				echo "  <tr><td height=\"2\" colspan=\"4\" class=\"eventSep\"><img src=\"". $image_path ."blank.gif\"></td></tr>\n";
         			}
				

                                $color = "";
	  	                $username=$pref->callist[$idx]->creator;
                                if ($username!=""){    
                                $query="SELECT cal_color FROM users where user_name = '$username'"; 
                                 
                                $result=$adb->query($query); 
                                if($adb->getRowCount($result)!=0)
				{
                                	$res = $adb->fetchByAssoc($result, -1, false); 
                                       	$usercolor = $res['cal_color']; 
                                       	$color="style=\"background: ".$usercolor.";\"";    
                                 }
                         } 
              		 echo "\n<table class=\"event\" $color cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\">\n";

         			$pref->callist[$idx]->formatted();
         			$a++;
       			}

       			if ( $hastable == 1 ) 
			{
         			echo "</table>\n";
       			}

 		}
 		else
 		{
 			echo "&nbsp;";
 		}
 		echo "</td>\n";

 		if ( $wd == ($l->user->weekstart+6)%7   ) 
		{
   			# end week
   			echo "</tr>\n";
   			if ( ($xm > $m) || ($xy > $y)  ) 
			{
     				break;
   			}
 		}
 		$a++;
 		$w++;

        //changed for fixing the Daylight Saving Time issue as per suggestion by Bushwack post id
        $ts = strtotime('+1 day', $ts);
}
if ( $n == 1 ) 
{
  	echo "<tr><th colspan=\"8\"><a class=\"nodeco\" href=\"JavaScript:noneandaway()\">NONE</a></th></tr>";
}

echo "</table>\n";
echo "</td></tr></table>\n";
echo "</td></tr>";
echo "<tr>
<td>
<table border=0 cellspacing=0 cellpadding=0 width=100% class=\"calBottomBg\">
<tr>

<td><img src=\"".$image_path."calBottomLeft.gif\"></td>
<td width=100%><img src=\"".$image_path."calBottomBg.gif\"></td>
<td align=right><img src=\"".$image_path."calBottomRight.gif\"></td>
</tr>
</table>
</td>
</tr>
</table>
<!-- content cache -->

</td>
</tr>
</table>

</td>
</tr>
</table>




</div>
<!-- PUBLIC CONTENTS STOPS-->
</td>

<td align=right valign=top><img src=\"".$image_path."showPanelTopRight.gif\"></td>
</tr>
</table>";
# selection of none allowed

echo "<br>\n";

?>
<!--
    CVS Info:  $Id: calendar_month.php,v 1.4 2005/02/28 13:27:41 saraj Exp $
    $Author: saraj $
-->
