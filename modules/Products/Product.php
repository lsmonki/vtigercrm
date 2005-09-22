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

include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('data/CRMEntity.php');
require_once('include/utils.php');


class Product extends CRMEntity {
	var $log;
	var $db;


	// Stored fields
	var $id;
	var $mode;

	// These are related
	var $name;
	var $vendorid;
	var $contactname;
	var $contactid;
	
	 // Josh added for importing and exporting -added in patch2
        var $unit_price;
        var $table_name = "products";
        var $object_name = "Product";
        var $entity_table = "crmentity";
        var $required_fields = Array(
                'productname'=>1
        );


	var $tab_name = Array('crmentity','products','productcf','seproductsrel');
	var $tab_name_index = Array('crmentity'=>'crmid','products'=>'productid','productcf'=>'productid','seproductsrel'=>'productid');
	var $column_fields = Array();

	var $sortby_fields = Array('productname','productcode','commissionrate');		  

        // This is the list of fields that are in the lists.
        var $list_fields = Array(
                                'Product Name'=>Array('products'=>'productname'),
                                'Product Code'=>Array('products'=>'productcode'),
                                'Commission Rate'=>Array('products'=>'commissionrate'),
                                'Qty/Unit'=>Array('products'=>'qty_per_unit'),
                                'Unit Price'=>Array('products'=>'unit_price')
                                );
        var $list_fields_name = Array(
                                        'Product Name'=>'productname',
                                        'Product Code'=>'productcode',
                                        'Commission Rate'=>'commissionrate',
                                        'Qty/Unit'=>'qty_per_unit',
                                        'Unit Price'=>'unit_price'
                                     );
        var $list_link_field= 'productname';


	var $list_mode;
	var $popup_type;

	var $search_fields = Array(
                                'Product Name'=>Array('products'=>'productname'),
                                'Product Code'=>Array('products'=>'productcode'),
                                'Unit Price'=>Array('products'=>'unit_price')
                                );
        var $search_fields_name = Array(
                                        'Product Name'=>'productname',
                                        'Product Code'=>'productcode',
                                        'Unit Price'=>'unit_price'
                                     );
	
	var $combofieldNames = Array('manufacturer'=>'manufacturer_dom'
                      ,'productcategory'=>'productcategory_dom');


	function Product() {
		$this->log =LoggerManager::getLogger('product');
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('Products');
	}

  function get_summary_text()
        {
                return $this->name;
        }

  		
	function get_attachments($id)
        {
	
		$query = "select notes.title,'Notes      ' ActivityType, notes.filename, attachments.type  FileType,crm2.modifiedtime  lastmodified, seattachmentsrel.attachmentsid attachmentsid, notes.notesid crmid from notes inner join senotesrel on senotesrel.notesid= notes.notesid inner join crmentity on crmentity.crmid= senotesrel.crmid inner join crmentity crm2 on crm2.crmid=notes.notesid and crm2.deleted=0 left join seattachmentsrel  on seattachmentsrel.crmid =notes.notesid left join attachments on seattachmentsrel.attachmentsid = attachments.attachmentsid where crmentity.crmid=".$id;
                $query .= ' union all ';
                $query .= "select attachments.description title ,'Attachments'  ActivityType, attachments.name  filename, attachments.type  FileType,crm2.modifiedtime  lastmodified, attachments.attachmentsid attachmentsid, seattachmentsrel.attachmentsid crmid from attachments inner join seattachmentsrel on seattachmentsrel.attachmentsid= attachments.attachmentsid inner join crmentity on crmentity.crmid= seattachmentsrel.crmid inner join crmentity crm2 on crm2.crmid=attachments.attachmentsid where crmentity.crmid=".$id;	

		renderRelatedAttachments($query,$id);
        }

	function get_opportunities($id)
        {
		$query = 'select potential.potentialid, potential.potentialname, potential.potentialtype,  products.productid, products.productname, products.qty_per_unit, products.unit_price, products.expiry_date from potential inner join products on potential.productid = products.productid where products.productid='.$id;
          renderRelatedPotentials($query);
        }

	function get_tickets($id)
        {
		//$query = 'select users.user_name, users.id, products.productid,products.productname, troubletickets.ticketid, troubletickets.parent_id, troubletickets.title, troubletickets.status, troubletickets.priority, crmentity.crmid, crmentity.smownerid, crmentity.modifiedtime from products  inner join seticketsrel on seticketsrel.crmid = products.productid inner join troubletickets on troubletickets.ticketid = seticketsrel.ticketid inner join crmentity on crmentity.crmid = troubletickets.ticketid left join users on users.id=crmentity.smownerid where products.productid= '.$id.' and crmentity.deleted=0';
		$query = "select users.user_name, users.id, products.productid,products.productname, troubletickets.ticketid, troubletickets.parent_id, troubletickets.title, troubletickets.status, troubletickets.priority, crmentity.crmid, crmentity.smownerid, crmentity.modifiedtime from troubletickets inner join crmentity on crmentity.crmid = troubletickets.ticketid left join products on products.productid=troubletickets.product_id left join users on users.id=crmentity.smownerid where crmentity.deleted=0 and products.productid=".$id;
          renderRelatedTickets($query,$id);
        }

	function get_meetings($id)
	{
		$query = "SELECT meetings.name,meetings.location,meetings.date_start from meetings inner join seactivityrel on seactivityrel.activityid=meetings.meetingid and seactivityrel.crmid=".$id."";
		renderRelatedMeetings($query);
	}

	function get_activities($id)
	{
		//$query = "SELECT activity.*,seactivityrel.*,crmentity.crmid, crmentity.smownerid, crmentity.modifiedtime, users.user_name from activity inner join seactivityrel on seactivityrel.activityid=activity.activityid inner join crmentity on crmentity.crmid=activity.activityid left join users on users.id=crmentity.smownerid where seactivityrel.crmid=".$id." and (activitytype='Task' or activitytype='Call' or activitytype='Meeting')";
		$query = "SELECT contactdetails.lastname, contactdetails.firstname, contactdetails.contactid, activity.*,seactivityrel.*,crmentity.crmid, crmentity.smownerid, crmentity.modifiedtime, users.user_name,recurringevents.recurringtype from activity inner join seactivityrel on seactivityrel.activityid=activity.activityid inner join crmentity on crmentity.crmid=activity.activityid left join cntactivityrel on cntactivityrel.activityid= activity.activityid left join contactdetails on contactdetails.contactid = cntactivityrel.contactid left join users on users.id=crmentity.smownerid left outer join recurringevents on recurringevents.activityid=activity.activityid where seactivityrel.crmid=".$id." and (activitytype='Task' or activitytype='Call' or activitytype='Meeting')";
		renderRelatedActivities($query,$id);
	}
	function get_quotes($id)
 	{
		$query = "select crmentity.*, quotes.*,potential.potentialname,account.accountname,quotesproductrel.productid from quotes inner join crmentity on crmentity.crmid=quotes.quoteid inner join quotesproductrel on quotesproductrel.quoteid=quotes.quoteid left outer join account on account.accountid=quotes.accountid left outer join potential on potential.potentialid=quotes.potentialid where crmentity.deleted=0 and quotesproductrel.productid=".$id;
		renderRelatedQuotes($query,$id,$this->column_fields['contact_id'],$this->column_fields['parent_id']);
	}
	function get_purchase_orders($id)
	{
		$query = "select crmentity.*, purchaseorder.*,products.productname,poproductrel.productid from purchaseorder inner join crmentity on crmentity.crmid=purchaseorder.purchaseorderid inner join poproductrel on poproductrel.purchaseorderid=purchaseorder.purchaseorderid inner join products on products.productid=poproductrel.productid where crmentity.deleted=0 and products.productid=".$id;
	      	renderProductPurchaseOrders($query,$id,$this->column_fields['vendor_id'],$this->column_fields['contact_id']);
        }
	function get_salesorder($id)
	{
		$query = "select crmentity.*, salesorder.*, products.productname as productname, account.accountname from salesorder inner join crmentity on crmentity.crmid=salesorder.salesorderid inner join soproductrel on soproductrel.salesorderid=salesorder.salesorderid inner join products on products.productid=soproductrel.productid left outer join account on account.accountid=salesorder.accountid where crmentity.deleted=0 and products.productid = ".$id;
		renderProductSalesOrders($query,$id,$this->column_fields['contact_id'],$this->column_fields['parent_id']);	
	}
	function get_invoices($id)
	{
		$query = "select crmentity.*, invoice.*, invoiceproductrel.quantity, account.accountname from invoice inner join crmentity on crmentity.crmid=invoice.invoiceid left outer join account on account.accountid=invoice.accountid inner join invoiceproductrel on invoiceproductrel.invoiceid=invoice.invoiceid where crmentity.deleted=0 and invoiceproductrel.productid=".$id;
		renderRelatedInvoices($query,$id,$this->column_fields['parent_id']);
	}
	function get_product_pricebooks($id)
        {                                                                                                                     
       	 	$query = 'select crmentity.crmid, pricebook.*,pricebookproductrel.productid as prodid from pricebook inner join crmentity on crmentity.crmid=pricebook.pricebookid inner join pricebookproductrel on pricebookproductrel.pricebookid=pricebook.pricebookid where crmentity.deleted=0 and pricebookproductrel.productid='.$id; 
	 	renderProductRelatedPriceBooks($query,$id);                                                                  
	}
	function product_novendor()
	{
		$query = "SELECT products.productname,crmentity.deleted from products inner join crmentity on crmentity.crmid=products.productid where crmentity.deleted=0 and products.vendor_id=''";
		$result=$this->db->query($query);
		return $this->db->num_rows($result);
	}
	
 //method added to construct the query to fetch the custom fields
	function constructCustomQueryAddendum()
	{
		global $adb;
		//get all the custom fields created
		$sql1 = "select columnname,fieldlabel from field where generatedtype=2 and tabid=14";
		$result = $adb->query($sql1);
		$numRows = $adb->num_rows($result);
		//select accountscf.columnname fieldlabel,accountscf.columnname fieldlabel
		$sql3 = "select ";
		for($i=0; $i < $numRows;$i++)
		{
			$columnName = $adb->query_result($result,$i,"columnname");
			$fieldlable = $adb->query_result($result,$i,"fieldlabel");
			//construct query as below
			if($i == 0)
			{
				$sql3 .= "productcf.".$columnName. " '" .$fieldlable."'";
			}
			else
			{
				$sql3 .= ", productcf.".$columnName. " '" .$fieldlable."'";
			}

		}
		return $sql3;

	}


	//check if the custom table exists or not in the first place
	function checkIfCustomTableExists()
	{
		//$result = mysql_query("select * from accountcf");
		//$testrow = mysql_num_fields($result);
		$result = $this->db->query("select * from productcf");
		$testrow = $this->db->num_fields($result);
		if($testrow > 1)
		{
			$exists=true;
		}
		else
		{
			$exists=false;
		}
		return $exists;
	}


	
	function create_export_query(&$order_by, &$where)
	{
		if($this->checkIfCustomTableExists())
		{

			$query = $this->constructCustomQueryAddendum() . 
				",    
				products.productid productid,
			products.productname productname,
			products.productcode productcode,
			products.productcategory productcategory,
			products.manufacturer manufacturer,
			products.product_description product_description,
			products.qty_per_unit qty_per_unit,
			products.unit_price unit_price,
			products.weight weight,
			products.pack_size pack_size,
			DATE_FORMAT(products.start_date, '%Y-%M-%D') AS start_date,
			DATE_FORMAT(products.expiry_date, '%Y-%M-%D') AS expiry_date,
			products.cost_factor cost_factor,
			products.commissionrate commissionrate,
			products.commissionmethod commissionmethod,
			products.discontinued discontinued,
			products.sales_start_date AS sales_start_date,
			products.sales_end_date AS sales_end_date,
			products.usageunit AS usageunit,
			products.serialno AS serialno,
			products.currency AS currency,
			products.reorderlevel AS reorderlevel,
			products.website AS website,
			products.taxclass AS taxclass,
			products.mfr_part_no AS mfr_part_no,
			products.vendor_part_no AS vendor_part_no,
			products.qtyinstock AS qtyinstock,
			products.productsheet AS productsheet,
			products.qtyindemand AS qtyindemand
				FROM ".$this->entity_table."
				INNER JOIN products ON
				crmentity.crmid = products.productid
				INNER JOIN users on users.id=crmentity.smownerid 
				LEFT JOIN productcf ON
				productcf.productid = products.productid";

		}
		else
		{
			$query = "SELECT
				products.productid productid,
			products.productname productname,
			products.productcode productcode,
			products.productcategory productcategory,
			products.manufacturer manufacturer,
			products.product_description product_description,
			products.qty_per_unit qty_per_unit,
			products.unit_price unit_price,
			products.weight weight,
			products.pack_size pack_size,
			DATE_FORMAT(products.start_date, '%Y-%M-%D') AS start_date,
			DATE_FORMAT(products.expiry_date, '%Y-%M-%D') AS expiry_date,
			products.cost_factor cost_factor,
			products.commissionrate commissionrate,
			products.commissionmethod commissionmethod,
			products.discontinued discontinued,
			products.sales_start_date AS sales_start_date,
			products.sales_end_date AS sales_end_date,
			products.usageunit AS usageunit,
			products.serialno AS serialno,
			products.currency AS currency,
			products.reorderlevel AS reorderlevel,
			products.website AS website,
			products.taxclass AS taxclass,
			products.mfr_part_no AS mfr_part_no,
			products.vendor_part_no AS vendor_part_no,
			products.qtyinstock AS qtyinstock,
			products.productsheet AS productsheet,
			products.qtyindemand AS qtyindemand
			FROM ".$this->table_name ." INNER JOIN crmentity on 
			crmentity.crmid = products.productid 
			INNER JOIN users on users.id=crmentity.smownerid ";

		}
	
		  $where_auto = " users.status='Active'
                        AND crmentity.deleted=0 ";



		 if($where != "")
                        $query .= " where ($where) AND ".$where_auto;
                else
                        $query .= " where ".$where_auto;

                if(!empty($order_by))
                        $query .= " ORDER BY $order_by";

                return $query;

	}


}
?>
