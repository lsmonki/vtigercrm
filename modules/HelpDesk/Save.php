<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the 
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header: /advent/projects/wesat/vtiger_crm/vtigercrm/modules/HelpDesk/Save.php,v 1.8 2005/04/25 05:21:46 rajeshkannan Exp $
 * Description:  Saves an Account record and then redirects the browser to the 
 * defined return URL.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/HelpDesk/HelpDesk.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');

$focus = new HelpDesk();
if(isset($_REQUEST['record']))
{
	$focus->id = $_REQUEST['record'];
}
if(isset($_REQUEST['mode']))
{
	$focus->mode = $_REQUEST['mode'];
}

foreach($focus->column_fields as $fieldname => $val)
{
	if(isset($_REQUEST[$fieldname]))
	{
		$value = $_REQUEST[$fieldname];
		$focus->column_fields[$fieldname] = $value;
	}
		
}


//$focus->saveentity("HelpDesk");
$focus->save("HelpDesk");
$return_id = $focus->id;

if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = $_REQUEST['return_module'];
else $return_module = "HelpDesk";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = $_REQUEST['return_action'];
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = $_REQUEST['return_id'];

if($_REQUEST['mode'] == 'edit')
	$reply = 'Re : ';
else
	$reply = '';

$_REQUEST['name'] = '[ Ticket ID : '.$focus->id.' ] '.$reply.$_REQUEST['ticket_title'];
$bodysubject = ' Ticket ID : '.$focus->id.'<br> Ticket Title : '.$_REQUEST['ticket_title'].'<br><br>';

if($_REQUEST['ticketstatus'] == 'Closed')
	$bodydetails .= 'We are happy that your problem is solved.  ....................';
elseif($_REQUEST['ticketstatus'] != 'Closed' && $_REQUEST['mode'] != 'edit')
	$bodydetails .= '<br> We have received the following Ticket details from you: ';
elseif($_REQUEST['mode'] == 'edit')
	$bodydetails .= '<br> Updated details of the ticket :';

$bodydetails .= '<br><br>Ticket Details : <br>';
$bodydetails .= '<br> Status : <b>'.$_REQUEST['ticketstatus'].'</b>';
$bodydetails .= '<br> Priority : <b>'.$_REQUEST['ticketpriorities'].'</b>';
$bodydetails .= '<br> Category : <b>'.$_REQUEST['ticketcategories'].'</b>';
$bodydetails .= '<br><br> Description : <br>'.$_REQUEST['description'];
$bodydetails .= '<br><br>Solution : <br>'.$_REQUEST['solution'];

$sql = "select * from ticketcomments where ticketid=".$focus->id;
$result = $adb->query($sql);
for($i=0;$i<$adb->num_rows($result);$i++)
{
	$comment = $adb->query_result($result,$i,'comments');
	if($comment != '')
	{
		$commentlist .= '<br><br>'.$comment;
	}
}

$_REQUEST['description'] = $bodysubject.$bodydetails.$commentlist;
$_REQUEST['parent_id'] = $_REQUEST['contact_id'];
$_REQUEST['return_id'] = $return_id;

if($_REQUEST['product_id'] != '' && $focus->id != '' && $_REQUEST['mode'] != 'edit')
{
        $sql = 'insert into seticketsrel values('.$_REQUEST['product_id'].' , '.$focus->id.')';
        $adb->query($sql);
        $return_id = $_REQUEST['product_id'];
}

require_once('modules/Emails/send_mail.php');
//header("Location: index.php?action=$return_action&module=$return_module&record=$return_id");

?>
