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
 #include_once $calpath .'task.pinc';
 include_once $calpath .'appointment.pinc';
 #include_once $calpath .'product.pinc';
 #include_once $calpath .'timetrack.pinc';

 require_once('modules/Calendar/preference.pinc');
 require_once('include/database/PearDatabase.php');
require_once('modules/Calendar/UserCalendar.php');

 
 /* Check if user is allowed to use it */
 #check_user();
 loadmodules('appointment','show');
 loadlayout();
 /**
  * display a calendar dfor a week
  */
 class calendar_week extends layout {
   /**
    * A one week calendar sheet
    */
	
   Function calendar_week()
   {
   	$this->pref = new preference();
	$this->db = new PearDatabase();
$calobj = new UserCalendar();
		$this->tablename = $calobj->table_name;

   }
   Function info() {
     global $lang,$tutos,$calpath,$callink,$image_path,$mod_strings;

     //$adr = $this->user;
     $adr = $this->pref;
     $ts = mktime(12,0,0,substr($this->t,4,2),substr($this->t,6,2),substr($this->t,0,4));

     /* Back to last Monday or Sunday before ts */
     while ( Date("w",$ts) != $this->pref->weekstart ) {
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
     echo "  <td colspan=\"2\" width=\"100%\" align=\"center\">".$this->pref->menulink($callink ."calendar_week&t=".$last_week,$this->pref->getImage(left,'list'),$mod_strings['LBL_LAST_WEEK']) ."&nbsp;". $mod_strings['LBL_WEEK'] ."&nbsp;" . $wn . "/". $yy ."&nbsp;". $this->pref->menulink($callink ."calendar_week&t=".$next_week,$this->pref->getImage(right,'list') ,$mod_strings['LBL_NEXT_WEEK']) ."</td>\n";
     echo " </tr>\n";
	 echo "</table>\n";
	 echo "<br><table class=\"outer\" border=\"0\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">\n";

     $from =  new DateTime();
     $to =  new DateTime();
     $from->setDateTimeTS($ts - 12 * 3600);
     $to->setDateTimeTS($ts - 12 * 3600);
     $to->addDays(7);

     $this->pref->callist = array();
     appointment::readCal($this->pref,$from,$to);
     //print_r($this->pref->callist);
     #task::readCal($this->user,$from,$to);

     /*foreach($tutos[activemodules] as $i => $f) {
       $x = &new $tutos[modules][$f][name]($this->dbconn);
       $x->readCal($this->pref,$from,$to);
     }
    */
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
		   echo $this->pref->menulink($callink ."calendar_day&t=". $tref,$mod_strings['LBL_DAY'. Date("w",$ts)],strftime($mod_strings['LBL_DATE_TITLE'],$ts));
		   if ( isset($dinfo[Desc]) ) {
			 echo " " . $this->pref->menulink($callink ."app_new&t=". $tref,$d,$mod_strings['LBL_NEW_APPNT_INFO'],$dinfo[popinfo]) ."\n";
		   } else {
			 echo " " . $this->pref->menulink($callink ."app_new&t=". $tref,$d,$mod_strings['LBL_NEW_APPNT_INFO']) ."\n";
		   }
		   echo "  </th>\n";
		   echo " </tr>\n";
		   echo " <tr>\n";
		   echo "  <td class=\"". $dinfo[color] ."\" width=\"50%\">\n";
		   if ( isset($dinfo[Desc]) ) {
			 echo "<span class=\"dinfo\">". $dinfo[Desc] ."</span>\n";
		   }
		   
		   $hastable = false;
		   foreach ($this->pref->callist as $idx => $x) {
			 /* the correct day */
			 if ( ! $this->pref->callist[$idx]->inside($dd) ) {
			   continue;
			 }
			 /*if (!cal_check_against_list($this->pref->callist[$idx],$this->uids)) {
			   continue;
			 }*/
			 // Do not show finished tasks
			 if ( ($this->pref->callist[$idx]->gettype() == "task") && ($this->pref->callist[$idx]->state == 2) ) {

			   continue;
			 }
			 if ( !$hastable ) {
			   echo "<table class=\"formatted\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\"  width=\"100%\">\n";
			   $hastable = true;
			 } else {
			   echo "  <tr><td class=\"". $dinfo[color] ."\" colspan=\"3\"><img src=\"". $image_path ."black.png\" width=\"100%\" height=\"1\" alt=\"--------\"></td></tr>\n";
			 }
			 // Show appointments or task or whatever
			 $this->pref->callist[$idx]->formatted();

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

     $this->name = $mod_strings['LBL_MODULE_NAME'];
     #if ( ! $this->user->feature_ok(usecalendar,PERM_SEE) ) {
     #  $msg .= sprintf($lang['Err0022'],"'". $this->name ."'");
     #  $this->stop = true;
     #}

     #$this->team = $this->user->get_prefteam();
     $this->teamname = "";
     #$this->uids = cal_parse_options($this->user,$this->teamname);
     $this->t = $_GET['t'];
     # menu
     #$m = appointment::getSelectLink($this->user);
     #$m[category][] = "obj";
     #$this->addmenu($m);
     #$m = appointment::getAddLink($this->user,$this->user);
     #$this->addMenu($m);
   }
 }

# info($_GET['t'],$current_user->get_prefteam(),$teamname,$uids);

 $l = new calendar_week($current_user);
 $l->display();
 #$dbconn->Close();
?>
<!--
    CVS Info:  $Id$
    $Author$
-->	
