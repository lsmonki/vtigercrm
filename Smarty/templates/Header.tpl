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
	<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
	<TABLE border=0 cellspacing=0 cellpadding=0 width=100% class="hdrNameBg">
	<tr>
		<td><img src="{$IMAGEPATH}/vtiger-crm.gif" alt="vtiger CRM" title="vtiger CRM" border=0></td>
		<td width=100% align=center>
		<table border=0 cellspacing=0 cellpadding=2 width=90% class="rssDisplay">
		 <tr>
		 <td class=rssDisplayPanel align=center><marquee id="rss" direction="left" scrolldelay="10" scrollamount="3" behavior="scroll" class="marStyle" onMouseOver="javascript:stop();" onMouseOut="javascript:start();">&nbsp;</marquee></td>
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
					<select class=small style="width:120px" title="Create New" onchange="QCreate(this);">
					<option>Quick create...{$maintabs}</option>
					{foreach  key=number item=module from=$detail}
						<option value="{$module}">New -{$module}</option>
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
			<form name="UnifiedSearch" method="post" action="index.php">
			<td style="height:19px;background-color:#ffffef" >
				<input type="hidden" name="action" value="UnifiedSearch">
				<input type="hidden" name="module" value="Home">
				<input type="text" name="query_string" value="{$QUERY_STRING}" class="searchBox" onFocus="this.value=''" >
			</td>
			<td style="background-color:#cccccc">
				<input type="submit" class="searchBtn" value="Find" alt="Find" title="Find">
			</td>
			</form>
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

<div id="calc" style="position:absolute;width:168px;height:188px;left:100px;top:100px;background-color:#E5E5E5;visibility:hidden;">
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr><td align="right" style="padding:3px;"><a href="#"><img src="{$IMAGEPATH}del.gif" border="0" onclick="fninvsh('calc')" /></a></td>
	</tr>
	<tr><td>{$CALC}</td></tr>
	</table>
</div>

<div id="wclock">
	<table class="leftFormBorder1" align="center" border="0" cellpadding="2" cellspacing="0" width="100%">
	<tbody>
	<tr><td align="right" style="padding-right:1px;"><a href="#"><img src="{$IMAGEPATH}del.gif" style="border:1px solid #E8F8FF;" onClick="fninvsh('wclock')"></a></td></tr>
	<tr>
	<td nowrap="nowrap"><div style="background-image: url(themes/blue/images/clock_bg.gif); background-repeat: no-repeat; background-position: 4px 38px;" id="theClockLayer">
<div id="theCities" class="citystyle">
<form action="" name="frmtimezone">
<input name="PHPSESSID" value="162c0ab587f6c555aaaa30d681b61f7c" type="hidden">
<select name="city" size="1" class="detailedViewTextBox"  id="city" style="width:125px;"  onchange="lcl(this.selectedIndex,this.options[0].selected)">
<option value="0" selected="selected">Local time</option>
<option value="4.30">Afghanistan</option>
<option value="1">Algeria</option>
<option value="-3">Argentina</option>
<option value="9.30">Australia - Adelaide</option>
<option value="8">Australia - Perth</option>
<option value="10">Australia - Sydney</option>
<option value="1">Austria</option>
<option value="3">Bahrain</option>
<option value="6">Bangladesh</option>
<option value="1">Belgium</option>
<option value="-4">Bolivia</option>
<option value="-5">Brazil - Andes</option>
<option value="-3">Brazil - East</option>
<option value="-4">Brazil - West</option>
<option value="2">Bulgaria</option>
<option value="6.30">Burma (Myanmar)</option>
<option value="-5">Chile</option>
<option value="-7">Canada - Calgary</option>
<option value="-3.30">Canada - Newfoundland</option>
<option value="-4">Canada - Nova Scotia</option>
<option value="-5">Canada - Toronto</option>
<option value="-8">Canada - Vancouver</option>
<option value="-6">Canada - Winnipeg</option>
<option value="8">China - Mainland</option>
<option value="8">China - Taiwan</option>
<option value="-5">Colombia</option>
<option value="-5">Cuba</option>
<option value="1">Denmark</option>
<option value="-5">Ecuador</option>
<option value="2">Egypt</option>
<option value="12">Fiji</option>
<option value="2">Finland</option>
<option value="1">France</option>
<option value="1">Germany</option>
<option value="0">Ghana</option>
<option value="2">Greece</option>
<option value="-3">Greenland</option>
<option value="1">Hungary</option>
<option value="5.30">India</option>
<option value="8">Indonesia - Bali, Borneo</option>
<option value="9">Indonesia - Irian Jaya</option>
<option value="7">Indonesia - Sumatra, Java</option>
<option value="3.30">Iran</option>
<option value="3">Iraq</option>
<option value="2">Israel</option>
<option value="1">Italy</option>
<option value="-5">Jamaica</option>
<option value="9">Japan</option>
<option value="3">Kenya</option>
<option value="9">Korea (North &amp; South)</option>
<option value="3">Kuwait</option>
<option value="1">Libya</option>
<option value="8">Malaysia</option>
<option value="5">Maldives</option>
<option value="1">Mali</option>
<option value="4">Mauritius</option>
<option value="-6">Mexico</option>
<option value="0">Morocco</option>
<option value="5.45">Nepal</option>
<option value="1">Netherlands</option>
<option value="12">New Zealand</option>
<option value="1">Nigeria</option>
<option value="1">Norway</option>
<option value="4">Oman</option>
<option value="5">Pakistan</option>
<option value="-5">Peru</option>
<option value="8">Philippines</option>
<option value="1">Poland</option>
<option value="1">Portugal</option>
<option value="3">Qatar</option>
<option value="2">Romania</option>
<option value="11">Russia - Kamchatka</option>
<option value="3">Russia - Moscow</option>
<option value="9">Russia - Vladivostok</option>
<option value="4">Seychelles</option>
<option value="3">Saudi Arabia</option>
<option value="8">Singapore</option>
<option value="2">South Africa</option>
<option value="1">Spain</option>
<option value="3">Syria</option>
<option value="5.30">Sri Lanka</option>
<option value="1">Sweden</option>
<option value="1">Switzerland</option>
<option value="7">Thailand</option>
<option value="12">Tonga</option>
<option value="2">Turkey</option>
<option value="3">Ukraine</option>
<option value="5">Uzbekistan</option>
<option value="7">Vietnam</option>
<option value="4">UAE</option>
<option value="0">UK</option>
<option value="-9">USA - Alaska</option>
<option value="-9">USA - Arizona</option>
<option value="-6">USA - Central</option>
<option value="-5">USA - Eastern</option>
<option value="-10">USA - Hawaii</option>
<option value="-5">USA - Indiana East</option>
<option value="-7">USA - Mountain</option>
<option value="-8">USA - Pacific</option>
<option value="3">Yemen</option>
<option value="1">Yugoslavia</option>
<option value="2">Zambia</option>
<option value="2">Zimbabwe</option>
</select>
</form>
</div>
<script language="javascript" src="include/js/clock.js"></script>            

<div id="theFace0" class="facestyle" style="color: rgb(0, 0, 0); top: 81px; left: 96px;">3</div>
<div id="theFace1" class="facestyle" style="color: rgb(0, 0, 0); top: 102px; left: 90.3731px;">4</div>
<div id="theFace2" class="facestyle" style="color: rgb(0, 0, 0); top: 117.373px; left: 75px;">5</div>
<div id="theFace3" class="facestyle" style="color: rgb(0, 0, 0); top: 123px; left: 54px;">6</div>
<div id="theFace4" class="facestyle" style="color: rgb(0, 0, 0); top: 117.373px; left: 33px;">7</div>
<div id="theFace5" class="facestyle" style="color: rgb(0, 0, 0); top: 102px; left: 17.6269px;">8</div>
<div id="theFace6" class="facestyle" style="color: rgb(0, 0, 0); top: 81px; left: 12px;">9</div>
<div id="theFace7" class="facestyle" style="color: rgb(0, 0, 0); top: 60px; left: 17.6269px;">10</div>
<div id="theFace8" class="facestyle" style="color: rgb(0, 0, 0); top: 44.6269px; left: 33px;">11</div>
<div id="theFace9" class="facestyle" style="color: rgb(0, 0, 0); top: 39px; left: 54px;">12</div>
<div id="theFace10" class="facestyle" style="color: rgb(0, 0, 0); top: 44.6269px; left: 75px;">1</div>
<div id="theFace11" class="facestyle" style="color: rgb(0, 0, 0); top: 60px; left: 90.3731px;">2</div>
</div></td>
</tr>
</tbody>
</table>
</div>

<div id="qcform" style="position:absolute;width:500px;top:60px;left:450px;"></div>
<script>
function Announcement_rss()
{ldelim}
	var ajaxObj = new Ajax(ajaxRssResponse);
	var urlstring = "module=Users&action=UsersAjax&announce_rss=yes";
		ajaxObj.process("index.php?",urlstring);

{rdelim}
function ajaxRssResponse(response)
{ldelim}
	if(document.getElementById("rss").innerHTML != response.responseText)
		document.getElementById("rss").innerHTML=response.responseText;
{rdelim}
setInterval("Announcement_rss()",300000)
</script>

<script>
function QCreate(qcoptions)
{ldelim}
        show("status");
        var ajaxObj = new Ajax(ajaxQCreateResponse);
        var module = qcoptions.options[qcoptions.options.selectedIndex].value;
        var urlstring = "module="+module+"&action="+module+"Ajax&ajaxmode=qcreate";
        ajaxObj.process("index.php?",urlstring);
{rdelim}
function ajaxQCreateResponse(response)
{ldelim}
        hide("status");
        show("qcform");
        document.getElementById('qcform').innerHTML = response.responseText;
{rdelim}
</script>


{* Begining of Slide Menu *}

<div id="mnuSlide">
<table border="0" cellpadding="0" cellspacing="0">
       <tr>
       <td  width="112" id="slidemenu">
       <div id="slide" style="display:none;">
       {foreach item=modulename from=$MODULELISTS}
               <a href="index.php?module={$modulename}&action=index" class="submenu">{$modulename}</a>
       {/foreach}
       </div>
       </td>
       <td width="8" valign="top">
       <div id="mnuKey" onclick="fnSlide1('slidemenu','slide')" ><img src="{$IMAGEPATH}/jump.gif" style="opacity:.7;"></div>
       </td>
       </tr>
</table>
</div>

