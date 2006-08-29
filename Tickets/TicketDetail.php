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


global $result;
global $client;
global $mod_strings;

if($_REQUEST['ticketid'] != '')
	$ticketid = $_REQUEST['ticketid'];

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
			$ticket_close_link = '<a href=index.php?module=Tickets&action=index&fun=close_ticket&ticketid='.$ticketid.'><b>'.$mod_strings['LBL_CLOSE_TICKET'].'</b></a>&nbsp;&nbsp;&nbsp;';
		}
	}
}

$i = $ticket_position_in_array;
if($result[$i]['ticketid'] == $ticketid)
{

	$ticket_status = $result[$i]['status'];

	$list = '

<tr><td colspan="2">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
   <!-- <tr><td height="35">&nbsp;</td></tr> -->
   <tr>
	<td align="left">
        	<span class="lvtHeaderText">&nbsp;&nbsp;'.$mod_strings['LBL_TICKET_INFORMATION'].'</span>
		<hr noshade="noshade" size="1" width="90%" align="left"><br><br>
		<table width="95%"  border="0" cellspacing="0" cellpadding="5" align="center">
		   <tr>
			<td colspan="3" class="detailedViewHeader"><b>'.$mod_strings['LBL_TICKET_INFORMATION'].'</b></td>
			<td class="detailedViewHeader"><div align="right">'.$ticket_close_link.'</div>&nbsp;</td>	
		   </tr>  
		   <tr>
			<td class="dvtCellLabel" align="right">'.$mod_strings['LBL_TITLE'].'</td>
			<td colspan="3" class="dvtCellInfo">'.$result[$i]['title'].'</td>
		   </tr>
		   <tr>
			<td class="dvtCellLabel" align="right">'.$mod_strings['LBL_TICKET_ID'].'</td>
			<td class="dvtCellInfo">'.$result[$i]['ticketid'].'</td>
			<td class="dvtCellLabel" align="right">'.$mod_strings['LBL_PRODUCT_NAME'].'</td>
			<td class="dvtCellInfo">'.$result[$i]['productname'].'</td>
		   </tr>
		   <tr>
			<td class="dvtCellLabel" align="right" width="20%">'.$mod_strings['LBL_PRIORITY'].'</td>
			<td class="dvtCellInfo" width="20%">'.$result[$i]['priority'].'</td>
			<td class="dvtCellLabel" width="20%" align="right">'.$mod_strings['LBL_SEVERITY'].'</td>
			<td class="dvtCellInfo" width="20%">'.$result[$i]['severity'].'</td>
		   </tr>
		   <tr>
			<td class="dvtCellLabel" align="right">'.$mod_strings['LBL_STATUS'].'</td>
			<td class="dvtCellInfo">'.$result[$i]['status'].'</td>
			<td class="dvtCellLabel" align="right">'.$mod_strings['LBL_CATEGORY'].'</td>
			<td class="dvtCellInfo">'.$result[$i]['category'].'</td>
		   </tr>
		   <tr>
			<td class="dvtCellLabel" align="right">'.$mod_strings['LBL_CREATED_TIME'].'</td>
			<td class="dvtCellInfo">'.$result[$i]['createdtime'].'</td>
			<td class="dvtCellLabel" align="right">'.$mod_strings['LBL_MODIFIED_TIME'].'</td>
			<td class="dvtCellInfo">'.$result[$i]['modifiedtime'].'</td>
		   </tr>
		   <tr>
			<td class="dvtCellLabel" align="right">'.$mod_strings['LBL_DESCRIPTION'].'</td>
			<td colspan="3" class="dvtCellInfo">'.nl2br($result[$i]['description']).'</td>
		   </tr>
		   <tr>
			<td class="dvtCellLabel" align="right">'.$mod_strings['LBL_RESOLUTION'].'</td>
			<td colspan="3" class="dvtCellInfo">'.nl2br($result[$i]['solution']).'</td>
		   </tr>
		   <tr><td colspan="4">&nbsp;</td></tr>';

	//This is to display the existing comments if any
	if($commentscount >= 1 && is_array($commentresult))
	{

		$list .= '
		   <tr><td colspan="4" class="detailedViewHeader"><b>Comments</b></td></tr>
		   <tr>
			<td colspan="4" class="dvtCellInfo">
			   <div id="scrollTab2">
				<table width="100%"  border="0" cellspacing="5" cellpadding="5">';

		//Form the comments in between tr tags
		for($j=0;$j<$commentscount;$j++)
		{
			$list .= '
				   <tr>
					<td width="5%" valign="top">'.($commentscount-$j).'</td>
					<td width="95%">'.$commentresult[$j]['comments'].'<br><span class="hdr">'.$mod_strings['LBL_COMMENT_BY'].' : '.$commentresult[$j]['owner'].' '.$mod_strings['LBL_ON'].' '.$commentresult[$j]['createdtime'].'</span></td>
				   </tr>';
		}

		$list .= '
				</table>
			   </div>
			</td>
		   </tr>';
	}

	$list .= '
		   <tr><td colspan="4" >&nbsp;</td></tr>';

	//Provide the Add Comment option if the ticket is not Closed
	if($ticket_status != $mod_strings['LBL_STATUS_CLOSED'])
	{
		$list .= '
		   <tr><td colspan="4" class="detailedViewHeader"><b>'.$mod_strings['LBL_ADD_COMMENT'].'</b></td></tr>
		   <form name="comments" action="index.php" method="post">
		   <input type="hidden" name="module">
		   <input type="hidden" name="action">
		   <input type="hidden" name="fun">
		   <input type=hidden name=ticketid value='.$ticketid.'>
		   <tr>
			<td colspan="4" class="dvtCellInfo"><textarea name="comments" cols="55" rows="5" class="detailedViewTextBox"  onFocus="this.className=\'detailedViewTextBoxOn\'" onBlur="this.className=\'detailedViewTextBox\'"></textarea></td>
		   </tr>
		   <tr>
			    <td><input title="Save [Alt+S]" accesskey="S" class="small"  name="submit" value="Submit" style="width: 70px;" type="submit" onclick="this.form.module.value=\'Tickets\';this.form.action.value=\'index\';this.form.fun.value=\'updatecomment\'; return verify_data(this.form,coments);"/></td>
		   </form>
			    <td colspan="2">&nbsp;</td>
			    <td>&nbsp;</td>
		   </tr>';
	}

	$list .= '

		   <!--  Added for Attachment -->
		   <tr><td colspan=4>&nbsp;</td></tr>
		   <tr><td colspan="4" class="detailedViewHeader"><b>Attachments</b></td></tr>';

	//Get the attachments list and form in the tr tag
	$files_array = getTicketAttachmentsList($ticketid);

	$attachments_count = count($files_array);
	if(is_array($files_array))
	{
		for($j=0;$j<$attachments_count;$j++)
		{
			$filename = $files_array[$j]['filename'];
			$filetype = $files_array[$j]['filetype'];
			$filesize = $files_array[$j]['filesize'];
			$fileid = $files_array[$j]['fileid'];
			$filecontents = $files_array[$j]['filecontents'];
			$contentname = $fileid.'_filecontents';

			$_SESSION[$contentname] = $filecontents;

			//To display the attachments title
			$attachments_title = '';
			if($j == 0)
				$attachments_title = '<b>Attachment(s) : </b>';

			$list .= '
			   <tr>
				<td class="dvtCellLabel" align="right">'.$attachments_title.'</td>
				<td class="dvtCellInfo" colspan="3"><a href="index.php?downloadfile=true&fileid='.$fileid.'&filename='.$filename.'&filetype='.$filetype.'&filesize='.$filesize.'">'.ltrim($filename,$ticketid.'_').'</a></td>
			   </tr>';
		}
	}

	//To display the file upload error
	if($upload_status != '')
	{
		$list .= '<tr>
				<td class="dvtCellLabel" align="right"><b>File Upload Error : </b></td>
				<td class="dvtCellInfo" colspan="3"><font color="red">'.$upload_status.'</font></td>
			   </tr>';
		
	}

	//Provide the Add Comment option if the ticket is not Closed
	if($ticket_status != $mod_strings['LBL_STATUS_CLOSED'])
	{
		//To display the File Browse option to upload attachment	
		$list .= '
		   <tr>
			<form name="fileattachment" method="post" enctype="multipart/form-data" action="index.php">
			<input type="hidden" name="module" value="Tickets">
			<input type="hidden" name="action" value="index">
			<input type="hidden" name="fun" value="uploadfile">
			<input type="hidden" name="ticketid" value="'.$ticketid.'">
			<td class="dvtCellLabel" align="right">Attach File : </td>
			<td colspan=2 class="dvtCellInfo"><input type="file" size="50" name="customerfile" class="detailedViewTextBox"/></td>
			<td class="dvtCellInfo"><input name="Attach" type="submit" value="Attach"></td>
			</form>
		   </tr>';
	}

	$list .= '
		</table>
	 </td>
   </tr>
</table>
</td></tr>

';

	echo $list;

}
else
{
	$list = '<br><br>'.$mod_strings['LBL_NO_DETAILS_EXIST'];
	echo $list;
}


?>
