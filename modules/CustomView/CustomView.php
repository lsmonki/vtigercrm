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
global $app_list_strings;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once('include/database/PearDatabase.php');
require_once ($theme_path."layout_utils.php");
require_once('data/CRMEntity.php');
require_once('include/utils/utils.php');

global $adv_filter_options;

$adv_filter_options = array("e"=>"".$mod_strings['equals']."",
                            "n"=>"".$mod_strings['not_equal_to']."",
                            "s"=>"".$mod_strings['starts_with']."",
                            "c"=>"".$mod_strings['contains']."",
                            "k"=>"".$mod_strings['does_not_contain']."",
                            "l"=>"".$mod_strings['less_than']."",
                            "g"=>"".$mod_strings['greater_than']."",
                            "m"=>"".$mod_strings['less_or_equal']."",
                            "h"=>"".$mod_strings['greater_or_equal']."",
                            );

class CustomView extends CRMEntity{



	var $module_list = Array("Leads"=>Array("Information"=>13,"Address"=>15,"Description"=>16,"Custom Information"=>14),
				 "Contacts"=>Array("Information"=>4,"Address"=>7,"Description"=>8,"Custom Information"=>5),
				 "Accounts"=>Array("Information"=>9,"Address"=>11,"Description"=>12,"Custom Information"=>10),
				 "Potentials"=>Array("Information"=>1,"Description"=>3,"Custom Information"=>2),
				 "Calendar"=>Array("Information"=>19,"Description"=>20),
 		                 "Campaigns"=>Array("Information"=>76,"Expectations"=>78,"Description"=>82,"Custom Information"=>77),
				 "Products"=>Array("Information"=>31,"Description"=>36,"Pricing Information"=>32,"Stock Information"=>33,"Custom Information"=>34),
				 "Vendors"=>Array("Information"=>44,"Address"=>46,"Description"=>47,"Custom Information"=>45),
				 "PriceBooks"=>Array("Information"=>48,"Description"=>50,"Custom Information"=>49),
				 "Notes"=>Array("Information"=>17,"Description"=>18),
				 "Emails"=>Array("Information"=>'21,22,23',"Description"=>24),
				 "HelpDesk"=>Array("Information"=>'25,26',"Description"=>28,"Custom Information"=>27,"Solution"=>29),
				 "Quotes"=>Array("Information"=>51,"Address"=>53,"Description"=>56,"Terms and Conditions"=>55,"Custom Information"=>52),
				 "PurchaseOrder"=>Array("Information"=>57,"Address"=>59,"Description"=>62,"Terms and Conditions"=>61,"Custom Information"=>58),
				 "SalesOrder"=>Array("Information"=>63,"Address"=>65,"Description"=>68,"Terms and Conditions"=>67,"Custom Information"=>64),
				 "Faq"=>Array("Information"=>'37,38,39'),
				 "Invoice"=>Array("Information"=>69,"Address"=>71,"Description"=>74,"Terms and Conditions"=>73,"Custom Information"=>70)
				);


	var $customviewmodule;

	var $list_fields;

	var $list_fields_name;

	var $setdefaultviewid;

	var $escapemodule;

	var $mandatoryvalues;
	
	var $showvalues;
	
	/** This function sets the currentuser id to the class variable smownerid,  
	  * modulename to the class variable customviewmodule
	  * @param $module -- The module Name:: Type String(optional)
	  * @returns  nothing 
	 */
	function CustomView($module="")
	{
		global $current_user,$adb;
		$this->customviewmodule = $module;
		$this->escapemodule[] =	$module."_";
		$this->escapemodule[] = "_";
		$this->smownerid = $current_user->id;
	}


	/** To get the customViewId of the specified module 
	  * @param $module -- The module Name:: Type String
	  * @returns  customViewId :: Type Integer 
	 */	
	function getViewId($module)
	{
		global $adb;
		if(isset($_REQUEST['viewname']) == false)
		{
			if (isset($_SESSION['lvs'][$module]["viewname"]) && $_SESSION['lvs'][$module]["viewname"]!='')
			{
				$viewid = $_SESSION['lvs'][$module]["viewname"];
			}
			elseif($this->setdefaultviewid != "")
			{
				$viewid = $this->setdefaultviewid;
			}else
			{
				$query="select cvid from vtiger_customview where setdefault=1 and entitytype='".$module."'";
				$cvresult=$adb->query($query);
				if($adb->num_rows($cvresult) == 0)
				{
					$query="select cvid from vtiger_customview where viewname='All' and entitytype='".$module."'";
					$cvresult=$adb->query($query);
				}
				$viewid = $adb->query_result($cvresult,0,'cvid');;
			}
		}
		else
		{
			$viewid =  $_REQUEST['viewname'];
		}
		$_SESSION['lvs'][$module]["viewname"] = $viewid;
		return $viewid;

	}
	
	// return type array
	/** to get the details of a customview
	  * @param $cvid :: Type Integer
	  * @returns  $customviewlist Array in the following format
	  * $customviewlist = Array('viewname'=>value,
	  *                         'setdefault'=>defaultchk,
	  *                         'setmetrics'=>setmetricschk)   	    
	 */	

	function getCustomViewByCvid($cvid)
	{
		global $adb;
		$tabid = getTabid($this->customviewmodule);
		$ssql = "select vtiger_customview.* from vtiger_customview inner join vtiger_tab on vtiger_tab.name = vtiger_customview.entitytype";
		$ssql .= " where vtiger_customview.cvid=".$cvid;		

		$result = $adb->query($ssql);

		while($cvrow=$adb->fetch_array($result))
		{
			$customviewlist["viewname"] = $cvrow["viewname"];
			$customviewlist["setdefault"] = $cvrow["setdefault"];
			$customviewlist["setmetrics"] = $cvrow["setmetrics"];
		}
		return $customviewlist;		
	}	

	/** to get the customviewCombo for the class variable customviewmodule
	  * @param $viewid :: Type Integer
	  * $viewid will make the corresponding selected
	  * @returns  $customviewCombo :: Type String 
	 */	

	function getCustomViewCombo($viewid='')
	{
		global $adb;
		global $app_strings;
		$tabid = getTabid($this->customviewmodule);
		$ssql = "select vtiger_customview.* from vtiger_customview inner join vtiger_tab on vtiger_tab.name = vtiger_customview.entitytype";
		$ssql .= " where vtiger_tab.tabid=".$tabid;
		$result = $adb->query($ssql);
		while($cvrow=$adb->fetch_array($result))
		{
			if($cvrow['viewname'] == 'All')
			{
				$cvrow['viewname'] = $app_strings['COMBO_ALL'];
			}
			
			if($cvrow['setdefault'] == 1 && $viewid =='')
                        {
	                         $shtml .= "<option selected value=\"".$cvrow['cvid']."\">".$cvrow['viewname']."</option>";
		                 $this->setdefaultviewid = $cvrow['cvid'];
			}			
			elseif($cvrow['cvid'] == $viewid)
			{
				$shtml .= "<option selected value=\"".$cvrow['cvid']."\">".$cvrow['viewname']."</option>";
				$this->setdefaultviewid = $cvrow['cvid'];
			}
			else
			{
				$shtml .= "<option value=\"".$cvrow['cvid']."\">".$cvrow['viewname']."</option>";
			}
		}
		return $shtml;
	}

	/** to get the getColumnsListbyBlock for the given module and Block 
	  * @param $module :: Type String 
	  * @param $block :: Type Integer
	  * @returns  $columnlist Array in the format 
	  * $columnlist = Array ($fieldlabel =>'$fieldtablename:$fieldcolname:$fieldname:$module_$fieldlabel1:$fieldtypeofdata',
	                         $fieldlabel1 =>'$fieldtablename1:$fieldcolname1:$fieldname1:$module_$fieldlabel11:$fieldtypeofdata1',
					|
			         $fieldlabeln =>'$fieldtablenamen:$fieldcolnamen:$fieldnamen:$module_$fieldlabel1n:$fieldtypeofdatan')
	 */	

	function getColumnsListbyBlock($module,$block)
	{
		global $adb;
		$tabid = getTabid($module);
		global $current_user;
	        require('user_privileges/user_privileges_'.$current_user->id.'.php');

		if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0)
		{
			$sql = "select * from vtiger_field ";
			$sql.= " where vtiger_field.tabid=".$tabid." and vtiger_field.block in (".$block.") and";
			$sql.= " vtiger_field.displaytype in (1,2)";
			$sql.= " order by sequence";
		}
		else
		{
			$profileList = getCurrentUserProfileList();
			$sql = "select * from vtiger_field inner join vtiger_profile2field on vtiger_profile2field.fieldid=vtiger_field.fieldid inner join vtiger_def_org_field on vtiger_def_org_field.fieldid=vtiger_field.fieldid ";
			$sql.= " where vtiger_field.tabid=".$tabid." and vtiger_field.block in (".$block.") and";
			$sql.= " vtiger_field.displaytype in (1,2) and vtiger_profile2field.visible=0";
			$sql.= " and vtiger_def_org_field.visible=0  and vtiger_profile2field.profileid in ".$profileList." order by sequence";
		}	



		$result = $adb->query($sql);
		$noofrows = $adb->num_rows($result);
		//Added on 14-10-2005 -- added ticket id in list
		if($module == 'HelpDesk' && $block == 25)
		{
			$module_columnlist['vtiger_crmentity:crmid::HelpDesk_Ticket_ID:I'] = 'Ticket ID';
		}
		//Added to include vtiger_activity type in vtiger_activity vtiger_customview list
		if($module == 'Calendar' && $block == 19)
		{
			$module_columnlist['vtiger_activity:activitytype:activitytype:Calendar_Activity_Type:C'] = 'Activity Type';
		}

		for($i=0; $i<$noofrows; $i++)
		{
			$fieldtablename = $adb->query_result($result,$i,"tablename");
			$fieldcolname = $adb->query_result($result,$i,"columnname");
			$fieldname = $adb->query_result($result,$i,"fieldname");
			$fieldtype = $adb->query_result($result,$i,"typeofdata");
			$fieldtype = explode("~",$fieldtype);
			$fieldtypeofdata = $fieldtype[0];
			$fieldlabel = $adb->query_result($result,$i,"fieldlabel");
			if($fieldlabel == "Related To")
			{
				$fieldlabel = "Related to";
			}
			if($fieldlabel == "Start Date & Time")
			{
				$fieldlabel = "Start Date";
				if($module == 'Calendar' && $block == 19)
					$module_columnlist['vtiger_activity:time_start::Calendar_Start_Time:I'] = 'Start Time';

			}
			$fieldlabel1 = str_replace(" ","_",$fieldlabel);
			$optionvalue = $fieldtablename.":".$fieldcolname.":".$fieldname.":".$module."_".$fieldlabel1.":".$fieldtypeofdata;
			//added to escape attachments fields in customview as we have multiple attachments
			if($module != 'HelpDesk' || $fieldname !='filename')
				$module_columnlist[$optionvalue] = $fieldlabel;
			if($fieldtype[1] == "M")
			{
				$this->mandatoryvalues[] = "'".$optionvalue."'";
				$this->showvalues[] = $fieldlabel;
			}
		}
		return $module_columnlist;
	}

	/** to get the getModuleColumnsList for the given module 
	  * @param $module :: Type String
	  * @returns  $ret_module_list Array in the following format
	  * $ret_module_list = 
		Array ('module' =>
				Array('BlockLabel1' => 
						Array('$fieldtablename:$fieldcolname:$fieldname:$module_$fieldlabel1:$fieldtypeofdata'=>$fieldlabel,
	                                        Array('$fieldtablename1:$fieldcolname1:$fieldname1:$module_$fieldlabel11:$fieldtypeofdata1'=>$fieldlabel1,
				Array('BlockLabel2' => 
						Array('$fieldtablename:$fieldcolname:$fieldname:$module_$fieldlabel1:$fieldtypeofdata'=>$fieldlabel,
	                                        Array('$fieldtablename1:$fieldcolname1:$fieldname1:$module_$fieldlabel11:$fieldtypeofdata1'=>$fieldlabel1,
					 |
				Array('BlockLabeln' => 
						Array('$fieldtablename:$fieldcolname:$fieldname:$module_$fieldlabel1:$fieldtypeofdata'=>$fieldlabel,
	                                        Array('$fieldtablename1:$fieldcolname1:$fieldname1:$module_$fieldlabel11:$fieldtypeofdata1'=>$fieldlabel1,
	 

	 */	


	function getModuleColumnsList($module)
	{
		foreach($this->module_list[$module] as $key=>$value)
		{
			$columnlist = $this->getColumnsListbyBlock($module,$value);
			if(isset($columnlist))
			{
				$ret_module_list[$module][$key] = $columnlist;
			}
		}
		return $ret_module_list;
	}

	/** to get the getModuleColumnsList for the given customview 
	  * @param $cvid :: Type Integer
	  * @returns  $columnlist Array in the following format
	  * $columnlist = Array( $columnindex => $columnname,
	  *			 $columnindex1 => $columnname1,  
	  *					|
	  *			 $columnindexn => $columnnamen)  
	  */	
	function getColumnsListByCvid($cvid)
	{
		global $adb;
		
		$sSQL = "select vtiger_cvcolumnlist.* from vtiger_cvcolumnlist";
		$sSQL .= " inner join vtiger_customview on vtiger_customview.cvid = vtiger_cvcolumnlist.cvid";
		$sSQL .= " where vtiger_customview.cvid =".$cvid." order by vtiger_cvcolumnlist.columnindex";
		$result = $adb->query($sSQL);
		while($columnrow = $adb->fetch_array($result))
		{
			$columnlist[$columnrow['columnindex']] = $columnrow['columnname'];
		} 
		return $columnlist;
	}

	/** to get the standard filter fields or the given module 
	  * @param $module :: Type String
	  * @returns  $stdcriteria_list Array in the following format
	  * $stdcriteria_list = Array( $tablename:$columnname:$fieldname:$module_$fieldlabel => $fieldlabel,
	  *			 $tablename1:$columnname1:$fieldname1:$module_$fieldlabel1 => $fieldlabel1,  
	  *					|
	  *			 $tablenamen:$columnnamen:$fieldnamen:$module_$fieldlabeln => $fieldlabeln)  
	  */	
	function getStdCriteriaByModule($module)
	{
		global $adb;
		$tabid = getTabid($module);

		global $current_user;
        	require('user_privileges/user_privileges_'.$current_user->id.'.php');


		foreach($this->module_list[$module] as $key=>$blockid)
		{
			$blockids[] = $blockid;
		}
		$blockids = implode(",",$blockids);


		if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0)
		{
			$sql = "select * from vtiger_field inner join vtiger_tab on vtiger_tab.tabid = vtiger_field.tabid ";
			$sql.= " where vtiger_field.tabid=".$tabid." and vtiger_field.block in (".$blockids.")
                        and (vtiger_field.uitype =5 or vtiger_field.displaytype=2) ";
			$sql.= " order by vtiger_field.sequence";
		}
		else
		{
			$profileList = getCurrentUserProfileList();
			$sql = "select * from vtiger_field inner join vtiger_tab on vtiger_tab.tabid = vtiger_field.tabid inner join  vtiger_profile2field on vtiger_profile2field.fieldid=vtiger_field.fieldid inner join vtiger_def_org_field on vtiger_def_org_field.fieldid=vtiger_field.fieldid ";
			$sql.= " where vtiger_field.tabid=".$tabid." and vtiger_field.block in (".$blockids.") and (vtiger_field.uitype =5 or vtiger_field.displaytype=2)";
			$sql.= " and vtiger_profile2field.visible=0";
			$sql.= " and vtiger_def_org_field.visible=0  and vtiger_profile2field.profileid in ".$profileList." order by vtiger_field.sequence";
		}			


		$result = $adb->query($sql);

		while($criteriatyperow = $adb->fetch_array($result))
		{
			$fieldtablename = $criteriatyperow["tablename"];
			$fieldcolname = $criteriatyperow["columnname"];
			$fieldlabel = $criteriatyperow["fieldlabel"];
			$fieldname = $criteriatyperow["fieldname"];
			$fieldlabel1 = str_replace(" ","_",$fieldlabel);
			$optionvalue = $fieldtablename.":".$fieldcolname.":".$fieldname.":".$module."_".$fieldlabel1;
			$stdcriteria_list[$optionvalue] = $fieldlabel;
		}

		return $stdcriteria_list;

	}
	
	/** to get the standard filter criteria  
	  * @param $selcriteria :: Type String (optional)
	  * @returns  $filter Array in the following format
	  * $filter = Array( 0 => array('value'=>$filterkey,'text'=>$mod_strings[$filterkey],'selected'=>$selected)
	  * 		     1 => array('value'=>$filterkey1,'text'=>$mod_strings[$filterkey1],'selected'=>$selected)	
	  *		                             		|	
	  * 		     n => array('value'=>$filterkeyn,'text'=>$mod_strings[$filterkeyn],'selected'=>$selected)	
	  */	
	function getStdFilterCriteria($selcriteria = "")
	{
		global $mod_strings; 
		$filter = array();

		$stdfilter = Array("custom"=>"".$mod_strings['Custom']."",
				"prevfy"=>"".$mod_strings['Previous FY']."",
				"thisfy"=>"".$mod_strings['Current FY']."",
				"nextfy"=>"".$mod_strings['Next FY']."",
				"prevfq"=>"".$mod_strings['Previous FQ']."",
				"thisfq"=>"".$mod_strings['Current FQ']."",
				"nextfq"=>"".$mod_strings['Next FQ']."",
				"yesterday"=>"".$mod_strings['Yesterday']."",
				"today"=>"".$mod_strings['Today']."",
				"tomorrow"=>"".$mod_strings['Tomorrow']."",
				"lastweek"=>"".$mod_strings['Last Week']."",
				"thisweek"=>"".$mod_strings['Current Week']."",
				"nextweek"=>"".$mod_strings['Next Week']."",
				"lastmonth"=>"".$mod_strings['Last Month']."",
				"thismonth"=>"".$mod_strings['Current Month']."",
				"nextmonth"=>"".$mod_strings['Next Month']."",
				"last7days"=>"".$mod_strings['Last 7 Days']."",
				"last30days"=>"".$mod_strings['Last 30 Days']."",
				"last60days"=>"".$mod_strings['Last 60 Days']."",
				"last90days"=>"".$mod_strings['Last 90 Days']."",
				"last120days"=>"".$mod_strings['Last 120 Days']."",
				"next30days"=>"".$mod_strings['Next 30 Days']."",
				"next60days"=>"".$mod_strings['Next 60 Days']."",
				"next90days"=>"".$mod_strings['Next 90 Days']."",
				"next120days"=>"".$mod_strings['Next 120 Days']."",
					);

				foreach($stdfilter as $FilterKey=>$FilterValue)
				{
					if($FilterKey == $selcriteria)
					{
						$shtml['value'] = $FilterKey;
						$shtml['text'] = $FilterValue;
						$shtml['selected'] = "selected";
					}else
					{
						$shtml['value'] = $FilterKey;
						$shtml['text'] = $FilterValue;
						$shtml['selected'] = "";
					}
					$filter[] = $shtml;
				}
				return $filter;

	}

	/** to get the standard filter criteria scripts  
	  * @returns  $jsStr : Type String
	  * This function will return the script to set the start data and end date 
	  * for the standard selection criteria
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
    
		if(date("m") <= 4)
		{
			$cFq = date("Y-m-d",mktime(0, 0, 0, "01","01",date("Y")));
			$cFq1 = date("Y-m-d",mktime(0, 0, 0, "04","30",date("Y")));
			$nFq = date("Y-m-d",mktime(0, 0, 0, "05","01",date("Y")));
			$nFq1 = date("Y-m-d",mktime(0, 0, 0, "08","31",date("Y")));
			$pFq = date("Y-m-d",mktime(0, 0, 0, "09","01",date("Y")-1));
			$pFq1 = date("Y-m-d",mktime(0, 0, 0, "12","31",date("Y")-1));
		}else if(date("m") > 4 and date("m") <= 8)
    		{
			$pFq = date("Y-m-d",mktime(0, 0, 0, "01","01",date("Y")));
		  	$pFq1 = date("Y-m-d",mktime(0, 0, 0, "04","30",date("Y")));
		  	$cFq = date("Y-m-d",mktime(0, 0, 0, "05","01",date("Y")));
		  	$cFq1 = date("Y-m-d",mktime(0, 0, 0, "08","31",date("Y")));
      			$nFq = date("Y-m-d",mktime(0, 0, 0, "09","01",date("Y")));
		  	$nFq1 = date("Y-m-d",mktime(0, 0, 0, "12","31",date("Y")));
      
    		}else
    		{
		  	$nFq = date("Y-m-d",mktime(0, 0, 0, "01","01",date("Y")+1));
		  	$nFq1 = date("Y-m-d",mktime(0, 0, 0, "04","30",date("Y")+1));
		  	$pFq = date("Y-m-d",mktime(0, 0, 0, "05","01",date("Y")));
		  	$pFq1 = date("Y-m-d",mktime(0, 0, 0, "08","31",date("Y")));
      			$cFq = date("Y-m-d",mktime(0, 0, 0, "09","01",date("Y")));
		  	$cFq1 = date("Y-m-d",mktime(0, 0, 0, "12","31",date("Y")));      
    		}
    
		$sjsStr = '<script language="JavaScript" type="text/javaScript">
			function showDateRange( type )
			{
				if (type!="custom")
				{
					document.CustomView.startdate.readOnly=true
						document.CustomView.enddate.readOnly=true
						getObj("jscal_trigger_date_start").style.visibility="hidden"
						getObj("jscal_trigger_date_end").style.visibility="hidden"
				}
				else
				{
					document.CustomView.startdate.readOnly=false
						document.CustomView.enddate.readOnly=false
						getObj("jscal_trigger_date_start").style.visibility="visible"
						getObj("jscal_trigger_date_end").style.visibility="visible"
				}
				if( type == "today" )
				{
					document.CustomView.startdate.value = "'.$today.'";
					document.CustomView.enddate.value = "'.$today.'";
				}
				else if( type == "yesterday" )
				{
					document.CustomView.startdate.value = "'.$yesterday.'";
					document.CustomView.enddate.value = "'.$yesterday.'";
				}
				else if( type == "tomorrow" )
				{

					document.CustomView.startdate.value = "'.$tomorrow.'";
					document.CustomView.enddate.value = "'.$tomorrow.'";
				}
				else if( type == "thisweek" )
				{
					document.CustomView.startdate.value = "'.$thisweek0.'";
					document.CustomView.enddate.value = "'.$thisweek1.'";
				}
				else if( type == "lastweek" )
				{
					document.CustomView.startdate.value = "'.$lastweek0.'";
					document.CustomView.enddate.value = "'.$lastweek1.'";
				}
				else if( type == "nextweek" )
				{
					document.CustomView.startdate.value = "'.$nextweek0.'";
					document.CustomView.enddate.value = "'.$nextweek1.'";
				}
				else if( type == "thismonth" )
				{
					document.CustomView.startdate.value = "'.$currentmonth0.'";
					document.CustomView.enddate.value = "'.$currentmonth1.'";
				}
				else if( type == "lastmonth" )
				{
					document.CustomView.startdate.value = "'.$lastmonth0.'";
					document.CustomView.enddate.value = "'.$lastmonth1.'";
				}
				else if( type == "nextmonth" )
				{
					document.CustomView.startdate.value = "'.$nextmonth0.'";
					document.CustomView.enddate.value = "'.$nextmonth1.'";
				}
				else if( type == "next7days" )
				{
					document.CustomView.startdate.value = "'.$today.'";
					document.CustomView.enddate.value = "'.$next7days.'";
				}
				else if( type == "next30days" )
				{
					document.CustomView.startdate.value = "'.$today.'";
					document.CustomView.enddate.value = "'.$next30days.'";
				}
				else if( type == "next60days" )
				{
					document.CustomView.startdate.value = "'.$today.'";
					document.CustomView.enddate.value = "'.$next60days.'";
				}
				else if( type == "next90days" )
				{
					document.CustomView.startdate.value = "'.$today.'";
					document.CustomView.enddate.value = "'.$next90days.'";
				}
				else if( type == "next120days" )
				{
					document.CustomView.startdate.value = "'.$today.'";
					document.CustomView.enddate.value = "'.$next120days.'";
				}
				else if( type == "last7days" )
				{
					document.CustomView.startdate.value = "'.$last7days.'";
					document.CustomView.enddate.value =  "'.$today.'";
				}
				else if( type == "last30days" )
				{
					document.CustomView.startdate.value = "'.$last30days.'";
					document.CustomView.enddate.value = "'.$today.'";
				}
				else if( type == "last60days" )
				{
					document.CustomView.startdate.value = "'.$last60days.'";
					document.CustomView.enddate.value = "'.$today.'";
				}
				else if( type == "last90days" )
				{
					document.CustomView.startdate.value = "'.$last90days.'";
					document.CustomView.enddate.value = "'.$today.'";
				}
				else if( type == "last120days" )
				{
					document.CustomView.startdate.value = "'.$last120days.'";
					document.CustomView.enddate.value = "'.$today.'";
				}
				else if( type == "thisfy" )
				{
					document.CustomView.startdate.value = "'.$currentFY0.'";
					document.CustomView.enddate.value = "'.$currentFY1.'";
				}
				else if( type == "prevfy" )
				{
					document.CustomView.startdate.value = "'.$lastFY0.'";
					document.CustomView.enddate.value = "'.$lastFY1.'";
				}
				else if( type == "nextfy" )
				{
					document.CustomView.startdate.value = "'.$nextFY0.'";
					document.CustomView.enddate.value = "'.$nextFY1.'";
				}
				else if( type == "nextfq" )
				{
					document.CustomView.startdate.value = "'.$nFq.'";
					document.CustomView.enddate.value = "'.$nFq1.'";
				}
				else if( type == "prevfq" )
				{
					document.CustomView.startdate.value = "'.$pFq.'";
					document.CustomView.enddate.value = "'.$pFq1.'";
				}
				else if( type == "thisfq" )
				{
					document.CustomView.startdate.value = "'.$cFq.'";
					document.CustomView.enddate.value = "'.$cFq1.'";
				}
				else
				{
					document.CustomView.startdate.value = "";
					document.CustomView.enddate.value = "";
				}
			}
		</script>';

		return $sjsStr;
	}
	
	/** to get the standard filter for the given customview Id  
	  * @param $cvid :: Type Integer
	  * @returns  $stdfilterlist Array in the following format
	  * $stdfilterlist = Array( 'columnname' =>  $tablename:$columnname:$fieldname:$module_$fieldlabel,'stdfilter'=>$stdfilter,'startdate'=>$startdate,'enddate'=>$enddate) 
	  */	

	function getStdFilterByCvid($cvid)
	{
		global $adb;

		$sSQL = "select vtiger_cvstdfilter.* from vtiger_cvstdfilter inner join vtiger_customview on vtiger_customview.cvid = vtiger_cvstdfilter.cvid";
		$sSQL .= " where vtiger_cvstdfilter.cvid=".$cvid;

		$result = $adb->query($sSQL);
		$stdfilterrow = $adb->fetch_array($result);

		$stdfilterlist["columnname"] = $stdfilterrow["columnname"];
		$stdfilterlist["stdfilter"] = $stdfilterrow["stdfilter"];

		if($stdfilterrow["stdfilter"] == "custom")
		{
			if($stdfilterrow["startdate"] != "0000-00-00")
			{
				$stdfilterlist["startdate"] = $stdfilterrow["startdate"];
			}
			if($stdfilterrow["enddate"] != "0000-00-00")
			{
				$stdfilterlist["enddate"] = $stdfilterrow["enddate"];
			}
		}else  //if it is not custom get the date according to the selected duration
		{
			$datefilter = $this->getDateforStdFilterBytype($stdfilterrow["stdfilter"]);
			$stdfilterlist["startdate"] = $datefilter[0];
			$stdfilterlist["enddate"] = $datefilter[1];
		}

		return $stdfilterlist;
	}

	/** to get the Advanced filter for the given customview Id  
	  * @param $cvid :: Type Integer
	  * @returns  $stdfilterlist Array in the following format
	  * $stdfilterlist = Array( 0=>Array('columnname' =>  $tablename:$columnname:$fieldname:$module_$fieldlabel,'comparator'=>$comparator,'value'=>$value),
	  *			    1=>Array('columnname' =>  $tablename1:$columnname1:$fieldname1:$module_$fieldlabel1,'comparator'=>$comparator1,'value'=>$value1),
	  *		   			|
	  *			    4=>Array('columnname' =>  $tablename4:$columnname4:$fieldname4:$module_$fieldlabel4,'comparator'=>$comparatorn,'value'=>$valuen),
	  */	
    	function getAdvFilterByCvid($cvid)
	{
		global $adb;
		global $modules;

		$sSQL = "select vtiger_cvadvfilter.* from vtiger_cvadvfilter inner join vtiger_customview on vtiger_cvadvfilter.cvid = vtiger_customview.cvid";
		$sSQL .= " where vtiger_cvadvfilter.cvid=".$cvid;
		$result = $adb->query($sSQL);

		while($advfilterrow = $adb->fetch_array($result))
		{
			$advft["columnname"] = $advfilterrow["columnname"];
			$advft["comparator"] = $advfilterrow["comparator"];
			$advft["value"] = $advfilterrow["value"];
			$advfilterlist[] = $advft;
		}

		return $advfilterlist;
	}


	/** to get the customview Columnlist Query for the given customview Id  
	  * @param $cvid :: Type Integer
	  * @returns  $getCvColumnList as a string 
	  * This function will return the columns for the given customfield in comma seperated values in the format
	  *                     $tablename.$columnname,$tablename1.$columnname1, ------ $tablenamen.$columnnamen  
	  * 
	  */	
	function getCvColumnListSQL($cvid)
	{
		$columnslist = $this->getColumnsListByCvid($cvid);
		if(isset($columnslist))
		{
			foreach($columnslist as $columnname=>$value)
			{
				$tablefield = "";
				if($value != "")
				{
					$list = explode(":",$value);
					
					//Added For getting status for Activities -Jaguar
					$sqllist_column = $list[0].".".$list[1];
					if($this->customviewmodule == "Calendar")
					{
						if($list[1] == "status")
						{
							$sqllist_column = "case when (vtiger_activity.status not like '') then vtiger_activity.status else vtiger_activity.eventstatus end as activitystatus";
						}
					}

					//Added for for assigned to sorting
					if($list[1] == "smownerid")
					{
						$sqllist_column = "case when (vtiger_users.user_name not like '') then vtiger_users.user_name else vtiger_groups.groupname end as user_name";
					}
					
					$sqllist[] = $sqllist_column;
					//Ends
					
					$tablefield[$list[0]] = $list[1];
					$fieldlabel = trim(str_replace($this->escapemodule," ",$list[3]));
					$this->list_fields[$fieldlabel] = $tablefield;
					$this->list_fields_name[$fieldlabel] = $list[2];
				}
			}
			$returnsql = implode(",",$sqllist);
		}
		return $returnsql;

	}

	/** to get the customview stdFilter Query for the given customview Id  
	  * @param $cvid :: Type Integer
	  * @returns  $stdfiltersql as a string 
	  * This function will return the standard filter criteria for the given customfield 
	  * 
	  */	
	function getCVStdFilterSQL($cvid)
	{
		global $adb;
		$stdfilterlist = $this->getStdFilterByCvid($cvid);
		if(isset($stdfilterlist))
		{
			foreach($stdfilterlist as $columnname=>$value)
			{
				if($columnname == "columnname")
				{
					$filtercolumn = $value;
				}elseif($columnname == "stdfilter")
				{
					$filtertype = $value;
				}elseif($columnname == "startdate")
				{
					$startdate = $value;
				}elseif($columnname == "enddate")
				{
					$enddate = $value;
				}
			}
			if($filtertype != "custom")
			{
				$datearray = $this->getDateforStdFilterBytype($filtertype);
				$startdate = $datearray[0];
				$enddate = $datearray[1];
			}
			if($startdate != "" && $enddate != "")
			{
				$columns = explode(":",$filtercolumn);
				$stdfiltersql = $columns[0].".".$columns[1]." between '".$startdate." 00:00:00' and '".$enddate." 23:59:00'";
			}
		}
		return $stdfiltersql;
	}
	/** to get the customview AdvancedFilter Query for the given customview Id  
	  * @param $cvid :: Type Integer
	  * @returns  $advfiltersql as a string 
	  * This function will return the advanced filter criteria for the given customfield 
	  * 
	  */	
	function getCVAdvFilterSQL($cvid)
	{
		$advfilter = $this->getAdvFilterByCvid($cvid);
		if(isset($advfilter))
		{
			foreach($advfilter as $key=>$advfltrow)
			{
				if(isset($advfltrow))
				{
					$columns = explode(":",$advfltrow["columnname"]);
					$datatype = (isset($columns[4])) ? $columns[4] : "";
					if($advfltrow["columnname"] != "" && $advfltrow["comparator"] != "")
					{

						$valuearray = explode(",",trim($advfltrow["value"]));
						if(isset($valuearray) && count($valuearray) > 1)
						{
							$advorsql = "";
							for($n=0;$n<count($valuearray);$n++)
							{
								$advorsql[] = $this->getRealValues($columns[0],$columns[1],$advfltrow["comparator"],trim($valuearray[$n]),$datatype);
							}
							$advorsqls = implode(" or ",$advorsql);
							$advfiltersql[] = " (".$advorsqls.") ";
						}else
						{
							//Added for getting vtiger_activity Status -Jaguar
							if($this->customviewmodule == "Calendar" && $columns[1] == "status")
							{
								$advfiltersql[] = "case when (vtiger_activity.status not like '') then vtiger_activity.status else vtiger_activity.eventstatus end".$this->getAdvComparator($advfltrow["comparator"],trim($advfltrow["value"]),$datatype);
							}
							else
							{
								$advfiltersql[] = $this->getRealValues($columns[0],$columns[1],$advfltrow["comparator"],trim($advfltrow["value"]),$datatype);
							}
						}
					}
				}
			}
		}
		if(isset($advfiltersql))
		{
			$advfsql = implode(" and ",$advfiltersql);
		}
		return $advfsql;
	}
	
	/** to get the realvalues for the given value   
	  * @param $tablename :: type string 
	  * @param $fieldname :: type string 
	  * @param $comparator :: type string 
	  * @param $value :: type string 
	  * @returns  $value as a string in the following format
	  *	  $tablename.$fieldname comparator
	  */
	function getRealValues($tablename,$fieldname,$comparator,$value,$datatype)
	{
		if($fieldname == "smownerid" || $fieldname == "inventorymanager")
		{
			$value = $tablename.".".$fieldname.$this->getAdvComparator($comparator,getUserId_Ol($value),$datatype);
		}else if($fieldname == "parentid")
		{
			$value = $tablename.".".$fieldname.$this->getAdvComparator($comparator,$this->getAccountId($value),$datatype);
		}else if($fieldname == "accountid")
		{
			$value = $tablename.".".$fieldname.$this->getAdvComparator($comparator,$this->getAccountId($value),$datatype);
		}else if($fieldname == "contactid")
		{
			$value = $tablename.".".$fieldname.$this->getAdvComparator($comparator,$this->getContactId($value),$datatype);
		}else if($fieldname == "vendor_id" || $fieldname == "vendorid")
		{
			$value = $tablename.".".$fieldname.$this->getAdvComparator($comparator,$this->getVendorId($value),$datatype);
		}else if($fieldname == "potentialid")
		{
			$value = $tablename.".".$fieldname.$this->getAdvComparator($comparator,$this->getPotentialId($value),$datatype);
		}else if($fieldname == "quoteid")
		{
			$value = $tablename.".".$fieldname.$this->getAdvComparator($comparator,$this->getQuoteId($value),$datatype);
		}
		else if($fieldname == "product_id")
		{
			$value = $tablename.".".$fieldname.$this->getAdvComparator($comparator,$this->getProductId($value),$datatype);
		}
		else if($fieldname == "salesorderid")
		{
			$value = $tablename.".".$fieldname.$this->getAdvComparator($comparator,$this->getSoId($value),$datatype);
		}
		else if($fieldname == "crmid" || $fieldname == "parent_id")
		{
			//Added on 14-10-2005 -- for HelpDesk
			if($this->customviewmodule == 'HelpDesk' && $fieldname == "crmid")
			{
				$value = $tablename.".".$fieldname.$this->getAdvComparator($comparator,$value,$datatype);
			}
			else
			{
				$value = $tablename.".".$fieldname." in (".$this->getSalesEntityId($value).") ";
			}
		}
		else
		{
			$value = $tablename.".".$fieldname.$this->getAdvComparator($comparator,$value,$datatype);	
		}
		return $value;
	}
	
	/** to get the entityId for the given module   
	  * @param $setype :: type string 
	  * @returns  $parent_id as a string of comma seperated id 
	  *       $id,$id1,$id2, ---- $idn
	  */

	function getSalesEntityId($setype)
	{
                global $log;
                $log->info("in getSalesEntityId ".$setype);
		global $adb;
		$sql = "select crmid from vtiger_crmentity where setype='".$setype."' and deleted = 0";
		$result = $adb->query($sql);
		while($row = $adb->fetch_array($result))
		{
			$parent_id[] = $row["crmid"];
		}
		if(isset($parent_id))
		{
			$parent_id = implode(",",$parent_id);
		}else
		{
			$parent_id = 0;
		}
		return $parent_id;
	}

	/** to get the salesorder id for the given sales order subject   
	  * @param $so_name :: type string 
	  * @returns  $so_id as a Integer
	  */

	function getSoId($so_name)
	{
		global $log;
                $log->info("in getSoId ".$so_name);
		global $adb;
		if($so_name != '')
		{
			$sql = "select salesorderid from vtiger_salesorder where subject='".$so_name."'";
			$result = $adb->query($sql);
			$so_id = $adb->query_result($result,0,"salesorderid");
		}
		return $so_id;
	}

	/** to get the Product id for the given Product Name   
	  * @param $product_name :: type string 
	  * @returns  $productid as a Integer
	  */

	function getProductId($product_name)
	{
		global $log;
                $log->info("in getProductId ".$product_name);
		global $adb;
		if($product_name != '')
		{
			$sql = "select productid from vtiger_products where productname='".$product_name."'";
			$result = $adb->query($sql);
			$productid = $adb->query_result($result,0,"productid");
		}
		return $productid;
	}

	/** to get the Quote id for the given Quote Name   
	  * @param $quote_name :: type string 
	  * @returns  $quote_id as a Integer
	  */
	  
	function getQuoteId($quote_name)
	{
		global $log;
                $log->info("in getQuoteId ".$quote_name);
		global $adb;
		if($quote_name != '')
		{
			$sql = "select quoteid from vtiger_quotes where subject='".$quote_name."'";
			$result = $adb->query($sql);
			$quote_id = $adb->query_result($result,0,"quoteid");
		}
		return $quote_id;
	}

	/** to get the Potential  id for the given Potential Name   
	  * @param $pot_name :: type string 
	  * @returns  $potentialid as a Integer
	  */

	function getPotentialId($pot_name)
	{
		 global $log;
                $log->info("in getPotentialId ".$pot_name);
		global $adb;
		if($pot_name != '')
		{
			$sql = "select potentialid from vtiger_potential where potentialname='".$pot_name."'";
			$result = $adb->query($sql);
			$potentialid = $adb->query_result($result,0,"potentialid");
		}
		return $potentialid;
	}
	
	/** to get the Vendor id for the given Vendor Name   
	  * @param $vendor_name :: type string 
	  * @returns  $vendor_id as a Integer
	  */


	function getVendorId($vendor_name)
	{
		 global $log;
                $log->info("in getVendorId ".$vendor_name);
		global $adb;
		if($vendor_name != '')
		{
			$sql = "select vendorid from vtiger_vendor where vendorname='".$vendor_name."'";
			$result = $adb->query($sql);
			$vendor_id = $adb->query_result($result,0,"vendorid");
		}
		return $vendor_id;
	}
	
	/** to get the Contact id for the given Contact Name   
	  * @param $contact_name :: type string 
	  * @returns  $contact_id as a Integer
	  */


	function getContactId($contact_name)
	{
		global $log;
                $log->info("in getContactId ".$contact_name);
		global $adb;
		if($contact_name != '')
		{
			$sql = "select contactid from vtiger_contactdetails where lastname='".$contact_name."'";
			$result = $adb->query($sql);
			$contact_id = $adb->query_result($result,0,"contactid");
		}
		return $contact_id;
	}

	/** to get the Account id for the given Account Name   
	  * @param $account_name :: type string 
	  * @returns  $accountid as a Integer
	  */

	function getAccountId($account_name)
	{
		 global $log;
                $log->info("in getAccountId ".$account_name);
		global $adb;
		if($account_name != '')
		{
			$sql = "select accountid from vtiger_account where accountname='".$account_name."'";
			$result = $adb->query($sql);
			$accountid = $adb->query_result($result,0,"accountid");
		}		
		return $accountid;
	}

	/** to get the comparator value for the given comparator and value   
	  * @param $comparator :: type string 
	  * @param $value :: type string
	  * @returns  $rtvalue in the format $comparator $value
	  */

	function getAdvComparator($comparator,$value,$datatype = '')
	{
			
		global $adb;
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
			$rtvalue = " like ".$adb->quote($value."%");
		}
		if($comparator == "c")
		{
			$rtvalue = " like ".$adb->quote("%".$value."%");
		}
		if($comparator == "k")
		{
			$rtvalue = " not like ".$adb->quote("%".$value."%");
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

		return $rtvalue;
	}

	/** to get the date value for the given type   
	  * @param $type :: type string 
	  * @returns  $datevalue array in the following format 
	  *             $datevalue = Array(0=>$startdate,1=>$enddate)
	  */

	function getDateforStdFilterBytype($type)
	{
		$thisyear = date("Y");
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

    		if(date("m") <= 4)
		{
		  	$cFq = date("Y-m-d",mktime(0, 0, 0, "01","01",date("Y")));
		  	$cFq1 = date("Y-m-d",mktime(0, 0, 0, "04","30",date("Y")));
		  	$nFq = date("Y-m-d",mktime(0, 0, 0, "05","01",date("Y")));
		  	$nFq1 = date("Y-m-d",mktime(0, 0, 0, "08","31",date("Y")));
      			$pFq = date("Y-m-d",mktime(0, 0, 0, "09","01",date("Y")-1));
		  	$pFq1 = date("Y-m-d",mktime(0, 0, 0, "12","31",date("Y")-1));
    		}else if(date("m") > 4 and date("m") <= 8)
    		{
		  	$pFq = date("Y-m-d",mktime(0, 0, 0, "01","01",date("Y")));
		  	$pFq1 = date("Y-m-d",mktime(0, 0, 0, "04","30",date("Y")));
		  	$cFq = date("Y-m-d",mktime(0, 0, 0, "05","01",date("Y")));
		  	$cFq1 = date("Y-m-d",mktime(0, 0, 0, "08","31",date("Y")));
      			$nFq = date("Y-m-d",mktime(0, 0, 0, "09","01",date("Y")));
		  	$nFq1 = date("Y-m-d",mktime(0, 0, 0, "12","31",date("Y")));
      
    		}else
    		{
		  	$nFq = date("Y-m-d",mktime(0, 0, 0, "01","01",date("Y")+1));
		  	$nFq1 = date("Y-m-d",mktime(0, 0, 0, "04","30",date("Y")+1));
		  	$pFq = date("Y-m-d",mktime(0, 0, 0, "05","01",date("Y")));
		  	$pFq1 = date("Y-m-d",mktime(0, 0, 0, "08","31",date("Y")));
      			$cFq = date("Y-m-d",mktime(0, 0, 0, "09","01",date("Y")));
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
		elseif($type == "thisfq")
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

	/** to get the customview query for the given customview   
	  * @param $viewid (custom view id):: type Integer 
	  * @param $listquery (List View Query):: type string 
	  * @param $module (Module Name):: type string 
	  * @returns  $query 
	  */

	function getModifiedCvListQuery($viewid,$listquery,$module)
	{
		if($viewid != "" && $listquery != "")
		{
			$listviewquery = substr($listquery, strpos($listquery,'FROM'),strlen($listquery));
			if($module == "Calendar" || $module == "Emails")
			{
				$query = "select ".$this->getCvColumnListSQL($viewid)." ,vtiger_crmentity.crmid,vtiger_activity.* ".$listviewquery;
			}else if($module == "Notes")
			{
				$query = "select ".$this->getCvColumnListSQL($viewid)." ,vtiger_crmentity.crmid,vtiger_notes.* ".$listviewquery;
			}
			else if($module == "Products")
			{
				$query = "select ".$this->getCvColumnListSQL($viewid)." ,vtiger_crmentity.crmid,vtiger_products.* ".$listviewquery;
			}
			else if($module == "Vendors")
			{
				$query = "select ".$this->getCvColumnListSQL($viewid)." ,vtiger_crmentity.crmid ".$listviewquery;
			}
			else if($module == "PriceBooks")
			{
				$query = "select ".$this->getCvColumnListSQL($viewid)." ,vtiger_crmentity.crmid ".$listviewquery;
			}
			else if($module == "Faq")
		       	{
				$query = "select ".$this->getCvColumnListSQL($viewid)." ,vtiger_crmentity.crmid ".$listviewquery;
			}		
			else
			{
				$query = "select ".$this->getCvColumnListSQL($viewid)." ,vtiger_crmentity.crmid ".$listviewquery;
			}
			$stdfiltersql = $this->getCVStdFilterSQL($viewid);
			$advfiltersql = $this->getCVAdvFilterSQL($viewid);
			if(isset($stdfiltersql) && $stdfiltersql != '')
			{
				$query .= ' and '.$stdfiltersql;
			}
			if(isset($advfiltersql) && $advfiltersql != '')
			{
				$query .= ' and '.$advfiltersql;
			}

		}
		return $query;
	}

	/** to get the Key Metrics for the home page query for the given customview  to find the no of records 
	  * @param $viewid (custom view id):: type Integer 
	  * @param $listquery (List View Query):: type string 
	  * @param $module (Module Name):: type string 
	  * @returns  $query 
	  */
	function getMetricsCvListQuery($viewid,$listquery,$module)
	{
		if($viewid != "" && $listquery != "")
                {
                        $listviewquery = substr($listquery, strpos($listquery,'FROM'),strlen($listquery));

                        $query = "select count(*) AS count ".$listviewquery;
                        
			$stdfiltersql = $this->getCVStdFilterSQL($viewid);
                        $advfiltersql = $this->getCVAdvFilterSQL($viewid);
                        if(isset($stdfiltersql) && $stdfiltersql != '')
                        {
                                $query .= ' and '.$stdfiltersql;
                        }
                        if(isset($advfiltersql) && $advfiltersql != '')
                        {
                                $query .= ' and '.$advfiltersql;
                        }

                }

                return $query;
	}
	
	/** to get the custom action details for the given customview  
	  * @param $viewid (custom view id):: type Integer 
	  * @returns  $calist array in the following format 
	  * $calist = Array ('subject'=>$subject,
  			     'module'=>$module,
	     		     'content'=>$content,
			     'cvid'=>$custom view id)
	  */
	function getCustomActionDetails($cvid)
	{
		global $adb;

		$sSQL = "select vtiger_customaction.* from vtiger_customaction inner join vtiger_customview on vtiger_customaction.cvid = vtiger_customview.cvid";
		$sSQL .= " where vtiger_customaction.cvid=".$cvid;
		$result = $adb->query($sSQL);

		while($carow = $adb->fetch_array($result))
		{
			$calist["subject"] = $carow["subject"];
			$calist["module"] = $carow["module"];
			$calist["content"] = $carow["content"];
			$calist["cvid"] = $carow["cvid"];
		}
		return $calist;	
	}

}
?>
