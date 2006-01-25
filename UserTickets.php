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

session_start();

if($_SESSION['customer_id'] != '' && isset($_SESSION['customer_id']))
{

require_once('PortalConfig.php');
require_once('language/en_us.lang.php');

echo '<link rel="stylesheet" type="text/css" href="customerportal.css">';

require_once('nusoap/lib/nusoap.php');
$proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : $Proxy_Host;
$proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : $Proxy_Port;
$proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : $Proxy_Username;
$proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : $Proxy_Password;

global $Server_Path;
global $client;

$client = new soapclient($Server_Path."/vtigerservice.php?service=customerportal", false,
						$proxyhost, $proxyport, $proxyusername, $proxypassword);
$err = $client->getError();
if ($err) 
{
	echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
}

$username = $_SESSION['customer_name'];//$_REQUEST['username'];
$id = $_SESSION['customer_id'];

// This is an archaic parameter list
$params = array('user_name' => "$username", 'id' => "$id");

function GetAllLinks($username)
{
	global $mod_strings;
	$date = date("Y-m-d H:i");

	if($_REQUEST['fun'] == 'home' || $_REQUEST['fun'] == '')
		$tabkeyHome = "tabOn";
	else
		$tabkeyHome = "tabOff";

        if($_REQUEST['fun'] == 'create')
	        $tabkeyNew = "tabOn";
	else
		$tabkeyNew = "tabOff";

	if($_REQUEST['fun'] == 'changepassword')
		$tabkeychange = "tabOn";
	else
		$tabkeychange = "tabOff";


	$list .= '<br><br>
			  <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			  <tr> 
				<td width="5" height="5"><img src="images/cp_top_start.gif" width="5" height="5"></td>
				<td class="topBand" width="100%" height="5"><img src="images/spacer.gif"></td>
				<td width="5" height="5"><div align="right"><img src="images/cp_top_end.gif" width="5" height="5"></td>
			  </tr>
			  <tr>
			    <td colspan="3" height="35" class="topBand">&nbsp;&nbsp;<img src="images/cp_logo.gif"></td>
			  </tr>
			  <tr>
			    <td colspan="3">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabBg">
					<tr>
				  		<td class='.$tabkeyHome.' nowrap><a class="tabOff" href="general.php?action=UserTickets&fun=home&username='.$username.'">'.$mod_strings['LNK_HOME'].'</a></td>
				  		<td class='.$tabkeyNew.' nowrap><a class="tabOff" href="general.php?action=UserTickets&fun=create&username='.$username.'">'.$mod_strings['LNK_SUBMIT_NEW_TICKET'].'</a></td>
				  		<td class='.$tabkeychange.' nowrap><a class="tabOff" href="general.php?action=UserTickets&fun=changepassword&username='.$username.'">'.$mod_strings['LBL_PROFILE'].'</a></td>';
	$list .= '		  		<td width="100%" nowrap><div align="right">'.$mod_strings['LBL_WELCOME'].' <b>'.$username.'</b>&nbsp;&nbsp;[<a href="general.php?logout=true&fun=logout" class="logoutLink">'.$mod_strings['LNK_LOGOUT'].'</a>]&nbsp;</div></td>
					</tr>
			  		</table>
				</td>	
			  </tr>
			  </table>			  
			';

	return $list;
}
/*
function GetTicketsList($result)
{
$list_fields = Array(
			0 => 'Ticketid',
			1 => 'Title',
//			2 => 'firstname',
//			3 => 'lastname',
//			4 => 'contact_id',
			2 => 'Priority',
			3 => 'Status',
			4 => 'Category',
//			5 => 'description'
			5 => 'Modified Time',
			6 => 'Created Time'
		     );


	$rowcount = count($result);

	$list .= '<table width="75%" border="0" cellspacing="0" cellpadding="0" align="center">';
	$list .= '<tr><td class="pageTitle">Tickets List</td></tr></table>';
		
	if($rowcount >= 1)
	{
		$list .= '<table width="75%" border="0" cellspacing="0" cellpadding="0" align="center" class="tblBorder"><tr>';
		foreach($list_fields as $key => $val)
			$list .= '<td class="tblHead">'.$list_fields[$key].'</td>';
		$list .= '</tr>';

		for($i=0;$i<$rowcount;$i++)
		{
			if ($i%2==0)
				$list .= '<tr class="tblEvenRow">';
			else
				$list .= '<tr class="tblOddRow">';
				
			if($result[$i]['status'] != 'Closed')
			{
				$list .= '<td class="tblData">'.$result[$i]['ticketid'].'</td>';
				$list .= '<td class="tblData"><a href="general.php?action=UserTickets&ticketid='.$result[$i]['ticketid'].'&fun=detail&username='.$_REQUEST['username'].'">'.$result[$i]['title'].'</a></td>';
				$list .= '<td class="tblData">'.$result[$i]['priority'].'</td>';
				$list .= '<td class="tblData">'.$result[$i]['status'].'</td>';
				$list .= '<td class="tblData">'.$result[$i]['category'].'</td>';
//				$list .= '<td class="tblData">'.$result[$i]['description'].'</td></tr>';
				$list .= '<td class="tblData">'.$result[$i]['modifiedtime'].'</td>';
				$list .= '<td class="tblData">'.$result[$i]['createdtime'].'</td></tr>';
			}
		}

/*		$row = current($result);
		for($i=0;$i<$rowcount;$i++)
		{
			$list .= '<tr>';
			foreach($list_fields as $key => $val)
			{
				if($val == 'title')
				{
					$list .= '<td><a href=../../index.php?module=HelpDesk&action=DetailView&record='.$row['ticketid'].'>'.$row['title'].'</a></td>';
				}
				else
					$list .= '<td>'.$row[$list_fields[$key]].'</td>';
			}
			$list .= '</tr>';
			$row = next($result);
		}
*//*
		$list .= '</table>';
	}
	else
	{
		$list .= 'None Scheduled';
	}

	return $list;
}
*/

function HomeTickets($result)
{
	global $result;
	global $mod_strings;
	$list = '';
	$closedlist = '';

	$list_fields = Array(   0 => 'TICKETID',
        	                1 => 'TITLE',
                	        2 => 'PRIORITY',
                        	3 => 'STATUS',
	                        4 => 'CATEGORY',
        	                5 => 'MODIFIED TIME',
                	        6 => 'CREATED TIME'
	                     );

	$val = @array_values($result);
	$rowcount = count($result);

//	$list .= '<table width=100% border=0 align=right>';
//	$list .= '<tr align=right><td>Last Login : '.$_SESSION['last_login'].'</td></tr>';
//	$list .= '</table><br>';

	if($rowcount >= 1 && $val)
	{
		$list .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">';
		$list .= '<tr><td class="pageTitle">'.$mod_strings['LBL_MY_OPEN_TICKETS'].'</td></tr></table>';
		$list .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="tblBorder">';
		//$list .= '<tr><td class="tblHead">S.No</td><td class="tblHead">Ticket Title</td></tr>';
		$list .= '<tr>';

		$closedlist .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">';
		$closedlist .= '<tr><td class="pageTitle">'.$mod_strings['LBL_CLOSED_TICKETS'].'</td></tr></table>';
		$closedlist .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="tblBorder">';
		//$closedlist .= '<tr><td class="tblHead">S.No</td><td class="tblHead">Ticket Title</td></tr>';
		$closedlist .= '<tr>';
		foreach($list_fields as $key => $val)
                {
		        $list .= '<td class="tblHead">'.$mod_strings[$list_fields[$key]].'</td>';
		        $closedlist .= '<td class="tblHead">'.$mod_strings[$list_fields[$key]].'</td>';
		}
		$list .= '</tr>';

		for($i=0;$i<count($result);$i++)
		{
			$ticketlist = '';
			
			if ($i%2==0)
				$ticketlist .= '<tr class="tblEvenRow">';
			else
				$ticketlist .= '<tr class="tblOddRow">';
				
			$ticketlist .= '<td class="tblData">'.$result[$i]['ticketid'].'</td>';
			$ticketlist .= '<td class="tblData"><a href="general.php?action=UserTickets&ticketid='.$result[$i]['ticketid'].'&fun=detail&username='.$_REQUEST['username'].'">'.$result[$i]['title'].'</a></td>';
                        $ticketlist .= '<td class="tblData">'.$result[$i]['priority'].'</td>';
                        $ticketlist .= '<td class="tblData">'.$result[$i]['status'].'</td>';
                        $ticketlist .= '<td class="tblData">'.$result[$i]['category'].'</td>';
                        $ticketlist .= '<td class="tblData">'.$result[$i]['modifiedtime'].'</td>';
                        $ticketlist .= '<td class="tblData">'.$result[$i]['createdtime'].'</td></tr>';

			if($result[$i]['status'] == $mod_strings['LBL_STATUS_CLOSED'])
				$closedlist .= $ticketlist;
			elseif($result[$i]['status'] != '')
				$list .= $ticketlist;
		}	

		$list .= '</table>';
		$closedlist .= '</table>';
	}
	else
	{
		$list = '<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">';
		$list .= '<tr><td class="pageTitle">'.$mod_strings['LBL_NONE_SUBMITTED'].'</td></tr></table>';
	}

	return $list.'<br><br>'.$closedlist;
}

function CreateTicket()
{
	global $mod_strings;
	global $client;

        $params = Array('id' => "$result[0]");
        $result = $client->call('get_combo_values', $params, $Server_Path, $Server_Path);

        $_SESSION['combolist'] = $result;
	$combolist = $_SESSION['combolist'];
	for($i=0;$i<count($result);$i++)
	{
		if($result[$i]['productid'] != '')
		{
			$productslist[0] = $result[$i]['productid'];
		}
		if($result[$i]['productname'] != '')
		{
			$productslist[1] = $result[$i]['productname'];
		}
		if($result[$i]['ticketpriorities'] != '')
		{
			$ticketpriorities = $result[$i]['ticketpriorities'];
		}
		if($result[$i]['ticketseverities'] != '')
		{
			$ticketseverities = $result[$i]['ticketseverities'];
		}
		if($result[$i]['ticketcategories'] != '')
		{
			$ticketcategories = $result[$i]['ticketcategories'];
		}
		//Added to display the module -- 10-11-2005
		if($result[$i]['moduleslist'] != '')
		{
			$moduleslist = $result[$i]['moduleslist'];
		}

	}

	$noofrows = count($productslist[0]);

	for($i=0;$i<$noofrows;$i++)
	{
		if($i > 0)
			$productarray .= ',';
		$productarray .= "'".$productslist[1][$i]."'";
	}

	$list = '';
	$list .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">';

	$list .= '<tr><td class="pageTitle uline">'.$mod_strings['LBL_CREATE_NEW_TICKET'].'</td></tr>';
	$list .= '<tr><td height="25">'.$mod_strings['LBL_NEW_INFORMATION'].'</td></tr>';
	$list .= '<tr><td style="padding-top: 10px">';
	$list .= '<form name="Submit" method="POST" action="UserTickets.php"> ';
	$list .= '<input type=hidden name=username value="'.$_REQUEST['username'].'">';
	$list .= '<input type=hidden name=fun value="save">';
	$list .= '<table border="0" cellspacing="2" cellpadding="2">';


	$list .= '<tr><td align="right">'.$mod_strings['LBL_TITLE'].': </td>';
	$list .= '<td><input name="title" maxlength="255" type="text" value=""></td>';

$list .= '<tr><td align="right">'.$mod_strings['LBL_PRODUCT_NAME'].' : </td>';
//To get Product dropdown - start
$list .= '      <style>                       @import url( css/dropdown.css );                </style>

                <script src="js/modomt.js"></script>
                <script src="js/getobject2.js"></script>
                <script src="js/acdropdown.js"></script>
                <script language="javascript">
	                var products = new Array('.$productarray.')
                </script> ';
$list .=  '<td valign="center">     
                <input class="dropdown" autocomplete="off" name="productid" id="inputer2" style="width: 135px;" acdropdown="true" autocomplete_list="array:products" autocomplete_list_sort="false" autocomplete_matchsubstring="true">
                </td>';
//To get Product dropdown - end

	$list .= '<tr><td align="right">'.$mod_strings['LBL_PRIORITY'].': </td>';
	$list .= '<td><select name="priority">';
	for($i=0;$i<count($ticketpriorities);$i++)
	{
		$list .= '<OPTION value="'.$ticketpriorities[$i].'">'.$ticketpriorities[$i].'</OPTION>';
	}
	$list .= '</select></td></tr>';
	$list .= '<tr><td align="right">'.$mod_strings['LBL_SEVERITY'].': </td>';
	$list .= '<td><select name="severity">';
	for($i=0;$i<count($ticketseverities);$i++)
        {
                $list .= '<OPTION value="'.$ticketseverities[$i].'">'.$ticketseverities[$i].'</OPTION>';
        }
	$list .= '</select></td></tr>';

	$list .= '<tr><td align="right">'.$mod_strings['LBL_CATEGORY'].': </td>';
	$list .= '<td><select name="category">';
	for($i=0;$i<count($ticketcategories);$i++)
        {
                $list .= '<OPTION value="'.$ticketcategories[$i].'">'.$ticketcategories[$i].'</OPTION>';
        }
	$list .= '</select></td></tr>';

	//Added to display the module -- 10-11-2005
	$list .= '<tr><td align="right">'.$mod_strings['LBL_MODULE'].': </td>';
        $list .= '<td><select name="module">';
	$list .= '<OPTION value="General">General</OPTION>';
        for($i=0;$i<count($moduleslist);$i++)
        {
                $list .= '<OPTION value="'.$moduleslist[$i].'">'.$moduleslist[$i].'</OPTION>';
        }
        $list .= '</select></td></tr>';

	$list .= '<tr><td align="right" valign="top">'.$mod_strings['LBL_DESCRIPTION'].': </td>';
	//$list .= '<td><input name="description" maxlength="255" type="text" value=""></td></tr>';
	$list .= '<td><textarea name="description" rows="10" cols="80"></textarea></td></tr>';
	$list .= '<tr><td></td><td><input type=submit name=save onclick="this.save.value=true" value='.$mod_strings['LBL_SUBMIT'].'>&nbsp;&nbsp;</td>';
	$list .= '<tr/></table></form>';
	$list .= '</td></tr>';
	$list .= '</table>';
	
	return $list;
}

function GetDetailView($result,$ticketid)
{
	global $result;
	global $client;
	global $mod_strings;

	$params = Array('id'=>"$ticketid");
	$commentresult = $client->call('get_ticket_comments', $params, $Server_Path, $Server_Path);	

	$ticketscount = count($result);
	$commentscount = count($commentresult);


	for($i=0;$i<$ticketscount;$i++)
	{
		if($result[$i]['ticketid'] == $ticketid)
		{
			$ticket_position_in_array = $i;
			//Get the creator of this ticket
			$creator = $client->call('get_ticket_creator', $params, $Server_Path, $Server_Path);	

			//If the ticket is created by this customer or status is not Closed then allow him to Close this ticket otherwise not
			if($creator == 0 && $result[$i]['status'] != $mod_strings['LBL_STATUS_CLOSED'])
			{
				$close_this_ticket = '<td class="pageTitle uline" align="right"><a href=general.php?action=UserTickets&fun=close_ticket&ticketid='.$ticketid.'>'.$mod_strings['LBL_CLOSE_TICKET'].'</a>&nbsp;&nbsp;&nbsp;</td>';
			}
		}
	}

	$list .= '';
	$list .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">';
	$list .= '<tr><td class="pageTitle uline">'.$mod_strings['LBL_TICKET_INFORMATION'].'</td>';

	if($close_this_ticket != '')
		$list .= $close_this_ticket;

	$list .= '</tr></table>';
	$list .= '<table border="0" cellspacing="4" cellpadding="2" style="margin-top:10px">';

	//assign the ticketposition(from $result) to the variable i. Now instead of search through the result we can get using this i
	$i = $ticket_position_in_array;
        if($result[$i]['ticketid'] == $ticketid)
	{
		$list .= '<tr><td width="15%" align="right" nowrap>'.$mod_strings['LBL_TICKET_ID'].' : </td>';
		$list .= '<td width="15%"><b>'.$result[$i]['ticketid'].'</b></td>';
		$list .= '<td width="15%"align="right" nowrap>'.$mod_strings['LBL_PRIORITY'].' : </td>';
		$list .= '<td width="15%" nowrap><b>'.$result[$i]['priority'].'</b></td>';
		$list .= '<td width="15%" align="right" nowrap>'.$mod_strings['LBL_CREATED_TIME'].' : </td>';
		$list .= '<td width="15%" nowrap><b>'.$result[$i]['createdtime'].'</b></td>';

		$list .= '<tr><td width="15%" align="right" nowrap>'.$mod_strings['LBL_CATEGORY'].' : </td>';
		$list .= '<td width="15%" nowrap><b>'.$result[$i]['category'].'</b></td>';
		$list .= '<td width="15%"align="right" nowrap>'.$mod_strings['LBL_STATUS'].' : </td>';
		$list .= '<td width="15%" nowrap><b>'.$result[$i]['status'].'</b></td>';
		$ticket_status = $result[$i]['status'];
		$list .= '<td width="15%" align="right" nowrap>'.$mod_strings['LBL_MODIFIED_TIME'].' : </td>';
		$list .= '<td width="15%"><b>'.$result[$i]['modifiedtime'].'</b></td>';

		$list .= '<tr><td width="15%" align="right" nowrap>'.$mod_strings['LBL_SEVERITY'].' : </td>';
		$list .= '<td width="15%" nowrap><b>'.$result[$i]['severity'].'</b></td>';
		$list .= '<td width="15%" align="right" nowrap>'.$mod_strings['LBL_PRODUCT_NAME'].' : </td>';
		$list .= '<td width="15%" nowrap><b>'.$result[$i]['productname'].'</b></td>';

		$list .= '<tr><td align="right" nowrap>'.$mod_strings['LBL_TITLE'].' : </td>';
		$list .= '<td><b>'.$result[$i]['title'].'</b></td></tr>';

		$list .= '<tr><td align="right" valign="top" nowrap>'.$mod_strings['LBL_DESCRIPTION'].' : </td>';
		$list .= '<td><b>'.nl2br($result[$i]['description']).'</b></td></tr>';

		$list .= '<tr><td align="right" valign="top" nowrap>'.$mod_strings['LBL_RESOLUTION'].' : </td>';
		$list .= '<td><b>'.nl2br($result[$i]['solution']).'</b></td></tr>';

		if($commentscount >= 1 && is_array($commentresult))
		{
			$list .= '<td align="right" valign="top" nowrap>'.$mod_strings['LBL_COMMENTS'].' : </td>';
			$list .= '<td nowrap colspan="5"> <div class="commentArea">';
		}

		for($j=0;$j<$commentscount;$j++)
		{
			if($commentresult[$j]['comments'] != '')
			{
				$list .= nl2br($commentresult[$j]['comments']);
				$list .= '<div class="commentInfo"> '.$mod_strings['LBL_COMMENT_BY'].' : ';
				$list .= $commentresult[$j]['owner'].' '.$mod_strings['LBL_ON'].' ';
				$list .= $commentresult[$j]['createdtime'].'</div><br>';
				$list .= '<div>';
				for($k=0;$k<50;$k++) $list .= '---';
				$list .= '</div>';
			}
		}
		$list .= '</div></td></tr>';	

		if($ticket_status != $mod_strings['LBL_STATUS_CLOSED'])
		{
			$list .= '<form name="form" action="#" method="post">';
			$list .= '<input type=hidden name=updatecomment value=true>';
			$list .= '<input type=hidden name=ticketid value='.$ticketid.'>';
			$list .= '<td align="right" valign="top" nowrap>'.$mod_strings['LBL_ADD_COMMENT'].' : </td>';
			$list .= '<td nowrap colspan="5"><textarea name="comments" cols="85" rows="7"></textarea> </td></tr>';
			$list .= '<tr><td/><td><input type=submit name=submit value='.$mod_strings['LBL_SUBMIT'].'></td>';
			$list .= '</table></form>';
		}
	}
	else
	{
		$list .= '<br><br>'.$mod_strings['LBL_NO_DETAILS_EXIST'];
	}

	return $list;
}
function Close_Ticket($result,$ticketid)
{
	global $client;
	$params = Array('id'=>"$ticketid");
	$result = $client->call('close_current_ticket', $params, $Server_Path, $Server_Path);
	return $result;
}
function UpdateComment()
{
	global $client;
	$ticketid = $_REQUEST['ticketid'];
	$ownerid = $_SESSION['customer_id'];
	$createdtime = date("Y-m-d H:i:s");
	$comments = $_REQUEST['comments'];

        $params = Array('id'=>"$ticketid",'ownerid'=>"$ownerid",'createdtime'=>"$createdtime",'comments'=>"$comments");
        $commentresult = $client->call('update_ticket_comment', $params, $Server_Path, $Server_Path);

	$_REQUEST['fun'] = 'detail';

	$username = $_SESSION['customer_name'];
	$parent_id = $_SESSION['customer_id'];
	$params_list = array('user_name' => "$username", 'id' => "$parent_id");
	$result = $client->call('get_tickets_list', $params_list, $Server_Path, $Server_Path);

	GetDetailView($result, $ticketid);
}
/*
function LogOut()
{
	echo 'Logoutt........';
	$logout_flag = true;
	return $logout_flag;
}
*/
function SaveTicket($ticket,$username)
{
	global $client;
	global $result;

	$title = $_REQUEST['title'];
	$description = $_REQUEST['description'];
	$priority = $_REQUEST['priority'];
	$severity = $_REQUEST['severity'];
	$category = $_REQUEST['category'];
	$parent_id = $_SESSION['customer_id'];
	$productid = $_SESSION['combolist'][0]['productid'][$_REQUEST['productid']];

	//Added to display the module -- 10-11-2005
	$module = $_REQUEST['module'];

	$params = array(
			'title'=>"$title",
			'description'=>"$description",
			'priority'=>"$priority",
			'severity'=>"$severity",
			'category'=>"$category",
			'user_name' => "$username",
			'parent_id'=>"$parent_id",
			'product_id'=>"$productid",
			'module'=>"$module"
		       );

	$record_result = $client->call('create_ticket', $params);
	if(isset($record_result[0]['new_ticket']) && $record_result[0]['new_ticket']['ticketid'] != '')
	{
		$new_record = 1;
		$ticket_id = $record_result[0]['new_ticket']['ticketid'];
	}

	//$list = GetTicketsList($result);
	$params_list = array('user_name' => "$username", 'id' => "$parent_id");
        $result = $client->call('get_tickets_list', $params_list, $Server_Path, $Server_Path);
	
	if($new_record == 1)
	{
		$list = GetDetailView($result,$ticket_id);
	}
	else
	{
		$list = HomeTickets($result);
	}
	echo $list;

//	foreach($ticket as $key => $val)
//		echo '<br>'.$key.' ==> '.$val;
}
function SavePassword()
{
	global $client;
	global $mod_strings;

	$customer_id = $_SESSION['customer_id'];
	$customer_name = $_SESSION['customer_name'];
	$oldpw = $_REQUEST['old_password'];
	$newpw = $_REQUEST['new_password'];
	$confirmpw = $_REQUEST['confirm_password'];

	$params = Array('user_name'=>"$customer_name",'user_password'=>"$oldpw");
	$result = $client->call('authenticate_user',$params);

	if($oldpw == $result[2])
	{
		if($newpw == $confirmpw)
		{
			$id = $result[0];
			$params = Array('id'=>"$id",'user_name'=>"$customer_name",'user_password'=>"$newpw");
			$result = $client->call('change_password',$params);
			$list = "<div class='infoMsg' style='margin: 20 0 20 0'>".$mod_strings['MSG_PASSWORD_CHANGED']."</div>";
		}
		else
		{
			$errormsg = $mod_strings['MSG_ENTER_NEW_PASSWORDS_SAME'];
			$list = ChangePasswordUI($errormsg);
		}
	}
	else
	{
		$errormsg = $mod_strings['MSG_YOUR_PASSWORD_WRONG'];
		$list = ChangePasswordUI($errormsg);
	}
	return $list;
}
function ChangePasswordUI($err='')
{
	global $mod_strings;
	$list = '';

        $list .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">';
        $list .= '<tr><td class="pageTitle uline">'.$mod_strings['LBL_MY_DETAILS'].'</td></tr></table>';
        $list .= '<table border="0" cellspacing="4" cellpadding="2" style="margin-top:10px">';

        $list .= '<tr><td align="right" nowrap>'.$mod_strings['LBL_LAST_LOGIN'].' : </td>';
        $list .= '<td><b>'.$_SESSION['last_login'].'</b></td></tr>';
        $list .= '<tr><td align="right" nowrap>'.$mod_strings['LBL_SUPPORT_START_DATE'].' : </td>';
        $list .= '<td><b>'.$_SESSION['support_start_date'].'</b></td></tr>';
        $list .= '<tr><td align="right" nowrap>'.$mod_strings['LBL_SUPPORT_END_DATE'].' : </td>';
        $list .= '<td><b>'.$_SESSION['support_end_date'].'</b></td></tr>';
        $list .= '</table>';

	$list .= '<br><br>';

        $list .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">';
        $list .= '<tr><td class="pageTitle uline">'.$mod_strings['LBL_CHANGE_PASSWORD'].'</td></tr>';
        $list .= '<tr><td style="padding-top: 10px">';

        if($err != '')
                $list .= "<div class='errorMsg' style='margin-bottom:5'>".$err."</div>";

	$list .= '<form name="Submit" method="POST" action="UserTickets.php"> ';
        $list .= '<input type=hidden name=fun value="savepassword">';
	$list .= '<table border="0" cellspacing="2" cellpadding="2">';
        $list .= '<tr><td align="right">'.$mod_strings['LBL_OLD_PASSWORD'].': </td>';
        $list .= '<td><input name="old_password" maxlength="255" type="password" value=""></td></tr>';
        $list .= '<tr><td align="right">'.$mod_strings['LBL_NEW_PASSWORD'].': </td>';
        $list .= '<td><input name="new_password" maxlength="255" type="password" value=""></td></tr>';
        $list .= '<tr><td align="right">'.$mod_strings['LBL_CONFIRM_PASSWORD'].': </td>';
        $list .= '<td><input name="confirm_password" maxlength="255" type="password" value=""></td></tr>';
	$list .= '<tr><td></td><td><input type=submit name=savepassword onclick="this.savepassword.value=true" value='.$mod_strings['LBL_SUBMIT'].'>&nbsp;&nbsp;</td>';
	$list .= '</table></form>';	

	return $list;
}

global $result;
$result = $client->call('get_tickets_list', $params, $Server_Path, $Server_Path);

echo "<table width='75%' align='center' border='0' cellspacing='0' cellpadding='0'><tr><td>";

$list = GetAllLinks($username);
echo $list;

if ($client->fault) 
{
	echo '<br><h2>Fault (This is expected)</h2><pre>'; print_r($result); echo '</pre>';
}
else 
{
	$err = $client->getError();
	if ($err) 
	{
		echo '<br><h2>Error</h2><pre>' . $err . '</pre>';
	}
	else 
	{
		//echo '<h2>Result</h2><pre>'; print_r($result); echo '</pre>';
	}
}
//These are the lines to print the Request, Response and Debug
//echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
//echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
//echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';

if($_REQUEST['updatecomment'] == 'true')
{
	$list = UpdateComment();
}
if($_REQUEST['fun'] == '' || $_REQUEST['fun'] == 'home')
{
	$list = HomeTickets($result);
	echo $list;
}
if(isset($_REQUEST['fun']) && $_REQUEST['fun'] == 'create')
{
	$list = CreateTicket();	
	echo $list;
}

if($_REQUEST['fun'] == 'changepassword')
{
	$list = ChangePasswordUI();	
	echo $list;
}

if(isset($_REQUEST['fun']) && $_REQUEST['fun'] == 'detail')
{
	$ticketid = $_REQUEST['ticketid'];
	$list = GetDetailView($result,$ticketid);
	echo $list;
}
if(isset($_REQUEST['fun']) && $_REQUEST['fun'] == 'close_ticket')
{
	$ticketid = $_REQUEST['ticketid'];
	$list = Close_Ticket($result,$ticketid);
	echo $list;
}

if($_REQUEST['fun'] == 'savepassword')
{
	$list = SavePassword();	
	echo $list;
}

if($_REQUEST['fun'] == 'save')
{
	$ticket = Array(
			'title'=>'title',
			'productid'=>'productid',
			'description'=>'description',
			'priority'=>'priority',
			'category'=>'category',
			'owner'=>'owner',
			'module'=>'module'
		       );

	foreach($ticket as $key => $val)
		$ticket[$key] = $_REQUEST[$key];
	$ticket['owner'] = $username;
	$ticket['productid'] = $_SESSION['combolist'][0]['productid'][$ticket['productid']];

	SaveTicket($ticket,$username);
}

echo "</td></tr></table>";



}
else
{
	header("Location: general.php?action=index&logout=true");
}
?>
<script language="javascript">
function OpenPopup(url)
{
	window.open(url,"preview", "width=600,height=400,status=yes,scrollbars=yes");
}
</script>
