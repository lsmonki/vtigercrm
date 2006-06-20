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


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Compose Mail</title>
<link REL="SHORTCUT ICON" HREF="include/images/vtigercrm_icon.ico">	
<style type="text/css">@import url("themes/{$THEME}/style.css");</style>
<script language="javascript" type="text/javascript" src="include/scriptaculous/prototype.js"></script>
</head>
<body marginheight="0" marginwidth="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<form name="EditView" method="POST" ENCTYPE="multipart/form-data" action="index.php">
<input type="hidden" name="form">
<input type="hidden" name="send_mail">
<input type="hidden" name="contact_id" value="{$CONTACT_ID}">
<input type="hidden" name="user_id" value="{$USER_ID}">
<input type="hidden" name="filename" value="{$FILENAME}">
<input type="hidden" name="old_id" value="{$OLD_ID}">
<input type="hidden" name="module" value="{$MODULE}">
<input type="hidden" name="record" value="{$ID}">
<input type="hidden" name="mode" value="{$MODE}">
<input type="hidden" name="action">
<input type="hidden" name="popupaction" value="create">
<input type="hidden" name="hidden_toid">
<table class="small" border="0" cellpadding="0" cellspacing="0" width="100%">
<tbody>
   <tr>
	<td colspan="3">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
		   <tr>
			<td width="143"><img src="{$IMAGE_PATH}composeMail.jpg"></td>
			<td background="{$IMAGE_PATH}mailHdr.jpg" style="background-repeat:repeat-x;" width="100%">&nbsp;</td>
			<td width="86"><img src="{$IMAGE_PATH}mailTitle.jpg"></td>
		   </tr>
		</table>	
	</td>
   </tr> 
	{foreach item=row from=$BLOCKS}
	{foreach item=elements from=$row}
	{if $elements.2.0 eq 'parent_id'}
   <tr>
	<td class="lvtCol" style="padding: 5px;" align="right"><b>{$MOD.LBL_TO}</b></td>
	<td class="dvtCellLabel" style="padding: 5px;">
 		<input name="{$elements.2.0}" type="hidden" value="{$IDLISTS}">
		<input type="hidden" name="saved_toid" value="{$TO_MAIL}">
		<textarea id="parent_name" readonly cols="70">{$TO_MAIL}</textarea>
	</td>
	<td class="dvtCellLabel" style="padding: 5px;" align="left">
		<select name="parent_type">
			{foreach key=labelval item=selectval from=$elements.1.0}
				<option value="{$labelval}" {$selectval}>{$labelval}</option>
			{/foreach}
		</select>
		&nbsp;
		<span class="lvtCol" style="padding: 3px;">
		<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module="+ document.EditView.parent_type.value +"&action=Popup&html=Popup_picker&form=HelpDeskEditView&popuptype=set_return_emails","test","width=640,height=565,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
		</span><span class="lvtCol" style="padding: 3px;"><input type="image" src="{$IMAGE_PATH}clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.parent_id.value=''; this.form.hidden_toid.value='';this.form.parent_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
		</span>
	</td>
   </tr>
   <tr>
	<td class="lvtCol" style="padding: 5px;" align="right">{$MOD.LBL_CC}</td>
	<td class="dvtCellLabel" style="padding: 5px;">
		<input name="ccmail" class="txtBox" type="text" value="{$CC_MAIL}" style="width:99%">&nbsp;
	</td>	
	<td class="dvtCellLabel">&nbsp;</td>
   </tr>
   <tr>
	<td class="lvtCol" style="padding: 5px;" align="right">{$MOD.LBL_BCC}</td>
	<td class="dvtCellLabel" style="padding: 5px;">
		<input name="bccmail" class="txtBox" type="text" value="{$BCC_MAIL}" style="width:99%">&nbsp;
	</td>
	<td class="dvtCellLabel">&nbsp;</td>
   </tr>
	{elseif $elements.2.0 eq 'subject'}
   <tr>
	<td class="lvtCol" style="padding: 5px;" align="right" nowrap><font color="red">*</font>{$elements.1.0}  :</td>
        {if $WEBMAIL eq 'true'}
                <td class="dvtCellLabel" style="padding: 5px;"><input type="text" class="txtBox" name="{$elements.2.0}" value="{$SUBJECT}" id="{$elements.2.0}" style="width:99%"></td>
        {else}
                <td class="dvtCellLabel" style="padding: 5px;"><input type="text" class="txtBox" name="{$elements.2.0}" value="{$elements.3.0}" id="{$elements.2.0}" style="width:99%"></td>
        {/if}
	<td class="dvtCellLabel">&nbsp;</td>
   </tr>
	{elseif $elements.2.0 eq 'filename'}

   <tr>
	<td class="lvtCol" style="padding: 5px;" align="right" nowrap>{$elements.1.0}  :</td>
	<td class="dvtCellLabel" style="padding: 5px;">
		<input name="{$elements.2.0}"  type="file" class="small" value="{$elements.3.1}" size="78"/>
		<input type="hidden" name="id" value=""/>{$elements.3.0}
	</td>
	<td class="dvtCellLabel">&nbsp;</td>
   </tr>
	{elseif $elements.2.0 eq 'description'}
   <tr>
	<td colspan="3" align="center" height="320">
        <input id="description___Config" value="" style="display: none;" type="hidden"><iframe id="description___Frame" src="include/fckeditor/editor/fckeditor.html?InstanceName=description&amp;Toolbar=Default" frameborder="no" height="370" scrolling="no" width="100%"></iframe>
        {if $WEBMAIL eq 'true'}
                <textarea style="display: none;" class="detailedViewTextBox" name="description" cols="90" rows="8">{$DESCRIPTION}</textarea>
        {else}
                <textarea style="display: none;" class="detailedViewTextBox" name="description" cols="90" rows="8">{$elements.3.0}</textarea>        {/if}
	</td>
   </tr>
	{/if}
	{/foreach}
	{/foreach}

   <tr>
	<td colspan="3" class="lvtCol" style="padding: 5px;" align="center">
		 <input title="{$APP.LBL_SELECTEMAILTEMPLATE_BUTTON_TITLE}" accessKey="{$APP.LBL_SELECTEMAILTEMPLATE_BUTTON_KEY}" class="classBtn" onclick="window.open('index.php?module=Users&action=lookupemailtemplates','emailtemplate','top=100,left=200,height=400,width=500,menubar=no,addressbar=no,status=yes')" type="button" name="button" value=" {$APP.LBL_SELECTEMAILTEMPLATE_BUTTON_LABEL}  ">
		<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="classBtn" onclick="return email_validate(this.form,'save');" type="button" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL} " >&nbsp;
		<input name="{$MOD.LBL_SEND}" value=" {$APP.LBL_SEND} " class="classBtn" type="button" onclick="return email_validate(this.form,'send');">&nbsp;
		<input name="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" value=" {$APP.LBL_CANCEL_BUTTON_LABEL} " class="classBtn" type="button" onClick="window.close()">
	</td>
   </tr>
</tbody>
</table>
</form>
</body>
{literal}
<script>
function email_validate(oform,mode)
{
	if(oform.parent_name.value.replace(/^\s+/g, '').replace(/\s+$/g, '').length==0)
	{
		alert('No recipients were specified');
		return false;
	}
	if(oform.subject.value.replace(/^\s+/g, '').replace(/\s+$/g, '').length==0)
	{
		if(email_sub = prompt('You did not specify a subject from this email. If you would like to provide one, please type it now','(no-Subject)'))
		{
			oform.subject.value = email_sub;
		}else
		{
			return false;
		}
	}
	if(mode == 'send')
	{
		server_check()	
	}else
	{
		oform.action.value='Save';
		oform.submit();
	}
}
function server_check()
{
	var oform = window.document.EditView;
        new Ajax.Request(
        	'index.php',
                {queue: {position: 'end', scope: 'command'},
                	method: 'post',
                        postBody:"module=Emails&action=EmailsAjax&file=Save&ajax=true&server_check=true",
			onComplete: function(response) {
			if(response.responseText == 'SUCESS')
			{
				oform.send_mail.value='true';
				oform.action.value='Save';
				oform.submit();
			}else
			{
				alert('Please Configure Your Mail Server');
				return false;
			}
               	    }
                }
        );
}
</script>
{/literal}
</html>
