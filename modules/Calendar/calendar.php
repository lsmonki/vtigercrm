<?php
/**
 * Copyright 1999 - 2004 by Gero Kohnert
 *
 * @modulegroup appointment
 * @module calendar
 */
 global $calpath;
 $calpath = 'modules/Calendar/';
 $callink = '../../index.php?module=Calendar&action=';

 global $theme;
 $theme_path="themes/".$theme."/";
 $image_path=$theme_path."images/";

 include_once $calpath .'webelements.p3';
 include_once $calpath .'permission.p3';
 if ( $tutos[tasksincalendar] == 1 ) {
   include_once $calpath .'task.pinc';
 }
 include_once $calpath .'appointment.pinc';
 include_once $calpath .'product.pinc';

 /* Check if user is allowed to use it */
 check_user();
 loadmodules("appointment","show");
 loadlayout();
 /**
  * display a calendar for some weeks
  */
 class calendar extends layout {
   /**
    * Display One Weeks appointments (including s) starting with Monday or Sunday
    * t format YYYYMMDD
    *
    */
   Function Cal_Week ($t) {
     global $lang, $tutos, $callink,$image_path;

     $ts = mktime(12,0,0,substr($t,4,2),substr($t,6,2),substr($t,0,4));
     /* Back to last weekstart day before ts */
#    echo $this->user->weekstart ." ". $ts." ".  strftime($lang['DateTimeStr'],$ts) ." ".  Date("w",$ts)."<br />";
     while ( Date("w",$ts) != $this->user->weekstart ) {
       $ts -= 86400;
     }
#    echo $this->user->weekstart ." ". $ts." ".  strftime($lang['DateTimeStr'],$ts) ." ".  Date("w",$ts)."<br />";

     $w0 =  (( 1 + Date("w",mktime(12,0,0,1,1, Date("Y",$ts) ) )) % 7) > 3;
     $wn = sprintf("%02d", Round( (Date("z",$ts)+7 ) / 7) );
     $yy = Date("y",$ts);
     echo " <td class=\"week\" width=\"5%\">". menulink($callink ."calendar_week&t=".Date("Ymd",$ts)."&amp;team=". $this->team, $wn ."/". $yy, $lang['week'] ." ". $wn ."/". $yy ) ."</td>\n";

     /* Select appointments for this day */
     $from =  new DateTime();
     $to   =  new DateTime();
     $from->setDateTimeTS($ts - 12 * 3600);
     $to->setDateTimeTS($ts - 12 * 3600);
     $to->addDays(7);

     $this->user->callink = array();
	     appointment::readCal($this->user,$from,$to);
     if ( $tutos[tasksincalendar] == 1 ) {
       task::readCal($this->user,$from,$to);
     }
     foreach($tutos[activemodules] as $i => $f) {
       $x = @new $tutos[modules][$f][name]($this->dbconn);
       $x->readCal($this->user,$from,$to);
     }

     $dd = new DateTime();
     $day = 0;
     while ( $day < 7 ) {
       # $d = strftime($lang['DateFormatStr'],$ts);
       $dd->setDateTimeTS($ts);
       $d = $dd->getDate();
       $tref = Date("Ymd",$ts);
       $next = NextDay($ts);
       # Check for workday
       if ( ! $dd->isWorkDay($this->user) ) {
         $ts = $next;
         $day++;
         continue;
       }
       $dinfo = GetDaysInfo($ts);

       echo "<td class=\"". $dinfo[color] ."\" width=\"10%\">\n";

       if ( isset($dinfo[Desc]) ) {
         if ( $this->user->feature_ok(usecalendar,PERM_NEW) ) {
           echo " " . makelink($callink ."app_new&t=". $tref,$d,$lang['NewAppointInfo'],$dinfo[popinfo]) ."\n";
         } else {
           echo " " . $d ."\n";
         }
         echo "<br /><span class=\"dinfo\">". $dinfo[Desc] ."</span>\n";
       } else {
         if ( $this->user->feature_ok(usecalendar,PERM_NEW) ) {
           echo " " . makelink($callink ."app_new&t=". $tref,$d,$lang['NewAppointInfo']) ."\n";
         } else {
           echo " " . $d ."\n";
         }
       }
       $hastable = 0;
       $a = 0;

       foreach ($this->user->callist as $idx => $x) {
         /* the correct day */
         if ( ! $this->user->callist[$idx]->inside($dd) ) {
           continue;
         }
         if (!cal_check_against_list($this->user->callist[$idx],$this->uids)) {
           continue;
         }

         if ( $hastable == 0 ) {
           echo "\n <table class=\"formatted\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\">\n";
           $hastable = 1;
         } else {
           echo "  <tr><td class=\"". $dinfo[color] ."\" colspan=\"3\"><img src=\"". $image_path ."black.png\" width=\"100%\" height=\"1\" alt=\"--------\"></td></tr>\n";
         }
#echo "1 ".$this->user->weekstart ."<br />";
         $this->user->callist[$idx]->formatted();
#echo "2 ".$this->user->weekstart ."<br />";
         $a++;
       }

       if ( $hastable == 1 ) {
         echo "</table>\n";
       }
       while ( $a < 2 ) {
         echo " <br />\n";
         $a++;
       }

       echo "</td>\n";

       /* Next Day */
       $day++;
       $ts = $next;
     }

     return(Date("Ymd",$ts));
   }
   /**
    * the data display part
    */
   Function info() {
     global $tutos , $lang, $callink;

     $cols = 1;

     $weeks = $this->user->get_prefweeks();
     for ( $i = 0;$i<=6;$i++ ) {
       if ($this->user->isWorkDay($i)) {
         $cols++;
       }
     }

     $ts = mktime(0,0,0,substr($this->t,4,2),substr($this->t,6,2),substr($this->t,0,4));
     $last_week = Date("Ymd",$ts -  7 * 86400);
     $next_week = Date("Ymd",$ts +  7 * 86400);
     $last_month = Date("Ymd",$ts -  28 * 86400);
     $next_month = Date("Ymd",$ts +  28 * 86400);

     # Display for current time
     $this->addHidden("t",$this->t);
     if ( $this->id > 0 ) {
       $this->addHidden("id",$this->id);
     }
     echo "<form action=\"calendar.php\" method=\"get\">\n";
     echo "<table class=\"single\" cellpadding=\"3\" cellspacing=\"0\" width=\"100%\" border=\"0\">\n";
     echo "<tr>\n";
     //echo " <th class=\"navigate\" align=\"left\" colspan=\"3\" rowspan=\"2\" nowrap=\"nowrap\">\n";
     //echo "&nbsp;". $lang['forphrase'] ."\n";
     //cal_options($this->team,$this->teamname);
     //Commented for integration	
     //echo " </th>\n";
     //Added for integration	
     echo " <th class=\"navigate\" colspan=\"2\" rowspan=\"2\" nowrap=\"nowrap\"></th>\n";
     //
     echo " <td nowrap=\"nowrap\" class=\"navigate\" colspan=\"".( $cols - 3) ."\" align=\"center\">\n";
     echo menulink($callink ."index&t=". $last_week,$this->theme->getImage(left,'list').$lang['lastweek'],$lang['lastweek']);
     echo "&nbsp;&nbsp;&nbsp;";
     echo menulink($callink ."index&t=". $this->t ,$lang['reload']);
     echo "&nbsp;&nbsp;&nbsp;";
     echo menulink($callink ."index&t=". $next_week,$lang['nextweek'].$this->theme->getImage(right,'list'),$lang['nextweek']);
     echo "</td>\n"; 
     //Added for Ingtegration	
     echo " <th class=\"navigate\" rowspan=\"2\" nowrap=\"nowrap\"></th>\n";
     //
     echo " </tr>\n";

     echo " <tr>\n";
     echo "  <td nowrap=\"nowrap\" class=\"navigate\" colspan=\"". ( $cols - 3)."\" align=\"center\">\n";
     echo menulink($callink ."index&t=". $last_month,$this->theme->getImage(left,'list').$this->theme->getImage(left,'list').$lang['minus4weeks'],$lang['minus4weeks']);
     echo "&nbsp;&nbsp;&nbsp;";
     if ( $this->user->feature_ok(usecalendar,PERM_NEW) ) {
       echo menulink($callink ."app_new&t=".$this->t,$lang['NewAppoint'],$lang['NewAppointInfo']);
     } else {
       echo "&nbsp;";
     }
     echo "&nbsp;&nbsp;&nbsp;";
     echo menulink($callink ."index&t=". $next_month,$lang['plus4weeks'].$this->theme->getImage(right,'list').$this->theme->getImage(right,'list'),$lang['plus4weeks']);
     echo "  </td>\n";
     echo " </tr>\n";

     echo " <tr>\n";
     echo "  <th>". $lang['week'] ."</th>\n";
     for ( $i = $this->user->weekstart;$i<=6;$i++ ) {
       if ($this->user->isWorkDay($i)) {
         echo "  <th>". $lang['Day'.$i] ."</th>\n";
       }
     } 
     for ( $i = 0;$i<$this->user->weekstart;$i++ ) {
       if ($this->user->isWorkDay($i)) {
         echo "  <th>". $lang['Day'.$i] ."</th>\n";
       }
     }
 

     $t2 = $this->t;
     for ($i = 0; $i < $weeks; $i++) {
       echo " </tr><tr>\n";
#      echo $this->user->weekstart ." ".$t2."<br />";
       $t2 = $this->cal_Week($t2);
#      echo $this->user->weekstart ." ".$t2."<br />";
     }

     echo " </tr>\n";
     echo "</table>\n";
     hiddenFormElements();
     echo $this->getHidden();
     echo "</form>\n";
   }
   /**
    *
    */
   Function navigate() {
   }
   /**
    *
    */
   Function prepare() {
     global $lang,$msg;

     $this->name = $lang['Calendar'];

     if ( ! $this->user->feature_ok(usecalendar,PERM_SEE) ) {
       $msg .= sprintf($lang['Err0022'],"'". $this->name ."'");
       $this->stop = true;
     }

     $this->teamname = "";
     $this->t = Date("Ymd");
     $this->id = -1;

     if ( isset($_GET['t']) ) {
       $this->t = $_GET['t'];
     }
     /* Show a calendar containing Appointment id */
     if ( isset($_GET['id']) ) {
       $this->id = $_GET['id'];
       $query = "SELECT id,a_start FROM ". $this->dbconn->prefix ."calendar where id =". $this->id;
       check_dbacl( $query, $this->user->id);
       $result = $this->dbconn->Exec($query);
       if ( 1 != $result->numrows()) {
         $msg .= sprintf($lang['Err0040'],$lang['Appointment']) ;
         $this->id = -1;
       } else {
         $d = $result->getDateTime(0, "a_start");
         $this->t = $d->getYYYYMMDD();
       }
       $result->free();
     }

     $this->uids = cal_parse_options($this->user,$this->teamname);
     $this->team = $this->user->get_prefteam();

     # menu
     $m = appointment::getSelectLink($this->user);
     $m[category][] = "obj";
     $this->addmenu($m);
     $m = appointment::getAddLink($this->user,$this->user);
     $this->addMenu($m);
   }
 }

 $l = new calendar($current_user);
 $l->display();
 $dbconn->Close();
?>
<!--
    CVS Info:  $Id$
    $Author$
-->
