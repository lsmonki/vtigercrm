<?php

/*********************************************************************************
 *** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 **  ("License"); You may not use this file except in compliance with the License
 ** The Original Code is:  vtiger CRM Open Source
 ** The Initial Developer of the Original Code is vtiger.
 ** Portions created by vtiger are Copyright (C) vtiger.
 ** All Rights Reserved.
 **
 *********************************************************************************/

define("RB_RECORD_DELETED", 'delete');
define("RB_RECORD_INSERTED", 'insert');
define("RB_RECORD_UPDATED", 'update');

function getRbListViewHeader($moduleDetails_array)
{
	global $log,$adb, $app_strings, $mod_strings;
	$return_header=array();
	if(sizeOf($moduleDetails_array) != 0)
	{
		foreach($moduleDetails_array as $colnam=>$tablename)
		{
			$name = $moduleDetails_array[$colnam]['fieldlabel'];
			$header[]= getTranslatedString($name);
		}
	}
	return $header;
}

function setListFieldsforCV($oCV,$moduleDetails_array)
{
	global $log,$adb;
	$return_header=array();
	if(sizeOf($moduleDetails_array) != 0)
	{
		foreach($moduleDetails_array as $colnam=>$tablename)
		{
			$list_fields[$tablename[fieldlabel]] = Array($tablename[tableName]=>$tablename[columnname]);
			$list_fields_name[$tablename[fieldlabel]] = $tablename[fieldname];
		}
	}
	$oCV->list_fields = $list_fields;
	$oCV->list_fields_name = $list_fields_name;
	return $oCV;

}

//For Search....
$rb_column_array=array('accountid','contact_id','product_id','campaignid','quoteid','vendorid','potentialid','salesorderid','vendor_id','contactid');
$rb_table_col_array=array('vtiger_account.accountname','vtiger_contactdetails.firstname,vtiger_contactdetails.lastname','vtiger_products.productname','vtiger_campaign.campaignname','vtiger_quotes.subject','vtiger_vendor.vendorname','vtiger_potential.potentialname','vtiger_salesorder.subject','vtiger_vendor.vendorname','vtiger_contactdetails.firstname,vtiger_contactdetails.lastname');



function getValuesforRBColumns($column_name,$search_string)
{
	global $log,$adb;
	$log->debug("Entering getValuesforRBColumns(".$column_name.",".$search_string.") method ...");
	global $rb_column_array,$rb_table_col_array;
	$sql = "select concat(tablename,':',fieldname) as tablename from vtiger_entityname where entityidfield='$column_name' or entityidcolumn='$column_name'";
	$result = $adb->query($sql);
	$tablename  = $adb->query_result($result,0,'tablename');
	$num_rows = $adb->num_rows($adb->query($sql));
	if($num_rows >= 1)
	{

		$val = $tablename;
		$explode_column=explode(",",$val);
		$x=count($explode_column);
		if($x >= 1 )
		{
			$main_tablename = explode(':',$explode_column[0]);
			$where=" $explode_column[0] like '". formatForSqlLike($search_string) ."' or $main_tablename[0]$main_tablename[1] like '".formatForSqlLike($search_string) ."'";

		}
	
	}
       	$log->debug("Exiting getValuesforRBColumns method ...");
	return $where;
}
function RBSearch($module)
{
	global $log;
        $log->debug("Entering RBSearch(".$module.") method ...");
	$url_string='';	
	if(isset($_REQUEST['search_field']) && $_REQUEST['search_field'] !="")
        {
                $search_column=$_REQUEST['search_field'];
        }
        if(isset($_REQUEST['search_text']) && $_REQUEST['search_text']!="")
        {
                $search_string=addslashes(ltrim(rtrim($_REQUEST['search_text'])));
        }
        if(isset($_REQUEST['searchtype']) && $_REQUEST['searchtype']!="")
        {


                $search_type=$_REQUEST['searchtype'];

                if($search_type == "BasicSearch")
		{
			$where=basicRBsearch($module,$search_column,$search_string);
                }
                else //Global Search
                {

                }

		$url_string = "&search_field=".$search_column."&search_text=".$search_string."&searchtype=BasicSearch";
		if(isset($_REQUEST['type']) && $_REQUEST['type'] != '')
			$url_string .= "&type=".$_REQUEST['type'];
		return $where."#@@#".$url_string;
		$log->debug("Exiting RBSearch method ...");
        }

}

function basicRBsearch($module,$search_field,$search_string)
{
	 global $log;
         $log->debug("Entering basicRBsearch(".$module.",".$search_field.",".$search_string.") method ...");
	global $adb;
	global $rb_column_array,$rb_table_col_array;
	if($search_field =='crmid')
	{
		$column_name='crmid';
		$table_name='vtiger_entity';
		$where="$table_name.$column_name like '".formatForSqlLike($search_string)."'";	
	}else
	{	
		//Check added for tickets by accounts/contacts in dashboard
		$search_field_first = $search_field;
		if($module=='HelpDesk' && ($search_field == 'contactid' || $search_field == 'account_id'))
		{
			$search_field = "parent_id";
		}
		//Check ends
		
		$tabid = getTabid($module);
		$qry="select vtiger_field.columnname,tablename from vtiger_field where tabid=$tabid and (fieldname='".$search_field."' or columnname='".$search_field."')";
		$result = $adb->query($qry);
		$noofrows = $adb->num_rows($result);
		if($noofrows!=0)
		{
			$column_name=$adb->query_result($result,0,'columnname');

			//Check added for tickets by accounts/contacts in dashboard
			if ($column_name == 'parent_id')
		        {
				if ($search_field_first	== 'account_id') $search_field_first = 'accountid';
				if ($search_field_first	== 'contactid') $search_field_first = 'contact_id';
				$column_name = $search_field_first;
			}
			//Check ends
			$table_name=$adb->query_result($result,0,'tablename');
			if($table_name == "vtiger_crmentity" && $column_name == "smownerid")
			{
				$where = get_usersid($table_name,$column_name,$search_string);
			}
			elseif($table_name == "vtiger_activity" && $column_name == "status")
			{
				$where="$table_name.$column_name like '".formatForSqlLike($search_string)."' or vtiger_activity.eventstatus like '".formatForSqlLike($search_string)."'";
			}
			elseif($table_name == "vtiger_pricebook" && $column_name == "active")
			{
				if(stristr('yes',$search_string))
				{
					$where="$table_name.$column_name = 1";
				}
				else if(stristr('no',$search_string))
				{
					$where="$table_name.$column_name is NULL";
				}
				else
				{
					//here where condition is added , since the $where query must go as differently so that it must give an empty set, either than Yes or No...
					$where="$table_name.$column_name = 2";
				}
			}
			elseif($table_name == "vtiger_activity" && $column_name == "status")
			{
				$where="$table_name.$column_name like '%".$search_string."%' or vtiger_activity.eventstatus like '".formatForSqlLike($search_string)."'";
			}
			$sql = "select concat(tablename,':',fieldname) as tablename from vtiger_entityname where entityidfield='$column_name' or entityidcolumn='$column_name'"; 
			$result = $adb->query_result($adb->query($sql),0,'tablename');
			$no_of_rows = $adb->num_rows($adb->query($sql));
			if($no_of_rows >= 1)
			{
				$where = getValuesforRBColumns($column_name,$search_string);
			}
			else if(($column_name != "status" || $table_name !='vtiger_activity')  && ($table_name != 'vtiger_crmentity' || $column_name != 'smownerid' ) && ($table_name != 'vtiger_pricebook' || $column_name != 'active') )
			{
				$tableName=explode(":",$table_name);
				$where="$table_name.$column_name like '".formatForSqlLike($search_string) ."'";
			}
		}
	}
	if($_REQUEST['type'] == 'entchar')
	{
		$search = array('Un Assigned','%','like');
		$replace = array('','','=');
		$where= str_replace($search,$replace,$where);
	}
	if($_REQUEST['type'] == 'alpbt')
	{
	        $where = str_replace_once("%", "", $where);
	}
	$log->debug("Exiting basicRBsearch method ...");
	return $where;

}

function getRbListViewDetails($module)
{
	global $log,$adb,$current_user;
	$rb_headers=array();
	$mod_label=array();
	$module_details=array();
	$tabid = getTabid($module);
	$default_headers="select vtiger_cvcolumnlist.columnname FROM vtiger_cvcolumnlist inner join vtiger_customview on vtiger_customview.cvid = vtiger_cvcolumnlist.cvid where vtiger_customview.viewname='All' and vtiger_customview.entitytype=? order by columnindex";
	$result=$adb->pquery($default_headers, array($module));
	$noOfRows=$adb->num_rows($result);
	if($noOfRows > 0)
	{

		$fld_list = array();
		for($i=0; $i<$noOfRows;$i++)
		{
			$module_data[$i] = $adb->query_result($result,$i,"columnname");
			list($module_details['tableName'],$module_details['columnName'],$module_details['fieldName'],$module_details['fldLabel'],$module_details['fldType']) = explode(':',$module_data[$i]);
			$mod_details[]=$module_details;
			$splt_label=explode("_",$module_details['fldLabel'],2);	
			$strrep = str_replace("_"," ",$splt_label[1]);
			
			$module_details['fldLabel'] = $strrep;
			$fld_list[$i] = $module_details['fieldName'];
			$mod_label[$i]=$module_details;

		}
		$query = "SELECT uitype, vtiger_field.columnname, fieldname, fieldlabel, tablename
			FROM vtiger_field
			INNER JOIN vtiger_tab ON vtiger_tab.tabid = vtiger_field.tabid
			INNER JOIN vtiger_customview ON vtiger_customview.entitytype = vtiger_tab.name
			INNER JOIN vtiger_cvcolumnlist ON vtiger_cvcolumnlist.columnname like concat('%:', fieldname, ':%') AND vtiger_cvcolumnlist.cvid = vtiger_customview.cvid
			WHERE vtiger_field.tabid = ? AND vtiger_customview.viewname='All' 
			AND fieldname IN (". generateQuestionMarks($fld_list) .") ORDER BY vtiger_cvcolumnlist.columnindex";
		$params = array($tabid, $fld_list);
		
		$result = $adb->pquery($query, $params);
		
		$tempvalues_array=array();
		$num_rows=$adb->num_rows($result);
		for($i=0;$i<$num_rows;$i++)
		{
			$temp=array();
			$uitype=$adb->query_result($result,$i,'uitype');
			$columnname=$adb->query_result($result,$i,'columnname');
			$field_name=$adb->query_result($result,$i,'fieldname');
			$tab_name=$adb->query_result($result,$i,'tablename');
			$field_label=$adb->query_result($result,$i,'fieldlabel');

			if (getFieldVisibilityPermission($module, $current_user->id, $field_name) != '0') {
				continue;
			}
			
			$temp['ui_type'] = $uitype;
			$temp['columnname']=$columnname;
			$temp['fieldname'] = $field_name;
			$temp['fieldlabel']=$field_label;
			$temp['tableName']=$tab_name;

			//handled specially for the email module---  coz,the field label of this module is Emali_Sender and the column name is smownerid .since we are taking the values FROM vtiger_field we are getting the field label as Assinged To.
			if($module == 'Emails' && $temp['fieldlabel'] == 'Assigned To')
			{
				$temp['fieldlabel']='Sender';
				$temp['columnname']='';
				$temp['tablename']='';
			}
			$tempvalues_array[]=$temp;
		}
		
		foreach($tempvalues_array as $key=>$value)
		{
			if(($value['ui_type'] == 73 || $value['ui_type'] == 51 || $value['ui_type'] == 50) && $value['fieldname']=='account_id')
			{
				$tempvalues_array[$key]['columnname'] = 'accountname';
				$tempvalues_array[$key]['tableName'] = 'vtiger_account';
			}
			if($value['ui_type'] == 76 && $value['fieldname']=='potential_id')
			{
				$tempvalues_array[$key]['columnname'] = 'potentialname';
				$tempvalues_array[$key]['tableName'] = 'vtiger_potential';
			}

			if($value['ui_type'] == 57 && $value['fieldname']=='contact_id')
			{
				$tempvalues_array[$key]['columnname'] = 'lastname';
				$tempvalues_array[$key]['tableName'] = 'vtiger_contactdetails';
			}

			/*if($value['ui_type'] == 59 && $value['fieldname']=='product_id')
			{
				$tempvalues_array[$key]['columnname'] = 'productname';
				$tempvalues_array[$key]['tableName'] = 'vtiger_products';
			}*/
			if($value['ui_type'] == 81 && $value['fieldname']=='vendor_id')
			{
				$tempvalues_array[$key]['columnname'] = 'vendorname';
				$tempvalues_array[$key]['tableName'] = 'vtiger_vendor';
			}
			if($value['ui_type'] == 80 && $value['fieldname']=='salesorder_id')
			{
				$tempvalues_array[$key]['columnname'] = 'subject as sosubject';
				$tempvalues_array[$key]['tableName'] = 'vtiger_salesorder';
			}
			if(($value['ui_type'] == 62  || $value['ui_type'] == 66 || $value['ui_type'] == 54 ||$value['ui_type'] == 100 || $value['ui_type'] == 68)&& $value['fieldname']=='parent_id')
			{
				$tempvalues_array[$key]['columnname'] = "";
				$tempvalues_array[$key]['tableName'] = "";
			}
			if($value['ui_type'] == 78 && $value['fieldname'] == 'quote_id')
			{
				$tempvalues_array[$key]['columnname'] = 'subject as qosubject';
				$tempvalues_array[$key]['tableName'] = 'vtiger_quotes';

			}	
		}
		return $tempvalues_array;
	}
}

function getRbListViewEntries($list_result,$lv_array,$module)
{
	global $adb;
	$lv_val = array();
	for($j=0;$j<$adb->num_rows($list_result);$j++)
	{
		$crmid = $adb->query_result($list_result,$j,'crmid');
		for($k=0;$k<count($lv_array);$k++)
		{
			if($lv_array[$k]['columnname'] == 'subject as sosubject')
			{
				$lv_array[$k]['columnname'] = substr('subject as sosubject' , 11);
			}
			if($lv_array[$k]['columnname'] == 'subject as qosubject')
			{
				$lv_array[$k]['columnname'] = substr('subject as qosubject' , 11);
			}
			if(($lv_array[$k]['ui_type'] == 62 || $lv_array[$k]['ui_type'] == 54 || $lv_array[$k]['ui_type'] == 100 ||$lv_array[$k]['ui_type'] == 66 || $lv_array[$k]['ui_type'] == 68) && $lv_array[$k]['fieldname'] == 'parent_id') 
			{
				$lv_array[$k]['columnname'] = 'relatedname';
			}
			//--------------special case fro email module---the uitype for email will only come as uitype=53 and fieldname=assinger_user_id.For the rest of the module we will handled it in the main formation of the array.ie in the "unction getRbListViewDetails()"..............//
			//
			if($lv_array[$k]['ui_type'] == 53 && $lv_array[$k]['fieldname'] == 'assigned_user_id' && $lv_array[$k]['columnname'] == '')
			{
				$lv_array[$k]['columnname'] = 'sender';
			}
			
			// Pick the value for the column
			$lv_val[$crmid][$lv_array[$k]['columnname']] = $adb->query_result($list_result,$j,$lv_array[$k]['columnname']);

			if($lv_array[$k]['ui_type'] == 53 && $lv_array[$k]['columnname'] == 'smownerid')
			{
				if($lv_val[$crmid][$lv_array[$k]['columnname']] != 0) {
					$lv_val[$crmid][$lv_array[$k]['columnname']]= getUserName($lv_val[$crmid][$lv_array[$k]['columnname']]);
				}
				else {
					$group_info = getGroupName($crmid, $module); 
					$lv_val[$crmid][$lv_array[$k]['columnname']]= $group_info[0];
				}
			}
			if($lv_array[$k]['ui_type'] == 15 && $lv_array[$k]['columnname'] == 'status' && $lv_val[$crmid][$lv_array[$k]['columnname']] == '')
			{

				$lv_val[$crmid][$lv_array[$k]['columnname']]=$adb->query_result($list_result,$j,'eventstatus');
			}
			// the value for the column "active" in pricebook will be 1 and NULL . So we are replacing here to display as yes or no..
			if($module=='PriceBooks')
			{	
				if($lv_val[$crmid]['active'] == 1)
				{
					$lv_val[$crmid]['active']='Yes';
				}
				else if($lv_val[$crmid]['active'] == 0)
				{
					$lv_val[$crmid]['active']='No';
				}
			}
			if($lv_array[$k]['columnname'] == 'relatedname' && $lv_val[$crmid][$lv_array[$k]['columnname']] == '' && ($module=='HelpDesk' || $module == 'Documents' || $module == 'Calendar')
				|| $lv_array[$k]['columnname'] == 'product_id' && $module == 'Faq')
			{
				$rel_name = getRelatedToNames($module,$crmid);
				$lv_val[$crmid][$lv_array[$k]['columnname']] = "<span style=\"color:green\">$rel_name</span>";
			}
			else if($lv_array[$k]['columnname'] == 'relatedname' && $lv_val[$crmid][$lv_array[$k]['columnname']] != '' && ($module=='HelpDesk' || $module == 'Documents' || $module == 'Calendar'))
			{
				$rel_name = $lv_val[$crmid][$lv_array[$k]['columnname']];
				$lv_val[$crmid][$lv_array[$k]['columnname']] = "<span style=\"color:red\">$rel_name</span>";
			}
			if($lv_array[$k]['columnname'] == 'accountname' && $lv_val[$crmid][$lv_array[$k]['columnname']] == '' && ($module=='Quotes' || $module == 'SalesOrder' || $module == 'Invoice'  || $module == 'Contacts' || $module == 'Potentials'))
			{
				$lv_val[$crmid][$lv_array[$k]['columnname']]=$adb->query_result($list_result,$j,'main_accountname');
					$acc_name = $lv_val[$crmid][$lv_array[$k]['columnname']];
				//to get the related account Name FROM the backup Table
				
				if($module == 'Contacts')
					$lv_val[$crmid][$lv_array[$k]['columnname']] = "<span style=\"color:green\">$acc_name</span>";
				else
					$lv_val[$crmid][$lv_array[$k]['columnname']] = "<span style=\"color:green\">$acc_name</span>";

			}
			else if ($lv_array[$k]['columnname'] == 'accountname' && $lv_val[$crmid][$lv_array[$k]['columnname']] != '' && ($module=='Quotes' || $module == 'SalesOrder'  || $module == 'Contacts' || $module == 'Potentials'))
			{
				$acc_name = $lv_val[$crmid][$lv_array[$k]['columnname']];
				if($module == 'Contacts')
					$lv_val[$crmid][$lv_array[$k]['columnname']] = "<span style=\"color:red\"> $acc_name</span>";
				else
					$lv_val[$crmid][$lv_array[$k]['columnname']] = "<span style=\"color:blue\"> $acc_name</span>";
			}
			if($lv_array[$k]['columnname'] == 'potentialname' && $lv_val[$crmid][$lv_array[$k]['columnname']] == '' && $module == 'Quotes')
			{
				$lv_val[$crmid][$lv_array[$k]['columnname']]=$adb->query_result($list_result,$j,'main_potentialname');
				$potential_name = $lv_val[$crmid][$lv_array[$k]['columnname']];
				$lv_val[$crmid][$lv_array[$k]['columnname']] = "<span style=\"color:green\">$potential_name</span>";

			}

			else if($lv_array[$k]['columnname'] == 'potentialname' && $lv_val[$crmid][$lv_array[$k]['columnname']] != '' && $module == 'Quotes')
			{
				$potential_name = $lv_val[$crmid][$lv_array[$k]['columnname']];
				$lv_val[$crmid][$lv_array[$k]['columnname']] = "<span style=\"color:red\"> $potential_name</span>";
			}
			if($lv_array[$k]['columnname'] == 'sosubject' && $lv_val[$crmid][$lv_array[$k]['columnname']] == "" && $module == 'Invoice')
			{

				$lv_val[$crmid][$lv_array[$k]['columnname']]=$adb->query_result($list_result,$j,'main_subject');
				$so_subject = $lv_val[$crmid][$lv_array[$k]['columnname']];
				$lv_val[$crmid][$lv_array[$k]['columnname']] = "<span style=\"color:green\">$so_subject</span>";
			}
			else if($lv_array[$k]['columnname'] == 'sosubject' && $lv_val[$crmid][$lv_array[$k]['columnname']] != "" && $module == 'Invoice')
			{
				$so_subject = $lv_val[$crmid][$lv_array[$k]['columnname']];
				$lv_val[$crmid][$lv_array[$k]['columnname']] = "<span style=\"color:red\">$so_subject</span>";
			}
			if($lv_array[$k]['columnname'] == 'vendorname' && $lv_val[$crmid][$lv_array[$k]['columnname']]=='' && $module=='PurchaseOrder')
			{
				$lv_val[$crmid][$lv_array[$k]['columnname']]=$adb->query_result($list_result,$j,'main_vendorname');
				$vendor_name = $lv_val[$crmid][$lv_array[$k]['columnname']];
				$lv_val[$crmid][$lv_array[$k]['columnname']] = "<span style=\"color:green\">$vendor_name</span>";
			}
			else if($lv_array[$k]['columnname'] == 'vendorname' && $lv_val[$crmid][$lv_array[$k]['columnname']] !='' && $module=='PurchaseOrder')
			{
				$vendor_name = $lv_val[$crmid][$lv_array[$k]['columnname']];
				$lv_val[$crmid][$lv_array[$k]['columnname']] = "<span style=\"color:blue\">$vendor_name</span>";
			}
			if($lv_array[$k]['columnname'] == 'qosubject' && $lv_val[$crmid][$lv_array[$k]['columnname']]=='' && $module=='SalesOrder')
			{
				$lv_val[$crmid][$lv_array[$k]['columnname']]=$adb->query_result($list_result,$j,'main_subject');
				$qo_subject =$lv_val[$crmid][$lv_array[$k]['columnname']];
				$lv_val[$crmid][$lv_array[$k]['columnname']] = "<span style=\"color:green\">$qo_subject</span>";
			}
			else if($lv_array[$k]['columnname'] == 'qosubject' && $lv_val[$crmid][$lv_array[$k]['columnname']]!='' && $module=='SalesOrder')
			{
				$qo_subject = $lv_val[$crmid][$lv_array[$k]['columnname']];
				$lv_val[$crmid][$lv_array[$k]['columnname']] = "<span style=\"color:red\">$qo_subject</span>";
			}
			if($lv_array[$k]['columnname'] == 'productname' && $lv_val[$crmid][$lv_array[$k]['columnname']]=='' && $module=='Faq')
			{

				$lv_val[$crmid][$lv_array[$k]['columnname']]=$adb->query_result($list_result,$j,'main_productname');
				$productname =  $lv_val[$crmid][$lv_array[$k]['columnname']];
				$lv_val[$crmid][$lv_array[$k]['columnname']] = "<span style=\"color:green\">$productname</span>";
			}
			else if($lv_array[$k]['columnname'] == 'productname' && $lv_val[$crmid][$lv_array[$k]['columnname']] != '' && $module=='Faq')
			{
				$productname = $lv_val[$crmid][$lv_array[$k]['columnname']];
				$lv_val[$crmid][$lv_array[$k]['columnname']] = "<span style=\"color:blue\">$productname</span>";
			}
			if($lv_array[$k]['columnname'] == 'lastname' && $lv_val[$crmid][$lv_array[$k]['columnname']]=='' && ($module=='Documents'))
			{
				$lv_val[$crmid][$lv_array[$k]['columnname']]=$adb->query_result($list_result,$j,'main_lastname');
				$contactname = $lv_val[$crmid][$lv_array[$k]['columnname']];
				$lv_val[$crmid][$lv_array[$k]['columnname']] = "<span style=\"color:green\">$contactname</span>";
			}
			else if($lv_array[$k]['columnname'] == 'lastname' && $lv_val[$crmid][$lv_array[$k]['columnname']] != '' && ($module=='Documents'))
			{
				$contactname = $lv_val[$crmid][$lv_array[$k]['columnname']];
				$lv_val[$crmid][$lv_array[$k]['columnname']] = "<span style=\"color:red\">$contactname</span>";
			}	
		}
	}
	return $lv_val;
}

function restore_related_records($record, $module) {
	global $adb;
	
	$result = $adb->pquery("select * from vtiger_relatedlists_rb where entityid = ?", array($record));
	$numRows = $adb->num_rows($result);
	for($i=0; $i < $numRows;$i++)
	{
		$action = $adb->query_result($result,$i,"action");
		$rel_table = $adb->query_result($result,$i,"rel_table");
		$rel_column = $adb->query_result($result,$i,"rel_column");
		$ref_column = $adb->query_result($result,$i,"ref_column");
		$related_crm_ids = $adb->query_result($result,$i,"related_crm_ids");
		restore_record($record, $action, $rel_table, $rel_column, $ref_column, $related_crm_ids, $module);
	}
	
}

function restore_record($record, $action, $rel_table, $rel_column, $ref_column, $related_crm_ids, $module) {
	global $adb;
	
	$sql = '';
	$params = array();
	if(strtolower($action) == RB_RECORD_UPDATED) {
		$related_ids = explode(",", $related_crm_ids);
		if($rel_table == 'vtiger_crmentity' && $rel_column == 'deleted') {
			$sql = "update $rel_table set $rel_column = 0 where $ref_column in (". generateQuestionMarks($related_ids) . ")";
			array_push($params, $related_ids);
		} else {
			$sql = "update $rel_table set $rel_column = ? where $rel_column is null and $ref_column in (". generateQuestionMarks($related_ids) . ")";
			array_push($params, $record, $related_ids);			
		}
	} elseif (strtolower($action) == RB_RECORD_DELETED) {
		if ($rel_table == 'vtiger_seproductrel') {
			$sql = "insert into $rel_table($rel_column, $ref_column, 'setype') values (?,?,?)";
			array_push($params, $record, $related_crm_ids, $module);
		} else {
			$sql = "insert into $rel_table($rel_column, $ref_column) values (?,?)";
			array_push($params, $record, $related_crm_ids);
		}
	}
	
	if ($sql != '') {
		$adb->pquery($sql, $params);
	}
}

function getRelatedToNames($module,$crmid)
{
	global $adb,$log;
	$log->debug("Entering into the function getRelatedTo(".$module.",".$crmid.".......)");
	if($module == 'HelpDesk')
	{
		$qry = "select parent_id FROM vtiger_troubletickets where ticketid=?";
		$result = $adb->pquery($qry, array($crmid));
		$parent_id = $adb->query_result($result,0,'parent_id');

	}
	else if($module == 'Calendar')
	{
		$qry = "select crmid FROM vtiger_seactivityrel where activityid=?";
		$result = $adb->pquery($qry, array($crmid));
		$parent_id = $adb->query_result($result,0,'crmid');
	}
	else if($module == 'Documents')
	{
		$qry = "select crmid FROM vtiger_senotesrel where notesid=?";
		$result = $adb->pquery($qry, array($crmid));
		$parent_id = $adb->query_result($result,0,'crmid');
	}
	else if($module == 'Faq')
	{
		$qry = "select product_id FROM vtiger_faq where id=?";
		$result = $adb->pquery($qry, array($crmid));
		$parent_id = $adb->query_result($result,0,'product_id');
	}
	if($parent_id != '')
	{
		$qry= "select setype FROM vtiger_crmentity where crmid =?";
		$setype_result = $adb->pquery($qry, array($parent_id));
		$parent_module = $adb->query_result($setype_result,0,'setype');
		if($parent_module != '')
		{
			$entity_val_array = getEntityName($parent_module,Array($parent_id));
			$entityname = $entity_val_array[$parent_id];
			return $entityname;
		}
	}

}

?>
