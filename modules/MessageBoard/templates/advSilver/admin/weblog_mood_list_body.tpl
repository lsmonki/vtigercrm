
<h1>{L_MOOD_TITLE}</h1>

<P>{L_MOOD_TEXT}</p>

<form method="post" action="{S_MOOD_ACTION}"><table cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">
	<tr>
		<th class="thTop">{L_IMAGE}</th>
		<th class="thTop">{L_MOOD}</th>
		<th colspan="2" class="thCornerR">{L_ACTION}</th>
	</tr>
	<!-- BEGIN mood -->
	<tr>
		<td class="{mood.ROW_CLASS}"><img src="{mood.MOOD_IMG}" alt="{mood.MOOD}" /></td>
		<td class="{mood.ROW_CLASS}">{mood.MOOD}</td>
		<td class="{mood.ROW_CLASS}"><a href="{mood.U_MOOD_EDIT}">{L_EDIT}</a></td>
		<td class="{mood.ROW_CLASS}"><a href="{mood.U_MOOD_DELETE}">{L_DELETE}</a></td>
	</tr>
	<!-- END mood -->
	<tr>
		<td class="catBottom" colspan="5" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="add" value="{L_MOOD_ADD}" class="mainoption" /></td>
	</tr>
</table></form>
