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

//Fix postgres queries
function fixPostgresQuery($query,$log,$debug)
{
    // First select the query fields from the remaining query
    $queryFields = substr($query, strlen('select'), strpos($query,'FROM')-strlen('select'));
    $queryRecord = substr($query, strpos($query,'FROM'), strlen($query));
    $groupClause = "";
    $orderClause = "";

    if( $debug)
	$log->info( "fixPostgresQuery: ".$query);

    // If we already have an order or group cluase separate ist for later use
    if( strpos($queryRecord,'GROUP') > 0)
    {
	$groupClause = substr($queryRecord, strpos($queryRecord,'GROUP')+strlen('GROUP BY'), strlen($queryRecord));
	if( strpos($groupClause,'ORDER') > 0)
	{
	    $orderClause = substr($groupClause, strpos($groupClause,'ORDER'), strlen($groupClause));
	    $groupClause = substr($groupClause, 0, strpos($groupClause,'ORDER'));
	}
	$queryRecord = substr($queryRecord, 0, strpos($queryRecord,'GROUP'));
    }

    if( strpos($queryRecord,'ORDER') > 0)
    {
	$orderClause = substr($queryRecord, strpos($queryRecord,'ORDER'), strlen($queryRecord));
	$queryRecord = substr($queryRecord, 0, strpos($queryRecord,'ORDER'));
    }

    // Construkt the privateGroupList from the filed list by separating combined
    // record.field entries
    $privateGroupList = array();
    $token = strtok( $queryFields, ", ()	");
    while( $token !== false) {
	if( strpos( $token, ".") !== false) {
	    array_push( $privateGroupList, $token);
	}
	$token = strtok( ", ()	");
    }
    sort( $privateGroupList);
    $groupFields = "";
    $last = "";
    for( $i = 0; $i < count($privateGroupList); $i++) {
	if( $last != $privateGroupList[$i]) {
	    if( $groupFields == "")
		$groupFields = $privateGroupList[$i];
	    else
		$groupFields .= ",".$privateGroupList[$i];
	}
	$last = $privateGroupList[$i];
    }

    // Rebuild the query
    $query = "SELECT ".$queryFields.$queryRecord." GROUP BY ";
    if( $groupClause != "" )
	$groupClause = $groupClause.",".$groupFields;
    else
	$groupClause = $groupFields;
    $query .= expandStar($groupClause,$log)." ".$orderClause;

    if( $debug)
	$log->info( "fixPostgresQuery result: ".$query);

    return( $query);
}

// Postgres8 will not accept a "tablename.*" entry in the GROUP BY clause
function expandStar($fieldlist,$log)
{
    $expanded="";
    $field = strtok( $fieldlist, ",");
    while( $field != "")
    {
	//remove leading and trailing spaces
	$field = trim( $field);

	//still spaces in the field indicate a complex structure
	if( strpos( $field, " ") == 0)
	{

	    //locate table- and fieldname
	    $pos = strpos( $field, ".");
	    if( $pos > 0)
	    {
		$table = substr( $field, 0, $pos);
		$subfield = substr( $field, $pos+1, strlen($field)-$pos);

		//do we need to expand?
		if( $subfield == "*") 
		    $field = expandRecord($table,$log);
	    }

	    //add the propably expanded field to the querylist
	    if( $expanded == "")
		$expanded = $field;
	    else
		$expanded .= ",".$field;
	}

	//next field
	$field = strtok(",");
    }

    //return the expanded fieldlist
    return( $expanded);
}

//return an expanded table field list
function expandRecord($table,$log)
{
    $result = "";
    $log->info( "Debug: expandRecord");
    $subfields = array();

    //vtiger_products table
    if( $table == "vtiger_products" )
	$subfields = array ( "productid", "productname", "productcode", "productcategory", "manufacturer", "product_description", "qty_per_unit", "unit_price", "weight", "pack_size", "sales_start_date", "sales_end_date", "start_date", "expiry_date", "cost_factor", "commissionrate", "commissionmethod", "discontinued", "usageunit", "handler", "contactid", "currency", "reorderlevel", "website", "taxclass", "mfr_part_no", "vendor_part_no", "serialno", "qtyinstock", "productsheet", "qtyindemand", "glacct", "vendor_id", "imagename" );

    //vtiger_activity table
    elseif( $table == "vtiger_activity") 
	$subfields = array ( "activityid", "subject", "semodule", "activitytype", "date_start", "due_date", "time_start", "time_end", "sendnotification", "duration_hours", "duration_minutes", "status", "eventstatus", "priority", "location", "notime", "visibility" );

    //vtiger_notes table
    elseif( $table == "vtiger_notes")
	$subfields = array ( "notesid", "title", "filename", "notecontent", "folderid", "filepath", "filetype", "filelocationtype", "filedownloadcount", "filestatus", "filesize", "filearchitecture", "fileversion", "os");

    //vtiger_faq table
    elseif( $table == "vtiger_faq")
	$subfields = array ( "id", "product_id", "question", "answer", "category", "status");

    //vtiger_profile2field 
    elseif( $table == "vtiger_profile2field")
	$subfields = array ( "profileid", "tabid", "fieldid", "visible", "readonly");

    //vtiger_field 
    elseif( $table == "vtiger_field")
	$subfields = array ( "tabid", "fieldid", "columnname", "tablename", "generatedtype", "uitype", "fieldname", "fieldlabel", "readonly", "presence", "selected", "maximumlength", "sequence", "block", "displaytype", "typeofdata", "quickcreate", "quickcreatesequence", "info_type");

    //vtiger_activity
    elseif( $table == "vtiger_activity")
	$subfields = array ( "activityid", "subject", "semodule", "activitytype", "date_start", "due_date", "time_start", "sendnotification", "duration_hours", "duration_minutes", "status", "eventstatus", "priority", "location", "notime", "visibility");

    //fields of the requested array still undefined
    else
	$log->info("function expandRecord: please add structural information for table '".$table."'");

    //construct an entity string
    for( $i=0; $i<count($subfields); $i++)
    {
	$result .= $table.".".$subfields[$i].",";
    }

    //remove the trailiung ,
    if( strlen( $result) > 0)
	$result = substr( $result, 0, strlen( $result) -1);
    
    //return out new string
    return( $result);
}
?>
