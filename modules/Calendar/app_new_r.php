<?php
/**
 * Copyright 1999 - 2004 by Gero Kohnert
 *
 * Editing of "repeating" appointments
 *
 * @modulegroup appointment
 * @module app_new_r
 * @package appointment
 */
 global $calpath , $callink;

 include_once 'webelements.p3';
 include_once 'permission.p3';
 include_once 'appointment.pinc';


 /* Check if user is allowed to use it */
 check_user();
 loadmodules("appointment","new");
 loadlayout();

 /**
  * display a appointment for changing
  */
 class app_new_r extends layout {
   /**
    * display the info
    */
   Function info() {
     global $table,$tutos, $lang;

     $a_checked[0] = "";
     $a_checked[1] = "";
     $a_checked[2] = "";
     $a_checked[3] = "";
     $a_checked[$this->obj->mod_allow] = " checked=\"checked\"";

     $r_checked[1] = "";
     $r_checked[2] = "";
     $r_checked[3] = "";
     $r_checked[4] = "";
     $r_checked[$this->obj->repeat] = " checked=\"checked\"";


     if ( $this->obj->allowed == 0 ) {
        echo "<b>". $lang['ReadOnlyAppoint'] ."</b><br />\n";
     }
     echo "<form name=\"appnew\" action=\"app_ins.php\" method=\"post\">\n";
     $this->addHidden("gotourl","app_new_r.php");
     if ( $this->obj->id > 0 ) {
       $this->addHidden("id",$this->obj->id);
     }

     echo $this->DataTableStart();

     echo "<tr>\n";
     echo "<th colspan=\"6\">";
     if ( $this->obj->id > 0 ) {
       echo $lang['ModOldAppoint'] ."(repeat)";
       echo "</th>\n";
       echo "</tr><tr>\n";
       echo "<td colspan=\"5\"><b>". $lang['AppCreatedBy'] ."</b>&nbsp;". $this->obj->creator->getLink();
       echo "&nbsp;<b>". $lang['atDateTime'] ."</b>&nbsp;". $this->obj->creation->getDateTime()."</td>";
       echo "<td align=\"right\">" .acl_link($this->obj) ."</td>\n";
       echo "</tr>\n";
     } else {
       echo $lang['CreateAppoint'] ." (repeat)";
       echo "</th>\n";
     }
     echo "</tr>\n";
     $this->addHidden("creator",$this->obj->creator->id);

     # START
     echo "<tr>";
     echo $this->showfield($lang['AppFirstDate'],0,"start_d");
     echo " <td colspan=\"2\">";
     $this->obj->start->EnterDate("start");
     echo "</td>";
     echo "<td valign=\"top\">&nbsp;<b>";
     if ( !isset($_SERVER['HTTP_USER_AGENT']) || ereg("Lynx",$_SERVER['HTTP_USER_AGENT']) || ereg("w3m",$_SERVER['HTTP_USER_AGENT']) ) {
       echo $lang['StartTime'];
     } else {
       echo "<a href=\"JavaScript: var d = document.forms[0];
mywindow = window.open('', 'timer', 'width=120,height=420,top=100,left=450');
mywindow.location.href = '". $tutos['base'] ."/php/minitimer.php?f=start&amp;". SID ."'; mywindow.focus();\"
onmouseover=\"self.status='minitimer' ;return true\">";
       echo $lang['StartTime'];
       echo "</a>";
     }
     echo "</b>&nbsp;<br />&nbsp;<font size=\"-1\">(HH:MM)</font></td>\n";
     echo " <td colspan=\"2\">";
     $this->obj->start->EnterTime("start");
     echo " </td>\n";

     # END
     echo "</tr><tr>\n";
     echo $this->showfield($lang['AppLastDate'],0,"end_d");
     echo "<td colspan=\"2\">";
     $this->obj->end->EnterDate("end");
     echo "<br />". $lang['AppNoLastDate'];
     echo "<input type=\"checkbox\" name=\"r_ignore\" value=\"1\"". ($this->obj->r_ignore == 1 ? " checked=\"checked\"":"") ." />";
     echo "</td>\n";
     echo "<td valign=\"top\">&nbsp;<b>";
     if ( !isset($_SERVER['HTTP_USER_AGENT']) || ereg("Lynx",$_SERVER['HTTP_USER_AGENT']) || ereg("w3m",$_SERVER['HTTP_USER_AGENT']) ) {
       echo $lang['EndTime'];
     } else {
       echo "<a href=\"JavaScript: var d = document.forms[0];
mywindow = window.open('', 'timer', 'width=120,height=420,top=100,left=450');
mywindow.location.href = '". $tutos['base'] ."/php/minitimer.php?f=end&amp;". SID ."'; mywindow.focus();\"
onmouseover=\"self.status='minitimer' ;return true\">";
       echo $lang['EndTime'];
       echo "</a>";
     }
     echo "</b>&nbsp;<br />&nbsp;<font size=\"-1\">(HH:MM)</font></td>\n";
     echo "<td colspan=\"2\">";
     $this->obj->end->EnterTime("end");
     echo "</td>\n";

     echo "</tr><tr>\n";

     # REPEAT TYPE
     echo $this->showfield($lang['AppRepeatType'],0,"repeat");
     echo " <td colspan=\"2\">\n";
     echo "  <input type=\"radio\" id=\"repeat\" name=\"repeat\" value=\"". APP_REP_DAY ."\"". $r_checked[APP_REP_DAY] ." />&nbsp;". $lang['AppRepeatDay'] ."<br />";
     echo "  <input type=\"radio\" name=\"repeat\" value=\"". APP_REP_WEEK ."\"". $r_checked[APP_REP_WEEK] ." />&nbsp;". $lang['AppRepeatWeek'] ."<br />";
     echo "  <input type=\"radio\" name=\"repeat\" value=\"". APP_REP_MONTH ."\"". $r_checked[APP_REP_MONTH] ." />&nbsp;". $lang['AppRepeatMonth'] ."<br />";
     echo "  <input type=\"radio\" name=\"repeat\" value=\"". APP_REP_YEAR ."\"". $r_checked[APP_REP_YEAR] ." />&nbsp;". $lang['AppRepeatYear'] ."<br />";
     echo " </td>\n";

     # Ignore times
     echo " <td colspan=\"3\" valign=\"top\">\n";
     echo $lang['IgnoreTime'];
     echo "&nbsp;<input type=\"checkbox\" name=\"t_ignore\" value=\"1\" ". ($this->obj->t_ignore == 1 ? "checked=\"checked\"":"") ." /><br />\n";
     echo "<font size=\"-1\">". $lang['IgnoreTime2'] ."</font>";
     echo " </td>\n";

     echo "</tr><tr>\n";

     # LOCATION
     echo $this->showfield($lang['Location2'],0,"outside");
     echo " <td colspan=\"5\">\n";
     echo "  <select id=\"outside\" name=\"outside\">\n";
     @reset($lang['AppLoc']);
     while ( list($i,$f) = each($lang['AppLoc']) ) {
       echo "   <option value=\"". $i ."\" ". ($this->obj->outside == $i ? "selected=\"selected\"":"") .">". $lang['AppLoc'][$i] ."</option>\n";
     }
     echo "  </select>\n";
     echo " </td>\n";

     echo "</tr><tr>\n";
     echo $this->showfield($lang['Description'],0,"descr");
     echo $this->textarea("descr",5,$table['appointment1']['description'][size],$this->obj->descr);

     echo "</tr><tr>\n";
     # Customer/Visitor name
     echo $this->showfieldc($lang['VisitAt'] ."<br />". $lang['VisitFrom'],0,"v");
     echo " <td colspan=\"5\">";
     select_from_array_or_input($this->obj,"v",$this->obj->visitor,1);
     echo " </td>\n";

     if ( $this->user->feature_ok(useprojects,PERM_SEE) ) {
       echo "</tr><tr>\n";
       echo $this->showfieldc($lang['Product'],0,"p");
       echo " <td colspan=\"5\">\n";
       select_from_array_or_input($this->obj,"p",$this->obj->product,1);
       echo " </td>\n";
     }

     echo "</tr><tr>\n";

     echo $this->showfield($lang['Participants'],1,"people");
     echo " <td valign=\"top\">\n";
     echo "". $this->user->askPeople("people[]",$this->obj->people,1) ."\n";
     echo " </td>\n";

     echo " <td colspan=\"4\">&nbsp;<b>". $lang['AppChangeOrDel'] ."</b>&nbsp;<br />\n";
     echo "&nbsp;<input type=\"radio\" name=\"mod_allow\" value=\"2\"". $a_checked[2] ." />&nbsp;". $lang['only'] ." ". $this->obj->creator->getLink() ."<br />\n";
     echo "&nbsp;<input type=\"radio\" name=\"mod_allow\" value=\"1\"". $a_checked[1] ." />&nbsp;". $lang['Participants'] ."<br />\n";
     echo "&nbsp;<input type=\"radio\" name=\"mod_allow\" value=\"0\"". $a_checked[0] ." />&nbsp;". $lang['everybody'] ."<br />\n";
     echo "&nbsp;<input type=\"radio\" name=\"mod_allow\" value=\"3\"". $a_checked[3] ." />&nbsp;". $lang['AppPrivate'] ."<br />\n";

     echo " </td>\n";

     if ( $this->obj->mod_ok() ) {
       echo "</tr><tr>\n";
       if ( $this->obj->id > 0 ) {
         submit_reset(0,1,2,1,2,0);
       } else {
         submit_reset(0,-1,2,1,2,0);
       }
     }
     echo "</tr>\n";
     echo $this->DataTableEnd();
     hiddenFormElements();
     echo $this->getHidden();
     echo "</form>\n";
     echo $lang['FldsRequired'] ."\n";
     echo $this->setfocus("appnew.descr");
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
     global $msg,$tutos, $lang;

     $p = array();
     $this->obj = new appointment($this->dbconn);

     if ( isset($_GET['id']) ) {
       $this->name = $lang['AppointModify'];
       $this->obj = $this->obj->read($_GET['id'],$this->obj);
       if ($this->obj->id < 0) {
         $msg .= sprintf($lang['Err0040'],$lang[$this->obj->getType()]);
         $this->stop = true;
       }
       $this->obj->read_participants();
        /* only the owner may change */
       if ( $this->user->id == $this->obj->creator->id )  {
         $this->obj->allowed = 2;
       }
       if ( $this->obj->visitor != -1 ) {
         $this->obj->xfn['v'] = $this->obj->visitor->getFullName();
       }
       if ( $this->obj->product != -1 ) {
         $this->obj->xfn['p'] = $this->obj->product->getFullName();
       }
       foreach($this->obj->participant as $i => $f) {
         $p[$i] = 2;
       }
     } else {
       $this->name = $lang['AppointCreate'];
       /* New event */
       if (isset($_GET['t']) && is_numeric($_GET['t'])) {
         $this->obj->start->setDateTime($_GET['t']);
         $this->obj->end->setDateTime($_GET['t']);
       }
       $this->obj->repeat = 1;
       $p[$this->user->id] = 2;
     }

     # Set available Parameters
     if ( isset($_GET['descr']) ) {
       $this->obj->descr = StripSlashes($_GET['descr']);
     }
     if ( isset($_GET['trace']) ) {
       $this->obj->trace = $_GET['trace'];
     }
     if ( isset($_GET['email']) ) {
       $this->obj->email = $_GET['email'];
     }
     if ( isset($_GET['mod_allow']) ) {
       $this->obj->mod_allow = $_GET['mod_allow'];
     }
     if ( isset($_GET['start']) ) {
       $this->obj->start->setDateTime($_GET['start']);
     }
     if ( isset($_GET['end']) ) {
       $this->obj->end->setDateTime($_GET['end']);
     }
     if ( isset($_GET['remember']) ) {
       $this->obj->remember = $_GET['remember'];
     }
     if ( isset($_GET['t_ignore']) ) {
       $this->obj->t_ignore = $_GET['t_ignore'];
     }

     preset_from_array_or_input($this->obj,'visitor','v');
     preset_from_array_or_input($this->obj,'product','p');

     if ( isset($_GET['people']) ) {
       $p = array();
       foreach($_GET['people'] as $i => $f) {
         $p[$f] = 2;
       }
     }
     $this->obj->people = $p;

     if ( isset($_GET['outside']) ) {
       $this->obj->outside = $_GET['outside'];
     }

     $this->obj->allowed = $this->obj->mod_ok();
     if ( ($this->obj->id < 0) && !$this->user->feature_ok(usecalendar,PERM_NEW) ) {
       $msg .= sprintf($lang['Err0054'],$lang[$this->obj->getType()]);
       $this->stop = true;
     } else if (  ! $this->obj->mod_ok() ) {
       $msg .= sprintf($lang['Err0024'],$lang[$this->obj->getType()]);
       $this->stop = true;
     }
     # menu
     $m = appointment::getSelectLink($this->user);
     $m[category][] = "obj";
     $this->addmenu($m);
     $m = appointment::getAddLink($this->user,$this->obj);
     $this->addmenu($m);
     if ( $this->obj->id > 0 ) {
       if ( $this->obj->del_ok() ) {
         $m = array( url => $this->obj->getDelURL(),
                     text => $lang['Delete'],
                     info => $lang['AppDelInfo'],
                     confirm => true,
                     category => array("app","del","obj")
                   );
         $this->addMenu($m);
       }
       $m = array( url => $this->obj->getURL(),
                   text => $lang['AppSeeEntry'],
                   info => $lang['AppSeeEntryI'],
                   category => array("app","show","obj")
                 );
       $this->addMenu($m);
     }
     if ( $this->obj->see_ok() && ($this->obj->id > 0) ) {
       $m = array( url => $calpath ."app_show&format=ical&id=". $this->obj->id,
                   text => $lang['AppGetIcal'],
                   info => $lang['AppGetIcal'],
                   category => array("app","show","obj")
                 );
       $this->addMenu($m);
     }
     add_module_addlinks($this,$this->obj);
   }
 }

 $l = new app_new_r($current_user);
 $l->display();
 $dbconn->Close();
?>
<!--
    CVS Info:  $Id$
    $Author$
-->
