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

	var $tab_name = Array('crmentity','pricebook','pricebookproductrel');
	var $tab_name_index = Array('crmentity'=>'crmid','pricebook'=>'pricebookid','pricebookproductrel'=>'pricebookid');
	var $column_fields = Array();

//	var $sortby_fields = Array('productname','productcode','commissionrate');		  

        // This is the list of fields that are in the lists.
	var $list_fields = Array(
                                'Price Book Name'=>Array('pricebook'=>'bookname'),
                                'Product Name'=>Array('pricebook'=>'productid')
                                );
        var $list_fields_name = Array(
                                        'Price Book Name'=>'bookname',
                                        'Product Name'=>'product_id'
                                     );
        var $list_link_field= 'bookname';


	var $list_mode;
	var $popup_type;

	var $search_fields = Array(
                                'Price Book Name'=>Array('pricebook'=>'bookname'),
                                'Product Name'=>Array('pricebook'=>'productid')
                                );
        var $search_fields_name = Array(
                                        'Price Book Name'=>'bookname',
                                        'Product Name'=>'product_id'
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

}
?>
