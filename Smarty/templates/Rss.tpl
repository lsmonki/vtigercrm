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
<script>
{literal}

function GetRssFeedList(id)
{
	$("status").style.display="inline";
        new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: 'module=Rss&action=RssAjax&file=ListView&directmode=ajax&record='+id,
                        onComplete: function(response) {
                                $("status").style.display="none";
				$("rssfeedscont").innerHTML=response.responseText;
                        }
                }
        );
}

function DeleteRssFeeds(id)
{
   if(id != '')	
   {		
  	if(confirm('Are you sure to delete the rss feed?'))
	{	
		show('status');	
		var feed = 'feed_'+id;
		$(feed).parentNode.removeChild($(feed));
		new Ajax.Request(
                	'index.php',
        	        {queue: {position: 'end', scope: 'command'},
                        	method: 'post',
	                        postBody: 'module=Rss&return_module=Rss&action=RssAjax&file=Delete&directmode=ajax&record='+id,
        	                onComplete: function(response) {
	        	                $("status").style.display="none";
                                	$("rssfeedscont").innerHTML=response.responseText;
					$("mysite").src = '';
					$("rsstitle").innerHTML = "&nbsp";
                        	}
                	}
        	);
	}
   }
}
function SaveRssFeeds()
{
	$("status").style.display="inline";
	var rssurl = $('rssurl').value;
	rssurl = rssurl.replace(/&/gi,"##amp##");
	new Ajax.Request(
		'index.php',
		{queue: {position: 'end', scope: 'command'},
			method: 'post',
			postBody:'module=Rss&action=RssAjax&file=Popup&directmode=ajax&rssurl='+rssurl, 
			onComplete: function(response) {
	
                                        $("status").style.display="none";
					if(isNaN(parseInt(response.responseText)))
        				{
				                alert(response.responseText);
				                $('rssurl').value = '';
					}
					else
        				{
				                GetRssFeedList(response.responseText);
				                getrssfolders();
				                $('rssurl').value = '';
				                Effect.Puff('PopupLay');
        				}
                                }
                        }
                );
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
			<img src='{$IMAGE_PATH}rssroot.gif' align='absmiddle'/><a href="javascript:;" onClick="fnvshobj(this,'PopupLay');$('rssurl').focus();" title='{$APP.LBL_ADD_RSS_FEEDS}'>{$MOD.LBL_ADD_RSS_FEED}</a>
			</td>
			<td width="2%">&nbsp;</td>
			<td width="60%"><img src="{$IMAGE_PATH}rssimage.gif" align="right"/></td>
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
				<tr><td valign="top" bgcolor="#FFFFFF" align="left"><div id="rssfolders" style="height:180px;overflow:auto;">{$RSSFEEDS}</div></td></tr>
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
	<td align="left" width="70%"><input type="text" id="rssurl" class="txtBox" value=""/></td>
	</tr>
	
	<tr><td colspan="2" style="border-bottom:1px dashed #CCCCCC;">&nbsp;</td></tr>
	<tr>
	<td	colspan="2" align="center">
	<input type="button" name="save" value=" &nbsp;{$APP.LBL_SAVE_BUTTON_LABEL}&nbsp; " class="crmbutton small save" onClick="SaveRssFeeds();"/>&nbsp;&nbsp;
	</td>
	</tr>
	<tr><td colspan="2" style="border-top:1px dashed #CCCCCC;">&nbsp;</td></tr>
	</table>
	</div>

<script type="text/javascript" language="Javascript">
function makedefaultRss(id)
{ldelim}
	if(id != '')
	{ldelim}
		$("status").style.display="inline";
		new Ajax.Request(
                	'index.php',
	                {ldelim}queue: {ldelim}position: 'end', scope: 'command'{rdelim},
        	                method: 'post',
                	        postBody:'module=Rss&action=RssAjax&file=Popup&directmode=ajax&record='+id, 
                        	onComplete: function(response) {ldelim}
                                	$("status").style.display="none";
        				getrssfolders();
        	               {rdelim}
                	{rdelim}
        	);
	{rdelim}
{rdelim}
function getrssfolders()
{ldelim}
	new Ajax.Request(
        	'index.php',
                {ldelim}queue: {ldelim}position: 'end', scope: 'command'{rdelim},
                	method: 'post',
                        postBody:'module=Rss&action=RssAjax&file=ListView&folders=true',
			onComplete: function(response) {ldelim}
                        		$("status").style.display="none";
					$("rssfolders").innerHTML=response.responseText;
                               {rdelim}
                        {rdelim}
                );
{rdelim}
</script>
