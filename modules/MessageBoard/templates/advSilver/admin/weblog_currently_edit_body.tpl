
<h1>{L_CURRENTLY_TITLE}</h1>

<p>{L_CURRENTLY_EXPLAIN}</p>

<script language="javascript" type="text/javascript">
<!--
function update_currently(newimage)
{
	document.currently_image.src = "./../images/weblogs/" + newimage;
}
//-->
</script>

<form method="post" action="{S_CURRENTLY_ACTION}"><table class="forumline" cellspacing="1" cellpadding="4" border="0" align="center">
	<tr>
		<th class="thHead" colspan="2">{L_CURRENTLY_CONFIG}</th>
	</tr>
	<tr>
		<td class="row1">{L_CURRENTLY_URL}</td>
		<td class="row1"><select name="currently_url" onchange="update_currently(this.options[selectedIndex].value);">{S_FILENAME_OPTIONS}</select> &nbsp; <img name="currently_image" src="{CURRENTLY_IMG}" border="0" alt="" /> &nbsp;</td>
	</tr>
	<tr>
		<td class="row2">{L_CURRENTLY_CURRENTLY}</td>
		<td class="row2"><input class="post" type="text" name="currently_currently" value="{CURRENTLY_CURRENTLY}" /></td>
	</tr>
	<tr>
		<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input class="mainoption" type="submit" value="{L_SUBMIT}" /></td>
	</tr>
</table></form>
