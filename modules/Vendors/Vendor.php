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

	var $tab_name = Array('vtiger_crmentity','vtiger_vendor','vtiger_vendorcf');
	var $tab_name_index = Array('vtiger_crmentity'=>'crmid','vtiger_vendor'=>'vendorid','vtiger_vendorcf'=>'vendorid');
	var $column_fields = Array();

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

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'vendorname';
	var $default_sort_order = 'ASC';

	function Vendor() {
		$this->log =LoggerManager::getLogger('vendor');
		$this->log->debug("Entering Vendor() method ...");
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('Vendors');
		$this->log->debug("Exiting Vendor method ...");
	}

	function get_products($id)
	{
		global $log;
		$log->debug("Entering get_products(".$id.") method ...");
		global $app_strings;
		require_once('modules/Products/Product.php');
		$focus = new Product();

		$button = '';

		$returnset = '&return_module=Vendors&return_action=CallRelatedList&return_id='.$id;

		$query = 'select vtiger_products.productid, vtiger_products.productname, vtiger_products.productcode, vtiger_products.commissionrate, vtiger_products.qty_per_unit, vtiger_products.unit_price, vtiger_crmentity.crmid, vtiger_crmentity.smownerid,vtiger_vendor.vendorname from vtiger_products inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_products.productid left outer join vtiger_vendor on vtiger_vendor.vendorid = vtiger_products.vendor_id where vtiger_vendor.vendorid = '.$id.' and vtiger_crmentity.deleted = 0';
		$log->debug("Exiting get_products method ...");
		return GetRelatedList('Vendors','Products',$focus,$query,$button,$returnset);
	}
	function get_purchase_orders($id)
	{
		global $log;
		$log->debug("Entering get_purchase_orders(".$id.") method ...");
		global $app_strings;
		require_once('modules/PurchaseOrder/PurchaseOrder.php');
		$focus = new Order();

		$button = '';

		$returnset = '&return_module=Vendors&return_action=CallRelatedList&return_id='.$id;

		$query = "select vtiger_crmentity.*, vtiger_purchaseorder.*,vtiger_vendor.vendorname from vtiger_purchaseorder inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_purchaseorder.purchaseorderid left outer join vtiger_vendor on vtiger_purchaseorder.vendorid=vtiger_vendor.vendorid left join vtiger_pogrouprelation on vtiger_purchaseorder.purchaseorderid=vtiger_pogrouprelation.purchaseorderid left join vtiger_groups on vtiger_groups.groupname=vtiger_pogrouprelation.groupname where vtiger_crmentity.deleted=0 and vtiger_purchaseorder.vendorid=".$id;
		$log->debug("Exiting get_purchase_orders method ...");
		return GetRelatedList('Vendors','PurchaseOrder',$focus,$query,$button,$returnset);
	}
	function get_contacts($id)
	{
		global $log;
		$log->debug("Entering get_contacts(".$id.") method ...");
		global $app_strings;
		require_once('modules/Contacts/Contact.php');
		$focus = new Contact();

		$button = '';
		$returnset = '&return_module=Vendors&return_action=CallRelatedList&return_id='.$id;

		$query = 'SELECT vtiger_contactdetails.*, vtiger_crmentity.crmid, vtiger_crmentity.smownerid,vtiger_vendorcontactrel.vendorid from vtiger_contactdetails inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_contactdetails.contactid  inner join vtiger_vendorcontactrel on vtiger_vendorcontactrel.contactid=vtiger_contactdetails.contactid left join vtiger_contactgrouprelation on vtiger_contactdetails.contactid=vtiger_contactgrouprelation.contactid left join vtiger_groups on vtiger_groups.groupname=vtiger_contactgrouprelation.groupname where vtiger_crmentity.deleted=0 and vtiger_vendorcontactrel.vendorid = '.$id;
		$log->debug("Exiting get_contacts method ...");
		return GetRelatedList('Vendor','Contacts',$focus,$query,$button,$returnset);

	}
}
?>
