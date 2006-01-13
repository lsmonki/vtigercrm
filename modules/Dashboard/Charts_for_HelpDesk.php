<?php

require_once('include/utils/utils.php');
require_once('include/database/PearDatabase.php');
require_once('include/utils/CommonUtils.php');


if(isset($_REQUEST['date_start']) && $_REQUEST['date_start'] !="")
{
        $date_start=$_REQUEST['date_start'];
}
else
{
        $date_start ="2000-01-01";
}
if(isset($_REQUEST['end_date']) && $_REQUEST['end_date']!="")
{
        $end_date=ltrim(rtrim($_REQUEST['end_date']));
}
else
{
        $end_date="2010-01-01";
}


$user_id=$current_user->id;


function helpDeskStatus_chart($user_id,$date_start,$end_date)
{
	global $adb;

	$x=get_days_n_dates($st,$en);
	$days=$x[0];
	echo "<h2> No of Days$days </h2>";

	$ticket_stat_qry="select ticketstatus from ticketstatus";
        $status_result=$adb->query($ticket_stat_qry);

        $status_array=array();

        while($row_status= $adb->fetch_array($status_result))
        {
                $status_array[]=$row_status['ticketstatus'];
                $name=$row_status['ticketstatus'];

                if($status_name!="") //passing Status values to graph
                        $status_name="$status_name,$name";
                else
                        $status_name="$name";

                $link_val="index.php?module=Leads&action=index&search_text=$name&search_field=ticketstatus&searchtype=BasicSearch&query=true";

                if($target_val!="")
                {
                        $target_val="$target_val,$link_val";
                }
                else
                {
                        $target_val="$link_val";
                }

        }
	
	$query=getListQuery("HelpDesk");

        $where= " and crmentity.smownerid=".$user_id." and ((crmentity.modifiedtime between '%".$date_start."%' and '%".$end_date."%') or (crmentity.createdtime between '%".$date_start."%' and '%".$end_date."%'))" ;
       $query.=$where;


        $result=$adb->query($query);
        $no_of_rows=$adb->num_rows($result);
        $ticket_aray=$adb->fetch_array($result);

        $status_count_array[]=array();

        if($no_of_rows!=0)
        {
                while($row = $adb->fetch_array($result))
                {
                        $ticket_status= $row['status'];
                        $status_count_array[$ticket_status]++;
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

                $total +=$val;
        }


        $title_of_graph ="Tickets total by Status is $total";

        echo <<< END

                <table border="0" cellspacing="0" cellpadding="5" ><tr><td>
                <img src="modules/Dashboard/pie_graph.php?refer_code=$status_name&referdata=$status_val&target=$target_val&width=530&height=300&title=$title_of_graph" border="0">

		
                </td></tr>
                </table>
END;


	
}

helpDeskStatus_chart($user_id,$date_start,$end_date);


?>
