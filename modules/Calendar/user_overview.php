<?php
/**
 * Copyright 1999 - 2004 by Gero Kohnert
 *
 * @modulegroup user
 * @module user_overview
 * @package user
 */
 include_once 'webelements.p3';
 include_once 'permission.p3';
 include_once 'product.pinc';
 include_once 'appointment.pinc';
 include_once 'task.pinc';

 /* Check if user is allowed to use it */
 check_user();
 loadmodules("user","overview");
 loadlayout();

 /**
  * show a overview of user accounts
  */
 class user_overview extends layout {
   /**
    * display the info
    */
   Function info() {
     global $lang,$tutos;


     $result = $this->dbconn->Exec($this->q);
     $n = $result->numrows();
     if ( 0 == $n) {
       echo $this->error($lang['Err0048']);
       $result->free();
       return;
     }

     echo $this->actionformStart("user_overview.php");
     echo $this->OverviewTableStart();
     echo "<thead>\n";
     echo "<tr>\n";
     echo $this->orderHeader("login", $lang['User'],$this->link2);
     echo $this->orderHeader("last_seen", $lang['UserLastSeen'],$this->link2);
     echo $this->orderHeader("last_host", $lang['UserLastHost'],$this->link2);
     echo $this->orderHeader("disabled", $lang['UserDisabled'],$this->link2);
     echo $this->orderHeader("lang", $lang['UserLanguage'],$this->link2);
     echo $this->orderHeader("tz", $lang['UserTimezone'],$this->link2);
     echo $this->orderHeader("theme", $lang['UserTheme'],$this->link2);
     echo $this->orderHeader("layout", $lang['UserLayout'],$this->link2);
     if ( $tutos[massupdate] == 1 ) {
       echo "  <th nowrap><input type=\"checkbox\" name=\"checkit\" onclick=\"CheckAll2();\" /></th>\n";
     }
     echo "</tr>\n";
     echo "</thead>\n";

     if ( $this->start == -1 ) {
       $a = $n - $tutos[maxshow];
       $end = $n;
       $this->start = $a;
     } else {
       $a = $this->start;
       $end = $this->start + $tutos[maxshow];
     }

     $line = 0;
     while ( ($a < $n) && ($a < $end) ) {
       $f = new tutos_user($this->dbconn);
       $uid = $result->get($a, "id");
       $f = $f->read($uid,$f,1);
       $a++;
       if ( ($f->id == -1) || ! $f->see_ok() ) {
         continue;
       }
       echo $this->OverviewRowStart($line,$f->login);
       echo " <td align=\"right\">". makelink("user_new.php?uid=".$f->uid ,$f->login) ."&nbsp;</td>";
       echo " <td>". $f->last_seen->getDateTime() ."&nbsp;</td>";
       echo " <td>". $f->last_host ."&nbsp;</td>";
       echo " <td align=\"center\">". ($f->disabled == 1 ? $lang['yes']:$lang['no']) ."</td>";
       echo " <td>". $f->lang ."&nbsp;</td>";
       echo " <td>". $f->tz ."&nbsp;</td>";
       echo " <td>". $f->theme ."&nbsp;</td>";
       echo " <td>". $f->ly ."&nbsp;</td>";
       if ( $tutos[massupdate] == 1 ) {
         echo " <td align=\"center\">\n";
         if ( $f->mod_ok() ) {
           echo " <input name=\"mark[]\" type=\"checkbox\" value=\"". $f->uid ."\"></td>\n";
         } else {
           echo "-\n";
         }
         echo "</td>\n";
       }
       echo $this->OverviewRowEnd($line++);
       unset ($f);
     }

     echo $this->list_navigation($this->link1,8 + $tutos[massupdate],$this->start,$a,$n);

     if ( $tutos[massupdate] == 1 ) {
       echo $this->UpdateRowStart(7);
       echo sprintf($lang['withmarked'],$lang['Users']);
       echo "<select name=\"action\">\n";
       echo " <option value=\"-1\" selected>". $lang['ActionNil'] ."</option>\n";
       echo " <option value=\"-2\">". $lang['Delete'] ."</option>\n";
       echo " <option value=\"enable\">". sprintf($lang['SetTo'],$lang['UserDisabled'],$lang['no']) ."</option>\n";
       echo " <option value=\"disable\">". sprintf($lang['SetTo'],$lang['UserDisabled'],$lang['yes']) ."</option>\n";
       echo " <option value=\"-4\">". $lang['AclModify'] ."</option>\n";
       echo "</select>\n";
       echo $this->UpdateRowEnd(2);
     }

     echo $this->OverviewTableEnd();
     echo $this->actionformEnd("user_overview.php");
   }
   /**
    * navigation
    */
   Function navigate() {
   }
   /**
    * action
    */
   Function action() {
     global $msg,$tutos,$lang;

     if ( $this->user->admin == 0 ) {
       return;
     }

     @reset($_GET['mark']);
     if ( $_GET['action'] == -2 ) {
       $this->dbconn->Begin("WORK");
       while (list ($key,$val) = @each ($_GET['mark'])) {
         $b = new tutos_user($this->dbconn);
         $b = $b->read($val,$b,2);
         if ( $b->uid != $val ) {
           $msg .= $b->uid ." != ". $val ."<br>";
           continue;
         }
         if ( $b->del_ok() ) {
           $msg .= $lang['Delete'] ."&nbsp;". $b->login ."<br>";
           $msg .= $b->delete();
         } else {
           $msg .= $b->getLink() .": ". sprintf($lang['Err0023'],$lang[$b->getType()]);
         }
         unset($b);
       }
       $this->dbconn->Commit("WORK");
     } else if ( $_GET['action'] == 'enable' ) {
       $this->dbconn->Begin("WORK");
       while (list ($key,$val) = @each ($_GET['mark'])) {
         $b = new tutos_user($this->dbconn);
         $b = $b->read($val,$b,2);
         if ( $b->uid != $val ) {
           $msg .= $b->uid ." != ". $val ."<br>";
           continue;
         }
         if ( $b->mod_ok() ) {
           $msg .= $lang['UserDisable'] ."&nbsp;". $b->login ."<br>";
           $b->setDisabled(0);
           $msg .= $b->save();
         } else {
           $msg .= $b->getLink() .": ". sprintf($lang['Err0024'],$lang[$b->getType()]);
         }
         unset($b);
       }
       $this->dbconn->Commit("WORK");
     } else if ( $_GET['action'] == 'disable' ) {
       $this->dbconn->Begin("WORK");
       while (list ($key,$val) = @each ($_GET['mark'])) {
         $b = new tutos_user($this->dbconn);
         $b = $b->read($val,$b,2);
         if ( $b->uid != $val ) {
           $msg .= $b->uid ." != ". $val ."<br>";
           continue;
         }
         if ( $b->mod_ok() ) {
           $msg .= $lang['UserDisabled'] ."&nbsp;". $b->login ."<br>";
           $b->setDisabled(1);
           $msg .= $b->save();
         } else {
           $msg .= $b->getLink() .": ". sprintf($lang['Err0024'],$lang[$b->getType()]);
         }
         unset($b);
       }
       $this->dbconn->Commit("WORK");
     } else if ( $_GET['action'] == -4 ) {
       $this->redirect = acl_action();
     }
   }
   /**
    * prepare
    */
   Function prepare() {
     global $msg,$tutos,$lang;

     $this->name = $lang['UserOverview'];

     if ( $this->user->admin == 0 ) {
       $msg .= "<span class=\"warn\">Only admins are allowed to see this</span><br>\n";
       if ( $tutos[demo] == 1 ) {
         $msg .= "<span class=\"warn\">exceptionally enabled for this demo</span><br>\n";
       } else {
         $this->stop = true;
       }
     }

     $this->link1 = "user_overview.php";
     $this->link2 = "user_overview.php";
     $this->q = "SELECT ". $this->dbconn->prefix ."people.*,id as u_id from ". $this->dbconn->prefix ."people";

     # sorting
     $xxx = "";
     order_parse($this->q,$this->link1,$xxx,$xxx,"login");
     
     $x = array( url => "user_new.php",
                 text => $lang['NewEntry'],
                 info => $lang['UserCreate'],
                 category => array("user","new","obj")
               );
     $this->addMenu($x);

     web_StackStartLayout($this,"user_overview.php","user_overview.php");
   }
 }

 $l = new user_overview($current_user);
 $l->display();
 $dbconn->Close();
?>
<!--
    CVS Info:  $Id$
    $Author$
-->
