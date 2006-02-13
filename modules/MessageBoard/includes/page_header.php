<?php
/***************************************************************************
 *                              page_header.php
 *                            -------------------
 *   begin                : Saturday, Feb 13, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: page_header.php,v 1.9 2005/01/04 15:04:15 saraj Exp $
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
$modifiedphp_root='index.php?module=MessageBoard&action=';
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

define('HEADER_INC', TRUE);
//
// gzip_compression
//
#$REF_UR=getenv ("HTTP_REFERER");
#message_die(GENERAL_MESSAGE, $REF_UR);
$do_gzip_compress = FALSE;
if ( $board_config['gzip_compress'] )
{
	$phpver = phpversion();

	$useragent = (isset($_SERVER["HTTP_USER_AGENT"]) ) ? $_SERVER["HTTP_USER_AGENT"] : $HTTP_USER_AGENT;

	if ( $phpver >= '4.0.4pl1' && ( strstr($useragent,'compatible') || strstr($useragent,'Gecko') ) )
	{
		if ( extension_loaded('zlib') )
		{
			ob_start('ob_gzhandler');
		}
	}
	else if ( $phpver > '4.0' )
	{
		if ( strstr($HTTP_SERVER_VARS['HTTP_ACCEPT_ENCODING'], 'gzip') )
		{
			if ( extension_loaded('zlib') )
			{
				$do_gzip_compress = TRUE;
				ob_start();
				ob_implicit_flush(0);

				header('Content-Encoding: gzip');
			}
		}
	}
}

//
// MOD - TODAY AT - BEGIN
// PARSE DATEFORMAT TO GET TIME FORMAT
//
$time_reg = '([gh][[:punct:][:space:]]{1,2}[i][[:punct:][:space:]]{0,2}[a]?[[:punct:][:space:]]{0,2}[S]?)';
eregi($time_reg, $board_config['default_dateformat'], $regs);
$board_config['default_timeformat'] = $regs[1];
unset($time_reg);
unset($regs);

//
// GET THE TIME TODAY AND YESTERDAY
//
$today_ary = explode('|', create_date('m|d|Y', time(),$board_config['board_timezone']));
$board_config['time_today'] = gmmktime(0 - $board_config['board_timezone'] - $board_config['dstime'],0,0,$today_ary[0],$today_ary[1],$today_ary[2]);
$board_config['time_yesterday'] = $board_config['time_today'] - 86400;
unset($today_ary);
// MOD - TODAY AT - END


//
// Parse and show the overall header.
//
//include($phpbb_root_path . 'includes/functions_rate.'.$phpEx); //added by isaac for rating an article

if ( !defined('IN_WEBLOGS') ) // added for weblog by isaac
{
	$template->set_filenames(array(
	'overall_header' => ( empty($gen_simple_header) ) ? 'overall_header.tpl' : (($gen_simple_header < 0) ? 'slideshow_header.tpl' : 'simple_header.tpl') )
);
}
else                          //added for blog_header by isaac
{
	$template->set_filenames(array(
	'overall_header' => ( empty($gen_simple_header) ) ? 'blog_header.tpl' : (($gen_simple_header < 0) ? 'slideshow_header.tpl' : 'simple_header.tpl') )
);
}

//
// Generate logged in/logged out status
//
if ( $userdata['session_logged_in'] )
{
	$u_login_logout = $modifiedphp_root . 'login'.'&logout=true&amp;sid=' . $userdata['session_id'];
	$l_login_logout = $lang['Logout'] . ' [ ' . $userdata['username'] . ' ]';
}
else
{
	$smart_redirect = strrchr($HTTP_SERVER_VARS['PHP_SELF'], '/');
	$smart_redirect = substr($smart_redirect, 1, strlen($smart_redirect));

	if( ($smart_redirect == ($modifiedphp_root . 'profile')) or ($smart_redirect == ($modifiedphp_root . 'login')) )
	{
		$smart_redirect = '';
	}

	if( isset($HTTP_GET_VARS) and !empty($smart_redirect) )
	{
		$smart_get_keys = array_keys($HTTP_GET_VARS);

		for ($i = 0; $i < count($HTTP_GET_VARS); $i++)
		{
			if ($smart_get_keys[$i] != 'sid')
			{
				$smart_redirect .= '&amp;' . $smart_get_keys[$i] . '=' . $HTTP_GET_VARS[$smart_get_keys[$i]];
			}
		}
	}

	$u_login_logout = 'login';
	$u_login_logout .= (!empty($smart_redirect)) ? '?redirect=' . $smart_redirect : '';
	$l_login_logout = $lang['Login'];
}

$s_last_visit = ( $userdata['session_logged_in'] ) ? create_date($board_config['default_dateformat'], $userdata['user_lastvisit'], $board_config['board_timezone']) : '';

//added by isaac for hiding login in overall_header

$curr=$HTTP_SERVER_VARS['SCRIPT_NAME'];

if ((stristr($curr,'login')) || ($userdata['session_logged_in']))
{
 $LOGIN_SCREEN_START="<!--";
 $LOGIN_SCREEN_END="-->";
}
else
{
	$LOGIN_SCREEN_START="";
    $LOGIN_SCREEN_END="";
}

//message_die(GENERAL_MESSAGE,$LOGIN_SCREEN_START);

//
// Get basic (usernames + totals) online
// situation
//
$logged_visible_online = 0;
$logged_hidden_online = 0;
$guests_online = 0;
$online_userlist = '';

if (defined('SHOW_ONLINE'))
{

	$user_forum_sql = ( !empty($forum_id) ) ? "AND s.session_page = " . intval($forum_id) : '';
	$sql = "SELECT u.username, u.user_id, u.user_allow_viewonline, u.user_level, u.user_weblog, s.session_logged_in, s.session_ip
		FROM ".USERS_TABLE." u, ".SESSIONS_TABLE." s
		WHERE u.user_id = s.session_user_id
			AND s.session_time >= ".( time() - 300 ) . "
			$user_forum_sql
		ORDER BY u.username ASC, s.session_ip ASC";
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain user/online information', '', __LINE__, __FILE__, $sql);
	}

	$userlist_ary = array();
	$userlist_visible = array();

	$prev_user_id = 0;
	$prev_user_ip = '';

	while( $row = $db->sql_fetchrow($result) )
	{
		// User is logged in and therefor not a guest
		if ( $row['session_logged_in'] )
		{
			// Skip multiple sessions for one user
			if ( $row['user_id'] != $prev_user_id )
			{
				$style_color = '';
				if ( $row['user_level'] == ADMIN )
				{
					$row['username'] = '<b>' . $row['username'] . '</b>';
					$style_color = 'style="color:#' . $theme['fontcolor3'] . '"';
				}
				else if ( $row['user_weblog'] != 0 )
				{
					$row['username'] = '<b>' . $row['username'] . '</b>';
					$style_color = 'style="color:#' . $theme['fontcolor1'] . '"';
				}
				else if ( $row['user_level'] == MOD )
				{
					$row['username'] = '<b>' . $row['username'] . '</b>';
					$style_color = 'style="color:#' . $theme['fontcolor2'] . '"';
				}

				if ( $row['user_allow_viewonline'] )
				{
					$user_online_link = '<a href="' . append_sid($modifiedphp_root . "profile&mode=viewprofile&amp;" . POST_USERS_URL . "=" . $row['user_id']) . '"' . $style_color .'>' . $row['username'] . '</a>';
					$logged_visible_online++;
				}
				else
				{
					$user_online_link = '<a href="' . append_sid($modifiedphp_root . "profile&mode=viewprofile&amp;" . POST_USERS_URL . "=" . $row['user_id']) . '"' . $style_color .'><i>' . $row['username'] . '</i></a>';
					$logged_hidden_online++;
				}

				if ( $row['user_allow_viewonline'] || $userdata['user_level'] == ADMIN )
				{
					$online_userlist .= ( $online_userlist != '' ) ? ', ' . $user_online_link : $user_online_link;
				}
			}

			$prev_user_id = $row['user_id'];
		}
		else
		{
			// Skip multiple sessions for one user
			if ( $row['session_ip'] != $prev_session_ip )
			{
				$guests_online++;
			}
		}

		$prev_session_ip = $row['session_ip'];
	}
	$db->sql_freeresult($result);

	if ( empty($online_userlist) )
	{
		$online_userlist = $lang['None'];
	}
	$online_userlist = ( ( isset($forum_id) ) ? $lang['Browsing_forum'] : $lang['Registered_users'] ) . ' ' . $online_userlist;

	$total_online_users = $logged_visible_online + $logged_hidden_online + $guests_online;

	if ( $total_online_users > $board_config['record_online_users'])
	{
		$board_config['record_online_users'] = $total_online_users;
		$board_config['record_online_date'] = time();

		$sql = "UPDATE " . CONFIG_TABLE . "
			SET config_value = '$total_online_users'
			WHERE config_name = 'record_online_users'";
		if ( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not update online user record (nr of users)', '', __LINE__, __FILE__, $sql);
		}

		$sql = "UPDATE " . CONFIG_TABLE . "
			SET config_value = '" . $board_config['record_online_date'] . "'
			WHERE config_name = 'record_online_date'";
		if ( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not update online user record (date)', '', __LINE__, __FILE__, $sql);
		}
	}

	if ( $total_online_users == 0 )
	{
		$l_t_user_s = $lang['Online_users_zero_total'];
	}
	else if ( $total_online_users == 1 )
	{
		$l_t_user_s = $lang['Online_user_total'];
	}
	else
	{
		$l_t_user_s = $lang['Online_users_total'];
	}

	if ( $logged_visible_online == 0 )
	{
		$l_r_user_s = $lang['Reg_users_zero_total'];
	}
	else if ( $logged_visible_online == 1 )
	{
		$l_r_user_s = $lang['Reg_user_total'];
	}
	else
	{
		$l_r_user_s = $lang['Reg_users_total'];
	}

	if ( $logged_hidden_online == 0 )
	{
		$l_h_user_s = $lang['Hidden_users_zero_total'];
	}
	else if ( $logged_hidden_online == 1 )
	{
		$l_h_user_s = $lang['Hidden_user_total'];
	}
	else
	{
		$l_h_user_s = $lang['Hidden_users_total'];
	}

	if ( $guests_online == 0 )
	{
		$l_g_user_s = $lang['Guest_users_zero_total'];
	}
	else if ( $guests_online == 1 )
	{
		$l_g_user_s = $lang['Guest_user_total'];
	}
	else
	{
		$l_g_user_s = $lang['Guest_users_total'];
	}

	$l_online_users = sprintf($l_t_user_s, $total_online_users);
	$l_online_users .= sprintf($l_r_user_s, $logged_visible_online);
	$l_online_users .= sprintf($l_h_user_s, $logged_hidden_online);
	$l_online_users .= sprintf($l_g_user_s, $guests_online);
}

//
// Obtain number of new private messages
// if user is logged in
//
if ( ($userdata['session_logged_in']) && (empty($gen_simple_header)) )
{
	if ( $userdata['user_new_privmsg'] )
	{
		$l_message_new = ( $userdata['user_new_privmsg'] == 1 ) ? $lang['New_pm'] : $lang['New_pms'];
		$l_privmsgs_text = sprintf($l_message_new, $userdata['user_new_privmsg']);

		if ( $userdata['user_last_privmsg'] > $userdata['user_lastvisit'] )
		{
			$sql = "UPDATE " . USERS_TABLE . "
				SET user_last_privmsg = " . $userdata['user_lastvisit'] . "
				WHERE user_id = " . $userdata['user_id'];
			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not update private message new/read time for user', '', __LINE__, __FILE__, $sql);
			}

			$s_privmsg_new = 1;
			$icon_pm = $images['pm_new_msg'];
		}
		else
		{
			$s_privmsg_new = 0;
			$icon_pm = $images['pm_new_msg'];
		}
	}
	else
	{
		$l_privmsgs_text = $lang['No_new_pm'];

		$s_privmsg_new = 0;
		$icon_pm = $images['pm_no_new_msg'];
	}

	if ( $userdata['user_unread_privmsg'] )
	{
		$l_message_unread = ( $userdata['user_unread_privmsg'] == 1 ) ? $lang['Unread_pm'] : $lang['Unread_pms'];
		$l_privmsgs_text_unread = sprintf($l_message_unread, $userdata['user_unread_privmsg']);
	}
	else
	{
		$l_privmsgs_text_unread = $lang['No_unread_pm'];
	}
}
else
{
	$icon_pm = $images['pm_no_new_msg'];
	$l_privmsgs_text = $lang['Login_check_pm'];
	$l_privmsgs_text_unread = '';
	$s_privmsg_new = 0;
}

//
// Generate HTML required for Mozilla Navigation bar
//
if (!isset($nav_links))
{
	$nav_links = array();
}

$nav_links_html = '';
$nav_link_proto = '<link rel="%s" href="%s" title="%s" />' . "\n";
while( list($nav_item, $nav_array) = @each($nav_links) )
{
	if ( !empty($nav_array['url']) )
	{
		$nav_links_html .= sprintf($nav_link_proto, $nav_item, append_sid($nav_array['url']), $nav_array['title']);
	}
	else
	{
		// We have a nested array, used for items like <link rel='chapter'> that can occur more than once.
		while( list(,$nested_array) = each($nav_array) )
		{
			$nav_links_html .= sprintf($nav_link_proto, $nav_item, $nested_array['url'], $nested_array['title']);
		}
	}
}

// Format Timezone. We are unable to use array_pop here, because of PHP3 compatibility
$l_timezone = explode('.', $board_config['board_timezone']);
$l_timezone = (count($l_timezone) > 1 && $l_timezone[count($l_timezone)-1] != 0) ? $lang[sprintf('%.1f', $board_config['board_timezone'])] : $lang[number_format($board_config['board_timezone'])];

// include the weblog lang file
$use_lang = ( !file_exists($modifiedphp_root . 'language/lang_' . $board_config['default_lang'] . '/lang_weblog_main.'.$phpEx) ) ? 'english' : $board_config['default_lang'];
include_once($phpbb_root_path . 'language/lang_' . $use_lang . '/lang_weblog_main.' . $phpEx);

// Add to search time mod
$Select_time='<select name="search_time" size="1" onchange="SetLastVisit();" class="gensmall">
	<option value="1" SELECTED>&nbsp;'.$lang['Select_time'].'&nbsp;</option>
	<option value="15">&nbsp;'.$lang['15_min'].'</option>
	<option value="30">&nbsp;'.$lang['30_min'].'</option>
	<option value="45">&nbsp;'.$lang['45_min'].'</option>
	<option value="60">&nbsp;'.$lang['1_Hour'].'</option>
	<option value="120">&nbsp;'.$lang['2_Hour'].'</option>
	<option value="180">&nbsp;'.$lang['3_Hour'].'</option>
	<option value="360">&nbsp;'.$lang['6_Hour'].'</option>
	<option value="540">&nbsp;'.$lang['9_Hour'].'</option>
	<option value="720">&nbsp;'.$lang['12_Hour'].'</option>
	<option value="1440">&nbsp;'.$lang['1_Day'].'</option>
	<option value="2880">&nbsp;'.$lang['2_Days'].'</option>
	<option value="4320">&nbsp;'.$lang['3_Days'].'</option>
	<option value="10080">&nbsp;'.$lang['7_Days'].'</option>
	<option value="20160">&nbsp;'.$lang['2_Weeks'].'</option>
	<option value="43200">&nbsp;'.$lang['1_Month'].'</option>
	<option value="129600">&nbsp;'.$lang['3_Months'].'</option>
	<option value="259200">&nbsp;'.$lang['6_Months'].'</option>
	<option value="518400">&nbsp;'.$lang['1_Year'].'</option>
  	</select>
	<input type="hidden" name="module" value="MessageBoard">
	<input type="hidden" name="action" value="search">
	<input type="hidden" name="return_module"="MessageBoard">
	<input type="hidden" name="return_action"="search">
	<input type="hidden" name="search_author" value="*">
	<input type="hidden" name="show_results" value="topics">';

	$filename = basename ($HTTP_SERVER_VARS['SCRIPT_FILENAME']);
	$forward_to = $HTTP_SERVER_VARS['QUERY_STRING'];

	if( preg_match("/^redirect=([a-z0-9\.#\/\?&=\+\-_]+)/si", $forward_to, $forward_matches) )
	{
		$forward_to = ( !empty($forward_matches[3]) ) ? $forward_matches[3] : $forward_matches[1];
		$forward_match = explode('&', $forward_to);

		if(count($forward_match) > 1)
		{
			$forward_page = '';

			for($i = 1; $i < count($forward_match); $i++)
			{
				if( !ereg("sid=", $forward_match[$i]) )
				{
					if( $forward_page != '' )
					{
						$forward_page .= '&';
					}
					$forward_page .= $forward_match[$i];
				}
			}
			$forward_page = $forward_match[0] . '?' . $forward_page;
		}
		else
		{
			$forward_page = $forward_match[0];
		}
	}
	if($forward_to != '')
		$forward_page="$filename?$forward_to";
	else
		$forward_page="$filename";

	$f_hidden_fields = '<input type="hidden" name="redirect" value="' . $forward_page . '" />';

function swapImage1($ocolumn,$orig_ocoloumn,$otype)
{
	$uimage = "<img src=\"images/up_blogs.gif\" border=\"0\">";
	$dimage = "<img src=\"images/down_blogs.gif\" border=\"0\">";
	if ($otype == "DESC")
	{
		$image = $dimage;
	}
	else
	{
		$image = $uimage;
	}

	if ($ocolumn == "" && $orig_ocoloumn == "p.post_time")
		return $image;
	if ($ocolumn != $orig_ocoloumn)
	{
		$image = "";
	}
	return $image;
}

// include the weblog lang file
$use_lang = ( !file_exists($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_weblog_main.'.$phpEx) ) ? 'english' : $board_config['default_lang'];
include_once($phpbb_root_path . 'language/lang_' . $use_lang . '/lang_weblog_main.' . $phpEx);

//code by Shankar
$myEx="";
//code by Shankar

//
// The following assigns all _common_ variables that may be used at any point
// in a template.
//
$template->assign_vars(array(
	'TERMS_OF_USE' => $lang['Terms_of_Use'],
    'SITENAME' => $board_config['sitename'],
	'S_HIDDEN_FIELDS' => $f_hidden_fields,
	'SITE_DESCRIPTION' => $board_config['site_desc'],
	'PAGE_TITLE' => $page_title,
	'SELECT_TIME' => $Select_time,
	//.$lang['Or'],
	'LAST_VISIT_DATE' => sprintf($lang['You_last_visit'], $s_last_visit),
	'CURRENT_TIME' => sprintf($lang['Current_time'], create_date($board_config['default_dateformat'], time(), $board_config['board_timezone'])),
	'TOTAL_USERS_ONLINE' => $l_online_users,
	'LOGGED_IN_USER_LIST' => $online_userlist,
	'RECORD_USERS' => sprintf($lang['Record_online_users'], $board_config['record_online_users'], create_date($board_config['default_dateformat'], $board_config['record_online_date'], $board_config['board_timezone'])),
	'PRIVATE_MESSAGE_INFO' => $l_privmsgs_text,
	'PRIVATE_MESSAGE_INFO_UNREAD' => $l_privmsgs_text_unread,
	'PRIVATE_MESSAGE_NEW_FLAG' => $s_privmsg_new,

	'PRIVMSG_IMG' => $icon_pm,

	'L_FEED'=> $lang['Feedback'],
	'L_USERNAME' => $lang['Username'],
	'L_PASSWORD' => $lang['Password'],
	'L_LOGIN_LOGOUT' => $l_login_logout,
	'L_LOGIN' => $lang['Login'],
	'L_LOG_ME_IN' => $lang['Log_me_in'],
	'L_AUTO_LOGIN' => $lang['Log_me_in'],
	'L_INDEX' => sprintf($lang['Forum_Index'], $board_config['sitename']),
	'L_BLOGINDEX' => sprintf($lang['Blog_Index'], $board_config['sitename']),
	'L_REGISTER' => $lang['Register'],
	'L_PROFILE' => $lang['Profile'],
	'L_SEARCH' => $lang['Search'],
	'L_BOOKMARKS' => $lang['Bookmarks'],
	'L_PRIVATEMSGS' => $lang['Private_Messages'],
	'L_WHO_IS_ONLINE' => $lang['Who_is_Online'],
	'L_MEMBERLIST' => $lang['Memberlist'],
	'L_FAQ' => $lang['FAQ'],
	'L_USERGROUPS' => $lang['Usergroups'],
	'L_SEARCH_NEW' => $lang['Search_new'],
	'L_SEARCH_UNANSWERED' => $lang['Search_unanswered'],
	'L_SEARCH_SELF' => $lang['Search_your_posts'],
	'L_WHOSONLINE_ADMIN' => sprintf($lang['Admin_online_color'], '<span style="color:#' . $theme['fontcolor3'] . '">', '</span>'),
	'L_WHOSONLINE_MOD' => sprintf($lang['Mod_online_color'], '<span style="color:#' . $theme['fontcolor2'] . '">', '</span>'),
	// Webblog added by isaac
	'L_WHOSONLINE_WEBLOGGER' => sprintf($lang['Weblogger_online_color'], '<span style="color:#' . $theme['fontcolor1'] . '">', '</span>'),
	'L_WEBLOGS' => $lang['Weblog_Weblogs'],
	'L_WEBLOG_CONFIG' => $lang['Weblog_CP'],
	'L_MYWEBLOG' => $lang['Weblog_MyWeblog'],
	'L_ALLTOPICS' => $lang['Weblog_AllTopics'],
	'L_BLOGGERLIST' => $lang['Blog_list'],
        
	'U_WEBLOGS' => append_sid($modifiedphp_root . "weblogs".$myEx),
	'U_ALLTOPICS' => append_sid($modifiedphp_root . "weblog_alltopics".$myEx),
	'U_MYWEBLOG' => append_sid($modifiedphp_root . "weblog.$myEx?w=".$userdata['user_weblog']),
	'U_WEBLOG_CONFIG' => append_sid($modifiedphp_root . "weblog_config".$myEx),
	'WEBLOG_CATEG' => $catname,    //added by rajkumar
	'U_BLOGGERLIST' => append_sid($modifiedphp_root . "weblog_users".$myEx),    //added by isaac for blogger list
	// end weblog - isaac

	//Added by isaac for sorting in blog index page
	'U_BLOG_TIMEORD' => append_sid($modifiedphp_root . "weblogs"),
	'U_BLOG_OWNERORD'=> append_sid($modifiedphp_root . "weblogs?sorder=username"),
	'U_BLOG_NAMEORD' => append_sid($modifiedphp_root . " weblogs?sorder=forum_name"),
	//'U_BLOG_TSORTIMG' => swapImage($blog_order_column,'p.post_time',$blog_order_type),
	//'U_BLOG_OSORTIMG' => swapImage($blog_order_column,'u.username',$blog_order_type),
	//'U_BLOG_NSORTIMG' => swapImage($blog_order_column,'f.forum_name',$blog_order_type),


	//end by isaac - sorting blog index
	'L_FAV' => $lang['favorites'],
	//
  	// MOD - TOPICS I'VE STARTED - deejay
  	'L_SEARCH_STARTEDTOPICS' => $lang['topics_created'],
  	'U_SEARCH_STARTEDTOPICS' => append_sid($modifiedphp_root . 'search'.$myEx.'?search_id=startedtopics'),
  	// MOD - TOPICS I'VE STARTED - deejay
  	//
	'L_WATCHED_TOPICS' => $lang['Watched_Topics'],

	'U_SEARCH_UNANSWERED' => append_sid($modifiedphp_root . 'search'.$myEx.'&search_id=unanswered'),
	'U_SEARCH_SELF' => append_sid($modifiedphp_root . 'search'.$myEx.'?search_id=egosearch'),
	'U_SEARCH_NEW' => append_sid($modifiedphp_root . 'search'.$myEx.'?search_id=newposts'),
	// 'U_INDEX' => append_sid($modifiedphp_root . 'index'.$myEx),
	'U_INDEX' => append_sid($modifiedphp_root . 'index'),
	'U_BLOGINDEX' => append_sid($modifiedphp_root . 'weblogs'.$myEx),
	'U_FEED' => append_sid($modifiedphp_root . 'form'),
	'U_REGISTER' => append_sid($modifiedphp_root . 'profile' . '&mode=register'),
	'U_PROFILE' => append_sid($modifiedphp_root . 'profile'.$myEx.'&mode=editprofile'),
	'U_PRIVATEMSGS' => append_sid($modifiedphp_root . 'privmsg'.$myEx.'&folder=inbox'),
	'U_PRIVATEMSGS_POPUP' => append_sid($modifiedphp_root . 'privmsg'.$myEx.'&mode=newpm'),
	'U_SEARCH' => append_sid($modifiedphp_root . 'search'.$myEx),
	'U_PORTAL' => append_sid($modifiedphp_root . 'portal_home'.$myEx),
	'U_BOOKMARKS' => append_sid('search.'.$myEx.'?search_id=bookmarks'),
	'U_MEMBERLIST' => append_sid($modifiedphp_root . 'memberlist'),
	'U_MODCP' => append_sid('modcp.'.$myEx),
	'U_FAQ' => append_sid($modifiedphp_root . 'faq'),
	'U_VIEWONLINE' => append_sid('viewonline.'.$myEx),
	'U_LOGIN_LOGOUT' => append_sid($modifiedphp_root . $u_login_logout),
	'U_GROUP_CP' => append_sid($modifiedphp_root .  'groupcp'),
	'U_FAV' => append_sid($modifiedphp_root . 'favorites'),
	'U_WATCHED_TOPICS' => append_sid($modifiedphp_root . 'watched_topics'),


	'S_CONTENT_DIRECTION' => $lang['DIRECTION'],
	'S_CONTENT_ENCODING' => $lang['ENCODING'],
	'S_CONTENT_DIR_LEFT' => $lang['LEFT'],
	'S_CONTENT_DIR_RIGHT' => $lang['RIGHT'],
	'S_TIMEZONE' => sprintf($lang['All_times'], $l_timezone),
	'S_LOGIN_ACTION' => append_sid($modifiedphp_root . 'login'),

	'T_HEAD_STYLESHEET' => $theme['head_stylesheet'],
	'T_BODY_BACKGROUND' => $theme['body_background'],
	'T_BODY_BGCOLOR' => '#'.$theme['body_bgcolor'],
	'T_BODY_TEXT' => '#'.$theme['body_text'],
	'T_BODY_LINK' => '#'.$theme['body_link'],
	'T_BODY_VLINK' => '#'.$theme['body_vlink'],
	'T_BODY_ALINK' => '#'.$theme['body_alink'],
	'T_BODY_HLINK' => '#'.$theme['body_hlink'],
	'T_TR_COLOR1' => '#'.$theme['tr_color1'],
	'T_TR_COLOR2' => '#'.$theme['tr_color2'],
	'T_TR_COLOR3' => '#'.$theme['tr_color3'],
	'T_TR_CLASS1' => $theme['tr_class1'],
	'T_TR_CLASS2' => $theme['tr_class2'],
	'T_TR_CLASS3' => $theme['tr_class3'],
	'T_TH_COLOR1' => '#'.$theme['th_color1'],
	'T_TH_COLOR2' => '#'.$theme['th_color2'],
	'T_TH_COLOR3' => '#'.$theme['th_color3'],
	'T_TH_CLASS1' => $theme['th_class1'],
	'T_TH_CLASS2' => $theme['th_class2'],
	'T_TH_CLASS3' => $theme['th_class3'],
	'T_TD_COLOR1' => '#'.$theme['td_color1'],
	'T_TD_COLOR2' => '#'.$theme['td_color2'],
	'T_TD_COLOR3' => '#'.$theme['td_color3'],
	'T_TD_CLASS1' => $theme['td_class1'],
	'T_TD_CLASS2' => $theme['td_class2'],
	'T_TD_CLASS3' => $theme['td_class3'],
	'T_FONTFACE1' => $theme['fontface1'],
	'T_FONTFACE2' => $theme['fontface2'],
	'T_FONTFACE3' => $theme['fontface3'],
	'T_FONTSIZE1' => $theme['fontsize1'],
	'T_FONTSIZE2' => $theme['fontsize2'],
	'T_FONTSIZE3' => $theme['fontsize3'],
	'T_FONTCOLOR1' => '#'.$theme['fontcolor1'],
	'T_FONTCOLOR2' => '#'.$theme['fontcolor2'],
	'T_FONTCOLOR3' => '#'.$theme['fontcolor3'],
	'T_SPAN_CLASS1' => $theme['span_class1'],
	'T_SPAN_CLASS2' => $theme['span_class2'],
	'T_SPAN_CLASS3' => $theme['span_class3'],
    'LOGIN_SCREEN_START' => $LOGIN_SCREEN_START,
    'LOGIN_SCREEN_END' => $LOGIN_SCREEN_END,
	'NAV_LINKS' => $nav_links_html)
);

//
// Login box?
//
if ( !$userdata['session_logged_in'] )
{
	$template->assign_block_vars('switch_user_logged_out', array());
}
else
{
	$template->assign_block_vars('switch_user_logged_in', array());

	if ( !empty($userdata['user_popup_pm']) )
	{
		$template->assign_block_vars('switch_enable_pm_popup', array());
	}

	//added by isaac for weblog 4.0
	$sql_weblog = "SELECT * FROM " . USER_GROUP_TABLE . " g INNER JOIN " . WEBLOG_GROUPS_TABLE . " w ON w.group_id = g.group_id WHERE g.user_id = " . $userdata['user_id'] . " AND g.user_pending <> 1";

	if ( !($result_weblog = $db->sql_query($sql_weblog)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain weblog group information', '', __LINE__, __FILE__, $sql_weblog);
	}
	//The following lines commented by Isaac
	//if ( $row_weblog = $db->sql_fetchrow($result_weblog) )
	//{
	//	if ( $weblog_config['weblog_create'] == 0 || ( $userdata['user_level'] == ADMIN  && $weblog_config['weblog_create'] == 1 ) )
		if ($userdata['session_logged_in'])     //added by Isaac
		{
			$template->assign_block_vars('switch_weblog_user', array());
		}
	//}
	$db->sql_freeresult($result_weblog);

	if ( $userdata['user_weblog']  )
	{
		$template->assign_block_vars('switch_weblog_created', array());
	}



	//added by Isaac
/*	$sql_weblog = "SELECT * FROM " . USER_GROUP_TABLE . " g INNER JOIN " . WEBLOG_GROUPS_TABLE . " w ON w.group_id = g.group_id WHERE g.user_id = " . $userdata['user_id'] . " AND g.user_pending <> 1";

	if( !($result_weblog = $db->sql_query($sql_weblog)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain weblog group information', '', __LINE__, __FILE__, $sql_weblog);
	}
	if ( $row_weblog = $db->sql_fetchrow($result_weblog) )
	{
		$template->assign_block_vars('switch_weblog_user', array());
	}
	$db->sql_freeresult($result_weblog); */
}

// Add no-cache control for cookies if they are set
//$c_no_cache = (isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_sid']) || isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_data'])) ? 'no-cache="set-cookie", ' : '';

// Work around for "current" Apache 2 + PHP module which seems to not
// cope with private cache control setting
if (!empty($_SERVER['SERVER_SOFTWARE']) && strstr($_SERVER['SERVER_SOFTWARE'], 'Apache/2'))
{
	//header ('Cache-Control: no-cache, pre-check=0, post-check=0');
}
else
{
	//header ('Cache-Control: private, pre-check=0, post-check=0, max-age=0');
}
//header ('Expires: 0');
//header ('Pragma: no-cache');

// Quick Search
// godwin - start - list keywords
if ($search_keywords!=''){
	$lang["Quick_Search"] = $search_keywords;
}
// godwin - end - list keywords

// godwin - start - list keywords - search using keywords

if (defined('IN_WEBLOGS'))
{
	$ACTION_URL=$modifiedphp_root . "weblog_search";
        
}
else
{
	$ACTION_URL=$modifiedphp_root . "search";
        
}
$quick_search = "
<table border=\"0\">

<form name=\"scrh\" action=\"" .$ACTION_URL . "&mode=results\" method=\"post\">
<tr>
<td>
	<input type=\"text\" width=\"500\" name=\"search_keywords\" class=\"post\" value=\"".$lang["Quick_Search"]."\" style=\"border-color: black;\" onFocus=\"if(this.value=='Quick Search') this.value=''\">

</td>

<td><INPUT TYPE=\"submit\" BORDER=\"0\" value=\"".$lang["Quick_Search_Go"]."\" ALT=\"Submit\" class=\"posts\" accesskey=\"s\"></td>
<input type=\"hidden\" name=\"searchuser\" value=\"\">
<input type=\"hidden\" name=\"search_forum\" value=\"-1\">
<input type=\"hidden\" name=\"search_author\" value=\"\">
<input type=\"hidden\" name=\"search_time\" value=\"0\">
<input type=\"hidden\" name=\"search_cat\" value=\"-1\">
<input type=\"hidden\" name=\"sort_by\" value=\"0\">
<input type=\"hidden\" name=\"search_fields\" value=\"all\">
<input type=\"hidden\" name=\"show_results\" value=\"topics\">
<input type=\"hidden\" name=\"return_chars\" value=\"-1\">
</td>
</tr>
</form>
</table>";
// godwin - end - list keywords - search using keywords


/* godwin - start - list keywords - search using keywords & author using radio button
$quick_search = "
<script language=\"Javascript\">
<!--
function search_assign()
{
	if (document.scrh.sel_categ[0].checked)
	{
		document.scrh.search_keywords.value=document.scrh.search_keywords1.value;
	}
else
	{
		document.scrh.search_author.value=document.scrh.search_keywords1.value;
	}
}
//-->
</script>
<table border=\"0\">
<form name=\"scrh\" action=\"./search.php?mode=results\" method=\"post\" onSubmit = \"search_assign()\">
<tr>
<td>
	<input type=\"text\" width=\"500\" name=\"search_keywords1\" class=\"post\" value=\"".$lang["Quick_Search"]."\" style=\"border-color: black;\" onFocus=\"this.value=''\">

</td>

<td><INPUT TYPE=\"submit\" BORDER=\"0\" value=\"".$lang["Quick_Search_Go"]."\" ALT=\"Submit\" class=\"post\" accesskey=\"s\"></td>
<input type=\"hidden\" name=\"searchuser\" value=\"\">
<input type=\"hidden\" name=\"search_forum\" value=\"-1\">
<input type=\"hidden\" name=\"search_author\" value=\"\">
<input type=\"hidden\" name=\"search_keywords\" value=\"\">
<input type=\"hidden\" name=\"search_time\" value=\"0\">
<input type=\"hidden\" name=\"search_cat\" value=\"-1\">
<input type=\"hidden\" name=\"sort_by\" value=\"0\">
<input type=\"hidden\" name=\"search_fields\" value=\"all\">
<input type=\"hidden\" name=\"show_results\" value=\"posts\">
<input type=\"hidden\" name=\"return_chars\" value=\"-1\">
</td>
</tr>
<tr>
<td align=\"left\">
	<span class=\"gensmall\">
		<input type=\"radio\" name=\"sel_categ\" value=\"key1\" checked>Posts&nbsp;&nbsp;
		<input type=\"radio\" name=\"sel_categ\" value=\"key2\">Author
	</span>
</td>
<td>&nbsp;</td>
</tr>

</form>
</table>";*/

$template->assign_vars(array(
	'QUICK_SEARCH' => $quick_search)
);
// Quick Search

//if ( !defined('IN_WEBLOGS') ) // added for weblog by isaac
//{
$template->pparse('overall_header');
//}

?>
