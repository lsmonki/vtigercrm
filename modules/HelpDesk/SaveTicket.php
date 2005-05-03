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
require_once("modules/Emails/class.phpmailer.php");
require_once('include/database/PearDatabase.php');
$groupname = $_REQUEST['assigned_group_name'];
$assigned_user_id = $_REQUEST['assigned_user_id'];
$user_query = "select user_name from users where id='".$assigned_user_id."'";
$user_rt = $adb->query($user_query);
$assigned_user_name = $adb->query_result($user_rt,0,"user_name");
$parent_type = $_REQUEST['parent_type'];
$parent_id = $_REQUEST['parent_id'];
$contact_id = $_REQUEST['contact_id'];
$priority = $_REQUEST['priority'];
$category = $_REQUEST['category'];
$status = $_REQUEST['status'];
$subject = $_REQUEST['subject'];
$description = $_REQUEST['description'];
$datemodified = date('YmdHis');
//$updatelog = "Ticket Created. Assigned To";
$return_action = $_REQUEST['return_action'];
$return_module = $_REQUEST['return_module'];
$return_id = $_REQUEST['return_id'];
$mode = $_REQUEST['mode'];
$estimated_finishing_date=$_REQUEST['estimated_finishing_date'];
$estimated_finishing_time=$_REQUEST['estimated_finishing_time'];

$estimated_date_and_time = $estimated_finishing_date.$estimated_finishing_time;

if(isset($mode) && $mode != '' && $mode == 'Edit')
{
	$ticketid = $_REQUEST['id'];
	//Updating History
	$tktresult = $adb->query("select * from troubletickets where id='".$ticketid."'");
	$updatelog = $adb->query_result($tktresult,0,"update_log");
	$old_user_id = $adb->query_result($tktresult,0,"assigned_user_id");
	$old_status = $adb->query_result($tktresult,0,"status");
	$old_priority = $adb->query_result($tktresult,0,"priority");
	if($old_user_id != $assigned_user_id || $old_status != $status || $old_priority != $priority)
	{
		$updatelog .= date("l dS F Y h:i:s A").' by '.$current_user->user_name.'--//--';
	}	

	if($old_user_id != $assigned_user_id)
	{
		$updatelog .= ' Transferred to '.$assigned_user_name.'\.';
	}
	if($old_status != $status)
	{
		$updatelog .= ' Status Changed to '.$status.'\.';
	}
	if($old_priority != $priority)
	{
		$updatelog .= ' Priority Changed to '.$priority.'\.';
	}
	if($old_user_id != $assigned_user_id || $old_status != $status || $old_priority != $priority)
	{
		$updatelog .= '--//--';
	}
	
	$query="update troubletickets set groupname='".$groupname."',contact_id='".$contact_id."',priority='".$priority."',status='".$status."',parent_id='".$parent_id."',parent_type='".$parent_type."',category='".$category."',title='".$subject."',description='".$description."',update_log='".$updatelog."',date_modified=".$adb->formatString("troubletickets","date_modified",$datemodified).",assigned_user_id='".$assigned_user_id."', estimate_finish_time = '".$estimated_date_and_time."' where id=".$ticketid;
	//echo $query;
	$adb->query($query);
	save_customfields($ticketid);

}
else
{
	$updatelog = date("l dS F Y h:i:s A").' by '.$current_user->user_name;
	$updatelog .='--//--Ticket created. Assigned to '.$assigned_user_name.'--//--'; 
	//Inserting value into troubletickets table
	$query="insert into troubletickets values('','".$groupname."','".$contact_id."','".$priority."','".$status."','".$parent_id."','".$parent_type."','".$category."','".$subject."','".$description."','".$updatelog."','','".$datemodified."','".$datemodified."','".$assigned_user_id."','','".$estimated_date_and_time."')";
	$adb->query($query);

	//Retreiving the id
	$idquery = "select max(id) as id from troubletickets";
	$idresult = $adb->query($idquery);
	$customticketid = $adb->query_result($idresult,0,"id");
	save_customfields($customticketid);
	if(! isset($return_id) || trim($return_id == ''))
	{
		$return_id = $customticketid;
	}
}
if($_REQUEST['send_mail']=='on')
{
        $mail = new PHPMailer();

        $sql="select * from users where id='".$_REQUEST['assigned_user_id']."'";
        $result = $adb->query($sql);
//      $mailid=mysql_result($result,0,"email1");
        if(!@$toname=$adb->query_result($result,0,"last_name")){}

        $sql="select * from contacts where id='".$_REQUEST['contact_id']."'";
        $result = $adb->query($sql);
        if(!@$mailid=$adb->query_result($result,0,"email1")){}

        $sql1="select email1 from users where user_name='" .$current_user->user_name ."'" ;
        $result1 = $adb->query($sql1);
        $from = $adb->query_result($result1,0,"email1");

$str = "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
<style>
BODY, P, TH, TD {
        font-family: Arial,Geneva,Helvetica,Swiss,SunSans-Regular;
        font-size: x-small;
}
HR {
        color: #FF9900;
        margin: 4px 0px 4px 0px;
}
.tablebg {
        background: #FFDD95;
}
.field {
        width: 100px;
        padding: 3px;
        border-top: 1px solid #FFF;
        background: #FFF5E8;
        white-space: nowrap;
        color: #700;
}
.value {
        padding: 3px;
        font-weight: bold;
        white-space: nowrap;
        background: #FFF;
        color: #000;
}
</style>
</head>

<body>
<img src='include/images/vtigercrm_icon.ico' width='46' height='62'>
<hr>
Dear Customer,
<br>
<br>
The issue reported by you is acknowledged and has been allocated the following:
<br>
<br>
<table width='400' border='0' cellpadding='0' cellspacing='1' class='tablebg'>
  <tr>
    <td class='field'>Ticket Id:</td>
    <td class='value'>".$return_id."</td>
  </tr>
  <tr>
    <td class='field'>Category:</td>
    <td class='value'>".$_REQUEST['category']."</td>
  </tr>
  <tr>
    <td class='field'>Group:</td>
    <td class='value'>".$_REQUEST['assigned_group_name']."</td>
  </tr>
  <tr>
    <td class='field'>Status:</td>
    <td class='value'>".$_REQUEST['status']."</td>
  </tr>
  <tr>
    <td class='field'>Priority:</td>
    <td class='value'>".$_REQUEST['priority']."</td>
  </tr>
  <tr>
    <td class='field'>Submitted By:</td>
    <td class='value'>".$current_user->user_name."</td>
 </tr>
  <tr>
    <td class='field'>Assigned To:</td>
    <td class='value'>".$toname."</td>
  </tr>
  <tr>
    <td class='field'>Contact:</td>
    <td class='value'>".$_REQUEST['contact_name']."</td>
  </tr>
  <tr>
    <td class='field'>Summary:</td>
    <td class='value'>".$subject."</td>
  </tr>
</table>
<br>
<b>Initial Comment:</b>
<br>
<br>
".$description."
<br>
<br>
Regards,
<br>
&lt;".$current_user->user_name."&gt;
</body>
</html>";

        $mail->Subject = '[Ticket Id : '.$return_id.'] '.$subject;
        $mail->Body    = $str;
        $initialfrom = $current_user->user_name;

        $mail->IsSMTP();                                      // set mailer to use SMTP

        $to=$mailid;

        $mail->Host = $mail_server;  // specify main and backup server
        $mail->SMTPAuth = true;     // turn on SMTP authentication
        $mail->Username = $mail_server_username ;//$smtp_username;  // SMTP username
        $mail->Password = $mail_server_password ;//$smtp_password; // SMTP password
        $mail->From = $from;
        $mail->FromName = $initialfrom;
        $mail->AddAddress($to);                  // name is optional
        $mail->AddReplyTo($from);
        $mail->WordWrap = 50;                                 // set word wrap to 50 characters



       $mail->IsHTML(true);                                  // set email format to HTML

       $mail->AltBody = "This is the body in plain text for non-HTML mail clients";


        if(!$mail->Send())
        {
//           echo "Message could not be sent. <p>";
//           echo "Mailer Error: " . $mail->ErrorInfo;
        }
}
$loc = "Location: index.php?action=".$return_action."&module=".$return_module."&record=".$return_id;
//echo "locisss ".$loc;
header($loc);

//Code to save the custom field info into database
function save_customfields($entity_id)
{
	global $adb;
	$dbquery="select * from customfields where module='HelpDesk'";
	$result = $adb->query($dbquery);
	$custquery = 'select * from ticketcf where ticketid="'.$entity_id.'"';
        $cust_result = $adb->query($custquery);
	if($adb->num_rows($result) != 0)
	{
		
		$columns='';
		$values='';
		$update='';
		$noofrows = $adb->num_rows($result);
		for($i=0; $i<$noofrows; $i++)
		{
			$fldName=$adb->query_result($result,$i,"fieldlabel");
			$colName=$adb->query_result($result,$i,"column_name");
			if(isset($_REQUEST[$colName]))
			{
				$fldvalue=$_REQUEST[$colName];
				if(get_magic_quotes_gpc() == 1)
                		{
                        		$fldvalue = stripslashes($fldvalue);
                		}
			}
			else
			{
				$fldvalue = '';
			}
			if(isset($_REQUEST['id']) && $_REQUEST['id'] != '' && $adb->num_rows($cust_result) !=0)
			{
				//Update Block
				if($i == 0)
				{
					$update = $colName.'="'.$fldvalue.'"';
				}
				else
				{
					$update .= ', '.$colName.'="'.$fldvalue.'"';
				}
			}
			else
			{
				//Insert Block
				if($i == 0)
				{
					$columns='ticketid, '.$colName;
					$values='"'.$entity_id.'", "'.$fldvalue.'"';
				}
				else
				{
					$columns .= ', '.$colName;
					$values .= ', "'.$fldvalue.'"';
				}
			}
			
				
		}
		if(isset($_REQUEST['id']) && $_REQUEST['id'] != '' && $adb->num_rows($cust_result) !=0)
		{
			//Update Block
			$query = 'update ticketcf SET '.$update.' where ticketid="'.$entity_id.'"'; 
			$adb->query($query);
		}
		else
		{
			//Insert Block
			$query = 'insert into ticketcf ('.$columns.') values('.$values.')';
			$adb->query($query);
		}
		
	}
	else
	{
		if(isset($_REQUEST['id']) && $_REQUEST['id'] != '' && $adb->num_rows($cust_result) !=0)
		{
			//Update Block
		}
		else
		{
			//Insert Block
			$query = 'insert into ticketcf (ticketid) values('.$entity_id.')';
			$adb->query($query);
		}
	}	
}
?>
