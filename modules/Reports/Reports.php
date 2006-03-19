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
global $profileList;

$profileList = getCurrentUserProfileList();
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
			'HelpDesk','Quotes','PurchaseOrder','Invoice','Activities'
		       );

$related_modules = Array('Leads'=>Array(''),
			 'Accounts'=>Array('Potentials','Contacts','Products','Quotes','Invoice'),
			 'Contacts'=>Array('Accounts','Potentials','Quotes','PurchaseOrder'),
			 'Potentials'=>Array('Accounts','Contacts','Quotes'),
			 'Activities'=>Array('Contacts'),
			 'Products'=>Array('Accounts','Contacts'),
			 'HelpDesk'=>Array('Products'),
			 'Quotes'=>Array('Accounts','Contacts','Potentials'),
			 'PurchaseOrder'=>Array('Contacts'),
			 'Invoice'=>Array('Accounts')
			);

foreach($report_modules as $values)
{
	$modules[] = $values."_";
}
$modules[] = "_";

class Reports extends CRMEntity{



/**
 * This class has the informations for Reports and inherits class CRMEntity and
 * has the variables required to generate,save,restore reports
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

	var $module_list = Array("Leads"=>Array("Information"=>13,"Address"=>15,"Description"=>16,"Custom Information"=>5),
	 			 "Contacts"=>Array("Information"=>4,"- Portal Information"=>6,"Address"=>7,"Description"=>8,"Custom Information"=>5),
				 "Accounts"=>Array("Information"=>9,"Address"=>11,"Description"=>12,"Custom Information"=>5),
				 "Potentials"=>Array("Information"=>1,"Description"=>3,"Custom Information"=>5),
				 "Activities"=>Array("Information"=>19,"Description"=>20),
				 "Products"=>Array("Information"=>31,"Description"=>36,"Custom Information"=>5),
				 "Notes"=>Array("Information"=>17,"Description"=>18),
				 "Emails"=>Array("Information"=>1,"Description"=>24),
				 "HelpDesk"=>Array("Information"=>'25,26',"Custom Information"=>5,"Description"=>28,"Solution"=>29),//patch2
				 "Quotes"=>Array("Information"=>51,"Address"=>53,"Description"=>56,"Custom Information"=>5),
				 "PurchaseOrder"=>Array("Information"=>57,"Address"=>59,"Description"=>61,"Custom Information"=>5),
				 "Invoice"=>Array("Information"=>69,"Address"=>71,"Description"=>74,"Custom Information"=>5)
				);

/** Function to set primodule,secmodule,reporttype,reportname,reportdescription,folderid for given reportid
 *  This function accepts the reportid as argument
 *  It sets primodule,secmodule,reporttype,reportname,reportdescription,folderid for the given reportid
 */
			
	function Reports($reportid="")
	{
		global $adb;

		if($reportid != "")
		{
			$ssql = "select reportmodules.*,report.* from report inner join reportmodules on report.reportid = reportmodules.reportmodulesid";
			$ssql .= " where report.reportid =".$reportid;
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
	
	function sgetRptFldr()
	{

		global $adb;
		global $log;

		$sql = "select * from reportfolder order by folderid";
		$result = $adb->query($sql);
		$reportfldrow = $adb->fetch_array($result);
		$x = 0;
        do
		{
			$reporttempid = $reportfldrow["folderid"]."RptFldr";
			$reporttempidjs[$x] = "'".$reportfldrow["folderid"]."RptFldr'";

			$shtml .= "<br><table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			<tr>
			    <td width=\"15\" nowrap>
				<div align=\"center\">
					<a href=\"javascript:toggleReports('".$reporttempid."')\"><img id=\"".$reporttempid."img\" src=\"".$image_path."collapse.gif\" border=\"0\" align=\"absmiddle\"></a>
				</div>
			  	</td>
			  	<td height=\"20\" class=\"uline\">
					<a class=\"relListHead\" href=\"javascript:toggleReports('".$reporttempid."')\">".$reportfldrow["foldername"]."</a>";
				    if($reportfldrow["state"]=="SAVED")
					{
						$shtml .="";
					}else
					{
						$shtml .="&nbsp;&nbsp;[ <a href=\"index.php?module=Reports&action=NewReportFolder&record=".$reportfldrow["folderid"] ."\" class=\"link\">Edit Folder</a>
						<span class=\"sep\">|</span>
						<a onclick=\"return window.confirm('Are you sure?');\" href=\"index.php?module=Reports&action=DeleteReportFolder&record=".$reportfldrow["folderid"] ."\" class=\"link\">Del Folder</a> ]";
					}
					$shtml .="
			   	</td>
			 </tr>
			</table>";
			$shtml .= $this->sgetRptsforFldr($reportfldrow["folderid"]);
			$x = $x + 1;

		}while($reportfldrow = $adb->fetch_array($result));
		$this->srptfldridjs = implode(",",$reporttempidjs);

		$log->info("Reports :: ListView->Successfully returned report folder HTML");
		return $shtml;
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
		
		require_once('include/utils/UserInfoUtil.php');
		
		$sql = "select report.*, reportmodules.* from report inner join reportfolder on reportfolder.folderid = report.folderid";
		$sql .= " inner join reportmodules on reportmodules.reportmodulesid = report.reportid where reportfolder.folderid=".$rpt_fldr_id;
		$result = $adb->query($sql);
		$report = $adb->fetch_array($result);
		if(count($report)>0)
		{
			$srptdetails .= '<div id="'.$rpt_fldr_id.'RptFldr" style="display:block">
				<table width="95%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				  <td width="15">&nbsp;</td>
				  <td>
				    <table border="0" width="100%" cellspacing="0" cellpadding="0" class="formOuterBorder">
					  <tr>
						<td width="15%" nowrap height="21" class="moduleListTitle" style="padding:0px 3px 0px 3px;"></td>
						<td width="30%" nowrap height="21" class="moduleListTitle" style="padding:0px 3px 0px 3px;">Report Name</td>
						<td width="55%" nowrap height="21" class="moduleListTitle" style="padding:0px 3px 0px 3px;">Description</td>
					  </tr>';
					  $rowcnt = 1;
					  $count_flag = 0;
					  do
					  {
					/*	if(isPermitted($report['primarymodule'],'index') == "yes" && (isPermitted($report['secondarymodules'],'index')== "yes" || $report['secondarymodules'] == ''))
						{*/
							$count_flag = 1;
							if ($rowcnt%2 == 0)
							$srptdetails .= '<tr class="evenListRow">';
							else
							$srptdetails .= '<tr class="oddListRow">';

							$srptdetails .= '<td height="21" style="padding:0px 3px 0px 3px;">
							<div align="center">';
							if($report["customizable"]==1)
							{
								$srptdetails .= '<a class="link" href="index.php?module=Reports&action=NewReport1&record='.$report["reportid"] .'&primarymodule='.$report["primarymodule"].'&secondarymodule='.$report["secondarymodules"].'">Customize</a>';
							}

							if($report["state"] !="SAVED")
							{
								$srptdetails .=  "&nbsp;<span class=\"sep\">|</span>&nbsp;<a class=\"link\" onclick=\"return window.confirm('Are you sure?');\" href=\"index.php?module=Reports&action=Delete&record=".$report["reportid"]."\">Del</a>";
							}
							$srptdetails .='</div>
							</td>
							<td  height="21" style="padding:0px 3px 0px 3px;" nowrap><a class="link" href="index.php?module=Reports&action=SaveAndRun&record='.$report["reportid"].'">'.$report["reportname"].'</a></td>
							<td  height="21" style="padding:0px 3px 0px 3px;">'.$report["description"].'</td>
							</tr>';
							$rowcnt++;
					//	}
					  }while($report = $adb->fetch_array($result));
				/*	  if($count_flag == 0)	
					  {
						$srptdetails .= "<tr><td colspan=3 align='center'>".$mod_strings['LBL_NO_PERMISSION']."</td></tr>";	
					   }
*/
				    	$srptdetails .= '</table>
				    		</td>
				  			</tr>
							</table>
							</div>';
		}else
		{
			$srptdetails .= '<div id="'.$rpt_fldr_id.'RptFldr" style="display:block">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
			    <td width="15"></td>
			    <td height="21">No reports in this folder</td>
			  </tr>
			</table>
			</div>';
		}
	
		$log->info("Reports :: ListView->Successfully returned report details HTML");
		return $srptdetails;
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

/** Function to set the Primary module fields for the given Report 
 *  This function sets the primary module columns for the given Report 
 *  It accepts the Primary module as the argument and set the fields of the module
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
 *  It accepts the module as the argument and set the fields of the module
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

/** Function to get fields for the given module and block
 *  This function gets the fields for the given module
 *  It accepts the module and the block as arguments and 
 *  returns the array column lists
 *  Array module_columnlist[ fieldtablename:fieldcolname:module_fieldlabel1:fieldname:fieldtypeofdata]=fieldlabel
 */

	function getColumnsListbyBlock($module,$block)
	{
        global $adb;
	global $log;
        global $profile_id;
	global $profileList;

        $tabid = getTabid($module);
	
	//Security Check 
	if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] ==0)
	{
		$sql = "select * from field inner join profile2field on profile2field.fieldid=field.fieldid inner join def_org_field on def_org_field.fieldid=field.fieldid where field.uitype != 50 and field.tabid=".$tabid." and field.block in (".$block .") and field.displaytype in (1,2) and profile2field.visible=0 and def_org_field.visible=0 and profile2field.profileid =  ".$profile_id." order by sequence";
	}
	else
	{
        	$sql = "select * from field inner join profile2field on profile2field.fieldid=field.fieldid inner join def_org_field on def_org_field.fieldid=field.fieldid where field.uitype != 50 and field.tabid=".$tabid." and field.block in (".$block .") and field.displaytype in (1,2) and profile2field.visible=0 and def_org_field.visible=0 and profile2field.profileid in ".$profileList." group by field.fieldid order by sequence";
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

            if($fieldtablename == "crmentity")
            {
   	        	$fieldtablename = $fieldtablename.$module;
            }
			if($fieldname == "assigned_user_id")
			{
			   $fieldtablename = "users".$module;
			   $fieldcolname = "user_name";
			}
			if($fieldname == "account_id")
			{
				$fieldtablename = "account".$module;
				$fieldcolname = "accountname";
			}
			if($fieldname == "contact_id")
			{
				$fieldtablename = "contactdetails".$module;
				$fieldcolname = "lastname";
			}
			if($fieldname == "parent_id")
			{
  				$fieldtablename = "crmentityRel".$module;
					$fieldcolname = "setype";
			}
			if($fieldname == "vendor_id")
	        {
               	$fieldtablename = "vendorRel";
				$fieldcolname = "vendorname";
            }
            if($fieldname == "potential_id")
            {
               	$fieldtablename = "potentialRel";
			    $fieldcolname = "potentialname";
            }
            if($fieldname == "assigned_user_id1")
            {
               	$fieldtablename = "usersRel1";
			    $fieldcolname = "user_name";
            }

            $fieldlabel = $adb->query_result($result,$i,"fieldlabel");
            $fieldlabel1 = str_replace(" ","_",$fieldlabel);
            $optionvalue = $fieldtablename.":".$fieldcolname.":".$module."_".$fieldlabel1.":".$fieldname.":".$fieldtypeofdata;
			$module_columnlist[$optionvalue] = $fieldlabel;
		}
        $log->info("Reports :: FieldColumns->Successfully returned ColumnslistbyBlock".$module.$block);
		return $module_columnlist;
	}
	
/** Function to set the standard filter fields for the given report
 *  This function gets the standard filter fields for the given report
 *  and set the values to the corresponding variables
 *  It accepts the repordid as argument 
 */

	function getSelectedStandardCriteria($reportid)
	{
		global $adb;
		$sSQL = "select reportdatefilter.* from reportdatefilter inner join report on report.reportid = reportdatefilter.datefilterid where report.reportid=".$reportid;

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
 *  This function get the combo values for the standard filter for the given report
 *  and return a HTML string 
 */

	function getSelectedStdFilterCriteria($selecteddatefilter = "")
	{
		    
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
				$sshtml .= "<option selected value='".$datefiltervalue[$i]."'>".$datefilterdisplay[$i]."</option>";
			}else
			{
				$sshtml .= "<option value='".$datefiltervalue[$i]."'>".$datefilterdisplay[$i]."</option>";
			}
		}
			
		return $sshtml;
	}

/** Function to get the selected standard filter columns 
 *  This function returns the selected standard filter criteria 
 *  which is selected for reports as an array
 *  Array stdcriteria_list[fieldtablename:fieldcolname:module_fieldlabel1]=fieldlabel
 */

	function getStdCriteriaByModule($module)
	{	
		global $adb;
		global $log;

		$tabid = getTabid($module);
		global $profile_id;
	
		foreach($this->module_list[$module] as $key=>$blockid)
		{
			$blockids[] = $blockid;
		}	
		$blockids = implode(",",$blockids);	

		$sql = "select * from field inner join tab on tab.tabid = field.tabid 
			inner join profile2field on profile2field.fieldid=field.fieldid 
			where field.tabid=".$tabid." and (field.uitype =5 or field.displaytype=2)  
			and profile2field.visible=0 and field.block in (".$blockids.") and profile2field.profileid=".$profile_id." order by field.sequence";
		
        $result = $adb->query($sql);

        while($criteriatyperow = $adb->fetch_array($result))
        {
			$fieldtablename = $criteriatyperow["tablename"];
            $fieldcolname = $criteriatyperow["columnname"];
            $fieldlabel = $criteriatyperow["fieldlabel"];

			if($fieldtablename == "crmentity")
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
 *  This function accepts the reportid as variable,sets the variable ascdescorder[] to the sort order and
 *  returns the array array_list which has the column responsible for the grouping
 *  Array array_list[0]=columnname
 */


	function getSelctedSortingColumns($reportid)
	{

		global $adb;
		global $log;

    	$sreportsortsql = "select reportsortcol.* from report";
		$sreportsortsql .= " inner join reportsortcol on report.reportid = reportsortcol.reportid";
		$sreportsortsql .= " where report.reportid =".$reportid." order by reportsortcol.sortcolid";

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
	
/** Function to get the selected columns list for a selected report
 *  This function accepts the reportid as the argument and get the selected columns
 *  for the given reportid and it forms a combo lists and returns
 *  HTML of the combo values
 */

	function getSelectedColumnsList($reportid)
	{

		global $adb;
	    global $modules;
		global $log;

		$ssql = "select selectcolumn.* from report inner join selectquery on selectquery.queryid = report.queryid";
		$ssql .= " left join selectcolumn on selectcolumn.queryid = selectquery.queryid where report.reportid =".$reportid;
		$ssql .= " order by selectcolumn.columnindex";

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

/** Function to Set the selected columns for the advanced filter for the report
 *  This function accepts the reportid as the argument and get the selected columns
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

		$ssql = "select relcriteria.* from report inner join selectquery on relcriteria.queryid = report.queryid";
		$ssql.= " left join relcriteria on relcriteria.queryid = selectquery.queryid";
		$ssql.= " where report.reportid =".$reportid." order by relcriteria.columnindex";

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
	
/** Function to get the list of report folders when Save and run  the report
 *  This function gets the report folders from database and form
 *  a combo values of the folders and return 
 *  HTML of the combo values
 */
	
	function sgetRptFldrSaveReport()
	{
		global $adb;
		global $log;

		$sql = "select * from reportfolder order by folderid";
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

/** Function to get the column to total fields in Reports 
 *  This function gets columns to total field 
 *  and generated the html for that fields
 *  It returns the HTML of the fields along with the check boxes
 */

	function sgetColumntoTotal($primarymodule,$secondarymodule)
	{
		$shtml = $this->sgetColumnstoTotalHTML($primarymodule,0);
		if($secondarymodule != "")
		{
			$secondarymodule = explode(":",$secondarymodule);
			for($i=0;$i < count($secondarymodule) ;$i++)
			{
				$shtml .= $this->sgetColumnstoTotalHTML($secondarymodule[$i],($i+1));
			}
		}
		
		return $shtml;
	}
	
/** Function to get the selected columns of total fields in Reports
 *  This function gets selected columns of total field
 *  and generated the html for that fields
 *  It returns the HTML of the fields along with the check boxes
 */
	
	
	function sgetColumntoTotalSelected($primarymodule,$secondarymodule,$reportid)
	{
		global $adb;
		global $log;

		if($reportid != "")
		{
			$ssql = "select reportsummary.* from reportsummary inner join report on report.reportid = reportsummary.reportsummaryid where report.reportid=".$reportid;
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
		$shtml = $this->sgetColumnstoTotalHTML($primarymodule,0);
		if($secondarymodule != "")
		{
			$secondarymodule = explode(":",$secondarymodule);
			for($i=0;$i < count($secondarymodule) ;$i++)
			{
				$shtml .= $this->sgetColumnstoTotalHTML($secondarymodule[$i],($i+1));
			}
		}
		
		$log->info("Reports :: Successfully returned sgetColumntoTotalSelected");
		return $shtml;
	}


/** Function to form the HTML for columns to total	
 *  This function formulates the HTML format of the
 *  fields along with four checkboxes
 *  It returns the HTML of the fields along with the check boxes
 */
	
	
	function sgetColumnstoTotalHTML($module)
	{
		//retreive the tabid	
		global $adb;
		global $log;
		global $profileList;

		$tabid = getTabid($module);
		global $profile_id;
		$escapedchars = Array('_SUM','_AVG','_MIN','_MAX');
		if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] ==0)
		{
			$ssql = "select * from field inner join tab on tab.tabid = field.tabid inner join def_org_field on def_org_field.fieldid=field.fieldid inner join profile2field on profile2field.fieldid=field.fieldid  where field.uitype != 50 and field.tabid=".$tabid." and field.displaytype = 1 and def_org_field.visible=0 and profile2field.visible=0 and profile2field.profileid=".$profile_id." order by sequence";
		}
		else
		{
			$ssql = "select * from field inner join tab on tab.tabid = field.tabid inner join def_org_field on def_org_field.fieldid=field.fieldid inner join profile2field on profile2field.fieldid=field.fieldid  where field.uitype != 50 and field.tabid=".$tabid." and field.displaytype = 1 and def_org_field.visible=0 and profile2field.visible=0 and profile2field.profileid in ".$profileList." order by sequence";
		}
		$result = $adb->query($ssql);
		$columntototalrow = $adb->fetch_array($result);
                $n = 0;
		do
		{
			$typeofdata = explode("~",$columntototalrow["typeofdata"]);

  		      if($typeofdata[0] == "N" || $typeofdata[0] == "I")
			{
				
				if(($n % 2) == 0)
				{
					$shtml .= '<tr class="evenListRow">';
				}else
				{
					$shtml .= '<tr class="oddListRow">';
				}

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
					$shtml .= '<td nowrap height="21" style="padding:0px 3px 0px 3px;">'.$columntototalrow['tablabel'].' - '.$columntototalrow['fieldlabel'].'</td>
					    <td height="21" style="padding:0px 3px 0px 3px;"><div align="center">';

						if($selectedcolumn1[2] == "cb:".$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel']."_SUM:2")
					    {
					    $shtml .=  '<input checked name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].'_SUM:2" type="checkbox" value="">';					    }else
					    {
						    $shtml .=  '<input name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].'_SUM:2" type="checkbox" value="">';
					    }
					    $shtml .=  '</div></td>
					    <td height="21" style="padding:0px 3px 0px 3px;"><div align="center">'; 
					    if($selectedcolumn1[3] == "cb:".$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel']."_AVG:3")
					    {
						   $shtml .=  '<input checked name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].'_AVG:3" type="checkbox" value="">';
					    }else
					    {
						    $shtml .=  '<input name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].'_AVG:3" type="checkbox" value="">';
					    }

					    $shtml .=  '</div></td>
					    <td height="21" style="padding:0px 3px 0px 3px;"><div align="center">';  
					    if($selectedcolumn1[4] == "cb:".$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel']."_MIN:4")
					    {
						   $shtml .=  '<input checked name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].'_MIN:4" type="checkbox" value="">';
					    }else
					    {
						    $shtml .=  '<input name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].'_MIN:4" type="checkbox" value="">';
					    }

					    $shtml .=  '</div></td>
					    <td height="21" style="padding:0px 3px 0px 3px;"><div align="center">'; 
					    if($selectedcolumn1[5] == "cb:".$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel']."_MAX:5")
					    {
						   $shtml .=  '<input checked name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].'_MAX:5" type="checkbox" value="">';
					    }else
					    {
						    $shtml .=  '<input name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].'_MAX:5" type="checkbox" value="">';
					    }
	
					    $shtml .=  '</div></td>
					</tr>';
					$n = $n + 1;
					
				}else
				{
					$shtml .= '<td nowrap height="21" style="padding:0px 3px 0px 3px;">'.$columntototalrow['tablabel'].' - '.$columntototalrow['fieldlabel'].'</td>
					    <td height="21" style="padding:0px 3px 0px 3px;"><div align="center"> 
						    <input name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].'_SUM:2" type="checkbox" value="">
					    </div></td>
					    <td height="21" style="padding:0px 3px 0px 3px;"><div align="center"> 
						     <input name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].'_AVG:3" type="checkbox" value="" >
					    </div></td>
					    <td height="21" style="padding:0px 3px 0px 3px;"><div align="center">  
						    <input name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].'_MIN:4"type="checkbox" value="" >
					    </div></td>
					    <td height="21" style="padding:0px 3px 0px 3px;"><div align="center"> 
						    <input name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].'_MAX:5" type="checkbox" value="" >	
					    </div></td>
					</tr>';
					$n = $n + 1;
				}
			}
		}while($columntototalrow = $adb->fetch_array($result));
		
		$log->info("Reports :: Successfully returned sgetColumnstoTotalHTML");
		return $shtml;
	}
}
?>
