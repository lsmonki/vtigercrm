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
 * $Header$
 * Description:  Includes generic helper functions used throughout the application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/



/** This function returns the name of the person.
  * It currently returns "first last".  It should not put the space if either name is not available.
  * It should not return errors if either name is not available.
  * If no names are present, it will return ""
  * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
  * All Rights Reserved.
  * Contributor(s): ______________________________________..
  */

  require_once('include/database/PearDatabase.php');
  require_once('include/ComboUtil.php'); //new
  require_once('include/utils/ListViewUtils.php');	
  require_once('include/utils/EditViewUtils.php');
  require_once('include/utils/DetailViewUtils.php');
  require_once('include/utils/CommonUtils.php');
  require_once('include/utils/InventoryUtils.php');
  require_once('include/utils/DeleteUtils.php');
  require_once('include/utils/SearchUtils.php');
  require_once('include/FormValidationUtil.php');
 
/** Function to return a full name
  * @param $row -- row:: Type integer
  * @param $first_column -- first column:: Type string
  * @param $last_column -- last column:: Type string
  * @returns $fullname -- fullname:: Type string 
  *
*/
function return_name(&$row, $first_column, $last_column)
{
	global $log;
	$log->debug("Entering return_name(".$row.",".$first_column.",".$last_column.") method ...");
	$first_name = "";
	$last_name = "";
	$full_name = "";

	if(isset($row[$first_column]))
	{
		$first_name = stripslashes($row[$first_column]);
	}

	if(isset($row[$last_column]))
	{
		$last_name = stripslashes($row[$last_column]);
	}

	$full_name = $first_name;

	// If we have a first name and we have a last name
	if($full_name != "" && $last_name != "")
	{
		// append a space, then the last name
		$full_name .= " ".$last_name;
	}
	// If we have no first name, but we have a last name
	else if($last_name != "")
	{
		// append the last name without the space.
		$full_name .= $last_name;
	}

	$log->debug("Exiting return_name method ...");
	return $full_name;
}

/** Function to return language 
  * @returns $languages -- languages:: Type string 
  *
*/

function get_languages()
{
	global $log;
	$log->debug("Entering get_languages() method ...");
	global $languages;
	$log->debug("Exiting get_languages method ...");
	return $languages;
}

/** Function to return language 
  * @param $key -- key:: Type string
  * @returns $languages -- languages:: Type string 
  *
*/

//seems not used
function get_language_display($key)
{
	global $log;
	$log->debug("Entering get_language_display(".$key.") method ...");
	global $languages;
	$log->debug("Exiting get_language_display method ...");
	return $languages[$key];
}

/** Function returns the user array 
  * @param $assigned_user_id -- assigned_user_id:: Type string
  * @returns $user_list -- user list:: Type array 
  *
*/

function get_assigned_user_name(&$assigned_user_id)
{
	global $log;
	$log->debug("Entering get_assigned_user_name(".$assigned_user_id.") method ...");
	$user_list = &get_user_array(false,"");
	if(isset($user_list[$assigned_user_id]))
	{
		$log->debug("Exiting get_assigned_user_name method ...");
		return $user_list[$assigned_user_id];
	}

	$log->debug("Exiting get_assigned_user_name method ...");
	return "";
}

/** Function returns the user key in user array 
  * @param $add_blank -- boolean:: Type boolean
  * @param $status -- user status:: Type string
  * @param $assigned_user -- user id:: Type string
  * @param $private -- sharing type:: Type string
  * @returns $user_array -- user array:: Type array 
  *
*/

//used in module file
function get_user_array($add_blank=true, $status="Active", $assigned_user="",$private="")
{
	global $log;
	$log->debug("Entering get_user_array(".$add_blank.",". $status.",".$assigned_user.",".$private.") method ...");
	global $current_user;
	if(isset($current_user) && $current_user->id != '')
	{
		require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
		require('user_privileges/user_privileges_'.$current_user->id.'.php');
	}
	static $user_array = null;
	$module=$_REQUEST['module'];

	if($user_array == null)
	{
		require_once('include/database/PearDatabase.php');
		$db = new PearDatabase();
		$temp_result = Array();
		// Including deleted vtiger_users for now.
		if (empty($status)) {
				$query = "SELECT id, user_name from vtiger_users";
		}
		else {
				if($private == 'private')
				{
					$log->debug("Sharing is Private. Only the current user should be listed");
					$query = "select id as id,user_name as user_name from vtiger_users where id=".$current_user->id." and status='Active' union select vtiger_user2role.userid as id,vtiger_users.user_name as user_name from vtiger_user2role inner join vtiger_users on vtiger_users.id=vtiger_user2role.userid inner join vtiger_role on vtiger_role.roleid=vtiger_user2role.roleid where vtiger_role.parentrole like '".$current_user_parent_role_seq."::%' and status='Active' union select shareduserid as id,vtiger_users.user_name as user_name from vtiger_tmp_write_user_sharing_per inner join vtiger_users on vtiger_users.id=vtiger_tmp_write_user_sharing_per.shareduserid where status='Active' and vtiger_tmp_write_user_sharing_per.userid=".$current_user->id." and vtiger_tmp_write_user_sharing_per.tabid=".getTabid($module);	
						
				}
				else
				{
					$log->debug("Sharing is Public. All vtiger_users should be listed");
					$query = "SELECT id, user_name from vtiger_users WHERE status='$status'";
				}
		}
		if (!empty($assigned_user)) {
			 $query .= " OR id='$assigned_user'";
		}

		$query .= " order by user_name ASC";

		$result = $db->query($query, true, "Error filling in user array: ");

		if ($add_blank==true){
			// Add in a blank row
			$temp_result[''] = '';
		}

		// Get the id and the name.
		while($row = $db->fetchByAssoc($result))
		{
			$temp_result[$row['id']] = $row['user_name'];
		}

		$user_array = &$temp_result;
	}

	$log->debug("Exiting get_user_array method ...");
	return $user_array;
}

/** Function skips executing arbitary commands given in a string
  * @param $string -- string:: Type string
  * @param $maxlength -- maximun length:: Type integer
  * @returns $string -- escaped string:: Type string 
  *
*/

function clean($string, $maxLength)
{
	global $log;
	$log->debug("Entering clean(".$string.",". $maxLength.") method ...");
	$string = substr($string, 0, $maxLength);
	$log->debug("Exiting clean method ...");
	return escapeshellcmd($string);
}

/**
 * Copy the specified request variable to the member variable of the specified object.
 * Do no copy if the member variable is already set.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function safe_map($request_var, & $focus, $always_copy = false)
{
	global $log;
	$log->debug("Entering safe_map(".$request_var.",".get_class($focus).",".$always_copy.") method ...");
	safe_map_named($request_var, $focus, $request_var, $always_copy);
	$log->debug("Exiting safe_map method ...");
}

/**
 * Copy the specified request variable to the member variable of the specified object.
 * Do no copy if the member variable is already set.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function safe_map_named($request_var, & $focus, $member_var, $always_copy)
{
	global $log;
	$log->debug("Entering safe_map_named(".$request_var.",".get_class($focus).",".$member_var.",".$always_copy.") method ...");
	if (isset($_REQUEST[$request_var]) && ($always_copy || is_null($focus->$member_var))) {
		$log->debug("safe map named called assigning '{$_REQUEST[$request_var]}' to $member_var");
		$focus->$member_var = $_REQUEST[$request_var];
	}
	$log->debug("Exiting safe_map_named method ...");
}

/** This function retrieves an application language file and returns the array of strings included in the $app_list_strings var.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 * If you are using the current language, do not call this function unless you are loading it for the first time */

function return_app_list_strings_language($language)
{
	global $log;
	$log->debug("Entering return_app_list_strings_language(".$language.") method ...");
	global $app_list_strings, $default_language, $log, $translation_string_prefix;
	$temp_app_list_strings = $app_list_strings;
	$language_used = $language;

	@include("include/language/$language.lang.php");
	if(!isset($app_list_strings))
	{
		$log->warn("Unable to find the application language file for language: ".$language);
		require("include/language/$default_language.lang.php");
		$language_used = $default_language;
	}

	if(!isset($app_list_strings))
	{
		$log->fatal("Unable to load the application language file for the selected language($language) or the default language($default_language)");
		$log->debug("Exiting return_app_list_strings_language method ...");
		return null;
	}


	$return_value = $app_list_strings;
	$app_list_strings = $temp_app_list_strings;

	$log->debug("Exiting return_app_list_strings_language method ...");
	return $return_value;
}

/** This function retrieves an application language file and returns the array of strings included.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 * If you are using the current language, do not call this function unless you are loading it for the first time */
function return_application_language($language)
{
	global $log;
	$log->debug("Entering return_application_language(".$language.") method ...");
	global $app_strings, $default_language, $log, $translation_string_prefix;
	$temp_app_strings = $app_strings;
	$language_used = $language;

	@include("include/language/$language.lang.php");
	if(!isset($app_strings))
	{
		$log->warn("Unable to find the application language file for language: ".$language);
		require("include/language/$default_language.lang.php");
		$language_used = $default_language;
	}

	if(!isset($app_strings))
	{
		$log->fatal("Unable to load the application language file for the selected language($language) or the default language($default_language)");
		$log->debug("Exiting return_application_language method ...");
		return null;
	}

	// If we are in debug mode for translating, turn on the prefix now!
	if($translation_string_prefix)
	{
		foreach($app_strings as $entry_key=>$entry_value)
		{
			$app_strings[$entry_key] = $language_used.' '.$entry_value;
		}
	}

	$return_value = $app_strings;
	$app_strings = $temp_app_strings;

	$log->debug("Exiting return_application_language method ...");
	return $return_value;
}

/** This function retrieves a module's language file and returns the array of strings included.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 * If you are in the current module, do not call this function unless you are loading it for the first time */
function return_module_language($language, $module)
{
	global $log;
	$log->debug("Entering return_module_language(".$language.",". $module.") method ...");
	global $mod_strings, $default_language, $log, $currentModule, $translation_string_prefix;

	if($currentModule == $module && isset($mod_strings) && $mod_strings != null)
	{
		// We should have already loaded the array.  return the current one.
		$log->debug("Exiting return_module_language method ...");
		return $mod_strings;
	}

	$temp_mod_strings = $mod_strings;
	$language_used = $language;

	@include("modules/$module/language/$language.lang.php");
	if(!isset($mod_strings))
	{
		$log->warn("Unable to find the module language file for language: ".$language." and module: ".$module);
		require("modules/$module/language/$default_language.lang.php");
		$language_used = $default_language;
	}

	if(!isset($mod_strings))
	{
		$log->fatal("Unable to load the module($module) language file for the selected language($language) or the default language($default_language)");
		$log->debug("Exiting return_module_language method ...");
		return null;
	}

	// If we are in debug mode for translating, turn on the prefix now!
	if($translation_string_prefix)
	{
		foreach($mod_strings as $entry_key=>$entry_value)
		{
			$mod_strings[$entry_key] = $language_used.' '.$entry_value;
		}
	}

	$return_value = $mod_strings;
	$mod_strings = $temp_mod_strings;

	$log->debug("Exiting return_module_language method ...");
	return $return_value;
}

/*This function returns the mod_strings for the current language and the specified module
*/

function return_specified_module_language($language, $module)
{
	global $log;
	global $default_language, $translation_string_prefix;

	@include("modules/$module/language/$language.lang.php");
	if(!isset($mod_strings))
	{
		$log->warn("Unable to find the module language file for language: ".$language." and module: ".$module);
		require("modules/$module/language/$default_language.lang.php");
		$language_used = $default_language;
	}

	if(!isset($mod_strings))
	{
		$log->fatal("Unable to load the module($module) language file for the selected language($language) or the default language($default_language)");
		$log->debug("Exiting return_module_language method ...");
		return null;
	}

	$return_value = $mod_strings;

	$log->debug("Exiting return_module_language method ...");
	return $return_value;
}

/** This function retrieves an application language file and returns the array of strings included in the $mod_list_strings var.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 * If you are using the current language, do not call this function unless you are loading it for the first time */
function return_mod_list_strings_language($language,$module)
{
	global $log;
	$log->debug("Entering return_mod_list_strings_language(".$language.",".$module.") method ...");
	global $mod_list_strings, $default_language, $log, $currentModule,$translation_string_prefix;

	$language_used = $language;
	$temp_mod_list_strings = $mod_list_strings;

	if($currentModule == $module && isset($mod_list_strings) && $mod_list_strings != null)
	{
		$log->debug("Exiting return_mod_list_strings_language method ...");
		return $mod_list_strings;
	}

	@include("modules/$module/language/$language.lang.php");

	if(!isset($mod_list_strings))
	{
		$log->fatal("Unable to load the application list language file for the selected language($language) or the default language($default_language)");
		$log->debug("Exiting return_mod_list_strings_language method ...");
		return null;
	}

	$return_value = $mod_list_strings;
	$mod_list_strings = $temp_mod_list_strings;

	$log->debug("Exiting return_mod_list_strings_language method ...");
	return $return_value;
}

/** This function retrieves a theme's language file and returns the array of strings included.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function return_theme_language($language, $theme)
{
	global $log;
	$log->debug("Entering return_theme_language(".$language.",". $theme.") method ...");
	global $mod_strings, $default_language, $log, $currentModule, $translation_string_prefix;

	$language_used = $language;

	@include("themes/$theme/language/$current_language.lang.php");
	if(!isset($theme_strings))
	{
		$log->warn("Unable to find the theme file for language: ".$language." and theme: ".$theme);
		require("themes/$theme/language/$default_language.lang.php");
		$language_used = $default_language;
	}

	if(!isset($theme_strings))
	{
		$log->fatal("Unable to load the theme($theme) language file for the selected language($language) or the default language($default_language)");
		$log->debug("Exiting return_theme_language method ...");
		return null;
	}

	// If we are in debug mode for translating, turn on the prefix now!
	if($translation_string_prefix)
	{
		foreach($theme_strings as $entry_key=>$entry_value)
		{
			$theme_strings[$entry_key] = $language_used.' '.$entry_value;
		}
	}

	$log->debug("Exiting return_theme_language method ...");
	return $theme_strings;
}



/** If the session variable is defined and is not equal to "" then return it.  Otherwise, return the default value.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
*/
function return_session_value_or_default($varname, $default)
{
	global $log;
	$log->debug("Entering return_session_value_or_default(".$varname.",". $default.") method ...");
	if(isset($_SESSION[$varname]) && $_SESSION[$varname] != "")
	{
		$log->debug("Exiting return_session_value_or_default method ...");
		return $_SESSION[$varname];
	}

	$log->debug("Exiting return_session_value_or_default method ...");
	return $default;
}

/**
  * Creates an array of where restrictions.  These are used to construct a where SQL statement on the query
  * It looks for the variable in the $_REQUEST array.  If it is set and is not "" it will create a where clause out of it.
  * @param &$where_clauses - The array to append the clause to
  * @param $variable_name - The name of the variable to look for an add to the where clause if found
  * @param $SQL_name - [Optional] If specified, this is the SQL column name that is used.  If not specified, the $variable_name is used as the SQL_name.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
  */
function append_where_clause(&$where_clauses, $variable_name, $SQL_name = null)
{
	global $log;
	$log->debug("Entering append_where_clause(".$where_clauses.",".$variable_name.",".$SQL_name.") method ...");
	if($SQL_name == null)
	{
		$SQL_name = $variable_name;
	}

	if(isset($_REQUEST[$variable_name]) && $_REQUEST[$variable_name] != "")
	{
		array_push($where_clauses, "$SQL_name like '$_REQUEST[$variable_name]%'");
	}
	$log->debug("Exiting append_where_clause method ...");
}

/**
  * Generate the appropriate SQL based on the where clauses.
  * @param $where_clauses - An Array of individual where clauses stored as strings
  * @returns string where_clause - The final SQL where clause to be executed.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
  */
function generate_where_statement($where_clauses)
{
	global $log;
	$log->debug("Entering generate_where_statement(".$where_clauses.") method ...");
	$where = "";
	foreach($where_clauses as $clause)
	{
		if($where != "")
		$where .= " and ";
		$where .= $clause;
	}

	$log->info("Here is the where clause for the list view: $where");
	$log->debug("Exiting generate_where_statement method ...");
	return $where;
}

/**
 * A temporary method of generating GUIDs of the correct format for our DB.
 * @return String contianing a GUID in the format: aaaaaaaa-bbbb-cccc-dddd-eeeeeeeeeeee
 *
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
*/
function create_guid()
{
	global $log;
	$log->debug("Entering create_guid() method ...");
        $microTime = microtime();
	list($a_dec, $a_sec) = explode(" ", $microTime);

	$dec_hex = sprintf("%x", $a_dec* 1000000);
	$sec_hex = sprintf("%x", $a_sec);

	ensure_length($dec_hex, 5);
	ensure_length($sec_hex, 6);

	$guid = "";
	$guid .= $dec_hex;
	$guid .= create_guid_section(3);
	$guid .= '-';
	$guid .= create_guid_section(4);
	$guid .= '-';
	$guid .= create_guid_section(4);
	$guid .= '-';
	$guid .= create_guid_section(4);
	$guid .= '-';
	$guid .= $sec_hex;
	$guid .= create_guid_section(6);

	$log->debug("Exiting create_guid method ...");
	return $guid;

}

/** Function to create guid section for a given character
  * @param $characters -- characters:: Type string
  * @returns $return -- integer:: Type integer``
  */
function create_guid_section($characters)
{
	global $log;
	$log->debug("Entering create_guid_section(".$characters.") method ...");
	$return = "";
	for($i=0; $i<$characters; $i++)
	{
		$return .= sprintf("%x", rand(0,15));
	}
	$log->debug("Exiting create_guid_section method ...");
	return $return;
}

/** Function to ensure length
  * @param $string -- string:: Type string
  * @param $length -- length:: Type string
  */

function ensure_length(&$string, $length)
{
	global $log;
	$log->debug("Entering ensure_length(".$string.",". $length.") method ...");
	$strlen = strlen($string);
	if($strlen < $length)
	{
		$string = str_pad($string,$length,"0");
	}
	else if($strlen > $length)
	{
		$string = substr($string, 0, $length);
	}
	$log->debug("Exiting ensure_length method ...");
}
/*
function microtime_diff($a, $b) {
	global $log;
	$log->debug("Entering microtime_diff(".$a.",". $b.") method ...");
	list($a_dec, $a_sec) = explode(" ", $a);
	list($b_dec, $b_sec) = explode(" ", $b);
	$log->debug("Exiting microtime_diff method ...");
	return $b_sec - $a_sec + $b_dec - $a_dec;
}
 */

/**
 * Return the display name for a theme if it exists.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_theme_display($theme) {
	global $log;
	$log->debug("Entering get_theme_display(".$theme.") method ...");
	global $theme_name, $theme_description;
	$temp_theme_name = $theme_name;
	$temp_theme_description = $theme_description;

	if (is_file("./themes/$theme/config.php")) {
		@include("./themes/$theme/config.php");
		$return_theme_value = $theme_name;
	}
	else {
		$return_theme_value = $theme;
	}
	$theme_name = $temp_theme_name;
	$theme_description = $temp_theme_description;

	$log->debug("Exiting get_theme_display method ...");
	return $return_theme_value;
}

/**
 * Return an array of directory names.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_themes() {
	global $log;
	$log->debug("Entering get_themes() method ...");
   if ($dir = @opendir("./themes")) {
		while (($file = readdir($dir)) !== false) {
           if ($file != ".." && $file != "." && $file != "CVS" && $file != "Attic" && $file != "akodarkgem" && $file != "bushtree" && $file != "coolblue" && $file != "Amazon" && $file != "busthree" && $file != "Aqua" && $file != "nature" && $file != "orange" && $file != "blue") {
			   if(is_dir("./themes/".$file)) {
				   if(!($file[0] == '.')) {
				   	// set the initial theme name to the filename
				   	$name = $file; 

				   	// if there is a configuration class, load that.
				   	if(is_file("./themes/$file/config.php"))
				   	{
				   		require_once("./themes/$file/config.php");
				   		$name = $theme_name;
				   	}

				   	if(is_file("./themes/$file/header.php"))
					{
						$filelist[$file] = $name;
					}
				   }
			   }
		   }
	   }
	   closedir($dir);
   }

   ksort($filelist);
   $log->debug("Exiting get_themes method ...");
   return $filelist;
}



/**
 * Create javascript to clear values of all elements in a form.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_clear_form_js () {
global $log;
$log->debug("Entering get_clear_form_js () method ...");
$the_script = <<<EOQ
<script type="text/javascript" language="JavaScript">
<!-- Begin
function clear_form(form) {
	for (j = 0; j < form.elements.length; j++) {
		if (form.elements[j].type == 'text' || form.elements[j].type == 'select-one') {
			form.elements[j].value = '';
		}
	}
}
//  End -->
</script>
EOQ;

$log->debug("Exiting get_clear_form_js  method ...");
return $the_script;
}

/**
 * Create javascript to set the cursor focus to specific vtiger_field in a form
 * when the screen is rendered.  The vtiger_field name is currently hardcoded into the
 * the function.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_set_focus_js () {
global $log;
$log->debug("Entering set_focus() method ...");
//TODO Clint 5/20 - Make this function more generic so that it can take in the target form and vtiger_field names as variables
$the_script = <<<EOQ
<script type="text/javascript" language="JavaScript">
<!-- Begin
function set_focus() {
	if (document.forms.length > 0) {
		for (i = 0; i < document.forms.length; i++) {
			for (j = 0; j < document.forms[i].elements.length; j++) {
				var vtiger_field = document.forms[i].elements[j];
				if ((vtiger_field.type == "text" || vtiger_field.type == "textarea" || vtiger_field.type == "password") &&
						!field.disabled && (vtiger_field.name == "first_name" || vtiger_field.name == "name")) {
				vtiger_field.focus();
                    if (vtiger_field.type == "text") {
                        vtiger_field.select();
                    }
					break;
	    		}
			}
      	}
   	}
}
//  End -->
</script>
EOQ;

$log->debug("Exiting get_set_focus_js  method ...");
return $the_script;
}

/**
 * Very cool algorithm for sorting multi-dimensional arrays.  Found at http://us2.php.net/manual/en/function.array-multisort.php
 * Syntax: $new_array = array_csort($array [, 'col1' [, SORT_FLAG [, SORT_FLAG]]]...);
 * Explanation: $array is the array you want to sort, 'col1' is the name of the column
 * you want to sort, SORT_FLAGS are : SORT_ASC, SORT_DESC, SORT_REGULAR, SORT_NUMERIC, SORT_STRING
 * you can repeat the 'col',FLAG,FLAG, as often you want, the highest prioritiy is given to
 * the first - so the array is sorted by the last given column first, then the one before ...
 * Example: $array = array_csort($array,'town','age',SORT_DESC,'name');
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function array_csort() {
   global $log;
   $log->debug("Entering array_csort() method ...");
   $args = func_get_args();
   $marray = array_shift($args);
   $i = 0;

   $msortline = "return(array_multisort(";
   foreach ($args as $arg) {
	   $i++;
	   if (is_string($arg)) {
		   foreach ($marray as $row) {
			   $sortarr[$i][] = $row[$arg];
		   }
	   } else {
		   $sortarr[$i] = $arg;
	   }
	   $msortline .= "\$sortarr[".$i."],";
   }
   $msortline .= "\$marray));";

   eval($msortline);
   $log->debug("Exiting array_csort method ...");
   return $marray;
}

/** Function to set default varibles on to the global variable
  * @param $defaults -- default values:: Type array
       */
function set_default_config(&$defaults)
{
	global $log;
	$log->debug("Entering set_default_config(".$defaults.") method ...");

	foreach ($defaults as $name=>$value)
	{
		if ( ! isset($GLOBALS[$name]) )
		{
			$GLOBALS[$name] = $value;
		}
	}
	$log->debug("Exiting set_default_config method ...");
}

$toHtml = array(
        '"' => '&quot;',
        '<' => '&lt;',
        '>' => '&gt;',
        '& ' => '&amp; ',
        "'" =>  '&#039;',
);

/** Function to convert the given string to html
  * @param $string -- string:: Type string
  * @param $ecnode -- boolean:: Type boolean
    * @returns $string -- string:: Type string 
      *
       */
function to_html($string, $encode=true){
	global $log;
	$log->debug("Entering to_html(".$string.",".$encode.") method ...");
        global $toHtml;
        if($encode && is_string($string)){//$string = htmlentities($string, ENT_QUOTES);
		if (is_array($toHtml))
			$string =strip_tags($string, '<span><br /><div><a><br><b><u><i><table><td><tr><style><p><command><h1><h2><h3><h4><h5><h6><li><ol><ul><th><tbody><font><center><big><hr><format> <strong><html><small>');
        }
	$log->debug("Exiting to_html method ...");
        return $string;
}

/** Function to get the assigned user name or group name
  * @param $id -- user id:: Type integer
  * @param $module -- module name:: Type string
    * @returns $string -- string:: Type string 
      *
       */

//it seems the fun ction is not used
function get_assigned_user_or_group_name($id,$module)
{
	global $log;
	$log->debug("Entering get_assigned_user_or_group_name(".$id.",".$module.") method ...");
	global $adb;

	//it might so happen that an entity is assigned to a group but at that time the group has no members. even in this case, the query should return a valid value and not just blank

  if($module == 'Leads')
  {

   $sql="select (case when (user_name is null) then  (vtiger_leadgrouprelation.groupname) else (user_name) end) as name from leads left join vtiger_users on vtiger_users.id= assigned_user_id left join vtiger_leadgrouprelation on vtiger_leadgrouprelation.leadid=leads.id where leads.deleted=0 and leads.id='". $id ."'";
   
  }
  else if($module == 'Tasks')
  {
       $sql="select (case when (user_name is null) then  (taskgrouprelation.groupname) else (user_name) end) as name from tasks left join vtiger_users on vtiger_users.id= assigned_user_id left join taskgrouprelation on taskgrouprelation.taskid=tasks.id where tasks.deleted=0 and tasks.id='". $id ."'";
  }
  else if($module == 'Calls')
  {
       $sql="select (case when (user_name is null) then  (callgrouprelation.groupname) else (user_name) end) as name from calls left join vtiger_users on vtiger_users.id= assigned_user_id left join callgrouprelation on callgrouprelation.callid=calls.id where calls.deleted=0 and calls.id='". $id ."'";
  }

	$result = $adb->query($sql);
	$tempval = $adb->fetch_row($result);
	$log->debug("Exiting get_assigned_user_or_group_name method ...");
	return $tempval[0];
}

/** Function to get the tabname for a given id
  * @param $tabid -- tab id:: Type integer
    * @returns $string -- string:: Type string 
      *
       */

function getTabname($tabid)
{
	global $log;
	$log->debug("Entering getTabname(".$tabid.") method ...");
        $log->info("tab id is ".$tabid);
        global $adb;
	$sql = "select tablabel from vtiger_tab where tabid='".$tabid."'";
	$result = $adb->query($sql);
	$tabname=  $adb->query_result($result,0,"tablabel");
	$log->debug("Exiting getTabname method ...");
	return $tabname;

}

/** Function to get the tab module name for a given id
  * @param $tabid -- tab id:: Type integer
    * @returns $string -- string:: Type string 
      *
       */

function getTabModuleName($tabid)
{
	global $log;
	$log->debug("Entering getTabModuleName(".$tabid.") method ...");
	if (file_exists('tabdata.php') && (filesize('tabdata.php') != 0))
        {
                include('tabdata.php');
		$tabname = array_search($tabid,$tab_info_array);
        }
        else
        {
	global $log;
        $log->info("tab id is ".$tabid);
        global $adb;
        $sql = "select name from vtiger_tab where tabid='".$tabid."'";
        $result = $adb->query($sql);
        $tabname=  $adb->query_result($result,0,"name");
	}
	$log->debug("Exiting getTabModuleName method ...");
        return $tabname;
}

/** Function to get column fields for a given module
  * @param $module -- module:: Type string
    * @returns $column_fld -- column field :: Type array 
      *
       */

function getColumnFields($module)
{
	global $log;
	$log->debug("Entering getColumnFields(".$module.") method ...");
	$log->info("in getColumnFields ".$module);
	global $adb;
	$column_fld = Array();
        $tabid = getTabid($module);
	$sql = "select * from vtiger_field where tabid=".$tabid;
        $result = $adb->query($sql);
        $noofrows = $adb->num_rows($result);
	for($i=0; $i<$noofrows; $i++)
	{
		$fieldname = $adb->query_result($result,$i,"fieldname");
		$column_fld[$fieldname] = ''; 
	}
	$log->debug("Exiting getColumnFields method ...");
	return $column_fld;	
}

/** Function to get a users's mail id
  * @param $userid -- userid :: Type integer
    * @returns $email -- email :: Type string 
      *
       */

function getUserEmail($userid)
{
	global $log;
	$log->debug("Entering getUserEmail(".$userid.") method ...");
	$log->info("in getUserEmail ".$userid);

        global $adb;
        if($userid != '')
        {
                $sql = "select email1 from vtiger_users where id=".$userid;
                $result = $adb->query($sql);
                $email = $adb->query_result($result,0,"email1");
        }
	$log->debug("Exiting getUserEmail method ...");
        return $email;
}		

/** Function to get a userid for outlook
  * @param $username -- username :: Type string
    * @returns $user_id -- user id :: Type integer 
       */

//outlook security
function getUserId_Ol($username)
{
	global $log;
	$log->debug("Entering getUserId_Ol(".$username.") method ...");
	$log->info("in getUserId_Ol ".$username);

	global $adb;
	$sql = "select id from vtiger_users where user_name='".$username."'";
	$result = $adb->query($sql);
	$num_rows = $adb->num_rows($result);
	if($num_rows > 0)
	{
		$user_id = $adb->query_result($result,0,"id");
    	}
	else
	{
		$user_id = 0;
	}
	$log->debug("Exiting getUserId_Ol method ...");
	return $user_id;
}	


/** Function to get a action id for a given action name
  * @param $action -- action name :: Type string
    * @returns $actionid -- action id :: Type integer 
       */

//outlook security

function getActionid($action)
{
	global $log;
	$log->debug("Entering getActionid(".$action.") method ...");
	global $adb;
	$log->info("get Actionid ".$action);
	$actionid = '';
	if(file_exists('tabdata.php') && (filesize('tabdata.php') != 0)) 
	{
		include('tabdata.php');
		$actionid= $action_id_array[$action];
	}
	else
	{
		$query="select * from vtiger_actionmapping where actionname='".$action."'";
        	$result =$adb->query($query);
        	$actionid=$adb->query_result($result,0,'actionid');
		
	}
	$log->info("action id selected is ".$actionid );
	$log->debug("Exiting getActionid method ...");	
	return $actionid;
}

/** Function to get a action for a given action id
  * @param $action id -- action id :: Type integer
    * @returns $actionname-- action name :: Type string 
       */


function getActionname($actionid)
{
	global $log;
	$log->debug("Entering getActionname(".$actionid.") method ...");
	global $adb;

	$actionname='';
	
	if (file_exists('tabdata.php') && (filesize('tabdata.php') != 0)) 
	{
		include('tabdata.php');
		$actionname= $action_name_array[$actionid];
	}
	else
	{
	
		$query="select * from vtiger_actionmapping where actionid=".$actionid ." and securitycheck=0";
		$result =$adb->query($query);
		$actionname=$adb->query_result($result,0,"actionname");
	}	
	$log->debug("Exiting getActionname method ...");
	return $actionname;
}

/** Function to get a assigned user id for a given entity
  * @param $record -- entity id :: Type integer
    * @returns $user_id -- user id :: Type integer 
       */

function getUserId($record)
{
	global $log;
	$log->debug("Entering getUserId(".$record.") method ...");
        $log->info("in getUserId ".$record);

	global $adb;
        $user_id=$adb->query_result($adb->query("select * from vtiger_crmentity where crmid = ".$record),0,'smownerid');
	$log->debug("Exiting getUserId method ...");
	return $user_id;	
}

/** Function to get a user id or group id for a given entity
  * @param $record -- entity id :: Type integer
    * @returns $ownerArr -- owner id :: Type array 
       */

function getRecordOwnerId($record)
{
	global $log;
	$log->debug("Entering getRecordOwnerId(".$record.") method ...");

	global $adb;
	$ownerArr=Array();
	$query="select * from vtiger_crmentity where crmid = ".$record;
	$result=$adb->query($query);
	$user_id=$adb->query_result($result,0,'smownerid');
	if($user_id != 0)
	{
		$ownerArr['Users']=$user_id;

	}
	elseif($user_id == 0)
	{
		$module=$adb->query_result($result,0,'setype');
		if($module == 'Leads')
		{
			$query1="select vtiger_groups.groupid from vtiger_leadgrouprelation inner join vtiger_groups on vtiger_groups.groupname = vtiger_leadgrouprelation.groupname where leadid=".$record;
		}
		elseif($module == 'Calendar' || $module == 'Emails')
		{

			$query1="select vtiger_groups.groupid from vtiger_activitygrouprelation inner join vtiger_groups on vtiger_groups.groupname = vtiger_activitygrouprelation.groupname where activityid=".$record;
		}
		elseif($module == 'HelpDesk')
		{
			$query1="select vtiger_groups.groupid from vtiger_ticketgrouprelation inner join vtiger_groups on vtiger_groups.groupname = vtiger_ticketgrouprelation.groupname where ticketid=".$record;
		}
		elseif($module == 'Accounts')
		{
			$query1="select vtiger_groups.groupid from vtiger_accountgrouprelation inner join vtiger_groups on vtiger_groups.groupname = vtiger_accountgrouprelation.groupname where accountid=".$record;
		}
		elseif($module == 'Contacts')
		{
			$query1="select vtiger_groups.groupid from vtiger_contactgrouprelation inner join vtiger_groups on vtiger_groups.groupname = vtiger_contactgrouprelation.groupname where contactid=".$record;
		}
		elseif($module == 'Potentials')
		{
			$query1="select vtiger_groups.groupid from vtiger_potentialgrouprelation inner join vtiger_groups on vtiger_groups.groupname = vtiger_potentialgrouprelation.groupname where potentialid=".$record;
		}
		elseif($module == 'Quotes')
		{
			$query1="select vtiger_groups.groupid from vtiger_quotegrouprelation inner join vtiger_groups on vtiger_groups.groupname = vtiger_quotegrouprelation.groupname where quoteid=".$record;
		}
		elseif($module == 'PurchaseOrder')
		{
			$query1="select vtiger_groups.groupid from vtiger_pogrouprelation inner join vtiger_groups on vtiger_groups.groupname = vtiger_pogrouprelation.groupname where purchaseorderid=".$record;
		}
		elseif($module == 'SalesOrder')
		{
			$query1="select vtiger_groups.groupid from vtiger_sogrouprelation inner join vtiger_groups on vtiger_groups.groupname = vtiger_sogrouprelation.groupname where salesorderid=".$record;
		}
		elseif($module == 'Invoice')
		{
			$query1="select vtiger_groups.groupid from vtiger_invoicegrouprelation inner join vtiger_groups on vtiger_groups.groupname = vtiger_invoicegrouprelation.groupname where invoiceid=".$record;
		}
		elseif($module == 'Campaigns')
		{
			$query1="select vtiger_groups.groupid from vtiger_campaigngrouprelation inner join vtiger_groups on vtiger_groups.groupname = vtiger_campaigngrouprelation.groupname where campaignid=".$record;
		}
		else
		{
			require_once("modules/$module/$module.php");
			$modObj = new $module();
			$query1="select vtiger_groups.groupid from vtiger_".$module."grouprelation inner join vtiger_groups on vtiger_groups.groupname = vtiger_".$module."grouprelation.groupname where ".$modObj->groupTable[1]."=".$record;
		}

		$result1=$adb->query($query1);
		$groupid=$adb->query_result($result1,0,'groupid');
		$ownerArr['Groups']=$groupid;

	}	
	$log->debug("Exiting getRecordOwnerId method ...");
	return $ownerArr;

}

/** Function to insert value to profile2field table
  * @param $profileid -- profileid :: Type integer
       */


function insertProfile2field($profileid)
{
	global $log;
	$log->debug("Entering insertProfile2field(".$profileid.") method ...");
        $log->info("in insertProfile2field ".$profileid);

	global $adb;
	$adb->database->SetFetchMode(ADODB_FETCH_ASSOC); 
	$fld_result = $adb->query("select * from vtiger_field where generatedtype=1 and displaytype in (1,2,3) and tabid != 29");
        $num_rows = $adb->num_rows($fld_result);
        for($i=0; $i<$num_rows; $i++)
        {
                 $tab_id = $adb->query_result($fld_result,$i,'tabid');
                 $field_id = $adb->query_result($fld_result,$i,'fieldid');
                 $adb->query("insert into vtiger_profile2field values (".$profileid.",".$tab_id.",".$field_id.",0,1)");
	}
	$log->debug("Exiting insertProfile2field method ...");
}

/** Function to insert into default org field
       */

function insert_def_org_field()
{
	global $log;
	$log->debug("Entering insert_def_org_field() method ...");
	global $adb;
	$adb->database->SetFetchMode(ADODB_FETCH_ASSOC); 
	$fld_result = $adb->query("select * from vtiger_field where generatedtype=1 and displaytype in (1,2,3) and tabid != 29");
        $num_rows = $adb->num_rows($fld_result);
        for($i=0; $i<$num_rows; $i++)
        {
                 $tab_id = $adb->query_result($fld_result,$i,'tabid');
                 $field_id = $adb->query_result($fld_result,$i,'fieldid');
                 $adb->query("insert into vtiger_def_org_field values (".$tab_id.",".$field_id.",0,1)");
	}
	$log->debug("Exiting insert_def_org_field() method ...");
}

/** Function to insert value to profile2field table
  * @param $fld_module -- field module :: Type string
  * @param $profileid -- profileid :: Type integer
  * @returns $result -- result :: Type string
  */
	 
function getProfile2FieldList($fld_module, $profileid)
{
	global $log;
	$log->debug("Entering getProfile2FieldList(".$fld_module.",". $profileid.") method ...");
        $log->info("in getProfile2FieldList ".$fld_module. ' vtiger_profile id is  '.$profileid);

	global $adb;
	$tabid = getTabid($fld_module);
	
	$query = "select vtiger_profile2field.visible,vtiger_field.* from vtiger_profile2field inner join vtiger_field on vtiger_field.fieldid=vtiger_profile2field.fieldid where vtiger_profile2field.profileid=".$profileid." and vtiger_profile2field.tabid=".$tabid;
	$result = $adb->query($query);
	$log->debug("Exiting getProfile2FieldList method ...");
	return $result;
}

/** Function to insert value to profile2fieldPermissions table
  * @param $fld_module -- field module :: Type string
  * @param $profileid -- profileid :: Type integer
  * @returns $return_data -- return_data :: Type string
  */

//added by jeri

function getProfile2FieldPermissionList($fld_module, $profileid)
{
	global $log;
	$log->debug("Entering getProfile2FieldPermissionList(".$fld_module.",". $profileid.") method ...");
        $log->info("in getProfile2FieldList ".$fld_module. ' vtiger_profile id is  '.$profileid);

	global $adb;
	$tabid = getTabid($fld_module);
	
	$query = "select vtiger_profile2field.visible,vtiger_field.* from vtiger_profile2field inner join vtiger_field on vtiger_field.fieldid=vtiger_profile2field.fieldid where vtiger_profile2field.profileid=".$profileid." and vtiger_profile2field.tabid=".$tabid;
	$result = $adb->query($query);
	$return_data=array();
    for($i=0; $i<$adb->num_rows($result); $i++)
    {
		$return_data[]=array($adb->query_result($result,$i,"fieldlabel"),$adb->query_result($result,$i,"visible"),$adb->query_result($result,$i,"uitype"),$adb->query_result($result,$i,"visible"),$adb->query_result($result,$i,"fieldid"),$adb->query_result($result,$i,"displaytype"));
	}	
	$log->debug("Exiting getProfile2FieldPermissionList method ...");
	return $return_data;
}

/** Function to getProfile2allfieldsListinsert value to profile2fieldPermissions table
  * @param $mod_array -- mod_array :: Type string
  * @param $profileid -- profileid :: Type integer
  * @returns $profilelist -- profilelist :: Type string
  */

function getProfile2AllFieldList($mod_array,$profileid)
{
	global $log;
     $log->debug("Entering getProfile2AllFieldList(".$mod_array.",".$profileid.") method ...");
     $log->info("in getProfile2AllFieldList vtiger_profile id is " .$profileid);

	global $adb;
	$profilelist=array();
	for($i=0;$i<count($mod_array);$i++)
	{
		$profilelist[key($mod_array)]=getProfile2FieldPermissionList(key($mod_array), $profileid);
		next($mod_array);
	}
	$log->debug("Exiting getProfile2AllFieldList method ...");
	return $profilelist;	
}

/** Function to getdefaultfield organisation list for a given module
  * @param $fld_module -- module name :: Type string
  * @returns $result -- string :: Type object
  */

//end of fn added by jeri

function getDefOrgFieldList($fld_module)
{
	global $log;
	$log->debug("Entering getDefOrgFieldList(".$fld_module.") method ...");
        $log->info("in getDefOrgFieldList ".$fld_module);

	global $adb;
	$tabid = getTabid($fld_module);
	
	$query = "select vtiger_def_org_field.visible,vtiger_field.* from vtiger_def_org_field inner join vtiger_field on vtiger_field.fieldid=vtiger_def_org_field.fieldid where vtiger_def_org_field.tabid=".$tabid;
	$result = $adb->query($query);
	$log->debug("Exiting getDefOrgFieldList method ...");
	return $result;
}

/** Function to getQuickCreate for a given tabid
  * @param $tabid -- tab id :: Type string
  * @param $actionid -- action id :: Type integer
  * @returns $QuickCreateForm -- QuickCreateForm :: Type boolean
  */

function getQuickCreate($tabid,$actionid)
{
	global $log;
	$log->debug("Entering getQuickCreate(".$tabid.",".$actionid.") method ...");
	$module=getTabModuleName($tabid);
	$actionname=getActionname($actionid);
        $QuickCreateForm= 'true';

	$perr=isPermitted($module,$actionname);
	if($perr = 'no')
	{
                $QuickCreateForm= 'false';
	}	
	$log->debug("Exiting getQuickCreate method ...");
	return $QuickCreateForm;

}

/** Function to getQuickCreate for a given tabid
  * @param $tabid -- tab id :: Type string
  * @param $actionid -- action id :: Type integer
  * @returns $QuickCreateForm -- QuickCreateForm :: Type boolean
  */

function ChangeStatus($status,$activityid,$activity_mode='')
 {
	global $log;
	$log->debug("Entering ChangeStatus(".$status.",".$activityid.",".$activity_mode."='') method ...");
        $log->info("in ChangeStatus ".$status. ' vtiger_activityid is  '.$activityid);

        global $adb;
        if ($activity_mode == 'Task')
        {
                $query = "Update vtiger_activity set status='".$status."' where activityid = ".$activityid;
        }
        elseif ($activity_mode == 'Events')
        {
                $query = "Update vtiger_activity set eventstatus='".$status."' where activityid = ".$activityid;
        }
	if($query) {
        	$adb->query($query);
	}
	$log->debug("Exiting ChangeStatus method ...");
 }

/** Function to set date values compatible to database (YY_MM_DD)
  * @param $value -- value :: Type string
  * @returns $insert_date -- insert_date :: Type string
  */

function getDBInsertDateValue($value)
{
	global $log;
	$log->debug("Entering getDBInsertDateValue(".$value.") method ...");
	global $current_user;
	$dat_fmt = $current_user->date_format;
	if($dat_fmt == '')
        {
                $dat_fmt = 'dd-mm-yyyy';
        }
	$insert_date='';
	if($dat_fmt == 'dd-mm-yyyy')
	{
		list($d,$m,$y) = split('-',$value);
	}
	elseif($dat_fmt == 'mm-dd-yyyy')
	{
		list($m,$d,$y) = split('-',$value);
	}
	elseif($dat_fmt == 'yyyy-mm-dd')
	{
		list($y,$m,$d) = split('-',$value);
	}
		
	if(!$y && !$m && !$d) {
		$insert_date = '';
	} else {
		$insert_date=$y.'-'.$m.'-'.$d;
	}
	$log->debug("Exiting getDBInsertDateValue method ...");
	return $insert_date;
}

/** Function to get unitprice for a given product id
  * @param $productid -- product id :: Type integer
  * @returns $up -- up :: Type string
  */

function getUnitPrice($productid)
{
	global $log,$current_user;
	$currencyid=fetchCurrency($current_user->id);
	$rate_symbol = getCurrencySymbolandCRate($currencyid);
	$rate = $rate_symbol['rate'];
	$log->debug("Entering getUnitPrice(".$productid.") method ...");
        $log->info("in getUnitPrice productid ".$productid);

        global $adb;
        $query = "select unit_price from vtiger_products where productid=".$productid;
        $result = $adb->query($query);
        $up = $adb->query_result($result,0,'unit_price');
	$up = convertFromDollar($up,$rate);
	$log->debug("Exiting getUnitPrice method ...");
        return $up;
}

/** Function to upload product image file 
  * @param $mode -- mode :: Type string
  * @param $id -- id :: Type integer
  * @returns $ret_array -- return array:: Type array
  */

function upload_product_image_file($mode,$id)
{
	global $log;
	$log->debug("Entering upload_product_image_file(".$mode.",".$id.") method ...");
	global $root_directory;
        $log->debug("Inside upload_product_image_file. The id is ".$id);
	$uploaddir = $root_directory ."/test/product/";

	$file_path_name = $_FILES['imagename']['name'];
	$file_name = basename($file_path_name);
	$file_name = $id.'_'.$file_name;
	$filetype= $_FILES['imagename']['type'];
	$filesize = $_FILES['imagename']['size'];

	$ret_array = Array();

	if($filesize > 0)
	{

		if(move_uploaded_file($_FILES["imagename"]["tmp_name"],$uploaddir.$file_name))
		{

			$upload_status = "yes";
			$ret_array["status"] = $upload_status;
			$ret_array["file_name"] = $file_name;
			

		}
		else
		{
			$errorCode =  $_FILES['imagename']['error'];
			$upload_status = "no";
			$ret_array["status"] = $upload_status;
			$ret_array["errorcode"] = $errorCode;
			
			
		}

	}
	else
	{
		$upload_status = "no";
                $ret_array["status"] = $upload_status;
	}
	$log->debug("Exiting upload_product_image_file method ...");
	return $ret_array;		

}

/** Function to upload product image file 
  * @param $id -- id :: Type integer
  * @param $deleted_array -- images to be deleted :: Type array
  * @returns $imagename -- imagelist:: Type array
  */

function getProductImageName($id,$deleted_array='')
{
	global $log;
	$log->debug("Entering getProductImageName(".$id.",".$deleted_array."='') method ...");
	global $adb;
	$image_array=array();	
	$query = "select imagename from vtiger_products where productid=".$id;
	$result = $adb->query($query);
	$image_name = $adb->query_result($result,0,"imagename");
	$image_array=explode("###",$image_name);
	$log->debug("Inside getProductImageName. The image_name is ".$image_name);
	if($deleted_array!='')
	{
		$resultant_image = array();
		$resultant_image=array_merge(array_diff($image_array,$deleted_array));
		$imagelists=implode('###',$resultant_image);	
		$log->debug("Exiting getProductImageName method ...");
		return	$imagelists;
	}
	else
	{
		$log->debug("Exiting getProductImageName method ...");
		return $image_name;	
	}
}

/** Function to get Contact images 
  * @param $id -- id :: Type integer
  * @returns $imagename -- imagename:: Type string
  */

function getContactImageName($id)
{
	global $log;
	$log->debug("Entering getContactImageName(".$id.") method ...");
        global $adb;
        $query = "select imagename from vtiger_contactdetails where contactid=".$id;
        $result = $adb->query($query);
        $image_name = $adb->query_result($result,0,"imagename");
        $log->debug("Inside getContactImageName. The image_name is ".$image_name);
	$log->debug("Exiting getContactImageName method ...");
        return $image_name;

}

/** Function to update sub total in inventory 
  * @param $module -- module name :: Type string
  * @param $tablename -- tablename :: Type string
  * @param $colname -- colname :: Type string
  * @param $colname1 -- coluname1 :: Type string
  * @param $entid_fld -- entity field :: Type string
  * @param $entid -- entid :: Type integer
  * @param $prod_total -- totalproduct :: Type integer
  */

function updateSubTotal($module,$tablename,$colname,$colname1,$entid_fld,$entid,$prod_total)
{
	global $log;
	$log->debug("Entering updateSubTotal(".$module.",".$tablename.",".$colname.",".$colname1.",".$entid_fld.",".$entid.",".$prod_total.") method ...");
        global $adb;
        //getting the subtotal
        $query = "select ".$colname.",".$colname1." from ".$tablename." where ".$entid_fld."=".$entid;
        $result1 = $adb->query($query);
        $subtot = $adb->query_result($result1,0,$colname);
        $subtot_upd = $subtot - $prod_total;

        $gdtot = $adb->query_result($result1,0,$colname1);
        $gdtot_upd = $gdtot - $prod_total;

        //updating the subtotal
        $sub_query = "update ".$tablename." set ".$colname."=".$subtot_upd.",".$colname1."=".$gdtot_upd." where ".$entid_fld."=".$entid;
        $adb->query($sub_query);
	$log->debug("Exiting updateSubTotal method ...");
}

/** Function to get Inventory Total 
  * @param $return_module -- return module :: Type string
  * @param $id -- entity id :: Type integer
  * @returns $total -- total:: Type integer
  */

function getInventoryTotal($return_module,$id)
{
	global $log;
	$log->debug("Entering getInventoryTotal(".$return_module.",".$id.") method ...");
	global $adb;
	if($return_module == "Potentials")
	{
		$query ="select vtiger_products.productname,vtiger_products.unit_price,vtiger_products.qtyinstock,vtiger_seproductsrel.* from vtiger_products inner join vtiger_seproductsrel on vtiger_seproductsrel.productid=vtiger_products.productid where crmid=".$id;
	}
	elseif($return_module == "Products")
	{
		$query="select vtiger_products.productid,vtiger_products.productname,vtiger_products.unit_price,vtiger_products.qtyinstock,vtiger_crmentity.* from vtiger_products inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_products.productid where vtiger_crmentity.deleted=0 and productid=".$id;
	}
	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	$total=0;
	for($i=1;$i<=$num_rows;$i++)
	{
		$unitprice=$adb->query_result($result,$i-1,'unit_price');
		$qty=$adb->query_result($result,$i-1,'quantity');
		$listprice=$adb->query_result($result,$i-1,'listprice');
		if($listprice == '')
		$listprice = $unitprice;
		if($qty =='')
		$qty = 1;
		$total = $total+($qty*$listprice);
	}
	$log->debug("Exiting getInventoryTotal method ...");
	return $total;
}

/** Function to update product quantity 
  * @param $product_id -- product id :: Type integer
  * @param $upd_qty -- quantity :: Type integer
  */

function updateProductQty($product_id, $upd_qty)
{
	global $log;
	$log->debug("Entering updateProductQty(".$product_id.",". $upd_qty.") method ...");
	global $adb;
	$query= "update vtiger_products set qtyinstock=".$upd_qty." where productid=".$product_id;
        $adb->query($query);
	$log->debug("Exiting updateProductQty method ...");

}

/** Function to get account information 
  * @param $parent_id -- parent id :: Type integer
  * @returns $accountid -- accountid:: Type integer
  */

function get_account_info($parent_id)
{
	global $log;
	$log->debug("Entering get_account_info(".$parent_id.") method ...");
        global $adb;
        $query = "select accountid from vtiger_potential where potentialid=".$parent_id;
        $result = $adb->query($query);
        $accountid=$adb->query_result($result,0,'accountid');
	$log->debug("Exiting get_account_info method ...");
        return $accountid;
}

/** Function to get quick create form fields 
  * @param $fieldlabel -- field label :: Type string
  * @param $uitype -- uitype :: Type integer
  * @param $fieldname -- field name :: Type string
  * @param $tabid -- tabid :: Type integer
  * @returns $return_field -- return field:: Type string
  */

//for Quickcreate-Form

function get_quickcreate_form($fieldlabel,$uitype,$fieldname,$tabid)
{
	global $log;
	$log->debug("Entering get_quickcreate_form(".$fieldlabel.",".$uitype.",".$fieldname.",".$tabid.") method ...");
	$return_field ='';
	switch($uitype)	
	{
		case 1: $return_field .=get_textField($fieldlabel,$fieldname);
			$log->debug("Exiting get_quickcreate_form method ...");
			return $return_field;
			break;
		case 2: $return_field .=get_textmanField($fieldlabel,$fieldname,$tabid);
			$log->debug("Exiting get_quickcreate_form method ...");
			return $return_field;
			break;
		case 6: $return_field .=get_textdateField($fieldlabel,$fieldname,$tabid);
			$log->debug("Exiting get_quickcreate_form method ...");
			return $return_field;
			break;
		case 11: $return_field .=get_textField($fieldlabel,$fieldname);
			$log->debug("Exiting get_quickcreate_form method ...");
			return $return_field;
			break;
		case 13: $return_field .=get_textField($fieldlabel,$fieldname);
			$log->debug("Exiting get_quickcreate_form method ...");
			return $return_field;	
			break;
		case 15: $return_field .=get_textcomboField($fieldlabel,$fieldname);
			$log->debug("Exiting get_quickcreate_form method ...");
			return $return_field;	
			break;
		case 16: $return_field .=get_textcomboField($fieldlabel,$fieldname);
			$log->debug("Exiting get_quickcreate_form method ...");
			return $return_field;	
			break;
		case 17: $return_field .=get_textwebField($fieldlabel,$fieldname);
			$log->debug("Exiting get_quickcreate_form method ...");
			return $return_field;
			break;
		case 19: $return_field .=get_textField($fieldlabel,$fieldname);
			$log->debug("Exiting get_quickcreate_form method ...");
			return $return_field;	
			break;
		case 22: $return_field .=get_textmanField($fieldlabel,$fieldname,$tabid);
			$log->debug("Exiting get_quickcreate_form method ...");
			return $return_field;
			break;
		case 23: $return_field .=get_textdateField($fieldlabel,$fieldname,$tabid);
			$log->debug("Exiting get_quickcreate_form method ...");
			return $return_field;
			break;
		case 50: $return_field .=get_textaccField($fieldlabel,$fieldname,$tabid);
			$log->debug("Exiting get_quickcreate_form method ...");
			return $return_field;
			break;
		case 51: $return_field .=get_textaccField($fieldlabel,$fieldname,$tabid);
			$log->debug("Exiting get_quickcreate_form method ...");
			return $return_field;
			break;
		case 55: $return_field .=get_textField($fieldlabel,$fieldname);
			$log->debug("Exiting get_quickcreate_form method ...");
			return $return_field;
			break;
		case 63: $return_field .=get_textdurationField($fieldlabel,$fieldname,$tabid);
			$log->debug("Exiting get_quickcreate_form method ...");
			return $return_field;
			break;
		case 71: $return_field .=get_textField($fieldlabel,$fieldname);
			$log->debug("Exiting get_quickcreate_form method ...");
			return $return_field;
			break;
	}
}	

/** Function to get quick create form fields 
  * @param $label -- field label :: Type string
  * @param $name -- field name :: Type string
  * @param $tid -- tabid :: Type integer
  * @returns $form_field -- return field:: Type string
  */

function get_textmanField($label,$name,$tid)
{
	global $log;
	$log->debug("Entering get_textmanField(".$label.",".$name.",".$tid.") method ...");
	$form_field='';
	if($tid == 9)
	{
		$form_field .='<td>';
		$form_field .= '<font color="red">*</font>';
		$form_field .= $label.':<br>';
		$form_field .='<input name="'.$name.'" id="QCK_T_'.$name.'" type="text" size="20" maxlength="" value=""></td>';
		$log->debug("Exiting get_textmanField method ...");
		return $form_field;	
	}
	if($tid == 16)
	{
		$form_field .='<td>';
		$form_field .= '<font color="red">*</font>';
		$form_field .= $label.':<br>';
		$form_field .='<input name="'.$name.'" id="QCK_E_'.$name.'" type="text" size="20" maxlength="" value=""></td>';
		$log->debug("Exiting get_textmanField method ...");
		return $form_field;	
	}
	else
	{
		$form_field .='<td>';
		$form_field .= '<font color="red">*</font>';
		$form_field .= $label.':<br>';
		$form_field .='<input name="'.$name.'" id="QCK_'.$name.'" type="text" size="20" maxlength="" value=""></td>';
		$log->debug("Exiting get_textmanField method ...");
		return $form_field;	
	}	
	
}	

/** Function to get textfield for website field  
  * @param $label -- field label :: Type string
  * @param $name -- field name :: Type string
  * @returns $form_field -- return field:: Type string
  */

function get_textwebField($label,$name)
{
	global $log;
	$log->debug("Entering get_textwebField(".$label.",".$name.") method ...");

	$form_field='';
	$form_field .='<td>';
	$form_field .= $label.':<br>http://<br>';
	$form_field .='<input name="'.$name.'" id="QCK_'.$name.'" type="text" size="20" maxlength="" value=""></td>';
	$log->debug("Exiting get_textwebField method ...");
	return $form_field;
	
}

/** Function to get textfield   
  * @param $label -- field label :: Type string
  * @param $name -- field name :: Type string
  * @returns $form_field -- return field:: Type string
  */

function get_textField($label,$name)
{
	global $log;
	$log->debug("Entering get_textField(".$label.",".$name.") method ...");	
	$form_field='';
	if($name == "amount")
	{
		$form_field .='<td>';
		$form_field .= $label.':(U.S Dollar:$)<br>';
		$form_field .='<input name="'.$name.'" id="QCK_'.$name.'" type="text" size="20" maxlength="" value=""></td>';
		$log->debug("Exiting get_textField method ...");
		return $form_field;
	}
	else
	{
		
		$form_field .='<td>';
		$form_field .= $label.':<br>';
		$form_field .='<input name="'.$name.'" id="QCK_'.$name.'" type="text" size="20" maxlength="" value=""></td>';
		$log->debug("Exiting get_textField method ...");
		return $form_field;
	}
	
}

/** Function to get account textfield   
  * @param $label -- field label :: Type string
  * @param $name -- field name :: Type string
  * @param $tid -- tabid :: Type integer
  * @returns $form_field -- return field:: Type string
  */

function get_textaccField($label,$name,$tid)
{
	global $log;
	$log->debug("Entering get_textaccField(".$label.",".$name.",".$tid.") method ...");
	
	global $app_strings;

	$form_field='';
	if($tid == 2)
	{
		$form_field .='<td>';
		$form_field .= '<font color="red">*</font>';
		$form_field .= $label.':<br>';
		$form_field .='<input name="account_name" type="text" size="20" maxlength="" id="account_name" value="" readonly><br>';
		$form_field .='<input name="account_id" id="QCK_'.$name.'" type="hidden" value="">&nbsp;<input title="'.$app_strings[LBL_CHANGE_BUTTON_TITLE].'" accessKey="'.$app_strings[LBL_CHANGE_BUTTON_KEY].'" type="button" tabindex="3" class="button" value="'.$app_strings[LBL_CHANGE_BUTTON_LABEL].'" name="btn1" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Accounts&action=Popup&popuptype=specific&form=EditView&form_submit=false","test","width=600,height=400,resizable=1,scrollbars=1");\'></td>';
		$log->debug("Exiting get_textaccField method ...");
		return $form_field;
	}
	else
	{	
		$form_field .='<td>';
		$form_field .= $label.':<br>';
		$form_field .='<input name="account_name" type="text" size="20" maxlength="" value="" readonly><br>';
		$form_field .='<input name="'.$name.'" id="QCK_'.$name.'" type="hidden" value="">&nbsp;<input title="'.$app_strings[LBL_CHANGE_BUTTON_TITLE].'" accessKey="'.$app_strings[LBL_CHANGE_BUTTON_KEY].'" type="button" tabindex="3" class="button" value="'.$app_strings[LBL_CHANGE_BUTTON_LABEL].'" name="btn1" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Accounts&action=Popup&popuptype=specific&form=EditView&form_submit=false","test","width=600,height=400,resizable=1,scrollbars=1");\'></td>';
		$log->debug("Exiting get_textaccField method ...");
		return $form_field;
	}	
		
}

/** Function to get combo field values   
  * @param $label -- field label :: Type string
  * @param $name -- field name :: Type string
  * @returns $form_field -- return field:: Type string
  */

function get_textcomboField($label,$name)
{
	global $log;
	$log->debug("Entering get_textcomboField(".$label.",".$name.") method ...");
	$form_field='';
	if($name == "sales_stage")
	{
		$comboFieldNames = Array('leadsource'=>'leadsource_dom'
                      ,'opportunity_type'=>'opportunity_type_dom'
                      ,'sales_stage'=>'sales_stage_dom');
		$comboFieldArray = getComboArray($comboFieldNames);
		$form_field .='<td>';
		$form_field .= '<font color="red">*</font>';
		$form_field .= $label.':<br>';
		$form_field .='<select name="'.$name.'">';
		$form_field .=get_select_options_with_id($comboFieldArray['sales_stage_dom'], "");
		$form_field .='</select></td>';
		$log->debug("Exiting get_textcomboField method ...");
		return $form_field;
		
	}
	if($name == "productcategory")
	{
		$comboFieldNames = Array('productcategory'=>'productcategory_dom');
		$comboFieldArray = getComboArray($comboFieldNames);
		$form_field .='<td>';
		$form_field .= $label.':<br>';
		$form_field .='<select name="'.$name.'">';
		$form_field .=get_select_options_with_id($comboFieldArray['productcategory_dom'], "");
		$form_field .='</select></td>';
		$log->debug("Exiting get_textcomboField method ...");
		return $form_field;	
		
	}
	if($name == "ticketpriorities")
	{
		$comboFieldNames = Array('ticketpriorities'=>'ticketpriorities_dom');
		$comboFieldArray = getComboArray($comboFieldNames);	
		$form_field .='<td>';
		$form_field .= $label.':<br>';
		$form_field .='<select name="'.$name.'">';
		$form_field .=get_select_options_with_id($comboFieldArray['ticketpriorities_dom'], "");
		$form_field .='</select></td>';
		$log->debug("Exiting get_textcomboField method ...");
		return $form_field;
	}
	if($name == "activitytype")
	{
		$comboFieldNames = Array('activitytype'=>'activitytype_dom',
			 'duration_minutes'=>'duration_minutes_dom');
		$comboFieldArray = getComboArray($comboFieldNames);
		$form_field .='<td>';
		$form_field .= $label.'<br>';
		$form_field .='<select name="'.$name.'">';
		$form_field .=get_select_options_with_id($comboFieldArray['activitytype_dom'], "");
		$form_field .='</select></td>';
		$log->debug("Exiting get_textcomboField method ...");
		return $form_field;
		
		
	}
        if($name == "eventstatus")
        {
                $comboFieldNames = Array('eventstatus'=>'eventstatus_dom');
                $comboFieldArray = getComboArray($comboFieldNames);
                $form_field .='<td>';
                $form_field .= $label.'<br>';
                $form_field .='<select name="'.$name.'">';
                $form_field .=get_select_options_with_id($comboFieldArray['eventstatus_dom'], "");
                $form_field .='</select></td>';
		$log->debug("Exiting get_textcomboField method ...");
                return $form_field;


        }
        if($name == "taskstatus")
        {
                $comboFieldNames = Array('taskstatus'=>'taskstatus_dom');
                $comboFieldArray = getComboArray($comboFieldNames);
                $form_field .='<td>';
                $form_field .= $label.'<br>';
                $form_field .='<select name="'.$name.'">';
                $form_field .=get_select_options_with_id($comboFieldArray['taskstatus_dom'], "");
                $form_field .='</select></td>';
		$log->debug("Exiting get_textcomboField method ...");
                return $form_field;
        }


	
}

/** Function to get date field    
  * @param $label -- field label :: Type string
  * @param $name -- field name :: Type string
  * @param $tid -- tabid :: Type integer
  * @returns $form_field -- return field:: Type string
  */


function get_textdateField($label,$name,$tid)
{
	global $log;
	$log->debug("Entering get_textdateField(".$label.",".$name.",".$tid.") method ...");
	global $theme;
	global $app_strings;
	global $current_user;

	$ntc_date_format = $app_strings['NTC_DATE_FORMAT'];
	$ntc_time_format = $app_strings['NTC_TIME_FORMAT'];
	
	$form_field='';
	$default_date_start = date('Y-m-d');
	$default_time_start = date('H:i');
	$dis_value=getNewDisplayDate();
	
	if($tid == 2)
	{
		$form_field .='<td>';
		$form_field .= '<font color="red">*</font>';
		$form_field .= $label.':<br>';
		$form_field .='<font size="1"><em old="ntc_date_format">('.$current_user->date_format.')</em></font><br>';
		$form_field .='<input name="'.$name.'"  size="12" maxlength="10" id="QCK_'.$name.'" type="text" value="">&nbsp';
	       	$form_field .='<img src="themes/'.$theme.'/images/calendar.gif" id="jscal_trigger"></td>';
		$log->debug("Exiting get_textdateField method ...");
		return $form_field;
			
	}
	if($tid == 9)
	{
		$form_field .='<td>';
		$form_field .= '<font color="red">*</font>';
		$form_field .= $label.':<br>';
		$form_field .='<input name="'.$name.'" id="QCK_T_'.$name.'" tabindex="2" type="text" size="10" maxlength="10" value="'.$default_date_start.'">&nbsp';
		$form_field.= '<img src="themes/'.$theme.'/images/calendar.gif" id="jscal_trigger_date_start">&nbsp';
		$form_field.='<input name="time_start" id="task_time_start" tabindex="1" type="text" size="5" maxlength="5" type="text" value="'.$default_time_start.'"><br><font size="1"><em old="ntc_date_format">('.$current_user->date_format.')</em></font>&nbsp<font size="1"><em>'.$ntc_time_format.'</em></font></td>';
		$log->debug("Exiting get_textdateField method ...");
		return $form_field;	
	}
	if($tid == 16)
	{
		$form_field .='<td>';
		$form_field .= '<font color="red">*</font>';
		$form_field .= $label.':<br>';
		$form_field .='<input name="'.$name.'" id="QCK_E_'.$name.'" tabindex="2" type="text" size="10" maxlength="10" value="'.$default_date_start.'">&nbsp';
		$form_field.= '<img src="themes/'.$theme.'/images/calendar.gif" id="jscal_trigger_event_date_start">&nbsp';
		$form_field.='<input name="time_start" id="event_time_start" tabindex="1" type="text" size="5" maxlength="5" type="text" value="'.$default_time_start.'"><br><font size="1"><em old="ntc_date_format">('.$current_user->date_format.')</em></font>&nbsp<font size="1"><em>'.$ntc_time_format.'</em></font></td>';
		$log->debug("Exiting get_textdateField method ...");
		return $form_field;	
	}
	
	else
	{
		$form_field .='<td>';
		$form_field .= '<font color="red">*</font>';
		$form_field .= $label.':<br>';
		$form_field .='<input name="'.$name.'" id="QCK_'.$name.'" type="text" size="10" maxlength="10" value="'.$default_date_start.'">&nbsp';
		$form_field.= '<img src="themes/'.$theme.'/images/calendar.gif" id="jscal_trigger">&nbsp';
		$form_field.='<input name="time_start" type="text" size="5" maxlength="5" type="text" value="'.$default_time_start.'"><br><font size="1"><em old="ntc_date_format">('.$current_user->date_format.')</em></font>&nbsp<font size="1"><em>'.$ntc_time_format.'</em></font></td>';
		$log->debug("Exiting get_textdateField method ...");
		return $form_field;	
	}
	
}

/** Function to get duration text field in activity  
  * @param $label -- field label :: Type string
  * @param $name -- field name :: Type string
  * @param $tid -- tabid :: Type integer
  * @returns $form_field -- return field:: Type string
  */

function get_textdurationField($label,$name,$tid)
{
	global $log;
	$log->debug("Entering get_textdurationField(".$label.",".$name.",".$tid.") method ...");
	$form_field='';
	if($tid == 16)
	{
		
		$comboFieldNames = Array('activitytype'=>'activitytype_dom',
			 'duration_minutes'=>'duration_minutes_dom');
		$comboFieldArray = getComboArray($comboFieldNames);
	
		$form_field .='<td>';
		$form_field .= $label.'<br>';
		$form_field .='<input name="'.$name.'" id="QCK_'.$name.'" type="text" size="2" value="1">&nbsp;';
		$form_field .='<select name="duration_minutes">';
		$form_field .=get_select_options_with_id($comboFieldArray['duration_minutes_dom'], "");
		$form_field .='</select><br>(hours/minutes)<br></td>';
		$log->debug("Exiting get_textdurationField method ...");
		return $form_field;
	}	
}

/** Function to get email text field  
  * @param $module -- module name :: Type name
  * @param $id -- entity id :: Type integer
  * @returns $hidden -- hidden:: Type string
  */

//Added to get the parents list as hidden for Emails -- 09-11-2005
function getEmailParentsList($module,$id)
{
	global $log;
	$log->debug("Entering getEmailParentsList(".$module.",".$id.") method ...");
        global $adb;
	if($module == 'Contacts')
		$focus = new Contacts();
	if($module == 'Leads')
		$focus = new Leads();
        
	$focus->retrieve_entity_info($id,$module);
        $fieldid = 0;
        $fieldname = 'email';
        if($focus->column_fields['email'] == '' && $focus->column_fields['yahooid'] != '')
                $fieldname = 'yahooid';

        $res = $adb->query("select * from vtiger_field where tabid = ".getTabid($module)." and fieldname='".$fieldname."'");
        $fieldid = $adb->query_result($res,0,'fieldid');

        $hidden .= '<input type="hidden" name="emailids" value="'.$id.'@'.$fieldid.'|">';
        $hidden .= '<input type="hidden" name="pmodule" value="'.$module.'">';

	$log->debug("Exiting getEmailParentsList method ...");
	return $hidden;
}

/** This Function returns the current status of the specified Purchase Order.
  * The following is the input parameter for the function
  *  $po_id --> Purchase Order Id, Type:Integer
  */
function getPoStatus($po_id)
{
	global $log;
	$log->debug("Entering getPoStatus(".$po_id.") method ...");

	global $log;
        $log->info("in getPoName ".$po_id);

        global $adb;
        $sql = "select postatus from vtiger_purchaseorder where purchaseorderid=".$po_id;
        $result = $adb->query($sql);
        $po_status = $adb->query_result($result,0,"postatus");
	$log->debug("Exiting getPoStatus method ...");
        return $po_status;
}

/** This Function adds the specified product quantity to the Product Quantity in Stock in the Warehouse 
  * The following is the input parameter for the function:
  *  $productId --> ProductId, Type:Integer
  *  $qty --> Quantity to be added, Type:Integer
  */
function addToProductStock($productId,$qty)
{
	global $log;
	$log->debug("Entering addToProductStock(".$productId.",".$qty.") method ...");
	global $adb;
	$qtyInStck=getProductQtyInStock($productId);
	$updQty=$qtyInStck + $qty;
	$sql = "UPDATE vtiger_products set qtyinstock=$updQty where productid=".$productId;
	$adb->query($sql);
	$log->debug("Exiting addToProductStock method ...");
	
}

/**	This Function adds the specified product quantity to the Product Quantity in Demand in the Warehouse 
  *	@param int $productId - ProductId
  *	@param int $qty - Quantity to be added
  */
function addToProductDemand($productId,$qty)
{
	global $log;
	$log->debug("Entering addToProductDemand(".$productId.",".$qty.") method ...");
	global $adb;
	$qtyInStck=getProductQtyInDemand($productId);
	$updQty=$qtyInStck + $qty;
	$sql = "UPDATE vtiger_products set qtyindemand=$updQty where productid=".$productId;
	$adb->query($sql);
	$log->debug("Exiting addToProductDemand method ...");
	
}

/**	This Function subtract the specified product quantity to the Product Quantity in Stock in the Warehouse 
  *	@param int $productId - ProductId
  *	@param int $qty - Quantity to be subtracted
  */
function deductFromProductStock($productId,$qty)
{
	global $log;
	$log->debug("Entering deductFromProductStock(".$productId.",".$qty.") method ...");
	global $adb;
	$qtyInStck=getProductQtyInStock($productId);
	$updQty=$qtyInStck - $qty;
	$sql = "UPDATE vtiger_products set qtyinstock=$updQty where productid=".$productId;
	$adb->query($sql);
	$log->debug("Exiting deductFromProductStock method ...");
	
}

/**	This Function subtract the specified product quantity to the Product Quantity in Demand in the Warehouse 
  *	@param int $productId - ProductId
  *	@param int $qty - Quantity to be subtract
  */
function deductFromProductDemand($productId,$qty)
{
	global $log;
	$log->debug("Entering deductFromProductDemand(".$productId.",".$qty.") method ...");
	global $adb;
	$qtyInStck=getProductQtyInDemand($productId);
	$updQty=$qtyInStck - $qty;
	$sql = "UPDATE vtiger_products set qtyindemand=$updQty where productid=".$productId;
	$adb->query($sql);
	$log->debug("Exiting deductFromProductDemand method ...");
	
}


/** This Function returns the current product quantity in stock.
  * The following is the input parameter for the function:
  *  $product_id --> ProductId, Type:Integer
  */
function getProductQtyInStock($product_id)
{
	global $log;
	$log->debug("Entering getProductQtyInStock(".$product_id.") method ...");
        global $adb;
        $query1 = "select qtyinstock from vtiger_products where productid=".$product_id;
        $result=$adb->query($query1);
        $qtyinstck= $adb->query_result($result,0,"qtyinstock");
	$log->debug("Exiting getProductQtyInStock method ...");
        return $qtyinstck;


}

/**	This Function returns the current product quantity in demand.
  *	@param int $product_id - ProductId
  *	@return int $qtyInDemand - Quantity in Demand of a product
  */
function getProductQtyInDemand($product_id)
{
	global $log;
	$log->debug("Entering getProductQtyInDemand(".$product_id.") method ...");
        global $adb;
        $query1 = "select qtyindemand from vtiger_products where productid=".$product_id;
        $result = $adb->query($query1);
        $qtyInDemand = $adb->query_result($result,0,"qtyindemand");
	$log->debug("Exiting getProductQtyInDemand method ...");
        return $qtyInDemand;
}

/** Function to seperate the Date and Time
  * This function accepts a sting with date and time and
  * returns an array of two elements.The first element
  * contains the date and the second one contains the time
  */
function getDateFromDateAndtime($date_time)
{
	global $log;
	$log->debug("Entering getDateFromDateAndtime(".$date_time.") method ...");
	$result = explode(" ",$date_time);
	$log->debug("Exiting getDateFromDateAndtime method ...");
	return $result;
}


/** Function to get header for block in edit/create and detailview  
  * @param $header_label -- header label :: Type string
  * @returns $output -- output:: Type string
  */

function getBlockTableHeader($header_label)
{
	global $log;
	$log->debug("Entering getBlockTableHeader(".$header_label.") method ...");
	global $mod_strings;
	$label = $mod_strings[$header_label];
	$output = $label;
	$log->debug("Exiting getBlockTableHeader method ...");
	return $output;

}



/**     Function to get the vtiger_table name from 'field' vtiger_table for the input vtiger_field based on the module
 *      @param  : string $module - current module value
 *      @param  : string $fieldname - vtiger_fieldname to which we want the vtiger_tablename
 *      @return : string $tablename - vtiger_tablename in which $fieldname is a column, which is retrieved from 'field' vtiger_table per $module basis
 */
function getTableNameForField($module,$fieldname)
{
	global $log;
	$log->debug("Entering getTableNameForField(".$module.",".$fieldname.") method ...");
	global $adb;
	$tabid = getTabid($module);

	$sql = "select tablename from vtiger_field where tabid=".$tabid." and columnname like '%".$fieldname."%'";
	$res = $adb->query($sql);

	$tablename = '';
	if($adb->num_rows($res) > 0)
	{
		$tablename = $adb->query_result($res,0,'tablename');
	}

	$log->debug("Exiting getTableNameForField method ...");
	return $tablename;
}

/** Function to get parent record owner  
  * @param $tabid -- tabid :: Type integer
  * @param $parModId -- parent module id :: Type integer
  * @param $record_id -- record id :: Type integer
  * @returns $parentRecOwner -- parentRecOwner:: Type integer
  */

function getParentRecordOwner($tabid,$parModId,$record_id)
{
	global $log;
	$log->debug("Entering getParentRecordOwner(".$tabid.",".$parModId.",".$record_id.") method ...");
	$parentRecOwner=Array();
	$parentTabName=getTabname($parModId);
	$relTabName=getTabname($tabid);
	$fn_name="get".$relTabName."Related".$parentTabName;
	$ent_id=$fn_name($record_id);
	if($ent_id != '')
	{
		$parentRecOwner=getRecordOwnerId($ent_id);	
	}
	$log->debug("Exiting getParentRecordOwner method ...");
	return $parentRecOwner;
}

/** Function to get potential related accounts   
  * @param $record_id -- record id :: Type integer
  * @returns $accountid -- accountid:: Type integer
  */

function getPotentialsRelatedAccounts($record_id)
{
	global $log;
	$log->debug("Entering getPotentialsRelatedAccounts(".$record_id.") method ...");
	global $adb;
	$query="select accountid from vtiger_potential where potentialid=".$record_id;
	$result=$adb->query($query);
	$accountid=$adb->query_result($result,0,'accountid');
	$log->debug("Exiting getPotentialsRelatedAccounts method ...");
	return $accountid;
}

/** Function to get email related accounts   
  * @param $record_id -- record id :: Type integer
  * @returns $accountid -- accountid:: Type integer
  */
function getEmailsRelatedAccounts($record_id)
{
	global $log;
	$log->debug("Entering getEmailsRelatedAccounts(".$record_id.") method ...");
	global $adb;
	$query = "select vtiger_seactivityrel.crmid from vtiger_seactivityrel inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_seactivityrel.crmid where vtiger_crmentity.setype='Accounts' and activityid=".$record_id;
	$result = $adb->query($query);
	$accountid=$adb->query_result($result,0,'crmid');
	$log->debug("Exiting getEmailsRelatedAccounts method ...");
	return $accountid;
}
/** Function to get email related Leads   
  * @param $record_id -- record id :: Type integer
  * @returns $leadid -- leadid:: Type integer
  */

function getEmailsRelatedLeads($record_id)
{
	global $log;
	$log->debug("Entering getEmailsRelatedLeads(".$record_id.") method ...");
	global $adb;
	$query = "select vtiger_seactivityrel.crmid from vtiger_seactivityrel inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_seactivityrel.crmid where vtiger_crmentity.setype='Leads' and activityid=".$record_id;
	$result = $adb->query($query);
	$leadid=$adb->query_result($result,0,'crmid');
	$log->debug("Exiting getEmailsRelatedLeads method ...");
	return $leadid;
}

/** Function to get HelpDesk related Accounts   
  * @param $record_id -- record id :: Type integer
  * @returns $accountid -- accountid:: Type integer
  */

function getHelpDeskRelatedAccounts($record_id)
{
	global $log;
	$log->debug("Entering getHelpDeskRelatedAccounts(".$record_id.") method ...");
	global $adb;
        $query="select parent_id from vtiger_troubletickets inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_troubletickets.parent_id where ticketid=".$record_id." and vtiger_crmentity.setype='Accounts'";
        $result=$adb->query($query);
        $accountid=$adb->query_result($result,0,'parent_id');
	$log->debug("Exiting getHelpDeskRelatedAccounts method ...");
        return $accountid;
}

/** Function to get Quotes related Accounts   
  * @param $record_id -- record id :: Type integer
  * @returns $accountid -- accountid:: Type integer
  */

function getQuotesRelatedAccounts($record_id)
{
	global $log;
	$log->debug("Entering getQuotesRelatedAccounts(".$record_id.") method ...");
	global $adb;
        $query="select accountid from vtiger_quotes where quoteid=".$record_id;
        $result=$adb->query($query);
        $accountid=$adb->query_result($result,0,'accountid');
	$log->debug("Exiting getQuotesRelatedAccounts method ...");
        return $accountid;
}

/** Function to get Quotes related Potentials   
  * @param $record_id -- record id :: Type integer
  * @returns $potid -- potid:: Type integer
  */

function getQuotesRelatedPotentials($record_id)
{
	global $log;
	$log->debug("Entering getQuotesRelatedPotentials(".$record_id.") method ...");
	global $adb;
        $query="select potentialid from vtiger_quotes where quoteid=".$record_id;
        $result=$adb->query($query);
        $potid=$adb->query_result($result,0,'potentialid');
	$log->debug("Exiting getQuotesRelatedPotentials method ...");
        return $potid;
}

/** Function to get Quotes related Potentials   
  * @param $record_id -- record id :: Type integer
  * @returns $accountid -- accountid:: Type integer
  */

function getSalesOrderRelatedAccounts($record_id)
{
	global $log;
	$log->debug("Entering getSalesOrderRelatedAccounts(".$record_id.") method ...");
	global $adb;
        $query="select accountid from vtiger_salesorder where salesorderid=".$record_id;
        $result=$adb->query($query);
        $accountid=$adb->query_result($result,0,'accountid');
	$log->debug("Exiting getSalesOrderRelatedAccounts method ...");
        return $accountid;
}

/** Function to get SalesOrder related Potentials   
  * @param $record_id -- record id :: Type integer
  * @returns $potid -- potid:: Type integer
  */

function getSalesOrderRelatedPotentials($record_id)
{
	global $log;
	$log->debug("Entering getSalesOrderRelatedPotentials(".$record_id.") method ...");
	global $adb;
        $query="select potentialid from vtiger_salesorder where salesorderid=".$record_id;
        $result=$adb->query($query);
        $potid=$adb->query_result($result,0,'potentialid');
	$log->debug("Exiting getSalesOrderRelatedPotentials method ...");
        return $potid;
}
/** Function to get SalesOrder related Quotes   
  * @param $record_id -- record id :: Type integer
  * @returns $qtid -- qtid:: Type integer
  */

function getSalesOrderRelatedQuotes($record_id)
{
	global $log;
	$log->debug("Entering getSalesOrderRelatedQuotes(".$record_id.") method ...");
	global $adb;
        $query="select quoteid from vtiger_salesorder where salesorderid=".$record_id;
        $result=$adb->query($query);
        $qtid=$adb->query_result($result,0,'quoteid');
	$log->debug("Exiting getSalesOrderRelatedQuotes method ...");
        return $qtid;
}

/** Function to get Invoice related Accounts   
  * @param $record_id -- record id :: Type integer
  * @returns $accountid -- accountid:: Type integer
  */

function getInvoiceRelatedAccounts($record_id)
{
	global $log;
	$log->debug("Entering getInvoiceRelatedAccounts(".$record_id.") method ...");
	global $adb;
        $query="select accountid from vtiger_invoice where invoiceid=".$record_id;
        $result=$adb->query($query);
        $accountid=$adb->query_result($result,0,'accountid');
	$log->debug("Exiting getInvoiceRelatedAccounts method ...");
        return $accountid;
}
/** Function to get Invoice related SalesOrder   
  * @param $record_id -- record id :: Type integer
  * @returns $soid -- soid:: Type integer
  */

function getInvoiceRelatedSalesOrder($record_id)
{
	global $log;
	$log->debug("Entering getInvoiceRelatedSalesOrder(".$record_id.") method ...");
	global $adb;
        $query="select salesorderid from vtiger_invoice where invoiceid=".$record_id;
        $result=$adb->query($query);
        $soid=$adb->query_result($result,0,'salesorderid');
	$log->debug("Exiting getInvoiceRelatedSalesOrder method ...");
        return $soid;
}


/** Function to get Days and Dates in between the dates specified
        * Portions created by vtiger are Copyright (C) vtiger.
        * All Rights Reserved.
        * Contributor(s): ______________________________________..
 */
function get_days_n_dates($st,$en)
{
	global $log;
	$log->debug("Entering get_days_n_dates(".$st.",".$en.") method ...");
        $stdate_arr=explode("-",$st);
        $endate_arr=explode("-",$en);

        $dateDiff = mktime(0,0,0,$endate_arr[1],$endate_arr[2],$endate_arr[0]) - mktime(0,0,0,$stdate_arr[1],$stdate_arr[2],$stdate_arr[0]);//to get  dates difference

        $days   =  floor($dateDiff/60/60/24)+1; //to calculate no of. days
        for($i=0;$i<$days;$i++)
        {
                $day_date[] = date("Y-m-d",mktime(0,0,0,date("$stdate_arr[1]"),(date("$stdate_arr[2]")+($i)),date("$stdate_arr[0]")));
        }
        if(!isset($day_date))
                $day_date=0;
        $nodays_dates=array($days,$day_date);
	$log->debug("Exiting get_days_n_dates method ...");
        return $nodays_dates; //passing no of days , days in between the days
}//function end


/** Function to get the start and End Dates based upon the period which we give
        * Portions created by vtiger are Copyright (C) vtiger.
        * All Rights Reserved.
        * Contributor(s): ______________________________________..
 */
function start_end_dates($period)
{
	global $log;
	$log->debug("Entering start_end_dates(".$period.") method ...");
        $st_thisweek= date("Y-m-d",mktime(0,0,0,date("n"),(date("j")-date("w")),date("Y")));
        if($period=="tweek")
        {
                $st_date= date("Y-m-d",mktime(0,0,0,date("n"),(date("j")-date("w")),date("Y")));
                $end_date = date("Y-m-d",mktime(0,0,0,date("n"),(date("j")-1),date("Y")));
                $st_week= date("w",mktime(0,0,0,date("n"),date("j"),date("Y")));
                if($st_week==0)
                {
                        $start_week=explode("-",$st_thisweek);
                        $st_date = date("Y-m-d",mktime(0,0,0,date("$start_week[1]"),(date("$start_week[2]")-7),date("$start_week[0]")));
                        $end_date = date("Y-m-d",mktime(0,0,0,date("$start_week[1]"),(date("$start_week[2]")-1),date("$start_week[0]")));
                }
                $period_type="week";
                $width="360";
        }
        else if($period=="lweek")
        {
                $start_week=explode("-",$st_thisweek);
                $st_date = date("Y-m-d",mktime(0,0,0,date("$start_week[1]"),(date("$start_week[2]")-7),date("$start_week[0]")));
                $end_date = date("Y-m-d",mktime(0,0,0,date("$start_week[1]"),(date("$start_week[2]")-1),date("$start_week[0]")));
                $st_week= date("w",mktime(0,0,0,date("n"),date("j"),date("Y")));
                if($st_week==0)
                {
                        $start_week=explode("-",$st_thisweek);
                        $st_date = date("Y-m-d",mktime(0,0,0,date("$start_week[1]"),(date("$start_week[2]")-14),date("$start_week[0]")));
                        $end_date = date("Y-m-d",mktime(0,0,0,date("$start_week[1]"),(date("$start_week[2]")-8),date("$start_week[0]")));
                }
                $period_type="week";
                $width="360";
        }
        else if($period=="tmon")
        {
		$period_type="month";
		$width="840";
		$st_date = date("Y-m-d",mktime(0, 0, 0, date("m"), "01",   date("Y")));	
		$end_date = date("Y-m-t");

        }
        else if($period=="lmon")
        {
                $st_date=date("Y-m-d",mktime(0,0,0,date("n")-1,date("1"),date("Y")));
                $end_date = date("Y-m-d",mktime(0, 0, 1, date("n"), 0,date("Y")));
                $period_type="month";
                $start_month=date("d",mktime(0,0,0,date("n"),date("j"),date("Y")));
                if($start_month==1)
                {
                        $st_date=date("Y-m-d",mktime(0,0,0,date("n")-2,date("1"),date("Y")));
                        $end_date = date("Y-m-d",mktime(0, 0, 1, date("n")-1, 0,date("Y")));
                }

                $width="840";
        }
        else
        {
                $curr_date=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
                $today_date=explode("-",$curr_date);
                $lastday_date=date("Y-m-d",mktime(0,0,0,date("$today_date[1]"),date("$today_date[2]")-1,date("$today_date[0]")));
                $st_date=$lastday_date;
                $end_date=$lastday_date;
                $period_type="yday";
		 $width="250";
        }
        if($period_type=="yday")
                $height="160";
        else
                $height="250";
        $datevalues=array($st_date,$end_date,$period_type,$width,$height);
	$log->debug("Exiting start_end_dates method ...");
        return $datevalues;
}//function ends


/**   Function to get the Graph and vtiger_table format for a particular date
        based upon the period
        * Portions created by vtiger are Copyright (C) vtiger.
        * All Rights Reserved.
        * Contributor(s): ______________________________________..
 */
function Graph_n_table_format($period_type,$date_value)
{
	global $log;
	$log->debug("Entering Graph_n_table_format(".$period_type.",".$date_value.") method ...");
        $date_val=explode("-",$date_value);
        if($period_type=="month")   //to get the vtiger_table format dates
        {
                $table_format=date("j",mktime(0,0,0,date($date_val[1]),(date($date_val[2])),date($date_val[0])));
                $graph_format=date("D",mktime(0,0,0,date($date_val[1]),(date($date_val[2])),date($date_val[0])));
        }
        else if($period_type=="week")
        {
                $table_format=date("d/m",mktime(0,0,0,date($date_val[1]),(date($date_val[2])),date($date_val[0])));
                $graph_format=date("D",mktime(0,0,0,date($date_val[1]),(date($date_val[2])),date($date_val[0])));
        }
        else if($period_type=="yday")
        {
                $table_format=date("j",mktime(0,0,0,date($date_val[1]),(date($date_val[2])),date($date_val[0])));
                $graph_format=$table_format;
        }
        $values=array($graph_format,$table_format);
	$log->debug("Exiting Graph_n_table_format method ...");
        return $values;
}

/** Function to get image count for a given product   
  * @param $id -- product id :: Type integer
  * @returns count -- count:: Type integer
  */

function getImageCount($id)
{
	global $log;
	$log->debug("Entering getImageCount(".$id.") method ...");
	global $adb;
	$image_lists=array();
	$query="select imagename from vtiger_products where productid=".$id;
	$result=$adb->query($query);
	$imagename=$adb->query_result($result,0,'imagename');
	$image_lists=explode("###",$imagename);
	$log->debug("Exiting getImageCount method ...");
	return count($image_lists);

}

/** Function to get user image for a given user   
  * @param $id -- user id :: Type integer
  * @returns $image_name -- image name:: Type string
  */

function getUserImageName($id)
{
	global $log;
	$log->debug("Entering getUserImageName(".$id.") method ...");
	global $adb;
	$query = "select imagename from vtiger_users where id=".$id;
	$result = $adb->query($query);
	$image_name = $adb->query_result($result,0,"imagename");
	$log->debug("Inside getUserImageName. The image_name is ".$image_name);
	$log->debug("Exiting getUserImageName method ...");
	return $image_name;

}

/** Function to get all user images for displaying it in listview   
  * @returns $image_name -- image name:: Type array
  */

function getUserImageNames()
{
	global $log;
	$log->debug("Entering getUserImageNames() method ...");
	global $adb;
	$query = "select imagename from vtiger_users where deleted=0";
	$result = $adb->query($query);
	$image_name=array();
	for($i=0;$i<$adb->num_rows($result);$i++)
	{
		if($adb->query_result($result,$i,"imagename")!='')
			$image_name[] = $adb->query_result($result,$i,"imagename");
	}
	$log->debug("Inside getUserImageNames.");
	if(count($image_name) > 0)
	{
		$log->debug("Exiting getUserImageNames method ...");
		return $image_name;
	}
}

/**   Function to remove the script tag in the contents
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function strip_selected_tags($text, $tags = array())
{
    $args = func_get_args();
    $text = array_shift($args);
    $tags = func_num_args() > 2 ? array_diff($args,array($text))  : (array)$tags;
    foreach ($tags as $tag){
        if(preg_match_all('/<'.$tag.'[^>]*>(.*)<\/'.$tag.'>/iU', $text, $found)){
            $text = str_replace($found[0],$found[1],$text);
        }
    }

    return $text;
}

/** Function to check whether user has opted for internal mailer
  * @returns $int_mailer -- int mailer:: Type boolean
    */
function useInternalMailer() {
	global $current_user,$adb;
	return $adb->query_result($adb->query("select int_mailer from vtiger_mail_accounts where user_id='".$current_user->id."'"),0,"int_mailer");
}

/**
* the function is like unescape in javascript
* added by dingjianting on 2006-10-1 for picklist editor
*/
function utf8RawUrlDecode ($source) {
    $decodedStr = "";
    $pos = 0;
    $len = strlen ($source);
    while ($pos < $len) {
        $charAt = substr ($source, $pos, 1);
        if ($charAt == '%') {
            $pos++;
            $charAt = substr ($source, $pos, 1);
            if ($charAt == 'u') {
                // we got a unicode character
                $pos++;
                $unicodeHexVal = substr ($source, $pos, 4);
                $unicode = hexdec ($unicodeHexVal);
                $entity = "&#". $unicode . ';';
                $decodedStr .= utf8_encode ($entity);
                $pos += 4;
            }
            else {
                // we have an escaped ascii character
                $hexVal = substr ($source, $pos, 2);
                $decodedStr .= chr (hexdec ($hexVal));
                $pos += 2;
            }
        } else {
            $decodedStr .= $charAt;
            $pos++;
        }
    }
    return $decodedStr;
}
?>
