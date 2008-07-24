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
require_once('user_privileges/default_module_view.php');

class PriceBooks extends CRMEntity {
	var $log;
	var $db;
	var $table_name = "vtiger_pricebook";
	var $tab_name = Array('vtiger_crmentity','vtiger_pricebook','vtiger_pricebookcf');
	var $tab_name_index = Array('vtiger_crmentity'=>'crmid','vtiger_pricebook'=>'pricebookid','vtiger_pricebookcf'=>'pricebookid');
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

	var $search_fields = Array(
                                'Price Book Name'=>Array('pricebook'=>'bookname')
                                );
        var $search_fields_name = Array(
                                        'Price Book Name'=>'bookname',
                                     );

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'bookname';
	var $default_sort_order = 'ASC';

	/**	Constructor which will set the column_fields in this object
	 */
	function PriceBooks() {
		$this->log =LoggerManager::getLogger('pricebook');
		$this->log->debug("Entering PriceBooks() method ...");
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('PriceBooks');
		$this->log->debug("Exiting PriceBook method ...");
	}

	function save_module($module)
	{
	}	

	/**	Function used to get the sort order for PriceBook listview
	 *	@return string	$sorder	- first check the $_REQUEST['sorder'] if request value is empty then check in the $_SESSION['PRICEBOOK_SORT_ORDER'] if this session value is empty then default sort order will be returned. 
	 */
	function getSortOrder()
	{
		global $log;
                $log->debug("Entering getSortOrder() method ...");	
		if(isset($_REQUEST['sorder'])) 
			$sorder = $_REQUEST['sorder'];
		else
			$sorder = (($_SESSION['PRICEBOOK_SORT_ORDER'] != '')?($_SESSION['PRICEBOOK_SORT_ORDER']):($this->default_sort_order));
		$log->debug("Exiting getSortOrder() method ...");
		return $sorder;
	}

	/**	Function used to get the order by value for PriceBook listview
	 *	@return string	$order_by  - first check the $_REQUEST['order_by'] if request value is empty then check in the $_SESSION['PRICEBOOK_ORDER_BY'] if this session value is empty then default order by will be returned. 
	 */
	function getOrderBy()
	{
		global $log;
                $log->debug("Entering getOrderBy() method ...");
		if (isset($_REQUEST['order_by'])) 
			$order_by = $_REQUEST['order_by'];
		else
			$order_by = (($_SESSION['PRICEBOOK_ORDER_BY'] != '')?($_SESSION['PRICEBOOK_ORDER_BY']):($this->default_order_by));
		$log->debug("Exiting getOrderBy method ...");
		return $order_by;
	}	

	/**	function used to get the products which are related to the pricebook
	 *	@param int $id - pricebook id
         *      @return array - return an array which will be returned from the function getPriceBookRelatedProducts
        **/
	function get_pricebook_products($id)
	{
		global $log,$singlepane_view;
		$log->debug("Entering get_pricebook_products(".$id.") method ...");
		global $app_strings;
		require_once('modules/Products/Products.php');	
		$focus = new Products();

		$button = '';

		if($singlepane_view == 'true')
			$returnset = '&return_module=PriceBooks&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=PriceBooks&return_action=CallRelatedList&return_id='.$id;

		$query = 'select vtiger_products.productid, vtiger_products.productname, vtiger_products.productcode, vtiger_products.commissionrate, vtiger_products.qty_per_unit, vtiger_products.unit_price, vtiger_crmentity.crmid, vtiger_crmentity.smownerid,vtiger_pricebookproductrel.listprice from vtiger_products inner join vtiger_pricebookproductrel on vtiger_products.productid = vtiger_pricebookproductrel.productid inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_products.productid inner join vtiger_pricebook on vtiger_pricebook.pricebookid = vtiger_pricebookproductrel.pricebookid  where vtiger_pricebook.pricebookid = '.$id.' and vtiger_crmentity.deleted = 0'; 
		$log->debug("Exiting get_pricebook_products method ...");
		return getPriceBookRelatedProducts($query,$focus,$returnset);
	}

	/**	function used to get whether the pricebook has related with a product or not
	 *	@param int $id - product id
	 *	@return true or false - if there are no pricebooks available or associated pricebooks for the product is equal to total number of pricebooks then return false, else return true
	 */
	function get_pricebook_noproduct($id)
	{
		global $log;
		$log->debug("Entering get_pricebook_noproduct(".$id.") method ...");
		 
		$query = "select vtiger_crmentity.crmid, vtiger_pricebook.* from vtiger_pricebook inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_pricebook.pricebookid where vtiger_crmentity.deleted=0";
		$result = $this->db->pquery($query, array());
		$no_count = $this->db->num_rows($result);
		if($no_count !=0)
		{
       	 	$pb_query = 'select vtiger_crmentity.crmid, vtiger_pricebook.pricebookid,vtiger_pricebookproductrel.productid from vtiger_pricebook inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_pricebook.pricebookid inner join vtiger_pricebookproductrel on vtiger_pricebookproductrel.pricebookid=vtiger_pricebook.pricebookid where vtiger_crmentity.deleted=0 and vtiger_pricebookproductrel.productid=?';
			$result_pb = $this->db->pquery($pb_query, array($id));
			if($no_count == $this->db->num_rows($result_pb))
			{
				$log->debug("Exiting get_pricebook_noproduct method ...");
				return false;
			}
			elseif($this->db->num_rows($result_pb) == 0)
			{
				$log->debug("Exiting get_pricebook_noproduct method ...");
				return true;
			}
			elseif($this->db->num_rows($result_pb) < $no_count)
			{
				$log->debug("Exiting get_pricebook_noproduct method ...");
				return true;
			}
		}
		else
		{
			$log->debug("Exiting get_pricebook_noproduct method ...");
			return false;
		}
	}

	/** Function to handle module specific operations when restoring an entity 
	*/
	function restore_module($crmid) {
		
	}

}
?>
