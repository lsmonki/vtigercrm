<?php
/**
 * Copyright 1999 - 2003 by Gero Kohnert
 *
 * @modulegroup address
 * @module address_select
 * @package address
 */
 include_once 'webelements.p3';
 include_once 'permission.p3';
 include_once 'product.pinc';
 include_once 'appointment.pinc';
 include_once 'task.pinc';

 /* Check if user is allowed to use it */
 check_user();
 loadmodules("address","del");

 $msg = "";

 $a = new tutos_address($dbconn);
 if ( isset($_GET['id']) ) {
   $a = $a->read($_GET['id'],$a);
   $gotourl = $a->getUrl();
 } elseif ( isset($_GET['id']) ) {
   $a = $a->read($_GET['id'],$a);
   $gotourl = $a->getUrl();
 } else {
   $gotourl = "address_select.php";
   $msg .= "Missing ID";
 }

 if ( $a->isUser() == 1 ) {
   $msg .= "Please remove Userentry first";
 }

 if ( $a->del_ok() == 0 ) {
   $msg .= sprintf($lang['Err0023'],$lang[$a->getType()]);
 }

 if ( $msg == "" ) {
   $dbconn->Begin("WORK");
   $a->delete();
   $dbconn->Commit("WORK");

   $gotourl = "address_select.php";
 }
 $gotourl = addMessage($gotourl,$msg,true);
 $gotourl = addSessionKey($gotourl,true);

 Header("Status: 302 Moved Temporarily");
 Header("Location: ". getBaseUrl() . $gotourl);
 $dbconn->Close();
 /*
  *  CVS Info:  $Id$
  *  $Author$
  */
?>
