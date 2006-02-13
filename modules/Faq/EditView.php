<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the 
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header: /cvsroot/vtigercrm/vtiger_crm/modules/Faq/EditView.php,v 1.5.2.2 2005/09/08 15:10:21 mickie Exp $
 * Description:  TODO To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
#require_once('data/Tracker.php'); // Commented for Tracker issue
require_once('modules/Faq/Faq.php');
require_once('include/CustomFieldUtil.php');
require_once('include/ComboUtil.php');
require_once('include/uifromdbutil.php');
require_once('include/FormValidationUtil.php');

global $app_strings;
global $mod_strings;
global $current_user;

$focus = new Faq();

if(isset($_REQUEST['record'])) 
{
    $focus->id = $_REQUEST['record'];
    $focus->mode = 'edit'; 	
    $focus->retrieve_entity_info($_REQUEST['record'],"Faq");		
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
    	$focus->mode = ''; 	
} 

//get Block 1 Information

$block_1 = getBlockInformation("Faq",1,$focus->mode,$focus->column_fields);



//get Address Information

$block_2 = getBlockInformation("Faq",2,$focus->mode,$focus->column_fields);

//get Description Information

$block_3 = getBlockInformation("Faq",3,$focus->mode,$focus->column_fields);

//get Custom Field Information
if($focus->mode == 'edit')
{
	$focus->column_fields['comments'] = '';
	$block_4 = getBlockInformation("Faq",4,$focus->mode,$focus->column_fields);
	$comments = $focus->getFAQComments($focus->id);
	$block_4_header = getBlockTableHeader("LBL_COMMENT_INFORMATION");
}

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";


require_once($theme_path.'layout_utils.php');

$xtpl=new XTemplate ('modules/Faq/EditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("BLOCK1", $block_1);
$xtpl->assign("BLOCK2", $block_2);
$xtpl->assign("BLOCK3", $block_3);
if($block_4 != '')
{
	$block_4_ui = '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formOuterBorder">
			   <tr><td>
				<table width="100%" border="0" cellspacing="1" cellpadding="2">'.$block_4.'
				</table>
			   </td></tr>
			</table>
		      ';
	$xtpl->assign("BLOCK4", $block_4_ui);
}
if($comments != '')
{
	$block_4_comments = '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formOuterBorder">
				<tr><td>';
	$block_4_comments .= 		$block_4_header;
	$block_4_comments .= 			'<table width="100%" border="0" cellspacing="1" cellpadding="2">';
	$block_4_comments .= 				$comments;
	$block_4_comments .= 			'</table>
				</td></tr>
			     </table>
			    ';
	//$xtpl->assign("BLOCK4_COMMENTS", $comments);
	//$xtpl->assign("BLOCK4_HEADER", $block_4_header);
	$xtpl->assign("BLOCK4_COMMENTS_BLOCK", $block_4_comments);
}

if($focus->mode == 'edit')
{
	$xtpl->assign("MODE", $focus->mode);
}		

if(isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
else $xtpl->assign("RETURN_MODULE","Faq");
if(isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
else $xtpl->assign("RETURN_ACTION","index");
if(isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$xtpl->assign("ID", $focus->id);

$xtpl->assign("CALENDAR_LANG", $app_strings['LBL_JSCALENDAR_LANG']);
$xtpl->assign("CALENDAR_DATEFORMAT", parse_calendardate($app_strings['NTC_DATE_FORMAT']));


$faq_tables = Array('faq'); 

 $validationData = getDBValidationData($faq_tables);
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

?>
