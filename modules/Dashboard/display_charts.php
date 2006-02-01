<?php

//	include("modules/Dashboard/Charts_for_Invoice.php");
	include("modules/Dashboard/Entity_charts.php");
        include("modules/Dashboard/horizontal_bargraph.php");
        include("modules/Dashboard/vertical_bargraph.php");
        include("modules/Dashboard/pie_graph.php");
	
global $tmp_dir;


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


$leads_query="select crmentity.crmid,crmentity.createdtime, leaddetails.*, crmentity.smownerid, leadscf.* from leaddetails inner join crmentity on crmentity.crmid=leaddetails.leadid inner join leadsubdetails on leadsubdetails.leadsubscriptionid=leaddetails.leadid inner join leadaddress on leadaddress.leadaddressid=leadsubdetails.leadsubscriptionid inner join leadscf on leaddetails.leadid = leadscf.leadid left join leadgrouprelation on leadscf.leadid=leadgrouprelation.leadid left join groups on groups.groupname=leadgrouprelation.groupname where crmentity.deleted=0 and leaddetails.converted=0";

$leads_by="leadsource";
$leads_title="Leads By Source";
$module="Leads";
$where="";

$cache_file_name=abs(crc32($user_id))."_".$leads_by."_".crc32($date_start.$end_date).".png";

$cache_file_name=$cache_file_name;

$html_imagename=$leads_by;
$Leads_by_source=module_Chart($user_id,$date_start,$end_date,$leads_query,$leads_by,$leads_title,$where,$module);

if($Leads_by_source!=0)
{
	$lead_name_val=$Leads_by_source[0];
	$lead_cnt_val=$Leads_by_source[1];
	$lead_graph_title=$Leads_by_source[2];
	$target_val=$Leads_by_source[3];	
	$lead_graph_date=$Leads_by_source[4];
	$urlstring=$Leads_by_source[5];
	$lead_cnt_table=$Leads_by_source[6];

	$width=350;
	$height=400;
	$top=30;
	$left=140;
	$bottom=120;
	$title=$lead_graph_title;

	echo <<< END
		<table border=0 cellspacing=0 cellpadding=1>
		<tr><td>	
END;

 	render_graph($tmp_dir."hor_".$cache_file_name,$html_imagename."_hor",$lead_cnt_val,$lead_name_val,$width,$height,$left,$right,$top,$bottom,$title,$target_val,"horizontal");

	echo <<< END
		</td><td>
END;
 	render_graph($tmp_dir."vert_".$cache_file_name,$html_imagename."_vert",$lead_cnt_val,$lead_name_val,$width,$height,$left,$right,$top,$bottom,$title,$target_val,"vertical");

	echo <<< END
		</td></tr>
		<tr><td>
END;

 	render_graph($tmp_dir."pie_".$cache_file_name,$html_imagename."_pie",$lead_cnt_val,$lead_name_val,$width,$height,$left,$right,$top,$bottom,$title,$target_val,"pie");
	echo <<< END
		</td></tr>
		<tr><td>

        <img src="modules/Dashboard/accumulated_bargraph.php?refer_code=$lead_graph_date&referdata=$lead_name_val&width=350&height=600&left=110&datavalue=$urlstring&title=$lead_graph_title" border="0">

	
        </td></tr>
		</table>
END;
	//accumlated_graph($lead_graph_date,$lead_name_val,$urlstring,$lead_graph_title,$target_val,350,600,110,$right,$top,$bottom)

}


function render_graph($cache_file_name,$html_imagename,$cnt_val,$name_val,$width,$height,$left,$right,$top,$bottom,$title,$target_val,$graph_type)
{
	if (!file_exists($cache_file_name) || !file_exists($cache_file_name.'.map')) 
	{
		if($graph_type=="horizontal")
		{
			horizontal_graph($cnt_val,$name_val,$width,$height,$left,$right,$top,$bottom,$title,$target_val,$cache_file_name,$html_imagename);
		}
		else if($graph_type=="vertical")	
		{
			vertical_graph($cnt_val,$name_val,$width,$height,$left,$right,$top,$bottom,$title,$target_val,$cache_file_name,$html_imagename);
		}
		else if($graph_type=="pie")
		{
			pie_chart($cnt_val,$name_val,$width,$height,$left,$right,$top,$bottom,$title,$target_val,$cache_file_name,$html_imagename);
			
		}
	}
	else
	{
		$imgMap_fp = fopen($cache_file_name.'.map', "rb");
		$imgMap = fread($imgMap_fp, filesize($cache_file_name.'.map'));
		fclose($imgMap_fp);
		$base_name_cache_file=basename($cache_file_name);
		$ccc="cache/images/".$base_name_cache_file;
		$return = "\n$imgMap\n";
		$return .= "<img src=$ccc ismap usemap=#$html_imagename border='0'>";
		echo $return;
	}
}

?>
