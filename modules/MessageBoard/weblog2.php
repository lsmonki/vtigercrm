<?php
/***************************************************************************
 *                               weblog.php
 *                            -------------------
 *
 *   modified from original viewforum.php by netclectic -
 *      http://www.phpbb.com/phpBB/profile.php?mode=viewprofile&u=6288
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

define('IN_PHPBB', true);
define('IN_WEBLOGS', true);
$phpbb_root_path = "./";
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'includes/weblogs_common.'.$phpEx);

//
// Start initial var setup
//
if( isset($HTTP_GET_VARS[POST_WEBLOG_URL]) || isset($HTTP_POST_VARS[POST_WEBLOG_URL]) )
{
	$weblog_id = ( isset($HTTP_GET_VARS[POST_WEBLOG_URL]) ) ? intval($HTTP_GET_VARS[POST_WEBLOG_URL]) : intval($HTTP_POST_VARS[POST_WEBLOG_URL]);
}
else
{
	$weblog_id = "";
}

$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;

if( isset($HTTP_GET_VARS['mark']) || isset($HTTP_POST_VARS['mark']) )
{
	$mark_read = (isset($HTTP_POST_VARS['mark'])) ? $HTTP_POST_VARS['mark'] : $HTTP_GET_VARS['mark'];
}
else
{
	$mark_read = "";
}
//
// End initial var setup
//

//
// Check if the user has actually sent a forum ID with his/her request
// If not give them a nice error page.
//
if( !empty($weblog_id) )
{
	$sql = "SELECT *
		FROM " . FORUMS_TABLE . "
		WHERE forum_id = $weblog_id";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't obtain forums information.", "", __LINE__, __FILE__, $sql);
	}
}
else
{
	message_die(GENERAL_MESSAGE, 'Forum_not_exist');
}

//
// If the query doesn't return any rows this isn't a valid forum. Inform
// the user.
//
if( !($forum_row = $db->sql_fetchrow($result)) )
{
	message_die(GENERAL_MESSAGE, 'Forum_not_exist');
}

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_WEBLOG2);
init_userprefs($userdata);
//
// End session management
//

// include the weblog lang file
$use_lang = ( !file_exists($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_weblog_main.'.$phpEx) ) ? 'english' : $board_config['default_lang'];
include($phpbb_root_path . 'language/lang_' . $use_lang . '/lang_weblog_main.' . $phpEx);

//
// Start auth check
//
$is_auth = array();
$is_auth = auth(AUTH_ALL, $weblog_id, $userdata, $forum_row);

if ( !$is_auth['auth_read'] )
{
	$message = ( !$is_auth[$forum_row['forum_id']]['auth_view'] ) ? $lang['Weblog_not_exist'] : sprintf($lang['Weblog_auth_read'], $is_auth[$forum_row['forum_id']]['auth_read_type']);

	message_die(GENERAL_MESSAGE, $message);
}

/*
if ( !$is_auth['auth_read'] || !$is_auth['auth_view'] )
{
	if ( !$userdata['session_logged_in'] )
	{
		$redirect = POST_WEBLOG_URL . "=$weblog_id" . ( ( isset($start) ) ? "&start=$start" : '' );
		$header_location = ( @preg_match("/Microsoft|WebSTAR|Xitami/", getenv("SERVER_SOFTWARE")) ) ? "Refresh: 0; URL=" : "Location: ";
		header($header_location . append_sid("login.$phpEx?redirect=weblog.$phpEx&$redirect", true));
		exit;
	}
	//
	// The user is not authed to read this forum ...
	//
	$message = ( !$is_auth['auth_view'] ) ? $lang['Weblog_not_exist'] : sprintf($lang['Weblog_auth_read'], $is_auth['auth_read_type']);

	message_die(GENERAL_MESSAGE, $message);
}
*/
//
// End of auth check
//

//
// Handle marking posts
//
if ( $mark_read == 'topics' )
{
	if ( $userdata['session_logged_in'] )
	{
		$sql = "SELECT MAX(post_time) AS last_post
			FROM " . POSTS_TABLE . "
			WHERE forum_id = $weblog_id";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain forums information', '', __LINE__, __FILE__, $sql);
		}

		if ( $row = $db->sql_fetchrow($result) )
		{
			$tracking_forums = ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f']) ) ? unserialize($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f']) : array();
			$tracking_topics = ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_t']) ) ? unserialize($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_t']) : array();

			if ( ( count($tracking_forums) + count($tracking_topics) ) >= 150 && empty($tracking_forums[$weblog_id]) )
			{
				asort($tracking_forums);
				unset($tracking_forums[key($tracking_forums)]);
			}

			if ( $row['last_post'] > $userdata['user_lastvisit'] )
			{
				$tracking_forums[$weblog_id] = time();

				setcookie($board_config['cookie_name'] . '_f', serialize($tracking_forums), 0, $board_config['cookie_path'], $board_config['cookie_domain'], $board_config['cookie_secure']);
			}
		}

		$template->assign_vars(array(
			'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("weblog.$phpEx?" . POST_WEBLOG_URL . "=$weblog_id") . '">')
		);
	}

	$message = $lang['Entries_marked_read'] . '<br /><br />' . sprintf($lang['Click_return_weblog'], '<a href="' . append_sid("weblog.$phpEx?" . POST_WEBLOG_URL . "=$weblog_id") . '">', '</a> ');
	message_die(GENERAL_MESSAGE, $message);
}
//
// End handle marking posts
//

$tracking_topics = ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . "_t"]) ) ? unserialize($HTTP_COOKIE_VARS[$board_config['cookie_name'] . "_t"]) : "";
$tracking_forums = ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . "_f"]) ) ? unserialize($HTTP_COOKIE_VARS[$board_config['cookie_name'] . "_f"]) : "";

//
// Do the forum Prune
//
if( $is_auth['auth_mod'] && $board_config['prune_enable'] )
{
	if( $forum_row['prune_next'] < time() && $forum_row['prune_enable'] )
	{
		include($phpbb_root_path . 'includes/prune.'.$phpEx);
		require($phpbb_root_path . 'includes/functions_admin.'.$phpEx);
		auto_prune($weblog_id);
	}
}
//
// End of forum prune
//

//
// Obtain list of moderators of each forum
// First users, then groups ... broken into two queries
//
$sql = "SELECT u.user_id, u.username, u.weblog_style
	FROM " . AUTH_ACCESS_TABLE . " aa, " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE . " g, " . USERS_TABLE . " u
	WHERE aa.forum_id = $weblog_id
		AND aa.auth_mod = " . TRUE . "
		AND g.group_single_user = 1
		AND ug.group_id = aa.group_id
		AND g.group_id = aa.group_id
		AND u.user_id = ug.user_id
	GROUP BY u.user_id, u.username
	ORDER BY u.user_id";
if(!$result = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, "Could not query forum moderator information", "", __LINE__, __FILE__, $sql);
}

$moderators = array();
while( $row = $db->sql_fetchrow($result) )
{
	$profiledata = get_userdata($row['user_id']);
	$moderators = $row;
}
$forum_moderators = '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $moderators['user_id']) . '">' . $moderators['username'] . '</a>';
unset($moderators);

# code added by Godwin[begin]
//
// Is user watching this forum?
//
if( $userdata['session_logged_in'] )
{
        ( $forum_row['forum_notify'] == '1' ) ? $can_watch_forum = TRUE : $can_watch_forum = FALSE;

        $sql = "SELECT notify_status
                FROM " . FORUMS_WATCH_TABLE . "
                WHERE forum_id = $weblog_id
                        AND user_id = " . $userdata['user_id'];
        if( !($result = $db->sql_query($sql)) )
        {
                message_die(GENERAL_ERROR, "Couldn't obtain forum watch information", "", __LINE__, __FILE__, $sql);
        }

        if( $row = $db->sql_fetchrow($result) )
        {
                if( isset($HTTP_GET_VARS['unwatch']) )
                {
                        if( $HTTP_GET_VARS['unwatch'] == "forum" )
                        {
                                $is_watching_forum = 0;

                                $sql_priority = (SQL_LAYER == "mysql") ? "LOW_PRIORITY" : "";
                                $sql = "DELETE $sql_priority FROM " . FORUMS_WATCH_TABLE . "
                                        WHERE forum_id = $forum_id
                                                AND user_id = " . $userdata['user_id'];
                                if( !$result = $db->sql_query($sql) )
                                {
                                        message_die(GENERAL_ERROR, "Couldn't delete forum watch information", "", __LINE__, __FILE__, $sql);
                                }
                        }

                        $template->assign_vars(array(
                                "META" => '<meta http-equiv="refresh" content="3;url=' . append_sid("viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id&amp;start=$start") . '">')
                        );
		$message = $lang['No_longer_watching_forum'] . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid("viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id&amp;start=$start") . '">', '</a>');
                        message_die(GENERAL_MESSAGE, $message);
                }
                else
                {
                        $is_watching_forum = TRUE;

                        if( $row['notify_status'] )
                        {
                                $sql_priority = (SQL_LAYER == "mysql") ? "LOW_PRIORITY" : "";
                                $sql = "UPDATE $sql_priority " . FORUMS_WATCH_TABLE . "
                                        SET notify_status = 0
                                        WHERE forum_id = $forum_id
                                                AND user_id = " . $userdata['user_id'];
                                if( !$result = $db->sql_query($sql))
                                {
                                        message_die(GENERAL_ERROR, "Couldn't update forum watch information", "", __LINE__, __FILE__, $sql);
                                }
                        }
                }
        }
        else
        {
                if( isset($HTTP_GET_VARS['watch']) )
                {
                        if( $HTTP_GET_VARS['watch'] == "forum" )
                        {
                                $is_watching_forum = TRUE;

                                $sql_priority = (SQL_LAYER == "mysql") ? "LOW_PRIORITY" : "";
                                $sql = "INSERT $sql_priority INTO " . FORUMS_WATCH_TABLE . " (user_id, forum_id, notify_status)
                                        VALUES (" . $userdata['user_id'] . ", $forum_id, 0)";
                                if( !$result = $db->sql_query($sql) )
                                {
                                        message_die(GENERAL_ERROR, "Couldn't insert forum watch information", "", __LINE__, __FILE__, $sql);
                                }
			}
			$template->assign_vars(array(
				"META" => '<meta http-equiv="refresh" content="3;url=' . append_sid("viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id&amp;start=$start") . '">')
			);

			$message = $lang['You_are_watching_forum'] . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid("viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id&amp;start=$start") . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			$is_watching_forum = 0;
		}
	}
}
else
{
	if( isset($HTTP_GET_VARS['unwatch']) )
	{
		if( $HTTP_GET_VARS['unwatch'] == "forum" )
		{
			header("Location: " . append_sid("login.$phpEx?redirect=viewforum.$phpEx&" . POST_FORUM_URL . "=$forum_id&unwatch=forum", true));
		}
	}
	else
	{
		$can_watch_forum = 0;
		$is_watching_forum = 0;
	}
}                                                                                                                           # code added by Godwin[end]


//
// Generate a 'Show topics in previous x days' select box. If the topicsdays var is sent
// then get it's value, find the number of topics with dates newer than it (to properly
// handle pagination) and alter the main query
//
$previous_days = array(0, 1, 7, 14, 30, 90, 180, 364);
$previous_days_text = array($lang['All_Entries'], $lang['1_Day'], $lang['7_Days'], $lang['2_Weeks'], $lang['1_Month'], $lang['3_Months'], $lang['6_Months'], $lang['1_Year']);

if( !empty($HTTP_POST_VARS['topicdays']) || !empty($HTTP_GET_VARS['topicdays']) )
{
	$topic_days = ( !empty($HTTP_POST_VARS['topicdays']) ) ? intval($HTTP_POST_VARS['topicdays']) : intval($HTTP_GET_VARS['topicdays']);
	$min_topic_time = time() - ($topic_days * 86400);

	$sql = "SELECT COUNT(t.topic_id) AS forum_topics
		FROM " . TOPICS_TABLE . " t, " . POSTS_TABLE . " p
		WHERE t.forum_id = $weblog_id
			AND p.post_id = t.topic_last_post_id
			AND p.post_time >= $min_topic_time";

	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't obtain limited topics count information", "", __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);

	$topics_count = $row['forum_topics'];
	$limit_topics_time = "AND p.post_time >= $min_topic_time";

	if( !empty($HTTP_POST_VARS['topicdays']) )
	{
		$start = 0;
	}
}
else
{
	$topics_count = $forum_row['forum_topics'];

	$limit_topics_time = "";
	$topic_days = 0;
}

$select_topic_days = "<select name=\"topicdays\">";
for($i = 0; $i < count($previous_days); $i++)
{
	$selected = ($topic_days == $previous_days[$i]) ? " selected=\"selected\"" : "";
	$select_topic_days .= "<option value=\"" . $previous_days[$i] . "\"$selected>" . $previous_days_text[$i] . "</option>";
}
$select_topic_days .= "</select>";


//
// All announcement data, this keeps announcements
// on each weblog page ...
//
$sql = "SELECT t.*, u.username, u.user_allow_viewonline, u.user_session_time, u.user_id, u2.username as user2, u2.user_id as id2, p.post_time, p.post_username
	FROM " . TOPICS_TABLE . " t, " . USERS_TABLE . " u, " . POSTS_TABLE . " p, " . USERS_TABLE . " u2
	WHERE t.forum_id IN ($weblog_id, -1)
		AND t.topic_poster = u.user_id
		AND p.post_id = t.topic_last_post_id
		AND p.poster_id = u2.user_id
		AND t.topic_type = " . POST_ANNOUNCE . "
	ORDER BY t.topic_last_post_id DESC ";
if( !$result = $db->sql_query($sql) )
{
   message_die(GENERAL_ERROR, "Couldn't obtain topic information", "", __LINE__, __FILE__, $sql);
}

$topic_rowset = array();
$total_announcements = 0;
while( $row = $db->sql_fetchrow($result) )
{
	$topic_rowset[] = $row;
	$total_announcements++;
}

$db->sql_freeresult($result);

//
// Grab all the basic data (all topics except announcements)
// for this forum
//
$sql = "SELECT t.*, u.username, u.user_id, u2.username as user2, u2.user_id as id2, p.post_username, p2.post_username AS post_username2, p2.post_time
	FROM " . TOPICS_TABLE . " t, " . USERS_TABLE . " u, " . POSTS_TABLE . " p, " . POSTS_TABLE . " p2, " . USERS_TABLE . " u2
	WHERE t.forum_id = $weblog_id
		AND t.topic_poster = u.user_id
		AND p.post_id = t.topic_first_post_id
		AND p2.post_id = t.topic_last_post_id
		AND u2.user_id = p2.poster_id
		AND t.topic_type <> " . POST_ANNOUNCE . "
		$limit_topics_time
	ORDER BY t.topic_type DESC, t.topic_time DESC
	LIMIT $start, ".$board_config['topics_per_page'];
if( !$result = $db->sql_query($sql) )
{
   message_die(GENERAL_ERROR, "Couldn't obtain topic information", "", __LINE__, __FILE__, $sql);
}

$total_topics = 0;
while( $row = $db->sql_fetchrow($result) )
{
	$topic_rowset[] = $row;
	$total_topics++;
}

$db->sql_freeresult($result);

//
// Total topics ...
//
$total_topics += $total_announcements;

//
// Define censored word matches
//
$orig_word = array();
$replacement_word = array();
obtain_word_list($orig_word, $replacement_word);

//
// Post URL generation for templating vars
//
$template->assign_vars(array(
	"L_DISPLAY_TOPICS" => 'Display entries from previous',

	"U_POST_NEW_TOPIC" => append_sid("weblog_posting.$phpEx?mode=newtopic&amp;" . POST_WEBLOG_URL . "=$weblog_id"),

	"S_SELECT_TOPIC_DAYS" => $select_topic_days,
	"S_POST_DAYS_ACTION" => append_sid("weblog.$phpEx?" . POST_WEBLOG_URL . "=" . $weblog_id . "&amp;start=$start"))
);

//
// User authorisation levels output
//
$s_auth_can = ( ( $is_auth['auth_post'] ) ? $lang['Weblog_post_can'] : $lang['Weblog_post_cannot'] ) . "<br />";
$s_auth_can .= ( ( $is_auth['auth_reply'] ) ? $lang['Weblog_reply_can'] : $lang['Weblog_reply_cannot'] ) . "<br />";
$s_auth_can .= ( ( $is_auth['auth_edit'] ) ? $lang['Weblog_edit_can'] : $lang['Weblog_edit_cannot'] ) . "<br />";
$s_auth_can .= ( ( $is_auth['auth_delete'] ) ? $lang['Weblog_delete_can'] : $lang['Weblog_delete_cannot'] ) . "<br />";
$s_auth_can .= ( ( $is_auth['auth_vote'] ) ? $lang['Weblog_vote_can'] : $lang['Weblog_vote_cannot'] ) . "<br />";

if ( defined('ATTACH_VERSION') )
{
	attach_build_auth_levels($is_auth, $s_auth_can);
}

//
// Mozilla navigation bar
//
$nav_links['up'] = array(
	'url' => append_sid("weblogs.".$phpEx),
	'title' => sprintf($lang['Forum_Index'], $board_config['sitename'])
);

//
// Dump out the page header and load weblog template
//
define('IN_WEBLOGS', true);
$page_title = $lang['View_All_Entries'] . " - " . $forum_row['forum_name'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'overall_header' => '../../weblogs/styles/' . $profiledata['weblog_style'] . '/weblog_header.cfg',
	'body' => '../../weblogs/styles/' . $profiledata['weblog_style'] . '/weblog_body2.cfg',
	'overall_footer' => '../../weblogs/styles/' . $profiledata['weblog_style'] . '/weblog_footer.cfg')
);

make_jumpbox('weblog.'.$phpEx);

$template->assign_vars(array(
	'FORUM_ID' => $weblog_id,
	'FORUM_NAME' => $forum_row['forum_name'],
	'MODERATORS' => $forum_moderators,
	'POST_IMG' => ( $forum_row['forum_status'] == FORUM_LOCKED ) ? 'weblogs/styles/' . $profiledata['weblog_style'] . '/reply_locked.gif' : 'weblogs/styles/' . $profiledata['weblog_style'] . '/entry.gif',

	'FOLDER_IMG' => 'weblogs/styles/' . $profiledata['weblog_style'] . '/folder.gif',
	'FOLDER_NEW_IMG' => 'weblogs/styles/' . $profiledata['weblog_style'] . '/folder_new.gif',
	'FOLDER_HOT_IMG' => 'weblogs/styles/' . $profiledata['weblog_style'] . '/folder_hot.gif',
	'FOLDER_HOT_NEW_IMG' => 'weblogs/styles/' . $profiledata['weblog_style'] . '/folder_new_hot.gif',
	'FOLDER_LOCKED_IMG' => 'weblogs/styles/' . $profiledata['weblog_style'] . '/folder_lock.gif',
	'FOLDER_LOCKED_NEW_IMG' => 'weblogs/styles/' . $profiledata['weblog_style'] . '/folder_lock_new.gif',
	'FOLDER_STICKY_IMG' => 'weblogs/styles/' . $profiledata['weblog_style'] . '/sticky.gif',
	'FOLDER_STICKY_NEW_IMG' => 'weblogs/styles/' . $profiledata['weblog_style'] . '/sticky_new.gif',
	'FOLDER_ANNOUNCE_IMG' => $images['folder_announce'],
	'FOLDER_ANNOUNCE_NEW_IMG' => $images['folder_announce_new'],

	'L_TOPICS' => $lang['Entries'],
	'L_REPLIES' => $lang['Replies'],
	'L_VIEWS' => $lang['Views'],
	'L_POSTS' => $lang['Posts'],
	'L_LASTPOST' => $lang['Last_Post'],
	'L_MODERATOR' => $lang['Weblog_owner'],
	'L_MARK_TOPICS_READ' => $lang['Mark_all_entries'],
	'L_POST_NEW_TOPIC' => ( $forum_row['forum_status'] == FORUM_LOCKED ) ? $lang['Weblog_locked'] : $lang['Post_new_entry'],
	'L_NO_NEW_POSTS' => $lang['No_new_posts'],
	'L_NEW_POSTS' => $lang['New_posts'],
	'L_NO_NEW_POSTS_LOCKED' => $lang['No_new_posts_locked'],
	'L_NEW_POSTS_LOCKED' => $lang['New_posts_locked'],
	'L_NO_NEW_POSTS_HOT' => $lang['No_new_posts_hot'],
	'L_NEW_POSTS_HOT' => $lang['New_posts_hot'],
	'L_ANNOUNCEMENT' => $lang['Post_Announcement'],
	'L_STICKY' => $lang['Post_Sticky'],
	'L_POSTED' => $lang['Posted'],
	'L_JOINED' => $lang['Joined'],
	'L_AUTHOR' => $lang['Author'],
	'L_WEBLOGS' => $lang['Weblogs'],

	'MOD_VERSION' => $mod_version,

	'S_AUTH_LIST' => $s_auth_can,

	'U_VIEW_FORUM' => append_sid("weblog.$phpEx?" . POST_WEBLOG_URL ."=$weblog_id"),

	'U_MARK_READ' => append_sid("weblog.$phpEx?" . POST_WEBLOG_URL . "=$weblog_id&amp;mark=topics"))
);
//
// End header
//

//
// Okay, lets dump out the page ...
//
if( $total_topics )
{
	for($i = 0; $i < $total_topics; $i++)
	{
		$entry_id = $topic_rowset[$i]['topic_id'];

		$topic_title = ( count($orig_word) ) ? preg_replace($orig_word, $replacement_word, $topic_rowset[$i]['topic_title']) : $topic_rowset[$i]['topic_title'];
		$topic_description = ( count($orig_word) ) ? preg_replace($orig_word, $replacement_word, $topic_rowset[$i]['topic_description']) : $topic_rowset[$i]['topic_description'];

		if ($topic_description)
		{
			$topic_description .= '<br />';
		}
		$replies = $topic_rowset[$i]['topic_replies'];

		$topic_type = $topic_rowset[$i]['topic_type'];

		$mood = $topic_rowset[$i]['topic_mood'] - 1;

		if ( $mood_data[$mood]['mood'] != '(none)' && $mood_data[$mood]['mood'] != '' && $mood > 0 )
		{
			$icon = '<img src="images/weblogs/' . $mood_data[$mood]['image_url'] . '" alt="' . $mood_data[$mood]['mood'] . '" border="0">';
		}
		else
		{
			$icon = '';
		}

		$currently1 = $topic_rowset[$i]['topic_currently1'] - 1;

		if ( $currently_data[$currently1]['currently'] != '(none)' && $currently_data[$currently1]['currently'] != '(custom)' && $currently1 > 0 )
		{
			$icon .= '<img src="images/weblogs/' . $currently_data[$currently1]['image_url'] . '" alt="' . $currently_data[$currently1]['currently'] . '" border="0">';
		}
		else
		{
			$icon .= '';
		}

		if( $topic_type == POST_ANNOUNCE )
		{
			$topic_type = $lang['Topic_Announcement'] . ' ';
		}
		else if( $topic_type == POST_STICKY )
		{
			$topic_type = $lang['Weblog_memorable'] . ' ';
		}
		else
		{
			$topic_type = '';
		}

		if( $topic_rowset[$i]['topic_vote'] )
		{
			$topic_type .= $lang['Topic_Poll'] . ' ';
		}
		
// Adding hidden label to hidden post - Godwin -->
                
		if( $topic_rowset[$i]['topic_auth'] == 3 )
		{
 	        	$topic_type .= $lang['Post_Hidden'] . ' ';
 	      	}
 	 
// Godwin <--

		if( $topic_rowset[$i]['topic_status'] == TOPIC_MOVED )
		{
			$topic_type = $lang['Topic_Moved'] . ' ';
			$entry_id = $topic_rowset[$i]['topic_moved_id'];

			$folder_image =  'weblogs/styles/' . $profiledata['weblog_style'] . '/folder.gif';
			$folder_alt = $lang['Topics_Moved'];
			$newest_post_img = '';
		}
		else
		{
			if( $topic_rowset[$i]['topic_type'] == POST_ANNOUNCE )
			{
				$folder = $images['folder_announce'];
				$folder_new = $images['folder_announce_new'];
			}
			else if( $topic_rowset[$i]['topic_type'] == POST_STICKY )
			{
				$folder = 'weblogs/styles/' . $profiledata['weblog_style'] . '/folder_sticky.gif';
				$folder_new = 'weblogs/styles/' . $profiledata['weblog_style'] . '/folder_sticky_new.gif';
			}
			else if( $topic_rowset[$i]['topic_status'] == TOPIC_LOCKED )
			{
				$folder = 'weblogs/styles/' . $profiledata['weblog_style'] . '/folder_lock.gif';
				$folder_new = 'weblogs/styles/' . $profiledata['weblog_style'] . '/folder_lock_new.gif';
			}
			else
			{
				if($replies >= $board_config['hot_threshold'])
				{
					$folder = 'weblogs/styles/' . $profiledata['weblog_style'] . '/folder_hot.gif';
					$folder_new = 'weblogs/styles/' . $profiledata['weblog_style'] . '/folder_hot_new.gif';
				}
				else
				{
					$folder = 'weblogs/styles/' . $profiledata['weblog_style'] . '/folder.gif';
					$folder_new = 'weblogs/styles/' . $profiledata['weblog_style'] . '/folder_new.gif';
				}
			}

			$newest_post_img = '';
			if( $userdata['session_logged_in'] )
			{
				if( $topic_rowset[$i]['post_time'] > $userdata['user_lastvisit'] )
				{
					if( !empty($tracking_topics) || !empty($tracking_forums) || isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f_all']) )
					{
						$unread_topics = true;

						if( !empty($tracking_topics[$entry_id]) )
						{
							if( $tracking_topics[$entry_id] >= $topic_rowset[$i]['post_time'] )
							{
								$unread_topics = false;
							}
						}

						if( !empty($tracking_forums[$weblog_id]) )
						{
							if( $tracking_forums[$weblog_id] >= $topic_rowset[$i]['post_time'] )
							{
								$unread_topics = false;
							}
						}

						if( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f_all']) )
						{
							if( $HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f_all'] >= $topic_rowset[$i]['post_time'] )
							{
								$unread_topics = false;
							}
						}

						if( $unread_topics )
						{
							$folder_image = $folder_new;
							$folder_alt = $lang['New_posts'];

							$newest_post_img = '<a href="' . append_sid("weblog_entry.$phpEx?"  . POST_POST_URL . '=' . $topic_rowset[$i]['topic_last_post_id']) . '#' . $topic_rowset[$i]['topic_last_post_id'] . '"><img src="' . $images['icon_newest_reply'] . '" alt="' . $lang['View_newest_post'] . '" title="' . $lang['View_newest_post'] . '" border="0" /></a> ';
						}
						else
						{
							$folder_image = $folder;
							$folder_alt = ( $topic_rowset[$i]['topic_status'] == TOPIC_LOCKED ) ? $lang['Entry_locked'] : $lang['No_new_posts'];

							$newest_post_img = '';
						}
					}
					else
					{
						$folder_image = $folder_new;
						$folder_alt = ( $topic_rowset[$i]['topic_status'] == TOPIC_LOCKED ) ? 'This topic is locked: you cannot edit posts or make replies.' : $lang['No_new_posts'];

						$newest_post_img = '<a href="' . append_sid("weblog_entry.$phpEx?"  . POST_POST_URL . '=' . $topic_rowset[$i]['topic_last_post_id']) . '#' . $topic_rowset[$i]['topic_last_post_id'] . '"><img src="' . $images['icon_newest_reply'] . '" alt="' . $lang['View_newest_post'] . '" title="' . $lang['View_newest_post'] . '" border="0" /></a> ';
					}
				}
				else
				{
					$folder_image = $folder;
					$folder_alt = ( $topic_rowset[$i]['topic_status'] == TOPIC_LOCKED ) ? $lang['Entry_locked'] : $lang['No_new_posts'];

					$newest_post_img = '';
				}
			}
			else
			{
				$folder_image = $folder;
				$folder_alt = ( $topic_rowset[$i]['topic_status'] == TOPIC_LOCKED ) ? $lang['Entry_locked'] : $lang['No_new_posts'];

				$newest_post_img = '';
			}
		}

		if( ( $replies + 1 ) > $board_config['posts_per_page'] )
		{
			$total_pages = ceil( ( $replies + 1 ) / $board_config['posts_per_page'] );
			$goto_page = ' [ <img src="' . $images['icon_gotopost'] . '" alt="' . $lang['Goto_page'] . '" title="' . $lang['Goto_page'] . '" />' . $lang['Goto_page'] . ': ';

			$times = 1;
			for($j = 0; $j < $replies + 1; $j += $board_config['posts_per_page'])
			{
				$goto_page .= '<a href="' . append_sid("weblog_entry.$phpEx?" . POST_ENTRY_URL . "=" . $entry_id . "&amp;start=$j") . '">' . $times . '</a>';
				if( $times == 1 && $total_pages > 4 )
				{
					$goto_page .= ' ... ';
					$times = $total_pages - 3;
					$j += ( $total_pages - 4 ) * $board_config['posts_per_page'];
				}
				else if ( $times < $total_pages )
				{
					$goto_page .= ', ';
				}
				$times++;
			}
			$goto_page .= ' ] ';
		}
		else
		{
			$goto_page = '';
		}

		$view_topic_url = append_sid("weblog_entry.$phpEx?" . POST_ENTRY_URL . "=$entry_id");

		$topic_author = ( $topic_rowset[$i]['user_id'] != ANONYMOUS ) ? '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . '=' . $topic_rowset[$i]['user_id']) . '">' : '';
		$topic_author .= ( $topic_rowset[$i]['user_id'] != ANONYMOUS ) ? $topic_rowset[$i]['username'] : ( ( $topic_rowset[$i]['post_username'] != '' ) ? $topic_rowset[$i]['post_username'] : $lang['Guest'] );

		$topic_author .= ( $topic_rowset[$i]['user_id'] != ANONYMOUS ) ? '</a>' : '';

		$first_post_time = create_date($board_config['default_dateformat'], $topic_rowset[$i]['topic_time'], $board_config['board_timezone']);

		$last_post_time = create_date($board_config['default_dateformat'], $topic_rowset[$i]['post_time'], $board_config['board_timezone']);

		//
		// MOD TODAY AT BEGIN
		//
		if ( $board_config['time_today'] < $topic_rowset[$i]['post_time'])
		{
			$last_post_time = sprintf($lang['Today_at'], create_date($board_config['default_timeformat'], $topic_rowset[$i]['post_time'], $board_config['board_timezone']));
		}
		else if ( $board_config['time_yesterday'] < $topic_rowset[$i]['post_time'])
		{
			$last_post_time = sprintf($lang['Yesterday_at'], create_date($board_config['default_timeformat'], $topic_rowset[$i]['post_time'], $board_config['board_timezone']));
		}
		// MOD TODAY AT END

		$last_post_author = ( $topic_rowset[$i]['id2'] == ANONYMOUS ) ? ( ($topic_rowset[$i]['post_username2'] != '' ) ? $topic_rowset[$i]['post_username2'] . ' ' : $lang['Guest'] . ' ' ) : '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . '='  . $topic_rowset[$i]['id2']) . '">' . $topic_rowset[$i]['user2'] . '</a>';

		if( $unread_topics )
		{
			$last_post_url = $newest_post_img;
		}
		else
			$last_post_url = '<a href="' . append_sid("weblog_entry.$phpEx?"  . POST_POST_URL . '=' . $topic_rowset[$i]['topic_last_post_id']) . '#' . $topic_rowset[$i]['topic_last_post_id'] . '"><img src="' . $images['icon_latest_reply'] . '" alt="' . $lang['View_latest_post'] . '" title="' . $lang['View_latest_post'] . '" border="0" /></a>';

		$views = $topic_rowset[$i]['topic_views'];

		$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars('topicrow', array(
			'ICON' => $icon,
			'ROW_COLOR' => $row_color,
			'ROW_CLASS' => $row_class,
			'FORUM_ID' => $weblog_id,
			'TOPIC_ID' => $entry_id,
			'TOPIC_FOLDER_IMG' => $folder_image,
			'TOPIC_AUTHOR' => $topic_author,
			'GOTO_PAGE' => $goto_page,
			'REPLIES' => $replies,
			'NEWEST_POST_IMG' => $newest_post_img,
			'TOPIC_TITLE' => $topic_title,
			'TOPIC_DESCRIPTION' => $topic_description,
			'TOPIC_TYPE' => $topic_type,
			'VIEWS' => $views,
			'FIRST_POST_TIME' => $first_post_time,
			'LAST_POST_TIME' => $last_post_time,
			'LAST_POST_AUTHOR' => $last_post_author,
			'LAST_POST_IMG' => $last_post_url,

			'L_TOPIC_FOLDER_ALT' => $folder_alt,

			'U_VIEW_TOPIC' => $view_topic_url,

			'TOPIC_ATTACHMENT_IMG' => ( defined('ATTACH_VERSION') ) ? topic_attachment_image($topic_rowset[$i]['topic_attachment']) : '')
		);
	}

	$template->assign_vars(array(
		'PAGINATION' => generate_pagination("weblog2.$phpEx?" . POST_WEBLOG_URL . "=$weblog_id&amp;topicdays=$topic_days", $topics_count, $board_config['topics_per_page'], $start),
		'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $board_config['topics_per_page'] ) + 1 ), ceil( $topics_count / $board_config['topics_per_page'] )),

		'L_GOTO_PAGE' => $lang['Goto_page'])
	);
}
else
{
	//
	// No topics
	//
	$no_topics_msg = ( $forum_row['forum_status'] == FORUM_LOCKED ) ? $lang['Weblog_locked'] : ( ($userdata['user_weblog'] == $forum_row['forum_id']) ? $lang['No_entries_owner'] : $lang['No_entries_guest']);
	$template->assign_vars(array(
		'L_NO_TOPICS' => $no_topics_msg)
	);

	$template->assign_block_vars('switch_no_topics', array() );

}

//
// Parse the page and print
//
//$template->pparse('overall_header');   //commented by isaac to remove double header in view topics page
$template->pparse('body');
//$template->pparse('overall_footer');     commented by isaac to fix the page_tail.php not being displayed

//
// Page footer
//
include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>
