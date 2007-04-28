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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Accounts/Save.php,v 1.7 2005/03/15 09:55:31 shaw Exp $
 * Description:  Saves an Account record and then redirects the browser to the 
 * defined return URL.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/Accounts/Accounts.php');
require_once('include/logging.php');
//require_once('database/DatabaseConnection.php');
require_once('include/database/PearDatabase.php');

if(isset($_REQUEST['dup_check']) && $_REQUEST['dup_check'] != '')
{
	//started
	$value = $_REQUEST['accountname'];
	$query = "SELECT accountname FROM vtiger_account,vtiger_crmentity WHERE accountname ='".$value."' and vtiger_account.accountid = vtiger_crmentity.crmid and vtiger_crmentity.deleted != 1";
	$result = $adb->query($query);
        if($adb->num_rows($result) > 0)
	{
		echo 'Account Name Already Exists!';
	}
	else
	{
		echo 'SUCCESS';
	}
	die;
}
//Ended



$local_log =& LoggerManager::getLogger('index');
global $log;
$focus = new Accounts();
global $current_user;
$currencyid=fetchCurrency($current_user->id);
$rate_symbol = getCurrencySymbolandCRate($currencyid);
$rate = $rate_symbol['rate'];
$curr_symbol = $rate_symbol['symbol'];
if(isset($_REQUEST['record']))
{
	$focus->id = $_REQUEST['record'];
$log->info("id is ".$focus->id);
}
if(isset($_REQUEST['mode']))
{
	$focus->mode = $_REQUEST['mode'];
}

//$focus->retrieve($_REQUEST['record']);

foreach($focus->column_fields as $fieldname => $val)
{
	if(isset($_REQUEST[$fieldname]))
	{
		$value = $_REQUEST[$fieldname];
		$log->DEBUG($fieldname."=Field Name &first& Value =".$value);
		$focus->column_fields[$fieldname] = $value;
	}
	if(isset($_REQUEST['annual_revenue']))
        {
                        $value = convertToDollar($_REQUEST['annual_revenue'],$rate);
                        $focus->column_fields['annual_revenue'] = $value;
        }
		
}
//echo '<BR>';
//print_r($focus->column_fields);
//echo '<BR>';

/* foreach($focus->additional_column_fields as $field)
{
	if(isset($_REQUEST[$field]))
	{
		$value = $_REQUEST[$field];
		$focus->$field = $value;
		
	}
}*/

//When changing the Account Address Information  it should also change the related contact address - dina
if($focus->mode == 'edit' && $_REQUEST['address_change'] == 'yes')
{
                $query = "update vtiger_contactaddress set mailingcity='".$focus->column_fields['bill_city']."',mailingstreet='".$focus->column_fields['bill_street']."',mailingcountry='".$focus->column_fields['bill_country']."',mailingzip='".$focus->column_fields['bill_code']."',mailingpobox='".$focus->column_fields['bill_pobox']."',mailingstate='".$focus->column_fields['bill_state']."',othercountry='".$focus->column_fields['ship_country']."',othercity='".$focus->column_fields['ship_city']."',otherstate='".$focus->column_fields['ship_state']."',otherzip='".$focus->column_fields['ship_code']."',otherstreet='".$focus->column_fields['ship_street']."',otherpobox='".$focus->column_fields['ship_pobox']."'  where contactaddressid in (select contactid from vtiger_contactdetails where accountid=".$focus->id.")" ;
                $adb->query($query);
}
//Changing account address - Ends

//$focus->saveentity("Accounts");
$focus->save("Accounts");
//echo '<BR>';
//echo $focus->id;
$return_id = $focus->id;
//save_customfields($focus->id);

if(isset($_REQUEST['parenttab']) && $_REQUEST['parenttab'] != "") $parenttab = $_REQUEST['parenttab'];
if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = $_REQUEST['return_module'];
else $return_module = "Accounts";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = $_REQUEST['return_action'];
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = $_REQUEST['return_id'];

$local_log->debug("Saved record with id of ".$return_id);


//code added for returning back to the current view after edit from list view
if($_REQUEST['return_viewname'] == '') $return_viewname='0';
if($_REQUEST['return_viewname'] != '')$return_viewname=$_REQUEST['return_viewname'];

//Send notification mail to the assigned to owner about the vtiger_account creation
if($focus->column_fields['notify_owner'] == 1 || $focus->column_fields['notify_owner'] == 'on')
	$status = sendNotificationToOwner('Accounts',$focus);

header("Location: index.php?action=$return_action&module=$return_module&parenttab=$parenttab&record=$return_id&viewname=$return_viewname");

/** Function to save Accounts custom field info into database
* @param integer $entity_id - accountid
*/
function save_customfields($entity_id)
{
	global $log;
	$log->debug("Entering save_customfields(".$entity_id.") method ...");
	$log->info("save customfields invoked");
	global $adb;
	$dbquery = "SELECT * FROM customfields WHERE module = 'Accounts'";
        /*
	$result = mysql_query($dbquery);
	$custquery = "SELECT * FROM vtiger_accountcf WHERE vtiger_accountid = '".$entity_id."'";
        $cust_result = mysql_query($custquery);
	if(mysql_num_rows($result) != 0)
        */
	$result = $adb->query($dbquery);
	$custquery = "SELECT * FROM vtiger_accountcf WHERE vtiger_accountid = '".$entity_id."'";
        $cust_result = $adb->query($custquery);
	if($adb->num_rows($result) != 0)
	{
		
		$columns='';
		$values='';
		$update='';
                //	$noofrows = mysql_num_rows($result);
                $noofrows = $adb->num_rows($result);
		for($i=0; $i<$noofrows; $i++)
		{
                  //$fldName=mysql_result($result,$i,"fieldlabel");
                  //$colName=mysql_result($result,$i,"column_name");
	$fldName=$adb->query_result($result,$i,"fieldlabel");
			$colName=$adb->query_result($result,$i,"column_name");
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
			//if(isset($_REQUEST['record']) && $_REQUEST['record'] != '' && mysql_num_rows($cust_result) !=0)
                          if(isset($_REQUEST['record']) && $_REQUEST['record'] != '' && $adb->num_rows($cust_result) !=0)
			{
				//Update Block
				if($i == 0)
				{
                                  //$update = $colName.'="'.$fldvalue.'"';
                                        $update = $colName."='".$fldvalue."'";
				}
				else
				{
                                  //$update .= ', '.$colName.'="'.$fldvalue.'"';
                                        $update .= ', '.$colName."='".$fldvalue."'";
				}
			}
			else
			{
				//Insert Block
				if($i == 0)
				{
					$columns='accountid, '.$colName;
					//$values='"'.$entity_id.'", "'.$fldvalue.'"';
                                        $values="'".$entity_id."', '".$fldvalue."'";
				}
				else
				{
					$columns .= ', '.$colName;
					//$values .= ', "'.$fldvalue.'"';
                                        $values .= ", '".$fldvalue."'";
				}
			}
			
				
		}
		//if(isset($_REQUEST['record']) && $_REQUEST['record'] != '' && mysql_num_rows($cust_result) !=0)
                  if(isset($_REQUEST['record']) && $_REQUEST['record'] != '' && $adb->num_rows($cust_result) !=0)
		{
			//Update Block
                        $query = "UPDATE vtiger_accountcf SET ".$update." WHERE vtiger_accountid='".$entity_id."'"; 
			$adb->query($query);
		}
		else
		{
			//Insert Block
			$query = "INSERT INTO vtiger_accountcf (".$columns.") VALUES(".$values.")";
                        $adb->query($query);
		}
		
	}
	$log->debug("Exiting save_customfields method ...");
	// commented by srini - PATCH for saving vtiger_accounts
	/*else
	{
          //if(isset($_REQUEST['record']) && $_REQUEST['record'] != '' && mysql_num_rows($cust_result) !=0)
                  if(isset($_REQUEST['record']) && $_REQUEST['record'] != '' && $adb->num_rows($cust_result) !=0)
		{
			//Update Block
		}
		else
		{
			//Insert Block
			$query = "INSERT INTO vtiger_accountcf (".$columns.") VALUES(".$values.")";
                        $adb->query($query);
			//mysql_query($query);
		}
	}*/	
}
?>
