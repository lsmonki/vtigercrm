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


	include("modules/Dashboard/Entity_charts.php");
        include("modules/Dashboard/horizontal_bargraph.php");
        include("modules/Dashboard/vertical_bargraph.php");
        include("modules/Dashboard/pie_graph.php");
	
global $tmp_dir;


$period=($_REQUEST['period'])?$_REQUEST['period']:"tmon"; // Period >> lmon- Last Month, tmon- This Month, lweek-LastWeek, tweek-ThisWeek; lday- Last Day 
$type=($_REQUEST['type'])?$_REQUEST['type']:"leadsource";
$dates_values=start_end_dates($period); //To get the stating and End dates for a given period 
$date_start=$dates_values[0]; //Starting date 
$end_date=$dates_values[1]; // Ending Date
$period_type=$dates_values[2]; //Period type as MONTH,WEEK,LDAY
$width=$dates_values[3];
$height=$dates_values[4];

//It gives all the dates in between the starting and ending dates and also gives the number of days,declared in utils.php
$no_days_dates=get_days_n_dates($date_start,$end_date);
$days=$no_days_dates[0];
$date_array=$no_days_dates[1]; //Array containig all the dates 
$user_id=$current_user->id;

// Query for Leads
$leads_query="select crmentity.crmid,crmentity.createdtime, leaddetails.*, crmentity.smownerid, leadscf.* from leaddetails inner join crmentity on crmentity.crmid=leaddetails.leadid inner join leadsubdetails on leadsubdetails.leadsubscriptionid=leaddetails.leadid inner join leadaddress on leadaddress.leadaddressid=leadsubdetails.leadsubscriptionid inner join leadscf on leaddetails.leadid = leadscf.leadid left join leadgrouprelation on leadscf.leadid=leadgrouprelation.leadid left join groups on groups.groupname=leadgrouprelation.groupname where crmentity.deleted=0 and leaddetails.converted=0 ";


//Query for Accounts
$account_query="select crmentity.*, account.*, accountscf.* from account inner join crmentity on crmentity.crmid=account.accountid inner join accountbillads on account.accountid=accountbillads.accountaddressid inner join accountshipads on account.accountid=accountshipads.accountaddressid inner join accountscf on account.accountid = accountscf.accountid left join accountgrouprelation on accountscf.accountid=accountgrouprelation.accountid left join groups on groups.groupname=accountgrouprelation.groupname left join users on users.id=crmentity.smownerid where crmentity.deleted=0 ";


//Query For Products
$products_query="select distinct(crmentity.crmid),crmentity.createdtime,products.*, productcf.* from products inner join crmentity on crmentity.crmid=products.productid left join productcf on products.productid = productcf.productid left join seproductsrel on seproductsrel.productid = products.productid where crmentity.deleted=0 ";

//Query for Potential
$potential_query= "select  crmentity.*,account.accountname, potential.*, potentialscf.* from potential inner join crmentity on crmentity.crmid=potential.potentialid inner join account on potential.accountid = account.accountid inner join potentialscf on potentialscf.potentialid = potential.potentialid left join potentialgrouprelation on potential.potentialid=potentialgrouprelation.potentialid left join groups on groups.groupname=potentialgrouprelation.groupname left join users on users.id=crmentity.smownerid where crmentity.deleted=0 ";

//Query for Sales Order
$so_query="select crmentity.*,salesorder.*,account.accountid,quotes.quoteid from salesorder inner join crmentity on crmentity.crmid=salesorder.salesorderid inner join sobillads on salesorder.salesorderid=sobillads.sobilladdressid inner join soshipads on salesorder.salesorderid=soshipads.soshipaddressid left join salesordercf on salesordercf.salesorderid = salesorder.salesorderid left outer join quotes on quotes.quoteid=salesorder.quoteid left outer join account on account.accountid=salesorder.accountid left join sogrouprelation on salesorder.salesorderid=sogrouprelation.salesorderid left join groups on groups.groupname=sogrouprelation.groupname left join users on users.id=crmentity.smownerid where crmentity.deleted=0 ";


//Query for Purchase Order

$po_query="select crmentity.*,purchaseorder.* from purchaseorder inner join crmentity on crmentity.crmid=purchaseorder.purchaseorderid left outer join vendor on purchaseorder.vendorid=vendor.vendorid inner join pobillads on purchaseorder.purchaseorderid=pobillads.pobilladdressid inner join poshipads on purchaseorder.purchaseorderid=poshipads.poshipaddressid left join purchaseordercf on purchaseordercf.purchaseorderid = purchaseorder.purchaseorderid left join pogrouprelation on purchaseorder.purchaseorderid=pogrouprelation.purchaseorderid left join groups on groups.groupname=pogrouprelation.groupname left join users on users.id=crmentity.smownerid where crmentity.deleted=0 ";

// Query for Quotes
$quotes_query="select crmentity.*,quotes.* from quotes inner join crmentity on crmentity.crmid=quotes.quoteid inner join quotesbillads on quotes.quoteid=quotesbillads.quotebilladdressid inner join quotesshipads on quotes.quoteid=quotesshipads.quoteshipaddressid left join quotescf on quotes.quoteid = quotescf.quoteid left outer join account on account.accountid=quotes.accountid left outer join potential on potential.potentialid=quotes.potentialid left join quotegrouprelation on quotes.quoteid=quotegrouprelation.quoteid left join groups on groups.groupname=quotegrouprelation.groupname left join users on users.id=crmentity.smownerid where crmentity.deleted=0 ";

//Query for Invoice
$invoice_query="select crmentity.*,invoice.* from invoice inner join crmentity on crmentity.crmid=invoice.invoiceid inner join invoicebillads on invoice.invoiceid=invoicebillads.invoicebilladdressid inner join invoiceshipads on invoice.invoiceid=invoiceshipads.invoiceshipaddressid left outer join salesorder on salesorder.salesorderid=invoice.salesorderid inner join invoicecf on invoice.invoiceid = invoicecf.invoiceid left join invoicegrouprelation on invoice.invoiceid=invoicegrouprelation.invoiceid left join groups on groups.groupname=invoicegrouprelation.groupname left join users on users.id=crmentity.smownerid where crmentity.deleted=0 ";

//Query for tickets
$helpdesk_query=" select troubletickets.status ticketstatus, troubletickets.*,crmentity.* from troubletickets inner join ticketcf on ticketcf.ticketid = troubletickets.ticketid inner join crmentity on crmentity.crmid=troubletickets.ticketid left join ticketgrouprelation on troubletickets.ticketid=ticketgrouprelation.ticketid left join groups on groups.groupname=ticketgrouprelation.groupname left join contactdetails on troubletickets.parent_id=contactdetails.contactid left join account on account.accountid=troubletickets.parent_id left join users on crmentity.smownerid=users.id and troubletickets.ticketid = ticketcf.ticketid where crmentity.deleted=0";

 /**  This function returns  the values for the graph, for any type of graph needed	 
        * Portions created by vtiger are Copyright (C) vtiger.
        * All Rights Reserved.
        * Contributor(s): ______________________________________..
 */
$graph_array = Array(
          "leadsource" => "Leads By Source",
          "leadstatus" => "Leads By Status",
          "leadindustry" => "Leads By Industry",
          "salesbyleadsource" => "Sales by LeadSource",
          "salesbyaccount" => "Sales by Accounts",
          "accountindustry" => "Account By Industry",
          "productcategory" => "Products by Category",
          "sobyaccounts" => "Sales Order by Accounts",
          "sobystatus" => "Sales Order by Status",
          "pobystatus" => "Purchase Order by Status",
          "quotesbyaccounts" => "Quotes by Accounts",
          "quotesbystage" => "Quotes by Stage",
          "invoicebyacnts" => "Invoices by Accounts",
          "invoicebystatus" => "Invoices by status",
          "ticketsbystatus" => "Tickets by status",
          "ticketsbypriority" => "Tickets by Priority",
          );
function get_graph_by_type($graph_by,$graph_title,$module,$where,$query)
{
	global $user_id,$date_start,$end_date,$type;

	//Giving the Cached image name	
	$cache_file_name=abs(crc32($user_id))."_".$type."_".crc32($date_start.$end_date).".png";
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


                $width=600;
                $height=400;
                $top=30;
                $left=140;
                $bottom=120;
                $title=$graph_title;

                return get_graph($cache_file_name,$html_imagename,$cnt_val,$name_val,$width,$height,$left,$right,$top,$bottom,$title,$target_val,$graph_date,$urlstring,$test_target_val,$date_start,$end_date);
        }
	else
	{
	
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
     global $graph_title;
	$val=explode(":",$title); 		
	$display_title=$val[0];	
			
	
$sHTML .= "<tr>
	   <td><table width=20%  border=0 cellspacing=0 cellpadding=0 align=left>
	  	 <tr>
		   <td rowspan=2 valign=top><span class=dashSerial>1</span></td>
 		   <td nowrap><span class=genHeaderSmall>".$graph_title."</span></td>
 		 </tr>
   		 <tr>
		   <td><span class=big>Horizontal Bar Chart</span> </td>
		 </tr>
		</table>
  	   </td>
	</tr>
	<tr>
	   <td height=200>"; 

	   $sHTML .= render_graph($tmp_dir."hor_".$cache_file_name,$html_imagename."_hor",$cnt_val,$name_val,$width,$height,$left,$right,$top,$bottom,$title,$target_val,"horizontal");

$sHTML .= "</td>
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

	   $sHTML .= render_graph($tmp_dir."vert_".$cache_file_name,$html_imagename."_vert",$cnt_val,$name_val,$width,$height,$left,$right,$top,$bottom,$title,$target_val,"vertical");

$sHTML .= "</td>
	</tr>
	<tr>
	   <td><hr noshade='noshade' size='1' /></td>
	</tr>";

$sHTML .= "<tr>
	   <td><table width=20%  border=0 cellspacing=0 cellpadding=0 align=left>
	  	 <tr>
		   <td rowspan=2 valign=top><span class=dashSerial>3</span></td>
 		   <td nowrap><span class=genHeaderSmall>".$graph_title."</span></td>
 		 </tr>
   		 <tr>
		   <td><span class=big>Pie Chart</span> </td>
		 </tr>
		</table>
  	   </td>
	</tr>
	<tr>
	   <td height=200>"; 

	   $sHTML .= render_graph($tmp_dir."pie_".$cache_file_name,$html_imagename."_pie",$cnt_val,$name_val,$width,$height,40,$right,$top,$bottom,$title,$target_val,"pie");

$sHTML .= "</td>
	</tr>
	<tr>
	   <td><hr noshade='noshade' size='1' /></td>
	</tr>";

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
	if(file_exists($cahce_file_name))
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
<table class="small" border="0" cellpadding="0" cellspacing="0" width="100%">
 <tbody>
    <tr>
          <td style="height: 2px;"></td>
    </tr>

    <tr>
       <td style="padding-left: 10px; padding-right: 10px;" class="moduleName" nowrap="nowrap">Analytics &gt; Dashboard </td>
       <td style="width: 1px;"></td>
       <td class="small" height="30">&nbsp;</td>
    </tr>
    <tr>
       <td style="height: 20px;"></td>
    </tr>
 </tbody>
</table>

<table class="dashMain" cellspacing="0" cellpadding="0" align="center">
   <tr>
    <th colspan="3"><img src="themes/blue/images/topBnr.gif" width="840" height="67" /></th>
  </tr>
  <tr><td colspan="3">&nbsp;</td></tr>

  <tr>
    <td width="20%" nowrap valign="top">
		<table width="100%"  border="0" cellspacing="0" cellpadding="0" bgcolor="#DFDFDF">
          	<tr><td class="dashMnuUnSel">
                    <a href="index.php?module=Dashboard&action=index&type=dashboardhome">Dashboard Home</a>
               </td></tr>              
               <?php 
                 $mnuHTML = "";
                 foreach($graph_array as $key=>$value)   
                 {
                    if($type == $key)
                    {
                         $mnuHTML .= '<tr><td class="dashMnuSel">
                                        <a href="index.php?module=Dashboard&action=display_charts&type='.$key.'">'.$value.'</a>
                                      </td></tr>';
                    }else
                    {
                         $mnuHTML .= '<tr><td class="dashMnuUnSel">
                                        <a href="index.php?module=Dashboard&action=display_charts&type='.$key.'">'.$value.'</a>
                                      </td></tr>';
                    }
                 }
                 echo $mnuHTML;
               ?>
	    </table>
	</td>
     <td width="79%" bgcolor="#CBCBCB" valign="top" style="padding-right:10px;" align="left">
		<table class="dashInner"  cellpadding="0" cellspacing="0">
		<tr><td class="lvtHeaderText" align="left" height="10"></td></tr>
		<tr><td><div id="dashChart">
			<table width="100%"  border="0" cellspacing="0" cellpadding="0">
				<!--char goes here-->
				<?php 
				//Charts for Lead Source
                    if(($type == "leadsource") && (getFieldVisibilityPermission('Leads',$user_id,'leadsource') == "0"))
                    {
                    	$graph_by="leadsource";
                    	$graph_title="Leads By Source";
                    	$module="Leads";
                    	$where="";
                    	$query=$leads_query;                   
                    	echo get_graph_by_type($graph_by,$graph_title,$module,$where,$query);
                    
                    }
                    // To display the charts  for Lead status                   
                    elseif (($type == "leadstatus")&& (getFieldVisibilityPermission('Leads',$user_id,'leadstatus') == "0"))
                    {
                    	$graph_by="leadstatus";
                    	$graph_title="Leads By Status";
                    	$module="Leads";
                    	$where="";
                    	$query=$leads_query;
                    	echo get_graph_by_type($graph_by,$graph_title,$module,$where,$query);
                    }
                    //Charts for Lead Industry
                    elseif (($type == "leadindustry") && (getFieldVisibilityPermission('Leads',$user_id,'industry') == "0"))
                    {
                    	$graph_by="industry";
                            $graph_title="Leads By Industry";
                            $module="Leads";
                            $where="";
                            $query=$leads_query;
                            echo get_graph_by_type($graph_by,$graph_title,$module,$where,$query);
                    }
                    //Sales by Lead Source
                    elseif (($type == "salesbyleadsource")&& (getFieldVisibilityPermission('Potentials',$user_id,'leadsource') == "0"))
                    {
                            $graph_by="leadsource";
                            $graph_title="Sales by LeadSource";
                            $module="Potentials";
                            $where=" and potential.sales_stage like '%Closed Won%' ";
                            $query=$potential_query;
                            echo get_graph_by_type($graph_by,$graph_title,$module,$where,$query);
                    }
                    //Sales by Account
                    elseif (($type == "salesbyaccount") && (getFieldVisibilityPermission('Potentials',$user_id,'account_id') == "0"))
                    {
                    	$graph_by="accountid";
                         $graph_title="Sales by Accounts";
                         $module="Potentials";
                         $where=" and potential.sales_stage like '%Closed Won%' ";
                         $query=$potential_query;
                         echo get_graph_by_type($graph_by,$graph_title,$module,$where,$query);
                    }
                    //Charts for Account by Industry
                    elseif (($type == "accountindustry") && (getFieldVisibilityPermission('Accounts',$user_id,'industry') == "0"))
                    {
                    	$graph_by="industry";
                            $graph_title="Account By Industry";
                            $module="Accounts";
                            $where="";
                            $query=$account_query;
                            echo get_graph_by_type($graph_by,$graph_title,$module,$where,$query);
                    }
                    //Charts for Products by Category
                    elseif (($type == "productcategory") && (getFieldVisibilityPermission('Products',$user_id,'productcategory') == "0"))
                    {
                    	$graph_by="productcategory";
                            $graph_title="Products by Category";
                            $module="Products";
                            $where="";
                            $query=$products_query;
                            echo get_graph_by_type($graph_by,$graph_title,$module,$where,$query);
                    }
                    // Sales Order by Accounts
                    elseif (($type == "sobyaccounts") && (getFieldVisibilityPermission('SalesOrder',$user_id,'account_id') == "0"))
                    {
                    	$graph_by="accountid";
                            $graph_title="Sales Order by Accounts";
                            $module="SalesOrder";
                            $where="";
                            $query=$so_query;
                            echo get_graph_by_type($graph_by,$graph_title,$module,$where,$query);
                    }
                    //Sales Order by Status
                    elseif (($type == "sobystatus") && (getFieldVisibilityPermission('SalesOrder',$user_id,'sostatus') == "0"))
                    {
                            $graph_by="sostatus";
                            $graph_title="Sales Order by Status";
                            $module="SalesOrder";
                            $where="";
                            $query=$so_query;
                            echo get_graph_by_type($graph_by,$graph_title,$module,$where,$query);
                    }
                    //Purchase Order by Status
                    elseif (($type == "pobystatus") && (getFieldVisibilityPermission('PurchaseOrder',$user_id,'postatus') == "0"))
                    {
                            $graph_by="postatus";
                            $graph_title="Purchase Order by Status";
                            $module="PurchaseOrder";
                            $where="";
                            $query=$po_query;
                            echo get_graph_by_type($graph_by,$graph_title,$module,$where,$query);
                    }
                    //Quotes by Accounts
                    elseif (($type == "quotesbyaccounts") && (getFieldVisibilityPermission('Quotes',$user_id,'account_id') == "0"))
                    {
                            $graph_by="accountid";
                            $graph_title="Quotes by Accounts";
                            $module="Quotes";
                            $where="";
                            $query=$quotes_query;
                            echo get_graph_by_type($graph_by,$graph_title,$module,$where,$query);
                    }
                    //Quotes by Stage
                    elseif (($type == "quotesbystage") && (getFieldVisibilityPermission('Quotes',$user_id,'quotestage') == "0"))
                    {
                            $graph_by="quotestage";
                            $graph_title="Quotes by Stage";
                            $module="Quotes";
                            $where="";
                            $query=$quotes_query;
                            echo get_graph_by_type($graph_by,$graph_title,$module,$where,$query);
                    }
                    //Invoice by Accounts
                    elseif (($type == "invoicebyacnts") && (getFieldVisibilityPermission('Invoice',$user_id,'account_id') == "0"))
                    {
                            $graph_by="accountid";
                            $graph_title="Invoices by Accounts";
                            $module="Invoice";
                            $where="";
                            $query=$invoice_query;
                            echo get_graph_by_type($graph_by,$graph_title,$module,$where,$query);
                    }
                    //Invoices by status
                    elseif (($type == "invoicebystatus") && (getFieldVisibilityPermission('Invoice',$user_id,'invoicestatus') == "0"))
                    {
                            $graph_by="invoicestatus";
                            $graph_title="Invoices by status";
                            $module="Invoice";
                            $where="";
                            $query=$invoice_query;
                            echo get_graph_by_type($graph_by,$graph_title,$module,$where,$query);
                    }
                    //Tickets by Status
                    elseif (($type == "ticketsbystatus") && (getFieldVisibilityPermission('HelpDesk',$user_id,'ticketstatus') == "0"))
                    {
                            $graph_by="ticketstatus";
                            $graph_title="Tickets by status";
                            $module="HelpDesk";
                            $where="";
                            $query=$helpdesk_query;
                            echo get_graph_by_type($graph_by,$graph_title,$module,$where,$query);
                    }
                    //Tickets by Priority
                    elseif (($type == "ticketsbypriority") && (getFieldVisibilityPermission('HelpDesk',$user_id,'ticketpriorities') == "0"))
                    {
                            $graph_by="priority";
                            $graph_title="Tickets by Priority";
                            $module="HelpDesk";
                            $where="";
                            $query=$helpdesk_query;
                            echo get_graph_by_type($graph_by,$graph_title,$module,$where,$query);
                    }
		    else
                    {
                        echo $mod_strings['LBL_NO_PERMISSION_FIELD'];
                    }

				?>

			</table>
	</div></td></tr>
		</table>
	  <br />
</td>

 <td width="1%"></td>
  </tr>
  <tr><td colspan="3" height="30">&nbsp;</td></tr>
</table>
