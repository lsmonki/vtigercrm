<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<!--
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/
-->
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/calendar-win2k-cold-1.css">
<?php
	echo get_module_title($_REQUEST["module"],"Add Business Card",true);
?>
<form name="AddBusinessCard" action='index.php' method='post'>
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
	document.AddBusinessCard.elements[id+"srecord"].value="true";
}
</script>
  <script type="text/javascript" src="jscalendar/calendar.js"></script>
  <script type="text/javascript" src="jscalendar/lang/calendar-en.js"></script>
  <script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
  <script type="text/javascript" src="include/general.js"></script>
  <table width="70%" cellpadding="0" cellspacing="1" border="0" class="formOuterBorder">
    <tbody>
      <tr> 
        <td class="formSecHeader"><?php echo $mod_strings['LBL_NEW_CONTACT'] ?></td>
      </tr>
      <tr> 
        <td> <input type="hidden" name="record" value=""> <input type="hidden" name="assigned_user_id" value='1'> 
          <table border='0' width='100%' cellpadding="2" cellspacing="0">
            <tr> 
              <td nowrap><?php echo $mod_strings['LBL_FIRST_NAME'] ?><br> <input name="firstname" type="text" value=""></td>
              <td><FONT class="required">*</FONT>&nbsp;<?php echo $mod_strings['LBL_LAST_NAME'] ?><br> <input name='lastname' type="text" value=""></td>
            </tr>
            <tr> 
              <td nowrap><?php echo $mod_strings['LBL_TITLE'] ?><br> <input name='title' type="text" value=""></td>
              <td nowrap><?php echo $mod_strings['LBL_DEPARTMENT'] ?><br> <input name='department' type="text" value=""></td>
            </tr>
            <tr> 
              <td><?php echo $mod_strings['LBL_CITY'] ?><BR> <input name='mailingcity'  maxlength='100' value=''></td>
              <td><?php echo $mod_strings['LBL_STATE'] ?><BR> <input name='mailingstate'  maxlength='100' value=''></td>
              <td><?php echo $mod_strings['LBL_POSTAL_CODE'] ?><BR> <input name='mailingzip'  maxlength='100' value=''></td>
              <td><?php echo $mod_strings['LBL_COUNTRY'] ?><BR> <input name='mailingcountry'  maxlength='100' value=''></td>
            </tr>
            <tr> 
              <td nowrap><?php echo $mod_strings['LBL_OFFICE_PHONE'] ?><br> <input name='phone' type="text" value=""></td>
              <td nowrap><?php echo $mod_strings['LBL_MOBILE_PHONE'] ?><br> <input name='mobile' type="text" value=""></td>
              <td nowrap><?php echo $mod_strings['LBL_FAX_PHONE'] ?><br> <input name='fax' type="text" value=""></td>
            </tr>
            <tr> 
              <td nowrap><?php echo $mod_strings['LBL_EMAIL_ADDRESS'] ?><br> <input name='email' type="text" value=""></td>
              <td nowrap><?php echo $mod_strings['LBL_OTHER_EMAIL_ADDRESS'] ?><br> <input name='otheremail' type="text" value=""></td>
            </tr>
          </table>
          <div id='contactnotelink' style="margin:2px"><a href='javascript:toggleDisplay("contactnote");'>[<?php echo $mod_strings['LBL_NEW_NOTE'] ?>]</a></div>
          <div id="contactnote" style="display:none;"> 
            <input type="hidden" name="contactnotesrecord" value="">
            <input type="hidden" name="ContactNotesparent_type" value="Accounts">
            <table border='0' width='100%' cellpadding="2" cellspacing="0">
              <tr> 
                <td><FONT class="required">*</FONT><?php echo $mod_strings['LBL_NOTE_SUBJECT'] ?><br> <input name='ContactNotesname' size='85' maxlength='255' type="text" value=""></td>
              </tr>
              <tr> 
                <td> <?php echo $mod_strings['LBL_NOTE'] ?><br> <textarea name='ContactNotesdescription' cols='85' rows='4' ></textarea> 
                </td>
              </tr>
            </table>
          </div></td>
      </tr>
    </tbody>
  </table>
  <br>
  <table width="70%" cellpadding="0" cellspacing="1" border="0" class="formOuterBorder">
    <tbody>
      <tr> 
        <td class="formSecHeader"><?php echo $mod_strings['LBL_NEW_ACCOUNT'] ?></td>
      </tr>
      <tr> 
        <td><input type="hidden" name="Accountsrecord" value=""> <input type="hidden" name="Accountsemail1" value=""> 
          <input type="hidden" name="Accountsemail2" value=""> <input type="hidden" name="assigned_user_id" value='1'> 
          <table width="100%" cellpadding="0" cellspacing="2" border="0">
            <tr> 
              <td> <FONT class="required">*</FONT><?php echo $mod_strings['LBL_ACCOUNT_NAME'] ?><br> <input name='account_name' type="text" value=""> 
              </td>
            </tr>
            <tr> 
              <td> <?php echo $mod_strings['LBL_PHONE'] ?><br> <input name='account_phone' type="text" value=""> 
              </td>
            </tr>
            <tr> 
              <td> <?php echo $mod_strings['LBL_WEBSITE'] ?><br>
                http:// 
                <input name='account_website' type="text" value="" size="35"> 
              </td>
            </tr>
          </table>
          <div id='accountnotelink' style="margin:2px"><a href='javascript:toggleDisplay("accountnote");'>[<?php echo $mod_strings['LBL_NEW_NOTE'] ?>]</a></div>
          <div id="accountnote" style="display:none"> 
            <input type="hidden" name="accountnotesrecord" value="">
            <input type="hidden" name="AccountNotesparent_type" value="Accounts">
            <table width="100%" cellpadding="0" cellspacing="2" border="0">
              <tr> 
                <td> <FONT class="required">*</FONT><?php echo $mod_strings['LBL_NOTE_SUBJECT'] ?><br> <input name='AccountNotesname' size='85' maxlength='255' type="text" value=""> 
                </td>
              </tr>
              <tr> 
                <td> <?php echo $mod_strings['LBL_NOTE'] ?><br> <textarea name='AccountNotesdescription' cols='85' rows='4' ></textarea> 
                </td>
              </tr>
            </table>
          </div></td>
      </tr>
  </table>
  <br>
  <table width="70%" cellpadding="0" cellspacing="1" border="0" class="formOuterBorder">
    <tbody>
      <tr> 
        <td class="formSecHeader"><?php echo $mod_strings['LBL_NEW_APPOINTMENT'] ?></td>
      </tr>
      <tr> 
        <td> <input type="hidden" name="Appointmentsrecord" value=""> <input type="hidden" name="Appointmentsstatus" value="Planned"> 
          <input type="hidden" name="Appointmentsparent_type" value="Accounts"> 
          <input type="hidden" name="Appointmentsassigned_user_id" value='1'> 
          <input type="hidden" name="Appointmentsduration_hours" value="1"> <input type="hidden" name="Appointmentsduration_minutes" value="00"> 
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr> 
              <td><input type='radio' name='appointment' value='Call' checked>
                &nbsp;<?php echo $mod_strings['LBL_NEW_CALL'] ?>
                <input type='radio' name='appointment' value='Meeting'>
                <?php echo $mod_strings['LBL_NEW_MEETING'] ?> </td>
            </tr>
            <tr> 
              <td><FONT class="required">*</FONT><?php echo $mod_strings['LBL_SUBJECT'] ?><br> <input name='Appointmentsname' size='35' maxlength='255' type="text"></td>
            </tr>
            <tr> 
              <td><FONT class="required">*</FONT><?php echo $mod_strings['LBL_START_DATE'] ?>&nbsp;<font size="1"><em><?php echo $app_strings['NTC_DATE_FORMAT'] ?></em></font><br> 
                <input name='Appointmentsdate_start' id='jscal_field' maxlength='10' type="text" value="2005-02-17" size="10"> 
                <img src="themes/blue/images/calendar.gif" id="jscal_trigger"></td>
            </tr>
            <tr> 
              <td></td>
            </tr>
            <tr> 
              <td><FONT class="required">*</FONT><?php echo $mod_strings['LBL_START_TIME'] ?>&nbsp;<font size="1"><em>(24:00)</em></font><br>
			  <input name='Appointmentstime_start' type="text" maxlength='5' value="06:12" size="5"> 
                <script type="text/javascript">
Calendar.setup ({
  inputField : "jscal_field", ifFormat : "%Y-%m-%d", showsTime : false, button : "jscal_trigger", singleClick : true, step : 1
    });
</script></td>
            </tr>
            <tr> 
				<td><?php echo $mod_strings['LBL_DESCRIPTION'] ?><br>
              <textarea name='Appointmentsdescription' cols='85' rows='4'></textarea>
			  </td>
            </tr>
          </table></td>
      </tr>
  </table>
  <br>
  <div align="center" style="width:70%"><input title='Save [Alt+S]' accessKey='S' class='button' type='submit' name='button' onclick="return formValidate(AddBusinessCard);" value='<?php echo $app_strings['LBL_SAVE_BUTTON_LABEL'] ?>'></div>
</form>
</body>
</html>

<script>
function formValidate(form)
{
	if(form.lastname.value == '')
	{
		alert("Enter Last Name for Contact");
		return false;
	}
	if(form.contactnotesrecord.value == "true" && form.ContactNotesname.value == '')
	{
		alert("Enter the Note's Subject related to Contact.");
		return false;
	}
	if(form.account_name.value == '')
        {
                alert("Enter Account Name");
                return false;
        }
	if(form.accountnotesrecord.value  == "true" && form.AccountNotesname.value == '')
	{
		alert("Enter the Note's Subject related to Account.");
		return false;
	}
	if(form.Appointmentsname.value == '')
	{
		alert("Enter the Subject for New Appoinment");
		return false;
	}
	dateflag = dateValidate("Appointmentsdate_start","Start Date","D~M");
	timeflag = timeValidate("Appointmentstime_start","Start Time","OTH");
	if (dateflag == "false" || timeflag == "false")
	{
		alert("Date or Time may not correct.");
		return false;
	}

	return true;
}
</script>
<?


?>
