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



//Field array for vtiger_troubletickets
$server->wsdl->addComplexType(
	'tickets_list_array',
	'complexType',
	'array',
	'',
	array(
	        'ticketid' => array('name'=>'ticketid','type'=>'xsd:string'),
	        'title' => array('name'=>'title','type'=>'xsd:string'),
        	'groupname' => array('name'=>'groupname','type'=>'xsd:string'),
        	'firstname' => array('name'=>'firstname','type'=>'xsd:string'),
        	'lastname' => array('name'=>'lastname','type'=>'xsd:string'),
	        'parent_id' => array('name'=>'parent_id','type'=>'xsd:string'),
	        'productid' => array('name'=>'productid','type'=>'xsd:string'),
	        'productname' => array('name'=>'productname','type'=>'xsd:string'),
	        'priority' => array('name'=>'priority','type'=>'xsd:string'),
	        'severity' => array('name'=>'severity','type'=>'xsd:string'),
	        'status' => array('name'=>'status','type'=>'xsd:string'),
	        'category' => array('name'=>'category','type'=>'xsd:string'),
	        'description' => array('name'=>'description','type'=>'xsd:string'),
	        'solution' => array('name'=>'solution','type'=>'xsd:string'),
	        'createdtime' => array('name'=>'createdtime','type'=>'xsd:string'),
	        'modifiedtime' => array('name'=>'modifiedtime','type'=>'xsd:string'),
	     )
);

$server->wsdl->addComplexType(
        'ticket_comments_array',
        'complexType',
        'array',
        '',
        array(
                'comments' => array('name'=>'comments','type'=>'tns:xsd:string'),
             )
);

$server->wsdl->addComplexType(
        'combo_values_array',
        'complexType',
        'array',
        '',
        array(
                'productid' => array('name'=>'productid','type'=>'tns:xsd:string'),
                'productname' => array('name'=>'productname','type'=>'tns:xsd:string'),
                'ticketpriorities' => array('name'=>'ticketpriorities','type'=>'tns:xsd:string'),
                'ticketseverities' => array('name'=>'ticketseverities','type'=>'tns:xsd:string'),
                'ticketcategories' => array('name'=>'ticketcategories','type'=>'tns:xsd:string'),
                'moduleslist' => array('name'=>'moduleslist','type'=>'tns:xsd:string'),
             )
);

$server->wsdl->addComplexType(
        'KBase_array',
        'complexType',
        'array',
        '',
	'SOAP-ENC:Array',
	array(),
        array(
                array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:kbase_detail[]')
	     ),
	'tns:kbase_detail'
);

$server->wsdl->addComplexType(
	'kbase_detail',
	'complexType',
        'array',
        '',
	array(
              'faqcategory' => array('name'=>'faqcategory','type'=>'tns:xsd:string'),
              'faq' => array(
				'id' => array('name'=>'id','type'=>'tns:xsd:string'),
		                'question' => array('name'=>'question','type'=>'tns:xsd:string'),
		                'answer' => array('name'=>'answer','type'=>'tns:xsd:string'),
        		        'category' => array('name'=>'category','type'=>'tns:xsd:string'),
        		        'faqcreatedtime' => array('name'=>'createdtime','type'=>'tns:xsd:string'),
        		        'faqmodifiedtime' => array('name'=>'createdtime','type'=>'tns:xsd:string'),
        		        'faqcomments' => array('name'=>'faqcomments','type'=>'tns:xsd:string'),
		    	    )
             )
);

$server->wsdl->addComplexType(
        'ticket_update_comment_array',
        'complexType',
        'array',
        '',
        array(
                'ticketid' => array('name'=>'ticketid','type'=>'tns:xsd:string'),
                'parent_id' => array('name'=>'parent_id','type'=>'tns:xsd:string'),
                'createdtime' => array('name'=>'createdtime','type'=>'tns:xsd:string'),
                'comments' => array('name'=>'comments','type'=>'tns:xsd:string'),
             )
);

//Added for User Details
$server->wsdl->addComplexType(
	'user_array',
	'complexType',
	'array',
        '',
        array(
		'id' => array('name'=>'id','type'=>'xsd:string'),
		'user_name' => array('name'=>'user_name','type'=>'xsd:string'),
		'user_password' => array('name'=>'user_password','type'=>'xsd:string'),
		'last_login' => array('name'=>'last_login_time','type'=>'xsd:string'),
		'support_start_date' => array('name'=>'support_start_date','type'=>'xsd:string'),
		'support_end_date' => array('name'=>'support_end_date','type'=>'xsd:string'),
	     )
);

//Added to get the picklist values as array
$server->wsdl->addComplexType(
        'get_picklists_array',
        'complexType',
        'array',
        '',
        array(
                'ticket_picklist' => array('name'=>'ticket_picklist','type'=>'tns:xsd:string'),
             )
);

//Added for ticket vtiger_attachments
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

//Added to return the file content
$server->wsdl->addComplexType(
        'get_filecontent_array',
        'complexType',
        'array',
        '',
        array(
		'fileid'=>'xsd:string','type'=>'tns:xsd:string',
             )
);

$server->wsdl->addComplexType(
        'add_ticket_attachment_array',
        'complexType',
        'array',
        '',
        array(
                'ticketid' => array('name'=>'ticketid','type'=>'xsd:string'),
                'filename' => array('name'=>'filename','type'=>'xsd:string'),
                'filetype' => array('name'=>'filetype','type'=>'xsd:string'),
                'filesize' => array('name'=>'filesize','type'=>'xsd:string'),
                'filecontents' => array('name'=>'filecontents','type'=>'xsd:string'),
             )
);





$server->register(
	'authenticate_user',
	array('user_name'=>'xsd:string','password'=>'xsd:string'),
	array('return'=>'tns:user_array'),
	$NAMESPACE);

$server->register(
	'change_password',
	array('id'=>'xsd:string','user_name'=>'xsd:string','password'=>'xsd:string'),
	array('return'=>'tns:user_array'),
	$NAMESPACE);
  
$server->register(
	'create_ticket',
	array('title'=>'xsd:string','description'=>'xsd:string','priority'=>'xsd:string','severity'=>'xsd:string','category'=>'xsd:string','user_name'=>'xsd:string','parent_id'=>'xsd:string','product_id'=>'xsd:string','module'=>'xsd:string'),
	array('return'=>'tns:tickets_list_array'),
	$NAMESPACE);

$server->register(
	'get_tickets_list',
	array('user_name'=>'xsd:string','id'=>'xsd:string','where'=>'xsd:string','match'=>'xsd:string'),
	array('return'=>'tns:tickets_list_array'),
	$NAMESPACE);

$server->register(
	'get_ticket_comments',
	array('id'=>'xsd:string'),
	array('return'=>'tns:ticket_comments_array'),
	$NAMESPACE);

$server->register(
	'get_combo_values',
	array('id'=>'xsd:string'),
	array('return'=>'tns:combo_values_array'),
	$NAMESPACE);

$server->register(
	'get_KBase_details',
	array('id'=>'xsd:string'),
	array('return'=>'tns:KBase_array'),
	$NAMESPACE);

$server->register(
	'save_faq_comment',
	array('faqid'=>'xsd:string','comments'=>'xsd:string'),
	array('return'=>'tns:KBase_array'),
	$NAMESPACE);

//ContactSerialise fix by CraigF
$server->register(
	'update_ticket_comment',
	array('ticketid'=>'xsd:string',
              'ownerid'=>'xsd:string',
              'comments'=>'xsd:string'),
	array('return'=>'tns:ticket_update_comment_array'),
	$NAMESPACE);
//End 
$server->register(
        'close_current_ticket',
        array('ticketid'=>'xsd:string'),
	array('return'=>'xsd:string'),
        $NAMESPACE);

$server->register(
	'update_login_details',
	array('id'=>'xsd:string','flag'=>'xsd:string'),
	array('return'=>'tns:user_array'),
	$NAMESPACE);

$server->register(
	'send_mail_for_password',
	array('email'=>'xsd:string'),
	array('return'=>'xsd:string'),
	$NAMESPACE);

$server->register(
        'get_ticket_creator',
        array('id'=>'xsd:string'),
        array('return'=>'xsd:string'),
        $NAMESPACE);

$server->register(
	'get_picklists',
	array('id'=>'xsd:string'),
	array('return'=>'tns:get_picklists_array'),
	$NAMESPACE);

$server->register(
	'get_ticket_attachments',
	array('id'=>'xsd:string','ticketid'=>'xsd:string'),
	array('return'=>'tns:get_ticket_attachments_array'),
	$NAMESPACE);

$server->register(
	'get_filecontent',
	array('id'=>'xsd:string','fileid'=>'xsd:string','filename'=>'xsd:string'),
	array('return'=>'tns:get_filecontent_array'),
	$NAMESPACE);

$server->register(
	'add_ticket_attachment',
	array('ticketid'=>'xsd:string','filename'=>'xsd:string','filetype'=>'xsd:string','filesize'=>'xsd:string','filecontents'=>'xsd:string'),
	array('return'=>'tns:add_ticket_attachment_array'),
	$NAMESPACE);



/**	function used to get the list of ticket comments
 *	@param int $ticketid - ticket id
 *	return array $response - ticket comments and details as a array with elements comments, owner and createdtime which will be returned from the function get_ticket_comments_list
 */
function get_ticket_comments($ticketid)
{
	$seed_ticket = new HelpDesk();
        $output_list = Array();

	$response = $seed_ticket->get_ticket_comments_list($ticketid);

	return $response;
}

/**	function used to get the combo values ie., picklist values of the HelpDesk module and also the list of products
 *	@param string $id - empty string (we wont use this value, just an input element)
 *	return array $output - array which contains the product id, product name, ticketpriorities, ticketseverities, ticketcategories and module owners list
 */
function get_combo_values($id)
{
	global $adb;
	$output = Array();
	$sql = "select * from vtiger_products inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_products.productid where vtiger_crmentity.deleted=0";
	$result = $adb->pquery($sql, array());
	$noofrows = $adb->num_rows($result);
	for($i=0;$i<$noofrows;$i++)
        {
        	$output['productid']['productid'][$i] = $adb->query_result($result,$i,"productid");
                $output['productname']['productname'][$i] = $adb->query_result($result,$i,"productname");
        }

	$result1 = $adb->pquery("select * from vtiger_ticketpriorities", array());
	for($i=0;$i<$adb->num_rows($result1);$i++)
	{
		$output['ticketpriorities']['ticketpriorities'][$i] = $adb->query_result($result1,$i,"ticketpriorities");
	}

        $result2 = $adb->pquery("select * from vtiger_ticketseverities", array());
        for($i=0;$i<$adb->num_rows($result2);$i++)
        {
                $output['ticketseverities']['ticketseverities'][$i] = $adb->query_result($result2,$i,"ticketseverities");
        }

        $result3 = $adb->pquery("select * from vtiger_ticketcategories", array());
        for($i=0;$i<$adb->num_rows($result3);$i++)
        {
                $output['ticketcategories']['ticketcategories'][$i] = $adb->query_result($result3,$i,"ticketcategories");
        }

	//Added to get the modules list -- september 10 2005
        $sql2 = "select vtiger_moduleowners.*,vtiger_tab.name from vtiger_moduleowners inner join vtiger_tab on vtiger_moduleowners.tabid = vtiger_tab.tabid order by vtiger_tab.tabsequence";
        $result4 = $adb->pquery($sql2, array());
	for($i=0;$i<$adb->num_rows($result4);$i++)
        {
		$output['moduleslist']['moduleslist'][$i] = $adb->query_result($result4,$i,"name");
        }

	return $output;
}

/**	function to get the Knowledge base details
 *	@param string $id - empty string (we wont use this value, just an input element)
 *	return array $result - array which contains the faqcategory, all product ids , product names and all faq details
 */
function get_KBase_details($id='')
{
	global $adb;

	$category_query = "select * from vtiger_faqcategories";
	$category_result = $adb->pquery($category_query, array());
	$category_noofrows = $adb->num_rows($category_result);
	for($j=0;$j<$category_noofrows;$j++)
	{
		$faqcategory = $adb->query_result($category_result,$j,'faqcategories');
		$result['faqcategory'][$j] = $faqcategory;
	}

	$product_query = "select * from vtiger_products inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_products.productid where vtiger_crmentity.deleted=0";
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
 *	@param int $faqid - faq id
 *	@param string $comment - comment to be added with the FAQ
 *	return array $result - This function will call get_KBase_details and return that array
 */
function save_faq_comment($faqid,$comment)
{
	global $adb;
	$createdtime = $adb->formatDate(date('YmdHis'), true);	
	if(trim($comment) != '')
	{
		$faq_query = "insert into vtiger_faqcomments values(?,?,?,?)";
		$adb->pquery($faq_query, array('', $faqid, $comment, $createdtime));
	}
	$result = get_KBase_details('');
	return $result;
}

/**	function used to get the tickets list
 *	@param string $user_name - customer name who has loggedin in the customer portal
 *	@param int $id - customer id ie., contact id who has loggedin in the customer portal
 *	@param string $where - where condition to get the tickets based on this condition if the customer enter the search criteria where as this is optional
 *	@param string $match - all or any, which will be entered when the customer entered multiple search conditions and whether we want to search all or any of the give conditions
 *	return array $output_list - This function will call get_user_tickets_list function and return the array with the ticket details
 */
function get_tickets_list($user_name,$id,$where='',$match='')
{

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
 *	@param string $title - title of the ticket
 *	@param string $description - description of the ticket
 *	@param string $priority - priority of the ticket
 *	@param string $severity - severity of the ticket
 *	@param string $category - category of the ticket
 *	@param string $user_name - customer name
 *	@param string $parent_id - parent id ie., customer id as this customer is the parent for this ticket
 *	@param string $product_id - product id for the ticket
 *	@param string $module - module name where as based on this module we will get the module owner and assign this ticket to that corresponding user
 *	return array - currently created ticket array, if this is not created then all tickets list will be returned
 */
function create_ticket($title,$description,$priority,$severity,$category,$user_name,$parent_id,$product_id,$module)
{
	global $adb;

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

	$tickets_list =  get_tickets_list($user_name,$parent_id); 
	foreach($tickets_list as $ticket_array)
	{
		if($ticket->id == $ticket_array['ticketid'])
		{
			$record_save = 1;
			$record_array[0]['new_ticket'] = $ticket_array;
		}
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
	//return $tickets_list;
	//return $ticket->id;
}

/**	function used to update the ticket comment which is added from the customer portal
 *	@param int $ticketid - ticket id
 *	@param int $ownerid - customer ie., contact id who has added this ticket comment
 *	@param string $comments - comment which is added from the customer portal
 *	return void
 */
function update_ticket_comment($ticketid,$ownerid,$comments)
{
	global $adb,$mod_strings;
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
 *	@param int $ticketid - ticket id
 *	return string - success or failure message will be returned based on the ticket close update query
 */
function close_current_ticket($ticketid)
{
	global $adb,$mod_strings;
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
 *	return array $list - returns array with all the customer details
 */
function authenticate_user($username,$password)
{
	global $adb;
	$current_date = date("Y-m-d");
	$sql = "select id, user_name, user_password,last_login_time, support_start_date, support_end_date from vtiger_portalinfo inner join vtiger_customerdetails on vtiger_portalinfo.id=vtiger_customerdetails.customerid inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_portalinfo.id where vtiger_crmentity.deleted=0 and user_name=? and user_password = ? and isactive=1 and vtiger_customerdetails.support_end_date >= ?";
	$result = $adb->pquery($sql, array($username, $password, $current_date));	
	$list['id'] = $adb->query_result($result,0,'id');
	$list['user_name'] = $adb->query_result($result,0,'user_name');
	$list['user_password'] = $adb->query_result($result,0,'user_password');
	$list['last_login_time'] = $adb->query_result($result,0,'last_login_time');
	$list['support_start_date'] = $adb->query_result($result,0,'support_start_date');
	$list['support_end_date'] = $adb->query_result($result,0,'support_end_date');

	return $list;
}

/**	function used to change the password for the customer portal
 *	@param int $id - customer id ie., contact id
 *	@param string $username - customer name
 *	@param string $password - new password to change
 *	return array $list - returns array with all the customer details
 */
function change_password($id,$username,$password)
{
	global $adb;
	$sql = "update vtiger_portalinfo set user_password=? where id=? and user_name=?";
	$result = $adb->pquery($sql, array($password, $id, $username));

	$list = authenticate_user($username,$password);

        return $list;
}

/**	function used to update the login details for the customer
 *	@param int $id - customer id
 *	@param $flag - login/logout, based on the login login or logout time will be updated for the customer
 *	return $list - empty value
 */
function update_login_details($id,$flag)
{
        global $adb;
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

        $mailserverresult = $adb->pquery("select * from vtiger_systems where server_type=?", array(email));
        $mail_server = $adb->query_result($mailserverresult,0,'server');
        $mail_server_username = $adb->query_result($mailserverresult,0,'server_username');
        $mail_server_password = $adb->query_result($mailserverresult,0,'server_password');
        $smtp_auth = $adb->query_result($mailserverresult,0,'smtp_auth');

        $mail->Host = $mail_server;
        $mail->SMTPAuth = $smtp_auth;
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
		return "false@@@<b>".$mod_strings['LBL_GIVE_MAILID']."</b>";
	}
	elseif($user_name == '' && $password == '')
	{
		return "false@@@<b>".$mod_strings['LBL_CHECK_MAILID']."</b>";
	}
	elseif($isactive == 0)
        {
                return "false@@@<b>".$mod_strings['LBL_LOGIN_REVOKED']."</b>";
        }
	elseif(!$mail->Send())
	{
		return "false@@@<b>".$mod_strings['LBL_MAIL_COULDNOT_SENT']."</b>";
	}
	else
		return "true@@@<b>".$mod_strings['LBL_MAIL_SENT']."</b>";

}

/**	function used to get the ticket creater 
 *	@param int $ticketid - ticket id
 *	return int $creator - ticket created user id will be returned ie., smcreatorid from crmentity table
 */
function get_ticket_creator($ticketid)
{
	global $adb;

	$res = $adb->pquery("select smcreatorid from vtiger_crmentity where crmid=?", array($ticketid));
	$creator = $adb->query_result($res,0,'smcreatorid');

	return $creator;
}

/**	function used to get the picklist values
 *	@param string $picklist_name - picklist name you want to retrieve from database
 *	return array $picklist_array - all values of the corresponding picklist will be returned as a array
 */
function get_picklists($picklist_name)
{
	global $adb, $log;
	$log->debug("Entering into function get_picklists($picklist_name)");
	
	$picklist_name = mysql_real_escape_string($picklist_name);
	$picklist_array = Array();

	$res = $adb->pquery("select * from vtiger_". $picklist_name, array());
	for($i=0;$i<$adb->num_rows($res);$i++)
	{
		$picklist_val = $adb->query_result($res,$i,$picklist_name);
		$picklist_array[$i] = $picklist_val;
	}

	$log->debug("Exit from function get_picklists($picklist_name)");
	return $picklist_array;
}

/**	function to get the attachments of a ticket
 *	@param int $userid - customer id
 *	@param int $ticketid - ticket id
 *	return array $output - This will return all the file details related to the ticket
 */
function get_ticket_attachments($userid,$ticketid)
{

	global $adb;

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
 *	@param int $contactid - customer ie., contact id 
 *	@param int $fileid - id of the file to which we want contents
 *	@param string $filename - name of the file to which we want contents
 *	return $filecontents array with single file contents like [fileid] => filecontent
 */
function get_filecontent($contactid, $fileid, $filename)
{
	global $adb;
	$query = "select vtiger_attachments.path from vtiger_troubletickets 
		inner join vtiger_seattachmentsrel on vtiger_seattachmentsrel.crmid = vtiger_troubletickets.ticketid 
		inner join vtiger_attachments on vtiger_attachments.attachmentsid = vtiger_seattachmentsrel.attachmentsid 
		where 	vtiger_troubletickets.parent_id=?  and 
			vtiger_attachments.attachmentsid= ? and 
			vtiger_attachments.name=?";
	$res = $adb->pquery($query, array($contactid, $fileid, $filename));

	if($adb->num_rows($res)>0)
	{
		$filenamewithpath = $adb->query_result($res,0,'path').$fileid."_".$filename;
		$filecontents[$fileid] = base64_encode(file_get_contents($filenamewithpath));
		$adb->println("Going to return the content of the file ==> $filenamewithpath");
	}
	return $filecontents;
}

/**	function to add attachment for a ticket ie., the passed contents will be write in a file and the details will be stored in database
 *	@param int $ticketid - ticket id
 *	@param string $filename - file name to be attached with the ticket
 *	@param string $filetype - file type
 *	@param int $filesize - file size
 *	@param string $filecontents - file contents as base64 encoded format
 *	return void
 */
function add_ticket_attachment($ticketid, $filename, $filetype, $filesize, $filecontents)
{
	global $adb;
	global $root_directory;

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

/* Begin the HTTP listener service and exit. */ 
$server->service($HTTP_RAW_POST_DATA); 

exit(); 

?>
