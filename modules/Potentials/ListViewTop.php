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
 * $Header:  vtiger_crm/modules/Opportunities/ListViewTop.php,v 1.1 2004/08/17 15:06:09 gjk Exp $
 * Description:  TODO: To be written.
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('modules/Opportunities/Opportunity.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');

global $theme;
global $image_path;
global $currentModule;
global $opp_list;
//we don't want the parent module's string file, but rather the string file specifc to this subpanel
global $app_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Opportunities');

$list_form=new XTemplate ('modules/Opportunities/ListViewTop.html');
$list_form->assign("MOD", $current_module_strings);
$list_form->assign("APP", $app_strings);

$list_form->assign("THEME", $theme);
$list_form->assign("IMAGE_PATH", $image_path);
$list_form->assign("MODULE_NAME", $currentModule);
$list_form->assign("MOD", $current_module_strings);
$list_form->assign("APP", $app_strings);
	
//build top 5 opportunity list
$oddRow = true;
$count = 1;
for($row = 0; $count <= 5 && isset($opp_list[$row]); $row++)
{
	if ($opp_list[$row]->sales_stage != 'Closed Won' && $opp_list[$row]->sales_stage != 'Closed Lost') {
		$count++;
		$opportunity_fields = array(
			'ID' => $opp_list[$row]->id,
			'NAME' => $opp_list[$row]->name,
			'ACCOUNT_NAME' => $opp_list[$row]->account_name,
			'ACCOUNT_ID' => $opp_list[$row]->account_id,
			'DATE_CLOSED' => $opp_list[$row]->date_closed,
			'AMOUNT' => $opp_list[$row]->amount
		);
		
		$list_form->assign("OPPORTUNITY", $opportunity_fields);
	
		
		if($oddRow)
		{
			//todo move to themes
			$list_form->assign("ROW_COLOR", 'oddListRow');
		}
		else
		{
			//todo move to themes
			$list_form->assign("ROW_COLOR", 'evenListRow');
		}
		$oddRow = !$oddRow;
		
		// Put the rows in.
		$list_form->parse("main.row");
	}
}
$list_form->parse("main");

echo get_form_header($current_module_strings['LBL_TOP_OPPORTUNITIES'], "", false);
$list_form->out("main");
echo get_form_footer();

echo "</td></tr>\n</table>\n";

?>
