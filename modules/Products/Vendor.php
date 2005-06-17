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

	var $sortby_fields = Array('name','company_name','category');		  

        // This is the list of fields that are in the lists.
	var $list_fields = Array(
                                'Vendor Name'=>Array('vendor'=>'name'),
                                'Phone'=>Array('vendor'=>'phone'),
                                'Email'=>Array('vendor'=>'email'),
                                'Category'=>Array('vendor'=>'category')
                                );
        var $list_fields_name = Array(
                                        'Vendor Name'=>'name',
                                        'Phone'=>'phone',
                                        'Email'=>'email',
                                        'Category'=>'category'
                                     );
        var $list_link_field= 'name';


	var $list_mode;
	var $popup_type;

	var $search_fields = Array(
                                'Vendor Name'=>Array('vendor'=>'name'),
                                'Phone'=>Array('vendor'=>'phone')
                                );
        var $search_fields_name = Array(
                                        'Vendor Name'=>'name',
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

}
?>
