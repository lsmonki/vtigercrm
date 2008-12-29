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

require_once("config.php");
require_once('PortalConfig.php');
require_once('include/logging.php');
require_once('include/nusoap/nusoap.php');
require_once('modules/HelpDesk/HelpDesk.php');
require_once('modules/Emails/mail.php');
require_once('modules/HelpDesk/language/en_us.lang.php');
require_once('include/utils/CommonUtils.php');


$log = &LoggerManager::getLogger('customerportal');

error_reporting(0);

$NAMESPACE = 'http://www.vtiger.com/products/crm';
$server = new soap_server;

$server->configureWSDL('customerportal');

$server->wsdl->addComplexType(
	'common_array',
	'complexType',
	'array',
	'',
	array(
		'fieldname' => array('name'=>'fieldname','type'=>'xsd:string'),
	)
);

$server->wsdl->addComplexType(
	'common_array1',
	'complexType',
	'array',
	'',
	'SOAP-ENC:Array',
	array(),
	array(
		array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:common_array[]')
	),
	'tns:common_array'
);

// Added for enhancement from Rosa Weber

$server->wsdl->addComplexType(
        'add_contact_detail_array',
        'complexType',
        'array',
        '',
        array(
                'salutation' => array('name'=>'salutation','type'=>'xsd:string'),
                'firstname' => array('name'=>'firstname','type'=>'xsd:string'),
                'phone' => array('name'=>'phone','type'=>'xsd:string'),
                'lastname' => array('name'=>'lastname','type'=>'xsd:string'),
                'mobile' => array('name'=>'mobile','type'=>'xsd:string'),
                'accountid' => array('name'=>'accountid','type'=>'xsd:string'),
                'leadsource' => array('name'=>'leadsource','type'=>'xsd:string'),
             )
);

$server->wsdl->addComplexType(
        'field_details_array',
        'complexType',
        'array',
        '',
        array(
                'fieldlabel' => array('name'=>'fieldlabel','type'=>'xsd:string'),
                'fieldvalue' => array('name'=>'fieldvalue','type'=>'xsd:string'),
             )
);
$server->wsdl->addComplexType(
        'field_datalist_array',
        'complexType',
        'array',
        '',
        array(
                'fielddata' => array('name'=>'fielddata','type'=>'xsd:string'),
             )
);


$server->wsdl->addComplexType(
        'product_list_array',
        'complexType',
        'array',
        '',
        array(
                'productid' => array('name'=>'productid','type'=>'xsd:string'),
                'productname' => array('name'=>'productname','type'=>'xsd:string'),
                'productcode' => array('name'=>'productcode','type'=>'xsd:string'),
                'commissionrate' => array('name'=>'commissionrate','type'=>'xsd:string'),
                'qtyinstock' => array('name'=>'qtyinstock','type'=>'xsd:string'),
                'qty_per_unit' => array('name'=>'qty_per_unit','type'=>'xsd:string'),
                'unit_price' => array('name'=>'unit_price','type'=>'xsd:string'),
             )
     );

$server->wsdl->addComplexType(
        'get_ticket_attachments_array',
        'complexType',
        'array',
        '',
        array(
                'files' => array(
		'fileid'=>'xsd:string','type'=>'tns:xsd:string',
		'filename'=>'xsd:string','type'=>'tns:xsd:string',
		'filesize'=>'xsd:string','type'=>'tns:xsd:string',
		'filetype'=>'xsd:string','type'=>'tns:xsd:string',
		'filecontents'=>'xsd:string','type'=>'tns:xsd:string'
		),
       )
);


$server->register(
	'authenticate_user',
	array('fieldname'=>'tns:common_array'),
	array('return'=>'tns:common_array'),
	$NAMESPACE);

$server->register(
	'change_password',
	array('fieldname'=>'tns:common_array'),
	array('return'=>'tns:common_array'),
	$NAMESPACE);
  
$server->register(
	'create_ticket',
	array('fieldname'=>'tns:common_array'),
	array('return'=>'tns:common_array'),
	$NAMESPACE);

//for a particular contact ticket list
$server->register(
	'get_tickets_list',
	array('fieldname'=>'tns:common_array'),
	array('return'=>'tns:common_array'),
	$NAMESPACE);

$server->register(
	'get_ticket_comments',
	array('fieldname'=>'tns:common_array'),
	array('return'=>'tns:common_array'),
	$NAMESPACE);

$server->register(
	'get_combo_values',
	array('fieldname'=>'tns:common_array'),
	array('return'=>'tns:common_array'),
	$NAMESPACE);

$server->register(
	'get_KBase_details',
	array('fieldname'=>'tns:common_array'),
	array('return'=>'tns:common_array1'),
	$NAMESPACE);

$server->register(
	'save_faq_comment',
	array('fieldname'=>'tns:common_array'),
	array('return'=>'tns:common_array'),
	$NAMESPACE);

$server->register(
	'update_ticket_comment',
	array('fieldname'=>'tns:common_array'),
	array('return'=>'tns:common_array'),
	$NAMESPACE);

$server->register(
        'close_current_ticket',
         array('fieldname'=>'tns:common_array'),
	array('return'=>'xsd:string'),
        $NAMESPACE);

$server->register(
	'update_login_details',
	array('fieldname'=>'tns:common_array'),
	array('return'=>'xsd:string'),
	$NAMESPACE);

$server->register(
	'send_mail_for_password',
	array('email'=>'xsd:string'),
	array('return'=>'xsd:string'),
	$NAMESPACE);

$server->register(
        'get_ticket_creator',
        array('fieldname'=>'tns:common_array'),
        array('return'=>'xsd:string'),
        $NAMESPACE);

$server->register(
	'get_picklists',
	array('fieldname'=>'tns:common_array'),
	array('return'=>'tns:common_array'),
	$NAMESPACE);

$server->register(
	'get_ticket_attachments',
	array('fieldname'=>'tns:common_array'),
	array('return'=>'tns:common_array'),
	$NAMESPACE);

$server->register(
	'get_filecontent',
	array('fieldname'=>'tns:common_array'),
	array('return'=>'tns:common_array'),
	$NAMESPACE);

$server->register(
	'add_ticket_attachment',
	array('fieldname'=>'tns:common_array'),
	array('return'=>'tns:common_array'),
	$NAMESPACE);

$server->register(
	'get_contact_detail',
	array('id'=>'xsd:string','block'=>'xsd:string','contactid'=>'xsd:string','sessionid'=>'xsd:string'),
	array('return'=>'tns:field_details_array'),
	$NAMESPACE);

$server->register(
	'get_cf_field_details',
	array('id'=>'xsd:string','contactid'=>'xsd:string','sessionid'=>'xsd:string'),
	array('return'=>'tns:field_details_array'),
	$NAMESPACE);

$server->register(
	'get_account_detail',
	array('id'=>'xsd:string','block'=>'xsd:string','contactid'=>'xsd:string','sessionid'=>'xsd:string'),
	array('return'=>'tns:field_details_array'),
	$NAMESPACE);

$server->register(
        'get_check_account_id',
        array('id'=>'xsd:string'),
        array('return'=>'xsd:string'),
        $NAMESPACE);

$server->register(
	'get_product_detail',
	array('id'=>'xsd:string','block'=>'xsd:string','contactid'=>'xsd:string','sessionid'=>'xsd:string'),
	array('return'=>'tns:field_details_array'),
	$NAMESPACE);

//to get details of quotes,invoices and documents
$server->register(
	'get_details',
	array('id'=>'xsd:string','block'=>'xsd:string','contactid'=>'xsd:string','sessionid'=>'xsd:string'),
	array('return'=>'tns:field_details_array'),
	$NAMESPACE);

 //to get the products list for the entire account of a contact
$server->register(
	'get_product_list_values',
	array('id'=>'xsd:string','block'=>'xsd:string','sessionid'=>'xsd:string','only_mine'=>'xsd:string'),
	array('return'=>'tns:field_details_array'),
	$NAMESPACE);
	
$server->register(
	'get_image_url',
	array('id'=>'xsd:string','module'=>'xsd:string','contactid'=>'xsd:string'),
	array('return'=>'tns:field_details_array'),
	$NAMESPACE);

$server->register(
	'get_list_values',
	array('id'=>'xsd:string','block'=>'xsd:string','sessionid'=>'xsd:string','only_mine'=>'xsd:string'),
	array('return'=>'tns:field_datalist_array'),
	$NAMESPACE);

$server->register(
	'get_product_urllist',
	array('customerid'=>'xsd:string','productid'=>'xsd:string','block'=>'xsd:string'),
	array('return'=>'tns:field_datalist_array'),
	$NAMESPACE);

$server->register(
	'get_pdf',
	array('id'=>'xsd:string','block'=>'xsd:string','contactid'=>'xsd:string','sessionid'=>'xsd:string'),
	array('return'=>'tns:field_datalist_array'),
	$NAMESPACE);

$server->register(
	'get_filecontent_detail',
	array('id'=>'xsd:string','folderid'=>'xsd:string','block'=>'xsd:string','contactid'=>'xsd:string','sessionid'=>'xsd:string'),
	array('return'=>'tns:get_ticket_attachments_array'),
	$NAMESPACE);

$server->register(
	'get_invoice_detail',
	array('id'=>'xsd:string','block'=>'xsd:string','contactid'=>'xsd:string','sessionid'=>'xsd:string'),
	array('return'=>'tns:field_details_array'),
	$NAMESPACE);

$server->register(
	'get_modules',
	array(),
	array('return'=>'tns:field_details_array'),
	$NAMESPACE);

$server->register(
	'show_all',
	array('module'=>'xsd:string'),
	array('return'=>'xsd:string'),
	$NAMESPACE);

$server->register(
	'get_faq_documents',
	array('id'=>'xsd:string','module'=>'xsd:string','customerid'=>'xsd:string','sessionid'=> 'xsd:string'),
	array('return'=>'tns:field_details_array'),
	$NAMESPACE);
	
/**	function used to get the list of ticket comments
	@param array $input_array - array which contains the following parameters
 	=>	int $id - customer id
		string $sessionid - session id
		int $ticketid - ticket id
 *	return array $response - ticket comments and details as a array with elements comments, owner and createdtime which will be returned from the function get_ticket_comments_list
 */
function get_ticket_comments($input_array)
{
	global $adb;
	$adb->println("INPUT ARRAY for the function get_ticket_comments");
	$adb->println($input_array);

	//foreach($input_array as $fieldname => $fieldvalue)$input_array[$fieldname] = mysql_real_escape_string($fieldvalue);

	$id = $input_array['id'];
	$sessionid = $input_array['sessionid'];
	$ticketid = (int) $input_array['ticketid'];

	if(!validateSession($id,$sessionid))
		return null;

	$seed_ticket = new HelpDesk();
	$output_list = Array();

	$response = $seed_ticket->get_ticket_comments_list($ticketid);

	return $response;
}

/**	function used to get the combo values ie., picklist values of the HelpDesk module and also the list of products
 *	@param array $input_array - array which contains the following parameters
		=>	int $id - customer id
			string $sessionid - session id
 *	return array $output - array which contains the product id, product name, ticketpriorities, ticketseverities, ticketcategories and module owners list
 */
function get_combo_values($input_array)
{
	global $adb;
	$adb->println("INPUT ARRAY for the function get_combo_values");
	$adb->println($input_array);

	$id = $input_array['id'];
	$sessionid = $input_array['sessionid'];

	if(!validateSession($id,$sessionid))
		return null;

	$output = Array();
	$sql = "select  productid, productname from vtiger_products inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_products.productid where vtiger_crmentity.deleted=0";
	$result = $adb->pquery($sql, array());
	$noofrows = $adb->num_rows($result);
	for($i=0;$i<$noofrows;$i++)
	{
		$output['productid']['productid'][$i] = $adb->query_result($result,$i,"productid");
		$output['productname']['productname'][$i] = decode_html($adb->query_result($result,$i,"productname"));
	}

        //We are going to display the picklist entries associated with admin user (role is H2)
	$admin_role = 'H2';
	$result1 = $adb->pquery("select vtiger_ticketpriorities.* from vtiger_ticketpriorities inner join vtiger_role2picklist on vtiger_role2picklist.picklistvalueid = vtiger_ticketpriorities.picklist_valueid and vtiger_role2picklist.roleid='$admin_role' ORDER BY vtiger_ticketpriorities.ticketpriorities_id ASC", array());
	//$result1 = $adb->pquery("select ticketpriorities from vtiger_ticketpriorities ", array());
	for($i=0;$i<$adb->num_rows($result1);$i++)
	{
		$output['ticketpriorities']['ticketpriorities'][$i] = $adb->query_result($result1,$i,"ticketpriorities");
	}

	$result2 = $adb->pquery("select vtiger_ticketseverities.* from vtiger_ticketseverities inner join vtiger_role2picklist on vtiger_role2picklist.picklistvalueid = vtiger_ticketseverities.picklist_valueid and vtiger_role2picklist.roleid='$admin_role' ORDER BY vtiger_ticketseverities.ticketseverities_id ASC", array());
	//$result2 = $adb->pquery("select ticketseverities from vtiger_ticketseverities ", array());
	for($i=0;$i<$adb->num_rows($result2);$i++)
	{
		$output['ticketseverities']['ticketseverities'][$i] = $adb->query_result($result2,$i,"ticketseverities");
	}

	$result3 = $adb->pquery("select vtiger_ticketcategories.* from vtiger_ticketcategories inner join vtiger_role2picklist on vtiger_role2picklist.picklistvalueid = vtiger_ticketcategories.picklist_valueid and vtiger_role2picklist.roleid='$admin_role' ORDER BY vtiger_ticketcategories.ticketcategories_id ASC", array());
	//$result3 = $adb->pquery("select ticketcategories from vtiger_ticketcategories ", array());
	for($i=0;$i<$adb->num_rows($result3);$i++)
	{
		$output['ticketcategories']['ticketcategories'][$i] = $adb->query_result($result3,$i,"ticketcategories");
	}

	//Added to get the modules list
	$sql2 = "select vtiger_moduleowners.*, vtiger_tab.name from vtiger_moduleowners inner join vtiger_tab on vtiger_moduleowners.tabid = vtiger_tab.tabid order by vtiger_tab.tabsequence";
	$result4 = $adb->pquery($sql2, array());
	for($i=0;$i<$adb->num_rows($result4);$i++)
	{
		$output['moduleslist']['moduleslist'][$i] = $adb->query_result($result4,$i,"name");
	}

	return $output;
}

/**	function to get the Knowledge base details
 *	@param array $input_array - array which contains the following parameters
		=>	int $id - customer id
			string $sessionid - session id
 *	return array $result - array which contains the faqcategory, all product ids , product names and all faq details
 */
function get_KBase_details($input_array)
{
	global $adb;
	$adb->println("INPUT ARRAY for the function get_KBase_details");
	$adb->println($input_array);

	$id = $input_array['id'];
	$sessionid = $input_array['sessionid'];

	if(!validateSession($id,$sessionid))
		return null;
 
	//We are going to display the picklist entries associated with admin user (role is H2)
	$admin_role = 'H2';
	$category_query = "select vtiger_faqcategories.* from vtiger_faqcategories inner join vtiger_role2picklist on vtiger_role2picklist.picklistvalueid = vtiger_faqcategories.picklist_valueid and vtiger_role2picklist.roleid='$admin_role' ORDER BY vtiger_faqcategories.faqcategories_id ASC";
	//$category_query = "select faqcategories from vtiger_faqcategories";
	$category_result = $adb->pquery($category_query, array());
	$category_noofrows = $adb->num_rows($category_result);
	for($j=0;$j<$category_noofrows;$j++)
	{
		$faqcategory = $adb->query_result($category_result,$j,'faqcategories');
		$result['faqcategory'][$j] = $faqcategory;
	}

	$product_query = "select productid, productname from vtiger_products inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_products.productid where vtiger_crmentity.deleted=0";
	$product_result = $adb->pquery($product_query, array());
	$product_noofrows = $adb->num_rows($product_result);
	for($i=0;$i<$product_noofrows;$i++)
	{
		$productid = $adb->query_result($product_result,$i,'productid');
		$productname = $adb->query_result($product_result,$i,'productname');
		$result['product'][$i]['productid'] = $productid;
		$result['product'][$i]['productname'] = $productname;
	}

	$faq_query = "select vtiger_faq.*, vtiger_crmentity.createdtime, vtiger_crmentity.modifiedtime from vtiger_faq inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_faq.id where vtiger_crmentity.deleted=0 and vtiger_faq.status='Published' order by vtiger_crmentity.modifiedtime DESC";
	$faq_result = $adb->pquery($faq_query, array());
	$faq_noofrows = $adb->num_rows($faq_result);
	for($k=0;$k<$faq_noofrows;$k++)
	{
		$faqid = $adb->query_result($faq_result,$k,'id');
		$result['faq'][$k]['id'] = $faqid;
		$result['faq'][$k]['product_id']  = $adb->query_result($faq_result,$k,'product_id');
		$result['faq'][$k]['question'] =  nl2br($adb->query_result($faq_result,$k,'question'));
		$result['faq'][$k]['answer'] = nl2br($adb->query_result($faq_result,$k,'answer'));
		$result['faq'][$k]['category'] = $adb->query_result($faq_result,$k,'category');
		$result['faq'][$k]['faqcreatedtime'] = $adb->query_result($faq_result,$k,'createdtime');
		$result['faq'][$k]['faqmodifiedtime'] = $adb->query_result($faq_result,$k,'modifiedtime');

		$faq_comment_query = "select * from vtiger_faqcomments where faqid=? order by createdtime DESC";
		$faq_comment_result = $adb->pquery($faq_comment_query, array($faqid));
		$faq_comment_noofrows = $adb->num_rows($faq_comment_result);
		for($l=0;$l<$faq_comment_noofrows;$l++)
		{
			$faqcomments = nl2br($adb->query_result($faq_comment_result,$l,'comments'));
			$faqcreatedtime = $adb->query_result($faq_comment_result,$l,'createdtime');
			if($faqcomments != '')
			{
				$result['faq'][$k]['comments'][$l] = $faqcomments;
				$result['faq'][$k]['createdtime'][$l] = $faqcreatedtime;
			}
		}
	}
	$adb->println($result);	
	return $result;
}

/**	function to save the faq comment
 *	@param array $input_array - array which contains the following values 
 		=> 	int $id - Customer ie., Contact id
			int $sessionid - session id
			int $faqid - faq id
			string $comment - comment to be added with the FAQ
 *	return array $result - This function will call get_KBase_details and return that array
 */
function save_faq_comment($input_array)
{
	global $adb;
	$adb->println("INPUT ARRAY for the function save_faq_comment");
	$adb->println($input_array);

	//foreach($input_array as $fieldname => $fieldvalue)$input_array[$fieldname] = mysql_real_escape_string($fieldvalue);

	$id = $input_array['id'];
	$sessionid = $input_array['sessionid'];
	$faqid = (int) $input_array['faqid'];
	$comment = $input_array['comment'];

	if(!validateSession($id,$sessionid))
		return null;

	$createdtime = $adb->formatDate(date('YmdHis'),true);	
	if(trim($comment) != '')
	{
		$faq_query = "insert into vtiger_faqcomments values(?,?,?,?)";
		$adb->pquery($faq_query, array('', $faqid, $comment, $createdtime));
	}

	$params = Array('id'=>"$id", 'sessionid'=>"$sessionid");
	$result = get_KBase_details($input_array);

	return $result;
}

/** function to get a list of tickets and to search tickets
 * @param array $input_array - array which contains the following values 
 		=> 	int $id - Customer ie., Contact id
			int $only_mine - if true it will display only tickets related to contact 
			otherwise displays tickets related to account it belongs and all the contacts that are under the same account
			int $where - used for searching tickets
			string $match - used for matching tickets
 *	return array $result - This function will call get_KBase_details and return that array
 */


function get_tickets_list($input_array) {
	
	global $adb,$log,$show_all;
	$log->debug("Entering function get_tickets_list ");
	
	$id = $input_array['id'];
	$only_mine = $input_array['onlymine'];
	$where = $input_array['where']; //addslashes is already added with where condition fields in portal itself
	$match = $input_array['match'];
	$sessionid = $input_array['sessionid'];
	
	if(!validateSession($id,$sessionid))
		return null;
	
	// Prepare where conditions based on search query
	$join_type = '';
	$where_conditions = '';
	if(trim($where) != '') {
		if($match == 'all' || $match == '') {
			$join_type = " AND ";
		} elseif($match == 'any') {
			$join_type = " OR ";
		}
		$where = explode("&&&",$where);
		$where_conditions = implode($join_type, $where);
	}
	
	$entity_ids_list = array();
	if($only_mine == 'true' || $show_all == 'false') 
	{
		array_push($entity_ids_list,$id);
	} 
	else 
	{
		$contactquery = "SELECT contactid, accountid FROM vtiger_contactdetails " .
			" INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_contactdetails.contactid" .
			" AND vtiger_crmentity.deleted = 0 " .
			" WHERE (accountid = (SELECT accountid FROM vtiger_contactdetails WHERE contactid = ?)  AND accountid != 0) OR contactid = ?";
		$contactres = $adb->pquery($contactquery, array($id,$id));
		$no_of_cont = $adb->num_rows($contactres);
		for($i=0;$i<$no_of_cont;$i++)
		{
			$cont_id = $adb->query_result($contactres,$i,'contactid');
			$acc_id = $adb->query_result($contactres,$i,'accountid');
			if(!in_array($cont_id, $entity_ids_list))
				$entity_ids_list[] = $cont_id;
			if(!in_array($acc_id, $entity_ids_list) && $acc_id != '0')
				$entity_ids_list[] = $acc_id;
		}
	}	
	
	$fields_list = array(
		'ticketid' => 'Ticket Id',
		'title' => 'Title',
		'priority' => 'Priority',
		'status' => 'Status',
		'category' => 'Category',
		'parent_id' => 'Related To',
		'modifiedtime' => 'Modified Time',
		'createdtime' => 'Created Time'
	);
	
	$query = "SELECT vtiger_troubletickets.*, vtiger_crmentity.createdtime, vtiger_crmentity.modifiedtime, '' AS setype
				FROM vtiger_troubletickets 
				INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_troubletickets.ticketid AND vtiger_crmentity.deleted = 0
				WHERE vtiger_troubletickets.parent_id IN (". generateQuestionMarks($entity_ids_list) .")";
	// Add conditions if there are any search parameters
	if ($join_type != '' && $where_conditions != '') {
		$query .= " AND (".$where_conditions.")";
	}
	$params = array($entity_ids_list);
	
	$res = $adb->pquery($query,$params);
	$noofdata = $adb->num_rows($res);
	
	for( $j= 0;$j < $noofdata; $j++)
	{
		$i=0;
		foreach($fields_list as $fieldname => $fieldlabel) {
			$output[0]['head'][0][$i]['fielddata'] = $fieldlabel;
			$fieldvalue = $adb->query_result($res,$j,$fieldname);
						
			if($fieldname == 'parent_id') {
				$crmid = $fieldvalue;
				$module = getSalesEntityType($crmid);
				if ($crmid != '' && $module != '') {
					$fieldvalues = getEntityName($module, array($crmid));
					if($module == 'Contacts')
					$fieldvalue = '<a href="index.php?module=Contacts&action=index&customer_id='.$crmid.'">'.$fieldvalues[$crmid].'</a>';
					elseif($module == 'Accounts')
					$fieldvalue = '<a href="index.php?module=Accounts&action=index&id='.$crmid.'">'.$fieldvalues[$crmid].'</a>';
				} else {
					$fieldvalue = '';
				}
			}
			$output[1]['data'][$j][$i]['fielddata'] = $fieldvalue;
			$i++;
		}
	}	
	$adb->println("out".$output,true);	

 	return $output;	 
}

/**	function used to create ticket which has been created from customer portal
 *	@param array $input_array - array which contains the following values 
 		=> 	int $id - customer id
			int $sessionid - session id
			string $title - title of the ticket
			string $description - description of the ticket
			string $priority - priority of the ticket
			string $severity - severity of the ticket
			string $category - category of the ticket
			string $user_name - customer name
			int $parent_id - parent id ie., customer id as this customer is the parent for this ticket
			int $product_id - product id for the ticket
			string $module - module name where as based on this module we will get the module owner and assign this ticket to that corresponding user
 *	return array - currently created ticket array, if this is not created then all tickets list will be returned
 */
function create_ticket($input_array)
{
	global $adb,$log, $Ticket_Assigned_to;
	$adb->println("INPUT ARRAY for the function create_ticket");
	$adb->println($input_array);

	//foreach($input_array as $fieldname => $fieldvalue)$input_array[$fieldname] = mysql_real_escape_string($fieldvalue);

	$id = $input_array['id'];
	$sessionid = $input_array['sessionid'];
	$title = $input_array['title'];
	$description = $input_array['description'];
	$priority = $input_array['priority'];
	$severity = $input_array['severity'];
	$category = $input_array['category'];
	$user_name = $input_array['user_name'];
	$parent_id = (int) $input_array['parent_id'];
	$product_id = (int) $input_array['product_id'];
	$module = $input_array['module'];
	//$assigned_to = $input_array['assigned_to'];
	
	if(!validateSession($id,$sessionid))
		return null;

        $seed_ticket = new HelpDesk();
        $output_list = Array();
   
	$ticket = new HelpDesk();
	
    	$ticket->column_fields[ticket_title] = $title;
	$ticket->column_fields[description]=$description;
	$ticket->column_fields[ticketpriorities]=$priority;
	$ticket->column_fields[ticketseverities]=$severity;
	$ticket->column_fields[ticketcategories]=$category;
	$ticket->column_fields[ticketstatus]='Open';

	$ticket->column_fields[parent_id]=$parent_id;
	$ticket->column_fields[product_id]=$product_id;

	//Added to get the user based on module from vtiger_moduleowners -- 10-11-2005
	$user_id = 1;//Default admin user id
	$groupname = '';
	if($module != '')
	{
		$res = $adb->pquery("select vtiger_moduleowners.*, vtiger_tab.name from vtiger_moduleowners inner join vtiger_tab on vtiger_moduleowners.tabid = vtiger_tab.tabid where name=?", array($module));
		if($adb->num_rows($res) > 0)
		{
			$user_id = $adb->query_result($res,0,"user_id");
		}
	}
	
	if($Ticket_Assigned_to != ''){
		$groupquery = "select groupid, groupname from vtiger_groups where groupname like '$Ticket_Assigned_to'";
		$groupres = $adb->pquery($groupquery,array());
		$log->debug("groupre".print_r($groupres,true));
		if ($adb->num_rows($groupres) > 0) {
			$groupname = $adb->query_result($groupres,0,'groupname');
			$user_id = 0;
		} else {
			$userquery = "select id from vtiger_users where user_name like '$Ticket_Assigned_to'";
			$userres = $adb->pquery($userquery,array());
			if ($adb->num_rows($userres) > 0) {
				$user_id = $adb->query_result($userres,0,'id');
			} 
		}
	} 
	
	$log->debug("user_id".$user_id);
	$ticket->column_fields[assigned_user_id]=$user_id;

	$adb->println($ticket->column_fields);
    $ticket->save("HelpDesk");
    
	$subject = '[From Portal][ Ticket ID : '.$ticket->id.' ] '.$title;
	$contents = ' Ticket ID : '.$ticket->id.'<br> Ticket Title : '.$title.'<br><br>'.$description;

	//get the contact email id who creates the ticket from portal and use this email as from email id in email
	$result = $adb->pquery("select email from vtiger_contactdetails where contactid=?", array($parent_id));
	$contact_email = $adb->query_result($result,0,'email');
	$from_email = $contact_email;

	//send mail to assigned to user
	$to_email = getUserEmailId('id',$user_id);
	$adb->println("Send mail to the user who is the owner of the module about the portal ticket");
	$mail_status = send_mail('HelpDesk',$to_email,'',$from_email,$subject,$contents);

	//send mail to the customer(contact who creates the ticket from portal)
	$adb->println("Send mail to the customer(contact) who creates the portal ticket");
	$mail_status = send_mail('Contacts',$contact_email,'',$from_email,$subject,$contents);

	//Calling this function will be taking time. Instead of this we have to check whether the ticket is created or not
	/*$params = Array('id'=>"$id", 'sessionid'=>"$sessionid", 'user_name'=>"$user_name");
	$tickets_list =  get_tickets_list($params); 
	
	foreach($tickets_list as $ticket_array)
	{
		if($ticket->id == $ticket_array['ticketid'])
		{
			$record_save = 1;
			$record_array[0]['new_ticket'] = $ticket_array;
		}
	}
	*/

	$ticketresult = $adb->query("select vtiger_troubletickets.ticketid from vtiger_troubletickets inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_troubletickets.ticketid inner join vtiger_ticketcf on vtiger_ticketcf.ticketid = vtiger_troubletickets.ticketid where vtiger_crmentity.deleted=0 and vtiger_troubletickets.ticketid = $ticket->id");
	if($adb->num_rows($ticketresult) == 1)
	{
		$record_save = 1;
		$record_array[0]['new_ticket']['ticketid'] = $adb->query_result($ticketresult,0,'ticketid');
	}

	if($record_save == 1)
	{
		$adb->println("Ticket from Portal is saved with id => ".$ticket->id);
		return $record_array;
	}
	else
	{
		$adb->println("There may be error in saving the ticket.");
		return $tickets_list;
	}
}

/**	function used to update the ticket comment which is added from the customer portal
 *	@param array $input_array - array which contains the following values 
 		=> 	int $id - customer id
			int $sessionid - session id
			int $ticketid - ticket id
			int $ownerid - customer ie., contact id who has added this ticket comment
			string $comments - comment which is added from the customer portal
 *	return void
 */
function update_ticket_comment($input_array)
{
	global $adb,$mod_strings;
	$adb->println("INPUT ARRAY for the function update_ticket_comment");
	$adb->println($input_array);

	//foreach($input_array as $fieldname => $fieldvalue)$input_array[$fieldname] = mysql_real_escape_string($fieldvalue);

	$id = $input_array['id'];
	$sessionid = $input_array['sessionid'];
	$ticketid = (int) $input_array['ticketid'];
	$ownerid = (int) $input_array['ownerid'];
	$comments = $input_array['comments'];

	if(!validateSession($id,$sessionid))
		return null;

	$servercreatedtime = $adb->formatDate(date('YmdHis'), true);
  	if(trim($comments) != '')
  	{
 		$sql = "insert into vtiger_ticketcomments values(?,?,?,?,?,?)";
  		$params1 = array('', $ticketid, $comments, $ownerid, 'customer', $servercreatedtime);
		$adb->pquery($sql, $params1);
  
 		$updatequery = "update vtiger_crmentity set modifiedtime=? where crmid=?";
		$updateparams = array($servercreatedtime, $ticketid);
  		$adb->pquery($updatequery, $updateparams);

		//To get the username and user email id, user means assigned to user of the ticket
		$result = $adb->pquery("select user_name, email1 from vtiger_users inner join vtiger_crmentity on vtiger_users.id=vtiger_crmentity.smownerid where vtiger_crmentity.crmid=?", array($ticketid));
		$owner = $adb->query_result($result,0,'user_name');
		$to_email = $adb->query_result($result,0,'email1');

		//To get the contact name
		$result1 = $adb->pquery("select lastname, firstname, email from vtiger_contactdetails where contactid=?", array($ownerid));
		$customername = $adb->query_result($result1,0,'firstname').' '.$adb->query_result($result1,0,'lastname');
		$customername = decode_html($customername);//Fix to display the original UTF-8 characters in sendername instead of ascii characters 
		$from_email = $adb->query_result($result1,0,'email');

		//send mail to the assigned to user when customer add comment
		$subject = $mod_strings['LBL_RESPONDTO_TICKETID']."##". $ticketid."##". $mod_strings['LBL_CUSTOMER_PORTAL'];
		$contents = $mod_strings['Dear']." ".$owner.","."<br><br>"
				.$mod_strings['LBL_CUSTOMER_COMMENTS']."<br><br>

				<b>".nl2br($comments)."</b><br><br>"

				.$mod_strings['LBL_RESPOND']."<br><br>"

				.$mod_strings['LBL_REGARDS']."<br>"
				.$mod_strings['LBL_SUPPORT_ADMIN'];

		$mailstatus = send_mail('HelpDesk',$to_email,$customername,$from_email,$subject,$contents);
  	}
}

/**	function used to close the ticket
 *	@param array $input_array - array which contains the following values 
 		=> 	int $id - customer id
			int $sessionid - session id
			int $ticketid - ticket id
 *	return string - success or failure message will be returned based on the ticket close update query
 */
function close_current_ticket($input_array)
{
	global $adb,$mod_strings;
	$adb->println("INPUT ARRAY for the function close_current_ticket");
	$adb->println($input_array);

	//foreach($input_array as $fieldname => $fieldvalue)$input_array[$fieldname] = mysql_real_escape_string($fieldvalue);

	$id = $input_array['id'];
	$sessionid = $input_array['sessionid'];
	$ticketid = (int) $input_array['ticketid'];

	if(!validateSession($id,$sessionid))
		return null;

	$sql = "update vtiger_troubletickets set status=? where ticketid=?";
	$result = $adb->pquery($sql, array($mod_strings['LBL_STATUS_CLOSED'], $ticketid));
	if($result)
		return "<br><b>".$mod_strings['LBL_STATUS_UPDATE']." "."'".$mod_strings['LBL_STATUS_CLOSED']."'"."."."</b>";
	else
		return "<br><b>".$mod_strings['LBL_COULDNOT_CLOSED']." ".$mod_strings['LBL_STATUS_CLOSED']."."."</br>";
}

/**	function used to authenticate whether the customer has access or not
 *	@param string $username - customer name for the customer portal
 *	@param string $password - password for the customer portal
 *	@param string $login - true or false. If true means function has been called for login process and we have to clear the session if any, false means not called during login and we should not unset the previous sessions
 *	return array $list - returns array with all the customer details
 */
function authenticate_user($username,$password,$login = 'true')
{
	global $adb;
	$adb->println("Inside the function authenticate_user($username, $password, $login).");

	$username = mysql_real_escape_string($username);
	$password = mysql_real_escape_string($password);

	$current_date = date("Y-m-d");
	$sql = "select id, user_name, user_password,last_login_time, support_start_date, support_end_date from vtiger_portalinfo inner join vtiger_customerdetails on vtiger_portalinfo.id=vtiger_customerdetails.customerid inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_portalinfo.id where vtiger_crmentity.deleted=0 and user_name=? and user_password = ? and isactive=1 and vtiger_customerdetails.support_end_date >= ?";
	$result = $adb->pquery($sql, array($username, $password, $current_date));

	$err[0]['err1'] = "There may more than one user with this details. Please contact your admin.";
	$err[1]['err1'] = "Please enter a valid username and password.";

	$num_rows = $adb->num_rows($result);

	if($num_rows > 1)		return $err[0];//More than one user
	elseif($num_rows <= 0)		return $err[1];//No user

	$customerid = $adb->query_result($result,0,'id');

	$list[0]['id'] = $customerid;
	$list[0]['user_name'] = $adb->query_result($result,0,'user_name');
	$list[0]['user_password'] = $adb->query_result($result,0,'user_password');
	$list[0]['last_login_time'] = $adb->query_result($result,0,'last_login_time');
	$list[0]['support_start_date'] = $adb->query_result($result,0,'support_start_date');
	$list[0]['support_end_date'] = $adb->query_result($result,0,'support_end_date');

	//During login process we will pass the value true. Other times (change password) we will pass false
	if($login != 'false')
	{
		$sessionid = makeRandomPassword();

		unsetServerSessionId($customerid);

		$sql="insert into vtiger_soapservice values(?,?,?)";
		$result = $adb->pquery($sql, array($customerid,'customer' ,$sessionid));

		$list[0]['sessionid'] = $sessionid;
	}

	return $list;
}

/**	function used to change the password for the customer portal
 *	@param array $input_array - array which contains the following values 
 		=> 	int $id - customer id
			int $sessionid - session id
			string $username - customer name
			string $password - new password to change
 *	return array $list - returns array with all the customer details
 */
function change_password($input_array)
{
	global $adb;
	$adb->println("INPUT ARRAY for the function change_password");
	$adb->println($input_array);

	$id = (int) $input_array['id'];
	$sessionid = $input_array['sessionid'];
	$username = $input_array['username'];
	$password = $input_array['password'];

	if(!validateSession($id,$sessionid))
		return null;

	$sql = "update vtiger_portalinfo set user_password=? where id=? and user_name=?";
	$result = $adb->pquery($sql, array($password, $id, $username));

	$list = authenticate_user($username,$password,'false');

	return $list;
}

/**	function used to update the login details for the customer
 *	@param array $input_array - array which contains the following values 
 		=> 	int $id - customer id
			int $sessionid - session id
			string $flag - login/logout, based on this flag, login or logout time will be updated for the customer
 *	return string $list - empty value
 */
function update_login_details($input_array)
{
	global $adb;
	$adb->println("INPUT ARRAY for the function update_login_details");
	$adb->println($input_array);

	$id = $input_array['id'];
	$sessionid = $input_array['sessionid'];
	$flag = $input_array['flag'];

	if(!validateSession($id,$sessionid))
		return null;

	$current_time = $adb->formatDate(date('YmdHis'), true);	

	if($flag == 'login')
	{
		$sql = "update vtiger_portalinfo set login_time=? where id=?"; 
		$result = $adb->pquery($sql, array($current_time, $id));
	}
	elseif($flag == 'logout')
	{
		$sql = "select * from vtiger_portalinfo where id=?";
		$result = $adb->pquery($sql, array($id));
		if($adb->num_rows($result) != 0)
			$last_login = $adb->query_result($result,0,'login_time');

		$sql = "update vtiger_portalinfo set logout_time=?, last_login_time=? where id=?";	
		$result = $adb->pquery($sql, array($current_time, $last_login, $id));
	}
	return $list;
}

/**	function used to send mail to the customer when he forgot the password and want to retrieve the password
 *	@param string $mailid - email address of the customer
 *	return message about the mail sending whether entered mail id is correct or not or is there any problem in mail sending
 */
function send_mail_for_password($mailid)
{
	global $adb,$mod_strings;
	$adb->println("Inside the function send_mail_for_password($mailid).");

	//$mailid = mysql_real_escape_string($input_array['email']);

	$sql = "select * from vtiger_portalinfo  where user_name=?";
	$res = $adb->pquery($sql, array($mailid));
	$user_name = $adb->query_result($res,0,'user_name');
	$password = $adb->query_result($res,0,'user_password');
	$isactive = $adb->query_result($res,0,'isactive');

	$fromquery = "select vtiger_users.user_name, vtiger_users.email1 from vtiger_users inner join vtiger_crmentity on vtiger_users.id = vtiger_crmentity.smownerid inner join vtiger_contactdetails on vtiger_contactdetails.contactid=vtiger_crmentity.crmid where vtiger_contactdetails.email =?";
	$from_res = $adb->pquery($fromquery, array($mailid));
	$initialfrom = $adb->query_result($from_res,0,'user_name');
	$from = $adb->query_result($from_res,0,'email1');

	$contents = $mod_strings['LBL_LOGIN_DETAILS'];
	$contents .= "<br><br>".$mod_strings['LBL_USERNAME']." ".$user_name;
	$contents .= "<br>".$mod_strings['LBL_PASSWORD']." ".$password;

        $mail = new PHPMailer();

        $mail->Subject = $mod_strings['LBL_SUBJECT_PORTAL_LOGIN_DETAILS'];
        $mail->Body    = $contents;
        $mail->IsSMTP();

        $mailserverresult = $adb->pquery("select * from vtiger_systems where server_type=?", array('email'));
        $mail_server = $adb->query_result($mailserverresult,0,'server');
        $mail_server_username = $adb->query_result($mailserverresult,0,'server_username');
        $mail_server_password = $adb->query_result($mailserverresult,0,'server_password');
        $smtp_auth = $adb->query_result($mailserverresult,0,'smtp_auth');

	$mail->Host = $mail_server;
	if($smtp_auth == 'true')
	        $mail->SMTPAuth = 'true';
        $mail->Username = $mail_server_username;
        $mail->Password = $mail_server_password;
        $mail->From = $from;
        $mail->FromName = $initialfrom;

        $mail->AddAddress($user_name);
        $mail->AddReplyTo($current_user->name);
        $mail->WordWrap = 50;

        $mail->IsHTML(true);

        $mail->AltBody = $mod_strings['LBL_ALTBODY'];
	if($mailid == '')
	{
		$ret_msg = "false@@@<b>".$mod_strings['LBL_GIVE_MAILID']."</b>";
	}
	elseif($user_name == '' && $password == '')
	{
		$ret_msg = "false@@@<b>".$mod_strings['LBL_CHECK_MAILID']."</b>";
	}
	elseif($isactive == 0)
        {
                $ret_msg = "false@@@<b>".$mod_strings['LBL_LOGIN_REVOKED']."</b>";
        }
	elseif(!$mail->Send())
	{
		$ret_msg = "false@@@<b>".$mod_strings['LBL_MAIL_COULDNOT_SENT']."</b>";
	}
	else
	{
		$ret_msg = "true@@@<b>".$mod_strings['LBL_MAIL_SENT']."</b>";
	}

	$adb->println("Exit from send_mail_for_password. $ret_msg");

	return $ret_msg;
}

/**	function used to get the ticket creater 
 *	@param array $input_array - array which contains the following values 
 		=>	int $id - customer ie., contact id 
			int $sessionid - session id
			int $ticketid - ticket id
 *	return int $creator - ticket created user id will be returned ie., smcreatorid from crmentity table
 */
function get_ticket_creator($input_array)
{
	global $adb;
	$adb->println("INPUT ARRAY for the function get_ticket_creator");
	$adb->println($input_array);

	$id = $input_array['id'];
	$sessionid = $input_array['sessionid'];
	$ticketid = (int) $input_array['ticketid'];

	if(!validateSession($id,$sessionid))
		return null;

	$res = $adb->pquery("select smcreatorid from vtiger_crmentity where crmid=?", array($ticketid));
	$creator = $adb->query_result($res,0,'smcreatorid');

	return $creator;
}

/**	function used to get the picklist values
 *	@param array $input_array - array which contains the following values 
 		=>	int $id - customer ie., contact id 
			int $sessionid - session id
			string $picklist_name - picklist name you want to retrieve from database
 *	return array $picklist_array - all values of the corresponding picklist will be returned as a array
 */
function get_picklists($input_array)
{
	global $adb, $log;
	$adb->println("INPUT ARRAY for the function get_picklists");
	$adb->println($input_array);

	$id = $input_array['id'];
	$sessionid = $input_array['sessionid'];
	$picklist_name = mysql_real_escape_string($input_array['picklist_name']);

	if(!validateSession($id,$sessionid))
		return null;

	$picklist_array = Array();

	//We are going to display the picklist entries associated with admin user (role is H2)
	$admin_role = 'H2';
	$res = $adb->pquery("select vtiger_". $picklist_name.".* from vtiger_". $picklist_name." inner join vtiger_role2picklist on vtiger_role2picklist.picklistvalueid = vtiger_". $picklist_name.".picklist_valueid and vtiger_role2picklist.roleid='$admin_role' ORDER BY vtiger_". $picklist_name.".".$picklist_name."_id ASC", array());
	//$res = $adb->pquery("select * from vtiger_". $picklist_name." ORDER BY ".$picklist_name."_id ASC", array());
	for($i=0;$i<$adb->num_rows($res);$i++)
	{
		$picklist_val = $adb->query_result($res,$i,$picklist_name);
		$picklist_array[$i] = $picklist_val;
	}

	$adb->println($picklist_array);
	$log->debug("Exit from function get_picklists($picklist_name)");
	return $picklist_array;
}

/**	function to get the attachments of a ticket
 *	@param array $input_array - array which contains the following values 
 		=>	int $id - customer ie., contact id 
			int $sessionid - session id
			int $ticketid - ticket id
 *	return array $output - This will return all the file details related to the ticket
 */
function get_ticket_attachments($input_array)
{
	global $adb;
	$adb->println("INPUT ARRAY for the function get_ticket_attachments");
	$adb->println($input_array);

	$id = $input_array['id'];
	$sessionid = $input_array['sessionid'];
	$ticketid = $input_array['ticketid'];
	
	$isPermitted = check_permission($id,'Tickets',$ticketid);
	if($isPermitted == false) {
		return array("#NOT AUTHORIZED#");
	}

	if(!validateSession($id,$sessionid))
		return null;

	$query = "select vtiger_troubletickets.ticketid, vtiger_attachments.* from vtiger_troubletickets inner join vtiger_seattachmentsrel on vtiger_seattachmentsrel.crmid = vtiger_troubletickets.ticketid inner join vtiger_attachments on vtiger_seattachmentsrel.attachmentsid = vtiger_attachments.attachmentsid where vtiger_troubletickets.ticketid=?";
	$res = $adb->pquery($query, array($ticketid));
	$noofrows = $adb->num_rows($res);

	for($i=0;$i<$noofrows;$i++)
	{
		$filename = $adb->query_result($res,$i,'name');
		$filepath = $adb->query_result($res,$i,'path');

		$fileid = $adb->query_result($res,$i,'attachmentsid');
		$filesize = filesize($filepath.$fileid."_".$filename);
		$filetype = $adb->query_result($res,$i,'type');

		//Now we will not pass the file content to CP, when the customer click on the link we will retrieve
		//$filecontents = base64_encode(file_get_contents($filepath.$fileid."_".$filename));//fread(fopen($filepath.$filename, "r"), $filesize));

		$output[$i]['fileid'] = $fileid;
		$output[$i]['filename'] = $filename;
		$output[$i]['filetype'] = $filetype;
		$output[$i]['filesize'] = $filesize;
		//$output[$i]['filecontents'] = $filecontents;
	}

	return $output;
}

/**	function used to get the contents of a file
 *	@param array $input_array - array which contains the following values 
 		=>	int $id - customer ie., contact id 
			int $sessionid - session id
			int $fileid - id of the file to which we want contents
			string $filename - name of the file to which we want contents
 *	return $filecontents array with single file contents like [fileid] => filecontent
 */
function get_filecontent($input_array)
{
	global $adb;
	$adb->println("INPUT ARRAY for the function get_filecontent");
	$adb->println($input_array);

	$id = $input_array['id'];
	$sessionid = $input_array['sessionid'];
	$fileid = $input_array['fileid'];
	$filename = $input_array['filename'];

	if(!validateSession($id,$sessionid))
		return null;

	$query = "select vtiger_attachments.path from vtiger_troubletickets 
		inner join vtiger_seattachmentsrel on vtiger_seattachmentsrel.crmid = vtiger_troubletickets.ticketid 
		inner join vtiger_attachments on vtiger_attachments.attachmentsid = vtiger_seattachmentsrel.attachmentsid 
		where 	vtiger_troubletickets.parent_id=?  and 
		vtiger_attachments.attachmentsid= ? and 
		vtiger_attachments.name=?";
	$res = $adb->pquery($query, array($id, $fileid, $filename));

	if($adb->num_rows($res)>0)
	{
		$filenamewithpath = $adb->query_result($res,0,'path').$fileid."_".$filename;
		$filecontents[$fileid] = base64_encode(file_get_contents($filenamewithpath));
		$adb->println("Going to return the content of the file ==> $filenamewithpath");
	}
	return $filecontents;
}

/**	function to add attachment for a ticket ie., the passed contents will be write in a file and the details will be stored in database
 *	@param array $input_array - array which contains the following values 
 		=>	int $id - customer ie., contact id 
			int $sessionid - session id
			int $ticketid - ticket id
			string $filename - file name to be attached with the ticket
			string $filetype - file type
			int $filesize - file size
			string $filecontents - file contents as base64 encoded format
 *	return void
 */
function add_ticket_attachment($input_array)
{
	global $adb,$log;
	global $root_directory;
	$adb->println("INPUT ARRAY for the function add_ticket_attachment");
	$adb->println($input_array);

	$id = $input_array['id'];
	$sessionid = $input_array['sessionid'];
	$ticketid = $input_array['ticketid'];
	$filename = $input_array['filename'];
	$filetype = $input_array['filetype'];
	$filesize = $input_array['filesize'];
	$filecontents = $input_array['filecontents'];

	if(!validateSession($id,$sessionid))
		return null;

	//decide the file path where we should upload the file in the server
	$upload_filepath = decideFilePath();

	$attachmentid = $adb->getUniqueID("vtiger_crmentity");

	//fix for space in file name
	$filename = preg_replace('/\s+/', '_', $filename);
	$new_filename = $attachmentid.'_'.$filename;

	$data = base64_decode($filecontents);

	//write a file with the passed content
	$handle = @fopen($upload_filepath.$new_filename,'w');
	fputs($handle, $data);
	fclose($handle);	

	//Now store this file information in db and relate with the ticket
	$date_var = $adb->formatDate(date('YmdHis'), true);
  	$description = 'CustomerPortal Attachment';
  
 	$crmquery = "insert into vtiger_crmentity (crmid,setype,description,createdtime) values(?,?,?,?)";
	$crmresult = $adb->pquery($crmquery, array($attachmentid, 'HelpDesk Attachment', $description, $date_var));

	$attachmentquery = "insert into vtiger_attachments(attachmentsid,name,description,type,path) values(?,?,?,?,?)";
	$attachmentreulst = $adb->pquery($attachmentquery, array($attachmentid, $filename, $description, $filetype, $upload_filepath));

	$relatedquery = "insert into vtiger_seattachmentsrel values(?,?)";
	$relatedresult = $adb->pquery($relatedquery, array($ticketid, $attachmentid));

}

/**	Function used to validate the session
  *	@param int $id - contact id to which we want the session id
  *	@param string $sessionid - session id which will be passed from customerportal
  *	return true/false - return true if valid session otherwise return false
**/
function validateSession($id, $sessionid)
{
	global $adb;
	$adb->println("Inside function validateSession($id, $sessionid)");

	$server_sessionid = getServerSessionId($id);

	$adb->println("Checking Server session id and customer input session id ==> $server_sessionid == $sessionid");

	if($server_sessionid == $sessionid)
	{
		$adb->println("Session id match. Authenticated to do the current operation.");
		return true;
	}
	else
	{
		$adb->println("Session id does not match. Not authenticated to do the current operation.");
		return false;
	}
}


/**	Function used to get the session id which was set during login time
  *	@param int $id - contact id to which we want the session id
  *	return string $sessionid - return the session id for the customer which is a random alphanumeric character string
**/
function getServerSessionId($id)
{
	global $adb;
	$adb->println("Inside the function getServerSessionId($id)");

	//To avoid SQL injection we are type casting as well as bound the id variable. In each and every function we will call this function
	$id = (int) $id;

	$query = "select * from vtiger_soapservice where type='customer' and id={$id}";
	$sessionid = $adb->query_result($adb->query($query),0,'sessionid');

	return $sessionid;
}

/**	Function used to unset the server session id for the customer
 *	@param int $id - contact id to which customer we want to unset the session id
 **/
function unsetServerSessionId($id)
{
	global $adb;
	$adb->println("Inside the function unsetServerSessionId");

	$id = (int) $id;

	$adb->query("delete from vtiger_soapservice where type='customer' and id=$id");

	return;
}


/**	function used to get the Contact Information
 *	@param int $id - contact id
 *	return string $message - contact informations will be returned from :contactdetails table
 */
function get_contact_detail($id,$block,$customerid,$sessionid)
{
	$isPermitted = check_permission($customerid,'Contacts',$id);
	if($isPermitted == false) {
		return array("#NOT AUTHORIZED#");
	}
	global $adb;
	
	if(!validateSession($customerid,$sessionid))
		return null;
		
	if($block == 'MYINFO')
	{
		$fields_list = "'firstname','phone','lastname','mobile','accountid','leadsource','title','fax','email','smownerid'";
	}
	elseif($block =='ADDRESSINFO')
	{
		$fields_list ="'mailingstreet','otherstreet','mailingpobox','otherpobox','mailingcity','othercity','mailingstate','otherstate','mailingzip','otherzip','mailingcountry','othercountry'";
	}
	elseif($block =='DESCINFO')
	{
		$fields_list ="'description'";
	}
	$fieldquery = "select fieldname, columnname, fieldlabel from vtiger_field where tabid=4 and columnname in ($fields_list) order by sequence";
	$fieldres = $adb->pquery($fieldquery);
	$nooffields = $adb->num_rows($fieldres);

	$query = "select vtiger_contactdetails.*,vtiger_contactaddress.*,vtiger_contactsubdetails.*,vtiger_customerdetails.customerid,vtiger_crmentity.* 
	from vtiger_contactdetails 
	inner join vtiger_contactaddress on vtiger_contactaddress.contactaddressid=vtiger_contactdetails.contactid 
	inner join vtiger_contactsubdetails on vtiger_contactsubdetails.contactsubscriptionid=vtiger_contactdetails.contactid 	     inner join vtiger_customerdetails on vtiger_customerdetails.customerid =vtiger_contactdetails.contactid 
	inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_contactdetails.contactid  
	where vtiger_contactdetails.contactid=".$id;
	$res = $adb->pquery($query);

	for($i=0;$i<$nooffields;$i++)
	{
		$fieldname  = $adb->query_result($fieldres,$i,'columnname');
		$fieldlabel = $adb->query_result($fieldres,$i,'fieldlabel');
		$fieldvalue = $adb->query_result($res,0,$fieldname);
		if($block=='MYINFO' && $fieldname == 'accountid' && $fieldvalue != '')
		{
			//$fieldvalue = get_account_name($fieldvalue);
			$fieldvalue = '<a href=index.php?module=Accounts&action=index&id='.$fieldvalue.'>'.get_account_name($fieldvalue).'</a>';
				
		}
		if($block=='MYINFO' && $fieldname == 'smownerid' && $fieldvalue != '')
		{
			$temp_fieldvalue = get_ownername($id,"Contacts");
			$fieldlabel = '';	
			$fieldvalue = '';
			//$fieldvalue = $temp_fieldvalue[0]['groupname'][0]['fieldvalue'];
			$output[0]['assigninfo'] = $temp_fieldvalue[0]['assigninfo'];
		}
		$output[0][$block][$i]['fieldlabel'] = $fieldlabel;
		$output[0][$block][$i]['fieldvalue'] = $fieldvalue;
	}
	
	return $output;
}


/**	function used to get the Contact CustomField Information
 *	@param int $id - contact id
 *	return string $message - contact informations will be returned from :contactdetails table
 */
function get_cf_field_details($id,$customerid,$sessionid)
{
	$isPermitted = check_permission($customerid,'Contacts',$id);
	if($isPermitted == false) {
		return array("#NOT AUTHORIZED#");
	}
	if(!validateSession($customerid,$sessionid))
		return null;
	global $adb;

	$fieldquery = "select fieldname, columnname, fieldlabel from vtiger_field where tabid=4 and tablename='vtiger_contactscf'";
	$fieldres = $adb->pquery($fieldquery);
	$nooffields = $adb->num_rows($fieldres);

	$query = "
	  select vtiger_contactscf.* from vtiger_contactdetails 
   		inner join vtiger_contactscf on vtiger_contactscf.contactid = vtiger_contactdetails.contactid
		inner join vtiger_contactaddress on vtiger_contactaddress.contactaddressid=vtiger_contactdetails.contactid 
		inner join vtiger_contactsubdetails on vtiger_contactsubdetails.contactsubscriptionid=vtiger_contactdetails.contactid 
		inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_contactdetails.contactid 
		where vtiger_contactscf.contactid=".$id;

   	$adb->println($query);
	$res = $adb->pquery($query);
	$output[0]['CUSTOMINFO'][0]['customfield']['fieldlabel'] =0;
	for($i=0;$i<$nooffields;$i++)
	{
		$output[0]['CUSTOMINFO'][0]['customfield']['fieldlabel'] ='1';
		$fieldname = $adb->query_result($fieldres,$i,'columnname');
		$output[0]['CUSTOMINFO'][$i]['fieldlabel'] = $adb->query_result($fieldres,$i,'fieldlabel');
		$output[0]['CUSTOMINFO'][$i]['fieldvalue'] = $adb->query_result($res,0,$fieldname);
	}
	
	return $output;
}

/**	function used to get the Assign_To Values
 *	@param int $id - id,modulename
 *	return string $message - Assigned To name returned
 */

function get_ownername($id,$modulename)
{
	global $adb,$log;
	$log->debug("Entering Function get_ownername..");
	$Allowed = api_permission($modulename);
	if($Allowed == 'false')
		return 'false';
 	$assignres = $adb->pquery("select * from vtiger_crmentity where crmid=".$id);
	$ownerid=$adb->query_result($assignres,0,'smownerid');
	
	$query='select groupname from vtiger_groups where groupid ='.$ownerid;
	$assigngroupres = $adb->pquery($query, 0, 'groupname');
	if($assigngroupres != '' && $modulename != '')
	{
		$groupname[0]['groupname'][0]['fieldlabel']='groupname';
		$groupname[0]['groupname'][0]['fieldvalue']=$assigngroupres;
		$groupname[0]['assigninfo'][0]['fieldlabel']='Group Name';
		$groupname[0]['assigninfo'][0]['fieldvalue']=$assigngroupres;
	}
	else if(($assigngroupres == '' || !isset($assigngroupres)) && $ownerid != '')
	{
		$query='select * from vtiger_users where id ='.$ownerid;
		$assignuserres = $adb->pquery($query);
		$groupname[0]['groupname'][0]['fieldlabel']='username';
		$groupname[0]['groupname'][0]['fieldvalue']=$adb->query_result($assignuserres,0,'user_name');
		$groupname[0]['assigninfo'][0]['fieldlabel']='First Name';
		$groupname[0]['assigninfo'][0]['fieldvalue']=$adb->query_result($assignuserres,0,'first_name');
		$groupname[0]['assigninfo'][1]['fieldlabel']='Last Name';
		$groupname[0]['assigninfo'][1]['fieldvalue']=$adb->query_result($assignuserres,0,'last_name');
		$groupname[0]['assigninfo'][2]['fieldlabel']='Email';
		$groupname[0]['assigninfo'][2]['fieldvalue']=$adb->query_result($assignuserres,0,'email1');
		$groupname[0]['assigninfo'][3]['fieldlabel']='Office Phone';
		$groupname[0]['assigninfo'][3]['fieldvalue']=$adb->query_result($assignuserres,0,'phone_work');
	}
	return $groupname;	 
}


/**	function used to get the Account name
 *	@param int $id - Account id
 *	return string $message - Account name returned
 */

function get_account_name($accountid)
{
	 global $adb;
	 $res = $adb->pquery("select * from vtiger_account where accountid=".$accountid);
	 $accountname=$adb->query_result($res,0,'accountname');
	 return $accountname;	 
}
/** function used to get the Contact name
 *  @param int $id -Contact id
 * return string $message -Contact name returned
 */

function get_contact_name($contactid)
{
	global $adb;
	$contact_name = '';
	if($contactid != '')
	{
        	$sql = "select * from vtiger_contactdetails where contactid=?";
        	$result = $adb->pquery($sql, array($contactid));
        	$firstname = $adb->query_result($result,0,"firstname");
        	$lastname = $adb->query_result($result,0,"lastname");
        	$contact_name = $firstname." ".$lastname;
        	return $contact_name;
	}

}
/**     function used to get the Account id
	*  *      @param int $id - Contact id
	*   *      return string $message - Account id returned
	*    */

function get_check_account_id($id)
{
	 global $adb;
	 $res = $adb->pquery("select * from vtiger_contactdetails where contactid=".$id);
	 $accountid=$adb->query_result($res,0,'accountid');
	 return $accountid;
}


/**	function used to get the Account Information
 *	@param int $id - account id
 *	return string $message - Account informations will be returned from :Accountdetails table
 */
function get_account_detail($id,$block,$contactid,$sessionid)
{
	global $adb,$log;
	$log->debug("Entering the function account_details($id - $block - $contactid)");
	$customer_account_id = get_check_account_id($contactid);
	if ($id != $customer_account_id) {
		return array("#NOT AUTHORIZED#");
	}
	if(!validateSession($contactid,$sessionid))
		return null;
		
	if($block == 'ACCINFORMATION')
		$fields_list = "'accountname','phone','website','fax','tickersymbol','parentid','email1','smownerid'";
	elseif($block =='ACCADDRESSINFORMATION')
		$fields_list ="'bill_street','ship_street','bill_pobox','ship_pobox','bill_city','ship_city','bill_state','ship_state','bill_code','ship_code','bill_country','ship_country'";
	elseif($block =='ACCDESCINFO')
		$fields_list ="'description'";
		
	$fieldquery = "select fieldname, columnname, fieldlabel from vtiger_field where tabid=6 and columnname in ($fields_list) order by sequence";
	$fieldres = $adb->pquery($fieldquery);
	$nooffields = $adb->num_rows($fieldres);

	$query = "select 
		vtiger_account.*,vtiger_accountbillads.*,vtiger_accountshipads.*,vtiger_crmentity.*,vtiger_accountscf.* 
		from vtiger_account 
		inner join vtiger_accountbillads on vtiger_accountbillads.accountaddressid=vtiger_account.accountid 
		inner join vtiger_accountshipads on vtiger_accountshipads.accountaddressid = vtiger_account.accountid 
		inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_account.accountid 
		inner join vtiger_accountscf on vtiger_accountscf.accountid =vtiger_account.accountid 
		where vtiger_account.accountid=".$id;
	$res = $adb->pquery($query);

	for($i=0;$i<$nooffields;$i++)
	{
		$fieldname  = $adb->query_result($fieldres,$i,'columnname');
		$fieldlabel = $adb->query_result($fieldres,$i,'fieldlabel');
		$fieldvalue = $adb->query_result($res,0,$fieldname);
		if($block=='ACCINFORMATION' && $fieldname == 'smownerid')
		{
			$temp_fieldvalue = get_ownername($id,"Accounts");
			$fieldlabel ='';
			$fieldvalue ='';
			$output[0]['assigninfo'] = $temp_fieldvalue[0]['assigninfo'];
		}
		if($block=='ACCINFORMATION' && $fieldname == 'parentid' && $fieldvalue != '')
			$fieldvalue = get_account_name($fieldvalue);
		$output[0][$block][$i]['fieldlabel'] = $fieldlabel;
		$output[0][$block][$i]['fieldvalue'] = $fieldvalue;
	}
       	return $output;
}

/**	function used to get the Account Information
 *	@param int $id - account id
 *	return string $message - Account informations will be returned from :Accountdetails table
 */
function get_product_detail($id,$block,$customerid,$sessionid)
{
	global $adb, $log;
	
	$isPermitted = check_permission($customerid,'Products',$id);
	if($isPermitted == false) {
		return array("#NOT AUTHORIZED#");
	}
	if(!validateSession($customerid,$sessionid))
		return null;
		
	if($block == 'PROINFORMATION')
		$fields_list = "'productname','productcode','discontinued','manufacturer','productcategory','sales_start_date','sales_end_date','start_date','expiry_date','website','vendor_id','mfr_part_no','vendor_part_no','serialno','productsheet','glacct'";
	elseif($block =='PRODESCINFO')
		$fields_list ="'description'";
	
	$fieldquery = "select fieldname, columnname, fieldlabel from vtiger_field where tabid=14 and columnname in ($fields_list) order by sequence";
	$fieldres = $adb->pquery($fieldquery);
	$nooffields = $adb->num_rows($fieldres);

	$query = "select 
		vtiger_products.*,vtiger_crmentity.* 
		from vtiger_products 
		inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_products.productid 
		where vtiger_products.productid=".$id;
	
	$res = $adb->pquery($query);

	for($i=0;$i<$nooffields;$i++)
	{
		$fieldname = $adb->query_result($fieldres,$i,'columnname');
		$output[0][$block][$i]['fieldlabel'] = $adb->query_result($fieldres,$i,'fieldlabel');
		$fieldvalue = $adb->query_result($res,0,$fieldname);
		if($block=='PROINFORMATION' && $fieldname == 'vendor_id' && $fieldvalue !='')
		{
			$fieldvalue = get_vendor_name($fieldvalue);
		}
		if($block=='PROINFORMATION' && $fieldname == 'discontinued')
		{
			if($fieldvalue == "1")
				$fieldvalue = "yes";
			else 
				$fieldvalue = "No";
		}
		$output[0][$block][$i]['fieldvalue'] = $fieldvalue;
	}
        $adb->println($output);	
	return $output;
}


/**	function used to get the vendor name
 *	@param int $id - vendor id
 *	return string $name - Vendor name returned
 */

function get_vendor_name($vendorid)
{
 	global $adb;
 	$res = $adb->pquery("select * from vtiger_vendor where vendorid=".$vendorid);
 	$name=$adb->query_result($res,0,'vendorname');
 	return $name;	 
}

/**	function used to get the Image url
 *	@param int $id - id
 *	return string $url - Image url returned
 */

function get_image_url($id,$module,$customerid)
{	
	global $adb, $log;
	global $site_URL;
	
	$isPermitted = check_permission($customerid,$module,$id);
	if($isPermitted == false) {
		return array("#NOT AUTHORIZED#");
	}
	
	$query ='select vtiger_attachments.*,vtiger_seattachmentsrel.* from vtiger_attachments inner join vtiger_seattachmentsrel on vtiger_seattachmentsrel.attachmentsid =vtiger_attachments.attachmentsid where vtiger_seattachmentsrel.crmid ='.$id; 
	$res = $adb->pquery($query);
	$noofdata = $adb->num_rows($res);
	for($i=0;$i<$noofdata;$i++)
	{
 		$filename=$adb->query_result($res,$i,'name');
 		$path=$adb->query_result($res,$i,'path');
 		$attachmentid=$adb->query_result($res,$i,'attachmentsid');
        	$url[0][$module][$i]['fieldlabel']="Image $i";
        	$url[0][$module][$i]['fieldvalue']=$site_URL.'/'.$path.$attachmentid.'_'.$filename;
	}

	if($noofdata == '')
	{	
        	$url[0][$module][0]['fieldlabel']="Image $i";
        	$url[0][$module][0]['fieldvalue']='';
	}
	$adb->println($url);	

 return $url;	 
}

/**	function used to get the Quotes/Invoice List
 *	@param int $id - id -Contactid
 *	return string $output - Quotes/Invoice list Array 
 */

function get_list_values($id,$block,$sessionid,$only_mine='true')
{
	global $adb,$log;
	$Allowed = api_permission($block);
	if($Allowed == 'false')
		return 'false';
		
		if(!validateSession($id,$sessionid))
		return null; 
		
	$entity_ids_list = array();
	$show_all=show_all($block);
	if($only_mine == 'true' || $show_all == 'false') 
	{
		array_push($entity_ids_list,$id);
	} 
	else 
	{
		$contactquery = "SELECT contactid, accountid FROM vtiger_contactdetails " .
			" INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_contactdetails.contactid" .
			" AND vtiger_crmentity.deleted = 0 " .
			" WHERE (accountid = (SELECT accountid FROM vtiger_contactdetails WHERE contactid = ?)  AND accountid != 0) OR contactid = ?";
		$contactres = $adb->pquery($contactquery, array($id,$id));
		$no_of_cont = $adb->num_rows($contactres);
		for($i=0;$i<$no_of_cont;$i++)
		{
			$cont_id = $adb->query_result($contactres,$i,'contactid');
			$acc_id = $adb->query_result($contactres,$i,'accountid');
			if(!in_array($cont_id, $entity_ids_list))
				$entity_ids_list[] = $cont_id;
			if(!in_array($acc_id, $entity_ids_list) && $acc_id != '0')
				$entity_ids_list[] = $acc_id;
		}
	}
	if($block == 'Quotes')
	{		
		$fields_list = array(
			'quoteid' => 'Quote Id',
			'subject' => 'Subject',
			'quote_no' => 'Quote No',
			'quotestage' => 'Quote Stage',
			'validtill' => 'Valid Till',
			'total' => 'Total',
			'entityid' => 'Related To'
		);
		
		$query = "select distinct vtiger_quotes.*,  
				case when vtiger_quotes.contactid is not null then vtiger_quotes.contactid else vtiger_quotes.accountid end as entityid,
				case when vtiger_quotes.contactid is not null then 'Contacts' else 'Accounts' end as setype
				from vtiger_quotes left join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_quotes.quoteid 
				where vtiger_crmentity.deleted=0 and (accountid in  (". generateQuestionMarks($entity_ids_list) .") or contactid in (". generateQuestionMarks($entity_ids_list) ."))";		
		$params = array($entity_ids_list,$entity_ids_list);
	}
	else if($block == 'Invoice')
	{	
		$fields_list = array(
			'invoiceid' => 'Invoice Id',
			'invoice_no' => 'Invoice No',
			'subject' => 'Subject',
			'salesorderid' => 'Salerorder',
			'invoicestatus' => 'Invoice Status',
			'total'=>'Total',
			'entityid' => 'Related To'
		);
		$query ="select distinct vtiger_invoice.*,  
				case when vtiger_invoice.contactid !=0 then vtiger_invoice.contactid else vtiger_invoice.accountid end as entityid,
				case when vtiger_invoice.contactid !=0 then 'Contacts' else 'Accounts' end as setype
				from vtiger_invoice 
				left join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_invoice.invoiceid 
				where vtiger_crmentity.deleted=0 and (accountid in (". generateQuestionMarks($entity_ids_list) .") or contactid in  (". generateQuestionMarks($entity_ids_list) ."))";
		$params = array($entity_ids_list,$entity_ids_list);
	}
	else if ($block == 'Documents')
	{
		$fields_list = array(
			'title' => 'Title',
			'filename' => 'FileName',
			'createdtime' => 'Created Time',
			'modifiedtime'=>'Modified Time',
			'entityid' => 'Related To'
		);
		$query ="select vtiger_notes.*, vtiger_crmentity.*, vtiger_senotesrel.crmid as entityid, '' as setype from vtiger_notes " .
				"inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_notes.notesid " .
				"left join vtiger_senotesrel on vtiger_senotesrel.notesid=vtiger_notes.notesid " .
				"where vtiger_crmentity.deleted = 0 and  vtiger_senotesrel.crmid in (".generateQuestionMarks($entity_ids_list).")"; 
		$params = array($entity_ids_list);
	}
	
	$res = $adb->pquery($query,$params);
	$noofdata = $adb->num_rows($res);
	for( $j= 0;$j < $noofdata; $j++)
	{
		$i=0;
		foreach($fields_list as $fieldname => $fieldlabel) {
			$output[0][$block]['head'][0][$i]['fielddata'] = $fieldlabel; //$adb->query_result($fieldres,$i,'fieldlabel');
			$fieldvalue = $adb->query_result($res,$j,$fieldname);
						
			if($block == 'Quotes' && $fieldname =='subject') 
			{
				$fieldid = $adb->query_result($res,$j,'quoteid');
				$filename = $fieldid.'_Quotes.pdf';
				$fieldvalue = '<a href="index.php?&module=Quotes&action=index&id='.$fieldid.'">'.$fieldvalue.'</a>';
			}
			if($block == 'Invoice' && $fieldname =='subject') 
			{
				$fieldid = $adb->query_result($res,$j,'invoiceid');
				$filename = $fieldid.'_Invoice.pdf';
				$fieldvalue = '<a href="index.php?&module=Invoice&action=index&status=true&id='.$fieldid.'">'.$fieldvalue.'</a>';
			}
			if($block == 'Documents' && $fieldname =='title') 
			{
				$fieldid = $adb->query_result($res,$j,'notesid');
				$filename = $fieldvalue;
				$fieldvalue = '<a href="index.php?&module=Documents&action=index&id='.$fieldid.'">'.$fieldvalue.'</a>';
			}			
			if($block == 'Invoice' && $fieldname == 'salesorderid')
			{
				if($fieldvalue != '')
				$fieldvalue = get_salesorder_name($fieldvalue);				
			}	
			if($block == 'Documents' && $fieldname =='filename') 
			{
				$fieldid = $adb->query_result($res,$j,'notesid');
				$filename = $fieldvalue;
				$folderid = $adb->query_result($res,$j,'folderid');
				$fieldvalue = '<a href="index.php?&downloadfile=true&folderid='.$folderid.'&filename='.$filename.'&module=Documents&action=index&id='.$fieldid.'">'.$fieldvalue.'</a>';
			}
			if($fieldname == 'entityid') {
				$crmid = $fieldvalue; 
				$module = $adb->query_result($res,$j,'setype');
				if ($module == '') {
					$module = getSalesEntityType($crmid);
				}
				if ($crmid != '' && $module != '') {
					$fieldvalues = getEntityName($module, array($crmid));
					if($module == 'Contacts')
					$fieldvalue = '<a href="index.php?module=Contacts&action=index&customer_id='.$crmid.'">'.$fieldvalues[$crmid].'</a>';
					elseif($module == 'Accounts')
					$fieldvalue = '<a href="index.php?module=Accounts&action=index&id='.$crmid.'">'.$fieldvalues[$crmid].'</a>';
				} else {
					$fieldvalue = '';
				}
			}
			$output[1][$block]['data'][$j][$i]['fielddata'] = $fieldvalue;
			$i++;
		}
	}
	
	$adb->println("out".$output,true);	

 return $output;	 
	
}


/**	function used to get the contents of a file
 *	@param int $id - customer ie., id 
 *	return $filecontents array with single file contents like [fileid] => filecontent
 */
function get_filecontent_detail($id,$folderid,$block,$customerid,$sessionid)
{
	global $adb,$log;
	global $site_URL;
		
	$isPermitted = check_permission($customerid,$block,$id);
	if($isPermitted == false) {
		return array("#NOT AUTHORIZED#");
	}
	
	if(!validateSession($customerid,$sessionid))
		return null; 
		
	if($block == 'Documents')
	{
		$query="SELECT * FROM vtiger_notes WHERE notesid ='$id' and folderid= '$folderid'";
		$res = $adb->pquery($query);
		$fileType = @$adb->query_result($res, 0, "filetype");
		$filename = $adb->query_result($res, 0, "filename");
		$filepath = $adb->query_result($res, 0, "filepath");
		$fileid = $id; 
		$saved_filename = $fileid."_".$folderid."_".$filename;
		$filesize = filesize($filepath.$fileid."_".$filename);
		$filenamewithpath=$filepath.$fileid.'_'.$folderid.'_'.$filename;
	}
	else
	{
		$query ='select vtiger_attachments.*,vtiger_seattachmentsrel.* from vtiger_attachments inner join vtiger_seattachmentsrel on vtiger_seattachmentsrel.attachmentsid=vtiger_attachments.attachmentsid where vtiger_seattachmentsrel.crmid ='.$id; 
		
		$res = $adb->pquery($query);
		
		$filename = $adb->query_result($res,0,'name');
		$filepath = $adb->query_result($res,0,'path');
		$fileid = $adb->query_result($res,0,'attachmentsid');
		$filesize = filesize($filepath.$fileid."_".$filename);
		$filetype = $adb->query_result($res,0,'type');
		$filenamewithpath=$filepath.$fileid.'_'.$filename;
	
	}
	$output[0]['fileid'] = $fileid;
	$output[0]['filename'] = $filename;
	$output[0]['filetype'] = $filetype;
	$output[0]['filesize'] = $filesize;
	$output[0]['filecontents']= base64_encode(file_get_contents($filenamewithpath));
	return $output;
}
/**	function used to get the Quotes/Invoice pdf
 *	@param int $id - id -id
 *	return string $output - pd link value
 */

function get_pdf($id,$block,$customerid,$sessionid)
{
	global $adb;
	global $current_user,$log;
	global $currentModule,$mod_strings,$app_strings,$app_list_strings;
			
	$isPermitted = check_permission($customerid,$block,$id);
	if($isPermitted == false) {
		return array("#NOT AUTHORIZED#");
	}
	
	if(!validateSession($customerid,$sessionid))
		return null; 
		
	require_once("modules/Users/Users.php");
	require_once("config.inc.php");
	$seed_user=new Users();
	$user_id=$seed_user->retrieve_user_id('admin');
	$current_user=$seed_user;
	$current_user->retrieve_entity_info($user_id, 'Users');
	$currentModule = $block;
	$current_language = $default_language;
	$app_strings = return_application_language($current_language);
	$app_list_strings = return_app_list_strings_language($current_language);
	$mod_strings = return_module_language($current_language, $currentModule);
//	$adb->println("inside get_pdf".$id."block".$block);

	$_REQUEST['record']= $id;
	$_REQUEST['savemode']= 'file';
	$filenamewithpath='test/product/'.$id.'_'.$block.'.pdf';
	if (file_exists($filenamewithpath) && (filesize($filenamewithpath) != 0)) 
		unlink($filenamewithpath);
	
	include("modules/$block/CreatePDF.php");
	
	if (file_exists($filenamewithpath) && (filesize($filenamewithpath) != 0)) 
	{
		//$output[0][$block][0]['fielddata']='Success';
		//we have to pass the file content
		$filecontents[] = base64_encode(file_get_contents($filenamewithpath));
		unlink($filenamewithpath);
		// TODO: Delete the file to avoid public access.

	//	exit();
	}
	else
	{
		//$output[0][$block][0]['fielddata']='Failed';
		$filecontents = "failure";
	}

	//$adb->println($output);	
	return $filecontents;	 
}

/**	function used to get the salesorder name
 *	@param int $id -  id
 *	return string $name - Salesorder name returned
 */

function get_salesorder_name($id)
{
 	global $adb;
 	$res = $adb->pquery(" select * from vtiger_salesorder where salesorderid=".$id);
 	$name=$adb->query_result($res,0,'subject');
 	return $name;	 
}

function get_invoice_detail($id,$block,$customerid,$sessionid)
{

	global $adb,$site_URL;
		
	$isPermitted = check_permission($customerid,'Invoice',$id);
	if($isPermitted == false) {
		return array("#NOT AUTHORIZED#");
	}
	
	if(!validateSession($customerid,$sessionid))
		return null; 
		
	if($block == 'INVINFORMATION')
		$fields_list ="'subject','invoicedate','duedate','total','createdtime','invoicestatus','salesorderid','customerno','contactid','accountid','purchaseorder','salescommission'";
	$fieldquery = "select fieldname, columnname, fieldlabel from vtiger_field where tabid=23 and columnname in ($fields_list) order by sequence";
	$fieldres = $adb->pquery($fieldquery);
	$nooffields = $adb->num_rows($fieldres);

	$query = "select 
		vtiger_invoice.*,vtiger_crmentity.* 
		from vtiger_invoice 
		inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_invoice.invoiceid 
		where vtiger_invoice.invoiceid=".$id;
	
	$res = $adb->pquery($query);

	for($i=0;$i<$nooffields;$i++)
	{
		$fieldname = $adb->query_result($fieldres,$i,'columnname');
		$fieldlabel = $adb->query_result($fieldres,$i,'fieldlabel');
		$fieldvalue = $adb->query_result($res,0,$fieldname);
		if($block=='INVINFORMATION' && $fieldname == 'subject' && $fieldvalue !='')
		{
			$fieldid = $adb->query_result($res,0,'invoiceid');
			//$filename = $fieldid.'_Invoice.pdf';
			$fieldlabel = "(Download PDF)  ".$fieldlabel;
			$fieldvalue = '<a href="index.php?downloadfile=true&module=Invoice&action=index&id='.$fieldid.'">'.$fieldvalue.'</a>';
		}
		if( $fieldname == 'salesorderid' || $fieldname == 'contactid' || $fieldname == 'accountid' || $fieldname == 'potentialid')
		{
			$crmid = $fieldvalue; 
			$module = getSalesEntityType($crmid);
			if ($crmid != '' && $module != '') {
				$fieldvalues = getEntityName($module, array($crmid));
				if($module == 'Contacts')
					$fieldvalue = '<a href="index.php?module=Contacts&action=index&customer_id='.$crmid.'">'.$fieldvalues[$crmid].'</a>';
				elseif($module == 'Accounts')
					$fieldvalue = '<a href="index.php?module=Accounts&action=index&id='.$crmid.'">'.$fieldvalues[$crmid].'</a>';
				else
					$fieldvalue = $fieldvalues[$crmid];
			} else {
				$fieldvalue = '';
			}
		}
		$output[0][$block][$i]['fieldlabel'] = $fieldlabel;//adb->query_result($fieldres,$i,'fieldlabel');
		$output[0][$block][$i]['fieldvalue'] = $fieldvalue;
	}
        $adb->println($output);	
	return $output;
}

//to get contactid's accountproduct details'

function get_product_list_values($id,$modulename,$sessionid,$only_mine='true')
{
	global $adb,$log;
	$entity_ids_list = array();
	$Allowed = api_permission($modulename);
	if($Allowed == 'false')
		return 'false';
		$show_all=show_all($modulename);
		
		if(!validateSession($id,$sessionid))
		return null; 
		
		if($only_mine == 'true' || $show_all == 'false') 
		{
			array_push($entity_ids_list,$id);
		} 
		else 
		{
				$contactquery = "SELECT contactid, accountid FROM vtiger_contactdetails " .
				" INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_contactdetails.contactid" .
				" AND vtiger_crmentity.deleted = 0 " .
				" WHERE (accountid = (SELECT accountid FROM vtiger_contactdetails WHERE contactid = ?)  AND accountid != 0) OR contactid = ?";
				$contactres = $adb->pquery($contactquery, array($id,$id));
				$no_of_cont = $adb->num_rows($contactres);
				for($i=0;$i<$no_of_cont;$i++)
				{
					$cont_id = $adb->query_result($contactres,$i,'contactid');
					$acc_id = $adb->query_result($contactres,$i,'accountid');
					if(!in_array($cont_id, $entity_ids_list))
						$entity_ids_list[] = $cont_id;
					if(!in_array($acc_id, $entity_ids_list) && $acc_id != '0')
						$entity_ids_list[] = $acc_id;
				}
	}
	$log->debug("allcontact".print_r($entity_ids_list,true));
	$fields_list = array(
		'productid' => 'Product Id',
		'productname' => 'Product Name',
		'productcode' => 'Product Code',
		'qty_per_unit' => 'Qty/Unit',
		'unit_price' => 'Unit Price',
		'entityid' => 'Related To'
	);
	
	$query = array();
	$params = array();
	
	$query[0] = "SELECT vtiger_products.*,vtiger_seproductsrel.crmid as entityid, vtiger_seproductsrel.setype FROM vtiger_seproductsrel  
				left join vtiger_crmentity on vtiger_seproductsrel.crmid=vtiger_crmentity.crmid 
				left join vtiger_products on vtiger_products.productid=vtiger_seproductsrel.productid  					
				where vtiger_seproductsrel.crmid in (". generateQuestionMarks($entity_ids_list).") and vtiger_crmentity.deleted = 0 ";
	$params[0] = array($entity_ids_list);
							
	$query[1] = "select distinct vtiger_products.*,  
				case when vtiger_quotes.contactid is not null then vtiger_quotes.contactid else vtiger_quotes.accountid end as entityid,
				case when vtiger_quotes.contactid is not null then 'Contacts' else 'Accounts' end as setype
				from vtiger_quotes left join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_quotes.quoteid 
				left join vtiger_inventoryproductrel on vtiger_inventoryproductrel.id=vtiger_quotes.quoteid
				left join vtiger_products on vtiger_products.productid = vtiger_inventoryproductrel.productid 
				where vtiger_crmentity.deleted=0 and (accountid in  (". generateQuestionMarks($entity_ids_list) .") or contactid in (". generateQuestionMarks($entity_ids_list) ."))";		
	$params[1] = array($entity_ids_list,$entity_ids_list);
	
	
	$query[2] = "select distinct vtiger_products.*,  
				case when vtiger_invoice.contactid !=0 then vtiger_invoice.contactid else vtiger_invoice.accountid end as entityid,
				case when vtiger_invoice.contactid !=0 then 'Contacts' else 'Accounts' end as setype
				from vtiger_invoice 
				left join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_invoice.invoiceid 
				left join vtiger_inventoryproductrel on vtiger_inventoryproductrel.id=vtiger_invoice.invoiceid
				left join vtiger_products on vtiger_products.productid = vtiger_inventoryproductrel.productid 
				where vtiger_crmentity.deleted=0 and (accountid in (". generateQuestionMarks($entity_ids_list) .") or contactid in  (". generateQuestionMarks($entity_ids_list) ."))";
		$params[2] = array($entity_ids_list,$entity_ids_list);
		
		for($k=0;$k<count($query);$k++)
		{
			$res[$k] = $adb->pquery($query[$k],$params[$k]);
			$noofdata[$k] = $adb->num_rows($res[$k]);			
			if($noofdata[$k] == 0)
   				$output[$k][$modulename]['data'] = '';
			for( $j= 0;$j < $noofdata[$k]; $j++)
			{
				$i=0;
				foreach($fields_list as $fieldname => $fieldlabel) {
					$output[$k][$modulename]['head'][0][$i]['fielddata'] = $fieldlabel; 
					$fieldvalue = $adb->query_result($res[$k],$j,$fieldname);
					$fieldid = $adb->query_result($res[$k],$j,'productid');
					
					if($fieldname == 'entityid') {
						$crmid = $fieldvalue; 
						$module = $adb->query_result($res[$k],$j,'setype');
						if ($crmid != '' && $module != '') {
							$fieldvalues = getEntityName($module, array($crmid));
							if($module == 'Contacts')
							$fieldvalue = '<a href="index.php?module=Contacts&action=index&customer_id='.$crmid.'">'.$fieldvalues[$crmid].'</a>';
							elseif($module == 'Accounts')
							$fieldvalue = '<a href="index.php?module=Accounts&action=index&id='.$crmid.'">'.$fieldvalues[$crmid].'</a>';
						} else {
							$fieldvalue = '';
						}
					}	

					if($fieldname == 'productname')
					$fieldvalue = '<a href="index.php?module=Products&action=index&productid='.$fieldid.'">'.$fieldvalue.'</a>';
					$output[$k][$modulename]['data'][$j][$i]['fielddata'] = $fieldvalue;
					$i++;
				}
			}
		}
 	$log->debug("Exiting function get_product_list_values.....");
 	return $output;	
}

/*function used to get details of tickets ,quotes and documents
 *	@param int $id - id of quotes or invoice or notes
 *	return string $message - Account informations will be returned from :Accountdetails table
 */
function get_details($id,$block,$customerid,$sessionid)
{
	global $adb,$log;
	$log->debug("Entering get_details($id,$block,$customerid) function..");
	$isPermitted = check_permission($customerid,$block,$id);
	if($isPermitted == false) {
		return array("#NOT AUTHORIZED#");
	}
	
		if(!validateSession($customerid,$sessionid))
		return null; 
		
	if($block == "Quotes")
	{
		$fields_list_arr = array('quote_no','subject','potentialid','quotestage','validtill','contactid','carrier','shipping','accountid','subtotal','total','taxtype','discount_percent','discount_amount');

		$fieldquery = "select fieldname, columnname, fieldlabel from vtiger_field where tabid=20 and columnname in (". generateQuestionMarks($fields_list_arr) .") order by sequence";
		$fieldparams = array($fields_list_arr);
		$query =  "select 
					vtiger_quotes.*,vtiger_crmentity.* 
					from vtiger_quotes
					inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_quotes.quoteid 
					where vtiger_quotes.quoteid=(". generateQuestionMarks($id) .")";
		
	}
	else if($block == "Documents")
	{
		$fields_list_arr = array('notesid','title','filename','notecontent','filetype','filesize','filelocationtype','fileversion','filestatus','os','createdtime','modifiedtime');
		$fieldquery = "select fieldname, columnname, fieldlabel from vtiger_field where tabid=8 and columnname in (". generateQuestionMarks($fields_list_arr) .") order by sequence";
		$fieldparams = array($fields_list_arr);
		$query =  "select 
					vtiger_notes.*,vtiger_crmentity.* 
					from vtiger_notes
					inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_notes.notesid 
					where vtiger_notes.notesid=(". generateQuestionMarks($id) .") and vtiger_crmentity.deleted!=1";
	
	}
	else if($block == "Tickets")
	{
		$fields_list_arr = array('parent_id','priority','product_id','severity','status','category','update_log','title','solution','modifiedtime','createdtime');
		$fieldquery = "select fieldname, columnname, fieldlabel from vtiger_field where tabid=13 and columnname in (". generateQuestionMarks($fields_list_arr) .") order by sequence";
		$fieldparams = array($fields_list_arr);	
		$query ="select 
					vtiger_troubletickets.*,vtiger_crmentity.modifiedtime,vtiger_crmentity.createdtime 
					from vtiger_troubletickets
					inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_troubletickets.ticketid 
					where (vtiger_troubletickets.ticketid=(". generateQuestionMarks($id) .") and vtiger_crmentity.deleted=0)";
	}
	$params = array($id);
	
	$fieldres = $adb->pquery($fieldquery,$fieldparams);
	$nooffields = $adb->num_rows($fieldres);
		
	$res = $adb->pquery($query,$params);
	for($i=0;$i<$nooffields;$i++)
	{
		$columnname = $adb->query_result($fieldres,$i,'columnname');
		$fieldname = $adb->query_result($fieldres,$i,'fieldname');
		$fieldid = $adb->query_result($fieldres,$i,'contactid');
		
		$output[0][$block][$i]['fieldlabel'] = $adb->query_result($fieldres,$i,'fieldlabel');
		
		$fieldvalue = $adb->query_result($res,0,$columnname);
		
		if($columnname == 'parent_id' || $columnname == 'contactid' || $columnname == 'accountid' || $columnname == 'potentialid')
		{
			$crmid = $fieldvalue; 
			$module = getSalesEntityType($crmid);
			if ($crmid != '' && $module != '') {
				$fieldvalues = getEntityName($module, array($crmid));
				if($module == 'Contacts')
					$fieldvalue = '<a href="index.php?module=Contacts&action=index&customer_id='.$crmid.'">'.$fieldvalues[$crmid].'</a>';
				elseif($module == 'Accounts')
					$fieldvalue = '<a href="index.php?module=Accounts&action=index&id='.$crmid.'">'.$fieldvalues[$crmid].'</a>';
				else
					$fieldvalue = $fieldvalues[$crmid];
			} else {
				$fieldvalue = '';
			}
		}
		if($columnname == 'product_id') {
			$fieldvalues = getEntityName('Products', array($fieldvalue));
			$fieldvalue = '<a href="index.php?module=Products&action=index&productid='.$fieldvalue.'">'.$fieldvalues[$fieldvalue].'</a>';
		}
		if($block=='Quotes' && $fieldname == 'subject' && $fieldvalue !='')
		{
			$fieldid = $adb->query_result($res,0,'quoteid');
			$output[0][$block][$i]['fieldlabel']= "(Download PDF)  ".$adb->query_result($fieldres,$i,'fieldlabel');
			$fieldvalue = '<a href="index.php?downloadfile=true&module=Quotes&action=index&id='.$fieldid.'">'.$fieldvalue.'</a>';
		}
		if($block == 'Documents' && $fieldname == 'filename')
		{
			$fieldid = $adb->query_result($res,0,'notesid');
			$filename = $fieldvalue;
			$folderid = $adb->query_result($res,0,'folderid');
			$fieldvalue = '<a href="index.php?&downloadfile=true&folderid='.$folderid.'&filename='.$filename.'&module=Documents&action=index&id='.$fieldid.'">'.$fieldvalue.'</a>';
		}
		$output[0][$block][$i]['fieldvalue'] = $fieldvalue;
	}
	return $output;
}

function check_permission($customerid, $module, $entityid) {
	global $adb,$log;
	$Allowed = api_permission($module);
	if($Allowed == 'false')
		return 'false';
		$show_all= show_all($module);
		if($show_all == 'false')
		$allowed_contacts_and_accounts=$customerid;
		else {
		$allowed_contacts_and_accounts = array();
		$contactquery = "SELECT contactid, accountid FROM vtiger_contactdetails " .
				" INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_contactdetails.contactid" .
				" AND vtiger_crmentity.deleted = 0 " .
				" WHERE (accountid = (SELECT accountid FROM vtiger_contactdetails WHERE contactid = ?) AND accountid != 0) OR contactid = ?";
		$contactres = $adb->pquery($contactquery, array($customerid,$customerid));
		$no_of_cont = $adb->num_rows($contactres);
			for($i=0;$i<$no_of_cont;$i++)
			{
				$cont_id = $adb->query_result($contactres,$i,'contactid');
				$acc_id = $adb->query_result($contactres,$i,'accountid');
				if(!in_array($cont_id, $allowed_contacts_and_accounts))
					$allowed_contacts_and_accounts[] = $cont_id;
				if(!in_array($acc_id, $allowed_contacts_and_accounts) && $acc_id != '0')
					$allowed_contacts_and_accounts[] = $acc_id;
			}
	}
	if(in_array($entityid, $allowed_contacts_and_accounts)) {
		return true;
	}
	
	$faqquery = "select id from vtiger_faq";
	$faqids = $adb->pquery($faqquery,array());
	$no_of_faq = $adb->num_rows($faqids);
	for($i=0;$i<$no_of_faq;$i++)
	{
		$faq_id[] = $adb->query_result($faqids,$i,'id');
	}
	switch($module) {
		case 'Products'	: 	$query = "SELECT vtiger_seproductsrel.productid FROM vtiger_seproductsrel  
									INNER JOIN vtiger_crmentity 
									ON vtiger_seproductsrel.productid=vtiger_crmentity.crmid 					
									WHERE vtiger_seproductsrel.crmid IN (". generateQuestionMarks($allowed_contacts_and_accounts).")
										AND vtiger_crmentity.deleted=0
										AND vtiger_seproductsrel.productid = ?";
							$res = $adb->pquery($query, array($allowed_contacts_and_accounts, $entityid));
							if ($adb->num_rows($res) > 0) {
								return true;
							}
							$query = "SELECT vtiger_inventoryproductrel.productid, vtiger_inventoryproductrel.id
									FROM vtiger_inventoryproductrel   
									INNER JOIN vtiger_crmentity 
									ON vtiger_inventoryproductrel.productid=vtiger_crmentity.crmid 					
									LEFT JOIN vtiger_quotes
									ON vtiger_inventoryproductrel.id = vtiger_quotes.quoteid 													
									WHERE vtiger_crmentity.deleted=0 
										AND (vtiger_quotes.contactid IN (". generateQuestionMarks($allowed_contacts_and_accounts).") or vtiger_quotes.accountid IN (".generateQuestionMarks($allowed_contacts_and_accounts)."))
										AND vtiger_inventoryproductrel.productid = ?";
							$res = $adb->pquery($query, array($allowed_contacts_and_accounts, $allowed_contacts_and_accounts, $entityid));
							if ($adb->num_rows($res) > 0) {
								return true;
							}
							$query = "SELECT vtiger_inventoryproductrel.productid, vtiger_inventoryproductrel.id
									FROM vtiger_inventoryproductrel   
									INNER JOIN vtiger_crmentity 
									ON vtiger_inventoryproductrel.productid=vtiger_crmentity.crmid 					
									LEFT JOIN vtiger_invoice
									ON vtiger_inventoryproductrel.id = vtiger_invoice.invoiceid 													
									WHERE vtiger_crmentity.deleted=0 
										AND (vtiger_invoice.contactid IN (". generateQuestionMarks($allowed_contacts_and_accounts).") or vtiger_invoice.accountid IN (".generateQuestionMarks($allowed_contacts_and_accounts)."))
										AND vtiger_inventoryproductrel.productid = ?";
							$res = $adb->pquery($query, array($allowed_contacts_and_accounts, $allowed_contacts_and_accounts, $entityid));
							if ($adb->num_rows($res) > 0) {
								return true;
							}
							break;
							
		case 'Quotes'	:	$query = "SELECT vtiger_quotes.quoteid
									FROM vtiger_quotes   
									INNER JOIN vtiger_crmentity 
									ON vtiger_quotes.quoteid=vtiger_crmentity.crmid  													
									WHERE vtiger_crmentity.deleted=0 
										AND (vtiger_quotes.contactid IN (". generateQuestionMarks($allowed_contacts_and_accounts).") or vtiger_quotes.accountid IN (".generateQuestionMarks($allowed_contacts_and_accounts)."))
										AND vtiger_quotes.quoteid = ?";
							$res = $adb->pquery($query, array($allowed_contacts_and_accounts, $allowed_contacts_and_accounts, $entityid));
							if ($adb->num_rows($res) > 0) {
								return true;
							}
							break;
							
		case 'Invoice'	:	$query = "SELECT vtiger_invoice.invoiceid
									FROM vtiger_invoice   
									INNER JOIN vtiger_crmentity 
									ON vtiger_invoice.invoiceid=vtiger_crmentity.crmid  													
									WHERE vtiger_crmentity.deleted=0 
										AND (vtiger_invoice.contactid IN (". generateQuestionMarks($allowed_contacts_and_accounts).") or vtiger_invoice.accountid IN (".generateQuestionMarks($allowed_contacts_and_accounts)."))
										AND vtiger_invoice.invoiceid = ?";
							$res = $adb->pquery($query, array($allowed_contacts_and_accounts, $allowed_contacts_and_accounts, $entityid));
							if ($adb->num_rows($res) > 0) {
								return true;
							}
							break;
							
		case 'Documents'	: 	$query = "SELECT vtiger_senotesrel.notesid FROM vtiger_senotesrel
										INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_senotesrel.notesid AND vtiger_crmentity.deleted = 0
										WHERE vtiger_senotesrel.crmid IN (". generateQuestionMarks($allowed_contacts_and_accounts) .")
										AND vtiger_senotesrel.notesid = ?";
							$res = $adb->pquery($query, array($allowed_contacts_and_accounts, $entityid));
							if ($adb->num_rows($res) > 0) {
								return true;
							}
								$query = "SELECT vtiger_senotesrel.notesid FROM vtiger_senotesrel
										INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_senotesrel.notesid AND vtiger_crmentity.deleted = 0
										WHERE vtiger_senotesrel.crmid IN (". generateQuestionMarks($faq_id) .")
										AND vtiger_senotesrel.notesid = ?";
								$res = $adb->pquery($query, array($faq_id,$entityid));	
								if ($adb->num_rows($res) > 0) {
								return true;
							}		
							break;
							
		case 'Tickets'	:	$query = "SELECT vtiger_troubletickets.ticketid FROM vtiger_troubletickets
										INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_troubletickets.ticketid AND vtiger_crmentity.deleted = 0
										WHERE vtiger_troubletickets.parent_id IN (". generateQuestionMarks($allowed_contacts_and_accounts) .")
										AND vtiger_troubletickets.ticketid = ?";
							$res = $adb->pquery($query, array($allowed_contacts_and_accounts, $entityid));
							if ($adb->num_rows($res) > 0) {
								return true;
							}
							break;		
	}
	return false;
}


function get_faq_documents($id,$module,$customerid,$sessionid)
{
	global $adb,$log;	
		$fields_list = array(
			'title' => 'Title',
			'filename' => 'FileName',
			'createdtime' => 'Created Time');
		
		if(!validateSession($customerid,$sessionid))
		return null; 
		
		$query ="select vtiger_notes.title,'Documents' ActivityType, vtiger_notes.filename,
				crm2.createdtime,vtiger_notes.notesid,vtiger_notes.folderid,
				vtiger_notes.notecontent description, vtiger_users.user_name
				from vtiger_notes
				inner join vtiger_senotesrel on vtiger_senotesrel.notesid= vtiger_notes.notesid
				inner join vtiger_crmentity on vtiger_crmentity.crmid= vtiger_senotesrel.crmid
				inner join vtiger_crmentity crm2 on crm2.crmid=vtiger_notes.notesid and crm2.deleted=0
				LEFT JOIN vtiger_groups
				ON vtiger_groups.groupid = vtiger_crmentity.smownerid			
				inner join vtiger_users on crm2.smownerid= vtiger_users.id
				where vtiger_crmentity.crmid=?";
				$res = $adb->pquery($query,array($id));
				$noofdata = $adb->num_rows($res);
				for( $j= 0;$j < $noofdata; $j++)
				{
				$i=0;
					foreach($fields_list as $fieldname => $fieldlabel) {
						$output[0][$module]['head'][0][$i]['fielddata'] = $fieldlabel; //$adb->query_result($fieldres,$i,'fieldlabel');
						$fieldvalue = $adb->query_result($res,$j,$fieldname);
								if($fieldname =='title') 
								{
									$fieldid = $adb->query_result($res,$j,'notesid');
									$filename = $fieldvalue;
									$fieldvalue = '<a href="index.php?&module=Documents&action=index&id='.$fieldid.'">'.$fieldvalue.'</a>';
								}
								if($fieldname == 'filename')
								{
									$fieldid = $adb->query_result($res,0,'notesid');
									$filename = $fieldvalue;
									$folderid = $adb->query_result($res,0,'folderid');
									$fieldvalue = '<a href="index.php?&downloadfile=true&folderid='.$folderid.'&filename='.$filename.'&module=Documents&action=index&id='.$fieldid.'">'.$fieldvalue.'</a>';
								}
									$output[1][$module]['data'][$j][$i]['fielddata'] = $fieldvalue;
					$i++;
					}
				}
				$log->debug("Exiting get_faq_document ..".print_r($output,true));
				return $output;
	}

function get_modules()
{
global $configModules,$log;
$i=0;
$showmodules_arr = array();
	foreach($configModules  as $mod => $configs)
	{
		foreach($configs as $allow => $value )
		if($allow == 'allow' && $value == true){
			$showmodules_arr[$i]=$mod;
			$i++;
			}	
	}
	return $showmodules_arr;	
}

function api_permission($module){
	global $configModules;
		foreach($configModules as $mod => $configs){
		if($mod == $module){
			if($configs['allow'] == true)
				return 'true';
			break;
		}
	}
	return 'false';
}
	
function show_all($module){
	global $configModules,$log;
		foreach($configModules as $mod => $configs){
			if($mod == $module){
			if($configs['viewall'] == true)
				return 'true';
			break;
			}
		}
	return 'false';
}

/* Begin the HTTP listener service and exit. */ 
$server->service($HTTP_RAW_POST_DATA); 

exit(); 

?>
