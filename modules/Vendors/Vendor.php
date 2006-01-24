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
/*	
//	var $combofieldNames = Array('manufacturer'=>'manufacturer_dom'
                      ,'productcategory'=>'productcategory_dom');
*/

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'vendorname';
	var $default_sort_order = 'ASC';

	function Vendor() {
		$this->log =LoggerManager::getLogger('vendor');
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('Vendors');
	}

  function get_summary_text()
        {
                return $this->name;
        }
	function get_products($id)
	{
		global $app_strings;
		require_once('modules/Products/Product.php');
		$focus = new Product();

		$button = '';

		if(isPermitted("Products",1,"") == 'yes')
		{
			$button .= '<input title="New Product" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Products\';this.form.return_module.value=\'Vendors\';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_PRODUCT'].'">&nbsp;';
		}
		if(isPermitted("Products",3,"") == 'yes')
		{
			if($focus->product_novendor() !=0)
			{
				$button .= '<input title="Change" accessKey="" tabindex="2" type="button" class="button" value="'.$app_strings['LBL_SELECT_PRODUCT_BUTTON_LABEL'].'" name="Button" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Products&action=Popup&return_module=Vendors&popuptype=detailview&form=EditView&form_submit=false&recordid='.$_REQUEST["record"].'","test","width=600,height=400,resizable=1,scrollbars=1");\'>&nbsp;';
			}
		}
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

		if(isPermitted("PurchaseOrder",1,"") == 'yes')
		{

			$button .= '<input title="'.$app_strings['LBL_PORDER_BUTTON_TITLE'].'" accessyKey="O" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'PurchaseOrder\';this.form.return_module.value=\'Vendors\';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$app_strings['LBL_PORDER_BUTTON'].'">&nbsp;';
		}
		$returnset = '&return_module=Vendors&return_action=DetailView&return_id='.$id;


		$query = "select crmentity.*, purchaseorder.*,vendor.vendorname from purchaseorder inner join crmentity on crmentity.crmid=purchaseorder.purchaseorderid left outer join vendor on purchaseorder.vendorid=vendor.vendorid where crmentity.deleted=0 and purchaseorder.vendorid=".$id;
		return GetRelatedList('Vendors','PurchaseOrder',$focus,$query,$button,$returnset);
	}
	function get_contacts($id)
	{
		global $app_strings;
		require_once('modules/Contacts/Contact.php');
		$focus = new Contact();

		$button = '';
		if(isPermitted("Contacts",1,"") == 'yes')
		{
			$button .= '<input title="'.$app_strings['LBL_SELECT_CONTACT_BUTTON_TITLE'].'" accessKey="'.$app_strings['LBL_SELECT_CONTACT_BUTTON_KEY'].'" type="button" class="button" value="'.$app_strings['LBL_SELECT_CONTACT_BUTTON_LABEL'].'" name="Button" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Contacts&action=Popup&return_module=Products&smodule=VENDOR&popuptype=detailview&form=EditView&form_submit=false&recordid='.$_REQUEST["record"].'","test","width=600,height=400,resizable=1,scrollbars=1");\'>&nbsp;';
		}
		$returnset = '&return_module=Vendors&return_action=DetailView&return_id='.$id;

		$query = 'SELECT contactdetails.*, crmentity.crmid, crmentity.smownerid,vendorcontactrel.vendorid from contactdetails inner join crmentity on crmentity.crmid = contactdetails.contactid  inner join vendorcontactrel on vendorcontactrel.contactid=contactdetails.contactid where crmentity.deleted=0 and vendorcontactrel.vendorid = '.$id;
		return GetRelatedList('Vendor','Contacts',$focus,$query,$button,$returnset);

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
