<?php
/**
 * Copyright 1999 - 2004 by Gero Kohnert
 *
 * @modulegroup user
 * @module user_ins
 * @package user
 */
 
 global $calpth,$callink;
 $callink = "index.php?module=Calendar&action=";
 $calpath = "modules/Calendar/";

 include_once $calpath .'webelements.p3';
 include_once $calpath .'permission.p3';
 include_once $calpath .'appointment.pinc';

 /* Check if user is allowed to use it */
 check_user();
 loadmodules("user","ins");

 $msg = "";
 $saveadr = false;
 $saveloc = false;
 $u = new tutos_user($dbconn);
 #$gotourl = "user_new.php";

 if ( ! isset($_POST['uid']) || empty($_POST['uid']) ) {
   $msg .= "Please enter a id<br>";
 } else if ( isset($_POST['uid']) && ($_POST['uid'] != -1) ) {
   # existing entry
   $u = $u->read($_POST['uid'],$u,1);
   $u->read_permissions();
   #$gotourl= addUrlParameter($gotourl,"uid=". $_POST['uid'],true);
 } else if ( isset($_POST['nid']) && ($_POST['nid'] != -1) )  {
   # new entry
   $u = $u->read($_POST['nid'],$u,0);
   $u->read_permissions();
   #$gotourl= addUrlParameter($gotourl,"id=". $_POST['nid'],true);
 } else if ( ($_POST['nid'] == -1) && ($_POST['uid'] == -1) ) {
   $saveadr = true;
   $adr = new tutos_address($dbconn);
   $loc = new location($dbconn);
   $loc->setLName("default");
   if ( !isset($_POST['first_name']) || empty($_POST['first_name']) ) {
     $msg .= sprintf($lang['Err0009'],$lang['AdrFirstName']) ."<br>";
   } else {
     $adr->setFName(trim(StripSlashes($_POST['first_name'])));
     #$gotourl= addUrlParameter($gotourl,"first_name=". UrlEncode(StripSlashes($_POST['first_name'])),true);
   }
   if ( !isset($_POST['last_name']) || empty($_POST['last_name']) ) {
     $msg .= sprintf($lang['Err0009'],$lang['AdrLastName']) ."<br>";
   } else {
     $adr->setLName(trim(StripSlashes($_POST['last_name'])));
     #$gotourl= addUrlParameter($gotourl,"last_name=". UrlEncode(StripSlashes($_POST['last_name'])),true);
   }
   if ( !isset($_POST['email1']) || empty($_POST['email1']) ) {
     $msg .= sprintf($lang['Err0009'],$lang['AdrEmail']) ."<br>";
   } else {
     $saveloc = true;
     $loc->setField("email_1",trim(StripSlashes($_POST['email1'])));
     #$gotourl= addUrlParameter($gotourl,"email=". UrlEncode(StripSlashes($_POST['email1'])),true);
   }
 }
     
 if ( ($u->uid == -1) && !$current_user->feature_ok(useuser,PERM_NEW) ) {
   $msg .= sprintf($lang['Err0054'],$lang[$u->getType()]);
 }
 if ( ($u->uid != -1) && !$current_user->feature_ok(useuser,PERM_MOD) ) {
   $msg .= sprintf($lang['Err0024'],$lang[$u->getType()]);
 }

 if ( !isset($_POST['user_name']) || empty($_POST['user_name']) ) {
   $msg .= sprintf($lang['Err0009'],$lang['Username']) ."<br>";
 } else {
   $u->setLogin($_POST['user_name']);
   #$gotourl= addUrlParameter($gotourl,"user_name=". UrlEncode($u->user_name),true);
 }

 # This does not work for mysql !!!
 if ( $dbconn->gettype() != "MySQL" ) {
   # Check old password
   if ( (!empty($_POST['p1']) || !empty($_POST['p2'])) && ($current_user->admin == 0) ) {
     if ( "'". $u->pw ."'" != $dbconn->Password($_POST['p0']) ) {
       $msg .= $lang['Err0042'] ."<br>";
     }
   }
 }
 if ( !empty($_POST['p1']) || !empty($_POST['p2']) ) {
   if ( $_POST['p1'] != $_POST['p2'] ) {
     $msg .= $lang['Err0041'] ."<br>";
   }
 }
 
 # Holidays
 $u->holiday = array();
 if (isset($_POST['h']) ) {
   foreach (array_unique($_POST['h']) as $i => $f) {
     #$gotourl= addUrlParameter($gotourl,"h[]=". UrlEncode($f),true);
     $u->holiday[$f] = 1;
   }
 }
 # Namedays
 $u->nameday = array();
 if (isset($_POST['nd']) ) {
   foreach (array_unique($_POST['nd']) as $i => $f) {
     #$gotourl= addUrlParameter($gotourl,"nd[]=". UrlEncode($f),true);
     $u->nameday[$f] = 1;
   }
 }
 # workdays
 if (isset($_POST['wd']) ) {
   $u->workday = array();
   foreach (array_unique($_POST['wd']) as $i => $f) {
     #$gotourl= addUrlParameter($gotourl,"wd[]=". UrlEncode($f),true);
     $u->workday[] = $f;
   }
 }
 # Weekstart
 if (isset($_POST['ws']) ) {
   $u->weekstart = $_POST['ws'];
   #$gotourl= addUrlParameter($gotourl,"ws=". UrlEncode($u->weekstart),true);
 }
 if ( ! $u->mod_ok() ) {
   $msg .= sprintf($lang['Err0024'],$lang[$u->getType()]) ."<br>";
 }

 # RowIcons
 $u->rowiconsbefore = array();
 if ( isset($_POST['rib']) ) {
   foreach (array_unique($_POST['rib']) as $i => $f) {
     #$gotourl= addUrlParameter($gotourl,"rib[]=". UrlEncode($f),true);
     $u->rowiconsbefore[$f] = 1;
   }
 } 
 $u->rowiconsafter = array();
 if ( isset($_POST['ria']) ) {
   foreach (array_unique($_POST['ria']) as $i => $f) {
     #$gotourl= addUrlParameter($gotourl,"ria[]=". UrlEncode($f),true);
     $u->rowiconsafter[$f] = 1;
   }
 }
 
 # Check that there is one admin left
 if ( $u->admin == 1 && ($_POST['admin'] == 0) ) {
   $q = "SELECT * FROM ". $dbconn->prefix ."people WHERE ". $dbconn->colname("admin") ." = 1";
   $r = $dbconn->Exec($q);
   $n = $r->numrows();
   if ( $n == 1 ) {
     $msg .= $lang['Err0047'] ."<br>";
   }
   $r->free();
 }
 # Disabled
 if ( isset($_POST['disabled']) ) {
   $u->setDisabled($_POST['disabled']);
 } else {
   $u->setDisabled(0);
 }

 # will set user-default-acl from input
 $u->acldefault = array();
 if ( isset($_POST['r']) ) {
   foreach($_POST['r'] as $i => $f) {
     $u->acldefault[$f]=$tutos[seeok];  
   }
 }  
 if ( isset($_POST['u']) ) {
   foreach($_POST['u'] as $i => $f) {
     $u->acldefault[$f]=$tutos[useok];  
   }
 }  
 if ( isset($_POST['m']) ) {
   foreach($_POST['m'] as $i => $f) {
     $u->acldefault[$f]=$tutos[modok];  
   }
 }  
 if ( isset($_POST['d']) ) {
   foreach($_POST['d'] as $i => $f) {
     $u->acldefault[$f]=$tutos[delok];  
   }
 }  

 if ( !empty($_POST['p1']) && !empty($_POST['p2']) ) {
   $u->setPassword($_POST['p1']);
   $u->updatepw = 1;
 } else {
   $u->updatepw = 0;
 }

 if ( $u->uid == -1 ) {
   $u->updatepw = 1;
 }

 #
 # Parse additional custom fields
 #
 $msg .= parse_custom_fields("people",$u);

 # Permissions
 $msg .= parse_permission_form($u);

 # other modules
 $msg .= module_parseforms($current_user,$u,$gotourl);
 
 if ( $msg == "" ) {
   $u->setAdmin($_POST['admin']);
   $u->setLanguage($_POST['lng']);
   $u->setTimezone($_POST['tz']);
   $u->setTheme($_POST['theme']);
   $u->setLayout($_POST['layout']);

   $dbconn->Begin("WORK");

   if ($saveadr) {
     $msg .= $adr->save();
     $u->id = $adr->id;
     if ($saveloc) {
       $loc->adr_id = $adr->id;
       $msg .= $loc->save();
     }
   }
   $msg .= $u->save_permissions();
   $msg .= $u->save();
   $dbconn->Commit("WORK");

   $gotourl = "address_show.php";
   if ( $tutos[demo] == 1 ) {
     $gotourl= addUrlParameter($gotourl,"lg=". $u->lang,true);
     $gotourl= addUrlParameter($gotourl,"th=". $u->theme,true);
     $gotourl= addUrlParameter($gotourl,"ly=". $u->ly,true);
   }
   $gotourl= addUrlParameter($gotourl,"id=". $u->id,true);
 }

 $gotourl = addMessage($gotourl,$msg,true);
 $gotourl = addSessionKey($gotourl,true);

 /* Go back to user mask */
 #Header("Status: 302 Moved Temporarily");
 #Header("Location: ". getBaseUrl() . $gotourl);
 $dbconn->Close();
 /*
  *  CVS Info:  $Id$
  *  $Author$
  */
?>
