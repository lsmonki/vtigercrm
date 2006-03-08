	<!-- Add Event DIV starts-->
	<div class="calAddEvent" style="display:none;width:600px" id="addEmail_<?php echo $num; ?>" align=center> 
	<form name="newRoleForm">	
		<!-- Attachments, Thread, Body starts-->
		<br>
		<table border=0 cellspacing=0 cellpadding=0 width="100%" align=center style="width:100%">
		<tr>
			<td align="right">
			<table border=0 cellspacing=0 cellpadding=5 width=100% class="addEventHeader">
                		<tr>
                        		<td  align=right>
						<a href="javascript:ghide('addEmail_<?php echo $num;?>');reset_timer();">Close [X]</a>
                        		</td>
                		</tr>
				<tr>
					<?
					require_once("modules/Webmails/Webmail.php");
					$webmail=new Webmail($mbox,$num);
					?>
					<td valign=top><b>Actions:</b><br>
					<table border=0 cellspacing=0 cellpadding=5 width=100% >
                				<tr>
                        				<td  align="left">
								<form name="addToVtiger" method="POST">
								<input type="hidden" name="module" value="Webmails">
								<input type="hidden" name="reply">
								<input type="hidden" name="action" value="Save">
								<input type="hidden" name="user_id" value="<?php echo $_SESSION["authenticated_user_id"];?>">
								<input type="hidden" name="mailid" value="<?php echo $num;?>">
								<input type="hidden" name="subject" value="<?php echo $webmail->subject;?>">
								<input title="Save" class="small" onclick="this.form.action.value='EditView';this.form.reply.value='single';return true;" type="submit" name="button" value="  Reply  " >
								<input title="Save" class="small" onclick="this.form.action.value='EditView';this.form.reply.value='all';return true;" type="submit" name="button" value="  Reply To All  " >
								<input title="Save" class="small" onclick="this.form.action.value='Save';return true;" type="submit" name="button" value="  Add to Vtiger  " >
								</form>
                        				</td>
                				</tr>
                			</table>
					</td>
				</tr>
                	</table>
			</td>
		</tr>
			<td><br>
				<table border=0 cellspacing=0 cellpadding=3 width=100%>
				<tr>
					<td class="dvtTabCache" style="width:10px">&nbsp;</td>
					<td id="cellTabAttachments_<?php echo $num; ?>" class="dvtSelectedCell" align=center nowrap><a href="javascript:void(0);" onClick="switchClass('cellTabBody_<?php echo $num; ?>','off');switchClass('cellTabAttachments_<?php echo $num; ?>','on');gshow('showAttachmentsUI_<?php echo $num; ?>');ghide('showBodyUI_<?php echo $num; ?>');">General Information</a></td>
					<?php if($showbody == "yes") {$extra_style="";} else {$extra_style="";} ?>
						<td class="dvtTabCache" style="width:10px;<?php echo $extra_style;?>" nowrap>&nbsp;</td>
						<td style="<?php echo $extra_style;?>" id="cellTabBody_<?php echo $num; ?>" class="dvtUnSelectedCell" align=center nowrap><a href="javascript:void(0);" onClick="switchClass('cellTabBody_<?php echo $num; ?>','on');switchClass('cellTabAttachments_<?php echo $num; ?>','off');ghide('showAttachmentsUI_<?php echo $num; ?>');gshow('showBodyUI_<?php echo $num; ?>');">Body</a></td> 
					<td class="dvtTabCache" style="width:100%">&nbsp;</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td width=100% valign=top align=left class="dvtContentSpace" style="padding:10px">
			<!-- Body UI -->
				<DIV id="showBodyUI_<?php echo $num; ?>" style="display:none;width:100%">
				<table border
				<table border=0 cellspacing=0 cellpadding=2 width=100%>
				<tr>
					<td width="100%" colspan="4">
					<table width="100%" align="center" border=0>
					    <tr>
						<td valign=top align="left" nowrap width="30%">From: <?php echo $from;?></td>
						<td valign=top align="left" nowrap width="30%">To: <?php echo $webmail->to[0];?></td>
					    </tr>
					    <tr>
						<td valign=top align="left" nowrap width="90%">Subject: <?php echo $webmail->subject;?></td>
					    </tr>
					    <tr>
						<td nowrap align="center" width="100%" colspan="3" class="addEventHeader">&nbsp;</td>
					    </tr>
					</table>
					</td>
				</tr>
					<td valign=top>
					<?
					if($showbody == "yes") {
						$webmail->loadMail();
						if(preg_match("/<style(.*)/i",$webmail->body) || preg_match("/<html(.*)/i",$webmail->body) ||preg_match("/<a (.*)/i",$webmail->body) || preg_match("/<img(.*)/i",$webmail->body))
							echo '<iframe src="index.php?module=Webmails&action=body&mailid='.$num.'&mailbox='.$mailbox.'" width="100%" height="210">'.$tmp.'</iframe>';
						else {
							echo '<div style="overflow:auto;height:210px;width:100%">';
							echo br2nl(($webmail->body));
							echo '</div>';
						}
					} else {
							echo '<div style="overflow:auto;height:210px">';
							echo 'You must enable body views in your email settings';
							echo 'to see the body of emails in the Quick View window';
							echo '</div>';
					}
					?>
					</td>
				</tr>
				</table>
				</DIV>
			
			<!-- Attachments UI -->
				<DIV id="showAttachmentsUI_<?php echo $num; ?>" style="display:block;width:100%">
				<table border=0 cellspacing=0 cellpadding=5  width=100%>
				<tr>
					<td nowrap align="left" width="50%" valign=top>
						<b>Attachments : </b>
					</td>
				</tr>
				</td>
				<tr>
				<td width="50%">
				<table border=0 cellspacing=0 cellpadding=0 width="100%" align="left" valign="top">
				<tr>
				  <td width="100%">
					<?
					if($attachments=getAttachmentDetails($start_message,$mbox)) {
					    $cnt=1;
					    for($j=0;$j<count($attachments);$j++) {
                				$fname=$attachments[$j]["filename"];
						 $filesize = $attachments[$i]["filesize"]." bytes";
        					 if($attachments[$j]["filesize"] > 1000000)
                					$filesize= substr((($attachments[$j]["filesize"]/1024)/1024),0,5)." megabytes";
        					 elseif ($attachments[$j]["filesize"] > 1024)
                					$filesize= substr(($attachments[$j]["filesize"]/1024),0,5)." kilobytes";

        					$at= "<tr><td width=100% valign='top'>".$cnt.") <a target='_blank' href='index.php?module=Webmails&action=dlAttachments&mailid=".$num."&num=".$j."'>".$fname."</a></b><br><i>".$filesize."</i><br> </td></tr>";
						$cnt++;
						echo $at;
    					    }
					} else {
						echo "<tr rowspan='3'><td width=80%><i>No Attachments</i></td></tr>";
					}
					?>
				  </td>
				</tr>
				</table>
				</td>
				</tr>
				<tr>
					<td nowrap align="center" width="100%" colspan="3" class="addEventHeader">&nbsp;</td>
				</tr>
				<tr>
				<td width="50%">
				<table border=0 cellspacing=0 cellpadding=0 width="50%" align="left">
					<?
					$relationship = $webmail->relationship;
					if($relationship != 0) {
						echo '<tr><td nowrap align="left" width="50%" valign="top"><b>Relationship Found: </b></td></tr>';
						echo "<tr><td width='100%' align='left' nowrap>Type: ".$relationship["type"]."</td></tr>";
						echo "<tr><td width='100%' align='left' nowrap>Name: <a href='index.php?module=".$relationship["type"]."&action=DetailView&record=".$relationship["id"]."'>".$relationship["name"]."</a></td></tr>";
					} else {
						echo '<tr><td nowrap align="left" width="50%" valign="top"><b>Create New Relationship: </b></td></tr>';
						echo "<tr><td width='100%' align='left'><a href='index.php?module=Leads&action=EditView&return_module=Webmails&return_action=ListView'>New Lead</a></td></tr>";
						echo "<tr><td width='100%' align='left'><a href='index.php?module=Contacts&action=EditView&return_module=Webmails&return_action=ListView'>New Contact</a></td></tr>";
						echo "<tr><td width='100%' align='left'><a href='index.php?module=Accounts&action=EditView&return_module=Webmails&return_action=ListView'>New Account</a></tr></td>";
					}
					?>
				</table>
				</td>
				</tr>
				<tr>
					<td nowrap align="center" width="100%" colspan="3" class="addEventHeader">&nbsp;</td>
				</tr>
				<tr>
				<td width="100%" colspan="2">
				<table border=0 cellspacing=0 cellpadding=2  width=100%>
				<tr>
					<td nowrap align=left width=100% valign=top>
					<strong>Mail Threads :</strong>
					</td>
				</tr>
					<?
					  for($k=1;$k<count($thread_view);$k++) {
						if($thread_view[$num]["message_id"] == $thread_view[$k]["in_reply_to"] && $thread_view[$num]["message_id"] != "") {
					      		echo '<tr><td>Replies: <a href="index.php?module=Webmails&action=DetailView&record=&mailbox='.$mailbox.'&mailid='.$thread_view[$k]["id"].'&parenttab=My%20Home%20Page">'.$thread_view[$k]["subject"].'</a></td></tr>';
						}
						if((isset($thread_view[$num]["in_reply_to"])) && ($thread_view[$num]["in_reply_to"] == $thread_view[$k]["message_id"])) {
					      		echo '<tr><td>Parent Message: <a href="index.php?module=Webmails&action=DetailView&record=&mailbox='.$mailbox.'&mailid='.$thread_view[$k]["id"].'&parenttab=My%20Home%20Page">'.$thread_view[$k]["subject"].'</a></td></tr>';
						}
					  }

					 //if($thread_p=='' && $thread_t=='')
					  	//echo '<tr><td><i>None</i></td></tr>';
					?>
				</table>
				</td></tr>
				</table>
				</DIV>
			</td>
		</tr>
		</table>
		<!-- Attachments, Thread, Body stops-->
<br>
		
</form>
</div>
