<div id="orgLay">
<table width="100%">
<tbody>
<tr>
	<td class="genHeaderSmall" align="left">{$NOTIFY_DETAILS.name}</td>
	<td align="right"><a href="javascript:hide('editdiv');"><img src="{$IMAGE_PATH}close.gif" align="middle" border="0"></a></td>
</tr>
<tr><td colspan="2"><hr></td></tr>
<tr>
<td align="right" width="40%"><b>{$MOD.LBL_STATUS} :</b></td>

<td align="left" width="60%">
{if $NOTIFY_DETAILS.id neq 7}
	<select class="small" id="notify_status">
{else}	
	<select class="small" disabled id="notify_status">
{/if}
{if $NOTIFY_DETAILS.active eq 1}
<option value="1" "selected">{$MOD.LBL_ACTIVE}</option>
<option value="0">{$MOD.LBL_INACTIVE}</option>
{else}
<option value="1">{$MOD.LBL_ACTIVE}</option>
<option value="0" "selected">{$MOD.LBL_INACTIVE}</option>
{/if}
</select>
</td>
</tr>
<tr><td style="border-bottom: 1px dashed rgb(204, 204, 204);" colspan="2">&nbsp;</td></tr>
<tr><td colspan="2" class="genHeaderSmall">{$MOD.LBL_EMAIL_CONTENTS}</td></tr>
<tr>
<td align="right"><b>{$MOD.LBL_SUBJECT} : </b></td>
<td align="left"><input class="txtBox" id="notifysubject" name="notifysubject" value="{$NOTIFY_DETAILS.subject}" size="40" type="text"></td>

</tr>
<tr>
<td align="right" valign="top"><b>{$MOD.LBL_MESSAGE} : </b></td>
<td align="left"><textarea id="notifybody" name="notifybody" class="txtBox" rows="5" cols="40">{$NOTIFY_DETAILS.body}</textarea></td>
</tr>
<tr><td colspan="2" style="border-bottom: 1px dashed rgb(204, 204, 204);">&nbsp;</td></tr>
<tr>
<td colspan="2" align="center">
<input name="save" value=" &nbsp;Save&nbsp; " class="classBtn" type="button" onClick="fetchSaveNotify('{$NOTIFY_DETAILS.id}','{$NOTIFY_DETAILS.subject}','{$NOTIFY_DETAILS.body}')">
</td>
</tr>
<tr><td colspan="2" style="border-top: 1px dashed rgb(204, 204, 204);">&nbsp;</td></tr>
</tbody>
</table>
</div>
