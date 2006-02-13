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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Accounts/Popup_picker.php,v 1.3 2004/12/20 16:29:23 jack Exp $
 * Description:  This file is used for all popups on this module
 * The popup_picker.html file is used for generating a list from which to find and 
 * choose one instance.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $theme;
require_once('modules/Accounts/Account.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('XTemplate/xtpl.php');
require_once('include/ListView/ListView.php');
global $mod_strings;
global $app_strings;

global $list_max_entries_per_page;
global $urlPrefix;
global $currentModule;

$log = LoggerManager::getLogger('account');

$seedAccount = new Account();

$where = "";
if(isset($_REQUEST['query']))
{
	// we have a query
	if(isset($_REQUEST['name'])) $name = $_REQUEST['name'];
	if(isset($_REQUEST['billing_address_city'])) $billing_address_city = $_REQUEST['billing_address_city'];
	if(isset($_REQUEST['phone_office'])) $phone_office = $_REQUEST['phone_office'];

	$where_clauses = Array();

	if(isset($name))
	{
		array_push($where_clauses, "name like '$name%'");
	}
	if(isset($billing_address_city))
	{
		array_push($where_clauses, "billing_address_city like '$billing_address_city%'");
	}

	$where = "";
	foreach($where_clauses as $clause)
	{
		if($where != "")
		$where .= " and ";
		$where .= $clause;
	}

	$log->info("Here is the where clause for the list view: $where");

}
    
    
    
$image_path = 'themes/'.$theme.'/images';    
    
////////////////////////////////////////////////////////    
// Start the output    
////////////////////////////////////////////////////////    
if (!isset($_REQUEST['html'])) {
	$form =new XTemplate ('modules/Accounts/Popup_picker.html');
	$log->debug("using file modules/Accounts/Popup_picker.html");
}
else {
	$log->debug("_REQUEST['html'] is ".$_REQUEST['html']);
	$form =new XTemplate ('modules/Accounts/'.$_REQUEST['html'].'.html');
	$log->debug("using file modules/Accounts/".$_REQUEST['html'].'.html');
}

$form->assign("MOD", $mod_strings);
$form->assign("APP", $app_strings);

// the form key is required
if(!isset($_REQUEST['form']))
	die("Missing 'form' parameter");
	
// This code should always return an answer.
// The form name should be made into a parameter and not be hard coded in this file.
if($_REQUEST['form'] == 'TasksEditView')
{
	$the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
	$the_javascript .= "function set_return(account_id, account_name) {\n";
	$the_javascript .= "	window.opener.document.EditView.parent_name.value = account_name;\n";
	$the_javascript .= "	window.opener.document.EditView.parent_id.value = account_id;\n";
	$the_javascript .= "}\n";
	$the_javascript .= "</script>\n";
	$button  = "<table cellspacing='0' cellpadding='1' border='0'><form border='0' action='index.php' method='post' name='form' id='form'>\n";
	$button .= "<tr><td>&nbsp;</td>";
	$button .= "<td><input title='".$app_strings['LBL_CLEAR_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CLEAR_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.opener.document.EditView.parent_name.value = '';window.opener.document.EditView.parent_id.value = ''; window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CLEAR_BUTTON_LABEL']."  '>\n";
	$button .= "<td><input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '>\n";
	$button .= "</td></tr></form></table>\n";
}
elseif($_REQUEST['form'] == 'HelpDeskEditView')
{
	$the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
	$the_javascript .= "function set_return(account_id, account_name) {\n";
	$the_javascript .= "	window.opener.document.EditView.parent_name.value = account_name;\n";
	$the_javascript .= "	window.opener.document.EditView.parent_id.value = account_id;\n";
	$the_javascript .= "}\n";
	$the_javascript .= "</script>\n";
	$button  = "<table cellspacing='0' cellpadding='1' border='0'><form border='0' action='index.php' method='post' name='form' id='form'>\n";
	$button .= "<tr><td>&nbsp;</td>";
	$button .= "<td><input title='".$app_strings['LBL_CLEAR_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CLEAR_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.opener.document.EditView.parent_name.value = '';window.opener.document.EditView.parent_id.value = ''; window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CLEAR_BUTTON_LABEL']."  '>\n";
	$button .= "<td><input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '>\n";
	$button .= "</td></tr></form></table>\n";
}
elseif(isset($_REQUEST['form_submit']) && $_REQUEST['form'] == 'AccountDetailView' && $_REQUEST['form_submit'] == 'true')
{
	$the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
	$the_javascript .= "function set_return(account_id, account_name) {\n";
	$the_javascript .= "	window.opener.document.DetailView.member_id.value = account_id; \n";
	$the_javascript .= "	window.opener.document.DetailView.return_module.value = window.opener.document.DetailView.module.value; \n";
	$the_javascript .= "	window.opener.document.DetailView.return_action.value = 'DetailView'; \n";
	$the_javascript .= "	window.opener.document.DetailView.return_id.value = window.opener.document.DetailView.record.value; \n";
	$the_javascript .= "	window.opener.document.DetailView.action.value = 'Save'; \n";
	$the_javascript .= "	window.opener.document.DetailView.submit(); \n";
	$the_javascript .= "}\n";
	$the_javascript .= "</script>\n";
	$button  = "<table cellspacing='0' cellpadding='1' border='0'><form border='0' action='index.php' method='post' name='form' id='form'>\n";
	$button .= "<tr><td>&nbsp;</td>";
	$button .= "<td><input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '>\n";
	$button .= "</tr></form></table>\n";
}
elseif ($_REQUEST['form'] == 'EditView') 
{
	$the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
	$the_javascript .= "function set_return(account_id, account_name) {\n";
	$the_javascript .= "	window.opener.document.EditView.account_name.value = account_name;\n";
	$the_javascript .= "	window.opener.document.EditView.account_id.value = account_id;\n";
	$the_javascript .= "}\n";
	$the_javascript .= "</script>\n";
	$button  = "<table cellspacing='0' cellpadding='1' border='0'><form border='0' action='index.php' method='post' name='form' id='form'>\n";
	$button .= "<tr><td>&nbsp;</td>";
	$button .= "<td><input title='".$app_strings['LBL_CLEAR_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CLEAR_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.opener.document.EditView.account_name.value = '';window.opener.document.EditView.account_id.value = ''; window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CLEAR_BUTTON_LABEL']."  '>\n";
	$button .= "<td><input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '>\n";
	$button .= "</td></tr></form></table>\n";
}	

$form->assign("SET_RETURN_JS", $the_javascript);


$form->assign("THEME", $theme);
$form->assign("IMAGE_PATH", $image_path);
$form->assign("MODULE_NAME", $currentModule);
if (isset($_REQUEST['form_submit'])) $form->assign("FORM_SUBMIT", $_REQUEST['form_submit']);
$form->assign("FORM", $_REQUEST['form']);
  
if (isset($_REQUEST['name'])) $form->assign("NAME", $_REQUEST['name']);
if (isset($_REQUEST['billing_address_city'])) $form->assign("BILLING_ADDRESS_CITY", $_REQUEST['billing_address_city']);

insert_popup_header($theme);
// Quick search.

echo get_form_header($mod_strings['LBL_SEARCH_FORM_TITLE'], "", false); 

$form->parse("main.SearchHeader");
$form->out("main.SearchHeader");

echo get_form_footer();

$form->parse("main.SearchHeaderEnd");
$form->out("main.SearchHeaderEnd");

// Reset the sections that are already in the page so that they do not print again later.
$form->reset("main.SearchHeader");
$form->reset("main.SearchHeaderEnd");





$ListView = new ListView();
$ListView->setXTemplate($form);
$ListView->setHeaderTitle($mod_strings['LBL_LIST_FORM_TITLE']);
$ListView->setHeaderText($button);
$ListView->setQuery($where, "", "name", "ACCOUNT");
$ListView->setModStrings($mod_strings);
$ListView->processListView($seedAccount, "main", "ACCOUNT");


?>

	<tr><td COLSPAN=7><?php echo get_form_footer(); ?></td></tr>
	</table>
</td></tr></tbody></table>
</td></tr>

<?php insert_popup_footer(); ?>
