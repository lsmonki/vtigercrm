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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Potentials/ListViewTop.php,v 1.18 2005/04/20 20:24:30 ray Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once("data/Tracker.php");
require_once('modules/Potentials/Opportunity.php');
require_once('include/logging.php');
require_once('include/ListView/ListView.php');

global $app_strings;
$current_module_strings = return_module_language($current_language, "Potentials");
$log = LoggerManager::getLogger('top opportunity_list');


$where = "AND potential.sales_stage <> 'Closed Won' AND potential.sales_stage <> 'Closed Lost' AND crmentity.smownerid='".$current_user->id."' ORDER BY amount DESC";

$list_query = getListQuery("Potentials",$where);
$list_result = $adb->limitQuery($list_query,0,5);
//$list_result = $adb->query($list_query);
$open_potentials_list = array();
$noofrows = $adb->num_rows($list_result);

if (count($list_result)>0)
for($i=0;$i<$noofrows;$i++) 
{
  //$parent_name=getRelatedTo("Activities",$list_result,$i);
  $open_potentials_list[] = Array('name' => $adb->query_result($list_result,$i,'potentialname'),
                                     'id' => $adb->query_result($list_result,$i,'potentialid'),
                                     'accountid' => $adb->query_result($list_result,$i,'accountid'),
                                     'accountname' => $adb->query_result($list_result,$i,'accountname'),
                                     'amount' => $adb->query_result($list_result,$i,'amount'),
                                     'closingdate' => getDisplayDate($adb->query_result($list_result,$i,'closingdate')),
                                     );
}

$xtpl=new XTemplate ('modules/Potentials/ListViewTop.html');
$xtpl->assign("MOD", $current_module_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("CURRENCY_SYMBOL", getCurrencySymbol());

// Stick the form header out there.
//echo get_form_header($current_module_strings['LBL_TOP_OPPORTUNITIES'], '', false);

$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("RETURN_URL", "&return_module=$currentModule&return_action=DetailView&return_id=" . ((is_object($focus)) ? $focus->id : ""));

$oddRow = true;
foreach($open_potentials_list as $potential)
{
	$potential_fields = array(
		'ID' => $potential['id'],
		'NAME' => $potential['name'],
		'ACCOUNT_ID' => $potential['accountid'],
		'ACCOUNT_NAME' => $potential['accountname'],
		'AMOUNT' => $potential['amount'],
		'DATE_CLOSED' => $potential['closingdate'],
	);

	$xtpl->assign("OPPORTUNITY", $potential_fields);

	if($oddRow)
    {
        //todo move to themes
		$xtpl->assign("ROW_COLOR", 'oddListRow');
    }
    else
    {
        //todo move to themes
		$xtpl->assign("ROW_COLOR", 'evenListRow');
    }
        $oddRow = !$oddRow;
        
	$xtpl->parse("main.row");
        // Put the rows in.
}

$xtpl->parse("main");
if (count($open_potentials_list)>0) $xtpl->out("main");
else echo "<em>".$current_module_strings['NTC_NONE_SCHEDULED']."</em>";
echo "<BR>";
// Stick on the form footer
echo get_form_footer();

/*$seedpotential = new Potential();
	
//build top 5 opportunity list
$where = "potential.sales_stage <> 'Closed Won' AND potential.sales_stage <> 'Closed Lost' AND crmentity.smcreatorid='".$current_user->id."'";


$ListView = new ListView();
$ListView->initNewXTemplate( 'modules/Potentials/ListViewTop.html',$current_module_strings);
$ListView->setHeaderTitle($current_module_strings['LBL_TOP_OPPORTUNITIES'] );
$ListView->setQuery($where, 5, "amount * 1 DESC", "OPPORTUNITY", false);
$ListView->processListView($seedpotential, "main", "OPPORTUNITY");
*/
?>
