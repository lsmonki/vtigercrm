<?php
/**
 * Copyright 2002-2004 by Gero Kohnert
 *
 * a calendar for a single day
 *
 * @modulegroup appointment
 * @module calendar_day
 */
 
 global $calpath,$callink;
 $calpath = 'modules/Calendar/';
 $callink = 'index.php?module=Calendar&action=';
 
 global $theme;
 $theme_path="themes/".$theme."/";
 $image_path=$theme_path."images/";
 require_once ($theme_path."layout_utils.php");
 global $mod_strings;

 echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_APPOINTMENT'], true); 
echo "\n<BR>\n";

 include_once $calpath .'webelements.p3';
 include_once $calpath .'permission.p3';
 include_once $calpath .'task.pinc';
 include_once $calpath .'appointment.pinc';
 include_once $calpath .'product.pinc';

 /* Check if user is allowed to use it */
 check_user();
 loadmodules("appointment","show");
 loadlayout();

 /**
  * display a calendar for one single day
  */
 class calendar_day extends layout {
   /**
    * the data display part
    */
   Function info() {
     global $lang,$tutos,$callink,$calpath,$image_path;

     $ts = mktime(12,0,0,substr($this->t,4,2),substr($this->t,6,2),substr($this->t,0,4));
     $from = new DateTime();
     $last_day = new DateTime();
     $next_day = new DateTime();

     $from->setDateTimeTS($ts);

     $last_day->setDateTimeTS($ts);
     $next_day->setDateTimeTS($ts);

     $next_day->addDays(1);
     $last_day->addDays(-1);

     $wn = sprintf("%02d", Round( (Date("z",$ts)+7 ) / 7) );
     $yy = Date("y",$ts);

     $this->user->callist = array();
     appointment::readCal($this->user,$from,$next_day);
#    echo strftime($lang['DateFormatTitle'],$from->ts)." ".$from->ts ."<br />";
     task::readCal($this->user,$from,$next_day);


     foreach($tutos[activemodules] as $i => $f) {
       $x = &new $tutos[modules][$f][name]($this->dbconn);
       $x->readCal($this->user,$from,$next_day);
     }


     for ($i = -1 ; $i < 24 ; $i++ ) {
       $table[$i] = array();
     }
     foreach ($this->user->callist as $idx => $xx) {
       if ( ! $this->user->callist[$idx]->inside($from)) {
         continue;
       }
       if (!cal_check_against_list($this->user->callist[$idx],$this->uids)) {
         continue;
       }
       if ( $this->user->callist[$idx]->gettype() == "note" ) {
         $table[-1][] = &$this->user->callist[$idx];
         $rowspan[-1][] = 1;
         continue;
       }
       if ( $this->user->callist[$idx]->gettype() == "watchlist" ) {
         $table[-1][] = &$this->user->callist[$idx];
         $rowspan[-1][] = 1;
         continue;
       }
       if ( $this->user->callist[$idx]->gettype() == "task" ) {
         $table[-1][] = &$this->user->callist[$idx];
         $rowspan[-1][] = 1;
         continue;
       }
       if ( $this->user->callist[$idx]->gettype() == "reminder" ) {
         $table[-1][] = &$this->user->callist[$idx];
         $rowspan[-1][] = 1;
         continue;
       }
       if ( $this->user->callist[$idx]->t_ignore == 1) {
         $table[-1][] = &$this->user->callist[$idx];
         $rowspan[-1][] = 1;
         continue;
       }
       if ( ($this->user->callist[$idx]->s_out == 1) && ($this->user->callist[$idx]->e_out == 1) ) {
         $table[-1][] = &$this->user->callist[$idx];
         $rowspan[-1][] = 1;
         continue;
       }
       $x1 = Date("G",$this->user->callist[$idx]->start->getTimeStamp());
       $x2 = Date("G",$this->user->callist[$idx]->end->getTimeStamp());

       if ( $this->user->callist[$idx]->s_out == 1 ) {
         $x1 = 0;
       }
       if ( $this->user->callist[$idx]->e_out == 1 ) {
         $x2 = 23;
       }
       # find a free position
       $pos = -1;
       $found = false;
       while ( $found == false ) {
         $found = true;
         $pos ++;
         for ( $i = $x1; $i <= $x2 ; $i++ ) {
           if (isset($table[$i][$pos]) ) {
             $found = false;
             continue;
           }
         }
       }
       for ( $i = $x1; $i <= $x2 ; $i++ ) {
         if ( $i == $x1 ) {
           $table[$i][$pos] = &$this->user->callist[$idx];
           $rowspan[$i][$pos] = ($x2 - $x1 +1);
         } else {
           $table[$i][$pos] = -1;
         }
       }
     }
     $maxcol = 1;
     for ($i = -1 ; $i < 24 ; $i++ ) {
       $maxcol = max($maxcol,count($table[$i]));
     }

     echo "<form action=\"". $callink ."calendar_day\" method=\"get\">\n";
     echo "<table class=\"navigate\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"90%\">\n";
     //echo " <tr>\n";
     //echo "  <th align=\"left\" nowrap=\"nowrap\" colspan=\"". ($maxcol +1) ."\">&nbsp;". $lang['forphrase'] ."\n";
     //cal_options($this->team,$this->teamname);
     //echo "  </th>\n";
     //echo " </tr>\n";
     echo " <tr>\n";
     echo " <td class=\"viewhead\" nowrap=\"nowrap\" width=\"100%\" align=\"center\">";
     echo menulink($callink ."calendar_day&t=".$last_day->getYYYYMMDD(),$this->theme->getImage(left,'list'),$last_day->getDate());
     echo "&nbsp;". strftime($lang['DateFormatTitle'],$from->ts) ."&nbsp;(". $lang['week']." ". menulink($callink ."calendar_week&t=".Date("Ymd",$from->ts), $wn ."/". $yy, $wn ."/". $yy ) .")&nbsp;";
     echo menulink($callink ."calendar_day&t=".$next_day->getYYYYMMDD(),$this->theme->getImage(right,'list'),$next_day->getDate());
     echo "</td></tr>\n";
	 echo "</table>\n";
	 echo "<br><table class=\"outer\" border=\"0\" cellpadding=\"1\" cellspacing=\"1\" width=\"90%\"><tr><td class=\"inner\">\n";
	 echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" width=\"100%\">\n";
     for ($i = -1 ; $i <24 ; $i++ ) {
       echo " <tr>\n";

       echo " <th class=\"viewhead\" width=\"10%\" align=\"right\" valign=\"top\">\n";
       if ( $i == -1 ) {
         echo  menulink($callink . "app_new&t=".$this->t, "NOTIME",$lang['NewAppointInfo']);
       } else {
         echo  menulink($callink . "app_new&start=". $this->t.sprintf("%02d",$i)."00&amp;end=".$this->t.sprintf("%02d",$i)."59" ,sprintf("%02d", $i).":00",$lang['NewAppointInfo']);
       }
       echo "&nbsp;</th>\n";

       for ($c = 0 ; $c < $maxcol ; $c++ ) {
         if ( isset ( $table[$i][$c] ) ) {
           if ( is_object ( $table[$i][$c] ) ) {
             echo " <td class=\"line". (1+($i % 2)) ."\" valign=\"top\" rowspan=\"". $rowspan[$i][$c]."\">";
             //echo "<img height=\"1\" width=\"100%\" src=\"". $image_path ."black.png\" alt=\"--------\"/>";
             echo "<table border=\"0\" cellpadding=\"1\" cellspacing=\"0\">\n";
             echo $table[$i][$c]->formatted();
             echo " </table></td>\n";
           } else if ( $table[$i][$c] = -1 ) {
             # SKIP occupied by rowspan
           }
         } else {
           echo "<td class=\"line". (1+($i % 2)) ."\" valign=\"top\">";
		   //echo "<img height=\"1\" width=\"100%\" src=\"". $image_path ."black.png\" alt=\"--------\" />";
		   echo "</td>\n";
         }
       }
       echo " </tr>\n";
     }
	 echo " </table>\n";
	 echo "</td></tr></table>\n";
     echo $this->DataTableEnd();
     hiddenFormElements();
     echo $this->getHidden();
     echo "</form>\n";
   }
   /**
    * navigate
    */
   Function navigate() {
   }
   /**
    * prepare
    */
   Function prepare() {
     global $tutos, $lang,$msg;

     $this->name = $lang['Calendar'];
     if ( ! $this->user->feature_ok(usecalendar,PERM_SEE) ) {
       $msg .= sprintf($lang['Err0022'],"'". $this->name ."'");
       $this->stop = true;
     }
     $this->teamname = "";
     $this->t = Date("Ymd");

     if ( isset ($_GET['t']) ) {
       $this->t = $_GET['t'];
     }
     $this->addHidden("t", $this->t);
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


# info($t,$this->user->get_prefteam(),$teamname,$uids);

 $l = new calendar_day($current_user);
 $l->display();
 $dbconn->Close();
?>
<!--
    CVS Info:  $Id$
    $Author$
-->
