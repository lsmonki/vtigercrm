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
 * $Header:  vtiger_crm/modules/Opportunities/DetailView.php,v 1.1 2004/08/17 15:06:09 gjk Exp $
 * Description:  TODO: To be written.
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Opportunities/Opportunity.php');
require_once('modules/Opportunities/Forms.php');

global $mod_strings;
global $app_strings;
global $app_list_strings;

$focus = new Opportunity();

if(isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
} 

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("Opportunity detail view");

$xtpl=new XTemplate ('modules/Opportunities/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id());
$xtpl->assign("ID", $focus->id);
$xtpl->assign("ACCOUNT_NAME", $focus->account_name);	
$xtpl->assign("ACCOUNT_ID", $focus->account_id);	
$xtpl->assign("ASSIGNED_TO", $focus->assigned_user_name);
$xtpl->assign("LEAD_SOURCE", $app_list_strings['lead_source_dom'][$focus->lead_source]);
$xtpl->assign("NAME", $focus->name);
$xtpl->assign("TYPE", $app_list_strings['opportunity_type_dom'][$focus->opportunity_type]);
if ($focus->amount != '') $xtpl->assign("AMOUNT", $app_strings['LBL_CURRENCY_SYMBOL'].$focus->amount);
$xtpl->assign("DATE_ENTERED", $focus->date_entered);
$xtpl->assign("DATE_CLOSED", $focus->date_closed);
$xtpl->assign("NEXT_STEP", $focus->next_step);
$xtpl->assign("SALES_STAGE", $app_list_strings['sales_stage_dom'][$focus->sales_stage]);
$xtpl->assign("PROBABILITY", $focus->probability);
$xtpl->assign("DESCRIPTION", $focus->description);
$xtpl->parse("main");
$xtpl->out("main");

echo "<BR>\n";

// Now get the list of contacts that match this one.
$focus_list = & $focus->get_contacts();

include('modules/Contacts/SubPanelViewOpportunity.php');

echo "<BR>\n";

// Now get the list of activities that match this opportunity.
$focus_tasks_list = & $focus->get_tasks();
$focus_meetings_list = & $focus->get_meetings();
$focus_calls_list = & $focus->get_calls();
$focus_emails_list = & $focus->get_emails();
$focus_notes_list = & $focus->get_notes();

include('modules/Activities/SubPanelView.php');

echo "</td></tr>\n";

?>