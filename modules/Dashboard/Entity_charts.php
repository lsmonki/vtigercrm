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

require_once('include/utils/utils.php');
require_once('include/database/PearDatabase.php');
require_once('include/utils/CommonUtils.php');

//To get the account names

/* Function to get the Account name for a given account id
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */

function get_account_name($acc_id)
{
	global $adb;

	$acc_qry="select accountname from account where accountid =".$acc_id;
	$acc_result=$adb->query($acc_qry);
	$no_acc_rows=$adb->num_rows($acc_result);

	if($no_acc_rows!=0)
	{
		while($acc_row = $adb->fetch_array($acc_result))
		{
			$name_val=$acc_row['accountname'];
		}
		$name=$name_val;
	}
	else
		$name="";	
	return $name;
}

/* Function returns the values to render the graph for a particular type 
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
*/

// TO get the Values for a particular graph type 
function module_Chart($user_id,$date_start="2000-01-01",$end_date="2017-01-01",$query,$graph_for,$title,$added_qry="",$module="",$graph_type)
{

	global $adb;
	global $days,$date_array,$period_type;

	$where= " and crmentity.smownerid=".$user_id." and crmentity.createdtime between '".$date_start."' and '".$end_date."'" ;
	$query.=$where;
	if($added_qry!="")
		$query.=$added_qry;

	$result=$adb->query($query);
	$no_of_rows=$adb->num_rows($result);
	$mod_count_array=array();
	$mod_name_array=array();
	$count_by_date[]=array();
	$mod_tot_cnt_array=array();

	$mod_name_val="";
	$mod_cnt_crtd_date="";
	$target_val="";
	$bar_target_val="";
	$test_target_val="";

	if($no_of_rows!=0)
	{
		while($row = $adb->fetch_array($result))
		{
			$mod_name= $row[$graph_for];
			if($mod_name=="")
				$mod_name="Un Assigned";
			$crtd_time=$row['createdtime'];
			$crtd_time_array=explode(" ",$crtd_time);
			$crtd_date=$crtd_time_array[0];

			if(!isset($mod_tot_cnt_array[$crtd_date]))
				$mod_tot_cnt_array[$crtd_date]=0;

			$mod_tot_cnt_array[$crtd_date]+=1;

			if (in_array($mod_name,$mod_name_array) == false)
			{
				array_push($mod_name_array,$mod_name); // getting all the unique Names into the array
			}

			//Counting the number of values for a type of graph
			if(!isset($mod_count_array[$mod_name]))
				$mod_count_array[$mod_name]=0;
			$mod_count_array[$mod_name]++;

			//Counting the number of values for a type of graph for a particular date
			if(!isset($count_by_date[$mod_name][$crtd_date]))
				$count_by_date[$mod_name][$crtd_date]=0;

			$count_by_date[$mod_name][$crtd_date]+=1;
		}
		$mod_by_mod_cnt=count($mod_name_array);

		if($mod_by_mod_cnt!=0)
		{
			$url_string="";

			$mod_cnt_table="<table border=0 cellspacing=1 cellpadding=3><tr>
				<th>  Status </th>";

			//Assigning the Header values to the table and giving the dates as graphformat 
			for($i=0; $i<$days; $i++)
			{
				$tdate=$date_array[$i];
				$values=Graph_n_table_format($period_type,$tdate);
				$graph_format=$values[0];
				$table_format=$values[1];
				$mod_cnt_table.= "<th>$table_format</th>";

			}
			$mod_cnt_table .= "<th>Total</th></tr>" ;

			//For all type of the array 
			for ($i=0;$i<count($mod_name_array); $i++)
			{
				$name=$mod_name_array[$i];
				if($name=="")
					$name="Un Assigned";

				if($graph_for =="accountid")
				{
					$name_val_table=get_account_name($name);
				}
				else
				{
					$name_val_table=$name;
				}


				$mod_cnt_table .= "<tr><td>$name_val_table</td>";
				$mod_cnt_crtd_date="";
				//For all the days
				for($j=0;$j<$days;$j++)
				{
					$tdate=$date_array[$j];

					if (!isset($count_by_date[$name][$tdate]))
					{
						$count_by_date[$name][$tdate]="0";
					}
					$cnt_by_date=$count_by_date[$name][$tdate];
					$mod_cnt_table .= "<td>$cnt_by_date </td>";

					if($i==0)
					{
						$values=Graph_n_table_format($period_type,$tdate);
						$graph_format=$values[0];
						$table_format=$values[1];


						//passing the created dates to graph
						if($mod_graph_date!="")
							$mod_graph_date="$mod_graph_date,$graph_format";
						else
							$mod_graph_date="$graph_format";

					}

					//passing the name count by date to graph
					if($mod_cnt_crtd_date!="")
						$mod_cnt_crtd_date.=",$cnt_by_date";
					else
						$mod_cnt_crtd_date="$cnt_by_date";

				}

				$mod_count_val=$mod_count_array[$name];

				$tot_mod_cnt=array_sum($count_by_date[$name]);
				$mod_cnt_table .= "<td align=center>$tot_mod_cnt</td></tr>";

				if($graph_for =="accountid")
				{
					$name_val=get_account_name($name);
					if($name_val!="")
						$name=$name_val;
				}
				//Passing name to graph
				if($mod_name_val!="") $mod_name_val.=",$name";
				else $mod_name_val="$name";


				//Passing count to graph
				if($mod_cnt_val!="") $mod_cnt_val.=",$mod_count_val";
				else $mod_cnt_val="$mod_count_val";	

				if($module!="")
				{
					//Check for Ticket Priority 
					if(($graph_type=="ticketsbypriority"))
					{
						$graph_for="ticketpriorities";
					}

					$link_val="index.php?module=".$module."&action=index&search_text=".$name."&search_field=".$graph_for."&searchtype=BasicSearch&query=true";

					//Adding the links to the graph	
					if($i==0)
						$bar_target_val .=$link_val;
					else
						$bar_target_val .=",".$link_val;
				}
				//The data as per given date
				if($i==0)
					$urlstring .=$mod_cnt_crtd_date;
				else
					$urlstring .="K".$mod_cnt_crtd_date;

				if($i==0)
					$test_target_val.=$link_val;
				else
					$test_target_val.="K".$link_val;
			}
			$mod_cnt_table .="</tr><tr><td class=\"$class\">Total</td>";
			//For all Days getting the table 
			for($k=0; $k<$days;$k++)
			{
				$tdate=$date_array[$k];
				if(!isset($mod_tot_cnt_array[$tdate]))
					$mod_tot_cnt_array[$tdate]="0";
				$tot= $mod_tot_cnt_array[$tdate];
				if($period_type!="yday")
					$mod_cnt_table.="<td>$tot</td>";
			}
			$cnt_total=array_sum($mod_tot_cnt_array);
			$mod_cnt_table.="<td align=\"center\" class=\"$class\">$cnt_total</td></tr></table>";
			$mod_cnt_table.="</table>";
			$title_of_graph="$title : $cnt_total";
			$bar_target_val=urlencode($bar_target_val);
			$test_target_val=urlencode($test_target_val);


			$Prod_mod_val=array($mod_name_val,$mod_cnt_val,$title_of_graph,$bar_target_val,$mod_graph_date,$urlstring,$mod_cnt_table,$test_target_val);	
			return $Prod_mod_val;
		}
		else
		{
			$data=0;

		}

	}
	else
	{
		$data=0;
		echo "<h3> The data is not available with the specified time period</h3>";
	}
	return $data;
}


/** Saving the images of the graph in the /cache/images
  * otherwise it will render the graph with the given details
  * Portions created by vtiger are Copyright (C) vtiger.
  * All Rights Reserved.
  * Contributor(s): ______________________________________..
*/


function save_image_map($filename,$image_map)
{


	global $log;

	if (!$handle = fopen($filename, 'w')) {
		$log->debug(" Cannot open file ($filename)");
		return;
	}

	// Write $somecontent to our opened file.
	if (fwrite($handle, $image_map) === FALSE) {
		$log->debug(" Cannot write to file ($filename)");
		return false;
	}

	fclose($handle);
	return true;
}

?>
