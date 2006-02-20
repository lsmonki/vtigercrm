<!-- BEGIN: main -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title>vtiger CRM 5 - Free, Commercial grade Open Source CRM</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link REL="SHORTCUT ICON" HREF="include/images/vtigercrm_icon.ico">	
	<style type="text/css">@import url("themes/{$THEME}/style.css");</style>
</head>
	<body leftmargin=0 topmargin=0 marginheight=0 marginwidth=0 class=small onLoad="Announcement_rss()">
	<!-- header -->
	<!-- header-vtiger crm name & RSS -->
	<script language="JavaScript" type="text/javascript" src="include/js/general.js"></script>
	<script language="JavaScript" type="text/javascript" src="include/js/ajax.js"></script>
	<TABLE border=0 cellspacing=0 cellpadding=0 width=100% class="hdrNameBg">
	<tr>
		<td><img src="{$IMAGEPATH}/vtiger-crm.gif" alt="vtiger CRM" title="vtiger CRM" border=0></td>
		<td width=100% align=center>
		<table border=0 cellspacing=0 cellpadding=2 width=90% class="rssDisplay">
		 <tr>
		 <td class=rssDisplayPanel align=center><marquee id="rss" direction="left" scrolldelay="10" scrollamount="3" behavior="scroll" class="marStyle" onMouseOver="javascript:stop();" onMouseOut="javascript:start();">                                                 .</marquee></td>
		 {*<td class=rssDisplayPanel align=center> RSS is not configured. <a href="#">Click here</a> to configure</td>*}
		 </tr>
		</table>
		</td>
		<td class=small nowrap>
		<table border=0 cellspacing=0 cellpadding=0>
		 <tr>
		 <td style="padding-left:10px;padding-right:10px" class=small nowrap> <a href="index.php?module=Users&action=DetailView&record={$CURRENT_USER_ID}">My Preferences</a></td>
		 <td style="padding-left:10px;padding-right:10px" class=small nowrap> <a href="index.php?module=Users&action=Logout">Logout</a> ({$CURRENT_USER})</td>
		 </tr>
		</table>
		</td>
	</tr>
	</TABLE>

<!-- header - master tabs -->
<TABLE border=0 cellspacing=0 cellpadding=0 width=100% class="hdrTabBg">
<tr>
	<td style="width:50px" class=small>&nbsp;</td>
	<td class=small nowrap> 
		<table border=0 cellspacing=0 cellpadding=0>

		<tr>
			{foreach key=maintabs item=detail from=$HEADERS}
				{if $maintabs ne $CATEGORY}
				  <td class=tabUnSelected align=center><a href="index.php?module={$detail[0]}&action=index&parenttab={$maintabs}">{$maintabs}</a><img src="{$IMAGEPATH}/menuDnArrow.gif" border=0 style="padding-left:5px"></td>
				  <td class=tabSeperator><img src="{$IMAGEPATH}/tabSeperatorBg.gif"></td>
				{else}
				  <td class=tabSelected align=center><a href="index.php?module={$detail[0]}&action=index&parenttab={$maintabs}">{$maintabs}</a><img src="{$IMAGEPATH}/menuDnArrow.gif" border=0 style="padding-left:5px"></td>
				  <td class=tabSeperator><img src="{$IMAGEPATH}/tabSeperatorBg.gif"></td>
				{/if}
			{/foreach}
			<td style="padding-left:10px" nowrap>
			
			{foreach  key=maintabs item=detail from=$HEADERS}
				{if $maintabs eq $CATEGORY}
					<select class=small style="width:120px" title="Create New">
					<option>Quick create...{$maintabs}</option>
					{foreach  key=number item=module from=$detail}
						<option>New -{$module}</option>
					{/foreach}
					</select>
				{/if}
			{/foreach}
			</td>
		</tr>

		</table>
	</td>
	<td align=right style="padding-right:10px" >
		<table border=0 cellspacing=0 cellpadding=0 id="search" style="border:1px solid #999999;background-color:white">
		<tr>
		<td style="height:19px;background-color:#ffffef" ><input type="text" value="Search..." class="searchBox" onClick="this.value=''" ></td>
		<td style="background-color:#cccccc"><input type="button" class="searchBtn" value="Find" alt="Find" title="Find"> </td>
		</td>

		</tr>
		</table>
	</td>
</td>
</tr>
</TABLE>
<!-- header - level 2 tabs -->
<TABLE border=0 cellspacing=0 cellpadding=0 width=100% class="level2Bg" style="border-bottom:1px solid #999999">
<tr>
	<td width=20 class=small>&nbsp;</td>
	<td>

		<table border=0 cellspacing=0 cellpadding=0>
		<tr>
			{foreach  key=maintabs item=detail from=$HEADERS}
				{if $maintabs eq $CATEGORY}
					{foreach  key=number item=module from=$detail}
						
						<td class="level2text"> <a href="index.php?module={$module}&action=index&parenttab={$maintabs}">{$module}</a> </td>
					{/foreach}
				{/if}
			{/foreach}
		</tr>
		</table>
	</td>
</tr>
</TABLE>		
<script>
function Announcement_rss()
{ldelim}
	var ajaxObj = new Ajax(ajaxResponse);
	var urlstring = "module=Users&action=UsersAjax&announce_rss=yes";
		ajaxObj.process("index.php?",urlstring);

{rdelim}
function ajaxResponse(response)
{ldelim}
	if(document.getElementById("rss").innerHTML != response.responseText)
		document.getElementById("rss").innerHTML=response.responseText;
{rdelim}
setInterval("Announcement_rss()",5000)
</script>
