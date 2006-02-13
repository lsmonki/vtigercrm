<?php
/***************************************************************************
 *                                weblog.php
 *                            -------------------
 *   begin                : Saturday, November 1, 2003
 *   copyright            : (C) 2003 Hyperion
 *   email                : vinng86@hotmail.com
 *
 *   $Id: weblog.php,v 1.9 2005/01/04 15:04:13 saraj Exp $
 *
 *   modified from Smartor's portal.php by hyperion -
 *      http://www.phpbb.com/phpBB/profile.php?mode=viewprofile&u=6288 *
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
else if( isset($HTTP_GET_VARS[POST_FORUM_URL]) || isset($HTTP_POST_VARS[POST_FORUM_URL]) )
{
	$weblog_id = ( isset($HTTP_GET_VARS[POST_FORUM_URL]) ) ? intval($HTTP_GET_VARS[POST_FORUM_URL]) : intval($HTTP_POST_VARS[POST_FORUM_URL]);
	$weblog_id = intval($weblog_id);
}
else
{
	$weblog_id = "";
}

if( isset($HTTP_GET_VARS['previous']) || isset($HTTP_POST_VARS['previous']) )
{
	$previous = ( isset($HTTP_GET_VARS['previous']) ) ? intval($HTTP_GET_VARS['previous']) : intval($HTTP_POST_VARS['previous']);
	$previous = intval($previous);
}
else
{
	$previous = 0;
}

if( isset($HTTP_GET_VARS['month']) || isset($HTTP_POST_VARS['month']) )
{
	$month = ( isset($HTTP_GET_VARS['month']) ) ? intval($HTTP_GET_VARS['month']) : intval($HTTP_POST_VARS['month']);
	$month = intval($month);
}
else
{
	$month = create_date("m", time(), $board_config['board_timezone']);
}

if( isset($HTTP_GET_VARS['day']) || isset($HTTP_POST_VARS['day']) )
{
	$day = ( isset($HTTP_GET_VARS['day']) ) ? intval($HTTP_GET_VARS['day']) : intval($HTTP_POST_VARS['day']);
	$day = intval($day);
}

if( isset($HTTP_GET_VARS['year']) || isset($HTTP_POST_VARS['year']) )
{
	$year = ( isset($HTTP_GET_VARS['year']) ) ? intval($HTTP_GET_VARS['year']) : intval($HTTP_POST_VARS['year']);
	$year = intval($year);
}
else
{
	$year = create_date("Y", time(), $board_config['board_timezone']);
}

if ( $year <= 1970 )
{
	$year = create_date("Y", time(), $board_config['board_timezone']);
}

$months = array($lang['datetime']['January'],$lang['datetime']['February'],$lang['datetime']['March'],$lang['datetime']['April'],$lang['datetime']['May'],$lang['datetime']['June'], $lang['datetime']['July'], $lang['datetime']['August'], $lang['datetime']['September'], $lang['datetime']['October'], $lang['datetime']['November'], $lang['datetime']['December']);

//
// Check if the user has actually sent a weblog ID with his/her request
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
	message_die(GENERAL_MESSAGE, 'Weblog_not_exist');
}

//Added for weblog category by Rajkumar
if( isset($HTTP_GET_VARS['wc']) || isset($HTTP_POST_VARS['wc']) )
{
	$blog_cat = ( isset($HTTP_POST_VARS['wc']) ) ? $HTTP_POST_VARS['wc'] : $HTTP_GET_VARS['wc'];
	$blog_cat_sql = " AND t.weblog_cat_id = $blog_cat ";
}
else
{
	$blog_cat = '';
}

//
// If the query doesn't return any rows this isn't a valid weblog. Inform
// the user.
//
if( !($weblog_row = $db->sql_fetchrow($result)) )
{
	message_die(GENERAL_MESSAGE, 'Weblog_not_exist');
}

//
// Start auth check
//
$is_auth = array();
$is_auth = auth(AUTH_ALL, $weblog_id, $userdata, $weblog_row);

if ( !$is_auth['auth_read'] )
{
	//
	// The user is not authed to read this weblog ...
	//
	$message = $lang['Weblog_not_allowed'];

	message_die(GENERAL_MESSAGE, $message);
}
//
// End of auth check
//


$sql = "SELECT u.*
	FROM " . AUTH_ACCESS_TABLE . " aa, " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE . " g, " . USERS_TABLE . " u
	WHERE aa.forum_id = $weblog_id
		AND aa.auth_mod = " . TRUE . "
		AND ug.group_id = aa.group_id
		AND g.group_single_user = 1
		AND u.user_id = ug.user_id
		AND g.group_id = aa.group_id
	GROUP BY u.user_id, u.username
	ORDER BY u.user_id";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, "Could not obtain weblog owner information for this weblog.", '', __LINE__, __FILE__, $sql);
}

while( $row = $db->sql_fetchrow($result) )
{
	$weblog_ownerdata = get_userdata($row['user_id']);
}

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

// Let the weblog owner see the new entry button
if( $is_auth['auth_mod'] )
{
	$new_entry = '<img src="weblogs/styles/' . $weblog_ownerdata['weblog_style'] . '/entry.gif" border="0">';
	$new_edit = '<img src="weblogs/styles/' . $weblog_ownerdata['weblog_style'] . '/icon_edit.gif" border="0">';
	$category = 'Categories';

	// Update here allows better control over links. Kept the old one to maintain compatibility with older weblogs.
	$template->assign_vars(array(
		'U_NEW_ENTRY' => append_sid("weblog_posting.$phpEx?mode=newtopic&amp;" . POST_WEBLOG_URL . "=$weblog_id"),
		'U_NEW_EDIT' => append_sid("weblog_posting.$phpEx?mode=editpost&amp;" . POST_POST_URL . "=" . $postrow[$i]['post_id'] . "&amp;wc=" . $postrow[$i]['weblog_cat_id'] . "&amp;" . POST_WEBLOG_URL . "=$weblog_id"),
		'U_CATEGORY' => append_sid("weblog_category.$phpEx?" . POST_WEBLOG_URL . "=$weblog_id"),
		'NEW_ENTRY_A' => '<a href="' . append_sid("weblog_posting.$phpEx?mode=newtopic&amp;" . POST_WEBLOG_URL . "=$weblog_id").'" class="nav">',
		'NEW_ENTRY_B' => '</a>',
		'BLOG_CATEGORY' => $category,
		'NEW_ENTRY' => $new_entry,
		'NEW_EDIT' => $new_edit)
	);

	$template->assign_block_vars('switch_owner', array());
}
else
{
	$new_entry = '';
	$new_edit  = '';
}

//
// Increment the number of views for this weblog.
//
$sql = "UPDATE " . FORUMS_TABLE . "
	SET forum_views = '" . ($weblog_row['forum_views'] + 1) . "'
	WHERE forum_id = $weblog_id";
if( !$result = $db->sql_query($sql) )
{
	message_die(GENERAL_ERROR, "Couldn't update weblog views information.", "", __LINE__, __FILE__, $sql);
}

//ADDED by Rajkumar for Category
$sql="SELECT weblog_cat_id , weblog_cat_name from ". WEBLOG_CATEGORIES ." WHERE forum_id=$weblog_id";
if( !$result = $db->sql_query($sql) )
{
        message_die(GENERAL_ERROR, "Couldn't obtain weblog category information.", "", __LINE__, __FILE__, $sql);
}
while( $row = $db->sql_fetchrow($result) )
{
        $blog_cat_dat[] = $row;
}
$blog_cat_count = count($blog_cat_dat);
$blog_cat_id = array();
$blog_cat_name = array();
$catname='';
if ($blog_cat == '' && $blog_cat_count >= 1)
{
	 $catname = " All " ;
}
elseif ($blog_cat)
{
    $catname = "<a href=" . append_sid("weblog.$phpEx?w=$weblog_id") . "> All </a> ";
}
else
{
	$catname='';
}
for ($i = 0; $i < $blog_cat_count; $i++)
{
	$blog_cat_id[$i] = $blog_cat_dat[$i]['weblog_cat_id'];
	$blog_cat_name[$i] = $blog_cat_dat[$i]['weblog_cat_name'];

	if ($blog_cat == $blog_cat_id[$i])
         $catname .= "| ". $blog_cat_name[$i]." ";
    else
         $catname .= "| <a href=" . append_sid("weblog.$phpEx?w=$weblog_id&wc=$blog_cat_id[$i]") . ">" . $blog_cat_name[$i] ." </a> ";

}

//
// Start output of page
//
define('IN_WEBLOGS', true);
$page_title = $weblog_row['forum_name'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => '../../weblogs/weblog_' . $weblog_ownerdata['user_id'] . '.cfg')
);

if ( $weblog_ownerdata['weblog_show_profile'] == WEBLOG_SHOW )
{
	$template->assign_block_vars('switch_show_profile', array());
}
if ( $weblog_ownerdata['weblog_contact_info'] == WEBLOG_SHOW )
{
	$template->assign_block_vars('switch_contact_user', array());
}
if ( $weblog_ownerdata['weblog_show_info'] == WEBLOG_SHOW )
{
	$template->assign_block_vars('switch_show_info', array());
}
if ( $weblog_ownerdata['weblog_show_desc'] == WEBLOG_SHOW )
{
	$template->assign_block_vars('switch_user_center', array());
}
else if ( $weblog_ownerdata['weblog_show_desc'] == WEBLOG_HIDE )
{
	$template->assign_block_vars('switch_user_right', array());
}

// Prepare Avatar Image
$avatar_img = '';
if ( $weblog_ownerdata['user_avatar_type'] && $weblog_ownerdata['user_allowavatar'] )
{
	switch( $weblog_ownerdata['user_avatar_type'] )
	{
		case USER_AVATAR_UPLOAD:
			$avatar_img = ( $board_config['allow_avatar_upload'] ) ? '<img src="' . $board_config['avatar_path'] . '/' . $weblog_ownerdata['user_avatar'] . '" alt="" border="0" />' : '';
			break;
		case USER_AVATAR_REMOTE:
			$avatar_img = ( $board_config['allow_avatar_remote'] ) ? '<img src="' . $weblog_ownerdata['user_avatar'] . '" alt="" border="0" />' : '';
			break;
		case USER_AVATAR_GALLERY:
			$avatar_img = ( $board_config['allow_avatar_local'] ) ? '<img src="' . $board_config['avatar_gallery_path'] . '/' . $weblog_ownerdata['user_avatar'] . '" alt="" border="0" />' : '';
			break;
	}
}


$temp_url = append_sid("privmsg.$phpEx?mode=post&amp;" . POST_USERS_URL . "=" . $weblog_ownerdata['user_id']);
$pm_img = '<img src="' . $images['icon_pm'] . '" alt="' . $lang['Send_private_message'] . '" title="' . $lang['Send_private_message'] . '" border="0" />';
$pm = $lang['Send_private_message'];
$pm_a = '<a href="' . $temp_url . '">';
$pm_b = '</a>';

if ( !empty($weblog_ownerdata['user_viewemail']) || $userdata['user_level'] == ADMIN )
{
	$email_uri = ( $board_config['board_email_form'] ) ? append_sid("profile.$phpEx?mode=email&amp;" . POST_USERS_URL .'=' . $weblog_ownerdata['user_id']) : 'mailto:' . $weblog_ownerdata['user_email'];

	$email_img = '<img src="' . $images['icon_email'] . '" alt="' . $lang['Send_email'] . '" title="' . $lang['Send_email'] . '" border="0" />';
	$email = $lang['Send_email'];
	$email_a = '<a href="' . $email_uri . '">';
	$email_b = '</a>';
}
else
{
	$email_img = '&nbsp;';
	$email = '&nbsp;';
	$email_a = '';
	$email_b = '';
}

$www_img = ( $weblog_ownerdata['user_website'] ) ? '<a href="' . $weblog_ownerdata['user_website'] . '" target="_userwww"><img src="' . $images['icon_www'] . '" alt="' . $lang['Visit_website'] . '" title="' . $lang['Visit_website'] . '" border="0" /></a>' : '&nbsp;';
$www = ( $weblog_ownerdata['user_website'] ) ? '<a href="' . $weblog_ownerdata['user_website'] . '" target="_userwww">' . $weblog_ownerdata['user_website'] . '</a>' : '&nbsp;';

if ( !empty($weblog_ownerdata['user_icq']) )
{
	$icq_status_img = '<a href="http://wwp.icq.com/' . $weblog_ownerdata['user_icq'] . '#pager"><img src="http://web.icq.com/whitepages/online?icq=' . $weblog_ownerdata['user_icq'] . '&img=5" width="18" height="18" border="0" /></a>';
	$icq_img = '<img src="' . $images['icon_icq'] . '" alt="' . $lang['ICQ'] . '" title="' . $lang['ICQ'] . '" border="0" />';
	$icq =  $lang['ICQ'];
	$icq_a = '<a href="http://wwp.icq.com/scripts/search.dll?to=' . $weblog_ownerdata['user_icq'] . '">';
	$icq_b = '</a>';
}
else
{
	$icq_status_img = '&nbsp;';
	$icq_img = '&nbsp;';
	$icq = '&nbsp;';
	$icq_a = '';
	$icq_b = '';
}

$aim_img = ( $weblog_ownerdata['user_aim'] ) ? '<img src="' . $images['icon_aim'] . '" alt="' . $lang['AIM'] . '" title="' . $lang['AIM'] . '" border="0" />' : '&nbsp;';
$aim = ( $weblog_ownerdata['user_aim'] ) ? $lang['AIM'] : '&nbsp;';
$aim_a = ( $weblog_ownerdata['user_aim'] ) ? '<a href="aim:goim?screenname=' . $weblog_ownerdata['user_aim'] . '&amp;message=Hello+Are+you+there?">' : '';
$aim_b = ( $weblog_ownerdata['user_aim'] ) ? '</a>' : '';

$msn_img = ( $weblog_ownerdata['user_msnm'] ) ? $weblog_ownerdata['user_msnm'] : '&nbsp;';
$msn = $msn_img;

$yim_img = ( $weblog_ownerdata['user_yim'] ) ? '<img src="' . $images['icon_yim'] . '" alt="' . $lang['YIM'] . '" title="' . $lang['YIM'] . '" border="0" />' : '';
$yim = ( $weblog_ownerdata['user_yim'] ) ? $lang['YIM'] : '';
$yim_a = ( $weblog_ownerdata['user_yim'] ) ? '<a href="http://edit.yahoo.com/config/send_webmesg?.target=' . $weblog_ownerdata['user_yim'] . '&amp;.src=pg">' : '';
$yim_b = ( $weblog_ownerdata['user_yim'] ) ? '</a>' : '';

// Birthday Mod support - Taken and modified directly from Niels' Birthday Mod
if ( $weblog_config['birthday_mod'] == 1 )
{
	$this_year = create_date('Y', time(), $board_config['board_timezone']);
	$this_date = create_date('md', time(), $board_config['board_timezone']);

	if ( $weblog_ownerdata['user_birthday'] != 999999 )
	{
		$poster_birthdate = realdate('md', $weblog_ownerdata['user_birthday']);
		if ( $weblog_config['birthday_zodiac_mod'] == 1 )
		{
			$n=0;
			while ( $n<26 )
			{
				if ( $poster_birthdate >= $zodiacdates[$n] & $poster_birthdate <= $zodiacdates[$n+1] )
				{
					$zodiac = $lang[$zodiacs[($n/2)]];
					$u_zodiac = $images[$zodiacs[($n/2)]];
					$zodiac_img = '<img src="' . $u_zodiac . '" alt="' . $zodiac . '" title="' . $zodiac . '" align="absmiddle" border="0" />';
					$n = 26;
				}
				else
				{
					$n = $n + 2;
				}
			}
		}
		$poster_age = $this_year - realdate ('Y',$weblog_ownerdata['user_birthday']);
		if ( $this_date < $poster_birthdate )
		{
			$poster_age--;
		}
	}
	else
	{
		$zodiac = '';
		$u_zodiac = '';
		$zodiac_img = '';
		$poster_age = '';
	}
}

//
// Fetch Posts from Weblog Forum
//

// Get the user's clearence levels
$access_level = 0;
if ( $userdata['session_logged_in'] )
{
	$access_level++;
	$group_id = intval($weblog_ownerdata['weblog_group']);
	//
	// Get user information for this group
	//
	$sql = "SELECT ug.group_id, ug.user_id, ug.user_pending
		FROM " . USER_GROUP_TABLE . " ug
		WHERE ug.group_id = $group_id
			AND ug.user_id = " . $userdata['user_id'] . "
		ORDER BY ug.user_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Error getting user list for group', '', __LINE__, __FILE__, $sql);
	}

	$group_members = $db->sql_fetchrowset($result);

	if ( count($group_members) == 1 || $userdata['user_level'] == ADMIN )
	{
		$access_level++;
	}

	if ( $weblog_ownerdata['user_id'] == $userdata['user_id'] || $userdata['user_level'] == ADMIN )
	{
		$access_level++;
	}
}

$entry_sql = 't.topic_first_post_id, t.topic_poster, t.topic_status, t.topic_mood, t.topic_currently1, t.topic_currently2, t.topic_time, t.topic_title, t.topic_replies, t.topic_id, t.topic_auth, t.topic_vote, t.weblog_cat_id, p.post_id, p.enable_smilies, u.username, u.user_id, pt.post_text, pt.post_id, pt.bbcode_uid';

if ( defined('ATTACH_VERSION') )
{
	$entry_sql .= ', t.topic_attachment';
}

//
// Fetch Entries
//

// Account for the "Go Back" or "Go Forward"
if ( !$day )
{
	$limit_sql = "LIMIT " . ($weblog_ownerdata['weblog_show_entries'] + $previous);
}

$sql = "SELECT $entry_sql
	FROM " . TOPICS_TABLE . " AS t, " . USERS_TABLE . " AS u, " . POSTS_TEXT_TABLE . " AS pt, " . POSTS_TABLE . " AS p
	WHERE t.forum_id IN (" . $weblog_id . ")
		AND t.topic_time <= " . time() . "
		AND t.topic_poster = u.user_id
		AND t.topic_first_post_id = pt.post_id
		AND t.topic_first_post_id = p.post_id
		AND t.topic_status <> 2
		AND t.topic_auth <= " . $access_level;
	$sql .= "$blog_cat_sql";
	$sql .= " ORDER BY t.topic_time DESC " . $limit_sql;
if(!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Could not query weblog entry information', '', __LINE__, __FILE__, $sql);
}

$i = 0;
while ( $row = $db->sql_fetchrow($result) )
{
	$entry_data[$i] = $row;
	$i++;
}

if ( defined('ATTACH_VERSION') )
{
	$post_id_array = array();

	for ($i = 0; $i < count($entry_data); $i++)
	{
		if ($entry_data[$i]['topic_attachment'] == 1)
		{
			$post_id_array[] = $entry_data[$i]['post_id'];
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

		init_display_template('body', '{postrow.ATTACHMENTS}', '../../weblogs/styles/' . $weblog_ownerdata['weblog_style'] . '/attach_body.cfg');

		init_complete_extensions_data();

		$template->assign_vars(array(
			'L_POSTED_ATTACHMENTS' => $lang['Posted_attachments'],
			'L_KILOBYTE' => $lang['KB'])
		);
	}
}

for ($i = $previous; $i < count($entry_data); $i++)
{
	//
	// Prepare every entry
	//
	$entry_data[$i]['post_text'] = stripslashes($entry_data[$i]['post_text']);
	$entry_data[$i]['post_text'] = bbencode_second_pass($entry_data[$i]['post_text'], $entry_data[$i]['bbcode_uid']);

	// Smilies
	if ($entry_data[$i]['enable_smilies'] == 1)
	{
		$entry_data[$i]['post_text'] = smilies_pass($entry_data[$i]['post_text']);
	}
	$entry_data[$i]['post_text'] = make_clickable($entry_data[$i]['post_text']);

	// Define censored word matches
	$orig_word = array();
	$replacement_word = array();
	obtain_word_list($orig_word, $replacement_word);

	// Censor text and title
	if (count($orig_word))
	{
		$entry_data[$i]['topic_title'] = preg_replace($orig_word, $replacement_word, $entry_data[$i]['topic_title']);
		$entry_data[$i]['post_text'] = preg_replace($orig_word, $replacement_word, $entry_data[$i]['post_text']);
	}
	$entry_data[$i]['post_text'] = nl2br($entry_data[$i]['post_text']);

	$icon = '';

# Code added to display the hidden and poll tags for the posts - Godwin -->
 	 
 	                 if( $entry_data[$i]['topic_auth'] == 3 )
 	                 {
 	                         $topic_type = $lang['Post_Hidden'] . ' ';
 	                 }
			 else
 	                 {
 	                         $topic_type='';
 	                 }
 	                 if( $entry_data[$i]['topic_vote'] == 1 )
 	                 {
 	                         $topic_type = $lang['Topic_Poll'] . ' ';
 	                 }
 	 
# Godwin <--

	// Mood Icons
	$mood = $entry_data[$i]['topic_mood'] - 1;
	if ( $mood_data[$mood]['mood'] != $lang['Weblog_none'] && $mood_data[$mood]['mood'] != '' && $mood > 0 )
	{
		$icon = '[ <b>' . $lang['Weblog_mood'] . ':</b> <img src="images/weblogs/' . $mood_data[$mood]['image_url'] . '" alt="' . $mood_data[$mood]['mood'] . '" border="0"> ' . $mood_data[$mood]['mood'] . ' ]<br />';
	}

	// Currently Icons
	$currently = $entry_data[$i]['topic_currently1'] - 1;
	if ( $currently_data[$currently]['image_url'] != '' && $currently_data[$currently]['currently'] != $lang['Weblog_none'] && $currently_data[$currently]['currently'] != $lang['Weblog_custom'] && $currently > 0 )
	{
		$icon .= '[ <img src="images/weblogs/' . $currently_data[$currently]['image_url'] . '" border="0">&nbsp;<b>' . $lang['Weblog_currently'] . ':</b> ' . $currently_data[$currently]['currently'] . ' ' . $entry_data[$i]['topic_currently2'] . ' ]<br />';
	}

	$time = '';
	// Say Today At, or Yesterday At instead of the the time - borrowed from the Today At/Yesterday At Mod
	if ( $board_config['time_today'] < $entry_data[$i]['topic_time'])
	{
		$time = sprintf($lang['Today_at'], create_date($board_config['default_timeformat'], $entry_data[$i]['topic_time'], $board_config['board_timezone'])) . '';
	}
	else if ( $board_config['time_yesterday'] < $entry_data[$i]['topic_time'])
	{
		$time = sprintf($lang['Yesterday_at'], create_date($board_config['default_timeformat'], $entry_data[$i]['topic_time'], $board_config['board_timezone'])) . '';
	}
	else
	{
		$time = create_date($board_config['default_dateformat'], $entry_data[$i]['topic_time'], $board_config['board_timezone']);
	}

	//Month variable added to fix calendar search post - Isaac
	if ( $day && $year && $month)
	{
		if ( create_date("d", $entry_data[$i]['topic_time'], $board_config['board_timezone']) == $day && create_date("Y", $entry_data[$i]['topic_time'], $board_config['board_timezone']) == $year && create_date("m", $entry_data[$i]['topic_time'], $board_config['board_timezone']) == $month )
		{
			// Output
			$template->assign_block_vars('postrow', array(
				'ICON' => $icon,
				'TITLE' => $entry_data[$i]['topic_title'],
				'POSTER' => $entry_data[$i]['username'],
				'TIME' => $time,
				'TEXT' => $entry_data[$i]['post_text'],
				'POST_COMMENT' => ( $entry_data[$i]['topic_status'] != TOPIC_LOCKED ) ? sprintf($weblog_ownerdata['weblog_post_comment_name'], $entry_data[$i]['topic_replies']) : '',
				'REPLIES' => ( $entry_data[$i]['topic_status'] != TOPIC_LOCKED ) ? sprintf($weblog_ownerdata['weblog_comment_name'], $entry_data[$i]['topic_replies']) : '',
				'U_VIEW_COMMENTS' => ( $entry_data[$i]['topic_status'] != TOPIC_LOCKED ) ? append_sid('weblog_entry.' . $phpEx . '?' . POST_ENTRY_URL . '=' . $entry_data[$i]['topic_id']) : '',
				'U_EDIT_POST' => ( $entry_data[$i]['topic_status'] != TOPIC_LOCKED ) ? append_sid('weblog_posting.' . $phpEx . '?mode=editpost&amp;' . POST_POST_URL . '=' . $entry_data[$i]['post_id'] . '&amp;wc=' . $entry_data[$i]['weblog_cat_id'] . '&amp;' . POST_WEBLOG_URL . '=' . $weblog_id) : '',
				'U_POST_COMMENT' => ( $entry_data[$i]['topic_status'] != TOPIC_LOCKED ) ? append_sid('weblog_posting.' . $phpEx . '?mode=reply&amp;' . POST_ENTRY_URL . '=' . $entry_data[$i]['topic_id']) : '',
				'ENTRY_ATTACHMENT_IMG' => ( defined('ATTACH_VERSION') ) ? topic_attachment_image($entry_data[$i]['topic_attachment']) : "")
			);
		}
	}
	else
	{
		// Output
		$template->assign_block_vars('postrow', array(
			'ICON' => $icon,
			'TITLE' => $entry_data[$i]['topic_title'],
			'POSTER' => $entry_data[$i]['username'],
			'TIME' => $time,
			'TOPIC_TYPE' => $topic_type,            // Godwin - to display poll & hidden tag
			'TEXT' => $entry_data[$i]['post_text'],
			'POST_COMMENT' => ( $entry_data[$i]['topic_status'] != TOPIC_LOCKED ) ? sprintf($weblog_ownerdata['weblog_post_comment_name'], $entry_data[$i]['topic_replies']) : '',
			'REPLIES' => ( $entry_data[$i]['topic_status'] != TOPIC_LOCKED ) ? sprintf($weblog_ownerdata['weblog_comment_name'], $entry_data[$i]['topic_replies']) : '',
			'U_VIEW_COMMENTS' => ( $entry_data[$i]['topic_status'] != TOPIC_LOCKED ) ? append_sid('weblog_entry.' . $phpEx . '?' . POST_ENTRY_URL . '=' . $entry_data[$i]['topic_id']) : '',
			'U_EDIT_POST' => ( $entry_data[$i]['topic_status'] != TOPIC_LOCKED ) ? append_sid('weblog_posting.' . $phpEx . '?mode=editpost&amp;' . POST_POST_URL . '=' . $entry_data[$i]['post_id'] . '&amp;wc=' . $entry_data[$i]['weblog_cat_id'] . '&amp;' . POST_WEBLOG_URL . '=' . $weblog_id) : '',
			'U_POST_COMMENT' => ( $entry_data[$i]['topic_status'] != TOPIC_LOCKED ) ? append_sid('weblog_posting.' . $phpEx . '?mode=reply&amp;' . POST_ENTRY_URL . '=' . $entry_data[$i]['topic_id']) : '',
			'ENTRY_ATTACHMENT_IMG' => ( defined('ATTACH_VERSION') ) ? topic_attachment_image($entry_data[$i]['topic_attachment']) : "")
		);

		if ( defined('ATTACH_VERSION') )
		{
			display_attachments($entry_data[$i]['post_id']);
		}
	}
}

//
// Prepare the calendar - some parts were borrowed the the Calendar Mod by WebSnail
//
$firstday =  (date('w', (mktime(0,0,0,$month,1,$year))) - $cal_config['week_start']) % 7;
$firstday = ( $firstday < 0 ) ? ($firstday + 7) : $firstday;

$lastday = date('t',  mktime(0,0,0,$month,1,$year));
$end_day = 7 - (($firstday + $lastday) % 7);
$end_day = ( $end_day == 7 ) ? 0 : $end_day;	// day 7 same as day 0

// Fetch the topic time, for use with the calendar
$sql = "SELECT t.topic_first_post_id, t.topic_poster, t.topic_status, t.topic_time, t.topic_auth, p.post_id, u.user_id, pt.post_id
	FROM " . TOPICS_TABLE . " AS t, " . USERS_TABLE . " AS u, " . POSTS_TEXT_TABLE . " AS pt, " . POSTS_TABLE . " AS p
	WHERE t.forum_id IN (" . $weblog_id . ")
		AND t.topic_time <= " . time() . "
		AND t.topic_poster = u.user_id
		AND t.topic_first_post_id = pt.post_id
		AND t.topic_first_post_id = p.post_id
		AND t.topic_status <> 2
		AND t.topic_auth <= " . $access_level;
	$sql .= "$blog_cat_sql";
	$sql .= " ORDER BY t.topic_time DESC";
if(!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Could not query entry time information', '', __LINE__, __FILE__, $sql);
}

$i = 0;
while ( $row = $db->sql_fetchrow($result) )
{
	$entry_time[$i] = $row;
	$i++;
}

// Make table cells for "empty" days (The blank squares in the calendar)
for ( $i = 0; $i < $firstday; $i++ )
{
	$template->assign_block_vars('no_day', array());
}

// Make table cells with numbers in them. Make a new line whenever all 7 spaces have been filled
for ($currentday = 1; $currentday <= $lastday; $currentday++)
{
	$countDay = 0;
	for ($i = 0; $i < count($entry_time); $i++)
	{
		if ( $currentday == create_date("j", $entry_time[$i]['topic_time'], $board_config['board_timezone']) && $month == create_date("n", $entry_time[$i]['topic_time'], $board_config['board_timezone']) && $year == create_date("Y", $entry_time[$i]['topic_time'], $board_config['board_timezone']))
			$countDay++;
	}

	$template->assign_block_vars('day_cell', array(
		'NUM_DAY' => ( $countDay != 0 ) ? '<a href="weblog.' . $phpEx . '?f=' . $weblog_id . '&amp;month=' . $month . '&amp;year=' . $year . '&amp;day=' . $currentday . '" class="nav"><u>' . $currentday . '</u></a>' : $currentday,
		'WEEK_ROW' => ( ($currentday + $firstday) % 7 == 0 ) ? '</tr><tr>' : '')
	);
}

// Make table cells for "empty" days (Fills in the last squares
for ( $i = 0; $i < $end_day; $i++ )
{
	$template->assign_block_vars('no_day2', array());
}

// For use with the arrows next to the month/year.
$nextmonth = ($month < 12) ? ($month + 1) : 1;
$nextyear = ($month < 12) ? $year : ($year + 1);

$lastmonth = ($month > 1) ? ($month - 1) : 12;
$lastyear = ($month > 1) ? $year: ($year - 1);

$admin_link = ( $userdata['user_level'] == ADMIN ) ? '<a href="admin/index.' . $phpEx . '?sid=' . $userdata['session_id'] . '">' . $lang['Admin_panel'] . '</a><br /><br />' : '';
//
// Generate page
//
$template->assign_vars(array(
	'FORUM_NAME' => $weblog_row['forum_name'],
	'L_ABOUT_USER' => sprintf($lang['About_user'], $weblog_ownerdata['username']),
	'L_JOINED' => $lang['Joined'],
	'L_CONTACT' => $lang['Contact'],
	'L_EMAIL_ADDRESS' => $lang['Email_address'],
	'L_EMAIL' => $lang['Email'],
	'L_PM' => $lang['Private_Message'],
	'L_ICQ_NUMBER' => $lang['ICQ'],
	'L_YAHOO' => $lang['YIM'],
	'L_AIM' => $lang['AIM'],
	'L_MESSENGER' => $lang['MSNM'],
	'L_LOCATION' => $lang['Location'],
	'L_OCCUPATION' => $lang['Occupation'],
	'L_INTERESTS' => $lang['Interests'],
	'L_WEBLOG_STARTED' => $lang['Weblog_started'],
	'U_USERPROFILE' => 'profile.php?mode=viewprofile&amp;u=' . $weblog_ownerdata['user_id'] . '',
	'LOCATION' => ( $weblog_ownerdata['user_from'] ) ? $weblog_ownerdata['user_from'] : '&nbsp;',
	'OCCUPATION' => ( $weblog_ownerdata['user_occ'] ) ? $weblog_ownerdata['user_occ'] : '&nbsp;',
	'INTERESTS' => ( $weblog_ownerdata['user_interests'] ) ? $weblog_ownerdata['user_interests'] : '&nbsp;',
	'AVATAR_IMG' => $avatar_img,

	'L_VIEW_ENTRIES' => $lang['View_All_Entries'],
	'L_WEBLOG_OWNER' => $lang['Weblog_Owner'],
	'L_WEBLOG' => $lang['Weblog'],
	'L_WEBLOGS' => $lang['Weblogs'],
	'L_ENTRIES' => $lang['Entries'],
	'L_AGE' => $lang['Weblog_age'],
	'L_DAYS' => $lang['Days'],
	'L_POSTS' => $lang['Posts'],
	'L_VISITS' => $lang['Weblog_visits'],
	'L_NEXT_MONTH' => $lang['Weblog_next_month'],
	'L_NEXT_YEAR' => $lang['Weblog_next_year'],
	'L_PREVIOUS_MONTH' => $lang['Weblog_previous_month'],
	'L_PREVIOUS_YEAR' => $lang['Weblog_previous_year'],
	'L_GO' => $lang['Go'],

	'L_CALENDAR' => $lang['Weblog_calendar'],
	'U_MONTH_B' => 'weblog.' . $phpEx . '?f=' . $weblog_id . '&amp;previous=' . $previous . '&amp;month=' . $lastmonth . '&amp;year=' . $year,
	'U_MONTH_F' => 'weblog.' . $phpEx . '?f=' . $weblog_id . '&amp;previous=' . $previous . '&amp;month=' . $nextmonth . '&amp;year=' . $year,
	'U_YEAR_B' => 'weblog.' . $phpEx . '?f=' . $weblog_id . '&amp;previous=' . $previous . '&amp;month=' . $month . '&amp;year=' . ($year-1),
	'U_YEAR_F' => 'weblog.' . $phpEx . '?f=' . $weblog_id . '&amp;previous=' . $previous . '&amp;month=' . $month . '&amp;year=' . ($year+1),
	'U_PAST' => append_sid("weblog2.$phpEx?" . POST_WEBLOG_URL . '=' . $weblog_id),

	'U_WEBLOG_RSS' => append_sid("weblog_rss.$phpEx?" . POST_WEBLOG_URL . '=' . $weblog_id),

	'USERNAME' => $weblog_ownerdata['username'],
	'JOINED' => create_date($lang['DATE_FORMAT'], $weblog_ownerdata['user_regdate'], $board_config['board_timezone']),
	'START_DATE' => create_date($lang['DATE_FORMAT'], $weblog_ownerdata['weblog_start_date'], $board_config['board_timezone']),
	'WEBLOG_DESC' => $weblog_row['forum_desc'],
	'WEBLOG_POSTS' => $weblog_row['forum_posts'],
	'WEBLOG_ENTRIES' => $weblog_row['forum_topics'],
	'WEBLOG_AGE' => intval((time() - $weblog_ownerdata['weblog_start_date'])/(60*60*24)),
	'WEBLOG_VISITS' => $weblog_row['forum_views'],

	'MONTH' => $months[$month-1],
	'YEAR' => $year,
	'FORWARD' => ( $previous != 0 && !$day ) ? '<a href="weblog.php?' . POST_WEBLOG_URL . '=' . $weblog_id . '&amp;previous=' . ($previous - $weblog_ownerdata['weblog_show_entries']) . '" class="nav">' . $lang['Weblog_forward'] . '</a>' : $lang['Weblog_forward'],
	'BACK' => ( ($previous + $weblog_ownerdata['weblog_show_entries']) <= count($entry_data) && !$day ) ? '<a href="weblog.php?' . POST_WEBLOG_URL . '=' . $weblog_id . '&amp;previous=' . ($previous + $weblog_ownerdata['weblog_show_entries']) . '" class="nav">' . $lang['Weblog_back'] . '</a>' : $lang['Weblog_back'],

	'L_LOGON' => $lang['Last_logon'],
	'LAST_LOGON' => $last_logon,

	'MOD_VERSION' => $mod_version,

	'PHPBB_VERSION' => '2' . $board_config['version'],
	'TRANSLATION_INFO' => ( isset($lang['TRANSLATION_INFO']) ) ? $lang['TRANSLATION_INFO'] : '',
	'ADMIN_LINK' => $admin_link,

	//'PM_IMG' => $pm_img,
	'PM' => $pm,
	'PM_A' => $pm_a,
	'PM_B' => $pm_b,
	//'EMAIL_IMG' => $email_img,
	'EMAIL' => $email,
	'EMAIL_A' => $email_a,
	'EMAIL_B' => $email_b,
	//'ICQ_STATUS_IMG' => $icq_status_img,
	//'ICQ_IMG' => $icq_img,
	'ICQ' => $icq,
	'ICQ_A' => $icq_a,
	'ICQ_B' => $icq_b,
	//'AIM_IMG' => $aim_img,
	'AIM' => $aim,
	'AIM_A' => $aim_a,
	'AIM_B' => $aim_b,
	//'MSN_IMG' => $msn_img,
	'MSN' => $msn,
	'MSN_A' => $msn_a,
	'MSN_B' => $msn_b,
	//'YIM_IMG' => $yim_img,
	'YIM' => $yim,
	'YIM_A' => $yim_a,
	'YIM_B' => $yim_b,
	//'WWW_IMG' => $www_img,
	'WWW' => $www,

	'S_AUTH_LIST' => $s_auth_can)
);

//
// Mod Support - mostly borrowed and modified
//
if ( $weblog_config['birthday_mod'] == 1 )
{
	$template->assign_vars(array(
		'L_BIRTHDAY' => $lang['Birthday'],
		'L_AGE' => $lang['Age'],
		'BIRTHDAY' => ( $weblog_ownerdata['user_birthday'] != 999999 ) ? realdate($lang['DATE_FORMAT'], $weblog_ownerdata['user_birthday']) : $lang['No_birthday_specify'],
		'AGE' => $poster_age)
	);
}

if ( $weblog_config['birthday_zodiac_mod'] == 1 )
{
	$template->assign_vars(array(
		'ZODIAC_IMG' => $zodiac_img,
		'ZODIAC' => $zodiac,
		'U_ZODIAC' => $u_zodiac,
		'L_ZODIAC' => $lang['Zodiac'])//($zodiac) ? $lang['Zodiac'] . ': ' : '')
	);
}

if ( $weblog_config['gender_mod'] == 1 )
{
	if ( !empty($weblog_ownerdata['user_gender']))
	{
		switch ($weblog_ownerdata['user_gender'])
		{
			case 1:
				$gender = $lang['Male'];
				break;
			case 2:
				$gender = $lang['Female'];
				break;
			default:
				$gender = $lang['No_gender_specify'];
           }
	}
	else
	{
		$gender = $lang['No_gender_specify'];
	}

	$template->assign_vars(array(
		'GENDER' => $gender,
		'L_GENDER' => $lang['Gender'])
	);

}

if ( $weblog_config['last_visit_mod'] == 1 )
{
	$template->assign_vars(array(
		'L_LOGON' => $lang['Last_logon'],
		'LAST_LOGON' => ($userdata['user_level'] == ADMIN || (!$board_config['hidde_last_logon'] && $weblog_ownerdata['user_allow_viewonline'])) ? (($weblog_ownerdata['user_lastlogon'])? create_date($board_config['default_dateformat'], $weblog_ownerdata['user_lastlogon'], $board_config['board_timezone']):$lang['Never_last_logon']):$lang['Hidde_last_logon'])
	);
}

//
// Generate the page
//
$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>
