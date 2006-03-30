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

class Vendor extends CRMEntity {
	var $log;
	var $db;


	// Stored fields
	var $id;
	var $mode;

	var $tab_name = Array('crmentity','vendor','vendorcf');
	var $tab_name_index = Array('crmentity'=>'crmid','vendor'=>'vendorid','vendorcf'=>'vendorid');
	var $column_fields = Array();

	var $sortby_fields = Array('vendorname','category');		  

        // This is the list of fields that are in the lists.
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


	var $list_mode;
	var $popup_type;

	var $search_fields = Array(
                                'Vendor Name'=>Array('vendor'=>'vendorname'),
                                'Phone'=>Array('vendor'=>'phone')
                                );
        var $search_fields_name = Array(
                                        'Vendor Name'=>'vendorname',
                                        'Phone'=>'phone'
                                     );

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'vendorname';
	var $default_sort_order = 'ASC';

	function Vendor() {
		$this->log =LoggerManager::getLogger('vendor');
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('Vendors');
	}

	function get_products($id)
	{
		global $app_strings;
		require_once('modules/Products/Product.php');
		$focus = new Product();

		$button = '';

		$returnset = '&return_module=Vendors&return_action=DetailView&return_id='.$id;

		$query = 'select products.productid, products.productname, products.productcode, products.commissionrate, products.qty_per_unit, products.unit_price, crmentity.crmid, crmentity.smownerid,vendor.vendorname from products inner join crmentity on crmentity.crmid = products.productid left outer join vendor on vendor.vendorid = products.vendor_id where vendor.vendorid = '.$id.' and crmentity.deleted = 0';
		return GetRelatedList('Vendors','Products',$focus,$query,$button,$returnset);
	}
	function get_purchase_orders($id)
	{
		global $app_strings;
		require_once('modules/PurchaseOrder/PurchaseOrder.php');
		$focus = new Order();

		$button = '';

		$returnset = '&return_module=Vendors&return_action=DetailView&return_id='.$id;

		$query = "select crmentity.*, purchaseorder.*,vendor.vendorname from purchaseorder inner join crmentity on crmentity.crmid=purchaseorder.purchaseorderid left outer join vendor on purchaseorder.vendorid=vendor.vendorid left join pogrouprelation on purchaseorder.purchaseorderid=pogrouprelation.purchaseorderid left join groups on groups.groupname=pogrouprelation.groupname where crmentity.deleted=0 and purchaseorder.vendorid=".$id;
		return GetRelatedList('Vendors','PurchaseOrder',$focus,$query,$button,$returnset);
	}
	function get_contacts($id)
	{
		global $app_strings;
		require_once('modules/Contacts/Contact.php');
		$focus = new Contact();

		$button = '';
		$returnset = '&return_module=Vendors&return_action=DetailView&return_id='.$id;

		$query = 'SELECT contactdetails.*, crmentity.crmid, crmentity.smownerid,vendorcontactrel.vendorid from contactdetails inner join crmentity on crmentity.crmid = contactdetails.contactid  inner join vendorcontactrel on vendorcontactrel.contactid=contactdetails.contactid left join contactgrouprelation on contactdetails.contactid=contactgrouprelation.contactid left join groups on groups.groupname=contactgrouprelation.groupname where crmentity.deleted=0 and vendorcontactrel.vendorid = '.$id;
		return GetRelatedList('Vendor','Contacts',$focus,$query,$button,$returnset);

	}
}
?>
