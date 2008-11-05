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

class Products extends CRMEntity {
	var $log;
	var $db;

	 // Josh added for importing and exporting -added in patch2
        var $unit_price;
		var $module_id = "productid";
        var $table_name = "vtiger_products";
        var $object_name = "Product";
        var $entity_table = "vtiger_crmentity";
        var $required_fields = Array(
                'productname'=>1
        );

	var $non_mass_edit_fields = Array('imagename');		

	var $tab_name = Array('vtiger_crmentity','vtiger_products','vtiger_productcf');
	var $tab_name_index = Array('vtiger_crmentity'=>'crmid','vtiger_products'=>'productid','vtiger_productcf'=>'productid','vtiger_seproductsrel'=>'productid','vtiger_producttaxrel'=>'productid');
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
	function Products() {
		$this->log =LoggerManager::getLogger('product');
		$this->log->debug("Entering Products() method ...");
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('Products');
		$this->log->debug("Exiting Product method ...");
	}

	function save_module($module)
	{
		//Inserting into product_taxrel table
		if($_REQUEST['ajxaction'] != 'DETAILVIEW')
		{
			$this->insertTaxInformation('vtiger_producttaxrel', 'Products');
			$this->insertPriceInformation('vtiger_productcurrencyrel', 'Products');
		}
		// Update unit price value in vtiger_productcurrencyrel
		$this->updateUnitPrice();
		//Inserting into attachments
		$this->insertIntoAttachment($this->id,'Products');	
		
	}	

	/**	function to save the product tax information in vtiger_producttaxrel table
	 *	@param string $tablename - vtiger_tablename to save the product tax relationship (producttaxrel)
	 *	@param string $module	 - current module name
	 *	$return void
	*/
	function insertTaxInformation($tablename, $module)
	{
		global $adb, $log;
		$log->debug("Entering into insertTaxInformation($tablename, $module) method ...");
		$tax_details = getAllTaxes();

		$tax_per = '';
		//Save the Product - tax relationship if corresponding tax check box is enabled
		//Delete the existing tax if any
		if($this->mode == 'edit')
		{
			for($i=0;$i<count($tax_details);$i++)
			{
				$taxid = getTaxId($tax_details[$i]['taxname']);
				$sql = "delete from vtiger_producttaxrel where productid=? and taxid=?";
				$adb->pquery($sql, array($this->id,$taxid));
			}
		}
		for($i=0;$i<count($tax_details);$i++)
		{
			$tax_name = $tax_details[$i]['taxname'];
			$tax_checkname = $tax_details[$i]['taxname']."_check";
			if($_REQUEST[$tax_checkname] == 'on' || $_REQUEST[$tax_checkname] == 1)
			{
				$taxid = getTaxId($tax_name);
				$tax_per = $_REQUEST[$tax_name];
				if($tax_per == '')
				{
					$log->debug("Tax selected but value not given so default value will be saved.");
					$tax_per = getTaxPercentage($tax_name);
				}
				
				$log->debug("Going to save the Product - $tax_name tax relationship");

				$query = "insert into vtiger_producttaxrel values(?,?,?)";
				$adb->pquery($query, array($this->id,$taxid,$tax_per));
			}
		}

		$log->debug("Exiting from insertTaxInformation($tablename, $module) method ...");
	}
	
	/**	function to save the product price information in vtiger_productcurrencyrel table
	 *	@param string $tablename - vtiger_tablename to save the product currency relationship (productcurrencyrel)
	 *	@param string $module	 - current module name
	 *	$return void
	*/
	function insertPriceInformation($tablename, $module)
	{
		global $adb, $log, $current_user;
		$log->debug("Entering into insertPriceInformation($tablename, $module) method ...");
		// Update the currency_id based on the logged in user's preference
		$currencyid=fetchCurrency($current_user->id);
		$adb->pquery("update vtiger_products set currency_id=? where productid=?", array($currencyid, $this->id));
		
		$currency_details = getAllCurrencies('all');
		
		//Delete the existing currency relationship if any
		if($this->mode == 'edit')
		{
			for($i=0;$i<count($currency_details);$i++)
			{
				$curid = $currency_details[$i]['curid'];
				$sql = "delete from vtiger_productcurrencyrel where productid=? and currencyid=?";
				$adb->pquery($sql, array($this->id,$curid));
			}
		}
		
		$product_base_conv_rate = getBaseConversionRateForProduct($this->id, $this->mode);
		
		//Save the Product - Currency relationship if corresponding currency check box is enabled
		for($i=0;$i<count($currency_details);$i++)
		{
			$curid = $currency_details[$i]['curid'];
			$curname = $currency_details[$i]['currencylabel'];
			$cur_checkname = 'cur_' . $curid . '_check';
			$cur_valuename = 'curname' . $curid;
			$base_currency_check = 'base_currency' . $curid;
			if($_REQUEST[$cur_checkname] == 'on' || $_REQUEST[$cur_checkname] == 1)
			{
				$conversion_rate = $currency_details[$i]['conversionrate'];
				$actual_conversion_rate = $product_base_conv_rate * $conversion_rate;
				$converted_price = $actual_conversion_rate * $_REQUEST['unit_price'];
				$actual_price = $_REQUEST[$cur_valuename];
				
				$log->debug("Going to save the Product - $curname currency relationship");

				$query = "insert into vtiger_productcurrencyrel values(?,?,?,?)";
				$adb->pquery($query, array($this->id,$curid,$converted_price,$actual_price));
				
				// Update the Product information with Base Currency choosen by the User.
				if ($_REQUEST['base_currency'] == $cur_valuename) {
					$adb->pquery("update vtiger_products set currency_id=?, unit_price=? where productid=?", array($curid, $actual_price, $this->id)); 
				}
			}
		}

		$log->debug("Exiting from insertPriceInformation($tablename, $module) method ...");
	}
	
	function updateUnitPrice() {
		$prod_res = $this->db->pquery("select unit_price, currency_id from vtiger_products where productid=?", array($this->id));
		$prod_unit_price = $this->db->query_result($prod_res, 0, 'unit_price');
		$prod_base_currency = $this->db->query_result($prod_res, 0, 'currency_id');	
		
		$query = "update vtiger_productcurrencyrel set actual_price=? where productid=? and currencyid=?";
		$params = array($prod_unit_price, $this->id, $prod_base_currency);	
		$this->db->pquery($query, $params);
	}
	
	function insertIntoAttachment($id,$module)
	{
		global $log, $adb;
		$log->debug("Entering into insertIntoAttachment($id,$module) method.");
		
		$file_saved = false;

		foreach($_FILES as $fileindex => $files)
		{
			if($files['name'] != '' && $files['size'] > 0)
			{       
			      if($_REQUEST[$fileindex.'_hidden'] != '')	
				      $files['original_name'] = $_REQUEST[$fileindex.'_hidden'];
			      else
				      $files['original_name'] = stripslashes($files['name']);
			      $files['original_name'] = str_replace('"','',$files['original_name']);
				$file_saved = $this->uploadAndSaveFile($id,$module,$files);
			}
		}

		//Remove the deleted vtiger_attachments from db - Products
		if($module == 'Products' && $_REQUEST['del_file_list'] != '')
		{
			$del_file_list = explode("###",trim($_REQUEST['del_file_list'],"###"));
			foreach($del_file_list as $del_file_name)
			{
				$attach_res = $adb->pquery("select vtiger_attachments.attachmentsid from vtiger_attachments inner join vtiger_seattachmentsrel on vtiger_attachments.attachmentsid=vtiger_seattachmentsrel.attachmentsid where crmid=? and name=?", array($id,$del_file_name));
				$attachments_id = $adb->query_result($attach_res,0,'attachmentsid');
				
				$del_res1 = $adb->pquery("delete from vtiger_attachments where attachmentsid=?", array($attachments_id));
				$del_res2 = $adb->pquery("delete from vtiger_seattachmentsrel where attachmentsid=?", array($attachments_id));
			}
		}

		$log->debug("Exiting from insertIntoAttachment($id,$module) method.");
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
		global $log,$current_user;
		$tab_id=getTabid('Documents');
		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3)
        {
			$sec_parameter=getListViewSecurityParameter('Documents');
        }
		$log->debug("Entering get_attachments(".$id.") method ...");

		$query = "SELECT vtiger_notes.title, 'Documents      ' AS ActivityType,
			vtiger_notes.filename, vtiger_attachments.type  AS FileType,
				crm2.modifiedtime AS lastmodified,
			vtiger_seattachmentsrel.attachmentsid AS attachmentsid,
			vtiger_notes.notesid AS crmid, 
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
			LEFT JOIN vtiger_notegrouprelation
				ON vtiger_notegrouprelation.notesid = vtiger_notes.notesid
			LEFT JOIN vtiger_groups
				ON vtiger_groups.groupname = vtiger_notegrouprelation.groupname				
			LEFT JOIN vtiger_seattachmentsrel
				ON vtiger_seattachmentsrel.crmid = vtiger_notes.notesid
			LEFT JOIN vtiger_attachments
				ON vtiger_seattachmentsrel.attachmentsid = vtiger_attachments.attachmentsid
			INNER JOIN vtiger_users
				ON vtiger_crmentity.smownerid = vtiger_users.id
			WHERE vtiger_crmentity.crmid = ".$id." ".$sec_parameter."
		UNION ALL
			SELECT vtiger_attachments.subject AS title,
				'Attachments' AS ActivityType,
			vtiger_attachments.name AS filename,
			vtiger_attachments.type AS FileType,
				crm2.modifiedtime AS lastmodified,
			vtiger_attachments.attachmentsid AS attachmentsid,
			vtiger_seattachmentsrel.attachmentsid AS crmid,
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

	/**	function used to get the list of leads which are related to the product
	 *	@param int $id - product id 
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_leads($id)
	{
		global $log, $singlepane_view, $mod_strings;
		$log->debug("Entering get_leads(".$id.") method ...");

		require_once('modules/Leads/Leads.php');
		$focus = new Leads();

		$button = '';

		if($singlepane_view == 'true')
			$returnset = '&return_module=Products&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Products&return_action=CallRelatedList&return_id='.$id;

		$query = "SELECT vtiger_leaddetails.leadid, vtiger_crmentity.crmid, vtiger_leaddetails.firstname, vtiger_leaddetails.lastname, vtiger_leaddetails.company, vtiger_leadaddress.phone, vtiger_leadsubdetails.website, vtiger_leaddetails.email, case when (vtiger_users.user_name not like \"\") then vtiger_users.user_name else vtiger_groups.groupname end as user_name, vtiger_crmentity.smownerid, vtiger_products.productname, vtiger_products.qty_per_unit, vtiger_products.unit_price, vtiger_products.expiry_date
			FROM vtiger_leaddetails
			INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_leaddetails.leadid
			INNER JOIN vtiger_leadaddress ON vtiger_leadaddress.leadaddressid = vtiger_leaddetails.leadid
			INNER JOIN vtiger_leadsubdetails ON vtiger_leadsubdetails.leadsubscriptionid = vtiger_leaddetails.leadid
			INNER JOIN vtiger_seproductsrel ON vtiger_seproductsrel.crmid=vtiger_leaddetails.leadid
			INNER JOIN vtiger_products ON vtiger_seproductsrel.productid = vtiger_products.productid
			LEFT JOIN vtiger_users ON vtiger_users.id = vtiger_crmentity.smownerid
			LEFT JOIN vtiger_leadgrouprelation ON vtiger_leaddetails.leadid = vtiger_leadgrouprelation.leadid
			LEFT JOIN vtiger_groups ON vtiger_groups.groupname = vtiger_leadgrouprelation.groupname
			WHERE vtiger_crmentity.deleted = 0 AND vtiger_products.productid = ".$id;

		$log->debug("Exiting get_leads($id) method ...");
		return GetRelatedList('Products','Leads',$focus,$query,$button,$returnset);
        }

	/**	function used to get the list of accounts which are related to the product
	 *	@param int $id - product id 
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_accounts($id)
	{
		global $log, $singlepane_view, $mod_strings;
		$log->debug("Entering get_accounts(".$id.") method ...");

		require_once('modules/Accounts/Accounts.php');
		$focus = new Accounts();

		$button = '';

		if($singlepane_view == 'true')
			$returnset = '&return_module=Products&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Products&return_action=CallRelatedList&return_id='.$id;

		$query = "SELECT vtiger_account.accountid, vtiger_crmentity.crmid, vtiger_account.accountname, vtiger_accountbillads.bill_city, vtiger_account.website, vtiger_account.phone, case when (vtiger_users.user_name not like \"\") then vtiger_users.user_name else vtiger_groups.groupname end as user_name, vtiger_crmentity.smownerid, vtiger_products.productname, vtiger_products.qty_per_unit, vtiger_products.unit_price, vtiger_products.expiry_date
			FROM vtiger_account
			INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_account.accountid
			INNER JOIN vtiger_accountbillads ON vtiger_accountbillads.accountaddressid = vtiger_account.accountid
			INNER JOIN vtiger_seproductsrel ON vtiger_seproductsrel.crmid=vtiger_account.accountid
			INNER JOIN vtiger_products ON vtiger_seproductsrel.productid = vtiger_products.productid
			LEFT JOIN vtiger_users ON vtiger_users.id = vtiger_crmentity.smownerid
			LEFT JOIN vtiger_accountgrouprelation ON vtiger_account.accountid = vtiger_accountgrouprelation.accountid
			LEFT JOIN vtiger_groups ON vtiger_groups.groupname = vtiger_accountgrouprelation.groupname
			WHERE vtiger_crmentity.deleted = 0 AND vtiger_products.productid = ".$id;

			
		$log->debug("Exiting get_accounts method ...");
		return GetRelatedList('Products','Accounts',$focus,$query,$button,$returnset);
        }

	/**	function used to get the list of contacts which are related to the product
	 *	@param int $id - product id 
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_contacts($id)
	{
		global $log, $singlepane_view, $mod_strings;
		$log->debug("Entering get_contacts(".$id.") method ...");

		require_once('modules/Contacts/Contacts.php');
		$focus = new Contacts();

		$button = '';

		if($singlepane_view == 'true')
			$returnset = '&return_module=Products&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Products&return_action=CallRelatedList&return_id='.$id;

		$query = "SELECT vtiger_contactdetails.firstname, vtiger_contactdetails.lastname, vtiger_contactdetails.title, vtiger_contactdetails.accountid, vtiger_contactdetails.email, vtiger_contactdetails.phone, vtiger_crmentity.crmid, case when (vtiger_users.user_name not like \"\") then vtiger_users.user_name else vtiger_groups.groupname end as user_name, vtiger_crmentity.smownerid, vtiger_products.productname, vtiger_products.qty_per_unit, vtiger_products.unit_price, vtiger_products.expiry_date,vtiger_account.accountname
			FROM vtiger_contactdetails
			INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_contactdetails.contactid
			INNER JOIN vtiger_seproductsrel ON vtiger_seproductsrel.crmid=vtiger_contactdetails.contactid
			INNER JOIN vtiger_products ON vtiger_seproductsrel.productid = vtiger_products.productid
			LEFT JOIN vtiger_users ON vtiger_users.id = vtiger_crmentity.smownerid
			LEFT JOIN vtiger_contactgrouprelation ON vtiger_contactdetails.contactid = vtiger_contactgrouprelation.contactid
			LEFT JOIN vtiger_groups ON vtiger_groups.groupname = vtiger_contactgrouprelation.groupname
			LEFT JOIN vtiger_account ON vtiger_account.accountid = vtiger_contactdetails.accountid
			WHERE vtiger_crmentity.deleted = 0 AND vtiger_products.productid = ".$id;

		$log->debug("Exiting get_contacts method ...");
		return GetRelatedList('Products','Contacts',$focus,$query,$button,$returnset);
        }


	/**	function used to get the list of potentials which are related to the product
	 *	@param int $id - product id 
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_opportunities($id)
	{
		global $log, $singlepane_view, $mod_strings;
		$log->debug("Entering get_opportunities(".$id.") method ...");

		require_once('modules/Potentials/Potentials.php');
		$focus = new Potentials();

		$button = '';

		if($singlepane_view == 'true')
			$returnset = '&return_module=Products&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Products&return_action=CallRelatedList&return_id='.$id;

		$query = "SELECT vtiger_potential.potentialid, vtiger_crmentity.crmid, vtiger_potential.potentialname, vtiger_account.accountname, vtiger_potential.accountid, vtiger_potential.sales_stage, vtiger_potential.amount, vtiger_potential.closingdate, case when (vtiger_users.user_name not like '') then vtiger_users.user_name else vtiger_groups.groupname end as user_name, vtiger_crmentity.smownerid, vtiger_products.productname, vtiger_products.qty_per_unit, vtiger_products.unit_price, vtiger_products.expiry_date
			FROM vtiger_potential
			INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_potential.potentialid
			INNER JOIN vtiger_account ON vtiger_potential.accountid = vtiger_account.accountid
			INNER JOIN vtiger_seproductsrel ON vtiger_seproductsrel.crmid = vtiger_potential.potentialid
			INNER JOIN vtiger_products ON vtiger_seproductsrel.productid = vtiger_products.productid
			LEFT JOIN vtiger_users ON vtiger_users.id = vtiger_crmentity.smownerid
			LEFT JOIN vtiger_potentialgrouprelation ON vtiger_potential.potentialid = vtiger_potentialgrouprelation.potentialid
			LEFT JOIN vtiger_groups ON vtiger_groups.groupname = vtiger_potentialgrouprelation.groupname
			WHERE vtiger_crmentity.deleted = 0 AND vtiger_products.productid = ".$id;

		$log->debug("Exiting get_opportunities($id) method ...");
		return GetRelatedList('Products','Potentials',$focus,$query,$button,$returnset);
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

		$query = "SELECT  case when (vtiger_users.user_name not like \"\") then vtiger_users.user_name else vtiger_groups.groupname end as user_name, vtiger_users.id,
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
			AND (activitytype != 'Emails')";
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
		require_once('modules/Quotes/Quotes.php');	
		$focus = new Quotes();
	
		$button = '';
		if($singlepane_view == 'true')
			$returnset = '&return_module=Products&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Products&return_action=CallRelatedList&return_id='.$id;


		$query = "SELECT vtiger_crmentity.*,
			vtiger_quotes.*,
			vtiger_potential.potentialname,
			vtiger_account.accountname,
			vtiger_inventoryproductrel.productid,

			case 
				when (vtiger_users.user_name not like '') then vtiger_users.user_name 
				else vtiger_groups.groupname 
			end 
			as user_name

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
			LEFT JOIN vtiger_users 
				ON vtiger_users.id=vtiger_crmentity.smownerid
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
		$focus = new PurchaseOrder();

		$button = '';

		if($singlepane_view == 'true')
			$returnset = '&return_module=Products&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Products&return_action=CallRelatedList&return_id='.$id;

		$query = "SELECT vtiger_crmentity.*,
			vtiger_purchaseorder.*,
			vtiger_products.productname,
			vtiger_inventoryproductrel.productid,

			case 
				when (vtiger_users.user_name not like '') then vtiger_users.user_name 
				else vtiger_groups.groupname 
			end 
			as user_name

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
			LEFT JOIN vtiger_users
				ON vtiger_users.id=vtiger_crmentity.smownerid
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
			vtiger_account.accountname,

			case 
				when (vtiger_users.user_name not like '') then vtiger_users.user_name 
				else vtiger_groups.groupname 
			end 
			as user_name

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
			LEFT JOIN vtiger_users 
				ON vtiger_users.id=vtiger_crmentity.smownerid
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
			vtiger_account.accountname,

			case 
				when (vtiger_users.user_name not like '') then vtiger_users.user_name 
				else vtiger_groups.groupname 
			end 
			as user_name

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
			LEFT JOIN vtiger_users
				ON  vtiger_users.id=vtiger_crmentity.smownerid
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
		require_once('modules/PriceBooks/PriceBooks.php');
		$focus = new PriceBooks();
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
		$result=$this->db->pquery($query, array());
		$log->debug("Exiting product_novendor method ...");
		return $this->db->num_rows($result);
	}
	
	/**	function used to get the export query for product
	 *	@param reference $where - reference of the where variable which will be added with the query
	 *	@return string $query - return the query which will give the list of products to export
	 */	
	function create_export_query($where)
	{
		global $log;
		$log->debug("Entering create_export_query(".$where.") method ...");

		include("include/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("Products", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);

		$query = "SELECT $fields_list FROM ".$this->table_name ."
			INNER JOIN vtiger_crmentity
				ON vtiger_crmentity.crmid = vtiger_products.productid 
			LEFT JOIN vtiger_productcf
				ON vtiger_products.productid = vtiger_productcf.productid
			INNER JOIN vtiger_users
				ON vtiger_users.id=vtiger_products.handler 

			LEFT JOIN vtiger_vendor
				ON vtiger_vendor.vendorid = vtiger_products.vendor_id
			WHERE vtiger_crmentity.deleted = 0 and vtiger_users.status = 'Active'";
	

		if($where != "")
                        $query .= " AND ($where) ";

		$log->debug("Exiting create_export_query method ...");
                return $query;

	}

}
?>
