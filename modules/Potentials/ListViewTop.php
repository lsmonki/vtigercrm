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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Opportunities/ListViewTop.php,v 1.3 2004/10/29 09:55:09 jack Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once("data/Tracker.php");
require_once('modules/Opportunities/Opportunity.php');
require_once('include/logging.php');
require_once('include/ListView/ListView.php');
$current_module_strings = return_module_language($current_language, "Opportunities");
$log = LoggerManager::getLogger('top opportunity_list');
$seedOpportunity = new Opportunity();
	
//build top 5 opportunity list
$where = "opportunities.sales_stage <> 'Closed Won' AND opportunities.sales_stage <> 'Closed Lost' AND opportunities.assigned_user_id='".$current_user->id."'";


$ListView = new ListView();
$ListView->initNewXTemplate( 'modules/Opportunities/ListViewTop.html',$current_module_strings);
$ListView->setHeaderTitle($current_module_strings['LBL_TOP_OPPORTUNITIES'] );
$ListView->setQuery($where, 5, "amount * 1 DESC", "OPPORTUNITY", false);
$ListView->processListView($seedOpportunity, "main", "OPPORTUNITY");

?>
