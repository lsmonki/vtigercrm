<?php
ini_set("memory_limit","32M");
set_time_limit(-1);

echo '<div align = "center"><br><b>Started HTML to UTF8 conversion</b><br></div>';
@ob_flush();

//This function will convert all the html values in the database into utf-8 values
convert_html2utf8_db();

//Displaying the close button at the end of the conversion.
echo '<table width="95%"  border="0" align="center">
	<tr bgcolor="#FFFFFF"><td colspan="2">&nbsp;</td></tr>
	<tr>
	<td colspan="2" align="center">
	<form name="close_migration" method="post" action="index.php">
	<input type="hidden" name="module" value="Settings">
	<input type="hidden" name="action" value="index">
	<input type="submit" name="close" value=" &nbsp;Close&nbsp; " class="crmbutton small cancel" />
	</form>
	</td>
	</tr>
	</table>';


/**
* Function to convert html values to its original character available in a database
* This function can called at any time after the migration
* It get all the tables and its VARCHAR/TEXT/LONGTEXT fields from the DB
* Converts the html-values to its original character and restore it. 
**/
function convert_html2utf8_db()
{
	global $adb,$log;
	//Getting all the tables from the current database.
	$alltables = $adb->get_tables();
	$log->debug("Started HTML to UTF-8 Conversion");
	$values=Array();
	//Tables for which conversion to utf8 not required.
	$skip_tables=Array('vtiger_sharedcalendar', 'vtiger_potcompetitorrel', 'vtiger_users2group', 'vtiger_group2grouprel', 'vtiger_group2role', 'vtiger_group2rs', 'vtiger_campaigncontrel', 'vtiger_campaignleadrel', 'vtiger_cntactivityrel', 'vtiger_crmentitynotesrel', 'vtiger_salesmanactivityrel', 'vtiger_vendorcontactrel', 'vtiger_salesmanticketrel', 'vtiger_seactivityrel', 'vtiger_seticketsrel', 'vtiger_senotesrel', 'vtiger_profile2globalpermissions', 'vtiger_profile2standardpermissions', 'vtiger_profile2field', 'vtiger_role2profile', 'vtiger_profile2utility', 'vtiger_activityproductrel', 'vtiger_pricebookproductrel', 'vtiger_activity_reminder', 'vtiger_actionmapping', 'vtiger_org_share_action2tab', 'vtiger_datashare_relatedmodule_permission', 'vtiger_tmp_read_user_sharing_per', 'vtiger_tmp_read_group_sharing_per', 'vtiger_tmp_write_user_sharing_per', 'vtiger_tmp_write_group_sharing_per', 'vtiger_tmp_read_user_rel_sharing_per', 'vtiger_tmp_read_group_rel_sharing_per', 'vtiger_tmp_write_user_rel_sharing_per', 'vtiger_tmp_write_group_rel_sharing_per', 'vtiger_role2picklist', 'vtiger_freetagged_objects', 'vtiger_tab', 'vtiger_blocks', 'vtiger_group2role', 'vtiger_group2rs');
	
	for($i=0;$i<count($alltables);$i++)
	{
		$table=$alltables[$i];
		if(!in_array($table,$skip_tables))
		{
			//Here selecting all the colums from the table
			$result = $adb->query("SHOW COLUMNS FROM $table");
			while ($row = $adb->fetch_array($result))
			{
				//Getting the primary key column of the table.
				if($row['key'] == 'PRI')
				{
					$values[$table]['key'][]=$row['field'];
				}
				//And Getting columns of type varchar, text and longtext.
				if(stristr($row['type'],'varchar') != '' || stristr($row['type'],'text') != '' || stristr($row['type'],'longtext') != '')
				{
					$values[$table]['columns'][] = $row['field'];
				}
			}
		}
	}
	//Array with tables in the database
	//Eg : Array
	//	(
	//      	[vtiger_account] => Array(
	//              	[key] => accountid,
	//              	[columns] => Array(
	//                      	[0] => accountname,
	//                      	[1] => email,
	//                      	[2] => website,
	//                      	.
	//                      	.
	//                      	.
	//              	)
	//		)
	//		[vtiger_leaddetails] => Array(
	//			[key] => leadid,
	//			[columns] => Array(
	//				[0] => firstname,
	//				[1] => lastname,
	//				[2] => company,
	//				.
	//				.
	//				.
	//			)	
	//      	)
	//		.
	//		.
	//		.
	//	)
	$final_array=$values;
	foreach($final_array as $tablename=>$value)
	{
		//Going to update values in the table.
		$key = $value['key'];
		$cols = $value['columns'];
		if($cols != "" && $key != "")
		{
			if(count($key) > 1)
				$key_list = implode(", ", $key);
			else
				$key_list = $key[0];
				
			if(count($cols) > 1)
				$col_list = implode(", ", $cols);
			else
				$col_list = $cols[0];
			//Getting the records available in the table.
			$query="SELECT $key_list, $col_list FROM $tablename";
			$res1 = $adb->query($query);
			$val = Array();
			$id = Array();
			echo "<br><br>Updating the values in the table <b>".$tablename."</b>...";
			$log->debug("Updating the values in the table :".$tablename);
			//Sending the current status to the browser
			@ob_flush();
			flush();
			for($k=0; $k < $adb->num_rows($res1); $k++)
			{
				$whereStr = "";
				for($l=0; $l < count($key); $l++)
				{
					$id[$l] = $adb->query_result($res1, $k, $key[$l]);
					if($l != 0)
						$whereStr .= " and ";
					$whereStr .= $key[$l]."=?";
				}	
				$updateStr = "";
				for($j=0; $j < count($cols); $j++)
				{
					//Converting the html values to utf8 chars
					//echo "<br>Updating the value of ".$cols[$j]." column with utf8 value";
					$val[$j] = html_to_utf8(decode_html($adb->query_result($res1, $k, $cols[$j])));
					if($j != 0)
						$updateStr .= ", ";
					$updateStr .= $cols[$j]."=?";
				}
				$updateQ = "UPDATE $tablename SET $updateStr where $whereStr";
				//echo "<br>".$updateQ;
				$params = array($val, $id);
				//echo "<pre>";print_r($params);echo "</pre>";
				$adb->pquery($updateQ, $params);
			}
			echo "<br><br>   ===> Update completed for <b>".$tablename."</b> table.";
			//Sending the current status to the browser
			@ob_flush();
			flush();
		}
        }
	echo '<div align = "center"><br><br><b> Conversion completed.</b></div>';
	$log->debug("HTML to UTF-8 Conversion has been completed");
}


?>
