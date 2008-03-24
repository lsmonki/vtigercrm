<?php
/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
   * ("License"); You may not use this file except in compliance with the License
   * The Original Code is:  vtiger CRM Open Source
   * The Initial Developer of the Original Code is vtiger.
   * Portions created by vtiger are Copyright (C) vtiger.
   * All Rights Reserved.
  *
 ********************************************************************************/

$count = 0;
$skip_required_count = 0;

/**	function used to save the records into database
 *	@param array $rows - array of total rows of the csv file
 *	@param array $rows1 - rows to be saved
 *	@param object $focus - object of the corresponding import module
 *	@param int $ret_field_count - total number of fields(columns) available in the csv file
 *	@param int $col_pos_to_field - field position in the mapped array
 *	@param int $start - starting row count value to import
 *	@param int $recordcount - count of records to be import ie., number of records to import
 *	@param string $module - import module
 *	@param int $totalnoofrows - total number of rows available
 *	@param int $skip_required_count - number of records skipped
 	This function will redirect to the ImportStep3 if the available records is greater than the record count (ie., number of records import in a single loop) otherwise (total records less than 500) then it will be redirected to import step last
 */
function InsertImportRecords($rows,$rows1,$focus,$ret_field_count,$col_pos_to_field,$start,$recordcount,$module,$totalnoofrows,$skip_required_count)
{
	global $current_user;
	global $adb;
	global $mod_strings;

// MWC ** Getting vtiger_users
$temp = get_user_array(FALSE);
foreach ( $temp as $key=>$data)
	$my_users[$data] = $key;
p(print_r(my_users,1));
$adb->println("Users List : ");
$adb->println($my_users);

if($start == 0)
{
	$_SESSION['totalrows'] = $rows;
	$_SESSION['return_field_count'] = $ret_field_count;
	$_SESSION['column_position_to_field'] = $col_pos_to_field;
}
$ii = $start;
// go thru each row, process and save()
foreach ($rows1 as $row)
{
	$adb->println("Going to Save the row ".$ii." =====> ");
	$adb->println($row);
	global $mod_strings;

	$do_save = 1;
	//MWC
	$my_userid = $current_user->id;

	//If we want to set default values for some fields for each entity then we have to set here
	if($module == 'Products')//discontinued is not null. if we unmap active, NULL will be inserted and query will fail
		$focus->column_fields['discontinued'] = 'on';

	for($field_count = 0; $field_count < $ret_field_count; $field_count++)
	{
		p("col_pos[".$field_count."]=".$col_pos_to_field[$field_count]);

		if ( isset( $col_pos_to_field[$field_count]) )
		{
			p("set =".$field_count);
			if (! isset( $row[$field_count]) )
			{
				continue;
			}

			p("setting");

			// TODO: add check for user input
			// addslashes, striptags, etc..
			$field = $col_pos_to_field[$field_count];

			//picklist function is added to avoid duplicate picklist entries
			$pick_orginal_val = getPicklist($field,$row[$field_count]);

			if($pick_orginal_val != null)
			{
				$focus->column_fields[$field]=$pick_orginal_val;
			}
			elseif (substr(trim($field), 0, 3) == "CF_") 
        		{
				p("setting custfld".$field."=".$row[$field_count]);
				$resCustFldArray[$field] = $row[$field_count]; 
        		}
			//MWC
			elseif ( $field == "assignedto" || $field == "assigned_user_id" )
			{
				//Here we are assigning the user id in column fields, so in function assign_user (ImportLead.php and ImportProduct.php files) we should use the id instead of user name when query the user
				//or we can use $focus->column_fields['smownerid'] = $my_users[$row[$field_count]];
				$focus->column_fields[$field] = $my_users[$row[$field_count]];	
				p("setting my_userid=$my_userid for user=".$row[$field_count]);
			}
			else
			{
				//$focus->$field = $row[$field_count];
				$focus->column_fields[$field] = $row[$field_count];
				p("Setting ".$field."=".$row[$field_count]);
			}
			
		}

	}
	if($focus->column_fields['notify_owner'] == '')
	{
		$focus->column_fields['notify_owner'] = '0';
	}	
	if($focus->column_fields['reference'] == '')
	{
		$focus->column_fields['reference'] = '0';
	}
	if($focus->column_fields['emailoptout'] == '')
	{
		$focus->column_fields['emailoptout'] = '0';
	}
	if($focus->column_fields['donotcall'] == '')
	{
		$focus->column_fields['donotcall'] = '0';
	}
	if($focus->column_fields['discontinued'] == '')
	{
		$focus->column_fields['discontinued'] = '0';
	}
	if($focus->column_fields['active'] == '')
	{
		$focus->column_fields['active'] = '0';
	}
	p("setting done");
	
	p("do save before req vtiger_fields=".$do_save);

	$adb->println($focus->required_fields);

	foreach ($focus->required_fields as $field=>$notused) 
	{ 
		$fv = trim($focus->column_fields[$field]);
		if (! isset($fv) || $fv == '') 
		{
		       p("fv ".$field." not set");	
			$do_save = 0; 
			$skip_required_count++; 
			break; 
		} 
	}

	p("do save=".$do_save);

	if ($do_save)
	{
		p("saving..");

	
		if ( ! isset($focus->column_fields["assigned_user_id"]) || $focus->column_fields["assigned_user_id"]=='')
		{
			//$focus->column_fields["assigned_user_id"] = $current_user->id;
			//MWC
			$focus->column_fields["assigned_user_id"] = $my_userid;
		}	

		// now do any special processing for ex., map account with contact and potential
		$focus->process_special_fields();
	
		$focus->save($module);
		//$focus->saveentity($module);
		$return_id = $focus->id;

		if(count($resCustFldArray)>0)
		{

			if($_REQUEST['module'] == 'Contacts')
			{
				$_REQUEST['module']='contactdetails';
			}
			$dbquery="select * from vtiger_field where vtiger_tablename=?";
			$custresult = $adb->pquery($dbquery, array($_REQUEST['module']));
			if($adb->num_rows($custresult) != 0)
			{
				if (! isset( $_REQUEST['module'] ) || $_REQUEST['module'] == 'Contacts')
				{
					$columns = 'contactid';
					$custTabName = 'contactscf';
				}
				else if ( $_REQUEST['module'] == 'Accounts')
				{
					$columns = 'accountid';
					$custTabName = 'accountscf';	
				}
				else if ( $_REQUEST['module'] == 'Potentials')
				{
					$columns = 'potentialid';
					$custTabName = 'potentialscf';
				}

				else if ( $_REQUEST['module'] == 'Products')
				{
					$columns = 'productid';
					$custTabName = 'productscf';
				}
				$noofrows = $adb->num_rows($custresult);
				$params = array($focus->id);
				
				for($j=0; $j<$noofrows; $j++)
				{
					$colName=$adb->query_result($custresult,$j,"columnname");
					if(array_key_exists($colName, $resCustFldArray))
					{
						$value_colName = $resCustFldArray[$colName];

						$columns .= ', '.$colName;
						array_push($params, $value_colName);
					}
				}
				
				$insert_custfld_query = 'insert into '.$custTabName.' ('.$columns.') values('. generateQuestionMarks($params) .')';
				$adb->pquery($insert_custfld_query, $params);

			}
		}	
		
		$last_import = new UsersLastImport();		
		$last_import->assigned_user_id = $current_user->id;
		$last_import->bean_type = $_REQUEST['module'];
		$last_import->bean_id = $focus->id;
		$last_import->save();
		array_push($saved_ids,$focus->id);
		$count++;
	}
$ii++;	
}

$_REQUEST['count'] = $ii;
if(isset($_REQUEST['module']))
	$modulename = $_REQUEST['module'];

$end = $start+$recordcount;
$START = $start + $recordcount;
$RECORDCOUNT = $recordcount;

if($end >= $totalnoofrows)
{
	$module = 'Import';//$_REQUEST['module'];
	$action = 'ImportSteplast';
	//exit;
	$imported_records = $ii - $skip_required_count;
	if($imported_records == $ii)
		$skip_required_count = 0;
	 $message= urlencode("<b>".$mod_strings['LBL_SUCCESS']."</b>"."<br><br>" .$mod_strings['LBL_SUCCESS_1']."  $imported_records" ."<br><br>" .$mod_strings['LBL_SKIPPED_1']."  $skip_required_count " );
}
else
{
	$module = 'Import';
	$action = 'ImportStep3';
}
?>

<script>
setTimeout("b()",1000);
function b()
{
	document.location.href="index.php?action=<?php echo $action?>&module=<?php echo $module?>&modulename=<?php echo $modulename?>&startval=<?php echo $end?>&recordcount=<?php echo $RECORDCOUNT?>&noofrows=<?php echo $totalnoofrows?>&message=<?php echo $message?>&skipped_record_count=<?php echo $skip_required_count?>&parenttab=<?php echo $_SESSION['import_parenttab']?>";
}
</script>

<?php
$_SESSION['import_display_message'] = '<br>'.$start.' '.$mod_strings['to'].' '.$end.' '.$mod_strings['of'].' '.$totalnoofrows.' '.$mod_strings['are_imported_succesfully'];
//return $_SESSION['import_display_message'];
}
?>

