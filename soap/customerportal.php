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

/* Begin the HTTP listener service and exit. */ 
$server->service($HTTP_RAW_POST_DATA); 

exit(); 

?>
