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
  
function return_name(&$row, $first_column, $last_column)
{
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

	return $full_name;
}

//login utils
function get_languages()
{
	global $languages;
	return $languages;
}

//seems not used
function get_language_display($key)
{
	global $languages;
	return $languages[$key];
}

function get_assigned_user_name(&$assigned_user_id)
{
	$user_list = &get_user_array(false,"");
	if(isset($user_list[$assigned_user_id]))
	{
		return $user_list[$assigned_user_id];
	}

	return "";
}

//used in module file
function get_user_array($add_blank=true, $status="Active", $assigned_user="",$private="")
{
	global $log;
	global $current_user;
	if(isset($current_user) && $current_user->id != '')
	{
		require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
		require('user_privileges/user_privileges_'.$current_user->id.'.php');
	}
	static $user_array = null;
	global $log;
	$module=$_REQUEST['module'];

	if($user_array == null)
	{
		require_once('include/database/PearDatabase.php');
		$db = new PearDatabase();
		$temp_result = Array();
		// Including deleted users for now.
		if (empty($status)) {
				$query = "SELECT id, user_name from users";
		}
		else {
				if($private == 'private')
				{
					$log->debug("Sharing is Private. Only the current user should be listed");
					$query = "select id as id,user_name as user_name from users where id=".$current_user->id." and status='Active' union select user2role.userid as id,users.user_name as user_name from user2role inner join users on users.id=user2role.userid inner join role on role.roleid=user2role.roleid where role.parentrole like '".$current_user_parent_role_seq."::%' and status='Active' union select shareduserid as id,users.user_name as user_name from tmp_read_user_sharing_per inner join users on users.id=tmp_read_user_sharing_per.shareduserid where status='Active' and tmp_read_user_sharing_per.userid=".$current_user->id." and tmp_read_user_sharing_per.tabid=".getTabid($module);	
						
				}
				else
				{
					$log->debug("Sharing is Public. All users should be listed");
					$query = "SELECT id, user_name from users WHERE status='$status'";
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

	return $user_array;
}

function clean($string, $maxLength)
{
	$string = substr($string, 0, $maxLength);
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
	safe_map_named($request_var, $focus, $request_var, $always_copy);
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
	if (isset($_REQUEST[$request_var]) && ($always_copy || is_null($focus->$member_var))) {
		$log->debug("safe map named called assigning '{$_REQUEST[$request_var]}' to $member_var");
		$focus->$member_var = $_REQUEST[$request_var];
	}
}

/** This function retrieves an application language file and returns the array of strings included in the $app_list_strings var.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 * If you are using the current language, do not call this function unless you are loading it for the first time */

function return_app_list_strings_language($language)
{
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
		return null;
	}


	$return_value = $app_list_strings;
	$app_list_strings = $temp_app_list_strings;

	return $return_value;
}

/** This function retrieves an application language file and returns the array of strings included.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 * If you are using the current language, do not call this function unless you are loading it for the first time */
function return_application_language($language)
{
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

	return $return_value;
}

/** This function retrieves a module's language file and returns the array of strings included.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 * If you are in the current module, do not call this function unless you are loading it for the first time */
function return_module_language($language, $module)
{
	global $mod_strings, $default_language, $log, $currentModule, $translation_string_prefix;

	if($currentModule == $module && isset($mod_strings) && $mod_strings != null)
	{
		// We should have already loaded the array.  return the current one.
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

	return $return_value;
}

/** This function retrieves an application language file and returns the array of strings included in the $mod_list_strings var.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 * If you are using the current language, do not call this function unless you are loading it for the first time */
function return_mod_list_strings_language($language,$module)
{
	global $mod_list_strings, $default_language, $log, $currentModule,$translation_string_prefix;

	$language_used = $language;
	$temp_mod_list_strings = $mod_list_strings;

	if($currentModule == $module && isset($mod_list_strings) && $mod_list_strings != null)
	{
		return $mod_list_strings;
	}

	@include("modules/$module/language/$language.lang.php");

	if(!isset($mod_list_strings))
	{
		$log->fatal("Unable to load the application list language file for the selected language($language) or the default language($default_language)");
		return null;
	}

	$return_value = $mod_list_strings;
	$mod_list_strings = $temp_mod_list_strings;

	return $return_value;
}

/** This function retrieves a theme's language file and returns the array of strings included.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function return_theme_language($language, $theme)
{
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

	return $theme_strings;
}



/** If the session variable is defined and is not equal to "" then return it.  Otherwise, return the default value.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
*/
function return_session_value_or_default($varname, $default)
{
	if(isset($_SESSION[$varname]) && $_SESSION[$varname] != "")
	{
		return $_SESSION[$varname];
	}

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
	if($SQL_name == null)
	{
		$SQL_name = $variable_name;
	}

	if(isset($_REQUEST[$variable_name]) && $_REQUEST[$variable_name] != "")
	{
		array_push($where_clauses, "$SQL_name like '$_REQUEST[$variable_name]%'");
	}
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
	$where = "";
	foreach($where_clauses as $clause)
	{
		if($where != "")
		$where .= " and ";
		$where .= $clause;
	}

	$log->info("Here is the where clause for the list view: $where");
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

	return $guid;

}

function create_guid_section($characters)
{
	$return = "";
	for($i=0; $i<$characters; $i++)
	{
		$return .= sprintf("%x", rand(0,15));
	}
	return $return;
}

function ensure_length(&$string, $length)
{
	$strlen = strlen($string);
	if($strlen < $length)
	{
		$string = str_pad($string,$length,"0");
	}
	else if($strlen > $length)
	{
		$string = substr($string, 0, $length);
	}
}

function microtime_diff($a, $b) {
   list($a_dec, $a_sec) = explode(" ", $a);
   list($b_dec, $b_sec) = explode(" ", $b);
   return $b_sec - $a_sec + $b_dec - $a_dec;
}


/**
 * Return the display name for a theme if it exists.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_theme_display($theme) {
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

	return $return_theme_value;
}

/**
 * Return an array of directory names.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_themes() {
   if ($dir = @opendir("./themes")) {
		while (($file = readdir($dir)) !== false) {
           if ($file != ".." && $file != "." && $file != "CVS" && $file != "Attic" && $file != "akodarkgem" && $file != "bushtree" && $file != "coolblue" && $file != "Amazon" && $file != "busthree" && $file != "Aqua" && $file != "nature" && $file != "orange") {
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
   return $filelist;
}



/**
 * Create javascript to clear values of all elements in a form.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_clear_form_js () {
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

return $the_script;
}

/**
 * Create javascript to set the cursor focus to specific field in a form
 * when the screen is rendered.  The field name is currently hardcoded into the
 * the function.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_set_focus_js () {
//TODO Clint 5/20 - Make this function more generic so that it can take in the target form and field names as variables
$the_script = <<<EOQ
<script type="text/javascript" language="JavaScript">
<!-- Begin
function set_focus() {
	if (document.forms.length > 0) {
		for (i = 0; i < document.forms.length; i++) {
			for (j = 0; j < document.forms[i].elements.length; j++) {
				var field = document.forms[i].elements[j];
				if ((field.type == "text" || field.type == "textarea" || field.type == "password") &&
						!field.disabled && (field.name == "first_name" || field.name == "name")) {
					field.focus();
                    if (field.type == "text") {
                        field.select();
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
   return $marray;
}


function set_default_config(&$defaults)
{

	foreach ($defaults as $name=>$value)
	{
		if ( ! isset($GLOBALS[$name]) )
		{
			$GLOBALS[$name] = $value;
		}
	}
}

$toHtml = array(
        '"' => '&quot;',
        '<' => '&lt;',
        '>' => '&gt;',
        '& ' => '&amp; ',
        "'" =>  '&#039;',
);

function to_html($string, $encode=true){
        global $toHtml;
        if($encode && is_string($string)){//$string = htmlentities($string, ENT_QUOTES);
        $string = str_replace(array_keys($toHtml), array_values($toHtml), $string);
        }
        return $string;
}


//it seems the fun ction is not used
function get_assigned_user_or_group_name($id,$module)
{
	global $adb;

	//it might so happen that an entity is assigned to a group but at that time the group has no members. even in this case, the query should return a valid value and not just blank

  if($module == 'Leads')
  {

   $sql="select (case when (user_name is null) then  (leadgrouprelation.groupname) else (user_name) end) as name from leads left join users on users.id= assigned_user_id left join leadgrouprelation on leadgrouprelation.leadid=leads.id where leads.deleted=0 and leads.id='". $id ."'";
   
  }
  else if($module == 'Tasks')
  {
       $sql="select (case when (user_name is null) then  (taskgrouprelation.groupname) else (user_name) end) as name from tasks left join users on users.id= assigned_user_id left join taskgrouprelation on taskgrouprelation.taskid=tasks.id where tasks.deleted=0 and tasks.id='". $id ."'";
  }
  else if($module == 'Calls')
  {
       $sql="select (case when (user_name is null) then  (callgrouprelation.groupname) else (user_name) end) as name from calls left join users on users.id= assigned_user_id left join callgrouprelation on callgrouprelation.callid=calls.id where calls.deleted=0 and calls.id='". $id ."'";
  }

	$result = $adb->query($sql);
	$tempval = $adb->fetch_row($result);
	return $tempval[0];
}

function getTabname($tabid)
{
	global $log;
        $log->info("tab id is ".$tabid);
        global $adb;
	$sql = "select tablabel from tab where tabid='".$tabid."'";
	$result = $adb->query($sql);
	$tabname=  $adb->query_result($result,0,"tablabel");
	return $tabname;

}

function getTabModuleName($tabid)
{
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
        $sql = "select name from tab where tabid='".$tabid."'";
        $result = $adb->query($sql);
        $tabname=  $adb->query_result($result,0,"name");
	}
        return $tabname;
}


function getColumnFields($module)
{
	global $log;
$log->info("in getColumnFields ".$module);
	global $adb;
	$column_fld = Array();
        $tabid = getTabid($module);
	$sql = "select * from field where tabid=".$tabid;
        $result = $adb->query($sql);
        $noofrows = $adb->num_rows($result);
	for($i=0; $i<$noofrows; $i++)
	{
		$fieldname = $adb->query_result($result,$i,"fieldname");
		$column_fld[$fieldname] = ''; 
	}
	return $column_fld;	
}

function getUserEmail($userid)
{
global $log;
$log->info("in getUserEmail ".$userid);

        global $adb;
        if($userid != '')
        {
                $sql = "select email1 from users where id=".$userid;
                $result = $adb->query($sql);
                $email = $adb->query_result($result,0,"email1");
        }
        return $email;
}		
//outlook security
function getUserId_Ol($username)
{
global $log;
$log->info("in getUserId_Ol ".$username);

	global $adb;
	$sql = "select id from users where user_name='".$username."'";
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
	return $user_id;
}	
//outlook security

function getActionid($action)
{
	global $log;
	global $adb;
	$log->info("get Actionid ".$action);

	$actionid = '';
	$query="select * from actionmapping where actionname='".$action."'";
        $result =$adb->query($query);
        $actionid=$adb->query_result($result,0,'actionid');
	$log->info("action id selected is ".$actionid );
	return $actionid;
}



function getActionname($actionid)
{
	global $log;
	global $adb;

	$actionname='';
	$query="select * from actionmapping where actionid=".$actionid;
	$result =$adb->query($query);
	$actionname=$adb->query_result($result,0,"actionname");
	return $actionname;
}


function getUserId($record)
{
	global $log;
        $log->info("in getUserId ".$record);

	global $adb;
        $user_id=$adb->query_result($adb->query("select * from crmentity where crmid = ".$record),0,'smownerid');
	return $user_id;	
}

function getRecordOwnerId($record)
{

	global $adb;
	$ownerArr=Array();
	$query="select * from crmentity where crmid = ".$record;
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
			$query1="select groups.groupid from leadgrouprelation inner join groups on groups.groupname = leadgrouprelation.groupname where leadid=".$record;
		}
		elseif($module == 'Activities' || $module == 'Emails')
		{

			$query1="select groups.groupid from activitygrouprelation inner join groups on groups.groupname = activitygrouprelation.groupname where activityid=".$record;
		}
		elseif($module == 'HelpDesk')
		{
			$query1="select groups.groupid from ticketgrouprelation inner join groups on groups.groupname = ticketgrouprelation.groupname where ticketid=".$record;
		}
		elseif($module == 'Accounts')
		{
			$query1="select groups.groupid from accountgrouprelation inner join groups on groups.groupname = accountgrouprelation.groupname where accountid=".$record;
		}
		elseif($module == 'Contacts')
		{
			$query1="select groups.groupid from contactgrouprelation inner join groups on groups.groupname = contactgrouprelation.groupname where contactid=".$record;
		}
		elseif($module == 'Potentials')
		{
			$query1="select groups.groupid from potentialgrouprelation inner join groups on groups.groupname = potentialgrouprelation.groupname where potentialid=".$record;
		}
		elseif($module == 'Quotes')
		{
			$query1="select groups.groupid from quotegrouprelation inner join groups on groups.groupname = quotegrouprelation.groupname where quoteid=".$record;
		}
		elseif($module == 'PurchaseOrder')
		{
			$query1="select groups.groupid from pogrouprelation inner join groups on groups.groupname = pogrouprelation.groupname where purchaseorderid=".$record;
		}
		elseif($module == 'SalesOrder')
		{
			$query1="select groups.groupid from sogrouprelation inner join groups on groups.groupname = sogrouprelation.groupname where salesorderid=".$record;
		}
		elseif($module == 'Invoice')
		{
			$query1="select groups.groupid from invoicegrouprelation inner join groups on groups.groupname = invoicegrouprelation.groupname where invoiceid=".$record;
		}

		$result1=$adb->query($query1);
		$groupid=$adb->query_result($result1,0,'groupid');
		$ownerArr['Groups']=$groupid;

	}	
	return $ownerArr;

}


function insertProfile2field($profileid)
{
	 global $log;
        $log->info("in insertProfile2field ".$profileid);

	global $adb;
	$adb->database->SetFetchMode(ADODB_FETCH_ASSOC); 
	$fld_result = $adb->query("select * from field where generatedtype=1 and displaytype in (1,2)");
        $num_rows = $adb->num_rows($fld_result);
        for($i=0; $i<$num_rows; $i++)
        {
                 $tab_id = $adb->query_result($fld_result,$i,'tabid');
                 $field_id = $adb->query_result($fld_result,$i,'fieldid');
                 $adb->query("insert into profile2field values (".$profileid.",".$tab_id.",".$field_id.",0,1)");
	}
}

function insert_def_org_field()
{
	global $adb;
	$adb->database->SetFetchMode(ADODB_FETCH_ASSOC); 
	$fld_result = $adb->query("select * from field where generatedtype=1 and displaytype in (1,2)");
        $num_rows = $adb->num_rows($fld_result);
        for($i=0; $i<$num_rows; $i++)
        {
                 $tab_id = $adb->query_result($fld_result,$i,'tabid');
                 $field_id = $adb->query_result($fld_result,$i,'fieldid');
                 $adb->query("insert into def_org_field values (".$tab_id.",".$field_id.",0,1)");
	}
}

function getProfile2FieldList($fld_module, $profileid)
{
	 global $log;
        $log->info("in getProfile2FieldList ".$fld_module. ' profile id is  '.$profileid);

	global $adb;
	$tabid = getTabid($fld_module);
	
	$query = "select profile2field.visible,field.* from profile2field inner join field on field.fieldid=profile2field.fieldid where profile2field.profileid=".$profileid." and profile2field.tabid=".$tabid;
	$result = $adb->query($query);
	return $result;
}

//added by jeri

function getProfile2FieldPermissionList($fld_module, $profileid)
{
	global $log;
    $log->info("in getProfile2FieldList ".$fld_module. ' profile id is  '.$profileid);

	global $adb;
	$tabid = getTabid($fld_module);
	
	$query = "select profile2field.visible,field.* from profile2field inner join field on field.fieldid=profile2field.fieldid where profile2field.profileid=".$profileid." and profile2field.tabid=".$tabid;
	$result = $adb->query($query);
	$return_data=array();
    for($i=0; $i<$adb->num_rows($result); $i++)
    {
		$return_data[]=array($adb->query_result($result,$i,"fieldlabel"),$adb->query_result($result,$i,"visible"),$adb->query_result($result,$i,"uitype"),$adb->query_result($result,$i,"visible"),$adb->query_result($result,$i,"fieldid"));
	}	
	return $return_data;
}

function getProfile2AllFieldList($mod_array,$profileid)
{
	global $log;
    $log->info("in getProfile2AllFieldList profile id is " .$profileid);

	global $adb;
	$profilelist=array();
	for($i=0;$i<count($mod_array);$i++)
	{
		$profilelist[key($mod_array)]=getProfile2FieldPermissionList(key($mod_array), $profileid);
		next($mod_array);
	}
	return $profilelist;	
}

//end of fn added by jeri

function getDefOrgFieldList($fld_module)
{
	global $log;
        $log->info("in getDefOrgFieldList ".$fld_module);

	global $adb;
	$tabid = getTabid($fld_module);
	
	$query = "select def_org_field.visible,field.* from def_org_field inner join field on field.fieldid=def_org_field.fieldid where def_org_field.tabid=".$tabid;
	$result = $adb->query($query);
	return $result;
}

function getQuickCreate($tabid,$actionid)
{
	$module=getTabModuleName($tabid);
	$actionname=getActionname($actionid);
        $QuickCreateForm= 'true';

	$perr=isPermitted($module,$actionname);
	if($perr = 'no')
	{
                $QuickCreateForm= 'false';
	}	
	return $QuickCreateForm;

}
function ChangeStatus($status,$activityid,$activity_mode='')
 {
	global $log;
        $log->info("in ChangeStatus ".$status. ' activityid is  '.$activityid);

        global $adb;
        if ($activity_mode == 'Task')
        {
                $query = "Update activity set status='".$status."' where activityid = ".$activityid;
        }
        elseif ($activity_mode == 'Events')
        {
                $query = "Update activity set eventstatus='".$status."' where activityid = ".$activityid;
        }
        $adb->query($query);
 }


function getDBInsertDateValue($value)
{
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
		
	$insert_date=$y.'-'.$m.'-'.$d;
	return $insert_date;
}

function getUnitPrice($productid)
{
	global $log;
        $log->info("in getUnitPrice productid ".$productid);

        global $adb;
        $query = "select unit_price from products where productid=".$productid;
        $result = $adb->query($query);
        $up = $adb->query_result($result,0,'unit_price');
        return $up;
}


function upload_product_image_file($mode,$id)
{
	global $root_directory;
	global $log;
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
	return $ret_array;		

}

function getProductImageName($id,$deleted_array='')
{
	global $adb;
	global $log;
	$image_array=array();	
	$query = "select imagename from products where productid=".$id;
	$result = $adb->query($query);
	$image_name = $adb->query_result($result,0,"imagename");
	$image_array=explode("###",$image_name);
	$log->debug("Inside getProductImageName. The image_name is ".$image_name);
	if($deleted_array!='')
	{
		$resultant_image = array();
		$resultant_image=array_merge(array_diff($image_array,$deleted_array));
		$imagelists=implode('###',$resultant_image);	
		return	$imagelists;
	}
	else
		return $image_name;	
}
function getContactImageName($id)
{
        global $adb;
        global $log;
        $query = "select imagename from contactdetails where contactid=".$id;
        $result = $adb->query($query);
        $image_name = $adb->query_result($result,0,"imagename");
        $log->debug("Inside getContactImageName. The image_name is ".$image_name);
        return $image_name;

}
function updateSubTotal($module,$tablename,$colname,$colname1,$entid_fld,$entid,$prod_total)
{
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
}
function getInventoryTotal($return_module,$id)
{
	global $adb;
	if($return_module == "Potentials")
	{
		$query ="select products.productname,products.unit_price,products.qtyinstock,seproductsrel.* from products inner join seproductsrel on seproductsrel.productid=products.productid where crmid=".$id;
	}
	elseif($return_module == "Products")
	{
		$query="select products.productid,products.productname,products.unit_price,products.qtyinstock,crmentity.* from products inner join crmentity on crmentity.crmid=products.productid where crmentity.deleted=0 and productid=".$id;
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
	return $total;
}

function updateProductQty($product_id, $upd_qty)
{
	global $adb;
	$query= "update products set qtyinstock=".$upd_qty." where productid=".$product_id;
        $adb->query($query);

}

function get_account_info($parent_id)
{
        global $adb;
        $query = "select accountid from potential where potentialid=".$parent_id;
        $result = $adb->query($query);
        $accountid=$adb->query_result($result,0,'accountid');
        return $accountid;
}


//for Quickcreate-Form

function get_quickcreate_form($fieldlabel,$uitype,$fieldname,$tabid)
{
	$return_field ='';
	switch($uitype)	
	{
		case 1: $return_field .=get_textField($fieldlabel,$fieldname);
			return $return_field;
			break;
		case 2: $return_field .=get_textmanField($fieldlabel,$fieldname,$tabid);
			return $return_field;
			break;
		case 6: $return_field .=get_textdateField($fieldlabel,$fieldname,$tabid);
			return $return_field;
			break;
		case 11: $return_field .=get_textField($fieldlabel,$fieldname);
			return $return_field;
			break;
		case 13: $return_field .=get_textField($fieldlabel,$fieldname);
			return $return_field;	
			break;
		case 15: $return_field .=get_textcomboField($fieldlabel,$fieldname);
			return $return_field;	
			break;
		case 16: $return_field .=get_textcomboField($fieldlabel,$fieldname);
			return $return_field;	
			break;
		case 17: $return_field .=get_textwebField($fieldlabel,$fieldname);
			return $return_field;
			break;
		case 19: $return_field .=get_textField($fieldlabel,$fieldname);
			return $return_field;	
			break;
		case 22: $return_field .=get_textmanField($fieldlabel,$fieldname,$tabid);
			return $return_field;
			break;
		case 23: $return_field .=get_textdateField($fieldlabel,$fieldname,$tabid);
			return $return_field;
			break;
		case 50: $return_field .=get_textaccField($fieldlabel,$fieldname,$tabid);
			return $return_field;
			break;
		case 51: $return_field .=get_textaccField($fieldlabel,$fieldname,$tabid);
			return $return_field;
			break;
		case 55: $return_field .=get_textField($fieldlabel,$fieldname);
			return $return_field;
			break;
		case 63: $return_field .=get_textdurationField($fieldlabel,$fieldname,$tabid);
			return $return_field;
			break;
		case 71: $return_field .=get_textField($fieldlabel,$fieldname);
			return $return_field;
			break;
	}
}	
		
function get_textmanField($label,$name,$tid)
{
	$form_field='';
	if($tid == 9)
	{
		$form_field .='<td>';
		$form_field .= '<font color="red">*</font>';
		$form_field .= $label.':<br>';
		$form_field .='<input name="'.$name.'" id="QCK_T_'.$name.'" type="text" size="20" maxlength="" value=""></td>';
		return $form_field;	
	}
	if($tid == 16)
	{
		$form_field .='<td>';
		$form_field .= '<font color="red">*</font>';
		$form_field .= $label.':<br>';
		$form_field .='<input name="'.$name.'" id="QCK_E_'.$name.'" type="text" size="20" maxlength="" value=""></td>';
		return $form_field;	
	}
	else
	{
		$form_field .='<td>';
		$form_field .= '<font color="red">*</font>';
		$form_field .= $label.':<br>';
		$form_field .='<input name="'.$name.'" id="QCK_'.$name.'" type="text" size="20" maxlength="" value=""></td>';
		return $form_field;	
	}	
	
}	

function get_textwebField($label,$name)
{

	$form_field='';
	$form_field .='<td>';
	$form_field .= $label.':<br>http://<br>';
	$form_field .='<input name="'.$name.'" id="QCK_'.$name.'" type="text" size="20" maxlength="" value=""></td>';
	return $form_field;
	
}

function get_textField($label,$name)
{
	$form_field='';
	if($name == "amount")
	{
		$form_field .='<td>';
		$form_field .= $label.':(U.S Dollar:$)<br>';
		$form_field .='<input name="'.$name.'" id="QCK_'.$name.'" type="text" size="20" maxlength="" value=""></td>';
		return $form_field;
	}
	else
	{
		
		$form_field .='<td>';
		$form_field .= $label.':<br>';
		$form_field .='<input name="'.$name.'" id="QCK_'.$name.'" type="text" size="20" maxlength="" value=""></td>';
		return $form_field;
	}
	
}

function get_textaccField($label,$name,$tid)
{
	
	global $app_strings;

	$form_field='';
	if($tid == 2)
	{
		$form_field .='<td>';
		$form_field .= '<font color="red">*</font>';
		$form_field .= $label.':<br>';
		$form_field .='<input name="account_name" type="text" size="20" maxlength="" id="account_name" value="" readonly><br>';
		$form_field .='<input name="account_id" id="QCK_'.$name.'" type="hidden" value="">&nbsp;<input title="'.$app_strings[LBL_CHANGE_BUTTON_TITLE].'" accessKey="'.$app_strings[LBL_CHANGE_BUTTON_KEY].'" type="button" tabindex="3" class="button" value="'.$app_strings[LBL_CHANGE_BUTTON_LABEL].'" name="btn1" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Accounts&action=Popup&popuptype=specific&form=EditView&form_submit=false","test","width=600,height=400,resizable=1,scrollbars=1");\'></td>';
		return $form_field;
	}
	else
	{	
		$form_field .='<td>';
		$form_field .= $label.':<br>';
		$form_field .='<input name="account_name" type="text" size="20" maxlength="" value="" readonly><br>';
		$form_field .='<input name="'.$name.'" id="QCK_'.$name.'" type="hidden" value="">&nbsp;<input title="'.$app_strings[LBL_CHANGE_BUTTON_TITLE].'" accessKey="'.$app_strings[LBL_CHANGE_BUTTON_KEY].'" type="button" tabindex="3" class="button" value="'.$app_strings[LBL_CHANGE_BUTTON_LABEL].'" name="btn1" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Accounts&action=Popup&popuptype=specific&form=EditView&form_submit=false","test","width=600,height=400,resizable=1,scrollbars=1");\'></td>';
		return $form_field;
	}	
		
}
function get_textcomboField($label,$name)
{
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
                return $form_field;
        }


	
}


function get_textdateField($label,$name,$tid)
{
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
		return $form_field;	
	}
	
}


function get_textdurationField($label,$name,$tid)
{
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
		return $form_field;
	}	
}

//Added to get the parents list as hidden for Emails -- 09-11-2005
function getEmailParentsList($module,$id)
{
        global $adb;
	if($module == 'Contacts')
		$focus = new Contact();
	if($module == 'Leads')
		$focus = new Lead();
        
	$focus->retrieve_entity_info($id,$module);
        $fieldid = 0;
        $fieldname = 'email';
        if($focus->column_fields['email'] == '' && $focus->column_fields['yahooid'] != '')
                $fieldname = 'yahooid';

        $res = $adb->query("select * from field where tabid = ".getTabid($module)." and fieldname='".$fieldname."'");
        $fieldid = $adb->query_result($res,0,'fieldid');

        $hidden .= '<input type="hidden" name="emailids" value="'.$id.'@'.$fieldid.'|">';
        $hidden .= '<input type="hidden" name="pmodule" value="'.$module.'">';

	return $hidden;
}

/** This Function returns the current status of the specified Purchase Order.
  * The following is the input parameter for the function
  *  $po_id --> Purchase Order Id, Type:Integer
  */
function getPoStatus($po_id)
{

	global $log;
        $log->info("in getPoName ".$po_id);

        global $adb;
        $sql = "select postatus from purchaseorder where purchaseorderid=".$po_id;
        $result = $adb->query($sql);
        $po_status = $adb->query_result($result,0,"postatus");
        return $po_status;
}

/** This Function adds the specified product quantity to the Product Quantity in Stock in the Warehouse 
  * The following is the input parameter for the function:
  *  $productId --> ProductId, Type:Integer
  *  $qty --> Quantity to be added, Type:Integer
  */
function addToProductStock($productId,$qty)
{
	global $adb;
	$qtyInStck=getProductQtyInStock($productId);
	$updQty=$qtyInStck + $qty;
	$sql = "UPDATE products set qtyinstock=$updQty where productid=".$productId;
	$adb->query($sql);
	
}
/** This Function returns the current product quantity in stock.
  * The following is the input parameter for the function:
  *  $product_id --> ProductId, Type:Integer
  */
function getProductQtyInStock($product_id)
{
        global $adb;
        $query1 = "select qtyinstock from products where productid=".$product_id;
        $result=$adb->query($query1);
        $qtyinstck= $adb->query_result($result,0,"qtyinstock");
        return $qtyinstck;


}
/** Function to seperate the Date and Time
  * This function accepts a sting with date and time and
  * returns an array of two elements.The first element
  * contains the date and the second one contains the time
  */
function getDateFromDateAndtime($date_time)
{
    $result = explode(" ",$date_time);
    return $result;
}


function getBlockTableHeader($header_label)
{
	global $mod_strings;
	$label = $mod_strings[$header_label];
	$output = $label;
	return $output;

}



/**     Function to get the table name from 'field' table for the input field based on the module
 *      @param  : string $module - current module value
 *      @param  : string $fieldname - fieldname to which we want the tablename
 *      @return : string $tablename - tablename in which $fieldname is a column, which is retrieved from 'field' table per $module basis
 */
function getTableNameForField($module,$fieldname)
{
	global $adb;
	$tabid = getTabid($module);

	$sql = "select tablename from field where tabid=".$tabid." and fieldname like '%".$fieldname."%'";
	$res = $adb->query($sql);

	$tablename = '';
	if($adb->num_rows($res) > 0)
	{
		$tablename = $adb->query_result($res,0,'tablename');
	}

	return $tablename;
}

function getParentRecordOwner($tabid,$parModId,$record_id)
{
	$parentRecOwner=Array();
	$parentTabName=getTabname($parModId);
	$relTabName=getTabname($tabid);
	$fn_name="get".$relTabName."Related".$parentTabName;
	$ent_id=$fn_name($record_id);
	if($ent_id != '')
	{
		$parentRecOwner=getRecordOwnerId($ent_id);	
	}
	return $parentRecOwner;
}

function getPotentialsRelatedAccounts($record_id)
{
	global $adb;
	$query="select accountid from potential where potentialid=".$record_id;
	$result=$adb->query($query);
	$accountid=$adb->query_result($result,0,'accountid');
	return $accountid;
}

function getEmailsRelatedAccounts($record_id)
{
	global $adb;
	$query = "select seactivityrel.crmid from seactivityrel inner join crmentity on crmentity.crmid=seactivityrel.crmid where crmentity.setype='Accounts' and activityid=".$record_id;
	$result = $adb->query($query);
	$accountid=$adb->query_result($result,0,'crmid');
	return $accountid;
}

function getEmailsRelatedLeads($record_id)
{
	global $adb;
	$query = "select seactivityrel.crmid from seactivityrel inner join crmentity on crmentity.crmid=seactivityrel.crmid where crmentity.setype='Leads' and activityid=".$record_id;
	$result = $adb->query($query);
	$leadid=$adb->query_result($result,0,'crmid');
	return $leadid;
}

function getHelpDeskRelatedAccounts($record_id)
{
	global $adb;
        $query="select parent_id from troubletickets inner join crmentity on crmentity.crmid=troubletickets.parent_id where ticketid=".$record_id." and crmentity.setype='Accounts'";
        $result=$adb->query($query);
        $accountid=$adb->query_result($result,0,'parent_id');
        return $accountid;
}


function getQuotesRelatedAccounts($record_id)
{
	global $adb;
        $query="select accountid from quotes where quoteid=".$record_id;
        $result=$adb->query($query);
        $accountid=$adb->query_result($result,0,'accountid');
        return $accountid;
}


function getQuotesRelatedPotentials($record_id)
{
	global $adb;
        $query="select potentialid from quotes where quoteid=".$record_id;
        $result=$adb->query($query);
        $potid=$adb->query_result($result,0,'potentialid');
        return $potid;
}

function getSalesOrderRelatedAccounts($record_id)
{
	global $adb;
        $query="select accountid from salesorder where salesorder=".$record_id;
        $result=$adb->query($query);
        $accountid=$adb->query_result($result,0,'accountid');
        return $accountid;
}


function getSalesOrderRelatedPotentials($record_id)
{
	global $adb;
        $query="select potentialid from salesorder where salesorder=".$record_id;
        $result=$adb->query($query);
        $potid=$adb->query_result($result,0,'potentialid');
        return $potid;
}

function getSalesOrderRelatedQuotes($record_id)
{
	global $adb;
        $query="select quoteid from salesorder where salesorder=".$record_id;
        $result=$adb->query($query);
        $qtid=$adb->query_result($result,0,'quoteid');
        return $qtid;
}

function getInvoiceRelatedAccounts($record_id)
{
	global $adb;
        $query="select accountid from invoice where invoiceid=".$record_id;
        $result=$adb->query($query);
        $accountid=$adb->query_result($result,0,'accountid');
        return $accountid;
}

function getInvoiceRelatedSalesOrder($record_id)
{
	global $adb;
        $query="select salesorderid from invoice where invoiceid=".$record_id;
        $result=$adb->query($query);
        $soid=$adb->query_result($result,0,'salesorderid');
        return $soid;
}


/** Function to get Days and Dates in between the dates specified
        * Portions created by vtiger are Copyright (C) vtiger.
        * All Rights Reserved.
        * Contributor(s): ______________________________________..
 */
function get_days_n_dates($st,$en)
{
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
        return $nodays_dates; //passing no of days , days in between the days
}//function end


/** Function to get the start and End Dates based upon the period which we give
        * Portions created by vtiger are Copyright (C) vtiger.
        * All Rights Reserved.
        * Contributor(s): ______________________________________..
 */
function start_end_dates($period)
{
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
        return $datevalues;
}//function ends


/**   Function to get the Graph and table format for a particular date
        based upon the period
        * Portions created by vtiger are Copyright (C) vtiger.
        * All Rights Reserved.
        * Contributor(s): ______________________________________..
 */
function Graph_n_table_format($period_type,$date_value)
{
        $date_val=explode("-",$date_value);
        if($period_type=="month")   //to get the table format dates
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
        return $values;
}


function getImageCount($id)
{
	global $adb;
	$image_lists=array();
	$query="select imagename from products where productid=".$id;
	$result=$adb->query($query);
	$imagename=$adb->query_result($result,0,'imagename');
	$image_lists=explode("###",$imagename);
	return count($image_lists);

}
function getUserImageName($id)
{
	global $adb;
	global $log;
	$query = "select imagename from users where id=".$id;
	$result = $adb->query($query);
	$image_name = $adb->query_result($result,0,"imagename");
	$log->debug("Inside getUserImageName. The image_name is ".$image_name);
	return $image_name;

}


function getUserImageNames()
{
	global $adb;
	global $log;
	$query = "select imagename from users where deleted=0";
	$result = $adb->query($query);
	$image_name=array();
	for($i=0;$i<$adb->num_rows($result);$i++)
	{
		if($adb->query_result($result,$i,"imagename")!='')
			$image_name[] = $adb->query_result($result,$i,"imagename");
	}
	$log->debug("Inside getUserImageNames.");
	if(count($image_name) > 0)
		return $image_name;
}


?>
