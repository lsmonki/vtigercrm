<?php

function renderRelatedContacts($query)
{
  
  global $theme;
  $theme_path="themes/".$theme."/";
  $image_path=$theme_path."images/";
  require_once ($theme_path."layout_utils.php");
  
  
  global $adb;
  global $mod_strings;
  global $app_strings;
  $id = $_REQUEST['record'];
 
  $result=$adb->query($query);
  $list .= '<br><br>';
  $list .= '<table width="100%" cellpadding="0" cellspacing="0" border="0"><tbody><tr>';
  $list .= '<form border="0" action="index.php" method="post" name="form" id="form">';
  $list .= '<input type="hidden" name="module">';
  $list .= '<input type="hidden" name="mode">';
  $list .= '<input type="hidden" name="return_module" value="Activities">';
  $list .= '<input type="hidden" name="return_action" value="DetailView">';
  $list .= '<input type="hidden" name="return_id" value="'.$id.'">';
  $list .= '<input type="hidden" name="parent_id" value="'.$id.'">';
  $list .= '<input type="hidden" name="action">';
  $list .= '<td>';

  $list .= '<table cellpadding="0" cellspacing="0" border="0"><tbody><tr><td class="formHeader" vAlign="top" align="left" height="20"> <img src="' .$image_path. '/left_arc.gif" border="0"></td><td class="formHeader" vAlign="middle" background="' . $image_path. '/header_tile.gif" align="left" noWrap height="20">'.$app_strings['LBL_CONTACT_TITLE'].'</td><td  class="formHeader" vAlign="top" align="right" height="20"><img src="' .$image_path. '/right_arc.gif" border="0"></td> </tr></tbody></table></td>';
  $list .= '<td>&nbsp;</td>';
  $list .= '<td>&nbsp;</td>';
//  $list .= '<td valign="bottom" align="right"><input title="New Contact" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Contacts\'" type="submit" name="button" value="'.$app_strings['LBL_SELECT_BUTTON_LABEL'].'">&nbsp;</td>';
  $list .= '<td valign="bottom" align="right"><input title="Change" accessKey="" tabindex="2" type="button" class="button" value="'.$app_strings['LBL_SELECT_CONTACT_BUTTON_LABEL'].'" name="Button" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Contacts&action=Popup&html=Popup_picker&popuptype=specific&form=EditView","test","width=600,height=400,resizable=1,scrollbars=1");\'>&nbsp;</td>';

  $list .= '</td></tr></form></tbody></table>';

  $list .= '<table border="0" cellpadding="0" cellspacing="0" class="FormBorder" width="100%">';
  $list .= '<tr class="ModuleListTitle" height=20>';

  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle" height="21">';

  $list .= $app_strings['LBL_ACTION'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle">';

  $list .= $app_strings['LBL_CONTACT_NAME'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle">';

  $list .= $app_strings['LBL_DEPARTMENT'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle">';

  $list .= $app_strings['LBL_ROLE'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle">';

  $list .= $app_strings['LBL_EMAIL'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle">';

  $list .= $app_strings['LBL_PHONE'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle">';

  $list .= $app_strings['LBL_LAST_MODIFIED'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle">';
  $list .= '</td>';
  $list .= '</tr>';

  $list .= '<tr><td COLSPAN="14" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td></tr>';

  $i=1;
  while($row = $adb->fetch_array($result))
  {


    if ($i%2==0)
      $trowclass = 'evenListRow';
    else
      $trowclass = 'oddListRow';
    $list .= '<tr class="'. $trowclass.'">';

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="10%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= '<a href="index.php?module=Contacts&action=EditView&return_module=Activities&return_action=DetailView&record='.$row["id"].'&return_id='.$_REQUEST['record'].'">'.$app_strings['LNK_EDIT'].'</a>  | <a href="index.php?module=Contacts&action=Delete&return_module=Activities&return_action=DetailView&record='.$row["id"].'&return_id='.$_REQUEST['record'].'">'.$app_strings['LNK_DELETE'].'</a>';

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="25%"><a href="index.php?module=Contacts&action=DetailView&return_module=Activities&return_action=DetailView&record='.$row["id"].'&return_id='.$_REQUEST['record'].'">'.$row['contactname'].'</td>';

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="15%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= $row['department']; 

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="15%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= $row['role']; 

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="15%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= $row['email']; 

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="10%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= $row['phone']; 

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="10%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= $row['assigned_user_id'];

    $list .= '</td>';

    $list .= '</tr>';
    $i++;
  }

  $list .= '</table>';
  echo $list;

  echo "<BR>\n";
}

function renderRelatedUsers($query)
{
  
  global $theme;
  $theme_path="themes/".$theme."/";
  $image_path=$theme_path."images/";
  require_once ($theme_path."layout_utils.php");
  
  global $adb;
  global $mod_strings;
  global $app_strings;
  // echo 'hi tasks '.$query;
  //echo "<BR>";
  global $adb;
  $id = $_REQUEST['record'];

  $result=$adb->query($query);   
  
  $list .= '<br><br>';
  $list .= '<table width="100%" cellpadding="0" cellspacing="0" border="0"><tbody><tr>';
  $list .= '<form border="0" action="index.php" method="post" name="form" id="form">';
  $list .= '<input type="hidden" name="module">';
  $list .= '<input type="hidden" name="mode">';
  $list .= '<input type="hidden" name="return_module" value="Activities">';
  $list .= '<input type="hidden" name="return_action" value="DetailView">';
  $list .= '<input type="hidden" name="return_id" value="'.$id.'">';
  $list .= '<input type="hidden" name="action">';
  $list .= '<td>';
  $list .= '<table cellpadding="0" cellspacing="0" border="0"><tbody><tr><td class="formHeader" vAlign="top" align="left" height="20"> <img src="' .$image_path. '/left_arc.gif" border="0"></td><td class="formHeader" vAlign="middle" background="' . $image_path. '/header_tile.gif" align="left" noWrap height="20">'.$app_strings['LBL_USER_TITLE'].'</td><td  class="formHeader" vAlign="top" align="right" height="20"><img src="' .$image_path. '/right_arc.gif" border="0"></td> </tr></tbody></table></td>';
  $list .= '<td>&nbsp;</td>';
  $list .= '<td>&nbsp;</td>';
//  $list .= '<td valign="bottom" align="right"><input title="Attach File" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Users\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_USER'].'">&nbsp;</td>';
  $list .= '<td valign="bottom" align="right"><input title="Change" accessKey="" tabindex="2" type="button" class="button" value="'.$app_strings['LBL_SELECT_USER_BUTTON_LABEL'].'" name="Button" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Users&action=Popup&html=Popup_picker&popuptype=specific&form=EditView","test","width=600,height=400,resizable=1,scrollbars=1");\'>&nbsp;</td>';

  $list .= '</td></tr></form></tbody></table>';

  $list .= '<table border="0" cellpadding="0" cellspacing="0" class="FormBorder" width="100%">';
  $list .= '<tr class="ModuleListTitle" height=20>';

  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle" height="21">';

  $list .= $app_strings['LBL_ACTION'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle">';

  $list .= $app_strings['LBL_LIST_NAME'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle">';

  $list .= $app_strings['LBL_LIST_USER_NAME'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle">';

  $list .= $app_strings['LBL_EMAIL'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle">';

  $list .= $app_strings['LBL_PHONE'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle">';

  $list .= '</td>';
  $list .= '</tr>';

  $list .= '<tr><td COLSPAN="10" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td></tr>';

  $i=1;
  while($row = $adb->fetch_array($result))
  {


    if ($i%2==0)
      $trowclass = 'evenListRow';
    else
      $trowclass = 'oddListRow';
    $list .= '<tr class="'. $trowclass.'">';

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="10%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= '<a href="index.php?module=Users&action=EditView&return_module=Activities&return_action=DetailView&record='.$row["id"].'&return_id='.$_REQUEST['record'].'">'.$app_strings['LNK_EDIT'].'</a>  | <a href="index.php?module=Users&action=Delete&return_module=Activities&return_action=DetailView&record='.$row["id"].'&return_id='.$_REQUEST['record'].'">'.$app_strings['LNK_DELETE'].'</a>';

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="30%"><a href="index.php?module=Users&action=DetailView&return_module=Activities&return_action=DetailView&record='.$row["id"].'&return_id='.$_REQUEST['record'].'">'.$row['last_name'].' '.$row['first_name'].'</td>';

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="20%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= $row['user_name'];

	$email = $row['email1'];
	if($email == '')	$email = $row['email2'];
	if($email == '')	$email = $row['yahoo_id'];

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="20%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= '<a href=mailto:'.$email.'>'.$email.'</a>';

	$phone = $row['phone_home'];
	if($phone == '')	$phone = $row['phone_work'];
        if($phone == '')        $phone = $row['phone_other'];
        if($phone == '')	$phone = $row['phone_fax'];

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="20%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= $phone;

    $list .= '</td>';

    $list .= '</tr>';
    $i++;
  }

  $list .= '</table>';
  echo $list;

  echo "<BR>\n";
}

echo get_form_footer();


?>
