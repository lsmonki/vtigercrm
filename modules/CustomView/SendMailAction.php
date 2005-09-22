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
require_once('include/database/PearDatabase.php');
require_once('modules/CustomView/CustomView.php');

global $current_user;
global $adb;

$idlist = $_POST['idlist'];
$viewid = $_REQUEST['viewname'];
$camodule=$_REQUEST['return_module'];
$storearray = explode(";",$idlist);
if(isset($viewid) && trim($viewid) != "")
{
	$oCustomView = new CustomView();
	$CustomActionDtls = $oCustomView->getCustomActionDetails($viewid);
	if(isset($CustomActionDtls))
	{
		$subject = $CustomActionDtls["subject"];
		$contents = $CustomActionDtls["content"];
	}
}

if(trim($subject) != "")
{
if(isset($storearray) && $camodule != "")
{
	foreach($storearray as $id)
	{
		if($camodule == "Contacts")
		{
			$sql="select * from contactdetails inner join crmentity on crmentity.crmid = contactdetails.contactid where crmentity.deleted =0 and contactdetails.contactid='" .$id ."'";
			$result = $adb->query($sql);
			$camodulerow = $adb->fetch_array($result);
			if(isset($camodulerow))
			{
				$emailid = $camodulerow["email"];
				$otheremailid = $camodulerow["otheremail"];
				$yahooid = $camodulerow["yahooid"];

				if(trim($emailid) != "")
				{
					SendMailtoCustomView($camodule,$id,$emailid,$current_user->id,$subject,$contents);
				}elseif(trim($otheremailid) != "")
				{
					SendMailtoCustomView($camodule,$id,$otheremailid,$current_user->id,$subject,$contents);
				}elseif(trim($yahooid) != "")
				{
					SendMailtoCustomView($camodule,$id,$yahooid,$current_user->id,$subject,$contents);
				}
				else
				{
					$adb->println("There is no email id for this Contact. Please give any email id.");
				}
			}

		}elseif($camodule == "Leads")
		{
			$sql="select * from leaddetails inner join crmentity on crmentity.crmid = leaddetails.leadid where crmentity.deleted =0 and leaddetails.leadid='" .$id ."'";
			//echo $sql;
                        $result = $adb->query($sql);
                        $camodulerow = $adb->fetch_array($result);
                        if(isset($camodulerow))
                        {
                                $emailid = $camodulerow["email"];
                                $yahooid = $camodulerow["yahooid"];

                                if(trim($emailid) != "")
                                {
                                        SendMailtoCustomView($camodule,$id,$emailid,$current_user->id,$subject,$contents);
                                }
				elseif($trim($yahooid) != "")
                                {
                                        SendMailtoCustomView($camodule,$id,$yahooid,$current_user->id,$subject,$contents);
                                }
				else
				{
					$adb->println("There is no email id for this Lead. Please give any email id.");
				}
                        }
		}elseif($camodule == "Accounts")
		{
			$sql="select * from account inner join crmentity on crmentity.crmid = account.accountid where crmentity.deleted =0 and account.accountid='" .$id ."'";
                        $result = $adb->query($sql);
                        $camodulerow = $adb->fetch_array($result);
                        if(isset($camodulerow))
                        {
                                $emailid = $camodulerow["email1"];
                                $otheremailid = $camodulerow["email2"];

                                if(trim($emailid) != "")
                                {
                                     SendMailtoCustomView($camodule,$id,$emailid,$current_user->id,$subject,$contents);
                                }
				elseif(trim($otheremailid) != "")
                                {
                                     SendMailtoCustomView($camodule,$id,$otheremailid,$current_user->id,$subject,$contents);
				}
				else
				{
					$adb->println("There is no email id for this Account. Please give any email id.");
				}
                        }	
		}
	}
}
}

function SendMailtoCustomView($module,$id,$to,$current_user_id,$subject,$contents)
{

	require_once("modules/Emails/class.phpmailer.php");

        $mail = new PHPMailer();

        $mail->Subject = $subject;
        $mail->Body    = nl2br($contents);
        $mail->IsSMTP();

        if($current_user_id != '')
        {
                global $adb;
                $sql = "select * from users where id= ".$current_user_id;
                $result = $adb->query($sql);
                $from = $adb->query_result($result,0,'email1');
                $initialfrom = $adb->query_result($result,0,'user_name');
        }
        if($mail_server=='')
        {
                global $adb;
                $mailserverresult=$adb->query("select * from systems where server_type='email'");
                $mail_server=$adb->query_result($mailserverresult,0,'server');
                $mail_server_username=$adb->query_result($mailserverresult,0,'server_username');
                $mail_server_password=$adb->query_result($mailserverresult,0,'server_password');
		$adb->println("Mail Server Details : '".$mail_server."','".$mail_server_username."','".$mail_server_password."'");
                $_REQUEST['server']=$mail_server;
        }
        $mail->Host = $mail_server;
        $mail->SMTPAuth = true;
        $mail->Username = $mail_server_username;
        $mail->Password = $mail_server_password;
        $mail->From = $from;
        $mail->FromName = $initialfrom;

        $mail->AddAddress($to);
        $mail->AddReplyTo($from);
        $mail->WordWrap = 50;

        $mail->IsHTML(true);
	$mail->AltBody = "This is the body in plain text for non-HTML mail clients";

	$adb->println("Mail sending process : To => '".$to."', From => '".$from."'");
        if(!$mail->Send())
        {
		$adb->println("(CustomView/SendMailAction.php) Error in Mail Sending : ".$mail->ErrorInfo);
                $errormsg = "Mail Could not be sent...";
        }
	else
	{
		$adb->println("(CustomView/SendMailAction.php) Mail has been Sent to => ".$to);
	}
	
}
header("Location: index.php?action=index&module=$camodule&viewname=$viewid");
?>
