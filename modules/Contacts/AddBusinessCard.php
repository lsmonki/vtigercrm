<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php

?>
<form action='index.php' method='post'>
<input type="hidden" name="module" value="Contacts">
<input type="hidden" name="action" value="SaveBusinessCard">
<input type="hidden" name="handle" value="Save">
<script>
function toggleDisplay(id){
  
  if(this.document.getElementById( id).style.display=='none'){
    this.document.getElementById( id).style.display='inline'
    this.document.getElementById(id+"link").style.display='none';
    
  }else{
    this.document.getElementById(  id).style.display='none'
    this.document.getElementById(id+"link").style.display='none';
  }
}
</script>
<table cellpadding="0" cellspacing="0" width="500" border="0" >



<tr >	<td  valign='top' align='left' colspan='3' border='0'><table width="100%" cellpadding="0" cellspacing="0" border="0" class="formHeaderULine"><tbody><tr>
<td valign="bottom">
<table width="100%" cellpadding="0" cellspacing="0" border="0"><tbody><tr>
<td vAlign="middle" class="formHeader" align="left" noWrap width="100%" height="15">New Contact&nbsp;</td>
</tr></tbody></table></td><td><IMG height='1' src='include/images/blank.gif'></td>
</tr>
</tbody></table></td></tr>
<tr >
<td colspan='1' class='blackline'>&nbsp;</td>
<td  class='evenListRow' valign='top' align='left' colspan='1' >

<input type="hidden" name="record" value="">
<input type="hidden" name="assigned_user_id" value='1'>
<table class='evenListRow' border='0' width='100%'><tr><td nowrap cospan='1'>First Name:<br><input name="firstname" type="text" value=""></td><td colspan='1'><FONT class="required">*</FONT>&nbsp;Last Name:<br><input name='lastname' type="text" value=""></td></tr>
<tr><td colspan='4'><hr></td></tr>
<tr><td nowrap colspan='1'>Title:<br><input name='title' type="text" value=""></td><td nowrap colspan='1'>Department:<br><input name='department' type="text" value=""></td></tr>
<tr><td colspan='4'><hr></td></tr>
<tr><td> City:<BR><input name='mailingcity'  maxlength='100' value=''></td><td>State:<BR><input name='mailingstate'  maxlength='100' value=''></td><td>Postal Code:<BR><input name='mailingzip'  maxlength='100' value=''></td><td>Country:<BR><input name='mailingcountry'  maxlength='100' value=''></td></tr>
<tr><td colspan='4'><hr></td></tr>
<tr><td nowrap >Office Phone:<br><input name='phone' type="text" value=""></td><td nowrap >Mobile:<br><input name='mobile' type="text" value=""></td><td nowrap >Fax:<br><input name='fax' type="text" value=""></td></tr>
<tr><td colspan='4'><hr></td></tr>
<tr><td nowrap colspan='1'>Email:<br><input name='email' type="text" value=""></td><td nowrap colspan='1'>Other Email:<br><input name='otheremail' type="text" value=""></td></tr>

</table>
<div id='contactnotelink'><a href='javascript:toggleDisplay("contactnote");'>[New Note]</a></div><div id="contactnote" style="display:none">
<input type="hidden" name="ContactNotesrecord" value="">
<input type="hidden" name="ContactNotesparent_type" value="Accounts">

<FONT class="required">*</FONT>Note Subject:<br>
<input name='ContactNotesname' size='85' maxlength='255' type="text" value=""><br>
Note:<br><textarea name='ContactNotesdescription' cols='85' rows='4' ></textarea><br>
</div></td>
<td colspan='1' class='blackline'>&nbsp;</td>
</tr>
<tr><td colspan='3' class='blackline'></td></tr>
<tr ><td  valign='top' align='left' colspan='3' border='0'><td>&nbsp;</td></tr>

<tr >	<td  valign='top' align='left' colspan='3' border='0'><table width="100%" cellpadding="0" cellspacing="0" border="0" class="formHeaderULine"><tbody><tr>
<td valign="bottom">
<table width="100%" cellpadding="0" cellspacing="0" border="0"><tbody><tr>
<td vAlign="middle" class="formHeader" align="left" noWrap width="100%" height="15">New Account&nbsp;</td>
</tr></tbody></table></td><td><IMG height='1' src='include/images/blank.gif'></td>
</tr>
</tbody></table></td></tr>
<tr >
<td colspan='1' class='blackline'>&nbsp;</td>
<td  class='oddListRow' valign='top' align='left' colspan='1' ><table width='100%'><tr><td valing='top'>		<input type="hidden" name="Accountsrecord" value="">
<input type="hidden" name="Accountsemail1" value="">
<input type="hidden" name="Accountsemail2" value="">
<input type="hidden" name="assigned_user_id" value='1'>	
<FONT class="required">*</FONT>Account Name:<br>
<input name='account_name' type="text" value=""><br>
Phone:<br>
<input name='account_phone' type="text" value=""><br>
Website:<br>
http://<input name='account_website' type="text" value=""><br></td></tr></table><div id='accountnotelink'><a href='javascript:toggleDisplay("accountnote");'>[New Note]</a></div><div id="accountnote" style="display:none">			
<input type="hidden" name="AccountNotesrecord" value="">
<input type="hidden" name="AccountNotesparent_type" value="Accounts">

<FONT class="required">*</FONT>Note Subject:<br>
<input name='AccountNotesname' size='85' maxlength='255' type="text" value=""><br>
Note:<br><textarea name='AccountNotesdescription' cols='85' rows='4' ></textarea><br>
</div></td>
<td colspan='1' class='blackline'>&nbsp;</td>
</tr>
<tr><td colspan='3' class='blackline'></td></tr>
<tr ><td  valign='top' align='left' colspan='3' border='0'><td>&nbsp;</td></tr>

<tr >	<td  valign='top' align='left' colspan='3' border='0'><table width="100%" cellpadding="0" cellspacing="0" border="0" class="formHeaderULine"><tbody><tr>
<td valign="bottom">
<table width="100%" cellpadding="0" cellspacing="0" border="0"><tbody><tr>
<td vAlign="middle" class="formHeader" align="left" noWrap width="100%" height="15">New Appointment&nbsp;</td>
</tr></tbody></table></td><td><IMG height='1' src='include/images/blank.gif'></td>
</tr>
</tbody></table></td></tr>
<tr >
<td colspan='1' class='blackline'>&nbsp;</td>
<td  class='oddListRow' valign='top' align='left' colspan='1' ><table width='100%'><tr><td valign='top'><input type='radio' name='appointment' value='Call' checked> New Call<input type='radio' name='appointment' value='Meeting'> New Meeting<br>
<input type="hidden" name="Appointmentsrecord" value="">
<input type="hidden" name="Appointmentsstatus" value="Planned">
<input type="hidden" name="Appointmentsparent_type" value="Accounts">
<input type="hidden" name="Appointmentsassigned_user_id" value='1'>
<input type="hidden" name="Appointmentsduration_hours" value="1">
<input type="hidden" name="Appointmentsduration_minutes" value="00">
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/calendar-win2k-cold-1.css">
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<FONT class="required">*</FONT>Subject:<br>
<input name='Appointmentsname' size='30' maxlength='255' type="text"><br>
<FONT class="required">*</FONT>Start Date:&nbsp;<font size="1"><em>(yyyy-mm-dd)</em></font><br>
<input name='Appointmentsdate_start' id='jscal_field' maxlength='10' type="text" value="2005-02-17"> <img src="themes/blue/images/calendar.gif" id="jscal_trigger"><br>
<FONT class="required">*</FONT>Start Time:&nbsp;<font size="1"><em>(24:00)</em></font><br>
<input name='Appointmentstime_start' type="text" maxlength='5' value="06:12"><br><br>

<script type="text/javascript">
Calendar.setup ({
  inputField : "jscal_field", ifFormat : "%Y-%m-%d", showsTime : false, button : "jscal_trigger", singleClick : true, step : 1
    });
</script></td><td>Description:<br><textarea name='Appointmentsdescription' cols='50' rows='5'></textarea></td></tr></table></td>
<td colspan='1' class='blackline'>&nbsp;</td>
</tr>
<tr><td colspan='3' class='blackline'></td></tr>
<tr ><td  valign='top' align='left' colspan='3' border='0'><td>&nbsp;</td></tr>



</table>
<input title='Save [Alt+S]' accessKey='S' class='button' type='submit' name='button' value='  Save  ' >
</form>
</body>
</html>


<?


?>