<?php
/***************************************************************************
 *                               weblogs.php
 *                            ------------------
 *   begin                : Saturday, Feb 13, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id$
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



$phpbb_root_path = './';
define('IN_PHPBB', true);
if (!file_exists($phpbb_root_path . 'extension.inc'))
{
    die ('<tt><b>phpBB Fetch All:</b>
          $phpbb_root_path is wrong and does not point to your forum.</tt>');
}

include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'includes/bbcode.'.$phpEx);
include($phpbb_root_path . 'includes/weblogs_common.'.$phpEx);
include($phpbb_root_path . 'includes/functions_search.'.$phpEx);

include_once ($phpbb_root_path . 'mods/phpbb_fetch_all/common.' . $phpEx);
include_once ($phpbb_root_path . 'mods/phpbb_fetch_all/stats.' . $phpEx);
include_once ($phpbb_root_path . 'mods/phpbb_fetch_all/users.' . $phpEx);
include_once ($phpbb_root_path . 'mods/phpbb_fetch_all/polls.' . $phpEx);
include_once ($phpbb_root_path . 'mods/phpbb_fetch_all/posts.' . $phpEx);
include_once ($phpbb_root_path . 'mods/phpbb_fetch_all/forums.' . $phpEx);

$CFG['posts_span_pages']        = true;
$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$per_page = 30;

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_WEBLOGS);
init_userprefs($userdata);
//
// End session management
//

//
// Start page proper
//

//Start Sort image function
function swapImage($ocolumn,$orig_ocoloumn,$otype)
{
	$uimage = "<img src=\"images/down.gif\" border=\"0\">";
	$dimage = "<img src=\"images/up.gif\" border=\"0\">";
	if ($otype == "DESC")
	{
		$image = $dimage;
	}
	else
	{
		$image = $uimage;
	}

	if ($ocolumn == "" && $orig_ocoloumn == "topic_time")
		return $image;
	if ($ocolumn != $orig_ocoloumn)
	{
		$image = "";
	}
	return $image;
}
//End Sort image function
$sql = "SELECT c.cat_id, c.cat_title, c.cat_order
	FROM " . CATEGORIES_TABLE . " c
	WHERE c.cat_allow_weblogs = 1
	ORDER BY c.cat_order";
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query categories list', '', __LINE__, __FILE__, $sql);
}

while( $category_rows[] = $db->sql_fetchrow($result) );

if( ( $total_categories = count($category_rows) ) )
{
	//
	// Statistics
	//
	if ( $weblog_config['display_stats'] == 1 )
	{
		$sort_order= ( isset($HTTP_GET_VARS['sorder']) ) ? $HTTP_GET_VARS['sorder'] : "";
		$type= ( isset($HTTP_GET_VARS['type']) ) ? $HTTP_GET_VARS['type'] : "";
		$template->assign_block_vars('stats', array());

# Query modified - To hide hidden post in all places except users myblog page - "and t.topic_auth <> 3
# and u.weblog_visible <> 0 added" - Godwin -->

                $sql_cnt = "SELECT f.forum_id, u.user_id, t.topic_id FROM phpbb_topics t, phpbb_forums f, phpbb_users u WHERE t.forum_id=f.forum_id AND f.forum_id=u.user_weblog AND t.topic_auth <> 3 and u.weblog_visible <> 0";

# Godwin <--

		if ($sort_order == "topic_views")
		$sql_cnt .=" AND t.topic_views <> 0";

		if ( !($result = $db->sql_query($sql_cnt)) )
		{
			message_die(GENERAL_ERROR, 'Could not query forums information', '', __LINE__, __FILE__, $sql);
		}

		while( $row = $db->sql_fetchrow($result) )
		{
			$topic_cnt[] = $row['topic_id'];
		}
		$db->sql_freeresult($result);

		$total_topic_stat = count($topic_cnt);

		if ($topic_cnt)
		{
			$topic_counts = implode(', ', $topic_cnt);
		}
		else
		{
			$topic_counts=0;
		}

        	$sql = "SELECT f.*, u.*, t.topic_time, t.topic_title, t.topic_views, t.topic_id from phpbb_topics t, phpbb_forums f, phpbb_users u where t.forum_id=f.forum_id and f.forum_id=u.user_weblog and t.topic_id IN ($topic_counts)";

		if ($sort_order == "topic_views")
		$sql .=" AND t.topic_views <> 0";

		if($sort_order && $type)
		$sql .=" ORDER BY $sort_order $type ". "LIMIT $start, " . $per_page;
		else
		$sql .=" ORDER BY topic_time DESC LIMIT $start, " . $per_page;
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not query forums information', '', __LINE__, __FILE__, $sql);
		}

		while( $row = $db->sql_fetchrow($result) )
		{
			$weblog_data_stat[] = $row;
		}
		$total_forums_stat = count($weblog_data_stat);

		if ( count($weblog_data_stat) == 0 )
		{
			message_die(GENERAL_MESSAGE, $lang['No_weblogs']);
		}

		//
		// Find which forums are visible for this user
		//
		$is_auth_ary = array();
		$is_auth_ary = auth(AUTH_READ, AUTH_LIST_ALL, $userdata, $weblog_data_stat);

		$weblog_owner_data_stat = array();
		$topic_data_stat = array();
		for ($i = 0; $i < $total_forums_stat; $i++)
		{
			$weblog_id = $weblog_data_stat[$i]['forum_id'];

			// Get Weblog Owner Data
			$sql = "SELECT u.user_id, u.username, u.weblog_start_date
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
				message_die(GENERAL_ERROR, 'Could not query forum moderator information', '', __LINE__, __FILE__, $sql);
			}

			while( $row = $db->sql_fetchrow($result) )
			{
				$weblog_owner_data_stat[$i] = $row;
			}

			$sql = "SELECT f.forum_id, t.topic_time, t.topic_id
				FROM " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f
				WHERE t.forum_id = $weblog_id
					AND f.forum_id = t.forum_id
				ORDER BY f.forum_id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not query forums information', '', __LINE__, __FILE__, $sql);
			}

			while( $row = $db->sql_fetchrow($result) )
			{
				$topic_data_stat[$i] = $row;
			}
		}

		$stat_lastupdated = array();
		$stat_lastupdated_name = array();
		$stat_lastupdated_owner = array();

		// Weblog IDs for linking
		$stat_lastupdated_id = array();
		$stat_lastupdated_visits = array();
		$stat_lastupdated_topics = array();
		$stat_lastupdated_topic_title = array();
		$stat_lastupdated_topic_id = array();
		$stat_lastupdated_date = array();

		for ($i = 0; $i < $total_forums_stat; $i++)
		{
			$weblog_id = $weblog_data_stat[$i]['forum_id'];
			if($sort_order)
			{
				//message_die(GENERAL_MESSAGE,$sort_order);
				$stat_lastupdated[$i] = $weblog_data_stat[$i][$sort_order];
			}
			else
			{
				$stat_lastupdated[$i] = $weblog_data_stat[$i]['username'];
			}
			$stat_lastupdated_date[$i] = $weblog_data_stat[$i]['topic_time'];
			$stat_lastupdated_name[$i] = $weblog_data_stat[$i]['forum_name'];
			$stat_lastupdated_topic_title[$i] = $weblog_data_stat[$i]['topic_title'];
			$stat_lastupdated_topic_id[$i] = $weblog_data_stat[$i]['topic_id'];
			$stat_lastupdated_owner[$i] = $weblog_owner_data_stat[$i]['username'];
			$stat_lastupdated_id[$i] = $weblog_data_stat[$i]['forum_id'];
			$stat_lastupdated_visits[$i] = $weblog_data_stat[$i]['topic_views'];
			$stat_lastupdated_topics[$i] = $weblog_data_stat[$i]['forum_topics'];
		}
		//
		// Output all the statistics
		//
		for ($i = 0; $i < $total_forums_stat; $i++)
		{
			//$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
			$row_class = ( !($i % 2) ) ? altrow1 : altrow2;

			if ( $stat_lastupdated[$i] )
			{
				$template->assign_block_vars('stats.lastupdatedrow', array(
					'NAME' => '<a href="' . append_sid("weblog.$phpEx?" . POST_WEBLOG_URL . "=$stat_lastupdated_id[$i]") . '" class="nav">' . $stat_lastupdated_name[$i] . '</a>',
					'OWNER' => $stat_lastupdated_owner[$i],
					'LASTPOST' => '<a href="' . append_sid("weblog_entry.$phpEx?" . POST_ENTRY_URL . "=$stat_lastupdated_topic_id[$i]") . '" class="nav">' . $stat_lastupdated_topic_title[$i] . '</a>',
					'DATE' => create_date($board_config['default_dateformat'], $stat_lastupdated_date[$i], $board_config['board_timezone']),
					'VISITS' => $stat_lastupdated_visits[$i],
					'NUMBER' => $stat_lastupdated_topics[$i],
					'CLASS' => $row_class)
				);
			}
			else
			{
				$template->assign_block_vars('stats.lastupdatedrow', array(
					'NAME' => '&nbsp;',
					'OWNER' => '&nbsp;',
					'DATE' => '&nbsp;',
					'VISITS' => '&nbsp;',
					'NUMBER' => '&nbsp;',
					'CLASS' => $row_class)
				);
			}
		}
	}

	//
	// Find which forums are visible for this user
	//
	$is_auth_ary = array();
	$is_auth_ary = auth(AUTH_READ, AUTH_LIST_ALL, $userdata, $weblog_data);

	//
	// Get Weblog Data
	//
	$sql = "SELECT f.*, p.post_time, p.post_username, u.*
		FROM (( " . CATEGORIES_TABLE . " c
			INNER JOIN " . FORUMS_TABLE . " f ON f.cat_id = c.cat_id
	      	INNER JOIN " . USERS_TABLE . " u2 ON u2.user_weblog = f.forum_id
			LEFT JOIN " . POSTS_TABLE . " p ON p.post_id = f.forum_last_post_id )
			LEFT JOIN " . USERS_TABLE . " u ON u.user_id = p.poster_id )
		WHERE c.cat_allow_weblogs = 1
			AND u.weblog_visible <> 0
		ORDER BY p.post_username";

	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query forums information', '', __LINE__, __FILE__, $sql);
	}

	while( $row = $db->sql_fetchrow($result) )
	{
		$weblog_data[] = $row;
	}
	$total_forums = count($weblog_data);
	$weblog_owner_data = array();
	$topic_data = array();

	for ($i = 0; $i < $total_forums; $i++)
	{
		$weblog_id = $weblog_data[$i]['forum_id'];
		//
		// Get Weblog Owner Data
		//
		$sql = "SELECT u.user_avatar_type, u.user_allowavatar, u.user_avatar, u.user_id, u.username
			FROM " . AUTH_ACCESS_TABLE . " aa, " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE . " g, " . USERS_TABLE . " u
			WHERE aa.forum_id = $weblog_id
				AND aa.auth_mod = " . TRUE . "
				AND g.group_single_user = 1
				AND ug.group_id = aa.group_id
				AND g.group_id = aa.group_id
				AND u.user_id = ug.user_id
			ORDER BY aa.forum_id";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not query forum moderator information', '', __LINE__, __FILE__, $sql);
		}

		while( $row = $db->sql_fetchrow($result) )
		{
			$weblog_owner_data[$i] = $row;
		}

		$access_level = 0;
		if ( $userdata['session_logged_in'] )
		{
			$access_level++;
			$group_id = intval($weblog_owner_data[$i]['weblog_group']);
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
				$access_level++;
			if ( $weblog_owner_data[$i]['user_id'] == $userdata['user_id'] || $userdata['user_level'] == ADMIN )
				$access_level++;
		}

		//
		// Get Topic Data
		//
		$sql = "SELECT f.forum_id, t.forum_id, t.topic_time, t.topic_mood, t.topic_currently1, t.topic_currently2, t.topic_time, t.topic_id
			FROM " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f, " . POSTS_TABLE . " p
			WHERE t.forum_id = $weblog_id
				AND t.forum_id = f.forum_id
				AND t.topic_auth <= " . $access_level . "
			LIMIT ".$total_forums_stat;
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not query forums information', '', __LINE__, __FILE__, $sql);
		}

		while( $row = $db->sql_fetchrow($result) )
		{
			$topic_data[$i] = $row;
		}
	}
	$type_order=$type;
	if($type=="ASC")
	$type="DESC";
	else
	$type="ASC";

/*	 $pagination = generate_pagination("weblog_alltopics.$phpEx?mode=$mode&amp;order=$sort_order", $total_members, $board_config['topics_per_page'], $start). '&nbsp;';&
	    'PAGINATION' => $pagination, */

	$template->assign_vars(array(
		'FORUM_IMG' => $images['forum'],
		'FORUM_NEW_IMG' => $images['forum_new'],
		'FORUM_LOCKED_IMG' => $images['forum_locked'],
		'L_WEBLOG_STATS' => $lang['Weblog_stats'],
		'L_BLOG_LIST' => $lang['Blog_list'],
		'L_WEBLOG_NAME' => '<a class=headerlink1 href="weblog_alltopics.'.$phpEx.'?sorder=forum_name&type='.$type.'">'.$lang['Weblog_name'].'</a> <a href="weblog_alltopics.'.$phpEx.'?sorder=forum_name&type='.$type.'">'.swapImage($sorder,"forum_name",$type).'</a>',
		'L_WEBLOG_NUMBER' => '<a class=headerlink1 href="weblog_alltopics.'.$phpEx.'?sorder=forum_topics&type='.$type.'">'.$lang['Weblog_number'].'</a> <a href="weblog_alltopics.'.$phpEx.'?sorder=forum_topics&type='.$type.'">'.swapImage($sorder,"forum_topics",$type).'</a>',
		'L_WEBLOG_OWNER' => '<a class=headerlink1 href="weblog_alltopics.'.$phpEx.'?sorder=username&type='.$type.'">'.$lang['Weblog_owner2'].'</a> <a href="weblog_alltopics.'.$phpEx.'?sorder=username&type='.$type.'">'.swapImage($sorder,"username",$type).'</a>',
		'L_WEBLOG_TITLE' => '<a class=headerlink1 href="weblog_alltopics.'.$phpEx.'?sorder=topic_title&type='.$type.'">'.$lang['Weblog_number'].'</a> <a href="weblog_alltopics.'.$phpEx.'?sorder=topic_title&type='.$type.'">'.swapImage($sorder,"topic_title",$type).'</a>',
		'L_WEBLOG_DATE' => '<a class=headerlink1 href="weblog_alltopics.'.$phpEx.'?sorder=topic_time&type='.$type.'">'.$lang['Weblog_date'].'</a> <a href="weblog_alltopics.'.$phpEx.'?sorder=topic_time&type='.$type.'">'.swapImage($sorder,"topic_time",$type).'</a>',
		'L_WEBLOG_VISITS' => '<a class=headerlink1 href="weblog_alltopics.'.$phpEx.'?sorder=topic_views&type='.$type.'">'.$lang['Weblog_visits'].'</a> <a href="weblog_alltopics.'.$phpEx.'?sorder=topic_views&type='.$type.'">'.swapImage($sorder,"topic_views",$type).'</a>',
		'L_PERPAGE' => $lang['Weblog_per_page'],
		'L_GO' => $lang['Weblog_go'],
		'L_SORT' => $lang['Weblog_sort'],

		'MOD_VERSION' => $mod_version,

		'L_LASTPOST' => $lang['Last_Post'],
		'L_WEBLOGS' => $lang['Weblogs'],
		'L_WEBLOG' => $lang['Weblog'],
		'L_LASTENTRY' => $lang['Weblog_last_entry'],
		'L_LATESTMOOD' => $lang['Weblog_latest_mood'],
		'L_LASTSEEN' => $lang['Weblog_last_seen'],
		'L_VIEW' => $lang['View'])
	);

	//
	// Start output of page
	//
	define('IN_WEBLOGS', true);      //added by isaac for blog_header
	$page_title = $lang['Weblog_Index'];
	include($phpbb_root_path . 'includes/page_header.'.$phpEx);

	$template->set_filenames(array(
		'body' => 'weblog_users_topic_body.tpl')
	);

	//
	// Okay, let's build the index
	//
	for($i = 0; $i < $total_categories; $i++)
	{
		$cat_id = $category_rows[$i]['cat_id'];
		//
		// Should we display this category/forum set?
		//
		$display_forums = false;
		for($j = 0; $j < $total_forums; $j++)
		{
			if ( $weblog_data[$j]['cat_id'] == $cat_id )
			{
				$display_forums = true;
			}
		}

		//
		// Yes, we should, so first dump out the category
		// title, then, if appropriate the forum list
		//
		if ( $display_forums )
		{
			$template->assign_block_vars('catrow', array(
				'CAT_ID' => $cat_id,
				'CAT_DESC' => $category_rows[$i]['cat_title'],
                )
            );

		for($j = 0; $j < $total_forums; $j++)
		{
			if ( $weblog_data[$j]['cat_id'] == $cat_id )
			{
				$weblog_id = $weblog_data[$j]['forum_id'];

					if ( $weblog_data[$j]['forum_status'] == FORUM_LOCKED )
					{
						$folder_image = $images['forum_locked'];
						$folder_alt = $lang['Weblog_locked'];
					}
					else
					{
						$unread_topics = false;
						if ( $userdata['session_logged_in'] )
						{
							if ( !empty($new_topic_data[$weblog_id]) )
							{
								$forum_last_post_time = 0;

								while( list($check_topic_id, $check_post_time) = @each($new_topic_data[$weblog_id]) )
								{
									if ( empty($tracking_topics[$check_topic_id]) )
									{
										$unread_topics = true;
										$forum_last_post_time = max($check_post_time, $forum_last_post_time);
									}
									else
									{
										if ( $tracking_topics[$check_topic_id] < $check_post_time )
										{
											$unread_topics = true;
											$forum_last_post_time = max($check_post_time, $forum_last_post_time);
										}
									}
								}

								if ( !empty($tracking_forums[$weblog_id]) )
								{
									if ( $tracking_forums[$weblog_id] > $forum_last_post_time )
									{
										$unread_topics = false;
									}
								}

								if ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f_all']) )
								{
									if ( $HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f_all'] > $forum_last_post_time )
									{
										$unread_topics = false;
									}
								}
							}
						}
						$folder_image = ( $unread_topics ) ? $images['forum_new'] : $images['forum'];
						$folder_alt = ( $unread_topics ) ? $lang['New_posts'] : $lang['No_new_posts'];
					}

					$currently1 = $topic_data[$j]['topic_currently1'] - 1;

					if ( $currently_data[$currently1]['currently'] != '(none)' && $currently_data[$currently1]['currently'] != '' && $currently1 > 0 )
					{
						$last_seen = '<img src="images/weblogs/' . $currently_data[$currently1]['image_url'] . '" border="0">&nbsp;' . $currently_data[$currently1]['currently'] . ' ' . $topic_data[$j]['topic_currently2'];
					}
					else
					{
						$last_seen = $currently_data[$currently1]['currently'];
					}
					$posts = $weblog_data[$j]['forum_posts'];
					$topics = $weblog_data[$j]['forum_topics'];

						if ( file_exists("weblogs/face_" . $weblog_owner_data[$j]['user_id'] . ".cfg") )
							$file1 = file("weblogs/face_" . $weblog_owner_data[$j]['user_id'] . ".cfg");
					}
			}
		}
	} // for ... categories
}// if ... total_categories
else
{
	message_die(GENERAL_MESSAGE, $lang['No_weblogs']);
}
//Pagination

	$base_url = "weblog_alltopics.php?sorder=$sort_order&type=$type_order";
	$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
//$start=0;
                $template->assign_vars(array(
                        'PAGINATION' => generate_pagination($base_url, $total_topic_stat, $per_page, $start),
                        'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $per_page ) + 1 ), ceil( $total_topic_stat / $per_page )),

                        'L_AUTHOR' => $lang['Author'],
                        'L_MESSAGE' => $lang['Message'],
                        'L_FORUM' => $lang['Forum'],
                        'L_TOPICS' => $lang['Topics'],
                        'L_REPLIES' => $lang['Replies'],
                        'L_VIEWS' => $lang['Views'],
                        'L_POSTS' => $lang['Posts'],
                        'L_LASTPOST' => $lang['Last_Post'],
                        'L_POSTED' => $lang['Posted'],
                        'L_SUBJECT' => $lang['Subject'],

                        'L_GOTO_PAGE' => $lang['Goto_page'])
                );

//
// Generate the page
//
$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>



