{*<!--
/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
   * ("License"); You may not use this file except in compliance with the License
   * The Original Code is:  vtiger CRM Open Source
   * The Initial Developer of the Original Code is vtiger.
   * Portions created by vtiger are Copyright (C) vtiger.
   * All Rights Reserved.
  *
 ********************************************************************************/
-->*}
<script language="JavaScript" type="text/javascript" src="modules/Rss/Rss.js"></script>
<script src="include/scriptaculous/prototype.js" type="text/javascript"></script>
<script src="include/scriptaculous/scriptaculous.js" type="text/javascript"></script>
<script language="JavaScript" type="text/javascript" src="include/js/ajax.js"></script>
<script>
{literal}

function ajaxSetDefault(response)
{
	hide('status');
	getrssfolders();
}
function GetRssFeedList(id)
{
	show('status');	
	var ajaxObj = new VtigerAjax(ajaxRssFeedResponse);
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
   if(id != '')	
   {		
  	if(confirm('Are you sure to delete the rss feed?'))
	{	
		show('status');	
		var feed = 'feed_'+id;
		document.getElementById(feed).parentNode.removeChild(document.getElementById(feed));
		var ajaxObj = new VtigerAjax(ajaxRssFeedResponse);
		var urlstring = 'module=Rss&return_module=Rss&action=RssAjax&vtigerfile=Delete&directmode=ajax&record='+id;
		ajaxObj.process("index.php?",urlstring);
	}
   }
}
function SaveRssFeeds()
{
	show('status');	
	var ajaxObj = new VtigerAjax(ajaxRssSaveResponse);
	rssurl = document.getElementById('rssurl').value;
	rssurl = rssurl.replace(/&/gi,"##amp##");
	var category = document.getElementById('rsscategory')[document.getElementById('rsscategory').selectedIndex].value;
	var urlstring = 'module=Rss&action=RssAjax&vtigerfile=Popup&directmode=ajax&rsscategory='+category+'&rssurl='+rssurl;
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
		Effect.Puff('PopupLay');
	}
		
}
{/literal}
</script>

<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%">
	<tr>
	<td class="showPanelBg" valign="top" width="95%"  style="padding-left:20px; "><br />
	{include file="Buttons_List1.tpl"}
	<hr noshade="noshade" size="1" />
	</td>

	<td width="5%" class="showPanelBg">&nbsp;</td>
	</tr>
	<tr>
	<td width="95%" style="padding-left:20px;" valign="top">
	<!-- module Select Table -->
		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td width="7" height="6" style="font-size:1px;font-family:Arial, Helvetica, sans-serif;"><img src="{$IMAGE_PATH}top_left.jpg" align="top"  /></td>
		<td bgcolor="#EBEBEB" style="font-size:1px;font-family:Arial, Helvetica, sans-serif;height:6px;"></td>

		<td width="8" height="6" style="font-size:1px;font-family:Arial, Helvetica, sans-serif;"><img src="{$IMAGE_PATH}top_right.jpg" width="8" height="6" align="top" /></td>
		</tr>
		<tr>
		<td bgcolor="#EBEBEB" width="7"></td>
		<td bgcolor="#ECECEC" style="padding-left:10px;height:20px;vertical-align:middle;">
			<table width="100%"  border="0" cellspacing="0" cellpadding="0">
			<tr>
			
			<td style="padding:10px;vertical-align:middle;" width="28%">
			<img src='{$IMAGE_PATH}rssroot.gif' align='absmiddle'/><a href="javascript:;" onClick="fnvshobj(this,'PopupLay');" title='{$APP.LBL_ADD_RSS_FEEDS}'>{$MOD.LBL_ADD_RSS_FEED}</a>
			</td>
			<td width="2%">&nbsp;</td>
			<td width="60%"><img src="{$IMAGE_PATH}rssimage.gif" width="176" height="44"  align="right"/></td>
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
		<td width="7" height="8" style="font-size:1px;font-family:Arial, Helvetica, sans-serif;"><img src="{$IMAGE_PATH}bottom_left.jpg" align="bottom"  /></td>
		<td bgcolor="#ECECEC" height="8" style="font-size:1px;" ></td>
		<td width="8" height="8" style="font-size:1px;font-family:Arial, Helvetica, sans-serif;"><img src="{$IMAGE_PATH}bottom_right.jpg" align="bottom" /></td>
		</tr>
		</table><br />
	
	</td>
	<td>&nbsp;</td>
	</tr>
</table>
	<div id="PopupLay">
	<table width="100%" border="0" cellpadding="5" cellspacing="0">
	<tr>
	<td class="genHeaderSmall" align="left"><img src="{$IMAGE_PATH}rssroot.gif" width="24" height="22" align="absmiddle" />&nbsp;{$MOD.LBL_ADD_RSS_FEED}</td>
	<td align="right"><a href="javascript:fninvsh('PopupLay');"><img src="{$IMAGE_PATH}close.gif" border="0"  align="absmiddle" /></a></td>
	</tr>
	<tr><td colspan="2"><hr /></td></tr>
	<tr>
	<td align="right" width="30%"><b>{$MOD.LBL_FEED}</b></td>
	<td align="left" width="70%"><input type="text" id="rssurl" class="txtBox" /></td>
	</tr>
	
	<tr>
	<td align="right"><b>{$MOD.LBL_CATEGORY}</b></td>
	<td align="left"><select id="rsscategory" class="importBox" style="width:100%">{$RSSCATEG}</select></td>
	</tr>
	<tr><td colspan="2" style="border-bottom:1px dashed #CCCCCC;">&nbsp;</td></tr>
	<tr>
	<td	colspan="2" align="center">
	<input type="button" name="save" value=" &nbsp;{$APP.LBL_SAVE_BUTTON_LABEL}&nbsp; " class="classBtn" onClick="SaveRssFeeds();"/>&nbsp;&nbsp;
	</td>
	</tr>
	<tr><td colspan="2" style="border-top:1px dashed #CCCCCC;">&nbsp;</td></tr>
	</table>
	</div>

<script type="text/javascript" language="Javascript">
function toggleRSSFolder(id) {ldelim}
	if (document.getElementById(id+"_feeds").style.display=="none") {ldelim}
		document.getElementById(id+"_feeds").style.display="block"
		document.getElementById(id+"_folder").src="{$IMAGE_PATH}rss_folder_opn.gif"
		document.getElementById(id+"_toggle").src="{$IMAGE_PATH}minus.gif"
	{rdelim} else {ldelim}
		document.getElementById(id+"_feeds").style.display="none"
		document.getElementById(id+"_folder").src="{$IMAGE_PATH}rss_folder_cls.gif"
		document.getElementById(id+"_toggle").src="{$IMAGE_PATH}plus.gif"
	{rdelim}

{rdelim}
function makedefaultRss(id)
{ldelim}
	if(id != '')
	{ldelim}
		show('status');	
		var ajaxObj = new VtigerAjax(ajaxSetDefault);
		var urlstring = 'module=Rss&action=RssAjax&vtigerfile=Popup&directmode=ajax&record='+id;
		ajaxObj.process("index.php?",urlstring);
	{rdelim}
{rdelim}
function getrssfolders()
{ldelim}
	var ajaxObj = new VtigerAjax(ajaxrssfolders);
	var urlstring = 'module=Rss&action=RssAjax&vtigerfile=ListView&folders=true';
	ajaxObj.process("index.php?",urlstring);
{rdelim}
function ajaxrssfolders(response)
{ldelim}
	hide('status')
	document.getElementById("rssfolders").innerHTML=response.responseText;
{rdelim}
</script>
