<?php
include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('include/utils.php');


class Product extends SugarBean {
	var $log;
	var $db;


	// Stored fields
	var $id;
	var $mode;

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
                                'Product Code'=>Array('products'=>'category')
                                );
        var $search_fields_name = Array(
                                        'Product Name'=>'productname',
                                        'Product Code'=>'category'
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
		$query = 'select notes.title,"Notes      " as ActivityType, notes.filename, attachments.type as "FileType",crm2.modifiedtime as "lastmodified", notes.notesid as noteattachmentid from notes inner join senotesrel on senotesrel.notesid= notes.notesid inner join crmentity on crmentity.crmid= senotesrel.crmid inner join crmentity crm2 on crm2.crmid=notes.notesid left join seattachmentsrel  on seattachmentsrel.crmid =notes.notesid left join attachments on seattachmentsrel.attachmentsid = attachments.attachmentsid where crmentity.crmid='.$id;
                $query .= ' union all ';
                $query .= 'select "          " as Title ,"Attachments" as ActivityType, attachments.name as "filename", attachments.type as "FileType",crm2.modifiedtime as "lastmodified", attachments.attachmentsid as noteattachmentid from attachments inner join seattachmentsrel on seattachmentsrel.attachmentsid= attachments.attachmentsid inner join crmentity on crmentity.crmid= seattachmentsrel.crmid inner join crmentity crm2 on crm2.crmid=attachments.attachmentsid where crmentity.crmid='.$id;
		renderRelatedAttachments($query);
        }


  function get_opportunities($id)
        {
          //include('modules/Products/RenderRelatedListUI.php');
           //$query = "SELECT potentialname,account.accountname,closingdate from potential inner join account on account.accountid=potential.accountid inner join seproductsrel on seproductsrel.crmid=potential.potentialid and seproductsrel.productid=".$id." inner join crmentity on crmentity.crmid=potential.potentialid and crmentity.deleted=0";
		$query = 'select potential.potentialid, potential.potentialname, potential.potentialtype,  products.productid, products.productname, products.qty_per_unit, products.unit_price, products.expiry_date from potential inner join products on potential.productid = products.productid where products.productid='.$id;
          renderRelatedPotentials($query);
        }


  function get_tickets($id)
        {
          //$query = "SELECT troubletickets.priority,troubletickets.ticketid,troubletickets.status,troubletickets.category from troubletickets inner join seticketsrel on seticketsrel.ticketid=troubletickets.ticketid and seticketsrel.crmid=".$id."";
		$query = 'select users.user_name, users.id, products.productid,products.productname, troubletickets.ticketid,troubletickets.title, troubletickets.status, crmentity.crmid, crmentity.smownerid, crmentity.modifiedtime from troubletickets inner join seticketsrel on seticketsrel.ticketid = troubletickets.ticketid inner join products on products.productid=seticketsrel.crmid inner join crmentity on crmentity.crmid = products.productid inner join users on users.id=crmentity.smownerid where products.productid='.$id;
          renderRelatedTickets($query);
        }
  function get_meetings($id)
  {
     $query = "SELECT meetings.name,meetings.location,meetings.date_start from meetings inner join seactivityrel on seactivityrel.activityid=meetings.meetingid and seactivityrel.crmid=".$id."";
    renderRelatedMeetings($query);
  }
  function get_activities($id)
  {
    //$query = "SELECT activity.subject,semodule,activitytype,date_start,status,priority from activity inner join seactivityrel on seactivityrel.activityid=activity.activityid where seactivityrel.crmid=".$id;
		$query = "SELECT activity.*,seactivityrel.*,crmentity.crmid, crmentity.smownerid, crmentity.modifiedtime, users.user_name from activity inner join seactivityrel on seactivityrel.activityid=activity.activityid inner join crmentity on crmentity.crmid=activity.activityid inner join users on users.id=crmentity.smownerid where seactivityrel.crmid=".$id." and (activitytype='Task' or activitytype='Call' or activitytype='Meeting')";
		renderRelatedActivities($query);
  }

  

}
?>
