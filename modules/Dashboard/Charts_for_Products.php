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


$products_query="select distinct(crmentity.crmid),crmentity.createdtime,products.*, productcf.* from products inner join crmentity on crmentity.crmid=products.productid left join productcf on products.productid = productcf.productid left join seproductsrel on seproductsrel.productid = products.productid where crmentity.deleted=0";

function product_Chart($user_id,$date_start,$end_date)
{
	 global $adb,$products_query;
        global $days,$date_array,$period_type;

        $where= " and crmentity.smownerid=".$user_id." and crmentity.createdtime between '%".$date_start."%' and '%".$end_date."%'" ;
        $products_query.=$where;

        $result=$adb->query($products_query);
        $no_of_rows=$adb->num_rows($result);
        $catgry_count_array=array();
        $catgry_name_array=array();
        $count_by_date[]=array();
        $catgry_tot_cnt_array=array();

        $catgry_name_val="";
        $catgry_cnt_crtd_date="";

        if($no_of_rows!=0)
        {
                while($row = $adb->fetch_array($result))
                {
                        $catgry_name= $row['productcategory'];
                        if($catgry_name=="")
                                $catgry_name="Un Assigned";

                        $crtd_time=$row['createdtime'];
                        $crtd_time_array=explode(" ",$crtd_time);
                        $crtd_date=$crtd_time_array[0];

                        if(!isset($catgry_tot_cnt_array[$crtd_date]))
                                $catgry_tot_cnt_array[$crtd_date]=0;

                        $catgry_tot_cnt_array[$crtd_date]+=1;

                        if (in_array($catgry_name,$catgry_name_array) == false)
                        {
                                array_push($catgry_name_array,$catgry_name); // getting all the unique catgry into the array
                        }
                        if(!isset($catgry_count_array[$catgry_name]))
				$catgry_count_array[$catgry_name]=0;
                        $catgry_count_array[$catgry_name]++;

                        if(!isset($count_by_date[$catgry_name][$crtd_date]))

                                $count_by_date[$catgry_name][$crtd_date]=0;

                        $count_by_date[$catgry_name][$crtd_date]+=1;
                }
                $product_by_catgry_cnt=count($catgry_name_array);

                if($product_by_catgry_cnt!=0)
                {
                        $url_string="";

                        $catgry_cnt_table="<table border=0 cellspacing=1 cellpadding=3><tr>
                                <th>Product Category </th>";

                        //Assigning the Header values to the Graph
                        for($i=0; $i<$days; $i++)
                        {
                                $tdate=$date_array[$i];
                                $values=Graph_n_table_format($period_type,$tdate);
                                $graph_format=$values[0];
                                $table_format=$values[1];
                                $catgry_cnt_table.= "<th>$table_format</th>";

                        }
                        $catgry_cnt_table .= "<th>Total</th></tr>" ;

                        for ($i=0;$i<count($catgry_name_array); $i++)
                        {
                                $catgry=$catgry_name_array[$i];
                                if($catgry=="")
                                        $catgry="Un Assigned";

                                $catgry_cnt_table .= "<tr><td>$catgry</td>";
                                 $catgry_cnt_crtd_date="";
                                for($j=0;$j<$days;$j++)
				{
				 $tdate=$date_array[$j];

                                        if (!isset($count_by_date[$catgry][$tdate]))
                                        {
                                                $count_by_date[$catgry][$tdate]="0";
                                        }
                                        $cnt_by_date=$count_by_date[$catgry][$tdate];
                                        $catgry_cnt_table .= "<td>$cnt_by_date </td>";

                                        if($i==0)
                                        {
                                                $values=Graph_n_table_format($period_type,$tdate);
                                                $graph_format=$values[0];
                                                $table_format=$values[1];


                                                //passing the created dates to graph
                                                if($catgry_graph_date!="")
                                                        $catgry_graph_date="$catgry_graph_date,$graph_format";
                                                else
                                                        $catgry_graph_date="$graph_format";

                                        }

                                        //passing the catgry count by date to graph
                                        if($catgry_cnt_crtd_date!="")
                                                $catgry_cnt_crtd_date.=",$cnt_by_date";
                                        else
                                                $catgry_cnt_crtd_date="$cnt_by_date";
                                }

                                $catgry_count_val=$catgry_count_array[$catgry];

                                $tot_catgry_cnt=array_sum($count_by_date[$catgry]);
                                 $catgry_cnt_table .= "<td align=center>$tot_catgry_cnt</td></tr>";

                                //Passing catgry name to graph
                                if($catgry_name_val!="") $catgry_name_val.=",$catgry";
                                else $catgry_name_val="$catgry";
				 //Passing count to graph
                                if($catgry_cnt_val!="") $catgry_cnt_val.=",$catgry_count_val";
                                else $catgry_cnt_val="$catgry_count_val";

                                if($i==0)
                                        $urlstring .=$catgry_cnt_crtd_date;
                                else
                                        $urlstring .="K".$catgry_cnt_crtd_date;
                        }
                         $catgry_cnt_table .="</tr><tr><td class=\"$class\">Total</td>";
                        for($k=0; $k<$days;$k++)
                        {
                                $tdate=$date_array[$k];
                                if(!isset($catgry_tot_cnt_array[$tdate]))
                                        $catgry_tot_cnt_array[$tdate]="0";
                                $tot= $catgry_tot_cnt_array[$tdate];
                                if($period_type!="yday")
                                        $catgry_cnt_table.="<td>$tot</td>";
                        }
                        $cnt_total=array_sum($catgry_tot_cnt_array);
                        $catgry_cnt_table.="<td align=\"center\" class=\"$class\">$cnt_total</td></tr></table>";
                        $catgry_cnt_table.="</table>";
                        $title_of_graph=" Products by category : $cnt_total";
                        $x_title="Industry";
                        $y_title="Count";

                        $Prod_catgry_val=array($catgry_name_val,$catgry_cnt_val,$title_of_graph,$target_val,$catgry_graph_date,$urlstring,$catgry_cnt_table);
                        return $Prod_catgry_val;
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

$productIndustry_values=product_Chart($user_id,$date_start,$end_date);
if($productIndustry_values!=0)
{
        $catgry_name_val=$productIndustry_values[0];
        $catgry_cnt_val=$productIndustry_values[1];
        $catgry_graph_title=$productIndustry_values[2];
        $indus_target_val=$productIndustry_values[3];
        $catgry_graph_date=$productIndustry_values[4];
        $urlstring=$productIndustry_values[5];
        $catgry_cnt_table=$productIndustry_values[6];
}


        if($productIndustry_values!=0)
        {
        echo <<< END

        <table border="0" cellspacing="0" cellpadding="5" >

        <tr><td>
                <img src="modules/Dashboard/line_graph.php?refer_code=$catgry_graph_date&referdata=$catgry_name_val&width=530&height=300&datavalue=$urlstring&title=$catgry_graph_title" border="0">
        </td><td>
        <img src="modules/Dashboard/pie_graph.php?refer_code=$catgry_name_val&referdata=$catgry_cnt_val&target=$indus_target_val&width=530&height=300&title=$catgry_graph_title" border="0">
        </td></tr>
        <tr><td>

        <img src="modules/Dashboard/horizontal_bargraph.php?refer_code=$catgry_name_val&referdata=$catgry_cnt_val&width=350&height=400&top=20&left=110&title=$catgry_graph_title" border="0">
        </td><td>

        <img src="modules/Dashboard/vertical_bargraph.php?refer_code=$catgry_name_val&referdata=$catgry_cnt_val&width=450&height=300&top=20&left=110&title=$catgry_graph_title" border="0">
        </td></tr>
	<tr><td>
	<img src="modules/Dashboard/accumulated_graph.php?refer_code=$catgry_graph_date&referdata=$catgry_name_val&width=350&height=400&left=110&datavalue=$urlstring&title=$catgry_graph_title" border="0">		
	</td></tr>
        <tr><td>
        $catgry_cnt_table
        </td></tr>
  </table>
END;

        }
        else
        {
                echo "<h3> No Data Available </h3>";
        }




?>
