<?php
/**
 * Copyright 2001 - 2004 by Gero Kohnert
 *
 * A calendar for one week
 *
 * @modulegroup appointment
 * @module calendar_week
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

 include_once $calpath .'webelements.p3';
 include_once $calpath .'permission.p3';
 include_once $calpath .'task.pinc';
 include_once $calpath .'appointment.pinc';
 include_once $calpath .'product.pinc';
 include_once $calpath .'timetrack.pinc';

 /* Check if user is allowed to use it */
 check_user();
 loadmodules('appointment','show');
 loadlayout();

 /**
  * display a calendar dfor a week
  */
 class calendar_week extends layout {
   /**
    * A one week calendar sheet
    */
   Function info() {
     global $lang,$tutos,$calpath,$callink,$image_path;

     $adr = $this->user;
     $ts = mktime(12,0,0,substr($this->t,4,2),substr($this->t,6,2),substr($this->t,0,4));

     /* Back to last Monday or Sunday before ts */
     while ( Date("w",$ts) != $this->user->weekstart ) {
       $ts -= 86400;
     }

     $w0 =  (( 1 + Date("w",mktime(12,0,0,1,1, Date("Y",$ts) ) )) % 7) > 3;
     $wn = sprintf("%02d", Round( (Date("z",$ts)+7 ) / 7) );
     $yy = Date("y",$ts);

     $last_week = Date("Ymd",$ts -  7 * 86400);
     $next_week = Date("Ymd",$ts +  7 * 86400);

     echo "<form action=\"". $callink ."calendar_week\" method=\"get\">\n";

     echo "<br><table class=\"navigate\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
     //echo " <tr>\n";
     //echo "  <th align=\"left\" nowrap=\"nowrap\">&nbsp;". $lang['forphrase'] ."\n";
     //cal_options($this->team,$this->teamname);
     //echo "  </th><th>&nbsp;</th>\n";
     //Added for integration	
     //echo " <th class=\"navigate\" nowrap=\"nowrap\"></th>\n";
     //
     //echo " </tr>\n";
     echo " <tr>\n";
     echo "  <td colspan=\"2\" width=\"100%\" align=\"center\">".menulink($callink ."calendar_week&t=".$last_week,$this->theme->getImage(left,'list'),$lang['lastweek']) ."&nbsp;". $lang['week'] ."&nbsp;" . $wn . "/". $yy ."&nbsp;". menulink($callink ."calendar_week&t=".$next_week,$this->theme->getImage(right,'list') ,$lang['nextweek']) ."</td>\n";
     echo " </tr>\n";
	 echo "</table>\n";
	 echo "<br><table class=\"outer\" border=\"0\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">\n";

     $from =  new DateTime();
     $to =  new DateTime();
     $from->setDateTimeTS($ts - 12 * 3600);
     $to->setDateTimeTS($ts - 12 * 3600);
     $to->addDays(7);

     $this->user->callist = array();
     appointment::readCal($this->user,$from,$to);
     task::readCal($this->user,$from,$to);

     foreach($tutos[activemodules] as $i => $f) {
       $x = &new $tutos[modules][$f][name]($this->dbconn);
       $x->readCal($this->user,$from,$to);
     }

     $day = 0;
     $col = 1;
     $dd = new DateTime();
     while ( $day < 8 ) {
	   if ($day!=7) {
		   $dd->setDateTimeTS($ts);
		   $d = $dd->getDate();
		   $tref = Date("Ymd",$ts);
		   $dinfo = GetDaysInfo($ts);
		   /* Select appointments for this day */
		   $next = NextDay($ts);
	
		   if ( $col == 1 ) {
			 echo " <tr>\n";
		   }
		   echo "  <td valign=\"top\" class=\"inner\" width=\"50%\">\n";
	
		   # DAY-TABLE STARTS
		   echo "<table border=\"0\" cellpadding=\"3\" cellspacing=\"1\" width=\"100%\">\n";
		   echo " <tr>\n";
		   echo "  <th class=\"viewhead\">\n";
		   echo menulink($callink ."calendar_day&t=". $tref,$lang['Day'. Date("w",$ts)],strftime($lang['DateFormatTitle'],$ts));
		   if ( isset($dinfo[Desc]) ) {
			 echo " " . menulink($callink ."app_new&t=". $tref,$d,$lang['NewAppointInfo'],$dinfo[popinfo]) ."\n";
		   } else {
			 echo " " . menulink($callink ."app_new&t=". $tref,$d,$lang['NewAppointInfo']) ."\n";
		   }
		   echo "  </th>\n";
		   echo " </tr>\n";
		   echo " <tr>\n";
		   echo "  <td class=\"". $dinfo[color] ."\" width=\"50%\">\n";
		   if ( isset($dinfo[Desc]) ) {
			 echo "<span class=\"dinfo\">". $dinfo[Desc] ."</span>\n";
		   }
		   
		   $hastable = false;
		   foreach ($this->user->callist as $idx => $x) {
			 /* the correct day */
			 if ( ! $this->user->callist[$idx]->inside($dd) ) {
			   continue;
			 }
			 if (!cal_check_against_list($this->user->callist[$idx],$this->uids)) {
			   continue;
			 }
			 // Do not show finished tasks
			 if ( ($this->user->callist[$idx]->gettype() == "task") && ($this->user->callist[$idx]->state == 2) ) {
			   continue;
			 }
			 if ( !$hastable ) {
			   echo "<table class=\"formatted\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\"  width=\"100%\">\n";
			   $hastable = true;
			 } else {
			   echo "  <tr><td class=\"". $dinfo[color] ."\" colspan=\"3\"><img src=\"". $image_path ."black.png\" width=\"100%\" height=\"1\" alt=\"--------\"></td></tr>\n";
			 }
			 // Show appointments or task or whatever
			 $this->user->callist[$idx]->formatted();
		   }
	
		   if ( $hastable ) {
			 echo " </table>\n";
		   } else {
			  echo "<br/><br/><br/><br/>\n";
		   }
	
		   # DAY-TABLE ENDS
		   echo "</td></tr>\n";
		   echo "</table>\n";
	
		   echo "  </td>\n";
		   if ( $col == 2 ) {
			 echo " </tr>\n";
			 $col = 0;
		   }
	
		   $day++;
		   $col++;
		   $ts = $next;
  	   } else {
	   		echo "<td class=\"appday\">&nbsp;</td>\n";
			$day++;
			$col++;
	   }
     }
     if ( $col == 2 ) {
       echo " </tr>\n";
     }
     echo "</table>\n";
     hiddenFormElements();
     $this->addHidden("t", $this->t);
     echo $this->getHidden();
     echo "</form>\n";
   }
   /**
    * naviagte
    */
   Function navigate() {
   }
   /**
    * prepare
    */
   Function prepare() {
     global $tutos, $lang;

     $this->name = $lang['Calendar'];
     if ( ! $this->user->feature_ok(usecalendar,PERM_SEE) ) {
       $msg .= sprintf($lang['Err0022'],"'". $this->name ."'");
       $this->stop = true;
     }

     $this->team = $this->user->get_prefteam();
     $this->teamname = "";
     $this->uids = cal_parse_options($this->user,$this->teamname);
     $this->t = $_GET['t'];
     # menu
     $m = appointment::getSelectLink($this->user);
     $m[category][] = "obj";
     $this->addmenu($m);
     $m = appointment::getAddLink($this->user,$this->user);
     $this->addMenu($m);
   }
 }

# info($_GET['t'],$current_user->get_prefteam(),$teamname,$uids);

 $l = new calendar_week($current_user);
 $l->display();
 $dbconn->Close();
?>
<!--
    CVS Info:  $Id$
    $Author$
-->
