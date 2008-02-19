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
global $calpath;
global $app_strings,$mod_strings;
global $theme;
global $log;

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once('include/database/PearDatabase.php');
require_once('data/CRMEntity.php');
require_once("modules/Reports/Reports.php");

class ReportRun extends CRMEntity
{

	var $primarymodule;
	var $secondarymodule;
	var $orderbylistsql;
	var $orderbylistcolumns;

	var $selectcolumns;
	var $groupbylist;
	var $reporttype;
	var $reportname;
	var $totallist;

	/** Function to set reportid,primarymodule,secondarymodule,reporttype,reportname, for given reportid
	 *  This function accepts the $reportid as argument
	 *  It sets reportid,primarymodule,secondarymodule,reporttype,reportname for the given reportid
	 */
	function ReportRun($reportid)
	{
		$oReport = new Reports($reportid);
		$this->reportid = $reportid;
		$this->primarymodule = $oReport->primodule;
		$this->secondarymodule = $oReport->secmodule; 
		$this->reporttype = $oReport->reporttype;
		$this->reportname = $oReport->reportname;
	}

	/** Function to get the columns for the reportid
	 *  This function accepts the $reportid
	 *  This function returns  $columnslist Array($tablename:$columnname:$fieldlabel:$fieldname:$typeofdata=>$tablename.$columnname As Header value,
	 *					      $tablename1:$columnname1:$fieldlabel1:$fieldname1:$typeofdata1=>$tablename1.$columnname1 As Header value,
	 *					      					|
 	 *					      $tablenamen:$columnnamen:$fieldlabeln:$fieldnamen:$typeofdatan=>$tablenamen.$columnnamen As Header value
	 *				      	     )
	 *
	 */
	function getQueryColumnsList($reportid)
	{
		global $adb;
		global $modules;
		global $log,$current_user;
		$ssql = "select vtiger_selectcolumn.* from vtiger_report inner join vtiger_selectquery on vtiger_selectquery.queryid = vtiger_report.queryid";
		$ssql .= " left join vtiger_selectcolumn on vtiger_selectcolumn.queryid = vtiger_selectquery.queryid";
		$ssql .= " where vtiger_report.reportid = ?";
		$ssql .= " order by vtiger_selectcolumn.columnindex";
		$result = $adb->pquery($ssql, array($reportid));
		$permitted_fields = Array();

		while($columnslistrow = $adb->fetch_array($result))
		{
			$fieldname ="";
			$fieldcolname = $columnslistrow["columnname"];
			list($tablename,$colname,$module_field,$fieldname,$single) = split(":",$fieldcolname);
			list($module,$field) = split("_",$module_field);
			require('user_privileges/user_privileges_'.$current_user->id.'.php');
			if(sizeof($permitted_fields) == 0 && $is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1)
			{
				list($module,$field) = split("_",$module_field);
				$permitted_fields = $this->getaccesfield($module);
			}
			$selectedfields = explode(":",$fieldcolname);
			$querycolumns = $this->getEscapedColumns($selectedfields);
			
			$mod_strings = return_module_language($current_language,$module);
			$fieldlabel = trim(str_replace($module," ",$selectedfields[2]));
			$mod_arr=explode('_',$fieldlabel);
			$mod = ($mod_arr[0] == '')?$module:$mod_arr[0];
			$fieldlabel = trim(str_replace("_"," ",$fieldlabel));
			//modified code to support i18n issue
			$fld_arr = explode(" ",$fieldlabel);
			$mod_lbl = getTranslatedString($fld_arr[0]); //module
			array_shift($fld_arr);
			$fld_lbl_str = implode(" ",$fld_arr);
			$fld_lbl = getTranslatedString($fld_lbl_str); //fieldlabel
			$fieldlabel = $mod_lbl." ".$fld_lbl;
			if(CheckFieldPermission($fieldname,$mod) != 'true')
			{
				continue;
			}
			else
			{
				$header_label = $selectedfields[2]; // Header label to be displayed in the reports table
				// To check if the field in the report is a custom field
				// and if yes, get the label of this custom field freshly from the vtiger_field as it would have been changed.
				// Asha - Reference ticket : #4906
				if (stripos($selectedfields[1], 'cf_') === 0) {
					$cf_columns = $adb->getColumnNames($tablename);
					if (array_search($colname, $cf_columns) != null) {
						$pquery = "select fieldlabel from vtiger_field where tablename = ? and fieldname = ?";
						$cf_res = $adb->pquery($pquery, array($tablename, $colname));
						if (count($cf_res) > 0){
							$cf_fld_label = $adb->query_result($cf_res, 0, "fieldlabel");
							$new_field_label = $module. "_" . str_replace(" ","_",$cf_fld_label);
							$header_label = $cf_fld_label;

							if ($selectedfields[2] != $new_field_label) {
								$selectedfields[2] = $field = $new_field_label;
								// Update the existing field name in the database with new field name.
								$module_field = implode(":", $selectedfields);
								$upd_query = "update vtiger_selectcolumn set columnname = ? where queryid=? and columnindex=?";
								$upd_params = array($module_field, $columnslistrow["queryid"], $columnslistrow["columnindex"]);
								$adb->pquery($upd_query, $upd_params);
							}	
						}
					}
				}
				if($querycolumns == "")
				{
					if($selectedfields[4] == 'C')
					{
						$columnslist[$fieldcolname] = "case when (".$selectedfields[0].".".$selectedfields[1]."='1')then 'yes' else 'no' end as '$selectedfields[2]'";
					}
					elseif($selectedfields[0] == 'vtiger_activity' && $selectedfields[1] == 'status')
					{
						$columnslist[$fieldcolname] = " case when (vtiger_activity.status not like '') then vtiger_activity.status else vtiger_activity.eventstatus end as Calendar_Status";
					}
					elseif($selectedfields[0] == 'vtiger_activity' && $selectedfields[1] == 'date_start')
					{
						$columnslist[$fieldcolname] = "concat(vtiger_activity.date_start,'  ',vtiger_activity.time_start) as Calendar_Start_Date_and_Time";
					}
					elseif(stristr($selectedfields[0],"vtiger_users") && ($selectedfields[1] == 'user_name') && $module_field != 'Products_Handler')
					{
						$temp_module_from_tablename = str_replace("vtiger_users","",$selectedfields[0]);
						if($temp_module_from_tablename == $module)
							$columnslist[$fieldcolname] = " case when (".$selectedfields[0].".user_name not like '') then ".$selectedfields[0].".user_name else vtiger_groups".$module.".groupname end as ".$module."_Assigned_To";
						else//Some Fields can't assigned to groups so case avoided (fields like inventory manager)
							$columnslist[$fieldcolname] = $selectedfields[0].".user_name as '".$header_label."'";
							
					}
					elseif(stristr($selectedfields[0],"vtiger_users") && ($selectedfields[1] == 'user_name') && $module_field == 'Products_Handler')//Products cannot be assiged to group only to handler so group is not included
					{
						$columnslist[$fieldcolname] = $selectedfields[0].".user_name as ".$this->primarymodule."_Handler";
					}
					elseif($selectedfields[0] == "vtiger_crmentity".$this->primarymodule)
					{
						$columnslist[$fieldcolname] = "vtiger_crmentity.".$selectedfields[1]." AS '".$header_label."'";
					}
				        elseif($selectedfields[0] == 'vtiger_invoice' && $selectedfields[1] == 'salesorderid')//handled for salesorder fields in Invoice Module Reports
					{
						$columnslist[$fieldcolname] = 'vtiger_salesorderInvoice.subject	AS "'.$selectedfields[2].'"';
					}elseif($selectedfields[0] == 'vtiger_campaign' && $selectedfields[1] == 'product_id')//handled for product fields in Campaigns Module Reports
					{
						$columnslist[$fieldcolname] = 'vtiger_productsCampaigns.productname AS "'.$header_label.'"';
					}
					else
					{
						$columnslist[$fieldcolname] = $selectedfields[0].".".$selectedfields[1].' AS "'.$header_label.'"';
					}
				}
				else
				{
					$columnslist[$fieldcolname] = $querycolumns;
				}
			}
		}
		$log->info("ReportRun :: Successfully returned getQueryColumnsList".$reportid);
		return $columnslist;		
	}
	
	/** Function to get field columns based on profile  
	 *  @ param $module : Type string 
	 *  returns permitted fields in array format	
	 */
	function getaccesfield($module)
	{
		global $current_user;
		global $adb;
		$access_fields = Array();
		
		$profileList = getCurrentUserProfileList();
		$query = "select vtiger_field.fieldname from vtiger_field inner join vtiger_profile2field on vtiger_profile2field.fieldid=vtiger_field.fieldid inner join vtiger_def_org_field on vtiger_def_org_field.fieldid=vtiger_field.fieldid where";
		$params = array();
		if($module == "Calendar")
		{
			if (count($profileList) > 0) {
				$query .= " vtiger_field.tabid in (9,16) and vtiger_field.displaytype in (1,2,3) and vtiger_profile2field.visible=0 and vtiger_def_org_field.visible=0 and vtiger_profile2field.profileid in (". generateQuestionMarks($profileList) .") group by vtiger_field.fieldid order by block,sequence";
				array_push($params, $profileList);
			} else {
				$query .= " vtiger_field.tabid in (9,16) and vtiger_field.displaytype in (1,2,3) and vtiger_profile2field.visible=0 and vtiger_def_org_field.visible=0 group by vtiger_field.fieldid order by block,sequence";
			}
		}
		else
		{
			array_push($params, $this->primarymodule, $this->secondarymodule);
			if (count($profileList) > 0) {
				$query .= " vtiger_field.tabid in (select tabid from vtiger_tab where vtiger_tab.name in (?,?)) and vtiger_field.displaytype in (1,2,3) and vtiger_profile2field.visible=0 and vtiger_def_org_field.visible=0 and vtiger_profile2field.profileid in (". generateQuestionMarks($profileList) .") group by vtiger_field.fieldid order by block,sequence";
				array_push($params, $profileList);
			} else {
				$query .= " vtiger_field.tabid in (select tabid from vtiger_tab where vtiger_tab.name in (?,?)) and vtiger_field.displaytype in (1,2,3) and vtiger_profile2field.visible=0 and vtiger_def_org_field.visible=0 group by vtiger_field.fieldid order by block,sequence";
			}
		}
		$result = $adb->pquery($query, $params);
		
		while($collistrow = $adb->fetch_array($result))
		{
			$access_fields[] = $collistrow["fieldname"];
		}
		//added to include ticketid for Reports module in select columnlist for all users
		if($module == "HelpDesk")
			$access_fields[] = "ticketid";
		return $access_fields;
	}
	
	/** Function to get Escapedcolumns for the field in case of multiple parents 
	 *  @ param $selectedfields : Type Array 
	 *  returns the case query for the escaped columns	
	 */
	function getEscapedColumns($selectedfields)
	{
		global $current_user;
		$fieldname = $selectedfields[3];
		if($fieldname == "parent_id")
		{
			if($this->primarymodule == "HelpDesk" && $selectedfields[0] == "vtiger_crmentityRelHelpDesk")
			{
				$querycolumn = "case vtiger_crmentityRelHelpDesk.setype when 'Accounts' then vtiger_accountRelHelpDesk.accountname when 'Contacts' then concat(vtiger_contactdetailsRelHelpDesk.lastname,' ',vtiger_contactdetailsRelHelpDesk.firstname) End"." '".$selectedfields[2]."', vtiger_crmentityRelHelpDesk.setype 'Entity_type'";
				return $querycolumn;
			}
			if($this->primarymodule == "Products" || $this->secondarymodule == "Products")
			{
				$querycolumn = "case vtiger_crmentityRelProducts.setype when 'Accounts' then vtiger_accountRelProducts.accountname when 'Leads' then vtiger_leaddetailsRelProducts.lastname when 'Potentials' then vtiger_potentialRelProducts.potentialname End"." '".$selectedfields[2]."', vtiger_crmentityRelProducts.setype 'Entity_type'";
			}
			if($this->primarymodule == "Calendar" || $this->secondarymodule == "Calendar")
			{
				$querycolumn = "case vtiger_crmentityRelCalendar.setype when 'Accounts' then vtiger_accountRelCalendar.accountname when 'Leads' then concat(vtiger_leaddetailsRelCalendar.lastname,' ',vtiger_leaddetailsRelCalendar.firstname) when 'Potentials' then vtiger_potentialRelCalendar.potentialname when 'Quotes' then vtiger_quotesRelCalendar.subject when 'PurchaseOrder' then vtiger_purchaseorderRelCalendar.subject when 'Invoice' then vtiger_invoiceRelCalendar.subject when 'SalesOrder' then vtiger_salesorderRelCalendar.subject when 'HelpDesk' then vtiger_troubleticketsRelCalendar.title when 'Campaigns' then vtiger_campaignRelCalendar.campaignname End"." '".$selectedfields[2]."', vtiger_crmentityRelCalendar.setype 'Entity_type'";
			}
		}elseif($fieldname == "contact_id" && strpos($selectedfields[2],"Contact_Name"))
		{
			if($this->primarymodule == 'PurchaseOrder' || $this->primarymodule == 'SalesOrder' || $this->primarymodule == 'Quotes' || $this->primarymodule == 'Invoice' || $this->primarymodule == 'Calendar') {
				if (getFieldVisibilityPermission("Contacts", $current_user->id, "firstname") == '0')
					$querycolumn = "concat(vtiger_contactdetails".$this->primarymodule.".lastname,' ',vtiger_contactdetails".$this->primarymodule.".firstname) as ".$selectedfields[2];
				else
					$querycolumn = "vtiger_contactdetails".$this->primarymodule.".lastname as ".$selectedfields[2];
			}		
			if($this->secondarymodule == 'Quotes' || $this->secondarymodule == 'Invoice') {
				if (getFieldVisibilityPermission("Contacts", $current_user->id, "firstname") == '0')
					$querycolumn = "concat(vtiger_contactdetails".$this->secondarymodule.".lastname,' ',vtiger_contactdetails".$this->secondarymodule.".firstname) as ".$selectedfields[2];
				else
					$querycolumn = "vtiger_contactdetails".$this->secondarymodule.".lastname as ".$selectedfields[2];
			}
		}
		return $querycolumn;
	}

	/** Function to get selectedcolumns for the given reportid  
	 *  @ param $reportid : Type Integer 
	 *  returns the query of columnlist for the selected columns	
	 */
	function getSelectedColumnsList($reportid)
	{

		global $adb;
		global $modules;
		global $log;

		$ssql = "select vtiger_selectcolumn.* from vtiger_report inner join vtiger_selectquery on vtiger_selectquery.queryid = vtiger_report.queryid"; 
		$ssql .= " left join vtiger_selectcolumn on vtiger_selectcolumn.queryid = vtiger_selectquery.queryid where vtiger_report.reportid = ? "; 
		$ssql .= " order by vtiger_selectcolumn.columnindex";

		$result = $adb->pquery($ssql, array($reportid));
		$noofrows = $adb->num_rows($result);

		if ($this->orderbylistsql != "")
		{
			$sSQL .= $this->orderbylistsql.", ";	
		}

		for($i=0; $i<$noofrows; $i++)
		{
			$fieldcolname = $adb->query_result($result,$i,"columnname");
			$ordercolumnsequal = true;
			if($fieldcolname != "")
			{
				for($j=0;$j<count($this->orderbylistcolumns);$j++)
				{
					if($this->orderbylistcolumns[$j] == $fieldcolname)
					{
						$ordercolumnsequal = false;
						break;
					}else
					{
						$ordercolumnsequal = true;
					}
				}
				if($ordercolumnsequal)
				{
					$selectedfields = explode(":",$fieldcolname);
					if($selectedfields[0] == "vtiger_crmentity".$this->primarymodule)
						$selectedfields[0] = "vtiger_crmentity";
					$sSQLList[] = $selectedfields[0].".".$selectedfields[1]." '".$selectedfields[2]."'";
				}
			}
		}
		$sSQL .= implode(",",$sSQLList);

		$log->info("ReportRun :: Successfully returned getSelectedColumnsList".$reportid);
		return $sSQL;
	}

	/** Function to get advanced comparator in query form for the given Comparator and value   
	 *  @ param $comparator : Type String  
	 *  @ param $value : Type String  
	 *  returns the check query for the comparator 	
	 */
	function getAdvComparator($comparator,$value,$datatype="")
	{

		global $log,$adb,$default_charset;
		$value=html_entity_decode(trim($value),ENT_QUOTES,$default_charset);
		if($comparator == "e")
		{
			if(trim($value) == "NULL")
			{
				$rtvalue = " is NULL";
			}elseif(trim($value) != "")
			{
				$rtvalue = " = ".$adb->quote($value);
			}elseif(trim($value) == "" && $datatype == "V")
			{
				$rtvalue = " = ".$adb->quote($value);
			}else
			{
				$rtvalue = " is NULL";
			}
		}
		if($comparator == "n")
		{
			if(trim($value) == "NULL")
			{
				$rtvalue = " is NOT NULL";
			}elseif(trim($value) != "")
			{
				$rtvalue = " <> ".$adb->quote($value);
			}elseif(trim($value) == "" && $datatype == "V")
			{
				$rtvalue = " <> ".$adb->quote($value);
			}else
			{
				$rtvalue = " is NOT NULL";
			}
		}
		if($comparator == "s")
		{
			$rtvalue = " like '". formatForSqlLike($value, 2) ."'";
		}
		if($comparator == "ew")
		{
			$rtvalue = " like '". formatForSqlLike($value, 1) ."'";
		}			
		if($comparator == "c")
		{
			$rtvalue = " like '". formatForSqlLike($value) ."'";
		}
		if($comparator == "k")
		{
			$rtvalue = " not like '". formatForSqlLike($value) ."'";
		}
		if($comparator == "l")
		{
			$rtvalue = " < ".$adb->quote($value);
		}
		if($comparator == "g")
		{
			$rtvalue = " > ".$adb->quote($value);
		}
		if($comparator == "m")
		{
			$rtvalue = " <= ".$adb->quote($value);
		}
		if($comparator == "h")
		{
			$rtvalue = " >= ".$adb->quote($value);
		}

		$log->info("ReportRun :: Successfully returned getAdvComparator");
		return $rtvalue;
	}

	/** Function to get the advanced filter columns for the reportid
	 *  This function accepts the $reportid
	 *  This function returns  $columnslist Array($columnname => $tablename:$columnname:$fieldlabel:$fieldname:$typeofdata=>$tablename.$columnname filtercriteria,
	 *					      $tablename1:$columnname1:$fieldlabel1:$fieldname1:$typeofdata1=>$tablename1.$columnname1 filtercriteria,
	 *					      					|
 	 *					      $tablenamen:$columnnamen:$fieldlabeln:$fieldnamen:$typeofdatan=>$tablenamen.$columnnamen filtercriteria 
	 *				      	     )
	 *
	 */


	function getAdvFilterList($reportid)
	{
		global $adb;
		global $modules;
		global $log;

		$advfiltersql =  "select vtiger_relcriteria.* from vtiger_report";
		$advfiltersql .= " inner join vtiger_selectquery on vtiger_selectquery.queryid = vtiger_report.queryid";
		$advfiltersql .= " left join vtiger_relcriteria on vtiger_relcriteria.queryid = vtiger_selectquery.queryid";
		$advfiltersql .= " where vtiger_report.reportid =?";
		$advfiltersql .= " order by vtiger_relcriteria.columnindex";

		$result = $adb->pquery($advfiltersql, array($reportid));
		while($advfilterrow = $adb->fetch_array($result))
		{
			$fieldcolname = $advfilterrow["columnname"];
			$comparator = $advfilterrow["comparator"];
			$value = $advfilterrow["value"];
			if($fieldcolname != "" && $comparator != "")
			{
				$selectedfields = explode(":",$fieldcolname);
				//Added to handle yes or no for checkbox  field in reports advance filters. -shahul
				if($selectedfields[4] == 'C')
				{
					if(strcasecmp(trim($value),"yes")==0)
						$value="1";
					if(strcasecmp(trim($value),"no")==0)
						$value="0";
				}
				
				$valuearray = explode(",",trim($value));
				$datatype = (isset($selectedfields[4])) ? $selectedfields[4] : "";
				if(isset($valuearray) && count($valuearray) > 1)
				{
					$advorsql = "";
					for($n=0;$n<count($valuearray);$n++)
					{
                                		if($selectedfields[0] == 'vtiger_crmentityRelHelpDesk' && $selectedfields[1]=='setype')
                                		{
							$advorsql[] = "(case vtiger_crmentityRelHelpDesk.setype when 'Accounts' then vtiger_accountRelHelpDesk.accountname else concat(vtiger_contactdetailsRelHelpDesk.lastname,' ',vtiger_contactdetailsRelHelpDesk.firstname) end) ". $this->getAdvComparator($comparator,trim($valuearray[$n]),$datatype);

		                                }
                        		        elseif($selectedfields[0] == 'vtiger_crmentityRelCalendar' && $selectedfields[1]=='setype')
        		                        {
							$advorsql[] = "(case vtiger_crmentityRelHelpDesk.setype when 'Accounts' then vtiger_accountRelHelpDesk.accountname else concat(vtiger_contactdetailsRelHelpDesk.lastname,' ',vtiger_contactdetailsRelHelpDesk.firstname) end) ". $this->getAdvComparator($comparator,trim($valuearray[$n]),$datatype);
		                                }
						elseif($selectedfields[1] == 'status')//when you use comma seperated values.
						{
							if($selectedfields[2] == 'Calendar_Status')
							$advorsql[] = "(case when (vtiger_activity.status not like '') then vtiger_activity.status else vtiger_activity.eventstatus end)".$this->getAdvComparator($comparator,trim($valuearray[$n]),$datatype);	
							elseif($selectedfields[2] == 'HelpDesk_Status')
							$advorsql[] = "vtiger_troubletickets.status".$this->getAdvComparator($comparator,trim($valuearray[$n]),$datatype);
						}else	
						{
							$advorsql[] = $selectedfields[0].".".$selectedfields[1].$this->getAdvComparator($comparator,trim($valuearray[$n]),$datatype);
						}
					}
					//If negative logic filter ('not equal to', 'does not contain') is used, 'and' condition should be applied instead of 'or'
					if($comparator == 'n' || $comparator == 'k')
						$advorsqls = implode(" and ",$advorsql);
					else
						$advorsqls = implode(" or ",$advorsql);
					$fieldvalue = " (".$advorsqls.") ";
				}
				elseif(($selectedfields[0] == "vtiger_users".$this->primarymodule || $selectedfields[0] == "vtiger_users".$this->secondarymodule) && $selectedfields[1] == 'user_name')
				{
					$module_from_tablename = str_replace("vtiger_users","",$selectedfields[0]);
					if($this->primarymodule == 'Products')
					{
						$fieldvalue = ($selectedfields[0].".user_name ".$this->getAdvComparator($comparator,trim($value),$datatype));
					}else
					{
						$fieldvalue = " case when (".$selectedfields[0].".user_name not like '') then ".$selectedfields[0].".user_name else vtiger_groups".$module_from_tablename.".groupname end ".$this->getAdvComparator($comparator,trim($value),$datatype);
					}
				}
				elseif($selectedfields[0] == "vtiger_crmentity".$this->primarymodule)
				{
					$fieldvalue = "vtiger_crmentity.".$selectedfields[1]." ".$this->getAdvComparator($comparator,trim($value),$datatype);
				}
                                elseif($selectedfields[0] == 'vtiger_crmentityRelHelpDesk' && $selectedfields[1]=='setype')
                                {
					$fieldvalue = "(vtiger_accountRelHelpDesk.accountname ".$this->getAdvComparator($comparator,trim($value),$datatype)." or vtiger_contactdetailsRelHelpDesk.lastname ".$this->getAdvComparator($comparator,trim($value),$datatype)." or vtiger_contactdetailsRelHelpDesk.firstname ".$this->getAdvComparator($comparator,trim($value),$datatype).")";

                                }
                                elseif($selectedfields[0] == 'vtiger_crmentityRelCalendar' && $selectedfields[1]=='setype')
                                {
					$fieldvalue = "(vtiger_accountRelCalendar.accountname ".$this->getAdvComparator($comparator,trim($value),$datatype)." or concat(vtiger_leaddetailsRelCalendar.lastname,' ',vtiger_leaddetailsRelCalendar.firstname) ".$this->getAdvComparator($comparator,trim($value),$datatype)." or vtiger_potentialRelCalendar.potentialname ".$this->getAdvComparator($comparator,trim($value),$datatype)." or vtiger_invoiceRelCalendar.subject ".$this->getAdvComparator($comparator,trim($value),$datatype)." or vtiger_quotesRelCalendar.subject ".$this->getAdvComparator($comparator,trim($value),$datatype)." or vtiger_purchaseorderRelCalendar.subject ".$this->getAdvComparator($comparator,trim($value),$datatype)." or vtiger_salesorderRelCalendar.subject ".$this->getAdvComparator($comparator,trim($value),$datatype)." or vtiger_troubleticketsRelCalendar.title ".$this->getAdvComparator($comparator,trim($value),$datatype)." or vtiger_campaignRelCalendar.campaignname ".$this->getAdvComparator($comparator,trim($value),$datatype).")";
                                }
				// fix for the ticket#4016 -- when you use only one value in its field.
				elseif($selectedfields[0] == "vtiger_activity" && $selectedfields[1] == 'status')
				{
					$fieldvalue = "(case when (vtiger_activity.status not like '') then vtiger_activity.status else vtiger_activity.eventstatus end)".$this->getAdvComparator($comparator,trim($value),$datatype);
				}
				//end fix
				elseif($selectedfields[3] == "contact_id" && strpos($selectedfields[2],"Contact_Name"))
				{
					if($this->primarymodule == 'PurchaseOrder' || $this->primarymodule == 'SalesOrder' || $this->primarymodule == 'Quotes' || $this->primarymodule == 'Invoice' || $this->primarymodule == 'Calendar')
						$fieldvalue = "concat(vtiger_contactdetails". $this->primarymodule .".lastname,' ',vtiger_contactdetails". $this->primarymodule .".firstname)".$this->getAdvComparator($comparator,trim($value),$datatype);
					if($this->secondarymodule == 'Quotes' || $this->secondarymodule == 'Invoice')
						$fieldvalue = "concat(vtiger_contactdetails". $this->secondarymodule .".lastname,' ',vtiger_contactdetails". $this->secondarymodule .".firstname)".$this->getAdvComparator($comparator,trim($value),$datatype);
				}
				else
				{
					$fieldvalue = $selectedfields[0].".".$selectedfields[1].$this->getAdvComparator($comparator,trim($value),$datatype);
				}
				if(isset($advfilterlist[$fieldcolname]))
					$advfilterlist[$fieldcolname] = $advfilterlist[$fieldcolname].' and '.$fieldvalue;
				else $advfilterlist[$fieldcolname] = $fieldvalue;		
			}

		}
		$log->info("ReportRun :: Successfully returned getAdvFilterList".$reportid);
		return $advfilterlist;
	}	

	/** Function to get the Standard filter columns for the reportid
	 *  This function accepts the $reportid datatype Integer
	 *  This function returns  $stdfilterlist Array($columnname => $tablename:$columnname:$fieldlabel:$fieldname:$typeofdata=>$tablename.$columnname filtercriteria,
	 *					      $tablename1:$columnname1:$fieldlabel1:$fieldname1:$typeofdata1=>$tablename1.$columnname1 filtercriteria,
	 *				      	     )
	 *
	 */
	function getStdFilterList($reportid)
	{
		global $adb;
		global $modules;
		global $log;

		$stdfiltersql = "select vtiger_reportdatefilter.* from vtiger_report";
		$stdfiltersql .= " inner join vtiger_reportdatefilter on vtiger_report.reportid = vtiger_reportdatefilter.datefilterid";
		$stdfiltersql .= " where vtiger_report.reportid = ?";

		$result = $adb->pquery($stdfiltersql, array($reportid));
		$stdfilterrow = $adb->fetch_array($result);
		if(isset($stdfilterrow))
		{
			$fieldcolname = $stdfilterrow["datecolumnname"];
			$datefilter = $stdfilterrow["datefilter"];
			$startdate = $stdfilterrow["startdate"];
			$enddate = $stdfilterrow["enddate"];

			if($fieldcolname != "none")
			{
				$selectedfields = explode(":",$fieldcolname);
				if($selectedfields[0] == "vtiger_crmentity".$this->primarymodule)
					$selectedfields[0] = "vtiger_crmentity";
				if($datefilter == "custom")
				{
					if($startdate != "0000-00-00" && $enddate != "0000-00-00" && $selectedfields[0] != "" && $selectedfields[1] != "")
					{
						$stdfilterlist[$fieldcolname] = $selectedfields[0].".".$selectedfields[1]." between '".$startdate." 00:00:00' and '".$enddate." 23:59:59'";
					}
				}else
				{
					$startenddate = $this->getStandarFiltersStartAndEndDate($datefilter);
					if($startenddate[0] != "" && $startenddate[1] != "" && $selectedfields[0] != "" && $selectedfields[1] != "")
					{
						$stdfilterlist[$fieldcolname] = $selectedfields[0].".".$selectedfields[1]." between '".$startenddate[0]." 00:00:00' and '".$startenddate[1]." 23:59:59'";
					}
				}

			}		
		}
		$log->info("ReportRun :: Successfully returned getStdFilterList".$reportid);
		return $stdfilterlist;
	}

	/** Function to get the RunTime filter columns for the given $filtercolumn,$filter,$startdate,$enddate 
	 *  @ param $filtercolumn : Type String
	 *  @ param $filter : Type String
	 *  @ param $startdate: Type String
	 *  @ param $enddate : Type String
	 *  This function returns  $stdfilterlist Array($columnname => $tablename:$columnname:$fieldlabel=>$tablename.$columnname 'between' $startdate 'and' $enddate)
	 *
	 */
	function RunTimeFilter($filtercolumn,$filter,$startdate,$enddate)
	{
		if($filtercolumn != "none")
		{
			$selectedfields = explode(":",$filtercolumn);
			if($selectedfields[0] == "vtiger_crmentity".$this->primarymodule)
				$selectedfields[0] = "vtiger_crmentity";
			if($filter == "custom")
			{
				if($startdate != "" && $enddate != "" && $selectedfields[0] != "" && $selectedfields[1] != "")
				{
					$stdfilterlist[$filtercolumn] = $selectedfields[0].".".$selectedfields[1]." between '".$startdate." 00:00:00' and '".$enddate." 23:59:00'";
				}
			}else
			{
				if($startdate != "" && $enddate != "")
				{
					$startenddate = $this->getStandarFiltersStartAndEndDate($filter);
					if($startenddate[0] != "" && $startenddate[1] != "" && $selectedfields[0] != "" && $selectedfields[1] != "")
					{
						$stdfilterlist[$filtercolumn] = $selectedfields[0].".".$selectedfields[1]." between '".$startenddate[0]." 00:00:00' and '".$startenddate[1]." 23:59:00'";
					}
				}
			}

		}
		return $stdfilterlist;

	}

	/** Function to get standardfilter for the given reportid  
	 *  @ param $reportid : Type Integer 
	 *  returns the query of columnlist for the selected columns	
	 */

	function getStandardCriterialSql($reportid)
	{
		global $adb;
		global $modules;
		global $log;

		$sreportstdfiltersql = "select vtiger_reportdatefilter.* from vtiger_report"; 
		$sreportstdfiltersql .= " inner join vtiger_reportdatefilter on vtiger_report.reportid = vtiger_reportdatefilter.datefilterid"; 
		$sreportstdfiltersql .= " where vtiger_report.reportid = ?";

		$result = $adb->pquery($sreportstdfiltersql, array($reportid));
		$noofrows = $adb->num_rows($result);

		for($i=0; $i<$noofrows; $i++)
		{
			$fieldcolname = $adb->query_result($result,$i,"datecolumnname");
			$datefilter = $adb->query_result($result,$i,"datefilter");
			$startdate = $adb->query_result($result,$i,"startdate");
			$enddate = $adb->query_result($result,$i,"enddate");

			if($fieldcolname != "none")
			{
				$selectedfields = explode(":",$fieldcolname);
				if($selectedfields[0] == "vtiger_crmentity".$this->primarymodule)
					$selectedfields[0] = "vtiger_crmentity";
				if($datefilter == "custom")
				{
					if($startdate != "0000-00-00" && $enddate != "0000-00-00" && $selectedfields[0] != "" && $selectedfields[1] != "")
					{
						$sSQL .= $selectedfields[0].".".$selectedfields[1]." between '".$startdate."' and '".$enddate."'";
					}
				}else
				{
					$startenddate = $this->getStandarFiltersStartAndEndDate($datefilter);
					if($startenddate[0] != "" && $startenddate[1] != "" && $selectedfields[0] != "" && $selectedfields[1] != "")
					{
						$sSQL .= $selectedfields[0].".".$selectedfields[1]." between '".$startenddate[0]."' and '".$startenddate[1]."'";
					}
				}
			}
		}
		$log->info("ReportRun :: Successfully returned getStandardCriterialSql".$reportid);
		return $sSQL;
	}

	/** Function to get standardfilter startdate and enddate for the given type   
	 *  @ param $type : Type String 
	 *  returns the $datevalue Array in the given format
	 * 		$datevalue = Array(0=>$startdate,1=>$enddate)	 
	 */


	function getStandarFiltersStartAndEndDate($type)
	{
		$today = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d"), date("Y")));
		$tomorrow  = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")+1, date("Y")));
		$yesterday  = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")-1, date("Y")));

		$currentmonth0 = date("Y-m-d",mktime(0, 0, 0, date("m"), "01",   date("Y")));
		$currentmonth1 = date("Y-m-t");
		$lastmonth0 = date("Y-m-d",mktime(0, 0, 0, date("m")-1, "01",   date("Y")));
		$lastmonth1 = date("Y-m-t", strtotime("-1 Month"));
		$nextmonth0 = date("Y-m-d",mktime(0, 0, 0, date("m")+1, "01",   date("Y")));
		$nextmonth1 = date("Y-m-t", strtotime("+1 Month"));

		$lastweek0 = date("Y-m-d",strtotime("-2 week Sunday"));
		$lastweek1 = date("Y-m-d",strtotime("-1 week Saturday"));

		$thisweek0 = date("Y-m-d",strtotime("-1 week Sunday"));
		$thisweek1 = date("Y-m-d",strtotime("this Saturday"));

		$nextweek0 = date("Y-m-d",strtotime("this Sunday"));
		$nextweek1 = date("Y-m-d",strtotime("+1 week Saturday"));

		$next7days = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")+6, date("Y")));
		$next30days = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")+29, date("Y")));
		$next60days = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")+59, date("Y")));
		$next90days = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")+89, date("Y")));
		$next120days = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")+119, date("Y")));

		$last7days = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")-6, date("Y")));
		$last30days = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")-29, date("Y")));
		$last60days = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")-59, date("Y")));
		$last90days = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")-89, date("Y")));
		$last120days = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")-119, date("Y")));

		$currentFY0 = date("Y-m-d",mktime(0, 0, 0, "01", "01",   date("Y")));
		$currentFY1 = date("Y-m-t",mktime(0, 0, 0, "12", date("d"),   date("Y")));
		$lastFY0 = date("Y-m-d",mktime(0, 0, 0, "01", "01",   date("Y")-1));
		$lastFY1 = date("Y-m-t", mktime(0, 0, 0, "12", date("d"), date("Y")-1));
		$nextFY0 = date("Y-m-d",mktime(0, 0, 0, "01", "01",   date("Y")+1));
		$nextFY1 = date("Y-m-t", mktime(0, 0, 0, "12", date("d"), date("Y")+1));

		if(date("m") <= 3)
		{
			$cFq = date("Y-m-d",mktime(0, 0, 0, "01","01",date("Y")));
			$cFq1 = date("Y-m-d",mktime(0, 0, 0, "03","31",date("Y")));
			$nFq = date("Y-m-d",mktime(0, 0, 0, "04","01",date("Y")));
			$nFq1 = date("Y-m-d",mktime(0, 0, 0, "06","30",date("Y")));
			$pFq = date("Y-m-d",mktime(0, 0, 0, "10","01",date("Y")-1));
			$pFq1 = date("Y-m-d",mktime(0, 0, 0, "12","31",date("Y")-1));
		}else if(date("m") > 3 and date("m") <= 6)
		{
			$pFq = date("Y-m-d",mktime(0, 0, 0, "01","01",date("Y")));
			$pFq1 = date("Y-m-d",mktime(0, 0, 0, "03","31",date("Y")));
			$cFq = date("Y-m-d",mktime(0, 0, 0, "04","01",date("Y")));
			$cFq1 = date("Y-m-d",mktime(0, 0, 0, "06","30",date("Y")));
			$nFq = date("Y-m-d",mktime(0, 0, 0, "07","01",date("Y")));
			$nFq1 = date("Y-m-d",mktime(0, 0, 0, "09","30",date("Y")));

		}else if(date("m") > 6 and date("m") <= 9)
		{
			$nFq = date("Y-m-d",mktime(0, 0, 0, "10","01",date("Y")));
			$nFq1 = date("Y-m-d",mktime(0, 0, 0, "12","31",date("Y")));
			$pFq = date("Y-m-d",mktime(0, 0, 0, "04","01",date("Y")));
			$pFq1 = date("Y-m-d",mktime(0, 0, 0, "06","30",date("Y")));
			$cFq = date("Y-m-d",mktime(0, 0, 0, "07","01",date("Y")));
			$cFq1 = date("Y-m-d",mktime(0, 0, 0, "09","30",date("Y")));
		}
		else if(date("m") > 9 and date("m") <= 12)
		{
			$nFq = date("Y-m-d",mktime(0, 0, 0, "01","01",date("Y")+1));
			$nFq1 = date("Y-m-d",mktime(0, 0, 0, "03","31",date("Y")+1));
			$pFq = date("Y-m-d",mktime(0, 0, 0, "07","01",date("Y")));
			$pFq1 = date("Y-m-d",mktime(0, 0, 0, "09","30",date("Y")));
			$cFq = date("Y-m-d",mktime(0, 0, 0, "10","01",date("Y")));
			$cFq1 = date("Y-m-d",mktime(0, 0, 0, "12","31",date("Y")));

		}

		if($type == "today" )
		{

			$datevalue[0] = $today;
			$datevalue[1] = $today;
		}
		elseif($type == "yesterday" )
		{

			$datevalue[0] = $yesterday;
			$datevalue[1] = $yesterday;
		}
		elseif($type == "tomorrow" )
		{

			$datevalue[0] = $tomorrow;
			$datevalue[1] = $tomorrow;
		}        
		elseif($type == "thisweek" )
		{

			$datevalue[0] = $thisweek0;
			$datevalue[1] = $thisweek1;
		}                
		elseif($type == "lastweek" )
		{

			$datevalue[0] = $lastweek0;
			$datevalue[1] = $lastweek1;
		}                
		elseif($type == "nextweek" )
		{

			$datevalue[0] = $nextweek0;
			$datevalue[1] = $nextweek1;
		}                
		elseif($type == "thismonth" )
		{

			$datevalue[0] =$currentmonth0;
			$datevalue[1] = $currentmonth1;
		}                

		elseif($type == "lastmonth" )
		{

			$datevalue[0] = $lastmonth0;
			$datevalue[1] = $lastmonth1;
		}             
		elseif($type == "nextmonth" )
		{

			$datevalue[0] = $nextmonth0;
			$datevalue[1] = $nextmonth1;
		}           
		elseif($type == "next7days" )
		{

			$datevalue[0] = $today;
			$datevalue[1] = $next7days;
		}                
		elseif($type == "next30days" )
		{

			$datevalue[0] =$today;
			$datevalue[1] =$next30days;
		}                
		elseif($type == "next60days" )
		{

			$datevalue[0] = $today;
			$datevalue[1] = $next60days;
		}                
		elseif($type == "next90days" )
		{

			$datevalue[0] = $today;
			$datevalue[1] = $next90days;
		}        
		elseif($type == "next120days" )
		{

			$datevalue[0] = $today;
			$datevalue[1] = $next120days;
		}        
		elseif($type == "last7days" )
		{

			$datevalue[0] = $last7days;
			$datevalue[1] = $today;
		}                        
		elseif($type == "last30days" )
		{

			$datevalue[0] = $last30days;
			$datevalue[1] =  $today;
		}                
		elseif($type == "last60days" )
		{

			$datevalue[0] = $last60days;
			$datevalue[1] = $today;
		}        
		else if($type == "last90days" )
		{

			$datevalue[0] = $last90days;
			$datevalue[1] = $today;
		}        
		elseif($type == "last120days" )
		{

			$datevalue[0] = $last120days;
			$datevalue[1] = $today;
		}        
		elseif($type == "thisfy" )
		{

			$datevalue[0] = $currentFY0;
			$datevalue[1] = $currentFY1;
		}                
		elseif($type == "prevfy" )
		{

			$datevalue[0] = $lastFY0;
			$datevalue[1] = $lastFY1;
		}                
		elseif($type == "nextfy" )
		{

			$datevalue[0] = $nextFY0;
			$datevalue[1] = $nextFY1;
		}                
		elseif($type == "nextfq" )
		{

			$datevalue[0] = $nFq;
			$datevalue[1] = $nFq1;
		}                        
		elseif($type == "prevfq" )
		{

			$datevalue[0] = $pFq; 
			$datevalue[1] = $pFq1;
		}                
		elseif($type == "thisfq" )
		{
			$datevalue[0] = $cFq;
			$datevalue[1] = $cFq1;
		}                
		else
		{
			$datevalue[0] = "";
			$datevalue[1] = "";
		}
		return $datevalue;
	}

	/** Function to get getGroupingList for the given reportid  
	 *  @ param $reportid : Type Integer 
	 *  returns the $grouplist Array in the following format	
	 *  		$grouplist = Array($tablename:$columnname:$fieldlabel:fieldname:typeofdata=>$tablename:$columnname $sorder,
	 *				   $tablename1:$columnname1:$fieldlabel1:fieldname1:typeofdata1=>$tablename1:$columnname1 $sorder,
	 *				   $tablename2:$columnname2:$fieldlabel2:fieldname2:typeofdata2=>$tablename2:$columnname2 $sorder)
	 * This function also sets the return value in the class variable $this->groupbylist
	 */


	function getGroupingList($reportid)
	{
		global $adb;
		global $modules;
		global $log;

		$sreportsortsql = "select vtiger_reportsortcol.* from vtiger_report";
		$sreportsortsql .= " inner join vtiger_reportsortcol on vtiger_report.reportid = vtiger_reportsortcol.reportid";
		$sreportsortsql .= " where vtiger_report.reportid =? order by vtiger_reportsortcol.sortcolid";

		$result = $adb->pquery($sreportsortsql, array($reportid));

		while($reportsortrow = $adb->fetch_array($result))
		{
			$fieldcolname = $reportsortrow["columnname"];
			list($tablename,$colname,$module_field,$fieldname,$single) = split(":",$fieldcolname);
			$sortorder = $reportsortrow["sortorder"];

			if($sortorder == "Ascending")
			{
				$sortorder = "ASC";

			}elseif($sortorder == "Descending")
			{
				$sortorder = "DESC";
			}

			if($fieldcolname != "none")
			{
				$selectedfields = explode(":",$fieldcolname);
				if($selectedfields[0] == "vtiger_crmentity".$this->primarymodule)
					$selectedfields[0] = "vtiger_crmentity";	
				$sqlvalue = $selectedfields[0].".".$selectedfields[1]." ".$sortorder;
				$grouplist[$fieldcolname] = $sqlvalue;
				$fieldlabel = trim(str_replace($module," ",$selectedfields[2]));
				$mod_arr=explode('_',$fieldlabel);
				$mod = ($mod_arr[0] == '')?$module:$mod_arr[0];
				if(CheckFieldPermission($fieldname,$mod) == 'true')
				{
					$this->groupbylist[$fieldcolname] = $selectedfields[0].".".$selectedfields[1]." ".$selectedfields[2];
				}
			}
		}
		$log->info("ReportRun :: Successfully returned getGroupingList".$reportid);
		return $grouplist;
	}

	/** function to get the selectedorderbylist for the given reportid  
	 *  @ param $reportid : type integer 
	 *  this returns the columns query for the sortorder columns
	 *  this function also sets the return value in the class variable $this->orderbylistsql
	 */


	function getSelectedOrderbyList($reportid)
	{

		global $adb;
		global $modules;
		global $log;

		$sreportsortsql = "select vtiger_reportsortcol.* from vtiger_report"; 
		$sreportsortsql .= " inner join vtiger_reportsortcol on vtiger_report.reportid = vtiger_reportsortcol.reportid"; 
		$sreportsortsql .= " where vtiger_report.reportid =? order by vtiger_reportsortcol.sortcolid";

		$result = $adb->pquery($sreportsortsql, array($reportid));
		$noofrows = $adb->num_rows($result);

		for($i=0; $i<$noofrows; $i++)
		{
			$fieldcolname = $adb->query_result($result,$i,"columnname");
			$sortorder = $adb->query_result($result,$i,"sortorder");

			if($sortorder == "Ascending")
			{
				$sortorder = "ASC";
			}
			elseif($sortorder == "Descending")
			{
				$sortorder = "DESC";
			}

			if($fieldcolname != "none")
			{
				$this->orderbylistcolumns[] = $fieldcolname;
				$n = $n + 1;
				$selectedfields = explode(":",$fieldcolname);
				if($n > 1)
				{
					$sSQL .= ", ";
					$this->orderbylistsql .= ", ";
				}
				if($selectedfields[0] == "vtiger_crmentity".$this->primarymodule)
					$selectedfields[0] = "vtiger_crmentity";
				$sSQL .= $selectedfields[0].".".$selectedfields[1]." ".$sortorder;
				$this->orderbylistsql .= $selectedfields[0].".".$selectedfields[1]." ".$selectedfields[2];
			}
		}
		$log->info("ReportRun :: Successfully returned getSelectedOrderbyList".$reportid);
		return $sSQL;
	}

	/** function to get secondary Module for the given Primary module and secondary module   
	 *  @ param $module : type String 
	 *  @ param $secmodule : type String 
	 *  this returns join query for the given secondary module
	 */

	function getRelatedModulesQuery($module,$secmodule)
	{
		global $log;

		if($module == "Contacts")
		{
			if($secmodule == "Accounts")
			{
				$query = " left join vtiger_account as vtiger_accounttmp on vtiger_accounttmp.accountid = vtiger_contactdetails.accountid
					left join vtiger_crmentity as vtiger_crmentityAccounts on vtiger_crmentityAccounts.crmid=vtiger_accounttmp.accountid and vtiger_crmentityAccounts.deleted=0
					left join vtiger_account on vtiger_account.accountid = vtiger_crmentityAccounts.crmid 
					left join vtiger_accountbillads on vtiger_account.accountid=vtiger_accountbillads.accountaddressid
					left join vtiger_accountshipads on vtiger_account.accountid=vtiger_accountshipads.accountaddressid
					left join vtiger_accountscf on vtiger_account.accountid = vtiger_accountscf.accountid
					left join vtiger_account as vtiger_accountAccounts on vtiger_accountAccounts.accountid = vtiger_account.parentid
					left join vtiger_accountgrouprelation on vtiger_account.accountid = vtiger_accountgrouprelation.accountid
					left join vtiger_groups vtiger_groupsAccounts on vtiger_groupsAccounts.groupname = vtiger_accountgrouprelation.groupname
					left join vtiger_users as vtiger_usersAccounts on vtiger_usersAccounts.id = vtiger_crmentityAccounts.smownerid ";
			}
			if($secmodule == "Potentials")
			{
				$query = " left join vtiger_potential as vtiger_potentialtmp on vtiger_potentialtmp.accountid = vtiger_contactdetails.accountid
					left join vtiger_crmentity as vtiger_crmentityPotentials on vtiger_crmentityPotentials.crmid=vtiger_potentialtmp.potentialid and vtiger_crmentityPotentials.deleted=0
					left join vtiger_potential on vtiger_potential.potentialid = vtiger_crmentityPotentials.crmid 
					left join vtiger_account as vtiger_accountPotentials on vtiger_potential.accountid = vtiger_accountPotentials.accountid
					left join vtiger_potentialscf on vtiger_potentialscf.potentialid = vtiger_potential.potentialid
					left join vtiger_potentialgrouprelation on vtiger_potential.potentialid = vtiger_potentialgrouprelation.potentialid
					left join vtiger_groups vtiger_groupsPotentials on vtiger_groupsPotentials.groupname = vtiger_potentialgrouprelation.groupname
					left join vtiger_users as vtiger_usersPotentials on vtiger_usersPotentials.id = vtiger_crmentityPotentials.smownerid
					left join vtiger_campaign on vtiger_potential.campaignid = vtiger_campaign.campaignid";
			}
			if($secmodule == "Quotes")
			{
				//CHECKK
				$query = " left join vtiger_quotes  as vtiger_quotestmp on vtiger_quotestmp.contactid = vtiger_contactdetails.contactid
					left join vtiger_crmentity as vtiger_crmentityQuotes on vtiger_crmentityQuotes.crmid=vtiger_quotestmp.quoteid and vtiger_crmentityQuotes.deleted=0
					left join vtiger_quotes on vtiger_quotes.quoteid = vtiger_crmentityQuotes.crmid
					left join vtiger_quotescf on vtiger_quotes.quoteid = vtiger_quotescf.quoteid 
					left join vtiger_quotesbillads on vtiger_quotes.quoteid=vtiger_quotesbillads.quotebilladdressid
					left join vtiger_quotesshipads on vtiger_quotes.quoteid=vtiger_quotesshipads.quoteshipaddressid
					left join vtiger_users as vtiger_usersQuotes on vtiger_usersQuotes.id = vtiger_crmentityQuotes.smownerid
					left join vtiger_users as vtiger_usersRel1 on vtiger_usersRel1.id = vtiger_quotes.inventorymanager
					left join vtiger_potential as vtiger_potentialRel on vtiger_potentialRel.potentialid = vtiger_quotes.potentialid
					left join vtiger_contactdetails as vtiger_contactdetailsQuotes on vtiger_contactdetailsQuotes.contactid = vtiger_quotes.contactid
					left join vtiger_quotegrouprelation on vtiger_quotes.quoteid = vtiger_quotegrouprelation.quoteid
					left join vtiger_groups as vtiger_groupsQuotes on vtiger_groupsQuotes.groupname = vtiger_quotegrouprelation.groupname
					left join vtiger_account as vtiger_accountQuotes on vtiger_accountQuotes.accountid = vtiger_quotes.accountid ";
			}
			if($secmodule == "PurchaseOrder")
			{
				$query = " left join vtiger_purchaseorder as vtiger_purchaseordertmp on vtiger_purchaseordertmp.contactid = vtiger_contactdetails.contactid
					left join vtiger_crmentity as vtiger_crmentityPurchaseOrder on vtiger_crmentityPurchaseOrder.crmid=vtiger_purchaseordertmp.purchaseorderid and vtiger_crmentityPurchaseOrder.deleted=0
					left join vtiger_purchaseorder on vtiger_purchaseorder.purchaseorderid = vtiger_crmentityPurchaseOrder.crmid
					left join vtiger_purchaseordercf on vtiger_purchaseorder.purchaseorderid = vtiger_purchaseordercf.purchaseorderid  
					left join vtiger_pobillads on vtiger_purchaseorder.purchaseorderid=vtiger_pobillads.pobilladdressid
					left join vtiger_poshipads on vtiger_purchaseorder.purchaseorderid=vtiger_poshipads.poshipaddressid
					left join vtiger_pogrouprelation on vtiger_purchaseorder.purchaseorderid = vtiger_pogrouprelation.purchaseorderid
					left join vtiger_groups vtiger_groupsPurchaseOrder on vtiger_groupsPurchaseOrder.groupname = vtiger_pogrouprelation.groupname
					left join vtiger_users as vtiger_usersPurchaseOrder on vtiger_usersPurchaseOrder.id = vtiger_crmentityPurchaseOrder.smownerid
					left join vtiger_vendor as vtiger_vendorRel on vtiger_vendorRel.vendorid = vtiger_purchaseorder.vendorid
					left join vtiger_contactdetails as vtiger_contactdetailsPurchaseOrder on vtiger_contactdetailsPurchaseOrder.contactid = vtiger_purchaseorder.contactid ";
			}

		}

		if($module == "Accounts")
		{
			if($secmodule == "Potentials")
			{
				$query = " left join vtiger_potential as vtiger_potentialtmp on vtiger_potentialtmp.accountid = vtiger_account.accountid
					left join vtiger_crmentity as vtiger_crmentityPotentials on vtiger_crmentityPotentials.crmid=vtiger_potentialtmp.potentialid and vtiger_crmentityPotentials.deleted=0
					left join vtiger_potential on vtiger_potential.potentialid = vtiger_crmentityPotentials.crmid
					left join vtiger_account as vtiger_accountPotentials on vtiger_potential.accountid = vtiger_accountPotentials.accountid
					left join vtiger_potentialscf on vtiger_potentialscf.potentialid = vtiger_potential.potentialid
					left join vtiger_potentialgrouprelation on vtiger_potential.potentialid = vtiger_potentialgrouprelation.potentialid
					left join vtiger_groups vtiger_groupsPotentials on vtiger_groupsPotentials.groupname = vtiger_potentialgrouprelation.groupname
					left join vtiger_users as vtiger_usersPotentials on vtiger_usersPotentials.id = vtiger_crmentityPotentials.smownerid
					left join vtiger_campaign on vtiger_potential.campaignid = vtiger_campaign.campaignid";

			}
			if($secmodule == "Contacts")
			{
				$query = " left join vtiger_contactdetails as vtiger_contactdetailstmp  on vtiger_contactdetailstmp.accountid = vtiger_account.accountid
					left join vtiger_crmentity as vtiger_crmentityContacts on vtiger_crmentityContacts.crmid = vtiger_contactdetailstmp.contactid and vtiger_crmentityContacts.deleted=0
					left join vtiger_contactdetails on vtiger_contactdetails.contactid = vtiger_crmentityContacts.crmid
					left join vtiger_contactaddress on vtiger_contactdetails.contactid = vtiger_contactaddress.contactaddressid
					left join vtiger_contactsubdetails on vtiger_contactdetails.contactid = vtiger_contactsubdetails.contactsubscriptionid
					left join vtiger_contactdetails as vtiger_contactdetailsContacts on vtiger_contactdetailsContacts.contactid = vtiger_contactdetails.reportsto
					left join vtiger_customerdetails on vtiger_customerdetails.customerid = vtiger_contactdetails.contactid
					left join vtiger_account as vtiger_accountContacts on vtiger_accountContacts.accountid = vtiger_contactdetails.accountid 
					left join vtiger_contactscf on vtiger_contactdetails.contactid = vtiger_contactscf.contactid
					left join vtiger_contactgrouprelation on vtiger_contactdetails.contactid = vtiger_contactgrouprelation.contactid
					left join vtiger_groups as vtiger_groupsContacts on vtiger_groupsContacts.groupname = vtiger_contactgrouprelation.groupname
					left join vtiger_users as vtiger_usersContacts on vtiger_usersContacts.id = vtiger_crmentityContacts.smownerid ";
			}
			if($secmodule == "Quotes")
			{
				$query = " left join vtiger_quotes as vtiger_quotestmp on vtiger_quotestmp.accountid = vtiger_account.accountid
					left join vtiger_crmentity as vtiger_crmentityQuotes on vtiger_crmentityQuotes.crmid=vtiger_quotestmp.quoteid and vtiger_crmentityQuotes.deleted=0
					left join vtiger_quotes on vtiger_quotes.quoteid = vtiger_crmentityQuotes.crmid
					left join vtiger_quotescf on vtiger_quotes.quoteid = vtiger_quotescf.quoteid 
					left join vtiger_quotesbillads on vtiger_quotes.quoteid=vtiger_quotesbillads.quotebilladdressid
					left join vtiger_quotesshipads on vtiger_quotes.quoteid=vtiger_quotesshipads.quoteshipaddressid
					left join vtiger_users as vtiger_usersQuotes on vtiger_usersQuotes.id = vtiger_crmentityQuotes.smownerid
					left join vtiger_users as vtiger_usersRel1 on vtiger_usersRel1.id = vtiger_quotes.inventorymanager
					left join vtiger_potential as vtiger_potentialRel on vtiger_potentialRel.potentialid = vtiger_quotes.potentialid
					left join vtiger_contactdetails as vtiger_contactdetailsQuotes on vtiger_contactdetailsQuotes.contactid = vtiger_quotes.contactid
					left join vtiger_quotegrouprelation on vtiger_quotes.quoteid = vtiger_quotegrouprelation.quoteid
					left join vtiger_groups as vtiger_groupsQuotes on vtiger_groupsQuotes.groupname = vtiger_quotegrouprelation.groupname
					left join vtiger_account as vtiger_accountQuotes on vtiger_accountQuotes.accountid = vtiger_quotes.accountid ";
			}
			if($secmodule == "PurchaseOrder")
			{
				$query = " left join vtiger_purchaseorder as vtiger_purchaseordertmp  on vtiger_purchaseordertmp.accountid = vtiger_account.accountid
					left join vtiger_crmentity as vtiger_crmentityPurchaseOrder on vtiger_crmentityPurchaseOrder.crmid=vtiger_purchaseordertmp.purchaseorderid and vtiger_crmentityPurchaseOrder.deleted=0
					left join vtiger_purchaseorder on vtiger_purchaseorder.purchaseorderid = vtiger_crmentityPurchaseOrder.crmid
					left join vtiger_purchaseordercf on vtiger_purchaseorder.purchaseorderid = vtiger_purchaseordercf.purchaseorderid  
					left join vtiger_pobillads on vtiger_purchaseorder.purchaseorderid=vtiger_pobillads.pobilladdressid
					left join vtiger_poshipads on vtiger_purchaseorder.purchaseorderid=vtiger_poshipads.poshipaddressid
					left join vtiger_users as vtiger_usersPurchaseOrder on vtiger_usersPurchaseOrder.id = vtiger_crmentityPurchaseOrder.smownerid
					left join vtiger_pogrouprelation on vtiger_purchaseorder.purchaseorderid = vtiger_pogrouprelation.purchaseorderid
					left join vtiger_groups as vtiger_groupsPurchaseOrder on vtiger_groupsPurchaseOrder.groupname = vtiger_pogrouprelation.groupname
					left join vtiger_vendor as vtiger_vendorRel on vtiger_vendorRel.vendorid = vtiger_purchaseorder.vendorid
					left join vtiger_contactdetails as vtiger_contactdetailsPurchaseOrder on vtiger_contactdetailsPurchaseOrder.contactid = vtiger_purchaseorder.contactid ";
			}
			if($secmodule == "Invoice")
			{
				$query = " left join vtiger_invoice as vtiger_invoicetmp on vtiger_invoicetmp.accountid = vtiger_account.accountid
					left join vtiger_crmentity as vtiger_crmentityInvoice on vtiger_crmentityInvoice.crmid=vtiger_invoicetmp.invoiceid and vtiger_crmentityInvoice.deleted=0
					left join vtiger_invoice on vtiger_invoice.invoiceid = vtiger_crmentityInvoice.crmid
					left join vtiger_invoicecf on vtiger_invoice.invoiceid = vtiger_invoicecf.invoiceid 
					left join vtiger_salesorder as vtiger_salesorderInvoice on vtiger_salesorderInvoice.salesorderid=vtiger_invoice.salesorderid
					left join vtiger_invoicebillads on vtiger_invoice.invoiceid=vtiger_invoicebillads.invoicebilladdressid
					left join vtiger_invoiceshipads on vtiger_invoice.invoiceid=vtiger_invoiceshipads.invoiceshipaddressid
					left join vtiger_invoicegrouprelation on vtiger_invoice.invoiceid = vtiger_invoicegrouprelation.invoiceid
					left join vtiger_groups as vtiger_groupsInvoice on vtiger_groupsInvoice.groupname = vtiger_invoicegrouprelation.groupname
					left join vtiger_users as vtiger_usersInvoice on vtiger_usersInvoice.id = vtiger_crmentityInvoice.smownerid
					left join vtiger_contactdetails as vtiger_contactdetailsInvoice on vtiger_invoice.contactid = vtiger_contactdetailsInvoice.contactid
					left join vtiger_account as vtiger_accountInvoice on vtiger_accountInvoice.accountid = vtiger_invoice.accountid ";
			}
			if($secmodule == "Products")
			{
				$query = " left join vtiger_seproductsrel on vtiger_seproductsrel.crmid = vtiger_account.accountid and vtiger_seproductsrel.setype = 'Accounts'
					left join vtiger_products  as  vtiger_productstmp  on vtiger_productstmp.productid = vtiger_seproductsrel.productid
					left join vtiger_crmentity as vtiger_crmentityProducts on vtiger_crmentityProducts.crmid=vtiger_productstmp.productid and vtiger_crmentityProducts.deleted=0
					left join vtiger_products on vtiger_products.productid = vtiger_crmentityProducts.crmid
					left join vtiger_productcf on vtiger_products.productid = vtiger_productcf.productid
					left join vtiger_users as vtiger_usersProducts on vtiger_usersProducts.id = vtiger_products.handler
					left join vtiger_contactdetails as vtiger_contactdetailsProducts on vtiger_contactdetailsProducts.contactid = vtiger_seproductsrel.crmid
					left join vtiger_vendor as vtiger_vendorRel on vtiger_vendorRel.vendorid = vtiger_products.vendor_id
					left join vtiger_crmentity as vtiger_crmentityRel on vtiger_crmentityRel.crmid = vtiger_seproductsrel.crmid
					left join vtiger_account as vtiger_accountRel on vtiger_accountRel.accountid=vtiger_crmentityRel.crmid
					left join vtiger_leaddetails as vtiger_leaddetailsRel on vtiger_leaddetailsRel.leadid = vtiger_crmentityRel.crmid
					left join vtiger_potential as vtiger_potentialRel on vtiger_potentialRel.potentialid = vtiger_crmentityRel.crmid ";
			}
		}
		if($module == "Quotes")
		{
			if($secmodule == "Accounts")
			{
				$query = " left join vtiger_account as vtiger_accounttmp on vtiger_accounttmp.accountid = vtiger_quotes.accountid
					left join vtiger_crmentity as vtiger_crmentityAccounts on vtiger_crmentityAccounts.crmid=vtiger_accounttmp.accountid and vtiger_crmentityAccounts.deleted=0 
					left join vtiger_account on vtiger_account.accountid = vtiger_crmentityAccounts.crmid
					left join vtiger_accountbillads on vtiger_account.accountid=vtiger_accountbillads.accountaddressid
					left join vtiger_accountshipads on vtiger_account.accountid=vtiger_accountshipads.accountaddressid
					left join vtiger_accountscf on vtiger_account.accountid = vtiger_accountscf.accountid
					left join vtiger_account as vtiger_accountAccounts on vtiger_accountAccounts.accountid = vtiger_account.parentid
					left join vtiger_accountgrouprelation on vtiger_account.accountid = vtiger_accountgrouprelation.accountid
					left join vtiger_groups as vtiger_groupsAccounts on vtiger_groupsAccounts.groupname = vtiger_accountgrouprelation.groupname
					left join vtiger_users as vtiger_usersAccounts on vtiger_usersAccounts.id = vtiger_crmentityAccounts.smownerid ";
			}
			if($secmodule == "Potentials")
			{
				$query = " left join vtiger_potential as vtiger_potentialtmp on vtiger_potentialtmp.potentialid = vtiger_quotes.potentialid
					left join vtiger_crmentity as vtiger_crmentityPotentials on vtiger_crmentityPotentials.crmid=vtiger_potentialtmp.potentialid  and vtiger_crmentityPotentials.deleted=0
					left join vtiger_potential on vtiger_potential.potentialid = vtiger_crmentityPotentials.crmid
					left join vtiger_account as vtiger_accountPotentials on vtiger_potential.accountid = vtiger_accountPotentials.accountid
					left join vtiger_potentialscf on vtiger_potentialscf.potentialid = vtiger_potential.potentialid
					left join vtiger_potentialgrouprelation on vtiger_potential.potentialid = vtiger_potentialgrouprelation.potentialid
					left join vtiger_groups as vtiger_groupsPotentials on vtiger_groupsPotentials.groupname = vtiger_potentialgrouprelation.groupname
					left join vtiger_users as vtiger_usersPotentials on vtiger_usersPotentials.id = vtiger_crmentityPotentials.smownerid
					left join vtiger_campaign on vtiger_potential.campaignid = vtiger_campaign.campaignid";

			}
			if($secmodule == "Contacts")
			{
				$query = " left join vtiger_contactdetails as vtiger_contactdetailstmp on vtiger_contactdetailstmp.contactid = vtiger_quotes.contactid
					left join vtiger_crmentity as vtiger_crmentityContacts on vtiger_crmentityContacts.crmid = vtiger_contactdetailstmp.contactid and vtiger_crmentityContacts.deleted=0
					left join vtiger_contactdetails on vtiger_contactdetails.contactid = vtiger_crmentityContacts.crmid
					left join vtiger_contactaddress on vtiger_contactdetails.contactid = vtiger_contactaddress.contactaddressid
					left join vtiger_contactsubdetails on vtiger_contactdetails.contactid = vtiger_contactsubdetails.contactsubscriptionid
					left join vtiger_contactdetails as vtiger_contactdetailsContacts on vtiger_contactdetailsContacts.contactid = vtiger_contactdetails.reportsto
					left join vtiger_customerdetails on vtiger_customerdetails.customerid = vtiger_contactdetails.contactid
					left join vtiger_account as vtiger_accountContacts on vtiger_accountContacts.accountid = vtiger_contactdetails.accountid

					left join vtiger_contactscf on vtiger_contactdetails.contactid = vtiger_contactscf.contactid
					left join vtiger_contactgrouprelation on vtiger_contactdetails.contactid = vtiger_contactgrouprelation.contactid
					left join vtiger_groups as vtiger_groupsContacts on vtiger_groupsContacts.groupname = vtiger_contactgrouprelation.groupname
					left join vtiger_users as vtiger_usersContacts on vtiger_usersContacts.id = vtiger_crmentityContacts.smownerid ";
			}

		}
		if($module == "PurchaseOrder")
		{
			if($secmodule == "Accounts")
			{
				$query = " left join vtiger_account as vtiger_accounttmp on vtiger_accounttmp.accountid = vtiger_purchaseorder.accountid
					left join vtiger_crmentity as vtiger_crmentityAccounts on vtiger_crmentityAccounts.crmid=vtiger_accounttmp.accountid and vtiger_crmentityAccounts.deleted=0
					left join vtiger_account on vtiger_account.accountid = vtiger_crmentityAccounts.crmid
					left join vtiger_accountbillads on vtiger_account.accountid=vtiger_accountbillads.accountaddressid
					left join vtiger_accountshipads on vtiger_account.accountid=vtiger_accountshipads.accountaddressid
					left join vtiger_accountscf on vtiger_account.accountid = vtiger_accountscf.accountid
					left join vtiger_account as vtiger_accountAccounts on vtiger_accountAccounts.accountid = vtiger_account.parentid
					left join vtiger_accountgrouprelation on vtiger_account.accountid = vtiger_accountgrouprelation.accountid
					left join vtiger_groups as vtiger_groupsAccounts on vtiger_groupsAccounts.groupname = vtiger_accountgrouprelation.groupname
					left join vtiger_users as vtiger_usersAccounts on vtiger_usersAccounts.id = vtiger_crmentityAccounts.smownerid ";
			}
			if($secmodule == "Contacts")
			{
				$query = " left join vtiger_contactdetails as vtiger_contactdetailstmp on vtiger_contactdetailstmp.contactid = vtiger_purchaseorder.contactid
					left join vtiger_crmentity as vtiger_crmentityContacts on vtiger_crmentityContacts.crmid = vtiger_contactdetailstmp.contactid and vtiger_crmentityContacts.deleted=0
					left join vtiger_contactdetails on vtiger_contactdetails.contactid = vtiger_crmentityContacts.crmid
					left join vtiger_contactaddress on vtiger_contactdetails.contactid = vtiger_contactaddress.contactaddressid
					left join vtiger_contactsubdetails on vtiger_contactdetails.contactid = vtiger_contactsubdetails.contactsubscriptionid
					left join vtiger_contactdetails as vtiger_contactdetailsContacts on vtiger_contactdetailsContacts.contactid = vtiger_contactdetails.reportsto
					left join vtiger_customerdetails on vtiger_customerdetails.customerid = vtiger_contactdetails.contactid
					left join vtiger_account as vtiger_accountContacts on vtiger_accountContacts.accountid = vtiger_contactdetails.accountid

					left join vtiger_contactscf on vtiger_contactdetails.contactid = vtiger_contactscf.contactid
					left join vtiger_contactgrouprelation on vtiger_contactdetails.contactid = vtiger_contactgrouprelation.contactid
					left join vtiger_groups as vtiger_groupsContacts on vtiger_groupsContacts.groupname = vtiger_contactgrouprelation.groupname
					left join vtiger_users as vtiger_usersContacts on vtiger_usersContacts.id = vtiger_crmentityContacts.smownerid ";
			}
		}
		if($module == "Invoice")
		{
			if($secmodule == "Accounts")
			{
				$query = " left join vtiger_account as vtiger_accounttmp on vtiger_accounttmp.accountid = vtiger_invoice.accountid
					left join vtiger_crmentity as vtiger_crmentityAccounts on vtiger_crmentityAccounts.crmid=vtiger_accounttmp.accountid and vtiger_crmentityAccounts.deleted=0
					left join vtiger_account on vtiger_account.accountid = vtiger_crmentityAccounts.crmid
					left join vtiger_accountbillads on vtiger_account.accountid=vtiger_accountbillads.accountaddressid
					left join vtiger_accountshipads on vtiger_account.accountid=vtiger_accountshipads.accountaddressid
					left join vtiger_accountscf on vtiger_account.accountid = vtiger_accountscf.accountid
					left join vtiger_account as vtiger_accountAccounts on vtiger_accountAccounts.accountid = vtiger_account.parentid
					left join vtiger_accountgrouprelation on vtiger_account.accountid = vtiger_accountgrouprelation.accountid
					left join vtiger_groups vtiger_groupsAccounts on vtiger_groupsAccounts.groupname = vtiger_accountgrouprelation.groupname
					left join vtiger_users as vtiger_usersAccounts on vtiger_usersAccounts.id = vtiger_crmentityAccounts.smownerid ";
			}
		}

		//Here we will get the Products - Accounts, Contacts relationship (Also Leads, Potentials)
		if($module == "Products")
		{
			if($secmodule == "Accounts")
			{
				$query = " left join vtiger_account as vtiger_accounttmp on vtiger_accounttmp.accountid = vtiger_seproductsrel.crmid
					left join vtiger_crmentity as vtiger_crmentityAccounts on vtiger_crmentityAccounts.crmid=vtiger_accounttmp.accountid and vtiger_crmentityAccounts.deleted=0
					left join vtiger_account on vtiger_account.accountid = vtiger_crmentityAccounts.crmid
					left join vtiger_accountbillads on vtiger_account.accountid=vtiger_accountbillads.accountaddressid
					left join vtiger_accountshipads on vtiger_account.accountid=vtiger_accountshipads.accountaddressid
					left join vtiger_accountscf on vtiger_account.accountid = vtiger_accountscf.accountid
					left join vtiger_account as vtiger_accountAccounts on vtiger_accountAccounts.accountid = vtiger_account.parentid
					left join vtiger_accountgrouprelation on vtiger_account.accountid = vtiger_accountgrouprelation.accountid
					left join vtiger_groups as vtiger_groupsAccounts on vtiger_groupsAccounts.groupname = vtiger_accountgrouprelation.groupname
					left join vtiger_users as vtiger_usersAccounts on vtiger_usersAccounts.id = vtiger_crmentityAccounts.smownerid ";
			}
			if($secmodule == "Contacts")
			{
				$query = " left join vtiger_contactdetails as vtiger_contactdetailstmp on vtiger_contactdetailstmp.contactid = vtiger_seproductsrel.crmid
					left join vtiger_crmentity as vtiger_crmentityContacts on vtiger_crmentityContacts.crmid = vtiger_contactdetailstmp.contactid and vtiger_crmentityContacts.deleted=0
					left join vtiger_contactdetails on vtiger_contactdetails.contactid = vtiger_crmentityContacts.crmid
					left join vtiger_contactaddress on vtiger_contactdetails.contactid = vtiger_contactaddress.contactaddressid
					left join vtiger_contactsubdetails on vtiger_contactdetails.contactid = vtiger_contactsubdetails.contactsubscriptionid
					left join vtiger_contactdetails as vtiger_contactdetailsContacts on vtiger_contactdetailsContacts.contactid = vtiger_contactdetails.reportsto
					left join vtiger_customerdetails on vtiger_customerdetails.customerid = vtiger_contactdetails.contactid
					left join vtiger_account as vtiger_accountContacts on vtiger_accountContacts.accountid = vtiger_contactdetails.accountid
					left join vtiger_contactscf on vtiger_contactdetails.contactid = vtiger_contactscf.contactid
					left join vtiger_contactgrouprelation on vtiger_contactdetails.contactid = vtiger_contactgrouprelation.contactid
					left join vtiger_groups as vtiger_groupsContacts on vtiger_groupsContacts.groupname = vtiger_contactgrouprelation.groupname
					left join vtiger_users as vtiger_usersContacts on vtiger_usersContacts.id = vtiger_crmentityContacts.smownerid ";

			}

		}
		if($module == "Potentials")
		{
			if($secmodule == "Accounts")
			{
				$query = " left join vtiger_account as vtiger_accounttmp on vtiger_accounttmp.accountid = vtiger_potential.accountid
					left join vtiger_crmentity as vtiger_crmentityAccounts on vtiger_crmentityAccounts.crmid=vtiger_accounttmp.accountid and vtiger_crmentityAccounts.deleted=0
					left join vtiger_account  on vtiger_account.accountid = vtiger_crmentityAccounts.crmid
					left join vtiger_accountbillads on vtiger_account.accountid=vtiger_accountbillads.accountaddressid
					left join vtiger_accountshipads on vtiger_account.accountid=vtiger_accountshipads.accountaddressid
					left join vtiger_accountscf on vtiger_account.accountid = vtiger_accountscf.accountid
					left join vtiger_account as vtiger_accountAccounts on vtiger_accountAccounts.accountid = vtiger_account.parentid
					left join vtiger_accountgrouprelation on vtiger_account.accountid = vtiger_accountgrouprelation.accountid
					left join vtiger_groups as vtiger_groupsAccounts on vtiger_groupsAccounts.groupname = vtiger_accountgrouprelation.groupname
					left join vtiger_users as vtiger_usersAccounts on vtiger_usersAccounts.id = vtiger_crmentityAccounts.smownerid ";
			}
			if($secmodule == "Contacts")
			{
				$query = " left join vtiger_contactdetails as vtiger_contactdetailstmp on vtiger_contactdetailstmp.accountid = vtiger_potential.accountid
					left join vtiger_crmentity as vtiger_crmentityContacts on vtiger_crmentityContacts.crmid = vtiger_contactdetailstmp.contactid  and vtiger_crmentityContacts.deleted=0
					left join vtiger_contactdetails on vtiger_contactdetails.contactid = vtiger_crmentityContacts.crmid
					left join vtiger_contactaddress on vtiger_contactdetails.contactid = vtiger_contactaddress.contactaddressid
					left join vtiger_customerdetails on vtiger_customerdetails.customerid = vtiger_contactdetails.contactid
					left join vtiger_contactsubdetails on vtiger_contactdetails.contactid = vtiger_contactsubdetails.contactsubscriptionid
					left join vtiger_contactdetails as vtiger_contactdetailsContacts on vtiger_contactdetailsContacts.contactid = vtiger_contactdetails.reportsto
					left join vtiger_account as vtiger_accountContacts on vtiger_accountContacts.accountid = vtiger_contactdetails.accountid
					left join vtiger_contactscf on vtiger_contactdetails.contactid = vtiger_contactscf.contactid
					left join vtiger_contactgrouprelation on vtiger_contactdetails.contactid = vtiger_contactgrouprelation.contactid
					left join vtiger_groups as vtiger_groupsContacts on vtiger_groupsContacts.groupname = vtiger_contactgrouprelation.groupname
					left join vtiger_users as vtiger_usersContacts on vtiger_usersContacts.id = vtiger_crmentityContacts.smownerid ";

			}
			if($secmodule == "Quotes")
			{
				$query = " left join vtiger_quotes as  vtiger_quotestmp on vtiger_quotestmp.potentialid = vtiger_potential.potentialid
					left join vtiger_crmentity as vtiger_crmentityQuotes on vtiger_crmentityQuotes.crmid=vtiger_quotestmp.quoteid and vtiger_crmentityQuotes.deleted=0
 					left join vtiger_quotes on vtiger_quotes.quoteid = vtiger_crmentityQuotes.crmid
					left join vtiger_quotescf on vtiger_quotes.quoteid = vtiger_quotescf.quoteid
					left join vtiger_quotesbillads on vtiger_quotes.quoteid=vtiger_quotesbillads.quotebilladdressid
					left join vtiger_quotesshipads on vtiger_quotes.quoteid=vtiger_quotesshipads.quoteshipaddressid
					left join vtiger_quotegrouprelation on vtiger_quotes.quoteid = vtiger_quotegrouprelation.quoteid
					left join vtiger_groups as vtiger_groupsQuotes on vtiger_groupsQuotes.groupname = vtiger_quotegrouprelation.groupname
					left join vtiger_users as vtiger_usersQuotes on vtiger_usersQuotes.id = vtiger_crmentityQuotes.smownerid
					left join vtiger_users as vtiger_usersRel1 on vtiger_usersRel1.id = vtiger_quotes.inventorymanager
					left join vtiger_potential as vtiger_potentialRel on vtiger_potentialRel.potentialid = vtiger_quotes.potentialid
					left join vtiger_contactdetails as vtiger_contactdetailsQuotes on vtiger_contactdetailsQuotes.contactid = vtiger_quotes.contactid
					left join vtiger_account as vtiger_accountQuotes on vtiger_accountQuotes.accountid = vtiger_quotes.accountid ";
			}
		}
		if($module == "HelpDesk")
		{
			if($secmodule == "Products")
			{
				$query = " left join vtiger_products as vtiger_productstmp  on vtiger_productstmp.productid = vtiger_troubletickets.product_id
					left join vtiger_crmentity as vtiger_crmentityProducts on vtiger_crmentityProducts.crmid=vtiger_productstmp.productid and vtiger_crmentityProducts.deleted=0
					left join vtiger_products on vtiger_products.productid = vtiger_crmentityProducts.crmid
					left join vtiger_productcf on vtiger_products.productid = vtiger_productcf.productid
					left join vtiger_users as vtiger_usersProducts on vtiger_usersProducts.id = vtiger_products.handler
					left join vtiger_vendor as vtiger_vendorRel on vtiger_vendorRel.vendorid = vtiger_products.vendor_id
					left join vtiger_seproductsrel on vtiger_seproductsrel.productid = vtiger_products.productid
					left join vtiger_crmentity as vtiger_crmentityRelProducts on vtiger_crmentityRelProducts.crmid = vtiger_seproductsrel.crmid
					left join vtiger_account as vtiger_accountRelProducts on vtiger_accountRelProducts.accountid=vtiger_seproductsrel.crmid
					left join vtiger_leaddetails as vtiger_leaddetailsRelProducts on vtiger_leaddetailsRelProducts.leadid = vtiger_seproductsrel.crmid
					left join vtiger_potential as vtiger_potentialRelProducts on vtiger_potentialRelProducts.potentialid = vtiger_seproductsrel.crmid ";
			}
		}
		if($module == "Calendar")
		{
			//Added Leads,Accounts,Potentials as secondarymodule for Calendar

			if($secmodule == "Leads")
			{
				$query = " left join vtiger_leaddetails as vtiger_leaddetailstmp  on vtiger_leaddetailstmp.leadid = vtiger_seactivityrel.crmid 
					left join vtiger_crmentity as vtiger_crmentityLeads on vtiger_crmentityLeads.crmid = vtiger_leaddetailstmp.leadid and vtiger_crmentityLeads.deleted=0 
					left join vtiger_leaddetails on vtiger_leaddetails.leadid = vtiger_crmentityLeads.crmid
					left join vtiger_leadaddress on vtiger_leaddetails.leadid = vtiger_leadaddress.leadaddressid 
					left join vtiger_leadsubdetails on vtiger_leadsubdetails.leadsubscriptionid = vtiger_leaddetails.leadid 
					left join vtiger_leadscf on vtiger_leadscf.leadid = vtiger_leaddetails.leadid 
					left join vtiger_leadgrouprelation on vtiger_leaddetails.leadid = vtiger_leadgrouprelation.leadid
					left join vtiger_groups as vtiger_groupsLeads on vtiger_groupsLeads.groupname = vtiger_leadgrouprelation.groupname
					left join vtiger_users as vtiger_usersLeads on vtiger_usersLeads.id = vtiger_crmentityLeads.smownerid ";
	
			}
			if($secmodule == "Accounts")
			{
				$query = " left join vtiger_account as vtiger_accounttmp on vtiger_accounttmp.accountid = vtiger_seactivityrel.crmid
					left join vtiger_crmentity as vtiger_crmentityAccounts on vtiger_crmentityAccounts.crmid=vtiger_accounttmp.accountid and vtiger_crmentityAccounts.deleted=0
					left join vtiger_account on vtiger_account.accountid = vtiger_crmentityAccounts.crmid
					left join vtiger_accountbillads on vtiger_account.accountid=vtiger_accountbillads.accountaddressid
					left join vtiger_accountshipads on vtiger_account.accountid=vtiger_accountshipads.accountaddressid
					left join vtiger_accountscf on vtiger_account.accountid = vtiger_accountscf.accountid
					left join vtiger_account as vtiger_accountAccounts on vtiger_accountAccounts.accountid = vtiger_account.parentid
					left join vtiger_accountgrouprelation on vtiger_account.accountid = vtiger_accountgrouprelation.accountid
					left join vtiger_groups as vtiger_groupsAccounts on vtiger_groupsAccounts.groupname = vtiger_accountgrouprelation.groupname
					left join vtiger_users as vtiger_usersAccounts on vtiger_usersAccounts.id = vtiger_crmentityAccounts.smownerid ";

			}
			if($secmodule == "Contacts")
			{
				$query = " left join vtiger_contactdetails as vtiger_contactdetailstmp on vtiger_contactdetailstmp.contactid = vtiger_cntactivityrel.contactid 
					left join vtiger_crmentity as vtiger_crmentityContacts on vtiger_crmentityContacts.crmid = vtiger_contactdetailstmp.contactid  and vtiger_crmentityContacts.deleted=0
					left join vtiger_contactdetails on vtiger_contactdetails.contactid = vtiger_crmentityContacts.crmid 
					left join vtiger_contactaddress on vtiger_contactdetails.contactid = vtiger_contactaddress.contactaddressid
					left join vtiger_customerdetails on vtiger_customerdetails.customerid = vtiger_contactdetails.contactid
					left join vtiger_contactsubdetails on vtiger_contactdetails.contactid = vtiger_contactsubdetails.contactsubscriptionid
					left join vtiger_contactdetails as vtiger_contactdetailsContacts on vtiger_contactdetailsContacts.contactid = vtiger_contactdetails.reportsto
					left join vtiger_account as vtiger_accountContacts on vtiger_accountContacts.accountid = vtiger_contactdetails.accountid
					left join vtiger_contactscf on vtiger_contactdetails.contactid = vtiger_contactscf.contactid
					left join vtiger_contactgrouprelation on vtiger_contactdetails.contactid = vtiger_contactgrouprelation.contactid
					left join vtiger_groups as vtiger_groupsContacts on vtiger_groupsContacts.groupname = vtiger_contactgrouprelation.groupname
					left join vtiger_users as vtiger_usersContacts on vtiger_usersContacts.id = vtiger_crmentityContacts.smownerid ";
			}
			if($secmodule == "Potentials")
                        {
				$query = " left join vtiger_potential  as vtiger_potentialtmp on vtiger_potentialtmp.potentialid = vtiger_seactivityrel.crmid 
					left join vtiger_crmentity as vtiger_crmentityPotentials on vtiger_crmentityPotentials.crmid=vtiger_potentialtmp.potentialid and vtiger_crmentityPotentials.deleted=0
					left join vtiger_potential on vtiger_potential.potentialid = vtiger_crmentityPotentials.crmid 
					left join vtiger_account as vtiger_accountPotentials on vtiger_potential.accountid = vtiger_accountPotentials.accountid
                                        left join vtiger_potentialscf on vtiger_potentialscf.potentialid = vtiger_potential.potentialid
					left join vtiger_potentialgrouprelation on vtiger_potential.potentialid = vtiger_potentialgrouprelation.potentialid
					left join vtiger_groups as vtiger_groupsPotentials on vtiger_groupsPotentials.groupname = vtiger_potentialgrouprelation.groupname
                                        left join vtiger_users as vtiger_usersPotentials on vtiger_usersPotentials.id = vtiger_crmentityPotentials.smownerid
					left join vtiger_campaign on vtiger_potential.campaignid = vtiger_campaign.campaignid";

                        }
		}
		if($module == 'Campaigns')
		{
			if($secmodule == 'Products')
			{
				$query = " left join vtiger_products as vtiger_productstmp on vtiger_productstmp.productid = vtiger_campaign.product_id  
					left join vtiger_crmentity as vtiger_crmentityProducts on vtiger_crmentityProducts.crmid=vtiger_productstmp.productid and vtiger_crmentityProducts.deleted=0
					left join vtiger_products on vtiger_products.productid = vtiger_crmentityProducts.crmid  
					left join vtiger_productcf on vtiger_products.productid = vtiger_productcf.productid
					left join vtiger_users as vtiger_usersProducts on vtiger_usersProducts.id = vtiger_products.handler
					left join vtiger_vendor as vtiger_vendorRel on vtiger_vendorRel.vendorid = vtiger_products.vendor_id";
			}
		}
		$log->info("ReportRun :: Successfully returned getRelatedModulesQuery".$secmodule);
		return $query;
	}
	/** function to get report query for the given module    
	 *  @ param $module : type String 
	 *  this returns join query for the given module
	 */

	function getReportsQuery($module)
	{
		global $log;
		if($module == "Leads")
		{
			$query = "from vtiger_leaddetails 
				inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_leaddetails.leadid 
				inner join vtiger_leadsubdetails on vtiger_leadsubdetails.leadsubscriptionid=vtiger_leaddetails.leadid 
				inner join vtiger_leadaddress on vtiger_leadaddress.leadaddressid=vtiger_leadsubdetails.leadsubscriptionid 
				inner join vtiger_leadscf on vtiger_leaddetails.leadid = vtiger_leadscf.leadid 
				left join vtiger_leadgrouprelation on vtiger_leaddetails.leadid = vtiger_leadgrouprelation.leadid
				left join vtiger_groups as vtiger_groupsLeads on vtiger_groupsLeads.groupname = vtiger_leadgrouprelation.groupname
				left join vtiger_users as vtiger_usersLeads on vtiger_usersLeads.id = vtiger_crmentity.smownerid
				left join vtiger_groups on vtiger_groups.groupname = vtiger_leadgrouprelation.groupname
				left join vtiger_users on vtiger_users.id = vtiger_crmentity.smownerid
				where vtiger_crmentity.deleted=0 and vtiger_leaddetails.converted=0";
		}
		if($module == "Accounts")
		{
			$query = "from vtiger_account 
				inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_account.accountid 
				inner join vtiger_accountbillads on vtiger_account.accountid=vtiger_accountbillads.accountaddressid 
				inner join vtiger_accountshipads on vtiger_account.accountid=vtiger_accountshipads.accountaddressid 
				inner join vtiger_accountscf on vtiger_account.accountid = vtiger_accountscf.accountid 
				left join vtiger_accountgrouprelation on vtiger_account.accountid = vtiger_accountgrouprelation.accountid
				left join vtiger_groups as vtiger_groupsAccounts on vtiger_groupsAccounts.groupname = vtiger_accountgrouprelation.groupname
				left join vtiger_account as vtiger_accountAccounts on vtiger_accountAccounts.accountid = vtiger_account.parentid
				left join vtiger_users as vtiger_usersAccounts on vtiger_usersAccounts.id = vtiger_crmentity.smownerid
				left join vtiger_groups on vtiger_groups.groupname = vtiger_accountgrouprelation.groupname
				left join vtiger_users on vtiger_users.id = vtiger_crmentity.smownerid
				".$this->getRelatedModulesQuery($module,$this->secondarymodule)."
				where vtiger_crmentity.deleted=0 ";
		}

		if($module == "Contacts")
		{
			$query = "from vtiger_contactdetails
				inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_contactdetails.contactid 
				inner join vtiger_contactaddress on vtiger_contactdetails.contactid = vtiger_contactaddress.contactaddressid 
				inner join vtiger_customerdetails on vtiger_customerdetails.customerid = vtiger_contactdetails.contactid
				inner join vtiger_contactsubdetails on vtiger_contactdetails.contactid = vtiger_contactsubdetails.contactsubscriptionid 
				inner join vtiger_contactscf on vtiger_contactdetails.contactid = vtiger_contactscf.contactid 
				left join vtiger_contactgrouprelation on vtiger_contactdetails.contactid = vtiger_contactgrouprelation.contactid
				left join vtiger_groups vtiger_groupsContacts on vtiger_groupsContacts.groupname = vtiger_contactgrouprelation.groupname
				left join vtiger_contactdetails as vtiger_contactdetailsContacts on vtiger_contactdetailsContacts.contactid = vtiger_contactdetails.reportsto
				left join vtiger_account as vtiger_accountContacts on vtiger_accountContacts.accountid = vtiger_contactdetails.accountid 
				left join vtiger_users as vtiger_usersContacts on vtiger_usersContacts.id = vtiger_crmentity.smownerid
				left join vtiger_users on vtiger_users.id = vtiger_crmentity.smownerid
				left join vtiger_groups on vtiger_groups.groupname = vtiger_contactgrouprelation.groupname
				".$this->getRelatedModulesQuery($module,$this->secondarymodule)." 
				where vtiger_crmentity.deleted=0";
		}

		if($module == "Potentials")
		{
			$query = "from vtiger_potential 
				inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_potential.potentialid 
				inner join vtiger_account as vtiger_accountPotentials on vtiger_potential.accountid = vtiger_accountPotentials.accountid 
				inner join vtiger_potentialscf on vtiger_potentialscf.potentialid = vtiger_potential.potentialid
				left join vtiger_potentialgrouprelation on vtiger_potential.potentialid = vtiger_potentialgrouprelation.potentialid
				left join vtiger_campaign on vtiger_potential.campaignid = vtiger_campaign.campaignid
				left join vtiger_groups vtiger_groupsPotentials on vtiger_groupsPotentials.groupname = vtiger_potentialgrouprelation.groupname
				left join vtiger_users as vtiger_usersPotentials on vtiger_usersPotentials.id = vtiger_crmentity.smownerid  
				left join vtiger_groups on vtiger_groups.groupname = vtiger_potentialgrouprelation.groupname
				left join vtiger_users on vtiger_users.id = vtiger_crmentity.smownerid  
				".$this->getRelatedModulesQuery($module,$this->secondarymodule)."
				where vtiger_crmentity.deleted=0 ";
		}

		//For this Product - we can related Accounts, Contacts (Also Leads, Potentials)
		if($module == "Products")
		{
			$query = "from vtiger_products 
				inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_products.productid 
				left join vtiger_productcf on vtiger_products.productid = vtiger_productcf.productid 
				left join vtiger_users as vtiger_usersProducts on vtiger_usersProducts.id = vtiger_products.handler 
				left join vtiger_vendor as vtiger_vendorRel on vtiger_vendorRel.vendorid = vtiger_products.vendor_id 
				left join vtiger_seproductsrel on vtiger_seproductsrel.productid= vtiger_products.productid and vtiger_seproductsrel.setype='".$this->secondarymodule."'	
				".$this->getRelatedModulesQuery($module,$this->secondarymodule)."
				where vtiger_crmentity.deleted=0";
		}

		if($module == "HelpDesk")
		{
			$query = "from vtiger_troubletickets 
				inner join vtiger_crmentity  
				on vtiger_crmentity.crmid=vtiger_troubletickets.ticketid 
				inner join vtiger_ticketcf on vtiger_ticketcf.ticketid = vtiger_troubletickets.ticketid
				left join vtiger_crmentity as vtiger_crmentityRelHelpDesk on vtiger_crmentityRelHelpDesk.crmid = vtiger_troubletickets.parent_id
				left join vtiger_account as vtiger_accountRelHelpDesk on vtiger_accountRelHelpDesk.accountid=vtiger_crmentityRelHelpDesk.crmid 
				left join vtiger_contactdetails as vtiger_contactdetailsRelHelpDesk on vtiger_contactdetailsRelHelpDesk.contactid= vtiger_crmentityRelHelpDesk.crmid
				left join vtiger_products as vtiger_productsRel on vtiger_productsRel.productid = vtiger_troubletickets.product_id 
				left join vtiger_ticketgrouprelation on vtiger_troubletickets.ticketid = vtiger_ticketgrouprelation.ticketid
				left join vtiger_groups as vtiger_groupsHelpDesk on vtiger_groupsHelpDesk.groupname = vtiger_ticketgrouprelation.groupname
				left join vtiger_users as vtiger_usersHelpDesk on vtiger_crmentity.smownerid=vtiger_usersHelpDesk.id 
				left join vtiger_groups on vtiger_groups.groupname = vtiger_ticketgrouprelation.groupname
				left join vtiger_users on vtiger_crmentity.smownerid=vtiger_users.id 
				".$this->getRelatedModulesQuery($module,$this->secondarymodule)."
				where vtiger_crmentity.deleted=0 ";
		}

		if($module == "Calendar")
		{
			$query = "from vtiger_activity 
				inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_activity.activityid 
				left join vtiger_cntactivityrel on vtiger_cntactivityrel.activityid= vtiger_activity.activityid 
				left join vtiger_contactdetails as vtiger_contactdetailsCalendar on vtiger_contactdetailsCalendar.contactid= vtiger_cntactivityrel.contactid
				left join vtiger_activitygrouprelation on vtiger_activitygrouprelation.activityid = vtiger_crmentity.crmid
				left join vtiger_groups as vtiger_groupsCalendar on vtiger_groupsCalendar.groupname = vtiger_activitygrouprelation.groupname
				left join vtiger_users as vtiger_usersCalendar on vtiger_usersCalendar.id = vtiger_crmentity.smownerid
				left join vtiger_groups on vtiger_groups.groupname = vtiger_activitygrouprelation.groupname
				left join vtiger_users on vtiger_users.id = vtiger_crmentity.smownerid
				left join vtiger_seactivityrel on vtiger_seactivityrel.activityid = vtiger_activity.activityid
				left join vtiger_activity_reminder on vtiger_activity_reminder.activity_id = vtiger_activity.activityid
				left join vtiger_recurringevents on vtiger_recurringevents.activityid = vtiger_activity.activityid
				left join vtiger_crmentity as vtiger_crmentityRelCalendar on vtiger_crmentityRelCalendar.crmid = vtiger_seactivityrel.crmid
				left join vtiger_account as vtiger_accountRelCalendar on vtiger_accountRelCalendar.accountid=vtiger_crmentityRelCalendar.crmid
				left join vtiger_leaddetails as vtiger_leaddetailsRelCalendar on vtiger_leaddetailsRelCalendar.leadid = vtiger_crmentityRelCalendar.crmid
				left join vtiger_potential as vtiger_potentialRelCalendar on vtiger_potentialRelCalendar.potentialid = vtiger_crmentityRelCalendar.crmid
				left join vtiger_quotes as vtiger_quotesRelCalendar on vtiger_quotesRelCalendar.quoteid = vtiger_crmentityRelCalendar.crmid
				left join vtiger_purchaseorder as vtiger_purchaseorderRelCalendar on vtiger_purchaseorderRelCalendar.purchaseorderid = vtiger_crmentityRelCalendar.crmid
				left join vtiger_invoice as vtiger_invoiceRelCalendar on vtiger_invoiceRelCalendar.invoiceid = vtiger_crmentityRelCalendar.crmid
				left join vtiger_salesorder as vtiger_salesorderRelCalendar on vtiger_salesorderRelCalendar.salesorderid = vtiger_crmentityRelCalendar.crmid
				left join vtiger_troubletickets as vtiger_troubleticketsRelCalendar on vtiger_troubleticketsRelCalendar.ticketid = vtiger_crmentityRelCalendar.crmid
				left join vtiger_campaign as vtiger_campaignRelCalendar on vtiger_campaignRelCalendar.campaignid = vtiger_crmentityRelCalendar.crmid
				".$this->getRelatedModulesQuery($module,$this->secondarymodule)."
				WHERE vtiger_crmentity.deleted=0 and (vtiger_activity.activitytype = 'Meeting' or vtiger_activity.activitytype='Call' or vtiger_activity.activitytype='Task')";
		}

		if($module == "Quotes")
		{
			$query = "from vtiger_quotes 
				inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_quotes.quoteid 
				inner join vtiger_quotesbillads on vtiger_quotes.quoteid=vtiger_quotesbillads.quotebilladdressid 
				inner join vtiger_quotesshipads on vtiger_quotes.quoteid=vtiger_quotesshipads.quoteshipaddressid  
				left join vtiger_quotescf on vtiger_quotes.quoteid = vtiger_quotescf.quoteid 
				left join vtiger_quotegrouprelation on vtiger_quotes.quoteid = vtiger_quotegrouprelation.quoteid
				left join vtiger_groups as vtiger_groupsQuotes on vtiger_groupsQuotes.groupname = vtiger_quotegrouprelation.groupname
				left join vtiger_users as vtiger_usersQuotes on vtiger_usersQuotes.id = vtiger_crmentity.smownerid
				left join vtiger_groups on vtiger_groups.groupname = vtiger_quotegrouprelation.groupname
				left join vtiger_users on vtiger_users.id = vtiger_crmentity.smownerid
				left join vtiger_users as vtiger_usersRel1 on vtiger_usersRel1.id = vtiger_quotes.inventorymanager
				left join vtiger_potential as vtiger_potentialRel on vtiger_potentialRel.potentialid = vtiger_quotes.potentialid
				left join vtiger_contactdetails as vtiger_contactdetailsQuotes on vtiger_contactdetailsQuotes.contactid = vtiger_quotes.contactid
				left join vtiger_account as vtiger_accountQuotes on vtiger_accountQuotes.accountid = vtiger_quotes.accountid
				".$this->getRelatedModulesQuery($module,$this->secondarymodule)."
				where vtiger_crmentity.deleted=0";
		}

		if($module == "PurchaseOrder")
		{
			$query = "from vtiger_purchaseorder 
				inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_purchaseorder.purchaseorderid 
				inner join vtiger_pobillads on vtiger_purchaseorder.purchaseorderid=vtiger_pobillads.pobilladdressid 
				inner join vtiger_poshipads on vtiger_purchaseorder.purchaseorderid=vtiger_poshipads.poshipaddressid 
				left join vtiger_purchaseordercf on vtiger_purchaseorder.purchaseorderid = vtiger_purchaseordercf.purchaseorderid  
				left join vtiger_pogrouprelation on vtiger_purchaseorder.purchaseorderid = vtiger_pogrouprelation.purchaseorderid
				left join vtiger_groups as vtiger_groupsPurchaseOrder on vtiger_groupsPurchaseOrder.groupname = vtiger_pogrouprelation.groupname
				left join vtiger_users as vtiger_usersPurchaseOrder on vtiger_usersPurchaseOrder.id = vtiger_crmentity.smownerid 
				left join vtiger_groups on vtiger_groups.groupname = vtiger_pogrouprelation.groupname
				left join vtiger_users on vtiger_users.id = vtiger_crmentity.smownerid 
				left join vtiger_vendor as vtiger_vendorRel on vtiger_vendorRel.vendorid = vtiger_purchaseorder.vendorid 
				left join vtiger_contactdetails as vtiger_contactdetailsPurchaseOrder on vtiger_contactdetailsPurchaseOrder.contactid = vtiger_purchaseorder.contactid 
				".$this->getRelatedModulesQuery($module,$this->secondarymodule)."
				where vtiger_crmentity.deleted=0";
		}

		if($module == "Invoice")
		{
			$query = "from vtiger_invoice 
				inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_invoice.invoiceid 
				inner join vtiger_invoicebillads on vtiger_invoice.invoiceid=vtiger_invoicebillads.invoicebilladdressid 
				inner join vtiger_invoiceshipads on vtiger_invoice.invoiceid=vtiger_invoiceshipads.invoiceshipaddressid 
				left join vtiger_salesorder as vtiger_salesorderInvoice on vtiger_salesorderInvoice.salesorderid=vtiger_invoice.salesorderid
				left join vtiger_invoicecf on vtiger_invoice.invoiceid = vtiger_invoicecf.invoiceid 
				left join vtiger_invoicegrouprelation on vtiger_invoice.invoiceid = vtiger_invoicegrouprelation.invoiceid
				left join vtiger_groups as vtiger_groupsInvoice on vtiger_groupsInvoice.groupname = vtiger_invoicegrouprelation.groupname
				left join vtiger_users as vtiger_usersInvoice on vtiger_usersInvoice.id = vtiger_crmentity.smownerid
				left join vtiger_groups on vtiger_groups.groupname = vtiger_invoicegrouprelation.groupname
				left join vtiger_users on vtiger_users.id = vtiger_crmentity.smownerid
				left join vtiger_account as vtiger_accountInvoice on vtiger_accountInvoice.accountid = vtiger_invoice.accountid
				left join vtiger_contactdetails as vtiger_contactdetailsInvoice on vtiger_contactdetailsInvoice.contactid = vtiger_invoice.contactid
				".$this->getRelatedModulesQuery($module,$this->secondarymodule)."
				where vtiger_crmentity.deleted=0";
		}
		if($module == "SalesOrder")
		{
			$query = "from vtiger_salesorder 
				inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_salesorder.salesorderid 
				inner join vtiger_sobillads on vtiger_salesorder.salesorderid=vtiger_sobillads.sobilladdressid 
				inner join vtiger_soshipads on vtiger_salesorder.salesorderid=vtiger_soshipads.soshipaddressid 
				left join vtiger_salesordercf on vtiger_salesorder.salesorderid = vtiger_salesordercf.salesorderid  
				left join vtiger_contactdetails as vtiger_contactdetailsSalesOrder on vtiger_contactdetailsSalesOrder.contactid = vtiger_salesorder.contactid 
				left join vtiger_quotes as vtiger_quotesSalesOrder on vtiger_quotesSalesOrder.quoteid = vtiger_salesorder.quoteid				
				left join vtiger_account as vtiger_accountSalesOrder on vtiger_accountSalesOrder.accountid = vtiger_salesorder.accountid
				left join vtiger_potential as vtiger_potentialRel on vtiger_potentialRel.potentialid = vtiger_salesorder.potentialid 
				left join vtiger_sogrouprelation on vtiger_salesorder.salesorderid = vtiger_sogrouprelation.salesorderid
				left join vtiger_groups as vtiger_groupsSalesOrder on vtiger_groupsSalesOrder.groupname = vtiger_sogrouprelation.groupname
				left join vtiger_users as vtiger_usersSalesOrder on vtiger_usersSalesOrder.id = vtiger_crmentity.smownerid 
				left join vtiger_groups on vtiger_groups.groupname = vtiger_sogrouprelation.groupname
				left join vtiger_users on vtiger_users.id = vtiger_crmentity.smownerid 
				where vtiger_crmentity.deleted=0";


		}	
		if($module == "Campaigns")
		{
		 $query = "from vtiger_campaign
			        inner join vtiger_campaignscf as vtiger_campaignscf on vtiger_campaignscf.campaignid=vtiger_campaign.campaignid   
				inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_campaign.campaignid
				left join vtiger_products as vtiger_productsCampaigns on vtiger_productsCampaigns.productid = vtiger_campaign.product_id
				left join vtiger_campaigngrouprelation on vtiger_campaign.campaignid = vtiger_campaigngrouprelation.campaignid
				left join vtiger_groups as vtiger_groupsCampaigns on vtiger_groupsCampaigns.groupname = vtiger_campaigngrouprelation.groupname
		                left join vtiger_users as vtiger_usersCampaigns on vtiger_usersCampaigns.id = vtiger_crmentity.smownerid
				left join vtiger_groups on vtiger_groups.groupname = vtiger_campaigngrouprelation.groupname
		                left join vtiger_users on vtiger_users.id = vtiger_crmentity.smownerid
                                ".$this->getRelatedModulesQuery($module,$this->secondarymodule)."
				where vtiger_crmentity.deleted=0";
		}
		$log->info("ReportRun :: Successfully returned getReportsQuery".$module);
		return $query;
	}
 

	/** function to get query for the given reportid,filterlist,type    
	 *  @ param $reportid : Type integer
	 *  @ param $filterlist : Type Array
	 *  @ param $module : Type String 
	 *  this returns join query for the report 
	 */

	function sGetSQLforReport($reportid,$filterlist,$type='')
	{
		global $log;

		$columnlist = $this->getQueryColumnsList($reportid);
		$groupslist = $this->getGroupingList($reportid);
		$stdfilterlist = $this->getStdFilterList($reportid);
		$columnstotallist = $this->getColumnsTotal($reportid);
		$advfilterlist = $this->getAdvFilterList($reportid);
		$this->totallist = $columnstotallist;
		global $current_user;
		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
		$tab_id = getTabid($this->primarymodule);
		//Fix for ticket #4915.
		$selectlist = $columnlist;
		//columns list
		if(isset($selectlist))
		{
			$selectedcolumns =  implode(", ",$selectlist);
		}
		//groups list
		if(isset($groupslist))
		{
			$groupsquery = implode(", ",$groupslist);
		}

		//standard list
		if(isset($stdfilterlist))
		{
			$stdfiltersql = implode(", ",$stdfilterlist);
		}
		if(isset($filterlist))
		{
			$stdfiltersql = implode(", ",$filterlist);
		}
		//columns to total list
		if(isset($columnstotallist))
		{
			$columnstotalsql = implode(", ",$columnstotallist);
		}
		//advanced filterlist
		if(isset($advfilterlist))
		{
			$advfiltersql = implode(" and ",$advfilterlist);
		}
		if($stdfiltersql != "")
		{
			$wheresql = " and ".$stdfiltersql;
		}
		if($advfiltersql != "")
		{
			$wheresql .= " and ".$advfiltersql;
		}

		$reportquery = $this->getReportsQuery($this->primarymodule);

		// If we don't have access to any columns, let us select one column and limit result to shown we have not results
                // Fix for: http://trac.vtiger.com/cgi-bin/trac.cgi/ticket/4758 - Prasad
		$allColumnsRestricted = false;

		if($type == 'COLUMNSTOTOTAL')
		{
			if($columnstotalsql != '')
			{
				$reportquery = "select ".$columnstotalsql." ".$reportquery." ".$wheresql;
			}
		}else
		{
			if($selectedcolumns == '') {
                                // Fix for: http://trac.vtiger.com/cgi-bin/trac.cgi/ticket/4758 - Prasad
                                
				$selectedcolumns = "''"; // "''" to get blank column name
                                $allColumnsRestricted = true;
                        }
			
			$reportquery = "select ".$selectedcolumns." ".$reportquery." ".$wheresql;
		}
		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3)
		{
			$sec_parameter=getListViewSecurityParameter($this->primarymodule);
			$reportquery .= " ".$sec_parameter;
		}
		
		if($tab_id == 9 || $tab_id == 16)
        	$reportquery.=" group by vtiger_activity.activityid ";

		if(trim($groupsquery) != "")
		{
			$reportquery .= " order by ".$groupsquery;
		}
		
		// Prasad: No columns selected so limit the number of rows directly.
                if($allColumnsRestricted) {
                        $reportquery .= " limit 0";
                }

		$log->info("ReportRun :: Successfully returned sGetSQLforReport".$reportid);
		return $reportquery;

	}

	/** function to get the report output in HTML,PDF,TOTAL,PRINT,PRINTTOTAL formats depends on the argument $outputformat    
	 *  @ param $outputformat : Type String (valid parameters HTML,PDF,TOTAL,PRINT,PRINT_TOTAL)
	 *  @ param $filterlist : Type Array
	 *  This returns HTML Report if $outputformat is HTML
         *  		Array for PDF if  $outputformat is PDF
	 *		HTML strings for TOTAL if $outputformat is TOTAL
	 *		Array for PRINT if $outputformat is PRINT
	 *		HTML strings for TOTAL fields  if $outputformat is PRINTTOTAL
	 *		HTML strings for 
	 */

	function GenerateReport($outputformat,$filterlist)
	{
		global $adb,$current_user;
		global $modules,$app_strings;
		global $mod_strings,$current_language;
		require('user_privileges/user_privileges_'.$current_user->id.'.php');

		if($outputformat == "HTML")
		{
			$sSQL = $this->sGetSQLforReport($this->reportid,$filterlist);
			$result = $adb->query($sSQL);

			if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1)
				$picklistarray = $this->getAccessPickListValues();
			if($result)
			{
				$y=$adb->num_fields($result);
				$arrayHeaders = Array();
				for ($x=0; $x<$y; $x++)
				{
					$fld = $adb->field_name($result, $x);
					if(in_array($this->getLstringforReportHeaders($fld->name), $arrayHeaders))
					{
						$headerLabel = str_replace("_"," ",$fld->name);
						$arrayHeaders[] = $headerLabel;
					}
					else
					{
						$headerLabel = str_replace($modules," ",$this->getLstringforReportHeaders($fld->name));
						$arrayHeaders[] = $headerLabel;
					}
					$header .= "<td class='rptCellLabel'>".$headerLabel."</td>";
				}

				$noofrows = $adb->num_rows($result);
				$custom_field_values = $adb->fetch_array($result);
				$groupslist = $this->getGroupingList($this->reportid);

				$column_definitions = $adb->getFieldsDefinition($result);

				do
				{
					$arraylists = Array();
					if(count($groupslist) == 1)
					{
						$newvalue = $custom_field_values[0];
					}elseif(count($groupslist) == 2)
					{
						$newvalue = $custom_field_values[0];
						$snewvalue = $custom_field_values[1];
					}elseif(count($groupslist) == 3)
					{
						$newvalue = $custom_field_values[0];
						$snewvalue = $custom_field_values[1];
						$tnewvalue = $custom_field_values[2];
					}

					if($newvalue == "") $newvalue = "-";

					if($snewvalue == "") $snewvalue = "-";

					if($tnewvalue == "") $tnewvalue = "-";

					$valtemplate .= "<tr>";

					for ($i=0; $i<$y; $i++)
					{
						$fld = $adb->field_name($result, $i);
						$fld_type = $column_definitions[$i]->type;

						if ($fld->name == "Potentials_Amount")
							$fieldvalue = convertFromMasterCurrency($custom_field_values[$i],$current_user->conv_rate);
						else
							$fieldvalue = getTranslatedString($custom_field_values[$i]);

						$fieldvalue = str_replace("<", "&lt;", $fieldvalue);
						$fieldvalue = str_replace(">", "&gt;", $fieldvalue);

					//check for Roll based pick list
						$temp_val= $fld->name;
						if(is_array($picklistarray))
							if(array_key_exists($temp_val,$picklistarray))
							{
								if(!in_array($custom_field_values[$i],$picklistarray[$fld->name]) && $custom_field_values[$i] != '')
									$fieldvalue =$app_strings['LBL_NOT_ACCESSIBLE'];

							}
						if(is_array($picklistarray[1]))
							if(array_key_exists($temp_val,$picklistarray[1]))
							{
								$temp =explode(",",str_ireplace(' |##| ',',',$fieldvalue));
								$temp_val = Array();
								foreach($temp as $key =>$val)
								{
										if(!in_array(trim($val),$picklistarray[1][$fld->name]) && trim($val) != '')
										{
											$temp_val[]=$app_strings['LBL_NOT_ACCESSIBLE'];
										}
										else
											$temp_val[]=$val;
								}
								$fieldvalue =(is_array($temp_val))?implode(", ",$temp_val):'';
							}

						if($fieldvalue == "" )
						{
							$fieldvalue = "-";
						}
						else if(stristr($fieldvalue,"|##|"))
						{
							$fieldvalue = str_ireplace(' |##| ',', ',$fieldvalue);
						}
						else if($fld_type == "date" || $fld_type == "datetime") {
							$fieldvalue = getDisplayDate($fieldvalue);
						}
																				
						if(($lastvalue == $fieldvalue) && $this->reporttype == "summary")
						{
							if($this->reporttype == "summary")
							{
								$valtemplate .= "<td class='rptEmptyGrp'>&nbsp;</td>";									
							}else
							{
								$valtemplate .= "<td class='rptData'>".$fieldvalue."</td>";
							}
						}else if(($secondvalue === $fieldvalue) && $this->reporttype == "summary")
						{
							if($lastvalue === $newvalue)
							{
								$valtemplate .= "<td class='rptEmptyGrp'>&nbsp;</td>";	
							}else
							{
								$valtemplate .= "<td class='rptGrpHead'>".$fieldvalue."</td>";
							}
						}
						else if(($thirdvalue === $fieldvalue) && $this->reporttype == "summary")
						{
							if($secondvalue === $snewvalue)
							{
								$valtemplate .= "<td class='rptEmptyGrp'>&nbsp;</td>";
							}else
							{
								$valtemplate .= "<td class='rptGrpHead'>".$fieldvalue."</td>";
							}
						}
						else
						{
							if($this->reporttype == "tabular")
							{
								$valtemplate .= "<td class='rptData'>".$fieldvalue."</td>";
							}else
							{
								$valtemplate .= "<td class='rptGrpHead'>".$fieldvalue."</td>";
							}
						}
					}
					$valtemplate .= "</tr>";
					$lastvalue = $newvalue;
					$secondvalue = $snewvalue;
					$thirdvalue = $tnewvalue;
					$arr_val[] = $arraylists;
					set_time_limit(0);
				}while($custom_field_values = $adb->fetch_array($result));

				$sHTML ='<table cellpadding="5" cellspacing="0" align="center" class="rptTable">
					<tr>'. 
					$header
					.'<!-- BEGIN values -->
					<tr>'. 
					$valtemplate
					.'</tr>
					</table>';
				//<<<<<<<<construct HTML>>>>>>>>>>>>
				$return_data[] = $sHTML;
				$return_data[] = $noofrows;
				$return_data[] = $sSQL;
				return $return_data;
			}
		}elseif($outputformat == "PDF")
		{

			$sSQL = $this->sGetSQLforReport($this->reportid,$filterlist);
			$result = $adb->query($sSQL);
			if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1)
			$picklistarray = $this->getAccessPickListValues();

			if($result)
			{
				$y=$adb->num_fields($result);
				$noofrows = $adb->num_rows($result);
				$custom_field_values = $adb->fetch_array($result);

				$column_definitions = $adb->getFieldsDefinition($result);

				do
				{
					$arraylists = Array();
					for ($i=0; $i<$y; $i++)
					{
						$fld = $adb->field_name($result, $i);
						if ($fld->name == "Potentials_Amount")
							$fieldvalue = convertFromMasterCurrency($custom_field_values[$i],$current_user->conv_rate);
						else
							$fieldvalue = $custom_field_values[$i];

						$fieldvalue = str_replace("<", "&lt;", $fieldvalue);
						$fieldvalue = str_replace(">", "&gt;", $fieldvalue);

						// Check for role based pick list
						$temp_val= $fld->name;
						if(is_array($picklistarray))
							if(array_key_exists($temp_val,$picklistarray))
							{
								if(!in_array($custom_field_values[$i],$picklistarray[$fld->name]) && $custom_field_values[$i] != '')
								{
									$fieldvalue =$app_strings['LBL_NOT_ACCESSIBLE'];
								}
							}
						if(is_array($picklistarray[1]))
							if(array_key_exists($temp_val,$picklistarray[1]))
							{
								$temp =explode(",",str_ireplace(' |##| ',',',$fieldvalue));
								$temp_val = Array();
								foreach($temp as $key =>$val)
								{
										if(!in_array(trim($val),$picklistarray[1][$fld->name]) && trim($val) != '')
										{
											$temp_val[]=$app_strings['LBL_NOT_ACCESSIBLE'];
										}
										else
											$temp_val[]=$val;
								}
								$fieldvalue =(is_array($temp_val))?implode(", ",$temp_val):'';
							}

						if($fieldvalue == "" )
						{
							$fieldvalue = "-";
						}
						else if(stristr($fieldvalue,"|##|"))
						{
							$fieldvalue = str_ireplace(' |##| ',', ',$fieldvalue);
						}
						else if($fld_type == "date" || $fld_type == "datetime") {
							$fieldvalue = getDisplayDate($fieldvalue);
						}
						if(array_key_exists($this->getLstringforReportHeaders($fld->name), $arraylists))
							$arraylists[str_replace("_"," ",$fld->name)] = $fieldvalue;
						else	
							$arraylists[str_replace($modules," ",$this->getLstringforReportHeaders($fld->name))] = $fieldvalue;
					}
					$arr_val[] = $arraylists;
					set_time_limit(0);
				}while($custom_field_values = $adb->fetch_array($result));

				return $arr_val;
			}
		}elseif($outputformat == "TOTALHTML")
		{
			$escapedchars = Array('_SUM','_AVG','_MIN','_MAX');
			$sSQL = $this->sGetSQLforReport($this->reportid,$filterlist,"COLUMNSTOTOTAL");
			if(isset($this->totallist))
			{
				if($sSQL != "")
				{
					$result = $adb->query($sSQL);
					$y=$adb->num_fields($result);
					$custom_field_values = $adb->fetch_array($result);

					$coltotalhtml .= "<table align='center' width='60%' cellpadding='3' cellspacing='0' border='0' class='rptTable'><tr><td class='rptCellLabel'>".$mod_strings[Totals]."</td><td class='rptCellLabel'>".$mod_strings[SUM]."</td><td class='rptCellLabel'>".$mod_strings[AVG]."</td><td class='rptCellLabel'>".$mod_strings[MIN]."</td><td class='rptCellLabel'>".$mod_strings[MAX]."</td></tr>";

					foreach($this->totallist as $key=>$value)
					{
						$fieldlist = explode(":",$key);
						$totclmnflds[str_replace($escapedchars," ",$fieldlist[3])] = str_replace($escapedchars," ",$fieldlist[3]);
					}

					for($i =0;$i<$y;$i++)
					{
						$fld = $adb->field_name($result, $i);
						$keyhdr[$fld->name] = $custom_field_values[$i];

					}
					foreach($totclmnflds as $key=>$value)
					{

						$coltotalhtml .= '<tr class="rptGrpHead" valign=top><td class="rptData">'.getTranslatedString(trim(str_replace($modules," ",$value))).'</td>';
						$arraykey = trim($value).'_SUM';
						if(isset($keyhdr[$arraykey]))
						{
							$coltotalhtml .= '<td class="rptTotal">'.convertFromMasterCurrency($keyhdr[$arraykey],$current_user->conv_rate).'</td>';
						}else
						{
							$coltotalhtml .= '<td class="rptTotal">&nbsp;</td>';
						}

						$arraykey = trim($value).'_AVG';
						if(isset($keyhdr[$arraykey]))
						{
							$coltotalhtml .= '<td class="rptTotal">'.convertFromMasterCurrency($keyhdr[$arraykey],$current_user->conv_rate).'</td>';
						}else
						{
							$coltotalhtml .= '<td class="rptTotal">&nbsp;</td>';
						}

						$arraykey = trim($value).'_MIN';
						if(isset($keyhdr[$arraykey]))
						{
							$coltotalhtml .= '<td class="rptTotal">'.convertFromMasterCurrency($keyhdr[$arraykey],$current_user->conv_rate).'</td>';
						}else
						{
							$coltotalhtml .= '<td class="rptTotal">&nbsp;</td>';
						}

						$arraykey = trim($value).'_MAX';
						if(isset($keyhdr[$arraykey]))
						{
							$coltotalhtml .= '<td class="rptTotal">'.convertFromMasterCurrency($keyhdr[$arraykey],$current_user->conv_rate).'</td>';
						}else
						{
							$coltotalhtml .= '<td class="rptTotal">&nbsp;</td>';
						}

						$coltotalhtml .= '<tr>';
					}

					$coltotalhtml .= "</table>";
				}
			}			
			return $coltotalhtml;
		}elseif($outputformat == "PRINT")
		{
			$sSQL = $this->sGetSQLforReport($this->reportid,$filterlist);
			$result = $adb->query($sSQL);
			if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1)
			$picklistarray = $this->getAccessPickListValues();

			if($result)
			{
				$y=$adb->num_fields($result);
				$arrayHeaders = Array();
				for ($x=0; $x<$y; $x++)
				{
					$fld = $adb->field_name($result, $x);
					if(in_array($this->getLstringforReportHeaders($fld->name), $arrayHeaders))
					{
						$headerLabel = str_replace("_"," ",$fld->name);
						$arrayHeaders[] = $headerLabel;
					}
					else
					{
						$headerLabel = str_replace($modules," ",$this->getLstringforReportHeaders($fld->name));
						$arrayHeaders[] = $headerLabel;	
					}	
					$header .= "<th>".$headerLabel."</th>";
				}
				$noofrows = $adb->num_rows($result);
				$custom_field_values = $adb->fetch_array($result);
				$groupslist = $this->getGroupingList($this->reportid);

				$column_definitions = $adb->getFieldsDefinition($result);

				do
				{
					$arraylists = Array();
					if(count($groupslist) == 1)
					{
						$newvalue = $custom_field_values[0];
					}elseif(count($groupslist) == 2)
					{
						$newvalue = $custom_field_values[0];
						$snewvalue = $custom_field_values[1];
					}elseif(count($groupslist) == 3)
					{
						$newvalue = $custom_field_values[0];
                                                $snewvalue = $custom_field_values[1];
						$tnewvalue = $custom_field_values[2];
					}
					
					if($newvalue == "") $newvalue = "-";

					if($snewvalue == "") $snewvalue = "-";

					if($tnewvalue == "") $tnewvalue = "-";
 
					$valtemplate .= "<tr>";
					
					for ($i=0; $i<$y; $i++)
					{
						$fld = $adb->field_name($result, $i);
						if ($fld->name == "Potentials_Amount")
							$fieldvalue = convertFromMasterCurrency($custom_field_values[$i],$current_user->conv_rate);
						else
							$fieldvalue = getTranslatedString($custom_field_values[$i]);

						$fieldvalue = str_replace("<", "&lt;", $fieldvalue);
						$fieldvalue = str_replace(">", "&gt;", $fieldvalue);	

						//Check For Role based pick list 
						$temp_val= $fld->name;
						if(is_array($picklistarray))
							if(array_key_exists($temp_val,$picklistarray))
							{
								if(!in_array($custom_field_values[$i],$picklistarray[$fld->name]) && $custom_field_values[$i] != '')
								{
									$fieldvalue =$app_strings['LBL_NOT_ACCESSIBLE'];
								}
							}
						if(is_array($picklistarray[1]))
							if(array_key_exists($temp_val,$picklistarray[1]))
							{

								$temp =explode(",",str_ireplace(' |##| ',',',$fieldvalue));
								$temp_val = Array();
								foreach($temp as $key =>$val)
								{
										if(!in_array(trim($val),$picklistarray[1][$fld->name]) && trim($val) != '')
										{
											$temp_val[]=$app_strings['LBL_NOT_ACCESSIBLE'];
										}
										else
											$temp_val[]=$val;
								}
								$fieldvalue =(is_array($temp_val))?implode(", ",$temp_val):'';
							}


						if($fieldvalue == "" )
						{
							$fieldvalue = "-";
						}
						else if(stristr($fieldvalue,"|##|"))
						{
							$fieldvalue = str_ireplace(' |##| ',', ',$fieldvalue);
						}
						else if($fld_type == "date" || $fld_type == "datetime") {
							$fieldvalue = getDisplayDate($fieldvalue);
						}
						if(($lastvalue == $fieldvalue) && $this->reporttype == "summary")
						{
							if($this->reporttype == "summary")
							{
								$valtemplate .= "<td style='border-top:1px dotted #FFFFFF;'>&nbsp;</td>";									
							}else
							{
								$valtemplate .= "<td>".$fieldvalue."</td>";
							}
						}else if(($secondvalue == $fieldvalue) && $this->reporttype == "summary")
						{
							if($lastvalue == $newvalue)
							{
								$valtemplate .= "<td style='border-top:1px dotted #FFFFFF;'>&nbsp;</td>";	
							}else
							{
								$valtemplate .= "<td>".$fieldvalue."</td>";
							}
						}
						else if(($thirdvalue == $fieldvalue) && $this->reporttype == "summary")
						{
							if($secondvalue == $snewvalue)
							{
								$valtemplate .= "<td style='border-top:1px dotted #FFFFFF;'>&nbsp;</td>";
							}else
							{
								$valtemplate .= "<td>".$fieldvalue."</td>";
							}
						}
						else
						{
							if($this->reporttype == "tabular")
							{
								$valtemplate .= "<td>".$fieldvalue."</td>";
							}else
							{
								$valtemplate .= "<td>".$fieldvalue."</td>";
							}
						}
					  }
					 $valtemplate .= "</tr>";
					 $lastvalue = $newvalue;
					 $secondvalue = $snewvalue;
					 $thirdvalue = $tnewvalue;
					 $arr_val[] = $arraylists;
					 set_time_limit(0);
				}while($custom_field_values = $adb->fetch_array($result));
				
				$sHTML = '<tr>'.$header.'</tr>'.$valtemplate;	
				$return_data[] = $sHTML;
				$return_data[] = $noofrows;
				return $return_data;
			}
		}elseif($outputformat == "PRINT_TOTAL")
		{
			$escapedchars = Array('_SUM','_AVG','_MIN','_MAX');
			$sSQL = $this->sGetSQLforReport($this->reportid,$filterlist,"COLUMNSTOTOTAL");
			if(isset($this->totallist))
			{
				if($sSQL != "")
				{
					$result = $adb->query($sSQL);
					$y=$adb->num_fields($result);
					$custom_field_values = $adb->fetch_array($result);

					$coltotalhtml .= '<table width="100%" border="0" cellpadding="5" cellspacing="0" align="center" class="printReport" ><tr><th>'.$mod_strings[Totals].'</th><th>'.$mod_strings[SUM].'</th><th>'.$mod_strings[AVG].'</th><th>'.$mod_strings[MIN].'</th><th>'.$mod_strings[MAX].'</th></tr>';

					foreach($this->totallist as $key=>$value)
					{
						$fieldlist = explode(":",$key);
						$totclmnflds[str_replace($escapedchars," ",$fieldlist[3])] = str_replace($escapedchars," ",$fieldlist[3]);
					}

					for($i =0;$i<$y;$i++)
					{
						$fld = $adb->field_name($result, $i);
						$keyhdr[$fld->name] = $custom_field_values[$i];

					}
					foreach($totclmnflds as $key=>$value)
					{

						$coltotalhtml .= '<tr valign=top><td>'.str_replace($modules," ",$value).'</td>';
						$arraykey = trim($value).'_SUM';
						if(isset($keyhdr[$arraykey]))
						{
							$coltotalhtml .= '<td>'.$keyhdr[$arraykey].'</td>';
						}else
						{
							$coltotalhtml .= '<td>&nbsp;</td>';
						}

						$arraykey = trim($value).'_AVG';
						if(isset($keyhdr[$arraykey]))
						{
							$coltotalhtml .= '<td>'.$keyhdr[$arraykey].'</td>';
						}else
						{
							$coltotalhtml .= '<td>&nbsp;</td>';
						}

						$arraykey = trim($value).'_MIN';
						if(isset($keyhdr[$arraykey]))
						{
							$coltotalhtml .= '<td>'.$keyhdr[$arraykey].'</td>';
						}else
						{
							$coltotalhtml .= '<td>&nbsp;</td>';
						}

						$arraykey = trim($value).'_MAX';
						if(isset($keyhdr[$arraykey]))
						{
							$coltotalhtml .= '<td>'.$keyhdr[$arraykey].'</td>';
						}else
						{
							$coltotalhtml .= '<td>&nbsp;</td>';
						}

						$coltotalhtml .= '<tr>';
					}

					$coltotalhtml .= "</table>";
				}
			}			
			return $coltotalhtml;
		}
	}

	//<<<<<<<new>>>>>>>>>>
	function getColumnsTotal($reportid)
	{
		global $adb;
		global $modules;
		global $log;
		
		$query = "select * from vtiger_reportmodules where reportmodulesid =?";
		$res = $adb->pquery($query , array($reportid));
		$modrow = $adb->fetch_array($res);
		$premod = $modrow["primarymodule"];
		$secmod = $modrow["secondarymodules"];
		$coltotalsql = "select vtiger_reportsummary.* from vtiger_report";
		$coltotalsql .= " inner join vtiger_reportsummary on vtiger_report.reportid = vtiger_reportsummary.reportsummaryid";
		$coltotalsql .= " where vtiger_report.reportid =?";

		$result = $adb->pquery($coltotalsql, array($reportid));

		while($coltotalrow = $adb->fetch_array($result))
		{
			$fieldcolname = $coltotalrow["columnname"];

			if($fieldcolname != "none")
			{
				$fieldlist = explode(":",$fieldcolname);
			if((CheckColumnPermission($fieldlist[1],$fieldlist[2],$premod) != "false") || (CheckColumnPermission($fieldlist[1],$fieldlist[2],$secmod)) != "false")
			{
				if($fieldlist[4] == 2)
				{
					$stdfilterlist[$fieldcolname] = "sum(".$fieldlist[1].".".$fieldlist[2].") '".$fieldlist[3]."'";
				}
				if($fieldlist[4] == 3)
				{
					//Fixed average calculation issue due to NULL values ie., when we use avg() function, NULL values will be ignored.to avoid this we use (sum/count) to find average.
					//$stdfilterlist[$fieldcolname] = "avg(".$fieldlist[1].".".$fieldlist[2].") '".$fieldlist[3]."'";
					$stdfilterlist[$fieldcolname] = "(sum(".$fieldlist[1].".".$fieldlist[2].")/count(*)) '".$fieldlist[3]."'";
				}
				if($fieldlist[4] == 4)
				{
					$stdfilterlist[$fieldcolname] = "min(".$fieldlist[1].".".$fieldlist[2].") '".$fieldlist[3]."'";
				}
				if($fieldlist[4] == 5)
				{
					$stdfilterlist[$fieldcolname] = "max(".$fieldlist[1].".".$fieldlist[2].") '".$fieldlist[3]."'";
				}
			}
			}
		}
		$log->info("ReportRun :: Successfully returned getColumnsTotal".$reportid);
		return $stdfilterlist;
	}
	//<<<<<<new>>>>>>>>>


	/** function to get query for the columns to total for the given reportid    
	 *  @ param $reportid : Type integer
	 *  This returns columnstoTotal query for the reportid 
	 */

	function getColumnsToTotalColumns($reportid)
	{
		global $adb;
		global $modules;
		global $log;

		$sreportstdfiltersql = "select vtiger_reportsummary.* from vtiger_report"; 
		$sreportstdfiltersql .= " inner join vtiger_reportsummary on vtiger_report.reportid = vtiger_reportsummary.reportsummaryid"; 
		$sreportstdfiltersql .= " where vtiger_report.reportid =?";

		$result = $adb->pquery($sreportstdfiltersql, array($reportid));
		$noofrows = $adb->num_rows($result);

		for($i=0; $i<$noofrows; $i++)
		{
			$fieldcolname = $adb->query_result($result,$i,"columnname");

			if($fieldcolname != "none")
			{
				$fieldlist = explode(":",$fieldcolname);
				if($fieldlist[4] == 2)
				{
					$sSQLList[] = "sum(".$fieldlist[1].".".$fieldlist[2].") ".$fieldlist[3];
				}
				if($fieldlist[4] == 3)
				{
					$sSQLList[] = "avg(".$fieldlist[1].".".$fieldlist[2].") ".$fieldlist[3];
				}
				if($fieldlist[4] == 4)
				{
					$sSQLList[] = "min(".$fieldlist[1].".".$fieldlist[2].") ".$fieldlist[3];
				}
				if($fieldlist[4] == 5)
				{
					$sSQLList[] = "max(".$fieldlist[1].".".$fieldlist[2].") ".$fieldlist[3];
				}
			}
		}
		if(isset($sSQLList))
		{
			$sSQL = implode(",",$sSQLList);
		}
		$log->info("ReportRun :: Successfully returned getColumnsToTotalColumns".$reportid);
		return $sSQL;
	}
	/** Function to convert the Report Header Names into i18n
	 *  @param $fldname: Type Varchar
	 *  Returns Language Converted Header Strings	
	 **/ 
	function getLstringforReportHeaders($fldname)
	{
		global $modules,$current_language,$current_user;
		$rep_header = ltrim(str_replace($modules," ",$fldname));
		$rep_header_temp = ereg_replace(" ","_",$rep_header);
		$rep_module = ereg_replace('_'.$rep_header_temp,"",$fldname);
		$temp_mod_strings = return_module_language($current_language,$rep_module);	
		// htmlentities should be decoded in field names (eg. &). Noticed for fields like 'Terms & Conditions', 'S&H Amount'
		$rep_header = decode_html($rep_header);
		$curr_symb = "";
                if($rep_header == 'Amount')
                        $curr_symb = "(in ".$current_user->currency_symbol.")";
                if($temp_mod_strings[$rep_header] != '')
                {
                        $rep_header = $temp_mod_strings[$rep_header];
                        $rep_header .=$curr_symb;
                }
		return $rep_header;  
	}

	/** Function to get picklist value array based on profile
	 *          *  returns permitted fields in array format
	 **/


	function getAccessPickListValues()
	{
		global $adb;
		global $current_user;
		$id = array(getTabid($this->primarymodule));
		if($this->secondarymodule != '')
			array_push($id,  getTabid($this->secondarymodule));

		$query = 'select fieldname,columnname,fieldid,fieldlabel,tabid,uitype from vtiger_field where tabid in('. generateQuestionMarks($id) .') and uitype in (15,16,111,33,55)'; //and columnname in (?)';
		$result = $adb->pquery($query, $id);//,$select_column));
		$roleid=$current_user->roleid;
		$subrole = getRoleSubordinates($roleid);
		if(count($subrole)> 0)
		{
			$roleids = $subrole;
			array_push($roleids, $roleid);
		}
		else
		{
			$roleids = $roleid;
		}

		$temp_status = Array();
		for($i=0;$i < $adb->num_rows($result);$i++)
		{
			$fieldname = $adb->query_result($result,$i,"fieldname");
			$fieldlabel = $adb->query_result($result,$i,"fieldlabel");
			$tabid = $adb->query_result($result,$i,"tabid");
			$uitype = $adb->query_result($result,$i,"uitype");
			if(!in_array($fieldname,array('activitytype','visibility','duration_minutes','recurringtype','hdnTaxType')))
			{
				$fieldlabel1 = str_replace(" ","_",$fieldlabel);
				$keyvalue = getTabModuleName($tabid)."_".$fieldlabel1;
				$fieldvalues = Array();
				if (count($roleids) > 1) {
					$mulsel="select distinct $fieldname from vtiger_$fieldname inner join vtiger_role2picklist on vtiger_role2picklist.picklistvalueid = vtiger_$fieldname.picklist_valueid where roleid in (\"". implode($roleids,"\",\"") ."\") and picklistid in (select picklistid from vtiger_$fieldname) order by sortid asc";
				} else {
					$mulsel="select distinct $fieldname from vtiger_$fieldname inner join vtiger_role2picklist on vtiger_role2picklist.picklistvalueid = vtiger_$fieldname.picklist_valueid where roleid ='".$roleid."' and picklistid in (select picklistid from vtiger_$fieldname) order by sortid asc";
				}
				if($fieldname != 'firstname')
				$mulselresult = $adb->query($mulsel);
				for($j=0;$j < $adb->num_rows($mulselresult);$j++)
				{
					$fieldvalues[] = $adb->query_result($mulselresult,$j,$fieldname);
				}
				$field_count = count($fieldvalues);
				if( $uitype == 111 && $field_count > 0 && ($fieldname == 'taskstatus' || $fieldname == 'eventstatus'))
				{
					$temp_count =count($temp_status[$keyvalue]);
					if($temp_count > 0)
					{
						for($t=0;$t < $field_count;$t++)
						{
							$temp_status[$keyvalue][($temp_count+$t)] = $fieldvalues[$t];
						}
						$fieldvalues = $temp_status[$keyvalue];
					}
					else
						$temp_status[$keyvalue] = $fieldvalues;
				}

				if($uitype == 33)
					$fieldlists[1][$keyvalue] = $fieldvalues;
				else if($uitype == 55 && $fieldname == 'salutationtype')
			                $fieldlists[$keyvalue] = $fieldvalues;
			        else if($uitype == 16 || $uitype == 15 || $uitype == 111)
				        $fieldlists[$keyvalue] = $fieldvalues; 
			}
		}
		return $fieldlists;
	}


}
?>
