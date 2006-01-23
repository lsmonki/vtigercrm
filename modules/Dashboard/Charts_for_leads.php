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


$period=($HTTP_GET_VARS['period'])?$HTTP_GET_VARS['period']:"tmon";
$dates_values=start_end_dates($period);
$date_start=$dates_values[0];
$end_date=$dates_values[1];
$period_type=$dates_values[2];
$width=$dates_values[3];
$height=$dates_values[4];

$no_days_dates=get_days_n_dates($date_start,$end_date);
$days=$no_days_dates[0];
$date_array=$no_days_dates[1];
$user_id=$current_user->id;

	$query ="select crmentity.crmid,crmentity.createdtime, leaddetails.*, crmentity.smownerid, leadscf.* from leaddetails inner join crmentity on crmentity.crmid=leaddetails.leadid inner join leadsubdetails on leadsubdetails.leadsubscriptionid=leaddetails.leadid inner join leadaddress on leadaddress.leadaddressid=leadsubdetails.leadsubscriptionid inner join leadscf on leaddetails.leadid = leadscf.leadid left join leadgrouprelation on leadscf.leadid=leadgrouprelation.leadid left join groups on groups.groupname=leadgrouprelation.groupname where crmentity.deleted=0 and leaddetails.converted=0";

//function to get the Graph for Lead Source
function leadStatus_chart($user_id,$date_start,$end_date)
{
	global $adb,$query;

	$lead_status_qry="select leadstatus from leadstatus";
	$status_result=$adb->query($lead_status_qry);

	$status_array=array();	

	while($row_status= $adb->fetch_array($status_result))
	{
		$status_array[]=$row_status['leadstatus'];
		$name=$row_status['leadstatus'];

		if($status_name!="") //passing Status values to graph
			$status_name="$status_name,$name";
		else
			$status_name="$name";

		$link_val="index.php?module=Leads&action=index&search_text=$name&search_field=leadstatus&searchtype=BasicSearch&query=true";

		if($target_val!="")
		{
			$target_val="$target_val,$link_val";
		}
		else
		{
			$target_val="$link_val";
		}
	}

	$where= " and crmentity.smownerid=".$user_id." and crmentity.createdtime between '%".$date_start."%' and '%".$end_date."%'" ;
	$query.=$where;

	$result=$adb->query($query);
	$no_of_rows=$adb->num_rows($result);
	$lead_aray=$adb->fetch_array($result);

	$status_count_array[]=array();

	$lead_status_table="<table border=0 cellspacing=1 cellpadding=3><tr>
                                <th>Lead Status</th><th>Total</th></tr>";

	if($no_of_rows!=0)
	{
		while($row = $adb->fetch_array($result))
		{
			$lead_status= $row['leadstatus'];
			$status_count_array[$lead_status]++;	
		}
	}
	$total=0;
	for($i=0;$i<count($status_array);$i++)
	{
		$status=$status_array[$i];

		if(!isset($status_count_array[$status]))
		{
			$status_count_array[$status]=0;
		}

		$val=$status_count_array[$status];
		if($status_val!="") //passing dnloads values to graph
			$status_val="$status_val,$val";
		else
			$status_val="$val";
		 $lead_status_table.="<tr><td>$status</td><td>$val</td></tr>";

		$total +=$val;
	}
	 $lead_status_table.="<tr><td>Total</td><td>$total </td></tr>
				</table>";

	$title_of_graph ="Leads total by Status is $total";

	$Lead_status_val=array($status_name,$status_val,$title_of_graph,$target_val,$lead_status_table);
        return $Lead_status_val;
}
//Function to get the Leads Details Based upon the Lead Source
function leadSource_chart($user_id,$date_start,$end_date)
{
	global $adb,$query;
	
	$lead_source_qry="select leadsource from leadsource";
        $source_result=$adb->query($lead_source_qry);

        $source_array=array();

        while($row_source= $adb->fetch_array($source_result))
        {
                $source_array[]=$row_source['leadsource'];
                $name=$row_source['leadsource'];

                if($source_name!="") //passing Status values to graph
                        $source_name="$source_name,$name";
                else
                        $source_name="$name";

                $link_val="index.php?module=Leads&action=index&search_text=$name&search_field=leadsource&searchtype=BasicSearch&query=true";

                if($target_val!="")
                {
                        $target_val="$target_val,$link_val";
                }
                else
                {
                        $target_val="$link_val";
                }
        }

        $where= " and crmentity.smownerid=".$user_id." and crmentity.modifiedtime between '%".$date_start."%' and '%".$end_date."%'" ;
        $query.=$where;

        $result=$adb->query($query);
        $no_of_rows=$adb->num_rows($result);
        $lead_aray=$adb->fetch_array($result);

        $source_count_array[]=array();

        if($no_of_rows!=0)
        {
                while($row = $adb->fetch_array($result))
                {
                        $lead_source= $row['leadsource'];
                        $source_count_array[$lead_source]++;
                }
        }
        $total=0;

	$lead_source_table="<table border=0 cellspacing=1 cellpadding=3><tr>
                                <th>Lead Status</th><th>Total</th></tr>";
        for($i=0;$i<count($source_array);$i++)
        {
                $source=$source_array[$i];

                if(!isset($source_count_array[$source]))
                {
                        $source_count_array[$source]=0;
                }

                $val=$source_count_array[$source];
		 if($source_val!="") //passing dnloads values to graph
                        $source_val="$source_val,$val";
                else
                        $source_val="$val";

		$lead_source_table.="<tr><td>$source</td><td>$val</td></tr>";
                $total +=$val;
        }
	$lead_source_table.="<tr><td>Total</td><td>$total </td></tr>
                                </table>";


        $title_of_graph ="Leads total by Source is $total";

        $Lead_source_val=array($source_name,$source_val,$title_of_graph,$target_val,$lead_source_table);
        return $Lead_source_val;
}


function lead_by_industry($user_id,$date_start,$end_date)
{
	
	global $adb,$query;
	global $days,$date_array,$period_type;
	$insdustry_query="select * from industry";

        $where= " and crmentity.smownerid=".$user_id." and crmentity.modifiedtime between '%".$date_start."%'
and '%".$end_date."%'" ;
        $query.=$where;
        $result=$adb->query($query);
        $no_of_rows=$adb->num_rows($result);
        $industry_count_array=array();
	$industry_name_array=array();
	$count_by_date[]=array();
	$industry_tot_cnt_array=array();

	$industry_name_val="";
	$industry_cnt_crtd_date="";

        if($no_of_rows!=0)
        {
                while($row = $adb->fetch_array($result))
                {
			$industry_name= $row['industry'];
			if($industry_name=="")
				$industry_name="Un Assigned";

			$crtd_time=$row['createdtime'];
			$crtd_time_array=explode(" ",$crtd_time);
			$crtd_date=$crtd_time_array[0];	
	
			if(!isset($industry_tot_cnt_array[$crtd_date]))
				$industry_tot_cnt_array[$crtd_date]=0;

			$industry_tot_cnt_array[$crtd_date]+=1;

			if (in_array($industry_name,$industry_name_array) == false)
			{
				array_push($industry_name_array,$industry_name); // getting all the unique industry into the array
			}
			if(!isset($industry_count_array[$industry_name]))
				$industry_count_array[$industry_name]=0;
			$industry_count_array[$industry_name]++;

			if(!isset($count_by_date[$industry_name][$crtd_date]))

				$count_by_date[$industry_name][$crtd_date]=0;

			$count_by_date[$industry_name][$crtd_date]+=1;
                }
		$lead_by_industry_cnt=count($industry_name_array);
		
		if($lead_by_industry_cnt!=0)
		{		
			$url_string="";
		
			$industry_cnt_table="<table border=0 cellspacing=1 cellpadding=3><tr>
                                <th>Industry</th>";
							
			//Assigning the Header values to the Graph
			for($i=0; $i<$days; $i++)
			{
				$tdate=$date_array[$i];
				$values=Graph_n_table_format($period_type,$tdate);	
				$table_format=$values[1];
				$industry_cnt_table.= "<th>$table_format</th>";
			}
			$industry_cnt_table .= "<th>Total</th></tr>" ;				
	
			for ($i=0;$i<count($industry_name_array); $i++)
			{
				$industry=$industry_name_array[$i];
				if($industry=="")
					$industry="Un Assigned";
			
				$industry_cnt_table .= "<tr><td>$industry</td>";

				$industry_cnt_crtd_date="";
				for($j=0;$j<$days;$j++)
				{
					$tdate=$date_array[$j];

					if (!isset($count_by_date[$industry][$tdate]))
					{
						$count_by_date[$industry][$tdate]="0"; 
					}
					$cnt_by_date=$count_by_date[$industry][$tdate];
					$industry_cnt_table .= "<td>$cnt_by_date </td>";	

					$values=Graph_n_table_format($period_type,$tdate);
					$graph_format=$values[0];
					$table_format=$values[1];

					//passing the created dates to graph
					if($industry_graph_date!="")
						$industry_graph_date="$industry_graph_date,$graph_format";
					else
						$industry_graph_date="$graph_format";
			
					//passing the industry count by date to graph
					if($industry_cnt_crtd_date!="")
						$industry_cnt_crtd_date.=",$cnt_by_date";
					else
						$industry_cnt_crtd_date="$cnt_by_date";
				}

				$industry_count_val=$industry_count_array[$industry];

				$tot_industry_cnt=array_sum($count_by_date[$industry]);
				 $industry_cnt_table .= "<td align=center>$tot_industry_cnt</td></tr>";

				//Passing industry name to graph
				if($industry_name_val!="") $industry_name_val.=",$industry";
				else $industry_name_val="$industry";
				//Passing count to graph
				if($industry_cnt_val!="") $industry_cnt_val.=",$industry_count_val";
				else $industry_cnt_val="$industry_count_val";	

				if($i==0)
					$urlstring .=$industry_cnt_crtd_date;
				else
					$urlstring .="K".$industry_cnt_crtd_date;
			}
			 $industry_cnt_table .="</tr><tr><td class=\"$class\">Total</td>";
			for($k=0; $k<$days;$k++)
			{
				$tdate=$date_array[$k];
				if(!isset($industry_tot_cnt_array[$tdate]))
					$industry_tot_cnt_array[$tdate]="0";
				$tot= $industry_tot_cnt_array[$tdate];
				if($period_type!="yday")
					$industry_cnt_table.="<td>$tot</td>";
			}
			$cnt_total=array_sum($industry_tot_cnt_array);
			$industry_cnt_table.="<td align=\"center\" class=\"$class\">$cnt_total</td></tr></table>";
			$industry_cnt_table.="</table>";
			$title_of_graph=" Leads by industry : $cnt_total";
			$x_title="Industry";
			$y_title="Count";

			$Lead_industry_val=array($industry_name_val,$industry_cnt_val,$title_of_graph,$target_val,$industry_graph_date,$urlstring,$industry_cnt_table);
  		      	return $Lead_industry_val;
		}
		else
		{
			$data=0;
		}
        }
	else
	{
		$data=0;
	}
	return $data;
}
//Lead Status Values
$leadStatus_values=leadStatus_chart($user_id,$date_start,$end_date);
$status_name=$leadStatus_values[0];
$status_val=$leadStatus_values[1];
$status_graph_title=$leadStatus_values[2];
$status_target_val=$leadStatus_values[3];
$lead_status_table=$leadStatus_values[4];

//Lead Source Values
$leadSource_values=leadSource_chart($user_id,$date_start,$end_date);
$source_name=$leadSource_values[0];
$source_val=$leadSource_values[1];
$source_graph_title=$leadSource_values[2];
$source_target_val=$leadSource_values[3];
$lead_source_table=$leadSource_values[4];

//Lead Industry Values
$leadIndustry_values=lead_by_industry($user_id,$date_start,$end_date);
if($leadIndustry_values!=0)
{
	$industry_name_val=$leadIndustry_values[0];
	$industry_cnt_val=$leadIndustry_values[1];
	$industry_graph_title=$leadIndustry_values[2];
	$indus_target_val=$leadIndustry_values[3];	
	$industry_graph_date=$leadIndustry_values[4];
	$urlstring=$leadIndustry_values[5];
	$industry_cnt_table=$leadIndustry_values[6];
}
	echo <<< END
		<table border="0" cellspacing="0" cellpadding="5" ><tr><td>
	   <img src="modules/Dashboard/horizontal_bargraph.php?refer_code=$status_name&referdata=$status_val&target=$status_target_val&width=350&height=400&top=20&left=110&title=$status_graph_title" border="0">
        </td><td>
	   <img src="modules/Dashboard/pie_graph.php?refer_code=$status_name&referdata=$status_val&target=$target_val&width=650&height=300&title=$status_graph_title" border="0">
        </td></tr>
        <tr><td>
        	$lead_status_table
      	</td></tr>
	<tr>
	<td>
       	<img src="modules/Dashboard/horizontal_bargraph.php?refer_code=$source_name&referdata=$source_val&target=$source_target_val&width=350&height=400&top=20&left=120&title=$source_graph_title" border="0">
        </td><td>
          <img src="modules/Dashboard/pie_graph.php?refer_code=$source_name&referdata=$source_val&width=630&height=300&title=$source_graph_title" border="0">
        </td></tr>
	<tr><td>
		$lead_source_table
	</td></tr>

END;

	if($leadIndustry_values!=0)
	{
	echo <<< END

	<tr><td>
		
	        <img src="modules/Dashboard/line_graph.php?refer_code=$industry_graph_date&referdata=$industry_name_val&width=530&height=300&datavalue=$urlstring&title=$industry_graph_title" border="0">
	</td><td>
	<img src="modules/Dashboard/pie_graph.php?refer_code=$industry_name_val&referdata=$industry_cnt_val&target=$indus_target_val&width=530&height=300&title=$industry_graph_title" border="0">
	</td></tr>
	<tr><td>
		
        <img src="modules/Dashboard/horizontal_bargraph.php?refer_code=$industry_name_val&referdata=$industry_cnt_val&width=350&height=400&top=20&left=110&title=$industry_graph_title" border="0">
	</td><td>
		
        <img src="modules/Dashboard/vertical_bargraph.php?refer_code=$industry_name_val&referdata=$industry_cnt_val&width=450&height=300&top=20&left=110&title=$industry_graph_title" border="0">
	</td></tr>
	<tr><td>
	$industry_cnt_table
	</td></tr>
        </table>
END;

	}


?>
