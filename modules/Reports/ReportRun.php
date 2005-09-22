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
global $vtlog;

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once('include/database/PearDatabase.php');
require_once ($theme_path."layout_utils.php");
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
	
	var $reportsql = Array("Leads"=>"from crmentity as crmentityLeads inner join leaddetails on crmentityLeads.crmid=leaddetails.leadid left join leadsubdetails on leadsubscriptionid = leaddetails.leadid left join leadaddress on leadaddress.leadaddressid = leaddetails.leadid left join leadscf on leadscf.leadid = leaddetails.leadid",

			       "Contacts"=>"from crmentity as crmentityContacts inner join contactdetails on crmentityContacts.crmid = contactdetails.contactid left join contactsubdetails on contactsubdetails.contactsubscriptionid = contactdetails.contactid left join contactaddress on contactaddress.contactaddressid = contactdetails.contactid left join contactscf on contactscf.contactid = contactdetails.contactid",

			       "Accounts"=>"from crmentity as crmentityAccounts inner join account on account.accountid = crmentityAccounts.crmid left join accountbillads on accountbillads.accountaddressid = account.accountid left join accountshipads on accountshipads.accountaddressid = account.accountid left join accountscf on accountscf.accountid = account.accountid",

			       "Potentials"=>"from crmentity as crmentityPotentials inner join potential on potential.potentialid = crmentity.crmid left join potentialscf on potentialscf.potentialid = potential.potentialid",

			       "Notes"=>"from crmentity as crmentityNotes inner join notes on notes.notesid = crmentityNotes.crmid left join senotesrel on senotesrel.notesid = notes.notesid",
				
			       "Emails"=>"from crmentity as crmentityEmails inner join emails on emails.emailid = crmentityEmails.crmid left join activity on activity.activityid = seactivityrel.activityid left join seactivityrel on seactivityrel.crmid = emails.emailid"

			       );

	
	function ReportRun($reportid)
	{
		$oReport = new Reports($reportid);
		$this->reportid = $reportid;
		$this->primarymodule = $oReport->primodule;
		$this->secondarymodule = $oReport->secmodule; 
		$this->reporttype = $oReport->reporttype;
	}
	
	function getQueryColumnsList($reportid)
	{
		global $adb;
		global $modules;
		global $vtlog;

		$ssql = "select selectcolumn.* from report inner join selectquery on selectquery.queryid = report.queryid";
                $ssql .= " left join selectcolumn on selectcolumn.queryid = selectquery.queryid";
		$ssql .= " where report.reportid =".$reportid;
                $ssql .= " order by selectcolumn.columnindex";

                $result = $adb->query($ssql);

		while($columnslistrow = $adb->fetch_array($result))
		{
			$fieldcolname = $columnslistrow["columnname"];
			$selectedfields = explode(":",$fieldcolname);

			$querycolumns = $this->getEscapedColumns($selectedfields);
			if($querycolumns == "")
			{
				$columnslist[$fieldcolname] = $selectedfields[0].".".$selectedfields[1]." '".$selectedfields[2]."'";
			}else
			{
				$columnslist[$fieldcolname] = $querycolumns;
			}
		}
		//print_r($columnslist);
		$vtlog->logthis("ReportRun :: Successfully returned getQueryColumnsList".$reportid,"info");
		return $columnslist;		
	}

	function getEscapedColumns($selectedfields)
	{
		$fieldname = $selectedfields[3];
		/*if($fieldname == "assigned_user_id")
		{
			$querycolumn = "usersRel.user_name"." ".$selectedfields[2];
		}*/
		/*if($fieldname == "account_id")
		{
			$querycolumn = "accountRel.accountname"." ".$selectedfields[2];
		}*/
		if($fieldname == "parent_id")
		{
			$querycolumn = "crmentityRel.setype Entity_type";
			//$querycolumn = "case crmentityRel.setype when 'Accounts' then accountRel.accountname when 'Leads' then leaddetailsRel.lastname when 'Potentials' then potentialRel.potentialname End"." ".$selectedfields[2].", crmentityRel.setype Entity_type";
		}
		/*if($fieldname == "contact_id")
		{
			$querycolumn = "contactdetailsRel.lastname"." ".$selectedfields[2];
		}*/
		/*if($fieldname == "vendor_id")
		{
			$querycolumn = "vendorRel.name"." ".$selectedfields[2];
		}
		if($fieldname == "potential_id")
                {
                        $querycolumn = "potentialRel.potentialname"." ".$selectedfields[2];
                }
		if($fieldname == "assigned_user_id1")
                {
                        $querycolumn = "usersRel1.user_name"." ".$selectedfields[2];
                }*/
		return $querycolumn;
	}
	
	function getSelectedColumnsList($reportid)
	{
	
		global $adb;
		global $modules;
		global $vtlog;
		
		$ssql = "select selectcolumn.* from report inner join selectquery on selectquery.queryid = report.queryid"; 
		$ssql .= " left join selectcolumn on selectcolumn.queryid = selectquery.queryid where report.reportid =".$reportid; 
		$ssql .= " order by selectcolumn.columnindex";
		
		$result = $adb->query($ssql);
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
					$sSQLList[] = $selectedfields[0].".".$selectedfields[1]." '".$selectedfields[2]."'";
				}
			}
		}
		$sSQL .= implode(",",$sSQLList);
		
		$vtlog->logthis("ReportRun :: Successfully returned getSelectedColumnsList".$reportid,"info");
		return $sSQL;
	}
	
	function getAdvComparator($comparator,$value)
        {

		global $vtlog;

		if($comparator == "e")
                {
                        if(trim($value) != "")
			{
				$rtvalue = " = ".PearDatabase::quote($value);
			}else
			{
				$rtvalue = " is NULL";
			}
                }
                if($comparator == "n")
                {
                        if(trim($value) != "")
			{
				$rtvalue = " <> ".PearDatabase::quote($value);
			}else
			{
				$rtvalue = " is NOT NULL";
			}
                }
                if($comparator == "s")
                {
                        $rtvalue = " like ".PearDatabase::quote($value."%");
                }
                if($comparator == "c")
                {
                        $rtvalue = " like ".PearDatabase::quote("%".$value."%");
                }
                if($comparator == "k")
                {
                        $rtvalue = " not like ".PearDatabase::quote("%".$value."%");
                }
                if($comparator == "l")
                {
                        $rtvalue = " < ".PearDatabase::quote($value);
                }
                if($comparator == "g")
		{
                        $rtvalue = " > ".PearDatabase::quote($value);
                }
                if($comparator == "m")
                {
                        $rtvalue = " <= ".PearDatabase::quote($value);
                }
                if($comparator == "h")
                {
                        $rtvalue = " >= ".PearDatabase::quote($value);
                }

                $vtlog->logthis("ReportRun :: Successfully returned getAdvComparator","info");
		return $rtvalue;
        }
	
	function getAdvFilterList($reportid)
	{
		global $adb;
		global $modules;
		global $vtlog;

		$advfiltersql =  "select relcriteria.* from report";
		$advfiltersql .= " inner join selectquery on selectquery.queryid = report.queryid";
                $advfiltersql .= " left join relcriteria on relcriteria.queryid = selectquery.queryid";
                $advfiltersql .= " where report.reportid =".$reportid;
                $advfiltersql .= " order by relcriteria.columnindex";
		
		$result = $adb->query($advfiltersql);
		while($advfilterrow = $adb->fetch_array($result))
		{
		     $fieldcolname = $advfilterrow["columnname"];
		     $comparator = $advfilterrow["comparator"];
		     $value = $advfilterrow["value"];
		     
		     if($fieldcolname != "" && $comparator != "")
		     {
			$selectedfields = explode(":",$fieldcolname);
			$valuearray = explode(",",trim($value));
                        if(isset($valuearray) && count($valuearray) > 1)
                        {
				$advorsql = "";
				for($n=0;$n<count($valuearray);$n++)
				{
					$advorsql[] = $selectedfields[0].".".$selectedfields[1].$this->getAdvComparator($comparator,trim($valuearray[$n]));
				}
				$advorsqls = implode(" or ",$advorsql);
				$fieldvalue = " (".$advorsqls.") ";
			}else
			{
				$fieldvalue = $selectedfields[0].".".$selectedfields[1].$this->getAdvComparator($comparator,trim($value));
			}
			$advfilterlist[$fieldcolname] = $fieldvalue;		
		     }
	    						
		}

		$vtlog->logthis("ReportRun :: Successfully returned getAdvFilterList".$reportid,"info");
		return $advfilterlist;
	}	

	function getStdFilterList($reportid)
	{
		global $adb;
		global $modules;
		global $vtlog;

		$stdfiltersql = "select reportdatefilter.* from report";
		$stdfiltersql .= " inner join reportdatefilter on report.reportid = reportdatefilter.datefilterid";
		$stdfiltersql .= " where report.reportid = ".$reportid;

		$result = $adb->query($stdfiltersql);
		$stdfilterrow = $adb->fetch_array($result);
		if(isset($stdfilterrow))
		{
			$fieldcolname = $stdfilterrow["datecolumnname"];
			$datefilter = $stdfilterrow["datefilter"];
			$startdate = $stdfilterrow["startdate"];
			$enddate = $stdfilterrow["enddate"];

			if($fieldcolname != "none")
			{
				if($datefilter == "custom")
                                {
                                        if($startdate != "0000-00-00" && $enddate != "0000-00-00")
                                        {
                                                $selectedfields = explode(":",$fieldcolname);
                                                $stdfilterlist[$fieldcolname] = $selectedfields[0].".".$selectedfields[1]." between '".$startdate."' and '".$enddate."'";
                                        }
                                }else
                                {
                                        $selectedfields = explode(":",$fieldcolname);
                                        $startenddate = $this->getStandarFiltersStartAndEndDate($datefilter);
                                        if($startenddate[0] != "" && $startenddate[1] != "")
                                        {
                                                $stdfilterlist[$fieldcolname] = $selectedfields[0].".".$selectedfields[1]." between '".$startenddate[0]." 00:00:00' and '".$startenddate[1]." 23:59:00'";
                                        }
                                }

			}		
		}
		$vtlog->logthis("ReportRun :: Successfully returned getStdFilterList".$reportid,"info");
		return $stdfilterlist;
	}
	function RunTimeFilter($filtercolumn,$filter,$startdate,$enddate)
	{
		if($filtercolumn != "none")
		{
			if($filter == "custom")
			{
				if($startdate != "" && $enddate != "")
				{
					$selectedfields = explode(":",$filtercolumn);
					$stdfilterlist[$filtercolumn] = $selectedfields[0].".".$selectedfields[1]." between '".$startdate."' and '".$enddate."'";
				}
			}else
			{
				if($startdate != "" && $enddate != "")
                                {
				$selectedfields = explode(":",$filtercolumn);
				$startenddate = $this->getStandarFiltersStartAndEndDate($filter);
				if($startenddate[0] != "" && $startenddate[1] != "")
				{
					$stdfilterlist[$filtercolumn] = $selectedfields[0].".".$selectedfields[1]." between '".$startenddate[0]." 00:00:00' and '".$startenddate[1]." 23:59:00'";
				}
				}
			}

		}

		return $stdfilterlist;

	}
	function getStandardCriterialSql($reportid)
	{
		global $adb;
		global $modules;
		global $vtlog;

		$sreportstdfiltersql = "select reportdatefilter.* from report"; 
		$sreportstdfiltersql .= " inner join reportdatefilter on report.reportid = reportdatefilter.datefilterid"; 
		$sreportstdfiltersql .= " where report.reportid =".$reportid;
		
		$result = $adb->query($sreportstdfiltersql);
		$noofrows = $adb->num_rows($result);
	
		for($i=0; $i<$noofrows; $i++)
		{
			$fieldcolname = $adb->query_result($result,$i,"datecolumnname");
			$datefilter = $adb->query_result($result,$i,"datefilter");
			$startdate = $adb->query_result($result,$i,"startdate");
			$enddate = $adb->query_result($result,$i,"enddate");
			
			if($fieldcolname != "none")
			{
				if($datefilter == "custom")
				{
					if($startdate != "0000-00-00" && $enddate != "0000-00-00")
					{
						$selectedfields = explode(":",$fieldcolname);
						$sSQL .= $selectedfields[0].".".$selectedfields[1]." between '".$startdate."' and '".$enddate."'";
					}
				}else
				{
					$selectedfields = explode(":",$fieldcolname);
					$startenddate = $this->getStandarFiltersStartAndEndDate($datefilter);
					if($startenddate[0] != "" && $startenddate[1] != "")
					{
						$sSQL .= $selectedfields[0].".".$selectedfields[1]." between '".$startenddate[0]."' and '".$startenddate[1]."'";
					}
				}
			}
		}
		$vtlog->logthis("ReportRun :: Successfully returned getStandardCriterialSql".$reportid,"info");
		return $sSQL;
	}

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
			    
			$datevalue[0] = "2005-07-01";
			$datevalue[1] = "2005-09-30";
			}                        
			elseif($type == "prevfq" )
			{
			    
			$datevalue[0] = "2005-01-01";
			$datevalue[1] = "2005-03-31";
			}                
			elseif($type == "thisfq" )
			{
			$datevalue[0] = "2005-04-01";
			$datevalue[1] = "2005-06-30";
			}                
			else
			{
			$datevalue[0] = "";
			$datevalue[1] = "";
			}

			//$vtlog->logthis("ReportRun :: Successfully returned getQueryColumnsList".$reportid,"info");
			return $datevalue;
	}

	function getGroupingList($reportid)
	{
		global $adb;
		global $modules;
		global $vtlog;

		$sreportsortsql = "select reportsortcol.* from report";
                $sreportsortsql .= " inner join reportsortcol on report.reportid = reportsortcol.reportid";
                $sreportsortsql .= " where report.reportid =".$reportid." order by reportsortcol.sortcolid";
		
		$result = $adb->query($sreportsortsql);

		while($reportsortrow = $adb->fetch_array($result))
		{
			$fieldcolname = $reportsortrow["columnname"];
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
				$sqlvalue = $selectedfields[0].".".$selectedfields[1]." ".$sortorder;
				$grouplist[$fieldcolname] = $sqlvalue;
				$this->groupbylist[$fieldcolname] = $selectedfields[0].".".$selectedfields[1]." ".$selectedfields[2];
			}
		}
		$vtlog->logthis("ReportRun :: Successfully returned getGroupingList".$reportid,"info");
		return $grouplist;
	}

	function getSelectedOrderbyList($reportid)
	{
	
		global $adb;
		global $modules;
		global $vtlog;
		//$modules = array("Leads_", "Accounts_", "Potentials_", "Contacts_","_");
	
		$sreportsortsql = "select reportsortcol.* from report"; 
		$sreportsortsql .= " inner join reportsortcol on report.reportid = reportsortcol.reportid"; 
		$sreportsortsql .= " where report.reportid =".$reportid." order by reportsortcol.sortcolid";
		
		$result = $adb->query($sreportsortsql);
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
				$sSQL .= $selectedfields[0].".".$selectedfields[1]." ".$sortorder;
				$this->orderbylistsql .= $selectedfields[0].".".$selectedfields[1]." ".$selectedfields[2];
			}
		}
		$vtlog->logthis("ReportRun :: Successfully returned getSelectedOrderbyList".$reportid,"info");
		return $sSQL;
	}

	function getSQLforPrimaryModule($module)
	{
	   global $vtlog;

	   if($module != "")
	   {
		foreach($this->reportsql as $reportmodule=>$reportquery)
		{
			if($reportmodule == $module)
			{
				$sql = $reportquery;
			}
		}
	   }
	   $vtlog->logthis("ReportRun :: Successfully returned getSQLforPrimaryModule".$module,"info");
	   return $sql;
	}
	function getRelatedModulesQuery($module,$secmodule)
	{
		global $vtlog;

		if($module == "Contacts")
		{
			if($secmodule == "Accounts")
			{
				$query = "left join account on account.accountid = contactdetails.accountid
                                left join crmentity as crmentityAccounts on crmentityAccounts.crmid=account.accountid
                                left join accountbillads on account.accountid=accountbillads.accountaddressid
                                left join accountshipads on account.accountid=accountshipads.accountaddressid
                                left join accountscf on account.accountid = accountscf.accountid
                                left join account as accountAccounts on accountAccounts.accountid = account.parentid
                                left join users as usersAccounts on usersAccounts.id = crmentityAccounts.smownerid ";
			}
			if($secmodule == "Potentials")
			{
				$query = "left join  potential on potential.accountid = contactdetails.accountid
				left join crmentity as crmentityPotentials on crmentityPotentials.crmid=potential.potentialid
				left join account as accountPotentials on potential.accountid = accountPotentials.accountid
				left join potentialscf on potentialscf.potentialid = potential.potentialid
				left join users as usersPotentials on usersPotentials.id = crmentityPotentials.smownerid ";
			}
			if($secmodule == "Quotes")
                        {
                                $query = "left join quotes on quotes.contactid = contactdetails.contactid
                                left join crmentity as crmentityQuotes on crmentityQuotes.crmid=quotes.quoteid
                                left join quotesbillads on quotes.quoteid=quotesbillads.quotebilladdressid
                                left join quotesshipads on quotes.quoteid=quotesshipads.quoteshipaddressid
                                left join users as usersQuotes on usersQuotes.id = crmentityQuotes.smownerid
                                left join users as usersRel1 on usersRel1.id = quotes.inventorymanager
                                left join potential as potentialRel on potentialRel.potentialid = quotes.potentialid
                                left join contactdetails as contactdetailsQuotes on contactdetailsQuotes.contactid = quotes.contactid
                                left join account as accountQuotes on accountQuotes.accountid = quotes.accountid ";
                        }
                        if($secmodule == "Orders")
                        {
                                $query = "left join purchaseorder on purchaseorder.contactid = contactdetails.contactid
                                left join crmentity as crmentityOrders on crmentityOrders.crmid=purchaseorder.purchaseorderid
                                left join pobillads on purchaseorder.purchaseorderid=pobillads.pobilladdressid
                                left join poshipads on purchaseorder.purchaseorderid=poshipads.poshipaddressid
                                left join users as usersOrders on usersOrders.id = crmentityOrders.smownerid
                                left join vendor as vendorRel on vendorRel.vendorid = purchaseorder.vendorid
                                left join contactdetails as contactdetailsOrders on contactdetailsOrders.contactid = purchaseorder.contactid ";
                        }

		}

		if($module == "Accounts")
		{
			if($secmodule == "Potentials")
			{
				$query = "left join potential on potential.accountid = account.accountid
				left join crmentity as crmentityPotentials on crmentityPotentials.crmid=potential.potentialid
                                left join potentialscf on potentialscf.potentialid = potential.potentialid
                                left join users as usersPotentials on usersPotentials.id = crmentityPotentials.smownerid ";

			}
			if($secmodule == "Contacts")
			{
				$query = "left join contactdetails on contactdetails.accountid = account.accountid
				left join crmentity as crmentityContacts on crmentityContacts.crmid = contactdetails.contactid
                                left join contactaddress on contactdetails.contactid = contactaddress.contactaddressid
                                left join contactsubdetails on contactdetails.contactid = contactsubdetails.contactsubscriptionid
				left join contactdetails as contactdetailsContacts on contactdetailsContacts.contactid = contactdetails.reportsto
				left join account as accountContacts on accountContacts.accountid = contactdetails.accountid 
                                left join contactscf on contactdetails.contactid = contactscf.contactid
                                left join users as usersContacts on usersContacts.id = crmentityContacts.smownerid ";
			}
			if($secmodule == "Quotes")
			{
				$query = "left join quotes on quotes.accountid = account.accountid
                                left join crmentity as crmentityQuotes on crmentityQuotes.crmid=quotes.quoteid
                                left join quotesbillads on quotes.quoteid=quotesbillads.quotebilladdressid
                                left join quotesshipads on quotes.quoteid=quotesshipads.quoteshipaddressid
                                left join users as usersQuotes on usersQuotes.id = crmentityQuotes.smownerid
                                left join users as usersRel1 on usersRel1.id = quotes.inventorymanager
                                left join potential as potentialRel on potentialRel.potentialid = quotes.potentialid
                                left join contactdetails as contactdetailsQuotes on contactdetailsQuotes.contactid = quotes.contactid
                                left join account as accountQuotes on accountQuotes.accountid = quotes.accountid ";
			}
			if($secmodule == "Orders")
			{
				$query = "left join purchaseorder on purchaseorder.accountid = account.accountid
                                left join crmentity as crmentityOrders on crmentityOrders.crmid=purchaseorder.purchaseorderid
                                left join pobillads on purchaseorder.purchaseorderid=pobillads.pobilladdressid
                                left join poshipads on purchaseorder.purchaseorderid=poshipads.poshipaddressid
                                left join users as usersOrders on usersOrders.id = crmentityOrders.smownerid
                                left join vendor as vendorRel on vendorRel.vendorid = purchaseorder.vendorid
                                left join contactdetails as contactdetailsOrders on contactdetailsOrders.contactid = purchaseorder.contactid ";
			}
			if($secmodule == "Invoice")
			{
				$query = "left join invoice on invoice.accountid = account.accountid
                                left join crmentity as crmentityInvoice on crmentityInvoice.crmid=invoice.invoiceid
                                left join invoicebillads on invoice.invoiceid=invoicebillads.invoicebilladdressid
                                left join invoiceshipads on invoice.invoiceid=invoiceshipads.invoiceshipaddressid
                                left join users as usersInvoice on usersInvoice.id = crmentityInvoice.smownerid
                                left join account as accountInvoice on accountInvoice.accountid = invoice.accountid ";
			}
			if($secmodule == "Products")
			{
				$query = "left join seproductsrel on seproductsrel.crmid = account.accountid
				left join products on products.productid = seproductsrel.productid
                                left join crmentity as crmentityProducts on crmentityProducts.crmid=products.productid
                                left join productcf on products.productid = productcf.productid
                                left join users as usersProducts on usersProducts.id = crmentityProducts.smownerid
                                left join contactdetails as contactdetailsProducts on contactdetailsProducts.contactid = products.contactid
                                left join vendor as vendorRel on vendorRel.vendorid = products.vendor_id
                                left join crmentity as crmentityRel on crmentityRel.crmid = seproductsrel.crmid
                                left join account as accountRel on accountRel.accountid=crmentityRel.crmid
                                left join leaddetails as leaddetailsRel on leaddetailsRel.leadid = crmentityRel.crmid
                                left join potential as potentialRel on potentialRel.potentialid = crmentityRel.crmid ";
			}
		}
		if($module == "Quotes")
		{
			if($secmodule == "Accounts")
                        {
                                $query = "left join account on account.accountid = quotes.accountid
                                left join crmentity as crmentityAccounts on crmentityAccounts.crmid=account.accountid
                                left join accountbillads on account.accountid=accountbillads.accountaddressid
                                left join accountshipads on account.accountid=accountshipads.accountaddressid
                                left join accountscf on account.accountid = accountscf.accountid
                                left join account as accountAccounts on accountAccounts.accountid = account.parentid
                                left join users as usersAccounts on usersAccounts.id = crmentityAccounts.smownerid ";
                        }
			if($secmodule == "Potentials")
                        {
                                $query = "left join potential on potential.potentialid = quotes.potentialid
                                left join crmentity as crmentityPotentials on crmentityPotentials.crmid=potential.potentialid 
				left join potentialscf on potentialscf.potentialid = potential.potentialid
                                left join users as usersPotentials on usersPotentials.id = crmentityPotentials.smownerid ";

                        }
			if($secmodule == "Contacts")
                        {
                                $query = "left join contactdetails on contactdetails.contactid = quotes.contactid
                                left join crmentity as crmentityContacts on crmentityContacts.crmid = contactdetails.contactid
                                left join contactaddress on contactdetails.contactid = contactaddress.contactaddressid
                                left join contactsubdetails on contactdetails.contactid = contactsubdetails.contactsubscriptionid
                                left join contactdetails as contactdetailsContacts on contactdetailsContacts.contactid = contactdetails.reportsto
                                left join account as accountContacts on accountContacts.accountid = contactdetails.accountid

                                left join contactscf on contactdetails.contactid = contactscf.contactid
                                left join users as usersContacts on usersContacts.id = crmentityContacts.smownerid ";
                        }

		}
		if($module == "Orders")
		{
			if($secmodule == "Accounts")
                        {
                                $query = "left join account on account.accountid = purchaseorder.accountid
                                left join crmentity as crmentityAccounts on crmentityAccounts.crmid=account.accountid
                                left join accountbillads on account.accountid=accountbillads.accountaddressid
                                left join accountshipads on account.accountid=accountshipads.accountaddressid
                                left join accountscf on account.accountid = accountscf.accountid
                                left join account as accountAccounts on accountAccounts.accountid = account.parentid
                                left join users as usersAccounts on usersAccounts.id = crmentityAccounts.smownerid ";
                        }
			if($secmodule == "Contacts")
			{
				$query = "left join contactdetails on contactdetails.contactid = purchaseorder.contactid
                                left join crmentity as crmentityContacts on crmentityContacts.crmid = contactdetails.contactid
                                left join contactaddress on contactdetails.contactid = contactaddress.contactaddressid
                                left join contactsubdetails on contactdetails.contactid = contactsubdetails.contactsubscriptionid
                                left join contactdetails as contactdetailsContacts on contactdetailsContacts.contactid = contactdetails.reportsto
                                left join account as accountContacts on accountContacts.accountid = contactdetails.accountid

                                left join contactscf on contactdetails.contactid = contactscf.contactid
                                left join users as usersContacts on usersContacts.id = crmentityContacts.smownerid ";
			}
		}
		if($module == "Invoice")
		{
			if($secmodule == "Accounts")
			{
				$query = "left join account on account.accountid = invoice.accountid
                                left join crmentity as crmentityAccounts on crmentityAccounts.crmid=account.accountid
                                left join accountbillads on account.accountid=accountbillads.accountaddressid
                                left join accountshipads on account.accountid=accountshipads.accountaddressid
                                left join accountscf on account.accountid = accountscf.accountid
                                left join account as accountAccounts on accountAccounts.accountid = account.parentid
                                left join users as usersAccounts on usersAccounts.id = crmentityAccounts.smownerid ";
			}
		}
		if($module == "Products")
		{
			if($secmodule == "Accounts")
                        {
                                $query = "left join account on account.accountid = crmentityRel.crmid
                                left join crmentity as crmentityAccounts on crmentityAccounts.crmid=account.accountid
                                left join accountbillads on account.accountid=accountbillads.accountaddressid
                                left join accountshipads on account.accountid=accountshipads.accountaddressid
                                left join accountscf on account.accountid = accountscf.accountid
                                left join account as accountAccounts on accountAccounts.accountid = account.parentid
                                left join users as usersAccounts on usersAccounts.id = crmentityAccounts.smownerid ";
                        }
			if($secmodule == "Contacts")
                        {
                                $query = "left join contactdetails on contactdetails.contactid = products.contactid
                                left join crmentity as crmentityContacts on crmentityContacts.crmid = contactdetails.contactid
                                left join contactaddress on contactdetails.contactid = contactaddress.contactaddressid
                                left join contactsubdetails on contactdetails.contactid = contactsubdetails.contactsubscriptionid
                                left join contactdetails as contactdetailsContacts on contactdetailsContacts.contactid = contactdetails.reportsto
                                left join account as accountContacts on accountContacts.accountid = contactdetails.accountid
                                left join contactscf on contactdetails.contactid = contactscf.contactid
                                left join users as usersContacts on usersContacts.id = crmentityContacts.smownerid ";

                        }

		}
		if($module == "Potentials")
		{
			if($secmodule == "Accounts")
                        {
                                $query = "left join account on account.accountid = potential.accountid
                                left join crmentity as crmentityAccounts on crmentityAccounts.crmid=account.accountid
                                left join accountbillads on account.accountid=accountbillads.accountaddressid
                                left join accountshipads on account.accountid=accountshipads.accountaddressid
                                left join accountscf on account.accountid = accountscf.accountid
                                left join account as accountAccounts on accountAccounts.accountid = account.parentid
                                left join users as usersAccounts on usersAccounts.id = crmentityAccounts.smownerid ";
                        }
			if($secmodule == "Contacts")
			{
				$query = "left join contactdetails on contactdetails.accountid = potential.accountid
				left join crmentity as crmentityContacts on crmentityContacts.crmid = contactdetails.contactid
				left join contactaddress on contactdetails.contactid = contactaddress.contactaddressid
				left join contactsubdetails on contactdetails.contactid = contactsubdetails.contactsubscriptionid
				left join contactdetails as contactdetailsContacts on contactdetailsContacts.contactid = contactdetails.reportsto
				left join account as accountContacts on accountContacts.accountid = contactdetails.accountid
				left join contactscf on contactdetails.contactid = contactscf.contactid
				left join users as usersContacts on usersContacts.id = crmentityContacts.smownerid ";

			}
			if($secmodule == "Quotes")
			{
				$query = "left join quotes on quotes.potentialid = potential.potentialid
                                left join crmentity as crmentityQuotes on crmentityQuotes.crmid=quotes.quoteid
                                left join quotesbillads on quotes.quoteid=quotesbillads.quotebilladdressid
                                left join quotesshipads on quotes.quoteid=quotesshipads.quoteshipaddressid
                                left join users as usersQuotes on usersQuotes.id = crmentityQuotes.smownerid
                                left join users as usersRel1 on usersRel1.id = quotes.inventorymanager
                                left join potential as potentialRel on potentialRel.potentialid = quotes.potentialid
                                left join contactdetails as contactdetailsQuotes on contactdetailsQuotes.contactid = quotes.contactid
                                left join account as accountQuotes on accountQuotes.accountid = quotes.accountid ";
			}
		}
		if($module == "HelpDesk")
		{
			if($secmodule == "Products")
			{
				$query = "left join products on products.productid = troubletickets.product_id
                                left join crmentity as crmentityProducts on crmentityProducts.crmid=products.productid
                                left join productcf on products.productid = productcf.productid
                                left join users as usersProducts on usersProducts.id = crmentityProducts.smownerid
				left join contactdetails as contactdetailsProducts on contactdetailsProducts.contactid = products.contactid 
                                left join vendor as vendorRel on vendorRel.vendorid = products.vendor_id
                                left join seproductsrel on seproductsrel.productid = products.productid
                                left join crmentity as crmentityRel on crmentityRel.crmid = seproductsrel.crmid
                                left join account as accountRel on accountRel.accountid=crmentityRel.crmid
                                left join leaddetails as leaddetailsRel on leaddetailsRel.leadid = crmentityRel.crmid
                                left join potential as potentialRel on potentialRel.potentialid = crmentityRel.crmid ";
			}
		}
		if($module == "Activities")
		{
			if($secmodule == "Contacts")
			{
                                $query = "left join contactdetails on contactdetails.contactid = cntactivityrel.contactid 
                                left join crmentity as crmentityContacts on crmentityContacts.crmid = contactdetails.contactid
                                left join contactaddress on contactdetails.contactid = contactaddress.contactaddressid
                                left join contactsubdetails on contactdetails.contactid = contactsubdetails.contactsubscriptionid
                                left join contactdetails as contactdetailsContacts on contactdetailsContacts.contactid = contactdetails.reportsto
                                left join account as accountContacts on accountContacts.accountid = contactdetails.accountid
                                left join contactscf on contactdetails.contactid = contactscf.contactid
                                left join users as usersContacts on usersContacts.id = crmentityContacts.smownerid ";
			}
		}
		$vtlog->logthis("ReportRun :: Successfully returned getRelatedModulesQuery".$secmodule,"info");
		return $query;
	}
	function getReportsQuery($module)
	{
		global $vtlog;
		//echo $this->secondarymodule."<br>";
		if($module == "Leads")
		{
			$query = "from leaddetails 
				inner join crmentity as crmentityLeads on crmentityLeads.crmid=leaddetails.leadid 
				inner join leadsubdetails on leadsubdetails.leadsubscriptionid=leaddetails.leadid 
				inner join leadaddress on leadaddress.leadaddressid=leadsubdetails.leadsubscriptionid 
				inner join leadscf on leaddetails.leadid = leadscf.leadid 
				left join users as usersLeads on usersLeads.id = crmentityLeads.smownerid
				where crmentityLeads.deleted=0 and leaddetails.converted=0";
		}

		if($module == "Accounts")
                {
			$query = "from account 
				inner join crmentity as crmentityAccounts on crmentityAccounts.crmid=account.accountid 
				inner join accountbillads on account.accountid=accountbillads.accountaddressid 
				inner join accountshipads on account.accountid=accountshipads.accountaddressid 
				inner join accountscf on account.accountid = accountscf.accountid 
				left join account as accountAccounts on accountAccounts.accountid = account.parentid
				left join users as usersAccounts on usersAccounts.id = crmentityAccounts.smownerid
				".$this->getRelatedModulesQuery($module,$this->secondarymodule)."
				where crmentityAccounts.deleted=0 ";
		}

		if($module == "Contacts")
                {
			$query = "from contactdetails
				inner join crmentity as crmentityContacts on crmentityContacts.crmid = contactdetails.contactid 
				inner join contactaddress on contactdetails.contactid = contactaddress.contactaddressid 
				inner join contactsubdetails on contactdetails.contactid = contactsubdetails.contactsubscriptionid 
				inner join contactscf on contactdetails.contactid = contactscf.contactid 
				left join contactdetails as contactdetailsContacts on contactdetailsContacts.contactid = contactdetails.reportsto
				left join account as accountContacts on accountContacts.accountid = contactdetails.accountid 
				left join users as usersContacts on usersContacts.id = crmentityContacts.smownerid
				".$this->getRelatedModulesQuery($module,$this->secondarymodule)." 
				where crmentityContacts.deleted=0";
		}

		if($module == "Potentials")
		{
			$query = "from potential 
				inner join crmentity as crmentityPotentials on crmentityPotentials.crmid=potential.potentialid 
				inner join account as accountPotentials on potential.accountid = accountPotentials.accountid 
				inner join potentialscf on potentialscf.potentialid = potential.potentialid
				left join users as usersPotentials on usersPotentials.id = crmentityPotentials.smownerid  
				".$this->getRelatedModulesQuery($module,$this->secondarymodule)."
				where crmentityPotentials.deleted=0 ";
		}
		
		if($module == "Products")
		{
			$query = "from products 
				inner join crmentity as crmentityProducts on crmentityProducts.crmid=products.productid 
				left join productcf on products.productid = productcf.productid 
				left join users as usersProducts on usersProducts.id = crmentityProducts.smownerid 
				left join contactdetails as contactdetailsProducts on contactdetailsProducts.contactid = products.contactid
				left join vendor as vendorRel on vendorRel.vendorid = products.vendor_id  
				left join seproductsrel on seproductsrel.productid = products.productid 
				left join crmentity as crmentityRel on crmentityRel.crmid = seproductsrel.crmid 
				left join account as accountRel on accountRel.accountid=crmentityRel.crmid 
				left join leaddetails as leaddetailsRel on leaddetailsRel.leadid = crmentityRel.crmid 
				left join potential as potentialRel on potentialRel.potentialid = crmentityRel.crmid 
				".$this->getRelatedModulesQuery($module,$this->secondarymodule)."
				where crmentityProducts.deleted=0 ";
		}

		if($module == "HelpDesk")
		{
			$query = "from troubletickets 
				inner join crmentity as crmentityHelpDesk 
				on crmentityHelpDesk.crmid=troubletickets.ticketid 
				inner join ticketcf on ticketcf.ticketid = troubletickets.ticketid
				left join crmentity as crmentityHelpDeskRel on crmentityHelpDeskRel.crmid = troubletickets.parent_id ".
//				left join ticketcomments on ticketcomments.ticketid = troubletickets.ticketid -- for patch2
				"left join products as productsRel on productsRel.productid = troubletickets.product_id
				left join users as usersHelpDesk on crmentityHelpDesk.smownerid=usersHelpDesk.id 
				".$this->getRelatedModulesQuery($module,$this->secondarymodule)."
				where crmentityHelpDesk.deleted=0 ";
		}

		if($module == "Activities")
		{
			$query = "from activity 
				inner join crmentity as crmentityActivities on crmentityActivities.crmid=activity.activityid 
				left join cntactivityrel on cntactivityrel.activityid= activity.activityid 
				left join contactdetails as contactdetailsActivities on contactdetailsActivities.contactid= cntactivityrel.contactid
				left join users as usersActivities on usersActivities.id = crmentityActivities.smownerid
				left join seactivityrel on seactivityrel.activityid = activity.activityid
				left join crmentity as crmentityRel on crmentityRel.crmid = seactivityrel.crmid
				left join account as accountRel on accountRel.accountid=crmentityRel.crmid
				left join leaddetails as leaddetailsRel on leaddetailsRel.leadid = crmentityRel.crmid
				left join potential as potentialRel on potentialRel.potentialid = crmentityRel.crmid
				".$this->getRelatedModulesQuery($module,$this->secondarymodule)."
				WHERE crmentityActivities.deleted=0 and (activity.activitytype = 'Meeting' or activity.activitytype='Call' or activity.activitytype='Task')";
		}
		
		if($module == "Quotes")
		{
			$query = "from quotes 
				inner join crmentity as crmentityQuotes on crmentityQuotes.crmid=quotes.quoteid 
				inner join quotesbillads on quotes.quoteid=quotesbillads.quotebilladdressid 
				inner join quotesshipads on quotes.quoteid=quotesshipads.quoteshipaddressid 
				left join users as usersQuotes on usersQuotes.id = crmentityQuotes.smownerid
				left join users as usersRel1 on usersRel1.id = quotes.inventorymanager
				left join potential as potentialRel on potentialRel.potentialid = quotes.potentialid
				left join contactdetails as contactdetailsQuotes on contactdetailsQuotes.contactid = quotes.contactid
				left join account as accountQuotes on accountQuotes.accountid = quotes.accountid
				".$this->getRelatedModulesQuery($module,$this->secondarymodule)."
				where crmentityQuotes.deleted=0";
		}
		
		if($module == "Orders")
		{
			$query = "from purchaseorder 
				inner join crmentity as crmentityOrders on crmentityOrders.crmid=purchaseorder.purchaseorderid 
				inner join pobillads on purchaseorder.purchaseorderid=pobillads.pobilladdressid 
				inner join poshipads on purchaseorder.purchaseorderid=poshipads.poshipaddressid 
				left join users as usersOrders on usersOrders.id = crmentityOrders.smownerid 
				left join vendor as vendorRel on vendorRel.vendorid = purchaseorder.vendorid 
				left join contactdetails as contactdetailsOrders on contactdetailsOrders.contactid = purchaseorder.contactid 
				".$this->getRelatedModulesQuery($module,$this->secondarymodule)."
				where crmentityOrders.deleted=0";
		}

		if($module == "Invoice")
		{
			$query = "from invoice 
				inner join crmentity as crmentityInvoice on crmentityInvoice.crmid=invoice.invoiceid 
				inner join invoicebillads on invoice.invoiceid=invoicebillads.invoicebilladdressid 
				inner join invoiceshipads on invoice.invoiceid=invoiceshipads.invoiceshipaddressid 
				left join users as usersInvoice on usersInvoice.id = crmentityInvoice.smownerid
				left join account as accountInvoice on accountInvoice.accountid = invoice.accountid
				".$this->getRelatedModulesQuery($module,$this->secondarymodule)."
				where crmentityInvoice.deleted=0";
		}

		$vtlog->logthis("ReportRun :: Successfully returned getReportsQuery".$module,"info");
		return $query;
	}

	function sGetSQLforReport($reportid,$filterlist)
	{
		global $vtlog;

		$columnlist = $this->getQueryColumnsList($reportid);
		$groupslist = $this->getGroupingList($reportid);
		$stdfilterlist = $this->getStdFilterList($reportid);
		$columnstotallist = $this->getColumnsTotal($reportid);
		$advfilterlist = $this->getAdvFilterList($reportid);

		if($this->reporttype == "summary")
		{
			if(isset($this->groupbylist))
			{
				$newcolumnlist = array_diff($columnlist, $this->groupbylist);
				$selectlist = array_merge($this->groupbylist,$newcolumnlist);
			}else
			{
				$selectlist = $columnlist;
			}
		}else
		{
			$selectlist = $columnlist;
		}

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
		//print_r($filterlist);
		if(isset($filterlist))
		{
			$stdfiltersql = implode(", ",$filterlist);
		}
		//columns to total list
		if(isset($columnstotallist))
		{
			//      print_r($columnstotal);
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

		if(trim($groupsquery) != "")
		{
			$reportquery = "select ".$selectedcolumns." ".$reportquery." ".$wheresql. " order by ".$groupsquery;
		}else
		{
			$reportquery = "select ".$selectedcolumns." ".$reportquery." ".$wheresql;
		}
		$vtlog->logthis("ReportRun :: Successfully returned sGetSQLforReport".$reportid,"info");
		return $reportquery;

	}

	function GenerateReport($outputformat,$filterlist)
	{
                 global $adb;
         	 global $modules;
		 global $mod_strings;

		if($outputformat == "HTML")
		{
			$sSQL = $this->sGetSQLforReport($this->reportid,$filterlist);
			$result = $adb->query($sSQL);
			$y=$adb->num_fields($result);

			if($result)
			{
				for ($x=0; $x<$y; $x++)
				{
					$fld = $adb->field_name($result, $x);
					$header .= "<td class='rptHead'>".str_replace($modules," ",$fld->name)."</td>";
				}
				
				$noofrows = $adb->num_rows($result);
				$custom_field_values = $adb->fetch_array($result);
				$groupslist = $this->getGroupingList($this->reportid);

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
						$fieldvalue = $custom_field_values[$i];

						if($fieldvalue == "" )
						{
							$fieldvalue = "-";
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
						}else if(($secondvalue == $fieldvalue) && $this->reporttype == "summary")
						{
							if($lastvalue == $newvalue)
							{
								$valtemplate .= "<td class='rptEmptyGrp'>&nbsp;</td>";	
							}else
							{
								$valtemplate .= "<td class='rptGrpHead'>".$fieldvalue."</td>";
							}
						}
						else if(($thirdvalue == $fieldvalue) && $this->reporttype == "summary")
						{
							if($secondvalue == $snewvalue)
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
				}while($custom_field_values = $adb->fetch_array($result));
				
				
				$totalhtml = '
				<tr>
				<td colspan='.($y+1).' class="rptTotal">'.$mod_strings['LBL_GRAND_TOTAL'].': '.$noofrows.' Records</td>
				</tr>';
				
				$sHTML = '<html>
				<head></head>
				<body>
				 <table cellpadding="0" cellspacing="0" border="0" class="rptTable">
				 <tr>
				 	<td class="rptTitle" colspan="'.$y.'">'.$mod_strings['LBL_GENERATED_REPORT'].'</td>
				 </tr>
				  <tr>'. 
				   $header
				  .'<!-- BEGIN values -->
				  <tr>'. 
				   $valtemplate
				  .'</tr>'
				  .$totalhtml.
				'</table>
				</body>
				</html>';
				//<<<<<<<<construct HTML>>>>>>>>>>>>
				return $sHTML;
			}
		}elseif($outputformat == "PDF")
		{
			
			$sSQL = $this->sGetSQLforReport($this->reportid,$filterlist);
			//$modules = array("Leads_", "Accounts_", "Potentials_", "Contacts_","_");
			$result = $adb->query($sSQL);
			$y=$adb->num_fields($result);

			if($result)
			{
				$noofrows = $adb->num_rows($result);
				$custom_field_values = $adb->fetch_array($result);
				
				//$modules = array("Leads_", "Accounts_", "Potentials_", "Contacts_","_");
				
				do
				{
					$arraylists = Array();
					for ($i=0; $i<$y; $i++)
					{
						$fld = $adb->field_name($result, $i);
						$fieldvalue = $custom_field_values[$i];
						if($fieldvalue == "" )
						{
						   $fieldvalue = "-";
						}
						$arraylists[str_replace($modules," ",$fld->name)] = $fieldvalue;
					}
					$arr_val[] = $arraylists;
				}while($custom_field_values = $adb->fetch_array($result));

				return $arr_val;
			}
		}elseif($outputformat == "TOTALHTML")
		{
			
			global $adb;
			
			$sSQL = $this->sGetSQLforReport("TOTAL",$filterlist);
			if($sSQL != "")
			{
				//$modules = array("Leads_", "Accounts_", "Potentials_", "Contacts_","_");
				$result = $adb->query($sSQL);
				$y=$adb->num_fields($result);
				$custom_field_values = $adb->fetch_array($result);
				$coltotalhtml .= "<table border=1><tr><td>Totals</td><td>SUM</td></tr>";
				for($i =0 ;$i<$y;$i++)
				{
					$fld = $adb->field_name($result, $i);
					$coltotalhtml .= '<tr valign=top><td>'.$fld->name.'</td>
						<td>'.$custom_field_values[$i].'</td>
					</tr>';
				}
				$coltotalhtml .= "</table>";
			}
			
			return $coltotalhtml;
		}
	}
	
	//<<<<<<<new>>>>>>>>>>
	function getColumnsTotal($reportid)
	{
		global $adb;
		global $modules;
		global $vtlog;

		$coltotalsql = "select reportsummary.* from report";
                $coltotalsql .= " inner join reportsummary on report.reportid = reportsummary.reportsummaryid";
                $coltotalsql .= " where report.reportid =".$reportid;

                $result = $adb->query($coltotalsql);
		
		while($coltotalrow = $adb->fetch_array($result))
		{
			$fieldcolname = $coltotalrow["columnname"];
			
			if($fieldcolname != "none")
                        {
                                $fieldlist = explode(":",$fieldcolname);
                                if($fieldlist[4] == 2)
                                {
                                  $stdfilterlist[$fieldcolname] = "sum(".$fieldlist[1].".".$fieldlist[2].") ".$fieldlist[3];
                                }
                                if($fieldlist[4] == 3)
                                {
                                  $stdfilterlist[$fieldcolname] = "avg(".$fieldlist[1].".".$fieldlist[2].") ".$fieldlist[3];
                                }
                                if($fieldlist[4] == 4)
                                {
                                  $stdfilterlist[$fieldcolname] = "min(".$fieldlist[1].".".$fieldlist[2].") ".$fieldlist[3];
                                }
                                if($fieldlist[4] == 5)
                                {
                                  $stdfilterlist[$fieldcolname] = "max(".$fieldlist[1].".".$fieldlist[2].") ".$fieldlist[3];
                                }
                        }
		}

		$vtlog->logthis("ReportRun :: Successfully returned getColumnsTotal".$reportid,"info");
		return $stdfilterlist;
	}
	//<<<<<<new>>>>>>>>>

	function getColumnsToTotalColumns($reportid)
	{
		global $adb;
		global $modules;
		global $vtlog;

		$sreportstdfiltersql = "select reportsummary.* from report"; 
		$sreportstdfiltersql .= " inner join reportsummary on report.reportid = reportsummary.reportsummaryid"; 
		$sreportstdfiltersql .= " where report.reportid =".$reportid;
		
		$result = $adb->query($sreportstdfiltersql);
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
		$vtlog->logthis("ReportRun :: Successfully returned getColumnsToTotalColumns".$reportid,"info");
		return $sSQL;
	}

}
?>
