<?php

function renderRelatedLeads($query)
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
  $list .= '<form action="index.php" method="post" name="EditView" id="form">';
  $list .= '<input type="hidden" name="module">';
  $list .= '<input type="hidden" name="mode">';
  $list .= '<input type="hidden" name="return_module" value="Emails">';
  $list .= '<input type="hidden" name="return_action" value="DetailView">';
  $list .= '<input type="hidden" name="return_id" value="'.$id.'">';
  $list .= '<input type="hidden" name="parent_id" value="'.$id.'">';
  $list .= '<input type="hidden" name="entity_id">';
  $list .= '<input type="hidden" name="action">';
  $list .= '<td>';
  $list .= '<table cellpadding="0" cellspacing="0" border="0"><tbody><tr><td class="formHeader" vAlign="top" align="left" height="20"> <img src="' .$image_path. '/left_arc.gif" border="0"></td><td class="formHeader" vAlign="middle" background="' . $image_path. '/header_tile.gif" align="left" noWrap height="20">'.$mod_strings['LBL_LEAD_TITLE'].'</td><td  class="formHeader" vAlign="top" align="right" height="20"><img src="' .$image_path. '/right_arc.gif" border="0"></td> </tr></tbody></table></td>';
  $list .= '<td>&nbsp;</td>';
  $list .= '<td>&nbsp;</td>';
//  $list .= '<td valign="bottom" align="right"><input title="New Lead" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Leads\'" type="submit" name="button" value="'.$app_strings['LBL_SELECT_BUTTON_LABEL'].'">&nbsp;</td>';
  $list .= '<td valign="bottom" align="right"><input title="'.$app_strings['LBL_SELECT_BUTTON_LABEL'].'" accessKey="'.$app_strings['LBL_SELECT_BUTTON_LABEL'].'" type="button" tabindex="1" class="button" value="'.$app_strings['LBL_SELECT_BUTTON_LABEL'].'" name="btn1" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Leads&action=Popup&popuptype=detailview&form=EditView&form_submit=false&recordid='.$_REQUEST["record"].'","test","width=600,height=400,resizable=1,scrollbars=1");\'>&nbsp;</td>';

  $list .= '</td></tr></form></tbody></table>';

  $list .= '<table border="0" cellpadding="0" cellspacing="0" class="FormBorder" width="100%">';
  $list .= '<tr class="ModuleListTitle" height=20>';

  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle" height="21">';

  $list .= $app_strings['LBL_ACTION'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle">';

  $list .= $app_strings['LBL_LEAD_NAME'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle">';

  $list .= $app_strings['LBL_STATUS'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle">';

  $list .= $app_strings['LBL_LAST_MODIFIED'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle">';

  $list .= '</td>';
  $list .= '</tr>';

  $list .= '<tr><td COLSPAN="9" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td></tr>';

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
    $list .= '<a href="index.php?module=Leads&action=EditView&return_module=Emails&return_action=DetailView&record='.$row["leadid"].'&return_id='.$_REQUEST["record"] .'">'.$app_strings['LNK_EDIT'].'</a>  |  <a href="index.php?module=Leads&action=Delete&return_module=Emails&return_action=DetailView&record='.$row["leadid"].'&return_id='.$_REQUEST["record"] .'">'.$app_strings['LNK_DELETE'].'</a>';

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="40%" height="21" style="padding:0px 3px 0px 3px;">';    
    $list .= '<a href="index.php?module=Leads&action=DetailView&return_module=Emails&return_action=DetailView&record='.$row["leadid"].'&return_id='.$_REQUEST['record'].'">'.$row['lastname'].' '.$row['firstname']; 

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="30%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= $row['leadstatus'];

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="20%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= $row['modifiedtime']; 

    $list .= '</td>';

    $list .= '</tr>';
    $i++;
  }

  $list .= '</table>';
  echo $list;

  echo "<BR>\n";
}

function renderRelatedAccounts($query)
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
  $list .= '<input type="hidden" name="return_module" value="Emails">';
  $list .= '<input type="hidden" name="return_action" value="DetailView">';
  $list .= '<input type="hidden" name="return_id" value="'.$id.'">';
  $list .= '<input type="hidden" name="parent_id" value="'.$id.'">';
  $list .= '<input type="hidden" name="action">';
  $list .= '<td>';

  $list .= '<table cellpadding="0" cellspacing="0" border="0"><tbody><tr><td class="formHeader" vAlign="top" align="left" height="20"> <img src="' .$image_path. '/left_arc.gif" border="0"></td><td class="formHeader" vAlign="middle" background="' . $image_path. '/header_tile.gif" align="left" noWrap height="20">'.$mod_strings['LBL_ACCOUNT_TITLE'].'</td><td  class="formHeader" vAlign="top" align="right" height="20"><img src="' .$image_path. '/right_arc.gif" border="0"></td> </tr></tbody></table></td>';
  $list .= '<td>&nbsp;</td>';
  $list .= '<td>&nbsp;</td>';

  $list .= '<td valign="bottom" align="right"><input title="'.$app_strings['LBL_SELECT_BUTTON_LABEL'].'" accessKey="'.$app_strings['LBL_SELECT_BUTTON_LABEL'].'" type="button" tabindex="1" class="button" value="'.$app_strings['LBL_SELECT_BUTTON_LABEL'].'" name="btn1" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Accounts&action=Popup&popuptype=detailview&form=EditView&form_submit=false&recordid='.$_REQUEST["record"].'","test","width=600,height=400,resizable=1,scrollbars=1");\'>&nbsp;</td>';

  $list .= '</td></tr></form></tbody></table>';

  $list .= '<table border="0" cellpadding="0" cellspacing="0" class="FormBorder" width="100%">';
  $list .= '<tr class="ModuleListTitle" height=20>';

  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle" height="21">';

  $list .= $app_strings['LBL_ACTION'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle">';

  $list .= $app_strings['LBL_ACCOUNT_NAME'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle">';

  $list .= $app_strings['LBL_ACCOUNT_TYPE'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle">';

  $list .= $app_strings['LBL_LAST_MODIFIED'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle">';

  $list .= '</td>';
  $list .= '</tr>';

  $list .= '<tr><td COLSPAN="9" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td></tr>';

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
    $list .= '<a href="index.php?module=Accounts&action=EditView&return_module=Emails&return_action=DetailView&record='.$row["accountid"].'&return_id='.$_REQUEST['record'].'">'.$app_strings['LNK_EDIT'].'</a>  | <a href="index.php?module=Accounts&action=Delete&return_module=Emails&return_action=DetailView&record='.$row["accountid"].'&return_id='.$_REQUEST['record'].'">'.$app_strings['LNK_DELETE'].'</a>';

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="40%"><a href="index.php?module=Accounts&action=DetailView&return_module=Emails&return_action=DetailView&record='.$row["accountid"].'&return_id='.$_REQUEST['record'].'">'.$row['accountname'];

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="30%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= $row['account_type']; 

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="20%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= $row['modifiedtime']; 

    $list .= '</td>';

    $list .= '</tr>';
    $i++;
  }

  $list .= '</table>';
  echo $list;

  echo "<BR>\n";
}

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
  $list .= '<input type="hidden" name="return_module" value="Emails">';
  $list .= '<input type="hidden" name="return_action" value="DetailView">';
  $list .= '<input type="hidden" name="return_id" value="'.$id.'">';
  $list .= '<input type="hidden" name="parent_id" value="'.$id.'">';
  $list .= '<input type="hidden" name="query" value="'.$query.'">';
  $list .= '<input type="hidden" name="action">';
  $list .= '<td>';

  $list .= '<table cellpadding="0" cellspacing="0" border="0"><tbody><tr><td class="formHeader" vAlign="top" align="left" height="20"> <img src="' .$image_path. '/left_arc.gif" border="0"></td><td class="formHeader" vAlign="middle" background="' . $image_path. '/header_tile.gif" align="left" noWrap height="20">'.$mod_strings['LBL_CONTACT_TITLE'].'</td><td  class="formHeader" vAlign="top" align="right" height="20"><img src="' .$image_path. '/right_arc.gif" border="0"></td> </tr></tbody></table></td>';
  $list .= '<td>&nbsp;</td>';
  $list .= '<td>&nbsp;</td>';
//  $list .= '<td valign="bottom" align="right"><input title="New Contact" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Contacts\'" type="submit" name="button" value="'.$app_strings['LBL_SELECT_BUTTON_LABEL'].'">&nbsp;</td>';
  $list .= '<td valign="bottom" align="right"><input title="Bulk Mail" accessyKey="F" class="button" onclick="this.form.action.value=\'sendmail\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Emails\';this.form.return_module.value=\'Emails\';" type="submit" name="button" value="'.$mod_strings['LBL_BULK_MAILS'].'">&nbsp;';
  $list .= '<input title="Change" accessKey="" tabindex="2" type="button" class="button" value="'.$app_strings['LBL_SELECT_CONTACT_BUTTON_LABEL'].'" name="Button" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Contacts&return_module=Emails&action=Popup&popuptype=detailview&form=EditView&form_submit=false&recordid='.$_REQUEST["record"].'","test","width=600,height=400,resizable=1,scrollbars=1");\'>&nbsp;</td>';

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
    $list .= '<a href="index.php?module=Contacts&action=EditView&return_module=Emails&return_action=DetailView&record='.$row["contactid"].'&return_id='.$_REQUEST['record'].'">'.$app_strings['LNK_EDIT'].'</a>  | <a href="index.php?module=Contacts&action=Delete&return_module=Emails&return_action=DetailView&record='.$row["contactid"].'&return_id='.$_REQUEST['record'].'">'.$app_strings['LNK_DELETE'].'</a>';

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="25%"><a href="index.php?module=Contacts&action=DetailView&return_module=Emails&return_action=DetailView&record='.$row["contactid"].'&return_id='.$_REQUEST['record'].'">'.$row['lastname'].' ' .$row['firstname'].'</td>';

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="15%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= $row['department']; 

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="10%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= $row['role']; 

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="15%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= $row['email']; 

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="10%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= $row['phone']; 

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="15%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= $row['modifiedtime'];

    $list .= '</td>';

    $list .= '</tr>';
    $i++;
  }

  $list .= '</table>';
  echo $list;

  echo "<BR>\n";
}

function renderRelatedPotentials($query)
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
  $list .= '<input type="hidden" name="account_id" value="'.$id.'">';
  $list .= '<input type="hidden" name="return_module" value="Emails">';
  $list .= '<input type="hidden" name="return_action" value="DetailView">';
  $list .= '<input type="hidden" name="return_id" value="'.$id.'">';
  $list .= '<input type="hidden" name="parent_id" value="'.$id.'">';
  $list .= '<input type="hidden" name="action">';
  $list .= '<td>';

  $list .= '<table cellpadding="0" cellspacing="0" border="0"><tbody><tr><td class="formHeader" vAlign="top" align="left" height="20"> <img src="' .$image_path. '/left_arc.gif" border="0"></td><td class="formHeader" vAlign="middle" background="' . $image_path. '/header_tile.gif" align="left" noWrap height="20">'.$app_strings['LBL_POTENTIAL_TITLE'].'</td><td  class="formHeader" vAlign="top" align="right" height="20"><img src="' .$image_path. '/right_arc.gif" border="0"></td> </tr></tbody></table></td>';
  $list .= '<td>&nbsp;</td>';
  $list .= '<td>&nbsp;</td>';
//  $list .= '<td valign="bottom" align="right"><input title="'.$app_strings['LBL_SELECT_BUTTON_LABEL'].'" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Potentials\';this.form.return_action.value=\'DetailView\';this.form.return_module.value=\'Emails\'" type="submit" name="button" value="'.$app_strings['LBL_SELECT_BUTTON_LABEL'].'">&nbsp;</td>';
  $list .= '<td valign="bottom" align="right"><input title="Change" accessKey="" tabindex="2" type="button" class="button" value="'.$app_strings['LBL_SELECT_BUTTON_LABEL'].'" name="Button" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Potentials&action=Popup&popuptype=detailview&form=EditView&form_submit=false&recordid='.$_REQUEST["record"].'","test","width=600,height=400,resizable=1,scrollbars=1");\'>&nbsp;</td>';
  $list .= '</td></tr></form></tbody></table>';

  $list .= '<table border="0" cellpadding="0" cellspacing="0" class="FormBorder" width="100%">';
  $list .= '<tr class="ModuleListTitle" height=20>';

  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle" height="21">';

  $list .= $app_strings['LBL_ACTION'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle">';

  $list .= $app_strings['LBL_POTENTIAL_NAME'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle">';

  $list .= $app_strings['LBL_PRODUCT'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle">';

  $list .= $app_strings['LBL_ENTITY_TYPE'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle">';

  $list .= $app_strings['LBL_AMOUNT'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle">';

  $list .= $app_strings['LBL_CLOSE_DATE'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle">';

  $list .= '</td>';
  $list .= '</tr>';

  $list .= '<tr><td COLSPAN="12" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td></tr>';

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
    $list .= '<a href="index.php?module=Potentials&action=EditView&return_module=Emails&return_action=DetailView&record='.$row["potentialid"].'&return_id='.$id.'">'.$app_strings['LNK_EDIT'].'</a>  |  <a href="index.php?module=Potentials&action=Delete&return_module=Emails&return_action=DetailView&record='.$row["potentialid"].'&return_id='.$id.'">'.$app_strings['LNK_DELETE'].'</a>';

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="30%"><a href="index.php?module=Potentials&action=DetailView&return_module=Emails&return_action=DetailView&record='.$row["potentialid"] .'&return_id='.$id.'">'.$row['potentialname'].'</td>';
    
    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="15%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= $row['productname'];
    $list .= '</td>';

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="15%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= $row['potentialtype'];
    $list .= '</td>';

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="15%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= $row['amount'];
    $list .= '</td>';

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="15%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= $row['closingdate'];

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
  $list .= '<input type="hidden" name="return_module" value="Emails">';
  $list .= '<input type="hidden" name="return_action" value="DetailView">';
  $list .= '<input type="hidden" name="return_id" value="'.$id.'">';
  $list .= '<input type="hidden" name="action">';
  $list .= '<td>';
  $list .= '<table cellpadding="0" cellspacing="0" border="0"><tbody><tr><td class="formHeader" vAlign="top" align="left" height="20"> <img src="' .$image_path. '/left_arc.gif" border="0"></td><td class="formHeader" vAlign="middle" background="' . $image_path. '/header_tile.gif" align="left" noWrap height="20">'.$mod_strings['LBL_USER_TITLE'].'</td><td  class="formHeader" vAlign="top" align="right" height="20"><img src="' .$image_path. '/right_arc.gif" border="0"></td> </tr></tbody></table></td>';
  $list .= '<td>&nbsp;</td>';
  $list .= '<td>&nbsp;</td>';
//  $list .= '<td valign="bottom" align="right"><input title="Attach File" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Users\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_USER'].'">&nbsp;</td>';
  $list .= '<td valign="bottom" align="right"><input title="Change" accessKey="" tabindex="2" type="button" class="button" value="'.$app_strings['LBL_SELECT_USER_BUTTON_LABEL'].'" name="Button" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Users&action=Popup&popuptype=detailview&form=EditView&form_submit=false&return_id='.$_REQUEST["record"].'&recordid='.$_REQUEST["record"].'","test","width=600,height=400,resizable=1,scrollbars=1");\'>&nbsp;</td>';

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
    $list .= '<a href="index.php?module=Users&action=EditView&return_module=Emails&return_action=DetailView&record='.$row["id"].'&return_id='.$_REQUEST['record'].'">'.$app_strings['LNK_EDIT'].'</a>  | <a href="index.php?module=Users&action=Delete&return_module=Emails&return_action=DetailView&record='.$row["id"].'&return_id='.$_REQUEST['record'].'">'.$app_strings['LNK_DELETE'].'</a>';

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="30%"><a href="index.php?module=Users&action=DetailView&return_module=Emails&return_action=DetailView&record='.$row["id"].'&return_id='.$_REQUEST['record'].'">'.$row['last_name'].' '.$row['first_name'].'</td>';

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="20%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= $row['user_name'];

        $email = $row['email1'];
        if($email == '')        $email = $row['email2'];
        if($email == '')        $email = $row['yahoo_id'];

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="20%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= '<a href=mailto:'.$email.'>'.$email.'</a>';

        $phone = $row['phone_home'];
        if($phone == '')        $phone = $row['phone_work'];
        if($phone == '')        $phone = $row['phone_other'];
        if($phone == '')        $phone = $row['phone_fax'];

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

function renderRelatedAttachments($query)
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
  $list .= '<input type="hidden" name="return_module" value="Emails">';
  $list .= '<input type="hidden" name="return_action" value="DetailView">';
  $list .= '<input type="hidden" name="return_id" value="'.$id.'">';
  $list .= '<input type="hidden" name="parent_id" value="'.$id.'">';
  $list .= '<input type="hidden" name="action">';
  $list .= '<td>';
 $list .= '<table cellpadding="0" cellspacing="0" border="0"><tbody><tr><td class="formHeader" vAlign="top" align="left" height="20"> <img src="' .$image_path. '/left_arc.gif" border="0"></td><td class="formHeader" vAlign="middle" background="' . $image_path. '/header_tile.gif" align="left" noWrap height="20">'.$app_strings['LBL_ATTACHMENT_AND_NOTES'].'</td><td  class="formHeader" vAlign="top" align="right" height="20"><img src="' .$image_path. '/right_arc.gif" border="0"></td> </tr></tbody></table></td>';
  $list .= '<td>&nbsp;</td>';
  $list .= '<td>&nbsp;</td>';
  $list .= '<td valign="bottom" align="right"><input title="New Potential" accessyKey="F" class="button" onclick="this.form.action.value=\'upload\';this.form.module.value=\'uploads\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_ATTACHMENT'].'">';
    $list .= '&nbsp;<input title="New Notes" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Notes\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_NOTE'].'">&nbsp;</td>';
//  $list .= '<td width="50%"></td>';

  $list .= '</td></tr></form></tbody></table>';

  $list .= '<table border="0" cellpadding="0" cellspacing="0" class="FormBorder" width="100%">';
  $list .= '<tr class="ModuleListTitle" height=20>';

  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle" height="21">';

  $list .= $app_strings['LBL_ACTION'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle">';


  $list .= $app_strings['LBL_TITLE'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td width="%" class="moduleListTitle">';

  $list .= $app_strings['LBL_ENTITY_TYPE'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td width="%" class="moduleListTitle">';

  $list .= $app_strings['LBL_FILENAME'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td width="%" class="moduleListTitle">';

  $list .= $app_strings['LBL_TYPE'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td width="%" class="moduleListTitle">';

  $list .= $app_strings['LBL_LAST_MODIFIED'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td width="%" class="moduleListTitle">';

  $list .= '</td>';
  $list .= '</tr>';

  $list .= '<tr><td COLSPAN="12" class="blackLine"><IMG SRC="themes/'.$theme.'/images//blank.gif"></td></tr>';

  $i=1;
  while($row = $adb->fetch_array($result))
  {

if($row[1] == 'Notes')
{
	$module = 'Notes';
	$editaction = 'EditView';
	$deleteaction = 'Delete';
}
elseif($row[1] == 'Attachments')
{
	$module = 'uploads';
	$editaction = 'upload';
	$deleteaction = 'deleteattachments';
}

    if ($i%2==0)
      $trowclass = 'evenListRow';
    else
      $trowclass = 'oddListRow';

    $list .= '<tr class="'. $trowclass.'">';

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="10%" height="21" style="padding:0px 3px 0px 3px;">';

if($row[1] == 'Notes')
    $list .= '<a href="index.php?module='.$module.'&action='.$editaction.'&return_module=Emails&return_action=DetailView&record='.$row["noteattachmentid"].'&filename='.$row[2].'&return_id='.$_REQUEST['record'].'">'.$app_strings['LNK_EDIT'].'</a>  | ';
    $list .= ' <a href="index.php?module='.$module.'&action='.$deleteaction.'&return_module=Emails&return_action=DetailView&record='.$row["noteattachmentid"].'&filename='.$row[2].'&return_id='.$_REQUEST['record'].'">'.$app_strings['LNK_DELETE'].'</a>';

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="30%"><a href="index.php?module='.$module.'&action=DetailView&return_module='.$returnmodule.'&return_action='.$returnaction.'&record='.$row["noteattachmentid"] .'&return_id='.$_REQUEST['record'].'">'.$row[0].'</td>';

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="15%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= $row[1];
    $list .= '</td>';

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="15%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= '<a href = "index.php?module=uploads&action=downloadfile&return_module=Emails&activity_type='.$row[1].'&fileid='.$row[5].'&filename='.$row[2].'">'.$row[2].'</a>';
    $list .= '</td>';

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="15%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= $row[3];
    $list .= '</td>';

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="15%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= $row[4];

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
