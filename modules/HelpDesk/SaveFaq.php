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
$category = $_REQUEST['category'];
$question = $_REQUEST['question'];
$answer = $_REQUEST['answer'];
$comment = $_REQUEST['comment'];
$author_id = $current_user->id;
$datemodified = date('YmdHis');
$return_action = $_REQUEST['return_action'];
$return_module = $_REQUEST['return_module'];
$return_id = $_REQUEST['return_id'];
$mode = $_REQUEST['mode'];
if(isset($mode) && $mode != '' && $mode == 'Edit')
{
	$faqid = $_REQUEST['id'];
	$query="update faq set question='".$question."',answer='".$answer."',category='".$category."',date_modified=".$adb->formatString("faq","date_modified",$datemodified).",comments='".$comment."' where id=".$faqid;
	//echo $query;
	$adb->query($query); 

}
else
{
	//Inserting value into faqs table table
	$query="insert into faq values('','".$question."','".$answer."','".$category."','".$author_id."',".$adb->formatString("faq","date_modified",$datemodified).",".$adb->formatString("faq","date_modified",$datemodified).",'".$comment."','0')";

	$adb->query($query);

	//Retreiving the id
	$idquery = "select max(id) as id from faq";
	$idresult = $adb->query($idquery);
	$return_id = $adb->query_result($idresult,0,"id");
}
$loc = "Location: index.php?action=".$return_action."&module=".$return_module."&record=".$return_id;
//echo "locisss ".$loc;
header($loc);
?>
