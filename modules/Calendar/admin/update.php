<?php
/**
 * Copyright 2002 - 2004 by Gero Kohnert
 *
 * Update all tables indices etc for TUTOS
 *
 * !! Remove/Disable this script after running !!
 *
 * @modulegroup ADMIN
 * @module update
 * @package BASE
 */
 global $calpath;

 $tutos['base'] = "modules/Calendar";
 #ini_set("include_path","..");
 include_once $calpath .'webelements.p3';
 include_once $calpath .'permission.p3';


 loadmodules("admin","update");
 loadlayout();

 /**
  * display a update output
  */
 class admin_update extends layout {
   /**
    * display the info
    */
   Function info() {
     global $table, $tableidx, $sequence, $tutos;

     echo "<h2>". $this->dbc->getFullName() ."</h2>\n";
     echo "<p>TABLES<p>\n";

     foreach ($table as $i => $f) {
       echo $f[Desc] ."<br>\n";
       if ($this->dbc->createtable($f) == -1) {
         if ( $tutos[debug] != 0 ) {
           echo "Table ". $f[name] ." not created:<span class=\"warn\">". $this->dbc->lasterror ."</span><br>\n";
         }
       } else { 
         echo "<span class=\"found\">Table <b>". $f[name] ."</b> created</span><br>\n";
       }
       flush();
     }

     echo "<p>COLUMNS<p>\n";
     foreach ($table as $i => $f) {
       echo $f[Desc] ."<br>\n";
       @reset($f);
       while ( list ($i2,$f2) = @each ($f) ) {
         if ( ! is_array($f2) ) {
           continue;
         }
         if ($this->dbc->createcolumn($f[name],$i2,$f2) == -1) {
           # nothing created (try an alter table to fix sizes)
         } else {
           echo "Column <span class=\"found\">". $f[name] ." (". $i2 .")</span> updated<br>\n";
         } 
       }
       flush();
     }

     echo "<p>INIDCES<p>\n";
     @reset($tableidx);
     while ( list ($i,$f) = @each ($tableidx) ) {
       echo $f[Desc] ."<br>\n";
       if ($this->dbc->createindex($f) == -1) {
       } else { 
         echo "<span class=\"found\">Index <b>". $f[name] ."</b> created</span><br>\n";
       }
       flush();
     }
     echo "<p>SEQUENCERS<p>\n";
     @reset($sequence);
     while ( list ($i,$f) = @each ($sequence) ) {
       echo $f[Desc] ."<br>\n";
       if ($this->dbc->createsequence($f) == -1) {
       } else { 
         echo "<span class=\"found\">Sequence <b>". $f[name] ."</b> created</span><br>\n";
       }
       flush();
     }

     # Update the DB entry
     # The current DB entry is always that with the ID 1
     $this->dbc->db->read_update();

     echo "DB Version:". $this->dbc->db->version ."<br>";
     echo "TUTOS Version:". $tutos[version] ."<br>";
     $oldv =  ereg_replace("[a-z\.]","",$this->dbc->db->version);
     $newv =  ereg_replace("[a-z\.]","",$tutos[version]);
     if ( $oldv < 1120021026 ) {
       #special update
       $updatefailed = false;
       echo "<h3>Special update for ". $tutos[version] ."</h3><br>";
       $tmpname = 'acl_temp';
       # drop temp table
       if ($this->dbc->droptable($tmpname) == -1) {
       }
       if ($this->dbc->dropindex($tableidx['acl1']) == -1) {
       }
       if ($this->dbc->dropindex($tableidx['acl2']) == -1) {
       }
       # rename acl
       if ($this->dbc->renametable($table['acl'],$tmpname) == -1) {
         $updatefailed = true;
         echo "Table  not renamed:<span class=\"warn\">". $this->dbc->lasterror ."</span><br>\n";
       }
       # create acl
       if ($this->dbc->createtable($table['acl']) == -1) {
         $updatefailed = true;
         echo "Table  not created:<span class=\"warn\">". $this->dbc->lasterror ."</span><br>\n";
       }
       if ($this->dbc->createindex($tableidx['acl1']) == -1) {
         $updatefailed = true;
         echo "Index  not created:<span class=\"warn\">". $this->dbc->lasterror ."</span><br>\n";
       }
       if ($this->dbc->createindex($tableidx['acl2']) == -1) {
         $updatefailed = true;
         echo "Index  not created:<span class=\"warn\">". $this->dbc->lasterror ."</span><br>\n";
       }
       # fill
       $q = "insert into ". $this->dbc->prefix . $table['acl'][name] ." (obj_id,adr_id,perm)";
       if ( $this->dbc->getType() == "Postgres" ) {
         $q .= " select obj_id,adr_id,int4(perm) from ". $this->dbc->prefix . $tmpname;
       } else {
         $q .= " select obj_id,adr_id,perm from ". $this->dbc->prefix . $tmpname;
       }
       $this->dbc->Exec($q,0);
       if ( $updatefailed ) {
         echo "Update failed<br>\n";
       }
     }

     $this->dbc->db->SetVersion($tutos[version]);
     $this->dbc->db->save();


     echo $this->error("If everything works disable this script");
   }

   /**
    * navigate
    */
   Function navigate() {
   }
   /**
    * prepare
    */
   Function prepare() {
     global $table,$sequence,$tableidx,$tutos,$msg,$lang;


     $this->nomenu = true;

     if ( ! minimum_version(minimumversion) ) {
       $msg .= "<br>\nSorry TUTOS needs a PHP Version >=  ". minimumversion ."<br>\n";
       $msg .= "you are using ". phpversion() ."<br>\n";
       $this->stop = true;
     }

     if (  isset($_GET['id']) ) {
       if ( $this->user->id == -1 ) {
          ReadLang($lang);
       }
       if ( isset($tutos[dbname][$_GET['id']] ) ) {
         $this->dbc = DB_Open($_GET['id'],false);
       } else {
         $msg .= "<br>The given database id ". $_GET['id'] ." is not defined in you configuration";
         $this->stop = true;
       }
     } else {
       /* Check if user is allowed to use it */
       $msg .= "<br>Please add a Database ID like ?id=0 to the URL";
       $this->stop = true;
     }


     # read the table definitions of modules
     foreach ($tutos[modules] as $r => $x) {
	   if (isset($tutos[modules][$r][file]) && file_exists(dirname($tutos['base'] ."/". $tutos[modules][$r][file]) ."/mtable.pinc") ) {
         @include_once dirname($tutos['base'] ."/". $tutos[modules][$r][file]) ."/mtable.pinc";
       }
     }
     $this->name = $lang['DBUpdate'];

     if ( $tutos[demo] != 0 ) {
       $msg .= "will not work in demo";
       $this->stop = true;
     }
   }
 }

 $dbc = null;
 $x = new tutos_user($dbc);
 $current_user = &$x;

 $l = new admin_update($x);
 $l->display();
?>
<!--
    CVS Info:  $Id: update.php,v 1.16 2005/05/03 13:18:49 saraj Exp $
    $Author: saraj $
-->
