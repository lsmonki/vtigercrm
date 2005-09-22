<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/

require_once('include/RelatedListView.php');
require_once('modules/Users/UserInfoUtil.php');

function getHiddenValues($id)
{
	$hidden .= '<form border="0" action="index.php" method="post" name="form" id="form">';
	$hidden .= '<input type="hidden" name="module">';
	$hidden .= '<input type="hidden" name="mode">';
	$hidden .= '<input type="hidden" name="return_module" value="Emails">';
	$hidden .= '<input type="hidden" name="return_action" value="DetailView">';
	$hidden .= '<input type="hidden" name="return_id" value="'.$id.'">';
	$hidden .= '<input type="hidden" name="parent_id" value="'.$id.'">';
	$hidden .= '<input type="hidden" name="email_id" value="'.$id.'">';
	$hidden .= '<input type="hidden" name="action">';
	return $hidden;
}

function renderRelatedContacts($query,$id)
{
	global $vtlog;
	global $mod_strings;
	global $app_strings;

	$hidden = getHiddenValues($id);
       	$hidden .= '<input type="hidden" name="query" value="'.$query.'">';
	echo $hidden;

	$focus = new Contact();
 
	$button .= '<input title="Bulk Mail" accessyKey="F" class="button" onclick="this.form.action.value=\'sendmail\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Emails\';this.form.return_module.value=\'Emails\';" type="submit" name="button" value="'.$mod_strings['LBL_BULK_MAILS'].'">&nbsp;';

        if(isPermitted("Contacts",3,"") == 'yes')
        {
		$button .= '<input title="Change" accessKey="" tabindex="2" type="button" class="button" value="'.$app_strings['LBL_SELECT_CONTACT_BUTTON_LABEL'].'" name="Button" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Contacts&return_module=Emails&action=Popup&popuptype=detailview&form=EditView&form_submit=false&recordid='.$_REQUEST["record"].'","test","width=600,height=400,resizable=1,scrollbars=1");\'>&nbsp;';
	}
	$returnset = '&return_module=Emails&return_action=DetailView&return_id='.$id;

	$list = GetRelatedList('Emails','Contacts',$focus,$query,$button,$returnset);
	$vtlog->logthis("Contact Related List for Email is Displayed",'info');
	echo '</form>';
}

function renderRelatedAttachments($query,$id)
{
	global $vtlog;
        $hidden = getHiddenValues($id);
        echo $hidden;

        getAttachmentsAndNotes('Emails',$query,$id);
	$vtlog->logthis("Notes&Attachments Related List for Email is Displayed",'info');
        echo '</form>';
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
  $list .= '<input type="hidden" name="mailto" value="users">';
  $list .= '<input type="hidden" name="query" value="'.$query.'">';
  $list .= '<input type="hidden" name="return_module" value="Emails">';
  $list .= '<input type="hidden" name="return_action" value="DetailView">';
  $list .= '<input type="hidden" name="return_id" value="'.$id.'">';
  $list .= '<input type="hidden" name="action">';
  $list .= '<td>';
  $list .= '<table cellpadding="0" cellspacing="0" border="0"><tbody><tr><td class="formHeader" vAlign="top" align="left" height="20"> <img src="' .$image_path. '/left_arc.gif" border="0"></td><td class="formHeader" vAlign="middle" background="' . $image_path. '/header_tile.gif" align="left" noWrap height="20">'.$mod_strings['LBL_USER_TITLE'].'</td><td  class="formHeader" vAlign="top" align="right" height="20"><img src="' .$image_path. '/right_arc.gif" border="0"></td> ';
  $list .= '<td>&nbsp;</td>';
//  $list .= '<td>&nbsp;</td>';
//  $list .= '<td valign="bottom" align="right"><input title="Attach File" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Users\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_USER'].'">&nbsp;</td>';
  $list .= '<td valign="bottom" align="left"><input title="Bulk Mail" accessyKey="F" class="button" onclick="this.form.action.value=\'sendmail\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Emails\';this.form.return_module.value=\'Emails\';" type="submit" name="button" value="'.$mod_strings['LBL_BULK_MAILS'].'">&nbsp;';
  $list .= '<input title="Change" accessKey="" tabindex="2" type="button" class="button" value="'.$app_strings['LBL_SELECT_USER_BUTTON_LABEL'].'" name="Button" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Users&return_module=Emails&action=Popup&popuptype=detailview&form=EditView&form_submit=true&return_id='.$_REQUEST["record"].'&recordid='.$_REQUEST["record"].'","test","width=600,height=400,resizable=1,scrollbars=1");\'>&nbsp;</td>';

  $list .= '</td></tr></form></tbody></table>';
  $list .= '</td></table>';

  $list .= '<table border="0" cellpadding="0" cellspacing="0" class="formHeaderULine" width="100%">';
  $list .= '<tr height=1><td height=1></td></tr></table>';
 
  $noofrows = $adb->num_rows($result);
  if ($noofrows > 15)
  {
	$list .= '<div style="overflow:auto;height:315px;width:100%;">';
  }

  $list .= '<table border="0" cellpadding="0" cellspacing="0" class="FormBorder" width="100%">';
  $list .= '<tr class="ModuleListTitle" height=20>';

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
  $list .= '<td class="moduleListTitle" height="21">';

  $list .= $app_strings['LBL_ACTION'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle">';

  $list .= '</td>';
  $list .= '</tr>';

  $list .= '<tr><td COLSPAN="10" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td></tr>';

  $i=1;
  while($row = $adb->fetch_array($result))
  {

    global $current_user;

    if ($i%2==0)
      $trowclass = 'evenListRow';
    else
      $trowclass = 'oddListRow';
    $list .= '<tr class="'. $trowclass.'">';

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    if(is_admin($current_user))
    {
    	$list .= '<td width="30%"><a href="index.php?module=Users&action=DetailView&return_module=Emails&return_action=DetailView&record='.$row["id"].'&return_id='.$_REQUEST['record'].'">'.$row['last_name'].' '.$row['first_name'].'</td>';
    }
    else
    {
    	$list .= '<td width="30%">'.$row['last_name'].' '.$row['first_name'].'</td>';
    }		

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
        if($phone == '')        $phone = $row['phone_mobile'];
        if($phone == '')        $phone = $row['phone_other'];
        if($phone == '')        $phone = $row['phone_fax'];

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="20%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= $phone;

    $list .= '</td>';

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="10%" height="21" style="padding:0px 3px 0px 3px;">';
    //Adding Security Check for User
    if(is_admin($current_user))
    {	 			
    	$list .= '<a href="index.php?module=Users&action=EditView&return_module=Emails&return_action=DetailView&record='.$row["id"].'&return_id='.$_REQUEST['record'].'">'.$app_strings['LNK_EDIT'].'</a>  |';
    }		
        $list .= '<a href="index.php?module=Users&action=Delete&return_module=Emails&return_action=DetailView&record='.$row["id"].'&return_id='.$_REQUEST['record'].'">'.$app_strings['LNK_DELETE'].'</a>';

    $list .= '</tr>';
    $i++;
  }

  $list .= '<tr><td COLSPAN="10" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td></tr>';
  $list .= '</table>';
  if ($noofrows > 15)
  {
	  $list .='</div>';
  }

  echo $list;

  echo "<BR>\n";
}


echo get_form_footer();


?>
