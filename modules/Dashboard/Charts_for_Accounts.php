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

 $no_days_dates=get_days_n_dates($date_start,$end_date);
        $days=$no_days_dates[0];
        $date_array=$no_days_dates[1];

$user_id=$current_user->id;

$query= "select crmentity.crmid, account.*,crmentity.smownerid,crmentity.createdtime, accountscf.* from account left join users on users.id=crmentity.smownerid inner join crmentity on crmentity.crmid=account.accountid inner join accountbillads on account.accountid=accountbillads.accountaddressid inner join accountshipads on account.accountid=accountshipads.accountaddressid inner join accountscf on account.accountid = accountscf.accountid left join accountgrouprelation on accountscf.accountid=accountgrouprelation.accountid left join groups on groups.groupname=accountgrouprelation.groupname where crmentity.deleted=0";

function account_by_industry($user_id,$date_start,$end_date)
{

        global $adb,$query;
        global $days,$date_array,$period_type;
        $insdustry_query="select * from industry";

        $where= " and crmentity.smownerid=".$user_id." and crmentity.createdtime between '%".$date_start."%'
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
                $acnt_by_industry_cnt=count($industry_name_array);

                if($acnt_by_industry_cnt!=0)
                {
                        $url_string="";

                        $industry_cnt_table="<table border=0 cellspacing=1 cellpadding=3><tr>
                                <th>Ticket Status</th>";

                        //Assigning the Header values to the Graph
                        for($i=0; $i<$days; $i++)
                        {
                                $tdate=$date_array[$i];
                                $values=Graph_n_table_format($period_type,$tdate);
				$graph_format=$values[0];
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

					if($i==0)
					{					
	                                        $values=Graph_n_table_format($period_type,$tdate);
        	                                $graph_format=$values[0];
                	                        $table_format=$values[1];

 						//passing the created dates to graph
	                                        if($industry_graph_date!="")
        	                                        $industry_graph_date="$industry_graph_date,$graph_format";
                	                        else
                        	                        $industry_graph_date="$graph_format";

					}
					
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
                        $title_of_graph=" Accounts by industry : $cnt_total";
                        $x_title="Industry";
                        $y_title="Count";

                        $Acnt_industry_val=array($industry_name_val,$industry_cnt_val,$title_of_graph,$target_val,$industry_graph_date,$urlstring,$industry_cnt_table);
                        return $Acnt_industry_val;
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

$acntIndustry_values=account_by_industry($user_id,$date_start,$end_date);
if($acntIndustry_values!=0)
{
        $industry_name_val=$acntIndustry_values[0];
        $industry_cnt_val=$acntIndustry_values[1];
        $industry_graph_title=$acntIndustry_values[2];
        $indus_target_val=$acntIndustry_values[3];
        $industry_graph_date=$acntIndustry_values[4];
        $urlstring=$acntIndustry_values[5];
        $industry_cnt_table=$acntIndustry_values[6];
}


	if($acntIndustry_values!=0)
        {
	echo <<< END

	<table border="0" cellspacing="0" cellpadding="5" >

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
	else
	{
		echo "<h3> No Data Available </h3>";
	}


?>
