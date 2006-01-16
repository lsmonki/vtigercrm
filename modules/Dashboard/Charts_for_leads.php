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



//function to get the Graph for Lead Source

function leadStatus_chart($user_id,$date_start,$end_date)
{
	global $adb;

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

		//array_push($aTargets[$stage], "index.php?module=Potentials&action=ListView&date_closed=$month&sales_stage=".urlencode($stage)."&query=true");

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



	//$query = getListQuery("Leads");

	$query ="select crmentity.crmid, leaddetails.leadstatus, crmentity.smownerid, leadscf.* from leaddetails inner join crmentity on crmentity.crmid=leaddetails.leadid inner join leadsubdetails on leadsubdetails.leadsubscriptionid=leaddetails.leadid inner join leadaddress on leadaddress.leadaddressid=leadsubdetails.leadsubscriptionid inner join leadscf on leaddetails.leadid = leadscf.leadid left join leadgrouprelation on leadscf.leadid=leadgrouprelation.leadid left join groups on groups.groupname=leadgrouprelation.groupname where crmentity.deleted=0 and leaddetails.converted=0";

	$where= " and crmentity.smownerid=".$user_id." and crmentity.modifiedtime between '%".$date_start."%' and '%".$end_date."%'" ;
	$query.=$where;

	$result=$adb->query($query);
	$no_of_rows=$adb->num_rows($result);
	$lead_aray=$adb->fetch_array($result);

	$status_count_array[]=array();

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

		$total +=$val;
	}


	$title_of_graph ="Leads total by Status is $total";

	echo <<< END

		<table border="0" cellspacing="0" cellpadding="5" ><tr><td>
		<img src="modules/Dashboard/horizontal_bargraph.php?refer_code=$status_name&referdata=$status_val&target=$target_val&width=350&height=400&top=20&left=110&title=$title_of_graph" border="0">
		</td></tr>
		</table>
END;

}

function leadSource_chart($user_id,$date_start,$end_date)
{
	//global $user_id,$date_start,$end_date,$adb;
	global $adb;
	
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

                //array_push($aTargets[$stage], "index.php?module=Potentials&action=ListView&date_closed=$month&sales_stage=".urlencode($stage)."&query=true");

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
	
	 $query ="select crmentity.crmid, leaddetails.leadsource, crmentity.smownerid, leadscf.* from leaddetails inner join crmentity on crmentity.crmid=leaddetails.leadid inner join leadsubdetails on leadsubdetails.leadsubscriptionid=leaddetails.leadid inner join leadaddress on leadaddress.leadaddressid=leadsubdetails.leadsubscriptionid inner join leadscf on leaddetails.leadid = leadscf.leadid left join leadgrouprelation on leadscf.leadid=leadgrouprelation.leadid left join groups on groups.groupname=leadgrouprelation.groupname where crmentity.deleted=0 and leaddetails.converted=0";

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

                $total +=$val;
        }


        $title_of_graph ="Leads total by Source is $total";

        echo <<< END

                <table border="0" cellspacing="0" cellpadding="5" ><tr><td>
                <img src="modules/Dashboard/pie_graph.php?refer_code=$source_name&referdata=$source_val&target=$target_val&width=630&height=300&title=$title_of_graph" border="0">

		
                </td></tr>
                </table>
END;



}


leadStatus_chart($user_id,$date_start,$end_date);
leadSource_chart($user_id,$date_start,$end_date);
		// Graph test


?>
