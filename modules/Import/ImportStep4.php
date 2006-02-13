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
 * $Header: /cvsroot/vtigercrm/vtiger_crm/modules/Import/ImportStep4.php,v 1.18.2.1 2005/09/02 11:11:26 cooljaguar Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('data/Tracker.php');
require_once('modules/Import/ImportContact.php');
require_once('modules/Import/ImportAccount.php');
require_once('modules/Import/ImportOpportunity.php');
require_once('modules/Import/ImportLead.php');
require_once('modules/Import/ImportMap.php');
require_once('modules/Import/UsersLastImport.php');
require_once('modules/Import/parse_utils.php');
require_once('include/ListView/ListView.php');
require_once('include/database/PearDatabase.php');
require_once('modules/Import/ImportSave.php');
require_once('modules/Import/ImportProduct.php');

set_time_limit(0);
ini_set("display_errors",'0');


function p($str)
{
	global $adb;
	$adb->println("IMP :".$str);
}

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
global $upload_maxsize;
global $site_URL;

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("Upload Step 2");


$delimiter = ',';
// file handle
$count = 0;
$error = "";
$col_pos_to_field = array();
$header_to_field = array();
$field_to_pos = array();
$focus = 0;
$current_bean_type = "";
$id_exists_count = 0;
$broken_ids = 0;

$has_header = 0;

if ( isset( $_REQUEST['has_header']) && $_REQUEST['has_header'] == 'on')
{
	$has_header = 1;
}
if($_REQUEST['modulename'] != '')
	$_REQUEST['module'] = $_REQUEST['modulename'];

if (! isset( $_REQUEST['module'] ) || $_REQUEST['module'] == 'Contacts')
{
	$current_bean_type = "ImportContact";
}
else if ( $_REQUEST['module'] == 'Accounts')
{
	$current_bean_type = "ImportAccount";
}
else if ( $_REQUEST['module'] == 'Potentials')
{
	$current_bean_type = "ImportOpportunity";
}
else if ( $_REQUEST['module'] == 'Leads')
{
	$current_bean_type = "ImportLead";
}
else if ( $_REQUEST['module'] == 'Products')
{
 	$current_bean_type = "ImportProduct";
}
$focus = new $current_bean_type();
//Constructing the custom field Array
require_once('include/CustomFieldUtil.php');
$custFldArray = getCustomFieldArray($_REQUEST['module']);
p("IMP 4: custFldArray");
p($custFldArray);
//Initializing  an empty Array to store the custom field Column Name and Value
$resCustFldArray = Array();

p("Getting from request");
// loop through all request variables
foreach ($_REQUEST as $name=>$value)
{
	p("name=".$name." value=".$value);
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

	
	p("user_field=".$user_field." if=".$focus->importable_fields[$user_field]);
	
	// match up the "official" field to the user 
	// defined one, and map to columm position: 
	if ( isset( $focus->importable_fields[$user_field] ) || isset( $custFldArray[$user_field] ))
	{
		p("user_field SET=".$user_field);
		// now mark that we've seen this field
		$field_to_pos[$user_field] = $pos;

		$col_pos_to_field[$pos] = $user_field;
	}
}

p("field_to_pos");
$adb->println($field_to_pos);
p("col_pos_to_field");
$adb->println($col_pos_to_field);

// Now parse the file and look for errors
$max_lines = -1;

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

$datarows = $ret_value['rows'];

$ret_field_count = $ret_value['field_count'];

$saved_ids = array();

$firstrow = 0;

if (! isset($datarows))
{
	$error = $mod_strings['LBL_FILE_ALREADY_BEEN_OR'];
	$datarows = array();
}

if ($has_header == 1)
{
	$firstrow = array_shift($datarows);
}


$seedUsersLastImport = new UsersLastImport();
$seedUsersLastImport->mark_deleted_by_user_id($current_user->id);
$skip_required_count = 0;

p("processing started ret_field_count=".$ret_field_count);
$adb->println($datarows);

$error = '';
$focus = new $current_bean_type();

if(isset($_SESSION['totalrows']) && $_SESSION['totalrows'] != '')
{
	$xrows = $_SESSION['totalrows'];
}
else
{
	$xrows = $datarows;
}
if(isset($_SESSION['return_field_count']))
{
	$ret_field_count = $_SESSION['return_field_count'];
}
if(isset($_SESSION['column_position_to_field']))
{
	$col_pos_to_field = $_SESSION['column_position_to_field'];
}
if($xrows != '')
{
	$datarows = $xrows;
}
if($_REQUEST['skipped_record_count'] != '')
	$skipped_record_count = $_REQUEST['skipped_record_count'];
else
	$_REQUEST['skipped_record_count'] = 0;

if($_REQUEST['noofrows'] != '')
	$totalnoofrows = $_REQUEST['noofrows'];
else
	$totalnoofrows = count($datarows);

$loopcount = ($totalnoofrows/$RECORDCOUNT)+1;

if($_REQUEST['startval'] != '')
	$START = $_REQUEST['startval'];
else
	$START = $_SESSION['startval'];
if($_REQUEST['recordcount'] != '')
	$RECORDCOUNT = $_REQUEST['recordcount'];
else
	$RECORDCOUNT = $_SESSION['recordcount'];

if(($START+$RECORDCOUNT) > $totalnoofrows)
{
	$RECORDCOUNT = $totalnoofrows - $START;
}

if($totalnoofrows > $RECORDCOUNT && $START < $totalnoofrows)
{
		$rows1 = Array();
		for($j=$START;$j<$START+$RECORDCOUNT;$j++)
		{
			$rows1[] = $datarows[$j];
		}
		$res = InsertImportRecords($datarows,$rows1,$focus,$ret_field_count,$col_pos_to_field,$START,$RECORDCOUNT,$_REQUEST['module'],$totalnoofrows,$skipped_record_count);

if($START != 0)
	echo '<b>'.$res.'</b>';

		$count = $_REQUEST['count'];
}
else
{
	if($START == 0)
	{
		$res = InsertImportRecords($datarows,$datarows,$focus,$ret_field_count,$col_pos_to_field,$START,$totalnoofrows,$_REQUEST['module'],$totalnoofrows,$skipped_record_count);
	}
//	exit;
}

// SAVE MAPPING IF REQUESTED
if ( isset($_REQUEST['save_map']) && $_REQUEST['save_map'] == 'on'
	&& isset($_REQUEST['save_map_as']) && $_REQUEST['save_map_as'] != '')
{
	p("save map");
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

	
	$mapping_file->retrieve_by_string_fields($query_arr, false);

	$result = $mapping_file->save_map( $current_user->id,
					$mapping_file_name,
					$_REQUEST['module'],
					$has_header,
					$serialized_mapping );

	$adb->println("Save map done");
	$adb->println($result);
}

if ($error != "")
{
	show_error_import( $mod_strings['LBL_ERROR']." ". $error);
	exit;
}
else 
{
	$message= urlencode($mod_strings['LBL_SUCCESS']."<BR>$count ". $mod_strings['LBL_MODULE_NAME']." ".$mod_strings['LBL_SUCCESSFULLY']."<br>".($broken_ids+$id_exists_count) ." ". $mod_strings['LBL_IDS_EXISTED_OR_LONGER']. "<br>$skip_required_count " .  $mod_strings['LBL_RECORDS_SKIPPED'] );

	header("Location: index.php?module={$_REQUEST['module']}&action=Import&step=last&return_module={$_REQUEST['return_module']}&return_action={$_REQUEST['return_action']}&message=$message");
exit;
}


?>
