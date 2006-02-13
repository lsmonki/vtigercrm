
<h1>{L_STYLE_TITLE}</h1>

<p>{L_STYLE_EXPLAIN}</p>

<script language="javascript" type="text/javascript">
<!--
function update_preview(newimage)
{
	document.preview_image.src = "./../weblogs/styles/" + newimage + "/weblog_preview.gif";
}
//-->
</script>

<form method="post" action="{S_STYLE_ACTION}"><table class="forumline" cellspacing="1" cellpadding="4" border="0" align="center">
	<tr>
		<th class="thHead" colspan="2">{L_STYLE_CONFIG}</th>
	</tr>
	<tr>
		<td class="row1">{L_STYLE_NAME}</td>
		<td class="row1"><input class="post" type="text" name="style_name" value="{STYLE_NAME}" /></td>
	</tr>
	<tr>
		<td class="row2">{L_STYLE_DIR}<br /></td>
		<td class="row2">{STYLE_DIR}</td>
	</tr>
	<tr>
		<td class="row2">{L_STYLE_PREVIEW}</td>
		<td class="row2"><img name="preview_image" src="{PREVIEW_IMG}" border="0"></td>
	</tr>
	<tr>
		<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input class="mainoption" type="submit" value="{L_SUBMIT}" /></td>
	</tr>
</table></form>
