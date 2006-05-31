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


//This is to save the comment made by the customer

$faqid = $_REQUEST['faqid'];
$comment = $_REQUEST['comments'];

//commented customer should be added as author for the comment
$params = array('faqid'=>"$faqid",'comments'=>"$comment");
$result = $client->call('save_faq_comment', $params, $Server_Path, $Server_Path);






?>
