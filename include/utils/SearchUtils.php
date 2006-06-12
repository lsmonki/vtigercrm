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


require_once('include/database/PearDatabase.php');
require_once('include/ComboUtil.php'); //new
require_once('include/utils/CommonUtils.php'); //new
	
$column_array=array('accountid','contact_id');
$table_col_array=array('vtiger_account.accountname','vtiger_contactdetails.firstname,vtiger_contactdetails.lastname');

/**This function is used to get the list view header values in a list view during search
*Param $focus - module object
*Param $module - module name
*Param $sort_qry - sort by value
*Param $sorder - sorting order (asc/desc)
*Param $order_by - order by
*Param $relatedlist - flag to check whether the header is for listvie or related list
*Param $oCv - Custom view object
*Returns the listview header values in an array
*/

function getSearchListHeaderValues($focus, $module,$sort_qry='',$sorder='',$order_by='',$relatedlist='',$oCv='')
{
	global $log;
	$log->debug("Entering getSearchListHeaderValues(".$focus.",". $module.",".$sort_qry.",".$sorder.",".$order_by.",".$relatedlist.",".$oCv.") method ...");
        global $adb;
        global $theme;
        global $app_strings;
        global $mod_strings,$current_user;

        $arrow='';
        $qry = getURLstring($focus);
        $theme_path="themes/".$theme."/";
        $image_path=$theme_path."images/";
        $search_header = Array();

        //Get the vtiger_tabid of the module
        //require_once('include/utils/UserInfoUtil.php')
        $tabid = getTabid($module);
        //added for vtiger_customview 27/5
        if($oCv)
        {
                if(isset($oCv->list_fields))
		{
                        $focus->list_fields = $oCv->list_fields;
                }
        }
	//Added to reduce the no. of queries logging for non-admin vtiger_users -- by Minnie-start
	$field_list ='(';
	$j=0;
	require('user_privileges/user_privileges_'.$current_user->id.'.php');
	foreach($focus->list_fields as $name=>$tableinfo)
	{
		$fieldname = $focus->list_fields_name[$name];
		if($oCv)
		{
			if(isset($oCv->list_fields_name))
			{
				$fieldname = $oCv->list_fields_name[$name];
			}
		}
		if($j != 0)
		{
			$field_list .= ', ';
		}
		$field_list .= "'".$fieldname."'";
		$j++;
	}
	$field_list .=')';
	//Getting the Entries from Profile2 vtiger_field vtiger_table
	if($is_admin == false)
	{
		$profileList = getCurrentUserProfileList();
		//changed to get vtiger_field.fieldname
		$query  = "select vtiger_profile2field.*,vtiger_field.fieldname from vtiger_field inner join vtiger_profile2field on vtiger_profile2field.fieldid=vtiger_field.fieldid inner join vtiger_def_org_field on vtiger_def_org_field.fieldid=vtiger_field.fieldid where vtiger_field.tabid=".$tabid." and vtiger_profile2field.visible=0 and vtiger_def_org_field.visible=0  and vtiger_profile2field.profileid in ".$profileList." and vtiger_field.fieldname in ".$field_list." group by vtiger_field.fieldid";
		$result = $adb->query($query);
		$field=Array();
		for($k=0;$k < $adb->num_rows($result);$k++)
		{
			$field[]=$adb->query_result($result,$k,"fieldname");
		}
	}

        //modified for vtiger_customview 27/5 - $app_strings change to $mod_strings
        foreach($focus->list_fields as $name=>$tableinfo)
        {
                //$fieldname = $focus->list_fields_name[$name];  //commented for vtiger_customview 27/5
                //added for vtiger_customview 27/5
                if($oCv)
                {
                        if(isset($oCv->list_fields_name))
                        {
				if( $oCv->list_fields_name[$name] == '')
					$fieldname = 'crmid';
				else
					$fieldname = $oCv->list_fields_name[$name];
                        }else
                        {
				if( $focus->list_fields_name[$name] == '')
					$fieldname = 'crmid';
				else
					$fieldname = $focus->list_fields_name[$name];
					
                        }
                }
		else
                {
                        $fieldname = $focus->list_fields_name[$name];
                }

                if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] ==0 || in_array($fieldname,$field))
		{
                        if(isset($focus->sortby_fields) && $focus->sortby_fields !='')
                        {
                                //Added on 14-12-2005 to avoid if and else check for every list vtiger_field for arrow image and change order

                                foreach($focus->list_fields[$name] as $tab=>$col)
                                {
                                        if(in_array($col,$focus->sortby_fields))
                                        {
                                                if($relatedlist !='')
                                                {
                                                        if($app_strings[$name])
                                                        {
                                                                $name = $app_strings[$name];
                                                        }
                                                        else
                                                        {
                                                                $name = $mod_strings[$name];
                                                        }
                                                }
                                                else
                                                {
                                                        if($app_strings[$name])
                                                        {
                                                                $lbl_name = $app_strings[$name];
                                                        }
                                                        else
                                                        {
								 $lbl_name = $mod_strings[$name];
                                                        }
                                                        $name = $lbl_name;
                                                }
                                        }
                                        else
                                        {       if($app_strings[$name])
                                                {
                                                        $name = $app_strings[$name];
                                                }
                                                elseif($mod_strings[$name])
                                                {
                                                        $name = $mod_strings[$name];
                                                }
                                        }
                                }
                        }
                        //Added condition to hide the close column in Related Lists
                        //if($name == 'Close' && $relatedlist != '')
                        if($name == 'Close')
                        {
                                //$list_header .= '';
                                // $list_header[] = '';
			}
                        else
                        {
				if($fieldname!='parent_id')
				{
					$fld_name=$fieldname;
                                	$search_header[$fld_name]=$name;
				}
                        }
                }
        }
	$log->debug("Exiting getSearchListHeaderValues method ...");	
        return $search_header;

}

/**This function is used to get the where condition for search listview query along with url_string
*Param $module - module name
*Returns the where conditions and url_string values in string format
*/

function Search($module)
{
	global $log;
        $log->debug("Entering Search(".$module.") method ...");
	$url_string='';	
	if(isset($_REQUEST['search_field']) && $_REQUEST['search_field'] !="")
        {
                $search_column=$_REQUEST['search_field'];
        }
        if(isset($_REQUEST['search_text']) && $_REQUEST['search_text']!="")
        {
                $search_string=ltrim(rtrim($_REQUEST['search_text']));
        }
        if(isset($_REQUEST['searchtype']) && $_REQUEST['searchtype']!="")
        {


                $search_type=$_REQUEST['searchtype'];

                if($search_type == "BasicSearch")
                {
                        $where=BasicSearch($module,$search_column,$search_string);
                }
                else if ($search_type == "AdvanceSearch")
                {
                }
                else //Global Search
                {
                }
		$url_string = "&search_field=".$search_column."&search_text=".$search_string."&searchtype=BasicSearch";
		return $where."#@@#".$url_string;
		$log->debug("Exiting Search method ...");
        }

}

/**This function is used to get user_id's for a given user_name during search
*Param $table_name - vtiger_tablename
*Param $column_name - columnname
*Param $search_string - searchstring value (username)
*Returns the where conditions for list query in string format
*/

function get_usersid($table_name,$column_name,$search_string)
{

	global $log;
        $log->debug("Entering get_usersid(".$table_name.",".$column_name.",".$search_string.") method ...");
	global $adb;
	$user_qry="select distinct(vtiger_users.id)from vtiger_users inner join vtiger_crmentity on vtiger_crmentity.smownerid=vtiger_users.id where vtiger_users.user_name like '%".$search_string."%' ";
	$user_result=$adb->query($user_qry);
	$noofuser_rows=$adb->num_rows($user_result);
	$x=$noofuser_rows-1;
	if($noofuser_rows!=0)
	{
		$where="(";
		for($i=0;$i<$noofuser_rows;$i++)
		{
			$user_id=$adb->query_result($user_result,$i,'id');
			$where .= "$table_name.$column_name =".$user_id;
			if($i != $x)
			{
				$where .= " or ";
			}
		}
		$where.=" or vtiger_groups.groupname like '%".$search_string."%')";
	}
	else
	{
		//$where="$table_name.$column_name =''";
		$where="groups.groupname like '%".$search_string."%' ";
	}	
	$log->debug("Exiting get_usersid method ...");
	return $where;	
}

/**This function is used to get where conditions for a given vtiger_accountid or contactid during search for their respective names
*Param $column_name - columnname
*Param $search_string - searchstring value (username)
*Returns the where conditions for list query in string format
*/


function getValuesforColumns($column_name,$search_string)
{
	global $log;
	$log->debug("Entering getValuesforColumns(".$column_name.",".$search_string.") method ...");
	global $column_array,$table_col_array;
	for($i=0; $i<count($column_array);$i++)
	{
		if($column_name == $column_array[$i])
		{
			$val=$table_col_array[$i];
			$explode_column=explode(",",$val);
			$x=count($explode_column);	
			if($x == 1 )
			{
				$where="$val like '%".$search_string ."%'";
			}
			else 
			{
				$where="(";
				for($j=0;$j<count($explode_column);$j++)
				{
					$where .= $explode_column[$j]." like '%".$search_string."%'";
					if($j != $x-1)
					{
						$where .= " or ";
					}
				}
				$where.=")";
			}
			break 1;
		}
	}
	$log->debug("Exiting getValuesforColumns method ...");
	return $where;
}

/**This function is used to get where conditions in Basic Search
*Param $module - module name
*Param $search_field - columnname/field name in which the string has be searched
*Param $search_string - searchstring value (username)
*Returns the where conditions for list query in string format
*/

function BasicSearch($module,$search_field,$search_string)
{
	 global $log;
         $log->debug("Entering BasicSearch(".$module.",".$search_field.",".$search_string.") method ...");
	global $adb;
	global $column_array,$table_col_array;

	if($search_field =='crmid')
	{
		$column_name='crmid';
		$table_name='crmentity';
		$where="$table_name.$column_name like '%".$search_string."%'";	
	}else
	{	
		$qry="select vtiger_field.columnname,tablename from vtiger_tab inner join vtiger_field on vtiger_field.tabid=vtiger_tab.tabid where name='".$module."' and fieldname='".$search_field."'";
		$result = $adb->query($qry);
		$noofrows = $adb->num_rows($result);
		if($noofrows!=0)
		{
			$column_name=$adb->query_result($result,0,'columnname');
			$table_name=$adb->query_result($result,0,'tablename');

			if($table_name == "crmentity" && $column_name == "smownerid")
			{
				$where = get_usersid($table_name,$column_name,$search_string);
			}
			elseif($table_name == "activity" && $column_name == "status")
			{
				$where="$table_name.$column_name like '%".$search_string."%' or vtiger_activity.eventstatus like '%".$search_string."%'";
			}
			else if(in_array($column_name,$column_array))
			{
				$where = getValuesforColumns($column_name,$search_string);
			}
			else
			{
				$where="$table_name.$column_name like '%".$search_string."%'";
			}
		}
	}
	$log->debug("Exiting BasicSearch method ...");
	return $where;
}

/**This function is used to get where conditions in Advance Search
*Param $module - module name
*Returns the where conditions for list query in string format
*/

function getAdvSearchfields($module)
{
	global $log;
        $log->debug("Entering getAdvSearchfields(".$module.") method ...");
	global $adb;
	global $current_user;	
	require('user_privileges/user_privileges_'.$current_user->id.'.php');

	$tabid = getTabid($module);

	if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0)
	{
		$sql = "select * from vtiger_field ";
		$sql.= " where vtiger_field.tabid=".$tabid." and";
		$sql.= " vtiger_field.displaytype in (1,2)";
		$sql.= " order by block,sequence";
	}
	else
	{
		$profileList = getCurrentUserProfileList();
		$sql = "select * from vtiger_field inner join vtiger_profile2field on vtiger_profile2field.fieldid=vtiger_field.fieldid inner join vtiger_def_org_field on vtiger_def_org_field.fieldid=vtiger_field.fieldid ";
		$sql.= " where vtiger_field.tabid=".$tabid." and";
		$sql.= " vtiger_field.displaytype in (1,2) and vtiger_profile2field.visible=0";
		$sql.= " and vtiger_def_org_field.visible=0  and vtiger_profile2field.profileid in ".$profileList." order by block,sequence";
	}


	$result = $adb->query($sql);
	$noofrows = $adb->num_rows($result);
	$block = '';

	for($i=0; $i<$noofrows; $i++)
	{
		$fieldtablename = $adb->query_result($result,$i,"tablename");
		$fieldcolname = $adb->query_result($result,$i,"columnname");
		$block = $adb->query_result($result,$i,"block");
		$fieldtype = explode("~",$fieldtype);
		$fieldtypeofdata = $fieldtype[0];
		$fieldlabel = $adb->query_result($result,$i,"fieldlabel");
		//Added on 14-10-2005 -- added ticket id in list
		if($module == 'HelpDesk' && $block == 25)
		{
			$module_columnlist['crmentity:crmid::HelpDesk_Ticket ID:I'] = 'Ticket ID';
		}
		//Added to include vtiger_activity type in vtiger_activity vtiger_customview list
		if($module == 'Activities' && $block == 19)
		{
			$module_columnlist['vtiger_activity:activitytype:activitytype:Activities_Activity Type:C'] = 'Activity Type';
		}
		if($fieldlabel == "Related To")
		{
			$fieldlabel = "Related to";
		}
		if($fieldlabel == "Start Date & Time")
		{
			$fieldlabel = "Start Date";
			if($module == 'Activities' && $block == 19)
				$module_columnlist['vtiger_activity:time_start::Activities_Start Time:I'] = 'Start Time';

		}
		$fieldlabel1 = str_replace(" ","_",$fieldlabel);
		if($fieldlabel != 'Related to')
		{
			if ($i==0)
				$OPTION_SET .= "<option value=\'".$fieldtablename.".".$fieldcolname."\' selected>".$fieldlabel."</option>";
			else
				$OPTION_SET .= "<option value=\'".$fieldtablename.".".$fieldcolname."\'>".$fieldlabel."</option>";
		}
	}
	$log->debug("Exiting getAdvSearchfields method ...");
	return $OPTION_SET;
}

/**This function is returns the search criteria options for Advance Search
*takes no parameter
*Returns the criteria option in html format
*/

function getcriteria_options()
{
	global $log;
	$log->debug("Entering getcriteria_options() method ...");
	$CRIT_OPT = "<option value=\'cts\'>contains</option><option value=\'dcts\'>does not contains</option><option value=\'is\'>is</option><option value=\'isn\'>is not</option><option value=\'bwt\'>begins with</option><option value=\'ewt\'>ends with</option><option value=\'grt\'>greater than</option><option value=\'lst\'>less than</option><option value=\'grteq\'>greater or equal</option><option value=\'lsteq\'>lesser or equal</option>";
	$log->debug("Exiting getcriteria_options method ...");
	return $CRIT_OPT;
}

/**This function is returns the where conditions for each search criteria option in Advance Search
*Param $criteria - search criteria option
*Param $searchstring - search string
*Param $searchfield - vtiger_fieldname to be search for 
*Returns the search criteria option (where condition) to be added in list query
*/

function getSearch_criteria($criteria,$searchstring,$searchfield)
{
	global $log;
	$log->debug("Entering getSearch_criteria(".$criteria.",".$searchstring.",".$searchfield.") method ...");
	$where_string = '';
	switch($criteria)
	{
		case 'cts':
			$where_string = $searchfield." like '%".$searchstring."%' ";
			break;
		
		case 'dcts':
			$where_string = $searchfield." not like '%".$searchstring."%' ";
			break;
			
		case 'is':
			$where_string = $searchfield." = '".$searchstring."' ";
			break;
			
		case 'isn':
			$where_string = $searchfield." <> '".$searchstring."' ";
			break;
			
		case 'bwt':
			$where_string = $searchfield." like '".$searchstring."%' ";
			break;

		case 'ewt':
			$where_string = $searchfield." like '%".$searchstring."' ";
			break;

		case 'grt':
			$where_string = $searchfield." > '".$searchstring."' ";
			break;

		case 'lst':
			$where_string = $searchfield." < '".$searchstring."' ";
			break;

		case 'grteq':
			$where_string = $searchfield." >= '".$searchstring."' ";
			break;

		case 'lsteq':
			$where_string = $searchfield." <= '".$searchstring."' ";
			break;


	}
	$log->debug("Exiting getSearch_criteria method ...");
	return $where_string;
}

/**This function is returns the where conditions for search
*Param $currentModule - module name
*Returns the where condition to be added in list query in string format
*/

function getWhereCondition($currentModule)
{
	global $log;
	global $column_array,$table_col_array;

        $log->debug("Entering getWhereCondition(".$currentModule.") method ...");
	
	if($_REQUEST['searchtype']=='advance')
	{
		$adv_string='';
		$url_string='';
		if(isset($_REQUEST['search_cnt']))
		$tot_no_criteria = $_REQUEST['search_cnt'];
		if($_REQUEST['matchtype'] == 'all')
			$matchtype = "and";
		else
			$matchtype = "or";
		for($i=0; $i<$tot_no_criteria; $i++)
		{
			if($i == $tot_no_criteria-1)
			$matchtype= "";
			
			$table_colname = 'Fields'.$i;
			$search_condition = 'Condition'.$i;
			$search_value = 'Srch_value'.$i;

			$tab_col = str_replace('\'','',stripslashes($_REQUEST[$table_colname]));
			$srch_cond = str_replace('\'','',stripslashes($_REQUEST[$search_condition]));
			$srch_val = $_REQUEST[$search_value];
			list($tab_name,$column_name) = split("[.]",$tab_col);
			$url_string .="&Fields".$i."=".$tab_col."&Condition".$i."=".$srch_cond."&Srch_value".$i."=".$srch_val;
			if($tab_col == "crmentity.smownerid")
			{
				$adv_string .= " (".getSearch_criteria($srch_cond,$srch_val,'users.user_name')." or";	
				$adv_string .= " ".getSearch_criteria($srch_cond,$srch_val,'groups.groupname')." )".$matchtype;	
			}
			elseif($tab_col == "activity.status")
			{
				$adv_string .= " (".getSearch_criteria($srch_cond,$srch_val,'activity.status')." or";	
				$adv_string .= " ".getSearch_criteria($srch_cond,$srch_val,'activity.eventstatus')." )".$matchtype;	
			}
			elseif($tab_col == "cntactivityrel.contactid")
			{
				$adv_string .= " (".getSearch_criteria($srch_cond,$srch_val,'contactdetails.firstname')." or";	
				$adv_string .= " ".getSearch_criteria($srch_cond,$srch_val,'contactdetails.lastname')." )".$matchtype;	
			}
			elseif(in_array($column_name,$column_array))
                        {
                                $adv_string .= getValuesforColumns($column_name,$srch_val)." ".$matchtype;
                        }
			else
			{
				$adv_string .= " ".getSearch_criteria($srch_cond,$srch_val,$tab_col)." ".$matchtype;	
			}
		}
		$where="(".$adv_string.")#@@#".$url_string."&searchtype=advance&search_cnt=".$tot_no_criteria."&matchtype=".$_REQUEST['matchtype'];
	}
	elseif($_REQUEST['type']=='dbrd')
	{
		$where = getdashboardcondition();
	}
	else
	{
 		$where=Search($currentModule);
	}
	$log->debug("Exiting getWhereCondition method ...");
	return $where;

}

/**This function is returns the where conditions for dashboard and shows the records when clicked on dashboard graph
*Takes no parameter, process the values got from the html request object
*Returns the search criteria option (where condition) to be added in list query
*/

function getdashboardcondition()
{
	global $adb;
	$where_clauses = Array();
	$url_string = "";

	if (isset($_REQUEST['leadsource'])) $lead_source = $_REQUEST['leadsource'];
	if (isset($_REQUEST['date_closed'])) $date_closed = $_REQUEST['date_closed'];
	if (isset($_REQUEST['sales_stage'])) $sales_stage = $_REQUEST['sales_stage'];
	if (isset($_REQUEST['closingdate_start'])) $date_closed_start = $_REQUEST['closingdate_start'];
	if (isset($_REQUEST['closingdate_end'])) $date_closed_end = $_REQUEST['closingdate_end'];
	

	if(isset($date_closed_start) && $date_closed_start != "" && isset($date_closed_end) && $date_closed_end != "")
	{
		array_push($where_clauses, "potential.closingdate >= ".$adb->quote($date_closed_start)." and vtiger_potential.closingdate <= ".$adb->quote($date_closed_end));
		$url_string .= "&closingdate_start=".$date_closed_start."&closingdate_end=".$date_closed_end;
	}
	
	if(isset($sales_stage) && $sales_stage!=''){
		if($sales_stage=='Other')
		array_push($where_clauses, "(vtiger_potential.sales_stage <> 'Closed Won' and vtiger_potential.sales_stage <> 'Closed Lost')");
		else
		array_push($where_clauses, "potential.sales_stage = ".$adb->quote($sales_stage));
		$url_string .= "&sales_stage=".$sales_stage;
	}
	if(isset($lead_source) && $lead_source != "") {
		array_push($where_clauses, "potential.leadsource = ".$adb->quote($lead_source));
		$url_string .= "&leadsource=".$lead_source;
	}
	
	if(isset($date_closed) && $date_closed != "") {
		array_push($where_clauses, $adb->getDBDateString("potential.closingdate")." like ".$adb->quote($date_closed.'%')."");
		$url_string .= "&date_closed=".$date_closed;
	}
	$where = "";
	foreach($where_clauses as $clause)
	{
		if($where != "")
		$where .= " and ";
		$where .= $clause;
	}
	return $where."#@@#".$url_string;
}
?>
