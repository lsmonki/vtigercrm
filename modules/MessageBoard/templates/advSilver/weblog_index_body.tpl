<!-- form method="post" action="{S_WEBLOG_SORT_ACTION}">
<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
  <tr>
	<td valign="top" width="20%">
		<table width="100%" cellspacing="1" cellpadding="1" border="0" align="left"><tr><td>
			<span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_WEBLOGS}" class="nav">{L_WEBLOGS}</a> <br/>{PAGINATION} </span>
		</td></tr></table>
	</td>
	<td valign="top" width="20%" align="right">
	<span class="genmed">{L_PERPAGE} {S_SELECT_PERPAGE} {L_SORT} {S_SELECT_SORT} {S_SELECT_ASC_DESC}</span>
	<input type="submit" class="liteoption" value="{L_GO}" name="submit" />
	</td>
  </tr>
</table>
</form -->

<table bgcolor="#ffffff" width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
  <tr>
	<td valign="top" width="48%">
		<table width="100%" cellspacing="1" cellpadding="1" border="0" align="left"><tr><td>
		<!-- BEGIN stats -->
		<table border="0" cellpadding="3" cellspacing="1" border="0" width="100%">
		  <tr>
			<td width="25%" colspan="3" bgcolor="f0b482" align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>{L_WEBLOG_LAST_UPDATED}</b></td>
		  </tr>
		  <tr>
			<td width="43%" align="center" bgcolor="ffe5c0"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b>{L_WEBLOG_NAME}</b></td>
			<td width="23%" align="center" bgcolor="ffe5c0"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b>{L_WEBLOG_OWNER}</b></td>
			<td width="33%" align="center" bgcolor="ffe5c0"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b>{L_WEBLOG_DATE}</b></td>
		  </tr>
		  <!-- BEGIN lastupdatedrow -->
		  <tr>
			<td width="43%" class="{stats.lastupdatedrow.CLASS}" align="left"><span class="gensmall">{stats.lastupdatedrow.NAME}</span></td>
			<td width="23%" class="{stats.lastupdatedrow.CLASS}" align="left"><span class="gensmall">{stats.lastupdatedrow.OWNER}</span></td>
			<td width="33%" class="{stats.lastupdatedrow.CLASS}" align="left"><span class="gensmall">{stats.lastupdatedrow.DATE}</span></td>
		  </tr>
		  <!-- END lastupdatedrow -->
		<tr>
			<td bgcolor="#e9d3be" colspan="3">
			<table border=0 width="100%" cellpadding=0 cellspacing=0>
			<tr>
				<td align=left><span class="nav"><a href="weblog_users.php?sorder=post_time&type=DESC">Latest Post / Comments</a></span></td>
				<td align=right><span class="nav"><a href="weblog_alltopics.php?sorder=topic_time&type=DESC">More....</a></span></td>
			</tr>
			</table>
			</td>
		</tr>
		</table>
		<br />
		<table border="0" cellpadding="3" cellspacing="1" border="0" class="forumline" width="100%">
		  <tr>
			<td width="25%" colspan="3" bgcolor="f0b482" align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>{L_WEBLOG_MOST_POPULAR}</b></td>
		  </tr>
		  <tr>
			<td width="43%" bgcolor="ffe5c0" align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b>{L_WEBLOG_NAME}</td>
			<td width="36%" bgcolor="ffe5c0" align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b>{L_WEBLOG_OWNER}</td>
			<td width="20%" bgcolor="ffe5c0" align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b>{L_WEBLOG_VISITS}</td>
		  </tr>
		  <!-- BEGIN mostpopularrow -->
		  <tr>
			<td width="43%" class="{stats.mostpopularrow.CLASS}" align="left"><span class="gensmall">{stats.mostpopularrow.NAME}</span></td>
			<td width="23%" class="{stats.mostpopularrow.CLASS}" align="left"><span class="gensmall">{stats.mostpopularrow.OWNER}</span></td>
			<td width="33%" class="{stats.mostpopularrow.CLASS}" align="left"><span class="gensmall">{stats.mostpopularrow.VISITS}</span></td>
		  </tr>
		  <!-- END mostpopularrow -->
		<tr>
			<td bgcolor="#e9d3be" align="right" colspan="3"><span class="nav"><a href="weblog_alltopics.php?sorder=topic_views&type=DESC">More....</a></span></td>
		  </tr>
		</table>
		<br />
		<!-- END stats -->
		</td></tr></table>
	</td>
	<td valign="top" width="1%"></td>
	<!-- td valign="top" width="50%">
		<table width="100%" cellspacing="1" cellpadding="1" border="0" align="center"><tr><td>
  <!-- BEGIN catrow -->
  <!-- BEGIN forumrow -->
		< table width="100%" cellspacing="0" cellpadding="0" border="0" align="center"><tr><td>
		{catrow.forumrow.WEBLOG_FACE}
		</td></tr></table>
  <!-- END forumrow -->
  <!-- END catrow -->
		</td></tr></table -->
	</td-->
	<td valign="top" width="1%"></td>
	<td valign="top" width="48%">
		<table width="100%" cellspacing="1" cellpadding="1" border="0" align="right"><tr><td>
		<!-- BEGIN stats -->

		<table border="0" cellpadding="3" cellspacing="1" border="0" class="forumline" width="100%">
		  <tr>
			<td width="25%" colspan="3" bgcolor="f0b482" align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>{L_WEBLOG_MOST_ENTRIES}</b></td>
		  </tr>
		  <tr>
			<td width="43%" bgcolor="ffe5c0" align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b>{L_WEBLOG_NAME}</b></td>
			<td width="36%" bgcolor="ffe5c0" align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b>{L_WEBLOG_OWNER}</b></td>
			<td width="20%" bgcolor="ffe5c0" align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b>{L_WEBLOG_NUMBER}</b></td>
		  </tr>
		  <!-- BEGIN mostentriesrow -->
		  <tr>
			<td width="43%" class="{stats.mostentriesrow.CLASS}" align="left"><span class="gensmall">{stats.mostentriesrow.NAME}</span></td>
			<td width="36%" class="{stats.mostentriesrow.CLASS}" align="left"><span class="gensmall">{stats.mostentriesrow.OWNER}</span></td>
			<td width="20%" class="{stats.mostentriesrow.CLASS}" align="left"><span class="gensmall">{stats.mostentriesrow.ENTRIES}</span></td>
		  </tr>
		  <!-- END mostentriesrow -->
		<tr>
			<td bgcolor="#e9d3be" align="right" colspan="3"><span class="nav"><a href="weblog_users.php?sorder=forum_topics&type=DESC">More....</a></span></td>
		  </tr>
		</table>
		<br />

		<table border="0" cellpadding="3" cellspacing="1" border="0" class="forumline" width="100%">
		  <tr>
			<td width="25%" colspan="3" bgcolor="f0b482" align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>{L_WEBLOG_POPULAR_AUTHORS}</b></td>
		  </tr>
		  <tr>
			<td width="43%" bgcolor="ffe5c0" align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b>{L_WEBLOG_NAME}</td>
			<td width="36%" bgcolor="ffe5c0" align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b>{L_WEBLOG_OWNER}</td>
			<td width="20%" bgcolor="ffe5c0" align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b>{L_WEBLOG_VISITS}</td>
		  </tr>
		  <!-- BEGIN newestrow -->
		  <tr>
			<td width="43%" class="{stats.newestrow.CLASS}" align="left"><span class="gensmall">{stats.newestrow.NAME}</span></td>
			<td width="36%" class="{stats.newestrow.CLASS}" align="left"><span class="gensmall">{stats.newestrow.OWNER}</span></td>
			<td width="20%" class="{stats.newestrow.CLASS}" align="left"><span class="gensmall">{stats.newestrow.VIEWS}</span></td>
		  </tr>
		  <!-- END newestrow -->
		  <tr>
			<td bgcolor="#e9d3be" align="right" colspan="3"><span class="nav"><a href="weblog_users.php?sorder=forum_views&type=DESC">More....</a></span></td>
		  </tr>
		</table>

		<br />
		<!-- END stats -->
		</td></tr></table>
  </tr>
</table>

<br />
<div align="center"><span class="copyright">Powered by Forum Weblogs Mod 0.3.1 by Hyperion<br />Powered by <a href="http://www.vtiger.com/" target="_blank">vtiger.com</a></span></div>

