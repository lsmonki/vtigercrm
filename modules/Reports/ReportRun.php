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
				$columnslist[$fieldcolname] = $selectedfields[0].".".$selectedfields[1]." ".$selectedfields[2];	
			}else
			{
				$columnslist[$fieldcolname] = $querycolumns;
			}
		}
		
		return $columnslist;		
	}

	function getEscapedColumns($selectedfields)
	{
		$fieldname = $selectedfields[3];
		if($fieldname == "assigned_user_id")
		{
			$querycolumn = "usersRel.user_name"." ".$selectedfields[2];
		}
		if($fieldname == "account_id")
		{
			$querycolumn = "accountRel.accountname"." ".$selectedfields[2];
		}
		if($fieldname == "parent_id")
		{
			$querycolumn = "case crmentityRel.setype when 'Accounts' then accountRel.accountname when 'Leads' then leaddetailsRel.lastname when 'Potentials' then potentialRel.potentialname End"." ".$selectedfields[2].", crmentityRel.setype Entity_type";
		}
		if($fieldname == "contact_id")
		{
			$querycolumn = "contactdetailsRel.lastname"." ".$selectedfields[2];
		}
		if($fieldname == "vendor_id")
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
                }
		return $querycolumn;
	}
	
	function getSelectedColumnsList($reportid)
	{
	
		global $adb;
		global $modules;

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
		return $sSQL;
	}
	
	function getAdvFilterList($reportid)
	{
		global $adb;
		global $modules;
	
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
			
			if($comparator == "e")
			{
                           $fieldvalue = $selectedfields[0].".".$selectedfields[1]." = '".$value."'";
			}
			
			$advfilterlist[$fieldcolname] = $fieldvalue;		
		     }
	    						
		}
		
		return $advfilterlist;
	}	

	function getStdFilterList($reportid)
	{
		global $adb;
		global $modules;

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
                                                $stdfilterlist[$fieldcolname] = $selectedfields[0].".".$selectedfields[1]." between '".$startenddate[0]."' and '".$startenddate[1]."'";
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
			
			return $datevalue;
	}

	function getGroupingList($reportid)
	{
		global $adb;
		global $modules;

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
		
		return $grouplist;
	}

	function getSelectedOrderbyList($reportid)
	{
	
		global $adb;
		global $modules;
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
		return $sSQL;
	}

	function getSQLforPrimaryModule($module)
	{
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
	   return $sql;
	}

	function getReportsQuery($module)
	{

		if($module == "Leads")
		{
			$query = "from leaddetails 
				inner join crmentity as crmentityLeads on crmentityLeads.crmid=leaddetails.leadid 
				inner join leadsubdetails on leadsubdetails.leadsubscriptionid=leaddetails.leadid 
				inner join leadaddress on leadaddress.leadaddressid=leadsubdetails.leadsubscriptionid 
				inner join leadscf on leaddetails.leadid = leadscf.leadid 
				left join users as usersRel on usersRel.id = crmentityLeads.smownerid
				where crmentityLeads.deleted=0 and leaddetails.converted=0";
		}

		if($module == "Accounts")
                {
			$query = "from account 
				inner join crmentity as crmentityAccounts on crmentityAccounts.crmid=account.accountid 
				inner join accountbillads on account.accountid=accountbillads.accountaddressid 
				inner join accountshipads on account.accountid=accountshipads.accountaddressid 
				inner join accountscf on account.accountid = accountscf.accountid 
				left join account as accountRel on accountRel.accountid = account.parentid
				where crmentityAccounts.deleted=0 ";
		}

		if($module == "Contacts")
                {
			$query = "from contactdetails
				inner join crmentity as crmentityContacts on crmentityContacts.crmid = contactdetails.contactid 
				inner join contactaddress on contactdetails.contactid = contactaddress.contactaddressid 
				inner join contactsubdetails on contactdetails.contactid = contactsubdetails.contactsubscriptionid 
				inner join contactscf on contactdetails.contactid = contactscf.contactid 
				left join account as accountRel on accountRel.accountid = contactdetails.accountid 
				left join users as usersRel on usersRel.id = crmentityContacts.smownerid 
				where crmentityContacts.deleted=0";
		}

		if($module == "Potentials")
		{
			$query = "from potential 
				inner join crmentity as crmentityPotentials on crmentityPotentials.crmid=potential.potentialid 
				inner join account as accountRel on potential.accountid = accountRel.accountid 
				inner join potentialscf on potentialscf.potentialid = potential.potentialid
				left join users as usersRel on usersRel.id = crmentityPotentials.smownerid  
				where crmentityPotentials.deleted=0 ";
		}
		
		if($module == "Products")
		{
			$query = "from products 
				inner join crmentity as crmentityProducts on crmentityProducts.crmid=products.productid 
				left join productcf on products.productid = productcf.productid 
				left join users as usersRel on usersRel.id = crmentityProducts.smownerid 
				left join contactdetails as contactdetailsRel on contactdetailsRel.contactid = products.contactid
				left join vendor as vendorRel on vendorRel.vendorid = products.vendor_id  
				left join seproductsrel on seproductsrel.productid = products.productid 
				left join crmentity as crmentityRel on crmentityRel.crmid = seproductsrel.crmid 
				left join account as accountRel on accountRel.accountid=crmentityRel.crmid 
				left join leaddetails as leaddetailsRel on leaddetailsRel.leadid = crmentityRel.crmid 
				left join potential as potentialRel on potentialRel.potentialid = crmentityRel.crmid 
				where crmentityProducts.deleted=0 ";
		}

		if($module == "HelpDesk")
		{
			$query = "from troubletickets 
				inner join crmentity as crmentityHelpDesk 
				on crmentityHelpDesk.crmid=troubletickets.ticketid 
				inner join ticketcf on ticketcf.ticketid = troubletickets.ticketid
				left join contactdetails as contactdetailsRel on troubletickets.contact_id=contactdetailsRel.contactid 
				left join users as usersRel on crmentityHelpDesk.smownerid=usersRel.id 
				where crmentityHelpDesk.deleted=0 ";
		}

		if($module == "Activities")
		{
			$query = "from activity 
				inner join crmentity as crmentityActivities on crmentityActivities.crmid=activity.activityid 
				left join cntactivityrel on cntactivityrel.activityid= activity.activityid 
				left join contactdetails as contactdetailsRel on contactdetailsRel.contactid= cntactivityrel.contactid
				left join users as usersRel on usersRel.id = crmentityActivities.smownerid
				left join seactivityrel on seactivityrel.activityid = activity.activityid
				left join crmentity as crmentityRel on crmentityRel.crmid = seactivityrel.crmid
				left join account as accountRel on accountRel.accountid=crmentityRel.crmid
				left join leaddetails as leaddetailsRel on leaddetailsRel.leadid = crmentityRel.crmid
				left join potential as potentialRel on potentialRel.potentialid = crmentityRel.crmid
				WHERE crmentityActivities.deleted=0 and (activity.activitytype = 'Meeting' or activity.activitytype='Call' or activity.activitytype='Task')";
		}
		
		if($module == "Quotes")
		{
			$query = "from quotes 
				inner join crmentity as crmentityQuotes on crmentityQuotes.crmid=quotes.quoteid 
				inner join quotesbillads on quotes.quoteid=quotesbillads.quotebilladdressid 
				inner join quotesshipads on quotes.quoteid=quotesshipads.quoteshipaddressid 
				left join users as usersRel on usersRel.id = crmentityQuotes.smownerid
				left join users as usersRel1 on usersRel1.id = quotes.inventorymanager
				left join potential as potentialRel on potentialRel.potentialid = quotes.potentialid
				left join contactdetails as contactdetailsRel on contactdetailsRel.contactid = quotes.contactid
				left join account as accountRel on accountRel.accountid = quotes.accountid
				where crmentityQuotes.deleted=0";
		}
		
		if($module == "Orders")
		{
			$query = "from purchaseorder 
				inner join crmentity as crmentityOrders on crmentityOrders.crmid=purchaseorder.purchaseorderid 
				inner join pobillads on purchaseorder.purchaseorderid=pobillads.pobilladdressid 
				inner join poshipads on purchaseorder.purchaseorderid=poshipads.poshipaddressid 
				left join users as usersRel on usersRel.id = crmentityOrders.smownerid 
				left join vendor as vendorRel on vendorRel.vendorid = purchaseorder.vendorid 
				left join contactdetails as contactdetailsRel on contactdetailsRel.contactid = purchaseorder.contactid 
				left join account as accountRel on accountRel.accountid = purchaseorder.accountid 
				where crmentityOrders.deleted=0";
		}

		if($module == "Invoice")
		{
			$query = "from invoice 
				inner join crmentity as crmentityInvoice on crmentityInvoice.crmid=invoice.invoiceid 
				inner join invoicebillads on invoice.invoiceid=invoicebillads.invoicebilladdressid 
				inner join invoiceshipads on invoice.invoiceid=invoiceshipads.invoiceshipaddressid 
				left join users as usersRel on usersRel.id = crmentityInvoice.smownerid
				left join account as accountRel on accountRel.accountid = invoice.accountid
				where crmentityInvoice.deleted=0";
		}

		return $query;
	}

	function getSQLforPrimaryModule1($module)
	{
 	   if($module != "")
	   {
		switch($module)
		{
			case "Leads":
				$sSQL = " from  crmentity as crmentityLeads";
				$sSQL .= " inner join leaddetails on crmentityLeads.crmid=leaddetails.leadid";
                        	$sSQL .= " left join leadsubdetails on leadsubscriptionid = leaddetails.leadid";
                        	$sSQL .= " left join leadaddress on leadaddress.leadaddressid = leaddetails.leadid";
                        	$sSQL .= " left join leadscf on leadscf.leadid = leaddetails.leadid";
				break;
			case "Contacts":
				$sSQL = " from crmentity as crmentityContacts";
				$sSQL .= " inner join contactdetails on contactdetails.contactid = crmentityContacts.crmid";
                        	$sSQL .= " left join contactsubdetails on contactsubdetails.contactsubscriptionid = contactdetails.contactid";
                        	$sSQL .= " left join contactaddress on contactaddress.contactaddressid = contactdetails.contactid";
                        	$sSQL .= " left join contactscf on contactscf.contactid = contactdetails.contactid";
				break;
			case "Accounts":
				$sSQL = " from crmentity as crmentityAccounts";
				$sSQL .= " inner join account on account.accountid = crmentity.crmid";
                        	$sSQL .= " left join accountbillads on accountbillads.accountaddressid = account.accountid";
                        	$sSQL .= " left join accountshipads on accountshipads.accountaddressid = account.accountid";
				break;
			case "Potentials":
				$sSQL = " from crmentity as crmentityPotentials";
				$sSQL .= " inner join potential on potential.potentialid = crmentity.crmid";
                        	$sSQL .= " left join potentialscf on potentialscf.potentialid = potential.potentialid";
				break;		
		}
		
		return $sSQL;			
	   }
	}

	function getSQLforSecondaryModule($primarymodule,$secondarymodule)
	{
//		if($primarymodule != "" && $secondarymodule != "")
//		{
//			switch($primarymodule)	
//		}	
	}
	
	function sGetSQLforReport($reportid)
	{
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
                	$wheresql = " and ".$advfiltersql;
        	}

		$reportquery = $this->getReportsQuery($this->primarymodule);
		$reportquery = "select ".$selectedcolumns." ".$reportquery." ".$wheresql;

		return $reportquery;

	}

	function sGetSQL($sqltype)
	{
	
		/*if($this->primarymodule == "Leads")
		{

			if($oReport->reporttype != "tabular")
			{
				$orderbysql = $this->getSelectedOrderbyList($this->reportid);
			}

			$sSQL = "select ";
			if($sqltype == "REPORT")
			{
				$sSQL .= $this->getSelectedColumnsList($this->reportid);
			}elseif($sqltype == "TOTAL")
			{
				$sSQL .= $this->getColumnsToTotalColumns($this->reportid);
				if($this->getColumnsToTotalColumns($this->reportid) == "")
				{
					$sSQL = "";
					return $sSQL;
				}
			}
			$sSQL .= " from  crmentity as crmentityLeads inner join leaddetails on crmentityLeads.crmid=leaddetails.leadid";
			$sSQL .= " left join leadsubdetails on leadsubscriptionid = leaddetails.leadid";
			$sSQL .= " left join leadaddress on leadaddress.leadaddressid = leaddetails.leadid";
			$sSQL .= " left join leadscf on leadscf.leadid = leaddetails.leadid";

			$stdfiltersql = $this->getStandardCriterialSql($this->reportid);
			if($stdfiltersql != "")
			{
				$sSQL .= " where ".$stdfiltersql ;
			}
			if($orderbysql != "")
			{
				$sSQL .= " order by ".$orderbysql ;
			}
		}*/
		
		if($oReport->reporttype != "tabular")
		{
			$orderbysql = $this->getSelectedOrderbyList($this->reportid);
		}

		$sSQL = "select ";
		if($sqltype == "REPORT")
		{
			$sSQL .= $this->getSelectedColumnsList($this->reportid);
		}elseif($sqltype == "TOTAL")
		{
			$sSQL .= $this->getColumnsToTotalColumns($this->reportid);
			if($this->getColumnsToTotalColumns($this->reportid) == "")
			{
				$sSQL = "";
				return $sSQL;
			}
		}
		/*$sSQL .= " from  crmentity as crmentityLeads inner join leaddetails on crmentityLeads.crmid=leaddetails.leadid";
		$sSQL .= " left join leadsubdetails on leadsubscriptionid = leaddetails.leadid";
		$sSQL .= " left join leadaddress on leadaddress.leadaddressid = leaddetails.leadid";
		$sSQL .= " left join leadscf on leadscf.leadid = leaddetails.leadid";*/
		$sSQL .= " ".$this->getReportsQuery($this->primarymodule);

		$stdfiltersql = $this->getStandardCriterialSql($this->reportid);
		if($stdfiltersql != "")
		{
			$sSQL .= " and ".$stdfiltersql ;
		}

		$advfilterlist = $this->getAdvFilterList($this->reportid);
		if(isset($advfilterlist))
		{
			$advfiltersql = implode(" and ",$advfilterlist);
		}

		if($advfiltersql != "")
		{
			$sSQL .= " and ".$advfiltersql ;
		}

		if($orderbysql != "")
		{
			$sSQL .= " order by ".$orderbysql ;
		}

		/*if($this->primarymodule == "Contacts")
		{

			$orderbysql = $this->getSelectedOrderbyList($this->reportid);

			$sSQL = "select ";
			if($sqltype == "REPORT")
			{
				$sSQL .= $this->getSelectedColumnsList($this->reportid);
			}elseif($sqltype == "TOTAL")
			{
				$sSQL .= $this->getColumnsToTotalColumns($this->reportid);
			}
			$sSQL .= " from crmentity as crmentityContacts inner join contactdetails on contactdetails.contactid = crmentityContacts.crmid";
			$sSQL .= " left join contactsubdetails on contactsubdetails.contactsubscriptionid = contactdetails.contactid";
			$sSQL .= " left join contactaddress on contactaddress.contactaddressid = contactdetails.contactid";
			$sSQL .= " left join contactscf on contactscf.contactid = contactdetails.contactid";
			if($this->secondarymodule != "")
			{
				$secondarymodule = explode(":",$this->secondarymodule);
				for($i=0;$i < count($secondarymodule) ;$i++)
				{
					if($secondarymodule[$i] == "Accounts")
					{
						$sSQL .= " left join crmentity as crmentityAccounts on crmentityAccounts.crmid=contactdetails.accountid";
						$sSQL .= " left join account on account.accountid = crmentityAccounts.crmid" ;
						$sSQL .= " left join accountbillads on accountbillads.accountaddressid = account.accountid" ;
						$sSQL .= " left join accountshipads on accountshipads.accountaddressid = account.accountid";
					}elseif($secondarymodule[$i] == "Potentials")
					{
						if(count($secondarymodule) == 1)
						{
							$sSQL .= " left join crmentity as crmentityPotentials on crmentityPotentials.crmid=contactdetails.accountid";
							$sSQL .= " left join account on account.accountid = crmentityPotentials.crmid" ;
							$sSQL .= " left join accountbillads on accountbillads.accountaddressid = account.accountid" ;
							$sSQL .= " left join accountshipads on accountshipads.accountaddressid = account.accountid";
						}else
						{
						$sSQL .= " left join potential on potential.accountid = account.accountid";
						$sSQL .= " left join crmentity as crmentityPotentials on crmentityPotentials.crmid = potential.potentialid";
						$sSQL .= " left join potentialscf on potentialscf.potentialid = potential.potentialid";
						}
					}
				}
			}

			if($orderbysql != "")
			{
				$sSQL .= " order by ".$orderbysql ;
			}
		}

		if($this->primarymodule== "Potentials")
		{
			$orderbysql = $this->getSelectedOrderbyList($this->reportid);

			$sSQL = "select ";
			if($sqltype == "REPORT")
			{
				$sSQL .= $this->getSelectedColumnsList($this->reportid);
			}elseif($sqltype == "TOTAL")
			{
				$sSQL .= $this->getColumnsToTotalColumns($this->reportid);
			}
			$sSQL .= " from crmentity inner join potential on potential.potentialid = crmentity.crmid";
			$sSQL .= " left join potentialscf on potentialscf.potentialid = potential.potentialid";

			if($orderbysql != "")
			{
				$sSQL .= " order by ".$orderbysql ;
			}
		}

		if($this->primarymodule == "Accounts")
		{
			$orderbysql = $this->getSelectedOrderbyList($this->reportid);

			$sSQL = "select ";
			if($sqltype == "REPORT")
			{
				$sSQL .= $this->getSelectedColumnsList($this->reportid);
			}elseif($sqltype == "TOTAL")
			{
				$sSQL .= $this->getColumnsToTotalColumns($this->reportid);
			}
			$sSQL .= " from crmentity inner join account on account.accountid = crmentity.crmid";
			$sSQL .= " left join accountbillads on accountbillads.accountaddressid = account.accountid";
			$sSQL .= " left join accountshipads on accountshipads.accountaddressid = account.accountid";

			if($orderbysql != "")
			{
				$sSQL .= " order by ".$orderbysql ;
			}

			//"left join potential on potential.accountid = account.accountid left join crmentity as crmentity1 on crmentity1.crmid = potential.accountid left join potentialscf on potentialscf.potentialid = potential.potentialid left join contactdetails on contactdetails.accountid = account.accountid left join crmentity as crmentity2 on crmentity2.crmid = contactdetails.contactid left join contactsubdetails on contactsubdetails.contactsubscriptionid = contactdetails.contactid left join contactaddress on contactaddress.contactaddressid = contactdetails.contactid left join contactscf on contactscf.contactid = contactdetails.contactid";
		}*/
		//echo $sSQL;
		return $sSQL;
	}

	function GenerateReport($outputformat)
	{
                 global $adb;
         	 global $modules;

		if($outputformat == "HTML")
		{
			$sSQL = $this->sGetSQLforReport($this->reportid);
			//echo $sSQL;
			//$modules = array("Leads_", "Accounts_", "Potentials_", "Contacts_","_");
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
				
				//$modules = array("Leads_", "Accounts_", "Potentials_", "Contacts_","_");
				
				do
				{
					$arraylists = Array();
					
					$newvalue = $custom_field_values[0];
					if($newvalue == "")
					 {
						 $newvalue = "-";
					 }
					if($lastvalue != "")
					 {
						 if($lastvalue == $newvalue)
						 {
							//$valtemplate .= '<tr valign=top>
							//  <td height=1></td>
							//  <td colspan='.($y-1).' bgcolor=#CCCCCC></td>
							//</tr>';
						 }else
						 {
							// $valtemplate .= '<tr valign=top>
							//  <td colspan='.$y.' bgcolor=#CCCCCC></td>
							//</tr>';
						 }
					 }
					 
					$valtemplate .= "<tr>";
					
					for ($i=0; $i<$y; $i++)
					  {
						  $fld = $adb->field_name($result, $i);
						   $fieldvalue = $custom_field_values[$i];
						   
						   if($fieldvalue == "" )
						   {
							   $fieldvalue = "-";
						   }
						   if($i == 0)
						  {
								if($lastvalue == $fieldvalue)
								{
									$valtemplate .= "<td class='rptEmptyGrp'>&nbsp;</td>";									
								}else
								{
									$valtemplate .= "<td class='rptGrpHead'>".$fieldvalue."</td>";
								}
						  }else
						  {
							  $arraylists[str_replace($modules," ",$fld->name)] = $fieldvalue;
							  $valtemplate .= "<td class='rptData'>".$fieldvalue."</td>";
						  }
					  }
					 $valtemplate .= "</tr>";
					 $lastvalue = $newvalue;
				$arr_val[] = $arraylists;
				}while($custom_field_values = $adb->fetch_array($result));
				
				//<<<<<<<<construct HTML>>>>>>>>>>>>
				
				/*
				$totalhtml = '<tr valign=top>
				<td colspan='.($y+1).' bgcolor=#CCCCCC></td>
				</tr>
				<tr valign=top>
				<td  colspan='.($y+1).' valign="middle" class="rptTotal">Grand Total: '.$noofrows.' Records</td>
				</tr>';
				*/
				
				$totalhtml = '
				<tr>
				<td colspan='.($y+1).' class="rptTotal">Grand Total: '.$noofrows.' Records</td>
				</tr>';
				
				$sHTML = '<html>
				<head></head>
				<body>
				 <table cellpadding="0" cellspacing="0" border="0" class="rptTable">
				 <tr>
				 	<td class="rptTitle" colspan="'.$y.'">Generated Report</td>
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
			
			$sSQL = $this->sGetSQL("REPORT");
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
			
			$sSQL = $this->sGetSQL("TOTAL");
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
		return $stdfilterlist;
	}
	//<<<<<<new>>>>>>>>>

	function getColumnsToTotalColumns($reportid)
	{
		global $adb;
		global $modules;
	
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
		return $sSQL;
	}

}
?>
