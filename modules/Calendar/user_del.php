<?php
/*
 * Copyright 1999 - 2003 by Gero Kohnert
 *
 */
 include_once 'webelements.p3';
 include_once 'permission.p3';
 include_once 'product.pinc';
 include_once 'appointment.pinc';
 include_once 'task.pinc';

 /* Check if user is allowed to use it */
 check_user();
 loadmodules("user","del");

 $msg = "";
 $gotourl = "address_select.php";
 $u = new tutos_user($dbconn);

 if ( ! isset($_GET['id']) )  {
   $msg .= sprintf($lang['Err0040'],$lang[$u->getType()]) ."<br>";
 } else if ( $_GET['id'] == $current_user->id ) {
   $msg .= $lang['Err0053']; // You cannot delete your own user entry
 } else {
   $u = $u->read($_GET['id'],$u);
   $gotourl = $u->getUrl();
 }

 if ( ! $u->del_ok() ) {
   $msg .= sprintf($lang['Err0023'],$u->getFullName()) ."<br>";
 }

 if ( $msg == "" ) {
   $dbconn->Begin("WORK");
   $msg .= $u->delete();
   $dbconn->Commit("WORK");

   $msg .= "User (not address!) ". $u->getFullName() ." was deleted<br>";
 }

 $gotourl = addMessage($gotourl,$msg,true);
 $gotourl = addSessionKey($gotourl,true);

 Header("Status: 302 Moved Temporarily");
 Header("Location: ". getBaseUrl() . $gotourl);
 $dbconn->Close();
/*
 *   CVS Info:  $Id$
 *   $Author$
 */
?>
