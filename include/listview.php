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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/include/listview.php,v 1.11 2004/12/08 12:09:36 jack Exp $
 * Description:  Includes generic helper functions used throughout the application.
 ********************************************************************************/
require_once('include/logging.php');

function listView($display_title, $html_varName, $xtemplate , $seed, $orderby){ 
	global $theme,$image_path, $currentModule, $list_max_entries_per_page, $where, $mod_strings, $app_strings;
	$log = LoggerManager::getLogger('listView_'.$html_varName);
	$list_form=new XTemplate ($xtemplate);
        $list_form->assign("ID",$mod_strings);
	$list_form->assign("MOD", $mod_strings);
	$list_form->assign("APP", $app_strings);
	$list_form->assign("THEME", $theme);
	$list_form->assign("IMAGE_PATH", $image_path);
	$list_form->assign("MODULE_NAME", $currentModule);
	
	$current_offset = 0;
	if(isset($_REQUEST['current_offset']))
	    $current_offset = $_REQUEST['current_offset'];

	$response;
	if($currentModule == "Leads")
	{
		$response = $seed->get_lead_list($orderby, $where, $current_offset);
	}
	elseif($currentModule == "MessageBoard")
        {
		if(isset($_REQUEST['query']))
		{
			$_REQUEST['query']= 'query';
		}
                $response = $seed->get_msgboard_data($orderby, $where, $current_offset);
        }
	else
	{	
		$response = $seed->get_list($orderby, $where, $current_offset);
	}
	
	$aList = $response['list'];
	$row_count = $response['row_count'];
	$next_offset = $response['next_offset'];
	$previous_offset = $response['previous_offset'];
	
	$start_record = $current_offset + 1;
	
	// Set the start row to 0 if there are no rows (adding one looks bad)
	if($row_count == 0)
	    $start_record = 0;
	
	$end_record = $start_record + $list_max_entries_per_page;
	
	// back up the the last page.
	if($end_record > $row_count+1)
	{
	    $end_record = $row_count+1;
	}
	
	// Deterime the start location of the last page
	if($row_count == 0)
		$number_pages = 0;
	else
		$number_pages = floor(($row_count - 1) / $list_max_entries_per_page);
	
	$last_page_offset = $number_pages * $list_max_entries_per_page;
	
	
	// Create the base URL without the current offset.
	// Check to see if the current offset is already there
	// If not, add it to the end.
	
	// All of the other values should use a regular expression search
	$base_URL = $_SERVER['REQUEST_URI'] .'?'.$_SERVER['QUERY_STRING']."&current_offset=";
	$start_URL = $base_URL."0";
	$previous_URL  = $base_URL.$previous_offset;
	$next_URL  = $base_URL.$next_offset;
	$end_URL  = $base_URL.$last_page_offset;
	
	$sort_URL_base = $base_URL.$current_offset."&sort_order=";
	
	$log->debug("Offsets: (start, previous, next, last)(0, $previous_offset, $next_offset, $last_page_offset)");
	
	if(0 == $current_offset)
	    $start_link = $app_strings['LNK_LIST_START'];
	else
	    $start_link = "<a href=\"$start_URL\" class=\"listFormHeaderLinks\">".$app_strings['LNK_LIST_START']."</a>";
	
	if($previous_offset < 0)
	    $previous_link = $app_strings['LNK_LIST_PREVIOUS'];
	else
	    $previous_link = "<a href=\"$previous_URL\" class=\"listFormHeaderLinks\">".$app_strings['LNK_LIST_PREVIOUS']."</a>";
	
	if($next_offset >= $end_record)
	    $next_link = $app_strings['LNK_LIST_NEXT'];
	else
	    $next_link = "<a href=\"$next_URL\" class=\"listFormHeaderLinks\">".$app_strings['LNK_LIST_NEXT']."</a>";
	
	if($last_page_offset <= $current_offset)
	    $end_link = $app_strings['LNK_LIST_END'];
	else
	    $end_link = "<a href=\"$end_URL\" class=\"listFormHeaderLinks\">".$app_strings['LNK_LIST_END']."</a>";
	
	$log->info("Offset (next, current, prev)($next_offset, $current_offset, $previous_offset)");
	$log->info("Start/end records ($start_record, $end_record)");
	
	$list_form->assign("START_RECORD", $start_record);
	$list_form->assign("END_RECORD", $end_record-1);
	$list_form->assign("ROW_COUNT", $row_count);
	if ($start_link !== "") $list_form->assign("START_LINK", "[ ".$start_link." ]");
	if ($end_link !== "") $list_form->assign("END_LINK", "[ ".$end_link." ]");
	if ($next_link !== "") $list_form->assign("NEXT_LINK", "[ ".$next_link." ]");
	if ($previous_link !== "") $list_form->assign("PREVIOUS_LINK", "[ ".$previous_link." ]");
	$list_form->parse("main.list_nav_row");
	$oddRow = true;

        if($currentModule == "MessageBoard")
        {
               foreach($aList as $aItem)
                {
                        $fields = $aItem->get_list_view_data();
			$list_form->assign($html_varName, $fields);
                        $list_form->assign("MASS_DELETE_CHANGESTATUS", "");

                if($oddRow)
                    {
                                $list_form->assign("ROW_COLOR", 'oddListRow');
                    }
                    else
                    {
                                $list_form->assign("ROW_COLOR", 'evenListRow');
                    }
                                $oddRow = !$oddRow;
                                $list_form->parse("main.row");
                }
        }
	else
	{

	foreach($aList as $aItem)
	{
		$fields = $aItem->get_list_view_data();
		$list_form->assign($html_varName, $fields);
                $list_form->assign("MASS_DELETE_CHANGESTATUS", "<input class='button' type='submit' value='Mass Delete' onclick=\"return massDelete()\"/><br><input class='button' type='submit' value='Change Status' onclick=\"return changeStatus()\"/>");
		
		if($oddRow)
	    {
			$list_form->assign("ROW_COLOR", 'oddListRow');
	    }
	    else
	    {
			$list_form->assign("ROW_COLOR", 'evenListRow');
	    }
	    $oddRow = !$oddRow;
	    
	    $aItem->list_view_pare_additional_sections($list_form);
		$list_form->parse("main.row");
	}
	}
	$list_form->parse("main");
	
	if( $display_title == 'Lead List')
	{
	$button ="<table cellspacing='0' cellpadding='1' border='0'><form action='index.php?module=Leads&action=fetchfile' method=post target=''><tr><td>&nbsp;</td><td><input class='button' type='submit' name='Import' value='Import Leads'/></td></form></tr></table>";

	//include 'modules/imports/ImportButton.html';
//	$importTitle = "&nbsp;&nbsp; [ <A href='index.php?module=imports&action=import'><Blink><B>".$app_strings['LNK_IMPORT_LEADS']."</Blink></B></A> ]";
//	$display_title = $display_title .$importTitle;
	}
	echo get_form_header( $display_title, $button, false);
	$list_form->out("main");
	echo get_form_footer();

	echo "</td></tr>\n</table>\n";
}
?>
