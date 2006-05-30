{*<!--
/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
   * ("License"); You may not use this file except in compliance with the License
   * The Original Code is:  vtiger CRM Open Source
   * The Initial Developer of the Original Code is vtiger.
   * Portions created by vtiger are Copyright (C) vtiger.
   * All Rights Reserved.
  *
 ********************************************************************************/
-->*}
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
{literal}
    function init() 
    {
        var blankOption = new Option('--None--', '--None--');
        var allOpts = new Object(0);
        var options = null;
            
	    options = new Object(19);
            options[0] = blankOption;
            
                
                options[1] = new Option('Account: Account Name', '$accounts-accountname$'); 
                options[2] = new Option('Account: Account Type', '$accounts-account_type$'); 
                options[3] = new Option('Account: Industry', '$accounts-industry$'); 
                options[4] = new Option('Account: Annual Revenue', '$accounts-annualrevenue$'); 
                options[5] = new Option('Account: Phone', '$accounts-phone$'); 
                options[6] = new Option('Account: Email', '$accounts-email1$'); 
                options[7] = new Option('Account: Rating', '$accounts-rating$'); 
                options[8] = new Option('Account: Website', '$accounts-website$'); 
                options[9] = new Option('Account: Fax', '$accounts-fax$'); 
            
	        allOpts[1] = options;
        
            options = new Object(11);
            options[0] = blankOption;
                
                options[1] = new Option('Contact: First Name', '$contacts-firstname$'); 
                options[2] = new Option('Contact: Last Name', '$contacts-lastname$'); 
                options[3] = new Option('Contact: Salutation', '$contacts-salutationtype$'); 
                options[4] = new Option('Contact: Title', '$contacts-title$'); 
                options[5] = new Option('Contact: Email', '$contacts-email$'); 
                options[6] = new Option('Contact: Department', '$contacts-department$'); 
                options[7] = new Option('Contact: Other Email','$contacts-otheremail$'); 
                options[8] = new Option('Contact: Phone', '$contacts-phone$'); 
                options[9] = new Option('Contact: Mobile', '$contacts-mobile$'); 
                options[10] = new Option('Contact: Currency', '$contacts-currency$'); 
                            
            allOpts[2] = options;
        
            
            options = new Object(19);
            options[0] = blankOption;
            
                
                options[1] = new Option('Lead: First Name', '$leads-firstname$'); 
                options[2] = new Option('Lead: Last Name', '$leads-lastname$'); 
                options[3] = new Option('Lead: Lead Source', '$leads-leadsource$'); 
                options[4] = new Option('Lead: Status', '$leads-leadstatus$'); 
                options[5] = new Option('Lead: Rating', '$leads-rating$'); 
                options[6] = new Option('Lead: Industry', '$leads-industry$'); 
                options[7] = new Option('Lead: Yahoo ID', '$leads-yahooid$'); 
                options[8] = new Option('Lead: Email', '$leads-email$'); 
                options[9] = new Option('Lead: Annual Revenue', '$leads-annualrevenue$'); 
                options[10] = new Option('Lead: Title', '$leads-designation$'); 
                options[11] = new Option('Lead: Salutation', '$leads-salutation$'); 
            
	        allOpts[3] = options;

	        options = new Object(19);
                options[0] = blankOption;
            
                options[1] = new Option('User: First Name', '$users-first_name$'); 
                options[2] = new Option('User: Last Name', '$users-last_name$'); 
		options[3] = new Option('User: Title', '$users-title$'); 
		options[4] = new Option('User: Department', '$users-department$'); 
		options[5] = new Option('User: HomePhone', '$users-phone_home$'); 
		options[6] = new Option('User: Mobile', '$users-phone_mobile$'); 
		options[7] = new Option('User: Signature', '$users-signature$'); 
		options[8] = new Option('User: Email', '$users-email$'); 
		options[9] = new Option('User: Street', '$users-address_street$'); 
		options[10] = new Option('User: City', '$users-address_city$'); 
		options[11] = new Option('User: State', '$users-address_state$'); 
		options[11] = new Option('User: Country', '$users-address_country$'); 
		options[11] = new Option('User: PostalCode', '$users-address_postalcode$'); 
            
            	allOpts[4] = options;
	    
        setAllOptions(allOpts);
    }
	
	function cancelForm(frm)
	{
		frm.action.value='detailviewemailtemplate'
		frm.submit()
	}
{/literal}
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
<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%" class="small">
	<tr>
		 <td class="showPanelBg" valign="top" width="90%"  style="padding-left:20px; "><br />
			{if $EMODE eq 'edit'}
        	        	<span class="lvtHeaderText"><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS}</a>
				<a href="index.php?module=Users&action=detailviewemailtemplate&templateid={$TEMPLATEID}"> &gt; {$MOD.LBL_COMMUNICATION_TEMPLATES} &gt; {$MOD.LBL_EDIT} {$MOD.EMAILTEMPLATES} &gt; {$TEMPLATENAME}</a></b></span>
			{else}
				<span class="lvtHeaderText"><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS}</a>
				<a href="index.php?module=Users&action=listemailtemplates">&gt; {$MOD.LBL_COMMUNICATION_TEMPLATES} &gt; {$MOD.LBL_CREATE_EMAIL_TEMPLATES} &gt; {$MOD.NEW}</a></b> </span>
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
					<table width="100%" border="0" cellpadding="0" cellspacing="0" class="small">
						<tr>
						  <td width="5%" valign="top" align="right" nowrap>
								<img src="{$IMAGE_PATH}ViewTemplate.gif" align="left" />
							</td>
								<td width="5%" valign="top" align="right" nowrap>
									<b><font color='red'>*</font>{$UMOD.LBL_TEMPLATE_NAME}</b><br />
									<br />
									<b>{$UMOD.LBL_DESCRIPTION}{$UMOD.LBL_COLON}</b><br />
									<br /><br />
									<b><font color='red'>{$APP.LBL_REQUIRED_SYMBOL}</font>{$UMOD.LBL_FOLDER}{$UMOD.LBL_COLON}</b>

								</td>
							<td width="85%" align="left" style="padding-left:10px;">
								<input name="templatename" type="text" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="{$TEMPLATENAME}" tabindex="1"/>
								<br />
								<textarea name="description" cols="30" rows="3" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" tabindex="2" >{$DESCRIPTION}</textarea>
								<br />
								{if $EMODE eq 'edit'}
									<select name="foldername" class="detailedViewTextBox" tabindex="3">
                                                                {foreach item=arr from=$FOLDERNAME}

                                                                 <option value="{$FOLDERNAME}" {$arr}>{$FOLDERNAME}</option>

                                                                        {if $FOLDERNAME == 'Public'}
                                                                                <option value="Personal">{$UMOD.LBL_PERSONAL}</option>
                                                                        {else}
                                                                                <option value="Public">{$UMOD.LBL_PUBLIC}</option>
                                                                        {/if}

                                                                {/foreach}
                                                                </select>
								{else}
									<select name="foldername" class="detailedViewTextBox" tabindex="3" value="{$FOLDERNAME}">
                	                                                        <option value="Personal">{$UMOD.LBL_PERSONAL}</option>
                        	                                                <option value="Public" selected>{$UMOD.LBL_PUBLIC}</option>
        	                                                        </select>
								{/if}
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
					<table width="100%" border="0" cellpadding="5" cellspacing="0" class="small">

	                  <tr>
    	                <td width="15%" align="right" bgcolor="#F6F6F6"><b><font color='red'>*</font>{$UMOD.LBL_TEMPLATE_SUBJECT}</b></td>
        	            <td width="60%" style="border-right:1px dashed #CCCCCC;">
							<input name="subject" type="text" class="detailedViewTextBox" value="{$SUBJECT}" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" tabindex="4"/></td>
            	      	<td width="25%" rowspan="3" style="padding:5px;text-align:left;vertical-align:top;">
							<table width="100%" border="0" cellpadding="0" cellspacing="0" class="small">
								<tr><td style="color:#999999;">{$UMOD.LBL_USE_MERGE_FIELDS_TO_EMAIL_CONTENT}</td></tr>

								<tr><td><b><u>{$UMOD.LBL_AVAILABLE_MERGE_FIELDS}</u></b></td></tr>
								<tr><td>&nbsp;</td></tr>

								<tr><td><b>{$UMOD.LBL_SELECT_FIELD_TYPE}</b></td></tr>
								<tr><td><select class="detailedViewTextBox" id="entityType" ONCHANGE="modifyMergeFieldSelect(this, document.getElementById('mergeFieldSelect'));" tabindex="6">
								<OPTION VALUE="0" selected>{$APP.LBL_NONE}                            
								<OPTION VALUE="1">{$UMOD.LBL_ACCOUNT_FIELDS}                            
                        				        <OPTION VALUE="2">{$UMOD.LBL_CONTACT_FIELDS}
                            
								<OPTION VALUE="3" >{$UMOD.LBL_LEAD_FIELDS}

                                				<OPTION VALUE="4" >{$UMOD.LBL_USER_FIELDS}
                            
								</select></td></tr>
								<tr><td>&nbsp;</td></tr>
								<tr><td><b>{$UMOD.LBL_SELECT_FIELD}</b></td></tr>
								<tr><td><select class="detailedViewTextBox" id="mergeFieldSelect" onchange="document.getElementById('mergeFieldValue').value=this.options[this.selectedIndex].value;" tabindex="7"><option value="0" selected>{$APP.LBL_NONE}</select></td></tr>
								<tr><td>&nbsp;</td></tr>
								<tr><td><b>{$UMOD.LBL_MERGE_FIELD_VALUE}</b></td></tr>

								<tr><td><input type="text"  id="mergeFieldValue" name="variable" value="variable" class="detailedViewTextBoxOn" tabindex="8"/></td></tr>
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
				<textarea name="body" rows="10" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" tabindex="5">{$BODY}</textarea>
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
			<input type="submit" value="{$APP.LBL_SAVE_BUTTON_LABEL}" class="small" onclick="this.form.action.value='saveemailtemplate'" tabindex="9"/>&nbsp;&nbsp;
			{if $EMODE eq 'edit'}
				<input type="submit" value="{$APP.LBL_CANCEL_BUTTON_LABEL}" class="small" onclick="cancelForm(this.form)" tabindex="10"/>
			{else}
				<input type="button" value="{$APP.LBL_CANCEL_BUTTON_LABEL}" class="small" onclick="window.history.back()" tabindex="10">
			{/if}
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

