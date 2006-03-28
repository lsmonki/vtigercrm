<?php
/**
 * Copyright 2001 - 2004 by Gero Kohnert
 *
 * A calendar for one week
 *
 * @modulegroup appointment
 * @module calendar_week
 */
 require_once('modules/Calendar/CalendarCommon.php');
 require_once('modules/Calendar/addEventUI.php');
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
 $t=Date("Ymd");
 $html = getHeaderTab($t,'week');
 echo $html;

 include_once $calpath .'webelements.p3';
 include_once $calpath .'permission.p3';
 include_once $calpath .'appointment.pinc';

 require_once('modules/Calendar/preference.pinc');
 require_once('include/database/PearDatabase.php');
 require_once('modules/Calendar/UserCalendar.php');

 
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

   /**
    * Function to get options for time filter combo
    * @param $selcriteria -- selected option :: Type string
    * Constructs html option tag
    * returns html option tag in string format
    */
   //Code added by Minnie - Starts
   function getTimeFilterOption($selcriteria="")
   {
	$timefilter = Array("fullday"=>"Full day (24 Hours)",
	 		    "workhr"=>"Work hours (8am - 8pm)",
		            "morningtonoon"=>"Early morning to Noon (12am - 12pm)",
			    "noontomidnight"=>"Noon to Midnight (12pm - 12am)",
			);
	$thtml = "";
	foreach($timefilter as $FilterKey=>$FilterValue)
   	{
	       if($FilterKey == $selcriteria)
	       {
		      $thtml .= "<option selected value=\"".$FilterKey."\">".$FilterValue."</option>";
	       }else
	       {
	              $thtml .= "<option value=\"".$FilterKey."\">".$FilterValue."</option>";
	       }
	}
	return $thtml;
   }
   //Code added by Minnie - Ends
   
   Function info() {
	global $lang,$tutos,$calpath,$callink,$image_path,$mod_strings,$adb;

     $ts = mktime(12,0,0,substr($this->t,4,2),substr($this->t,6,2),substr($this->t,0,4));

     $xy=Date("w",$ts);

     /* Back to last Monday or Sunday before ts */
     while ( Date("w",$ts) != $this->pref->weekstart ) {
       $ts -= 86400;
     }

     $w0 =  (( 1 + Date("w",mktime(12,0,0,1,1, Date("Y",$ts) ) )) % 7) > 3;
     $wn = sprintf("%02d", Round( (Date("z",$ts)+7 ) / 7) );


     $yy = Date("Y",$ts);
     $day_from = Date("d",$ts);
     $day_to = Date("d",$ts + 6 * 86400);
     $mon = Date("n",$ts);
     $m_name = $mod_strings['cal_month_long'][$mon];
     $mon_next = Date("n",$ts + 7 * 86400);
     $mn_name =  $mod_strings['cal_month_long'][$mon_next]."&nbsp;";
     $last_week = Date("Ymd",$ts -  7 * 86400);
     $next_week = Date("Ymd",$ts +  7 * 86400);

     if ($mn_name == $m_name)
     {
	$mn_name ="";
     }
     $calendarheader = getCalendarHeader($last_week,$next_week,"week",$day_from,$this->pref,$day_to,$m_name,$yy);
     echo $calendarheader;
     echo "<tr><td>";
     echo "<!-- calendar list -->
              <table border=\"0\" cellspacing=\"0\" cellpadding=\"10\" width=\"100%\" class=\"calDisplay\">
              <tr><td align=center>";
     echo "<div align=left style=\"padding:5px;width:95%\">";
     echo "Time filter : <select class=small onchange='getTimeRange(this.options[this.selectedIndex].value )'>";
     echo $this->getTimeFilterOption();
     echo "</select>";
     echo "</div>";
     echo "<div class=\"calDiv\" >";
	echo "<table border=0 cellspacing=1 cellpadding=5 width=100% class=\"calDayHour\" style=\"background-color: #dadada\">";

     $day = 0;
     $col = 1;
     $dd = new DateTime();
     $tempts=$ts;
     for ($row=1;$row<=1;$row++)
     {
	     for ($column=0;$column<=7;$column++)
	     {
		     if($column==0)
		     {
			echo "<tr>";
			echo "<td width=12% class=\"lvtCol\" bgcolor=\"blue\" valign=top>&nbsp;</td>";
		     }
		     else
		     {
			     
		     $next = NextDay($ts);
		     $dd->setDateTimeTS($next);
		     $d = $dd->getDate();
		     $tref = Date("Ymd",$next);
		     $dinfo = GetDaysInfo($next);

		     $from =  new DateTime();
		     $to =  new DateTime();
		     $from->setDateTimeTS($next - 12 * 3600);
		     $to->setDateTimeTS($next - 12 * 3600);
		     $this->pref->callist = array();
		     appointment::readCal($this->pref,$from,$to);
		     //start

		     for ($i = -1 ; $i < 24 ; $i++ ) {
			     $table[$column][$i] = array();
		     }
		     foreach ($this->pref->callist as $idx => $xx) {
			     if ( ! $this->pref->callist[$idx]->inside($from)) {
				     continue;
		             }
			     if ( ($this->pref->callist[$idx]->gettype() == "task") && ($this->pref->callist[$idx]->state == 2) ) {
		             	     continue;
			     }
			     $x1 = Date("G",$this->pref->callist[$idx]->start->getTimeStamp());
			     $x2 = Date("G",$this->pref->callist[$idx]->end->getTimeStamp());

			     if ( $this->pref->callist[$idx]->s_out == 1 ) {
				     $x1 = 0;
			     }
			     if ( $this->pref->callist[$idx]->e_out == 1 ) {
				     $x2 = 23;
			     }
# find a free position
		     	     $pos = -1;
		             $found = false;
			     while ( $found == false ) {
				     $found = true;
				     $pos ++;
				     for ( $i = $x1; $i <= $x2 ; $i++ ) {
					     if (isset($table[$column][$i][$pos]) ) {
						     $found = false;
						     continue;
					     }
				     }
			     }
			     for ( $i = $x1; $i <= $x2 ; $i++ ) {
				     if ( $i == $x1 ) {
					     $table[$column][$i][$pos] = &$this->pref->callist[$idx];
					     $rowspan[$column][$i][$pos] = ($x2 - $x1 +1);
				     } else {
					     $table[$column][$i][$pos] = -1;
				     }
			     }
		     }
		     for ($i = -1 ; $i < 24 ; $i++ ) {
			     $maxcol[$i] = max($maxcol[$i],count($table[$column][$i]));
		     }
		     //end
		     echo "<td width=12% class=\"lvtCol\" bgcolor=\"blue\" valign=top>";
		     echo strftime("%d - %a",$ts);
		     echo "</td>";
		     $ts = $next;
		     }
	     }
	     echo "</tr>";
     }
     for ($row=0;$row<24;$row++)
     {
	     $ts=$tempts;
	     echo "<tr>";
	        $next = NextDay($ts);
	        for ($column=1;$column<=1;$column++)
		{
		     echo "<td  style=\"background-color:#eaeaea; border-top:1px solid #efefef;height:40px\" width=12% valign=top>";
		     if($row==0) echo "12am";
		     if($row>0 && $row<12) echo $row."am";
		     if($row == 12) echo $row."pm";
		     if($row>12 && $row<24) echo ($row-12)."pm";
		     echo "</td>";
		}
		for ($column=0;$column<=6;$column++)
		{
		     echo "<td onClick=\"gshow('addEvent')\" href=\"javascript:void(0)\" onMouseOver=\"this.className='cellNormalHover'\" onMouseOut=\"this.className='cellNormal'\" bgcolor=\"white\" style=\"height:40px\" width=12% valign=top>";

		     for ($c = 0 ; $c < $maxcol[$row] ; $c++ ) {
			     if ( isset ( $table[$column][$row][$c] ) ) {
				     if ( is_object ( $table[$column][$row][$c] ) ) {
					     $color = "";
					     $username=$table[$column][$row][$c]->creator;
					     if ($username!=""){
						     $query="SELECT cal_color FROM users where user_name = '$username'";

						     $result=$adb->query($query);
						     if($adb->getRowCount($result)!=0){
							     $res = $adb->fetchByAssoc($result, -1, false);
							     $usercolor = $res['cal_color'];
							     $color="style=\"background: ".$usercolor.";\"";
						     }
					     }
					     echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"2\" class=\"calEventNormal\" width=100%>\n";
					     echo $table[$column][$row][$c]->formatted();
					     echo " </table>";
				     } else if ( $table[$column][$row][$c] = -1 ) {
					     # SKIP occupied by rowspan
				     }
			     }
		     }

   		     echo "<div valign=bottom align=right onclick=\"gshow('addEvent')\"  width=10% class=\"small\" id=$row.\" pm\"><br>";
		     echo "+";
		     echo"</div></td>";
		     $ts=$next;
	      }
	     echo "</tr>";
     }

     if ( $col == 2 ) {
       echo " </tr>\n";
     }
     echo "</table>\n";
     echo "</td></tr></table>\n";
     hiddenFormElements();
     $this->addHidden("t", $this->t);
     echo $this->getHidden();
     echo "</form>\n";
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
     $this->teamname = "";
     $this->t = $_GET['t'];
   }
 }


 $l = new calendar_week($current_user);
 $l->display();
?>
<!--
    CVS Info:  $Id: calendar_week.php 2074 2005-10-14 11:51:34Z cooljaguar $
    $Author: cooljaguar $
-->	
