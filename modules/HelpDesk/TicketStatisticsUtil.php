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

function getTotalNoofTickets()
{
	global $adb;
	$query = "select count(*) as totalticketcount from troubletickets inner join crmentity on crmentity.crmid=troubletickets.ticketid where crmentity.deleted=0";
	$result = $adb->query($query);
	$totTickets = $adb->query_result($result,0,"totalticketcount");
	return $totTickets;
}

function getTotalNoofOpenTickets()
{
	global $adb;
	$query = "select count(*) as totalOpenticketcount from troubletickets inner join crmentity on crmentity.crmid=troubletickets.ticketid where crmentity.deleted=0 and troubletickets.status !='Closed'";
	$result = $adb->query($query);
	$totOpenTickets = $adb->query_result($result,0,"totalOpenticketcount");
	return $totOpenTickets;
}

function getTotalNoofClosedTickets()
{
	global $adb;
	$query = "select count(*) as totalClosedticketcount from troubletickets inner join crmentity on crmentity.crmid=troubletickets.ticketid where crmentity.deleted=0 and troubletickets.status ='Closed'";
	$result = $adb->query($query);
	$totClosedTickets = $adb->query_result($result,0,"totalClosedticketcount");
	return $totClosedTickets;
}

function outBar($val,$image_path,$singleUnit) {
        /*if($totalVal == 0) {
                $percent = 0;
        } else {
                $percent = round(($val/$totalVal)*100);
        }
        $scale = $percent * 0.8;*/
	$scale = round($val*$singleUnit);
	if($scale < 1 && scale > 0)
	{
		$scale = 1;
	}
        $out = '<img src='.$image_path.'bl_bar.jpg height=10 width='. $scale .'%>';
        $out .= str_pad($val, (3-strlen(strval($val)))*12 + strlen(strval($val)), "&nbsp;&nbsp;", STR_PAD_LEFT);
	return $out;
}


function showPriorities($image_path, $singleUnit)
{
	global $adb;
	global $mod_strings;
	$prresult = getFromDB("ticketpriorities");
	$noofrows = $adb->num_rows($prresult);
	$prOut = '';

	for($i=0; $i<$noofrows; $i++)
	{
		$priority_val = $adb->query_result($prresult,$i,"ticketpriorities");
		$prOut .= '<tr>';
		if($i == 0)
		{
	        	$prOut .=  '<td class="dataLabel" width="10%" noWrap><div align="left">'.$mod_strings['LBL_PRIORITIES'].'</div></td>';
		}
		else
		{
			
	        	$prOut .=  '<td class="dataLabel" width="10%" noWrap><div align="left"> </div></td>';
		}
          	$prOut .= '<TD  class="dataLabel" width="10%" noWrap ><div align="left">'.$priority_val.'</div></TD>';
		$noofOpenTickets = getTicketCount("Open", $priority_val, "priority");
		$noofClosedTickets = getTicketCount("Closed", $priority_val, "priority"); 
		$noofTotalTickets = getTicketCount("Total", $priority_val, "priority");
		$openOut = outBar($noofOpenTickets, $image_path, $singleUnit); 
		$closeOut = outBar($noofClosedTickets, $image_path, $singleUnit); 
		$totOut = outBar($noofTotalTickets, $image_path, $singleUnit); 
          	$prOut .= '<TD  width="25%" noWrap ><div align="left">'.$openOut.'</div></TD>';
          	$prOut .= '<TD  width="25%" noWrap ><div align="left">'.$closeOut.'</div></TD>';
          	$prOut .= '<TD  width="25%" noWrap ><div align="left">'.$totOut.'</div></TD>';
		$prOut .= '</tr>';
		
	}
	return $prOut;
		
	
}

function showCategories($image_path, $singleUnit)
{
	global $adb;
	global $mod_strings;
	$prresult = getFromDB("ticketcategories");
	$noofrows = $adb->num_rows($prresult);
	$prOut = '';

	for($i=0; $i<$noofrows; $i++)
	{
		$priority_val = $adb->query_result($prresult,$i,"ticketcategories");
		$prOut .= '<tr>';
		if($i == 0)
		{
	        	$prOut .=  '<td class="dataLabel" width="10%" noWrap><div align="left">'.$mod_strings['LBL_CATEGORIES'].'</div></td>';
		}
		else
		{
			
	        	$prOut .=  '<td class="dataLabel" width="10%" noWrap><div align="left"> </div></td>';
		}	
          	$prOut .= '<TD  class="dataLabel" width="10%" noWrap ><div align="left">'.$priority_val.'</div></TD>';
		$noofOpenTickets = getTicketCount("Open", $priority_val, "category");
		$noofClosedTickets = getTicketCount("Closed", $priority_val, "category"); 
		$noofTotalTickets = getTicketCount("Total", $priority_val, "category");
		$openOut = outBar($noofOpenTickets, $image_path, $singleUnit); 
		$closeOut = outBar($noofClosedTickets, $image_path, $singleUnit); 
		$totOut = outBar($noofTotalTickets, $image_path, $singleUnit); 
          	$prOut .= '<TD  width="25%" noWrap ><div align="left">'.$openOut.'</div></TD>';
          	$prOut .= '<TD  width="25%" noWrap ><div align="left">'.$closeOut.'</div></TD>';
          	$prOut .= '<TD  width="25%" noWrap ><div align="left">'.$totOut.'</div></TD>';
		$prOut .= '</tr>';
		
	}
	return $prOut;
		
	
}

function showUserBased($image_path, $singleUnit)
{
	global $adb;
	global $mod_strings;
	$prresult = getFromDB("users");
	$noofrows = $adb->num_rows($prresult);
	$prOut = '';

	for($i=0; $i<$noofrows; $i++)
	{
		$priority_val = $adb->query_result($prresult,$i,"id");
		$user_name = $adb->query_result($prresult,$i,"user_name");
		$prOut .= '<tr>';
		if($i == 0)
		{
	        	$prOut .=  '<td class="dataLabel" width="10%" noWrap><div align="left">'.$mod_strings['LBL_SUPPORTERS'].'</div></td>';
		}
		else
		{
			
	        	$prOut .=  '<td class="dataLabel" width="10%" noWrap><div align="left"> </div></td>';
		}	
          	$prOut .= '<TD  class="dataLabel" width="10%" noWrap ><div align="left">'.$user_name.'</div></TD>';
		$noofOpenTickets = getTicketCount("Open", $priority_val, "smownerid");
		$noofClosedTickets = getTicketCount("Closed", $priority_val, "smownerid"); 
		$noofTotalTickets = getTicketCount("Total", $priority_val, "smownerid");
		$openOut = outBar($noofOpenTickets, $image_path, $singleUnit); 
		$closeOut = outBar($noofClosedTickets, $image_path, $singleUnit); 
		$totOut = outBar($noofTotalTickets, $image_path, $singleUnit); 
          	$prOut .= '<TD  width="25%" noWrap ><div align="left">'.$openOut.'</div></TD>';
          	$prOut .= '<TD  width="25%" noWrap ><div align="left">'.$closeOut.'</div></TD>';
          	$prOut .= '<TD  width="25%" noWrap ><div align="left">'.$totOut.'</div></TD>';
		$prOut .= '</tr>';
		
	}
	return $prOut;
		
	
}

function getFromDB($tableName)
{
	global $adb;
	$query = "select * from ".$tableName;
	$result = $adb->query($query);
	return $result;
}

function getTicketCount($mode, $priority_val, $critColName)
{
	if($critColName == "smownerid")
	{
		$table_name = 'crmentity';
	}
	else
	{
		$table_name = 'troubletickets';
	}
	global $adb;
	if($mode == 'Open')
	{
		$query = "select count(*) as count from troubletickets inner join crmentity on crmentity.crmid=troubletickets.ticketid where crmentity.deleted=0  and ".$table_name.".".$critColName."='".$priority_val."' and troubletickets.status !='Closed'";
		
	}
	elseif($mode == 'Closed')
	{
		$query = "select count(*) as count from troubletickets inner join crmentity on crmentity.crmid=troubletickets.ticketid where crmentity.deleted=0 and ".$table_name.".".$critColName."='".$priority_val."' and troubletickets.status ='Closed'";
	}
	elseif($mode == 'Total')
	{
		$query = "select count(*) as count from troubletickets inner join crmentity on crmentity.crmid=troubletickets.ticketid where crmentity.deleted=0 and ".$table_name.".".$critColName."='".$priority_val."' and deleted='0'";
	}
	$result = $adb->query($query);
	$nooftickets = $adb->query_result($result,0,"count");
	return $nooftickets;
}


?>
