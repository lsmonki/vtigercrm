<h1>{L_SUBTEMPLATE_TITLE}</h1>

<p>{L_SUBTEMPLATE_EXPLAIN}</p>

<form action="{S_CONFIG_ACTION}" method="post">
<table width="100%" cellpadding="2" cellspacing="0" align="center">
<tr>
	<td>
		<span class="nav">
			<a href="{U_CHOOSE_MAIN_STYLE}" class="nav">{L_MAIN_STYLE}</a>{NAV_SEPARATOR}{MAIN_STYLE}
		</span>
	</td>
</tr>
</table>

<table width="99%" cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">
<tr>
	<th>{L_NAME}</th>
	<th>{L_DIR}</th>
	<th>{L_HEAD_STYLESHEET}</th>
	<th>{L_IMAGEFILE}</th>
	<th>{L_EDIT}</th>
	<th>{L_DELETE}</th>
</tr>
<!-- BEGIN subtpl -->
<tr>
	<td class="{subtpl.ROW_CLASS}"><span class="genmed">{subtpl.NAME}</span></td>
	<td class="{subtpl.ROW_CLASS}"><span class="genmed">{subtpl.DIR}</span></td>
	<td class="{subtpl.ROW_CLASS}"><span class="genmed">{subtpl.HEAD_STYLESHEET}</span></td>
	<td class="{subtpl.ROW_CLASS}"><span class="genmed">{subtpl.IMAGEFILE}</span></td>
	<td class="{subtpl.ROW_CLASS}"><a href="{subtpl.U_EDIT}" class="genmed">{L_EDIT}</a></td>
	<td class="{subtpl.ROW_CLASS}"><a href="{subtpl.U_DELETE}" class="genmed">{L_DELETE}</a></td>
</tr>
<!-- END subtpl -->
<tr>
	<td class="catBottom" colspan="6" align="center">
		<input type="submit" name="create" value="{L_CREATE}" class="mainoption">
		{S_HIDDEN_FIELDS}
	</td>
</tr>
</table>
</form>

<table width="99%" cellpadding="2" cellspacing="0">
<tr>
	<td><span class="nav">{L_USAGE} {MAIN_STYLE}</a></span></td>
</tr>
</table>

<table cellspacing="1" width="99%" cellpadding="4" border="0" align="center" class="forumline">
<tr>
	<th colspan="{MAX_SPAN}">{L_FORUM}</th>
	<th colspan="{TPL_SPAN}">{L_TEMPLATE}</th>
</tr>
<!-- BEGIN boardrow -->
<tr>
	<!-- BEGIN inc -->
	<td class="row1"><span class="mainmenu">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
	<!-- END inc -->
	<td class="{boardrow.ROW_CLASS}" colspan="{boardrow.SPAN}" nowrap="nowrap"><span class="{boardrow.SPAN_CLASS}">{boardrow.NAME}</span></td>
	<!-- BEGIN tpl -->
	<td class="{boardrow.ROW_CLASS}"><span class="gen">{boardrow.tpl.SUBTEMPLATE}</span></td>
	<!-- END tpl -->
</tr>
<!-- END boardrow -->
<tr>
	<td class="catBottom" align="center" colspan="{MAX_SPAN_FULL}">&nbsp;</td>
</tr>
</table>