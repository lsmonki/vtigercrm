<?php
/**
 * Copyright 1999 - 2004 by Gero Kohnert
 *
 *  CVS Info:  $Id$
 *  $Author$
 *
 * A four week calendar 
 *
 * @modulegroup appointment
 * @module app_show
 * @package appointment
 */
 global $calpath,$callink;
 
 include_once $calpath .'webelements.p3';
 include_once $calpath .'permission.p3';
 include_once $calpath .'appointment.pinc';
 include_once $calpath .'product.pinc';


 /* --------------------------------------------------------------------------- */
 /* Check if user is allowed to use it */
 check_user();
 loadmodules('appointment','show');
 loadlayout();
 global $theme;
 $theme_path="themes/".$theme."/";
 
 require_once ($theme_path."layout_utils.php");
 global $mod_strings;
 
 echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_APPOINTMENT_DETAIL'], true); 
// echo "\n<BR>\n";

 /**
  * display a appointment
  */
 class app_show extends layout {
   /**
    * the data display part
    */
   Function info() {
     global $tutos,$lang;
	 global $mod_strings,$callink;

     if ( $this->format == "ical") {
       Header("Expires: 0");
       Header("Pragma: no-cache");
       # set content to text/calendar
       if (strstr($_SERVER["HTTP_USER_AGENT"],"MSIE 5") || strstr($_SERVER["HTTP_USER_AGENT"],"MSIE 6") ) {
         Header("Cache-Control:");
         Header("Connection: close");
         Header("Content-Transfer-Encoding: binary");
       }
       Header("Content-type: text/calendar");
       Header("Content-Disposition: attachment; filename=\"appointment.ics\"");
       Header("Content-Description: Upload from TUTOS" );
       echo $this->obj->getIcal();
       return;
     } else if ( $this->format == "xml") {
       echo $this->obj->exportXMLHeader();
       echo $this->obj->exportXML();
       return;
     }

     //echo $this->DataTableStart();
	 echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"5\" width=\"90%\">\n";
     echo "<tr>\n";
     //echo "<th colspan=\"3\">". $lang['Calendar'] ."</th>\n";
     //echo $this->user->layout->showGuiIcons("<th>","</th>",$this->obj,$this->obj,array("mod","del"),false);
	 echo "<td><input type=\"button\" class=\"button\" onclick=\"callAction('edit')\" value=\"". $lang['Modify'] ."\">&nbsp;";
	 echo "<input type=\"button\" class=\"button\" onclick=\"callAction('del')\" value=\"". $lang['Delete'] ."\"></td>";
     echo "</tr>\n";
	 echo "</table>\n";
	 
	 echo "<script>\n";
	 echo "function callAction(act) {\n";
	 echo "  if (act==\"del\") {\n";
	 echo "     if (confirm(\"". $lang['ConfirmDelete'] ."\"))\n";
	 echo "       document.location.href=\"". $callink ."app_del&id=". $this->obj->id ."\"\n";
	 echo "  } else {\n";
	 echo "       document.location.href=\"". $callink ."app_new&id=". $this->obj->id ."\"\n";
	 echo "  }\n";
	 echo "}\n";
	 echo "</script>\n";
	 
	 echo "<table class=\"single\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" width=\"90%\">\n";
	 echo "<tr><td class=\"inner\">\n";
	 echo "<table border=\"0\" cellpadding=\"5\" cellspacing=\"1\" width=\"100%\">\n";
     echo "<tr>\n";
     echo " <td class=\"desc\">". $lang['AppCreatedBy'] .":</td>\n";
     echo " <td colspan=\"5\">". $this->obj->creator->getLink() ."&nbsp;". $lang['atDateTime'] ."&nbsp;". $this->obj->creation->getLinkDateTime() ."</td>\n";
     echo "</tr>\n";

     echo "<tr>\n";
     if ( $this->obj->repeat == 0 ) {
       echo $this->showfield($lang['StartDate']);
     } else {
       echo $this->showfield($lang['AppFirstDate']);
     }

     $dinfo = GetDaysInfo( $this->obj->start->getTimeStamp() );

     if ( ($this->obj->t_ignore != 0) || ($this->obj->repeat != 0) ) {
       echo $this->showdata($this->obj->start->getDate(),2);
     } else {
       echo $this->showdata($this->obj->start->getDateTime(),2);
     }

     echo "<td><span class=\"dinfo\">";
     if ( isset ($dinfo[Desc]) ) {
       echo $dinfo[Desc];
     }
     echo "</span>&nbsp;</td>\n";
     echo "</tr>\n";

     echo "<tr>\n";
     if ( $this->obj->repeat == 0 ) {
       echo $this->showfield($lang['EndDate']);
     } else {
       echo $this->showfield($lang['AppLastDate']);
     }
     $dinfo = GetDaysInfo( $this->obj->end->getTimeStamp() );

     if ( ($this->obj->t_ignore != 0) || ($this->obj->repeat != 0) ) {
       if ( ($this->obj->repeat != 0) && ($this->obj->r_ignore == 1) ) {
         echo  $this->showdata($lang['AppNoLastDate'],2);
       } else {
         echo  $this->showdata($this->obj->end->getDate(),2);
       }
     } else {
       echo  $this->showdata($this->obj->end->getDateTime(),2);
     }
     echo "<td><span class=\"dinfo\">";
     if ( isset ($dinfo[Desc]) ) {
       echo $dinfo[Desc];
     }
     echo "</span>&nbsp;</td>\n";
     echo "</tr>\n";

     if ( $this->obj->repeat != 0 ) {
       if ( $this->obj->t_ignore != 1 ) {
         echo "<tr>\n";
         echo $this->showfield($lang['StartTime']);
         echo $this->showdata($this->obj->start->getTime());
         echo $this->showfield($lang['EndTime']);
         echo $this->showdata($this->obj->end->getTime());
         echo "</tr>\n";
       }
       echo "<tr>\n";
       echo $this->showfield($lang['AppRepeatType']);
       echo " <td colspan=\"3\">\n";
       if ( $this->obj->repeat == APP_REP_WEEK ) {
         $wd = strftime("Day%w",$this->obj->start->getTimeStamp());
         echo sprintf($lang['AppRepeatInfo1'],$lang[$wd]);
       }
       if ( $this->obj->repeat == APP_REP_MONTH ) {
         echo strftime($lang['AppRepeatInfo2'],$this->obj->start->getTimeStamp());
       }
       if ( $this->obj->repeat == APP_REP_YEAR ) {
         echo strftime($lang['AppRepeatInfo3'],$this->obj->start->getTimeStamp());
       }
       if ( $this->obj->repeat == APP_REP_DAY ) {
         echo $lang['AppRepeatInfo4'];
       }
       echo " </td>\n";
       echo "</tr>\n";
     }
     echo "<tr>\n";
     echo $this->showfield($lang['Location2']);
     echo $this->showdata($this->obj->getLocation(),3);
     echo "</tr>\n";

     if ( $this->obj->remember > 0 ) {
       echo "<tr>\n";
       echo $this->showfield("reminder at");
       echo $this->showdata($lang['AppRemind'][$this->obj->remember],3);
       echo "</tr>\n";
     }

     if ( $this->obj->descr != "" ) {
       echo "<tr>\n";
       echo $this->showfield($lang['Description']);
       $descr = eregi_replace("\n","<br>",$this->obj->descr);
       echo $this->showdata(urlReplace($descr),3);
       echo "</tr>\n";
     }

     $pre = "";
     if ( count($this->obj->participant) > 0 ) {
       foreach ($this->obj->participant as $i => $f) {
         echo "<tr>\n";
         if ( $pre == "" ) {
           echo $this->showfield($lang['Participants']);
         } else {
           echo "<td>&nbsp;</td>";
         }
         if ( $this->obj->trace == 1 ) {
           echo " <td colspan=\"2\">". $f->getLink() ."</td>";
           echo "<td>". $lang['AppState'][$this->obj->participant_state[$i]] ."</td>";
         } else {
           echo " <td colspan=\"3\">". $f->getLink() ."</td>";
         }
         echo "</tr>\n";
         $pre = 1;
       }
     }
     if ( $this->obj->visitor != -1 ) {
       echo "<tr>\n";
       if ( $this->obj->outside ) {
         echo $this->showfield($lang['VisitAt']);
       } else {
         echo $this->showfield($lang['VisitFrom']);
       }
       echo $this->showdata($this->obj->visitor->getLink(),3);
       echo "</tr>\n";
     }
     if ( $this->user->feature_ok(useprojects,PERM_SEE) ) {
       if ( $this->obj->product != -1 ) {
         echo "<tr>\n";
         echo $this->showfield($lang['ProductP']);
         echo $this->showdata($this->obj->product->getLink(),3);
         echo "</tr>\n";
       }
     }
     # References to modules
     show_module_infolists($this->user,$this->obj,4,$this->format);
     //echo $this->obj->getHistoryLink(4);
     //echo $this->DataTableEnd();
	 echo "</table>\n";
	 echo "</td></tr></table>\n";
   }
   /**
    * naviagation
    */
   Function navigate() {
   }
   /**
    * prepare
    */
   Function prepare() {
     global $msg,$tutos, $lang, $callink;

     $this->name = $lang['AppointDetail'];
     $this->obj = new appointment($this->dbconn);
     if ( isset($_GET['id']) ) {
       $this->obj = $this->obj->read($_GET['id'],$this->obj);
     } else {
       $this->stop = true;
     }
     if ( ! $this->obj->see_ok() ) {
       $msg .= sprintf($lang['Err0022'],$lang[$this->obj->getType()]);
       $this->stop = true;
     } else if ( $this->obj->id < 0 ) {
       $msg .= sprintf($lang['Err0040'],$lang[$this->obj->getType()]);
       $this->stop = true;
     }
     $this->obj->read_participants();
     # menu
     $m = appointment::getSelectLink($this->user);
     $m[category][] = "obj";
     $this->addmenu($m);
     $m = appointment::getAddLink($this->user,$this->obj);
     $this->addMenu($m);
     if ( ($this->obj->id > 0) && $this->obj->mod_ok() ) {
       $m = array( text => $lang['Modify'],
                   info => $lang['ModOldAppoint'],
                   category => array("app","mod","obj")
                 );
       if ( $this->obj->repeat == 0 ) {
         $m[url] = $callink ."app_new&id=". $this->obj->id;
       } else {
         $m[url] = $callink ."app_new_r&id=". $this->obj->id;
       }
       $this->addMenu($m);
     }
     if ( $this->obj->see_ok() && ($this->obj->id > 0) ) {
       $m = array( url => $callink ."app_show&format=ical&id=".$this->obj->id,
                   text => $lang['AppGetIcal'],
                   info => "get this appointment as Ical file",
                   category => array("app","view")
                 );
       $this->addMenu($m);
     }
     add_module_addlinks($this,$this->obj);

     # add this page to the browsehistory of the users session
     $this->addtohistory();
   }
 }

 $l = new app_show($current_user);
 $l->display();
 $dbconn->Close();
?>
