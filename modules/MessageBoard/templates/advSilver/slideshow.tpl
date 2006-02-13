<script language="Javascript" type="text/javascript">
<!--
var BoxChecked = -999;
var TimeoutID = false;
var PageLoaded = false;

function RestoreDefault(dd)
{
	for (var i = 0; i < dd.length; i++) if (dd.options[i].defaultSelected) dd.options[i].selected = true;
}

function CreateLink(dd)
{
	var h = location.pathname + '?' + dd.name + '=' + dd.options[dd.selectedIndex].value;
	if (dd.name == 'id')
	{
		if (document.ss.c) h = h.substring(0, h.length - 1) + 'x';
		if (document.ss.order) h += '&order=' + document.ss.order.value;
	}
	if (document.ualbum.sid) h += '&sid=' + document.ualbum.sid.value;
	return h;
}

function GetSecs(t)
{ // assumes secs is < 60 and in last 3 characters of text
	var secs = t.substring(t.length - 3);
	if (secs.charAt(2) < '0' || secs.charAt(2) > '9') secs = secs.substring(0, 2);
	if (secs.charAt(0) < '0' || secs.charAt(0) > '9') secs = secs.substring(1);
	if (secs.charAt(0) < '0' || secs.charAt(0) > '9') secs = secs.substring(1);
	return eval(secs);
}

function UpdateLink(a, idd)
{ // update link if slideshow was stopped or started, selectedIndex was changed, etc.
	var x = idd + GetSecs(id.options[id.selectedIndex].value) + ((BoxChecked) ? 'x' : '');
	for (var i = StopOption + 1; i < id.length; i++) if (id.options[i].defaultSelected) if (id.options[i].value == (x + 'X')) x = idd.substring(0, idd.length - 1) ;
	return a.href = location.pathname + '?id=' + x + a.href.substring((a.href + '&').indexOf('&'));
}

function UpdateSecs(opt, secs)
{ // assumes secs is < 60
	var i =  opt.text.indexOf(':') + 1;
	opt.text =  opt.text.substring(0, i) + ( (secs > 9) ? '' : '0' ) + secs + opt.text.substring(i + 2);
}

function ResetSecs()
{ // reset the option text and return the default selection
	for (var i = StopOption + 1; i < id.length; i++)
	{
		var opt = id.options[i];
		UpdateSecs(opt, GetSecs(opt.value));
	}
}

function CountDown(i)
{
	if (TimeoutID) clearTimeout(TimeoutID);
	if (Math.abs(BoxChecked) != 1) return;
	var opt = id.options[id.selectedIndex];
	var secs = GetSecs(opt.text) - i;
	UpdateSecs(opt, secs);
	if (secs == 0) location.href = CreateLink(id); else TimeoutID = setTimeout('CountDown(1)', 1000);
}

function SetCheckBox()
{
	if (BoxChecked == -1) { BoxChecked = true; if (PageLoaded) CountDown(1); }
	if (BoxChecked != 2 && document.ss.c.checked != BoxChecked) document.ss.c.checked = BoxChecked;
	setTimeout('SetCheckBox()', 10);
}
//-->
</script>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td><table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td nowrap="nowrap" width="20"></td>
				<td><table border="0" cellpadding="1" cellspacing="0">
					<tr><td class="slideshow" height="14" valign="bottom"><b>{COMMENT}</b></td></tr>
					<tr><td class="slideshow">{L_TOPIC}: <a class="slideshow" title="{L_VIEW_POST}" href="{U_POST}">{TOPIC_TITLE}</a></td></tr>
					<tr><td class="slideshow" nowrap="nowrap">{L_DOWNLOAD_COUNT} ({FILESIZE} {L_KILOBYTE})</td></tr>
					<tr><td><table border="0" cellpadding="0" cellspacing="0"><tr>
<form method="get" name="ualbum" action="{U_DOWNLOAD}">
	<td class="slideshow"><select size="1" name="u" onChange="location.href=CreateLink(this);">{UOPTIONS}</select></td>
	<td class="slideshow" nowrap="nowrap">
		<script language="Javascript1.2" type="text/javascript">
		<!-- // let IE javascript enabled folks right click on the image to open things up in a new window
		RestoreDefault(document.ualbum.u);
		document.write('<a href="/" onMouseOver="this.href=CreateLink(document.ualbum.u);" onClick="location.href=CreateLink(document.ualbum.u); return false;"><img src="{MINI_SEARCH_IMG}" border="0" vspace="4" hspace="2" /></a><noinput');
		//-->
		</script><input type="image" src="{MINI_SEARCH_IMG}" border="0" vspace="4" hspace="2" />
		&nbsp; &nbsp;
	</td>{HIDDEN_SID}
</form>
<form method="get" name="malbum" action="{U_DOWNLOAD}">
       	<td class="slideshow"><select size="1" name="m" onChange="location.href=CreateLink(this);">{MOPTIONS}</select></td>
	<td class="slideshow" nowrap="nowrap">
		<script language="Javascript1.2" type="text/javascript">
		<!-- // let IE javascript enabled folks right click on the image to open things up in a new window
		RestoreDefault(document.malbum.m);
		document.write('<a href="/" onMouseOver="this.href=CreateLink(document.malbum.m);" onClick="location.href=CreateLink(document.malbum.m); return false;"><img src="{MINI_SEARCH_IMG}" border="0" vspace="4" hspace="2" /></a><noinput');
		//-->
		</script><input type="image" src="templates/advSilver/images/icon_mini_search.gif" border="0" vspace="4" hspace="2" />
		&nbsp;
	</td>{HIDDEN_SID}
</form>
					</tr></table></td></tr>
				</table></td>
			</tr>
		</table></td>
		<td align="right"><table border="0" cellpadding="0" cellspacing="7">
			<tr>
				<td><a title="{PREV_POSTER_NAME} » {PREV_TOPIC_TITLE} » {PREV_COMMENT} ({PREV_FILESIZE} {L_KILOBYTE})" href="{U_PREV_DOWNLOAD}" onMouseOver="if (BoxChecked != -999) UpdateLink(this, '{PREV_ID_DIRECTION}')" onClick="if (BoxChecked != -999) { location.href=UpdateLink(this, '{PREV_ID_DIRECTION}'); BoxChecked = 2; return false; }"><span style="imageborder"><img src="{U_PREV_THUMB}" border="1" class="imageborder" alt="{PREV_POSTER_NAME} » {PREV_TOPIC_TITLE} » {PREV_COMMENT} ({PREV_FILESIZE} {L_KILOBYTE})" /></span></a></td>
				<td><table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td class="slideshow" nowrap="nowrap" align="center">
							<a class="slideshow" href="{U_INDEX}">{L_FORUM_INDEX}<br />
							<a class="slideshow" href="{U_ACTIVE_TOPICS}">{L_ACTIVE_TOPICS}</a>
						</td>
					</tr>
					<tr>
<form method="get" name="ss" action="{U_DOWNLOAD}">
	<td align="center"><table border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td valign="bottom" height="31"><table border="0" cellpadding="0" cellspacing="0"><tr><td height="30" class="slideshow" nowrap="nowrap">
			<script language="Javascript1.2" type="text/javascript">
			<!--
			var x = '{SLIDESHOW_VALUE}';
			if (x != 'meta') document.write('&nbsp;');
			//-->
			</script><select size="1" name="id" onFocus="if (BoxChecked != -999) { ResetSecs(); BoxChecked = false; }" onChange="if (BoxChecked == -999) location.href=CreateLink(this); else if (this.selectedIndex == StopOption) this.selectedIndex = i; else BoxChecked = -1;">
				{SLIDESHOW_STOP_OPTION}

				<option value="{NEXT_ID_DIRECTION}3X">0:03</option>
				<option value="{NEXT_ID_DIRECTION}4X" {NO_SLIDESHOW_SELECTED}>0:04</option>
				<option value="{NEXT_ID_DIRECTION}6X">0:06</option>
				<option value="{NEXT_ID_DIRECTION}8X">0:08</option>

				<option value="{PREV_ID_DIRECTION}4X">«:04</option>
				<option value="{PREV_ID_DIRECTION}6X">«:06</option>
				<option value="{PREV_ID_DIRECTION}8X">«:08</option>

				<option value="{RAND_ID_DIRECTION}4X">?:04</option>
				<option value="{RAND_ID_DIRECTION}6X">?:06</option>
				<option value="{RAND_ID_DIRECTION}8X">?:08</option>
			</select></td></tr></table></td>
			<td class="slideshow">
			<script language="Javascript1.2" type="text/javascript">
			<!--
			if (x != 'meta')
			{
				document.write ('<input type="checkbox" name="c" onClick="BoxChecked = -document.ss.c.checked;">');
				var id = document.ss.id;
				var i = id.selectedIndex;
				var StopOption = (x.indexOf('x') > -1) - 1;
				var BoxChecked = (i == StopOption); // turn off slideshow if they hit their back button (i != StopOption)
				if (id.options[i].defaultSelected && x != '')
				{
					x = x.substring(0, (x + 'x').indexOf('x')) + 'X';
					i = StopOption; var s = i + 1; var secs = GetSecs(x);
					for (var j = s; j < id.length; j++)
					{
						var opt = id.options[j];
						if (opt.value.indexOf(x) > -1) i = j;
						if (opt.value.indexOf(x.charAt(0)) > -1) var hold = opt;
						if (GetSecs(opt.value) == secs) s = j;
					}
					if (i == StopOption) // not found so make it an option
					{
						i = s; opt = id.options[i];
						opt.value = hold.value.substring(0, hold.value.indexOf(x.charAt(0))) + x;
						opt.text = hold.text;
					}
					id.selectedIndex = i;
				}
				ResetSecs();
				SetCheckBox();
				document.write ('<noinput');
			}
			//-->
			</script><input type="submit" value="{L_GO_X}" /></td>
		</tr>
	</table></td>{HIDDEN_ORDER}{HIDDEN_SID}
</form>
					</tr>
					<tr>
						<td class="slideshow" nowrap="nowrap" align="center">
							{L_CURR_OF_TOTAL}<br />
							<a class="slideshow" href="{U_RAND_DOWNLOAD}" onMouseOver="if (BoxChecked != -999) UpdateLink(this, '{RAND_ID_DIRECTION}')" onClick="if (BoxChecked != -999) { location.href=UpdateLink(this, '{RAND_ID_DIRECTION}'); BoxChecked = 2; return false; }">{L_RANDOM_PIC}</a>
						</td>
					</tr>
				</table></td>
				<td><a title="{NEXT_POSTER_NAME} » {NEXT_TOPIC_TITLE} » {NEXT_COMMENT} ({NEXT_FILESIZE} {L_KILOBYTE})" href="{U_NEXT_DOWNLOAD}" onMouseOver="if (BoxChecked != -999) UpdateLink(this, '{NEXT_ID_DIRECTION}')" onClick="if (BoxChecked != -999) { location.href=UpdateLink(this, '{NEXT_ID_DIRECTION}'); BoxChecked = 2; return false; }"><span style="imageborder"><img src="{U_NEXT_THUMB}" border="1" class="imageborder" alt="{NEXT_POSTER_NAME} » {NEXT_TOPIC_TITLE} » {NEXT_COMMENT} ({NEXT_FILESIZE} {L_KILOBYTE})" /></span></a></td>
			</tr>
		</table></td>
	</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="20"></td>
		<td class{IMAGE_NO_BACKGROUND}="imageload"><span style="imageborder"><img class="imageborder" src="{U_IMAGE}" title="{DOWNLOAD_NAME}" alt="{DOWNLOAD_NAME}" /></span></td>
	</tr>
</table>
<table border="0" cellpadding="0" cellspacing="20" width="100%">
	<tr>
		<td class="slideshow">{MESSAGE}<br /><br />
			{L_POSTED_BY}: <a class="slideshow" href="{U_POSTER_PROFILE}">{POSTER_NAME}</a>{L_EDIT_POST_SET_BORDER}<br />
			{L_IN}: <a class="slideshow" title="{L_VIEW_POST}" href="{U_POST}">{TOPIC_TITLE}</a>
		</td>
	</tr>
</table>
<!-- slideshow photo album mod & design by vHiker -->
</body>
</html>