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

require_once("modules/Faq/Faq.php");

$focus = new FAQ();

//Map the vtiger_fields like ticket column => vtiger_faq column where ticket column is the troubletikcets vtiger_field name & vtiger_faq - column_fields
$ticket_faq_mapping_fields = Array(
			'title'=>'question',
			'product_id'=>'product_id',
			'description'=>'faq_answer',
			//'ticketstatus'=>'faqstatus',
			//'ticketcategories'=>'faqcategory'
		   );

$sql = "select ticketid, title, product_id, description, solution,status, category from vtiger_troubletickets where ticketid=".$_REQUEST['record'];
$res = $adb->query($sql);

//set all the ticket values to FAQ
foreach($ticket_faq_mapping_fields as $ticket_column => $faq_column)
{
	$focus->column_fields[$faq_column] = $adb->query_result($res,0,$ticket_column);
}

$focus->save("Faq");

if($focus->id != '')
{
	$description = $adb->query_result($res,0,'description');
	$solution = $adb->query_result($res,0,'solution');

	//Add the solution of the ticket with the FAQ answer
	$answer = $description;
	if($solution != '')
	{
		$answer .= '\r\n\r\nSOLUTION:\r\n'.$solution;
	}

	//Retrive the ticket comments from the vtiger_ticketcomments vtiger_table and added into the vtiger_faq answer
	$sql = "select ticketid, comments, createdtime from vtiger_ticketcomments where ticketid=".$_REQUEST['record'];
	$res = $adb->query($sql);
	$noofrows = $adb->num_rows($res);
	$answer .= '\r\n\r\nCOMMENTS:';
	for($i=0; $i < $noofrows; $i++)
	{
		$comments = $adb->query_result($res,$i,'comments');
		if($comments != '')
		{
			$answer .= '\r\n'.$comments;
		}
	}

	$sql1 = "update vtiger_faq set answer='".$answer."' where id=".$focus->id;
	$adb->query($sql1);
}

header("Location:index.php?module=Faq&action=EditView&record=$focus->id&return_module=Faq&return_action=DetailView&return_id=$focus->id");

?>
