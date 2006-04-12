<script language="JavaScript" type="text/javascript" src="modules/Rss/Rss.js"></script>
<script>
{literal}

function ajaxSetDefault(response)
{
	hide('status');
	getrssfolders();
	//window.location = "index.php?module=Rss&action=ListView&record="+response.responseText;
}
function GetRssFeedList(id)
{
	show('status');	
	var ajaxObj = new Ajax(ajaxRssFeedResponse);
	var urlstring = 'module=Rss&action=RssAjax&vtigerfile=ListView&directmode=ajax&record='+id;
	ajaxObj.process("index.php?",urlstring);
}
function ajaxRssFeedResponse(response)
{
	hide('status');	
	document.getElementById("rssfeedscont").innerHTML=response.responseText;
}
function DeleteRssFeeds(id)
{
	show('status');	
	var feed = 'feed_'+id;
	document.getElementById(feed).parentNode.removeChild(document.getElementById(feed));
	var ajaxObj = new Ajax(ajaxRssFeedResponse);
	var urlstring = 'module=Rss&return_module=Rss&action=RssAjax&vtigerfile=Delete&directmode=ajax&record='+id;
	ajaxObj.process("index.php?",urlstring);
}
function SaveRssFeeds()
{
	show('status');	
	var ajaxObj = new Ajax(ajaxRssSaveResponse);
	var rssurl = document.getElementById('rssurl').value;
	var category = document.getElementById('rsscategory')[document.getElementById('rsscategory').selectedIndex].value;
	var urlstring = 'module=Rss&action=RssAjax&vtigerfile=Popup&directmode=ajax&rssurl='+rssurl+'&rsscategory='+category;
	ajaxObj.process("index.php?",urlstring);
}
function ajaxRssSaveResponse(response)
{
	hide('status')	
	if(isNaN(parseInt(response.responseText)))	
	{
		alert(response.responseText);
		document.getElementById('rssurl').value = '';
	}
	else
	{
		GetRssFeedList(response.responseText);
		getrssfolders();
		document.getElementById('rssurl').value = '';
		fninvsh('PopupLay');	
		//window.location = "index.php?module=Rss&action=ListView&record="+response.responseText;
	}
		
}
{/literal}
</script>

<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%">
	<tr>
	<td class="showPanelBg" valign="top" width="95%"  style="padding-left:20px; "><br />
	<span class="lvtHeaderText">Tools &gt; Rss </span>
	<hr noshade="noshade" size="1" />
	</td>

	<td width="5%" class="showPanelBg">&nbsp;</td>
	</tr>
	<tr>
	<td width="95%" style="padding-left:20px;" valign="top">
	<!-- module Select Table -->
		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td width="7" height="6" style="font-size:1px;font-family:Arial, Helvetica, sans-serif;"><img src="{$IMAGEPATH}top_left.jpg" align="top"  /></td>
		<td bgcolor="#EBEBEB" style="font-size:1px;font-family:Arial, Helvetica, sans-serif;height:6px;"></td>

		<td width="8" height="6" style="font-size:1px;font-family:Arial, Helvetica, sans-serif;"><img src="{$IMAGEPATH}top_right.jpg" width="8" height="6" align="top" /></td>
		</tr>
		<tr>
		<td bgcolor="#EBEBEB" width="7"></td>
		<td bgcolor="#ECECEC" style="padding-left:10px;height:20px;vertical-align:middle;">
			<table width="100%"  border="0" cellspacing="0" cellpadding="0">
			<tr>
			
			<td style="padding:10px;vertical-align:middle;" width="28%">
			<img src='{$IMAGEPATH}rssroot.gif' align='absmiddle'/><a href="javascript:;" onClick="fnvshobj(this,'PopupLay');" title='{$APP.LBL_ADD_RSS_FEEDS}'>Add Rss Feed</a>
			</td>
			<td width="2%">&nbsp;</td>
			<td width="60%"><img src="{$IMAGEPATH}rssimage.gif" width="176" height="44"  align="right"/></td>
			</tr>
			<tr><td colspan="3">
				
				<table width="100%"  border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td bgcolor="#949494" width="25%" height="25">&nbsp;</td>
				<td rowspan="2" width="2%">&nbsp;</td>
				<td rowspan="2">
					<div id="rssfeedscont">
						{include file='RssFeeds.tpl'}
					</div>
				</td>
				</tr>
				<tr><td valign="top" bgcolor="#FFFFFF"><div id="rssfolders">{$RSSFEEDS}</div></td></tr>
				</table>
				
			</td></tr>
	
			<tr>		
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td height="5"></td>
			</tr>
			
			<tr>
			<td colspan="3" class="frameHdr" id="rsstitle">&nbsp;</td>
			</tr>
			<tr>
			<td colspan="3">
			<iframe width="100%" height="300" frameborder="0" id="mysite" scrolling="auto" marginheight="0" marginwidth="0" style="background-color:#FFFFFF;"></iframe>
			</td>
			</tr>
			</table>
	
		</td>
		<td bgcolor="#EBEBEB" width="8"></td>
		</tr>
		<tr>
		<td width="7" height="8" style="font-size:1px;font-family:Arial, Helvetica, sans-serif;"><img src="{$IMAGEPATH}bottom_left.jpg" align="bottom"  /></td>
		<td bgcolor="#ECECEC" height="8" style="font-size:1px;" ></td>
		<td width="8" height="8" style="font-size:1px;font-family:Arial, Helvetica, sans-serif;"><img src="{$IMAGEPATH}bottom_right.jpg" align="bottom" /></td>
		</tr>
		</table><br />
	
	</td>
	<td>&nbsp;</td>
	</tr>
</table>
<div id="status" style="display:none;position:absolute;background-color:#bbbbbb;left:887px;top:0px;height:17px;white-space:nowrap;"">Processing Request...</div>
	<div id="PopupLay">
	<table width="100%" border="0" cellpadding="5" cellspacing="0">
	<tr>
	<td class="genHeaderSmall" align="left"><img src="{$IMAGEPATH}rssroot.gif" width="24" height="22" align="absmiddle" />&nbsp;Add RSS Feed</td>
	<td align="right"><a href="javascript:fninvsh('PopupLay');"><img src="{$IMAGEPATH}close.gif" border="0"  align="absmiddle" /></a></td>
	</tr>
	<tr><td colspan="2"><hr /></td></tr>
	<tr>
	<td align="right" width="30%"><b>Feed :</b></td>
	<td align="left" width="70%"><input type="text" id="rssurl" class="txtBox" /></td>
	</tr>
	
	<tr>
	<td align="right"><b>Category : </b></td>
	<td align="left"><select id="rsscategory" class="importBox" style="width:100%">{$RSSCATEG}</select></td>
	</tr>
	<tr><td colspan="2" style="border-bottom:1px dashed #CCCCCC;">&nbsp;</td></tr>
	<tr>
	<td	colspan="2" align="center">
	<input type="button" name="save" value=" &nbsp;Save&nbsp; " class="classBtn" onClick="SaveRssFeeds();"/>&nbsp;&nbsp;
	</td>
	</tr>
	<tr><td colspan="2" style="border-top:1px dashed #CCCCCC;">&nbsp;</td></tr>
	</table>
	</div>

<script type="text/javascript" language="Javascript">
function toggleRSSFolder(id) {ldelim}
	if (document.getElementById(id+"_feeds").style.display=="none") {ldelim}
		document.getElementById(id+"_feeds").style.display="block"
		document.getElementById(id+"_folder").src="{$IMAGEPATH}rss_folder_opn.gif"
		document.getElementById(id+"_toggle").src="{$IMAGEPATH}minus.gif"
	{rdelim} else {ldelim}
		document.getElementById(id+"_feeds").style.display="none"
		document.getElementById(id+"_folder").src="{$IMAGEPATH}rss_folder_cls.gif"
		document.getElementById(id+"_toggle").src="{$IMAGEPATH}plus.gif"
	{rdelim}

{rdelim}
function makedefaultRss(id)
{ldelim}
	show('status');	
	var ajaxObj = new Ajax(ajaxSetDefault);
	var urlstring = 'module=Rss&action=RssAjax&vtigerfile=Popup&directmode=ajax&record='+id;
	ajaxObj.process("index.php?",urlstring);
{rdelim}
function getrssfolders()
{ldelim}
	var ajaxObj = new Ajax(ajaxrssfolders);
	var urlstring = 'module=Rss&action=RssAjax&vtigerfile=ListView&folders=true';
	ajaxObj.process("index.php?",urlstring);
{rdelim}
function ajaxrssfolders(response)
{ldelim}
	hide('status')
	document.getElementById("rssfolders").innerHTML=response.responseText;
{rdelim}
</script>
