
<h1>{L_STYLE_TITLE}</h1>

<P>{L_STYLE_TEXT}</p>

<form method="post" action="{S_STYLE_ACTION}"><table cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">
	<tr>
		<th class="thTop">{L_STYLE_NAME}</th>
		<th colspan="2" class="thCornerR">{L_ACTION}</th>
	</tr>
	<!-- BEGIN style -->
	<tr>
		<td class="{style.ROW_CLASS}" align="center">{style.STYLE_IMAGE}<br />{style.STYLE}</td>
		<td class="{style.ROW_CLASS}"><a href="{style.U_STYLE_EDIT}">{L_EDIT}</a></td>
		<td class="{style.ROW_CLASS}"><a href="{style.U_STYLE_DELETE}">{L_DELETE}</a></td>
	</tr>
	<!-- END style -->
	<tr>
		<td class="catBottom" colspan="7" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="add" value="{L_STYLE_ADD}" class="mainoption" /></td>
	</tr>
</table></form>
