<?php
/**
 * Copyright 1999 - 2002 by Gero Kohnert
 *
 * @modulegroup appointment
 * @module app_select
 * @package appointment
 */
 include_once 'webelements.p3';
 include_once 'permission.p3';
 include_once 'appointment.pinc';

 /* Check if user is allowed to use it */
 check_user();
 loadmodules("appointment","select");
 loadlayout();

 /**
  * display a appointment selection form
  */
 class app_select extends layout {
   /**
    *
    */
   Function info() {
     global $lang,$tutos;

     echo "<br><br><center>\n";

     echo "<form name=\"appsearch\" action=\"app_overview.php\" method=\"GET\">\n";
     DoubleTableStart();

     echo "<tr>\n";
     if ( $this->ext == false ) {
       echo "<th colspan=\"3\">". $lang['SearchForApp'] ."</th>\n";

       echo "</tr><tr>\n";

       echo $this->showfieldc($lang['AdrName']);
       echo " <td align=\"center\">&nbsp;<input type=\"text\" value=\"\" name=\"name\"></td>\n";
       echo " <td align=\"center\">\n";
       echo " <input type=\"submit\" value=\"". $lang['Search'] ."\"></td>\n";
       echo "</tr><tr>\n";
       echo " <td align=\"center\" colspan=\"2\"><font size=\"-1\">". $lang['AppSelInfo'] ."</font></td>\n";
       echo " <td align=\"center\">". makelink("app_select.php?ext=1",$lang['extended']) ."</td>\n";
     } else {
       echo "<th colspan=\"3\">". $lang['SearchForAppExt'] ."</th>\n";

       echo "</tr><tr>\n";
       echo $this->showfieldc($lang['AdrName']);
       echo " <td>&nbsp;<input type=\"text\" value=\"\" name=\"name\">";
       echo " </td>\n";
       echo " <td align=\"center\"><input type=\"submit\" value=\"". $lang['Search'] ."\"></td>\n";
  
       echo "</tr><tr>\n";
       echo " <td rowspan=\"2\" class=\"desc\">". $lang['TimetrackBetween'] ."</td>\n";
       echo " <td colspan=\"2\">&nbsp;";
       $this->asfd->EnterDate("f",1);
       echo "&nbsp;</td>\n";

       echo "</tr><tr>\n";
       echo " <td colspan=\"2\">&nbsp;";
       $this->astd->EnterDate("t",1);
       echo "&nbsp;</td>\n";

       echo "</tr><tr>\n";
       echo $this->showfieldc($lang['Location2'],"loc");
       echo " <td colspan=\"2\">&nbsp;<select name=\"loc\">\n";
       echo " <option value=\"-1\">". $lang['all'] ."</option>\n";
       foreach ($lang['AppLoc'] as $i => $f ) {
         echo "   <option VALUE=\"". $i ."\">". $lang['AppLoc'][$i] ."</option>\n";
       }
       echo " </select></td>\n";
       echo "</tr><tr>\n";
       echo "<td><input id=\"myapps\" type=\"checkbox\" name=\"myapps\" value=\"1\"></td>";
       echo $this->showfieldc($lang['AppUserSearch'])."</td></tr>";
     }

     DoubleTableEnd();
     hiddenFormElements();
	 echo $this->getHidden();
     echo "</form>\n";
     echo "</center>\n";
     echo $this->setfocus("appsearch.name");

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
     global $msg,$lang;
    
     $this->name = $lang['AppsSearch'];

     # Defaults for search
     $this->asfd = new DateTime(0);
     $this->astd = new DateTime(0);

     if ( isset($_SESSION['appsearchfrom']) ) {
       $this->asfd->setDateTime($_SESSION['appsearchfrom']);
     }
     if ( isset($_SESSION['appsearchto']) ) {
       $this->astd->setDateTime($_SESSION['appsearchto']);
     }
     if ( isset($_GET['ext']) ) {
       $this->ext = $_GET['ext'];
     } else {
       $this->ext = false;
     }
     # menu
     $m = appointment::getAddLink($this->user,$this->user);
     $this->addMenu($m);
   }
 }

 $l = new app_select($current_user);
 $l->display();
 $dbconn->Close();
?>
<!--
    CVS Info:  $Id$
    $Author$
-->
