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
require_once('data/CRMEntity.php');
require_once('include/utils/UserInfoUtil.php');
global $calpath;
global $app_strings,$mod_strings;
global $app_list_strings;
global $modules;
global $blocks;
global $adv_filter_options;
global $log;

global $report_modules;
global $related_modules;

$adv_filter_options = array("e"=>"equals",
		            "n"=>"not equal to",
			    "s"=>"starts with",
			    "c"=>"contains",
			    "k"=>"does not contain",
			    "l"=>"less than",
			    "g"=>"greater than",
			    "m"=>"less or equal",
			    "h"=>"greater or equal"
			   );

$report_modules = Array('Leads','Accounts','Contacts','Potentials','Products',
			'HelpDesk','Quotes','PurchaseOrder','Invoice','Calendar','SalesOrder','Campaigns'
		       );

$related_modules = Array('Leads'=>Array(),
			 'Accounts'=>Array('Potentials','Contacts','Products','Quotes','Invoice'),
			 'Contacts'=>Array('Accounts','Potentials','Quotes','PurchaseOrder'),
			 'Potentials'=>Array('Accounts','Contacts','Quotes'),
			 'Calendar'=>Array('Contacts'),
			 'Products'=>Array('Accounts','Contacts'),
			 'HelpDesk'=>Array('Products'),
			 'Quotes'=>Array('Accounts','Contacts','Potentials'),
			 'PurchaseOrder'=>Array('Contacts'),
			 'SalesOrder'=>Array(),
			 'Invoice'=>Array('Accounts'),
			 'Campaigns'=>Array('Products')
			);

foreach($report_modules as $values)
{
	$modules[] = $values."_";
}
$modules[] = "_";

class Reports extends CRMEntity{



	/**
	 * This class has the informations for Reports and inherits class CRMEntity and
	 * has the variables required to generate,save,restore vtiger_reports
	 * and also the required functions for the same
	 * Contributor(s): ______________________________________..
	 */


	var $srptfldridjs;

	var $column_fields = Array();

	var $sort_fields = Array();
	var $sort_values = Array();

	var $id;
	var $mode;
	var $mcount;

	var $startdate;
	var $enddate;

	var $ascdescorder;

	var $stdselectedfilter;
	var $stdselectedcolumn;

	var $primodule;
	var $secmodule;
	var $columnssummary;

	var $reporttype;
	var $reportname;
	var $reportdescription;
	var $folderid;
	var $module_blocks;

	var $pri_module_columnslist;
	var $sec_module_columnslist;

	var $advft_column;
	var $advft_option;
	var $advft_value;

	var $module_list = Array(
				"Leads"=>Array("Information"=>13,"Address"=>15,"Description"=>16,"Custom Information"=>14),
				"Contacts"=>Array("Information"=>4,"- Portal Information"=>6,"Address"=>7,"Description"=>8,"Custom Information"=>5),
				"Accounts"=>Array("Information"=>9,"Address"=>11,"Description"=>12,"Custom Information"=>10),
				"Potentials"=>Array("Information"=>1,"Description"=>3,"Custom Information"=>2),
				"Calendar"=>Array("Information"=>19,"Description"=>20),
 		                "Campaigns"=>Array("Information"=>76,"Expectations"=>78,"Description"=>82,"Custom Information"=>77),
				"Products"=>Array("Information"=>31,"Description"=>36,"Pricing Information"=>32,"Stock Information"=>33,"Custom Information"=>34),
				"Notes"=>Array("Information"=>17,"Description"=>18),
				"Emails"=>Array("Information"=>21,"Description"=>24),
				"HelpDesk"=>Array("Information"=>'25,26',"Custom Information"=>27,"Description"=>28,"Solution"=>29),//patch2
				"Quotes"=>Array("Information"=>51,"Address"=>53,"Description"=>56,"Terms and Conditions"=>55,"Custom Information"=>52),
				"PurchaseOrder"=>Array("Information"=>57,"Address"=>59,"Description"=>62,"Terms and Conditions"=>61,"Custom Information"=>58),
				"SalesOrder"=>Array("Information"=>63,"Address"=>65,"Description"=>68,"Terms and Conditions"=>67,"Custom Information"=>64),
				"Invoice"=>Array("Information"=>69,"Address"=>71,"Description"=>74,"Terms and Conditions"=>73,"Custom Information"=>70)
				);

	/** Function to set primodule,secmodule,reporttype,reportname,reportdescription,folderid for given vtiger_reportid
	 *  This function accepts the vtiger_reportid as argument
	 *  It sets primodule,secmodule,reporttype,reportname,reportdescription,folderid for the given vtiger_reportid
	 */

	function Reports($reportid="")
	{
		global $adb;

		if($reportid != "")
		{
			$ssql = "select vtiger_reportmodules.*,vtiger_report.* from vtiger_report inner join vtiger_reportmodules on vtiger_report.reportid = vtiger_reportmodules.reportmodulesid";
			$ssql .= " where vtiger_report.reportid =".$reportid;
			$result = $adb->query($ssql);
			$reportmodulesrow = $adb->fetch_array($result);
			if($reportmodulesrow)
			{
				$this->primodule = $reportmodulesrow["primarymodule"];
				$this->secmodule = $reportmodulesrow["secondarymodules"];
				$this->reporttype = $reportmodulesrow["reporttype"];
				$this->reportname = $reportmodulesrow["reportname"];
				$this->reportdescription = $reportmodulesrow["description"];
				$this->folderid = $reportmodulesrow["folderid"];
			}
		}
	}


	/** Function to get the Listview of Reports
	 *  This function accepts no argument
	 *  This generate the Reports view page and returns a string
	 *  contains HTML 
	 */

	function sgetRptFldr($mode='')
	{

		global $adb;
		global $log;
		$returndata = Array();
		$sql = "select * from vtiger_reportfolder order by folderid";
		$result = $adb->query($sql);
		$reportfldrow = $adb->fetch_array($result);
		if($mode != '')
		{
			do
			{
				if($reportfldrow["state"] == $mode)
				{
					$details = Array();	
					$details['state'] = $reportfldrow["state"]; 
					$details['id'] = $reportfldrow["folderid"]; 
					$details['name'] = $reportfldrow["foldername"]; 
					$details['description'] = $reportfldrow["description"]; 
					$details['details'] = $this->sgetRptsforFldr($reportfldrow["folderid"]);
					$returndata[] = $details;
				}
			}while($reportfldrow = $adb->fetch_array($result));
		}else
		{
			do
			{
				$details = Array();	
				$details['state'] = $reportfldrow["state"]; 
				$details['id'] = $reportfldrow["folderid"]; 
				$details['name'] = $reportfldrow["foldername"]; 
				$details['description'] = $reportfldrow["description"]; 
				$returndata[] = $details;
			}while($reportfldrow = $adb->fetch_array($result));
		}

		$log->info("Reports :: ListView->Successfully returned vtiger_report folder HTML");
		return $returndata;
	}

	/** Function to get the Reports inside each modules
	 *  This function accepts the folderid
	 *  This Generates the Reports under each Reports module 
	 *  This Returns a HTML sring
	 */

	function sgetRptsforFldr($rpt_fldr_id)
	{
		$srptdetails="";
		global $adb;
		global $log;
		global $mod_strings;
		$returndata = Array();

		require_once('include/utils/UserInfoUtil.php');

		$sql = "select vtiger_report.*, vtiger_reportmodules.* from vtiger_report inner join vtiger_reportfolder on vtiger_reportfolder.folderid = vtiger_report.folderid";
		$sql .= " inner join vtiger_reportmodules on vtiger_reportmodules.reportmodulesid = vtiger_report.reportid where vtiger_reportfolder.folderid=".$rpt_fldr_id;
		$result = $adb->query($sql);
		$report = $adb->fetch_array($result);
		if(count($report)>0)
		{
			do
			{
				$report_details = Array();
				$report_details ['customizable'] = $report["customizable"];
				$report_details ['reportid'] = $report["reportid"];
				$report_details ['primarymodule'] = $report["primarymodule"];
				$report_details ['secondarymodules'] = $report["secondarymodules"];
				$report_details ['state'] = $report["state"];
				$report_details ['description'] = $report["description"];
				$report_details ['reportname'] = $report["reportname"];

				$returndata []=$report_details; 
			}while($report = $adb->fetch_array($result));
		}

		$log->info("Reports :: ListView->Successfully returned vtiger_report details HTML");
		return $returndata;
	}

	/** Function to get the array of ids
	 *  This function forms the array for the ExpandCollapse
	 *  Javascript
	 *  It returns the array of ids
	 *  Array('1RptFldr','2RptFldr',........,'9RptFldr','10RptFldr')
	 */

	function sgetJsRptFldr()
	{
		$srptfldr_js = "var ReportListArray=new Array(".$this->srptfldridjs.")
			setExpandCollapse()";
		return $srptfldr_js;
	}

	/** Function to set the Primary module vtiger_fields for the given Report 
	 *  This function sets the primary module columns for the given Report 
	 *  It accepts the Primary module as the argument and set the vtiger_fields of the module
	 *  to the varialbe pri_module_columnslist and returns true if sucess
	 */

	function getPriModuleColumnsList($module)
	{
		foreach($this->module_list[$module] as $key=>$value)
		{
			$ret_module_list[$module][$key] = $this->getColumnsListbyBlock($module,$value);
		}
		$this->pri_module_columnslist = $ret_module_list;
		return true;
	}

	/** Function to set the Secondary module fileds for the given Report
	 *  This function sets the secondary module columns for the given module
	 *  It accepts the module as the argument and set the vtiger_fields of the module
	 *  to the varialbe sec_module_columnslist and returns true if sucess
	 */

	function getSecModuleColumnsList($module)
	{
		if($module != "")
		{
			$secmodule = explode(":",$module);
			for($i=0;$i < count($secmodule) ;$i++)
			{
				foreach($this->module_list[$secmodule[$i]] as $key=>$value)
				{
					$ret_module_list[$secmodule[$i]][$key] = $this->getColumnsListbyBlock($secmodule[$i],$value);
				}
			}
			$this->sec_module_columnslist = $ret_module_list;
		}
		return true;
	}

	/** Function to get vtiger_fields for the given module and block
	 *  This function gets the vtiger_fields for the given module
	 *  It accepts the module and the block as arguments and 
	 *  returns the array column lists
	 *  Array module_columnlist[ vtiger_fieldtablename:fieldcolname:module_fieldlabel1:fieldname:fieldtypeofdata]=fieldlabel
	 */

	function getColumnsListbyBlock($module,$block)
	{
		global $adb;
		global $log;
		global $current_user;


		$tabid = getTabid($module);

		
		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		//Security Check 
		if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] ==0)
		{
			$sql = "select * from vtiger_field where vtiger_field.uitype != 50 and vtiger_field.tabid=".$tabid." and vtiger_field.block in (".$block .") and vtiger_field.displaytype in (1,2) order by sequence";
		}
		else
		{
			
			$profileList = getCurrentUserProfileList();
			$sql = "select * from vtiger_field inner join vtiger_profile2field on vtiger_profile2field.fieldid=vtiger_field.fieldid inner join vtiger_def_org_field on vtiger_def_org_field.fieldid=vtiger_field.fieldid where vtiger_field.uitype != 50 and vtiger_field.tabid=".$tabid." and vtiger_field.block in (".$block .") and vtiger_field.displaytype in (1,2) and vtiger_profile2field.visible=0 and vtiger_def_org_field.visible=0 and vtiger_profile2field.profileid in ".$profileList." group by vtiger_field.fieldid order by sequence";
		}
		$result = $adb->query($sql);
		$noofrows = $adb->num_rows($result);
		for($i=0; $i<$noofrows; $i++)
		{
			$fieldtablename = $adb->query_result($result,$i,"tablename");
			$fieldcolname = $adb->query_result($result,$i,"columnname");
			$fieldname = $adb->query_result($result,$i,"fieldname");
			$fieldtype = $adb->query_result($result,$i,"typeofdata");
			$fieldtype = explode("~",$fieldtype);
			$fieldtypeofdata = $fieldtype[0];
			if($fieldtablename == "vtiger_crmentity")
			{
				$fieldtablename = $fieldtablename.$module;
			}
			if($fieldname == "assigned_user_id")
			{
				$fieldtablename = "vtiger_users".$module;
				$fieldcolname = "user_name";
			}
			if($fieldname == "account_id")
			{
				$fieldtablename = "vtiger_account".$module;
				$fieldcolname = "accountname";
			}
			if($fieldname == "contact_id")
			{
				$fieldtablename = "vtiger_contactdetails".$module;
				$fieldcolname = "lastname";
			}
			if($fieldname == "parent_id")
			{
				$fieldtablename = "vtiger_crmentityRel".$module;
				$fieldcolname = "setype";
			}
			if($fieldname == "vendor_id")
			{
				$fieldtablename = "vtiger_vendorRel";
				$fieldcolname = "vendorname";
			}
			if($fieldname == "potential_id")
			{
				$fieldtablename = "vtiger_potentialRel";
				$fieldcolname = "potentialname";
			}
			if($fieldname == "assigned_user_id1")
			{
				$fieldtablename = "vtiger_usersRel1";
				$fieldcolname = "user_name";
			}
			if($fieldname == 'quote_id')
			{
				$fieldtablename = "vtiger_quotes".$module;
				$fieldcolname = "subject";
			}
			if($fieldname == 'product_id' && $fieldtablename == 'vtiger_troubletickets')
			{
				$fieldtablename = "vtiger_productsRel";
				$fieldcolname = "productname";
			}

			$fieldlabel = $adb->query_result($result,$i,"fieldlabel");
			$fieldlabel1 = str_replace(" ","_",$fieldlabel);
			$optionvalue = $fieldtablename.":".$fieldcolname.":".$module."_".$fieldlabel1.":".$fieldname.":".$fieldtypeofdata;
			//added to escape attachments fields in Reports as we have multiple attachments
                        if($module != 'HelpDesk' || $fieldname !='filename')
				$module_columnlist[$optionvalue] = $fieldlabel;
		}
		$log->info("Reports :: FieldColumns->Successfully returned ColumnslistbyBlock".$module.$block);
		return $module_columnlist;
	}

	/** Function to set the standard filter vtiger_fields for the given vtiger_report
	 *  This function gets the standard filter vtiger_fields for the given vtiger_report
	 *  and set the values to the corresponding variables
	 *  It accepts the repordid as argument 
	 */

	function getSelectedStandardCriteria($reportid)
	{
		global $adb;
		$sSQL = "select vtiger_reportdatefilter.* from vtiger_reportdatefilter inner join vtiger_report on vtiger_report.reportid = vtiger_reportdatefilter.datefilterid where vtiger_report.reportid=".$reportid;

		$result = $adb->query($sSQL);
		$selectedstdfilter = $adb->fetch_array($result);

		$this->stdselectedcolumn = $selectedstdfilter["datecolumnname"];
		$this->stdselectedfilter = $selectedstdfilter["datefilter"];

		if($selectedstdfilter["datefilter"] == "custom")
		{
			if($selectedstdfilter["startdate"] != "0000-00-00")
			{
				$this->startdate = $selectedstdfilter["startdate"]; 
			}
			if($selectedstdfilter["enddate"] != "0000-00-00")
			{
				$this->enddate = $selectedstdfilter["enddate"]; 
			}
		}
	}

	/** Function to get the combo values for the standard filter
	 *  This function get the combo values for the standard filter for the given vtiger_report
	 *  and return a HTML string 
	 */

	function getSelectedStdFilterCriteria($selecteddatefilter = "")
	{
		global $mod_strings;

		$datefiltervalue = Array("custom","prevfy","thisfy","nextfy","prevfq","thisfq","nextfq",
				"yesterday","today","tomorrow","lastweek","thisweek","nextweek","lastmonth","thismonth",
				"nextmonth","last7days","last30days", "last60days","last90days","last120days",
				"next30days","next60days","next90days","next120days"
				);

		$datefilterdisplay = Array("Custom","Previous FY", "Current FY","Next FY","Previous FQ","Current FQ","Next FQ","Yesterday",
				"Today","Tomorrow","Last Week","Current Week","Next Week","Last Month","Current Month",
				"Next Month","Last 7 Days","Last 30 Days","Last 60 Days","Last 90 Days","Last 120 Days",
				"Next 7 Days","Next 30 Days","Next 60 Days","Next 90 Days","Next 120 Days"
				);

		for($i=0;$i<count($datefiltervalue);$i++)
		{
			if($selecteddatefilter == $datefiltervalue[$i])
			{
				$sshtml .= "<option selected value='".$datefiltervalue[$i]."'>".$mod_strings[$datefilterdisplay[$i]]."</option>";
			}else
			{
				$sshtml .= "<option value='".$datefiltervalue[$i]."'>".$mod_strings[$datefilterdisplay[$i]]."</option>";
			}
		}

		return $sshtml;
	}

	/** Function to get the selected standard filter columns 
	 *  This function returns the selected standard filter criteria 
	 *  which is selected for vtiger_reports as an array
	 *  Array stdcriteria_list[fieldtablename:fieldcolname:module_fieldlabel1]=fieldlabel
	 */

	function getStdCriteriaByModule($module)
	{	
		global $adb;
		global $log;
		global $current_user;
		require('user_privileges/user_privileges_'.$current_user->id.'.php');		

		$tabid = getTabid($module);
		foreach($this->module_list[$module] as $key=>$blockid)
		{
			$blockids[] = $blockid;
		}	
		$blockids = implode(",",$blockids);	

		if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0)
		{
			$sql = "select * from vtiger_field where vtiger_field.tabid=".$tabid." and (vtiger_field.uitype =5 or vtiger_field.displaytype=2) and vtiger_field.block in (".$blockids.") order by vtiger_field.sequence";
		}
		else
		{

			$profileList = getCurrentUserProfileList();
			$sql = "select * from vtiger_field inner join vtiger_tab on vtiger_tab.tabid = vtiger_field.tabid inner join vtiger_profile2field on vtiger_profile2field.fieldid=vtiger_field.fieldid inner join vtiger_def_org_field on vtiger_def_org_field.fieldid=vtiger_field.fieldid  where vtiger_field.tabid=".$tabid." and (vtiger_field.uitype =5 or vtiger_field.displaytype=2) and vtiger_profile2field.visible=0 and vtiger_def_org_field.visible=0 and vtiger_field.block in (".$blockids.") and vtiger_profile2field.profileid in ".$profileList." order by vtiger_field.sequence";

		}

		$result = $adb->query($sql);

		while($criteriatyperow = $adb->fetch_array($result))
		{
			$fieldtablename = $criteriatyperow["tablename"];
			$fieldcolname = $criteriatyperow["columnname"];
			$fieldlabel = $criteriatyperow["fieldlabel"];

			if($fieldtablename == "vtiger_crmentity")
			{
				$fieldtablename = $fieldtablename.$module;
			}
			$fieldlabel1 = str_replace(" ","_",$fieldlabel);
			$optionvalue = $fieldtablename.":".$fieldcolname.":".$module."_".$fieldlabel1;
			$stdcriteria_list[$optionvalue] = $fieldlabel;
		}

		$log->info("Reports :: StdfilterColumns->Successfully returned Stdfilter for".$module);
		return $stdcriteria_list;

	}

	/** Function to form a javascript to determine the start date and end date for a standard filter 
	 *  This function is to form a javascript to determine
	 *  the start date and End date from the value selected in the combo lists
	 */

	function getCriteriaJS()
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


		$sjsStr = '<script language="JavaScript" type="text/javaScript">
			function showDateRange( type )
			{
				if (type!="custom")
				{
					document.NewReport.startdate.readOnly=true
						document.NewReport.enddate.readOnly=true
						getObj("jscal_trigger_date_start").style.visibility="hidden"
						getObj("jscal_trigger_date_end").style.visibility="hidden"
				}
				else
				{
					document.NewReport.startdate.readOnly=false
						document.NewReport.enddate.readOnly=false
						getObj("jscal_trigger_date_start").style.visibility="visible"
						getObj("jscal_trigger_date_end").style.visibility="visible"
				}
				if( type == "today" )
				{

					document.NewReport.startdate.value = "'.$today.'";
					document.NewReport.enddate.value = "'.$today.'";
				}
				else if( type == "yesterday" )
				{

					document.NewReport.startdate.value = "'.$yesterday.'";
					document.NewReport.enddate.value = "'.$yesterday.'";
				}
				else if( type == "tomorrow" )
				{

					document.NewReport.startdate.value = "'.$tomorrow.'";
					document.NewReport.enddate.value = "'.$tomorrow.'";
				}        
				else if( type == "thisweek" )
				{

					document.NewReport.startdate.value = "'.$thisweek0.'";
					document.NewReport.enddate.value = "'.$thisweek1.'";
				}                
				else if( type == "lastweek" )
				{

					document.NewReport.startdate.value = "'.$lastweek0.'";
					document.NewReport.enddate.value = "'.$lastweek1.'";
				}                
				else if( type == "nextweek" )
				{

					document.NewReport.startdate.value = "'.$nextweek0.'";
					document.NewReport.enddate.value = "'.$nextweek1.'";
				}                

				else if( type == "thismonth" )
				{

					document.NewReport.startdate.value = "'.$currentmonth0.'";
					document.NewReport.enddate.value = "'.$currentmonth1.'";
				}                

				else if( type == "lastmonth" )
				{

					document.NewReport.startdate.value = "'.$lastmonth0.'";
					document.NewReport.enddate.value = "'.$lastmonth1.'";
				}             
				else if( type == "nextmonth" )
				{

					document.NewReport.startdate.value = "'.$nextmonth0.'";
					document.NewReport.enddate.value = "'.$nextmonth1.'";
				}           
				else if( type == "next7days" )
				{

					document.NewReport.startdate.value = "'.$today.'";
					document.NewReport.enddate.value = "'.$next7days.'";
				}                
				else if( type == "next30days" )
				{

					document.NewReport.startdate.value = "'.$today.'";
					document.NewReport.enddate.value = "'.$next30days.'";
				}                
				else if( type == "next60days" )
				{

					document.NewReport.startdate.value = "'.$today.'";
					document.NewReport.enddate.value = "'.$next60days.'";
				}                
				else if( type == "next90days" )
				{

					document.NewReport.startdate.value = "'.$today.'";
					document.NewReport.enddate.value = "'.$next90days.'";
				}        
				else if( type == "next120days" )
				{

					document.NewReport.startdate.value = "'.$today.'";
					document.NewReport.enddate.value = "'.$next120days.'";
				}        
				else if( type == "last7days" )
				{

					document.NewReport.startdate.value = "'.$last7days.'";
					document.NewReport.enddate.value =  "'.$today.'";
				}                        
				else if( type == "last30days" )
				{

					document.NewReport.startdate.value = "'.$last30days.'";
					document.NewReport.enddate.value = "'.$today.'";
				}                
				else if( type == "last60days" )
				{

					document.NewReport.startdate.value = "'.$last60days.'";
					document.NewReport.enddate.value = "'.$today.'";
				}        
				else if( type == "last90days" )
				{

					document.NewReport.startdate.value = "'.$last90days.'";
					document.NewReport.enddate.value = "'.$today.'";
				}        
				else if( type == "last120days" )
				{

					document.NewReport.startdate.value = "'.$last120days.'";
					document.NewReport.enddate.value = "'.$today.'";
				}        
				else if( type == "thisfy" )
				{

					document.NewReport.startdate.value = "'.$currentFY0.'";
					document.NewReport.enddate.value = "'.$currentFY1.'";
				}                
				else if( type == "prevfy" )
				{

					document.NewReport.startdate.value = "'.$lastFY0.'";
					document.NewReport.enddate.value = "'.$lastFY1.'";
				}                
				else if( type == "nextfy" )
				{

					document.NewReport.startdate.value = "'.$nextFY0.'";
					document.NewReport.enddate.value = "'.$nextFY1.'";
				}                
				else if( type == "nextfq" )
				{

					document.NewReport.startdate.value = "2005-07-01";
					document.NewReport.enddate.value = "2005-09-30";
				}                        
				else if( type == "prevfq" )
				{

					document.NewReport.startdate.value = "2005-01-01";
					document.NewReport.enddate.value = "2005-03-31";
				}                
				else if( type == "thisfq" )
				{
					document.NewReport.startdate.value = "2005-04-01";
					document.NewReport.enddate.value = "2005-06-30";
				}                
				else
				{
					document.NewReport.startdate.value = "";
					document.NewReport.enddate.value = "";
				}        
			}        
		</script>';

		return $sjsStr;
	}

	/** Function to set the order of grouping and to find the columns responsible
	 *  to the grouping
	 *  This function accepts the vtiger_reportid as variable,sets the variable ascdescorder[] to the sort order and
	 *  returns the array array_list which has the column responsible for the grouping
	 *  Array array_list[0]=columnname
	 */


	function getSelctedSortingColumns($reportid)
	{

		global $adb;
		global $log;

		$sreportsortsql = "select vtiger_reportsortcol.* from vtiger_report";
		$sreportsortsql .= " inner join vtiger_reportsortcol on vtiger_report.reportid = vtiger_reportsortcol.reportid";
		$sreportsortsql .= " where vtiger_report.reportid =".$reportid." order by vtiger_reportsortcol.sortcolid";

		$result = $adb->query($sreportsortsql);
		$noofrows = $adb->num_rows($result);

		for($i=0; $i<$noofrows; $i++)
		{
			$fieldcolname = $adb->query_result($result,$i,"columnname");
			$sort_values = $adb->query_result($result,$i,"sortorder");
			$this->ascdescorder[] = $sort_values;
			$array_list[] = $fieldcolname;
		}

		$log->info("Reports :: Successfully returned getSelctedSortingColumns");
		return $array_list;
	}

	/** Function to get the selected columns list for a selected vtiger_report
	 *  This function accepts the vtiger_reportid as the argument and get the selected columns
	 *  for the given vtiger_reportid and it forms a combo lists and returns
	 *  HTML of the combo values
	 */

	function getSelectedColumnsList($reportid)
	{

		global $adb;
		global $modules;
		global $log;

		$ssql = "select vtiger_selectcolumn.* from vtiger_report inner join vtiger_selectquery on vtiger_selectquery.queryid = vtiger_report.queryid";
		$ssql .= " left join vtiger_selectcolumn on vtiger_selectcolumn.queryid = vtiger_selectquery.queryid where vtiger_report.reportid =".$reportid;
		$ssql .= " order by vtiger_selectcolumn.columnindex";

		$result = $adb->query($ssql);
		$noofrows = $adb->num_rows($result);

		for($i=0; $i<$noofrows; $i++)
		{
			$fieldcolname = $adb->query_result($result,$i,"columnname");
			$fieldlist = explode(":",$fieldcolname);
			if($fieldcolname != "")
			{
				$shtml .= "<option value=\"".$fieldcolname."\">".str_replace($modules," ",$fieldlist[2])."</option>";
			}
		}

		$log->info("Reports :: Successfully returned getSelectedColumnsList");
		return $shtml;
	}

	/** Function to Set the selected columns for the advanced filter for the vtiger_report
	 *  This function accepts the vtiger_reportid as the argument and get the selected columns
	 *  in the advanced filter and sets the values
	 *  $this->advft_column[] = The column name
	 *  $this->advft_option[] = The filter option
	 *  $this->advft_value[] = The value to be compared
	 *	and returns true in sucess
	 */

	//<<<<<<<<advanced filter>>>>>>>>>>>>>>
	function getAdvancedFilterList($reportid)
	{
		global $adb;
		global $modules;
		global $log;
		$ssql = 'select vtiger_relcriteria.* from vtiger_report inner join vtiger_relcriteria on vtiger_relcriteria.queryid = vtiger_report.queryid left join vtiger_selectquery on vtiger_relcriteria.queryid = vtiger_selectquery.queryid';
		$ssql.= " where vtiger_report.reportid =".$reportid." order by vtiger_relcriteria.columnindex";

		$result = $adb->query($ssql);

		while($relcriteriarow = $adb->fetch_array($result))
		{
			$this->advft_column[] = $relcriteriarow["columnname"];
			$this->advft_option[] = $relcriteriarow["comparator"];
			$this->advft_value[] = $relcriteriarow["value"];
		}

		$log->info("Reports :: Successfully returned getAdvancedFilterList");
		return true;
	}
	//<<<<<<<<advanced filter>>>>>>>>>>>>>>

	/** Function to get the list of vtiger_report folders when Save and run  the vtiger_report
	 *  This function gets the vtiger_report folders from database and form
	 *  a combo values of the folders and return 
	 *  HTML of the combo values
	 */

	function sgetRptFldrSaveReport()
	{
		global $adb;
		global $log;

		$sql = "select * from vtiger_reportfolder order by folderid";
		$result = $adb->query($sql);
		$reportfldrow = $adb->fetch_array($result);
		$x = 0;
		do
		{
			$shtml .= "<option value='".$reportfldrow['folderid']."'>".$reportfldrow['foldername']."</option>";
		}while($reportfldrow = $adb->fetch_array($result));

		$log->info("Reports :: Successfully returned sgetRptFldrSaveReport");
		return $shtml;
	}

	/** Function to get the column to total vtiger_fields in Reports 
	 *  This function gets columns to total vtiger_field 
	 *  and generated the html for that vtiger_fields
	 *  It returns the HTML of the vtiger_fields along with the check boxes
	 */

	function sgetColumntoTotal($primarymodule,$secondarymodule)
	{
		$options = Array();
		$options []= $this->sgetColumnstoTotalHTML($primarymodule,0);
		if($secondarymodule != "")
		{
			$secondarymodule = explode(":",$secondarymodule);
			for($i=0;$i < count($secondarymodule) ;$i++)
			{
				$options []= $this->sgetColumnstoTotalHTML($secondarymodule[$i],($i+1));
			}
		}
		return $options;
	}

	/** Function to get the selected columns of total vtiger_fields in Reports
	 *  This function gets selected columns of total vtiger_field
	 *  and generated the html for that vtiger_fields
	 *  It returns the HTML of the vtiger_fields along with the check boxes
	 */


	function sgetColumntoTotalSelected($primarymodule,$secondarymodule,$reportid)
	{
		global $adb;
		global $log;
		$options = Array();
		if($reportid != "")
		{
			$ssql = "select vtiger_reportsummary.* from vtiger_reportsummary inner join vtiger_report on vtiger_report.reportid = vtiger_reportsummary.reportsummaryid where vtiger_report.reportid=".$reportid;
			$result = $adb->query($ssql);
			if($result)
			{
				$reportsummaryrow = $adb->fetch_array($result);

				do
				{
					$this->columnssummary[] = $reportsummaryrow["columnname"];

				}while($reportsummaryrow = $adb->fetch_array($result));
			}
		}	
		$options []= $this->sgetColumnstoTotalHTML($primarymodule,0);
		if($secondarymodule != "")
		{
			$secondarymodule = explode(":",$secondarymodule);
			for($i=0;$i < count($secondarymodule) ;$i++)
			{
				$options []= $this->sgetColumnstoTotalHTML($secondarymodule[$i],($i+1));
			}
		}

		$log->info("Reports :: Successfully returned sgetColumntoTotalSelected");
		return $options;
	}


	/** Function to form the HTML for columns to total	
	 *  This function formulates the HTML format of the
	 *  vtiger_fields along with four checkboxes
	 *  It returns the HTML of the vtiger_fields along with the check boxes
	 */


	function sgetColumnstoTotalHTML($module)
	{
		//retreive the vtiger_tabid	
		global $adb;
		global $log;
		global $current_user;
		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		$tabid = getTabid($module);
		$escapedchars = Array('_SUM','_AVG','_MIN','_MAX');
		if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] ==0)
		{
			$ssql = "select * from vtiger_field inner join vtiger_tab on vtiger_tab.tabid = vtiger_field.tabid where vtiger_field.uitype != 50 and vtiger_field.tabid=".$tabid." and vtiger_field.displaytype = 1 order by sequence";
		}
		else
		{
			$profileList = getCurrentUserProfileList();
			$ssql = "select * from vtiger_field inner join vtiger_tab on vtiger_tab.tabid = vtiger_field.tabid inner join vtiger_def_org_field on vtiger_def_org_field.fieldid=vtiger_field.fieldid inner join vtiger_profile2field on vtiger_profile2field.fieldid=vtiger_field.fieldid  where vtiger_field.uitype != 50 and vtiger_field.tabid=".$tabid." and vtiger_field.displaytype = 1 and vtiger_def_org_field.visible=0 and vtiger_profile2field.visible=0 and vtiger_profile2field.profileid in ".$profileList." order by sequence";
		}
		$result = $adb->query($ssql);
		$columntototalrow = $adb->fetch_array($result);
		$options_list = Array();	
		do
		{
			$typeofdata = explode("~",$columntototalrow["typeofdata"]);

			if($typeofdata[0] == "N" || $typeofdata[0] == "I")
			{
				$options = Array();
				if(isset($this->columnssummary))
				{
					$selectedcolumn = "";
					$selectedcolumn1 = "";

					for($i=0;$i < count($this->columnssummary) ;$i++)
					{
						$selectedcolumnarray = explode(":",$this->columnssummary[$i]);
						$selectedcolumn = $selectedcolumnarray[1].":".$selectedcolumnarray[2].":".
							str_replace($escapedchars,"",$selectedcolumnarray[3]);

						if ($selectedcolumn != $columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.str_replace(" ","_",$columntototalrow['fieldlabel']))
						{
							$selectedcolumn = "";
						}else
						{
							$selectedcolumn1[$selectedcolumnarray[4]] = $this->columnssummary[$i];
						}

					}

					$columntototalrow['fieldlabel'] = str_replace(" ","_",$columntototalrow['fieldlabel']);
					$options []= $columntototalrow['tablabel'].' - '.$columntototalrow['fieldlabel'];
					if($selectedcolumn1[2] == "cb:".$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel']."_SUM:2")
					{
						$options []=  '<input checked name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].'_SUM:2" type="checkbox" value="">';					    
					}else
					{
						$options []=  '<input name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].'_SUM:2" type="checkbox" value="">';
					}
					if($selectedcolumn1[3] == "cb:".$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel']."_AVG:3")
					{
						$options []=  '<input checked name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].'_AVG:3" type="checkbox" value="">';
					}else
					{
						$options []=  '<input name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].'_AVG:3" type="checkbox" value="">';
					}

					if($selectedcolumn1[4] == "cb:".$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel']."_MIN:4")
					{
						$options []=  '<input checked name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].'_MIN:4" type="checkbox" value="">';
					}else
					{
						$options []=  '<input name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].'_MIN:4" type="checkbox" value="">';
					}

					if($selectedcolumn1[5] == "cb:".$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel']."_MAX:5")
					{
						$options []=  '<input checked name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].'_MAX:5" type="checkbox" value="">';
					}else
					{
						$options []=  '<input name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].'_MAX:5" type="checkbox" value="">';
					}
				}else
				{
					$options []= $columntototalrow['tablabel'].' - '.$columntototalrow['fieldlabel'];
					$options []= '<input name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].'_SUM:2" type="checkbox" value="">';
					$options []= '<input name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].'_AVG:3" type="checkbox" value="" >';
					$options []= '<input name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].'_MIN:4"type="checkbox" value="" >';
					$options [] ='<input name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].'_MAX:5" type="checkbox" value="" >';	
				}
				$options_list [] = $options;
			}
		}while($columntototalrow = $adb->fetch_array($result));

		$log->info("Reports :: Successfully returned sgetColumnstoTotalHTML");
		return $options_list;
	}
}

/** Function to get the primary module list in vtiger_reports
 *  This function generates the list of primary modules in vtiger_reports
 *  and returns an array of permitted modules 
 */

function getReportsModuleList()
{
	global $adb;
	global $app_list_strings;
	global $report_modules;	
	global $mod_strings;
	$modules = Array();
	foreach($app_list_strings['moduleList'] as $key=>$value)
	{
		for($i=0;$i<count($report_modules);$i++)
		{
			if($key == $report_modules[$i])
			{
				if(isPermitted($key,'index') == "yes")
				{
					$count_flag = 1;
					$modules [$key] = $value;
				}
			}
		}
		
	}
	return $modules;
}
/** Function to get the Related module list in vtiger_reports
 *  This function generates the list of secondary modules in vtiger_reports
 *  and returns the related module as an Array 
 */

function getReportRelatedModules($module)
{
	global $app_list_strings;
	global $related_modules;
	global $mod_strings;
	$optionhtml = Array();
	foreach($related_modules[$module] as $rel_modules)
	{
		if(isPermitted($rel_modules,'index') == "yes")
		{
			$optionhtml []= $rel_modules;		
		}	
	}
	return $optionhtml;
}
?>
