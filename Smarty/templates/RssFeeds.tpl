<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr><td colspan="2" class="subHdr"><b>Feeds list from : {$TITLE}</b></td></tr>
	<tr class="delBg"><td>
		<input type="button" name="delete" value=" Delete " class="classBtn" onClick="DeleteRssFeeds('{$ID}');"/></td>
		<td align="right"><input type="button" name="setdefault" value=" Set As Default " class="classBtn" onClick="makedefaultRss('{$ID}');"/>
	</td></tr>
	<tr><td colspan="2" align="left"><div id="rssScroll">{$RSSDETAILS}</div></td></tr>
</table>
