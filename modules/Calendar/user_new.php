<?php
/**
 * Copyright 1999 - 2004 by Gero Kohnert
 *
 * Editing of TUTOS users and their permissions
 *
 * @modulegroup user
 * @module user_new
 * @package user
 */
 include_once 'webelements.p3';
 include_once 'permission.p3';

 /* Check if user is allowed to use it */
 check_user();
 loadmodules("user","new");
 loadlayout();

 /**
  * display a user change/create form
  */
 class user_new extends layout {
   /**
    * helpfunction to generate an option-field
    */
   Function acldefault_select($min,$max,$name) {
     global $lang,$tutos;

     $nr["r"] = 0;
     $nr["u"] = 1;
     $nr["m"] = 2;
     $nr["d"] = 3;
     $js = "onchange='deselect(this.form,". $nr[$name] .");'";
     echo " <select multiple size=\"". min(count($this->obj->teamlist),$tutos[maxshow]) ."\" name=\"". $name ."[]\" ". $js .">\n";

     if ( isset($this->obj->acldefault[EVERYBODY]) ) {
       $l = $this->obj->acldefault[EVERYBODY];
     } else {
       $l = 0;
     }
     if ( ($l < $max) && ($l >= $min) ) {
       echo "  <option value=\"". EVERYBODY ."\" selected>- ". $lang['everybody'] ." -</option>\n";
     } else {
       echo "  <option value=\"". EVERYBODY ."\">- ". $lang['everybody'] ." -</option>\n";
     }

     if ( isset($this->obj->acldefault[MYTEAMS]) ) {
       $l = $this->obj->acldefault[MYTEAMS];
     } else {
       $l = 0;
     }
     if ( ($l < $max) && ($l >= $min) ) {
       echo "  <option value=\"". MYTEAMS ."\" selected>- all my teams-</option>\n";
     } else {
       echo "  <option value=\"". MYTEAMS ."\">- all my teams -</option>\n";
     }
     foreach ($this->obj->teamlist as $i => $fn) {
       if ( isset($this->obj->acldefault[$i]) ) {
         $l = $this->obj->acldefault[$i];
       } else {
         $l = 0;
       }
       if ( ($l < $max) && ($l >= $min) ) {
         echo "  <option value=\"". $i ."\" selected >&nbsp;". myentities($this->obj->teamlist[$i]->getFullName()) ."&nbsp;</option>\n";
       } else {
         echo "  <option value=\"". $i ."\">&nbsp;". myentities($this->obj->teamlist[$i]->getFullName()) ."&nbsp;</option>\n";
       }
     }
     echo " </select>\n";
   }
   /**
    * the data display part
    */
   Function info() {
     global $lang,$tutos,$table;

     echo "<script language='JavaScript'>\n";
     echo " function deselect (obj,nr) { \n";
     echo " for(var j = 0; j <= obj.length ; j++) {\n";
     echo "   if ( obj.elements[j].name == \"r[]\" ) {\n";
     echo "    var w = obj.elements[j]; \n";
     echo "    var x = obj.elements[j+1]; \n";
     echo "    var y = obj.elements[j+2]; \n";
     echo "    var z = obj.elements[j+3]; \n";
     echo "    break; \n";
     echo "   }\n";
     echo " }\n";  
     echo "  \n";  
     echo "  for (var i = 0; i < w.options.length; i++) {\n";
     echo "    if ( (nr == 0) && (w.options[i].selected) ) {\n";
     echo "      x.options[i].selected = false;\n";
     echo "      y.options[i].selected = false;\n";
     echo "      z.options[i].selected = false;\n";
     echo "    }\n";
     echo "    if ( (nr == 1) && (x.options[i].selected) ) {\n";
     echo "      w.options[i].selected = false;\n";
     echo "      y.options[i].selected = false;\n";
     echo "      z.options[i].selected = false;\n";
     echo "    }\n";
     echo "    if ( (nr == 2) && (y.options[i].selected) ) {\n";
     echo "      w.options[i].selected = false;\n";
     echo "      x.options[i].selected = false;\n";
     echo "      z.options[i].selected = false;\n";
     echo "    }\n";
     echo "    if ( (nr == 3) && (z.options[i].selected) ) {\n";
     echo "      w.options[i].selected = false;\n";
     echo "      x.options[i].selected = false;\n";
     echo "      y.options[i].selected = false;\n";
     echo "    }\n";
     echo "  }\n";  
     echo " }\n";   
     echo "</script>\n";

     echo "<form name=\"useradd\" action=\"user_ins.php\" method=\"post\">\n";

     echo $this->DataTableStart();
     echo "<tr>\n";
     echo " <th colspan=\"9\">". $lang['UserInfo'] ."</th>\n";
     echo "</tr>\n";

     echo "<tr>\n";
     echo " <td align=\"right\" colspan=\"9\">". acl_link($this->obj) ."</td>\n";
     echo "</tr>\n";

     echo "<tr>\n";
     if ( $this->user->isAdmin() ) {
       echo $this->showfieldc($lang['Username'],1,"login");
       echo " <td colspan=\"2\"><input id=\"login\" size=\"".min($table['people']['login'][size],20) ."\" maxlength=\"". $table['people']['login'][size] ."\" name=\"login\" value=\"". $this->obj->login ."\"></td>\n";
       echo $this->showfieldc($lang['UserDisabled'],0,"disabled");
       echo " <td colspan=\"2\"><input id=\"disabled\" type=\"checkbox\" name=\"disabled\" value=\"1\"". ($this->obj->disabled == 1 ? " checked":"") ."></td>\n";
     } else {
       echo $this->showfieldc($lang['Username'],0,"login");
       echo $this->showdata($this->obj->login,5);
       $this->addHidden("login", $this->obj->login );
     }
     echo "<td colspan=\"3\">&nbsp;</td>\n";
     echo "</tr>\n";

     if ($this->obj->id == -1) {
       # new entry
       echo "<tr>\n";
       echo $this->showfieldc($lang['AdrFirstName'],1,"fname");
       echo " <td colspan=\"8\"><input id=\"fname\" size=\"".min($table['address']['f_name'][size],40) ."\" maxlength=\"". $table['address']['f_name'][size] ."\" name=\"fname\" value=\"". $this->obj->f_name ."\"></td>\n";
       echo "</tr>\n";
       echo "<tr>\n";
       echo $this->showfieldc($lang['AdrLastName'],1,"lname");
       echo " <td colspan=\"8\"><input id=\"lname\" size=\"".min($table['address']['l_name'][size],40) ."\" maxlength=\"". $table['address']['l_name'][size] ."\" name=\"lname\" value=\"". $this->obj->l_name ."\"></td>\n";
       echo "</tr>\n";
       echo "<tr>\n";
       echo $this->showfieldc($lang['AdrEmail'],1,"email");
       echo " <td colspan=\"8\"><input id=\"email\" size=\"".min($table['location']['email_1'][size],40) ."\" maxlength=\"". $table['location']['email_1'][size] ."\" name=\"email\" value=\"". $this->obj->email_1 ."\"></td>\n";
       echo "</tr>\n";
     } else {
       echo "<tr>\n";
       echo $this->showfieldc($lang['User']);
       echo $this->showdata($this->obj->getLink(),8);
       echo "</tr>\n";
     }
     echo "<tr>\n";
     echo $this->showfieldc($lang['UserLastSeen']);
     echo $this->showdata($this->obj->last_seen->getDateTime(),2);
     echo $this->showfieldc($lang['UserLastHost']);
     echo $this->showdata($this->obj->last_host,2);
     echo "<td colspan=\"3\">&nbsp;</td>\n";
     echo "</tr>\n";

     echo "<tr>\n";
     echo $this->showfieldc($lang['UserAdmin'],1,"admin");
     if ( $this->user->isAdmin()) {
       $achecked[0] ="";
       $achecked[1] ="";
       $achecked[$this->obj->admin] ="CHECKED";
       echo " <td colspan=\"1\" align=\"right\">". $lang['yes'] ."&nbsp;<input id=\"admin\" type=\"radio\" name=\"admin\" value=\"1\" ". $achecked[1] ."></td>";
       echo " <td colspan=\"4\" align=\"left\">". $lang['no'] ."&nbsp;<input id=\"admin\" type=\"radio\" name=\"admin\" value=\"0\" ". $achecked[0] ."></td>";
     } else {
       $this->addHidden("admin",$this->obj->admin);
       if ( $this->obj->isAdmin() ) {
         echo $this->showdata($lang['yes'],5);
       } else {
         echo $this->showdata($lang['no'],5);
       }
     }
     echo "<td colspan=\"3\">&nbsp;</td>\n";
     echo "</tr>\n";

     if ( ($tutos[authtype] != "pam") && ($tutos[authtype] != "ldap") ) {
       if ( $this->dbconn->Password("a") == "'a'" ) {
         echo "<tr>\n";
         echo " <td colspan=\"9\"><span class=\"warn\">Passwords will not be encrypted in the Database.<br>Do not use your standard password !!</span></td>\n";
         echo "</tr>\n";
       }
       if ( ! $this->user->isAdmin() ) {
         if ( $this->dbconn->gettype() != "MySQL" ) {
           echo "<tr>\n";
           echo $this->showfieldc($lang['Password'] ." (old)",0,"p0");
           echo " <td colspan=\"8\"><input id=\"p0\" type=\"password\" size=\"20\" maxlength=\"20\" name=\"p0\" value=\"\"></td>\n";
           echo "</tr>\n";
         }
       }
       echo "<tr>\n";
       echo $this->showfieldc($lang['Password'] ." (new)",0,"p1");
       echo " <td colspan=\"8\"><input id=\"p1\" type=\"password\" size=\"20\" maxlength=\"20\" name=\"p1\" value=\"\"></td>\n";
       echo "</tr>\n";

       echo "<tr>\n";
       echo $this->showfieldc($lang['Password'] ." (repeat)",0,"p2");
       echo " <td colspan=\"8\"><input id=\"p2\" type=\"password\" size=\"20\" maxlength=\"20\" name=\"p2\" value=\"\"></td>\n";
       echo "</tr>\n";
     }
     #
     # Feature Permissions
     permission_form($this,$this->user,$this->obj);

     #
     # Default-Groups for new objects
     #
     if ( ($tutos[defaultacl] == 2) &&
        ( ($this->user->isAdmin())  || (count($this->user->teamlist) > 0) ) 
       ) {
       # read a list of all teams;
       $q = "select * from ". $this->dbconn->prefix ."teams";
       $r = $this->dbconn->Exec($q);
       $n = $r->numrows();
       $a = 0;
       $this->obj->teamlist = array();
       while ( $a < $n ) {
         $t = new team($this->dbconn);
         $t->read_result($r,$a);
         $this->obj->teamlist[$t->id] = &$t;
         unset ($t);
         $a++;
       }
       $r->free();
       echo "<tr>\n";
       echo $this->showfieldc($lang['UserDefaultGrp'],0,"acldefault");
       echo " <th width=\"30%\" colspan=\"2\">". $lang['ACLread'] ."</th>\n";
       echo " <th width=\"30%\" colspan=\"2\">". $lang['ACLread'] ."<br>". $lang['ACLuse'] ."</th>\n";
       echo " <th width=\"30%\" colspan=\"2\">". $lang['ACLread'] ."<br>". $lang['ACLuse'] ."<br>". $lang['ACLmodify'] ."</th>\n";
       echo " <th width=\"30%\" colspan=\"2\">". $lang['ACLread'] ."<br>". $lang['ACLuse'] ."<br>". $lang['ACLmodify'] ."<br>". $lang['ACLdelete'] ."</th>\n";
       echo "</tr>\n";
       echo "<tr>\n"; 
       echo " <td colspan=\"1\">&nbsp;</td>\n";
       echo " <td colspan=\"2\" align=\"center\">\n";
       $this->acldefault_select(1,$tutos[useok],"r");
       echo " </td>\n";
       echo " <td colspan=\"2\" align=\"center\">\n";
       $this->acldefault_select($tutos[useok], $tutos[modok],"u");
       echo " </td>\n";
       echo " <td colspan=\"2\" align=\"center\">\n";
       $this->acldefault_select($tutos[modok], $tutos[delok],"m");
       echo " </td>\n";
       echo " <td colspan=\"2\" align=\"center\">\n";
       $this->acldefault_select($tutos[delok], 99, "d");
       echo " </td>\n";
       echo "</tr>\n";
     }
     #
     # LANGUAGE
     #
     echo "<tr>\n";
     echo $this->showfieldc($lang['UserLanguage'],1,"lng");
     echo " <td colspan=\"2\">\n";
     echo "  <select id=\"lng\" name=\"lng\">\n";
     foreach($lang['lang'] as $i => $f) {
       echo "   <option value=\"". $i ."\"". ( !strcasecmp($i,$this->obj->lang) ? " selected":"") .">". myentities($f) ."</option>\n";
     }
     echo "  </select>\n";
     echo " </td>\n";

     # TIMEZONE
     echo $this->showfieldc($lang['UserTimezone'],1,"tz");
     echo " <td colspan=\"2\">";
     echo " <select id=\"tz\" name=\"tz\">\n";
     foreach($tutos[timezones] as $f) {
       echo "  <option value=\"". $f ."\"". ($this->obj->tz == $f ? " selected":"") .">". $f ."</option>\n";
     }
     echo " </select>\n";
     echo " </td>\n";

     echo "<td colspan=\"3\">&nbsp;</td>\n";

     echo "</tr>\n";

     echo "<tr>\n";
     # Weekstart
     echo $this->showfield($lang['UserWeekstart'],1,"ws");
     echo " <td nowrap colspan=\"2\">\n";
     echo " <select id=\"ws\" name=\"ws\">\n";
     echo "  <option value=\"0\"". ($this->obj->weekstart == 0 ? " selected":"") .">". $lang['Day0'] ."</option>\n";
     echo "  <option value=\"1\"". ($this->obj->weekstart == 1 ? " selected":"") .">". $lang['Day1'] ."</option>\n";
     echo "  <option value=\"6\"". ($this->obj->weekstart == 6 ? " selected":"") .">". $lang['Day6'] ."</option>\n";
     echo " </select>\n";
     echo " </td>\n";

     # Workdays
     echo $this->showfield($lang['UserWorkdays'],1,"wd[]");
     echo " <td nowrap colspan=\"5\">\n";
     for ( $i = 0 ; $i < 7 ; $i++ ) {
       echo "<input id=\"wd[]\" type=\"checkbox\" name=\"wd[]\" value=\"". $i ."\" ". ($this->obj->isworkday($i) ? "checked":"") .">". $lang['Day'.$i];
     }
     echo " </td>\n";

     echo "</tr>\n";

     echo "<tr>\n";
     # HOLIDAYS
     $xx = 1;
     echo $this->showfield($lang['UserHoliday'],0,"h[]");
     @reset ($tutos[holiday]);
     echo " <td colspan=\"2\">\n";
     while( list ($i,$f) = @each ($tutos[holiday])) {
       if ( $tutos[holiday][$i] != 1 ) {
         continue;
       }
       echo "  <input id=\"h[]\" type=\"checkbox\" name=\"h[]\" value=\"". strtolower($i) ."\" ". ($this->obj->holiday[strtolower($i)] == 1 ? "checked":"") .">". $i;
       if ( 0 == ($xx % 3) ) {
         echo "<br>\n";
       } else {
         echo "&nbsp;&nbsp;";
       }
       $xx++;
     }
     echo " </td>\n";

     # Namedays
     $xx = 1;
     echo $this->showfield($lang['UserNamedays'],0,"nd[]");
     echo " <td colspan=\"2\">\n";
     foreach ($tutos[nameday] as $i => $f) {
       if ( $tutos[nameday][$i] != 1 ) {
         continue;
       }
       echo "  <input id=\"nd[]\" type=\"checkbox\" name=\"nd[]\" value=\"". strtolower($i) ."\" ". ($this->obj->nameday[strtolower($i)] == 1 ? "checked":"") .">". $i;
       if ( 0 == ($xx % 3) ) {
         echo "<br>\n";
       } else {
         echo "&nbsp;&nbsp;";
       }
       $xx++;
     }
     echo " </td>\n";

     echo "<td colspan=\"3\">&nbsp;</td>\n";

     echo "</tr>\n";

     echo "<tr>\n";
     
     # RowIcons
     echo $this->showfieldc($lang['RowIconsBefore'],0,"rib[]");
     echo " <td nowrap colspan=\"3\">\n";
     foreach($tutos[rowiconsbefore] as $f) {
       echo "<input id=\"rib[]\" type=\"checkbox\" name=\"rib[]\" value=\"". $f ."\" ". ($this->obj->rowiconsbefore[strtolower($f)] == 1 ? "checked":"") .">";
       if ( $f == "see" ) {
         echo $lang['show'];
       } else if ( $f == "mod" ) {
         echo $lang['Modify'];
       } else if ( $f == "del" ) {
         echo $lang['Delete'];
       }
     }          
     echo " </td>\n";
     echo "</tr>\n";
     echo "<tr>\n";
     echo $this->showfieldc($lang['RowIconsAfter'],0,"ria[]");
     echo " <td nowrap colspan=\"3\">\n";
     foreach($tutos[rowiconsafter] as $f) {
       echo "<input id=\"ria[]\" type=\"checkbox\" name=\"ria[]\" value=\"". $f ."\" ". ($this->obj->rowiconsafter[strtolower($f)] == 1 ? "checked":"") .">";
       if ( $f == "see" ) {
         echo $lang['show'];
       } else if ( $f == "mod" ) {
         echo $lang['Modify'];
       } else if ( $f == "del" ) {
         echo $lang['Delete'];
       }
     } 
     echo " </td>\n";
     echo "</tr>\n";
     
     echo "<tr>\n";
     # THEME
     echo $this->showfieldc($lang['UserTheme'],1,"theme");
     echo " <td colspan=\"2\">\n";
     echo " <select id=\"theme\" name=\"theme\">\n";
     foreach($tutos[themes] as $f) {
       echo "  <option value=\"". $f ."\" ". ($f == $this->obj->theme ? " selected":"") .">". $f ."</option>\n";
     }
     echo " </select>\n";
     echo " </td>\n";

     # Layout Engine
     echo $this->showfieldc($lang['UserLayout'],1,"layout");
     echo " <td colspan=\"2\">\n";
     echo " <select id=\"layout\" name=\"layout\">\n";
     foreach($tutos[layouts] as $f) {
       echo "  <option value=\"". $f ."\" ". ($f == $this->obj->ly ? " selected":"") .">". $f ."</option>\n";
     }
     echo " </select>\n";
     echo " </td>\n";

     echo "<td colspan=\"3\">&nbsp;</td>\n";

     echo "</tr>\n";

     #
     # Edit additional custom fields
     #
     edit_custom_fields($this,"people",$this->obj,8);
     # References to modules
     module_addforms($this->user,$this->obj,8);

     echo "<tr>\n";
     if ($this->obj->uid > 0 ) {
       submit_reset(0,1,3,1,3,0);
     } else {
       submit_reset(0,-1,3,1,3,0);
     }
     echo "</tr>\n";
     if ( ($this->obj->uid != -1) && ($this->user->feature_ok(usehistory,PERM_SEE)) ) {
       echo "<tr>\n";
       echo " <td colspan=\"8\">". makelink("history_show.php?id=". $this->obj->uid,$lang['HistoryLink'],sprintf($lang['HistoryLinkI'],$this->obj->getFullname())) ."</td>\n";
       echo "</tr>\n";
     }
     echo $this->DataTableEnd();
     echo $this->getHidden();
     hiddenFormElements();
     echo "</form>\n";
     echo $lang['FldsRequired'] ."\n";
     echo $this->setfocus("useradd.login");
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
     global $msg,$tutos,$lang;

     $this->obj = new tutos_user($this->dbconn);
     if ( isset($_GET['id']) ) {
       # Read address as a user entry
       $this->obj = $this->obj->read($_GET['id'],$this->obj,0);
       $this->obj->read_permissions();
     } else if ( isset($_GET['uid']) ) {
       $this->obj = $this->obj->read($_GET['uid'],$this->obj,1);
       $this->obj->read_permissions();
     } else {
       # We need a new adress entry and location
       $this->obj->email_1 = "";
       if ( isset($_GET['fname']) ) {
         $this->obj->f_name = $_GET['fname'];
       }
       if ( isset($_GET['lname']) ) {
         $this->obj->l_name = $_GET['lname'];
       }
       if ( isset($_GET['email']) ) {
         $this->obj->email_1 = $_GET['email'];
       }
     }

     $this->addHidden("uid",$this->obj->uid);
     $this->addHidden("id",$this->obj->id);

     if ( ($this->obj->uid == -1) && !$this->user->feature_ok(useuser,PERM_NEW) ) {
       $msg .= sprintf($lang['Err0054'],$lang[$this->obj->getType()]);
       $this->stop = true;
     } else if ( ($this->obj->id == -1) && !$this->user->feature_ok(useuser,PERM_NEW) ) {
       $msg .= sprintf($lang['Err0054'],$lang[$this->obj->getType()]);
       $this->stop = true;
     } else if ( ! $this->obj->mod_ok() ) {
       $msg .= sprintf($lang['Err0024'],$lang[$this->obj->getType()]);
       $this->stop = true;
     }
     if ( $tutos[useacl] != 1) {
       if ( !$this->user->isadmin() && ($this->user->id != $this->obj->id ) ) {
         $msg .= sprintf($lang['Err0054'],$lang[$this->obj->getType()]);
         $this->stop = true;
       }
     }

     if ( $this->obj->uid != -1 ) {
       $this->name = $lang['UserModify'] .": ". $this->obj->login;
     } else {
       $this->name = $lang['UserCreate'];
     }

     if (isset($_GET['login'])) {
       $this->obj->login = StripSlashes($_GET['login']);
     }

     if ($this->obj->login =="") {
       # create a default 
       $this->obj->login = strtolower(substr($this->obj->f_name,0,min(2,strlen($this->obj->f_name))) . substr($this->obj->l_name,0,min(6,strlen($this->obj->l_name))));
     }


     if ( isset($_GET['h']) ) {
       foreach ($_GET['h'] as $i => $f) {
         $this->obj->holiday[$i] = $f;
       }
     }
     if ( isset($_GET['wd']) ) {
       $this->obj->workday = array();
       foreach($_GET['wd'] as $i => $f) {
         $this->obj->workday[$i] = $f;
       }
     }
     if ( isset($_GET['nd']) ) {
       foreach($_GET['nd'] as $i => $f) {
         $this->obj->nameday[$i] = $f;
       }
     }
     if ( isset($_GET['ws']) ) {
       $this->obj->weekstart = $_GET['ws'];
     }

     # Create the menu items
#var_dump ($this->obj->acl);
     if ( $this->user->feature_ok(useuser,PERM_NEW) ) {
       $x = array( url => "user_new.php",
                   text => $lang['NewEntry'],
                   info => $lang['UserCreate'],
                   category => array("user","new","obj")
                 );
       $this->addMenu($x);
     }
     if ( ($this->obj->del_ok()) && ($this->obj->uid > 0) ) {
       $x = array( url => "user_del.php?id=". $this->obj->id,
                   confirm => true,
                   text => $lang['Delete'],
                   info => sprintf($lang['UserDelInfo'], $this->obj->getFullName()),
                   category => array("user","admin","obj","del")
                 );
       $this->addMenu($x);
     }
     if ( $this->obj->uid > 0) {
       $x = array( url => "mytutos.php?adr=". $this->obj->id,
                   confirm => false,
                   text => sprintf($lang['PersonalPageFor'], $this->obj->getFullName()),
                   info => sprintf($lang['PersonalPageFor'], $this->obj->getFullName()),
                   category => array("view","obj")
                 );
       $this->addMenu($x);
     }
     add_module_newlinks($this,$this->obj);
     web_StackStartLayout($this,"user_new.php","user_new.php");     
   }
 }


 $l = new user_new($current_user);
 $l->display();
 $dbconn->Close();
?>
<!--
    CVS Info:  $Id$
    $Author$
-->
