<?php
/**
 * Copyright 1999 - 2004 by Gero Kohnert
 *
 * Editing of "normal" (not repeatig) appointments
 *
 * @modulegroup appointment
 * @module app_new
 * @package appointment
 */
 global $calpath;
 global $callink;
 $callink = "index.php?module=Calendar&action=";

 global $theme;
 $theme_path="themes/".$theme."/";
 $image_path=$theme_path."images/";
 require_once ($theme_path."layout_utils.php");
 global $mod_strings;

 echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_APPOINTMENT'], true); 
// echo "\n<BR>\n";

 include_once $calpath .'webelements.p3';
 include_once $calpath .'permission.p3';
 include_once $calpath .'appointment.pinc';

 require_once('include/database/PearDatabase.php');
 require_once('modules/Calendar/preference.pinc');

 /* Check if user is allowed to use it */
 //check_user();
 loadmodules("appointment","new");
 loadlayout();

 /**
  * display a appointment for changing
  */
 class app_new extends layout {

	Function app_new()
	{
		$this->db = new PearDatabase();
                $this->pref = new preference();	
	}
   /**
    * display the info
    */
   Function info() {
     global $tutos, $lang,$table,$callink,$app_strings,$mod_strings,$apmt_location,$current_user;
     $a_checked[0] = "";
     $a_checked[1] = "";
     $a_checked[2] = "";
     $a_checked[3] = "";
     $a_checked[$this->obj->mod_allow] = " checked=\"checked\"";

	//print("GS --> info allowed=".$this->obj->allowed);
     if ( $this->obj->allowed == 0 ) {
       echo $this->error($lang['ReadOnlyAppoint']);
     }
     
     echo "<form name=\"appnew\" action=\"index.php\" method=\"post\">\n";
     echo "<input type=\"hidden\" name=\"module\" value=\"Calendar\">\n";
     echo "<input type=\"hidden\" name=\"return_module\" value=\"Calendar\">\n";
     echo "<input type=\"hidden\" name=\"return_action\" value=\"calendar\">\n";
     echo "<input type=\"hidden\" name=\"action\">\n";
     $this->addHidden("repeat","0");
     $this->addHidden("r_ignore","0");
     $this->addHidden("a_start","");
     $this->addHidden("a_end","");
     //print("GS --> current user id=".$current_user->id);
     if ( $current_user->id !="" ) {
       $this->addHidden("creator",$current_user->id);
     }
     if ( isset($this->obj->id) && isset($this->obj->id) != "" ) {
       $this->addHidden("record",$this->obj->id);
       $this->addHidden("id",$this->obj->id);
     }


	 //echo $this->DataTableStart();
	 
	 /*
	 echo "<table class=\"navigate\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"90%\">\n";
	 echo "<tr>\n";
     echo "<th colspan=\"6\">";
     if ( $this->obj->id > 0 ) {
       echo $lang['ModOldAppoint'];
       echo "</th>\n";
       echo "</tr><tr>\n";
       echo "<td colspan=\"5\">&nbsp;<b>". $lang['AppCreatedBy'] ."</b>&nbsp;". $this->obj->creator->getLink();
       echo "&nbsp;<b>". $lang['atDateTime'] ."</b>&nbsp;". $this->obj->creation->getDateTime()."</td>";
       echo "<td align=\"right\">" .acl_link($this->obj) ."</td>\n";
       echo "</tr>\n";
     } else {
       echo $lang['CreateAppoint'];
       echo "</th>\n";
     }
     echo "</tr>\n";
	 echo "</table>\n";
	 */
// srini commented creator duplicate
     //$this->addHidden("creator",$this->obj->creator->id);
	 
	 if ( $this->obj->allowed != 0 ) {
       echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"5\"><tr>\n";
	if ( isset($this->obj->id) && isset($this->obj->id) != "" ) {
       //if ( $this->obj->id > 0 ) {
         //submit_reset(0,1,2,1,2,0);
		 submit_reset(0,0,2,0,2,2,0);
       } else {
         //submit_reset(0,-1,2,1,2,0);
		 submit_reset(0,0,2,0,2,0,0);
       }
       echo "</tr></table>\n";
     }
	 
	 echo "<table class=\"single\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" width=\"90%\">\n";
	 echo "<tr><td class=\"inner\">\n";
	 echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"1\" width=\"100%\">\n";
	 if ( $this->obj->id != "" ) {
       echo "<tr>\n";
       echo "<td class=\"desc\">". $mod_strings['LBL_APPCREATED_BY'] .":</td>\n";
       #echo "<td colspan=\"5\">". $this->obj->creator->getLink() . "&nbsp;" . $mod_strings['LBL_AT_DATE_TIME'] ."&nbsp;". $this->obj->creation->getDateTime()."</td>";
//print("GS --> appid=".$this->obj->id);
echo "<td colspan=\"5\"><b>". $this->obj->getUserName($this->obj->creator) . "&nbsp;" . $mod_strings['LBL_AT_DATE_TIME'] ."&nbsp;". $this->obj->creation->getDateTime()."</b></td>";
       //echo "<td align=\"right\">" .acl_link($this->obj) ."</td>\n";
       echo "</tr>\n";
     }
     # START
     echo "<tr>\n";
     echo $this->showfieldc($mod_strings['LBL_APP_START_DATE'],0,"start_d");
     echo "<td colspan=\"2\">";
     $this->obj->start->EnterDate("start");
     echo "</td>";
     echo "<td valign=\"top\" class=\"desc\">&nbsp;<b>";
     if ( !isset($_SERVER['HTTP_USER_AGENT']) || ereg("Lynx",$_SERVER['HTTP_USER_AGENT']) || ereg("w3m",$_SERVER['HTTP_USER_AGENT']) ) {
       echo $mod_strings['LBL_APP_START_TIME'];
     } else {
       echo "<a href=\"JavaScript: var d = document.forms[0];
mywindow = window.open('', 'timer', 'resizable=yes,width=100,height=415,top=100,left=450');
mywindow.location.href = '". $callink ."minitimer&f=start&amp;". SID ."'; mywindow.focus();\"
 onmouseover=\"self.status='minitimer' ;return true\">";
       echo $mod_strings['LBL_APP_START_TIME'];
       echo "</a>\n";
     }
     echo ":</b>&nbsp;<br/>&nbsp;<font size=\"-1\">(HH:MM)</font></td>\n";
     echo "<td colspan=\"2\">";
     $this->obj->start->EnterTime("start");
     echo "</td>\n";

     # END
     echo "</tr><tr>\n";

     echo $this->showfieldc($mod_strings['LBL_APP_END_DATE'],0,"end_d");
     echo "<td colspan=\"2\">";
     $this->obj->end->EnterDate("end");
     echo "</td>\n";
     echo "<td valign=\"top\" class=\"desc\">&nbsp;<b>";
     if ( !isset($_SERVER['HTTP_USER_AGENT']) || ereg("Lynx",$_SERVER['HTTP_USER_AGENT']) || ereg("w3m",$_SERVER['HTTP_USER_AGENT']) ) {
       echo $mod_strings['LBL_APP_END_TIME'];
     } else {
       echo "<a href=\"JavaScript: var d = document.forms[0];
mywindow = window.open('', 'timer', 'resizable=yes,width=100,height=415,top=100,left=450');
mywindow.location.href = '". $callink."minitimer&f=end&amp;". SID ."'; mywindow.focus();\"
onmouseover=\"self.status='minitimer' ;return true\">";
       echo $mod_strings['LBL_APP_END_TIME'];
       echo "</a>\n";
     }
     echo ":</b>&nbsp;<br />&nbsp;<font size=\"-1\">(HH:MM)</font></td>\n";
     echo "<td colspan=\"2\">";
     $this->obj->end->EnterTime("end");
     echo "</td>\n";

     echo "</tr><tr>\n";

     # LOCATION
     echo $this->showfieldc($mod_strings['LBL_APP_LOCATION'],0,"outside");
     echo " <td colspan=\"2\">\n";
     echo "  <select id=\"outside\" name=\"outside\">\n";
     foreach ($mod_strings['AppLoc'] as $i => $f) {
       echo "   <option value=\"". $i ."\"". ($this->obj->outside == $i ? " selected=\"selected\"":"") .">". $mod_strings['AppLoc'][$i] ."</option>\n";
     }
     echo "  </select>\n";
     echo " </td>\n";

     # Ignore times
     echo " <td colspan=\"3\" valign=\"top\">\n";
     echo "<input type=\"checkbox\" name=\"t_ignore\" value=\"1\"". ($this->obj->t_ignore == 1 ? " checked=\"checked\"":"") ." />\n";
     echo $mod_strings['LBL_APP_IGNORE_TIME'] ."<br/>\n";
     echo "<font size=\"-1\">". $mod_strings['LBL_APP_IGNORE_TIME2'] ."</font>";
     echo " </td>\n";
     echo "</tr><tr>\n";
     echo $this->showfieldc($mod_strings['LBL_SUBJECT'],1,"subject");
     echo "<td><input type=\"text\" name=\"subject\" value=\"".$this->obj->subject."\" size=\"40\" maxlength=\"50\"></td>\n";	
     echo "</tr><tr>\n";

     echo $this->showfield($mod_strings['LBL_APP_DESCRIPTION'],0,"descr");
     echo $this->pref->textarea("descr",5,100,$this->obj->descr);
     echo "</tr>\n";
     echo "<tr>\n";
     # Customer/Visitor name
     #echo $this->showfieldc($lang['VisitAt'] ."<br />". $lang['VisitFrom'],0,"v");
     //Added to get vtigerCRM contacts
     echo $this->showfieldc($mod_strings['LBL_CONTACT'],1,"contact_name");
     echo " <td colspan=\"2\">";
     #echo "<input type=\"text\" name=\"contact_name\" value=\"".$this->obj->contact_name."\" size=\"20\" maxlength=\"40\" readonly>";
     echo "<input type=\"text\" name=\"contact_name\" value=\"".$this->obj->getContactName($this->obj->contact_id)."\" size=\"20\" maxlength=\"40\" readonly>";

	echo "<input type=\"hidden\" name=\"contact_id\" value=\"".$this->obj->contact_id."\">";
     //echo " </td>\n";
     echo " &nbsp;<input title='".$app_strings['LBL_SELECT_CONTACT_BUTTON_TITLE']."' accessyKey='".$app_strings['LBL_SELECT_CONTACT_BUTTON_KEY']."' type='button' class='button' value='  ".$app_strings['LBL_SELECT_CONTACT_BUTTON_LABEL']."  ' name='button' LANGUAGE=javascript onclick='window.open(\"index.php?module=Contacts&action=Popup&html=Popup_picker&form=appnew&form_submit=false\",\"new\",\"width=600,height=400,resizable=1,scrollbars=1\");'>";
     //echo " </td>\n";
     echo " <td colspan=3>&nbsp;</td>\n";

     echo " </tr>\n";

   //  if ( $this->user->feature_ok(useprojects,PERM_SEE) ) {
       #echo "</tr><tr>\n";
       #echo $this->showfieldc($lang['Product'],0,"p");
       #echo " <td colspan=\"5\">\n";
       #select_from_array_or_input($this->obj,"p",$this->obj->product,1);
       #echo " </td>\n";
    // }

     # list of possible participants
     #echo "</tr><tr>\n";
     #echo " <td class=\"desc\" rowspan=\"3\" valign=\"top\"><label for=\"people\">". $lang['Participants'] ."</label>&nbsp;<font color=\"red\">(*)</font></td>";
     #echo " <td rowspan=\"3\" valign=\"top\">\n";
     //Added default options as it is the required field
        echo "<input type=\"hidden\" name=\"mod_allow\" value=\"1\">\n";	
	foreach($this->obj->people as $tp => $fp)
	{
		$pid = $tp;
	}
       echo "<input type=\"hidden\" name=\"people[]\" value=\"$pid\">\n";
    //ended default options
     #echo "". $this->user->askPeople("people[]",$this->obj->people,1) ."\n";

     #echo " </td>\n";

     # Voting
     #echo " <td colspan=\"1\">\n";
     #echo "<input type=\"checkbox\" name=\"trace\" value=\"1\"". ($this->obj->trace == 1 ? " checked":"") ." />\n";
     #echo $lang['AppTrace']."<br /><font size=\"-1\">". $lang['AppTraceDesc'] ."</font></td>\n";

     # Permissions
     #echo " <td rowspan=\"2\" colspan=\"3\">&nbsp;<b>". $lang['AppChangeOrDel'] ."</b>&nbsp;<br />\n";
     #echo "&nbsp;<input type=\"radio\" name=\"mod_allow\" value=\"2\"". $a_checked[2] ." />&nbsp;". $lang['only'] ." ". $this->obj->creator->getLink() ."<br />\n";
     #echo "&nbsp;<input type=\"radio\" name=\"mod_allow\" value=\"1\"". $a_checked[1] ." />&nbsp;". $lang['Participants'] ."<br />\n";
     #echo "&nbsp;<input type=\"radio\" name=\"mod_allow\" value=\"0\"". $a_checked[0] ." />&nbsp;". $lang['everybody'] ."<br />\n";
     #echo "&nbsp;<input type=\"radio\" name=\"mod_allow\" value=\"3\"". $a_checked[3] ." />&nbsp;". $lang['AppPrivate'] ."<br />\n";
     #echo " </td>\n";

     #echo "</tr><tr>\n";

     # check available
     #echo "<td colspan=\"1\">\n";
     #echo "<input type=\"checkbox\" name=\"check\" value=\"1\"" . ($this->obj->check == 1 ? " checked":"") ." />\n";
     #echo $lang['AppCheck']."<br /><font size=\"-1\">". $lang['AppCheckDesc'] ."</font></td>\n";
     #echo "</tr><tr>\n";

     # email invite/inform
     #echo " <td colspan=\"1\">\n";
     #echo "<input type=\"checkbox\" name=\"email\" value=\"1\"". ($this->obj->email == 1 ? " checked":"") ." />\n";
     if ( $this->obj->id > 0 ) {
      # echo $lang['InfoEmail'];
     } else {
       #echo $lang['InviteEmail'];
     }
     #echo " </td>\n";

     # reminder email
     #echo " <td colspan=\"3\"><b>". $lang['AppEmailRemind'] ."</b><br />\n";
     #echo "  <select name=\"remember\">\n";
     #foreach ($lang['AppRemind'] as $i => $f) {
      # echo "   <option value=\"". $i ."\"". ($i == $this->obj->remember ? " selected=\"selected\"":"") .">". $f ."</option>\n";
     #}
     #echo "  </select>";
     #echo " </td>\n";
     #echo " </tr>\n";

     module_addforms($this->user,$this->obj,6);

     //echo $this->DataTableEnd();
	 echo "</table>\n";
	 echo "</td></tr></table>\n";

     if ( $this->obj->allowed != 0 ) {
       echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"5\"><tr>\n";
	if ( isset($this->obj->id) && isset($this->obj->id) != "" ) {
       //if ( $this->obj->id > 0 ) {
         //submit_reset(0,1,2,1,2,0);
		 submit_reset(0,0,2,0,2,2,0);
       } else {
         //submit_reset(0,-1,2,1,2,0);
		 submit_reset(0,0,2,0,2,0,0);
       }
       echo "</tr></table>\n";
     }

     hiddenFormElements();
     echo $this->getHidden();
     echo "</form>\n";
     echo $this->setfocus("appnew.descr");
     echo $mod_strings['FldsRequired'] ."\n";
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
     global $msg,$tutos,$lang,$callink;
     
     if ($msg!='')
     {
	echo "<center><font color=red><b>".$msg."</b></font></center>";
     }
     $p = array();
     $this->obj = new appointment($this->dbconn);
     //print("GS --> prepare id=".$_GET['id']);
     if ( isset($_GET['id']) ) {
       $this->name =  $mod_strings['AppointModify'];
       $this->obj = $this->obj->read($_GET['id'],$this->obj);
       if ($this->obj->id < 0) {
         $msg .= sprintf($lang['Err0040'],$lang[$this->obj->getType()]);
         $this->stop = true;
       }
       //$this->obj->read_participants();
       /* only the owner may change */
       if ( $this->user->id == $this->obj->creator->id )  {
         $this->obj->allowed = 2;
       }
    /*   if ( $this->obj->visitor != -1 ) {
         $this->obj->xfn['v'] = $this->obj->visitor->getFullName();
       }
       if ( $this->obj->product != -1 ) {
         $this->obj->xfn['p'] = $this->obj->product->getFullName();
       }
       foreach($this->obj->participant as $i => $f) {
         $p[$i] = 2;
       }*/
     } else {
       $this->name = $mod_strings['AppointCreate'];
       /* New event */
       if (isset($_GET['t']) && is_numeric($_GET['t'])) {
         $this->obj->start->setDateTime($_GET['t']);
         $this->obj->end->setDateTime($_GET['t']);
       }
       $p[$this->user->id] = 2;
     }


     # Set available Parameters
     if ( isset($_GET['descr']) ) {
       $this->obj->descr = StripSlashes($_GET['descr']);
     }
     if ( isset($_GET['subject']) ) {
       $this->obj->subject = $_GET['subject'];
     }
     if ( isset($_GET['start']) ) {
       $this->obj->start->setDateTime($_GET['start']);
     }
     if ( isset($_GET['end']) ) {
       $this->obj->end->setDateTime($_GET['end']);
     }
     if ( isset($_GET['contact_name']) ) {
       $this->obj->contact_name = $_GET['contact_name'];
     }
     if ( isset($_GET['t_ignore']) ) {
       $this->obj->t_ignore = $_GET['t_ignore'];
     }
     if ( isset($_GET['contact_id']) ) {
       $this->obj->contact_id = $_GET['contact_id'];
     }
     if ( isset($_GET['creator']) ) {
       $this->obj->creator->id = $_GET['creator'];
     }  else {
       $this->obj->check = 0;
     }


     if ( isset($_GET['outside']) ) {
       $this->obj->outside = $_GET['outside'];
     }

     $this->obj->allowed = $this->obj->mod_ok();

    /* if ( ($this->obj->id < 0) && !$this->user->feature_ok(usecalendar,PERM_NEW) ) {
       $msg .= sprintf($lang['Err0054'],$lang[$this->obj->getType()]);
       $this->stop = true;
     } else if (  ! $this->obj->mod_ok() ) {
       $msg .= sprintf($lang['Err0024'],$lang[$this->obj->getType()]);
       $this->stop = true;
     }*/
  /*   # menu
     $m = appointment::getSelectLink($this->user);
     $m[category][] = "obj";
     $this->addmenu($m);
     $m = appointment::getAddLink($this->user,$this->obj);
     $this->addMenu($m);
     if ( $this->obj->id > 0 ) {
       if ( $this->obj->del_ok() ) {
         $m = array( url => $this->obj->getDelURL(),
                     text => $lang['Delete'],
                     info => $lang['AppDelInfo'],
                     confirm => true,
                     category => array("app","del","obj")
                   );
         $this->addMenu($m);
       }
       $m = array( url => $this->obj->getURL(),
                   text => $lang['AppSeeEntry'],
                   info => $lang['AppSeeEntryI'],
                   category => array("app","view")
                 );
       $this->addMenu($m);
     }
     if ( $this->obj->see_ok() && ($this->obj->id > 0) ) {
       $m = array( url => $callink ."app_show&format=ical&id=". $this->obj->id,
                   text => $lang['AppGetIcal'],
                   info => $lang['AppGetIcal'],
                   category => array("app","view")
                 );
       $this->addMenu($m);
     }
     add_module_addlinks($this,$this->obj);*/
   }
 }


 $l = new app_new($current_user);
 $l->display();
 //$dbconn->Close();
?>
<!--
    CVS Info:  $Id$
    $Author$
-->
