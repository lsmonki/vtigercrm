<?php
/**
 * Copyright 1999 - 2004 by Gero Kohnert
 *
 *  CVS Info:  $Id$
 *  $Author$
 *
 * @modulegroup appointment
 * @module app_overview
 */
 include_once 'webelements.p3';
 include_once 'permission.p3';
 include_once 'appointment.pinc';
 include_once 'product.pinc';
 include_once 'group/group.pinc';

 /* Check if user is allowed to use it */
 check_user();
 loadmodules("appointment","overview");
 loadlayout();

 /**
  * show a overview of appointments
  */
 class app_overview extends layout {
   /**
    * display the info
    */
   Function info() {
     global $lang,$tutos;

     $n = $this->result->numrows();

     if ( $this->format == "xml" ) {
       $a = new appointment($this->dbconn);
       echo $a->exportXMLHeader();
       echo $a->exportXML_head();
       echo "<appointment_set>\n";
       $x = 0;
       while ( $x < $n ) {
         $a = new appointment($this->dbconn);
         $a->read_result($this->result,$x);
         echo $a->exportXML(false);
         $x++;
       }
       echo "</appointment_set>\n";
     } else {
     echo $this->actionformStart("app_overview.php");
     echo $this->OverviewTableStart();
     echo "<thead>\n";
     echo "<tr>\n";
     echo $this->orderHeader("","ID",$this->link2);
     echo $this->orderHeader("a_start",$lang['AppStart'],$this->link2);
     echo $this->orderHeader("a_end",$lang['AppEnd'],$this->link2);
     echo $this->orderHeader("","&reg;",$this->link2);
     echo $this->orderHeader("description",$lang['Description'],$this->link2);
     echo $this->orderHeader("outside",$lang['Location2'],$this->link2);
     echo $this->orderHeader("product",$lang['ProductP'],$this->link2);
     echo $this->orderHeader("",$lang['Participants'],$this->link2);
     if ( $tutos[massupdate] == 1 ) {
       echo "  <th nowrap=\"nowrap\"><input type=\"checkbox\" name=\"checkit\" onclick=\"CheckAll2();\" /></th>\n";
     }
     echo "</tr>\n";
     echo "</thead>\n";

     if ( $this->start == -1 ) {
       $a = $n - $tutos[maxshow];
       $end = $n;
       $this->start = $a;
     } else {
       $a = $this->start;
       $end = $this->start + $tutos[maxshow];
     }
     $line = 0;
     while ( ($a < $n) && ($a < $end) ) {
       $f = new appointment($this->dbconn);
       $f->read_result($this->result,$a);
       $f->read_participants();
       $a++;
       if ( ! $f->see_ok() ) {
         continue;
       }
       echo $this->OverviewRowStart($line);
       echo " <td valign=\"top\" align=\"right\">". $f->getLink($a) ."</td>\n";

       if ( $f->t_ignore == 0) {
         if ( $f->start->getDateTime() == $f->end->getDateTime() ) {
           echo " <td valign=\"top\" colspan=\"2\" align=\"left\">". $f->start->getDateTime() ."</td>\n";
         } else {
           echo " <td valign=\"top\">". $f->start->getDateTime() ."</td>\n";
           echo " <td valign=\"top\">". $f->end->getDateTime() ."</td>\n";
         }
       } else {
         if ( $f->start->getDate() == $f->end->getDate() ) {
           echo " <td valign=\"top\" colspan=\"2\" align=\"left\">". $f->start->getDate() ."</td>\n";
         } else {
           echo " <td valign=\"top\">". $f->start->getDate() ."</td>\n";
           echo " <td valign=\"top\">". $f->end->getDate() ."</td>\n";
         }
       }
       if ( $f->repeat ) {
         echo " <td valign=\"top\">&reg;</td>\n";
       } else {
         echo " <td valign=\"top\">&nbsp;</td>\n";
       }
       $x = myentities($f->descr);
       if ( $this->filter['name'] != "" ) {
         $x = eregi_replace("(". $this->filter['name'] .")","<span class=\"found\">\\1</span>",$x);
       }
       echo " <td valign=\"top\">".$x."&nbsp;</td>\n";
       echo " <td valign=\"top\">".  $f->getLocation() ."</td>\n";
       if ( $f->product->id > 0 ) {
         echo " <td valign=\"top\">". $f->product->getLink() ."</td>\n";
       } else {
         echo " <td valign=\"top\">&nbsp;</td>\n";
       }
       echo " <td valign=\"top\">\n";
       $pre = "";
       foreach ($f->participant as $x ) {
         echo $pre.$x->getLink() ;
         $pre = "<br />" ;
       }
       echo "&nbsp;</td>\n";

       if ( $tutos[massupdate] == 1 ) {
         echo " <td align=\"center\">\n";
         if ( $f->mod_ok() ) {
           echo "<input name=\"mark[]\" type=\"checkbox\" value=\"". $f->id ."\" />\n";
         } else {
           echo "-\n";
         }
         echo "</td>\n";
       }

       echo $this->OverviewRowEnd($line++);
       unset($f);
     }

     echo $this->list_navigation($this->link1,8 + $tutos[massupdate],$this->start,$a,$n);

     if ( $tutos[massupdate] == 1 ) {
       echo $this->UpdateRowStart(7);
       echo sprintf($lang['withmarked'],$lang['Appointments']);
       echo "&nbsp;<select name=\"action\">\n";
       echo " <option value=\"-1\" selected=\"selected\">". $lang['ActionNil'] ."</option>\n";
       echo " <option value=\"-2\">". $lang['Delete'] ."</option>\n";
       echo "</select>\n";
       echo $this->UpdateRowEnd(2);
     }

     echo $this->OverviewTableEnd();
     echo $this->actionformEnd("app_overview.php");
     }  
     $this->result->free();
   }
   /**
    * navigate
    */
   Function navigate() {
   }
   /**
    * action
    */
   Function action() {
     global $lang,$msg;

     @reset($_GET['mark']);
     if ( $_GET['action'] == -2 ) {
       $this->dbconn->Begin("WORK");
       while (list ($key,$val) = @each ($_GET['mark'])) {
         $b = new appointment($this->dbconn);
         $b = $b->read($val,$b);
         if ( $b->id != $val ) {
           continue;
         }
         if ( $b->del_ok() ) {
           $msg .= $lang['Delete'] ."&nbsp;". $b->getFullName() ."<br />";
           $msg .= $b->delete();
         } else {
           $msg .= $b->getLink() .": ". sprintf($lang['Err0023'],$lang[$b->getType()]) ."<br />";
         }
         unset($b);
       }
       $this->dbconn->Commit("WORK");
     }
   }
   /**
    * prepare
    */
   Function prepare() {
     global $lang,$msg;

     $this->name = $lang['AppointOverview'];
     $this->link1 = "app_overview.php";
     $this->filter = array();
     $this->filter['name'] = "";
     if ( isset($_GET['myapps']) && ($_GET['myapps'] = 1) ) {
       $this->q = "SELECT * from ". $this->dbconn->prefix ."calendar c, ". $this->dbconn->prefix ."participants p";
       $pre = " WHERE c.id = p.app_id AND ";
       team::obj_read($obj);
       $this->q .= $pre."p.adr_id in ( ". $this->user->id;
       foreach ( $this->user->teamlist as $i => $f) {
         $this->q .= ",". $i;
       }
       $this->q .= ")";
       $pre = " AND ";
       $this->link1 = addUrlParameter($this->link1,"myapps=".$_GET['myapps']);
       $this->addHidden("myapps",$_GET['myapps']);
       $this->filter['myapps'] = $_GET['myapps'];
     } else {
       $this->q = "SELECT * from ". $this->dbconn->prefix ."calendar";
       $pre = " WHERE";
     }
     #
     # Text Search
     #
     if ( isset($_GET['name']) && !empty($_GET['name']) && ($_GET['name'] != "*") ) {
       $this->q .= " " . $pre ."(". $this->dbconn->Like("description",$_GET['name']) .")";
       $pre = " AND";
       $this->link1 = addUrlParameter($this->link1,"name=". urlencode($_GET['name']));
       $this->addHidden("name",$_GET['name']);
       $this->filter['name'] = $_GET['name'];
     }
     #
     # Show all apps with given link (addr,company or department)
     #
     if ( isset($_GET['link_id']) ) {
       if (false == is_numeric($_GET['link_id'])) {
         $msg .= sprintf($lang['Err0012'],"link_id",$_GET['link_id']);
         $this->stop = true;
       }
       $obj = getObject($this->dbconn,$_GET['link_id']);
       if ( $obj->id > 0 ) {
         if ( $obj->getType() == "product" ) {
           $this->q .= $pre . " product = ". $_GET['link_id'];
           $pre = " AND";
         } else if ( $obj->getType() == "company" ) {
           $obj->read_members();
           $this->q .= $pre . " visitor in (". $_GET['link_id'];
           while ( list ($i,$f) = @each ($obj->member) ) {
             $this->q .= ",". $i;
           }
           $this->q .= " )";
           $pre = " AND";
         } else if ( $obj->getType() == "department" ) {
           $obj->read_members();
           $this->q .= $pre . " visitor in (". $_GET['link_id'];
           while ( list ($i,$f) = @each ($obj->member) ) {
             $this->q .= ",". $i;
           }
           $this->q .= " )";
           $pre = " AND";
         } else if ( $obj->getType() == "address" ) {
           $this->q .= $pre . " visitor = ". $_GET['link_id'];
         }
         $this->name .= " : ".$obj->getFullName();
       }
       $this->link1 = addUrlParameter($this->link1,"link_id=".$_GET['link_id']);
       $this->addHidden("link_id",$_GET['link_id']);
     }
     # Start Date for search
     $from = new DateTime(0);
     if ( isset($_GET['fd']) ) {
       $from->setDateTime($_GET['fd']);
     } else {
       $from->setDateTimeF("f");
       # remember this
#       session_register('appsearchfrom');
       $_SESSION['appsearchfrom'] = $from->getYYYYMMDD();
     }
     if ( $from->notime == 0 ) {
       $this->q .= $pre ."(a_start >= ". $this->dbconn->Date($from) .")";
       $pre = "AND ";
       $this->link1 = addUrlParameter($this->link1,"fd=".$from->getYYYYMMDD());
       $this->addHidden("fd",$from->getYYYYMMDD());
       $this->filter['from'] = $from;
     }
     # End Date for search
     $to = new DateTime(0);
     if ( isset($_GET['td']) ) {
       $to->setDateTime($_GET['td']);
     } else {
       $to->setDateTimeF("t");
       # remember this
#       session_register('appsearchto');
       $_SESSION['appsearchto'] = $to->getYYYYMMDD();
     }
     if ( $to->notime == 0 ) {
       $this->q .= $pre ."(a_end <= ". $this->dbconn->Date($to) .")";
       $pre = " AND ";
       $this->link1 = addUrlParameter($this->link1,"td=".$to->getYYYYMMDD());
       $this->addHidden("td",$to->getYYYYMMDD());
       $this->filter['to'] = $to;
     }
     if ( isset($_GET['loc']) && ($_GET['loc'] != -1) ) {
       $this->q .= $pre ."(outside = ". $_GET['loc'] .")";
       $pre = " AND ";
       $this->link1 = addUrlParameter($this->link1,"loc=".$_GET['loc']);
       $this->addHidden("loc",$_GET['loc']);
       $this->filter['loc'] = $_GET['loc'];
     }

     check_dbacl( $this->q, $this->user->id);

     #
     # display order
     #
     $this->link2 = $this->link1;
     $xxx = "";
     order_parse($this->q,$this->link1,$xxx,$xxx,"a_start ASC");
     # display default sortorder
     if (!isset($_GET['xf'])) {
       $_GET['xf'] = "a_start";
       $_GET['xo'] = 1;
     }
     if ($this->stop) {
       return;
     }
     $this->result = $this->dbconn->Exec($this->q);
     if ( 0 == $this->result->numrows() && $this->format != 'xml') {
       $this->redirect = "app_select.php?msg=". UrlEncode($lang['Err0048']);
       $this->result->free();
     }

     # menu
     $m = appointment::getSelectLink($this->user);
     $m[category][] = "obj";
     $this->addmenu($m);
     if ( isset($obj) ) {
       $m = appointment::getAddLink($this->user,$obj);
     } else {
       $m = appointment::getAddLink($this->user,$this->user);
     } 
     $this->addMenu($m);
   }
 }

 $l = new app_overview($current_user);
 $l->display();
 $dbconn->Close();
?>