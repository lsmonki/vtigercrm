<?php
/**
 * Copyright 1999 - 2003 by Gero Kohnert
 *
 *
 * @modulegroup appointment
 * @module app_ins
 * @package appointment
 */
 global $calpath,$callink;
 $calpath = 'modules/Calendar/';
 $callink = 'index.php?module=Calendar&action=';

 include_once $calpath .'webelements.p3';
 include_once $calpath .'permission.p3';
 include_once $calpath .'appointment.pinc';
 include_once $calpath .'product.pinc';

 /* Check if user is allowed to use it */
 check_user();
 loadmodules('appointment','del');
 $msg = "";
 $a = new appointment($dbconn);


 if ( !isset($_GET['id']) ) {
   $msg .= sprintf($lang['Err0040'],$lang[$a->getType()]) ."<br>";
 } else {
   $a = $a->read($_GET['id'],$a);
   $a->read_participants();
 }

 /* Check for existance and rights */
 if ( ! $a->del_ok() ) {
   $msg .= sprintf($lang['Err0023'],$lang[$a->getType()]) ."<br>";
   if ( $a->repeat != 0 ) {
     $gotourl = $callink ."app_new_r&id=".$id;
   } else {
     $gotourl = $callink ."app_new&id=".$id;
   }
 }

 if ( $msg == "" ) {
   $gotourl = $callink ."calendar&t=". $a->start->getYYYYMMDD();

   $dbconn->Begin("WORK");
   $msg .= $a->delete();
   $dbconn->Commit("WORK");
 }
 $gotourl = addMessage($gotourl,$msg,true);
 $gotourl = addSessionKey($gotourl,true);

 #echo  "dfdfd" .$gotourl;
 Header("Status: 302 Moved Temporarily");
 Header("Location: ". getBaseUrl(). $gotourl);
 $dbconn->Close();
 /*
  *  CVS Info:  $Id$
  *  $Author$
  */
?>
