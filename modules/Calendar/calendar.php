<?php
/**
 * Copyright 1999 - 2004 by Gero Kohnert
 *
 * @modulegroup appointment
 * @module calendar
 */
 global $calpath;
 global $app_strings,$mod_strings;
 $calpath = 'modules/Calendar/';
 $callink = '../../index.php?module=Calendar&action=';

 global $theme;
 $theme_path="themes/".$theme."/";
 $image_path=$theme_path."images/";

 include_once $calpath .'webelements.p3';
 include_once $calpath .'permission.p3';
 include_once $calpath .'preference.pinc';
 require_once('include/database/PearDatabase.php');
 require_once('modules/Calendar/UserCalendar.php');
 require_once ($theme_path."layout_utils.php");
 if ( $tutos[tasksincalendar] == 1 ) {
   #include_once $calpath .'task.pinc';
 }
 include_once $calpath .'appointment.pinc';
 #include_once $calpath .'product.pinc';

 /* Check if user is allowed to use it */
 #check_user();
 loadmodules("appointment","show");
 loadlayout();
 echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_TITLE'], true); 
 echo "\n<BR>\n";
 /**
  * display a calendar for some weeks
  */
 class calendar extends layout {
	
	Function calendar()
	{
		$this->db = new PearDatabase();
 		$this->pref = new preference();
		$calobj = new UserCalendar();
		$this->tablename = $calobj->table_name;
	}
   /**
    * Display One Weeks appointments (including s) starting with Monday or Sunday
    * t format YYYYMMDD
    *
    */
   Function Cal_Week ($t) {
     global $mod_strings, $tutos, $callink,$image_path,$current_user;

     $ts = mktime(12,0,0,substr($t,4,2),substr($t,6,2),substr($t,0,4));
     /* Back to last weekstart day before ts */
#    echo $this->user->weekstart ." ". $ts." ".  strftime($lang['DateTimeStr'],$ts) ." ".  Date("w",$ts)."<br />";
     while ( Date("w",$ts) != $this->pref->weekstart ) {
       $ts -= 86400;
     }
#    echo $this->user->weekstart ." ". $ts." ".  strftime($lang['DateTimeStr'],$ts) ." ".  Date("w",$ts)."<br />";

     $w0 =  (( 1 + Date("w",mktime(12,0,0,1,1, Date("Y",$ts) ) )) % 7) > 3;
     $wn = sprintf("%02d", Round( (Date("z",$ts)+7 ) / 7) );
     $yy = Date("y",$ts);
     echo " <td class=\"week\" width=\"5%\">". $this->pref->menulink($callink ."calendar_week&t=".Date("Ymd",$ts)."&amp;team=". $this->pref->team, $wn ."/". $yy, $mod_strings['LBL_WEEK'] ." ". $wn ."/". $yy ) ."</td>\n";

     /* Select appointments for this day */
     $from =  new DateTime();
     $to   =  new DateTime();
     $from->setDateTimeTS($ts - 12 * 3600);
     $to->setDateTimeTS($ts - 12 * 3600);
     $to->addDays(7);
     
     $this->pref->callist = array();
	     appointment::readCal($this->pref,$from,$to);
	//print_r($this->pref->callist);
	//print_r($this->user->callist);
     /*if ( $tutos[tasksincalendar] == 1 ) {
       task::readCal($this->pref,$from,$to);
     }
     foreach($tutos[activemodules] as $i => $f) {
       $x = @new $tutos[modules][$f][name]($this->dbconn);
       $x->readCal($this->user,$from,$to);
     }*/

     $dd = new DateTime();
     $day = 0;
     while ( $day < 7 ) {
       # $d = strftime($lang['DateFormatStr'],$ts);
       $dd->setDateTimeTS($ts);
       $d = $dd->getDate();
       $tref = Date("Ymd",$ts);
       $next = NextDay($ts);
       # Check for workday
       if ( ! $dd->isWorkDay($this->pref) ) {
         $ts = $next;
         $day++;
         continue;
       }
	//print("GS --> L1");
       $dinfo = GetDaysInfo($ts);
	//print("GS --> L2");

       echo "<td class=\"". $dinfo[color] ."\" width=\"10%\">\n";

       if ( isset($dinfo[Desc]) ) {
         #if ( $this->user->feature_ok(usecalendar,PERM_NEW) ) {
          echo " " . $this->pref->makelink($callink ."app_new&t=". $tref,$d,$mod_strings['LBL_NEW_APPNT_INFO'],$dinfo[popinfo]) ."\n";
         #} else {
         #  echo " " . $d ."\n";
         #}
         echo "<br /><span class=\"dinfo\">". $dinfo[Desc] ."</span>\n";
       } else {
         #if ( $this->user->feature_ok(usecalendar,PERM_NEW) ) {
	   //Comented - added by raj
           #echo " " . makelink($callink ."app_new&t=". $tref,$d,$mod_strings['LBL_NEW_APPNT_INFO']) ."\n";
           echo " " . makelink($callink ."calendar_day&t=". $tref,$d,$mod_strings['LBL_VIEW_DAY_APPNT_INFO']) ."\n";
		
         #} else {
         #  echo " " . $d ."\n";
         #}
       }
       $hastable = 0;
       $a = 0;

	//print("GS --> L3");
	//print_r($this->user->callist);
       foreach ($this->pref->callist as $idx => $x) {
         /* the correct day */
         if ( ! $this->pref->callist[$idx]->inside($dd) ) {
  		//print("GS --> not inside");
           continue;
         }
	 //print("GS --> inside");
         /*if (!cal_check_against_list($this->pref->callist[$idx],$this->uids)) {
	     print("GS --> not in list");
           continue;
         }
	 print("GS --> list");*/

         if ( $hastable == 0 ) {
           echo "\n <table class=\"formatted\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\">\n";
           $hastable = 1;
         } 
        else {
           echo "  <tr><td class=\"". $dinfo[color] ."\" colspan=\"3\"><img src=\"". $image_path ."black.png\" width=\"100%\" height=\"1\" alt=\"--------\"></td></tr>\n";
         }
#echo "1 ".$this->user->weekstart ."<br />";
         $this->pref->callist[$idx]->formatted();
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
     global $tutos , $lang, $callink,$mod_strings;

     $cols = 1;

     $weeks = $this->pref->get_prefweeks();
     for ( $i = 0;$i<=6;$i++ ) {
       if ($this->pref->isWorkDay($i)) {
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
//	 echo "<table class=\"leftFormHeader\" cellpadding=\"0\" cellspacing=\"1\" width=\"100%\" border=\"0\">\n";
//	 echo "<tr><td>\n";
     echo "<table class=\"navigate\" cellpadding=\"3\" cellspacing=\"0\" width=\"100%\" border=\"0\">\n";
     echo "<tr>\n";
     //echo " <th class=\"navigate\" align=\"left\" colspan=\"3\" rowspan=\"2\" nowrap=\"nowrap\">\n";
     //echo "&nbsp;". $lang['forphrase'] ."\n";
     //cal_options($this->team,$this->teamname);
     //Commented for integration	
     //echo " </th>\n";
     //Added for integration	
     //echo " <th class=\"navigate\" colspan=\"2\" rowspan=\"2\" nowrap=\"nowrap\"></th>\n";
     //
	 echo "  <td nowrap=\"nowrap\" width=\"150\">\n";
     #if ( $this->user->feature_ok(usecalendar,PERM_NEW) ) {
       echo $this->pref->menulink($callink ."app_new&t=".$this->t,$this->pref->getImage(appointment,'list').$mod_strings['LBL_NEW_APPNT'],$mod_strings['LBL_NEW_APPNT_INFO']);
     #} else {
     #  echo "&nbsp;";
     #}
     echo "  </td>\n";
     echo " <td nowrap=\"nowrap\" align=\"center\">\n";
	 echo $this->pref->menulink($callink ."calendar&t=". $last_month,$this->pref->getImage(first,'list').$lang[''],$mod_strings['LBL_4WEEKS_BACK']);
	 echo "&nbsp;&nbsp;";
     echo $this->pref->menulink($callink ."calendar&t=". $last_week,$this->pref->getImage(left,'list').$lang[''],$mod_strings['LBL_LAST_WEEK']);
	 echo "&nbsp;&nbsp;";
	 echo $this->pref->menulink($callink ."calendar&t=". $next_week,$lang[''].$this->pref->getImage(right,'list'),$mod_strings['LBL_NEXT_WEEK']);     
     echo "&nbsp;&nbsp;";
     echo $this->pref->menulink($callink ."calendar&t=". $next_month,$lang[''].$this->pref->getImage(last,'list'),$mod_strings['LBL_4WEEKS_PLUS']);
     echo "</td>\n"; 
	 echo "<td nowrap=\"nowrap\" width=\"150\" align=\"right\">\n";
 	 echo $this->pref->menulink($callink ."calendar&t=". $this->t ,$this->pref->getImage(reload,'list').$mod_strings['LBL_RELOAD'],$mod_strings['LBL_RELOAD']);
	 echo "&nbsp;</td>\n";
     //Added for Ingtegration	
     //echo " <th class=\"navigate\" rowspan=\"2\" nowrap=\"nowrap\"></th>\n";
     //
     //echo " </tr>\n";

	 //echo " <tr>\n";
     echo " </tr></table>\n";
// 	 echo "</td></tr></table>\n";
     echo " <br>\n";
	 echo " <table class=\"outer\" cellpadding=\"3\" cellspacing=\"1\" width=\"100%\" border=\"0\">\n";
     echo " <tr>\n";
     echo "  <th class=\"viewhead\">". $mod_strings['LBL_WEEK'] ."</th>\n";
     for ( $i = $this->pref->weekstart;$i<=6;$i++ ) {
       if ($this->pref->isWorkDay($i)) {
         echo "  <th class=\"viewhead\">". $mod_strings['LBL_DAY'.$i] ."</th>\n";
       }
     } 
     for ( $i = 0;$i<$this->pref->weekstart;$i++ ) {
       if ($this->pref->isWorkDay($i)) {
         echo "  <th class=\"viewhead\">". $mod_strings['LBL_DAY'.$i] ."</th>\n";
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
     global $lang,$msg,$db,$mod_strings;

     $this->name = $mod_strings['LBL_MODULE_NAME'];

     #if ( ! $this->user->feature_ok(usecalendar,PERM_SEE) ) {
     #  $msg .= sprintf($lang['Err0022'],"'". $this->name ."'");
     #  $this->stop = true;
     #}

     $this->teamname = "";
     $this->t = Date("Ymd");
     $this->id = -1;

     if ( isset($_GET['t']) ) {
       $this->t = $_GET['t'];
     }
     /* Show a calendar containing Appointment id */
     if ( isset($_GET['id']) ) {
       $this->id = $_GET['id'];
       $query = "SELECT id,a_start FROM calendar where id =". $this->id;
       check_dbacl( $query, $this->user->id);
       $result = $this->db->query($query);
       if ( 1 != $this->db->getRowCount($result)) {
         $msg .= sprintf($lang['Err0040'],$lang['Appointment']) ;
         $this->id = -1;
       } else {
         $d = $result->getDateTime(0, "a_start");
         $this->t = $d->getYYYYMMDD();
       }
       //$result->free();
     }

     #$this->uids = cal_parse_options($this->user,$this->teamname);
     #$this->team = $this->user->get_prefteam();

     # menu
     #$m = appointment::getSelectLink($this->user);
     #$m[category][] = "obj";
     #$this->addmenu($m);
     #$m = appointment::getAddLink($this->user,$this->user);
     #$this->addMenu($m);
   }
 }
	
 $l = new calendar($current_user);
 $l->display();
 //$dbconn->Close();
?>
<!--
    CVS Info:  $Id: calendar.php,v 1.16 2005/05/03 13:18:42 saraj Exp $
    $Author: saraj $
-->
