<?php
/**
 * Copyright 1999 - 2002 by Gero Kohnert
 *
 * vote for an appointment (execution)
 *
 * @modulegroup appointment
 * @module app_do_vote
 * @package appointment
 */
 include_once 'webelements.p3';
 include_once 'permission.p3';
 include_once 'appointment.pinc';

 /* Check if user is allowed to use it */
 check_user();
 loadmodules('appointment','mod');

 /* Check the input */
 $msg = "";
 $a = new appointment($dbconn);

 $gotourl = "calendar.php";
 if ( ! isset($_GET['id']) ) {
   $msg .= "Missing Appointment ID<br>";
 } else {
   $a = $a->read($_GET['id'],$a);
   $a->read_participants();
   $gotourl= addUrlParameter($gotourl,"id=".$_GET['id']);
 }
 if ( ! isset($_GET['adr_id']) ) {
   $msg .= "Missing User ID<br>";
 }
 if ( ! isset($_GET['vote']) ) {
   $msg .= "Missing Vote<br>";
 }

 if ( $msg == "" ) {
   $dbconn->Begin("WORK");
   $a->save_vote($_GET['vote'],$_GET['adr_id']);
   $dbconn->Commit("WORK");
 }
 $gotourl= addUrlParameter($gotourl,"msg=". UrlEncode($msg));

 /* Go back to calendar */
 Header("Status: 302 Moved Temporarily");
 Header("Location: ". getBaseUrl() . $gotourl);
 $dbconn->Close();
 /*
  *  CVS Info:  $Id$
  *  $Author$
  */
?>