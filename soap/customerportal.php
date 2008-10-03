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
require_once('include/logging.php');
require_once('include/nusoap/nusoap.php');
require_once('modules/HelpDesk/HelpDesk.php');
require_once('modules/Emails/mail.php');
require_once('modules/HelpDesk/language/en_us.lang.php');


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

//Puneeth : Added for enhancement from Rosa Weber

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


//Puneeth : End



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

//Puneeth : Added for enhancement from Rosa Weber

$server->register(
        'get_portalmessage',
        array('id'=>'xsd:string'),
        array('return'=>'xsd:string'),
        $NAMESPACE);

$server->register(
	'get_contact_detail',
	array('id'=>'xsd:string','block'=>'xsd:string'),
	array('return'=>'tns:field_details_array'),
	$NAMESPACE);

$server->register(
	'get_cf_field_details',
	array('id'=>'xsd:string'),
	array('return'=>'tns:field_details_array'),
	$NAMESPACE);

$server->register(
	'get_account_detail',
	array('id'=>'xsd:string','block'=>'xsd:string'),
	array('return'=>'tns:field_details_array'),
	$NAMESPACE);

$server->register(
        'get_check_account_id',
        array('id'=>'xsd:string'),
        array('return'=>'xsd:string'),
        $NAMESPACE);

$server->register(
        'get_account_name',
        array('id'=>'xsd:string'),
        array('return'=>'xsd:string'),
        $NAMESPACE);

$server->register(
        'get_ownername',
        array('id'=>'xsd:string','modulename'=>'xsd:string'),
        array('return'=>'xsd:string'),
        $NAMESPACE);

$server->register(
	'get_product_list',
	array('id'=>'xsd:string','block'=>'xsd:string'),
	array('return'=>'tns:product_list_array'),
	$NAMESPACE);

$server->register(
	'get_product_detail',
	array('id'=>'xsd:string','block'=>'xsd:string'),
	array('return'=>'tns:field_details_array'),
	$NAMESPACE);

$server->register(
        'get_vendor_name',
        array('id'=>'xsd:string'),
        array('return'=>'xsd:string'),
        $NAMESPACE);

$server->register(
	'get_image_url',
	array('id'=>'xsd:string','module'=>'xsd:string'),
	array('return'=>'tns:field_details_array'),
	$NAMESPACE);

$server->register(
	'get_list_values',
	array('id'=>'xsd:string','block'=>'xsd:string'),
	array('return'=>'tns:field_datalist_array'),
	$NAMESPACE);

$server->register(
	'get_product_urllist',
	array('customerid'=>'xsd:string','productid'=>'xsd:string','block'=>'xsd:string'),
	array('return'=>'tns:field_datalist_array'),
	$NAMESPACE);

$server->register(
	'get_pdf',
	array('id'=>'xsd:string','block'=>'xsd:string'),
	array('return'=>'tns:field_datalist_array'),
	$NAMESPACE);

$server->register(
        'get_salesorder_name',
        array('id'=>'xsd:string'),
        array('return'=>'xsd:string'),
        $NAMESPACE);

$server->register(
	'get_filecontent_detail',
	array('id'=>'xsd:string','block'=>'xsd:string'),
	array('return'=>'tns:get_ticket_attachments_array'),
	$NAMESPACE);

$server->register(
	'get_invoice_detail',
	array('id'=>'xsd:string','block'=>'xsd:string'),
	array('return'=>'tns:field_details_array'),
	$NAMESPACE);


//Puneeth : End

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

	//$params = Array('id'=>"$id", 'sessionid'=>"$sessionid");
	//$result = get_KBase_details($input_array);

	return $result;
}

/**	function used to get the tickets list
 *	@param array $input_array - array which contains the following values 
 		=>	int $id - customer ie., contact id who has loggedin in the customer portal
			int $sessionid - session id
			string $user_name - customer name who has loggedin in the customer portal
			string $where - where condition to get the tickets based on this condition if the customer enter the search criteria where as this is optional
			string $match - all or any, which will be entered when the customer entered multiple search conditions and whether we want to search all or any of the given conditions
 *	return array $output_list - This function will call get_user_tickets_list function and return the array with the ticket details
 */
function get_tickets_list($input_array)
{
	global $adb;
	$adb->println("INPUT ARRAY for the function get_tickets_list");
	$adb->println($input_array);

	$temp = $input_array['where'];//addslashes is already added with where condition fields in portal itself
	//foreach($input_array as $fieldname => $fieldvalue)$input_array[$fieldname] = mysql_real_escape_string($fieldvalue);

	$id = (int) $input_array['id'];
	$sessionid = $input_array['sessionid'];
	$user_name = $input_array['user_name'];
	$match = $input_array['match'];

	$where = $temp;
	$adb->println("WHERE CONDITION ==> $where");	

	if(!validateSession($id,$sessionid))
		return null;

        $seed_ticket = new HelpDesk();
        $output_list = Array();
 
	$response = $seed_ticket->get_user_tickets_list($user_name,$id,$where,$match);
        $ticketsList = $response['list'];
    
       	// create a return array of ticket details.
	foreach($ticketsList as $ticket)
	{
   		$output_list[] = Array(
			"ticketid" => $ticket[ticketid],
			"title"    => $ticket[title],
			"firstname" => $ticket[firstname],
			"lastname" => $ticket[lastname],
			"parent_id"=> $ticket[parent_id],
			"productid"=> $ticket[productid],
			"productname"=> $ticket[productname],
			"priority" => $ticket[priority],
			"severity"=>$ticket[severity],
			"status"=>$ticket[status],
			"category"=>$ticket[category],
			"description"=>$ticket[description],
			"solution"=>$ticket[solution],
                        "createdtime"=>$ticket[createdtime],
                        "modifiedtime"=>$ticket[modifiedtime],
 			);
    	}

    //to remove an erroneous compiler warning
    $seed_ticket = $seed_ticket;

    return $output_list;
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
	global $adb;
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
	if($module != '')
	{
		$res = $adb->pquery("select vtiger_moduleowners.*, vtiger_tab.name from vtiger_moduleowners inner join vtiger_tab on vtiger_moduleowners.tabid = vtiger_tab.tabid where name=?", array($module));
		if($adb->num_rows($res) > 0)
		{
			$user_id = $adb->query_result($res,0,"user_id");
		}
	}
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
	global $adb;
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

//Puneeth : Added for enhancement from Rosa Weber

/**	function used to get the Contact Portal message 
 *	@param int $id - contact id
 *	return string $message - contact posrtal message will be returned from :ustomerdetails table
 */
function get_portalmessage($id)
{
	global $adb;

	$res = $adb->pquery("select * from vtiger_customerdetails where customerid=".$id);
	$message = $adb->query_result($res,0,'portalmessage');

	return $message;
}

/**	function used to get the Contact Information
 *	@param int $id - contact id
 *	return string $message - contact informations will be returned from :contactdetails table
 */
function get_contact_detail($id,$block)
{

	global $adb;
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
			$temp_fieldvalue = get_ownername($id,"contact");
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
function get_cf_field_details($id)
{

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
	global $adb;
 	$assignres = $adb->pquery("select * from vtiger_crmentity where crmid=".$id);
	$ownerid=$adb->query_result($assignres,0,'smownerid');
	if($ownerid == 0 && $modulename != '')
	{
		if($modulename =='contact')
			$query='select * from vtiger_contactgrouprelation where contactid ='.$id;
		else if($modulename =='account')
			$query='select * from vtiger_accountgrouprelation where accountid ='.$id;
 		$assigngroupres = $adb->pquery($query);
		$groupname[0]['groupname'][0]['fieldlabel']='groupname';
		$groupname[0]['groupname'][0]['fieldvalue']=$adb->query_result($assigngroupres,0,'groupname');
		$groupname[0]['assigninfo'][0]['fieldlabel']='Group Name';
		$groupname[0]['assigninfo'][0]['fieldvalue']=$adb->query_result($assigngroupres,0,'groupname');
	}
	else if($ownerid != 0 && $ownerid != '')
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
function get_account_detail($id,$block)
{

	global $adb;
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
			$temp_fieldvalue = get_ownername($id,"account");
			$fieldlabel ='';
			$fieldvalue ='';
		//	$fieldvalue =$temp_fieldvalue[0]['groupname'][0]['fieldvalue'];
			$output[0]['assigninfo'] = $temp_fieldvalue[0]['assigninfo'];
		}
		if($block=='ACCINFORMATION' && $fieldname == 'parentid' && $fieldvalue != '')
			$fieldvalue = get_account_name($fieldvalue);
		$output[0][$block][$i]['fieldlabel'] = $fieldlabel;
		$output[0][$block][$i]['fieldvalue'] = $fieldvalue;
	}
        $adb->println($output);	
	return $output;
}
// get A product list
//
function get_product_list($id,$block)
{
	
	global $adb;
	$sql = "select 
		vtiger_products.*
		from vtiger_products 
		inner join vtiger_inventoryproductrel on vtiger_inventoryproductrel.productid=vtiger_products.productid 
		inner join vtiger_invoice on vtiger_inventoryproductrel.id = vtiger_invoice.invoiceid  
		where vtiger_invoice.accountid=".$id." 
	       	group by vtiger_inventoryproductrel.productid";
	$result = $adb->pquery($sql);	
	$noofdata = $adb->num_rows($result);
	for($i=0;$i<$noofdata;$i++)
	{
		$list['0'][$block][$i]['productid'] = $adb->query_result($result,$i,'productid');
		$list['0'][$block][$i]['productname'] = $adb->query_result($result,$i,'productname');
		$list['0'][$block][$i]['productcode'] = $adb->query_result($result,$i,'productcode');
		$list['0'][$block][$i]['commissionrate'] = $adb->query_result($result,$i,'commissionrate');
		$list['0'][$block][$i]['qtyinstock'] = $adb->query_result($result,$i,'qtyinstock');
		$list['0'][$block][$i]['qty_per_unit'] = $adb->query_result($result,$i,'qty_per_unit');
		$list['0'][$block][$i]['unit_price'] = $adb->query_result($result,$i,'unit_price');
	}
        $adb->println($list);	
	
	return $list;
}

/**	function used to get the Account Information
 *	@param int $id - account id
 *	return string $message - Account informations will be returned from :Accountdetails table
 */
function get_product_detail($id,$block)
{

	global $adb;
	if($block == 'PROINFORMATION')
		$fields_list = "'productname','productcode','discontinued','manufacturer','productcategory','sales_start_date','sales_end_date','start_date','expiry_date','website','vendor_id','mfr_part_no','vendor_part_no','serialno','productsheet','glacct'";
	elseif($block =='PRODESCINFO')
		$fields_list ="'product_description'";
	
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

function get_image_url($id,$module)
{
	global $adb;
	global $site_URL;
	
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

function get_list_values($id,$block)
{
	global $adb;
	$adb->println("inside get_list_values ".$id."block".$block);
	if($block == 'Quotes')
	{	
		$fields_list ="'quoteid','subject','quotestage','validtill','team','accountid','total'";
		$fieldquery = "select fieldname, columnname, fieldlabel from vtiger_field where tabid=20 and columnname in ($fields_list) order by sequence";
		$query ='select vtiger_quotes.* from vtiger_quotes inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_quotes.quoteid where vtiger_crmentity.deleted != 1 and vtiger_quotes.contactid='.$id.'  order by vtiger_quotes.quoteid desc'; 
	}
	else if($block == 'Invoice')
	{	
		$fields_list ="'invoice_no','subject','salesorderid','invoicestatus','total'";
		$fieldquery = "select fieldname, columnname, fieldlabel from vtiger_field where tabid=23 and columnname in ($fields_list) order by sequence";
		$query ='select vtiger_invoice.* from vtiger_invoice inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_invoice.invoiceid where  vtiger_crmentity.deleted != 1 and  vtiger_invoice.contactid='.$id.' order by vtiger_invoice.invoiceid desc '; 
	}
	else if ($block == 'Documents')
	{
		$fields_list ="'title','filename','createdtime','modifiedtime'";
		$fieldquery = "select fieldname, columnname, fieldlabel from vtiger_field where tabid=8 and columnname in ($fields_list) order by sequence";
		$query ='select vtiger_notes.*,vtiger_crmentity.* from vtiger_notes inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_notes.notesid where vtiger_crmentity.deleted != 1 and  vtiger_notes.contact_id='.$id.' order by vtiger_notes.notesid desc '; 
	}
	else if ($block == 'Product')
	{
		$fields_list ="'productid','productname','productcode','qty_per_unit','unit_price'";
		$fieldquery = "select fieldname, columnname, fieldlabel from vtiger_field where tabid=14 and columnname in ($fields_list) order by sequence";
	$query ="select case when vtiger_quotes.contactid not like '' then \"Quotes\" when vtiger_invoice.contactid not like '' then \"Invoice\" else vtiger_crmentity.setype end as type, vtiger_invoice.contactid as invo,vtiger_products.*, productname,vtiger_inventoryproductrel.id from vtiger_products left join vtiger_seproductsrel on vtiger_seproductsrel.productid = vtiger_products.productid left join vtiger_inventoryproductrel on vtiger_inventoryproductrel.productid = vtiger_products.productid left join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_seproductsrel.crmid left join vtiger_invoice on vtiger_invoice.invoiceid = vtiger_inventoryproductrel.id left join vtiger_quotes on vtiger_quotes.quoteid = vtiger_inventoryproductrel.id where (vtiger_quotes.contactid = $id or vtiger_invoice.contactid = $id or vtiger_seproductsrel.crmid = $id or vtiger_seproductsrel.crmid in (select accountid from vtiger_contactdetails where contactid=$id)) group by vtiger_products.productid order by vtiger_products.productid desc";
	}
	else if ($block == 'ProductPurchased' || $block == 'ProductPending' || $block == 'ProductCompleted')
	{
		$fields_list ="'productid','productname','productcode','qty_per_unit','unit_price'";
		$fieldquery = "select fieldname, columnname, fieldlabel from vtiger_field where tabid=14 and columnname in ($fields_list) order by sequence";
		$query ="select case when vtiger_quotes.contactid not like '' then \"Quotes\" when vtiger_invoice.contactid not like '' then \"Invoice\" else vtiger_crmentity.setype end as type, vtiger_invoice.contactid as invo,vtiger_products.*, productname,vtiger_inventoryproductrel.id from vtiger_products left join vtiger_seproductsrel on vtiger_seproductsrel.productid = vtiger_products.productid left join vtiger_inventoryproductrel on vtiger_inventoryproductrel.productid = vtiger_products.productid left join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_seproductsrel.crmid left join vtiger_invoice on vtiger_invoice.invoiceid = vtiger_inventoryproductrel.id left join vtiger_quotes on vtiger_quotes.quoteid = vtiger_inventoryproductrel.id ";
		$where ="where (vtiger_quotes.contactid = $id or vtiger_invoice.contactid = $id or vtiger_seproductsrel.crmid = $id or vtiger_seproductsrel.crmid in (select accountid from vtiger_contactdetails where contactid=$id)) and vtiger_invoice.contactid not like '' ";
		if($block == 'ProductPending')
			$where .= " and vtiger_invoice.download_status != 1 ";
		if($block == 'ProductCompleted')
			$where .= " and vtiger_invoice.download_status = 1 ";

		$groupby =' group by vtiger_products.productid order by vtiger_invoice.invoiceid desc';
		
		$query .=$where.$groupby;

	}
	$fieldres = $adb->pquery($fieldquery);
	$nooffields = $adb->num_rows($fieldres);
	for($i=0;$i<$nooffields;$i++)
		{
		$fieldname = $adb->query_result($fieldres,$i,'columnname');
		$output[0][$block]['head'][0][$i]['fielddata'] = $adb->query_result($fieldres,$i,'fieldlabel');
		}
	
	$res = $adb->pquery($query);
	$noofdata = $adb->num_rows($res);
	for( $j= 0;$j < $noofdata; $j++)
	{
		for($i=0;$i<$nooffields;$i++)
		{
			$fieldname = $adb->query_result($fieldres,$i,'columnname');
			$fieldvalue = $adb->query_result($res,$j,$fieldname);
			if($block == 'Quotes' && $fieldname =='subject') 
			{
				$fieldid = $adb->query_result($res,$j,'quoteid');
				$filename = $fieldid.'_Quotes.pdf';
				$fieldvalue = '<a href="index.php?downloadfile=true&module=Quotes&action=index&id='.$fieldid.'">'.$fieldvalue.'</a>';
			}
			if($block == 'Invoice' && $fieldname =='subject') 
			{
				$fieldid = $adb->query_result($res,$j,'invoiceid');
				$filename = $fieldid.'_Invoice.pdf';
				$fieldvalue = '<a href="index.php?downloadfile=true&module=Invoice&action=index&status=true&id='.$fieldid.'">'.$fieldvalue.'</a>';
			}
			if($block == 'Documents' && $fieldname =='filename') 
			{
				$fieldid = $adb->query_result($res,$j,'notesid');
				$filename = $fieldvalue;
				$fieldvalue = '<a href="index.php?downloadfile=true&filename='.$filename.'&module=Attachments&action=index&id='.$fieldid.'">'.$fieldvalue.'</a>';
			}
			if(($block == 'Product' || $block == 'ProductPurchased' || $block == 'ProductPending'|| $block == 'ProductCompleted')&& $fieldname =='productname') 
			{
				$fieldid = $adb->query_result($res,$j,'productid');
				if($block == 'ProductCompleted')
					$status = 'Completed';
				else
					$status = $block;
				$fieldvalue = '<a href="index.php?module=Products&action=index&productid='.$fieldid.'&status='.$status.'">'.$fieldvalue.'</a>';
			}
			if($block == 'Quotes' && $fieldname == 'accountid')
			{
				$fieldvalue = get_account_name($fieldvalue);
			}	
			if($block == 'Invoice' && $fieldname == 'salesorderid')
			{
				if($fieldvalue != '')
				$fieldvalue = get_salesorder_name($fieldvalue);
			}	
			$output[1][$block]['data'][$j][$i]['fielddata'] = $fieldvalue;
		}
	}
	
	$adb->println($output);	

 return $output;	 
	
}

/**	function used to get the Quotes/Invoice pdf
 *	@param int $id - id -id
 *	return string $output - pd link value
 */

function get_pdf($id,$block)
{
	global $adb;
	global $current_user,$log;
	global $currentModule,$mod_strings,$app_strings,$app_list_strings;
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

/**	function used to get the contents of a file
 *	@param int $id - customer ie., id 
 *	return $filecontents array with single file contents like [fileid] => filecontent
 */
function get_filecontent_detail($id,$block)
{
	global $adb;
	global $site_URL;
	$query ='select vtiger_attachments.*,vtiger_seattachmentsrel.* from vtiger_attachments inner join vtiger_seattachmentsrel on vtiger_seattachmentsrel.attachmentsid=vtiger_attachments.attachmentsid where vtiger_seattachmentsrel.crmid ='.$id; 
	$res = $adb->pquery($query);
	$filename = $adb->query_result($res,0,'name');
	$filepath = $adb->query_result($res,0,'path');
	$fileid = $adb->query_result($res,0,'attachmentsid');
	$filesize = filesize($filepath.$fileid."_".$filename);
	$filetype = $adb->query_result($res,0,'type');
	$filenamewithpath=$filepath.$fileid.'_'.$filename;

	$output[0]['fileid'] = $fileid;
	$output[0]['filename'] = $filename;
	$output[0]['filetype'] = $filetype;
	$output[0]['filesize'] = $filesize;
	$output[0]['filecontents']= base64_encode(file_get_contents($filenamewithpath));
	return $output;
}

/**	function used to get the Product Url ListDetails name
 *	@param int $id -  id
 *	return $arr - downoadlisturl array returned
 */

function get_product_urllist($customerid,$productid,$block)
{
		global $adb,$site_URL;
		$fieldheader = array('Invoice No','Invoice Name','Download Url','License Key','Status');
		$query=" select vtiger_invoice.* from vtiger_invoice 
			inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_invoice.invoiceid 
			inner join vtiger_inventoryproductrel on vtiger_invoice.invoiceid = vtiger_inventoryproductrel.id 
			where vtiger_invoice.contactid= $customerid and vtiger_inventoryproductrel.productid = $productid  ";
		$groupby= " group by vtiger_invoice.invoiceid order by vtiger_invoice.invoiceid desc ";
		if($block =='ProductPending')
			$spl_con =" and vtiger_invoice.download_status = 0 ";
		if($block =='Completed')
			$spl_con =" and vtiger_invoice.download_status = 1 ";
		$query .=$spl_con.$groupby;
		$res = $adb->pquery($query);
		$noofdata = $adb->num_rows($res);
		$nooffield =count($fieldheader);
		for( $i= 0;$i < $nooffield; $i++)
		$output[0][$block]['head'][0][0][$i]['fielddata'] = $fieldheader[$i];
		$fieldlist =array('invoice_no','subject','file_path','license_key','download_status');
		
		for( $i= 0;$i < $noofdata; $i++)
		{
			$sql =" select vtiger_invoice.* from vtiger_invoice where vtiger_invoice.contactid= $customerid ".$spl_con." and vtiger_invoice.invoiceid =".$adb->query_result($res,$i,'invoiceid');
			
		$result = $adb->pquery($sql);
		$noresult = $adb->num_rows($result);
			for($j= 0;$j < $noresult; $j++)
		$result = $adb->pquery($sql);
		$noresult = $adb->num_rows($result);
			for($j= 0;$j < $noresult; $j++)
			{
				for( $i1= 0;$i1 < $nooffield; $i1++)
				{
					$fieldvalue = $adb->query_result($result,$j,$fieldlist[$i1]);
					if($fieldlist[$i1] == 'file_path' && $fieldvalue != '')
					{
						$id     = $adb->query_result($result,$j,'invoiceid');
						$subject= $adb->query_result($result,$j,'subject');
						$file_path = $adb->query_result($result,$j,'file_path');
						$inv_status = $adb->query_result($result,$j,'invoicestatus');
						$status = $adb->query_result($result,$j,$fieldlist[$nooffield-1]);
						if($status != '' && $status == 0 && $file_path != '' && $inv_status == 'Paid')
						{
							$param = "action=download&parentid=$id";
							$param = base64_encode($param);
							$fieldvalue ='<a href='.$site_URL.'/dindex.php?param='.$param.'>Download</a>'; 
						}
						else
							$fieldvalue ='Un Available';
					}
					if($fieldlist[$i1]=='download_status')
					{
						if($fieldvalue == 0)
							$fieldvalue ='Not Done';
						else 
							$fieldvalue ='Done';
					
					}
				$output[1][$block]['data'][$i][$j][$i1]['fielddata'] = $fieldvalue;
				}
			}
		}
 	return $output;	 
}
 
function get_invoice_detail($id,$block)
{

	global $adb,$site_URL;
	if($block == 'INVINFORMATION')
		$fields_list = "'subject','invoicedate','duedate','total','createdtime','invoicestatus'";
	elseif($block =='INVDESCINFO')
		$fields_list ="'download_status','license_key','subject','file_path'";
	
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
		if($block=='INVDESCINFO' && $fieldname == 'subject' && $fieldvalue !='')
		{
			$fieldid = $adb->query_result($res,0,'invoiceid');
			$filename = $fieldid.'_Invoice.pdf';
			$fieldlabel = 'Download PDF';
			$fieldvalue = '<a href="index.php?downloadfile=true&module=Invoice&action=index&id='.$fieldid.'">Invoice.pdf</a>';
		}
		if($block=='INVDESCINFO' && $fieldname == 'download_status')
		{
			if($fieldvalue == "1")
				$fieldvalue = " Done ";
			else 
				$fieldvalue = " Not Done";
		}
		if($block == 'INVDESCINFO' && $fieldname == 'file_path' && $fieldvalue != '')
		{
			$fieldlabel='Download Product';
			$inv_id     = $adb->query_result($res,$j,'invoiceid');
			$subject= $adb->query_result($res,$j,'subject');
			$file_path = $adb->query_result($res,$j,'file_path');
			$inv_status = $adb->query_result($res,$j,'invoicestatus');
			$status = $adb->query_result($res,$j,'download_status');
			if($status != '' && $status == 0 && $file_path != '' && $inv_status == 'Paid')
			{
				$param = "action=download&parentid=$inv_id";
				$param = base64_encode($param);
				$fieldvalue ='<a href='.$site_URL.'/dindex.php?param='.$param.'>Download</a>'; 
			}
			else
				$fieldvalue ='Un Available';
		}
		$output[0][$block][$i]['fieldlabel'] = $fieldlabel;//adb->query_result($fieldres,$i,'fieldlabel');
		$output[0][$block][$i]['fieldvalue'] = $fieldvalue;
	}
        $adb->println($output);	
	return $output;
}


//Puneeth : End

/* Begin the HTTP listener service and exit. */ 
$server->service($HTTP_RAW_POST_DATA); 

exit(); 

?>
