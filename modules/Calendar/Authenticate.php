<?php
/*
 * Copyright 1999 - 2004 by Gero Kohnert
 *
 * based on some work/ideas of Michael Somers( Twent First Century Communication <msomers at tfcci.com>)
 */
 global $calpath;
 $calpath = 'modules/Calendar/';
 include_once $calpath .'webelements.p3';
 include_once $calpath .'permission.p3';
 include_once $calpath .'product.pinc';
 include_once $calpath .'appointment.pinc';
 include_once $calpath .'task.pinc';
 include_once $calpath .'timetrack.pinc';


 /* Check if user is allowed to use it */
 check_user();
 loadmodules('mytutos','show');
 loadlayout();
 
 /* Web stack start */
 web_StackStart('mytutos.php',$calpath .'mytutos.php',$current_user->getFullName(),sprintf($lang['PersonalPageFor'],$current_user->getFullname()));

 Function info_table_start() {
   return " <table border=\"0\" cellpadding=\"3\" cellspacing=\"1\" width=\"100%\">\n";
 }
 Function info_table_end() {
   return " </table>\n";
 }
 /* ---------------------------------------------------------------------------
  * Display calendar for one week
  */

 Function calendar_info(&$adr,$t) {
   global $lang, $tutos, $current_user;

   $ts = mktime(12,0,0,substr($t,4,2),substr($t,6,2),substr($t,0,4));

   /* Back to last Monday or Sunday before ts */
   while ( Date("w",$ts) != $adr->weekstart ) {
     $ts -= 86400;
   }

   $w0 =  (( 1 + Date("w",mktime(12,0,0,1,1, Date("Y",$ts) ) )) % 7) > 3;
   $wn = sprintf("%02d", Round( (Date("z",$ts)+7 ) / 7) );
   $yy = Date("y",$ts);

   $day = 0;
   $dd = new DateTime();

   $from =  new DateTime();
   $from->setDateTimeTS($ts - 12 * 3600);
   $to = $from;
   $to->addDays(7);

   $adr->callist = array();
   appointment::readCal($adr,$from,$to);
   task::readCal($adr,$from,$to);
   foreach($tutos[activemodules] as $i => $f) {
     $x = new $tutos[modules][$f][name]($adr->dbconn);
     $x->readCal($adr,$from,$to);
   }

   #echo info_table_start();
   #echo " <tr>\n";
   #echo "  <th>". $lang['week'] ."</th>\n";
   $wd = $ts;

   for ( $i = $adr->weekstart;$i<=6;$i++ ) {
     if ($adr->isWorkDay($i)) {
       $tref = Date("Ymd",$wd);
       #echo "  <th>". menulink("calendar_day.php?t=". $tref,$lang["Day$i"]) ."</th>\n";
     }
     $wd = NextDay($wd);
   } 

   for ( $i = 0;$i<$adr->weekstart;$i++ ) {
     if ($adr->isWorkDay($i)) {
       $tref = Date("Ymd",$wd);
       #echo "  <th>". menulink("calendar_day.php?t=". $tref,$lang["Day$i"]) ."</th>\n";
     }
     $wd = NextDay($wd);
   }
   #echo " </tr>\n";

   #echo " <tr>\n";
   #echo "  <td class=\"week\" width=\"5%\">". menulink("calendar_week.php?t=".Date("Ymd",$ts), $wn ."/". $yy,$lang['week'] ." ". $wn ."/". $yy ) ."</td>\n";

   while ( $day < 7 ) {
     # $d = strftime($lang['DateFormatStr'],$ts);
     $dd->setDateTimeTS($ts);
     $d = $dd->getDate();
     $tref = Date("Ymd",$ts);
     $dinfo = GetDaysInfo($ts);
     /* Select appointments for this day */
     $next = NextDay($ts);
     if ( ! $dd->isWorkDay($adr) ) {
       $ts = $next;
       $day++;
       continue;
     }
     #echo "<td class=\"". $dinfo[color] ."\" width=\"10%\">\n";

     if ( isset($dinfo[Desc]) ) {
       if ( $current_user->feature_ok(usecalendar,PERM_NEW) ) {
         #echo " " . makelink("app_new.php?t=". $tref,$d,$lang['NewAppointInfo'],$dinfo[popinfo]) ."\n";
       } else {
         #echo " " . $d ."\n";
       }
       #echo "<br /><span class=\"dinfo\">". $dinfo[Desc] ."</span>\n";
     } else {
       if ( $current_user->feature_ok(usecalendar,PERM_NEW) ) {
         #echo " " . makelink("app_new.php?t=". $tref,$d,$lang['NewAppointInfo']) ."\n";
       } else {
         #echo " " . $d ."\n";
       }
     }

     $hastable = 0;
     foreach ($adr->callist as $idx => $x) {
       /* the correct day */
       if ( ! $adr->callist[$idx]->inside($dd) ) {
         continue;
       }
       # Check if appointment is displayed
       if ( $adr->callist[$idx]->gettype() == "appointment" ) {
         $found = 0;
         @reset($adr->callist[$idx]->participant);
         while ( ($found == 0) && (list ($i,$f) = @each ($adr->callist[$idx]->participant)) ) {
           if ( $f->id == $adr->id ) {
             $found = 1;
           } else if ( array_key_exists($f->id,$adr->teamlist) ) {
             $found = 1;
           }
         }
         if ( $found == 0 ) {
           continue;
         }
       }
       // Do not show finished tasks
       if ( ($adr->callist[$idx]->gettype() == "task") && ($adr->callist[$idx]->state == 2) ) {
         continue;
       }
       if ( $hastable == 0 ) {
         #echo " <table class=\"formatted\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\">\n";
         $hastable = 1;
       } else {
         #echo "  <tr><td class=\"". $dinfo[color] ."\" colspan=\"3\"><img src=\"". $tutos['base'] ."/html/black.png\" width=\"100%\" height=\"1\" alt=\"--------\"></td></tr>\n";
       }
       // Show appointments or task
       $adr->callist[$idx]->formatted();
     }

     if ( $hastable == 1 ) {
       #echo " </table>\n";
     }

     #echo "</td>\n";
     $day++;
     $ts = $next;
   }
   #echo " </tr>\n";
   #echo info_table_end();
 }



 /**
  * display an overview of everything that's interessting for an user
  */
 class mytutos extends layout {
   /**
    * the data display part
    */
   Function info() {
     global $lang , $tutos;

     #echo $this->DataTableStart();
     #echo "<tr>\n";
     # Header
     #echo "<th colspan=\"4\">". sprintf($lang['PersonalPageFor'],menulink($this->adr->getUrl(),$this->adr->getFullName(),$this->adr->getFullName())) ."</th>\n";
     #echo "</tr>\n";

     # Display all the info blocks
     # Weeks calendar
     $ts = mktime(0,0,0,substr($this->t,4,2),substr($this->t,6,2),substr($this->t,0,4));
     $last_week = Date("Ymd",$ts -  7 * 86400);
     $next_week = Date("Ymd",$ts +  7 * 86400);
     if ( $this->user->feature_ok(usecalendar,PERM_SEE) ) {
       #echo "<tr>\n";
       #echo " <th colspan=\"4\">\n  ". menulink("mytutos.php?t=".$last_week,$this->theme->getImage(left,'list'),$lang['lastweek']) . "&nbsp;". $this->theme->getImage(appointment::getHtmlIcon(),'list') ." ". $lang['Calendar'] ."&nbsp;" . menulink("mytutos.php?t=".$next_week,$this->theme->getImage(right,'list') ,$lang['nextweek'])  ."\n</th>\n";
       #echo "</tr>\n";
       #echo "<tr>\n";
       #echo " <td colspan=\"4\" valign=\"top\">\n";
       calendar_info($this->adr,$this->t);
       #echo " </td>\n";
       #echo "</tr>\n";
     }

     $cnt = 0;

     # Projects
     $r = product::mytutos($this->adr);
     if ($r != "") {
       if ( ($cnt % 2) == 0 ) {
         #echo "<tr>\n";
       }
       #echo " <td width=\"50%\" colspan=\"2\" valign=\"top\">\n". $r ."</td>\n";
       if ( ($cnt % 2) != 0 ) {
         #echo "</tr>\n";
       }
       $cnt++;
     }

     foreach($tutos[activemodules] as $i => $f) {
       $x = new $tutos[modules][$f][name]($this->dbconn);
       $r = $x->mytutos($this->adr);
       if ($r == "") {
         continue;
       }
       if ( ($cnt % 2) == 0 ) {
         #echo "<tr>\n";
       }
       #echo " <td width=\"50%\" colspan=\"2\" valign=\"top\">\n". $r ."</td>\n";
       if ( ($cnt % 2) != 0 ) {
         #echo "</tr>\n";
       }
       $cnt++;
     }
     if ( ($cnt % 2) != 0 ) {
       #echo "</tr>\n";
     }

     #echo $this->DataTableEnd();
   }
   /**
    * naviagtion
    */
   Function navigate() {
     global $tutos, $lang ;

     #echo "<tr><td>\n";
     if ( $this->adr->mod_ok() ) {
       #echo  menulink("user_new.php?id=".$this->adr->id ,$lang['PersonalSettings'],sprintf($lang['PersonalSettingsI'],$this->adr->getFullName())) . "<br />\n";
     }

     if ( $this->user->feature_ok(usetaskmanagement,PERM_SEE) &&  $this->user->feature_ok(usecalendar,PERM_SEE) ) {
       #echo menulink("res_cal.php?id=".$this->adr->id,$lang['ResCal'],$lang['ResCal']) ."<br />\n";
     }

     if ( $this->user->feature_ok(usetimetrack,PERM_SEE) ) {
       #echo menulink("timetrack_overview.php?worker=".$this->adr->id,$lang['TimetrackBooked'],sprintf($lang['TimetrackBookedI'],$this->adr->getFullName())) ."<br />\n";
     }

     #echo "</td></tr>\n";
   }
   /**
    * prepare
    */
   Function prepare() {
     global $lang ;

     $this->name = $lang['PersonalPage'];
     $this->t = Date("Ymd");
     $this->adr = new tutos_user($this->dbconn);

     if ( isset($_GET['t']) ) {
       $this->t = $_GET['t'];
     }
     if ( isset($_GET['adr']) ) {
       $this->adr = $this->adr->read($_GET['adr'],$this->adr);
       $this->adr->layout = $this->user->layout;
     }
     if ( $this->adr->id == -1 ) {
       $this->adr = &$this->user;
     }
     if (! $this->adr->see_ok() ) {
       $this->adr = &$this->user;
     }

     $x = task::getaddlink($this->user,$this->adr);
     $this->addMenu($x);
     $x = timetrack::getaddlink($this->user,$this->adr);
     $this->addMenu($x);
     $x = timetrack::getSelectLink($this->user,$lang['TimetrackSearch']);
     $this->addMenu($x);

     if ( $this->user->feature_ok(usetaskmanagement,PERM_SEE) ) {
       $x = array( url => "task_overview.php?id=".$this->adr->id,
                   image =>  $this->theme->getImage(task::getHtmlIcon(),'menu'),
                   text => $lang['TaskOverview'],
                   info =>sprintf($lang['TaskOverviewInfo'],$this->adr->getFullName()),
                   category => array("overview","task")
                 );
       $this->addMenu($x);
     }

    if ( $this->user->feature_ok(usehistory,PERM_SEE) ) {
       $x = array( url => "history_show.php?adr_id=".$this->adr->id,
                   text => $lang['HistoryLink'],
                   info =>sprintf($lang['HistoryLinkI'],$this->adr->getFullName()),
                   category => array("overview","history")
                 );
       $this->addMenu($x);
    }



   }
 }

 $l = new mytutos($current_user);
 #$l->display();
 $dbconn->Close();
?>
