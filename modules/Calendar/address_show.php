<?php
/**
 * Copyright 1999 - 2004 by Gero Kohnert
 *
 *   CVS Info:  $Id$
 *   $Author$
 *
 * @modulegroup address
 * @module adr_ldap_overview
 * @package address
 */
 
 global $calpath, $callink;

 include_once $calpath .'webelements.p3';
 include_once $calpath .'permission.p3';
 include_once $calpath .'address_tools.p3';
 include_once $calpath .'product.pinc';
 include_once $calpath .'appointment.pinc';

 /* Check if user is allowed to use it */
 check_user();
 loadmodules("address","show");
 loadlayout();
 /**
  * display a address
  */
 class address_show extends layout {
   /**
    * Display a small popup with ready for cut & paste address
    */
   Function popinfo() {
     global $tutos, $lang;
     echo $this->PrintHeader($this->obj->getFullName());
     echo "<pre>\n\n\n";
     if (is_object($this->obj->location[$this->lid]->company) ) {
       echo "  ". myentities($this->obj->location[$this->lid]->company->getFullName()) ."\n";
     }
     if (is_object($this->obj->location[$this->lid]->department) ) {
       echo "  ". myentities($this->obj->location[$this->lid]->department->getFullName()) ."\n";
     }
     if (isset($this->obj->title)) {
       echo "  ". myentities($this->obj->title) ."\n";
     }
     echo "  ". myentities($this->obj->getFullName()) ."\n";
     echo "  ". myentities($this->obj->location[$this->lid]->street1) ."\n";
     echo "  ". myentities($this->obj->location[$this->lid]->street2) ."\n";
#ToDo: localize this
     $adrformat = "%s - %s %s";
     echo "  ". sprintf($adrformat,$this->obj->location[$this->lid]->country , $this->obj->location[$this->lid]->zip , $this->obj->location[$this->lid]->city )."\n";
     echo "  ". $lang['countrycde'][$this->obj->location[$this->lid]->country] ."\n";
     echo "\n\n\n";
     echo "  ". $lang['Phone'] .":\t".myentities($this->obj->location[$this->lid]->phone_1) ."\n";
     echo "  ". $lang['MobilePhone'] .":\t".myentities($this->obj->location[$this->lid]->phone_2) ."\n";
     echo "  ". $lang['AdrFax'] .":\t".myentities($this->obj->location[$this->lid]->fax_1) ."\n";
     echo "\n\n\n";
     echo "  ". $lang['AdrEmail'] .":\t".myentities($this->obj->location[$this->lid]->email_1) ."\n";
     echo "  ". $lang['AdrEmail'] .":\t".myentities($this->obj->location[$this->lid]->email_2) ."\n";

     echo "</pre>\n";
     $this->PrintFooter();
   }
   /**
    *
    */
   Function info() {
     global $tutos, $lang;

     if ( $this->format == "vcard" ) {
       $b =  $this->obj->getVcard();
       Header("Expires: 0");
       Header("Pragma: no-cache");
       # set content to text/calendar
       if (strstr($_SERVER["HTTP_USER_AGENT"],"MSIE 5") || strstr($_SERVER["HTTP_USER_AGENT"],"MSIE 6") ) {
         Header("Cache-Control:");
         Header("Connection: close");
         Header("Content-Transfer-Encoding: binary");
       }
       Header("Content-type: text/x-vcard");
       Header("Content-Disposition: attachment; filename=\"". $this->obj->f_name ."_". $this->obj->l_name .".vcf\"");
       Header("Content-Description: Upload from TUTOS" );
       echo $b;
       return;
     } else if ( $this->format == "popup" ) {
       $this->popinfo();
       return;
     } else if ( $this->format == "xml" ) {
       $r =  $this->obj->exportXML();
       echo $this->obj->exportXMLHeader(strlen($r));
       echo $r;
       return;
     }
     $cols = 1;
     if ( count($this->obj->loc) > 0 ) {
       $cols = 1 + count($this->obj->loc);
     } else {
       $cols++;
     }
     if ( $cols == 2 ) {
       $cols++;
     }
     echo $this->DataTableStart();
     
     # Header for Base Address Data
     echo "<tr><th colspan=\"". ($cols - 1) ."\">". $lang['AddressEntries'] ."</th>";
     # myparentobj-parameter not needed, but must be filled. Dont know how to leave it blank.
     echo $this->user->layout->showGuiIcons("<th align=\"right\">","</th>",$this->obj,$this->obj,array("mod","del"),true);
     echo "</tr>\n";
     
     Show_LocFields($this->user,$lang['AdrTitle'],$this->obj,"title",0);
     Show_LocFields($this->user,$lang['AdrFirstName'],$this->obj,"f_name",0);
     Show_LocFields($this->user,$lang['AdrMiddleName'],$this->obj,"m_name",0);
     Show_LocFields($this->user,$lang['AdrLastName'],$this->obj,"l_name",0);
     Show_LocFields($this->user,$lang['AdrBirthday'],$this->obj,"birthday",0);

     # References to modules
     show_module_infolists($this->user,$this->obj,$cols,$this->format);
     
     # Location's Header and Direkt Links to Modify and Delete
     echo "<tr><td></td></tr>\n";
     echo "<tr><td></td></tr>\n";
     Show_LocFields($this->user,'',$this,"ModifyDelete",9);
     
     Show_LocFields($this->user,$lang['AdrDescription'],$this->obj,"lname",1);
     Show_LocFields($this->user,$lang['Company'],$this->obj,"c_id",1);
     Show_LocFields($this->user,$lang['Department'],$this->obj,"d_id",1);
     Show_LocFields($this->user,$lang['Description'],$this->obj,"desc1",1);
     Show_LocFields($this->user,$lang['Phone'],$this->obj,"phone_1",1);
     Show_LocFields($this->user,$lang['MobilePhone'],$this->obj,"phone_2",1);
     Show_LocFields($this->user,$lang['AdrFax'],$this->obj,"fax_1",1);
     Show_LocFields($this->user,$lang['AdrEmail'] ." #1",$this->obj,"email_1",1);
     Show_LocFields($this->user,$lang['AdrEmail'] ." #2",$this->obj,"email_2",1);
     Show_LocFields($this->user,$lang['Street'],$this->obj,"street1",1);
     Show_LocFields($this->user,$lang['Street'],$this->obj,"street2",1);
     Show_LocFields($this->user,$lang['City'],$this->obj,"city",1);
     Show_LocFields($this->user,$lang['ZIP'],$this->obj,"zip",1);
     Show_LocFields($this->user,$lang['State'],$this->obj,"state",1);
     Show_LocFields($this->user,$lang['Country'],$this->obj,"country",1);

     team::infolist($this->user,$this->obj,$cols,$this->format);
     product::infolist($this->user,$this->obj,$cols,$this->format);
     appointment::infolist($this->user,$this->obj,$cols,$this->format);

     echo $this->obj->getHistoryLink($cols);
     echo $this->DataTableEnd();
   }
   /**
    * navigation
    */
   Function navigate() {
   }
   /**
    * prepare
    */
   Function prepare() {
     global $msg,$lang,$g_hash;

     $this->name = $lang['AddressDetail'];
     $this->lif = -1;
     $this->obj = new tutos_address($this->dbconn);
     if ( isset($_GET['id']) ) {       
       $this->obj = $this->obj->read($_GET['id'],$this->obj);
     }
     if ( isset($_GET['lid']) ) {
       $this->lid = $_GET['lid'];
       $q = "SELECT adr_id FROM ". $this->dbconn->prefix ."adrloc WHERE loc_id = ". $this->lid;
       $r = $this->dbconn->Exec($q);
       if ($r->numrows() == 1) {
         $id = $r->get(0, "adr_id");
         $this->obj = $this->obj->read($id,$this->obj);
         if ( $this->obj->id < 0 ) {
           $this->obj = new company($this->dbconn);
           $this->obj = $this->obj->read($id,$this->obj);
         }
         if ( $this->obj->id < 0 ) {
           $this->obj = new department($this->dbconn);
           $this->obj = $this->obj->read($id,$this->obj);
         }
       }
       $r->free();
     }

     if ( ! $this->obj->see_ok() ) {
       $msg .= sprintf($lang['Err0022'],$lang[$this->obj->getType()]);
       $this->stop = true;
       $this->format = "html";
     } else if ( $this->obj->id < 0 ) {
       $msg .= sprintf($lang['Err0040'],$lang[$this->obj->getType()]);
       $this->stop = true;
       $this->format = "html";
     } else {
       $this->obj->read_locs_data();
       $this->name = $lang['AddressDetail'].": ". $this->obj->getFullName();
     }
     # Menu      
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
     if ( $this->obj->id != -1 ) {
       if ( $this->obj->mod_ok() ) {
         if ( count($this->obj->location) > 0 ) {
           @reset($this->obj->location);
           list ($j,$l) = @each ($this->obj->location);
           $url = "address_new.php?id=". $this->obj->id ."&loc_id=". $l->id;
         } else {
           $url = "address_new.php?id=". $this->obj->id;
         }
         $x = array( url => $url,
                     text => $lang['Modify'],
                     info => $lang['Modify'],
                     category => array("address","new","obj")
                   );
         $this->addMenu($x);
       }
     }

     if ( $this->user->feature_ok(useaddressbook,PERM_NEW) ) {
       $x = array( url => "company_new.php",
                   text => $lang['CompanyCreate'],
                   info => $lang['CompanyCreateInfo'],
                   category => array("company","new","support")
                 );
       $this->addMenu($x);
       $x = array( url => "department_new.php",
                   text => $lang['DepartmentCreate'],
                   info => $lang['DepCreateInfo'],
                   category => array("department","new","support")
                 );
       $this->addMenu($x);
     }

     if ($this->obj->id > 0) {
       if ( $this->user->feature_ok(usevcard,PERM_SEE) ) {
         $x = array( url => $this->obj->getUrl() ."&amp;format=vcard",
                     text => $lang['AdrGetVcard'],
                     info => $lang['AdrGetVcardI'],
                     category => array("address","view")
                   );
         $this->addMenu($x);
       }
       if ( $this->obj->use_ok() && $this->user->feature_ok(usetimetrack,PERM_SEE) ) {
         $x = array( url => "timetrack_overview.php?worker=".$this->obj->id,
                     text => $lang['TimetrackBooked'],
                     info => sprintf($lang['TimetrackBookedI'],$this->obj->getFullName()),
                     category => array("timetrack","module")
                   );
         $this->addMenu($x);
       }
       if ( $this->obj->use_ok() && $this->user->feature_ok(usetaskmanagement,PERM_SEE) ) {
         $x = array( url => "task_overview.php?id=".$this->obj->id,
                     text => $lang['TaskOverview'],
                     info => sprintf($lang['TaskOverviewInfo'],$this->obj->getFullName()),
                     category => array("task","module")
                   );
         $this->addMenu($x);
       }
       if ( $this->obj->use_ok() && $this->user->feature_ok(usetaskmanagement,PERM_SEE) &&  $this->user->feature_ok(usecalendar,PERM_SEE) ) {
         $x = array( url => "res_cal.php?id=".$this->obj->id,
                     text => $lang['ResCal'],
                     info => $lang['ResCal'],
                     category => array("timetrack","task","module")
                   );
         $this->addMenu($x);
       }
       if ( $this->obj->isUser()  &&  $this->user->feature_ok(usecalendar,PERM_SEE) ) {
         $x = array( url => "calendar.php?team=". $this->obj->id,
                     text => $lang['Calendar'],
                     info => sprintf($lang['ShowCal'], $this->obj->getFullName()),
                     category => array("app","module")
                   );
         $this->addMenu($x);
       }
     }
     add_module_addlinks($this,$this->obj);

     # add this page to the browsehistory of the users session
     $this->addtohistory();
     web_StackStartLayoutObj($this);
   }
 }

 $l = new address_show($current_user);
 $l->display();
 $dbconn->Close();
?>
