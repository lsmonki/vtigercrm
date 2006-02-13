<?php
/***************************************************************************
 *                               weblog_entry.php
 *                            -------------------
 *
 *   modified from original viewtopic.php by netclectic -
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
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'includes/bbcode.'.$phpEx);
include($phpbb_root_path . 'includes/weblogs_common.'.$phpEx);

//
// Start initial var setup
//
$entry_id = $post_id = 0;
if ( isset($HTTP_GET_VARS[POST_ENTRY_URL]) )
{
	$entry_id = intval($HTTP_GET_VARS[POST_ENTRY_URL]);
}
else
{
	$entry_id = 0;
}

if ( isset($HTTP_GET_VARS[POST_POST_URL]))
{
	$post_id = intval($HTTP_GET_VARS[POST_POST_URL]);
}


$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;

if ( !isset($entry_id) && !isset($post_id) )
{
	message_die(GENERAL_MESSAGE, 'Entry_post_not_exist');
}

//
// Find topic id if user requested a newer
// or older topic
//
if ( isset($HTTP_GET_VARS['view']) && empty($HTTP_GET_VARS[POST_POST_URL]) )
{
	if ( $HTTP_GET_VARS['view'] == 'newest' )
	{
		if ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_sid']) || isset($HTTP_GET_VARS['sid']) )
		{
			$session_id = isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_sid']) ? $HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_sid'] : $HTTP_GET_VARS['sid'];

			if ( $session_id )
			{
				$sql = "SELECT p.post_id
					FROM " . POSTS_TABLE . " p, " . SESSIONS_TABLE . " s,  " . USERS_TABLE . " u
					WHERE s.session_id = '$session_id'
						AND u.user_id = s.session_user_id
						AND p.topic_id = $entry_id
						AND p.post_time >= u.user_lastvisit
					ORDER BY p.post_time ASC
					LIMIT 1";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not obtain newer/older topic information', '', __LINE__, __FILE__, $sql);
				}

				if ( !($row = $db->sql_fetchrow($result)) )
				{
					message_die(GENERAL_MESSAGE, 'No_new_posts_last_visit');
				}

				$post_id = $row['post_id'];

				if (isset($HTTP_GET_VARS['sid']))
				{
					redirect("weblog_entry.$phpEx?sid=$session_id&" . POST_POST_URL . "=$post_id#$post_id");
				}
				else
				{
					redirect("weblog_entry.$phpEx?" . POST_POST_URL . "=$post_id#$post_id");
				}
			}
		}

		redirect(append_sid("weblog_entry.$phpEx?" . POST_ENTRY_URL . "=$entry_id", true));
	}
	else if ( $HTTP_GET_VARS['view'] == 'next' || $HTTP_GET_VARS['view'] == 'previous' )
	{
		$sql_condition = ( $HTTP_GET_VARS['view'] == 'next' ) ? '>' : '<';
		$sql_ordering = ( $HTTP_GET_VARS['view'] == 'next' ) ? 'ASC' : 'DESC';

		$sql = "SELECT t.topic_id
			FROM " . TOPICS_TABLE . " t, " . TOPICS_TABLE . " t2
			WHERE
				t2.topic_id = $entry_id
				AND t.forum_id = t2.forum_id
				AND t.topic_last_post_id $sql_condition t2.topic_last_post_id
			ORDER BY t.topic_last_post_id $sql_ordering
			LIMIT 1";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Could not obtain newer/older topic information", '', __LINE__, __FILE__, $sql);
		}

		if ( $row = $db->sql_fetchrow($result) )
		{
			$entry_id = intval($row['topic_id']);
		}
		else
		{
			$message = ( $HTTP_GET_VARS['view'] == 'next' ) ? 'No_newer_entries' : 'No_older_entries';
			message_die(GENERAL_MESSAGE, $message);
		}
	}
}

//
// This rather complex gaggle of code handles querying for topics but
// also allows for direct linking to a post (and the calculation of which
// page the post is on and the correct display of weblog_entry)
//
$join_sql_table = ( empty($post_id) ) ? '' : ", " . POSTS_TABLE . " p, " . POSTS_TABLE . " p2 ";
$join_sql = ( empty($post_id) ) ? "t.topic_id = $entry_id" : "p.post_id = $post_id AND t.topic_id = p.topic_id AND p2.topic_id = p.topic_id AND p2.post_id <= $post_id";
$count_sql = ( empty($post_id) ) ? '' : ", COUNT(p2.post_id) AS prev_posts";

$order_sql = ( empty($post_id) ) ? '' : "GROUP BY p.post_id, t.topic_id, t.topic_title, t.topic_status, t.topic_replies, t.topic_time, t.topic_type, t.topic_vote, t.topic_last_post_id, f.forum_name, f.forum_status, f.forum_id, f.auth_view, f.auth_read, f.auth_post, f.auth_reply, f.auth_edit, f.auth_delete, f.auth_sticky, f.auth_announce, f.auth_pollcreate, f.auth_vote, f.auth_attachments ORDER BY p.post_id ASC";

$sql = "SELECT t.topic_id, t.topic_title, t.topic_status, t.topic_replies, t.topic_time, t.topic_type, t.topic_vote, t.topic_last_post_id, f.forum_name, f.forum_status, f.forum_id, f.auth_view, f.auth_read, f.auth_post, f.auth_reply, f.auth_edit, f.auth_delete, f.auth_sticky, f.auth_announce, f.auth_pollcreate, f.auth_vote, f.auth_attachments" . $count_sql . "
	FROM " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f" . $join_sql_table . "
	WHERE $join_sql
		AND f.forum_id = t.forum_id
		$order_sql";
if ( defined('ATTACH_VERSION') )
{
	attach_setup_viewtopic_auth($order_sql, $sql);
}
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, "Could not obtain topic information", '', __LINE__, __FILE__, $sql);
}

if ( !($forum_topic_data = $db->sql_fetchrow($result)) )
{
	message_die(GENERAL_MESSAGE, 'Entry_post_not_exist');
}

$weblog_id = intval($forum_topic_data['forum_id']);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_WEBLOG_ENTRY, $entry_id);
init_userprefs($userdata);
//
// End session management
//

//
// Start auth check - Godwin
//
$is_auth = array();
$is_auth = auth(AUTH_ALL, $weblog_id, $userdata, $forum_topic_data);


if( !$is_auth['auth_read'] )
{
/*	if ( !$userdata['session_logged_in'] )
	{
		$redirect = ( isset($post_id) ) ? POST_POST_URL . "=$post_id" : POST_ENTRY_URL . "=$entry_id";
		$redirect .= ( isset($start) ) ? "&start=$start" : '';
		redirect(append_sid("login.$phpEx?redirect=weblog_entry.$phpEx&$redirect", true));
	}*/

	$message = ( !$is_auth['auth_view'] ) ? $lang['Entry_post_not_exist'] : sprintf($lang['Sorry_auth_read'], $is_auth['auth_read_type']);

	message_die(GENERAL_MESSAGE, $message);
}


//
// End auth check - Godwin
//

$forum_name = $forum_topic_data['forum_name'];
$topic_title = $forum_topic_data['topic_title'];
$entry_id = intval($forum_topic_data['topic_id']);
$topic_time = $forum_topic_data['topic_time'];

if ( !empty($post_id) )
{
	$start = floor(($forum_topic_data['prev_posts'] - 1) / intval($board_config['posts_per_page'])) * intval($board_config['posts_per_page']);
}

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
	$weblogowner_data = get_userdata($row['user_id']);
	$moderators = $row;
}

//
// Load templates
//
$template->set_filenames(array(
	'overall_header' => '../../weblogs/styles/' . $weblogowner_data['weblog_style'] . '/weblog_header.cfg',
	'body' => '../../weblogs/styles/' . $weblogowner_data['weblog_style'] . '/weblog_entry_body.cfg',
	'overall_footer' => '../../weblogs/styles/' . $weblogowner_data['weblog_style'] . '/weblog_footer.cfg')
);

//
// Is user watching this thread?
//
if( $userdata['session_logged_in'] )
{
	$can_watch_topic = TRUE;

	$sql = "SELECT notify_status
		FROM " . TOPICS_WATCH_TABLE . "
		WHERE topic_id = $entry_id
			AND user_id = " . $userdata['user_id'];
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Could not obtain topic watch information", '', __LINE__, __FILE__, $sql);
	}

	if ( $row = $db->sql_fetchrow($result) )
	{
		if ( isset($HTTP_GET_VARS['unwatch']) )
		{
			if ( $HTTP_GET_VARS['unwatch'] == 'topic' )
			{
				$is_watching_topic = 0;

				$sql_priority = (SQL_LAYER == "mysql") ? "LOW_PRIORITY" : '';
				$sql = "DELETE $sql_priority FROM " . TOPICS_WATCH_TABLE . "
					WHERE topic_id = $entry_id
						AND user_id = " . $userdata['user_id'];
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, "Could not delete topic watch information", '', __LINE__, __FILE__, $sql);
				}
			}

			$template->assign_vars(array(
				'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("weblog_entry.$phpEx?" . POST_ENTRY_URL . "=$entry_id&amp;start=$start") . '">')
			);

			$message = $lang['No_longer_watching_entry'] . '<br /><br />' . sprintf($lang['Click_return_entry'], '<a href="' . append_sid("weblog_entry.$phpEx?" . POST_ENTRY_URL . "=$entry_id&amp;start=$start") . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			$is_watching_topic = TRUE;

			if ( $row['notify_status'] )
			{
				$sql_priority = (SQL_LAYER == "mysql") ? "LOW_PRIORITY" : '';
				$sql = "UPDATE $sql_priority " . TOPICS_WATCH_TABLE . "
					SET notify_status = 0
					WHERE topic_id = $entry_id
						AND user_id = " . $userdata['user_id'];
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, "Could not update topic watch information", '', __LINE__, __FILE__, $sql);
				}
			}
		}
	}
	else
	{
		if ( isset($HTTP_GET_VARS['watch']) )
		{
			if ( $HTTP_GET_VARS['watch'] == 'topic' )
			{
				$is_watching_topic = TRUE;

				$sql_priority = (SQL_LAYER == "mysql") ? "LOW_PRIORITY" : '';
				$sql = "INSERT $sql_priority INTO " . TOPICS_WATCH_TABLE . " (user_id, topic_id, notify_status)
					VALUES (" . $userdata['user_id'] . ", $entry_id, 0)";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, "Could not insert topic watch information", '', __LINE__, __FILE__, $sql);
				}
			}

			$template->assign_vars(array(
				'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("weblog_entry.$phpEx?" . POST_ENTRY_URL . "=$entry_id&amp;start=$start") . '">')
			);

			$message = $lang['You_are_watching_entry'] . '<br /><br />' . sprintf($lang['Click_return_entry'], '<a href="' . append_sid("weblog_entry.$phpEx?" . POST_ENTRY_URL . "=$entry_id&amp;start=$start") . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			$is_watching_topic = 0;
		}
	}
}
else
{
	if ( isset($HTTP_GET_VARS['unwatch']) )
	{
		if ( $HTTP_GET_VARS['unwatch'] == 'topic' )
		{
			redirect(append_sid("login.$phpEx?redirect=weblog_entry.$phpEx&" . POST_ENTRY_URL . "=$entry_id&unwatch=topic", true));
		}
	}
	else
	{
		$can_watch_topic = 0;
		$is_watching_topic = 0;
	}
}

//
// Generate a 'Show posts in previous x days' select box. If the postdays var is POSTed
// then get it's value, find the number of topics with dates newer than it (to properly
// handle pagination) and alter the main query
//
$previous_days = array(0, 1, 7, 14, 30, 90, 180, 364);
$previous_days_text = array($lang['All_Posts'], $lang['1_Day'], $lang['7_Days'], $lang['2_Weeks'], $lang['1_Month'], $lang['3_Months'], $lang['6_Months'], $lang['1_Year']);

if( !empty($HTTP_POST_VARS['postdays']) || !empty($HTTP_GET_VARS['postdays']) )
{
	$post_days = ( !empty($HTTP_POST_VARS['postdays']) ) ? $HTTP_POST_VARS['postdays'] : $HTTP_GET_VARS['postdays'];
	$min_post_time = time() - (intval($post_days) * 86400);

	$sql = "SELECT COUNT(p.post_id) AS num_posts
		FROM " . TOPICS_TABLE . " t, " . POSTS_TABLE . " p
		WHERE t.topic_id = $entry_id
			AND p.topic_id = t.topic_id
			AND p.post_time >= $min_post_time";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Could not obtain limited topics count information", '', __LINE__, __FILE__, $sql);
	}

	$total_replies = ( $row = $db->sql_fetchrow($result) ) ? intval($row['num_posts']) : 0;

	$limit_posts_time = "AND p.post_time >= $min_post_time ";

	if ( !empty($HTTP_POST_VARS['postdays']))
	{
		$start = 0;
	}
}
else
{
	$total_replies = intval($forum_topic_data['topic_replies']) + 1;

	$limit_posts_time = '';
	$post_days = 0;
}

$select_post_days = '<select name="postdays">';
for($i = 0; $i < count($previous_days); $i++)
{
	$selected = ($post_days == $previous_days[$i]) ? ' selected="selected"' : '';
	$select_post_days .= '<option value="' . $previous_days[$i] . '"' . $selected . '>' . $previous_days_text[$i] . '</option>';
}
$select_post_days .= '</select>';

//
// Decide how to order the post display
//
if ( !empty($HTTP_POST_VARS['postorder']) || !empty($HTTP_GET_VARS['postorder']) )
{
	$post_order = (!empty($HTTP_POST_VARS['postorder'])) ? $HTTP_POST_VARS['postorder'] : $HTTP_GET_VARS['postorder'];
	$post_time_order = ($post_order == "asc") ? "ASC" : "DESC";
}
else
{
	$post_order = 'asc';
	$post_time_order = 'ASC';
}

$select_post_order = '<select name="postorder">';
if ( $post_time_order == 'ASC' )
{
	$select_post_order .= '<option value="asc" selected="selected">' . $lang['Oldest_First'] . '</option><option value="desc">' . $lang['Newest_First'] . '</option>';
}
else
{
	$select_post_order .= '<option value="asc">' . $lang['Oldest_First'] . '</option><option value="desc" selected="selected">' . $lang['Newest_First'] . '</option>';
}
$select_post_order .= '</select>';

//
// Go ahead and pull all data for this topic
//
$sql = "SELECT u.*, p.*,  pt.post_text, pt.post_subject, pt.bbcode_uid
	FROM " . POSTS_TABLE . " p, " . USERS_TABLE . " u, " . POSTS_TEXT_TABLE . " pt
	WHERE p.topic_id = $entry_id
		$limit_posts_time
		AND pt.post_id = p.post_id
		AND u.user_id = p.poster_id
	ORDER BY p.post_time $post_time_order
	LIMIT $start, ".$board_config['posts_per_page'];
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, "Could not obtain post/user information.", '', __LINE__, __FILE__, $sql);
}

$postrow = array();
if ($row = $db->sql_fetchrow($result))
{
	do
	{
		$postrow[] = $row;
	}
	while ($row = $db->sql_fetchrow($result));
	$db->sql_freeresult($result);

	$total_posts = count($postrow);
}
else
{
   include($phpbb_root_path . 'includes/functions_admin.' . $phpEx);
   sync('topic', $entry_id);

   message_die(GENERAL_MESSAGE, $lang['No_posts_topic']);
}

$resync = FALSE;
if ($forum_topic_data['topic_replies'] + 1 < $start + count($postrow))
{
   $resync = TRUE;
}
elseif ($start + $board_config['posts_per_page'] > $forum_topic_data['topic_replies'])
{
   $row_id = intval($forum_topic_data['topic_replies']) % intval($board_config['posts_per_page']);
   if ($postrow[$row_id]['post_id'] != $forum_topic_data['topic_last_post_id'] || $start + count($postrow) < $forum_topic_data['topic_replies'])
   {
      $resync = TRUE;
   }
}
elseif (count($postrow) < $board_config['posts_per_page'])
{
   $resync = TRUE;
}

if ($resync)
{
   include($phpbb_root_path . 'includes/functions_admin.' . $phpEx);
   sync('topic', $entry_id);

   $result = $db->sql_query('SELECT COUNT(post_id) AS total FROM ' . POSTS_TABLE . ' WHERE topic_id = ' . $entry_id);
   $row = $db->sql_fetchrow($result);
   $total_replies = $row['total'];
}

$sql = "SELECT *
	FROM " . RANKS_TABLE . "
	ORDER BY rank_special, rank_min";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, "Could not obtain ranks information.", '', __LINE__, __FILE__, $sql);
}

$ranksrow = array();
while ( $row = $db->sql_fetchrow($result) )
{
	$ranksrow[] = $row;
}
$db->sql_freeresult($result);

//
// Define censored word matches
//
$orig_word = array();
$replacement_word = array();
obtain_word_list($orig_word, $replacement_word);

//
// Censor topic title
//
if ( count($orig_word) )
{
	$topic_title = preg_replace($orig_word, $replacement_word, $topic_title);
}

//
// Was a highlight request part of the URI?
//
$highlight_match = $highlight = '';
if (isset($HTTP_GET_VARS['highlight']))
{
	// Split words and phrases
	$words = explode(' ', trim(htmlspecialchars(urldecode($HTTP_GET_VARS['highlight']))));

	for($i = 0; $i < sizeof($words); $i++)
	{
		if (trim($words[$i]) != '')
		{
			$highlight_match .= (($highlight_match != '') ? '|' : '') . str_replace('*', '\w*', phpbb_preg_quote($words[$i], '#'));
		}
	}
	unset($words);

	$highlight = urlencode($HTTP_GET_VARS['highlight']);
}

//
// Post, reply and other URL generation for
// templating vars
//
$new_topic_url = append_sid("weblog_posting.$phpEx?mode=newtopic&amp;" . POST_WEBLOG_URL . "=$weblog_id");
$reply_topic_url = append_sid("weblog_posting.$phpEx?mode=reply&amp;" . POST_ENTRY_URL . "=$entry_id");
$view_forum_url = append_sid("weblog.$phpEx?" . POST_WEBLOG_URL . "=$weblog_id");
$view_prev_topic_url = append_sid("weblog_entry.$phpEx?" . POST_ENTRY_URL . "=$entry_id&amp;view=previous");
$view_next_topic_url = append_sid("weblog_entry.$phpEx?" . POST_ENTRY_URL . "=$entry_id&amp;view=next");

//
// Mozilla navigation bar
//
$nav_links['prev'] = array(
	'url' => $view_prev_topic_url,
	'title' => $lang['View_previous_entry']
);
$nav_links['next'] = array(
	'url' => $view_next_topic_url,
	'title' => $lang['View_next_entry']
);
$nav_links['up'] = array(
	'url' => $view_forum_url,
	'title' => $forum_name
);

$reply_img = ( $forum_topic_data['forum_status'] == FORUM_LOCKED || $forum_topic_data['topic_status'] == TOPIC_LOCKED ) ? 'weblogs/styles/' . $weblogowner_data['weblog_style'] . '/reply-locked.gif' : 'weblogs/styles/' . $weblogowner_data['weblog_style'] . '/reply.gif';
$reply_alt = ( $forum_topic_data['forum_status'] == FORUM_LOCKED || $forum_topic_data['topic_status'] == TOPIC_LOCKED ) ? $lang['Topic_locked'] : $lang['Reply_to_entry'];
$post_img = ( $forum_topic_data['forum_status'] == FORUM_LOCKED ) ? 'weblogs/styles/' . $weblogowner_data['weblog_style'] . '/reply-locked.gif' : 'weblogs/styles/' . $weblogowner_data['weblog_style'] . '/entry.gif';
$post_alt = ( $forum_topic_data['forum_status'] == FORUM_LOCKED ) ? $lang['Weblog_locked'] : $lang['Post_new_entry'];

//
// Set a cookie for this topic
//
if ( $userdata['session_logged_in'] )
{
	$tracking_topics = ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_t']) ) ? unserialize($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_t']) : array();
	$tracking_forums = ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f']) ) ? unserialize($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f']) : array();

	if ( !empty($tracking_topics[$entry_id]) && !empty($tracking_forums[$weblog_id]) )
	{
		$topic_last_read = ( $tracking_topics[$entry_id] > $tracking_forums[$weblog_id] ) ? $tracking_topics[$entry_id] : $tracking_forums[$weblog_id];
	}
	else if ( !empty($tracking_topics[$entry_id]) || !empty($tracking_forums[$weblog_id]) )
	{
		$topic_last_read = ( !empty($tracking_topics[$entry_id]) ) ? $tracking_topics[$entry_id] : $tracking_forums[$weblog_id];
	}
	else
	{
		$topic_last_read = $userdata['user_lastvisit'];
	}

	if ( count($tracking_topics) >= 150 && empty($tracking_topics[$entry_id]) )
	{
		asort($tracking_topics);
		unset($tracking_topics[key($tracking_topics)]);
	}

	$tracking_topics[$entry_id] = time();

	setcookie($board_config['cookie_name'] . '_t', serialize($tracking_topics), 0, $board_config['cookie_path'], $board_config['cookie_domain'], $board_config['cookie_secure']);
}

make_jumpbox('viewforum.'.$phpEx, $weblog_id);

//
// Output page header
//
define('IN_WEBLOGS', true);
define('SHOW_ONLINE', true);
$page_title = $lang['Weblog_view_entry'] .' - ' . $topic_title;
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

//
// User authorisation levels output
//
$s_auth_can = ( ( $is_auth['auth_post'] ) ? $lang['Weblog_post_can'] : $lang['Weblog_post_cannot'] ) . "<br />";
$s_auth_can .= ( ( $is_auth['auth_reply'] ) ? $lang['Weblog_reply_can'] : $lang['Weblog_reply_cannot'] ) . "<br />";
$s_auth_can .= ( ( $is_auth['auth_edit'] ) ? $lang['Weblog_edit_can'] : $lang['Weblog_edit_cannot'] ) . "<br />";
$s_auth_can .= ( ( $is_auth['auth_delete'] ) ? $lang['Weblog_delete_can'] : $lang['Weblog_delete_cannot'] ) . "<br />";

if ( defined('ATTACH_VERSION') )
{
	attach_build_auth_levels($is_auth, $s_auth_can);
}

$topic_mod = '';

//
// Topic watch information
//
$s_watching_topic = '';
if ( $can_watch_topic )
{
	if ( $is_watching_topic )
	{
		$s_watching_topic = "<a href=\"weblog_entry.$phpEx?" . POST_ENTRY_URL . "=$entry_id&amp;unwatch=topic&amp;start=$start&amp;sid=" . $userdata['session_id'] . '">' . $lang['Stop_watching_entry'] . '</a>';
		$s_watching_topic_img = ( isset($images['topic_un_watch']) ) ? "<a href=\"weblog_entry.$phpEx?" . POST_ENTRY_URL . "=$entry_id&amp;unwatch=topic&amp;start=$start&amp;sid=" . $userdata['session_id'] . '"><img src="' . $images['topic_un_watch'] . '" alt="' . $lang['Stop_watching_entry'] . '" title="' . $lang['Stop_watching_entry'] . '" border="0"></a>' : '';
	}
	else
	{
		$s_watching_topic = "<a href=\"weblog_entry.$phpEx?" . POST_ENTRY_URL . "=$entry_id&amp;watch=topic&amp;start=$start&amp;sid=" . $userdata['session_id'] . '">' . $lang['Start_watching_entry'] . '</a>';
		$s_watching_topic_img = ( isset($images['Topic_watch']) ) ? "<a href=\"weblog_entry.$phpEx?" . POST_ENTRY_URL . "=$entry_id&amp;watch=topic&amp;start=$start&amp;sid=" . $userdata['session_id'] . '"><img src="' . $images['Topic_watch'] . '" alt="' . $lang['Start_watching_entry'] . '" title="' . $lang['Start_watching_entry'] . '" border="0"></a>' : '';
	}
}

//
// If we've got a hightlight set pass it on to pagination,
// I get annoyed when I lose my highlight after the first page.
//
$pagination = ( $highlight != '' ) ? generate_pagination("weblog_entry.$phpEx?" . POST_ENTRY_URL . "=$entry_id&amp;postdays=$post_days&amp;postorder=$post_order&amp;highlight=$highlight", $total_replies, $board_config['posts_per_page'], $start) : generate_pagination("weblog_entry.$phpEx?" . POST_ENTRY_URL . "=$entry_id&amp;postdays=$post_days&amp;postorder=$post_order", $total_replies, $board_config['posts_per_page'], $start);

//
// Send vars to template
//
$template->assign_vars(array(
	'FORUM_ID' => $weblog_id,
	'FORUM_NAME' => $forum_name,
	'TOPIC_ID' => $entry_id,
	'TOPIC_TITLE' => $topic_title,
	'PAGINATION' => $pagination,
	'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / intval($board_config['posts_per_page']) ) + 1 ), ceil( $total_replies / intval($board_config['posts_per_page']) )),

	'POST_IMG' => $post_img,
	'REPLY_IMG' => $reply_img,

	'L_AUTHOR' => $lang['Author'],
	'L_MESSAGE' => $lang['Message'],
	'L_POSTED' => $lang['Posted'],
	'L_POST_SUBJECT' => $lang['Post_subject'],
	'L_VIEW_NEXT_TOPIC' => $lang['View_next_entry'],
	'L_VIEW_PREVIOUS_TOPIC' => $lang['View_previous_entry'],
	'L_POST_NEW_TOPIC' => $post_alt,
	'L_POST_REPLY_TOPIC' => $reply_alt,
	'L_BACK_TO_TOP' => $lang['Back_to_top'],
	'L_DISPLAY_POSTS' => $lang['Display_posts'],
	'L_LOCK_TOPIC' => $lang['Lock_entry'],
	'L_UNLOCK_TOPIC' => $lang['Unlock_entry'],
	'L_MOVE_TOPIC' => $lang['Move_entry'],
	'L_SPLIT_TOPIC' => $lang['Split_entry'],
	'L_DELETE_TOPIC' => $lang['Delete_entry'],
	'L_GOTO_PAGE' => $lang['Goto_page'],

	'L_WEBLOGS' => $lang['Weblogs'],
	'MOD_VERSION' => $mod_version,

	'S_TOPIC_LINK' => POST_ENTRY_URL,
	'S_SELECT_POST_DAYS' => $select_post_days,
	'S_SELECT_POST_ORDER' => $select_post_order,
	'S_POST_DAYS_ACTION' => append_sid("weblog_entry.$phpEx?" . POST_ENTRY_URL . '=' . $entry_id . "&amp;start=$start"),
	'S_AUTH_LIST' => $s_auth_can,
	'S_TOPIC_ADMIN' => $topic_mod,
	'S_WATCH_TOPIC' => $s_watching_topic,
	'S_WATCH_TOPIC_IMG' => $s_watching_topic_img,

	'U_VIEW_TOPIC' => append_sid("weblog_entry.$phpEx?" . POST_ENTRY_URL . "=$entry_id&amp;start=$start&amp;postdays=$post_days&amp;postorder=$post_order&amp;highlight=$highlight"),
	'U_VIEW_FORUM' => $view_forum_url,
	'U_VIEW_OLDER_TOPIC' => $view_prev_topic_url,
	'U_VIEW_NEWER_TOPIC' => $view_next_topic_url,
	'U_POST_NEW_TOPIC' => $new_topic_url,
	'U_POST_REPLY_TOPIC' => $reply_topic_url)
);

//
// Does this topic contain a poll?
//
if ( !empty($forum_topic_data['topic_vote']) )
{
	$s_hidden_fields = '';

	$sql = "SELECT vd.vote_id, vd.vote_text, vd.vote_start, vd.vote_length, vr.vote_option_id, vr.vote_option_text, vr.vote_result
		FROM " . VOTE_DESC_TABLE . " vd, " . VOTE_RESULTS_TABLE . " vr
		WHERE vd.topic_id = $entry_id
			AND vr.vote_id = vd.vote_id
		ORDER BY vr.vote_option_id ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Could not obtain vote data for this topic", '', __LINE__, __FILE__, $sql);
	}

	if ( $vote_info = $db->sql_fetchrowset($result) )
	{
		$db->sql_freeresult($result);
		$vote_options = count($vote_info);

		$vote_id = $vote_info[0]['vote_id'];
		$vote_title = $vote_info[0]['vote_text'];

		$sql = "SELECT vote_id
			FROM " . VOTE_USERS_TABLE . "
			WHERE vote_id = $vote_id
				AND vote_user_id = " . intval($userdata['user_id']);
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Could not obtain user vote data for this topic", '', __LINE__, __FILE__, $sql);
		}

		$user_voted = ( $row = $db->sql_fetchrow($result) ) ? TRUE : 0;
		$db->sql_freeresult($result);

		if ( isset($HTTP_GET_VARS['vote']) || isset($HTTP_POST_VARS['vote']) )
		{
			$view_result = ( ( ( isset($HTTP_GET_VARS['vote']) ) ? $HTTP_GET_VARS['vote'] : $HTTP_POST_VARS['vote'] ) == 'viewresult' ) ? TRUE : 0;
		}
		else
		{
			$view_result = 0;
		}

		$poll_expired = ( $vote_info[0]['vote_length'] ) ? ( ( $vote_info[0]['vote_start'] + $vote_info[0]['vote_length'] < time() ) ? TRUE : 0 ) : 0;

		if ( $user_voted || $view_result || $poll_expired || !$is_auth['auth_vote'] || $forum_topic_data['topic_status'] == TOPIC_LOCKED )
		{
			$template->set_filenames(array(
				'pollbox' => 'viewtopic_poll_result.tpl')
			);

			$vote_results_sum = 0;

			for($i = 0; $i < $vote_options; $i++)
			{
				$vote_results_sum += $vote_info[$i]['vote_result'];
			}

			$vote_graphic = 0;
			$vote_graphic_max = count(5);

			for($i = 0; $i < $vote_options; $i++)
			{
				$vote_percent = ( $vote_results_sum > 0 ) ? $vote_info[$i]['vote_result'] / $vote_results_sum : 0;
				$vote_graphic_length = round($vote_percent * $board_config['vote_graphic_length']);

				$vote_graphic_img = 'weblogs/styles/' . $weblogowner_data['weblog_style'] . '/voting_bar.gif';
				$vote_graphic = ($vote_graphic < $vote_graphic_max - 1) ? $vote_graphic + 1 : 0;

				if ( count($orig_word) )
				{
					$vote_info[$i]['vote_option_text'] = preg_replace($orig_word, $replacement_word, $vote_info[$i]['vote_option_text']);
				}

				$template->assign_block_vars("poll_option", array(
					'POLL_OPTION_CAPTION' => $vote_info[$i]['vote_option_text'],
					'POLL_OPTION_RESULT' => $vote_info[$i]['vote_result'],
					'POLL_OPTION_PERCENT' => sprintf("%.1d%%", ($vote_percent * 100)),

					'POLL_OPTION_IMG' => $vote_graphic_img,
					'POLL_OPTION_IMG_WIDTH' => $vote_graphic_length)
				);
			}

			$template->assign_vars(array(
				'L_TOTAL_VOTES' => $lang['Total_votes'],
				'TOTAL_VOTES' => $vote_results_sum)
			);

		}
		else
		{
			$template->set_filenames(array(
				'pollbox' => 'viewtopic_poll_ballot.tpl')
			);

			for($i = 0; $i < $vote_options; $i++)
			{
				if ( count($orig_word) )
				{
					$vote_info[$i]['vote_option_text'] = preg_replace($orig_word, $replacement_word, $vote_info[$i]['vote_option_text']);
				}

				$template->assign_block_vars("poll_option", array(
					'POLL_OPTION_ID' => $vote_info[$i]['vote_option_id'],
					'POLL_OPTION_CAPTION' => $vote_info[$i]['vote_option_text'])
				);
			}

			$template->assign_vars(array(
				'L_SUBMIT_VOTE' => $lang['Submit_vote'],
				'L_VIEW_RESULTS' => $lang['View_results'],

				'U_VIEW_RESULTS' => append_sid("weblog_entry.$phpEx?" . POST_ENTRY_URL . "=$entry_id&amp;postdays=$post_days&amp;postorder=$post_order&amp;vote=viewresult"))
			);

			$s_hidden_fields = '<input type="hidden" name="topic_id" value="' . $entry_id . '" /><input type="hidden" name="mode" value="vote" />';
		}

		if ( count($orig_word) )
		{
			$vote_title = preg_replace($orig_word, $replacement_word, $vote_title);
		}

		$s_hidden_fields .= '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" />';

		$template->assign_vars(array(
			'POLL_QUESTION' => $vote_title,

			'S_HIDDEN_FIELDS' => $s_hidden_fields,
			'S_POLL_ACTION' => append_sid("weblog_posting.$phpEx?mode=vote&amp;" . POST_ENTRY_URL . "=$entry_id"))
		);

		$template->assign_var_from_handle('POLL_DISPLAY', 'pollbox');
	}
}

if ( defined('ATTACH_VERSION') )
{
	$post_id_array = array();

	for ($i = 0; $i < count($postrow); $i++)
	{
		if ($postrow[$i]['post_attachment'] == 1)
		{
			$post_id_array[] = $postrow[$i]['post_id'];
		}
	}

	$rows = get_attachments_from_post($post_id_array);
	$num_rows = count($rows);

	@reset($attachments);

	if ( $num_rows != 0 )
	{
		for ($i = 0; $i < $num_rows; $i++)
		{
			$attachments['_' . $rows[$i]['post_id']][] = $rows[$i];
		}

		init_display_template('body', '{postrow.ATTACHMENTS}', '../../weblogs/styles/' . $weblogowner_data['weblog_style'] . '/attach_body.cfg');

		init_complete_extensions_data();

		$template->assign_vars(array(
			'L_POSTED_ATTACHMENTS' => $lang['Posted_attachments'],
			'L_KILOBYTE' => $lang['KB'])
		);
	}
}

//
// Update the topic view counter
//
$sql = "UPDATE " . TOPICS_TABLE . "
	SET topic_views = topic_views + 1
	WHERE topic_id = $entry_id";
if ( !$db->sql_query($sql) )
{
	message_die(GENERAL_ERROR, "Could not update topic views.", '', __LINE__, __FILE__, $sql);
}

//
// Okay, let's do the loop, yeah come on baby let's do the loop
// and it goes like this ...
//
for($i = 0; $i < $total_posts; $i++)
{
	$poster_id = $postrow[$i]['user_id'];
	$poster = ( $poster_id == ANONYMOUS ) ? $lang['Guest'] : $postrow[$i]['username'];

	$post_date = create_date($board_config['default_dateformat'], $postrow[$i]['post_time'], $board_config['board_timezone']);

	//
	// Borrowed from Today At/Yesterday At Mod
	//
	if ( $board_config['time_today'] < $postrow[$i]['post_time'])
	{
		$post_date = sprintf($lang['Today_at'], create_date($board_config['default_timeformat'], $postrow[$i]['post_time'], $board_config['board_timezone']));
	}
	else if ( $board_config['time_yesterday'] < $postrow[$i]['post_time'])
	{
		$post_date = sprintf($lang['Yesterday_at'], create_date($board_config['default_timeformat'], $postrow[$i]['post_time'], $board_config['board_timezone']));
	}

	$poster_posts = ( $postrow[$i]['user_id'] != ANONYMOUS ) ? $lang['Posts'] . ': ' . $postrow[$i]['user_posts'] : '';

	$poster_from = ( $postrow[$i]['user_from'] && $postrow[$i]['user_id'] != ANONYMOUS ) ? $lang['Location'] . ': ' . $postrow[$i]['user_from'] : '';

	$poster_joined = ( $postrow[$i]['user_id'] != ANONYMOUS ) ? $lang['Joined'] . ': ' . create_date($lang['DATE_FORMAT'], $postrow[$i]['user_regdate'], $board_config['board_timezone']) : '';

	$poster_avatar = '';
	if ( $postrow[$i]['user_avatar_type'] && $poster_id != ANONYMOUS && $postrow[$i]['user_allowavatar'] )
	{
		switch( $postrow[$i]['user_avatar_type'] )
		{
			case USER_AVATAR_UPLOAD:
				$poster_avatar = ( $board_config['allow_avatar_upload'] ) ? '<img src="' . $board_config['avatar_path'] . '/' . $postrow[$i]['user_avatar'] . '" alt="" border="0" />' : '';
				break;
			case USER_AVATAR_REMOTE:
				$poster_avatar = ( $board_config['allow_avatar_remote'] ) ? '<img src="' . $postrow[$i]['user_avatar'] . '" alt="" border="0" />' : '';
				break;
			case USER_AVATAR_GALLERY:
				$poster_avatar = ( $board_config['allow_avatar_local'] ) ? '<img src="' . $board_config['avatar_gallery_path'] . '/' . $postrow[$i]['user_avatar'] . '" alt="" border="0" />' : '';
				break;
		}
	}

	//
	// Define the little post icon
	//
	if ( $userdata['session_logged_in'] && $postrow[$i]['post_time'] > $userdata['user_lastvisit'] && $postrow[$i]['post_time'] > $topic_last_read )
	{
		$mini_post_img = $images['icon_minipost_new'];
		$mini_post_alt = $lang['New_post'];
	}
	else
	{
		$mini_post_img = $images['icon_minipost'];
		$mini_post_alt = $lang['Post'];
	}

	$mini_post_url = append_sid("weblog_entry.$phpEx?" . POST_POST_URL . '=' . $postrow[$i]['post_id']) . '#' . $postrow[$i]['post_id'];

	//
	// Generate ranks, set them to empty string initially.
	//
	$poster_rank = '';
	$rank_image = '';
	if ( $postrow[$i]['user_id'] == ANONYMOUS )
	{
	}
	else if ( $postrow[$i]['user_rank'] )
	{
		for($j = 0; $j < count($ranksrow); $j++)
		{
			if ( $postrow[$i]['user_rank'] == $ranksrow[$j]['rank_id'] && $ranksrow[$j]['rank_special'] )
			{
				$poster_rank = $ranksrow[$j]['rank_title'];
				$rank_image = ( $ranksrow[$j]['rank_image'] ) ? '<img src="' . $ranksrow[$j]['rank_image'] . '" alt="' . $poster_rank . '" title="' . $poster_rank . '" border="0" /><br />' : '';
			}
		}
	}
	else
	{
		for($j = 0; $j < count($ranksrow); $j++)
		{
			if ( $postrow[$i]['user_posts'] >= $ranksrow[$j]['rank_min'] && !$ranksrow[$j]['rank_special'] )
			{
				$poster_rank = $ranksrow[$j]['rank_title'];
				$rank_image = ( $ranksrow[$j]['rank_image'] ) ? '<img src="' . $ranksrow[$j]['rank_image'] . '" alt="' . $poster_rank . '" title="' . $poster_rank . '" border="0" /><br />' : '';
			}
		}
	}

	//
	// Handle anon users posting with usernames
	//
	if ( $poster_id == ANONYMOUS && $postrow[$i]['post_username'] != '' )
	{
		$poster = $postrow[$i]['post_username'];
		$poster_rank = $lang['Guest'];
		$poster_age = '';
	}

	$temp_url = '';

	if ( $poster_id != ANONYMOUS )
	{
		$temp_url = append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$poster_id");
		$profile_img = '<a href="' . $temp_url . '"><img src="weblogs/styles/' . $weblogowner_data['weblog_style'] . '/icon_profile.gif" alt="' . $lang['Read_profile'] . '" title="' . $lang['Read_profile'] . '" border="0" /></a>';
		$profile = '<a href="' . $temp_url . '">' . $lang['Read_profile'] . '</a>';

		$temp_url = append_sid("privmsg.$phpEx?mode=post&amp;" . POST_USERS_URL . "=$poster_id");
		$pm_img = '<a href="' . $temp_url . '"><img src="weblogs/styles/' . $weblogowner_data['weblog_style'] . '/icon_pm.gif" alt="' . $lang['Send_private_message'] . '" title="' . $lang['Send_private_message'] . '" border="0" /></a>';
		$pm = '<a href="' . $temp_url . '">' . $lang['Send_private_message'] . '</a>';

		if ( !empty($postrow[$i]['user_viewemail']) || $is_auth['auth_mod'] )
		{
			$email_uri = ( $board_config['board_email_form'] ) ? append_sid("profile.$phpEx?mode=email&amp;" . POST_USERS_URL .'=' . $poster_id) : 'mailto:' . $postrow[$i]['user_email'];

			$email_img = '<a href="' . $email_uri . '"><img src="weblogs/styles/' . $weblogowner_data['weblog_style'] . '/icon_email.gif" alt="' . $lang['Send_email'] . '" title="' . $lang['Send_email'] . '" border="0" /></a>';
			$email = '<a href="' . $email_uri . '">' . $lang['Send_email'] . '</a>';
		}
		else
		{
			$email_img = '';
			$email = '';
		}

		$www_img = ( $postrow[$i]['user_website'] ) ? '<a href="' . $postrow[$i]['user_website'] . '" target="_userwww"><img src="weblogs/styles/' . $weblogowner_data['weblog_style'] . '/icon_www.gif" alt="' . $lang['Visit_website'] . '" title="' . $lang['Visit_website'] . '" border="0" /></a>' : '';
		$www = ( $postrow[$i]['user_website'] ) ? '<a href="' . $postrow[$i]['user_website'] . '" target="_userwww">' . $lang['Visit_website'] . '</a>' : '';

		if ( !empty($postrow[$i]['user_icq']) )
		{
			$icq_status_img = '<a href="http://wwp.icq.com/' . $postrow[$i]['user_icq'] . '#pager"><img src="http://web.icq.com/whitepages/online?icq=' . $postrow[$i]['user_icq'] . '&img=5" width="18" height="18" border="0" /></a>';
			$icq_img = '<a href="http://wwp.icq.com/scripts/search.dll?to=' . $postrow[$i]['user_icq'] . '"><img src="weblogs/styles/' . $weblogowner_data['weblog_style'] . '/icon_icq_add.gif" alt="' . $lang['ICQ'] . '" title="' . $lang['ICQ'] . '" border="0" /></a>';
			$icq =  '<a href="http://wwp.icq.com/scripts/search.dll?to=' . $postrow[$i]['user_icq'] . '">' . $lang['ICQ'] . '</a>';
		}
		else
		{
			$icq_status_img = '';
			$icq_img = '';
			$icq = '';
		}

		$aim_img = ( $postrow[$i]['user_aim'] ) ? '<a href="aim:goim?screenname=' . $postrow[$i]['user_aim'] . '&amp;message=Hello+Are+you+there?"><img src="weblogs/styles/' . $weblogowner_data['weblog_style'] . '/icon_aim.gif" alt="' . $lang['AIM'] . '" title="' . $lang['AIM'] . '" border="0" /></a>' : '';
		$aim = ( $postrow[$i]['user_aim'] ) ? '<a href="aim:goim?screenname=' . $postrow[$i]['user_aim'] . '&amp;message=Hello+Are+you+there?">' . $lang['AIM'] . '</a>' : '';

		$temp_url = append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$poster_id");
		$msn_img = ( $postrow[$i]['user_msnm'] ) ? '<a href="' . $temp_url . '"><img src="weblogs/styles/' . $weblogowner_data['weblog_style'] . '/icon_msnm.gif" alt="' . $lang['MSNM'] . '" title="' . $lang['MSNM'] . '" border="0" /></a>' : '';
		$msn = ( $postrow[$i]['user_msnm'] ) ? '<a href="' . $temp_url . '">' . $lang['MSNM'] . '</a>' : '';

		$yim_img = ( $postrow[$i]['user_yim'] ) ? '<a href="http://edit.yahoo.com/config/send_webmesg?.target=' . $postrow[$i]['user_yim'] . '&amp;.src=pg"><img src="weblogs/styles/' . $weblogowner_data['weblog_style'] . '/icon_yim.gif" alt="' . $lang['YIM'] . '" title="' . $lang['YIM'] . '" border="0" /></a>' : '';
		$yim = ( $postrow[$i]['user_yim'] ) ? '<a href="http://edit.yahoo.com/config/send_webmesg?.target=' . $postrow[$i]['user_yim'] . '&amp;.src=pg">' . $lang['YIM'] . '</a>' : '';

		$weblog_img = ( $postrow[$i]['user_weblog'] != 0 ) ? '<a href="' . append_sid("weblog.$phpEx?" . POST_WEBLOG_URL . "=".$postrow[$i]['user_weblog']) . '"><img src="weblogs/styles/' . $weblogowner_data['weblog_style'] . '/icon_weblog.gif" alt="' . $lang['Weblog'] . '" title="' . $lang['Weblog'] . '" border="0" /></a>' : '';
		$weblog = ( $postrow[$i]['user_weblog'] != 0 ) ? '<a href="' . append_sid("weblog.$phpEx?" . POST_WEBLOG_URL . "=".$postrow[$i]['user_weblog']) . '">' . $lang['Weblog'] . '</a>' : '';
	}
	else
	{
		$profile_img = '';
		$profile = '';
		$pm_img = '';
		$pm = '';
		$email_img = '';
		$email = '';
		$www_img = '';
		$www = '';
		$icq_status_img = '';
		$icq_img = '';
		$icq = '';
		$aim_img = '';
		$aim = '';
		$msn_img = '';
		$msn = '';
		$yim_img = '';
		$yim = '';
		$weblog_img = '';
		$weblog = '';
	}

	$temp_url = append_sid("weblog_posting.$phpEx?mode=quote&amp;" . POST_POST_URL . "=" . $postrow[$i]['post_id'] . "&amp;" . POST_WEBLOG_URL . "=" . $weblog_id);
	$quote_img = '<a href="' . $temp_url . '"><img src="weblogs/styles/' . $weblogowner_data['weblog_style'] . '/icon_quote.gif" alt="' . $lang['Reply_with_quote'] . '" title="' . $lang['Reply_with_quote'] . '" border="0" /></a>';
	$quote = '<a href="' . $temp_url . '">' . $lang['Reply_with_quote'] . '</a>';

	$temp_url = append_sid("search.$phpEx?search_author=" . urlencode($postrow[$i]['username']) . "&amp;" . POST_WEBLOG_URL . "= " . $weblog_id . "&amp;" . POST_WEBLOG_URL . "=" . $weblog_id . "&amp;showresults=posts");
	$search_img = '<a href="' . $temp_url . '"><img src="weblogs/styles/' . $weblogowner_data['weblog_style'] . '/icon_search.gif" alt="' . $lang['Search_user_posts'] . '" title="' . $lang['Search_user_posts'] . '" border="0" /></a>';
	$search = '<a href="' . $temp_url . '">' . $lang['Search_user_posts'] . '</a>';

	if ( ( $userdata['user_id'] == $poster_id && $is_auth['auth_edit'] ) || $is_auth['auth_mod'] )
	{
		$temp_url = append_sid("weblog_posting.$phpEx?mode=editpost&amp;" . POST_POST_URL . "=" . $postrow[$i]['post_id'] . "&amp;" . POST_WEBLOG_URL . "=" . $weblog_id);
		$edit_img = '<a href="' . $temp_url . '"><img src="weblogs/styles/' . $weblogowner_data['weblog_style'] . '/icon_edit.gif" alt="' . $lang['Edit_delete_post'] . '" title="' . $lang['Edit_delete_post'] . '" border="0" /></a>';
		$edit = '<a href="' . $temp_url . '">' . $lang['Edit_delete_post'] . '</a>';
	}
	else
	{
		$edit_img = '';
		$edit = '';
	}

	if ( $is_auth['auth_mod'] )
	{
		$temp_url = "modcp.$phpEx?mode=ip&amp;" . POST_POST_URL . "=" . $postrow[$i]['post_id'] . "&amp;" . POST_TOPIC_URL . "=" . $entry_id . "&amp;sid=" . $userdata['session_id'];
		$ip_img = '<a href="' . $temp_url . '"><img src="weblogs/styles/' . $weblogowner_data['weblog_style'] . '/icon_ip.gif" alt="' . $lang['View_IP'] . '" title="' . $lang['View_IP'] . '" border="0" /></a>';
		$ip = '<a href="' . $temp_url . '">' . $lang['View_IP'] . '</a>';

		$temp_url = "weblog_posting.$phpEx?mode=delete&amp;" . POST_POST_URL . "=" . $postrow[$i]['post_id'] . "&amp;sid=" . $userdata['session_id'];
		$delpost_img = '<a href="' . $temp_url . '"><img src="weblogs/styles/' . $weblogowner_data['weblog_style'] . '/icon_delete.gif" alt="' . $lang['Delete_post'] . '" title="' . $lang['Delete_post'] . '" border="0" /></a>';
		$delpost = '<a href="' . $temp_url . '">' . $lang['Delete_post'] . '</a>';
	}
	else
	{
		$ip_img = '';
		$ip = '';

		if ( $userdata['user_id'] == $poster_id && $is_auth['auth_delete'] && $forum_topic_data['topic_last_post_id'] == $postrow[$i]['post_id'] )
		{
			$temp_url = "weblog_posting.$phpEx?mode=delete&amp;" . POST_POST_URL . "=" . $postrow[$i]['post_id'] . "&amp;" . POST_WEBLOG_URL . "=" . $weblog_id . "&amp;sid=" . $userdata['session_id'];
			$delpost_img = '<a href="' . $temp_url . '"><img src="weblogs/styles/' . $weblogowner_data['weblog_style'] . '/icon_delete.gif" alt="' . $lang['Delete_post'] . '" title="' . $lang['Delete_post'] . '" border="0" /></a>';
			$delpost = '<a href="' . $temp_url . '">' . $lang['Delete_post'] . '</a>';
		}
		else
		{
			$delpost_img = '';
			$delpost = '';
		}
	}

	$post_subject = ( $postrow[$i]['post_subject'] != '' ) ? $postrow[$i]['post_subject'] : '';

	$message = $postrow[$i]['post_text'];
	$bbcode_uid = $postrow[$i]['bbcode_uid'];

	$user_sig = ( $postrow[$i]['enable_sig'] && $postrow[$i]['user_sig'] != '' && $board_config['allow_sig'] ) ? $postrow[$i]['user_sig'] : '';
	$user_sig_bbcode_uid = $postrow[$i]['user_sig_bbcode_uid'];

	//
	// Note! The order used for parsing the message _is_ important, moving things around could break any
	// output
	//

	//
	// If the board has HTML off but the post has HTML
	// on then we process it, else leave it alone
	//
	if ( !$board_config['allow_html'] )
	{
		if ( $user_sig != '' && $userdata['user_allowhtml'] )
		{
			$user_sig = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $user_sig);
		}

		if ( $postrow[$i]['enable_html'] )
		{
			$message = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $message);
		}
	}

	//
	// Parse message and/or sig for BBCode if reqd
	//
	if ( $board_config['allow_bbcode'] )
	{
		if ( $user_sig != '' && $user_sig_bbcode_uid != '' )
		{
			$user_sig = ( $board_config['allow_bbcode'] ) ? bbencode_second_pass($user_sig, $user_sig_bbcode_uid) : preg_replace('/\:[0-9a-z\:]+\]/si', ']', $user_sig);
		}

		if ( $bbcode_uid != '' )
		{
			$message = ( $board_config['allow_bbcode'] ) ? bbencode_second_pass($message, $bbcode_uid) : preg_replace('/\:[0-9a-z\:]+\]/si', ']', $message);
		}
	}

	if ( $user_sig != '' )
	{
		$user_sig = make_clickable($user_sig);
	}
	$message = make_clickable($message);

	//
	// Parse smilies
	//
	if ( $board_config['allow_smilies'] )
	{
		if ( $postrow[$i]['user_allowsmile'] && $user_sig != '' )
		{
			$user_sig = smilies_pass($user_sig);
		}

		if ( $postrow[$i]['enable_smilies'] )
		{
			$message = smilies_pass($message);
		}
	}

	//
	// Highlight active words (primarily for search)
	//
	if ($highlight_match)
	{
		// This was shamelessly 'borrowed' from volker at multiartstudio dot de
		// via php.net's annotated manual
		$message = str_replace('\"', '"', substr(preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "preg_replace('#\b(" . $highlight_match . ")\b#i', '<span style=\"color:#" . $theme['fontcolor3'] . "\"><b>\\\\1</b></span>', '\\0')", '>' . $message . '<'), 1, -1));
	}

	//
	// Replace naughty words
	//
	if (count($orig_word))
	{
		$post_subject = preg_replace($orig_word, $replacement_word, $post_subject);

		if ($user_sig != '')
		{
			$user_sig = str_replace('\"', '"', substr(preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "preg_replace(\$orig_word, \$replacement_word, '\\0')", '>' . $user_sig . '<'), 1, -1));
		}

		$message = str_replace('\"', '"', substr(preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "preg_replace(\$orig_word, \$replacement_word, '\\0')", '>' . $message . '<'), 1, -1));
	}

	//
	// Replace newlines (we use this rather than nl2br because
	// till recently it wasn't XHTML compliant)
	//
	if ( $user_sig != '' )
	{
		$user_sig = '<br />_________________<br />' . str_replace("\n", "\n<br />\n", $user_sig);
	}

	$message = str_replace("\n", "\n<br />\n", $message);

	//
	// Editing information
	//
	if ( $postrow[$i]['post_edit_count'] )
	{
		$l_edit_time_total = ( $postrow[$i]['post_edit_count'] == 1 ) ? $lang['Edited_time_total'] : $lang['Edited_times_total'];

		$l_edited_by = '<br /><br />' . sprintf($l_edit_time_total, $poster, create_date($board_config['default_dateformat'], $postrow[$i]['post_edit_time'], $board_config['board_timezone']), $postrow[$i]['post_edit_count']);
	}
	else
	{
		$l_edited_by = '';
	}

	$mood = $postrow[$i]['post_mood'] - 1;

	if ( $mood_data[$mood]['mood'] != $lang['Weblog_none'] && $mood_data[$mood]['mood'] != '' && $mood > 0 )
	{
		$icon = '[ <b>' . $lang['Weblog_mood'] . ':</b> <img src="images/weblogs/' . $mood_data[$mood]['image_url'] . '" alt="' . $mood_data[$mood]['mood'] . '" border="0"> ' . $mood_data[$mood]['mood'] . ' ]<br />';
	}
	else
	{
		$icon = '';
	}

	$currently1 = $postrow[$i]['post_currently1'] - 1;

	if ( $currently_data[$currently1]['currently'] != $lang['Weblog_custom'] && $currently_data[$currently1]['currently'] != $lang['Weblog_none'] && $currently1 > 0)
	{
		$icon .= "[ <img src='images/weblogs/" . $currently_data[$currently1]['image_url'] . "' border='0'><b>" . $lang['Weblog_currently'] . ":</b> " . $currently_data[$currently1]['currently'] . " " . $postrow[$i]['post_currently2'] . " ]<br />";
	}
	else
	{
		$icon .= '';
	}

	//
	// Borrowed from Online/Offline/Hidden Mod
	//
	if ( $postrow[$i]['user_id'] == ANONYMOUS )
	{
		$status = '';
	}
	if ( $poster_id != ANONYMOUS )
	{
		if($postrow[$i]['user_session_time'] >= (time()-60))
		{
			if($postrow[$i]['user_allow_viewonline'])
			{
				$status = '<img src="weblogs/styles/' . $weblogowner_data['weblog_style'] . '/icon_online.gif" alt="' . $lang['Online'] . '" title="' . $lang['Online'] . '" border="0" />';
			}
			else if( $is_auth['auth_mod'] || $userdata['user_id'] == $postrow[$i]['user_id'] )
			{
				$status = '<img src="weblogs/styles/' . $weblogowner_data['weblog_style'] . '/icon_hidden.gif" alt="' . $lang['Hidden'] . '" title="' . $lang['Hidden'] . '" border="0" />';
			}
			else
			{
				$status = '<img src="weblogs/styles/' . $weblogowner_data['weblog_style'] . '/icon_offline.gif" alt="' . $lang['Offline'] . '" title="' . $lang['Offline'] . '" border="0" />';
			}
		}
		else
		{
			$status = '<img src="weblogs/styles/' . $weblogowner_data['weblog_style'] . '/icon_offline.gif" alt="' . $lang['Offline'] . '" title="' . $lang['Offline'] . '" border="0" />';
		}
	}

	//
	// Again this will be handled by the templating
	// code at some point
	//
	$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
	$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

	$template->assign_block_vars('postrow', array(
		'ROW_COLOR' => '#' . $row_color,
		'ROW_CLASS' => $row_class,
		'POSTER_NAME' => $poster,
		'POSTER_RANK' => $poster_rank,
		'RANK_IMAGE' => $rank_image,
		'POSTER_JOINED' => $poster_joined,
		'POSTER_POSTS' => $poster_posts,
		'POSTER_FROM' => $poster_from,
		'POSTER_AVATAR' => $poster_avatar,
		'POST_DATE' => $post_date,
		'POST_SUBJECT' => $post_subject,
		'MESSAGE' => $message,
		'SIGNATURE' => $user_sig,
		'EDITED_MESSAGE' => $l_edited_by,

		'MINI_POST_IMG' => $mini_post_img,
		'PROFILE_IMG' => $profile_img,
		'PROFILE' => $profile,
		'SEARCH_IMG' => $search_img,
		'SEARCH' => $search,
		'PM_IMG' => $pm_img,
		'PM' => $pm,
		'EMAIL_IMG' => $email_img,
		'EMAIL' => $email,
		'WWW_IMG' => $www_img,
		'WWW' => $www,
		'ICQ_STATUS_IMG' => $icq_status_img,
		'ICQ_IMG' => $icq_img,
		'ICQ' => $icq,
		'AIM_IMG' => $aim_img,
		'AIM' => $aim,
		'MSN_IMG' => $msn_img,
		'MSN' => $msn,
		'YIM_IMG' => $yim_img,
		'YIM' => $yim,
		'EDIT_IMG' => $edit_img,
		'EDIT' => $edit,
		'QUOTE_IMG' => $quote_img,
		'QUOTE' => $quote,
		'IP_IMG' => $ip_img,
		'IP' => $ip,
		'DELETE_IMG' => $delpost_img,
		'DELETE' => $delpost,

		'ICON' => $icon,
		'WEBLOG_IMG' => $weblog_img,
		'WEBLOG' => $weblog,

		'L_MINI_POST_ALT' => $mini_post_alt,

		'U_MINI_POST' => $mini_post_url,
		'U_POST_ID' => $postrow[$i]['post_id'])
	);

	if ( defined('ATTACH_VERSION') )
	{
		//display_post_attachments($postrow[$i]['post_id'], $postrow[$i]['post_attachment']);
		display_attachments($postrow[$i]['post_id']);
	}
}

//$template->pparse('overall_header');   //commented by isaac to remove double header in view posts page
$template->pparse('body');
//$template->pparse('overall_footer');     commented by isaac to fix the page_tail.php not being displayed

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>
