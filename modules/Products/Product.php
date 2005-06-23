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


class Product extends CRMEntity {
	var $log;
	var $db;


	// Stored fields
	var $id;
	var $mode;

	// These are related
	var $name;
	var $vendorid;
	var $contactname;
	var $contactid;

	var $tab_name = Array('crmentity','products','productcf','seproductsrel');
	var $tab_name_index = Array('crmentity'=>'crmid','products'=>'productid','productcf'=>'productid','seproductsrel'=>'productid');
	var $column_fields = Array();

	var $sortby_fields = Array('productname','productcode','commissionrate');		  

        // This is the list of fields that are in the lists.
        var $list_fields = Array(
                                'Product Name'=>Array('products'=>'productname'),
                                'Product Code'=>Array('products'=>'productcode'),
                                'Commission Rate'=>Array('products'=>'commissionrate'),
                                'Qty/Unit'=>Array('products'=>'qty_per_unit'),
                                'Unit Price'=>Array('products'=>'unit_price')
                                );
        var $list_fields_name = Array(
                                        'Product Name'=>'productname',
                                        'Product Code'=>'productcode',
                                        'Commission Rate'=>'commissionrate',
                                        'Qty/Unit'=>'qty_per_unit',
                                        'Unit Price'=>'unit_price'
                                     );
        var $list_link_field= 'productname';


	var $list_mode;
	var $popup_type;

	var $search_fields = Array(
                                'Product Name'=>Array('products'=>'productname'),
                                'Product Code'=>Array('products'=>'productcode'),
                                'Unit Price'=>Array('products'=>'unit_price')
                                );
        var $search_fields_name = Array(
                                        'Product Name'=>'productname',
                                        'Product Code'=>'productcode',
                                        'Unit Price'=>'unit_price'
                                     );
	
	var $combofieldNames = Array('manufacturer'=>'manufacturer_dom'
                      ,'productcategory'=>'productcategory_dom');


	function Product() {
		$this->log =LoggerManager::getLogger('product');
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('Products');
	}

  function get_summary_text()
        {
                return $this->name;
        }

  		
	function get_attachments($id)
        {
	
		$query = "select notes.title,'Notes      ' ActivityType, notes.filename, attachments.type  FileType,crm2.modifiedtime  lastmodified, seattachmentsrel.attachmentsid attachmentsid, notes.notesid crmid from notes inner join senotesrel on senotesrel.notesid= notes.notesid inner join crmentity on crmentity.crmid= senotesrel.crmid inner join crmentity crm2 on crm2.crmid=notes.notesid left join seattachmentsrel  on seattachmentsrel.crmid =notes.notesid left join attachments on seattachmentsrel.attachmentsid = attachments.attachmentsid where crmentity.crmid=".$id;
                $query .= ' union all ';
                $query .= "select attachments.description title ,'Attachments'  ActivityType, attachments.name  filename, attachments.type  FileType,crm2.modifiedtime  lastmodified, attachments.attachmentsid attachmentsid, seattachmentsrel.attachmentsid crmid from attachments inner join seattachmentsrel on seattachmentsrel.attachmentsid= attachments.attachmentsid inner join crmentity on crmentity.crmid= seattachmentsrel.crmid inner join crmentity crm2 on crm2.crmid=attachments.attachmentsid where crmentity.crmid=".$id;	
		renderRelatedAttachments($query,$id);
        }

	function get_opportunities($id)
        {
		$query = 'select potential.potentialid, potential.potentialname, potential.potentialtype,  products.productid, products.productname, products.qty_per_unit, products.unit_price, products.expiry_date from potential inner join products on potential.productid = products.productid where products.productid='.$id;
          renderRelatedPotentials($query);
        }

	function get_tickets($id)
        {
		//$query = 'select users.user_name, users.id, products.productid,products.productname, troubletickets.ticketid, troubletickets.parent_id, troubletickets.title, troubletickets.status, troubletickets.priority, crmentity.crmid, crmentity.smownerid, crmentity.modifiedtime from products  inner join seticketsrel on seticketsrel.crmid = products.productid inner join troubletickets on troubletickets.ticketid = seticketsrel.ticketid inner join crmentity on crmentity.crmid = troubletickets.ticketid left join users on users.id=crmentity.smownerid where products.productid= '.$id.' and crmentity.deleted=0';
		$query = "select users.user_name, users.id, products.productid,products.productname, troubletickets.ticketid, troubletickets.parent_id, troubletickets.title, troubletickets.status, troubletickets.priority, crmentity.crmid, crmentity.smownerid, crmentity.modifiedtime from troubletickets inner join crmentity on crmentity.crmid = troubletickets.ticketid left join products on products.productid=troubletickets.product_id left join users on users.id=crmentity.smownerid where crmentity.deleted=0 and products.productid=".$id;
          renderRelatedTickets($query,$id);
        }

	function get_meetings($id)
	{
		$query = "SELECT meetings.name,meetings.location,meetings.date_start from meetings inner join seactivityrel on seactivityrel.activityid=meetings.meetingid and seactivityrel.crmid=".$id."";
		renderRelatedMeetings($query);
	}

	function get_activities($id)
	{
		//$query = "SELECT activity.*,seactivityrel.*,crmentity.crmid, crmentity.smownerid, crmentity.modifiedtime, users.user_name from activity inner join seactivityrel on seactivityrel.activityid=activity.activityid inner join crmentity on crmentity.crmid=activity.activityid left join users on users.id=crmentity.smownerid where seactivityrel.crmid=".$id." and (activitytype='Task' or activitytype='Call' or activitytype='Meeting')";
		$query = "SELECT contactdetails.lastname, contactdetails.firstname, activity.*,seactivityrel.*,crmentity.crmid, crmentity.smownerid, crmentity.modifiedtime, users.user_name from activity inner join seactivityrel on seactivityrel.activityid=activity.activityid inner join crmentity on crmentity.crmid=activity.activityid left join cntactivityrel on cntactivityrel.activityid= activity.activityid left join contactdetails on contactdetails.contactid = cntactivityrel.contactid left join users on users.id=crmentity.smownerid where seactivityrel.crmid=".$id." and (activitytype='Task' or activitytype='Call' or activitytype='Meeting')";
		renderRelatedActivities($query,$id);
	}
	function product_novendor()
	{
		$query = "SELECT products.productname,crmentity.deleted from products inner join crmentity on crmentity.crmid=products.productid where crmentity.deleted=0 and products.vendor_id=''";
		$result=$this->db->query($query);
		return $this->db->num_rows($result);
	}

}
?>
