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


class Vendor extends CRMEntity {
	var $log;
	var $db;


	// Stored fields
	var $id;
	var $mode;

	var $tab_name = Array('crmentity','vendor','vendorcf');
	var $tab_name_index = Array('crmentity'=>'crmid','vendor'=>'vendorid','vendorcf'=>'vendorid');
	var $column_fields = Array();

	var $sortby_fields = Array('vendorname','company_name','category');		  

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
/*	
//	var $combofieldNames = Array('manufacturer'=>'manufacturer_dom'
                      ,'productcategory'=>'productcategory_dom');
*/

	function Vendor() {
		$this->log =LoggerManager::getLogger('vendor');
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('Vendor');
	}

  function get_summary_text()
        {
                return $this->name;
        }
	function get_products($id)
	{
		$query = 'select products.productid, products.productname, products.productcode, products.commissionrate, products.qty_per_unit, products.unit_price, crmentity.crmid, crmentity.smownerid,vendor.vendorname from products inner join crmentity on crmentity.crmid = products.productid left outer join vendor on vendor.vendorid = products.vendor_id where vendor.vendorid = '.$id.' and crmentity.deleted = 0';
	      	renderRelatedProducts($query,$id,'vendor_id');
        }
	function get_purchase_orders($id)
	{
		$query = "select crmentity.*, purchaseorder.*,vendor.vendorname from purchaseorder inner join crmentity on crmentity.crmid=purchaseorder.purchaseorderid left outer join vendor on purchaseorder.vendorid=vendor.vendorid where crmentity.deleted=0 and purchaseorder.vendorid=".$id;
	      	renderRelatedOrders($query,$id,'vendor_id');
        }
	function get_contacts($id)
        {
 		$query = 'SELECT contactdetails.*, crmentity.crmid, crmentity.smownerid,vendorcontactrel.vendorid from contactdetails inner join crmentity on crmentity.crmid = contactdetails.contactid  inner join vendorcontactrel on vendorcontactrel.contactid=contactdetails.contactid where crmentity.deleted=0 and vendorcontactrel.vendorid = '.$id;
               renderRelatedContacts($query,$id);
       }
       function get_related_contacts($id)
       {
               $query = 'SELECT vendorcontactrel.*, crmentity.crmid from vendorcontactrel inner join crmentity on crmentity.crmid = vendorcontactrel.contactid where crmentity.deleted=0 and vendorcontactrel.vendorid = '.$id;
               $result = $this->db->query($query);
               $cnt_id = array();
               $cnt_list = '';
               if($this->db->num_rows($result)!=0)
               {
                       while($row = $this->db->fetch_array($result))
                       {
                               $cnt_id[] = $row['contactid'];
                       }
                       for ($i = 0; $i < count($cnt_id); $i++)
                       {
                               $cnt_list .= $cnt_id[$i] . ',';
                       }

                       if ($cnt_list)
                       {
                               $cnt_list = substr($cnt_list, 0, strlen($cnt_list) -1);
                       }

               }
               return $cnt_list;
         }

}
?>
