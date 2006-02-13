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
global $calpath;
global $app_strings,$mod_strings;
global $app_list_strings;
global $modules;
global $blocks;
global $adv_filter_options;
global $vtlog;

global $report_modules;
global $related_modules;

//$modules = array("Leads_", "Accounts_", "Potentials_", "Contacts_", "Products_", "_");

$adv_filter_options = array("e"=>"equals",
			    "n"=>"not equal to",
			    "s"=>"starts with",
			    "c"=>"contains",
			    "k"=>"does not contain",
			    "l"=>"less than",
			    "g"=>"greater than",
			    "m"=>"less or equal",
			    "h"=>"greater or equal");

$report_modules = Array('Leads','Accounts','Contacts','Potentials','Products','HelpDesk','Quotes','Orders','Invoice','Activities');

$related_modules = Array('Leads'=>Array(''),
			 'Accounts'=>Array('Potentials','Contacts','Products','Quotes','Invoice'),
			 'Contacts'=>Array('Accounts','Potentials','Quotes','Orders'),
			 'Potentials'=>Array('Accounts','Contacts','Quotes'),
			 'Activities'=>Array('Contacts'),
			 'Products'=>Array('Accounts','Contacts'),
			 'HelpDesk'=>Array('Products'),
			 'Quotes'=>Array('Accounts','Contacts','Potentials'),
			 'Orders'=>Array('Contacts'),
			 'Invoice'=>Array('Accounts')
			);

foreach($report_modules as $values)
{
	$modules[] = $values."_";
}
$modules[] = "_";

class Reports extends CRMEntity{

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

	var $module_list = Array("Leads"=>Array("Information"=>1,"Address"=>2,"Description"=>3,"Custom Information"=>5),
				 "Contacts"=>Array("Information"=>1,"Address"=>2,"Description"=>3,"Custom Information"=>5),
				 "Accounts"=>Array("Information"=>1,"Address"=>2,"Description"=>3,"Custom Information"=>5),
				 "Potentials"=>Array("Information"=>1,"Description"=>2,"Custom Information"=>5),
				 "Activities"=>Array("Information"=>1,"Description"=>2),
				 "Products"=>Array("Information"=>1,"Description"=>2,"Custom Information"=>5),
				 "Notes"=>Array("Information"=>1,"Description"=>3),
				 "Emails"=>Array("Information"=>1,"Description"=>2),
				 "HelpDesk"=>Array("Information"=>'1,2',"Custom Information"=>5,"Description"=>3,"Solution"=>4),//patch2
				 "Quotes"=>Array("Information"=>1,"Address"=>2,"Description"=>3,"Custom Information"=>5),
				 "Orders"=>Array("Information"=>1,"Address"=>2,"Description"=>3,"Custom Information"=>5),
				 "Invoice"=>Array("Information"=>1,"Address"=>2,"Description"=>3,"Custom Information"=>5)
				);

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

	function save($module_name)
	{
		if($module_name = "ReportFolder")
		{
			if($this->mode = "Save")
			{
				$this->id = "";

			}elseif($this->mode = "Edit")
			{

			}
		}
	}
	function sgetRptFldr(){

		global $adb;
		global $vtlog;

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
				<a class=\"relListHead\" href=\"javascript:toggleReports('".$reporttempid."')\">".$reportfldrow["foldername"]."</a>
			   ";
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

		$vtlog->logthis("Reports :: ListView->Successfully returned report folder HTML","info");
		return $shtml;
	}

	function sgetRptsforFldr($rpt_fldr_id)
	{
		$srptdetails="";
		global $adb;
		global $vtlog;
		
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
						do
						{
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

								if($report["state"]=="SAVED")
								{
								//	$srptdetails .= '<span class="sep">|</span>&nbsp;<span class="disabled">Del</span>';
								}else
								{
									$srptdetails .=  "&nbsp;<span class=\"sep\">|</span>&nbsp;<a class=\"link\" onclick=\"return window.confirm('Are you sure?');\" href=\"index.php?module=Reports&action=Delete&record=".$report["reportid"]."\">Del</a>";
								}
								$srptdetails .='</div>
								</td>
								<td  height="21" style="padding:0px 3px 0px 3px;" nowrap><a class="link" href="index.php?module=Reports&action=SaveAndRun&record='.$report["reportid"].'">'.$report["reportname"].'</a></td>
								<td  height="21" style="padding:0px 3px 0px 3px;">'.$report["description"].'</td>
								</tr>
							';
							$rowcnt++;
						}while($report = $adb->fetch_array($result));
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

		$vtlog->logthis("Reports :: ListView->Successfully returned report details HTML","info");
		return $srptdetails;
	}

	function sgetJsRptFldr()
	{
		$srptfldr_js = "var ReportListArray=new Array(".$this->srptfldridjs.")
		setExpandCollapse()";
		return $srptfldr_js;
	}

	function getPriModuleColumnsList($module)
	{
		foreach($this->module_list[$module] as $key=>$value)
		{
			$ret_module_list[$module][$key] = $this->getColumnsListbyBlock($module,$value);
		}
		$this->pri_module_columnslist = $ret_module_list;
		return true;
	}

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

	/*function getEscapedFieldNames($fieldname)
        {
                //print($selectedfields);
                //print_r($selectedfields);
                //$fieldname = $selectedfields[3];
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
        }*/

	function getColumnsListbyBlock($module,$block)
	{
                global $adb;
		global $vtlog;

                $tabid = getTabid($module);
                global $profile_id;

                $sql = "select * from field inner join profile2field on profile2field.fieldid=field.fieldid  where field.uitype != 50 and field.tabid=".$tabid." and field.block in (".$block .") and field.displaytype in (1,2) and profile2field.visible=0 and profile2field.profileid=".$profile_id." order by sequence";

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
			if($fieldname == "vendor_id")
	                {
                        	$fieldtablename = "vendorRel";
				$fieldcolname = "vendorname";
//				$querycolumn = "vendorRel.name"." ".$selectedfields[2];
                	}
                	if($fieldname == "potential_id")
                	{
                        	$fieldtablename = "potentialRel";
			        $fieldcolname = "potentialname";
//				$querycolumn = "potentialRel.potentialname"." ".$selectedfields[2];
                	}
                	if($fieldname == "assigned_user_id1")
                	{
                        	$fieldtablename = "usersRel1";
			        $fieldcolname = "user_name";
//				$querycolumn = "usersRel1.user_name"." ".$selectedfields[2];
                	}

                        $fieldlabel = $adb->query_result($result,$i,"fieldlabel");
                        $fieldlabel1 = str_replace(" ","_",$fieldlabel);
                        $optionvalue = $fieldtablename.":".$fieldcolname.":".$module."_".$fieldlabel1.":".$fieldname.":".$fieldtypeofdata;
			$module_columnlist[$optionvalue] = $fieldlabel;
		}
		$vtlog->logthis("Reports :: FieldColumns->Successfully returned ColumnslistbyBlock".$module.$block,"info");
                return $module_columnlist;
	}
	function getReportBlockInformation($module,$block,$block_name,$selected="")
	{
		//retreive the tabid
		global $adb;
		$tabid = getTabid($module);
		global $profile_id;
		//echo $selected;
		$sql = "select * from field inner join profile2field on profile2field.fieldid=field.fieldid  where field.uitype != 50 and field.tabid=".$tabid." and field.block in (".$block .") and field.displaytype in (1,2) and profile2field.visible=0 and profile2field.profileid=".$profile_id." order by sequence";

		//putan if condition and reuse the code for sorting
		$shtml = "<optgroup label=\"".$block_name."\" class=\"select\" style=\"border:none\">";

		$result = $adb->query($sql);
		$noofrows = $adb->num_rows($result);
		for($i=0; $i<$noofrows; $i++)
		{
			$fieldtablename = $adb->query_result($result,$i,"tablename");
			$fieldcolname = $adb->query_result($result,$i,"columnname");
			//if($this->mcount != 0)
			//{
			if($fieldtablename == "crmentity")
			{
			   $fieldtablename = $fieldtablename.$module;
			}
			//}
			$fieldlabel = $adb->query_result($result,$i,"fieldlabel");
			$fieldlabel1 = str_replace(" ","_",$fieldlabel);
			$optionvalue = $fieldtablename.":".$fieldcolname.":".$module."_".$fieldlabel1;
			if($selected == $optionvalue)
			{
				$soptionhtml .= "<option selected value=\"".$optionvalue."\">".$fieldlabel."</option>";
			}else
			{
				$soptionhtml .= "<option value=\"".$optionvalue."\">".$fieldlabel."</option>";
			}
			$this->module_blocks[$block][$optionvalue] = $fieldlabel;
		}
		if($soptionhtml!="")
		{
			$shtml = $shtml.$soptionhtml;
		}else
		{
			$shtml="";
		}
		return $shtml;
		//return $this->modules_block;
	}

	function getAvailableColumnsforQs($primarymodule,$secondarymodule)
	{

		$this->mcount = 0;
		$html = $this->getColumnsforReportModule($primarymodule);
		$secondarymodule = explode(":",$secondarymodule);

		for($i=0;$i < count($secondarymodule) ;$i++)
		{
			$this->mcount = $this->mcount + 1;
			$html .= $this->getColumnsforReportModule($secondarymodule[$i]);
		}

		return $html;
	}

	function getColumnsforReportModule($module,$selectedvalue="")
	{
		global $app_list_strings;
		//global $adb;

		//$sSQL = "select * from tab where tabname='".$module."'";
		//$result = $adb->query($sSQL);
		//$tabrow = $adb->fetch_array($result);

		//if($result)
		//{

		//}
		//if($module == "Leads")
		//{
		 foreach($app_list_strings['moduleList'] as $key=>$value)
		 {
			if($module == $key)
			{
			$shtml .= $this->getReportBlockInformation($module,1,$value.' Information',$selectedvalue);
			$shtml .= $this->getReportBlockInformation($module,2,$value.' Address Information',$selectedvalue);
			$shtml .= $this->getReportBlockInformation($module,3,$value.' Description Information',$selectedvalue);
			$shtml .= $this->getReportBlockInformation($module,5,$value.' Custom Information',$selectedvalue);
			}
		}
		//}
		/*if($module == "Accounts")
		{
			$shtml .= $this->getReportBlockInformation('Accounts',1,'Account Information',$selectedvalue);
			$shtml .= $this->getReportBlockInformation('Accounts',2,'Account Address Information',$selectedvalue);
			$shtml .= $this->getReportBlockInformation('Accounts',3,'Account Description Information',$selectedvalue);
			$shtml .= $this->getReportBlockInformation('Accounts',5,'Account Custom Information',$selectedvalue);
		}
		if($module == "Contacts")
		{
			$shtml .= $this->getReportBlockInformation('Contacts',1,'Contact Information',$selectedvalue);
			$shtml .= $this->getReportBlockInformation('Contacts',2,'Contacts Address Information',$selectedvalue);
			$shtml .= $this->getReportBlockInformation('Contacts',3,'Contacts Description Information',$selectedvalue);
			$shtml .= $this->getReportBlockInformation('Contacts',5,'Contacts Custom Information',$selectedvalue);
		}
		if($module == "Potentials")
		{
			$shtml .= $this->getReportBlockInformation('Potentials',1,'Potential Information',$selectedvalue);
			$shtml .= $this->getReportBlockInformation('Potentials',2,'Potentials Description Information',$selectedvalue);
			$shtml .= $this->getReportBlockInformation('Potentials',5,'Potentials Custom Information',$selectedvalue);
		}*/
		return $shtml;
	}

	function getSortingColumnDetails($reportId)
	{
		$ssql = "select reportsortcol.* from report left join reportsortcol on report.reportid=reportsortcol.reportid where report.reportid=".$reportId;
	}

	function getSelectedReportType($reportid)
	{
		global $adb;

		$ssql = "select report.reporttype from report where reportid=".$reportid;
		$result = $adb->query($ssql);
		$reporttyperow = $adb->fetch_array($result);
		return $reporttyperow["reporttype"];
	}

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
		//$shtml = $this->getStandardCriteria($primarymodule,$secondarymodule,$selectedcolumn);
		//return $shtml;
	}
	
	function getSelectedStdFilterCriteria($selecteddatefilter = "")
	{
		    
		$datefiltervalue = Array("custom","prevfy","thisfy","nextfy","prevfq","thisfq","nextfq",
                                                 "yesterday","today","tomorrow","lastweek","thisweek","nextweek","lastmonth","thismonth",
		                                 "nextmonth","last7days","last30days", "last60days","last90days","last120days",
		                                 "next30days","next60days","next90days","next120days");
		
		$datefilterdisplay = Array("Custom","Previous FY", "Current FY","Next FY","Previous FQ","Current FQ","Next FQ","Yesterday",
                                                     "Today","Tomorrow","Last Week","Current Week","Next Week","Last Month","Current Month",
                                                     "Next Month","Last 7 Days","Last 30 Days","Last 60 Days","Last 90 Days","Last 120 Days",        
                                                     "Next 7 Days","Next 30 Days","Next 60 Days","Next 90 Days","Next 120 Days");
						     
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

	function getStdCriteriaByModule($module)
	{	
		global $adb;
		global $vtlog;

		$tabid = getTabid($module);
		global $profile_id;
	
		foreach($this->module_list[$module] as $key=>$blockid)
		{
			$blockids[] = $blockid;
		}	
		$blockids = implode(",",$blockids);	

		//print_r($blockids);
		$sql = "select * from field inner join tab on tab.tabid = field.tabid 
			inner join profile2field on profile2field.fieldid=field.fieldid 
			where field.tabid=".$tabid." and (field.uitype =5 or field.displaytype=2)  
			and profile2field.visible=0 and field.block in (".$blockids.") and profile2field.profileid=".$profile_id." order by field.sequence";
		
		//echo $sql;
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
		
		$vtlog->logthis("Reports :: StdfilterColumns->Successfully returned Stdfilter for".$module,"info");
		return $stdcriteria_list;
		
	}
	
	function sgetStandardCriteriaHTML($tabid,$count,$selectedvalue="")
	{
		global $adb;
		global $vtlog;

		global $profile_id;
		
		$sql = "select * from field inner join tab on tab.tabid = field.tabid inner join profile2field on profile2field.fieldid=field.fieldid  where field.tabid=".$tabid." and (field.uitype =5 or field.displaytype=2) and profile2field.visible=0 and profile2field.profileid=".$profile_id." order by field.sequence";
		$result = $adb->query($sql);
		$criteriatyperow = $adb->fetch_array($result);

		do
		{
			$fieldtablename = $criteriatyperow["tablename"];	
			$fieldcolname = $criteriatyperow["columnname"];	
			$fieldlabel = $criteriatyperow["fieldlabel"];
			$tablabel = $criteriatyperow["tablabel"];
			if($count != 0)
			{
				if($fieldtablename == "crmentity")
				{
					$optionvalue = $tablabel.":".$fieldtablename.$count.":".$fieldcolname.":".$fieldlabel;
				}else
				{
					$optionvalue = $tablabel.":".$fieldtablename.":".$fieldcolname.":".$fieldlabel;
				}
			}else
			{
				$optionvalue = $tablabel.":".$fieldtablename.":".$fieldcolname.":".$fieldlabel;
			}
			if($selectedvalue == $optionvalue)
			{
				$shtml .= "<option selected value=\"".$optionvalue."\">".$criteriatyperow["tablabel"]." - ".$fieldlabel."</option>";
			}
			else
			{
				$shtml .= "<option value=\"".$optionvalue."\">".$criteriatyperow["tablabel"]." - ".$fieldlabel."</option>";
			}
		
		}while($criteriatyperow = $adb->fetch_array($result));
		
		$vtlog->logthis("Reports :: StdfilterColumns->Successfully returned Stdfilter HTML for".$tabid,"info");
		return $shtml;
	}
	
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
		
//		$currentFQ0 = date("Y-m-d",mktime(0,0,0,(date("m")*3)/3,"01",date("Y")));
//		$currentFQ1 = "";
//		$previousFQ0 = "";
//		$previousFQ1 = "";
//		$nextFQ0 = "";
//                $nextFQ1 = "";

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
	
	function getSelctedSortingColumns($reportid)
	{

		global $adb;
		global $vtlog;

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
		
		$vtlog->logthis("Reports :: Successfully returned getSelctedSortingColumns","info");
		return $array_list;
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

		for($i=0; $i<$noofrows; $i++)
		{
			$fieldcolname = $adb->query_result($result,$i,"columnname");
			$fieldlist = explode(":",$fieldcolname);
			if($fieldcolname != "")
			{
				$shtml .= "<option value=\"".$fieldcolname."\">".str_replace($modules," ",$fieldlist[2])."</option>";
			}
		}

		$vtlog->logthis("Reports :: Successfully returned getSelectedColumnsList","info");
		return $shtml;
	}

	//<<<<<<<<advanced filter>>>>>>>>>>>>>>
	function getAdvancedFilterList($reportid)
	{
		global $adb;
		global $modules;
		global $vtlog;

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

		$vtlog->logthis("Reports :: Successfully returned getAdvancedFilterList","info");
		return true;
	}
	//<<<<<<<<advanced filter>>>>>>>>>>>>>>
	
	function sgetRptFldrSaveReport()
	{
		global $adb;
		global $vtlog;

		$sql = "select * from reportfolder order by folderid";
		$result = $adb->query($sql);
		$reportfldrow = $adb->fetch_array($result);
		$x = 0;
                do
		{
			$shtml .= "<option value='".$reportfldrow['folderid']."'>".$reportfldrow['foldername']."</option>";

		}while($reportfldrow = $adb->fetch_array($result));
		
		$vtlog->logthis("Reports :: Successfully returned sgetRptFldrSaveReport","info");
		return $shtml;
	}
	
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
	
	function sgetColumntoTotalSelected($primarymodule,$secondarymodule,$reportid)
	{
		global $adb;
		global $vtlog;

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
//		print_r($this->columnssummary);		
		$shtml = $this->sgetColumnstoTotalHTML($primarymodule,0);
		if($secondarymodule != "")
		{
			$secondarymodule = explode(":",$secondarymodule);
			for($i=0;$i < count($secondarymodule) ;$i++)
			{
				$shtml .= $this->sgetColumnstoTotalHTML($secondarymodule[$i],($i+1));
			}
		}
		
		$vtlog->logthis("Reports :: Successfully returned sgetColumntoTotalSelected","info");
		return $shtml;
	}

	function getColumnstoTotalByModule($module)
	{
	}
	function sgetColumnstoTotalHTML($module)
	{
		//retreive the tabid	
		global $adb;
		global $vtlog;

		$tabid = getTabid($module);
		global $profile_id;
		
		$ssql = "select * from field inner join tab on tab.tabid = field.tabid inner join profile2field on profile2field.fieldid=field.fieldid  where field.uitype != 50 and field.tabid=".$tabid." and field.displaytype = 1 and profile2field.visible=0 and profile2field.profileid=".$profile_id." order by sequence";
		
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
						$selectedcolumn = $selectedcolumnarray[1].":".$selectedcolumnarray[2].":".$selectedcolumnarray[3];
					
						if ($selectedcolumn != $columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.str_replace(" ","_",$columntototalrow['fieldlabel']))
						{
							$selectedcolumn = "";
						}else
						{
							$selectedcolumn1[$selectedcolumnarray[4]] = $this->columnssummary[$i];
						}
						
      				          }
					  //print_r($selectedcolumn1);
					  //print_r("cb:".$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].":2");
$columntototalrow['fieldlabel'] = str_replace(" ","_",$columntototalrow['fieldlabel']);
					$shtml .= '<td nowrap height="21" style="padding:0px 3px 0px 3px;">'.$columntototalrow['tablabel'].' - '.$columntototalrow['fieldlabel'].'</td>
					    <td height="21" style="padding:0px 3px 0px 3px;"><div align="center">';
					    if($selectedcolumn1[2] == "cb:".$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].":2")
					    {
						//echo "here";   
					    $shtml .=  '<input checked name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].':2" type="checkbox" value="">';					    }else
					    {
						    $shtml .=  '<input name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].':2" type="checkbox" value="">';
					    }
					    $shtml .=  '</div></td>
					    <td height="21" style="padding:0px 3px 0px 3px;"><div align="center">'; 
					    if($selectedcolumn1[3] == "cb:".$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].":3")
					    {
						   $shtml .=  '<input checked name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].':3" type="checkbox" value="">';
					    }else
					    {
						    $shtml .=  '<input name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].':3" type="checkbox" value="">';
					    }

					    $shtml .=  '</div></td>
					    <td height="21" style="padding:0px 3px 0px 3px;"><div align="center">';  
					    if($selectedcolumn1[4] == "cb:".$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].":4")
					    {
						   $shtml .=  '<input checked name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].':4" type="checkbox" value="">';
					    }else
					    {
						    $shtml .=  '<input name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].':4" type="checkbox" value="">';
					    }

					    $shtml .=  '</div></td>
					    <td height="21" style="padding:0px 3px 0px 3px;"><div align="center">'; 
					    if($selectedcolumn1[5] == "cb:".$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].":5")
					    {
						   $shtml .=  '<input checked name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].':5" type="checkbox" value="">';
					    }else
					    {
						    $shtml .=  '<input name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].':5" type="checkbox" value="">';
					    }
	
					    $shtml .=  '</div></td>
					</tr>';
					$n = $n + 1;
					
				}else
				{
					$shtml .= '<td nowrap height="21" style="padding:0px 3px 0px 3px;">'.$columntototalrow['tablabel'].' - '.$columntototalrow['fieldlabel'].'</td>
					    <td height="21" style="padding:0px 3px 0px 3px;"><div align="center"> 
						    <input name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].':2" type="checkbox" value="">
					    </div></td>
					    <td height="21" style="padding:0px 3px 0px 3px;"><div align="center"> 
						     <input name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].':3" type="checkbox" value="" >
					    </div></td>
					    <td height="21" style="padding:0px 3px 0px 3px;"><div align="center">  
						    <input name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].':4"type="checkbox" value="" >
					    </div></td>
					    <td height="21" style="padding:0px 3px 0px 3px;"><div align="center"> 
						    <input name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].':5" type="checkbox" value="" >	
					    </div></td>
					</tr>';
					$n = $n + 1;
				}
			}
		}while($columntototalrow = $adb->fetch_array($result));
		
		$vtlog->logthis("Reports :: Successfully returned sgetColumnstoTotalHTML","info");
		return $shtml;
	}
}
?>
