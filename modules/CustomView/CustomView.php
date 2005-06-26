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
global $adv_filter_options;

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

class CustomView extends CRMEntity{



	var $module_list = Array("Leads"=>Array("Information"=>1,"Address"=>2,"Description"=>3,"Custom Information"=>5),
				 "Contacts"=>Array("Information"=>1,"Address"=>2,"Description"=>3,"Custom Information"=>5),
				 "Accounts"=>Array("Information"=>1,"Address"=>2,"Description"=>3,"Custom Information"=>5),
				 "Potentials"=>Array("Information"=>1,"Description"=>2,"Custom Information"=>5),
				 "Activities"=>Array("Information"=>1,"Description"=>2),
				 "Products"=>Array("Information"=>1,"Description"=>2,"Custom Information"=>5),
				 "Notes"=>Array("Information"=>1,"Description"=>3),
				 "Emails"=>Array("Information"=>1,"Description"=>2),
				 "HelpDesk"=>Array("Information"=>'1,2',"Description"=>3,"Custom Information"=>5),
				 "Quotes"=>Array("Information"=>1,"Address"=>2,"Description"=>3,"Custom Information"=>5),
				 "Orders"=>Array("Information"=>1,"Address"=>2,"Description"=>3,"Custom Information"=>5),
				 "Invoice"=>Array("Information"=>1,"Address"=>2,"Description"=>3,"Custom Information"=>5)
				);


	var $customviewmodule;

	var $list_fields;

	var $list_fields_name;

	var $setdefaultviewid;

	var $escapemodule;

	var $mandatoryvalues;
	
	var $showvalues;

	function CustomView($module="")
	{
		$this->customviewmodule = $module;
		$this->escapemodule[] =	$module."_";
		$this->escapemodule[] = "_";
	}

	// to get the available customviews for a module
	// return type array
	function getCustomViewByCvid($cvid)
	{
		global $adb;
		$tabid = getTabid($this->customviewmodule);
		$ssql = "select customview.* from customview inner join tab on tab.name = customview.entitytype";
		$ssql .= " where customview.cvid=".$cvid;		

		$result = $adb->query($ssql);

		while($cvrow=$adb->fetch_array($result))
		{
			$customviewlist["viewname"] = $cvrow["viewname"];
			$customviewlist["setdefault"] = $cvrow["setdefault"];
			$customviewlist["setmetrics"] = $cvrow["setmetrics"];
		}
		return $customviewlist;		
	}	
	function getCustomViewCombo()
	{
		global $adb;
                $tabid = getTabid($this->customviewmodule);
                $ssql = "select customview.* from customview inner join tab on tab.name = customview.entitytype";
                $ssql .= " where tab.tabid=".$tabid;
		//echo $ssql;
                $result = $adb->query($ssql);
                while($cvrow=$adb->fetch_array($result))
                {
                        if($cvrow['setdefault'] == 1)
			{
				$shtml .= "<option selected value=\"".$cvrow['cvid']."\">".$cvrow['viewname']."</option>";
				$this->setdefaultviewid = $cvrow['cvid'];
			}
			else
			{
				$shtml .= "<option value=\"".$cvrow['cvid']."\">".$cvrow['viewname']."</option>";
			}
                }
		//echo $shtml;
                return $shtml;
	}
	function getColumnsListbyBlock($module,$block)
        {
                global $adb;
                $tabid = getTabid($module);
                global $profile_id;

                $sql = "select * from field inner join profile2field on profile2field.fieldid=field.fieldid";
		$sql.= " where field.tabid=".$tabid." and field.block in (".$block.") and";
		$sql.= " field.displaytype in (1,2) and profile2field.visible=0";
		$sql.= " and profile2field.profileid=".$profile_id." order by sequence";

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
                        /*if($fieldtablename == "crmentity")
                        {
                           $fieldtablename = $fieldtablename.$module;
                        }*/
                        $fieldlabel = $adb->query_result($result,$i,"fieldlabel");
                        $fieldlabel1 = str_replace(" ","_",$fieldlabel);
                        $optionvalue = $fieldtablename.":".$fieldcolname.":".$fieldname.":".$module."_".$fieldlabel1.":".$fieldtypeofdata;
                        $module_columnlist[$optionvalue] = $fieldlabel;
			if($fieldtype[1] == "M")
			{
				$this->mandatoryvalues[] = "'".$optionvalue."'";
				$this->showvalues[] = $fieldlabel;
			}
                }
                return $module_columnlist;
        }

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
	
	function getColumnsListByCvid($cvid)
	{
		global $adb;
		
		$sSQL = "select cvcolumnlist.* from cvcolumnlist";
		$sSQL .= " inner join customview on customview.cvid = cvcolumnlist.cvid";
		$sSQL .= " where customview.cvid =".$cvid." order by cvcolumnlist.columnindex";
		//echo $sSQL;
		$result = $adb->query($sSQL);
		
		while($columnrow = $adb->fetch_array($result))
		{
			$columnlist[$columnrow['columnindex']] = $columnrow['columnname'];
		} 
	
		return $columnlist;
	}

	function getStdCriteriaByModule($module)
        {
                global $adb;
                $tabid = getTabid($module);
                global $profile_id;

                $sql = "select * from field inner join tab on tab.tabid = field.tabid
                        inner join profile2field on profile2field.fieldid=field.fieldid
                        where field.tabid=".$tabid." and (field.uitype =5 or field.displaytype=2)
                        and profile2field.visible=0 and profile2field.profileid=".$profile_id." order by field.sequence";

                $result = $adb->query($sql);

                while($criteriatyperow = $adb->fetch_array($result))
                {
                        $fieldtablename = $criteriatyperow["tablename"];
                        $fieldcolname = $criteriatyperow["columnname"];
                        $fieldlabel = $criteriatyperow["fieldlabel"];
			$fieldname = $criteriatyperow["fieldname"];
                        /*if($fieldtablename == "crmentity")
                        {
                           $fieldtablename = $fieldtablename.$module;
                        }*/
                        $fieldlabel1 = str_replace(" ","_",$fieldlabel);
                        $optionvalue = $fieldtablename.":".$fieldcolname.":".$fieldname.":".$module."_".$fieldlabel1;
                        $stdcriteria_list[$optionvalue] = $fieldlabel;
                }

                return $stdcriteria_list;

        }

	function getStdFilterCriteria($selcriteria = "")
        {

                $stdfilter = Array("custom"=>"Custom",
					 "prevfy"=>"Previous FY",
					 "thisfy"=>"Current FY",
					 "nextfy"=>"Next FY",
					 "prevfq"=>"Previous FQ",
					 "thisfq"=>"Current FQ",
					 "nextfq"=>"Next FQ",
                                         "yesterday"=>"Yesterday",
					 "today"=>"Today",
					 "tomorrow"=>"Tomorrow",
					 "lastweek"=>"Last Week",
					 "thisweek"=>"Current Week",
					 "nextweek"=>"Next Week",
					 "lastmonth"=>"Last Month",
					 "thismonth"=>"Current Month",
                                         "nextmonth"=>"Next Month",
					 "last7days"=>"Last 7 Days",
					 "last30days"=>"Last 30 Days", 
					 "last60days"=>"Last 60 Days",
					 "last90days"=>"Last 90 Days",
					 "last120days"=>"Last 120 Days",
                                         "next30days"=>"Next 30 Days",
					 "next60days"=>"Next 60 Days",
					 "next90days"=>"Next 90 Days",
					 "next120days"=>"Next 120 Days"
					 );

		foreach($stdfilter as $FilterKey=>$FilterValue)
		{
			if($FilterKey == $selcriteria)
			{
			 $shtml .= "<option selected value='".$FilterKey."'>".$FilterValue."</option>";
			}else
			{
			 $shtml .= "<option value='".$FilterKey."'>".$FilterValue."</option>";
			}
		}
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
                        document.CustomView.startdate.value = "2005-07-01";
                        document.CustomView.enddate.value = "2005-09-30";
                        }
                        else if( type == "prevfq" )
                        {
                        document.CustomView.startdate.value = "2005-01-01";
                        document.CustomView.enddate.value = "2005-03-31";
                        }
                        else if( type == "thisfq" )
                        {
                        document.CustomView.startdate.value = "2005-04-01";
                        document.CustomView.enddate.value = "2005-06-30";
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
	
	function getStdFilterByCvid($cvid)
        {
                global $adb;
		
		$sSQL = "select cvstdfilter.* from cvstdfilter inner join customview on customview.cvid = cvstdfilter.cvid";
		$sSQL .= " where cvstdfilter.cvid=".$cvid;

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
                }
		
		return $stdfilterlist;
        }
	
	//<<<<<<<<advanced filter>>>>>>>>>>>>>>
        function getAdvFilterByCvid($cvid)
        {
                global $adb;
                global $modules;
	
		$sSQL = "select cvadvfilter.* from cvadvfilter inner join customview on cvadvfilter.cvid = customview.cvid";
		$sSQL .= " where cvadvfilter.cvid=".$cvid;
		//echo $sSQL;
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
        //<<<<<<<<advanced filter>>>>>>>>>>>>>>

	function getCvColumnListSQL($cvid)
	{
		$columnslist = $this->getColumnsListByCvid($cvid);
		//print_r($columnslist);
		if(isset($columnslist))
		{
			foreach($columnslist as $columnname=>$value)
			{
				$tablefield = "";
				if($value != "")
				{
					$list = explode(":",$value);
					$sqllist[] = $list[0].".".$list[1];

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

	function getCVStdFilterSQL($cvid)
	{
		global $adb;

		$stdfilterlist = $this->getStdFilterByCvid($cvid);
		//print_r($stdfilterlist);
		if(isset($stdfilterlist))
		{
			foreach($stdfilterlist as $columnname=>$value)
			{
				if($columnname == "columnname")
				{
					$filtercolumn = $value;
				}elseif($columnname = "stdfilter")
				{
					$filtertype = $value;
				}elseif($columnname = "startdate")
				{
					$startdate = $value;
				}elseif($columnname = "enddate")
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
				$stdfiltersql = $columns[0].".".$columns[1]." between '".$startdate."' and '".$enddate."'";
			}
		}
		//echo $stdfiltersql;
		return $stdfiltersql;
	}
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
					if($advfltrow["columnname"] != "" && $advfltrow["comparator"] != "" && $advfltrow["value"] != "")
					{
						$advfiltersql[] = $columns[0].".".$columns[1].$this->getAdvComparator($advfltrow["comparator"],$advfltrow["value"]);
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
	
	function getAdvComparator($comparator,$value)
	{
/*		fLabels['e'] = 'equals';
		fLabels['n'] = 'not equal to';
		fLabels['s'] = 'starts with';
		fLabels['c'] = 'contains';
		fLabels['k'] = 'does not contain';
		fLabels['l'] = 'less than';
		fLabels['g'] = 'greater than';
		fLabels['m'] = 'less or equal';
		fLabels['h'] = 'greater or equal';*/
		//require_once('include/database/PearDatabase.php');

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
                                $rtvalue = "is NOT NULL";
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
		
		return $rtvalue;
	}
	function getDateforStdFilterBytype($type)
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

	function getModifiedCvListQuery($viewid,$listquery,$module)
	{
		if($viewid != "" && $listquery != "")
		{
			$listviewquery = substr($listquery, strpos($listquery,'from'),strlen($listquery));
			//$listviewquery = substr($listviewquery,strpos($listviewquery,'from'),strpos($listviewquery,'where'));

			//$wherequery = substr($listquery, strpos($listquery,'where'),strlen($listquery));


			//echo $listviewquery." ".$wherequery;
			$query = "select ".$this->getCvColumnListSQL($viewid)." ,crmentity.crmid ".$listviewquery;
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
		//echo $query;
		return $query;
	}
	
	function getMetricsCvListQuery($viewid,$listquery,$module)
	{
		if($viewid != "" && $listquery != "")
                {
                        $listviewquery = substr($listquery, strpos($listquery,'from'),strlen($listquery));

                        $query = "select count(*) count ".$listviewquery;
                        
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
	
	/*function getMetricsCustomView($viewnames)
	{
		global $adb;
                $tabid = getTabid($this->customviewmodule);
                $ssql = "select customview.* from customview inner join tab on tab.name = customview.entitytype";
                $ssql .= " where ;
                //echo $ssql;
                $result = $adb->query($ssql);
                while($cvrow=$adb->fetch_array($result))
                {
                        if($cvrow['setdefault'] == 1)
                        {
                                $shtml .= "<option selected value=\"".$cvrow['cvid']."\">".$cvrow['viewname']."</option>";
                                $this->setdefaultviewid = $cvrow['cvid'];
                        }
                        else
                        {
                                $shtml .= "<option value=\"".$cvrow['cvid']."\">".$cvrow['viewname']."</option>";
                        }
                }
                //echo $shtml;
                return $shtml;
	}*/

	function getParentId($fields,$values)
	{
		global $adb;

		if($fields = 'crmentity.smownerid')
		{
			$sSQL = " left join users on users".$value;
			$result = $adb->query($sSQL);
		}
	}

}
?>
