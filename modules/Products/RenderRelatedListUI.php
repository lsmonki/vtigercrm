<?php

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
  $list .= '<input type="hidden" name="return_module" value="Products">';
  $list .= '<input type="hidden" name="return_action" value="DetailView">';
  $list .= '<input type="hidden" name="return_id" value="'.$id.'">';
  $list .= '<input type="hidden" name="parent_id" value="'.$id.'">';
  $list .= '<input type="hidden" name="action">';
  $list .= '<td>';

  $list .= '<table cellpadding="0" cellspacing="0" border="0"><tbody><tr><td class="formHeader" vAlign="top" align="left" height="20"> <img src="' .$image_path. '/left_arc.gif" border="0"></td><td class="formHeader" vAlign="middle" background="' . $image_path. '/header_tile.gif" align="left" noWrap height="20">'.$mod_strings['LBL_POTENTIAL_TITLE'].'</td><td  class="formHeader" vAlign="top" align="right" height="20"><img src="' .$image_path. '/right_arc.gif" border="0"></td> </tr></tbody></table></td>';
  $list .= '<td>&nbsp;</td>';
  $list .= '<td>&nbsp;</td>';
  $list .= '<td valign="bottom" align="right"><input title="New Potential" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Potentials\';this.form.return_action.value=\'DetailView\';this.form.return_module.value=\'Products\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_POTENTIAL'].'">&nbsp;</td>';

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
    $list .= '<a href="index.php?module=Potentials&action=EditView&return_module=Products&return_action=DetailView&record='.$row["potentialid"].'&return_id='.$id.'">'.$app_strings['LNK_EDIT'].'</a>  |  <a href="index.php?module=Potentials&action=Delete&return_module=Products&return_action=DetailView&record='.$row["potentialid"].'&return_id='.$id.'">'.$app_strings['LNK_DELETE'].'</a>';

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="30%"><a href="index.php?module=Potentials&action=DetailView&return_module=Products&return_action=DetailView&record='.$row["potentialid"] .'&return_id='.$id.'">'.$row['potentialname'].'</td>';
    
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
    $list .= $row['unit_price'];
    $list .= '</td>';

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="15%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= $row['expiry_date'];

    $list .= '</td>';

    $list .= '</tr>';
    $i++;
  }


  $list .= '</table>';
  echo $list;

  echo "<BR>\n";
}

function renderRelatedActivities($query)
{
  
  global $theme;
  $theme_path="themes/".$theme."/";
  $image_path=$theme_path."images/";
  require_once ($theme_path."layout_utils.php");
  
  
  global $adb;
  global $mod_strings;
  global $app_strings;

  global $adb;
  $id = $_REQUEST['record'];

  $result=$adb->query($query);   
  
  $list .= '<br><br>';
  $list .= '<table width="100%" cellpadding="0" cellspacing="0" border="0"><tbody><tr>';
  $list .= '<form border="0" action="index.php" method="post" name="form" id="form">';
  $list .= '<input type="hidden" name="module">';
  $list .= '<input type="hidden" name="mode">';
  $list .= '<input type="hidden" name="activity_mode">';
  $list .= '<input type="hidden" name="return_module" value="Products">';
  $list .= '<input type="hidden" name="return_action" value="DetailView">';
  $list .= '<input type="hidden" name="return_id" value="'.$id.'">';
  $list .= '<input type="hidden" name="parent_id" value="'.$id.'">';
  $list .= '<input type="hidden" name="action">';
  $list .= '<td>';
  $list .= '<table cellpadding="0" cellspacing="0" border="0"><tbody><tr><td class="formHeader" vAlign="top" align="left" height="20"> <img src="' .$image_path. '/left_arc.gif" border="0"></td><td class="formHeader" vAlign="middle" background="' . $image_path. '/header_tile.gif" align="left" noWrap height="20">'.$app_strings['LBL_OPEN_ACTIVITIES'].'</td><td  class="formHeader" vAlign="top" align="right" height="20"><img src="' .$image_path. '/right_arc.gif" border="0"></td> </tr></tbody></table></td>';
  $list .= '<td>&nbsp;</td>';
  $list .= '<td>&nbsp;</td>';
  $list .= '<td valign="bottom" align="right"><input title="New Task" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Activities\';this.form.activity_mode.value=\'Task\';this.form.return_module.value=\'Products\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_TASK'].'">&nbsp;';
  $list .= '<input title="New Event" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';;this.form.activity_mode.value=\'Events\';this.form.module.value=\'Activities\';this.form.return_module.value=\'Products\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_EVENT'].'">&nbsp;</td>';
//  $list .= '<td width="50%"></td>';

  $list .= '</td></tr></form></tbody></table>';

  $list .= '<table border="0" cellpadding="0" cellspacing="0" class="FormBorder" width="100%">';
  $list .= '<tr class="ModuleListTitle" height=20>';

  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle" height="21">';

  $list .= $app_strings['LBL_ACTION'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle">';

  $list .= $app_strings['LBL_SUBJECT'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle">';

  $list .= $app_strings['LBL_ENTITY_TYPE'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle">';

  $list .= $app_strings['LBL_DUE_DATE'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle">';

  $list .= $app_strings['LBL_STATUS'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle">';

  $list .= $app_strings['LBL_LIST_ASSIGNED_USER'].'</td>';
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
	$mode='Task';
	if($row['activitytype'] == 'Call' || $row['activitytype'] == 'Meeting')
		$mode='Events';

    $list .= '<tr class="'. $trowclass.'">';

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="10%" height="21" style="padding:0px 3px 0px 3px;">';   
    $list .= '<a href="index.php?module=Activities&action=EditView&return_module=Products&return_action=DetailView&activity_mode='.$mode.'&record='.$row["activityid"].'&return_id='.$id.'">'.$app_strings['LNK_EDIT'].'</a>  |  <a href="index.php?module=Activities&action=Delete&return_module=Products&return_action=DetailView&record='.$row["activityid"].'&return_id='.$id.'">'.$app_strings['LNK_DELETE'].'</a>';
    $list .= '</td> ';

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="30%"><a href="index.php?module=Activities&action=DetailView&return_module=Products&return_action=DetailView&activity_mode='.$mode.'&record='.$row["activityid"] .'&return_id='.$id.'">'.$row['subject'].'</td>';

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="10%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= $row['activitytype'];
    $list .= '</td>';

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="10%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= $row['date_start'];
    $list .= '</td>';

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="15%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= $row['status'];
    $list .= '</td>';

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="10%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= $row['user_name'];
    $list .= '</td>';

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
  $list .= '<input type="hidden" name="return_module" value="Products">';
  $list .= '<input type="hidden" name="return_action" value="DetailView">';
  $list .= '<input type="hidden" name="return_id" value="'.$id.'">';
  $list .= '<input type="hidden" name="parent_id" value="'.$id.'">';
  $list .= '<input type="hidden" name="action">';
  $list .= '<td>';
 $list .= '<table cellpadding="0" cellspacing="0" border="0"><tbody><tr><td class="formHeader" vAlign="top" align="left" height="20"> <img src="' .$image_path. '/left_arc.gif" border="0"></td><td class="formHeader" vAlign="middle" background="' . $image_path. '/header_tile.gif" align="left" noWrap height="20">'.$app_strings['LBL_ATTACHMENT_AND_NOTES'].'</td><td  class="formHeader" vAlign="top" align="right" height="20"><img src="' .$image_path. '/right_arc.gif" border="0"></td> </tr></tbody></table></td>';
  $list .= '<td>&nbsp;</td>';
  $list .= '<td>&nbsp;</td>';
  $list .= '<td valign="bottom" align="right"><input title="New Attachment" accessyKey="F" class="button" onclick="this.form.action.value=\'upload\';this.form.module.value=\'uploads\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_ATTACHMENT'].'">';
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

  $list .= '<tr><td COLSPAN="12" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td></tr>';

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
    $list .= '<a href="index.php?module='.$module.'&action='.$editaction.'&return_module=Products&return_action=DetailView&record='.$row["noteattachmentid"].'&filename='.$row[2].'&return_id='.$_REQUEST['record'].'">'.$app_strings['LNK_EDIT'].'</a>  |  ';
    $list .= '<a href="index.php?module='.$module.'&action='.$deleteaction.'&return_module=Products&return_action=DetailView&record='.$row["noteattachmentid"].'&filename='.$row[2].'&return_id='.$_REQUEST['record'].'">'.$app_strings['LNK_DELETE'].'</a>';

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="30%"><a href="index.php?module='.$module.'&action=DetailView&return_module=Products&return_action=DetailView&record='.$row["noteattachmentid"] .'&return_id='.$_REQUEST['record'].'">'.$row[0].'</td>';

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="15%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= $row[1];
    $list .= '</td>';

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="15%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= '<a href = "index.php?module=uploads&action=downloadfile&return_module=Products&activity_type='.$row[1].'&fileid='.$row[5].'&filename='.$row[2].'">'.$row[2].'</a>';
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

function renderRelatedTickets($query)
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
  $list .= '<input type="hidden" name="return_module" value="Products">';
  $list .= '<input type="hidden" name="return_action" value="DetailView">';
  $list .= '<input type="hidden" name="return_id" value="'.$id.'">';
  $list .= '<input type="hidden" name="parent_id" value="'.$id.'">';
  $list .= '<input type="hidden" name="action">';
  $list .= '<td>';

  $list .= '<table cellpadding="0" cellspacing="0" border="0"><tbody><tr><td class="formHeader" vAlign="top" align="left" height="20"> <img src="' .$image_path. '/left_arc.gif" border="0"></td><td class="formHeader" vAlign="middle" background="' . $image_path. '/header_tile.gif" align="left" noWrap height="20">'.$mod_strings['LBL_TICKET_TITLE'].'</td><td  class="formHeader" vAlign="top" align="right" height="20"><img src="' .$image_path. '/right_arc.gif" border="0"></td> </tr></tbody></table></td>';
  $list .= '<td>&nbsp;</td>';
  $list .= '<td>&nbsp;</td>';
  $list .= '<td valign="bottom" align="right"><input title="New TICKET" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'HelpDesk\';this.form.return_action.value=\'DetailView\';this.form.return_module.value=\'Products\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_TICKET'].'">&nbsp;</td>';

  $list .= '</td></tr></form></tbody></table>';

  $list .= '<table border="0" cellpadding="0" cellspacing="0" class="FormBorder" width="100%">';
  $list .= '<tr class="ModuleListTitle" height=20>';

  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle" height="21">';

  $list .= $app_strings['LBL_ACTION'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle">';

  $list .= $app_strings['LBL_TICKET_ID'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle">';

  $list .= $app_strings['LBL_SUBJECT'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle">';

  $list .= $app_strings['LBL_PRODUCT'].'</td>';
  $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
  $list .= '<td class="moduleListTitle">';

  $list .= $app_strings['LBL_LIST_ASSIGNED_USER'].'</td>';
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
    $list .= '<a href="index.php?module=HelpDesk&action=EditView&return_module=Products&return_action=DetailView&record='.$row["ticketid"].'&return_id='.$id.'">'.$app_strings['LNK_EDIT'].'</a>  |  <a href="index.php?module=HelpDesk&action=Delete&return_module=Products&return_action=DetailView&record='.$row["ticketid"].'&return_id='.$id.'">'.$app_strings['LNK_DELETE'].'</a>';

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="10%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= $row['ticketid'];
    $list .= '</td>';

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="25%"><a href="index.php?module=HelpDesk&action=DetailView&return_module=Products&return_action=DetailView&record='.$row["ticketid"] .'&return_id='.$id.'">'.$row['title'].'</td>';
    
    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="15%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= $row['productname'];
    $list .= '</td>';

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="10%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= $row['user_name'];
    $list .= '</td>';

    $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
    $list .= '<td width="15%" height="21" style="padding:0px 3px 0px 3px;">';
    $list .= $row['status'];
    $list .= '</td>';

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


echo get_form_footer();


?>
