<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Compose Mail</title>
<link REL="SHORTCUT ICON" HREF="include/images/vtigercrm_icon.ico">	
<style type="text/css">@import url("themes/{$THEME}/style.css");</style>
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
<input type="hidden" name="parenttab" value="{$CATEGORY}">
<input type="hidden" name="return_module" value="{$RETURN_MODULE}">
<input type="hidden" name="return_id" value="{$RETURN_ID}">
<input type="hidden" name="return_action" value="{$RETURN_ACTION}">
<input type="hidden" name="return_viewname" value="{$RETURN_VIEWNAME}">
<input type="hidden" name="popupaction" value="create">
<table class="small" border="0" cellpadding="0" cellspacing="0" width="100%">
<tbody>
	<tr><td colspan="3"><table width="100%" border="0" cellpadding="0" cellspacing="0">

	<tr>
		<td width="143"><img src="{$IMAGE_PATH}composeMail.jpg" width="143"></td>
		<td background="{$IMAGE_PATH}mailHdr.jpg" style="background-repeat:repeat-x;" width="100%">&nbsp;</td>
		<td width="86"><img src="{$IMAGE_PATH}mailTitle.jpg"></td>
	</tr>
																																										</table>	
	</td></tr> 
	<tr>
	<td class="lvtCol" style="padding: 5px;" align="right" width="15%"><b>To : </b></td>
	<td class="dvtCellLabel" style="padding: 5px;">
 	<input name="{$BLOCKS.0.1.2.0}" type="hidden" value="{$BLOCKS.0.1.3.1}">
	<textarea readonly name="parent_name" class=txtBox" cols="50" rows="2">{$BLOCKS.0.1.3.0}</textarea>&nbsp;
	<select name="parent_type">
	{foreach key=labelval item=selectval from=$BLOCKS.0.1.1.0}
	<option value="{$labelval}" {$selectval}>{$labelval}</option>
    {/foreach}
    </select>
		&nbsp;
		<span class="lvtCol" style="padding: 3px;">
		<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module="+ document.EditView.parent_type.value +"&action=Popup&html=Popup_picker&form=HelpDeskEditView","test","width=600,height=400,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
		</span><span class="lvtCol" style="padding: 3px;"><input type="image" src="{$IMAGE_PATH}clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.parent_id.value=''; this.form.parent_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
	</span>
	</td>
	<td class="dvtCellLabel" width="25%">&nbsp;</td>
	</tr>
	<tr>
	<td class="lvtCol" style="padding: 5px;" align="right">CC:</td>
	<td class="dvtCellLabel" style="padding: 5px;">
	
	<input name="ccmail" class="txtBox" type="text">&nbsp;
	</td>	
	<td class="dvtCellLabel" width="25%">&nbsp;</td>
	</tr>
	<tr>
	<td class="lvtCol" style="padding: 5px;" align="right">BCC:</td>
	<td class="dvtCellLabel" style="padding: 5px;">
	<input name="bccmail" class="txtBox" type="text">&nbsp;
	
	</td>
	<td class="dvtCellLabel" width="25%">&nbsp;</td>
	</tr>
	<tr>
	<td class="lvtCol" style="padding: 5px;" align="right" nowrap><font color="red">*</font>{$BLOCKS.1.0.1.0}  :</td>
	<td colspan="2" class="dvtCellLabel" style="padding: 5px;"><input type="text" class="txtBox" name="{$BLOCKS.1.0.2.0}" value="{$BLOCKS.1.0.3.1}"></td>
	</tr>
	<tr>
	
	<td class="lvtCol" style="padding: 5px;" align="right" nowrap>{$BLOCKS.1.1.1.0}  :</td>
	<td class="dvtCellLabel" style="padding: 5px;">
	<input name="{$BLOCKS.1.1.2.0}"  type="file" class="small" value="{$BLOCKS.1.1.3.1}"/>
	<input type="hidden" name="id" value=""/>{$BLOCKS.1.1.3.0}</td>
	<td class="dvtCellLabel">&nbsp;</td>
	</tr>
	<tr>
	<td colspan="3" align="center" height="320">
	<input id="description___Config" value="" style="display: none;" type="hidden"><iframe id="description___Frame" src="include/fckeditor/editor/fckeditor.html?InstanceName=description&amp;Toolbar=Default" frameborder="no" height="400" scrolling="no" width="100%"></iframe><textarea style="display: none;" class="detailedViewTextBox" name="description" cols="90" rows="8">{$fldvalue}</textarea>
	</td>
	</tr>
	<tr>

	<td colspan="3" class="lvtCol" style="padding: 5px;" align="center">
	<input title="Save [Alt+S]" accessKey="S" class="classBtn" onclick="this.form.action.value='Save';" type="submit" name="button" value="Save" >&nbsp;
	<input name="send" value=" &nbsp;Send&nbsp; " class="classBtn" type="submit" onclick="this.form.action.value='Save';this.form.send_mail.value='true';">&nbsp;
	<input name="cancel" value=" Cancel " class="classBtn" type="button" onClick="window.close()">
	</td>
	</tr>
</tbody></table>
</form>
</body>
</html>
