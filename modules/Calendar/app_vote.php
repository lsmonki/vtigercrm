<?php
/**
 * Copyright 1999 - 2002 by Gero Kohnert
 *
 * vote for an appointment
 *
 * @modulegroup appointment
 * @module app_vote
 * @package appointment
 */

 global $calpath,$callink; 

 include_once $calpath .'webelements.p3';
 include_once $calpath .'permission.p3';
 include_once $calpath .'appointment.pinc';
 include_once $calpath .'product.pinc';

 /* Check if user is allowed to use it */
 check_user();
 loadmodules("appointment","vote");
 loadlayout();

 /**
  * display a appointment vote form
  */
 class app_vote extends layout {
   /**
    * the data display part
    */
   Function info() {
     global $lang;

     echo $this->DataTableStart();
     echo "<th nowrap colspan=\"3\">". sprintf($lang['AppVoteHead'], menulink($this->obj->getUrl(),$this->obj->start->GetDate())) ."</th>\n";

     echo "<tr>\n";
     echo $this->showfield($lang['StartDate']);
     echo $this->showdata($this->obj->start->getDateTime(),2);
     echo "</tr><tr>\n";
     echo $this->showfield($lang['EndDate']);
     echo $this->showdata($this->obj->end->getDateTime(),2);
     echo "</tr><tr>\n";
     echo $this->showfield($lang['Description']);
     echo $this->showdata(urlReplace($this->obj->descr),2);
     echo "</tr><tr>\n";
     echo $this->showfield($lang['Participants']);
     echo " <td colspan=\"2\">";
     foreach ($this->obj->participant as $f) {
       echo $f->getLink() ;
       echo "<br>" ;
     }
     echo "</td>\n" ;
     echo "</tr><tr>\n";
     echo $this->showfield($lang['AppVoteState']);
     $s = sprintf("state%d",$this->obj->participant_state[$this->adr_id]);
     echo "<td colspan=\"2\" align=\"center\" class=\"". $s ."\"><b>". $lang['AppState'][$this->obj->participant_state[$this->adr_id]] ."</b></td>\n";

     echo "</tr><tr>\n";
     echo "<td colspan=\"3\" align=\"center\"><b>". $lang['AppVoteSelect'] ."</b></td>\n";
     echo "</tr><tr>\n";


     $url = "app_do_vote.php";
     $url = addUrlParameter($url,"id=".$this->obj->id);
     $url = addUrlParameter($url,"adr_id=".$this->adr_id);
     $url = addSessionKey($url);

     echo "<td width=\"30%\" class=\"state1\" align=\"center\"><b>";
     $vurl =  addUrlParameter($url,"vote=1");
     echo makelink($vurl,$lang['AppState'][1],"Vote ". $lang['AppState'][1]);
     echo "</b></td>\n";

     echo "<td width=\"30%\" class=\"state0\" align=\"center\"><b>";
     $vurl =  addUrlParameter($url,"vote=0");
     echo makelink($vurl,$lang['AppState'][0],"Vote ". $lang['AppState'][0]);
     echo "</b></td>\n";

     echo "<td width=\"30%\" class=\"state2\" align=\"center\"><b>";
     $vurl =  addUrlParameter($url,"vote=2");
     echo makelink($vurl,$lang['AppState'][2],"Vote ". $lang['AppState'][2]);
     echo "</b></td>\n";

     echo "</tr>\n";
     echo $this->DataTableEnd();
   }
   /**
    * navigate
    */
   Function navigate() {
     global $lang;

     echo "<tr><td>\n";
     if ( $this->obj->id > 0 ) {
       echo  menulink($callink ."app_show&id=". $this->obj->id ,$lang['AppSeeEntry'],$lang['AppSeeEntryI']) . "<br>";
     }
     echo "</td></tr>\n";
   }
   /**
    * prepare
    */
   Function prepare() {
     global $lang;

     $this->name = $lang['AppointCommit'];
     $this->obj = new appointment($this->dbconn);

     if ( !isset($_GET['id']) || !isset($_GET['adr_id']) ) {
       $msg .= "No appointment or no adr given !<br>";
       $this->stop = true;
     } else {
       $this->obj = $this->obj->read($_GET['id'],$this->obj);
       $this->obj->read_participants();
       $this->adr_id =  $_GET['adr_id'];
     }
     if ( ! $this->obj->see_ok() ) {
       $msg .= sprintf($lang['Err0022'],$lang[$this->obj->getType()]);
       $this->stop = true;
     }
     if ( 1 != $this->obj->trace ) {
       echo "You can not vote for this appointment<br>";
       $this->stop = true;
     }
     # menu
     $m = appointment::getSelectLink($this->user);
     $m[category][] = "obj";
     $this->addmenu($m);
     $m = appointment::getAddLink($this->user,$this->obj);
     $this->addMenu($m);

     add_module_addlinks($this,$this->obj);
   }
 }

 $l = new app_vote($current_user);
 $l->display();
 $dbconn->Close();
?>
<!--
    CVS Info:  $Id$
    $Author$
-->
