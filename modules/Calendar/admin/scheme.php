<?php
/**
 * Copyright 2001-2004 by Gero Kohnert
 *
 * Create all tables indices etc for TUTOS
 *
 * !! Remove/Disable this script after running !!
 *
 * @modulegroup ADMIN
 * @module scheme
 * @package BASE
 */
 global $calpath;
 $calpath='modules/Calendar/';
 $tutos['base'] = "modules/Calendar";
 include_once $calpath .'webelements.p3';
 include_once $calpath .'permission.p3';
 include_once $calpath .'appointment.pinc';
 include_once $calpath .'timetrack.pinc';
 include_once $calpath .'product.pinc';
 include_once $calpath .'mail.pinc';
 include_once $calpath .'history.pinc';

 loadmodules("admin","install");
 loadlayout();

 /**
  * initialize database
  */
 class admin_scheme extends layout {
   /**
    * Perform Step 1  (creating tables,indizes,sequences)
    */
   Function step1() {
     global $table,$tableidx,$sequence;

     echo "<p>";
     echo "<font color=green>Creating Calendar Tables</font><br />\n";
   flush();
     $out = 0;
	if ( $this->obj->conn != -1 ) {
     } else {
       echo "<font color=red>failed to open Database! </font><br />";
       flush();
       return;
     }
     flush();

     foreach ($table as $i => $f) {
       if ($this->obj->droptable($f) == -1) {
       } else { 
         echo "<font color=red><span class=\"found\">Table <b>". $this->obj->prefix . $f[name] ."</b> dropped</span></font><br />\n";
       }
      
       if ($this->obj->createtable($f) == -1) {
       } else { 
         echo "<font color=green><span class=\"found\">Table <b>". $this->obj->prefix . $f[name] ."</b> created</span></font><br />\n";
       }
       flush();
     }
     # Build the inidces
     foreach ($tableidx as $i => $f) {
       if ($this->obj->dropindex($f) == -1) {
       } else { 
       }
       if ($this->obj->createindex($f) == -1) {
       } else { 
       }
       flush();
     }
     # Build the sequencers
     @reset($sequence);
     while ( list ($i,$f) = @each ($sequence) ) {
       if ( $this->obj->dropsequence($f) == -1 ) {
       } else {
       };
       if ( $this->obj->createsequence($f) == -1 ) {
       } else {
       };
       flush();
     }
     # Save the Database
     # The current DB entry is always that with the ID 1
     $this->obj->db->creator = $this->user;
     $this->obj->db->newid = 1;
     $this->obj->db->id = -1;
     $this->obj->db->dbconn = &$this->obj;
     $msg = $this->obj->db->save();
     echo $msg;
     return;
   }
   /**
    * Perform Step 2  (insert a single super user)
    */
   Function step2() {
     global $tutos;

     $msg = "";
#     echo "(re)creating a <b>admin</b> account<br />\n";
     $tutos[demo] = 0;


     $adr = new tutos_address($this->obj);
     $adr->f_name = "Admin";
     $adr->l_name = "User";
     $adr->birthday = new DateTime();
     $adr->creator = $adr;


     $this->user = new tutos_user($this->obj);
     $this->user->updatepw = 1;
     $this->user->login = "admin";
     $this->user->pw = "admin";
     $this->user->id = $adr->id;
     $this->user->admin = 1;

   }
   /**
    * Perform Step 3  (example data)
    */
   Function step3() {
     global $lang,$tutos,$current_user;

     flush();
     $msg = "";
     $tutos[demo] = 0;

     # a array to store the demo env
     $demodata = array();

     include_once($calpath .'demodata.p3');

     #
     # Walk over all modules and create some demo data
     #
     # read the table definitions of modules
     foreach ($tutos[modules] as $r => $x) {
       if (!isset($tutos[modules][$r][file])) continue;
       if (file_exists(dirname($tutos['base'] ."/". $tutos[modules][$r][file]) ."/demodata.p3") ) {
         loadmodule($r);
         @include_once dirname($tutos['base'] ."/". $tutos[modules][$r][file]) ."/demodata.p3";
       }
     }
   
     # Notes
     return;
   }
   /**
    * intialize a database step by step
    */
   Function info() {
     global $tutos,$lang;

     echo $this->DataTableStart();
     $this->addHidden("id",$this->id);
       $this->step1();
       $this->step2();
     $this->obj->Close();
   }
   /**
    * navigate
    */
   Function navigate() {
     global $tutos,$lang;


     foreach ($tutos[dbname] as $i => $f) {
     }

   }

   /**
    * prepare
    */
   Function prepare() {
     global $msg,$current_user,$lang,$tutos,$msg,$table,$tableidx,$sequence;

     if (isset($_GET['msg'])) { 
       $msg = $_GET['msg'];
     }
     ReadLang($lang);

     if ( ! minimum_version(minimumversion) ) {
       $msg .= "<br />\nSorry TUTOS needs a PHP Version >=  ". minimumversion ."<br />\n";
       $msg .= "you are using ". phpversion() ."<br />\n";
       $this->stop = true;
     }

     # read the table definitions of modules
     foreach ($tutos[modules] as $r => $x) {
       if (!isset($tutos[modules][$r][file])) continue;
       if (file_exists(dirname($tutos['base'] ."/". $tutos[modules][$r][file]) ."/mtable.pinc") ) {
         @include_once dirname($tutos['base'] ."/". $tutos[modules][$r][file]) ."/mtable.pinc";
         loadmodule($r);
       }
     }
     if ( isset($_GET['step']) && ($tutos[demo] == 0) ) {
       $this->step = $_GET['step'];
     } else {
       $this->step = 0;
     }
     if ( isset($_GET['id']) ) {
       $this->id = $_GET['id'];
     } else {
       $this->id = 0;
     }
     if ( ! isset($tutos[dbname][$this->id] ) ) {
       $this->stop = true;
     } else {
       $this->obj = DB_Open($this->id,false);
     }
     if ( $this->stop == false ) {
       if ( $this->obj->gettype() == "Postgres" ) {
         # No persistent connections here
         $this->obj->setPersistent(0);
       }
       if ( $this->obj->gettype() == "Interbase" ) {
         # No persistent connections here
         $this->obj->setPersistent(0);
       }
     }
     $this->user = new tutos_user($this->obj);
     $this->user->f_name = "TUTOS";
     $this->user->l_name = "(installation service)";
     $this->user->admin = 1;
     $current_user = $this->user;

     $this->name = $lang['DBInit']." STEP ". $this->step;
   }
 }


 $dbc = null;
 $x = new tutos_user($dbc);
 $l = new admin_scheme($x);
 $sch='true';
 $l->display($sch);
?>
<!--
    CVS Info:  $Id$
    $Author$
-->
