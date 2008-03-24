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

class Vendors extends CRMEntity {
	var $log;
	var $db;
	var $table_name = "vtiger_vendor";
	var $tab_name = Array('vtiger_crmentity','vtiger_vendor','vtiger_vendorcf');
	var $tab_name_index = Array('vtiger_crmentity'=>'crmid','vtiger_vendor'=>'vendorid','vtiger_vendorcf'=>'vendorid');
	var $column_fields = Array();

        //Pavani: Assign value to entity_table
        var $entity_table = "vtiger_crmentity";
        var $sortby_fields = Array('vendorname','category');

        // This is the list of vtiger_fields that are in the lists.
	var $list_fields = Array(
                                'Vendor Name'=>Array('vendor'=>'vendorname'),
                                'Phone'=>Array('vendor'=>'phone'),
                                'Email'=>Array('vendor'=>'email'),
                                'Category'=>Array('vendor'=>'category')
                                );
        var $list_fields_name = Array(
                                        'Vendor Name'=>'vendorname',
                                        'Phone'=>'phone',
                                        'Email'=>'email',
                                        'Category'=>'category'
                                     );
        var $list_link_field= 'vendorname';

	var $search_fields = Array(
                                'Vendor Name'=>Array('vendor'=>'vendorname'),
                                'Phone'=>Array('vendor'=>'phone')
                                );
        var $search_fields_name = Array(
                                        'Vendor Name'=>'vendorname',
                                        'Phone'=>'phone'
                                     );
	//By Pavani..Specifying required fields for vendors
        var $required_fields =  array('vendorname'=>1);
	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'vendorname';
	var $default_sort_order = 'ASC';

	/**	Constructor which will set the column_fields in this object
	 */
	function Vendors() {
		$this->log =LoggerManager::getLogger('vendor');
		$this->log->debug("Entering Vendors() method ...");
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('Vendors');
		$this->log->debug("Exiting Vendor method ...");
	}

	function save_module($module)
	{
	}	

	/**	function used to get the list of products which are related to the vendor
	 *	@param int $id - vendor id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_products($id)
	{
		global $log,$singlepane_view;
		$log->debug("Entering get_products(".$id.") method ...");
		global $app_strings;
		require_once('modules/Products/Products.php');
		$focus = new Products();

		$button = '';

		if($singlepane_view == 'true')
			$returnset = '&return_module=Vendors&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Vendors&return_action=CallRelatedList&return_id='.$id;

		$query = "SELECT vtiger_products.productid, vtiger_products.productname, vtiger_products.productcode, 
				vtiger_products.commissionrate, vtiger_products.qty_per_unit, vtiger_products.unit_price, 
				vtiger_crmentity.crmid, vtiger_crmentity.smownerid,vtiger_vendor.vendorname 
			  FROM vtiger_products 
			  INNER JOIN vtiger_vendor ON vtiger_vendor.vendorid = vtiger_products.vendor_id 
			  INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_products.productid 
			  WHERE vtiger_crmentity.deleted = 0 AND vtiger_vendor.vendorid = $id";

		$log->debug("Exiting get_products method ...");
		return GetRelatedList('Vendors','Products',$focus,$query,$button,$returnset);
	}

	/**	function used to get the list of purchase orders which are related to the vendor
	 *	@param int $id - vendor id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_purchase_orders($id)
	{
		global $log,$singlepane_view;
		$log->debug("Entering get_purchase_orders(".$id.") method ...");
		global $app_strings;
		require_once('modules/PurchaseOrder/PurchaseOrder.php');
		$focus = new PurchaseOrder();

		$button = '';

		if($singlepane_view == 'true')
			$returnset = '&return_module=Vendors&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Vendors&return_action=CallRelatedList&return_id='.$id;

		$query = "select case when (vtiger_users.user_name not like '') then vtiger_users.user_name else vtiger_groups.groupname end as user_name,vtiger_crmentity.*, vtiger_purchaseorder.*,vtiger_vendor.vendorname from vtiger_purchaseorder inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_purchaseorder.purchaseorderid left outer join vtiger_vendor on vtiger_purchaseorder.vendorid=vtiger_vendor.vendorid left join vtiger_pogrouprelation on vtiger_purchaseorder.purchaseorderid=vtiger_pogrouprelation.purchaseorderid left join vtiger_groups on vtiger_groups.groupname=vtiger_pogrouprelation.groupname left join vtiger_users on vtiger_users.id=vtiger_crmentity.smownerid where vtiger_crmentity.deleted=0 and vtiger_purchaseorder.vendorid=".$id;
		$log->debug("Exiting get_purchase_orders method ...");
		return GetRelatedList('Vendors','PurchaseOrder',$focus,$query,$button,$returnset);
	}
	//Pavani: Function to create, export query for vendors module
        /** Function to export the vendors in CSV Format
        * @param reference variable - where condition is passed when the query is executed
        * Returns Export Vendors Query.
        */
        function create_export_query($where)
        {
                global $log;
                global $current_user;
                $log->debug("Entering create_export_query(".$where.") method ...");

                include("include/utils/ExportUtils.php");

                //To get the Permitted fields query and the permitted fields list
                $sql = getPermittedFieldsQuery("Vendors", "detail_view");
                $fields_list = getFieldsListFromQuery($sql);

                $query = "SELECT $fields_list FROM ".$this->entity_table."
                                INNER JOIN vtiger_vendor
                                        ON vtiger_crmentity.crmid = vtiger_vendor.vendorid
                                LEFT JOIN vtiger_vendorcf
                                        ON vtiger_vendorcf.vendorid=vtiger_vendor.vendorid
                                LEFT JOIN vtiger_seattachmentsrel
                                        ON vtiger_vendor.vendorid=vtiger_seattachmentsrel.crmid
                                LEFT JOIN vtiger_attachments
                                ON vtiger_seattachmentsrel.attachmentsid = vtiger_attachments.attachmentsid
                                LEFT JOIN vtiger_users
                                        ON vtiger_crmentity.smownerid = vtiger_users.id and vtiger_users.status='Active'
                                ";
                $where_auto = " vtiger_crmentity.deleted = 0 ";

                 if($where != "")
                   $query .= "  WHERE ($where) AND ".$where_auto;
                else
                   $query .= "  WHERE ".$where_auto;

                $log->debug("Exiting create_export_query method ...");
                return $query;
        }

	/**	function used to get the list of contacts which are related to the vendor
	 *	@param int $id - vendor id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_contacts($id)
	{
		global $log,$singlepane_view;
		$log->debug("Entering get_contacts(".$id.") method ...");
		global $app_strings;
		require_once('modules/Contacts/Contacts.php');
		$focus = new Contacts();

		$button = '';
		if($singlepane_view == 'true')
			$returnset = '&return_module=Vendors&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Vendors&return_action=CallRelatedList&return_id='.$id;

		$query = 'SELECT case when (vtiger_users.user_name not like "") then vtiger_users.user_name else vtiger_groups.groupname end as user_name,vtiger_contactdetails.*, vtiger_crmentity.crmid, vtiger_crmentity.smownerid,vtiger_vendorcontactrel.vendorid,vtiger_account.accountname from vtiger_contactdetails inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_contactdetails.contactid  inner join vtiger_vendorcontactrel on vtiger_vendorcontactrel.contactid=vtiger_contactdetails.contactid left join vtiger_contactgrouprelation on vtiger_contactdetails.contactid=vtiger_contactgrouprelation.contactid left join vtiger_groups on vtiger_groups.groupname=vtiger_contactgrouprelation.groupname left join vtiger_account on vtiger_account.accountid = vtiger_contactdetails.accountid left join vtiger_users on vtiger_users.id=vtiger_crmentity.smownerid where vtiger_crmentity.deleted=0 and vtiger_vendorcontactrel.vendorid = '.$id;
		$log->debug("Exiting get_contacts method ...");
		return GetRelatedList('Vendors','Contacts',$focus,$query,$button,$returnset);

	}
	
	function getSortOrder()
        {
                global $log;
                $log->debug("Entering getSortOrder() method ...");
                if(isset($_REQUEST['sorder']))
                        $sorder = $_REQUEST['sorder'];
                else
                        $sorder = (($_SESSION['VENDORS_SORT_ORDER'] != '')?($_SESSION['VENDORS_SORT_ORDER']):($this->default_sort_order));
                $log->debug("Exiting getSortOrder() method ...");
                return $sorder;
        }

	function getOrderBy()
	{
		global $log;
		$log->debug("Entering getOrderBy() method ...");
		if (isset($_REQUEST['order_by']))
			$order_by = $_REQUEST['order_by'];
		else
			$order_by = (($_SESSION['VENDORS_ORDER_BY'] != '')?($_SESSION['VENDORS_ORDER_BY']):($this->default_order_by));
		$log->debug("Exiting getOrderBy method ...");
		return $order_by;
	}

}
?>
