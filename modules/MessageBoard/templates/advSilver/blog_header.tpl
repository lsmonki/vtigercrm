<script language="javascript">
<!--
function SetLastVisit()
{
	if (!document.LastVisitFrm.search_time.options[0].selected)
{
document.LastVisitFrm.submit();
}
	return true;
}
// --></script>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html dir="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={S_CONTENT_ENCODING}">
<meta http-equiv="Content-Style-Type" content="text/css">
{META}
{NAV_LINKS}
<title>{SITENAME} :: {PAGE_TITLE}</title>

<link rel="stylesheet" href="templates/advSilver/{T_HEAD_STYLESHEET}" type="text/css" />
<style type="text/css">
<!--

/* General page style. The scroll bar colours only visible in IE5.5+ */
body {
	background-color: #ffffff;
	scrollbar-face-color: #DEE3E7;
	scrollbar-highlight-color: #FFFFFF;
	scrollbar-shadow-color: #DEDEDE;
	scrollbar-3dlight-color: #D7D7D7;
	scrollbar-arrow-color:  #666666;
	scrollbar-track-color: #EFEFEF;
	scrollbar-darkshadow-color: #989898;
}

/* General font families for common tags */
font,th,td,p { font-family: Verdana, Arial, Helvetica, sans-serif }
a:link,a:active,a:visited { color : #000000; }
a:hover		{ text-decoration: underline; color : #333333; }
hr	{ height: 0px; border: solid #D7D7D7 0px; border-top-width: 1px;}

/* This is the border line & background colour round the entire page */
.bodyline	{ background-color: #FFFFFF; border: 1px #FF8888 solid; }

/* This is the outline round the main forum tables */
.forumline	{ background-color: #FFFFFF; border: 0px #666666 solid; }

/* Main table cell colours and backgrounds */
td.row1	{ background-color: #F2F2E1; }
td.row2	{ background-color: #F1F1D8; }
td.row3	{ background-color: #D8D8B3; }

/*
  This is for the table cell above the Topics, Post & Last posts on the index.php page
  By default this is the fading out gradiated silver background.
  However, you could replace this with a bitmap specific for each forum
*/
td.rowpic {
		background-color: #FFFFFF;
		background-image: url(templates/advSilver/images/cellpic2.jpg);
		background-repeat: repeat-y;
}

/* Header cells - the blue and silver gradient backgrounds */
th	{
	color: #AAAAAA; font-size: 11px; font-weight : bold;
	background-color: #DEDEDE; height: 25px;
}

td.cat,td.catHead,td.catSides,td.catLeft,td.catRight,td.catBottom {

			background-color: #D7D7D7; border: #FFFFFF; border-style: solid; height: 28px;
}

/*
  Setting additional nice inner borders for the main table cells.
  The names indicate which sides the border will be on.
  Don't worry if you don't understand this, just ignore it :-)
*/
td.cat,td.catHead,td.catBottom {
	height: 29px;
	border-width: 0px 0px 0px 0px;
}
th.thHead,th.thSides,th.thTop,th.thLeft,th.thRight,th.thBottom,th.thCornerL,th.thCornerR {
	font-weight: bold; border: #FFFFFF; border-style: solid; height: 28px;
}
td.row3Right,td.spaceRow {
	background-color: #D7D7D7; border: #FFFFFF; border-style: solid;
}

th.thHead,td.catHead { font-size: 12px; border-width: 1px 1px 0px 1px; }
th.thSides,td.catSides,td.spaceRow	 { border-width: 0px 1px 0px 1px; }
th.thRight,td.catRight,td.row3Right	 { border-width: 0px 1px 0px 0px; }
th.thLeft,td.catLeft	  { border-width: 0px 0px 0px 1px; }
th.thBottom,td.catBottom  { border-width: 0px 1px 1px 1px; }
th.thTop	 { border-width: 1px 0px 0px 0px; }
th.thCornerL { border-width: 1px 0px 0px 1px; }
th.thCornerR { border-width: 1px 1px 0px 0px; }

/* The largest text used in the index page title and toptic title etc. */
.maintitle	{
	font-weight: bold; font-size: 22px; font-family: "Trebuchet MS",Verdana, Arial, Helvetica, sans-serif;
	text-decoration: none; line-height : 120%; color : #000000;
}

/* General text */
.gen { font-size : 12px; }
.genmed { font-size : 11px; }
.gensmall { font-size : 10px; }
.gen,.genmed,.gensmall { color : #000000; }
a.gen,a.genmed,a.gensmall { color: 446688; text-decoration: none; }
a.gen:hover,a.genmed:hover,a.gensmall:hover	{ color: #000033; text-decoration: underline; }

.title { font-size: 36pt; color : 999999; line-height:100% }
.title2 { font-size: 24pt; color : 006699; line-height:50% }
.title3 { }
.time { font-size:12pt; color: DD6900 }
td.back { background-color: EFEFEF }
td.white_back { background-color: FFFFFF }

/* The register, login, search etc links at the top of the page */
.mainmenu		{ font-size : 11px; color : #000000; background-color : #FFCC99; border : f1b888; }
a.mainmenu		{ text-decoration: none; color : #000000;  }
a.mainmenu:hover{ text-decoration: underline; color : #000000; }

/* Forum category titles */
.cattitle		{ font-weight: bold; font-size: 12px ; letter-spacing: 1px; color : #6666666}
a.cattitle		{ text-decoration: none; color : #666666; }
a.cattitle:hover{ text-decoration: underline; }

/* Used for the Weblog category navigation in Blog */
.wcat			{ font-weight: bold; font-size: 12px; color : #D3761F;}
a.wcat			{ text-decoration: none; color : #0000FF; }
a.wcat:hover		{ text-decoration: underline; }


/* Forum title: Text and link to the forums used in: index.php */
.forumlink		{ font-weight: bold; font-size: 12px; color : #666666; }
a.forumlink 	{ text-decoration: none; color : #666666; }
a.forumlink:hover{ text-decoration: underline; color : #333333; }

/* Used for the navigation text, (Page 1,2,3 etc) and the navigation bar when in a forum */
.nav			{ font-weight: bold; font-size: 11px; color : #D3761F;}
a.nav			{ text-decoration: none; color : #666666; }
a.nav:hover		{ text-decoration: underline; }


/* titles for the topics: could specify viewed link colour too */
.topictitle,h1,h2	{ font-weight: bold; font-size: 11px; color : #000000; }
a.topictitle:link   { text-decoration: none; color : #666666; }
a.topictitle:visited { text-decoration: none; color : #666666; }
a.topictitle:hover	{ text-decoration: underline; color : #333333; }

/* Name of poster in viewmsg.php and viewtopic.php and other places */
.name			{ font-size : 11px; color : #000000;}

/* Location, number of posts, post date etc */
.postdetails		{ font-size : 10px; color : #000000; }

/* The content of the posts (body of text) */
.postbody { font-size : 12px; line-height: 18px}
a.postlink:link	{ text-decoration: none; color : #66666 }
a.postlink:visited { text-decoration: none; color : #888888; }
a.postlink:hover { text-decoration: underline; color : #333333}

/* Quote & Code blocks */
.code {
	font-family: Courier, 'Courier New', sans-serif; font-size: 11px; color: #006600;
	background-color: #FAFAFA; border: #D7D7D7; border-style: solid;
	border-left-width: 1px; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px
}

.quote {
	font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; color: #444444; line-height: 125%;
	background-color: #FAFAFA; border: #D1D7DC; border-style: solid;
	border-left-width: 1px; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px
}

/* Copyright and bottom info */
.copyright		{ font-size: 10px; font-family: Verdana, Arial, Helvetica, sans-serif; color: #444444; letter-spacing: -1px;}
a.copyright		{ color: #444444; text-decoration: none;}
a.copyright:hover { color: #000000; text-decoration: underline;}

/* Form elements */
input,textarea, select {
	color : #000000;
	font: normal 11px Verdana, Arial, Helvetica, sans-serif;
	border-color : #000000;
}

/* The text input fields background colour */
input.post, textarea.post, select {
	background-color : #FFFFFF;
}

input { text-indent : 2px; }

/* The buttons used for bbCode styling in message post */
input.button {
	background-color : #EFEFEF;
	color : #000000;
	font-size: 11px; font-family: Verdana, Arial, Helvetica, sans-serif;
}

/* The main submit button option */
input.mainoption {
	background-color : #FAFAFA;
	font-weight : bold;
}

/* None-bold submit button */
input.liteoption {
	background-color : #FAFAFA;
	font-weight : normal;
}

/* This is the line in the posting page which shows the rollover
  help line. This is actually a text box, but if set to be the same
  colour as the background no one will know ;)
*/
.helpline { background-color: #DEE3E7; border-style: none; }

/* Import the fancy styles for IE only (NS4.x doesn't use the @import function) */
@import url("templates/advSilver/formIE.css");
-->
</style>
<!-- BEGIN switch_enable_pm_popup -->
<script language="Javascript" type="text/javascript">
<!--
	if ( {PRIVATE_MESSAGE_NEW_FLAG} )
	{
		window.open('{U_PRIVATEMSGS_POPUP}', '_phpbbprivmsg', 'HEIGHT=225,resizable=yes,WIDTH=400');;
	}
//-->
</script>
<!-- END switch_enable_pm_popup -->
{GREETING_POPUP}
<!-- Added by Isaac for placing cursor -->
<script language="javascript" type="text/javascript">
<!--
	function focusTextField (form) {
		if ((typeof form != 'object') || (typeof form.elements != 'object')) {
			return false;
		}

		var elements = form.elements;
		for (var i = 0; i < elements.length; i++) {
			if ((elements[i].type != 'text') && (elements[i].type != 'password')
				&& (elements[i].type != 'textarea')) {
				continue;
			}
			if (elements[i].value == '') {
				elements[i].focus();
				break;
			}
		}
		return true;
	}
// -->
</script>
<!-- end isaac- placing cursor -->
</head>
<body bgcolor="{T_BODY_BGCOLOR}" text="{T_BODY_TEXT}" link="{T_BODY_LINK}" vlink="{T_BODY_VLINK}">

<a name="top"></a>



<table width="100%" border="0" cellspacing="1" cellpadding="0" align="center" bordercolor="f1b888">
<tr rowspan="2">
<td width="5%" valign="top"><a href="{U_BLOGINDEX}"><img src="templates/advSilver/images/logo_blog.gif" border="0" alt="{L_BLOGINDEX}" /></a></td>
<td width="95%" >
	<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="bodyline">
    <tr>
	<td colspan="4" height="25" align="center" valign="middle" class="mainmenu"><span class="mainmenu"><a href="{U_PORTAL}" class="mainmenu">Forum Home</a> | <a href="{U_WEBLOGS}" class="mainmenu">{L_WEBLOGS}</a> | <a href="{U_MYWEBLOG}" class="mainmenu">{L_MYWEBLOG}</a> | <a href="{U_WEBLOG_CONFIG}" class="mainmenu">{L_WEBLOG_CONFIG}</a> | <a href="{U_BLOGGERLIST}" class="mainmenu">{L_BLOGGERLIST}</a> | <a href="{U_ALLTOPICS}" class="mainmenu">{L_ALLTOPICS}</a> | <a href="{U_SEARCH}" class="mainmenu">{L_SEARCH}</a> | <a href="{U_LOGIN_LOGOUT}" class="mainmenu">{L_LOGIN_LOGOUT}</a></span></td>
	</tr>
<tr>
<!-- LOGIN -->
<td class="mainmenu">{LOGIN_SCREEN_START}
    <form name="overall_login" method="post" action="{S_LOGIN_ACTION}">
	  <td align="right" valign="bottom" height="28"><span class="gensmall">
	  <input class="post" type="text" name="username" size="11" value="username" onBlur="if(this.value==''){this.value='username';}" onFocus="if(this.value=='username'){this.value='';}" />&nbsp;&nbsp;
		<input class="post" type="password" name="password" size="10" maxlength="32" />
		&nbsp;&nbsp;
		{S_HIDDEN_FIELDS}<input type="submit" name="login" class="mainoption" value="{L_LOGIN}" />
		</span></td>
	 </form>
{LOGIN_SCREEN_END}</td>
<td width=30% align="right" valign="bottom" class="mainmenu"><form name="LastVisitFrm" action="search.php" method="post">{SELECT_TIME}</td></form>
<td width=10% align="right" valign="bottom" class="mainmenu">{QUICK_SEARCH}</td>
</tr>
</table>
</td></tr>
</table>
<br>
<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">


			<tr>
				<td align="center" width="100%" valign="middle"><!--span class="maintitle">{SITENAME}</span><br /><span class="gen">{SITE_DESCRIPTION}<br />&nbsp; </span-->
				<table cellspacing="0" cellpadding="2" border="0">
					<tr>
						<!--td align="center" valign="top" nowrap="nowrap"><span class="mainmenu">&nbsp;<a href="{U_WATCHED_TOPICS}" class="mainmenu"><img src="templates/advSilver/images/icon_mini_watch.gif" width="12" height="13" border="0" alt="{L_WATCHED_TOPICS}" hspace="3" />{L_WATCHED_TOPICS}</a></span><span class="mainmenu">&nbsp; &nbsp;<a href="{U_FAQ}" class="mainmenu"><img src="templates/advSilver/images/icon_mini_faq.gif" width="12" height="13" border="0" alt="{L_FAQ}" hspace="3" />{L_FAQ}</a></span><span class="mainmenu">&nbsp; &nbsp;<a href="{U_SEARCH}" class="mainmenu"><img src="templates/advSilver/images/icon_mini_search.gif" width="12" height="13" border="0" alt="{L_SEARCH}" hspace="3" />{L_SEARCH}</a>&nbsp;  &nbsp;<a href="{U_MEMBERLIST}" class="mainmenu"><img src="templates/advSilver/images/icon_mini_members.gif" width="12" height="13" border="0" alt="{L_MEMBERLIST}" hspace="3" />{L_MEMBERLIST}</a>&nbsp; &nbsp;<a href="{U_GROUP_CP}" class="mainmenu"><img src="templates/advSilver/images/icon_mini_groups.gif" width="12" height="13" border="0" alt="{L_USERGROUPS}" hspace="3" />{L_USERGROUPS}</a>&nbsp; &nbsp;<a href="{U_FAV}" class="mainmenu"><img src="templates/advSilver/images/icon_mini_groups.gif" width="12" height="13" border="0" alt="{L_FAV}" hspace="3" />{L_FAV}</a>&nbsp;&nbsp;
						<!-- BEGIN switch_user_logged_out -->
						&nbsp;<a href="{U_REGISTER}" class="mainmenu"><img src="templates/advSilver/images/icon_mini_register.gif" width="12" height="13" border="0" alt="{L_REGISTER}" hspace="3" />{L_REGISTER}</a></span>&nbsp;
						<!-- END switch_user_logged_out -->
						<!-- BEGIN switch_user_logged_in -->
						&nbsp;<a href="{U_BOOKMARKS}" class="mainmenu"><img src="templates/advSilver/images/icon_mini_bookmark.gif" width="12" height="13" border="0" alt="{L_BOOKMARKS}" hspace="3" />{L_BOOKMARKS}</a>&nbsp;
						<!-- END switch_user_logged_in -->

						</td-->
					</tr-->
					<tr>
						<!--td height="25" align="center" valign="top" nowrap="nowrap"><img src="templates/advSilver/images/icon_mini_groups.gif" width="12" height="13" border="0" alt="Forum Home" hspace="3" /><span class="mainmenu">&nbsp;<a href="http://cmtest-server/web/isaacnkumar/tmp/phpBB2/mods/phpbb_fetch_all/examples/portal.php" class="mainmenu">Home</a>&nbsp;<img src="templates/advSilver/images/icon_mini_bookmark.gif" width="12" height="13" border="0" alt="Google News Alert" hspace="3" /><a href="http://forums-server/googlenews/index.php" class="mainmenu">Google Alerts</a>&nbsp; &nbsp;<a href="{U_PROFILE}" class="mainmenu"><img src="templates/advSilver/images/icon_mini_profile.gif" width="12" height="13" border="0" alt="{L_PROFILE}" hspace="3" />{L_PROFILE}</a>&nbsp; &nbsp;<a href="{U_PRIVATEMSGS}" class="mainmenu"><img src="templates/advSilver/images/icon_mini_message.gif" width="12" height="13" border="0" alt="{PRIVATE_MESSAGE_INFO}" hspace="3" />{PRIVATE_MESSAGE_INFO}</a>&nbsp; &nbsp;<a href="{U_LOGIN_LOGOUT}" class="mainmenu"><img src="templates/advSilver/images/icon_mini_login.gif" width="12" height="13" border="0" alt="{L_LOGIN_LOGOUT}" hspace="3" />{L_LOGIN_LOGOUT}</a>&nbsp;</span></td-->
					</tr>
				</table></td>
			</tr>
		</table>
		<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
  		<tr>
			<td valign="top">
			<table width="100%" cellspacing="1" cellpadding="1" border="0" align="left">
			<tr>
			<td>
				<span class="wcat">{WEBLOG_CATEG}</span>
			</td>
			</tr>
			</table>
		</td>
	  	</tr>
		</table>

