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
require_once('include/database/PearDatabase.php');
//require_once('HelpDeskUtil.php');
require_once('XTemplate/xtpl.php');
require_once('include/utils.php');
require_once('modules/HelpDesk/HelpDesk.php');
require_once('include/uifromdbutil.php');

$focus = new HelpDesk();

if(isset($_REQUEST['record']) && isset($_REQUEST['record'])) {
    $focus->retrieve_entity_info($_REQUEST['record'],"HelpDesk");
    $focus->name=$focus->column_fields['ticket_title'];
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
        $focus->id = "";
}

//Added code for Error display in sending mail to assigned to user when ticket is created or updated.
if($_REQUEST['message'] != '' && $_REQUEST['message'] != 1 && $focus->column_fields['assigned_user_id'] != 0)
{
        $error_message = '<h4><b><font color=red>Mail could not be sent to "Assigned To" user. ';
        if(@strstr($_REQUEST['message'],'Language string failed to load: connect_host'))
                $error_message .= 'Please check the mail server.';
        elseif(@strstr($_REQUEST['message'],'Language string failed to load: recipients_failed'))
                $error_message .= 'Please check the "Assigned to" email id.';

        $error_message .= '</font></b></h4>';
        echo $error_message;
}

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$xtpl=new XTemplate ('modules/HelpDesk/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

if (isset($focus->name)) $xtpl->assign("NAME", $focus->name);
else $xtpl->assign("NAME", "");
$xtpl->assign("TICKETID", $_REQUEST['record']);

$block_1 = getDetailBlockInformation("HelpDesk",1,$focus->column_fields);
$xtpl->assign("BLOCK1", $block_1);
$block_2 = getDetailBlockInformation("HelpDesk",2,$focus->column_fields);
$xtpl->assign("BLOCK2", $block_2);
$block_3 = getDetailBlockInformation("HelpDesk",3,$focus->column_fields);
$xtpl->assign("BLOCK3", $block_3);

$block_1_header = getBlockTableHeader("LBL_TICKET_INFORMATION");
$block_3_header = getBlockTableHeader("LBL_DESCRIPTION_INFORMATION");
$xtpl->assign("BLOCK1_HEADER", $block_1_header);
$xtpl->assign("BLOCK3_HEADER", $block_3_header);

$block_4 = getDetailBlockInformation("HelpDesk",4,$focus->column_fields);
$block_4_header = getBlockTableHeader("LBL_TICKET_RESOLUTION");
$xtpl->assign("BLOCK4", $block_4);
$xtpl->assign("BLOCK4_HEADER", $block_4_header);

$block_6 = getCommentInformation($_REQUEST['record']);
if($block_6 != '')
{
	$block_6_header = getBlockTableHeader("LBL_COMMENTS");
	$xtpl->assign("BLOCK6", $block_6);
	$xtpl->assign("BLOCK6_HEADER", $block_6_header);
}

$block_5 = getDetailBlockInformation("HelpDesk",5,$focus->column_fields);
if(trim($block_5) != '')
{
        $cust_fld = '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formOuterBorder">';
        $cust_fld .=  '<tr><td>';
	$block_5_header = getBlockTableHeader("LBL_CUSTOM_INFORMATION");
        $cust_fld .= $block_5_header;
        $cust_fld .= '<table width="100%" border="0" cellspacing="1" cellpadding="0">';
        $cust_fld .= $block_5;
        $cust_fld .= '</table>';
        $cust_fld .= '</td></tr></table>';
        $cust_fld .= '<BR>';

}

$xtpl->assign("CUSTOMFIELD", $cust_fld);

$permissionData = $_SESSION['action_permission_set'];
if(isPermitted("HelpDesk",1,$_REQUEST['record']) == 'yes')
{
	$xtpl->assign("EDITBUTTON","<td><input title=\"$app_strings[LBL_EDIT_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_EDIT_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='HelpDesk'; this.form.return_action.value='DetailView'; this.form.return_id.value='".$_REQUEST['record']."'; this.form.action.value='EditView'\" type=\"submit\" name=\"Edit\" value=\"$app_strings[LBL_EDIT_BUTTON_LABEL]\"></td>");


	$xtpl->assign("DUPLICATEBUTTON","<td><input title=\"$app_strings[LBL_DUPLICATE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_DUPLICATE_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='HelpDesk'; this.form.return_action.value='DetailView'; this.form.isDuplicate.value='true'; this.form.action.value='EditView'\" type=\"submit\" name=\"Duplicate\" value=\"$app_strings[LBL_DUPLICATE_BUTTON_LABEL]\"></td>");
}


if(isPermitted("HelpDesk",2,$_REQUEST['record']) == 'yes')
{
	$xtpl->assign("DELETEBUTTON","<td><input title=\"$app_strings[LBL_DELETE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_DELETE_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='HelpDesk'; this.form.return_action.value='ListView'; this.form.action.value='Delete'; return confirm('$app_strings[NTC_DELETE_CONFIRMATION]')\" type=\"submit\" name=\"Delete\" value=\"$app_strings[LBL_DELETE_BUTTON_LABEL]\"></td>");
}



$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$xtpl->assign("ID", $_REQUEST['record']);

if(isPermitted("HelpDesk",8,'') == 'yes')
{
$xtpl->assign("MERGEBUTTON","<input title=\"$app_strings[LBL_MERGE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_MERGE_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.action.value='Merge';\" type=\"submit\" name=\"Merge\" value=\" $app_strings[LBL_MERGE_BUTTON_LABEL]\"></td>");

        require_once('modules/Users/UserInfoUtil.php');
        $wordTemplateResult = fetchWordTemplateList("HelpDesk");
        $tempCount = $adb->num_rows($wordTemplateResult);
        $tempVal = $adb->fetch_array($wordTemplateResult);
        for($templateCount=0;$templateCount<$tempCount;$templateCount++)
        {
                $optionString .="<option value=\"".$tempVal["templateid"]."\">" .$tempVal["filename"] ."</option>";
                $tempVal = $adb->fetch_array($wordTemplateResult);
        }
$xtpl->assign("WORDTEMPLATEOPTIONS","<td align=right>&nbsp;&nbsp;".$app_strings['LBL_SELECT_TEMPLATE_TO_MAIL_MERGE']."<select name=\"mergefile\">".$optionString."</select>");
}

$xtpl->parse("main");
$xtpl->out("main");


//Security check for related list
global $profile_id;
$tab_per_Data = getAllTabsPermission($profile_id);
$permissionData = $_SESSION['action_permission_set'];
$focus->id = $_REQUEST['record'];
getRelatedLists("HelpDesk",$focus);
Get_Ticket_History();

function getCommentInformation($ticketid)
{
	global $adb;
	global $mod_strings;
	$sql = "select * from ticketcomments where ticketid=".$ticketid;
	$result = $adb->query($sql);
	$noofrows = $adb->num_rows($result);
	if($noofrows == 0)
		return '';
	$list .= '<div style="overflow: scroll;height:200;width:100%;">';
        for($i=0;$i<$noofrows;$i++)
        {
		if($adb->query_result($result,$i,'comments') != '')
		{
                        $list .= '<div valign="top" width="70%"class="dataField">';
                        $list .= nl2br($adb->query_result($result,$i,'comments'));

			$list .= '</div><div valign="top" width="20%" class="dataLabel"><font color=darkred>';
        	        $list .= $mod_strings['LBL_AUTHOR'].' : ';
			if($adb->query_result($result,$i,'ownertype') == 'user')
				$list .= getUserName($adb->query_result($result,$i,'ownerid'));
			else
				$list .= getCustomerName($ticketid);

			$list .= ' on '.$adb->query_result($result,$i,'createdtime').' &nbsp;';

			$list .= '</font></div>';
		}
	}
	$list .= '</div>';
	return $list;
}
function getCustomerName($id)
{
	global $adb;
	$sql = "select * from PortalInfo inner join troubletickets on troubletickets.parent_id = PortalInfo.id where troubletickets.ticketid=".$id;
	$result = $adb->query($sql);
	$customername = $adb->query_result($result,0,'user_name');
	return $customername;
}


//Constructing the Related Lists from here
/*
if($tab_per_Data[2] == 0)
{
        if($permissionData[2][3] == 0)
        {
		$focus->get_opportunities($_REQUEST['record']);
	}
}


$focus->get_activities($_REQUEST['record']);
if($tab_per_Data[8] == 0)
{
        if($permissionData[8][3] == 0)
        {
		$focus->get_attachments($_REQUEST['record']);
	}
}


require_once('modules/Products/binaryfilelist.php');
echo '<br><br>';
echo '<table width="50%" cellpadding="0" cellspacing="0" border="0"><tbody><tr>';
echo '<form border="0" action="index.php" method="post" name="form" id="form">';

echo '<input type="hidden" name="module">';
echo '<input type="hidden" name="mode">';
echo '<input type="hidden" name="return_module" value="'.$currentModule.'">';
echo '<input type="hidden" name="return_id" value="'.$productid.'">';
echo '<input type="hidden" name="action">';


echo '<td>';
echo '<table cellpadding="0" cellspacing="0" border="0"><tbody><tr>
                <td class="formHeader" vAlign="top" align="left" height="20">
         <img src="' .$image_path. '/left_arc.gif" border="0"></td>
   <td class="formHeader" vAlign="middle" background="' . $image_path. '/header_tile.gif" align="left" noWrap width="100%" height="20">'.$mod_strings['LBL_ATTACHMENTS'].'</td>
        <td  class="formHeader" vAlign="top" align="right" height="20">
                  <img src="' .$image_path. '/right_arc.gif" border="0"></td>
                </tr></tbody></table>
      </td>';
echo '<td>&nbsp;</td>';
echo '<td>&nbsp;</td>';
echo '<td valign="bottom"><input title="Attach File" accessyKey="F" class="button" onclick="this.form.action.value=\'upload\';this.form.module.value=\'Products\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_ATTACHMENT'].'"></td>';
echo '<td width="50%"></td>';

echo '</td></tr></form></tbody></table>';
echo getAttachmentsList($productid, $theme);

echo "<BR>\n";
echo "<BR>\n";

require_once('include/RelatedTicketListUtil.php');
$list = getTicketList($productid, "Products", $image_path,$theme);
echo $list;
// Stick on the form footer
echo get_form_footer();
*/


?>
