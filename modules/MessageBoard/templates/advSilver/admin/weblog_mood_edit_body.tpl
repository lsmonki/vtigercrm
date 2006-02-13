
<h1>{L_MOOD_TITLE}</h1>

<p>{L_MOOD_EXPLAIN}</p>

<script language="javascript" type="text/javascript">
<!--
function update_mood(newimage)
{
	document.mood_image.src = "./../images/weblogs/" + newimage;
}
//-->
</script>

<form method="post" action="{S_MOOD_ACTION}"><table class="forumline" cellspacing="1" cellpadding="4" border="0" align="center">
	<tr>
		<th class="thHead" colspan="2">{L_MOOD_CONFIG}</th>
	</tr>
	<tr>
		<td class="row1">{L_MOOD_URL}</td>
		<td class="row1"><select name="mood_url" onchange="update_mood(this.options[selectedIndex].value);">{S_FILENAME_OPTIONS}</select> &nbsp; <img name="mood_image" src="{MOOD_IMG}" border="0" alt="" /> &nbsp;</td>
	</tr>
	<tr>
		<td class="row2">{L_MOOD_MOOD}</td>
		<td class="row2"><input class="post" type="text" name="mood_mood" value="{MOOD_MOOD}" /></td>
	</tr>
	<tr>
		<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input class="mainoption" type="submit" value="{L_SUBMIT}" /></td>
	</tr>
</table></form>
