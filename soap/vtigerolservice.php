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
require_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('include/nusoap/nusoap.php');

$log = &LoggerManager::getLogger('vtigerolservice');

error_reporting(0);

$NAMESPACE = 'http://www.vtigercrm.com/vtigercrm';

$server = new soap_server;

$server->configureWSDL('vtigerolservice');

//ContactDetails SOAP Structure
$server->wsdl->addComplexType(
    'contactdetail',
    'complexType',
    'struct',
    'all',
    '',
    array(
	   'id' => array('name'=>'id','type'=>'xsd:string'),
        'title' => array('name'=>'title','type'=>'xsd:string'),
        'firstname' => array('name'=>'firstname','type'=>'xsd:string'),
        'middlename' => array('name'=>'middlename','type'=>'xsd:string'),
        'lastname' => array('name'=>'lastname','type'=>'xsd:string'),
        'birthdate'=> array('name'=>'birthdate','type'=>'xsd:string'),
        'emailaddress' => array('name'=>'emailaddress','type'=>'xsd:string'),
        'jobtitle'=> array('name'=>'jobtitle','type'=>'xsd:string'),
        'department'=> array('name'=>'department','type'=>'xsd:string'),
        'accountname' => array('name'=>'accountname','type'=>'xsd:string'),
        'officephone'=> array('name'=>'officephone','type'=>'xsd:string'),
        'homephone'=> array('name'=>'homephone','type'=>'xsd:string'),
        'otherphone'=> array('name'=>'otherphone','type'=>'xsd:string'),
        'fax'=> array('name'=>'fax','type'=>'xsd:string'),
        'mobile'=> array('name'=>'mobile','type'=>'xsd:string'),
        'asstname'=> array('name'=>'asstname','type'=>'xsd:string'),
        'asstphone'=> array('name'=>'asstphone','type'=>'xsd:string'),
        'reportsto'=> array('name'=>'reportsto','type'=>'xsd:string'),
        'mailingstreet'=> array('name'=>'mailingstreet','type'=>'xsd:string'),
        'mailingcity'=> array('name'=>'mailingcity','type'=>'xsd:string'),
        'mailingstate'=> array('name'=>'mailingstate','type'=>'xsd:string'),
        'mailingzip'=> array('name'=>'mailingzip','type'=>'xsd:string'),
        'mailingcountry'=> array('name'=>'mailingcountry','type'=>'xsd:string'),
        'otherstreet'=> array('name'=>'otherstreet','type'=>'xsd:string'),
        'othercity'=> array('name'=>'othercity','type'=>'xsd:string'),
        'otherstate'=> array('name'=>'otherstate','type'=>'xsd:string'),
        'otherzip'=> array('name'=>'otherzip','type'=>'xsd:string'),
        'othercountry'=> array('name'=>'othercountry','type'=>'xsd:string'),
        'description'=> array('name'=>'description','type'=>'xsd:string'),
        'category'=> array('name'=>'category','type'=>'xsd:string'),
    )
);

$server->wsdl->addComplexType(
    'contactdetails',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:contactdetail[]')
    ),
    'tns:contactdetail'
);

$server->wsdl->addComplexType(
    'taskdetail',
    'complexType',
    'struct',
    'all',
    '',
    array(
			'id'=>array('name'=>'id','type'=>'xsd:string'),
			'subject'=>array('name'=>'subject','type'=>'xsd:string'),
			'startdate'=>array('name'=>'startdate','type'=>'xsd:string'),
			'duedate'=>array('name'=>'duedate','type'=>'xsd:string'),
			'status'=> array('name'=>'status','type'=>'xsd:string'),
			'priority'=>array('name'=>'priority','type'=>'xsd:string'),
			'description'=>array('name'=>'description','type'=>'xsd:string'),
			'contactname'=>array('name'=>'contactname','type'=>'xsd:string'),
			'category'=>array('name'=>'category','type'=>'xsd:string'),
		  )
);

$server->wsdl->addComplexType(
    'taskdetails',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:taskdetail[]')
    ),
    'tns:taskdetail'
);

$server->wsdl->addComplexType(
    'clndrdetail',
    'complexType',
    'struct',
    'all',
    '',
    array(
          'id'=>array('name'=>'id','type'=>'xsd:string'),
          'subject'=>array('name'=>'subject','type'=>'xsd:string'),
          'startdate'=>array('name'=>'startdate','type'=>'xsd:string'),
          'duedate'=>array('name'=>'duedate','type'=>'xsd:string'),
          'location'=> array('name'=>'location','type'=>'xsd:string'),
          'description'=>array('name'=>'description','type'=>'xsd:string'),
          'contactname'=>array('name'=>'contactname','type'=>'xsd:string'),
          'category'=>array('name'=>'category','type'=>'xsd:string'),
        )
);

$server->wsdl->addComplexType(
    'clndrdetails',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:clndrdetail[]')
    ),
    'tns:clndrdetail'
);

$server->wsdl->addComplexType(
    'emailmsgdetail',
    'complexType',
    'struct',
    'all',
    '',
    array(
          'subject'=>array('name'=>'subject','type'=>'xsd:string'),
          'body'=>array('name'=>'body','type'=>'xsd:string'),
          'datesent'=>array('name'=>'datesent','type'=>'xsd:string'),
         )
);


$server->register(
    'LoginToVtiger',
    array('userid'=>'xsd:string','password'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

$server->register(
    'SearchContactsByEmail',
    array('username'=>'xsd:string','emailaddress'=>'xsd:string'),
    array('return'=>'tns:contactdetails'),
    $NAMESPACE);

$server->register(
    'AddMessageToContact',
    array('username'=>'xsd:string','contactid'=>'xsd:string','msgdtls'=>'tns:emailmsgdetail'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

$server->register(
    'AddEmailAttachment',
    array('emailid'=>'xsd:string','filedata'=>'xsd:string',
					'filename'=>'xsd:string','filesize'=>'xsd:string','filetype'=>'xsd:string',
					'username'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

//For Contacts Sync
$server->register(
		'GetContacts',
    array('username'=>'xsd:string'),
    array('return'=>'tns:contactdetails'),
    $NAMESPACE);

$server->register(
   'AddContacts',
    array('username'=>'xsd:string','cntdtls'=>'tns:contactdetails'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

$server->register(
   'UpdateContacts',
    array('username'=>'xsd:string','cntdtls'=>'tns:contactdetails'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

$server->register(
   'DeleteContacts',
    array('username'=>'xsd:string','crmid'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);   
//End for Contacts Sync

//For Tasks Sync
$server->register(
		'GetTasks',
    array('username'=>'xsd:string'),
    array('return'=>'tns:taskdetails'),
    $NAMESPACE);

$server->register(
   'AddTasks',
    array('username'=>'xsd:string','taskdtls'=>'tns:taskdetails'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

$server->register(
   'UpdateTasks',
    array('username'=>'xsd:string','taskdtls'=>'tns:taskdetails'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

$server->register(
   'DeleteTasks',
    array('username'=>'xsd:string','crmid'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE); 
//End for Tasks Sync

//For Calendar Sync
$server->register(
		'GetClndr',
    array('username'=>'xsd:string'),
    array('return'=>'tns:clndrdetails'),
    $NAMESPACE);

$server->register(
   'AddClndr',
    array('username'=>'xsd:string','clndrdtls'=>'tns:clndrdetails'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

$server->register(
   'UpdateClndr',
    array('username'=>'xsd:string','clndrdtls'=>'tns:clndrdetails'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

$server->register(
   'DeleteClndr',
    array('username'=>'xsd:string','crmid'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE); 
//End for Calendar Sync

function SearchContactsByEmail($username,$emailaddress)
{
     require_once('modules/Contacts/Contact.php');
     
     $seed_contact = new Contact();
     $output_list = Array();
     
     $response = $seed_contact->get_searchbyemailid($username,$emailaddress);
     $contactList = $response['list'];
     
     // create a return array of names and email addresses.
     foreach($contactList as $contact)
     {
          $output_list[] = Array(
               "id" => $contact[id],
               "firstname" => $contact[first_name],
               "lastname" => $contact[last_name],
               "accountname" => $contact[account_name],
               "emailaddress" => $contact[email1],
          );
     }
     
     //to remove an erroneous compiler warning
     $seed_contact = $seed_contact;
     return $output_list;
}    

function AddMessageToContact($username,$contactid,$msgdtls)
{
	//global $log;
     require_once('modules/Users/User.php');
	require_once('modules/Emails/Email.php');
	
	$seed_user = new User();
	$user_id = $seed_user->retrieve_user_id($username);
	
	foreach($msgdtls as $msgdtl)
	{
     	if(isset($msgdtl))
     	{    
          	$email = new Email();
               //$log->debug($msgdtls['contactid']);
          	$email_body = str_replace("'", "''", $msgdtl['body']);
          	$email_subject = str_replace("'", "''",$msgdtl['subject']);
          	$date_sent = getDisplayDate($msgdtl['datesent']);
                
          	$email->column_fields[subject] = $email_subject;
          	$email->column_fields[assigned_user_id] = $user_id;
          	$email->column_fields[date_start] = $date_sent;
          	$email->column_fields[description]  = $email_body;
          
          	$email->save("Emails");
          
          	$email->set_emails_contact_invitee_relationship($email->id,$contactid);
          	$email->set_emails_se_invitee_relationship($email->id,$contactid);
          	$email->set_emails_user_invitee_relationship($email->id,$user_id);
          	
          	return $email->id;
          }
          else
          {
               return "";
          }
     }
}

function LoginToVtiger($userid,$password)
{
	global $adb;
	require_once('modules/Users/User.php');
	
	$return_access = "FALSE";
	
	$objuser = new User();
	
	if($password != "")
	{
		$objuser->user_name = $userid;
		$objuser->load_user($password);
		if($objuser->is_authenticated())
		{
			$return_access = "TRUE";
		}else
		{
				$return_access = "FALSE";
		}
	}else
	{
			//$server->setError("Invalid username and/or password");
			$return_access = "FALSE";
	}
	$objuser = $objuser;
	return $return_access;
}

function AddEmailAttachment($emailid,$filedata,$filename,$filesize,$filetype,$username)
{
     global $adb;
     require_once('modules/Users/User.php');
     $date_var = date('YmdHis');
     
     $seed_user = new User();
     $user_id = $seed_user->retrieve_user_id($username);
     	
     $crmid = $adb->getUniqueID("crmentity");
     
     $sql1 = "insert into crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime) 
     values(".$crmid.",".$user_id.",".$user_id.",'Emails Attachment',' ',".$adb->formatString("crmentity","createdtime",$date_var).",".$adb->formatString("crmentity","modifiedtime",$date_var).")";
     
     $entityresult = $adb->query($sql1);	
     $filetype="application/octet-stream";
     
     if($entityresult != false)
     {
     $sql2="insert into attachments(attachmentsid, name, description, type, attachmentsize, attachmentcontents) 
     values(".$crmid.",'".$filename."',' ','".$filetype."','".$filesize."','".$adb->getEmptyBlob()."')";
     
     $result=$adb->query($sql2);
     
     if($result != false)
     {
     $result = $adb->updateBlob('attachments','attachmentcontents',"attachmentsid='".$crmid."' and name='".$filename."'",addslashes($filedata));
     }
     
     $sql3='insert into seattachmentsrel values('.$emailid.','.$crmid.')';
     $adb->query($sql3);
     
     return $crmid;   
   }
   else
   {
   		 //$server->setError("Invalid username and/or password"); 
          return "";
   }
}

function GetContacts($username)
{
    global $adb;
    require_once('modules/Contacts/Contact.php');
     
    $seed_contact = new Contact();
    $output_list = Array();
     
    $query = $seed_contact->get_contactsforol($username);
    $result = $adb->query($query);
    
    while($contact = $adb->fetch_array($result))
  	{
        if($contact["birthdate"] == "0000-00-00")
        {
	        	$contact["birthdate"] = "";
        }
        if($contact["salutation"] == "--None--")
        {
          	$contact["salutation"] = "";
        }
        
        $namelist = explode(" ", $contact["last_name"]);
        if(isset($namelist))
        {
        	if(count($namelist) >= 2) 
			{
				$contact["last_name"] = $namelist[count($namelist)-1];       	
				for($i=0; $i<count($namelist)-2; $i++)
				{
					$middlename[] = $namelist[$i];
				}
				if(isset($middlename))
				{
					$middlename = implode(" ",$middlename);
				}
			}
        }

		$output_list[] = Array(
				"id" => $contact["id"],
				"title" => $contact["salutation"],
				"firstname" => $contact["first_name"],
				"middlename" => trim($middlename),
				"lastname" => trim($contact["last_name"]),
				"birthdate" => $contact["birthdate"],
				"emailaddress" => $contact["email"],
				"jobtitle" => $contact["title"],
				"department" => $contact["department"],
				"accountname" => $contact["account_name"],                         
				"officephone" => $contact["phone"],
				"homephone" => $contact["homephone"],
				"otherphone" => $contact["otherphone"],           
				"fax" => $contact["fax"],
				"mobile" => $contact["mobile"],
				"asstname" => $contact["assistant_name"],
				"asstphone" => $contact["assistantphone"],             
				"reportsto" => $contact["reports_to_name"],
				"mailingstreet" => $contact["mailingstreet"],
				"mailingcity" => $contact["mailingcity"],
				"mailingstate" => $contact["mailingstate"],
				"mailingzip" => $contact["mailingzip"],
				"mailingcountry" => $contact["mailingcountry"],              
				"otherstreet" => $contact["otherstreet"],
				"othercity" => $contact["othercity"],
				"otherstate" => $contact["otherstate"],
				"otherzip" => $contact["otherzip"],
				"othercountry" => $contact["othercountry"],
				"description" => "",
				"category" => "",        
			  	);
  	}
  	
    //to remove an erroneous compiler warning
    $seed_contact = $seed_contact;
    return $output_list;
}

function AddContacts($username,$cntdtls)
{
	global $adb;
	global $current_user;
	require_once('modules/Users/User.php');
	require_once('modules/Contacts/Contact.php');
	
	$seed_user = new User();
	$user_id = $seed_user->retrieve_user_id($username);
	$current_user = $seed_user;
	$current_user->retrieve($user_id);
	
	$contact = new Contact();
	
	foreach($cntdtls as $cntrow)
	{
      if(isset($cntrow))
      {
  			$contact->column_fields[salutation]=$cntrow["title"];		
      	$contact->column_fields[firstname]=$cntrow["firstname"];
      	if($cntrow["middlename"] != "")
      	{
      		$contact->column_fields[lastname]=$cntrow["middlename"]." ".$cntrow["lastname"];
      	}else
      	{
      		$contact->column_fields[lastname]=$cntrow["lastname"];
      	}
      	$contact->column_fields[birthday]= getDisplayDate($cntrow["birthdate"]);
      	$contact->column_fields[email]=$cntrow["emailaddress"];
      	$contact->column_fields[title]=$cntrow["jobtitle"];
      	$contact->column_fields[department]=$cntrow["department"];
      	$contact->column_fields[account_id]= retrieve_account_id($cntrow["accountname"],$user_id);
          $contact->column_fields[phone]= $cntrow["officephone"];
          $contact->column_fields[homephone]= $cntrow["homephone"];
          $contact->column_fields[otherphone]= $cntrow["otherphone"];
          $contact->column_fields[fax]= $cntrow["fax"];
      	$contact->column_fields[mobile]=$cntrow["mobile"];
      	$contact->column_fields[assistant]= $cntrow["asstname"];
          $contact->column_fields[assistantphone]= $cntrow["asstphone"];     
      	//$contact->column_fields[reports_to_id] =retrievereportsto($reportsto,$user_id,$account_id);// NOT FIXED IN SAVEENTITY.PHP
      	$contact->column_fields[mailingstreet]=$cntrow["mailingstreet"];
      	$contact->column_fields[mailingcity]=$cntrow["mailingcity"];
      	$contact->column_fields[mailingstate]=$cntrow["mailingstate"];
      	$contact->column_fields[mailingzip]=$cntrow["mailingzip"];
      	$contact->column_fields[mailingcountry]=$cntrow["mailingcountry"];    
      	$contact->column_fields[otherstreet]=$cntrow["otherstreet"];
      	$contact->column_fields[othercity]=$cntrow["othercity"];
      	$contact->column_fields[otherstate]=$cntrow["otherstate"];
      	$contact->column_fields[otherzip]=$cntrow["otherzip"];
      	$contact->column_fields[othercountry]=$cntrow["othercountry"];    	
          $contact->column_fields[assigned_user_id]=$user_id;   
  		$contact->column_fields[description]= $cntrow["description"];
       	$contact->save("Contacts");	
			}	
	}
	$contact = $contact;	
	return $contact->id;
}

function UpdateContacts($username,$cntdtls)
{
	global $adb;
	global $current_user;
	require_once('modules/Users/User.php');
	require_once('modules/Contacts/Contact.php');
	
	$seed_user = new User();
	$user_id = $seed_user->retrieve_user_id($username);
	$current_user = $seed_user;
	$current_user->retrieve($user_id);
	
	$contact = new Contact();
	
	foreach($cntdtls as $cntrow)
	{
      if(isset($cntrow))
      {
				$contact->retrieve_entity_info($cntrow["id"],"Contacts");
  			$contact->column_fields[salutation]=$cntrow["title"];		
      	$contact->column_fields[firstname]=$cntrow["firstname"];
      	if($cntrow["middlename"] != "")
      	{
      		$contact->column_fields[lastname]=$cntrow["middlename"]." ".$cntrow["lastname"];
      	}else
      	{
      		$contact->column_fields[lastname]=$cntrow["lastname"];
      	}
      	$contact->column_fields[birthday]= getDisplayDate($cntrow["birthdate"]);
      	$contact->column_fields[email]=$cntrow["emailaddress"];
      	$contact->column_fields[title]=$cntrow["jobtitle"];
      	$contact->column_fields[department]=$cntrow["department"];
      	$contact->column_fields[account_id]= retrieve_account_id($cntrow["accountname"],$user_id);
        $contact->column_fields[phone]= $cntrow["officephone"];
        $contact->column_fields[homephone]= $cntrow["homephone"];
        $contact->column_fields[otherphone]= $cntrow["otherphone"];
        $contact->column_fields[fax]= $cntrow["fax"];
      	$contact->column_fields[mobile]=$cntrow["mobile"];
      	$contact->column_fields[assistant]= $cntrow["asstname"];
        $contact->column_fields[assistantphone]= $cntrow["asstphone"];     
      	//$contact->column_fields[reports_to_id] =retrievereportsto($reportsto,$user_id,$account_id);// NOT FIXED IN SAVEENTITY.PHP
      	$contact->column_fields[mailingstreet]=$cntrow["mailingstreet"];
      	$contact->column_fields[mailingcity]=$cntrow["mailingcity"];
      	$contact->column_fields[mailingstate]=$cntrow["mailingstate"];
      	$contact->column_fields[mailingzip]=$cntrow["mailingzip"];
      	$contact->column_fields[mailingcountry]=$cntrow["mailingcountry"];    
      	$contact->column_fields[otherstreet]=$cntrow["otherstreet"];
      	$contact->column_fields[othercity]=$cntrow["othercity"];
      	$contact->column_fields[otherstate]=$cntrow["otherstate"];
      	$contact->column_fields[otherzip]=$cntrow["otherzip"];
      	$contact->column_fields[othercountry]=$cntrow["othercountry"];    	
        $contact->column_fields[assigned_user_id]=$user_id;   
  		  $contact->column_fields[description]= $cntrow["description"];
  		  $contact->id = $cntrow["id"];
  		  $contact->mode = "edit";
       	$contact->save("Contacts");	
			}	
	}	
	$contact = $contact;
	return $contact->id;
}

function DeleteContacts($username,$crmid)
{
  global $current_user;
	require_once('modules/Users/User.php');
	require_once('modules/Contacts/Contact.php');
	 
	$seed_user = new User();
	$user_id = $seed_user->retrieve_user_id($username);
	$current_user = $seed_user;
	$current_user->retrieve($user_id);
 
  $contact = new Contact();
  $contact->id = $crmid;
  $contact->mark_deleted($contact->id);
  
  $contact = $contact;
  return $contact->id;
}

function retrieve_account_id($account_name,$user_id)
{
    
		if($account_name=="")
    {
        return null;
    }

    $query = "select account.accountname accountname,account.accountid accountid from account inner join crmentity on crmentity.crmid=account.accountid where crmentity.deleted=0 and account.accountname='" .$account_name."'";


	  $db = new PearDatabase();
    $result=  $db->query($query) or die ("Not able to execute insert");
    
    $rows_count =  $db->getRowCount($result);
    if($rows_count==0)
    {
    	require_once('modules/Accounts/Account.php');
	    $account = new Account();
    	$account->column_fields[accountname] = $account_name;
    	$account->column_fields[assigned_user_id]=$user_id;
    	//$account->saveentity("Accounts");
    	$account->save("Accounts");
        //mysql_close();
    	return $account->id;
    }
    else if ($rows_count==1)
    {
        $row = $db->fetchByAssoc($result, 0);
        //mysql_close();
        return $row["accountid"];	    
    }
    else
    {
        $row = $db->fetchByAssoc($result, 0);
        //mysql_close();
        return $row["accountid"];	    
    }
    
}

function GetTasks($username)
{
	global $adb;
	require_once('modules/Activities/Activity.php');
		
	$seed_task = new Activity();
    $output_list = Array();
  
    $query = $seed_task->get_tasksforol($username);
    $result = $adb->query($query);
    
    while($task = $adb->fetch_array($result))
  	{
  		if($task["startdate"] == "0000-00-00" || $task["startdate"] == NULL)
        {
	       	$task["startdate"] = "";
        }
        if($task["duedate"] == "0000-00-00" || $task["duedate"] == NULL)
        {
	       	$task["duedate"] = "";
        }
        
        if($task["status"] == "Not Started")
        {
       		$task["status"] = "0";
        }else if($task["status"] == "In Progress")
        {
        	$task["status"] = "1";
        }else if($task["status"] == "Completed")
        {
        	$task["status"] = "2";
        }else if($task["status"] == "Deferred")
        {
        	$task["status"] = "4";
        }else if($task["status"] == "Pending Input" || $task["status"] == "Planned")
        {
        	$task["status"] = "3";
        }else
        {
        	$task["status"] = "0";
        }
        
        if($task["priority"] == "High")
        {
       		$task["priority"] = "2";
        }else if($task["priority"] == "Low")
        {
        	$task["priority"] = "0";
        }else if($task["priority"] == "Medium")
        {
        	$task["priority"] = "1";
        }
        
		$output_list[] = Array(
						"id" => $task["taskid"],
						"subject" => $task["subject"],
						"startdate" => $task["startdate"],
						"duedate" => $task["duedate"],
						"status" => $task["status"],
						"priority" => $task["priority"],
						"description" => $task["description"],
						"contactname" => $task["firstname"]." ".$task["lastname"],
						"category" => "",        
						);
  	}
	$seed_task = $seed_task;
	return $output_list;
}

function AddTasks($username,$taskdtls)
{
	global $current_user;
	require_once('modules/Users/User.php');
	require_once('modules/Activities/Activity.php');
	
	$seed_user = new User();
	$user_id = $seed_user->retrieve_user_id($username);
	$current_user = $seed_user;
	$current_user->retrieve($user_id);
	
	$task = new Activity();
	
	foreach($taskdtls as $taskrow)
	{
      	if(isset($taskrow))
      	{
			if($taskrow["status"] == "0")
			{
				$taskrow["status"] = "Not Started";
			}else if($taskrow["status"] == "1")
			{
				$taskrow["status"] = "In Progress";
			}else if($taskrow["status"] == "2")
			{
				$taskrow["status"] = "Completed";
			}else if($taskrow["status"] == "4")
			{
				$taskrow["status"] = "Deferred";
			}else if($taskrow["status"] == "3")
			{
				$taskrow["status"] = "Planned";
			}else
			{
				$taskrow["status"] = "Not Started";
			}

			if($taskrow["priority"] == "2")
			{
				$taskrow["priority"] = "High";
			}else if($taskrow["priority"] == "0")
			{
				$taskrow["priority"] = "Low";
			}else if($taskrow["priority"] == "1")
			{
				$taskrow["priority"] = "Medium";
			}

			$task->column_fields[subject] = $taskrow["subject"];
			$task->column_fields[date_start]=getDisplayDate($taskrow["startdate"]);
			$task->column_fields[due_date]=getDisplayDate($taskrow["duedate"]);         
			$task->column_fields[taskstatus]=$taskrow["status"];
			$task->column_fields[taskpriority]=$taskrow["priority"];
			$task->column_fields[description]=$taskrow["description"];
			$task->column_fields[activitytype]="Task";
			//$task->column_fields[contact_id]= retrievereportsto($contact_name,$user_id,null); 
			$task->column_fields[assigned_user_id]=$user_id;
			$task->save("Activities");
		}
	}
	return $task->id;
}

function UpdateTasks($username,$taskdtls)
{
	global $current_user;
	require_once('modules/Users/User.php');
	require_once('modules/Activities/Activity.php');
	
	$seed_user = new User();
	$user_id = $seed_user->retrieve_user_id($username);
	$current_user = $seed_user;
	$current_user->retrieve($user_id);
	
	$task = new Activity();
	
	foreach($taskdtls as $taskrow)
	{
      if(isset($taskrow))
      {
			if($taskrow["status"] == "0")
			{
				$taskrow["status"] = "Not Started";
			}else if($taskrow["status"] == "1")
			{
				$taskrow["status"] = "In Progress";
			}else if($taskrow["status"] == "2")
			{
				$taskrow["status"] = "Completed";
			}else if($taskrow["status"] == "4")
			{
				$taskrow["status"] = "Deferred";
			}else if($taskrow["status"] == "3")
			{
				$taskrow["status"] = "Planned";
			}else
			{
				$taskrow["status"] = "Not Started";
			}
        
    		if($taskrow["priority"] == "2")
            {
           		$taskrow["priority"] = "High";
            }else if($taskrow["priority"] == "0")
            {
            	$taskrow["priority"] = "Low";
            }else if($taskrow["priority"] == "1")
            {
            	$taskrow["priority"] = "Medium";
            }
					
			$task->retrieve_entity_info($taskrow["id"],"Activities");
			$task->column_fields[subject] = $taskrow["subject"];
			$task->column_fields[date_start] = getDisplayDate($taskrow["startdate"]);
			$task->column_fields[due_date] = getDisplayDate($taskrow["duedate"]);         
			$task->column_fields[taskstatus] = $taskrow["status"];
			$task->column_fields[taskpriority] = $taskrow["priority"];
			$task->column_fields[description] = $taskrow["description"];
			$task->column_fields[activitytype] = "Task";
			//$task->column_fields[contact_id]= retrievereportsto($contact_name,$user_id,null); 
			$task->column_fields[assigned_user_id] = $user_id;

			$task->id = $taskrow["id"];
			$task->mode="edit";

			$task->save("Activities");
      }
  }
	return $task->id;
}

function DeleteTasks($username,$crmid)
{
	global $current_user;
	require_once('modules/Users/User.php');
	require_once('modules/Activities/Activity.php');
	   
	$seed_user = new User();
	$user_id = $seed_user->retrieve_user_id($username);
	$current_user = $seed_user;
	$current_user->retrieve($user_id);

	$task = new Activity();
	$task->id = $crmid;
	$task->mark_deleted($task->id);
	return $task->id;     
}

function GetClndr($username)
{
	global $adb;
	require_once('modules/Activities/Activity.php');

	$seed_clndr = new Activity();
	$output_list = Array();

	$query = $seed_clndr->get_calendarsforol($username);
	$result = $adb->query($query);
    
    while($clndr = $adb->fetch_array($result))
  	{
  		if($clndr["startdate"] == "0000-00-00" || $clndr["startdate"] == NULL)
        {
	        	$clndr["startdate"] = "";
        }
        if($clndr["duedate"] == "0000-00-00" || $clndr["duedate"] == NULL)
        {
	        	$clndr["duedate"] = "";
        }
		
	   //this seperates the $$clndr["startdate"] into an array - YYYY-MM-DD
	   $expldstartdate = explode("-", $clndr["startdate"]);
	   $expldtimestart = explode(":", $clndr["startime"]);	

	   $expldduedate = explode("-", $clndr["duedate"]);

	   //this makes a timestamp out of the exploded date this number is in seconds
	   $startdtm = mktime($expldtimestart[0], $expldtimestart[1], 0, $expldstartdate[1], $expldstartdate[2], $expldstartdate[0]);
		
       $duedtm = mktime($expldtimestart[0]+$clndr["duehours"], $expldtimestart[1]+$clndr["dueminutes"], 0, $expldduedate[1], $expldduedate[2], $expldduedate[0]);
       
	   $clndr["startdate"] = date("Y-m-d H:i:s", $startdtm);
	   $clndr["duedate"] = date("Y-m-d H:i:s", $duedtm);

	   $output_list[] = Array(
						"id" => $clndr["clndrid"],
						"subject" => $clndr["subject"],
						"startdate" => $clndr["startdate"],
						"duedate" => $clndr["duedate"],
						"location" => $clndr["location"],
						"description" => $clndr["description"],
						"contactname" => $clndr["firstname"]." ".$clndr["lastname"],
						"category" => "",        
						);
  	}
	$seed_clndr = $seed_clndr;
	return $output_list;
}

function AddClndr($username,$clndrdtls)
{
	global $current_user;
	require_once('modules/Users/User.php');
	require_once('modules/Activities/Activity.php');
	
	$seed_user = new User();
	$user_id = $seed_user->retrieve_user_id($username);
	$current_user = $seed_user;
	$current_user->retrieve($user_id);
	
	$clndr = new Activity();
	
	foreach($clndrdtls as $clndrow)
	{
      if(isset($clndrow))
      {
			$astartdtm = explode(" ",$clndrow["startdate"]);
			$aduedtm = explode(" ",$clndrow["duedate"]);
			
			$atimestart = explode(":",trim($astartdtm[1]));
			$atimedue = explode(":",trim($aduedtm[1]));

			$stimestart = $atimestart[0].":".$atimestart[1];
			$stimeend = $atimedue[0].":".$atimedue[1];
		
			if( $diff=@get_time_difference($stimestart, $stimeend) )
			{
				$stimeduehr = sprintf('%02d',$diff['hours']);
				$stimeduemin = sprintf('%02d',$diff['minutes']);
			}

			$clndr->column_fields[subject] = $clndrow["subject"];
			$clndr->column_fields[date_start]=getDisplayDate(trim($astartdtm[0]));
			$clndr->column_fields[due_date]=getDisplayDate(trim($aduedtm[0])); 
			$clndr->column_fields[time_start]=$stimestart;
			$clndr->column_fields[duration_hours]=$stimeduehr;        
			$clndr->column_fields[duration_minutes]=$stimeduemin;          
			$clndr->column_fields[location]=$clndrow["location"];
			$clndr->column_fields[description]=$clndrow["description"];
			$clndr->column_fields[activitytype]="Meeting";
			$clndr->column_fields[assigned_user_id]=$user_id;
			$clndr->save("Activities");
      }
  }
	return $clndr->id;
}

function UpdateClndr($username,$clndrdtls)
{
	global $current_user;
	global $adb;
	require_once('modules/Users/User.php');
	require_once('modules/Activities/Activity.php');
	
	$seed_user = new User();
	$user_id = $seed_user->retrieve_user_id($username);
	$current_user = $seed_user;
	$current_user->retrieve($user_id);
	
	$clndr = new Activity();
	
	foreach($clndrdtls as $clndrow)
	{
      if(isset($clndrow))
      {
			$astartdtm = explode(" ",$clndrow["startdate"]);
			$aduedtm = explode(" ",$clndrow["duedate"]);
			
			$atimestart = explode(":",trim($astartdtm[1]));
			$atimedue = explode(":",trim($aduedtm[1]));

			$stimestart = $atimestart[0].":".$atimestart[1];
			$stimeend = $atimedue[0].":".$atimedue[1];
		
			if( $diff=@get_time_difference($stimestart, $stimeend) )
			{
				$stimeduehr = sprintf('%02d',$diff['hours']);
				$stimeduemin = sprintf('%02d',$diff['minutes']);
			}

			$clndr->retrieve_entity_info($clndrow["id"],"Activities");
			$clndr->column_fields[subject] = $clndrow["subject"];
			$clndr->column_fields[date_start]=getDisplayDate(trim($astartdtm[0]));
			$clndr->column_fields[due_date]=getDisplayDate(trim($aduedtm[0])); 
			$clndr->column_fields[time_start]=$stimestart;
			$clndr->column_fields[duration_hours]=$stimeduehr;       
			$clndr->column_fields[duration_minutes]=$stimeduemin;              
			$clndr->column_fields[location]=$clndrow["location"];
			$clndr->column_fields[description]=$clndrow["description"];
			$clndr->column_fields[activitytype]="Meeting";
			$clndr->column_fields[assigned_user_id]=$user_id;
			$clndr->id = $clndrow["id"];
			$clndr->mode="edit";
			$clndr->save("Activities");
      }
  }
	return $clndr->id;
}

function DeleteClndr($username,$crmid)
{
	global $current_user;
	require_once('modules/Users/User.php');
	require_once('modules/Activities/Activity.php');
	   
	$seed_user = new User();
	$user_id = $seed_user->retrieve_user_id($username);
	$current_user = $seed_user;
	$current_user->retrieve($user_id);

	$clndr = new Activity();
	$clndr->id = $crmid;
	$clndr->mark_deleted($clndr->id);
	return $clndr->id;     
}

//To find the Difference between time
function get_time_difference( $start, $end )
{
    $uts['start'] = strtotime( $start );
    $uts['end'] = strtotime( $end );
    if( $uts['start']!==-1 && $uts['end']!==-1 )
    {
        if( $uts['end'] >= $uts['start'] )
        {
            $diff    =    $uts['end'] - $uts['start'];
            if( $days=intval((floor($diff/86400))) )
                $diff = $diff % 86400;
            if( $hours=intval((floor($diff/3600))) )
                $diff = $diff % 3600;
            if( $minutes=intval((floor($diff/60))) )
                $diff = $diff % 60;
            $diff    =    intval( $diff );            
            return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
        }
    }
    return( false );
}

$server->service(utf8_encode($HTTP_RAW_POST_DATA)); 
exit();
?>
