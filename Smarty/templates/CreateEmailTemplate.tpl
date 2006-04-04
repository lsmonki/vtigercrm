<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
<style type="text/css">@import url(../themes/blue/style.css);</style>

<script language="JavaScript" type="text/javascript">
    var allOptions = null;

    function setAllOptions(inputOptions) 
    {ldelim}
        allOptions = inputOptions;
    {rdelim}

    function modifyMergeFieldSelect(cause, effect) 
    {ldelim}
        var selected = cause.options[cause.selectedIndex].value;  id="mergeFieldValue"
        var s = allOptions[cause.selectedIndex];
            
        effect.length = s;
        for (var i = 0; i < s; i++) 
	{ldelim}
            effect.options[i] = s[i];
        {rdelim}
        document.getElementById('mergeFieldValue').value = '';
    {rdelim}

    function init() 
    {ldelim}
        var blankOption = new Option('', '');
        var allOpts = new Object(0);
        var options = null;
        
            options = new Object(11);
            options[0] = blankOption;
                
                options[1] = new Option('Contact: First Name', '$contacts_firstname'); 
                options[2] = new Option('Contact: Last Name', '$contacts_lastname'); 
                options[3] = new Option('Contact: Salutation', '$contacts_salutationtype'); 
                options[4] = new Option('Contact: Title', '$contacts_title'); 
                options[5] = new Option('Contact: Email', '$contacts_email'); 
                options[6] = new Option('Contact: Department', '$contacts_department'); 
                options[7] = new Option('Contact: Other Email','$contacts_otheremail'); 
                options[8] = new Option('Contact: Phone', '$contacts_phone'); 
                options[9] = new Option('Contact: Mobile', '$contacts_mobile'); 
                options[10] = new Option('Contact: Currency', '$contacts_currency'); 
                            
            allOpts[1] = options;
        
            
            options = new Object(19);
            options[0] = blankOption;
            
                
                options[1] = new Option('Lead: First Name', '$leads_firstname'); 
                options[2] = new Option('Lead: Last Name', '$leads_lastname'); 
                options[3] = new Option('Lead: Lead Source', '$leads_leadsource'); 
                options[4] = new Option('Lead: Status', '$leads_leadstatus'); 
                options[5] = new Option('Lead: Rating', '$leads_rating'); 
                options[6] = new Option('Lead: Industry', '$leads_industry'); 
                options[7] = new Option('Lead: Yahoo ID', '$leads_yahooid'); 
                options[8] = new Option('Lead: Email', '$leads_email'); 
                options[9] = new Option('Lead: Annual Revenue', '$leads_annualrevenue'); 
                options[10] = new Option('Lead: Title', '$leads_designation'); 
                options[11] = new Option('Lead: Salutation', '$leads_salutation'); 
            
	        allOpts[2] = options;

	        options = new Object(19);
                options[0] = blankOption;
            
                options[1] = new Option('User: First Name', '$users_first_name'); 
                options[2] = new Option('User: Last Name', '$users_last_name'); 
		options[3] = new Option('User: Title', '$users_title'); 
		options[4] = new Option('User: Department', '$users_department'); 
		options[5] = new Option('User: HomePhone', '$users_phone_home'); 
		options[6] = new Option('User: Mobile', '$users_phone_mobile'); 
		options[7] = new Option('User: Signature', '$users_signature'); 
		options[8] = new Option('User: Email', '$users_email'); 
		options[9] = new Option('User: Street', '$users_address_street'); 
		options[10] = new Option('User: City', '$users_address_city'); 
		options[11] = new Option('User: State', '$users_address_state'); 
		options[11] = new Option('User: Country', '$users_address_country'); 
		options[11] = new Option('User: PostalCode', '$users_address_postalcode'); 
            
            	allOpts[3] = options;
	    
        setAllOptions(allOpts);
    {rdelim}
	
	function cancelForm(frm)
	{ldelim}
		frm.action.value='listemailtemplates'
		frm.submit()
	{rdelim}

</script>


<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
	{include file='SettingsMenu.tpl'}
<td width="75%" valign="top">
<form action="index.php" method="post" name="templatecreate" onsubmit="return check4null(templatecreate);">  
<input type="hidden" name="action">
<input type="hidden" name="mode" value="{$MODE}">
<input type="hidden" name="module" value="Users">
<input type="hidden" name="templateid" value="{$TEMPLATEID}">

<!-- EMAIL TEMPLATE PAGE STARTS HERE -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%">
	<tr>
		 <td class="showPanelBg" valign="top" width="90%"  style="padding-left:20px; "><br />
			{if $EMODE eq 'edit'}
        	        	<span class="lvtHeaderText"><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS}</a> > Communication Templates &gt; Editing Email Templates &gt; {$TEMPLATENAME}</b></span>
			{else}
				<span class="lvtHeaderText"><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS}</a> > Communication Templates &gt; Creating Email Templates &gt; New</b> </span>
			{/if}
            	    <hr noshade="noshade" size="1" />
		</td>
		<td width="10%" class="showPanelBg">&nbsp;</td>
	</tr>
	<tr>
		<td width="90%" style="padding-left:20px;" valign="top">
			<table width="100%"  border="0" cellspacing="0" cellpadding="0">
			  <tr><td colspan="2">&nbsp;</td></tr>
			  <tr>

				<td colspan="2">
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr>
						  <td width="5%" valign="top" align="right" nowrap>
								<img src="{$IMAGE_PATH}ViewTemplate.gif" align="left" />
							</td>
								<td width="5%" valign="top" align="right" nowrap>
									<b><font color='red'>*</font>{$UMOD.LBL_TEMPLATE_NAME}</b><br />
									<br /><br />
									<b>Description:</b><br />
									<br /><br />
									<b><font color='red'>*</font>Folder:</b>

								</td>
							<td width="85%" align="left" style="padding-left:10px;">
								<input name="templatename" type="text" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="{$TEMPLATENAME}" />
								<br />
								<textarea name="description" cols="30" rows="3" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" >{$DESCRIPTION}</textarea>
								<br />
								<select name="foldername" class="detailedViewTextBox" tabindex="1" value="{$FOLDERNAME}">
									<option value="Personal">{$UMOD.LBL_PERSONAL}</option>
									<option value="Public" selected>{$UMOD.LBL_PUBLIC}</option>
								</select>
							</td>
						</tr>
					</table>

				</td>
			  </tr>
			  <tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			  </tr>
			  <tr>
			    <td colspan="2" style="border:2px solid #CCCCCC;">
					<table width="100%" border="0" cellpadding="5" cellspacing="0">

	                  <tr>
    	                <td width="15%" align="right" bgcolor="#F6F6F6"><b><font color='red'>*</font>{$UMOD.LBL_TEMPLATE_SUBJECT}</b></td>
        	            <td width="60%" style="border-right:1px dashed #CCCCCC;">
							<input name="subject" type="text" class="detailedViewTextBox" value="{$SUBJECT}" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'"/></td>
            	      	<td width="25%" rowspan="3" style="padding:5px;text-align:left;vertical-align:top;">
							<table width="100%" border="0" cellpadding="0" cellspacing="0">
								<tr><td style="color:#999999;">{$UMOD.LBL_USE_MERGE_FIELDS_TO_EMAIL_CONTENT}</td></tr>

								<tr><td><b><u>Available Merge Fields</u></b></td></tr>
								<tr><td>&nbsp;</td></tr>

								<tr><td><b>{$UMOD.LBL_SELECT_FIELD_TYPE}</b></td></tr>
								<tr><td><select class="detailedViewTextBox" id="entityType" ONCHANGE="modifyMergeFieldSelect(this, document.getElementById('mergeFieldSelect'));">
								<OPTION VALUE="0" selected>                            
                            		
                        				        <OPTION VALUE="1">{$UMOD.LBL_CONTACT_FIELDS}
                            
								<OPTION VALUE="2" >{$UMOD.LBL_LEAD_FIELDS}

                                				<OPTION VALUE="3" >{$UMOD.LBL_USER_FIELDS}
                            
								</select></td></tr>
								<tr><td>&nbsp;</td></tr>
								<tr><td><b>{$UMOD.LBL_SELECT_FIELD}</b></td></tr>
								<tr><td><select class="detailedViewTextBox" id="mergeFieldSelect" onchange="document.getElementById('mergeFieldValue').value=this.options[this.selectedIndex].value;"></select></td></tr>
								<tr><td>&nbsp;</td></tr>
								<tr><td><b>{$UMOD.LBL_MERGE_FIELD_VALUE}</b></td></tr>

								<tr><td><input type="text"  id="mergeFieldValue" name="variable" value="variable" class="detailedViewTextBoxOn" /></td></tr>
								<tr><td style="color:#999999;">({$UMOD.LBL_COPY_AND_PASTE_MERGE_FIELD})</td></tr>
							</table>
							<script>
						                modifyMergeFieldSelect(document.getElementById('mergeTypeSelect'), document.getElementById('mergeFieldSelect'));
						        </script>
						
						</td>

					  </tr>
					  <tr>
					  	<td bgcolor="#F6F6F6">&nbsp;</td>

					  	<td style="border-right:1px dashed #CCCCCC;">&nbsp;</td>
					  </tr>
	                  <tr>
	                    <td  align="right" bgcolor="#F6F6F6" valign="top"><b>{$UMOD.LBL_TEMPLATE_MESSAGE}</b></td>
	                    <td style="border-right:1px dashed #CCCCCC;" nowrap>
				<textarea name="body" rows="10" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'">{$BODY}</textarea>
			    </td>
			  </tr>

               	  </table>
		      </td>
		      </tr>
			</table>
		</td>
		<td>&nbsp;</td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>

		<td align="center">
			<input type="submit" value="Save" class="small" onclick="this.form.action.value='saveemailtemplate'" />&nbsp;&nbsp;
			<input type="submit" value="Cancel" class="small" onclick="cancelForm(this.form)" />
		</td>
		<td>&nbsp;</td>
	</tr>
	
</table>

</form>
</td>
</tr>
</table>

<script>

function check4null(form)
{ldelim}
        var isError = false;
        var errorMessage = "";
        // Here we decide whether to submit the form.
        if (trim(form.templatename.value) =='') {ldelim}
                isError = true;
                errorMessage += "\n template name";
                form.templatename.focus();
        {rdelim}
        if (trim(form.foldername.value) =='') {ldelim}
                isError = true;
                errorMessage += "\n folder name";
                form.foldername.focus();
        {rdelim}
        if (trim(form.subject.value) =='') {ldelim}
                isError = true;
                errorMessage += "\n subject";
                form.subject.focus();
        {rdelim}

        // Here we decide whether to submit the form.
        if (isError == true) {ldelim}
                alert("Missing required fields: " + errorMessage);
                return false;
        {rdelim}
 return true;
{rdelim}

init();

</script>

{$JAVASCRIPT}
{include file='SettingsSubMenu.tpl'}

