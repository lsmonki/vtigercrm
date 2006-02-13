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

// takes a string and parses it into one record per line,
// one field per delimiter, to a maximum number of lines
// some files have a header, some dont.
// keeps track of which fields are used

function parse_import($file_name,$delimiter,$max_lines,$has_header)
{
	$line_count = 0;

	$field_count = 0;

	$rows = array();

	if (! file_exists($file_name))
	{
		return -1;
	}

	$fh = fopen($file_name,"r");

	if (! $fh)
	{
		return -1;
	}

	while ( (( $fields = fgetcsv($fh, 4096, $delimiter) ) !== FALSE) 
		&& ( $max_lines == -1 || $line_count < $max_lines)) 
	{

		if ( count($fields) == 1 && isset($fields[0]) && $fields[0] == '')
		{
			break;
		}
		$this_field_count = count($fields);

		if ( $this_field_count > $field_count)
		{
			$field_count = $this_field_count;
		}

		array_push($rows,$fields);

		$line_count++;

	}

	// got no rows
	if ( count($rows) == 0)
	{
		return -3;
	}

	$ret_array = array(
		"rows"=>&$rows,
		"field_count"=>$field_count
	);

	return $ret_array;

}

function parse_import_act($file_name,$delimiter,$max_lines,$has_header)
{
	$line_count = 0;

	$field_count = 0;

	$rows = array();

	if (! file_exists($file_name))
	{
		return -1;
	}

	$fh = fopen($file_name,"r");

	if (! $fh)
	{
		return -1;
	}

	while ( ($line = fgets($fh, 4096))
                && ( $max_lines == -1 || $line_count < $max_lines) )

	{
		
		$line = trim($line);
		$line = substr_replace($line,"",0,1);
		$line = substr_replace($line,"",-1);
		$fields = explode("\",\"",$line);

		$this_field_count = count($fields);

		if ( $this_field_count > $field_count)
		{
			$field_count = $this_field_count;
		}

		array_push($rows,$fields);

		$line_count++;

	}

	// got no rows
	if ( count($rows) == 0)
	{
		return -3;
	}

	$ret_array = array(
		"rows"=>&$rows,
		"field_count"=>$field_count
	);

	return $ret_array;

}
?>
