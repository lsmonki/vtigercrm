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

$customviews = Array(Array('viewname'=>'Hot Leads',
			   'setdefault'=>'0','setmetrics'=>'1',
			   'cvmodule'=>'Leads','stdfilterid'=>'','advfilterid'=>'0'),

		     Array('viewname'=>'This Month Leads',
			   'setdefault'=>'0','setmetrics'=>'0',
			   'cvmodule'=>'Leads','stdfilterid'=>'0','advfilterid'=>''),

		     Array('viewname'=>'Prospect Accounts',
                           'setdefault'=>'0','setmetrics'=>'1',
                           'cvmodule'=>'Accounts','stdfilterid'=>'','advfilterid'=>'1'),
		     
		     Array('viewname'=>'New This Week',
                           'setdefault'=>'0','setmetrics'=>'0',
                           'cvmodule'=>'Accounts','stdfilterid'=>'1','advfilterid'=>''),

		     Array('viewname'=>'Contacts Address',
                           'setdefault'=>'0','setmetrics'=>'0',
                           'cvmodule'=>'Contacts','stdfilterid'=>'','advfilterid'=>''),

		     Array('viewname'=>'Todays Birthday',
                           'setdefault'=>'0','setmetrics'=>'0',
                           'cvmodule'=>'Contacts','stdfilterid'=>'2','advfilterid'=>''),

		     Array('viewname'=>'Potentails Won',
                           'setdefault'=>'0','setmetrics'=>'1',
                           'cvmodule'=>'Potentials','stdfilterid'=>'','advfilterid'=>'2'),

		     Array('viewname'=>'Prospecting',
                           'setdefault'=>'0','setmetrics'=>'0',
                           'cvmodule'=>'Potentials','stdfilterid'=>'','advfilterid'=>'3'),
 	 	     
	             Array('viewname'=>'Open Tickets',
                           'setdefault'=>'0','setmetrics'=>'1',
                           'cvmodule'=>'HelpDesk','stdfilterid'=>'','advfilterid'=>'4'),
       	             
		     Array('viewname'=>'High Prioriy Tickets',
                           'setdefault'=>'0','setmetrics'=>'0',
                           'cvmodule'=>'HelpDesk','stdfilterid'=>'','advfilterid'=>'5'),

		     Array('viewname'=>'Open Quotes',
                           'setdefault'=>'0','setmetrics'=>'1',
                           'cvmodule'=>'Quotes','stdfilterid'=>'','advfilterid'=>'6'),

		     Array('viewname'=>'Rejected Quotes',
                           'setdefault'=>'0','setmetrics'=>'0',
                           'cvmodule'=>'Quotes','stdfilterid'=>'','advfilterid'=>'7')
		    );


$cvcolumns = Array(Array('leaddetails:firstname:firstname:Leads_First_Name:V',
                         'leaddetails:lastname:lastname:Leads_Last_Name:V',
                         'leaddetails:company:company:Leads_Company:V',
                         'leaddetails:leadsource:leadsource:Leads_Lead_Source:V',
                         'leadsubdetails:website:website:Leads_Website:V',
                         'leaddetails:email:email:Leads_Email:V'),

		   Array('leaddetails:firstname:firstname:Leads_First_Name:V',
                         'leaddetails:lastname:lastname:Leads_Last_Name:V',
                         'leaddetails:company:company:Leads_Company:V',
                         'leaddetails:leadsource:leadsource:Leads_Lead_Source:V',
                         'leadsubdetails:website:website:Leads_Website:V',
                         'leaddetails:email:email:Leads_Email:V'),
	
		   Array('account:accountname:accountname:Accounts_Account_Name:V',
			 'account:phone:phone:Accounts_Phone:V',
			 'account:website:website:Accounts_Website:V',
			 'account:rating:rating:Accounts_Rating:V',
			 'crmentity:smownerid:assigned_user_id:Accounts_Assigned_To:V'),

		   Array('account:accountname:accountname:Accounts_Account_Name:V',
                         'account:phone:phone:Accounts_Phone:V',
                         'account:website:website:Accounts_Website:V',
                         'accountbillads:city:bill_city:Accounts_City:V',
                         'crmentity:smownerid:assigned_user_id:Accounts_Assigned_To:V'),

		   Array('contactdetails:firstname:firstname:Contacts_First_Name:V',
                         'contactdetails:lastname:lastname:Contacts_Last_Name:V',
                         'contactaddress:mailingstreet:mailingstreet:Contacts_Mailing_Street:V',
                         'contactaddress:mailingcity:mailingcity:Contacts_City:V',
                         'contactaddress:mailingstate:mailingstate:Contacts_State:V',
			 'contactaddress:mailingzip:mailingzip:Contacts_Zip:V',
			 'contactaddress:mailingcountry:mailingcountry:Contacts_Country:V'),
		   
		   Array('contactdetails:firstname:firstname:Contacts_First_Name:V',
                         'contactdetails:lastname:lastname:Contacts_Last_Name:V',
                         'contactdetails:title:title:Contacts_Title:V',
                         'contactdetails:accountid:account_id:Contacts_Account_Name:I',
                         'contactdetails:email:email:Contacts_Email:V',
			 'contactsubdetails:otherphone:otherphone:Contacts_Phone:V',
			 'crmentity:smownerid:assigned_user_id:Contacts_Assigned_To:V'),
		  
                   Array('potential:potentialname:potentialname:Potentials_Potential_Name:V',
                         'potential:accountid:account_id:Potentials_Account_Name:V',
                         'potential:amount:amount:Potentials_Amount:N',
                         'potential:leadsource:leadsource:Potentials_Lead_Source:V',
                         'potential:closingdate:closingdate:Potentials_Expected_Close_Date:D',
                         'crmentity:smownerid:assigned_user_id:Potentials_Assigned_To:V'),

		   Array('potential:potentialname:potentialname:Potentials_Potential_Name:V',
                         'potential:accountid:account_id:Potentials_Account_Name:V',
                         'potential:amount:amount:Potentials_Amount:N',
                         'potential:leadsource:leadsource:Potentials_Lead_Source:V',
                         'potential:closingdate:closingdate:Potentials_Expected_Close_Date:D',
                         'crmentity:smownerid:assigned_user_id:Potentials_Assigned_To:V'),

		   Array('troubletickets:title:ticket_title:HelpDesk_Title:V',
                         'troubletickets:parent_id:parent_id:HelpDesk_Related_to:I',
                         'troubletickets:priority:ticketpriorities:HelpDesk_Priority:V',
                         'troubletickets:product_id:product_id:HelpDesk_Product_Name:I',
                         'crmentity:smownerid:assigned_user_id:HelpDesk_Assigned_To:V'),

		   Array('troubletickets:title:ticket_title:HelpDesk_Title:V',
                         'troubletickets:parent_id:parent_id:HelpDesk_Related_to:I',
                         'troubletickets:status:ticketstatus:HelpDesk_Status:V',
                         'troubletickets:product_id:product_id:HelpDesk_Product_Name:I',
                         'crmentity:smownerid:assigned_user_id:HelpDesk_Assigned_To:V'),

		   Array('quotes:subject:subject:Quotes_Subject:V',
                         'quotes:quotestage:quotestage:Quotes_Quote_Stage:V',
                         'quotes:potentialid:potential_id:Quotes_Potential_Name:I',
                         'quotes:accountid:account_id:Quotes_Account_Name:I',
                         'quotes:validtill:validtill:Quotes_Valid_Till:D',
			 'crmentity:smownerid:assigned_user_id:Quotes_Assigned_To:V'),

		   Array('quotes:subject:subject:Quotes_Subject:V',
                         'quotes:potentialid:potential_id:Quotes_Potential_Name:I',
                         'quotes:accountid:account_id:Quotes_Account_Name:I',
                         'quotes:validtill:validtill:Quotes_Valid_Till:D',
                         'crmentity:smownerid:assigned_user_id:Quotes_Assigned_To:V')
                  );

$cvstdfilters = Array(Array('columnname'=>'crmentity:modifiedtime:modifiedtime:Leads_Modified_Time',
                            'datefilter'=>'thismonth',
                            'startdate'=>'2005-06-01',
                            'enddate'=>'2005-06-30'),

		      Array('columnname'=>'crmentity:createdtime:createdtime:Accounts_Created_Time',
                            'datefilter'=>'thisweek',
                            'startdate'=>'2005-06-19',
                            'enddate'=>'2005-06-25'),

		      Array('columnname'=>'contactsubdetails:birthday:birthday:Contacts_Birthdate',
                            'datefilter'=>'today',
                            'startdate'=>'2005-06-25',
                            'enddate'=>'2005-06-25')
                     );

$cvadvfilters = Array(
                      Array(
                            Array('columnname'=>'leaddetails:leadstatus:leadstatus:Leads_Lead_Status:V',
                                  'comparator'=>'e',
                                  'value'=>'Hot'
                                 )
                           ),

		      Array(
                            Array('columnname'=>'account:account_type:accounttype:Accounts_Type:V',
                                  'comparator'=>'e',
                                  'value'=>'Prospect'
                                 )
                           ),
		     Array(
                            Array('columnname'=>'potential:sales_stage:sales_stage:Potentials_Sales_Stage:V',
                                  'comparator'=>'e',
                                  'value'=>'Closed Won'
                                 )
                           ),
		     Array(
                            Array('columnname'=>'potential:sales_stage:sales_stage:Potentials_Sales_Stage:V',
                                  'comparator'=>'e',
                                  'value'=>'Prospecting'
                                 )
                           ),
		     Array(
                            Array('columnname'=>'troubletickets:status:ticketstatus:HelpDesk_Status:V',
                                  'comparator'=>'n',
                                  'value'=>'Closed'
                                 )
                           ),
		     Array(
                            Array('columnname'=>'troubletickets:priority:ticketpriorities:HelpDesk_Priority:V',
                                  'comparator'=>'e',
                                  'value'=>'High'
                                 )
                           ),
		     Array(
                            Array('columnname'=>'quotes:quotestage:quotestage:Quotes_Quote_Stage:V',
                                  'comparator'=>'n',
                                  'value'=>'Accepted'
                                 ),
			    Array('columnname'=>'quotes:quotestage:quotestage:Quotes_Quote_Stage:V',
                                  'comparator'=>'n',
                                  'value'=>'Rejected'
                                 )
                           ),
		     Array(
                            Array('columnname'=>'quotes:quotestage:quotestage:Quotes_Quote_Stage:V',
                                  'comparator'=>'e',
                                  'value'=>'Rejected'
                                 )
                           )
                     );

foreach($customviews as $key=>$customview)
{
        $queryid = insertCustomView($customview['viewname'],$customview['setdefault'],$customview['setmetrics'],$customview['cvmodule']);
        insertCvColumns($queryid,$cvcolumns[$key]);
	
	if(isset($cvstdfilters[$customview['stdfilterid']]))
	{
		$i = $customview['stdfilterid'];
		insertCvStdFilter($queryid,$cvstdfilters[$i]['columnname'],$cvstdfilters[$i]['datefilter'],$cvstdfilters[$i]['startdate'],$cvstdfilters[$i]['enddate']);
	}
	if(isset($cvadvfilters[$customview['advfilterid']]))
	{
        	insertCvAdvFilter($queryid,$cvadvfilters[$customview['advfilterid']]);
	}
}

function insertCustomView($viewname,$setdefault,$setmetrics,$cvmodule)
{
	global $adb;

	$genCVid = $adb->getUniqueID("customview");
	if($genCVid != "")
	{

		$customviewsql = "insert into customview(cvid,viewname,setdefault,setmetrics,entitytype)";
		$customviewsql .= " values(".$genCVid.",'".$viewname."',".$setdefault.",".$setmetrics.",'".$cvmodule."')";
		//echo $customviewsql;
		$customviewresult = $adb->query($customviewsql);
	}

	return $genCVid;
}

function insertCvColumns($CVid,$columnslist)
{
	global $adb;
	if($CVid != "")
	{
		for($i=0;$i<count($columnslist);$i++)
		{
			$columnsql = "insert into cvcolumnlist (cvid,columnindex,columnname)";
			$columnsql .= " values (".$CVid.",".$i.",'".$columnslist[$i]."')";
			//echo $columnsql;
			$columnresult = $adb->query($columnsql);
		}
	}
}

function insertCvStdFilter($CVid,$filtercolumn,$filtercriteria,$startdate,$enddate)
{
	global $adb;
	if($CVid != "")
	{
		$stdfiltersql = "insert into cvstdfilter(cvid,columnname,stdfilter,startdate,enddate)";
		$stdfiltersql .= " values (".$CVid.",'".$filtercolumn."',";
		$stdfiltersql .= "'".$filtercriteria."',";
		$stdfiltersql .= "'".$startdate."',";
		$stdfiltersql .= "'".$enddate."')";
		//echo $stdfiltersql;
		$stdfilterresult = $adb->query($stdfiltersql);
	}
}

function insertCvAdvFilter($CVid,$filters)
{
	global $adb;
	if($CVid != "")
	{
		foreach($filters as $i=>$filter)
		{
			$advfiltersql = "insert into cvadvfilter(cvid,columnindex,columnname,comparator,value)";
                        $advfiltersql .= " values (".$CVid.",".$i.",'".$filter['columnname']."',";
                        $advfiltersql .= "'".$filter['comparator']."',";
                        $advfiltersql .= "'".$filter['value']."')";
			//echo $advfiltersql;
                        $advfilterresult = $adb->query($advfiltersql);
		}

		/*for($i=0;$i<count($filtercolumns);$i++)
		{
			$advfiltersql = "insert into cvadvfilter(cvid,columnindex,columnname,comparator,value)";
			$advfiltersql .= " values (".$CVid.",".$i.",'".$filtercolumns[$i]."',";
			$advfiltersql .= "'".$filteroption[$i]."',";
			$advfiltersql .= "'".$filtervalue[$i]."')";
			//echo $advfiltersql;
			$advfilterresult = $adb->query($advfiltersql);
		}*/

	}
}
?>
