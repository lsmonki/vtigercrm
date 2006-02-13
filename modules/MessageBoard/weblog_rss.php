<?php
/***************************************************************************
 *                               weblog_rss.php
 *                            --------------------
 *   begin                : Saturday, November 1, 2003
 *   copyright            : (C) 2003 Hyperion
 *   email                : vinng86@hotmail.com
 *
 *   $Id: weblog_rss.php,v 1.9 2005/01/04 15:04:13 saraj Exp $
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
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/bbcode.' . $phpEx);
include($phpbb_root_path . 'includes/weblogs_common.' . $phpEx);
//
// Get all the data
//

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_WEBLOG);
init_userprefs($userdata);
//
// End session management
//

//	
// Start initial var setup
//
if( isset($HTTP_GET_VARS[POST_WEBLOG_URL]) || isset($HTTP_POST_VARS[POST_WEBLOG_URL]) )
{
	$weblog_id = ( isset($HTTP_GET_VARS[POST_WEBLOG_URL]) ) ? intval($HTTP_GET_VARS[POST_WEBLOG_URL]) : intval($HTTP_POST_VARS[POST_WEBLOG_URL]);
	$weblog_id = intval($weblog_id);
}

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
		message_die(GENERAL_ERROR, "Couldn't obtain weblogs information.", "", __LINE__, __FILE__, $sql);
	}
}
else
{
	message_die(GENERAL_MESSAGE, $lang['Weblog_not_exist']);
}

//
// If the query doesn't return any rows this isn't a valid forum. Inform
// the user.
//
if( !($weblog_row = $db->sql_fetchrow($result)) )
{
	message_die(GENERAL_MESSAGE, $lang['Weblog_not_exist']);
}

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


$sql = "SELECT u.*
	FROM " . AUTH_ACCESS_TABLE . " aa, " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE . " g, " . USERS_TABLE . " u
	WHERE aa.forum_id = $weblog_id 
		AND aa.auth_mod = " . TRUE . " 
		AND g.group_single_user = 1
		AND ug.group_id = aa.group_id 
		AND g.group_id = aa.group_id 
		AND u.user_id = ug.user_id 
	GROUP BY u.user_id, u.username  
	ORDER BY u.user_id";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, "Could not obtain post/user information.", '', __LINE__, __FILE__, $sql);
}

while( $row = $db->sql_fetchrow($result) )
{
	$profiledata = get_userdata($row['user_id']);
}

$sql = "SELECT 	  t.topic_id,
			  t.topic_time,
			  t.topic_title,
			  t.topic_mood,
			  t.topic_currently1,
			  t.topic_currently2,
			  t.forum_id,
			  t.topic_poster,
			  t.topic_first_post_id,
			  t.topic_status,
			  u.username,
			  u.user_id,
			  t.topic_replies,
			  pt.bbcode_uid,
			  pt.post_text,
			  pt.post_id,
			  p.post_id
			FROM
			  " . TOPICS_TABLE . " AS t,
			  " . USERS_TABLE . " AS u,
			  " . POSTS_TEXT_TABLE . " AS pt,
			  " . POSTS_TABLE . " AS p
			WHERE
			  t.forum_id IN (" . $weblog_id . ") AND
			  t.topic_time <= " . time() . " AND
			  t.topic_poster = u.user_id AND
			  t.topic_first_post_id = pt.post_id AND
			  t.topic_first_post_id = p.post_id AND
			  t.topic_status <> 2
			ORDER BY
			  t.topic_time DESC";
if(!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Could not query entry information', '', __LINE__, __FILE__, $sql);
}
$i = 0;
$entry_data = array();
while ($row = $db->sql_fetchrow($result))
{
	$entry_data[$i]['topic_id'] = $row['topic_id'];
	$entry_data[$i]['topic_time'] = $row['topic_time'];
	$entry_data[$i]['topic_title'] = $row['topic_title'];
	$entry_data[$i]['topic_mood'] = $row['topic_mood'];
	$entry_data[$i]['topic_currently1'] = $row['topic_currently1'];
	$entry_data[$i]['topic_currently2'] = $row['topic_currently2'];
	$entry_data[$i]['topic_replies'] = $row['topic_replies'];
	$entry_data[$i]['post_text'] = $row['post_text'];
	$entry_data[$i]['bbcode_uid'] = $row['bbcode_uid'];
	$entry_data[$i]['user_id'] = $row['user_id'];
	$entry_data[$i]['username'] = $row['username'];
	$i++;
}

// 
// End getting data
//

// 
// Start output of RSS
//

$server_name = trim($board_config['server_name']);
$server_protocol = ( $board_config['cookie_secure'] ) ? 'https://' : 'http://';
$server_port = ( $board_config['server_port'] <> 80 ) ? ':' . trim($board_config['server_port']) . '/' : '';
$script_name = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($board_config['script_path']));

$basedir = $server_protocol . $server_name . '/' . $server_port . $script_name . '/';

$smilies_path = $board_config['smilies_path'];
$smilies_url = $basedir . $smilies_path;
$smilies_path = preg_replace("/\//", "\/", $smilies_path);


// Borrowed from includes/page_header.php
if (!empty($HTTP_SERVER_VARS['SERVER_SOFTWARE']) && strstr($HTTP_SERVER_VARS['SERVER_SOFTWARE'], 'Apache/2'))
{
	header ('Cache-Control: no-cache, pre-check=0, post-check=0, max-age=0');
}
else
{
	header ('Cache-Control: private, pre-check=0, post-check=0, max-age=0');
}
header ('Expires: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
header ('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Content-Type: text/xml');

echo ("<?xml version=\"1.0\" encoding=\"iso-8859-1\" ?>\n");
echo ("  <rss version=\"2.0\">\n");
echo ("	<channel>\n");
echo ("	  <title>" . htmlspecialchars($weblog_row['forum_name']) . "</title>\n");
echo ("	  <link>" . htmlspecialchars($basedir . "weblog.$phpEx?" . POST_WEBLOG_URL . "=$weblog_id") . "</link>\n");
echo ("	  <description>" . htmlspecialchars($weblog_row['forum_desc']) . "</description>\n");
echo ("	  <managingEditor>" . htmlspecialchars($profiledata['user_email']) . "</managingEditor>\n");
echo ("	  <webMaster>" . htmlspecialchars($board_config['board_email']) . "</webMaster>\n");
echo ("	  <lastBuildDate>" . gmdate('D, d M Y H:i:s', time()) . " GMT</lastBuildDate>\n");
echo ("	  <generator>Forum Weblogs Mod " . $mod_version . " by Hyperion</generator>\n");

for ($i = 0; $i < count($entry_data); $i++)
{
	// Prepare all data

	// Entry Text
	$entry_text = '';
	$mood = $entry_data[$i]['topic_mood'] - 1;
	if ( $mood_data[$mood]['mood'] != $lang['Weblog_none'] && $mood_data[$mood]['mood'] != '' && $mood > 0 )
	{ 
		$entry_text .= '[ <b>' . $lang['Weblog_mood'] . ':</b> ' . $mood_data[$mood]['mood'] . ' ]<br />'; 
	}

	// Currently Icons
	$currently = $entry_data[$i]['topic_currently1'] - 1;
	if ( $currently_data[$currently]['image_url'] != '' && $currently_data[$currently]['currently'] != $lang['Weblog_none'] && $currently_data[$currently]['currently'] != $lang['Weblog_custom'] && $currently > 0 )
	{ 
		$entry_text .= '[ <b>' . $lang['Weblog_currently'] . ':</b> ' . $currently_data[$currently]['currently'] . ' ' . $entry_data[$i]['topic_currently2'] . ' ]<br />'; 
	} 

	$entry_text .= $entry_data[$i]['post_text'];
	$entry_text = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $entry_text);
	$entry_text = str_replace("\n", "\n<br />\n", $entry_text);
//	$entry_text = bbencode_first_pass($entry_text, $entry_data[$i]['bbcode_uid']);
	$entry_text = '<span style="line-height: 100%">' . $entry_text . '</span>';
	$entry_text = bbencode_second_pass($entry_text, $entry_data[$i]['bbcode_uid']);
	$entry_text = make_clickable($entry_text);
//	$entry_text = smilies_pass($entry_text);

	// Variable reassignment and reformatting for author
	$entry_author = $entry_data[$i]['username'];
	if ( $entry_data[$i]['user_id'] != -1 )
	{
	        $entry_author = '<a href="' . $basedir . 'profile.' . $phpEx . '?mode=viewprofile&u=' . $entry_data[$i]['user_id'] . '" target="_blank">' . $entry_author . '</a>';
	}
	$entry_author = htmlspecialchars ($entry_author);

	$entry_id = $entry_data[$i]['topic_id'];
	$entry_title = htmlspecialchars($entry_data[$i]['topic_title']);
	$entry_link = htmlspecialchars($basedir . "weblog_entry.$phpEx?t=$entry_id");
	$entry_time = $entry_data[$i]['topic_time'];
	echo ("	  <item>\n");
	echo ("	    <title>" . $entry_title . "</title>\n");
	echo ("	    <link>" . $entry_link . "</link>\n");
	echo ("	    <description>$entry_text</description>\n");
	echo ("	    <author>" . $entry_author . "</author>\n");
	echo ("	    <pubDate>" . gmdate('D, d M Y H:i:s', $entry_time) . " GMT</pubDate>\n");
	echo ("	  </item>\n");
}

echo ("	</channel>\n");
echo ("  </rss>\n");
?>