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

require_once('modules/Leads/Lead.php');
require_once('include/logging.php');
require_once('database/DatabaseConnection.php');

$local_log =& LoggerManager::getLogger('index');

$focus = new Lead();

$focus->retrieve($_REQUEST['record']);

	
foreach($focus->column_fields as $field)
{
	$tempvalue;
		if($field == 'assigned_user_id')
		{
		//check which radio button the user has chosen
			if($_REQUEST['assigntype'] == 'T')
			{
				$tempvalue = $_REQUEST['assigned_group_name'];
				$value=$tempvalue;
			}
			else
			{
				$tempvalue = $_REQUEST['assigned_user_id'];
				$value = $tempvalue;
			}
	        }
		else if(isset($_REQUEST[$field]))
		{
			$value=$_REQUEST[$field];
		}
		$focus->$field = $value;
		if(get_magic_quotes_gpc() == 1)
		{
			$focus->$field = stripslashes($focus->$field);
		}
	}

foreach($focus->additional_column_fields as $field)
{
	if(isset($_REQUEST[$field]))
	{
	if($field == 'assigned_user_id')
		{
		//check which radio button the user has chosen
			if($_REQUEST['assigntype'] == 'T')
			{
				$value = $_REQUEST['assigned_group_name'];
			}
			else
			{
				$value = $_REQUEST['assigned_user_id'];
			}
		}
	else
	{
			
		$value = $_REQUEST[$field];
	}
		$focus->$field = $value;
		if(get_magic_quotes_gpc() == 1)
		{
			$focus->$field = stripslashes($focus->$field);
		}
	}
}

$focus->save();
$return_id = $focus->id;
save_customfields($focus->id);

if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = $_REQUEST['return_module'];
else $return_module = "Leads";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = $_REQUEST['return_action'];
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = $_REQUEST['return_id'];

$local_log->debug("Saved record with id of ".$return_id);

header("Location: index.php?action=$return_action&module=$return_module&record=$return_id");

//Code to save the custom field info into database
function save_customfields($entity_id)
{
	$dbquery="select * from customfields where module='Leads'";
	$result = mysql_query($dbquery);
	$custquery = 'select * from leadcf where leadid="'.$entity_id.'"';
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
					$columns='leadid, '.$colName;
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
			$query = 'update leadcf SET '.$update.' where leadid="'.$entity_id.'"'; 
			mysql_query($query);
		}
		else
		{
			//Insert Block
			$query = 'insert into leadcf ('.$columns.') values('.$values.')';
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
			$query = 'insert into leadcf ('.$columns.') values('.$values.')';
			mysql_query($query);
		}
	}	
}
?>
