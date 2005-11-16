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

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Import/ImportContact.php');
require_once('modules/Import/ImportAccount.php');
require_once('modules/Import/ImportOpportunity.php');
require_once('modules/Import/ImportLead.php');
require_once('modules/Import/Forms.php');
require_once('modules/Import/parse_utils.php');
require_once('modules/Import/ImportMap.php');
require_once('include/database/PearDatabase.php');
require_once('include/CustomFieldUtil.php');
require_once('modules/Import/ImportProduct.php');

@session_unregister('column_position_to_field');
@session_unregister('totalrows');
@session_unregister('recordcount');
@session_unregister('startval');
@session_unregister('return_field_count');
$_SESSION['totalrows'] = '';
$_SESSION['recordcount'] = 500;
$_SESSION['startval'] = 0;

global $mod_strings;
global $mod_list_strings;
global $app_list_strings;
global $app_strings;
global $current_user;
global $import_file_name;
global $upload_maxsize;

global $theme;
global $outlook_contacts_field_map;
global $act_contacts_field_map;
global $salesforce_contacts_field_map;
global $outlook_accounts_field_map;
global $act_accounts_field_map;
global $salesforce_accounts_field_map;
global $salesforce_opportunities_field_map;
global $import_dir;
$focus = 0;
$delimiter = ',';
$max_lines = 3;

$has_header = 0;

if ( isset( $_REQUEST['has_header']))
{
	$has_header = 1;
}

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');



$log->info($mod_strings['LBL_MODULE_NAME']." Upload Step 3");

if (!is_uploaded_file($_FILES['userfile']['tmp_name']) )
{
	show_error_import($mod_strings['LBL_IMPORT_MODULE_ERROR_NO_UPLOAD']);
	exit;
}
else if ($_FILES['userfile']['size'] > $upload_maxsize)
{
	show_error_import( $mod_strings['LBL_IMPORT_MODULE_ERROR_LARGE_FILE'] . " ". $upload_maxsize. " ". $mod_strings['LBL_IMPORT_MODULE_ERROR_LARGE_FILE_END']);
	exit;
}
if( !is_writable( $import_dir ))
{
	show_error_import($mod_strings['LBL_IMPORT_MODULE_NO_DIRECTORY'].$import_dir.$mod_strings['LBL_IMPORT_MODULE_NO_DIRECTORY_END']);
	exit;
}

$tmp_file_name = $import_dir. "IMPORT_".$current_user->id;

move_uploaded_file($_FILES['userfile']['tmp_name'], $tmp_file_name);


// Now parse the file and look for errors
$ret_value = 0;

if ($_REQUEST['source'] == 'act')
{
	$ret_value = parse_import_act($tmp_file_name,$delimiter,$max_lines,$has_header);
} 
else
{
	$ret_value = parse_import($tmp_file_name,$delimiter,$max_lines,$has_header);
}

if ($ret_value == -1)
{
	show_error_import( $mod_strings['LBL_CANNOT_OPEN'] );
	exit;
} 
else if ($ret_value == -2)
{
	show_error_import( $mod_strings['LBL_NOT_SAME_NUMBER'] );
	exit;
}
else if ( $ret_value == -3 )
{
	show_error_import( $mod_strings['LBL_NO_LINES'] );
	exit;
}


$rows = $ret_value['rows'];

$ret_field_count = $ret_value['field_count'];
//echo 'my return field count i s ' .$ret_field_count;
$xtpl=new XTemplate ('modules/Import/ImportStep3.html');

$xtpl->assign("TMP_FILE", $tmp_file_name );

$xtpl->assign("SOURCE", $_REQUEST['source'] );

$source_to_name = array( 'outlook'=>$mod_strings['LBL_MICROSOFT_OUTLOOK'],
'act'=>$mod_strings['LBL_ACT'],
'salesforce'=>$mod_strings['LBL_SALESFORCE'],
'custom'=>$mod_strings['LBL_CUSTOM'],
'other'=>$mod_strings['LBL_CUSTOM'],
);

$xtpl->assign("SOURCE_NAME", $source_to_name[$_REQUEST['source']] );
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);

if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);

$xtpl->assign("THEME", $theme);

$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);

$xtpl->assign("HEADER", $app_strings['LBL_IMPORT']." ". $mod_strings['LBL_MODULE_NAME']);


if (! isset( $_REQUEST['module'] ) || $_REQUEST['module'] == 'Contacts')
{
	$focus = new ImportContact();
}
else if ( $_REQUEST['module'] == 'Accounts')
{
	$focus = new ImportAccount();
}
else if ( $_REQUEST['module'] == 'Potentials')
{
	$focus = new ImportOpportunity();
}
else if ( $_REQUEST['module'] == 'Leads')
{
	$focus = new ImportLead();
}
else if ( $_REQUEST['module'] == 'Products')
{
	$focus = new ImportProduct();
}



//if ($has_header)
//{
//	$firstrow = array_shift($rows);
//} 
//else
//{
	$firstrow = $rows[0];
//}

$field_map = $outlook_contacts_field_map;

if ( isset( $_REQUEST['source_id']))
{
	$mapping_file = new ImportMap();

	//$mapping_file->retrieve_entity_info( $_REQUEST['source_id'],$_REQUEST['return_module']);
	$mapping_file->retrieve( $_REQUEST['source_id'],false);
	$adb->println("richie : ".$mapping_file->toString());

	$mapping_content = $mapping_file->content;

	$mapping_arr = array();

	if ( isset($mapping_content) && $mapping_content != "")
	{
		$pairs = split("&",$mapping_content);
	
		foreach ($pairs as $pair)
		{
			list($name,$value) = split("=",$pair);
			$mapping_arr["$name"] = $value;
		}
	}
}

if ( count($mapping_arr) > 0)
{
	$field_map = &$mapping_arr;
}
else if ($_REQUEST['source'] == 'other')
{
	if ($_REQUEST['module'] == 'Contacts')
	{
		$field_map = $outlook_contacts_field_map;
	} 
	else if ($_REQUEST['module'] == 'Accounts')
	{
		$field_map = $outlook_accounts_field_map;
	}
	else if ($_REQUEST['module'] == 'Potentials')
	{
		$field_map = $salesforce_opportunities_field_map;
	}
} 
else if ($_REQUEST['source'] == 'act')
{
	if ($_REQUEST['module'] == 'Contacts')
	{
		$field_map = $act_contacts_field_map;
	} 
	else if ($_REQUEST['module'] == 'Accounts')
	{
		$field_map = $act_accounts_field_map;
	}
}
else if ($_REQUEST['source'] == 'salesforce')
{
	if ($_REQUEST['module'] == 'Contacts')
	{
		$field_map = $salesforce_contacts_field_map;
	} 
	else if ($_REQUEST['module'] == 'Accounts')
	{
		$field_map = $salesforce_accounts_field_map;
	}
	else if ($_REQUEST['module'] == 'Potentials')
	{
		$field_map = $salesforce_opportunities_field_map;
	}
}
else if ($_REQUEST['source'] == 'outlook')
{
	$xtpl->assign("IMPORT_FIRST_CHECKED", " CHECKED");
	if ($_REQUEST['module'] == 'Contacts')
	{
		$field_map = $outlook_contacts_field_map;
	} 
	else if ($_REQUEST['module'] == 'Accounts')
	{
		$field_map = $outlook_accounts_field_map;
	}

}

$add_one = 1;
$start_at = 0;

if ( $has_header)
{
	$xtpl->parse("main.table.toprow.headercell");
	$add_one = 0;
	$start_at = 1;
} 

for($row_count = $start_at; $row_count < count($rows); $row_count++ )
{
	$xtpl->assign("ROWCOUNT", $row_count + $add_one);
	$xtpl->parse("main.table.toprow.topcell");
}

$xtpl->parse("main.table.toprow");

$list_string_key = strtolower($_REQUEST['module']);
$list_string_key .= "_import_fields";

$translated_column_fields = $mod_list_strings[$list_string_key];

//$adb->println("IMP3 : trans before");
//$adb->println($translated_column_fields);

// adding custom fields translations

getCustomFieldTrans($_REQUEST['module'],&$translated_column_fields);

$adb->println("IMP3 : trans");
$adb->println($translated_column_fields);


$cnt=1;
for($field_count = 0; $field_count < $ret_field_count; $field_count++)
{

	$xtpl->assign("COLCOUNT", $field_count + 1);
	$suggest = "";

	if ($has_header && isset( $field_map[$firstrow[$field_count]] ) )
	{
		$suggest = $field_map[$firstrow[$field_count]];	
	}
	else if (isset($field_map[$field_count]))
	{
		$suggest = $field_map[$field_count];	
	}

	if($_REQUEST['module']=='Accounts')
	{
		$tablename='account';
		$focus1=new Account();
	}
	if($_REQUEST['module']=='Contacts')
	{
		$tablename='contactdetails';
		$focus1=new Contact();
 	}
	if($_REQUEST['module']=='Leads')
 	{
		$tablename='leaddetails';
		$focus1=new Lead();
	}
	if($_REQUEST['module']=='Potentials')
 	{
		$tablename='potential';
		$focus1=new Potential();
	}
	if($_REQUEST['module']=='Products')
 	{
 		$tablename='products';
 		$focus1=new Product();
 	}

	
//echo 'xxxxxxxxxxxxxxxxxxxx';
//print_r($focus->importable_fields);
//print_r($focus->column_fields);
/*
	$xtpl->assign("SELECTFIELD", getFieldSelect(	$focus->importable_fields,
							$requiredfieldval,
							$focus1->required_fields,
							$suggest,
							$focus1->column_fields,
							$tablename
						   ));
*/
	$xtpl->assign("SELECTFIELD", getFieldSelect(	$focus->importable_fields,
							$field_count,//requiredfieldval,
							$focus1->required_fields,
							$suggest,
							$translated_column_fields,
							$tablename
						   ));

/*		getFieldSelect(	$focus->importable_fields,
				$field_count,
				$focus->required_fields,
				$suggest,
				$translated_column_fields,
				$_REQUEST['module']
				));
*/
	$xtpl->parse("main.table.row.headcell");

	$pos = 0;

	foreach ( $rows as $row ) 
	{
		if ($cnt%2==0)
			$xtpl->assign("ROWCLASS","evenListRow");
		else
			$xtpl->assign("ROWCLASS","oddListRow");
		
		if( isset($row[$field_count]) && $row[$field_count] != '')
		{
			$xtpl->assign("CELL",htmlspecialchars($row[$field_count]));
			$xtpl->parse("main.table.row.cell");
		} 
		else
		{
			$xtpl->parse("main.table.row.cellempty");
		}
		
		$cnt++;
	}

	$xtpl->parse("main.table.row");

}

$xtpl->parse("main.table");

$module_key = "LBL_".strtoupper($_REQUEST['module'])."_NOTE_";

for ($i = 1;isset($mod_strings[$module_key.$i]);$i++)
{
	$xtpl->assign("NOTETEXT", $mod_strings[$module_key.$i]);
	$xtpl->parse("main.note");
}



if($has_header)
{
	$xtpl->assign("HAS_HEADER", 'on');
} 
else
{
	$xtpl->assign("HAS_HEADER", 'off');
}


$xtpl->assign("MODULE", $_REQUEST['module']);
//$xtpl->assign("JAVASCRIPT", get_validate_import_fields_js($focus->required_fields,$translated_column_fields) );

$xtpl->assign("JAVASCRIPT2", get_readonly_js() );


$xtpl->parse("main");

$xtpl->out("main");


?>
