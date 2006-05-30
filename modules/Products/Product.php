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
require_once('include/utils/utils.php');
require_once('include/RelatedListView.php');

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


	var $tab_name = Array('crmentity','products','productcf','seproductsrel','producttaxrel','attachments');
	var $tab_name_index = Array('crmentity'=>'crmid','products'=>'productid','productcf'=>'productid','seproductsrel'=>'productid','producttaxrel'=>'productid','attachments'=>'attachmentsid');
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

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'productname';
	var $default_sort_order = 'ASC';

	function Product() {
		$this->log =LoggerManager::getLogger('product');
		$this->log->debug("Entering Product() method ...");
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('Products');
		$this->log->debug("Exiting Product method ...");
	}

	function get_attachments($id)
	{
		global $log;
		$log->debug("Entering get_attachments(".$id.") method ...");
		// Armando Lüscher 18.10.2005 -> §visibleDescription
		// Desc: Inserted crmentity.createdtime, notes.notecontent description, users.user_name
		// Inserted inner join users on crmentity.smcreatorid= users.id
		$query = "SELECT notes.title, 'Notes      ' AS ActivityType,
				notes.filename, attachments.type  AS FileType,
				crm2.modifiedtime AS lastmodified,
				seattachmentsrel.attachmentsid AS attachmentsid,
				notes.notesid AS crmid, crmentity.createdtime,
				notes.notecontent AS description,
				users.user_name
			FROM notes
			INNER JOIN senotesrel
				ON senotesrel.notesid = notes.notesid
			INNER JOIN crmentity
				ON crmentity.crmid = senotesrel.crmid
			INNER JOIN crmentity AS crm2
				ON crm2.crmid = notes.notesid
				AND crm2.deleted = 0
			LEFT JOIN seattachmentsrel
				ON seattachmentsrel.crmid = notes.notesid
			LEFT JOIN attachments
				ON seattachmentsrel.attachmentsid = attachments.attachmentsid
			INNER JOIN users
				ON crmentity.smcreatorid = users.id
			WHERE crmentity.crmid = ".$id."
		UNION ALL
			SELECT attachments.description AS title,
				'Attachments' AS ActivityType,
				attachments.name AS filename,
				attachments.type AS FileType,
				crm2.modifiedtime AS lastmodified,
				attachments.attachmentsid AS attachmentsid,
				seattachmentsrel.attachmentsid AS crmid,
				crmentity.createdtime,
				attachments.description, users.user_name
			FROM attachments
			INNER JOIN seattachmentsrel
				ON seattachmentsrel.attachmentsid = attachments.attachmentsid
			INNER JOIN crmentity
				ON crmentity.crmid = seattachmentsrel.crmid
			INNER JOIN crmentity AS crm2
				ON crm2.crmid = attachments.attachmentsid
			INNER JOIN users
				ON crmentity.smcreatorid = users.id
			WHERE crmentity.crmid = ".$id;	

		$log->debug("Exiting get_attachments method ...");
        	return getAttachmentsAndNotes('Products',$query,$id);
		}

	function get_opportunities($id)
	{
		global $log;
		$log->debug("Entering get_opportunities(".$id.") method ...");
		$query = "SELECT potential.potentialid, potential.potentialname,
				potential.potentialtype, products.productid,
				products.productname, products.qty_per_unit,
				products.unit_price, products.expiry_date
			FROM potential
			INNER JOIN products
				ON potential.productid = products.productid
			LEFT JOIN potentialgrouprelation
				ON potential.potentialid = potentialgrouprelation.potentialid
			LEFT JOIN groups
				ON groups.groupname = potentialgrouprelation.groupname
			WHERE crmentity.deleted = 0
			AND products.productid = ".$id;
		$log->debug("Exiting get_opportunities method ...");
          renderRelatedPotentials($query);
        }

	function get_tickets($id)
	{
		global $log;
		$log->debug("Entering get_tickets(".$id.") method ...");
		global $mod_strings;
		require_once('modules/HelpDesk/HelpDesk.php');
		$focus = new HelpDesk();

		$button = '';

		$returnset = '&return_module=Products&return_action=DetailView&return_id='.$id;

		$query = "SELECT users.user_name, users.id,
				products.productid, products.productname,
				troubletickets.ticketid,
				troubletickets.parent_id, troubletickets.title,
				troubletickets.status, troubletickets.priority,
				crmentity.crmid, crmentity.smownerid,
				crmentity.modifiedtime
			FROM troubletickets
			INNER JOIN crmentity
				ON crmentity.crmid = troubletickets.ticketid
			LEFT JOIN products
				ON products.productid = troubletickets.product_id
			LEFT JOIN users
				ON users.id = crmentity.smownerid
			LEFT JOIN ticketgrouprelation
				ON troubletickets.ticketid = ticketgrouprelation.ticketid
			LEFT JOIN groups
				ON groups.groupname = ticketgrouprelation.groupname
			WHERE crmentity.deleted = 0
			AND products.productid = ".$id;
	$log->debug("Exiting get_tickets method ...");
		return GetRelatedList('Products','HelpDesk',$focus,$query,$button,$returnset);
	}


	function get_activities($id)
	{
		global $log;
		$log->debug("Entering get_activities(".$id.") method ...");
		global $app_strings;
	
		require_once('modules/Activities/Activity.php');

        	//if($this->column_fields['contact_id']!=0 && $this->column_fields['contact_id']!='')
        	$focus = new Activity();

		$button = '';

		$returnset = '&return_module=Products&return_action=DetailView&return_id='.$id;


		$query = "SELECT contactdetails.lastname,
				contactdetails.firstname,
				contactdetails.contactid,
				activity.*,
				seactivityrel.*,
				crmentity.crmid, crmentity.smownerid,
				crmentity.modifiedtime,
				users.user_name,
				recurringevents.recurringtype
			FROM activity
			INNER JOIN seactivityrel
				ON seactivityrel.activityid = activity.activityid
			INNER JOIN crmentity
				ON crmentity.crmid=activity.activityid
			LEFT JOIN cntactivityrel
				ON cntactivityrel.activityid = activity.activityid
			LEFT JOIN contactdetails
				ON contactdetails.contactid = cntactivityrel.contactid
			LEFT JOIN users
				ON users.id = crmentity.smownerid
			LEFT OUTER JOIN recurringevents
				ON recurringevents.activityid = activity.activityid
			LEFT JOIN activitygrouprelation
				ON activitygrouprelation.activityid = crmentity.crmid
			LEFT JOIN groups
				ON groups.groupname = activitygrouprelation.groupname
			WHERE seactivityrel.crmid=".$id."
			AND (activitytype = 'Task'
				OR activitytype = 'Call'
				OR activitytype = 'Meeting')";
		$log->debug("Exiting get_activities method ...");
		return GetRelatedList('Products','Activities',$focus,$query,$button,$returnset);
	}
	function get_quotes($id)
	{
		global $log;
		$log->debug("Entering get_quotes(".$id.") method ...");	
		global $app_strings;
		require_once('modules/Quotes/Quote.php');	
		$focus = new Quote();
	
		$button = '';
		$returnset = '&return_module=Products&return_action=DetailView&return_id='.$id;


		$query = "SELECT crmentity.*,
				quotes.*,
				potential.potentialname,
				account.accountname,
				quotesproductrel.productid
			FROM quotes
			INNER JOIN crmentity
				ON crmentity.crmid = quotes.quoteid
			INNER JOIN quotesproductrel
				ON quotesproductrel.quoteid = quotes.quoteid
			LEFT OUTER JOIN account
				ON account.accountid = quotes.accountid
			LEFT OUTER JOIN potential
				ON potential.potentialid = quotes.potentialid
			LEFT JOIN quotegrouprelation
				ON quotes.quoteid = quotegrouprelation.quoteid
			LEFT JOIN groups
				ON groups.groupname = quotegrouprelation.groupname
			WHERE crmentity.deleted = 0
			AND quotesproductrel.productid = ".$id;
		$log->debug("Exiting get_quotes method ...");
		return GetRelatedList('Products','Quotes',$focus,$query,$button,$returnset);
	}
	function get_purchase_orders($id)
	{
		global $log;
		$log->debug("Entering get_purchase_orders(".$id.") method ...");
		global $app_strings;
		require_once('modules/PurchaseOrder/PurchaseOrder.php');
		$focus = new Order();

		$button = '';

		$returnset = '&return_module=Products&return_action=DetailView&return_id='.$id;

		$query = "SELECT crmentity.*,
				purchaseorder.*,
				products.productname,
				poproductrel.productid
			FROM purchaseorder
			INNER JOIN crmentity
				ON crmentity.crmid = purchaseorder.purchaseorderid
			INNER JOIN poproductrel
				ON poproductrel.purchaseorderid = purchaseorder.purchaseorderid
			INNER JOIN products
				ON products.productid = poproductrel.productid
			LEFT JOIN pogrouprelation
				ON purchaseorder.purchaseorderid = pogrouprelation.purchaseorderid
			LEFT JOIN groups
				ON groups.groupname = pogrouprelation.groupname
			WHERE crmentity.deleted = 0
			AND products.productid = ".$id;
		$log->debug("Exiting get_purchase_orders method ...");
		return GetRelatedList('Products','PurchaseOrder',$focus,$query,$button,$returnset);
	}
	function get_salesorder($id)
	{
		global $log;
		$log->debug("Entering get_salesorder(".$id.") method ...");
		global $app_strings;
		require_once('modules/SalesOrder/SalesOrder.php');
        $focus = new SalesOrder();
 
		$button = '';
		$returnset = '&return_module=Products&return_action=DetailView&return_id='.$id;

		$query = "SELECT crmentity.*,
				salesorder.*,
				products.productname AS productname,
				account.accountname
			FROM salesorder
			INNER JOIN crmentity
				ON crmentity.crmid = salesorder.salesorderid
			INNER JOIN soproductrel
				ON soproductrel.salesorderid = salesorder.salesorderid
			INNER JOIN products
				ON products.productid = soproductrel.productid
			LEFT OUTER JOIN account
				ON account.accountid = salesorder.accountid
			LEFT JOIN sogrouprelation
				ON salesorder.salesorderid = sogrouprelation.salesorderid
			LEFT JOIN groups
				ON groups.groupname = sogrouprelation.groupname
			WHERE crmentity.deleted = 0
			AND products.productid = ".$id;
		$log->debug("Exiting get_salesorder method ...");
		return GetRelatedList('Products','SalesOrder',$focus,$query,$button,$returnset);
	}
	function get_invoices($id)
	{
		global $log;
		$log->debug("Entering get_invoices(".$id.") method ...");
		global $app_strings;
		require_once('modules/Invoice/Invoice.php');
		$focus = new Invoice();

		$button = '';
		$returnset = '&return_module=Products&return_action=DetailView&return_id='.$id;


		$query = "SELECT crmentity.*,
				invoice.*,
				invoiceproductrel.quantity,
				account.accountname
			FROM invoice
			INNER JOIN crmentity
				ON crmentity.crmid = invoice.invoiceid
			LEFT OUTER JOIN account
				ON account.accountid = invoice.accountid
			INNER JOIN invoiceproductrel
				ON invoiceproductrel.invoiceid = invoice.invoiceid
			LEFT JOIN invoicegrouprelation
				ON invoice.invoiceid = invoicegrouprelation.invoiceid
			LEFT JOIN groups
				ON groups.groupname = invoicegrouprelation.groupname
			WHERE crmentity.deleted = 0
			AND invoiceproductrel.productid = ".$id;
		$log->debug("Exiting get_invoices method ...");
		return GetRelatedList('Products','Invoice',$focus,$query,$button,$returnset);
	}
	function get_product_pricebooks($id)
	{     
		global $log;
		$log->debug("Entering get_product_pricebooks(".$id.") method ...");
		global $mod_strings;
		require_once('modules/PriceBooks/PriceBook.php');
		$focus = new PriceBook();
		$button = '';
		$returnset = '&return_module=Products&return_action=DetailView&return_id='.$id;


		$query = "SELECT crmentity.crmid,
				pricebook.*,
				pricebookproductrel.productid as prodid
			FROM pricebook
			INNER JOIN crmentity
				ON crmentity.crmid = pricebook.pricebookid
			INNER JOIN pricebookproductrel
				ON pricebookproductrel.pricebookid = pricebook.pricebookid
			WHERE crmentity.deleted = 0
			AND pricebookproductrel.productid = ".$id; 
		$log->debug("Exiting get_product_pricebooks method ...");
		return GetRelatedList('Products','PriceBooks',$focus,$query,$button,$returnset);
	}

	function product_novendor()
	{
		global $log;
		$log->debug("Entering product_novendor() method ...");
		$query = "SELECT products.productname, crmentity.deleted
			FROM products
			INNER JOIN crmentity
				ON crmentity.crmid = products.productid
			WHERE crmentity.deleted = 0
			AND products.vendor_id = ''";
		$result=$this->db->query($query);
		$log->debug("Exiting product_novendor method ...");
		return $this->db->num_rows($result);
	}
	
	
	function create_export_query(&$order_by, &$where)
	{
		global $log;
		$log->debug("Entering create_export_query(".$order_by.",".$where.") method ...");
		if($this->checkIfCustomTableExists('productcf'))
		{

		$query = $this->constructCustomQueryAddendum('productcf','Products') ."    
				products.productid AS productid,
				products.productname AS productname,
				products.productcode AS productcode,
				products.productcategory AS productcategory,
				products.manufacturer AS manufacturer,
				products.product_description AS product_description,
				products.qty_per_unit AS qty_per_unit,
				products.unit_price AS unit_price,
				products.weight AS weight,
				products.pack_size AS pack_size,
				DATE_FORMAT(products.start_date, '%Y-%M-%D') AS start_date,
				DATE_FORMAT(products.expiry_date, '%Y-%M-%D') AS expiry_date,
				products.cost_factor AS cost_factor,
				products.commissionrate AS commissionrate,
				products.commissionmethod AS commissionmethod,
				products.discontinued AS discontinued,
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
			INNER JOIN products
				ON crmentity.crmid = products.productid
			INNER JOIN users
				ON users.id = crmentity.smownerid 
			LEFT JOIN productcf
				ON productcf.productid = products.productid";

		}
		else
		{
			$query = "SELECT products.productid AS productid,
				products.productname AS productname,
				products.productcode AS productcode,
				products.productcategory AS productcategory,
				products.manufacturer AS manufacturer,
				products.product_description AS product_description,
				products.qty_per_unit AS qty_per_unit,
				products.unit_price AS unit_price,
				products.weight AS weight,
				products.pack_size AS pack_size,
				DATE_FORMAT(products.start_date, '%Y-%M-%D') AS start_date,
				DATE_FORMAT(products.expiry_date, '%Y-%M-%D') AS expiry_date,
				products.cost_factor AS cost_factor,
				products.commissionrate AS commissionrate,
				products.commissionmethod AS commissionmethod,
				products.discontinued AS discontinued,
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
			FROM ".$this->table_name ."
			INNER JOIN crmentity
				ON crmentity.crmid = products.productid 
			INNER JOIN users
				ON users.id=crmentity.smownerid ";

		}
	
		  $where_auto = " users.status = 'Active'
                        AND crmentity.deleted = 0 ";



		 if($where != "")
                        $query .= " WHERE ($where) AND ".$where_auto;
                else
                        $query .= " WHERE ".$where_auto;

                if(!empty($order_by))
                        $query .= " ORDER BY $order_by";

		$log->debug("Exiting create_export_query method ...");
                return $query;

	}


}
?>
