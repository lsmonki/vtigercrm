<?php
/*
 * Copyright 1999 - 2004 by Gero Kohnert
 *
 *
 *   CVS Info:  $Id$
 *   $Author$
 *
 */
 include_once 'webelements.p3';
 include_once 'permission.p3';
 include_once 'address_tools.p3';
 include_once 'appointment.pinc';
 include_once 'task.pinc';
 include_once 'product.pinc';

 /* Check if user is allowed to use it */
 check_user();
 loadmodules("address","overview");
 loadlayout();

 /**
  * show a overview of addresses
  */
 class address_overview extends layout {
   /**
    * display the info
    */
   Function info() {
     global $lang;

     if ( $this->format == "xml" ) {
       $n = $this->result->numrows();
       $a = new tutos_address($this->dbconn);
       echo $a->exportXMLHeader();
       echo $a->exportXML_head() ;
       echo "<address_set>\n";
       $x = 0;
       while ( $x < $n ) {
         $a = new tutos_address($this->dbconn);
         $a->read_result($this->result,$x);
         echo $a->exportXML(false);
         $x++;
       }
       echo "</address_set>\n";
     } else {
       address_overview($this,$this->result,$this->ashow,$this->atool,"address_overview.php");
     }
     $this->result->free();
   }
   /**
    * navigate
    */
   Function navigate() {
   }
   /**
    * action via massupdate
    */
   Function action() {
     global $lang,$msg;

     if ( $_GET['action'] == -2 ) {
       $this->dbconn->Begin("WORK");
       foreach ($_GET['mark'] as $key => $val) {
         $b = new tutos_address($this->dbconn);
         $b = $b->read($val,$b);
         if ( $b->id != $val ) {
           continue;
         }
         if ( $b->del_ok() ) {
           $msg .= sprintf($lang['AdrBDelInfo'], $b->getFullName()) ."<br>";
           $msg .= $b->delete();
         } else {
           $msg .= $b->getLink() .": ". sprintf($lang['Err0023'],$lang[$b->getType()]) ."<br>\n";
         }
         unset($b);
       }
       $this->dbconn->Commit("WORK");
     }
     else if ( $_GET['action'] == 'WatchAdd' ) {
       $this->dbconn->Begin("WORK");
       foreach ($_GET['mark'] as $key => $val) {
         $b = new tutos_address($this->dbconn);
         $b = $b->read($val,$b);
         if ( $b->id != $val ) {
           continue;
         }
         $w = new watchlist($this->dbconn);
         $w->add($b,$this->user);
       }
       $this->dbconn->Commit("WORK");
     } else if ( $_GET['action'] == 'WatchDel' ) {
       $this->dbconn->Begin("WORK");
       foreach ($_GET['mark'] as $key => $val) {
         $b = new tutos_address($this->dbconn);
         $b = $b->read($val,$b);
         if ( $b->id != $val ) {
           continue;
         }
         $w = new watchlist($this->dbconn);
         $w->remove($b->id,$this->user->id);
       }
       $this->dbconn->Commit("WORK");
     } else if ( $_GET['action'] == -4 ) {
       $this->redirect = acl_action();
     }
   }
   /**
    * prepare
    */
   Function prepare() {
     global $msg,$tutos,$lang;

     $this->name = $lang['AddressOverview'];

     adr_tool_init($this->atool,$this->ashow);
     if ( $this->user->layout->checkGuiIcons() && count($this->user->get_preficons(1)) ) {
       $this->ashow['icon_before'] = 1;
     }
     $this->ashow['f_name'] = 1;
     $this->ashow['l_name'] = 1;
     $this->ashow['location'] = 1;
     $this->ashow['email'] = 1;
     $this->ashow['phone'] = 1;
     $this->ashow['c_id'] = 1;
     $this->ashow['d_id'] = 1;
     if ( $this->user->layout->checkGuiIcons() && count($this->user->get_preficons(2)) ) {
       $this->ashow['icon_after'] = 1;
     }
     $this->atool['link1'] = "address_overview.php";
     $this->atool['maxshow'] = $tutos[maxshow];

     if ( isset($_GET['astart']) ) {
       $this->atool['start'] = $_GET['astart'];
     } else {
       $this->atool['start'] = 0;
     }

     $this->atool['format'] = $this->format;

     if ( isset($_GET['name']) ) {
       $name = $_GET['name'];
       $this->atool['link1'] = addUrlParameter($this->atool['link1'],"name=".UrlEncode($name));
       $this->addHidden("name",$name);
     } else {
       $name = "";
     }
     if ( isset($_GET['ext']) ) {
       $ext = $_GET['ext'];
       $this->addHidden("ext",$ext);
     }

     $e['name'] = 0;

     #
     # extended search including locations
     #
     if ( isset($ext) && ($name != "*") && ($name != "") ) {
       $q = "SELECT a.adr_id FROM ". $this->dbconn->prefix ."location l, ". $this->dbconn->prefix ."adrloc a WHERE l.id = a.loc_id ";
       $pre = " AND (";
       $aids = "0";

       $_SESSION['adrsearch'] = $ext;

       @reset($ext);
       while( list ($i,$f) = @each ($ext)) {
         if ( $f == "email" ) {
           $q .= " ". $pre ." ". $this->dbconn->Like("email_1",$name) ." ";
           $pre = "OR";
           $q .= " ". $pre ." ". $this->dbconn->Like("email_2",$name) ." ";
           $this->ashow[$f] = 1;
           $this->atool['search'][$f] = $name;
         }
         if ( $f == "phone" ) {
           $q .= " ". $pre ." ". $this->dbconn->Like("phone_1",$name) ." ";
           $pre = "OR";
           $q .= " ". $pre ." ". $this->dbconn->Like("phone_2",$name) ." ";
           $pre = "OR";
           $q .= " ". $pre ." ". $this->dbconn->Like("fax_1",$name) ." ";
           $this->ashow[$f] = 1;
           $this->ashow['fax'] = 1;
           $this->atool['search'][$f] = $name;
         }
         if ( $f == "city" ) {
           $q .= " ". $pre ." ". $this->dbconn->Like("city",$name) ." ";
           $pre = "OR";
           $this->ashow[$f] = 1;
           $this->atool['search'][$f] = $name;
         }
         if ( $f == "street" ) {
           $q .= " ". $pre ." ". $this->dbconn->Like("street1",$name) ." ";
           $pre = "OR";
           $q .= " ". $pre ." ". $this->dbconn->Like("street2",$name) ." ";
           $this->ashow[$f] = 1;
           $this->atool['search'][$f] = $name;
         }
         if ( $f == "desc" ) {
           $q .= " ". $pre ." ". $this->dbconn->Like("desc1",$name) ." ";
           $pre = "OR";
           $this->ashow['desc1'] = 1;
           $this->atool['search']['desc1'] = $name;
         }
         if ( $f == "name" ) {
           $e['name'] = 1;
         }
         $this->atool['link1'] = addUrlParameter($this->atool['link1'],"ext[]=".$f);
       }
       if ( $pre == "OR" ) {
         $q .= ")";
         $result = $this->dbconn->Exec($q);
         $n = $result->numrows();
         $a = 0;
         while ( $a < $n ) {
           $aids = $aids . "," . $result->get($a,"adr_id");
           $a++;
         }
       }
     } else {
       $aids = "";
       if ( ($name != "*") && ($name != "") ) {
         $e['name'] = 1;
       }
     }
     if ( ($name != "*") && ($name != "") ) {
       $name = StripSlashes($name);
       $_SESSION['adrlook'] = $name;
     } else {
       $this->atool['search']['name'] = "";
       $this->atool['search']['phone'] = "";
       $this->atool['search']['email'] = "";
     }

     $q = "SELECT * FROM ". $this->dbconn->prefix ."addresses";
     $pre = "WHERE";

     if ( $aids != "" ) {
       $q .= " ". $pre ." id IN (". $aids .")";
       $pre = " OR ";
     }
     if ( $e['name'] == 1 ) {
       $this->atool['search']['name'] = $name;
       $q .= " ". $pre ."(". $this->dbconn->Like("l_name",$name,"f_name") .")";
       $pre = " OR ";
       $q .= " ". $pre ."(". $this->dbconn->Like("f_name",$name,"l_name") .")";
     }

     check_dbacl( $q, $this->user->id);

     # Searchorders
     $xxx = "";
     $this->atool['link2'] = $this->atool['link1'];
     order_parse($q,$this->atool['link1'],$xxx,$xxx,"l_name");
     # display default sortorder
     if (!isset($_GET['xf'])) {
       $_GET['xf'] = "l_name";
       $_GET['xo'] = 1;
     }

	 if ($this->stop) {
	   return;
	 }
     $this->result = $this->dbconn->Exec($q);

     if ( $this->format != 'xml' ) {
       if ( 0 == $this->result->numrows()) {
         $this->redirect = "address_select.php?msg=". UrlEncode($lang['Err0048']);
         $this->result->free();
       } else if ($this->result->numrows() == 1) {
         $a = new tutos_address($this->dbconn);
         $a->read_result($this->result,0);
         if ( $a->see_ok() ) {
           $this->redirect = "address_show.php?id=".$a->id;
           $this->result->free();
         }
       }
     }
     # menu
     $x = tutos_address::getSelectLink($this->user,$lang['Search']);
     $x[category][] = "obj";
     $this->addMenu($x);

     if ( $this->user->feature_ok(useaddressbook,PERM_NEW) ) {
       $x = array( url => "address_new.php",
                   text => $lang['NewEntry'],
                   info => $lang['AdrCreateInfo'],
                   category => array("address","new","obj")
                 );
       $this->addMenu($x);
     }
     if ( $this->format != "paper" ) {
       $ll = addUrlParameter($this->atool['link1'],"format=paper");
       $x = array( url => $ll,
                   text => $lang['Printable'],
                   info => $lang['PrintableInfo'],
                   category => array("address","view")
                 );
       $this->addMenu($x);
     }
     web_StackStartLayout($this,"address_overview.php","address_overview.php");
   }
 }

 $l = new address_overview($current_user);
 $l->display();
 $dbconn->Close();
?>
