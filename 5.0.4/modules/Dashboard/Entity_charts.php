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
include("modules/Dashboard/horizontal_bargraph.php");
include("modules/Dashboard/vertical_bargraph.php");
include("modules/Dashboard/pie_graph.php");

//To get the vtiger_account names

/* Function to get the Account name for a given vtiger_account id
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */

function get_account_name($acc_id)
{
	global $adb;
	$acc_qry="select accountname from vtiger_account where accountid =".$acc_id;
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

	global $adb,$current_user;
	global $days,$date_array,$period_type;

	//$where= " and vtiger_crmentity.smownerid=".$user_id." and vtiger_crmentity.createdtime between '".$date_start."' and '".$end_date."'" ;
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
			if($graph_for == "productname")
			{
				if($row['qtyinstock'] =='')
					$mod_count_array[$mod_name] = 1;
				else
					$mod_count_array[$mod_name]=$row['qtyinstock'];

			}
			else
			{
				if(!isset($mod_count_array[$mod_name]))
					$mod_count_array[$mod_name]=0;
				$mod_count_array[$mod_name]++;
			}

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

			//Assigning the Header values to the vtiger_table and giving the dates as graphformat 
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
				$id_name = "";
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
				if($graph_for =="smownerid")
				{
					$name_val=get_assigned_user_name($name);
					if($name_val!="")
						$name=$name_val;
				}
				if($graph_for =="product_id" || $graph_for =="productid")
				{
					$query = "SELECT productname FROM vtiger_products WHERE productid='".$name."'";
					$result = $adb->query($query);
					$name_val = $adb->query_result($result,0,"productname");
					if($name_val!="")
						$name=$name_val;
				}
				if($graph_for =="purchaseorderid")
				{
					$query = "SELECT subject FROM vtiger_purchaseorder WHERE purchaseorderid='".$name."'";
					$result = $adb->query($query);
					$name_val = $adb->query_result($result,0,"subject");
					$id_name = $name;
					if($name_val!="")
						$name=$name_val;
				}
				if($graph_for =="quoteid")
				{
					$query = "SELECT subject FROM vtiger_quotes WHERE quoteid='".$name."'";
					$result = $adb->query($query);
					$name_val = $adb->query_result($result,0,"subject");
					$id_name = $name;
					if($name_val!="")
						$name=$name_val;
				}
				if($graph_for =="invoiceid")
				{
					$query = "SELECT subject FROM vtiger_invoice WHERE invoiceid='".$name."'";
					$result = $adb->query($query);
					$name_val = $adb->query_result($result,0,"subject");
					$id_name = $name;
					if($name_val!="")
						$name=$name_val;
				}
	if($graph_for =="campaignid")
				{
					//this will return the list of the names of the campaign``:w for the y-axis
					$query = "SELECT campaignname FROM vtiger_campaign WHERE campaignid='".$name."'";
					$result = $adb->query($query);
					$name_val = $adb->query_result($result,0,"campaignname");
					$id_name = $name;
					if($name_val!="")
						$name=$name_val;
				}
				if($graph_for =="contactid")
				{
					$query = "SELECT lastname FROM vtiger_contactdetails WHERE contactid='".$name."'";
					$result = $adb->query($query);
					$name_val = $adb->query_result($result,0,"lastname");
					if($name_val!="")
						$name=$name_val;
				}

				//Passing name to graph
				if($mod_name_val!="") $mod_name_val.="::$name";
				else $mod_name_val="$name";


				//Passing count to graph
				if($mod_cnt_val!="") $mod_cnt_val.="::$mod_count_val";
				else $mod_cnt_val="$mod_count_val";	

				if($module!="")
				{
					//Check for Ticket Priority 
					if(($graph_type=="ticketsbypriority"))
					{
						$graph_for="ticketpriorities";
					}

					//added to get valid url in dashbord for tickets by team
					if($graph_for == "ticketgroupname" || $graph_for == "groupname") $graph_for = "smownerid";

					if($graph_for == "accountid") $graph_for = "account_id";
					$cvid = getCvIdOfAll($module);
					if($module == "Home")
					{
						$cvid = getCvIdOfAll($name);
						$link_val="index.php?module=".$name."&action=ListView&from_homepagedb=true&type=dbrd&query=true&owner=".$current_user->user_name."&viewname=".$cvid;
					}
					else if($module == "Contacts" || ($module=="Products" && ($graph_for == "quoteid" || $graph_for == "invoiceid" || $graph_for == "purchaseorderid")))
						$link_val="index.php?module=".$module."&action=ListView&from_dashboard=true&type=dbrd&query=true&".$graph_for."=".$id_name."&viewname=".$cvid;
					else
						$link_val="index.php?module=".$module."&action=index&from_dashboard=true&search_text=".$name."&search_field=".$graph_for."&searchtype=BasicSearch&query=true&type=entchar&viewname=".$cvid;
					if($graph_for == "account_id") $graph_for = "accountid";

					//Adding the links to the graph	
					if($i==0)
						$bar_target_val .=$link_val;
					else
						$bar_target_val .="::".$link_val;
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
			//For all Days getting the vtiger_table 
			for($k=0; $k<$days;$k++)
			{
				$tdate=$date_array[$k];
				if(!isset($mod_tot_cnt_array[$tdate]))
					$mod_tot_cnt_array[$tdate]="0";
				$tot= $mod_tot_cnt_array[$tdate];
				if($period_type!="yday")
					$mod_cnt_table.="<td>$tot</td>";
			}
			if($graph_for == "productname")
			{
				$cnt_total=array_sum($mod_count_array);
			}
			else
			{
				$cnt_total=array_sum($mod_tot_cnt_array);
			}

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
		return "<h3> The data is not available with the specified time period</h3>";
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

function get_graph_by_type($graph_by,$graph_title,$module,$where,$query,$width=900,$height=500)
{
	global $user_id,$date_start,$end_date,$type,$mod_strings;
	$time = time();
	//Giving the Cached image name
	$cache_file_name=abs(crc32($user_id))."_".$type."_".crc32($date_start.$end_date).$time.".png";
	$html_imagename=$graph_by; //Html image name for the graph

	$graph_details=module_Chart($user_id,$date_start,$end_date,$query,$graph_by,$graph_title,$where,$module,$type);
	if($graph_details!=0)
	{
		$name_val=$graph_details[0];
		$cnt_val=$graph_details[1];
		$graph_title=$graph_details[2];
		$target_val=$graph_details[3];
		$graph_date=$graph_details[4];
		$urlstring=$graph_details[5];
		$cnt_table=$graph_details[6];
		$test_target_val=$graph_details[7];
		
		if(isset($_REQUEST['display_view']) && $_REQUEST['display_view'] == 'MATRIX')
		{
			$width = 350;
			$height = 250;
		}else
		{
			$width = 850;
			$height = 500;	
		}		

		$top=20;
		$left=140;
		$bottom=120;
		$title=$graph_title;

		return get_graph($cache_file_name,$html_imagename,$cnt_val,$name_val,$width,$height,$left,$right,$top,$bottom,$title,$target_val,$graph_date,$urlstring,$test_target_val,$date_start,$end_date);
	}
	else
	{
		 sleep(1);
                 echo '<h3>'.$mod_strings['LBL_NO_DATA'].'</h3>';
	}
	
}

/** Returns  the Horizontal,vertical, pie graphs and Accumulated Graphs
for the details
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): ______________________________________..
*/


// Function for get graphs
function get_graph($cache_file_name,$html_imagename,$cnt_val,$name_val,$width,$height,$left,$right,$top,$bottom,$title,$target_val,$graph_date,$urlstring,$test_target_val,$date_start,$end_date)
{

	global $tmp_dir;
	global $graph_title, $mod_strings;
	global $theme;
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";

	$val=explode(":",$title);
	$display_title=$val[0];

	if(isset($_REQUEST['display_view']) && $_REQUEST['display_view'] == 'MATRIX')
	{
		$sHTML .="<tr><td width=50%><table width=100%  border=0 cellspacing=0 cellpadding=0 align=left>"; 
	}

$sHTML .= "<tr>
	   <td><a name='1'></a><table width=20%  border=0 cellspacing=12 cellpadding=0 align=left>
	         <tr>
	    	   <td rowspan=2 valign=top><span class=\"dash_count\">1</span></td>
	           <td nowrap><span class=genHeaderSmall>".$graph_title."</span></td>
		 </tr>
		 <tr>
		   <td nowrap><span class=big>".$mod_strings['LBL_HORZ_BAR_CHART']."</span> </td>
		 </tr>
		</table>
	   </td>
	   <td align='right'>";
	 if(isset($_REQUEST['display_view']) && $_REQUEST['display_view'] == 'MATRIX')
	 {  
		$sHTML .= "&nbsp;";
		 
	 }else
	 {		 
		$sHTML .= "<table cellpadding='0' cellspacing='0' border='0' class='small'>
		<tr>
			<td class='small'>".$mod_strings['VIEWCHART']." :&nbsp;</td>
			<td class='dash_row_sel'>1</td>
			<td class='dash_row_unsel'><a class='dash_href' href='#2'>2</a></td>
			<td class='dash_switch'><a href='#top'><img align='absmiddle' src='".$image_path."dash_scroll_up.jpg' border='0'></a></td>
		</tr>
		</table>";
	 }	
	$sHTML .="</td>
	</tr>
	<tr>
           <td colspan='2'>";
   

	   $sHTML .= render_graph($tmp_dir."hor_".$cache_file_name,$html_imagename."_hor",$cnt_val,$name_val,$width,$height,$left,$right,$top,$bottom,$title,$target_val,"horizontal");
//Commented by Minnie -- same content displayed in two graphs
/*$sHTML .= "</td>
	</tr>
	<tr>
	   <td><hr noshade='noshade' size='1' /></td>
	</tr>";

$sHTML .= "<tr>
	   <td><table width=20%  border=0 cellspacing=0 cellpadding=0 align=left>
	   	 <tr>
		   <td rowspan=2 valign=top><span class=dashSerial>2</span></td>
		   <td nowrap><span class=genHeaderSmall>".$graph_title."</span></td>
		 </tr>
		 <tr>
		   <td><span class=big>Vertical Bar Chart</span> </td>
		 </tr>
	        </table>
	   </td>
	</tr>
	<tr>
	   <td height=200>";

	   $sHTML .= render_graph($tmp_dir."vert_".$cache_file_name,$html_imagename."_vert",$cnt_val,$name_val,$width,$height,$left,$right,$top,$bottom,$title,$target_val,"vertical");*/

$sHTML .= "</td>
	</tr>";

	if(isset($_REQUEST['display_view']) && $_REQUEST['display_view'] == 'MATRIX')
	{
		$sHTML .="</table></td><td width=50%><table width=100%  border=0 cellspacing=0 cellpadding=0 align=left>"; 
	}else
	{
		$sHTML .= "<tr><td colspan='2' class='dash_chart_btm'>&nbsp;</td></tr>";
	}

$sHTML .= "<tr>
	   <td><a name='2'></a><table width=20%  border=0 cellspacing=12 cellpadding=0 align=left>
           	 <tr>
	           <td rowspan=2 valign=top><span class=\"dash_count\">2</span></td>
	           <td nowrap><span class=genHeaderSmall>".$graph_title."</span></td>
	         </tr>
	         <tr>
	           <td><span class=big>".$mod_strings['LBL_PIE_CHART']."</span> </td>
	         </tr>
	        </table>
	   </td>
	     <td align='right'>";
	 if(isset($_REQUEST['display_view']) && $_REQUEST['display_view'] == 'MATRIX')
	 {  
		$sHTML .= "&nbsp;";
		 
	 }else
	 {		 
		$sHTML .= "<table cellpadding='0' cellspacing='0' border='0' class='small'>
		<tr>
			<td class='small'>".$mod_strings['VIEWCHART']." :&nbsp;</td>
			<td class='dash_row_unsel'><a class='dash_href' href='#1'>1</a></td>
			<td class='dash_row_sel'>2</td>
			<td class='dash_switch'><a href='#top'><img align='absmiddle' src='".$image_path."dash_scroll_up.jpg' border='0'></a></td>
		</tr>
		</table>";
	 }	
	$sHTML .="</td>
	</tr>
	<tr>
	   <td colspan='2'>";

	   $sHTML .= render_graph($tmp_dir."pie_".$cache_file_name,$html_imagename."_pie",$cnt_val,$name_val,$width,$height,$left,$right,$top,$bottom,$title,$target_val,"pie");

$sHTML .= "</td>
	</tr>";

if(isset($_REQUEST['display_view']) && $_REQUEST['display_view'] == 'MATRIX')
{
	$sHTML .="</table></td></tr>"; 
}
$sHTML .= "<tr><td colspan='2' class='dash_chart_btm'>&nbsp;</td></tr>";


return $sHTML;
}

/** Returns graph, if the cached image is present it'll display that image,
otherwise it will render the graph with the given details
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): ______________________________________..
*/

// Function to get the chached image if exists
function render_graph($cache_file_name,$html_imagename,$cnt_val,$name_val,$width,$height,$left,$right,$top,$bottom,$title,$target_val,$graph_type)
{

	//Checks whether the cached image is present or not
	if(file_exists($cache_file_name))
	{
		unlink($cache_file_name);
	}
	if(file_exists($cache_file_name.'.map'))
	{
		unlink($cache_file_name.'.map');
	}
	if (!file_exists($cache_file_name) || !file_exists($cache_file_name.'.map'))
	{
		//If the Cached image is not present
		if($graph_type=="horizontal")
		{
			return horizontal_graph($cnt_val,$name_val,$width,$height,$left,$right,$top,$bottom,$title,$target_val,$cache_file_name,$html_imagename);
		}
		else if($graph_type=="vertical")
		{
			return vertical_graph($cnt_val,$name_val,$width,$height,$left,$right,$top,$bottom,$title,$target_val,$cache_file_name,$html_imagename);
		}
		else if($graph_type=="pie")
		{
			return pie_chart($cnt_val,$name_val,$width,$height,$left,$right,$top,$bottom,$title,$target_val,$cache_file_name,$html_imagename);

		}
	}
	else
	{
		//Getting the cached image
		$imgMap_fp = fopen($cache_file_name.'.map', "rb");
		$imgMap = fread($imgMap_fp, filesize($cache_file_name.'.map'));
		fclose($imgMap_fp);
		$base_name_cache_file=basename($cache_file_name);
		$ccc="cache/images/".$base_name_cache_file;
		$return = "\n$imgMap\n";
		$return .= "<img src=$ccc ismap usemap=#$html_imagename border='0'>";
		return $return;
	}
}
?>
