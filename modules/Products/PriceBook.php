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


class PriceBook extends CRMEntity {
	var $log;
	var $db;


	// Stored fields
	var $id;
	var $mode;

	var $tab_name = Array('crmentity','pricebook','pricebookcf');
	var $tab_name_index = Array('crmentity'=>'crmid','pricebook'=>'pricebookid','pricebookcf'=>'pricebookid');
	var $column_fields = Array();

	var $sortby_fields = Array('bookname');		  

        // This is the list of fields that are in the lists.
	var $list_fields = Array(
                                'Price Book Name'=>Array('pricebook'=>'bookname'),
                                'Active'=>Array('pricebook'=>'active')
                                );
        var $list_fields_name = Array(
                                        'Price Book Name'=>'bookname',
                                        'Active'=>'active'
                                     );
        var $list_link_field= 'bookname';


	var $list_mode;
	var $popup_type;

	var $search_fields = Array(
                                'Price Book Name'=>Array('pricebook'=>'bookname')
                                );
        var $search_fields_name = Array(
                                        'Price Book Name'=>'bookname',
                                     );
/*	
//	var $combofieldNames = Array('manufacturer'=>'manufacturer_dom'
                      ,'productcategory'=>'productcategory_dom');
*/

	function PriceBook() {
		$this->log =LoggerManager::getLogger('pricebook');
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('PriceBook');
	}

  function get_summary_text()
        {
                return $this->name;
        }

	
	function get_pricebook_products($id)
         {                                                                                                                     
         $query = 'select products.productid, products.productname, products.productcode, products.commissionrate, products.qty_per_unit, products.unit_price, crmentity.crmid, crmentity.smownerid,pricebookproductrel.listprice from products inner join pricebookproductrel on products.productid = pricebookproductrel.productid inner join crmentity on crmentity.crmid = products.productid inner join pricebook on pricebook.pricebookid = pricebookproductrel.pricebookid  where pricebook.pricebookid = '.$id.' and crmentity.deleted = 0'; 
	 renderPriceBookRelatedProducts($query,$id);                                                                  
	}

}
?>
