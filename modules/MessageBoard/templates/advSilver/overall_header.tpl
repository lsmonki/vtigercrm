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
<html dir="{S_CONTENT_DIRECTION}">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={S_CONTENT_ENCODING}">
<meta http-equiv="Content-Style-Type" content="text/css">
{META}
{NAV_LINKS}
<title>{SITENAME} :: {PAGE_TITLE}</title>

<link rel="stylesheet" href="templates/advSilver/{T_HEAD_STYLESHEET}" type="text/css" />
<style type="text/css">
</style>
<!-- BEGIN switch_enable_pm_popup -->
<!-- added by isaac to place cursor in the first empty field in a page-->
<script language="Javascript" type="text/javascript">
<!--
	if ( {PRIVATE_MESSAGE_NEW_FLAG} )
	{
		window.open('{U_PRIVATEMSGS_POPUP}', '_phpbbprivmsg', 'HEIGHT=225,resizable=yes,WIDTH=400');;
	}
//-->
</script>
<!-- END switch_enable_pm_popup -->
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


<table width="100%" border="0" cellspacing="1" cellpadding="0">
<tr rowspan="2">
	<!-- td width="5%" valign="top"><a href="{U_PORTAL}"><img src="templates/advSilver/images/logo_phpBB.gif" border="0" alt="{L_INDEX}" vspace="1" /></a></td -->
	<td width="95%">
		<table width="100%" border="0" cellspacing="1" cellpadding="0" align="center" class="bodyline">
		<!--tr>
			<td colspan="4" height="25" align="center" valign="middle"><span class="mainmenu"><!--a href="{U_PORTAL}" class="mainmenu">Home</a> | <a href="{U_INDEX}" class="mainmenu">Home</a--><!-- | <a href="{U_WATCHED_TOPICS}" class="mainmenu">{L_WATCHED_TOPICS}</a> | <a href="{U_FAQ}" class="mainmenu">{L_FAQ}</a> | <a href="{U_WEBLOGS}" class="mainmenu">{L_WEBLOGS}</a -->
                <!-- BEGIN switch_weblog_user -->
               <!-- a href="{U_WEBLOG_CONFIG}" class="mainmenu">{L_WEBLOG_CONFIG}</a -->
                <!-- END switch_weblog_user -->
                <!-- BEGIN switch_weblog_user -->
               <!-- a href="{U_MYWEBLOG}" class="mainmenu">{L_MYWEBLOG}</a -->
                <!-- END switch_weblog_user -->
                <!-- BEGIN switch_user_logged_out -->
			  	<!-- a href="{U_REGISTER}" class="mainmenu">{L_REGISTER}</a -->
				<!-- END switch_user_logged_out -->
				<!-- | <a href="{U_SEARCH}" class="mainmenu">{L_SEARCH}</a>
			  | <a href="{U_MEMBERLIST}" class="mainmenu">{L_MEMBERLIST}</a-->
			   <!-- a href="{U_GROUP_CP}" class="mainmenu">{L_USERGROUPS}</a -->
			   <!-- a href="{U_FAV}" class="mainmenu">{L_FAV}</a>
			  | <a href="{U_PROFILE}" class="mainmenu">{L_PROFILE}</a-->
			   <!-- a href="{U_PRIVATEMSGS}" class="mainmenu">{PRIVATE_MESSAGE_INFO}</a -->
			 <!-- | <a href="{U_LOGIN_LOGOUT}" class="mainmenu">{L_LOGIN_LOGOUT}</a --></span></td>
		</tr-->


<tr>
<!-- LOGIN -->
</tr>
</table>
</td></tr>
</table>
<br>
<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">

	<td class="bodyline"><table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<!--td><a href="{U_INDEX}"><img src="templates/advSilver/images/logo_phpBB.gif" border="0" alt="{L_INDEX}" vspace="1" /></a></td-->
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


