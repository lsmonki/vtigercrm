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
 * Description:  Contains a variety of utility functions used to display UI 
 * components such as form headers and footers.  Intended to be modified on a per 
 * theme basis.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

function get_validate_import_fields_js (&$req_fields,&$all_fields)
{
	global $mod_strings;

	$err_multiple = $mod_strings['ERR_MULTIPLE'];
	$err_required = $mod_strings['ERR_MISSING_REQUIRED_FIELDS']; 
	$err_select_full_name = $mod_strings['ERR_SELECT_FULL_NAME']; 
	$print_required_array = "";
	$first = 1;

	foreach ($req_fields as $required=>$unused)
	{
		if ($first == 0)
		{
			$print_required_array .= ",";
		}

		$first = 0;
		$print_required_array .= "required['$required'] = '". $all_fields[$required] . "';\n";
		
	}

	$the_script  = <<<EOQ

<script type="text/javascript" language="Javascript">
<!--  to hide script contents from old browsers

function verify_data(form) 
{
	var isError = false;
	var errorMessage = "";

        var hash = new Object();

	var required = new Object();

	$print_required_array

	for(i=0;i < form.length;i++)
	{
		if ( form.elements[i].name.indexOf("colnum",0) == 0)
		{
		
			if ( form.elements[i].value == "-1")
			{
				continue;
			}
			if ( hash[ form.elements[i].value ] == 1)
			{
				// got same field more than once
				isError = true;
			}
			hash[form.elements[i].value] = 1;
		}
        }

	if (isError == true) 
	{
		alert( "$err_multiple" );
		return false;
	}

	if (hash['full_name'] == 1 && (hash['last_name'] == 1 || hash['first_name'] == 1) )
	{
		alert( "$err_select_full_name" );
		return false;
	}

	for(var field_name in required)
	{
		// contacts hack to bypass errors if full_name is set
		if (field_name == 'last_name' && 
				hash['full_name'] == 1)
		{
			continue;
		}
		if ( hash[ field_name ] != 1 )
		{
				isError = true;
				errorMessage += "$err_required " + required[field_name];
		}
	}

	if (isError == true) 
	{
		alert( errorMessage);
		return false;
	}


	return true;
}

// end hiding contents from old browsers  -->
</script>

EOQ;

	return $the_script;
}




function get_validate_upload_js () 
{
	global $mod_strings;

	$err_missing_required_fields = $mod_strings['ERR_MISSING_REQUIRED_FIELDS'];
	$lbl_select_file = $mod_strings['ERR_SELECT_FILE'];
	$lbl_custom = $mod_strings['LBL_CUSTOM'];

	$the_script  = <<<EOQ

<script type="text/javascript" language="Javascript">
<!--  to hide script contents from old browsers

function verify_data(form) 
{
	var isError = false;
	var errorMessage = "";
	if (form.userfile.value == "") 
	{
		isError = true;
		errorMessage += "\\n$lbl_select_file";
        } 
	else 
	{
		for(i=0;i < form.delimiter.length;i++)
		{
			if ( form.delimiter[i].value == "custom"
				&& form.delimiter[i].checked == true
				&& form.custom_delim.value == "")
			{
				isError = true;
				errorMessage += "\\n$lbl_custom";
				break;
			}
		}
        }

	if (isError == true) 
	{
		alert("$err_missing_required_fields" + errorMessage);
		return false;
	}


	return true;
}

// end hiding contents from old browsers  -->
</script>

EOQ;

	return $the_script;
}

function getFieldSelect(&$column_fields,$colnum,&$required_fields,$suggest_field,$translated_fields)
{
	global $mod_strings;
	global $app_strings;
	global $outlook_contacts_field_map;

	$output = "<select name=\"colnum" . $colnum ."\">\n";
	$output .= "<option value=\"-1\">". $mod_strings['LBL_DONT_MAP'] . "</option>";

	$count = 0;
	$req_mark = ""; 

	asort($translated_fields);


	foreach ($translated_fields as $field=>$name)
	{

	 	if (! isset($column_fields[$field]))
		{
			continue;
		}

		$output .= "<option value=\"".$field;

		if ( isset( $suggest_field) && 
			$field == $suggest_field)
		{
			$output .= "\" SELECTED>";
		}
		else 
		{
			$output .= "\">";
		}
		if ( isset( $required_fields[$field]))
		{
			$req_mark = " ". $app_strings['LBL_REQUIRED_SYMBOL'];
		} 
		else
		{
			$req_mark = "";
		}

		$output .=  $name . $req_mark."</option>\n";

		$count ++;
	}

	$output .= "</select>\n";

	return $output;

}


function get_readonly_js () 
{
?>
<script type="text/javascript" language="Javascript">
<!--  to hide script contents from old browsers

function set_readonly(form) 
{
	
	if (form.save_map.checked) 
	{ 
		form.save_map.value='on'; 
		form.save_map_as.readOnly=false;
		form.save_map_as.focus();
	}
	else 
	{
		form.save_map.value='off'; 
		form.save_map_as.value=""; 
		form.save_map_as.readOnly=true; 
	}
}

// end hiding contents from old browsers  -->
</script>

<?
}


?>
