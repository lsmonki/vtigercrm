<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
  <tr>
        <td colspan=2><hr /></font></td>
  </tr>
  <tr>
	<td valign="top" width="80%">
		<table width="97%" cellspacing="1" cellpadding="1" border="0" align="center"><tr>
		<td><span style=" font-size: 14pt; color : 999999; line-height:100%"><b>{FORUM_NAME}</b><br /></span>
		<!--span class="gensmall" style=" font-size: 10pt ; line-height:150%" >{WEBLOG_DESC}</span><br>
		<br>
		<table width="97%" cellpadding="1" cellspacing="0" border="0">
				  <tr>
					<td class="row1" nowrap align="left"><span class="gensmall" style="line-height:150%"><b>{L_WEBLOG_OWNER}:</b></span></td>
					<td class="row1" nowrap align="left"><span class="gensmall" style="line-height:150%">[ <a href="{U_USERPROFILE}" class="nav">{USERNAME}</a> ]</span></td>
                    <td class="row1" width="7%"></td>
					<td class="row1" nowrap align="left"><span class="gensmall" style="line-height:150%"><b>{L_WEBLOG}:</b></span></td>
					<td class="row1" nowrap align="left"><span class="gensmall" style="line-height:150%">[ <a href="{U_PAST}" class="nav">{L_VIEW_ENTRIES}</a> ]</span></td>
                    <td class="row1" width="7%"> </td>
				    <td class="row1" width="7%"> </td>
				    <td class="row1" nowrap align="left"><span class="gensmall" style="line-height:150%"></span></td>
					<td class="row1" nowrap align="left"><span class="gensmall" style="line-height:150%"><a href="{U_NEW_ENTRY}" class="nav">{NEW_ENTRY}</a><br/></span></td>
				  </tr>
		</table-->
		{L_COMMENT_START}
		<br><span style=" font-size: 12pt; color : 006699; line-height:50%"><b>{L_WEBLOG_CATEGORY}</b></span><br><br>
		{L_COMMENT_UPD}
		<table width="50%" cellpading="1">
		<tr>
			<td width="43%" align="center" bgcolor="ffe5c0"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b>{L_WEBLOG_CATEGORY_NAME}</b></td>
			<td width="23%" align="center" bgcolor="ffe5c0"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b>{L_WEBLOG_CATEGORY_EDIT}</b></td>
			<td width="33%" align="center" bgcolor="ffe5c0"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b>{L_WEBLOG_CATEGORY_REMOVE}</b></td>
		</tr>
		<!-- BEGIN postrow -->
		<tr>
			<td width="43%" class="{postrow.CLASS}" align="left"><span class="gensmall">{postrow.NAME}</span></td>
			<td width="23%" class="{postrow.CLASS}" align="center"><span class="gensmall">{postrow.EDIT}</span></td>
			<td width="33%" class="{postrow.CLASS}" align="center"><span class="gensmall">{postrow.REMOVE}</span></td>
		</tr>
		<!-- END postrow -->
		</table>
		{L_COMMENT_END}
		<form action="{S_CAT_ACTION}" method="post" target="_top" name="loginForm">
		<br><span style=" font-size: 12pt; color : 006699; line-height:50%"><b>{L_WEBLOG_CATEGORY_TITLE}</b></span><br /><br />{L_DELETE_CONTENTS}{L_MOVE_CONTENTS}{L_WEBLOG_CATEGORY_REMOVE_TITLE}

		<table width="50%" cellpading="1">
		{L_REMOVE_START}
		<tr>
			<td width="20%"><span class="gen">{L_WEBLOG_CATEGORY_NAME}:</span></td>
		</tr>
		<tr>
                        <td>
                          <input type="text" name="cname" value="{L_CAT_NAME}" size="25" maxlength="40" />
                        </td>
		</tr>
		<tr>
			<td width="20%"><span class="gen">{L_WEBLOG_CATEGORY_DESC}:</span></td>
		</tr>
		<tr>
                        <td><textarea input type="text" name="cdesc" cols="49" rows="3" />{L_CAT_DESC}</textarea></td>

		</tr>
		{L_REMOVE_END}
		<tr>
                        <td>{S_HIDDEN_FIELDS}<input type="submit" name="login" class="mainoption" value="{L_WEBLOG_CATEGORY_BUTTON}" />&nbsp;&nbsp;&nbsp;{CAT_HIDDEN}</td>
                  </tr>
		</table>
		</form>

		<hr></td></tr></table>
	</td>
  </tr>
</table>

</td></tr></table>
<div align="center"><span class="copyright"><br />
<!--
	We request you retain the full copyright notice below including the link to www.phpbb.com.
	This not only gives respect to the large amount of time given freely by the developers
	but also helps build interest, traffic and use of phpBB 2.0. If you cannot (for good
	reason) retain the full copyright we request you at least leave in place the
	Powered by phpBB {PHPBB_VERSION} line, with phpBB linked to www.phpbb.com. If you refuse
	to include even this then support on our forums may be affected.

	The phpBB Group : 2002
// -->
		</td>
	</tr>
</table>

</body>
</html>
