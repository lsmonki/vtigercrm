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
require_once('include/database/PearDatabase.php');
require_once('modules/Users/UserInfoUtil.php');

$local_log =& LoggerManager::getLogger('index');
global $vtlog;
$focus = new Lead();

if(isset($_REQUEST['record']))
{
	$focus->id = $_REQUEST['record'];
}
if(isset($_REQUEST['mode']))
{
	$focus->mode = $_REQUEST['mode'];
}

//$focus->retrieve($_REQUEST['record']);

foreach($focus->column_fields as $fieldname => $val)
{
  /*
  $tempvalue;
  if($field == 'assigned_user_id')
  {
    //check which radio button the user has chosen
    if($_REQUEST['assigntype'] == 'T')
    {
      $value='null';
      $focus->$field = $value;
    }
    else
    {
      $tempvalue = $_REQUEST['assigned_user_id'];
      $value = $tempvalue;
      $focus->$field = $value;
    }
  }
  else if(isset($_REQUEST[$field]))
  {
    $value=$_REQUEST[$field];
    $focus->$field = $value;
  }
	
  if(get_magic_quotes_gpc() == 1)
  {
    $focus->$field = stripslashes($focus->$field);
  }
  */
  	if(isset($_REQUEST[$fieldname]))
	{
          $value = $_REQUEST[$fieldname];
	  $vtlog->logthis("the value is ".$value,'info');  
          //echo '<BR>';
          //echo $fieldname."         ".$value;
          //echo '<BR>';
          $focus->column_fields[$fieldname] = $value;
        }
        
}

/*
foreach($focus->additional_column_fields as $field)
{
	if(isset($_REQUEST[$field]))
	{
          if($field == 'assigned_user_id')
          {
            //check which radio button the user has chosen
            if($_REQUEST['assigntype'] == 'T')
            {
              $value = 'null';
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
$createLeadFlag = true;
if($focus->id == "")
{
}
else
{
$createLeadFlag = false;
if($_REQUEST['assigntype'] == 'T')
			{
				$tempvalue = $_REQUEST['assigned_group_name'];
				$value=$tempvalue;
                                updateLeadGroupRelation($focus->id,$value);
                        }
else
{
updateLeadGroupRelation($focus->id,'');
}


}
*/
//$focus->saveentity("Leads");
$focus->save("Leads");

$return_id = $focus->id;
	  $vtlog->logthis("the return id is ".$return_id,'info');  
/*
if($createLeadFlag)
{
		if($_REQUEST['assigntype'] == 'T')
			{
				$tempvalue = $_REQUEST['assigned_group_name'];
				$value=$tempvalue;
                                insert2LeadGroupRelation($focus->id,$value);
                        }
}
save_customfields($focus->id);
*/
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
	  $vtlog->logthis("save custom field invoked ".$entity_id,'debug');  
	global $adb;
	$dbquery="select * from customfields where module='Leads'";
	$result = $adb->query($dbquery);
	$custquery = "select * from leadcf where leadid='".$entity_id."'";
        $cust_result = $adb->query($custquery);
	if($adb->num_rows($result) != 0)
	{
		
		$columns='';
		$values='';
		$update='';
		$noofrows = $adb->num_rows($result);
		for($i=0; $i<$noofrows; $i++)
		{
			$fldName=$adb->query_result($result,$i,"fieldlabel");
			$colName=$adb->query_result($result,$i,"column_name");
			if(isset($_REQUEST[$colName]))
			{
				$fldvalue=$_REQUEST[$colName];
	  $vtlog->logthis("the columnName is ".$fldvalue,'info');  
				if(get_magic_quotes_gpc() == 1)
                		{
                        		$fldvalue = stripslashes($fldvalue);
                		}
			}
			else
			{
				$fldvalue = '';
			}
			if(isset($_REQUEST['record']) && $_REQUEST['record'] != '' && $adb->num_rows($cust_result) !=0)
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
		if(isset($_REQUEST['record']) && $_REQUEST['record'] != '' && $adb->num_rows($cust_result) !=0)
		{
			//Update Block
			$query = 'update leadcf SET '.$update.' where leadid="'.$entity_id.'"'; 
			$adb->query($query);
		}
		else
		{
			//Insert Block
			$query = 'insert into leadcf ('.$columns.') values('.$values.')';
			$adb->query($query);
		}
		
	}
	/* srini patch
	else
	{
		if(isset($_REQUEST['record']) && $_REQUEST['record'] != '' && $adb->num_rows($cust_result) !=0)
		{
			//Update Block
		}
		else
		{
			//Insert Block
			$query = 'insert into leadcf ('.$columns.') values('.$values.')';
			$adb->query($query);
		}
	}*/	
}
?>
