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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Contacts/Popup.php,v 1.3.2.1 2004/12/22 17:07:09 jack Exp $
 * Description:  This file is used for all popups on this module
 * The popup_picker.html file is used for generating a list from which to find and 
 * choose one instance.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $theme;
require_once('modules/Contacts/Contact.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('XTemplate/xtpl.php');
require_once('include/utils.php');
require_once('include/ListView/ListView.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;

global $list_max_entries_per_page;
global $urlPrefix;
global $currentModule;

$log = LoggerManager::getLogger('contact');

$seed_object = new Contact();


$where = "";
if(isset($_REQUEST['query']))
{
	$search_fields = Array("first_name", "last_name", "account_name");

	$where_clauses = Array();

	append_where_clause($where_clauses, "first_name", "contacts.first_name");
	append_where_clause($where_clauses, "last_name", "contacts.last_name");
	append_where_clause($where_clauses, "account_name", "accounts.name");
	append_where_clause($where_clauses, "account_id", "accounts.id");

	$where = generate_where_statement($where_clauses);
	$log->info($where);
}


$image_path = 'themes/'.$theme.'/images';

////////////////////////////////////////////////////////
// Start the output
////////////////////////////////////////////////////////
if (!isset($_REQUEST['html'])) {
	$form =new XTemplate ('modules/Contacts/Popup_picker.html');
	$log->debug("using file modules/Contacts/Popup_picker.html");
}
else {
	$log->debug("_REQUEST['html'] is ".$_REQUEST['html']);
	$form =new XTemplate ('modules/Contacts/'.$_REQUEST['html'].'.html');
	$log->debug("using file modules/Contacts/".$_REQUEST['html'].'.html');
}

$form->assign("MOD", $mod_strings);
$form->assign("APP", $app_strings);

// the form key is required
if(!isset($_REQUEST['form']))
	die("Missing 'form' parameter");
	
// This code should always return an answer.
// The form name should be made into a parameter and not be hard coded in this file.
if(isset($_REQUEST['form_submit']) && $_REQUEST['form'] == 'DetailView' && $_REQUEST['form_submit'] == 'true')
{
	$the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
	$the_javascript .= "function set_return(contact_id, contact_name) {\n";
	$the_javascript .= "	window.opener.document.DetailView.return_module.value = window.opener.document.DetailView.module.value; \n";
	$the_javascript .= "	window.opener.document.DetailView.return_action.value = 'DetailView'; \n";
	$the_javascript .= "	window.opener.document.DetailView.source_module.value = 'contacts'; \n";
	$the_javascript .= "    window.opener.document.DetailView.contact_id.value = contact_id; \n";
	$the_javascript .= "	window.opener.document.DetailView.entity_id.value = contact_id; \n";
	$the_javascript .= "	window.opener.document.DetailView.return_id.value = window.opener.document.DetailView.record.value; \n";
	$the_javascript .= "	window.opener.document.DetailView.action.value = 'Save'; \n";
	$the_javascript .= "	window.opener.document.DetailView.submit(); \n";
	$the_javascript .= "}\n";
	$the_javascript .= "</script>\n";
	$button  = "<table cellspacing='0' cellpadding='1' border='0'><form border='0' action='index.php' method='post' name='form' id='form'>\n";
	$button .= "<tr><td>&nbsp;</td>";
	$button .= "<td><input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessyKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '></td>\n";
	$button .= "</tr></form></table>\n";
}
elseif(isset($_REQUEST['form_submit']) && $_REQUEST['form'] == 'ContactDetailView' && $_REQUEST['form_submit'] == 'true')
{
	$the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
	$the_javascript .= "function set_return(contact_id, contact_name) {\n";
	$the_javascript .= "	window.opener.document.DetailView.new_reports_to_id.value = contact_id; \n";
	$the_javascript .= "	window.opener.document.DetailView.return_module.value = window.opener.document.DetailView.module.value; \n";
	$the_javascript .= "	window.opener.document.DetailView.return_action.value = 'DetailView'; \n";
	$the_javascript .= "	window.opener.document.DetailView.return_id.value = window.opener.document.DetailView.record.value; \n";
	$the_javascript .= "	window.opener.document.DetailView.action.value = 'Save'; \n";
	$the_javascript .= "	window.opener.document.DetailView.submit(); \n";
	$the_javascript .= "}\n";
	$the_javascript .= "</script>\n";
	$button  = "<table cellspacing='0' cellpadding='1' border='0'><form border='0' action='index.php' method='post' name='form' id='form'>\n";
	$button .= "<tr><td>&nbsp;</td>";
	$button .= "<td><input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessyKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '></td>\n";
	$button .= "</tr></form></table>\n";
}
elseif(isset($_REQUEST['form_submit']) && $_REQUEST['form'] == 'OpportunityDetailView' && $_REQUEST['form_submit'] == 'true')
{
	$the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
	$the_javascript .= "function set_return(contact_id, contact_name) {\n";
	$the_javascript .= "	window.opener.document.DetailView.contact_id.value = contact_id; \n";
	$the_javascript .= "	window.opener.document.DetailView.contact_role.value = '".$app_list_strings['opportunity_relationship_type_default_key']."'; \n";
	$the_javascript .= "	window.opener.document.DetailView.return_module.value = window.opener.document.DetailView.module.value; \n";
	$the_javascript .= "	window.opener.document.DetailView.return_action.value = 'DetailView'; \n";
	$the_javascript .= "	window.opener.document.DetailView.return_id.value = window.opener.document.DetailView.record.value; \n";
	$the_javascript .= "	window.opener.document.DetailView.module.value = 'Contacts'; \n";
	$the_javascript .= "	window.opener.document.DetailView.action.value = 'SaveContactOpportunityRelationship'; \n";
	$the_javascript .= "	window.opener.document.DetailView.submit(); \n";
	$the_javascript .= "}\n";
	$the_javascript .= "</script>\n";
	$button  = "<table cellspacing='0' cellpadding='1' border='0'><form border='0' action='index.php' method='post' name='form' id='form'>\n";
	$button .= "<tr><td>&nbsp;</td>";
	$button .= "<td><input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessyKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '>\n";
	$button .= "</td></tr></form></table>\n";
}
elseif(isset($_REQUEST['form_submit']) && $_REQUEST['form'] == 'CaseDetailView' && $_REQUEST['form_submit'] == 'true')
{
	$the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
	$the_javascript .= "function set_return(case_id, case_name) {\n";
	$the_javascript .= "	window.opener.document.DetailView.contact_id.value = contact_id; \n";
	$the_javascript .= "	window.opener.document.DetailView.contact_role.value = '".$app_list_strings['case_relationship_type_default_key']."'; \n";
	$the_javascript .= "	window.opener.document.DetailView.return_module.value = window.opener.document.DetailView.module.value; \n";
	$the_javascript .= "	window.opener.document.DetailView.return_action.value = 'DetailView'; \n";
	$the_javascript .= "	window.opener.document.DetailView.return_id.value = window.opener.document.DetailView.record.value; \n";
	$the_javascript .= "	window.opener.document.DetailView.action.value = 'SaveContactCaseRelationship'; \n";
	$the_javascript .= "	window.opener.document.DetailView.submit(); \n";
	$the_javascript .= "}\n";
	$the_javascript .= "</script>\n";
	$button  = "<table cellspacing='0' cellpadding='1' border='0'><form border='0' action='index.php' method='post' name='form' id='form'>\n";
	$button .= "<tr><td>&nbsp;</td>";
	$button .= "<td><input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessyKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '>\n";
	$button .= "</td></tr></form></table>\n";
}
elseif ($_REQUEST['form'] == 'ContactEditView') 
{
	$the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
	$the_javascript .= "function set_return(contact_id, contact_name) {\n";
	$the_javascript .= "	window.opener.document.EditView.reports_to_name.value = contact_name;\n";
	$the_javascript .= "	window.opener.document.EditView.reports_to_id.value = contact_id;\n";
	$the_javascript .= "}\n";
	$the_javascript .= "</script>\n";
	$button  = "<table cellspacing='0' cellpadding='1' border='0'><form border='0' action='index.php' method='post' name='form' id='form'>\n";
	$button .= "<tr><td>&nbsp;</td>";
	$button .= "<td><input title='".$app_strings['LBL_CLEAR_BUTTON_TITLE']."' accessyKey='".$app_strings['LBL_CLEAR_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.opener.document.EditView.reports_to_name.value = '';window.opener.document.EditView.reports_to_id.value = ''; window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CLEAR_BUTTON_LABEL']."  '></td>\n";
	$button .= "<td><input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessyKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '></td>\n";
	$button .= "</tr></form></table>\n";
}
elseif ($_REQUEST['form'] == 'appnew')                                                                                      {                                                                                                                                   $the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";                                               $the_javascript .= "function set_return(contact_id, contact_name, account_name) {\n";                                       $the_javascript .= "    window.opener.document.appnew.contact_name.value = contact_name;\n";                                $the_javascript .= "    window.opener.document.appnew.account_name.value = account_name;\n";                                $the_javascript .= "}\n";                                                                                                   $the_javascript .= "</script>\n";                                                                                           $button  = "<table cellspacing='0' cellpadding='1' border='0'><form border='0' action='index.php' method='post' name='form' id='form'>\n";                                                                                                              $button .= "<tr><td>&nbsp;</td>";                                                                                           $button .= "<td><input title='".$app_strings['LBL_CLEAR_BUTTON_TITLE']."' accessyKey='".$app_strings['LBL_CLEAR_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.opener.document.appnew.contact_name.value = '';window.opener.document.appnew.contact_id.value = ''; window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CLEAR_BUTTON_LABEL']."  '></td>\n";                                                                                                           $button .= "<td><input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessyKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '></td>\n";                                                                                       $button .= "</tr></form></table>\n";                                                                                }	
else 
{
	$the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
	$the_javascript .= "function set_return(contact_id, contact_name) {\n";
	$the_javascript .= "	window.opener.document.EditView.contact_name.value = contact_name;\n";
	$the_javascript .= "	window.opener.document.EditView.contact_id.value = contact_id;\n";
	$the_javascript .= "}\n";
	$the_javascript .= "</script>\n";
	$button  = "<table cellspacing='0' cellpadding='1' border='0'><form border='0' action='index.php' method='post' name='form' id='form'>\n";
	$button .= "<tr><td>&nbsp;</td>";
	$button .= "<td><input title='".$app_strings['LBL_CLEAR_BUTTON_TITLE']."' accessyKey='".$app_strings['LBL_CLEAR_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.opener.document.EditView.contact_name.value = '';window.opener.document.EditView.contact_id.value = ''; window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CLEAR_BUTTON_LABEL']."  '></td>\n";
	$button .= "<td><input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessyKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '></td>\n";
	$button .= "</tr></form></table>\n";
}	

$form->assign("SET_RETURN_JS", $the_javascript);

$form->assign("THEME", $theme);
$form->assign("IMAGE_PATH", $image_path);
$form->assign("MODULE_NAME", $currentModule);
if (isset($_REQUEST['form_submit'])) $form->assign("FORM_SUBMIT", $_REQUEST['form_submit']);
$form->assign("FORM", $_REQUEST['form']);

insert_popup_header($theme);

// Quick search.
echo get_form_header($mod_strings['LBL_SEARCH_FORM_TITLE'], "", false);

if (isset($_REQUEST['first_name']))
{
	$last_search['FIRST_NAME'] = $_REQUEST['first_name'];
 	
}

if (isset($_REQUEST['last_name']))
{
	$last_search['LAST_NAME'] = $_REQUEST['last_name'];
 	
}

if (isset($_REQUEST['account_name'])) 
{
	$last_search['ACCOUNT_NAME'] = $_REQUEST['account_name'];
 	
}

if (isset($last_search)) 
{
	$form->assign("LAST_SEARCH", $last_search);
}

$form->parse("main.SearchHeader");
$form->out("main.SearchHeader");

echo get_form_footer();

$form->parse("main.SearchHeaderEnd");
$form->out("main.SearchHeaderEnd");

// Reset the sections that are already in the page so that they do not print again later.
$form->reset("main.SearchHeader");
$form->reset("main.SearchHeaderEnd");

// Stick the form header out there.



$ListView = new ListView();
$ListView->setXTemplate($form);
$ListView->setHeaderTitle($mod_strings['LBL_LIST_FORM_TITLE']);
$ListView->setHeaderText($button);
$ListView->setQuery($where, "", "first_name, last_name", "CONTACT");
$ListView->setModStrings($mod_strings);
$ListView->processListView($seed_object, "main", "CONTACT");

?>

	<tr><td COLSPAN=7><?php echo get_form_footer(); ?></td></tr>
	</table>
</td></tr></tbody></table>
</td></tr>

<?php insert_popup_footer(); ?>
