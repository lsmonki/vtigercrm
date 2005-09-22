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
require_once('XTemplate/xtpl.php');
require_once('include/uifromdbutil.php');
require_once('modules/HelpDesk/HelpDesk.php');
require_once('modules/HelpDesk/Forms.php');
require_once('include/FormValidationUtil.php');
global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;

$focus = new HelpDesk();

if(isset($_REQUEST['record'])) 
{
    $focus->id = $_REQUEST['record'];
    $focus->mode = 'edit'; 	
    $focus->retrieve_entity_info($_REQUEST['record'],"HelpDesk");
    $focus->name=$focus->column_fields['ticket_title'];		
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
    	$focus->mode = ''; 	
} 

//get Block 1 Information

$block_1 = getBlockInformation("HelpDesk",1,$focus->mode,$focus->column_fields);
$block_1_header = getBlockTableHeader("LBL_TICKET_INFORMATION");


//get Subject Information

$block_2 = getBlockInformation("HelpDesk",2,$focus->mode,$focus->column_fields);

//get Description Information

$block_3 = getBlockInformation("HelpDesk",3,$focus->mode,$focus->column_fields);
$block_3_header = getBlockTableHeader("LBL_DESCRIPTION_INFORMATION");

//get Custom Field Information
$block_5 = getBlockInformation("HelpDesk",5,$focus->mode,$focus->column_fields);
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
        $cust_fld .='<BR>';
}


global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$xtpl=new XTemplate ('modules/HelpDesk/EditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("CALENDAR_LANG", $app_strings['LBL_JSCALENDAR_LANG']);
$xtpl->assign("BLOCK1", $block_1);
$xtpl->assign("BLOCK2", $block_2);
$xtpl->assign("BLOCK3", $block_3);
$xtpl->assign("BLOCK1_HEADER", $block_1_header);
$xtpl->assign("BLOCK3_HEADER", $block_3_header);

if($focus->mode == 'edit')
{
	$block_4 = getBlockInformation("HelpDesk",4,$focus->mode,$focus->column_fields);
	$block_4_header = getBlockTableHeader("LBL_TICKET_RESOLUTION");

	$block_4_ui = '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formOuterBorder">
			   <tr><td>'.$block_4_header.'
				<table width="100%" border="0" cellspacing="1" cellpadding="2">'.$block_4.'
			   	</table>
			   </td></tr>
		       </table>
		      ';
	$xtpl->assign("BLOCK4_UI", $block_4_ui);
	//$xtpl->assign("BLOCK4", $block_4);
	//$xtpl->assign("BLOCK4_HEADER", $block_4_header);

	$block_7 = getCommentInformation($focus->id);
	if($block_7 != '')
	{
		$block_7_header = getBlockTableHeader("LBL_COMMENTS");
		$block_7_ui = '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formOuterBorder">
				   <tr><td>'.$block_7_header.'
					<table width="100%" border="0" cellspacing="1" cellpadding="2">'.$block_7.'</table>
				   </td></tr>
			       </table>
			      ';
		$xtpl->assign("BLOCK7_UI", $block_7_ui);
		//$xtpl->assign("BLOCK7", $block_7);
		//$xtpl->assign("BLOCK7_HEADER", $block_7_header);
	}

	$block_6 = getBlockInformation("HelpDesk",6,$focus->mode,$focus->column_fields);
        $block_6_header = getBlockTableHeader("LBL_COMMENTS");
	if($block_6 != '')
	{
		$block_6_ui = '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formOuterBorder">
				   <tr><td>
					<table width="100%" border="0" cellspacing="1" cellpadding="2">'.$block_6.'</table>
				   </td></tr>
			       </table>
			      ';
	        $xtpl->assign("BLOCK6_UI", $block_6_ui);
	        //$xtpl->assign("BLOCK6", $block_6);
	}
        $xtpl->assign("BLOCK6_HEADER", $block_6_header);
}

if (isset($focus->name)) $xtpl->assign("NAME", $focus->name);
else $xtpl->assign("NAME", "");

if(isset($cust_fld))
{
        $xtpl->assign("CUSTOMFIELD", $cust_fld);
}
$xtpl->assign("ID", $focus->id);
if($focus->mode == 'edit')
{
        $xtpl->assign("MODE", $focus->mode);
        $xtpl->assign("OLDSMOWNERID", $focus->column_fields['assigned_user_id']);
}

if(isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if(isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if(isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
if(isset($_REQUEST['product_id'])) $xtpl->assign("PRODUCTID", $_REQUEST['product_id']);

$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js());





 $ticket_tables = Array('troubletickets','crmentity');
 $tabid = getTabid("HelpDesk");
 $validationData = getDBValidationData($ticket_tables,$tabid);
 $fieldName = '';
 $fieldLabel = '';
 $fldDataType = '';

 $rows = count($validationData);
 foreach($validationData as $fldName => $fldLabel_array)
 {
   if($fieldName == '')
   {
     $fieldName="'".$fldName."'";
   }
   else
   {
     $fieldName .= ",'".$fldName ."'";
   }
   foreach($fldLabel_array as $fldLabel => $datatype)
   {
	if($fieldLabel == '')
	{
			
     		$fieldLabel = "'".$fldLabel ."'";
	}		
      else
       {
      $fieldLabel .= ",'".$fldLabel ."'";
        }
 	if($fldDataType == '')
         {
      		$fldDataType = "'".$datatype ."'";
    	}
	 else
        {
       		$fldDataType .= ",'".$datatype ."'";
     	}
   }
 }

$xtpl->assign("VALIDATION_DATA_FIELDNAME",$fieldName);
$xtpl->assign("VALIDATION_DATA_FIELDDATATYPE",$fldDataType);
$xtpl->assign("VALIDATION_DATA_FIELDLABEL",$fieldLabel);













$xtpl->parse("main");

$xtpl->out("main");

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
                	$list .= '<div valign="top" width="70%" class="dataField">';
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

?>
