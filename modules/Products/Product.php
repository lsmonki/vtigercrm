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
require_once('user_privileges/default_module_view.php');

class Product extends CRMEntity {
	var $log;
	var $db;

	 // Josh added for importing and exporting -added in patch2
        var $unit_price;
        var $table_name = "vtiger_products";
        var $object_name = "Product";
        var $entity_table = "vtiger_crmentity";
        var $required_fields = Array(
                'productname'=>1
        );


	var $tab_name = Array('vtiger_crmentity','vtiger_products','vtiger_productcf','vtiger_seproductsrel','vtiger_producttaxrel','vtiger_attachments');
	var $tab_name_index = Array('vtiger_crmentity'=>'crmid','vtiger_products'=>'productid','vtiger_productcf'=>'productid','vtiger_seproductsrel'=>'productid','vtiger_producttaxrel'=>'productid','vtiger_attachments'=>'attachmentsid');
	var $column_fields = Array();

	var $sortby_fields = Array('productname','productcode','commissionrate');		  

        // This is the list of vtiger_fields that are in the lists.
        var $list_fields = Array(
                                'Product Name'=>Array('products'=>'productname'),
                                'Part Number'=>Array('products'=>'productcode'),
                                'Commission Rate'=>Array('products'=>'commissionrate'),
                                'Qty/Unit'=>Array('products'=>'qty_per_unit'),
                                'Unit Price'=>Array('products'=>'unit_price')
                                );
        var $list_fields_name = Array(
                                        'Product Name'=>'productname',
                                        'Part Number'=>'productcode',
                                        'Commission Rate'=>'commissionrate',
                                        'Qty/Unit'=>'qty_per_unit',
                                        'Unit Price'=>'unit_price'
                                     );
        var $list_link_field= 'productname';

	var $search_fields = Array(
                                'Product Name'=>Array('products'=>'productname'),
                                'Part Number'=>Array('products'=>'productcode'),
                                'Unit Price'=>Array('products'=>'unit_price')
                                );
        var $search_fields_name = Array(
                                        'Product Name'=>'productname',
                                        'Part Number'=>'productcode',
                                        'Unit Price'=>'unit_price'
                                     );
	
	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'productname';
	var $default_sort_order = 'ASC';

	/**	Constructor which will set the column_fields in this object
	 */
	function Product() {
		$this->log =LoggerManager::getLogger('product');
		$this->log->debug("Entering Product() method ...");
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('Products');
		$this->log->debug("Exiting Product method ...");
	}

	/**	Function used to get the sort order for Product listview
	 *	@return string	$sorder	- first check the $_REQUEST['sorder'] if request value is empty then check in the $_SESSION['PRODUCTS_SORT_ORDER'] if this session value is empty then default sort order will be returned. 
	 */
	function getSortOrder()
	{
		global $log;
		$log->debug("Entering getSortOrder() method ...");
		if(isset($_REQUEST['sorder']))
			$sorder = $_REQUEST['sorder'];
		else
			$sorder = (($_SESSION['PRODUCTS_SORT_ORDER'] != '')?($_SESSION['PRODUCTS_SORT_ORDER']):($this->default_sort_order));
		$log->debug("Exiting getSortOrder() method ...");
		return $sorder;
	}

	/**	Function used to get the order by value for Product listview
	 *	@return string	$order_by  - first check the $_REQUEST['order_by'] if request value is empty then check in the $_SESSION['PRODUCTS_ORDER_BY'] if this session value is empty then default order by will be returned. 
	 */
	function getOrderBy()
	{
		global $log;
		$log->debug("Entering getOrderBy() method ...");
		if (isset($_REQUEST['order_by']))
			$order_by = $_REQUEST['order_by'];
		else
			$order_by = (($_SESSION['PRODUCTS_ORDER_BY'] != '')?($_SESSION['PRODUCTS_ORDER_BY']):($this->default_order_by));
		$log->debug("Exiting getOrderBy method ...");
		return $order_by;
	}

	/**	function used to get the attachment which are related to the product
	 *	@param int $id - product id to which we want to retrieve the attachments and notes
         *      @return array - array which will be returned from the function getAttachmentsAndNotes
        **/
	function get_attachments($id)
	{
		global $log;
		$log->debug("Entering get_attachments(".$id.") method ...");

		$query = "SELECT vtiger_notes.title, 'Notes      ' AS ActivityType,
			vtiger_notes.filename, vtiger_attachments.type  AS FileType,
				crm2.modifiedtime AS lastmodified,
			vtiger_seattachmentsrel.attachmentsid AS attachmentsid,
			vtiger_notes.notesid AS crmid, vtiger_crmentity.createdtime,
			vtiger_notes.notecontent AS description,
			vtiger_users.user_name
			FROM vtiger_notes
			INNER JOIN vtiger_senotesrel
				ON vtiger_senotesrel.notesid = vtiger_notes.notesid
			INNER JOIN vtiger_crmentity
				ON vtiger_crmentity.crmid = vtiger_senotesrel.crmid
			INNER JOIN vtiger_crmentity AS crm2
				ON crm2.crmid = vtiger_notes.notesid
				AND crm2.deleted = 0
			LEFT JOIN vtiger_seattachmentsrel
				ON vtiger_seattachmentsrel.crmid = vtiger_notes.notesid
			LEFT JOIN vtiger_attachments
				ON vtiger_seattachmentsrel.attachmentsid = vtiger_attachments.attachmentsid
			INNER JOIN vtiger_users
				ON vtiger_crmentity.smcreatorid = vtiger_users.id
			WHERE vtiger_crmentity.crmid = ".$id."
		UNION ALL
			SELECT vtiger_attachments.description AS title,
				'Attachments' AS ActivityType,
			vtiger_attachments.name AS filename,
			vtiger_attachments.type AS FileType,
				crm2.modifiedtime AS lastmodified,
			vtiger_attachments.attachmentsid AS attachmentsid,
			vtiger_seattachmentsrel.attachmentsid AS crmid,
			vtiger_crmentity.createdtime,
			vtiger_attachments.description, vtiger_users.user_name
			FROM vtiger_attachments
			INNER JOIN vtiger_seattachmentsrel
				ON vtiger_seattachmentsrel.attachmentsid = vtiger_attachments.attachmentsid
			INNER JOIN vtiger_crmentity
				ON vtiger_crmentity.crmid = vtiger_seattachmentsrel.crmid
			INNER JOIN vtiger_crmentity AS crm2
				ON crm2.crmid = vtiger_attachments.attachmentsid
			INNER JOIN vtiger_users
				ON vtiger_crmentity.smcreatorid = vtiger_users.id
			WHERE vtiger_crmentity.crmid = ".$id;	

		$log->debug("Exiting get_attachments method ...");
        	return getAttachmentsAndNotes('Products',$query,$id);
	}

	/**	function used to get the list of potentials which are related to the product
	 *	@param int $id - product id 
	 *	@return void - but this function will call the function renderRelatedPotentials with parameter query
	 */
	function get_opportunities($id)
	{
		global $log;
		$log->debug("Entering get_opportunities(".$id.") method ...");
		$query = "SELECT vtiger_potential.potentialid, vtiger_potential.potentialname,
			vtiger_potential.potentialtype, vtiger_products.productid,
			vtiger_products.productname, vtiger_products.qty_per_unit,
			vtiger_products.unit_price, vtiger_products.expiry_date
			FROM vtiger_potential
			INNER JOIN vtiger_products
				ON vtiger_potential.productid = vtiger_products.productid
			LEFT JOIN vtiger_potentialgrouprelation
				ON vtiger_potential.potentialid = vtiger_potentialgrouprelation.potentialid
			LEFT JOIN vtiger_groups
				ON vtiger_groups.groupname = vtiger_potentialgrouprelation.groupname
			inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_products.productid
			WHERE vtiger_crmentity.deleted = 0
			AND vtiger_products.productid = ".$id;
		$log->debug("Exiting get_opportunities method ...");
          renderRelatedPotentials($query);
        }

	/**	function used to get the list of tickets which are related to the product
	 *	@param int $id - product id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_tickets($id)
	{
		global $log, $singlepane_view;
		$log->debug("Entering get_tickets(".$id.") method ...");
		global $mod_strings;
		require_once('modules/HelpDesk/HelpDesk.php');
		$focus = new HelpDesk();

		$button = '';

		if($singlepane_view == 'true')
			$returnset = '&return_module=Products&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Products&return_action=CallRelatedList&return_id='.$id;

		$query = "SELECT vtiger_users.user_name, vtiger_users.id,
			vtiger_products.productid, vtiger_products.productname,
			vtiger_troubletickets.ticketid,
			vtiger_troubletickets.parent_id, vtiger_troubletickets.title,
			vtiger_troubletickets.status, vtiger_troubletickets.priority,
			vtiger_crmentity.crmid, vtiger_crmentity.smownerid,
			vtiger_crmentity.modifiedtime
			FROM vtiger_troubletickets
			INNER JOIN vtiger_crmentity
				ON vtiger_crmentity.crmid = vtiger_troubletickets.ticketid
			LEFT JOIN vtiger_products
				ON vtiger_products.productid = vtiger_troubletickets.product_id
			LEFT JOIN vtiger_users
				ON vtiger_users.id = vtiger_crmentity.smownerid
			LEFT JOIN vtiger_ticketgrouprelation
				ON vtiger_troubletickets.ticketid = vtiger_ticketgrouprelation.ticketid
			LEFT JOIN vtiger_groups
				ON vtiger_groups.groupname = vtiger_ticketgrouprelation.groupname
			WHERE vtiger_crmentity.deleted = 0
			AND vtiger_products.productid = ".$id;
	$log->debug("Exiting get_tickets method ...");
		return GetRelatedList('Products','HelpDesk',$focus,$query,$button,$returnset);
	}

	/**	function used to get the list of activities which are related to the product
	 *	@param int $id - product id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_activities($id)
	{
		global $log, $singlepane_view;
		$log->debug("Entering get_activities(".$id.") method ...");
		global $app_strings;
	
		require_once('modules/Calendar/Activity.php');

        	//if($this->column_fields['contact_id']!=0 && $this->column_fields['contact_id']!='')
        	$focus = new Activity();

		$button = '';

		if($singlepane_view == 'true')
			$returnset = '&return_module=Products&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Products&return_action=CallRelatedList&return_id='.$id;


		$query = "SELECT vtiger_contactdetails.lastname,
			vtiger_contactdetails.firstname,
			vtiger_contactdetails.contactid,
			vtiger_activity.*,
			vtiger_seactivityrel.*,
			vtiger_crmentity.crmid, vtiger_crmentity.smownerid,
			vtiger_crmentity.modifiedtime,
			vtiger_users.user_name,
			vtiger_recurringevents.recurringtype
			FROM vtiger_activity
			INNER JOIN vtiger_seactivityrel
				ON vtiger_seactivityrel.activityid = vtiger_activity.activityid
			INNER JOIN vtiger_crmentity
				ON vtiger_crmentity.crmid=vtiger_activity.activityid
			LEFT JOIN vtiger_cntactivityrel
				ON vtiger_cntactivityrel.activityid = vtiger_activity.activityid
			LEFT JOIN vtiger_contactdetails
				ON vtiger_contactdetails.contactid = vtiger_cntactivityrel.contactid
			LEFT JOIN vtiger_users
				ON vtiger_users.id = vtiger_crmentity.smownerid
			LEFT OUTER JOIN vtiger_recurringevents
				ON vtiger_recurringevents.activityid = vtiger_activity.activityid
			LEFT JOIN vtiger_activitygrouprelation
				ON vtiger_activitygrouprelation.activityid = vtiger_crmentity.crmid
			LEFT JOIN vtiger_groups
				ON vtiger_groups.groupname = vtiger_activitygrouprelation.groupname
			WHERE vtiger_seactivityrel.crmid=".$id."
			AND (activitytype = 'Task'
				OR vtiger_activitytype = 'Call'
				OR vtiger_activitytype = 'Meeting')";
		$log->debug("Exiting get_activities method ...");
		return GetRelatedList('Products','Calendar',$focus,$query,$button,$returnset);
	}

	/**	function used to get the list of quotes which are related to the product
	 *	@param int $id - product id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_quotes($id)
	{
		global $log, $singlepane_view;
		$log->debug("Entering get_quotes(".$id.") method ...");	
		global $app_strings;
		require_once('modules/Quotes/Quote.php');	
		$focus = new Quote();
	
		$button = '';
		if($singlepane_view == 'true')
			$returnset = '&return_module=Products&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Products&return_action=CallRelatedList&return_id='.$id;


		$query = "SELECT vtiger_crmentity.*,
			vtiger_quotes.*,
			vtiger_potential.potentialname,
			vtiger_account.accountname,
			vtiger_inventoryproductrel.productid
			FROM vtiger_quotes
			INNER JOIN vtiger_crmentity
				ON vtiger_crmentity.crmid = vtiger_quotes.quoteid
			INNER JOIN vtiger_inventoryproductrel
				ON vtiger_inventoryproductrel.id = vtiger_quotes.quoteid
			LEFT OUTER JOIN vtiger_account
				ON vtiger_account.accountid = vtiger_quotes.accountid
			LEFT OUTER JOIN vtiger_potential
				ON vtiger_potential.potentialid = vtiger_quotes.potentialid
			LEFT JOIN vtiger_quotegrouprelation
				ON vtiger_quotes.quoteid = vtiger_quotegrouprelation.quoteid
			LEFT JOIN vtiger_groups
				ON vtiger_groups.groupname = vtiger_quotegrouprelation.groupname
			WHERE vtiger_crmentity.deleted = 0
			AND vtiger_inventoryproductrel.productid = ".$id;
		$log->debug("Exiting get_quotes method ...");
		return GetRelatedList('Products','Quotes',$focus,$query,$button,$returnset);
	}

	/**	function used to get the list of purchase orders which are related to the product
	 *	@param int $id - product id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_purchase_orders($id)
	{
		global $log,$singlepane_view;
		$log->debug("Entering get_purchase_orders(".$id.") method ...");
		global $app_strings;
		require_once('modules/PurchaseOrder/PurchaseOrder.php');
		$focus = new Order();

		$button = '';

		if($singlepane_view == 'true')
			$returnset = '&return_module=Products&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Products&return_action=CallRelatedList&return_id='.$id;

		$query = "SELECT vtiger_crmentity.*,
			vtiger_purchaseorder.*,
			vtiger_products.productname,
			vtiger_inventoryproductrel.productid
			FROM vtiger_purchaseorder
			INNER JOIN vtiger_crmentity
				ON vtiger_crmentity.crmid = vtiger_purchaseorder.purchaseorderid
			INNER JOIN vtiger_inventoryproductrel
				ON vtiger_inventoryproductrel.id = vtiger_purchaseorder.purchaseorderid
			INNER JOIN vtiger_products
				ON vtiger_products.productid = vtiger_inventoryproductrel.productid
			LEFT JOIN vtiger_pogrouprelation
				ON vtiger_purchaseorder.purchaseorderid = vtiger_pogrouprelation.purchaseorderid
			LEFT JOIN vtiger_groups
				ON vtiger_groups.groupname = vtiger_pogrouprelation.groupname
			WHERE vtiger_crmentity.deleted = 0
			AND vtiger_products.productid = ".$id;
		$log->debug("Exiting get_purchase_orders method ...");
		return GetRelatedList('Products','PurchaseOrder',$focus,$query,$button,$returnset);
	}

	/**	function used to get the list of sales orders which are related to the product
	 *	@param int $id - product id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_salesorder($id)
	{
		global $log,$singlepane_view;
		$log->debug("Entering get_salesorder(".$id.") method ...");
		global $app_strings;
		require_once('modules/SalesOrder/SalesOrder.php');

	        $focus = new SalesOrder();
 
		$button = '';
		if($singlepane_view == 'true')
			$returnset = '&return_module=Products&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Products&return_action=CallRelatedList&return_id='.$id;

		$query = "SELECT vtiger_crmentity.*,
			vtiger_salesorder.*,
			vtiger_products.productname AS productname,
			vtiger_account.accountname
			FROM vtiger_salesorder
			INNER JOIN vtiger_crmentity
				ON vtiger_crmentity.crmid = vtiger_salesorder.salesorderid
			INNER JOIN vtiger_inventoryproductrel
				ON vtiger_inventoryproductrel.id = vtiger_salesorder.salesorderid
			INNER JOIN vtiger_products
				ON vtiger_products.productid = vtiger_inventoryproductrel.productid
			LEFT OUTER JOIN vtiger_account
				ON vtiger_account.accountid = vtiger_salesorder.accountid
			LEFT JOIN vtiger_sogrouprelation
				ON vtiger_salesorder.salesorderid = vtiger_sogrouprelation.salesorderid
			LEFT JOIN vtiger_groups
				ON vtiger_groups.groupname = vtiger_sogrouprelation.groupname
			WHERE vtiger_crmentity.deleted = 0
			AND vtiger_products.productid = ".$id;
		$log->debug("Exiting get_salesorder method ...");
		return GetRelatedList('Products','SalesOrder',$focus,$query,$button,$returnset);
	}

	/**	function used to get the list of invoices which are related to the product
	 *	@param int $id - product id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_invoices($id)
	{
		global $log,$singlepane_view;
		$log->debug("Entering get_invoices(".$id.") method ...");
		global $app_strings;
		require_once('modules/Invoice/Invoice.php');
		$focus = new Invoice();

		$button = '';
		if($singlepane_view == 'true')
			$returnset = '&return_module=Products&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Products&return_action=CallRelatedList&return_id='.$id;


		$query = "SELECT vtiger_crmentity.*,
			vtiger_invoice.*,
			vtiger_inventoryproductrel.quantity,
			vtiger_account.accountname
			FROM vtiger_invoice
			INNER JOIN vtiger_crmentity
				ON vtiger_crmentity.crmid = vtiger_invoice.invoiceid
			LEFT OUTER JOIN vtiger_account
				ON vtiger_account.accountid = vtiger_invoice.accountid
			INNER JOIN vtiger_inventoryproductrel
				ON vtiger_inventoryproductrel.id = vtiger_invoice.invoiceid
			LEFT JOIN vtiger_invoicegrouprelation
				ON vtiger_invoice.invoiceid = vtiger_invoicegrouprelation.invoiceid
			LEFT JOIN vtiger_groups
				ON vtiger_groups.groupname = vtiger_invoicegrouprelation.groupname
			WHERE vtiger_crmentity.deleted = 0
			AND vtiger_inventoryproductrel.productid = ".$id;
		$log->debug("Exiting get_invoices method ...");
		return GetRelatedList('Products','Invoice',$focus,$query,$button,$returnset);
	}

	/**	function used to get the list of pricebooks which are related to the product
	 *	@param int $id - product id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_product_pricebooks($id)
	{     
		global $log,$singlepane_view;
		$log->debug("Entering get_product_pricebooks(".$id.") method ...");
		global $mod_strings;
		require_once('modules/PriceBooks/PriceBook.php');
		$focus = new PriceBook();
		$button = '';
		if($singlepane_view == 'true')
			$returnset = '&return_module=Products&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Products&return_action=CallRelatedList&return_id='.$id;


		$query = "SELECT vtiger_crmentity.crmid,
			vtiger_pricebook.*,
			vtiger_pricebookproductrel.productid as prodid
			FROM vtiger_pricebook
			INNER JOIN vtiger_crmentity
				ON vtiger_crmentity.crmid = vtiger_pricebook.pricebookid
			INNER JOIN vtiger_pricebookproductrel
				ON vtiger_pricebookproductrel.pricebookid = vtiger_pricebook.pricebookid
			WHERE vtiger_crmentity.deleted = 0
			AND vtiger_pricebookproductrel.productid = ".$id; 
		$log->debug("Exiting get_product_pricebooks method ...");
		return GetRelatedList('Products','PriceBooks',$focus,$query,$button,$returnset);
	}

	/**	function used to get the number of vendors which are related to the product
	 *	@param int $id - product id
	 *	@return int number of rows - return the number of products which do not have relationship with vendor
	 */
	function product_novendor()
	{
		global $log;
		$log->debug("Entering product_novendor() method ...");
		$query = "SELECT vtiger_products.productname, vtiger_crmentity.deleted
			FROM vtiger_products
			INNER JOIN vtiger_crmentity
				ON vtiger_crmentity.crmid = vtiger_products.productid
			WHERE vtiger_crmentity.deleted = 0
			AND vtiger_products.vendor_id is NULL";
		$result=$this->db->query($query);
		$log->debug("Exiting product_novendor method ...");
		return $this->db->num_rows($result);
	}
	
	/**	function used to get the export query for product
	 *	@param reference &$order_by - reference of the order by variable which will be added with the query
	 *	@param reference &$where - reference of the where variable which will be added with the query
	 *	@return string $query - return the query which will give the list of products to export
	 */	
	function create_export_query(&$order_by, &$where)
	{
		global $log;
		$log->debug("Entering create_export_query(".$order_by.",".$where.") method ...");

		include("include/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("Products", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);

		$query = "SELECT $fields_list FROM ".$this->table_name ."
			INNER JOIN vtiger_crmentity
				ON vtiger_crmentity.crmid = vtiger_products.productid 
			LEFT JOIN vtiger_productcf
				ON vtiger_products.productid = vtiger_productcf.productid
			LEFT JOIN vtiger_seproductsrel
				ON vtiger_seproductsrel.productid = vtiger_products.productid
			LEFT JOIN vtiger_producttaxrel
				ON vtiger_producttaxrel.productid = vtiger_products.productid
			INNER JOIN vtiger_users
				ON vtiger_users.id=vtiger_crmentity.smownerid 

			LEFT JOIN vtiger_crmentity vtiger_crmentityRelatedTo
				ON vtiger_crmentityRelatedTo.crmid = vtiger_seproductsrel.crmid
				
			LEFT JOIN vtiger_leaddetails vtiger_ProductRelatedToLead
				ON vtiger_ProductRelatedToLead.leadid = vtiger_seproductsrel.crmid
			LEFT JOIN vtiger_account vtiger_ProductRelatedToAccount
				ON vtiger_ProductRelatedToAccount.accountid = vtiger_seproductsrel.crmid
			LEFT JOIN vtiger_potential vtiger_ProductRelatedToPotential
				ON vtiger_ProductRelatedToPotential.potentialid = vtiger_seproductsrel.crmid
	
			LEFT JOIN vtiger_contactdetails 
				ON vtiger_contactdetails.contactid = vtiger_products.contactid
			LEFT JOIN vtiger_vendor
				ON vtiger_vendor.vendorid = vtiger_products.vendor_id
			
			WHERE vtiger_crmentity.deleted = 0 AND vtiger_users.status = 'Active'
				AND ((vtiger_seproductsrel.crmid IS NULL
					AND (vtiger_products.contactid = 0 OR vtiger_products.contactid IS NULL))
				OR vtiger_seproductsrel.crmid IN (".getReadEntityIds('Leads').")
				OR vtiger_seproductsrel.crmid IN (".getReadEntityIds('Accounts').")
				OR vtiger_seproductsrel.crmid IN (".getReadEntityIds('Potentials').")
				OR vtiger_products.contactid IN (".getReadEntityIds('Contacts').")) 
			group by vtiger_products.productid
			";
			//ProductRelatedToLead, Account and Potential tables are added to get the Related to field
	

		if($where != "")
                        $query .= " AND ($where) ";

                if(!empty($order_by))
                        $query .= " ORDER BY $order_by";

		$log->debug("Exiting create_export_query method ...");
                return $query;

	}


}
?>
