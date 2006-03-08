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
 echo "\n<BR>\n";
 $t=Date("Ymd");
?>
<table border=0 cellspacing=0 cellpadding=0 width=95% align=center>
<tr>
<form name="Calendar" method="GET" action="index.php">
          <input type="hidden" name="module" value="Calendar">
	  <input type="hidden" name="action">
	  <input type="hidden" name="t">
  <td>
   <table border=0 cellspacing=0 cellpadding=3 width=100%>
   <tr>
      <td class="dvtTabCache" style="width:10px" nowrap>&nbsp;</td>
      <td class="dvtUnSelectedCell" align=center nowrap><a href="index.php?module=Calendar&action=new_calendar&sel=day&t=<?echo $t?>">Day</a></td>
      <td class="dvtTabCache" style="width:10px">&nbsp;</td>
      <td class="dvtSelectedCell" align=center nowrap><a href="index.php?module=Calendar&action=new_calendar&sel=week&t=<?echo $t?>">Week</a></td>
      <td class="dvtTabCache" style="width:10px">&nbsp;</td>
      <td class="dvtUnSelectedCell" align=center nowrap><a href="index.php?module=Calendar&action=new_calendar&sel=month&t=<?echo $t?>">Month</a></td>
      <td class="dvtTabCache" style="width:10px">&nbsp;</td>
      <td class="dvtTabCache" style="width:100%">&nbsp;</td>
   </tr>
   </table>
  </td>
 </form> 
</tr>
<tr>
  <td valign=top align=left >
   <table border=0 cellspacing=0 cellpadding=3 width=100% class="dvtContentSpace">
   <tr>
      <td align=left style="padding:5px">
<?php

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
  //   global $lang,$tutos,$calpath,$callink,$image_path,$mod_strings;
	global $lang,$tutos,$calpath,$callink,$image_path,$mod_strings,$adb;

     //$adr = $this->user;
     //$adr = $this->pref;
     $ts = mktime(12,0,0,substr($this->t,4,2),substr($this->t,6,2),substr($this->t,0,4));

     $xy=Date("w",$ts);

     /* Back to last Monday or Sunday before ts */
     while ( Date("w",$ts) != $this->pref->weekstart ) {
       $ts -= 86400;
     }

     $w0 =  (( 1 + Date("w",mktime(12,0,0,1,1, Date("Y",$ts) ) )) % 7) > 3;
     $wn = sprintf("%02d", Round( (Date("z",$ts)+7 ) / 7) );


     #$yy = Date("y",$ts);
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
     echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">";
     echo "<form action=\"". $callink ."calendar_weekview\" method=\"get\">\n";
     echo "<tr><td>";
     echo "<table border=0 cellspacing=0 cellpadding=0 width=100% class=\"calTopBg\"><tr><td>\n";
     echo "<img src=\"";
     echo $image_path."calTopLeft.gif\"></td><td>";
     echo $this->pref->menulink($callink ."calendar_weekview&t=".$last_week,$this->pref->getImage(left,'list'),$mod_strings['LBL_LAST_WEEK']) ."</td>";
     echo "<td><img src=\"";
     echo $image_path."calSep.gif\"></td>";
     echo "<td align=\"center\" width=\"100%\" class=\"lvtHeaderText\">";
     echo $mod_strings['LBL_WEEK'] ."&nbsp;of&nbsp;" . $m_name . "&nbsp;".$day_from."&nbsp;to&nbsp;".$mn_name.$day_to."&nbsp;". $yy ."&nbsp;";
     echo "</td><td><img src=\"";
     echo $image_path."calSep.gif\"></td><td>";
     echo $this->pref->menulink($callink ."calendar_weekview&t=".$next_week,$this->pref->getImage(right,'list') ,$mod_strings['LBL_NEXT_WEEK']) ."</td>\n";
     echo "<td align=right><img src=\"";
     echo $image_path."calTopRight.gif\"></td>";
     echo "</tr></table></td></tr>";
     echo "<tr><td>";
     echo "<!-- calendar list -->
              <table border=\"0\" cellspacing=\"0\" cellpadding=\"10\" width=\"100%\" class=\"calDisplay\">
              <tr><td align=center>";
     echo "
	     <div align=left style=\"padding:5px;width:95%\">
	     Time filter : <select class=small>
	     <option>Select ...</option>
	     <option> - Work hours (8am - 8pm)</option>

	     <option> - Early morning to Noon (12am - 12pm)</option>
	     <option> - Noon to Midnight (12pm - 12am)</option>
	     <option> - Full day (24 Hours)</option>
	     <option> - Custom time...</option>
	     </select>

	     </div>";
     echo "<div class=\"calDiv\" >";
	echo "<table border=0 cellspacing=1 cellpadding=5 width=100% class=\"calDayHour\" style=\"background-color: #dadada\">";

     $day = 0;
     $col = 1;
     $dd = new DateTime();
     $tempts=$ts;
     for ($row=1;$row<=1;$row++)
     {
	     echo "<tr>";
	     echo "<td width=12% class=\"lvtCol\" bgcolor=\"blue\" valign=top>&nbsp;</td>";
	     for ($column=0;$column<=6;$column++)
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
			     if ( $this->pref->callist[$idx]->gettype() == "note" ) {
				     $table[$column][-1][] = &$this->pref->callist[$idx];
				     $rowspan[$column][-1][] = 1;
				     continue;
			     }
			     if ( $this->pref->callist[$idx]->gettype() == "watchlist" ) {
				     $table[$column][-1][] = &$this->pref->callist[$idx];
				     $rowspan[$column][-1][] = 1;
				     continue;
			     }
			     if ( $this->pref->callist[$idx]->gettype() == "task" ) {
				     $table[$column][-1][] = &$this->pref->callist[$idx];
				     $rowspan[$column][-1][] = 1;
				     continue;
			     }
			     if ( $this->pref->callist[$idx]->gettype() == "reminder" ) {
				     $table[$column][-1][] = &$this->pref->callist[$idx];
				     $rowspan[$column][-1][] = 1;
				     continue;
			     }
			     if ( $this->pref->callist[$idx]->t_ignore == 1) {
				     $table[$column][-1][] = &$this->pref->callist[$idx];
				     $rowspan[$column][-1][] = 1;
				     continue;
			     }
			     if ( ($this->pref->callist[$idx]->s_out == 1) && ($this->pref->callist[$idx]->e_out == 1) ) {
				     $table[$column][-1][] = &$this->pref->callist[$idx];
				     $rowspan[$column][-1][] = 1;
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
		     echo "<td onMouseOver=\"this.className='cellNormalHover'\" onMouseOut=\"this.className='cellNormal'\" bgcolor=\"white\" style=\"height:40px\" width=12% valign=top>";

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
					     echo " </table>";//</td>\n";
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
    CVS Info:  $Id: calendar_week.php 2074 2005-10-14 11:51:34Z cooljaguar $
    $Author: cooljaguar $
-->	
