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
 * $Header:  vtiger_crm/sugarcrm/modules/Opportunities/Save.php,v 1.3 2004/11/25 11:32:50 jack Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/Opportunities/Opportunity.php');
require_once('include/logging.php');
require_once('database/DatabaseConnection.php');

$local_log =& LoggerManager::getLogger('index');

$focus = new Opportunity();

$focus->retrieve($_REQUEST['record']);

foreach($focus->column_fields as $field)
{
	if(isset($_REQUEST[$field]))
	{
		$value = $_REQUEST[$field];
		$focus->$field = $value;
		
	}
}

foreach($focus->additional_column_fields as $field)
{
	if(isset($_REQUEST[$field]))
	{
		$value = $_REQUEST[$field];
		$focus->$field = $value;
		
	}
}

$focus->save();
$return_id = $focus->id;
save_customfields($focus->id);

if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = $_REQUEST['return_module'];
else $return_module = "Opportunities";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = $_REQUEST['return_action'];
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = $_REQUEST['return_id'];

$local_log->debug("Saved record with id of ".$return_id);

header("Location: index.php?action=$return_action&module=$return_module&record=$return_id");
//Code to save the custom field info into database
function save_customfields($entity_id)
{
	$dbquery="select * from customfields where module='Opportunities'";
	$result = mysql_query($dbquery);
	$custquery = 'select * from opportunitycf where opportunityid="'.$entity_id.'"';
        $cust_result = mysql_query($custquery);
	if(mysql_num_rows($result) != 0)
	{
		
		$columns='';
		$values='';
		$update='';
		$noofrows = mysql_num_rows($result);
		for($i=0; $i<$noofrows; $i++)
		{
			$fldName=mysql_result($result,$i,"fieldlabel");
			$colName=mysql_result($result,$i,"column_name");
			if(isset($_REQUEST[$colName]))
			{
				$fldvalue=$_REQUEST[$colName];
				if(get_magic_quotes_gpc() == 1)
                		{
                        		$fldvalue = stripslashes($fldvalue);
                		}
			}
			else
			{
				$fldvalue = '';
			}
			if(isset($_REQUEST['record']) && $_REQUEST['record'] != '' && mysql_num_rows($cust_result) !=0)
			{
				//Update Block
				if($i == 0)
				{
					$update = $colName.'="'.$fldvalue.'"';
				}
				else
				{
					$update .= ', '.$colName.'="'.$fldvalue.'"';
				}
			}
			else
			{
				//Insert Block
				if($i == 0)
				{
					$columns='opportunityid, '.$colName;
					$values='"'.$entity_id.'", "'.$fldvalue.'"';
				}
				else
				{
					$columns .= ', '.$colName;
					$values .= ', "'.$fldvalue.'"';
				}
			}
			
				
		}
		if(isset($_REQUEST['record']) && $_REQUEST['record'] != '' && mysql_num_rows($cust_result) !=0)
		{
			//Update Block
			$query = 'update opportunitycf SET '.$update.' where opportunityid="'.$entity_id.'"'; 
			mysql_query($query);
		}
		else
		{
			//Insert Block
			$query = 'insert into opportunitycf ('.$columns.') values('.$values.')';
			mysql_query($query);
		}
		
	}
	else
	{
		if(isset($_REQUEST['record']) && $_REQUEST['record'] != '' && mysql_num_rows($cust_result) !=0)
		{
			//Update Block
		}
		else
		{
			//Insert Block
			$query = 'insert into opportunitycf ('.$columns.') values('.$values.')';
			mysql_query($query);
		}
	}	
}
?>
