<?
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
//require_once('modules/Reports/CannedReports.php');
global $adb;

$rptfolder = Array(Array('Account and Contact Reports'=>'Account and Contact Reports'),
		   Array('Lead Reports'=>'Lead Reports'),
	           Array('Potential Reports'=>'Potential Reports'),
		   Array('Activity Reports'=>'Activity Reports'),
		   Array('HelpDesk Reports'=>'HelpDesk Reports'),
		   Array('Product Reports'=>'Product Reports'),
		   Array('Quote Reports' =>'Quote Reports'),
		   Array('PurchaseOrder Reports'=>'PurchaseOrder Reports'),
		   Array('Invoice Reports'=>'Invoice Reports')
                  );

$reportmodules = Array(Array('primarymodule'=>'Contacts','secondarymodule'=>'Accounts'),
		       Array('primarymodule'=>'Contacts','secondarymodule'=>'Accounts'),
		       Array('primarymodule'=>'Contacts','secondarymodule'=>'Potentials'),
		       Array('primarymodule'=>'Leads','secondarymodule'=>''),
		       Array('primarymodule'=>'Leads','secondarymodule'=>''),
		       Array('primarymodule'=>'Potentials','secondarymodule'=>''),
		       Array('primarymodule'=>'Potentials','secondarymodule'=>''),
		       Array('primarymodule'=>'Activities','secondarymodule'=>''),
		       Array('primarymodule'=>'Activities','secondarymodule'=>''),
		       Array('primarymodule'=>'HelpDesk','secondarymodule'=>'Products'),
		       Array('primarymodule'=>'HelpDesk','secondarymodule'=>''),
  		       Array('primarymodule'=>'HelpDesk','secondarymodule'=>''),
		       Array('primarymodule'=>'Products','secondarymodule'=>''),
		       Array('primarymodule'=>'Products','secondarymodule'=>'Contacts'),
		       Array('primarymodule'=>'Quotes','secondarymodule'=>''),
		       Array('primarymodule'=>'Quotes','secondarymodule'=>''),
		       Array('primarymodule'=>'PurchaseOrder','secondarymodule'=>'Contacts'),
		       Array('primarymodule'=>'PurchaseOrder','secondarymodule'=>''),
		       Array('primarymodule'=>'Invoice','secondarymodule'=>'')
		      );

$selectcolumns = Array(Array('contactdetails:firstname:Contacts_First_Name:firstname:V',
                             'contactdetails:lastname:Contacts_Last_Name:lastname:V',
                             'contactsubdetails:leadsource:Contacts_Lead_Source:leadsource:V',
                             'accountContacts:accountname:Contacts_Account_Name:account_id:I',
			     'account:industry:Accounts_industry:industry:V',
			     'contactdetails:email:Contacts_Email:email:V'),

		       Array('contactdetails:firstname:Contacts_First_Name:firstname:V',
                             'contactdetails:lastname:Contacts_Last_Name:lastname:V',
                             'contactsubdetails:leadsource:Contacts_Lead_Source:leadsource:V',
                             'accountContacts:accountname:Contacts_Account_Name:account_id:I',
                             'account:industry:Accounts_industry:industry:V',
                             'contactdetails:email:Contacts_Email:email:V'),

		       Array('contactdetails:firstname:Contacts_First_Name:firstname:V',
                             'contactdetails:lastname:Contacts_Last_Name:lastname:V',
                             'accountContacts:accountname:Contacts_Account_Name:account_id:I',
                             'contactdetails:email:Contacts_Email:email:V',
                             'potential:potentialname:Potentials_Potential_Name:potentialname:V',
                             'potential:sales_stage:Potentials_Sales_Stage:sales_stage:V'),

		       Array('leaddetails:firstname:Leads_First_Name:firstname:V',
			     'leaddetails:lastname:Leads_Last_Name:lastname:V',
			     'leaddetails:company:Leads_Company:company:V',
			     'leaddetails:email:Leads_Email:email:V'),

		       Array('leaddetails:firstname:Leads_First_Name:firstname:V',
                             'leaddetails:lastname:Leads_Last_Name:lastname:V',
                             'leaddetails:company:Leads_Company:company:V',
                             'leaddetails:email:Leads_Email:email:V',
			     'leaddetails:leadsource:Leads_Lead_Source:leadsource:V'),

		       Array('potential:potentialname:Potentials_Potential_Name:potentialname:V',
                             'potential:amount:Potentials_Amount:amount:N',
                             'potential:potentialtype:Potentials_Type:opportunity_type:V',
                             'potential:leadsource:Potentials_Lead_Source:leadsource:V',
                             'potential:sales_stage:Potentials_Sales_Stage:sales_stage:V'),
  
		       Array('potential:potentialname:Potentials_Potential_Name:potentialname:V',
                             'potential:amount:Potentials_Amount:amount:N',
                             'potential:potentialtype:Potentials_Type:opportunity_type:V',
                             'potential:leadsource:Potentials_Lead_Source:leadsource:V',
			     'potential:sales_stage:Potentials_Sales_Stage:sales_stage:V'),

		       Array('activity:subject:Activities_Subject:subject:V',
			     'contactdetailsActivities:lastname:Activities_Contact_Name:contact_id:I',
                             'activity:status:Activities_Status:taskstatus:V',
                             'activity:priority:Activities_Priority:taskpriority:V',
                             'usersActivities:user_name:Activities_Assigned_To:assigned_user_id:V'),

		       Array('activity:subject:Activities_Subject:subject:V',
                             'contactdetailsActivities:lastname:Activities_Contact_Name:contact_id:I',
                             'activity:status:Activities_Status:taskstatus:V',
                             'activity:priority:Activities_Priority:taskpriority:V',
                             'usersActivities:user_name:Activities_Assigned_To:assigned_user_id:V'),

        	       Array('troubletickets:title:HelpDesk_Title:ticket_title:V',
                             'troubletickets:status:HelpDesk_Status:ticketstatus:V',
                             'products:productname:Products_Product_Name:productname:V',
                             'products:discontinued:Products_Product_Active:discontinued:V',
                             'products:productcategory:Products_Product_Category:productcategory:V',
			     'products:manufacturer:Products_Manufacturer:manufacturer:V',
			     'contactdetailsProducts:lastname:Products_Contact_Name:contact_id:I'),

 		       Array('troubletickets:title:HelpDesk_Title:ticket_title:V',
                             'troubletickets:priority:HelpDesk_Priority:ticketpriorities:V',
                             'troubletickets:severity:HelpDesk_Severity:ticketseverities:V',
                             'troubletickets:status:HelpDesk_Status:ticketstatus:V',
                             'troubletickets:category:HelpDesk_Category:ticketcategories:V',
                             'usersHelpDesk:user_name:HelpDesk_Assigned_To:assigned_user_id:V'),

		       Array('troubletickets:title:HelpDesk_Title:ticket_title:V',
                             'troubletickets:priority:HelpDesk_Priority:ticketpriorities:V',
                             'troubletickets:severity:HelpDesk_Severity:ticketseverities:V',
                             'troubletickets:status:HelpDesk_Status:ticketstatus:V',
                             'troubletickets:category:HelpDesk_Category:ticketcategories:V',
                             'usersHelpDesk:user_name:HelpDesk_Assigned_To:assigned_user_id:V'),
		      
 		       Array('products:productname:Products_Product_Name:productname:V',
                             'products:productcode:Products_Product_Code:productcode:V',
                             'products:discontinued:Products_Product_Active:discontinued:V',
                             'products:productcategory:Products_Product_Category:productcategory:V',
                             'contactdetailsProducts:lastname:Products_Contact_Name:contact_id:I',
                             'products:website:Products_Website:website:V',
			     'vendorRel:vendorname:Products_Vendor_Name:vendor_id:I',
			     'products:mfr_part_no:Products_Mfr_PartNo:mfr_part_no:V'),

		       Array('products:productname:Products_Product_Name:productname:V',
                             'products:manufacturer:Products_Manufacturer:manufacturer:V',
                             'products:productcategory:Products_Product_Category:productcategory:V',
                             'contactdetails:firstname:Contacts_First_Name:firstname:V',
                             'contactdetails:lastname:Contacts_Last_Name:lastname:V',
                             'contactsubdetails:leadsource:Contacts_Lead_Source:leadsource:V'),

		       Array('quotes:subject:Quotes_Subject:subject:V',
                             'potentialRel:potentialname:Quotes_Potential_Name:potential_id:I',
                             'quotes:quotestage:Quotes_Quote_Stage:quotestage:V',
                             'contactdetailsQuotes:lastname:Quotes_Contact_Name:contact_id:V',
                             'usersRel1:user_name:Quotes_Inventory_Manager:assigned_user_id1:I',
                             'accountQuotes:accountname:Quotes_Account_Name:account_id:I'),

		       Array('quotes:subject:Quotes_Subject:subject:V',
                             'potentialRel:potentialname:Quotes_Potential_Name:potential_id:I',
                             'quotes:quotestage:Quotes_Quote_Stage:quotestage:V',
                             'contactdetailsQuotes:lastname:Quotes_Contact_Name:contact_id:V',	
                             'usersRel1:user_name:Quotes_Inventory_Manager:assigned_user_id1:I',
                             'accountQuotes:accountname:Quotes_Account_Name:account_id:I',
			     'quotes:carrier:Quotes_Carrier:carrier:V',
			     'quotes:shipping:Quotes_Shipping:shipping:V'),

		       Array('purchaseorder:subject:PurchaseOrder_Subject:subject:V',
			     'vendorRel:vendorname:PurchaseOrder_Vendor_Name:vendor_id:I',
			     'purchaseorder:tracking_no:PurchaseOrder_Tracking_Number:tracking_no:V',
			     'contactdetails:firstname:Contacts_First_Name:firstname:V',
			     'contactdetails:lastname:Contacts_Last_Name:lastname:V',
			     'contactsubdetails:leadsource:Contacts_Lead_Source:leadsource:V',
			     'contactdetails:email:Contacts_Email:email:V'),

		       Array('purchaseorder:subject:PurchaseOrder_Subject:subject:V',
			     'vendorRel:vendorname:PurchaseOrder_Vendor_Name:vendor_id:I',
			     'purchaseorder:requisition_no:PurchaseOrder_Requisition_No:requisition_no:V',
                             'purchaseorder:tracking_no:PurchaseOrder_Tracking_Number:tracking_no:V',
			     'contactdetailsPurchaseOrder:lastname:PurchaseOrder_Contact_Name:contact_id:I',
			     'purchaseorder:carrier:PurchaseOrder_Carrier:carrier:V',
			     'purchaseorder:salescommission:PurchaseOrder_Sales_Commission:salescommission:N',
			     'purchaseorder:exciseduty:PurchaseOrder_Excise_Duty:exciseduty:N',
                             'usersPurchaseOrder:user_name:PurchaseOrder_Assigned_To:assigned_user_id:V'),

		       Array('invoice:subject:Invoice_Subject:subject:V',
			     'invoice:salesorderid:Invoice_Sales_Order:salesorder_id:I',
			     'invoice:customerno:Invoice_Customer_No:customerno:V',
                             'invoice:notes:Invoice_Notes:notes:V',
			     'invoice:invoiceterms:Invoice_Invoice_Terms:invoiceterms:V',
			     'invoice:exciseduty:Invoice_Excise_Duty:exciseduty:N',
			     'invoice:salescommission:Invoice_Sales_Commission:salescommission:N',
			     'accountInvoice:accountname:Invoice_Account_Name:account_id:I')
			);

$reports = Array(Array('reportname'=>'Contacts by Accounts',
                       'reportfolder'=>'Account and Contact Reports',
                       'description'=>'Contacts related to Accounts',
                       'reporttype'=>'tabular',
		       'sortid'=>'','stdfilterid'=>'','advfilterid'=>'0'),

		 Array('reportname'=>'Contacts without Accounts',
                       'reportfolder'=>'Account and Contact Reports',
                       'description'=>'Contacts not related to Accounts',
                       'reporttype'=>'tabular',
		       'sortid'=>'','stdfilterid'=>'','advfilterid'=>'1'),

		 Array('reportname'=>'Contacts by Potentials',
                       'reportfolder'=>'Account and Contact Reports',
                       'description'=>'Contacts related to Potentials',
                       'reporttype'=>'tabular',
		       'sortid'=>'','stdfilterid'=>'','advfilterid'=>'2'),

		 Array('reportname'=>'Lead by Source',
                       'reportfolder'=>'Lead Reports',
                       'description'=>'Lead by Source',
                       'reporttype'=>'summary',
		       'sortid'=>'0','stdfilterid'=>'','advfilterid'=>''),

		 Array('reportname'=>'Lead Status Report',
                       'reportfolder'=>'Lead Reports',
                       'description'=>'Lead Status Report',
                       'reporttype'=>'summary',
                       'sortid'=>'1','stdfilterid'=>'','advfilterid'=>''),

		 Array('reportname'=>'Potential Pipeline',
                       'reportfolder'=>'Potential Reports',
                       'description'=>'Potential Pipline',
                       'reporttype'=>'summary',
                       'sortid'=>'2','stdfilterid'=>'','advfilterid'=>''),

		 Array('reportname'=>'Closed Potentials',
                       'reportfolder'=>'Potential Reports',
                       'description'=>'Potential that have Won',
                       'reporttype'=>'tabular',
                       'sortid'=>'','stdfilterid'=>'','advfilterid'=>'3'),

		 Array('reportname'=>'Last Month Activities',
                       'reportfolder'=>'Activity Reports',
                       'description'=>'Last Month Activites',
                       'reporttype'=>'tabular',
                       'sortid'=>'','stdfilterid'=>'0','advfilterid'=>''),

		 Array('reportname'=>'This Month Activities',
                       'reportfolder'=>'Activity Reports',
                       'description'=>'This Month Activites',
                       'reporttype'=>'tabular',
                       'sortid'=>'','stdfilterid'=>'1','advfilterid'=>''),

		 Array('reportname'=>'Tickets by Products',
                       'reportfolder'=>'HelpDesk Reports',
                       'description'=>'Tickets related to Products',
                       'reporttype'=>'tabular',
                       'sortid'=>'','stdfilterid'=>'','advfilterid'=>''),

		 Array('reportname'=>'Tickets by Priority',
                       'reportfolder'=>'HelpDesk Reports',
                       'description'=>'Tickets by Priority',
                       'reporttype'=>'summary',
                       'sortid'=>'3','stdfilterid'=>'','advfilterid'=>''),

 		 Array('reportname'=>'Open Tickets',
                       'reportfolder'=>'HelpDesk Reports',
                       'description'=>'Tickets that are Open',
                       'reporttype'=>'tabular',
                       'sortid'=>'','stdfilterid'=>'','advfilterid'=>'4'),

		 Array('reportname'=>'Product Details',
                       'reportfolder'=>'Product Reports',
                       'description'=>'Product Detailed Report',
                       'reporttype'=>'tabular',
                       'sortid'=>'','stdfilterid'=>'','advfilterid'=>''),

		 Array('reportname'=>'Products by Contacts',
                       'reportfolder'=>'Product Reports',
                       'description'=>'Products related to Contacts',
                       'reporttype'=>'tabular',
                       'sortid'=>'','stdfilterid'=>'','advfilterid'=>''),

		 Array('reportname'=>'Open Quotes',
                       'reportfolder'=>'Quote Reports',
                       'description'=>'Quotes that are Open',
                       'reporttype'=>'tabular',
                       'sortid'=>'','stdfilterid'=>'','advfilterid'=>'5'),

		 Array('reportname'=>'Quotes Detailed Report',
                       'reportfolder'=>'Quote Reports',
                       'description'=>'Quotes detailed report',
                       'reporttype'=>'tabular',
                       'sortid'=>'','stdfilterid'=>'','advfilterid'=>''),

		 Array('reportname'=>'PurchaseOrder by Contacts',
                       'reportfolder'=>'PurchaseOrder Reports',
                       'description'=>'PurchaseOrder related to Contacts',
                       'reporttype'=>'tabular',
                       'sortid'=>'','stdfilterid'=>'','advfilterid'=>''),

		 Array('reportname'=>'PurchaseOrder Detailed Report',
                       'reportfolder'=>'PurchaseOrder Reports',
                       'description'=>'PurchaseOrder detailed report',
                       'reporttype'=>'tabular',
                       'sortid'=>'','stdfilterid'=>'','advfilterid'=>''),

		 Array('reportname'=>'Invoice Detailed Report',
                       'reportfolder'=>'Invoice Reports',
                       'description'=>'Invoice detailed report',
                       'reporttype'=>'tabular',
                       'sortid'=>'','stdfilterid'=>'','advfilterid'=>'')

		);

$sortorder = Array(
                   Array(
                         Array('columnname'=>'leaddetails:leadsource:Leads_Lead_Source:leadsource:V',
                               'sortorder'=>'Ascending'
                              )
			),
		   Array(
                         Array('columnname'=>'leaddetails:leadstatus:Leads_Lead_Status:leadstatus:V',
                               'sortorder'=>'Ascending'
                              )
                        ),
		   Array(
                         Array('columnname'=>'potential:sales_stage:Potentials_Sales_Stage:sales_stage:V',
                               'sortorder'=>'Ascending'
                              )
                        ),
		   Array(
                         Array('columnname'=>'troubletickets:priority:HelpDesk_Priority:ticketpriorities:V',
                               'sortorder'=>'Ascending'
                              )
                        )
                  );

$stdfilters = Array(Array('columnname'=>'crmentityActivities:modifiedtime:Activities_Modified_Time',
			  'datefilter'=>'lastmonth',
			  'startdate'=>'2005-05-01',
			  'enddate'=>'2005-05-31'),

		    Array('columnname'=>'crmentityActivities:modifiedtime:Activities_Modified_Time',
                          'datefilter'=>'thismonth',
                          'startdate'=>'2005-06-01',
                          'enddate'=>'2005-06-30')
		   );

$advfilters = Array(
                      Array(
                            Array('columnname'=>'accountContacts:accountname:Contacts_Account_Name:account_id:I',
                                  'comparator'=>'n',
                                  'value'=>''
                                 )
                           ),
		      Array(
                            Array('columnname'=>'accountContacts:accountname:Contacts_Account_Name:account_id:I',
                                  'comparator'=>'e',
                                  'value'=>''
                                 )
                           ),
		      Array(
                            Array('columnname'=>'potential:potentialname:Potentials_Potential_Name:potentialname:V',
                                  'comparator'=>'n',
                                  'value'=>''
                                 )
                           ),
		      Array(
                            Array('columnname'=>'potential:sales_stage:Potentials_Sales_Stage:sales_stage:V',
                                  'comparator'=>'e',
                                  'value'=>'Closed Won'
                                 )
                           ),
		      Array(
                            Array('columnname'=>'troubletickets:status:HelpDesk_Status:ticketstatus:V',
                                  'comparator'=>'n',
                                  'value'=>'Closed'
                                 )
                           ),
		      Array(
                            Array('columnname'=>'quotes:quotestage:Quotes_Quote_Stage:quotestage:V',
                                  'comparator'=>'n',
                                  'value'=>'Accepted'
                                 ),
			    Array('columnname'=>'quotes:quotestage:Quotes_Quote_Stage:quotestage:V',
                                  'comparator'=>'n',
                                  'value'=>'Rejected'
                                 )
                           )
                     );
//quotes:quotestage:Quotes_Quote_Stage:quotestage:V
foreach($rptfolder as $key=>$rptarray)
{
        foreach($rptarray as $foldername=>$folderdescription)
        {
                PopulateReportFolder($foldername,$folderdescription);
                $reportid[$foldername] = $key+1;
        }
}

foreach($reports as $key=>$report)
{
        $queryid = insertSelectQuery();
        insertReports($queryid,$reportid[$report['reportfolder']],$report['reportname'],$report['description'],$report['reporttype']);
        insertSelectColumns($queryid,$selectcolumns[$key]);
        insertReportModules($queryid,$reportmodules[$key]['primarymodule'],$reportmodules[$key]['secondarymodule']);
	
	if(isset($stdfilters[$report['stdfilterid']]))
	{
		$i = $report['stdfilterid'];
		insertStdFilter($queryid,$stdfilters[$i]['columnname'],$stdfilters[$i]['datefilter'],$stdfilters[$i]['startdate'],$stdfilters[$i]['enddate']);
	}

	if(isset($advfilters[$report['advfilterid']]))
	{
		insertAdvFilter($queryid,$advfilters[$report['advfilterid']]);
	}

	if($report['reporttype'] == "summary")
	{
		insertSortColumns($queryid,$sortorder[$report['sortid']]);
	}
}

/** Function to store the foldername and folderdescription to database
 *  This function accepts the given folder name and description
 *  ans store it in db as SAVED
 */

function PopulateReportFolder($fldrname,$fldrdescription)
{
	global $adb;
	$sql = "INSERT INTO reportfolder ";
	$sql .= "(FOLDERID,FOLDERNAME,DESCRIPTION,STATE) ";
	$sql .= "VALUES (null,'".$fldrname."','".$fldrdescription."','SAVED')";
	$result = $adb->query($sql);
}

/** Function to add an entry in selestquery table 
 *
 */

function insertSelectQuery()
{
	global $adb;
	$genQueryId = $adb->getUniqueID("selectquery");
        if($genQueryId != "")
        {
		$iquerysql = "insert into selectquery (QUERYID,STARTINDEX,NUMOFOBJECTS) values (".$genQueryId.",0,0)";
		$iquerysqlresult = $adb->query($iquerysql);
	}

	return $genQueryId;
}

/** Function to store the field names selected for a report to a database
 *  
 *  
 */

function insertSelectColumns($queryid,$columnname)
{
	global $adb;
	if($queryid != "")
	{
		for($i=0;$i < count($columnname);$i++)
		{
			$icolumnsql = "insert into selectcolumn (QUERYID,COLUMNINDEX,COLUMNNAME) values (".$queryid.",".$i.",'".$columnname[$i]."')";
			$icolumnsqlresult = $adb->query($icolumnsql);	
		}
	}
}


/** Function to store the report details to database
 *  This function accepts queryid,folderid,reportname,description,reporttype
 *  as arguments and store the informations in report table
 */

function insertReports($queryid,$folderid,$reportname,$description,$reporttype)
{
	global $adb;
	if($queryid != "")
	{
		$ireportsql = "insert into report (REPORTID,FOLDERID,REPORTNAME,DESCRIPTION,REPORTTYPE,QUERYID,STATE)";
                $ireportsql .= " values (".$queryid.",".$folderid.",'".$reportname."','".$description."','".$reporttype."',".$queryid.",'SAVED')";
		$ireportresult = $adb->query($ireportsql);
	}
}

/** Function to store the report modules to database
 *  This function accepts queryid,primary module and secondary module
 *  as arguments and store the informations in reportmodules table
 */


function insertReportModules($queryid,$primarymodule,$secondarymodule)
{
	global $adb;
	if($queryid != "")
	{
		$ireportmodulesql = "insert into reportmodules (REPORTMODULESID,PRIMARYMODULE,SECONDARYMODULES) values (".$queryid.",'".$primarymodule."','".$secondarymodule."')";
		$ireportmoduleresult = $adb->query($ireportmodulesql);
	}
}


/** Function to store the report sortorder to database
 *  This function accepts queryid,sortlists
 *  as arguments and store the informations sort columns and
 *  and sortorder in reportsortcol table
 */


function insertSortColumns($queryid,$sortlists)
{
	global $adb;
	if($queryid != "")
	{
		foreach($sortlists as $i=>$sort)
                {
			$sort_bysql = "insert into reportsortcol (SORTCOLID,REPORTID,COLUMNNAME,SORTORDER) 
					values (".($i+1).",".$queryid.",'".$sort['columnname']."','".$sort['sortorder']."')";
			$sort_byresult = $adb->query($sort_bysql);
		}
	}

}


/** Function to store the report sort date details to database
 *  This function accepts queryid,filtercolumn,datefilter,startdate,enddate
 *  as arguments and store the informations in reportdatefilter table
 */


function insertStdFilter($queryid,$filtercolumn,$datefilter,$startdate,$enddate)
{
	global $adb;
	if($queryid != "")
	{
		$ireportmodulesql = "insert into reportdatefilter (DATEFILTERID,DATECOLUMNNAME,DATEFILTER,STARTDATE,ENDDATE) values (".$queryid.",'".$filtercolumn."','".$datefilter."','".$startdate."','".$enddate."')";
		$ireportmoduleresult = $adb->query($ireportmodulesql);
	}

}

/** Function to store the report conditions to database
 *  This function accepts queryid,filters
 *  as arguments and store the informations in relcriteria table
 */


function insertAdvFilter($queryid,$filters)
{
	global $adb;
	if($queryid != "")
	{
		foreach($filters as $i=>$filter)
		{
		      $irelcriteriasql = "insert into relcriteria(QUERYID,COLUMNINDEX,COLUMNNAME,COMPARATOR,VALUE) 
		      values (".$queryid.",".$i.",'".$filter['columnname']."','".$filter['comparator']."','".$filter['value']."')";
		      $irelcriteriaresult = $adb->query($irelcriteriasql);
		}
	}
}
?>
