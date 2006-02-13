
<h1>{L_CURRENTLY_TITLE}</h1>

<P>{L_CURRENTLY_TEXT}</p>

<form method="post" action="{S_CURRENTLY_ACTION}"><table cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">
	<tr>
		<th class="thTop">{L_IMAGE}</th>
		<th class="thTop">{L_CURRENTLY}</th>
		<th colspan="2" class="thCornerR">{L_ACTION}</th>
	</tr>
	<!-- BEGIN currently -->
	<tr>
		<td class="{currently.ROW_CLASS}"><img src="{currently.CURRENTLY_IMG}" alt="{currently.CURRENTLY}" /></td>
		<td class="{currently.ROW_CLASS}">{currently.CURRENTLY}</td>
		<td class="{currently.ROW_CLASS}"><a href="{currently.U_CURRENTLY_EDIT}">{L_EDIT}</a></td>
		<td class="{currently.ROW_CLASS}"><a href="{currently.U_CURRENTLY_DELETE}">{L_DELETE}</a></td>
	</tr>
	<!-- END currently -->
	<tr>
		<td class="catBottom" colspan="5" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="add" value="{L_CURRENTLY_ADD}" class="mainoption" /></td>
	</tr>
</table></form>
