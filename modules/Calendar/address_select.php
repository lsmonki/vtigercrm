<?php
/**
 * Copyright 1999 - 2003 by Gero Kohnert
 *
 * @modulegroup address
 * @module address_select
 * @package address
 */
 include_once 'webelements.p3';
 include_once 'permission.p3';

 /* Check if user is allowed to use it */
 check_user();
 loadmodules("address","select");
 loadlayout();

 /**
  * display a address selection form
  */
 class address_select extends layout {
   /**
    *
    */
   Function info() {
     global $lang, $tutos;

     echo "<br /><br /><center>\n";

     echo "<form name=\"adrsearch1\" method=\"GET\" action=\"address_overview.php\">\n";
     DoubleTableStart();
     echo "<tr>\n";
     if ( $this->ext == 0 ) {
       echo "<th colspan=\"3\">". $lang['SearchForAdr'] ."</th>\n";
       echo "</tr><tr>\n";
       echo $this->showfieldc($lang['AdrName']);
       echo "<td>&nbsp;<input type=\"text\" value=\"". $this->lookfor ."\" name=\"name\">&nbsp;</td>\n";
       echo "<td><input type=\"submit\" value=\"". $lang['Search'] ."\" title=\"". $lang['SearchForAdr'] ."\"></td>\n";
       echo "</tr><tr>\n";
       echo "<td colspan=\"2\">&nbsp;</td>\n";
       echo "<td>". makelink("address_select.php?ext=1",$lang['extended']) ."</td>\n";
     } else {
       echo "<th colspan=\"3\">". $lang['SearchForAdrExt'] ."</th>\n";
       echo "</tr><tr>\n";
       echo $this->showfieldc($lang['AdrName']);
       echo " <td>&nbsp;<input type=\"text\" value=\"". $this->lookfor ."\" name=\"name\">&nbsp;</td>\n";
       echo " <td><input type=\"submit\" value=\"". $lang['Search'] ."\" title=\"". $lang['SearchForAdrExt'] ."\"></td>\n";
       echo "</tr><tr>\n";

       $c['name'] = "";
       $c['city'] = "";
       $c['street'] = "";
       $c['email'] = "";
       $c['desc'] = "";
       $c['phone'] = "";
       if ( isset($tutos[adrsearch]) ) {
         @reset($tutos[adrsearch]);
         while( list ($i,$f) = @each ($tutos[adrsearch])) {
           $c[$f] = " checked";
         }
       }

       echo " <td colspan=\"3\">\n";
       echo "  &nbsp;<input type=\"checkbox\" value=\"name\" name=\"ext[]\"". $c['name'] .">&nbsp;". $lang['AdrLastName'] ." &amp; ". $lang['AdrFirstName'] ."<br />\n";
       echo "  &nbsp;<input type=\"checkbox\" value=\"city\" name=\"ext[]\"". $c['city'] .">&nbsp;". $lang['City'] ."<br />\n";
       echo "  &nbsp;<input type=\"checkbox\" value=\"street\" name=\"ext[]\"". $c['street'] .">&nbsp;". $lang['Street'] ."<br />\n";
       echo "  &nbsp;<input type=\"checkbox\" value=\"email\" name=\"ext[]\"". $c['email'] .">&nbsp;". $lang['AdrEmail'] ."<br />\n";
       echo "  &nbsp;<input type=\"checkbox\" value=\"phone\" name=\"ext[]\"". $c['phone'] .">&nbsp;". $lang['Phone'] ."/". $lang['AdrFax']."<br />\n";
       echo "  &nbsp;<input type=\"checkbox\" value=\"desc\" name=\"ext[]\"". $c['desc'] .">&nbsp;". $lang['Description']."\n";
       echo " </td>\n";
     }

     echo "</tr>\n";
     DoubleTableEnd();
     hiddenFormElements();
     echo $this->getHidden();
     echo "</form>\n";

     if ( defined('useldap') && $this->user->feature_ok(useldap,PERM_SEL) ) {
       echo "<form name=\"adrsearch2\" method=\"get\" action=\"ldap/ldap_overview.php\">\n";
       DoubleTableStart();
       echo "<tr>\n";
       echo "<th colspan=\"3\">". $lang['SearchLdapAdr'] ."</th>\n";
       echo "</tr><tr>\n";
       echo $this->showfieldc($lang['AdrName']);
       echo "<td>&nbsp;<input type=\"text\" value=\"". $this->lookfor ."\" name=\"name\">&nbsp;</td>\n";
       echo "<td><input type=\"submit\" value=\"". $lang['Search'] ."\" title=\"". $lang['SearchLdapAdr'] ."\"></td>\n";

       if ( count($tutos[ldapserver])  > 1 ) {
         echo "</tr><tr>\n";
         echo $this->showfieldc($lang['LDAPServer']);
         echo "<td colspan=2>\n";
         echo "<select name=\"sv\">\n";
         foreach($tutos[ldapserver] as $i => $t) {
           echo "<option value=\"". $i ."\"". ($this->ldapserver==$i ? " selected":"") .">". $t ."</option>\n";
         }
         echo "</select>\n";
         echo "</td>\n";
       } else {
         $this->addHidden("sv","0");
       }
       echo "</tr>\n";
       DoubleTableEnd();
       hiddenFormElements();
       echo $this->getHidden();
       echo "</form>\n";
     }

     echo "<form name=\"cmpsearch1\" method=\"get\" action=\"company_overview.php\">\n";
     DoubleTableStart();
     echo "<tr>\n";
     echo "<th colspan=\"3\">". $lang['SearchForCmp'] ."</th>\n";
     echo "</tr><tr>\n";
     echo $this->showfieldc($lang['Company']);
     echo "<td>&nbsp;<input type=\"text\" value=\"". $this->lookfor ."\" name=\"name\">&nbsp;</td>\n";
     echo "<td><input type=\"submit\" value=\"". $lang['Search'] ."\" title=\"". $lang['SearchForCmp']."\"></td>\n";
     echo "</tr>\n";
     DoubleTableEnd();
     hiddenFormElements();
     echo $this->getHidden();
     echo "</form>\n";
     echo "</center>\n";

     echo $this->setfocus("adrsearch1.name");
   }
   /**
    *
    */
   Function navigate() {
   }
   /**
    * preapre everything read data , parse args etc
    */
   Function prepare() {
     global $msg,$lang,$tutos;
     $this->name = $lang['AddressSearch'];

     if ( ! $this->user->feature_ok(useaddressbook,PERM_SEL) ) {
       $msg .= sprintf($lang['Err0022'],"'". $this->name ."'");
       $this->stop = true;
     }

     # The extended mode fields
     if ( isset($_SESSION['adrsearch']) ) {
       $tutos[adrsearch] = $_SESSION['adrsearch'];
     }
     # The default search string
     if ( isset($_SESSION['adrlook']) ) {
       $this->lookfor = myentities($_SESSION['adrlook']);
     } else {
       $this->lookfor = "";
     }
     if ( isset($_GET['ext']) ) {
       $this->ext = $_GET['ext'];
     } else {
       $this->ext = 0;
     }
     if ( isset($_SESSION['ldapserver']) ) {
       $this->ldapserver = $_SESSION['ldapserver'];
     } else {
       $this->ldapserver = 0;
     }

     # menu
     if ( $this->user->feature_ok(useaddressbook,PERM_NEW) ) {
       $x = array( url => "address_new.php",
                   text => $lang['NewEntry'],
                   info => $lang['AdrCreateInfo'],
                   category => array("address","new","obj")
                 );
       $this->addMenu($x);
     }
     if ( $this->user->feature_ok(useaddressbook,PERM_NEW) ) {
       $x = array( url => "company_new.php",
                   text => $lang['CompanyCreate'],
                   info => $lang['CompanyCreateInfo'],
                   category => array("company","new","obj")
                 );
       $this->addMenu($x);
       $x = array( url => "department_new.php",
                   text => $lang['DepartmentCreate'],
                   info => $lang['DepCreateInfo'],
                   category => array("department","new","obj")
                 );
       $this->addMenu($x);
     }
   }
 }

 $l = new address_select($current_user);
 $l->display();
 $dbconn->Close();
?>
<!--
    CVS Info:  $Id$
    $Author$
-->