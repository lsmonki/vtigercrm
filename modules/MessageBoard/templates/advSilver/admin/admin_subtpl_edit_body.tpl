<h1>{L_SUBTEMPLATE_TITLE}</h1>

<p>{L_SUBTEMPLATE_EXPLAIN}</p>

<form action="{S_CONFIG_ACTION}" method="post">
<table width="100%" cellpadding="2" cellspacing="0">
<tr>
	<td>
		<span class="nav">
			<a href="{U_CHOOSE_MAIN_STYLE}" class="nav">{L_MAIN_STYLE}</a>{NAV_SEPARATOR}<a href="{U_MAIN_STYLE}" class="nav">{MAIN_STYLE}</a>
		</span>
	</td>
</tr>
</table>

<table width="99%" cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">
<tr>
	<th colspan="2">{L_SUBTEMPLATE_TITLE}</th>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_NAME}</span></td>
	<td class="row2"><input type="text" name="name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_DIR}</span></td>
	<td class="row2">{S_DIR}</td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_HEAD_STYLESHEET}</span></td>
	<td class="row2"><input type="text" name="head_stylesheet" value="{HEAD_STYLESHEET}"></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_IMAGEFILE}</span></td>
	<td class="row2"><input type="text" name="imagefile" value="{IMAGEFILE}"></td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center">
		<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption">
		<input type="submit" name="cancel" value="{L_CANCEL}" class="mainoption">
		{S_HIDDEN_FIELDS}
	</td>
</tr>
</table>
<br />

<table cellspacing="1" width="99%" cellpadding="4" border="0" align="center" class="forumline">
<tr>
	<th colspan="{MAX_SPAN}">{L_FORUM}</th>
	<th>{L_TEMPLATE}</th>
</tr>
<!-- BEGIN boardrow -->
<tr>
	<!-- BEGIN inc -->
	<td class="row1"><span class="mainmenu">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
	<!-- END inc -->
	<td class="{boardrow.ROW_CLASS}" colspan="{boardrow.SPAN}" nowrap="nowrap"><span class="{boardrow.SPAN_CLASS}">{boardrow.NAME}</span></td>
	<td class="{boardrow.ROW_CLASS}" align="center"><input type="checkbox" name="board_ids[]" value="{boardrow.BOARD_ID}" {boardrow.S_CHECKED}></td>
</tr>
<!-- END boardrow -->
<tr>
	<td class="catBottom" align="center" colspan="{MAX_SPAN_FULL}">
		<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption">
		<input type="submit" name="cancel" value="{L_CANCEL}" class="mainoption">
	</td>
</tr>
</table>

</form>