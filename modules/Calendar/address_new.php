<?php
/**
 * Copyright 1999 - 2004 by Gero Kohnert
 */
 include_once 'webelements.p3';
 include_once 'permission.p3';

 /* Check if user is allowed to use it */
 check_user();
 loadmodules("address","new");
 loadlayout();

 /**
  * display a address/location change/create form
  */
 class address_new extends layout {
   /**
    *
    */
   Function ask_location() {
     global $lang,$table;

     $modloc = true;

     echo "<tr>\n";

     # Address Desription
     $this->add_entry ($lang['AdrCategory'],'category',$this->loc->category,$modloc,30,3);
     echo "</tr><tr>\n";
     # Address Desription
     $this->add_entry ($lang['AdrDescription'],'lname',$this->loc->lname,$modloc,$table['address1']['name'][size]);
     $this->add_entry ($lang['Description'],'desc1',$this->loc->desc1,$modloc,$table['location']['desc1'][size]);
     echo "</tr><tr>\n";

     # COMPANY/NAME
     if ( isset($this->loc->cl) ) {
       echo $this->showfield($lang['Company']);
       echo "<td><select name=\"c_id\">\n";
       foreach($this->loc->cl as $i => $f ) {
         echo "  <option value=\"". $i ."\">". myentities($f->name) ."</option>\n";
       }
       echo "  <option value=\"-1\">None</option>\n";
       echo "</select>\n";
       # Copy company location
       echo "<br /><input type=\"checkbox\" value=\"1\" name=\"cploc\" ".($this->loc->cploc == 1 ? "checked=\"checked\"":"") ." />". $lang['AdrCpLoc'];
       echo "</td>\n";
     } else {
       $this->add_entry ($lang['Company'],'company',$this->loc,$modloc,$table['company']['name'][size]);
     }
     # EMAIL
     $this->add_entry ($lang['AdrEmail']. " #1",'email_1',$this->loc->email_1,$modloc,$table['location']['email_1'][size]);
     echo "</tr><tr>\n";

     # DEPARTMENT
     if ( isset($this->loc->dl) ) {
       echo $this->showfield($lang['Department']);
       echo "<td><select name=\"d_id\">\n";
       foreach($this->loc->dl as $i => $f ) {
         echo "  <option value=\"". $i ."\">". myentities($f->name) ." / ". myentities($f->company->name) ."</option>\n";
       }
       echo "  <option value=\"-1\">None</option>\n";
       echo "</select>\n";
       echo "</td>\n";
     } else {
       $this->add_entry ($lang['Department'],'department',$this->loc->department_name,$modloc,$table['department']['name'][size]);
     }
     # EMAIL
     $this->add_entry ($lang['AdrEmail']." #2",'email_2',$this->loc->email_2,$modloc,$table['location']['email_2'][size]);
     echo "</tr><tr>\n";

     # STREET
     $this->add_entry ($lang['Street'],'street1',$this->loc->street1,$modloc,$table['location']['street1'][size]);
     # PHONE
     $this->add_entry ($lang['Phone'],'phone_1',$this->loc->phone_1,$modloc,$table['location']['phone_1'][size]);
     echo "</tr><tr>\n";

     # STREET
     $this->add_entry ($lang['Street'],'street2',$this->loc->street2,$modloc,$table['location']['street2'][size]);
     # PHONE
     $this->add_entry ($lang['MobilePhone'],'phone_2',$this->loc->phone_2,$modloc,$table['location']['phone_2'][size]);
     echo "</tr><tr>\n";

     # CITY
     $this->add_entry ($lang['City'],'city',$this->loc->city,$modloc,$table['location']['city'][size]);
     # FAX
     $this->add_entry ($lang['AdrFax'],'fax_1',$this->loc->fax_1,$modloc,$table['location']['fax_1'][size]);
     echo "</tr><tr>\n";

     # ZIP
     $this->add_entry ($lang['ZIP'],'zip',$this->loc->zip,$modloc,$table['location']['zip'][size]);
     echo "<td colspan=\"2\">&nbsp;</td>";
     echo "</tr><tr>\n";

     # STATE
     $this->add_entry ($lang['State'],'state',$this->loc->state,$modloc,$table['location']['state'][size]);
     echo "<td colspan=\"2\">&nbsp;</td>";
     echo "</tr><tr>\n";

     # COUNTRY
     $this->add_entry ($lang['Country'],'country',$this->loc->country,$modloc,$table['location']['country'][size],3);

     echo "</tr>\n";
   }
   /**
    *
    */
   Function ask_address() {
     global $lang,$table,$tutos;

     echo "<tr>\n";
     # TITLE
     $this->add_entry ($lang['AdrTitle'],'title',$this->obj->title,$this->modadr,$table['address']['title'][size],3);
     echo "</tr><tr>\n";

     # FIRST NAME
     $this->add_entry ($lang['AdrFirstName'],'f_name',$this->obj->f_name,$this->modadr,$table['address']['f_name'][size],3);
     echo "</tr><tr>\n";

     # MIDDLE NAME
     $this->add_entry ($lang['AdrMiddleName'],'m_name',$this->obj->m_name,$this->modadr,$table['address']['m_name'][size],3);
     echo "</tr><tr>\n";

     # LAST NAME
     $this->add_entry ($lang['AdrLastName'],'l_name',$this->obj->l_name,$this->modadr,$table['address']['l_name'][size],3);
     echo "</tr><tr>\n";

     # BIRTHDAY
     $this->add_entry ($lang['AdrBirthday'],'birthday',$this->obj->birthday,$this->modadr,0,3);
     echo "</tr>\n";

     if ( class_exists('tutos_file') && $this->user->feature_ok(usedocmanagement,PERM_NEW) ) {
       # Picture
       if ( $this->modadr ) {
         echo "<tr>\n";
         $this->obj->read_picture();
         $this->add_entry ($lang['AdrPicture'],'picture',$this->obj->pic_file,$this->modadr,30,3);
         echo "</tr>\n";
       }
     }
     if ( $this->modadr ) {
       # References to modules
       module_addforms($this->user,$this->obj,4);
     }

   }
   /**
    *
    */
   Function info() {
     global $lang,$table;

     echo "<form name=\"adrnew\" enctype=\"multipart/form-data\" action=\"address_ins.php\" method=\"post\">\n";

     echo $this->DataTableStart();


     if ( $this->obj->id > 0 ) {
       $this->addHidden("id",$this->obj->id);
       # Its an update
       $title = $lang['AdrBChangeTitle'];
     } else {
       $title = $lang['AdrBNewTitle'];
	 }

     $title .= " / ";

     if ( $this->loc->id > 0 ) {
       $this->addHidden("loc_id",$this->loc->id);
       $title .= sprintf($lang['AdrLocChange'],myentities($this->obj->getFullName()));
     } else {
       $title .= sprintf($lang['AdrLocCreate'],myentities($this->obj->getFullName()));
     }

     echo " <tr><th colspan=\"4\">". $title ."</th></tr>\n";

     if ( $this->obj->id > 0)  {
       echo "<tr><td colspan=\"4\" align=\"right\">&nbsp;";
       echo acl_link($this->obj);
       echo "</td></tr>\n";
     }

     $this->ask_address();
     if ( $this->loc->id > -1)  {
       echo "<tr><td colspan=\"4\" align=\"right\">&nbsp;";
       echo acl_link($this->loc);
       echo "</td></tr>\n";
     }
     $this->ask_location();

     echo "<tr>\n";
     if ( $this->obj->id > 0 ) {
       submit_reset(0,1,1,1,1,0);
     } else {
       submit_reset(0,-1,1,1,1,0);
     }
     echo "</tr>\n";
     echo $this->DataTableEnd();
     echo $this->getHidden();
     hiddenFormElements();
     echo $this->setfocus("adrnew.lname");
     echo "</form>\n";
     echo $lang['FldsRequired'] ."\n";
   }
   /**
   * Add a Form Entry
    */
   function add_entry($text,$varname,$varvalue,$mod,$size,$width = 1) {
     global $lang;

     $showsize = min($size,30);

     if ( ($varname == 'f_name') ||
          ($varname == 'l_name') ||
          ($varname == 'lname') ||
          ($varname == 'category') ) {
       $req = 1;
     } else {
       $req = 0;
     }
     echo $this->showfieldc($text . "&nbsp;",$req,$varname);
     echo "<td colspan=\"". $width ."\">\n";
     if ( $varname == 'birthday' ) {
       if ( $mod == true ) {
         $varvalue->EnterDate($varname,1);
       } else {
         echo $varvalue->getLinkDate();
         $this->addHidden("mon_". $varname,$varvalue->month);
         $this->addHidden("day_". $varname,$varvalue->day);
         $this->addHidden("year_". $varname,$varvalue->year);
       }
     } elseif ( $varname == "picture" ) {
       if ( $mod == true ) {
         echo " <input size=\"30\" id=\"picture\" name=\"file\" type=\"file\" value=\"". myentities($varvalue->pic_path) ."\" />\n";
         $this->addHidden("pic_id",$varvalue->id);
         if ( $varvalue->id > 0 ) {
           echo "<br />". $varvalue->getLink($lang['AdrPicture']);
         }
       }
     } elseif ( $varname == "category" ) {
       if ($varvalue > 2) {
         echo "&nbsp;<input type=\"hidden\" id=\"category\" name=\"". $varname ."\"  value=\"". $varvalue ."\" />";
       } else { 
         $cchecked[1] = "";
         $cchecked[2] = "";
         $cchecked[$varvalue] = "checked=\"checked\"";
         echo $lang['AdrCat1'] ."<input type=\"radio\" id=\"category\" name=\"". $varname ."\"  value=\"1\" ". $cchecked[1] ." />";
         echo "&nbsp;&nbsp;". $lang['AdrCat2'] ."<input type=\"radio\" name=\"". $varname ."\"  value=\"2\" ". $cchecked[2] ." />";
       }
     } elseif ( $varname == "company" ) {
       if ( $mod == true ) {
         echo "<input id=\"". $varname ."\" name=\"". $varname ."\" size=\"". $showsize ."\" maxlength=\"". $size ."\" value=\"". myentities($varvalue->company_name) ."\" />";
         echo "<br /><input type=\"checkbox\" value=\"1\" name=\"cploc\"". ( $varvalue->cploc == 1 ? " checked=\"checked\"":"") ." />". $lang['AdrCpLoc'];
       } else {
         if ( $varvalue == "" ) {
           echo "&nbsp;";
         } else {
           echo $varvalue;
         }
         $this->addHidden($varname,$varvalue);
       }
     } elseif ( $varname == "country" ) {
         SelectCntryCde($varname,$varvalue);
     } else {
       if ( $mod == true ) {
         echo "<input id=\"". $varname ."\"  name=\"". $varname ."\" size=\"". $showsize ."\" maxlength=\"". $size ."\" value=\"". myentities($varvalue) ."\" />";
       } else {
         if ( $varvalue == "" ) {
           echo "&nbsp;";
         } else {
           echo $varvalue;
         }
         $this->addHidden($varname,$varvalue);
       }
     }
     echo "</td>\n";
   }
   /**
    * navigate
    */
   Function navigate() {
     global $lang;

     echo "<tr><td>\n";

     if ( $this->obj->id > 0 ) {

       if ( $this->obj->mod_ok()  ) {
         if ( count($this->obj->location) > 0 ) {
           DoubleTableStart();
           foreach ($this->obj->location as $i => $f) {
             echo "<tr><th colspan=\"2\">". sprintf($lang['AdrLocTitle'],myentities($f->lname)) ."</th></tr>\n";
             echo "<tr>\n";
             echo " <td>\n";
             # FIXME check mod_ok
             if ( $f->mod_ok() ) {
               echo menulink("address_new.php?id=". $this->obj->id ."&loc_id=". $i,$lang['Change'],sprintf($lang['ChangeLocInfo'],$f->lname, $this->obj->getFullName()));
             } else {
               echo $lang['Change'];
             }
             echo " </td><td>\n";
             # FIXME check del_ok
             if ( $f->del_ok() ) {
               echo confirmlink("location_del.php?ref=". $this->obj->id ."&id=". $i,$lang['Delete'],sprintf($lang['DeleteLocInfo'],$f->lname, $this->obj->getFullName()));
             } else {
               echo $lang['Delete'];
             }
             echo " </td>\n";
             echo "</tr>\n";
           }
           DoubleTableEnd();
         }
         echo menulink("address_new.php?id=". $this->obj->id ."&loc_id=-1",$lang['AdrLNew'],sprintf($lang['AdrLNewInfo'],$this->obj->getFullName())) ."<br />";
       }

     }
     echo "</td></tr>\n";
   }
   /**
    * prepare
    */
   Function prepare() {
     global $msg,$lang;

     $this->name = $lang['Addresses'];
     $this->obj = new tutos_address($this->dbconn);
     $this->loc = new location($this->dbconn);
     $this->modloc = true;
     $this->modadr = true;

     if ( isset($_GET['id']) && ($_GET['id'] != "-1" )) {
       $id = $_GET['id'];
       # Modify Base Address
       $this->obj = $this->obj->read($id,$this->obj);
       $this->obj->read_locs_data();
       if ($this->obj->id < 0) {
         $msg .= sprintf($lang['Err0040'],$lang[$this->obj->getType()]);
         $this->stop = true;
       }
     }

     if ( isset($_GET['loc_id']) && ($_GET['loc_id'] != "-1" )) {
       $loc_id = $_GET['loc_id'];
       $this->loc = $this->loc->read($loc_id,$this->loc);
     } else if ( isset($_GET['loc_id']) && ($_GET['loc_id'] == "-1" )) {
       # use a empty new location
     } else {
       # if there is no default location use a empty new location
#       foreach ($this->obj->location as $loc_id => $this->loc) {
#	   }
	 }

     if ( isset($loc_id) ) {
       if ( ($loc_id == -1) ) {
         $this->loc->id = $loc_id;
       } else {
         $this->loc->lname = $this->obj->loc[$loc_id];
         if ($this->loc->id < 0) {
           $msg .= sprintf($lang['Err0040'],$lang[$this->loc->getType()]);
           $this->stop = true;
         } else if ( ! $this->loc->mod_ok() ) {
           $msg .= sprintf($lang['Err0024'],$lang[$this->loc->getType()]);
           $this->stop = true;
         }
       }
	 }

     if ( isset($this->loc->company) ) {
       $this->loc->company_name = $this->loc->company->name;
     }
     if ( isset($this->loc->department) ) {
       $this->loc->department_name = $this->loc->department->name;
     }
     if ( isset($_GET['company']) ) {
       $this->loc->company_name = StripSlashes($_GET['company']);
     }
     if ( isset($_GET['department']) ) {
       $this->loc->department_name = StripSlashes($_GET['department']);
     }
     if ( isset($_GET['lname']) ) {
       $this->loc->lname = StripSlashes($_GET['lname']);
     }
     if ( isset($_GET['category']) ) {
       $this->loc->category = $_GET['category'];
     }
     if ( isset($_GET['cploc']) ) {
       $this->loc->cploc = $_GET['cploc'];
     } else {
       $this->loc->cploc = 0;
     }
     if ( isset($_GET['cl']) ) {
       $cl = $_GET['cl'];
       foreach ($cl as $f) {
         $c = new company($this->dbconn);
         $c = $c->read($f,$c);
         $this->loc->cl[$f] = $c;
       }
     }
     if ( isset($_GET['dl']) ) {
       $dl = $_GET['dl'];
       foreach ($dl as $f)  {
         $d = new department($this->dbconn);
         $d = $d->read($f,$d);
         $this->loc->dl[$f] = $d;
       }
     }
     foreach($this->loc->larray as $i => $f) {
       if ( isset($_GET[$f]) ) {
         $this->loc->$f = StripSlashes($_GET[$f]);
       }
       $i++;
     }
     if ( isset($_GET['title']) ) {
       $this->obj->title = StripSlashes($_GET['title']);
     }
     if ( isset($_GET['f_name']) ) {
       $this->obj->f_name = StripSlashes($_GET['f_name']);
     }
     if ( isset($_GET['m_name']) ) {
       $this->obj->m_name = StripSlashes($_GET['m_name']);
     }
     if ( isset($_GET['l_name']) ) {
       $this->obj->l_name = StripSlashes($_GET['l_name']);
     }
     if ( isset($_GET['bd']) ) {
       $this->obj->birthday->setDateTime($_GET['bd']);
     }
     if ( isset($_GET['pic_path']) ) {
       $this->obj->pic_file->pic_path = StripSlashes($_GET['pic_path']);
     } else {
       $this->obj->pic_file->pic_path = "";
     }
     if (($this->obj->id < 0) && !$this->user->feature_ok(useaddressbook,PERM_NEW) ) {
       $msg .= sprintf($lang['Err0054'],$lang[$this->obj->getType()]);
       $this->stop = true;
     } else if ( ! $this->obj->mod_ok() ) {
       $msg .= sprintf($lang['Err0024'],$lang[$this->obj->getType()]);
       $this->stop = true;
     }

     # Menu      
     $x = tutos_address::getSelectLink($this->user,$lang['Search']);
     $x[category][] = "obj";
     $this->addMenu($x);
     if ( $this->user->feature_ok(useaddressbook,PERM_NEW) ) {
       $x = array( url => "address_new.php",
                   confirm => false,
                   text => $lang['AdrBNew'],
                   info => $lang['AdrBNewInfo'],
                   category => array("address","new","obj")
                 );
       $this->addMenu($x);
     }
     if ( ($this->obj->id > 0) && $this->obj->mod_ok() ) {
       $x = array( url => $this->obj->getModURL(),
                   confirm => false,
                   text => $lang['AdrBMod'],
                   info => sprintf($lang['AdrBModInfo'],$this->obj->getFullName()),
                   category => array("address","mod","obj")
                 );
       $this->addMenu($x);
     }
     if ( ($this->obj->id > 0) && $this->obj->del_ok() ) {
       $x = array( url => $this->obj->getDelURL(),
                   confirm => true,
                   text => $lang['AdrBDel'],
                   info => sprintf($lang['AdrBDelInfo'],$this->obj->getFullName()),
                   category => array("address","del","obj")
                 );
       $this->addMenu($x);
     }


     if ( $this->user->admin == 1 ) {
       $x = array( url => "team_new.php",
                   confirm => false,
                   text => $lang['TeamCreate'],
                   info => $lang['TeamCreateI'],
                   category => array("team","new","support")
                 );
       $this->addMenu($x);
     }
     if ( $this->user->feature_ok(usecompany,PERM_NEW) ) {
       $x = array( url => "company_new.php",
                   confirm => false,
                   text => $lang['CompanyCreate'],
                   info => $lang['CompanyCreateInfo'],
                   category => array("company","new","support")
                 );
       $this->addMenu($x);
	 }
     if ( $this->user->feature_ok(usedepartment,PERM_NEW) ) {
       $x = array( url => "department_new.php",
                   confirm => false,
                   text => $lang['DepartmentCreate'],
                   info => $lang['DepCreateInfo'],
                   category => array("department","new","support")
                 );
       $this->addMenu($x);
     }
     if ( $this->obj->id > 0 ) {
       $x = array( url => $this->obj->getURL(),
                   text => $lang['AdrSeeEntry'],
                   info => sprintf($lang['AdrSeeEntryI'],$this->obj->getFullName()),
                   category => array("address","view")
                 );
       $this->addMenu($x);

       if ( $this->obj->isUser() != 0 ) {
         if ( $this->obj->user->mod_ok() ) {
           $x = array( url => "user_new.php?id=". $this->obj->id ,
                       text => $lang['UserModify'],
                       info => sprintf($lang['UserModInfo'],$this->obj->getFullName()),
                       category => array("user","module","mod")
                     );
           $this->addMenu($x);
         }
       } else if ( $this->user->feature_ok(useuser,PERM_NEW) )  {
           $x = array( url => "user_new.php?id=". $this->obj->id ,
                       text => $lang['UserCreate'],
                       info => sprintf($lang['UserNewInfo'],$this->obj->getFullName()),
                       category => array("user","module","new")
                     );
           $this->addMenu($x);
       }
     }

     add_module_newlinks($this,$this->obj);
     
     web_StackStartLayout($this,"address_new.php","address_new.php");     
   }
 }

 $l = new address_new($current_user);
 $l->display();
 $dbconn->Close();
?>
<!--
    CVS Info:  $Id$
    $Author$
-->