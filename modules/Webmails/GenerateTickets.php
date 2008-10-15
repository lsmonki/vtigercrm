<?php
//set_time_limit(600);

require_once('config.inc.php');
require_once('include/utils/utils.php');
require_once('include/utils/CommonUtils.php');
require_once('include/logging.php');
require_once('include/utils/UserInfoUtil.php');
require_once('modules/HelpDesk/HelpDesk.php');
require_once('include/language/en_us.lang.php');
require_once('include/database/PearDatabase.php');
require_once('modules/Emails/class.phpmailer.php');

global $adb,$log,$default_charset,$current_user;
global $app_strings;
$date_var = date('YmdHis');

$focus = new HelpDesk();
// Retrieve SMTP server address
$sql="SELECT * FROM vtiger_systems WHERE server_type= 'email'";
$rs = $adb->query($sql);
$smtpserver = $adb->query_result($rs,0,'server');
$smtp_server_username = $adb->query_result($rs,0,'server_username');
$smtp_server_password = $adb->query_result($rs,0,'server_password');
$smtp_auth = $adb->query_result($rs,0,'smtp_auth');

//Start (code): kiran

$mailbox = array();

$mailboxquery = "select * from vtiger_mail_accounts";
$mailboxresult = $adb->query($mailboxquery);
$mailbox['server_name'] = $adb->query_result($mailboxresult,0,'mail_servername');
$mailbox['protocol'] = $adb->query_result($mailboxresult,0,'mail_protocol');
$mailbox['vtigeruserid'] = $adb->query_result($mailboxresult,0,'user_id');
$mailbox['username'] =  $adb->query_result($mailboxresult,0,'mail_username');
$mailbox['password'] = $adb->query_result($mailboxresult,0,'mail_password');
$mailbox['ssltype'] = $adb->query_result($mailboxresult,0,'ssltype');
$mailbox['sslmethod'] = $adb->query_result($mailboxresult,0,'sslmeth');

require_once('include/utils/encryption.php');
$oencrypt = new Encryption();
$mailbox[password] = $oencrypt->decrypt(trim($mailbox[password]));

// first we will try a regular old IMAP connection: 
if($mailbox[ssltype] == "")
{
	$mailbox[ssltype] = "notls";
} 
if($mailbox[sslmethod] == "")
{
	$mailbox[sslmethod] = "novalidate-cert";
} 

if($mailbox[protocol] == "pop3")
{
	$port = "110";
}
else
{
	if($mailbox[ssltype] == "tls" || $mailbox[ssltype] == "ssl")
		$port = "993";
	else
		$port = "143";
}

$imap = imap_open("{".$mailbox[server_name].":".$port."/".$mailbox[protocol]."/".$mailbox[ssltype]."/".$mailbox[sslmethod]."}INBOX",$mailbox[username],$mailbox[password]);

$unreadmessagesarr = imap_search($imap,'UNSEEN');

$mailboxinfo = imap_mailboxmsginfo($imap);

$nummails = $mailboxinfo->Nmsgs;

//start: loop though mails in mailbox
for($mailcount=0;$mailcount<$nummails;$mailcount++)
{
	$msg_number = $mailcount + 1;
	$mailheaderinfo = imap_headerinfo($imap,$msg_number);
	$mailfrom = $mailheaderinfo->from;
	foreach ($mailfrom as $id => $object)
	{
	    $fromaddress = $object->mailbox . "@" . $object->host;
	}
	
	$mailto = $mailheaderinfo->to;
	foreach ($mailto as $id => $object)
	{
	    $toaddress = $object->mailbox . "@" . $object->host;
	}
	
	$mailsubject = imap_utf8($mailheaderinfo->subject);
	if($mailsubject == '')
		$mailsubject = 'No Title'; //if no subject is given to the mail set the subject to 'No Title' since we are using subject of mail as the title of the ticket : kiran
	
	// get plain text
	$plainmailbody = get_part($imap, $msg_number, "TEXT/PLAIN");
	// get HTML text
	$htmlmailbody = get_part($imap, $msg_number, "TEXT/HTML");
	
	if ($htmlmailbody != "")
	{
		$mailbody = $htmlmailbody;
	}
	else
	{
	   	$mailbody = ereg_replace("\n","<br>",$plainmailbody);
	}
	
	$struct = imap_fetchstructure($imap,$msg_number);
	//echo '<pre>';print_r($struct->parts);echo '<pre>';die;
	$contentParts = count($struct->parts);
	$noofattachments = 0;
	$attachment = array();
	if ($contentParts > 1)
	{  
	   foreach ($struct->parts as $key=>$part)
	   {
			if ($part->disposition == "ATTACHMENT") // Check if an attachment exists
			{
				//get the file name from the structure
				$filename = $part->dparameters[0]->value;
				
				//start: get the content type
				$primarytypeid = $part->type;
				$primarytype = getPrimaryTypeName($primarytypeid);				
				$subtype = $part->subtype;				
				$contenttype = $primarytype.'/'.$subtype;
				//end				
				
				//get the content of file
				imap_savebody($imap,$fh,$msg_number);
				$filecontent = imap_fetchbody($imap, $msg_number, $key+1);
	            if( $struct->parts[$key]->encoding == 3 )
	            {
	            	$filecontent = base64_decode( $filecontent );
	            }
				
				$attachment[] = array($filename,$filecontent,$contenttype);
				$noofattachments++;
	        }      
	   }
	   
	}
	
	$userid= $mailbox[vtigeruserid];
	
	$accountid=findaccount($fromaddress);
	$contactid=findcontact($fromaddress);

	if($accountid=='' && $contactid=='')
	{
		// No contact found, so create a new one
		$contactid=createcontact($fromaddress);
		// Link contact to ticket
		if($ticketid!=''){
			$sql="INSERT INTO vtiger_seticketsrel SET crmid= ?,ticketid= ?";
			$rs = $adb->pquery($sql,array($contactid,$ticketid));
		}
	
		$parentid=$contactid;
	}	
	$parent_id = $contactid;

	// Determine if this is reply or not
	$oldticketid=fetchticketid($mailsubject); /* code postion changed by SAKTI on 20th jun, 2008 */
	
	$emailbody1 = brtonl($mailbody);
	$emailbody1 = html_entity_decode($emailbody1, ENT_QUOTES, $default_charset);
	$date = date('Y-m-d H:i:s');

	if($oldticketid != "" && (substr($mailsubject,0,15) == "RE: [ ".strtoupper($app_strings['Ticket ID'])) && in_array($msg_number,$unreadmessagesarr)) 
	{
		$query="INSERT INTO vtiger_ticketcomments SET ticketid= ?,createdtime= ?,comments= ?,ownertype= ?,ownerid= ?";
		$rs = $adb->pquery($query,array($oldticketid,$date,strip_tags(trimreply($emailbody1)),'customer',$parent_id));
		
		imap_setflag_full($imap, $msg_number, "\\Seen"); // set the message as read 	
	}
	
	//End (code): kiran
	// Create new ticket
	if($oldticketid=="")
	{
		//$ticketid=createentity();
		//$focus->id=$ticketid;
		// Retrieve userid for mailbox
		$userid= $mailbox[vtigeruserid];
	
		// Create entity
		//$crmid = $ticketid;
		$smcreatorid = 1;
		$smownerid = $mailbox[vtigeruserid];
		$modifiedby = 0;
		$setype = 'HelpDesk';
		//$description = addslashes($mailbody);
		$description = brtonl($mailbody);
		$description = html_entity_decode($description, ENT_QUOTES, $default_charset);	
		$description = strip_tags($description);	
		$focus->column_fields['assigned_user_id']=$smownerid;
		$focus->column_fields['ticketpriorities']='Low';
		$focus->column_fields['product_id']='';
		$focus->column_fields['parent_id'] = $parent_id;
		$focus->column_fields['ticketseverities']='Minor';
		$focus->column_fields['ticketstatus']='Open';
		$focus->column_fields['ticketcategories']='Big Problem';
		$focus->column_fields['update_log']='';
		$focus->column_fields['createdtime']='';
		$focus->column_fields['modifiedtime']='';
		$focus->column_fields['filename']='';
		$focus->column_fields['ticket_title']=addslashes($mailheaderinfo->subject);
		$focus->column_fields['description']=$description;
		$focus->column_fields['comments']='';
		$focus->column_fields['solution']='';
		
		$focus->save('HelpDesk');
		$ticketid=$focus->id;
		
		/*	$query = "INSERT INTO vtiger_crmentity VALUES (?,?,?,?,?,'".addslashes($description)."',?,?,?,?,?,?,?)";
		$result = $adb->pquery($query,array($crmid,$smcreatorid,$smownerid,$modifiedby,$setype,$adb->formatDate($date_var, true),$adb->formatDate($date_var, true),null,null,0,1,0));
		// Create ticket
		$query="INSERT INTO vtiger_troubletickets set ticketid='".$ticketid."',groupname='null',parent_id='',product_id='',priority='Normal',severity='Minor',status='Open',category='Other Problem',title='".addslashes($mailsubject)."',filename='',solution='',update_log='Ticket created from mail import.',version_id='null'";
		$result = $adb->pquery($query,array());
		// Create custom field
		$query="INSERT INTO vtiger_ticketcf SET ticketid= ?";
		$result = $adb->pquery($query,array($ticketid));*/
		
		$_REQUEST['server']=$smtpserver;
	
		// Send acknowledgement email to user that a ticket has been filed
		$ackemailsubject="[ ".$app_strings['Ticket ID']." : ".$ticketid." ] - ".$mailsubject;
		$hyperlink= $PORTAL_URL.'/index.php?action=index&module=Tickets&ticketid='.$ticketid.'&fun=detail';
		$ackemailbody="Received your issue and filed a ticket. We'll get back to you soon \n The link for the ticket is as given below \n ".$hyperlink; //kiran: TO DO - this message should be configurable
		$mail= new PHPMailer();
		$mail->IsSMTP();	// set mailer to use SMTP	
		$mail->From     = $mailbox[username];
		$mail->FromName = $mailbox[username];
		$mail->Host     = $smtpserver;
		//$mail->Mailer   = "smtp";
		$mail->Body     = $ackemailbody;
		$mail->AddAddress($fromaddress);
		$mail->Subject = $ackemailsubject;
		$mail->Username = $smtp_server_username ;	// SMTP username
		$mail->Password = $smtp_server_password ;	
		$mail->AddCustomHeader('ticketid: '.$ticketid);
		if($smtp_auth == 'true')
			$mail->SMTPAuth = true;
		else
		$mail->SMTPAuth = false;
		
		$mail->WordWrap = 50;                                 // set word wrap to 50 characters
	
		$mail->IsHTML(true);                                  // set email format to HTMl

		if(!MailSend($mail))
		{
			die('ack mail sending failed');
			$log->debug("Error sending customer alert email to: ".$fromaddress."<br>");
		}
		// Mark message for deletion
		imap_delete($imap,$msg_number);	
	}
	else
	{
		// This is a reply, so use the old ticketid as the current one
		$ticketid=$oldticketid;
	
		// Determine reply portion of body text
		$mailbody=trimreply($mailbody); /* '<hr size=1>' string removed from end by SAKTI on 20th Jun, 2008 */
	
		// Retrieve ticket ownerid
		$query="select vtiger_crmentity.smownerid from vtiger_crmentity inner join vtiger_troubletickets on vtiger_troubletickets.ticketid=vtiger_crmentity.crmid where vtiger_troubletickets.ticketid= ?";
		$rs = $adb->pquery($query,array($ticketid));
		$userid = $mailbox[vtigeruserid];
								
		$date=date('Y-m-d H:i:s'); /* Code added here after removing from below codes by SAKTI on 20th Jun, 2008 */
		// Create comments
			// Update log
			$day=date('l');
			$day='Customer replied on '.$day;
								
			$sql = "select update_log from vtiger_troubletickets where ticketid= ?";
			$rs = $adb->pquery($sql,array($ticketid));
			$row_log=$adb->fetch_array($rs);
			$update_log=$row_log[update_log].''.$day.''.$date.'--//--';
	
			$sql="UPDATE vtiger_troubletickets SET status= ?,update_log= ? where ticketid= ?";
			$rs = $adb->pquery($sql,array('Open',$update_log,$ticketid));
	}
							
	$parentid="";
	
	// Link ticket to account, if needed
	if($accountid != "")
	{
		$sql="SELECT * FROM vtiger_seticketsrel WHERE crmid = ? AND ticketid = ?";
		$rs = $adb->pquery($sql,array($accountid,$ticketid));
		$noofrows = $adb->num_rows($rs);
		if($noofrows==0)
		{
			// Link account to ticket
			$sql="INSERT INTO vtiger_seticketsrel SET crmid= ?,ticketid= ?";
			$rs = $adb->pquery($sql,array($accountid,$ticketid));
		}
		$parentid = $accountid;
	}
	
	// Link ticket to contact, if needed
	if($contactid!="")
	{
		$sql="SELECT * FROM vtiger_seticketsrel WHERE crmid= ? AND ticketid= ?";
		$rs= $adb->pquery($sql,array($contactid,$ticketid));
		$noofrows = $adb->num_rows($rs);
		if($noofrows==0)
		{
			// Link contact to ticket
			$sql="INSERT INTO vtiger_seticketsrel SET crmid= ?,ticketid= ?";
			$rs = $adb->pquery($sql,array($contactid,$ticketid));
		}
		$parentid=$contactid;
	}

							
	/* Start of code added by SAKTI on 2nd Jul, 2008 */					
							
	if($parentid!="")
	{
		// Create ticket
		$query="UPDATE vtiger_troubletickets SET parent_id= ? WHERE ticketid= ?";
		$rs = $adb->pquery($query,array($parentid,$ticketid));
	}
	
	// Save and link attachments
	$attachmentemailstr="";
	for($file=0;$file<sizeof($attachment);$file++)
	{
		$tmpfile=$attachment[$file][1];
		$filename=$attachment[$file][0];
		$mimetype=$attachment[$file][2];
		//if($isdebug==true){echo "Saving attachment ".$a." - ".$tmpfile." - ".$filename."<br>";}
		saveattachment($tmpfile,$filename,$mimetype,$ticketid);
		$attachmentemailstr.="* ".$filename."\n";
	}
	
	// Send notification email to CRM users
	$hyperlink= $site_URL.'/index.php?action=DetailView&module=HelpDesk&record='.$ticketid.'&parenttab=Support';
	//kiran: TO DO - above path should be changed
	
	if($oldticketid=="")
	{
		// This is a new ticket
		$emailsubject= $app_strings['LBL_NEW_TICKET']." Notification [ ".$app_strings['Ticket ID']." : ".$ticketid." ] - ".$mailsubject;
		$emailbody="";
		$emailbody.="---SENDER---<br>".$fromaddress."<br><br>";
		$emailbody.="---BODY---<br>".$mailbody."<br><br>";
		if(sizeof($attachment)>0)
		{
			$emailbody.="---ATTACHMENTS---<br>".$attachmentemailstr."<br>";
		}
		$emailbody.="---LINK---<br>".$hyperlink."<br><br>";
		
		$_REQUEST['server']=$smtpserver;
	
		$mail= new PHPMailer();
		$mail->IsSMTP();	// set mailer to use SMTP	
		$mail->From     = $mailbox[username];
		$mail->FromName = $mailbox[username];
		$mail->Host     = $smtpserver;
		//$mail->Mailer   = "smtp";
		$mail->Body     = $emailbody;
		$mail->AddAddress($mailbox[username]);
		$mail->Subject = $emailsubject;
		$mail->Username = $smtp_server_username ;	// SMTP username
		$mail->Password = $smtp_server_password ;	
		$mail->AddCustomHeader('ticketid: '.$ticketid);
		if($smtp_auth == 'true')
			$mail->SMTPAuth = true;
		else
		$mail->SMTPAuth = false;
		
		$mail->WordWrap = 50;                                 // set word wrap to 50 characters
	
		$mail->IsHTML(true);                                  // set email format to HTMl
			
		if(!MailSend($mail))
		{
			die('update mail sending failed');
			$log->debug("Error sending customer alert email to: ".$mailbox[username]."<br>");
		}
	}	
}
//end : loop through mails	

// Delete marked messages
imap_expunge($imap);

imap_close($imap);


function trimreply($thetext){
		$email=split("Original Message",$thetext);
		if (sizeof($email)>0) { 
			$thetext=$email[0];
			while(substr($thetext,-1)=="-" || substr($thetext,-1)==" " || substr($thetext,-1)==">"){
				$thetext=substr($thetext,0,strlen($thetext)-1);
			}

			return $thetext;
		} else { 
			return $thetext;
		}
}

function saveattachment($filecontent,$filename,$mimetype,$ticketid)
{
	global $mailbox,$userid,$adb,$date_var;
	
	// Create entity for attachment
	$attachmentid=createentity();

	$crmid = $attachmentid;
	$smcreatorid = 1;
	$smownerid = $userid;
	$modifiedby = 0;
	$setype = 'HelpDesk Attachment';
	$description = $filename;
	
	$query="INSERT INTO vtiger_crmentity VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
	$result = $adb->pquery($query,array($crmid,$smcreatorid,$smownerid,$modifiedby,$setype,$description,$adb->formatDate($date_var, true),$adb->formatDate($date_var, true),null,null,0,1,0));

	// Move and rename file to attachments directory
	$destfilename=str_replace(" ","-",$filename);
	if(!is_dir('storage/troubleticketattachments'))
	{
		//create new folder
		mkdir('storage/troubleticketattachments');
	}	
	$destfolder = 'storage/troubleticketattachments';
	$savepath=$destfolder."/".$attachmentid."_".$destfilename;
	if(!file_exists($savepath))
	{
		$fhandle = fopen($savepath,"wb");
		fwrite($fhandle,$filecontent);
		fclose($fhandle);
	}	
	//rename($filepath,$savepath);

	// Create attachment
	$sql="INSERT INTO vtiger_attachments SET attachmentsid= ?,name= ?,description= ?,type= ?,path= ?";
	$rs = $adb->pquery($sql,array($attachmentid,$destfilename,'Customer Uploaded',$mimetype,$destfolder.'/'));

	// Link attachment to ticket
	$sql="INSERT INTO vtiger_seattachmentsrel SET crmid= ?,attachmentsid = ?";
	$result = $adb->pquery($sql,array($ticketid,$attachmentid));
}

function createcontact($address)
{
	global $mailbox,$userid,$adb,$date_var;

	$contactid=createentity();

	// Create entity
	$crmid = $contactid;
	$smcreatorid = 1;
	$smownerid = $userid;
	$modifiedby = 0;
	$setype = 'Contacts';
	$description = '';
	print_r($current_user->id);
	$query="INSERT INTO vtiger_crmentity VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
	$result = $adb->pquery($query,array($crmid,$smcreatorid,$smownerid,$modifiedby,$setype,$description,$adb->formatDate($date_var, true),$adb->formatDate($date_var, true),null,null,0,1,0));	

	// Insert into contact details
	$sql="INSERT INTO vtiger_contactdetails SET email= ?,contactid= ?,firstname= ?,lastname= ?";
	$rs = $adb->pquery($sql,array($address,$contactid,'Unknown','Customer'));

	//insert into vtiger_contactscf
	$sql="INSERT INTO vtiger_contactscf SET contactid= ? ";
	$rs = $adb->pquery($sql,array($contactid));

	//insert into vtiger_contactaddress
	$sql="INSERT INTO vtiger_contactaddress SET contactaddressid= ?";
	$rs = $adb->pquery($sql,array($contactid));

	//insert into vtiger_contactsubdetails
	$sql="INSERT INTO vtiger_contactsubdetails  SET contactsubscriptionid= ?";
	$rs = $adb->pquery($sql,array($contactid));

    return $contactid;
}

function findcontact($address)
{
	global $adb;
	$contactid="";
	$sql="SELECT contactid FROM vtiger_contactdetails INNER JOIN vtiger_crmentity ON vtiger_contactdetails.contactid=vtiger_crmentity.crmid WHERE vtiger_contactdetails.email= ? AND vtiger_crmentity.deleted=0 group by vtiger_contactdetails.contactid";
	$rs = $adb->pquery($sql,array($address));
	
	$noofrows= $adb->num_rows($rs);
	if($noofrows>0)
	{
		$contactid=$adb->query_result($rs,0,"contactid");
	}
	return $contactid;
}

function findaccount($address)
{
	global $adb;
	$accountid="";
	$sql="SELECT accountid FROM vtiger_account INNER JOIN vtiger_crmentity ON vtiger_account.accountid=vtiger_crmentity.crmid WHERE vtiger_account.email1= ? AND vtiger_crmentity.deleted=0 group by vtiger_account.accountid";
	$rs = $adb->pquery($sql,array($address));
	$noofrows=$adb->num_rows($rs);
	if($noofrows>0)
	{
		$accountid=$adb->query_result($rs,0,"accountid");
	}
	return $accountid;
}

function createentity()
{
	global $adb;
	$query="SELECT id FROM vtiger_crmentity_seq";
	$rstemp = $adb->pquery($query,array());
	$nextcrmid=$adb->query_result($rstemp,0,"id");
	$newcrmid=$nextcrmid+1;

	$query="UPDATE vtiger_crmentity_seq SET id= ?";
	$rs=$adb->pquery($query,array($newcrmid));

	return $newcrmid;
}

function fetchticketid($subject)
{
	global $app_strings,$adb;
	$ticketid="";

	preg_match("/\[\sTICKET\sID\s:\s(?<digit>\d+)\s\\]/",$subject, $matches);
	if($matches[1]!="")
	{
		// Possibly a reply, found a Ticket ID in the subject
		$ticketid=$matches[1];

		// Check if ticket ID exists
		$query="SELECT * FROM vtiger_troubletickets INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid=vtiger_troubletickets.ticketid WHERE deleted=0 AND ticketid=$ticketid";
		$rs = $adb->pquery($query,array());
		$noofrows=$adb->num_rows($rs);
		if($noofrows==0)
		{
			$ticketid="";
		}
	}
	return $ticketid;
}
 
//Start: kiran
//Function to get the primary MIME type name based on the id
function getPrimaryTypeName($id)
{
	if($id == 0)
		$typename = 'TEXT';
	if($id == 1)
		$typename = 'MULTIPART';
	if($id == 2)
		$typename = 'MESSAGE';
	if($id == 3)
		$typename = 'APPLICATION';
	if($id == 4)
		$typename = 'AUDIO';
	if($id == 5)
		$typename = 'IMAGE';
	if($id == 6)
		$typename = 'VIDEO';
	if($id == 7)
		$typename = 'MODEL';		

	return $typename;
}

 function get_mime_type(&$structure) {
    $primary_mime_type = array("TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER");
    if($structure->subtype) {
         return $primary_mime_type[(int) $structure->type] . '/' . $structure->subtype;
     }
     return "TEXT/PLAIN";
}

function get_part($stream, $msg_number, $mime_type, $structure = false, $part_number = false) {
    if (!$structure) {
         $structure = imap_fetchstructure($stream, $msg_number);
     }
    if($structure) {
         if($mime_type == get_mime_type($structure)) {
              if(!$part_number) {
                   $part_number = "1";
               }
              $text = imap_fetchbody($stream, $msg_number, $part_number);
              if($structure->encoding == 3) {
                   return imap_base64($text);
               } else if ($structure->encoding == 4) {
                   return imap_qprint($text);
               } else {
                   return $text;
            }
        }
         if ($structure->type == 1) { /* multipart */
              while (list($index, $sub_structure) = each($structure->parts)) {
                if ($part_number) {
                    $prefix = $part_number . '.';
                }
                $data = get_part($stream, $msg_number, $mime_type, $sub_structure, $prefix . ($index + 1));
                if ($data) {
                    return $data;
                }
            }
        }
    }
    return false;
}

function MailSend($mail)
{
	global $log;
        if(!$mail->Send())
        {
		$log->info("Error in Mail Sending : Error log = '".$mail->ErrorInfo."'");
           $msg = $mail->ErrorInfo;
        }
	else
       	{	
		$log->info("Mail has been sent from the vtigerCRM system : Status : '".$mail->ErrorInfo."'");
		return true;
	}		
}

function brtonl($str) {
    $str = preg_replace("/(\r\n|\n|\r)/", "", $str);
    return preg_replace("=<br */?>=i", "\n", $str);
}

//End: kiran
header("Location: index.php?action=index&module=Webmails");	
?>

