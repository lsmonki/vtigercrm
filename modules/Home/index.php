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
 * $Header:  vtiger_crm/sugarcrm/modules/Home/index.php,v 1.5 2005/01/08 13:24:05 jack Exp $
 * Description:  Main file for the Home module.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;

$_REQUEST['search_form'] = 'false';
$_REQUEST['query'] = 'true';
$_REQUEST['status'] = 'In Progress--Not Started';
$_REQUEST['current_user_only'] = 'On';

$task_title = $mod_strings['LBL_OPEN_TASKS'];

?>
<table width=100% align="left" cellpadding="5" cellspacing="5" border="0">
<tr>
<td valign="top"><?php include("modules/Opportunities/ListViewTop.php"); ?></td>
<td rowspan="3" width="320" valign="top">
<?php echo get_left_form_header($mod_strings['LBL_PIPELINE_FORM_TITLE']);
	include ("modules/Dashboard/Chart_my_pipeline_by_sales_stage.php"); 
	echo get_left_form_footer(); ?>
</td>
</tr><tr>		
<td valign="top"><?php include("modules/Activities/OpenListView.php") ;?></td>
</tr><tr>		
<td valign="top"><?php include("modules/Tasks/ListView.php") ;?></td>
</tr>
</table>
<?
 //include($phpbb_root_path .'login.php');
?>
