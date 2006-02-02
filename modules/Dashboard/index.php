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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Dashboard/index.php,v 1.2 2004/10/06 09:02:05 jack Exp $
 * Description:  Main file for the Home module.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $app_strings;
global $app_list_strings;
global $mod_strings;

global $theme;
global $currentModule;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');
require_once('include/logging.php');

$log = LoggerManager::getLogger('dashboard');


	echo <<< END
		<table width=100% align="left" cellpadding="5" cellspacing="5" border="0">
		<tr>
		<td valign="top">
END;
 	echo get_left_form_header($mod_strings['LBL_SALES_STAGE_FORM_TITLE']);
	include ("modules/Dashboard/Chart_pipeline_by_sales_stage.php"); 
	echo get_left_form_footer(); 
 
	echo <<< END
		</td>
		<td valign="top">
END;
        echo get_left_form_header($mod_strings['LBL_MONTH_BY_OUTCOME']);
	include ("modules/Dashboard/Chart_outcome_by_month.php"); 
	echo get_left_form_footer(); 

	echo <<< END
	</td>
	</tr><tr>
	<td valign="top">
END;
	echo get_left_form_header($mod_strings['LBL_LEAD_SOURCE_BY_OUTCOME']);
	include ("modules/Dashboard/Chart_lead_source_by_outcome.php"); 
	echo get_left_form_footer(); 
	
	echo <<< END
	</td>
	<td valign="top">
END;

	echo get_left_form_header($mod_strings['LBL_LEAD_SOURCE_FORM_TITLE']);
	include ("modules/Dashboard/Chart_pipeline_by_lead_source.php"); 
	echo get_left_form_footer(); 

//Added to get the graphs
	echo <<< END
	</td>
	</tr>
	<tr><td>
	<a href="index.php?module=Dashboard&action=display_charts&type=leadsource">Charts by LeadSource</a>  

	</td></tr>
	<tr><td>
	<a href="index.php?module=Dashboard&action=display_charts&type=leadstatus">Charts by LeadStatus</a>  
	</td></tr>
	<tr><td>
	<a href="index.php?module=Dashboard&action=display_charts&type=leadindustry">Charts for Leads by Industry</a>  
	</td></tr>
	 <tr><td>
        <a href="index.php?module=Dashboard&action=display_charts&type=salesbyleadsource">Sales by LeadSource</a>
        </td></tr>
	<tr><td>
	<a href="index.php?module=Dashboard&action=display_charts&type=salesbyaccount">Sales by Account</a>  
	</td></tr><tr><td>
	<a href="index.php?module=Dashboard&action=display_charts&type=accountindustry">Account  by Industry</a>  
	</td></tr><tr><td>
	<a href="index.php?module=Dashboard&action=display_charts&type=productcategory">Products by Category</a>  
	</td></tr><tr><td>
	<a href="index.php?module=Dashboard&action=display_charts&type=sobyaccounts">SalesOrder by Accounts</a>  
	</td></tr><tr><td>
	<a href="index.php?module=Dashboard&action=display_charts&type=sobystatus">SalesOrder by Status</a>  
	</td></tr><tr><td>
	<a href="index.php?module=Dashboard&action=display_charts&type=pobystatus">PurchaseOrder by Status</a>
	</td></tr><tr><td>
        <a href="index.php?module=Dashboard&action=display_charts&type=quotesbyaccounts">Quotes by Accounts</a>
        </td></tr><tr><td>
        <a href="index.php?module=Dashboard&action=display_charts&type=quotesbystage">Quotes by Stage</a>
	 </td></tr><tr><td>
        <a href="index.php?module=Dashboard&action=display_charts&type=invoicebyacnts">Invoices by Accounts</a>
	</td></tr><tr><td>
        <a href="index.php?module=Dashboard&action=display_charts&type=invoicebystatus">Invoices by Status</a>
	</td></tr><tr><td>
        <a href="index.php?module=Dashboard&action=display_charts&type=ticketsbystatus">Tickets by Status</a>
	</td></tr><tr><td>
        <a href="index.php?module=Dashboard&action=display_charts&type=ticketsbypriority">Tickets by Priority</a>

	</td></tr>
	</table>

END;
 
?>

