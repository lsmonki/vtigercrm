<?php
/**
 * Copyright 1999 - 2004 by Gero Kohnert
 *
 * @modulegroup address
 * @module address_ins
 * @package address
 */
 include_once 'webelements.p3';
 include_once 'permission.p3';

 /* Check if user is allowed to use it */
 check_user();
 loadmodules("address","ins");

 $msg = "";

 $gotourl = "address_new.php";
 $f_name = "";
 $l_name = "";
 $m_name = "";
 $title = "";
 # Base entry
 $a = new tutos_address($dbconn);
 # Location 
 $l = new location($dbconn);

 if ( isset($_POST['id']) ) {
   $gotourl= addUrlParameter($gotourl,"id=". $_POST['id'],true);
   $a = $a->read($_POST['id'],$a);
   $a->read_picture();
   if ( ! $a->mod_ok() ) {
     # Not allowed
     $msg .= sprintf($lang['Err0024'],$lang[$a->getType()],true);
   }
   $new = false;
 } else {
   $new = true;
 }

 if ( isset($_POST['loc_id']) ) {
   $gotourl= addUrlParameter($gotourl,"loc_id=". $_POST['loc_id'],true);
   $l = $l->read($_POST['loc_id'],$l);
   if ( ! $l->mod_ok() ) {
     # Not allowed
     $msg .= sprintf($lang['Err0024'],$lang[$l->getType()],true);
   }
 }


 $birthday = new DateTime(0);
 $birthday->setDateTimeF("birthday",1);


 if ( !$birthday->checkDMY(true) ) {
   $msg .= sprintf($lang['Err0038'],$lang['AdrBirthday']) ."<br>";
 } else {
   $gotourl= addUrlParameter($gotourl,"bd=". $birthday->getYYYYMMDD(),true);
 }
 if ( empty($_POST['f_name']) || ($_POST['f_name'] == "Unknown" ) || !isset($_POST['f_name']) ) {
   $msg .= sprintf($lang['Err0009'],$lang['AdrFirstName']) ."<br>";
 } else {
   $f_name = trim(StripSlashes($_POST['f_name']));
   $gotourl= addUrlParameter($gotourl,"f_name=". UrlEncode(StripSlashes($f_name)),true);
 }

 if ( empty($_POST['l_name']) || !isset($_POST['l_name']) ) {
   $msg .= sprintf($lang['Err0009'],$lang['AdrLastName']) ."<br>";
 } else {
   $l_name = trim(StripSlashes($_POST['l_name']));
   $gotourl= addUrlParameter($gotourl,"l_name=". UrlEncode(StripSlashes($l_name)),true);
 }
 if ( isset($_POST['m_name']) ) {
   $m_name = trim(StripSlashes($_POST['m_name']));
   $gotourl= addUrlParameter($gotourl,"m_name=". UrlEncode(StripSlashes($m_name)),true);
 }
 if ( isset($_POST['title']) ) {
   $title = trim(StripSlashes($_POST['title']));
   $gotourl= addUrlParameter($gotourl,"title=". UrlEncode(StripSlashes($title)),true);
 }

 if ( isset($_POST['pic_id']) ) {
   $pic_id = $_POST['pic_id'];
   $gotourl= addUrlParameter($gotourl,"pic_id=". $pic_id,true);
 }
 if ( isset($HTTP_POST_FILES['file']) ) {
   $file = $HTTP_POST_FILES['file'];
   $gotourl= addUrlParameter($gotourl,"pic_path=". $file['name'],true);
 }
#echo var_dump($_POST) ."<br>";
#echo var_dump($HTTP_POST_FILES) ."<br>";

 # If no other problems than check if name is already used
 if ( $msg == "" ) {
   if ( $new 
    || ($birthday->getYYYYMMDD() != $a->birthday->getYYYYMMDD())
    || ($f_name != $a->f_name)
    || ($m_name != $a->m_name)
    || ($l_name != $a->l_name) ) {

     $q = "SELECT * FROM ". $a->tablename ." WHERE ". $dbconn->Like2("f_name", $f_name) ." AND ". $dbconn->Like2("m_name",$m_name) ." AND ". $dbconn->Like2("l_name",$l_name);
     if ( $birthday->notime != 1 ) {
       $q .= " AND birthday = ". $dbconn->Date($birthday);
     }
     if ( ! $new ) {
       $q .= " AND id != ". $a->id;
     }
     $r = $dbconn->Exec($q);
     $n = $r->numrows();
     if ( 0 != $n) {
       $x = new tutos_address($dbconn);
       $x->read_result($r,0);
       $msg .= sprintf($lang['Err0039'],$x->getLink()) ."<br>";
     }
     $r->free();
   }
 }
 # after check we can set the stuff
 $a->setTitle($title);
 $a->setFName($f_name);
 $a->setLName($l_name);
 $a->setMName($m_name);

 # location
 $lmsg = $l->parseform();
 if ($l->used || ($l->id >0)) {
   $msg .= $lmsg;
 }

 $savefile = 0;
 if ( $msg == "" ) {
   if ( isset($file) && ($file != "none") ) {
     $farr = $HTTP_POST_FILES['file'];
     $a->pic_file = new tutos_file($dbconn);
     $a->pic_file->tmploc = $farr['tmp_name'];
     $a->pic_file->filesize = $farr['size'];
     $a->pic_file->filename = $farr['name'];
     $a->pic_file->filetype = $farr['type'];
     $a->pic_file->name = $lang['AdrPicture'] ." ". $a->getFullName();
     $a->pic_file->logtxt = $a->getFullName();
     $a->pic_file->savemode = 0;
     if ( isset($farr['size']) && ($farr['size'] > 0) && ($farr['name'] != "")) {
       $savefile = 1;
     }
   }
 }

 # other modules
 $msg .= module_parseforms($current_user,$a,$gotourl);

 if ( $msg == "" ) {
   $a->setBirthday($birthday);

   $dbconn->Begin("WORK");
   $msg = $a->save();
   $gotourl = $a->getModUrl();
   $l->adr_id = $a->id;
   if ($l->used || ($l->id >0)) {
     $msg = $l->save();
     $gotourl= addUrlParameter($gotourl,"loc_id=". $l->id,true);
   }
   if ( $savefile == 1 ) {
     $a->pic_file->link_id = $a->id;
     $msg .= $a->pic_file->save();
     $a->pic_id = $a->pic_file->id;
     $msg = $a->save();
   }
   $dbconn->Commit("WORK");

 }

 $gotourl = addMessage($gotourl,$msg,true);
 $gotourl = addSessionKey($gotourl,true);

 Header("Status: 302 Moved Temporarily");
 Header("Location:". getBaseUrl(). $gotourl);
 $dbconn->Close();
 /*
  *  CVS Info:  $Id$
  *  $Author$
  */
?>
