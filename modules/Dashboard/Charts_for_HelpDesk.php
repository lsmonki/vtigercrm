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



$user_id=$current_user->id;

function helpDeskStatus_chart($user_id,$date_start,$end_date)
{
	global $adb,$period_type;

	$no_days_dates=get_days_n_dates($date_start,$end_date);
	$days=$no_days_dates[0];
	$date_array=$no_days_dates[1];

	$ticket_stat_qry="select ticketstatus from ticketstatus";
        $status_result=$adb->query($ticket_stat_qry);
        $status_array=array();


        while($row_status= $adb->fetch_array($status_result))
        {
                $status_array[]=$row_status['ticketstatus'];
                $name=$row_status['ticketstatus'];

                if($status_name_graph!="") //passing Status values to graph
                        $status_name_graph="$status_name_graph,$name";
                else
                        $status_name_graph="$name";
        }

	
	$query ="select troubletickets.status,crmentity.createdtime from troubletickets inner join ticketcf on ticketcf.ticketid = troubletickets.ticketid inner join crmentity on crmentity.crmid=troubletickets.ticketid left join ticketgrouprelation on troubletickets.ticketid=ticketgrouprelation.ticketid left join groups on groups.groupname=ticketgrouprelation.groupname left join contactdetails on troubletickets.parent_id=contactdetails.contactid left join account on account.accountid=troubletickets.parent_id left join users on crmentity.smownerid=users.id and troubletickets.ticketid = ticketcf.ticketid where crmentity.deleted=0 and crmentity.smownerid=1 and (crmentity.createdtime between '% ".$date_start."%' and '%".$end_date."%') order by crmentity.createdtime ;";


        $result=$adb->query($query);
        $no_of_rows=$adb->num_rows($result);

        $status_count_array=array();
	$status_date_cnt_array[]=array();

        if($no_of_rows!=0)
        {
		while($row = $adb->fetch_array($result))
                {
			$tdate=$row['createdtime'];
                        $t_date=explode(" ",$tdate);
                        $tdate=$t_date[0];
	
                        $ticket_status= strtolower($row['status']);

			$status_count_array[$ticket_status]++;

			if(!isset($status_totcnt_array[$tdate]))
	                	$status_totcnt_array[$tdate]="0";

			


			for($i=0;$i<count($status_array);$i++)
			{
				$status_name=strtolower($status_array[$i]);

				if(!isset($status_date_cnt_array[$status_name][$tdate]))
					$status_date_cnt_array[$status_name][$tdate]=0;
				if(stristr($ticket_status,$status_name)!="")
				{
					$status_date_cnt_array[$status_name][$tdate]+=1;
				}
			}
			$status_totcnt_array[$tdate]+=1;

                }
	}

	$ticket_status_table="<table border=1 cellspacing=1 cellpadding=3><tr>
				<th>Ticket Status</th>";

	for($i=0;$i<$days;$i++)
	{	
		for($j=0;$j<count($status_array);$j++)
		{
			$status_name=strtolower($status_array[$j]);
			if(!isset($status_date_cnt_array[$status_name][$tdate]))
				$status_date_cnt_array[$status_name][$tdate]="0";

		}
		$date_val=explode("-",$date_array[$i]);
		if($period_type=="month")   //to get the table format dates
		{
			$table_format=date("j",mktime(0,0,0,date($date_val[1]),(date($date_val[2])),date($date_val[0])));
			$graph_format=date("D",mktime(0,0,0,date($date_val[1]),(date($date_val[2])),date($date_val[0])));
		}
		else if($period_type=="week")
		{
			$table_format=date("d/m",mktime(0,0,0,date($date_val[1]),(date($date_val[2])),date($date_val[0])));
			$graph_format=date("D",mktime(0,0,0,date($date_val[1]),(date($date_val[2])),date($date_val[0])));
		}
		else if($period_type=="yday")
		{
			$table_format=date("j",mktime(0,0,0,date($date_val[1]),(date($date_val[2])),date($date_val[0])));
			$graph_format=$table_format;
		}
		 $ticket_status_table .= "<th>$table_format</th>";
		if($status_graph_date!="") 
			$status_graph_date="$status_graph_date,$graph_format";
		else 
			$status_graph_date="$graph_format";
		$tdate=$date_array[$i];
	}
	$ticket_status_table .= "<th>Total</th></tr>" ;

        $total=0;	
	$urlstring ='';

//	$tot_status_cnt=0;
        for($i=0;$i<count($status_array);$i++)
        {

                $status=strtolower($status_array[$i]);
		
                if(!isset($status_count_array[$status]))
                {
                        $status_count_array[$status]=0;
                }

                $val=$status_count_array[$status];


		if($status_val!="") //passing dnloads values to graph
                        $status_val="$status_val,$val";
                else
                        $status_val="$val";

                $total +=$val;
		$ticket_cnt = "";
		$ticket_status_table .= "<tr><td>".$status_array[$i]."</td>";

		for($j=0; $j<$days; $j++)
		{
			$tdate=$date_array[$j];
			if(!isset($status_date_cnt_array[$status][$tdate]))
				$status_date_cnt_array[$status][$tdate]=0;
			$cnt_value=$status_date_cnt_array[$status][$tdate];	
			
			if($ticket_cnt!='')
                                $ticket_cnt .=",".$cnt_value;
                        else
                                $ticket_cnt = $cnt_value;

			$ticket_status_table .= "<td>$cnt_value </td>";

		}
		$tot_status_cnt=array_sum($status_date_cnt_array[$status]);
		$ticket_status_table .= "<td>$tot_status_cnt </td></tr>";
		if($i==0)
                 $urlstring .=$ticket_cnt;
                else
                 $urlstring .="K".$ticket_cnt;

	
        }
	$ticket_status_table .="</tr><tr><td class=\"$class\">Total</td>";

	for($i=0;$i<$days;$i++)
	{
		$tdate=$date_array[$i];
		if(!isset($status_totcnt_array[$tdate]))
			$status_totcnt_array[$tdate]="0";
		$tot= $status_totcnt_array[$tdate];
		if($period_type!="yday")
			$ticket_status_table.="<td>$tot</td>";
	}
	$cnt_total=array_sum($status_totcnt_array);
	$ticket_status_table.="<td align=\"center\" class=\"$class\">$cnt_total</td></tr></table>";


	$ticket_status_table .= "</table>";
	
  	$value=explode("&",$urlstring);
        for($i=0;$i<count($value);$i++)
        {
              	$data=$value[$i];
                $graph_data=explode(",",$data);
        }

        $title_of_graph ="Tickets total by Status is $total";

	 echo <<< END
                <table border="1" cellspacing="0" cellpadding="5" >
                <tr>
                <td>
                <img src="modules/Dashboard/pie_graph.php?refer_code=$status_name_graph&referdata=$status_val&target=$target_val&width=530&height=300&title=$title_of_graph" border="0">
                </td>
                <td>
                <img src="modules/Dashboard/line_graph.php?refer_code=$status_graph_date&target=$target_val&width=530&height=300&title=$title_of_graph&datay=$datay&data1y=$data1y&data2y=$data2y&data3y=$data3y&datavalue=$urlstring&referdata=$status_name_graph" border="0">
                </td></tr>
		<tr>
		<td>
                <img src="modules/Dashboard/horizontal_bargraph.php?refer_code=$status_name_graph&referdata=$status_val&target=$target_val&width=530&height=300&top=20&left=120&title=$title_of_graph" border="0">
		
		</td>
		<td>
		$ticket_status_table
		</td>
		</tr>
                </table>
END;

}

helpDeskStatus_chart($user_id,$date_start,$end_date);


?>
