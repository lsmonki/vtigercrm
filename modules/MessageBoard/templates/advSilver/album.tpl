<script language="Javascript" type="text/javascript">
<!--
function RestoreDefault(dd)
{
	for (var i = 0; i < dd.length; i++) if (dd.options[i].defaultSelected) dd.options[i].selected = true;
}

function CreateLink(dd)
{
	var h = location.pathname + '?' + dd.name + '=' + dd.options[dd.selectedIndex].value;
	if (document.ualbum.sid) h += '&sid=' + document.ualbum.sid.value;
	return h;
}
//-->
</script>

<table border="0" cellpadding="8" cellspacing="0">
	<tr>
		<td>
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td nowrap="nowrap" class="nav"><a href="{U_INDEX}">{L_INDEX}</a> -&gt; <a href="{U_RAND_DOWNLOAD}">{L_PICS}</a> -&gt;&nbsp;</td>
<form method="get" name="ualbum" action="{U_DOWNLOAD}">
	<td nowrap="nowrap" width="2"></td>
	<td class="genmed"><select size="1" name="u" onChange="if (this.options[selectedIndex].value != 0) location.href=CreateLink(this);">{UOPTIONS}</select></td>
	<td>
		<script language="Javascript1.2" type="text/javascript">
		<!--
		RestoreDefault(document.ualbum.u);
		document.write('<noinput');
		//-->
		</script><input type="image" src="{MINI_SEARCH_IMG}" border="0" hspace="2" />
	</td>{HIDDEN_SID}
</form>
					<td class="genmed" nowrap="nowrap">&nbsp;{L_OR}&nbsp;</td>
<form method="get" name="malbum" action="{U_DOWNLOAD}">
	<td nowrap="nowrap" width="2"></td>
	<td class="genmed"><select size="1" name="m" onChange="if (this.options[selectedIndex].value != 0) location.href=CreateLink(this);">{MOPTIONS}</select></td>
	<td>
		<script language="Javascript1.2" type="text/javascript">
		<!--
		RestoreDefault(document.malbum.m);
		document.write('<noinput');
		//-->
		</script><input type="image" src="{MINI_SEARCH_IMG}" border="0" hspace="2" />
	</td>{HIDDEN_SID}
</form>
					<td class="nav" nowrap="nowrap">&nbsp; &nbsp; &nbsp;<a href="{U_PREV}">{L_PREV}</a></td>
					<td class="nav" nowrap="nowrap">&nbsp; &nbsp; &nbsp;<a href="{U_NEXT}">{L_NEXT}</a></td>
<!-- BEGIN switch_poster_profile -->
					<td class="nav" nowrap="nowrap">&nbsp; &nbsp; &nbsp;</td>
					<td class="nav" align="center"><a href="{U_POSTER_PROFILE}">{L_VIEW_PROFILE}</a></td>
<!-- END switch_poster_profile -->
    				</tr>
			</table>
		</td>
	</tr>
</table>
<span class="gensmall">&nbsp;<br />
<!-- BEGIN thumb -->
<a title="{thumb.POSTER_NAME} » {thumb.TOPIC_TITLE} » {thumb.COMMENT} ({thumb.FILESIZE} {L_KILOBYTE})" href="{thumb.U_DOWNLOAD}"><span style="imageborder"><img class="imageborder" src="{thumb.U_THUMB}" border="1" alt="{thumb.POSTER_NAME} » {thumb.TOPIC_TITLE} » {thumb.COMMENT} ({thumb.FILESIZE} {L_KILOBYTE})" border="1" hspace="8" vspace="8" align="middle" /></span></a>
<!-- END thumb -->
</span>