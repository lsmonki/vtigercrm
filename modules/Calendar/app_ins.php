<?php
/**
 * Copyright 1999 - 2003 by Gero Kohnert
 *
 * this script parses all the inputs and stores the object into the database.
 * on completition it jumps to the detail screen of the stored object
 *
 * @modulegroup appointment
 * @module app_ins
 * @package appointment
 */
 global $calpath,$callink,$db;
 $callink = "index.php?module=Calendar&action=";
 include_once $calpath .'webelements.p3';
 include_once $calpath .'permission.p3';
 include_once $calpath .'appointment.pinc';
 require_once('modules/Calendar/preference.pinc');
 include_once $calpath .'product.pinc';

 /* Check if user is allowed to use it */
 #check_user();
 loadmodules("appointment","new");
 $pref = new preference();
 if ( ! isset($_POST['gotourl']) ) {
   $gotourl= $callink ."app_new";
 } else {
   $gotourl=$_POST['gotourl'] ;
 }
 $msg = "";
 
 $a = new appointment($dbconn);
 #
 # ID
 #
 if ( isset($_POST['creator_id']) ) {
   $gotourl= addUrlParameter($gotourl,"id=".$_POST['creator_id'],true);
   $a = $a->read($_POST['id'],$a);
   #$a->read_participants();
 }
 if ( isset($_POST['t_ignore']) ) {
   $a->t_ignore = 1;
 } else {
   $a->t_ignore = 0;
 }
 if ( isset($_POST['r_ignore']) ) {
   $a->r_ignore = $_POST['r_ignore'];
 } else {
   $a->r_ignore = 0;
 }
 $gotourl= addUrlParameter($gotourl,"t_ignore=".$a->t_ignore,true);
 $gotourl= addUrlParameter($gotourl,"r_ignore=".$a->r_ignore,true);

 # Set the Start and End time

 $start = new DateTime();
 $start->setDateTimeF("start");
 $start->getTimeStamp();
 if ( ( !$start->checkDMY()) || (-1 == $start->getTimeStamp() ) ) {
   $msg .= sprintf($lang['Err0038'],$lang['StartDate']) ."<br>";
 } else {
   $gotourl= addUrlParameter($gotourl,"start=".$start->getYYYYMMDDHHMM(),true);
   $a->setStartTime($start);
 }

 $end = new DateTime();
 $end->setDateTimeF("end");
 if ( ( !$end->checkDMY()) || (-1 == $end->getTimeStamp()) ) {
   $msg .= sprintf($lang['Err0038'],$lang['EndDate']) ."<br>";
 } else {
   $gotourl= addUrlParameter($gotourl,"end=".$end->getYYYYMMDDHHMM(),true);
   $a->setEndTime($end);
 }

 #
 # Checks
 #
 if ( ($a->start->ts > $a->end->ts) && ($a->r_ignore == 0) ) {
   # Start after End
   $msg .= $lang['Err0002'] ."<br>";
 }


 $a->oldparticipant = $a->participant;
 if ( isset($_POST['people']) && (count($_POST['people']) > 0)) {
   foreach ($_POST['people'] as $f) {
     if ( $f == "" ) {
	   continue;
	 }
     $gotourl= addUrlParameter($gotourl,"people[]=".$f,true);

	 unset($a->oldparticipant[$f]); // a virtual move to the new list

     if (!isset($a->participant[$f])) {
	   // new participant
       $obj = GetObject($dbconn, $f);
       if (  $obj->id == $f ) {
		 $a->addParticipant($obj);
       }
       unset($obj);
     }
   }
 }
 # remove the remaining particiapants from the old list
 # those participants are no longer member of the app
 foreach ($a->oldparticipant as $i => $f) {
   $a->delParticipant($i);
 }

 if ( count($a->participant) == 0 ) {
   $msg .= sprintf($lang['Err0014'],$lang['Participants']) ."<br>";
 }

 if ( !isset($_POST['mod_allow']) ) {
   $msg .= sprintf($lang['Err0014'],$lang['AppChangeOrDel']) ."<br>";
 } else {
   $gotourl= addUrlParameter($gotourl,"mod_allow=".$_POST['mod_allow'],true);
   $a->mod_allow = $_POST['mod_allow'];
 }

 if ( !isset($_POST['creator']) ) {
   $msg .= "Missing creator<br>";
 } else {
   $a->creator = new tutos_user($dbconn);
   $a->creator = $a->creator->read($_POST['creator'],$a->creator);
 }
 #
 # Subject
 #
 if ( !isset($_POST['subject']) || $_POST['subject'] == "") {
   $msg .= "Missing Subject<br>";
 }
 else {
	$a->subject = $_POST['subject'];
 }
 #
 # Contact
 #
 if ( !isset($_POST['contact_name']) || $_POST['contact_name'] == "") {
   $msg .= "Missing Contact<br>";
 }
 else
 {
   $a->contact_name = $_POST['contact_name'];
 } 
   $a->account_name = $_POST['account_name'];
 #
 # TRACE
 #
 if ( !isset($_POST['trace']) ) {
   $a->setTrace(0);
 } else {
   $a->setTrace($_POST['trace']);
 }
 $gotourl= addUrlParameter($gotourl,"trace=".$a->trace,true);
 #
 # EMAIL
 #
 if ( !isset($_POST['email']) ) {
   $a->email = 0;
 } else {
   $a->email = $_POST['email'];
 }
 $gotourl= addUrlParameter($gotourl,"email=".$a->email,true);
 #
 # OUTSIDE
 #
 if ( !isset($_POST['outside']) ) {
   $a->SetLocation(0);
 } else {
   $a->SetLocation($_POST['outside']);
 }
 $gotourl= addUrlParameter($gotourl,"outside=".$a->outside,true);
 #
 # REPEAT
 #
 if ( !isset($_POST['repeat']) ) {
   $a->repeat = 0;
 } else {
   $a->repeat = $_POST['repeat'];
 }
 $gotourl= addUrlParameter($gotourl,"repeat=".$a->repeat,true);

 if ( $a->repeat == 0 ) {
   $a->r_arg      = "";
 } else if ( $a->repeat == 1 ) {
   $a->r_arg      = Date("w",$a->start->GetTimeStamp());
 } else if ( $a->repeat == 2 ) {
   $a->r_arg      = Date("j",$a->start->GetTimeStamp());
 } else if ( $a->repeat == 3 ) {
   $a->r_arg      = Date("j/n",$a->start->GetTimeStamp());
 } else if ( $a->repeat == 4 ) {
   $a->r_arg      = "";
 }
 #
 # REMEMBER
 #
 if ( !isset($_POST['remember']) ) {
   $a->setRemember(0);
 } else {
   $a->setRemember($_POST['remember']);
 }
 $gotourl= addUrlParameter($gotourl,"remember=".$a->remember,true);
 #
 # DESCRIPTION
 #
 if ( isset($_POST['descr']) && ! empty($_POST['descr']) ) {
   $a->SetDescription(StripSlashes(trim($_POST['descr'])));
 }
 $gotourl = addUrlParameter($gotourl,"descr=". UrlEncode($a->descr),true);
 #
 # VISITOR
 #
 # Clear Visitor Address
 $v = -1;
 if ( isset($_POST['vfn']) && !empty($_POST['vfn']) ) {
   $v = check_field(StripSlashes($_POST['vfn']),"vfn","vl","acd");
 } else {
   if ( isset($_POST['vid']) && !empty($_POST['vid']) ) {
     if ( $_POST['vid'] != -1 ) {
       $v = getObject($dbconn,$_POST['vid']);
     }
   }
 }
 if ( is_object($v) && ($v->use_ok()) ) {
   $a->setVisitor($v);
 } else {
   $a->setVisitor(-1);
 }
 #
 # PRODUCT
 #
 $p = -1;
 if ( isset($_POST['pfn']) && !empty($_POST['pfn']) ) {
   $p = check_field(StripSlashes($_POST['pfn']),"pfn","pl","p");
 } else {
   if ( isset($_POST['pid']) && !empty($_POST['pid']) ) {
     if ( $_POST['pid'] != -1 ) {
       $p = getObject($dbconn,$_POST['pid']);
     }
   }
 }
 if ( is_object($p) && ($p->use_ok()) ) {
   $a->setProduct($p);
 } else {
   $a->setProduct(-1);
 }
 # other modules
 $msg .= module_parseforms($current_user,$a,$gotourl);

 # check availability of participants and resources
 if ( ($msg == "") && (isset($_POST['check']) && $_POST['check'] == 1) ) {
   $gotourl= addUrlParameter($gotourl,"check=".$_POST['check'],true);
   $a->check_participants($msg);
 }

 ##################################################
 # End of Checks
 ##################################################
 if ( $msg == "" ) {
   $dbconn->Begin("WORK");
   $msg .= $a->save();
   $dbconn->Commit("WORK");

   /* Go back to calendar */
   $gotourl = $callink ."calendar";
   $gotourl= addUrlParameter($gotourl,"id=".$a->id,true);
 }
 $gotourl= addUrlParameter($gotourl,"msg=".$msg,true);
 $gotourl = addMessage($gotourl,$msg,true);
 $gotourl = addSessionKey($gotourl,true);
 
 Header("Status: 302 Moved Temporarily");
 Header("Location: ". getBaseUrl() . $gotourl);
 $dbconn->Close();
/*
 *
 *   CVS Info:  $Id$
 *   $Author$
 *
 */
?>
