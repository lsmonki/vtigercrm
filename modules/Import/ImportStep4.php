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
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('data/Tracker.php');
require_once('modules/Import/ImportContact.php');
require_once('modules/Import/ImportAccount.php');
require_once('modules/Import/ImportMap.php');
require_once('modules/Import/UsersLastImport.php');
require_once('modules/Import/parse_utils.php');
require_once('include/ListView/ListView.php');

function implode_assoc($inner_delim, $outer_delim, $array) 
{
	$output = array();

	foreach( $array as $key => $item )
	{
               $output[] = $key . $inner_delim . $item;
	}

       return implode($outer_delim, $output);
}

global $mod_strings;
global $app_list_strings;
global $app_strings;
global $current_user;
global $import_file_name;
global $theme;
global $site_URL;

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("Upload Step 2");


$maxfilesize = 300000;
$delimiter = ',';
// file handle
$count = 0;
$error = "";
$col_pos_to_field = array();
$header_to_field = array();
$field_to_pos = array();
$focus = 0;
$current_bean_type = "";
$broken_ids = 0;

$has_header = 0;

if ( isset( $_REQUEST['has_header']) && $_REQUEST['has_header'] == 'on')
{
	$has_header = 1;
}

if (! isset( $_REQUEST['module'] ) || $_REQUEST['module'] == 'Contacts')
{
	$current_bean_type = "ImportContact";
}
else if ( $_REQUEST['module'] == 'Accounts')
{
	$current_bean_type = "ImportAccount";
}
$focus = new $current_bean_type();

// loop through all request variables
foreach ($_REQUEST as $name=>$value)
{
	// only look for var names that start with "colnum"
	if ( strncasecmp( $name, "colnum", 6) != 0 )
	{
		continue;
	}
	if ($value == "-1")
	{
		continue;
	}

	// this value is a user defined field name
	$user_field = $value;

	// pull out the column position for this field name
	$pos = substr($name,6);

	// make sure we haven't seen this field defined yet
	if ( isset( $field_to_pos[$user_field]) )
	{
		show_error_import($mod_strings['LBL_ERROR_MULTIPLE']);
	        exit;

	}


	// match up the "official" field to the user 
	// defined one, and map to columm position: 
	if ( isset( $focus->importable_fields[$user_field] ) )
	{
		// now mark that we've seen this field
		$field_to_pos[$user_field] = $pos;

		$col_pos_to_field[$pos] = $user_field;
	}
}


// Now parse the file and look for errors
$max_lines = 10000;

$ret_value = 0;

if ($_REQUEST['source'] == 'act')
{
        $ret_value = parse_import_act($_REQUEST['tmp_file'],$delimiter,$max_lines,$has_header);
}
else
{
	$ret_value = parse_import($_REQUEST['tmp_file'],$delimiter,$max_lines,$has_header);
}

if (file_exists($_REQUEST['tmp_file']))
{
	unlink($_REQUEST['tmp_file']);
}

$rows = $ret_value['rows'];

$ret_field_count = $ret_value['field_count'];

$saved_ids = array();

$firstrow = 0;

if (! isset($rows))
{
	$error = $mod_strings['LBL_FILE_ALREADY_BEEN_OR'];
	$rows = array();
}

if ($has_header == 1)
{
	$firstrow = array_shift($rows);
}


$seedUsersLastImport = new UsersLastImport();
$seedUsersLastImport->mark_deleted_by_user_id($current_user->id);

$skip_required_count = 0;

// go thru each row, process and save()
foreach ($rows as $row)
{
	$focus = new $current_bean_type();

	$do_save = 1;

	for($field_count = 0; $field_count < $ret_field_count; $field_count++)
	{

		if ( isset( $col_pos_to_field[$field_count]) )
		{
			if (! isset( $row[$field_count]) )
			{
				continue;
			}

			// TODO: add check for user input
			// addslashes, striptags, etc..
			$field = $col_pos_to_field[$field_count];
			$focus->$field = $row[$field_count];

		}

	}
	
	// if the id was specified	
	if ( isset( $focus->id ) )
	{
		// check if it already exists
		$check_bean = new $current_bean_type();
		$check_bean->retrieve($focus->id);
	
		if (isset($check_bean->id) && $check_bean->id != -1)
		{
			$do_save = 0;
		}
		// check if the id is too long
		else if ( strlen($focus->id) > 36)
		{
			$do_save = 0;
		}
		if ($do_save == 0)
		{
                        $skip_required_count++;
			$broken_ids++;
		} 
		else
		{
			// set the flag to force an insert
			$focus->new_with_id = true;
		}
	}

	// now do any special processing
	$focus->process_special_fields();

	foreach ($focus->required_fields as $field=>$notused) 
	{ 
		if (! isset($focus->$field) || $focus->$field == '') 
		{ 
			$do_save = 0; 
			$skip_required_count++; 
			break; 
		} 
	}


	if ($do_save)
	{
		// TODO: create account relationship and create if not exists
		// TODO: make generic relationship function for other modules
		$focus->save();	
		$last_import = new UsersLastImport();
		$last_import->assigned_user_id = $current_user->id;
		$last_import->bean_type = $_REQUEST['module'];
		$last_import->bean_id = $focus->id;
		$last_import->save();
		array_push($saved_ids,$focus->id);
		$count++;
	}
	

}

// SAVE MAPPING IF REQUESTED
if ( isset($_REQUEST['save_map']) && $_REQUEST['save_map'] == 'on'
	&& isset($_REQUEST['save_map_as']) && $_REQUEST['save_map_as'] != '')
{
	$serialized_mapping = '';

	if( $has_header)
	{


		foreach($col_pos_to_field as $pos=>$field_name)
		{
	
			if ( isset($firstrow[$pos]) &&  isset( $field_name))
			{
				$header_to_field[ $firstrow[$pos] ] = $field_name;
			}
		}

		$serialized_mapping = implode_assoc("=","&",$header_to_field);
	}
	else
	{
		$serialized_mapping = implode_assoc("=","&",$col_pos_to_field);
	}

	$mapping_file_name = $_REQUEST['save_map_as'];


	$mapping_file = new ImportMap();

	$query_arr = array('assigned_user_id'=>$current_user->id,'name'=>$mapping_file_name);

	
	$mapping_file->retrieve_by_string_fields($query_arr);

	$result = $mapping_file->save_map( $current_user->id,
					$mapping_file_name,
					$_REQUEST['module'],
					$has_header,
					$serialized_mapping );
}

if ($error != "")
{
	show_error_import( $mod_strings['LBL_ERROR']." ". $error);
	exit;
}
else 
{
	$message= urlencode($mod_strings['LBL_SUCCESS']."<BR>$count ". $mod_strings['LBL_MODULE_NAME']." ".$mod_strings['LBL_SUCCESSFULLY']."<br>$skip_required_count  ". $mod_strings['LBL_IDS_EXISTED_OR_LONGER']. "<br>$broken_ids " .  $mod_strings['LBL_RECORDS_SKIPPED'] );

	header("Location: $site_URL/index.php?module={$_REQUEST['module']}&action=Import&step=last&return_module={$_REQUEST['return_module']}&return_action={$_REQUEST['return_action']}&message=$message");
exit;
}


?>
